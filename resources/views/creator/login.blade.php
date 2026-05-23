<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Creator Login — Pregota</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px}
.card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.09);border-radius:20px;padding:36px 28px;max-width:380px;width:100%;text-align:center}
.logo{font-size:22px;font-weight:900;background:linear-gradient(135deg,#00A651,#007A33);-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-bottom:20px}
h1{font-size:20px;font-weight:800;margin-bottom:24px}
.form-group{margin-bottom:14px;text-align:left}
label{display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.78);margin-bottom:6px}
input{width:100%;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.14);border-radius:10px;padding:12px 14px;color:#fff;font-size:15px;outline:none;transition:.2s}
input:focus{border-color:#00A651}
input::placeholder{color:rgba(255,255,255,.25)}
.btn{width:100%;padding:14px;border-radius:12px;border:none;font-size:16px;font-weight:700;cursor:pointer;background:linear-gradient(135deg,#00A651,#007A33);color:#fff;margin-top:4px}
.err{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);border-radius:8px;padding:10px 12px;font-size:13px;color:#fca5a5;margin-bottom:14px;text-align:left}
.register-link{margin-top:16px;font-size:13px;color:rgba(255,255,255,.6)}
.register-link a{color:#a78bfa;text-decoration:none}
</style>
</head>
<body>
<div class="card">
    <div class="logo">Pregota</div>
    <h1>Creator Sign In</h1>

    @if($errors->any())
    <div class="err">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('creator.login.post') }}">
        @csrf
        <div class="form-group">
            <label>Handle</label>
            <input type="text" name="handle" placeholder="yourhandle" value="{{ old('handle') }}" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="••••••" required>
        </div>
        <button type="submit" class="btn">Sign In →</button>
    </form>

    <div class="register-link">New creator? <a href="{{ route('creator.register') }}">Create an account</a></div>
</div>
</body>
</html>
