<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>School Fee Collections — Pregota</title>
<meta name="description" content="Collect school fees via M-Pesa without handling cash. Parents pay directly — class teachers share a link, admin sees everything in real time.">
@include('partials.pwa')
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#0B141A;color:#fff;min-height:100vh}

.nav{padding:14px 24px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(255,255,255,.08);position:sticky;top:0;background:#0B141A;z-index:10}
.logo{font-size:20px;font-weight:900;background:linear-gradient(135deg,#60a5fa,#93c5fd);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none}
.nav-cta{background:linear-gradient(135deg,#2563eb,#3b82f6);color:#fff;border:none;border-radius:8px;padding:8px 18px;font-size:13px;font-weight:700;cursor:pointer;text-decoration:none}

.hero{padding:64px 24px 48px;text-align:center;max-width:640px;margin:0 auto}
.badge{display:inline-flex;align-items:center;gap:7px;background:rgba(96,165,250,.1);border:1px solid rgba(96,165,250,.25);border-radius:20px;padding:6px 16px;font-size:12px;font-weight:700;color:#60a5fa;margin-bottom:24px;letter-spacing:.05em}
.hero h1{font-size:clamp(30px,6vw,50px);font-weight:900;line-height:1.1;letter-spacing:-.5px;margin-bottom:18px}
.hero h1 em{font-style:normal;background:linear-gradient(135deg,#60a5fa,#93c5fd);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.hero p{font-size:16px;color:rgba(255,255,255,.82);line-height:1.7;margin-bottom:32px;max-width:460px;margin-left:auto;margin-right:auto}
.hero-btns{display:flex;gap:12px;justify-content:center;flex-wrap:wrap}
.btn-primary{background:linear-gradient(135deg,#2563eb,#3b82f6);color:#fff;border:none;border-radius:12px;padding:14px 28px;font-size:15px;font-weight:700;cursor:pointer;text-decoration:none;display:inline-block}
.btn-secondary{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.15);color:rgba(255,255,255,.7);border-radius:12px;padding:14px 28px;font-size:15px;font-weight:700;text-decoration:none;display:inline-block}

/* Role cards */
.roles{padding:48px 24px;max-width:700px;margin:0 auto}
.section-tag{display:inline-block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:rgba(255,255,255,.6);margin-bottom:12px}
.roles-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-top:28px}
@media(max-width:560px){.roles-grid{grid-template-columns:1fr}}
.role-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:16px;padding:22px}
.role-icon{font-size:28px;margin-bottom:12px}
.role-title{font-size:14px;font-weight:700;margin-bottom:6px;color:#60a5fa}
.role-desc{font-size:13px;color:rgba(255,255,255,.72);line-height:1.6}

/* Problem */
.prob-card{background:rgba(239,68,68,.06);border:1px solid rgba(239,68,68,.15);border-radius:16px;padding:24px;margin-top:24px}
.prob-item{display:flex;align-items:flex-start;gap:14px;padding:11px 0;border-bottom:1px solid rgba(239,68,68,.1)}
.prob-item:last-child{border-bottom:none}
.prob-icon{font-size:20px;flex-shrink:0;margin-top:1px}
.prob-text strong{font-size:13px;color:#fca5a5;display:block;margin-bottom:3px}
.prob-text span{font-size:12px;color:rgba(255,255,255,.68);line-height:1.6}

/* How it works */
.how{background:rgba(96,165,250,.04);border-top:1px solid rgba(96,165,250,.1);border-bottom:1px solid rgba(96,165,250,.1);padding:56px 24px}
.how-inner{max-width:700px;margin:0 auto}
.steps{display:flex;flex-direction:column;gap:0;margin-top:28px}
.step{display:flex;gap:20px;padding:20px 0;border-bottom:1px solid rgba(255,255,255,.05)}
.step:last-child{border-bottom:none}
.step-num{width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#2563eb,#3b82f6);display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:900;flex-shrink:0;margin-top:2px}
.step-body h3{font-size:15px;font-weight:700;margin-bottom:4px}
.step-body p{font-size:13px;color:rgba(255,255,255,.72);line-height:1.6}

/* Features */
.features{padding:56px 24px;max-width:700px;margin:0 auto}
.feat-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-top:28px}
@media(max-width:520px){.feat-grid{grid-template-columns:1fr}}
.feat-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:20px}
.feat-icon{font-size:28px;margin-bottom:10px}
.feat-title{font-size:14px;font-weight:700;color:#60a5fa;margin-bottom:4px}
.feat-text{font-size:13px;color:rgba(255,255,255,.72);line-height:1.6}

/* Preview card */
.preview{padding:40px 24px;max-width:680px;margin:0 auto;text-align:center}
.preview-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:20px;padding:24px;text-align:left;max-width:380px;margin:0 auto;position:relative}
.preview-label{position:absolute;top:-12px;right:16px;background:#3b82f6;color:#fff;font-size:10px;font-weight:700;padding:3px 10px;border-radius:20px;letter-spacing:.05em}
.preview-school{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.5);margin-bottom:6px}
.preview-title{font-size:17px;font-weight:900;margin-bottom:4px}
.preview-term{font-size:12px;color:rgba(255,255,255,.6);margin-bottom:16px}
.student-row{display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid rgba(255,255,255,.06);font-size:13px}
.student-row:last-child{border-bottom:none}
.student-name{color:rgba(255,255,255,.85);font-weight:600}
.paid-badge{background:rgba(34,197,94,.15);border:1px solid rgba(34,197,94,.3);color:#4ade80;font-size:10px;font-weight:700;padding:2px 8px;border-radius:20px}
.pending-badge{background:rgba(251,191,36,.1);border:1px solid rgba(251,191,36,.25);color:#fbbf24;font-size:10px;font-weight:700;padding:2px 8px;border-radius:20px}

/* Fee box */
.fee-box{background:rgba(96,165,250,.08);border:1px solid rgba(96,165,250,.2);border-radius:14px;padding:20px;margin-top:24px}
.fee-row{display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid rgba(255,255,255,.06);font-size:13px}
.fee-row:last-child{border-bottom:none;font-weight:700}
.fee-label{color:rgba(255,255,255,.82)}
.fee-value{font-weight:600}

/* CTA */
.cta-bottom{background:linear-gradient(135deg,rgba(37,99,235,.15),rgba(59,130,246,.08));border-top:1px solid rgba(96,165,250,.2);padding:64px 24px;text-align:center}
.cta-bottom h2{font-size:clamp(24px,4vw,36px);font-weight:900;margin-bottom:12px}
.cta-bottom p{font-size:15px;color:rgba(255,255,255,.78);margin-bottom:32px;line-height:1.6}
.footer{padding:20px 24px;text-align:center;color:rgba(255,255,255,.2);font-size:11px;border-top:1px solid rgba(255,255,255,.06)}
.section{padding:56px 24px;max-width:700px;margin:0 auto}
.section h2{font-size:clamp(22px,4vw,32px);font-weight:900;line-height:1.2;margin-bottom:14px}
.section p{font-size:15px;color:rgba(255,255,255,.78);line-height:1.7}
</style>
</head>
<body>

<nav class="nav">
    <a href="{{ route('home') }}" class="logo">Pregota</a>
    <a href="{{ route('school-collection.new') }}" class="nav-cta">Set Up Your School →</a>
</nav>

<!-- Hero -->
<div class="hero">
    <div class="badge">🏫 School Fee Collections · via M-Pesa</div>
    <h1>Collect school fees.<br><em>No cash handling.<br>No reconciliation.</em></h1>
    <p>Parents pay directly via M-Pesa STK Push. Class teachers share a link with their class. Admin sees who has paid in real time — per student, per class.</p>
    <div class="hero-btns">
        <a href="{{ route('school-collection.new') }}" class="btn-primary">Set Up a Collection — Free</a>
        <a href="#how-it-works" class="btn-secondary">See How It Works</a>
    </div>
</div>

<!-- Who is this for -->
<div class="roles">
    <div class="section-tag">Built for Everyone in the School</div>
    <h2 style="font-size:clamp(22px,4vw,32px);font-weight:900;line-height:1.2;margin-bottom:6px">One setup. Three views.</h2>
    <div class="roles-grid">
        <div class="role-card">
            <div class="role-icon">📋</div>
            <div class="role-title">Admin / Bursar</div>
            <div class="role-desc">Set up the collection in 3 minutes. See all classes, per-student payment status, total collected, and trigger the payout in one click.</div>
        </div>
        <div class="role-card">
            <div class="role-icon">👩‍🏫</div>
            <div class="role-title">Class Teacher</div>
            <div class="role-desc">Get your own class link. Share it with parents on WhatsApp. See exactly which of your students have paid and which haven't — no chasing admin.</div>
        </div>
        <div class="role-card">
            <div class="role-icon">👨‍👩‍👧</div>
            <div class="role-title">Parent</div>
            <div class="role-desc">Click the link, enter your child's name, get an M-Pesa STK Push. Confirm with your PIN. Done — no queues, no bank, no cash envelope.</div>
        </div>
    </div>
</div>

<!-- The problem -->
<div class="section">
    <div class="section-tag">The Problem Today</div>
    <h2>Cash collection in schools is a 2005 problem. We're in 2026.</h2>

    <div class="prob-card">
        <div class="prob-item">
            <div class="prob-icon">💸</div>
            <div class="prob-text">
                <strong>Cash gets lost between parent and school</strong>
                <span>Money changes hands via students, parents deposit into teacher accounts, or cash is collected in class. Each step is a reconciliation nightmare and a theft risk.</span>
            </div>
        </div>
        <div class="prob-item">
            <div class="prob-icon">📝</div>
            <div class="prob-text">
                <strong>Teachers spend hours on payment follow-up</strong>
                <span>Calling parents, updating spreadsheets, chasing unpaid students — all time that should be spent teaching. Pregota makes the list live and automatic.</span>
            </div>
        </div>
        <div class="prob-item">
            <div class="prob-icon">🗂️</div>
            <div class="prob-text">
                <strong>Reconciliation takes days — and still has errors</strong>
                <span>M-Pesa screenshots, bank slips, handwritten lists — everything has to be matched manually. Mistakes cause disputes. Pregota records every payment automatically.</span>
            </div>
        </div>
        <div class="prob-item">
            <div class="prob-icon">🚌</div>
            <div class="prob-text">
                <strong>Parents travel to deposit at the school or bank</strong>
                <span>For parents far from the school, paying fees means a trip. With Pregota, they pay from their phone in 30 seconds — anywhere, any time.</span>
            </div>
        </div>
    </div>
</div>

<!-- How it works -->
<div class="how" id="how-it-works">
    <div class="how-inner">
        <div class="section-tag">How It Works</div>
        <h2>Admin sets up once. Everyone else is automatic.</h2>
        <p style="font-size:15px;color:rgba(255,255,255,.78);margin-top:10px">The whole school — every class, every teacher, every parent — runs from a single 3-minute setup.</p>

        <div class="steps">
            <div class="step">
                <div class="step-num">1</div>
                <div class="step-body">
                    <h3>Admin sets up the collection</h3>
                    <p>Enter the school name, term/year, fee amount per student, and the school's M-Pesa number. Add all classes with their class teachers. Takes about 3 minutes.</p>
                </div>
            </div>
            <div class="step">
                <div class="step-num">2</div>
                <div class="step-body">
                    <h3>Each class teacher gets their own link</h3>
                    <p>Every teacher receives a unique URL for their class only — e.g. <strong style="color:#60a5fa">pregota.com/sc/green-primary/form-2a/abc123</strong>. They share it with their class parents on WhatsApp. That's all they do.</p>
                </div>
            </div>
            <div class="step">
                <div class="step-num">3</div>
                <div class="step-body">
                    <h3>Parents pay via M-Pesa STK Push</h3>
                    <p>They click the link, enter their child's name and M-Pesa number. An STK Push arrives on their phone — they confirm with PIN. Done in under a minute. No bank visit. No cash envelope.</p>
                </div>
            </div>
            <div class="step">
                <div class="step-num">4</div>
                <div class="step-body">
                    <h3>Admin pays out to the school M-Pesa in one click</h3>
                    <p>When ready, the admin dashboard shows total collected and paid/unpaid per student per class. One click triggers a direct M-Pesa payout to the school's number — no middlemen.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview -->
<div class="preview">
    <div class="section-tag" style="display:block;text-align:center;margin-bottom:20px">What the Teacher Sees</div>
    <div class="preview-card">
        <div class="preview-label">TEACHER DASHBOARD</div>
        <div class="preview-school">Greenfield Primary · Term 2 2026</div>
        <div class="preview-title">Form 2A — Mr. Kamau</div>
        <div class="preview-term">Fee per student: KES 1,850 · 18 students</div>
        <div class="student-row"><span class="student-name">Achieng, Faith</span><span class="paid-badge">Paid</span></div>
        <div class="student-row"><span class="student-name">Kariuki, Brian</span><span class="paid-badge">Paid</span></div>
        <div class="student-row"><span class="student-name">Mutua, Jane</span><span class="pending-badge">Pending</span></div>
        <div class="student-row"><span class="student-name">Njoroge, Paul</span><span class="paid-badge">Paid</span></div>
        <div class="student-row"><span class="student-name">Wanjiku, Amina</span><span class="pending-badge">Pending</span></div>
        <div style="margin-top:14px;padding-top:14px;border-top:1px solid rgba(255,255,255,.07);display:flex;justify-content:space-between;font-size:12px;color:rgba(255,255,255,.6)">
            <span>16 / 18 paid</span>
            <span style="color:#4ade80;font-weight:700">KES 29,600 collected</span>
        </div>
    </div>
</div>

<!-- Features -->
<div class="features">
    <div class="section-tag">Features</div>
    <h2 style="font-size:clamp(22px,4vw,32px);font-weight:900;line-height:1.2;margin-bottom:6px">Built for how Kenyan schools actually work.</h2>
    <div class="feat-grid">
        <div class="feat-card">
            <div class="feat-icon">🔐</div>
            <div class="feat-title">School M-Pesa stays private</div>
            <div class="feat-text">The school number is encrypted and used only for payout. Parents never see it — they pay through Pregota's STK Push.</div>
        </div>
        <div class="feat-card">
            <div class="feat-icon">📊</div>
            <div class="feat-title">Per-student, per-class tracking</div>
            <div class="feat-text">Admin sees all classes and all students. Teachers see only their class. Both views update in real time — no spreadsheet needed.</div>
        </div>
        <div class="feat-card">
            <div class="feat-icon">📱</div>
            <div class="feat-title">Works on any phone</div>
            <div class="feat-text">No app download. Parents pay from any browser. The teacher's dashboard is mobile-first — designed for phones, not desktops.</div>
        </div>
        <div class="feat-card">
            <div class="feat-icon">🔍</div>
            <div class="feat-title">Teacher can search by student name</div>
            <div class="feat-text">A parent says they paid? The teacher types the name and sees payment status instantly — no scrolling through a long list.</div>
        </div>
        <div class="feat-card">
            <div class="feat-icon">💰</div>
            <div class="feat-title">Direct payout to school M-Pesa</div>
            <div class="feat-text">When ready, admin clicks once. The total collected goes straight to the school's M-Pesa. No manual bank transfer. No holding funds overnight.</div>
        </div>
        <div class="feat-card">
            <div class="feat-icon">🏫</div>
            <div class="feat-title">Works for any school type</div>
            <div class="feat-text">Primary, secondary, day school, boarding — any institution that collects per-student fees per term. Even PTA levies and trip contributions.</div>
        </div>
    </div>
</div>

<!-- Fee box -->
<div class="how" style="background:rgba(96,165,250,.04);border-color:rgba(96,165,250,.1)">
    <div class="how-inner">
        <div class="section-tag">Pricing</div>
        <h2 style="font-size:clamp(22px,4vw,32px);font-weight:900;line-height:1.2;margin-bottom:6px">KES 30 flat fee per payment. Paid by the parent.</h2>
        <p style="font-size:15px;color:rgba(255,255,255,.78);margin-top:8px">The school receives exactly what you set. The KES 30 service fee is added on top of the fee amount — paid by each parent separately.</p>

        <div class="fee-box">
            <div class="fee-row">
                <span class="fee-label">Fee per student (set by school)</span>
                <span class="fee-value" style="color:#60a5fa">KES 1,850</span>
            </div>
            <div class="fee-row">
                <span class="fee-label">Pregota service fee (flat, per payment)</span>
                <span class="fee-value" style="color:rgba(255,255,255,.78)">+ KES 30</span>
            </div>
            <div class="fee-row">
                <span class="fee-label">Parent pays via M-Pesa STK Push</span>
                <span class="fee-value">KES 1,880</span>
            </div>
            <div class="fee-row" style="margin-top:4px;padding-top:12px;border-top:1px solid rgba(255,255,255,.08)">
                <span class="fee-label">School receives</span>
                <span class="fee-value" style="color:#60a5fa;font-size:15px">KES 1,850 — exactly what you set</span>
            </div>
        </div>
    </div>
</div>

<!-- CTA -->
<div class="cta-bottom">
    <h2>Ready to end the cash collection chaos?<br><em style="font-style:normal;background:linear-gradient(135deg,#60a5fa,#93c5fd);-webkit-background-clip:text;-webkit-text-fill-color:transparent">Set up in 3 minutes.</em></h2>
    <p>One form. All classes. All teachers. Parents pay from their phones. The school gets the money directly.</p>
    <a href="{{ route('school-collection.new') }}" class="btn-primary" style="font-size:16px;padding:16px 36px">Set Up Your School Collection →</a>
    <p style="margin-top:16px;font-size:12px;color:rgba(255,255,255,.82)">Free to set up · KES 30 per payment · No monthly fee</p>
</div>

@include('partials.discover', ['current' => 'school', 'fullWidth' => true])
<footer class="footer">© 2026 Pregota · School Fee Collections via M-Pesa · pregota.com</footer>

</body>
</html>
