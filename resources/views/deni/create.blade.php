<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Create a Deni — Pregota</title>
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh}
.nav{padding:14px 24px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.07)}
.logo{font-size:20px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.wrap{max-width:480px;margin:0 auto;padding:36px 20px 80px}
h1{font-size:24px;font-weight:900;margin-bottom:6px}
.sub{font-size:14px;color:rgba(255,255,255,.5);margin-bottom:32px;line-height:1.6}
.field{margin-bottom:18px}
.field label{display:block;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.45);margin-bottom:7px}
.field input{width:100%;padding:13px 14px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:11px;color:#fff;font-size:15px;outline:none;font-family:inherit}
.field input:focus{border-color:rgba(37,211,102,.4)}
.field .hint{font-size:12px;color:rgba(255,255,255,.3);margin-top:5px}
.submit-btn{width:100%;padding:14px;background:linear-gradient(135deg,#25D366,#1aaa52);color:#fff;font-size:16px;font-weight:900;border:none;border-radius:13px;cursor:pointer;margin-top:6px}
.submit-btn:hover{opacity:.9}
.how-it-works{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:14px;padding:20px;margin-bottom:28px}
.how-it-works h3{font-size:13px;font-weight:700;color:rgba(255,255,255,.6);margin-bottom:12px;text-transform:uppercase;letter-spacing:.06em}
.step{display:flex;gap:12px;margin-bottom:10px;font-size:13px;color:rgba(255,255,255,.6);line-height:1.5}
.step-num{width:22px;height:22px;border-radius:50%;background:rgba(37,211,102,.12);border:1px solid rgba(37,211,102,.2);color:#4ADE80;font-size:11px;font-weight:900;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:1px}
</style>
</head>
<body>
<nav class="nav">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    <a href="{{ route('buyer.me') }}" style="font-size:13px;color:rgba(255,255,255,.4);text-decoration:none">My Pregota →</a>
</nav>

<div class="wrap">
    <h1>🧾 Record a Deni</h1>
    <div class="sub">Track what a customer owes you. They get a payment link — you see every payment in real time. No account needed.</div>

    <div class="how-it-works">
        <h3>How it works</h3>
        <div class="step"><div class="step-num">1</div><div>Fill in the details below — what they owe and how much.</div></div>
        <div class="step"><div class="step-num">2</div><div>You get an admin link (bookmark it) and a customer payment link.</div></div>
        <div class="step"><div class="step-num">3</div><div>Send the payment link to your customer via WhatsApp — they pay via M-Pesa.</div></div>
        <div class="step"><div class="step-num">4</div><div>Open your admin link anytime to see payments and the remaining balance.</div></div>
    </div>

    <form method="POST" action="{{ route('deni.store') }}">
        @csrf
        <div class="field">
            <label>Your Business / Stall Name</label>
            <input type="text" name="creditor_name" placeholder="e.g. Mama Pima Vibanda" maxlength="100" required value="{{ old('creditor_name') }}">
        </div>
        <div class="field">
            <label>What is the deni for?</label>
            <input type="text" name="description" placeholder="e.g. Lunch — rice + beef stew" maxlength="300" required value="{{ old('description') }}">
        </div>
        <div class="field">
            <label>Amount Owed (KES)</label>
            <input type="number" name="original_amount" placeholder="120" min="1" max="500000" required value="{{ old('original_amount') }}">
        </div>
        <div class="field">
            <label>Customer's Phone (optional)</label>
            <input type="tel" name="debtor_phone" placeholder="0712 345 678" value="{{ old('debtor_phone') }}">
            <div class="hint">If entered, the deni will appear on their Pregota dashboard automatically.</div>
        </div>
        <div class="field">
            <label>Due Date (optional)</label>
            <input type="date" name="due_date" value="{{ old('due_date') }}">
        </div>
        <button type="submit" class="submit-btn">Create Deni →</button>
    </form>
</div>
</body>
</html>
