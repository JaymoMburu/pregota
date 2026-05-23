<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Create Your Tip Page — Pregota</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh;display:flex}

.panel-left{width:42%;height:100vh;position:sticky;top:0;background:radial-gradient(circle 260px at -40px -80px,rgba(0,166,81,.35),transparent 70%),radial-gradient(circle 200px at calc(100% + 20px) 100%,rgba(0,122,51,.28),transparent 70%),linear-gradient(150deg,#030D07,#0A1A0F 55%,#0F2418);display:flex;flex-direction:column;padding:40px 44px;overflow:hidden}
.left-logo{font-size:22px;font-weight:900;position:relative;z-index:1;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.left-center{flex:1;display:flex;flex-direction:column;justify-content:center;position:relative;z-index:1;gap:32px}
.headline h1{font-size:clamp(26px,3vw,38px);font-weight:900;line-height:1.15;letter-spacing:-.5px}
.headline h1 em{font-style:normal;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.headline p{margin-top:10px;font-size:14px;color:rgba(255,255,255,.72);line-height:1.65;max-width:280px}
.check-list{display:flex;flex-direction:column;gap:12px}
.check-item{display:flex;align-items:center;gap:10px;font-size:13px;color:rgba(255,255,255,.65)}
.check-item::before{content:"✓";width:20px;height:20px;background:rgba(34,197,94,.2);border:1px solid rgba(34,197,94,.35);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:10px;color:#4ade80;flex-shrink:0}
.left-foot{margin-top:auto;position:relative;z-index:1;font-size:11px;color:rgba(255,255,255,.82)}

.panel-right{width:58%;min-height:100vh;background:#0B141A;display:flex;flex-direction:column;border-left:1px solid rgba(255,255,255,.06)}
.right-nav{padding:16px 32px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.06)}
.logo-sm{font-size:18px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.right-body{flex:1;padding:32px;overflow-y:auto}
.form-wrap{max-width:440px}
.form-title{font-size:20px;font-weight:900;margin-bottom:6px}
.form-subtitle{font-size:13px;color:rgba(255,255,255,.68);margin-bottom:24px}

.section-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.6);margin-bottom:12px;margin-top:20px}
.form-group{margin-bottom:14px}
label{display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.78);margin-bottom:6px}
input,select{width:100%;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.15);border-radius:10px;padding:12px 14px;color:#fff;font-size:16px;outline:none;transition:.2s;font-family:inherit}
input:focus,select:focus{border-color:#00A651;background:rgba(0,166,81,.08)}
input::placeholder{color:rgba(255,255,255,.82)}
select option{background:#0B1810}
.hint{font-size:11px;color:rgba(255,255,255,.6);margin-top:5px}
.hint.green{color:#4ade80}

.handle-wrap{position:relative}
.handle-prefix{position:absolute;left:14px;top:50%;transform:translateY(-50%);font-size:14px;color:rgba(255,255,255,.6);pointer-events:none}
.handle-wrap input{padding-left:90px}

.emoji-grid{display:grid;grid-template-columns:repeat(6,1fr);gap:6px;margin-bottom:6px}
.emoji-opt{background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:8px;padding:8px 4px;cursor:pointer;text-align:center;font-size:20px;transition:.15s}
.emoji-opt:hover,.emoji-opt.selected{border-color:#00A651;background:rgba(0,166,81,.15)}
input[type=hidden]{}

.alert.error{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);color:#f87171;border-radius:8px;padding:10px 12px;margin-bottom:14px;font-size:13px}

.submit-btn{width:100%;padding:15px;border-radius:12px;border:none;font-size:16px;font-weight:700;cursor:pointer;background:linear-gradient(135deg,#00A651,#007A33);color:#fff;margin-top:8px;transition:.2s}
.submit-btn:hover{opacity:.9;transform:translateY(-1px)}
.login-link{text-align:center;margin-top:16px;font-size:13px;color:rgba(255,255,255,.6)}
.login-link a{color:#25D366;text-decoration:none;font-weight:600}

.m-logo{display:none}
@media(max-width:820px){
    body{flex-direction:column}
    .panel-left{display:none}
    .m-logo{display:block;font-size:22px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none;padding:14px 18px 4px}
    .panel-right{width:100%;border-left:none}
    .right-body{padding:16px}
}
</style>
</head>
<body>

<div class="panel-left">
    <a href="{{ route('home') }}" class="left-logo">Pregota</a>
    <div class="left-center">
        <div class="headline">
            <h1>Your tips.<br>Your privacy.<br><em>Your link.</em></h1>
            <p>No employer needed. Register yourself in 2 minutes and start receiving tips today.</p>
        </div>
        <div class="check-list">
            <div class="check-item">Your M-Pesa number stays completely private</div>
            <div class="check-item">Get a personal link: pregota.com/t/yourname</div>
            <div class="check-item">Money arrives straight to your phone</div>
            <div class="check-item">Free to use — KES 15 fee per tip paid by customer</div>
            <div class="check-item">See your earnings and customer feedback</div>
        </div>
    </div>
    <div class="left-foot">© 2026 Pregota · For Service Workers in Kenya</div>
</div>

<div class="panel-right">
    <a href="{{ route('home') }}" class="m-logo">Pregota</a>
    @include('partials.module-nav', ['activeModule' => 'tips'])
    <nav class="right-nav">
        <a href="{{ route('staff.landing') }}" class="logo-sm">Pregota</a>
        <span style="font-size:12px;color:rgba(255,255,255,.6)">Individual Registration</span>
    </nav>
    <div class="right-body">
        <div class="form-wrap">
            <div class="form-title">Create Your Tip Page</div>
            <div class="form-subtitle">Free to sign up. Takes 2 minutes.</div>

            @if($errors->any())
            <div class="alert error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('staff.register.post') }}">
                @csrf

                <div class="section-label">Your Identity</div>

                <div class="form-group">
                    <label>Your Name</label>
                    <input type="text" name="name" placeholder="Grace Wanjiku"
                           value="{{ old('name') }}" maxlength="60" required>
                </div>

                <div class="form-group">
                    <label>Your Job / Role</label>
                    <input type="text" name="role" placeholder="Waitress · Villa Rosa Kempinski"
                           value="{{ old('role') }}" maxlength="60" required>
                    <div class="hint">Shown on your tip page. Be specific — it builds trust.</div>
                </div>

                <div class="form-group">
                    <label>Your Tip Page Handle</label>
                    <div class="handle-wrap">
                        <span class="handle-prefix">pregota.com/t/</span>
                        <input type="text" name="handle" id="handleInput"
                               placeholder="grace" value="{{ old('handle') }}"
                               maxlength="30" pattern="[a-zA-Z0-9]+" required
                               oninput="this.value=this.value.toLowerCase().replace(/[^a-z0-9]/g,'')">
                    </div>
                    <div class="hint" id="handleHint">Letters and numbers only. This is your permanent tip link.</div>
                </div>

                <div class="form-group">
                    <label>Pick an Emoji (optional)</label>
                    <div class="emoji-grid">
                        @foreach(['😊','🌟','💪','🙌','✨','🔥','💅','🍽️','☕','🚗','🛵','🎵'] as $emoji)
                        <div class="emoji-opt {{ old('avatar_emoji', '😊') === $emoji ? 'selected' : '' }}"
                             onclick="selectEmoji('{{ $emoji }}', this)">{{ $emoji }}</div>
                        @endforeach
                    </div>
                    <input type="hidden" name="avatar_emoji" id="avatarEmoji" value="{{ old('avatar_emoji', '😊') }}">
                </div>

                <div class="section-label">Your M-Pesa Details</div>

                <div class="form-group">
                    <label>M-Pesa Number for Tips</label>
                    <input type="tel" name="payout_phone" placeholder="07XX XXX XXX" required>
                    <div class="hint green">🔒 Encrypted. Never shown to anyone — not even us.</div>
                </div>

                <div class="section-label">Login Details</div>

                <div class="form-group">
                    <label>Login Phone Number</label>
                    <input type="tel" name="login_phone" placeholder="07XX XXX XXX"
                           value="{{ old('login_phone') }}" required>
                    <div class="hint">Use this to log in to your dashboard.</div>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="At least 6 characters" required>
                </div>

                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" placeholder="Repeat your password" required>
                </div>

                <button type="submit" class="submit-btn">Create My Tip Page →</button>
            </form>

            <div class="login-link">Already have an account? <a href="{{ route('staff.login') }}">Sign in →</a></div>
        </div>
    </div>
</div>

<script>
function selectEmoji(val, el) {
    document.querySelectorAll('.emoji-opt').forEach(e => e.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('avatarEmoji').value = val;
}

const handleInput = document.getElementById('handleInput');
const handleHint  = document.getElementById('handleHint');
let handleTimer;

handleInput.addEventListener('input', () => {
    clearTimeout(handleTimer);
    const val = handleInput.value;
    if (!val) { handleHint.textContent = 'Letters and numbers only. This is your permanent tip link.'; handleHint.style.color = ''; return; }
    handleHint.textContent = `Your link will be: pregota.com/t/${val}`;
    handleHint.style.color = '#25D366';
});
</script>
</body>
</html>
