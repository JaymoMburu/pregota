<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $group->name }} â€” Pregota</title>
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}input,textarea,select,button{font-family:inherit;font-size:inherit}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh}
.nav{padding:14px 24px;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid rgba(255,255,255,.07)}
.logo{font-size:20px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.wrap{max-width:460px;margin:0 auto;padding:36px 20px 80px}
.group-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.09);border-radius:18px;padding:24px;margin-bottom:28px;text-align:center}
.group-icon{font-size:40px;margin-bottom:10px}
.group-name{font-size:22px;font-weight:900;margin-bottom:5px}
.group-desc{font-size:13px;color:rgba(255,255,255,.5);line-height:1.55;margin-bottom:14px}
.group-meta{display:flex;gap:10px;justify-content:center;flex-wrap:wrap}
.meta-chip{font-size:11px;font-weight:700;padding:4px 12px;border-radius:999px;background:rgba(37,211,102,.1);border:1px solid rgba(37,211,102,.2);color:#4ADE80}
.field{margin-bottom:18px}
.field label{display:block;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.45);margin-bottom:7px}
.field input{width:100%;padding:13px 14px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:11px;color:#fff;font-size:15px;font-family:inherit;outline:none}
.field input:focus{border-color:rgba(37,211,102,.4)}
.field .hint{font-size:12px;color:rgba(255,255,255,.3);margin-top:5px}
.pay-btn{width:100%;padding:14px;background:linear-gradient(135deg,#25D366,#1aaa52);color:#fff;font-size:16px;font-weight:900;border:none;border-radius:13px;cursor:pointer}
.pay-btn:hover{opacity:.9}
.pay-btn:disabled{opacity:.45;cursor:not-allowed}
.err{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);border-radius:9px;padding:10px 14px;font-size:13px;color:#fca5a5;margin-top:12px;display:none}
.pending-state{text-align:center;padding:30px 0;display:none}
.spinner{width:44px;height:44px;border:3px solid rgba(255,255,255,.1);border-top-color:#25D366;border-radius:50%;animation:spin .8s linear infinite;margin:0 auto 16px}
@keyframes spin{to{transform:rotate(360deg)}}
.confirmed-state{text-align:center;padding:20px 0;display:none}
.conf-icon{font-size:56px;margin-bottom:14px}
.conf-amount{font-size:36px;font-weight:900;color:#4ADE80;margin-bottom:6px}
.receipt-link{font-size:13px;color:#a78bfa;font-family:monospace;text-decoration:none}
.already-paid{background:rgba(37,211,102,.06);border:1px solid rgba(37,211,102,.2);border-radius:12px;padding:18px;text-align:center}
</style>
</head>
<body>
<nav class="nav">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
</nav>
<div class="wrap">
    <div class="group-card">
        <div class="group-icon">ðŸ¤</div>
        <div class="group-name">{{ $group->name }}</div>
        @if($group->description)<div class="group-desc">{{ $group->description }}</div>@endif
        <div class="group-meta">
            @if($group->amount_per_member)<span class="meta-chip">KES {{ number_format($group->amount_per_member) }} per member</span>@endif
            <span class="meta-chip">{{ ucfirst($group->frequency) }}</span>
            @if($group->next_due)<span class="meta-chip">Due {{ $group->next_due->format('d M Y') }}</span>@endif
        </div>
    </div>

    <div id="pay-form">
        <div class="field">
            <label>Your M-Pesa Number</label>
            <input type="tel" id="phone" placeholder="0712 345 678" autocomplete="tel">
            <div class="hint">You'll get an M-Pesa prompt to confirm payment</div>
        </div>

        @if(!$group->amount_per_member)
        <div class="field">
            <label>Amount (KES)</label>
            <input type="number" id="amount" placeholder="Enter amount" min="10" max="500000">
        </div>
        @endif

        <div class="err" id="err-msg"></div>
        <button class="pay-btn" id="pay-btn" onclick="pay()">Pay with M-Pesa</button>
    </div>

    <div class="pending-state" id="pending-state">
        <div class="spinner"></div>
        <div style="font-size:16px;font-weight:700;margin-bottom:6px">M-Pesa prompt sent</div>
        <div style="font-size:13px;color:rgba(255,255,255,.45)">Check your phone and enter your M-Pesa PIN</div>
    </div>

    <div class="confirmed-state" id="confirmed-state">
        <div class="conf-icon">âœ…</div>
        <div class="conf-amount">KES {{ $group->amount_per_member ? number_format($group->amount_per_member) : 'â€”' }}</div>
        <div style="font-size:16px;font-weight:700;margin-bottom:6px">Contribution Received!</div>
        <div style="font-size:13px;color:rgba(255,255,255,.45);margin-bottom:14px">{{ $group->name }}</div>
        <a id="receipt-link" class="receipt-link" href="#" target="_blank"></a>
    </div>
</div>

<script>
const CSRF   = '{{ csrf_token() }}';
const SLUG   = '{{ $group->slug }}';
let checkoutId = null;
let pollTimer  = null;

async function pay() {
    const phone  = document.getElementById('phone').value.trim();
    const errEl  = document.getElementById('err-msg');
    @if(!$group->amount_per_member)
    const amount = parseInt(document.getElementById('amount').value || '0');
    @else
    const amount = {{ (int) $group->amount_per_member }};
    @endif

    if (!phone || !/^(\+?254|0)[17]\d{8}$/.test(phone)) {
        errEl.textContent = 'Enter a valid Safaricom number.'; errEl.style.display = 'block'; return;
    }
    @if(!$group->amount_per_member)
    if (!amount || amount < 10) {
        errEl.textContent = 'Enter an amount of at least KES 10.'; errEl.style.display = 'block'; return;
    }
    @endif
    errEl.style.display = 'none';
    document.getElementById('pay-btn').disabled = true;

    const res  = await fetch(`/group/${SLUG}/pay`, {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
        body: JSON.stringify({phone, amount}),
    });
    const data = await res.json();

    if (!res.ok) {
        if (data.error === 'already_paid') {
            document.getElementById('pay-form').innerHTML = `<div class="already-paid">âœ… You have already paid for this period. Thank you!</div>`;
        } else {
            errEl.textContent = data.message || 'Something went wrong.'; errEl.style.display = 'block';
            document.getElementById('pay-btn').disabled = false;
        }
        return;
    }

    checkoutId = data.checkout_request_id;
    document.getElementById('pay-form').style.display = 'none';
    document.getElementById('pending-state').style.display = 'block';
    poll();
}

function poll() {
    fetch(`/group/${SLUG}/status?checkout_request_id=${checkoutId}`)
        .then(r => r.json())
        .then(d => {
            if (d.status === 'confirmed') {
                document.getElementById('pending-state').style.display = 'none';
                document.getElementById('confirmed-state').style.display = 'block';
                if (d.receipt_number) {
                    const link = document.getElementById('receipt-link');
                    link.textContent = d.receipt_number;
                    link.href = `/receipt/${d.receipt_number}`;
                }
            } else if (d.status === 'failed') {
                document.getElementById('pending-state').style.display = 'none';
                document.getElementById('pay-form').style.display = 'block';
                document.getElementById('pay-btn').disabled = false;
                const e = document.getElementById('err-msg');
                e.textContent = 'Payment failed or was cancelled. Try again.'; e.style.display = 'block';
            } else {
                pollTimer = setTimeout(poll, 2500);
            }
        })
        .catch(() => { pollTimer = setTimeout(poll, 3000); });
}
</script>
</body>
</html>

