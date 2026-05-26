<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Seller Login — Pregota</title>
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh;display:flex;flex-direction:column}
.nav{padding:16px 24px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.07)}
.logo{font-size:20px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.wrap{flex:1;display:flex;align-items:center;justify-content:center;padding:40px 24px}
.card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:20px;padding:36px;width:100%;max-width:400px}
h1{font-size:22px;font-weight:900;margin-bottom:6px}
.sub{font-size:13px;color:rgba(255,255,255,.6);margin-bottom:28px}
.form-group{margin-bottom:18px}
label{display:block;font-size:13px;font-weight:700;color:rgba(255,255,255,.8);margin-bottom:6px}
input{width:100%;padding:12px 14px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);border-radius:10px;color:#fff;font-size:14px;outline:none;transition:.15s}
input:focus{border-color:rgba(37,211,102,.5)}
.btn{width:100%;padding:14px;background:linear-gradient(135deg,#25D366,#1aaa52);color:#fff;font-size:15px;font-weight:800;border:none;border-radius:12px;cursor:pointer;transition:.2s}
.btn:hover{transform:translateY(-1px)}
.error{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.2);border-radius:8px;padding:10px 14px;font-size:13px;color:#fca5a5;margin-bottom:18px}
.links{text-align:center;margin-top:20px;font-size:13px;color:rgba(255,255,255,.5);display:flex;flex-direction:column;gap:8px}
.links a{color:#25D366;text-decoration:none;font-weight:600}
</style>
</head>
<body>
<nav class="nav">
    <a href="{{ route('seller.landing') }}" class="logo">Pregota</a>
</nav>
<div class="wrap">
    <div class="card">
        <h1>Welcome back</h1>
        <div class="sub">Log in to your pay link dashboard</div>

        @if($errors->any())
        <div class="error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('seller.login.post') }}">
            @csrf
            <div class="form-group">
                <label>Your handle</label>
                <input type="text" name="handle" value="{{ old('handle') }}" placeholder="yourshop" required autocomplete="username">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="••••••" required autocomplete="current-password">
            </div>
            <button type="submit" class="btn">Login →</button>
        </form>
        <div class="links">
            <span>Don't have a pay link yet? <a href="{{ route('seller.register') }}">Create one free</a></span>
        </div>
    </div>
</div>
</body>
</html>
