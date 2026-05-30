﻿﻿<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $subscription->plan->name }} — Reminder</title>
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}input,textarea,select,button{font-family:inherit;font-size:inherit}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px}
.card{max-width:420px;width:100%;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.09);border-radius:22px;padding:36px 28px;text-align:center}
.icon{font-size:48px;margin-bottom:14px}
.biz{font-size:14px;color:rgba(255,255,255,.72);margin-bottom:6px}
.plan-name{font-size:22px;font-weight:900;margin-bottom:20px}
.status-active{background:rgba(37,211,102,.08);border:1px solid rgba(37,211,102,.2);border-radius:12px;padding:16px;color:#4ADE80;font-weight:700;margin-bottom:20px}
.status-overdue{background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.25);border-radius:12px;padding:16px;color:#f87171;font-weight:700;margin-bottom:20px}
.amount-box{background:rgba(168,85,247,.06);border:1px solid rgba(168,85,247,.18);border-radius:14px;padding:18px;margin-bottom:24px}
.amount-val{font-size:38px;font-weight:900;color:#c084fc}
.amount-label{font-size:12px;color:rgba(255,255,255,.72);margin-top:4px}
.pay-link{display:block;padding:14px;background:linear-gradient(135deg,#25D366,#1aaa52);color:#fff;font-size:16px;font-weight:900;border-radius:13px;text-decoration:none}
</style>
</head>
<body>
<div class="card">
    <div class="icon">🔔</div>
    <div class="biz">{{ $subscription->plan->payLink->business_name }}</div>
    <div class="plan-name">{{ $subscription->plan->name }}</div>

    @if($subscription->status === 'active' && !$subscription->isDue())
        <div class="status-active">✅ Subscription active</div>
        <div style="font-size:13px;color:rgba(255,255,255,.72)">
            Next payment due: {{ $subscription->next_due_at?->format('d M Y') }}
        </div>
    @else
        <div class="status-overdue">⚠️ Payment due</div>
        <div class="amount-box">
            <div class="amount-val">KES {{ number_format($subscription->plan->amount) }}</div>
            <div class="amount-label">per {{ $subscription->plan->frequencyLabel() }}</div>
        </div>
        <a href="{{ route('subscription.show', $subscription->plan_id) }}" class="pay-link">Renew Now →</a>
    @endif
</div>
</body>
</html>
