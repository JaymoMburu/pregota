﻿﻿<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<title>Till — {{ $payLink->business_name }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800;900&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0}input,textarea,select,button{font-family:inherit;font-size:inherit}
body{font-family:'Plus Jakarta Sans',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh;display:flex;flex-direction:column;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}

/* Header */
.hdr{padding:14px 24px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.07);flex-shrink:0}
.biz{font-size:17px;font-weight:900;color:#4ADE80}
.till-badge{font-size:11px;font-weight:700;background:rgba(37,211,102,.12);border:1px solid rgba(37,211,102,.25);color:#4ADE80;padding:4px 10px;border-radius:20px;letter-spacing:.06em;text-transform:uppercase}
.dash-link{font-size:12px;color:rgba(255,255,255,.65);text-decoration:none}
.dash-link:hover{color:rgba(255,255,255,.6)}

/* Main area */
.main{flex:1;display:flex;align-items:center;justify-content:center;padding:24px 20px}
.card{width:100%;max-width:480px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.09);border-radius:24px;padding:36px 32px;text-align:center}

/* Phase labels */
.phase-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:rgba(255,255,255,.65);margin-bottom:20px}

/* Amount display */
.amount-display{font-size:64px;font-weight:900;line-height:1;margin-bottom:6px;color:#fff;letter-spacing:-2px;min-height:72px}
.amount-display.has-val{color:#4ADE80}
.amount-hint{font-size:13px;color:rgba(255,255,255,.65);margin-bottom:28px;min-height:20px}

/* Keypad */
.keypad{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:20px}
.key{padding:18px 10px;font-size:22px;font-weight:700;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.09);border-radius:14px;cursor:pointer;color:#fff;user-select:none;transition:background .1s}
.key:hover,.key:active{background:rgba(255,255,255,.12)}
.key.zero{grid-column:span 2}
.key.del{color:rgba(255,255,255,.78)}

/* Buttons */
.btn-primary{width:100%;padding:16px;background:linear-gradient(135deg,#25D366,#1aaa52);color:#fff;font-size:17px;font-weight:900;border:none;border-radius:14px;cursor:pointer;margin-bottom:10px}
.btn-primary:hover{opacity:.9}
.btn-primary:disabled{opacity:.4;cursor:not-allowed}
.btn-reset{width:100%;padding:12px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);color:rgba(255,255,255,.78);font-size:14px;font-weight:700;border-radius:12px;cursor:pointer}
.btn-reset:hover{background:rgba(255,255,255,.09)}

/* Locked amount card */
.locked-amount{background:rgba(37,211,102,.06);border:1px solid rgba(37,211,102,.2);border-radius:16px;padding:24px;margin-bottom:24px}
.locked-label{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(37,211,102,.6);margin-bottom:6px}
.locked-val{font-size:56px;font-weight:900;color:#4ADE80;letter-spacing:-1px}
.locked-fee{font-size:13px;color:rgba(255,255,255,.65);margin-top:4px}

/* Phone input */
.phone-wrap{margin-bottom:20px;text-align:left}
.phone-label{display:block;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.72);margin-bottom:8px}
.phone-input{width:100%;padding:15px 16px;background:rgba(255,255,255,.07);border:2px solid rgba(255,255,255,.12);border-radius:12px;color:#fff;font-size:22px;font-weight:700;outline:none;font-family:inherit;letter-spacing:.05em;text-align:center}
.phone-input:focus{border-color:rgba(37,211,102,.45)}
.phone-hint{font-size:12px;color:rgba(255,255,255,.65);text-align:center;margin-top:7px}

/* Pending state */
.spinner{width:48px;height:48px;border:3px solid rgba(255,255,255,.1);border-top-color:#25D366;border-radius:50%;animation:spin .8s linear infinite;margin:0 auto 20px}
@keyframes spin{to{transform:rotate(360deg)}}
.pending-msg{font-size:16px;font-weight:700;margin-bottom:6px}
.pending-sub{font-size:13px;color:rgba(255,255,255,.72);line-height:1.55}

/* Confirmed state */
.check{font-size:64px;margin-bottom:16px;line-height:1}
.confirmed-amount{font-size:42px;font-weight:900;color:#4ADE80;margin-bottom:8px}
.confirmed-receipt{font-size:13px;color:rgba(255,255,255,.65);margin-bottom:6px}
.receipt-link{font-size:13px;color:#a78bfa;font-family:monospace}
.countdown{font-size:12px;color:rgba(255,255,255,.65);margin-top:18px}

/* Error */
.err-box{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);border-radius:10px;padding:12px 16px;font-size:14px;color:#fca5a5;margin-bottom:16px;display:none}
</style>
</head>
<body>

<div class="hdr">
    <div>
        <div class="biz">{{ $payLink->business_name }}</div>
    </div>
    <div style="display:flex;gap:12px;align-items:center">
        <span class="till-badge">Till Mode</span>
        <a href="{{ route('seller.dashboard') }}" class="dash-link">Dashboard →</a>
    </div>
</div>

<div class="main">
    <div class="card">

        {{-- Phase 1: Cashier enters amount --}}
        <div id="phase-amount">
            <div class="phase-label">Cashier — Enter Total</div>
            <div class="amount-display" id="amt-display">0</div>
            <div class="amount-hint" id="amt-hint">Use keypad below</div>

            <div class="keypad">
                <div class="key" onclick="key('1')">1</div>
                <div class="key" onclick="key('2')">2</div>
                <div class="key" onclick="key('3')">3</div>
                <div class="key" onclick="key('4')">4</div>
                <div class="key" onclick="key('5')">5</div>
                <div class="key" onclick="key('6')">6</div>
                <div class="key" onclick="key('7')">7</div>
                <div class="key" onclick="key('8')">8</div>
                <div class="key" onclick="key('9')">9</div>
                <div class="key zero" onclick="key('0')">0</div>
                <div class="key del" onclick="del()">⌫</div>
            </div>

            <button class="btn-primary" id="ready-btn" onclick="goPhoneEntry()" disabled>Ready — Hand to Customer →</button>
            <button class="btn-reset" onclick="resetTill()">Clear</button>
        </div>

        {{-- Phase 2: Customer enters phone --}}
        <div id="phase-phone" style="display:none">
            <div class="phase-label">Customer — Enter Your M-Pesa Number</div>

            <div class="locked-amount">
                <div class="locked-label">Amount to Pay</div>
                <div class="locked-val">KES <span id="locked-amt">0</span></div>
                <div class="locked-fee">Fee: KES <span id="locked-fee">0</span> · You pay KES <span id="locked-total">0</span></div>
            </div>

            <div class="err-box" id="phone-err"></div>

            <div class="phone-wrap">
                <label class="phone-label">Your Safaricom Number</label>
                <input type="tel" id="phone-input" class="phone-input" placeholder="0712 345 678" autocomplete="tel" inputmode="tel">
                <div class="phone-hint">You'll get an M-Pesa prompt — enter your PIN to pay</div>
            </div>

            <button class="btn-primary" id="pay-btn" onclick="initiatePay()">Pay with M-Pesa</button>
            <button class="btn-reset" onclick="backToAmount()">← Back (Cashier)</button>
        </div>

        {{-- Phase 3: Pending STK --}}
        <div id="phase-pending" style="display:none">
            <div class="phase-label">Waiting for M-Pesa</div>
            <div class="spinner"></div>
            <div class="pending-msg">M-Pesa prompt sent</div>
            <div class="pending-sub">Check your phone and enter your M-Pesa PIN to complete payment.</div>
        </div>

        {{-- Phase 4: Confirmed --}}
        <div id="phase-confirmed" style="display:none">
            <div class="check">✅</div>
            <div class="confirmed-amount">KES <span id="conf-amt">0</span></div>
            <div style="font-size:16px;font-weight:700;margin-bottom:20px">Payment Confirmed</div>
            <div class="confirmed-receipt">Receipt</div>
            <a id="conf-receipt-link" class="receipt-link" href="#" target="_blank"></a>
            <div class="countdown" id="countdown"></div>
            <button class="btn-reset" style="margin-top:20px" onclick="resetTill()">Next Customer →</button>
        </div>

    </div>
</div>

<script>
const CSRF   = '{{ csrf_token() }}';
const HANDLE = '{{ $payLink->handle }}';
let amountRaw  = '';
let checkoutId = null;
let pollTimer  = null;
let cdTimer    = null;

// ── Keypad ────────────────────────────────────────────────────────────────
function key(d) {
    if (amountRaw.length >= 6) return;
    amountRaw += d;
    render();
}
function del() {
    amountRaw = amountRaw.slice(0, -1);
    render();
}
function render() {
    const val = parseInt(amountRaw || '0');
    const disp = document.getElementById('amt-display');
    disp.textContent = val > 0 ? 'KES ' + val.toLocaleString() : '0';
    disp.className   = 'amount-display' + (val > 0 ? ' has-val' : '');

    const hint = document.getElementById('amt-hint');
    if (val < 10 && val > 0) hint.textContent = 'Minimum KES 10';
    else if (val > 150000)   hint.textContent = 'Maximum KES 150,000';
    else                     hint.textContent = val > 0 ? '' : 'Use keypad below';

    document.getElementById('ready-btn').disabled = val < 10 || val > 150000;
}

// ── Phase transitions ─────────────────────────────────────────────────────
function show(id) {
    ['phase-amount','phase-phone','phase-pending','phase-confirmed'].forEach(p =>
        document.getElementById(p).style.display = 'none'
    );
    document.getElementById(id).style.display = 'block';
}

function goPhoneEntry() {
    const amount = parseInt(amountRaw || '0');
    const fee    = Math.max(2, Math.ceil(amount * 0.01));
    document.getElementById('locked-amt').textContent   = amount.toLocaleString();
    document.getElementById('locked-fee').textContent   = fee.toLocaleString();
    document.getElementById('locked-total').textContent = (amount + fee).toLocaleString();
    document.getElementById('phone-input').value = '';
    document.getElementById('phone-err').style.display = 'none';
    show('phase-phone');
    setTimeout(() => document.getElementById('phone-input').focus(), 100);
}

function backToAmount() {
    show('phase-amount');
}

function resetTill() {
    clearTimeout(pollTimer);
    clearTimeout(cdTimer);
    amountRaw  = '';
    checkoutId = null;
    render();
    show('phase-amount');
}

// ── Pay ───────────────────────────────────────────────────────────────────
async function initiatePay() {
    const phone  = document.getElementById('phone-input').value.trim();
    const amount = parseInt(amountRaw || '0');
    const errEl  = document.getElementById('phone-err');

    if (!phone || !/^(\+?254|0)[17]\d{8}$/.test(phone.replace(/\s/g,''))) {
        errEl.textContent = 'Enter a valid Safaricom number (e.g. 0712 345 678).';
        errEl.style.display = 'block';
        return;
    }
    errEl.style.display = 'none';

    document.getElementById('pay-btn').disabled = true;
    show('phase-pending');

    const body = new URLSearchParams({phone, amount, _token: CSRF});
    try {
        const res  = await fetch(`/pay/${HANDLE}/pay`, {method:'POST', body});
        const data = await res.json();
        if (!res.ok || !data.payment_id) throw new Error(data.message || 'Failed');
        checkoutId = data.payment_id;
        pollStatus();
    } catch(e) {
        show('phase-phone');
        document.getElementById('pay-btn').disabled = false;
        errEl.textContent = 'Could not send M-Pesa prompt. Try again.';
        errEl.style.display = 'block';
    }
}

// ── Poll ─────────────────────────────────────────────────────────────────
function pollStatus() {
    if (!checkoutId) return;
    fetch(`/seller/status?payment_id=${checkoutId}`)
        .then(r => r.json())
        .then(d => {
            if (d.status === 'confirmed') {
                showConfirmed(d);
            } else if (d.status === 'failed' || d.status === 'cancelled') {
                show('phase-phone');
                document.getElementById('pay-btn').disabled = false;
                const errEl = document.getElementById('phone-err');
                errEl.textContent = 'Payment was cancelled or failed. Please try again.';
                errEl.style.display = 'block';
            } else {
                pollTimer = setTimeout(pollStatus, 2500);
            }
        })
        .catch(() => { pollTimer = setTimeout(pollStatus, 3000); });
}

function showConfirmed(d) {
    document.getElementById('conf-amt').textContent = parseInt(amountRaw || '0').toLocaleString();
    const link = document.getElementById('conf-receipt-link');
    if (d.receipt_number) {
        link.textContent = d.receipt_number;
        link.href = d.receipt_url || '#';
        link.style.display = 'block';
    } else {
        link.style.display = 'none';
    }
    show('phase-confirmed');

    // Auto-reset countdown
    let secs = 8;
    function tick() {
        document.getElementById('countdown').textContent = `Next customer in ${secs}s…`;
        if (secs-- <= 0) { resetTill(); return; }
        cdTimer = setTimeout(tick, 1000);
    }
    tick();
}

// Enter key on phone input
document.getElementById('phone-input').addEventListener('keydown', e => {
    if (e.key === 'Enter') initiatePay();
});

render();
</script>
</body>
</html>
