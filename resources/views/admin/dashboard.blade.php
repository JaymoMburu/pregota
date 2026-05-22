<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pregota Admin Dashboard</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0f0f1a;color:#fff;min-height:100vh}
.nav{padding:14px 28px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.08);background:rgba(0,0,0,.3)}
.logo{font-size:18px;font-weight:900;background:linear-gradient(135deg,#7c3aed,#db2777);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.nav-right{display:flex;gap:12px;align-items:center}
.logout{color:rgba(255,255,255,.4);font-size:13px;text-decoration:none}
.main{padding:28px}
.kpis{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:14px;margin-bottom:28px}
.kpi{background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:18px}
.kpi-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.4);margin-bottom:6px}
.kpi-val{font-size:24px;font-weight:900}
.kpi-val.green{color:#22c55e}
.kpi-val.purple{color:#a78bfa}
.kpi-val.pink{color:#f472b6}
.kpi-val.yellow{color:#fbbf24}
.table-wrap{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.08);border-radius:16px;overflow:hidden}
.table-header{padding:16px 20px;border-bottom:1px solid rgba(255,255,255,.07);display:flex;justify-content:space-between;align-items:center}
.table-header h2{font-size:15px;font-weight:700}
table{width:100%;border-collapse:collapse;font-size:13px}
th{padding:10px 14px;text-align:left;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.35);background:rgba(255,255,255,.03)}
td{padding:11px 14px;border-top:1px solid rgba(255,255,255,.05);color:rgba(255,255,255,.8)}
tr:hover td{background:rgba(255,255,255,.03)}
.badge{display:inline-flex;padding:2px 10px;border-radius:999px;font-size:11px;font-weight:700}
.badge.active{background:rgba(34,197,94,.15);color:#4ade80}
.badge.redeemed{background:rgba(167,139,250,.15);color:#a78bfa}
.badge.pending{background:rgba(251,191,36,.15);color:#fbbf24}
.badge.expired,.badge.cancelled{background:rgba(255,255,255,.08);color:rgba(255,255,255,.4)}
.code{font-family:monospace;font-size:13px;font-weight:700;letter-spacing:.05em;color:#a78bfa}
.view-link{color:rgba(255,255,255,.4);font-size:12px;text-decoration:none}
.view-link:hover{color:#fff}
.frozen-section{background:rgba(239,68,68,.06);border:1px solid rgba(239,68,68,.2);border-radius:16px;overflow:hidden;margin-bottom:28px}
.frozen-section .table-header{border-bottom-color:rgba(239,68,68,.15)}
.frozen-section h2{color:#f87171}
.unfreeze-btn{display:inline-block;padding:4px 12px;border-radius:6px;border:1px solid rgba(34,197,94,.3);background:rgba(34,197,94,.08);color:#4ade80;font-size:12px;font-weight:700;cursor:pointer}
.unfreeze-btn:hover{background:rgba(34,197,94,.15)}
.reason-text{font-size:11px;color:rgba(255,255,255,.35);max-width:240px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
</style>
</head>
<body>
<nav class="nav">
    <div class="logo">Pregota Admin</div>
    <div class="nav-right">
        <a href="{{ route('admin.partners') }}" style="color:#a78bfa;font-size:13px;text-decoration:none;font-weight:600;margin-right:16px">Partners</a>
        <a href="{{ route('home') }}" style="color:rgba(255,255,255,.4);font-size:13px;text-decoration:none">← Live Site</a>
        <form method="POST" action="{{ route('admin.logout') }}" style="display:inline">
            @csrf
            <button type="submit" style="background:none;border:none;color:rgba(255,255,255,.4);cursor:pointer;font-size:13px">Logout</button>
        </form>
    </div>
</nav>

<div class="main">
    <div class="kpis">
        <div class="kpi"><div class="kpi-label">Total Vouchers</div><div class="kpi-val">{{ number_format($stats['total_vouchers']) }}</div></div>
        <div class="kpi"><div class="kpi-label">Active</div><div class="kpi-val yellow">{{ number_format($stats['active']) }}</div></div>
        <div class="kpi"><div class="kpi-label">Redeemed</div><div class="kpi-val green">{{ number_format($stats['redeemed']) }}</div></div>
        <div class="kpi"><div class="kpi-label">Pending</div><div class="kpi-val" style="color:#94a3b8">{{ number_format($stats['pending']) }}</div></div>
        <div class="kpi"><div class="kpi-label">Gross Volume</div><div class="kpi-val purple">KES {{ number_format($stats['gross_volume'], 0) }}</div></div>
        <div class="kpi"><div class="kpi-label">Total Fees Earned</div><div class="kpi-val pink">KES {{ number_format($stats['total_fees'], 0) }}</div></div>
        <div class="kpi"><div class="kpi-label">Total Payout</div><div class="kpi-val" style="color:#38bdf8">KES {{ number_format($stats['total_payout'], 0) }}</div></div>
    </div>

    @if($frozenSchoolCollections->count() || $frozenCollections->count())
    <div class="frozen-section">
        <div class="table-header">
            <h2>🚨 Frozen Collections — Pending Review</h2>
            <span style="color:rgba(239,68,68,.5);font-size:12px">{{ $frozenSchoolCollections->count() + $frozenCollections->count() }} frozen</span>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Name</th>
                    <th>Raised</th>
                    <th>Reason</th>
                    <th>Frozen At</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($frozenSchoolCollections as $sc)
                <tr>
                    <td><span style="font-size:11px;color:#60a5fa;font-weight:700">SCHOOL</span></td>
                    <td>
                        <div style="font-weight:700">{{ $sc->school_name }}</div>
                        <div style="font-size:11px;color:rgba(255,255,255,.35)">{{ $sc->term_label }}</div>
                    </td>
                    <td>KES {{ number_format($sc->total_raised) }}</td>
                    <td><span class="reason-text">{{ $sc->freeze_reason ?? '—' }}</span></td>
                    <td>{{ $sc->updated_at->format('d M, H:i') }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.school-collection.unfreeze', $sc) }}">
                            @csrf
                            <button type="submit" class="unfreeze-btn">Unfreeze</button>
                        </form>
                    </td>
                </tr>
                @endforeach
                @foreach($frozenCollections as $col)
                <tr>
                    <td><span style="font-size:11px;color:#a78bfa;font-weight:700">GROUP</span></td>
                    <td>
                        <div style="font-weight:700">{{ $col->title }}</div>
                        <div style="font-size:11px;color:rgba(255,255,255,.35)">{{ $col->organiser_name }}</div>
                    </td>
                    <td>KES {{ number_format($col->total_raised) }}</td>
                    <td><span class="reason-text">{{ $col->freeze_reason ?? '—' }}</span></td>
                    <td>{{ $col->updated_at->format('d M, H:i') }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.collection.unfreeze', $col) }}">
                            @csrf
                            <button type="submit" class="unfreeze-btn">Unfreeze</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="table-wrap">
        <div class="table-header">
            <h2>All Vouchers</h2>
            <span style="color:rgba(255,255,255,.3);font-size:12px">{{ $vouchers->total() }} total</span>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Gross</th>
                    <th>Face Value</th>
                    <th>Payout</th>
                    <th>Fee</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Expires</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($vouchers as $v)
                <tr>
                    <td><span class="code">{{ $v->code }}</span></td>
                    <td>{{ number_format($v->gross_amount, 0) }}</td>
                    <td>{{ number_format($v->face_value, 0) }}</td>
                    <td>{{ number_format($v->payout_amount, 0) }}</td>
                    <td>{{ number_format($v->fee_in + $v->fee_out, 0) }}</td>
                    <td><span class="badge {{ $v->status }}">{{ ucfirst($v->status) }}</span></td>
                    <td>{{ $v->created_at->format('d M, H:i') }}</td>
                    <td>{{ $v->expires_at?->format('d M') ?? '—' }}</td>
                    <td><a href="{{ route('admin.voucher', $v) }}" class="view-link">View →</a></td>
                </tr>
                @empty
                <tr><td colspan="9" style="text-align:center;color:rgba(255,255,255,.3);padding:32px">No vouchers yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div style="padding:16px 20px">{{ $vouchers->links() }}</div>
    </div>
</div>
</body>
</html>
