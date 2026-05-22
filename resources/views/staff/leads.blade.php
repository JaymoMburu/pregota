<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Customer Leads — Pregota</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0f0f1a;color:#fff;min-height:100vh}
.topbar{padding:14px 20px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.07);background:#0f0f1a;position:sticky;top:0;z-index:10}
.logo{font-size:18px;font-weight:900;background:linear-gradient(135deg,#c084fc,#f472b6);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.back{font-size:13px;color:rgba(255,255,255,.4);text-decoration:none}
.page{max-width:600px;margin:0 auto;padding:24px 20px 60px}
.page-title{font-size:20px;font-weight:900;margin-bottom:4px}
.page-sub{font-size:13px;color:rgba(255,255,255,.4);margin-bottom:24px;line-height:1.6}

.summary-row{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:24px}
.sum-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:12px;padding:16px;text-align:center}
.sum-num{font-size:28px;font-weight:900;color:#fbbf24}
.sum-label{font-size:11px;color:rgba(255,255,255,.4);margin-top:3px}

.lead-item{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);border-radius:12px;padding:14px 16px;margin-bottom:10px}
.lead-phone{font-size:15px;font-weight:700;font-family:monospace;letter-spacing:.05em;color:rgba(255,255,255,.7)}
.lead-meta{font-size:11px;color:rgba(255,255,255,.35);margin-top:3px}
.lead-venue{font-size:11px;color:rgba(255,255,255,.45);margin-top:2px}

.notice{background:rgba(124,58,237,.08);border:1px solid rgba(124,58,237,.2);border-radius:12px;padding:16px 18px;margin-bottom:24px}
.notice p{font-size:13px;color:rgba(255,255,255,.55);line-height:1.7}
.notice strong{color:#c084fc}

.empty{text-align:center;padding:48px 20px;color:rgba(255,255,255,.3);font-size:14px}
.empty-icon{font-size:40px;margin-bottom:12px}
</style>
</head>
<body>

<div class="topbar">
    <a href="{{ route('staff.dashboard') }}" class="back">← Dashboard</a>
    <a href="{{ route('home') }}" class="logo">Pregota</a>
</div>

<div class="page">
    <div class="page-title">Customer Opt-Ins</div>
    <div class="page-sub">Customers who agreed to hear from your restaurant after paying.</div>

    <div class="notice">
        <p>These customers <strong>voluntarily chose</strong> to share their contact with the restaurant — Pregota asked them on the restaurant's behalf after they paid. Full numbers are accessible only to the <strong>restaurant manager</strong> via the Business Dashboard, not to individual staff.</p>
    </div>

    <div class="summary-row">
        <div class="sum-card">
            <div class="sum-num">{{ $optIns->count() }}</div>
            <div class="sum-label">Total opt-ins</div>
        </div>
        <div class="sum-card">
            <div class="sum-num">{{ $optIns->where('created_at', '>=', today())->count() }}</div>
            <div class="sum-label">Today</div>
        </div>
    </div>

    @if($optIns->count())
    @foreach($optIns as $optIn)
    <div class="lead-item">
        <div class="lead-phone">{{ substr($optIn->getPhone(), 0, 4) }}••• ••••</div>
        <div class="lead-venue">{{ $optIn->billSplit->business_name }}{{ $optIn->billSplit->label ? ' · ' . $optIn->billSplit->label : '' }}</div>
        <div class="lead-meta">{{ $optIn->created_at->format('d M Y · g:i A') }}</div>
    </div>
    @endforeach
    @else
    <div class="empty">
        <div class="empty-icon">📱</div>
        <div>No opt-ins yet.</div>
        <div style="font-size:12px;margin-top:8px;color:rgba(255,255,255,.25)">Customers are offered to sign up after paying.</div>
    </div>
    @endif
</div>
</body>
</html>
