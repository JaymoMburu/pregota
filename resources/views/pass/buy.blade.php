﻿﻿<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pregota Pass — Unlimited Access</title>
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}input,textarea,select,button{font-family:inherit;font-size:inherit}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh}
.nav{padding:14px 24px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.08);position:sticky;top:0;background:#0B141A;z-index:10}
.logo{font-size:20px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.nav-back{font-size:13px;color:rgba(255,255,255,.72);text-decoration:none}
.nav-back:hover{color:rgba(255,255,255,.7)}

.wrap{max-width:500px;margin:0 auto;padding:40px 20px 80px}

.hero{text-align:center;margin-bottom:36px}
.hero-badge{display:inline-flex;align-items:center;gap:7px;background:rgba(37,211,102,.1);border:1px solid rgba(37,211,102,.25);border-radius:20px;padding:5px 14px;font-size:11px;font-weight:700;color:#4ADE80;margin-bottom:16px;letter-spacing:.05em}
.hero h1{font-size:28px;font-weight:900;margin-bottom:10px;line-height:1.15}
.hero p{font-size:14px;color:rgba(255,255,255,.78);line-height:1.7;max-width:380px;margin:0 auto}

/* Active pass banner */
.active-banner{background:rgba(37,211,102,.07);border:1px solid rgba(37,211,102,.2);border-radius:14px;padding:16px 20px;margin-bottom:28px;display:flex;align-items:center;gap:14px}
.active-icon{font-size:28px}
.active-label{font-size:13px;font-weight:700;color:#4ADE80}
.active-sub{font-size:12px;color:rgba(255,255,255,.72);margin-top:2px}

/* Pass cards */
.pass-cards{display:flex;flex-direction:column;gap:12px;margin-bottom:28px}
.pass-card{background:rgba(255,255,255,.04);border:2px solid rgba(255,255,255,.08);border-radius:16px;padding:20px;cursor:pointer;transition:.15s;position:relative}
.pass-card:hover{border-color:rgba(37,211,102,.3);background:rgba(37,211,102,.04)}
.pass-card.selected{border-color:#25D366;background:rgba(37,211,102,.06)}
.pass-card.recommended{border-color:rgba(37,211,102,.35)}
.rec-badge{position:absolute;top:-10px;left:20px;background:linear-gradient(135deg,#25D366,#4ADE80);color:#0B141A;font-size:10px;font-weight:900;padding:3px 10px;border-radius:999px;letter-spacing:.05em}
.pass-top{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:8px}
.pass-label{font-size:18px;font-weight:900}
.pass-price{font-size:22px;font-weight:900;color:#4ADE80}
.pass-price span{font-size:13px;font-weight:400;color:rgba(255,255,255,.72)}
.pass-perks{display:flex;flex-direction:column;gap:5px}
.pass-perk{font-size:12px;color:rgba(255,255,255,.78);display:flex;align-items:center;gap:7px}
.pass-perk::before{content:'✓';color:#4ADE80;font-weight:700;font-size:11px}

/* Phone + buy */
.buy-section{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:16px;padding:20px}
.buy-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.65);margin-bottom:14px}
.field{margin-bottom:14px}
.field label{display:block;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.72);margin-bottom:7px}
.field input{width:100%;padding:13px 14px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:11px;color:#fff;font-size:15px;outline:none;font-family:inherit;transition:.15s}
.field input:focus{border-color:rgba(37,211,102,.4)}

.buy-btn{width:100%;padding:15px;background:linear-gradient(135deg,#25D366,#1aaa52);color:#fff;font-size:16px;font-weight:900;border:none;border-radius:13px;cursor:pointer;transition:.2s}
.buy-btn:hover{transform:translateY(-1px);box-shadow:0 8px 24px rgba(37,211,102,.3)}
.buy-btn:disabled{opacity:.45;cursor:not-allowed;transform:none;box-shadow:none}

.err{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);border-radius:9px;padding:10px 14px;font-size:13px;color:#fca5a5;margin-top:12px;display:none}

/* Pending / confirmed states */
.pending-state,.confirmed-state{display:none;text-align:center;padding:20px 0}
.spinner{width:44px;height:44px;border:3px solid rgba(255,255,255,.1);border-top-color:#25D366;border-radius:50%;animation:spin .8s linear infinite;margin:0 auto 16px}
@keyframes spin{to{transform:rotate(360deg)}}

/* Comparison strip */
.compare{margin-top:32px;background:rgba(255,255,255,.02);border:1px solid rgba(255,255,255,.06);border-radius:14px;padding:18px}
.compare-title{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.65);margin-bottom:14px}
.compare-row{display:flex;justify-content:space-between;align-items:center;font-size:13px;padding:7px 0;border-bottom:1px solid rgba(255,255,255,.04)}
.compare-row:last-child{border:none}
.compare-row span:first-child{color:rgba(255,255,255,.78)}
.compare-row span:last-child{font-weight:700;color:#4ADE80}
</style>
</head>
<body>

<nav class="nav">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    <a href="javascript:history.back()" class="nav-back">← Back</a>
</nav>

<div class="wrap">

    <div class="hero">
        <div class="hero-badge">📶 Pregota Pass</div>
        <h1>Unlimited access.<br>One flat price.</h1>
        <p>Like buying a data bundle — pay once and use Pregota freely all day, week, or month. Every login still requires your M-Pesa PIN for privacy.</p>
    </div>

    @if($activePass)
    <div class="active-banner">
        <div class="active-icon">✅</div>
        <div>
            <div class="active-label">You have an active {{ ucfirst($activePass->pass_type) }} Pass</div>
            <div class="active-sub">Expires {{ $activePass->expires_at->format('d M Y, H:i') }} · {{ $activePass->daysRemaining() }} day(s) remaining</div>
        </div>
    </div>
    @endif

    <div class="pass-cards" id="pass-cards">
        @foreach($passes as $key => $pass)
        <div class="pass-card {{ $key === 'weekly' ? 'recommended' : '' }}" id="card-{{ $key }}" onclick="selectPass('{{ $key }}')">
            @if($key === 'weekly')<div class="rec-badge">BEST VALUE</div>@endif
            <div class="pass-top">
                <div class="pass-label">{{ $pass['label'] }}</div>
                <div class="pass-price">KES {{ $pass['price'] }}<span> / {{ $pass['days'] === 1 ? 'day' : $pass['days'] . ' days' }}</span></div>
            </div>
            <div class="pass-perks">
                <div class="pass-perk">Unlimited dashboard logins for {{ $pass['days'] === 1 ? '24 hours' : $pass['days'] . ' days' }}</div>
                <div class="pass-perk">STK Push stays on — your M-Pesa PIN is still your key</div>
                <div class="pass-perk">Works on all Pregota dashboards (seller, deni, spending)</div>
                @if($key === 'monthly')
                <div class="pass-perk">Best for vibanda, boda boda, and daily users</div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <div class="buy-section">
        <div class="buy-label" id="buy-label">Select a pass above</div>
        <div id="buy-form">
            <div class="field">
                <label>Your M-Pesa Number</label>
                <input type="tel" id="buy-phone" placeholder="0712 345 678" autocomplete="tel">
            </div>
            <div class="err" id="err-msg"></div>
            <button class="buy-btn" id="buy-btn" onclick="doBuy()" disabled>Choose a pass to continue</button>
        </div>

        <div class="pending-state" id="pending-state">
            <div class="spinner"></div>
            <div style="font-size:15px;font-weight:700;margin-bottom:6px">M-Pesa prompt sent</div>
            <div style="font-size:13px;color:rgba(255,255,255,.72)">Enter your PIN to activate your pass</div>
        </div>

        <div class="confirmed-state" id="confirmed-state">
            <div style="font-size:48px;margin-bottom:12px">🎉</div>
            <div style="font-size:22px;font-weight:900;color:#4ADE80;margin-bottom:6px">Pass activated!</div>
            <div id="pass-msg" style="font-size:14px;color:rgba(255,255,255,.78)"></div>
        </div>
    </div>

    <div class="compare">
        <div class="compare-title">Without a pass vs with a pass</div>
        <div class="compare-row"><span>Check dashboard 5× a day</span><span>KES 10 → KES 15 (Day Pass)</span></div>
        <div class="compare-row"><span>Check dashboard daily for a week</span><span>KES 70+ → KES 50 (Week Pass)</span></div>
        <div class="compare-row"><span>Vibanda owner, daily use</span><span>KES 300+ → KES 150 (Month Pass)</span></div>
        <div class="compare-row"><span>STK Push privacy on every login</span><span>✓ Always active</span></div>
    </div>

</div>

<script>
let selectedPass = null;
let checkoutId   = null;
const PASSES     = @json(config('pregota.passes'));

function selectPass(key) {
    document.querySelectorAll('.pass-card').forEach(c => c.classList.remove('selected'));
    document.getElementById('card-' + key).classList.add('selected');
    selectedPass = key;
    const p = PASSES[key];
    document.getElementById('buy-label').textContent = p.label + ' — KES ' + p.price;
    const btn = document.getElementById('buy-btn');
    btn.disabled = false;
    btn.textContent = 'Buy ' + p.label + ' — KES ' + p.price + ' via M-Pesa →';
}

async function doBuy() {
    const phone  = document.getElementById('buy-phone').value.trim();
    const errEl  = document.getElementById('err-msg');
    errEl.style.display = 'none';

    if (!selectedPass) { errEl.textContent = 'Select a pass first.'; errEl.style.display = 'block'; return; }
    if (!phone || !/^(\+?254|0)[17]\d{8}$/.test(phone)) {
        errEl.textContent = 'Enter a valid Safaricom number.'; errEl.style.display = 'block'; return;
    }

    document.getElementById('buy-btn').disabled = true;

    const res  = await fetch('{{ route('pass.purchase') }}', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        body: JSON.stringify({ phone, pass_type: selectedPass }),
    });
    const data = await res.json();

    if (data.already_active) {
        errEl.textContent = 'You already have an active ' + data.pass_type + ' pass — expires ' + data.expires_at + '.';
        errEl.style.display = 'block';
        document.getElementById('buy-btn').disabled = false;
        return;
    }

    if (!res.ok) {
        errEl.textContent = data.message || 'Something went wrong.'; errEl.style.display = 'block';
        document.getElementById('buy-btn').disabled = false; return;
    }

    checkoutId = data.checkout_request_id;
    document.getElementById('buy-form').style.display = 'none';
    document.getElementById('pending-state').style.display = 'block';
    poll();
}

function poll() {
    fetch('{{ route('pass.poll') }}?checkout_request_id=' + checkoutId)
        .then(r => r.json())
        .then(d => {
            if (d.status === 'active') {
                document.getElementById('pending-state').style.display = 'none';
                document.getElementById('confirmed-state').style.display = 'block';
                document.getElementById('pass-msg').textContent =
                    PASSES[d.pass_type].label + ' active until ' + d.expires_at + '. Enjoy unlimited access.';
            } else if (d.status === 'failed') {
                document.getElementById('pending-state').style.display = 'none';
                document.getElementById('buy-form').style.display = 'block';
                document.getElementById('err-msg').textContent = 'Payment failed. Please try again.';
                document.getElementById('err-msg').style.display = 'block';
                document.getElementById('buy-btn').disabled = false;
            } else {
                setTimeout(poll, 2500);
            }
        })
        .catch(() => setTimeout(poll, 3000));
}
</script>
</body>
</html>
