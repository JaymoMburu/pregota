<?php

namespace App\Http\Controllers;

use App\Models\Dispute;
use App\Models\SellerPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class DisputeController extends Controller
{
    public function show(string $receipt)
    {
        $payment = SellerPayment::where('receipt_number', $receipt)
            ->where('status', 'confirmed')
            ->with('payLink:id,business_name,handle')
            ->firstOrFail();

        $already = Dispute::where('receipt_number', $receipt)->exists();

        return view('dispute', compact('payment', 'already'));
    }

    public function store(Request $request, string $receipt)
    {
        $payment = SellerPayment::where('receipt_number', $receipt)
            ->where('status', 'confirmed')
            ->firstOrFail();

        // One dispute per receipt
        if (Dispute::where('receipt_number', $receipt)->exists()) {
            return back()->with('error', 'A dispute has already been filed for this receipt.');
        }

        $data = $request->validate([
            'phone'       => ['required', 'string', 'regex:/^(\+?254|0)[17]\d{8}$/'],
            'issue_type'  => ['required', 'in:non_delivery,wrong_amount,wrong_product,damaged,other'],
            'description' => ['required', 'string', 'min:20', 'max:1000'],
        ]);

        Dispute::create([
            'receipt_number'       => $receipt,
            'buyer_phone_encrypted'=> Crypt::encryptString($data['phone']),
            'issue_type'           => $data['issue_type'],
            'description'          => $data['description'],
        ]);

        return redirect()->route('dispute.show', $receipt)
            ->with('filed', 'Your dispute has been filed. We will review it within 24 hours.');
    }
}
