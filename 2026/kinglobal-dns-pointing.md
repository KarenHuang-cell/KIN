# Pointing kinglobal.net to Webflow

Self-contained guide. As of 2026-06-02.

---

## Status snapshot

| Domain | Webflow status | DNS | Action needed |
|---|---|---|---|
| `kin.net` | ✅ Connected · Active · Published | A → 198.202.211.1 (via cdn.webflow.com) | None — live |
| `www.kin.net` | ✅ Connected · Active · Default · Published | CNAME → cdn.webflow.com | None — live |
| `kinglobal.net` | ⚠️ Unverified · Not published | Not pointing to Webflow | **Add records below + verify + publish** |
| `www.kinglobal.net` | ⚠️ Unverified · Not published | Not pointing to Webflow | **Same flow** |

Both domains live on **GoDaddy** (same account that holds kin.net).

---

## Webflow site context

- Site: **KIN Global** (siteId `69f5ade35adb69631fc0fa1a`)
- Designer: https://kin-global.design.webflow.com
- Publishing tab: https://webflow.com/dashboard/sites/kin-global/publishing
- Site is already published to kin.net + www.kin.net (12+ hours stable)

---

## Path A — Quick Update via Entri (recommended)

This is what worked for kin.net. Entri auto-detects GoDaddy, logs you in, writes the records.

1. Webflow Publishing tab → click **`⋯`** menu next to **`kinglobal.net`** → **Quick update**
2. Entri popup appears → **Continue**
3. Entri detects GoDaddy → prompts for GoDaddy login
4. Sign in to GoDaddy → enter **6-digit 2FA code** from authenticator app
5. Approve the DNS changes Entri shows
6. Repeat steps 1–5 for **`www.kinglobal.net`** (or Entri may handle both at once — confirm in the success summary)
7. Wait 5–30 min for SSL provisioning (status flips "Update needed" → green checkmark)
8. Webflow Designer → top-right **Publish** → tick `kinglobal.net` + `www.kinglobal.net` → **Publish to selected domains**

---

## Path B — Manual records in GoDaddy

Use only if Quick Update fails. Webflow's manual panel currently shows these exact values for **`kinglobal.net`**:

### Records for root domain (`kinglobal.net`)

| Type | Name | Value | TTL |
|------|------|-------|-----|
| TXT | `_webflow` | `one-time-verification=95b252c7-6f51-4bdf-8471-46f4de90a064` | 1 hour |
| A | `@` | `198.202.211.1` | 1 hour |

The TXT record is a one-time ownership check. After Webflow verifies, you can delete it.

### Records for www (`www.kinglobal.net`)

Click **Verify domain** on the `www.kinglobal.net` row in Webflow Publishing to expand its panel. Webflow will show a CNAME record — copy the **exact value** it gives you. It will be something like:

| Type | Name | Value | TTL |
|------|------|-------|-----|
| CNAME | `www` | `cdn.webflow.com` *(confirm exact value from Webflow panel)* | 1 hour |

### Steps in GoDaddy

1. Sign in at https://dcc.godaddy.com/domains (needs 2FA code)
2. Find **`kinglobal.net`** in the domain list → **`⋯`** → **Manage DNS**
3. **Delete** any existing records that conflict:
   - Any `A` record with Name `@`
   - Any `CNAME` record with Name `www`
4. **Add** the TXT, A, and CNAME records above (click **Add New Record** for each)
5. **Save**
6. Wait ~10 min for DNS propagation
7. Back in Webflow → click **Verify domain** on each row → should flip to **Connected**
8. **Publish** the site in Webflow to `kinglobal.net` + `www.kinglobal.net`

---

## Verification (after DNS update)

Open Terminal (Cmd+Space → "Terminal") and paste:

```bash
dig kinglobal.net +short
dig www.kinglobal.net +short
```

**Expected output:**
- `kinglobal.net` → `198.202.211.1` (or `75.2.60.5` / `99.83.190.102` — all are valid Webflow IPs)
- `www.kinglobal.net` → `cdn.webflow.com.` followed by an IP

If nothing returns or you see an old IP, DNS hasn't propagated yet — wait another 10–15 min and retry.

---

## Known gotchas (learned from kin.net flow)

1. **404 immediately after DNS flips** — expected for up to 30 min. The page shows "Site Not Found" while SSL provisions and you republish. Not broken.
2. **Webflow status stuck on "Update needed"** even after DNS is correct — usually a Webflow verification lag. Wait 15 min and refresh. Don't add duplicate records.
3. **Don't manually add records on top of what Entri wrote** — that creates conflicts. Either Quick Update OR manual, not both.
4. **GoDaddy 2FA is needed** to sign in — code comes from whichever authenticator app set up the account (Google Authenticator / Authy / 1Password / Apple Passwords).
5. **Webflow Background Video on home** strips audio — unrelated to DNS but worth flagging: if the home page video needs sound after launch, use the HTML5 Embed approach (already done — script `homeherovideocontrol` is applied).

---

## After both domains are live

- Decide which is the **primary canonical** for kinglobal traffic:
  - Option 1: `kinglobal.net` redirects to `kin.net` (kin.net is the brand)
  - Option 2: `kinglobal.net` serves the same site at its own URL
  - Webflow handles this via the **Make default** button in Publishing — set whichever should be canonical, and Webflow auto-redirects the non-default versions to it.
- Update any external links pointing at kinglobal.net (LinkedIn, email signatures, press, etc.) to confirm they resolve.
- Add to SEO sitemap if separate canonical.

---

## If you get stuck

The whole flow is identical to what worked for kin.net 12 hours ago — same registrar, same Webflow site, same Entri integration. If Quick Update errors, screenshot the error and check:

1. Is the kinglobal.net domain spelled exactly right in Webflow? (typos like `kinlgobal.net` are silent killers)
2. Did GoDaddy 2FA succeed? (sometimes the popup times out — try again)
3. Is there an old hosting record at the registrar (e.g., from a previous Bluehost setup) blocking the new A record? Delete it first.

Once kin.net + kinglobal.net + their www variants all show **Connected · Active · Published**, the launch is complete.
