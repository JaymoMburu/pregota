<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Live â€” {{ $payLink->business_name }}</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}input,textarea,select,button{font-family:inherit;font-size:inherit}
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

/* Conductor prompt panel */
.prompt-panel{margin:0 18px 14px;background:rgba(96,165,250,.07);border:1px solid rgba(96,165,250,.2);border-radius:14px;overflow:hidden}
.prompt-panel-header{padding:12px 16px;display:flex;align-items:center;justify-content:space-between;cursor:pointer;user-select:none}
.prompt-panel-title{font-size:13px;font-weight:800;color:#60a5fa}
.prompt-panel-sub{font-size:11px;color:rgba(255,255,255,.45);margin-top:1px}
.prompt-toggle{font-size:18px;color:rgba(96,165,250,.5);transition:.2s}
.prompt-body{display:none;padding:0 16px 16px}
.prompt-body.open{display:block}
.prompt-fare-row{display:flex;flex-wrap:wrap;gap:7px;margin-bottom:14px}
.pf-btn{padding:8px 14px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:10px;color:#fff;font-size:12px;font-weight:700;cursor:pointer;transition:.15s;text-align:center;line-height:1.3}
.pf-btn:hover{background:rgba(96,165,250,.12);border-color:rgba(96,165,250,.3)}
.pf-btn.selected{background:rgba(96,165,250,.18);border-color:#60a5fa;color:#93c5fd}
.pf-btn-amt{display:block;font-size:15px;font-weight:900;color:#60a5fa;margin-top:2px}
.pf-btn.selected .pf-btn-amt{color:#bfdbfe}
.prompt-waiting{display:none;background:rgba(251,191,36,.08);border:1px solid rgba(251,191,36,.2);border-radius:10px;padding:12px 14px;margin-top:10px;font-size:13px;color:#fbbf24;text-align:center}
.prompt-confirmed{display:none;background:rgba(37,211,102,.08);border:1px solid rgba(37,211,102,.2);border-radius:10px;padding:12px 14px;margin-top:10px;text-align:center}
.prompt-failed{display:none;background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2);border-radius:10px;padding:12px 14px;margin-top:10px;font-size:13px;color:#f87171;text-align:center}
.prompt-locked{padding:10px 16px 14px;font-size:12px;color:rgba(255,255,255,.4)}
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
        <div class="today-val" id="today-count">â€”</div>
        <div class="today-label">Payments today</div>
    </div>
    <div class="today-stat">
        <div class="today-val" id="today-total">â€”</div>
        <div class="today-label">Total collected</div>
    </div>
    <div class="today-stat" id="tips-stat" style="display:none">
        <div class="today-val" id="today-tips" style="color:#fbbf24">â€”</div>
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
    âš ï¸ No route set yet â€” tap below to set the current route and fare
</div>
@endif

{{-- Change route form --}}
<div class="route-form" id="route-form">
    <h4>Set Current Route & Fare</h4>
    <div class="rf-group">
        <label class="rf-label">Route</label>
        <input type="text" class="rf-input" id="rf-route" placeholder="e.g. CBD â†’ Westlands" maxlength="100"
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
    <button class="rf-submit" onclick="submitRoute()">âœ“ Set Route & Fare</button>
    <button class="rf-cancel" onclick="toggleRouteForm()">Cancel</button>
    <div id="rf-msg" style="display:none"></div>
</div>

{{-- Conductor: Prompt Passenger --}}
<div class="prompt-panel">
    <div class="prompt-panel-header" onclick="togglePrompt()">
        <div>
            <div class="prompt-panel-title">ðŸ“± Prompt Passenger</div>
            <div class="prompt-panel-sub">Enter their number â€” send M-Pesa prompt directly</div>
        </div>
        <div class="prompt-toggle" id="prompt-chevron">â€º</div>
    </div>

    @if($conductorUnlocked)
    <div class="prompt-body" id="prompt-body">

        {{-- Fare stage quick-select --}}
        @if($fares->isNotEmpty() || ($payLink->current_fare && $payLink->current_fare > 0))
        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.4);margin-bottom:8px">Select fare</div>
        <div class="prompt-fare-row" id="pf-buttons">
            @if($payLink->current_fare && $payLink->current_fare > 0)
            <button class="pf-btn" id="pf-live" onclick="selectPromptFare(this, {{ (int)$payLink->current_fare }}, '{{ addslashes($payLink->current_route ?? 'Current') }}')">
                {{ $payLink->current_route ?? 'Active fare' }}
                <span class="pf-btn-amt">KES {{ number_format($payLink->current_fare) }}</span>
            </button>
            @endif
            @foreach($fares as $fare)
            <button class="pf-btn" onclick="selectPromptFare(this, {{ $fare->amount }}, '{{ addslashes($fare->label) }}')">
                {{ $fare->label }}
                <span class="pf-btn-amt">KES {{ number_format($fare->amount) }}</span>
            </button>
            @endforeach
        </div>
        @endif

        <div class="rf-group" style="margin-bottom:10px">
            <label class="rf-label">Amount (KES)</label>
            <input type="number" id="prompt-amount" class="rf-input" placeholder="e.g. 70" min="1" max="10000">
        </div>
        <div class="rf-group" style="margin-bottom:12px">
            <label class="rf-label">Passenger's M-Pesa number</label>
            <input type="tel" id="prompt-phone" class="rf-input" placeholder="0712 345 678" autocomplete="off">
        </div>

        <button class="rf-submit" id="prompt-btn" onclick="sendPrompt()">ðŸ“± Send M-Pesa Prompt</button>

        <div class="prompt-waiting" id="prompt-waiting">
            ðŸ“± Prompt sent â€” waiting for passenger to enter PINâ€¦
            <div style="font-size:11px;color:rgba(255,255,255,.4);margin-top:4px" id="prompt-wait-detail"></div>
        </div>
        <div class="prompt-confirmed" id="prompt-confirmed">
            <div style="font-size:28px">âœ…</div>
            <div style="font-size:15px;font-weight:900;color:#4ade80;margin-top:4px" id="prompt-confirmed-amt"></div>
            <div style="font-size:12px;color:rgba(255,255,255,.5);margin-top:2px">Payment confirmed</div>
            <button onclick="resetPrompt()" style="margin-top:10px;background:rgba(37,211,102,.12);border:1px solid rgba(37,211,102,.3);border-radius:8px;color:#4ade80;font-size:12px;font-weight:700;padding:6px 16px;cursor:pointer">Prompt another</button>
        </div>
        <div class="prompt-failed" id="prompt-failed">
            âŒ Payment failed or declined.
            <button onclick="resetPrompt()" style="margin-left:10px;background:none;border:none;color:rgba(255,255,255,.5);font-size:12px;cursor:pointer;text-decoration:underline">Try again</button>
        </div>
    </div>
    @else
    <div class="prompt-locked" id="prompt-body">
        ðŸ”’ Set your route above first â€” entering your password unlocks this for the session.
    </div>
    @endif
</div>

<div class="section-label">Recent payments â€” updates automatically</div>

<div id="payment-list">
    <div class="empty">
        <div class="empty-icon">â³</div>
        <div class="empty-text">Waiting for paymentsâ€¦</div>
    </div>
</div>

<div class="status-bar" id="status-bar">Connectingâ€¦</div>

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
            list.innerHTML = '<div class="empty"><div class="empty-icon">â³</div><div class="empty-text">Waiting for paymentsâ€¦</div></div>';
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
            ? `<div style="font-size:11px;color:#fbbf24;font-weight:700;margin-top:2px">ðŸ™ +${fmt(p.tip_amount)} tip${p.tip_recipient ? ' â†’ ' + p.tip_recipient : ''}${p.tip_comment ? ' Â· "' + p.tip_comment + '"' : ''}</div>`
            : '';

        const row = document.createElement('div');
        row.className = 'payment-row' + (firstLoad ? '' : ' new-flash');
        row.id        = 'pay-' + p.id;
        row.innerHTML = `
            <div class="payment-icon">${hasTip ? 'ðŸ™' : 'âœ…'}</div>
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
            document.getElementById('status-bar').textContent = 'Connection error â€” retryingâ€¦';
        });
}

poll();
setInterval(poll, 3000);

// â”€â”€ Route change form â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
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
    btn.textContent = 'Savingâ€¦';

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
        btn.textContent = 'âœ“ Set Route & Fare';
        if (data.success) {
            showMsg('âœ“ Route updated â€” passengers see the new fare immediately.', true);
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
        btn.textContent = 'âœ“ Set Route & Fare';
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
    const bar = document.getElementById('current-route-bar');
    const noBar = document.getElementById('no-route-bar');

    if (noBar) noBar.style.display = 'none';

    if (bar) {
        document.getElementById('live-route-name').textContent = route;
        document.getElementById('live-fare-val').textContent   = 'KES ' + fare.toLocaleString();
    } else {
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

    // When conductor sets a live fare, update the live-fare button in the prompt panel
    const liveBtn = document.getElementById('pf-live');
    if (liveBtn) {
        liveBtn.querySelector('.pf-btn-amt').textContent = 'KES ' + fare.toLocaleString();
        liveBtn.onclick = () => selectPromptFare(liveBtn, fare, route);
    } else {
        // Inject a live-fare button if there wasn't one before
        const pfButtons = document.getElementById('pf-buttons');
        if (pfButtons) {
            const btn = document.createElement('button');
            btn.id = 'pf-live';
            btn.className = 'pf-btn';
            btn.innerHTML = `${route}<span class="pf-btn-amt">KES ${fare.toLocaleString()}</span>`;
            btn.onclick = () => selectPromptFare(btn, fare, route);
            pfButtons.insertBefore(btn, pfButtons.firstChild);
        }
    }

    // Unlock prompt panel if it was locked (session established by setRoute)
    const locked = document.querySelector('.prompt-locked');
    if (locked) {
        locked.innerHTML = '<div class="prompt-body open" style="padding-bottom:16px">âœ“ Unlocked â€” enter passenger phone below.</div>';
    }
}

// â”€â”€ Conductor Prompt â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
let promptPollTimer = null;
let promptOpen = false;

function togglePrompt() {
    promptOpen = !promptOpen;
    document.getElementById('prompt-body').classList.toggle('open', promptOpen);
    document.getElementById('prompt-chevron').style.transform = promptOpen ? 'rotate(90deg)' : '';
}

function selectPromptFare(btn, amount, label) {
    document.querySelectorAll('.pf-btn').forEach(b => b.classList.remove('selected'));
    btn.classList.add('selected');
    document.getElementById('prompt-amount').value = amount;
}

let promptPaymentId = null;

function sendPrompt() {
    const phone  = document.getElementById('prompt-phone').value.trim();
    const amount = parseInt(document.getElementById('prompt-amount').value);

    if (!phone || !/^(\+?254|0)[17]\d{8}$/.test(phone)) {
        alert('Enter a valid Safaricom number.');
        return;
    }
    if (!amount || amount < 1) {
        alert('Select a fare or enter an amount.');
        return;
    }

    const btn = document.getElementById('prompt-btn');
    btn.disabled = true;
    btn.textContent = 'Sendingâ€¦';

    fetch('{{ route('seller.conductor.prompt', $payLink->handle) }}', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: new URLSearchParams({phone, amount, _token: '{{ csrf_token() }}'}).toString(),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            promptPaymentId = data.payment_id;
            document.getElementById('prompt-btn').style.display = 'none';
            document.getElementById('prompt-wait-detail').textContent = 'KES ' + amount.toLocaleString() + ' â†’ ' + phone;
            document.getElementById('prompt-waiting').style.display = 'block';
            promptPollTimer = setInterval(pollPromptStatus, 3000);
        } else {
            btn.disabled = false;
            btn.textContent = 'ðŸ“± Send M-Pesa Prompt';
            alert(data.message || 'Failed. Try again.');
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.textContent = 'ðŸ“± Send M-Pesa Prompt';
        alert('Network error.');
    });
}

function pollPromptStatus() {
    if (!promptPaymentId) return;
    fetch('{{ route('seller.status') }}?payment_id=' + promptPaymentId)
        .then(r => r.json())
        .then(data => {
            if (data.status === 'confirmed') {
                clearInterval(promptPollTimer);
                const amount = parseInt(document.getElementById('prompt-amount').value);
                document.getElementById('prompt-waiting').style.display = 'none';
                document.getElementById('prompt-confirmed-amt').textContent = 'KES ' + amount.toLocaleString();
                document.getElementById('prompt-confirmed').style.display = 'block';
            } else if (data.status === 'failed') {
                clearInterval(promptPollTimer);
                document.getElementById('prompt-waiting').style.display = 'none';
                document.getElementById('prompt-failed').style.display = 'block';
            }
        });
}

function resetPrompt() {
    clearInterval(promptPollTimer);
    promptPaymentId = null;
    document.getElementById('prompt-phone').value  = '';
    document.getElementById('prompt-amount').value = '';
    document.querySelectorAll('.pf-btn').forEach(b => b.classList.remove('selected'));
    document.getElementById('prompt-btn').style.display = 'block';
    document.getElementById('prompt-btn').disabled = false;
    document.getElementById('prompt-btn').textContent = 'ðŸ“± Send M-Pesa Prompt';
    document.getElementById('prompt-waiting').style.display  = 'none';
    document.getElementById('prompt-confirmed').style.display = 'none';
    document.getElementById('prompt-failed').style.display   = 'none';
}
</script>

</body>
</html>

