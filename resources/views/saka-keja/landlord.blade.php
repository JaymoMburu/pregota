<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Landlord Dashboard Â· Saka Keja</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800;900&display=swap" rel="stylesheet">
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Plus Jakarta Sans',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}
.card{max-width:400px;width:100%;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.09);border-radius:22px;padding:36px 28px}
.logo{font-size:18px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;display:block;margin-bottom:6px;text-decoration:none}
.brand{font-size:13px;font-weight:800;color:#f59e0b;display:block;margin-bottom:24px}
.icon{font-size:40px;margin-bottom:12px}
.title{font-size:22px;font-weight:900;margin-bottom:6px}
.sub{font-size:13px;color:rgba(255,255,255,.5);margin-bottom:28px;line-height:1.6}
.field{margin-bottom:14px}
.field label{display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.4);margin-bottom:7px}
.field input{width:100%;padding:13px 14px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:11px;color:#fff;font-size:15px;outline:none;font-family:inherit;transition:.2s}
.field input:focus{border-color:rgba(245,158,11,.4);background:rgba(245,158,11,.04)}
.btn{width:100%;padding:14px;background:linear-gradient(135deg,#d97706,#f59e0b);color:#0B141A;font-size:15px;font-weight:900;border:none;border-radius:13px;cursor:pointer;margin-top:6px}
.btn:disabled{opacity:.45;cursor:not-allowed}
.note{font-size:11px;color:rgba(255,255,255,.3);text-align:center;margin-top:14px;line-height:1.6}
.err{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);border-radius:9px;padding:10px 14px;font-size:13px;color:#fca5a5;margin-top:12px;display:none}
.pending{display:none;text-align:center;padding:20px 0}
.spinner{width:44px;height:44px;border:3px solid rgba(255,255,255,.1);border-top-color:#f59e0b;border-radius:50%;animation:spin .8s linear infinite;margin:0 auto 16px}
@keyframes spin{to{transform:rotate(360deg)}}
.browse-link{display:block;text-align:center;margin-top:18px;font-size:13px;color:rgba(255,255,255,.35);text-decoration:none}
.browse-link:hover{color:rgba(255,255,255,.6)}
</style>
</head>
<body>
<div class="card">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    <span class="brand">ðŸ  Saka Keja</span>

    <div id="login-view">
        <div class="icon">ðŸ”‘</div>
        <div class="title">Landlord Dashboard</div>
        <div class="sub">Enter your M-Pesa number to access your listings and see who wants to connect with you. KES 1 verification charge.</div>

        <div class="field">
            <label>Your M-Pesa Number</label>
            <input type="tel" id="phone" placeholder="07XX XXX XXX" autocomplete="tel">
        </div>
        <div class="err" id="err-msg"></div>
        <button class="btn" id="login-btn" onclick="doLogin()">Access Dashboard â†’</button>
        <div class="note">KES 1 is charged to verify your number. Access is valid for this session.</div>
    </div>

    <div class="pending" id="pending-view">
        <div class="spinner"></div>
        <div style="font-size:15px;font-weight:700;margin-bottom:6px">Check your phone</div>
        <div style="font-size:13px;color:rgba(255,255,255,.45)">Enter your M-Pesa PIN to verify and access your dashboard.</div>
    </div>

    <a href="{{ route('saka-keja.browse') }}" class="browse-link">â† Browse listings</a>
</div>

<script>
const CSRF = '{{ csrf_token() }}';
let checkoutId = null;

async function doLogin() {
    const phone = document.getElementById('phone').value.trim();
    const err   = document.getElementById('err-msg');
    err.style.display = 'none';

    if (!phone || !/^(\+?254|0)[17]\d{8}$/.test(phone)) {
        err.textContent = 'Enter a valid Safaricom number.';
        err.style.display = 'block';
        return;
    }

    document.getElementById('login-btn').disabled = true;

    let data;
    try {
        const res = await fetch('{{ route("saka-keja.landlord.auth") }}', {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
            body: JSON.stringify({phone}),
        });
        data = await res.json();
    } catch(e) {
        err.textContent = 'Network error. Try again.';
        err.style.display = 'block';
        document.getElementById('login-btn').disabled = false;
        return;
    }

    if (!data.success) {
        err.textContent = data.message || 'Something went wrong.';
        err.style.display = 'block';
        document.getElementById('login-btn').disabled = false;
        return;
    }

    checkoutId = data.checkout_request_id;
    document.getElementById('login-view').style.display = 'none';
    document.getElementById('pending-view').style.display = 'block';
    poll();
}

function poll() {
    fetch('{{ route("saka-keja.landlord.poll") }}?checkout_request_id=' + checkoutId)
        .then(r => r.json())
        .then(d => {
            if (d.status === 'confirmed') {
                window.location.href = d.redirect;
            } else if (d.status === 'failed') {
                document.getElementById('pending-view').style.display = 'none';
                document.getElementById('login-view').style.display = 'block';
                const err = document.getElementById('err-msg');
                err.textContent = 'Payment failed or cancelled. Try again.';
                err.style.display = 'block';
                document.getElementById('login-btn').disabled = false;
            } else {
                setTimeout(poll, 2500);
            }
        })
        .catch(() => setTimeout(poll, 3000));
}
</script>
</body>
</html>

