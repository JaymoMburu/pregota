<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Saka Keja — Find Your House · Pregota</title>
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh}
.nav{padding:14px 20px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.07);position:sticky;top:0;background:#0B141A;z-index:10}
.logo{font-size:20px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.brand{font-size:14px;font-weight:800;color:#f59e0b}
.list-btn{padding:9px 16px;background:linear-gradient(135deg,#d97706,#f59e0b);border:none;border-radius:10px;color:#0B141A;font-size:13px;font-weight:800;cursor:pointer;text-decoration:none}
.wrap{max-width:680px;margin:0 auto;padding:24px 16px 80px}
.hero{margin-bottom:28px}
.hero-title{font-size:26px;font-weight:900;line-height:1.2;margin-bottom:8px}
.hero-title span{background:linear-gradient(135deg,#f59e0b,#fbbf24);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.hero-sub{font-size:14px;color:rgba(255,255,255,.5);line-height:1.6}

.filters{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:20px}
.filter-chip{padding:7px 14px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:999px;font-size:12px;font-weight:700;cursor:pointer;transition:.15s;color:rgba(255,255,255,.7)}
.filter-chip.active,.filter-chip:hover{background:rgba(245,158,11,.12);border-color:rgba(245,158,11,.4);color:#f59e0b}

.search-row{display:flex;gap:8px;margin-bottom:20px}
.search-input{flex:1;padding:10px 14px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:10px;color:#fff;font-size:14px;outline:none;font-family:inherit}
.search-input:focus{border-color:rgba(245,158,11,.4)}
.search-input::placeholder{color:rgba(255,255,255,.3)}

.grid{display:grid;grid-template-columns:1fr;gap:14px}
@media(min-width:480px){.grid{grid-template-columns:1fr 1fr}}

.card{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:16px;overflow:hidden;text-decoration:none;color:#fff;transition:.15s;display:block}
.card:hover{border-color:rgba(245,158,11,.25);background:rgba(245,158,11,.03);transform:translateY(-2px)}
.card-img{width:100%;height:170px;object-fit:cover;background:rgba(255,255,255,.05)}
.card-img-placeholder{width:100%;height:170px;background:rgba(255,255,255,.04);display:flex;align-items:center;justify-content:center;font-size:40px;color:rgba(255,255,255,.15)}
.card-body{padding:14px}
.card-type{display:inline-flex;padding:3px 10px;background:rgba(245,158,11,.12);border:1px solid rgba(245,158,11,.25);border-radius:999px;font-size:11px;font-weight:700;color:#f59e0b;margin-bottom:8px}
.card-location{font-size:15px;font-weight:800;margin-bottom:4px}
.card-rent{font-size:20px;font-weight:900;color:#4ADE80}
.card-rent-label{font-size:11px;color:rgba(255,255,255,.35);margin-left:4px}
.card-connect{display:flex;align-items:center;justify-content:space-between;margin-top:10px;padding-top:10px;border-top:1px solid rgba(255,255,255,.06)}
.card-fee{font-size:11px;color:rgba(255,255,255,.35)}
.card-arrow{font-size:16px;color:#f59e0b}

.empty{text-align:center;padding:60px 20px;color:rgba(255,255,255,.3)}
.empty-icon{font-size:48px;margin-bottom:12px}
.empty-text{font-size:15px;font-weight:700;margin-bottom:6px;color:rgba(255,255,255,.5)}
.empty-sub{font-size:13px}

.list-cta{background:rgba(245,158,11,.06);border:1px solid rgba(245,158,11,.15);border-radius:16px;padding:20px;text-align:center;margin-bottom:24px}
.list-cta-title{font-size:16px;font-weight:800;margin-bottom:6px}
.list-cta-sub{font-size:13px;color:rgba(255,255,255,.45);margin-bottom:14px}
.list-cta-btn{display:inline-block;padding:12px 24px;background:linear-gradient(135deg,#d97706,#f59e0b);border-radius:11px;color:#0B141A;font-size:14px;font-weight:800;text-decoration:none}
</style>
</head>
<body>
<nav class="nav">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    <span class="brand">🏠 Saka Keja</span>
    <a href="{{ route('saka-keja.list') }}" class="list-btn">List Yours →</a>
</nav>

<div class="wrap">
    <div class="hero">
        <div class="hero-title">Find a house.<br><span>No agents. No BS.</span></div>
        <div class="hero-sub">Browse real listings from verified landlords. Pay KES 200 to connect directly — no extra fees, no middlemen.</div>
    </div>

    <div class="list-cta">
        <div class="list-cta-title">Got a vacant house?</div>
        <div class="list-cta-sub">List it on Saka Keja and connect directly with serious seekers.</div>
        <a href="{{ route('saka-keja.list') }}" class="list-cta-btn">List My Property — KES 200 →</a>
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
        <input class="search-input" type="text" id="location-search" placeholder="Search by area e.g. Kasarani, Westlands...">
    </div>

    <div class="grid" id="listings-grid">
        @forelse($listings as $listing)
        <a href="{{ route('saka-keja.show', $listing->id) }}" class="card" data-type="{{ $listing->unit_type }}" data-location="{{ strtolower($listing->location) }}">
            @if($listing->firstPhoto())
                <img class="card-img" src="{{ asset('uploads/saka-keja/' . $listing->id . '/' . $listing->firstPhoto()) }}" alt="{{ $listing->location }}" loading="lazy">
            @else
                <div class="card-img-placeholder">🏠</div>
            @endif
            <div class="card-body">
                <div class="card-type">{{ $listing->unitLabel() }}</div>
                <div class="card-location">{{ $listing->location }}</div>
                <div>
                    <span class="card-rent">KES {{ number_format($listing->rent) }}</span>
                    <span class="card-rent-label">/month</span>
                </div>
                <div class="card-connect">
                    <span class="card-fee">Connect for KES 200</span>
                    <span class="card-arrow">→</span>
                </div>
            </div>
        </a>
        @empty
        <div class="empty" style="grid-column:1/-1">
            <div class="empty-icon">🏠</div>
            <div class="empty-text">No listings yet</div>
            <div class="empty-sub">Be the first to list your property.</div>
        </div>
        @endforelse
    </div>
</div>

<script>
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
    document.querySelectorAll('.card').forEach(card => {
        const typeMatch = !activeType || card.dataset.type === activeType;
        const locMatch  = !searchTerm || card.dataset.location.includes(searchTerm);
        card.style.display = (typeMatch && locMatch) ? '' : 'none';
    });
}
</script>
</body>
</html>
