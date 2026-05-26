<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Disputes — Pregota Admin</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh}
.nav{padding:14px 28px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.08);background:rgba(0,0,0,.3)}
.logo{font-size:18px;font-weight:900;background:linear-gradient(135deg,#00A651,#007A33);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.nav-links{display:flex;gap:16px;align-items:center}
.nav-links a{color:rgba(255,255,255,.55);font-size:13px;text-decoration:none}
.nav-links a:hover{color:#fff}
.main{padding:28px;max-width:1100px}
h1{font-size:20px;font-weight:900;margin-bottom:6px}
.sub{font-size:13px;color:rgba(255,255,255,.45);margin-bottom:24px}

.alert{padding:12px 16px;border-radius:10px;font-size:13px;margin-bottom:20px}
.alert-success{background:rgba(37,211,102,.08);border:1px solid rgba(37,211,102,.2);color:#4ADE80}

.dispute-card{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.08);border-radius:16px;margin-bottom:16px;overflow:hidden}
.dc-head{padding:16px 20px;display:flex;justify-content:space-between;align-items:flex-start;gap:16px;border-bottom:1px solid rgba(255,255,255,.05)}
.dc-receipt{font-family:monospace;font-size:13px;color:#a78bfa;font-weight:700}
.dc-biz{font-size:15px;font-weight:800;margin-bottom:3px}
.dc-amount{font-size:20px;font-weight:900;color:#4ADE80;white-space:nowrap}
.dc-body{padding:16px 20px}
.dc-meta{display:flex;gap:20px;flex-wrap:wrap;margin-bottom:12px}
.dc-meta-item{font-size:12px;color:rgba(255,255,255,.45)}
.dc-meta-item strong{color:rgba(255,255,255,.8)}
.issue-badge{display:inline-flex;padding:3px 10px;border-radius:999px;font-size:11px;font-weight:700;background:rgba(239,68,68,.12);border:1px solid rgba(239,68,68,.25);color:#f87171;margin-bottom:10px}
.description{font-size:13px;color:rgba(255,255,255,.7);line-height:1.6;background:rgba(255,255,255,.03);border-radius:8px;padding:12px 14px;margin-bottom:14px}

.status-badge{display:inline-flex;padding:3px 10px;border-radius:999px;font-size:11px;font-weight:700}
.status-badge.open{background:rgba(239,68,68,.12);border:1px solid rgba(239,68,68,.25);color:#f87171}
.status-badge.investigating{background:rgba(251,191,36,.12);border:1px solid rgba(251,191,36,.25);color:#fbbf24}
.status-badge.resolved{background:rgba(37,211,102,.12);border:1px solid rgba(37,211,102,.25);color:#4ADE80}
.status-badge.dismissed{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);color:rgba(255,255,255,.45)}

.dc-actions{display:flex;gap:8px;flex-wrap:wrap;align-items:center}
.action-form{display:inline}
select.status-sel{padding:6px 10px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:8px;color:#fff;font-size:12px;font-family:inherit;outline:none}
select.status-sel option{background:#1a2730}
input.note-input{padding:6px 10px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:8px;color:#fff;font-size:12px;font-family:inherit;outline:none;width:220px}
input.note-input::placeholder{color:rgba(255,255,255,.3)}
.btn-update{padding:6px 14px;background:rgba(168,85,247,.15);border:1px solid rgba(168,85,247,.3);color:#c084fc;font-size:12px;font-weight:700;border-radius:8px;cursor:pointer}
.btn-update:hover{background:rgba(168,85,247,.25)}
.btn-suspend{padding:6px 14px;background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);color:#f87171;font-size:12px;font-weight:700;border-radius:8px;cursor:pointer}
.btn-suspend:hover{background:rgba(239,68,68,.2)}
.btn-reinstate{padding:6px 14px;background:rgba(37,211,102,.1);border:1px solid rgba(37,211,102,.25);color:#4ADE80;font-size:12px;font-weight:700;border-radius:8px;cursor:pointer}
.btn-reinstate:hover{background:rgba(37,211,102,.2)}
.admin-note{font-size:11px;color:rgba(255,255,255,.4);margin-top:8px;font-style:italic}

.empty{text-align:center;padding:60px;color:rgba(255,255,255,.3)}
.empty-icon{font-size:40px;margin-bottom:12px}

.seller-tag{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;padding:2px 8px;border-radius:999px;margin-left:6px;vertical-align:middle}
.seller-tag.suspended{background:rgba(239,68,68,.15);color:#f87171;border:1px solid rgba(239,68,68,.3)}
</style>
</head>
<body>
<nav class="nav">
    <div class="logo">Pregota Admin</div>
    <div class="nav-links">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <a href="{{ route('admin.disputes') }}" style="color:#fff;font-weight:700">Disputes</a>
        <a href="{{ route('admin.logout') }}" onclick="event.preventDefault();document.getElementById('lf').submit()">Logout</a>
        <form id="lf" method="POST" action="{{ route('admin.logout') }}">@csrf</form>
    </div>
</nav>

<div class="main">
    <h1>Disputes</h1>
    <div class="sub">Buyer complaints filed via receipt pages. Resolve or dismiss each one. Suspend repeat-offender sellers.</div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($disputes->isEmpty())
        <div class="empty">
            <div class="empty-icon">✅</div>
            <div>No disputes filed yet.</div>
        </div>
    @else
        @foreach($disputes as $d)
        @php
            $payment = $d->payment;
            $payLink = $payment?->payLink;
            $issueLabels = [
                'non_delivery'  => 'Not delivered',
                'wrong_amount'  => 'Wrong amount',
                'wrong_product' => 'Wrong product',
                'damaged'       => 'Damaged',
                'other'         => 'Other',
            ];
        @endphp
        <div class="dispute-card">
            <div class="dc-head">
                <div>
                    <div class="dc-biz">
                        {{ $payLink?->business_name ?? '—' }}
                        @if($payLink?->is_suspended)
                            <span class="seller-tag suspended">Suspended</span>
                        @endif
                    </div>
                    <div class="dc-receipt">{{ $d->receipt_number }}</div>
                </div>
                <div style="text-align:right">
                    <div class="dc-amount">KES {{ number_format($payment?->amount ?? 0) }}</div>
                    <div style="font-size:11px;color:rgba(255,255,255,.35);margin-top:3px">{{ $d->created_at->format('d M Y · H:i') }}</div>
                </div>
            </div>
            <div class="dc-body">
                <div class="dc-meta">
                    <div class="dc-meta-item">Buyer: <strong style="font-family:monospace">{{ $d->buyer_phone }}</strong></div>
                    <div class="dc-meta-item">Status: <span class="status-badge {{ $d->status }}">{{ $d->status }}</span></div>
                    @if($payLink)
                    <div class="dc-meta-item">Seller: <strong>pregota.com/pay/{{ $payLink->handle }}</strong></div>
                    @endif
                </div>
                <div class="issue-badge">{{ $issueLabels[$d->issue_type] ?? $d->issue_type }}</div>
                <div class="description">{{ $d->description }}</div>
                @if($d->admin_note)
                    <div class="admin-note">Admin note: {{ $d->admin_note }}</div>
                @endif

                <div class="dc-actions">
                    <form method="POST" action="{{ route('admin.disputes.status', $d->id) }}" class="action-form" style="display:flex;gap:6px;align-items:center">
                        @csrf
                        <select name="status" class="status-sel">
                            @foreach(['open','investigating','resolved','dismissed'] as $s)
                                <option value="{{ $s }}" {{ $d->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                        <input type="text" name="admin_note" class="note-input" placeholder="Admin note (optional)" value="{{ $d->admin_note }}">
                        <button type="submit" class="btn-update">Update</button>
                    </form>

                    @if($payLink)
                        @if(!$payLink->is_suspended)
                            <form method="POST" action="{{ route('admin.sellers.suspend', $payLink->id) }}" class="action-form" onsubmit="return confirm('Suspend {{ $payLink->business_name }}? Their pay link will stop working.')">
                                @csrf
                                <button type="submit" class="btn-suspend">Suspend Seller</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.sellers.reinstate', $payLink->id) }}" class="action-form">
                                @csrf
                                <button type="submit" class="btn-reinstate">Reinstate Seller</button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>
        </div>
        @endforeach

        <div style="margin-top:20px">{{ $disputes->links() }}</div>
    @endif
</div>
</body>
</html>
