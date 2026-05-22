<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $bill->label ?? 'Bill Split' }} — Pregota</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0f0f1a;color:#fff;min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:24px 20px}

.card{width:100%;max-width:380px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:24px;padding:28px 24px;text-align:center}

.bill-label{font-size:13px;color:rgba(255,255,255,.4);margin-bottom:6px}
.bill-amount{font-size:36px;font-weight:900;letter-spacing:-.5px;margin-bottom:20px}

/* QR */
#qrcode{display:flex;justify-content:center;margin-bottom:20px}
#qrcode canvas,#qrcode img{border-radius:12px;padding:10px;background:#fff}

.scan-hint{font-size:13px;color:rgba(255,255,255,.4);margin-bottom:20px}

/* Progress */
.progress-wrap{margin-bottom:16px}
.progress-bar{height:10px;background:rgba(255,255,255,.08);border-radius:999px;overflow:hidden;margin-bottom:10px}
.progress-fill{height:100%;background:linear-gradient(90deg,#7c3aed,#db2777);border-radius:999px;transition:.5s}
.progress-label{display:flex;justify-content:space-between;font-size:12px;color:rgba(255,255,255,.45)}
.progress-label .paid{color:#4ade80;font-weight:700}

/* Payments list */
.payments-list{display:flex;flex-direction:column;gap:8px;margin-top:16px;max-height:220px;overflow-y:auto}
.payment-row{display:flex;align-items:center;justify-content:space-between;background:rgba(34,197,94,.07);border:1px solid rgba(34,197,94,.2);border-radius:10px;padding:10px 14px;animation:slideIn .3s ease}
@keyframes slideIn{from{opacity:0;transform:translateY(-6px)}to{opacity:1;transform:translateY(0)}}
.payment-amount{font-size:15px;font-weight:800;color:#4ade80}
.payment-meta{font-size:11px;color:rgba(255,255,255,.35);margin-top:2px}
.payment-check{font-size:20px}
.payments-empty{font-size:12px;color:rgba(255,255,255,.25);text-align:center;padding:12px 0}

/* States */
.status-pending{display:block}
.status-settled{display:none}
.settled-icon{font-size:56px;margin-bottom:12px}
.settled-title{font-size:20px;font-weight:900;color:#4ade80;margin-bottom:6px}
.settled-sub{font-size:13px;color:rgba(255,255,255,.45)}

.new-bill-btn{display:inline-block;margin-top:20px;padding:12px 28px;border-radius:12px;border:none;font-size:14px;font-weight:700;cursor:pointer;background:linear-gradient(135deg,#7c3aed,#db2777);color:#fff;text-decoration:none}
.footer{margin-top:20px;font-size:11px;color:rgba(255,255,255,.2)}

/* Send to phone */
.send-section{margin-top:20px;padding-top:16px;border-top:1px solid rgba(255,255,255,.07);text-align:left}
.send-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.3);margin-bottom:10px;display:flex;align-items:center;gap:6px}
.send-row{display:flex;gap:8px;margin-bottom:8px}
.send-input{flex:1;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.12);border-radius:8px;padding:10px 12px;color:#fff;font-size:14px;outline:none;font-family:inherit}
.send-input:focus{border-color:#7c3aed}
.send-input::placeholder{color:rgba(255,255,255,.3)}
.send-btn{padding:10px 14px;border-radius:8px;border:none;background:rgba(124,58,237,.3);border:1px solid rgba(124,58,237,.4);color:#c084fc;font-size:12px;font-weight:700;cursor:pointer;white-space:nowrap}
.send-btn:disabled{opacity:.4;cursor:not-allowed}
.send-status{font-size:12px;margin-top:6px;min-height:18px;text-align:center}
</style>
</head>
<body>

<div class="card">
    <div class="status-pending" id="pendingState">
        <div class="bill-label">{{ $bill->business_name }}{{ $bill->label ? ' · ' . $bill->label : '' }}</div>
        <div class="bill-amount">KES {{ number_format($bill->total_amount) }}</div>

        <div id="qrcode"></div>
        <div class="scan-hint">Show this to the table — everyone scans to pay their share</div>

        <div class="progress-wrap">
            <div class="progress-bar">
                <div class="progress-fill" id="progressFill" style="width:{{ $bill->progressPct() }}%"></div>
            </div>
            <div class="progress-label">
                <span class="paid" id="paidAmt">KES {{ number_format($bill->paid_amount) }} paid</span>
                <span id="remainingAmt">KES {{ number_format($bill->remainingAmount()) }} remaining</span>
            </div>
        </div>

        <div class="payments-list" id="paymentsList">
            @forelse($bill->payments->where('status','paid') as $p)
            <div class="payment-row">
                <div>
                    <div class="payment-amount">KES {{ number_format($p->amount) }}</div>
                    <div class="payment-meta">{{ $p->updated_at->format('g:i A') }}</div>
                </div>
                <div class="payment-check">✅</div>
            </div>
            @empty
            <div class="payments-empty" id="emptyHint">Waiting for first payment...</div>
            @endforelse
        </div>

        <!-- No QR scanner? Send STK Push directly -->
        <div class="send-section">
            <div class="send-label">📱 No scanner? Send to phone</div>
            <div class="send-row">
                <input class="send-input" id="sendAmount" type="number"
                       placeholder="KES amount" min="1" max="{{ $bill->remainingAmount() }}"
                       value="{{ $bill->remainingAmount() }}">
                <input class="send-input" id="sendPhone" type="tel" placeholder="07XX XXX XXX">
            </div>
            <button class="send-btn" id="sendBtn" onclick="sendToPhone()" style="width:100%">
                Send Payment Request →
            </button>
            <div class="send-status" id="sendStatus"></div>
        </div>
    </div>

    <div class="status-settled" id="settledState">
        <div class="settled-icon">✅</div>
        <div class="settled-title">Bill Settled!</div>
        <div class="settled-sub">KES {{ number_format($bill->total_amount) }} is on its way to your {{ $bill->payout_type === 'till' ? 'Till' : 'Paybill' }}.</div>
        <a href="{{ route('bill-split.new') }}" class="new-bill-btn">New Bill Split →</a>
    </div>
</div>

<div class="footer">Powered by Pregota · Expires {{ $bill->expires_at->diffForHumans() }}</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
const SPLIT_URL  = '{{ url('/s/' . $bill->split_token) }}';
const STATUS_URL = '{{ route('bill-split.bill-status', $bill->waiter_token) }}';
const PAY_URL    = '{{ route('bill-split.pay', $bill->split_token) }}';
const CSRF       = document.cookie.match(/XSRF-TOKEN=([^;]+)/)?.[1]
                    ? decodeURIComponent(document.cookie.match(/XSRF-TOKEN=([^;]+)/)[1])
                    : '{{ csrf_token() }}';
const FMT        = n => 'KES ' + Number(n).toLocaleString('en-KE');

new QRCode(document.getElementById('qrcode'), {
    text:   SPLIT_URL,
    width:  220,
    height: 220,
    colorDark:  '#000000',
    colorLight: '#ffffff',
    correctLevel: QRCode.CorrectLevel.M,
});

let settled      = {{ $bill->status === 'settled' ? 'true' : 'false' }};
let knownPayments = {{ $bill->payments->count() }};

function renderPayments(payments) {
    const list = document.getElementById('paymentsList');
    if (payments.length === 0) return;

    const empty = document.getElementById('emptyHint');
    if (empty) empty.remove();

    // Only add newly arrived rows
    const current = list.querySelectorAll('.payment-row').length;
    payments.slice(current).reverse().forEach(p => {
        const row = document.createElement('div');
        row.className = 'payment-row';
        row.innerHTML = `<div>
            <div class="payment-amount">${FMT(p.amount)}</div>
            <div class="payment-meta">${p.time}</div>
        </div><div class="payment-check">✅</div>`;
        list.prepend(row);
    });
}

function poll() {
    if (settled) return;
    fetch(STATUS_URL)
        .then(r => r.json())
        .then(d => {
            document.getElementById('progressFill').style.width = d.progress + '%';
            document.getElementById('paidAmt').textContent      = FMT(d.paid_amount) + ' paid';
            document.getElementById('remainingAmt').textContent = FMT(d.remaining) + ' remaining';
            const amtInput = document.getElementById('sendAmount');
            if (amtInput && d.remaining > 0) { amtInput.value = d.remaining; amtInput.max = d.remaining; }

            if (d.payments) renderPayments(d.payments);

            if (d.settled) {
                settled = true;
                document.getElementById('pendingState').style.display = 'none';
                document.getElementById('settledState').style.display = 'block';
            }
        })
        .catch(() => {});
}

if (!settled) setInterval(poll, 3000);
else {
    document.getElementById('pendingState').style.display = 'none';
    document.getElementById('settledState').style.display = 'block';
}

function sendToPhone() {
    const amount = parseInt(document.getElementById('sendAmount').value);
    const phone  = document.getElementById('sendPhone').value.trim();
    const status = document.getElementById('sendStatus');
    const btn    = document.getElementById('sendBtn');

    if (!amount || amount < 1)  { status.style.color = '#f87171'; status.textContent = 'Enter an amount.'; return; }
    if (!phone)                  { status.style.color = '#f87171'; status.textContent = 'Enter the customer\'s phone number.'; return; }

    btn.disabled = true;
    status.style.color = 'rgba(255,255,255,.45)';
    status.textContent = 'Sending STK Push…';

    fetch(PAY_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ amount, phone }),
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            status.style.color = '#4ade80';
            status.textContent = '✓ Sent! Customer should see M-Pesa prompt now.';
            document.getElementById('sendPhone').value = '';
        } else {
            status.style.color = '#f87171';
            status.textContent = d.message || 'Failed. Please try again.';
        }
        btn.disabled = false;
    })
    .catch(() => {
        status.style.color = '#f87171';
        status.textContent = 'Network error. Please try again.';
        btn.disabled = false;
    });
}
</script>
</body>
</html>
