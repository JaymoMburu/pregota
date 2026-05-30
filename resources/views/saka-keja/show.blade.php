<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $listing->unitLabel() }} — {{ $listing->location }} · Saka Keja</title>
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh}
.nav{padding:14px 20px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.07)}
.logo{font-size:20px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.back{font-size:13px;color:rgba(255,255,255,.4);text-decoration:none}
.back:hover{color:rgba(255,255,255,.7)}
.wrap{max-width:620px;margin:0 auto;padding:24px 16px 80px}

.gallery{display:flex;gap:8px;overflow-x:auto;margin-bottom:24px;padding-bottom:4px;scrollbar-width:none}
.gallery::-webkit-scrollbar{display:none}
.gallery img{height:220px;width:auto;min-width:280px;border-radius:14px;object-fit:cover;flex-shrink:0}
.gallery-placeholder{width:100%;height:200px;background:rgba(255,255,255,.04);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:56px;color:rgba(255,255,255,.15);margin-bottom:24px}

.badge-row{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:16px}
.badge{display:inline-flex;padding:4px 12px;border-radius:999px;font-size:12px;font-weight:700}
.badge-type{background:rgba(245,158,11,.12);border:1px solid rgba(245,158,11,.25);color:#f59e0b}
.badge-loc{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);color:rgba(255,255,255,.6)}

.rent-line{margin-bottom:6px}
.rent-val{font-size:32px;font-weight:900;color:#4ADE80}
.rent-label{font-size:14px;color:rgba(255,255,255,.4);margin-left:6px}

.desc{font-size:14px;color:rgba(255,255,255,.55);line-height:1.7;margin-bottom:28px}

.connect-box{background:rgba(245,158,11,.05);border:1px solid rgba(245,158,11,.2);border-radius:18px;padding:22px}
.connect-title{font-size:18px;font-weight:900;margin-bottom:6px}
.connect-sub{font-size:13px;color:rgba(255,255,255,.45);margin-bottom:20px;line-height:1.6}
.field{margin-bottom:14px}
.field label{display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.4);margin-bottom:7px}
.field input{width:100%;padding:13px 14px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:11px;color:#fff;font-size:15px;outline:none;font-family:inherit;transition:.2s}
.field input:focus{border-color:rgba(245,158,11,.4);background:rgba(245,158,11,.04)}
.btn{width:100%;padding:15px;background:linear-gradient(135deg,#d97706,#f59e0b);color:#0B141A;font-size:15px;font-weight:900;border:none;border-radius:13px;cursor:pointer;transition:.15s}
.btn:disabled{opacity:.45;cursor:not-allowed}
.err{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);border-radius:9px;padding:10px 14px;font-size:13px;color:#fca5a5;margin-top:12px;display:none}
.pending{display:none;text-align:center;padding:20px 0}
.spinner{width:44px;height:44px;border:3px solid rgba(255,255,255,.1);border-top-color:#f59e0b;border-radius:50%;animation:spin .8s linear infinite;margin:0 auto 16px}
@keyframes spin{to{transform:rotate(360deg)}}
.success{display:none;text-align:center;padding:20px 0}
.success-icon{font-size:48px;margin-bottom:12px}
.success-title{font-size:18px;font-weight:900;margin-bottom:6px;color:#4ADE80}
.success-sub{font-size:13px;color:rgba(255,255,255,.45);line-height:1.6}
.note{font-size:11px;color:rgba(255,255,255,.3);text-align:center;margin-top:12px;line-height:1.6}
</style>
</head>
<body>
<nav class="nav">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    <a href="{{ route('saka-keja.browse') }}" class="back">← Back to listings</a>
</nav>

<div class="wrap">
    @if($listing->photos && count($listing->photos))
    <div class="gallery">
        @foreach($listing->photos as $photo)
        <img src="{{ asset('uploads/saka-keja/' . $listing->id . '/' . $photo) }}" alt="{{ $listing->location }}">
        @endforeach
    </div>
    @else
    <div class="gallery-placeholder">🏠</div>
    @endif

    <div class="badge-row">
        <span class="badge badge-type">{{ $listing->unitLabel() }}</span>
        <span class="badge badge-loc">📍 {{ $listing->location }}</span>
    </div>

    <div class="rent-line">
        <span class="rent-val">KES {{ number_format($listing->rent) }}</span>
        <span class="rent-label">/ month</span>
    </div>

    @if($listing->description)
    <div class="desc" style="margin-top:12px">{{ $listing->description }}</div>
    @endif

    @php $secureTotal = $listing->totalSecureAmount() + 200; @endphp
    <div style="background:rgba(74,222,128,.05);border:1px solid rgba(74,222,128,.15);border-radius:16px;padding:18px;margin-bottom:14px">
        <div style="font-size:16px;font-weight:900;margin-bottom:6px">🔒 Secure this house</div>
        <div style="font-size:13px;color:rgba(255,255,255,.45);margin-bottom:14px;line-height:1.6">Pay KES {{ number_format($secureTotal) }} to Pregota escrow. Your money is held safely — released to landlord only when you confirm you're moving in.</div>
        <a href="{{ route('saka-keja.deposit', $listing->id) }}" style="display:block;text-align:center;padding:13px;background:linear-gradient(135deg,#16a34a,#22c55e);border-radius:11px;color:#fff;font-weight:800;text-decoration:none;font-size:14px">Secure — KES {{ number_format($secureTotal) }} →</a>
    </div>

    <div class="connect-box">
        <div class="connect-title">Just want to view first?</div>
        <div class="connect-sub">Pay KES 200 to get the landlord's contact. They will call you to arrange a viewing. No deposit required yet.</div>

        <div id="connect-form">
            <div class="field">
                <label>Your Name</label>
                <input type="text" id="seeker_name" placeholder="e.g. James Kamau" maxlength="100">
            </div>
            <div class="field">
                <label>Your M-Pesa Number</label>
                <input type="tel" id="phone" placeholder="07XX XXX XXX" autocomplete="tel">
            </div>
            <div class="err" id="err-msg"></div>
            <button class="btn" id="connect-btn" onclick="doConnect()">Connect — KES 200 via M-Pesa →</button>
            <div class="note">STK Push will appear on your phone. Enter your M-Pesa PIN to confirm.</div>
        </div>

        <div class="pending" id="pending-view">
            <div class="spinner"></div>
            <div style="font-size:15px;font-weight:700;margin-bottom:6px">Check your phone</div>
            <div style="font-size:13px;color:rgba(255,255,255,.45)">Enter your M-Pesa PIN to confirm the KES 200 connection fee.</div>
        </div>

        <div class="success" id="success-view">
            <div class="success-icon">✅</div>
            <div class="success-title">Connected!</div>
            <div class="success-sub">Your number has been shared with the landlord.<br>Expect a call soon to arrange a viewing.</div>
        </div>
    </div>
</div>

<script>
const CSRF = '{{ csrf_token() }}';
let checkoutId = null;

async function doConnect() {
    const name  = document.getElementById('seeker_name').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const err   = document.getElementById('err-msg');
    err.style.display = 'none';

    if (!name) { err.textContent = 'Enter your name.'; err.style.display = 'block'; return; }
    if (!phone || !/^(\+?254|0)[17]\d{8}$/.test(phone)) { err.textContent = 'Enter a valid Safaricom number.'; err.style.display = 'block'; return; }

    document.getElementById('connect-btn').disabled = true;

    let data;
    try {
        const res = await fetch('{{ route("saka-keja.connect", $listing->id) }}', {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
            body: JSON.stringify({seeker_name: name, phone}),
        });
        data = await res.json();
    } catch(e) {
        err.textContent = 'Network error. Please try again.';
        err.style.display = 'block';
        document.getElementById('connect-btn').disabled = false;
        return;
    }

    if (!data.success) {
        err.textContent = data.message || 'Something went wrong.';
        err.style.display = 'block';
        document.getElementById('connect-btn').disabled = false;
        return;
    }

    checkoutId = data.checkout_request_id;
    document.getElementById('connect-form').style.display = 'none';
    document.getElementById('pending-view').style.display = 'block';
    pollConnect();
}

function pollConnect() {
    fetch('{{ route("saka-keja.connect.poll") }}?checkout_request_id=' + checkoutId)
        .then(r => r.json())
        .then(d => {
            if (d.status === 'confirmed') {
                document.getElementById('pending-view').style.display = 'none';
                document.getElementById('success-view').style.display = 'block';
            } else if (d.status === 'failed') {
                document.getElementById('pending-view').style.display = 'none';
                document.getElementById('connect-form').style.display = 'block';
                const err = document.getElementById('err-msg');
                err.textContent = 'Payment failed or cancelled. Please try again.';
                err.style.display = 'block';
                document.getElementById('connect-btn').disabled = false;
            } else {
                setTimeout(pollConnect, 2500);
            }
        })
        .catch(() => setTimeout(pollConnect, 3000));
}
</script>
</body>
</html>
