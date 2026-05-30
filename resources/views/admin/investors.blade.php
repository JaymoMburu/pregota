<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pregota Admin — Investors</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}input,textarea,select,button{font-family:inherit;font-size:inherit}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh}
.nav{padding:14px 28px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.08);background:rgba(0,0,0,.3)}
.logo{font-size:18px;font-weight:900;background:linear-gradient(135deg,#00A651,#007A33);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.nav-links{display:flex;gap:20px;align-items:center}
.nav-links a{color:rgba(255,255,255,.68);font-size:13px;text-decoration:none}
.nav-links a:hover,.nav-links a.active{color:#a78bfa}
.main{padding:28px}
h1{font-size:20px;font-weight:800;margin-bottom:4px}
.sub{font-size:13px;color:rgba(255,255,255,.68);margin-bottom:24px}
.success{background:rgba(52,211,153,.1);border:1px solid rgba(52,211,153,.25);color:#6ee7b7;border-radius:10px;padding:11px 14px;font-size:13px;margin-bottom:18px}
.error-msg{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);color:#f87171;border-radius:10px;padding:11px 14px;font-size:13px;margin-bottom:18px}

/* Form card */
.form-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:16px;padding:22px 24px;margin-bottom:28px}
.form-card h2{font-size:14px;font-weight:700;margin-bottom:16px;color:#a78bfa}
.fg{display:grid;grid-template-columns:1fr 1fr;gap:14px}
.field{display:flex;flex-direction:column;gap:5px}
.field label{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.6)}
.field input,.field select,.field textarea{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:9px;padding:9px 12px;color:#fff;font-size:13px;outline:none}
.field select option{background:#0B1810;color:#fff}
.field input:focus,.field select:focus{border-color:rgba(0,166,81,.5)}
.btn-add{background:linear-gradient(135deg,#00A651,#007A33);border:none;border-radius:9px;padding:10px 20px;color:#fff;font-size:13px;font-weight:700;cursor:pointer;margin-top:8px}

/* Table */
.panel{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.08);border-radius:16px;overflow:hidden}
.panel-head{padding:16px 20px;border-bottom:1px solid rgba(255,255,255,.07);display:flex;justify-content:space-between;align-items:center}
.panel-head h2{font-size:15px;font-weight:700}
table{width:100%;border-collapse:collapse;font-size:13px}
th{padding:10px 14px;text-align:left;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.6);background:rgba(255,255,255,.03)}
td{padding:12px 14px;border-top:1px solid rgba(255,255,255,.05);color:rgba(255,255,255,.8);vertical-align:middle}
tr:hover td{background:rgba(255,255,255,.02)}
.badge{display:inline-flex;padding:2px 10px;border-radius:999px;font-size:11px;font-weight:700}
.badge.active{background:rgba(52,211,153,.15);color:#4ade80}
.badge.inactive{background:rgba(255,255,255,.08);color:rgba(255,255,255,.68)}
.type-badge{display:inline-block;background:rgba(0,166,81,.15);color:#a78bfa;font-size:11px;font-weight:700;padding:2px 8px;border-radius:6px}
.action-btn{font-size:11px;font-weight:700;padding:4px 10px;border-radius:6px;border:1px solid;cursor:pointer;background:none}
.action-btn.toggle{border-color:rgba(251,191,36,.3);color:#fbbf24}
.action-btn.toggle:hover{background:rgba(251,191,36,.1)}
.action-btn.reset{border-color:rgba(0,166,81,.3);color:#a78bfa;margin-left:6px}
.action-btn.reset:hover{background:rgba(0,166,81,.1)}
.pw-form{display:inline}
.pw-input{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:6px;padding:3px 8px;color:#fff;font-size:11px;width:110px;outline:none;margin-left:6px}
</style>
</head>
<body>
<nav class="nav">
    <div class="logo">Pregota Admin</div>
    <div class="nav-links">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <a href="{{ route('admin.investors') }}" class="active">Investors</a>
        <a href="{{ route('admin.partners') }}">Partners</a>
        <a href="{{ route('admin.businesses') }}">Businesses</a>
        <a href="{{ route('home') }}">← Live Site</a>
        <form method="POST" action="{{ route('admin.logout') }}" style="display:inline">
            @csrf
            <button type="submit" style="background:none;border:none;color:rgba(255,255,255,.82);cursor:pointer;font-size:13px">Logout</button>
        </form>
    </div>
</nav>

<div class="main">
    <h1>Investor Management</h1>
    <p class="sub">Create and manage investor portal access. Each investor gets a private read-only dashboard.</p>

    @if(session('success'))<div class="success">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="error-msg">{{ $errors->first() }}</div>@endif

    {{-- Create investor form --}}
    <div class="form-card">
        <h2>Add New Investor</h2>
        <form method="POST" action="{{ route('admin.investors.create') }}">
            @csrf
            <div class="fg">
                <div class="field">
                    <label>Full Name</label>
                    <input type="text" name="name" placeholder="John Kamau" required>
                </div>
                <div class="field">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="investor@example.com" required>
                </div>
                <div class="field">
                    <label>Password (min 8 chars)</label>
                    <input type="text" name="password" placeholder="Set a strong password" required>
                </div>
                <div class="field">
                    <label>Investor Type</label>
                    <select name="investor_type">
                        <option value="angel">Angel Investor</option>
                        <option value="vc">Venture Capital</option>
                        <option value="strategic">Strategic Partner</option>
                        <option value="grant">Grant / Non-dilutive</option>
                    </select>
                </div>
                <div class="field">
                    <label>Equity % (optional)</label>
                    <input type="number" name="equity_pct" placeholder="e.g. 5.00" step="0.01" min="0" max="100">
                </div>
                <div class="field">
                    <label>Amount Invested (KES, optional)</label>
                    <input type="number" name="amount_invested_kes" placeholder="e.g. 1500000" step="0.01" min="0">
                </div>
            </div>
            <div class="field" style="margin-top:12px">
                <label>Notes (optional)</label>
                <input type="text" name="notes" placeholder="e.g. Lead angel, introduced via Nairobi garage">
            </div>
            <button type="submit" class="btn-add">Create Investor Account →</button>
        </form>
    </div>

    {{-- Investor list --}}
    <div class="panel">
        <div class="panel-head">
            <h2>Investors ({{ $investors->count() }})</h2>
            <span style="font-size:12px;color:rgba(255,255,255,.82)">Each investor logs in at /investors/login</span>
        </div>
        @if($investors->isEmpty())
        <div style="padding:32px;text-align:center;color:rgba(255,255,255,.82);font-size:13px">No investors added yet.</div>
        @else
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Type</th>
                    <th>Equity</th>
                    <th>Invested (KES)</th>
                    <th>Status</th>
                    <th>Last Login</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($investors as $inv)
                <tr>
                    <td><strong>{{ $inv->name }}</strong></td>
                    <td style="color:rgba(255,255,255,.78)">{{ $inv->email }}</td>
                    <td><span class="type-badge">{{ $inv->typeLabel() }}</span></td>
                    <td>{{ $inv->equity_pct ? $inv->equity_pct . '%' : '—' }}</td>
                    <td>{{ $inv->amount_invested_kes ? 'KES ' . number_format($inv->amount_invested_kes, 0) : '—' }}</td>
                    <td><span class="badge {{ $inv->is_active ? 'active' : 'inactive' }}">{{ $inv->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td style="color:rgba(255,255,255,.68);font-size:12px">{{ $inv->last_login_at ? $inv->last_login_at->format('d M y, H:i') : 'Never' }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.investors.toggle', $inv) }}" style="display:inline">
                            @csrf
                            <button type="submit" class="action-btn toggle">{{ $inv->is_active ? 'Deactivate' : 'Activate' }}</button>
                        </form>
                        <form method="POST" action="{{ route('admin.investors.reset-password', $inv) }}" class="pw-form">
                            @csrf
                            <input type="text" name="password" class="pw-input" placeholder="New password">
                            <button type="submit" class="action-btn reset">Reset</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
</body>
</html>
