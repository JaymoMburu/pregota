<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use Illuminate\Console\Command;

class MarkOverdueSubscriptions extends Command
{
    protected $signature   = 'subscriptions:mark-overdue';
    protected $description = 'Mark active subscriptions whose next_due_at has passed as overdue';

    public function handle(): void
    {
        $count = Subscription::where('status', 'active')
            ->whereNotNull('next_due_at')
            ->whereDate('next_due_at', '<', now()->toDateString())
            ->update(['status' => 'overdue']);

        $this->info("Marked {$count} subscription(s) as overdue.");
    }
}
