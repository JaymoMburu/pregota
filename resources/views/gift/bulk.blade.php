﻿﻿<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Bulk Gift Codes — Pregota for Business</title>
<meta name="description" content="Buy M-Pesa gift codes in bulk. One payment, multiple codes — each redeemable by any Kenyan phone.">
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}input,textarea,select,button{font-family:inherit;font-size:inherit}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh}
.nav{padding:14px 24px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.07);background:#0B141A;position:sticky;top:0;z-index:10}
.logo{font-size:20px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.nav-back{color:rgba(255,255,255,.6);font-size:13px;text-decoration:none}
.nav-back:hover{color:#fff}

.wrap{max-width:760px;margin:0 auto;padding:40px 20px 80px}

.badge-corp{display:inline-flex;align-items:center;gap:7px;background:rgba(251,191,36,.1);border:1px solid rgba(251,191,36,.25);border-radius:20px;padding:5px 14px;font-size:12px;font-weight:700;color:#fbbf24;margin-bottom:20px;letter-spacing:.04em}
h1{font-size:clamp(28px,5vw,44px);font-weight:900;line-height:1.1;margin-bottom:12px}
h1 em{font-style:normal;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.sub{font-size:15px;color:rgba(255,255,255,.72);line-height:1.6;margin-bottom:36px}

/* How it works */
.how{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:40px}
@media(max-width:600px){.how{grid-template-columns:1fr}}
.how-step{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:18px}
.how-num{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:rgba(255,255,255,.65);margin-bottom:8px}
.how-text{font-size:13px;color:rgba(255,255,255,.78);line-height:1.5}

/* Form */
.card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.09);border-radius:20px;padding:28px}
.card-title{font-size:16px;font-weight:800;margin-bottom:22px}
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}
@media(max-width:540px){.form-grid{grid-template-columns:1fr}}
.form-group{display:flex;flex-direction:column;gap:6px}
.form-group.full{grid-column:1/-1}
label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.78)}
input{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.14);border-radius:10px;padding:12px 14px;color:#fff;font-size:16px;outline:none;transition:.2s;width:100%}
input:focus{border-color:#25D366;background:rgba(37,211,102,.08)}
input::placeholder{color:rgba(255,255,255,.25)}

/* Preview box */
.preview{background:rgba(37,211,102,.06);border:1px solid rgba(37,211,102,.2);border-radius:14px;padding:18px;margin-top:20px}
.preview-title{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#4ADE80;margin-bottom:14px}
.preview-row{display:flex;justify-content:space-between;align-items:center;font-size:13px;color:rgba(255,255,255,.78);padding:5px 0}
.preview-row.total{border-top:1px solid rgba(255,255,255,.08);padding-top:12px;margin-top:8px;font-size:15px;font-weight:800;color:#fff}
.preview-row.total .val{color:#4ADE80}
.max-codes{font-size:11px;color:rgba(255,255,255,.78);margin-top:10px}
.max-codes strong{color:#fbbf24}
.warn{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);border-radius:8px;padding:8px 12px;font-size:12px;color:#fca5a5;margin-top:10px;display:none}

.btn{width:100%;padding:15px;border-radius:12px;border:none;font-size:16px;font-weight:700;cursor:pointer;background:linear-gradient(135deg,#00A651,#007A33);color:#fff;margin-top:20px;transition:.15s}
.btn:hover:not(:disabled){opacity:.9}
.btn:disabled{opacity:.5;cursor:not-allowed}

/* Screens */
.screen{display:none}
.screen.active{display:block}

/* Waiting */
.waiting-box{text-align:center;padding:48px 24px}
.waiting-icon{font-size:52px;margin-bottom:20px;animation:pulse 1.8s ease-in-out infinite}
@keyframes pulse{0%,100%{opacity:1}50%{opacity:.4}}
.waiting-ref{font-family:monospace;font-size:17px;font-weight:700;color:#4ADE80;margin-top:8px}

/* Success */
.success-header{text-align:center;padding:24px 0 28px}
.success-icon{font-size:52px;margin-bottom:14px}
.success-title{font-size:22px;font-weight:900;margin-bottom:6px}
.success-sub{font-size:14px;color:rgba(255,255,255,.72)}

.codes-wrap{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.08);border-radius:16px;overflow:hidden;margin-top:20px}
.codes-header{padding:14px 18px;border-bottom:1px solid rgba(255,255,255,.07);display:flex;justify-content:space-between;align-items:center}
.codes-header h3{font-size:14px;font-weight:700}
.download-btn{display:inline-flex;align-items:center;gap:6px;padding:8px 14px;border-radius:8px;background:rgba(37,211,102,.12);border:1px solid rgba(37,211,102,.25);color:#4ADE80;font-size:13px;font-weight:700;text-decoration:none;transition:.15s}
.download-btn:hover{background:rgba(37,211,102,.2)}
.codes-list{max-height:420px;overflow-y:auto}
.code-row{display:flex;align-items:center;gap:14px;padding:11px 18px;border-top:1px solid rgba(255,255,255,.05);font-size:13px}
.code-row:first-child{border-top:none}
.code-num{color:rgba(255,255,255,.65);font-size:11px;width:22px;flex-shrink:0;text-align:right}
.code-val{font-family:monospace;font-weight:700;font-size:14px;color:#a78bfa;flex:1;letter-spacing:.06em}
.code-amount{color:rgba(255,255,255,.68);font-size:12px;white-space:nowrap}
.code-copy{background:none;border:1px solid rgba(255,255,255,.1);border-radius:6px;color:rgba(255,255,255,.78);font-size:11px;padding:3px 8px;cursor:pointer;transition:.15s}
.code-copy:hover{color:#fff;border-color:rgba(255,255,255,.65)}
.code-copy.copied{border-color:rgba(37,211,102,.4);color:#4ADE80}

.err-msg{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);border-radius:10px;padding:12px 16px;font-size:13px;color:#fca5a5;margin-top:16px;display:none}

.redeem-note{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:12px;padding:16px;margin-top:16px;font-size:13px;color:rgba(255,255,255,.72);line-height:1.6}
.redeem-note a{color:#4ADE80}
</style>
</head>
<body>

<nav class="nav">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    <a href="{{ route('gift.home') }}" class="nav-back">← Gift Vouchers</a>
</nav>

<div class="wrap">

    <!-- FORM SCREEN -->
    <div class="screen active" id="screen-form">
        <div class="badge-corp">🏢 For Businesses &amp; Organisations</div>
        <h1>Bulk Gift Codes<br><em>One payment. Many gifts.</em></h1>
        <p class="sub">Buy gift codes in bulk — pay once via M-Pesa, get a set of unique codes. Share them with staff, clients, or partners. Each code is redeemed independently to any Kenyan number.</p>

        <div class="how">
            <div class="how-step">
                <div class="how-num">Step 1</div>
                <div class="how-text">Enter the number of codes and how much each recipient should get.</div>
            </div>
            <div class="how-step">
                <div class="how-num">Step 2</div>
                <div class="how-text">Get an M-Pesa STK Push for the total. Approve it on your phone.</div>
            </div>
            <div class="how-step">
                <div class="how-num">Step 3</div>
                <div class="how-text">Download your codes instantly. Share them however you like.</div>
            </div>
        </div>

        <div class="card">
            <div class="card-title">Configure your order</div>
            <form id="bulk-form">
                @csrf
                <div class="form-grid">
                    <div class="form-group">
                        <label>Company / Organisation Name</label>
                        <input type="text" id="company_name" name="company_name" placeholder="Acme Kenya Ltd" required>
                    </div>
                    <div class="form-group">
                        <label>Contact Name</label>
                        <input type="text" id="contact_name" name="contact_name" placeholder="Jane Mwangi" required>
                    </div>
                    <div class="form-group">
                        <label>M-Pesa Number (for STK Push)</label>
                        <input type="tel" id="phone" name="phone" placeholder="07XX XXX XXX" required>
                    </div>
                    <div class="form-group"></div>
                    <div class="form-group">
                        <label>Amount per Code (KES)</label>
                        <input type="number" id="amount_per_code" name="amount_per_code"
                            placeholder="{{ $minAmount }}" min="{{ $minAmount }}" step="50"
                            value="{{ $minAmount }}" required>
                    </div>
                    <div class="form-group">
                        <label>Number of Codes</label>
                        <input type="number" id="code_count" name="code_count"
                            placeholder="10" min="1" max="500" value="10" required>
                    </div>
                </div>

                <!-- Live preview -->
                <div class="preview" id="fee-preview">
                    <div class="preview-title">Order Summary</div>
                    <div class="preview-row">
                        <span>Codes</span><span class="val" id="p-count">10</span>
                    </div>
                    <div class="preview-row">
                        <span>Each recipient gets</span><span class="val" id="p-per">KES —</span>
                    </div>
                    <div class="preview-row">
                        <span>Pregota fee</span><span class="val" id="p-fee">KES —</span>
                    </div>
                    <div class="preview-row total">
                        <span>You pay via M-Pesa</span><span class="val" id="p-total">KES —</span>
                    </div>
                    <div class="max-codes" id="max-note"></div>
                    <div class="warn" id="limit-warn">⚠️ Total exceeds the M-Pesa limit of KES {{ number_format($maxAmount) }}. Reduce codes or amount per code.</div>
                </div>

                <div class="err-msg" id="form-err"></div>

                <button type="submit" class="btn" id="submit-btn" disabled>Pay &amp; Generate Codes →</button>
            </form>
        </div>
    </div>

    <!-- WAITING SCREEN -->
    <div class="screen" id="screen-waiting">
        <div class="waiting-box">
            <div class="waiting-icon">📲</div>
            <div style="font-size:18px;font-weight:700;margin-bottom:8px">Check your phone</div>
            <div style="font-size:14px;color:rgba(255,255,255,.72);line-height:1.6;max-width:340px;margin:0 auto">An M-Pesa STK Push has been sent to your phone. Enter your M-Pesa PIN to complete the payment.</div>
            <div class="waiting-ref" id="waiting-ref"></div>
            <div style="font-size:12px;color:rgba(255,255,255,.72);margin-top:24px">Generating your codes after payment…</div>
        </div>
    </div>

    <!-- SUCCESS SCREEN -->
    <div class="screen" id="screen-success">
        <div class="success-header">
            <div class="success-icon">🎉</div>
            <div class="success-title">Codes Ready!</div>
            <div class="success-sub" id="success-sub">Your gift codes have been generated.</div>
        </div>

        <div class="codes-wrap">
            <div class="codes-header">
                <h3 id="codes-header-text">Gift Codes</h3>
                <a href="#" class="download-btn" id="download-link">⬇ Download CSV</a>
            </div>
            <div class="codes-list" id="codes-list"></div>
        </div>

        <div class="redeem-note">
            <strong>How recipients redeem:</strong> Go to <a href="{{ route('redeem') }}" target="_blank">pregota.com/redeem</a> and enter their code. Money arrives on M-Pesa within seconds.
        </div>
    </div>

    <!-- FAILED SCREEN -->
    <div class="screen" id="screen-failed">
        <div class="waiting-box">
            <div style="font-size:52px;margin-bottom:20px">❌</div>
            <div style="font-size:18px;font-weight:700;margin-bottom:8px">Payment Not Received</div>
            <div style="font-size:14px;color:rgba(255,255,255,.72);line-height:1.6;max-width:340px;margin:0 auto">The M-Pesa payment was not completed. No codes were generated and nothing was charged.</div>
            <button class="btn" style="max-width:260px;margin:28px auto 0;display:block" onclick="resetToForm()">Try Again</button>
        </div>
    </div>

</div>

<script>
const FEE_IN_PCT  = {{ $feeInPct }};
const FEE_OUT_PCT = {{ $feeOutPct }};
const MAX_AMOUNT  = {{ $maxAmount }};
const MIN_AMOUNT  = {{ $minAmount }};
const FEE_MIN     = 50;

let currentRef = null;
let pollTimer   = null;

// ── Fee calculation (mirrors PHP VoucherService::calculateFees) ───────────
function calcFees(payout, count) {
    const feeOut   = Math.max(FEE_MIN / 2, payout * FEE_OUT_PCT / (100 - FEE_OUT_PCT));
    const faceVal  = payout + feeOut;
    const feeIn    = Math.max(FEE_MIN, faceVal * FEE_IN_PCT / (100 - FEE_IN_PCT));
    const gross    = Math.ceil(faceVal + feeIn);
    return {
        gross,
        feeIn:  Math.round(feeIn * 100) / 100,
        feeOut: Math.round(feeOut * 100) / 100,
        grossTotal: gross * count,
        feeTotal: (Math.round(feeIn * 100) / 100 + Math.round(feeOut * 100) / 100) * count,
    };
}

function updatePreview() {
    const amount = parseInt(document.getElementById('amount_per_code').value) || 0;
    const count  = parseInt(document.getElementById('code_count').value)      || 0;

    const pCount  = document.getElementById('p-count');
    const pPer    = document.getElementById('p-per');
    const pFee    = document.getElementById('p-fee');
    const pTotal  = document.getElementById('p-total');
    const maxNote = document.getElementById('max-note');
    const limitW  = document.getElementById('limit-warn');
    const btn     = document.getElementById('submit-btn');

    pCount.textContent = count || '—';

    if (!amount || !count || amount < MIN_AMOUNT) {
        pPer.textContent = 'KES —';
        pFee.textContent = 'KES —';
        pTotal.textContent = 'KES —';
        maxNote.textContent = '';
        limitW.style.display = 'none';
        btn.disabled = true;
        return;
    }

    const fees = calcFees(amount, count);
    const feePerCode = Math.round((fees.feeIn + fees.feeOut) * 100) / 100;
    const maxCodes = Math.floor(MAX_AMOUNT / fees.gross);

    pPer.textContent   = 'KES ' + amount.toLocaleString();
    pFee.textContent   = 'KES ' + (feePerCode * count).toLocaleString(undefined, {minimumFractionDigits:0, maximumFractionDigits:0});
    pTotal.textContent = 'KES ' + fees.grossTotal.toLocaleString();

    const overLimit = fees.grossTotal > MAX_AMOUNT;
    limitW.style.display = overLimit ? 'block' : 'none';

    const formFilled = document.getElementById('company_name').value.trim() &&
                       document.getElementById('contact_name').value.trim() &&
                       document.getElementById('phone').value.trim();

    btn.disabled = overLimit || !formFilled;

    maxNote.innerHTML = 'Max codes at KES&nbsp;' + amount.toLocaleString() + ' each: <strong>' + maxCodes.toLocaleString() + '</strong>';
}

// ── Form inputs ───────────────────────────────────────────────────────────
['amount_per_code', 'code_count', 'company_name', 'contact_name', 'phone'].forEach(id => {
    document.getElementById(id).addEventListener('input', updatePreview);
});
updatePreview();

// ── Submit ────────────────────────────────────────────────────────────────
document.getElementById('bulk-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('submit-btn');
    const err = document.getElementById('form-err');
    btn.disabled = true;
    btn.textContent = 'Sending STK Push…';
    err.style.display = 'none';

    const body = new FormData(this);

    try {
        const res  = await fetch('{{ route('gift.bulk.initiate') }}', { method: 'POST', body });
        const data = await res.json();

        if (data.success) {
            currentRef = data.reference;
            showScreen('screen-waiting');
            document.getElementById('waiting-ref').textContent = data.reference;
            startPolling();
        } else {
            err.textContent  = data.message || 'Something went wrong.';
            err.style.display = 'block';
            btn.disabled = false;
            btn.textContent = 'Pay & Generate Codes →';
        }
    } catch (ex) {
        err.textContent  = 'Network error. Please try again.';
        err.style.display = 'block';
        btn.disabled = false;
        btn.textContent = 'Pay & Generate Codes →';
    }
});

// ── Polling ───────────────────────────────────────────────────────────────
function startPolling() {
    pollTimer = setInterval(poll, 3000);
}

async function poll() {
    if (!currentRef) return;
    try {
        const res  = await fetch('{{ route('gift.bulk.status') }}?ref=' + encodeURIComponent(currentRef));
        const data = await res.json();

        if (data.status === 'active') {
            clearInterval(pollTimer);
            showSuccess(data);
        } else if (data.status === 'failed') {
            clearInterval(pollTimer);
            showScreen('screen-failed');
        }
    } catch (_) {}
}

// ── Success render ────────────────────────────────────────────────────────
function showSuccess(data) {
    showScreen('screen-success');
    const codes = data.codes || [];
    document.getElementById('success-sub').textContent =
        codes.length + ' codes ready · Reference: ' + data.reference;
    document.getElementById('codes-header-text').textContent =
        codes.length + ' Gift Codes';
    document.getElementById('download-link').href =
        '{{ route('gift.bulk.download') }}?ref=' + encodeURIComponent(data.reference);

    const list = document.getElementById('codes-list');
    list.innerHTML = codes.map((c, i) =>
        `<div class="code-row">
            <span class="code-num">${i + 1}</span>
            <span class="code-val">${c.code}</span>
            <span class="code-amount">KES ${c.value.toLocaleString()} · ${c.expires}</span>
            <button class="code-copy" onclick="copyCode(this,'${c.code}')">Copy</button>
        </div>`
    ).join('');
}

// ── Helpers ───────────────────────────────────────────────────────────────
function showScreen(id) {
    document.querySelectorAll('.screen').forEach(s => s.classList.remove('active'));
    document.getElementById(id).classList.add('active');
}

function resetToForm() {
    clearInterval(pollTimer);
    currentRef = null;
    showScreen('screen-form');
    const btn = document.getElementById('submit-btn');
    btn.disabled = false;
    btn.textContent = 'Pay & Generate Codes →';
}

function copyCode(btn, code) {
    navigator.clipboard.writeText(code).then(() => {
        btn.textContent = 'Copied!';
        btn.classList.add('copied');
        setTimeout(() => { btn.textContent = 'Copy'; btn.classList.remove('copied'); }, 1800);
    });
}
</script>

</body>
</html>
