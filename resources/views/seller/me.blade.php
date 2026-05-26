<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Pregota Receipts</title>
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh}
.nav{padding:14px 24px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.07)}
.logo{font-size:20px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.wrap{max-width:560px;margin:0 auto;padding:32px 20px 80px}
h1{font-size:26px;font-weight:900;margin-bottom:6px}
.sub{font-size:14px;color:rgba(255,255,255,.55);margin-bottom:32px}

/* Lookup form */
.lookup-box{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.09);border-radius:16px;padding:24px;margin-bottom:28px}
label{display:block;font-size:12px;font-weight:700;color:rgba(255,255,255,.65);text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px}
input{width:100%;padding:12px 14px;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.12);border-radius:10px;color:#fff;font-size:15px;outline:none}
input:focus{border-color:rgba(37,211,102,.4)}
.lookup-hint{font-size:11px;color:rgba(255,255,255,.4);margin-top:6px}
.btn{width:100%;margin-top:16px;padding:13px;background:linear-gradient(135deg,#25D366,#1aaa52);color:#fff;font-size:15px;font-weight:800;border:none;border-radius:12px;cursor:pointer}
.btn:hover{opacity:.9}

/* Summary bar */
.summary{display:flex;gap:14px;margin-bottom:28px}
.sum-box{flex:1;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:12px;padding:14px;text-align:center}
.sum-val{font-size:22px;font-weight:900;color:#4ADE80}
.sum-label{font-size:10px;color:rgba(255,255,255,.45);text-transform:uppercase;letter-spacing:.08em;margin-top:3px}

/* Stamp cards */
.stamps-section{margin-bottom:28px}
.stamps-section h2{font-size:14px;font-weight:700;color:rgba(255,255,255,.65);text-transform:uppercase;letter-spacing:.08em;margin-bottom:12px}
.stamp-card{background:rgba(37,211,102,.06);border:1px solid rgba(37,211,102,.18);border-radius:14px;padding:16px 18px;margin-bottom:10px}
.stamp-card-top{display:flex;justify-content:space-between;align-items:center;margin-bottom:10px}
.stamp-biz{font-size:14px;font-weight:800}
.stamp-reward-tag{font-size:11px;font-weight:700;padding:3px 10px;border-radius:999px;background:rgba(37,211,102,.12);color:#4ADE80;border:1px solid rgba(37,211,102,.25)}
.stamp-track{display:flex;gap:5px;flex-wrap:wrap;margin-bottom:8px}
.stamp-dot{width:22px;height:22px;border-radius:50%;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:10px}
.stamp-dot.filled{background:rgba(37,211,102,.25);border-color:#25D366;color:#4ADE80}
.stamp-dot.reward{background:rgba(251,191,36,.25);border-color:#fbbf24}
.stamp-meta{font-size:12px;color:rgba(255,255,255,.5)}
.reward-ready{background:rgba(251,191,36,.1);border:1px solid rgba(251,191,36,.3);border-radius:10px;padding:10px 14px;font-size:13px;color:#fbbf24;font-weight:700;margin-top:8px;text-align:center}

/* Seller groups */
.seller-group{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:14px;overflow:hidden;margin-bottom:16px}
.seller-group-header{padding:14px 18px;display:flex;justify-content:space-between;align-items:center;cursor:pointer;user-select:none}
.seller-group-header:hover{background:rgba(255,255,255,.03)}
.sg-left{display:flex;flex-direction:column;gap:2px}
.sg-name{font-size:14px;font-weight:800}
.sg-handle{font-size:11px;font-family:monospace;color:rgba(255,255,255,.4)}
.sg-right{text-align:right}
.sg-total{font-size:16px;font-weight:900;color:#4ADE80}
.sg-count{font-size:11px;color:rgba(255,255,255,.4)}
.sg-chevron{font-size:12px;color:rgba(255,255,255,.4);margin-left:8px;transition:.2s}
.sg-body{display:none;border-top:1px solid rgba(255,255,255,.06)}
.sg-body.open{display:block}
.payment-row{display:flex;justify-content:space-between;align-items:flex-start;padding:11px 18px;border-bottom:1px solid rgba(255,255,255,.04)}
.payment-row:last-child{border-bottom:none}
.pr-left{font-size:13px}
.pr-date{font-size:11px;color:rgba(255,255,255,.4);margin-top:2px}
.pr-note{font-size:11px;color:rgba(255,255,255,.45);font-style:italic;margin-top:2px}
.pr-right{display:flex;flex-direction:column;align-items:flex-end;gap:4px}
.pr-amount{font-size:14px;font-weight:800}
.pr-receipt{font-size:11px;color:#a78bfa;text-decoration:none;font-family:monospace}
.pr-receipt:hover{color:#c4b5fd}

/* Error / not found */
.not-found{text-align:center;padding:28px;color:rgba(255,255,255,.45)}
.error-msg{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);border-radius:10px;padding:12px 16px;font-size:13px;color:#fca5a5;margin-bottom:16px}

#results{display:none}
</style>
</head>
<body>
<nav class="nav">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    <a href="{{ route('seller.directory') }}" style="font-size:13px;color:rgba(255,255,255,.5);text-decoration:none">Find Sellers →</a>
</nav>

<div class="wrap">
    <h1>My Receipts</h1>
    <div class="sub">Enter the M-Pesa number you use to pay — see all your Pregota receipts.</div>

    <div class="lookup-box">
        <label>Your M-Pesa Phone Number</label>
        <input type="tel" id="phone-input" placeholder="0712 345 678" autocomplete="tel">
        <div class="lookup-hint">We never store your number — it's only used to look up your payments.</div>
        <button class="btn" onclick="lookup()">Show My Receipts →</button>
    </div>

    <div id="error-box" style="display:none" class="error-msg"></div>
    <div id="results">
        <div class="summary">
            <div class="sum-box">
                <div class="sum-val" id="sum-total">—</div>
                <div class="sum-label">Total Spent</div>
            </div>
            <div class="sum-box">
                <div class="sum-val" id="sum-count">—</div>
                <div class="sum-label">Payments</div>
            </div>
            <div class="sum-box">
                <div class="sum-val" id="sum-sellers">—</div>
                <div class="sum-label">Sellers</div>
            </div>
        </div>

        <div id="stamps-section" class="stamps-section" style="display:none">
            <h2>🎟 Stamp Cards</h2>
            <div id="stamps-list"></div>
        </div>

        <div id="sellers-list"></div>
        <div id="not-found" class="not-found" style="display:none">
            <div style="font-size:36px;margin-bottom:10px">🧾</div>
            <div style="font-size:16px;font-weight:700;margin-bottom:4px">No payments found</div>
            <div style="font-size:13px">No confirmed Pregota payments for this number.</div>
        </div>
    </div>
</div>

<script>
const CSRF = '{{ csrf_token() }}';

async function lookup() {
    const phone = document.getElementById('phone-input').value.trim();
    if (!phone) return;

    document.getElementById('error-box').style.display = 'none';

    try {
        const res  = await fetch('{{ route('buyer.me.lookup') }}', {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN': CSRF},
            body: JSON.stringify({phone}),
        });
        const data = await res.json();

        if (!res.ok) {
            const msg = data.errors?.phone?.[0] || 'Invalid phone number.';
            document.getElementById('error-box').textContent = msg;
            document.getElementById('error-box').style.display = 'block';
            return;
        }

        document.getElementById('results').style.display = 'block';

        if (!data.found) {
            document.getElementById('not-found').style.display = 'block';
            document.getElementById('sellers-list').innerHTML = '';
            return;
        }

        document.getElementById('not-found').style.display = 'none';
        document.getElementById('sum-total').textContent = 'KES ' + data.total_kes.toLocaleString();
        const totalCount = data.grouped.reduce((s, g) => s + g.count, 0);
        document.getElementById('sum-count').textContent = totalCount;
        document.getElementById('sum-sellers').textContent = data.grouped.length;

        // Stamp cards
        if (data.stamps && data.stamps.length > 0) {
            document.getElementById('stamps-section').style.display = 'block';
            document.getElementById('stamps-list').innerHTML = data.stamps.map(s => {
                const dots = Array.from({length: s.stamps_required}, (_, i) => {
                    const filled = i < s.stamp_count;
                    return `<div class="stamp-dot ${filled ? 'filled' : ''}">
                        ${filled ? '✓' : ''}
                    </div>`;
                }).join('');
                return `<div class="stamp-card">
                    <div class="stamp-card-top">
                        <div class="stamp-biz">${s.business_name}</div>
                        <div class="stamp-reward-tag">${s.stamps_required} stamps</div>
                    </div>
                    <div class="stamp-track">${dots}</div>
                    <div class="stamp-meta">${s.stamp_count} / ${s.stamps_required} — ${s.stamps_required - s.stamp_count} more for: <strong>${s.reward || 'reward'}</strong></div>
                    ${s.reward_pending ? '<div class="reward-ready">🎉 Reward ready — show this to the seller!</div>' : ''}
                </div>`;
            }).join('');
        }

        // Seller groups
        document.getElementById('sellers-list').innerHTML = data.grouped.map((g, i) => `
            <div class="seller-group">
                <div class="seller-group-header" onclick="toggleGroup(${i})">
                    <div class="sg-left">
                        <div class="sg-name">${g.business_name}</div>
                        <div class="sg-handle">pregota.com/pay/${g.handle}</div>
                    </div>
                    <div style="display:flex;align-items:center">
                        <div class="sg-right">
                            <div class="sg-total">KES ${g.total_spent.toLocaleString()}</div>
                            <div class="sg-count">${g.count} payment${g.count !== 1 ? 's' : ''}</div>
                        </div>
                        <div class="sg-chevron" id="chev-${i}">▶</div>
                    </div>
                </div>
                <div class="sg-body" id="group-${i}">
                    ${g.payments.map(p => `
                    <div class="payment-row">
                        <div class="pr-left">
                            <div>KES ${p.amount.toLocaleString()}${p.tip_amount > 0 ? ` <span style="font-size:11px;color:rgba(255,255,255,.45)">+ KES ${p.tip_amount} tip</span>` : ''}</div>
                            <div class="pr-date">${p.date}</div>
                            ${p.note ? `<div class="pr-note">${p.note}</div>` : ''}
                        </div>
                        <div class="pr-right">
                            <div class="pr-amount">KES ${p.amount.toLocaleString()}</div>
                            ${p.receipt_url ? `<a href="${p.receipt_url}" target="_blank" class="pr-receipt">${p.receipt_number}</a>` : ''}
                        </div>
                    </div>`).join('')}
                </div>
            </div>
        `).join('');

    } catch (e) {
        document.getElementById('error-box').textContent = 'Something went wrong. Please try again.';
        document.getElementById('error-box').style.display = 'block';
    }
}

function toggleGroup(i) {
    const body = document.getElementById('group-' + i);
    const chev = document.getElementById('chev-' + i);
    const open = body.classList.toggle('open');
    chev.style.transform = open ? 'rotate(90deg)' : '';
}

document.getElementById('phone-input').addEventListener('keydown', e => {
    if (e.key === 'Enter') lookup();
});
</script>
</body>
</html>
