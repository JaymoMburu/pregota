<?php

namespace App\Http\Controllers;

use App\Models\BulkGift;
use App\Services\BulkGiftService;
use Illuminate\Http\Request;

class BulkGiftController extends Controller
{
    public function __construct(private BulkGiftService $service) {}

    public function page()
    {
        $maxAmount  = (int) config('pregota.max_amount');
        $minAmount  = (int) config('pregota.min_amount');
        $feeInPct   = (float) config('pregota.fee_in_pct');
        $feeOutPct  = (float) config('pregota.fee_out_pct');

        return view('gift.bulk', compact('maxAmount', 'minAmount', 'feeInPct', 'feeOutPct'));
    }

    public function initiate(Request $request)
    {
        $data = $request->validate([
            'company_name'    => 'required|string|max:200',
            'contact_name'    => 'required|string|max:200',
            'phone'           => 'required|string|min:9|max:13',
            'amount_per_code' => 'required|integer|min:' . config('pregota.min_amount'),
            'code_count'      => 'required|integer|min:1|max:500',
        ]);

        $amountPerCode = (float) $data['amount_per_code'];
        $count         = (int)   $data['code_count'];
        $fees          = $this->service->calculateFees($count, $amountPerCode);
        $maxAmount     = (int) config('pregota.max_amount');

        if ($fees['grossTotal'] > $maxAmount) {
            return response()->json([
                'success' => false,
                'message' => 'Total exceeds M-Pesa limit of KES ' . number_format($maxAmount) . '. Reduce number of codes or amount per code.',
            ], 422);
        }

        $phone = $this->normalisePhone($data['phone']);
        if (! $phone) {
            return response()->json(['success' => false, 'message' => 'Invalid phone number.'], 422);
        }

        $bulk = $this->service->initiate(
            $data['company_name'],
            $data['contact_name'],
            $count,
            $amountPerCode,
            $phone,
        );

        return response()->json([
            'success'   => true,
            'reference' => $bulk->reference,
        ]);
    }

    public function status(Request $request)
    {
        $ref  = $request->query('ref', '');
        $bulk = BulkGift::where('reference', strtoupper($ref))->first();

        if (! $bulk) {
            return response()->json(['status' => 'not_found'], 404);
        }

        $response = ['status' => $bulk->status, 'reference' => $bulk->reference];

        if ($bulk->isActive()) {
            $response['codes'] = $bulk->vouchers()
                ->select('code', 'payout_amount', 'expires_at')
                ->get()
                ->map(fn($v) => [
                    'code'    => $v->code,
                    'value'   => (int) $v->payout_amount,
                    'expires' => $v->expires_at?->format('d M Y'),
                ])
                ->all();
        }

        return response()->json($response);
    }

    public function download(Request $request)
    {
        $ref  = $request->query('ref', '');
        $bulk = BulkGift::where('reference', strtoupper($ref))->where('status', 'active')->first();

        if (! $bulk) abort(404);

        $vouchers = $bulk->vouchers()->select('code', 'payout_amount', 'expires_at')->get();

        $rows = "Code,Recipient Gets (KES),Expires\n";
        foreach ($vouchers as $v) {
            $rows .= "{$v->code}," . (int) $v->payout_amount . "," . ($v->expires_at?->format('d M Y') ?? '') . "\n";
        }

        return response($rows, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $bulk->reference . '-codes.csv"',
        ]);
    }

    private function normalisePhone(string $raw): ?string
    {
        $clean = preg_replace('/\D/', '', $raw);
        if (strlen($clean) === 9 && str_starts_with($clean, '7') || str_starts_with($clean, '1')) {
            $clean = '254' . $clean;
        } elseif (strlen($clean) === 10 && str_starts_with($clean, '0')) {
            $clean = '254' . substr($clean, 1);
        }
        return (strlen($clean) === 12 && str_starts_with($clean, '254')) ? $clean : null;
    }
}
