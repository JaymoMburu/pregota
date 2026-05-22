<?php

namespace App\Http\Controllers;

use App\Models\DirectGift;
use App\Models\Partner;
use App\Models\PartnerClick;
use App\Models\Voucher;
use App\Services\DirectGiftService;
use App\Services\VoucherService;
use Illuminate\Http\Request;

class GiftController extends Controller
{
    public function __construct(
        private VoucherService $vouchers,
        private DirectGiftService $directGifts,
    ) {}

    public function home()
    {
        return view('home');
    }

    public function giftPage()
    {
        return view('gift.home');
    }

    public function redeem()
    {
        return view('gift.redeem');
    }

    public function track()
    {
        return view('gift.track');
    }

    public function initiate(Request $request)
    {
        $data = $request->validate([
            'amount'      => ['required', 'numeric', 'min:' . config('pregota.min_amount'), 'max:' . config('pregota.max_amount')],
            'phone'       => ['required', 'string', 'regex:/^(\+?254|0)[17]\d{8}$/'],
            'message'     => ['nullable', 'string', 'max:160'],
            'sender_name' => ['nullable', 'string', 'max:60'],
        ]);

        // amount = gift amount (what recipient gets); VoucherService computes gross internally
        $voucher = $this->vouchers->initiate((float) $data['amount'], $data['phone'], $data['message'] ?? null, $data['sender_name'] ?? null);

        return response()->json([
            'success'       => true,
            'voucher_code'  => $voucher->code,
            'recall_token'  => $voucher->recall_token,
            'checkout_id'   => $voucher->mpesa_checkout_id,
            'gross_amount'  => $voucher->gross_amount,
            'payout'        => $voucher->payout_amount,
            'message'       => 'STK Push sent. Please enter your M-Pesa PIN.',
        ]);
    }

    public function checkStatus(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        $voucher = Voucher::where('code', strtoupper($request->code))->first();

        if (! $voucher) {
            return response()->json(['status' => 'not_found']);
        }

        [$inHold, $holdSeconds] = $this->holdState($voucher);

        return response()->json([
            'status'       => $voucher->status,
            'face_value'   => $voucher->face_value,
            'expires_at'   => $voucher->expires_at?->toISOString(),
            'in_hold'      => $inHold,
            'hold_seconds' => $holdSeconds,
        ]);
    }

    public function verifyCode(Request $request)
    {
        $data    = $request->validate(['code' => ['required', 'string', 'max:20']]);
        $voucher = Voucher::where('code', strtoupper(trim($data['code'])))->first();

        if (! $voucher) {
            return response()->json(['found' => false, 'message' => 'Gift code not found.']);
        }

        if ($voucher->status === 'redeemed') {
            return response()->json(['found' => true, 'valid' => false, 'message' => 'This gift has already been redeemed.']);
        }

        if ($voucher->isExpired() || $voucher->status === 'expired') {
            return response()->json(['found' => true, 'valid' => false, 'message' => 'This gift code has expired.']);
        }

        if ($voucher->status === 'cancelled') {
            return response()->json(['found' => true, 'valid' => false, 'message' => 'This gift code was cancelled.']);
        }

        if ($voucher->status === 'pending') {
            return response()->json(['found' => true, 'valid' => false, 'message' => 'Payment not confirmed yet. Please try again shortly.']);
        }

        [$inHold, $holdSeconds] = $this->holdState($voucher);

        $partners = $inHold ? [] : Partner::active()->get()->groupBy('category');

        return response()->json([
            'found'         => true,
            'valid'         => true,
            'in_hold'       => $inHold,
            'hold_seconds'  => $holdSeconds,
            'payout_amount' => $voucher->payout_amount,
            'has_message'   => (bool) $voucher->message,
            'sender_name'   => $voucher->sender_name,
            'gift_msg'      => $voucher->message,
            'partners'      => $partners,
        ]);
    }

    public function partnerRedirect(Request $request, Partner $partner)
    {
        $code = $request->query('code');

        PartnerClick::create([
            'partner_id'   => $partner->id,
            'voucher_code' => $code ? strtoupper($code) : null,
            'ip'           => $request->ip(),
        ]);

        return redirect($partner->url);
    }

    public function trackStatus(Request $request)
    {
        $data    = $request->validate(['code' => ['required', 'string', 'max:20']]);
        $voucher = Voucher::where('code', strtoupper(trim($data['code'])))->first();

        if (! $voucher) {
            return response()->json(['found' => false]);
        }

        $expiresSeconds = ($voucher->status === 'active' && $voucher->expires_at)
            ? max(0, (int) now()->diffInSeconds($voucher->expires_at, false))
            : 0;

        [$inHold, $holdSeconds] = $this->holdState($voucher);

        return response()->json([
            'found'           => true,
            'status'          => $voucher->status,
            'in_hold'         => $inHold,
            'hold_seconds'    => $holdSeconds,
            'face_value'      => $voucher->face_value,
            'payout_amount'   => $voucher->payout_amount,
            'created_at'      => $voucher->created_at->format('d M Y, H:i'),
            'expires_at'      => $voucher->expires_at?->format('d M Y, H:i'),
            'redeemed_at'     => $voucher->redeemed_at?->format('d M Y, H:i'),
            'expires_seconds' => $expiresSeconds,
        ]);
    }

    public function claim(Request $request)
    {
        $data = $request->validate([
            'code'  => ['required', 'string'],
            'phone' => ['required', 'string', 'regex:/^(\+?254|0)[17]\d{8}$/'],
        ]);

        $result = $this->vouchers->claim($data['code'], $data['phone']);

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    private function holdState(Voucher $voucher): array
    {
        if ($voucher->status !== 'active' || ! $voucher->activated_at) {
            return [false, 0];
        }
        $claimableAt = $voucher->activated_at->addMinutes(config('pregota.hold_minutes', 5));
        if ($claimableAt->lte(now())) {
            return [false, 0];
        }
        return [true, max(0, (int) now()->diffInSeconds($claimableAt, false))];
    }

    public function directInitiate(Request $request)
    {
        $data = $request->validate([
            'amount'          => ['required', 'numeric', 'min:' . config('pregota.min_amount'), 'max:' . config('pregota.max_amount')],
            'sender_phone'    => ['required', 'string', 'regex:/^(\+?254|0)[17]\d{8}$/'],
            'recipient_phone' => ['required', 'string', 'regex:/^(\+?254|0)[17]\d{8}$/'],
        ]);

        if ($data['sender_phone'] === $data['recipient_phone']) {
            return response()->json(['success' => false, 'message' => 'Sender and recipient phone numbers cannot be the same.'], 422);
        }

        $gift = $this->directGifts->initiate(
            (float) $data['amount'],
            $data['sender_phone'],
            $data['recipient_phone']
        );

        return response()->json([
            'success'      => true,
            'gift_id'      => $gift->id,
            'checkout_id'  => $gift->mpesa_checkout_id,
            'gross_amount' => $gift->gross_amount,
            'gift_amount'  => $gift->gift_amount,
            'message'      => 'STK Push sent. Please enter your M-Pesa PIN.',
        ]);
    }

    public function directStatus(Request $request)
    {
        $request->validate(['gift_id' => 'required|integer']);
        $gift = DirectGift::find($request->gift_id);

        if (! $gift) return response()->json(['status' => 'not_found']);

        return response()->json(['status' => $gift->status]);
    }

    public function recall(Request $request)
    {
        $data = $request->validate([
            'code'         => ['required', 'string', 'max:20'],
            'recall_token' => ['required', 'string', 'max:15'],
            'phone'        => ['required', 'string', 'regex:/^(\+?254|0)[17]\d{8}$/'],
        ]);

        $result = $this->vouchers->recall($data['code'], $data['recall_token'], $data['phone']);

        return response()->json($result, $result['success'] ? 200 : 422);
    }
}
