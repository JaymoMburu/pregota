<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pregota — Redeem Your Gift</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}
html,body{height:100%}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0f0f1a;color:#fff;min-height:100vh;display:flex;flex-direction:column}
.nav{padding:16px 24px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.08)}
.logo{font-size:22px;font-weight:900;background:linear-gradient(135deg,#7c3aed,#db2777);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.nav-link{color:rgba(255,255,255,.55);text-decoration:none;font-size:13px;font-weight:600;padding:7px 14px;border:1px solid rgba(255,255,255,.15);border-radius:8px}

.main{flex:1;display:flex;align-items:flex-start;justify-content:center;padding:32px 20px}
.card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.09);border-radius:20px;padding:32px 28px;max-width:480px;width:100%}

/* Steps */
.step{display:none}
.step.active{display:block}

/* Step 1 — Code entry */
.gift-icon{font-size:48px;text-align:center;margin-bottom:16px}
h2{font-size:20px;font-weight:800;margin-bottom:6px;text-align:center}
.sub{color:rgba(255,255,255,.5);font-size:13px;text-align:center;margin-bottom:24px;line-height:1.5}
.form-group{margin-bottom:14px}
label{display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.55);margin-bottom:6px}
input{width:100%;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.16);border-radius:10px;padding:13px 16px;color:#fff;font-size:15px;outline:none;transition:.2s;font-family:inherit}
input:focus{border-color:#7c3aed;background:rgba(124,58,237,.1)}
input::placeholder{color:rgba(255,255,255,.3)}
.btn{width:100%;padding:14px;border-radius:12px;border:none;font-size:16px;font-weight:700;cursor:pointer;background:linear-gradient(135deg,#7c3aed,#db2777);color:#fff;transition:.2s;margin-top:4px}
.btn:hover{opacity:.9}
.btn:disabled{opacity:.45;cursor:not-allowed}
.err{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3);border-radius:10px;padding:12px 14px;font-size:13px;color:#fca5a5;margin-bottom:14px;display:none}

/* Step 2 — Gift revealed + choice */
.gift-reveal{background:linear-gradient(135deg,rgba(124,58,237,.15),rgba(219,39,119,.1));border:1px solid rgba(124,58,237,.3);border-radius:14px;padding:20px;text-align:center;margin-bottom:24px}
.gift-amount{font-size:36px;font-weight:900;background:linear-gradient(135deg,#c084fc,#f472b6);-webkit-background-clip:text;-webkit-text-fill-color:transparent;line-height:1}
.gift-from{font-size:12px;color:rgba(255,255,255,.45);margin-top:8px}
.gift-msg-box{background:rgba(255,255,255,.05);border-radius:8px;padding:10px 12px;margin-top:10px;font-style:italic;font-size:13px;color:rgba(255,255,255,.65);line-height:1.5}

.choice-label{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.4);margin-bottom:12px}
.choices{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:8px}
.choice-btn{background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.12);border-radius:12px;padding:16px 12px;cursor:pointer;text-align:center;transition:.2s;color:#fff}
.choice-btn:hover{border-color:#7c3aed;background:rgba(124,58,237,.12)}
.choice-btn.selected{border-color:#7c3aed;background:rgba(124,58,237,.15)}
.choice-emoji{font-size:26px;margin-bottom:6px}
.choice-title{font-size:13px;font-weight:700}
.choice-sub{font-size:11px;color:rgba(255,255,255,.4);margin-top:2px}

/* Step 3a — Cash claim */
.back-link{font-size:13px;color:rgba(255,255,255,.4);cursor:pointer;margin-bottom:20px;display:inline-flex;align-items:center;gap:6px;background:none;border:none;color:rgba(255,255,255,.45)}
.back-link:hover{color:#fff}

/* Step 3b — Partner grid */
.partner-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px}
.partner-card{border-radius:12px;padding:14px;border:1px solid rgba(255,255,255,.1);cursor:pointer;text-decoration:none;color:#fff;display:flex;flex-direction:column;gap:6px;transition:.2s;background:rgba(255,255,255,.04)}
.partner-card:hover{border-color:rgba(255,255,255,.3);background:rgba(255,255,255,.07);transform:translateY(-1px)}
.partner-emoji{font-size:24px}
.partner-name{font-size:13px;font-weight:700}
.partner-tag{font-size:11px;color:rgba(255,255,255,.45);line-height:1.4}
.partner-cta{font-size:11px;font-weight:700;margin-top:4px;opacity:.7}

/* Success */
.success-box{background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.3);border-radius:14px;padding:24px;text-align:center}

.footer{padding:16px;text-align:center;color:rgba(255,255,255,.25);font-size:11px;border-top:1px solid rgba(255,255,255,.06)}
</style>
</head>
<body>
<nav class="nav">
    <div class="logo">Pregota</div>
    <div style="display:flex;gap:8px">
        <a href="{{ route('track') }}" class="nav-link">Track Gift</a>
        <a href="{{ route('home') }}" class="nav-link">Send a Gift</a>
    </div>
</nav>

<div class="main">
    <div class="card">

        <!-- ── STEP 1: Enter code ── -->
        <div class="step active" id="step1">
            <div class="gift-icon">🎁</div>
            <h2>Redeem Your Gift</h2>
            <p class="sub">Enter the gift code you received to see your gift and choose what to do with it.</p>

            <div class="err" id="s1Err"></div>

            <div class="form-group">
                <label>Gift Code</label>
                <input type="text" id="codeInput" placeholder="PRG-XXXX-XXXX" maxlength="13" autocomplete="off"
                    oninput="this.value=this.value.toUpperCase().replace(/[^A-Z0-9-]/g,'')">
            </div>
            <button class="btn" id="verifyBtn" onclick="verifyCode()">Open Gift →</button>
        </div>

        <!-- ── STEP 2: Choice ── -->
        <div class="step" id="step2">
            <div class="gift-reveal">
                <div style="font-size:13px;color:rgba(255,255,255,.45);margin-bottom:6px">Your gift</div>
                <div class="gift-amount" id="giftAmount">—</div>
                <div class="gift-from" id="giftFrom"></div>
                <div class="gift-msg-box" id="giftMsg" style="display:none"></div>
            </div>

            <div class="choice-label">What would you like to do?</div>
            <div class="choices">
                <div class="choice-btn" onclick="goTo('cash')">
                    <div class="choice-emoji">💸</div>
                    <div class="choice-title">Get Cash</div>
                    <div class="choice-sub">Send to M-Pesa</div>
                </div>
                <div class="choice-btn" onclick="goTo('shop')">
                    <div class="choice-emoji">🛍️</div>
                    <div class="choice-title">Shop</div>
                    <div class="choice-sub">Jumia, Naivas &amp; more</div>
                </div>
                <div class="choice-btn" onclick="goTo('save')">
                    <div class="choice-emoji">🏦</div>
                    <div class="choice-title">Save</div>
                    <div class="choice-sub">Bank or M-Shwari</div>
                </div>
                <div class="choice-btn" onclick="goTo('invest')">
                    <div class="choice-emoji">📈</div>
                    <div class="choice-title">Invest</div>
                    <div class="choice-sub">Money Market Funds</div>
                </div>
            </div>
        </div>

        <!-- ── STEP 3a: Cash claim ── -->
        <div class="step" id="step3-cash">
            <button class="back-link" onclick="showStep('step2')">← Back</button>
            <h2 style="text-align:left;margin-bottom:4px">Send to M-Pesa</h2>
            <p class="sub" style="text-align:left;margin-bottom:20px">Enter the number where you want to receive <strong id="cashAmount" style="color:#fff"></strong>.</p>

            <div class="err" id="cashErr"></div>

            <div class="form-group">
                <label>Your M-Pesa Number</label>
                <input type="tel" id="phoneInput" placeholder="07XX XXX XXX">
            </div>
            <button class="btn" id="claimBtn" onclick="claimCash()">Claim Gift →</button>

            <div id="claimSuccess" style="display:none;margin-top:16px">
                <div class="success-box">
                    <div style="font-size:36px;margin-bottom:10px">✅</div>
                    <div style="font-size:16px;font-weight:800;margin-bottom:6px">Gift Claimed!</div>
                    <div style="font-size:13px;color:rgba(255,255,255,.6)" id="claimMsg"></div>
                </div>
            </div>
        </div>

        <!-- ── STEP 3b: Shop / Save / Invest partners ── -->
        <div class="step" id="step3-partners">
            <button class="back-link" onclick="showStep('step2')">← Back</button>
            <h2 style="text-align:left;margin-bottom:4px" id="partnerTitle">Shop</h2>
            <p class="sub" style="text-align:left;margin-bottom:20px">Choose a partner and use your gift money there. Your code is still valid to cash out anytime.</p>

            <div class="partner-grid" id="partnerGrid"></div>

            <p style="font-size:11px;color:rgba(255,255,255,.3);margin-top:16px;text-align:center">
                Your gift code remains valid · Cash out anytime on this page
            </p>
        </div>

    </div>
</div>

<footer class="footer">© 2026 Pregota · Anonymous Gift Transfers</footer>

<script>
const CSRF = document.querySelector('meta[name=csrf-token]').content;
const fmt  = n => 'KES ' + Number(n).toLocaleString('en-KE', {minimumFractionDigits:2});

let _partners = {};
let _code     = '';

function showStep(id) {
    document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));
    document.getElementById(id).classList.add('active');
}

async function verifyCode() {
    const code = document.getElementById('codeInput').value.trim();
    if (!code) return;
    const btn = document.getElementById('verifyBtn');
    btn.disabled = true; btn.textContent = 'Checking...';
    document.getElementById('s1Err').style.display = 'none';

    try {
        const res  = await fetch('/gift/verify', {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
            body: JSON.stringify({code}),
        });
        const json = await res.json();

        if (!json.found || !json.valid) {
            const el = document.getElementById('s1Err');
            el.textContent = json.message || 'Invalid gift code.';
            el.style.display = 'block';
            return;
        }

        if (json.in_hold) {
            showHoldScreen(json.hold_seconds);
            return;
        }

        _code     = code;
        _partners = json.partners || {};

        document.getElementById('giftAmount').textContent = fmt(json.payout_amount);
        document.getElementById('cashAmount').textContent = fmt(json.payout_amount);

        const fromEl = document.getElementById('giftFrom');
        fromEl.textContent = json.sender_name ? 'From: ' + json.sender_name : 'From: Anonymous';

        const msgEl = document.getElementById('giftMsg');
        if (json.gift_msg) {
            msgEl.textContent = '"' + json.gift_msg + '"';
            msgEl.style.display = 'block';
        }

        showStep('step2');
    } catch(e) {
        const el = document.getElementById('s1Err');
        el.textContent = 'Network error. Please try again.';
        el.style.display = 'block';
    } finally {
        btn.disabled = false; btn.textContent = 'Open Gift →';
    }
}

const categoryMeta = {
    shop:   { title: 'Shop with your gift',    cta: 'Visit' },
    save:   { title: 'Save your gift',          cta: 'Save here' },
    invest: { title: 'Invest your gift',        cta: 'Invest now' },
};

function goTo(choice) {
    if (choice === 'cash') {
        showStep('step3-cash');
        return;
    }

    const meta    = categoryMeta[choice];
    const list    = _partners[choice] || [];
    document.getElementById('partnerTitle').textContent = meta.title;

    const grid = document.getElementById('partnerGrid');
    grid.innerHTML = list.map(p => `
        <a class="partner-card" href="/gift/partner/${p.slug}?code=${encodeURIComponent(_code)}" target="_blank" rel="noopener"
            style="border-color:${p.brand_color}22">
            <div class="partner-emoji">${p.logo_emoji}</div>
            <div class="partner-name">${p.name}</div>
            <div class="partner-tag">${p.tagline || ''}</div>
            <div class="partner-cta" style="color:${p.brand_color}">${p.cta_text} →</div>
        </a>
    `).join('');

    if (!list.length) {
        grid.innerHTML = '<p style="color:rgba(255,255,255,.4);font-size:13px;grid-column:1/-1">No partners listed yet. Check back soon.</p>';
    }

    showStep('step3-partners');
}

async function claimCash() {
    const phone = document.getElementById('phoneInput').value.trim();
    const btn   = document.getElementById('claimBtn');
    const errEl = document.getElementById('cashErr');
    errEl.style.display = 'none';

    if (!phone) return;
    btn.disabled = true; btn.textContent = 'Processing...';

    try {
        const res  = await fetch('/gift/claim', {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
            body: JSON.stringify({code: _code, phone}),
        });
        const json = await res.json();

        if (json.success) {
            document.getElementById('claimSuccess').style.display = 'block';
            document.getElementById('claimMsg').textContent = json.message;
            document.querySelector('#step3-cash form, #step3-cash .form-group') && null;
            document.getElementById('phoneInput').closest('.form-group').style.display = 'none';
            btn.style.display = 'none';
        } else {
            errEl.textContent = json.message || 'Redemption failed.';
            errEl.style.display = 'block';
        }
    } catch(e) {
        errEl.textContent = 'Network error. Please try again.';
        errEl.style.display = 'block';
    } finally {
        btn.disabled = false; btn.textContent = 'Claim Gift →';
    }
}

document.getElementById('codeInput').addEventListener('keydown', e => {
    if (e.key === 'Enter') verifyCode();
});

let holdTimer;
function showHoldScreen(seconds) {
    const errEl = document.getElementById('s1Err');
    const btn   = document.getElementById('verifyBtn');

    errEl.style.display = 'none';

    // Replace button with hold notice
    btn.style.display = 'none';

    let existing = document.getElementById('holdNotice');
    if (!existing) {
        existing = document.createElement('div');
        existing.id = 'holdNotice';
        existing.style.cssText = 'background:rgba(251,191,36,.1);border:1px solid rgba(251,191,36,.3);border-radius:12px;padding:18px;text-align:center;margin-top:4px';
        btn.insertAdjacentElement('afterend', existing);
    }

    function render(s) {
        const m = Math.floor(s / 60), sec = s % 60;
        const fmt = m + ':' + String(sec).padStart(2, '0');
        existing.innerHTML = `
            <div style="font-size:32px;margin-bottom:8px">⏳</div>
            <div style="font-size:15px;font-weight:800;color:#fbbf24;margin-bottom:6px">Gift is in its verification window</div>
            <div style="font-size:28px;font-weight:900;font-family:monospace;color:#fbbf24;margin:10px 0">${fmt}</div>
            <div style="font-size:12px;color:rgba(255,255,255,.45);line-height:1.6">The sender is confirming you received this gift. You'll be able to open it when this reaches zero.</div>`;
    }

    render(seconds);
    clearInterval(holdTimer);
    holdTimer = setInterval(() => {
        seconds--;
        if (seconds <= 0) {
            clearInterval(holdTimer);
            existing.remove();
            btn.style.display = 'block';
            btn.textContent = 'Open Gift →';
            verifyCode(); // auto-retry
            return;
        }
        render(seconds);
    }, 1000);
}
</script>
</body>
</html>
