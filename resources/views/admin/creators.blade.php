<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Creator Approvals — Pregota Admin</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh;padding:24px}
.top{display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;flex-wrap:wrap;gap:12px}
.logo{font-size:20px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.back{font-size:13px;color:rgba(255,255,255,.6);text-decoration:none;border:1px solid rgba(255,255,255,.12);border-radius:8px;padding:7px 14px}
.back:hover{color:#fff;border-color:rgba(255,255,255,.3)}
h1{font-size:20px;font-weight:900;margin-bottom:4px}
.sub{font-size:13px;color:rgba(255,255,255,.6);margin-bottom:24px}
.badge{display:inline-flex;align-items:center;gap:5px;background:rgba(239,68,68,.15);border:1px solid rgba(239,68,68,.3);border-radius:20px;padding:3px 10px;font-size:11px;font-weight:700;color:#fca5a5}
.badge.green{background:rgba(34,197,94,.1);border-color:rgba(34,197,94,.25);color:#4ade80}
.section-head{font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.5);margin:28px 0 12px}
.creator-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:16px 20px;display:flex;align-items:center;gap:16px;margin-bottom:10px;flex-wrap:wrap}
.avatar{width:46px;height:46px;border-radius:50%;background:linear-gradient(135deg,#00A651,#007A33);display:flex;align-items:center;justify-content:center;font-size:20px;font-weight:900;flex-shrink:0;overflow:hidden}
.avatar img{width:100%;height:100%;object-fit:cover}
.info{flex:1;min-width:160px}
.name{font-size:15px;font-weight:700}
.handle{font-size:12px;color:rgba(255,255,255,.5);margin-top:2px}
.meta{font-size:11px;color:rgba(255,255,255,.45);margin-top:4px}
.bio{font-size:12px;color:rgba(255,255,255,.65);margin-top:5px;line-height:1.5;max-width:360px}
.actions{display:flex;gap:8px;flex-shrink:0}
.btn-approve{background:linear-gradient(135deg,#00A651,#007A33);color:#fff;border:none;border-radius:8px;padding:8px 18px;font-size:13px;font-weight:700;cursor:pointer}
.btn-approve:hover{opacity:.9}
.btn-reject{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3);color:#fca5a5;border-radius:8px;padding:8px 18px;font-size:13px;font-weight:700;cursor:pointer}
.btn-reject:hover{background:rgba(239,68,68,.2)}
.empty{color:rgba(255,255,255,.4);font-size:13px;padding:20px 0}
.alert{padding:12px 16px;border-radius:10px;font-size:13px;margin-bottom:20px}
.alert.success{background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.25);color:#4ade80}
.stats-row{display:flex;gap:12px;margin-bottom:28px;flex-wrap:wrap}
.stat{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);border-radius:12px;padding:14px 18px;flex:1;min-width:120px}
.stat-val{font-size:22px;font-weight:900;color:#fff}
.stat-lbl{font-size:11px;color:rgba(255,255,255,.5);margin-top:3px;text-transform:uppercase;letter-spacing:.06em}
</style>
</head>
<body>

<div class="top">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    <a href="{{ route('admin.dashboard') }}" class="back">← Admin Dashboard</a>
</div>

@if(session('success'))
<div class="alert success">{{ session('success') }}</div>
@endif

<h1>Creator Accounts</h1>
<p class="sub">Review and approve creator registrations before they go live.</p>

<div class="stats-row">
    <div class="stat">
        <div class="stat-val">{{ $pending->count() }}</div>
        <div class="stat-lbl">Pending Approval</div>
    </div>
    <div class="stat">
        <div class="stat-val">{{ $active->count() }}</div>
        <div class="stat-lbl">Active Creators</div>
    </div>
</div>

<div class="section-head">
    Pending Approval
    @if($pending->count()) <span class="badge">{{ $pending->count() }} waiting</span> @endif
</div>

@forelse($pending as $creator)
<div class="creator-card">
    <div class="avatar">
        @if($creator->photo_url)
            <img src="{{ $creator->photo_url }}" alt="">
        @else
            {{ strtoupper(substr($creator->display_name, 0, 1)) }}
        @endif
    </div>
    <div class="info">
        <div class="name">{{ $creator->display_name }}</div>
        <div class="handle">@{{ $creator->handle }} · pregota.com/c/{{ $creator->handle }}</div>
        <div class="meta">Registered {{ $creator->created_at->diffForHumans() }} · Min gift KES {{ number_format($creator->min_gift_amount, 0) }}</div>
        @if($creator->bio)
        <div class="bio">{{ $creator->bio }}</div>
        @endif
    </div>
    <div class="actions">
        <form method="POST" action="{{ route('admin.creators.approve', $creator) }}" style="display:inline">
            @csrf
            <button type="submit" class="btn-approve">Approve</button>
        </form>
        <form method="POST" action="{{ route('admin.creators.reject', $creator) }}" style="display:inline"
              onsubmit="return confirm('Reject and permanently delete @{{ $creator->handle }}?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn-reject">Reject</button>
        </form>
    </div>
</div>
@empty
<div class="empty">No pending creators — all caught up.</div>
@endforelse

<div class="section-head">
    Active Creators <span class="badge green">{{ $active->count() }} live</span>
</div>

@forelse($active as $creator)
<div class="creator-card" style="opacity:.8">
    <div class="avatar">
        @if($creator->photo_url)
            <img src="{{ $creator->photo_url }}" alt="">
        @else
            {{ strtoupper(substr($creator->display_name, 0, 1)) }}
        @endif
    </div>
    <div class="info">
        <div class="name">{{ $creator->display_name }}</div>
        <div class="handle">@{{ $creator->handle }}</div>
        <div class="meta">
            {{ $creator->gifts()->where('status','paid')->count() }} gifts ·
            KES {{ number_format($creator->total_received, 0) }} received ·
            Active since {{ $creator->updated_at->format('d M Y') }}
        </div>
    </div>
    <div class="actions">
        <a href="{{ route('creator.page', $creator->handle) }}" target="_blank" style="color:rgba(255,255,255,.6);font-size:12px;text-decoration:none;border:1px solid rgba(255,255,255,.1);border-radius:8px;padding:7px 14px">View Page ↗</a>
        <form method="POST" action="{{ route('admin.creators.reject', $creator) }}" style="display:inline"
              onsubmit="return confirm('Deactivate and delete @{{ $creator->handle }}? This cannot be undone.')">
            @csrf @method('DELETE')
            <button type="submit" class="btn-reject" style="font-size:12px;padding:7px 12px">Remove</button>
        </form>
    </div>
</div>
@empty
<div class="empty">No active creators yet.</div>
@endforelse

</body>
</html>
