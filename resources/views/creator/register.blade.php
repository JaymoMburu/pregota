<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Creator Registration — Pregota</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}input,textarea,select,button{font-family:inherit;font-size:inherit}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh;display:flex;flex-direction:column;align-items:center;padding:32px 20px}
.card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.09);border-radius:20px;padding:32px 28px;max-width:480px;width:100%}
.logo{font-size:22px;font-weight:900;background:linear-gradient(135deg,#00A651,#007A33);-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-bottom:4px;text-align:center}
h1{font-size:20px;font-weight:800;text-align:center;margin-bottom:6px}
.sub{font-size:13px;color:rgba(255,255,255,.68);text-align:center;margin-bottom:28px}
.form-group{margin-bottom:14px}
label{display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.78);margin-bottom:6px}
input{width:100%;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.14);border-radius:10px;padding:12px 14px;color:#fff;font-size:15px;outline:none;transition:.2s}
input:focus{border-color:#00A651;background:rgba(0,166,81,.1)}
input::placeholder{color:rgba(255,255,255,.25)}
.hint{font-size:11px;color:rgba(255,255,255,.82);margin-top:5px}
.section{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.82);margin:20px 0 12px;border-top:1px solid rgba(255,255,255,.07);padding-top:16px}
.btn{width:100%;padding:14px;border-radius:12px;border:none;font-size:16px;font-weight:700;cursor:pointer;background:linear-gradient(135deg,#00A651,#007A33);color:#fff;margin-top:6px}
.btn:hover{opacity:.9}
.err{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);border-radius:8px;padding:10px 12px;font-size:13px;color:#fca5a5;margin-bottom:14px}
.login-link{text-align:center;margin-top:16px;font-size:13px;color:rgba(255,255,255,.6)}
.login-link a{color:#a78bfa;text-decoration:none}
.pending-box{background:rgba(251,191,36,.1);border:1px solid rgba(251,191,36,.3);border-radius:12px;padding:20px;text-align:center;margin-bottom:20px}
.pending-box .icon{font-size:36px;margin-bottom:10px}
.pending-box strong{display:block;font-size:16px;font-weight:800;color:#fbbf24;margin-bottom:6px}
.pending-box p{font-size:13px;color:rgba(255,255,255,.78);line-height:1.6}
</style>
</head>
<body>
<div class="card">
    <div class="logo">Pregota</div>
    <h1>Creator Account</h1>
    <p class="sub">Receive gifts privately. Share a link — never your number.</p>

    @if(session('pending'))
    <div class="pending-box">
        <div class="icon">⏳</div>
        <strong>Application Received!</strong>
        <p>{{ session('pending') }}</p>
    </div>
    @endif

    @if($errors->any())
    <div class="err">{{ $errors->first() }}</div>
    @endif

    @if(!session('pending'))
    <form method="POST" action="{{ route('creator.register.post') }}">
        @csrf

        <div class="form-group">
            <label>Your Handle</label>
            <input type="text" name="handle" placeholder="e.g. djkibz" value="{{ old('handle') }}"
                pattern="[a-z0-9._-]+" required>
            <div class="hint">pregota.com/c/<strong>yourhandle</strong> · lowercase, no spaces</div>
        </div>

        <div class="form-group">
            <label>Display Name</label>
            <input type="text" name="display_name" placeholder="DJ Kibz" value="{{ old('display_name') }}" required>
        </div>

        <div class="form-group">
            <label>Bio (optional)</label>
            <input type="text" name="bio" placeholder="TikTok creator · Comedy & Music" value="{{ old('bio') }}" maxlength="200">
        </div>

        <div class="section">Payout Details (private)</div>

        <div class="form-group">
            <label>Your M-Pesa Number</label>
            <input type="tel" name="phone" placeholder="07XX XXX XXX" required>
            <div class="hint">Never shown publicly. Gifts are paid directly to this number.</div>
        </div>

        <div class="section">Goal (optional)</div>

        <div class="form-group">
            <label>Goal Title</label>
            <input type="text" name="goal_title" placeholder="e.g. New recording equipment" value="{{ old('goal_title') }}">
        </div>
        <div class="form-group">
            <label>Goal Amount (KES)</label>
            <input type="number" name="goal_amount" placeholder="e.g. 80000" value="{{ old('goal_amount') }}" min="100">
        </div>
        <div class="form-group">
            <label>Minimum Gift (KES)</label>
            <input type="number" name="min_gift" placeholder="50" value="{{ old('min_gift', 50) }}" min="50">
        </div>

        <div class="section">Account Password</div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Min 6 characters" required>
        </div>
        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" placeholder="Repeat password" required>
        </div>

        <button type="submit" class="btn">Create Creator Account →</button>
    </form>
    @endif

    <div class="login-link">Already have an account? <a href="{{ route('creator.login') }}">Sign in</a></div>
</div>
</body>
</html>
