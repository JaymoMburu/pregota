<?php

namespace App\Services;

use App\Models\Collection;
use App\Models\CollectionContribution;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CollectionService
{
    public function __construct(private DarajaService $daraja) {}

    public function create(array $data): Collection
    {
        $base = Str::slug($data['title']);
        $slug = $base . '-' . strtolower(Str::random(4));
        while (Collection::where('slug', $slug)->exists()) {
            $slug = $base . '-' . strtolower(Str::random(4));
        }

        $collection = new Collection([
            'slug'            => $slug,
            'title'           => $data['title'],
            'description'     => $data['description'] ?? null,
            'photo_path'      => $data['photo_path'] ?? null,
            'occasion'        => $data['occasion'],
            'organiser_name'  => $data['organiser_name'],
            'organiser_phone' => $data['organiser_phone'] ?? null,
            'recipient_name'  => $data['recipient_name'],
            'target_amount'   => $data['target_amount'] ?? null,
            'deadline'        => $data['deadline'] ?? null,
            'payout_trigger'  => $data['payout_trigger'] ?? 'manual',
            'manage_token'    => Str::random(48),
            'status'          => 'open',
        ]);

        $collection->setRecipientPhone($data['recipient_phone']);
        $collection->save();

        // KES 1 STK Push to verify the payout phone is owned by the organiser
        $stk = $this->daraja->stkPush(
            1,
            $data['recipient_phone'],
            'VERIFY-' . $collection->id,
            'Pregota — Phone Verification'
        );
        if (isset($stk['CheckoutRequestID'])) {
            $collection->update(['verification_checkout_id' => $stk['CheckoutRequestID']]);
        }

        return $collection;
    }

    public function confirmVerification(string $checkoutId): bool
    {
        $collection = Collection::where('verification_checkout_id', $checkoutId)->first();
        if (! $collection) return false;
        $collection->update(['phone_verified' => true]);
        return true;
    }

    public function resendVerification(Collection $collection, string $phone): void
    {
        $stk = $this->daraja->stkPush(1, $phone, 'VERIFY-' . $collection->id, 'Pregota — Phone Verification');
        if (isset($stk['CheckoutRequestID'])) {
            $collection->update(['verification_checkout_id' => $stk['CheckoutRequestID']]);
        }
    }

    public function contribute(Collection $collection, int $amount, string $phone, ?string $name): CollectionContribution
    {
        $fee   = (int) config('pregota.collection_fee', 30);
        $gross = $amount + $fee;

        return DB::transaction(function () use ($collection, $amount, $phone, $name, $fee, $gross) {
            $contribution = CollectionContribution::create([
                'collection_id'    => $collection->id,
                'contributor_name' => $name ? trim($name) : null,
                'amount'           => $amount,
                'fee'              => $fee,
                'gross_amount'     => $gross,
                'status'           => 'pending',
            ]);

            $stk = $this->daraja->stkPush(
                $gross,
                $phone,
                'COL-' . $contribution->id,
                'Pregota — ' . Str::limit($collection->title, 40)
            );

            if (isset($stk['CheckoutRequestID'])) {
                $contribution->update(['mpesa_checkout_id' => $stk['CheckoutRequestID']]);
            }

            return $contribution->fresh();
        });
    }

    public function confirmContribution(string $checkoutId, string $mpesaCode): ?CollectionContribution
    {
        $contribution = CollectionContribution::where('mpesa_checkout_id', $checkoutId)->first();
        if (! $contribution) return null;

        $contribution->update([
            'status'                  => 'paid',
            'mpesa_confirmation_code' => $mpesaCode,
        ]);

        $collection = $contribution->collection;

        DB::transaction(function () use ($collection, $contribution) {
            $collection->increment('total_raised', $contribution->amount);
            $collection->increment('contributor_count');
        });

        $collection->refresh();

        // Auto-payout when target is reached
        if (
            $collection->isOpen() &&
            $collection->payout_trigger === 'target' &&
            $collection->target_amount &&
            $collection->total_raised >= $collection->target_amount
        ) {
            $this->payout($collection);
        }

        return $contribution->fresh();
    }

    public function failContribution(string $checkoutId): void
    {
        CollectionContribution::where('mpesa_checkout_id', $checkoutId)
            ->where('status', 'pending')
            ->update(['status' => 'failed']);
    }

    public function payout(Collection $collection): bool
    {
        if (! $collection->isOpen() || $collection->total_raised === 0) {
            return false;
        }

        // Read and immediately destroy the recipient phone
        $recipientPhone = $collection->getRecipientPhone();
        $collection->update(['recipient_phone_encrypted' => null]);

        $b2c = $this->daraja->b2cPayout(
            $collection->total_raised,
            $recipientPhone,
            'Collection-' . $collection->id
        );

        if (isset($b2c['ConversationID'])) {
            $collection->update([
                'status'             => 'paid',
                'b2c_conversation_id'=> $b2c['ConversationID'],
                'paid_out_at'        => now(),
            ]);
            return true;
        }

        return false;
    }
}
