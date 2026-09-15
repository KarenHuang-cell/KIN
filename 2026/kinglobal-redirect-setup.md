# kinglobal.net → kin.net redirect — as-built record

**Date:** 2026-06-09
**Status:** ✅ Live & verified
**Site:** KIN Global (Webflow) · siteId `69f5ade35adb69631fc0fa1a`

---

## Summary

`kinglobal.net` and `www.kinglobal.net` now **301-redirect to `https://www.kin.net/`**. They were published to the KIN Global Webflow site via the Webflow **Data API** — not the Designer Publish button (see why below). `kin.net` was deliberately left untouched.

## Verified state (2026-06-09)

| URL | Result |
|---|---|
| `https://kinglobal.net/` | 301 → `https://www.kin.net/` (200) |
| `https://www.kinglobal.net/` | 301 → `https://www.kin.net/` (200) |
| `https://kin.net/` | 301 → `https://www.kin.net/` — unchanged |
| `https://www.kin.net/` | 200 — canonical (Default), unchanged |

All hops over HTTPS with valid SSL. Served by Webflow (same site as kin.net).

## Why the API, not the Publish button

The Designer's **Publish** modal would not list `kinglobal.net` / `www.kinglobal.net`. They were **Connected** with **SSL Active**, but had **never been published** — and Webflow doesn't surface a never-published, non-default custom domain in that modal (confirmed via the API: `fullSiteCompiledAt: null` for both). A hard refresh didn't help. So the publish was done directly through the Data API.

## What was actually run

Webflow Data API — `publish_site`:

- **site_id:** `69f5ade35adb69631fc0fa1a`
- **customDomains:**
  - `kinglobal.net` — `6a277df3b782785106ab8b89`
  - `www.kinglobal.net` — `6a277df5b782785106ab8b90`
- **publishToWebflowSubdomain:** `false`
- `kin.net` (`6a17a1d59918952109a9e0d6`) and `www.kin.net` (`6a17a1db9918952109a9e0e5`) **excluded on purpose.**

The redirect is **not** a separate setting. It's Webflow's **default-domain** behaviour: any connected, published domain that isn't the Default gets a 301 to the Default (`www.kin.net`). Publishing the kinglobal pair while `www.kin.net` stayed Default is what created `kinglobal.net → kin.net`.

## Guardrails ⚠️

- **`www.kin.net` must stay the Default domain.** Do **not** click "Make default" on a kinglobal row in Site Settings → Publishing. With kin.net live, that flips the direction — kin.net would start redirecting to kinglobal.
- **kin.net content was not republished.** There were Designer edits newer than the 5 June publish; they were intentionally left unpublished. kinglobal only redirects, so nothing is served there.
- **DNS / email:** GoDaddy records for kinglobal.net are A `@` → `198.202.211.1`, `www` CNAME → `cdn.webflow.com`, plus the `_webflow` ownership TXT. Leave the **MX (email)** records alone.

## Re-verify any time

```bash
for u in https://kinglobal.net/ https://www.kinglobal.net/ https://kin.net/ https://www.kin.net/; do
  echo "== $u =="
  curl -sSIL "$u" -o /dev/null -w "%{http_code}  final: %{url_effective}\n"
done
```

Expect `kinglobal.net` and `www.kinglobal.net` to finish at `https://www.kin.net/`.

## Changing it later

- **Push the newer Designer edits live to kin.net:** Designer → Publish → `www.kin.net` (or the API including the kin.net domain IDs). Ships everything currently saved.
- **Make kinglobal serve its own page instead of redirecting:** not possible while a single Default funnels everything to it — would need a separate setup. Ask first.
- **Move the redirect off Webflow (GoDaddy forwarding):** possible but more fragile (SSL re-provisioning, GoDaddy https-forwarding quirks). Not recommended while this works.

## Access note

kinglobal.net is on the **client's GoDaddy** account (authenticator 2FA on their device). For future DNS changes, use GoDaddy **Delegate Access** rather than borrowing the login.

**Related:** `kinglobal-dns-pointing.md`
