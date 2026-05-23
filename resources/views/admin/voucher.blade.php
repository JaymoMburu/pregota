<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Voucher {{ $voucher->code }} — Pregota Admin</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh}
.nav{padding:14px 28px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.08)}
.logo{font-size:18px;font-weight:900;background:linear-gradient(135deg,#00A651,#007A33);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.back{color:rgba(255,255,255,.4);font-size:13px;text-decoration:none}
.main{padding:28px;max-width:900px;margin:0 auto}
.voucher-hero{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.09);border-radius:16px;padding:28px;margin-bottom:24px;display:flex;gap:24px;flex-wrap:wrap;align-items:center}
.code-big{font-family:monospace;font-size:32px;font-weight:900;letter-spacing:.12em;background:linear-gradient(135deg,#00A651,#007A33);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.badge{display:inline-flex;padding:4px 14px;border-radius:999px;font-size:12px;font-weight:700}
.badge.active{background:rgba(34,197,94,.15);color:#4ade80}
.badge.redeemed{background:rgba(167,139,250,.15);color:#a78bfa}
.badge.pending{background:rgba(251,191,36,.15);color:#fbbf24}
.badge.expired,.badge.cancelled{background:rgba(255,255,255,.08);color:rgba(255,255,255,.4)}
.grid{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px}
.card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:20px}
.card h3{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.35);margin-bottom:14px}
.row{display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid rgba(255,255,255,.05);font-size:13px}
.row:last-child{border-bottom:none}
.row .lbl{color:rgba(255,255,255,.45)}
.row .val{font-weight:600;color:#fff}
.ledger{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:14px;overflow:hidden}
.ledger h3{padding:14px 20px;border-bottom:1px solid rgba(255,255,255,.07);font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.35)}
.entry{padding:12px 20px;border-bottom:1px solid rgba(255,255,255,.05);display:flex;gap:16px;align-items:flex-start}
.entry:last-child{border-bottom:none}
.event-dot{width:8px;height:8px;border-radius:50%;background:#00A651;flex-shrink:0;margin-top:5px}
.event-name{font-size:12px;font-weight:700;color:#a78bfa;margin-bottom:4px}
.event-hash{font-family:monospace;font-size:10px;color:rgba(255,255,255,.25);word-break:break-all}
.event-payload{font-size:11px;color:rgba(255,255,255,.45);margin-top:4px}
.event-time{font-size:11px;color:rgba(255,255,255,.3);white-space:nowrap;margin-left:auto}
.fabric-badge{display:inline-flex;padding:1px 7px;border-radius:4px;font-size:10px;background:rgba(34,197,94,.1);color:#4ade80;font-family:monospace}
</style>
</head>
<body>
<nav class="nav">
    <div class="logo">Pregota Admin</div>
    <a href="{{ route('admin.dashboard') }}" class="back">← Dashboard</a>
</nav>

<div class="main">
    @if(session('success'))
    <div style="background:rgba(34,197,94,.12);border:1px solid rgba(34,197,94,.3);border-radius:10px;padding:12px 16px;margin-bottom:16px;font-size:13px;color:#4ade80">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div style="background:rgba(239,68,68,.12);border:1px solid rgba(239,68,68,.3);border-radius:10px;padding:12px 16px;margin-bottom:16px;font-size:13px;color:#f87171">{{ session('error') }}</div>
    @endif

    <div class="voucher-hero">
        <div>
            <div class="code-big">{{ $voucher->code }}</div>
            <div style="margin-top:10px;display:flex;align-items:center;gap:12px;flex-wrap:wrap">
                <span class="badge {{ $voucher->status }}">{{ ucfirst($voucher->status) }}</span>
                @if($voucher->status === 'pending')
                <form method="POST" action="{{ route('admin.voucher.activate', $voucher) }}" style="display:inline">
                    @csrf
                    <button type="submit" style="background:rgba(34,197,94,.15);border:1px solid rgba(34,197,94,.4);color:#4ade80;border-radius:6px;padding:4px 14px;font-size:12px;font-weight:700;cursor:pointer" onclick="return confirm('Manually activate this voucher?')">Activate</button>
                </form>
                @endif
                @if(!in_array($voucher->status, ['redeemed','cancelled']))
                <form method="POST" action="{{ route('admin.voucher.cancel', $voucher) }}" style="display:inline">
                    @csrf
                    <button type="submit" style="background:rgba(239,68,68,.12);border:1px solid rgba(239,68,68,.3);color:#f87171;border-radius:6px;padding:4px 14px;font-size:12px;font-weight:700;cursor:pointer" onclick="return confirm('Cancel this voucher?')">Cancel</button>
                </form>
                @endif
            </div>
        </div>
        @if($voucher->sender_name || $voucher->message)
        <div style="flex:1;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:10px;padding:14px;font-size:14px">
            @if($voucher->sender_name)
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.35);margin-bottom:6px">From: {{ $voucher->sender_name }}</div>
            @else
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.25);margin-bottom:6px">Anonymous</div>
            @endif
            @if($voucher->message)
            <div style="font-style:italic;color:rgba(255,255,255,.6)">"{{ $voucher->message }}"</div>
            @endif
        </div>
        @endif
    </div>

    <div class="grid">
        <div class="card">
            <h3>Financials</h3>
            <div class="row"><span class="lbl">Gross Amount</span><span class="val">KES {{ number_format($voucher->gross_amount, 2) }}</span></div>
            <div class="row"><span class="lbl">Deposit Fee (2.5%)</span><span class="val">KES {{ number_format($voucher->fee_in, 2) }}</span></div>
            <div class="row"><span class="lbl">Face Value</span><span class="val">KES {{ number_format($voucher->face_value, 2) }}</span></div>
            <div class="row"><span class="lbl">Withdrawal Fee (1.5%)</span><span class="val">KES {{ number_format($voucher->fee_out, 2) }}</span></div>
            <div class="row"><span class="lbl">Recipient Payout</span><span class="val" style="color:#22c55e">KES {{ number_format($voucher->payout_amount, 2) }}</span></div>
        </div>
        <div class="card">
            <h3>Timeline</h3>
            <div class="row"><span class="lbl">Created</span><span class="val">{{ $voucher->created_at->format('d M Y, H:i') }}</span></div>
            <div class="row"><span class="lbl">Activated</span><span class="val">{{ $voucher->activated_at?->format('d M Y, H:i') ?? '—' }}</span></div>
            <div class="row"><span class="lbl">Redeemed</span><span class="val">{{ $voucher->redeemed_at?->format('d M Y, H:i') ?? '—' }}</span></div>
            <div class="row"><span class="lbl">Expires</span><span class="val">{{ $voucher->expires_at?->format('d M Y, H:i') ?? '—' }}</span></div>
            <div class="row"><span class="lbl">M-Pesa Ref</span><span class="val" style="font-family:monospace;font-size:12px">{{ $voucher->mpesa_confirmation_code ?? '—' }}</span></div>
            <div class="row"><span class="lbl">B2C Ref</span><span class="val" style="font-family:monospace;font-size:12px">{{ $voucher->b2c_confirmation_code ?? '—' }}</span></div>
        </div>
    </div>

    <div class="ledger">
        <h3>Immutable Ledger Trail ({{ $voucher->ledger->count() }} entries)</h3>
        @foreach($voucher->ledger as $entry)
        <div class="entry">
            <div class="event-dot"></div>
            <div style="flex:1;min-width:0">
                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">
                    <span class="event-name">{{ strtoupper(str_replace('_', ' ', $entry->event)) }}</span>
                    @if($entry->amount) <span style="font-size:12px;color:#fff;font-weight:700">KES {{ number_format($entry->amount, 2) }}</span> @endif
                    @if($entry->fabric_tx_id) <span class="fabric-badge">Fabric: {{ substr($entry->fabric_tx_id, 0, 12) }}...</span> @endif
                </div>
                <div class="event-payload">{{ json_encode($entry->payload) }}</div>
                <div class="event-hash">{{ $entry->entry_hash }}</div>
            </div>
            <div class="event-time">{{ $entry->created_at->format('H:i:s') }}</div>
        </div>
        @endforeach
    </div>
</div>
</body>
</html>
