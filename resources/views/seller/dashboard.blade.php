<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $payLink->business_name }} — Pay Link Dashboard</title>
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh}
.nav{padding:14px 24px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.07);background:rgba(0,0,0,.3)}
.logo{font-size:18px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.nav-right{display:flex;gap:12px;align-items:center}
.main{padding:24px;max-width:760px;margin:0 auto}

.welcome{margin-bottom:28px}
.welcome h1{font-size:22px;font-weight:900;margin-bottom:4px}
.welcome p{font-size:13px;color:rgba(255,255,255,.6)}

.link-box{background:linear-gradient(135deg,rgba(37,211,102,.12),rgba(26,170,82,.06));border:1px solid rgba(37,211,102,.25);border-radius:18px;padding:24px;margin-bottom:24px}
.link-label{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#25D366;margin-bottom:10px}
.link-url{font-family:monospace;font-size:16px;font-weight:900;color:#fff;background:rgba(0,0,0,.3);border-radius:10px;padding:12px 16px;display:flex;align-items:center;justify-content:space-between;gap:12px}
.copy-btn{background:rgba(37,211,102,.15);border:1px solid rgba(37,211,102,.3);color:#25D366;font-size:12px;font-weight:700;padding:6px 14px;border-radius:8px;cursor:pointer;white-space:nowrap;transition:.15s}
.copy-btn:hover{background:rgba(37,211,102,.25)}

.kpis{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:24px}
@media(max-width:480px){.kpis{grid-template-columns:1fr 1fr}}
.kpi{background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:16px}
.kpi-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.65);margin-bottom:6px}
.kpi-val{font-size:22px;font-weight:900}
.kpi-val.green{color:#25D366}
.kpi-val.blue{color:#60a5fa}

.table-wrap{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:16px;overflow:hidden}
.table-header{padding:14px 18px;border-bottom:1px solid rgba(255,255,255,.07);display:flex;justify-content:space-between;align-items:center}
.table-header h2{font-size:14px;font-weight:700}
table{width:100%;border-collapse:collapse;font-size:13px}
th{padding:9px 14px;text-align:left;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.55);background:rgba(255,255,255,.03)}
td{padding:10px 14px;border-top:1px solid rgba(255,255,255,.05);color:rgba(255,255,255,.8)}
tr:hover td{background:rgba(255,255,255,.03)}
.badge{display:inline-flex;padding:2px 10px;border-radius:999px;font-size:11px;font-weight:700}
.badge.confirmed{background:rgba(34,197,94,.15);color:#4ade80}
.badge.pending{background:rgba(251,191,36,.15);color:#fbbf24}
.badge.failed{background:rgba(239,68,68,.12);color:#fca5a5}
.empty{text-align:center;padding:36px;color:rgba(255,255,255,.45);font-size:13px}

@media(max-width:500px){
    .link-url{font-size:12px}
    th,td{padding:8px 10px}
}
</style>
</head>
<body>
<nav class="nav">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    <div class="nav-right">
        <a href="{{ route('seller.public', $payLink->handle) }}" style="color:rgba(255,255,255,.65);font-size:13px;text-decoration:none" target="_blank">View my page →</a>
        <form method="POST" action="{{ route('seller.logout') }}" style="display:inline">
            @csrf
            <button type="submit" style="background:none;border:none;color:rgba(255,255,255,.5);font-size:13px;cursor:pointer">Logout</button>
        </form>
    </div>
</nav>

<div class="main">
    @if(session('success'))
    <div style="background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.2);border-radius:10px;padding:12px 16px;font-size:13px;color:#4ade80;margin-bottom:20px">
        ✓ {{ session('success') }}
    </div>
    @endif

    <div class="welcome">
        <h1>{{ $payLink->business_name }}</h1>
        <p>pregota.com/pay/{{ $payLink->handle }}</p>
    </div>

    <div class="link-box">
        <div class="link-label">Your pay link</div>
        <div class="link-url">
            <span id="pay-url">pregota.com/pay/{{ $payLink->handle }}</span>
            <button class="copy-btn" onclick="copyLink()">📋 Copy</button>
        </div>
    </div>

    <div class="kpis">
        <div class="kpi">
            <div class="kpi-label">Total Received (net)</div>
            <div class="kpi-val green">KES {{ number_format($payLink->total_received) }}</div>
        </div>
        <div class="kpi">
            <div class="kpi-label">Payments</div>
            <div class="kpi-val blue">{{ number_format($payLink->payment_count) }}</div>
        </div>
        <div class="kpi">
            <div class="kpi-label">Pending</div>
            <div class="kpi-val" style="color:#fbbf24">{{ $payments->where('status','pending')->count() }}</div>
        </div>
    </div>

    <div class="table-wrap">
        <div class="table-header">
            <h2>Recent Payments</h2>
            <span style="color:rgba(255,255,255,.5);font-size:12px">Last 50</span>
        </div>
        @if($payments->isEmpty())
        <div class="empty">No payments yet. Share your link to get started.</div>
        @else
        <table>
            <thead>
                <tr>
                    <th>Amount Received</th>
                    <th>Pregota Fee</th>
                    <th>Your Net</th>
                    <th>Note</th>
                    <th>Status</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $p)
                <tr>
                    <td style="font-weight:700">KES {{ number_format($p->amount) }}</td>
                    <td style="color:rgba(255,255,255,.5)">KES {{ number_format($p->fee) }}</td>
                    <td style="color:#4ade80;font-weight:700">KES {{ number_format($p->net_amount) }}</td>
                    <td style="color:rgba(255,255,255,.55);font-size:12px">{{ $p->buyer_note ?: '—' }}</td>
                    <td><span class="badge {{ $p->status }}">{{ ucfirst($p->status) }}</span></td>
                    <td style="font-size:12px;color:rgba(255,255,255,.55)">{{ $p->created_at->format('d M, H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>

<script>
function copyLink() {
    const url = 'https://pregota.com/pay/{{ $payLink->handle }}';
    navigator.clipboard.writeText(url).then(() => {
        const btn = document.querySelector('.copy-btn');
        btn.textContent = '✓ Copied!';
        setTimeout(() => btn.textContent = '📋 Copy', 2000);
    });
}
</script>
</body>
</html>
