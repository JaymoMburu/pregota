<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Saka Keja â€” Find a House, No Agents Â· Pregota</title>
<meta name="description" content="Browse verified landlord listings in Kenya. Pay deposit safely through Pregota escrow. No agents, no lost money.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800;900&display=swap" rel="stylesheet">
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Plus Jakarta Sans',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}

/* Nav */
.nav{padding:14px 24px;display:flex;justify-content:space-between;align-items:center;position:sticky;top:0;background:rgba(11,20,26,.92);backdrop-filter:blur(12px);border-bottom:1px solid rgba(255,255,255,.06);z-index:50}
.logo{font-size:20px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.nav-right{display:flex;align-items:center;gap:10px}
.nav-landlord{font-size:12px;color:rgba(255,255,255,.45);text-decoration:none;font-weight:700;padding:7px 12px;border:1px solid rgba(255,255,255,.1);border-radius:9px;transition:.15s}
.nav-landlord:hover{color:#f59e0b;border-color:rgba(245,158,11,.3)}
.nav-list{padding:8px 16px;background:linear-gradient(135deg,#d97706,#f59e0b);border:none;border-radius:10px;color:#0B141A;font-size:13px;font-weight:800;cursor:pointer;text-decoration:none}

/* Hero */
.hero{padding:64px 24px 56px;text-align:center;max-width:640px;margin:0 auto;position:relative}
.hero-badge{display:inline-flex;align-items:center;gap:6px;padding:6px 14px;background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.25);border-radius:999px;font-size:12px;font-weight:700;color:#f59e0b;margin-bottom:20px}
.hero-title{font-size:clamp(34px,7vw,58px);font-weight:900;line-height:1.05;margin-bottom:16px;letter-spacing:-.03em}
.hero-title .green{background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.hero-title .amber{background:linear-gradient(135deg,#f59e0b,#fbbf24);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.hero-sub{font-size:16px;color:rgba(255,255,255,.55);line-height:1.7;margin-bottom:32px;max-width:480px;margin-left:auto;margin-right:auto}
.hero-btns{display:flex;gap:12px;justify-content:center;flex-wrap:wrap;margin-bottom:36px}
.btn-primary{padding:14px 28px;background:linear-gradient(135deg,#16a34a,#22c55e);color:#fff;font-size:15px;font-weight:800;border:none;border-radius:13px;cursor:pointer;text-decoration:none;display:inline-block;transition:.15s}
.btn-primary:hover{opacity:.9;transform:translateY(-1px)}
.btn-secondary{padding:14px 28px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);color:rgba(255,255,255,.8);font-size:15px;font-weight:800;border-radius:13px;cursor:pointer;text-decoration:none;display:inline-block;transition:.15s}
.btn-secondary:hover{background:rgba(255,255,255,.1)}

/* Trust pills */
.trust-row{display:flex;gap:10px;justify-content:center;flex-wrap:wrap}
.trust-pill{display:flex;align-items:center;gap:6px;padding:7px 13px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:999px;font-size:12px;font-weight:700;color:rgba(255,255,255,.6)}
.trust-pill span{font-size:14px}

/* Divider */
.section{max-width:820px;margin:0 auto;padding:0 20px}
.section-title{font-size:22px;font-weight:900;margin-bottom:6px}
.section-sub{font-size:14px;color:rgba(255,255,255,.45);margin-bottom:32px}

/* How it works */
.how-wrap{background:rgba(255,255,255,.02);border-top:1px solid rgba(255,255,255,.05);border-bottom:1px solid rgba(255,255,255,.05);padding:56px 20px}
.how-tabs{display:flex;gap:8px;margin-bottom:32px;justify-content:center}
.how-tab{padding:9px 20px;border-radius:10px;font-size:13px;font-weight:800;cursor:pointer;border:1px solid rgba(255,255,255,.1);background:rgba(255,255,255,.04);color:rgba(255,255,255,.5);transition:.15s}
.how-tab.active-seeker{background:rgba(37,211,102,.1);border-color:rgba(37,211,102,.3);color:#4ADE80}
.how-tab.active-landlord{background:rgba(245,158,11,.1);border-color:rgba(245,158,11,.3);color:#f59e0b}
.steps{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;max-width:820px;margin:0 auto}
.step{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:16px;padding:22px 18px;position:relative}
.step-num{font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.1em;margin-bottom:10px}
.step-num.green{color:#4ADE80}
.step-num.amber{color:#f59e0b}
.step-icon{font-size:28px;margin-bottom:10px}
.step-title{font-size:14px;font-weight:900;margin-bottom:6px}
.step-desc{font-size:12px;color:rgba(255,255,255,.45);line-height:1.6}

/* Why section */
.why-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:14px;margin-bottom:56px}
.why-card{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:16px;padding:22px 18px}
.why-icon{font-size:28px;margin-bottom:12px}
.why-title{font-size:14px;font-weight:900;margin-bottom:6px}
.why-desc{font-size:12px;color:rgba(255,255,255,.45);line-height:1.6}

/* Stats */
.stats-bar{display:flex;gap:0;border:1px solid rgba(255,255,255,.08);border-radius:16px;overflow:hidden;margin-bottom:56px}
.stat-item{flex:1;padding:22px 16px;text-align:center;border-right:1px solid rgba(255,255,255,.06)}
.stat-item:last-child{border-right:none}
.stat-val{font-size:28px;font-weight:900;color:#4ADE80;margin-bottom:4px}
.stat-label{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:rgba(255,255,255,.35)}
@media(max-width:460px){.stats-bar{flex-direction:column}.stat-item{border-right:none;border-bottom:1px solid rgba(255,255,255,.06)}.stat-item:last-child{border-bottom:none}}

/* Listings */
.listings-wrap{padding:0 20px 80px;max-width:820px;margin:0 auto}
.listings-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:10px}
.listings-head-title{font-size:20px;font-weight:900}
.count-badge{font-size:12px;font-weight:700;padding:4px 10px;background:rgba(74,222,128,.1);border:1px solid rgba(74,222,128,.2);border-radius:999px;color:#4ADE80}

.filters{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:14px}
.filter-chip{padding:7px 14px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:999px;font-size:12px;font-weight:700;cursor:pointer;transition:.15s;color:rgba(255,255,255,.6)}
.filter-chip.active,.filter-chip:hover{background:rgba(245,158,11,.1);border-color:rgba(245,158,11,.35);color:#f59e0b}

.search-row{display:flex;gap:8px;margin-bottom:24px}
.search-input{flex:1;padding:11px 16px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:11px;color:#fff;font-size:14px;outline:none;font-family:inherit}
.search-input:focus{border-color:rgba(245,158,11,.4)}
.search-input::placeholder{color:rgba(255,255,255,.3)}

.grid{display:grid;grid-template-columns:1fr;gap:14px}
@media(min-width:480px){.grid{grid-template-columns:1fr 1fr}}

.card{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:16px;overflow:hidden;text-decoration:none;color:#fff;transition:.2s;display:block}
.card:hover{border-color:rgba(245,158,11,.3);background:rgba(245,158,11,.03);transform:translateY(-3px);box-shadow:0 8px 32px rgba(0,0,0,.3)}
.card-img{width:100%;height:180px;object-fit:cover;background:rgba(255,255,255,.05)}
.card-img-placeholder{width:100%;height:180px;background:linear-gradient(135deg,rgba(255,255,255,.03),rgba(255,255,255,.06));display:flex;align-items:center;justify-content:center;font-size:44px;color:rgba(255,255,255,.12)}
.card-body{padding:16px}
.card-type{display:inline-flex;padding:3px 10px;background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.22);border-radius:999px;font-size:11px;font-weight:700;color:#f59e0b;margin-bottom:8px}
.card-location{font-size:15px;font-weight:800;margin-bottom:6px}
.card-rent{font-size:22px;font-weight:900;color:#4ADE80}
.card-rent-label{font-size:11px;color:rgba(255,255,255,.35);margin-left:3px}
.card-footer{display:flex;align-items:center;justify-content:space-between;margin-top:12px;padding-top:12px;border-top:1px solid rgba(255,255,255,.05)}
.card-fee{font-size:11px;color:rgba(255,255,255,.35);display:flex;align-items:center;gap:5px}
.card-fee::before{content:'ðŸ”’';font-size:10px}
.card-arrow{font-size:18px;color:#f59e0b;font-weight:900}

.empty{text-align:center;padding:60px 20px;color:rgba(255,255,255,.3);grid-column:1/-1}
.empty-icon{font-size:52px;margin-bottom:14px}
.empty-text{font-size:16px;font-weight:700;margin-bottom:6px;color:rgba(255,255,255,.5)}
.empty-sub{font-size:13px}

/* Landlord CTA */
.landlord-cta{background:linear-gradient(135deg,rgba(245,158,11,.08),rgba(245,158,11,.04));border:1px solid rgba(245,158,11,.18);border-radius:20px;padding:32px 28px;text-align:center;margin-bottom:32px}
.landlord-cta-title{font-size:20px;font-weight:900;margin-bottom:8px}
.landlord-cta-sub{font-size:14px;color:rgba(255,255,255,.5);line-height:1.7;margin-bottom:20px}
.landlord-cta-btn{display:inline-block;padding:13px 28px;background:linear-gradient(135deg,#d97706,#f59e0b);border-radius:12px;color:#0B141A;font-size:14px;font-weight:800;text-decoration:none}

/* Sticky CTA bar (mobile) */
.sticky-cta{display:none;position:fixed;bottom:0;left:0;right:0;padding:12px 16px;background:rgba(11,20,26,.95);backdrop-filter:blur(10px);border-top:1px solid rgba(255,255,255,.08);z-index:40}
.sticky-cta-inner{display:flex;gap:10px;max-width:480px;margin:0 auto}
.sticky-cta-inner a{flex:1;padding:12px;border-radius:11px;font-size:14px;font-weight:800;text-align:center;text-decoration:none}
.scta-browse{background:linear-gradient(135deg,#16a34a,#22c55e);color:#fff}
.scta-list{background:rgba(245,158,11,.12);border:1px solid rgba(245,158,11,.25);color:#f59e0b}
@media(max-width:600px){.sticky-cta{display:block}}
</style>
</head>
<body>

{{-- â”€â”€ Nav â”€â”€ --}}
<nav class="nav">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    <div class="nav-right">
        <a href="{{ route('saka-keja.landlord') }}" class="nav-landlord">Landlord Login</a>
        <a href="{{ route('saka-keja.list') }}" class="nav-list">List Property â†’</a>
    </div>
</nav>

{{-- â”€â”€ Hero â”€â”€ --}}
<div class="hero">
    <div class="hero-badge"><span>ðŸ </span> Saka Keja by Pregota</div>
    <h1 class="hero-title">
        Find a house.<br>
        <span class="green">No agents.</span><br>
        <span class="amber">No lost money.</span>
    </h1>
    <p class="hero-sub">
        Connect directly with verified landlords. Your deposit is held safely by Pregota â€” released only when you confirm you're moving in.
    </p>
    <div class="hero-btns">
        <a href="#listings" class="btn-primary">ðŸ” Browse Houses</a>
        <a href="{{ route('saka-keja.list') }}" class="btn-secondary">List My Property</a>
    </div>
    <div class="trust-row">
        <div class="trust-pill"><span>ðŸ”’</span> Deposit protected</div>
        <div class="trust-pill"><span>ðŸ“µ</span> No agents</div>
        <div class="trust-pill"><span>âœ…</span> M-Pesa verified landlords</div>
        <div class="trust-pill"><span>â†©ï¸</span> Full refund if you change mind</div>
    </div>
</div>

{{-- â”€â”€ How it works â”€â”€ --}}
<div class="how-wrap">
    <div class="section" style="text-align:center">
        <div class="section-title">How Saka Keja Works</div>
        <div class="section-sub">Simple, safe, and transparent for both seekers and landlords.</div>
        <div class="how-tabs">
            <div class="how-tab active-seeker" id="tab-seeker" onclick="showSteps('seeker')">ðŸ” I'm Looking for a House</div>
            <div class="how-tab" id="tab-landlord" onclick="showSteps('landlord')">ðŸ  I Have a Vacant House</div>
        </div>

        <div class="steps" id="steps-seeker">
            <div class="step">
                <div class="step-num green">Step 1</div>
                <div class="step-icon">ðŸ”</div>
                <div class="step-title">Browse & Choose</div>
                <div class="step-desc">Browse listings from verified landlords. Filter by area, unit type, and budget. No registration needed to browse.</div>
            </div>
            <div class="step">
                <div class="step-num green">Step 2</div>
                <div class="step-icon">ðŸ”’</div>
                <div class="step-title">Pay Deposit via M-Pesa</div>
                <div class="step-desc">Pay your deposit + rent advance through Pregota. We hold it safely â€” the landlord cannot touch it until you confirm.</div>
            </div>
            <div class="step">
                <div class="step-num green">Step 3</div>
                <div class="step-icon">ðŸ¡</div>
                <div class="step-title">View & Confirm Move-In</div>
                <div class="step-desc">Visit the house. If you like it, confirm on the app and the deposit is released to the landlord. Changed your mind? Get a full refund.</div>
            </div>
        </div>

        <div class="steps" id="steps-landlord" style="display:none">
            <div class="step">
                <div class="step-num amber">Step 1</div>
                <div class="step-icon">ðŸ“¸</div>
                <div class="step-title">List Your Property</div>
                <div class="step-desc">Create a listing with photos, rent, and deposit details. Your phone number stays private â€” seekers only see the location.</div>
            </div>
            <div class="step">
                <div class="step-num amber">Step 2</div>
                <div class="step-icon">ðŸ“²</div>
                <div class="step-title">Get Serious Seekers</div>
                <div class="step-desc">Seekers pay KES 200 to connect with you. Only serious people appear in your dashboard â€” no time wasters.</div>
            </div>
            <div class="step">
                <div class="step-num amber">Step 3</div>
                <div class="step-icon">ðŸ’°</div>
                <div class="step-title">Receive Deposit Safely</div>
                <div class="step-desc">Once your tenant confirms move-in, Pregota releases the deposit to you. Monthly rent is collected and forwarded automatically.</div>
            </div>
        </div>
    </div>
</div>

{{-- â”€â”€ Why Saka Keja â”€â”€ --}}
<div style="padding:56px 20px 0">
    <div class="section" style="text-align:center;margin-bottom:32px">
        <div class="section-title">Why Kenyans Trust Saka Keja</div>
        <div class="section-sub">We built this after experiencing the rogue agent problem first hand.</div>
    </div>
    <div class="section">
        <div class="why-grid">
            <div class="why-card">
                <div class="why-icon">ðŸ›¡ï¸</div>
                <div class="why-title">Deposit Escrow</div>
                <div class="why-desc">Your deposit is held by Pregota, not the landlord. It's released only when you physically visit and confirm you're moving in.</div>
            </div>
            <div class="why-card">
                <div class="why-icon">ðŸ“µ</div>
                <div class="why-title">No Middlemen</div>
                <div class="why-desc">We connect you directly with the verified landlord. No agent fees on top of your rent and deposit.</div>
            </div>
            <div class="why-card">
                <div class="why-icon">ðŸ“±</div>
                <div class="why-title">M-Pesa Verified</div>
                <div class="why-desc">Every landlord verifies via M-Pesa STK push. No fake listings from people who don't own the property.</div>
            </div>
            <div class="why-card">
                <div class="why-icon">â†©ï¸</div>
                <div class="why-title">Full Refund Guarantee</div>
                <div class="why-desc">Don't like the house? Cancel before move-in and get your full deposit back. Only the KES 200 escrow fee is retained.</div>
            </div>
            <div class="why-card">
                <div class="why-icon">ðŸ†</div>
                <div class="why-title">First Come First Served</div>
                <div class="why-desc">The first seeker who confirms move-in gets the house. All other depositors are automatically refunded â€” fairly and instantly.</div>
            </div>
            <div class="why-card">
                <div class="why-icon">ðŸ“‹</div>
                <div class="why-title">Legal Declaration</div>
                <div class="why-desc">Your move-in confirmation is a timestamped declaration admissible under the Kenya Evidence Act â€” protecting both parties.</div>
            </div>
        </div>
    </div>
</div>

{{-- â”€â”€ Stats â”€â”€ --}}
<div style="padding:0 20px 0">
    <div class="section">
        <div class="stats-bar">
            <div class="stat-item">
                <div class="stat-val">{{ $listings->count() }}</div>
                <div class="stat-label">Active Listings</div>
            </div>
            <div class="stat-item">
                <div class="stat-val">KES 200</div>
                <div class="stat-label">Connection Fee</div>
            </div>
            <div class="stat-item">
                <div class="stat-val">100%</div>
                <div class="stat-label">Deposit Protected</div>
            </div>
        </div>
    </div>
</div>

{{-- â”€â”€ Listings â”€â”€ --}}
<div class="listings-wrap" id="listings">
    <div class="listings-head">
        <div>
            <div class="listings-head-title">Available Houses</div>
        </div>
        <div class="count-badge" id="count-badge">{{ $listings->count() }} listing{{ $listings->count() !== 1 ? 's' : '' }}</div>
    </div>

    <div class="filters" id="type-filters">
        <div class="filter-chip active" data-type="">All Types</div>
        <div class="filter-chip" data-type="bedsitter">Bedsitter</div>
        <div class="filter-chip" data-type="1br">1 Bedroom</div>
        <div class="filter-chip" data-type="2br">2 Bedrooms</div>
        <div class="filter-chip" data-type="3br">3 Bedrooms</div>
        <div class="filter-chip" data-type="studio">Studio</div>
        <div class="filter-chip" data-type="shop">Shop</div>
    </div>

    <div class="search-row">
        <input class="search-input" type="text" id="location-search" placeholder="ðŸ”  Search by area â€” e.g. Kasarani, Westlands, Ngong...">
    </div>

    <div class="grid" id="listings-grid">
        @forelse($listings as $listing)
        <a href="{{ route('saka-keja.show', $listing->id) }}" class="card" data-type="{{ $listing->unit_type }}" data-location="{{ strtolower($listing->location) }}">
            @if($listing->firstPhoto())
                <img class="card-img" src="{{ asset('uploads/saka-keja/' . $listing->id . '/' . $listing->firstPhoto()) }}" alt="{{ $listing->location }}" loading="lazy">
            @else
                <div class="card-img-placeholder">ðŸ </div>
            @endif
            <div class="card-body">
                <div class="card-type">{{ $listing->unitLabel() }}</div>
                <div class="card-location">{{ $listing->location }}</div>
                <div>
                    <span class="card-rent">KES {{ number_format($listing->rent) }}</span>
                    <span class="card-rent-label">/month</span>
                </div>
                <div class="card-footer">
                    <span class="card-fee">Deposit held in escrow</span>
                    <span class="card-arrow">â†’</span>
                </div>
            </div>
        </a>
        @empty
        <div class="empty">
            <div class="empty-icon">ðŸ </div>
            <div class="empty-text">No listings yet</div>
            <div class="empty-sub">Be the first landlord to list a property.</div>
        </div>
        @endforelse
    </div>

    <div id="no-results" style="display:none;text-align:center;padding:40px 20px;color:rgba(255,255,255,.35)">
        <div style="font-size:36px;margin-bottom:10px">ðŸ”</div>
        <div style="font-size:15px;font-weight:700;margin-bottom:5px;color:rgba(255,255,255,.5)">No matches found</div>
        <div style="font-size:13px">Try a different area or remove the filter.</div>
    </div>

    {{-- Landlord CTA --}}
    <div class="landlord-cta" style="margin-top:40px">
        <div class="landlord-cta-title">ðŸ  Got a Vacant House?</div>
        <div class="landlord-cta-sub">
            List it on Saka Keja and connect with verified, serious seekers.<br>
            Your number stays private. You get paid through Pregota â€” safely.
        </div>
        <a href="{{ route('saka-keja.list') }}" class="landlord-cta-btn">List My Property â€” KES 200 â†’</a>
    </div>
</div>

{{-- Mobile sticky CTA --}}
<div class="sticky-cta">
    <div class="sticky-cta-inner">
        <a href="#listings" class="scta-browse">ðŸ” Browse Houses</a>
        <a href="{{ route('saka-keja.list') }}" class="scta-list">List Property â†’</a>
    </div>
</div>

<script>
// â”€â”€ How it works tabs
function showSteps(who) {
    document.getElementById('steps-seeker').style.display   = who === 'seeker'   ? 'grid' : 'none';
    document.getElementById('steps-landlord').style.display = who === 'landlord' ? 'grid' : 'none';
    document.getElementById('tab-seeker').className   = 'how-tab' + (who === 'seeker'   ? ' active-seeker' : '');
    document.getElementById('tab-landlord').className = 'how-tab' + (who === 'landlord' ? ' active-landlord' : '');
}

// â”€â”€ Filter & search
const chips = document.querySelectorAll('[data-type]');
let activeType = '';
let searchTerm = '';

chips.forEach(chip => {
    chip.addEventListener('click', () => {
        chips.forEach(c => c.classList.remove('active'));
        chip.classList.add('active');
        activeType = chip.dataset.type;
        filterListings();
    });
});

document.getElementById('location-search').addEventListener('input', e => {
    searchTerm = e.target.value.toLowerCase().trim();
    filterListings();
});

function filterListings() {
    const cards = document.querySelectorAll('.card');
    let visible = 0;
    cards.forEach(card => {
        const typeMatch = !activeType || card.dataset.type === activeType;
        const locMatch  = !searchTerm || card.dataset.location.includes(searchTerm);
        const show      = typeMatch && locMatch;
        card.style.display = show ? '' : 'none';
        if (show) visible++;
    });
    document.getElementById('count-badge').textContent = visible + ' listing' + (visible !== 1 ? 's' : '');
    document.getElementById('no-results').style.display = visible === 0 && cards.length > 0 ? 'block' : 'none';
}

// Smooth scroll for nav anchor
document.querySelectorAll('a[href="#listings"]').forEach(a => {
    a.addEventListener('click', e => {
        e.preventDefault();
        document.getElementById('listings').scrollIntoView({behavior:'smooth',block:'start'});
    });
});
</script>
</body>
</html>

