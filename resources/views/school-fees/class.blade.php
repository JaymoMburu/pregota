<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $collection->school_name }} â€” {{ $class->class_name }} Fees</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
*{box-sizing:border-box;margin:0;padding:0}input,textarea,select,button{font-family:inherit;font-size:inherit}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh}
.topbar{padding:14px 20px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.07);background:#0B141A;position:sticky;top:0;z-index:10}
.logo{font-size:18px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}

.hero{padding:28px 20px 16px;max-width:560px;margin:0 auto}
.school-badge{display:inline-flex;align-items:center;gap:6px;padding:5px 12px;border-radius:20px;background:rgba(0,166,81,.15);border:1px solid rgba(0,166,81,.3);font-size:12px;color:#25D366;font-weight:600;margin-bottom:12px}
h1{font-size:clamp(20px,4.5vw,30px);font-weight:900;line-height:1.15;margin-bottom:6px}
.meta{font-size:13px;color:rgba(255,255,255,.68);display:flex;flex-wrap:wrap;gap:10px}

.status-banner{margin:0 20px 16px;max-width:520px;margin-left:auto;margin-right:auto;padding:12px 16px;border-radius:10px;font-size:13px;font-weight:600;text-align:center;background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);color:#f87171}

.stats-row{max-width:560px;margin:0 auto 20px;padding:0 20px;display:grid;grid-template-columns:1fr 1fr;gap:12px}
.stat-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:12px;padding:16px;text-align:center}
.stat-num{font-size:26px;font-weight:900;color:#25D366}
.stat-label{font-size:11px;color:rgba(255,255,255,.68);margin-top:4px}

.main{max-width:560px;margin:0 auto;padding:0 20px 40px;display:flex;flex-direction:column;gap:18px}
.card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:20px}
.card-title{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.6);margin-bottom:14px}

.amount-display{text-align:center;padding:16px 0 8px}
.amount-big{font-size:48px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;line-height:1}
.amount-label{font-size:13px;color:rgba(255,255,255,.68);margin-top:4px}

.form-group{margin-bottom:12px}
label{display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.78);margin-bottom:6px}
input{width:100%;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.15);border-radius:10px;padding:12px 14px;color:#fff;font-size:14px;outline:none;transition:.2s;font-family:inherit}
input:focus{border-color:#00A651;background:rgba(0,166,81,.08)}
input::placeholder{color:rgba(255,255,255,.82)}
.hint{font-size:11px;color:rgba(255,255,255,.6);margin-top:5px}
.fee-line{display:flex;justify-content:space-between;font-size:12px;color:rgba(255,255,255,.68);background:rgba(255,255,255,.03);padding:8px 12px;border-radius:8px;margin-bottom:12px}
.fee-line strong{color:rgba(255,255,255,.75)}

.pay-btn{width:100%;padding:15px;border-radius:12px;border:none;font-size:16px;font-weight:700;cursor:pointer;background:linear-gradient(135deg,#00A651,#007A33);color:#fff;transition:.2s}
.pay-btn:hover:not(:disabled){opacity:.9;transform:translateY(-1px)}
.pay-btn:disabled{opacity:.5;cursor:not-allowed;transform:none}

.status-overlay{display:none;text-align:center;padding:20px 0}
.spin{width:36px;height:36px;border:3px solid rgba(0,166,81,.25);border-top-color:#00A651;border-radius:50%;animation:spin .8s linear infinite;margin:0 auto 12px}
@keyframes spin{to{transform:rotate(360deg)}}
.status-icon{font-size:40px;margin-bottom:10px}
.status-msg{font-size:15px;font-weight:700;margin-bottom:6px}
.status-sub{font-size:13px;color:rgba(255,255,255,.72)}
.btn-sm{padding:10px 20px;border-radius:8px;border:1px solid rgba(255,255,255,.15);background:rgba(255,255,255,.07);color:#fff;font-size:13px;font-weight:600;cursor:pointer;margin-top:14px}

.paid-list{display:flex;flex-direction:column;gap:8px}
.paid-item{display:flex;align-items:center;gap:12px}
.paid-avatar{width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#00A651,#007A33);display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;flex-shrink:0}
.paid-name{flex:1;font-size:13px;font-weight:600}
.paid-amount{font-size:13px;font-weight:700;color:#25D366}
.paid-time{font-size:11px;color:rgba(255,255,255,.82)}
.empty{text-align:center;padding:16px;font-size:13px;color:rgba(255,255,255,.82)}
</style>
</head>
<body>

<div class="topbar">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    <span style="font-size:12px;color:rgba(255,255,255,.6)">School Fees</span>
</div>

@if(! $collection->isOpen())
<div class="status-banner">ðŸ”’ This collection is now closed. No new payments are accepted.</div>
@endif

<div class="hero">
    <div class="school-badge">ðŸ« {{ $collection->school_name }}</div>
    <h1>{{ $class->class_name }} Â· {{ $collection->term_label }}</h1>
    <div class="meta">
        <span>Class Teacher: <strong style="color:rgba(255,255,255,.75)">{{ $class->teacher_name }}</strong></span>
        <span>Â·</span>
        <span>Fees: <strong style="color:rgba(255,255,255,.75)">KES {{ number_format($collection->amount_per_student) }}</strong> per student</span>
    </div>
</div>

<div class="stats-row">
    <div class="stat-card">
        <div class="stat-num">{{ $class->contributor_count }}</div>
        <div class="stat-label">Students paid</div>
    </div>
    <div class="stat-card">
        <div class="stat-num">KES {{ number_format($class->total_raised) }}</div>
        <div class="stat-label">Collected this class</div>
    </div>
</div>

<div class="main">

    @if($collection->isOpen())
    <div class="card" id="formCard">
        <div class="card-title">Pay School Fees</div>
        <div id="formArea">
            <div class="amount-display">
                <div class="amount-big">KES {{ number_format($collection->amount_per_student) }}</div>
                <div class="amount-label">per student Â· {{ $collection->term_label }}</div>
            </div>

            <div class="fee-line">
                <span>Fees: <strong>KES {{ number_format($collection->amount_per_student) }}</strong></span>
                <span>Service fee: KES 30 â†’ Total: <strong>KES {{ number_format($collection->amount_per_student + 30) }}</strong></span>
            </div>

            <div class="form-group">
                <label>Student Name</label>
                <input type="text" id="studentName" placeholder="e.g. Grace Wanjiku" maxlength="80" required>
                <div class="hint">Enter the student's full name as it appears in the register.</div>
            </div>
            <div class="form-group">
                <label>Your M-Pesa Number (Parent / Guardian)</label>
                <input type="tel" id="payerPhone" placeholder="07XX XXX XXX" oninput="validateForm()">
                <div class="hint">You will receive an STK Push on this number.</div>
            </div>

            <button class="pay-btn" id="payBtn" onclick="sendPayment()" disabled>
                Pay KES {{ number_format($collection->amount_per_student + 30) }} via M-Pesa
            </button>
        </div>

        <div class="status-overlay" id="statusOverlay">
            <div class="spin" id="spinIcon"></div>
            <div class="status-icon" id="statusIcon" style="display:none"></div>
            <div class="status-msg" id="statusMsg">Sending STK Pushâ€¦</div>
            <div class="status-sub" id="statusSub">Check your phone and enter your M-Pesa PIN.</div>
            <button class="btn-sm" id="retryBtn" style="display:none" onclick="resetForm()">Try Again</button>
        </div>
    </div>
    @endif

    <div class="card">
        <div class="card-title">Paid Students ({{ $class->contributor_count }})</div>
        @if($payments->count())
        <div class="paid-list">
            @foreach($payments as $p)
            <div class="paid-item">
                <div class="paid-avatar">{{ strtoupper(substr($p->student_name, 0, 1)) }}</div>
                <div>
                    <div class="paid-name">{{ $p->student_name }}</div>
                    <div class="paid-time">{{ $p->paid_at?->diffForHumans() }}</div>
                </div>
                <div class="paid-amount">KES {{ number_format($p->amount) }}</div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty">No payments yet â€” share this link with parents.</div>
        @endif
    </div>

</div>

<script>
const SLUG       = '{{ $collection->slug }}';
const CLASS_TOKEN= '{{ $class->class_token }}';
const CSRF       = document.querySelector('meta[name=csrf-token]').content;
const AMOUNT     = {{ $collection->amount_per_student }};
let pollTimer, paymentId;

function validateForm() {
    const phone = document.getElementById('payerPhone').value.trim();
    const name  = document.getElementById('studentName').value.trim();
    document.getElementById('payBtn').disabled = !(name && /^(\+?254|0)[17]\d{8}$/.test(phone));
}
document.getElementById('studentName')?.addEventListener('input', validateForm);

async function sendPayment() {
    const name  = document.getElementById('studentName').value.trim();
    const phone = document.getElementById('payerPhone').value.trim();

    showOverlay('pending', 'Sending STK Pushâ€¦', 'Check your phone and enter your M-Pesa PIN.');

    try {
        const res  = await fetch(`/school-fees/${SLUG}/class/${CLASS_TOKEN}/pay`, {
            method:  'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
            body:    JSON.stringify({student_name: name, amount: AMOUNT, phone}),
        });
        const data = await res.json();

        if (!data.success) { showOverlay('error', 'Error', data.message || 'Something went wrong.'); return; }
        paymentId = data.payment_id;
        pollStatus();
    } catch(e) {
        showOverlay('error', 'Network Error', 'Check your connection and try again.');
    }
}

function pollStatus() {
    pollTimer = setTimeout(async () => {
        try {
            const res  = await fetch(`/school-fees/status?payment_id=${paymentId}`);
            const data = await res.json();
            if (data.status === 'paid') {
                showOverlay('success', 'âœ… Payment received!', 'Thank you. KES ' + data.class_total?.toLocaleString() + ' collected for your class so far.');
                setTimeout(() => location.reload(), 3000);
                return;
            }
            if (data.status === 'failed') { showOverlay('error', 'âŒ Payment not completed', 'The STK Push was not confirmed. Please try again.'); return; }
            pollStatus();
        } catch(e) { pollStatus(); }
    }, 2500);
}

function showOverlay(state, msg, sub) {
    document.getElementById('formArea').style.display      = 'none';
    document.getElementById('statusOverlay').style.display = 'block';
    document.getElementById('spinIcon').style.display      = state==='pending' ? 'block' : 'none';
    document.getElementById('statusIcon').style.display    = state!=='pending' ? 'block' : 'none';
    document.getElementById('statusIcon').textContent      = state==='success' ? 'âœ…' : 'âŒ';
    document.getElementById('statusMsg').textContent       = msg;
    document.getElementById('statusSub').textContent       = sub;
    document.getElementById('retryBtn').style.display      = state==='error' ? 'inline-block' : 'none';
}

function resetForm() {
    clearTimeout(pollTimer);
    document.getElementById('formArea').style.display      = 'block';
    document.getElementById('statusOverlay').style.display = 'none';
}
</script>
</body>
</html>

