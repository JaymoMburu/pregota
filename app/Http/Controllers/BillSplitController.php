<?php

namespace App\Http\Controllers;

use App\Models\BillSplit;
use App\Models\BillSplitPayment;
use App\Models\CustomerOptIn;
use App\Models\StaffMember;
use App\Services\BillSplitService;
use Illuminate\Http\Request;

class BillSplitController extends Controller
{
    public function __construct(private BillSplitService $service) {}

    public function create()
    {
        return view('bill-split.new');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'business_name'      => ['required', 'string', 'max:80'],
            'label'              => ['nullable', 'string', 'max:60'],
            'total_amount'       => ['required', 'integer', 'min:100', 'max:150000'],
            'payout_type'        => ['required', 'in:paybill,till'],
            'payout_destination' => ['required', 'digits_between:5,7'],
            'tip_handle'         => ['nullable', 'string', 'max:30', 'regex:/^[a-z0-9._-]+$/',
                                     'exists:staff_members,handle'],
        ]);

        $bill = $this->service->create($data);

        return redirect()->route('bill-split.manage', $bill->waiter_token);
    }

    public function manage(string $waiterToken)
    {
        $bill = BillSplit::where('waiter_token', $waiterToken)
            ->with(['payments' => fn($q) => $q->where('status', 'paid')->latest()])
            ->firstOrFail();
        return view('bill-split.manage', compact('bill'));
    }

    public function show(string $splitToken)
    {
        $bill  = BillSplit::where('split_token', $splitToken)->firstOrFail();
        $staff = $bill->tip_handle
            ? StaffMember::where('handle', $bill->tip_handle)->where('active', true)->first()
            : null;
        return view('bill-split.show', compact('bill', 'staff'));
    }

    public function pay(Request $request, string $splitToken)
    {
        $bill = BillSplit::where('split_token', $splitToken)->firstOrFail();

        if (! $bill->isOpen()) {
            return response()->json(['success' => false, 'message' => 'This bill is no longer accepting payments.'], 422);
        }

        $remaining = $bill->remainingAmount();

        if ($remaining <= 0) {
            return response()->json(['success' => false, 'message' => 'Bill is already fully paid.'], 422);
        }

        $data = $request->validate([
            'amount' => ['required', 'integer', 'min:1', 'max:' . $remaining],
            'phone'  => ['required', 'string', 'regex:/^(\+?254|0)[17]\d{8}$/'],
        ]);

        try {
            $payment = $this->service->pay($bill, (int) $data['amount'], $data['phone']);
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }

        return response()->json([
            'success'    => true,
            'payment_id' => $payment->id,
            'message'    => 'STK Push sent. Enter your M-Pesa PIN.',
        ]);
    }

    public function paymentStatus(Request $request)
    {
        $request->validate(['payment_id' => 'required|integer']);
        $payment = BillSplitPayment::find($request->payment_id);

        if (! $payment) return response()->json(['status' => 'not_found']);

        $bill = $payment->billSplit;

        return response()->json([
            'status'      => $payment->status,
            'paid_amount' => $bill->paid_amount,
            'remaining'   => $bill->remainingAmount(),
            'progress'    => $bill->progressPct(),
            'settled'     => $bill->status === 'settled',
        ]);
    }

    public function optIn(Request $request, string $splitToken)
    {
        $bill = BillSplit::where('split_token', $splitToken)->firstOrFail();

        $data = $request->validate([
            'phone' => ['required', 'string', 'regex:/^(\+?254|0)[17]\d{8}$/'],
        ]);

        $optIn = new CustomerOptIn(['bill_split_id' => $bill->id]);
        $optIn->setPhone($data['phone']);
        $optIn->save();

        return response()->json(['success' => true]);
    }

    public function billStatus(string $token)
    {
        $bill = BillSplit::where('split_token', $token)
            ->orWhere('waiter_token', $token)
            ->with(['payments' => fn($q) => $q->where('status', 'paid')->latest()])
            ->firstOrFail();

        return response()->json([
            'paid_amount' => $bill->paid_amount,
            'remaining'   => $bill->remainingAmount(),
            'progress'    => $bill->progressPct(),
            'settled'     => $bill->status === 'settled',
            'total'       => $bill->total_amount,
            'payments'    => $bill->payments->map(fn($p) => [
                'amount' => $p->amount,
                'time'   => $p->updated_at->format('g:i A'),
            ]),
        ]);
    }
}
