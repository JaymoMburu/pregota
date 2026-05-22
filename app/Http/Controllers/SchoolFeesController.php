<?php

namespace App\Http\Controllers;

use App\Models\FraudReport;
use App\Models\SchoolClass;
use App\Models\SchoolCollection;
use App\Models\SchoolPayment;
use App\Services\SchoolFeesService;
use Illuminate\Http\Request;

class SchoolFeesController extends Controller
{
    public function __construct(private SchoolFeesService $service) {}

    public function create()
    {
        return view('school-collection.new');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'school_name'            => ['required', 'string', 'max:120'],
            'term_label'             => ['required', 'string', 'max:60'],
            'amount_per_student'     => ['required', 'integer', 'min:50'],
            'admin_name'             => ['required', 'string', 'max:60'],
            'recipient_phone'        => ['required', 'string', 'regex:/^(\+?254|0)[17]\d{8}$/'],
            'classes'                => ['required', 'array', 'min:1'],
            'classes.*.class_name'   => ['required', 'string', 'max:60'],
            'classes.*.teacher_name' => ['required', 'string', 'max:60'],
        ]);

        $collection = $this->service->create($data, $data['classes']);

        return redirect()->route('school-collection.verify', ['slug' => $collection->slug])
            ->with('admin_token', $collection->admin_token);
    }

    public function verify(Request $request, string $slug)
    {
        $collection = SchoolCollection::where('slug', $slug)->firstOrFail();
        $adminToken = session('admin_token') ?? $request->query('token');
        abort_unless($adminToken === $collection->admin_token, 403);

        if ($collection->phone_verified) {
            return redirect()->route('school-collection.admin', [
                'slug'  => $slug,
                'token' => $adminToken,
            ]);
        }

        return view('school-collection.verify', compact('collection', 'adminToken'));
    }

    public function verifyStatus(Request $request, string $slug)
    {
        $collection = SchoolCollection::where('slug', $slug)->firstOrFail();
        return response()->json(['verified' => (bool) $collection->phone_verified]);
    }

    public function resendVerification(Request $request, string $slug)
    {
        $collection = SchoolCollection::where('slug', $slug)->firstOrFail();
        abort_unless($request->query('token') === $collection->admin_token, 403);

        if ($collection->phone_verified) {
            return response()->json(['already_verified' => true]);
        }

        $this->service->resendVerification($collection, $collection->getRecipientPhone());
        return response()->json(['sent' => true]);
    }

    public function admin(Request $request, string $slug)
    {
        $collection = SchoolCollection::where('slug', $slug)
            ->with('classes.payments')
            ->firstOrFail();

        abort_unless($request->query('token') === $collection->admin_token, 403);

        return view('school-collection.admin', compact('collection'));
    }

    public function payout(Request $request, string $slug)
    {
        $collection = SchoolCollection::where('slug', $slug)->firstOrFail();

        $request->validate(['token' => 'required|string']);
        abort_unless($request->token === $collection->admin_token, 403);

        if (! $collection->isOpen()) {
            return back()->with('error', 'Collection is already closed or paid out.');
        }
        if ($collection->total_raised === 0) {
            return back()->with('error', 'No payments to pay out yet.');
        }

        $success = $this->service->payout($collection);

        return back()->with(
            $success ? 'success' : 'error',
            $success
                ? 'KES ' . number_format($collection->fresh()->total_raised) . ' payout sent to school M-Pesa.'
                : 'Payout failed. Please try again or contact support.'
        );
    }

    public function close(Request $request, string $slug)
    {
        $collection = SchoolCollection::where('slug', $slug)->firstOrFail();

        $request->validate(['token' => 'required|string']);
        abort_unless($request->token === $collection->admin_token, 403);

        $collection->update(['status' => 'closed']);
        return back()->with('success', 'Collection closed. No new payments will be accepted.');
    }

    public function classPage(Request $request, string $slug, string $classToken)
    {
        $collection = SchoolCollection::where('slug', $slug)->firstOrFail();
        $class      = SchoolClass::where('school_collection_id', $collection->id)
            ->where('class_token', $classToken)
            ->firstOrFail();

        $payments = $class->paidPayments()->latest('paid_at')->get();

        return view('school-collection.class', compact('collection', 'class', 'payments'));
    }

    public function teacherView(Request $request, string $slug, string $teacherToken)
    {
        $collection = SchoolCollection::where('slug', $slug)->firstOrFail();
        $class      = SchoolClass::where('school_collection_id', $collection->id)
            ->where('teacher_token', $teacherToken)
            ->firstOrFail();

        $paidPayments = $class->paidPayments()->latest('paid_at')->get();
        $pending      = $class->payments()->where('status', 'pending')->latest()->get();

        $required = $collection->amount_per_student;

        $studentTotals = $paidPayments->groupBy(function ($p) {
            return $p->student_id ?: 'name:' . strtolower(trim($p->student_name));
        })->map(function ($payments) use ($required) {
            $totalPaid = $payments->sum('amount');
            return [
                'student_id' => $payments->first()->student_id,
                'name'       => $payments->first()->student_name,
                'total_paid' => $totalPaid,
                'balance'    => max(0, $required - $totalPaid),
                'is_full'    => $totalPaid >= $required,
                'last_paid'  => $payments->max('paid_at'),
                'payments'   => $payments,
            ];
        })->sortByDesc('is_full')->values();

        $fullCount    = $studentTotals->where('is_full', true)->count();
        $partialCount = $studentTotals->where('is_full', false)->count();

        return view('school-collection.teacher', compact(
            'collection', 'class', 'paidPayments', 'pending',
            'studentTotals', 'fullCount', 'partialCount'
        ));
    }

    public function studentBalance(Request $request)
    {
        $request->validate([
            'slug'        => 'required|string',
            'class_token' => 'required|string',
            'student_id'  => 'required|string|max:40',
        ]);

        $collection = SchoolCollection::where('slug', $request->slug)->firstOrFail();
        $class      = SchoolClass::where('school_collection_id', $collection->id)
            ->where('class_token', $request->class_token)
            ->firstOrFail();

        $payments = $class->payments()
            ->where('status', 'paid')
            ->whereRaw('UPPER(student_id) = ?', [strtoupper(trim($request->student_id))])
            ->get();

        $totalPaid = $payments->sum('amount');
        $required  = $collection->amount_per_student;
        $balance   = max(0, $required - $totalPaid);

        return response()->json([
            'total_paid'  => (int) $totalPaid,
            'balance'     => (int) $balance,
            'required'    => $required,
            'is_complete' => $balance === 0 && $totalPaid > 0,
            'known_name'  => $payments->last()?->student_name,
        ]);
    }

    public function pay(Request $request, string $slug, string $classToken)
    {
        $collection = SchoolCollection::where('slug', $slug)->firstOrFail();
        $class      = SchoolClass::where('school_collection_id', $collection->id)
            ->where('class_token', $classToken)
            ->firstOrFail();

        if ($collection->is_frozen) {
            return response()->json(['success' => false, 'message' => 'This collection has been suspended pending review.'], 422);
        }
        if (! $collection->phone_verified) {
            return response()->json(['success' => false, 'message' => 'This collection is pending verification.'], 422);
        }
        if (! $collection->isOpen()) {
            return response()->json(['success' => false, 'message' => 'This collection is no longer accepting payments.'], 422);
        }

        $data = $request->validate([
            'student_id'   => ['required', 'string', 'max:40'],
            'student_name' => ['required', 'string', 'max:80'],
            'amount'       => ['required', 'integer', 'min:50'],
            'phone'        => ['required', 'string', 'regex:/^(\+?254|0)[17]\d{8}$/'],
        ]);

        $payment = $this->service->pay($class, $data['student_name'], $data['student_id'], (int) $data['amount'], $data['phone']);

        return response()->json([
            'success'      => true,
            'payment_id'   => $payment->id,
            'gross_amount' => $payment->gross_amount,
            'message'      => 'STK Push sent. Enter your M-Pesa PIN.',
        ]);
    }

    public function status(Request $request)
    {
        $request->validate(['payment_id' => 'required|integer']);
        $p = SchoolPayment::find($request->payment_id);

        if (! $p) return response()->json(['status' => 'not_found']);

        $extra = [];
        if ($p->status === 'paid') {
            $col   = $p->schoolClass->schoolCollection;
            $extra = [
                'class_total'  => $p->schoolClass->total_raised,
                'school_total' => $col->total_raised,
            ];
        }

        return response()->json(array_merge(['status' => $p->status], $extra));
    }

    public function reportFraud(Request $request, string $slug)
    {
        $collection = SchoolCollection::where('slug', $slug)->firstOrFail();

        $data = $request->validate(['reason' => ['required', 'string', 'max:300']]);

        FraudReport::create([
            'reportable_type' => SchoolCollection::class,
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
