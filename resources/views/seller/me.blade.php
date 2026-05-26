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
.sub{font-size:14px;color:rgba(255,255,255,.55);margin-bottom:28px}

/* Lookup */
.lookup-box{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.09);border-radius:16px;padding:20px 24px;margin-bottom:24px}
.field-label{display:block;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.55);margin-bottom:7px}
.phone-row{display:flex;gap:10px}
input[type=tel]{flex:1;padding:11px 14px;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.12);border-radius:10px;color:#fff;font-size:15px;outline:none}
input[type=tel]:focus{border-color:rgba(37,211,102,.4)}
.lookup-btn{padding:11px 20px;background:linear-gradient(135deg,#25D366,#1aaa52);color:#fff;font-size:14px;font-weight:800;border:none;border-radius:10px;cursor:pointer;white-space:nowrap}
.lookup-hint{font-size:11px;color:rgba(255,255,255,.3);margin-top:7px}

/* Log entry form */
.log-section{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.08);border-radius:16px;margin-bottom:28px;overflow:hidden}
.log-toggle{padding:14px 20px;display:flex;justify-content:space-between;align-items:center;cursor:pointer;user-select:none}
.log-toggle:hover{background:rgba(255,255,255,.03)}
.log-toggle-label{font-size:14px;font-weight:700}
.log-toggle-sub{font-size:12px;color:rgba(255,255,255,.45);margin-top:2px}
.log-body{display:none;padding:20px;border-top:1px solid rgba(255,255,255,.07)}
.log-body.open{display:block}
.type-toggle{display:flex;gap:8px;margin-bottom:16px}
.type-btn{flex:1;padding:10px;border-radius:10px;font-size:13px;font-weight:700;cursor:pointer;text-align:center;border:1px solid rgba(255,255,255,.1);background:rgba(255,255,255,.04);color:rgba(255,255,255,.6);transition:.15s}
.type-btn.active-exp{background:rgba(239,68,68,.12);border-color:rgba(239,68,68,.3);color:#f87171}
.type-btn.active-inc{background:rgba(37,211,102,.12);border-color:rgba(37,211,102,.3);color:#4ADE80}
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px}
@media(max-width:440px){.form-grid{grid-template-columns:1fr}}
.form-field{display:flex;flex-direction:column;gap:5px}
.form-field label{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:rgba(255,255,255,.5)}
.form-field input,.form-field select{padding:10px 12px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:9px;color:#fff;font-size:13px;outline:none;font-family:inherit}
.form-field input:focus,.form-field select:focus{border-color:rgba(37,211,102,.35)}
select option{background:#1a2730}
.desc-input{width:100%;padding:10px 12px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:9px;color:#fff;font-size:13px;outline:none;font-family:inherit;margin-bottom:14px}
.desc-input:focus{border-color:rgba(37,211,102,.35)}
.save-entry-btn{width:100%;padding:12px;background:linear-gradient(135deg,#25D366,#1aaa52);color:#fff;font-size:14px;font-weight:800;border:none;border-radius:10px;cursor:pointer}
.save-entry-btn:hover{opacity:.9}
.entry-success{font-size:12px;color:#4ADE80;text-align:center;margin-top:8px;display:none}

/* KPIs */
.kpis{display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-bottom:22px}
@media(max-width:480px){.kpis{grid-template-columns:1fr 1fr}}
.kpi{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:12px;padding:14px}
.kpi-label{font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.45);margin-bottom:4px}
.kpi-val{font-size:19px;font-weight:900;color:#4ADE80}
.kpi-sub{font-size:10px;color:rgba(255,255,255,.35);margin-top:3px}
.kpi-up{color:#4ADE80;font-size:10px;font-weight:700;margin-top:3px}
.kpi-down{color:#f87171;font-size:10px;font-weight:700;margin-top:3px}
.income-strip{background:rgba(37,211,102,.06);border:1px solid rgba(37,211,102,.15);border-radius:12px;padding:12px 16px;margin-bottom:22px;display:flex;justify-content:space-between;align-items:center}
.income-strip-label{font-size:12px;color:rgba(255,255,255,.55)}
.income-strip-val{font-size:16px;font-weight:900;color:#4ADE80}

/* Section header */
.section-head{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.4);margin:24px 0 10px}

/* Monthly chart */
.chart-wrap{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:14px;padding:18px 18px 12px}
.chart-bars{display:flex;align-items:flex-end;gap:5px;height:90px;margin-bottom:6px}
.bar-col{flex:1;display:flex;flex-direction:column;align-items:center;gap:3px}
.bar-inner{width:100%;background:rgba(37,211,102,.22);border-radius:3px 3px 0 0;min-height:2px;transition:.3s;position:relative;cursor:default}
.bar-inner:hover{background:rgba(37,211,102,.45)}
.bar-inner .tooltip{display:none;position:absolute;bottom:calc(100% + 4px);left:50%;transform:translateX(-50%);background:#1a2730;border:1px solid rgba(255,255,255,.12);border-radius:6px;padding:4px 8px;font-size:10px;white-space:nowrap;z-index:10}
.bar-inner:hover .tooltip{display:block}
.bar-label{font-size:8px;color:rgba(255,255,255,.35);text-align:center}
.bar-this{background:rgba(37,211,102,.5)!important}

/* Day of week */
.dow-wrap{display:grid;grid-template-columns:repeat(7,1fr);gap:5px;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:14px;padding:14px}
.dow-col{display:flex;flex-direction:column;align-items:center;gap:5px}
.dow-bar-track{height:50px;display:flex;flex-direction:column;justify-content:flex-end;width:100%}
.dow-bar{width:100%;background:rgba(37,211,102,.2);border-radius:3px 3px 0 0;min-height:2px}
.dow-day{font-size:9px;color:rgba(255,255,255,.4);font-weight:700}

/* Category */
.cat-list{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:14px;padding:16px;display:flex;flex-direction:column;gap:10px}
.cat-row-top{display:flex;justify-content:space-between;align-items:center;margin-bottom:4px}
.cat-name{font-size:13px;font-weight:700}
.cat-amount{font-size:13px;font-weight:700;color:#4ADE80}
.cat-track{height:5px;background:rgba(255,255,255,.07);border-radius:999px;overflow:hidden}
.cat-fill{height:100%;border-radius:999px;transition:.5s}
.cat-fill-auto{background:linear-gradient(90deg,#25D366,#4ADE80)}
.cat-fill-manual{background:linear-gradient(90deg,#60a5fa,#93c5fd)}
.cat-meta{font-size:10px;color:rgba(255,255,255,.35);margin-top:3px;display:flex;gap:10px}
.nudge{background:rgba(37,211,102,.06);border:1px solid rgba(37,211,102,.15);border-radius:10px;padding:10px 14px;font-size:12px;color:rgba(255,255,255,.6);margin-top:6px;line-height:1.55}
.nudge a{color:#25D366;text-decoration:none;font-weight:700}

/* Manual entries list */
.manual-list{display:flex;flex-direction:column;gap:8px}
.manual-row{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:12px;padding:12px 16px;display:flex;justify-content:space-between;align-items:flex-start}
.manual-row.income{border-color:rgba(37,211,102,.15);background:rgba(37,211,102,.04)}
.manual-row.expense{border-color:rgba(239,68,68,.12);background:rgba(239,68,68,.03)}
.mr-left{flex:1}
.mr-cat{font-size:12px;font-weight:700;margin-bottom:2px}
.mr-desc{font-size:11px;color:rgba(255,255,255,.5);font-style:italic}
.mr-date{font-size:10px;color:rgba(255,255,255,.35);margin-top:2px}
.mr-right{display:flex;align-items:center;gap:10px}
.mr-amount{font-size:15px;font-weight:900}
.mr-amount.exp{color:#f87171}
.mr-amount.inc{color:#4ADE80}
.del-btn{background:none;border:none;color:rgba(255,255,255,.3);font-size:16px;cursor:pointer;padding:0;line-height:1}
.del-btn:hover{color:#f87171}

/* Stamps */
.stamp-card{background:rgba(37,211,102,.05);border:1px solid rgba(37,211,102,.15);border-radius:14px;padding:14px 16px;margin-bottom:10px}
.sd{width:18px;height:18px;border-radius:50%;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12);display:inline-flex;align-items:center;justify-content:center;font-size:8px;margin-right:3px}
.sd.on{background:rgba(37,211,102,.25);border-color:#25D366;color:#4ADE80}

/* Sellers */
.sg{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:14px;overflow:hidden;margin-bottom:10px}
.sg-hdr{padding:13px 16px;display:flex;justify-content:space-between;align-items:center;cursor:pointer}
.sg-hdr:hover{background:rgba(255,255,255,.03)}
.sg-body{display:none;border-top:1px solid rgba(255,255,255,.06)}
.sg-body.open{display:block}
.pr{display:flex;justify-content:space-between;align-items:flex-start;padding:9px 16px;border-bottom:1px solid rgba(255,255,255,.04)}
.pr:last-child{border-bottom:none}

.err{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);border-radius:10px;padding:12px 16px;font-size:13px;color:#fca5a5;margin-bottom:16px;display:none}
#results{display:none}
.not-found{text-align:center;padding:40px;color:rgba(255,255,255,.4);display:none}
</style>
</head>
<body>
<nav class="nav">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    <a href="{{ route('seller.directory') }}" style="font-size:13px;color:rgba(255,255,255,.5);text-decoration:none">Find Sellers →</a>
</nav>

<div class="wrap">
    <h1>My Spending</h1>
    <div class="sub">Track everything — Pregota payments fill in automatically. Log the rest manually.</div>

    {{-- Phone lookup --}}
    <div class="lookup-box">
        <label class="field-label">Your M-Pesa Phone Number</label>
        <div class="phone-row">
            <input type="tel" id="phone-input" placeholder="0712 345 678" autocomplete="tel">
            <button class="lookup-btn" onclick="lookup()">Show →</button>
        </div>
        <div class="lookup-hint">Hashed — never stored in plain text.</div>
    </div>

    {{-- Log a transaction (collapsible) --}}
    <div class="log-section" id="log-section" style="display:none">
        <div class="log-toggle" onclick="toggleLog()">
            <div>
                <div class="log-toggle-label">➕ Log a Transaction</div>
                <div class="log-toggle-sub">Record spending or income not yet on Pregota</div>
            </div>
            <div id="log-chevron" style="font-size:18px;color:rgba(255,255,255,.4)">▶</div>
        </div>
        <div class="log-body" id="log-body">
            <div class="type-toggle">
                <div class="type-btn active-exp" id="type-exp" onclick="setType('expense')">📤 Expense</div>
                <div class="type-btn" id="type-inc" onclick="setType('income')">📥 Income / Received</div>
            </div>
            <div class="form-grid">
                <div class="form-field">
                    <label>Amount (KES)</label>
                    <input type="number" id="entry-amount" placeholder="e.g. 70" min="1">
                </div>
                <div class="form-field">
                    <label>Category</label>
                    <select id="entry-category">
                        <option value="">— Select —</option>
                        <option value="transport">🚐 Transport / Matatu</option>
                        <option value="food">🍱 Food & Restaurant</option>
                        <option value="groceries">🛒 Groceries & Kiosk</option>
                        <option value="salon">💇 Salon & Beauty</option>
                        <option value="fashion">👗 Fashion & Clothing</option>
                        <option value="electronics">📱 Electronics</option>
                        <option value="services">🛠 Services & Freelance</option>
                        <option value="other">🏪 Other</option>
                    </select>
                </div>
                <div class="form-field">
                    <label>Date</label>
                    <input type="date" id="entry-date">
                </div>
            </div>
            <input type="text" class="desc-input" id="entry-desc" placeholder="Description (optional) — e.g. Lunch at Java, CBD → Westlands" maxlength="200">
            <button class="save-entry-btn" onclick="saveEntry()">Save Entry</button>
            <div class="entry-success" id="entry-success">✓ Saved!</div>
        </div>
    </div>

    <div id="err" class="err"></div>

    <div id="results">

        {{-- Income strip --}}
        <div class="income-strip" id="income-strip" style="display:none">
            <div>
                <div class="income-strip-label">💰 Total Income Logged</div>
                <div style="font-size:11px;color:rgba(255,255,255,.35);margin-top:2px">Manually recorded income / money received</div>
            </div>
            <div class="income-strip-val" id="income-total">—</div>
        </div>

        {{-- KPIs --}}
        <div class="kpis">
            <div class="kpi">
                <div class="kpi-label">All Time Spent</div>
                <div class="kpi-val" id="kpi-total">—</div>
                <div class="kpi-sub" id="kpi-count"></div>
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
                <div class="kpi-label">Avg / Transaction</div>
                <div class="kpi-val" id="kpi-avg">—</div>
            </div>
        </div>

        {{-- Monthly trend --}}
        <div class="section-head">Monthly Spending</div>
        <div class="chart-wrap">
            <div class="chart-bars" id="chart-bars"></div>
        </div>

        {{-- Day of week --}}
        <div class="section-head">When You Spend Most</div>
        <div class="dow-wrap" id="dow-bars"></div>

        {{-- Category --}}
        <div class="section-head">By Category</div>
        <div class="cat-list" id="cat-list"></div>

        {{-- Manual entries --}}
        <div id="manual-section" style="display:none">
            <div class="section-head" style="display:flex;justify-content:space-between;align-items:center">
                <span>✏️ Manual Entries</span>
            </div>
            <div class="manual-list" id="manual-list"></div>
        </div>

        {{-- Stamp cards --}}
        <div id="stamps-section" style="display:none">
            <div class="section-head">🎟 Stamp Cards</div>
            <div id="stamps-list"></div>
        </div>

        {{-- Auto payments by seller --}}
        <div id="sellers-section" style="display:none">
            <div class="section-head">Pregota Payments — By Seller</div>
            <div id="sellers-list"></div>
        </div>

        <div id="not-found" class="not-found">
            <div style="font-size:36px;margin-bottom:10px">📒</div>
            <div style="font-size:16px;font-weight:700;margin-bottom:6px">Nothing here yet</div>
            <div style="font-size:13px;line-height:1.6">No Pregota payments found for this number.<br>Use the form above to start logging your spending.</div>
        </div>
    </div>
</div>

<script>
const CSRF  = '{{ csrf_token() }}';
let activePhone = '';
let entryType   = 'expense';

// Set today as default date
document.getElementById('entry-date').value = new Date().toISOString().slice(0,10);

function toggleLog() {
    const open = document.getElementById('log-body').classList.toggle('open');
    document.getElementById('log-chevron').style.transform = open ? 'rotate(90deg)' : '';
}

function setType(t) {
    entryType = t;
    document.getElementById('type-exp').className = 'type-btn' + (t === 'expense' ? ' active-exp' : '');
    document.getElementById('type-inc').className = 'type-btn' + (t === 'income'  ? ' active-inc' : '');
}

async function saveEntry() {
    const amount = parseInt(document.getElementById('entry-amount').value);
    const date   = document.getElementById('entry-date').value;
    if (!amount || amount < 1) { alert('Enter a valid amount.'); return; }
    if (!date) { alert('Select a date.'); return; }

    const res = await fetch('{{ route('buyer.me.entry') }}', {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN': CSRF},
        body: JSON.stringify({
            phone:       activePhone,
            type:        entryType,
            amount,
            category:    document.getElementById('entry-category').value || null,
            description: document.getElementById('entry-desc').value || null,
            entry_date:  date,
        }),
    });
    const data = await res.json();
    if (data.success) {
        document.getElementById('entry-success').style.display = 'block';
        document.getElementById('entry-amount').value = '';
        document.getElementById('entry-desc').value   = '';
        document.getElementById('entry-date').value   = new Date().toISOString().slice(0,10);
        setTimeout(() => document.getElementById('entry-success').style.display = 'none', 2000);
        // Refresh data
        lookup(true);
    }
}

async function deleteEntry(id) {
    if (!confirm('Remove this entry?')) return;
    await fetch(`/me/entry/${id}`, {
        method: 'DELETE',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN': CSRF},
        body: JSON.stringify({phone: activePhone}),
    });
    lookup(true);
}

async function lookup(silent = false) {
    const phone = document.getElementById('phone-input').value.trim();
    if (!phone) return;
    activePhone = phone;

    const errEl = document.getElementById('err');
    errEl.style.display = 'none';

    try {
        const res = await fetch('{{ route('buyer.me.lookup') }}', {
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

        // Show entry form now that we have a valid phone
        document.getElementById('log-section').style.display = 'block';
        document.getElementById('results').style.display     = 'block';

        if (!d.found) {
            document.getElementById('not-found').style.display = 'block';
            return;
        }
        document.getElementById('not-found').style.display = 'none';

        // ── KPIs ──
        document.getElementById('kpi-total').textContent = 'KES ' + d.total_kes.toLocaleString();
        document.getElementById('kpi-count').textContent = d.total_count + ' transaction' + (d.total_count !== 1 ? 's' : '');
        document.getElementById('kpi-month').textContent = 'KES ' + d.this_month.toLocaleString();
        document.getElementById('kpi-week').textContent  = 'KES ' + d.this_week.toLocaleString();
        document.getElementById('kpi-avg').textContent   = 'KES ' + d.avg_tx.toLocaleString();

        const diffEl = document.getElementById('kpi-month-diff');
        if (d.last_month > 0) {
            const pct  = Math.round(((d.this_month - d.last_month) / d.last_month) * 100);
            diffEl.className   = pct >= 0 ? 'kpi-up' : 'kpi-down';
            diffEl.textContent = (pct >= 0 ? '▲ ' : '▼ ') + Math.abs(pct) + '% vs last month';
        } else {
            diffEl.className   = 'kpi-sub';
            diffEl.textContent = 'KES ' + d.last_month.toLocaleString() + ' last month';
        }

        // Income strip
        if (d.total_income > 0) {
            document.getElementById('income-strip').style.display = 'flex';
            document.getElementById('income-total').textContent = 'KES ' + d.total_income.toLocaleString();
        }

        // ── Monthly chart ──
        const months = d.by_month;
        const maxM   = Math.max(...months.map(m => m.total), 1);
        const nowKey = new Date().toISOString().slice(0,7);
        document.getElementById('chart-bars').innerHTML = months.map(m => {
            const h   = Math.max(3, Math.round((m.total / maxM) * 90));
            const cur = m.month === nowKey;
            return `<div class="bar-col">
                <div class="bar-inner ${cur ? 'bar-this' : ''}" style="height:${h}px">
                    <div class="tooltip">KES ${m.total.toLocaleString()} · ${m.count} entries</div>
                </div>
                <div class="bar-label">${m.label.split(' ')[0]}</div>
            </div>`;
        }).join('');

        // ── Day of week ──
        const maxD = Math.max(...d.by_dow.map(x => x.total), 1);
        document.getElementById('dow-bars').innerHTML = d.by_dow.map(x => {
            const h = Math.max(2, Math.round((x.total / maxD) * 50));
            return `<div class="dow-col">
                <div class="dow-bar-track"><div class="dow-bar" style="height:${h}px"></div></div>
                <div class="dow-day">${x.day}</div>
            </div>`;
        }).join('');

        // ── Categories ──
        const maxC = d.by_category[0]?.total || 1;
        document.getElementById('cat-list').innerHTML = d.by_category.map(c => {
            const pct    = Math.round((c.total / maxC) * 100);
            const autoPct = Math.round((c.auto / maxC) * 100);
            const isManual = c.manual > 0 && c.auto === 0;
            const nudge = isManual
                ? `<div class="nudge">Paying <strong>${c.category}</strong> manually every time?
                    Ask the seller to join Pregota — it'll track automatically.
                    <a href="{{ route('seller.landing') }}" target="_blank">Share pregota.com/for-sellers →</a></div>`
                : '';
            return `<div>
                <div class="cat-row-top">
                    <div class="cat-name">${c.emoji} ${c.category.charAt(0).toUpperCase()+c.category.slice(1)}</div>
                    <div>
                        <span class="cat-amount">KES ${c.total.toLocaleString()}</span>
                        <span style="font-size:10px;color:rgba(255,255,255,.35);margin-left:8px">${c.count} entries</span>
                    </div>
                </div>
                <div class="cat-track"><div class="cat-fill ${isManual ? 'cat-fill-manual' : 'cat-fill-auto'}" style="width:${pct}%"></div></div>
                <div class="cat-meta">
                    ${c.auto > 0   ? `<span>✅ KES ${c.auto.toLocaleString()} via Pregota</span>` : ''}
                    ${c.manual > 0 ? `<span>✏️ KES ${c.manual.toLocaleString()} manual</span>` : ''}
                </div>
                ${nudge}
            </div>`;
        }).join('');

        // ── Manual entries ──
        if (d.manual && d.manual.length > 0) {
            document.getElementById('manual-section').style.display = 'block';
            document.getElementById('manual-list').innerHTML = d.manual.map(e => `
                <div class="manual-row ${e.type}" id="me-${e.id}">
                    <div class="mr-left">
                        <div class="mr-cat">${e.emoji} ${e.category.charAt(0).toUpperCase()+e.category.slice(1)}
                            <span style="font-size:10px;font-weight:400;color:rgba(255,255,255,.4);margin-left:6px">${e.type}</span>
                        </div>
                        ${e.description ? `<div class="mr-desc">${e.description}</div>` : ''}
                        <div class="mr-date">${e.date}</div>
                    </div>
                    <div class="mr-right">
                        <div class="mr-amount ${e.type === 'expense' ? 'exp' : 'inc'}">
                            ${e.type === 'expense' ? '−' : '+'}KES ${e.amount.toLocaleString()}
                        </div>
                        <button class="del-btn" onclick="deleteEntry(${e.id})" title="Remove">×</button>
                    </div>
                </div>`).join('');
        }

        // ── Stamp cards ──
        if (d.stamps && d.stamps.length > 0) {
            document.getElementById('stamps-section').style.display = 'block';
            document.getElementById('stamps-list').innerHTML = d.stamps.map(s => {
                const dots = Array.from({length: s.stamps_required}, (_, i) =>
                    `<span class="sd ${i < s.stamp_count ? 'on' : ''}">✓</span>`).join('');
                return `<div class="stamp-card">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
                        <div style="font-size:14px;font-weight:800">${s.business_name}</div>
                        <div style="font-size:11px;color:rgba(255,255,255,.4)">${s.stamps_required} stamps = reward</div>
                    </div>
                    <div style="margin-bottom:6px">${dots}</div>
                    <div style="font-size:12px;color:rgba(255,255,255,.5)">${s.stamp_count}/${s.stamps_required} · ${s.stamps_required-s.stamp_count} more for: <strong>${s.reward||'reward'}</strong></div>
                    ${s.reward_pending ? '<div style="color:#fbbf24;font-size:12px;font-weight:700;margin-top:6px">🎉 Reward ready!</div>' : ''}
                </div>`;
            }).join('');
        }

        // ── Auto payments by seller ──
        if (d.grouped && d.grouped.length > 0) {
            document.getElementById('sellers-section').style.display = 'block';
            document.getElementById('sellers-list').innerHTML = d.grouped.map((g, i) => `
                <div class="sg">
                    <div class="sg-hdr" onclick="document.getElementById('sg-${i}').classList.toggle('open')">
                        <div>
                            <div style="font-size:14px;font-weight:800">${g.business_name}</div>
                            <div style="font-size:11px;font-family:monospace;color:rgba(255,255,255,.4);margin-top:2px">pregota.com/pay/${g.handle}</div>
                        </div>
                        <div style="text-align:right">
                            <div style="font-size:16px;font-weight:900;color:#4ADE80">KES ${g.total_spent.toLocaleString()}</div>
                            <div style="font-size:11px;color:rgba(255,255,255,.4)">${g.count} payment${g.count!==1?'s':''} ▶</div>
                        </div>
                    </div>
                    <div class="sg-body" id="sg-${i}">
                        ${g.payments.map(p => `
                        <div class="pr">
                            <div>
                                <div style="font-size:13px;font-weight:700">KES ${p.amount.toLocaleString()}${p.tip_amount>0?` <span style="font-size:10px;color:rgba(255,255,255,.4)">+${p.tip_amount} tip</span>`:''}</div>
                                <div style="font-size:11px;color:rgba(255,255,255,.4);margin-top:1px">${p.date}</div>
                                ${p.note?`<div style="font-size:10px;color:rgba(255,255,255,.4);font-style:italic">"${p.note}"</div>`:''}
                            </div>
                            ${p.receipt_url?`<a href="${p.receipt_url}" target="_blank" style="font-size:11px;color:#a78bfa;font-family:monospace;text-decoration:none">${p.receipt_number}</a>`:''}
                        </div>`).join('')}
                    </div>
                </div>`).join('');
        }

    } catch(e) {
        document.getElementById('err').textContent = 'Something went wrong. Please try again.';
        document.getElementById('err').style.display = 'block';
    }
}

document.getElementById('phone-input').addEventListener('keydown', e => {
    if (e.key === 'Enter') lookup();
});
</script>
</body>
</html>
