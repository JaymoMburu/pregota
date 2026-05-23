<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Creator Gift Pages — Pregota</title>
<meta name="description" content="Get paid by your Kenyan audience via M-Pesa. No bank account, no PayPal, no awkward payment links. Just your page and your fans.">
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh}

.nav{padding:14px 24px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.08);position:sticky;top:0;background:#0B141A;z-index:10}
.logo{font-size:20px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.nav-cta{background:linear-gradient(135deg,#00A651,#007A33);color:#fff;border:none;border-radius:8px;padding:8px 18px;font-size:13px;font-weight:700;cursor:pointer;text-decoration:none}

/* Hero */
.hero{padding:64px 24px 48px;text-align:center;max-width:620px;margin:0 auto}
.creator-badge{display:inline-flex;align-items:center;gap:7px;background:rgba(74,222,128,.1);border:1px solid rgba(74,222,128,.25);border-radius:20px;padding:6px 16px;font-size:12px;font-weight:700;color:#4ADE80;margin-bottom:24px;letter-spacing:.05em}
.hero h1{font-size:clamp(32px,6vw,52px);font-weight:900;line-height:1.1;letter-spacing:-.5px;margin-bottom:18px}
.hero h1 em{font-style:normal;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.hero p{font-size:16px;color:rgba(255,255,255,.55);line-height:1.7;margin-bottom:32px;max-width:440px;margin-left:auto;margin-right:auto}
.hero-btns{display:flex;gap:12px;justify-content:center;flex-wrap:wrap}
.btn-primary{background:linear-gradient(135deg,#00A651,#007A33);color:#fff;border:none;border-radius:12px;padding:14px 28px;font-size:15px;font-weight:700;cursor:pointer;text-decoration:none;display:inline-block}
.btn-secondary{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.15);color:rgba(255,255,255,.7);border-radius:12px;padding:14px 28px;font-size:15px;font-weight:700;text-decoration:none;display:inline-block}

/* Page preview */
.preview{padding:40px 24px;max-width:680px;margin:0 auto;text-align:center}
.preview-url{display:inline-flex;align-items:center;gap:10px;background:rgba(0,166,81,.12);border:1px solid rgba(0,166,81,.25);border-radius:12px;padding:10px 20px;font-size:14px;font-weight:700;color:#25D366;margin-bottom:24px;font-family:monospace}
.preview-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:20px;padding:28px;text-align:left;max-width:380px;margin:0 auto;position:relative}
.preview-avatar{width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,#00A651,#007A33);display:flex;align-items:center;justify-content:center;font-size:32px;margin-bottom:14px}
.preview-name{font-size:20px;font-weight:900;margin-bottom:4px}
.preview-handle{font-size:13px;color:rgba(255,255,255,.4);margin-bottom:12px}
.preview-bio{font-size:13px;color:rgba(255,255,255,.55);line-height:1.6;margin-bottom:20px}
.preview-amounts{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:20px}
.preview-amount{background:rgba(0,166,81,.15);border:1px solid rgba(0,166,81,.3);border-radius:8px;padding:8px 16px;font-size:13px;font-weight:700;color:#25D366;cursor:pointer}
.preview-amount.active{background:linear-gradient(135deg,#00A651,#007A33);border-color:transparent;color:#fff}
.preview-btn{width:100%;background:linear-gradient(135deg,#00A651,#007A33);border:none;border-radius:10px;padding:13px;font-size:14px;font-weight:700;color:#fff;cursor:pointer;text-align:center}
.preview-label{position:absolute;top:-12px;right:16px;background:#4ADE80;color:#fff;font-size:10px;font-weight:700;padding:3px 10px;border-radius:20px;letter-spacing:.05em}

/* Problem section */
.section{padding:56px 24px;max-width:680px;margin:0 auto}
.section-tag{display:inline-block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:rgba(255,255,255,.35);margin-bottom:12px}
.section h2{font-size:clamp(22px,4vw,32px);font-weight:900;line-height:1.2;margin-bottom:14px}
.section p{font-size:15px;color:rgba(255,255,255,.5);line-height:1.7}

.problem-card{background:rgba(239,68,68,.06);border:1px solid rgba(239,68,68,.2);border-radius:16px;padding:24px;margin-top:24px}
.problem-title{font-size:14px;font-weight:700;color:#fca5a5;margin-bottom:14px}
.problem-item{display:flex;align-items:flex-start;gap:12px;padding:10px 0;border-bottom:1px solid rgba(239,68,68,.1)}
.problem-item:last-child{border-bottom:none}
.problem-icon{font-size:18px;margin-top:1px;flex-shrink:0}
.problem-text strong{font-size:13px;color:#fca5a5;display:block;margin-bottom:2px}
.problem-text span{font-size:12px;color:rgba(255,255,255,.4);line-height:1.55}

/* How it works */
.how{background:rgba(0,166,81,.05);border-top:1px solid rgba(0,166,81,.1);border-bottom:1px solid rgba(0,166,81,.1);padding:56px 24px}
.how-inner{max-width:680px;margin:0 auto}
.steps{display:flex;flex-direction:column;gap:0;margin-top:28px}
.step{display:flex;gap:20px;padding:20px 0;border-bottom:1px solid rgba(255,255,255,.05)}
.step:last-child{border-bottom:none}
.step-num{width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#00A651,#007A33);display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:900;flex-shrink:0;margin-top:2px}
.step-body h3{font-size:15px;font-weight:700;margin-bottom:4px}
.step-body p{font-size:13px;color:rgba(255,255,255,.45);line-height:1.6}

/* What fans see */
.fan-section{padding:56px 24px;max-width:680px;margin:0 auto}
.fan-flow{display:flex;flex-direction:column;gap:12px;margin-top:28px}
.fan-step{display:flex;gap:16px;align-items:flex-start;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:14px;padding:18px}
.fan-step-icon{font-size:28px;flex-shrink:0}
.fan-step-body h4{font-size:14px;font-weight:700;margin-bottom:4px}
.fan-step-body p{font-size:13px;color:rgba(255,255,255,.45);line-height:1.6}

/* Earnings */
.earnings{background:rgba(74,222,128,.04);border-top:1px solid rgba(74,222,128,.1);border-bottom:1px solid rgba(74,222,128,.1);padding:56px 24px}
.earnings-inner{max-width:680px;margin:0 auto}
.earnings-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-top:28px}
@media(max-width:520px){.earnings-grid{grid-template-columns:1fr}}
.e-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:20px}
.e-icon{font-size:28px;margin-bottom:8px}
.e-title{font-size:14px;font-weight:700;color:#4ADE80;margin-bottom:4px}
.e-text{font-size:13px;color:rgba(255,255,255,.45);line-height:1.6}

/* Fee breakdown */
.fee-box{background:rgba(0,166,81,.08);border:1px solid rgba(0,166,81,.2);border-radius:14px;padding:20px;margin-top:24px}
.fee-row{display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid rgba(255,255,255,.06);font-size:13px}
.fee-row:last-child{border-bottom:none;font-weight:700;color:#25D366}
.fee-label{color:rgba(255,255,255,.55)}
.fee-value{font-weight:600}

/* Who it's for */
.who{padding:56px 24px;max-width:680px;margin:0 auto}
.creator-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-top:24px}
@media(max-width:480px){.creator-grid{grid-template-columns:1fr 1fr}}
.creator-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:12px;padding:16px;text-align:center}
.creator-emoji{font-size:28px;margin-bottom:6px}
.creator-title{font-size:13px;font-weight:700;color:rgba(255,255,255,.8)}
.creator-sub{font-size:11px;color:rgba(255,255,255,.35);margin-top:2px}

/* CTA */
.cta-bottom{background:linear-gradient(135deg,rgba(0,166,81,.15),rgba(0,122,51,.1));border-top:1px solid rgba(0,166,81,.2);padding:64px 24px;text-align:center}
.cta-bottom h2{font-size:clamp(24px,4vw,36px);font-weight:900;margin-bottom:12px}
.cta-bottom p{font-size:15px;color:rgba(255,255,255,.5);margin-bottom:32px;line-height:1.6}

.footer{padding:20px 24px;text-align:center;color:rgba(255,255,255,.2);font-size:11px;border-top:1px solid rgba(255,255,255,.06)}
</style>
</head>
<body>

<nav class="nav">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    <a href="{{ route('creator.register') }}" class="nav-cta">Get My Creator Page →</a>
</nav>

<!-- Hero -->
<div class="hero">
    <div class="creator-badge">🎤 For Kenyan Content Creators</div>
    <h1>Receive M-Pesa gifts.<br><em>Without sharing your number.</em></h1>
    <p>Your fans send you money in seconds via M-Pesa — privately. No "send to my number" conversations. No strangers saving your contact. No unwanted calls. Just gifts, straight to your M-Pesa.</p>
    <div class="hero-btns">
        <a href="{{ route('creator.register') }}" class="btn-primary">Create My Gift Page — Free</a>
        <a href="#how-it-works" class="btn-secondary">See How It Works</a>
    </div>
</div>

<!-- Page preview -->
<div class="preview">
    <div class="preview-url">🔗 pregota.com/c/<strong>yourname</strong></div>
    <div class="preview-card">
        <div class="preview-label">YOUR PAGE</div>
        <div class="preview-avatar">🎙️</div>
        <div class="preview-name">Jay Podcast</div>
        <div class="preview-handle">pregota.com/c/jaypodcast</div>
        <div class="preview-bio">Weekly conversations on Kenyan tech, business, and culture. If my content has added value to your life, buy me a coffee ☕</div>
        <div class="preview-amounts">
            <div class="preview-amount">KES 50</div>
            <div class="preview-amount active">KES 100</div>
            <div class="preview-amount">KES 200</div>
            <div class="preview-amount">KES 500</div>
        </div>
        <div class="preview-btn">Send Gift via M-Pesa ›</div>
    </div>
</div>

<!-- The problem -->
<div class="section">
    <div class="section-tag">The Problem</div>
    <h2>Kenyan creators can't get paid by their own audience.</h2>
    <p>You've built a following in Kenya. Your audience wants to support you. But every "support me" tool out there ignores them.</p>

    <div class="problem-card">
        <div class="problem-title">⚠️ Why existing tools fail Kenyan creators</div>
        <div class="problem-item">
            <div class="problem-icon">📱</div>
            <div class="problem-text">
                <strong>Sharing your M-Pesa number exposes everything</strong>
                <span>The moment you say "send to 07XX XXX XXX" — strangers have your full name, your WhatsApp, and a direct line to you forever. Creators face spam, unwanted messages, and worse. Your number was never meant to be public.</span>
            </div>
        </div>
        <div class="problem-item">
            <div class="problem-icon">💳</div>
            <div class="problem-text">
                <strong>PayPal & Buy Me a Coffee require a credit card</strong>
                <span>Most Kenyans don't have international credit cards. Your audience hits a wall before they can support you.</span>
            </div>
        </div>
        <div class="problem-item">
            <div class="problem-icon">🏦</div>
            <div class="problem-text">
                <strong>Bank transfers are too complicated</strong>
                <span>Sharing account numbers, branch codes, and reference numbers kills the moment. Nobody follows through.</span>
            </div>
        </div>
        <div class="problem-item">
            <div class="problem-icon">😔</div>
            <div class="problem-text">
                <strong>Paybill/Till numbers feel transactional and cold</strong>
                <span>They work for businesses — not for a personal creator relationship. Fans want to gift, not invoice.</span>
            </div>
        </div>
    </div>
</div>

<!-- How it works -->
<div class="how" id="how-it-works">
    <div class="how-inner">
        <div class="section-tag">How It Works</div>
        <h2>Your page. Their M-Pesa. Your earnings.</h2>
        <p style="font-size:15px;color:rgba(255,255,255,.5);margin-top:10px">Set up in 60 seconds. Share a single link. Receive gifts from anyone in Kenya instantly.</p>

        <div class="steps">
            <div class="step">
                <div class="step-num">1</div>
                <div class="step-body">
                    <h3>Create your free creator page</h3>
                    <p>Sign up with your name, handle, and a short bio about your content. Add your M-Pesa number privately — it's never shown to fans. You get your link instantly: <strong style="color:#25D366">pregota.com/c/yourhandle</strong></p>
                </div>
            </div>
            <div class="step">
                <div class="step-num">2</div>
                <div class="step-body">
                    <h3>Share your link everywhere</h3>
                    <p>Put it in your YouTube description, Instagram bio, TikTok link, podcast show notes, or Twitter/X profile. One link works everywhere — no app needed for your fans.</p>
                </div>
            </div>
            <div class="step">
                <div class="step-num">3</div>
                <div class="step-body">
                    <h3>Fan opens the link and picks an amount</h3>
                    <p>They choose KES 50, 100, 200, 500 — or type any amount. They enter their M-Pesa number and tap Send. An STK Push arrives on their phone. They confirm in 2 taps.</p>
                </div>
            </div>
            <div class="step">
                <div class="step-num">4</div>
                <div class="step-body">
                    <h3>Gift arrives on your M-Pesa instantly</h3>
                    <p>You get an M-Pesa notification. Your creator dashboard shows every gift with the amount and message. Your number was never revealed. Your privacy stays protected — always.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- What fans experience -->
<div class="fan-section">
    <div class="section-tag">Fan Experience</div>
    <h2>10 seconds. Zero friction.</h2>
    <p style="font-size:15px;color:rgba(255,255,255,.5);margin-top:10px;margin-bottom:0">This is what your fan sees when they click your link.</p>

    <div class="fan-flow">
        <div class="fan-step">
            <div class="fan-step-icon">🔗</div>
            <div class="fan-step-body">
                <h4>They open your link</h4>
                <p>Your page loads instantly — your photo, your bio, your gift amounts. No sign-up, no app download, no account needed.</p>
            </div>
        </div>
        <div class="fan-step">
            <div class="fan-step-icon">💰</div>
            <div class="fan-step-body">
                <h4>They pick an amount</h4>
                <p>Preset amounts (KES 50, 100, 200, 500) make it easy. They can also type any amount from KES 10 up.</p>
            </div>
        </div>
        <div class="fan-step">
            <div class="fan-step-icon">📱</div>
            <div class="fan-step-body">
                <h4>M-Pesa STK Push arrives</h4>
                <p>They enter their phone number. An M-Pesa prompt appears on their phone asking to confirm. No codes to copy, no bank details to enter.</p>
            </div>
        </div>
        <div class="fan-step">
            <div class="fan-step-icon">✅</div>
            <div class="fan-step-body">
                <h4>Done — gift sent</h4>
                <p>They see a confirmation screen. They can leave you a message. You get notified instantly. The whole thing takes under 30 seconds.</p>
            </div>
        </div>
    </div>
</div>

<!-- Earnings & fees -->
<div class="earnings">
    <div class="earnings-inner">
        <div class="section-tag">What You Earn</div>
        <h2>You keep what your fan intends to give.</h2>
        <p style="font-size:15px;color:rgba(255,255,255,.5);margin-top:10px">Your fan enters a gift amount — that's exactly what lands on your M-Pesa. Pregota's fees are added on top and paid by the fan. Example — fan gifts you KES 500:</p>

        <div class="fee-box">
            <div class="fee-row">
                <span class="fee-label">You receive</span>
                <span class="fee-value" style="color:#4ade80">KES 500</span>
            </div>
            <div class="fee-row">
                <span class="fee-label">Platform fee (added on top)</span>
                <span class="fee-value" style="color:rgba(255,255,255,.5)">+ KES 75</span>
            </div>
            <div class="fee-row">
                <span class="fee-label">Fan pays via M-Pesa</span>
                <span class="fee-value">KES 575</span>
            </div>
        </div>

        <div class="earnings-grid">
            <div class="e-card">
                <div class="e-icon">⚡</div>
                <div class="e-title">Instant payouts</div>
                <div class="e-text">Gifts go to your M-Pesa the moment they're confirmed. No waiting for a payout cycle or minimum balance.</div>
            </div>
            <div class="e-card">
                <div class="e-icon">📊</div>
                <div class="e-title">Gift dashboard</div>
                <div class="e-text">See every gift, amount, and fan message in your private dashboard. Track your earnings over time.</div>
            </div>
            <div class="e-card">
                <div class="e-icon">🔒</div>
                <div class="e-title">Your number stays private</div>
                <div class="e-text">Fans never see your M-Pesa number. No WhatsApp requests, no unsolicited calls. Your privacy is protected by design.</div>
            </div>
            <div class="e-card">
                <div class="e-icon">🆓</div>
                <div class="e-title">Free to create</div>
                <div class="e-text">No subscription, no monthly fee, no setup cost. We only earn when you earn. Your page is free forever.</div>
            </div>
        </div>
    </div>
</div>

<!-- Who it's for -->
<div class="who">
    <div class="section-tag">Who This Is For</div>
    <h2>Any creator with a Kenyan audience.</h2>
    <div class="creator-grid">
        <div class="creator-card">
            <div class="creator-emoji">📺</div>
            <div class="creator-title">YouTubers</div>
            <div class="creator-sub">Video creators</div>
        </div>
        <div class="creator-card">
            <div class="creator-emoji">🎙️</div>
            <div class="creator-title">Podcasters</div>
            <div class="creator-sub">Audio creators</div>
        </div>
        <div class="creator-card">
            <div class="creator-emoji">🎵</div>
            <div class="creator-title">Musicians</div>
            <div class="creator-sub">Artists & bands</div>
        </div>
        <div class="creator-card">
            <div class="creator-emoji">🎭</div>
            <div class="creator-title">Comedians</div>
            <div class="creator-sub">Skits & stand-up</div>
        </div>
        <div class="creator-card">
            <div class="creator-emoji">🎨</div>
            <div class="creator-title">Visual Artists</div>
            <div class="creator-sub">Designers & painters</div>
        </div>
        <div class="creator-card">
            <div class="creator-emoji">✍️</div>
            <div class="creator-title">Writers</div>
            <div class="creator-sub">Bloggers & authors</div>
        </div>
        <div class="creator-card">
            <div class="creator-emoji">🎮</div>
            <div class="creator-title">Gamers</div>
            <div class="creator-sub">Streamers & esports</div>
        </div>
        <div class="creator-card">
            <div class="creator-emoji">📸</div>
            <div class="creator-title">Photographers</div>
            <div class="creator-sub">Photography & film</div>
        </div>
        <div class="creator-card">
            <div class="creator-emoji">🧑‍🏫</div>
            <div class="creator-title">Educators</div>
            <div class="creator-sub">Tutors & mentors</div>
        </div>
    </div>
</div>

<!-- CTA bottom -->
<div class="cta-bottom">
    <h2>Your creativity deserves<br><em style="font-style:normal;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent">to be paid.</em></h2>
    <p>Join Kenyan creators already receiving M-Pesa gifts from their audience.<br>Free to set up. Takes 60 seconds.</p>
    <a href="{{ route('creator.register') }}" class="btn-primary" style="font-size:16px;padding:16px 36px">Create My Gift Page — Free →</a>
    <p style="margin-top:16px;font-size:12px;color:rgba(255,255,255,.3)">No monthly fees · No hidden charges · Cancel anytime</p>
</div>

@include('partials.discover', ['current' => 'creators', 'fullWidth' => true])
<footer class="footer">© 2026 Pregota · Creator Gift Pages · pregota.com</footer>

</body>
</html>
