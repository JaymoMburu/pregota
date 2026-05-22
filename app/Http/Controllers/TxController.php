<?php

namespace App\Http\Controllers;

use App\Models\CollectionContribution;
use App\Models\CreatorGift;
use App\Models\DirectGift;
use App\Models\SchoolPayment;
use App\Models\TipTransaction;
use App\Models\Voucher;

class TxController extends Controller
{
    private array $registry = [
        ['model' => TipTransaction::class,        'type' => 'TIP',          'label' => 'Staff Tip'],
        ['model' => CreatorGift::class,            'type' => 'CREATOR_GIFT', 'label' => 'Creator Gift'],
        ['model' => DirectGift::class,             'type' => 'DIRECT_GIFT',  'label' => 'Direct Gift'],
        ['model' => CollectionContribution::class, 'type' => 'COLLECTION',   'label' => 'Collection'],
        ['model' => SchoolPayment::class,          'type' => 'SCHOOL',       'label' => 'School Collection'],
        ['model' => Voucher::class,                'type' => 'VOUCHER',      'label' => 'Gift Voucher'],
    ];

    public function verify(string $hash)
    {
        if (!preg_match('/^[a-f0-9]{64}$/', $hash)) {
            abort(404);
        }

        foreach ($this->registry as $entry) {
            $record = $entry['model']::where('tx_hash', $hash)->first();
            if ($record) {
                return view('tx.verify', [
                    'hash'   => $hash,
                    'label'  => $entry['label'],
                    'type'   => $entry['type'],
                    'record' => $record,
                ]);
            }
        }

        abort(404);
    }
}
