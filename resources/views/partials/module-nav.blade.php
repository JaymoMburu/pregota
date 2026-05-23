@php
$_active = $activeModule ?? '';
$_mods = [
    'gift'       => ['emoji' => '🎁', 'label' => 'Gift',        'url' => route('gift.home'),           'color' => '#25D366'],
    'collection' => ['emoji' => '🤝', 'label' => 'Collections', 'url' => route('collection.new'),      'color' => '#34d399'],
    'school'     => ['emoji' => '🏫', 'label' => 'School',      'url' => route('school-collection.new'),'color' => '#60a5fa'],
    'tips'       => ['emoji' => '⭐', 'label' => 'Tips',        'url' => route('staff.landing'),       'color' => '#fbbf24'],
];
@endphp
<div style="padding:8px 16px;border-bottom:1px solid rgba(255,255,255,.06);display:flex;align-items:center;gap:8px;flex-wrap:nowrap;overflow-x:auto;scrollbar-width:none;-webkit-overflow-scrolling:touch;background:#0B141A">
    <a href="{{ route('home') }}" style="font-size:12px;color:rgba(255,255,255,.6);text-decoration:none;margin-right:6px;display:flex;align-items:center;gap:4px;white-space:nowrap;flex-shrink:0" onmouseover="this.style.color='rgba(255,255,255,.7)'" onmouseout="this.style.color='rgba(255,255,255,.6)'">
        ‹ All
    </a>
    <div style="width:1px;height:16px;background:rgba(255,255,255,.1);flex-shrink:0"></div>
    @foreach($_mods as $key => $mod)
    @if($key === $_active)
    <span style="display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:20px;font-size:12px;font-weight:700;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.15);color:#fff;white-space:nowrap">
        {{ $mod['emoji'] }} {{ $mod['label'] }}
    </span>
    @else
    <a href="{{ $mod['url'] }}" style="display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:20px;font-size:12px;font-weight:600;background:transparent;border:1px solid rgba(255,255,255,.08);color:rgba(255,255,255,.72);text-decoration:none;white-space:nowrap;transition:.15s" onmouseover="this.style.color='{{ $mod['color'] }}';this.style.borderColor='{{ $mod['color'] }}40'" onmouseout="this.style.color='rgba(255,255,255,.72)';this.style.borderColor='rgba(255,255,255,.08)'">
        {{ $mod['emoji'] }} {{ $mod['label'] }}
    </a>
    @endif
    @endforeach
</div>
