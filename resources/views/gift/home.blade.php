<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pregota — Send an Anonymous Gift</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}
html,body{height:100%}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;display:flex;min-height:100vh}

/* ── Left panel ── */
.panel-left{
    width:52%;height:100vh;position:sticky;top:0;
    background:radial-gradient(circle 300px at -50px -100px,rgba(0,166,81,.35),transparent 70%),radial-gradient(circle 230px at calc(100% + 30px) 100%,rgba(0,122,51,.28),transparent 70%),linear-gradient(150deg,#030D07 0%,#0A1A0F 55%,#0F2418 100%);
    display:flex;flex-direction:column;
    padding:40px 48px;overflow:hidden;
}

.left-logo{font-size:24px;font-weight:900;position:relative;z-index:1;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent}

.left-center{flex:1;display:flex;flex-direction:column;justify-content:center;position:relative;z-index:1;gap:48px}

.headline h1{font-size:clamp(34px,3.8vw,54px);font-weight:900;line-height:1.1;letter-spacing:-.5px}
.headline h1 em{font-style:normal;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.headline p{margin-top:14px;font-size:15px;color:rgba(255,255,255,.72);line-height:1.6;max-width:320px}

/* How it works steps */
.steps-list{display:flex;flex-direction:column;gap:24px}
.step-item{display:flex;align-items:flex-start;gap:16px}
.step-num{
    width:32px;height:32px;border-radius:50%;flex-shrink:0;
    background:linear-gradient(135deg,#00A651,#007A33);
    display:flex;align-items:center;justify-content:center;
    font-size:13px;font-weight:900;margin-top:1px;
}
.step-text h3{font-size:14px;font-weight:700;margin-bottom:3px;color:rgba(255,255,255,.9)}
.step-text p{font-size:13px;color:rgba(255,255,255,.78);line-height:1.55}

.left-foot{margin-top:auto;position:relative;z-index:1;font-size:11px;color:rgba(255,255,255,.6)}

/* ── Right panel ── */
.panel-right{
    width:48%;min-height:100vh;background:#0B141A;
    display:flex;flex-direction:column;
    border-left:1px solid rgba(255,255,255,.06);
}
.right-nav{padding:16px 32px;display:flex;justify-content:flex-end;gap:10px;border-bottom:1px solid rgba(255,255,255,.06)}
.nav-link{color:rgba(255,255,255,.72);text-decoration:none;font-size:13px;font-weight:600;padding:7px 14px;border:1px solid rgba(255,255,255,.12);border-radius:8px;transition:.15s}
.nav-link:hover{background:rgba(255,255,255,.06);color:#fff}

.right-body{flex:1;display:flex;align-items:center;justify-content:center;padding:24px 32px}
.form-wrap{width:100%;max-width:440px}
.form-title{font-size:22px;font-weight:900;margin-bottom:22px}

.form-group{margin-bottom:14px}
label{display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.6);margin-bottom:6px}
input,textarea{width:100%;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.18);border-radius:10px;padding:13px 16px;color:#fff;font-size:16px;outline:none;transition:.2s;font-family:inherit}
input:focus,textarea:focus{border-color:#00A651;background:rgba(0,166,81,.1)}
input::placeholder,textarea::placeholder{color:rgba(255,255,255,.65)}
textarea{resize:none;height:68px}

.fee-preview{background:rgba(0,166,81,.07);border:1px solid rgba(0,166,81,.2);border-radius:10px;padding:11px 14px;margin-bottom:14px;font-size:12px;display:none}
.fee-row{display:flex;justify-content:space-between;padding:2px 0;color:rgba(255,255,255,.65)}
.fee-row.total{color:#fff;font-weight:700;border-top:1px solid rgba(255,255,255,.08);margin-top:5px;padding-top:7px}

.hint{font-size:11px;color:rgba(255,255,255,.68);margin-top:5px}

.btn-primary{width:100%;padding:15px;border-radius:12px;border:none;font-size:16px;font-weight:700;cursor:pointer;background:linear-gradient(135deg,#00A651,#007A33);color:#fff;margin-top:6px;transition:.2s}
.btn-primary:hover{opacity:.9;transform:translateY(-1px)}
.btn-primary:disabled{opacity:.45;cursor:not-allowed;transform:none}

.right-foot{padding:12px 32px;text-align:center;color:rgba(255,255,255,.6);font-size:11px;border-top:1px solid rgba(255,255,255,.06)}

/* Creator search */
.search-wrap{margin-bottom:20px;position:relative}
.search-wrap input{padding-left:38px;background:rgba(255,255,255,.06)}
.search-icon{position:absolute;left:13px;top:50%;transform:translateY(-50%);font-size:15px;pointer-events:none;opacity:.5}
.search-results{position:absolute;top:calc(100% + 6px);left:0;right:0;background:#161f27;border:1px solid rgba(255,255,255,.12);border-radius:14px;z-index:50;overflow:hidden;box-shadow:0 16px 48px rgba(0,0,0,.6)}
.search-empty{padding:18px;text-align:center;font-size:13px;color:rgba(255,255,255,.5)}
.creator-result{display:flex;align-items:center;gap:12px;padding:12px 16px;cursor:pointer;text-decoration:none;transition:.15s;border-bottom:1px solid rgba(255,255,255,.05)}
.creator-result:last-child{border-bottom:none}
.creator-result:hover{background:rgba(255,255,255,.04)}
.cr-avatar{width:42px;height:42px;border-radius:50%;background:linear-gradient(135deg,#00A651,#007A33);display:flex;align-items:center;justify-content:center;font-size:17px;font-weight:900;flex-shrink:0;overflow:hidden}
.cr-avatar img{width:100%;height:100%;object-fit:cover}
.cr-info{flex:1;min-width:0}
.cr-name{font-size:14px;font-weight:700;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.cr-handle{font-size:12px;color:rgba(255,255,255,.5);margin-top:1px}
.cr-min{font-size:11px;color:#4ade80;margin-top:3px}

/* Tabs */
.tabs{display:flex;gap:0;margin-bottom:22px;border:1px solid rgba(255,255,255,.1);border-radius:10px;overflow:hidden}
.tab-btn{flex:1;padding:10px 6px;border:none;background:rgba(255,255,255,.04);color:rgba(255,255,255,.72);font-size:13px;font-weight:600;cursor:pointer;transition:.15s;font-family:inherit}
.tab-btn.active{background:linear-gradient(135deg,rgba(0,166,81,.3),rgba(0,122,51,.2));color:#fff}
.tab-pane{display:none}
.tab-pane.active{display:block}

/* Direct gift inline success */
.direct-success{text-align:center;padding:20px 0;display:none}
.direct-pending{text-align:center;margin-top:14px;font-size:13px;color:rgba(255,255,255,.78);display:none}

/* ── Modal ── */
.modal{position:fixed;inset:0;background:rgba(0,0,0,.85);display:none;align-items:center;justify-content:center;z-index:200;padding:24px}
.modal.show{display:flex}
.modal-box{background:#13131f;border:1px solid rgba(255,255,255,.1);border-radius:22px;padding:36px 32px;max-width:380px;width:100%;text-align:center}
.code-display{font-size:28px;font-weight:900;letter-spacing:.15em;background:linear-gradient(135deg,#00A651,#007A33);-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin:20px 0;font-family:monospace}
.copy-btn{background:rgba(0,166,81,.2);border:1px solid rgba(0,166,81,.4);color:#a78bfa;border-radius:8px;padding:8px 22px;cursor:pointer;font-size:13px;font-weight:600}
.copy-btn:hover{background:rgba(0,166,81,.3)}
.status-dot{width:9px;height:9px;border-radius:50%;background:#f59e0b;display:inline-block;animation:pulse 1.5s infinite;margin-right:6px;vertical-align:middle}
@keyframes pulse{0%,100%{opacity:1}50%{opacity:.3}}

/* Mobile logo — hidden on desktop, shown when left panel is gone */
.m-logo{display:none}

@media(max-width:800px){
    body{flex-direction:column}
    .panel-left{display:none}
    .panel-right{width:100%;border-left:none;min-height:100vh}
    .m-logo{
        display:block;font-size:22px;font-weight:900;
        background:linear-gradient(135deg,#25D366,#4ADE80);
        -webkit-background-clip:text;-webkit-text-fill-color:transparent;
        text-decoration:none;padding:14px 18px 4px
    }
    .right-nav{
        padding:8px 14px;gap:6px;
        overflow-x:auto;scrollbar-width:none;-webkit-overflow-scrolling:touch;
        justify-content:flex-start;border-bottom:1px solid rgba(255,255,255,.06)
    }
    .right-nav::-webkit-scrollbar{display:none}
    .right-nav .nav-link{white-space:nowrap;font-size:11px;padding:5px 10px}
    .right-body{padding:16px;align-items:flex-start;padding-top:20px}
    .form-wrap{max-width:100%}
    .form-title{font-size:20px;margin-bottom:14px}
    .tabs{margin-bottom:16px}
    .btn-primary{padding:16px;font-size:17px}
    .right-foot{padding:10px 16px;font-size:10px}
}
</style>
</head>
<body>

<!-- LEFT -->
<div class="panel-left">

    <div class="left-logo">Pregota</div>

    <div class="left-center">
        <div class="headline">
            <h1>Send a gift.<br><em>Anonymously.</em></h1>
            <p>They receive the money. You stay invisible.</p>
        </div>

        <div class="steps-list">
            <div class="step-item">
                <div class="step-num">1</div>
                <div class="step-text">
                    <h3>Enter the gift amount</h3>
                    <p>That's exactly what your recipient receives. Fees are added on top.</p>
                </div>
            </div>
            <div class="step-item">
                <div class="step-num">2</div>
                <div class="step-text">
                    <h3>Pay via M-Pesa STK Push</h3>
                    <p>Confirm the prompt on your phone. Your statement shows "Pregota Ltd".</p>
                </div>
            </div>
            <div class="step-item">
                <div class="step-num">3</div>
                <div class="step-text">
                    <h3>Share the gift code</h3>
                    <p>A unique code like PRG-7492-X8Q1 is generated. Send it by WhatsApp or SMS.</p>
                </div>
            </div>
            <div class="step-item">
                <div class="step-num">4</div>
                <div class="step-text">
                    <h3>Recipient claims the money</h3>
                    <p>They enter the code and their M-Pesa number. Cash arrives — no names, no trail.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="left-foot">© 2026 Pregota · Phone numbers never stored · Secured by M-Pesa STK Push</div>
</div>

<!-- RIGHT -->
<div class="panel-right">
    <a href="{{ route('home') }}" class="m-logo">Pregota</a>
    @include('partials.module-nav', ['activeModule' => 'gift'])
    <div class="right-nav" style="padding:10px 24px;gap:8px">
        <a href="{{ route('gift.multi') }}" class="nav-link" style="font-size:12px;padding:5px 12px">🎤 Multi-Creator</a>
        <a href="{{ route('gift.bulk') }}" class="nav-link" style="font-size:12px;padding:5px 12px">🏢 Bulk Codes</a>
        <a href="{{ route('track') }}" class="nav-link" style="font-size:12px;padding:5px 12px">Track Gift</a>
        <a href="{{ route('redeem') }}" class="nav-link" style="font-size:12px;padding:5px 12px">Redeem Gift</a>
    </div>

    <div class="right-body">
        <div class="form-wrap">

            <!-- Creator search -->
            <div class="search-wrap">
                <span class="search-icon">🔍</span>
                <input type="text" id="creatorSearch" placeholder="Search a creator to gift…" autocomplete="off">
                <div class="search-results" id="searchResults" style="display:none"></div>
            </div>

            <!-- Tab switcher -->
            <div class="tabs">
                <button class="tab-btn active" id="tabVoucherBtn" onclick="switchTab('voucher')">🎁 Gift Voucher</button>
                <button class="tab-btn" id="tabDirectBtn" onclick="switchTab('direct')">⚡ Direct Gift</button>
            </div>

            <!-- TAB: Gift Voucher (existing flow) -->
            <div class="tab-pane active" id="tabVoucher">
                <div class="form-title" style="font-size:18px;margin-bottom:16px">Send a Gift Voucher</div>

                <form id="giftForm">
                    <div class="form-group">
                        <label>Gift Amount (KES)</label>
                        <input type="number" id="amount" name="amount" placeholder="What the recipient receives"
                            min="{{ config('pregota.min_amount') }}" max="{{ config('pregota.max_amount') }}" required>
                    </div>

                    <div class="fee-preview" id="feePreview">
                        <div class="fee-row"><span>Recipient gets</span><span id="fRecipient">—</span></div>
                        <div class="fee-row"><span id="fFeeOutLabel">Payout fee</span><span id="fFeeOut">—</span></div>
                        <div class="fee-row"><span id="fFeeInLabel">Deposit fee</span><span id="fFeeIn">—</span></div>
                        <div class="fee-row total"><span>You pay (M-Pesa)</span><span id="fGross">—</span></div>
                    </div>

                    <div class="form-group">
                        <label>Your M-Pesa Number</label>
                        <input type="tel" id="phone" name="phone" placeholder="07XX XXX XXX" required>
                    </div>

                    <div class="form-group">
                        <label>Message (optional)</label>
                        <textarea id="message" name="message" placeholder="Happy birthday! Thinking of you..."></textarea>
                    </div>

                    <div class="form-group">
                        <label>Your Name (optional)</label>
                        <input type="text" id="sender_name" name="sender_name" placeholder="Leave blank to stay anonymous" maxlength="60">
                        <div class="hint">Recipient sees "From: [name]" only if you fill this in.</div>
                    </div>

                    <button type="submit" class="btn-primary" id="submitBtn">Send Gift Voucher →</button>
                </form>
            </div>

            <!-- TAB: Direct Gift -->
            <div class="tab-pane" id="tabDirect">
                <div class="form-title" style="font-size:18px;margin-bottom:6px">Direct Gift</div>
                <div style="font-size:12px;color:rgba(255,255,255,.68);margin-bottom:16px;line-height:1.6">Money goes straight to the recipient's M-Pesa — no code, no claiming. KES {{ config('pregota.gift_direct_fee') }} flat fee.</div>

                <!-- Direct gift form -->
                <div id="directForm">
                    <div class="form-group">
                        <label>Gift Amount (KES)</label>
                        <input type="number" id="dAmount" placeholder="What the recipient receives"
                            min="{{ config('pregota.min_amount') }}" max="{{ config('pregota.max_amount') }}">
                    </div>

                    <div class="fee-preview" id="dFeePreview">
                        <div class="fee-row"><span>Recipient gets</span><span id="dRecipient">—</span></div>
                        <div class="fee-row"><span>Service fee (flat)</span><span>KES {{ config('pregota.gift_direct_fee') }}</span></div>
                        <div class="fee-row total"><span>You pay (M-Pesa)</span><span id="dGross">—</span></div>
                    </div>

                    <div class="form-group">
                        <label>Recipient M-Pesa Number</label>
                        <input type="tel" id="dRecipientPhone" placeholder="07XX XXX XXX">
                        <div class="hint">Their number is never stored — used only to send the money.</div>
                    </div>

                    <div class="form-group">
                        <label>Your M-Pesa Number</label>
                        <input type="tel" id="dSenderPhone" placeholder="07XX XXX XXX">
                    </div>

                    <button class="btn-primary" id="dSubmitBtn" onclick="sendDirect()">Send Direct Gift →</button>

                    <div class="direct-pending" id="dPending">
                        <span class="status-dot"></span>Waiting for M-Pesa confirmation...
                    </div>
                </div>

                <!-- Direct success state -->
                <div class="direct-success" id="directSuccess">
                    <div style="font-size:44px;margin-bottom:12px">⚡</div>
                    <div style="font-size:20px;font-weight:900;margin-bottom:6px">Gift Sent!</div>
                    <div style="font-size:13px;color:rgba(255,255,255,.78);margin-bottom:6px">KES <span id="dSentAmount">—</span> sent directly to the recipient's M-Pesa.</div>
                    <div style="font-size:12px;color:rgba(255,255,255,.82);margin-bottom:20px">No code needed — they should receive an M-Pesa notification shortly.</div>
                    <button onclick="resetDirect()" style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:10px;padding:10px 22px;color:rgba(255,255,255,.6);cursor:pointer;font-size:13px;font-weight:600">Send Another →</button>
                </div>
            </div>

        </div>
    </div>

    <div class="right-foot">Your phone number is never stored · Powered by M-Pesa</div>
</div>

<!-- MODAL -->
<div class="modal" id="successModal">
    <div class="modal-box">
        <div style="font-size:40px;margin-bottom:12px">🎁</div>
        <h2 style="font-size:20px;font-weight:800;margin-bottom:8px">Gift Code Generated!</h2>
        <p style="color:rgba(255,255,255,.78);font-size:13px;margin-bottom:4px">Share this code with your recipient</p>
        <div class="code-display" id="voucherCode">—</div>
        <button class="copy-btn" onclick="copyCode()">Copy Code</button>

        <div style="background:rgba(0,166,81,.1);border:1px solid rgba(0,166,81,.25);border-radius:10px;padding:12px 14px;margin-top:18px;text-align:left">
            <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.68);margin-bottom:6px">Cancel / Recall Token</div>
            <div style="font-size:15px;font-weight:800;font-family:monospace;color:#25D366;letter-spacing:.1em" id="recallToken">—</div>
            <button class="copy-btn" onclick="copyRecallToken()" style="margin-top:8px;font-size:11px;padding:5px 14px">Copy Token</button>
            <p style="font-size:11px;color:rgba(255,255,255,.68);margin-top:8px;line-height:1.55">Save this token. If the gift is unredeemed you can use it on the Track page to cancel. The deposit fee is not refunded — you receive the face value only.</p>
        </div>

        <p id="mpesaStatus" style="color:rgba(255,255,255,.68);font-size:12px;margin-top:16px">
            <span class="status-dot"></span>Waiting for M-Pesa confirmation...
        </p>

        <div id="holdAlert" style="display:none;background:rgba(251,191,36,.1);border:1px solid rgba(251,191,36,.3);border-radius:10px;padding:12px 14px;margin-top:12px;text-align:left">
            <div style="font-size:13px;font-weight:700;color:#fbbf24;margin-bottom:4px">
                ⚠️ You have <span id="holdCountdown" style="font-family:monospace">5:00</span> to cancel
            </div>
            <div style="font-size:11px;color:rgba(255,255,255,.72);line-height:1.55">If you sent this to the wrong person, go to <strong style="color:rgba(255,255,255,.7)">Track Gift</strong> and use your recall token above before this window closes.</div>
        </div>
        <div id="holdGone" style="display:none;font-size:11px;color:#4ade80;margin-top:10px">✅ Verification window passed — gift is now claimable.</div>

        <p style="color:rgba(255,255,255,.25);font-size:11px;margin-top:10px">Valid for 72 hours · Share via WhatsApp, SMS, or verbally</p>
        <button onclick="document.getElementById('successModal').classList.remove('show')"
            style="margin-top:14px;background:none;border:none;color:rgba(255,255,255,.82);cursor:pointer;font-size:13px">Close</button>
    </div>
</div>

<script>
const fmt       = n => 'KES ' + Number(n).toLocaleString('en-KE', {minimumFractionDigits:2});
const CSRF      = document.querySelector('meta[name=csrf-token]').content;
const DIRECT_FEE  = {{ config('pregota.gift_direct_fee') }};
const MIN_AMT     = {{ config('pregota.min_amount') }};
const FEE_IN_PCT  = {{ config('pregota.fee_in_pct') }};
const FEE_OUT_PCT = {{ config('pregota.fee_out_pct') }};
const FEE_MIN     = {{ config('pregota.fee_min_kes') }};

// ── Tab switching ──────────────────────────────────────────────────────────
function switchTab(tab) {
    document.getElementById('tabVoucher').classList.toggle('active', tab === 'voucher');
    document.getElementById('tabDirect').classList.toggle('active', tab === 'direct');
    document.getElementById('tabVoucherBtn').classList.toggle('active', tab === 'voucher');
    document.getElementById('tabDirectBtn').classList.toggle('active', tab === 'direct');
}

// ── Pre-fill from URL query params (when redirected from tip page) ─────────
(function() {
    const p = new URLSearchParams(window.location.search);
    const amt  = p.get('amount');
    const mode = p.get('mode');
    if (mode === 'direct') switchTab('direct');
    if (amt) {
        const amtNum = parseFloat(amt);
        if (mode === 'direct') {
            document.getElementById('dAmount').value = amt;
            updateDirectFee(amtNum);
        } else {
            document.getElementById('amount').value = amt;
            updateVoucherFee(amtNum);
        }
    }
})();

// ── Voucher fee preview ────────────────────────────────────────────────────
function updateVoucherFee(v) {
    const preview = document.getElementById('feePreview');
    if (!v || v < MIN_AMT) { preview.style.display = 'none'; return; }
    const feeOutCalc = v * FEE_OUT_PCT / (100 - FEE_OUT_PCT);
    const feeOut     = Math.max(FEE_MIN / 2, feeOutCalc);
    const faceValue  = v + feeOut;
    const feeInCalc  = faceValue * FEE_IN_PCT / (100 - FEE_IN_PCT);
    const feeIn      = Math.max(FEE_MIN, feeInCalc);
    const gross      = Math.ceil(faceValue + feeIn);
    document.getElementById('fRecipient').textContent   = fmt(v);
    document.getElementById('fFeeOut').textContent      = fmt(feeOut);
    document.getElementById('fFeeIn').textContent       = fmt(feeIn);
    document.getElementById('fGross').textContent       = fmt(gross);
    document.getElementById('fFeeOutLabel').textContent = feeOutCalc < FEE_MIN/2 ? 'Payout fee (minimum)' : `Payout fee (${FEE_OUT_PCT}%)`;
    document.getElementById('fFeeInLabel').textContent  = feeInCalc  < FEE_MIN   ? 'Deposit fee (minimum)' : `Deposit fee (${FEE_IN_PCT}%)`;
    preview.style.display = 'block';
}

document.getElementById('amount').addEventListener('input', function() {
    updateVoucherFee(parseFloat(this.value));
});

document.getElementById('giftForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('submitBtn');
    btn.disabled = true; btn.textContent = 'Sending STK Push...';

    const data = {
        amount:      document.getElementById('amount').value,
        phone:       document.getElementById('phone').value,
        message:     document.getElementById('message').value,
        sender_name: document.getElementById('sender_name').value.trim(),
        _token:      document.querySelector('meta[name=csrf-token]').content,
    };

    try {
        const res  = await fetch('/gift/initiate', {method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':data._token},body:JSON.stringify(data)});
        const json = await res.json();
        if (json.success) {
            document.getElementById('voucherCode').textContent = json.voucher_code;
            document.getElementById('recallToken').textContent = json.recall_token || '—';
            document.getElementById('successModal').classList.add('show');
            pollStatus(json.voucher_code);
        } else {
            alert(json.message || 'Something went wrong. Please try again.');
        }
    } catch(err) {
        alert('Network error. Please try again.');
    } finally {
        btn.disabled = false; btn.textContent = 'Send Gift →';
    }
});

async function pollStatus(code) {
    for (let i = 0; i < 20; i++) {
        await new Promise(r => setTimeout(r, 3000));
        const res  = await fetch('/gift/status?code=' + code);
        const json = await res.json();
        if (json.status === 'active') {
            document.querySelector('.status-dot').style.cssText = 'background:#22c55e;animation:none';
            document.getElementById('mpesaStatus').innerHTML = '✅ Payment confirmed! Share the code above with your recipient.';
            document.getElementById('mpesaStatus').style.color = 'rgba(255,255,255,.82)';
            if (json.hold_seconds > 0) {
                document.getElementById('holdAlert').style.display = 'block';
                startHoldCountdown(json.hold_seconds);
            }
            break;
        }
        if (json.status === 'cancelled') {
            document.getElementById('mpesaStatus').textContent = '❌ Payment was cancelled or failed.';
            break;
        }
    }
}

let holdTimer;
function startHoldCountdown(seconds) {
    clearInterval(holdTimer);
    const el = document.getElementById('holdCountdown');
    function tick() {
        if (seconds <= 0) {
            clearInterval(holdTimer);
            document.getElementById('holdAlert').style.display = 'none';
            document.getElementById('holdGone').style.display = 'block';
            return;
        }
        const m = Math.floor(seconds / 60), s = seconds % 60;
        el.textContent = m + ':' + String(s).padStart(2, '0');
        seconds--;
    }
    tick();
    holdTimer = setInterval(tick, 1000);
}

function copyCode() {
    navigator.clipboard.writeText(document.getElementById('voucherCode').textContent).then(() => {
        const btn = document.querySelectorAll('.copy-btn')[0];
        btn.textContent = 'Copied!';
        setTimeout(() => btn.textContent = 'Copy Code', 2000);
    });
}
function copyRecallToken() {
    navigator.clipboard.writeText(document.getElementById('recallToken').textContent).then(() => {
        const btn = document.querySelectorAll('.copy-btn')[1];
        btn.textContent = 'Copied!';
        setTimeout(() => btn.textContent = 'Copy Token', 2000);
    });
}

// ── Direct gift fee preview ────────────────────────────────────────────────
function updateDirectFee(v) {
    const preview = document.getElementById('dFeePreview');
    if (!v || v < MIN_AMT) { preview.style.display = 'none'; return; }
    document.getElementById('dRecipient').textContent = fmt(v);
    document.getElementById('dGross').textContent     = fmt(Math.ceil(v + DIRECT_FEE));
    preview.style.display = 'block';
}
document.getElementById('dAmount').addEventListener('input', function() {
    updateDirectFee(parseFloat(this.value));
});

// ── Direct gift submission ─────────────────────────────────────────────────
let currentDirectId = null;

async function sendDirect() {
    const amount    = parseFloat(document.getElementById('dAmount').value);
    const recipient = document.getElementById('dRecipientPhone').value.trim();
    const sender    = document.getElementById('dSenderPhone').value.trim();
    const btn       = document.getElementById('dSubmitBtn');

    if (!amount || !recipient || !sender) {
        alert('Please fill in all fields.');
        return;
    }
    if (recipient === sender) {
        alert('Sender and recipient numbers cannot be the same.');
        return;
    }

    btn.disabled = true; btn.textContent = 'Sending...';

    try {
        const res  = await fetch('/gift/direct', {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF},
            body: JSON.stringify({amount, sender_phone: sender, recipient_phone: recipient}),
        });
        const json = await res.json();
        if (json.success) {
            currentDirectId = json.gift_id;
            document.getElementById('dPending').style.display = 'block';
            btn.textContent = 'Waiting for PIN...';
            pollDirectStatus(json.gift_id, amount);
        } else {
            alert(json.message || 'Something went wrong.');
            btn.disabled = false; btn.textContent = 'Send Direct Gift →';
        }
    } catch(e) {
        alert('Network error. Please try again.');
        btn.disabled = false; btn.textContent = 'Send Direct Gift →';
    }
}

async function pollDirectStatus(giftId, amount) {
    for (let i = 0; i < 20; i++) {
        await new Promise(r => setTimeout(r, 3000));
        const res  = await fetch('/gift/direct/status?gift_id=' + giftId);
        const json = await res.json();
        if (json.status === 'paid') {
            document.getElementById('dSentAmount').textContent = Number(amount).toLocaleString('en-KE');
            document.getElementById('directForm').style.display  = 'none';
            document.getElementById('directSuccess').style.display = 'block';
            return;
        }
        if (json.status === 'failed') {
            document.getElementById('dPending').innerHTML = '❌ Payment failed or cancelled. Please try again.';
            document.getElementById('dSubmitBtn').disabled = false;
            document.getElementById('dSubmitBtn').textContent = 'Send Direct Gift →';
            return;
        }
    }
    document.getElementById('dPending').innerHTML = '⚠️ Timed out. Check your M-Pesa — if charged, contact support.';
}

// ── Creator search ─────────────────────────────────────────────────────────
(function() {
    const input   = document.getElementById('creatorSearch');
    const results = document.getElementById('searchResults');
    let timer;

    function fmtKes(n) { return 'KES ' + Number(n).toLocaleString('en-KE'); }

    function renderResults(creators) {
        if (!creators.length) {
            results.innerHTML = '<div class="search-empty">No creators found</div>';
        } else {
            results.innerHTML = creators.map(c => {
                const initials = c.display_name.charAt(0).toUpperCase();
                const avatar   = c.photo_url
                    ? `<img src="${c.photo_url}" alt="${c.display_name}">`
                    : initials;
                return `<a href="/c/${c.handle}" class="creator-result">
                    <div class="cr-avatar">${avatar}</div>
                    <div class="cr-info">
                        <div class="cr-name">${c.display_name}</div>
                        <div class="cr-handle">@${c.handle}</div>
                        <div class="cr-min">Min gift ${fmtKes(c.min_gift_amount)}</div>
                    </div>
                </a>`;
            }).join('');
        }
        results.style.display = 'block';
    }

    input.addEventListener('input', function() {
        clearTimeout(timer);
        const q = this.value.trim();
        if (q.length < 2) { results.style.display = 'none'; return; }
        timer = setTimeout(async () => {
            try {
                const res  = await fetch('/gift/search?q=' + encodeURIComponent(q));
                const data = await res.json();
                renderResults(data);
            } catch(e) {}
        }, 350);
    });

    document.addEventListener('click', function(e) {
        if (!input.contains(e.target) && !results.contains(e.target)) {
            results.style.display = 'none';
        }
    });

    input.addEventListener('focus', function() {
        if (this.value.trim().length >= 2 && results.children.length) {
            results.style.display = 'block';
        }
    });
})();

function resetDirect() {
    document.getElementById('dAmount').value           = '';
    document.getElementById('dRecipientPhone').value   = '';
    document.getElementById('dSenderPhone').value      = '';
    document.getElementById('dFeePreview').style.display = 'none';
    document.getElementById('dPending').style.display    = 'none';
    document.getElementById('directSuccess').style.display = 'none';
    document.getElementById('directForm').style.display    = 'block';
    document.getElementById('dSubmitBtn').disabled = false;
    document.getElementById('dSubmitBtn').textContent = 'Send Direct Gift →';
}
</script>
</body>
</html>
