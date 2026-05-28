<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Deni — Creditor Access · Pregota</title>
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px}
.card{max-width:400px;width:100%;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.09);border-radius:22px;padding:36px 28px}
.logo{font-size:18px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;display:block;margin-bottom:28px;text-decoration:none}
.icon{font-size:40px;margin-bottom:12px}
.title{font-size:22px;font-weight:900;margin-bottom:6px}
.sub{font-size:13px;color:rgba(255,255,255,.5);margin-bottom:28px;line-height:1.6}
.field{margin-bottom:14px}
.field label{display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.4);margin-bottom:7px}
.field input{width:100%;padding:13px 14px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:11px;color:#fff;font-size:15px;outline:none;font-family:inherit;transition:.2s}
.field input:focus{border-color:rgba(239,68,68,.4);background:rgba(239,68,68,.04)}
.btn{width:100%;padding:14px;background:linear-gradient(135deg,#dc2626,#ef4444);color:#fff;font-size:15px;font-weight:900;border:none;border-radius:13px;cursor:pointer;margin-top:6px}
.btn:disabled{opacity:.45;cursor:not-allowed}
.note{font-size:11px;color:rgba(255,255,255,.3);text-align:center;margin-top:14px;line-height:1.6}
.err{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);border-radius:9px;padding:10px 14px;font-size:13px;color:#fca5a5;margin-top:12px;display:none}
.pending{display:none;text-align:center;padding:20px 0}
.spinner{width:44px;height:44px;border:3px solid rgba(255,255,255,.1);border-top-color:#ef4444;border-radius:50%;animation:spin .8s linear infinite;margin:0 auto 16px}
@keyframes spin{to{transform:rotate(360deg)}}
.use-cases{display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-bottom:24px}
.uc{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:10px;padding:10px 8px;text-align:center}
.uc-icon{font-size:22px;margin-bottom:4px}
.uc-label{font-size:11px;color:rgba(255,255,255,.5)}
</style>
</head>
<body>
<div class="card">
    <a href="{{ route('home') }}" class="logo">Pregota</a>

    <div id="login-view">
        <div class="icon">🧾</div>
        <div class="title">Deni Dashboard</div>
        <div class="sub">Track all your madeni in one place. Pay KES 2 to verify your number and access your account.</div>

        <div class="use-cases">
            <div class="uc"><div class="uc-icon">🏍️</div><div class="uc-label">Boda Boda</div></div>
            <div class="uc"><div class="uc-icon">🏪</div><div class="uc-label">Vibanda</div></div>
            <div class="uc"><div class="uc-icon">🤝</div><div class="uc-label">Friends & Family</div></div>
        </div>

        <div class="field">
            <label>Your Name or Business</label>
            <input type="text" id="display_name" placeholder="e.g. Mama Njeri, Kamau Boda" maxlength="100">
        </div>
        <div class="field">
            <label>Your M-Pesa Number</label>
            <input type="tel" id="phone" placeholder="07XX XXX XXX" autocomplete="tel">
        </div>
        <div class="err" id="err-msg"></div>
        <button class="btn" id="auth-btn" onclick="doAuth()">Get Access — KES 20 / day →</button>
        <div class="note">KES 20 is charged once per day via M-Pesa. Access your dashboard all day until midnight — no password needed.</div>
    </div>

    <div class="pending" id="pending-view">
        <div class="spinner"></div>
        <div style="font-size:15px;font-weight:700;margin-bottom:6px">Check your phone</div>
        <div style="font-size:13px;color:rgba(255,255,255,.45)">Enter your M-Pesa PIN to confirm KES 2 and access your Deni dashboard.</div>
    </div>
</div>

<script>
const CSRF = '{{ csrf_token() }}';
let checkoutId = null;

async function doAuth() {
    const name  = document.getElementById('display_name').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const err   = document.getElementById('err-msg');
    err.style.display = 'none';

    if (!name) { err.textContent = 'Enter your name or business name.'; err.style.display = 'block'; return; }
    if (!phone || !/^(\+?254|0)[17]\d{8}$/.test(phone)) { err.textContent = 'Enter a valid Safaricom number.'; err.style.display = 'block'; return; }

    document.getElementById('auth-btn').disabled = true;

    const res  = await fetch('{{ route("creditor.auth") }}', {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
        body: JSON.stringify({phone, display_name: name}),
    });
    const data = await res.json();

    if (!res.ok || !data.success) {
        err.textContent = data.message || 'Something went wrong.';
        err.style.display = 'block';
        document.getElementById('auth-btn').disabled = false;
        return;
    }

    checkoutId = data.checkout_request_id;
    document.getElementById('login-view').style.display = 'none';
    document.getElementById('pending-view').style.display = 'block';
    poll();
}

function poll() {
    fetch('{{ route("creditor.poll") }}?checkout_request_id=' + checkoutId)
        .then(r => r.json())
        .then(d => {
            if (d.status === 'confirmed') {
                window.location.href = d.redirect;
            } else if (d.status === 'failed') {
                document.getElementById('pending-view').style.display = 'none';
                document.getElementById('login-view').style.display = 'block';
                const err = document.getElementById('err-msg');
                err.textContent = 'Payment failed or cancelled. Please try again.';
                err.style.display = 'block';
                document.getElementById('auth-btn').disabled = false;
            } else {
                setTimeout(poll, 2500);
            }
        })
        .catch(() => setTimeout(poll, 3000));
}
</script>
</body>
</html>
