﻿﻿<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pregota Admin Dashboard</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}input,textarea,select,button{font-family:inherit;font-size:inherit}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh}
.nav{padding:14px 28px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.08);background:rgba(0,0,0,.3)}
.logo{font-size:18px;font-weight:900;background:linear-gradient(135deg,#00A651,#007A33);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.nav-right{display:flex;gap:12px;align-items:center}
.logout{color:rgba(255,255,255,.68);font-size:13px;text-decoration:none}
.main{padding:28px}
.kpis{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:14px;margin-bottom:28px}
.kpi{background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:18px}
.kpi-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.68);margin-bottom:6px}
.kpi-val{font-size:24px;font-weight:900}
.kpi-val.green{color:#22c55e}
.kpi-val.purple{color:#a78bfa}
.kpi-val.pink{color:#4ADE80}
.kpi-val.yellow{color:#fbbf24}
.table-wrap{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.08);border-radius:16px;overflow:hidden}
.table-header{padding:16px 20px;border-bottom:1px solid rgba(255,255,255,.07);display:flex;justify-content:space-between;align-items:center}
.table-header h2{font-size:15px;font-weight:700}
table{width:100%;border-collapse:collapse;font-size:13px}
th{padding:10px 14px;text-align:left;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.6);background:rgba(255,255,255,.03)}
td{padding:11px 14px;border-top:1px solid rgba(255,255,255,.05);color:rgba(255,255,255,.8)}
tr:hover td{background:rgba(255,255,255,.03)}
.badge{display:inline-flex;padding:2px 10px;border-radius:999px;font-size:11px;font-weight:700}
.badge.active{background:rgba(34,197,94,.15);color:#4ade80}
.badge.redeemed{background:rgba(167,139,250,.15);color:#a78bfa}
.badge.pending{background:rgba(251,191,36,.15);color:#fbbf24}
.badge.expired,.badge.cancelled{background:rgba(255,255,255,.08);color:rgba(255,255,255,.68)}
.code{font-family:monospace;font-size:13px;font-weight:700;letter-spacing:.05em;color:#a78bfa}
.view-link{color:rgba(255,255,255,.68);font-size:12px;text-decoration:none}
.view-link:hover{color:#fff}
.frozen-section{background:rgba(239,68,68,.06);border:1px solid rgba(239,68,68,.2);border-radius:16px;overflow:hidden;margin-bottom:28px}
.frozen-section .table-header{border-bottom-color:rgba(239,68,68,.15)}
.frozen-section h2{color:#f87171}
.unfreeze-btn{display:inline-block;padding:4px 12px;border-radius:6px;border:1px solid rgba(34,197,94,.3);background:rgba(34,197,94,.08);color:#4ade80;font-size:12px;font-weight:700;cursor:pointer}
.unfreeze-btn:hover{background:rgba(34,197,94,.15)}
.reason-text{font-size:11px;color:rgba(255,255,255,.6);max-width:240px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.payout-section{background:rgba(251,191,36,.05);border:1px solid rgba(251,191,36,.25);border-radius:16px;overflow:hidden;margin-bottom:28px}
.payout-section .table-header{border-bottom-color:rgba(251,191,36,.15)}
.payout-section h2{color:#fbbf24}
.mark-paid-btn{display:inline-block;padding:5px 14px;border-radius:6px;border:1px solid rgba(251,191,36,.4);background:rgba(251,191,36,.1);color:#fbbf24;font-size:12px;font-weight:700;cursor:pointer}
.mark-paid-btn:hover{background:rgba(251,191,36,.2)}
.phone-val{font-family:monospace;font-size:13px;font-weight:700;color:#4ADE80;letter-spacing:.04em}
.bell-wrap{position:relative;display:inline-flex;align-items:center}
.bell-badge{position:absolute;top:-5px;right:-7px;background:#ef4444;color:#fff;border-radius:999px;font-size:9px;font-weight:800;padding:1px 5px;min-width:16px;text-align:center;line-height:14px}
.bell-icon{font-size:18px;cursor:pointer;opacity:.8;transition:.15s}
.bell-icon:hover{opacity:1}
</style>
</head>
<body>
<nav class="nav">
    <div class="logo">Pregota Admin</div>
    <div class="nav-right">
        @if($pendingPayouts->count())
        <a href="#pending-payouts" class="bell-wrap" style="margin-right:16px;text-decoration:none">
            <span class="bell-icon">🔔</span>
            <span class="bell-badge">{{ $pendingPayouts->count() }}</span>
        </a>
        @endif
        <a href="{{ route('admin.creators') }}" style="color:#25D366;font-size:13px;text-decoration:none;font-weight:600;margin-right:16px">Creators @if(\App\Models\Creator::where('is_active',false)->exists()) <span style="background:rgba(239,68,68,.8);color:#fff;border-radius:20px;padding:1px 7px;font-size:10px">{{ \App\Models\Creator::where('is_active',false)->count() }}</span> @endif</a>
        <a href="{{ route('admin.disputes') }}" style="color:#f87171;font-size:13px;text-decoration:none;font-weight:600;margin-right:16px">Disputes @if(\App\Models\Dispute::where('status','open')->exists()) <span style="background:rgba(239,68,68,.8);color:#fff;border-radius:20px;padding:1px 7px;font-size:10px">{{ \App\Models\Dispute::where('status','open')->count() }}</span> @endif</a>
        <a href="{{ route('admin.partners') }}" style="color:#a78bfa;font-size:13px;text-decoration:none;font-weight:600;margin-right:16px">Partners</a>
        <a href="{{ route('home') }}" style="color:rgba(255,255,255,.68);font-size:13px;text-decoration:none">← Live Site</a>
        <form method="POST" action="{{ route('admin.logout') }}" style="display:inline">
            @csrf
            <button type="submit" style="background:none;border:none;color:rgba(255,255,255,.68);cursor:pointer;font-size:13px">Logout</button>
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
                        <div style="font-size:11px;color:rgba(255,255,255,.6)">{{ $sc->term_label }}</div>
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
                        <div style="font-size:11px;color:rgba(255,255,255,.6)">{{ $col->organiser_name }}</div>
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

    @if($pendingPayouts->count())
    <div class="payout-section" id="pending-payouts">
        <div class="table-header">
            <h2>💸 Pending Payouts — Send These Now</h2>
            <span style="color:rgba(251,191,36,.6);font-size:12px">{{ $pendingPayouts->count() }} waiting</span>
        </div>
        @if(session('success'))
        <div style="padding:10px 20px;background:rgba(34,197,94,.1);border-bottom:1px solid rgba(34,197,94,.15);font-size:13px;color:#4ade80">
            ✓ {{ session('success') }}
        </div>
        @endif
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Send This Amount</th>
                    <th>To This Number</th>
                    <th>Claimed</th>
                    <th>Waiting</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendingPayouts as $p)
                <tr>
                    <td><span class="code">{{ $p->code }}</span></td>
                    <td style="font-size:15px;font-weight:800;color:#fbbf24">KES {{ number_format($p->payout_amount, 0) }}</td>
                    <td><span class="phone-val">{{ $p->recipient_phone }}</span></td>
                    <td style="font-size:12px;color:rgba(255,255,255,.6)">{{ $p->claimed_at?->format('d M, H:i') }}</td>
                    <td style="font-size:12px;color:rgba(255,255,255,.78)">{{ $p->claimed_at?->diffForHumans() }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.voucher.mark-paid', $p) }}">
                            @csrf
                            <button type="submit" class="mark-paid-btn">✓ Mark as Paid</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if($sellerPayouts->count())
    <div class="payout-section" style="background:rgba(37,211,102,.04);border-color:rgba(37,211,102,.2);margin-bottom:28px">
        <div class="table-header" style="border-bottom-color:rgba(37,211,102,.12)">
            <h2 style="color:#25D366">💚 Seller Payouts — Ready to Send</h2>
            <span style="color:rgba(37,211,102,.5);font-size:12px">{{ $sellerPayouts->count() }} sellers · min KES {{ number_format(\App\Services\SellerService::MIN_PAYOUT_KES) }} reached</span>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Handle</th>
                    <th>Business</th>
                    <th>Category</th>
                    <th>Send This Amount</th>
                    <th>Payments</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sellerPayouts as $sl)
                <tr>
                    <td><span class="code">{{ $sl->handle }}</span></td>
                    <td style="font-weight:700">{{ $sl->business_name }}</td>
                    <td style="font-size:12px;color:rgba(255,255,255,.78)">{{ ucfirst($sl->category ?? '—') }}</td>
                    <td style="font-size:15px;font-weight:800;color:#25D366">KES {{ number_format($sl->total_received) }}</td>
                    <td style="color:rgba(255,255,255,.6)">{{ number_format($sl->payment_count) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div style="padding:10px 18px;font-size:11px;color:rgba(255,255,255,.72)">
            Send via M-Pesa to each seller's registered number. After sending, manually reset their total_received to 0 in the DB until automated B2C is live.
        </div>
    </div>
    @endif

    <div class="table-wrap">
        <div class="table-header">
            <h2>All Vouchers</h2>
            <span style="color:rgba(255,255,255,.82);font-size:12px">{{ $vouchers->total() }} total</span>
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
                    <td style="display:flex;gap:8px;align-items:center">
                        <a href="{{ route('admin.voucher', $v) }}" class="view-link">View →</a>
                        @if($v->status === 'pending')
                        <form method="POST" action="{{ route('admin.voucher.activate', $v) }}">
                            @csrf
                            <button type="submit" style="background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.3);color:#4ade80;font-size:11px;font-weight:700;padding:3px 10px;border-radius:6px;cursor:pointer">Activate</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" style="text-align:center;color:rgba(255,255,255,.82);padding:32px">No vouchers yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div style="padding:16px 20px">{{ $vouchers->links() }}</div>
    </div>
</div>
</body>
</html>
