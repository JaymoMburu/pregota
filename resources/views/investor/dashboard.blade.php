<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pregota — Investor Dashboard</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0a0a14;color:#fff;min-height:100vh}

/* Nav */
.nav{padding:14px 32px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.07);background:rgba(0,0,0,.4);position:sticky;top:0;z-index:10;backdrop-filter:blur(8px)}
.logo{font-size:18px;font-weight:900;background:linear-gradient(135deg,#7c3aed,#db2777);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.nav-right{display:flex;align-items:center;gap:20px}
.investor-pill{background:rgba(124,58,237,.15);border:1px solid rgba(124,58,237,.25);border-radius:99px;padding:5px 14px;font-size:12px;color:#a78bfa;font-weight:600}
.logout-btn{background:none;border:none;color:rgba(255,255,255,.3);font-size:13px;cursor:pointer}
.logout-btn:hover{color:#fff}

/* Page */
.page{padding:32px}
.page-header{margin-bottom:28px}
.page-header h1{font-size:22px;font-weight:800;color:#fff;margin-bottom:4px}
.page-header p{font-size:13px;color:rgba(255,255,255,.4)}
.updated{font-size:11px;color:rgba(255,255,255,.25);margin-top:6px}

/* KPI Grid */
.kpis{display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:14px;margin-bottom:28px}
.kpi{background:#13131f;border:1px solid rgba(255,255,255,.07);border-radius:16px;padding:20px 22px;position:relative;overflow:hidden}
.kpi::before{content:'';position:absolute;top:0;left:0;right:0;height:3px}
.kpi.purple::before{background:linear-gradient(90deg,#7c3aed,#a855f7)}
.kpi.pink::before{background:linear-gradient(90deg,#db2777,#f472b6)}
.kpi.green::before{background:linear-gradient(90deg,#059669,#34d399)}
.kpi.blue::before{background:linear-gradient(90deg,#2563eb,#60a5fa)}
.kpi.gold::before{background:linear-gradient(90deg,#d97706,#fbbf24)}
.kpi-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.35);margin-bottom:10px}
.kpi-val{font-size:28px;font-weight:900;line-height:1;margin-bottom:5px}
.kpi.purple .kpi-val{color:#a78bfa}
.kpi.pink .kpi-val{color:#f472b6}
.kpi.green .kpi-val{color:#34d399}
.kpi.blue .kpi-val{color:#60a5fa}
.kpi.gold .kpi-val{color:#fbbf24}
.kpi-sub{font-size:11px;color:rgba(255,255,255,.3)}
.kpi-badge{display:inline-block;background:rgba(52,211,153,.12);border:1px solid rgba(52,211,153,.2);color:#34d399;font-size:10px;font-weight:700;border-radius:99px;padding:2px 8px;margin-left:6px}
.kpi-badge.neg{background:rgba(248,113,113,.12);border-color:rgba(248,113,113,.2);color:#f87171}

/* Two column */
.row2{display:grid;grid-template-columns:1.4fr 1fr;gap:18px;margin-bottom:18px}
@media(max-width:900px){.row2{grid-template-columns:1fr}}

/* Panel */
.panel{background:#13131f;border:1px solid rgba(255,255,255,.07);border-radius:16px;overflow:hidden}
.panel-head{padding:16px 20px;border-bottom:1px solid rgba(255,255,255,.06);display:flex;justify-content:space-between;align-items:center}
.panel-head h2{font-size:14px;font-weight:700;color:#fff}
.panel-head span{font-size:11px;color:rgba(255,255,255,.3)}
.panel-body{padding:20px}

/* Chart */
.chart-wrap{position:relative;height:220px}

/* Module table */
.mt{width:100%;border-collapse:collapse;font-size:13px}
.mt th{padding:8px 14px;text-align:left;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.3);background:rgba(255,255,255,.02);border-bottom:1px solid rgba(255,255,255,.05)}
.mt td{padding:11px 14px;border-bottom:1px solid rgba(255,255,255,.04);color:rgba(255,255,255,.8);vertical-align:middle}
.mt tr:last-child td{border-bottom:none}
.mt tr:hover td{background:rgba(255,255,255,.02)}
.num{font-weight:700;font-variant-numeric:tabular-nums}
.tag{display:inline-block;background:rgba(255,255,255,.06);border-radius:6px;padding:2px 8px;font-size:11px;color:rgba(255,255,255,.4);font-weight:600}

/* Milestones */
.ms-list{display:flex;flex-direction:column;gap:0}
.ms{display:flex;align-items:flex-start;gap:14px;padding:13px 0;border-bottom:1px solid rgba(255,255,255,.05)}
.ms:last-child{border-bottom:none}
.ms-dot{width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0;margin-top:1px}
.ms-dot.done{background:rgba(52,211,153,.15);border:1px solid rgba(52,211,153,.3)}
.ms-dot.pending{background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1)}
.ms-text h4{font-size:13px;font-weight:700;color:#fff;margin-bottom:2px}
.ms-text p{font-size:12px;color:rgba(255,255,255,.35);line-height:1.5}

/* Investor profile card */
.profile{background:#13131f;border:1px solid rgba(124,58,237,.2);border-radius:16px;padding:20px 22px;margin-bottom:18px}
.profile-name{font-size:16px;font-weight:800;color:#fff;margin-bottom:4px}
.profile-type{font-size:12px;color:#a78bfa;font-weight:600;margin-bottom:14px}
.profile-row{display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid rgba(255,255,255,.05);font-size:13px}
.profile-row:last-child{border-bottom:none}
.profile-row .lbl{color:rgba(255,255,255,.35)}
.profile-row .val{color:#fff;font-weight:600}

/* Use of funds */
.uof{display:flex;flex-direction:column;gap:10px}
.uof-item{display:flex;align-items:center;gap:12px}
.uof-bar-wrap{flex:1;background:rgba(255,255,255,.06);border-radius:99px;height:7px;overflow:hidden}
.uof-bar{height:100%;border-radius:99px}
.uof-label{font-size:12px;color:rgba(255,255,255,.7);width:180px;flex-shrink:0}
.uof-pct{font-size:12px;font-weight:700;color:rgba(255,255,255,.5);width:36px;text-align:right;flex-shrink:0}

/* Confidentiality banner */
.confidential{background:rgba(124,58,237,.08);border:1px solid rgba(124,58,237,.18);border-radius:10px;padding:10px 16px;font-size:12px;color:rgba(255,255,255,.35);margin-bottom:24px;display:flex;gap:10px;align-items:center}
</style>
</head>
<body>

<nav class="nav">
    <div class="logo">Pregota</div>
    <div class="nav-right">
        <div class="investor-pill">{{ $investor->name }}</div>
        <form method="POST" action="{{ route('investor.logout') }}" style="display:inline">
            @csrf
            <button type="submit" class="logout-btn">Sign out</button>
        </form>
    </div>
</nav>

<div class="page">

    <div class="page-header">
        <h1>Investor Dashboard</h1>
        <p>Live platform metrics — all figures are aggregated and update in real time</p>
        <div class="updated">Last updated: {{ now()->format('d M Y, H:i') }} EAT</div>
    </div>

    <div class="confidential">
        🔒 <span>Confidential — for authorised investors only. Do not distribute or share this page.</span>
    </div>

    {{-- KPI Row --}}
    <div class="kpis">
        <div class="kpi purple">
            <div class="kpi-label">Total Revenue (Fees)</div>
            <div class="kpi-val">KES {{ number_format($totalRevenue, 0) }}</div>
            <div class="kpi-sub">
                Cumulative platform fees collected
                @if($momGrowth !== null)
                    <span class="kpi-badge {{ $momGrowth >= 0 ? '' : 'neg' }}">
                        {{ $momGrowth >= 0 ? '+' : '' }}{{ $momGrowth }}% MoM
                    </span>
                @endif
            </div>
        </div>
        <div class="kpi pink">
            <div class="kpi-label">KES Disbursed to Recipients</div>
            <div class="kpi-val">KES {{ number_format($totalDisbursed, 0) }}</div>
            <div class="kpi-sub">Real money that reached real people</div>
        </div>
        <div class="kpi green">
            <div class="kpi-label">Total Transactions</div>
            <div class="kpi-val">{{ number_format($totalTx) }}</div>
            <div class="kpi-sub">Confirmed paid transactions, all modules</div>
        </div>
        <div class="kpi blue">
            <div class="kpi-label">Gross Volume Processed</div>
            <div class="kpi-val">KES {{ number_format($grossVolume, 0) }}</div>
            <div class="kpi-sub">Total KES through M-Pesa (incl. fees)</div>
        </div>
        <div class="kpi gold">
            <div class="kpi-label">Gross Margin</div>
            <div class="kpi-val">{{ $grossMarginPct }}%</div>
            <div class="kpi-sub">Revenue ÷ Gross Volume · target ≥ 90%</div>
        </div>
    </div>

    {{-- Chart + Investor Profile --}}
    <div class="row2">
        <div class="panel">
            <div class="panel-head">
                <h2>Monthly Revenue (Last 6 Months)</h2>
                <span>KES fees earned per month</span>
            </div>
            <div class="panel-body">
                <div class="chart-wrap">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

        <div>
            {{-- Investor profile --}}
            <div class="profile">
                <div class="profile-name">{{ $investor->name }}</div>
                <div class="profile-type">{{ $investor->typeLabel() }}</div>
                @if($investor->equity_pct)
                <div class="profile-row">
                    <span class="lbl">Equity stake</span>
                    <span class="val">{{ $investor->equity_pct }}%</span>
                </div>
                @endif
                @if($investor->amount_invested_kes)
                <div class="profile-row">
                    <span class="lbl">Amount invested</span>
                    <span class="val">KES {{ number_format($investor->amount_invested_kes, 0) }}</span>
                </div>
                @endif
                <div class="profile-row">
                    <span class="lbl">Active collections now</span>
                    <span class="val">{{ number_format($activeCollections) }}</span>
                </div>
                <div class="profile-row">
                    <span class="lbl">Total collections created</span>
                    <span class="val">{{ number_format($totalCollections) }}</span>
                </div>
                <div class="profile-row">
                    <span class="lbl">Schools using platform</span>
                    <span class="val">{{ number_format($totalSchools) }}</span>
                </div>
            </div>

            {{-- Use of funds (seed round) --}}
            <div class="panel">
                <div class="panel-head">
                    <h2>Seed Round Use of Funds</h2>
                    <span>KES 15M target</span>
                </div>
                <div class="panel-body">
                    <div class="uof">
                        <div class="uof-item">
                            <div class="uof-label">Engineering (2 devs, 12m)</div>
                            <div class="uof-bar-wrap"><div class="uof-bar" style="width:45%;background:linear-gradient(90deg,#7c3aed,#a855f7)"></div></div>
                            <div class="uof-pct">45%</div>
                        </div>
                        <div class="uof-item">
                            <div class="uof-label">Community activation</div>
                            <div class="uof-bar-wrap"><div class="uof-bar" style="width:20%;background:linear-gradient(90deg,#db2777,#f472b6)"></div></div>
                            <div class="uof-pct">20%</div>
                        </div>
                        <div class="uof-item">
                            <div class="uof-label">Daraja go-live &amp; compliance</div>
                            <div class="uof-bar-wrap"><div class="uof-bar" style="width:15%;background:linear-gradient(90deg,#2563eb,#60a5fa)"></div></div>
                            <div class="uof-pct">15%</div>
                        </div>
                        <div class="uof-item">
                            <div class="uof-label">Legal &amp; data protection</div>
                            <div class="uof-bar-wrap"><div class="uof-bar" style="width:10%;background:linear-gradient(90deg,#059669,#34d399)"></div></div>
                            <div class="uof-pct">10%</div>
                        </div>
                        <div class="uof-item">
                            <div class="uof-label">Operations &amp; infrastructure</div>
                            <div class="uof-bar-wrap"><div class="uof-bar" style="width:10%;background:linear-gradient(90deg,#d97706,#fbbf24)"></div></div>
                            <div class="uof-pct">10%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Module breakdown --}}
    <div class="panel" style="margin-bottom:18px;">
        <div class="panel-head">
            <h2>Revenue by Module</h2>
            <span>All-time cumulative</span>
        </div>
        <table class="mt">
            <thead>
                <tr>
                    <th>Module</th>
                    <th>Fee Structure</th>
                    <th>Transactions</th>
                    <th>Revenue (KES)</th>
                    <th>Disbursed to Users (KES)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($modules as $m)
                <tr>
                    <td><strong>{{ $m['name'] }}</strong></td>
                    <td><span class="tag">{{ $m['fee'] }}</span></td>
                    <td class="num">{{ number_format($m['tx']) }}</td>
                    <td class="num" style="color:#a78bfa">{{ number_format($m['revenue'], 0) }}</td>
                    <td class="num" style="color:#34d399">{{ number_format($m['disbursed'], 0) }}</td>
                </tr>
                @endforeach
                <tr style="background:rgba(255,255,255,.02)">
                    <td colspan="2"><strong style="color:#fff">Total</strong></td>
                    <td class="num"><strong>{{ number_format($totalTx) }}</strong></td>
                    <td class="num" style="color:#a78bfa"><strong>{{ number_format($totalRevenue, 0) }}</strong></td>
                    <td class="num" style="color:#34d399"><strong>{{ number_format($totalDisbursed, 0) }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Milestones --}}
    <div class="panel">
        <div class="panel-head">
            <h2>Platform Milestones</h2>
            <span>Build &amp; commercial progress</span>
        </div>
        <div class="panel-body">
            <div class="ms-list">
                <div class="ms">
                    <div class="ms-dot done">✓</div>
                    <div class="ms-text"><h4>Core platform built</h4><p>All modules functional — Gift Vouchers, Direct Gift, Staff Tips, Creator Tips, Welfare Collections, School Collections, Bill Split</p></div>
                </div>
                <div class="ms">
                    <div class="ms-dot done">✓</div>
                    <div class="ms-text"><h4>M-Pesa Daraja integrated (sandbox)</h4><p>STK Push + B2C payout pipeline fully functional. End-to-end flow tested with real M-Pesa numbers in sandbox environment.</p></div>
                </div>
                <div class="ms">
                    <div class="ms-dot done">✓</div>
                    <div class="ms-text"><h4>Blockchain transaction sealing</h4><p>Every transaction sealed with SHA-256 chained hash. Tamper-evident audit trail on every payment.</p></div>
                </div>
                <div class="ms">
                    <div class="ms-dot done">✓</div>
                    <div class="ms-text"><h4>Fraud detection layer</h4><p>Automated freeze on suspicious collection patterns, duplicate submission detection, manual admin review queue.</p></div>
                </div>
                <div class="ms">
                    <div class="ms-dot pending">○</div>
                    <div class="ms-text"><h4>Business registration (eCitizen)</h4><p>Sole proprietor → Limited company. Required before Daraja production application. KES 10,650 · 3–5 days.</p></div>
                </div>
                <div class="ms">
                    <div class="ms-dot pending">○</div>
                    <div class="ms-text"><h4>Daraja production credentials</h4><p>STK Push + B2C production approval from Safaricom. Requires company registration + Paybill number. 2–4 week timeline.</p></div>
                </div>
                <div class="ms">
                    <div class="ms-dot pending">○</div>
                    <div class="ms-text"><h4>First 10 corporate welfare group pilots</h4><p>Target: Nairobi offices with 50–300 employees. Activation campaign funded from seed round community allocation.</p></div>
                </div>
                <div class="ms">
                    <div class="ms-dot pending">○</div>
                    <div class="ms-text"><h4>5,000 active welfare groups</h4><p>18-month target post-seed. Organic growth via WhatsApp link distribution — every completed collection markets to 30 new users.</p></div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
const months  = @json($months->pluck('label'));
const revenue = @json($months->pluck('revenue'));
const max     = Math.max(...revenue, 1);

const ctx = document.getElementById('revenueChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: months,
        datasets: [{
            label: 'Revenue (KES)',
            data: revenue,
            backgroundColor: revenue.map((v, i) =>
                i === revenue.length - 1
                    ? 'rgba(167,139,250,0.9)'
                    : 'rgba(124,58,237,0.4)'
            ),
            borderColor: revenue.map((v, i) =>
                i === revenue.length - 1
                    ? 'rgba(167,139,250,1)'
                    : 'rgba(124,58,237,0.7)'
            ),
            borderWidth: 1,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => 'KES ' + ctx.parsed.y.toLocaleString()
                }
            }
        },
        scales: {
            x: { grid: { color: 'rgba(255,255,255,.05)' }, ticks: { color: 'rgba(255,255,255,.4)', font: { size: 11 } } },
            y: { grid: { color: 'rgba(255,255,255,.05)' }, ticks: { color: 'rgba(255,255,255,.4)', font: { size: 11 }, callback: v => 'KES ' + v.toLocaleString() }, beginAtZero: true }
        }
    }
});
</script>
</body>
</html>
