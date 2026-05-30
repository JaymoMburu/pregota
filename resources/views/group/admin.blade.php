﻿﻿<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $group->name }} Admin — Pregota</title>
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}input,textarea,select,button{font-family:inherit;font-size:inherit}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh}
.nav{padding:14px 24px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.07)}
.logo{font-size:20px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.wrap{max-width:640px;margin:0 auto;padding:32px 20px 80px}
h1{font-size:22px;font-weight:900;margin-bottom:4px}
.period-label{font-size:12px;color:rgba(255,255,255,.72);margin-bottom:24px}

/* Auth card */
.auth-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.09);border-radius:18px;padding:28px 24px;margin-bottom:24px}
.field label{display:block;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.72);margin-bottom:7px}
.field input{width:100%;padding:12px 14px;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.12);border-radius:11px;color:#fff;font-size:15px;font-family:inherit;outline:none;margin-bottom:14px}
.field input:focus{border-color:rgba(37,211,102,.4)}
.pin-row{display:flex;gap:10px;justify-content:center;margin-bottom:14px}
.pin-box{width:52px;height:60px;background:rgba(255,255,255,.07);border:2px solid rgba(255,255,255,.12);border-radius:12px;font-size:26px;font-weight:900;text-align:center;color:#fff;outline:none;caret-color:transparent;font-family:monospace}
.pin-box:focus{border-color:rgba(37,211,102,.5)}
.action-btn{width:100%;padding:13px;background:linear-gradient(135deg,#25D366,#1aaa52);color:#fff;font-size:15px;font-weight:800;border:none;border-radius:12px;cursor:pointer}
.err{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);border-radius:9px;padding:10px 14px;font-size:13px;color:#fca5a5;margin-top:12px;display:none}

/* Stats */
.stats{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:24px}
.stat{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);border-radius:12px;padding:14px;text-align:center}
.stat-val{font-size:24px;font-weight:900;color:#4ADE80}
.stat-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:rgba(255,255,255,.65);margin-top:3px}

/* Member list */
.member-row{display:flex;justify-content:space-between;align-items:center;padding:13px 16px;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);border-radius:12px;margin-bottom:8px}
.member-row.paid{border-color:rgba(37,211,102,.2);background:rgba(37,211,102,.04)}
.member-left{display:flex;align-items:center;gap:10px}
.status-dot{width:9px;height:9px;border-radius:50%}
.dot-paid{background:#4ADE80}
.dot-pending{background:#fbbf24}
.dot-failed{background:#f87171}
.member-amount{font-size:15px;font-weight:800}
.member-date{font-size:11px;color:rgba(255,255,255,.65);margin-top:2px}
.receipt-tag{font-size:11px;font-family:monospace;color:#a78bfa;text-decoration:none}
.copy-btn{font-size:11px;padding:4px 10px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:6px;color:rgba(255,255,255,.78);cursor:pointer}
.copy-btn:hover{background:rgba(255,255,255,.1);color:#fff}

.share-link-box{background:rgba(37,211,102,.05);border:1px solid rgba(37,211,102,.15);border-radius:13px;padding:16px 18px;margin-bottom:20px}
.share-url{font-family:monospace;font-size:13px;color:#4ADE80;word-break:break-all;margin-bottom:10px}
.copy-link-btn{padding:7px 16px;background:rgba(37,211,102,.12);border:1px solid rgba(37,211,102,.25);color:#4ADE80;font-size:13px;font-weight:700;border-radius:8px;cursor:pointer}
</style>
</head>
<body>
<nav class="nav">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    <a href="{{ route('group.show', $group->slug) }}" style="font-size:13px;color:rgba(255,255,255,.72);text-decoration:none">Member Link →</a>
</nav>

<div class="wrap">
    <h1>{{ $group->name }}</h1>
    <div class="period-label">Period: {{ $period }} · {{ ucfirst($group->frequency) }}</div>

    @if(!$isAdmin)
    <div class="auth-card" id="auth-card">
        <div style="font-size:17px;font-weight:900;margin-bottom:6px">🔐 Admin Access</div>
        <div style="font-size:13px;color:rgba(255,255,255,.78);margin-bottom:20px">Enter your admin phone and PIN to view member payments.</div>
        <div class="field">
            <label>Admin Phone</label>
            <input type="tel" id="admin-phone" placeholder="0712 345 678" autocomplete="tel">
        </div>
        <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.72);margin-bottom:8px">Admin PIN</div>
        <div class="pin-row" id="pin-boxes"></div>
        <button class="action-btn" id="login-btn" onclick="adminLogin()" disabled>Unlock →</button>
        <div class="err" id="auth-err"></div>
    </div>
    @endif

    <div id="admin-panel" style="{{ $isAdmin ? '' : 'display:none' }}">
        <div class="share-link-box">
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(37,211,102,.6);margin-bottom:6px">Member Payment Link</div>
            <div class="share-url" id="member-url">{{ url('/group/' . $group->slug) }}</div>
            <button class="copy-link-btn" onclick="copyMemberLink()">📋 Copy Link</button>
        </div>

        @php
            $confirmed = $payments->where('status', 'confirmed');
            $pending   = $payments->where('status', 'pending');
        @endphp

        <div class="stats">
            <div class="stat"><div class="stat-val">{{ $confirmed->count() }}</div><div class="stat-label">Paid</div></div>
            <div class="stat"><div class="stat-val">KES {{ number_format($confirmed->sum('amount')) }}</div><div class="stat-label">Collected</div></div>
            <div class="stat"><div class="stat-val">{{ $pending->count() }}</div><div class="stat-label">Pending</div></div>
        </div>

        <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.65);margin-bottom:12px">Member Payments</div>

        @if($payments->isEmpty())
            <div style="text-align:center;padding:40px;color:rgba(255,255,255,.65)">No payments yet for this period.</div>
        @else
            @foreach($payments as $p)
            <div class="member-row {{ $p->status === 'confirmed' ? 'paid' : '' }}">
                <div class="member-left">
                    <div class="status-dot dot-{{ $p->status === 'confirmed' ? 'paid' : ($p->status === 'failed' ? 'failed' : 'pending') }}"></div>
                    <div>
                        <div class="member-amount">KES {{ number_format($p->amount) }}</div>
                        <div class="member-date">{{ $p->updated_at->format('d M Y · H:i') }}</div>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:8px">
                    @if($p->receipt_number)
                        <a href="{{ route('receipt.show', $p->receipt_number) }}" class="receipt-tag" target="_blank">{{ $p->receipt_number }}</a>
                    @endif
                    <button class="copy-btn" onclick="copyReminder('{{ url('/group/reminder/' . $p->reminder_token) }}')">Copy Reminder Link</button>
                </div>
            </div>
            @endforeach
        @endif
    </div>
</div>

<script>
const CSRF = '{{ csrf_token() }}';
const SLUG = '{{ $group->slug }}';

@if(!$isAdmin)
function makePinBoxes(containerId, onComplete) {
    const wrap = document.getElementById(containerId);
    wrap.innerHTML = '';
    for (let i = 0; i < 4; i++) {
        const inp = document.createElement('input');
        inp.type = 'password'; inp.maxLength = 1; inp.inputMode = 'numeric';
        inp.pattern = '[0-9]'; inp.className = 'pin-box';
        inp.addEventListener('input', () => {
            inp.value = inp.value.replace(/\D/g, '');
            if (inp.value && inp.nextElementSibling) inp.nextElementSibling.focus();
            onComplete();
        });
        inp.addEventListener('keydown', e => {
            if (e.key === 'Backspace' && !inp.value && inp.previousElementSibling) inp.previousElementSibling.focus();
        });
        wrap.appendChild(inp);
    }
}
function getPinValue(id) {
    return Array.from(document.getElementById(id).querySelectorAll('input')).map(i => i.value).join('');
}
makePinBoxes('pin-boxes', () => {
    const p = getPinValue('pin-boxes');
    document.getElementById('login-btn').disabled = p.length < 4;
    if (p.length === 4) adminLogin();
});

async function adminLogin() {
    const phone = document.getElementById('admin-phone').value.trim();
    const pin   = getPinValue('pin-boxes');
    const errEl = document.getElementById('auth-err');
    errEl.style.display = 'none';

    const res  = await fetch(`/group/${SLUG}/admin-login`, {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
        body: JSON.stringify({phone, pin}),
    });
    const data = await res.json();
    if (data.success) {
        document.getElementById('auth-card').style.display = 'none';
        document.getElementById('admin-panel').style.display = 'block';
    } else {
        errEl.textContent = data.message || 'Incorrect credentials.';
        errEl.style.display = 'block';
    }
}
@endif

function copyMemberLink() {
    navigator.clipboard.writeText(document.getElementById('member-url').textContent.trim());
    alert('Member link copied!');
}
function copyReminder(url) {
    navigator.clipboard.writeText(url);
    alert('Reminder link copied! Send it via WhatsApp to the member.');
}
</script>
</body>
</html>
