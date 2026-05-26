<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pay {{ $payLink->business_name }} — Pregota</title>
<meta name="description" content="Pay {{ $payLink->business_name }} via M-Pesa. Instant STK Push — no app needed.">
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh;display:flex;flex-direction:column}
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
.fare-label{font-size:12px;color:rgba(255,255,255,.55);margin-top:4px}
.fare-locked{font-size:11px;color:rgba(255,255,255,.45);margin-top:6px;display:flex;align-items:center;gap:4px}

/* No route set — open entry */
.no-route-note{font-size:12px;color:rgba(255,255,255,.5);background:rgba(255,255,255,.04);border-radius:8px;padding:10px 12px;margin-bottom:18px}

.form-group{margin-bottom:18px}
label{display:block;font-size:13px;font-weight:700;color:rgba(255,255,255,.8);margin-bottom:6px}
input{width:100%;padding:13px 14px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);border-radius:10px;color:#fff;font-size:15px;outline:none;transition:.15s;font-family:inherit}
input:focus{border-color:rgba(37,211,102,.5);background:rgba(255,255,255,.08)}
.hint{font-size:11px;color:rgba(255,255,255,.42);margin-top:5px}

.security-note{display:flex;align-items:center;gap:8px;font-size:12px;color:rgba(255,255,255,.5);background:rgba(255,255,255,.04);border-radius:8px;padding:10px 12px;margin-bottom:20px}

.btn{width:100%;padding:15px;background:linear-gradient(135deg,#25D366,#1aaa52);color:#fff;font-size:16px;font-weight:800;border:none;border-radius:12px;cursor:pointer;transition:.2s;display:flex;align-items:center;justify-content:center;gap:8px}
.btn:hover{transform:translateY(-1px);box-shadow:0 8px 24px rgba(37,211,102,.3)}
.btn:disabled{opacity:.6;cursor:not-allowed;transform:none;box-shadow:none}

/* Status states */
.status-box{display:none;border-radius:16px;padding:32px 24px;text-align:center}
.status-box.visible{display:block}
.status-box.waiting{background:rgba(251,191,36,.08);border:1px solid rgba(251,191,36,.2)}
.status-box.success{background:rgba(34,197,94,.06);border:1px solid rgba(34,197,94,.2)}
.status-box.failed{background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2)}

.powered{text-align:center;font-size:11px;color:rgba(255,255,255,.35);margin-top:20px}
.powered a{color:rgba(255,255,255,.45);text-decoration:none}

/* Route changed warning */
.route-changed{display:none;background:rgba(251,191,36,.12);border:1px solid rgba(251,191,36,.25);border-radius:10px;padding:10px 14px;font-size:12px;color:#fbbf24;margin-bottom:16px}
.route-changed.visible{display:block}
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

            {{-- Show-to-conductor prompt --}}
            <div style="background:rgba(37,211,102,.12);border:2px solid rgba(37,211,102,.4);border-radius:12px;padding:14px 16px">
                <div style="font-size:17px;font-weight:900;color:#4ade80;margin-bottom:3px">✓ Show this to the conductor</div>
                <div style="font-size:11px;color:rgba(255,255,255,.5)">Your number was never shared</div>
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
let routePollTimer = null;

// Track current conductor-set fare
let conductorFare  = {{ $payLink->current_fare ?? 'null' }};
let conductorRoute = {{ $payLink->current_route ? json_encode($payLink->current_route) : 'null' }};
const isTransport  = {{ $payLink->category === 'transport' ? 'true' : 'false' }};

function getAmount() {
    if (conductorFare) return conductorFare;
    @if($payLink->fixed_amount && $payLink->default_amount)
    return {{ (int) $payLink->default_amount }};
    @else
    return parseInt(document.getElementById('amount')?.value || '0');
    @endif
}

function initiatePay() {
    const phone  = document.getElementById('phone').value.trim();
    const amount = getAmount();

    if (!phone || !/^(\+?254|0)[17]\d{8}$/.test(phone)) {
        alert('Enter a valid Safaricom number (e.g. 0712 345 678).');
        return;
    }

    @if(!$payLink->current_fare && !($payLink->fixed_amount && $payLink->default_amount))
    if (!amount || amount < 10) {
        alert('Enter an amount of at least KES 10.');
        return;
    }
    @endif

    const btn = document.getElementById('pay-btn');
    btn.disabled = true;
    document.getElementById('btn-text').textContent = 'Sending M-Pesa prompt…';

    const body = new URLSearchParams({
        phone,
        _token: '{{ csrf_token() }}'
    });

    // Only send amount if it's not server-determined
    @if(!$payLink->current_fare && !($payLink->fixed_amount && $payLink->default_amount))
    body.append('amount', amount);
    @endif

    fetch('{{ route('seller.pay', $payLink->handle) }}', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: body.toString(),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            paymentId = data.payment_id;
            stopRoutePoll();
            showState('waiting');
            pollTimer = setInterval(pollStatus, 3000);
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
    fetch('{{ route('seller.status') }}?payment_id=' + paymentId)
        .then(r => r.json())
        .then(data => {
            if (data.status === 'confirmed') {
                clearInterval(pollTimer);
                const amt = getAmount();
                document.getElementById('receipt-amount').textContent = 'KES ' + amt.toLocaleString();
                if (conductorRoute) {
                    document.getElementById('receipt-route').textContent = conductorRoute;
                }
                const now = new Date();
                document.getElementById('receipt-time').textContent =
                    now.toLocaleDateString('en-KE', {weekday:'short', day:'numeric', month:'short'})
                    + ' · ' + now.toLocaleTimeString('en-KE', {hour:'2-digit', minute:'2-digit', second:'2-digit'});
                showState('success');
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

            if (newFare && newRoute) {
                if (routeCard) {
                    document.getElementById('route-name') && (document.getElementById('route-name').textContent = newRoute);
                    document.getElementById('fare-display') && (document.getElementById('fare-display').textContent = 'KES ' + newFare.toLocaleString());
                }
                if (noRouteNote) noRouteNote.style.display = 'none';
                if (amountGroup) amountGroup.style.display = 'none';
            } else if (!newFare) {
                if (noRouteNote) noRouteNote.style.display = 'block';
                if (amountGroup) amountGroup.style.display = 'block';
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
