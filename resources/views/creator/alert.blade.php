<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Alert Overlay</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{background:transparent;font-family:'Segoe UI',system-ui,sans-serif;overflow:hidden;width:420px}

.alert-wrap{position:fixed;bottom:24px;left:24px;width:380px;pointer-events:none}

.gift-alert{
    background:linear-gradient(135deg,rgba(124,58,237,.95),rgba(219,39,119,.9));
    border-radius:16px;padding:16px 20px;
    display:flex;align-items:center;gap:14px;
    box-shadow:0 8px 32px rgba(124,58,237,.4);
    transform:translateX(-120%);opacity:0;
    transition:transform .5s cubic-bezier(.34,1.56,.64,1), opacity .4s ease;
}
.gift-alert.show{transform:translateX(0);opacity:1}
.gift-alert.hide{transform:translateX(-120%);opacity:0;transition:transform .4s ease,opacity .3s ease}

.alert-emoji{font-size:32px;flex-shrink:0;animation:bounce .6s infinite alternate}
@keyframes bounce{from{transform:scale(1)}to{transform:scale(1.15)}}

.alert-body{}
.alert-from{font-size:13px;font-weight:700;color:rgba(255,255,255,.8);margin-bottom:2px}
.alert-amount{font-size:22px;font-weight:900;color:#fff;line-height:1}
.alert-msg{font-size:12px;color:rgba(255,255,255,.7);margin-top:4px;font-style:italic}

.pregota-badge{font-size:9px;color:rgba(255,255,255,.4);margin-top:6px;font-weight:600;letter-spacing:.1em;text-transform:uppercase}
</style>
</head>
<body>
<div class="alert-wrap">
    <div class="gift-alert" id="alert">
        <div class="alert-emoji">🎁</div>
        <div class="alert-body">
            <div class="alert-from" id="alertFrom">Someone</div>
            <div class="alert-amount" id="alertAmount">KES 0</div>
            <div class="alert-msg" id="alertMsg" style="display:none"></div>
            <div class="pregota-badge">via Pregota</div>
        </div>
    </div>
</div>

<script>
const fmt = n => 'KES ' + Number(n).toLocaleString('en-KE', {minimumFractionDigits:0});
let showing = false;
const queue = [];

function showAlert(gift) {
    document.getElementById('alertFrom').textContent  = gift.fan_name + ' sent';
    document.getElementById('alertAmount').textContent = fmt(gift.amount);
    const msgEl = document.getElementById('alertMsg');
    if (gift.message) { msgEl.textContent = '"' + gift.message + '"'; msgEl.style.display = 'block'; }
    else { msgEl.style.display = 'none'; }

    const el = document.getElementById('alert');
    el.classList.remove('hide');
    el.classList.add('show');

    setTimeout(() => {
        el.classList.add('hide');
        el.classList.remove('show');
        setTimeout(() => { showing = false; processQueue(); }, 500);
    }, 5000);
}

function processQueue() {
    if (queue.length && !showing) {
        showing = true;
        showAlert(queue.shift());
    }
}

async function poll() {
    try {
        const res  = await fetch('{{ route("creator.alert.poll", [$creator->handle, $creator->alert_token]) }}');
        const json = await res.json();
        if (json.gift) { queue.push(json.gift); processQueue(); }
    } catch(e) {}
    setTimeout(poll, 3000);
}

poll();
</script>
</body>
</html>
