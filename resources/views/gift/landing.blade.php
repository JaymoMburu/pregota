<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Send a Gift via M-Pesa — Pregota</title>
<meta name="description" content="Send money anonymously via M-Pesa. They receive a gift code. You stay invisible. No M-Pesa number sharing required.">
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh}

.nav{padding:14px 24px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.08);position:sticky;top:0;background:#0B141A;z-index:10}
.logo{font-size:20px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.nav-cta{background:linear-gradient(135deg,#00A651,#007A33);color:#fff;border:none;border-radius:8px;padding:8px 18px;font-size:13px;font-weight:700;cursor:pointer;text-decoration:none}

.hero{padding:64px 24px 48px;text-align:center;max-width:620px;margin:0 auto}
.badge{display:inline-flex;align-items:center;gap:7px;background:rgba(0,166,81,.1);border:1px solid rgba(0,166,81,.25);border-radius:20px;padding:6px 16px;font-size:12px;font-weight:700;color:#25D366;margin-bottom:24px;letter-spacing:.05em}
.hero h1{font-size:clamp(30px,6vw,50px);font-weight:900;line-height:1.1;letter-spacing:-.5px;margin-bottom:18px}
.hero h1 em{font-style:normal;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.hero p{font-size:16px;color:rgba(255,255,255,.82);line-height:1.7;margin-bottom:32px;max-width:440px;margin-left:auto;margin-right:auto}
.hero-btns{display:flex;gap:12px;justify-content:center;flex-wrap:wrap}
.btn-primary{background:linear-gradient(135deg,#00A651,#007A33);color:#fff;border:none;border-radius:12px;padding:14px 28px;font-size:15px;font-weight:700;cursor:pointer;text-decoration:none;display:inline-block}
.btn-secondary{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.15);color:rgba(255,255,255,.7);border-radius:12px;padding:14px 28px;font-size:15px;font-weight:700;text-decoration:none;display:inline-block}

/* Gift type cards */
.types{padding:48px 24px;max-width:700px;margin:0 auto}
.types-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-top:28px}
@media(max-width:520px){.types-grid{grid-template-columns:1fr}}
.type-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:16px;padding:22px;text-decoration:none;color:#fff;display:block;transition:.2s}
.type-card:hover{border-color:rgba(0,166,81,.4);background:rgba(0,166,81,.06)}
.type-icon{font-size:32px;margin-bottom:12px}
.type-title{font-size:15px;font-weight:700;margin-bottom:6px;color:#fff}
.type-desc{font-size:13px;color:rgba(255,255,255,.72);line-height:1.6}
.type-tag{display:inline-block;margin-top:10px;font-size:11px;font-weight:700;color:#25D366}

/* Problem section */
.problem{padding:56px 24px;max-width:700px;margin:0 auto}
.section-tag{display:inline-block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:rgba(255,255,255,.6);margin-bottom:12px}
.section h2{font-size:clamp(22px,4vw,32px);font-weight:900;line-height:1.2;margin-bottom:14px}
.section p{font-size:15px;color:rgba(255,255,255,.78);line-height:1.7}
.prob-card{background:rgba(239,68,68,.06);border:1px solid rgba(239,68,68,.15);border-radius:16px;padding:24px;margin-top:24px}
.prob-item{display:flex;align-items:flex-start;gap:14px;padding:11px 0;border-bottom:1px solid rgba(239,68,68,.1)}
.prob-item:last-child{border-bottom:none}
.prob-icon{font-size:20px;flex-shrink:0;margin-top:1px}
.prob-text strong{font-size:13px;color:#fca5a5;display:block;margin-bottom:3px}
.prob-text span{font-size:12px;color:rgba(255,255,255,.68);line-height:1.6}

/* How it works */
.how{background:rgba(0,166,81,.04);border-top:1px solid rgba(0,166,81,.1);border-bottom:1px solid rgba(0,166,81,.1);padding:56px 24px}
.how-inner{max-width:700px;margin:0 auto}
.steps{display:flex;flex-direction:column;gap:0;margin-top:28px}
.step{display:flex;gap:20px;padding:20px 0;border-bottom:1px solid rgba(255,255,255,.05)}
.step:last-child{border-bottom:none}
.step-num{width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#00A651,#007A33);display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:900;flex-shrink:0;margin-top:2px}
.step-body h3{font-size:15px;font-weight:700;margin-bottom:4px}
.step-body p{font-size:13px;color:rgba(255,255,255,.72);line-height:1.6}

/* Use cases */
.uses{padding:56px 24px;max-width:700px;margin:0 auto}
.uses-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-top:24px}
@media(max-width:480px){.uses-grid{grid-template-columns:1fr 1fr}}
.use-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:12px;padding:16px;text-align:center}
.use-emoji{font-size:28px;margin-bottom:6px}
.use-name{font-size:13px;font-weight:700;color:rgba(255,255,255,.8)}
.use-sub{font-size:11px;color:rgba(255,255,255,.6);margin-top:2px}

/* Fee box */
.fee-section{background:rgba(0,166,81,.04);border-top:1px solid rgba(0,166,81,.1);border-bottom:1px solid rgba(0,166,81,.1);padding:56px 24px}
.fee-inner{max-width:700px;margin:0 auto}
.fee-box{background:rgba(0,166,81,.08);border:1px solid rgba(0,166,81,.2);border-radius:14px;padding:20px;margin-top:24px}
.fee-row{display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid rgba(255,255,255,.06);font-size:13px}
.fee-row:last-child{border-bottom:none;font-weight:700}
.fee-label{color:rgba(255,255,255,.82)}
.fee-value{font-weight:600}

/* Advantages */
.adv-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-top:28px}
@media(max-width:520px){.adv-grid{grid-template-columns:1fr}}
.adv-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:20px}
.adv-icon{font-size:28px;margin-bottom:10px}
.adv-title{font-size:14px;font-weight:700;color:#25D366;margin-bottom:4px}
.adv-text{font-size:13px;color:rgba(255,255,255,.72);line-height:1.6}

/* CTA */
.cta-bottom{background:linear-gradient(135deg,rgba(0,166,81,.15),rgba(0,122,51,.08));border-top:1px solid rgba(0,166,81,.2);padding:64px 24px;text-align:center}
.cta-bottom h2{font-size:clamp(24px,4vw,36px);font-weight:900;margin-bottom:12px}
.cta-bottom p{font-size:15px;color:rgba(255,255,255,.78);margin-bottom:32px;line-height:1.6}
.footer{padding:20px 24px;text-align:center;color:rgba(255,255,255,.2);font-size:11px;border-top:1px solid rgba(255,255,255,.06)}
</style>
</head>
<body>

<nav class="nav">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    <a href="{{ route('gift.home') }}" class="nav-cta">Send a Gift →</a>
</nav>

<!-- Hero -->
<div class="hero">
    <div class="badge">🎁 Gift Vouchers · Powered by M-Pesa</div>
    <h1>Send money as a gift.<br><em>Anonymously.</em></h1>
    <p>They receive the money. You stay invisible. No need to share anyone's M-Pesa number — just send a gift code via WhatsApp or SMS.</p>
    <div class="hero-btns">
        <a href="{{ route('gift.home') }}" class="btn-primary">Send a Gift Now</a>
        <a href="#how-it-works" class="btn-secondary">How It Works</a>
    </div>
</div>

<!-- Gift types -->
<div class="types">
    <div class="section-tag">Two Ways to Gift</div>
    <h2 style="font-size:clamp(22px,4vw,32px);font-weight:900;line-height:1.2;margin-bottom:6px">Pick the type that fits.</h2>
    <p style="font-size:15px;color:rgba(255,255,255,.78);margin-top:8px">Whether you want a surprise or instant delivery, we have you covered.</p>

    <div class="types-grid">
        <a href="{{ route('gift.home') }}" class="type-card">
            <div class="type-icon">🎁</div>
            <div class="type-title">Gift Voucher</div>
            <div class="type-desc">They receive a unique code like PRG-7492-X8Q1. You share it by WhatsApp or SMS. They redeem it at any time using their own M-Pesa — you never need their number.</div>
            <div class="type-tag">Best for surprise gifts →</div>
        </a>
        <a href="{{ route('gift.home') }}" class="type-card">
            <div class="type-icon">⚡</div>
            <div class="type-title">Direct Gift</div>
            <div class="type-desc">Money lands straight on the recipient's M-Pesa the moment you confirm. Fast, no code, no steps for them. You do need their number for this one.</div>
            <div class="type-tag">Best for instant transfers →</div>
        </a>
    </div>
</div>

<!-- The problem -->
<div class="problem section">
    <div class="section-tag">Why Not Just M-Pesa?</div>
    <h2>Because sending to someone's personal number has real problems.</h2>

    <div class="prob-card">
        <div class="prob-item">
            <div class="prob-icon">🔓</div>
            <div class="prob-text">
                <strong>You expose their phone number</strong>
                <span>Once you send via standard M-Pesa, your number appears on their statement. If they forward the money or you screenshot — that number spreads. Pregota keeps both sides private.</span>
            </div>
        </div>
        <div class="prob-item">
            <div class="prob-icon">😬</div>
            <div class="prob-text">
                <strong>Awkward to ask for their number</strong>
                <span>You want to gift someone in a group — a colleague, a teacher, a stranger online. Asking for their personal M-Pesa number feels intrusive. A gift link solves that.</span>
            </div>
        </div>
        <div class="prob-item">
            <div class="prob-icon">🙈</div>
            <div class="prob-text">
                <strong>No anonymity — your name appears</strong>
                <span>Standard M-Pesa shows your registered name on every transaction. If you want to give quietly, there's no way to hide. Pregota shows nothing — not even your phone number.</span>
            </div>
        </div>
    </div>
</div>

<!-- How it works -->
<div class="how" id="how-it-works">
    <div class="how-inner">
        <div class="section-tag">How It Works</div>
        <h2>Four steps. The whole thing takes under two minutes.</h2>
        <p style="font-size:15px;color:rgba(255,255,255,.78);margin-top:10px">Gift Voucher flow — they redeem at their own time, using their own M-Pesa.</p>

        <div class="steps">
            <div class="step">
                <div class="step-num">1</div>
                <div class="step-body">
                    <h3>Enter the gift amount</h3>
                    <p>Type what you want them to receive. The fee is added on top — they get the full amount you enter. Minimum KES {{ number_format(config('pregota.min_amount'), 0) }}, maximum KES {{ number_format(config('pregota.max_amount'), 0) }}.</p>
                </div>
            </div>
            <div class="step">
                <div class="step-num">2</div>
                <div class="step-body">
                    <h3>Pay via M-Pesa STK Push</h3>
                    <p>Enter your own phone number. An M-Pesa prompt pops up on your screen — confirm with your PIN. Your statement shows "Pregota Ltd", not the recipient's name or number.</p>
                </div>
            </div>
            <div class="step">
                <div class="step-num">3</div>
                <div class="step-body">
                    <h3>Get a unique gift code</h3>
                    <p>Once your payment is confirmed, you receive a code like <strong style="color:#25D366">PRG-7492-X8Q1</strong>. Send it by WhatsApp, SMS, DM — any way you like. Add a message if you want.</p>
                </div>
            </div>
            <div class="step">
                <div class="step-num">4</div>
                <div class="step-body">
                    <h3>They redeem it — money arrives instantly</h3>
                    <p>They visit pregota.com, enter the code and their own M-Pesa number. Cash lands immediately. No account needed. Valid for 72 hours.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Use cases -->
<div class="uses">
    <div class="section-tag">When to Use It</div>
    <h2>Any occasion where cash is the right gift.</h2>
    <div class="uses-grid">
        <div class="use-card"><div class="use-emoji">🎂</div><div class="use-name">Birthday</div><div class="use-sub">Anonymous surprise</div></div>
        <div class="use-card"><div class="use-emoji">🎓</div><div class="use-name">Graduation</div><div class="use-sub">Congratulations gift</div></div>
        <div class="use-card"><div class="use-emoji">🙏</div><div class="use-name">Thank You</div><div class="use-sub">Show appreciation</div></div>
        <div class="use-card"><div class="use-emoji">🍼</div><div class="use-name">Baby Shower</div><div class="use-sub">New arrival gift</div></div>
        <div class="use-card"><div class="use-emoji">💼</div><div class="use-name">Farewell</div><div class="use-sub">Office whip-round</div></div>
        <div class="use-card"><div class="use-emoji">❤️</div><div class="use-name">Support</div><div class="use-sub">Anonymous help</div></div>
        <div class="use-card"><div class="use-emoji">🎤</div><div class="use-name">Creator Gift</div><div class="use-sub">Support your fave</div></div>
        <div class="use-card"><div class="use-emoji">🏫</div><div class="use-name">Teacher Gift</div><div class="use-sub">End of term</div></div>
        <div class="use-card"><div class="use-emoji">🎉</div><div class="use-name">Any Occasion</div><div class="use-sub">Cash is always right</div></div>
    </div>
</div>

<!-- Fee section -->
<div class="fee-section">
    <div class="fee-inner">
        <div class="section-tag">Pricing</div>
        <h2 style="font-size:clamp(22px,4vw,32px);font-weight:900;line-height:1.2;margin-bottom:6px">Transparent fees. No surprises.</h2>
        <p style="font-size:15px;color:rgba(255,255,255,.78);margin-top:8px">We show you exactly what you'll pay before you confirm. The recipient always gets the full amount you entered.</p>

        <div class="fee-box">
            <div class="fee-row">
                <span class="fee-label">You want them to receive</span>
                <span class="fee-value" style="color:#25D366">KES 2,000</span>
            </div>
            <div class="fee-row">
                <span class="fee-label">Payout fee ({{ config('pregota.fee_out_pct') }}%)</span>
                <span class="fee-value" style="color:rgba(255,255,255,.78)">+ KES {{ number_format(2000 * config('pregota.fee_out_pct') / (100 - config('pregota.fee_out_pct')), 0) }}</span>
            </div>
            <div class="fee-row">
                <span class="fee-label">Deposit fee ({{ config('pregota.fee_in_pct') }}%)</span>
                <span class="fee-value" style="color:rgba(255,255,255,.78)">+ KES {{ number_format((2000 + 2000 * config('pregota.fee_out_pct') / (100 - config('pregota.fee_out_pct'))) * config('pregota.fee_in_pct') / (100 - config('pregota.fee_in_pct')), 0) }}</span>
            </div>
            <div class="fee-row">
                <span class="fee-label">You pay via M-Pesa</span>
                @php
                    $feeOut = 2000 * config('pregota.fee_out_pct') / (100 - config('pregota.fee_out_pct'));
                    $faceVal = 2000 + $feeOut;
                    $feeIn = $faceVal * config('pregota.fee_in_pct') / (100 - config('pregota.fee_in_pct'));
                    $gross = ceil($faceVal + $feeIn);
                @endphp
                <span class="fee-value">KES {{ number_format($gross, 0) }}</span>
            </div>
            <div class="fee-row" style="margin-top:4px;padding-top:12px;border-top:1px solid rgba(255,255,255,.08)">
                <span class="fee-label">Recipient receives</span>
                <span class="fee-value" style="color:#25D366;font-size:15px">KES 2,000 — exactly what you chose</span>
            </div>
        </div>

        <div class="adv-grid" style="margin-top:32px">
            <div class="adv-card">
                <div class="adv-icon">🔒</div>
                <div class="adv-title">Complete anonymity</div>
                <div class="adv-text">Your name, your number — nothing is passed to the recipient. Their number is never stored or shown to you.</div>
            </div>
            <div class="adv-card">
                <div class="adv-icon">⏱️</div>
                <div class="adv-title">5-minute recall window</div>
                <div class="adv-text">Sent to the wrong person? Use your recall token within 5 minutes to cancel — you get back the face value.</div>
            </div>
            <div class="adv-card">
                <div class="adv-icon">📱</div>
                <div class="adv-title">No app needed</div>
                <div class="adv-text">Works on any phone with M-Pesa. No download, no sign-up, no account for the sender or recipient.</div>
            </div>
            <div class="adv-card">
                <div class="adv-icon">🎤</div>
                <div class="adv-title">Gift your favourite creator</div>
                <div class="adv-text">Find any registered creator on Pregota and send directly to their profile — they receive the money automatically.</div>
            </div>
        </div>
    </div>
</div>

<!-- CTA -->
<div class="cta-bottom">
    <h2>Ready to send?<br><em style="font-style:normal;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent">Takes under two minutes.</em></h2>
    <p>No account. No app. Just M-Pesa and a gift code. The most private way to give cash in Kenya.</p>
    <a href="{{ route('gift.home') }}" class="btn-primary" style="font-size:16px;padding:16px 36px">Send a Gift →</a>
    <p style="margin-top:16px;font-size:12px;color:rgba(255,255,255,.82)">No account needed · Minimum KES {{ number_format(config('pregota.min_amount'), 0) }} · Valid 72 hours</p>
</div>

@include('partials.discover', ['current' => 'gift', 'fullWidth' => true])
<footer class="footer">© 2026 Pregota · Anonymous Gift Transfers via M-Pesa · pregota.com</footer>

</body>
</html>
