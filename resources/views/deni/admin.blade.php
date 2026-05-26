<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tab — {{ $deni->creditorLabel() }} · Pregota</title>
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh}
.nav{padding:14px 24px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.07);position:sticky;top:0;background:#0B141A;z-index:10}
.logo{font-size:20px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.wrap{max-width:580px;margin:0 auto;padding:28px 20px 80px}

/* Header */
.tab-header{margin-bottom:24px}
.tab-label{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.09em;color:rgba(255,255,255,.35);margin-bottom:6px}
.tab-title{font-size:22px;font-weight:900;margin-bottom:3px}
.tab-meta{font-size:13px;color:rgba(255,255,255,.45)}

/* Stats */
.stats{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:20px}
.stat{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);border-radius:12px;padding:14px;text-align:center}
.stat-val{font-size:20px;font-weight:900}
.stat-val.red{color:#f87171}
.stat-val.green{color:#4ADE80}
.stat-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:rgba(255,255,255,.35);margin-top:3px}

/* Progress */
.prog-wrap{margin-bottom:24px}
.prog-track{height:8px;background:rgba(255,255,255,.08);border-radius:999px;overflow:hidden;margin-bottom:6px}
.prog-fill{height:100%;background:linear-gradient(90deg,#ef4444,#fbbf24);border-radius:999px;transition:.4s}
.prog-labels{display:flex;justify-content:space-between;font-size:11px;color:rgba(255,255,255,.35)}

/* Alert banners */
.banner{border-radius:11px;padding:13px 16px;margin-bottom:16px;font-size:13px}
.banner-yellow{background:rgba(251,191,36,.07);border:1px solid rgba(251,191,36,.2);color:#fcd34d}
.banner-green{background:rgba(37,211,102,.06);border:1px solid rgba(37,211,102,.18);color:#4ADE80}
.banner-settled{background:rgba(37,211,102,.08);border:1px solid rgba(37,211,102,.2);border-radius:13px;padding:16px;text-align:center;font-weight:700;font-size:15px;color:#4ADE80;margin-bottom:20px}

/* Section headings */
.section-head{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.35);margin-bottom:12px;display:flex;align-items:center;gap:10px}
.section-head::after{content:'';flex:1;height:1px;background:rgba(255,255,255,.06)}

/* Add charge form */
.charge-form{background:rgba(239,68,68,.05);border:1px solid rgba(239,68,68,.18);border-radius:14px;padding:18px;margin-bottom:16px}
.charge-form-title{font-size:13px;font-weight:700;color:#f87171;margin-bottom:14px}
.charge-row{display:flex;gap:10px;flex-wrap:wrap}
.charge-desc{flex:1;min-width:180px;padding:10px 12px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:9px;color:#fff;font-size:14px;outline:none;font-family:inherit}
.charge-desc:focus{border-color:rgba(239,68,68,.4)}
.charge-amount{width:110px;padding:10px 12px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:9px;color:#fff;font-size:14px;outline:none;font-family:inherit}
.charge-amount:focus{border-color:rgba(239,68,68,.4)}
.charge-btn{padding:10px 18px;background:linear-gradient(135deg,#dc2626,#ef4444);border:none;border-radius:9px;color:#fff;font-size:13px;font-weight:700;cursor:pointer;white-space:nowrap}

/* Items list */
.item-row{display:flex;justify-content:space-between;align-items:center;padding:11px 14px;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);border-radius:9px;margin-bottom:6px}
.item-desc{font-size:13px;color:rgba(255,255,255,.8)}
.item-date{font-size:11px;color:rgba(255,255,255,.3);margin-top:2px}
.item-amount{font-size:14px;font-weight:800;color:#f87171}

/* Share box */
.share-box{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.08);border-radius:13px;padding:14px 16px;margin-bottom:24px}
.share-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.09em;color:rgba(255,255,255,.3);margin-bottom:8px}
.share-url{font-family:monospace;font-size:12px;color:#c084fc;word-break:break-all;margin-bottom:10px}
.share-btns{display:flex;gap:8px;flex-wrap:wrap}
.share-btn{font-size:12px;padding:6px 14px;border-radius:7px;cursor:pointer;font-weight:600;text-decoration:none;border:none}
.share-btn-copy{background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.12);color:rgba(255,255,255,.7)}
.share-btn-wa{background:rgba(37,211,102,.12);border:1px solid rgba(37,211,102,.25);color:#4ADE80}

/* Payment history */
.payment-row{display:flex;justify-content:space-between;align-items:center;padding:11px 14px;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);border-radius:9px;margin-bottom:6px}
.payment-row.confirmed{border-color:rgba(37,211,102,.18)}
.payment-row.failed{border-color:rgba(239,68,68,.12);opacity:.55}
.badge{display:inline-flex;padding:2px 9px;border-radius:999px;font-size:11px;font-weight:700}
.badge.confirmed{background:rgba(37,211,102,.12);color:#4ADE80}
.badge.pending{background:rgba(251,191,36,.12);color:#fbbf24}
.badge.failed{background:rgba(239,68,68,.12);color:#f87171}
</style>
</head>
<body>

<nav class="nav">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    @if(session()->has('seller_id'))
        <a href="{{ route('seller.dashboard') }}" style="font-size:13px;color:rgba(255,255,255,.4);text-decoration:none">← Dashboard</a>
    @else
        <a href="{{ route('deni.create') }}" style="font-size:13px;color:rgba(255,255,255,.4);text-decoration:none">+ New Deni</a>
    @endif
</nav>

<div class="wrap">

    {{-- Bookmark reminder for non-seller first visit --}}
    @if(session('deni_admin_link'))
    <div class="banner banner-yellow" style="margin-bottom:20px">
        <div style="font-weight:700;margin-bottom:5px">📌 Bookmark this page</div>
        <div style="font-size:12px;color:rgba(255,255,255,.55);margin-bottom:8px">This is your admin view. Save or bookmark this URL — it's the only way to get back.</div>
        <div style="font-family:monospace;font-size:11px;color:#fcd34d;word-break:break-all;background:rgba(0,0,0,.3);padding:7px 10px;border-radius:7px">{{ session('deni_admin_link') }}</div>
    </div>
    @endif

    @if(session('charge_added'))
    <div class="banner banner-green" style="margin-bottom:16px">✓ Charge added — tab total updated.</div>
    @endif

    {{-- Tab header --}}
    <div class="tab-header">
        <div class="tab-label">🧾 Customer Tab</div>
        <div class="tab-title">{{ $deni->description }}</div>
        <div class="tab-meta">{{ $deni->creditorLabel() }}{{ $deni->due_date ? ' · Due ' . $deni->due_date->format('d M Y') : '' }}</div>
    </div>

    {{-- Stats --}}
    <div class="stats">
        <div class="stat">
            <div class="stat-val">KES {{ number_format($deni->original_amount) }}</div>
            <div class="stat-label">Total Owed</div>
        </div>
        <div class="stat">
            <div class="stat-val green">KES {{ number_format($deni->amount_paid) }}</div>
            <div class="stat-label">Paid</div>
        </div>
        <div class="stat">
            <div class="stat-val {{ $deni->balance() > 0 ? 'red' : 'green' }}">KES {{ number_format($deni->balance()) }}</div>
            <div class="stat-label">Balance</div>
        </div>
    </div>

    @php $pct = $deni->original_amount > 0 ? round(($deni->amount_paid / $deni->original_amount) * 100) : 0; @endphp
    <div class="prog-wrap">
        <div class="prog-track"><div class="prog-fill" style="width:{{ $pct }}%"></div></div>
        <div class="prog-labels"><span>{{ $pct }}% paid</span><span>{{ 100 - $pct }}% remaining</span></div>
    </div>

    @if($deni->status === 'settled')
        <div class="banner-settled">✅ Fully settled</div>
    @endif

    {{-- Customer payment link --}}
    <div class="share-box">
        <div class="share-label">Customer Payment Link</div>
        @php $payUrl = url('/deni/' . $deni->debtor_token); @endphp
        <div class="share-url">{{ $payUrl }}</div>
        <div class="share-btns">
            <button class="share-btn share-btn-copy" onclick="navigator.clipboard.writeText('{{ $payUrl }}');this.textContent='✓ Copied!'">📋 Copy Link</button>
            @php
                $waMsg = $deni->creditorLabel() . ' has recorded a deni of KES ' . number_format($deni->original_amount) . ' for: ' . $deni->description . '. Pay via M-Pesa: ' . $payUrl;
            @endphp
            <a href="https://wa.me/?text={{ rawurlencode($waMsg) }}" target="_blank" class="share-btn share-btn-wa">💬 Send via WhatsApp</a>
        </div>
    </div>

    {{-- Add Charge --}}
    @if($deni->status !== 'settled')
    <div class="section-head">Charges</div>

    <div class="charge-form">
        <div class="charge-form-title">+ Add a Charge</div>
        <form method="POST" action="{{ route('deni.charge', $deni->admin_token) }}">
            @csrf
            <div class="charge-row">
                <input class="charge-desc" type="text" name="description" placeholder="What for? e.g. Ugali + fish, 4 Jun" maxlength="200" required>
                <input class="charge-amount" type="number" name="amount" placeholder="KES" min="1" max="500000" required>
                <button class="charge-btn" type="submit">+ Add</button>
            </div>
        </form>
    </div>
    @else
    <div class="section-head">Charges</div>
    @endif

    {{-- Items list --}}
    @if($deni->items->isEmpty())
        <div style="text-align:center;padding:20px 0;color:rgba(255,255,255,.3);font-size:13px">No itemised charges — tab was created as a single amount.</div>
    @else
        @foreach($deni->items->sortByDesc('created_at') as $item)
        <div class="item-row">
            <div>
                <div class="item-desc">{{ $item->description }}</div>
                <div class="item-date">{{ $item->created_at->format('d M Y · H:i') }}</div>
            </div>
            <div class="item-amount">KES {{ number_format($item->amount) }}</div>
        </div>
        @endforeach
    @endif

    {{-- Payment history --}}
    <div class="section-head" style="margin-top:28px">Payments Received</div>

    @if($deni->payments->where('status', '!=', 'failed')->isEmpty())
        <div style="text-align:center;padding:20px 0;color:rgba(255,255,255,.3);font-size:13px">No payments yet. Share the link above with your customer.</div>
    @else
        @foreach($deni->payments->sortByDesc('created_at') as $p)
        <div class="payment-row {{ $p->status }}">
            <div>
                <div style="font-size:15px;font-weight:800">KES {{ number_format($p->amount) }}</div>
                <div style="font-size:11px;color:rgba(255,255,255,.35);margin-top:2px">{{ $p->created_at->format('d M Y · H:i') }}</div>
            </div>
            <div style="display:flex;align-items:center;gap:10px">
                @if($p->receipt_number)
                    <a href="{{ route('receipt.show', $p->receipt_number) }}" target="_blank" style="font-size:11px;color:#a78bfa;font-family:monospace;text-decoration:none">{{ $p->receipt_number }}</a>
                @endif
                <span class="badge {{ $p->status }}">{{ $p->status }}</span>
            </div>
        </div>
        @endforeach
    @endif

</div>
</body>
</html>
