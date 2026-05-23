<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Group Collections — Pregota</title>
<meta name="description" content="Collect contributions for any occasion via M-Pesa — no WhatsApp group needed. Bereavement, wedding, medical, chama and more.">
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh}

.nav{padding:14px 24px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.08);position:sticky;top:0;background:#0B141A;z-index:10}
.logo{font-size:20px;font-weight:900;background:linear-gradient(135deg,#34d399,#10b981);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.nav-cta{background:linear-gradient(135deg,#059669,#10b981);color:#fff;border:none;border-radius:8px;padding:8px 18px;font-size:13px;font-weight:700;cursor:pointer;text-decoration:none}

/* Hero */
.hero{padding:64px 24px 48px;text-align:center;max-width:640px;margin:0 auto}
.badge{display:inline-flex;align-items:center;gap:7px;background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.25);border-radius:20px;padding:6px 16px;font-size:12px;font-weight:700;color:#34d399;margin-bottom:24px;letter-spacing:.05em}
.hero h1{font-size:clamp(30px,6vw,50px);font-weight:900;line-height:1.1;letter-spacing:-.5px;margin-bottom:18px}
.hero h1 em{font-style:normal;background:linear-gradient(135deg,#34d399,#10b981);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.hero p{font-size:16px;color:rgba(255,255,255,.55);line-height:1.7;margin-bottom:32px;max-width:460px;margin-left:auto;margin-right:auto}
.hero-btns{display:flex;gap:12px;justify-content:center;flex-wrap:wrap}
.btn-primary{background:linear-gradient(135deg,#059669,#10b981);color:#fff;border:none;border-radius:12px;padding:14px 28px;font-size:15px;font-weight:700;cursor:pointer;text-decoration:none;display:inline-block}
.btn-secondary{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.15);color:rgba(255,255,255,.7);border-radius:12px;padding:14px 28px;font-size:15px;font-weight:700;text-decoration:none;display:inline-block}

/* Page preview */
.preview{padding:40px 24px;max-width:680px;margin:0 auto;text-align:center}
.preview-url{display:inline-flex;align-items:center;gap:10px;background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.25);border-radius:12px;padding:10px 20px;font-size:14px;font-weight:700;color:#34d399;margin-bottom:24px;font-family:monospace}
.preview-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:20px;padding:28px;text-align:left;max-width:380px;margin:0 auto;position:relative}
.preview-label{position:absolute;top:-12px;right:16px;background:#10b981;color:#fff;font-size:10px;font-weight:700;padding:3px 10px;border-radius:20px;letter-spacing:.05em}
.occ-badge{display:inline-flex;align-items:center;gap:6px;background:rgba(16,185,129,.12);border:1px solid rgba(16,185,129,.25);border-radius:20px;padding:4px 12px;font-size:11px;font-weight:700;color:#34d399;margin-bottom:12px}
.preview-title{font-size:18px;font-weight:900;margin-bottom:6px;line-height:1.25}
.preview-desc{font-size:12px;color:rgba(255,255,255,.5);line-height:1.6;margin-bottom:16px}
.preview-bar-wrap{margin-bottom:16px}
.preview-bar-label{display:flex;justify-content:space-between;font-size:11px;color:rgba(255,255,255,.4);margin-bottom:6px}
.preview-bar{height:6px;background:rgba(255,255,255,.08);border-radius:4px}
.preview-bar-fill{height:100%;width:62%;background:linear-gradient(90deg,#059669,#34d399);border-radius:4px}
.preview-stats{display:flex;gap:12px;margin-bottom:16px}
.preview-stat{font-size:11px;color:rgba(255,255,255,.4);text-align:center;flex:1;background:rgba(255,255,255,.04);border-radius:8px;padding:8px 4px}
.preview-stat strong{display:block;font-size:14px;font-weight:800;color:#fff;margin-bottom:2px}
.preview-btn{width:100%;background:linear-gradient(135deg,#059669,#10b981);border:none;border-radius:10px;padding:13px;font-size:14px;font-weight:700;color:#fff;cursor:pointer;text-align:center}

/* Section styles */
.section{padding:56px 24px;max-width:700px;margin:0 auto}
.section-tag{display:inline-block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:rgba(255,255,255,.35);margin-bottom:12px}
.section h2{font-size:clamp(22px,4vw,32px);font-weight:900;line-height:1.2;margin-bottom:14px}
.section p{font-size:15px;color:rgba(255,255,255,.5);line-height:1.7}

/* WhatsApp problems */
.problem-card{background:rgba(239,68,68,.06);border:1px solid rgba(239,68,68,.15);border-radius:16px;padding:24px;margin-top:24px}
.problem-title{font-size:13px;font-weight:700;color:#fca5a5;margin-bottom:16px;display:flex;align-items:center;gap:8px}
.problem-item{display:flex;align-items:flex-start;gap:14px;padding:11px 0;border-bottom:1px solid rgba(239,68,68,.1)}
.problem-item:last-child{border-bottom:none}
.problem-icon{font-size:20px;flex-shrink:0;margin-top:1px}
.problem-text strong{font-size:13px;color:#fca5a5;display:block;margin-bottom:3px}
.problem-text span{font-size:12px;color:rgba(255,255,255,.4);line-height:1.6}

/* How it works */
.how{background:rgba(16,185,129,.04);border-top:1px solid rgba(16,185,129,.1);border-bottom:1px solid rgba(16,185,129,.1);padding:56px 24px}
.how-inner{max-width:700px;margin:0 auto}
.steps{display:flex;flex-direction:column;gap:0;margin-top:28px}
.step{display:flex;gap:20px;padding:20px 0;border-bottom:1px solid rgba(255,255,255,.05)}
.step:last-child{border-bottom:none}
.step-num{width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#059669,#10b981);display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:900;flex-shrink:0;margin-top:2px}
.step-body h3{font-size:15px;font-weight:700;margin-bottom:4px}
.step-body p{font-size:13px;color:rgba(255,255,255,.45);line-height:1.6}

/* Contributor flow */
.contrib-section{padding:56px 24px;max-width:700px;margin:0 auto}
.contrib-flow{display:flex;flex-direction:column;gap:12px;margin-top:28px}
.contrib-step{display:flex;gap:16px;align-items:flex-start;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:14px;padding:18px}
.contrib-icon{font-size:28px;flex-shrink:0}
.contrib-body h4{font-size:14px;font-weight:700;margin-bottom:4px}
.contrib-body p{font-size:13px;color:rgba(255,255,255,.45);line-height:1.6}

/* Advantages grid */
.adv{background:rgba(16,185,129,.04);border-top:1px solid rgba(16,185,129,.1);border-bottom:1px solid rgba(16,185,129,.1);padding:56px 24px}
.adv-inner{max-width:700px;margin:0 auto}
.adv-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-top:28px}
@media(max-width:520px){.adv-grid{grid-template-columns:1fr}}
.adv-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:20px}
.adv-icon{font-size:28px;margin-bottom:10px}
.adv-title{font-size:14px;font-weight:700;color:#34d399;margin-bottom:4px}
.adv-text{font-size:13px;color:rgba(255,255,255,.45);line-height:1.6}

/* Fee box */
.fee-box{background:rgba(16,185,129,.08);border:1px solid rgba(16,185,129,.2);border-radius:14px;padding:20px;margin-top:28px}
.fee-row{display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid rgba(255,255,255,.06);font-size:13px}
.fee-row:last-child{border-bottom:none;font-weight:700}
.fee-label{color:rgba(255,255,255,.55)}
.fee-value{font-weight:600}

/* Occasions */
.occasions{padding:56px 24px;max-width:700px;margin:0 auto}
.occ-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-top:24px}
@media(max-width:480px){.occ-grid{grid-template-columns:1fr 1fr}}
.occ-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:12px;padding:16px;text-align:center}
.occ-emoji{font-size:28px;margin-bottom:6px}
.occ-name{font-size:13px;font-weight:700;color:rgba(255,255,255,.8)}
.occ-sub{font-size:11px;color:rgba(255,255,255,.35);margin-top:2px}

/* CTA */
.cta-bottom{background:linear-gradient(135deg,rgba(5,150,105,.15),rgba(16,185,129,.08));border-top:1px solid rgba(16,185,129,.2);padding:64px 24px;text-align:center}
.cta-bottom h2{font-size:clamp(24px,4vw,36px);font-weight:900;margin-bottom:12px}
.cta-bottom p{font-size:15px;color:rgba(255,255,255,.5);margin-bottom:32px;line-height:1.6}

.footer{padding:20px 24px;text-align:center;color:rgba(255,255,255,.2);font-size:11px;border-top:1px solid rgba(255,255,255,.06)}
</style>
</head>
<body>

<nav class="nav">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    <a href="{{ route('collection.new') }}" class="nav-cta">Start a Collection →</a>
</nav>

<!-- Hero -->
<div class="hero">
    <div class="badge">🤝 Group Collections · Powered by M-Pesa</div>
    <h1>Collect contributions.<br><em>No WhatsApp group needed.</em></h1>
    <p>Share one link. Everyone contributes directly via M-Pesa STK Push. No group chats, no pressure, no public shaming, no admin handling cash. Money goes straight to the person who needs it.</p>
    <div class="hero-btns">
        <a href="{{ route('collection.new') }}" class="btn-primary">Start a Collection — Free</a>
        <a href="#how-it-works" class="btn-secondary">See How It Works</a>
    </div>
</div>

<!-- Page preview -->
<div class="preview">
    <div class="preview-url">🔗 pregota.com/collections/<strong>grace-wanjiku-welfare</strong></div>
    <div class="preview-card">
        <div class="preview-label">YOUR COLLECTION</div>
        <div class="occ-badge">🕊️ Bereavement</div>
        <div class="preview-title">Grace Wanjiku Bereavement Welfare</div>
        <div class="preview-desc">Kamau Mwangi's mother passed away on Monday. The family is in need of support for funeral expenses. All contributions go directly to the family.</div>
        <div class="preview-bar-wrap">
            <div class="preview-bar-label">
                <span>KES 18,600 raised</span>
                <span>of KES 30,000</span>
            </div>
            <div class="preview-bar"><div class="preview-bar-fill"></div></div>
        </div>
        <div class="preview-stats">
            <div class="preview-stat"><strong>37</strong>Contributors</div>
            <div class="preview-stat"><strong>3 days</strong>Remaining</div>
            <div class="preview-stat"><strong>100%</strong>Goes to family</div>
        </div>
        <div class="preview-btn">Contribute via M-Pesa ›</div>
    </div>
</div>

<!-- The Problem -->
<div class="section">
    <div class="section-tag">The Problem</div>
    <h2>WhatsApp groups were never built for collecting money.</h2>
    <p>Every Kenyan has been pulled into a "contribution WhatsApp group" they didn't ask to join — and lived to regret it.</p>

    <div class="problem-card">
        <div class="problem-title">📲 Why WhatsApp group collections fail everyone</div>

        <div class="problem-item">
            <div class="problem-icon">🔔</div>
            <div class="problem-text">
                <strong>Added without your consent — and you can't escape</strong>
                <span>You're added to a group you never agreed to join. Leaving feels rude. So you stay, muted, watching hundreds of messages pile up about a collection you contributed to weeks ago.</span>
            </div>
        </div>

        <div class="problem-item">
            <div class="problem-icon">😳</div>
            <div class="problem-text">
                <strong>"These people have not yet contributed" — public shaming</strong>
                <span>The admin posts a list. Your name is on it. 200 people see it. The pressure and embarrassment that follows is real — even when you had every intention to contribute.</span>
            </div>
        </div>

        <div class="problem-item">
            <div class="problem-icon">🔓</div>
            <div class="problem-text">
                <strong>Your number is now in the hands of strangers</strong>
                <span>Every member of that group has your number. They save it, forward it to other groups, or use it to add you to the next collection — without asking. Your privacy is gone.</span>
            </div>
        </div>

        <div class="problem-item">
            <div class="problem-icon">💸</div>
            <div class="problem-text">
                <strong>Admin handles all the cash — with zero accountability</strong>
                <span>Everyone sends to one person's M-Pesa. There's no real-time tally, no proof of total collected, no guarantee every shilling reaches the recipient. You just have to trust.</span>
            </div>
        </div>

        <div class="problem-item">
            <div class="problem-icon">🗂️</div>
            <div class="problem-text">
                <strong>Tracking is a nightmare of screenshots and guesswork</strong>
                <span>Admins collect M-Pesa messages, paste them into notes, manually count who paid. Mistakes happen. Disputes happen. It shouldn't be this hard.</span>
            </div>
        </div>
    </div>
</div>

<!-- How it works -->
<div class="how" id="how-it-works">
    <div class="how-inner">
        <div class="section-tag">How It Works</div>
        <h2>One link. Everyone pays directly. You're done.</h2>
        <p style="font-size:15px;color:rgba(255,255,255,.5);margin-top:10px">Set up a collection in under 2 minutes. Share the link anywhere. Watch contributions arrive in real time.</p>

        <div class="steps">
            <div class="step">
                <div class="step-num">1</div>
                <div class="step-body">
                    <h3>Create the collection</h3>
                    <p>Enter the occasion (bereavement, wedding, medical, etc.), write a short description, and add the recipient's name and M-Pesa number privately. Set a target amount and deadline if you want — or leave it open. Takes under 2 minutes.</p>
                </div>
            </div>
            <div class="step">
                <div class="step-num">2</div>
                <div class="step-body">
                    <h3>Share the link — that's all</h3>
                    <p>You get a unique link like <strong style="color:#34d399">pregota.com/collections/your-title</strong>. Send it on WhatsApp, share in a status, post in a group, or forward by text. No group to create, no admin rights to manage.</p>
                </div>
            </div>
            <div class="step">
                <div class="step-num">3</div>
                <div class="step-body">
                    <h3>Contributors pay directly via M-Pesa</h3>
                    <p>Each person opens the link, picks an amount, enters their phone number, and gets an M-Pesa STK Push. They confirm with their PIN. The money moves immediately — they never need to know anyone's personal number.</p>
                </div>
            </div>
            <div class="step">
                <div class="step-num">4</div>
                <div class="step-body">
                    <h3>Money goes straight to the recipient</h3>
                    <p>As soon as the goal is reached (or the deadline passes, or you trigger it manually), the collected amount is paid out directly to the recipient's M-Pesa. You as organiser never touch a single shilling.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Contributor experience -->
<div class="contrib-section">
    <div class="section-tag">What Contributors Experience</div>
    <h2>As easy as sending an M-Pesa.</h2>
    <p style="font-size:15px;color:rgba(255,255,255,.5);margin-top:10px;margin-bottom:0">No sign-up. No app. No asking anyone for a number. Just click, confirm, done.</p>

    <div class="contrib-flow">
        <div class="contrib-step">
            <div class="contrib-icon">🔗</div>
            <div class="contrib-body">
                <h4>They click the link you shared</h4>
                <p>The collection page loads instantly — title, story, photo, how much has been raised so far, and how many people have contributed. Fully transparent.</p>
            </div>
        </div>
        <div class="contrib-step">
            <div class="contrib-icon">💰</div>
            <div class="contrib-body">
                <h4>They enter whatever they can — or a fixed amount if the group requires it</h4>
                <p>By default, contributors type any amount freely — KES 50, KES 5,000, whatever they can manage. If the organiser set a per-person amount (e.g. chama rule: KES 500 each), that amount is pre-filled and locked automatically — no confusion, no under-paying. They add their name or stay anonymous.</p>
            </div>
        </div>
        <div class="contrib-step">
            <div class="contrib-icon">📱</div>
            <div class="contrib-body">
                <h4>M-Pesa STK Push arrives on their phone</h4>
                <p>They enter their own number. A standard M-Pesa prompt pops up on their screen. They type their PIN and confirm. No codes to share, no one's number to memorise.</p>
            </div>
        </div>
        <div class="contrib-step">
            <div class="contrib-icon">✅</div>
            <div class="contrib-body">
                <h4>Confirmed — they're on the contributor wall</h4>
                <p>They see a thank-you screen. Their name (if provided) appears on the public contributor list. The collection total updates instantly for everyone.</p>
            </div>
        </div>
    </div>
</div>

<!-- Advantages -->
<div class="adv">
    <div class="adv-inner">
        <div class="section-tag">Why Pregota</div>
        <h2>Everything a WhatsApp group can't give you.</h2>

        <div class="adv-grid">
            <div class="adv-card">
                <div class="adv-icon">🚫</div>
                <div class="adv-title">No group. No drama.</div>
                <div class="adv-text">Nobody gets added anywhere. No notifications, no pressure messages, no exit awkwardness. Share a link — people contribute on their own terms.</div>
            </div>
            <div class="adv-card">
                <div class="adv-icon">🔒</div>
                <div class="adv-title">Everyone's privacy protected</div>
                <div class="adv-text">Contributors don't need to share their number with the organiser or anyone else. The organiser never sees contributor phone numbers. The recipient's number is never shown publicly.</div>
            </div>
            <div class="adv-card">
                <div class="adv-icon">📊</div>
                <div class="adv-title">Real-time total — fully transparent</div>
                <div class="adv-text">Anyone with the link can see exactly how much has been raised and how many people have contributed. No guessing, no trusting a screenshot.</div>
            </div>
            <div class="adv-card">
                <div class="adv-icon">💸</div>
                <div class="adv-title">Organiser never handles the money</div>
                <div class="adv-text">Every contribution goes directly into the collection. When it pays out, it goes straight to the recipient's M-Pesa — not through your personal account. Zero risk of disputes.</div>
            </div>
            <div class="adv-card">
                <div class="adv-icon">⏰</div>
                <div class="adv-title">Open 24/7 — even after your status expires</div>
                <div class="adv-text">The link works anytime. People can contribute at midnight, a week after you shared it, even after you forgot about it. The collection page stays live until you close it.</div>
            </div>
            <div class="adv-card">
                <div class="adv-icon">🙌</div>
                <div class="adv-title">Contributors choose their anonymity</div>
                <div class="adv-text">Want to give quietly without appearing on the wall? Contributors can stay anonymous. No obligation to publicly display their contribution if they'd rather not.</div>
            </div>
        </div>

        <p style="font-size:13px;color:rgba(255,255,255,.3);margin-top:20px;text-align:center">KES 30 flat fee per contribution · Added on top · Recipient receives 100% of every pledged amount</p>

        <div class="fee-box">
            <div class="fee-row">
                <span class="fee-label">Contributor pledges</span>
                <span class="fee-value" style="color:#34d399">KES 500</span>
            </div>
            <div class="fee-row">
                <span class="fee-label">Pregota platform fee (added on top)</span>
                <span class="fee-value" style="color:rgba(255,255,255,.5)">+ KES 30</span>
            </div>
            <div class="fee-row">
                <span class="fee-label">Contributor pays via M-Pesa STK Push</span>
                <span class="fee-value">KES 530</span>
            </div>
            <div class="fee-row" style="margin-top:4px;padding-top:12px;border-top:1px solid rgba(255,255,255,.08)">
                <span class="fee-label">Recipient receives</span>
                <span class="fee-value" style="color:#34d399;font-size:15px">KES 500 — every shilling pledged</span>
            </div>
        </div>
    </div>
</div>

<!-- Occasions -->
<div class="occasions">
    <div class="section-tag">When to Use It</div>
    <h2>Any occasion where Kenyans come together.</h2>
    <div class="occ-grid">
        <div class="occ-card">
            <div class="occ-emoji">🕊️</div>
            <div class="occ-name">Bereavement</div>
            <div class="occ-sub">Funeral welfare</div>
        </div>
        <div class="occ-card">
            <div class="occ-emoji">💒</div>
            <div class="occ-name">Wedding</div>
            <div class="occ-sub">Harambee ya ndoa</div>
        </div>
        <div class="occ-card">
            <div class="occ-emoji">🏥</div>
            <div class="occ-name">Medical</div>
            <div class="occ-sub">Hospital bills</div>
        </div>
        <div class="occ-card">
            <div class="occ-emoji">🤲</div>
            <div class="occ-name">Chama</div>
            <div class="occ-sub">Group savings</div>
        </div>
        <div class="occ-card">
            <div class="occ-emoji">🎂</div>
            <div class="occ-name">Birthday</div>
            <div class="occ-sub">Surprise collections</div>
        </div>
        <div class="occ-card">
            <div class="occ-emoji">🎓</div>
            <div class="occ-name">Education</div>
            <div class="occ-sub">School fees & fees</div>
        </div>
        <div class="occ-card">
            <div class="occ-emoji">🏠</div>
            <div class="occ-name">Moving</div>
            <div class="occ-sub">New home harambee</div>
        </div>
        <div class="occ-card">
            <div class="occ-emoji">🙏</div>
            <div class="occ-name">Community</div>
            <div class="occ-sub">Neighbourhood causes</div>
        </div>
        <div class="occ-card">
            <div class="occ-emoji">🎁</div>
            <div class="occ-name">Gift</div>
            <div class="occ-sub">Team or office gifts</div>
        </div>
    </div>
</div>

<!-- CTA -->
<div class="cta-bottom">
    <h2>Ready to collect?<br><em style="font-style:normal;background:linear-gradient(135deg,#34d399,#10b981);-webkit-background-clip:text;-webkit-text-fill-color:transparent">No WhatsApp group required.</em></h2>
    <p>Takes under 2 minutes to set up. Share one link. Let people contribute on their own — in their own time, with their privacy intact.</p>
    <a href="{{ route('collection.new') }}" class="btn-primary" style="font-size:16px;padding:16px 36px">Start a Collection — Free →</a>
    <p style="margin-top:16px;font-size:12px;color:rgba(255,255,255,.3)">No account needed · No monthly fee · Money goes direct to recipient</p>
</div>

@include('partials.discover', ['current' => 'collection', 'fullWidth' => true])
<footer class="footer">© 2026 Pregota · Group Collections via M-Pesa · pregota.com</footer>

</body>
</html>
