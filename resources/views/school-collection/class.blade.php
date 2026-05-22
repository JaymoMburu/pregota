<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $collection->school_name }} — {{ $class->class_name }} Collection</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0f0f1a;color:#fff;min-height:100vh}
.topbar{padding:14px 20px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.07);background:#0f0f1a;position:sticky;top:0;z-index:10}
.logo{font-size:18px;font-weight:900;background:linear-gradient(135deg,#c084fc,#f472b6);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}

.hero{padding:28px 20px 16px;max-width:560px;margin:0 auto}
.school-badge{display:inline-flex;align-items:center;gap:6px;padding:5px 12px;border-radius:20px;background:rgba(124,58,237,.15);border:1px solid rgba(124,58,237,.3);font-size:12px;color:#c084fc;font-weight:600;margin-bottom:12px}
h1{font-size:clamp(20px,4.5vw,30px);font-weight:900;line-height:1.15;margin-bottom:6px}
.meta{font-size:13px;color:rgba(255,255,255,.4);display:flex;flex-wrap:wrap;gap:10px}

.status-banner{margin:0 20px 16px;max-width:520px;margin-left:auto;margin-right:auto;padding:12px 16px;border-radius:10px;font-size:13px;font-weight:600;text-align:center;background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);color:#f87171}

.stats-row{max-width:560px;margin:0 auto 20px;padding:0 20px;display:grid;grid-template-columns:1fr 1fr;gap:12px}
.stat-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:12px;padding:16px;text-align:center}
.stat-num{font-size:26px;font-weight:900;color:#c084fc}
.stat-label{font-size:11px;color:rgba(255,255,255,.4);margin-top:4px}

.main{max-width:560px;margin:0 auto;padding:0 20px 40px;display:flex;flex-direction:column;gap:18px}
.card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:20px}
.card-title{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.35);margin-bottom:14px}

/* Balance info panel */
.balance-info{display:none;background:rgba(96,165,250,.07);border:1px solid rgba(96,165,250,.2);border-radius:10px;padding:12px 14px;margin-bottom:14px}
.balance-info.complete{background:rgba(74,222,128,.07);border-color:rgba(74,222,128,.25)}
.balance-row{display:flex;justify-content:space-between;align-items:center;font-size:13px}
.balance-row+.balance-row{margin-top:6px}
.balance-key{color:rgba(255,255,255,.5)}
.balance-val{font-weight:700}
.balance-val.paid{color:#60a5fa}
.balance-val.remaining{color:#fbbf24}
.balance-val.done{color:#4ade80}
.balance-progress{height:5px;background:rgba(255,255,255,.1);border-radius:3px;margin-top:10px;overflow:hidden}
.balance-progress-fill{height:100%;background:linear-gradient(90deg,#7c3aed,#4ade80);border-radius:3px;transition:width .4s}
.complete-badge{display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:20px;background:rgba(74,222,128,.12);border:1px solid rgba(74,222,128,.3);color:#4ade80;font-size:12px;font-weight:700;margin-bottom:10px}

.form-group{margin-bottom:12px}
label{display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.5);margin-bottom:6px}
input{width:100%;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.15);border-radius:10px;padding:12px 14px;color:#fff;font-size:14px;outline:none;transition:.2s;font-family:inherit}
input:focus{border-color:#7c3aed;background:rgba(124,58,237,.08)}
input::placeholder{color:rgba(255,255,255,.3)}
.hint{font-size:11px;color:rgba(255,255,255,.35);margin-top:5px}
.fee-line{display:flex;justify-content:space-between;font-size:12px;color:rgba(255,255,255,.4);background:rgba(255,255,255,.03);padding:8px 12px;border-radius:8px;margin-bottom:12px}
.fee-line strong{color:rgba(255,255,255,.75)}

.full-btn{margin-top:8px;padding:8px 14px;border-radius:8px;background:rgba(124,58,237,.1);border:1px solid rgba(124,58,237,.25);color:#c084fc;font-size:12px;font-weight:600;cursor:pointer;transition:.15s;width:100%;text-align:left}
.full-btn:hover{background:rgba(124,58,237,.18)}

.pay-btn{width:100%;padding:15px;border-radius:12px;border:none;font-size:16px;font-weight:700;cursor:pointer;background:linear-gradient(135deg,#7c3aed,#db2777);color:#fff;transition:.2s}
.pay-btn:hover:not(:disabled){opacity:.9;transform:translateY(-1px)}
.pay-btn:disabled{opacity:.5;cursor:not-allowed;transform:none}

.status-overlay{display:none;text-align:center;padding:20px 0}
.spin{width:36px;height:36px;border:3px solid rgba(124,58,237,.25);border-top-color:#7c3aed;border-radius:50%;animation:spin .8s linear infinite;margin:0 auto 12px}
@keyframes spin{to{transform:rotate(360deg)}}
.status-icon{font-size:40px;margin-bottom:10px}
.status-msg{font-size:15px;font-weight:700;margin-bottom:6px}
.status-sub{font-size:13px;color:rgba(255,255,255,.45)}
.btn-sm{padding:10px 20px;border-radius:8px;border:1px solid rgba(255,255,255,.15);background:rgba(255,255,255,.07);color:#fff;font-size:13px;font-weight:600;cursor:pointer;margin-top:14px}

/* History list */
.paid-list{display:flex;flex-direction:column;gap:8px}
.paid-item{display:flex;align-items:center;gap:12px}
.paid-avatar{width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#7c3aed,#db2777);display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;flex-shrink:0}
.paid-name{flex:1;font-size:13px;font-weight:600}
.paid-amount{font-size:13px;font-weight:700;color:#c084fc}
.paid-time{font-size:11px;color:rgba(255,255,255,.3)}
.empty{text-align:center;padding:16px;font-size:13px;color:rgba(255,255,255,.3)}

/* Partial badge */
.partial-badge{display:inline-block;font-size:10px;padding:2px 7px;border-radius:8px;background:rgba(251,191,36,.12);border:1px solid rgba(251,191,36,.2);color:#fbbf24;font-weight:700;margin-left:6px;vertical-align:middle}

/* Frozen banner */
.frozen-banner{margin:0 20px 16px;max-width:520px;margin-left:auto;margin-right:auto;padding:14px 16px;border-radius:10px;background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3);color:#f87171;font-size:13px;font-weight:600;text-align:center}

/* Trust notice */
.trust-notice{margin:0 20px 0;max-width:520px;margin-left:auto;margin-right:auto;padding:10px 14px;border-radius:9px;background:rgba(59,130,246,.06);border:1px solid rgba(59,130,246,.15);color:rgba(255,255,255,.4);font-size:11.5px;display:flex;align-items:center;gap:8px;margin-bottom:16px}
.trust-notice a{color:#60a5fa;text-decoration:none}
.trust-notice a:hover{text-decoration:underline}

/* Report modal */
.modal-backdrop{display:none;position:fixed;inset:0;background:rgba(0,0,0,.7);z-index:100;align-items:center;justify-content:center;padding:20px}
.modal-backdrop.open{display:flex}
.modal{background:#161624;border:1px solid rgba(255,255,255,.1);border-radius:16px;padding:28px;max-width:400px;width:100%}
.modal h3{font-size:16px;font-weight:800;margin-bottom:6px;color:#f87171}
.modal p{font-size:13px;color:rgba(255,255,255,.45);margin-bottom:16px;line-height:1.5}
.modal textarea{width:100%;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.15);border-radius:10px;padding:12px 14px;color:#fff;font-size:13px;outline:none;resize:vertical;min-height:90px;font-family:inherit}
.modal textarea:focus{border-color:#ef4444}
.modal-actions{display:flex;gap:10px;margin-top:14px}
.report-submit-btn{flex:1;padding:12px;border-radius:9px;border:none;background:#ef4444;color:#fff;font-size:14px;font-weight:700;cursor:pointer}
.report-submit-btn:hover{background:#dc2626}
.report-submit-btn:disabled{opacity:.5;cursor:not-allowed}
.modal-cancel-btn{padding:12px 20px;border-radius:9px;border:1px solid rgba(255,255,255,.12);background:rgba(255,255,255,.05);color:rgba(255,255,255,.5);font-size:14px;font-weight:600;cursor:pointer}
.report-link{display:inline-flex;align-items:center;gap:5px;font-size:11px;color:rgba(239,68,68,.55);text-decoration:none;cursor:pointer;border:none;background:none;padding:0;transition:.15s}
.report-link:hover{color:#f87171}
</style>
</head>
<body>

<div class="topbar">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    <span style="font-size:12px;color:rgba(255,255,255,.35)">Collection</span>
</div>

@if($collection->is_frozen)
<div class="frozen-banner">
    🚫 This collection has been suspended pending review. Payments are not currently accepted.
</div>
@elseif(! $collection->isOpen())
<div class="status-banner">🔒 This collection is now closed. No new payments are accepted.</div>
@endif

<div class="hero">
    <div class="school-badge">🏫 {{ $collection->school_name }}</div>
    <h1>{{ $class->class_name }} · {{ $collection->term_label }}</h1>
    <div class="meta">
        <span>Class Teacher: <strong style="color:rgba(255,255,255,.75)">{{ $class->teacher_name }}</strong></span>
        <span>·</span>
        <span>Collection amount: <strong style="color:rgba(255,255,255,.75)">KES {{ number_format($collection->amount_per_student) }}</strong></span>
    </div>
</div>

<div class="stats-row">
    <div class="stat-card">
        <div class="stat-num">{{ $class->contributor_count }}</div>
        <div class="stat-label">Payments received</div>
    </div>
    <div class="stat-card">
        <div class="stat-num">KES {{ number_format($class->total_raised) }}</div>
        <div class="stat-label">Collected this class</div>
    </div>
</div>

<div class="trust-notice">
    <span>🔒</span>
    <span>Verify this collection is genuine before paying. If you have concerns,
        <button class="report-link" onclick="openReport()">report it</button>.
    </span>
</div>

<div class="main">

    @if($collection->isOpen() && ! $collection->is_frozen)
    <div class="card" id="formCard">
        <div class="card-title">Pay Collection</div>
        <div id="formArea">

            <div class="form-group">
                <label>Student ID / Admission Number</label>
                <input type="text" id="studentId" placeholder="e.g. ADM-2024-001" maxlength="40" autocomplete="off" oninput="onStudentIdInput()" style="text-transform:uppercase" required>
                <div class="hint">Enter the admission number or student ID as issued by the school.</div>
            </div>

            <div class="form-group">
                <label>Student Name</label>
                <input type="text" id="studentName" placeholder="e.g. Grace Wanjiku" maxlength="80" autocomplete="off" oninput="validateForm()" required>
                <div class="hint">Enter the student's full name.</div>
            </div>

            <!-- Balance panel: shown when student has prior payments -->
            <div class="balance-info" id="balanceInfo">
                <div id="completeMsg" style="display:none">
                    <span class="complete-badge">✅ Fully Paid</span>
                    <div style="font-size:13px;color:rgba(255,255,255,.5)">This student has completed their collection payment.</div>
                </div>
                <div id="partialMsg">
                    <div class="balance-row">
                        <span class="balance-key">Already paid</span>
                        <span class="balance-val paid" id="balancePaidAmt"></span>
                    </div>
                    <div class="balance-row">
                        <span class="balance-key">Remaining balance</span>
                        <span class="balance-val remaining" id="balanceRemAmt"></span>
                    </div>
                    <div class="balance-row">
                        <span class="balance-key">Collection amount</span>
                        <span class="balance-val" style="color:rgba(255,255,255,.6)" id="balanceReqAmt"></span>
                    </div>
                    <div class="balance-progress">
                        <div class="balance-progress-fill" id="balanceBar" style="width:0%"></div>
                    </div>
                </div>
            </div>

            <div id="paymentFields">
                <div style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:10px;padding:12px 14px;margin-bottom:14px;display:flex;justify-content:space-between;align-items:center">
                    <span style="font-size:12px;color:rgba(255,255,255,.45)">Collection amount set by school</span>
                    <span style="font-size:18px;font-weight:900;color:#c084fc">KES {{ number_format($collection->amount_per_student) }}</span>
                </div>

                <div class="form-group">
                    <label>Amount You Are Paying Now</label>
                    <input type="number" id="payAmount" placeholder="Enter amount e.g. 500" min="50" oninput="onAmountInput()" required>
                    <button type="button" class="full-btn" id="fullBtn" onclick="payFull()">
                        Pay full remaining — KES <span id="fullBtnAmt">{{ number_format($collection->amount_per_student) }}</span>
                    </button>
                    <div class="hint">Pay the full amount or any partial amount — minimum KES 50.</div>
                </div>

                <div class="fee-line" id="feeLine">
                    <span>Paying: <strong id="feeLineAmount">—</strong></span>
                    <span>Service fee: KES 30 → Total charged: <strong id="feeLineTotal">—</strong></span>
                </div>

                <div class="form-group">
                    <label>Your M-Pesa Number (Parent / Guardian)</label>
                    <input type="tel" id="payerPhone" placeholder="07XX XXX XXX" oninput="validateForm()">
                    <div class="hint">You will receive an STK Push on this number.</div>
                </div>

                <button class="pay-btn" id="payBtn" onclick="sendPayment()" disabled>Pay via M-Pesa</button>
            </div>
        </div>

        <div class="status-overlay" id="statusOverlay">
            <div class="spin" id="spinIcon"></div>
            <div class="status-icon" id="statusIcon" style="display:none"></div>
            <div class="status-msg" id="statusMsg">Sending STK Push…</div>
            <div class="status-sub" id="statusSub">Check your phone and enter your M-Pesa PIN.</div>
            <button class="btn-sm" id="retryBtn" style="display:none" onclick="resetForm()">Try Again</button>
        </div>
    </div>
    @endif

    <div style="text-align:center;padding:4px 0 4px">
        <button class="report-link" onclick="openReport()" style="font-size:12px;color:rgba(239,68,68,.45)">
            ⚠️ Report a concern about this collection
        </button>
    </div>

    <div class="card">
        <div class="card-title">Recent Payments ({{ $payments->count() }})</div>
        @if($payments->count())
        <div class="paid-list">
            @foreach($payments as $p)
            <div class="paid-item">
                <div class="paid-avatar">{{ strtoupper(substr($p->student_name, 0, 1)) }}</div>
                <div style="flex:1">
                    <div class="paid-name">{{ $p->student_name }}</div>
                    <div class="paid-time">{{ $p->paid_at?->diffForHumans() }}</div>
                </div>
                <div class="paid-amount">KES {{ number_format($p->amount) }}</div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty">No payments yet — share this link with parents.</div>
        @endif
    </div>

</div>

<!-- Report Fraud Modal -->
<div class="modal-backdrop" id="reportModal">
    <div class="modal">
        <h3>⚠️ Report a Concern</h3>
        <p>If you believe this collection is fraudulent or being used for something suspicious, let us know. We will review and may suspend the collection while we investigate.</p>
        <textarea id="reportReason" placeholder="Describe your concern (e.g. not from this school, unknown organiser, suspicious activity)…" maxlength="300"></textarea>
        <div id="reportMsg" style="display:none;margin-top:10px;font-size:13px;font-weight:600;color:#4ade80"></div>
        <div class="modal-actions">
            <button class="modal-cancel-btn" onclick="closeReport()">Cancel</button>
            <button class="report-submit-btn" id="reportSubmitBtn" onclick="submitReport()">Submit Report</button>
        </div>
    </div>
</div>

<script>
const SLUG        = '{{ $collection->slug }}';
const CLASS_TOKEN = '{{ $class->class_token }}';
const CSRF        = document.querySelector('meta[name=csrf-token]').content;
const REQUIRED    = {{ $collection->amount_per_student }};

let studentData = { total_paid: 0, balance: REQUIRED, required: REQUIRED, is_complete: false };
let idTimeout;
let pollTimer;
let paymentId;

// ── Student ID input: debounce lookup ────────────────────────────────────────
function onStudentIdInput() {
    document.getElementById('studentId').value = document.getElementById('studentId').value.toUpperCase();
    clearTimeout(idTimeout);
    hideBalanceInfo();
    resetStudentData();
    validateForm();
    const id = document.getElementById('studentId').value.trim();
    if (id.length >= 2) {
        idTimeout = setTimeout(() => lookupBalance(id), 600);
    }
}

async function lookupBalance(studentId) {
    try {
        const url = `/school-collection/student-balance?slug=${encodeURIComponent(SLUG)}&class_token=${encodeURIComponent(CLASS_TOKEN)}&student_id=${encodeURIComponent(studentId)}`;
        const res  = await fetch(url);
        const data = await res.json();
        studentData = data;
        if (data.known_name && !document.getElementById('studentName').value.trim()) {
            document.getElementById('studentName').value = data.known_name;
        }
        updateBalanceUI(data);
        validateForm();
    } catch(e) {}
}

function resetStudentData() {
    studentData = { total_paid: 0, balance: REQUIRED, required: REQUIRED, is_complete: false };
    setAmountDefault(REQUIRED, false);
}

// ── Balance UI ────────────────────────────────────────────────────────────────
function updateBalanceUI(data) {
    const panel = document.getElementById('balanceInfo');
    if (data.total_paid > 0) {
        panel.style.display = 'block';
        document.getElementById('balancePaidAmt').textContent = 'KES ' + data.total_paid.toLocaleString();
        document.getElementById('balanceRemAmt').textContent  = 'KES ' + data.balance.toLocaleString();
        document.getElementById('balanceReqAmt').textContent  = 'KES ' + data.required.toLocaleString();
        const pct = Math.round((data.total_paid / data.required) * 100);
        document.getElementById('balanceBar').style.width = pct + '%';

        if (data.is_complete) {
            panel.classList.add('complete');
            document.getElementById('completeMsg').style.display = 'block';
            document.getElementById('partialMsg').style.display  = 'none';
            document.getElementById('paymentFields').style.display = 'none';
        } else {
            panel.classList.remove('complete');
            document.getElementById('completeMsg').style.display = 'none';
            document.getElementById('partialMsg').style.display  = 'block';
            document.getElementById('paymentFields').style.display = 'block';
            setAmountDefault(data.balance, true);
        }
    } else {
        hideBalanceInfo();
        setAmountDefault(REQUIRED, false);
    }
}

function hideBalanceInfo() {
    const panel = document.getElementById('balanceInfo');
    panel.style.display = 'none';
    panel.classList.remove('complete');
    document.getElementById('paymentFields').style.display = 'block';
}

// ── Amount helpers ────────────────────────────────────────────────────────────
function setAmountDefault(balance, prefill = false) {
    const input = document.getElementById('payAmount');
    input.value = prefill ? balance : '';
    input.max   = balance;
    document.getElementById('fullBtnAmt').textContent = balance.toLocaleString();
    updateFeeDisplay(prefill ? balance : 0);
}

function payFull() {
    const bal = studentData.balance;
    document.getElementById('payAmount').value = bal;
    onAmountInput();
}

function onAmountInput() {
    const val = parseInt(document.getElementById('payAmount').value) || 0;
    updateFeeDisplay(val);
    validateForm();
}

function updateFeeDisplay(amount) {
    const gross = (amount || 0) + 30;
    document.getElementById('feeLineAmount').textContent = 'KES ' + (amount || 0).toLocaleString();
    document.getElementById('feeLineTotal').textContent  = 'KES ' + gross.toLocaleString();
    document.getElementById('payBtn').textContent        = 'Pay KES ' + gross.toLocaleString() + ' via M-Pesa';
}

// ── Validation ────────────────────────────────────────────────────────────────
function validateForm() {
    const id       = document.getElementById('studentId').value.trim();
    const name     = document.getElementById('studentName').value.trim();
    const phone    = document.getElementById('payerPhone').value.trim();
    const amount   = parseInt(document.getElementById('payAmount').value) || 0;
    const phoneOk  = /^(\+?254|0)[17]\d{8}$/.test(phone);
    const amountOk = amount >= 50 && amount <= studentData.balance;
    document.getElementById('payBtn').disabled = !(id && name && phoneOk && amountOk && !studentData.is_complete);
}

// ── Payment ───────────────────────────────────────────────────────────────────
async function sendPayment() {
    const student_id   = document.getElementById('studentId').value.trim();
    const student_name = document.getElementById('studentName').value.trim();
    const phone        = document.getElementById('payerPhone').value.trim();
    const amount       = parseInt(document.getElementById('payAmount').value);

    showOverlay('pending', 'Sending STK Push…', 'Check your phone and enter your M-Pesa PIN.');

    try {
        const res  = await fetch(`/school-collection/${SLUG}/class/${CLASS_TOKEN}/pay`, {
            method:  'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF},
            body:    JSON.stringify({ student_id, student_name, amount, phone }),
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
            const res  = await fetch(`/school-collection/status?payment_id=${paymentId}`);
            const data = await res.json();
            if (data.status === 'paid') {
                const msg = data.class_total
                    ? 'KES ' + data.class_total.toLocaleString() + ' collected for your class so far.'
                    : 'Payment recorded. Thank you!';
                showOverlay('success', '✅ Payment received!', msg);
                setTimeout(() => location.reload(), 3000);
                return;
            }
            if (data.status === 'failed') {
                showOverlay('error', '❌ Payment not completed', 'The STK Push was not confirmed. Please try again.');
                return;
            }
            pollStatus();
        } catch(e) { pollStatus(); }
    }, 2500);
}

function showOverlay(state, msg, sub) {
    document.getElementById('formArea').style.display      = 'none';
    document.getElementById('statusOverlay').style.display = 'block';
    document.getElementById('spinIcon').style.display      = state === 'pending' ? 'block' : 'none';
    document.getElementById('statusIcon').style.display    = state !== 'pending' ? 'block' : 'none';
    document.getElementById('statusIcon').textContent      = state === 'success' ? '✅' : '❌';
    document.getElementById('statusMsg').textContent       = msg;
    document.getElementById('statusSub').textContent       = sub;
    document.getElementById('retryBtn').style.display      = state === 'error' ? 'inline-block' : 'none';
}

function resetForm() {
    clearTimeout(pollTimer);
    document.getElementById('formArea').style.display      = 'block';
    document.getElementById('statusOverlay').style.display = 'none';
}

// Initialise presets on page load
setAmountDefault(REQUIRED, false);

// ── Report fraud ──────────────────────────────────────────────────────────────
function openReport()  { document.getElementById('reportModal').classList.add('open'); }
function closeReport() { document.getElementById('reportModal').classList.remove('open'); }

async function submitReport() {
    const reason = document.getElementById('reportReason').value.trim();
    if (!reason) { document.getElementById('reportReason').focus(); return; }

    const btn = document.getElementById('reportSubmitBtn');
    btn.disabled = true;
    btn.textContent = 'Submitting…';

    try {
        const res  = await fetch(`/school-collection/${SLUG}/report`, {
            method:  'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body:    JSON.stringify({ reason }),
        });
        const data = await res.json();
        if (data.frozen) {
            const msg = document.getElementById('reportMsg');
            msg.textContent = '✅ Report received. This collection has been suspended pending review.';
            msg.style.display = 'block';
            document.querySelector('.modal-actions').style.display = 'none';
            setTimeout(() => location.reload(), 2500);
        }
    } catch(e) {
        btn.disabled    = false;
        btn.textContent = 'Submit Report';
    }
}
</script>
</body>
</html>
