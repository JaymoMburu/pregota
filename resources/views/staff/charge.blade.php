<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Charge Customer â€” Pregota</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}input,textarea,select,button{font-family:inherit;font-size:inherit}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh;display:flex;flex-direction:column;align-items:center;padding:24px 20px}
.topbar{width:100%;max-width:420px;display:flex;justify-content:space-between;align-items:center;margin-bottom:24px}
.logo{font-size:18px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.back{font-size:13px;color:rgba(255,255,255,.68);text-decoration:none}

.card{width:100%;max-width:420px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:20px;padding:28px 24px}
.card-title{font-size:20px;font-weight:900;margin-bottom:4px}
.card-sub{font-size:13px;color:rgba(255,255,255,.68);margin-bottom:24px}

label{display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.78);margin-bottom:6px}
input{width:100%;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.15);border-radius:10px;padding:13px 14px;color:#fff;font-size:16px;outline:none;transition:.2s;font-family:inherit}
input:focus{border-color:#00A651;background:rgba(0,166,81,.08)}
input::placeholder{color:rgba(255,255,255,.82)}
.form-group{margin-bottom:16px}
.hint{font-size:11px;color:rgba(255,255,255,.6);margin-top:5px}

.section-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.6);margin:20px 0 12px;padding-top:16px;border-top:1px solid rgba(255,255,255,.06)}

/* Saved till pill */
.saved-till{display:flex;align-items:center;justify-content:space-between;background:rgba(74,222,128,.07);border:1px solid rgba(74,222,128,.2);border-radius:12px;padding:14px 16px;margin-bottom:16px}
.saved-till-info strong{font-size:14px;color:#4ade80;display:block}
.saved-till-info span{font-size:11px;color:rgba(255,255,255,.68);margin-top:2px;display:block}
.change-btn{font-size:12px;color:rgba(255,255,255,.6);background:none;border:none;cursor:pointer;text-decoration:underline}

/* Till type toggle */
.type-row{display:flex;gap:10px;margin-bottom:4px}
.type-opt{display:flex;align-items:center;gap:7px;font-size:13px;font-weight:600;color:rgba(255,255,255,.75);cursor:pointer;flex:1;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.12);border-radius:10px;padding:11px 13px}
.type-opt input[type=radio]{width:auto;accent-color:#00A651}
.save-check{display:flex;align-items:center;gap:8px;font-size:12px;color:rgba(255,255,255,.72);margin-top:10px;cursor:pointer}
.save-check input{width:auto;accent-color:#00A651}

.alert.error{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);color:#f87171;border-radius:8px;padding:10px 12px;margin-bottom:16px;font-size:13px}

/* Amount preview */
.amount-preview{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:12px;padding:14px 16px;margin-bottom:16px;display:none}
.preview-row{display:flex;justify-content:space-between;font-size:13px;padding:3px 0}
.preview-row .lbl{color:rgba(255,255,255,.72)}
.preview-row .val{font-weight:600}
.preview-divider{border:none;border-top:1px solid rgba(255,255,255,.07);margin:8px 0}
.preview-total{display:flex;justify-content:space-between;font-size:15px;font-weight:900}
.preview-total .val{color:#25D366}
.preview-note{font-size:10px;color:rgba(255,255,255,.82);margin-top:6px;text-align:center}

.submit-btn{width:100%;padding:15px;border-radius:12px;border:none;font-size:16px;font-weight:700;cursor:pointer;background:linear-gradient(135deg,#00A651,#007A33);color:#fff;margin-top:4px;transition:.2s}
.submit-btn:hover{opacity:.9}
</style>
</head>
<body>

<div class="topbar">
    <a href="{{ route('staff.dashboard') }}" class="back">â† Dashboard</a>
    <a href="{{ route('home') }}" class="logo">Pregota</a>
</div>

<div class="card">
    <div class="card-title">Charge Customer</div>
    <div class="card-sub">Enter the amount. Show the QR. Customer pays from their phone.</div>

    @if($errors->any())
    <div class="alert error">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('staff.charge.store') }}" id="chargeForm">
        @csrf

        <div class="form-group">
            <label>Amount (KES)</label>
            <input type="number" name="amount" id="amountInput"
                   placeholder="e.g. 2500" min="10" max="150000"
                   value="{{ old('amount') }}" required autofocus>
            <div class="hint">What the customer owes.</div>
        </div>

        <div class="amount-preview" id="amountPreview">
            <div class="preview-row"><span class="lbl">Your charge</span><span class="val" id="prvShare">â€”</span></div>
            <div class="preview-row"><span class="lbl">Pregota service fee</span><span class="val">KES 30</span></div>
            <hr class="preview-divider">
            <div class="preview-total"><span>Customer pays</span><span class="val" id="prvTotal">â€”</span></div>
            <div class="preview-note">KES 30 is Pregota's fee â€” not deducted from your charge</div>
        </div>

        <div class="form-group">
            <label>Description (optional)</label>
            <input type="text" name="description" placeholder="e.g. Table 5 Â· Lunch"
                   maxlength="60" value="{{ old('description') }}">
            <div class="hint">Appears on the customer's payment screen.</div>
        </div>

        @if($staff->hasTill())
        <input type="hidden" name="use_saved" id="useSaved" value="1">
        <div class="section-label">Payout Destination</div>
        <div class="saved-till" id="savedTillBox">
            <div class="saved-till-info">
                <strong>{{ $staff->till_type === 'till' ? 'Till (Lipa na M-Pesa)' : 'Paybill' }}</strong>
                <span>Saved â€” money goes here when customer pays</span>
            </div>
            <button type="button" class="change-btn" onclick="showTillForm()">Change</button>
        </div>
        @endif

        <div id="tillForm" style="{{ $staff->hasTill() ? 'display:none' : '' }}">
            <div class="section-label" style="{{ $staff->hasTill() ? '' : 'margin-top:0;padding-top:0;border-top:none' }}">Payout Destination</div>
            <div class="form-group">
                <div class="type-row">
                    <label class="type-opt">
                        <input type="radio" name="till_type" value="paybill"
                               {{ old('till_type', 'paybill') === 'paybill' ? 'checked' : '' }}
                               onchange="updateTillLabel()">
                        Paybill
                    </label>
                    <label class="type-opt">
                        <input type="radio" name="till_type" value="till"
                               {{ old('till_type') === 'till' ? 'checked' : '' }}
                               onchange="updateTillLabel()">
                        Till (Lipa na M-Pesa)
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label id="tillLabel">Paybill Number</label>
                <input type="text" name="till_number" id="tillInput"
                       placeholder="e.g. 522522" inputmode="numeric"
                       pattern="[0-9]{5,7}" maxlength="7"
                       value="{{ old('till_number') }}">
                <div class="hint">Money goes here the moment the customer pays.</div>
            </div>
            @if(!$staff->hasTill())
            <label class="save-check">
                <input type="checkbox" name="save_till" value="1" checked>
                Save this for future charges
            </label>
            @endif
        </div>

        <button type="submit" class="submit-btn" style="margin-top:20px">Generate QR â†’</button>
    </form>
</div>

<script>
const FMT = n => 'KES ' + Number(n).toLocaleString('en-KE');

document.getElementById('amountInput').addEventListener('input', function() {
    const v = parseInt(this.value);
    const box = document.getElementById('amountPreview');
    if (!v || v < 1) { box.style.display = 'none'; return; }
    document.getElementById('prvShare').textContent = FMT(v);
    document.getElementById('prvTotal').textContent = FMT(v + 30);
    box.style.display = 'block';
});

function updateTillLabel() {
    const isTill = document.querySelector('input[name="till_type"]:checked')?.value === 'till';
    const lbl = document.getElementById('tillLabel');
    const inp = document.getElementById('tillInput');
    if (lbl) lbl.textContent = isTill ? 'Till Number' : 'Paybill Number';
    if (inp) inp.placeholder  = isTill ? 'e.g. 123456' : 'e.g. 522522';
}

function showTillForm() {
    document.getElementById('savedTillBox').style.display = 'none';
    document.getElementById('tillForm').style.display     = 'block';
    document.getElementById('useSaved').value             = '0';
}
</script>
</body>
</html>

