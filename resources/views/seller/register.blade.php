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
.handle-wrap{position:relative}
.handle-prefix{position:absolute;left:14px;top:50%;transform:translateY(-50%);font-size:13px;color:rgba(255,255,255,.45);pointer-events:none}
.handle-input{padding-left:130px!important;font-family:monospace;font-weight:700;color:#4ADE80}
.hint{font-size:11px;color:rgba(255,255,255,.45);margin-top:4px}
.row-2{display:grid;grid-template-columns:1fr 1fr;gap:16px}
@media(max-width:420px){.row-2{grid-template-columns:1fr}}
.toggle-group{display:flex;align-items:center;gap:10px;padding:12px 14px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:10px}
.toggle-label{flex:1;font-size:13px;color:rgba(255,255,255,.8)}
.toggle-desc{font-size:11px;color:rgba(255,255,255,.45)}
input[type=checkbox]{width:auto;accent-color:#25D366;width:18px;height:18px}
.divider{border:none;border-top:1px solid rgba(255,255,255,.07);margin:28px 0}
.btn{width:100%;padding:15px;background:linear-gradient(135deg,#25D366,#1aaa52);color:#fff;font-size:16px;font-weight:800;border:none;border-radius:12px;cursor:pointer;transition:.2s}
.btn:hover{transform:translateY(-1px);box-shadow:0 8px 24px rgba(37,211,102,.3)}
.error{background:rgba(239,68,68,.12);border:1px solid rgba(239,68,68,.25);border-radius:8px;padding:12px 16px;font-size:13px;color:#fca5a5;margin-bottom:20px}
.login-link{text-align:center;margin-top:20px;font-size:13px;color:rgba(255,255,255,.55)}
.login-link a{color:#25D366;text-decoration:none;font-weight:600}
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

    <form method="POST" action="{{ route('seller.register.post') }}">
        @csrf

        <div class="form-group">
            <label>Your handle (this becomes your link)</label>
            <div class="handle-wrap">
                <span class="handle-prefix">pregota.com/pay/</span>
                <input type="text" name="handle" class="handle-input" value="{{ old('handle') }}" placeholder="yourshop" required autocomplete="off" pattern="[a-z0-9._-]+" title="Lowercase letters, numbers, dots, hyphens only">
            </div>
            <div class="hint">Lowercase letters, numbers, hyphens, dots only</div>
        </div>

        <div class="form-group">
            <label>Business / display name</label>
            <input type="text" name="business_name" value="{{ old('business_name') }}" placeholder="e.g. Akinyi's Boutique" required maxlength="100">
        </div>

        <div class="form-group">
            <label>Category <span style="color:rgba(255,255,255,.45)">(optional)</span></label>
            <select name="category">
                <option value="">— Select category —</option>
                <option value="fashion" {{ old('category') == 'fashion' ? 'selected' : '' }}>Fashion & Clothing</option>
                <option value="food" {{ old('category') == 'food' ? 'selected' : '' }}>Food & Restaurant</option>
                <option value="salon" {{ old('category') == 'salon' ? 'selected' : '' }}>Salon & Beauty</option>
                <option value="electronics" {{ old('category') == 'electronics' ? 'selected' : '' }}>Electronics</option>
                <option value="services" {{ old('category') == 'services' ? 'selected' : '' }}>Services & Freelance</option>
                <option value="groceries" {{ old('category') == 'groceries' ? 'selected' : '' }}>Groceries & Kiosk</option>
                <option value="transport" {{ old('category') == 'transport' ? 'selected' : '' }}>Transport & Matatu</option>
                <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>

        <div class="form-group">
            <label>Description <span style="color:rgba(255,255,255,.45)">(optional)</span></label>
            <textarea name="description" placeholder="Tell buyers what you sell..." maxlength="300">{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label>Your M-Pesa number (for payouts)</label>
            <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="0712 345 678" required>
            <div class="hint">Your personal number — never shown to buyers</div>
        </div>

        <hr class="divider">

        <div class="form-group">
            <label>Default payment amount (KES) <span style="color:rgba(255,255,255,.45)">(optional)</span></label>
            <input type="number" name="default_amount" value="{{ old('default_amount') }}" placeholder="e.g. 500" min="10" max="150000">
            <div class="hint">Leave blank to let buyers enter any amount</div>
        </div>

        <div class="form-group">
            <div class="toggle-group">
                <div>
                    <div class="toggle-label">Fixed amount only</div>
                    <div class="toggle-desc">Buyers cannot change the amount</div>
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

        <button type="submit" class="btn">Create My Pay Link →</button>
    </form>

    <div class="login-link">Already have a pay link? <a href="{{ route('seller.login') }}">Login here</a></div>
</div>
</body>
</html>
