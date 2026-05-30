<?php

namespace App\Http\Controllers;

use App\Models\SakaKejaAuthSession;
use App\Models\SakaKejaConnection;
use App\Models\SakaKejaListing;
use App\Services\DarajaService;
use App\Services\SellerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class SakaKejaController extends Controller
{
    const LISTING_FEE    = 200;
    const CONNECTION_FEE = 200;

    public function __construct(
        private DarajaService $daraja,
        private SellerService $seller,
    ) {}

    // ── Public browse ─────────────────────────────────────────────────────

    public function browse(Request $request)
    {
        $query = SakaKejaListing::where('status', 'active')->latest();

        if ($request->filled('unit_type')) {
            $query->where('unit_type', $request->unit_type);
        }
        if ($request->filled('max_rent')) {
            $query->where('rent', '<=', (int) $request->max_rent);
        }
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        $listings = $query->get();
        return view('saka-keja.browse', compact('listings'));
    }

    public function show($id)
    {
        $listing = SakaKejaListing::where('id', $id)->where('status', 'active')->firstOrFail();
        return view('saka-keja.show', compact('listing'));
    }

    // ── Listing creation ──────────────────────────────────────────────────

    public function listForm()
    {
        return view('saka-keja.list');
    }

    public function submitListing(Request $request)
    {
        $data = $request->validate([
            'landlord_name' => ['required', 'string', 'max:100'],
            'phone'         => ['required', 'regex:/^(\+?254|0)[17]\d{8}$/'],
            'unit_type'     => ['required', 'in:bedsitter,1br,2br,3br,studio,shop'],
            'location'      => ['required', 'string', 'max:150'],
            'rent'          => ['required', 'integer', 'min:500', 'max:500000'],
            'description'   => ['nullable', 'string', 'max:1000'],
            'photos'        => ['required', 'array', 'min:1', 'max:8'],
            'photos.*'      => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $hash = $this->seller->hashPhone($data['phone']);

        $listing = SakaKejaListing::create([
            'landlord_phone_hash'      => $hash,
            'landlord_phone_encrypted' => Crypt::encryptString($data['phone']),
            'landlord_name'            => $data['landlord_name'],
            'location'                 => $data['location'],
            'unit_type'                => $data['unit_type'],
            'rent'                     => $data['rent'],
            'description'              => $data['description'],
            'listing_fee'              => self::LISTING_FEE,
            'status'                   => 'pending_verification',
        ]);

        // Store photos
        $dir = public_path("uploads/saka-keja/{$listing->id}");
        if (! is_dir($dir)) mkdir($dir, 0755, true);

        $photoNames = [];
        foreach ($request->file('photos') as $photo) {
            $name         = Str::uuid() . '.' . $photo->getClientOriginalExtension();
            $photo->move($dir, $name);
            $photoNames[] = $name;
        }
        $listing->update(['photos' => $photoNames]);

        $stk = $this->daraja->stkPush(
            phone: $data['phone'],
            amount: self::LISTING_FEE,
            accountRef: 'SAKAKEJA',
            description: 'Saka Keja Listing Fee',
        );

        if (! isset($stk['CheckoutRequestID'])) {
            $listing->delete();
            return response()->json(['success' => false, 'message' => $stk['errorMessage'] ?? 'STK Push failed. Try again.'], 422);
        }

        $listing->update(['verification_checkout_id' => $stk['CheckoutRequestID']]);

        return response()->json([
            'success'             => true,
            'listing_id'          => $listing->id,
            'checkout_request_id' => $stk['CheckoutRequestID'],
            'safaricom_msg'       => $stk['CustomerMessage'] ?? '',
        ]);
    }

    public function pollListing(Request $request)
    {
        $listing = SakaKejaListing::where('verification_checkout_id', $request->query('checkout_request_id'))->first();

        if (! $listing) return response()->json(['status' => 'not_found']);

        if ($listing->status === 'active') {
            return response()->json(['status' => 'confirmed', 'redirect' => route('saka-keja.show', $listing->id)]);
        }

        if ($listing->status === 'failed') {
            return response()->json(['status' => 'failed']);
        }

        return response()->json(['status' => 'pending']);
    }

    // ── Seeker connection ─────────────────────────────────────────────────

    public function initiateConnect(Request $request, $id)
    {
        $listing = SakaKejaListing::where('id', $id)->where('status', 'active')->firstOrFail();

        $data = $request->validate([
            'seeker_name' => ['required', 'string', 'max:100'],
            'phone'       => ['required', 'regex:/^(\+?254|0)[17]\d{8}$/'],
        ]);

        $hash = $this->seller->hashPhone($data['phone']);

        $alreadyConnected = SakaKejaConnection::where('listing_id', $id)
            ->where('seeker_phone_hash', $hash)
            ->where('status', 'confirmed')
            ->exists();

        if ($alreadyConnected) {
            return response()->json(['success' => false, 'message' => 'You have already connected with this landlord.'], 422);
        }

        $stk = $this->daraja->stkPush(
            phone: $data['phone'],
            amount: self::CONNECTION_FEE,
            accountRef: 'SAKAKEJA',
            description: 'Saka Keja — Connect with landlord',
        );

        if (! isset($stk['CheckoutRequestID'])) {
            return response()->json(['success' => false, 'message' => $stk['errorMessage'] ?? 'STK Push failed. Try again.'], 422);
        }

        SakaKejaConnection::create([
            'listing_id'             => $listing->id,
            'seeker_name'            => $data['seeker_name'],
            'seeker_phone_hash'      => $hash,
            'seeker_phone_encrypted' => Crypt::encryptString($data['phone']),
            'checkout_request_id'    => $stk['CheckoutRequestID'],
            'amount'                 => self::CONNECTION_FEE,
        ]);

        return response()->json([
            'success'             => true,
            'checkout_request_id' => $stk['CheckoutRequestID'],
            'safaricom_msg'       => $stk['CustomerMessage'] ?? '',
        ]);
    }

    public function pollConnect(Request $request)
    {
        $conn = SakaKejaConnection::where('checkout_request_id', $request->query('checkout_request_id'))->first();

        if (! $conn) return response()->json(['status' => 'not_found']);

        return response()->json(['status' => $conn->status]);
    }

    // ── Landlord dashboard auth ───────────────────────────────────────────

    public function landlordPage()
    {
        if (session()->has('saka_keja_phone_hash')) {
            return redirect()->route('saka-keja.dashboard');
        }
        return view('saka-keja.landlord');
    }

    public function initiateLogin(Request $request)
    {
        $data = $request->validate([
            'phone' => ['required', 'regex:/^(\+?254|0)[17]\d{8}$/'],
        ]);

        $hash = $this->seller->hashPhone($data['phone']);

        $stk = $this->daraja->stkPush(
            phone: $data['phone'],
            amount: 1,
            accountRef: 'SAKAKEJA',
            description: 'Saka Keja Dashboard Access',
        );

        if (! isset($stk['CheckoutRequestID'])) {
            return response()->json(['success' => false, 'message' => $stk['errorMessage'] ?? 'STK Push failed.'], 422);
        }

        SakaKejaAuthSession::create([
            'checkout_request_id' => $stk['CheckoutRequestID'],
            'phone_hash'          => $hash,
            'phone_encrypted'     => Crypt::encryptString($data['phone']),
        ]);

        return response()->json([
            'success'             => true,
            'checkout_request_id' => $stk['CheckoutRequestID'],
            'safaricom_msg'       => $stk['CustomerMessage'] ?? '',
        ]);
    }

    public function pollLogin(Request $request)
    {
        $auth = SakaKejaAuthSession::where('checkout_request_id', $request->query('checkout_request_id'))->first();

        if (! $auth) return response()->json(['status' => 'not_found']);

        if ($auth->status === 'confirmed') {
            session(['saka_keja_phone_hash' => $auth->phone_hash]);
            return response()->json(['status' => 'confirmed', 'redirect' => route('saka-keja.dashboard')]);
        }

        if ($auth->status === 'failed') {
            return response()->json(['status' => 'failed']);
        }

        return response()->json(['status' => 'pending']);
    }

    public function dashboard()
    {
        if (! session()->has('saka_keja_phone_hash')) {
            return redirect()->route('saka-keja.landlord');
        }

        $hash     = session('saka_keja_phone_hash');
        $listings = SakaKejaListing::where('landlord_phone_hash', $hash)
            ->with(['connections' => fn($q) => $q->where('status', 'confirmed')->latest()])
            ->latest()
            ->get();

        $listings->each(function ($listing) {
            $listing->connections->each(function ($conn) {
                try {
                    $conn->seeker_phone = Crypt::decryptString($conn->seeker_phone_encrypted);
                } catch (\Exception $e) {
                    $conn->seeker_phone = '—';
                }
            });
        });

        $totalConnections = $listings->sum(fn($l) => $l->connections->count());

        return view('saka-keja.dashboard', compact('listings', 'totalConnections'));
    }

    public function markRented(Request $request, $id)
    {
        $hash    = session('saka_keja_phone_hash');
        $listing = SakaKejaListing::where('id', $id)->where('landlord_phone_hash', $hash)->firstOrFail();
        $listing->update(['status' => 'rented']);
        return response()->json(['success' => true]);
    }

    public function deleteListing($id)
    {
        $hash    = session('saka_keja_phone_hash');
        $listing = SakaKejaListing::where('id', $id)->where('landlord_phone_hash', $hash)->firstOrFail();
        $listing->update(['status' => 'inactive']);
        return response()->json(['success' => true]);
    }

    public function landlordLogout(Request $request)
    {
        $request->session()->forget('saka_keja_phone_hash');
        return redirect()->route('saka-keja.landlord');
    }
}
