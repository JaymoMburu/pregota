<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pregota Admin</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}input,textarea,select,button{font-family:inherit;font-size:inherit}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh;display:flex;align-items:center;justify-content:center}
.card{background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:20px;padding:40px 32px;max-width:360px;width:100%;text-align:center}
h1{font-size:22px;font-weight:900;margin-bottom:6px}
.sub{color:rgba(255,255,255,.68);font-size:13px;margin-bottom:28px}
input{width:100%;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.15);border-radius:10px;padding:13px 16px;color:#fff;font-size:15px;outline:none;margin-bottom:14px}
input:focus{border-color:#00A651}
.btn{width:100%;padding:14px;border-radius:10px;border:none;background:linear-gradient(135deg,#00A651,#007A33);color:#fff;font-size:15px;font-weight:700;cursor:pointer}
.error{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3);border-radius:8px;padding:10px;font-size:13px;color:#fca5a5;margin-bottom:14px}
</style>
</head>
<body>
<div class="card">
    <h1>ðŸ” Pregota Admin</h1>
    <p class="sub">Restricted access</p>
    @if($errors->any())
    <div class="error">{{ $errors->first() }}</div>
    @endif
    <form method="POST" action="{{ route('admin.authenticate') }}">
        @csrf
        <input type="password" name="password" placeholder="Admin password" autofocus required>
        <button type="submit" class="btn">Enter</button>
    </form>
</div>
</body>
</html>

