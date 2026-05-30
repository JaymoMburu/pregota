<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $plan->name }} Subscribers â€” Pregota</title>
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}input,textarea,select,button{font-family:inherit;font-size:inherit}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh}
.nav{padding:14px 24px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.07)}
.logo{font-size:20px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.wrap{max-width:640px;margin:0 auto;padding:32px 20px 80px}
h1{font-size:22px;font-weight:900;margin-bottom:4px}
.sub{font-size:13px;color:rgba(255,255,255,.45);margin-bottom:24px}
.stats{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:24px}
.stat{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);border-radius:12px;padding:14px;text-align:center}
.stat-val{font-size:22px;font-weight:900;color:#4ADE80}
.stat-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:rgba(255,255,255,.38);margin-top:3px}
.row{display:flex;justify-content:space-between;align-items:center;padding:12px 16px;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);border-radius:11px;margin-bottom:8px}
.row.active{border-color:rgba(37,211,102,.2)}
.row.overdue{border-color:rgba(239,68,68,.2);background:rgba(239,68,68,.03)}
.badge{display:inline-flex;padding:2px 9px;border-radius:999px;font-size:11px;font-weight:700}
.badge.active{background:rgba(37,211,102,.12);color:#4ADE80}
.badge.overdue{background:rgba(239,68,68,.12);color:#f87171}
.badge.cancelled{background:rgba(255,255,255,.06);color:rgba(255,255,255,.45)}
.copy-btn{font-size:11px;padding:4px 10px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:6px;color:rgba(255,255,255,.5);cursor:pointer}
.copy-btn:hover{background:rgba(255,255,255,.1);color:#fff}
.date{font-size:11px;color:rgba(255,255,255,.35);margin-top:2px}
.share-box{background:rgba(168,85,247,.05);border:1px solid rgba(168,85,247,.15);border-radius:13px;padding:14px 18px;margin-bottom:20px;display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap}
.share-url{font-family:monospace;font-size:12px;color:#c084fc;word-break:break-all}
</style>
</head>
<body>
<nav class="nav">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    <a href="{{ route('seller.dashboard') }}" style="font-size:13px;color:rgba(255,255,255,.4);text-decoration:none">â† Dashboard</a>
</nav>
<div class="wrap">
    <h1>{{ $plan->name }}</h1>
    <div class="sub">KES {{ number_format($plan->amount) }} / {{ $plan->frequencyLabel() }}</div>

    <div class="share-box">
        <div>
            <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(168,85,247,.6);margin-bottom:4px">Subscribe Link</div>
            <div class="share-url">{{ url('/subscribe/' . $plan->id) }}</div>
        </div>
        <button class="copy-btn" onclick="navigator.clipboard.writeText('{{ url('/subscribe/' . $plan->id) }}');alert('Copied!')">ðŸ“‹ Copy</button>
    </div>

    @php
        $active   = $subscribers->where('status','active')->count();
        $overdue  = $subscribers->where('status','overdue')->count();
        $revenue  = $subscribers->where('status','active')->sum(fn($s) => $plan->amount);
    @endphp
    <div class="stats">
        <div class="stat"><div class="stat-val">{{ $active }}</div><div class="stat-label">Active</div></div>
        <div class="stat"><div class="stat-val">{{ $overdue }}</div><div class="stat-label">Overdue</div></div>
        <div class="stat"><div class="stat-val">KES {{ number_format($revenue) }}</div><div class="stat-label">MRR</div></div>
    </div>

    @if($subscribers->isEmpty())
        <div style="text-align:center;padding:40px;color:rgba(255,255,255,.3)">No subscribers yet. Share the subscribe link to get started.</div>
    @else
        @foreach($subscribers as $s)
        <div class="row {{ $s->status }}">
            <div>
                <div style="display:flex;align-items:center;gap:8px">
                    <span class="badge {{ $s->status }}">{{ $s->status }}</span>
                    @if($s->next_due_at)<span style="font-size:11px;color:rgba(255,255,255,.4)">Next due: {{ $s->next_due_at->format('d M Y') }}</span>@endif
                </div>
                @if($s->last_paid_at)<div class="date">Last paid: {{ $s->last_paid_at->format('d M Y') }}</div>@endif
            </div>
            <button class="copy-btn" onclick="navigator.clipboard.writeText('{{ url('/subscription/reminder/' . $s->reminder_token) }}');alert('Reminder link copied! Send via WhatsApp.')">Copy Reminder</button>
        </div>
        @endforeach
        <div style="margin-top:16px">{{ $subscribers->links() }}</div>
    @endif
</div>
</body>
</html>

