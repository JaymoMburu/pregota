<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Split Bill — Pregota</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh;display:flex}
.panel-left{width:42%;height:100vh;position:sticky;top:0;background:radial-gradient(circle 260px at -40px -80px,rgba(0,166,81,.35),transparent 70%),radial-gradient(circle 200px at calc(100% + 20px) 100%,rgba(0,122,51,.28),transparent 70%),linear-gradient(150deg,#030D07,#0A1A0F 55%,#0F2418);display:flex;flex-direction:column;padding:40px 44px;overflow:hidden}
.left-logo{font-size:22px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.left-center{flex:1;display:flex;flex-direction:column;justify-content:center;gap:28px}
.headline h1{font-size:clamp(24px,2.8vw,38px);font-weight:900;line-height:1.12;letter-spacing:-.5px}
.headline h1 em{font-style:normal;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.headline p{margin-top:10px;font-size:14px;color:rgba(255,255,255,.72);line-height:1.65;max-width:300px}
.flow-steps{display:flex;flex-direction:column;gap:14px}
.flow-step{display:flex;gap:12px;align-items:flex-start}
.flow-num{width:26px;height:26px;border-radius:50%;background:linear-gradient(135deg,#00A651,#007A33);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:900;flex-shrink:0;margin-top:1px}
.flow-text strong{font-size:13px;color:rgba(255,255,255,.85);display:block;margin-bottom:1px}
.flow-text span{font-size:12px;color:rgba(255,255,255,.68);line-height:1.5}
.left-foot{margin-top:auto;font-size:11px;color:rgba(255,255,255,.82)}

.panel-right{width:58%;min-height:100vh;background:#0B141A;display:flex;flex-direction:column;border-left:1px solid rgba(255,255,255,.06)}
.right-nav{padding:16px 32px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.06)}
.logo-sm{font-size:18px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.right-body{flex:1;padding:32px;overflow-y:auto}
.form-wrap{max-width:460px}
.form-title{font-size:20px;font-weight:900;margin-bottom:4px}
.form-subtitle{font-size:13px;color:rgba(255,255,255,.68);margin-bottom:24px}
.section-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.6);margin-bottom:10px;margin-top:20px}
.form-group{margin-bottom:12px}
label{display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.78);margin-bottom:6px}
input{width:100%;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.15);border-radius:10px;padding:11px 13px;color:#fff;font-size:14px;outline:none;transition:.2s;font-family:inherit}
input:focus{border-color:#00A651;background:rgba(0,166,81,.08)}
input::placeholder{color:rgba(255,255,255,.82)}
.hint{font-size:11px;color:rgba(255,255,255,.6);margin-top:5px}
.hint.green{color:#4ade80}
.alert.error{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);color:#f87171;border-radius:8px;padding:10px 12px;margin-bottom:14px;font-size:13px}
.submit-btn{width:100%;padding:15px;border-radius:12px;border:none;font-size:16px;font-weight:700;cursor:pointer;background:linear-gradient(135deg,#00A651,#007A33);color:#fff;margin-top:10px;transition:.2s}
.submit-btn:hover{opacity:.9;transform:translateY(-1px)}
@media(max-width:820px){body{flex-direction:column}.panel-left{width:100%;height:auto;position:static;padding:24px 20px}.panel-right{width:100%;border-left:none}.right-body{padding:20px}}
</style>
</head>
<body>
<div class="panel-left">
    <a href="{{ route('home') }}" class="left-logo">Pregota</a>
    <div class="left-center">
        <div class="headline">
            <h1>One bill.<br><em>Everyone pays.<br>Zero chasing.</em></h1>
            <p>Enter the total. Show the QR. The table handles themselves. Money lands on your M-Pesa when the last person pays.</p>
        </div>
        <div class="flow-steps">
            <div class="flow-step"><div class="flow-num">1</div><div class="flow-text"><strong>You enter the bill total</strong><span>Takes 10 seconds. No itemisation needed.</span></div></div>
            <div class="flow-step"><div class="flow-num">2</div><div class="flow-text"><strong>Show the QR to the table</strong><span>Everyone scans from their phone. No app needed.</span></div></div>
            <div class="flow-step"><div class="flow-num">3</div><div class="flow-text"><strong>Each person pays their share</strong><span>They type what they owe. M-Pesa STK Push. Done.</span></div></div>
            <div class="flow-step"><div class="flow-num">4</div><div class="flow-text"><strong>Full amount lands on your M-Pesa</strong><span>Instant payout the moment the bill is complete.</span></div></div>
        </div>
    </div>
    <div class="left-foot">© 2026 Pregota · KES 30 per payment</div>
</div>

<div class="panel-right">
    @include('partials.module-nav', ['activeModule' => ''])
    <nav class="right-nav">
        <a href="{{ route('home') }}" class="logo-sm">Pregota</a>
        <span style="font-size:12px;color:rgba(255,255,255,.6)">Bill Split</span>
    </nav>
    <div class="right-body">
        <div class="form-wrap">
            <div class="form-title">New Bill Split</div>
            <div class="form-subtitle">Enter the total and your M-Pesa. The table pays from the QR.</div>

            @if($errors->any())
            <div class="alert error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('bill-split.store') }}">
                @csrf

                <div class="form-group">
                    <label>Business / Restaurant Name</label>
                    <input type="text" name="business_name" placeholder="Java House Westlands"
                           value="{{ old('business_name') }}" maxlength="80" required>
                    <div class="hint">Shown on the customer's M-Pesa payment prompt — so they know exactly who they're paying.</div>
                </div>

                <div class="form-group">
                    <label>Table / Bill Label (optional)</label>
                    <input type="text" name="label" placeholder="Table 7 · Sunday lunch"
                           value="{{ old('label') }}" maxlength="60">
                    <div class="hint">Shown on the payment screen so customers know what they're paying for.</div>
                </div>

                <div class="form-group">
                    <label>Total Bill Amount (KES)</label>
                    <input type="number" name="total_amount" id="totalAmount"
                           placeholder="4800" value="{{ old('total_amount') }}"
                           min="100" max="150000" required>
                    <div class="hint" id="feeHint"></div>
                </div>

                <div class="section-label">Payout Destination</div>
                <div class="form-group">
                    <label>Payment Type</label>
                    <div style="display:flex;gap:10px;margin-bottom:4px">
                        <label style="display:flex;align-items:center;gap:7px;font-size:13px;font-weight:600;text-transform:none;letter-spacing:0;color:rgba(255,255,255,.75);cursor:pointer;flex:1;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.12);border-radius:10px;padding:11px 13px" id="labelPaybill">
                            <input type="radio" name="payout_type" value="paybill" checked style="width:auto;accent-color:#00A651" onchange="updateLabel()">
                            Paybill
                        </label>
                        <label style="display:flex;align-items:center;gap:7px;font-size:13px;font-weight:600;text-transform:none;letter-spacing:0;color:rgba(255,255,255,.75);cursor:pointer;flex:1;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.12);border-radius:10px;padding:11px 13px" id="labelTill">
                            <input type="radio" name="payout_type" value="till" style="width:auto;accent-color:#00A651" onchange="updateLabel()">
                            Till (Lipa na M-Pesa)
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label id="destLabel">Paybill Number</label>
                    <input type="text" name="payout_destination" id="destInput"
                           placeholder="e.g. 522522" inputmode="numeric"
                           pattern="[0-9]{5,7}" maxlength="7" required>
                    <div class="hint green">🔒 Encrypted. Deleted immediately after payout. Never visible to customers or staff.</div>
                    <div class="hint" id="destHint">Money goes directly to your restaurant's Paybill — not to any personal phone.</div>
                </div>

                <div class="section-label">Tip Link (optional)</div>
                <div class="form-group">
                    <label>Your Pregota Tip Handle</label>
                    <input type="text" name="tip_handle" placeholder="grace"
                           value="{{ old('tip_handle') }}" maxlength="30"
                           oninput="this.value=this.value.toLowerCase().replace(/[^a-z0-9._-]/g,'')">
                    <div class="hint">After paying their share, customers will be offered to tip you directly. Leave blank to skip.</div>
                </div>

                <button type="submit" class="submit-btn">Generate Split QR →</button>
                <div style="text-align:center;margin-top:10px;font-size:11px;color:rgba(255,255,255,.25)">KES 30 added per person · Paid by each customer · Expires in 90 min</div>
            </form>
        </div>
    </div>
</div>

<script>
function updateLabel() {
    const isTill = document.querySelector('input[name="payout_type"]:checked').value === 'till';
    document.getElementById('destLabel').textContent = isTill ? 'Till Number' : 'Paybill Number';
    document.getElementById('destInput').placeholder  = isTill ? 'e.g. 123456' : 'e.g. 522522';
    document.getElementById('destHint').textContent   = isTill
        ? 'Money goes directly to your Till (Buy Goods) — not to any personal phone.'
        : 'Money goes directly to your restaurant\'s Paybill — not to any personal phone.';
}

document.getElementById('totalAmount').addEventListener('input', function() {
    const v = parseInt(this.value);
    const hint = document.getElementById('feeHint');
    if (!v || v < 100) { hint.textContent = ''; return; }
    hint.textContent = 'Customers pay their share + KES 30 each. You receive KES ' + v.toLocaleString('en-KE') + ' exactly.';
    hint.style.color = 'rgba(255,255,255,.6)';
});
</script>
</body>
</html>
