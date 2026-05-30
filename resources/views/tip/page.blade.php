<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tip {{ $staff->name }} â€” Pregota</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}input,textarea,select,button{font-family:inherit;font-size:inherit}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh;display:flex;flex-direction:column}
.nav{padding:14px 24px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.08)}
.logo{font-size:20px;font-weight:900;background:linear-gradient(135deg,#00A651,#007A33);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.nav-link{color:rgba(255,255,255,.78);text-decoration:none;font-size:13px;font-weight:600;padding:7px 14px;border:1px solid rgba(255,255,255,.15);border-radius:8px}

.main{flex:1;display:flex;align-items:flex-start;justify-content:center;padding:32px 20px}
.card{max-width:400px;width:100%}

/* Staff profile */
.profile{text-align:center;margin-bottom:28px}
.avatar{width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#00A651,#007A33);display:flex;align-items:center;justify-content:center;font-size:38px;margin:0 auto 14px}
.staff-name{font-size:24px;font-weight:900;margin-bottom:4px}
.staff-role{font-size:13px;color:rgba(255,255,255,.72)}
.business-tag{display:inline-flex;align-items:center;gap:6px;background:rgba(0,166,81,.12);border:1px solid rgba(0,166,81,.25);border-radius:20px;padding:4px 12px;font-size:12px;color:#a78bfa;margin-top:8px}

/* Rating badge */
.rating-badge{display:flex;align-items:center;justify-content:center;gap:6px;margin-top:12px;font-size:13px;color:rgba(255,255,255,.78)}
.stars{color:#fbbf24;letter-spacing:1px}

/* Preset amounts */
.amounts-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.68);margin-bottom:10px}
.preset-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:8px;margin-bottom:14px}
.preset-btn{background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.12);border-radius:10px;padding:12px 6px;cursor:pointer;text-align:center;color:#fff;font-size:13px;font-weight:700;transition:.15s}
.preset-btn:hover,.preset-btn.selected{border-color:#00A651;background:rgba(0,166,81,.15);color:#25D366}

/* Form */
.form-group{margin-bottom:14px}
label{display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.78);margin-bottom:6px}
input{width:100%;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.15);border-radius:10px;padding:13px 16px;color:#fff;font-size:15px;outline:none;transition:.2s;font-family:inherit}
input:focus{border-color:#00A651;background:rgba(0,166,81,.1)}
input::placeholder{color:rgba(255,255,255,.82)}

.fee-preview{background:rgba(0,166,81,.07);border:1px solid rgba(0,166,81,.2);border-radius:10px;padding:10px 14px;margin-bottom:14px;font-size:12px;display:none}
.fee-row{display:flex;justify-content:space-between;padding:2px 0;color:rgba(255,255,255,.6)}
.fee-row.total{color:#fff;font-weight:700;border-top:1px solid rgba(255,255,255,.08);margin-top:5px;padding-top:6px}

.btn{width:100%;padding:15px;border-radius:12px;border:none;font-size:16px;font-weight:700;cursor:pointer;background:linear-gradient(135deg,#00A651,#007A33);color:#fff;margin-top:4px;transition:.2s}
.btn:disabled{opacity:.45;cursor:not-allowed}

/* Feedback step */
.feedback-step{display:none;text-align:center}
.star-row{display:flex;justify-content:center;gap:10px;margin:16px 0;font-size:40px;cursor:pointer}
.star-row span{transition:.15s;filter:grayscale(1);opacity:.4}
.star-row span.lit{filter:none;opacity:1}
.tag-grid{display:flex;flex-wrap:wrap;gap:8px;justify-content:center;margin:14px 0}
.tag-btn{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);border-radius:20px;padding:7px 14px;font-size:12px;cursor:pointer;color:rgba(255,255,255,.7);transition:.15s}
.tag-btn:hover,.tag-btn.selected{background:rgba(0,166,81,.18);border-color:#00A651;color:#25D366}
textarea{width:100%;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);border-radius:10px;padding:12px;color:#fff;font-size:13px;resize:none;height:70px;outline:none;margin-top:10px;font-family:inherit}
textarea::placeholder{color:rgba(255,255,255,.25)}

/* Success */
.success-box{background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.3);border-radius:14px;padding:24px;text-align:center;display:none}
.status-dot{width:9px;height:9px;border-radius:50%;background:#f59e0b;display:inline-block;animation:pulse 1.5s infinite;margin-right:6px;vertical-align:middle}
@keyframes pulse{0%,100%{opacity:1}50%{opacity:.3}}

.footer{padding:14px;text-align:center;color:rgba(255,255,255,.25);font-size:11px;border-top:1px solid rgba(255,255,255,.06)}
</style>
</head>
<body>
<nav class="nav">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    <a href="{{ route('home') }}" class="nav-link">Send a Gift</a>
</nav>

<div class="main">
    <div class="card">

        <!-- Profile -->
        <div class="profile">
            <div class="avatar">{{ $staff->avatar_emoji }}</div>
            <div class="staff-name">{{ $staff->name }}</div>
            @if($staff->role)
            <div class="staff-role">{{ $staff->role }}</div>
            @endif
            <div class="business-tag">{{ $staff->business->logo_emoji }} {{ $staff->business->name }}@if($staff->branch) Â· {{ $staff->branch }}@endif</div>
            @php $avg = $staff->averageRating(); $count = $staff->tipCount(); @endphp
            @if($count > 0)
            <div class="rating-badge">
                <span class="stars">{{ str_repeat('â˜…', floor($avg)) }}{{ $avg - floor($avg) >= 0.5 ? 'Â½' : '' }}</span>
                <span>{{ $avg }} Â· {{ $count }} {{ Str::plural('tip', $count) }}</span>
            </div>
            @endif
        </div>

        <!-- Privacy badge -->
        <div style="display:flex;align-items:center;gap:10px;background:rgba(34,197,94,.07);border:1px solid rgba(34,197,94,.18);border-radius:10px;padding:10px 14px;margin-bottom:22px">
            <span style="font-size:20px;flex-shrink:0">ðŸ›¡ï¸</span>
            <div style="flex:1">
                <div style="font-size:12px;font-weight:700;color:#4ade80">Privacy Protected</div>
                <div style="font-size:11px;color:rgba(255,255,255,.68);margin-top:1px;line-height:1.5">{{ $staff->name }}'s number is never visible â€” not to you, not to their employer.</div>
            </div>
            <a href="{{ route('staff.landing') }}" style="font-size:10px;color:rgba(74,222,128,.55);text-decoration:none;white-space:nowrap;flex-shrink:0">Learn more</a>
        </div>

        <!-- Tip form -->
        <div id="tipForm">
            <div class="amounts-label">Choose tip amount</div>
            <div class="preset-grid">
                <button class="preset-btn" onclick="selectPreset(this, 50)">KES 50</button>
                <button class="preset-btn" onclick="selectPreset(this, 100)">KES 100</button>
                <button class="preset-btn" onclick="selectPreset(this, 200)">KES 200</button>
                <button class="preset-btn" onclick="selectPreset(this, 500)">KES 500</button>
            </div>

            <div class="form-group">
                <label>Or enter amount (KES)</label>
                <input type="number" id="amount" placeholder="Any amount" min="{{ config('pregota.min_amount') }}" max="{{ config('pregota.max_amount') }}" oninput="clearPresets();updateFee()">
            </div>

            @if(!$feeWaived)
            <div class="fee-preview" id="feePreview">
                <div class="fee-row"><span>{{ $staff->name }} receives</span><span id="fRecipient">â€”</span></div>
                <div class="fee-row"><span>Service fee</span><span>KES {{ $flatFee }}</span></div>
                <div class="fee-row total"><span>You pay (M-Pesa)</span><span id="fGross">â€”</span></div>
            </div>
            @else
            <div id="feePreview" style="display:none"></div>
            @endif

            <!-- Gift nudge shown when amount â‰¥ nudge threshold -->
            <div id="giftNudge" style="display:none;background:rgba(37,211,102,.07);border:1px solid rgba(37,211,102,.25);border-radius:10px;padding:10px 14px;margin-bottom:14px">
                <div style="font-size:12px;font-weight:700;color:#25D366;margin-bottom:5px">ðŸ’¡ Sending KES 500+?</div>
                <div style="font-size:12px;color:rgba(255,255,255,.78);margin-bottom:8px">For larger amounts you can send a Gift Voucher (shareable code) or a Direct Gift (instant delivery). Recipient gets the full amount â€” same fee of KES 75.</div>
                <div style="display:flex;gap:8px;flex-wrap:wrap">
                    <a id="nudgeVoucher" href="#" style="font-size:12px;font-weight:700;color:#a78bfa;background:rgba(0,166,81,.15);border:1px solid rgba(0,166,81,.3);border-radius:7px;padding:5px 12px;text-decoration:none">ðŸŽ Gift Voucher â†’</a>
                    <a id="nudgeDirect" href="#" style="font-size:12px;font-weight:700;color:#25D366;background:rgba(37,211,102,.12);border:1px solid rgba(37,211,102,.3);border-radius:7px;padding:5px 12px;text-decoration:none">âš¡ Direct Gift â†’</a>
                </div>
            </div>

            <div class="form-group">
                <label>Your M-Pesa Number</label>
                <input type="tel" id="phone" placeholder="07XX XXX XXX">
            </div>

            <button class="btn" id="tipBtn" onclick="sendTip()">Send Tip â†’</button>

            <div id="pendingStatus" style="display:none;text-align:center;margin-top:16px;font-size:13px;color:rgba(255,255,255,.78)">
                <span class="status-dot"></span>Waiting for M-Pesa confirmation...
            </div>
        </div>

        <!-- Feedback step -->
        <div class="feedback-step" id="feedbackStep">
            <div style="font-size:40px;margin-bottom:10px">ðŸŽ‰</div>
            <div style="font-size:18px;font-weight:800;margin-bottom:6px">Tip sent to {{ $staff->name }}!</div>
            <div style="font-size:13px;color:rgba(255,255,255,.72);margin-bottom:20px">How was your experience?</div>

            <div class="star-row" id="starRow">
                <span onclick="setRating(1)">â­</span>
                <span onclick="setRating(2)">â­</span>
                <span onclick="setRating(3)">â­</span>
                <span onclick="setRating(4)">â­</span>
                <span onclick="setRating(5)">â­</span>
            </div>

            <div class="tag-grid" id="tagGrid"></div>

            <textarea id="comment" placeholder="Leave a comment (optional)..."></textarea>

            <button class="btn" id="feedbackBtn" onclick="submitFeedback()" style="margin-top:12px" disabled>Submit Feedback</button>
            <button onclick="skipFeedback()" style="background:none;border:none;color:rgba(255,255,255,.82);cursor:pointer;font-size:13px;margin-top:12px;display:block;width:100%">Skip</button>
        </div>

        <!-- Final success -->
        <div class="success-box" id="successBox">
            <div style="font-size:40px;margin-bottom:10px">âœ…</div>
            <div style="font-size:16px;font-weight:800;margin-bottom:6px">Thank you!</div>
            <div style="font-size:13px;color:rgba(255,255,255,.82)">Your feedback has been recorded. {{ $staff->name }} appreciates it.</div>
            <a href="{{ route('home') }}" style="display:inline-block;margin-top:16px;color:#a78bfa;font-size:13px">Send a gift instead â†’</a>
        </div>

    </div>
</div>

<footer class="footer">Â© 2026 Pregota Â· Tips are private Â· pregota.com</footer>

<script>
const CSRF     = document.querySelector('meta[name=csrf-token]').content;
const HANDLE   = '{{ $staff->handle }}';
const CATEGORY = '{{ $staff->business->category }}';
const fmt      = n => 'KES ' + Number(n).toLocaleString('en-KE', {minimumFractionDigits:0});

const FLAT_FEE   = {{ $flatFee }};
const FEE_WAIVED = {{ $feeWaived ? 'true' : 'false' }};

let selectedRating = 0;
let selectedTags   = [];
let currentTipId   = null;

// Load tags
fetch('/tip/tags?category=' + CATEGORY)
    .then(r => r.json())
    .then(tags => {
        document.getElementById('tagGrid').innerHTML = tags.map(t =>
            `<button class="tag-btn" onclick="toggleTag(this,'${t.tag}')">${t.emoji} ${t.tag}</button>`
        ).join('');
    });

function selectPreset(btn, amount) {
    document.querySelectorAll('.preset-btn').forEach(b => b.classList.remove('selected'));
    btn.classList.add('selected');
    document.getElementById('amount').value = amount;
    updateFee();
}

function clearPresets() {
    document.querySelectorAll('.preset-btn').forEach(b => b.classList.remove('selected'));
}

const NUDGE_THRESHOLD = {{ config('pregota.gift_nudge_threshold', 500) }};

function updateFee() {
    const v = parseFloat(document.getElementById('amount').value);
    const preview = document.getElementById('feePreview');
    const nudge   = document.getElementById('giftNudge');
    if (!v || v < {{ config('pregota.min_amount') }}) {
        preview.style.display = 'none';
        nudge.style.display   = 'none';
        return;
    }
    const gross = Math.ceil(v + FLAT_FEE);
    document.getElementById('fRecipient').textContent = fmt(v);
    document.getElementById('fGross').textContent     = fmt(gross);
    preview.style.display = FEE_WAIVED ? 'none' : 'block';

    if (v >= NUDGE_THRESHOLD) {
        nudge.style.display = 'block';
        document.getElementById('nudgeVoucher').href = '/?amount=' + v + '&mode=voucher';
        document.getElementById('nudgeDirect').href  = '/?amount=' + v + '&mode=direct';
    } else {
        nudge.style.display = 'none';
    }
}

async function sendTip() {
    const amount = document.getElementById('amount').value;
    const phone  = document.getElementById('phone').value.trim();
    const btn    = document.getElementById('tipBtn');
    if (!amount || !phone) return;
    btn.disabled = true; btn.textContent = 'Sending...';

    try {
        const res  = await fetch('/t/' + HANDLE + '/tip', {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
            body: JSON.stringify({amount, phone}),
        });
        const json = await res.json();
        if (json.success) {
            currentTipId = json.tip_id;
            document.getElementById('pendingStatus').style.display = 'block';
            btn.textContent = 'Waiting for PIN...';
            pollTipStatus(json.tip_id);
        } else {
            alert(json.message || 'Something went wrong.');
            btn.disabled = false; btn.textContent = 'Send Tip â†’';
        }
    } catch(e) {
        alert('Network error. Please try again.');
        btn.disabled = false; btn.textContent = 'Send Tip â†’';
    }
}

async function pollTipStatus(tipId) {
    for (let i = 0; i < 20; i++) {
        await new Promise(r => setTimeout(r, 3000));
        const res  = await fetch('/tip/status?tip_id=' + tipId);
        const json = await res.json();
        if (json.status === 'paid' || json.status === 'active') {
            showFeedbackStep();
            return;
        }
        if (json.status === 'failed') {
            document.getElementById('pendingStatus').innerHTML = 'âŒ Payment failed. Please try again.';
            document.getElementById('tipBtn').disabled = false;
            document.getElementById('tipBtn').textContent = 'Send Tip â†’';
            return;
        }
    }
}

function showFeedbackStep() {
    document.getElementById('tipForm').style.display = 'none';
    document.getElementById('feedbackStep').style.display = 'block';
}

function setRating(r) {
    selectedRating = r;
    document.querySelectorAll('#starRow span').forEach((s, i) => {
        s.classList.toggle('lit', i < r);
    });
    document.getElementById('feedbackBtn').disabled = false;
}

function toggleTag(btn, tag) {
    btn.classList.toggle('selected');
    if (selectedTags.includes(tag)) {
        selectedTags = selectedTags.filter(t => t !== tag);
    } else {
        selectedTags.push(tag);
    }
}

async function submitFeedback() {
    const btn = document.getElementById('feedbackBtn');
    btn.disabled = true; btn.textContent = 'Submitting...';

    try {
        await fetch('/tip/feedback', {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
            body: JSON.stringify({
                tip_id:  currentTipId,
                rating:  selectedRating,
                tags:    selectedTags,
                comment: document.getElementById('comment').value.trim() || null,
            }),
        });
    } catch(e) {}
    showSuccess();
}

function skipFeedback() { showSuccess(); }

function showSuccess() {
    document.getElementById('feedbackStep').style.display = 'none';
    document.getElementById('successBox').style.display = 'block';
}
</script>
</body>
</html>

