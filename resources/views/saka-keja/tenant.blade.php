<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tenant Portal Â· Saka Keja</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800;900&display=swap" rel="stylesheet">
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}input,textarea,select,button{font-family:inherit;font-size:inherit}
body{font-family:'Plus Jakarta Sans',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh;padding:20px-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}
.card{max-width:460px;width:100%;margin:0 auto}
.logo{font-size:18px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;display:block;margin-bottom:6px;text-decoration:none}
.brand{font-size:13px;font-weight:800;color:#f59e0b;display:block;margin-bottom:20px}

.listing-box{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.09);border-radius:16px;padding:18px;margin-bottom:16px}
.listing-type{font-size:11px;font-weight:700;color:#f59e0b;margin-bottom:3px}
.listing-loc{font-size:17px;font-weight:900}
.listing-meta{font-size:13px;color:rgba(255,255,255,.4);margin-top:4px}
.tenant-name{font-size:13px;font-weight:700;color:rgba(255,255,255,.6);margin-top:6px}

/* Deposit section */
.deposit-box{background:rgba(74,222,128,.04);border:1px solid rgba(74,222,128,.13);border-radius:16px;padding:18px;margin-bottom:16px}
.section-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.35);margin-bottom:12px}
.deposit-row{display:flex;justify-content:space-between;align-items:center;margin-bottom:8px}
.deposit-key{font-size:13px;color:rgba(255,255,255,.5)}
.deposit-val{font-size:13px;font-weight:800;color:#4ADE80}
.move-out-notice{background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.2);border-radius:11px;padding:12px 14px;margin-top:12px;font-size:13px;color:rgba(255,255,255,.65);line-height:1.6}
.move-out-notice strong{color:#f59e0b}
.btn-move-out{width:100%;margin-top:14px;padding:12px;background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2);border-radius:11px;color:#fca5a5;font-size:13px;font-weight:700;cursor:pointer}
.btn-move-out:hover{background:rgba(239,68,68,.15)}

/* Rent section */
.pay-box{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.09);border-radius:16px;padding:20px;margin-bottom:16px}
.pay-title{font-size:16px;font-weight:900;margin-bottom:6px}
.pay-sub{font-size:13px;color:rgba(255,255,255,.4);margin-bottom:18px;line-height:1.6}
.paid-badge{display:inline-flex;align-items:center;gap:6px;padding:8px 14px;background:rgba(74,222,128,.1);border:1px solid rgba(74,222,128,.2);border-radius:999px;font-size:13px;font-weight:700;color:#4ADE80;margin-bottom:12px}
.paused-notice{background:rgba(245,158,11,.07);border:1px solid rgba(245,158,11,.18);border-radius:11px;padding:12px 14px;font-size:13px;color:#f59e0b;line-height:1.5}

.field{margin-bottom:14px}
.field label{display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.4);margin-bottom:7px}
.field input,.field select{width:100%;padding:13px 14px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:11px;color:#fff;font-size:15px;outline:none;font-family:inherit;transition:.2s}
.field input:focus,.field select:focus{border-color:rgba(245,158,11,.4);background:rgba(245,158,11,.04)}
.field select option{background:#1a2633}

.fee-note{font-size:12px;color:rgba(255,255,255,.3);margin-bottom:14px;line-height:1.5}
.fee-note strong{color:rgba(255,255,255,.5)}

.btn{width:100%;padding:15px;background:linear-gradient(135deg,#d97706,#f59e0b);color:#0B141A;font-size:15px;font-weight:900;border:none;border-radius:13px;cursor:pointer}
.btn:disabled{opacity:.45;cursor:not-allowed}
.err{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);border-radius:9px;padding:10px 14px;font-size:13px;color:#fca5a5;margin-top:12px;display:none}
.pending{display:none;text-align:center;padding:20px 0}
.spinner{width:44px;height:44px;border:3px solid rgba(255,255,255,.1);border-top-color:#f59e0b;border-radius:50%;animation:spin .8s linear infinite;margin:0 auto 16px}
@keyframes spin{to{transform:rotate(360deg)}}
.success{display:none;text-align:center;padding:16px 0}

.history-box{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.09);border-radius:16px;padding:18px}
.history-title{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.35);margin-bottom:14px}
.payment-row{display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid rgba(255,255,255,.05)}
.payment-row:last-child{border-bottom:none}
.payment-month{font-size:14px;font-weight:700}
.payment-date{font-size:11px;color:rgba(255,255,255,.3);margin-top:2px}
.payment-right{text-align:right}
.payment-amount{font-size:14px;font-weight:800;color:#4ADE80}
.payment-receipt{font-size:11px;color:rgba(255,255,255,.3);margin-top:2px}
.no-history{font-size:13px;color:rgba(255,255,255,.25);padding:8px 0}
</style>
</head>
<body>
<div class="card">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    <span class="brand">ðŸ  Saka Keja â€” Tenant Portal</span>

    <div class="listing-box">
        <div class="listing-type">{{ $deposit->listing->unitLabel() }}</div>
        <div class="listing-loc">{{ $deposit->listing->location }}</div>
        <div class="listing-meta">KES {{ number_format($deposit->listing->rent) }}/month</div>
        <div class="tenant-name">Tenant: {{ $deposit->seeker_name }}</div>
    </div>

    {{-- â”€â”€ Deposit section â”€â”€ --}}
    <div class="deposit-box">
        <div class="section-label">ðŸ” Security Deposit</div>
        <div class="deposit-row">
            <span class="deposit-key">Amount held</span>
            <span class="deposit-val">KES {{ number_format($deposit->deposit_amount) }}</span>
        </div>
        <div class="deposit-row">
            <span class="deposit-key">Moved in</span>
            <span class="deposit-val" style="color:rgba(255,255,255,.65)">{{ $deposit->confirmed_at->format('d M Y') }}</span>
        </div>
        <div class="deposit-row">
            <span class="deposit-key">Status</span>
            <span class="deposit-val" style="color:{{ $deposit->status === 'moving_out' ? '#f59e0b' : '#4ADE80' }}">
                {{ $deposit->status === 'moving_out' ? 'Move-out Requested' : 'Active Tenancy' }}
            </span>
        </div>

        @if($deposit->status === 'moving_out')
            <div class="move-out-notice">
                â³ Your move-out request is pending landlord inspection.<br>
                Requested on <strong>{{ $deposit->move_out_requested_at->format('d M Y, H:i') }}</strong>.<br>
                The landlord will inspect the property. If no damage or repainting is required, your KES {{ number_format($deposit->deposit_amount) }} deposit will be fully refunded.
            </div>
        @else
            <div style="font-size:12px;color:rgba(255,255,255,.35);margin-top:10px;line-height:1.6">
                â„¹ï¸ The deposit covers any damages or repainting costs when you vacate. If the house is in good condition, the full deposit is refunded.
            </div>
            <button class="btn-move-out" onclick="doMoveOut()">ðŸšª Request Move Out</button>
        @endif
    </div>

    {{-- â”€â”€ Rent section â”€â”€ --}}
    <div class="pay-box">
        <div class="pay-title">Pay Monthly Rent</div>

        @if($deposit->status === 'moving_out')
            <div class="paused-notice">âš ï¸ Rent payments are paused while your move-out is being processed.</div>
        @else
            <div class="pay-sub">Pay via M-Pesa. Pregota deducts 2% management fee and sends the balance to your landlord.</div>

            @if($paidThisMonth)
            <div class="paid-badge">âœ“ Rent paid for {{ now()->format('F Y') }}</div>
            <div style="font-size:12px;color:rgba(255,255,255,.3);margin-bottom:14px">Receipt: {{ $paidThisMonth->receipt_number }}</div>
            @endif

            <div id="pay-form">
                <div class="field">
                    <label>Rent Month</label>
                    <select id="rent_month">
                        @for($i = 0; $i >= -2; $i--)
                        @php $m = now()->addMonths($i)->format('Y-m'); @endphp
                        <option value="{{ $m }}" {{ $i === 0 ? 'selected' : '' }}>{{ now()->addMonths($i)->format('F Y') }}</option>
                        @endfor
                    </select>
                </div>
                <div class="field">
                    <label>Your M-Pesa Number</label>
                    <input type="tel" id="phone" placeholder="07XX XXX XXX" autocomplete="tel">
                </div>
                <div class="fee-note">
                    KES {{ number_format($deposit->listing->rent) }} rent â†’ Pregota keeps <strong>KES {{ number_format((int)ceil($deposit->listing->rent * 2 / 100)) }}</strong> (2%) â†’ Landlord receives <strong>KES {{ number_format($deposit->listing->rent - (int)ceil($deposit->listing->rent * 2 / 100)) }}</strong>
                </div>
                <div class="err" id="err-msg"></div>
                <button class="btn" id="pay-btn" onclick="doPay()">Pay KES {{ number_format($deposit->listing->rent) }} â†’</button>
            </div>

            <div class="pending" id="pending-view">
                <div class="spinner"></div>
                <div style="font-size:15px;font-weight:700;margin-bottom:6px">Check your phone</div>
                <div style="font-size:13px;color:rgba(255,255,255,.45)">Enter your M-Pesa PIN to pay rent.</div>
            </div>

            <div class="success" id="success-view">
                <div style="font-size:52px;line-height:1;margin-bottom:10px">âœ…</div>
                <div id="rent-amount" style="font-size:28px;font-weight:900;color:#fff;margin-bottom:4px"></div>
                <div id="rent-month-label" style="font-size:14px;font-weight:700;color:#4ADE80;margin-bottom:14px"></div>
                <div id="rent-receipt-box" style="display:none;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:12px;padding:14px 16px;text-align:left;margin-bottom:14px">
                    <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.35);margin-bottom:10px">M-Pesa Receipt</div>
                    <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:6px">
                        <span style="color:rgba(255,255,255,.45)">Receipt No.</span>
                        <span id="rent-receipt-no" style="font-weight:700;font-family:monospace;color:#4ADE80"></span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:13px">
                        <span style="color:rgba(255,255,255,.45)">Property</span>
                        <span style="font-weight:600;color:rgba(255,255,255,.7)">{{ $deposit->listing->location }}</span>
                    </div>
                </div>
                <div style="font-size:12px;color:rgba(255,255,255,.3)">Refreshing in a momentâ€¦</div>
            </div>
        @endif
    </div>

    <div class="history-box">
        <div class="history-title">Rent Payment History</div>
        @forelse($payments->where('status','confirmed') as $p)
        <div class="payment-row">
            <div>
                <div class="payment-month">{{ \Carbon\Carbon::createFromFormat('Y-m', $p->rent_month)->format('F Y') }}</div>
                <div class="payment-date">{{ $p->created_at->format('d M Y') }}</div>
            </div>
            <div class="payment-right">
                <div class="payment-amount">KES {{ number_format($p->gross_amount) }}</div>
                <div class="payment-receipt">{{ $p->receipt_number }}</div>
            </div>
        </div>
        @empty
        <div class="no-history">No payments yet.</div>
        @endforelse
    </div>
</div>

<script>
const CSRF = '{{ csrf_token() }}';
let checkoutId = null;

async function doMoveOut() {
    if (!confirm('Request to move out? Your landlord will be notified. The deposit will be refunded after inspection â€” full refund if the house is in good condition.')) return;

    const res  = await fetch('{{ route("saka-keja.deposit.move-out", $deposit->token) }}', {
        method: 'POST', headers: {'X-CSRF-TOKEN': CSRF}
    });
    const data = await res.json();

    if (data.success) location.reload();
    else alert(data.message || 'Could not process request. Try again.');
}

async function doPay() {
    const phone = document.getElementById('phone').value.trim();
    const month = document.getElementById('rent_month').value;
    const err   = document.getElementById('err-msg');
    err.style.display = 'none';

    if (!phone || !/^(\+?254|0)[17]\d{8}$/.test(phone)) {
        err.textContent = 'Enter a valid Safaricom number.';
        err.style.display = 'block';
        return;
    }

    document.getElementById('pay-btn').disabled = true;

    let data;
    try {
        const res = await fetch('{{ route("saka-keja.rent.post", $deposit->token) }}', {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
            body: JSON.stringify({phone, rent_month: month}),
        });
        data = await res.json();
    } catch(e) {
        err.textContent = 'Network error. Try again.';
        err.style.display = 'block';
        document.getElementById('pay-btn').disabled = false;
        return;
    }

    if (!data.success) {
        err.textContent = data.message || 'Something went wrong.';
        err.style.display = 'block';
        document.getElementById('pay-btn').disabled = false;
        return;
    }

    checkoutId = data.checkout_request_id;
    document.getElementById('pay-form').style.display = 'none';
    document.getElementById('pending-view').style.display = 'block';
    poll();
}

function poll() {
    fetch('{{ route("saka-keja.rent.poll") }}?checkout_request_id=' + checkoutId)
        .then(r => r.json())
        .then(d => {
            if (d.status === 'confirmed') {
                document.getElementById('pending-view').style.display = 'none';
                document.getElementById('success-view').style.display = 'block';
                if (d.amount) document.getElementById('rent-amount').textContent = 'KES ' + Number(d.amount).toLocaleString();
                if (d.rent_month) document.getElementById('rent-month-label').textContent = d.rent_month + ' Rent âœ“';
                if (d.receipt) {
                    document.getElementById('rent-receipt-no').textContent = d.receipt;
                    document.getElementById('rent-receipt-box').style.display = 'block';
                }
                setTimeout(() => location.reload(), 4000);
            } else if (d.status === 'failed') {
                document.getElementById('pending-view').style.display = 'none';
                document.getElementById('pay-form').style.display = 'block';
                const err = document.getElementById('err-msg');
                err.textContent = 'Payment failed. Try again.';
                err.style.display = 'block';
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


