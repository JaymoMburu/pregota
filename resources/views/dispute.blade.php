<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Report a Problem â€” Pregota</title>
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}input,textarea,select,button{font-family:inherit;font-size:inherit}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh}
.nav{padding:14px 24px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.07)}
.logo{font-size:20px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.wrap{max-width:520px;margin:0 auto;padding:40px 20px 80px}
h1{font-size:22px;font-weight:900;margin-bottom:6px}
.sub{font-size:13px;color:rgba(255,255,255,.5);margin-bottom:28px;line-height:1.55}

.receipt-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:18px 20px;margin-bottom:24px;display:flex;justify-content:space-between;align-items:center}
.rc-biz{font-size:15px;font-weight:800}
.rc-ref{font-size:12px;font-family:monospace;color:rgba(255,255,255,.45);margin-top:3px}
.rc-amt{font-size:20px;font-weight:900;color:#4ADE80}

.field{margin-bottom:18px}
.field label{display:block;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.45);margin-bottom:7px}
.field input,.field select,.field textarea{width:100%;padding:12px 14px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:10px;color:#fff;font-size:14px;font-family:inherit;outline:none}
.field input:focus,.field select:focus,.field textarea:focus{border-color:rgba(239,68,68,.4)}
.field textarea{resize:vertical;min-height:110px;line-height:1.55}
select option{background:#1a2730}

.submit-btn{width:100%;padding:13px;background:linear-gradient(135deg,#ef4444,#b91c1c);color:#fff;font-size:15px;font-weight:800;border:none;border-radius:12px;cursor:pointer;margin-top:4px}
.submit-btn:hover{opacity:.9}

.alert{border-radius:11px;padding:14px 18px;font-size:14px;margin-bottom:20px;line-height:1.55}
.alert-success{background:rgba(37,211,102,.08);border:1px solid rgba(37,211,102,.25);color:#4ADE80}
.alert-error{background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.25);color:#fca5a5}

.already{background:rgba(251,191,36,.06);border:1px solid rgba(251,191,36,.2);border-radius:12px;padding:20px;text-align:center}
.already-icon{font-size:32px;margin-bottom:10px}
.already-title{font-weight:800;margin-bottom:5px}
.already-sub{font-size:13px;color:rgba(255,255,255,.5);line-height:1.55}

.back{display:block;text-align:center;margin-top:20px;font-size:13px;color:rgba(255,255,255,.4);text-decoration:none}
.back:hover{color:rgba(255,255,255,.7)}
</style>
</head>
<body>
<nav class="nav">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
</nav>

<div class="wrap">
    <h1>Report a Problem</h1>
    <div class="sub">If you paid but didn't receive what was promised, let us know. We review every dispute and can suspend sellers who fail to deliver.</div>

    <div class="receipt-card">
        <div>
            <div class="rc-biz">{{ $payment->payLink->business_name }}</div>
            <div class="rc-ref">{{ $payment->receipt_number }}</div>
        </div>
        <div class="rc-amt">KES {{ number_format($payment->amount) }}</div>
    </div>

    @if(session('filed'))
        <div class="alert alert-success">{{ session('filed') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    @if($already && !session('filed'))
        <div class="already">
            <div class="already-icon">ðŸ“‹</div>
            <div class="already-title">Dispute already filed</div>
            <div class="already-sub">We've received your complaint for this receipt and are reviewing it. You'll be contacted on the phone number you provided.</div>
        </div>
    @elseif(!$already)
        <form method="POST" action="{{ route('dispute.store', $payment->receipt_number) }}">
            @csrf

            <div class="field">
                <label>Your M-Pesa Number</label>
                <input type="tel" name="phone" placeholder="0712 345 678" value="{{ old('phone') }}" autocomplete="tel" required>
                @error('phone')<div style="color:#fca5a5;font-size:12px;margin-top:5px">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label>What went wrong?</label>
                <select name="issue_type" required>
                    <option value="">â€” Select â€”</option>
                    <option value="non_delivery" {{ old('issue_type') == 'non_delivery' ? 'selected' : '' }}>Product / service not delivered</option>
                    <option value="wrong_amount" {{ old('issue_type') == 'wrong_amount' ? 'selected' : '' }}>I was charged the wrong amount</option>
                    <option value="wrong_product" {{ old('issue_type') == 'wrong_product' ? 'selected' : '' }}>Wrong product / service received</option>
                    <option value="damaged" {{ old('issue_type') == 'damaged' ? 'selected' : '' }}>Product arrived damaged</option>
                    <option value="other" {{ old('issue_type') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('issue_type')<div style="color:#fca5a5;font-size:12px;margin-top:5px">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label>Describe what happened</label>
                <textarea name="description" placeholder="Tell us exactly what happened â€” what you ordered, what you received (or didn't), and any other relevant details." required minlength="20">{{ old('description') }}</textarea>
                @error('description')<div style="color:#fca5a5;font-size:12px;margin-top:5px">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="submit-btn">Submit Dispute</button>
        </form>
    @endif

    <a href="{{ route('receipt.show', $payment->receipt_number) }}" class="back">â† Back to receipt</a>
</div>
</body>
</html>

