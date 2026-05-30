<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $collection->title }} — Pregota</title>
@php use Illuminate\Support\Facades\Storage; @endphp
<meta name="csrf-token" content="{{ csrf_token() }}">
@php
    $ogImage = $collection->photo_path
        ? asset(Storage::url($collection->photo_path))
        : asset('img/pregota-og-default.png');
    $ogDescription = ($collection->description
        ? \Illuminate\Support\Str::limit(strip_tags($collection->description), 120)
        : 'Contribute to ' . $collection->recipient_name . ' — organised by ' . $collection->organiser_name . '.') . ' Pay securely via M-Pesa.';
@endphp
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="{{ $collection->title }}">
<meta property="og:description" content="{{ $ogDescription }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta name="twitter:card" content="{{ $collection->photo_path ? 'summary_large_image' : 'summary' }}">
<meta name="twitter:title" content="{{ $collection->title }}">
<meta name="twitter:description" content="{{ $ogDescription }}">
<meta name="twitter:image" content="{{ $ogImage }}">
<style>
*{box-sizing:border-box;margin:0;padding:0}input,textarea,select,button{font-family:inherit;font-size:inherit}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh}

.topbar{padding:14px 20px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.07);position:sticky;top:0;background:#0B141A;z-index:10}
.logo{font-size:18px;font-weight:900;background:linear-gradient(135deg,#25D366,#4ADE80);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.share-top-btn{display:flex;align-items:center;gap:6px;padding:8px 14px;border-radius:8px;background:#25d366;color:#fff;font-size:12px;font-weight:700;border:none;cursor:pointer;text-decoration:none}

.hero{padding:28px 20px 20px;max-width:600px;margin:0 auto}
.occasion-badge{display:inline-flex;align-items:center;gap:6px;padding:5px 12px;border-radius:20px;background:rgba(0,166,81,.18);border:1px solid rgba(0,166,81,.35);font-size:12px;color:#25D366;font-weight:600;margin-bottom:14px}
.collection-title{font-size:clamp(22px,5vw,32px);font-weight:900;line-height:1.15;margin-bottom:8px}
.meta-line{font-size:13px;color:rgba(255,255,255,.72);display:flex;flex-wrap:wrap;gap:12px;align-items:center}
.meta-sep{color:rgba(255,255,255,.2)}

.status-banner{margin:0 20px 20px;max-width:560px;margin-left:auto;margin-right:auto;padding:12px 16px;border-radius:10px;font-size:13px;font-weight:600;text-align:center}
.status-banner.closed{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);color:#f87171}
.status-banner.paid{background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.25);color:#4ade80}

.progress-card{margin:0 20px 20px;max-width:560px;margin-left:auto;margin-right:auto;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:20px}
.progress-amounts{display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:10px}
.raised-amount{font-size:28px;font-weight:900}
.raised-amount span{font-size:14px;font-weight:400;color:rgba(255,255,255,.68)}
.target-label{font-size:13px;color:rgba(255,255,255,.68)}
.progress-bar-wrap{height:8px;background:rgba(255,255,255,.08);border-radius:4px;overflow:hidden;margin-bottom:10px}
.progress-bar-fill{height:100%;background:linear-gradient(90deg,#00A651,#007A33);border-radius:4px;transition:.5s}
.progress-meta{display:flex;gap:16px;font-size:12px;color:rgba(255,255,255,.68)}
.progress-meta strong{color:rgba(255,255,255,.75)}

/* Photo hero */
.collection-photo{width:100%;max-width:600px;margin:0 auto 0;display:block;max-height:340px;object-fit:cover;border-bottom:1px solid rgba(255,255,255,.06)}
.collection-description{font-size:14px;color:rgba(255,255,255,.82);line-height:1.7;margin-top:12px;white-space:pre-wrap}

.main-grid{max-width:600px;margin:0 auto;padding:0 20px 40px;display:flex;flex-direction:column;gap:20px}

.card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:20px}
.card-title{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:rgba(255,255,255,.6);margin-bottom:14px}

/* Contribution form */
.amount-presets{display:grid;grid-template-columns:repeat(4,1fr);gap:8px;margin-bottom:12px}
.preset-btn{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);border-radius:8px;padding:10px 4px;font-size:13px;font-weight:700;color:rgba(255,255,255,.7);cursor:pointer;text-align:center;transition:.15s}
.preset-btn:hover,.preset-btn.active{border-color:#00A651;background:rgba(0,166,81,.15);color:#25D366}
.form-group{margin-bottom:12px}
label{display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.78);margin-bottom:6px}
input{width:100%;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.15);border-radius:10px;padding:12px 14px;color:#fff;font-size:16px;outline:none;transition:.2s;font-family:inherit}
input:focus{border-color:#00A651;background:rgba(0,166,81,.08)}
input::placeholder{color:rgba(255,255,255,.82)}
.fee-line{display:flex;justify-content:space-between;align-items:center;font-size:12px;color:rgba(255,255,255,.68);padding:8px 12px;background:rgba(255,255,255,.03);border-radius:8px;margin-bottom:12px}
.fee-line strong{color:rgba(255,255,255,.75)}
.hint{font-size:11px;color:rgba(255,255,255,.6);margin-top:5px}
.submit-btn{width:100%;padding:15px;border-radius:12px;border:none;font-size:16px;font-weight:700;cursor:pointer;background:linear-gradient(135deg,#00A651,#007A33);color:#fff;transition:.2s}
.submit-btn:hover:not(:disabled){opacity:.9;transform:translateY(-1px)}
.submit-btn:disabled{opacity:.5;cursor:not-allowed;transform:none}

/* Status overlay */
.status-overlay{display:none;text-align:center;padding:20px 0}
.spin{width:36px;height:36px;border:3px solid rgba(0,166,81,.25);border-top-color:#00A651;border-radius:50%;animation:spin .8s linear infinite;margin:0 auto 12px}
@keyframes spin{to{transform:rotate(360deg)}}
.status-icon{font-size:40px;margin-bottom:10px}
.status-msg{font-size:15px;font-weight:700;margin-bottom:6px}
.status-sub{font-size:13px;color:rgba(255,255,255,.72)}
.btn-sm{padding:10px 20px;border-radius:8px;border:1px solid rgba(255,255,255,.15);background:rgba(255,255,255,.07);color:#fff;font-size:13px;font-weight:600;cursor:pointer;margin-top:14px}

/* Contributor wall */
.contrib-list{display:flex;flex-direction:column;gap:8px}
.contrib-item{display:flex;align-items:center;gap:12px}
.contrib-avatar{width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#00A651,#007A33);display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;flex-shrink:0}
.contrib-info{flex:1}
.contrib-name{font-size:13px;font-weight:600;color:rgba(255,255,255,.85)}
.contrib-amount{font-size:14px;font-weight:700;color:#25D366}
.contrib-time{font-size:11px;color:rgba(255,255,255,.82)}
.empty-wall{text-align:center;padding:20px;font-size:13px;color:rgba(255,255,255,.82)}

/* Share card */
.share-card{background:linear-gradient(135deg,rgba(0,166,81,.15),rgba(0,122,51,.1));border:1px solid rgba(0,166,81,.25);border-radius:14px;padding:20px;text-align:center}
.share-card p{font-size:13px;color:rgba(255,255,255,.78);margin-bottom:14px}
.wa-btn{display:inline-flex;align-items:center;gap:8px;padding:12px 24px;border-radius:10px;background:#25d366;color:#fff;font-size:14px;font-weight:700;text-decoration:none;border:none;cursor:pointer}
.copy-link-btn{display:inline-flex;align-items:center;gap:6px;padding:10px 18px;border-radius:10px;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.15);color:rgba(255,255,255,.7);font-size:13px;font-weight:600;cursor:pointer;margin-left:8px}
.copy-link-btn.copied{color:#4ade80;border-color:rgba(74,222,128,.35)}

/* Organiser contact */
.contact-bar{max-width:600px;margin:0 auto 16px;padding:0 20px}
.contact-card{display:flex;align-items:center;justify-content:space-between;gap:12px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:12px;padding:12px 16px}
.contact-info{display:flex;flex-direction:column;gap:2px}
.contact-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.82)}
.contact-name{font-size:14px;font-weight:700;color:rgba(255,255,255,.85)}
.contact-actions{display:flex;gap:8px;flex-shrink:0}
.contact-call-btn{display:inline-flex;align-items:center;gap:6px;padding:8px 14px;border-radius:8px;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.12);color:rgba(255,255,255,.7);font-size:12px;font-weight:700;text-decoration:none;transition:.15s}
.contact-call-btn:hover{background:rgba(255,255,255,.12);color:#fff}
.contact-wa-btn{display:inline-flex;align-items:center;gap:6px;padding:8px 14px;border-radius:8px;background:rgba(37,211,102,.1);border:1px solid rgba(37,211,102,.25);color:#25d366;font-size:12px;font-weight:700;text-decoration:none;transition:.15s}
.contact-wa-btn:hover{background:rgba(37,211,102,.18)}

/* Frozen banner */
.frozen-banner{margin:0 20px 20px;max-width:560px;margin-left:auto;margin-right:auto;padding:14px 16px;border-radius:10px;background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3);color:#f87171;font-size:13px;font-weight:600;text-align:center}

/* Trust notice */
.trust-notice{max-width:560px;margin:0 auto 16px;padding:10px 14px;border-radius:9px;background:rgba(59,130,246,.06);border:1px solid rgba(59,130,246,.15);color:rgba(255,255,255,.68);font-size:11.5px;display:flex;align-items:center;gap:8px}
.report-link{display:inline;font-size:11.5px;color:#60a5fa;background:none;border:none;padding:0;cursor:pointer;text-decoration:underline}
.report-link:hover{color:#93c5fd}

/* Report modal */
.modal-backdrop{display:none;position:fixed;inset:0;background:rgba(0,0,0,.7);z-index:100;align-items:center;justify-content:center;padding:20px}
.modal-backdrop.open{display:flex}
.modal{background:#161624;border:1px solid rgba(255,255,255,.1);border-radius:16px;padding:28px;max-width:400px;width:100%}
.modal h3{font-size:16px;font-weight:800;margin-bottom:6px;color:#f87171}
.modal p{font-size:13px;color:rgba(255,255,255,.72);margin-bottom:16px;line-height:1.5}
.modal textarea{width:100%;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.15);border-radius:10px;padding:12px 14px;color:#fff;font-size:16px;outline:none;resize:vertical;min-height:90px;font-family:inherit}
.modal textarea:focus{border-color:#ef4444}
.modal-actions{display:flex;gap:10px;margin-top:14px}
.report-submit-btn{flex:1;padding:12px;border-radius:9px;border:none;background:#ef4444;color:#fff;font-size:14px;font-weight:700;cursor:pointer}
.report-submit-btn:hover{background:#dc2626}
.report-submit-btn:disabled{opacity:.5;cursor:not-allowed}
.modal-cancel-btn{padding:12px 20px;border-radius:9px;border:1px solid rgba(255,255,255,.12);background:rgba(255,255,255,.05);color:rgba(255,255,255,.78);font-size:14px;font-weight:600;cursor:pointer}

@media(max-width:480px){
    .amount-presets{grid-template-columns:repeat(4,1fr)}
    .preset-btn{font-size:12px;padding:9px 2px}
}
</style>
</head>
<body>

<div class="topbar">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    @if($collection->isOpen())
    <a href="{{ waShareUrl($collection) }}" target="_blank" class="share-top-btn">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
        Share on WhatsApp
    </a>
    @endif
</div>

@if($collection->photo_path)
<img src="{{ asset(Storage::url($collection->photo_path)) }}"
     alt="{{ $collection->title }}"
     class="collection-photo">
@endif

@if($collection->is_frozen)
<div class="frozen-banner">
    🚫 This collection has been suspended pending review. Contributions are not currently accepted.
</div>
@elseif(! $collection->isOpen())
<div class="status-banner {{ $collection->isPaid() ? 'paid' : 'closed' }}">
    @if($collection->isPaid())
        ✅ Payout complete — KES {{ number_format($collection->total_raised) }} sent to {{ $collection->recipient_name }}.
    @else
        🔒 This collection is now closed. No new contributions are accepted.
    @endif
</div>
@endif

<div class="hero">
    <div class="occasion-badge">{{ $collection->occasionEmoji() }} {{ $collection->occasionLabel() }}</div>
    <h1 class="collection-title">{{ $collection->title }}</h1>
    <div class="meta-line">
        <span>Organised by <strong style="color:rgba(255,255,255,.75)">{{ $collection->organiser_name }}</strong></span>
        <span class="meta-sep">·</span>
        <span>For <strong style="color:rgba(255,255,255,.75)">{{ $collection->recipient_name }}</strong></span>
        @if($collection->deadline)
        <span class="meta-sep">·</span>
        <span>Closes {{ $collection->deadline->format('j M Y') }}</span>
        @endif
    </div>
    @if($collection->description)
    <p class="collection-description">{{ $collection->description }}</p>
    @endif
</div>

@if($collection->organiser_phone)
@php
    $e164 = preg_replace('/^0/', '+254', preg_replace('/\s+/', '', $collection->organiser_phone));
    $waMsg = urlencode('Hi, I saw the collection for ' . $collection->title . ' on Pregota and wanted to follow up.');
@endphp
<div class="contact-bar">
    <div class="contact-card">
        <div class="contact-info">
            <span class="contact-label">Organiser</span>
            <span class="contact-name">{{ $collection->organiser_name }}</span>
        </div>
        <div class="contact-actions">
            <a href="tel:{{ $e164 }}" class="contact-call-btn">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81a19.79 19.79 0 01-3.07-8.68A2 2 0 012 .18h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.09-1.09a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
                Call
            </a>
            <a href="https://wa.me/{{ ltrim($e164, '+') }}?text={{ $waMsg }}" target="_blank" class="contact-wa-btn">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                WhatsApp
            </a>
        </div>
    </div>
</div>
@endif

@if($collection->total_raised > 0 || $collection->target_amount)
<div class="progress-card">
    <div class="progress-amounts">
        <div class="raised-amount">KES {{ number_format($collection->total_raised) }} <span>raised</span></div>
        @if($collection->target_amount)
        <div class="target-label">of KES {{ number_format($collection->target_amount) }} goal</div>
        @endif
    </div>
    @if($collection->target_amount)
    <div class="progress-bar-wrap">
        <div class="progress-bar-fill" style="width:{{ $collection->progressPct() }}%"></div>
    </div>
    @endif
    <div class="progress-meta">
        <div><strong>{{ number_format($collection->contributor_count) }}</strong> {{ Str::plural('contributor', $collection->contributor_count) }}</div>
        @if($collection->target_amount)
        <div><strong>{{ $collection->progressPct() }}%</strong> of goal</div>
        @endif
        @if($collection->isDeadlinePassed() && $collection->isOpen())
        <div style="color:#f87171">⏰ Deadline passed</div>
        @endif
    </div>
</div>
@endif

<div style="max-width:600px;margin:0 auto;padding:0 20px 4px">
    <div class="trust-notice">
        <span>🔒</span>
        <span>Always verify this collection is genuine before contributing. If something seems off,
            <button class="report-link" onclick="openReport()">report a concern</button>.
        </span>
    </div>
</div>

<div class="main-grid">

    @if($collection->isOpen() && ! $collection->is_frozen)
    <!-- Contribution form -->
    <div class="card" id="formCard">
        <div class="card-title">Contribute Now</div>

        <div id="formArea">
            @if($collection->per_person_amount)
            <div style="background:rgba(0,166,81,.1);border:1px solid rgba(0,166,81,.25);border-radius:10px;padding:12px 14px;margin-bottom:14px;font-size:13px;color:rgba(255,255,255,.7)">
                🔒 Fixed contribution: <strong style="color:#25D366">KES {{ number_format($collection->per_person_amount) }}</strong> per person
            </div>
            <div class="form-group">
                <label>Amount (KES)</label>
                <input type="number" id="amount" value="{{ $collection->per_person_amount }}" readonly
                       style="opacity:.6;cursor:not-allowed">
            </div>
            @else
            @if($collection->preset_amounts && count($collection->preset_amounts))
            <div class="amount-presets">
                @foreach($collection->preset_amounts as $preset)
                <div class="preset-btn" onclick="setPreset({{ $preset }}, this)">{{ number_format($preset) }}</div>
                @endforeach
            </div>
            @endif
            <div class="form-group">
                <label>Amount (KES)</label>
                <input type="number" id="amount" placeholder="e.g. 500" min="100" step="100"
                       oninput="updateFee(); clearPresets()">
            </div>
            @endif

            <div class="fee-line" id="feeLine" style="display:none">
                <span>Your contribution: <strong id="feeNet">KES —</strong></span>
                <span>Fee: <strong id="feeSvc">KES 30</strong> → You pay: <strong id="feeGross">KES —</strong></span>
            </div>

            <div class="form-group">
                <label>Your Name <span style="font-weight:400;text-transform:none;letter-spacing:0">(optional)</span></label>
                <input type="text" id="contribName" placeholder="Shown on the contributor wall" maxlength="60">
            </div>

            <div class="form-group">
                <label>Your M-Pesa Number</label>
                <input type="tel" id="contribPhone" placeholder="07XX XXX XXX">
                <div class="hint">You will receive an STK Push on this number.</div>
            </div>

            <button class="submit-btn" id="payBtn" onclick="sendContribution()" disabled>Contribute via M-Pesa</button>
        </div>

        <div class="status-overlay" id="statusOverlay">
            <div class="spin" id="spinIcon"></div>
            <div class="status-icon" id="statusIcon" style="display:none"></div>
            <div class="status-msg" id="statusMsg">Sending STK Push…</div>
            <div class="status-sub" id="statusSub">Check your phone and enter your M-Pesa PIN.</div>
            <button class="btn-sm" id="retryBtn" style="display:none" onclick="resetForm()">Try Again</button>
        </div>
    </div>
    @endif

    <!-- Share card -->
    @if($collection->isOpen() && ! $collection->is_frozen)
    <div class="share-card">
        <p>Know someone who would like to contribute? Share this collection.</p>
        <a href="{{ waShareUrl($collection) }}" target="_blank" class="wa-btn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            Share on WhatsApp
        </a>
        <button class="copy-link-btn" id="copyBtn" onclick="copyLink()">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
            Copy Link
        </button>
    </div>
    @endif

    <!-- Report concern -->
    <div style="text-align:center">
        <button onclick="openReport()" style="background:none;border:none;color:rgba(239,68,68,.45);font-size:12px;cursor:pointer;padding:4px 0">
            ⚠️ Report a concern about this collection
        </button>
    </div>

    <!-- Contributor wall -->
    <div class="card">
        <div class="card-title">Contributors ({{ $collection->contributor_count }})</div>
        @if($contributions->count())
        <div class="contrib-list">
            @foreach($contributions as $c)
            <div class="contrib-item">
                <div class="contrib-avatar">{{ strtoupper(substr($c->displayName(), 0, 1)) }}</div>
                <div class="contrib-info">
                    <div class="contrib-name">{{ $c->displayName() }}</div>
                    <div class="contrib-time">{{ $c->created_at->diffForHumans() }}</div>
                </div>
                <div class="contrib-amount">KES {{ number_format($c->amount) }}</div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-wall">Be the first to contribute 🙌</div>
        @endif
    </div>

</div>

<!-- Report Fraud Modal -->
<div class="modal-backdrop" id="reportModal">
    <div class="modal">
        <h3>⚠️ Report a Concern</h3>
        <p>If you believe this collection is fraudulent or being misused, let us know. We will investigate and may suspend it while reviewing.</p>
        <textarea id="reportReason" placeholder="Describe your concern (e.g. unknown organiser, suspicious use of funds, not a genuine collection)…" maxlength="300"></textarea>
        <div id="reportMsg" style="display:none;margin-top:10px;font-size:13px;font-weight:600;color:#4ade80"></div>
        <div class="modal-actions">
            <button class="modal-cancel-btn" onclick="closeReport()">Cancel</button>
            <button class="report-submit-btn" id="reportSubmitBtn" onclick="submitReport()">Submit Report</button>
        </div>
    </div>
</div>

<script>
const SLUG       = '{{ $collection->slug }}';
const CSRF       = document.querySelector('meta[name=csrf-token]').content;
let pollTimer    = null;
let contribId    = null;

const amountEl   = document.getElementById('amount');
const phoneEl    = document.getElementById('contribPhone');
const payBtn     = document.getElementById('payBtn');

function updateFee() {
    const amt = parseInt(amountEl?.value);
    if (!amt || amt < 100) {
        document.getElementById('feeLine').style.display = 'none';
        payBtn.disabled = true;
        return;
    }
    const fee = Math.max(30, Math.ceil(amt * 0.01));
    document.getElementById('feeNet').textContent   = 'KES ' + amt.toLocaleString();
    document.getElementById('feeSvc').textContent   = 'KES ' + fee.toLocaleString();
    document.getElementById('feeGross').textContent = 'KES ' + (amt + fee).toLocaleString();
    document.getElementById('feeLine').style.display = 'flex';
    validateForm();
}

function validateForm() {
    const amt   = parseInt(amountEl?.value);
    const phone = phoneEl?.value.trim();
    const validPhone = /^(\+?254|0)[17]\d{8}$/.test(phone);
    payBtn.disabled = !(amt >= 100 && validPhone);
}

function setPreset(val, el) {
    amountEl.value = val;
    document.querySelectorAll('.preset-btn').forEach(b => b.classList.remove('active'));
    el.classList.add('active');
    updateFee();
}

function clearPresets() {
    document.querySelectorAll('.preset-btn').forEach(b => b.classList.remove('active'));
}

phoneEl?.addEventListener('input', validateForm);

// If amount is pre-filled (per_person_amount), show fee immediately
if (amountEl && amountEl.readOnly) updateFee();

async function sendContribution() {
    const amt   = parseInt(amountEl.value);
    const phone = phoneEl.value.trim();
    const name  = document.getElementById('contribName').value.trim();

    showOverlay('pending', 'Sending STK Push…', 'Check your phone and enter your M-Pesa PIN.');
    payBtn.disabled = true;

    try {
        const res  = await fetch(`/collections/${SLUG}/contribute`, {
            method:  'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body:    JSON.stringify({ amount: amt, phone, name: name || null }),
        });
        const data = await res.json();

        if (!data.success) {
            showOverlay('error', 'Error', data.message || 'Something went wrong.');
            return;
        }

        contribId = data.contribution_id;
        pollStatus();
    } catch (e) {
        showOverlay('error', 'Network Error', 'Please check your connection and try again.');
    }
}

function pollStatus() {
    pollTimer = setTimeout(async () => {
        try {
            const res  = await fetch(`/collections/status?contribution_id=${contribId}`);
            const data = await res.json();

            if (data.status === 'paid') {
                showOverlay('success', '✅ Contribution received!',
                    'Thank you! KES ' + data.total_raised?.toLocaleString() + ' raised so far.');
                setTimeout(() => location.reload(), 3000);
                return;
            }
            if (data.status === 'failed') {
                showOverlay('error', '❌ Payment not completed', 'The STK Push was not confirmed. Please try again.');
                return;
            }
            pollStatus();
        } catch (e) {
            pollStatus();
        }
    }, 2500);
}

function showOverlay(state, msg, sub) {
    document.getElementById('formArea').style.display     = 'none';
    document.getElementById('statusOverlay').style.display = 'block';
    document.getElementById('spinIcon').style.display     = state === 'pending' ? 'block' : 'none';
    document.getElementById('statusIcon').style.display   = state !== 'pending' ? 'block' : 'none';
    document.getElementById('statusIcon').textContent     = state === 'success' ? '✅' : '❌';
    document.getElementById('statusMsg').textContent      = msg;
    document.getElementById('statusSub').textContent      = sub;
    document.getElementById('retryBtn').style.display     = state === 'error' ? 'inline-block' : 'none';
}

function resetForm() {
    clearTimeout(pollTimer);
    document.getElementById('formArea').style.display     = 'block';
    document.getElementById('statusOverlay').style.display = 'none';
    payBtn.disabled = false;
}

function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        const btn = document.getElementById('copyBtn');
        btn.textContent = '✓ Copied!';
        btn.classList.add('copied');
        setTimeout(() => {
            btn.innerHTML = `<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg> Copy Link`;
            btn.classList.remove('copied');
        }, 2000);
    });
}

function openReport()  { document.getElementById('reportModal').classList.add('open'); }
function closeReport() { document.getElementById('reportModal').classList.remove('open'); }

async function submitReport() {
    const reason = document.getElementById('reportReason').value.trim();
    if (!reason) { document.getElementById('reportReason').focus(); return; }

    const btn = document.getElementById('reportSubmitBtn');
    btn.disabled = true;
    btn.textContent = 'Submitting…';

    try {
        const res  = await fetch(`/collections/${SLUG}/report`, {
            method:  'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body:    JSON.stringify({ reason }),
        });
        const data = await res.json();
        if (data.frozen) {
            const msg = document.getElementById('reportMsg');
            msg.textContent = '✅ Report received. This collection has been suspended pending review.';
            msg.style.display = 'block';
            document.querySelector('.modal-actions').style.display = 'none';
            setTimeout(() => location.reload(), 2500);
        }
    } catch(e) {
        btn.disabled    = false;
        btn.textContent = 'Submit Report';
    }
}
</script>
</body>
</html>
