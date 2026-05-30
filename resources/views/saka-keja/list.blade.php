﻿﻿﻿<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>List Your Property Â· Saka Keja</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800;900&display=swap" rel="stylesheet">
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}input,textarea,select,button{font-family:inherit;font-size:inherit}
body{font-family:'Plus Jakarta Sans',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh;padding:20px-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}
.card{max-width:480px;width:100%;margin:0 auto;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.09);border-radius:22px;padding:32px 26px}
.logo{font-size:18px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;display:block;margin-bottom:6px;text-decoration:none}
.brand{font-size:13px;font-weight:800;color:#f59e0b;margin-bottom:24px;display:block}
.title{font-size:22px;font-weight:900;margin-bottom:6px}
.sub{font-size:13px;color:rgba(255,255,255,.72);margin-bottom:24px;line-height:1.6}
.field{margin-bottom:16px}
.field label{display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.72);margin-bottom:7px}
.field input,.field textarea{width:100%;padding:13px 14px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:11px;color:#fff;font-size:15px;outline:none;font-family:inherit;transition:.2s}
.field textarea{min-height:80px;resize:vertical}
.field input:focus,.field textarea:focus{border-color:rgba(245,158,11,.4);background:rgba(245,158,11,.04)}
.field input[type=file]{padding:10px}
.field input::placeholder,.field textarea::placeholder{color:rgba(255,255,255,.25)}

.type-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:8px}
.type-btn{padding:10px 6px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.09);border-radius:10px;text-align:center;cursor:pointer;transition:.15s;font-size:12px;font-weight:700;color:rgba(255,255,255,.78)}
.type-btn:hover,.type-btn.selected{background:rgba(245,158,11,.1);border-color:rgba(245,158,11,.35);color:#f59e0b}
.type-icon{font-size:22px;display:block;margin-bottom:4px}

.photo-drop{border:2px dashed rgba(255,255,255,.12);border-radius:13px;padding:28px;text-align:center;cursor:pointer;transition:.2s;margin-bottom:4px}
.photo-drop:hover,.photo-drop.dragover{border-color:rgba(245,158,11,.4);background:rgba(245,158,11,.04)}
.photo-drop-icon{font-size:32px;margin-bottom:8px}
.photo-drop-text{font-size:13px;color:rgba(255,255,255,.72);line-height:1.5}
.photo-drop-text strong{color:rgba(255,255,255,.7)}
.photo-previews{display:flex;gap:8px;flex-wrap:wrap;margin-top:10px}
.photo-preview-wrap{position:relative}
.photo-preview{width:72px;height:72px;object-fit:cover;border-radius:9px;border:1px solid rgba(255,255,255,.1)}
.photo-remove{position:absolute;top:-6px;right:-6px;background:#ef4444;border:none;border-radius:999px;width:20px;height:20px;color:#fff;font-size:12px;cursor:pointer;display:flex;align-items:center;justify-content:center}

.btn{width:100%;padding:15px;background:linear-gradient(135deg,#d97706,#f59e0b);color:#0B141A;font-size:15px;font-weight:900;border:none;border-radius:13px;cursor:pointer;margin-top:6px;transition:.15s}
.btn:disabled{opacity:.45;cursor:not-allowed}
.note{font-size:11px;color:rgba(255,255,255,.65);text-align:center;margin-top:12px;line-height:1.6}
.err{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);border-radius:9px;padding:10px 14px;font-size:13px;color:#fca5a5;margin-top:12px;display:none}
.pending{display:none;text-align:center;padding:20px 0}
.spinner{width:44px;height:44px;border:3px solid rgba(255,255,255,.1);border-top-color:#f59e0b;border-radius:50%;animation:spin .8s linear infinite;margin:0 auto 16px}
@keyframes spin{to{transform:rotate(360deg)}}
</style>
</head>
<body>
<div class="card">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    <span class="brand">ðŸ  Saka Keja</span>

    <div id="form-view">
        <div class="title">List Your Property</div>
        <div class="sub">Pay KES 200 via M-Pesa to verify your number and go live. Seekers pay KES 200 to connect with you directly.</div>

        <div class="field">
            <label>Your Name</label>
            <input type="text" id="landlord_name" placeholder="e.g. John Kamau" maxlength="100">
        </div>
        <div class="field">
            <label>Your M-Pesa Number</label>
            <input type="tel" id="phone" placeholder="07XX XXX XXX" autocomplete="tel">
        </div>
        <div class="field">
            <label>Unit Type</label>
            <div class="type-grid" id="type-grid">
                <div class="type-btn" data-value="bedsitter"><span class="type-icon">ðŸ›ï¸</span>Bedsitter</div>
                <div class="type-btn" data-value="studio"><span class="type-icon">ðŸšª</span>Studio</div>
                <div class="type-btn" data-value="1br"><span class="type-icon">ðŸ </span>1 Bedroom</div>
                <div class="type-btn" data-value="2br"><span class="type-icon">ðŸ¡</span>2 Bedrooms</div>
                <div class="type-btn" data-value="3br"><span class="type-icon">ðŸ˜ï¸</span>3 Bedrooms</div>
                <div class="type-btn" data-value="shop"><span class="type-icon">ðŸª</span>Shop</div>
            </div>
            <input type="hidden" id="unit_type">
        </div>
        <div class="field">
            <label>Location / Estate / Area</label>
            <input type="text" id="location" placeholder="e.g. Kasarani, near KPA stage" maxlength="150">
        </div>
        <div class="field">
            <label>Monthly Rent (KES)</label>
            <input type="number" id="rent" placeholder="e.g. 8000" min="500" max="500000">
        </div>
        <div class="field">
            <label>Description (optional)</label>
            <textarea id="description" placeholder="e.g. 2nd floor, water included, secure compound, 5 mins from stage..." maxlength="1000"></textarea>
        </div>
        <div class="field">
            <label>Photos (at least 1, up to 8)</label>
            <div class="photo-drop" id="photo-drop" onclick="document.getElementById('photo-input').click()">
                <div class="photo-drop-icon">ðŸ“¸</div>
                <div class="photo-drop-text"><strong>Tap to add photos</strong><br>JPG, PNG, WebP â€” max 5MB each</div>
            </div>
            <input type="file" id="photo-input" accept="image/jpg,image/jpeg,image/png,image/webp" multiple style="display:none">
            <div class="photo-previews" id="photo-previews"></div>
        </div>

        <div class="err" id="err-msg"></div>
        <button class="btn" id="submit-btn" onclick="doSubmit()">List Property â€” Pay KES 200 â†’</button>
        <div class="note">Your number is verified via M-Pesa. Seekers never see your number â€” they pay to connect and you call them.</div>
    </div>

    <div class="pending" id="pending-view">
        <div class="spinner"></div>
        <div style="font-size:15px;font-weight:700;margin-bottom:6px">Check your phone</div>
        <div style="font-size:13px;color:rgba(255,255,255,.72)">Enter your M-Pesa PIN to confirm KES 200 and publish your listing.</div>
    </div>
</div>

<script>
const CSRF    = '{{ csrf_token() }}';
let selectedType = '';
let selectedFiles = [];
let checkoutId = null;

// Unit type selection
document.querySelectorAll('.type-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.type-btn').forEach(b => b.classList.remove('selected'));
        btn.classList.add('selected');
        selectedType = btn.dataset.value;
        document.getElementById('unit_type').value = selectedType;
    });
});

// Photo handling
const photoInput = document.getElementById('photo-input');
const photoDrop  = document.getElementById('photo-drop');
const previews   = document.getElementById('photo-previews');

photoInput.addEventListener('change', e => addFiles(Array.from(e.target.files)));

photoDrop.addEventListener('dragover', e => { e.preventDefault(); photoDrop.classList.add('dragover'); });
photoDrop.addEventListener('dragleave', () => photoDrop.classList.remove('dragover'));
photoDrop.addEventListener('drop', e => {
    e.preventDefault();
    photoDrop.classList.remove('dragover');
    addFiles(Array.from(e.dataTransfer.files));
});

function addFiles(files) {
    files.forEach(file => {
        if (selectedFiles.length >= 8) return;
        if (!file.type.startsWith('image/')) return;
        selectedFiles.push(file);
        const wrap = document.createElement('div');
        wrap.className = 'photo-preview-wrap';
        const idx = selectedFiles.length - 1;
        wrap.innerHTML = `<img class="photo-preview" src="${URL.createObjectURL(file)}"><button class="photo-remove" onclick="removePhoto(${idx}, this.parentNode)">Ã—</button>`;
        previews.appendChild(wrap);
    });
}

function removePhoto(idx, wrap) {
    selectedFiles.splice(idx, 1);
    wrap.remove();
}

async function doSubmit() {
    const err = document.getElementById('err-msg');
    err.style.display = 'none';

    const name     = document.getElementById('landlord_name').value.trim();
    const phone    = document.getElementById('phone').value.trim();
    const location = document.getElementById('location').value.trim();
    const rent     = document.getElementById('rent').value.trim();
    const desc     = document.getElementById('description').value.trim();

    if (!name)     { err.textContent = 'Enter your name.'; err.style.display = 'block'; return; }
    if (!phone || !/^(\+?254|0)[17]\d{8}$/.test(phone)) { err.textContent = 'Enter a valid Safaricom number.'; err.style.display = 'block'; return; }
    if (!selectedType) { err.textContent = 'Select a unit type.'; err.style.display = 'block'; return; }
    if (!location) { err.textContent = 'Enter the location/area.'; err.style.display = 'block'; return; }
    if (!rent || parseInt(rent) < 500) { err.textContent = 'Enter a valid monthly rent.'; err.style.display = 'block'; return; }
    if (selectedFiles.length === 0)  { err.textContent = 'Add at least one photo.'; err.style.display = 'block'; return; }

    document.getElementById('submit-btn').disabled = true;

    const formData = new FormData();
    formData.append('landlord_name', name);
    formData.append('phone', phone);
    formData.append('unit_type', selectedType);
    formData.append('location', location);
    formData.append('rent', rent);
    formData.append('description', desc);
    formData.append('_token', CSRF);
    selectedFiles.forEach(f => formData.append('photos[]', f));

    let data;
    try {
        const res = await fetch('{{ route("saka-keja.list.post") }}', { method: 'POST', body: formData });
        data = await res.json();
    } catch(e) {
        err.textContent = 'Network error. Please try again.';
        err.style.display = 'block';
        document.getElementById('submit-btn').disabled = false;
        return;
    }

    if (!data.success) {
        err.textContent = data.message || 'Something went wrong.';
        err.style.display = 'block';
        document.getElementById('submit-btn').disabled = false;
        return;
    }

    checkoutId = data.checkout_request_id;
    document.getElementById('form-view').style.display = 'none';
    document.getElementById('pending-view').style.display = 'block';
    pollListing();
}

function pollListing() {
    fetch('{{ route("saka-keja.list.poll") }}?checkout_request_id=' + checkoutId)
        .then(r => r.json())
        .then(d => {
            if (d.status === 'confirmed') {
                window.location.href = d.redirect;
            } else if (d.status === 'failed') {
                document.getElementById('pending-view').style.display = 'none';
                document.getElementById('form-view').style.display = 'block';
                const err = document.getElementById('err-msg');
                err.textContent = 'Payment failed or cancelled. Please try again.';
                err.style.display = 'block';
                document.getElementById('submit-btn').disabled = false;
            } else {
                setTimeout(pollListing, 2500);
            }
        })
        .catch(() => setTimeout(pollListing, 3000));
}
</script>
</body>
</html>

