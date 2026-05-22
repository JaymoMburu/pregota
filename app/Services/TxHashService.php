<?php

namespace App\Services;

class TxHashService
{
    /**
     * Compute a deterministic SHA-256 hash for a confirmed payment.
     *
     * Hash input: "PREGOTA|{TYPE}|{id}|{mpesa_code}|{amount}|{paid_at}"
     * Anyone with these six values can recompute and verify the hash.
     */
    public function seal(string $type, int $id, string $mpesaCode, int $amount, string $paidAt): string
    {
        $raw = implode('|', ['PREGOTA', strtoupper($type), $id, $mpesaCode, $amount, $paidAt]);
        return hash('sha256', $raw);
    }

    public function verify(string $hash, string $type, int $id, string $mpesaCode, int $amount, string $paidAt): bool
    {
        return hash_equals($hash, $this->seal($type, $id, $mpesaCode, $amount, $paidAt));
    }
}
