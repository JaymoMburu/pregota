<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Collection — {{ $collection->title }}</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}input,textarea,select,button{font-family:inherit;font-size:inherit}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh}

.topbar{padding:14px 20px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.07);background:#0B141A}
.logo{font-size:18px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.badge{font-size:11px;padding:4px 10px;border-radius:20px;font-weight:700}
.badge.open{background:rgba(34,197,94,.15);border:1px solid rgba(34,197,94,.3);color:#4ade80}
.badge.closed{background:rgba(239,68,68,.12);border:1px solid rgba(239,68,68,.25);color:#f87171}
.badge.paid{background:rgba(0,166,81,.15);border:1px solid rgba(0,166,81,.3);color:#25D366}

.page{max-width:700px;margin:0 auto;padding:24px 20px 60px}

.alert{padding:12px 16px;border-radius:10px;margin-bottom:18px;font-size:13px;font-weight:600}
.alert.success{background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.25);color:#4ade80}
.alert.error{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);color:#f87171}

h2{font-size:22px;font-weight:900;margin-bottom:4px}
.subtitle{font-size:13px;color:rgba(255,255,255,.68);margin-bottom:24px}

.stats-row{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:24px}
.stat-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:12px;padding:16px;text-align:center}
.stat-num{font-size:26px;font-weight:900}
.stat-num.purple{color:#25D366}
.stat-label{font-size:11px;color:rgba(255,255,255,.68);margin-top:3px}

.progress-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:12px;padding:16px;margin-bottom:24px}
.progress-head{display:flex;justify-content:space-between;font-size:13px;color:rgba(255,255,255,.72);margin-bottom:8px}
.progress-bar-wrap{height:8px;background:rgba(255,255,255,.08);border-radius:4px;overflow:hidden}
.progress-bar-fill{height:100%;background:linear-gradient(90deg,#00A651,#007A33);border-radius:4px}

.section-title{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.6);margin-bottom:12px}

.share-block{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.08);border-radius:12px;padding:16px;margin-bottom:24px}
.link-row{display:flex;gap:8px;align-items:center;margin-top:8px}
.link-input{flex:1;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);border-radius:8px;padding:10px 12px;color:rgba(255,255,255,.6);font-size:12px;outline:none;font-family:monospace}
.copy-btn{padding:10px 16px;border-radius:8px;background:rgba(0,166,81,.2);border:1px solid rgba(0,166,81,.3);color:#25D366;font-size:12px;font-weight:700;cursor:pointer;white-space:nowrap}
.wa-btn{display:inline-flex;align-items:center;gap:6px;padding:10px 16px;border-radius:8px;background:#25d366;color:#fff;font-size:12px;font-weight:700;text-decoration:none;white-space:nowrap}

.actions-row{display:flex;gap:10px;margin-bottom:24px;flex-wrap:wrap}
.btn-payout{flex:1;min-width:160px;padding:14px;border-radius:10px;border:none;font-size:14px;font-weight:700;cursor:pointer;background:linear-gradient(135deg,#00A651,#007A33);color:#fff}
.btn-payout:hover{opacity:.9}
.btn-close{flex:1;min-width:120px;padding:14px;border-radius:10px;border:1px solid rgba(239,68,68,.35);background:rgba(239,68,68,.08);color:#f87171;font-size:14px;font-weight:700;cursor:pointer}
.btn-close:hover{background:rgba(239,68,68,.14)}

.table-wrap{overflow-x:auto}
table{width:100%;border-collapse:collapse}
th{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.6);padding:10px 12px;text-align:left;border-bottom:1px solid rgba(255,255,255,.07)}
td{padding:12px;font-size:13px;border-bottom:1px solid rgba(255,255,255,.05);color:rgba(255,255,255,.75)}
tr:last-child td{border-bottom:none}
.status-pill{display:inline-block;padding:3px 8px;border-radius:12px;font-size:10px;font-weight:700}
.status-pill.paid{background:rgba(34,197,94,.12);color:#4ade80}
.status-pill.pending{background:rgba(251,191,36,.1);color:#fbbf24}
.status-pill.failed{background:rgba(239,68,68,.1);color:#f87171}
.empty-row td{text-align:center;color:rgba(255,255,255,.82);padding:28px}

.warning-box{background:rgba(251,191,36,.08);border:1px solid rgba(251,191,36,.2);color:#fbbf24;border-radius:10px;padding:12px 14px;font-size:12px;margin-bottom:16px}

@media(max-width:500px){
    .stats-row{grid-template-columns:1fr 1fr}
    .stat-card:last-child{grid-column:span 2}
}
</style>
</head>
<body>

<div class="topbar">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    <span class="badge {{ $collection->status }}">{{ ucfirst($collection->status) }}</span>
</div>

<div class="page">

    @if(session('created'))
    <div class="alert success">🎉 Collection created! Bookmark this page — it's your private organiser dashboard.</div>
    @endif

    @if(session('success'))
    <div class="alert success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
    <div class="alert error">{{ session('error') }}</div>
    @endif

    <h2>{{ $collection->title }}</h2>
    <div class="subtitle">
        {{ $collection->occasionEmoji() }} {{ $collection->occasionLabel() }} ·
        Recipient: {{ $collection->recipient_name }}
        @if($collection->deadline)
        · Deadline: {{ $collection->deadline->format('j M Y') }}
        @endif
    </div>

    <!-- Stats -->
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-num purple">KES {{ number_format($collection->total_raised) }}</div>
            <div class="stat-label">Total Raised</div>
        </div>
        <div class="stat-card">
            <div class="stat-num">{{ number_format($collection->contributor_count) }}</div>
            <div class="stat-label">Contributors</div>
        </div>
        <div class="stat-card">
            <div class="stat-num">{{ $collection->payout_trigger === 'manual' ? 'Manual' : ($collection->payout_trigger === 'target' ? 'On target' : 'On deadline') }}</div>
            <div class="stat-label">Payout trigger</div>
        </div>
    </div>

    @if($collection->target_amount)
    <div class="progress-card">
        <div class="progress-head">
            <span>Progress to goal</span>
            <span>KES {{ number_format($collection->total_raised) }} / {{ number_format($collection->target_amount) }} ({{ $collection->progressPct() }}%)</span>
        </div>
        <div class="progress-bar-wrap">
            <div class="progress-bar-fill" style="width:{{ $collection->progressPct() }}%"></div>
        </div>
    </div>
    @endif

    <!-- Share -->
    @if($collection->isOpen())
    <div class="share-block">
        <div class="section-title">Share with Contributors</div>
        <div class="link-row">
            <input class="link-input" id="shareUrl" readonly
                   value="{{ route('collection.show', $collection->slug) }}">
            <button class="copy-btn" onclick="copyUrl()">Copy</button>
            <a href="{{ waShareUrl($collection) }}" target="_blank" class="wa-btn">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.050 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                WhatsApp
            </a>
        </div>
    </div>

    <!-- Actions -->
    @if($collection->total_raised > 0)
    <div class="warning-box">
        ⚠️ Once you initiate payout, funds are sent directly to {{ $collection->recipient_name }}'s M-Pesa. This cannot be reversed. The recipient phone is immediately deleted from our system.
    </div>
    @endif

    <div class="actions-row">
        <form method="POST" action="{{ route('collection.payout', $collection->slug) }}"
              onsubmit="return confirm('Send KES {{ number_format($collection->total_raised) }} to {{ $collection->recipient_name }}? This cannot be undone.')">
            @csrf
            <input type="hidden" name="token" value="{{ request()->query('token') }}">
            <button type="submit" class="btn-payout"
                    {{ $collection->total_raised === 0 ? 'disabled style=opacity:.4;cursor:not-allowed' : '' }}>
                ⚡ Pay Out to {{ $collection->recipient_name }}
            </button>
        </form>
        <form method="POST" action="{{ route('collection.close', $collection->slug) }}"
              onsubmit="return confirm('Close this collection? No new contributions will be accepted.')">
            @csrf
            <input type="hidden" name="token" value="{{ request()->query('token') }}">
            <button type="submit" class="btn-close">🔒 Close Collection</button>
        </form>
    </div>
    @endif

    <!-- Contributions table -->
    <div class="section-title">All Contributions ({{ $contributions->count() }})</div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contributions as $c)
                <tr>
                    <td>{{ $c->displayName() }}</td>
                    <td>KES {{ number_format($c->amount) }}</td>
                    <td><span class="status-pill {{ $c->status }}">{{ ucfirst($c->status) }}</span></td>
                    <td style="color:rgba(255,255,255,.68)">{{ $c->created_at->format('j M, H:i') }}</td>
                </tr>
                @empty
                <tr class="empty-row"><td colspan="4">No contributions yet — share your link!</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

<script>
function copyUrl() {
    const el = document.getElementById('shareUrl');
    navigator.clipboard.writeText(el.value).then(() => {
        const btn = document.querySelector('.copy-btn');
        btn.textContent = '✓ Copied';
        setTimeout(() => btn.textContent = 'Copy', 2000);
    });
}
</script>
</body>
</html>
