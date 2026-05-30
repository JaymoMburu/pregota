﻿<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gift Multiple Creators — Pregota</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}input,textarea,select,button{font-family:inherit;font-size:inherit}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh}

.nav{padding:14px 24px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.07);position:sticky;top:0;background:#0B141A;z-index:10}
.logo{font-size:20px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.nav-link{color:rgba(255,255,255,.72);text-decoration:none;font-size:13px;font-weight:600;padding:7px 14px;border:1px solid rgba(255,255,255,.1);border-radius:8px;transition:.15s}
.nav-link:hover{background:rgba(255,255,255,.06);color:#fff}

.page{max-width:560px;margin:0 auto;padding:28px 20px 60px;display:flex;flex-direction:column;gap:20px}

.hero{text-align:center;padding:8px 0 4px}
.hero h1{font-size:clamp(24px,4.5vw,34px);font-weight:900;line-height:1.1;margin-bottom:8px}
.hero h1 em{font-style:normal;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.hero p{font-size:14px;color:rgba(255,255,255,.72);line-height:1.6}

/* Search card */
.card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:16px;padding:20px}
.card-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.6);margin-bottom:12px}

.search-wrap{position:relative}
.search-input{width:100%;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.15);border-radius:10px;padding:12px 14px 12px 38px;color:#fff;font-size:14px;outline:none;transition:.2s;font-family:inherit}
.search-input:focus{border-color:#00A651;background:rgba(0,166,81,.08)}
.search-input::placeholder{color:rgba(255,255,255,.68)}
.search-icon{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:rgba(255,255,255,.72);font-size:15px;pointer-events:none}

.search-result{display:none;background:rgba(0,166,81,.08);border:1px solid rgba(0,166,81,.2);border-radius:10px;padding:12px 14px;margin-top:8px;align-items:center;gap:12px}
.search-result.show{display:flex}
.result-avatar{width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#00A651,#007A33);display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;flex-shrink:0;overflow:hidden}
.result-avatar img{width:100%;height:100%;object-fit:cover}
.result-info{flex:1;min-width:0}
.result-name{font-size:14px;font-weight:700;color:rgba(255,255,255,.9)}
.result-handle{font-size:12px;color:rgba(255,255,255,.6)}
.result-amount-wrap{display:flex;align-items:center;gap:8px}
.amount-input{width:110px;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.15);border-radius:8px;padding:9px 10px;color:#fff;font-size:14px;font-weight:700;outline:none;text-align:right;font-family:inherit}
.amount-input:focus{border-color:#00A651}
.add-btn{padding:9px 16px;border-radius:8px;background:linear-gradient(135deg,#00A651,#007A33);border:none;color:#fff;font-size:13px;font-weight:700;cursor:pointer;white-space:nowrap;transition:.15s}
.add-btn:hover{opacity:.9}
.add-btn:disabled{opacity:.4;cursor:not-allowed}
.search-err{font-size:12px;color:#f87171;margin-top:6px;display:none}

/* Gift basket */
.basket{display:flex;flex-direction:column;gap:10px}
.basket-item{display:flex;align-items:center;gap:12px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:12px;padding:12px 14px}
.basket-avatar{width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#00A651,#007A33);display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;flex-shrink:0}
.basket-info{flex:1;min-width:0}
.basket-name{font-size:14px;font-weight:700;color:rgba(255,255,255,.88)}
.basket-handle{font-size:11px;color:rgba(255,255,255,.72)}
.basket-amount{font-size:15px;font-weight:900;color:#25D366;flex-shrink:0}
.remove-btn{background:none;border:none;color:rgba(239,68,68,.5);font-size:18px;cursor:pointer;padding:2px 4px;line-height:1;flex-shrink:0;transition:.15s}
.remove-btn:hover{color:#f87171}
.empty-basket{text-align:center;padding:20px;font-size:13px;color:rgba(255,255,255,.72)}

/* Hint */
.limit-hint{font-size:11px;color:rgba(255,255,255,.72);text-align:center;margin-top:-8px}

/* Summary card */
.summary-row{display:flex;justify-content:space-between;align-items:center;padding:7px 0;font-size:13px;color:rgba(255,255,255,.72);border-bottom:1px solid rgba(255,255,255,.05)}
.summary-row:last-child{border-bottom:none}
.summary-row.total{color:#fff;font-weight:800;font-size:15px;border-top:1px solid rgba(255,255,255,.1);margin-top:6px;padding-top:12px}

/* Phone + submit */
label{display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.6);margin-bottom:6px}
input[type=tel]{width:100%;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.15);border-radius:10px;padding:12px 14px;color:#fff;font-size:15px;outline:none;transition:.2s;font-family:inherit}
input[type=tel]:focus{border-color:#00A651;background:rgba(0,166,81,.08)}
input[type=tel]::placeholder{color:rgba(255,255,255,.68)}

.submit-btn{width:100%;padding:16px;border-radius:12px;border:none;font-size:16px;font-weight:700;cursor:pointer;background:linear-gradient(135deg,#00A651,#007A33);color:#fff;transition:.2s;margin-top:4px}
.submit-btn:hover:not(:disabled){opacity:.9;transform:translateY(-1px)}
.submit-btn:disabled{opacity:.4;cursor:not-allowed;transform:none}

/* Status overlay */
.status-panel{display:none;text-align:center}
.status-panel.show{display:block}
.spin{width:40px;height:40px;border:3px solid rgba(0,166,81,.2);border-top-color:#00A651;border-radius:50%;animation:spin .8s linear infinite;margin:0 auto 16px}
@keyframes spin{to{transform:rotate(360deg)}}
.dist-list{text-align:left;display:flex;flex-direction:column;gap:8px;margin:20px 0}
.dist-item{display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:10px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08)}
.dist-icon{font-size:18px;flex-shrink:0}
.dist-info{flex:1}
.dist-name{font-size:13px;font-weight:700}
.dist-amount{font-size:12px;color:rgba(255,255,255,.72)}
.dist-status{font-size:11px;font-weight:700;padding:2px 8px;border-radius:8px}
.dst-pending{background:rgba(251,191,36,.1);color:#fbbf24}
.dst-sent{background:rgba(74,222,128,.1);color:#4ade80}
.dst-failed{background:rgba(239,68,68,.1);color:#f87171}

@media(max-width:520px){
    .result-amount-wrap{flex-wrap:wrap}
    .amount-input{width:90px}
}
</style>
</head>
<body>

<nav class="nav">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    <a href="{{ route('gift.home') }}" class="nav-link">Single Gift</a>
</nav>

@include('partials.module-nav', ['activeModule' => 'gift'])

<div class="page">

    <div class="hero">
        <h1>Gift <em>Multiple</em> Creators</h1>
        <p>Send M-Pesa gifts to up to 5 creators in one payment. One M-Pesa prompt — multiple gifts distributed automatically.</p>
    </div>

    <!-- Step 1: Search & add creators -->
    <div class="card" id="builderCard">
        <div class="card-label">Step 1 — Add Creators (2–5)</div>

        <div class="search-wrap">
            <span class="search-icon">@</span>
            <input type="text" id="handleInput" class="search-input" placeholder="Type creator handle (e.g. jaymo)"
                autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false">
        </div>
        <div id="searchErr" class="search-err"></div>

        <div class="search-result" id="searchResult">
            <div class="result-avatar" id="rAvatar">?</div>
            <div class="result-info">
                <div class="result-name" id="rName">—</div>
                <div class="result-handle" id="rHandle">—</div>
            </div>
            <div class="result-amount-wrap">
                <input type="number" class="amount-input" id="rAmount"
                    placeholder="KES"
                    min="{{ config('pregota.min_amount') }}"
                    max="{{ config('pregota.max_amount') }}">
                <button class="add-btn" id="addBtn" onclick="addToBasket()">Add</button>
            </div>
        </div>

        <div style="margin-top:16px">
            <div class="basket" id="basket">
                <div class="empty-basket" id="emptyBasket">Search for a creator above to get started.</div>
            </div>
        </div>
        <div class="limit-hint" id="limitHint" style="margin-top:12px"></div>
    </div>

    <!-- Step 2: Fee summary + pay -->
    <div class="card" id="summaryCard" style="display:none">
        <div class="card-label">Step 2 — Review & Pay</div>

        <div id="summaryRows"></div>

        <div style="margin-top:16px">
            <label>Your M-Pesa Number</label>
            <input type="tel" id="senderPhone" placeholder="07XX XXX XXX">
        </div>

        <button class="submit-btn" id="submitBtn" onclick="sendMultiGift()" style="margin-top:14px" disabled>
            Pay via M-Pesa →
        </button>
    </div>

    <!-- Step 3: Status / distribution progress -->
    <div class="card" id="statusCard" style="display:none">
        <div class="status-panel show" id="statusPanel">
            <div class="spin" id="statusSpin"></div>
            <div style="font-size:18px;font-weight:900;margin-bottom:6px" id="statusTitle">Waiting for M-Pesa…</div>
            <div style="font-size:13px;color:rgba(255,255,255,.72);margin-bottom:4px" id="statusSub">Check your phone and enter your M-Pesa PIN.</div>
            <div class="dist-list" id="distList"></div>
            <button onclick="resetAll()" style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:10px;padding:10px 22px;color:rgba(255,255,255,.72);cursor:pointer;font-size:13px;font-weight:600;margin-top:8px" id="resetBtn" style="display:none">Send Another →</button>
        </div>
    </div>

</div>

<script>
const CSRF    = document.querySelector('meta[name=csrf-token]').content;
const fmt     = n => 'KES ' + Number(n).toLocaleString('en-KE');
const FEE_PER = 30;  // per-creator B2C buffer
const FEE_PCT = {{ config('pregota.fee_in_pct') }};
const FEE_MIN = {{ config('pregota.fee_min_kes') }};
const MIN_AMT = {{ config('pregota.min_amount') }};
const MAX_AMT = {{ config('pregota.max_amount') }};
const MAX_CREATORS = 5;

let basket    = [];    // [{creator_id, handle, display_name, amount, photo_url}]
let found     = null;  // current search result
let searching = false;
let searchTimer = null;
let currentRef  = null;

// ── Search ────────────────────────────────────────────────────────────────
const handleInput = document.getElementById('handleInput');
handleInput.addEventListener('input', function() {
    clearTimeout(searchTimer);
    const val = this.value.trim().replace(/^@/, '');
    document.getElementById('searchResult').classList.remove('show');
    document.getElementById('searchErr').style.display = 'none';
    found = null;
    if (val.length < 2) return;
    searchTimer = setTimeout(() => lookupCreator(val), 400);
});

async function lookupCreator(handle) {
    if (searching) return;
    searching = true;
    try {
        const res  = await fetch(`/gift/multi-creator/search?handle=${encodeURIComponent(handle)}`);
        const data = await res.json();
        const errEl = document.getElementById('searchErr');

        if (!data.found) {
            errEl.textContent = 'No creator found with that handle.';
            errEl.style.display = 'block';
            return;
        }

        // Already in basket?
        if (basket.find(b => b.creator_id === data.creator_id)) {
            errEl.textContent = data.display_name + ' is already in your list.';
            errEl.style.display = 'block';
            return;
        }

        found = data;
        document.getElementById('rName').textContent   = data.display_name;
        document.getElementById('rHandle').textContent = '@' + data.handle;
        document.getElementById('rAmount').min         = data.min_gift;
        document.getElementById('rAmount').placeholder = `Min ${fmt(data.min_gift)}`;
        document.getElementById('rAmount').value       = '';
        const av = document.getElementById('rAvatar');
        if (data.photo_url) {
            av.innerHTML = `<img src="${data.photo_url}" alt="">`;
        } else {
            av.textContent = data.display_name.charAt(0).toUpperCase();
        }
        document.getElementById('searchResult').classList.add('show');
        document.getElementById('addBtn').disabled = basket.length >= MAX_CREATORS;
    } catch(e) {
        document.getElementById('searchErr').textContent = 'Search failed. Please try again.';
        document.getElementById('searchErr').style.display = 'block';
    } finally {
        searching = false;
    }
}

function addToBasket() {
    if (!found || basket.length >= MAX_CREATORS) return;
    const amt = parseInt(document.getElementById('rAmount').value);
    if (!amt || amt < found.min_gift) {
        alert(`Minimum gift for ${found.display_name} is ${fmt(found.min_gift)}.`);
        return;
    }
    if (amt > MAX_AMT) {
        alert(`Maximum gift is ${fmt(MAX_AMT)}.`);
        return;
    }

    basket.push({ ...found, amount: amt });
    document.getElementById('handleInput').value = '';
    document.getElementById('searchResult').classList.remove('show');
    found = null;
    renderBasket();
    renderSummary();
}

// ── Basket rendering ──────────────────────────────────────────────────────
function renderBasket() {
    const el      = document.getElementById('basket');
    const emptyEl = document.getElementById('emptyBasket');
    const hintEl  = document.getElementById('limitHint');

    if (basket.length === 0) {
        el.innerHTML = '';
        el.appendChild(emptyEl);
        emptyEl.style.display = 'block';
        hintEl.textContent = '';
        document.getElementById('summaryCard').style.display = 'none';
        return;
    }

    emptyEl.style.display = 'none';
    el.innerHTML = '';
    basket.forEach((b, idx) => {
        const div = document.createElement('div');
        div.className = 'basket-item';
        div.innerHTML = `
            <div class="basket-avatar">${b.photo_url ? `<img src="${b.photo_url}" style="width:100%;height:100%;object-fit:cover;border-radius:50%">` : b.display_name.charAt(0).toUpperCase()}</div>
            <div class="basket-info">
                <div class="basket-name">${b.display_name}</div>
                <div class="basket-handle">@${b.handle}</div>
            </div>
            <div class="basket-amount">${fmt(b.amount)}</div>
            <button class="remove-btn" onclick="removeFromBasket(${idx})">×</button>
        `;
        el.appendChild(div);
    });

    const remaining = MAX_CREATORS - basket.length;
    hintEl.textContent = remaining > 0
        ? `You can add ${remaining} more creator${remaining > 1 ? 's' : ''}`
        : 'Maximum 5 creators reached';

    document.getElementById('addBtn').disabled = basket.length >= MAX_CREATORS;
}

function removeFromBasket(idx) {
    basket.splice(idx, 1);
    renderBasket();
    renderSummary();
}

// ── Summary ───────────────────────────────────────────────────────────────
function calcFees() {
    const totalPayout  = basket.reduce((s, b) => s + b.amount, 0);
    const feeOutTotal  = FEE_PER * basket.length;
    const feeIn        = Math.max(FEE_MIN, Math.ceil(totalPayout * FEE_PCT / (100 - FEE_PCT)));
    const gross        = totalPayout + feeIn + feeOutTotal;
    return { totalPayout, feeOutTotal, feeIn, gross };
}

function renderSummary() {
    const summaryCard = document.getElementById('summaryCard');
    if (basket.length < 2) {
        summaryCard.style.display = 'none';
        return;
    }
    summaryCard.style.display = 'block';

    const { totalPayout, feeOutTotal, feeIn, gross } = calcFees();
    const rows = document.getElementById('summaryRows');
    rows.innerHTML = `
        <div class="summary-row"><span>Total gifts (${basket.length} creators)</span><span>${fmt(totalPayout)}</span></div>
        <div class="summary-row"><span>B2C delivery fee (${basket.length} × KES ${FEE_PER})</span><span>${fmt(feeOutTotal)}</span></div>
        <div class="summary-row"><span>Platform fee (${FEE_PCT}%)</span><span>${fmt(feeIn)}</span></div>
        <div class="summary-row total"><span>You pay via M-Pesa</span><span>${fmt(gross)}</span></div>
    `;

    validateSubmit();
}

// ── Phone validation ──────────────────────────────────────────────────────
document.getElementById('senderPhone').addEventListener('input', validateSubmit);

function validateSubmit() {
    const phone = document.getElementById('senderPhone').value.trim();
    const valid = /^(\+?254|0)[17]\d{8}$/.test(phone);
    document.getElementById('submitBtn').disabled = !(basket.length >= 2 && valid);
}

// ── Submit ────────────────────────────────────────────────────────────────
async function sendMultiGift() {
    const phone = document.getElementById('senderPhone').value.trim();
    const btn   = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.textContent = 'Sending STK Push…';

    const items = basket.map(b => ({ creator_id: b.creator_id, amount: b.amount }));

    try {
        const res  = await fetch('/gift/multi-creator/initiate', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body:    JSON.stringify({ sender_phone: phone, items }),
        });
        const data = await res.json();

        if (!data.success) {
            alert(data.message || 'Something went wrong. Please try again.');
            btn.disabled = false;
            btn.textContent = 'Pay via M-Pesa →';
            return;
        }

        currentRef = data.reference;
        showStatusCard();
        pollStatus();
    } catch(e) {
        alert('Network error. Please try again.');
        btn.disabled = false;
        btn.textContent = 'Pay via M-Pesa →';
    }
}

// ── Status display ────────────────────────────────────────────────────────
function showStatusCard() {
    document.getElementById('builderCard').style.display  = 'none';
    document.getElementById('summaryCard').style.display  = 'none';
    document.getElementById('statusCard').style.display   = 'block';
    renderDistList(basket.map(b => ({ ...b, b2c_status: 'pending' })));
}

function renderDistList(items) {
    const list = document.getElementById('distList');
    list.innerHTML = items.map(item => {
        const icon   = item.b2c_status === 'sent' ? '✅' : item.b2c_status === 'failed' ? '❌' : '⏳';
        const cls    = item.b2c_status === 'sent' ? 'dst-sent' : item.b2c_status === 'failed' ? 'dst-failed' : 'dst-pending';
        const label  = item.b2c_status === 'sent' ? 'Sent' : item.b2c_status === 'failed' ? 'Failed' : 'Pending';
        return `<div class="dist-item">
            <span class="dist-icon">${icon}</span>
            <div class="dist-info">
                <div class="dist-name">${item.display_name || item.display_name}</div>
                <div class="dist-amount">${fmt(item.amount)}</div>
            </div>
            <span class="dist-status ${cls}">${label}</span>
        </div>`;
    }).join('');
}

async function pollStatus() {
    for (let i = 0; i < 30; i++) {
        await new Promise(r => setTimeout(r, 3000));
        try {
            const res  = await fetch('/gift/multi-creator/status?reference=' + currentRef);
            const data = await res.json();

            renderDistList(data.items);

            if (data.status === 'active' || data.status === 'distributing') {
                document.getElementById('statusTitle').textContent = 'Distributing gifts…';
                document.getElementById('statusSub').textContent   = `${data.distributed} of ${data.total} creators paid.`;
                document.getElementById('statusSpin').style.display = 'block';
            }

            if (data.status === 'complete') {
                document.getElementById('statusSpin').style.display = 'none';
                document.getElementById('statusTitle').textContent  = '🎉 All gifts sent!';
                document.getElementById('statusSub').textContent    = `${data.total} creators received their gifts.`;
                document.getElementById('resetBtn').style.display   = 'inline-block';
                break;
            }

            if (data.status === 'failed') {
                document.getElementById('statusSpin').style.display = 'none';
                document.getElementById('statusTitle').textContent  = '⚠️ Some gifts failed';
                document.getElementById('statusSub').textContent    = 'Payment confirmed but one or more payouts failed. Contact support with reference: ' + currentRef;
                document.getElementById('resetBtn').style.display   = 'inline-block';
                break;
            }
        } catch(e) { /* continue polling */ }
    }
}

function resetAll() {
    basket     = [];
    found      = null;
    currentRef = null;
    document.getElementById('handleInput').value = '';
    document.getElementById('senderPhone').value = '';
    renderBasket();
    renderSummary();
    document.getElementById('statusCard').style.display  = 'none';
    document.getElementById('builderCard').style.display = 'block';
    document.getElementById('statusTitle').textContent   = 'Waiting for M-Pesa…';
    document.getElementById('statusSub').textContent     = 'Check your phone and enter your M-Pesa PIN.';
    document.getElementById('statusSpin').style.display  = 'block';
    document.getElementById('resetBtn').style.display    = 'none';
    document.getElementById('distList').innerHTML        = '';
}
</script>
</body>
</html>
