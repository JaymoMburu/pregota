# Pregota — Project Plan & Milestone Tracker
**Version 1.0 | May 2026**

---

## Current Status
- ✅ Product built (localhost) — all modules functional
- ✅ M-Pesa STK Push + B2C payout integrated (sandbox)
- ✅ Blockchain transaction sealing
- ❌ Not live on production domain
- ❌ Daraja production credentials not obtained
- ❌ Business not registered
- ❌ Zero paying users

---

## PHASE 1 — PRODUCTION READY
**Duration: Weeks 1–4**
**Goal: App live on real domain, real M-Pesa money flowing**

### Legal & Business (Week 1)
- [ ] Register business at eCitizen (Sole Proprietor or Limited Company)
      → Sole proprietor: KES 950, 1 day
      → Limited company: KES 10,650, 3–5 days
      *Recommended: Limited company — needed for Safaricom API*
- [ ] Open business bank account (Equity or KCB)
      → Needed for Daraja API application
- [ ] Get M-Pesa Paybill or Buy Goods number
      → Apply via Safaricom Business: safaricom.co.ke/business
      → Requires: certificate of incorporation, KRA PIN, bank account
      → Timeline: 2–3 weeks

### Technical (Weeks 1–2)
- [ ] Buy domain: `pregota.co.ke` or `pregota.com`
      → pregota.co.ke: ~KES 1,500/year (Kenya NIC)
      → pregota.com: ~$12/year (Namecheap)
- [ ] Set up server (DigitalOcean / Hetzner / Vultr)
      → Recommended: Hetzner CX22 — €3.79/month (cheapest reliable option)
      → Alternatively: Render.com free tier to start (limited)
- [ ] Configure Laravel for production
      → Set APP_ENV=production, APP_DEBUG=false
      → Configure proper .env for production DB
- [ ] Install SSL certificate (Let's Encrypt — free)
- [ ] Set up database backups (daily)
- [ ] Deploy app to server
- [ ] Test all routes and pages on live domain

### Daraja API Production (Weeks 2–4)
- [ ] Apply for Daraja API production access
      → Go to: developer.safaricom.co.ke
      → Submit: business registration, KRA PIN, paybill number
      → Timeline: 1–3 weeks for approval
- [ ] Apply for B2C production access (payouts)
      → Separate approval process — needed for all payouts to users
      → Timeline: 1–2 weeks after STK push approved
- [ ] Switch .env from sandbox to production credentials
- [ ] Test real KES 1 STK push end-to-end
- [ ] Test real KES 1 B2C payout end-to-end

### Pre-Launch Checklist (Week 4)
- [ ] Error monitoring set up (Laravel Telescope or Sentry free tier)
- [ ] Test school collection full flow with real money
- [ ] Test tip full flow with real money
- [ ] Test collection (chama) full flow with real money
- [ ] WhatsApp number set up for support (no support system needed yet)
- [ ] Basic FAQ page added to homepage

**MILESTONE 1 — GO LIVE**
📅 Target: End of Week 4
✅ Definition: Real M-Pesa transaction processed on pregota.co.ke end-to-end

**Budget Phase 1:**
| Item | Cost |
|------|------|
| Business registration | KES 10,650 |
| Domain (pregota.co.ke) | KES 1,500 |
| Server (3 months Hetzner) | KES 1,500 |
| Paybill registration | KES 0 (free) |
| Miscellaneous | KES 5,000 |
| **Total** | **KES 18,650** |

---

## PHASE 2 — PILOT: 3 SCHOOLS
**Duration: Weeks 5–10**
**Goal: 3 schools live, first real transactions, first KES earned**

### Week 5: Identify & Approach Schools
- [ ] List 10 schools within your immediate area (walking distance)
      *Start with schools you have a personal connection to — parent, alumni, friend who teaches*
- [ ] Visit each school in person — ask for the bursar or deputy principal
- [ ] Demo the system on your phone (live, on pregota.co.ke)
- [ ] Leave a one-page printed explainer (see explainer template below)
- [ ] Target: get 3 schools to agree to pilot

### Week 6–7: Onboard Pilot Schools
- [ ] Sit with each school admin, set up their collection live
      (takes 10 minutes per school)
- [ ] Walk them through the admin dashboard
- [ ] Help each teacher receive their tracking link via WhatsApp
- [ ] Confirm first parent payment goes through on each school
- [ ] Give your WhatsApp number to each bursar directly

### Week 8–10: Monitor & Fix
- [ ] Check admin dashboards daily
- [ ] Fix any issues within 24 hours
- [ ] Call each bursar weekly — ask what's confusing or not working
- [ ] Track: number of transactions, number of parents using it, complaints
- [ ] Collect written/voice note testimonials from bursars and teachers

**MILESTONE 2 — FIRST REVENUE**
📅 Target: End of Week 7
✅ Definition: First KES 30 fee collected from a real school payment

**MILESTONE 3 — PILOT COMPLETE**
📅 Target: End of Week 10
✅ Definition:
- 3 schools active
- Minimum 50 transactions processed
- KES 1,500 in fees collected
- At least 1 school ready to continue into Term 3

---

## PHASE 3 — EXPAND: CHAMAS + CHURCHES
**Duration: Months 3–4**
**Goal: Collections module gets first users, 10 active collection campaigns**

### Month 3: Leverage School Contacts
- [ ] Message every teacher in the pilot schools personally
      *"Hi, you've used Pregota for school fees. You can also use it for your chama
       or any group collection. Try it free: pregota.co.ke/collections/new"*
- [ ] Ask each bursar: "Does your school run any fundraisers or harambees?"
      → Offer to set up a collection for their next event
- [ ] Join 5 WhatsApp groups where your target users are active
      → Chama groups, parent groups, church groups
      → Don't spam — participate, then mention Pregota when relevant

### Month 4: Churches
- [ ] Identify 3 churches in your area (start with one you attend or know)
- [ ] Meet with the treasurer or pastor
- [ ] Pitch: handle one harambee or fundraiser free
- [ ] If successful, ask for permission to announce to congregation
- [ ] One church = access to chamas, funerals, school parents

**MILESTONE 4 — COLLECTIONS LIVE**
📅 Target: End of Month 4
✅ Definition:
- 10 active collection campaigns created
- At least 5 fully paid out (B2C confirmed)
- KES 5,000 cumulative fees collected

---

## PHASE 4 — GROWTH
**Duration: Months 5–6**
**Goal: Self-sustaining growth, first financial projection validated**

### Schools
- [ ] Ask each pilot school for referral to 2 other schools
- [ ] Target: 10 schools active by end of Month 6
- [ ] Approach a school cluster (schools under same management board)
      → One pitch = 5–10 schools

### Collections
- [ ] Create a simple WhatsApp broadcast list of all organiser contacts
- [ ] Send one message per month — tip, update, or success story
- [ ] Ask successful organisers to share their experience in their groups

### Tips Module
- [ ] Approach 3 restaurants you personally know
- [ ] Offer free setup for staff — frame it as a staff benefit
- [ ] Don't spend time here if no traction in 2 weeks — come back later

### Metrics to track weekly
- [ ] Total transactions this week
- [ ] Total fees collected this week
- [ ] New school collections created
- [ ] New group collections created
- [ ] Support issues raised

**MILESTONE 5 — REVENUE TARGET**
📅 Target: End of Month 6
✅ Definition:
- 10 schools active
- 30 collection campaigns processed
- KES 50,000 cumulative fees collected
- At least 1 unprompted referral received

---

## PHASE 5 — INVESTMENT READY
**Duration: Months 7–12**
**Goal: Enough traction to approach angels or accelerators**

### Product
- [ ] Add SMS notifications (Africa's Talking — cheap)
- [ ] Add transaction history page per user (downloadable PDF receipt)
- [ ] Explore school bulk onboarding (CSV upload for class lists)
- [ ] Mobile-optimise all pages based on real user feedback

### Business
- [ ] Formalise fee collection (reconciliation, accounting)
- [ ] Get at least 2 written testimonials from school bursars
- [ ] Document key metrics: MoM growth, retention, transaction volume
- [ ] Build a simple pitch deck (use the /deck page as base)

### Fundraising (Month 10–12 if ready)
- [ ] Apply to: Antler Kenya, Google for Startups Africa, Chandaria Foundation
- [ ] Apply to: Safaricom Spark Fund (fintech focus, ideal fit)
- [ ] Target raise: KES 5–10M seed to hire 1 sales person + marketing

**MILESTONE 6 — INVESTMENT READY**
📅 Target: Month 12
✅ Definition:
- KES 500,000 cumulative fees collected
- 50+ schools onboarded or in pipeline
- 200+ collection campaigns processed
- MoM growth rate of 20%+
- Clear unit economics documented

---

## FINANCIAL PROJECTIONS

| Month | Schools | Transactions | Fees (KES) | Cumulative |
|-------|---------|-------------|------------|-----------|
| 1 | 0 | 0 | 0 | 0 |
| 2 | 0 | 0 | 0 | 0 |
| 3 | 3 | 180 | 5,400 | 5,400 |
| 4 | 3 | 360 + 100 col | 10,800 + 3,000 | 19,200 |
| 5 | 6 | 720 + 200 col | 21,600 + 6,000 | 46,800 |
| 6 | 10 | 1,200 + 400 col | 36,000 + 12,000 | 94,800 |
| 9 | 25 | 3,000 + 1,000 col | 90,000 + 30,000 | 390,000 |
| 12 | 50 | 6,000 + 3,000 col | 180,000 + 90,000 | 1,020,000 |

*Col = collection contributions. School transactions assume 2 payments/student/term.*
*KES 1,000,000+ by Month 12 is achievable with consistent effort.*

---

## ONE-PAGE SCHOOL EXPLAINER (Print this, take it to schools)

```
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
PREGOTA — Digital School Fee Collection
pregota.co.ke
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

FOR THE SCHOOL:
✓ Set up in 5 minutes — no software to install
✓ Each class gets its own payment link
✓ You see every payment in real time
✓ Withdraw collected fees anytime via M-Pesa
✓ No cash handling, no bank queues

FOR PARENTS:
✓ Pay directly from M-Pesa — no app needed
✓ Pay in instalments — any amount accepted
✓ Instant M-Pesa confirmation received

FOR TEACHERS:
✓ Private dashboard showing who has paid
✓ See balance remaining per student
✓ Share reminder link via WhatsApp

COST: KES 30 per transaction (paid by parent)
      Zero cost to the school

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Contact: [YOUR WHATSAPP NUMBER]
Demo: pregota.co.ke/school-collection/new
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
```

---

## WEEKLY REVIEW QUESTIONS
Ask yourself every Monday:

1. How many transactions happened last week?
2. Did any user contact me with a problem?
3. Did I speak to at least 1 new potential user?
4. What is the one thing slowing adoption?
5. What can I ship or fix this week?

---

*Plan created: May 2026*
*Next review: End of Phase 2 (Week 10)*
