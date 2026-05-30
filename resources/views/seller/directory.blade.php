<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pay with Pregota â€” Find Sellers</title>
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}input,textarea,select,button{font-family:inherit;font-size:inherit}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh}
.nav{padding:14px 24px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.07)}
.logo{font-size:20px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.nav-right{display:flex;gap:12px;align-items:center}
.nav-link{font-size:13px;color:rgba(255,255,255,.6);text-decoration:none}
.nav-link:hover{color:#fff}
.wrap{max-width:860px;margin:0 auto;padding:32px 20px 80px}
h1{font-size:28px;font-weight:900;margin-bottom:6px}
.sub{font-size:14px;color:rgba(255,255,255,.55);margin-bottom:28px}

/* Search */
.search-row{display:flex;gap:10px;margin-bottom:24px}
.search-input{flex:1;padding:12px 16px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);border-radius:12px;color:#fff;font-size:14px;outline:none}
.search-input:focus{border-color:rgba(37,211,102,.4)}
.search-btn{padding:12px 20px;background:rgba(37,211,102,.15);border:1px solid rgba(37,211,102,.3);border-radius:12px;color:#4ADE80;font-size:14px;font-weight:700;cursor:pointer}
.search-btn:hover{background:rgba(37,211,102,.25)}

/* Category tabs */
.cats{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:28px}
.cat-pill{padding:7px 14px;border-radius:999px;font-size:12px;font-weight:700;text-decoration:none;border:1px solid rgba(255,255,255,.12);color:rgba(255,255,255,.6);transition:.15s}
.cat-pill:hover{border-color:rgba(37,211,102,.3);color:#4ADE80}
.cat-pill.active{background:rgba(37,211,102,.12);border-color:rgba(37,211,102,.4);color:#4ADE80}

/* Grid */
.grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:16px}
.card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:16px;padding:20px;text-decoration:none;color:#fff;display:flex;flex-direction:column;gap:8px;transition:.15s}
.card:hover{border-color:rgba(37,211,102,.3);background:rgba(37,211,102,.05);transform:translateY(-2px)}
.card-emoji{font-size:28px;line-height:1;margin-bottom:4px}
.card-name{font-size:16px;font-weight:800}
.card-handle{font-size:12px;font-family:monospace;color:rgba(255,255,255,.45)}
.card-desc{font-size:12px;color:rgba(255,255,255,.55);line-height:1.55;flex:1}
.card-footer{display:flex;justify-content:space-between;align-items:center;margin-top:4px}
.card-count{font-size:11px;color:rgba(255,255,255,.4)}
.card-count strong{color:#4ADE80}
.card-badge{font-size:11px;font-weight:700;padding:3px 9px;border-radius:999px;background:rgba(37,211,102,.1);color:#4ADE80;border:1px solid rgba(37,211,102,.2)}
.empty{text-align:center;padding:60px 20px;color:rgba(255,255,255,.4)}
.empty h2{font-size:18px;margin-bottom:8px}

/* Me link */
.me-banner{background:rgba(167,139,250,.07);border:1px solid rgba(167,139,250,.2);border-radius:14px;padding:16px 20px;margin-bottom:28px;display:flex;justify-content:space-between;align-items:center;gap:16px}
.me-banner-text{font-size:13px;color:rgba(255,255,255,.7);line-height:1.55}
.me-banner-text strong{color:#fff}
.me-btn{padding:9px 18px;background:rgba(167,139,250,.15);border:1px solid rgba(167,139,250,.3);border-radius:10px;color:#a78bfa;font-size:13px;font-weight:700;text-decoration:none;white-space:nowrap}
.me-btn:hover{background:rgba(167,139,250,.25)}
</style>
</head>
<body>
<nav class="nav">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    <div class="nav-right">
        <a href="{{ route('buyer.me') }}" class="nav-link">My Receipts</a>
        <a href="{{ route('seller.register') }}" class="nav-link">Add My Business â†’</a>
    </div>
</nav>

<div class="wrap">
    <h1>Pay with Pregota</h1>
    <div class="sub">Browse all sellers accepting M-Pesa via Pregota</div>

    <div class="me-banner">
        <div class="me-banner-text">
            <strong>Track your spending & get receipts</strong><br>
            All your Pregota payments in one place â€” printable for KRA expense claims.
        </div>
        <a href="{{ route('buyer.me') }}" class="me-btn">My Receipts â†’</a>
    </div>

    <form method="GET" action="{{ route('seller.directory') }}" class="search-row">
        @if($category)
        <input type="hidden" name="category" value="{{ $category }}">
        @endif
        <input type="text" name="q" class="search-input" placeholder="Search by name or handleâ€¦" value="{{ $search }}">
        <button type="submit" class="search-btn">Search</button>
    </form>

    <div class="cats">
        <a href="{{ route('seller.directory', $search ? ['q' => $search] : []) }}"
           class="cat-pill {{ ! $category ? 'active' : '' }}">All</a>
        @foreach($categories as $key => $cat)
        <a href="{{ route('seller.directory', array_filter(['category' => $key, 'q' => $search])) }}"
           class="cat-pill {{ $category === $key ? 'active' : '' }}">
            {{ $cat['emoji'] }} {{ $cat['label'] }}
        </a>
        @endforeach
    </div>

    @if($sellers->isEmpty())
    <div class="empty">
        <h2>No sellers found</h2>
        <p>Try a different search or category.</p>
    </div>
    @else
    <div class="grid">
        @foreach($sellers as $seller)
        @php
            $cat = $categories[$seller->category] ?? ['emoji' => 'ðŸª', 'label' => ucfirst($seller->category ?? 'Other')];
        @endphp
        <a href="{{ route('seller.public', $seller->handle) }}" class="card">
            <div class="card-emoji">{{ $cat['emoji'] }}</div>
            <div class="card-name">{{ $seller->business_name }}</div>
            <div class="card-handle">pregota.com/pay/{{ $seller->handle }}</div>
            @if($seller->description)
            <div class="card-desc">{{ Str::limit($seller->description, 80) }}</div>
            @endif
            <div class="card-footer">
                <div class="card-count">
                    @if($seller->payment_count > 0)
                    <strong>{{ number_format($seller->payment_count) }}</strong> payments
                    @else
                    New on Pregota
                    @endif
                </div>
                @if($seller->stamps_required)
                <div class="card-badge">ðŸŽŸ Stamp Card</div>
                @endif
            </div>
        </a>
        @endforeach
    </div>
    @endif
</div>

</body>
</html>

