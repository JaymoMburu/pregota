<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pregota — Track Your Gift</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh;display:flex;flex-direction:column}
.nav{padding:16px 24px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.08)}
.logo{font-size:22px;font-weight:900;background:linear-gradient(135deg,#00A651,#007A33);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.nav-link{color:rgba(255,255,255,.6);text-decoration:none;font-size:14px;font-weight:600;padding:8px 16px;border:1px solid rgba(255,255,255,.2);border-radius:8px}
.main{flex:1;display:flex;align-items:center;justify-content:center;padding:40px 24px}
.card{background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:20px;padding:40px 32px;max-width:420px;width:100%;text-align:center}
.icon{font-size:48px;margin-bottom:20px}
h1{font-size:24px;font-weight:900;margin-bottom:8px}
.sub{color:rgba(255,255,255,.5);font-size:14px;margin-bottom:32px}
.form-group{margin-bottom:18px;text-align:left}
label{display:block;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.5);margin-bottom:8px}
input{width:100%;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.15);border-radius:10px;padding:14px 16px;color:#fff;font-size:16px;outline:none;transition:.2s;letter-spacing:.05em}
input:focus{border-color:#00A651}
input::placeholder{color:rgba(255,255,255,.25);letter-spacing:normal}
.btn{width:100%;padding:15px;border-radius:12px;border:none;font-size:16px;font-weight:700;cursor:pointer;background:linear-gradient(135deg,#00A651,#007A33);color:#fff;margin-top:8px}
.btn:disabled{opacity:.5;cursor:not-allowed}
.result{margin-top:28px;display:none}
.status-card{border-radius:14px;padding:24px;text-align:center}
.status-card.pending{background:rgba(251,191,36,.08);border:1px solid rgba(251,191,36,.25)}
.status-card.active{background:rgba(0,166,81,.08);border:1px solid rgba(0,166,81,.25)}
.status-card.redeemed{background:rgba(34,197,94,.08);border:1px solid rgba(34,197,94,.25)}
.status-card.expired,.status-card.cancelled,.status-card.recalled{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.1)}
.status-icon{font-size:40px;margin-bottom:12px}
.status-title{font-size:20px;font-weight:800;margin-bottom:8px}
.status-desc{font-size:13px;color:rgba(255,255,255,.55);line-height:1.6}
.meta-row{display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid rgba(255,255,255,.06);font-size:13px;margin-top:16px}
.meta-row:last-child{border-bottom:none}
.meta-lbl{color:rgba(255,255,255,.4)}
.meta-val{font-weight:600}
.countdown{font-size:12px;color:#fbbf24;margin-top:8px}
.footer{text-align:center;padding:24px;color:rgba(255,255,255,.25);font-size:12px;border-top:1px solid rgba(255,255,255,.06)}
</style>
</head>
<body>
<nav class="nav">
    <div class="logo">Pregota</div>
    <a href="{{ route('home') }}" class="nav-link">Send a Gift</a>
</nav>

<div class="main">
    <div class="card">
        <div class="icon">🔍</div>
        <h1>Track Your Gift</h1>
        <p class="sub">Enter your gift code to see if it's been redeemed. No identity is revealed.</p>

        <form id="trackForm">
            <div class="form-group">
                <label>Gift Code</label>
                <input type="text" id="code" placeholder="PRG-XXXX-XXXX" maxlength="13" required
                    oninput="this.value=this.value.toUpperCase().replace(/[^A-Z0-9-]/g,'')">
            </div>
            <button type="submit" class="btn" id="trackBtn">Check Status →</button>
        </form>

        <div class="result" id="result"></div>
    </div>
</div>

<footer class="footer">
    © 2026 Pregota · Anonymous Gift Transfers · pregota.com
</footer>

<script>
const statusMeta = {
    pending:   { icon:'⏳', title:'Awaiting Payment',    desc:'M-Pesa payment has not been confirmed yet.',           color:'pending'   },
    active:    { icon:'🎁', title:'Ready to Redeem',     desc:'Payment confirmed. This gift is waiting to be claimed.',color:'active'    },
    redeemed:  { icon:'✅', title:'Gift Claimed!',        desc:'Someone has already redeemed this gift code.',         color:'redeemed'  },
    expired:   { icon:'⌛', title:'Expired',              desc:'This gift code has expired and can no longer be used.',color:'expired'   },
    cancelled: { icon:'❌', title:'Cancelled',            desc:'This gift was cancelled or the payment failed.',       color:'cancelled' },
    recalled:  { icon:'↩️', title:'Recalled by Sender',  desc:'The sender recalled this gift. A refund was sent to them via M-Pesa.', color:'cancelled' },
};

document.getElementById('trackForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('trackBtn');
    btn.disabled = true; btn.textContent = 'Checking...';

    try {
        const res  = await fetch('/gift/track', {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content},
            body: JSON.stringify({code: document.getElementById('code').value.trim()}),
        });
        const json = await res.json();
        renderResult(json);
    } catch(err) {
        alert('Network error. Please try again.');
    } finally {
        btn.disabled = false; btn.textContent = 'Check Status →';
    }
});

function renderResult(json) {
    const el = document.getElementById('result');
    el.style.display = 'block';

    if (!json.found) {
        el.innerHTML = `<div class="status-card cancelled"><div class="status-icon">❓</div><div class="status-title">Not Found</div><div class="status-desc">No gift with that code exists. Please check and try again.</div></div>`;
        return;
    }

    // Hold window — active but not yet claimable
    if (json.status === 'active' && json.in_hold) {
        renderHoldState(json);
        return;
    }

    const m = statusMeta[json.status] || statusMeta.cancelled;
    let rows = '';
    rows += `<div class="meta-row"><span class="meta-lbl">Face Value</span><span class="meta-val">KES ${Number(json.face_value).toLocaleString('en-KE',{minimumFractionDigits:2})}</span></div>`;
    rows += `<div class="meta-row"><span class="meta-lbl">Recipient Payout</span><span class="meta-val">KES ${Number(json.payout_amount).toLocaleString('en-KE',{minimumFractionDigits:2})}</span></div>`;
    rows += `<div class="meta-row"><span class="meta-lbl">Created</span><span class="meta-val">${json.created_at}</span></div>`;
    if (json.expires_at && json.status === 'active') {
        rows += `<div class="meta-row"><span class="meta-lbl">Expires</span><span class="meta-val">${json.expires_at}</span></div>`;
    }
    if (json.redeemed_at) {
        rows += `<div class="meta-row"><span class="meta-lbl">Redeemed</span><span class="meta-val">${json.redeemed_at}</span></div>`;
    }

    const countdown = (json.status === 'active' && json.expires_seconds > 0)
        ? `<div class="countdown">⏱ Expires in ${formatSeconds(json.expires_seconds)}</div>` : '';

    const trackedCode = document.getElementById('code').value.trim();
    const recallForm = json.status === 'active' ? `
        <div id="recallSection" style="margin-top:20px;border-top:1px solid rgba(255,255,255,.08);padding-top:18px;text-align:left">
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.4);margin-bottom:10px">Cancel This Gift</div>
            <div style="font-size:12px;color:rgba(255,255,255,.4);margin-bottom:12px;line-height:1.55">Changed your mind? Enter your recall token and M-Pesa number to cancel. You will receive the face value back — the deposit fee is <strong style="color:rgba(255,255,255,.6)">not</strong> refunded.</div>
            <input type="text" id="recallTokenInput" placeholder="RC-XXXX-XXXX" maxlength="15"
                style="width:100%;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.15);border-radius:8px;padding:10px 12px;color:#fff;font-size:13px;outline:none;margin-bottom:8px;font-family:monospace;letter-spacing:.08em"
                oninput="this.value=this.value.toUpperCase().replace(/[^A-Z0-9-]/g,'')">
            <input type="tel" id="recallPhoneInput" placeholder="07XX XXX XXX (your M-Pesa)"
                style="width:100%;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.15);border-radius:8px;padding:10px 12px;color:#fff;font-size:13px;outline:none;margin-bottom:10px">
            <button onclick="submitRecall(document.getElementById('code').value.trim())"
                id="recallBtn"
                style="width:100%;padding:11px;border-radius:10px;border:none;font-size:14px;font-weight:700;cursor:pointer;background:linear-gradient(135deg,#dc2626,#9b1c1c);color:#fff">
                Cancel Gift &amp; Refund →
            </button>
            <div id="recallMsg" style="font-size:12px;margin-top:8px;display:none"></div>
        </div>` : '';

    el.innerHTML = `<div class="status-card ${m.color}">
        <div class="status-icon">${m.icon}</div>
        <div class="status-title">${m.title}</div>
        <div class="status-desc">${m.desc}</div>
        ${countdown}
        ${rows}
        ${recallForm}
    </div>`;

    if (json.status === 'active' && json.expires_seconds > 0) startCountdown(json.expires_seconds);
}

function renderHoldState(json) {
    const el = document.getElementById('result');
    el.style.display = 'block';
    el.innerHTML = `<div class="status-card active" style="border-color:rgba(251,191,36,.4);background:rgba(251,191,36,.06)">
        <div class="status-icon">⏳</div>
        <div class="status-title" style="color:#fbbf24">Verification Window Active</div>
        <div class="status-desc">Payment confirmed. The sender has a short window to verify they sent this to the right person before it becomes claimable.</div>
        <div class="countdown" id="holdCountdown" style="font-size:20px;font-weight:900;font-family:monospace;color:#fbbf24;margin:14px 0">${formatSeconds(json.hold_seconds)}</div>
        <div style="font-size:12px;color:rgba(255,255,255,.35);margin-bottom:16px">Gift becomes claimable when this reaches zero</div>
        <div style="border-top:1px solid rgba(255,255,255,.08);padding-top:16px;text-align:left">
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(251,191,36,.7);margin-bottom:8px">⚠️ Sent to the wrong person?</div>
            <div style="font-size:12px;color:rgba(255,255,255,.4);margin-bottom:10px;line-height:1.55">Cancel now using your recall token. The deposit fee is not refunded — you receive the face value only.</div>
            <input type="text" id="recallTokenInput" placeholder="RC-XXXX-XXXX" maxlength="15"
                style="width:100%;background:rgba(255,255,255,.07);border:1px solid rgba(251,191,36,.3);border-radius:8px;padding:10px 12px;color:#fff;font-size:13px;outline:none;margin-bottom:8px;font-family:monospace;letter-spacing:.08em"
                oninput="this.value=this.value.toUpperCase().replace(/[^A-Z0-9-]/g,'')">
            <input type="tel" id="recallPhoneInput" placeholder="07XX XXX XXX (your M-Pesa)"
                style="width:100%;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.15);border-radius:8px;padding:10px 12px;color:#fff;font-size:13px;outline:none;margin-bottom:10px">
            <button onclick="submitRecall(document.getElementById('code').value.trim())" id="recallBtn"
                style="width:100%;padding:11px;border-radius:10px;border:none;font-size:14px;font-weight:700;cursor:pointer;background:linear-gradient(135deg,#dc2626,#9b1c1c);color:#fff">
                Cancel Gift &amp; Refund →
            </button>
            <div id="recallMsg" style="font-size:12px;margin-top:8px;display:none"></div>
        </div>
    </div>`;
    startHoldCountdown(json.hold_seconds);
}

let holdInterval;
function startHoldCountdown(seconds) {
    clearInterval(holdInterval);
    holdInterval = setInterval(() => {
        seconds--;
        const el = document.getElementById('holdCountdown');
        if (!el) { clearInterval(holdInterval); return; }
        if (seconds <= 0) {
            clearInterval(holdInterval);
            // Re-check status — window expired
            document.getElementById('trackForm').dispatchEvent(new Event('submit'));
            return;
        }
        el.textContent = formatSeconds(seconds);
    }, 1000);
}

let countdownInterval;
function startCountdown(seconds) {
    clearInterval(countdownInterval);
    countdownInterval = setInterval(() => {
        seconds--;
        const el = document.querySelector('.countdown');
        if (!el) { clearInterval(countdownInterval); return; }
        if (seconds <= 0) { el.textContent = '⌛ Expired'; clearInterval(countdownInterval); return; }
        el.textContent = '⏱ Expires in ' + formatSeconds(seconds);
    }, 1000);
}

function formatSeconds(s) {
    const h = Math.floor(s/3600), m = Math.floor((s%3600)/60), sec = s%60;
    if (h > 0) return `${h}h ${m}m`;
    if (m > 0) return `${m}m ${sec}s`;
    return `${sec}s`;
}

async function submitRecall(code) {
    const token = document.getElementById('recallTokenInput').value.trim();
    const phone = document.getElementById('recallPhoneInput').value.trim();
    const msgEl = document.getElementById('recallMsg');
    const btn   = document.getElementById('recallBtn');

    if (!token || !phone) { showRecallMsg('Please enter both the recall token and your M-Pesa number.', false); return; }

    btn.disabled = true; btn.textContent = 'Processing...';
    msgEl.style.display = 'none';

    try {
        const res  = await fetch('/gift/recall', {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content},
            body: JSON.stringify({code, recall_token: token, phone}),
        });
        const json = await res.json();
        showRecallMsg(json.message, json.success);
        if (json.success) {
            btn.style.display = 'none';
            document.getElementById('recallTokenInput').style.display = 'none';
            document.getElementById('recallPhoneInput').style.display = 'none';
        }
    } catch(err) {
        showRecallMsg('Network error. Please try again.', false);
    } finally {
        btn.disabled = false;
        if (btn.textContent === 'Processing...') btn.textContent = 'Cancel Gift & Refund →';
    }
}

function showRecallMsg(text, success) {
    const el = document.getElementById('recallMsg');
    el.style.display = 'block';
    el.style.color = success ? '#4ade80' : '#f87171';
    el.textContent = text;
}
</script>
</body>
</html>
