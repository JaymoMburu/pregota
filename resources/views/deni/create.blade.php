<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Record a Deni — Pregota</title>
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh}

.nav{padding:14px 24px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.08);position:sticky;top:0;background:#0B141A;z-index:10}
.logo{font-size:20px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.nav-back{font-size:13px;color:rgba(255,255,255,.45);text-decoration:none;display:flex;align-items:center;gap:5px}
.nav-back:hover{color:rgba(255,255,255,.7)}

.wrap{max-width:520px;margin:0 auto;padding:40px 20px 80px}

/* Header */
.page-badge{display:inline-flex;align-items:center;gap:7px;background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);border-radius:20px;padding:5px 14px;font-size:11px;font-weight:700;color:#f87171;margin-bottom:16px;letter-spacing:.05em}
.page-title{font-size:26px;font-weight:900;margin-bottom:8px}
.page-sub{font-size:14px;color:rgba(255,255,255,.55);line-height:1.65;margin-bottom:32px}

/* How it works strip */
.how-strip{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:14px;padding:18px 20px;margin-bottom:28px}
.how-strip-title{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.38);margin-bottom:14px}
.how-steps{display:flex;flex-direction:column;gap:10px}
.how-step{display:flex;gap:12px;align-items:flex-start;font-size:13px;color:rgba(255,255,255,.6);line-height:1.5}
.how-num{width:22px;height:22px;border-radius:50%;background:rgba(239,68,68,.15);border:1px solid rgba(239,68,68,.25);color:#f87171;font-size:11px;font-weight:900;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:1px}

/* Form */
.form-section{margin-bottom:28px}
.form-section-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(239,68,68,.6);margin-bottom:14px;display:flex;align-items:center;gap:8px}
.form-section-label::after{content:'';flex:1;height:1px;background:rgba(239,68,68,.12)}

.field{margin-bottom:16px}
.field label{display:block;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.4);margin-bottom:7px}
.field input{width:100%;padding:13px 14px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:11px;color:#fff;font-size:15px;outline:none;font-family:inherit;transition:.15s}
.field input:focus{border-color:rgba(239,68,68,.4);background:rgba(239,68,68,.04)}
.field .hint{font-size:12px;color:rgba(255,255,255,.3);margin-top:5px;line-height:1.5}
.field-row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
@media(max-width:420px){.field-row{grid-template-columns:1fr}}

.optional-badge{font-size:10px;font-weight:600;color:rgba(255,255,255,.3);background:rgba(255,255,255,.05);border-radius:4px;padding:1px 6px;margin-left:6px;vertical-align:middle;letter-spacing:.03em}

.submit-btn{width:100%;padding:15px;background:linear-gradient(135deg,#dc2626,#ef4444);color:#fff;font-size:16px;font-weight:900;border:none;border-radius:13px;cursor:pointer;margin-top:8px;transition:.2s}
.submit-btn:hover{transform:translateY(-1px);box-shadow:0 8px 24px rgba(239,68,68,.3)}

.privacy-note{text-align:center;font-size:12px;color:rgba(255,255,255,.3);margin-top:14px;line-height:1.6}
.privacy-note span{color:rgba(239,68,68,.5)}

@if($errors->any())
.error-box{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);border-radius:10px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#fca5a5}
@endif
</style>
</head>
<body>
<nav class="nav">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    @if(session()->has('seller_id'))
        <a href="{{ route('seller.dashboard') }}" class="nav-back">← Dashboard</a>
    @else
        <a href="{{ route('deni.landing') }}" class="nav-back">← How Deni Works</a>
    @endif
</nav>

<div class="wrap">
    <div class="page-badge">🧾 Deni — Credit Tracking</div>
    <div class="page-title">Record a Deni</div>
    <div class="page-sub">Gave credit at your shop or lent a friend money? Record it here — they get a payment link, pay via M-Pesa, and money goes straight to you. No account needed.</div>

    <div class="how-strip">
        <div class="how-strip-title">How it works</div>
        <div class="how-steps">
            <div class="how-step"><div class="how-num">1</div><div>Fill in the details below — what's owed and how much.</div></div>
            <div class="how-step"><div class="how-num">2</div><div>You get an <strong style="color:rgba(255,255,255,.8)">admin link</strong> (bookmark it) and a <strong style="color:rgba(255,255,255,.8)">customer payment link</strong>.</div></div>
            <div class="how-step"><div class="how-num">3</div><div>Send the payment link to your customer via WhatsApp — they pay via M-Pesa.</div></div>
            <div class="how-step"><div class="how-num">4</div><div>Money lands in your M-Pesa instantly. Open your admin link anytime to check payments.</div></div>
        </div>
    </div>

    @if($errors->any())
    <div class="error-box">
        @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
    </div>
    @endif

    <form method="POST" action="{{ route('deni.store') }}">
        @csrf

        {{-- Section: About you --}}
        <div class="form-section">
            <div class="form-section-label">About you</div>

            @if(!session()->has('seller_id'))
            <div class="field">
                <label>Your Name or Business</label>
                <input type="text" name="creditor_name" placeholder="e.g. Mama Njeri Kibanda or James Mburu" maxlength="100" required value="{{ old('creditor_name') }}">
            </div>
            <div class="field">
                <label>Your M-Pesa Number</label>
                <input type="tel" name="lender_phone" placeholder="0712 345 678" required value="{{ old('lender_phone') }}">
                <div class="hint">💰 This is where M-Pesa will send the money the moment they pay.</div>
            </div>
            @endif
        </div>

        {{-- Section: The deni --}}
        <div class="form-section">
            <div class="form-section-label">The deni</div>

            <div class="field">
                <label>What is the deni for?</label>
                <input type="text" name="description" placeholder="e.g. Lunch — rice, beef stew & chai · Monday" maxlength="300" required value="{{ old('description') }}">
                <div class="hint">Be specific — this appears on the customer's payment page.</div>
            </div>

            <div class="field">
                <label>Amount Owed (KES)</label>
                <input type="number" name="original_amount" placeholder="120" min="1" max="500000" required value="{{ old('original_amount') }}">
            </div>
        </div>

        {{-- Section: Customer details (optional) --}}
        <div class="form-section">
            <div class="form-section-label">Customer details <span class="optional-badge">optional</span></div>

            <div class="field-row">
                <div class="field">
                    <label>Their Phone</label>
                    <input type="tel" name="debtor_phone" placeholder="0712 345 678" value="{{ old('debtor_phone') }}">
                    <div class="hint">If entered, the deni appears on their Pregota dashboard automatically.</div>
                </div>
                <div class="field">
                    <label>Due Date</label>
                    <input type="date" name="due_date" value="{{ old('due_date') }}">
                    <div class="hint">Shown on the customer's payment page.</div>
                </div>
            </div>
        </div>

        <button type="submit" class="submit-btn">Create Deni & Get Links →</button>
        <div class="privacy-note">🔒 Your M-Pesa number is encrypted and never shown to the customer.<br>Money goes straight to your M-Pesa via <span>M-Pesa B2C</span> the moment they pay.</div>
    </form>
</div>
</body>
</html>
