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

/* Current route display */
.current-route-bar{margin:0 18px 0;background:rgba(37,211,102,.1);border:1px solid rgba(37,211,102,.25);border-radius:12px;padding:14px 16px;display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:12px}
.current-route-info{flex:1}
.current-route-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#25D366;margin-bottom:4px}
.current-route-name{font-size:16px;font-weight:900}
.current-fare-val{font-size:22px;font-weight:900;color:#25D366;white-space:nowrap}
.no-route-bar{margin:0 18px 12px;background:rgba(251,191,36,.07);border:1px solid rgba(251,191,36,.2);border-radius:12px;padding:12px 16px;font-size:13px;color:#fbbf24}

/* Change route form */
.change-route-btn{background:rgba(37,211,102,.12);border:1px solid rgba(37,211,102,.25);color:#25D366;font-size:12px;font-weight:700;padding:8px 14px;border-radius:8px;cursor:pointer;white-space:nowrap}
.route-form{display:none;margin:0 18px 12px;background:rgba(0,0,0,.3);border:1px solid rgba(255,255,255,.1);border-radius:14px;padding:18px}
.route-form.open{display:block}
.route-form h4{font-size:14px;font-weight:800;margin-bottom:14px}
.rf-group{margin-bottom:12px}
.rf-label{font-size:12px;font-weight:700;color:rgba(255,255,255,.75);margin-bottom:5px;display:block}
.rf-input{width:100%;padding:10px 12px;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.12);border-radius:9px;color:#fff;font-size:14px;font-family:inherit;outline:none}
.rf-input:focus{border-color:rgba(37,211,102,.5)}
.rf-row{display:grid;grid-template-columns:1fr 1fr;gap:10px}
.rf-submit{width:100%;padding:11px;background:linear-gradient(135deg,#25D366,#1aaa52);color:#fff;font-weight:800;font-size:14px;border:none;border-radius:9px;cursor:pointer;margin-top:4px}
.rf-cancel{background:none;border:none;color:rgba(255,255,255,.45);font-size:12px;cursor:pointer;margin-top:8px;display:block;width:100%;text-align:center}
.rf-error{font-size:12px;color:#fca5a5;margin-top:8px}
.rf-success{font-size:12px;color:#4ade80;margin-top:8px}
</style>
</head>
<body>

<div class="header">
    <div class="biz-name">
        {{ $payLink->business_name }}
        @if($payLink->category === 'transport')
        <span style="font-size:12px;color:rgba(255,255,255,.55);font-weight:700;margin-left:6px">{{ $payLink->displayIdentifier() }}</span>
        @endif
    </div>
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
    <div class="today-stat" id="tips-stat" style="display:none">
        <div class="today-val" id="today-tips" style="color:#fbbf24">—</div>
        <div class="today-label">Tips today</div>
    </div>
    @if($payLink->fixed_amount && $payLink->default_amount)
    <div class="today-stat">
        <div class="today-val" style="color:#fbbf24">KES {{ number_format($payLink->default_amount) }}</div>
        <div class="today-label">Fixed fare</div>
    </div>
    @endif
</div>

{{-- Current route display --}}
@if($payLink->current_route && $payLink->current_fare)
<div class="current-route-bar" id="current-route-bar">
    <div class="current-route-info">
        <div class="current-route-label">Current Route</div>
        <div class="current-route-name" id="live-route-name">{{ $payLink->current_route }}</div>
    </div>
    <div class="current-fare-val" id="live-fare-val">KES {{ number_format($payLink->current_fare) }}</div>
    <button class="change-route-btn" onclick="toggleRouteForm()">Change</button>
</div>
@else
<div class="no-route-bar" id="no-route-bar">
    ⚠️ No route set yet — tap below to set the current route and fare
</div>
@endif

{{-- Change route form --}}
<div class="route-form" id="route-form">
    <h4>Set Current Route & Fare</h4>
    <div class="rf-group">
        <label class="rf-label">Route</label>
        <input type="text" class="rf-input" id="rf-route" placeholder="e.g. CBD → Westlands" maxlength="100"
            value="{{ $payLink->current_route ?? '' }}">
    </div>
    <div class="rf-group">
        <label class="rf-label">Fare (KES)</label>
        <input type="number" class="rf-input" id="rf-fare" placeholder="e.g. 70" min="1" max="10000"
            value="{{ $payLink->current_fare ?? '' }}">
    </div>
    <div class="rf-group">
        <label class="rf-label">Password</label>
        <input type="password" class="rf-input" id="rf-password" placeholder="Your account password">
    </div>
    <button class="rf-submit" onclick="submitRoute()">✓ Set Route & Fare</button>
    <button class="rf-cancel" onclick="toggleRouteForm()">Cancel</button>
    <div id="rf-msg" style="display:none"></div>
</div>

<div class="sharing-hint">
    📲 <span>Passenger scans the QR sticker — they see the route and fare already on their screen. No need to say anything.</span>
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
    const todayTips = payments.reduce((s, p) => s + (p.tip_amount || 0), 0);
    if (todayTips > 0) {
        const tipsEl = document.getElementById('today-tips');
        if (tipsEl) {
            tipsEl.textContent = fmt(todayTips);
            document.getElementById('tips-stat').style.display = '';
        }
    }

    payments.forEach(p => {
        if (knownIds.has(p.id)) return;
        addedAny = true;

        const hasTip  = p.tip_amount > 0;
        const tipLine = hasTip
            ? `<div style="font-size:11px;color:#fbbf24;font-weight:700;margin-top:2px">🙏 +${fmt(p.tip_amount)} tip${p.tip_recipient ? ' → ' + p.tip_recipient : ''}${p.tip_comment ? ' · "' + p.tip_comment + '"' : ''}</div>`
            : '';

        const row = document.createElement('div');
        row.className = 'payment-row' + (firstLoad ? '' : ' new-flash');
        row.id        = 'pay-' + p.id;
        row.innerHTML = `
            <div class="payment-icon">${hasTip ? '🙏' : '✅'}</div>
            <div class="payment-info">
                <div class="payment-amount">${fmt(p.amount)}${hasTip ? ` <span style="font-size:13px;color:rgba(255,255,255,.5)">+ ${fmt(p.tip_amount)} tip</span>` : ''}</div>
                ${tipLine}
                <div class="payment-note" style="margin-top:${hasTip?'2':'0'}px">${p.note || ''}</div>
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

// ── Route change form ─────────────────────────────────────────────────────
function toggleRouteForm() {
    const form = document.getElementById('route-form');
    form.classList.toggle('open');
    if (form.classList.contains('open')) {
        document.getElementById('rf-route').focus();
        document.getElementById('rf-msg').style.display = 'none';
    }
}

function submitRoute() {
    const route    = document.getElementById('rf-route').value.trim();
    const fare     = parseInt(document.getElementById('rf-fare').value);
    const password = document.getElementById('rf-password').value;
    const msg      = document.getElementById('rf-msg');

    if (!route) { showMsg('Enter the route name.', false); return; }
    if (!fare || fare < 1) { showMsg('Enter a valid fare.', false); return; }
    if (!password) { showMsg('Enter your password.', false); return; }

    const btn = document.querySelector('.rf-submit');
    btn.disabled = true;
    btn.textContent = 'Saving…';

    fetch('{{ route('seller.set-route', $payLink->handle) }}', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: new URLSearchParams({
            current_route: route,
            current_fare: fare,
            password,
            _token: '{{ csrf_token() }}'
        }).toString(),
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        btn.textContent = '✓ Set Route & Fare';
        if (data.success) {
            showMsg('✓ Route updated — passengers see the new fare immediately.', true);
            // Update displayed values
            updateRouteDisplay(route, fare);
            document.getElementById('rf-password').value = '';
            setTimeout(() => document.getElementById('route-form').classList.remove('open'), 1500);
        } else {
            showMsg(data.message || 'Wrong password.', false);
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.textContent = '✓ Set Route & Fare';
        showMsg('Network error. Try again.', false);
    });
}

function showMsg(text, success) {
    const msg = document.getElementById('rf-msg');
    msg.textContent = text;
    msg.style.display = 'block';
    msg.className = success ? 'rf-success' : 'rf-error';
}

function updateRouteDisplay(route, fare) {
    // Show or update the current route bar
    const bar = document.getElementById('current-route-bar');
    const noBar = document.getElementById('no-route-bar');

    if (noBar) noBar.style.display = 'none';

    if (bar) {
        document.getElementById('live-route-name').textContent = route;
        document.getElementById('live-fare-val').textContent   = 'KES ' + fare.toLocaleString();
    } else {
        // Create bar dynamically if it wasn't rendered (no route was set initially)
        const newBar = document.createElement('div');
        newBar.id        = 'current-route-bar';
        newBar.className = 'current-route-bar';
        newBar.innerHTML = `
            <div class="current-route-info">
                <div class="current-route-label">Current Route</div>
                <div class="current-route-name" id="live-route-name">${route}</div>
            </div>
            <div class="current-fare-val" id="live-fare-val">KES ${fare.toLocaleString()}</div>
            <button class="change-route-btn" onclick="toggleRouteForm()">Change</button>`;
        document.getElementById('route-form').insertAdjacentElement('beforebegin', newBar);
    }
}
</script>

</body>
</html>
