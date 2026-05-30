﻿<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $class->class_name }} — Collection Tracker</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}input,textarea,select,button{font-family:inherit;font-size:inherit}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh}
.topbar{padding:14px 20px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.07);background:#0B141A;position:sticky;top:0;z-index:10}
.logo{font-size:18px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.teacher-badge{font-size:11px;padding:4px 12px;border-radius:20px;background:rgba(0,166,81,.15);border:1px solid rgba(0,166,81,.3);color:#25D366;font-weight:700}

.hero{padding:24px 20px 16px;max-width:640px;margin:0 auto}
.school-label{font-size:12px;color:rgba(255,255,255,.6);margin-bottom:6px}
h1{font-size:clamp(20px,4.5vw,28px);font-weight:900;line-height:1.15;margin-bottom:6px}
.meta{font-size:13px;color:rgba(255,255,255,.68)}

.page{max-width:640px;margin:0 auto;padding:0 20px 48px;display:flex;flex-direction:column;gap:16px}

/* Stats */
.stats-row{display:grid;grid-template-columns:repeat(4,1fr);gap:10px}
.stat-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:12px;padding:14px;text-align:center}
.stat-num{font-size:22px;font-weight:900}
.stat-num.green{color:#4ade80}
.stat-num.teal{color:#2dd4bf}
.stat-num.amber{color:#fbbf24}
.stat-num.purple{color:#25D366}
.stat-label{font-size:10px;color:rgba(255,255,255,.68);margin-top:4px;line-height:1.3}

/* Share parent link */
.share-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:16px}
.card-title{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.6);margin-bottom:10px}
.link-row{display:flex;gap:8px;align-items:center;margin-bottom:10px}
.link-input{flex:1;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:8px;padding:9px 10px;color:rgba(255,255,255,.6);font-size:12px;outline:none;font-family:monospace}
.copy-btn{padding:8px 14px;border-radius:8px;background:rgba(0,166,81,.2);border:1px solid rgba(0,166,81,.3);color:#25D366;font-size:12px;font-weight:700;cursor:pointer;white-space:nowrap}
.wa-btn{display:inline-flex;align-items:center;gap:6px;padding:9px 14px;border-radius:8px;background:#25d366;color:#fff;font-size:12px;font-weight:700;text-decoration:none}
.nudge-btn{display:inline-flex;align-items:center;gap:6px;padding:9px 14px;border-radius:8px;background:rgba(251,191,36,.1);border:1px solid rgba(251,191,36,.25);color:#fbbf24;font-size:12px;font-weight:700;text-decoration:none;cursor:pointer}

/* Student list */
.list-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:16px}
.student-item{padding:12px 0;border-bottom:1px solid rgba(255,255,255,.05)}
.student-item:last-child{border-bottom:none}
.student-top{display:flex;align-items:center;gap:12px;margin-bottom:7px}
.avatar{width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#00A651,#007A33);display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;flex-shrink:0}
.student-name{flex:1;font-size:14px;font-weight:600;color:rgba(255,255,255,.85)}
.student-id{font-size:11px;color:rgba(255,255,255,.82);font-weight:400;margin-left:6px;font-family:monospace}
.full-badge{font-size:11px;padding:3px 9px;border-radius:10px;background:rgba(74,222,128,.12);border:1px solid rgba(74,222,128,.25);color:#4ade80;font-weight:700}
.partial-badge{font-size:11px;padding:3px 9px;border-radius:10px;background:rgba(251,191,36,.1);border:1px solid rgba(251,191,36,.2);color:#fbbf24;font-weight:700}
.student-amounts{display:flex;gap:16px;padding-left:46px;font-size:12px;margin-bottom:6px}
.amt-paid{color:#4ade80;font-weight:700}
.amt-remaining{color:rgba(255,255,255,.6)}
.amt-separator{color:rgba(255,255,255,.15)}
/* Mini progress bar */
.mini-bar{height:4px;background:rgba(255,255,255,.08);border-radius:2px;margin:0 0 0 46px;overflow:hidden}
.mini-bar-fill{height:100%;background:linear-gradient(90deg,#00A651,#4ade80);border-radius:2px;transition:width .3s}
.student-time{padding-left:46px;font-size:11px;color:rgba(255,255,255,.25);margin-top:4px}

/* Pending */
.pending-item{display:flex;align-items:center;justify-content:space-between;padding:9px 12px;background:rgba(251,191,36,.04);border:1px solid rgba(251,191,36,.1);border-radius:9px;margin-bottom:6px}
.pending-item:last-child{margin-bottom:0}
.pending-name{font-size:13px;color:rgba(255,255,255,.65)}
.pending-badge{font-size:11px;padding:2px 8px;border-radius:10px;background:rgba(251,191,36,.12);color:#fbbf24;font-weight:700}

.empty{text-align:center;padding:20px;font-size:13px;color:rgba(255,255,255,.82)}
.refresh-note{text-align:center;font-size:11px;color:rgba(255,255,255,.2);padding:8px 0}

@media(max-width:480px){
    .stats-row{grid-template-columns:1fr 1fr}
    .student-amounts{flex-wrap:wrap;gap:8px}
}
</style>
</head>
<body>

<div class="topbar">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    <span class="teacher-badge">Teacher View</span>
</div>

<div class="hero">
    <div class="school-label">{{ $collection->school_name }} · {{ $collection->term_label }}</div>
    <h1>{{ $class->class_name }} — Collection Tracker</h1>
    <div class="meta">{{ $class->teacher_name }} · Collection amount: KES {{ number_format($collection->amount_per_student) }}</div>
</div>

<div class="page">

    <!-- Stats -->
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-num green">{{ $fullCount }}</div>
            <div class="stat-label">Fully Paid</div>
        </div>
        <div class="stat-card">
            <div class="stat-num teal">{{ $partialCount }}</div>
            <div class="stat-label">Partial</div>
        </div>
        <div class="stat-card">
            <div class="stat-num amber">{{ $pending->count() }}</div>
            <div class="stat-label">STK Pending</div>
        </div>
        <div class="stat-card">
            <div class="stat-num purple" style="font-size:16px">KES {{ number_format($class->total_raised) }}</div>
            <div class="stat-label">Collected</div>
        </div>
    </div>

    <!-- Share parent link -->
    <div class="share-card">
        <div class="card-title">Parent Payment Link — Share This With Your Class</div>
        @php $classUrl = route('school-collection.class', ['slug' => $collection->slug, 'classToken' => $class->class_token]); @endphp
        <div class="link-row">
            <input class="link-input" id="payLink" readonly value="{{ $classUrl }}">
            <button class="copy-btn" onclick="copyLink('payLink', this)">Copy</button>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap">
            <a href="https://wa.me/?text={{ urlencode('Dear Parents,' . "\n\n" . 'Kindly pay ' . $collection->school_name . ' ' . $collection->term_label . ' fees (KES ' . number_format($collection->amount_per_student) . ') via this link:' . "\n" . $classUrl . "\n\n" . 'You can pay in instalments — any amount from KES 50.' . "\n" . '— ' . $class->teacher_name) }}"
               target="_blank" class="wa-btn">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                Share with Class
            </a>
            @if($studentTotals->count() > 0)
            <a href="https://wa.me/?text={{ urlencode('Dear Parent,' . "\n\n" . 'Kindly note that ' . $collection->term_label . ' fees for ' . $class->class_name . ' are still outstanding.' . "\n\n" . $fullCount . ' students have fully paid. You can pay in instalments — use this link:' . "\n" . $classUrl . "\n\n" . '— ' . $class->teacher_name) }}"
               target="_blank" class="nudge-btn">
                ⚡ Nudge Non-Payers
            </a>
            @endif
        </div>
    </div>

    <!-- Per-student payment status -->
    @if($studentTotals->count())
    <div class="list-card">
        <div class="card-title" style="display:flex;justify-content:space-between;align-items:center">
            <span>Student Fee Status ({{ $studentTotals->count() }} student{{ $studentTotals->count() === 1 ? '' : 's' }})</span>
            <span id="searchCount" style="font-size:11px;color:rgba(255,255,255,.72)"></span>
        </div>
        <div style="margin-bottom:12px">
            <input type="text" id="studentSearch" placeholder="Search by student name or ID…"
                style="width:100%;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:8px;padding:9px 12px;color:#fff;font-size:13px;outline:none;font-family:inherit"
                oninput="filterStudents(this.value)">
        </div>
        <div id="noResults" style="display:none;text-align:center;padding:14px;font-size:13px;color:rgba(255,255,255,.72)">No students match your search.</div>
        @foreach($studentTotals as $s)
        @php $pct = min(100, round(($s['total_paid'] / $collection->amount_per_student) * 100)); @endphp
        <div class="student-item" data-search="{{ strtolower($s['name'] . ' ' . ($s['student_id'] ?? '')) }}">
            <div class="student-top">
                <div class="avatar">{{ strtoupper(substr($s['name'], 0, 1)) }}</div>
                <div class="student-name">
                    {{ $s['name'] }}
                    @if($s['student_id'])<span class="student-id">{{ $s['student_id'] }}</span>@endif
                </div>
                @if($s['is_full'])
                    <span class="full-badge">✓ Full</span>
                @else
                    <span class="partial-badge">{{ $pct }}%</span>
                @endif
            </div>
            <div class="student-amounts">
                <span class="amt-paid">KES {{ number_format($s['total_paid']) }} paid</span>
                @if(!$s['is_full'])
                <span class="amt-separator">·</span>
                <span class="amt-remaining">KES {{ number_format($s['balance']) }} remaining</span>
                @endif
            </div>
            <div class="mini-bar"><div class="mini-bar-fill" style="width:{{ $pct }}%"></div></div>
            @if($s['last_paid'])
            <div class="student-time">Last payment {{ \Carbon\Carbon::parse($s['last_paid'])->diffForHumans() }}</div>
            @endif
        </div>
        @endforeach
    </div>
    @else
    <div class="list-card">
        <div class="card-title">Student Fee Status</div>
        <div class="empty">No payments yet. Share the parent link above.</div>
    </div>
    @endif

    @if($pending->count())
    <!-- Pending STK pushes -->
    <div class="list-card">
        <div class="card-title">⏳ STK Sent — Awaiting PIN ({{ $pending->count() }})</div>
        @foreach($pending as $p)
        <div class="pending-item">
            <span class="pending-name">{{ $p->student_name }}</span>
            <span class="pending-badge">Pending M-Pesa</span>
        </div>
        @endforeach
        <div class="refresh-note">This page auto-updates. Refresh to see latest.</div>
    </div>
    @endif

</div>

<script>
function copyLink(id, btn) {
    navigator.clipboard.writeText(document.getElementById(id).value).then(() => {
        const orig = btn.textContent;
        btn.textContent = '✓ Copied';
        setTimeout(() => btn.textContent = orig, 2000);
    });
}

function filterStudents(q) {
    const term  = q.trim().toLowerCase();
    const items = document.querySelectorAll('.student-item');
    let   shown = 0;
    items.forEach(el => {
        const match = !term || el.dataset.search.includes(term);
        el.style.display = match ? '' : 'none';
        if (match) shown++;
    });
    const countEl = document.getElementById('searchCount');
    const noRes   = document.getElementById('noResults');
    countEl.textContent = term ? `${shown} of ${items.length}` : '';
    noRes.style.display = (term && shown === 0) ? 'block' : 'none';
}

// Pause auto-refresh while user is searching
let refreshTimer = null;
function scheduleRefresh() {
    clearTimeout(refreshTimer);
    const searchEl = document.getElementById('studentSearch');
    if (!searchEl || !searchEl.value.trim()) {
        refreshTimer = setTimeout(() => location.reload(), 30000);
    }
}
document.getElementById('studentSearch')?.addEventListener('input', scheduleRefresh);
scheduleRefresh();
</script>
</body>
</html>
