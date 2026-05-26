<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Seller Pay Links, Subscriptions & Deni — Pregota</title>
<meta name="description" content="Get paid via M-Pesa. Pay links, till mode, subscriptions, group contributions, and deni tab tracking — all in one place.">
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh}
.nav{padding:16px 24px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.07);position:sticky;top:0;background:#0B141A;z-index:10}
.logo{font-size:20px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.nav-links{display:flex;gap:8px}
.nav-link{color:rgba(255,255,255,.78);text-decoration:none;font-size:13px;font-weight:600;padding:7px 14px;border:1px solid rgba(255,255,255,.1);border-radius:8px;transition:.15s}
.nav-link:hover{background:rgba(255,255,255,.06);color:#fff}
.nav-cta{background:linear-gradient(135deg,#25D366,#1aaa52);color:#fff!important;border-color:transparent!important}

.hero{padding:64px 24px 48px;text-align:center;max-width:640px;margin:0 auto}
.badge{display:inline-flex;align-items:center;gap:8px;background:rgba(0,166,81,.12);border:1px solid rgba(0,166,81,.25);border-radius:20px;padding:6px 16px;font-size:12px;font-weight:700;color:#25D366;margin-bottom:24px;letter-spacing:.04em}
h1{font-size:clamp(34px,6vw,54px);font-weight:900;line-height:1.09;letter-spacing:-.5px;margin-bottom:16px}
h1 em{font-style:normal;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.hero p{font-size:16px;color:rgba(255,255,255,.72);line-height:1.65;max-width:420px;margin:0 auto 32px}
.cta-row{display:flex;gap:12px;justify-content:center;flex-wrap:wrap}
.btn-primary{display:inline-flex;align-items:center;gap:8px;padding:14px 28px;background:linear-gradient(135deg,#25D366,#1aaa52);color:#fff;font-weight:800;font-size:15px;border-radius:12px;text-decoration:none;transition:.2s}
.btn-primary:hover{transform:translateY(-1px);box-shadow:0 8px 24px rgba(37,211,102,.3)}
.btn-sec{display:inline-flex;align-items:center;gap:8px;padding:14px 28px;background:rgba(255,255,255,.06);color:rgba(255,255,255,.88);font-weight:700;font-size:15px;border-radius:12px;text-decoration:none;border:1px solid rgba(255,255,255,.12);transition:.15s}
.btn-sec:hover{background:rgba(255,255,255,.1)}

.link-preview{margin:48px auto;max-width:380px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.1);border-radius:20px;padding:28px;text-align:center}
.link-url{font-family:monospace;font-size:14px;color:#25D366;font-weight:700;background:rgba(37,211,102,.08);border:1px solid rgba(37,211,102,.2);border-radius:8px;padding:8px 16px;display:inline-block;margin-bottom:8px}
.link-caption{font-size:12px;color:rgba(255,255,255,.55)}

.how{padding:0 24px 64px;max-width:760px;margin:0 auto}
.how h2{font-size:22px;font-weight:900;margin-bottom:28px;text-align:center}
.steps{display:grid;grid-template-columns:repeat(3,1fr);gap:20px}
@media(max-width:600px){.steps{grid-template-columns:1fr}}
.step{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);border-radius:16px;padding:24px;text-align:center}
.step-num{font-size:28px;margin-bottom:12px;display:block}
.step-title{font-size:15px;font-weight:800;margin-bottom:6px}
.step-desc{font-size:13px;color:rgba(255,255,255,.65);line-height:1.55}

.fee-box{background:rgba(37,211,102,.06);border:1px solid rgba(37,211,102,.18);border-radius:20px;padding:32px;max-width:500px;margin:0 auto 64px;text-align:center}
.fee-box h3{font-size:18px;font-weight:900;margin-bottom:8px}
.fee-big{font-size:42px;font-weight:900;color:#25D366;margin:12px 0 4px}
.fee-note{font-size:13px;color:rgba(255,255,255,.6);line-height:1.55}
.fee-examples{display:flex;gap:8px;justify-content:center;flex-wrap:wrap;margin-top:16px}
.fee-ex{background:rgba(255,255,255,.06);border-radius:8px;padding:6px 14px;font-size:12px;color:rgba(255,255,255,.75)}

.usecases{padding:0 24px 64px;max-width:760px;margin:0 auto}
.usecases h2{font-size:22px;font-weight:900;margin-bottom:28px;text-align:center}
.uc-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}
@media(max-width:560px){.uc-grid{grid-template-columns:1fr}}
.uc{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);border-radius:14px;padding:20px}
.uc-icon{font-size:24px;margin-bottom:8px}
.uc-name{font-weight:800;margin-bottom:4px}
.uc-desc{font-size:13px;color:rgba(255,255,255,.65);line-height:1.5}

.bottom-cta{padding:48px 24px;text-align:center;border-top:1px solid rgba(255,255,255,.06)}
.bottom-cta h2{font-size:26px;font-weight:900;margin-bottom:8px}
.bottom-cta p{font-size:14px;color:rgba(255,255,255,.6);margin-bottom:24px}
</style>
</head>
<body>

<nav class="nav">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    <div class="nav-links">
        <a href="{{ route('seller.login') }}" class="nav-link">Login</a>
        <a href="{{ route('seller.register') }}" class="nav-link nav-cta">Get My Pay Link</a>
    </div>
</nav>

<div class="hero">
    <div class="badge">🛍️ Pay Links · Subscriptions · Deni · Groups</div>
    <h1>Get paid via M-Pesa.<br><em>Every way you need.</em></h1>
    <p>One-off payments, recurring subscriptions, group contributions, deni tabs — all through M-Pesa STK Push. Your personal number stays private — always.</p>
    <div class="cta-row">
        <a href="{{ route('seller.register') }}" class="btn-primary">Get My Pay Link →</a>
        <a href="{{ route('seller.login') }}" class="btn-sec">I already have one</a>
    </div>
    <div class="link-preview">
        <div class="link-url">pregota.com/pay/yourshop</div>
        <div class="link-caption">Your unique payment page — share anywhere</div>
    </div>
</div>

<div class="how">
    <h2>How it works</h2>
    <div class="steps">
        <div class="step">
            <span class="step-num">⚡</span>
            <div class="step-title">Set up in 60 seconds</div>
            <div class="step-desc">Enter your business name, M-Pesa number, and set your handle. No documents needed.</div>
        </div>
        <div class="step">
            <span class="step-num">🔗</span>
            <div class="step-title">Share your link or QR</div>
            <div class="step-desc">Post on WhatsApp, Instagram, or print a QR code sticker. Matatus: stick it on the window — passengers scan it without you saying a word.</div>
        </div>
        <div class="step">
            <span class="step-num">💚</span>
            <div class="step-title">They see it, they pay</div>
            <div class="step-desc">The current route and fare are already on screen. Buyer just enters their phone number and confirms the M-Pesa PIN. Done.</div>
        </div>
    </div>
</div>

<div style="padding:0 24px 48px;max-width:540px;margin:0 auto">
    <div class="fee-box">
        <h3>Simple, honest pricing</h3>
        <div class="fee-big">1%</div>
        <div class="fee-note">1% per transaction, rounded up — minimum KES 2. Buyers pay the full amount — the fee is deducted from your daily payout.</div>
        <div class="fee-examples">
            <span class="fee-ex">KES 70 → you get KES 68</span>
            <span class="fee-ex">KES 500 → you get KES 495</span>
            <span class="fee-ex">KES 2,000 → you get KES 1,980</span>
        </div>
    </div>
</div>

<div class="usecases">
    <h2>Who is this for?</h2>
    <div class="uc-grid">
        <div class="uc">
            <div class="uc-icon">👗</div>
            <div class="uc-name">Instagram & TikTok Sellers</div>
            <div class="uc-desc">Stop sharing your personal M-Pesa in DMs. Drop your pay link in your bio.</div>
        </div>
        <div class="uc">
            <div class="uc-icon">🏪</div>
            <div class="uc-name">Kiosk & Small Shops</div>
            <div class="uc-desc">Print your QR code. Customers scan and pay — no till, no cash.</div>
        </div>
        <div class="uc">
            <div class="uc-icon">🍽️</div>
            <div class="uc-name">Restaurants — Pay Before Eat</div>
            <div class="uc-desc">Cashier enters the bill on the till tablet. Customer enters their number, pays — food goes out. No card machine needed.</div>
        </div>
        <div class="uc">
            <div class="uc-icon">🛒</div>
            <div class="uc-name">Supermarkets & Shops</div>
            <div class="uc-desc">Open Till Mode on a counter tablet. Cashier types the total — customer enters their M-Pesa number and pays. Faster than cash counting.</div>
        </div>
        <div class="uc">
            <div class="uc-icon">💇</div>
            <div class="uc-name">Salons & Freelancers</div>
            <div class="uc-desc">Send your link via WhatsApp. Client pays before or after service — your number stays private.</div>
        </div>
        <div class="uc">
            <div class="uc-icon">🚐</div>
            <div class="uc-name">Matatus & Transport</div>
            <div class="uc-desc">Conductor silently updates the route and fare on their phone. Passenger scans the QR — sees the exact fare already on screen — enters their number and pays. No shouting. No mishearing. No distraction.</div>
        </div>
        <div class="uc">
            <div class="uc-icon">♻️</div>
            <div class="uc-name">Subscriptions</div>
            <div class="uc-desc">Create monthly, quarterly, or annual plans. Subscribers pay once and auto-renew. You send WhatsApp reminder links — they tap and pay. Gyms, internet resellers, content services.</div>
        </div>
        <div class="uc">
            <div class="uc-icon">🤝</div>
            <div class="uc-name">Groups & Chamas</div>
            <div class="uc-desc">Welfare groups, chamas, churches, and choirs pay their monthly contributions via M-Pesa. Admin sees who has paid per period. No more chasing cash or WhatsApp shouting matches.</div>
        </div>
    </div>
</div>

<div style="padding:0 24px 64px;max-width:760px;margin:0 auto">
    <div style="background:rgba(168,85,247,.06);border:1px solid rgba(168,85,247,.2);border-radius:20px;padding:32px;text-align:center">
        <div style="font-size:28px;margin-bottom:12px">🖥️</div>
        <div style="font-size:18px;font-weight:900;margin-bottom:8px">Till Mode — built for the counter</div>
        <div style="font-size:14px;color:rgba(255,255,255,.6);line-height:1.65;max-width:440px;margin:0 auto">
            Open Till Mode on any tablet at your counter. Cashier types the total on a big keypad — hands it to the customer — customer enters their M-Pesa number and approves. Screen confirms payment, resets automatically for the next customer. No card machine. No cash. No app to install.
        </div>
    </div>
</div>

<div style="padding:0 24px 64px;max-width:760px;margin:0 auto">
    <div style="background:rgba(239,68,68,.05);border:1px solid rgba(239,68,68,.18);border-radius:20px;padding:32px;text-align:center">
        <div style="font-size:28px;margin-bottom:12px">🧾</div>
        <div style="font-size:18px;font-weight:900;margin-bottom:8px">Deni — track customer credit with zero hassle</div>
        <div style="font-size:14px;color:rgba(255,255,255,.6);line-height:1.65;max-width:480px;margin:0 auto 20px">
            Customer ate and promises to pay later? Record a deni in 30 seconds — enter what they owe, their phone number, and you get a WhatsApp-ready payment link. They tap it, pay via M-Pesa, and the money comes straight to you. No chasing. No awkward reminders. The balance updates in real time on both your admin view and their personal dashboard.
        </div>
        <a href="{{ route('deni.create') }}" style="display:inline-block;padding:12px 28px;background:rgba(239,68,68,.15);border:1px solid rgba(239,68,68,.3);color:#f87171;font-weight:700;font-size:14px;border-radius:11px;text-decoration:none">Record a Deni →</a>
    </div>
</div>

<div class="bottom-cta">
    <h2>Your pay link is free to create.</h2>
    <p>Takes 60 seconds. No documents. No bank account required.</p>
    <a href="{{ route('seller.register') }}" class="btn-primary">Get My Pay Link →</a>
</div>

</body>
</html>
