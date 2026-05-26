<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Pregota Spending</title>
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh}
.nav{padding:14px 24px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.07)}
.logo{font-size:20px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.wrap{max-width:600px;margin:0 auto;padding:32px 20px 80px}
h1{font-size:26px;font-weight:900;margin-bottom:6px}
.sub{font-size:14px;color:rgba(255,255,255,.55);margin-bottom:32px}

/* Lookup */
.lookup-box{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.09);border-radius:16px;padding:24px;margin-bottom:28px}
.field-label{display:block;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.55);margin-bottom:7px}
.phone-row{display:flex;gap:10px}
input[type=tel]{flex:1;padding:12px 14px;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.12);border-radius:10px;color:#fff;font-size:15px;outline:none}
input[type=tel]:focus{border-color:rgba(37,211,102,.4)}
.lookup-btn{padding:12px 20px;background:linear-gradient(135deg,#25D366,#1aaa52);color:#fff;font-size:14px;font-weight:800;border:none;border-radius:10px;cursor:pointer;white-space:nowrap}
.lookup-hint{font-size:11px;color:rgba(255,255,255,.35);margin-top:8px}

/* KPI grid */
.kpis{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:24px}
.kpis.four{grid-template-columns:repeat(4,1fr)}
@media(max-width:480px){.kpis.four{grid-template-columns:1fr 1fr}}
.kpi{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:16px}
.kpi-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.45);margin-bottom:5px}
.kpi-val{font-size:22px;font-weight:900;color:#4ADE80}
.kpi-sub{font-size:11px;color:rgba(255,255,255,.35);margin-top:3px}
.kpi-up{color:#4ADE80;font-size:11px;font-weight:700;margin-top:3px}
.kpi-down{color:#f87171;font-size:11px;font-weight:700;margin-top:3px}

/* Section header */
.section-head{font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.45);margin:28px 0 12px}

/* Monthly chart */
.chart-wrap{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:14px;padding:20px;margin-bottom:8px}
.chart-bars{display:flex;align-items:flex-end;gap:6px;height:100px;margin-bottom:8px}
.bar-col{flex:1;display:flex;flex-direction:column;align-items:center;gap:4px}
.bar-inner{width:100%;background:rgba(37,211,102,.25);border-radius:4px 4px 0 0;min-height:2px;transition:.3s;cursor:default;position:relative}
.bar-inner:hover{background:rgba(37,211,102,.5)}
.bar-inner .tooltip{display:none;position:absolute;bottom:calc(100% + 4px);left:50%;transform:translateX(-50%);background:#1a2730;border:1px solid rgba(255,255,255,.12);border-radius:6px;padding:4px 8px;font-size:10px;white-space:nowrap;pointer-events:none}
.bar-inner:hover .tooltip{display:block}
.bar-label{font-size:9px;color:rgba(255,255,255,.4);text-align:center}
.bar-this{background:rgba(37,211,102,.55)!important}

/* Category bars */
.cat-list{display:flex;flex-direction:column;gap:10px;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:14px;padding:18px}
.cat-row{display:flex;flex-direction:column;gap:5px}
.cat-row-top{display:flex;justify-content:space-between;align-items:center}
.cat-name{font-size:13px;font-weight:700}
.cat-amount{font-size:13px;font-weight:700;color:#4ADE80}
.cat-pct-track{height:6px;background:rgba(255,255,255,.08);border-radius:999px;overflow:hidden}
.cat-pct-fill{height:100%;background:linear-gradient(90deg,#25D366,#4ADE80);border-radius:999px;transition:.6s}
.cat-count{font-size:11px;color:rgba(255,255,255,.4)}

/* Day of week */
.dow-wrap{display:grid;grid-template-columns:repeat(7,1fr);gap:6px;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:14px;padding:16px}
.dow-col{display:flex;flex-direction:column;align-items:center;gap:6px}
.dow-bar-track{height:60px;display:flex;flex-direction:column;justify-content:flex-end;width:100%}
.dow-bar{width:100%;background:rgba(37,211,102,.2);border-radius:3px 3px 0 0;min-height:2px}
.dow-day{font-size:9px;color:rgba(255,255,255,.45);font-weight:700}

/* Stamp cards */
.stamp-card{background:rgba(37,211,102,.05);border:1px solid rgba(37,211,102,.18);border-radius:14px;padding:16px 18px;margin-bottom:10px}
.stamp-card-top{display:flex;justify-content:space-between;align-items:center;margin-bottom:10px}
.stamp-biz{font-size:14px;font-weight:800}
.stamp-dots{display:flex;gap:4px;flex-wrap:wrap;margin-bottom:6px}
.sd{width:20px;height:20px;border-radius:50%;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:9px}
.sd.on{background:rgba(37,211,102,.3);border-color:#25D366;color:#4ADE80}
.stamp-meta{font-size:12px;color:rgba(255,255,255,.5)}
.reward-ready{font-size:12px;font-weight:700;color:#fbbf24;margin-top:6px}

/* Sellers */
.seller-group{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:14px;overflow:hidden;margin-bottom:12px}
.sg-header{padding:14px 18px;display:flex;justify-content:space-between;align-items:center;cursor:pointer}
.sg-header:hover{background:rgba(255,255,255,.03)}
.sg-name{font-size:14px;font-weight:800}
.sg-handle{font-size:11px;font-family:monospace;color:rgba(255,255,255,.4);margin-top:2px}
.sg-total{font-size:16px;font-weight:900;color:#4ADE80;text-align:right}
.sg-count{font-size:11px;color:rgba(255,255,255,.4);text-align:right;margin-top:1px}
.sg-body{display:none;border-top:1px solid rgba(255,255,255,.06)}
.sg-body.open{display:block}
.pay-row{display:flex;justify-content:space-between;align-items:flex-start;padding:10px 18px;border-bottom:1px solid rgba(255,255,255,.04)}
.pay-row:last-child{border-bottom:none}
.pay-date{font-size:11px;color:rgba(255,255,255,.4);margin-top:2px}
.pay-note{font-size:11px;color:rgba(255,255,255,.4);font-style:italic;margin-top:1px}
.pay-receipt{font-size:11px;color:#a78bfa;text-decoration:none;font-family:monospace}

.error-msg{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);border-radius:10px;padding:12px 16px;font-size:13px;color:#fca5a5;margin-bottom:16px;display:none}
#results{display:none}
.not-found{text-align:center;padding:36px;color:rgba(255,255,255,.4);display:none}
</style>
</head>
<body>
<nav class="nav">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    <a href="{{ route('seller.directory') }}" style="font-size:13px;color:rgba(255,255,255,.5);text-decoration:none">Find Sellers →</a>
</nav>

<div class="wrap">
    <h1>My Spending</h1>
    <div class="sub">Your complete M-Pesa payment history via Pregota — daily, monthly, by category.</div>

    <div class="lookup-box">
        <label class="field-label">Your M-Pesa Phone Number</label>
        <div class="phone-row">
            <input type="tel" id="phone-input" placeholder="0712 345 678" autocomplete="tel">
            <button class="lookup-btn" onclick="lookup()">Show →</button>
        </div>
        <div class="lookup-hint">Your number is hashed — never stored in plain text. Only used to look up your payments.</div>
    </div>

    <div id="error-msg" class="error-msg"></div>

    <div id="results">
        {{-- Summary KPIs --}}
        <div class="kpis four">
            <div class="kpi">
                <div class="kpi-label">All Time</div>
                <div class="kpi-val" id="kpi-total">—</div>
                <div class="kpi-sub" id="kpi-count">— payments</div>
            </div>
            <div class="kpi">
                <div class="kpi-label">This Month</div>
                <div class="kpi-val" id="kpi-month">—</div>
                <div id="kpi-month-diff" class="kpi-sub"></div>
            </div>
            <div class="kpi">
                <div class="kpi-label">This Week</div>
                <div class="kpi-val" id="kpi-week">—</div>
            </div>
            <div class="kpi">
                <div class="kpi-label">Avg Transaction</div>
                <div class="kpi-val" id="kpi-avg">—</div>
            </div>
        </div>

        {{-- Monthly trend --}}
        <div class="section-head">Monthly Spending</div>
        <div class="chart-wrap">
            <div class="chart-bars" id="chart-bars"></div>
        </div>

        {{-- Day of week pattern --}}
        <div class="section-head">When You Spend</div>
        <div class="dow-wrap" id="dow-bars"></div>

        {{-- Category breakdown --}}
        <div class="section-head">Spending by Category</div>
        <div class="cat-list" id="cat-list"></div>

        {{-- Stamp cards --}}
        <div id="stamps-section" style="display:none">
            <div class="section-head">🎟 Stamp Cards</div>
            <div id="stamps-list"></div>
        </div>

        {{-- Seller detail --}}
        <div class="section-head">By Seller</div>
        <div id="sellers-list"></div>

        <div id="not-found" class="not-found">
            <div style="font-size:36px;margin-bottom:10px">🧾</div>
            <div style="font-size:16px;font-weight:700">No payments found</div>
            <div style="font-size:13px;margin-top:4px">No confirmed Pregota payments for this number yet.</div>
        </div>
    </div>
</div>

<script>
const CSRF = '{{ csrf_token() }}';

async function lookup() {
    const phone = document.getElementById('phone-input').value.trim();
    if (!phone) return;
    const errEl = document.getElementById('error-msg');
    errEl.style.display = 'none';

    try {
        const res  = await fetch('{{ route('buyer.me.lookup') }}', {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN': CSRF},
            body: JSON.stringify({phone}),
        });
        const d = await res.json();

        if (!res.ok) {
            errEl.textContent = d.errors?.phone?.[0] || 'Invalid phone number.';
            errEl.style.display = 'block';
            return;
        }

        document.getElementById('results').style.display = 'block';

        if (!d.found) {
            document.getElementById('not-found').style.display = 'block';
            return;
        }
        document.getElementById('not-found').style.display = 'none';

        // ── KPIs ──────────────────────────────────────────────────────────
        document.getElementById('kpi-total').textContent  = 'KES ' + d.total_kes.toLocaleString();
        document.getElementById('kpi-count').textContent  = d.total_count + ' payment' + (d.total_count !== 1 ? 's' : '');
        document.getElementById('kpi-month').textContent  = 'KES ' + d.this_month.toLocaleString();
        document.getElementById('kpi-week').textContent   = 'KES ' + d.this_week.toLocaleString();
        document.getElementById('kpi-avg').textContent    = 'KES ' + d.avg_tx.toLocaleString();

        const diffEl = document.getElementById('kpi-month-diff');
        if (d.last_month > 0) {
            const pct  = Math.round(((d.this_month - d.last_month) / d.last_month) * 100);
            const sign = pct >= 0 ? '▲' : '▼';
            diffEl.className = pct >= 0 ? 'kpi-up' : 'kpi-down';
            diffEl.textContent = sign + ' ' + Math.abs(pct) + '% vs last month';
        } else {
            diffEl.textContent = 'KES ' + d.last_month.toLocaleString() + ' last month';
            diffEl.className = 'kpi-sub';
        }

        // ── Monthly chart ─────────────────────────────────────────────────
        const months  = d.by_month;
        const maxVal  = Math.max(...months.map(m => m.total), 1);
        const nowKey  = new Date().toISOString().slice(0,7); // YYYY-MM
        document.getElementById('chart-bars').innerHTML = months.map(m => {
            const h   = Math.max(4, Math.round((m.total / maxVal) * 100));
            const cur = m.month === nowKey;
            return `<div class="bar-col">
                <div class="bar-inner ${cur ? 'bar-this' : ''}" style="height:${h}px">
                    <div class="tooltip">KES ${m.total.toLocaleString()}<br>${m.count} payment${m.count!==1?'s':''}</div>
                </div>
                <div class="bar-label">${m.label.split(' ')[0]}</div>
            </div>`;
        }).join('');

        // ── Day of week ───────────────────────────────────────────────────
        const maxDow = Math.max(...d.by_dow.map(x => x.total), 1);
        document.getElementById('dow-bars').innerHTML = d.by_dow.map(x => {
            const h = Math.max(2, Math.round((x.total / maxDow) * 60));
            return `<div class="dow-col">
                <div class="dow-bar-track">
                    <div class="dow-bar" style="height:${h}px" title="KES ${x.total.toLocaleString()}"></div>
                </div>
                <div class="dow-day">${x.day}</div>
            </div>`;
        }).join('');

        // ── Category breakdown ─────────────────────────────────────────────
        const maxCat = d.by_category[0]?.total || 1;
        document.getElementById('cat-list').innerHTML = d.by_category.map(c => {
            const pct = Math.round((c.total / maxCat) * 100);
            return `<div class="cat-row">
                <div class="cat-row-top">
                    <div class="cat-name">${c.emoji} ${c.category.charAt(0).toUpperCase() + c.category.slice(1)}</div>
                    <div>
                        <span class="cat-amount">KES ${c.total.toLocaleString()}</span>
                        <span class="cat-count" style="margin-left:8px">${c.count} payment${c.count!==1?'s':''}</span>
                    </div>
                </div>
                <div class="cat-pct-track"><div class="cat-pct-fill" style="width:${pct}%"></div></div>
            </div>`;
        }).join('');

        // ── Stamp cards ───────────────────────────────────────────────────
        if (d.stamps && d.stamps.length > 0) {
            document.getElementById('stamps-section').style.display = 'block';
            document.getElementById('stamps-list').innerHTML = d.stamps.map(s => {
                const dots = Array.from({length: s.stamps_required}, (_, i) =>
                    `<div class="sd ${i < s.stamp_count ? 'on' : ''}">✓</div>`
                ).join('');
                return `<div class="stamp-card">
                    <div class="stamp-card-top">
                        <div class="stamp-biz">${s.business_name}</div>
                        <div style="font-size:11px;color:rgba(255,255,255,.4)">${s.stamps_required} stamps = reward</div>
                    </div>
                    <div class="stamp-dots">${dots}</div>
                    <div class="stamp-meta">${s.stamp_count}/${s.stamps_required} — ${s.stamps_required - s.stamp_count} more for: <strong>${s.reward || 'reward'}</strong></div>
                    ${s.reward_pending ? '<div class="reward-ready">🎉 Reward ready — show to seller!</div>' : ''}
                </div>`;
            }).join('');
        }

        // ── Sellers ───────────────────────────────────────────────────────
        document.getElementById('sellers-list').innerHTML = d.grouped.map((g, i) => `
            <div class="seller-group">
                <div class="sg-header" onclick="toggleSeller(${i})">
                    <div>
                        <div class="sg-name">${g.business_name}</div>
                        <div class="sg-handle">pregota.com/pay/${g.handle}</div>
                    </div>
                    <div>
                        <div class="sg-total">KES ${g.total_spent.toLocaleString()}</div>
                        <div class="sg-count">${g.count} payment${g.count!==1?'s':''} ▶</div>
                    </div>
                </div>
                <div class="sg-body" id="sg-${i}">
                    ${g.payments.map(p => `
                    <div class="pay-row">
                        <div>
                            <div style="font-size:13px;font-weight:700">KES ${p.amount.toLocaleString()}${p.tip_amount>0 ? ` <span style="font-size:11px;color:rgba(255,255,255,.4)">+${p.tip_amount} tip</span>` : ''}</div>
                            <div class="pay-date">${p.date}</div>
                            ${p.note ? `<div class="pay-note">"${p.note}"</div>` : ''}
                        </div>
                        ${p.receipt_url ? `<a href="${p.receipt_url}" target="_blank" class="pay-receipt">${p.receipt_number}</a>` : ''}
                    </div>`).join('')}
                </div>
            </div>
        `).join('');

    } catch(e) {
        document.getElementById('error-msg').textContent = 'Something went wrong. Please try again.';
        document.getElementById('error-msg').style.display = 'block';
    }
}

function toggleSeller(i) {
    document.getElementById('sg-' + i).classList.toggle('open');
}

document.getElementById('phone-input').addEventListener('keydown', e => {
    if (e.key === 'Enter') lookup();
});
</script>
</body>
</html>
