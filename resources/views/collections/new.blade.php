<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Start a Collection — Pregota</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0f0f1a;color:#fff;min-height:100vh;display:flex}

.panel-left{width:45%;height:100vh;position:sticky;top:0;background:radial-gradient(circle 260px at -40px -80px,rgba(124,58,237,.35),transparent 70%),radial-gradient(circle 200px at calc(100% + 20px) 100%,rgba(219,39,119,.28),transparent 70%),linear-gradient(150deg,#0a0015,#1e0840 55%,#2d0a4e);display:flex;flex-direction:column;padding:40px 44px;overflow:hidden}
.left-logo{font-size:22px;font-weight:900;position:relative;z-index:1;background:linear-gradient(135deg,#c084fc,#f472b6);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.left-center{flex:1;display:flex;flex-direction:column;justify-content:center;position:relative;z-index:1;gap:40px;padding:40px 0}
.headline h1{font-size:clamp(28px,3.2vw,44px);font-weight:900;line-height:1.12;letter-spacing:-.5px}
.headline h1 em{font-style:normal;background:linear-gradient(135deg,#c084fc,#f472b6);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.headline p{margin-top:12px;font-size:14px;color:rgba(255,255,255,.45);line-height:1.65;max-width:300px}
.benefit-list{display:flex;flex-direction:column;gap:16px}
.benefit{display:flex;align-items:flex-start;gap:14px}
.benefit-icon{width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#7c3aed,#db2777);display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0;margin-top:1px}
.benefit-text strong{font-size:13px;color:rgba(255,255,255,.85);display:block;margin-bottom:2px}
.benefit-text span{font-size:12px;color:rgba(255,255,255,.4);line-height:1.5}
.left-foot{margin-top:auto;position:relative;z-index:1;font-size:11px;color:rgba(255,255,255,.3)}

.panel-right{width:55%;min-height:100vh;background:#0f0f1a;display:flex;flex-direction:column;border-left:1px solid rgba(255,255,255,.06)}
.right-nav{padding:16px 32px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.06)}
.logo-sm{font-size:18px;font-weight:900;background:linear-gradient(135deg,#c084fc,#f472b6);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.right-body{flex:1;padding:32px;overflow-y:auto}
.form-wrap{max-width:480px}
.form-title{font-size:20px;font-weight:900;margin-bottom:22px}
.form-section{margin-bottom:22px}
.section-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.35);margin-bottom:12px}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.form-group{margin-bottom:14px}
label{display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.5);margin-bottom:6px}
input,select,textarea{width:100%;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.15);border-radius:10px;padding:12px 14px;color:#fff;font-size:14px;outline:none;transition:.2s;font-family:inherit}
input:focus,select:focus,textarea:focus{border-color:#7c3aed;background:rgba(124,58,237,.08)}
input::placeholder{color:rgba(255,255,255,.3)}
select option{background:#1a1a2e}
.hint{font-size:11px;color:rgba(255,255,255,.35);margin-top:5px}

.occasion-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-bottom:4px}
.occ-btn{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.1);border-radius:10px;padding:12px 6px;cursor:pointer;text-align:center;transition:.15s}
.occ-btn:hover,.occ-btn.selected{border-color:#7c3aed;background:rgba(124,58,237,.15)}
.occ-emoji{font-size:22px;display:block;margin-bottom:4px}
.occ-label{font-size:11px;color:rgba(255,255,255,.6);font-weight:600}
.occ-btn.selected .occ-label{color:#c084fc}
input[type=hidden]{}

.trigger-opts{display:flex;flex-direction:column;gap:8px}
.trigger-opt{display:flex;align-items:flex-start;gap:10px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:10px;padding:12px;cursor:pointer}
.trigger-opt:has(input:checked){border-color:#7c3aed;background:rgba(124,58,237,.08)}
.trigger-opt input[type=radio]{margin-top:2px;accent-color:#7c3aed;width:16px;height:16px;flex-shrink:0}
.trigger-opt-text strong{font-size:13px;color:rgba(255,255,255,.85);display:block;margin-bottom:2px}
.trigger-opt-text span{font-size:11px;color:rgba(255,255,255,.4);line-height:1.5}

.submit-btn{width:100%;padding:15px;border-radius:12px;border:none;font-size:16px;font-weight:700;cursor:pointer;background:linear-gradient(135deg,#7c3aed,#db2777);color:#fff;margin-top:6px;transition:.2s}
.submit-btn:hover{opacity:.9;transform:translateY(-1px)}
.alert.error{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);color:#f87171;border-radius:8px;padding:10px 12px;margin-bottom:14px;font-size:13px}
textarea{resize:vertical;min-height:90px}

/* Photo upload */
.photo-drop{border:2px dashed rgba(255,255,255,.12);border-radius:12px;padding:24px;text-align:center;cursor:pointer;transition:.2s;position:relative}
.photo-drop:hover,.photo-drop.over{border-color:#7c3aed;background:rgba(124,58,237,.06)}
.photo-drop input[type=file]{position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%}
.photo-drop-icon{font-size:28px;margin-bottom:6px}
.photo-drop-label{font-size:13px;color:rgba(255,255,255,.45);line-height:1.5}
.photo-drop-label strong{color:rgba(255,255,255,.7)}
.photo-preview{display:none;margin-top:12px;border-radius:10px;overflow:hidden;max-height:180px;position:relative}
.photo-preview img{width:100%;height:180px;object-fit:cover;display:block;border-radius:10px}
.photo-remove-btn{position:absolute;top:8px;right:8px;background:rgba(0,0,0,.65);border:none;border-radius:50%;width:28px;height:28px;color:#fff;font-size:14px;cursor:pointer;display:flex;align-items:center;justify-content:center}

@media(max-width:820px){
    body{flex-direction:column}
    .panel-left{width:100%;min-height:auto;padding:24px 20px}
    .left-center{gap:16px}
    .headline h1{font-size:26px}
    .panel-right{width:100%;border-left:none}
    .right-body{padding:20px}
    .b1,.b2{display:none}
}
</style>
</head>
<body>

<div class="panel-left">
    <a href="{{ route('home') }}" class="left-logo">Pregota</a>
    <div class="left-center">
        <div class="headline">
            <h1>Collect for any cause.<br><em>Zero reconciliation.</em></h1>
            <p>The organizer shares a link. Everyone contributes directly. The money goes straight to the person in need. The organizer's job is done.</p>
        </div>
        <div class="benefit-list">
            <div class="benefit">
                <div class="benefit-icon">📵</div>
                <div class="benefit-text">
                    <strong>Your number stays private</strong>
                    <span>No one needs to send to your personal M-Pesa. Share a link instead.</span>
                </div>
            </div>
            <div class="benefit">
                <div class="benefit-icon">📊</div>
                <div class="benefit-text">
                    <strong>Real-time ledger</strong>
                    <span>Everyone can see who has contributed and the running total — no disputes.</span>
                </div>
            </div>
            <div class="benefit">
                <div class="benefit-icon">⚡</div>
                <div class="benefit-text">
                    <strong>Direct payout — no middleman</strong>
                    <span>Funds go straight to the recipient's M-Pesa. The organiser never handles a shilling.</span>
                </div>
            </div>
            <div class="benefit">
                <div class="benefit-icon">🇰🇪</div>
                <div class="benefit-text">
                    <strong>Built for Kenyan groups</strong>
                    <span>Choir, church, office, chama — every group that contributes together.</span>
                </div>
            </div>
        </div>
    </div>
    <div class="left-foot">© 2026 Pregota · KES 30 service fee per contribution</div>
</div>

<div class="panel-right">
    @include('partials.module-nav', ['activeModule' => 'collection'])
    <nav class="right-nav">
        <a href="{{ route('home') }}" class="logo-sm">Pregota</a>
        <span style="font-size:12px;color:rgba(255,255,255,.35)">Collections · Quick Setup</span>
    </nav>
    <div class="right-body">
        <div class="form-wrap">
            <div class="form-title">Start a Collection</div>

            @if($errors->any())
            <div class="alert error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('collection.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- Occasion -->
                <div class="form-section">
                    <div class="section-label">What is this for?</div>
                    <div class="occasion-grid">
                        @foreach([
                            'bereavement' => ['🕊️','Bereavement'],
                            'wedding'     => ['💍','Wedding'],
                            'medical'     => ['🏥','Medical'],
                            'farewell'    => ['👋','Farewell'],
                            'education'   => ['🎓','Education'],
                            'other'       => ['🤝','Other'],
                        ] as $val => [$emoji, $label])
                        <div class="occ-btn {{ old('occasion', 'bereavement') === $val ? 'selected' : '' }}"
                             onclick="selectOccasion('{{ $val }}', this)">
                            <span class="occ-emoji">{{ $emoji }}</span>
                            <span class="occ-label">{{ $label }}</span>
                        </div>
                        @endforeach
                    </div>
                    <input type="hidden" name="occasion" id="occasion" value="{{ old('occasion', 'bereavement') }}">
                </div>

                <!-- Title & Organiser -->
                <div class="form-section">
                    <div class="section-label">Collection Details</div>
                    <div class="form-group">
                        <label>Collection Title</label>
                        <input type="text" name="title" id="titleInput"
                               placeholder="e.g. Grace Wanjiku Bereavement Welfare"
                               value="{{ old('title') }}" maxlength="120" required>
                    </div>
                    <div class="form-group">
                        <label>Your Name (Organiser)</label>
                        <input type="text" name="organiser_name" placeholder="Kamau Mwangi"
                               value="{{ old('organiser_name') }}" maxlength="60" required>
                    </div>
                    <div class="form-group">
                        <label>Your Contact Number <span style="font-weight:400;text-transform:none;letter-spacing:0">(optional — shown publicly)</span></label>
                        <input type="tel" name="organiser_phone" placeholder="07XX XXX XXX"
                               value="{{ old('organiser_phone') }}">
                        <div class="hint">Contributors can call or WhatsApp you to verify the collection. Leave blank to keep private.</div>
                    </div>
                </div>

                <!-- Description -->
                <div class="form-section">
                    <div class="section-label">Tell the Story (optional)</div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" placeholder="Briefly explain the cause — who it's for, what happened, why you're collecting. This appears on the collection page for contributors to read." maxlength="1500">{{ old('description') }}</textarea>
                        <div class="hint">Up to 1,500 characters. Shown publicly to contributors.</div>
                    </div>
                    <div class="form-group">
                        <label>Photo (optional)</label>
                        <div class="photo-drop" id="photoDrop">
                            <input type="file" name="photo" id="photoInput" accept="image/jpeg,image/jpg,image/png,image/webp" onchange="onPhotoChange(this)">
                            <div class="photo-drop-icon">🖼️</div>
                            <div class="photo-drop-label"><strong>Click to upload</strong> or drag &amp; drop<br>JPG, PNG or WebP · Max 4 MB</div>
                        </div>
                        <div class="photo-preview" id="photoPreview">
                            <img id="photoPreviewImg" src="" alt="Preview">
                            <button type="button" class="photo-remove-btn" onclick="removePhoto()" title="Remove photo">×</button>
                        </div>
                        <div class="hint">A photo of the person, church, family, or cause helps contributors connect.</div>
                    </div>
                </div>

                <!-- Recipient -->
                <div class="form-section">
                    <div class="section-label">Who Receives the Money?</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Recipient Name</label>
                            <input type="text" name="recipient_name" placeholder="Grace Wanjiku"
                                   value="{{ old('recipient_name') }}" maxlength="60" required>
                        </div>
                        <div class="form-group">
                            <label>Their M-Pesa Number</label>
                            <input type="tel" name="recipient_phone" placeholder="07XX XXX XXX" required>
                            <div class="hint">Encrypted. Never shown to contributors.</div>
                        </div>
                    </div>
                </div>

                <!-- Goal & Deadline -->
                <div class="form-section">
                    <div class="section-label">Target & Deadline (optional)</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Target Amount (KES)</label>
                            <input type="number" name="target_amount" placeholder="e.g. 30000"
                                   min="100" value="{{ old('target_amount') }}">
                        </div>
                        <div class="form-group">
                            <label>Deadline</label>
                            <input type="date" name="deadline" value="{{ old('deadline') }}"
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                        </div>
                    </div>
                </div>

                <!-- Payout trigger -->
                <div class="form-section">
                    <div class="section-label">When Should Money Be Paid Out?</div>
                    <div class="trigger-opts">
                        <label class="trigger-opt">
                            <input type="radio" name="payout_trigger" value="manual"
                                   {{ old('payout_trigger', 'manual') === 'manual' ? 'checked' : '' }}>
                            <div class="trigger-opt-text">
                                <strong>I will trigger it manually</strong>
                                <span>You decide when to pay out from your organiser dashboard.</span>
                            </div>
                        </label>
                        <label class="trigger-opt">
                            <input type="radio" name="payout_trigger" value="target"
                                   {{ old('payout_trigger') === 'target' ? 'checked' : '' }}>
                            <div class="trigger-opt-text">
                                <strong>Automatically when target is reached</strong>
                                <span>Pays out immediately once the goal amount is collected.</span>
                            </div>
                        </label>
                        <label class="trigger-opt">
                            <input type="radio" name="payout_trigger" value="deadline"
                                   {{ old('payout_trigger') === 'deadline' ? 'checked' : '' }}>
                            <div class="trigger-opt-text">
                                <strong>On the deadline date</strong>
                                <span>Reminder — you still trigger the payout from your dashboard on that day.</span>
                            </div>
                        </label>
                    </div>
                </div>

                <button type="submit" class="submit-btn">Create Collection →</button>
                <div style="text-align:center;margin-top:12px;font-size:11px;color:rgba(255,255,255,.25)">
                    KES 30 fee per contribution · Recipient gets 100% of pledged amounts
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const occasions = {
    bereavement: 'e.g. Grace Wanjiku Bereavement Welfare',
    wedding:     'e.g. John & Mary Wedding Contribution',
    medical:     'e.g. Peter Kamau Medical Support',
    farewell:    'e.g. Sarah Njeri Farewell Collection',
    education:   'e.g. Brian Ochieng Education Support',
    other:       'e.g. Choir Group Contribution',
};

function selectOccasion(val, el) {
    document.querySelectorAll('.occ-btn').forEach(b => b.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('occasion').value = val;
    const input = document.getElementById('titleInput');
    if (!input.value || Object.values(occasions).some(p => input.value === p || input.placeholder === input.value)) {
        input.placeholder = occasions[val];
    }
}

function onPhotoChange(input) {
    const file = input.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('photoPreviewImg').src = e.target.result;
        document.getElementById('photoPreview').style.display = 'block';
        document.getElementById('photoDrop').style.display   = 'none';
    };
    reader.readAsDataURL(file);
}

function removePhoto() {
    document.getElementById('photoInput').value      = '';
    document.getElementById('photoPreview').style.display = 'none';
    document.getElementById('photoDrop').style.display    = 'block';
    document.getElementById('photoPreviewImg').src   = '';
}

// Drag-and-drop highlight
const drop = document.getElementById('photoDrop');
drop.addEventListener('dragover', e => { e.preventDefault(); drop.classList.add('over'); });
drop.addEventListener('dragleave', () => drop.classList.remove('over'));
drop.addEventListener('drop', e => { e.preventDefault(); drop.classList.remove('over'); });
</script>
</body>
</html>
