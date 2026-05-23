<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pay Your Share — {{ $bill->label ?? 'Bill Split' }}</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh;display:flex;flex-direction:column;align-items:center;padding:24px 20px}

.card{width:100%;max-width:400px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.09);border-radius:24px;padding:28px 24px;margin-top:12px}

/* Bill header */
.bill-label{font-size:12px;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.6);margin-bottom:4px;text-align:center}
.bill-total{font-size:13px;color:rgba(255,255,255,.68);text-align:center;margin-bottom:16px}

/* Progress */
.progress-wrap{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);border-radius:12px;padding:14px 16px;margin-bottom:20px}
.progress-bar{height:8px;background:rgba(255,255,255,.08);border-radius:999px;overflow:hidden;margin-bottom:8px}
.progress-fill{height:100%;background:linear-gradient(90deg,#00A651,#007A33);border-radius:999px;transition:.5s}
.progress-row{display:flex;justify-content:space-between;font-size:12px}
.remaining-val{font-size:22px;font-weight:900;text-align:center;margin-bottom:4px;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.remaining-lbl{font-size:11px;color:rgba(255,255,255,.6);text-align:center;margin-bottom:12px}

/* Form */
.section-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.68);margin-bottom:8px}
.form-group{margin-bottom:14px}
label{display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.78);margin-bottom:6px}
input{width:100%;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.14);border-radius:10px;padding:13px 14px;color:#fff;font-size:16px;outline:none;transition:.2s;font-family:inherit}
input:focus{border-color:#00A651;background:rgba(0,166,81,.1)}
input::placeholder{color:rgba(255,255,255,.82)}
.hint{font-size:11px;color:rgba(255,255,255,.6);margin-top:5px}

.breakdown{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.09);border-radius:12px;padding:14px 16px;margin-bottom:14px;display:none}
.breakdown-row{display:flex;justify-content:space-between;align-items:center;font-size:13px;padding:4px 0}
.breakdown-row .lbl{color:rgba(255,255,255,.78)}
.breakdown-row .val{font-weight:600;color:rgba(255,255,255,.75)}
.breakdown-divider{border:none;border-top:1px solid rgba(255,255,255,.08);margin:8px 0}
.breakdown-total{display:flex;justify-content:space-between;align-items:center;padding-top:4px}
.breakdown-total .lbl{font-size:13px;font-weight:700;color:rgba(255,255,255,.8)}
.breakdown-total .val{font-size:18px;font-weight:900;color:#25D366}
.fee-tag{font-size:10px;color:rgba(255,255,255,.82);margin-top:6px;text-align:center}

.btn{width:100%;padding:15px;border-radius:12px;border:none;font-size:16px;font-weight:700;cursor:pointer;background:linear-gradient(135deg,#00A651,#007A33);color:#fff;transition:.2s;margin-top:4px}
.btn:hover{opacity:.9}
.btn:disabled{opacity:.45;cursor:not-allowed}

.err{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);border-radius:10px;padding:11px 14px;font-size:13px;color:#fca5a5;margin-bottom:14px;display:none}

/* After payment */
.waiting-box{text-align:center;padding:20px 0;display:none}
.waiting-icon{font-size:40px;margin-bottom:12px;animation:pulse 1.5s infinite}
@keyframes pulse{0%,100%{opacity:1}50%{opacity:.4}}
.success-box{text-align:center;padding:20px 0;display:none}
.fail-box{text-align:center;padding:20px 0;display:none}

/* Settled state */
.settled-box{text-align:center;padding:20px 0}

/* Tip section */
.tip-section{margin-top:20px;padding-top:20px;border-top:1px solid rgba(255,255,255,.07);display:none}
.tip-heading{font-size:15px;font-weight:800;margin-bottom:4px}
.tip-sub{font-size:12px;color:rgba(255,255,255,.68);margin-bottom:16px}
.tip-waiter{display:flex;align-items:center;gap:10px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);border-radius:12px;padding:12px 14px;margin-bottom:16px}
.tip-avatar{width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,#00A651,#007A33);display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0}
.tip-name{font-size:14px;font-weight:700}
.tip-role{font-size:11px;color:rgba(255,255,255,.68);margin-top:1px}
.tip-skip{background:none;border:none;color:rgba(255,255,255,.82);font-size:12px;cursor:pointer;margin-top:10px;text-decoration:underline;display:block;text-align:center}
.tip-done{text-align:center;padding:16px 0;display:none}

/* Opt-in */
.optin-section{margin-top:20px;padding-top:20px;border-top:1px solid rgba(255,255,255,.07);display:none}
.optin-badge{display:inline-flex;align-items:center;gap:6px;background:rgba(251,191,36,.1);border:1px solid rgba(251,191,36,.2);border-radius:20px;padding:4px 12px;font-size:11px;font-weight:700;color:#fbbf24;margin-bottom:10px}
.optin-heading{font-size:15px;font-weight:800;margin-bottom:4px}
.optin-sub{font-size:12px;color:rgba(255,255,255,.72);margin-bottom:14px;line-height:1.5}
.optin-skip{background:none;border:none;color:rgba(255,255,255,.82);font-size:12px;cursor:pointer;margin-top:10px;text-decoration:underline;display:block;text-align:center}
.optin-done{text-align:center;padding:14px 0;display:none}

.footer{margin-top:20px;font-size:11px;color:rgba(255,255,255,.2);text-align:center}
.pregota-link{color:rgba(255,255,255,.82);text-decoration:none;font-weight:700}
</style>
</head>
<body>

@php $settled = $bill->status === 'settled'; $open = $bill->isOpen(); @endphp

<div class="card">
    <div class="bill-label">{{ $bill->business_name }}</div>
    @if($bill->label)
    <div style="font-size:12px;color:rgba(255,255,255,.82);text-align:center;margin-bottom:4px">{{ $bill->label }}</div>
    @endif
    <div class="bill-total">Total bill: KES {{ number_format($bill->total_amount) }}</div>

    <!-- Progress bar -->
    <div class="progress-wrap">
        <div class="remaining-val" id="remainingVal">
            KES {{ number_format($bill->remainingAmount()) }}
        </div>
        <div class="remaining-lbl">remaining to pay</div>
        <div class="progress-bar">
            <div class="progress-fill" id="progressFill" style="width:{{ $bill->progressPct() }}%"></div>
        </div>
        <div class="progress-row">
            <span style="color:#4ade80;font-weight:700" id="paidAmt">KES {{ number_format($bill->paid_amount) }} paid</span>
            <span style="color:rgba(255,255,255,.6)">{{ $bill->progressPct() }}%</span>
        </div>
    </div>

    @if($settled)
    <div class="settled-box">
        <div style="font-size:48px;margin-bottom:10px">✅</div>
        <div style="font-size:18px;font-weight:900;color:#4ade80;margin-bottom:6px">Bill fully paid!</div>
        <div style="font-size:13px;color:rgba(255,255,255,.72)">Everyone has paid. You're all settled.</div>
    </div>

    @elseif(!$open)
    <div class="settled-box">
        <div style="font-size:48px;margin-bottom:10px">⏱️</div>
        <div style="font-size:16px;font-weight:700;margin-bottom:6px">This bill has expired</div>
        <div style="font-size:13px;color:rgba(255,255,255,.72)">Ask your waiter to generate a new split.</div>
    </div>

    @else
    <div id="formSection">
        <div class="err" id="errBox"></div>

        <div class="section-label">Pay your share</div>

        <div class="form-group">
            <label>Your Share (KES)</label>
            <input type="number" id="amount" placeholder="{{ number_format($bill->remainingAmount()) }}"
                   min="1" max="{{ $bill->remainingAmount() }}" required>
            <div class="hint">Enter what you owe. Max: KES {{ number_format($bill->remainingAmount()) }}</div>
        </div>

        <div class="breakdown" id="breakdown">
            <div class="breakdown-row">
                <span class="lbl">{{ $bill->business_name }}{{ $bill->label ? ' · ' . $bill->label : '' }}</span>
                <span class="val" id="bdShare">—</span>
            </div>
            <div class="breakdown-row">
                <span class="lbl">Pregota service fee</span>
                <span class="val">KES 30</span>
            </div>
            <hr class="breakdown-divider">
            <div class="breakdown-total">
                <span class="lbl">You pay on M-Pesa</span>
                <span class="val" id="bdTotal">—</span>
            </div>
            <div class="fee-tag">KES 30 is Pregota's fee — not charged by the restaurant</div>
        </div>

        <div class="form-group">
            <label>Your M-Pesa Number</label>
            <input type="tel" id="phone" placeholder="07XX XXX XXX" required>
        </div>

        <button type="button" class="btn" id="payBtn" onclick="pay()">Pay My Share →</button>
    </div>

    <!-- Waiting for M-Pesa PIN -->
    <div class="waiting-box" id="waitingBox">
        <div class="waiting-icon">📱</div>
        <div style="font-size:16px;font-weight:700;margin-bottom:6px">Check your phone</div>
        <div style="font-size:13px;color:rgba(255,255,255,.72)">Enter your M-Pesa PIN to complete payment.</div>
    </div>

    <!-- Payment success -->
    <div class="success-box" id="successBox">
        <div style="font-size:48px;margin-bottom:10px">🎉</div>
        <div style="font-size:18px;font-weight:900;margin-bottom:6px">Payment confirmed!</div>
        <div style="font-size:13px;color:rgba(255,255,255,.72)" id="successMsg"></div>
    </div>

    <!-- Payment failed -->
    <div class="fail-box" id="failBox">
        <div style="font-size:40px;margin-bottom:10px">❌</div>
        <div style="font-size:15px;font-weight:700;margin-bottom:10px">Payment cancelled</div>
        <button class="btn" onclick="resetForm()" style="font-size:14px;padding:12px">Try Again</button>
    </div>

    <!-- Restaurant opt-in — revealed after tip section (or after payment if no tip) -->
    <div class="optin-section" id="optinSection">
        <div class="optin-badge">📱 Stay connected</div>
        <div class="optin-heading">Hear from {{ $bill->business_name }}?</div>
        <div class="optin-sub">Pregota is asking on their behalf — would you like to give <strong style="color:rgba(255,255,255,.75)">{{ $bill->business_name }}</strong> your contact so they can send you offers and updates? Your number goes directly to them. Nothing else.</div>

        <div id="optinForm">
            <div class="form-group">
                <label>Your Phone Number</label>
                <input type="tel" id="optinPhone" placeholder="07XX XXX XXX">
                <div class="hint">Voluntarily shared with {{ $bill->business_name }} only. Pregota does not keep or sell it.</div>
            </div>
            <button type="button" class="btn" id="optinBtn" onclick="submitOptIn()" style="background:linear-gradient(135deg,#d97706,#f59e0b)">Yes, share my contact →</button>
            <button type="button" class="optin-skip" onclick="skipOptIn()">No thanks, skip</button>
        </div>

        <div class="optin-done" id="optinDone">
            <div style="font-size:32px;margin-bottom:8px">🙌</div>
            <div style="font-size:14px;font-weight:700;margin-bottom:4px">Contact shared!</div>
            <div style="font-size:12px;color:rgba(255,255,255,.68)">{{ $bill->business_name }} will be in touch. You can ask them to remove you at any time.</div>
        </div>
    </div>

    @if($staff)
    <!-- Tip section — revealed after successful payment -->
    <div class="tip-section" id="tipSection">
        <div class="tip-heading">Enjoyed the service?</div>
        <div class="tip-sub">Leave a tip for your waiter — any amount you feel is right.</div>

        <div class="tip-waiter">
            <div class="tip-avatar">{{ $staff->avatar_emoji ?? '😊' }}</div>
            <div>
                <div class="tip-name">{{ $staff->name }}</div>
                <div class="tip-role">{{ $staff->role }}</div>
            </div>
        </div>

        <div class="err" id="tipErrBox"></div>

        <div id="tipForm">
            <div class="form-group">
                <label>Tip Amount (KES)</label>
                <input type="number" id="tipAmount" placeholder="How much?" min="50" max="150000">
            </div>
            <div class="form-group">
                <label>Your M-Pesa Number</label>
                <input type="tel" id="tipPhone" placeholder="07XX XXX XXX">
            </div>
            <button type="button" class="btn" id="tipBtn" onclick="sendTip()">Send Tip →</button>
            <button type="button" class="tip-skip" onclick="skipTip()">No thanks</button>
        </div>

        <div class="waiting-box" id="tipWaiting">
            <div class="waiting-icon">📱</div>
            <div style="font-size:15px;font-weight:700;margin-bottom:6px">Check your phone</div>
            <div style="font-size:13px;color:rgba(255,255,255,.72)">Enter your M-Pesa PIN to send the tip.</div>
        </div>

        <div class="tip-done" id="tipDone">
            <div style="font-size:36px;margin-bottom:8px">🙏</div>
            <div style="font-size:15px;font-weight:700;margin-bottom:4px">Tip sent!</div>
            <div style="font-size:12px;color:rgba(255,255,255,.68)">{{ $staff->name }} will appreciate it.</div>
        </div>
    </div>
    @endif
    @endif
</div>

<div class="footer">Powered by <a href="{{ route('home') }}" class="pregota-link">Pregota</a></div>

@if(!$settled && $open)
<script>
const CSRF        = document.querySelector('meta[name=csrf-token]').content;
const PAY_URL     = '{{ route('bill-split.pay', $bill->split_token) }}';
const STATUS_URL  = '{{ route('bill-split.payment-status') }}';
const BILL_URL    = '{{ route('bill-split.bill-status', $bill->split_token) }}';
const OPTIN_URL   = '{{ route('bill-split.optin', $bill->split_token) }}';
const MAX_AMOUNT  = {{ $bill->remainingAmount() }};
const FMT         = n => 'KES ' + Number(n).toLocaleString('en-KE');

let paymentId = null;
let pollTimer  = null;
let billTimer  = null;

document.getElementById('amount').addEventListener('input', function() {
    const v = parseInt(this.value);
    const box = document.getElementById('breakdown');
    if (!v || v < 1) { box.style.display = 'none'; return; }
    const share = Math.min(v, MAX_AMOUNT);
    document.getElementById('bdShare').textContent = FMT(share);
    document.getElementById('bdTotal').textContent = FMT(share + 30);
    box.style.display = 'block';
});

function pay() {
    const amount = parseInt(document.getElementById('amount').value);
    const phone  = document.getElementById('phone').value.trim();
    const err    = document.getElementById('errBox');
    err.style.display = 'none';

    if (!amount || amount < 1) { showErr('Please enter an amount.'); return; }
    if (amount > MAX_AMOUNT)   { showErr('Amount exceeds the remaining balance.'); return; }
    if (!phone)                 { showErr('Please enter your M-Pesa number.'); return; }

    document.getElementById('payBtn').disabled = true;
    document.getElementById('formSection').style.display = 'none';
    document.getElementById('waitingBox').style.display  = 'block';

    fetch(PAY_URL, {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
        body: JSON.stringify({ amount, phone }),
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            paymentId = d.payment_id;
            pollTimer = setInterval(pollPayment, 3000);
        } else {
            showErr(d.message || 'Payment failed. Please try again.');
            resetForm();
        }
    })
    .catch(() => { showErr('Network error. Please try again.'); resetForm(); });
}

function pollPayment() {
    fetch(STATUS_URL + '?payment_id=' + paymentId)
        .then(r => r.json())
        .then(d => {
            if (d.status === 'paid') {
                clearInterval(pollTimer);
                document.getElementById('waitingBox').style.display  = 'none';
                document.getElementById('successBox').style.display  = 'block';
                const remaining = d.remaining;
                document.getElementById('successMsg').textContent =
                    remaining > 0
                        ? 'Your share is paid. ' + FMT(remaining) + ' still outstanding from others.'
                        : 'Your share is paid. Bill fully settled!';
                updateBillDisplay(d);
                if (d.settled) showSettled();
                startBillPoll();
                if (typeof revealTip === 'function') setTimeout(revealTip, 800);
                else setTimeout(revealOptIn, 800);
            } else if (d.status === 'failed') {
                clearInterval(pollTimer);
                document.getElementById('waitingBox').style.display = 'none';
                document.getElementById('failBox').style.display    = 'block';
            }
        })
        .catch(() => {});
}

function startBillPoll() {
    if (billTimer) return;
    billTimer = setInterval(() => {
        fetch(BILL_URL).then(r => r.json()).then(d => {
            updateBillDisplay(d);
            if (d.settled) { clearInterval(billTimer); showSettled(); }
        }).catch(() => {});
    }, 4000);
}

function updateBillDisplay(d) {
    document.getElementById('progressFill').style.width = d.progress + '%';
    document.getElementById('paidAmt').textContent      = FMT(d.paid_amount) + ' paid';
    document.getElementById('remainingVal').textContent = FMT(d.remaining);
}

function showSettled() {
    document.getElementById('successBox').innerHTML =
        '<div style="font-size:48px;margin-bottom:10px">✅</div>' +
        '<div style="font-size:18px;font-weight:900;color:#4ade80;margin-bottom:6px">Bill fully paid!</div>' +
        '<div style="font-size:13px;color:rgba(255,255,255,.72)">Everyone has paid. You\'re all settled.</div>';
}

@if($staff)
const TIP_URL        = '{{ route('tip.initiate', $staff->handle) }}';
const TIP_STATUS_URL = '{{ route('tip.status') }}';
let tipId   = null;
let tipTimer = null;

function revealTip() {
    const section = document.getElementById('tipSection');
    if (!section) return;
    section.style.display = 'block';
    // Pre-fill phone from the bill payment
    const phone = document.getElementById('phone');
    if (phone) document.getElementById('tipPhone').value = phone.value;
}

function skipTip() {
    document.getElementById('tipSection').style.display = 'none';
    setTimeout(revealOptIn, 400);
}

function sendTip() {
    const amount = parseInt(document.getElementById('tipAmount').value);
    const phone  = document.getElementById('tipPhone').value.trim();
    const err    = document.getElementById('tipErrBox');
    err.style.display = 'none';

    if (!amount || amount < 50) { err.textContent = 'Minimum tip is KES 50.'; err.style.display = 'block'; return; }
    if (!phone)                  { err.textContent = 'Please enter your M-Pesa number.'; err.style.display = 'block'; return; }

    document.getElementById('tipBtn').disabled = true;
    document.getElementById('tipForm').style.display    = 'none';
    document.getElementById('tipWaiting').style.display = 'block';

    fetch(TIP_URL, {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
        body: JSON.stringify({ amount, phone }),
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            tipId    = d.tip_id;
            tipTimer = setInterval(pollTip, 3000);
        } else {
            showTipErr(d.message || 'Could not send tip. Please try again.');
        }
    })
    .catch(() => showTipErr('Network error. Please try again.'));
}

function pollTip() {
    fetch(TIP_STATUS_URL + '?tip_id=' + tipId)
        .then(r => r.json())
        .then(d => {
            if (d.status === 'paid') {
                clearInterval(tipTimer);
                document.getElementById('tipWaiting').style.display = 'none';
                document.getElementById('tipDone').style.display    = 'block';
                setTimeout(revealOptIn, 1200);
            } else if (d.status === 'failed') {
                clearInterval(tipTimer);
                showTipErr('Tip payment cancelled. You can try again.');
            }
        })
        .catch(() => {});
}

function showTipErr(msg) {
    const e = document.getElementById('tipErrBox');
    e.textContent = msg;
    e.style.display = 'block';
    document.getElementById('tipForm').style.display    = 'block';
    document.getElementById('tipWaiting').style.display = 'none';
    document.getElementById('tipBtn').disabled = false;
}
@endif

function revealOptIn() {
    const section = document.getElementById('optinSection');
    if (!section) return;
    section.style.display = 'block';
    const payPhone = document.getElementById('phone');
    if (payPhone) document.getElementById('optinPhone').value = payPhone.value;
    section.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function submitOptIn() {
    const phone = document.getElementById('optinPhone').value.trim();
    if (!phone) return;
    document.getElementById('optinBtn').disabled = true;
    fetch(OPTIN_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ phone }),
    })
    .then(r => r.json())
    .then(() => {
        document.getElementById('optinForm').style.display = 'none';
        document.getElementById('optinDone').style.display = 'block';
    })
    .catch(() => { document.getElementById('optinBtn').disabled = false; });
}

function skipOptIn() {
    document.getElementById('optinSection').style.display = 'none';
}

function resetForm() {
    document.getElementById('formSection').style.display = 'block';
    document.getElementById('waitingBox').style.display  = 'none';
    document.getElementById('failBox').style.display     = 'none';
    document.getElementById('payBtn').disabled = false;
}

function showErr(msg) {
    const e = document.getElementById('errBox');
    e.textContent = msg;
    e.style.display = 'block';
    document.getElementById('formSection').style.display = 'block';
    document.getElementById('waitingBox').style.display  = 'none';
    document.getElementById('payBtn').disabled = false;
}
</script>
@endif
</body>
</html>
