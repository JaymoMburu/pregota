<?php

return [
    'fee_in_pct'           => env('PREGOTA_FEE_IN_PCT', 2.5),
    'fee_out_pct'          => env('PREGOTA_FEE_OUT_PCT', 1.5),
    'fee_min_kes'          => env('PREGOTA_FEE_MIN_KES', 50),
    'voucher_expiry_hours' => env('PREGOTA_VOUCHER_EXPIRY_HOURS', 72),
    'admin_password'       => env('PREGOTA_ADMIN_PASSWORD'),
    'min_amount'           => env('PREGOTA_MIN_AMOUNT', 500),
    'max_amount'           => env('PREGOTA_MAX_AMOUNT', 140000),
    'hold_minutes'         => (int) env('PREGOTA_HOLD_MINUTES', 5),
    'tip_fee_flat'         => (int) env('PREGOTA_TIP_FEE_FLAT', 15),
    'gift_direct_fee'      => (int) env('PREGOTA_GIFT_DIRECT_FEE', 75),
    'gift_nudge_threshold' => (int) env('PREGOTA_GIFT_NUDGE_THRESHOLD', 500),
    'collection_fee'       => (int) env('PREGOTA_COLLECTION_FEE', 30),
];
