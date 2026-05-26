<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Pregota — Spending, Groups, Madeni</title>
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh}
.nav{padding:14px 24px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.07)}
.logo{font-size:20px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.wrap{max-width:560px;margin:0 auto;padding:40px 20px 80px}
h1{font-size:26px;font-weight:900;margin-bottom:6px}
.sub{font-size:14px;color:rgba(255,255,255,.55);margin-bottom:28px}

/* Auth card */
.auth-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.09);border-radius:18px;padding:28px 24px;margin-bottom:24px}
.auth-step{display:none}
.auth-step.active{display:block}
.step-title{font-size:18px;font-weight:900;margin-bottom:6px}
.step-sub{font-size:13px;color:rgba(255,255,255,.5);margin-bottom:22px;line-height:1.55}
.field-label{display:block;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.5);margin-bottom:7px}
.field-input{width:100%;padding:12px 14px;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.12);border-radius:11px;color:#fff;font-size:15px;outline:none;font-family:inherit}
.field-input:focus{border-color:rgba(37,211,102,.4)}
.action-btn{width:100%;margin-top:16px;padding:13px;background:linear-gradient(135deg,#25D366,#1aaa52);color:#fff;font-size:15px;font-weight:800;border:none;border-radius:12px;cursor:pointer}
.action-btn:hover{opacity:.9}
.action-btn:disabled{opacity:.5;cursor:not-allowed}
.back-link{display:block;text-align:center;margin-top:14px;font-size:13px;color:rgba(255,255,255,.4);cursor:pointer}
.back-link:hover{color:rgba(255,255,255,.7)}

/* PIN dots */
.pin-row{display:flex;gap:12px;justify-content:center;margin-bottom:8px}
.pin-box{width:52px;height:60px;background:rgba(255,255,255,.07);border:2px solid rgba(255,255,255,.12);border-radius:12px;font-size:26px;font-weight:900;text-align:center;color:#fff;outline:none;caret-color:transparent;font-family:monospace}
.pin-box:focus{border-color:rgba(37,211,102,.5);background:rgba(255,255,255,.09)}
.pin-hint{text-align:center;font-size:12px;color:rgba(255,255,255,.4);margin-bottom:16px}

.err-msg{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);border-radius:9px;padding:10px 14px;font-size:13px;color:#fca5a5;margin-top:12px;display:none}

/* Log entry */
.log-section{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.08);border-radius:16px;margin-bottom:26px;overflow:hidden}
.log-toggle{padding:14px 20px;display:flex;justify-content:space-between;align-items:center;cursor:pointer;user-select:none}
.log-toggle:hover{background:rgba(255,255,255,.03)}
.log-body{display:none;padding:18px 20px;border-top:1px solid rgba(255,255,255,.07)}
.log-body.open{display:block}
.type-toggle{display:flex;gap:8px;margin-bottom:14px}
.type-btn{flex:1;padding:9px;border-radius:9px;font-size:13px;font-weight:700;cursor:pointer;text-align:center;border:1px solid rgba(255,255,255,.1);background:rgba(255,255,255,.04);color:rgba(255,255,255,.55)}
.type-btn.active-exp{background:rgba(239,68,68,.1);border-color:rgba(239,68,68,.3);color:#f87171}
.type-btn.active-inc{background:rgba(37,211,102,.1);border-color:rgba(37,211,102,.3);color:#4ADE80}
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:12px}
@media(max-width:420px){.form-grid{grid-template-columns:1fr}}
.form-field label{display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:rgba(255,255,255,.45);margin-bottom:5px}
.form-field input,.form-field select{width:100%;padding:9px 11px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:8px;color:#fff;font-size:13px;outline:none;font-family:inherit}
.form-field input:focus,.form-field select:focus{border-color:rgba(37,211,102,.35)}
select option{background:#1a2730}
.desc-input{width:100%;padding:9px 11px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:8px;color:#fff;font-size:13px;outline:none;font-family:inherit;margin-bottom:12px}
.save-btn{width:100%;padding:11px;background:linear-gradient(135deg,#25D366,#1aaa52);color:#fff;font-size:14px;font-weight:800;border:none;border-radius:10px;cursor:pointer}
.entry-ok{font-size:12px;color:#4ADE80;text-align:center;margin-top:8px;display:none}

/* KPIs */
.kpis{display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-bottom:20px}
@media(max-width:460px){.kpis{grid-template-columns:1fr 1fr}}
.kpi{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);border-radius:12px;padding:13px}
.kpi-label{font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:rgba(255,255,255,.4);margin-bottom:4px}
.kpi-val{font-size:18px;font-weight:900;color:#4ADE80}
.kpi-sub{font-size:10px;color:rgba(255,255,255,.3);margin-top:2px}
.kpi-up{color:#4ADE80;font-size:10px;font-weight:700;margin-top:2px}
.kpi-down{color:#f87171;font-size:10px;font-weight:700;margin-top:2px}
.income-bar{background:rgba(37,211,102,.05);border:1px solid rgba(37,211,102,.14);border-radius:11px;padding:11px 16px;display:flex;justify-content:space-between;align-items:center;margin-bottom:20px}
.section-head{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.38);margin:22px 0 10px}

/* Charts */
.chart-wrap{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:13px;padding:16px 16px 10px}
.chart-bars{display:flex;align-items:flex-end;gap:5px;height:80px;margin-bottom:5px}
.bar-col{flex:1;display:flex;flex-direction:column;align-items:center;gap:3px}
.bar-inner{width:100%;background:rgba(37,211,102,.2);border-radius:3px 3px 0 0;min-height:2px;position:relative;cursor:default}
.bar-inner:hover{background:rgba(37,211,102,.4)}
.bar-inner .tt{display:none;position:absolute;bottom:calc(100%+4px);left:50%;transform:translateX(-50%);background:#1a2730;border:1px solid rgba(255,255,255,.1);border-radius:6px;padding:4px 8px;font-size:10px;white-space:nowrap;z-index:10}
.bar-inner:hover .tt{display:block}
.bar-label{font-size:8px;color:rgba(255,255,255,.32);text-align:center}
.bar-this{background:rgba(37,211,102,.45)!important}
.dow-wrap{display:grid;grid-template-columns:repeat(7,1fr);gap:5px;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:13px;padding:14px}
.dow-col{display:flex;flex-direction:column;align-items:center;gap:5px}
.dow-track{height:46px;display:flex;flex-direction:column;justify-content:flex-end;width:100%}
.dow-bar{width:100%;background:rgba(37,211,102,.18);border-radius:3px 3px 0 0;min-height:2px}
.dow-day{font-size:9px;color:rgba(255,255,255,.38);font-weight:700}

/* Category */
.cat-list{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:13px;padding:14px;display:flex;flex-direction:column;gap:10px}
.cat-row-top{display:flex;justify-content:space-between;align-items:center;margin-bottom:3px}
.cat-name{font-size:13px;font-weight:700}
.cat-amount{font-size:13px;font-weight:700;color:#4ADE80}
.cat-track{height:5px;background:rgba(255,255,255,.07);border-radius:999px;overflow:hidden}
.cat-fill-auto{height:100%;background:linear-gradient(90deg,#25D366,#4ADE80);border-radius:999px;transition:.5s}
.cat-fill-manual{height:100%;background:linear-gradient(90deg,#60a5fa,#93c5fd);border-radius:999px;transition:.5s}
.cat-meta{font-size:10px;color:rgba(255,255,255,.32);margin-top:3px;display:flex;gap:10px}
.nudge{background:rgba(37,211,102,.06);border:1px solid rgba(37,211,102,.14);border-radius:9px;padding:9px 12px;font-size:12px;color:rgba(255,255,255,.6);margin-top:5px;line-height:1.55}
.nudge a{color:#25D366;text-decoration:none;font-weight:700}

/* Manual entries */
.manual-list{display:flex;flex-direction:column;gap:7px}
.me-row{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:11px;padding:11px 14px;display:flex;justify-content:space-between;align-items:flex-start}
.me-row.income{border-color:rgba(37,211,102,.14)}
.me-row.expense{border-color:rgba(239,68,68,.1)}
.me-cat{font-size:12px;font-weight:700;margin-bottom:2px}
.me-desc{font-size:11px;color:rgba(255,255,255,.45);font-style:italic}
.me-date{font-size:10px;color:rgba(255,255,255,.32);margin-top:2px}
.me-amt-exp{font-size:15px;font-weight:900;color:#f87171}
.me-amt-inc{font-size:15px;font-weight:900;color:#4ADE80}
.del-btn{background:none;border:none;color:rgba(255,255,255,.25);font-size:18px;cursor:pointer;padding:0 0 0 10px;line-height:1}
.del-btn:hover{color:#f87171}

/* Quick actions */
.quick-actions{display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-bottom:24px}
.qa-btn{display:flex;flex-direction:column;align-items:center;gap:5px;padding:14px 8px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:14px;font-size:12px;font-weight:700;color:rgba(255,255,255,.7);cursor:pointer;text-decoration:none;transition:.15s}
.qa-btn:hover{background:rgba(255,255,255,.08)}
.qa-icon{font-size:22px}
@media(max-width:420px){.quick-actions{grid-template-columns:repeat(2,1fr)}}

/* Madeni */
.deni-alert{background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2);border-radius:12px;padding:12px 16px;margin-bottom:16px;display:flex;justify-content:space-between;align-items:center}
.deni-card{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:13px;padding:14px 16px;margin-bottom:8px}
.deni-prog-track{height:6px;background:rgba(255,255,255,.08);border-radius:999px;overflow:hidden;margin:8px 0 6px}
.deni-prog-fill{height:100%;background:linear-gradient(90deg,#25D366,#4ADE80);border-radius:999px}
.pay-deni-btn{display:inline-block;padding:6px 14px;background:linear-gradient(135deg,#25D366,#1aaa52);color:#fff;font-size:12px;font-weight:700;border-radius:8px;text-decoration:none;margin-top:6px}

/* Subscriptions in /me */
.sub-card{background:rgba(168,85,247,.05);border:1px solid rgba(168,85,247,.12);border-radius:13px;padding:14px 16px;margin-bottom:8px;display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap}
.sub-badge-active{background:rgba(37,211,102,.1);color:#4ADE80;font-size:11px;font-weight:700;padding:2px 9px;border-radius:999px}
.sub-badge-overdue{background:rgba(239,68,68,.1);color:#f87171;font-size:11px;font-weight:700;padding:2px 9px;border-radius:999px}
.renew-btn{display:inline-block;padding:6px 14px;background:linear-gradient(135deg,#25D366,#1aaa52);color:#fff;font-size:12px;font-weight:700;border-radius:8px;text-decoration:none}

/* Groups */
.grp-card{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:11px;padding:11px 14px;margin-bottom:7px;display:flex;justify-content:space-between;align-items:center}

/* Pay modal */
.pay-modal{display:none;position:fixed;inset:0;background:rgba(0,0,0,.7);z-index:200;align-items:flex-end;justify-content:center;padding:0}
.pay-modal.open{display:flex}
.pay-sheet{background:#141e24;border-radius:22px 22px 0 0;padding:28px 24px 40px;width:100%;max-width:480px}
.pay-sheet h3{font-size:17px;font-weight:900;margin-bottom:16px}
.pay-sheet input{width:100%;padding:13px 14px;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.12);border-radius:11px;color:#fff;font-size:15px;outline:none;font-family:inherit;margin-bottom:12px}
.pay-sheet input:focus{border-color:rgba(37,211,102,.4)}
.pay-sheet-btn{width:100%;padding:13px;background:linear-gradient(135deg,#25D366,#1aaa52);color:#fff;font-size:15px;font-weight:800;border:none;border-radius:12px;cursor:pointer}

/* Stamps */
.stamp-card{background:rgba(37,211,102,.04);border:1px solid rgba(37,211,102,.14);border-radius:13px;padding:13px 15px;margin-bottom:9px}
.sd{width:17px;height:17px;border-radius:50%;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.12);display:inline-flex;align-items:center;justify-content:center;font-size:8px;margin:0 2px 2px 0}
.sd.on{background:rgba(37,211,102,.22);border-color:#25D366;color:#4ADE80}

/* Sellers */
.sg{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:13px;overflow:hidden;margin-bottom:9px}
.sg-hdr{padding:12px 15px;display:flex;justify-content:space-between;align-items:center;cursor:pointer}
.sg-hdr:hover{background:rgba(255,255,255,.03)}
.sg-body{display:none;border-top:1px solid rgba(255,255,255,.05)}
.sg-body.open{display:block}
.pr{display:flex;justify-content:space-between;align-items:flex-start;padding:9px 15px;border-bottom:1px solid rgba(255,255,255,.04)}
.pr:last-child{border-bottom:none}

#results{display:none}
.not-found{text-align:center;padding:36px;color:rgba(255,255,255,.38);display:none}
</style>
</head>
<body>
<nav class="nav">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    <span style="font-size:13px;color:rgba(255,255,255,.45)">My Pregota</span>
</nav>

<div class="wrap">
    <h1>My Pregota</h1>
    <div class="sub">Payments, subscriptions, groups, madeni — all in one place.</div>

    {{-- Auth card (3 steps: phone → pin-entry/set → done) --}}
    <div class="auth-card" id="auth-card">

        {{-- Step 1: Phone --}}
        <div class="auth-step active" id="step-phone">
            <div class="step-title">Enter your number</div>
            <div class="step-sub">Your M-Pesa number gives access to your spending history. It's hashed — never stored in plain text.</div>
            <label class="field-label">M-Pesa Phone Number</label>
            <input type="tel" id="phone-input" class="field-input" placeholder="0712 345 678" autocomplete="tel">
            <button class="action-btn" onclick="checkPhone()">Continue →</button>
            <div class="err-msg" id="err-phone"></div>
        </div>

        {{-- Step 2a: Set PIN (new user) --}}
        <div class="auth-step" id="step-set-pin">
            <div class="step-title">🔐 Create your PIN</div>
            <div class="step-sub">First time here. Set a 4-digit PIN to secure your spending history. You'll need it every time you log in.</div>
            <div class="pin-hint">Choose a 4-digit PIN</div>
            <div class="pin-row" id="set-pin-boxes"></div>
            <div class="pin-hint" style="margin-top:18px">Confirm PIN</div>
            <div class="pin-row" id="confirm-pin-boxes"></div>
            <button class="action-btn" id="set-pin-btn" onclick="submitPin('set')" disabled>Create PIN →</button>
            <div class="err-msg" id="err-set-pin"></div>
            <span class="back-link" onclick="goBack()">← Use a different number</span>
        </div>

        {{-- Step 2b: Enter PIN (returning user) --}}
        <div class="auth-step" id="step-enter-pin">
            <div class="step-title">🔐 Enter your PIN</div>
            <div id="expired-notice" style="display:none;background:rgba(251,191,36,.08);border:1px solid rgba(251,191,36,.25);border-radius:9px;padding:10px 14px;font-size:13px;color:#fcd34d;margin-bottom:14px">⏱ Your 24-hour session expired. Re-enter your PIN to continue.</div>
            <div class="step-sub" id="pin-sub">Enter your 4-digit Pregota PIN to access your spending history.</div>
            <div class="pin-row" id="enter-pin-boxes"></div>
            <button class="action-btn" id="enter-pin-btn" onclick="submitPin('verify')" disabled>Unlock →</button>
            <div class="err-msg" id="err-enter-pin"></div>
            <span class="back-link" onclick="goBack()">← Use a different number</span>
        </div>

    </div>

    {{-- Log entry form (shown after auth) --}}
    <div class="log-section" id="log-section" style="display:none">
        <div class="log-toggle" onclick="toggleLog()">
            <div>
                <div style="font-size:14px;font-weight:700">➕ Log a Transaction</div>
                <div style="font-size:12px;color:rgba(255,255,255,.4);margin-top:2px">Record spending or income not yet on Pregota</div>
            </div>
            <div id="log-chevron" style="font-size:16px;color:rgba(255,255,255,.35)">▶</div>
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
                        <option value="transport">🚐 Transport</option>
                        <option value="supermarket">🛒 Supermarket</option>
                        <option value="food">🍽️ Restaurant / Food</option>
                        <option value="groceries">🥬 Groceries</option>
                        <option value="salon">💇 Salon & Beauty</option>
                        <option value="fashion">👗 Fashion</option>
                        <option value="electronics">📱 Electronics</option>
                        <option value="services">🛠 Services</option>
                        <option value="other">🏪 Other</option>
                    </select>
                </div>
                <div class="form-field">
                    <label>Date</label>
                    <input type="date" id="entry-date">
                </div>
            </div>
            <input type="text" class="desc-input" id="entry-desc" placeholder="Description — e.g. Lunch at Java, CBD → Westlands" maxlength="200">
            <button class="save-btn" onclick="saveEntry()">Save</button>
            <div class="entry-ok" id="entry-ok">✓ Saved!</div>
        </div>
    </div>

    {{-- Pay Modal --}}
    <div class="pay-modal" id="pay-modal" onclick="if(event.target===this)closePayModal()">
        <div class="pay-sheet">
            <h3>Pay a Seller</h3>
            <input type="text" id="pay-handle" placeholder="Seller handle — e.g. mama-pima" autocomplete="off">
            <button class="pay-sheet-btn" onclick="goToSeller()">Go to Pay Page →</button>
        </div>
    </div>

    <div id="results">
        {{-- Quick Actions --}}
        <div class="quick-actions">
            <button class="qa-btn" onclick="openPayModal()"><span class="qa-icon">💳</span>Pay</button>
            <a href="{{ route('gift.home') }}" class="qa-btn"><span class="qa-icon">🎁</span>Gift</a>
            <a href="{{ route('redeem') }}" class="qa-btn"><span class="qa-icon">🎟</span>Redeem</a>
            <a href="{{ route('seller.directory') }}" class="qa-btn"><span class="qa-icon">🔍</span>Find Sellers</a>
        </div>

        {{-- Madeni alert --}}
        <div class="deni-alert" id="deni-alert" style="display:none">
            <div>
                <div style="font-size:13px;font-weight:700;color:#f87171">⚠️ Outstanding Madeni</div>
                <div style="font-size:12px;color:rgba(255,255,255,.5);margin-top:2px">You owe <strong id="deni-total-label"></strong> — tap below to pay</div>
            </div>
        </div>

        {{-- Madeni section --}}
        <div id="deni-section" style="display:none">
            <div class="section-head">🧾 Madeni (What I Owe)</div>
            <div id="deni-list"></div>
        </div>

        {{-- Subscriptions section --}}
        <div id="subs-section" style="display:none">
            <div class="section-head">♻️ My Subscriptions</div>
            <div id="subs-list"></div>
        </div>

        {{-- Groups section --}}
        <div id="groups-section" style="display:none">
            <div class="section-head">🤝 Group Contributions</div>
            <div id="groups-list"></div>
        </div>

        <div class="income-bar" id="income-bar" style="display:none">
            <div>
                <div style="font-size:13px;font-weight:700">💰 Income / Received</div>
                <div style="font-size:11px;color:rgba(255,255,255,.35);margin-top:2px">Manually recorded</div>
            </div>
            <div style="font-size:18px;font-weight:900;color:#4ADE80" id="income-total">—</div>
        </div>

        <div class="kpis">
            <div class="kpi"><div class="kpi-label">All Time Spent</div><div class="kpi-val" id="kpi-total">—</div><div class="kpi-sub" id="kpi-count"></div></div>
            <div class="kpi"><div class="kpi-label">This Month</div><div class="kpi-val" id="kpi-month">—</div><div id="kpi-diff" class="kpi-sub"></div></div>
            <div class="kpi"><div class="kpi-label">This Week</div><div class="kpi-val" id="kpi-week">—</div></div>
            <div class="kpi"><div class="kpi-label">Avg / Tx</div><div class="kpi-val" id="kpi-avg">—</div></div>
        </div>

        <div class="section-head">Monthly Spending</div>
        <div class="chart-wrap"><div class="chart-bars" id="chart-bars"></div></div>

        <div class="section-head">When You Spend Most</div>
        <div class="dow-wrap" id="dow-bars"></div>

        <div class="section-head">By Category</div>
        <div class="cat-list" id="cat-list"></div>

        <div id="manual-section" style="display:none">
            <div class="section-head">✏️ Manual Entries</div>
            <div class="manual-list" id="manual-list"></div>
        </div>

        <div id="stamps-section" style="display:none">
            <div class="section-head">🎟 Stamp Cards</div>
            <div id="stamps-list"></div>
        </div>

        <div id="sellers-section" style="display:none">
            <div class="section-head">Pregota Payments — By Seller</div>
            <div id="sellers-list"></div>
        </div>

        <div id="not-found" class="not-found">
            <div style="font-size:34px;margin-bottom:10px">📒</div>
            <div style="font-size:16px;font-weight:700;margin-bottom:5px">Nothing here yet</div>
            <div style="font-size:13px;line-height:1.6;color:rgba(255,255,255,.5)">No Pregota payments found.<br>Use the form above to start logging your spending.</div>
        </div>
    </div>
</div>

<script>
const CSRF       = '{{ csrf_token() }}';
let activePhone  = '';
let activeHash   = null;
let entryType    = 'expense';
let setPinVal    = '';
let enterPinVal  = '';

document.getElementById('entry-date').value = new Date().toISOString().slice(0,10);

// ── PIN box builder ────────────────────────────────────────────────────────
function makePinBoxes(containerId, onComplete) {
    const wrap = document.getElementById(containerId);
    wrap.innerHTML = '';
    for (let i = 0; i < 4; i++) {
        const inp = document.createElement('input');
        inp.type      = 'password';
        inp.maxLength = 1;
        inp.inputMode = 'numeric';
        inp.pattern   = '[0-9]';
        inp.className = 'pin-box';
        inp.addEventListener('input', () => {
            inp.value = inp.value.replace(/\D/g, '');
            if (inp.value && inp.nextElementSibling) inp.nextElementSibling.focus();
            onComplete();
        });
        inp.addEventListener('keydown', e => {
            if (e.key === 'Backspace' && !inp.value && inp.previousElementSibling) {
                inp.previousElementSibling.focus();
            }
        });
        wrap.appendChild(inp);
    }
}

function getPinValue(containerId) {
    return Array.from(document.getElementById(containerId).querySelectorAll('input'))
        .map(i => i.value).join('');
}

function clearPin(containerId) {
    document.getElementById(containerId).querySelectorAll('input').forEach(i => i.value = '');
    document.getElementById(containerId).querySelector('input')?.focus();
}

// ── Step 1: phone ──────────────────────────────────────────────────────────
async function checkPhone() {
    const phone = document.getElementById('phone-input').value.trim();
    if (!phone) return;
    activePhone = phone;
    document.getElementById('expired-notice').style.display = 'none';

    const errEl = document.getElementById('err-phone');
    errEl.style.display = 'none';

    try {
        const res  = await fetch('{{ route('buyer.me.has-pin') }}?phone=' + encodeURIComponent(phone));
        const data = await res.json();

        if (!res.ok) { errEl.textContent = 'Invalid phone number.'; errEl.style.display = 'block'; return; }

        if (data.has_pin) {
            // Returning user — show PIN entry
            document.getElementById('pin-sub').textContent = 'Enter your 4-digit Pregota PIN for ' + phone + '.';
            makePinBoxes('enter-pin-boxes', () => {
                const v = getPinValue('enter-pin-boxes');
                document.getElementById('enter-pin-btn').disabled = v.length < 4;
                if (v.length === 4) submitPin('verify');
            });
            showStep('step-enter-pin');
        } else {
            // New user — show PIN creation
            makePinBoxes('set-pin-boxes', () => {
                const a = getPinValue('set-pin-boxes');
                const b = getPinValue('confirm-pin-boxes');
                document.getElementById('set-pin-btn').disabled = !(a.length === 4 && b.length === 4);
            });
            makePinBoxes('confirm-pin-boxes', () => {
                const a = getPinValue('set-pin-boxes');
                const b = getPinValue('confirm-pin-boxes');
                document.getElementById('set-pin-btn').disabled = !(a.length === 4 && b.length === 4);
                if (a.length === 4 && b.length === 4) submitPin('set');
            });
            showStep('step-set-pin');
        }
    } catch(e) {
        errEl.textContent = 'Something went wrong.'; errEl.style.display = 'block';
    }
}

// ── Step 2: PIN submit ─────────────────────────────────────────────────────
async function submitPin(mode) {
    const isSet   = mode === 'set';
    const pin     = getPinValue(isSet ? 'set-pin-boxes' : 'enter-pin-boxes');
    const errEl   = document.getElementById(isSet ? 'err-set-pin' : 'err-enter-pin');

    if (pin.length < 4) return;

    if (isSet) {
        const confirm = getPinValue('confirm-pin-boxes');
        if (pin !== confirm) {
            errEl.textContent = 'PINs do not match. Try again.';
            errEl.style.display = 'block';
            clearPin('confirm-pin-boxes');
            return;
        }
    }

    errEl.style.display = 'none';

    try {
        const res  = await fetch('{{ route('buyer.me.pin') }}', {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN': CSRF},
            body: JSON.stringify({phone: activePhone, pin}),
        });
        const data = await res.json();

        if (!res.ok || !data.success) {
            errEl.textContent = data.message || 'Incorrect PIN.';
            errEl.style.display = 'block';
            clearPin(isSet ? 'set-pin-boxes' : 'enter-pin-boxes');
            return;
        }

        // Verified — hide auth card, load data
        document.getElementById('auth-card').style.display = 'none';
        document.getElementById('log-section').style.display = 'block';
        loadData();
    } catch(e) {
        errEl.textContent = 'Something went wrong.'; errEl.style.display = 'block';
    }
}

function showStep(id) {
    document.querySelectorAll('.auth-step').forEach(s => s.classList.remove('active'));
    document.getElementById(id).classList.add('active');
}

function goBack() {
    showStep('step-phone');
    document.getElementById('phone-input').focus();
}

function showExpired() {
    // Hide results/log, re-show auth card at PIN entry step
    document.getElementById('log-section').style.display = 'none';
    document.getElementById('results').style.display     = 'none';
    document.getElementById('auth-card').style.display   = 'block';
    document.getElementById('expired-notice').style.display = 'block';
    document.getElementById('pin-sub').textContent = 'Re-enter your PIN for ' + activePhone + '.';
    makePinBoxes('enter-pin-boxes', () => {
        const v = getPinValue('enter-pin-boxes');
        document.getElementById('enter-pin-btn').disabled = v.length < 4;
        if (v.length === 4) submitPin('verify');
    });
    showStep('step-enter-pin');
}

document.getElementById('phone-input').addEventListener('keydown', e => {
    if (e.key === 'Enter') checkPhone();
});

// ── Log entry ──────────────────────────────────────────────────────────────
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
    const res  = await fetch('{{ route('buyer.me.entry') }}', {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN': CSRF},
        body: JSON.stringify({phone: activePhone, type: entryType, amount,
            category: document.getElementById('entry-category').value || null,
            description: document.getElementById('entry-desc').value || null,
            entry_date: date}),
    });
    const data = await res.json();
    if (!res.ok) { if (data.error === 'session_expired') showExpired(); return; }
    if (data.success) {
        document.getElementById('entry-ok').style.display = 'block';
        document.getElementById('entry-amount').value = '';
        document.getElementById('entry-desc').value = '';
        document.getElementById('entry-date').value = new Date().toISOString().slice(0,10);
        setTimeout(() => document.getElementById('entry-ok').style.display = 'none', 2000);
        loadData();
    }
}
async function deleteEntry(id) {
    if (!confirm('Remove this entry?')) return;
    const r = await fetch(`/me/entry/${id}`, {
        method: 'DELETE',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN': CSRF},
        body: JSON.stringify({phone: activePhone}),
    });
    if (!r.ok) { const rd = await r.json(); if (rd.error === 'session_expired') { showExpired(); return; } }
    loadData();
}

// ── Load data ──────────────────────────────────────────────────────────────
async function loadData() {
    const res = await fetch('{{ route('buyer.me.lookup') }}', {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN': CSRF},
        body: JSON.stringify({phone: activePhone}),
    });
    const d = await res.json();

    if (!res.ok) {
        if (d.error === 'session_expired') showExpired();
        return;
    }

    document.getElementById('results').style.display = 'block';

    if (!d.found) { document.getElementById('not-found').style.display = 'block'; return; }
    document.getElementById('not-found').style.display = 'none';

    // KPIs
    document.getElementById('kpi-total').textContent = 'KES ' + d.total_kes.toLocaleString();
    document.getElementById('kpi-count').textContent = d.total_count + ' transaction' + (d.total_count!==1?'s':'');
    document.getElementById('kpi-month').textContent = 'KES ' + d.this_month.toLocaleString();
    document.getElementById('kpi-week').textContent  = 'KES ' + d.this_week.toLocaleString();
    document.getElementById('kpi-avg').textContent   = 'KES ' + d.avg_tx.toLocaleString();
    const diffEl = document.getElementById('kpi-diff');
    if (d.last_month > 0) {
        const pct = Math.round(((d.this_month - d.last_month) / d.last_month) * 100);
        diffEl.className   = pct >= 0 ? 'kpi-up' : 'kpi-down';
        diffEl.textContent = (pct >= 0 ? '▲ ' : '▼ ') + Math.abs(pct) + '% vs last month';
    }
    if (d.total_income > 0) {
        document.getElementById('income-bar').style.display = 'flex';
        document.getElementById('income-total').textContent = 'KES ' + d.total_income.toLocaleString();
    }

    // Monthly chart
    const maxM = Math.max(...d.by_month.map(m => m.total), 1);
    const nowK = new Date().toISOString().slice(0,7);
    document.getElementById('chart-bars').innerHTML = d.by_month.map(m => {
        const h = Math.max(3, Math.round((m.total/maxM)*80));
        return `<div class="bar-col"><div class="bar-inner ${m.month===nowK?'bar-this':''}" style="height:${h}px"><div class="tt">KES ${m.total.toLocaleString()}<br>${m.count} entries</div></div><div class="bar-label">${m.label.split(' ')[0]}</div></div>`;
    }).join('');

    // DOW
    const maxD = Math.max(...d.by_dow.map(x=>x.total), 1);
    document.getElementById('dow-bars').innerHTML = d.by_dow.map(x => {
        const h = Math.max(2, Math.round((x.total/maxD)*46));
        return `<div class="dow-col"><div class="dow-track"><div class="dow-bar" style="height:${h}px"></div></div><div class="dow-day">${x.day}</div></div>`;
    }).join('');

    // Categories
    const maxC = d.by_category[0]?.total||1;
    document.getElementById('cat-list').innerHTML = d.by_category.map(c => {
        const pct = Math.round((c.total/maxC)*100);
        const manual = c.manual > 0 && c.auto === 0;
        return `<div>
            <div class="cat-row-top"><div class="cat-name">${c.emoji} ${c.category.charAt(0).toUpperCase()+c.category.slice(1)}</div><div><span class="cat-amount">KES ${c.total.toLocaleString()}</span> <span style="font-size:10px;color:rgba(255,255,255,.3)">${c.count} entries</span></div></div>
            <div class="cat-track"><div class="${manual?'cat-fill-manual':'cat-fill-auto'}" style="width:${pct}%"></div></div>
            <div class="cat-meta">${c.auto>0?`<span>✅ KES ${c.auto.toLocaleString()} Pregota</span>`:''} ${c.manual>0?`<span>✏️ KES ${c.manual.toLocaleString()} manual</span>`:''}</div>
            ${manual ? `<div class="nudge">Paying <strong>${c.category}</strong> manually? Ask the seller to join Pregota — it'll track automatically. <a href="{{ route('seller.landing') }}" target="_blank">Share pregota.com/for-sellers →</a></div>` : ''}
        </div>`;
    }).join('');

    // Manual entries
    if (d.manual?.length > 0) {
        document.getElementById('manual-section').style.display = 'block';
        document.getElementById('manual-list').innerHTML = d.manual.map(e =>
            `<div class="me-row ${e.type}" id="me-${e.id}">
                <div>
                    <div class="me-cat">${e.emoji} ${e.category.charAt(0).toUpperCase()+e.category.slice(1)} <span style="font-size:10px;font-weight:400;color:rgba(255,255,255,.35)">${e.type}</span></div>
                    ${e.description?`<div class="me-desc">${e.description}</div>`:''}
                    <div class="me-date">${e.date}</div>
                </div>
                <div style="display:flex;align-items:center">
                    <div class="${e.type==='expense'?'me-amt-exp':'me-amt-inc'}">${e.type==='expense'?'−':'+'}KES ${e.amount.toLocaleString()}</div>
                    <button class="del-btn" onclick="deleteEntry(${e.id})">×</button>
                </div>
            </div>`
        ).join('');
    }

    // Stamps
    if (d.stamps?.length > 0) {
        document.getElementById('stamps-section').style.display = 'block';
        document.getElementById('stamps-list').innerHTML = d.stamps.map(s => {
            const dots = Array.from({length:s.stamps_required},(_,i)=>`<span class="sd ${i<s.stamp_count?'on':''}">✓</span>`).join('');
            return `<div class="stamp-card"><div style="display:flex;justify-content:space-between;margin-bottom:7px"><div style="font-size:14px;font-weight:800">${s.business_name}</div><div style="font-size:10px;color:rgba(255,255,255,.35)">${s.stamps_required} stamps = reward</div></div><div style="margin-bottom:5px">${dots}</div><div style="font-size:12px;color:rgba(255,255,255,.45)">${s.stamp_count}/${s.stamps_required} · ${s.stamps_required-s.stamp_count} more for: <strong>${s.reward||'reward'}</strong></div>${s.reward_pending?'<div style="color:#fbbf24;font-size:12px;font-weight:700;margin-top:5px">🎉 Reward ready!</div>':''}</div>`;
        }).join('');
    }

    // Madeni
    if (d.deni?.length > 0) {
        document.getElementById('deni-alert').style.display = 'flex';
        document.getElementById('deni-total-label').textContent = 'KES ' + d.total_deni.toLocaleString();
        document.getElementById('deni-section').style.display = 'block';
        document.getElementById('deni-list').innerHTML = d.deni.map(dn => {
            const pct = dn.original_amount > 0 ? Math.round((dn.amount_paid / dn.original_amount) * 100) : 0;
            return `<div class="deni-card">
                <div style="display:flex;justify-content:space-between;align-items:flex-start">
                    <div>
                        <div style="font-size:14px;font-weight:800">${dn.creditor}</div>
                        <div style="font-size:12px;color:rgba(255,255,255,.5);margin-top:2px">${dn.description}</div>
                    </div>
                    <div style="text-align:right;font-size:16px;font-weight:900;color:#f87171">KES ${dn.balance.toLocaleString()}<div style="font-size:10px;color:rgba(255,255,255,.35);font-weight:400">remaining</div></div>
                </div>
                <div class="deni-prog-track"><div class="deni-prog-fill" style="width:${pct}%"></div></div>
                <div style="display:flex;justify-content:space-between;align-items:center">
                    <div style="font-size:11px;color:rgba(255,255,255,.35)">KES ${dn.amount_paid.toLocaleString()} paid of KES ${dn.original_amount.toLocaleString()}${dn.due_date ? ' · Due '+dn.due_date : ''}</div>
                    <a href="${dn.pay_link}" class="pay-deni-btn">Pay Now →</a>
                </div>
            </div>`;
        }).join('');
    }

    // Subscriptions
    if (d.subscriptions?.length > 0) {
        document.getElementById('subs-section').style.display = 'block';
        document.getElementById('subs-list').innerHTML = d.subscriptions.map(s =>
            `<div class="sub-card">
                <div>
                    <div style="font-size:14px;font-weight:800">${s.plan_name}</div>
                    <div style="font-size:12px;color:rgba(255,255,255,.45)">${s.business_name} · KES ${s.amount.toLocaleString()}/${s.frequency}</div>
                    ${s.next_due ? `<div style="font-size:11px;color:rgba(255,255,255,.35);margin-top:3px">Next due: ${s.next_due}</div>` : ''}
                </div>
                <div style="display:flex;flex-direction:column;align-items:flex-end;gap:6px">
                    <span class="${s.status === 'overdue' ? 'sub-badge-overdue' : 'sub-badge-active'}">${s.status === 'overdue' ? '⚠️ Overdue' : '✅ Active'}</span>
                    ${s.is_due ? `<a href="/subscription/reminder/${s.reminder_token}" class="renew-btn">Renew →</a>` : ''}
                </div>
            </div>`
        ).join('');
    }

    // Group contributions
    if (d.group_payments?.length > 0) {
        document.getElementById('groups-section').style.display = 'block';
        document.getElementById('groups-list').innerHTML = d.group_payments.map(g =>
            `<div class="grp-card">
                <div>
                    <div style="font-size:14px;font-weight:700">🤝 ${g.group_name}</div>
                    <div style="font-size:11px;color:rgba(255,255,255,.4);margin-top:2px">${g.period} · ${g.date}</div>
                </div>
                <div style="font-size:15px;font-weight:900;color:#4ADE80">KES ${g.amount.toLocaleString()}</div>
            </div>`
        ).join('');
    }

    // Sellers
    if (d.grouped?.length > 0) {
        document.getElementById('sellers-section').style.display = 'block';
        document.getElementById('sellers-list').innerHTML = d.grouped.map((g,i)=>
            `<div class="sg"><div class="sg-hdr" onclick="document.getElementById('sg-${i}').classList.toggle('open')"><div><div style="font-size:14px;font-weight:800">${g.business_name}</div><div style="font-size:11px;font-family:monospace;color:rgba(255,255,255,.35);margin-top:2px">pregota.com/pay/${g.handle}</div></div><div style="text-align:right"><div style="font-size:16px;font-weight:900;color:#4ADE80">KES ${g.total_spent.toLocaleString()}</div><div style="font-size:11px;color:rgba(255,255,255,.35)">${g.count} payment${g.count!==1?'s':''} ▶</div></div></div><div class="sg-body" id="sg-${i}">${g.payments.map(p=>`<div class="pr"><div><div style="font-size:13px;font-weight:700">KES ${p.amount.toLocaleString()}${p.tip_amount>0?` <span style="font-size:10px;color:rgba(255,255,255,.35)">+${p.tip_amount} tip</span>`:''}</div><div style="font-size:11px;color:rgba(255,255,255,.35);margin-top:1px">${p.date}</div>${p.note?`<div style="font-size:10px;color:rgba(255,255,255,.35);font-style:italic">"${p.note}"</div>`:''}</div>${p.receipt_url?`<a href="${p.receipt_url}" target="_blank" style="font-size:11px;color:#a78bfa;font-family:monospace;text-decoration:none">${p.receipt_number}</a>`:''}</div>`).join('')}</div></div>`
        ).join('');
    }
}

// ── Quick actions ─────────────────────────────────────────────────────────
function openPayModal() {
    document.getElementById('pay-modal').classList.add('open');
    setTimeout(() => document.getElementById('pay-handle').focus(), 100);
}
function closePayModal() {
    document.getElementById('pay-modal').classList.remove('open');
}
function goToSeller() {
    const handle = document.getElementById('pay-handle').value.trim().toLowerCase();
    if (!handle) return;
    window.location.href = '/pay/' + handle;
}
document.getElementById('pay-handle').addEventListener('keydown', e => {
    if (e.key === 'Enter') goToSeller();
    if (e.key === 'Escape') closePayModal();
});

// ── Inactivity auto-lock (5 minutes) ──────────────────────────────────────
const IDLE_MS = 5 * 60 * 1000;
let idleTimer  = null;
let dataLoaded = false;

function resetIdle() {
    if (!dataLoaded) return;
    clearTimeout(idleTimer);
    idleTimer = setTimeout(lockScreen, IDLE_MS);
}

function lockScreen() {
    dataLoaded = false;
    document.getElementById('log-section').style.display  = 'none';
    document.getElementById('results').style.display      = 'none';
    document.getElementById('auth-card').style.display    = 'block';
    document.getElementById('expired-notice').style.display = 'block';
    document.getElementById('expired-notice').textContent = '🔒 Locked after 5 minutes of inactivity. Re-enter your PIN.';
    document.getElementById('pin-sub').textContent        = 'Re-enter your PIN for ' + activePhone + '.';
    makePinBoxes('enter-pin-boxes', () => {
        const v = getPinValue('enter-pin-boxes');
        document.getElementById('enter-pin-btn').disabled = v.length < 4;
        if (v.length === 4) submitPin('verify');
    });
    showStep('step-enter-pin');
}

['click','keydown','touchstart','scroll'].forEach(ev =>
    document.addEventListener(ev, resetIdle, {passive: true})
);

// Start idle timer only after data is first loaded
const _origLoadData = loadData;
loadData = async function() {
    await _origLoadData();
    dataLoaded = true;
    resetIdle();
};
</script>
</body>
</html>
