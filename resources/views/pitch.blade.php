<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pregota Collections — Investor Deck</title>
<meta property="og:title" content="Pregota Collections — Investor Deck">
<meta property="og:description" content="Zero-reconciliation welfare contributions for Kenyan groups. KES 35–60B flows through informal welfare collections in Kenya every year.">
<style>
*{box-sizing:border-box;margin:0;padding:0}
:root{--purple:#7c3aed;--pink:#db2777;--bg:#0f0f1a;--card:rgba(255,255,255,.04);--border:rgba(255,255,255,.08);--muted:rgba(255,255,255,.4);--subtle:rgba(255,255,255,.07)}
body{font-family:'Segoe UI',system-ui,sans-serif;background:var(--bg);color:#fff;line-height:1.6}
a{color:#c084fc;text-decoration:none}

/* ── Layout ── */
.slide{min-height:100vh;display:flex;flex-direction:column;justify-content:center;padding:60px clamp(20px,6vw,120px);border-bottom:1px solid var(--border);position:relative;overflow:hidden}
.slide-num{position:absolute;top:28px;right:32px;font-size:11px;font-weight:700;color:rgba(255,255,255,.18);letter-spacing:.12em}
.slide-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.14em;color:var(--purple);margin-bottom:16px}
h1{font-size:clamp(36px,6vw,72px);font-weight:900;line-height:1.08;letter-spacing:-.5px}
h2{font-size:clamp(24px,4vw,44px);font-weight:900;line-height:1.12;letter-spacing:-.3px;margin-bottom:16px}
h3{font-size:18px;font-weight:800;margin-bottom:8px}
p{font-size:16px;color:rgba(255,255,255,.6);max-width:680px;line-height:1.75}
em{font-style:normal;background:linear-gradient(135deg,#c084fc,#f472b6);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
strong{color:rgba(255,255,255,.9);font-weight:700}

/* ── Blobs ── */
.blob{position:absolute;border-radius:50%;filter:blur(120px);pointer-events:none;z-index:0}
.blob-purple{background:#7c3aed}
.blob-pink{background:#db2777}
.slide>*:not(.blob){position:relative;z-index:1}

/* ── Cover ── */
#cover{background:linear-gradient(150deg,#080010,#150730 50%,#200840)}
#cover h1{margin-bottom:20px}
#cover .tagline{font-size:clamp(16px,2.2vw,22px);color:rgba(255,255,255,.5);max-width:600px;margin-bottom:40px;font-weight:400}
.cover-badge{display:inline-flex;align-items:center;gap:8px;padding:8px 18px;border-radius:24px;background:rgba(124,58,237,.15);border:1px solid rgba(124,58,237,.3);font-size:13px;color:#c084fc;font-weight:700;margin-bottom:32px;letter-spacing:.04em}
.logo-mark{font-size:clamp(18px,2.5vw,26px);font-weight:900;background:linear-gradient(135deg,#c084fc,#f472b6);-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-bottom:40px;display:block}

/* ── Quote / Story ── */
.story-quote{font-size:clamp(18px,2.5vw,28px);font-weight:700;color:#fff;line-height:1.45;max-width:760px;margin-bottom:32px;border-left:3px solid var(--purple);padding-left:24px}
.story-body{font-size:16px;color:rgba(255,255,255,.55);max-width:680px;line-height:1.8}

/* ── Problem layers ── */
.problem-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:16px;margin-top:32px;max-width:900px}
.problem-card{background:rgba(239,68,68,.06);border:1px solid rgba(239,68,68,.18);border-radius:14px;padding:22px}
.problem-card h3{color:#fca5a5;font-size:15px;margin-bottom:8px}
.problem-card p{font-size:13px;color:rgba(255,255,255,.45);line-height:1.65;max-width:none}
.big-stat{font-size:clamp(40px,7vw,90px);font-weight:900;line-height:1;margin:32px 0 8px;background:linear-gradient(135deg,#c084fc,#f472b6);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.big-stat-label{font-size:16px;color:rgba(255,255,255,.45)}

/* ── Steps ── */
.steps{display:flex;flex-direction:column;gap:0;max-width:680px;margin-top:28px}
.step{display:flex;gap:20px;padding:18px 0;border-bottom:1px solid var(--border)}
.step:last-child{border-bottom:none}
.step-n{width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--purple),var(--pink));display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:900;flex-shrink:0;margin-top:2px}
.step-body h3{font-size:15px;font-weight:700;margin-bottom:3px;color:#fff}
.step-body p{font-size:13px;color:rgba(255,255,255,.45);max-width:none;line-height:1.55}
.step-final h3{color:#4ade80}

/* ── Stats row ── */
.stats-row{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:14px;margin-top:32px;max-width:800px}
.stat-box{background:var(--card);border:1px solid var(--border);border-radius:14px;padding:20px}
.stat-box .num{font-size:32px;font-weight:900;background:linear-gradient(135deg,#c084fc,#f472b6);-webkit-background-clip:text;-webkit-text-fill-color:transparent;line-height:1}
.stat-box .lbl{font-size:12px;color:var(--muted);margin-top:6px}

/* ── Tables ── */
.deck-table{width:100%;max-width:800px;border-collapse:collapse;margin-top:24px;font-size:14px}
.deck-table th{padding:10px 14px;text-align:left;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--muted);border-bottom:1px solid var(--border)}
.deck-table td{padding:12px 14px;border-bottom:1px solid rgba(255,255,255,.04);color:rgba(255,255,255,.7);vertical-align:top}
.deck-table tr:last-child td{border-bottom:none}
.deck-table .hi{color:#c084fc;font-weight:700}
.deck-table .check{color:#4ade80;font-weight:700}
.deck-table .cross{color:rgba(255,255,255,.2)}

/* ── Modules grid ── */
.modules{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:10px;margin-top:28px;max-width:900px}
.module-card{background:var(--card);border:1px solid var(--border);border-radius:12px;padding:16px}
.module-card .icon{font-size:22px;margin-bottom:8px}
.module-card h3{font-size:14px;font-weight:700;margin-bottom:4px}
.module-card p{font-size:12px;color:var(--muted);max-width:none;line-height:1.55}
.module-card .fee{font-size:12px;color:#c084fc;font-weight:700;margin-top:8px}

/* ── Roadmap ── */
.roadmap{display:flex;flex-direction:column;gap:16px;max-width:720px;margin-top:28px}
.phase{background:var(--card);border:1px solid var(--border);border-radius:12px;padding:18px}
.phase-tag{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--purple);margin-bottom:6px}
.phase h3{font-size:15px;font-weight:700;margin-bottom:8px}
.phase ul{padding-left:16px;display:flex;flex-direction:column;gap:4px}
.phase ul li{font-size:13px;color:rgba(255,255,255,.5)}
.phase.active{border-color:rgba(124,58,237,.35);background:rgba(124,58,237,.07)}

/* ── Ask ── */
.fund-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px;max-width:800px;margin-top:28px}
@media(max-width:600px){.fund-grid{grid-template-columns:1fr}}
.fund-card{background:var(--card);border:1px solid var(--border);border-radius:12px;padding:18px}
.fund-card h3{font-size:13px;font-weight:700;margin-bottom:10px;color:rgba(255,255,255,.6);text-transform:uppercase;letter-spacing:.06em}
.fund-item{display:flex;justify-content:space-between;align-items:center;padding:7px 0;border-bottom:1px solid rgba(255,255,255,.04);font-size:13px}
.fund-item:last-child{border-bottom:none}
.fund-item .pct{color:#c084fc;font-weight:700}

/* ── Closing ── */
#closing{background:linear-gradient(150deg,#080010,#150730 50%,#200840);text-align:center;align-items:center}
#closing h2{text-align:center}
#closing p{text-align:center;margin:0 auto}
.closing-stat{font-size:clamp(48px,9vw,100px);font-weight:900;line-height:1;background:linear-gradient(135deg,#c084fc,#f472b6);-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin:28px 0 8px}
.closing-sub{font-size:18px;color:rgba(255,255,255,.4);margin-bottom:40px}
.contact{font-size:15px;color:rgba(255,255,255,.4);margin-top:40px}
.contact a{color:#c084fc}

/* ── Nav dots ── */
.nav-dots{position:fixed;right:20px;top:50%;transform:translateY(-50%);display:flex;flex-direction:column;gap:8px;z-index:100}
.dot{width:8px;height:8px;border-radius:50%;background:rgba(255,255,255,.15);cursor:pointer;transition:.2s}
.dot.active{background:#c084fc;transform:scale(1.3)}

/* ── Progress bar ── */
.progress{position:fixed;top:0;left:0;height:3px;background:linear-gradient(90deg,#7c3aed,#db2777);z-index:200;transition:.1s}

@media(max-width:600px){
    .slide{padding:48px 20px}
    .nav-dots{display:none}
    .fund-grid{grid-template-columns:1fr}
}
</style>
</head>
<body>

<div class="progress" id="progress"></div>

<div class="nav-dots" id="navDots"></div>

<!-- ── 01 COVER ── -->
<section class="slide" id="cover" data-label="Cover">
    <div class="blob blob-purple" style="width:600px;height:600px;opacity:.2;top:-200px;left:-100px"></div>
    <div class="blob blob-pink" style="width:400px;height:400px;opacity:.12;bottom:-100px;right:-50px"></div>
    <div class="slide-num">01 / 14</div>
    <span class="logo-mark">Pregota</span>
    <div class="cover-badge">🇰🇪 Built for Kenya · Seed Round 2026</div>
    <h1>Zero reconciliation.<br><em>Direct to recipient.</em></h1>
    <p class="tagline">Kamau shares a link. Everyone pays via M-Pesa. Grace gets the money. Kamau never touches a shilling.</p>
</section>

<!-- ── 02 THE MOMENT ── -->
<section class="slide" id="moment" data-label="The Problem">
    <div class="slide-num">02 / 14</div>
    <div class="slide-label">The Moment Everyone Knows</div>
    <div class="story-quote">"Ati unatuma wapi?"</div>
    <div class="story-body">
        It is Monday morning. A colleague's parent passed away over the weekend. The WhatsApp message goes out. 47 people say <em style="-webkit-text-fill-color:rgba(255,255,255,.5);background:none">"pole sana."</em>
        Someone volunteers their personal M-Pesa number as the collection point.<br><br>
        By Wednesday: 23 people have sent. 8 sent twice by mistake. 4 sent the wrong amount.
        The volunteer has <strong>KES 34,200 on their personal M-Pesa</strong> that belongs to someone else, and cannot sleep.<br><br>
        <strong>This happens in every office, every church, every chama, every school group in Kenya. Every week.</strong>
    </div>
</section>

<!-- ── 03 THE PROBLEM ── -->
<section class="slide" id="problem" data-label="Problem Depth">
    <div class="slide-num">03 / 14</div>
    <div class="slide-label">Three Layers of Pain</div>
    <h2>The coordinator pays the price.<br><em>Every time.</em></h2>
    <div class="problem-grid">
        <div class="problem-card">
            <h3>⏱️ The Burden</h3>
            <p>4–8 hours per collection. Manual reconciliation of every M-Pesa message. Chasing non-payers without embarrassing anyone. Holding money that isn't theirs. Answering "nimesend, umeona?" at midnight.</p>
        </div>
        <div class="problem-card">
            <h3>🤝 The Trust Collapse</h3>
            <p>When money passes through a person, disputes happen. Accusations happen. "Sijawahi kupokea ya Kamau." Friendships end. Groups dissolve. The institution of mutual aid — built over decades — is undermined by a coordination problem.</p>
        </div>
        <div class="problem-card">
            <h3>📊 The Scale</h3>
            <p>Kenya: 300,000+ registered chamas. ~1,000 deaths/day. ~800 weddings/day. 230,000–500,000 active welfare WhatsApp groups. <strong style="color:#fca5a5">KES 35–60 billion</strong> flows through informal welfare collections annually.</p>
        </div>
    </div>
</section>

<!-- ── 04 SOLUTION ── -->
<section class="slide" id="solution" data-label="Solution">
    <div class="slide-num">04 / 14</div>
    <div class="slide-label">The Solution</div>
    <h2>Pregota Collections</h2>
    <p>One link. Everyone pays directly to the recipient. The organiser never holds a shilling.</p>
    <div class="steps">
        <div class="step"><div class="step-n">1</div><div class="step-body"><h3>Kamau opens pregota.com/collections/new</h3><p>Enters occasion, recipient name, recipient M-Pesa (encrypted, never shown to anyone). Takes 90 seconds.</p></div></div>
        <div class="step"><div class="step-n">2</div><div class="step-body"><h3>Gets a shareable link</h3><p>pregota.com/c/grace-wanjiku-welfare-abcd — pastes it in the WhatsApp group.</p></div></div>
        <div class="step"><div class="step-n">3</div><div class="step-body"><h3>Contributors open the link</h3><p>Choose amount. Enter their own M-Pesa. Get STK Push. No account needed. No app download.</p></div></div>
        <div class="step"><div class="step-n">4</div><div class="step-body"><h3>Live contributor wall updates</h3><p>Everyone sees who has paid and the running total. No disputes. No "nimesend?"</p></div></div>
        <div class="step step-final"><div class="step-n">5</div><div class="step-body"><h3>Kamau clicks "Pay Out" — done.</h3><p>KES goes directly to Grace's M-Pesa via B2C. Kamau's job is over. He never held a shilling.</p></div></div>
    </div>
</section>

<!-- ── 05 MARKET ── -->
<section class="slide" id="market" data-label="Market Size">
    <div class="slide-num">05 / 14</div>
    <div class="slide-label">Market Size</div>
    <h2>A market hiding in<br><em>plain sight.</em></h2>
    <div class="stats-row">
        <div class="stat-box"><div class="num">500K</div><div class="lbl">Active welfare WhatsApp groups in Kenya</div></div>
        <div class="stat-box"><div class="num">7,000</div><div class="lbl">Collection occasions triggered per day nationally</div></div>
        <div class="stat-box"><div class="num">KES 60B</div><div class="lbl">Annual informal welfare contributions</div></div>
        <div class="stat-box"><div class="num">KES 83M</div><div class="lbl">Pregota annual revenue at 10% market penetration</div></div>
    </div>
    <p style="margin-top:28px;font-size:14px;color:rgba(255,255,255,.35)">Bottom-up: 230,000 groups × 8 occasions/yr × 15 contributors × KES 30 fee = KES 83M at 10% penetration</p>
</section>

<!-- ── 06 UNIT ECONOMICS ── -->
<section class="slide" id="economics" data-label="Unit Economics">
    <div class="slide-num">06 / 14</div>
    <div class="slide-label">Business Model & Unit Economics</div>
    <h2>KES 30 flat fee.<br><em>93% gross margin.</em></h2>
    <table class="deck-table" style="max-width:520px">
        <tr><th>Line item</th><th>Per contribution</th></tr>
        <tr><td>Revenue (fee)</td><td class="hi">KES 30</td></tr>
        <tr><td>Daraja STK Push cost</td><td>~KES 1.00</td></tr>
        <tr><td>Daraja B2C payout (amortised)</td><td>~KES 0.70</td></tr>
        <tr><td>Infrastructure (amortised)</td><td>~KES 0.50</td></tr>
        <tr><td><strong>Gross margin</strong></td><td class="hi"><strong>KES 27.80 (93%)</strong></td></tr>
    </table>
    <p style="margin-top:24px;font-size:14px">Why flat fee beats percentage: <strong>Mchanga charges ~4.5%</strong> — on a KES 50,000 collection that's KES 2,250 from the recipient's money. Pregota charges the <strong>contributor</strong> a flat KES 30. <strong>The recipient gets 100% of what was pledged.</strong></p>
</section>

<!-- ── 07 TRACTION ── -->
<section class="slide" id="traction" data-label="Traction">
    <div class="slide-num">07 / 14</div>
    <div class="slide-label">Traction & Validation</div>
    <h2>Built. Integrated. <em>Real transactions.</em></h2>
    <div class="stats-row" style="max-width:700px">
        <div class="stat-box"><div class="num">23</div><div class="lbl">Welfare coordinators interviewed — 100% described the reconciliation problem unprompted</div></div>
        <div class="stat-box"><div class="num">Live</div><div class="lbl">M-Pesa Daraja integration — STK Push, callbacks, B2C payout all running in production</div></div>
        <div class="stat-box"><div class="num">6</div><div class="lbl">Revenue modules built on one codebase: Tips, Gifts, Direct Gift, Creator Tips, Collections, Subscriptions</div></div>
    </div>
    <p style="margin-top:24px">The tip module is already handling real M-Pesa transactions — proving the payment rail works. Collections uses the identical infrastructure.</p>
</section>

<!-- ── 08 GROWTH ── -->
<section class="slide" id="growth" data-label="Growth Mechanic">
    <div class="slide-num">08 / 14</div>
    <div class="blob blob-purple" style="width:500px;height:500px;opacity:.15;top:-100px;right:-100px"></div>
    <div class="slide-label">Distribution</div>
    <h2>The link is the <em>marketing.</em></h2>
    <p>Every completed collection is a live demo seen by every contributor.</p>
    <div class="steps" style="margin-top:28px">
        <div class="step"><div class="step-n">→</div><div class="step-body"><h3>Kamau shares a link in a WhatsApp group of 40</h3><p>30 people open it to contribute.</p></div></div>
        <div class="step"><div class="step-n">→</div><div class="step-body"><h3>All 30 experience the product</h3><p>They see the interface, the live wall, the real-time total. No pitch needed.</p></div></div>
        <div class="step"><div class="step-n">→</div><div class="step-body"><h3>Next time their group needs to collect</h3><p>Someone in those 30 remembers. <strong>One collection = 30 people acquired.</strong></p></div></div>
        <div class="step"><div class="step-n">→</div><div class="step-body"><h3>Groups overlap</h3><p>Kamau is in his office welfare group, church choir, and old-school class group. One win propagates across all three. The network compounds.</p></div></div>
    </div>
    <p style="margin-top:20px;font-size:13px;color:rgba(255,255,255,.35)">Same mechanic as GoFundMe (share link = distribution) and M-Pesa (transaction notification = acquisition prompt). Zero paid marketing required for initial spread.</p>
</section>

<!-- ── 09 COMPETITION ── -->
<section class="slide" id="competition" data-label="Competition">
    <div class="slide-num">09 / 14</div>
    <div class="slide-label">Competitive Landscape</div>
    <h2>Nobody solves<br><em>direct-to-recipient.</em></h2>
    <div style="overflow-x:auto">
    <table class="deck-table">
        <tr>
            <th>Feature</th>
            <th style="color:#c084fc">Pregota</th>
            <th>Mchanga</th>
            <th>Paybill</th>
            <th>Personal M-Pesa</th>
        </tr>
        <tr><td>Money goes direct to recipient</td><td class="check">✓</td><td class="cross">✗</td><td class="cross">✗</td><td class="cross">✗</td></tr>
        <tr><td>Zero coordinator reconciliation</td><td class="check">✓</td><td class="cross">✗</td><td class="cross">✗</td><td class="cross">✗</td></tr>
        <tr><td>No contributor account needed</td><td class="check">✓</td><td class="check">✓</td><td class="check">✓</td><td class="check">✓</td></tr>
        <tr><td>Live contributor wall</td><td class="check">✓</td><td class="check">✓</td><td class="cross">✗</td><td class="cross">✗</td></tr>
        <tr><td>STK Push (no app needed)</td><td class="check">✓</td><td class="cross">✗</td><td class="check">✓</td><td class="check">✓</td></tr>
        <tr><td>Recipient phone stays private</td><td class="check">✓</td><td class="cross">✗</td><td class="cross">✗</td><td class="cross">✗</td></tr>
        <tr><td>Flat predictable fee</td><td class="check">✓</td><td class="cross">✗ (4.5%)</td><td class="cross">✗</td><td class="cross">✗</td></tr>
    </table>
    </div>
</section>

<!-- ── 10 PLATFORM ── -->
<section class="slide" id="platform" data-label="Platform">
    <div class="slide-num">10 / 14</div>
    <div class="slide-label">The Broader Platform</div>
    <h2>Collections is one module.<br><em>Six revenue streams. One brand.</em></h2>
    <div class="modules">
        <div class="module-card"><div class="icon">🎁</div><h3>Gift Vouchers</h3><p>Send KES as a redeemable code. Recipient claims to any M-Pesa.</p><div class="fee">KES 75 / gift</div></div>
        <div class="module-card"><div class="icon">⚡</div><h3>Direct Gift</h3><p>Send anonymously to any M-Pesa. Recipient number encrypted, deleted after payout.</p><div class="fee">KES 75 / gift</div></div>
        <div class="module-card"><div class="icon">💸</div><h3>Staff Tips</h3><p>Tip service workers without knowing their number. Privacy by design.</p><div class="fee">KES 15 / tip</div></div>
        <div class="module-card"><div class="icon">🎨</div><h3>Creator Tips</h3><p>Fan tips for content creators. Kenyan Ko-fi.</p><div class="fee">KES 25 / tip</div></div>
        <div class="module-card"><div class="icon">🤝</div><h3>Collections</h3><p>Welfare group contributions. Direct to recipient. Zero reconciliation.</p><div class="fee">KES 30 / contribution</div></div>
        <div class="module-card"><div class="icon">📊</div><h3>Business SaaS</h3><p>Analytics + fee waiver for subscribed businesses.</p><div class="fee">KES 1,500–7,000 / mo</div></div>
    </div>
    <p style="margin-top:20px;font-size:14px">One Daraja integration. One codebase. The long-term play: <strong>Pregota becomes the financial privacy layer for informal Kenyan transactions.</strong></p>
</section>

<!-- ── 11 ROADMAP ── -->
<section class="slide" id="roadmap" data-label="Roadmap">
    <div class="slide-num">11 / 14</div>
    <div class="slide-label">Roadmap</div>
    <h2>Phase by phase.<br><em>Each one compounds.</em></h2>
    <div class="roadmap">
        <div class="phase active">
            <div class="phase-tag">Phase 1 — Now · Live</div>
            <h3>Quick Collections + Full tip/gift platform</h3>
            <ul>
                <li>Ad-hoc collections: any occasion, any group, instant link</li>
                <li>STK Push + B2C payout pipeline live on Daraja</li>
                <li>Staff Tips, Gift Vouchers, Direct Gift, Creator Tips running</li>
            </ul>
        </div>
        <div class="phase">
            <div class="phase-tag">Phase 2 — Q3 2026</div>
            <h3>Welfare Groups (recurring)</h3>
            <ul>
                <li>Permanent groups with fixed membership roster</li>
                <li>Monthly recurring contribution schedules</li>
                <li>Running balance ledger, disbursement requests, approval workflow</li>
                <li>Eliminates chama Excel sheets entirely</li>
            </ul>
        </div>
        <div class="phase">
            <div class="phase-tag">Phase 3 — Q4 2026</div>
            <h3>Scale & Ecosystem</h3>
            <ul>
                <li>USSD fallback (*483#) for feature phones</li>
                <li>API for chama management apps (plug in Pregota's payment rail)</li>
                <li>Employer-sponsored welfare groups as staff benefit</li>
                <li>Tanzania and Uganda expansion</li>
            </ul>
        </div>
    </div>
</section>

<!-- ── 12 THE ASK ── -->
<section class="slide" id="ask" data-label="The Ask">
    <div class="slide-num">12 / 14</div>
    <div class="slide-label">The Ask</div>
    <h2>Raising <em>KES 15M</em><br>seed round.</h2>
    <div class="fund-grid">
        <div class="fund-card">
            <h3>Use of Funds</h3>
            <div class="fund-item"><span>Engineering (2 senior devs, 12 mo)</span><span class="pct">45% · KES 6.75M</span></div>
            <div class="fund-item"><span>Safaricom Daraja Go-Live & compliance</span><span class="pct">15% · KES 2.25M</span></div>
            <div class="fund-item"><span>Community activation (10 pilot companies)</span><span class="pct">20% · KES 3.0M</span></div>
            <div class="fund-item"><span>Legal, compliance, data protection</span><span class="pct">10% · KES 1.5M</span></div>
            <div class="fund-item"><span>Infrastructure & operations</span><span class="pct">10% · KES 1.5M</span></div>
        </div>
        <div class="fund-card">
            <h3>18-Month Targets</h3>
            <div class="fund-item"><span>Active welfare groups</span><span class="pct">5,000</span></div>
            <div class="fund-item"><span>Daily transactions</span><span class="pct">8,000–12,000</span></div>
            <div class="fund-item"><span>Monthly revenue</span><span class="pct">KES 7–10M</span></div>
            <div class="fund-item"><span>Total KES to recipients</span><span class="pct">KES 500M+</span></div>
        </div>
    </div>
</section>

<!-- ── 13 CLOSING ── -->
<section class="slide" id="closing" data-label="Closing">
    <div class="blob blob-purple" style="width:700px;height:700px;opacity:.18;top:-200px;left:-200px"></div>
    <div class="blob blob-pink" style="width:500px;height:500px;opacity:.1;bottom:-200px;right:-200px"></div>
    <div class="slide-num">13 / 14</div>
    <div class="closing-stat">7,000</div>
    <div class="closing-sub">collection occasions triggered in Kenya. Every day.</div>
    <h2 style="max-width:640px">Every one of them ends with someone reconciling M-Pesa messages at midnight.</h2>
    <p style="margin-top:16px;max-width:560px">We are not building a feature. We are ending a weekly source of stress and suspicion for millions of Kenyans. The technology is built. The integration works. The market is already doing this — manually — asking for a better way.</p>
    <p style="margin-top:20px;font-size:18px;color:#fff;font-weight:700;max-width:none"><em>Pregota is that better way.</em></p>
    <div class="contact">pregota.com &nbsp;·&nbsp; <a href="mailto:hello@pregota.com">hello@pregota.com</a></div>
</section>

<script>
const slides  = document.querySelectorAll('.slide');
const dotsEl  = document.getElementById('navDots');
const progEl  = document.getElementById('progress');

slides.forEach((s, i) => {
    const d = document.createElement('div');
    d.className = 'dot';
    d.title = s.dataset.label || '';
    d.onclick = () => s.scrollIntoView({behavior:'smooth'});
    dotsEl.appendChild(d);
});

const dots = dotsEl.querySelectorAll('.dot');

const obs = new IntersectionObserver(entries => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            const idx = [...slides].indexOf(e.target);
            dots.forEach((d,i) => d.classList.toggle('active', i === idx));
            progEl.style.width = ((idx + 1) / slides.length * 100) + '%';
        }
    });
}, {threshold: .5});

slides.forEach(s => obs.observe(s));
</script>
</body>
</html>
