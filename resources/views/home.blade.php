<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pregota — M-Pesa Made Simple</title>
<meta name="description" content="Digital M-Pesa payments for every Kenyan occasion. Gift vouchers, group collections, school collections, staff tips, seller pay links, and madeni tracking.">
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh}

.nav{padding:14px 24px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.08);position:sticky;top:0;background:#0B141A;z-index:10}
.logo{font-size:20px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.nav-links{display:flex;gap:8px;align-items:center}
.nav-link{color:rgba(255,255,255,.78);text-decoration:none;font-size:13px;font-weight:600;padding:7px 14px;border:1px solid rgba(255,255,255,.1);border-radius:8px;transition:.15s}
.nav-link:hover{background:rgba(255,255,255,.06);color:#fff}
.nav-cta{background:linear-gradient(135deg,#25D366,#1aaa52);color:#fff!important;border-color:transparent!important;border-radius:8px}
@media(max-width:520px){.nav-links{display:none}}

/* Hero */
.hero{padding:64px 24px 48px;text-align:center;max-width:640px;margin:0 auto}
.badge{display:inline-flex;align-items:center;gap:7px;background:rgba(0,166,81,.1);border:1px solid rgba(0,166,81,.25);border-radius:20px;padding:6px 16px;font-size:12px;font-weight:700;color:#25D366;margin-bottom:24px;letter-spacing:.05em}
.hero h1{font-size:clamp(32px,6vw,54px);font-weight:900;line-height:1.08;letter-spacing:-.5px;margin-bottom:18px}
.hero h1 em{font-style:normal;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.hero p{font-size:16px;color:rgba(255,255,255,.82);line-height:1.7;margin-bottom:32px;max-width:440px;margin-left:auto;margin-right:auto}
.hero-btns{display:flex;gap:12px;justify-content:center;flex-wrap:wrap}
.btn-primary{background:linear-gradient(135deg,#25D366,#1aaa52);color:#fff;border:none;border-radius:12px;padding:14px 28px;font-size:15px;font-weight:700;cursor:pointer;text-decoration:none;display:inline-block;transition:.2s}
.btn-primary:hover{transform:translateY(-1px);box-shadow:0 8px 24px rgba(37,211,102,.3)}
.btn-secondary{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.15);color:rgba(255,255,255,.7);border-radius:12px;padding:14px 28px;font-size:15px;font-weight:700;text-decoration:none;display:inline-block;transition:.15s}
.btn-secondary:hover{background:rgba(255,255,255,.1)}

/* Section common */
.section{padding:56px 24px;max-width:720px;margin:0 auto}
.section-tag{display:inline-block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:rgba(255,255,255,.6);margin-bottom:12px}
.section h2{font-size:clamp(22px,4vw,32px);font-weight:900;line-height:1.2;margin-bottom:14px}
.section p{font-size:15px;color:rgba(255,255,255,.78);line-height:1.7}

/* Module cards */
.modules-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-top:28px}
@media(max-width:580px){.modules-grid{grid-template-columns:1fr}}
.mod-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:18px;padding:24px;text-decoration:none;color:#fff;display:flex;flex-direction:column;transition:.2s;position:relative;overflow:hidden}
.mod-card:hover{transform:translateY(-2px)}
.mod-icon{font-size:34px;margin-bottom:12px}
.mod-name{font-size:17px;font-weight:900;margin-bottom:6px}
.mod-desc{font-size:13px;color:rgba(255,255,255,.72);line-height:1.6;flex:1;margin-bottom:16px}
.mod-tags{display:flex;flex-wrap:wrap;gap:5px;margin-bottom:16px}
.mod-tag{font-size:11px;padding:3px 10px;border-radius:20px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);color:rgba(255,255,255,.72)}
.mod-cta{font-size:13px;font-weight:700;display:inline-flex;align-items:center;gap:5px}
.mod-card:hover .mod-cta span{transform:translateX(3px);transition:.2s}

.mod-green{border-color:rgba(37,211,102,.15)}
.mod-green:hover{background:rgba(37,211,102,.08);border-color:rgba(37,211,102,.4)}
.mod-green .mod-cta{color:#25D366}
.mod-teal{border-color:rgba(16,185,129,.12)}
.mod-teal:hover{background:rgba(16,185,129,.07);border-color:rgba(16,185,129,.35)}
.mod-teal .mod-cta{color:#34d399}
.mod-blue{border-color:rgba(96,165,250,.12)}
.mod-blue:hover{background:rgba(96,165,250,.07);border-color:rgba(96,165,250,.35)}
.mod-blue .mod-cta{color:#60a5fa}
.mod-yellow{border-color:rgba(245,158,11,.12)}
.mod-yellow:hover{background:rgba(245,158,11,.06);border-color:rgba(245,158,11,.35)}
.mod-yellow .mod-cta{color:#fbbf24}
.mod-red{border-color:rgba(239,68,68,.15)}
.mod-red:hover{background:rgba(239,68,68,.06);border-color:rgba(239,68,68,.35)}
.mod-red .mod-cta{color:#f87171}

.mod-creator{background:linear-gradient(135deg,rgba(0,166,81,.1),rgba(236,72,153,.08));border-color:rgba(37,211,102,.2);grid-column:1/-1}
.mod-creator:hover{background:linear-gradient(135deg,rgba(0,166,81,.18),rgba(236,72,153,.14));border-color:rgba(37,211,102,.5)}
.mod-creator .mod-cta{color:#4ADE80}
.creator-badge{display:inline-flex;align-items:center;gap:6px;background:rgba(74,222,128,.12);border:1px solid rgba(74,222,128,.25);border-radius:20px;padding:3px 11px;font-size:11px;font-weight:700;color:#4ADE80;margin-bottom:12px;width:fit-content;letter-spacing:.04em}

/* How it works */
.how{background:rgba(0,166,81,.04);border-top:1px solid rgba(0,166,81,.1);border-bottom:1px solid rgba(0,166,81,.1);padding:56px 24px}
.how-inner{max-width:700px;margin:0 auto}
.steps{display:flex;flex-direction:column;gap:0;margin-top:28px}
.step{display:flex;gap:20px;padding:20px 0;border-bottom:1px solid rgba(255,255,255,.05)}
.step:last-child{border-bottom:none}
.step-num{width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#25D366,#1aaa52);display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:900;flex-shrink:0;margin-top:2px}
.step-body h3{font-size:15px;font-weight:700;margin-bottom:4px}
.step-body p{font-size:13px;color:rgba(255,255,255,.72);line-height:1.6}

/* Trust / advantages */
.adv{background:rgba(255,255,255,.02);border-top:1px solid rgba(255,255,255,.07);border-bottom:1px solid rgba(255,255,255,.07);padding:56px 24px}
.adv-inner{max-width:700px;margin:0 auto}
.adv-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-top:28px}
@media(max-width:520px){.adv-grid{grid-template-columns:1fr}}
.adv-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:20px}
.adv-icon{font-size:28px;margin-bottom:10px}
.adv-title{font-size:14px;font-weight:700;color:#25D366;margin-bottom:4px}
.adv-text{font-size:13px;color:rgba(255,255,255,.72);line-height:1.6}

/* Bottom CTA */
.cta-bottom{background:linear-gradient(135deg,rgba(0,166,81,.15),rgba(0,122,51,.08));border-top:1px solid rgba(0,166,81,.2);padding:64px 24px;text-align:center}
.cta-bottom h2{font-size:clamp(24px,4vw,36px);font-weight:900;margin-bottom:12px}
.cta-bottom p{font-size:15px;color:rgba(255,255,255,.78);margin-bottom:32px;line-height:1.6}

.footer{padding:20px 24px;text-align:center;color:rgba(255,255,255,.2);font-size:11px;border-top:1px solid rgba(255,255,255,.06)}
</style>
</head>
<body>

<nav class="nav">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    <div class="nav-links">
        <a href="{{ route('saka-keja.browse') }}" class="nav-link" style="color:#f59e0b;border-color:rgba(245,158,11,.25)">🏠 Saka Keja</a>
        <a href="{{ route('deni.landing') }}" class="nav-link">Deni</a>
        <a href="{{ route('redeem') }}" class="nav-link">Redeem a Gift</a>
        <a href="{{ route('buyer.me') }}" class="nav-link nav-cta">My Pregota</a>
    </div>
</nav>

<!-- Hero -->
<div class="hero">
    <div class="badge">🇰🇪 Built for Kenya · Powered by M-Pesa</div>
    <h1>M-Pesa.<br><em>Every way you need it.</em></h1>
    <p>Send gifts, collect contributions, track madeni, receive tips, run school collections — all via M-Pesa STK Push. No app. No account needed.</p>
    <div class="hero-btns">
        <a href="{{ route('buyer.me') }}" class="btn-primary">Open My Pregota →</a>
        <a href="#modules" class="btn-secondary">Explore All Features</a>
    </div>
</div>

<!-- Modules -->
<div class="section" id="modules">
    <div class="section-tag">Everything on Pregota</div>
    <h2>Pick what you need — pay via M-Pesa.</h2>
    <p>One platform covering every M-Pesa use case Kenyans deal with daily — from sending anonymous gifts to tracking a kibanda tab.</p>

    <div class="modules-grid">

        <!-- Gift Vouchers -->
        <a href="{{ route('gift.landing') }}" class="mod-card mod-green">
            <div class="mod-icon">🎁</div>
            <div class="mod-name">Gift Vouchers</div>
            <div class="mod-desc">Send money to anyone — anonymously. They receive a code to claim it. Your number is never revealed.</div>
            <div class="mod-tags">
                <span class="mod-tag">Birthday</span>
                <span class="mod-tag">Anniversary</span>
                <span class="mod-tag">Surprise</span>
            </div>
            <div class="mod-cta">Send a Gift <span>›</span></div>
        </a>

        <!-- Group Collections -->
        <a href="{{ route('collection.landing') }}" class="mod-card mod-teal">
            <div class="mod-icon">💬</div>
            <div class="mod-name">WhatsApp Collections</div>
            <div class="mod-desc">Share one link — everyone pays via M-Pesa directly. No group adds. Nobody holds the cash.</div>
            <div class="mod-tags">
                <span class="mod-tag">Bereavement</span>
                <span class="mod-tag">Chama</span>
                <span class="mod-tag">Wedding</span>
            </div>
            <div class="mod-cta">Start a Collection <span>›</span></div>
        </a>

        <!-- School Collections -->
        <a href="{{ route('school.landing') }}" class="mod-card mod-blue">
            <div class="mod-icon">🏫</div>
            <div class="mod-name">School Collections</div>
            <div class="mod-desc">Each class gets its own payment link. Parents pay via M-Pesa. Admin sees every payment in real time.</div>
            <div class="mod-tags">
                <span class="mod-tag">Remedial classes</span>
                <span class="mod-tag">Trips</span>
                <span class="mod-tag">PTA levy</span>
            </div>
            <div class="mod-cta">Set Up Collection <span>›</span></div>
        </a>

        <!-- Staff Tips -->
        <a href="{{ route('staff.landing') }}" class="mod-card mod-yellow">
            <div class="mod-icon">⭐</div>
            <div class="mod-name">Staff Tips</div>
            <div class="mod-desc">Receive tips directly to your M-Pesa without sharing your personal number. Privacy protected — always.</div>
            <div class="mod-tags">
                <span class="mod-tag">Waitstaff</span>
                <span class="mod-tag">Salon</span>
                <span class="mod-tag">Delivery</span>
            </div>
            <div class="mod-cta">Create Tip Page <span>›</span></div>
        </a>

        <!-- Seller Pay Links -->
        <a href="{{ route('seller.landing') }}" class="mod-card mod-green">
            <div class="mod-icon">🛍️</div>
            <div class="mod-name">Seller Pay Links</div>
            <div class="mod-desc">Get paid via M-Pesa without sharing your personal number. One link — share on WhatsApp, Instagram, or print a QR at your shop.</div>
            <div class="mod-tags">
                <span class="mod-tag">Instagram shops</span>
                <span class="mod-tag">Kiosks</span>
                <span class="mod-tag">Matatus</span>
            </div>
            <div class="mod-cta">Get My Pay Link <span>›</span></div>
        </a>

        <!-- Deni -->
        <a href="{{ route('deni.landing') }}" class="mod-card mod-red">
            <div class="mod-icon">🧾</div>
            <div class="mod-name">Deni</div>
            <div class="mod-desc">Extended credit at your kibanda? Lent a friend money? Record it — they get a payment link, pay via M-Pesa, money goes straight to you.</div>
            <div class="mod-tags">
                <span class="mod-tag">Vibanda</span>
                <span class="mod-tag">Restaurants</span>
                <span class="mod-tag">Personal loans</span>
            </div>
            <div class="mod-cta" style="color:#f87171">Record a Deni <span>›</span></div>
        </a>

        <!-- My Pregota -->
        <a href="{{ route('buyer.me') }}" class="mod-card mod-blue">
            <div class="mod-icon">📊</div>
            <div class="mod-name">My Pregota</div>
            <div class="mod-desc">Your personal hub — payments, active subscriptions, group contributions, outstanding madeni. One PIN, everything in one place.</div>
            <div class="mod-tags">
                <span class="mod-tag">Spending history</span>
                <span class="mod-tag">Groups</span>
                <span class="mod-tag">Madeni</span>
            </div>
            <div class="mod-cta" style="color:#60a5fa">Open My Dashboard <span>›</span></div>
        </a>

        <!-- Find Sellers -->
        <a href="{{ route('seller.directory') }}" class="mod-card mod-teal">
            <div class="mod-icon">🔍</div>
            <div class="mod-name">Find Sellers</div>
            <div class="mod-desc">Browse all businesses accepting Pregota payments. Pay without sharing numbers, get KRA-valid receipts, track your spending.</div>
            <div class="mod-tags">
                <span class="mod-tag">Matatus</span>
                <span class="mod-tag">Food</span>
                <span class="mod-tag">Salons</span>
            </div>
            <div class="mod-cta" style="color:#34d399">Browse & Pay <span>›</span></div>
        </a>

        <!-- Saka Keja — full width featured -->
        <a href="{{ route('saka-keja.browse') }}" class="mod-card" style="grid-column:1/-1;background:linear-gradient(135deg,rgba(245,158,11,.08),rgba(245,158,11,.04));border-color:rgba(245,158,11,.2)">
            <div style="display:inline-flex;align-items:center;gap:6px;background:rgba(245,158,11,.12);border:1px solid rgba(245,158,11,.25);border-radius:20px;padding:3px 11px;font-size:11px;font-weight:700;color:#f59e0b;margin-bottom:12px;letter-spacing:.04em">🏠 NEW — Saka Keja</div>
            <div style="display:flex;align-items:center;gap:20px;flex-wrap:wrap">
                <div style="font-size:44px;flex-shrink:0">🏡</div>
                <div style="flex:1;min-width:200px">
                    <div class="mod-name" style="font-size:19px">Saka Keja — Find a House, No Agents</div>
                    <div class="mod-desc" style="margin-bottom:12px">Browse verified landlord listings. Pay deposit safely through Pregota escrow — released only when you confirm move-in. No agents. No lost money.</div>
                    <div class="mod-tags">
                        <span class="mod-tag">Deposit Protected</span>
                        <span class="mod-tag">Verified Landlords</span>
                        <span class="mod-tag">No Middlemen</span>
                        <span class="mod-tag">Full Refund Guarantee</span>
                    </div>
                </div>
                <div class="mod-cta" style="flex-shrink:0;font-size:14px;align-self:flex-end;color:#f59e0b">Browse Houses <span>›</span></div>
            </div>
        </a>

        <!-- Creator Gift — full width -->
        <a href="{{ route('creator.landing') }}" class="mod-card mod-creator">
            <div class="creator-badge">✨ For Content Creators</div>
            <div style="display:flex;align-items:center;gap:20px;flex-wrap:wrap">
                <div class="mod-icon" style="margin-bottom:0;font-size:44px;flex-shrink:0">🎤</div>
                <div style="flex:1;min-width:200px">
                    <div class="mod-name" style="font-size:19px">Creator Gift Page</div>
                    <div class="mod-desc" style="margin-bottom:12px">Set up your personal gift page in 60 seconds. Fans send you M-Pesa gifts directly — no awkward conversations, no sharing your number.</div>
                    <div class="mod-tags">
                        <span class="mod-tag">TikTok</span>
                        <span class="mod-tag">Instagram</span>
                        <span class="mod-tag">YouTube</span>
                        <span class="mod-tag">Podcast</span>
                        <span class="mod-tag">pregota.com/c/yourname</span>
                    </div>
                </div>
                <div class="mod-cta" style="flex-shrink:0;font-size:14px;align-self:flex-end">Get Your Page <span>›</span></div>
            </div>
        </a>

    </div>
</div>

<!-- How it works -->
<div class="how">
    <div class="how-inner">
        <div class="section-tag">How It Works</div>
        <h2 style="font-size:clamp(22px,4vw,32px);font-weight:900;line-height:1.2;margin-bottom:14px">M-Pesa STK Push. No app. No sign-up.</h2>
        <p style="font-size:15px;color:rgba(255,255,255,.78);line-height:1.7">Every payment on Pregota goes through M-Pesa's own STK Push — the same prompt you already know. No download, no account, no card required.</p>

        <div class="steps">
            <div class="step">
                <div class="step-num">1</div>
                <div class="step-body">
                    <h3>Pick what you need</h3>
                    <p>Send a gift, start a collection, record a deni, create a pay link — or just pay a seller. Each flow is built for a specific Kenyan use case.</p>
                </div>
            </div>
            <div class="step">
                <div class="step-num">2</div>
                <div class="step-body">
                    <h3>Enter your M-Pesa number</h3>
                    <p>Type the number linked to your M-Pesa account. That's all we need — no registration, no password, no card details.</p>
                </div>
            </div>
            <div class="step">
                <div class="step-num">3</div>
                <div class="step-body">
                    <h3>Confirm on your phone</h3>
                    <p>An M-Pesa STK Push pops up instantly on your screen. Enter your PIN and confirm — takes under 10 seconds. Your statement shows "Pregota Ltd".</p>
                </div>
            </div>
            <div class="step">
                <div class="step-num">4</div>
                <div class="step-body">
                    <h3>Money moves immediately</h3>
                    <p>Gift codes are generated, contributions are recorded, seller accounts are credited, deni balances update — all in real time, the moment M-Pesa confirms.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Why Pregota -->
<div class="adv">
    <div class="adv-inner">
        <div class="section-tag">Why Pregota</div>
        <h2 style="font-size:clamp(22px,4vw,32px);font-weight:900;line-height:1.2;margin-bottom:14px">Built for how Kenyans actually move money.</h2>
        <p style="font-size:15px;color:rgba(255,255,255,.78);line-height:1.7">Standard M-Pesa is powerful but raw. Pregota wraps it in purpose-built flows for every common occasion — with privacy, accountability, and zero drama.</p>

        <div class="adv-grid">
            <div class="adv-card">
                <div class="adv-icon">🔒</div>
                <div class="adv-title">Nobody shares their personal number</div>
                <div class="adv-text">Senders, receivers, sellers, contributors — nobody's M-Pesa number is ever revealed to the other side. Phone numbers are encrypted and deleted after use.</div>
            </div>
            <div class="adv-card">
                <div class="adv-icon">📱</div>
                <div class="adv-title">No app. No account. No friction.</div>
                <div class="adv-text">Every Pregota flow works on any phone. Buyers and contributors don't need an account. Just a number and an M-Pesa PIN.</div>
            </div>
            <div class="adv-card">
                <div class="adv-icon">⚡</div>
                <div class="adv-title">Real-time everywhere</div>
                <div class="adv-text">Payments confirm in seconds. Collection totals update live. Deni balances reflect instantly. Seller dashboards show every transaction as it happens.</div>
            </div>
            <div class="adv-card">
                <div class="adv-icon">🔐</div>
                <div class="adv-title">Every transaction cryptographically sealed</div>
                <div class="adv-text">Each payment is signed with a tamper-proof hash — so there's a verifiable record of what was paid, when, and to whom. No disputes, no "I never received it".</div>
            </div>
        </div>
    </div>
</div>

<!-- Bottom CTA -->
<div class="cta-bottom">
    <h2>Start with whatever<br><em style="font-style:normal;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent">you need right now.</em></h2>
    <p>No account required. No monthly fee. Just M-Pesa — the way it should have always worked.</p>
    <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
        <a href="{{ route('gift.home') }}" class="btn-primary" style="font-size:15px;padding:15px 28px">🎁 Send a Gift →</a>
        <a href="{{ route('collection.new') }}" class="btn-secondary" style="font-size:15px;padding:15px 28px">💬 Start a Collection</a>
        <a href="{{ route('deni.landing') }}" class="btn-secondary" style="font-size:15px;padding:15px 28px">🧾 Record a Deni</a>
    </div>
    <p style="margin-top:20px;font-size:12px;color:rgba(255,255,255,.5)">🔒 Numbers encrypted · ⚡ M-Pesa STK Push · 🔐 Every transaction sealed</p>
</div>

<footer class="footer">© 2026 Pregota · M-Pesa Made Simple · pregota.com</footer>

</body>
</html>
