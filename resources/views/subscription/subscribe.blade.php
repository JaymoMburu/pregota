<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $plan->name }} — {{ $plan->payLink->business_name }}</title>
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh}
.nav{padding:14px 24px;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid rgba(255,255,255,.07)}
.logo{font-size:20px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.wrap{max-width:440px;margin:0 auto;padding:36px 20px 80px}
.plan-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.09);border-radius:18px;padding:26px;margin-bottom:28px;text-align:center}
.biz-name{font-size:13px;color:rgba(255,255,255,.45);margin-bottom:8px}
.plan-name{font-size:22px;font-weight:900;margin-bottom:10px}
.plan-desc{font-size:13px;color:rgba(255,255,255,.55);line-height:1.55;margin-bottom:16px}
.price-row{display:flex;align-items:baseline;justify-content:center;gap:6px}
.price-amount{font-size:40px;font-weight:900;color:#4ADE80}
.price-freq{font-size:14px;color:rgba(255,255,255,.45)}
.field{margin-bottom:18px}
.field label{display:block;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.45);margin-bottom:7px}
.field input{width:100%;padding:13px 14px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:11px;color:#fff;font-size:15px;font-family:inherit;outline:none}
.field input:focus{border-color:rgba(37,211,102,.4)}
.field .hint{font-size:12px;color:rgba(255,255,255,.3);margin-top:5px}
.sub-btn{width:100%;padding:14px;background:linear-gradient(135deg,#25D366,#1aaa52);color:#fff;font-size:16px;font-weight:900;border:none;border-radius:13px;cursor:pointer}
.sub-btn:hover{opacity:.9}
.sub-btn:disabled{opacity:.45;cursor:not-allowed}
.err{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);border-radius:9px;padding:10px 14px;font-size:13px;color:#fca5a5;margin-top:12px;display:none}
.pending-state,.confirmed-state{text-align:center;padding:30px 0;display:none}
.spinner{width:44px;height:44px;border:3px solid rgba(255,255,255,.1);border-top-color:#25D366;border-radius:50%;animation:spin .8s linear infinite;margin:0 auto 16px}
@keyframes spin{to{transform:rotate(360deg)}}
.conf-icon{font-size:56px;margin-bottom:14px}
.next-due{font-size:13px;color:rgba(255,255,255,.45);margin-top:8px}
</style>
</head>
<body>
<nav class="nav">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
</nav>
<div class="wrap">
    <div class="plan-card">
        <div class="biz-name">{{ $plan->payLink->business_name }}</div>
        <div class="plan-name">{{ $plan->name }}</div>
        @if($plan->description)<div class="plan-desc">{{ $plan->description }}</div>@endif
        <div class="price-row">
            <div class="price-amount">KES {{ number_format($plan->amount) }}</div>
            <div class="price-freq">/ {{ $plan->frequencyLabel() }}</div>
        </div>
    </div>

    <div id="sub-form">
        <div class="field">
            <label>Your M-Pesa Number</label>
            <input type="tel" id="phone" placeholder="0712 345 678" autocomplete="tel">
            <div class="hint">You'll get an M-Pesa prompt — enter your PIN to subscribe</div>
        </div>
        <div class="err" id="err-msg"></div>
        <button class="sub-btn" id="sub-btn" onclick="subscribe()">Subscribe & Pay →</button>
    </div>

    <div class="pending-state" id="pending-state">
        <div class="spinner"></div>
        <div style="font-size:16px;font-weight:700;margin-bottom:6px">M-Pesa prompt sent</div>
        <div style="font-size:13px;color:rgba(255,255,255,.45)">Check your phone and enter your M-Pesa PIN</div>
    </div>

    <div class="confirmed-state" id="confirmed-state">
        <div class="conf-icon">✅</div>
        <div style="font-size:28px;font-weight:900;color:#4ADE80;margin-bottom:6px">Subscribed!</div>
        <div style="font-size:15px;font-weight:700;margin-bottom:4px">{{ $plan->name }}</div>
        <div style="font-size:13px;color:rgba(255,255,255,.45);margin-bottom:8px">{{ $plan->payLink->business_name }}</div>
        <div class="next-due" id="next-due"></div>
    </div>
</div>

<script>
const CSRF   = '{{ csrf_token() }}';
const PLAN   = {{ $plan->id }};
let checkoutId = null;

async function subscribe() {
    const phone = document.getElementById('phone').value.trim();
    const errEl = document.getElementById('err-msg');
    if (!phone || !/^(\+?254|0)[17]\d{8}$/.test(phone)) {
        errEl.textContent = 'Enter a valid Safaricom number.'; errEl.style.display = 'block'; return;
    }
    errEl.style.display = 'none';
    document.getElementById('sub-btn').disabled = true;

    const res  = await fetch(`/subscribe/${PLAN}/pay`, {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
        body: JSON.stringify({phone}),
    });
    const data = await res.json();

    if (!res.ok) {
        errEl.textContent = data.message || 'Something went wrong.'; errEl.style.display = 'block';
        document.getElementById('sub-btn').disabled = false;
        return;
    }

    checkoutId = data.checkout_request_id;
    document.getElementById('sub-form').style.display = 'none';
    document.getElementById('pending-state').style.display = 'block';
    poll();
}

function poll() {
    fetch(`/subscribe/${PLAN}/status?checkout_request_id=${checkoutId}`)
        .then(r => r.json())
        .then(d => {
            if (d.status === 'confirmed') {
                document.getElementById('pending-state').style.display = 'none';
                document.getElementById('confirmed-state').style.display = 'block';
                if (d.next_due) document.getElementById('next-due').textContent = 'Next payment due: ' + d.next_due;
            } else {
                setTimeout(poll, 2500);
            }
        })
        .catch(() => setTimeout(poll, 3000));
}
</script>
</body>
</html>
