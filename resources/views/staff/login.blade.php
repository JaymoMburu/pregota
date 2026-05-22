<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Staff Login — Pregota</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0f0f1a;color:#fff;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px}
.card{width:100%;max-width:380px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:16px;padding:32px}
.logo{font-size:22px;font-weight:900;background:linear-gradient(135deg,#c084fc,#f472b6);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none;display:block;margin-bottom:24px}
h1{font-size:22px;font-weight:900;margin-bottom:6px}
.sub{font-size:13px;color:rgba(255,255,255,.4);margin-bottom:24px}
.form-group{margin-bottom:14px}
label{display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.5);margin-bottom:6px}
input{width:100%;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.15);border-radius:10px;padding:12px 14px;color:#fff;font-size:14px;outline:none;transition:.2s;font-family:inherit}
input:focus{border-color:#7c3aed;background:rgba(124,58,237,.08)}
input::placeholder{color:rgba(255,255,255,.3)}
.alert.error{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);color:#f87171;border-radius:8px;padding:10px 12px;margin-bottom:14px;font-size:13px}
.submit-btn{width:100%;padding:15px;border-radius:12px;border:none;font-size:15px;font-weight:700;cursor:pointer;background:linear-gradient(135deg,#7c3aed,#db2777);color:#fff;margin-top:6px;transition:.2s}
.submit-btn:hover{opacity:.9}
.links{margin-top:20px;display:flex;flex-direction:column;gap:8px;text-align:center;font-size:13px;color:rgba(255,255,255,.35)}
.links a{color:#c084fc;text-decoration:none;font-weight:600}
</style>
</head>
<body>
<div class="card">
    <a href="{{ route('staff.landing') }}" class="logo">Pregota</a>
    <h1>Welcome back</h1>
    <div class="sub">Log in to your tip page dashboard.</div>

    @if($errors->any())
    <div class="alert error">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('staff.login.post') }}">
        @csrf
        <div class="form-group">
            <label>Phone Number</label>
            <input type="tel" name="login_phone" placeholder="07XX XXX XXX"
                   value="{{ old('login_phone') }}" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Your password" required>
        </div>
        <button type="submit" class="submit-btn">Sign In →</button>
    </form>

    <div class="links">
        <span>New here? <a href="{{ route('staff.register') }}">Create your free tip page →</a></span>
        <span>Are you a business? <a href="{{ route('business.login') }}">Business login →</a></span>
    </div>
</div>
</body>
</html>
