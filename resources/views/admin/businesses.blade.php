<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Businesses — Pregota Admin</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}input,textarea,select,button{font-family:inherit;font-size:inherit}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh}
.nav{padding:14px 24px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.08)}
.logo{font-size:18px;font-weight:900;background:linear-gradient(135deg,#00A651,#007A33);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.nav-links{display:flex;gap:16px;align-items:center}
.nav-link{color:rgba(255,255,255,.72);text-decoration:none;font-size:13px}
.nav-link.active{color:#a78bfa;font-weight:600}
.main{padding:24px;max-width:960px;margin:0 auto}
.page-title{font-size:20px;font-weight:900;margin-bottom:4px}
.page-sub{font-size:13px;color:rgba(255,255,255,.68);margin-bottom:24px}

.alert{border-radius:8px;padding:10px 12px;margin-bottom:16px;font-size:13px}
.alert.success{background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.25);color:#4ade80}
.alert.error{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);color:#f87171}

.card{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:14px;overflow:hidden;margin-bottom:24px}
.biz-row{display:grid;grid-template-columns:2fr 1fr 1fr 1fr auto;align-items:center;padding:14px 18px;border-bottom:1px solid rgba(255,255,255,.05);gap:12px}
.biz-row:last-child{border-bottom:none}
.biz-row.header{background:rgba(255,255,255,.03);font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.82)}
.biz-name{font-weight:700;font-size:14px}
.biz-meta{font-size:11px;color:rgba(255,255,255,.6);margin-top:2px}
.plan-badge{display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700}
.plan-badge.free{background:rgba(255,255,255,.08);color:rgba(255,255,255,.78)}
.plan-badge.paid{background:linear-gradient(135deg,#00A651,#007A33);color:#fff}
.expires{font-size:11px;color:rgba(255,255,255,.6)}
.expires.soon{color:#fbbf24}
.expires.expired{color:#f87171}
.actions{display:flex;gap:6px;align-items:center}
.btn-sm{background:none;border:1px solid rgba(255,255,255,.1);border-radius:6px;padding:5px 12px;color:rgba(255,255,255,.78);font-size:11px;cursor:pointer;text-decoration:none;display:inline-block}
.btn-sm:hover{background:rgba(255,255,255,.06);color:#fff}
.btn-sm.primary{color:#a78bfa;border-color:rgba(0,166,81,.3)}
.btn-sm.danger{color:#f87171;border-color:rgba(239,68,68,.2)}

/* Subscribe modal */
.modal{position:fixed;inset:0;background:rgba(0,0,0,.85);display:none;align-items:center;justify-content:center;z-index:200;padding:20px}
.modal.show{display:flex}
.modal-box{background:#13131f;border:1px solid rgba(255,255,255,.1);border-radius:20px;padding:28px;max-width:380px;width:100%}
.modal-title{font-size:16px;font-weight:800;margin-bottom:18px}
label{display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.68);margin-bottom:6px}
select,input{width:100%;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);border-radius:8px;padding:10px 12px;color:#fff;font-size:13px;outline:none;font-family:inherit;margin-bottom:14px}
select option{background:#0B1810}
select:focus,input:focus{border-color:#00A651}
.modal-btns{display:flex;gap:10px;margin-top:4px}
.btn-primary{background:linear-gradient(135deg,#00A651,#007A33);color:#fff;border:none;border-radius:8px;padding:10px 20px;font-size:13px;font-weight:700;cursor:pointer;flex:1}
.btn-cancel{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);color:rgba(255,255,255,.78);border-radius:8px;padding:10px 20px;font-size:13px;font-weight:600;cursor:pointer}
.no-data{text-align:center;padding:40px;color:rgba(255,255,255,.25);font-size:13px}
</style>
</head>
<body>
<nav class="nav">
    <a href="{{ route('admin.dashboard') }}" class="logo">Pregota Admin</a>
    <div class="nav-links">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">Vouchers</a>
        <a href="{{ route('admin.businesses') }}" class="nav-link active">Businesses</a>
        <a href="{{ route('admin.partners') }}" class="nav-link">Partners</a>
        <form method="POST" action="{{ route('admin.logout') }}" style="display:inline">
            @csrf
            <button type="submit" style="background:none;border:none;color:rgba(255,255,255,.82);cursor:pointer;font-size:13px">Logout</button>
        </form>
    </div>
</nav>

<div class="main">
    @if(session('success'))
    <div class="alert success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert error">{{ session('error') }}</div>
    @endif

    <div class="page-title">Businesses</div>
    <div class="page-sub">{{ $businesses->count() }} registered · {{ $businesses->where('plan', '!=', 'free')->count() }} subscribed</div>

    <div class="card">
        <div class="biz-row header">
            <div>Business</div>
            <div>Staff</div>
            <div>Plan</div>
            <div>Expires</div>
            <div>Actions</div>
        </div>

        @forelse($businesses as $biz)
        @php
            $isSubscribed = $biz->isSubscribed();
            $expiresSoon  = $biz->plan_expires_at && $biz->plan_expires_at->diffInDays(now()) <= 7 && $isSubscribed;
            $isExpired    = $biz->plan !== 'free' && $biz->plan_expires_at && $biz->plan_expires_at->lt(now());
        @endphp
        <div class="biz-row">
            <div>
                <div class="biz-name">{{ $biz->logo_emoji }} {{ $biz->name }}</div>
                <div class="biz-meta">{{ $biz->categoryLabel() }}@if($biz->city) · {{ $biz->city }}@endif · {{ $biz->email }}</div>
            </div>
            <div style="font-size:13px;color:rgba(255,255,255,.78)">{{ $biz->staff_count }}</div>
            <div>
                <span class="plan-badge {{ $isSubscribed ? 'paid' : 'free' }}">{{ $biz->planLabel() }}</span>
            </div>
            <div>
                @if($biz->plan === 'free')
                <span class="expires">—</span>
                @elseif($isExpired)
                <span class="expires expired">Expired {{ $biz->plan_expires_at->format('M j') }}</span>
                @elseif($expiresSoon)
                <span class="expires soon">{{ $biz->plan_expires_at->format('M j, Y') }} ⚠</span>
                @else
                <span class="expires">{{ $biz->plan_expires_at?->format('M j, Y') ?? 'No expiry' }}</span>
                @endif
            </div>
            <div class="actions">
                <button class="btn-sm primary" onclick="openSubscribe({{ $biz->id }}, '{{ addslashes($biz->name) }}')">
                    {{ $isSubscribed ? 'Extend' : 'Subscribe' }}
                </button>
                @if($isSubscribed || $isExpired)
                <form method="POST" action="{{ route('admin.businesses.cancel', $biz) }}"
                    onsubmit="return confirm('Cancel {{ $biz->name }} subscription?')">
                    @csrf
                    <button type="submit" class="btn-sm danger">Cancel</button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div class="no-data">No businesses registered yet</div>
        @endforelse
    </div>
</div>

<!-- Subscribe modal -->
<div class="modal" id="subscribeModal">
    <div class="modal-box">
        <div class="modal-title" id="modalTitle">Subscribe Business</div>
        <form method="POST" id="subscribeForm">
            @csrf
            <label>Plan</label>
            <select name="plan">
                <option value="starter">Starter — KES 1,500/mo (5 staff)</option>
                <option value="growth" selected>Growth — KES 3,500/mo (20 staff)</option>
                <option value="business">Business — KES 7,000/mo (50 staff)</option>
                <option value="enterprise">Enterprise — Custom</option>
            </select>

            <label>Months</label>
            <input type="number" name="months" value="1" min="1" max="12" placeholder="1">

            <div class="modal-btns">
                <button type="submit" class="btn-primary">Activate →</button>
                <button type="button" class="btn-cancel" onclick="closeSubscribe()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function openSubscribe(id, name) {
    document.getElementById('modalTitle').textContent = 'Subscribe: ' + name;
    document.getElementById('subscribeForm').action = '/admin/businesses/' + id + '/subscribe';
    document.getElementById('subscribeModal').classList.add('show');
}
function closeSubscribe() {
    document.getElementById('subscribeModal').classList.remove('show');
}
document.getElementById('subscribeModal').addEventListener('click', function(e) {
    if (e.target === this) closeSubscribe();
});
</script>
</body>
</html>
