<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $payLink->business_name }} — Pay Link Dashboard</title>
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh}
.nav{padding:14px 24px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.07);background:rgba(0,0,0,.3)}
.logo{font-size:18px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.nav-right{display:flex;gap:12px;align-items:center}
.main{padding:24px;max-width:760px;margin:0 auto}

.welcome{margin-bottom:28px}
.welcome h1{font-size:22px;font-weight:900;margin-bottom:4px}
.welcome p{font-size:13px;color:rgba(255,255,255,.6)}

.link-box{background:linear-gradient(135deg,rgba(37,211,102,.12),rgba(26,170,82,.06));border:1px solid rgba(37,211,102,.25);border-radius:18px;padding:24px;margin-bottom:24px}
.link-label{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#25D366;margin-bottom:10px}
.link-url{font-family:monospace;font-size:16px;font-weight:900;color:#fff;background:rgba(0,0,0,.3);border-radius:10px;padding:12px 16px;display:flex;align-items:center;justify-content:space-between;gap:12px}
.copy-btn{background:rgba(37,211,102,.15);border:1px solid rgba(37,211,102,.3);color:#25D366;font-size:12px;font-weight:700;padding:6px 14px;border-radius:8px;cursor:pointer;white-space:nowrap;transition:.15s}
.copy-btn:hover{background:rgba(37,211,102,.25)}
.link-actions{display:flex;gap:10px;margin-top:14px;flex-wrap:wrap}
.action-btn{display:inline-flex;align-items:center;gap:6px;padding:9px 16px;border-radius:9px;font-size:13px;font-weight:700;cursor:pointer;transition:.15s;text-decoration:none;border:none}
.action-btn.qr{background:rgba(96,165,250,.12);border:1px solid rgba(96,165,250,.25);color:#60a5fa}
.action-btn.qr:hover{background:rgba(96,165,250,.2)}
.action-btn.share{background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.12);color:rgba(255,255,255,.85)}
.action-btn.share:hover{background:rgba(255,255,255,.12)}

/* QR modal */
.qr-modal{display:none;position:fixed;inset:0;background:rgba(0,0,0,.7);z-index:100;align-items:center;justify-content:center;padding:24px}
.qr-modal.open{display:flex}
.qr-card{background:#141e24;border:1px solid rgba(255,255,255,.1);border-radius:20px;padding:32px;max-width:360px;width:100%;text-align:center}
.qr-card h3{font-size:16px;font-weight:900;margin-bottom:4px}
.qr-card p{font-size:12px;color:rgba(255,255,255,.55);margin-bottom:20px}
#qr-canvas{border-radius:12px;background:#fff;padding:12px}
.qr-btns{display:flex;gap:10px;margin-top:20px;justify-content:center}
.qr-dl{padding:10px 20px;background:linear-gradient(135deg,#25D366,#1aaa52);color:#fff;font-weight:700;font-size:13px;border:none;border-radius:10px;cursor:pointer}
.qr-close{padding:10px 20px;background:rgba(255,255,255,.07);color:rgba(255,255,255,.75);font-weight:700;font-size:13px;border:1px solid rgba(255,255,255,.1);border-radius:10px;cursor:pointer}

.kpis{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:24px}
@media(max-width:480px){.kpis{grid-template-columns:1fr 1fr}}
.kpi{background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:16px}
.kpi-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.65);margin-bottom:6px}
.kpi-val{font-size:22px;font-weight:900}
.kpi-val.green{color:#25D366}
.kpi-val.blue{color:#60a5fa}

.table-wrap{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:16px;overflow:hidden}
.table-header{padding:14px 18px;border-bottom:1px solid rgba(255,255,255,.07);display:flex;justify-content:space-between;align-items:center}
.table-header h2{font-size:14px;font-weight:700}
table{width:100%;border-collapse:collapse;font-size:13px}
th{padding:9px 14px;text-align:left;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.55);background:rgba(255,255,255,.03)}
td{padding:10px 14px;border-top:1px solid rgba(255,255,255,.05);color:rgba(255,255,255,.8)}
tr:hover td{background:rgba(255,255,255,.03)}
.badge{display:inline-flex;padding:2px 10px;border-radius:999px;font-size:11px;font-weight:700}
.badge.confirmed{background:rgba(34,197,94,.15);color:#4ade80}
.badge.pending{background:rgba(251,191,36,.15);color:#fbbf24}
.badge.failed{background:rgba(239,68,68,.12);color:#fca5a5}
.empty{text-align:center;padding:36px;color:rgba(255,255,255,.45);font-size:13px}
.stamp-section{background:rgba(37,211,102,.05);border:1px solid rgba(37,211,102,.18);border-radius:16px;padding:20px;margin-bottom:24px}
.stamp-section h2{font-size:14px;font-weight:700;color:#4ADE80;margin-bottom:4px}
.stamp-section p{font-size:12px;color:rgba(255,255,255,.55);margin-bottom:16px;line-height:1.55}
.form-row{display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap}
.form-group-sm{display:flex;flex-direction:column;gap:5px}
.form-group-sm label{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:rgba(255,255,255,.55)}
.form-group-sm input{padding:9px 12px;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.12);border-radius:9px;color:#fff;font-size:13px;outline:none;width:100%}
.form-group-sm input:focus{border-color:rgba(37,211,102,.4)}
.save-btn{padding:9px 18px;background:rgba(37,211,102,.15);border:1px solid rgba(37,211,102,.3);color:#4ADE80;font-size:13px;font-weight:700;border-radius:9px;cursor:pointer;white-space:nowrap}
.save-btn:hover{background:rgba(37,211,102,.25)}

@media(max-width:500px){
    .link-url{font-size:12px}
    th,td{padding:8px 10px}
}
</style>
</head>
<body>
<nav class="nav">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    <div class="nav-right">
        <a href="{{ route('seller.public', $payLink->handle) }}" style="color:rgba(255,255,255,.65);font-size:13px;text-decoration:none" target="_blank">View my page →</a>
        <form method="POST" action="{{ route('seller.logout') }}" style="display:inline">
            @csrf
            <button type="submit" style="background:none;border:none;color:rgba(255,255,255,.5);font-size:13px;cursor:pointer">Logout</button>
        </form>
    </div>
</nav>

<div class="main">
    @if(session('success'))
    <div style="background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.2);border-radius:10px;padding:12px 16px;font-size:13px;color:#4ade80;margin-bottom:20px">
        ✓ {{ session('success') }}
    </div>
    @endif

    <div class="welcome">
        <h1>{{ $payLink->business_name }}</h1>
        <p>pregota.com/pay/{{ $payLink->handle }}</p>
    </div>

    <div class="link-box">
        <div class="link-label">Your pay link</div>
        <div class="link-url">
            <span id="pay-url">pregota.com/pay/{{ $payLink->handle }}</span>
            <button class="copy-btn" onclick="copyLink()">📋 Copy</button>
        </div>
        <div class="link-actions">
            <button class="action-btn qr" onclick="showQr()">📲 Download QR Code</button>
            <button class="action-btn share" onclick="shareLink()">🔗 Share Link</button>
            <a href="{{ route('seller.live', $payLink->handle) }}" class="action-btn" style="background:rgba(251,191,36,.1);border:1px solid rgba(251,191,36,.25);color:#fbbf24;text-decoration:none" target="_blank">👁 Conductor View</a>
            <a href="{{ route('seller.till', $payLink->handle) }}" class="action-btn" style="background:rgba(168,85,247,.1);border:1px solid rgba(168,85,247,.25);color:#c084fc;text-decoration:none" target="_blank">🖥 Till Mode</a>
        </div>
    </div>

    <!-- QR Modal -->
    <div class="qr-modal" id="qr-modal" onclick="if(event.target===this)closeQr()">
        <div class="qr-card">
            <h3>{{ $payLink->business_name }}</h3>
            <p>Print this and stick it where customers can scan it</p>
            <canvas id="qr-canvas" width="220" height="220"></canvas>
            <div class="qr-btns">
                <button class="qr-dl" onclick="downloadQr()">⬇ Download PNG</button>
                <button class="qr-close" onclick="closeQr()">Close</button>
            </div>
        </div>
    </div>

    <div class="kpis">
        <div class="kpi">
            <div class="kpi-label">Total Received (net)</div>
            <div class="kpi-val green">KES {{ number_format($payLink->total_received) }}</div>
        </div>
        <div class="kpi">
            <div class="kpi-label">Payments</div>
            <div class="kpi-val blue">{{ number_format($payLink->payment_count) }}</div>
        </div>
        <div class="kpi">
            <div class="kpi-label">Pending</div>
            <div class="kpi-val" style="color:#fbbf24">{{ $payments->where('status','pending')->count() }}</div>
        </div>
    </div>

    {{-- Stamp card settings --}}
    <div class="stamp-section">
        <h2>🎟 Stamp Card</h2>
        <p>Reward repeat customers. After N payments they unlock a reward you define — free ride, discount, free coffee. Buyers see their progress on your pay page and on their receipts page. This brings them back.</p>
        <form method="POST" action="{{ route('seller.stamp-card') }}">
            @csrf
            <div class="form-row">
                <div class="form-group-sm" style="width:120px">
                    <label>Stamps to reward</label>
                    <input type="number" name="stamps_required" min="2" max="50" placeholder="e.g. 10"
                        value="{{ $payLink->stamps_required }}">
                </div>
                <div class="form-group-sm" style="flex:1;min-width:180px">
                    <label>Reward description</label>
                    <input type="text" name="stamp_reward" maxlength="200" placeholder="e.g. Free trip · 10th ride free"
                        value="{{ $payLink->stamp_reward }}">
                </div>
                <button type="submit" class="save-btn">Save</button>
            </div>
            @if($payLink->stamps_required)
            <div style="margin-top:10px;font-size:12px;color:rgba(37,211,102,.8)">
                ✓ Active — customers earn 1 stamp per payment, get <strong>"{{ $payLink->stamp_reward }}"</strong> after {{ $payLink->stamps_required }} stamps.
                Leave "Stamps to reward" blank and save to disable.
            </div>
            @else
            <div style="margin-top:10px;font-size:11px;color:rgba(255,255,255,.35)">Stamp card is off. Set a number above to activate it.</div>
            @endif
        </form>
    </div>

    {{-- Subscription Plans --}}
    <div class="stamp-section" style="background:rgba(168,85,247,.05);border-color:rgba(168,85,247,.18)">
        <h2 style="color:#c084fc">♻️ Subscription Plans</h2>
        <p>Create recurring plans — monthly, quarterly, or annual. Customers subscribe once and you can send them reminder links via WhatsApp when payment is due.</p>
        <form method="POST" action="{{ route('subscription.save-plan') }}" id="plan-form">
            @csrf
            <div class="form-row">
                <div class="form-group-sm" style="flex:1;min-width:140px">
                    <label>Plan Name</label>
                    <input type="text" name="name" maxlength="80" placeholder="e.g. Monthly Premium" required>
                </div>
                <div class="form-group-sm" style="width:120px">
                    <label>Amount (KES)</label>
                    <input type="number" name="amount" min="10" max="150000" placeholder="500" required>
                </div>
                <div class="form-group-sm" style="width:110px">
                    <label>Frequency</label>
                    <select name="frequency" style="padding:9px 12px;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.12);border-radius:9px;color:#fff;font-size:13px;outline:none;width:100%">
                        <option value="monthly">Monthly</option>
                        <option value="quarterly">Quarterly</option>
                        <option value="annually">Annually</option>
                    </select>
                </div>
                <button type="submit" class="save-btn" style="background:rgba(168,85,247,.15);border-color:rgba(168,85,247,.3);color:#c084fc">+ Add Plan</button>
            </div>
            <div class="form-group-sm" style="margin-top:10px">
                <label>Description (optional)</label>
                <input type="text" name="description" maxlength="200" placeholder="What does this plan include?">
            </div>
        </form>

        @if($subscriptionPlans->isNotEmpty())
        <div style="margin-top:16px;display:flex;flex-direction:column;gap:8px">
            @foreach($subscriptionPlans as $plan)
            <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 14px;background:rgba(168,85,247,.06);border:1px solid rgba(168,85,247,.15);border-radius:10px;flex-wrap:wrap;gap:8px">
                <div>
                    <div style="font-size:14px;font-weight:700">{{ $plan->name }}</div>
                    <div style="font-size:12px;color:rgba(255,255,255,.45)">KES {{ number_format($plan->amount) }} / {{ $plan->frequencyLabel() }} · {{ $plan->subscriptions_count }} subscriber{{ $plan->subscriptions_count == 1 ? '' : 's' }}</div>
                </div>
                <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
                    <button onclick="navigator.clipboard.writeText('{{ url('/subscribe/' . $plan->id) }}');alert('Subscribe link copied!')" style="font-size:11px;padding:4px 10px;background:rgba(168,85,247,.1);border:1px solid rgba(168,85,247,.2);border-radius:6px;color:#c084fc;cursor:pointer">Copy Subscribe Link</button>
                    <a href="{{ route('subscription.subscribers', $plan->id) }}" style="font-size:11px;padding:4px 10px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:6px;color:rgba(255,255,255,.6);text-decoration:none">View Subscribers →</a>
                    <form method="POST" action="{{ route('subscription.toggle-plan', $plan->id) }}" style="display:inline">
                        @csrf
                        <button type="submit" style="font-size:11px;padding:4px 10px;background:{{ $plan->is_active ? 'rgba(239,68,68,.1)' : 'rgba(37,211,102,.1)' }};border:1px solid {{ $plan->is_active ? 'rgba(239,68,68,.25)' : 'rgba(37,211,102,.25)' }};border-radius:6px;color:{{ $plan->is_active ? '#f87171' : '#4ADE80' }};cursor:pointer">
                            {{ $plan->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    <div class="table-wrap">
        <div class="table-header">
            <h2>Recent Payments</h2>
            <span style="color:rgba(255,255,255,.5);font-size:12px">Last 50</span>
        </div>
        @if($payments->isEmpty())
        <div class="empty">No payments yet. Share your link to get started.</div>
        @else
        <table>
            <thead>
                <tr>
                    <th>Amount Received</th>
                    <th>Pregota Fee</th>
                    <th>Your Net</th>
                    <th>Note</th>
                    <th>Receipt</th>
                    <th>Status</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $p)
                <tr>
                    <td style="font-weight:700">KES {{ number_format($p->amount) }}</td>
                    <td style="color:rgba(255,255,255,.5)">KES {{ number_format($p->fee) }}</td>
                    <td style="color:#4ade80;font-weight:700">KES {{ number_format($p->net_amount) }}</td>
                    <td style="color:rgba(255,255,255,.55);font-size:12px">{{ $p->buyer_note ?: '—' }}</td>
                    <td style="font-size:11px">
                        @if($p->receipt_number)
                        <a href="{{ route('receipt.show', $p->receipt_number) }}" target="_blank" style="color:#a78bfa;font-family:monospace;text-decoration:none">{{ $p->receipt_number }}</a>
                        @else
                        <span style="color:rgba(255,255,255,.3)">—</span>
                        @endif
                    </td>
                    <td><span class="badge {{ $p->status }}">{{ ucfirst($p->status) }}</span></td>
                    <td style="font-size:12px;color:rgba(255,255,255,.55)">{{ $p->created_at->format('d M, H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js" integrity="sha512-CNgIRecGo7nphbeZ04Sc13ka07paqdeTu0WR1IM4kNcpmBAUSHSe1HDAH/bxRHZ2rOS6QTLXT8ROuJq9q0BGQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
const PAY_URL = 'https://pregota.com/pay/{{ $payLink->handle }}';

function copyLink() {
    navigator.clipboard.writeText(PAY_URL).then(() => {
        const btn = document.querySelector('.copy-btn');
        btn.textContent = '✓ Copied!';
        setTimeout(() => btn.textContent = '📋 Copy', 2000);
    });
}

function shareLink() {
    if (navigator.share) {
        navigator.share({
            title: 'Pay {{ $payLink->business_name }} via M-Pesa',
            url: PAY_URL,
        });
    } else {
        copyLink();
    }
}

let qrGenerated = false;
function showQr() {
    document.getElementById('qr-modal').classList.add('open');
    if (!qrGenerated) {
        new QRCode(document.getElementById('qr-canvas'), {
            text: PAY_URL,
            width: 220,
            height: 220,
            colorDark: '#000000',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.H,
        });
        qrGenerated = true;
    }
}

function closeQr() {
    document.getElementById('qr-modal').classList.remove('open');
}

function downloadQr() {
    const canvas = document.querySelector('#qr-canvas canvas') || document.getElementById('qr-canvas');
    const link = document.createElement('a');
    link.download = 'pregota-pay-{{ $payLink->handle }}.png';
    link.href = canvas.toDataURL('image/png');
    link.click();
}
</script>
</body>
</html>
