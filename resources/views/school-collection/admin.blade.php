<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard — {{ $collection->school_name }}</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0f0f1a;color:#fff;min-height:100vh}
.topbar{padding:14px 20px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.07);background:#0f0f1a}
.logo{font-size:18px;font-weight:900;background:linear-gradient(135deg,#c084fc,#f472b6);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.status-badge{font-size:11px;padding:4px 10px;border-radius:20px;font-weight:700}
.status-badge.open{background:rgba(34,197,94,.15);border:1px solid rgba(34,197,94,.3);color:#4ade80}
.status-badge.closed,.status-badge.paid{background:rgba(124,58,237,.15);border:1px solid rgba(124,58,237,.3);color:#c084fc}

.page{max-width:800px;margin:0 auto;padding:24px 20px 60px}
.alert{padding:12px 16px;border-radius:10px;margin-bottom:18px;font-size:13px;font-weight:600}
.alert.success{background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.25);color:#4ade80}
.alert.error{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);color:#f87171}
.alert.info{background:rgba(124,58,237,.1);border:1px solid rgba(124,58,237,.25);color:#c084fc}

h2{font-size:22px;font-weight:900;margin-bottom:4px}
.subtitle{font-size:13px;color:rgba(255,255,255,.4);margin-bottom:24px}

/* Totals */
.totals-row{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:28px}
@media(max-width:600px){.totals-row{grid-template-columns:1fr 1fr}}
.total-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:12px;padding:16px;text-align:center}
.total-card .num{font-size:24px;font-weight:900;color:#c084fc}
.total-card .lbl{font-size:11px;color:rgba(255,255,255,.4);margin-top:4px}

/* Section */
.section-title{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.35);margin-bottom:12px}

/* Class accordion */
.acc-list{display:flex;flex-direction:column;gap:6px;margin-bottom:28px}
.acc-item{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:12px;overflow:hidden}
.acc-header{display:flex;align-items:center;gap:12px;padding:13px 16px;cursor:pointer;user-select:none;transition:.15s}
.acc-header:hover{background:rgba(255,255,255,.03)}
.acc-left{flex:1;min-width:0}
.acc-class-name{font-size:14px;font-weight:800;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.acc-teacher{font-size:11px;color:rgba(255,255,255,.35);margin-top:2px}
.acc-right{display:flex;align-items:center;gap:8px;flex-shrink:0}
.acc-stat{font-size:11px;font-weight:700;padding:2px 8px;border-radius:8px}
.acc-stat.green{background:rgba(74,222,128,.1);color:#4ade80}
.acc-stat.amber{background:rgba(251,191,36,.1);color:#fbbf24}
.acc-stat.red{background:rgba(239,68,68,.1);color:#f87171}
.acc-amount{font-size:13px;font-weight:900;color:#c084fc;min-width:80px;text-align:right}
.acc-chevron{font-size:12px;color:rgba(255,255,255,.3);transition:.2s;margin-left:4px}
.acc-chevron.open{transform:rotate(180deg)}
.acc-body{display:none;border-top:1px solid rgba(255,255,255,.06);padding:14px 16px}

/* Links */
.link-section{margin-bottom:12px}
.link-lbl{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.3);margin-bottom:5px}
.class-link-row{display:flex;gap:7px;align-items:center}
.class-link-input{flex:1;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:7px;padding:7px 9px;color:rgba(255,255,255,.45);font-size:11px;outline:none;font-family:monospace}
.copy-cls-btn{padding:6px 11px;border-radius:7px;background:rgba(124,58,237,.2);border:1px solid rgba(124,58,237,.3);color:#c084fc;font-size:11px;font-weight:700;cursor:pointer;white-space:nowrap}
.wa-cls-btn{display:inline-flex;align-items:center;gap:5px;padding:6px 11px;border-radius:7px;background:#25d366;color:#fff;font-size:11px;font-weight:700;text-decoration:none;white-space:nowrap}

/* Student table */
.stu-toggle{display:flex;align-items:center;gap:6px;font-size:11px;font-weight:700;color:rgba(124,58,237,.8);cursor:pointer;border:none;background:none;padding:0;margin:10px 0 0}
.stu-toggle:hover{color:#c084fc}
.stu-table{width:100%;border-collapse:collapse;margin-top:8px;font-size:12px}
.stu-table th{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:rgba(255,255,255,.3);text-align:left;padding:5px 8px;border-bottom:1px solid rgba(255,255,255,.07)}
.stu-table td{padding:7px 8px;border-bottom:1px solid rgba(255,255,255,.04);color:rgba(255,255,255,.7);vertical-align:middle}
.stu-table tr:last-child td{border-bottom:none}
.stu-table td.mono{font-family:monospace;color:rgba(255,255,255,.3);font-size:11px}
.stu-table td.amt{font-weight:700;color:#c084fc;text-align:right}
.badge-full{font-size:10px;padding:2px 7px;border-radius:6px;background:rgba(74,222,128,.1);color:#4ade80;font-weight:700}
.badge-part{font-size:10px;padding:2px 7px;border-radius:6px;background:rgba(251,191,36,.1);color:#fbbf24;font-weight:700}

/* Pending mini-list */
.pending-section{margin-top:10px;padding-top:10px;border-top:1px solid rgba(255,255,255,.05)}
.pending-lbl{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(251,191,36,.5);margin-bottom:6px}
.pending-row{display:flex;justify-content:space-between;align-items:center;padding:5px 0;border-bottom:1px solid rgba(255,255,255,.04);font-size:12px}
.pending-row:last-child{border-bottom:none}
.paid-student-row{display:flex;justify-content:space-between;align-items:center;padding:6px 0;border-bottom:1px solid rgba(255,255,255,.04);font-size:13px}
.paid-student-row .name{color:rgba(255,255,255,.75)}
.paid-student-row .amount{color:#c084fc;font-weight:700}

/* Actions */
.actions-row{display:flex;gap:10px;margin-bottom:28px;flex-wrap:wrap}
.btn-payout{flex:1;min-width:180px;padding:14px;border-radius:10px;border:none;font-size:14px;font-weight:700;cursor:pointer;background:linear-gradient(135deg,#7c3aed,#db2777);color:#fff}
.btn-payout:hover:not(:disabled){opacity:.9}
.btn-payout:disabled{opacity:.4;cursor:not-allowed}
.btn-close{flex:1;min-width:120px;padding:14px;border-radius:10px;border:1px solid rgba(239,68,68,.3);background:rgba(239,68,68,.06);color:#f87171;font-size:14px;font-weight:700;cursor:pointer}
.warning-box{background:rgba(251,191,36,.08);border:1px solid rgba(251,191,36,.2);color:#fbbf24;border-radius:10px;padding:12px 14px;font-size:12px;margin-bottom:14px}
</style>
</head>
<body>

<div class="topbar">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    <span class="status-badge {{ $collection->status }}">{{ ucfirst($collection->status) }}</span>
</div>

<div class="page">

    @if(session('created'))
    <div class="alert info">🎉 Collection created! Bookmark this page — it's your private admin dashboard. Share each class link with the respective teacher.</div>
    @endif
    @if(session('success'))<div class="alert success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert error">{{ session('error') }}</div>@endif

    <h2>{{ $collection->school_name }}</h2>
    <div class="subtitle">{{ $collection->term_label }} · KES {{ number_format($collection->amount_per_student) }} per student · Admin: {{ $collection->admin_name }}</div>

    <!-- Totals -->
    <div class="totals-row">
        <div class="total-card">
            <div class="num">KES {{ number_format($collection->total_raised) }}</div>
            <div class="lbl">Total Collected</div>
        </div>
        <div class="total-card">
            <div class="num">{{ $collection->contributor_count }}</div>
            <div class="lbl">Students Paid</div>
        </div>
        <div class="total-card">
            <div class="num">{{ $collection->classes->count() }}</div>
            <div class="lbl">Classes</div>
        </div>
        <div class="total-card">
            <div class="num">{{ $collection->classes->sum(fn($c) => $c->payments->where('status','pending')->count()) }}</div>
            <div class="lbl">Pending M-Pesa</div>
        </div>
    </div>

    <!-- Payout actions -->
    @if($collection->isOpen())
    @if($collection->total_raised > 0)
    <div class="warning-box">⚠️ Payout sends KES {{ number_format($collection->total_raised) }} directly to the school M-Pesa. The recipient number is deleted immediately. This cannot be reversed.</div>
    @endif
    <div class="actions-row">
        <form method="POST" action="{{ route('school-collection.payout', $collection->slug) }}"
              onsubmit="return confirm('Pay out KES {{ number_format($collection->total_raised) }} to school M-Pesa? This cannot be undone.')">
            @csrf
            <input type="hidden" name="token" value="{{ request()->query('token') }}">
            <button type="submit" class="btn-payout" {{ $collection->total_raised === 0 ? 'disabled' : '' }}>
                ⚡ Pay Out KES {{ number_format($collection->total_raised) }} to School
            </button>
        </form>
        <form method="POST" action="{{ route('school-collection.close', $collection->slug) }}"
              onsubmit="return confirm('Close this collection?')">
            @csrf
            <input type="hidden" name="token" value="{{ request()->query('token') }}">
            <button type="submit" class="btn-close">🔒 Close Collection</button>
        </form>
    </div>
    @endif

    <!-- Classes accordion -->
    <div class="section-title">Classes ({{ $collection->classes->count() }})</div>
    <div class="acc-list">
        @foreach($collection->classes as $class)
        @php
            $required   = $collection->amount_per_student;
            $classUrl   = route('school-collection.class',   ['slug' => $collection->slug, 'classToken'   => $class->class_token]);
            $teacherUrl = route('school-collection.teacher', ['slug' => $collection->slug, 'teacherToken' => $class->teacher_token]);
            $waMsg      = urlencode("Dear Parents,\n\nKindly pay " . $collection->school_name . " " . $collection->term_label . " (KES " . number_format($collection->amount_per_student) . ") via this link:\n" . $classUrl . "\n\nYou can pay in instalments — any amount from KES 50.\n— " . $class->teacher_name);

            $students = $class->payments->where('status', 'paid')
                ->groupBy(fn($p) => $p->student_id ?: 'name:'.strtolower(trim($p->student_name)))
                ->map(fn($pmts) => [
                    'student_id' => $pmts->first()->student_id,
                    'name'       => $pmts->first()->student_name,
                    'total_paid' => $pmts->sum('amount'),
                    'balance'    => max(0, $required - $pmts->sum('amount')),
                    'is_full'    => $pmts->sum('amount') >= $required,
                ])
                ->sortByDesc('is_full')->values();

            $pendingList = $class->payments->where('status', 'pending');
            $fullCount   = $students->where('is_full', true)->count();
            $partCount   = $students->where('is_full', false)->count();
        @endphp

        <div class="acc-item">
            <!-- Collapsed header -->
            <div class="acc-header" onclick="toggleAcc({{ $class->id }})">
                <div class="acc-left">
                    <div class="acc-class-name">{{ $class->class_name }}</div>
                    <div class="acc-teacher">{{ $class->teacher_name }}</div>
                </div>
                <div class="acc-right">
                    @if($fullCount)  <span class="acc-stat green">{{ $fullCount }} full</span>@endif
                    @if($partCount)  <span class="acc-stat amber">{{ $partCount }} partial</span>@endif
                    @if($pendingList->count()) <span class="acc-stat red">{{ $pendingList->count() }} pending</span>@endif
                    @if(!$students->count()) <span style="font-size:11px;color:rgba(255,255,255,.2)">No payments</span>@endif
                    <span class="acc-amount">KES {{ number_format($class->total_raised) }}</span>
                    <span class="acc-chevron" id="chev-{{ $class->id }}">▾</span>
                </div>
            </div>

            <!-- Expanded body -->
            <div class="acc-body" id="body-{{ $class->id }}">

                <div class="link-section">
                    <div class="link-lbl">Parent Payment Link</div>
                    <div class="class-link-row">
                        <input class="class-link-input" readonly value="{{ $classUrl }}" id="link-{{ $class->id }}">
                        <button class="copy-cls-btn" onclick="doCopy('link-{{ $class->id }}', this)">Copy</button>
                        <a href="https://wa.me/?text={{ $waMsg }}" target="_blank" class="wa-cls-btn">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            WhatsApp
                        </a>
                    </div>
                </div>
                <div class="link-section">
                    <div class="link-lbl">Teacher Tracking Link (private)</div>
                    <div class="class-link-row">
                        <input class="class-link-input" readonly value="{{ $teacherUrl }}" id="tlink-{{ $class->id }}">
                        <button class="copy-cls-btn" onclick="doCopy('tlink-{{ $class->id }}', this)">Copy</button>
                        <a href="https://wa.me/?text={{ urlencode('Hi ' . $class->teacher_name . ', here is your private collection tracker for ' . $class->class_name . ': ' . $teacherUrl) }}" target="_blank" class="wa-cls-btn">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            Send to Teacher
                        </a>
                    </div>
                </div>

                @if($students->count())
                <button class="stu-toggle" onclick="toggleStu(this, 'stu-{{ $class->id }}')">
                    ▾ Show {{ $students->count() }} student{{ $students->count() === 1 ? '' : 's' }}
                </button>
                <div style="display:none" id="stu-{{ $class->id }}">
                    <table class="stu-table">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th style="text-align:right">Paid</th>
                                <th style="text-align:right">Remaining</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($students as $s)
                        <tr>
                            <td class="mono">{{ $s['student_id'] ?: '—' }}</td>
                            <td>{{ $s['name'] }}</td>
                            <td>@if($s['is_full'])<span class="badge-full">✓ Full</span>@else<span class="badge-part">Partial</span>@endif</td>
                            <td class="amt">KES {{ number_format($s['total_paid']) }}</td>
                            <td style="text-align:right;color:{{ $s['is_full'] ? 'rgba(255,255,255,.2)' : '#fbbf24' }};font-size:12px">
                                {{ $s['is_full'] ? '—' : 'KES '.number_format($s['balance']) }}
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div style="font-size:12px;color:rgba(255,255,255,.25);margin-top:6px">No payments yet.</div>
                @endif

                @if($pendingList->count())
                <div class="pending-section">
                    <div class="pending-lbl">⏳ Pending M-Pesa ({{ $pendingList->count() }})</div>
                    @foreach($pendingList as $p)
                    <div class="pending-row">
                        <div>
                            <span style="color:rgba(255,255,255,.6)">{{ $p->student_name }}</span>
                            @if($p->student_id)<span style="font-size:10px;color:rgba(255,255,255,.25);font-family:monospace;margin-left:6px">{{ $p->student_id }}</span>@endif
                        </div>
                        <span style="color:#fbbf24;font-weight:700">KES {{ number_format($p->gross_amount) }}</span>
                    </div>
                    @endforeach
                </div>
                @endif

            </div><!-- /acc-body -->
        <div class="class-card">
            <div class="class-card-header">
                <div>
                    <div class="class-name">{{ $class->class_name }}</div>
                    <div class="class-teacher">{{ $class->teacher_name }}</div>
                </div>
                <div class="class-raised">
                    <div class="amount">KES {{ number_format($class->total_raised) }}</div>
                    <div class="count">{{ $students->count() }} student{{ $students->count() === 1 ? '' : 's' }}</div>
                </div>
            </div>

            <!-- Links -->
            <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.3);margin-bottom:5px">Parent Payment Link</div>
            <div class="class-link-row">
                <input class="class-link-input" readonly value="{{ $classUrl }}" id="link-{{ $class->id }}">
                <button class="copy-cls-btn" onclick="doCopy('link-{{ $class->id }}', this)">Copy</button>
                <a href="https://wa.me/?text={{ $waMsg }}" target="_blank" class="wa-cls-btn">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    WhatsApp
                </a>
            </div>
            <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.3);margin:10px 0 5px">Teacher Tracking Link (private)</div>
            <div class="class-link-row">
                <input class="class-link-input" readonly value="{{ $teacherUrl }}" id="tlink-{{ $class->id }}">
                <button class="copy-cls-btn" onclick="doCopy('tlink-{{ $class->id }}', this)">Copy</button>
                <a href="https://wa.me/?text={{ urlencode('Hi ' . $class->teacher_name . ', here is your private collection tracker for ' . $class->class_name . ': ' . $teacherUrl) }}" target="_blank" class="wa-cls-btn">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Send to Teacher
                </a>
            </div>

        </div><!-- /acc-item -->
        @endforeach
    </div>

</div>

<script>
function toggleAcc(id) {
    const body  = document.getElementById('body-' + id);
    const chev  = document.getElementById('chev-' + id);
    const open  = body.style.display === 'block';
    body.style.display = open ? 'none' : 'block';
    chev.classList.toggle('open', !open);
}
function toggleStu(btn, id) {
    const el   = document.getElementById(id);
    const open = el.style.display === 'block';
    el.style.display = open ? 'none' : 'block';
    btn.textContent  = (open ? '▾ Show' : '▴ Hide') + btn.textContent.replace(/^[▾▴] (Show|Hide)/, '');
}
function doCopy(id, btn) {
    navigator.clipboard.writeText(document.getElementById(id).value).then(() => {
        const orig = btn.textContent;
        btn.textContent = '✓ Copied';
        setTimeout(() => btn.textContent = orig, 2000);
    });
}
</script>
</body>
</html>
