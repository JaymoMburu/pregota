<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Receipt {{ $payment->receipt_number }} — Pregota</title>
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh;display:flex;flex-direction:column;align-items:center;padding:32px 16px 80px}

.card{width:100%;max-width:420px;background:#111c24;border:1px solid rgba(255,255,255,.1);border-radius:20px;overflow:hidden}

/* Header stripe */
.card-header{background:linear-gradient(135deg,#0d3320,#0a2018);padding:28px 28px 24px;text-align:center;border-bottom:1px solid rgba(37,211,102,.15)}
.logo-text{font-size:15px;font-weight:900;color:rgba(255,255,255,.5);letter-spacing:.05em;margin-bottom:16px}
.logo-text span{color:#25D366}
.tick{font-size:64px;line-height:1;margin-bottom:12px}
.paid-label{font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:#4ADE80;margin-bottom:4px}
.amount-big{font-size:44px;font-weight:900;color:#fff;line-height:1.1}
.amount-big .currency{font-size:22px;vertical-align:super;font-weight:700;color:rgba(255,255,255,.75)}

/* Body rows */
.card-body{padding:24px 28px}
.row{display:flex;justify-content:space-between;align-items:flex-start;padding:12px 0;border-bottom:1px solid rgba(255,255,255,.06)}
.row:last-child{border-bottom:none}
.row-label{font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.45)}
.row-val{font-size:14px;font-weight:700;color:#fff;text-align:right;max-width:60%}
.row-val.green{color:#25D366}
.row-val.mono{font-family:monospace;font-size:13px;color:#a78bfa;letter-spacing:.04em}

/* Tip row */
.tip-row{background:rgba(37,211,102,.05);border:1px solid rgba(37,211,102,.12);border-radius:10px;padding:12px 14px;margin:4px 0 8px;font-size:13px}
.tip-row-top{display:flex;justify-content:space-between;margin-bottom:4px}
.tip-comment{font-size:11px;color:rgba(255,255,255,.5);font-style:italic}

/* KRA stamp */
.kra-stamp{margin:20px 28px 0;padding:14px 16px;background:rgba(255,255,255,.03);border:1px dashed rgba(255,255,255,.15);border-radius:10px;text-align:center}
.kra-stamp p{font-size:11px;color:rgba(255,255,255,.5);line-height:1.65}
.kra-stamp strong{color:rgba(255,255,255,.75)}

/* Actions */
.actions{display:flex;gap:10px;margin-top:24px;width:100%;max-width:420px}
.btn{flex:1;padding:13px;border-radius:12px;font-size:14px;font-weight:800;border:none;cursor:pointer;text-align:center;text-decoration:none;display:flex;align-items:center;justify-content:center;gap:6px}
.btn-print{background:linear-gradient(135deg,#25D366,#1aaa52);color:#fff}
.btn-back{background:rgba(255,255,255,.07);color:rgba(255,255,255,.8);border:1px solid rgba(255,255,255,.12)}
.btn:hover{opacity:.9}

/* ── Print styles ──────────────────────────────────────────────────────── */
@media print{
    body{background:#fff!important;color:#000!important;padding:0;display:block}
    .card{border:1px solid #ddd;border-radius:8px;max-width:100%;box-shadow:none;background:#fff!important}
    .card-header{background:#f9fafb!important;border-bottom:1px solid #e5e7eb!important}
    .logo-text{color:#374151!important}
    .logo-text span{color:#059669!important}
    .paid-label{color:#059669!important}
    .amount-big{color:#111!important}
    .amount-big .currency{color:#374151!important}
    .row-label{color:#6b7280!important}
    .row-val{color:#111!important}
    .row-val.green{color:#059669!important}
    .row-val.mono{color:#6d28d9!important}
    .kra-stamp{border-color:#d1d5db!important;background:#f9fafb!important}
    .kra-stamp p{color:#6b7280!important}
    .kra-stamp strong{color:#374151!important}
    .tip-row{background:#f0fdf4!important;border-color:#a7f3d0!important}
    .actions{display:none!important}
    .print-footer{display:block!important}
}
.print-footer{display:none;text-align:center;font-size:10px;color:#9ca3af;margin-top:16px;padding:0 28px 20px}
</style>
</head>
<body>

<div class="card">
    <div class="card-header">
        <div class="logo-text"><span>Pregota</span> Payment Receipt</div>
        <div class="tick">✅</div>
        <div class="paid-label">Payment Confirmed</div>
        <div class="amount-big">
            <span class="currency">KES</span> {{ number_format($payment->amount, 0) }}
        </div>
        @if($payment->tip_amount > 0)
        <div style="font-size:13px;color:rgba(255,255,255,.55);margin-top:6px">
            incl. KES {{ number_format($payment->tip_amount, 0) }} tip
        </div>
        @endif
    </div>

    <div class="card-body">

        @php $link = $payment->payLink; @endphp

        <div class="row">
            <span class="row-label">Paid to</span>
            <span class="row-val">
                {{ $link->business_name }}
                @if($link->category === 'transport')
                <br><span style="font-family:monospace;font-size:12px;color:rgba(255,255,255,.55)">{{ $link->displayIdentifier() }}</span>
                @endif
            </span>
        </div>

        @if($link->current_route)
        <div class="row">
            <span class="row-label">Route</span>
            <span class="row-val green">{{ $link->current_route }}</span>
        </div>
        @endif

        <div class="row">
            <span class="row-label">Category</span>
            <span class="row-val" style="text-transform:capitalize">{{ $link->category }}</span>
        </div>

        @if($payment->buyer_note)
        <div class="row">
            <span class="row-label">Note</span>
            <span class="row-val" style="font-style:italic;color:rgba(255,255,255,.65)">{{ $payment->buyer_note }}</span>
        </div>
        @endif

        @if($payment->tip_amount > 0)
        <div class="row">
            <span class="row-label">Tip</span>
            <div style="text-align:right">
                <div class="tip-row">
                    <div class="tip-row-top">
                        <span>🙏 {{ ucfirst($payment->tip_recipient ?? 'Staff') }}</span>
                        <span style="font-weight:800;color:#4ADE80">+ KES {{ number_format($payment->tip_amount, 0) }}</span>
                    </div>
                    @if($payment->tip_comment)
                    <div class="tip-comment">"{{ $payment->tip_comment }}"</div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <div class="row">
            <span class="row-label">Date & Time</span>
            <span class="row-val">{{ $payment->updated_at->format('D, d M Y · H:i:s') }}</span>
        </div>

        <div class="row">
            <span class="row-label">M-Pesa Ref</span>
            <span class="row-val mono">{{ $payment->mpesa_ref }}</span>
        </div>

        <div class="row">
            <span class="row-label">Receipt No.</span>
            <span class="row-val mono">{{ $payment->receipt_number }}</span>
        </div>

    </div>

    <div class="kra-stamp">
        <p>
            <strong>Valid for KRA expense records</strong><br>
            This receipt is generated from a confirmed M-Pesa transaction via Pregota.<br>
            Reference: <strong>{{ $payment->receipt_number }}</strong> · M-Pesa: <strong>{{ $payment->mpesa_ref }}</strong>
        </p>
    </div>

    <div class="print-footer">
        Verified at pregota.com/receipt/{{ $payment->receipt_number }}<br>
        Generated {{ $payment->updated_at->format('d M Y H:i') }} · Pregota Limited
    </div>
</div>

<div class="actions">
    <button class="btn btn-print" onclick="window.print()">🖨 Print / Save PDF</button>
    <a href="{{ route('seller.public', $payment->payLink->handle) }}" class="btn btn-back">← Back</a>
</div>

</body>
</html>
