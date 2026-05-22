<?php

namespace App\Services;

use App\Models\SchoolClass;
use App\Models\SchoolCollection;
use App\Models\SchoolPayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SchoolFeesService
{
    public function __construct(private DarajaService $daraja) {}

    public function create(array $data, array $classes): SchoolCollection
    {
        $base = Str::slug($data['school_name']);
        $slug = $base . '-' . strtolower(Str::random(4));
        while (SchoolCollection::where('slug', $slug)->exists()) {
            $slug = $base . '-' . strtolower(Str::random(4));
        }

        return DB::transaction(function () use ($data, $classes, $slug) {
            $collection = new SchoolCollection([
                'slug'               => $slug,
                'school_name'        => $data['school_name'],
                'term_label'         => $data['term_label'],
                'amount_per_student' => (int) $data['amount_per_student'],
                'admin_name'         => $data['admin_name'],
                'admin_token'        => Str::random(48),
                'status'             => 'open',
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

            foreach ($classes as $cls) {
                SchoolClass::create([
                    'school_collection_id' => $collection->id,
                    'class_name'           => $cls['class_name'],
                    'teacher_name'         => $cls['teacher_name'],
                    'class_token'          => Str::random(32),
                    'teacher_token'        => Str::random(32),
                ]);
            }

            return $collection;
        });
    }

    public function confirmVerification(string $checkoutId): bool
    {
        $collection = SchoolCollection::where('verification_checkout_id', $checkoutId)->first();
        if (! $collection) return false;
        $collection->update(['phone_verified' => true]);
        return true;
    }

    public function resendVerification(SchoolCollection $collection, string $phone): void
    {
        $stk = $this->daraja->stkPush(1, $phone, 'VERIFY-' . $collection->id, 'Pregota — Phone Verification');
        if (isset($stk['CheckoutRequestID'])) {
            $collection->update(['verification_checkout_id' => $stk['CheckoutRequestID']]);
        }
    }

    public function pay(SchoolClass $class, string $studentName, string $studentId, int $amount, string $phone): SchoolPayment
    {
        $fee   = (int) config('pregota.collection_fee', 30);
        $gross = $amount + $fee;

        return DB::transaction(function () use ($class, $studentName, $studentId, $amount, $fee, $gross, $phone) {
            $payment = SchoolPayment::create([
                'school_class_id' => $class->id,
                'student_id'      => strtoupper(trim($studentId)),
                'student_name'    => trim($studentName),
                'amount'          => $amount,
                'fee'             => $fee,
                'gross_amount'    => $gross,
                'status'          => 'pending',
            ]);

            $collection = $class->schoolCollection;

            $stk = $this->daraja->stkPush(
                $gross,
                $phone,
                'SCH-' . $payment->id,
                $collection->school_name . ' · ' . $collection->term_label
            );

            if (isset($stk['CheckoutRequestID'])) {
                $payment->update(['mpesa_checkout_id' => $stk['CheckoutRequestID']]);
            }

            return $payment->fresh();
        });
    }

    public function confirmPayment(string $checkoutId, string $mpesaCode): ?SchoolPayment
    {
        $payment = SchoolPayment::where('mpesa_checkout_id', $checkoutId)->first();
        if (! $payment) return null;

        $payment->update([
            'status'                  => 'paid',
            'mpesa_confirmation_code' => $mpesaCode,
            'paid_at'                 => now(),
        ]);

        $class = $payment->schoolClass;

        DB::transaction(function () use ($class, $payment) {
            $class->increment('total_raised', $payment->amount);
            $class->increment('contributor_count');
            $class->schoolCollection->increment('total_raised', $payment->amount);
            $class->schoolCollection->increment('contributor_count');
        });

        return $payment->fresh();
    }

    public function failPayment(string $checkoutId): void
    {
        SchoolPayment::where('mpesa_checkout_id', $checkoutId)
            ->where('status', 'pending')
            ->update(['status' => 'failed']);
    }

    public function payout(SchoolCollection $collection): bool
    {
        if (! $collection->isOpen() || $collection->total_raised === 0) {
            return false;
        }

        $recipientPhone = $collection->getRecipientPhone();
        $collection->update(['recipient_phone_encrypted' => null]);

        $b2c = $this->daraja->b2cPayout(
            $collection->total_raised,
            $recipientPhone,
            'SchFees-' . $collection->id
        );

        if (isset($b2c['ConversationID'])) {
            $collection->update([
                'status'              => 'paid',
                'b2c_conversation_id' => $b2c['ConversationID'],
                'paid_out_at'         => now(),
            ]);
            return true;
        }

        return false;
    }
}
