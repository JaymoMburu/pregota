<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pregota — Investor Access</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}input,textarea,select,button{font-family:inherit;font-size:inherit}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0a0a14;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px}
.card{background:#13131f;border:1px solid rgba(255,255,255,.08);border-radius:20px;padding:40px 36px;width:100%;max-width:400px}
.logo{font-size:22px;font-weight:900;background:linear-gradient(135deg,#00A651,#007A33);-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-bottom:6px}
.badge{display:inline-block;background:rgba(0,166,81,.15);border:1px solid rgba(0,166,81,.3);color:#a78bfa;font-size:10px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;padding:3px 10px;border-radius:99px;margin-bottom:24px}
h1{font-size:20px;font-weight:800;color:#fff;margin-bottom:6px}
.sub{font-size:13px;color:rgba(255,255,255,.68);margin-bottom:28px;line-height:1.5}
.field{margin-bottom:16px}
label{display:block;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.68);margin-bottom:6px}
input{width:100%;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:10px;padding:12px 14px;color:#fff;font-size:14px;outline:none;transition:.2s}
input:focus{border-color:rgba(0,166,81,.6);background:rgba(0,166,81,.05)}
.btn{width:100%;padding:13px;border-radius:10px;border:none;background:linear-gradient(135deg,#00A651,#007A33);color:#fff;font-size:14px;font-weight:700;cursor:pointer;margin-top:8px;transition:.2s}
.btn:hover{opacity:.88}
.error{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);color:#f87171;border-radius:10px;padding:11px 14px;font-size:13px;margin-bottom:16px}
.footer{margin-top:28px;text-align:center;font-size:12px;color:rgba(255,255,255,.2)}
</style>
</head>
<body>
<div class="card">
    <div class="logo">Pregota</div>
    <div class="badge">Investor Portal</div>
    <h1>Welcome back</h1>
    <p class="sub">Access your investor dashboard — live platform metrics and growth data.</p>

    @if(session('error'))
    <div class="error">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('investor.login.post') }}">
        @csrf
        <div class="field">
            <label>Email Address</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
            @error('email')<p style="color:#f87171;font-size:12px;margin-top:4px">{{ $message }}</p>@enderror
        </div>
        <div class="field">
            <label>Password</label>
            <input type="password" name="password" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn">Sign In →</button>
    </form>

    <div class="footer">Pregota · Confidential investor access only</div>
</div>
</body>
</html>
