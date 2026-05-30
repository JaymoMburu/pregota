<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Verify Phone â€” {{ $collection->school_name }}</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
*{box-sizing:border-box;margin:0;padding:0}input,textarea,select,button{font-family:inherit;font-size:inherit}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh;display:flex;flex-direction:column}
.topbar{padding:14px 20px;display:flex;align-items:center;border-bottom:1px solid rgba(255,255,255,.07);background:#0B141A}
.logo{font-size:18px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}

.page{flex:1;display:flex;align-items:center;justify-content:center;padding:32px 20px}
.card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.09);border-radius:18px;padding:36px 32px;max-width:440px;width:100%;text-align:center}

.icon-ring{width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,rgba(0,166,81,.25),rgba(59,130,246,.15));border:2px solid rgba(0,166,81,.35);display:flex;align-items:center;justify-content:center;font-size:30px;margin:0 auto 20px}

h1{font-size:22px;font-weight:900;margin-bottom:8px}
.sub{font-size:14px;color:rgba(255,255,255,.72);line-height:1.55;margin-bottom:28px}
.sub strong{color:rgba(255,255,255,.75)}

.step-list{text-align:left;display:flex;flex-direction:column;gap:10px;margin-bottom:28px}
.step{display:flex;align-items:flex-start;gap:12px;font-size:13px;color:rgba(255,255,255,.65);line-height:1.45}
.step-num{width:22px;height:22px;border-radius:50%;background:rgba(0,166,81,.2);border:1px solid rgba(0,166,81,.35);color:#25D366;font-size:11px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:1px}

.status-box{padding:16px;border-radius:12px;font-size:13px;font-weight:600;margin-bottom:20px}
.status-box.waiting{background:rgba(251,191,36,.07);border:1px solid rgba(251,191,36,.2);color:#fbbf24}
.status-box.success{background:rgba(34,197,94,.08);border:1px solid rgba(34,197,94,.25);color:#4ade80}

.spin-inline{display:inline-block;width:14px;height:14px;border:2px solid rgba(251,191,36,.25);border-top-color:#fbbf24;border-radius:50%;animation:spin .8s linear infinite;vertical-align:middle;margin-right:6px}
@keyframes spin{to{transform:rotate(360deg)}}

.resend-btn{background:none;border:1px solid rgba(255,255,255,.12);border-radius:8px;color:rgba(255,255,255,.72);font-size:12px;padding:8px 16px;cursor:pointer;transition:.15s}
.resend-btn:hover{border-color:rgba(255,255,255,.25);color:rgba(255,255,255,.7)}
.resend-btn:disabled{opacity:.4;cursor:not-allowed}

.notice{margin-top:24px;font-size:11px;color:rgba(255,255,255,.25);line-height:1.5}
</style>
</head>
<body>

<div class="topbar">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
</div>

<div class="page">
    <div class="card">
        <div class="icon-ring">ðŸ“±</div>
        <h1>Verify Your Phone</h1>
        <p class="sub">
            We sent a <strong>KES 1 STK Push</strong> to your registered payout number.
            Approve it on your phone to verify ownership and activate this collection.
        </p>

        <div class="step-list">
            <div class="step">
                <div class="step-num">1</div>
                <span>Check your phone for an M-Pesa prompt from Pregota</span>
            </div>
            <div class="step">
                <div class="step-num">2</div>
                <span>Enter your M-Pesa PIN to approve the KES 1 charge</span>
            </div>
            <div class="step">
                <div class="step-num">3</div>
                <span>This page will automatically redirect to your admin dashboard</span>
            </div>
        </div>

        <div class="status-box waiting" id="statusBox">
            <span class="spin-inline" id="spinner"></span>
            <span id="statusText">Waiting for verificationâ€¦</span>
        </div>

        <button class="resend-btn" id="resendBtn" onclick="resend()">
            Didn't receive it? Resend STK Push
        </button>

        <p class="notice">The KES 1 goes to Pregota. It is a one-time verification charge â€” not part of your payout.</p>
    </div>
</div>

<script>
const SLUG        = '{{ $collection->slug }}';
const TOKEN       = '{{ $adminToken }}';
const CSRF        = document.querySelector('meta[name=csrf-token]').content;
const ADMIN_URL   = '{{ route('school-collection.admin', ['slug' => $collection->slug]) }}?token={{ $adminToken }}';
let pollTimer;
let resendCooldown;

function poll() {
    pollTimer = setTimeout(async () => {
        try {
            const res  = await fetch(`/school-collection/${SLUG}/verify-status`);
            const data = await res.json();
            if (data.verified) {
                document.getElementById('statusBox').className = 'status-box success';
                document.getElementById('spinner').style.display = 'none';
                document.getElementById('statusText').textContent = 'âœ… Verified! Redirecting to your dashboardâ€¦';
                setTimeout(() => { window.location.href = ADMIN_URL; }, 1500);
                return;
            }
            poll();
        } catch(e) { poll(); }
    }, 2500);
}

async function resend() {
    const btn = document.getElementById('resendBtn');
    btn.disabled = true;
    btn.textContent = 'Sendingâ€¦';

    try {
        await fetch(`/school-collection/${SLUG}/resend-verify?token=${TOKEN}`, {
            method:  'POST',
            headers: { 'X-CSRF-TOKEN': CSRF },
        });
        btn.textContent = 'Sent! Check your phone.';
        resendCooldown = setTimeout(() => {
            btn.disabled    = false;
            btn.textContent = 'Didn\'t receive it? Resend STK Push';
        }, 30000);
    } catch(e) {
        btn.disabled    = false;
        btn.textContent = 'Resend failed. Try again.';
    }
}

poll();
</script>
</body>
</html>

