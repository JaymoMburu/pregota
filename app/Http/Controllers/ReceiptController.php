<?php

namespace App\Http\Controllers;

use App\Models\SellerPayment;
use Illuminate\Http\Response;

class ReceiptController extends Controller
{
    public function show(string $receipt): Response
    {
        $payment = SellerPayment::where('receipt_number', $receipt)
            ->where('status', 'confirmed')
            ->with('payLink')
            ->firstOrFail();

        return response()->view('receipt', compact('payment'));
    }
}
