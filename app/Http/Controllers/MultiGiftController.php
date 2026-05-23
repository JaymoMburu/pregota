<?php

namespace App\Http\Controllers;

use App\Models\Creator;
use App\Models\MultiGift;
use App\Services\MultiGiftService;
use Illuminate\Http\Request;

class MultiGiftController extends Controller
{
    public function __construct(private MultiGiftService $service) {}

    public function page()
    {
        return view('gift.multi');
    }

    public function searchCreator(Request $request)
    {
        $handle = trim(ltrim($request->query('handle', ''), '@'));

        if (strlen($handle) < 2) {
            return response()->json(['found' => false]);
        }

        $creator = Creator::where('handle', $handle)->first();

        if (! $creator) {
            return response()->json(['found' => false, 'message' => 'Creator not found']);
        }

        return response()->json([
            'found'        => true,
            'creator_id'   => $creator->id,
            'handle'       => $creator->handle,
            'display_name' => $creator->display_name,
            'min_gift'     => $creator->min_gift_amount,
            'photo_url'    => $creator->photo_url,
        ]);
    }

    public function initiate(Request $request)
    {
        $data = $request->validate([
            'sender_phone'          => ['required', 'string', 'regex:/^(\+?254|0)[17]\d{8}$/'],
            'items'                 => ['required', 'array', 'min:2', 'max:5'],
            'items.*.creator_id'    => ['required', 'integer', 'exists:creators,id'],
            'items.*.amount'        => ['required', 'integer', 'min:' . config('pregota.min_amount'), 'max:' . config('pregota.max_amount')],
        ]);

        // Hydrate item display fields from DB
        $items = collect($data['items'])->map(function ($item) {
            $creator = Creator::findOrFail($item['creator_id']);
            return [
                'creator_id'   => $creator->id,
                'handle'       => $creator->handle,
                'display_name' => $creator->display_name,
                'amount'       => (int) $item['amount'],
            ];
        })->all();

        // Reject duplicate creators
        $uniqueIds = array_unique(array_column($items, 'creator_id'));
        if (count($uniqueIds) < count($items)) {
            return response()->json(['success' => false, 'message' => 'You cannot gift the same creator twice in one batch.']);
        }

        try {
            $gift = $this->service->initiate($items, $data['sender_phone']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Could not initiate payment. Please try again.']);
        }

        return response()->json([
            'success'   => true,
            'reference' => $gift->reference,
            'gross'     => $gift->gross_amount,
        ]);
    }

    public function status(Request $request)
    {
        $ref  = $request->query('reference');
        $gift = MultiGift::where('reference', $ref)->firstOrFail();

        return response()->json([
            'status'      => $gift->status,
            'distributed' => $gift->distributedCount(),
            'total'       => count($gift->items),
            'items'       => collect($gift->items)->map(fn($i) => [
                'display_name' => $i['display_name'],
                'handle'       => $i['handle'],
                'amount'       => $i['amount'],
                'b2c_status'   => $i['b2c_status'],
            ]),
        ]);
    }
}
