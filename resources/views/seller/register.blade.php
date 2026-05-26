<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Create Your Pay Link — Pregota</title>
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh}
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

/* Handle preview */
.handle-preview{margin-top:8px;padding:10px 14px;background:rgba(37,211,102,.07);border:1px solid rgba(37,211,102,.18);border-radius:9px;font-size:13px;display:none}
.handle-preview span{font-family:monospace;font-weight:900;color:#25D366}
.handle-preview.visible{display:block}

/* Reg plate field */
.reg-input{font-size:20px!important;font-weight:900!important;text-transform:uppercase!important;letter-spacing:.12em!important;color:#4ADE80!important;font-family:monospace!important;text-align:center!important}

.toggle-group{display:flex;align-items:center;gap:10px;padding:12px 14px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:10px}
.toggle-label{flex:1;font-size:13px;color:rgba(255,255,255,.8)}
.toggle-desc{font-size:11px;color:rgba(255,255,255,.45)}
input[type=checkbox]{width:18px!important;height:18px;accent-color:#25D366}
.divider{border:none;border-top:1px solid rgba(255,255,255,.07);margin:28px 0}
.btn{width:100%;padding:15px;background:linear-gradient(135deg,#25D366,#1aaa52);color:#fff;font-size:16px;font-weight:800;border:none;border-radius:12px;cursor:pointer;transition:.2s}
.btn:hover{transform:translateY(-1px);box-shadow:0 8px 24px rgba(37,211,102,.3)}
.error{background:rgba(239,68,68,.12);border:1px solid rgba(239,68,68,.25);border-radius:8px;padding:12px 16px;font-size:13px;color:#fca5a5;margin-bottom:20px}
.login-link{text-align:center;margin-top:20px;font-size:13px;color:rgba(255,255,255,.55)}
.login-link a{color:#25D366;text-decoration:none;font-weight:600}

/* Matatu context banner */
.matatu-banner{display:none;background:rgba(37,211,102,.07);border:1px solid rgba(37,211,102,.2);border-radius:12px;padding:14px 16px;margin-bottom:24px;font-size:13px;color:rgba(255,255,255,.75);line-height:1.6}
.matatu-banner.visible{display:block}
.matatu-banner strong{color:#25D366}
</style>
</head>
<body>
<nav class="nav">
    <a href="{{ route('seller.landing') }}" class="logo">Pregota</a>
</nav>

<div class="wrap">
    <h1>Create your pay link</h1>
    <div class="sub">Takes 60 seconds. Share it anywhere.</div>

    @if($errors->any())
    <div class="error">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('seller.register.post') }}" id="reg-form">
        @csrf

        {{-- Category first — drives the rest of the form --}}
        <div class="form-group">
            <label>What type of business?</label>
            <select name="category" id="category" onchange="onCategoryChange()">
                <option value="">— Select category —</option>
                <option value="transport" {{ old('category') == 'transport' ? 'selected' : '' }}>🚐 Matatu / Transport</option>
                <option value="fashion" {{ old('category') == 'fashion' ? 'selected' : '' }}>👗 Fashion & Clothing</option>
                <option value="food" {{ old('category') == 'food' ? 'selected' : '' }}>🍱 Food & Restaurant</option>
                <option value="salon" {{ old('category') == 'salon' ? 'selected' : '' }}>💇 Salon & Beauty</option>
                <option value="electronics" {{ old('category') == 'electronics' ? 'selected' : '' }}>📱 Electronics</option>
                <option value="services" {{ old('category') == 'services' ? 'selected' : '' }}>🛠 Services & Freelance</option>
                <option value="groceries" {{ old('category') == 'groceries' ? 'selected' : '' }}>🛒 Groceries & Kiosk</option>
                <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>

        {{-- Matatu context banner --}}
        <div class="matatu-banner" id="matatu-banner">
            🚐 <strong>For matatus:</strong> Your vehicle reg number becomes your unique payment link.
            Each matatu gets its own QR code — the SACCO can track every vehicle separately.
        </div>

        {{-- Identifier field: reg plate for matatu, handle for others --}}
        <div class="form-group" id="identifier-group">
            <label id="identifier-label">Your handle (this becomes your link)</label>

            {{-- Matatu: visible reg plate input (formatted), hidden handle --}}
            <div id="reg-wrap" style="display:none">
                <input type="text" id="reg-plate-display" class="reg-input"
                    placeholder="KCA 123A"
                    autocomplete="off"
                    oninput="onRegInput(this)"
                    maxlength="10"
                    value="{{ old('category') == 'transport' ? strtoupper(str_replace('-', ' ', old('handle', ''))) : '' }}">
                <div class="hint">Enter your vehicle reg number exactly as it appears on the plate</div>
                <div class="handle-preview" id="reg-preview">
                    Your link: <span id="reg-preview-url">pregota.com/pay/…</span>
                </div>
            </div>

            {{-- Non-matatu: standard handle input --}}
            <div id="handle-wrap">
                <div style="position:relative">
                    <span style="position:absolute;left:14px;top:50%;transform:translateY(-50%);font-size:13px;color:rgba(255,255,255,.45);pointer-events:none">pregota.com/pay/</span>
                    <input type="text" id="handle-display" name="handle" id="handle-display"
                        style="padding-left:130px;font-family:monospace;font-weight:700;color:#4ADE80"
                        value="{{ old('category') != 'transport' ? old('handle') : '' }}"
                        placeholder="yourshop"
                        autocomplete="off"
                        pattern="[a-z0-9._-]+"
                        title="Lowercase letters, numbers, dots, hyphens only"
                        oninput="onHandleInput(this)">
                </div>
                <div class="hint">Lowercase letters, numbers, hyphens, dots only</div>
            </div>

            {{-- Hidden handle that always gets submitted --}}
            <input type="hidden" name="handle" id="handle-hidden" value="{{ old('handle') }}">
        </div>

        <div class="form-group">
            <label id="name-label">Business / display name</label>
            <input type="text" name="business_name" id="business-name"
                value="{{ old('business_name') }}"
                placeholder="e.g. Akinyi's Boutique" required maxlength="100">
        </div>

        <div class="form-group">
            <label id="desc-label">Description <span style="color:rgba(255,255,255,.45)">(optional)</span></label>
            <textarea name="description" id="description" placeholder="Tell buyers what you sell..." maxlength="300">{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label>M-Pesa number for payouts</label>
            <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="0712 345 678" required>
            <div class="hint">Your personal number — never shown to buyers or passengers</div>
        </div>

        <hr class="divider">

        <div class="form-group">
            <label id="amount-label">Default payment amount (KES) <span style="color:rgba(255,255,255,.45)">(optional)</span></label>
            <input type="number" name="default_amount" value="{{ old('default_amount') }}" placeholder="e.g. 70" min="10" max="150000">
            <div class="hint" id="amount-hint">Leave blank to let buyers enter any amount</div>
        </div>

        <div class="form-group">
            <div class="toggle-group">
                <div>
                    <div class="toggle-label" id="fixed-label">Fixed amount only</div>
                    <div class="toggle-desc" id="fixed-desc">Buyers cannot change the amount</div>
                </div>
                <input type="checkbox" name="fixed_amount" value="1" {{ old('fixed_amount') ? 'checked' : '' }}>
            </div>
        </div>

        <hr class="divider">

        <div class="form-group">
            <label>Password (to access your dashboard)</label>
            <input type="password" name="password" placeholder="Choose a password" required minlength="6" autocomplete="new-password">
        </div>

        <div class="form-group">
            <label>Confirm password</label>
            <input type="password" name="password_confirmation" placeholder="Repeat password" required autocomplete="new-password">
        </div>

        <button type="submit" class="btn" id="submit-btn">Create My Pay Link →</button>
    </form>

    <div class="login-link">Already have a pay link? <a href="{{ route('seller.login') }}">Login here</a></div>
</div>

<script>
const isTransport = document.getElementById('category').value === 'transport';

function onCategoryChange() {
    const cat = document.getElementById('category').value;
    const isMatatu = cat === 'transport';

    // Banner
    document.getElementById('matatu-banner').classList.toggle('visible', isMatatu);

    // Identifier field
    document.getElementById('reg-wrap').style.display    = isMatatu ? 'block' : 'none';
    document.getElementById('handle-wrap').style.display = isMatatu ? 'none' : 'block';
    document.getElementById('identifier-label').textContent = isMatatu
        ? 'Vehicle Registration Number'
        : 'Your handle (this becomes your link)';

    // Handle input required attr — only for non-matatu (matatu uses reg display + hidden)
    document.getElementById('handle-display').required = !isMatatu;
    document.getElementById('handle-display').name     = isMatatu ? '' : 'handle';

    // Name / description labels
    document.getElementById('name-label').textContent = isMatatu
        ? 'SACCO / Route name'
        : 'Business / display name';
    document.getElementById('business-name').placeholder = isMatatu
        ? 'e.g. City Hoppa — CBD → Westlands'
        : "e.g. Akinyi's Boutique";
    document.getElementById('desc-label').querySelector('label') // safe
    document.getElementById('description').placeholder = isMatatu
        ? 'e.g. Route: Railways → Westlands · Stage: Railways, Nation, Westlands'
        : 'Tell buyers what you sell...';

    // Amount labels
    document.getElementById('amount-label').innerHTML = isMatatu
        ? 'Suggested fare (KES) <span style="color:rgba(255,255,255,.45)">(optional)</span>'
        : 'Default payment amount (KES) <span style="color:rgba(255,255,255,.45)">(optional)</span>';
    document.getElementById('amount-hint').textContent = isMatatu
        ? 'Optional — leave blank. Routes and fares change throughout the day. Passengers enter the fare the conductor calls.'
        : 'Leave blank to let buyers enter any amount';
    document.getElementById('fixed-label').textContent = isMatatu
        ? 'Fixed fare only'
        : 'Fixed amount only';
    document.getElementById('fixed-desc').textContent = isMatatu
        ? 'Only tick this if you run a single fixed route all day'
        : 'Buyers cannot change the amount';
    document.getElementById('submit-btn').textContent = isMatatu
        ? 'Create Matatu Pay Link →'
        : 'Create My Pay Link →';

    // Refresh preview if reg already entered
    if (isMatatu) {
        const display = document.getElementById('reg-plate-display');
        if (display.value) onRegInput(display);
    }
}

function onRegInput(input) {
    // Accept anything the user types, strip to URL-safe on the hidden field
    const raw    = input.value.toUpperCase().replace(/[^A-Z0-9]/g, ' ').replace(/\s+/g, ' ').trim();
    const slug   = raw.replace(/\s/g, '').toLowerCase(); // kca123a

    document.getElementById('handle-hidden').value = slug;

    const preview = document.getElementById('reg-preview');
    const previewUrl = document.getElementById('reg-preview-url');
    if (slug.length > 0) {
        previewUrl.textContent = 'pregota.com/pay/' + slug;
        preview.classList.add('visible');
    } else {
        preview.classList.remove('visible');
    }
}

function onHandleInput(input) {
    // Keep hidden in sync for non-matatu
    document.getElementById('handle-hidden').value = input.value;
}

// Remove the non-hidden handle from form submission for matatu (avoid duplicate name)
document.getElementById('reg-form').addEventListener('submit', function() {
    const cat = document.getElementById('category').value;
    if (cat === 'transport') {
        document.getElementById('handle-display').name = '';
    }
});

// Init on page load (handles old() repopulation)
if (document.getElementById('category').value) {
    onCategoryChange();
}
</script>
</body>
</html>
