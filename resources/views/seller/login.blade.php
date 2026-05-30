<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pay Link Login â€” Pregota</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800;900&display=swap" rel="stylesheet">
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}input,textarea,select,button{font-family:inherit;font-size:inherit}
body{font-family:'Plus Jakarta Sans',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh;display:flex;flex-direction:column-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}
.nav{padding:16px 24px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.07)}
.logo{font-size:20px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.wrap{flex:1;display:flex;align-items:center;justify-content:center;padding:40px 24px}
.card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:20px;padding:36px;width:100%;max-width:400px}
h1{font-size:22px;font-weight:900;margin-bottom:6px}
.sub{font-size:13px;color:rgba(255,255,255,.5);margin-bottom:28px;line-height:1.6}
.field{margin-bottom:16px}
.field label{display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.4);margin-bottom:7px}
.field input{width:100%;padding:13px 14px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:11px;color:#fff;font-size:15px;outline:none;font-family:inherit;transition:.2s}
.field input:focus{border-color:rgba(37,211,102,.4);background:rgba(37,211,102,.04)}
.btn{width:100%;padding:14px;background:linear-gradient(135deg,#25D366,#1aaa52);color:#fff;font-size:15px;font-weight:900;border:none;border-radius:13px;cursor:pointer;transition:.2s}
.btn:hover{transform:translateY(-1px);box-shadow:0 8px 24px rgba(37,211,102,.3)}
.btn:disabled{opacity:.45;cursor:not-allowed;transform:none;box-shadow:none}
.note{font-size:11px;color:rgba(255,255,255,.3);text-align:center;margin-top:12px;line-height:1.6}
.err{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);border-radius:9px;padding:10px 14px;font-size:13px;color:#fca5a5;margin-top:12px;display:none}
.pending{display:none;text-align:center;padding:20px 0}
.spinner{width:44px;height:44px;border:3px solid rgba(255,255,255,.1);border-top-color:#25D366;border-radius:50%;animation:spin .8s linear infinite;margin:0 auto 16px}
@keyframes spin{to{transform:rotate(360deg)}}
.links{text-align:center;margin-top:20px;font-size:13px;color:rgba(255,255,255,.45)}
.links a{color:#25D366;text-decoration:none;font-weight:600}
</style>
</head>
<body>
<nav class="nav">
    <a href="{{ route('seller.landing') }}" class="logo">Pregota</a>
</nav>
<div class="wrap">
    <div class="card">
        <div id="login-view">
            <h1>Welcome back</h1>
            <div class="sub">Enter your handle and the M-Pesa number you registered with. We'll send an STK Push to verify â€” no password needed.</div>

            <div class="field">
                <label>Your Handle</label>
                <input type="text" id="handle" placeholder="yourshop" autocomplete="username">
            </div>
            <div class="field">
                <label>Your M-Pesa Number</label>
                <input type="tel" id="phone" placeholder="07XX XXX XXX" autocomplete="tel">
            </div>
            <div class="err" id="err-msg"></div>
            <button class="btn" id="login-btn" onclick="doLogin()">Login â€” Verify via M-Pesa â†’</button>
            <div class="note">KES 1 verification charge. Access is valid for 1 hour.</div>
        </div>

        <div class="pending" id="pending-view">
            <div class="spinner"></div>
            <div style="font-size:15px;font-weight:700;margin-bottom:6px">Check your phone</div>
            <div style="font-size:13px;color:rgba(255,255,255,.45)">Enter your M-Pesa PIN to confirm and access your pay link dashboard.</div>
        </div>

        <div class="links" id="links-row">
            Don't have a pay link yet? <a href="{{ route('seller.register') }}">Create one free</a>
        </div>
    </div>
</div>

<script>
const CSRF = '{{ csrf_token() }}';
let checkoutId = null;

async function doLogin() {
    const handle = document.getElementById('handle').value.trim().toLowerCase();
    const phone  = document.getElementById('phone').value.trim();
    const err    = document.getElementById('err-msg');
    err.style.display = 'none';

    if (!handle) { err.textContent = 'Enter your handle.'; err.style.display='block'; return; }
    if (!phone || !/^(\+?254|0)[17]\d{8}$/.test(phone)) { err.textContent = 'Enter a valid Safaricom number.'; err.style.display='block'; return; }

    document.getElementById('login-btn').disabled = true;

    let data;
    try {
        const res = await fetch('{{ route("seller.login.post") }}', {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
            body: JSON.stringify({handle, phone}),
        });
        data = await res.json();
    } catch(e) {
        err.textContent = 'Network error. Please try again.';
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
    document.getElementById('login-view').style.display  = 'none';
    document.getElementById('links-row').style.display   = 'none';
    document.getElementById('pending-view').style.display = 'block';
    pollLogin();
}

function pollLogin() {
    fetch('{{ route("seller.login.poll") }}?checkout_request_id=' + checkoutId)
        .then(r => r.json())
        .then(d => {
            if (d.status === 'confirmed') {
                window.location.href = d.redirect;
            } else if (d.status === 'failed') {
                document.getElementById('pending-view').style.display = 'none';
                document.getElementById('login-view').style.display   = 'block';
                document.getElementById('links-row').style.display    = 'block';
                const err = document.getElementById('err-msg');
                err.textContent = 'Payment failed or cancelled. Please try again.';
                err.style.display = 'block';
                document.getElementById('login-btn').disabled = false;
            } else {
                setTimeout(pollLogin, 2500);
            }
        })
        .catch(() => setTimeout(pollLogin, 3000));
}
</script>
</body>
</html>


