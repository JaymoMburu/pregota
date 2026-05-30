﻿﻿<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pay {{ $payLink->business_name }} — Pregota</title>
<meta name="description" content="Pay {{ $payLink->business_name }} via M-Pesa. Instant STK Push — no app needed.">
@include('partials.pwa')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800;900&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0}input,textarea,select,button{font-family:inherit;font-size:inherit}
body{font-family:'Plus Jakarta Sans',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh;display:flex;flex-direction:column;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}
.nav{padding:14px 20px;display:flex;align-items:center;border-bottom:1px solid rgba(255,255,255,.07)}
.logo{font-size:18px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.wrap{flex:1;display:flex;align-items:center;justify-content:center;padding:32px 20px}
.card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.09);border-radius:24px;padding:36px 32px;width:100%;max-width:420px}

/* Business header */
.biz-icon{width:56px;height:56px;background:linear-gradient(135deg,#25D366,#1aaa52);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:24px;margin-bottom:14px}
.biz-name{font-size:20px;font-weight:900;margin-bottom:4px}
.biz-cat{font-size:11px;font-weight:700;color:#25D366;text-transform:uppercase;letter-spacing:.08em;margin-bottom:12px}

/* Route + fare card (shown when conductor has set it) */
.route-card{background:linear-gradient(135deg,rgba(37,211,102,.14),rgba(26,170,82,.06));border:1px solid rgba(37,211,102,.3);border-radius:16px;padding:18px 20px;margin-bottom:24px}
.route-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#25D366;margin-bottom:8px}
.route-name{font-size:18px;font-weight:900;margin-bottom:8px}
.fare-big{font-size:38px;font-weight:900;color:#25D366;line-height:1}
.fare-label{font-size:12px;color:rgba(255,255,255,.78);margin-top:4px}
.fare-locked{font-size:11px;color:rgba(255,255,255,.72);margin-top:6px;display:flex;align-items:center;gap:4px}

/* No route set — open entry */
.no-route-note{font-size:12px;color:rgba(255,255,255,.78);background:rgba(255,255,255,.04);border-radius:8px;padding:10px 12px;margin-bottom:18px}

.form-group{margin-bottom:18px}
label{display:block;font-size:13px;font-weight:700;color:rgba(255,255,255,.8);margin-bottom:6px}
input{width:100%;padding:13px 14px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);border-radius:10px;color:#fff;font-size:15px;outline:none;transition:.15s;font-family:inherit}
input:focus{border-color:rgba(37,211,102,.5);background:rgba(255,255,255,.08)}
.hint{font-size:11px;color:rgba(255,255,255,.72);margin-top:5px}

.security-note{display:flex;align-items:center;gap:8px;font-size:12px;color:rgba(255,255,255,.78);background:rgba(255,255,255,.04);border-radius:8px;padding:10px 12px;margin-bottom:20px}

/* Tip section */
.tip-section{border-top:1px solid rgba(255,255,255,.07);margin:20px 0 18px;padding-top:18px}
.tip-toggle{display:flex;align-items:center;justify-content:space-between;cursor:pointer;user-select:none}
.tip-toggle-label{font-size:14px;font-weight:700;color:rgba(255,255,255,.85)}
.tip-toggle-sub{font-size:11px;color:rgba(255,255,255,.72);margin-top:2px}
.tip-chevron{font-size:18px;color:rgba(255,255,255,.72);transition:.2s}
.tip-body{display:none;margin-top:16px}
.tip-body.open{display:block}
.tip-amounts{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:14px}
.tip-btn{padding:9px 16px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:10px;color:rgba(255,255,255,.8);font-size:14px;font-weight:700;cursor:pointer;transition:.15s}
.tip-btn:hover{background:rgba(255,255,255,.1)}
.tip-btn.selected{background:rgba(37,211,102,.15);border-color:rgba(37,211,102,.4);color:#25D366}
.tip-custom{margin-bottom:14px}
.tip-recipient-row{display:flex;gap:8px;margin-bottom:14px}
.tip-who{flex:1;padding:10px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.08);border-radius:10px;font-size:13px;font-weight:700;color:rgba(255,255,255,.65);cursor:pointer;text-align:center;transition:.15s}
.tip-who:hover{background:rgba(255,255,255,.09)}
.tip-who.selected{background:rgba(37,211,102,.12);border-color:rgba(37,211,102,.3);color:#25D366}
.tip-total{background:rgba(37,211,102,.07);border:1px solid rgba(37,211,102,.18);border-radius:10px;padding:10px 14px;font-size:13px;color:rgba(255,255,255,.75);display:none;margin-bottom:14px}
.tip-total strong{color:#25D366;font-size:15px}

.btn{width:100%;padding:15px;background:linear-gradient(135deg,#25D366,#1aaa52);color:#fff;font-size:16px;font-weight:800;border:none;border-radius:12px;cursor:pointer;transition:.2s;display:flex;align-items:center;justify-content:center;gap:8px}
.btn:hover{transform:translateY(-1px);box-shadow:0 8px 24px rgba(37,211,102,.3)}
.btn:disabled{opacity:.6;cursor:not-allowed;transform:none;box-shadow:none}

/* Status states */
.status-box{display:none;border-radius:16px;padding:32px 24px;text-align:center}
.status-box.visible{display:block}
.status-box.waiting{background:rgba(251,191,36,.08);border:1px solid rgba(251,191,36,.2)}
.status-box.success{background:rgba(34,197,94,.06);border:1px solid rgba(34,197,94,.2)}
.status-box.failed{background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2)}

.powered{text-align:center;font-size:11px;color:rgba(255,255,255,.65);margin-top:20px}
.powered a{color:rgba(255,255,255,.72);text-decoration:none}

/* Route changed warning */
.route-changed{display:none;background:rgba(251,191,36,.12);border:1px solid rgba(251,191,36,.25);border-radius:10px;padding:10px 14px;font-size:12px;color:#fbbf24;margin-bottom:16px}
.route-changed.visible{display:block}

/* Fare stage buttons */
.fare-stages-wrap{margin-bottom:20px}
.fare-stages-label{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.78);margin-bottom:10px}
.fare-btns{display:flex;gap:8px;flex-wrap:wrap}
.fare-btn{padding:10px 16px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);border-radius:12px;color:#fff;font-size:13px;font-weight:700;cursor:pointer;transition:.15s;text-align:center;line-height:1.4;min-width:90px}
.fare-btn:hover{background:rgba(37,211,102,.1);border-color:rgba(37,211,102,.3)}
.fare-btn.selected{background:rgba(37,211,102,.15);border-color:rgba(37,211,102,.5);color:#4ADE80}
.fare-btn-amount{display:block;font-size:17px;font-weight:900;color:#4ADE80;margin-top:3px}
.fare-btn.selected .fare-btn-amount{color:#25D366}
.change-stop{font-size:11px;color:rgba(255,255,255,.72);cursor:pointer;margin-top:6px;display:inline-block}
.change-stop:hover{color:#25D366}

/* Social proof */
.social-proof{font-size:12px;color:rgba(255,255,255,.72);margin-bottom:16px;display:flex;align-items:center;gap:6px}
.social-proof strong{color:rgba(255,255,255,.75)}

/* Stamp card progress */
.stamp-bar{display:none;background:rgba(37,211,102,.06);border:1px solid rgba(37,211,102,.18);border-radius:12px;padding:12px 14px;margin-bottom:16px}
.stamp-bar.visible{display:block}
.stamp-bar-top{display:flex;justify-content:space-between;align-items:center;margin-bottom:8px}
.stamp-bar-label{font-size:12px;font-weight:700;color:#4ADE80}
.stamp-bar-count{font-size:12px;color:rgba(255,255,255,.78)}
.stamp-dots{display:flex;gap:4px;flex-wrap:wrap}
.sd{width:18px;height:18px;border-radius:50%;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.12);font-size:9px;display:flex;align-items:center;justify-content:center}
.sd.on{background:rgba(37,211,102,.25);border-color:#25D366;color:#4ADE80}
.stamp-reward{font-size:11px;color:rgba(255,255,255,.78);margin-top:6px}
.stamp-reward-ready{font-size:12px;font-weight:700;color:#fbbf24;margin-top:6px}
</style>
</head>
<body>
<nav class="nav">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
</nav>
<div class="wrap">
    <div class="card">

        {{-- Business header --}}
        <div class="biz-icon">
            {{ $payLink->category === 'transport' ? '🚐' : '🛍️' }}
        </div>
        <div class="biz-name">{{ $payLink->business_name }}</div>
        @if($payLink->category === 'transport')
        <div class="biz-cat" style="font-size:13px;letter-spacing:.12em;color:#4ADE80">{{ $payLink->displayIdentifier() }}</div>
        @elseif($payLink->category)
        <div class="biz-cat">{{ ucfirst($payLink->category) }}</div>
        @endif

        {{-- Social proof --}}
        @if($payLink->payment_count > 0)
        <div class="social-proof">
            <span>✅</span>
            <span><strong>{{ number_format($payLink->payment_count) }}</strong> {{ $payLink->payment_count === 1 ? 'person has' : 'people have' }} paid here</span>
        </div>
        @endif

        {{-- Stamp card progress (populated via JS after phone blur) --}}
        @if($payLink->stamps_required)
        <div class="stamp-bar" id="stamp-bar">
            <div class="stamp-bar-top">
                <div class="stamp-bar-label">🎟 Your Stamps</div>
                <div class="stamp-bar-count" id="stamp-count-label">Enter your number to see your progress</div>
            </div>
            <div class="stamp-dots" id="stamp-dots"></div>
            <div class="stamp-reward" id="stamp-reward-text"></div>
            <div class="stamp-reward-ready" id="stamp-reward-ready" style="display:none">🎉 Reward ready — show to seller after payment!</div>
        </div>
        @endif

        <div id="form-section">

            {{-- Route changed warning (shown if conductor updates mid-session) --}}
            <div class="route-changed" id="route-changed">
                ⚠️ Route or fare just changed — page updated. Please check the new fare before paying.
            </div>

            {{-- Route + fare card (shown when conductor has set current route) --}}
            @if($payLink->current_route && $payLink->current_fare)
            <div class="route-card" id="route-card">
                <div class="route-label">Current Route</div>
                <div class="route-name" id="route-name">{{ $payLink->current_route }}</div>
                <div class="fare-big" id="fare-display">KES {{ number_format($payLink->current_fare) }}</div>
                <div class="fare-label">fare per passenger</div>
                <div class="fare-locked">🔒 Set by conductor · cannot be changed</div>
            </div>
            @elseif($payLink->fixed_amount && $payLink->default_amount)
            <div class="route-card" id="route-card">
                <div class="route-label">Fixed Fare</div>
                <div class="fare-big">KES {{ number_format($payLink->default_amount) }}</div>
                <div class="fare-locked">🔒 Fixed amount</div>
            </div>
            @elseif($tillAmount)
            <div class="route-card" id="route-card">
                <div class="route-label">Amount</div>
                <div class="fare-big">KES {{ number_format($tillAmount) }}</div>
                <div class="fare-locked">🔒 Set by cashier</div>
            </div>
            @elseif($fares->isNotEmpty())
            {{-- Fare stage buttons — passenger taps their stop --}}
            <div class="fare-stages-wrap" id="fare-stages">
                <div class="fare-stages-label">Select your stop</div>
                <div class="fare-btns" id="fare-btns">
                    @foreach($fares as $fare)
                    <button class="fare-btn"
                            data-amount="{{ $fare->amount }}"
                            onclick="selectFare(this, {{ $fare->amount }}, '{{ addslashes($fare->label) }}')">
                        {{ $fare->label }}
                        <span class="fare-btn-amount">KES {{ number_format($fare->amount) }}</span>
                    </button>
                    @endforeach
                </div>
                <div id="fare-selected-card" style="display:none">
                    <div class="route-card">
                        <div class="route-label">Your Fare</div>
                        <div class="route-name" id="fare-selected-label"></div>
                        <div class="fare-big" id="fare-selected-amount"></div>
                        <div class="fare-label">per passenger</div>
                    </div>
                    <span class="change-stop" onclick="clearFare()">✏️ Change stop</span>
                </div>
            </div>
            @else
            <div class="no-route-note" id="no-route-note">
                Ask the conductor for the fare, then enter the amount below.
            </div>
            <div class="form-group" id="amount-group">
                <label>Amount (KES)</label>
                <input type="number" id="amount" placeholder="Enter amount" min="10" max="150000" autocomplete="off">
                <div class="hint">Minimum KES 10</div>
            </div>
            @endif

            <div class="form-group">
                <label>Your M-Pesa number</label>
                <input type="tel" id="phone" placeholder="0712 345 678" autocomplete="tel">
                <div class="hint">You'll get an M-Pesa prompt — enter your PIN to pay</div>
            </div>

            {{-- Optional tip — transport only --}}
            @if($payLink->category === 'transport')
            <div class="tip-section">
                <div class="tip-toggle" onclick="toggleTip()">
                    <div>
                        <div class="tip-toggle-label">🙏 Add a tip</div>
                        <div class="tip-toggle-sub">Optional · fee-free · goes directly to the crew</div>
                    </div>
                    <div class="tip-chevron" id="tip-chevron">›</div>
                </div>

                <div class="tip-body" id="tip-body">
                    <div class="tip-amounts">
                        <button class="tip-btn" onclick="selectTip(10)">+10</button>
                        <button class="tip-btn" onclick="selectTip(20)">+20</button>
                        <button class="tip-btn" onclick="selectTip(50)">+50</button>
                        <button class="tip-btn" onclick="selectTip(100)">+100</button>
                        <button class="tip-btn" id="tip-custom-btn" onclick="selectTip('custom')">Other</button>
                    </div>
                    <div class="tip-custom" id="tip-custom-wrap" style="display:none">
                        <input type="number" id="tip-custom-input" placeholder="Enter tip amount (KES)" min="1" max="5000" oninput="onCustomTip()">
                    </div>
                    <div class="tip-recipient-row">
                        <div class="tip-who" id="tip-conductor" onclick="selectRecipient('conductor')">👤 Conductor</div>
                        <div class="tip-who" id="tip-driver" onclick="selectRecipient('driver')">🚐 Driver</div>
                    </div>
                    <input type="text" id="tip-comment" placeholder="Leave a message… (optional)" maxlength="200" style="margin-bottom:14px">
                    <div class="tip-total" id="tip-total"></div>
                </div>
            </div>
            @endif

            <div class="security-note">
                🔒 <span>Your number is never shared with the seller. Secured by Pregota.</span>
            </div>

            <button class="btn" id="pay-btn" onclick="initiatePay()">
                <span id="btn-text">Pay via M-Pesa →</span>
            </button>
        </div>

        {{-- Waiting for PIN --}}
        <div class="status-box waiting" id="status-waiting">
            <div style="font-size:48px;margin-bottom:12px">📱</div>
            <div style="font-size:20px;font-weight:900;margin-bottom:8px">Check your phone</div>
            <div style="font-size:13px;color:rgba(255,255,255,.65);line-height:1.6">M-Pesa prompt sent to your number.<br>Enter your PIN to complete payment.</div>
        </div>

        {{-- Success receipt --}}
        <div class="status-box success" id="status-success" style="padding:28px 20px">
            {{-- Big tick --}}
            <div style="font-size:72px;line-height:1;margin-bottom:8px">✅</div>

            {{-- Amount --}}
            <div id="receipt-amount" style="font-size:48px;font-weight:900;color:#fff;line-height:1;margin-bottom:8px"></div>

            {{-- Route --}}
            <div id="receipt-route" style="font-size:16px;font-weight:800;color:#25D366;margin-bottom:6px"></div>

            {{-- Business + plate --}}
            <div style="font-size:13px;color:rgba(255,255,255,.6);margin-bottom:6px">
                paid to <strong style="color:#fff">{{ $payLink->business_name }}</strong>@if($payLink->category === 'transport') <strong style="color:rgba(255,255,255,.8)">· {{ $payLink->displayIdentifier() }}</strong>@endif
            </div>

            {{-- Timestamp — prominent --}}
            <div id="receipt-time" style="font-size:15px;font-weight:700;color:rgba(255,255,255,.75);margin-bottom:20px"></div>

            {{-- Show-to-seller prompt (always) --}}
            <div style="background:rgba(37,211,102,.12);border:2px solid rgba(37,211,102,.4);border-radius:12px;padding:14px 16px;margin-bottom:10px">
                <div style="font-size:17px;font-weight:900;color:#4ade80;margin-bottom:3px">✓ Show this to the seller</div>
                <div style="font-size:11px;color:rgba(255,255,255,.78)">Your number was never shared</div>
            </div>

            {{-- Show-to-conductor prompt (transport only) --}}
            @if($payLink->category === 'transport')
            <div style="background:rgba(37,211,102,.08);border:1px solid rgba(37,211,102,.25);border-radius:12px;padding:14px 16px;margin-bottom:14px">
                <div style="font-size:15px;font-weight:900;color:#4ade80;margin-bottom:3px">🎫 Show this to the conductor</div>
                <div style="font-size:11px;color:rgba(255,255,255,.78)">Proof of fare payment for this trip</div>
            </div>
            @else
            <div style="margin-bottom:14px"></div>
            @endif

            {{-- Stamp card progress update --}}
            <div id="receipt-stamp-info"></div>

            {{-- Receipt link (shown once receipt_number arrives) --}}
            <div id="receipt-link-box" style="display:none;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.12);border-radius:12px;padding:14px 16px;text-align:center">
                <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.72);margin-bottom:8px">Save your receipt</div>
                <a id="receipt-link" href="#" target="_blank" style="display:inline-block;padding:9px 20px;background:rgba(255,255,255,.09);border:1px solid rgba(255,255,255,.18);border-radius:8px;font-size:13px;font-weight:700;color:#fff;text-decoration:none;margin-bottom:8px">🧾 Open Receipt</a>
                <div style="font-size:11px;color:rgba(255,255,255,.72)">Valid for KRA expense records · printable PDF</div>
            </div>
        </div>

        {{-- Failed --}}
        <div class="status-box failed" id="status-failed">
            <div style="font-size:48px;margin-bottom:12px">❌</div>
            <div style="font-size:20px;font-weight:900;margin-bottom:8px">Payment failed</div>
            <div style="font-size:13px;color:rgba(255,255,255,.65);margin-bottom:20px">The payment was not completed. Please try again.</div>
            <button class="btn" onclick="resetForm()">Try Again</button>
        </div>

        <div class="powered">Powered by <a href="{{ route('home') }}">Pregota</a> · M-Pesa STK Push</div>
    </div>
</div>

<script>
let paymentId    = null;
let pollTimer    = null;
let pollCount    = 0;
let routePollTimer = null;

// Track current conductor-set fare
let conductorFare  = {{ $payLink->current_fare ?? 'null' }};
let conductorRoute = {{ $payLink->current_route ? json_encode($payLink->current_route) : 'null' }};
const isTransport  = {{ $payLink->category === 'transport' ? 'true' : 'false' }};
const hasFareStages = {{ $fares->isNotEmpty() ? 'true' : 'false' }};

let selectedFareAmount = 0;

function selectFare(btn, amount, label) {
    document.querySelectorAll('.fare-btn').forEach(b => b.classList.remove('selected'));
    btn.classList.add('selected');
    selectedFareAmount = amount;
    document.getElementById('fare-selected-label').textContent = label;
    document.getElementById('fare-selected-amount').textContent = 'KES ' + amount.toLocaleString();
    document.getElementById('fare-selected-card').style.display = 'block';
    document.getElementById('fare-btns').style.display = 'none';
    updateTipTotal();
}

function clearFare() {
    selectedFareAmount = 0;
    document.querySelectorAll('.fare-btn').forEach(b => b.classList.remove('selected'));
    document.getElementById('fare-selected-card').style.display = 'none';
    document.getElementById('fare-btns').style.display = 'flex';
    updateTipTotal();
}

// ── Tip logic ─────────────────────────────────────────────────────────────
let selectedTip       = 0;
let selectedRecipient = null;
let tipOpen           = false;

function toggleTip() {
    tipOpen = !tipOpen;
    document.getElementById('tip-body').classList.toggle('open', tipOpen);
    document.getElementById('tip-chevron').style.transform = tipOpen ? 'rotate(90deg)' : '';
}

function selectTip(val) {
    document.querySelectorAll('.tip-btn').forEach(b => b.classList.remove('selected'));
    const customWrap = document.getElementById('tip-custom-wrap');

    if (val === 'custom') {
        document.getElementById('tip-custom-btn').classList.add('selected');
        customWrap.style.display = 'block';
        document.getElementById('tip-custom-input').focus();
        selectedTip = parseInt(document.getElementById('tip-custom-input').value) || 0;
    } else {
        customWrap.style.display = 'none';
        selectedTip = val;
        // highlight the right button
        document.querySelectorAll('.tip-btn').forEach(b => {
            if (b.textContent === '+' + val) b.classList.add('selected');
        });
    }
    updateTipTotal();
}

function onCustomTip() {
    selectedTip = parseInt(document.getElementById('tip-custom-input').value) || 0;
    updateTipTotal();
}

function selectRecipient(who) {
    selectedRecipient = who;
    document.getElementById('tip-conductor').classList.toggle('selected', who === 'conductor');
    document.getElementById('tip-driver').classList.toggle('selected', who === 'driver');
}

function updateTipTotal() {
    const totalEl = document.getElementById('tip-total');
    const fare    = getAmount();
    if (selectedTip > 0) {
        totalEl.style.display = 'block';
        totalEl.innerHTML = `Fare <strong>KES ${fare.toLocaleString()}</strong> + Tip <strong style="color:#fbbf24">KES ${selectedTip.toLocaleString()}</strong> = Total <strong>KES ${(fare + selectedTip).toLocaleString()}</strong>`;
    } else {
        totalEl.style.display = 'none';
    }
}

function getAmount() {
    if (conductorFare) return conductorFare;
    @if($payLink->fixed_amount && $payLink->default_amount)
    return {{ (int) $payLink->default_amount }};
    @elseif($tillAmount)
    return {{ $tillAmount }};
    @else
    if (hasFareStages) return selectedFareAmount;
    return parseInt(document.getElementById('amount')?.value || '0');
    @endif
}

@if($payLink->stamps_required)
function loadStamps(phone) {
    if (!phone || !/^(\+?254|0)[17]\d{8}$/.test(phone.replace(/\s/g,''))) return;
    fetch('{{ route('seller.stamps', $payLink->handle) }}?phone=' + encodeURIComponent(phone))
        .then(r => r.json())
        .then(d => {
            if (!d.enabled) return;
            const bar   = document.getElementById('stamp-bar');
            const dots  = document.getElementById('stamp-dots');
            const label = document.getElementById('stamp-count-label');
            const rewardText = document.getElementById('stamp-reward-text');
            const rewardReady = document.getElementById('stamp-reward-ready');

            const count = d.stamp_count;
            const total = d.stamps_required;
            label.textContent = count + ' / ' + total + ' stamps';
            dots.innerHTML = Array.from({length: total}, (_, i) =>
                `<div class="sd ${i < count ? 'on' : ''}">✓</div>`
            ).join('');
            rewardText.textContent = d.stamps_left > 0
                ? d.stamps_left + ' more payment' + (d.stamps_left > 1 ? 's' : '') + ' for: ' + (d.reward || 'reward')
                : '';
            rewardReady.style.display = d.reward_pending ? 'block' : 'none';
            bar.classList.add('visible');
        });
}
document.getElementById('phone').addEventListener('blur', function() { loadStamps(this.value.trim()); });
@endif

function initiatePay() {
    const phone  = document.getElementById('phone').value.trim();
    const amount = getAmount();

    if (!phone || !/^(\+?254|0)[17]\d{8}$/.test(phone)) {
        alert('Enter a valid Safaricom number (e.g. 0712 345 678).');
        return;
    }

    @if(!$payLink->current_fare && !($payLink->fixed_amount && $payLink->default_amount) && !$tillAmount)
    if (hasFareStages) {
        if (!selectedFareAmount) {
            alert('Please select your stop first.');
            return;
        }
    } else if (!amount || amount < 10) {
        alert('Enter an amount of at least KES 10.');
        return;
    }
    @endif

    const btn = document.getElementById('pay-btn');
    btn.disabled = true;
    document.getElementById('btn-text').textContent = 'Sending M-Pesa prompt…';

    const tipComment = document.getElementById('tip-comment')?.value.trim() || '';

    const body = new URLSearchParams({
        phone,
        _token: '{{ csrf_token() }}'
    });

    // Only send amount if it's not server-determined
    @if(!$payLink->current_fare && !($payLink->fixed_amount && $payLink->default_amount))
    body.append('amount', amount);
    @endif
    @if($tillAmount)
    body.append('amount', {{ $tillAmount }});
    @endif

    if (selectedTip > 0) {
        body.append('tip_amount', selectedTip);
        if (selectedRecipient) body.append('tip_recipient', selectedRecipient);
        if (tipComment) body.append('tip_comment', tipComment);
    }

    fetch('{{ route('seller.pay', $payLink->handle) }}', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: body.toString(),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            paymentId = data.payment_id;
            pollCount = 0;
            stopRoutePoll();
            showState('waiting');
            pollTimer = setInterval(pollStatus, 2500);
        } else {
            btn.disabled = false;
            document.getElementById('btn-text').textContent = 'Pay via M-Pesa →';
            alert(data.message || 'Something went wrong. Please try again.');
        }
    })
    .catch(() => {
        btn.disabled = false;
        document.getElementById('btn-text').textContent = 'Pay via M-Pesa →';
        alert('Network error. Please try again.');
    });
}

function pollStatus() {
    if (!paymentId) return;
    pollCount++;
    // Give up after 2 minutes (48 × 2.5s)
    if (pollCount > 48) {
        clearInterval(pollTimer);
        showState('failed');
        return;
    }
    fetch('{{ route('seller.status') }}?payment_id=' + paymentId)
        .then(r => r.json())
        .then(data => {
            if (data.status === 'confirmed') {
                clearInterval(pollTimer);
                const fare = getAmount();
                const total = fare + selectedTip;
                const amtEl = document.getElementById('receipt-amount');
                if (selectedTip > 0) {
                    amtEl.innerHTML = `KES ${total.toLocaleString()}<div style="font-size:14px;color:rgba(255,255,255,.6);font-weight:600;margin-top:4px">Fare KES ${fare.toLocaleString()} + Tip KES ${selectedTip.toLocaleString()}${selectedRecipient ? ' (' + selectedRecipient + ')' : ''}</div>`;
                } else {
                    amtEl.textContent = 'KES ' + fare.toLocaleString();
                }
                if (conductorRoute) {
                    document.getElementById('receipt-route').textContent = conductorRoute;
                }
                const now = new Date();
                document.getElementById('receipt-time').textContent =
                    now.toLocaleDateString('en-KE', {weekday:'short', day:'numeric', month:'short'})
                    + ' · ' + now.toLocaleTimeString('en-KE', {hour:'2-digit', minute:'2-digit', second:'2-digit'});
                showState('success');
                if (data.receipt_url) {
                    const box = document.getElementById('receipt-link-box');
                    document.getElementById('receipt-link').href = data.receipt_url;
                    box.style.display = 'block';
                }
                @if($payLink->stamps_required)
                if (data.stamp_info) {
                    const s = data.stamp_info;
                    const count = s.stamp_count;
                    const total = s.stamps_required;
                    const dotsHtml = Array.from({length: total}, (_, i) =>
                        `<div class="sd ${i < count ? 'on' : ''}">✓</div>`
                    ).join('');
                    document.getElementById('receipt-stamp-info').innerHTML =
                        `<div style="margin-top:16px;padding:12px 14px;background:rgba(37,211,102,.08);border:1px solid rgba(37,211,102,.2);border-radius:10px">
                            <div style="font-size:12px;font-weight:700;color:#4ADE80;margin-bottom:6px">🎟 ${count} / ${total} stamps</div>
                            <div style="display:flex;gap:4px;flex-wrap:wrap;margin-bottom:6px">${dotsHtml}</div>
                            ${s.reward_pending
                                ? '<div style="font-size:12px;color:#fbbf24;font-weight:700">🎉 Reward unlocked — show to seller!</div>'
                                : `<div style="font-size:11px;color:rgba(255,255,255,.78)">${s.stamps_left} more for: ${s.reward || 'reward'}</div>`}
                        </div>`;
                }
                @endif
            } else if (data.status === 'failed') {
                clearInterval(pollTimer);
                showState('failed');
            }
        });
}

// Poll for route/fare changes from conductor
function pollRoute() {
    fetch('{{ route('seller.current', $payLink->handle) }}')
        .then(r => r.json())
        .then(data => {
            const newFare  = data.current_fare;
            const newRoute = data.current_route;
            const changed  = (newFare !== conductorFare) || (newRoute !== conductorRoute);

            if (changed && (conductorFare !== null || conductorRoute !== null)) {
                // Route/fare changed mid-session — alert passenger
                document.getElementById('route-changed').classList.add('visible');
            }

            conductorFare  = newFare;
            conductorRoute = newRoute;

            // Update displayed values
            const routeCard = document.getElementById('route-card');
            const noRouteNote = document.getElementById('no-route-note');
            const amountGroup = document.getElementById('amount-group');

            const fareStages = document.getElementById('fare-stages');

            if (newFare && newRoute) {
                if (routeCard) {
                    document.getElementById('route-name') && (document.getElementById('route-name').textContent = newRoute);
                    document.getElementById('fare-display') && (document.getElementById('fare-display').textContent = 'KES ' + newFare.toLocaleString());
                } else if (fareStages) {
                    // Conductor override while fare stages were showing — inject route card
                    const card = document.createElement('div');
                    card.id = 'route-card';
                    card.className = 'route-card';
                    card.innerHTML = `<div class="route-label">Current Route</div><div class="route-name" id="route-name">${newRoute}</div><div class="fare-big" id="fare-display">KES ${newFare.toLocaleString()}</div><div class="fare-label">fare per passenger</div><div class="fare-locked">🔒 Set by conductor · cannot be changed</div>`;
                    fareStages.parentNode.insertBefore(card, fareStages);
                }
                if (noRouteNote) noRouteNote.style.display = 'none';
                if (amountGroup) amountGroup.style.display = 'none';
                if (fareStages) fareStages.style.display = 'none';
            } else if (!newFare) {
                if (hasFareStages) {
                    if (fareStages) fareStages.style.display = 'block';
                    if (noRouteNote) noRouteNote.style.display = 'none';
                    if (amountGroup) amountGroup.style.display = 'none';
                } else {
                    if (noRouteNote) noRouteNote.style.display = 'block';
                    if (amountGroup) amountGroup.style.display = 'block';
                }
            }
        })
        .catch(() => {});
}

function stopRoutePoll() {
    if (routePollTimer) clearInterval(routePollTimer);
}

function showState(state) {
    document.getElementById('form-section').style.display = 'none';
    ['waiting','success','failed'].forEach(s => {
        document.getElementById('status-' + s).classList.toggle('visible', s === state);
    });
}

function resetForm() {
    clearInterval(pollTimer);
    paymentId = null;
    document.getElementById('form-section').style.display = 'block';
    ['waiting','success','failed'].forEach(s => {
        document.getElementById('status-' + s).classList.remove('visible');
    });
    const btn = document.getElementById('pay-btn');
    btn.disabled = false;
    document.getElementById('btn-text').textContent = 'Pay via M-Pesa →';
    startRoutePoll();
}

function startRoutePoll() {
    routePollTimer = setInterval(pollRoute, 5000);
}

// Start polling for route changes
startRoutePoll();
</script>
</body>
</html>
