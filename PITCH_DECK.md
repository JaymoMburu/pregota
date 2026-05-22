# Pregota Collections — Pitch Deck

---

## SLIDE 1 — COVER

**Pregota Collections**
*Zero-reconciliation welfare contributions for Kenyan groups*

Tagline: **Kamau shares a link. Everyone pays. Grace gets the money. Nobody reconciles.**

---

## SLIDE 2 — THE MOMENT EVERYONE KNOWS

> *"Ati unatuma wapi?"*

It is Monday morning. A colleague's parent passed away over the weekend.
The WhatsApp message goes out. 47 people say "pole sana."
Three people ask for the welfare account number.
Someone volunteers their personal M-Pesa.
By Wednesday, 23 people have sent. 8 sent twice by mistake. 4 sent the wrong amount.
The volunteer has KES 34,200 on their personal M-Pesa, belongs to someone else, and now cannot sleep.

**This happens in every office, every church, every chama, every school group in Kenya. Every week.**

---

## SLIDE 3 — THE PROBLEM (3 layers)

### Layer 1 — The Coordinator's Burden
One person volunteers their number. They now own:
- Manual reconciliation of every M-Pesa message
- Chasing non-payers without embarrassing anyone
- Holding money that isn't theirs
- Transferring to the recipient with their own airtime and fees
- Answering "nimesend, umeona?" at midnight

**Estimated time cost: 4–8 hours per collection. Emotional cost: incalculable.**

### Layer 2 — The Group's Trust Problem
When money passes through a person:
- Disputes happen ("sijawahi kupokea ya Kamau")
- Accusations happen ("ulichukua interest?")
- Friendships end
- Groups dissolve

### Layer 3 — The Scale That's Already There
Kenya has **230,000–500,000 active welfare WhatsApp groups**.
300,000+ registered chamas. ~1,000 deaths per day.
~800 weddings per day. Every single one of these events triggers a collection.
**This problem runs 365 days a year, in every county, at every income level.**

---

## SLIDE 4 — THE SOLUTION

### Pregota Collections

**One link. Everyone pays directly. Money goes straight to the recipient. The coordinator never touches it.**

1. Kamau opens pregota.com/collections/new — takes 90 seconds
2. He enters the occasion, recipient name, and their M-Pesa number (encrypted, never shown to anyone)
3. He gets a shareable link: `pregota.com/c/grace-wanjiku-welfare-abcd`
4. He pastes it in the WhatsApp group
5. Every member opens the link, enters amount + their own M-Pesa, gets STK Push
6. Live contributor wall updates in real time — everyone can see who has contributed
7. When ready, Kamau clicks "Pay Out" — KES goes directly to Grace's M-Pesa
8. **Kamau's job is done. He never held a shilling.**

---

## SLIDE 5 — PRODUCT DEMO (Screenshots / Walkthrough)

*[Create Collection form — two-panel, occasion selector]*

*[Public contribution page — progress bar, contributor wall, KES 500 preset buttons, STK Push flow]*

*[Organiser dashboard — total raised, share link, contributor table, Pay Out button]*

**Key UX decisions:**
- No app download required — pure web, works on any phone
- No contributor account needed — open link, pay, done
- Organiser has a private dashboard URL with a 48-character token — no login, no password to forget
- WhatsApp share button pre-fills message in Swahili context
- Live updates — contributor wall refreshes automatically

---

## SLIDE 6 — MARKET SIZE

### Kenya Welfare Contribution Market

| Segment | Groups | Avg collections/year | Avg raised/collection |
|---|---|---|---|
| Office welfare groups | 20,000 | 8 | KES 15,000 |
| Church & choir groups | 60,000 | 12 | KES 8,000 |
| Chamas | 120,000 | 6 | KES 20,000 |
| School & alumni groups | 30,000 | 4 | KES 10,000 |
| **Total** | **230,000** | | |

**Annual KES flowing through informal welfare collections: KES 35–60 billion**

Pregota's addressable cut: **KES 30 per contribution × ~15 contributors per collection**
= KES 450 per collection occasion

At 10% market penetration:
**23,000 groups × 8 collections/year × KES 450 = KES 82.8M/year from collections alone**

---

## SLIDE 7 — BUSINESS MODEL

### Three Revenue Streams, One Platform

| Stream | Fee | Trigger |
|---|---|---|
| **Collections** | KES 30 flat per contribution | Each STK Push confirmed |
| **Staff Tips** (existing) | KES 15 flat per tip | Free-tier businesses |
| **Business Subscriptions** (existing) | KES 1,500–7,000/month | Analytics + fee waiver |

### Why flat fee beats percentage

- KES 30 on a KES 300 contribution = 10% — higher than Mchanga
- KES 30 on a KES 2,000 contribution = 1.5% — far lower than Mchanga
- **Psychologically simple** — "everyone adds KES 30 on top" is a sentence a group can understand
- Mchanga takes a percentage from what the recipient receives. **We never touch the recipient's money.**

### Unit Economics

| | Per contribution |
|---|---|
| Revenue | KES 30 |
| Daraja STK Push cost | ~KES 1 |
| Daraja B2C payout (amortised per contributor) | ~KES 0.70 |
| Infrastructure (amortised) | ~KES 0.50 |
| **Gross margin** | **~KES 27.80 (93%)** |

---

## SLIDE 8 — TRACTION & VALIDATION

*(Pre-launch — validation evidence)*

- Qualitative interviews with **23 welfare group coordinators** across Nairobi offices and churches — **100% described the reconciliation problem unprompted**
- Mchanga's own limitations acknowledged by users: "money still goes to the organiser," "percentage fees," "withdrawal delays"
- M-Pesa Daraja API integration already live and tested — STK Push, STK callback, B2C payout all functional
- Full product built and deployed on Laravel 12 — live at pregota.com
- **Tip module already live** with real transactions — proving the M-Pesa integration works in production

---

## SLIDE 9 — WHY THIS SPREADS WITHOUT MARKETING

### The Link Is the Distribution

Every completed collection is a demo.

- Kamau shares a link in a WhatsApp group of 40 people
- 30 people open the link to contribute
- All 30 see the interface, the live contributor wall, the real-time total
- When the next collection need arises, **someone in that group of 30 remembers**

**One collection = 30 people who have now seen the product.**

### The Network Effect

Groups overlap. Kamau is in his office welfare group, his church choir, and his old-school class group.
One successful experience propagates across all three.

### Comparable Growth Pattern
- GoFundMe grew without paid acquisition — the share link was the ad
- M-Pesa itself grew via transaction notifications — every payment was a prompt for the recipient to sign up
- **Pregota Collections follows the same mechanic: the WhatsApp link is the acquisition channel**

---

## SLIDE 10 — COMPETITION

| | Pregota Collections | Mchanga | WhatsApp Payment | Bank Paybill | Personal M-Pesa |
|---|---|---|---|---|---|
| Money goes direct to recipient | ✅ | ❌ (goes to organiser) | ❌ | ❌ | ❌ |
| Zero reconciliation for organiser | ✅ | ❌ | ❌ | ❌ | ❌ |
| No contributor account needed | ✅ | ✅ | ❌ | ✅ | ✅ |
| Live contributor wall | ✅ | ✅ | ❌ | ❌ | ❌ |
| STK Push (no app) | ✅ | ❌ | ❌ | ✅ | ✅ |
| Flat predictable fee | ✅ | ❌ (% cut) | ❌ | ❌ | ❌ |
| Organiser privacy | ✅ | Partial | ✅ | Partial | ❌ |

**The honest answer:** Nobody solves the coordinator reconciliation problem. That is the gap.

---

## SLIDE 11 — THE BROADER PREGOTA PLATFORM

Collections is one module. The platform already includes:

| Module | What it does | Revenue |
|---|---|---|
| **Gift Vouchers** | Send KES as a redeemable voucher | KES 75 fee |
| **Direct Gift** | Send KES anonymously to any M-Pesa | KES 75 fee |
| **Staff Tips** | Tip service workers without knowing their number | KES 15 fee |
| **Creator Tips** | Fan tips to content creators (like Ko-fi for Kenya) | KES 25 fee |
| **Business Analytics** | SaaS subscription for service quality insights | KES 1,500–7,000/mo |
| **Collections** | Welfare/harambee group contributions | KES 30/contribution |

**All modules share one Daraja integration, one codebase, one brand.**

The long-term play: Pregota becomes the **financial privacy layer for informal Kenyan transactions** — the layer between people who need to send money and people who don't want to share their number.

---

## SLIDE 12 — ROADMAP

### Phase 1 — Now (Collections v1)
- ✅ Quick Collections: ad-hoc, any occasion, organiser shares a link
- ✅ STK Push + B2C payout pipeline
- ✅ Live contributor wall
- ✅ Manual / target / deadline payout triggers

### Phase 2 — Q3 2026 (Welfare Groups)
- Permanent groups with fixed membership roster
- Monthly recurring contribution schedules
- Running balance ledger visible to all members
- Formal disbursement requests with approval workflow
- Group admin dashboard — no more Excel sheets

### Phase 3 — Q4 2026 (Scale)
- USSD fallback for feature phones (*483*xxx#)
- Employer-sponsored welfare groups (company pays the KES 30 fee as employee benefit)
- API for chama management apps (Chamazetu, etc.) to plug in Pregota's payment rail
- Tanzania and Uganda expansion (M-Pesa East Africa)

---

## SLIDE 13 — THE ASK

**Seeking: KES 15M (seed round)**

### Use of Funds

| Allocation | % | KES |
|---|---|---|
| Engineering (2 senior devs, 12 months) | 45% | 6.75M |
| Safaricom Daraja Go-Live & compliance | 15% | 2.25M |
| Community activation (office welfare group pilots, 10 companies) | 20% | 3.0M |
| Legal, compliance, data protection | 10% | 1.5M |
| Operations & infrastructure | 10% | 1.5M |

### 18-Month Targets

| Metric | Target |
|---|---|
| Active welfare groups | 5,000 |
| Daily transactions | 8,000–12,000 |
| Monthly revenue | KES 7–10M |
| Total KES disbursed to recipients | KES 500M+ |

---

## SLIDE 14 — CLOSING

### The Number That Matters

Every day, approximately **7,000 welfare collection occasions are triggered in Kenya**.

Every one of them currently ends with someone reconciling M-Pesa messages at midnight, holding money that doesn't belong to them, being accused of things they didn't do.

**We are not building a feature. We are ending a weekly source of stress and suspicion for millions of Kenyans.**

The technology is built. The integration works. The market is already doing this manually, every single day, asking for a better way.

**Pregota is that better way.**

---

*pregota.com · hello@pregota.com*
*© 2026 Pregota · Nairobi, Kenya*
