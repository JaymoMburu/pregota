<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $deni->creditorLabel() }} — Tab Payment</title>
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px}
.card{max-width:420px;width:100%;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.09);border-radius:22px;padding:36px 28px;text-align:center}
.logo{font-size:18px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;display:block;margin-bottom:24px}
.icon{font-size:44px;margin-bottom:12px}
.biz{font-size:14px;color:rgba(255,255,255,.45);margin-bottom:6px}
.desc{font-size:18px;font-weight:900;margin-bottom:20px}
.progress-box{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:18px;margin-bottom:20px;text-align:left}
.prog-row{display:flex;justify-content:space-between;font-size:13px;color:rgba(255,255,255,.55);margin-bottom:10px}
.prog-track{height:8px;background:rgba(255,255,255,.08);border-radius:999px;overflow:hidden;margin-bottom:8px}
.prog-fill{height:100%;background:linear-gradient(90deg,#25D366,#4ADE80);border-radius:999px;transition:.6s}
.prog-labels{display:flex;justify-content:space-between;font-size:11px;color:rgba(255,255,255,.35)}
.amount-label{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.4);margin-bottom:8px}
.field{margin-bottom:14px;text-align:left}
.field label{display:block;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.45);margin-bottom:7px}
.field input{width:100%;padding:13px 14px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:11px;color:#fff;font-size:15px;outline:none;font-family:inherit}
.field input:focus{border-color:rgba(37,211,102,.4)}
.pay-btn{width:100%;padding:14px;background:linear-gradient(135deg,#25D366,#1aaa52);color:#fff;font-size:16px;font-weight:900;border:none;border-radius:13px;cursor:pointer;margin-top:4px}
.pay-btn:disabled{opacity:.45;cursor:not-allowed}
.err{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);border-radius:9px;padding:10px 14px;font-size:13px;color:#fca5a5;margin-top:12px;display:none;text-align:left}
.settled-box{background:rgba(37,211,102,.08);border:1px solid rgba(37,211,102,.2);border-radius:14px;padding:18px;color:#4ADE80;font-size:15px;font-weight:700}
.pending-state,.confirmed-state{display:none;text-align:center;padding:10px 0}
.spinner{width:44px;height:44px;border:3px solid rgba(255,255,255,.1);border-top-color:#25D366;border-radius:50%;animation:spin .8s linear infinite;margin:0 auto 16px}
@keyframes spin{to{transform:rotate(360deg)}}
</style>
</head>
<body>
<div class="card">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    <div class="icon">🧾</div>
    <div class="biz">{{ $deni->creditorLabel() }}</div>
    <div class="desc">{{ $deni->description }}</div>

    @if($deni->status === 'settled')
        <div class="settled-box">✅ Fully paid — thank you!</div>
    @else
        @php $pct = $deni->original_amount > 0 ? round(($deni->amount_paid / $deni->original_amount) * 100) : 0; @endphp
        <div class="progress-box">
            <div class="prog-row">
                <span>Balance remaining</span>
                <span style="font-weight:900;color:#fff">KES {{ number_format($deni->balance()) }}</span>
            </div>
            <div class="prog-track"><div class="prog-fill" style="width:{{ $pct }}%"></div></div>
            <div class="prog-labels">
                <span>KES {{ number_format($deni->amount_paid) }} paid</span>
                <span>KES {{ number_format($deni->original_amount) }} total</span>
            </div>
            @if($deni->due_date)
                <div style="font-size:11px;color:rgba(255,255,255,.38);margin-top:8px">Due by {{ $deni->due_date->format('d M Y') }}</div>
            @endif
        </div>

        <div id="pay-form">
            <div class="field">
                <label>Amount to Pay (KES)</label>
                <input type="number" id="pay-amount" value="{{ $deni->balance() }}" min="1" max="{{ $deni->balance() }}">
            </div>
            <div class="field">
                <label>Your M-Pesa Number</label>
                <input type="tel" id="pay-phone" placeholder="0712 345 678" autocomplete="tel">
            </div>
            <div style="font-size:11px;color:rgba(255,255,255,.3);text-align:center;margin-bottom:10px">Payment goes directly to {{ $deni->creditorLabel() }} via M-Pesa</div>
            <div class="err" id="err-msg"></div>
            <button class="pay-btn" id="pay-btn" onclick="doPay()">Pay via M-Pesa →</button>
        </div>

        <div class="pending-state" id="pending-state">
            <div class="spinner"></div>
            <div style="font-size:15px;font-weight:700;margin-bottom:6px">M-Pesa prompt sent</div>
            <div style="font-size:13px;color:rgba(255,255,255,.45)">Enter your PIN on your phone</div>
        </div>

        <div class="confirmed-state" id="confirmed-state">
            <div style="font-size:48px;margin-bottom:12px">✅</div>
            <div style="font-size:22px;font-weight:900;color:#4ADE80;margin-bottom:6px">Payment received!</div>
            <div id="balance-msg" style="font-size:14px;color:rgba(255,255,255,.55)"></div>
        </div>
    @endif
</div>

<script>
const CSRF  = '{{ csrf_token() }}';
const TOKEN = '{{ $deni->debtor_token }}';
let checkoutId = null;

async function doPay() {
    const phone  = document.getElementById('pay-phone').value.trim();
    const amount = parseInt(document.getElementById('pay-amount').value);
    const errEl  = document.getElementById('err-msg');
    errEl.style.display = 'none';

    if (!phone || !/^(\+?254|0)[17]\d{8}$/.test(phone)) {
        errEl.textContent = 'Enter a valid Safaricom number.'; errEl.style.display = 'block'; return;
    }
    if (!amount || amount < 1) {
        errEl.textContent = 'Enter a valid amount.'; errEl.style.display = 'block'; return;
    }

    document.getElementById('pay-btn').disabled = true;

    const res  = await fetch(`/deni/${TOKEN}/pay`, {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
        body: JSON.stringify({phone, amount}),
    });
    const data = await res.json();

    if (!res.ok) {
        errEl.textContent = data.message || 'Something went wrong.'; errEl.style.display = 'block';
        document.getElementById('pay-btn').disabled = false; return;
    }

    checkoutId = data.checkout_request_id;
    document.getElementById('pay-form').style.display = 'none';
    document.getElementById('pending-state').style.display = 'block';
    poll();
}

function poll() {
    fetch(`/deni/${TOKEN}/status?checkout_request_id=${checkoutId}`)
        .then(r => r.json())
        .then(d => {
            if (d.status === 'confirmed') {
                document.getElementById('pending-state').style.display = 'none';
                document.getElementById('confirmed-state').style.display = 'block';
                const msg = d.deni_status === 'settled'
                    ? 'Fully paid! Tab is now closed. ✅'
                    : `KES ${d.balance.toLocaleString()} remaining on this tab.`;
                document.getElementById('balance-msg').textContent = msg;
            } else if (d.status === 'failed') {
                document.getElementById('pending-state').style.display = 'none';
                document.getElementById('pay-form').style.display = 'block';
                document.getElementById('err-msg').textContent = 'Payment failed. Please try again.';
                document.getElementById('err-msg').style.display = 'block';
                document.getElementById('pay-btn').disabled = false;
            } else {
                setTimeout(poll, 2500);
            }
        })
        .catch(() => setTimeout(poll, 3000));
}
</script>
</body>
</html>
