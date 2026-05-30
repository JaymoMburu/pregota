<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Dashboard â€” Pregota</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}input,textarea,select,button{font-family:inherit;font-size:inherit}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh}

.topbar{padding:14px 20px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.07);background:#0B141A;position:sticky;top:0;z-index:10}
.logo{font-size:18px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.logout-btn{font-size:12px;color:rgba(255,255,255,.6);background:none;border:none;cursor:pointer;padding:6px 10px}

.page{max-width:640px;margin:0 auto;padding:24px 20px 60px}

.alert{padding:12px 16px;border-radius:10px;margin-bottom:18px;font-size:13px;font-weight:600}
.alert.success{background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.25);color:#4ade80}

/* Profile header */
.profile-header{display:flex;align-items:center;gap:16px;margin-bottom:28px}
.avatar{width:60px;height:60px;border-radius:50%;background:linear-gradient(135deg,#00A651,#007A33);display:flex;align-items:center;justify-content:center;font-size:28px;flex-shrink:0}
.profile-info h2{font-size:20px;font-weight:900}
.profile-info .role{font-size:13px;color:rgba(255,255,255,.72);margin-top:2px}
.rating-badge{display:inline-flex;align-items:center;gap:4px;font-size:12px;color:#fbbf24;margin-top:4px}

/* Stats */
.stats-row{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:24px}
.stat-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:12px;padding:16px;text-align:center}
.stat-num{font-size:24px;font-weight:900;color:#25D366}
.stat-label{font-size:11px;color:rgba(255,255,255,.68);margin-top:3px}

/* Tip link card */
.link-card{background:linear-gradient(135deg,rgba(0,166,81,.15),rgba(0,122,51,.08));border:1px solid rgba(0,166,81,.25);border-radius:14px;padding:20px;margin-bottom:24px}
.link-card-title{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.68);margin-bottom:10px}
.link-row{display:flex;gap:8px;align-items:center}
.link-input{flex:1;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.12);border-radius:8px;padding:10px 12px;color:rgba(255,255,255,.7);font-size:13px;outline:none;font-family:monospace}
.copy-btn{padding:10px 16px;border-radius:8px;background:rgba(0,166,81,.25);border:1px solid rgba(0,166,81,.35);color:#25D366;font-size:12px;font-weight:700;cursor:pointer;white-space:nowrap}
.wa-share{display:inline-flex;align-items:center;gap:6px;margin-top:10px;padding:9px 16px;border-radius:8px;background:#25d366;color:#fff;font-size:12px;font-weight:700;text-decoration:none}

/* Card styles */
.card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:20px;margin-bottom:20px}
.card-title{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.6);margin-bottom:14px}

/* Tip list */
.tip-item{display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid rgba(255,255,255,.05)}
.tip-item:last-child{border-bottom:none}
.tip-amount{font-size:16px;font-weight:700;color:#25D366}
.tip-time{font-size:11px;color:rgba(255,255,255,.82)}
.tip-rating{font-size:12px;color:#fbbf24}
.empty{text-align:center;padding:20px;font-size:13px;color:rgba(255,255,255,.82)}

/* Splits */
.split-item{display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid rgba(255,255,255,.05)}
.split-item:last-child{border-bottom:none}
.split-left{flex:1;min-width:0}
.split-name{font-size:13px;font-weight:600;color:rgba(255,255,255,.85);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.split-meta{font-size:11px;color:rgba(255,255,255,.82);margin-top:1px}
.split-right{text-align:right;flex-shrink:0;margin-left:12px}
.split-amount{font-size:15px;font-weight:800;color:#4ade80}
.split-status{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;margin-top:2px}
.split-status.settled{color:#4ade80}
.split-status.open{color:#fbbf24}
.split-status.expired{color:rgba(255,255,255,.82)}
.reconcile-bar{display:flex;gap:10px;margin-bottom:16px}
.reconcile-chip{flex:1;background:rgba(74,222,128,.07);border:1px solid rgba(74,222,128,.18);border-radius:10px;padding:12px;text-align:center}
.reconcile-num{font-size:20px;font-weight:900;color:#4ade80}
.reconcile-label{font-size:10px;color:rgba(255,255,255,.6);margin-top:2px}
.new-split-btn{display:inline-flex;align-items:center;gap:6px;margin-top:4px;padding:9px 16px;border-radius:8px;background:rgba(74,222,128,.12);border:1px solid rgba(74,222,128,.25);color:#4ade80;font-size:12px;font-weight:700;text-decoration:none}

/* Feedback */
.fb-item{padding:12px 0;border-bottom:1px solid rgba(255,255,255,.05)}
.fb-item:last-child{border-bottom:none}
.fb-rating{font-size:13px;color:#fbbf24;margin-bottom:4px}
.fb-comment{font-size:13px;color:rgba(255,255,255,.6);line-height:1.5}
.fb-time{font-size:11px;color:rgba(255,255,255,.25);margin-top:4px}
.fb-tags{display:flex;flex-wrap:wrap;gap:4px;margin-top:6px}
.fb-tag{padding:3px 8px;border-radius:10px;background:rgba(0,166,81,.15);border:1px solid rgba(0,166,81,.25);font-size:11px;color:#25D366}

/* Profile form */
details summary{cursor:pointer;list-style:none;font-size:13px;font-weight:700;color:rgba(255,255,255,.78);padding:4px 0}
details summary::-webkit-details-marker{display:none}
details[open] summary{color:#25D366;margin-bottom:16px}
.form-group{margin-bottom:12px}
label{display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.78);margin-bottom:6px}
input{width:100%;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.15);border-radius:10px;padding:11px 13px;color:#fff;font-size:14px;outline:none;transition:.2s;font-family:inherit}
input:focus{border-color:#00A651;background:rgba(0,166,81,.08)}
input::placeholder{color:rgba(255,255,255,.82)}
.save-btn{padding:11px 24px;border-radius:10px;border:none;font-size:14px;font-weight:700;cursor:pointer;background:linear-gradient(135deg,#00A651,#007A33);color:#fff}
.hint{font-size:11px;color:rgba(255,255,255,.6);margin-top:5px}

@media(max-width:480px){.stats-row{grid-template-columns:1fr 1fr}.stat-card:last-child{grid-column:span 2}}
</style>
</head>
<body>

<div class="topbar">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    <form method="POST" action="{{ route('staff.logout') }}" style="display:inline">
        @csrf
        <button type="submit" class="logout-btn">Sign out</button>
    </form>
</div>

<div class="page">

    @if(session('success'))
    <div class="alert success">{{ session('success') }}</div>
    @endif

    <!-- Profile header -->
    <div class="profile-header">
        <div class="avatar">{{ $staff->avatar_emoji ?? 'ðŸ˜Š' }}</div>
        <div class="profile-info">
            <h2>{{ $staff->name }}</h2>
            <div class="role">{{ $staff->role }}</div>
            @if($stats['rating_count'] > 0)
            <div class="rating-badge">
                â˜… {{ number_format($stats['avg_rating'], 1) }} Â· {{ $stats['rating_count'] }} {{ Str::plural('rating', $stats['rating_count']) }}
            </div>
            @endif
        </div>
    </div>

    <!-- Stats â€” only shown when staff uses tips -->
    @if($stats['count'] > 0 || $stats['today'] > 0)
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-num">KES {{ number_format($stats['today']) }}</div>
            <div class="stat-label">Tips today</div>
        </div>
        <div class="stat-card">
            <div class="stat-num">KES {{ number_format($stats['month']) }}</div>
            <div class="stat-label">Tips this month</div>
        </div>
        <div class="stat-card">
            <div class="stat-num">{{ $stats['count'] }}</div>
            <div class="stat-label">Total tips</div>
        </div>
    </div>
    @endif

    <!-- Tip link -->
    <div class="link-card">
        <div class="link-card-title">Your Tip Page Link</div>
        <div class="link-row">
            <input class="link-input" id="tipUrl" readonly value="{{ $tipUrl }}">
            <button class="copy-btn" onclick="copyLink()">Copy</button>
        </div>
        <a href="https://wa.me/?text={{ urlencode('Hi! You can tip me securely via Pregota â€” no need to ask for my number ðŸ˜Š ' . $tipUrl) }}"
           target="_blank" class="wa-share">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            Share on WhatsApp
        </a>
    </div>

    <!-- Today's Bill Splits -->
    <div class="card">
        <div class="card-title">Today's Bill Splits</div>

        @if($todaySplits->count() > 0)
        <div class="reconcile-bar">
            <div class="reconcile-chip">
                <div class="reconcile-num">KES {{ number_format($stats['today_splits_total']) }}</div>
                <div class="reconcile-label">Settled today</div>
            </div>
            <div class="reconcile-chip" style="background:rgba(251,191,36,.07);border-color:rgba(251,191,36,.18)">
                <div class="reconcile-num" style="color:#fbbf24">{{ $todaySplits->count() }}</div>
                <div class="reconcile-label">{{ Str::plural('bill', $todaySplits->count()) }} opened</div>
            </div>
            @if($stats['today_optins'] > 0)
            <a href="{{ route('staff.leads') }}" style="text-decoration:none;flex:1">
            <div class="reconcile-chip" style="background:rgba(251,191,36,.07);border-color:rgba(251,191,36,.18)">
                <div class="reconcile-num" style="color:#fbbf24">{{ $stats['today_optins'] }}</div>
                <div class="reconcile-label">{{ Str::plural('customer', $stats['today_optins']) }} opted in â€º</div>
            </div>
            </a>
            @endif
        </div>

        @foreach($todaySplits as $split)
        <div class="split-item">
            <div class="split-left">
                <div class="split-name">{{ $split->business_name }}{{ $split->label ? ' Â· ' . $split->label : '' }}</div>
                <div class="split-meta">KES {{ number_format($split->total_amount) }} total Â· {{ $split->created_at->format('g:i A') }}</div>
            </div>
            <div class="split-right">
                <div class="split-amount">KES {{ number_format($split->paid_amount) }}</div>
                <div class="split-status {{ $split->status }}">{{ ucfirst($split->status) }}</div>
            </div>
        </div>
        @endforeach
        @else
        <div class="empty">No bill splits today yet.</div>
        @endif

        <div style="display:flex;gap:10px;margin-top:4px">
            <a href="{{ route('staff.charge') }}" class="new-split-btn" style="flex:1;justify-content:center;background:linear-gradient(135deg,#00A651,#007A33)">
                âš¡ Charge Customer
            </a>
            <a href="{{ route('bill-split.new') }}" class="new-split-btn" style="flex:1;justify-content:center">
                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Split Bill
            </a>
        </div>
    </div>

    <!-- Recent tips â€” only shown when tips have been used -->
    @if($recentTips->count() > 0)
    <div class="card">
        <div class="card-title">Recent Tips ({{ $recentTips->count() }})</div>
        @forelse($recentTips as $tip)
        <div class="tip-item">
            <div>
                <div class="tip-amount">KES {{ number_format($tip->tip_amount) }}</div>
                <div class="tip-time">{{ $tip->paid_at?->diffForHumans() ?? $tip->created_at->diffForHumans() }}</div>
            </div>
            @if($tip->feedback)
            <div class="tip-rating">{{ str_repeat('â˜…', $tip->feedback->rating) }}</div>
            @endif
        </div>
        @empty
        <div class="empty">No tips yet. Share your link and start earning!</div>
        @endforelse
    </div>
    @endif

    <!-- Feedback -->
    @if($recentFeedback->count())
    <div class="card">
        <div class="card-title">Customer Feedback ({{ $stats['rating_count'] }})</div>
        @foreach($recentFeedback as $fb)
        <div class="fb-item">
            <div class="fb-rating">{{ str_repeat('â˜…', $fb->rating) }}{{ str_repeat('â˜†', 5 - $fb->rating) }}</div>
            @if($fb->comment)
            <div class="fb-comment">"{{ $fb->comment }}"</div>
            @endif
            @if($fb->tags && count($fb->tags))
            <div class="fb-tags">
                @foreach($fb->tags as $tag)
                <span class="fb-tag">{{ $tag }}</span>
                @endforeach
            </div>
            @endif
            <div class="fb-time">{{ $fb->created_at->diffForHumans() }}</div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Edit profile -->
    <div class="card">
        <details>
            <summary>âš™ï¸ Edit Profile & Settings</summary>
            <form method="POST" action="{{ route('staff.profile.update') }}">
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <label>Your Name</label>
                    <input type="text" name="name" value="{{ $staff->name }}" maxlength="60" required>
                </div>
                <div class="form-group">
                    <label>Your Role</label>
                    <input type="text" name="role" value="{{ $staff->role }}" maxlength="60" required>
                </div>
                <div class="form-group">
                    <label>New M-Pesa Number (leave blank to keep current)</label>
                    <input type="tel" name="payout_phone" placeholder="07XX XXX XXX">
                    <div class="hint">ðŸ”’ Encrypted and private.</div>
                </div>
                <div class="form-group">
                    <label>New Password (leave blank to keep current)</label>
                    <input type="password" name="password" placeholder="At least 6 characters">
                </div>
                <div class="form-group">
                    <label>Confirm New Password</label>
                    <input type="password" name="password_confirmation" placeholder="Repeat new password">
                </div>

                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.6);margin:20px 0 10px;padding-top:16px;border-top:1px solid rgba(255,255,255,.06)">
                    Restaurant Till / Paybill
                </div>
                <div class="form-group">
                    <div style="display:flex;gap:8px;margin-bottom:8px">
                        <label style="display:flex;align-items:center;gap:6px;font-size:12px;font-weight:600;text-transform:none;letter-spacing:0;color:rgba(255,255,255,.7);cursor:pointer;flex:1;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:8px;padding:9px 11px">
                            <input type="radio" name="till_type" value="paybill" style="width:auto;accent-color:#00A651" {{ $staff->till_type === 'paybill' ? 'checked' : '' }}>
                            Paybill
                        </label>
                        <label style="display:flex;align-items:center;gap:6px;font-size:12px;font-weight:600;text-transform:none;letter-spacing:0;color:rgba(255,255,255,.7);cursor:pointer;flex:1;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:8px;padding:9px 11px">
                            <input type="radio" name="till_type" value="till" style="width:auto;accent-color:#00A651" {{ $staff->till_type === 'till' ? 'checked' : '' }}>
                            Till (Lipa na M-Pesa)
                        </label>
                    </div>
                    <input type="text" name="till_number" placeholder="Paybill or Till number"
                           inputmode="numeric" maxlength="7"
                           style="background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.15);border-radius:10px;padding:11px 13px;color:#fff;font-size:14px;outline:none;width:100%">
                    <div class="hint">Used for Charge Customer and Bill Split payouts. ðŸ”’ Encrypted.</div>
                    @if($staff->hasTill())
                    <div class="hint" style="color:#4ade80;margin-top:4px">âœ“ Till/Paybill saved. Leave blank to keep current.</div>
                    @endif
                </div>

                <button type="submit" class="save-btn">Save Changes</button>
            </form>
        </details>
    </div>

</div>

<script>
function copyLink() {
    navigator.clipboard.writeText(document.getElementById('tipUrl').value).then(() => {
        const btn = document.querySelector('.copy-btn');
        btn.textContent = 'âœ“ Copied';
        setTimeout(() => btn.textContent = 'Copy', 2000);
    });
}
</script>
</body>
</html>

