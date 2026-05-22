<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Partners — Pregota Admin</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0f0f1a;color:#fff;min-height:100vh}
.nav{padding:14px 28px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.08)}
.logo{font-size:18px;font-weight:900;background:linear-gradient(135deg,#7c3aed,#db2777);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.nav-links{display:flex;gap:12px}
.back{color:rgba(255,255,255,.4);font-size:13px;text-decoration:none}
.main{padding:28px;max-width:960px;margin:0 auto}
h1{font-size:20px;font-weight:800;margin-bottom:24px}
.alert{border-radius:10px;padding:12px 16px;margin-bottom:20px;font-size:13px}
.alert.success{background:rgba(34,197,94,.12);border:1px solid rgba(34,197,94,.3);color:#4ade80}
.grid{display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:32px}
@media(max-width:700px){.grid{grid-template-columns:1fr}}
.partner-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:18px}
.partner-card.inactive{opacity:.45}
.pc-head{display:flex;align-items:center;gap:10px;margin-bottom:8px}
.pc-emoji{font-size:24px}
.pc-name{font-size:15px;font-weight:700}
.pc-cat{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;padding:2px 8px;border-radius:999px;margin-left:auto}
.cat-shop{background:rgba(246,139,30,.15);color:#f68b1e}
.cat-save{background:rgba(34,197,94,.12);color:#4ade80}
.cat-invest{background:rgba(124,58,237,.15);color:#a78bfa}
.pc-tag{font-size:12px;color:rgba(255,255,255,.45);margin-bottom:12px;line-height:1.4}
.pc-url{font-size:11px;color:rgba(255,255,255,.25);font-family:monospace;margin-bottom:12px;word-break:break-all}
.pc-actions{display:flex;gap:8px}
.pc-btn{font-size:12px;font-weight:600;border:none;border-radius:6px;padding:6px 12px;cursor:pointer}
.btn-toggle-on{background:rgba(239,68,68,.15);color:#f87171}
.btn-toggle-off{background:rgba(34,197,94,.15);color:#4ade80}
.btn-delete{background:rgba(255,255,255,.06);color:rgba(255,255,255,.4)}
.btn-delete:hover{background:rgba(239,68,68,.15);color:#f87171}

/* Add form */
.add-form{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.09);border-radius:14px;padding:24px}
.add-form h2{font-size:15px;font-weight:700;margin-bottom:18px;color:rgba(255,255,255,.7)}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px}
.form-group{display:flex;flex-direction:column;gap:5px}
label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.4)}
input,select{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);border-radius:8px;padding:10px 12px;color:#fff;font-size:13px;outline:none}
input:focus,select:focus{border-color:#7c3aed}
select option{background:#1a1a2e}
.submit-btn{background:linear-gradient(135deg,#7c3aed,#db2777);color:#fff;border:none;border-radius:10px;padding:11px 24px;font-size:14px;font-weight:700;cursor:pointer;margin-top:4px}
</style>
</head>
<body>
<nav class="nav">
    <div class="logo">Pregota Admin</div>
    <div class="nav-links">
        <a href="{{ route('admin.dashboard') }}" class="back">Vouchers</a>
        <a href="{{ route('admin.partners') }}" class="back" style="color:#a78bfa">Partners</a>
    </div>
</nav>

<div class="main">
    <h1>Partner Brands</h1>

    @if(session('success'))
    <div class="alert success">{{ session('success') }}</div>
    @endif

    @php
    $byCategory = $partners->groupBy('category');
    $catLabels  = ['shop' => '🛍️ Shop', 'save' => '🏦 Save', 'invest' => '📈 Invest'];
    @endphp

    @foreach(['shop','save','invest'] as $cat)
    @if(isset($byCategory[$cat]))
    <div style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.35);margin-bottom:12px">
        {{ $catLabels[$cat] }}
    </div>
    <div class="grid">
        @foreach($byCategory[$cat] as $p)
        <div class="partner-card {{ $p->is_active ? '' : 'inactive' }}">
            <div class="pc-head">
                <span class="pc-emoji">{{ $p->logo_emoji }}</span>
                <span class="pc-name">{{ $p->name }}</span>
                <span class="pc-cat cat-{{ $p->category }}">{{ $p->category }}</span>
            </div>
            <div class="pc-tag">{{ $p->tagline }}</div>
            <div class="pc-url">{{ $p->url }}</div>
            <div class="pc-actions">
                <form method="POST" action="{{ route('admin.partners.toggle', $p) }}" style="display:inline">
                    @csrf
                    <button type="submit" class="pc-btn {{ $p->is_active ? 'btn-toggle-on' : 'btn-toggle-off' }}">
                        {{ $p->is_active ? 'Deactivate' : 'Activate' }}
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.partners.delete', $p) }}" style="display:inline"
                    onsubmit="return confirm('Remove {{ $p->name }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="pc-btn btn-delete">Remove</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @endif
    @endforeach

    <!-- Add new partner -->
    <div class="add-form">
        <h2>Add Partner</h2>
        <form method="POST" action="{{ route('admin.partners.create') }}">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" placeholder="e.g. Jumia Kenya" required>
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <select name="category" required>
                        <option value="shop">Shop</option>
                        <option value="save">Save</option>
                        <option value="invest">Invest</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Tagline</label>
                    <input type="text" name="tagline" placeholder="Short description">
                </div>
                <div class="form-group">
                    <label>CTA Button Text</label>
                    <input type="text" name="cta_text" placeholder="e.g. Shop Now" value="Visit" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Emoji / Logo</label>
                    <input type="text" name="logo_emoji" placeholder="🛍️" maxlength="10" value="🏢">
                </div>
                <div class="form-group">
                    <label>Brand Color</label>
                    <input type="text" name="brand_color" placeholder="#F68B1E" value="#7c3aed" maxlength="20">
                </div>
            </div>
            <div class="form-group" style="margin-bottom:14px">
                <label>Partner URL</label>
                <input type="url" name="url" placeholder="https://partner.co.ke" required>
            </div>
            <button type="submit" class="submit-btn">Add Partner</button>
        </form>
    </div>
</div>
</body>
</html>
