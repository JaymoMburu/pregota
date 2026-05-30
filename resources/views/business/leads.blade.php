<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Customer Leads — {{ $business->name }}</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}input,textarea,select,button{font-family:inherit;font-size:inherit}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh}
.topbar{padding:14px 20px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.07);background:#0B141A;position:sticky;top:0;z-index:10}
.logo{font-size:18px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.back{font-size:13px;color:rgba(255,255,255,.68);text-decoration:none}

.page{max-width:700px;margin:0 auto;padding:28px 20px 60px}
.page-title{font-size:22px;font-weight:900;margin-bottom:4px}
.page-sub{font-size:13px;color:rgba(255,255,255,.68);margin-bottom:28px}

.summary-row{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:28px}
.sum-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:12px;padding:16px;text-align:center}
.sum-num{font-size:26px;font-weight:900;color:#fbbf24}
.sum-label{font-size:11px;color:rgba(255,255,255,.68);margin-top:3px}

.toolbar{display:flex;gap:10px;align-items:center;margin-bottom:18px;flex-wrap:wrap}
.filter-btn{padding:7px 14px;border-radius:8px;border:1px solid rgba(255,255,255,.12);background:rgba(255,255,255,.05);color:rgba(255,255,255,.78);font-size:12px;font-weight:600;cursor:pointer}
.filter-btn.active{border-color:#fbbf24;background:rgba(251,191,36,.1);color:#fbbf24}
.export-btn{margin-left:auto;display:inline-flex;align-items:center;gap:6px;padding:8px 18px;border-radius:8px;border:1px solid rgba(74,222,128,.25);background:rgba(74,222,128,.08);color:#4ade80;font-size:12px;font-weight:700;cursor:pointer}

.leads-table{width:100%;border-collapse:collapse}
.leads-table th{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.6);padding:0 12px 10px;text-align:left}
.leads-table td{padding:12px;border-bottom:1px solid rgba(255,255,255,.05);font-size:13px;vertical-align:middle}
.leads-table tr:last-child td{border-bottom:none}
.leads-table tr:hover td{background:rgba(255,255,255,.02)}
.phone-cell{font-family:monospace;font-weight:700;font-size:14px;letter-spacing:.05em}
.venue-cell{color:rgba(255,255,255,.6)}
.date-cell{color:rgba(255,255,255,.6);font-size:12px;white-space:nowrap}
.copy-btn{padding:5px 10px;border-radius:6px;border:1px solid rgba(251,191,36,.2);background:rgba(251,191,36,.07);color:#fbbf24;font-size:11px;font-weight:700;cursor:pointer}

.empty{text-align:center;padding:60px 20px;color:rgba(255,255,255,.82)}
.empty-icon{font-size:44px;margin-bottom:14px}
</style>
</head>
<body>

<div class="topbar">
    <a href="{{ route('business.dashboard') }}" class="back">← Dashboard</a>
    <a href="{{ route('home') }}" class="logo">Pregota</a>
</div>

<div class="page">
    <div class="page-title">Customer Leads</div>
    <div class="page-sub">{{ $business->name }} · Customers who voluntarily shared their contact with you through Pregota after paying</div>

    <div class="summary-row">
        <div class="sum-card">
            <div class="sum-num">{{ $optIns->count() }}</div>
            <div class="sum-label">Total opt-ins</div>
        </div>
        <div class="sum-card">
            <div class="sum-num">{{ $optIns->where('created_at', '>=', today())->count() }}</div>
            <div class="sum-label">Today</div>
        </div>
        <div class="sum-card">
            <div class="sum-num">{{ $optIns->where('created_at', '>=', now()->startOfWeek())->count() }}</div>
            <div class="sum-label">This week</div>
        </div>
    </div>

    @if($optIns->count())
    <div class="toolbar">
        <button class="filter-btn active" onclick="filter('all', this)">All</button>
        <button class="filter-btn" onclick="filter('today', this)">Today</button>
        <button class="filter-btn" onclick="filter('week', this)">This week</button>
        <button class="export-btn" onclick="exportCSV()">↓ Export CSV</button>
    </div>

    <table class="leads-table">
        <thead>
            <tr>
                <th>Phone</th>
                <th>Table / Venue</th>
                <th>Staff</th>
                <th>Date & Time</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="leadsBody">
            @foreach($optIns as $optIn)
            @php $phone = $optIn->getPhone(); @endphp
            <tr data-date="{{ $optIn->created_at->toDateString() }}"
                data-phone="{{ $phone }}"
                data-venue="{{ $optIn->billSplit->business_name }}{{ $optIn->billSplit->label ? ' · ' . $optIn->billSplit->label : '' }}"
                data-staff="{{ $optIn->billSplit->tip_handle ?? '—' }}"
                data-datetime="{{ $optIn->created_at->format('d M Y · g:i A') }}">
                <td class="phone-cell">{{ $phone }}</td>
                <td class="venue-cell">
                    {{ $optIn->billSplit->business_name }}
                    @if($optIn->billSplit->label)
                    <span style="color:rgba(255,255,255,.6)"> · {{ $optIn->billSplit->label }}</span>
                    @endif
                </td>
                <td style="color:rgba(255,255,255,.72)">{{ $optIn->billSplit->tip_handle ?? '—' }}</td>
                <td class="date-cell">{{ $optIn->created_at->format('d M · g:i A') }}</td>
                <td><button class="copy-btn" onclick="copyPhone('{{ $phone }}', this)">Copy</button></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="empty">
        <div class="empty-icon">📱</div>
        <div style="font-weight:700;margin-bottom:8px">No leads yet</div>
        <div style="font-size:12px">Customers are offered to opt in after paying via bill split. Make sure your staff use their Pregota handle when creating splits.</div>
    </div>
    @endif
</div>

<script>
const today   = new Date().toISOString().split('T')[0];
const weekAgo = new Date(Date.now() - 7*86400000).toISOString().split('T')[0];

function filter(period, btn) {
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('#leadsBody tr').forEach(row => {
        const d    = row.dataset.date;
        const show = period === 'all'   ? true
                   : period === 'today' ? d === today
                   : d >= weekAgo;
        row.style.display = show ? '' : 'none';
    });
}

function copyPhone(phone, btn) {
    navigator.clipboard.writeText(phone).then(() => {
        btn.textContent = '✓ Copied';
        setTimeout(() => btn.textContent = 'Copy', 2000);
    });
}

function exportCSV() {
    const rows = [['Phone', 'Venue', 'Staff Handle', 'Date & Time']];
    document.querySelectorAll('#leadsBody tr').forEach(row => {
        if (row.style.display === 'none') return;
        rows.push([row.dataset.phone, row.dataset.venue, row.dataset.staff, row.dataset.datetime]);
    });
    const csv  = rows.map(r => r.map(c => `"${c}"`).join(',')).join('\n');
    const blob = new Blob([csv], { type: 'text/csv' });
    const a    = Object.assign(document.createElement('a'), {
        href: URL.createObjectURL(blob),
        download: '{{ Str::slug($business->name) }}-leads.csv'
    });
    a.click();
}
</script>
</body>
</html>
