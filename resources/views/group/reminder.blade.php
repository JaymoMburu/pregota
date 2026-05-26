<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $payment->group->name }} — Payment Reminder</title>
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px}
.card{max-width:420px;width:100%;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.09);border-radius:22px;padding:36px 28px;text-align:center}
.icon{font-size:48px;margin-bottom:16px}
.group-name{font-size:20px;font-weight:900;margin-bottom:6px}
.period{font-size:13px;color:rgba(255,255,255,.45);margin-bottom:20px}
.amount-box{background:rgba(37,211,102,.06);border:1px solid rgba(37,211,102,.18);border-radius:14px;padding:18px;margin-bottom:24px}
.amount-label{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(37,211,102,.6);margin-bottom:6px}
.amount-val{font-size:42px;font-weight:900;color:#4ADE80}
.due-date{font-size:13px;color:rgba(255,255,255,.4);margin-top:6px}
.status-paid{background:rgba(37,211,102,.08);border:1px solid rgba(37,211,102,.2);border-radius:12px;padding:16px;margin-bottom:20px;color:#4ADE80;font-weight:700}
.pay-link{display:block;padding:14px;background:linear-gradient(135deg,#25D366,#1aaa52);color:#fff;font-size:16px;font-weight:900;border-radius:13px;text-decoration:none}
.pay-link:hover{opacity:.9}
</style>
</head>
<body>
<div class="card">
    <div class="icon">🤝</div>
    <div class="group-name">{{ $payment->group->name }}</div>
    <div class="period">Period: {{ $payment->period }}</div>

    @if($payment->status === 'confirmed')
        <div class="status-paid">✅ Payment confirmed — KES {{ number_format($payment->amount) }}</div>
        @if($payment->receipt_number)
            <a href="{{ route('receipt.show', $payment->receipt_number) }}" style="font-size:13px;color:#a78bfa;font-family:monospace">{{ $payment->receipt_number }}</a>
        @endif
    @else
        <div class="amount-box">
            <div class="amount-label">Amount Due</div>
            <div class="amount-val">KES {{ number_format($payment->group->amount_per_member ?? $payment->amount) }}</div>
            @if($payment->group->next_due)
                <div class="due-date">Due by {{ $payment->group->next_due->format('d M Y') }}</div>
            @endif
        </div>
        <a href="{{ route('group.show', $payment->group->slug) }}" class="pay-link">Pay Now →</a>
    @endif
</div>
</body>
</html>
