<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Deni â€” Track Credit & Collect What You're Owed Â· Pregota</title>
<meta name="description" content="Record a customer tab or personal loan in 30 seconds. They get a payment link, pay via M-Pesa, money goes straight to you. Real-time balance tracking for vibanda, restaurants, shops, and friends.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800;900&display=swap" rel="stylesheet">
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}input,textarea,select,button{font-family:inherit;font-size:inherit}
body{font-family:'Plus Jakarta Sans',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}

.nav{padding:14px 24px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.08);position:sticky;top:0;background:#0B141A;z-index:10}
.logo{font-size:20px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.nav-cta{background:linear-gradient(135deg,#dc2626,#ef4444);color:#fff;border:none;border-radius:8px;padding:8px 18px;font-size:13px;font-weight:700;cursor:pointer;text-decoration:none}

/* Hero */
.hero{padding:64px 24px 48px;text-align:center;max-width:640px;margin:0 auto}
.badge{display:inline-flex;align-items:center;gap:7px;background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);border-radius:20px;padding:6px 16px;font-size:12px;font-weight:700;color:#f87171;margin-bottom:24px;letter-spacing:.05em}
.hero h1{font-size:clamp(32px,6vw,52px);font-weight:900;line-height:1.08;letter-spacing:-.5px;margin-bottom:18px}
.hero h1 em{font-style:normal;background:linear-gradient(135deg,#f87171,#fbbf24);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.hero p{font-size:16px;color:rgba(255,255,255,.82);line-height:1.7;margin-bottom:32px;max-width:460px;margin-left:auto;margin-right:auto}
.hero-btns{display:flex;gap:12px;justify-content:center;flex-wrap:wrap}
.btn-primary{background:linear-gradient(135deg,#dc2626,#ef4444);color:#fff;border:none;border-radius:12px;padding:14px 28px;font-size:15px;font-weight:700;cursor:pointer;text-decoration:none;display:inline-block;transition:.2s}
.btn-primary:hover{transform:translateY(-1px);box-shadow:0 8px 24px rgba(239,68,68,.35)}
.btn-secondary{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.15);color:rgba(255,255,255,.7);border-radius:12px;padding:14px 28px;font-size:15px;font-weight:700;text-decoration:none;display:inline-block;transition:.15s}
.btn-secondary:hover{background:rgba(255,255,255,.1)}

/* Section common */
.section{padding:56px 24px;max-width:720px;margin:0 auto}
.section-tag{display:inline-block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:rgba(255,255,255,.6);margin-bottom:12px}
.section h2{font-size:clamp(22px,4vw,32px);font-weight:900;line-height:1.2;margin-bottom:14px}
.section p{font-size:15px;color:rgba(255,255,255,.78);line-height:1.7}

/* Demo card */
.demo-wrap{padding:32px 24px 0;max-width:400px;margin:0 auto;text-align:center}
.demo-card{background:rgba(255,255,255,.04);border:1px solid rgba(239,68,68,.2);border-radius:20px;padding:24px;text-align:left}
.demo-label{position:relative}
.demo-label::before{content:'LIVE EXAMPLE';position:absolute;top:-36px;left:50%;transform:translateX(-50%);background:rgba(239,68,68,.15);border:1px solid rgba(239,68,68,.25);color:#f87171;font-size:10px;font-weight:700;letter-spacing:.08em;padding:3px 10px;border-radius:20px;white-space:nowrap}
.demo-biz{font-size:12px;color:rgba(255,255,255,.45);margin-bottom:4px}
.demo-desc{font-size:17px;font-weight:900;margin-bottom:16px}
.demo-prog-row{display:flex;justify-content:space-between;font-size:12px;color:rgba(255,255,255,.55);margin-bottom:6px}
.demo-track{height:8px;background:rgba(255,255,255,.08);border-radius:999px;overflow:hidden;margin-bottom:6px}
.demo-fill{height:100%;background:linear-gradient(90deg,#ef4444,#fbbf24);border-radius:999px;width:33%}
.demo-labels{display:flex;justify-content:space-between;font-size:11px;color:rgba(255,255,255,.35);margin-bottom:16px}
.demo-btn{width:100%;padding:11px;background:linear-gradient(135deg,#dc2626,#ef4444);border:none;border-radius:10px;font-size:14px;font-weight:700;color:#fff;cursor:pointer}

/* Who it's for */
.who-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-top:28px}
@media(max-width:600px){.who-grid{grid-template-columns:1fr}}
.who-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:16px;padding:22px}
.who-icon{font-size:34px;margin-bottom:12px}
.who-title{font-size:15px;font-weight:800;margin-bottom:6px;color:#f87171}
.who-desc{font-size:13px;color:rgba(255,255,255,.72);line-height:1.6}
.who-tags{display:flex;flex-wrap:wrap;gap:5px;margin-top:12px}
.who-tag{font-size:11px;padding:3px 9px;border-radius:20px;background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.15);color:rgba(255,255,255,.65)}

/* Problem */
.prob-section{background:rgba(239,68,68,.03);border-top:1px solid rgba(239,68,68,.1);border-bottom:1px solid rgba(239,68,68,.1);padding:56px 24px}
.prob-inner{max-width:720px;margin:0 auto}
.prob-card{background:rgba(239,68,68,.06);border:1px solid rgba(239,68,68,.15);border-radius:16px;padding:24px;margin-top:24px}
.prob-item{display:flex;align-items:flex-start;gap:14px;padding:13px 0;border-bottom:1px solid rgba(239,68,68,.1)}
.prob-item:last-child{border-bottom:none}
.prob-icon{font-size:22px;flex-shrink:0;margin-top:1px}
.prob-text strong{font-size:13px;color:#fca5a5;display:block;margin-bottom:3px}
.prob-text span{font-size:12px;color:rgba(255,255,255,.68);line-height:1.6}

/* How it works */
.how{background:rgba(239,68,68,.03);border-top:1px solid rgba(239,68,68,.08);border-bottom:1px solid rgba(239,68,68,.08);padding:56px 24px}
.how-inner{max-width:700px;margin:0 auto}
.steps{display:flex;flex-direction:column;gap:0;margin-top:28px}
.step{display:flex;gap:20px;padding:20px 0;border-bottom:1px solid rgba(255,255,255,.05)}
.step:last-child{border-bottom:none}
.step-num{width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#dc2626,#ef4444);display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:900;flex-shrink:0;margin-top:2px}
.step-body h3{font-size:15px;font-weight:700;margin-bottom:4px}
.step-body p{font-size:13px;color:rgba(255,255,255,.72);line-height:1.6}

/* Advantages */
.adv-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-top:28px}
@media(max-width:520px){.adv-grid{grid-column:1fr}}
.adv-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:20px}
.adv-icon{font-size:28px;margin-bottom:10px}
.adv-title{font-size:14px;font-weight:700;color:#f87171;margin-bottom:4px}
.adv-text{font-size:13px;color:rgba(255,255,255,.72);line-height:1.6}

/* Use cases grid */
.uc-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-top:24px}
@media(max-width:500px){.uc-grid{grid-template-columns:1fr 1fr}}
.uc-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:12px;padding:16px;text-align:center}
.uc-emoji{font-size:28px;margin-bottom:6px}
.uc-name{font-size:13px;font-weight:700;color:rgba(255,255,255,.8)}
.uc-sub{font-size:11px;color:rgba(255,255,255,.55);margin-top:2px;line-height:1.4}

/* Bottom CTA */
.cta-bottom{background:linear-gradient(135deg,rgba(220,38,38,.15),rgba(239,68,68,.07));border-top:1px solid rgba(239,68,68,.2);padding:64px 24px;text-align:center}
.cta-bottom h2{font-size:clamp(24px,4vw,36px);font-weight:900;margin-bottom:12px}
.cta-bottom p{font-size:15px;color:rgba(255,255,255,.78);margin-bottom:32px;line-height:1.6}
.footer{padding:20px 24px;text-align:center;color:rgba(255,255,255,.2);font-size:11px;border-top:1px solid rgba(255,255,255,.06)}
</style>
</head>
<body>

<nav class="nav">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    <div style="display:flex;gap:10px;align-items:center">
        <a href="{{ route('creditor.login') }}" style="color:rgba(255,255,255,.6);font-size:13px;font-weight:600;text-decoration:none">Smart Biashara â†’</a>
        <a href="{{ route('deni.create') }}" class="nav-cta">Record a Deni â†’</a>
    </div>
</nav>

<!-- Hero -->
<div class="hero">
    <div class="badge">ðŸ§¾ Deni â€” For Lenders &amp; Businesses</div>
    <h1>Credit given.<br><em>Always collected.</em></h1>
    <p>Whether you run a kibanda extending lunch credit or you're a friend who lent money â€” record it in 30 seconds. They get a payment link, pay via M-Pesa, money lands straight in your account. No chasing. No awkward reminders.</p>
    <div class="hero-btns">
        <a href="{{ route('deni.create') }}" class="btn-primary">Record a Deni â€” Free â†’</a>
        <a href="{{ route('creditor.login') }}" class="btn-secondary">ðŸ¢ Smart Biashara â†’</a>
    </div>
    <div style="margin-top:14px;font-size:12px;color:rgba(255,255,255,.35)">Creditor or business? <a href="{{ route('creditor.login') }}" style="color:#f87171;text-decoration:none;font-weight:700">Smart Biashara â†’</a> â€” one dashboard for all your madeni.</div>
</div>

<!-- Demo preview -->
<div class="demo-wrap">
    <div class="demo-label">
        <div class="demo-card" style="margin-top:24px">
            <div class="demo-biz">Mama Njeri Kibanda</div>
            <div class="demo-desc">Lunch â€” rice, beef stew & chai</div>
            <div class="demo-prog-row">
                <span>Balance remaining</span>
                <span style="font-weight:900;color:#fff">KES 80</span>
            </div>
            <div class="demo-track"><div class="demo-fill"></div></div>
            <div class="demo-labels">
                <span>KES 40 paid</span>
                <span>KES 120 total</span>
            </div>
            <div class="demo-btn">Pay via M-Pesa â†’</div>
        </div>
    </div>
</div>

<!-- Who it's for -->
<div class="section">
    <div class="section-tag">Who Uses Deni</div>
    <h2>For personal lenders and businesses.</h2>
    <p>Whether you run a kibanda, a shop, or a boda boda â€” or you're a friend who regularly lends cash â€” Deni tracks every shilling owed and collects it via M-Pesa. No awkward conversations. No lost money.</p>

    <div class="who-grid">
        <div class="who-card">
            <div class="who-icon">ðŸ²</div>
            <div class="who-title">Vibanda & Restaurants</div>
            <div class="who-desc">Customer eats and promises to pay later. Record the tab in seconds â€” they get a link, you get the money when they pay. Track partial payments automatically.</div>
            <div class="who-tags">
                <span class="who-tag">Lunch tabs</span>
                <span class="who-tag">Running credit</span>
                <span class="who-tag">Regular customers</span>
            </div>
        </div>
        <div class="who-card">
            <div class="who-icon">ðŸª</div>
            <div class="who-title">Shops & Businesses</div>
            <div class="who-desc">Sold goods on credit or offer business-to-customer lending? Record what's owed, send the payment link via WhatsApp â€” customer pays via M-Pesa, balance updates in real time.</div>
            <div class="who-tags">
                <span class="who-tag">Shop credit</span>
                <span class="who-tag">B2C lending</span>
                <span class="who-tag">Goods on account</span>
            </div>
        </div>
        <div class="who-card">
            <div class="who-icon">ðŸ’°</div>
            <div class="who-title">Personal Lenders</div>
            <div class="who-desc">Lent a friend cash, covered a bill, or helped someone in an emergency? Create a deni â€” they get a payment link and you track every partial payment until it's fully repaid.</div>
            <div class="who-tags">
                <span class="who-tag">Personal loans</span>
                <span class="who-tag">Fare covered</span>
                <span class="who-tag">Emergency help</span>
            </div>
        </div>
    </div>
</div>

<!-- The Problem -->
<div class="prob-section">
    <div class="prob-inner">
        <div class="section-tag">The Problem</div>
        <h2 style="font-size:clamp(22px,4vw,32px);font-weight:900;line-height:1.2;margin-bottom:14px">Giving credit in Kenya is an act of faith. Collecting it is war.</h2>
        <p style="font-size:15px;color:rgba(255,255,255,.78);line-height:1.7">Every day, thousands of vibanda owners, shop keepers, and friends extend credit without any system to track or collect it. Here's exactly how it falls apart.</p>

        <div class="prob-card">
            <div class="prob-item">
                <div class="prob-icon">ðŸ““</div>
                <div class="prob-text">
                    <strong>The exercise book that everyone ignores</strong>
                    <span>You write it down. The customer knows you wrote it down. But when it's time to collect, somehow the amount is always wrong, partially forgotten, or "already paid".</span>
                </div>
            </div>
            <div class="prob-item">
                <div class="prob-icon">ðŸ˜¬</div>
                <div class="prob-text">
                    <strong>Asking for your money feels like begging</strong>
                    <span>You serve the same people every day. Chasing them for KES 120 damages the relationship, makes the air awkward, and sometimes means they stop coming altogether.</span>
                </div>
            </div>
            <div class="prob-item">
                <div class="prob-icon">ðŸ“µ</div>
                <div class="prob-text">
                    <strong>They stop picking up your calls</strong>
                    <span>You have their number. You call. They see it's you. They don't answer. You can't afford a lawyer. You can't afford to write it off. You just stew.</span>
                </div>
            </div>
            <div class="prob-item">
                <div class="prob-icon">ðŸ¤¯</div>
                <div class="prob-text">
                    <strong>Multiple small debts are impossible to track</strong>
                    <span>Ten customers, each with a running tab, each paying partial amounts at different times. Manually reconciling who paid what, when, and what's still owed â€” daily. No sane person can do this accurately.</span>
                </div>
            </div>
        </div>
        <p style="text-align:center;font-size:15px;color:rgba(255,255,255,.78);margin-top:28px;font-weight:700">Deni solves this â€” for the person giving credit and the person who owes.</p>
    </div>
</div>

<!-- How it works -->
<div class="how" id="how-it-works">
    <div class="how-inner">
        <div class="section-tag">How It Works</div>
        <h2 style="font-size:clamp(22px,4vw,32px);font-weight:900;line-height:1.2;margin-bottom:14px">Record a deni in 30 seconds. Collect via M-Pesa.</h2>
        <p style="font-size:15px;color:rgba(255,255,255,.78);line-height:1.7">No account needed. No registration. Just a form, a link, and M-Pesa.</p>

        <div class="steps">
            <div class="step">
                <div class="step-num">1</div>
                <div class="step-body">
                    <h3>Fill in the details</h3>
                    <p>Your name or business, what the deni is for, the amount owed, and your M-Pesa number (where the money will land when they pay). Optionally add their phone and a due date.</p>
                </div>
            </div>
            <div class="step">
                <div class="step-num">2</div>
                <div class="step-body">
                    <h3>You get two links instantly</h3>
                    <p>An <strong style="color:#fbbf24">admin link</strong> (bookmark it â€” this is your management view showing all payments and balance) and a <strong style="color:#f87171">customer payment link</strong> to share via WhatsApp.</p>
                </div>
            </div>
            <div class="step">
                <div class="step-num">3</div>
                <div class="step-body">
                    <h3>Customer opens their link, pays via M-Pesa</h3>
                    <p>They see the balance, how much they've paid, and how much remains. They enter their M-Pesa number and confirm â€” an STK Push pops up on their phone. Full payment or partial â€” their choice.</p>
                </div>
            </div>
            <div class="step">
                <div class="step-num">4</div>
                <div class="step-body">
                    <h3>Money goes straight to your M-Pesa</h3>
                    <p>The moment they confirm their PIN, the payment processes and lands in your M-Pesa via B2C. The balance updates on both your admin view and their customer link â€” in real time.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Advantages -->
<div class="section">
    <div class="section-tag">Why Deni Works</div>
    <h2>Accountability without confrontation.</h2>
    <p>The link does the chasing for you. The customer knows what they owe, sees their progress, and can pay any time â€” no awkward face-to-face required.</p>

    <div class="adv-grid">
        <div class="adv-card">
            <div class="adv-icon">ðŸ’¬</div>
            <div class="adv-title">WhatsApp-ready in one tap</div>
            <div class="adv-text">After creating the deni, you get a pre-formatted WhatsApp message with the payment link ready to send. Tap once â€” it opens in WhatsApp with the message already written.</div>
        </div>
        <div class="adv-card">
            <div class="adv-icon">ðŸ“Š</div>
            <div class="adv-title">Real-time balance â€” both sides</div>
            <div class="adv-text">You see every payment the moment it confirms. The customer sees their remaining balance and payment history. No disputes, no "I already paid you".</div>
        </div>
        <div class="adv-card">
            <div class="adv-icon">ðŸ’³</div>
            <div class="adv-title">Partial payments â€” fully supported</div>
            <div class="adv-text">Customer can pay whatever they have now. The progress bar updates, the balance adjusts. You see KES 40 paid against KES 120 owed. Next time they pay the rest.</div>
        </div>
        <div class="adv-card">
            <div class="adv-icon">âš¡</div>
            <div class="adv-title">Money lands instantly via B2C</div>
            <div class="adv-text">Payments go via M-Pesa STK Push into Pregota's till, then B2C'd directly to your personal M-Pesa number â€” the same moment they confirm their PIN.</div>
        </div>
        <div class="adv-card">
            <div class="adv-icon">ðŸ”’</div>
            <div class="adv-title">No account needed</div>
            <div class="adv-text">You don't need to register. Neither does the customer. A vibanda owner can record a deni in under a minute, send the link, and forget about chasing â€” all without signing up for anything.</div>
        </div>
        <div class="adv-card">
            <div class="adv-icon">ðŸ“±</div>
            <div class="adv-title">Appears on buyer's dashboard</div>
            <div class="adv-text">If the customer uses Pregota, their outstanding madeni show on their My Pregota dashboard â€” a gentle, always-visible reminder of what they still owe.</div>
        </div>
    </div>
</div>

<!-- Occasions -->
<div style="padding:0 24px 56px;max-width:720px;margin:0 auto">
    <div class="section-tag">When to Use It</div>
    <h2 style="font-size:clamp(22px,4vw,32px);font-weight:900;line-height:1.2;margin-bottom:14px">Any time you give before you receive.</h2>
    <div class="uc-grid">
        <div class="uc-card"><div class="uc-emoji">ðŸ›</div><div class="uc-name">Kibanda Lunch</div><div class="uc-sub">Ate, will pay Friday</div></div>
        <div class="uc-card"><div class="uc-emoji">ðŸ¥¤</div><div class="uc-name">Running Tab</div><div class="uc-sub">Daily tea & mandazi</div></div>
        <div class="uc-card"><div class="uc-emoji">ðŸ›’</div><div class="uc-name">Shop Credit</div><div class="uc-sub">Goods on account</div></div>
        <div class="uc-card"><div class="uc-emoji">ðŸš</div><div class="uc-name">Fare Covered</div><div class="uc-sub">Paid for their bus</div></div>
        <div class="uc-card"><div class="uc-emoji">ðŸ’‡</div><div class="uc-name">Salon Credit</div><div class="uc-sub">Hair done, pay later</div></div>
        <div class="uc-card"><div class="uc-emoji">ðŸ¤</div><div class="uc-name">Personal Loan</div><div class="uc-sub">Lent a friend cash</div></div>
        <div class="uc-card"><div class="uc-emoji">ðŸ¥</div><div class="uc-name">Emergency Covered</div><div class="uc-sub">Paid their hospital</div></div>
        <div class="uc-card"><div class="uc-emoji">ðŸ“¦</div><div class="uc-name">Goods on Delivery</div><div class="uc-sub">Delivered, awaiting pay</div></div>
        <div class="uc-card"><div class="uc-emoji">ðŸŽ“</div><div class="uc-name">Fees Helped</div><div class="uc-sub">Covered their school</div></div>
    </div>
</div>

<!-- Bottom CTA -->
<div class="cta-bottom">
    <h2>Stop chasing.<br><em style="font-style:normal;background:linear-gradient(135deg,#f87171,#fbbf24);-webkit-background-clip:text;-webkit-text-fill-color:transparent">Start collecting.</em></h2>
    <p>Record your first deni in under a minute. Free to create. No account needed. Money goes straight to your M-Pesa the moment they pay.</p>
    <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
        <a href="{{ route('deni.create') }}" class="btn-primary" style="font-size:16px;padding:15px 36px">Record a Deni â€” Free â†’</a>
        <a href="{{ route('buyer.me') }}" class="btn-secondary" style="font-size:15px;padding:14px 28px">ðŸ“Š View My Madeni</a>
    </div>
    <p style="margin-top:16px;font-size:12px;color:rgba(255,255,255,.5)">No account needed Â· Money via B2C to your M-Pesa Â· Partial payments supported</p>
</div>

@include('partials.discover', ['current' => 'deni', 'fullWidth' => true])
<footer class="footer">Â© 2026 Pregota Â· Deni â€” Credit Tracking via M-Pesa Â· pregota.com</footer>

</body>
</html>


