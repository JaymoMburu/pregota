<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Listings · Saka Keja</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800;900&display=swap" rel="stylesheet">
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Plus Jakarta Sans',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh}
.nav{padding:14px 20px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.07);position:sticky;top:0;background:#0B141A;z-index:10}
.logo{font-size:20px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.logout-btn{font-size:12px;color:rgba(255,255,255,.35);background:none;border:none;cursor:pointer;padding:6px 10px}
.logout-btn:hover{color:rgba(255,255,255,.6)}
.wrap{max-width:620px;margin:0 auto;padding:24px 16px 80px}

.greeting{margin-bottom:24px}
.greeting-name{font-size:22px;font-weight:900}
.greeting-sub{font-size:13px;color:rgba(255,255,255,.4);margin-top:3px}

.stats{display:grid;grid-template-columns:repeat(2,1fr);gap:10px;margin-bottom:24px}
.stat{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);border-radius:12px;padding:16px;text-align:center}
.stat-val{font-size:22px;font-weight:900;color:#f59e0b}
.stat-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:rgba(255,255,255,.35);margin-top:3px}

.add-btn{display:block;width:100%;padding:14px;background:linear-gradient(135deg,#d97706,#f59e0b);border:none;border-radius:13px;color:#0B141A;font-size:14px;font-weight:800;cursor:pointer;margin-bottom:24px;text-align:center;text-decoration:none}

.section-head{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.35);margin-bottom:10px;display:flex;align-items:center;gap:10px}
.section-head::after{content:'';flex:1;height:1px;background:rgba(255,255,255,.06)}

.listing-card{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:14px;margin-bottom:12px;overflow:hidden}
.listing-card.rented{opacity:.55;border-color:rgba(74,222,128,.15)}
.listing-card.inactive{opacity:.4}
.listing-top{display:flex;gap:12px;padding:14px}
.listing-img{width:72px;height:72px;object-fit:cover;border-radius:10px;flex-shrink:0;background:rgba(255,255,255,.05)}
.listing-img-placeholder{width:72px;height:72px;border-radius:10px;background:rgba(255,255,255,.04);display:flex;align-items:center;justify-content:center;font-size:28px;flex-shrink:0}
.listing-info{flex:1;min-width:0}
.listing-type{font-size:11px;font-weight:700;color:#f59e0b;margin-bottom:3px}
.listing-location{font-size:15px;font-weight:800;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.listing-rent{font-size:13px;color:#4ADE80;font-weight:700;margin-top:2px}
.status-badge{display:inline-flex;padding:2px 9px;border-radius:999px;font-size:10px;font-weight:700;margin-top:4px}
.status-active{background:rgba(74,222,128,.1);color:#4ADE80;border:1px solid rgba(74,222,128,.2)}
.status-pending{background:rgba(245,158,11,.1);color:#f59e0b;border:1px solid rgba(245,158,11,.2)}
.status-rented{background:rgba(255,255,255,.06);color:rgba(255,255,255,.4);border:1px solid rgba(255,255,255,.1)}
.status-inactive{background:rgba(239,68,68,.08);color:#fca5a5;border:1px solid rgba(239,68,68,.15)}

.listing-actions{display:flex;gap:8px;padding:0 14px 14px}
.action-btn{padding:7px 14px;border-radius:8px;font-size:12px;font-weight:700;cursor:pointer;border:none;transition:.15s}
.btn-rented{background:rgba(74,222,128,.1);color:#4ADE80;border:1px solid rgba(74,222,128,.2)}
.btn-rented:hover{background:rgba(74,222,128,.2)}
.btn-delete{background:rgba(239,68,68,.08);color:#fca5a5;border:1px solid rgba(239,68,68,.15)}
.btn-delete:hover{background:rgba(239,68,68,.15)}

.leads-section{border-top:1px solid rgba(255,255,255,.06);padding:12px 14px}
.leads-title{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.3);margin-bottom:10px}
.lead-row{display:flex;align-items:center;justify-content:space-between;padding:8px 0;border-bottom:1px solid rgba(255,255,255,.04)}
.lead-row:last-child{border-bottom:none}
.lead-name{font-size:13px;font-weight:700}
.lead-phone{font-size:13px;color:#4ADE80;font-weight:700}
.lead-time{font-size:11px;color:rgba(255,255,255,.3);margin-top:1px}
.no-leads{font-size:12px;color:rgba(255,255,255,.25);padding:8px 0}

.empty{text-align:center;padding:50px 20px;color:rgba(255,255,255,.3)}
.empty-icon{font-size:48px;margin-bottom:12px}
</style>
</head>
<body>
<nav class="nav">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    <form action="{{ route('saka-keja.landlord.logout') }}" method="POST" style="display:inline">
        @csrf
        <button class="logout-btn" type="submit">Sign out</button>
    </form>
</nav>

<div class="wrap">
    <div class="greeting">
        <div class="greeting-name">🏠 My Listings</div>
        <div class="greeting-sub">Saka Keja Landlord Dashboard</div>
    </div>

    <div class="stats">
        <div class="stat">
            <div class="stat-val">{{ $listings->where('status','active')->count() }}</div>
            <div class="stat-label">Active Listings</div>
        </div>
        <div class="stat">
            <div class="stat-val">{{ $totalConnections }}</div>
            <div class="stat-label">Total Leads</div>
        </div>
    </div>

    <a href="{{ route('saka-keja.list') }}" class="add-btn">+ Add New Listing — KES 200</a>

    <div class="section-head">Your Properties</div>

    @forelse($listings as $listing)
    <div class="listing-card {{ $listing->status }}" id="listing-{{ $listing->id }}">
        <div class="listing-top">
            @if($listing->firstPhoto())
                <img class="listing-img" src="{{ asset('uploads/saka-keja/' . $listing->id . '/' . $listing->firstPhoto()) }}" alt="">
            @else
                <div class="listing-img-placeholder">🏠</div>
            @endif
            <div class="listing-info">
                <div class="listing-type">{{ $listing->unitLabel() }}</div>
                <div class="listing-location">{{ $listing->location }}</div>
                <div class="listing-rent">KES {{ number_format($listing->rent) }}/mo</div>
                <span class="status-badge status-{{ $listing->status }}">
                    {{ match($listing->status) {
                        'active'               => 'Active',
                        'pending_verification' => 'Pending payment',
                        'rented'               => 'Rented out',
                        'failed'               => 'Payment failed',
                        default                => ucfirst($listing->status),
                    } }}
                </span>
            </div>
        </div>

        @if($listing->status === 'active')
        <div class="listing-actions">
            <button class="action-btn btn-rented" onclick="markRented({{ $listing->id }})">Mark as Rented</button>
            <button class="action-btn btn-delete" onclick="deleteListing({{ $listing->id }})">Remove</button>
        </div>
        @endif

        @if($listing->status === 'taken')
        <div class="leads-section">
            @php
                $tenant = $listing->deposits->whereIn('status', ['confirmed','moving_out'])->first();
                $isMovingOut = $tenant && $tenant->status === 'moving_out';
            @endphp
            @if($isMovingOut)
            <div style="background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.2);border-radius:10px;padding:12px 14px;margin-bottom:12px">
                <div style="font-size:12px;font-weight:800;color:#f59e0b;margin-bottom:4px">⚠️ Move-Out Requested</div>
                <div style="font-size:12px;color:rgba(255,255,255,.55);margin-bottom:10px">{{ $tenant->seeker_name }} requested to move out on {{ $tenant->move_out_requested_at?->format('d M Y') }}. Inspect the property first, then approve. Deposit held: KES {{ number_format($tenant->deposit_amount) }} — refund full amount if house is in good condition.</div>
                <button onclick="approveMoveOut('{{ $tenant->token }}')" style="width:100%;padding:9px;background:linear-gradient(135deg,#16a34a,#22c55e);color:#fff;font-size:13px;font-weight:800;border:none;border-radius:9px;cursor:pointer" id="approve-btn-{{ $tenant->id }}">✓ Approve Move Out &amp; Release Deposit</button>
            </div>
            @endif

            <div class="leads-title">Current Tenant</div>
            @if($tenant)
            <div class="lead-row">
                <div>
                    <div class="lead-name">{{ $tenant->seeker_name }}</div>
                    <div class="lead-time">Moved in {{ $tenant->confirmed_at?->format('d M Y') }}</div>
                </div>
                <div class="lead-phone">{{ $tenant->seeker_phone }}</div>
            </div>
            <div style="margin-top:10px">
                <div class="leads-title">Rent History</div>
                @forelse($listing->rentPayments->where('status','confirmed')->sortByDesc('rent_month') as $rp)
                <div class="lead-row">
                    <div>
                        <div class="lead-name">{{ \Carbon\Carbon::createFromFormat('Y-m',$rp->rent_month)->format('F Y') }}</div>
                        <div class="lead-time">{{ $rp->receipt_number }} — you received KES {{ number_format($rp->net_amount) }}</div>
                    </div>
                    <div class="lead-phone">KES {{ number_format($rp->gross_amount) }}</div>
                </div>
                @empty
                <div class="no-leads">No rent payments yet.</div>
                @endforelse
            </div>
            @endif
        </div>
        @else
        <div class="leads-section">
            <div class="leads-title">Seekers ({{ $listing->connections->count() }} lead{{ $listing->connections->count() !== 1 ? 's' : '' }})</div>
            @forelse($listing->connections as $conn)
            <div class="lead-row">
                <div>
                    <div class="lead-name">{{ $conn->seeker_name }}</div>
                    <div class="lead-time">{{ $conn->created_at->diffForHumans() }}</div>
                </div>
                <div class="lead-phone">{{ $conn->seeker_phone }}</div>
            </div>
            @empty
            <div class="no-leads">No connections yet — seekers who pay KES 200 will appear here.</div>
            @endforelse
        </div>
        @endif
    </div>
    @empty
    <div class="empty">
        <div class="empty-icon">🏠</div>
        <div style="font-size:15px;font-weight:700;color:rgba(255,255,255,.5);margin-bottom:6px">No listings yet</div>
        <div style="font-size:13px">Click "Add New Listing" to publish your first property.</div>
    </div>
    @endforelse
</div>

<script>
const CSRF = '{{ csrf_token() }}';

async function approveMoveOut(token) {
    if (!confirm('Approve move-out and refund the deposit? The listing will go back to active.')) return;
    const btn = document.querySelector(`[onclick="approveMoveOut('${token}')"]`);
    if (btn) { btn.disabled = true; btn.textContent = 'Processing…'; }
    const res  = await fetch(`/saka-keja/deposit/${token}/approve-move-out`, {method:'POST',headers:{'X-CSRF-TOKEN':CSRF}});
    const data = await res.json();
    if (data.success) location.reload();
    else { alert(data.message || 'Could not process.'); if (btn) btn.disabled = false; }
}

async function markRented(id) {
    if (!confirm('Mark this listing as rented? It will be removed from the public browse.')) return;
    await fetch(`/saka-keja/${id}/rented`, {method:'POST',headers:{'X-CSRF-TOKEN':CSRF}});
    document.getElementById('listing-' + id).classList.add('rented');
    document.querySelector(`#listing-${id} .status-badge`).textContent = 'Rented out';
    document.querySelector(`#listing-${id} .listing-actions`).remove();
}

async function deleteListing(id) {
    if (!confirm('Remove this listing from Saka Keja?')) return;
    await fetch(`/saka-keja/${id}`, {method:'DELETE',headers:{'X-CSRF-TOKEN':CSRF}});
    document.getElementById('listing-' + id).style.opacity = '0.3';
}
</script>
</body>
</html>
