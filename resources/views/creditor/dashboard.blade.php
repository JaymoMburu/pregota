<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ session('creditor_name') }} — Deni Dashboard · Pregota</title>
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh}
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

@media(max-width:480px){
    .stats{grid-template-columns:repeat(3,1fr)}
    .stat-val{font-size:15px}
}
</style>
</head>
<body>

<nav class="nav">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    <div class="nav-right">
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
        <div class="greeting-sub">{{ $openCount }} open {{ Str::plural('tab', $openCount) }} · Your Deni Dashboard</div>
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
</script>
</body>
</html>
