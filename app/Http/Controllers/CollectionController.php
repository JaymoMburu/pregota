<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\CollectionContribution;
use App\Models\FraudReport;
use App\Services\CollectionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CollectionController extends Controller
{
    public function __construct(private CollectionService $service) {}

    public function create()
    {
        return view('collections.new');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'           => ['required', 'string', 'max:120'],
            'description'     => ['nullable', 'string', 'max:1500'],
            'occasion'        => ['required', 'in:bereavement,wedding,medical,farewell,education,other'],
            'organiser_name'  => ['required', 'string', 'max:60'],
            'organiser_phone' => ['nullable', 'string', 'regex:/^(\+?254|0)[17]\d{8}$/'],
            'recipient_name'  => ['required', 'string', 'max:60'],
            'recipient_phone' => ['required', 'string', 'regex:/^(\+?254|0)[17]\d{8}$/'],
            'target_amount'      => ['nullable', 'integer', 'min:100'],
            'per_person_amount'  => ['nullable', 'integer', 'min:50', 'max:' . config('pregota.max_amount')],
            'preset_amounts'     => ['nullable', 'array', 'max:4'],
            'preset_amounts.*'   => ['nullable', 'integer', 'min:50', 'max:' . config('pregota.max_amount')],
            'deadline'           => ['nullable', 'date', 'after:now'],
            'payout_trigger'  => ['required', 'in:target,deadline,manual'],
            'photo'           => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:4096'],
        ]);

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('collections', 'public');
        }

        $presets = array_values(array_filter(array_map('intval', $data['preset_amounts'] ?? []), fn($v) => $v >= 50));
        sort($presets);
        $data['preset_amounts'] = $presets ?: null;

        $collection = $this->service->create($data);

        return redirect()->route('collection.verify', ['slug' => $collection->slug])
            ->with('manage_token', $collection->manage_token);
    }

    public function verify(Request $request, string $slug)
    {
        $collection  = Collection::where('slug', $slug)->firstOrFail();
        $manageToken = session('manage_token') ?? $request->query('token');
        abort_unless($manageToken === $collection->manage_token, 403);

        if ($collection->phone_verified) {
            return redirect()->route('collection.manage', [
                'slug'  => $slug,
                'token' => $manageToken,
            ]);
        }

        return view('collections.verify', compact('collection', 'manageToken'));
    }

    public function verifyStatus(Request $request, string $slug)
    {
        $collection = Collection::where('slug', $slug)->firstOrFail();
        return response()->json(['verified' => (bool) $collection->phone_verified]);
    }

    public function resendVerification(Request $request, string $slug)
    {
        $collection = Collection::where('slug', $slug)->firstOrFail();
        abort_unless($request->query('token') === $collection->manage_token, 403);

        if ($collection->phone_verified) {
            return response()->json(['already_verified' => true]);
        }

        $this->service->resendVerification($collection, $collection->getRecipientPhone());
        return response()->json(['sent' => true]);
    }

    public function show(string $slug)
    {
        $collection    = Collection::where('slug', $slug)->firstOrFail();
        $contributions = $collection->paidContributions()
            ->latest()
            ->limit(50)
            ->get();

        return view('collections.show', compact('collection', 'contributions'));
    }

    public function contribute(Request $request, string $slug)
    {
        $collection = Collection::where('slug', $slug)->firstOrFail();

        if ($collection->is_frozen) {
            return response()->json(['success' => false, 'message' => 'This collection has been suspended pending review.'], 422);
        }
        if (! $collection->phone_verified) {
            return response()->json(['success' => false, 'message' => 'This collection is pending verification.'], 422);
        }
        if (! $collection->isOpen()) {
            return response()->json(['success' => false, 'message' => 'This collection is no longer accepting contributions.'], 422);
        }

        $data = $request->validate([
            'amount' => ['required', 'integer', 'min:50', 'max:' . config('pregota.max_amount')],
            'phone'  => ['required', 'string', 'regex:/^(\+?254|0)[17]\d{8}$/'],
            'name'   => ['nullable', 'string', 'max:60'],
        ]);

        $contribution = $this->service->contribute(
            $collection,
            (int) $data['amount'],
            $data['phone'],
            $data['name'] ?? null
        );

        return response()->json([
            'success'         => true,
            'contribution_id' => $contribution->id,
            'gross_amount'    => $contribution->gross_amount,
            'message'         => 'STK Push sent. Enter your M-Pesa PIN.',
        ]);
    }

    public function status(Request $request)
    {
        $request->validate(['contribution_id' => 'required|integer']);
        $c = CollectionContribution::find($request->contribution_id);

        if (! $c) return response()->json(['status' => 'not_found']);

        $extra = [];
        if ($c->status === 'paid') {
            $col   = $c->collection;
            $extra = [
                'total_raised'      => $col->total_raised,
                'contributor_count' => $col->contributor_count,
                'collection_paid'   => $col->isPaid(),
                'progress_pct'      => $col->progressPct(),
            ];
        }

        return response()->json(array_merge(['status' => $c->status], $extra));
    }

    public function manage(Request $request, string $slug)
    {
        $collection = Collection::where('slug', $slug)->firstOrFail();
        abort_unless($request->query('token') === $collection->manage_token, 403);

        $contributions = $collection->contributions()
            ->orderByDesc('created_at')
            ->get();

        return view('collections.manage', compact('collection', 'contributions'));
    }

    public function payout(Request $request, string $slug)
    {
        $collection = Collection::where('slug', $slug)->firstOrFail();

        $request->validate(['token' => 'required|string']);
        abort_unless($request->token === $collection->manage_token, 403);

        if (! $collection->isOpen()) {
            return back()->with('error', 'Collection is already closed or paid out.');
        }

        if ($collection->total_raised === 0) {
            return back()->with('error', 'No contributions to pay out yet.');
        }

        $success = $this->service->payout($collection);

        return back()->with(
            $success ? 'success' : 'error',
            $success
                ? 'KES ' . number_format($collection->fresh()->total_raised) . ' payout initiated to ' . $collection->recipient_name . '.'
                : 'Payout failed. Please try again or contact support.'
        );
    }

    public function close(Request $request, string $slug)
    {
        $collection = Collection::where('slug', $slug)->firstOrFail();

        $request->validate(['token' => 'required|string']);
        abort_unless($request->token === $collection->manage_token, 403);

        if (! $collection->isOpen()) {
            return back()->with('error', 'Collection is already closed.');
        }

        $collection->update(['status' => 'closed']);

        return back()->with('success', 'Collection closed. No new contributions will be accepted.');
    }

    public function reportFraud(Request $request, string $slug)
    {
        $collection = Collection::where('slug', $slug)->firstOrFail();

        $data = $request->validate(['reason' => ['required', 'string', 'max:300']]);

        FraudReport::create([
            'reportable_type' => Collection::class,
            'reportable_id'   => $collection->id,
            'reason'          => $data['reason'],
        ]);

        $collection->update([
            'is_frozen'    => true,
            'freeze_reason' => 'Reported: ' . $data['reason'],
        ]);

        return response()->json(['frozen' => true]);
    }
}
