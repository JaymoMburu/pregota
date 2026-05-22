@php
$modules = [
    'gift'       => ['emoji' => '🎁', 'name' => 'Gift Vouchers',      'desc' => 'Send anonymous M-Pesa gifts',  'url' => route('home')],
    'collection' => ['emoji' => '🤝', 'name' => 'Group Collections',  'desc' => 'Funerals, chamas, harambees',  'url' => route('collection.new')],
    'school'     => ['emoji' => '🏫', 'name' => 'School Collections', 'desc' => 'Exam fees, trips, PTA levies', 'url' => route('school-collection.new')],
    'tips'       => ['emoji' => '⭐', 'name' => 'Staff Tips',         'desc' => 'Receive tips privately',       'url' => route('staff.landing')],
];
$_current = $current ?? '';
$_full    = $fullWidth ?? false;
$_others  = array_filter($modules, fn($k) => $k !== $_current, ARRAY_FILTER_USE_KEY);
@endphp

@if($_full)
{{-- ── Full-width strip (used on full-page layouts e.g. staff landing) ── --}}
<div style="background:rgba(255,255,255,.02);border-top:1px solid rgba(255,255,255,.07);padding:40px 24px">
    <div style="max-width:680px;margin:0 auto">
        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:rgba(255,255,255,.3);margin-bottom:16px;text-align:center">Also on Pregota</div>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px">
            @foreach($_others as $mod)
            <a href="{{ $mod['url'] }}" style="display:flex;flex-direction:column;align-items:center;gap:6px;padding:16px 10px;border-radius:12px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);text-decoration:none;transition:.15s" onmouseover="this.style.borderColor='rgba(124,58,237,.4)';this.style.background='rgba(124,58,237,.1)'" onmouseout="this.style.borderColor='rgba(255,255,255,.08)';this.style.background='rgba(255,255,255,.04)'">
                <span style="font-size:26px">{{ $mod['emoji'] }}</span>
                <strong style="font-size:12px;font-weight:700;color:rgba(255,255,255,.8);text-align:center">{{ $mod['name'] }}</strong>
                <span style="font-size:11px;color:rgba(255,255,255,.35);text-align:center;line-height:1.4">{{ $mod['desc'] }}</span>
            </a>
            @endforeach
        </div>
    </div>
</div>
@else
{{-- ── Compact sidebar strip (used inside the left panel of split-panel pages) ── --}}
<div style="margin-top:20px;padding-top:16px;border-top:1px solid rgba(255,255,255,.08);position:relative;z-index:1">
    <div style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:rgba(255,255,255,.25);margin-bottom:10px">Also on Pregota</div>
    <div style="display:flex;flex-direction:column;gap:6px">
        @foreach($_others as $mod)
        <a href="{{ $mod['url'] }}" style="display:flex;align-items:center;gap:10px;padding:9px 12px;border-radius:10px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);text-decoration:none;transition:.15s" onmouseover="this.style.borderColor='rgba(124,58,237,.3)';this.style.background='rgba(124,58,237,.1)'" onmouseout="this.style.borderColor='rgba(255,255,255,.07)';this.style.background='rgba(255,255,255,.04)'">
            <span style="font-size:18px;flex-shrink:0;width:24px;text-align:center">{{ $mod['emoji'] }}</span>
            <div style="flex:1;min-width:0">
                <strong style="display:block;font-size:12px;font-weight:700;color:rgba(255,255,255,.75)">{{ $mod['name'] }}</strong>
                <span style="font-size:11px;color:rgba(255,255,255,.35)">{{ $mod['desc'] }}</span>
            </div>
            <span style="color:rgba(255,255,255,.2);font-size:14px;flex-shrink:0">›</span>
        </a>
        @endforeach
    </div>
</div>
@endif
