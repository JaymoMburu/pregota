<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gift {{ $creator->display_name }} — Pregota</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh;display:flex;flex-direction:column;align-items:center;padding:24px 20px}
.card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.09);border-radius:24px;padding:32px 28px;max-width:420px;width:100%;margin-top:16px}

/* Creator profile */
.avatar{width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#00A651,#007A33);display:flex;align-items:center;justify-content:center;font-size:32px;font-weight:900;margin:0 auto 14px;overflow:hidden}
.avatar img{width:100%;height:100%;object-fit:cover}
.creator-name{font-size:22px;font-weight:900;text-align:center}
.creator-handle{font-size:13px;color:rgba(255,255,255,.6);text-align:center;margin-top:3px}
.creator-bio{font-size:13px;color:rgba(255,255,255,.78);text-align:center;margin-top:10px;line-height:1.6}

/* Goal bar */
.goal{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:12px;padding:14px 16px;margin:20px 0}
.goal-label{display:flex;justify-content:space-between;font-size:12px;color:rgba(255,255,255,.72);margin-bottom:8px}
.goal-bar{height:6px;background:rgba(255,255,255,.08);border-radius:999px;overflow:hidden}
.goal-fill{height:100%;background:linear-gradient(90deg,#00A651,#007A33);border-radius:999px;transition:.6s}
.goal-pct{font-size:11px;color:#a78bfa;margin-top:5px;text-align:right}

/* Stats row */
.stats{display:flex;gap:12px;margin-bottom:24px}
.stat{flex:1;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);border-radius:10px;padding:10px;text-align:center}
.stat-val{font-size:16px;font-weight:800;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.stat-lbl{font-size:10px;color:rgba(255,255,255,.6);margin-top:2px;text-transform:uppercase;letter-spacing:.06em}

/* Form */
.section-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.68);margin-bottom:8px}
.form-group{margin-bottom:14px}
label{display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.78);margin-bottom:6px}
input,textarea{width:100%;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.14);border-radius:10px;padding:13px 14px;color:#fff;font-size:16px;outline:none;transition:.2s;font-family:inherit}
input:focus,textarea:focus{border-color:#00A651;background:rgba(0,166,81,.1)}
input::placeholder,textarea::placeholder{color:rgba(255,255,255,.82)}
textarea{resize:none;height:64px}
.btn{width:100%;padding:15px;border-radius:12px;border:none;font-size:16px;font-weight:700;cursor:pointer;background:linear-gradient(135deg,#00A651,#007A33);color:#fff;transition:.2s;margin-top:4px}
.btn:hover{opacity:.9}
.btn:disabled{opacity:.45;cursor:not-allowed}

/* Fee preview */
.fee-preview{background:rgba(0,166,81,.07);border:1px solid rgba(0,166,81,.2);border-radius:10px;padding:11px 14px;margin-bottom:14px;font-size:12px;display:none}
.fee-row{display:flex;justify-content:space-between;padding:2px 0;color:rgba(255,255,255,.6)}
.fee-row.total{color:#fff;font-weight:700;border-top:1px solid rgba(255,255,255,.08);margin-top:5px;padding-top:7px}

.err{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);border-radius:10px;padding:11px 14px;font-size:13px;color:#fca5a5;margin-bottom:14px;display:none}
.success-box{background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.3);border-radius:14px;padding:24px;text-align:center;margin-top:16px;display:none}

.footer{margin-top:24px;font-size:11px;color:rgba(255,255,255,.2);text-align:center}
.pregota-link{color:rgba(255,255,255,.82);text-decoration:none;font-weight:700}
.pregota-link:hover{color:#25D366}
</style>
</head>
<body>

<div class="card">
    <!-- Creator profile -->
    <div class="avatar">
        @if($creator->photo_url)
            <img src="{{ $creator->photo_url }}" alt="{{ $creator->display_name }}">
        @else
            {{ strtoupper(substr($creator->display_name, 0, 1)) }}
        @endif
    </div>
    <div class="creator-name">{{ $creator->display_name }}</div>
    <div class="creator-handle">@{{ $creator->handle }}</div>
    @if($creator->bio)
    <div class="creator-bio">{{ $creator->bio }}</div>
    @endif

    <!-- Goal bar -->
    @if($creator->goal_title && $creator->goal_amount)
    <div class="goal">
        <div class="goal-label">
            <span>{{ $creator->goal_title }}</span>
            <span>KES {{ number_format($creator->total_received, 0) }} / {{ number_format($creator->goal_amount, 0) }}</span>
        </div>
        <div class="goal-bar"><div class="goal-fill" style="width:{{ $creator->goalProgress() }}%"></div></div>
        <div class="goal-pct">{{ $creator->goalProgress() }}% reached</div>
    </div>
    @endif

    <!-- Stats -->
    <div class="stats">
        <div class="stat">
            <div class="stat-val">{{ $creator->gifts()->where('status','paid')->count() }}</div>
            <div class="stat-lbl">Gifts</div>
        </div>
        <div class="stat">
            <div class="stat-val">KES {{ number_format($creator->total_received, 0) }}</div>
            <div class="stat-lbl">Received</div>
        </div>
        <div class="stat">
            <div class="stat-val">KES {{ number_format($creator->min_gift_amount, 0) }}</div>
            <div class="stat-lbl">Min Gift</div>
        </div>
    </div>

    <div class="section-label">Send a gift</div>
    <div class="err" id="errBox"></div>

    <form id="giftForm">
        <div class="form-group">
            <label>Gift Amount (KES)</label>
            <input type="number" id="amount" placeholder="Min KES {{ number_format($creator->min_gift_amount, 0) }}"
                min="{{ $creator->min_gift_amount }}" max="{{ config('pregota.max_amount') }}" required>
        </div>

        <div class="fee-preview" id="feePreview">
            <div class="fee-row"><span>{{ $creator->display_name }} receives</span><span id="fRecipient">—</span></div>
            <div class="fee-row"><span id="fFeeOutLabel">Payout fee</span><span id="fFeeOut">—</span></div>
            <div class="fee-row"><span id="fFeeInLabel">Deposit fee</span><span id="fFeeIn">—</span></div>
            <div class="fee-row total"><span>You pay (M-Pesa)</span><span id="fGross">—</span></div>
        </div>

        <div class="form-group">
            <label>Your M-Pesa Number</label>
            <input type="tel" id="phone" placeholder="07XX XXX XXX" required>
        </div>
        <div class="form-group">
            <label>Your Name (optional)</label>
            <input type="text" id="fan_name" placeholder="Stay anonymous" maxlength="60">
        </div>
        <div class="form-group">
            <label>Message (optional)</label>
            <textarea id="message" placeholder="Keep it up! Love your content..."></textarea>
        </div>
        <button type="submit" class="btn" id="sendBtn">Send Gift →</button>
    </form>

    <div class="success-box" id="successBox">
        <div style="font-size:40px;margin-bottom:12px">🎉</div>
        <div style="font-size:17px;font-weight:800;margin-bottom:6px">Gift Sent!</div>
        <div style="font-size:13px;color:rgba(255,255,255,.82)" id="successMsg"></div>
    </div>
</div>

<div class="footer">
    Powered by <a href="{{ route('home') }}" class="pregota-link">Pregota</a> · Anonymous gift transfers via M-Pesa
</div>

<script>
const CSRF        = document.querySelector('meta[name=csrf-token]').content;
const fmt         = n => 'KES ' + Number(n).toLocaleString('en-KE', {minimumFractionDigits:2});
const CREATOR_MIN = {{ $creator->min_gift_amount }};
const FEE_IN_PCT  = {{ config('pregota.fee_in_pct') }};
const FEE_OUT_PCT = {{ config('pregota.fee_out_pct') }};
const FEE_MIN     = {{ config('pregota.fee_min_kes') }};

document.getElementById('amount').addEventListener('input', function() {
    const v = parseFloat(this.value);
    const preview = document.getElementById('feePreview');
    if (!v || v < CREATOR_MIN) { preview.style.display='none'; return; }

    const feeOutCalc = v * FEE_OUT_PCT / (100 - FEE_OUT_PCT);
    const feeOut     = Math.max(FEE_MIN / 2, feeOutCalc);
    const faceValue  = v + feeOut;
    const feeInCalc  = faceValue * FEE_IN_PCT / (100 - FEE_IN_PCT);
    const feeIn      = Math.max(FEE_MIN, feeInCalc);
    const gross      = Math.ceil(faceValue + feeIn);

    document.getElementById('fRecipient').textContent   = fmt(v);
    document.getElementById('fFeeOut').textContent      = fmt(feeOut);
    document.getElementById('fFeeIn').textContent       = fmt(feeIn);
    document.getElementById('fGross').textContent       = fmt(gross);
    document.getElementById('fFeeOutLabel').textContent = feeOutCalc < FEE_MIN/2 ? 'Payout fee (minimum)' : `Payout fee (${FEE_OUT_PCT}%)`;
    document.getElementById('fFeeInLabel').textContent  = feeInCalc  < FEE_MIN   ? 'Deposit fee (minimum)' : `Deposit fee (${FEE_IN_PCT}%)`;
    preview.style.display = 'block';
});

document.getElementById('giftForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('sendBtn');
    const err = document.getElementById('errBox');
    err.style.display = 'none';
    btn.disabled = true; btn.textContent = 'Sending...';

    try {
        const res  = await fetch('/c/{{ $creator->handle }}/gift', {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
            body: JSON.stringify({
                amount:   document.getElementById('amount').value,
                phone:    document.getElementById('phone').value,
                fan_name: document.getElementById('fan_name').value.trim(),
                message:  document.getElementById('message').value.trim(),
            }),
        });
        const json = await res.json();

        if (json.success) {
            document.getElementById('giftForm').style.display = 'none';
            document.getElementById('successBox').style.display = 'block';
            document.getElementById('successMsg').textContent = json.message;
        } else {
            err.textContent = json.message || 'Something went wrong.';
            err.style.display = 'block';
        }
    } catch(e) {
        err.textContent = 'Network error. Please try again.';
        err.style.display = 'block';
    } finally {
        btn.disabled = false; btn.textContent = 'Send Gift →';
    }
});
</script>
</body>
</html>
