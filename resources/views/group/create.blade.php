<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Create Contribution Group â€” Pregota</title>
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}input,textarea,select,button{font-family:inherit;font-size:inherit}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh}
.nav{padding:14px 24px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.07)}
.logo{font-size:20px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.wrap{max-width:520px;margin:0 auto;padding:40px 20px 80px}
h1{font-size:24px;font-weight:900;margin-bottom:6px}
.sub{font-size:13px;color:rgba(255,255,255,.5);margin-bottom:30px;line-height:1.55}
.field{margin-bottom:18px}
.field label{display:block;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.45);margin-bottom:7px}
.field input,.field select,.field textarea{width:100%;padding:12px 14px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:10px;color:#fff;font-size:14px;font-family:inherit;outline:none}
.field input:focus,.field select:focus,.field textarea:focus{border-color:rgba(37,211,102,.4)}
.field .hint{font-size:11px;color:rgba(255,255,255,.3);margin-top:5px}
select option{background:#1a2730}
.field textarea{resize:vertical;min-height:80px}
.pin-row{display:flex;gap:10px;justify-content:center;margin-bottom:4px}
.pin-box{width:52px;height:60px;background:rgba(255,255,255,.07);border:2px solid rgba(255,255,255,.12);border-radius:12px;font-size:26px;font-weight:900;text-align:center;color:#fff;outline:none;caret-color:transparent;font-family:monospace}
.pin-box:focus{border-color:rgba(37,211,102,.5)}
.grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}
@media(max-width:480px){.grid{grid-template-columns:1fr}}
.submit-btn{width:100%;padding:14px;background:linear-gradient(135deg,#25D366,#1aaa52);color:#fff;font-size:16px;font-weight:900;border:none;border-radius:13px;cursor:pointer;margin-top:8px}
.submit-btn:hover{opacity:.9}
.err{color:#fca5a5;font-size:12px;margin-top:4px}
.section-title{font-size:13px;font-weight:700;color:rgba(255,255,255,.5);text-transform:uppercase;letter-spacing:.08em;margin:24px 0 14px;padding-top:20px;border-top:1px solid rgba(255,255,255,.06)}
</style>
</head>
<body>
<nav class="nav">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
</nav>
<div class="wrap">
    <h1>Create a Group</h1>
    <div class="sub">Chama, welfare, church, or any group that collects contributions. Members pay via M-Pesa â€” you see who's paid instantly.</div>

    <form method="POST" action="{{ route('group.create') }}">
        @csrf

        <div class="field">
            <label>Group Name</label>
            <input type="text" name="name" placeholder="e.g. Westlands Chama 2026" value="{{ old('name') }}" required maxlength="120">
            @error('name')<div class="err">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label>Description (optional)</label>
            <textarea name="description" placeholder="What is this group collecting for?">{{ old('description') }}</textarea>
        </div>

        <div class="grid">
            <div class="field">
                <label>Amount per Member (KES)</label>
                <input type="number" name="amount_per_member" placeholder="Leave blank for open" min="10" max="500000" value="{{ old('amount_per_member') }}">
                <div class="hint">Leave blank to let members enter their own amount</div>
            </div>
            <div class="field">
                <label>Frequency</label>
                <select name="frequency">
                    <option value="once" {{ old('frequency') == 'once' ? 'selected' : '' }}>One-time (Harambee)</option>
                    <option value="monthly" {{ old('frequency') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                    <option value="quarterly" {{ old('frequency') == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                    <option value="annually" {{ old('frequency','annually') == 'annually' ? 'selected' : '' }}>Annually</option>
                </select>
            </div>
        </div>

        <div class="field">
            <label>Next Due Date (optional)</label>
            <input type="date" name="next_due" value="{{ old('next_due') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
            <div class="hint">Used to remind members when payment is due</div>
        </div>

        <div class="section-title">Your Admin Access</div>

        <div class="field">
            <label>Your M-Pesa Number</label>
            <input type="tel" name="admin_phone" placeholder="0712 345 678" value="{{ old('admin_phone') }}" autocomplete="tel" required>
            @error('admin_phone')<div class="err">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label>Set a 4-digit Admin PIN</label>
            <div class="pin-row" id="pin-boxes"></div>
            <input type="hidden" name="pin" id="pin-val">
            <input type="hidden" name="pin_confirmation" id="pin-confirm-val">
            @error('pin')<div class="err" style="text-align:center">{{ $message }}</div>@enderror
        </div>

        <div class="field" id="confirm-wrap" style="display:none">
            <label>Confirm PIN</label>
            <div class="pin-row" id="confirm-boxes"></div>
        </div>

        <button type="submit" class="submit-btn" id="submit-btn" disabled>Create Group â†’</button>
    </form>
</div>

<script>
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
    document.getElementById('pin-val').value = p;
    if (p.length === 4) {
        document.getElementById('confirm-wrap').style.display = 'block';
        document.getElementById('confirm-boxes').querySelector('input')?.focus();
    }
    check();
});

makePinBoxes('confirm-boxes', () => {
    document.getElementById('pin-confirm-val').value = getPinValue('confirm-boxes');
    check();
});

function check() {
    const p = getPinValue('pin-boxes');
    const c = getPinValue('confirm-boxes');
    document.getElementById('submit-btn').disabled = !(p.length === 4 && c.length === 4);
}
</script>
</body>
</html>

