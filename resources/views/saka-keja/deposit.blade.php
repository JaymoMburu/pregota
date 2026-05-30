<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Secure This House · Saka Keja</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800;900&display=swap" rel="stylesheet">
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Plus Jakarta Sans',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh;padding:20px}
.card{max-width:460px;width:100%;margin:0 auto;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.09);border-radius:22px;padding:32px 26px}
.logo{font-size:18px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;display:block;margin-bottom:6px;text-decoration:none}
.brand{font-size:13px;font-weight:800;color:#f59e0b;display:block;margin-bottom:20px}
.listing-info{background:rgba(245,158,11,.06);border:1px solid rgba(245,158,11,.15);border-radius:13px;padding:14px;margin-bottom:22px}
.listing-type{font-size:11px;font-weight:700;color:#f59e0b;margin-bottom:3px}
.listing-loc{font-size:16px;font-weight:800}
.listing-rent{font-size:13px;color:rgba(255,255,255,.5);margin-top:3px}

.breakdown{margin-bottom:22px}
.breakdown-title{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.35);margin-bottom:10px}
.breakdown-row{display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid rgba(255,255,255,.05);font-size:14px}
.breakdown-row:last-child{border-bottom:none}
.breakdown-label{color:rgba(255,255,255,.55)}
.breakdown-amount{font-weight:700}
.breakdown-total{display:flex;justify-content:space-between;align-items:center;padding:12px 0;border-top:2px solid rgba(245,158,11,.2);margin-top:4px}
.breakdown-total-label{font-size:14px;font-weight:800}
.breakdown-total-amount{font-size:20px;font-weight:900;color:#4ADE80}
.escrow-note{font-size:11px;color:rgba(245,158,11,.7);margin-top:6px;line-height:1.5}

.field{margin-bottom:14px}
.field label{display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.4);margin-bottom:7px}
.field input{width:100%;padding:13px 14px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:11px;color:#fff;font-size:15px;outline:none;font-family:inherit;transition:.2s}
.field input:focus{border-color:rgba(245,158,11,.4);background:rgba(245,158,11,.04)}
.btn{width:100%;padding:15px;background:linear-gradient(135deg,#d97706,#f59e0b);color:#0B141A;font-size:15px;font-weight:900;border:none;border-radius:13px;cursor:pointer;transition:.15s}
.btn:disabled{opacity:.45;cursor:not-allowed}
.err{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);border-radius:9px;padding:10px 14px;font-size:13px;color:#fca5a5;margin-top:12px;display:none}
.note{font-size:11px;color:rgba(255,255,255,.3);text-align:center;margin-top:12px;line-height:1.6}
.pending{display:none;text-align:center;padding:20px 0}
.spinner{width:44px;height:44px;border:3px solid rgba(255,255,255,.1);border-top-color:#f59e0b;border-radius:50%;animation:spin .8s linear infinite;margin:0 auto 16px}
@keyframes spin{to{transform:rotate(360deg)}}
</style>
</head>
<body>
<div class="card">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    <span class="brand">🏠 Saka Keja — Secure This House</span>

    <div class="listing-info">
        <div class="listing-type">{{ $listing->unitLabel() }}</div>
        <div class="listing-loc">{{ $listing->location }}</div>
        <div class="listing-rent">KES {{ number_format($listing->rent) }}/month</div>
    </div>

    <div id="form-view">
        <div class="breakdown">
            <div class="breakdown-title">Payment Breakdown</div>
            @php
                $deposit   = $listing->deposit_amount ?? $listing->rent;
                $advance   = $listing->rent * ($listing->advance_months ?? 1);
                $utilities = collect($listing->utility_fees ?? []);
                $total     = $listing->totalSecureAmount();
            @endphp
            <div class="breakdown-row">
                <span class="breakdown-label">Deposit (refundable)</span>
                <span class="breakdown-amount">KES {{ number_format($deposit) }}</span>
            </div>
            <div class="breakdown-row">
                <span class="breakdown-label">Rent advance ({{ $listing->advance_months ?? 1 }} month{{ ($listing->advance_months ?? 1) > 1 ? 's' : '' }})</span>
                <span class="breakdown-amount">KES {{ number_format($advance) }}</span>
            </div>
            @foreach($utilities as $fee)
            <div class="breakdown-row">
                <span class="breakdown-label">{{ $fee['name'] }}</span>
                <span class="breakdown-amount">KES {{ number_format($fee['amount']) }}</span>
            </div>
            @endforeach
            <div class="breakdown-row">
                <span class="breakdown-label">Pregota escrow fee</span>
                <span class="breakdown-amount">KES 200</span>
            </div>
            <div class="breakdown-total">
                <span class="breakdown-total-label">Total via M-Pesa</span>
                <span class="breakdown-total-amount">KES {{ number_format($total + 200) }}</span>
            </div>
            <div class="escrow-note">🔒 All funds held securely by Pregota. Released to landlord only when you confirm you're moving in.</div>
        </div>

        <div class="field">
            <label>Your Name</label>
            <input type="text" id="seeker_name" placeholder="e.g. James Kamau" maxlength="100">
        </div>
        <div class="field">
            <label>Your M-Pesa Number</label>
            <input type="tel" id="phone" placeholder="07XX XXX XXX" autocomplete="tel">
        </div>
        <div class="err" id="err-msg"></div>
        <button class="btn" id="deposit-btn" onclick="doDeposit()">Pay KES {{ number_format($total + 200) }} to Secure →</button>
        <div class="note">STK Push will appear on your phone. Your money stays with Pregota until you confirm you're moving in.</div>
    </div>

    <div class="pending" id="pending-view">
        <div class="spinner"></div>
        <div style="font-size:15px;font-weight:700;margin-bottom:6px">Check your phone</div>
        <div style="font-size:13px;color:rgba(255,255,255,.45)">Enter your M-Pesa PIN to secure this house. Your money is held safely by Pregota.</div>
    </div>
</div>

<script>
const CSRF = '{{ csrf_token() }}';
let checkoutId = null;

async function doDeposit() {
    const name  = document.getElementById('seeker_name').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const err   = document.getElementById('err-msg');
    err.style.display = 'none';

    if (!name) { err.textContent = 'Enter your name.'; err.style.display = 'block'; return; }
    if (!phone || !/^(\+?254|0)[17]\d{8}$/.test(phone)) { err.textContent = 'Enter a valid Safaricom number.'; err.style.display = 'block'; return; }

    document.getElementById('deposit-btn').disabled = true;

    let data;
    try {
        const res = await fetch('{{ route("saka-keja.deposit.post", $listing->id) }}', {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
            body: JSON.stringify({seeker_name: name, phone}),
        });
        data = await res.json();
    } catch(e) {
        err.textContent = 'Network error. Try again.';
        err.style.display = 'block';
        document.getElementById('deposit-btn').disabled = false;
        return;
    }

    if (!data.success) {
        err.textContent = data.message || 'Something went wrong.';
        err.style.display = 'block';
        document.getElementById('deposit-btn').disabled = false;
        return;
    }

    checkoutId = data.checkout_request_id;
    document.getElementById('form-view').style.display = 'none';
    document.getElementById('pending-view').style.display = 'block';
    poll();
}

function poll() {
    fetch('{{ route("saka-keja.deposit.poll") }}?checkout_request_id=' + checkoutId)
        .then(r => r.json())
        .then(d => {
            if (d.status === 'confirmed') {
                window.location.href = d.redirect;
            } else if (d.status === 'failed') {
                document.getElementById('pending-view').style.display = 'none';
                document.getElementById('form-view').style.display = 'block';
                const err = document.getElementById('err-msg');
                err.textContent = 'Payment failed or cancelled. Try again.';
                err.style.display = 'block';
                document.getElementById('deposit-btn').disabled = false;
            } else {
                setTimeout(poll, 2500);
            }
        })
        .catch(() => setTimeout(poll, 3000));
}
</script>
</body>
</html>
