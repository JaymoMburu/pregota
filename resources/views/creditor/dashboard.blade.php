<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ session('creditor_name') }} — Business Dashboard · Pregota</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800;900&display=swap" rel="stylesheet">
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Plus Jakarta Sans',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh}
.nav{padding:14px 20px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.07);position:sticky;top:0;background:#0B141A;z-index:10}
.logo{font-size:20px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.nav-right{display:flex;align-items:center;gap:12px}
.wrap{max-width:620px;margin:0 auto;padding:24px 16px 80px}

.greeting{margin-bottom:24px}
.greeting-name{font-size:22px;font-weight:900}
.greeting-sub{font-size:13px;color:rgba(255,255,255,.45);margin-top:3px}

.stats{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:24px}
.stat{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);border-radius:12px;padding:14px;text-align:center}
.stat-val{font-size:18px;font-weight:900}
.stat-val.red{color:#f87171}
.stat-val.green{color:#4ADE80}
.stat-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:rgba(255,255,255,.35);margin-top:3px}

/* Customers quick-add section */
.customers-wrap{margin-bottom:24px}
.section-head{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.35);margin-bottom:10px;display:flex;align-items:center;gap:10px}
.section-head::after{content:'';flex:1;height:1px;background:rgba(255,255,255,.06)}

.customer-chips{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:10px}
.customer-chip{display:flex;flex-direction:column;align-items:center;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.09);border-radius:12px;padding:10px 14px;cursor:pointer;transition:.15s;min-width:90px;text-align:center}
.customer-chip:hover,.customer-chip.active{background:rgba(239,68,68,.08);border-color:rgba(239,68,68,.3)}
.chip-phone{font-size:12px;font-weight:700;color:rgba(255,255,255,.8)}
.chip-balance{font-size:11px;color:#f87171;margin-top:2px}
.chip-tabs{font-size:10px;color:rgba(255,255,255,.3);margin-top:1px}

.quick-form{display:none;background:rgba(239,68,68,.05);border:1px solid rgba(239,68,68,.18);border-radius:13px;padding:16px;margin-bottom:10px}
.quick-form-title{font-size:12px;font-weight:700;color:rgba(255,255,255,.5);text-transform:uppercase;letter-spacing:.07em;margin-bottom:12px}
.quick-form-phone{font-size:13px;font-weight:700;color:#f87171;margin-bottom:12px}
.quick-row{display:flex;gap:8px;flex-wrap:wrap}
.quick-input{flex:1;min-width:140px;padding:10px 12px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:9px;color:#fff;font-size:14px;outline:none;font-family:inherit}
.quick-input:focus{border-color:rgba(239,68,68,.4)}
.quick-submit{padding:10px 20px;background:linear-gradient(135deg,#dc2626,#ef4444);border:none;border-radius:9px;color:#fff;font-size:14px;font-weight:700;cursor:pointer;white-space:nowrap}
.quick-result{display:none;margin-top:10px;padding:10px 14px;background:rgba(37,211,102,.07);border:1px solid rgba(37,211,102,.18);border-radius:9px;font-size:13px;color:#4ADE80}

.new-btn{width:100%;padding:14px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.1);color:rgba(255,255,255,.6);font-size:14px;font-weight:700;border-radius:13px;cursor:pointer;margin-bottom:20px;text-decoration:none;display:block;text-align:center;transition:.15s}
.new-btn:hover{background:rgba(255,255,255,.07);color:rgba(255,255,255,.85)}
.new-btn-primary{background:linear-gradient(135deg,#dc2626,#ef4444);border:none;color:#fff}
.new-btn-primary:hover{transform:translateY(-1px);box-shadow:0 6px 20px rgba(239,68,68,.3)}

.deni-card{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:13px;padding:16px;margin-bottom:10px}
.deni-card.open{border-left:3px solid #ef4444}
.deni-card.partial{border-left:3px solid #fbbf24}
.deni-card.settled{border-left:3px solid #4ADE80;opacity:.7}
.deni-top{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:10px}
.deni-desc{font-size:15px;font-weight:700}
.deni-date{font-size:11px;color:rgba(255,255,255,.3);margin-top:2px}
.badge{display:inline-flex;padding:2px 9px;border-radius:999px;font-size:11px;font-weight:700}
.badge.open{background:rgba(239,68,68,.12);color:#f87171}
.badge.partial{background:rgba(251,191,36,.12);color:#fbbf24}
.badge.settled{background:rgba(74,222,128,.12);color:#4ADE80}

.deni-amounts{display:flex;gap:16px;margin-bottom:12px}
.amt-block{flex:1}
.amt-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:rgba(255,255,255,.35);margin-bottom:3px}
.amt-val{font-size:16px;font-weight:900}
.amt-val.red{color:#f87171}
.amt-val.green{color:#4ADE80}

.prog-track{height:5px;background:rgba(255,255,255,.07);border-radius:999px;overflow:hidden;margin-bottom:12px}
.prog-fill{height:100%;background:linear-gradient(90deg,#25D366,#4ADE80);border-radius:999px}

.deni-actions{display:flex;gap:8px;flex-wrap:wrap}
.action-btn{font-size:12px;padding:6px 14px;border-radius:7px;cursor:pointer;font-weight:600;text-decoration:none;border:none;font-family:inherit}
.btn-link{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);color:rgba(255,255,255,.7)}
.btn-charge{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.2);color:#f87171}
.btn-wa{background:rgba(37,211,102,.1);border:1px solid rgba(37,211,102,.2);color:#4ADE80}

.charge-form{display:none;margin-top:12px;padding-top:12px;border-top:1px solid rgba(255,255,255,.06)}
.charge-row{display:flex;gap:8px;flex-wrap:wrap}
.charge-input{flex:1;min-width:140px;padding:9px 12px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:8px;color:#fff;font-size:13px;outline:none;font-family:inherit}
.charge-input:focus{border-color:rgba(239,68,68,.4)}
.charge-submit{padding:9px 16px;background:linear-gradient(135deg,#dc2626,#ef4444);border:none;border-radius:8px;color:#fff;font-size:13px;font-weight:700;cursor:pointer}

.empty{text-align:center;padding:40px 20px;color:rgba(255,255,255,.3);font-size:14px}

/* Tabs */
.tab-bar{display:flex;gap:4px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);border-radius:12px;padding:4px;margin-bottom:20px}
.tab-btn{flex:1;padding:9px;border-radius:9px;font-size:13px;font-weight:700;cursor:pointer;text-align:center;background:none;border:none;color:rgba(255,255,255,.45);font-family:inherit;transition:.15s}
.tab-btn.active{background:rgba(255,255,255,.08);color:#fff}

/* Ledger */
.ledger-summary{display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;margin-bottom:20px}
.led-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);border-radius:12px;padding:14px;text-align:center}
.led-val{font-size:17px;font-weight:900}
.led-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:rgba(255,255,255,.35);margin-top:3px}
.led-entry{display:flex;justify-content:space-between;align-items:center;padding:11px 0;border-bottom:1px solid rgba(255,255,255,.05)}
.led-entry:last-child{border-bottom:none}
.led-cat{font-size:11px;color:rgba(255,255,255,.4);margin-top:2px}
.led-amount{font-size:15px;font-weight:900}
.led-del{background:none;border:none;color:rgba(255,255,255,.2);cursor:pointer;font-size:16px;padding:2px 6px}
.led-del:hover{color:#f87171}
.add-entry-form{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:13px;padding:16px;margin-bottom:16px}
.ae-row{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:8px}
.ae-input{width:100%;padding:10px 12px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:9px;color:#fff;font-size:14px;outline:none;font-family:inherit}
.ae-input:focus{border-color:rgba(239,68,68,.4)}
.ae-input option{color:#111;background:#fff}
select option{color:#111;background:#fff}
.ae-type{display:flex;gap:8px;margin-bottom:8px}
.ae-type label{flex:1;display:flex;align-items:center;gap:6px;padding:9px 12px;border-radius:9px;cursor:pointer;font-size:13px;font-weight:600;border:1px solid rgba(255,255,255,.1);background:rgba(255,255,255,.04);color:rgba(255,255,255,.6)}

/* Notification bell */
.bell-btn{position:relative;background:none;border:none;color:rgba(255,255,255,.5);cursor:pointer;padding:4px 8px;font-size:20px}
.bell-badge{position:absolute;top:0;right:2px;background:#ef4444;color:#fff;font-size:9px;font-weight:900;border-radius:999px;padding:1px 5px;min-width:16px;text-align:center}
.notif-panel{display:none;position:absolute;right:0;top:44px;width:300px;background:#1a2730;border:1px solid rgba(255,255,255,.1);border-radius:14px;box-shadow:0 12px 40px rgba(0,0,0,.5);z-index:100;max-height:360px;overflow-y:auto}
.notif-item{padding:12px 16px;border-bottom:1px solid rgba(255,255,255,.06);font-size:13px}
.notif-item:last-child{border-bottom:none}
.notif-amount{font-weight:900;color:#4ADE80}
.notif-desc{color:rgba(255,255,255,.5);font-size:12px;margin-top:2px}
.notif-time{font-size:11px;color:rgba(255,255,255,.3);margin-top:2px}
.notif-empty{padding:20px;text-align:center;color:rgba(255,255,255,.3);font-size:13px}

@media(max-width:480px){
    .stats{grid-template-columns:repeat(3,1fr)}
    .stat-val{font-size:15px}
    .ledger-summary{grid-template-columns:1fr 1fr}
    .ae-row{grid-template-columns:1fr}
}
</style>
</head>
<body>

<nav class="nav">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    <div class="nav-right" style="position:relative">
        <div style="position:relative">
            <button class="bell-btn" onclick="toggleNotif()" id="bell-btn">🔔
                @if($todayPayments > 0)<span class="bell-badge">{{ $todayPayments }}</span>@endif
            </button>
            <div class="notif-panel" id="notif-panel">
                <div style="padding:12px 16px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.35);border-bottom:1px solid rgba(255,255,255,.06)">Recent Payments</div>
                <div id="notif-list"><div class="notif-empty">Loading…</div></div>
            </div>
        </div>
        <span style="font-size:12px;color:rgba(255,255,255,.4)">{{ session('creditor_name') }}</span>
        <form method="POST" action="{{ route('creditor.logout') }}" style="display:inline">
            @csrf
            <button type="submit" style="background:none;border:none;color:rgba(255,255,255,.35);font-size:12px;cursor:pointer">Logout</button>
        </form>
    </div>
</nav>

<div class="wrap">

    @if(session('charge_added'))
    <div style="background:rgba(37,211,102,.07);border:1px solid rgba(37,211,102,.18);border-radius:10px;padding:12px 16px;font-size:13px;color:#4ADE80;margin-bottom:16px">✓ Charge added — tab updated.</div>
    @endif

    {{-- Payout settings --}}
    <div style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:13px;padding:14px 16px;margin-bottom:20px">
        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.35);margin-bottom:10px">💰 Receive Payments To</div>
        <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
            <div id="payout-display" style="flex:1;font-size:14px;font-weight:700;color:{{ session('creditor_payout_till') ? '#4ADE80' : 'rgba(255,255,255,.7)' }}">
                {{ session('creditor_payout_till') ? '🏪 Till ' . session('creditor_payout_till') : '📱 M-Pesa / Pochi (your login number)' }}
            </div>
            <button onclick="toggleTillEdit()" style="font-size:12px;padding:5px 12px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:7px;color:rgba(255,255,255,.55);cursor:pointer;font-family:inherit" id="payout-edit-btn">Change</button>
        </div>
        <div id="till-edit-form" style="display:none;margin-top:12px">
            <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center">
                <input type="text" id="till-input" placeholder="Till number (e.g. 123456) — blank = use M-Pesa" maxlength="7" inputmode="numeric"
                    value="{{ session('creditor_payout_till','') }}"
                    style="flex:1;padding:10px 12px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:9px;color:#fff;font-size:14px;outline:none;font-family:inherit;min-width:180px">
                <button onclick="saveTill()" style="padding:10px 18px;background:linear-gradient(135deg,#dc2626,#ef4444);border:none;border-radius:9px;color:#fff;font-size:13px;font-weight:700;cursor:pointer">Save</button>
                <button onclick="toggleTillEdit()" style="background:none;border:none;color:rgba(255,255,255,.3);cursor:pointer;font-size:12px;padding:6px">Cancel</button>
            </div>
            <div style="font-size:11px;color:rgba(255,255,255,.3);margin-top:6px">Leave blank to receive via M-Pesa/Pochi instead of Till.</div>
        </div>
    </div>

    <div class="greeting">
        <div class="greeting-name">{{ session('creditor_name') }}</div>
        <div class="greeting-sub">{{ $openCount }} open {{ Str::plural('tab', $openCount) }} · Business Dashboard</div>
    </div>


    <div class="stats">
        <div class="stat">
            <div class="stat-val red">KES {{ number_format($totalOutstanding) }}</div>
            <div class="stat-label">Outstanding</div>
        </div>
        <div class="stat">
            <div class="stat-val green">KES {{ number_format($totalCollected) }}</div>
            <div class="stat-label">Collected</div>
        </div>
        <div class="stat">
            <div class="stat-val">{{ $openDeni->count() + $settledDeni->count() }}</div>
            <div class="stat-label">Total Tabs</div>
        </div>
    </div>

    {{-- Tab bar --}}
    <div class="tab-bar">
        <button class="tab-btn active" id="tab-deni-btn"   onclick="showTab('deni')">🧾 Madeni</button>
        <button class="tab-btn"        id="tab-ledger-btn" onclick="showTab('ledger')">📒 Ledger</button>
        <button class="tab-btn"        id="tab-payout-btn" onclick="showTab('payout')">💸 Pay Out</button>
    </div>

    {{-- LEDGER TAB --}}
    <div id="tab-ledger" style="display:none">

        {{-- P&L Summary --}}
        <div class="ledger-summary">
            <div class="led-card">
                <div class="led-val" style="color:#4ADE80">KES {{ number_format($todayIncome) }}</div>
                <div class="led-label">Today's Income</div>
            </div>
            <div class="led-card">
                <div class="led-val" style="color:#f87171">KES {{ number_format($todayExpense) }}</div>
                <div class="led-label">Today's Expenses</div>
            </div>
            <div class="led-card">
                <div class="led-val" style="color:{{ ($todayIncome - $todayExpense) >= 0 ? '#4ADE80' : '#f87171' }}">
                    KES {{ number_format(abs($todayIncome - $todayExpense)) }}
                </div>
                <div class="led-label">Today's {{ ($todayIncome - $todayExpense) >= 0 ? 'Profit' : 'Loss' }}</div>
            </div>
        </div>
        <div style="display:flex;gap:10px;margin-bottom:20px">
            <div style="flex:1;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);border-radius:10px;padding:12px;text-align:center">
                <div style="font-size:14px;font-weight:900;color:#4ADE80">KES {{ number_format($monthIncome) }}</div>
                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:rgba(255,255,255,.3);margin-top:3px">30-Day Income</div>
            </div>
            <div style="flex:1;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);border-radius:10px;padding:12px;text-align:center">
                <div style="font-size:14px;font-weight:900;color:#f87171">KES {{ number_format($monthExpense) }}</div>
                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:rgba(255,255,255,.3);margin-top:3px">30-Day Expenses</div>
            </div>
            <div style="flex:1;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);border-radius:10px;padding:12px;text-align:center">
                <div style="font-size:14px;font-weight:900;color:{{ ($monthIncome-$monthExpense)>=0?'#4ADE80':'#f87171' }}">KES {{ number_format(abs($monthIncome-$monthExpense)) }}</div>
                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:rgba(255,255,255,.3);margin-top:3px">30-Day {{ ($monthIncome-$monthExpense)>=0?'Profit':'Loss' }}</div>
            </div>
        </div>

        {{-- Add entry form --}}
        <div class="add-entry-form">
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.35);margin-bottom:10px">Record Entry</div>
            <div class="ae-type">
                <label style="border-color:rgba(37,211,102,.25);background:rgba(37,211,102,.06)">
                    <input type="radio" name="ae-type" value="income" id="ae-income" checked style="accent-color:#4ADE80"> 💰 Income
                </label>
                <label style="border-color:rgba(239,68,68,.25);background:rgba(239,68,68,.06)">
                    <input type="radio" name="ae-type" value="expense" id="ae-expense" style="accent-color:#f87171"> 💸 Expense
                </label>
            </div>
            <div class="ae-row">
                <div>
                    <select id="ae-category" class="ae-input">
                        <option value="">Category</option>
                    </select>
                </div>
                <div>
                    <input type="number" id="ae-amount" class="ae-input" placeholder="Amount (KES)" min="1">
                </div>
            </div>
            <input type="text" id="ae-desc" class="ae-input" placeholder="Description — e.g. Unga 5kg, maziwa, chapati sales" maxlength="300" style="margin-bottom:8px">
            <div style="display:flex;gap:8px;align-items:center">
                <input type="date" id="ae-date" class="ae-input" style="flex:1">
                <button onclick="saveLedgerEntry()" style="padding:10px 20px;background:linear-gradient(135deg,#dc2626,#ef4444);border:none;border-radius:9px;color:#fff;font-size:14px;font-weight:700;cursor:pointer;white-space:nowrap">Add →</button>
            </div>
            <div id="ae-ok" style="display:none;margin-top:8px;font-size:13px;color:#4ADE80">✓ Saved!</div>
        </div>

        {{-- Entry list filter --}}
        <div style="display:flex;gap:6px;margin-bottom:12px">
            <button onclick="filterLedger('all')"     id="lf-all"     style="flex:1;padding:8px;border-radius:9px;font-size:12px;font-weight:700;cursor:pointer;border:1px solid rgba(255,255,255,.1);background:rgba(255,255,255,.08);color:#fff;font-family:inherit">All</button>
            <button onclick="filterLedger('income')"  id="lf-income"  style="flex:1;padding:8px;border-radius:9px;font-size:12px;font-weight:700;cursor:pointer;border:1px solid rgba(37,211,102,.2);background:rgba(37,211,102,.05);color:#4ADE80;font-family:inherit">💰 Income</button>
            <button onclick="filterLedger('expense')" id="lf-expense" style="flex:1;padding:8px;border-radius:9px;font-size:12px;font-weight:700;cursor:pointer;border:1px solid rgba(239,68,68,.2);background:rgba(239,68,68,.05);color:#f87171;font-family:inherit">💸 Expenses</button>
        </div>

        {{-- Entry list --}}
        <div id="ledger-list">
        @forelse($ledger as $entry)
        <div class="led-entry" id="led-{{ $entry->id }}" data-type="{{ $entry->type }}">
            <div style="flex:1">
                <div style="font-size:14px;font-weight:700">
                    {{ $entry->source === 'deni_payment' ? '🧾 ' : ($entry->type === 'income' ? '💰 ' : '💸 ') }}
                    {{ $entry->description ?: ucfirst(str_replace('_',' ',$entry->category)) }}
                </div>
                <div class="led-cat">
                    {{ ucfirst(str_replace('_',' ',$entry->category)) }}
                    @if($entry->source === 'deni_payment') · Auto from deni payment @endif
                    · {{ $entry->entry_date->format('d M') }}
                </div>
            </div>
            <div style="text-align:right;display:flex;align-items:center;gap:8px">
                <div class="led-amount" style="color:{{ $entry->type==='income'?'#4ADE80':'#f87171' }}">
                    {{ $entry->type === 'income' ? '+' : '-' }}KES {{ number_format($entry->amount) }}
                </div>
                @if($entry->source === 'manual')
                <button class="led-del" onclick="deleteLedgerEntry({{ $entry->id }})">×</button>
                @endif
            </div>
        </div>
        @empty
        <div class="empty" id="ledger-empty">No entries yet. Record your first income or expense above.</div>
        @endforelse
        </div>
    </div>

    {{-- PAY OUT TAB --}}
    <div id="tab-payout" style="display:none">

        {{-- Saved contacts --}}
        <div class="section-head">Saved Payees</div>
        <div class="customer-chips" id="payee-chips">
            @foreach($contacts as $c)
            <div class="customer-chip" id="payee-chip-{{ $c->id }}"
                onclick="selectPayee({{ $c->id }}, '{{ addslashes($c->name) }}', '{{ $c->till ? '🏪 Till '.$c->till : ($c->phone_masked ?? '📱 Phone') }}')">
                <div class="chip-phone" style="font-size:13px;color:#fff">{{ $c->name }}</div>
                <div style="font-size:10px;color:rgba(255,255,255,.35);margin-top:2px">
                    {{ $c->till ? '🏪 Till '.$c->till : ($c->phone_masked ?? '📱 Phone') }}
                </div>
                <div style="margin-top:6px;display:flex;gap:6px;justify-content:center">
                    <span onclick="event.stopPropagation();editPayee({{ $c->id }})"
                          style="font-size:11px;color:rgba(255,255,255,.4);cursor:pointer;padding:2px 6px;border:1px solid rgba(255,255,255,.12);border-radius:5px">✏️ Edit</span>
                </div>
            </div>
            @endforeach
            <div class="customer-chip" onclick="toggleAddPayee()" id="add-payee-chip" style="border-style:dashed;color:rgba(255,255,255,.4)">
                <div style="font-size:20px">＋</div>
                <div style="font-size:11px;margin-top:2px">Add Payee</div>
            </div>
        </div>

        {{-- Add / Edit payee form --}}
        <div id="add-payee-form" style="display:none;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:13px;padding:16px;margin-bottom:16px">
            <div id="np-form-title" style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.35);margin-bottom:10px">New Payee</div>
            <input type="hidden" id="np-edit-id" value="">
            <div class="ae-row" style="margin-bottom:8px">
                <input type="text" id="np-name" class="ae-input" placeholder="Name — e.g. Amina, Kariuki Supplier" maxlength="100">
                <input type="text" id="np-till" class="ae-input" placeholder="Till number (or leave blank)" inputmode="numeric" maxlength="7">
            </div>
            <input type="tel" id="np-phone" class="ae-input" placeholder="Phone (if no Till) — e.g. 0712345678" style="margin-bottom:8px">
            <div style="display:flex;gap:8px">
                <button id="np-save-btn" onclick="savePayee()" style="padding:10px 20px;background:linear-gradient(135deg,#dc2626,#ef4444);border:none;border-radius:9px;color:#fff;font-size:14px;font-weight:700;cursor:pointer">Save Payee</button>
                <button onclick="cancelPayeeForm()" style="background:none;border:none;color:rgba(255,255,255,.3);cursor:pointer;font-size:13px;padding:6px">Cancel</button>
            </div>
            <div id="np-err" style="display:none;margin-top:8px;font-size:13px;color:#f87171"></div>
        </div>

        {{-- Pay form --}}
        <div id="pay-form" style="display:none;background:rgba(239,68,68,.04);border:1px solid rgba(239,68,68,.15);border-radius:13px;padding:16px;margin-bottom:16px">
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.35);margin-bottom:6px">Pay</div>
            <div id="pay-to-label" style="font-size:15px;font-weight:700;color:#f87171;margin-bottom:12px"></div>
            <div class="ae-row" style="margin-bottom:8px">
                <input type="number" id="pay-amount" class="ae-input" placeholder="Amount (KES)" min="10">
                <select id="pay-category" class="ae-input">
                    <option value="salary">💼 Salary / Wages</option>
                    <option value="stock">📦 Stock / Supplier</option>
                    <option value="utilities">💡 Utilities</option>
                    <option value="rent">🏠 Rent</option>
                    <option value="other">📦 Other</option>
                </select>
            </div>
            <input type="text" id="pay-desc" class="ae-input" placeholder="Note — e.g. May salary, Unga 10 bags" maxlength="200" style="margin-bottom:8px">
            <div style="display:flex;gap:8px;align-items:center">
                <button onclick="initiatePayout()" id="pay-btn" style="flex:1;padding:12px;background:linear-gradient(135deg,#dc2626,#ef4444);border:none;border-radius:9px;color:#fff;font-size:15px;font-weight:700;cursor:pointer">Pay Now via M-Pesa →</button>
                <button onclick="clearPayee()" style="background:none;border:none;color:rgba(255,255,255,.3);cursor:pointer;font-size:13px;padding:6px">Cancel</button>
            </div>
            <div id="pay-status" style="display:none;margin-top:10px;padding:10px 14px;border-radius:9px;font-size:13px"></div>
        </div>

        {{-- Recent payouts --}}
        @if($recentPayouts->isNotEmpty())
        <div class="section-head" style="margin-top:4px">Recent Payouts</div>
        @foreach($recentPayouts as $p)
        <div class="led-entry">
            <div style="flex:1">
                <div style="font-size:14px;font-weight:700">
                    {{ $p->status === 'confirmed' ? '✅' : ($p->status === 'failed' ? '❌' : '⏳') }}
                    {{ $p->recipient_name }}
                </div>
                <div class="led-cat">
                    {{ ucfirst($p->category) }} · {{ $p->created_at->format('d M') }}
                    @if($p->description) · {{ $p->description }} @endif
                    @if($p->receipt_number) · {{ $p->receipt_number }} @endif
                </div>
            </div>
            <div class="led-amount" style="color:{{ $p->status==='confirmed'?'#f87171':($p->status==='failed'?'rgba(255,255,255,.3)':'#fbbf24') }}">
                -KES {{ number_format($p->amount) }}
            </div>
        </div>
        @endforeach
        @else
        <div class="empty" style="padding:30px 20px">
            <div style="font-size:28px;margin-bottom:10px">💸</div>
            <div>No payouts yet.</div>
            <div style="font-size:12px;margin-top:4px">Add a payee above to get started.</div>
        </div>
        @endif

    </div>

    {{-- DENI TAB --}}
    <div id="tab-deni">

    {{-- Quick-add from known customers --}}
    @if($customers->isNotEmpty())
    <div class="customers-wrap">
        <div class="section-head">Quick Add Deni</div>
        <div class="customer-chips">
            @foreach($customers as $c)
            <div class="customer-chip" onclick="selectCustomer('{{ $c['phone_hash'] }}', '{{ $c['display'] }}', '{{ $c['phone_masked'] }}', '{{ addslashes($c['phone_encrypted']) }}', '{{ addslashes($c['name'] ?? '') }}')" id="chip-{{ $c['phone_hash'] }}">
                <div class="chip-phone" style="{{ $c['name'] ? 'font-size:13px;color:#fff' : '' }}">{{ $c['display'] }}</div>
                @if($c['name'])
                <div style="font-size:10px;color:rgba(255,255,255,.35);margin-top:1px">{{ $c['phone_masked'] }}</div>
                @endif
                @if($c['outstanding'] > 0)
                <div class="chip-balance">KES {{ number_format($c['outstanding']) }}</div>
                @endif
                <div class="chip-tabs">{{ $c['open_tabs'] }} open</div>
            </div>
            @endforeach
        </div>

        <div class="quick-form" id="quick-form">
            <div class="quick-form-title">New Deni</div>
            <div class="quick-form-phone" id="quick-phone-label"></div>
            <div class="quick-row">
                <input class="quick-input" type="text" id="quick-desc" placeholder="What for? e.g. Uji na mandazi" maxlength="300" style="flex:2">
                <input class="quick-input" type="number" id="quick-amount" placeholder="KES" min="1" max="500000" style="max-width:100px">
                <button class="quick-submit" onclick="submitQuickDeni()">Add →</button>
            </div>
            <div class="quick-result" id="quick-result"></div>
        </div>
    </div>
    @endif

    {{-- New deni (full form) --}}
    <a href="{{ route('deni.create') }}" class="new-btn {{ $customers->isEmpty() ? 'new-btn-primary' : '' }}">
        {{ $customers->isEmpty() ? '+ Record a New Deni' : '+ New Customer / Full Form' }}
    </a>

    {{-- Open / Partial tabs --}}
    @if($openDeni->isNotEmpty())
    <div class="section-head">Open Tabs</div>
    @foreach($openDeni->sortByDesc('created_at') as $d)
    @php $pct = $d->original_amount > 0 ? round(($d->amount_paid / $d->original_amount) * 100) : 0; @endphp
    <div class="deni-card {{ $d->status }}">
        <div class="deni-top">
            <div>
                <div class="deni-desc">{{ $d->description }}</div>
                <div class="deni-date">
                    @if($d->debtor_name)<span style="color:rgba(255,255,255,.55);font-weight:600">{{ $d->debtor_name }}</span> · @endif
                    {{ $d->created_at->format('d M Y') }}{{ $d->due_date ? ' · Due ' . $d->due_date->format('d M') : '' }}
                </div>
            </div>
            <span class="badge {{ $d->status }}">{{ ucfirst($d->status) }}</span>
        </div>

        <div class="deni-amounts">
            <div class="amt-block">
                <div class="amt-label">Balance</div>
                <div class="amt-val red">KES {{ number_format($d->balance()) }}</div>
            </div>
            <div class="amt-block">
                <div class="amt-label">Paid</div>
                <div class="amt-val green">KES {{ number_format($d->amount_paid) }}</div>
            </div>
            <div class="amt-block">
                <div class="amt-label">Total</div>
                <div class="amt-val">KES {{ number_format($d->original_amount) }}</div>
            </div>
        </div>

        <div class="prog-track"><div class="prog-fill" style="width:{{ $pct }}%"></div></div>

        @php $payUrl = url('/deni/' . $d->debtor_token); $waMsg = session('creditor_name') . ' amekuandikia deni ya KES ' . number_format($d->original_amount) . ' kwa: ' . $d->description . '. Lipa hapa: ' . $payUrl; @endphp
        <div class="deni-actions">
            <button class="action-btn btn-link" onclick="navigator.clipboard.writeText('{{ $payUrl }}');this.textContent='✓ Copied!'">📋 Copy Link</button>
            <a href="https://wa.me/?text={{ rawurlencode($waMsg) }}" target="_blank" class="action-btn btn-wa">💬 WhatsApp</a>
            <button class="action-btn btn-charge" onclick="toggleCharge('{{ $d->admin_token }}')">+ Add Charge</button>
        </div>

        <div class="charge-form" id="charge-{{ $d->admin_token }}">
            <form method="POST" action="{{ route('deni.charge', $d->admin_token) }}">
                @csrf
                <input type="hidden" name="from" value="creditor">
                <div class="charge-row">
                    <input class="charge-input" type="text" name="description" placeholder="What for? e.g. Uji + mandazi" maxlength="200" required>
                    <input class="charge-input" type="number" name="amount" placeholder="KES" min="1" style="max-width:100px" required>
                    <button class="charge-submit" type="submit">Add</button>
                    <button type="button" onclick="toggleCharge('{{ $d->admin_token }}')" style="background:none;border:none;color:rgba(255,255,255,.3);cursor:pointer;font-size:12px;padding:6px">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    @endforeach
    @endif

    {{-- Settled tabs --}}
    @if($settledDeni->isNotEmpty())
    <div class="section-head" style="margin-top:24px">Settled</div>
    @foreach($settledDeni->sortByDesc('created_at') as $d)
    <div class="deni-card settled">
        <div class="deni-top">
            <div>
                <div class="deni-desc">{{ $d->description }}</div>
                <div class="deni-date">{{ $d->created_at->format('d M Y') }}</div>
            </div>
            <span class="badge settled">✅ Settled</span>
        </div>
        <div style="font-size:13px;color:rgba(255,255,255,.4)">KES {{ number_format($d->amount_paid) }} collected in full.</div>
    </div>
    @endforeach
    @endif

    @if($openDeni->isEmpty() && $settledDeni->isEmpty())
    <div class="empty">
        <div style="font-size:32px;margin-bottom:12px">🧾</div>
        <div>No madeni recorded yet.</div>
        <div style="font-size:12px;margin-top:6px">Tap "Record a New Deni" to get started.</div>
    </div>
    @endif

</div>

</div>{{-- /.wrap --}}

<script>
let activeHash = null;
let activeEncrypted = null;
let activeName = null;

function selectCustomer(hash, display, maskedPhone, encrypted, name) {
    document.querySelectorAll('.customer-chip').forEach(c => c.classList.remove('active'));
    const chip = document.getElementById('chip-' + hash);

    if (activeHash === hash) {
        activeHash = null;
        activeEncrypted = null;
        activeName = null;
        document.getElementById('quick-form').style.display = 'none';
        return;
    }

    activeHash = hash;
    activeEncrypted = encrypted;
    activeName = name || null;
    chip.classList.add('active');
    document.getElementById('quick-phone-label').textContent = display + (name ? ' · ' + maskedPhone : '');
    document.getElementById('quick-desc').value = '';
    document.getElementById('quick-amount').value = '';
    document.getElementById('quick-result').style.display = 'none';
    document.getElementById('quick-form').style.display = 'block';
    document.getElementById('quick-desc').focus();
}

function submitQuickDeni() {
    const desc   = document.getElementById('quick-desc').value.trim();
    const amount = document.getElementById('quick-amount').value.trim();
    const result = document.getElementById('quick-result');

    if (!desc || !amount || !activeHash) return;

    const btn = document.querySelector('.quick-submit');
    btn.disabled = true;
    btn.textContent = '...';

    fetch('{{ route('deni.quick') }}', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        body: JSON.stringify({
            debtor_phone_hash:      activeHash,
            debtor_phone_encrypted: activeEncrypted,
            debtor_name:            activeName,
            description:            desc,
            original_amount:        parseInt(amount),
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            result.style.display = 'block';
            result.textContent   = '✓ Deni of KES ' + parseInt(amount).toLocaleString() + ' recorded. Reloading…';
            setTimeout(() => location.reload(), 1200);
        } else {
            result.style.display = 'block';
            result.style.color   = '#f87171';
            result.textContent   = data.message || 'Error. Try again.';
            btn.disabled = false;
            btn.textContent = 'Add →';
        }
    })
    .catch(() => {
        result.style.display = 'block';
        result.style.color   = '#f87171';
        result.textContent   = 'Network error. Try again.';
        btn.disabled = false;
        btn.textContent = 'Add →';
    });
}

function toggleCharge(token) {
    const el = document.getElementById('charge-' + token);
    if (el) el.style.display = el.style.display === 'none' ? 'block' : 'none';
}

function toggleTillEdit() {
    const form = document.getElementById('till-edit-form');
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}

async function saveTill() {
    const till = document.getElementById('till-input').value.trim();
    const res  = await fetch('{{ route("creditor.payout.till") }}', {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body: JSON.stringify({till: till || null}),
    });
    if (res.ok) {
        document.getElementById('payout-display').innerHTML = till
            ? '🏪 Till ' + till
            : '📱 M-Pesa / Pochi (your login number)';
        document.getElementById('payout-display').style.color = till ? '#4ADE80' : 'rgba(255,255,255,.7)';
        document.getElementById('till-edit-form').style.display = 'none';
    }
}

// ── Tabs ──────────────────────────────────────────────────────────
function showTab(tab) {
    ['deni','ledger','payout'].forEach(t => {
        document.getElementById('tab-' + t).style.display      = t === tab ? '' : 'none';
        document.getElementById('tab-' + t + '-btn').className = 'tab-btn' + (t === tab ? ' active' : '');
    });
}

// ── Pay Out ───────────────────────────────────────────────────────
let activePayeeId = null;
let payPollTimer  = null;

function selectPayee(id, name, detail) {
    document.querySelectorAll('.customer-chip').forEach(c => c.classList.remove('active'));
    if (activePayeeId === id) { activePayeeId = null; clearPayee(); return; }
    activePayeeId = id;
    document.getElementById('payee-chip-' + id).classList.add('active');
    document.getElementById('pay-to-label').textContent = name + (detail ? '  ·  ' + detail : '');
    document.getElementById('pay-form').style.display = 'block';
    document.getElementById('pay-status').style.display = 'none';
    document.getElementById('pay-amount').focus();
}

function clearPayee() {
    activePayeeId = null;
    document.querySelectorAll('.customer-chip').forEach(c => c.classList.remove('active'));
    document.getElementById('pay-form').style.display = 'none';
}

function toggleAddPayee() {
    cancelPayeeForm();
    const form = document.getElementById('add-payee-form');
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}

function cancelPayeeForm() {
    document.getElementById('np-edit-id').value = '';
    document.getElementById('np-name').value    = '';
    document.getElementById('np-till').value    = '';
    document.getElementById('np-phone').value   = '';
    document.getElementById('np-form-title').textContent = 'New Payee';
    document.getElementById('np-save-btn').textContent   = 'Save Payee';
    document.getElementById('np-err').style.display = 'none';
    document.getElementById('add-payee-form').style.display = 'none';
}

async function editPayee(id) {
    const res  = await fetch(`/creditor/contacts/${id}`, {
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json'},
    });
    const data = await res.json();
    if (!data.id) return;
    document.getElementById('np-edit-id').value  = id;
    document.getElementById('np-name').value     = data.name || '';
    document.getElementById('np-till').value     = data.till || '';
    document.getElementById('np-phone').value    = data.phone || '';
    document.getElementById('np-form-title').textContent = 'Edit Payee';
    document.getElementById('np-save-btn').textContent   = 'Update Payee';
    document.getElementById('np-err').style.display = 'none';
    document.getElementById('add-payee-form').style.display = 'block';
    document.getElementById('np-name').focus();
}

function showPayeeErr(msg) {
    const err = document.getElementById('np-err');
    err.style.display = 'block';
    err.textContent   = msg;
    err.scrollIntoView({behavior:'smooth', block:'nearest'});
}

async function savePayee() {
    const name   = document.getElementById('np-name').value.trim();
    const till   = document.getElementById('np-till').value.trim();
    const phone  = document.getElementById('np-phone').value.trim();
    const editId = document.getElementById('np-edit-id').value;

    document.getElementById('np-err').style.display = 'none';

    if (!name)            { showPayeeErr('Name is required.'); return; }
    if (!till && !phone)  { showPayeeErr('Enter a till number or a phone number.'); return; }
    if (phone && !/^(0[17]\d{8}|\+?254[17]\d{8})$/.test(phone)) {
        showPayeeErr('Phone must be 10 digits e.g. 0712 345 678'); return;
    }
    if (till && !/^\d{5,7}$/.test(till)) {
        showPayeeErr('Till number must be 5–7 digits.'); return;
    }

    const url    = editId ? `/creditor/contacts/${editId}` : '{{ route("creditor.contact.save") }}';
    const method = editId ? 'PUT' : 'POST';

    const res  = await fetch(url, {
        method,
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body: JSON.stringify({name, till: till||null, phone: phone||null}),
    });
    const data = await res.json();
    if (data.id) {
        const detail = data.till ? '🏪 Till ' + data.till : (data.phone_masked || '📱 Phone');
        const safeName = name.replace(/'/g,"\\'");
        if (editId) {
            // Update existing chip in place
            const chip = document.getElementById('payee-chip-' + editId);
            if (chip) {
                chip.setAttribute('onclick', `selectPayee(${editId}, '${safeName}', '${detail}')`);
                chip.innerHTML = `<div class="chip-phone" style="font-size:13px;color:#fff">${name}</div>
                    <div style="font-size:10px;color:rgba(255,255,255,.35);margin-top:2px">${detail}</div>
                    <div style="margin-top:6px;display:flex;gap:6px;justify-content:center">
                        <span onclick="event.stopPropagation();editPayee(${editId})" style="font-size:11px;color:rgba(255,255,255,.4);cursor:pointer;padding:2px 6px;border:1px solid rgba(255,255,255,.12);border-radius:5px">✏️ Edit</span>
                    </div>`;
            }
        } else {
            const chips  = document.getElementById('payee-chips');
            const addBtn = document.getElementById('add-payee-chip');
            const chip   = document.createElement('div');
            chip.className = 'customer-chip';
            chip.id = 'payee-chip-' + data.id;
            chip.setAttribute('onclick', `selectPayee(${data.id}, '${safeName}', '${detail}')`);
            chip.innerHTML = `<div class="chip-phone" style="font-size:13px;color:#fff">${name}</div>
                <div style="font-size:10px;color:rgba(255,255,255,.35);margin-top:2px">${detail}</div>
                <div style="margin-top:6px;display:flex;gap:6px;justify-content:center">
                    <span onclick="event.stopPropagation();editPayee(${data.id})" style="font-size:11px;color:rgba(255,255,255,.4);cursor:pointer;padding:2px 6px;border:1px solid rgba(255,255,255,.12);border-radius:5px">✏️ Edit</span>
                </div>`;
            chips.insertBefore(chip, addBtn);
        }
        cancelPayeeForm();
    } else {
        const msg = data.error
            || (data.errors ? Object.values(data.errors).flat()[0] : null)
            || 'Error saving. Try again.';
        showPayeeErr(msg);
    }
}

async function initiatePayout() {
    if (!activePayeeId) return;
    const amount  = parseInt(document.getElementById('pay-amount').value) || 0;
    const cat     = document.getElementById('pay-category').value;
    const desc    = document.getElementById('pay-desc').value.trim();
    const status  = document.getElementById('pay-status');
    const btn     = document.getElementById('pay-btn');

    if (!amount || amount < 10) {
        status.style.display = 'block';
        status.style.background = 'rgba(239,68,68,.1)';
        status.style.color = '#f87171';
        status.textContent = 'Enter an amount (minimum KES 10).';
        return;
    }

    btn.disabled = true; btn.textContent = 'Sending STK Push…';
    status.style.display = 'none';

    const res  = await fetch('{{ route("creditor.payout.initiate") }}', {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body: JSON.stringify({contact_id: activePayeeId, amount, category: cat, description: desc||null}),
    });
    const data = await res.json();

    if (data.success) {
        status.style.display = 'block';
        status.style.background = 'rgba(251,191,36,.07)';
        status.style.color = '#fbbf24';
        status.textContent = '📲 Check your phone — approve the M-Pesa prompt…';
        pollPayout(data.checkout_request_id, 0);
    } else {
        btn.disabled = false; btn.textContent = 'Pay Now via M-Pesa →';
        status.style.display = 'block';
        status.style.background = 'rgba(239,68,68,.1)';
        status.style.color = '#f87171';
        status.textContent = data.message || 'Failed. Try again.';
    }
}

function pollPayout(checkoutId, attempts) {
    if (attempts > 30) {
        document.getElementById('pay-status').textContent = 'Timed out. Check your M-Pesa messages.';
        document.getElementById('pay-btn').disabled = false;
        document.getElementById('pay-btn').textContent = 'Pay Now via M-Pesa →';
        return;
    }
    payPollTimer = setTimeout(async () => {
        let data;
        try {
            const res = await fetch(`{{ route("creditor.payout.poll") }}?checkout_request_id=${checkoutId}`);
            data = await res.json();
        } catch (e) {
            pollPayout(checkoutId, attempts + 1);
            return;
        }
        const status = document.getElementById('pay-status');
        if (data.status === 'confirmed') {
            status.style.background = 'rgba(37,211,102,.07)';
            status.style.color = '#4ADE80';
            status.textContent = `✅ KES ${Number(data.amount).toLocaleString()} sent to ${data.name}! Logged as expense.`;
            document.getElementById('pay-btn').disabled = false;
            document.getElementById('pay-btn').textContent = 'Pay Now via M-Pesa →';
            document.getElementById('pay-amount').value = '';
            document.getElementById('pay-desc').value = '';
            setTimeout(() => location.reload(), 2000);
        } else if (data.status === 'failed') {
            status.style.background = 'rgba(239,68,68,.1)';
            status.style.color = '#f87171';
            status.textContent = '❌ Payment failed or cancelled.';
            document.getElementById('pay-btn').disabled = false;
            document.getElementById('pay-btn').textContent = 'Pay Now via M-Pesa →';
        } else {
            if (data._query_debug) {
                status.style.display = 'block';
                status.style.background = 'rgba(255,255,255,.05)';
                status.style.color = '#facc15';
                status.textContent = 'Safaricom query: RC=' + (data._query_debug.ResultCode ?? '?') + ' ' + (data._query_debug.ResultDesc ?? data._query_debug.errorMessage ?? JSON.stringify(data._query_debug));
            }
            pollPayout(checkoutId, attempts + 1);
        }
    }, 3000);
}

// ── Notifications ─────────────────────────────────────────────────
let notifLoaded = false;
function toggleNotif() {
    const panel = document.getElementById('notif-panel');
    const isOpen = panel.style.display === 'block';
    panel.style.display = isOpen ? 'none' : 'block';
    if (!isOpen && !notifLoaded) {
        notifLoaded = true;
        fetch('{{ route("creditor.notifications") }}')
            .then(r => r.json())
            .then(data => {
                const list = document.getElementById('notif-list');
                if (!data.payments || !data.payments.length) {
                    list.innerHTML = '<div class="notif-empty">No payments yet.</div>';
                    return;
                }
                list.innerHTML = data.payments.map(p =>
                    `<div class="notif-item">
                        <div><span class="notif-amount">+KES ${Number(p.amount).toLocaleString()}</span>${p.debtor_name ? ' from ' + p.debtor_name : ''}</div>
                        <div class="notif-desc">${p.description || ''}</div>
                        <div class="notif-time">${p.paid_at}${p.receipt ? ' · ' + p.receipt : ''}</div>
                    </div>`
                ).join('');
            })
            .catch(() => {
                document.getElementById('notif-list').innerHTML = '<div class="notif-empty">Could not load.</div>';
            });
    }
}
document.addEventListener('click', e => {
    const panel = document.getElementById('notif-panel');
    const btn   = document.getElementById('bell-btn');
    if (panel && !panel.contains(e.target) && !btn.contains(e.target)) {
        panel.style.display = 'none';
    }
});

// ── Ledger categories ─────────────────────────────────────────────
const INCOME_CATS = [
    ['deni_payment','🧾 Deni Payment'],['salary','💼 Salary'],['business','🏪 Business Sales'],
    ['freelance','💻 Freelance'],['rental','🏠 Rental Income'],['friends_family','🤝 Friends & Family'],
    ['side_hustle','⚡ Side Hustle'],['other','✨ Other'],
];
const EXPENSE_CATS = [
    ['stock','📦 Stock / Restocking'],['transport','🚐 Transport'],['food','🍽️ Food & Meals'],
    ['groceries','🥬 Groceries'],['airtime','📶 Airtime & Data'],['rent','🏠 Rent / Stall Fee'],
    ['health','💊 Health'],['wages','👷 Wages Paid'],['utilities','💡 Utilities'],
    ['savings','🏦 Savings'],['other','📦 Other'],
];

function refreshCategories() {
    const isIncome = document.getElementById('ae-income').checked;
    const cats     = isIncome ? INCOME_CATS : EXPENSE_CATS;
    const sel      = document.getElementById('ae-category');
    sel.innerHTML  = '<option value="">— Category —</option>' +
        cats.map(([v, l]) => `<option value="${v}">${l}</option>`).join('');
}

document.getElementById('ae-income').addEventListener('change',  refreshCategories);
document.getElementById('ae-expense').addEventListener('change', refreshCategories);
refreshCategories();
document.getElementById('ae-date').valueAsDate = new Date();

// ── Save ledger entry ─────────────────────────────────────────────
async function saveLedgerEntry() {
    const isIncome = document.getElementById('ae-income').checked;
    const cat      = document.getElementById('ae-category').value;
    const amount   = parseInt(document.getElementById('ae-amount').value) || 0;
    const desc     = document.getElementById('ae-desc').value.trim();
    const date     = document.getElementById('ae-date').value;
    const ok       = document.getElementById('ae-ok');

    if (!cat || !amount || !date) {
        ok.style.display = 'block'; ok.style.color = '#f87171';
        ok.textContent   = 'Fill in category, amount and date.';
        return;
    }

    const res = await fetch('{{ route("creditor.ledger.save") }}', {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body: JSON.stringify({
            type: isIncome ? 'income' : 'expense', category: cat,
            amount, description: desc || null, entry_date: date,
        }),
    });
    const data = await res.json();
    if (data.success) {
        ok.style.display = 'block'; ok.style.color = '#4ADE80'; ok.textContent = '✓ Saved!';
        document.getElementById('ae-amount').value = '';
        document.getElementById('ae-desc').value   = '';
        refreshCategories();
        const sign     = isIncome ? '+' : '-';
        const color    = isIncome ? '#4ADE80' : '#f87171';
        const catLabel = (isIncome ? INCOME_CATS : EXPENSE_CATS).find(([v]) => v === cat)?.[1] || cat;
        const today    = new Date(date).toLocaleDateString('en-GB', {day:'2-digit', month:'short'});
        const entryType = isIncome ? 'income' : 'expense';
        const row      = document.createElement('div');
        row.className  = 'led-entry'; row.id = 'led-' + data.id;
        row.dataset.type = entryType;
        row.style.display = (activeLedgerFilter === 'all' || activeLedgerFilter === entryType) ? '' : 'none';
        row.innerHTML  = `
            <div style="flex:1">
                <div style="font-size:14px;font-weight:700">${isIncome ? '💰 ' : '💸 '}${desc || catLabel}</div>
                <div class="led-cat">${catLabel} · ${today}</div>
            </div>
            <div style="text-align:right;display:flex;align-items:center;gap:8px">
                <div class="led-amount" style="color:${color}">${sign}KES ${amount.toLocaleString()}</div>
                <button class="led-del" onclick="deleteLedgerEntry(${data.id})">×</button>
            </div>`;
        const list  = document.getElementById('ledger-list');
        const empty = list.querySelector('.empty');
        if (empty) empty.remove();
        list.prepend(row);
        setTimeout(() => { ok.style.display = 'none'; }, 2200);
    } else {
        ok.style.display = 'block'; ok.style.color = '#f87171';
        ok.textContent   = data.errors ? Object.values(data.errors).flat().join(' ') : 'Error saving.';
    }
}

// ── Ledger filter ─────────────────────────────────────────────────
let activeLedgerFilter = 'all';
function filterLedger(type) {
    activeLedgerFilter = type;
    document.querySelectorAll('#ledger-list .led-entry').forEach(row => {
        row.style.display = (type === 'all' || row.dataset.type === type) ? '' : 'none';
    });
    ['all','income','expense'].forEach(t => {
        const btn = document.getElementById('lf-' + t);
        if (!btn) return;
        btn.style.opacity = t === type ? '1' : '0.45';
        btn.style.fontWeight = t === type ? '900' : '700';
    });
}

// ── Delete ledger entry ───────────────────────────────────────────
async function deleteLedgerEntry(id) {
    if (!confirm('Delete this entry?')) return;
    const res = await fetch(`/creditor/ledger/${id}`, {
        method: 'DELETE',
        headers: {'X-CSRF-TOKEN':'{{ csrf_token() }}'},
    });
    const data = await res.json();
    if (data.deleted) {
        const row = document.getElementById('led-' + id);
        if (row) row.remove();
    }
}
</script>
</body>
</html>
