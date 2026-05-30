<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Set Up School Collection â€” Pregota</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
*{box-sizing:border-box;margin:0;padding:0}input,textarea,select,button{font-family:inherit;font-size:inherit}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh;display:flex}
.panel-left{width:42%;height:100vh;position:sticky;top:0;background:radial-gradient(circle 260px at -40px -80px,rgba(0,166,81,.35),transparent 70%),radial-gradient(circle 200px at calc(100% + 20px) 100%,rgba(0,122,51,.28),transparent 70%),linear-gradient(150deg,#030D07,#0A1A0F 55%,#0F2418);display:flex;flex-direction:column;padding:40px 44px;overflow:hidden}
.left-logo{font-size:22px;font-weight:900;position:relative;z-index:1;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.left-center{flex:1;display:flex;flex-direction:column;justify-content:center;position:relative;z-index:1;gap:28px;padding:40px 0}
.headline h1{font-size:clamp(24px,2.8vw,38px);font-weight:900;line-height:1.12;letter-spacing:-.5px}
.headline h1 em{font-style:normal;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.headline p{margin-top:10px;font-size:14px;color:rgba(255,255,255,.72);line-height:1.65;max-width:300px}
.flow-steps{display:flex;flex-direction:column;gap:14px}
.flow-step{display:flex;gap:12px;align-items:flex-start}
.flow-num{width:26px;height:26px;border-radius:50%;background:linear-gradient(135deg,#00A651,#007A33);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:900;flex-shrink:0;margin-top:1px}
.flow-text strong{font-size:13px;color:rgba(255,255,255,.85);display:block;margin-bottom:1px}
.flow-text span{font-size:12px;color:rgba(255,255,255,.68);line-height:1.5}
.left-foot{margin-top:auto;position:relative;z-index:1;font-size:11px;color:rgba(255,255,255,.82)}

.panel-right{width:58%;min-height:100vh;background:#0B141A;display:flex;flex-direction:column;border-left:1px solid rgba(255,255,255,.06)}
.right-nav{padding:16px 32px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.06)}
.logo-sm{font-size:18px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.right-body{flex:1;padding:32px;overflow-y:auto}
.form-wrap{max-width:500px}
.form-title{font-size:20px;font-weight:900;margin-bottom:4px}
.form-subtitle{font-size:13px;color:rgba(255,255,255,.68);margin-bottom:24px}

.section-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.6);margin-bottom:10px;margin-top:20px}
.form-group{margin-bottom:12px}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
label{display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.78);margin-bottom:6px}
input{width:100%;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.15);border-radius:10px;padding:11px 13px;color:#fff;font-size:16px;outline:none;transition:.2s;font-family:inherit}
input:focus{border-color:#00A651;background:rgba(0,166,81,.08)}
input::placeholder{color:rgba(255,255,255,.82)}
.hint{font-size:11px;color:rgba(255,255,255,.6);margin-top:5px}
.hint.green{color:#4ade80}
.alert.error{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);color:#f87171;border-radius:8px;padding:10px 12px;margin-bottom:14px;font-size:13px}

/* Classes builder */
.classes-builder{display:flex;flex-direction:column;gap:8px;margin-bottom:10px}
.class-row{display:grid;grid-template-columns:1fr 1fr 32px;gap:8px;align-items:center;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:10px;padding:10px 12px}
.class-row input{background:transparent;border:none;padding:4px 0;font-size:14px;border-bottom:1px solid rgba(255,255,255,.12);border-radius:0}
.class-row input:focus{border-bottom-color:#00A651;background:transparent}
.remove-class{background:none;border:none;color:rgba(239,68,68,.5);font-size:18px;cursor:pointer;line-height:1;padding:0;width:24px;text-align:center}
.remove-class:hover{color:#f87171}
.add-class-btn{width:100%;padding:10px;border-radius:10px;border:1px dashed rgba(255,255,255,.2);background:rgba(255,255,255,.03);color:rgba(255,255,255,.78);font-size:13px;font-weight:600;cursor:pointer;transition:.15s}
.add-class-btn:hover{border-color:#00A651;color:#25D366;background:rgba(0,166,81,.06)}
.class-header{display:grid;grid-template-columns:1fr 1fr 32px;gap:8px;padding:0 12px;margin-bottom:4px}
.class-header span{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.82)}

.submit-btn{width:100%;padding:15px;border-radius:12px;border:none;font-size:16px;font-weight:700;cursor:pointer;background:linear-gradient(135deg,#00A651,#007A33);color:#fff;margin-top:10px;transition:.2s}
.submit-btn:hover{opacity:.9;transform:translateY(-1px)}

.m-logo{display:none}
@media(max-width:820px){
    body{flex-direction:column}
    .panel-left{display:none}
    .m-logo{display:block;font-size:22px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none;padding:14px 18px 4px}
    .panel-right{width:100%;border-left:none}
    .right-body{padding:16px}
    .form-row{grid-template-columns:1fr}
}
</style>
</head>
<body>
<div class="panel-left">
    <a href="{{ route('home') }}" class="left-logo">Pregota</a>
    <div class="left-center">
        <div class="headline">
            <h1>School collections.<br><em>No cash handling.<br>No reconciliation.</em></h1>
            <p>Remedial classes, trips, PTA activities, prize giving â€” parents pay directly via M-Pesa. No cash to the teacher's personal number. Admin sees every payment in real time.</p>
        </div>
        <div class="flow-steps">
            <div class="flow-step"><div class="flow-num">1</div><div class="flow-text"><strong>Admin sets up the collection</strong><span>Adds all classes and class teachers in one form. Takes 3 minutes.</span></div></div>
            <div class="flow-step"><div class="flow-num">2</div><div class="flow-text"><strong>Each teacher gets their own link</strong><span>They share it with their class parents on WhatsApp.</span></div></div>
            <div class="flow-step"><div class="flow-num">3</div><div class="flow-text"><strong>Parents pay via M-Pesa STK Push</strong><span>No cash. No withdrawal. Instant confirmation per student.</span></div></div>
            <div class="flow-step"><div class="flow-num">4</div><div class="flow-text"><strong>Admin pays out when ready</strong><span>One click sends the total directly to the school M-Pesa. Every Thursday â€” done in seconds.</span></div></div>
        </div>
    </div>
    <div class="left-foot">Â© 2026 Pregota Â· KES 30 per payment</div>
</div>

<div class="panel-right">
    <a href="{{ route('home') }}" class="m-logo">Pregota</a>
    @include('partials.module-nav', ['activeModule' => 'school'])
    <nav class="right-nav">
        <a href="{{ route('home') }}" class="logo-sm">Pregota</a>
        <span style="font-size:12px;color:rgba(255,255,255,.6)">School Collections Â· Setup</span>
    </nav>
    <div class="right-body">
        <div class="form-wrap">
            <div class="form-title">Set Up School Collection</div>
            <div class="form-subtitle">You'll get a private admin dashboard link plus a unique link for each class teacher.</div>

            @if($errors->any())
            <div class="alert error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('school-collection.store') }}">
                @csrf

                <div class="section-label">School Details</div>
                <div class="form-group">
                    <label>School Name</label>
                    <input type="text" name="school_name" placeholder="Greenfield Primary School"
                           value="{{ old('school_name') }}" maxlength="120" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Term / Period</label>
                        <input type="text" name="term_label" placeholder="Term 2 Â· 2026"
                               value="{{ old('term_label') }}" maxlength="60" required>
                    </div>
                    <div class="form-group">
                        <label>Amount per Student (KES)</label>
                        <input type="number" name="amount_per_student" placeholder="1850"
                               value="{{ old('amount_per_student', 1850) }}" min="50" required>
                    </div>
                </div>

                <div class="section-label">Admin Details</div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Your Name (Admin)</label>
                        <input type="text" name="admin_name" placeholder="Mrs. Wanjiku"
                               value="{{ old('admin_name') }}" maxlength="60" required>
                    </div>
                    <div class="form-group">
                        <label>School M-Pesa Number</label>
                        <input type="tel" name="recipient_phone" placeholder="07XX XXX XXX" required>
                        <div class="hint green">ðŸ”’ Encrypted. Deleted after payout.</div>
                    </div>
                </div>

                <div class="section-label">Classes & Teachers</div>
                <div class="class-header">
                    <span>Class Name</span>
                    <span>Class Teacher</span>
                    <span></span>
                </div>
                <div class="classes-builder" id="classesBuilder">
                    <div class="class-row">
                        <input type="text" name="classes[0][class_name]" placeholder="e.g. Form 1A" maxlength="60" required>
                        <input type="text" name="classes[0][teacher_name]" placeholder="Mr. Kamau" maxlength="60" required>
                        <button type="button" class="remove-class" onclick="removeClass(this)" title="Remove">Ã—</button>
                    </div>
                </div>
                <button type="button" class="add-class-btn" onclick="addClass()">+ Add Another Class</button>

                <button type="submit" class="submit-btn">Create Collection & Get Links â†’</button>
                <div style="text-align:center;margin-top:10px;font-size:11px;color:rgba(255,255,255,.25)">KES 30 fee per payment Â· Added on top of the amount Â· Paid by parent</div>
            </form>
        </div>
    </div>
</div>

<script>
let idx = 1;
function addClass() {
    const row = document.createElement('div');
    row.className = 'class-row';
    row.innerHTML = `
        <input type="text" name="classes[${idx}][class_name]" placeholder="e.g. Form 2B" maxlength="60" required>
        <input type="text" name="classes[${idx}][teacher_name]" placeholder="Ms. Grace" maxlength="60" required>
        <button type="button" class="remove-class" onclick="removeClass(this)" title="Remove">Ã—</button>`;
    document.getElementById('classesBuilder').appendChild(row);
    row.querySelector('input').focus();
    idx++;
}
function removeClass(btn) {
    const rows = document.querySelectorAll('.class-row');
    if (rows.length <= 1) return;
    btn.closest('.class-row').remove();
}
</script>
</body>
</html>

