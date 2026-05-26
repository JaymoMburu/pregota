<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pregota — M-Pesa Made Simple</title>
<meta name="description" content="Digital M-Pesa payments for every Kenyan occasion. Gift vouchers, group collections, school collections, staff tips.">
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh}

/* Nav */
.nav{padding:16px 24px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.07);position:sticky;top:0;background:#0B141A;z-index:10}
.logo{font-size:22px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.nav-links{display:flex;gap:8px}
.nav-link{color:rgba(255,255,255,.78);text-decoration:none;font-size:13px;font-weight:600;padding:7px 14px;border:1px solid rgba(255,255,255,.1);border-radius:8px;transition:.15s}
.nav-link:hover{background:rgba(255,255,255,.06);color:#fff}

/* Hero */
.hero{padding:64px 24px 40px;text-align:center;max-width:640px;margin:0 auto}
.hero-tag{display:inline-flex;align-items:center;gap:8px;background:rgba(0,166,81,.12);border:1px solid rgba(0,166,81,.25);border-radius:20px;padding:6px 16px;font-size:12px;font-weight:700;color:#25D366;margin-bottom:24px;letter-spacing:.04em}
.hero h1{font-size:clamp(36px,6vw,58px);font-weight:900;line-height:1.08;letter-spacing:-.5px;margin-bottom:16px}
.hero h1 em{font-style:normal;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.hero p{font-size:16px;color:rgba(255,255,255,.72);line-height:1.65;max-width:400px;margin:0 auto}

/* Module grid */
.modules{padding:16px 24px 72px;max-width:880px;margin:0 auto}
.modules-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:rgba(255,255,255,.82);text-align:center;margin-bottom:20px}
.module-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}
@media(max-width:600px){.module-grid{grid-template-columns:1fr}}

.module-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:20px;padding:28px 26px;display:flex;flex-direction:column;gap:0;transition:.2s;text-decoration:none;color:#fff;position:relative;overflow:hidden}
.module-card::before{content:'';position:absolute;inset:0;border-radius:20px;opacity:0;transition:.2s;pointer-events:none}
.module-card:hover{transform:translateY(-2px);border-color:rgba(0,166,81,.4)}
.module-card:hover::before{opacity:1}

.mc-gift:hover{background:rgba(0,166,81,.1)}
.mc-gift:hover::before{background:radial-gradient(ellipse at top left,rgba(37,211,102,.08),transparent 60%)}
.mc-collection:hover{background:rgba(16,185,129,.06)}
.mc-collection:hover::before{background:radial-gradient(ellipse at top left,rgba(16,185,129,.06),transparent 60%)}
.mc-school:hover{background:rgba(59,130,246,.07)}
.mc-school:hover::before{background:radial-gradient(ellipse at top left,rgba(59,130,246,.06),transparent 60%)}
.mc-tips:hover{background:rgba(245,158,11,.06)}
.mc-tips:hover::before{background:radial-gradient(ellipse at top left,rgba(245,158,11,.05),transparent 60%)}
.mc-creator{background:linear-gradient(135deg,rgba(0,166,81,.12),rgba(236,72,153,.1));border-color:rgba(37,211,102,.2)}
.mc-creator:hover{background:linear-gradient(135deg,rgba(0,166,81,.2),rgba(236,72,153,.15));border-color:rgba(37,211,102,.5);transform:translateY(-2px)}
.mc-creator .mc-cta{color:#4ADE80}
.mc-creator-badge{display:inline-flex;align-items:center;gap:6px;background:rgba(74,222,128,.12);border:1px solid rgba(74,222,128,.25);border-radius:20px;padding:4px 12px;font-size:11px;font-weight:700;color:#4ADE80;margin-bottom:14px;letter-spacing:.04em;width:fit-content}

.mc-icon{font-size:36px;margin-bottom:14px;display:block;position:relative;z-index:1}
.mc-name{font-size:20px;font-weight:900;margin-bottom:6px;position:relative;z-index:1}
.mc-desc{font-size:13px;color:rgba(255,255,255,.72);line-height:1.6;margin-bottom:20px;flex:1;position:relative;z-index:1}
.mc-examples{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:20px;position:relative;z-index:1}
.mc-tag{font-size:11px;padding:3px 10px;border-radius:20px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);color:rgba(255,255,255,.78)}
.mc-cta{display:inline-flex;align-items:center;gap:6px;font-size:13px;font-weight:700;color:#25D366;position:relative;z-index:1}
.mc-cta span{transition:.2s}
.module-card:hover .mc-cta span{transform:translateX(3px)}

.mc-gift .mc-cta{color:#25D366}
.mc-collection .mc-cta{color:#34d399}
.mc-school .mc-cta{color:#60a5fa}
.mc-tips .mc-cta{color:#fbbf24}

/* Trust bar */
.trust{padding:24px;border-top:1px solid rgba(255,255,255,.06);display:flex;justify-content:center;gap:32px;flex-wrap:wrap}
.trust-item{display:flex;align-items:center;gap:8px;font-size:12px;color:rgba(255,255,255,.6)}
.trust-item span{font-size:16px}

@media(max-width:480px){
    .hero{padding:40px 20px 28px}
    .modules{padding:12px 16px 48px}
    .module-card{padding:22px 18px}
    .mc-name{font-size:18px}
    .nav-links{display:none}
}
</style>
</head>
<body>

<nav class="nav">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    <div class="nav-links">
        <a href="{{ route('redeem') }}" class="nav-link">Redeem a Gift</a>
        <a href="{{ route('staff.landing') }}" class="nav-link">For Staff</a>
        <a href="{{ route('business.register') }}" class="nav-link">For Business</a>
    </div>
</nav>

<div class="hero">
    <div class="hero-tag">🇰🇪 Built for Kenya · Powered by M-Pesa</div>
    <h1>Money moving.<br><em>Zero hassle.</em></h1>
    <p>Built for how Kenyans actually move money.</p>
</div>

<div class="modules">
    <div class="modules-label">Choose what you need</div>
    <div class="module-grid">

        <!-- Gift Vouchers -->
        <a href="{{ route('gift.landing') }}" class="module-card mc-gift">
            <span class="mc-icon">🎁</span>
            <div class="mc-name">Gift Vouchers</div>
            <div class="mc-desc">Send money to anyone — anonymously. They get a code to claim it. Your number is never revealed.</div>
            <div class="mc-examples">
                <span class="mc-tag">Birthday</span>
                <span class="mc-tag">Anniversary</span>
                <span class="mc-tag">Thank you</span>
                <span class="mc-tag">Surprise</span>
            </div>
            <div class="mc-cta">Send a Gift <span>›</span></div>
        </a>

        <!-- Group Collections -->
        <a href="{{ route('collection.landing') }}" class="module-card mc-collection">
            <span class="mc-icon">💬</span>
            <div class="mc-name">WhatsApp Group Collections</div>
            <div class="mc-desc">Tired of being added to a WhatsApp contribution group you never asked for? Tired of chasing people or holding their cash? Share one link — everyone pays via M-Pesa directly. Nobody gets added to anything.</div>
            <div class="mc-examples">
                <span class="mc-tag">Bereavement</span>
                <span class="mc-tag">Chama</span>
                <span class="mc-tag">Wedding</span>
                <span class="mc-tag">Medical</span>
            </div>
            <div class="mc-cta">Start a Collection <span>›</span></div>
        </a>

        <!-- School Collections -->
        <a href="{{ route('school.landing') }}" class="module-card mc-school">
            <span class="mc-icon">🏫</span>
            <div class="mc-name">School Collections</div>
            <div class="mc-desc">Set up a school collection in minutes. Each class gets its own payment link. Admin sees every payment in real time.</div>
            <div class="mc-examples">
                <span class="mc-tag">Remedial classes</span>
                <span class="mc-tag">School trips</span>
                <span class="mc-tag">PTA levy</span>
                <span class="mc-tag">Prize giving</span>
            </div>
            <div class="mc-cta">Set Up Collection <span>›</span></div>
        </a>

        <!-- Staff Tips -->
        <a href="{{ route('staff.landing') }}" class="module-card mc-tips">
            <span class="mc-icon">⭐</span>
            <div class="mc-name">Staff Tips</div>
            <div class="mc-desc">Receive tips directly to your M-Pesa without sharing your personal number. Your privacy stays protected — always.</div>
            <div class="mc-examples">
                <span class="mc-tag">Waitstaff</span>
                <span class="mc-tag">Salon</span>
                <span class="mc-tag">Delivery</span>
                <span class="mc-tag">Hotel</span>
            </div>
            <div class="mc-cta">Create Tip Page <span>›</span></div>
        </a>

        <!-- Creator Gifts — full width -->
        <a href="{{ route('creator.landing') }}" class="module-card mc-creator" style="grid-column:1/-1;flex-direction:row;align-items:center;gap:28px;padding:28px 32px">
            <span class="mc-icon" style="margin-bottom:0;font-size:48px;flex-shrink:0">🎤</span>
            <div style="flex:1">
                <div class="mc-creator-badge">✨ For Content Creators</div>
                <div class="mc-name" style="font-size:22px">Creator Gift Page</div>
                <div class="mc-desc" style="margin-bottom:16px">Set up your personal gift page in 60 seconds. Share your link — fans send you M-Pesa gifts directly, no awkward conversations, no sharing your number. Used by YouTubers, podcasters, artists, and streamers.</div>
                <div class="mc-examples">
                    <span class="mc-tag">TikTok</span>
                    <span class="mc-tag">Instagram</span>
                    <span class="mc-tag">YouTube</span>
                    <span class="mc-tag">Podcast</span>
                    <span class="mc-tag">Music</span>
                    <span class="mc-tag">pregota.com/c/yourname</span>
                </div>
            </div>
            <div class="mc-cta" style="flex-shrink:0;font-size:15px">Get Your Page <span>›</span></div>
        </a>

    </div>
</div>

<div class="trust">
    <div class="trust-item"><span>🔒</span> Phone numbers encrypted & deleted after use</div>
    <div class="trust-item"><span>⚡</span> M-Pesa STK Push — no app needed</div>
    <div class="trust-item"><span>🔐</span> Every transaction cryptographically sealed</div>
</div>

</body>
</html>
