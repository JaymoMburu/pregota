<?php

namespace App\Http\Controllers;

use App\Models\FeedbackTag;
use App\Models\StaffMember;
use App\Models\TipFeedback;
use App\Models\TipTransaction;
use App\Services\TipService;
use Illuminate\Http\Request;

class TipController extends Controller
{
    public function __construct(private TipService $tips) {}

    public function page(string $handle)
    {
        $staff = StaffMember::where('handle', $handle)
            ->where('active', true)
            ->with('business')
            ->firstOrFail();

        $feeWaived = $staff->business && $staff->business->isSubscribed();
        $flatFee   = $feeWaived ? 0 : (int) config('pregota.tip_fee_flat', 15);

        return view('tip.page', compact('staff', 'feeWaived', 'flatFee'));
    }

    public function initiate(Request $request, string $handle)
    {
        $staff = StaffMember::where('handle', $handle)->where('active', true)->firstOrFail();

        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:' . config('pregota.min_amount'), 'max:' . config('pregota.max_amount')],
            'phone'  => ['required', 'string', 'regex:/^(\+?254|0)[17]\d{8}$/'],
        ]);

        $tip = $this->tips->initiate((float) $data['amount'], $data['phone'], $staff);

        return response()->json([
            'success'      => true,
            'tip_id'       => $tip->id,
            'checkout_id'  => $tip->mpesa_checkout_id,
            'gross_amount' => $tip->gross_amount,
            'tip_amount'   => $tip->tip_amount,
            'message'      => 'STK Push sent. Please enter your M-Pesa PIN.',
        ]);
    }

    public function checkStatus(Request $request)
    {
        $request->validate(['tip_id' => 'required|integer']);
        $tip = TipTransaction::find($request->tip_id);

        if (! $tip) return response()->json(['status' => 'not_found']);

        return response()->json(['status' => $tip->status]);
    }

    public function submitFeedback(Request $request)
    {
        $data = $request->validate([
            'tip_id'  => ['required', 'integer'],
            'rating'  => ['required', 'integer', 'min:1', 'max:5'],
            'tags'    => ['nullable', 'array'],
            'tags.*'  => ['string', 'max:50'],
            'comment' => ['nullable', 'string', 'max:300'],
        ]);

        $tip = TipTransaction::find($data['tip_id']);

        if (! $tip || $tip->feedback_submitted) {
            return response()->json(['success' => false, 'message' => 'Feedback already submitted or tip not found.']);
        }

        TipFeedback::create([
            'tip_transaction_id' => $tip->id,
            'staff_member_id'    => $tip->staff_member_id,
            'rating'             => $data['rating'],
            'tags'               => $data['tags'] ?? [],
            'comment'            => $data['comment'] ?? null,
        ]);

        $tip->update(['feedback_submitted' => true]);

        return response()->json(['success' => true, 'message' => 'Thank you for your feedback!']);
    }

    public function tags(Request $request)
    {
        $category = $request->query('category', 'general');
        $tags = FeedbackTag::where('active', true)
            ->where(fn($q) => $q->where('category', $category)->orWhere('category', 'general'))
            ->orderBy('sort_order')
            ->get(['tag', 'emoji', 'category']);

        return response()->json($tags);
    }
}
