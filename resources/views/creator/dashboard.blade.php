<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Creator Dashboard — Pregota</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0f0f1a;color:#fff;min-height:100vh}
.nav{padding:14px 24px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.08)}
.logo{font-size:20px;font-weight:900;background:linear-gradient(135deg,#7c3aed,#db2777);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.nav-right{display:flex;align-items:center;gap:14px}
.nav-handle{font-size:13px;color:rgba(255,255,255,.4)}
.logout-btn{background:none;border:none;color:rgba(255,255,255,.35);cursor:pointer;font-size:13px}

.main{padding:24px;max-width:800px;margin:0 auto}

/* Stats */
.stats{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:24px}
@media(max-width:600px){.stats{grid-template-columns:1fr 1fr}}
.stat{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:16px}
.stat-val{font-size:20px;font-weight:900;background:linear-gradient(135deg,#c084fc,#f472b6);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.stat-lbl{font-size:11px;color:rgba(255,255,255,.35);margin-top:4px;text-transform:uppercase;letter-spacing:.06em}

/* Creator link card */
.link-card{background:rgba(124,58,237,.08);border:1px solid rgba(124,58,237,.25);border-radius:14px;padding:18px 20px;margin-bottom:24px}
.link-title{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.4);margin-bottom:8px}
.link-url{font-size:16px;font-weight:700;color:#c084fc;font-family:monospace;word-break:break-all}
.link-actions{display:flex;gap:10px;margin-top:12px;flex-wrap:wrap}
.link-btn{font-size:12px;font-weight:600;padding:7px 14px;border-radius:8px;cursor:pointer;border:none;transition:.15s}
.btn-copy{background:rgba(124,58,237,.2);color:#a78bfa}
.btn-obs{background:rgba(34,197,94,.12);color:#4ade80}
.btn-view{background:rgba(255,255,255,.06);color:rgba(255,255,255,.6)}

/* Goal */
.goal-bar-wrap{height:6px;background:rgba(255,255,255,.07);border-radius:999px;overflow:hidden;margin:8px 0 4px}
.goal-fill{height:100%;background:linear-gradient(90deg,#7c3aed,#db2777);border-radius:999px}

/* Gift feed */
.feed-title{font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.3);margin-bottom:12px}
.gift-item{display:flex;justify-content:space-between;align-items:flex-start;padding:12px 0;border-bottom:1px solid rgba(255,255,255,.05)}
.gift-item:last-child{border-bottom:none}
.gift-amount{font-size:16px;font-weight:800;background:linear-gradient(135deg,#c084fc,#f472b6);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.gift-from{font-size:12px;color:rgba(255,255,255,.4);margin-top:2px}
.gift-msg{font-size:12px;color:rgba(255,255,255,.5);font-style:italic;margin-top:4px}
.gift-time{font-size:11px;color:rgba(255,255,255,.25);white-space:nowrap;margin-left:12px}
.no-gifts{text-align:center;padding:32px;color:rgba(255,255,255,.25);font-size:13px}

/* Profile edit */
.edit-form{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:14px;padding:20px;margin-top:24px}
.edit-title{font-size:13px;font-weight:700;color:rgba(255,255,255,.5);margin-bottom:16px}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px}
@media(max-width:500px){.form-row{grid-template-columns:1fr}}
.form-group{display:flex;flex-direction:column;gap:5px}
label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.4)}
input{background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:8px;padding:10px 12px;color:#fff;font-size:13px;outline:none}
input:focus{border-color:#7c3aed}
.save-btn{background:linear-gradient(135deg,#7c3aed,#db2777);color:#fff;border:none;border-radius:10px;padding:10px 22px;font-size:14px;font-weight:700;cursor:pointer;margin-top:4px}
.alert{border-radius:8px;padding:10px 12px;margin-bottom:14px;font-size:13px}
.alert.success{background:rgba(34,197,94,.12);border:1px solid rgba(34,197,94,.3);color:#4ade80}
</style>
</head>
<body>
<nav class="nav">
    <div class="logo">Pregota Creators</div>
    <div class="nav-right">
        <span class="nav-handle">@{{ $creator->handle }}</span>
        <form method="POST" action="{{ route('creator.logout') }}" style="display:inline">
            @csrf
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>
</nav>

<div class="main">
    @if(session('success'))
    <div class="alert success">{{ session('success') }}</div>
    @endif

    <!-- Stats -->
    <div class="stats">
        <div class="stat">
            <div class="stat-val">KES {{ number_format($stats['today'], 0) }}</div>
            <div class="stat-lbl">Today</div>
        </div>
        <div class="stat">
            <div class="stat-val">KES {{ number_format($stats['month'], 0) }}</div>
            <div class="stat-lbl">This month</div>
        </div>
        <div class="stat">
            <div class="stat-val">KES {{ number_format($stats['total'], 0) }}</div>
            <div class="stat-lbl">All time</div>
        </div>
        <div class="stat">
            <div class="stat-val">{{ $stats['count'] }}</div>
            <div class="stat-lbl">Total gifts</div>
        </div>
    </div>

    <!-- Creator link + goal -->
    <div class="link-card">
        <div class="link-title">Your Gift Page</div>
        <div class="link-url" id="creatorUrl">{{ url('/c/' . $creator->handle) }}</div>
        <div class="link-actions">
            <button class="link-btn btn-copy" onclick="copyLink()">Copy Link</button>
            <a href="{{ route('creator.page', $creator->handle) }}" target="_blank" class="link-btn btn-view" style="text-decoration:none">Preview Page</a>
            <button class="link-btn btn-obs" onclick="copyObs()">Copy OBS Alert URL</button>
        </div>
        @if($creator->goal_title && $creator->goal_amount)
        <div style="margin-top:14px">
            <div style="display:flex;justify-content:space-between;font-size:12px;color:rgba(255,255,255,.4)">
                <span>{{ $creator->goal_title }}</span>
                <span>{{ $creator->goalProgress() }}%</span>
            </div>
            <div class="goal-bar-wrap"><div class="goal-fill" style="width:{{ $creator->goalProgress() }}%"></div></div>
            <div style="font-size:11px;color:rgba(255,255,255,.25)">KES {{ number_format($creator->total_received, 0) }} of KES {{ number_format($creator->goal_amount, 0) }}</div>
        </div>
        @endif
    </div>

    <!-- Recent gifts -->
    <div class="feed-title">Recent Gifts</div>

    @if($gifts->isEmpty())
    <div class="no-gifts">No gifts yet — share your link to get started</div>
    @else
    @foreach($gifts as $gift)
    <div class="gift-item">
        <div>
            <div class="gift-amount">KES {{ number_format($gift->payout_amount, 2) }}</div>
            <div class="gift-from">{{ $gift->fan_name ?: 'Anonymous' }}</div>
            @if($gift->message)
            <div class="gift-msg">"{{ $gift->message }}"</div>
            @endif
        </div>
        <div class="gift-time">{{ $gift->created_at->diffForHumans() }}</div>
    </div>
    @endforeach
    @endif

    <!-- Profile edit -->
    <div class="edit-form">
        <div class="edit-title">Edit Profile & Settings</div>
        <form method="POST" action="{{ route('creator.profile') }}">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label>Display Name</label>
                    <input type="text" name="display_name" value="{{ $creator->display_name }}" required>
                </div>
                <div class="form-group">
                    <label>Bio</label>
                    <input type="text" name="bio" value="{{ $creator->bio }}" maxlength="200">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Goal Title</label>
                    <input type="text" name="goal_title" value="{{ $creator->goal_title }}">
                </div>
                <div class="form-group">
                    <label>Goal Amount (KES)</label>
                    <input type="number" name="goal_amount" value="{{ $creator->goal_amount }}" min="100">
                </div>
            </div>
            <div class="form-group" style="margin-bottom:14px">
                <label>Minimum Gift (KES)</label>
                <input type="number" name="min_gift" value="{{ $creator->min_gift_amount }}" min="50">
            </div>
            <button type="submit" class="save-btn">Save Changes</button>
        </form>
    </div>
</div>

<script>
function copyLink() {
    navigator.clipboard.writeText(document.getElementById('creatorUrl').textContent.trim()).then(() => {
        const btn = event.target;
        btn.textContent = 'Copied!';
        setTimeout(() => btn.textContent = 'Copy Link', 2000);
    });
}
function copyObs() {
    const obsUrl = '{{ url("/c/" . $creator->handle . "/alert/" . $creator->alert_token) }}';
    navigator.clipboard.writeText(obsUrl).then(() => {
        const btn = event.target;
        btn.textContent = 'Copied!';
        setTimeout(() => btn.textContent = 'Copy OBS Alert URL', 2000);
    });
}
</script>
</body>
</html>
