<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Live — {{ $payLink->business_name }}</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
html,body{height:100%}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0a1a0f;color:#fff;min-height:100vh}

.header{padding:14px 18px;background:rgba(0,0,0,.4);border-bottom:1px solid rgba(37,211,102,.15);display:flex;align-items:center;justify-content:space-between}
.biz-name{font-size:15px;font-weight:900;color:#25D366}
.live-dot{display:flex;align-items:center;gap:6px;font-size:11px;color:rgba(255,255,255,.6)}
.dot{width:8px;height:8px;background:#25D366;border-radius:50%;animation:pulse 1.5s infinite}
@keyframes pulse{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.5;transform:scale(.8)}}

.today-bar{padding:16px 18px;background:rgba(37,211,102,.06);border-bottom:1px solid rgba(37,211,102,.1);display:flex;gap:24px}
.today-stat{text-align:center}
.today-val{font-size:22px;font-weight:900;color:#25D366}
.today-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.5);margin-top:2px}

.section-label{padding:10px 18px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.45);background:rgba(0,0,0,.2)}

#payment-list{padding:0}

.payment-row{padding:14px 18px;border-bottom:1px solid rgba(255,255,255,.05);display:flex;align-items:center;gap:14px;transition:.3s}
.payment-row.new-flash{background:rgba(37,211,102,.18);animation:flash .8s ease-out forwards}
@keyframes flash{0%{background:rgba(37,211,102,.35)}100%{background:transparent}}
.payment-icon{width:40px;height:40px;background:rgba(37,211,102,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0}
.payment-info{flex:1;min-width:0}
.payment-amount{font-size:18px;font-weight:900;color:#25D366}
.payment-note{font-size:12px;color:rgba(255,255,255,.55);margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.payment-time{font-size:11px;color:rgba(255,255,255,.4);text-align:right;flex-shrink:0}
.payment-abs{font-size:13px;font-weight:700;color:rgba(255,255,255,.7)}
.payment-rel{font-size:11px;color:rgba(255,255,255,.4);margin-top:1px}

.empty{padding:48px 24px;text-align:center;color:rgba(255,255,255,.4)}
.empty-icon{font-size:40px;margin-bottom:12px}
.empty-text{font-size:14px}

.status-bar{position:fixed;bottom:0;left:0;right:0;padding:10px 18px;background:rgba(0,0,0,.5);border-top:1px solid rgba(255,255,255,.07);font-size:11px;color:rgba(255,255,255,.4);text-align:center}

.sharing-hint{margin:16px 18px;background:rgba(96,165,250,.08);border:1px solid rgba(96,165,250,.18);border-radius:12px;padding:12px 16px;font-size:12px;color:rgba(255,255,255,.6);display:flex;gap:10px;align-items:center}
.sharing-hint strong{color:#60a5fa}
</style>
</head>
<body>

<div class="header">
    <div class="biz-name">{{ $payLink->business_name }}</div>
    <div class="live-dot"><span class="dot"></span> LIVE</div>
</div>

<div class="today-bar">
    <div class="today-stat">
        <div class="today-val" id="today-count">—</div>
        <div class="today-label">Payments today</div>
    </div>
    <div class="today-stat">
        <div class="today-val" id="today-total">—</div>
        <div class="today-label">Total collected</div>
    </div>
    @if($payLink->fixed_amount && $payLink->default_amount)
    <div class="today-stat">
        <div class="today-val" style="color:#fbbf24">KES {{ number_format($payLink->default_amount) }}</div>
        <div class="today-label">Fixed fare</div>
    </div>
    @endif
</div>

<div class="sharing-hint">
    📲 <span>Passenger scans QR or opens: <strong>pregota.com/pay/{{ $payLink->handle }}</strong> — they type the fare you call, then pay</span>
</div>

<div class="section-label">Recent payments — updates automatically</div>

<div id="payment-list">
    <div class="empty">
        <div class="empty-icon">⏳</div>
        <div class="empty-text">Waiting for payments…</div>
    </div>
</div>

<div class="status-bar" id="status-bar">Connecting…</div>

<script>
let knownIds    = new Set();
let todayCount  = 0;
let todayTotal  = 0;
let firstLoad   = true;

function fmt(n) {
    return 'KES ' + n.toLocaleString();
}

function renderPayments(payments) {
    const list = document.getElementById('payment-list');

    if (payments.length === 0) {
        if (firstLoad) {
            list.innerHTML = '<div class="empty"><div class="empty-icon">⏳</div><div class="empty-text">Waiting for payments…</div></div>';
        }
        firstLoad = false;
        return;
    }

    let newIds    = payments.map(p => p.id);
    let addedAny  = false;

    // Rebuild totals
    todayCount = payments.length;
    todayTotal = payments.reduce((s, p) => s + p.amount, 0);

    payments.forEach(p => {
        if (knownIds.has(p.id)) return;
        addedAny = true;

        const row = document.createElement('div');
        row.className = 'payment-row' + (firstLoad ? '' : ' new-flash');
        row.id        = 'pay-' + p.id;
        row.innerHTML = `
            <div class="payment-icon">✅</div>
            <div class="payment-info">
                <div class="payment-amount">${fmt(p.amount)}</div>
                <div class="payment-note">${p.note || 'No note'}</div>
            </div>
            <div class="payment-time">
                <div class="payment-abs">${p.time_abs}</div>
                <div class="payment-rel">${p.time}</div>
            </div>`;

        // Insert at top
        const firstChild = list.querySelector('.payment-row');
        if (firstChild) {
            list.insertBefore(row, firstChild);
        } else {
            list.innerHTML = '';
            list.appendChild(row);
        }

        knownIds.add(p.id);
    });

    document.getElementById('today-count').textContent = todayCount;
    document.getElementById('today-total').textContent  = fmt(todayTotal);

    firstLoad = false;
}

function poll() {
    fetch('{{ route('seller.recent', $payLink->handle) }}')
        .then(r => r.json())
        .then(data => {
            renderPayments(data);
            document.getElementById('status-bar').textContent = 'Updated ' + new Date().toLocaleTimeString();
        })
        .catch(() => {
            document.getElementById('status-bar').textContent = 'Connection error — retrying…';
        });
}

poll();
setInterval(poll, 3000);

// Keep display times fresh
setInterval(() => {
    document.querySelectorAll('.payment-rel').forEach(el => {
        // minor update — just show "just now" for recent rows
    });
}, 30000);
</script>

</body>
</html>
