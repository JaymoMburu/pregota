<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Transaction Verified — Pregota</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:24px}
.logo{font-size:20px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none;margin-bottom:32px}
.card{width:100%;max-width:480px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:18px;padding:28px}
.seal-icon{text-align:center;font-size:48px;margin-bottom:16px}
.verified-title{text-align:center;font-size:22px;font-weight:900;color:#4ade80;margin-bottom:4px}
.verified-sub{text-align:center;font-size:13px;color:rgba(255,255,255,.68);margin-bottom:24px}
.type-badge{display:inline-flex;align-items:center;gap:6px;padding:4px 12px;border-radius:20px;background:rgba(0,166,81,.15);border:1px solid rgba(0,166,81,.3);font-size:11px;color:#25D366;font-weight:700;margin-bottom:20px}
.row{display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid rgba(255,255,255,.05)}
.row:last-child{border-bottom:none}
.row-key{font-size:12px;color:rgba(255,255,255,.68)}
.row-val{font-size:13px;font-weight:600;color:rgba(255,255,255,.85);text-align:right}
.row-val.green{color:#4ade80}
.row-val.purple{color:#25D366}
.hash-block{margin-top:20px;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);border-radius:10px;padding:14px}
.hash-lbl{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.82);margin-bottom:8px}
.hash-val{font-family:monospace;font-size:11px;color:rgba(255,255,255,.72);word-break:break-all;line-height:1.6}
.copy-hash{margin-top:10px;width:100%;padding:9px;border-radius:8px;background:rgba(0,166,81,.1);border:1px solid rgba(0,166,81,.2);color:#25D366;font-size:12px;font-weight:700;cursor:pointer}
.copy-hash:hover{background:rgba(0,166,81,.18)}
.footer-note{text-align:center;font-size:11px;color:rgba(255,255,255,.2);margin-top:20px;line-height:1.6}
.home-link{display:block;text-align:center;margin-top:16px;font-size:13px;color:rgba(0,166,81,.7);text-decoration:none}
.home-link:hover{color:#25D366}
</style>
</head>
<body>

<a href="{{ route('home') }}" class="logo">Pregota</a>

<div class="card">
    <div class="seal-icon">🔐</div>
    <div class="verified-title">Transaction Verified</div>
    <div class="verified-sub">This payment is cryptographically sealed and has not been altered.</div>

    <div style="text-align:center;margin-bottom:20px">
        <span class="type-badge">{{ $label }}</span>
    </div>

    <div class="row">
        <span class="row-key">Amount</span>
        <span class="row-val purple">KES {{ number_format($record->amount) }}</span>
    </div>
    <div class="row">
        <span class="row-key">M-Pesa Receipt</span>
        <span class="row-val">{{ $record->mpesa_confirmation_code }}</span>
    </div>
    <div class="row">
        <span class="row-key">Sealed at</span>
        <span class="row-val">{{ ($record->paid_at ?? $record->updated_at)?->format('j M Y, H:i') }}</span>
    </div>
    <div class="row">
        <span class="row-key">Status</span>
        <span class="row-val green">✓ Confirmed</span>
    </div>

    <div class="hash-block">
        <div class="hash-lbl">SHA-256 Seal</div>
        <div class="hash-val" id="hashVal">{{ $hash }}</div>
        <button class="copy-hash" onclick="copyHash()">Copy Hash</button>
    </div>
</div>

<div class="footer-note">
    Hash computed from: PREGOTA · {{ $type }} · Record ID · M-Pesa code · Amount · Timestamp<br>
    Anyone can independently verify this record has not been tampered with.
</div>

<a href="{{ route('home') }}" class="home-link">← Back to Pregota</a>

<script>
function copyHash() {
    navigator.clipboard.writeText(document.getElementById('hashVal').textContent.trim()).then(() => {
        const btn = document.querySelector('.copy-hash');
        btn.textContent = '✓ Copied';
        setTimeout(() => btn.textContent = 'Copy Hash', 2000);
    });
}
</script>
</body>
</html>
