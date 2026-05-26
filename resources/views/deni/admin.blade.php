<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tab — {{ $deni->creditorLabel() }}</title>
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh}
.nav{padding:14px 24px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.07)}
.logo{font-size:20px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.wrap{max-width:560px;margin:0 auto;padding:32px 20px 80px}
h1{font-size:20px;font-weight:900;margin-bottom:4px}
.sub{font-size:13px;color:rgba(255,255,255,.45);margin-bottom:24px}
.summary{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:24px}
.stat{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);border-radius:12px;padding:14px;text-align:center}
.stat-val{font-size:22px;font-weight:900;color:#4ADE80}
.stat-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:rgba(255,255,255,.38);margin-top:3px}
.prog-track{height:8px;background:rgba(255,255,255,.08);border-radius:999px;overflow:hidden;margin:16px 0}
.prog-fill{height:100%;background:linear-gradient(90deg,#25D366,#4ADE80);border-radius:999px}
.share-box{background:rgba(168,85,247,.05);border:1px solid rgba(168,85,247,.15);border-radius:13px;padding:14px 18px;margin-bottom:20px;display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap}
.share-url{font-family:monospace;font-size:12px;color:#c084fc;word-break:break-all}
.copy-btn{font-size:12px;padding:6px 14px;background:rgba(168,85,247,.1);border:1px solid rgba(168,85,247,.2);border-radius:7px;color:#c084fc;cursor:pointer}
.payment-row{display:flex;justify-content:space-between;align-items:center;padding:12px 16px;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);border-radius:11px;margin-bottom:8px}
.payment-row.confirmed{border-color:rgba(37,211,102,.2)}
.payment-row.failed{border-color:rgba(239,68,68,.15);opacity:.6}
.badge{display:inline-flex;padding:2px 9px;border-radius:999px;font-size:11px;font-weight:700}
.badge.confirmed{background:rgba(37,211,102,.12);color:#4ADE80}
.badge.pending{background:rgba(251,191,36,.12);color:#fbbf24}
.badge.failed{background:rgba(239,68,68,.12);color:#f87171}
.settled-banner{background:rgba(37,211,102,.08);border:1px solid rgba(37,211,102,.2);border-radius:13px;padding:16px;text-align:center;color:#4ADE80;font-weight:700;font-size:15px;margin-bottom:20px}
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
    {{-- First-visit confirmation for non-seller vibanda owners --}}
    @if(session('deni_admin_link'))
    <div style="background:rgba(251,191,36,.08);border:1px solid rgba(251,191,36,.25);border-radius:12px;padding:16px;margin-bottom:20px">
        <div style="font-size:13px;font-weight:700;color:#fbbf24;margin-bottom:6px">📌 Bookmark this page</div>
        <div style="font-size:12px;color:rgba(255,255,255,.55);margin-bottom:10px">This is your admin view. Save or bookmark this URL — it's the only way to get back to it.</div>
        <div style="font-family:monospace;font-size:11px;color:#fcd34d;word-break:break-all;background:rgba(0,0,0,.3);padding:8px 10px;border-radius:7px">{{ session('deni_admin_link') }}</div>
    </div>
    @endif

    @if(session('deni_whatsapp') || session('deni_link'))
    <div style="background:rgba(37,211,102,.05);border:1px solid rgba(37,211,102,.15);border-radius:12px;padding:14px 16px;margin-bottom:20px">
        <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(37,211,102,.6);margin-bottom:8px">Customer Payment Link</div>
        <div style="font-family:monospace;font-size:12px;color:#4ADE80;word-break:break-all;margin-bottom:10px">{{ session('deni_link') ?? url('/deni/' . $deni->debtor_token) }}</div>
        <div style="display:flex;gap:8px;flex-wrap:wrap">
            <button onclick="navigator.clipboard.writeText('{{ url('/deni/' . $deni->debtor_token) }}');this.textContent='✓ Copied!'" style="font-size:12px;padding:6px 14px;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.12);border-radius:7px;color:rgba(255,255,255,.7);cursor:pointer">📋 Copy Link</button>
            @if(session('deni_whatsapp'))
            <a href="{{ session('deni_whatsapp') }}" target="_blank" style="font-size:12px;padding:6px 14px;background:rgba(37,211,102,.15);border:1px solid rgba(37,211,102,.3);border-radius:7px;color:#4ADE80;text-decoration:none;font-weight:700">💬 Send via WhatsApp</a>
            @endif
        </div>
    </div>
    @endif

    <h1>{{ $deni->description }}</h1>
    <div class="sub">{{ $deni->creditorLabel() }}{{ $deni->due_date ? ' · Due ' . $deni->due_date->format('d M Y') : '' }}</div>

    @if($deni->status === 'settled')
        <div class="settled-banner">✅ Fully settled — KES {{ number_format($deni->original_amount) }}</div>
    @endif

    @php $pct = $deni->original_amount > 0 ? round(($deni->amount_paid / $deni->original_amount) * 100) : 0; @endphp
    <div class="summary">
        <div class="stat"><div class="stat-val">KES {{ number_format($deni->original_amount) }}</div><div class="stat-label">Total</div></div>
        <div class="stat"><div class="stat-val">KES {{ number_format($deni->amount_paid) }}</div><div class="stat-label">Paid</div></div>
        <div class="stat"><div class="stat-val {{ $deni->balance() > 0 ? 'style=color:#f87171' : '' }}">KES {{ number_format($deni->balance()) }}</div><div class="stat-label">Balance</div></div>
    </div>

    <div class="prog-track"><div class="prog-fill" style="width:{{ $pct }}%"></div></div>

    @if(!session('deni_link'))
    <div class="share-box">
        <div>
            <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(168,85,247,.6);margin-bottom:4px">Customer Payment Link</div>
            <div class="share-url">{{ url('/deni/' . $deni->debtor_token) }}</div>
        </div>
        <button class="copy-btn" onclick="navigator.clipboard.writeText('{{ url('/deni/' . $deni->debtor_token) }}');alert('Copied! Send via WhatsApp.')">📋 Copy</button>
    </div>
    @endif

    <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.38);margin-bottom:12px">Payment History</div>

    @if($deni->payments->isEmpty())
        <div style="text-align:center;padding:30px;color:rgba(255,255,255,.3)">No payments yet. Share the link above with your customer.</div>
    @else
        @foreach($deni->payments->sortByDesc('created_at') as $p)
        <div class="payment-row {{ $p->status }}">
            <div>
                <div style="font-size:15px;font-weight:800">KES {{ number_format($p->amount) }}</div>
                <div style="font-size:11px;color:rgba(255,255,255,.38);margin-top:2px">{{ $p->created_at->format('d M Y · H:i') }}</div>
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
