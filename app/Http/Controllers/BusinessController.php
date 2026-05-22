<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\CustomerOptIn;
use App\Models\FeedbackTag;
use App\Models\StaffMember;
use App\Models\TipFeedback;
use App\Models\TipTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BusinessController extends Controller
{
    public function registerForm()
    {
        return view('business.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'slug'     => ['required', 'string', 'max:30', 'alpha_dash', 'unique:businesses,slug'],
            'category' => ['required', 'in:restaurant,salon,hotel,delivery,other'],
            'city'     => ['nullable', 'string', 'max:60'],
            'email'    => ['required', 'email', 'unique:businesses,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $business = Business::create([
            'name'     => $data['name'],
            'slug'     => strtolower($data['slug']),
            'category' => $data['category'],
            'city'     => $data['city'] ?? null,
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        session(['business_id' => $business->id]);

        return redirect()->route('business.dashboard')->with('success', 'Welcome to Pregota! Add your first staff member to get started.');
    }

    public function loginForm()
    {
        return view('business.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $business = Business::where('email', $data['email'])->first();

        if (! $business || ! Hash::check($data['password'], $business->password)) {
            return back()->withErrors(['email' => 'Invalid credentials.']);
        }

        session(['business_id' => $business->id]);

        return redirect()->route('business.dashboard');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('business_id');
        return redirect()->route('business.login');
    }

    public function dashboard()
    {
        $business = Business::findOrFail(session('business_id'));
        $staff    = $business->staff()->withCount(['feedback', 'tips' => fn($q) => $q->where('status', 'paid')])->get();
        $staffIds = $staff->pluck('id');

        $handles = $staff->pluck('handle');

        // Core stats — service quality only, no financial data shown to management
        $stats = [
            'avg_rating'    => round(TipFeedback::whereIn('staff_member_id', $staffIds)->avg('rating') ?? 0, 1),
            'total_tips'    => $staff->sum('tips_count'),
            'total_reviews' => $staff->sum('feedback_count'),
            'staff_count'   => $staff->count(),
            'leads_count'   => CustomerOptIn::whereHas('billSplit', fn($q) =>
                                   $q->whereIn('tip_handle', $handles)
                               )->count(),
        ];

        $recentFeedback = TipFeedback::whereIn('staff_member_id', $staffIds)
            ->with('staff')
            ->latest()
            ->take(10)
            ->get();

        $analytics = null;

        if ($business->isSubscribed()) {
            // Rating trend — last 7 days
            $trendRaw = TipFeedback::whereIn('staff_member_id', $staffIds)
                ->where('created_at', '>=', now()->subDays(6)->startOfDay())
                ->selectRaw('DATE(created_at) as day, ROUND(AVG(rating), 1) as avg_rating, COUNT(*) as count')
                ->groupBy('day')
                ->orderBy('day')
                ->get()
                ->keyBy('day');

            $trend = [];
            for ($i = 6; $i >= 0; $i--) {
                $date   = now()->subDays($i)->format('Y-m-d');
                $label  = now()->subDays($i)->format('D');
                $entry  = $trendRaw[$date] ?? null;
                $trend[] = [
                    'label'      => $label,
                    'avg_rating' => $entry ? (float) $entry->avg_rating : null,
                    'count'      => $entry ? (int) $entry->count : 0,
                ];
            }

            // Top feedback tags
            $allTags = TipFeedback::whereIn('staff_member_id', $staffIds)
                ->whereNotNull('tags')
                ->pluck('tags')
                ->flatten()
                ->filter();
            $topTags = $allTags->countBy()->sortDesc()->take(8);

            // Staff leaderboard by average rating
            $leaderboard = $staff->map(function ($s) {
                return [
                    'name'       => $s->name,
                    'role'       => $s->role,
                    'emoji'      => $s->avatar_emoji,
                    'avg_rating' => $s->averageRating(),
                    'review_count' => (int) $s->feedback_count,
                ];
            })->sortByDesc('avg_rating')->values();

            // Review rate: % of tipped customers who left feedback
            $totalPaidTips = TipTransaction::whereIn('staff_member_id', $staffIds)
                ->where('status', 'paid')->count();
            $reviewRate = $totalPaidTips > 0
                ? round($stats['total_reviews'] / $totalPaidTips * 100)
                : 0;

            $analytics = compact('trend', 'topTags', 'leaderboard', 'reviewRate');
        }

        return view('business.dashboard', compact('business', 'staff', 'stats', 'recentFeedback', 'analytics'));
    }

    public function addStaff(Request $request)
    {
        $business = Business::findOrFail(session('business_id'));

        $data = $request->validate([
            'name'         => ['required', 'string', 'max:60'],
            'handle'       => ['required', 'string', 'max:30', 'alpha_dash', 'unique:staff_members,handle'],
            'role'         => ['nullable', 'string', 'max:60'],
            'branch'       => ['nullable', 'string', 'max:100'],
            'avatar_emoji' => ['nullable', 'string', 'max:10'],
            'phone'        => ['required', 'string', 'regex:/^(\+?254|0)[17]\d{8}$/'],
        ]);

        $staff = new StaffMember([
            'business_id'  => $business->id,
            'handle'       => strtolower($data['handle']),
            'name'         => $data['name'],
            'role'         => $data['role'] ?? null,
            'branch'       => $data['branch'] ?? null,
            'avatar_emoji' => $data['avatar_emoji'] ?? '😊',
            'alert_token'  => Str::random(32),
        ]);

        $staff->setPayoutPhone($data['phone']);
        $staff->save();

        return back()->with('success', $data['name'] . ' added. Tip page: pregota.com/t/' . $staff->handle);
    }

    public function toggleStaff(StaffMember $staff)
    {
        $this->authorizeStaff($staff);
        $staff->update(['active' => ! $staff->active]);
        return back();
    }

    public function removeStaff(StaffMember $staff)
    {
        $this->authorizeStaff($staff);
        $staff->delete();
        return back()->with('success', 'Staff member removed.');
    }

    public function staffStats(StaffMember $staff)
    {
        $this->authorizeStaff($staff);

        $feedback = TipFeedback::where('staff_member_id', $staff->id)->get();

        $tagCounts = collect($feedback->pluck('tags')->flatten())
            ->countBy()
            ->sortDesc()
            ->take(10);

        $ratingDist = $feedback->groupBy('rating')->map->count();

        return response()->json([
            'name'        => $staff->name,
            'role'        => $staff->role,
            'avg_rating'  => round($feedback->avg('rating') ?? 0, 1),
            'total_tips'  => $staff->tipCount(),
            'tag_counts'  => $tagCounts,
            'rating_dist' => $ratingDist,
            'comments'    => $feedback->whereNotNull('comment')->pluck('comment')->take(5),
        ]);
    }

    public function leads()
    {
        $business = Business::findOrFail(session('business_id'));
        $handles  = $business->staff()->pluck('handle');

        $optIns = CustomerOptIn::whereHas('billSplit', fn($q) =>
                        $q->whereIn('tip_handle', $handles)
                    )
                    ->with('billSplit')
                    ->latest()
                    ->get();

        return view('business.leads', compact('business', 'optIns'));
    }

    private function authorizeStaff(StaffMember $staff): void
    {
        abort_unless($staff->business_id === session('business_id'), 403);
    }
}
