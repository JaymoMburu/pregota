<?php

return [
    'fee_in_pct'           => env('PREGOTA_FEE_IN_PCT', 2.5),
    'fee_out_pct'          => env('PREGOTA_FEE_OUT_PCT', 1.5),
    'fee_min_kes'          => env('PREGOTA_FEE_MIN_KES', 50),
    'voucher_expiry_hours' => env('PREGOTA_VOUCHER_EXPIRY_HOURS', 72),
    'admin_password'       => env('PREGOTA_ADMIN_PASSWORD'),
    'min_amount'           => env('PREGOTA_MIN_AMOUNT', 50),
    'max_amount'           => env('PREGOTA_MAX_AMOUNT', 140000),
    'hold_minutes'         => (int) env('PREGOTA_HOLD_MINUTES', 5),
    'tip_fee_flat'         => (int) env('PREGOTA_TIP_FEE_FLAT', 15),
    'gift_direct_fee'      => (int) env('PREGOTA_GIFT_DIRECT_FEE', 75),
    'gift_nudge_threshold' => (int) env('PREGOTA_GIFT_NUDGE_THRESHOLD', 500),
    'collection_fee'       => (int) env('PREGOTA_COLLECTION_FEE', 30),
    'manual_payouts'       => env('PREGOTA_MANUAL_PAYOUTS', false),

    'gift_tiers' => [
        ['min' => 50,     'max' => 100,    'type' => 'flat', 'value' => 15],
        ['min' => 101,    'max' => 499,    'type' => 'flat', 'value' => 40],
        ['min' => 500,    'max' => 999,    'type' => 'flat', 'value' => 50],
        ['min' => 1000,   'max' => 99999,  'type' => 'pct',  'value' => 5],
        ['min' => 100000, 'max' => 140000, 'type' => 'pct',  'value' => 3],
    ],

    'passes' => [
        'daily'   => ['days' => 1,  'price' => 15,  'label' => 'Day Pass'],
        'weekly'  => ['days' => 7,  'price' => 50,  'label' => 'Week Pass'],
        'monthly' => ['days' => 30, 'price' => 150, 'label' => 'Month Pass'],
    ],

    'deni_tiers' => [
        ['min' => 1,    'max' => 100,  'type' => 'flat', 'value' => 10],
        ['min' => 101,  'max' => 1500, 'type' => 'flat', 'value' => 40],
        ['min' => 1501, 'max' => 5000, 'type' => 'flat', 'value' => 60],
        ['min' => 5001, 'max' => null, 'type' => 'pct',  'value' => 1.5],
    ],
];
