<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Create Your Pay Link â€” Pregota</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800;900&display=swap" rel="stylesheet">
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}input,textarea,select,button{font-family:inherit;font-size:inherit}
body{font-family:'Plus Jakarta Sans',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}
.nav{padding:16px 24px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.07)}
.logo{font-size:20px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.wrap{max-width:480px;margin:48px auto;padding:0 24px 80px}
h1{font-size:26px;font-weight:900;margin-bottom:6px}
.sub{font-size:14px;color:rgba(255,255,255,.6);margin-bottom:32px}
.form-group{margin-bottom:20px}
label{display:block;font-size:13px;font-weight:700;color:rgba(255,255,255,.85);margin-bottom:6px}
input,select,textarea{width:100%;padding:12px 14px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);border-radius:10px;color:#fff;font-size:14px;font-family:inherit;outline:none;transition:.15s}
input:focus,select:focus,textarea:focus{border-color:rgba(37,211,102,.5);background:rgba(255,255,255,.08)}
select option{background:#1a2730;color:#fff}
textarea{resize:vertical;min-height:80px}
.hint{font-size:11px;color:rgba(255,255,255,.45);margin-top:4px;line-height:1.5}
.hint strong{color:rgba(255,255,255,.75)}
.handle-preview{margin-top:8px;padding:10px 14px;background:rgba(37,211,102,.07);border:1px solid rgba(37,211,102,.18);border-radius:9px;font-size:13px;display:none}
.handle-preview span{font-family:monospace;font-weight:900;color:#25D366}
.handle-preview.visible{display:block}
.reg-input{font-size:20px!important;font-weight:900!important;text-transform:uppercase!important;letter-spacing:.12em!important;color:#4ADE80!important;font-family:monospace!important;text-align:center!important}
.toggle-group{display:flex;align-items:center;gap:10px;padding:12px 14px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:10px}
.toggle-label{flex:1;font-size:13px;color:rgba(255,255,255,.8)}
.toggle-desc{font-size:11px;color:rgba(255,255,255,.45)}
input[type=checkbox]{width:18px!important;height:18px;accent-color:#25D366}
.divider{border:none;border-top:1px solid rgba(255,255,255,.07);margin:28px 0}
.btn{width:100%;padding:15px;background:linear-gradient(135deg,#25D366,#1aaa52);color:#fff;font-size:16px;font-weight:800;border:none;border-radius:12px;cursor:pointer;transition:.2s}
.btn:hover{transform:translateY(-1px);box-shadow:0 8px 24px rgba(37,211,102,.3)}
.btn:disabled{opacity:.45;cursor:not-allowed;transform:none;box-shadow:none}
.err{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.2);border-radius:9px;padding:10px 14px;font-size:13px;color:#fca5a5;margin-bottom:16px;display:none}
.login-link{text-align:center;margin-top:20px;font-size:13px;color:rgba(255,255,255,.55)}
.login-link a{color:#25D366;text-decoration:none;font-weight:600}
.matatu-banner{display:none;background:rgba(37,211,102,.07);border:1px solid rgba(37,211,102,.2);border-radius:12px;padding:14px 16px;margin-bottom:24px;font-size:13px;color:rgba(255,255,255,.75);line-height:1.6}
.matatu-banner.visible{display:block}
.matatu-banner strong{color:#25D366}
.pending{display:none;text-align:center;padding:40px 0}
.spinner{width:48px;height:48px;border:3px solid rgba(255,255,255,.1);border-top-color:#25D366;border-radius:50%;animation:spin .8s linear infinite;margin:0 auto 20px}
@keyframes spin{to{transform:rotate(360deg)}}
</style>
</head>
<body>
<nav class="nav">
    <a href="{{ route('seller.landing') }}" class="logo">Pregota</a>
</nav>

<div class="wrap">

    <div id="form-view">
        <h1>Create your pay link</h1>
        <div class="sub">Takes 60 seconds. Verify with M-Pesa â€” no password needed.</div>

        <div class="err" id="err-msg"></div>

        {{-- Category --}}
        <div class="form-group">
            <label>What type of business?</label>
            <select id="category" onchange="onCategoryChange()">
                <option value="">â€” Select category â€”</option>
                <option value="transport">ðŸš Matatu / Transport</option>
                <option value="supermarket">ðŸ›’ Supermarket / Shop</option>
                <option value="food">ðŸ½ï¸ Restaurant / Food</option>
                <option value="groceries">ðŸ¥¬ Groceries & Kiosk</option>
                <option value="fashion">ðŸ‘— Fashion & Clothing</option>
                <option value="salon">ðŸ’‡ Salon & Beauty</option>
                <option value="electronics">ðŸ“± Electronics</option>
                <option value="services">ðŸ›  Services & Freelance</option>
                <option value="other">ðŸª Other</option>
            </select>
        </div>

        <div class="matatu-banner" id="matatu-banner">
            ðŸš <strong>For matatus:</strong> Your reg number becomes your payment link. The conductor updates the route and fare from the live view each time the route changes. Passengers see it automatically.
        </div>

        {{-- Handle --}}
        <div class="form-group" id="identifier-group">
            <label id="identifier-label">Your handle (this becomes your link)</label>

            <div id="reg-wrap" style="display:none">
                <input type="text" id="reg-plate-display" class="reg-input" placeholder="KCA 123A" autocomplete="off" oninput="onRegInput(this)" maxlength="10">
                <div class="hint">Enter your vehicle reg number exactly as on the plate</div>
                <div class="handle-preview" id="reg-preview">Your link: <span id="reg-preview-url">pregota.com/pay/â€¦</span></div>
            </div>

            <div id="handle-wrap">
                <div style="position:relative">
                    <span style="position:absolute;left:14px;top:50%;transform:translateY(-50%);font-size:13px;color:rgba(255,255,255,.45);pointer-events:none">pregota.com/pay/</span>
                    <input type="text" id="handle-display" style="padding-left:130px;font-family:monospace;font-weight:700;color:#4ADE80" placeholder="yourshop" autocomplete="off" pattern="[a-z0-9._-]+" oninput="onHandleInput(this)">
                </div>
                <div class="hint">Lowercase letters, numbers, hyphens, dots only</div>
            </div>

            <input type="hidden" id="handle-hidden">
        </div>

        <div class="form-group">
            <label id="name-label">Business / display name</label>
            <input type="text" id="business-name" placeholder="e.g. Akinyi's Boutique" maxlength="100">
        </div>

        <div class="form-group">
            <label>Description <span style="color:rgba(255,255,255,.45)">(optional)</span></label>
            <textarea id="description" placeholder="Tell buyers what you sell..." maxlength="300"></textarea>
        </div>

        <div class="form-group">
            <label>Your M-Pesa Number</label>
            <input type="tel" id="phone" placeholder="07XX XXX XXX" autocomplete="tel">
            <div class="hint">We'll send an STK Push to verify â€” this is also where your payments land</div>
        </div>

        <div id="fare-fields">
            <hr class="divider">
            <div class="form-group">
                <label id="amount-label">Default payment amount (KES) <span style="color:rgba(255,255,255,.45)">(optional)</span></label>
                <input type="number" id="default-amount" placeholder="e.g. 500" min="10" max="150000">
                <div class="hint" id="amount-hint">Leave blank to let buyers enter any amount</div>
            </div>
            <div class="form-group">
                <div class="toggle-group">
                    <div>
                        <div class="toggle-label" id="fixed-label">Fixed amount only</div>
                        <div class="toggle-desc" id="fixed-desc">Buyers cannot change the amount</div>
                    </div>
                    <input type="checkbox" id="fixed-amount">
                </div>
            </div>
        </div>

        <button class="btn" id="submit-btn" onclick="doRegister()">Create My Pay Link â€” Verify via M-Pesa â†’</button>
        <div style="font-size:11px;color:rgba(255,255,255,.3);text-align:center;margin-top:10px">KES 1 verification charge via M-Pesa STK Push. No password needed â€” ever.</div>
    </div>

    <div class="pending" id="pending-view">
        <div class="spinner"></div>
        <div style="font-size:17px;font-weight:900;margin-bottom:8px">Check your phone</div>
        <div style="font-size:14px;color:rgba(255,255,255,.45);line-height:1.6">Enter your M-Pesa PIN to confirm KES 1 and activate your pay link.</div>
    </div>

    <div class="login-link" id="login-link-row">Already have a pay link? <a href="{{ route('seller.login') }}">Login here</a></div>
</div>

<script>
const CSRF = '{{ csrf_token() }}';
let checkoutId = null;

function onCategoryChange() {
    const cat = document.getElementById('category').value;
    const isMatatu = cat === 'transport';
    document.getElementById('matatu-banner').classList.toggle('visible', isMatatu);
    document.getElementById('fare-fields').style.display = isMatatu ? 'none' : 'block';
    document.getElementById('reg-wrap').style.display    = isMatatu ? 'block' : 'none';
    document.getElementById('handle-wrap').style.display = isMatatu ? 'none'  : 'block';
    document.getElementById('identifier-label').textContent = isMatatu ? 'Vehicle Registration Number' : 'Your handle (this becomes your link)';
    document.getElementById('name-label').textContent = isMatatu ? 'SACCO / Route name' : 'Business / display name';
    document.getElementById('business-name').placeholder = isMatatu ? 'e.g. City Hoppa â€” CBD â†’ Westlands' : "e.g. Akinyi's Boutique";
    document.getElementById('description').placeholder = isMatatu ? 'e.g. Route: Railways â†’ Westlands' : 'Tell buyers what you sell...';
    document.getElementById('amount-label').innerHTML = isMatatu ? 'Suggested fare (KES) <span style="color:rgba(255,255,255,.45)">(optional)</span>' : 'Default payment amount (KES) <span style="color:rgba(255,255,255,.45)">(optional)</span>';
    document.getElementById('amount-hint').textContent = isMatatu ? 'Optional â€” routes and fares change. Passengers enter the fare the conductor calls.' : 'Leave blank to let buyers enter any amount';
    document.getElementById('fixed-label').textContent = isMatatu ? 'Fixed fare only' : 'Fixed amount only';
    document.getElementById('fixed-desc').textContent  = isMatatu ? 'Only tick if you run a single fixed route all day' : 'Buyers cannot change the amount';
    document.getElementById('submit-btn').textContent  = isMatatu ? 'Create Matatu Pay Link â€” Verify via M-Pesa â†’' : 'Create My Pay Link â€” Verify via M-Pesa â†’';
    if (isMatatu) { const d = document.getElementById('reg-plate-display'); if (d.value) onRegInput(d); }
}

function onRegInput(input) {
    const raw  = input.value.toUpperCase().replace(/[^A-Z0-9]/g, ' ').replace(/\s+/g, ' ').trim();
    const slug = raw.replace(/\s/g, '').toLowerCase();
    document.getElementById('handle-hidden').value = slug;
    const preview = document.getElementById('reg-preview');
    if (slug.length > 0) { document.getElementById('reg-preview-url').textContent = 'pregota.com/pay/' + slug; preview.classList.add('visible'); }
    else preview.classList.remove('visible');
}

function onHandleInput(input) {
    document.getElementById('handle-hidden').value = input.value.toLowerCase().replace(/[^a-z0-9._-]/g, '');
    input.value = document.getElementById('handle-hidden').value;
}

async function doRegister() {
    const err = document.getElementById('err-msg');
    err.style.display = 'none';

    const handle   = document.getElementById('handle-hidden').value.trim();
    const bizName  = document.getElementById('business-name').value.trim();
    const phone    = document.getElementById('phone').value.trim();
    const category = document.getElementById('category').value;
    const desc     = document.getElementById('description').value.trim();
    const amount   = document.getElementById('default-amount')?.value.trim();
    const fixed    = document.getElementById('fixed-amount')?.checked ? 1 : 0;

    if (!handle)   { err.textContent = 'Enter your handle (your pay link address).'; err.style.display='block'; return; }
    if (!bizName)  { err.textContent = 'Enter your business or display name.'; err.style.display='block'; return; }
    if (!phone || !/^(\+?254|0)[17]\d{8}$/.test(phone)) { err.textContent = 'Enter a valid Safaricom number.'; err.style.display='block'; return; }

    document.getElementById('submit-btn').disabled = true;

    const res = await fetch('{{ route("seller.register.post") }}', {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
        body: JSON.stringify({handle, business_name: bizName, category: category || null, description: desc || null, phone, default_amount: amount ? parseInt(amount) : null, fixed_amount: fixed}),
    });
    const data = await res.json();

    if (!data.success) {
        err.textContent = data.message || 'Something went wrong.';
        err.style.display = 'block';
        document.getElementById('submit-btn').disabled = false;
        return;
    }

    checkoutId = data.checkout_request_id;
    document.getElementById('form-view').style.display   = 'none';
    document.getElementById('login-link-row').style.display = 'none';
    document.getElementById('pending-view').style.display = 'block';
    pollRegister();
}

function pollRegister() {
    fetch('{{ route("seller.register.poll") }}?checkout_request_id=' + checkoutId)
        .then(r => r.json())
        .then(d => {
            if (d.status === 'confirmed') {
                window.location.href = d.redirect;
            } else if (d.status === 'failed') {
                document.getElementById('pending-view').style.display = 'none';
                document.getElementById('form-view').style.display    = 'block';
                document.getElementById('login-link-row').style.display = 'block';
                const err = document.getElementById('err-msg');
                err.textContent = d.message || 'Payment failed or cancelled. Please try again.';
                err.style.display = 'block';
                document.getElementById('submit-btn').disabled = false;
            } else {
                setTimeout(pollRegister, 2500);
            }
        })
        .catch(() => setTimeout(pollRegister, 3000));
}
</script>
</body>
</html>


