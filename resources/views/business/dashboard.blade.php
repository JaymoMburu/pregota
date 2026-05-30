<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $business->name }} Dashboard â€” Pregota</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
*{box-sizing:border-box;margin:0;padding:0}input,textarea,select,button{font-family:inherit;font-size:inherit}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh}
.nav{padding:14px 24px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.08)}
.logo{font-size:20px;font-weight:900;background:linear-gradient(135deg,#00A651,#007A33);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.nav-right{display:flex;align-items:center;gap:12px}
.biz-name{font-size:13px;color:rgba(255,255,255,.68)}
.logout-btn{background:none;border:none;color:rgba(255,255,255,.82);cursor:pointer;font-size:13px}

.main{padding:24px;max-width:900px;margin:0 auto}
.section-title{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.82);margin-bottom:12px}

/* Plan banner */
.plan-banner{border-radius:12px;padding:12px 18px;margin-bottom:22px;display:flex;justify-content:space-between;align-items:center;gap:12px}
.plan-banner.free{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.1)}
.plan-banner.paid{background:linear-gradient(135deg,rgba(0,166,81,.15),rgba(0,122,51,.1));border:1px solid rgba(0,166,81,.25)}
.plan-tag{font-size:12px;font-weight:700;padding:3px 10px;border-radius:20px}
.plan-tag.free{background:rgba(255,255,255,.08);color:rgba(255,255,255,.78)}
.plan-tag.paid{background:linear-gradient(135deg,#00A651,#007A33);color:#fff}
.plan-text{font-size:13px;color:rgba(255,255,255,.78)}
.plan-text strong{color:rgba(255,255,255,.8)}
.upgrade-link{font-size:12px;font-weight:700;color:#a78bfa;text-decoration:none;white-space:nowrap;background:rgba(0,166,81,.15);border:1px solid rgba(0,166,81,.3);border-radius:8px;padding:6px 14px}

/* Stats */
.stats{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:28px}
@media(max-width:600px){.stats{grid-template-columns:1fr 1fr}}
.stat{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:16px}
.stat-val{font-size:22px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.stat-lbl{font-size:11px;color:rgba(255,255,255,.6);margin-top:4px;text-transform:uppercase;letter-spacing:.06em}

/* Analytics */
.analytics-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:28px}
@media(max-width:640px){.analytics-grid{grid-template-columns:1fr}}
.analytics-card{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:14px;padding:20px}
.analytics-card h3{font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.6);margin-bottom:16px}
.analytics-card.full-width{grid-column:1/-1}

/* Trend bars */
.trend-bars{display:flex;align-items:flex-end;gap:4px;height:52px}
.trend-bar-wrap{flex:1;display:flex;flex-direction:column;align-items:center;gap:4px}
.trend-bar{width:100%;border-radius:3px 3px 0 0;min-height:3px;transition:.3s}
.trend-label{font-size:10px;color:rgba(255,255,255,.82)}
.trend-value{font-size:10px;color:rgba(255,255,255,.72);margin-bottom:2px}

/* Tag bars */
.tag-bar-row{display:flex;align-items:center;gap:8px;margin-bottom:8px}
.tag-name{width:110px;font-size:12px;color:rgba(255,255,255,.6);text-align:right;flex-shrink:0}
.tag-track{flex:1;background:rgba(255,255,255,.06);border-radius:4px;height:7px;overflow:hidden}
.tag-fill{height:100%;background:linear-gradient(90deg,#00A651,#007A33);border-radius:4px}
.tag-count{width:22px;font-size:11px;color:rgba(255,255,255,.6);text-align:right}

/* Leaderboard */
.leader-row{display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid rgba(255,255,255,.05)}
.leader-row:last-child{border-bottom:none}
.leader-rank{font-size:14px;font-weight:900;color:rgba(255,255,255,.15);width:22px;text-align:center}
.leader-stars{color:#fbbf24;font-size:13px}

/* Upgrade card */
.upgrade-card{background:rgba(255,255,255,.03);border:1px dashed rgba(0,166,81,.3);border-radius:16px;padding:28px;margin-bottom:28px;text-align:center}
.upgrade-card h3{font-size:18px;font-weight:900;margin-bottom:8px}
.upgrade-card p{font-size:13px;color:rgba(255,255,255,.72);margin-bottom:24px;line-height:1.6}
.plans-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:22px;text-align:left}
@media(max-width:520px){.plans-grid{grid-template-columns:1fr}}
.plan-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.09);border-radius:12px;padding:16px}
.plan-card.popular{border-color:rgba(0,166,81,.5);background:rgba(0,166,81,.08)}
.plan-name{font-size:13px;font-weight:800;margin-bottom:2px}
.plan-price{font-size:20px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.plan-price span{font-size:12px;font-weight:400;color:rgba(255,255,255,.6)}
.plan-feature{font-size:11px;color:rgba(255,255,255,.68);margin-top:8px;line-height:1.6}
.plan-feature li{list-style:none;padding:1px 0}
.plan-feature li::before{content:'âœ“ ';color:#4ade80}

/* Staff table */
.staff-card{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:14px;overflow:hidden;margin-bottom:28px}
.staff-row{display:grid;grid-template-columns:2fr 1fr 1fr 1fr auto;align-items:center;padding:14px 18px;border-bottom:1px solid rgba(255,255,255,.05);gap:10px}
.staff-row:last-child{border-bottom:none}
.staff-row.header{background:rgba(255,255,255,.03);font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.82)}
.staff-name{font-weight:700;font-size:14px}
.staff-handle{font-size:11px;color:rgba(255,255,255,.6);margin-top:2px}
.rating-stars{color:#fbbf24;font-size:14px}
.badge{display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600}
.badge.active{background:rgba(34,197,94,.12);color:#4ade80;border:1px solid rgba(34,197,94,.2)}
.badge.inactive{background:rgba(255,255,255,.06);color:rgba(255,255,255,.68);border:1px solid rgba(255,255,255,.1)}
.row-actions{display:flex;gap:6px}
.action-btn{background:none;border:1px solid rgba(255,255,255,.1);border-radius:6px;padding:5px 10px;color:rgba(255,255,255,.78);font-size:11px;cursor:pointer}
.action-btn:hover{background:rgba(255,255,255,.06);color:#fff}
.view-btn{color:#a78bfa;border-color:rgba(0,166,81,.3)}
@media(max-width:640px){
    .staff-row{grid-template-columns:1fr auto}
    .hide-mobile{display:none}
}

/* Add staff form */
.add-form{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:14px;padding:20px;margin-bottom:28px}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px}
@media(max-width:500px){.form-row{grid-template-columns:1fr}}
.form-group{display:flex;flex-direction:column;gap:5px}
label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.68)}
input,select{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);border-radius:8px;padding:10px 12px;color:#fff;font-size:13px;outline:none;font-family:inherit}
input:focus,select:focus{border-color:#00A651}
select option{background:#0B1810}
.hint{font-size:10px;color:rgba(255,255,255,.82)}
.save-btn{background:linear-gradient(135deg,#00A651,#007A33);color:#fff;border:none;border-radius:10px;padding:10px 22px;font-size:14px;font-weight:700;cursor:pointer}
.alert{border-radius:8px;padding:10px 12px;margin-bottom:14px;font-size:13px}
.alert.success{background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.25);color:#4ade80}

/* Feedback */
.feedback-item{padding:12px 0;border-bottom:1px solid rgba(255,255,255,.05)}
.feedback-item:last-child{border-bottom:none}
.fb-stars{color:#fbbf24;font-size:13px;margin-bottom:3px}
.fb-tags{display:flex;flex-wrap:wrap;gap:5px;margin:5px 0}
.fb-tag{background:rgba(0,166,81,.1);border:1px solid rgba(0,166,81,.2);border-radius:12px;padding:2px 9px;font-size:11px;color:#a78bfa}
.fb-comment{font-size:12px;color:rgba(255,255,255,.78);font-style:italic;margin-top:4px}
.fb-meta{font-size:11px;color:rgba(255,255,255,.25);margin-top:4px}
.no-data{text-align:center;padding:28px;color:rgba(255,255,255,.25);font-size:13px}

/* Modal */
.modal{position:fixed;inset:0;background:rgba(0,0,0,.85);display:none;align-items:center;justify-content:center;z-index:200;padding:20px}
.modal.show{display:flex}
.modal-box{background:#13131f;border:1px solid rgba(255,255,255,.1);border-radius:20px;padding:28px;max-width:400px;width:100%;max-height:80vh;overflow-y:auto}
</style>
</head>
<body>
<nav class="nav">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    <div class="nav-right">
        <span class="biz-name">{{ $business->logo_emoji }} {{ $business->name }}</span>
        <form method="POST" action="{{ route('business.logout') }}" style="display:inline">
            @csrf
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>
</nav>

<div class="main">
    @if(session('success'))
    <div class="alert success">{{ session('success') }}</div>
    @endif

    <!-- Plan banner -->
    @if($business->isSubscribed())
    <div class="plan-banner paid">
        <div style="display:flex;align-items:center;gap:10px;flex:1;min-width:0">
            <span class="plan-tag paid">{{ $business->planLabel() }}</span>
            <span class="plan-text"><strong>Full Analytics Active</strong> Â· Subscription expires {{ $business->plan_expires_at?->format('M j, Y') ?? 'never' }}</span>
        </div>
        <span style="font-size:12px;color:rgba(255,255,255,.82)">Tips are fee-free âœ“</span>
    </div>
    @else
    <div class="plan-banner free">
        <div style="display:flex;align-items:center;gap:10px;flex:1;min-width:0">
            <span class="plan-tag free">Free Plan</span>
            <span class="plan-text">Upgrade to unlock trends, leaderboards &amp; full analytics</span>
        </div>
        <a href="#upgrade" class="upgrade-link">See Plans â†’</a>
    </div>
    @endif

    <!-- Stats â€” service quality only, no financial data -->
    <div class="stats">
        <div class="stat">
            <div class="stat-val">{{ $stats['avg_rating'] > 0 ? $stats['avg_rating'] : 'â€”' }}</div>
            <div class="stat-lbl">Avg Rating</div>
        </div>
        <div class="stat">
            <div class="stat-val">{{ $stats['total_reviews'] }}</div>
            <div class="stat-lbl">Reviews</div>
        </div>
        <div class="stat">
            <div class="stat-val">{{ $stats['total_tips'] }}</div>
            <div class="stat-lbl">Tips Received</div>
        </div>
        <div class="stat">
            <div class="stat-val">{{ $stats['staff_count'] }}</div>
            <div class="stat-lbl">Staff</div>
        </div>
    </div>

    @if($stats['leads_count'] > 0)
    <a href="{{ route('business.leads') }}" style="display:flex;align-items:center;justify-content:space-between;background:rgba(251,191,36,.07);border:1px solid rgba(251,191,36,.2);border-radius:14px;padding:16px 20px;margin-bottom:24px;text-decoration:none">
        <div>
            <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(251,191,36,.6);margin-bottom:4px">Customer Leads</div>
            <div style="font-size:20px;font-weight:900;color:#fbbf24">{{ $stats['leads_count'] }} <span style="font-size:13px;font-weight:500;color:rgba(255,255,255,.68)">customers opted in Â· View & export â†’</span></div>
        </div>
    </a>
    @endif

    @if($analytics)
    <!-- Full analytics â€” subscribed businesses only -->
    <div class="section-title">Analytics</div>
    <div class="analytics-grid">

        <!-- Rating trend 7 days -->
        <div class="analytics-card">
            <h3>Rating Trend â€” Last 7 Days</h3>
            @php $maxCount = collect($analytics['trend'])->max('count') ?: 1; @endphp
            <div class="trend-bars">
                @foreach($analytics['trend'] as $day)
                @php
                    $pct = $day['avg_rating'] ? round($day['avg_rating'] / 5 * 100) : 0;
                    $color = $day['count'] > 0 ? 'linear-gradient(to top,#00A651,#007A33)' : 'rgba(255,255,255,.08)';
                @endphp
                <div class="trend-bar-wrap">
                    @if($day['avg_rating'])
                    <div class="trend-value">{{ $day['avg_rating'] }}</div>
                    @else
                    <div class="trend-value" style="opacity:0">0</div>
                    @endif
                    <div class="trend-bar" style="height:{{ $pct }}%;background:{{ $color }}" title="{{ $day['label'] }}: {{ $day['avg_rating'] ?? 'no data' }}"></div>
                    <div class="trend-label">{{ $day['label'] }}</div>
                </div>
                @endforeach
            </div>
            <div style="margin-top:10px;font-size:11px;color:rgba(255,255,255,.82)">
                Review rate: <strong style="color:rgba(255,255,255,.6)">{{ $analytics['reviewRate'] }}%</strong> of tipped customers left feedback
            </div>
        </div>

        <!-- Top feedback tags -->
        <div class="analytics-card">
            <h3>Top Feedback Tags</h3>
            @if($analytics['topTags']->isEmpty())
            <div style="font-size:13px;color:rgba(255,255,255,.25);text-align:center;padding:20px 0">No feedback yet</div>
            @else
            @php $maxTag = $analytics['topTags']->max(); @endphp
            @foreach($analytics['topTags'] as $tag => $count)
            <div class="tag-bar-row">
                <div class="tag-name">{{ $tag }}</div>
                <div class="tag-track"><div class="tag-fill" style="width:{{ $maxTag > 0 ? round($count/$maxTag*100) : 0 }}%"></div></div>
                <div class="tag-count">{{ $count }}</div>
            </div>
            @endforeach
            @endif
        </div>

        <!-- Staff leaderboard -->
        <div class="analytics-card full-width">
            <h3>Staff Leaderboard â€” By Customer Rating</h3>
            @if($analytics['leaderboard']->isEmpty())
            <div style="font-size:13px;color:rgba(255,255,255,.25);text-align:center;padding:20px 0">Add staff to see leaderboard</div>
            @else
            @foreach($analytics['leaderboard'] as $i => $member)
            <div class="leader-row">
                <div class="leader-rank">#{{ $i + 1 }}</div>
                <span style="font-size:22px">{{ $member['emoji'] }}</span>
                <div style="flex:1">
                    <div style="font-size:13px;font-weight:700">{{ $member['name'] }}</div>
                    @if($member['role'])<div style="font-size:11px;color:rgba(255,255,255,.6)">{{ $member['role'] }}</div>@endif
                </div>
                <div style="text-align:right">
                    @if($member['avg_rating'] > 0)
                    <div class="leader-stars">{{ str_repeat('â˜…', (int) round($member['avg_rating'])) }}{{ str_repeat('â˜†', 5 - (int) round($member['avg_rating'])) }}</div>
                    <div style="font-size:11px;color:rgba(255,255,255,.6)">{{ $member['avg_rating'] }} Â· {{ $member['review_count'] }} {{ Str::plural('review', $member['review_count']) }}</div>
                    @else
                    <div style="font-size:12px;color:rgba(255,255,255,.2)">No reviews yet</div>
                    @endif
                </div>
            </div>
            @endforeach
            @endif
        </div>

    </div>
    @else
    <!-- Upgrade card â€” free tier -->
    <div class="upgrade-card" id="upgrade">
        <div style="font-size:32px;margin-bottom:12px">ðŸ“Š</div>
        <h3>Unlock Full Service Analytics</h3>
        <p>See rating trends over time, which staff your customers love most,<br>what they're saying â€” and what keeps them coming back.</p>

        <div class="plans-grid">
            <div class="plan-card">
                <div class="plan-name">Starter</div>
                <div class="plan-price">KES 1,500<span>/mo</span></div>
                <ul class="plan-feature">
                    <li>Up to 5 staff</li>
                    <li>Rating trends & charts</li>
                    <li>Tag analysis</li>
                    <li>Staff leaderboard</li>
                    <li>No tip service fee</li>
                </ul>
            </div>
            <div class="plan-card popular">
                <div style="font-size:10px;font-weight:700;color:#a78bfa;letter-spacing:.08em;margin-bottom:6px">MOST POPULAR</div>
                <div class="plan-name">Growth</div>
                <div class="plan-price">KES 3,500<span>/mo</span></div>
                <ul class="plan-feature">
                    <li>Up to 20 staff</li>
                    <li>Everything in Starter</li>
                    <li>Multi-branch grouping</li>
                    <li>Monthly summary reports</li>
                    <li>No tip service fee</li>
                </ul>
            </div>
            <div class="plan-card">
                <div class="plan-name">Business</div>
                <div class="plan-price">KES 7,000<span>/mo</span></div>
                <ul class="plan-feature">
                    <li>Up to 50 staff</li>
                    <li>Everything in Growth</li>
                    <li>Data export (CSV)</li>
                    <li>Priority support</li>
                    <li>No tip service fee</li>
                </ul>
            </div>
        </div>

        <a href="mailto:hello@pregota.com?subject=Pregota Business Subscription â€” {{ $business->name }}"
           style="display:inline-block;background:linear-gradient(135deg,#00A651,#007A33);color:#fff;border:none;border-radius:12px;padding:13px 28px;font-size:15px;font-weight:700;text-decoration:none;cursor:pointer">
            Subscribe Now â€” Contact Us â†’
        </a>
        <div style="margin-top:10px;font-size:11px;color:rgba(255,255,255,.25)">M-Pesa payment Â· Activate within 24 hours</div>
    </div>
    @endif

    <!-- Staff list -->
    <div class="section-title">Your Team</div>
    <div class="staff-card">
        <div class="staff-row header">
            <div>Staff Member</div>
            <div class="hide-mobile">Rating</div>
            <div class="hide-mobile">Reviews</div>
            <div class="hide-mobile">Status</div>
            <div>Actions</div>
        </div>
        @forelse($staff as $member)
        <div class="staff-row">
            <div>
                <div class="staff-name">{{ $member->avatar_emoji }} {{ $member->name }}</div>
                <div class="staff-handle">pregota.com/t/{{ $member->handle }}@if($member->role) Â· {{ $member->role }}@endif</div>
            </div>
            <div class="hide-mobile">
                @if($member->feedback_count > 0)
                <span class="rating-stars">{{ str_repeat('â˜…', (int) round($member->averageRating())) }}</span>
                <span style="font-size:12px;color:rgba(255,255,255,.78);margin-left:4px">{{ $member->averageRating() }}</span>
                @else
                <span style="color:rgba(255,255,255,.25);font-size:12px">No reviews</span>
                @endif
            </div>
            <div class="hide-mobile" style="font-size:13px;color:rgba(255,255,255,.78)">{{ $member->feedback_count }}</div>
            <div class="hide-mobile"><span class="badge {{ $member->active ? 'active' : 'inactive' }}">{{ $member->active ? 'Active' : 'Inactive' }}</span></div>
            <div class="row-actions">
                <button class="action-btn view-btn" onclick="viewStaff({{ $member->id }})">Stats</button>
                <form method="POST" action="{{ route('business.staff.toggle', $member) }}" style="display:inline">
                    @csrf
                    <button type="submit" class="action-btn">{{ $member->active ? 'Disable' : 'Enable' }}</button>
                </form>
                <form method="POST" action="{{ route('business.staff.remove', $member) }}" style="display:inline"
                    onsubmit="return confirm('Remove {{ $member->name }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="action-btn" style="color:#f87171;border-color:rgba(239,68,68,.2)">âœ•</button>
                </form>
            </div>
        </div>
        @empty
        <div class="no-data">No staff yet â€” add your first team member below</div>
        @endforelse
    </div>

    <!-- Add staff -->
    <div class="section-title">Add Staff Member</div>
    <div class="add-form">
        <form method="POST" action="{{ route('business.staff.add') }}">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" placeholder="Grace Wanjiku" required>
                </div>
                <div class="form-group">
                    <label>Tip Page Handle</label>
                    <input type="text" name="handle" placeholder="{{ $business->slug }}-grace" required>
                    <div class="hint">pregota.com/t/[handle]</div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Role</label>
                    <input type="text" name="role" placeholder="Waitress, Barista, Porter...">
                </div>
                <div class="form-group">
                    <label>Branch (optional)</label>
                    <input type="text" name="branch" placeholder="Westgate, Sarit...">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Avatar Emoji</label>
                    <input type="text" name="avatar_emoji" placeholder="ðŸ˜Š" maxlength="4" value="ðŸ˜Š">
                </div>
                <div class="form-group">
                    <label>M-Pesa Number (private)</label>
                    <input type="tel" name="phone" placeholder="07XX XXX XXX" required>
                    <div class="hint">Encrypted. Never shown anywhere.</div>
                </div>
            </div>
            <button type="submit" class="save-btn">Add Staff Member â†’</button>
        </form>
    </div>

    <!-- Recent feedback -->
    <div class="section-title">Recent Customer Feedback</div>
    @if($recentFeedback->isEmpty())
    <div class="no-data" style="background:rgba(255,255,255,.02);border:1px solid rgba(255,255,255,.06);border-radius:14px">No feedback yet â€” share your team's tip pages to start collecting</div>
    @else
    <div style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:14px;padding:16px 20px">
        @foreach($recentFeedback as $fb)
        <div class="feedback-item">
            <div style="display:flex;justify-content:space-between;align-items:flex-start">
                <div>
                    <div class="fb-stars">{{ str_repeat('â˜…', $fb->rating) }}{{ str_repeat('â˜†', 5 - $fb->rating) }}</div>
                    <div style="font-size:12px;color:rgba(255,255,255,.78);margin-top:2px">{{ $fb->staff->name }}@if($fb->staff->role) Â· {{ $fb->staff->role }}@endif</div>
                    @if(!empty($fb->tags))
                    <div class="fb-tags">@foreach($fb->tags as $tag)<span class="fb-tag">{{ $tag }}</span>@endforeach</div>
                    @endif
                    @if($fb->comment)
                    <div class="fb-comment">"{{ $fb->comment }}"</div>
                    @endif
                </div>
                <div class="fb-meta">{{ $fb->created_at->diffForHumans() }}</div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

<!-- Staff detail modal -->
<div class="modal" id="staffModal">
    <div class="modal-box">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px">
            <div style="font-size:16px;font-weight:800" id="modalName">â€”</div>
            <button onclick="document.getElementById('staffModal').classList.remove('show')"
                style="background:none;border:none;color:rgba(255,255,255,.68);cursor:pointer;font-size:20px">âœ•</button>
        </div>
        <div id="modalContent" style="color:rgba(255,255,255,.6);font-size:13px">Loading...</div>
    </div>
</div>

<script>
async function viewStaff(id) {
    document.getElementById('staffModal').classList.add('show');
    document.getElementById('modalContent').innerHTML = 'Loading...';

    const res  = await fetch('/business/staff/' + id + '/stats', {
        headers:{'X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content}
    });
    const d = await res.json();

    document.getElementById('modalName').textContent = d.name + (d.role ? ' Â· ' + d.role : '');

    const ratingBar = (r, count) =>
        `<div style="display:flex;align-items:center;gap:8px;margin-bottom:4px">
            <span style="color:#fbbf24;font-size:12px;width:70px">${'â˜…'.repeat(r)}${'â˜†'.repeat(5-r)}</span>
            <div style="flex:1;height:6px;background:rgba(255,255,255,.08);border-radius:3px;overflow:hidden">
                <div style="height:100%;background:linear-gradient(90deg,#00A651,#007A33);width:${d.total_tips > 0 ? Math.round((count||0)/d.total_tips*100) : 0}%"></div>
            </div>
            <span style="font-size:11px;color:rgba(255,255,255,.6);width:20px">${count||0}</span>
        </div>`;

    const tagRows = Object.entries(d.tag_counts||{}).map(([tag,cnt]) =>
        `<span style="background:rgba(0,166,81,.12);border:1px solid rgba(0,166,81,.2);border-radius:12px;padding:3px 10px;font-size:11px;color:#a78bfa;margin:3px">${tag} Ã—${cnt}</span>`
    ).join('');

    document.getElementById('modalContent').innerHTML = `
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:18px">
            <div style="background:rgba(255,255,255,.04);border-radius:10px;padding:12px;text-align:center">
                <div style="font-size:24px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent">${d.avg_rating}</div>
                <div style="font-size:11px;color:rgba(255,255,255,.6);margin-top:2px">Avg Rating</div>
            </div>
            <div style="background:rgba(255,255,255,.04);border-radius:10px;padding:12px;text-align:center">
                <div style="font-size:24px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent">${d.total_tips}</div>
                <div style="font-size:11px;color:rgba(255,255,255,.6);margin-top:2px">Total Reviews</div>
            </div>
        </div>
        <div style="margin-bottom:14px">
            <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.82);margin-bottom:8px">Rating Breakdown</div>
            ${[5,4,3,2,1].map(r => ratingBar(r, d.rating_dist?.[r])).join('')}
        </div>
        ${tagRows ? `<div style="margin-bottom:14px"><div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.82);margin-bottom:8px">Top Feedback Tags</div><div style="display:flex;flex-wrap:wrap">${tagRows}</div></div>` : ''}
        ${d.comments?.length ? `<div><div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.82);margin-bottom:8px">Recent Comments</div>${d.comments.map(c=>`<div style="font-size:12px;color:rgba(255,255,255,.78);font-style:italic;padding:6px 0;border-bottom:1px solid rgba(255,255,255,.05)">"${c}"</div>`).join('')}</div>` : ''}
    `;
}
</script>
</body>
</html>

