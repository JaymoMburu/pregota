<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Business Login — Pregota</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh;display:flex;flex-direction:column}
.nav{padding:14px 24px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.08)}
.logo{font-size:20px;font-weight:900;background:linear-gradient(135deg,#00A651,#007A33);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.nav-link{color:rgba(255,255,255,.5);text-decoration:none;font-size:13px;font-weight:600;padding:7px 14px;border:1px solid rgba(255,255,255,.15);border-radius:8px}
.main{flex:1;display:flex;align-items:center;justify-content:center;padding:32px 20px}
.card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.09);border-radius:20px;padding:36px 32px;max-width:380px;width:100%}
h1{font-size:22px;font-weight:900;margin-bottom:6px}
.sub{color:rgba(255,255,255,.45);font-size:13px;margin-bottom:28px}
.form-group{margin-bottom:14px}
label{display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.5);margin-bottom:6px}
input{width:100%;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.15);border-radius:10px;padding:12px 14px;color:#fff;font-size:14px;outline:none;transition:.2s}
input:focus{border-color:#00A651}
.err{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);border-radius:8px;padding:10px 12px;font-size:13px;color:#fca5a5;margin-bottom:14px}
.btn{width:100%;padding:14px;border-radius:12px;border:none;font-size:16px;font-weight:700;cursor:pointer;background:linear-gradient(135deg,#00A651,#007A33);color:#fff;margin-top:4px}
.reg-link{text-align:center;margin-top:18px;font-size:13px;color:rgba(255,255,255,.4)}
.reg-link a{color:#a78bfa;text-decoration:none}
</style>
</head>
<body>
<nav class="nav">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    <a href="{{ route('business.register') }}" class="nav-link">Register</a>
</nav>
<div class="main">
    <div class="card">
        <h1>Business Login</h1>
        <p class="sub">Manage your team, view feedback and service analytics.</p>

        @if($errors->any())
        <div class="err">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('business.login.post') }}">
            @csrf
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn">Log In →</button>
        </form>
        <div class="reg-link">New business? <a href="{{ route('business.register') }}">Register here</a></div>
    </div>
</div>
</body>
</html>
