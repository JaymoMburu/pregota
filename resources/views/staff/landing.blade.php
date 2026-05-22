<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>For Staff — Pregota</title>
<meta name="description" content="Receive tips without sharing your M-Pesa number. Your contact stays private — always.">
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0f0f1a;color:#fff;min-height:100vh}

/* Nav */
.nav{padding:14px 24px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.08);position:sticky;top:0;background:#0f0f1a;z-index:10}
.logo{font-size:20px;font-weight:900;background:linear-gradient(135deg,#7c3aed,#db2777);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.nav-cta{background:linear-gradient(135deg,#7c3aed,#db2777);color:#fff;border:none;border-radius:8px;padding:8px 18px;font-size:13px;font-weight:700;cursor:pointer;text-decoration:none}

/* Hero */
.hero{padding:64px 24px 48px;text-align:center;max-width:580px;margin:0 auto;position:relative}
.shield-badge{display:inline-flex;align-items:center;gap:7px;background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.25);border-radius:20px;padding:6px 16px;font-size:12px;font-weight:700;color:#4ade80;margin-bottom:24px;letter-spacing:.05em}
.hero h1{font-size:clamp(32px,6vw,52px);font-weight:900;line-height:1.1;letter-spacing:-.5px;margin-bottom:18px}
.hero h1 em{font-style:normal;background:linear-gradient(135deg,#c084fc,#f472b6);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.hero p{font-size:16px;color:rgba(255,255,255,.55);line-height:1.7;margin-bottom:32px;max-width:420px;margin-left:auto;margin-right:auto}
.hero-btns{display:flex;gap:12px;justify-content:center;flex-wrap:wrap}
.btn-primary{background:linear-gradient(135deg,#7c3aed,#db2777);color:#fff;border:none;border-radius:12px;padding:14px 28px;font-size:15px;font-weight:700;cursor:pointer;text-decoration:none;display:inline-block}
.btn-secondary{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.15);color:rgba(255,255,255,.7);border-radius:12px;padding:14px 28px;font-size:15px;font-weight:700;text-decoration:none;display:inline-block}

/* Risk section */
.section{padding:56px 24px;max-width:680px;margin:0 auto}
.section-tag{display:inline-block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:rgba(255,255,255,.35);margin-bottom:12px}
.section h2{font-size:clamp(22px,4vw,32px);font-weight:900;line-height:1.2;margin-bottom:14px}
.section p{font-size:15px;color:rgba(255,255,255,.5);line-height:1.7}

.risk-card{background:rgba(239,68,68,.06);border:1px solid rgba(239,68,68,.2);border-radius:16px;padding:24px;margin-top:24px}
.risk-title{font-size:14px;font-weight:700;color:#fca5a5;margin-bottom:14px}
.risk-item{display:flex;align-items:flex-start;gap:12px;padding:10px 0;border-bottom:1px solid rgba(239,68,68,.1)}
.risk-item:last-child{border-bottom:none}
.risk-icon{font-size:18px;margin-top:1px;flex-shrink:0}
.risk-text strong{font-size:13px;color:#fca5a5;display:block;margin-bottom:2px}
.risk-text span{font-size:12px;color:rgba(255,255,255,.4);line-height:1.55}

/* How it works */
.how{background:rgba(124,58,237,.05);border-top:1px solid rgba(124,58,237,.1);border-bottom:1px solid rgba(124,58,237,.1);padding:56px 24px}
.how-inner{max-width:680px;margin:0 auto}
.steps{display:flex;flex-direction:column;gap:0;margin-top:28px}
.step{display:flex;gap:20px;padding:20px 0;border-bottom:1px solid rgba(255,255,255,.05)}
.step:last-child{border-bottom:none}
.step-num{width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#7c3aed,#db2777);display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:900;flex-shrink:0;margin-top:2px}
.step-body h3{font-size:15px;font-weight:700;margin-bottom:4px}
.step-body p{font-size:13px;color:rgba(255,255,255,.45);line-height:1.6}

/* Bill Split feature */
.split-feature{background:rgba(74,222,128,.04);border-top:1px solid rgba(74,222,128,.1);border-bottom:1px solid rgba(74,222,128,.1);padding:56px 24px}
.split-feature-inner{max-width:680px;margin:0 auto}
.split-tag{display:inline-block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:#4ade80;margin-bottom:12px;opacity:.7}
.split-feature h2{font-size:clamp(22px,4vw,32px);font-weight:900;line-height:1.2;margin-bottom:14px}
.split-steps{display:flex;flex-direction:column;gap:0;margin-top:28px}
.split-step{display:flex;gap:20px;padding:20px 0;border-bottom:1px solid rgba(74,222,128,.08)}
.split-step:last-child{border-bottom:none}
.split-num{width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#059669,#4ade80);display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:900;flex-shrink:0;margin-top:2px;color:#0f0f1a}
.split-body h3{font-size:15px;font-weight:700;margin-bottom:4px}
.split-body p{font-size:13px;color:rgba(255,255,255,.45);line-height:1.6}
.split-bonus{margin-top:28px;background:rgba(124,58,237,.08);border:1px solid rgba(124,58,237,.2);border-radius:14px;padding:20px;display:flex;gap:14px;align-items:flex-start}
.split-bonus-icon{font-size:28px;flex-shrink:0}
.split-bonus h4{font-size:14px;font-weight:700;color:#c084fc;margin-bottom:4px}
.split-bonus p{font-size:13px;color:rgba(255,255,255,.45);line-height:1.6}
.split-cta{margin-top:28px;display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,#059669,#10b981);color:#fff;border:none;border-radius:12px;padding:14px 28px;font-size:15px;font-weight:700;text-decoration:none}

/* Customer feedback */
.feedback{padding:56px 24px;max-width:680px;margin:0 auto}
.feedback-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-top:28px}
@media(max-width:520px){.feedback-grid{grid-template-columns:1fr}}
.fb-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:18px}
.fb-stars{font-size:14px;letter-spacing:1px;margin-bottom:8px}
.fb-text{font-size:13px;color:rgba(255,255,255,.65);line-height:1.6;margin-bottom:12px;min-height:40px}
.fb-tags{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:10px}
.fb-tag{background:rgba(124,58,237,.15);border:1px solid rgba(124,58,237,.25);border-radius:20px;padding:3px 10px;font-size:11px;color:#c084fc;font-weight:600}
.fb-footer{font-size:11px;color:rgba(255,255,255,.25);display:flex;justify-content:space-between}
.fb-amount{color:#4ade80;font-weight:700;font-size:12px}

/* Privacy guarantee */
.guarantee{padding:56px 24px;max-width:680px;margin:0 auto}
.guarantee-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-top:28px}
@media(max-width:480px){.guarantee-grid{grid-template-columns:1fr}}
.g-card{background:rgba(34,197,94,.06);border:1px solid rgba(34,197,94,.15);border-radius:14px;padding:18px}
.g-icon{font-size:24px;margin-bottom:8px}
.g-title{font-size:14px;font-weight:700;color:#4ade80;margin-bottom:4px}
.g-text{font-size:12px;color:rgba(255,255,255,.45);line-height:1.6}

/* Who it's for */
.jobs{padding:0 24px 56px;max-width:680px;margin:0 auto}
.job-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-top:24px}
@media(max-width:480px){.job-grid{grid-template-columns:1fr 1fr}}
.job-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:12px;padding:16px;text-align:center}
.job-emoji{font-size:28px;margin-bottom:6px}
.job-title{font-size:13px;font-weight:700;color:rgba(255,255,255,.8)}
.job-sub{font-size:11px;color:rgba(255,255,255,.35);margin-top:2px}

/* CTA bottom */
.cta-bottom{background:linear-gradient(135deg,rgba(124,58,237,.15),rgba(219,39,119,.1));border-top:1px solid rgba(124,58,237,.2);padding:64px 24px;text-align:center}
.cta-bottom h2{font-size:clamp(24px,4vw,36px);font-weight:900;margin-bottom:12px}
.cta-bottom p{font-size:15px;color:rgba(255,255,255,.5);margin-bottom:32px;line-height:1.6}

/* Employer strip */
.employer-strip{background:rgba(255,255,255,.03);border-top:1px solid rgba(255,255,255,.07);padding:24px;text-align:center}
.employer-strip p{font-size:13px;color:rgba(255,255,255,.35)}
.employer-strip a{color:#a78bfa;text-decoration:none;font-weight:600}

.footer{padding:20px 24px;text-align:center;color:rgba(255,255,255,.2);font-size:11px;border-top:1px solid rgba(255,255,255,.06)}
</style>
</head>
<body>

<nav class="nav">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    <a href="{{ route('staff.register') }}" class="nav-cta">Get My Tip Page →</a>
</nav>
@include('partials.module-nav', ['activeModule' => 'tips'])

<!-- Hero -->
<div class="hero">
    <div class="shield-badge">🛡️ Employee Privacy Protection</div>
    <h1>Get tipped without<br>giving out your <em>number.</em></h1>
    <p>Your personal M-Pesa number exposes more than you think. Pregota lets customers tip you directly — your contact details stay invisible. Always.</p>
    <div class="hero-btns">
        <a href="{{ route('staff.register') }}" class="btn-primary">Create My Tip Page — Free</a>
        <a href="#how-it-works" class="btn-secondary">See How It Works</a>
    </div>
</div>

<!-- The risk -->
<div class="section">
    <div class="section-tag">The Problem</div>
    <h2>When you share your M-Pesa number, you share more than you know.</h2>
    <p>Most people don't realise what their M-Pesa number reveals to a stranger. Here's what a customer gets the moment you share it for a tip:</p>

    <div class="risk-card">
        <div class="risk-title">⚠️ What a stranger can access from your M-Pesa number</div>
        <div class="risk-item">
            <div class="risk-icon">💬</div>
            <div class="risk-text">
                <strong>Your WhatsApp profile</strong>
                <span>Profile photo, status message, and "last seen" — visible to anyone who saves your number.</span>
            </div>
        </div>
        <div class="risk-item">
            <div class="risk-icon">👤</div>
            <div class="risk-text">
                <strong>Your full name</strong>
                <span>M-Pesa shows your registered name on the payment confirmation screen.</span>
            </div>
        </div>
        <div class="risk-item">
            <div class="risk-icon">📞</div>
            <div class="risk-text">
                <strong>A direct line to you — forever</strong>
                <span>A customer you served once can call or message you any time, day or night.</span>
            </div>
        </div>
        <div class="risk-item">
            <div class="risk-icon">📍</div>
            <div class="risk-text">
                <strong>The ability to track you</strong>
                <span>WhatsApp activity shows when you're online. That's enough for someone with bad intentions.</span>
            </div>
        </div>
    </div>
</div>

<!-- How it works -->
<div class="how" id="how-it-works">
    <div class="how-inner">
        <div class="section-tag">How It Works</div>
        <h2>Simple for you. Invisible to them.</h2>
        <p style="font-size:15px;color:rgba(255,255,255,.5);margin-top:10px">Your customer tips you normally via M-Pesa. They never see your number. The money arrives directly on your phone.</p>

        <div class="steps">
            <div class="step">
                <div class="step-num">1</div>
                <div class="step-body">
                    <h3>Create your free tip page</h3>
                    <p>Sign up in 2 minutes. Add your name, role, and M-Pesa number privately. You get a personal link like <strong style="color:#c084fc">pregota.com/t/grace</strong></p>
                </div>
            </div>
            <div class="step">
                <div class="step-num">2</div>
                <div class="step-body">
                    <h3>Share your QR code</h3>
                    <p>Download your QR code. Print it on a small card, stick it near your workstation, or show it on your phone. No number visible anywhere.</p>
                </div>
            </div>
            <div class="step">
                <div class="step-num">3</div>
                <div class="step-body">
                    <h3>Customer scans and tips</h3>
                    <p>They choose an amount — KES 50, 100, 200, 500 — and pay via their M-Pesa. They never see your number or name in their contacts.</p>
                </div>
            </div>
            <div class="step">
                <div class="step-num">4</div>
                <div class="step-body">
                    <h3>Money arrives on your M-Pesa</h3>
                    <p>The tip lands directly on your phone. No app needed. No middleman holding your money. Instant.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bill Split feature -->
<div class="split-feature">
    <div class="split-feature-inner">
        <div class="split-tag">Also for Waitstaff</div>
        <h2>Split the bill at your table.<br><span style="background:linear-gradient(135deg,#4ade80,#10b981);-webkit-background-clip:text;-webkit-text-fill-color:transparent">No awkward M-Pesa codes.</span></h2>
        <p style="font-size:15px;color:rgba(255,255,255,.5);margin-top:10px;line-height:1.7">Enter the total once. Show a QR. Each person at the table scans and pays their share directly — the full amount lands on your M-Pesa the moment the last person pays.</p>

        <div class="split-steps">
            <div class="split-step">
                <div class="split-num">1</div>
                <div class="split-body">
                    <h3>You enter the total bill</h3>
                    <p>Takes 10 seconds. No itemisation, no splitting by items — just the total. Add a table label so customers know what they're paying for.</p>
                </div>
            </div>
            <div class="split-step">
                <div class="split-num">2</div>
                <div class="split-body">
                    <h3>Show the QR to the table</h3>
                    <p>A large QR code appears on your screen. Set it on the table or hold it up. Everyone scans from their own phone — no app needed.</p>
                </div>
            </div>
            <div class="split-step">
                <div class="split-num">3</div>
                <div class="split-body">
                    <h3>Each person pays what they owe</h3>
                    <p>They type their share and confirm with M-Pesa STK Push — no app to open, no balance visible. Your screen shows each payment arriving in real time.</p>
                </div>
            </div>
            <div class="split-step">
                <div class="split-num">4</div>
                <div class="split-body">
                    <h3>Full amount goes straight to the till or Paybill</h3>
                    <p>The moment the bill is complete, the full total is sent directly to the restaurant's Till or Paybill number. It never touches a personal phone. Your screen shows it settled.</p>
                </div>
            </div>
        </div>

        <div class="split-bonus">
            <div class="split-bonus-icon">💜</div>
            <div>
                <h4>Tip prompt included — automatically</h4>
                <p>After paying their share, each customer is quietly offered the option to tip you. No pressure, no preset amounts — they choose. The tip goes directly to your M-Pesa.</p>
            </div>
        </div>

        <div class="split-bonus" style="margin-top:12px;background:rgba(251,191,36,.06);border-color:rgba(251,191,36,.2)">
            <div class="split-bonus-icon">📱</div>
            <div>
                <h4 style="color:#fbbf24">Your restaurant gets their contact — if they agree</h4>
                <p>After paying, Pregota asks each customer: <em style="color:rgba(255,255,255,.6)">"Would you like to receive offers from this restaurant?"</em> If they say yes, their number goes to your manager — voluntarily, transparently, no hidden collection. Safaricom now masks customer numbers on M-Pesa payments. This is the honest way to build your list.</p>
            </div>
        </div>

        <a href="{{ route('bill-split.new') }}" class="split-cta">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Try Bill Split Now — Free
        </a>
        <p style="margin-top:10px;font-size:11px;color:rgba(255,255,255,.3)">KES 30 fee added per person · Paid by each customer · No monthly charge</p>
    </div>
</div>

<!-- Customer feedback -->
<div class="feedback">
    <div class="section-tag">Customer Feedback</div>
    <h2>Tips come with feedback. So you can grow.</h2>
    <p style="font-size:15px;color:rgba(255,255,255,.5);margin-top:10px;line-height:1.7">After tipping, customers can leave a star rating, emoji reaction, and a short note. You see it all on your private dashboard — your employer only sees service quality data, never your earnings.</p>

    <div class="feedback-grid">
        <div class="fb-card">
            <div class="fb-stars">★★★★★</div>
            <div class="fb-text">"Fantastic service, very attentive and professional throughout. Will definitely be back!"</div>
            <div class="fb-tags">
                <span class="fb-tag">😊 Friendly</span>
                <span class="fb-tag">⚡ Fast</span>
            </div>
            <div class="fb-footer">
                <span>Anonymous customer</span>
                <span class="fb-amount">KES 200 tip</span>
            </div>
        </div>
        <div class="fb-card">
            <div class="fb-stars">★★★★★</div>
            <div class="fb-text">"Made my whole evening. The kind of service you remember."</div>
            <div class="fb-tags">
                <span class="fb-tag">🌟 Above & Beyond</span>
            </div>
            <div class="fb-footer">
                <span>Anonymous customer</span>
                <span class="fb-amount">KES 500 tip</span>
            </div>
        </div>
        <div class="fb-card">
            <div class="fb-stars">★★★★☆</div>
            <div class="fb-text">"Great attitude, always smiling. Place was busy but you handled it well."</div>
            <div class="fb-tags">
                <span class="fb-tag">💪 Hardworking</span>
                <span class="fb-tag">😊 Friendly</span>
            </div>
            <div class="fb-footer">
                <span>Anonymous customer</span>
                <span class="fb-amount">KES 150 tip</span>
            </div>
        </div>
        <div class="fb-card">
            <div class="fb-stars">★★★★★</div>
            <div class="fb-text">"Knew exactly what I needed before I asked. Impressive."</div>
            <div class="fb-tags">
                <span class="fb-tag">🧠 Knowledgeable</span>
                <span class="fb-tag">⚡ Fast</span>
            </div>
            <div class="fb-footer">
                <span>Anonymous customer</span>
                <span class="fb-amount">KES 300 tip</span>
            </div>
        </div>
    </div>
    <p style="margin-top:20px;font-size:12px;color:rgba(255,255,255,.3);text-align:center">Sample feedback — shown on your personal dashboard. Customer identities are always anonymous.</p>
</div>

<!-- Privacy guarantee -->
<div class="guarantee">
    <div class="section-tag">Your Privacy Guarantee</div>
    <h2>What Pregota protects — by design.</h2>
    <div class="guarantee-grid">
        <div class="g-card">
            <div class="g-icon">🔒</div>
            <div class="g-title">Number never shown</div>
            <div class="g-text">Your M-Pesa number is encrypted and never displayed on your tip page, receipts, or anywhere visible.</div>
        </div>
        <div class="g-card">
            <div class="g-icon">👁️</div>
            <div class="g-title">Employer can't see it</div>
            <div class="g-text">Even if your employer registers on Pregota, they cannot see your M-Pesa number. Your earnings are yours alone.</div>
        </div>
        <div class="g-card">
            <div class="g-icon">📵</div>
            <div class="g-title">No contact exposure</div>
            <div class="g-text">Customers cannot find your WhatsApp, call you, or contact you through Pregota. The transaction ends at the tip.</div>
        </div>
        <div class="g-card">
            <div class="g-icon">🗑️</div>
            <div class="g-title">No data stored</div>
            <div class="g-text">The sender's phone number is never saved in our system. We don't know who tipped you — only that they did.</div>
        </div>
    </div>
</div>

<!-- Who it's for -->
<div class="jobs">
    <div class="section-tag">Who This Is For</div>
    <h2>Any service job where tips matter.</h2>
    <div class="job-grid">
        <div class="job-card">
            <div class="job-emoji">🍽️</div>
            <div class="job-title">Waitstaff</div>
            <div class="job-sub">Restaurants & cafés</div>
        </div>
        <div class="job-card">
            <div class="job-emoji">💅</div>
            <div class="job-title">Salon Staff</div>
            <div class="job-sub">Stylists & therapists</div>
        </div>
        <div class="job-card">
            <div class="job-emoji">🏨</div>
            <div class="job-title">Hotel Staff</div>
            <div class="job-sub">Porters & housekeeping</div>
        </div>
        <div class="job-card">
            <div class="job-emoji">🚗</div>
            <div class="job-title">Drivers</div>
            <div class="job-sub">Taxi & ride-hail</div>
        </div>
        <div class="job-card">
            <div class="job-emoji">🛵</div>
            <div class="job-title">Delivery Riders</div>
            <div class="job-sub">Food & courier</div>
        </div>
        <div class="job-card">
            <div class="job-emoji">🎵</div>
            <div class="job-title">Performers</div>
            <div class="job-sub">Musicians & artists</div>
        </div>
    </div>
</div>

<!-- CTA bottom -->
<div class="cta-bottom">
    <h2>Your tips. Your privacy.<br>Your safety.</h2>
    <p>Join thousands of service workers in Kenya who tip privately.<br>Free to sign up. Takes 2 minutes.</p>
    <a href="{{ route('staff.register') }}" class="btn-primary" style="font-size:16px;padding:16px 36px">Create My Free Tip Page →</a>
    <p style="margin-top:16px;font-size:12px;color:rgba(255,255,255,.3)">No monthly fees · No hidden charges · Cancel anytime</p>
</div>

<!-- Employer strip -->
<div class="employer-strip">
    <p>Are you a business owner or manager? <a href="{{ route('business.register') }}">Register your business →</a> to enable tipping for your whole team and access service quality analytics.</p>
</div>

@include('partials.discover', ['current' => 'tips', 'fullWidth' => true])
<footer class="footer">© 2026 Pregota · Staff Privacy Protection · pregota.com</footer>

</body>
</html>
