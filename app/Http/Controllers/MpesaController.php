<?php

namespace App\Http\Controllers;

use App\Models\BillSplitPayment;
use App\Models\BulkGift;
use App\Models\GroupPayment;
use App\Models\Subscription;
use App\Models\Collection;
use App\Models\CollectionContribution;
use App\Models\CreatorGift;
use App\Models\DirectGift;
use App\Models\LedgerEntry;
use App\Models\MultiGift;
use App\Models\SchoolCollection;
use App\Models\SchoolPayment;
use App\Models\SellerPayment;
use App\Models\TipTransaction;
use App\Models\Voucher;
use App\Services\BillSplitService;
use App\Services\BulkGiftService;
use App\Services\CollectionService;
use App\Services\DirectGiftService;
use App\Services\MultiGiftService;
use App\Services\SchoolFeesService;
use App\Services\SellerService;
use App\Services\TipService;
use App\Services\TxHashService;
use App\Services\VoucherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MpesaController extends Controller
{
    public function __construct(
        private VoucherService $vouchers,
        private BulkGiftService $bulkGifts,
        private CreatorController $creators,
        private TipService $tips,
        private DirectGiftService $directGifts,
        private CollectionService $collections,
        private SchoolFeesService $schoolFees,
        private BillSplitService $billSplits,
        private MultiGiftService $multiGifts,
        private SellerService $sellers,
        private TxHashService $txHash,
    ) {}

    public function stkCallback(Request $request)
    {
        $body     = $request->all();
        $callback = $body['Body']['stkCallback'] ?? null;

        Log::info('STK Callback received', ['result_code' => $callback['ResultCode'] ?? 'unknown']);

        if (! $callback) {
            return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
        }

        $checkoutId = $callback['CheckoutRequestID'];
        $resultCode = $callback['ResultCode'];

        if ($resultCode === 0) {
            $items     = collect($callback['CallbackMetadata']['Item'] ?? []);
            $amount    = (float) ($items->firstWhere('Name', 'Amount')['Value'] ?? 0);
            $mpesaCode = $items->firstWhere('Name', 'MpesaReceiptNumber')['Value'] ?? '';

            // Phone verification callbacks — handle before all other checks
            if (SchoolCollection::where('verification_checkout_id', $checkoutId)->exists()) {
                $this->schoolFees->confirmVerification($checkoutId);
                return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
            }
            if (Collection::where('verification_checkout_id', $checkoutId)->exists()) {
                $this->collections->confirmVerification($checkoutId);
                return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
            }

            // MultiGift check (before others — has distinct reference format)
            $handled = $this->multiGifts->confirmPayment($checkoutId, $mpesaCode, $amount);
            if ($handled) {
                return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
            }

            // Tip → Creator → DirectGift → Collection → SchoolFees → Voucher
            $handled = $this->tips->confirmPayment($checkoutId, $mpesaCode, $amount);
            if ($handled) { $this->seal($handled, 'TIP'); }
            else {
                $handled = $this->creators->confirmGift($checkoutId, $mpesaCode, $amount);
                if ($handled) { $this->seal($handled, 'CREATOR_GIFT'); }
                else {
                    $handled = $this->directGifts->confirmPayment($checkoutId, $mpesaCode, $amount);
                    if ($handled) { $this->seal($handled, 'DIRECT_GIFT'); }
                    else {
                        $handled = $this->collections->confirmContribution($checkoutId, $mpesaCode);
                        if ($handled) { $this->seal($handled, 'COLLECTION'); }
                        else {
                            $handled = $this->schoolFees->confirmPayment($checkoutId, $mpesaCode);
                            if ($handled) { $this->seal($handled, 'SCHOOL'); }
                            else {
                                $handled = $this->billSplits->confirmPayment($checkoutId, $mpesaCode);
                                if ($handled) { $this->seal($handled, 'SPLIT'); }
                                else {
                                    // Group contributions
                                    $groupPayment = GroupPayment::where('checkout_request_id', $checkoutId)->where('status', 'pending')->first();
                                    if ($groupPayment) {
                                        $groupPayment->update(['status' => 'confirmed', 'receipt_number' => 'PRG-' . now()->format('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(6))]);
                                    } else {
                                        // Subscriptions
                                        $sub = Subscription::where('checkout_request_id', $checkoutId)->first();
                                        if ($sub) {
                                            $sub->update(['status' => 'active', 'last_paid_at' => now(), 'next_due_at' => $sub->plan->nextDueDate(), 'receipt_number' => 'PRG-' . now()->format('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(6))]);
                                        } else {
                                            $handled = $this->bulkGifts->confirmPayment($checkoutId, $mpesaCode, $amount);
                                            if (! $handled) {
                                                $handled = $this->sellers->confirmPayment($checkoutId, $mpesaCode, $amount);
                                                if (! $handled) {
                                                    $handled = $this->vouchers->confirmDeposit($checkoutId, $mpesaCode, $amount);
                                                    if ($handled) { $this->seal($handled, 'VOUCHER'); }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } else {
            $reason = $callback['ResultDesc'] ?? 'Unknown';

            $multi = MultiGift::where('mpesa_checkout_id', $checkoutId)->first();
            if ($multi) {
                $this->multiGifts->failPayment($checkoutId);
                return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
            }

            $tip = TipTransaction::where('mpesa_checkout_id', $checkoutId)->first();
            if ($tip) {
                $this->tips->failPayment($checkoutId);
            } else {
                $gift = CreatorGift::where('mpesa_checkout_id', $checkoutId)->first();
                if ($gift) {
                    $gift->update(['status' => 'failed']);
                } else {
                    $direct = DirectGift::where('mpesa_checkout_id', $checkoutId)->first();
                    if ($direct) {
                        $this->directGifts->failPayment($checkoutId);
                    } else {
                        $col = CollectionContribution::where('mpesa_checkout_id', $checkoutId)->first();
                        if ($col) {
                            $this->collections->failContribution($checkoutId);
                        } else {
                            $sch = SchoolPayment::where('mpesa_checkout_id', $checkoutId)->first();
                            if ($sch) {
                                $this->schoolFees->failPayment($checkoutId);
                            } else {
                                $split = BillSplitPayment::where('mpesa_checkout_id', $checkoutId)->first();
                                if ($split) {
                                    $this->billSplits->failPayment($checkoutId);
                                } else {
                                    $bulk = BulkGift::where('mpesa_checkout_id', $checkoutId)->first();
                                    if ($bulk) {
                                        $this->bulkGifts->failPayment($checkoutId);
                                    } else {
                                        GroupPayment::where('checkout_request_id', $checkoutId)->update(['status' => 'failed']);
                                        Subscription::where('checkout_request_id', $checkoutId)->update(['status' => 'overdue']);
                                        $seller = SellerPayment::where('mpesa_checkout_id', $checkoutId)->first();
                                        if ($seller) {
                                            $this->sellers->failPayment($checkoutId);
                                        } else {
                                            $this->vouchers->failDeposit($checkoutId, $reason);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
    }

    private function seal($model, string $type): void
    {
        if (! $model || ! $model->mpesa_confirmation_code) return;
        $paidAt = $model->paid_at ?? $model->updated_at;
        if (! $paidAt) return;

        $hash = $this->txHash->seal(
            $type,
            $model->id,
            $model->mpesa_confirmation_code,
            (int) $model->amount,
            $paidAt->format('Y-m-d H:i:s')
        );

        $model->tx_hash = $hash;
        $model->save();
    }

    public function b2cResult(Request $request)
    {
        $body   = $request->all();
        $result = $body['Result'] ?? null;

        Log::info('B2C Result received', ['result_code' => $result['ResultCode'] ?? 'unknown']);

        if (! $result) {
            return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
        }

        $conversationId = $result['ConversationID'] ?? null;
        $resultCode     = $result['ResultCode'];

        if ($conversationId) {
            $voucher = Voucher::where('b2c_conversation_id', $conversationId)->first();

            if ($voucher) {
                if ($resultCode === 0) {
                    $params    = collect($result['ResultParameters']['ResultParameter'] ?? []);
                    $txReceipt = $params->firstWhere('Key', 'TransactionReceipt')['Value'] ?? null;

                    $voucher->update(['b2c_confirmation_code' => $txReceipt]);

                    LedgerEntry::record($voucher, 'b2c_confirmed', [
                        'transaction_receipt' => $txReceipt,
                        'result_code'         => $resultCode,
                    ], null, $txReceipt);
                } else {
                    LedgerEntry::record($voucher, 'b2c_failed', [
                        'result_code' => $resultCode,
                        'result_desc' => $result['ResultDesc'] ?? 'Unknown',
                    ]);
                }
            }
        }

        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
    }

    public function b2cTimeout(Request $request)
    {
        Log::warning('B2C Timeout', $request->all());
        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
    }
}
