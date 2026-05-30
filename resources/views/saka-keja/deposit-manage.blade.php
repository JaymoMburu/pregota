<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Deposit · Saka Keja</title>
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh;padding:20px}
.card{max-width:460px;width:100%;margin:0 auto;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.09);border-radius:22px;padding:32px 26px}
.logo{font-size:18px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;display:block;margin-bottom:6px;text-decoration:none}
.brand{font-size:13px;font-weight:800;color:#f59e0b;display:block;margin-bottom:20px}

.status-held{background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.2);border-radius:13px;padding:16px;margin-bottom:22px;text-align:center}
.status-confirmed{background:rgba(74,222,128,.08);border:1px solid rgba(74,222,128,.2);border-radius:13px;padding:16px;margin-bottom:22px;text-align:center}
.status-refunded{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.1);border-radius:13px;padding:16px;margin-bottom:22px;text-align:center}
.status-taken{background:rgba(239,68,68,.06);border:1px solid rgba(239,68,68,.15);border-radius:13px;padding:16px;margin-bottom:22px;text-align:center}
.status-icon{font-size:36px;margin-bottom:8px}
.status-title{font-size:17px;font-weight:900;margin-bottom:4px}
.status-sub{font-size:13px;color:rgba(255,255,255,.45);line-height:1.6}

.listing-info{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:13px;padding:14px;margin-bottom:22px}
.listing-type{font-size:11px;font-weight:700;color:#f59e0b;margin-bottom:3px}
.listing-loc{font-size:16px;font-weight:800}
.listing-rent{font-size:13px;color:rgba(255,255,255,.45);margin-top:3px}

.amount-held{text-align:center;margin-bottom:22px;padding:16px;background:rgba(74,222,128,.05);border:1px solid rgba(74,222,128,.12);border-radius:13px}
.amount-label{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.35);margin-bottom:6px}
.amount-val{font-size:28px;font-weight:900;color:#4ADE80}
.amount-sub{font-size:12px;color:rgba(255,255,255,.35);margin-top:4px}

.confirm-section{margin-bottom:16px}
.declaration{background:rgba(245,158,11,.05);border:1px solid rgba(245,158,11,.15);border-radius:11px;padding:14px;margin-bottom:14px;font-size:13px;color:rgba(255,255,255,.6);line-height:1.7}
.declaration strong{color:rgba(255,255,255,.85)}
.checkbox-row{display:flex;align-items:flex-start;gap:10px;margin-bottom:16px;cursor:pointer}
.checkbox-row input{width:18px;height:18px;margin-top:2px;flex-shrink:0;accent-color:#f59e0b;cursor:pointer}
.checkbox-label{font-size:13px;color:rgba(255,255,255,.65);line-height:1.5}

.btn{width:100%;padding:15px;background:linear-gradient(135deg,#16a34a,#22c55e);color:#fff;font-size:15px;font-weight:900;border:none;border-radius:13px;cursor:pointer;margin-bottom:10px;transition:.15s}
.btn:disabled{opacity:.35;cursor:not-allowed}
.btn-cancel{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.1);color:rgba(255,255,255,.45);font-size:13px;font-weight:700;padding:12px;border-radius:11px;cursor:pointer;width:100%}
.btn-cancel:hover{background:rgba(239,68,68,.08);border-color:rgba(239,68,68,.2);color:#fca5a5}
.dispute-note{font-size:11px;color:rgba(255,255,255,.3);text-align:center;margin-top:12px;line-height:1.6}

.success-view{display:none;text-align:center;padding:10px 0}
.success-icon{font-size:48px;margin-bottom:12px}
</style>
</head>
<body>
<div class="card">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    <span class="brand">🏠 Saka Keja — My Deposit</span>

    <div class="listing-info">
        <div class="listing-type">{{ $deposit->listing->unitLabel() }}</div>
        <div class="listing-loc">{{ $deposit->listing->location }}</div>
        <div class="listing-rent">KES {{ number_format($deposit->listing->rent) }}/month</div>
    </div>

    @if($deposit->status === 'confirmed')
        <div class="status-confirmed">
            <div class="status-icon">✅</div>
            <div class="status-title">You're moving in!</div>
            <div class="status-sub">Deposit released to landlord. Receipt: {{ $deposit->receipt_number }}<br>Confirmed on {{ $deposit->confirmed_at->format('d M Y, H:i') }}</div>
        </div>

    @elseif($deposit->status === 'refunded')
        <div class="status-refunded">
            <div class="status-icon">↩️</div>
            <div class="status-title">Deposit Refunded</div>
            <div class="status-sub">Your KES {{ number_format($deposit->deposit_amount) }} deposit has been refunded. The KES 200 escrow fee is retained by Pregota.</div>
        </div>
        <a href="{{ route('saka-keja.browse') }}" style="display:block;text-align:center;padding:14px;background:linear-gradient(135deg,#d97706,#f59e0b);border-radius:13px;color:#0B141A;font-weight:800;text-decoration:none;font-size:14px">Browse Other Houses →</a>

    @elseif($deposit->listing->status === 'taken' && $deposit->status === 'held')
        <div class="status-taken">
            <div class="status-icon">🏠</div>
            <div class="status-title">House Already Taken</div>
            <div class="status-sub">Another seeker confirmed first. Your KES {{ number_format($deposit->deposit_amount) }} deposit will be refunded to your M-Pesa shortly.</div>
        </div>
        <a href="{{ route('saka-keja.browse') }}" style="display:block;text-align:center;padding:14px;background:linear-gradient(135deg,#d97706,#f59e0b);border-radius:13px;color:#0B141A;font-weight:800;text-decoration:none;font-size:14px">Browse Other Houses →</a>

    @else
        <div class="status-held">
            <div class="status-icon">🔒</div>
            <div class="status-title">KES {{ number_format($deposit->deposit_amount) }} Secured with Pregota</div>
            <div class="status-sub">Your deposit is held safely. Visit the house, then confirm below to release to landlord.</div>
        </div>

        <div class="amount-held">
            <div class="amount-label">Held by Pregota</div>
            <div class="amount-val">KES {{ number_format($deposit->deposit_amount) }}</div>
            <div class="amount-sub">Released to landlord only when you confirm</div>
        </div>

        <div id="confirm-section" class="confirm-section">
            <div class="declaration">
                By confirming, I, <strong>{{ $deposit->seeker_name }}</strong>, declare that I have <strong>physically visited the property at {{ $deposit->listing->location }}</strong> and agree to release my deposit of <strong>KES {{ number_format($deposit->deposit_amount) }}</strong> to the landlord via Pregota on <strong>{{ now()->format('d M Y') }}</strong>.
            </div>

            <label class="checkbox-row">
                <input type="checkbox" id="visited-check" onchange="checkDeclaration()">
                <span class="checkbox-label">I confirm I have physically visited this property and it matches what was listed.</span>
            </label>

            <button class="btn" id="confirm-btn" disabled onclick="doConfirm()">✓ I'm Moving In — Release Deposit to Landlord</button>
            <button class="btn-cancel" onclick="doCancel()">I changed my mind — Refund my deposit</button>
            <div class="dispute-note">After confirming, you have 24 hours to raise a dispute if the house does not match what was advertised.</div>
        </div>

        <div class="success-view" id="success-view">
            <div class="success-icon">✅</div>
            <div style="font-size:18px;font-weight:900;margin-bottom:6px;color:#4ADE80">Confirmed!</div>
            <div style="font-size:13px;color:rgba(255,255,255,.45);line-height:1.6">Deposit released to landlord. Welcome to your new home!</div>
        </div>
    @endif

    <div style="text-align:center;margin-top:20px">
        <a href="{{ route('saka-keja.browse') }}" style="font-size:12px;color:rgba(255,255,255,.3);text-decoration:none">← Browse other listings</a>
    </div>
</div>

<script>
const CSRF = '{{ csrf_token() }}';

function checkDeclaration() {
    document.getElementById('confirm-btn').disabled = !document.getElementById('visited-check').checked;
}

async function doConfirm() {
    if (!confirm('Confirm you are moving in? This will release KES {{ number_format($deposit->deposit_amount) }} to the landlord.')) return;

    document.getElementById('confirm-btn').disabled = true;

    const res  = await fetch('{{ route("saka-keja.deposit.confirm", $deposit->token) }}', {
        method: 'POST', headers: {'X-CSRF-TOKEN': CSRF}
    });
    const data = await res.json();

    if (data.success) {
        document.getElementById('confirm-section').style.display = 'none';
        document.getElementById('success-view').style.display = 'block';
        setTimeout(() => location.reload(), 3000);
    } else {
        alert(data.message || 'Could not confirm. Please try again.');
        document.getElementById('confirm-btn').disabled = false;
    }
}

async function doCancel() {
    if (!confirm('Cancel and get your deposit refunded? The KES 200 escrow fee is not refunded.')) return;

    const res  = await fetch('{{ route("saka-keja.deposit.cancel", $deposit->token) }}', {
        method: 'POST', headers: {'X-CSRF-TOKEN': CSRF}
    });
    const data = await res.json();

    if (data.success) location.reload();
}
</script>
</body>
</html>
