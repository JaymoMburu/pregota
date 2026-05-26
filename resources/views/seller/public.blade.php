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
.biz-icon{width:60px;height:60px;background:linear-gradient(135deg,#25D366,#1aaa52);border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:26px;margin-bottom:16px}
.biz-name{font-size:22px;font-weight:900;margin-bottom:4px}
.biz-cat{font-size:12px;font-weight:700;color:#25D366;text-transform:uppercase;letter-spacing:.08em;margin-bottom:8px}
.biz-desc{font-size:13px;color:rgba(255,255,255,.65);line-height:1.55;margin-bottom:28px}

.form-group{margin-bottom:18px}
label{display:block;font-size:13px;font-weight:700;color:rgba(255,255,255,.8);margin-bottom:6px}
.amount-display{font-size:32px;font-weight:900;color:#25D366;margin-bottom:4px}
.amount-fixed-note{font-size:12px;color:rgba(255,255,255,.5)}
input{width:100%;padding:13px 14px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);border-radius:10px;color:#fff;font-size:15px;outline:none;transition:.15s;font-family:inherit}
input:focus{border-color:rgba(37,211,102,.5);background:rgba(255,255,255,.08)}
.hint{font-size:11px;color:rgba(255,255,255,.42);margin-top:5px}

.fee-note{display:flex;align-items:center;gap:8px;font-size:12px;color:rgba(255,255,255,.5);background:rgba(255,255,255,.04);border-radius:8px;padding:10px 12px;margin-bottom:20px}
.fee-note strong{color:rgba(255,255,255,.75)}

.btn{width:100%;padding:15px;background:linear-gradient(135deg,#25D366,#1aaa52);color:#fff;font-size:16px;font-weight:800;border:none;border-radius:12px;cursor:pointer;transition:.2s;display:flex;align-items:center;justify-content:center;gap:8px}
.btn:hover{transform:translateY(-1px);box-shadow:0 8px 24px rgba(37,211,102,.3)}
.btn:disabled{opacity:.6;cursor:not-allowed;transform:none;box-shadow:none}

/* Status states */
.status-box{display:none;border-radius:16px;padding:28px;text-align:center;margin-top:0}
.status-box.visible{display:block}
.status-icon{font-size:48px;margin-bottom:12px}
.status-title{font-size:20px;font-weight:900;margin-bottom:6px}
.status-msg{font-size:13px;color:rgba(255,255,255,.65);line-height:1.55}
.status-box.waiting{background:rgba(251,191,36,.08);border:1px solid rgba(251,191,36,.2)}
.status-box.success{background:rgba(34,197,94,.08);border:1px solid rgba(34,197,94,.2)}
.status-box.failed{background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2)}

.powered{text-align:center;font-size:11px;color:rgba(255,255,255,.35);margin-top:20px}
.powered a{color:rgba(255,255,255,.45);text-decoration:none}
</style>
</head>
<body>
<nav class="nav">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
</nav>
<div class="wrap">
    <div class="card">
        <div class="biz-icon">🛍️</div>
        <div class="biz-name">{{ $payLink->business_name }}</div>
        @if($payLink->category)
        <div class="biz-cat">{{ ucfirst($payLink->category) }}</div>
        @endif
        @if($payLink->description)
        <div class="biz-desc">{{ $payLink->description }}</div>
        @endif

        <div id="form-section">
            @if($payLink->fixed_amount && $payLink->default_amount)
            <div class="form-group">
                <label>Amount</label>
                <div class="amount-display">KES {{ number_format($payLink->default_amount) }}</div>
                <div class="amount-fixed-note">Fixed amount</div>
            </div>
            @else
            <div class="form-group">
                <label>Amount (KES)</label>
                <input type="number" id="amount" placeholder="Enter amount" min="10" max="150000"
                    value="{{ $payLink->default_amount ?? '' }}" autocomplete="off">
                <div class="hint">Minimum KES 10</div>
            </div>
            @endif

            <div class="form-group">
                <label>Your M-Pesa number</label>
                <input type="tel" id="phone" placeholder="0712 345 678" autocomplete="tel">
                <div class="hint">You'll get an STK Push — enter your PIN to confirm</div>
            </div>

            <div class="form-group">
                <label>Note <span style="color:rgba(255,255,255,.4)">(optional)</span></label>
                <input type="text" id="note" placeholder="What's this payment for?" maxlength="200">
            </div>

            <div class="fee-note">
                🔒 <span>Your number is <strong>never shared</strong> with the seller. Secured by Pregota.</span>
            </div>

            <button class="btn" id="pay-btn" onclick="initiatePay()">
                <span id="btn-text">Pay via M-Pesa →</span>
            </button>
        </div>

        <div class="status-box waiting" id="status-waiting">
            <div class="status-icon">📱</div>
            <div class="status-title">Check your phone</div>
            <div class="status-msg">An M-Pesa prompt has been sent to your number.<br>Enter your PIN to complete the payment.</div>
        </div>

        <div class="status-box success" id="status-success">
            <div class="status-icon">✅</div>
            <div class="status-title">Payment confirmed!</div>
            <div class="status-msg">Your payment to <strong>{{ $payLink->business_name }}</strong> was successful.<br>Thank you!</div>
        </div>

        <div class="status-box failed" id="status-failed">
            <div class="status-icon">❌</div>
            <div class="status-title">Payment failed</div>
            <div class="status-msg" id="failed-msg">The payment was not completed. Please try again.</div>
            <button class="btn" onclick="reset()" style="margin-top:20px">Try Again</button>
        </div>

        <div class="powered">Powered by <a href="{{ route('home') }}">Pregota</a> · M-Pesa STK Push</div>
    </div>
</div>

<script>
let paymentId = null;
let pollTimer = null;

function initiatePay() {
    const phone  = document.getElementById('phone').value.trim();
    @if($payLink->fixed_amount && $payLink->default_amount)
    const amount = {{ (int) $payLink->default_amount }};
    @else
    const amount = parseInt(document.getElementById('amount').value);
    @endif
    const note   = document.getElementById('note')?.value.trim() || '';

    if (!phone || !/^(\+?254|0)[17]\d{8}$/.test(phone)) {
        alert('Enter a valid Safaricom number.');
        return;
    }
    @if(!($payLink->fixed_amount && $payLink->default_amount))
    if (!amount || amount < 10) {
        alert('Enter an amount of at least KES 10.');
        return;
    }
    @endif

    const btn = document.getElementById('pay-btn');
    btn.disabled = true;
    document.getElementById('btn-text').textContent = 'Sending STK Push…';

    const body = new URLSearchParams({
        phone, note,
        _token: '{{ csrf_token() }}'
    });
    @if(!($payLink->fixed_amount && $payLink->default_amount))
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
            showState('waiting');
            pollTimer = setInterval(poll, 3000);
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

function poll() {
    if (!paymentId) return;
    fetch('{{ route('seller.status') }}?payment_id=' + paymentId)
        .then(r => r.json())
        .then(data => {
            if (data.status === 'confirmed') {
                clearInterval(pollTimer);
                showState('success');
            } else if (data.status === 'failed') {
                clearInterval(pollTimer);
                showState('failed');
            }
        });
}

function showState(state) {
    document.getElementById('form-section').style.display = state === 'waiting' || state === 'failed' ? (state === 'waiting' ? 'none' : 'none') : 'none';
    document.getElementById('form-section').style.display = 'none';
    ['waiting','success','failed'].forEach(s => {
        const el = document.getElementById('status-' + s);
        el.classList.toggle('visible', s === state);
    });
}

function reset() {
    clearInterval(pollTimer);
    paymentId = null;
    document.getElementById('form-section').style.display = 'block';
    ['waiting','success','failed'].forEach(s => {
        document.getElementById('status-' + s).classList.remove('visible');
    });
    const btn = document.getElementById('pay-btn');
    btn.disabled = false;
    document.getElementById('btn-text').textContent = 'Pay via M-Pesa →';
}
</script>
</body>
</html>
