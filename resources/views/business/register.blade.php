<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register Business â€” Pregota</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}input,textarea,select,button{font-family:inherit;font-size:inherit}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh;display:flex;flex-direction:column}
.nav{padding:14px 24px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.08)}
.logo{font-size:20px;font-weight:900;background:linear-gradient(135deg,#00A651,#007A33);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.nav-link{color:rgba(255,255,255,.78);text-decoration:none;font-size:13px;font-weight:600;padding:7px 14px;border:1px solid rgba(255,255,255,.15);border-radius:8px}
.main{flex:1;display:flex;align-items:center;justify-content:center;padding:32px 20px}
.card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.09);border-radius:20px;padding:36px 32px;max-width:460px;width:100%}
h1{font-size:22px;font-weight:900;margin-bottom:6px}
.sub{color:rgba(255,255,255,.72);font-size:13px;margin-bottom:28px}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
@media(max-width:480px){.form-row{grid-template-columns:1fr}}
.form-group{margin-bottom:14px}
label{display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.78);margin-bottom:6px}
input,select{width:100%;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.15);border-radius:10px;padding:12px 14px;color:#fff;font-size:14px;outline:none;transition:.2s;font-family:inherit}
input:focus,select:focus{border-color:#00A651}
select option{background:#0B1810;color:#fff}
.hint{font-size:11px;color:rgba(255,255,255,.6);margin-top:4px}
.err{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);border-radius:8px;padding:10px 12px;font-size:13px;color:#fca5a5;margin-bottom:14px}
.btn{width:100%;padding:14px;border-radius:12px;border:none;font-size:16px;font-weight:700;cursor:pointer;background:linear-gradient(135deg,#00A651,#007A33);color:#fff;margin-top:4px}
.login-link{text-align:center;margin-top:18px;font-size:13px;color:rgba(255,255,255,.68)}
.login-link a{color:#a78bfa;text-decoration:none}
</style>
</head>
<body>
<nav class="nav">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    <a href="{{ route('business.login') }}" class="nav-link">Login</a>
</nav>
<div class="main">
    <div class="card">
        <h1>Register Your Business</h1>
        <p class="sub">Enable private tipping + customer feedback for your team.</p>

        @if($errors->any())
        <div class="err">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('business.register.post') }}">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label>Business Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Art CafÃ©" required>
                </div>
                <div class="form-group">
                    <label>Short Slug</label>
                    <input type="text" name="slug" value="{{ old('slug') }}" placeholder="artcafe" required>
                    <div class="hint">Used in staff URLs: /t/artcafe-grace</div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Category</label>
                    <select name="category">
                        <option value="restaurant" {{ old('category')=='restaurant'?'selected':'' }}>Restaurant</option>
                        <option value="salon" {{ old('category')=='salon'?'selected':'' }}>Salon & Spa</option>
                        <option value="hotel" {{ old('category')=='hotel'?'selected':'' }}>Hotel</option>
                        <option value="delivery" {{ old('category')=='delivery'?'selected':'' }}>Delivery</option>
                        <option value="other" {{ old('category')=='other'?'selected':'' }}>Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>City</label>
                    <input type="text" name="city" value="{{ old('city') }}" placeholder="Nairobi">
                </div>
            </div>
            <div class="form-group">
                <label>Business Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" required>
                </div>
            </div>
            <button type="submit" class="btn">Create Business Account â†’</button>
        </form>
        <div class="login-link">Already registered? <a href="{{ route('business.login') }}">Log in</a></div>
    </div>
</div>
</body>
</html>

