<?php

namespace App\Console\Commands;

use App\Services\VoucherService;
use Illuminate\Console\Command;

class ExpireVouchers extends Command
{
    protected $signature   = 'vouchers:expire';
    protected $description = 'Mark active vouchers past their expiry date as expired';

    public function handle(VoucherService $vouchers): int
    {
        $count = $vouchers->expireStale();
        $this->info("Expired {$count} voucher(s).");
        return self::SUCCESS;
    }
}
