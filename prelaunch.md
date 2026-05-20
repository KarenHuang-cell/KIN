# Pre-launch checklist — KIN Global Webflow

Tasks to complete the day the site is published to its final domain `www.kin.net`.

---

## DNS migration — point kin.net at Webflow

**Why:** The site lives on `kin-investor.webflow.io` (staging). To make `kin.net` and `www.kin.net` serve the Webflow site, KIN's DNS records (currently at Bluehost, or wherever the domain is hosted) need to point at Webflow's IP addresses.

**Heads-up:** Webflow is mid-migration to Cloudflare hosting infrastructure (banner in Designer flagged this). The IP addresses Webflow gives you may be the new Cloudflare ones, not the legacy values you'd find in older guides. Always use the addresses Webflow shows you *today* in Site Settings.

### Steps

1. In Webflow → **Site Settings → Publishing → Production** → click **Add Custom Domain** → enter `kin.net` (and also `www.kin.net` as a second domain).
2. Webflow shows the required DNS records — typically:
   - An **A record** on the root domain (`kin.net`) pointing to Webflow's IP
   - A **CNAME record** on `www` pointing to `proxy-ssl.webflow.com` (or similar)
3. Log into the DNS host (Bluehost or whoever holds kin.net DNS) → DNS / Zone Editor.
4. Add the A record and CNAME record exactly as Webflow specified. Delete any conflicting old A records on the root.
5. Back in Webflow, click **Check status** next to each domain. DNS can take a few minutes to a few hours to propagate.
6. Once both domains show ✓, set `www.kin.net` as the **primary domain** (or whichever KIN prefers).
7. **Publish** the site to the new custom domain (top-right Publish button → tick the custom domain checkboxes).
8. Visit `https://www.kin.net` in a browser — should serve the Webflow site with valid HTTPS.

### Common pitfalls

- **HTTPS not working immediately.** Webflow auto-provisions SSL via Let's Encrypt; this can take a few extra minutes after DNS resolves.
- **Old A records still pointing at Bluehost hosting.** Delete them. The DNS host can't have two A records on the same hostname pointing at different IPs.
- **www and root point at different places.** Pick one as primary; redirect the other. Webflow handles the redirect for you once both domains are added.

---

## Google site verification

**Why:** Google Search Console is Google's free dashboard. It shows which pages are indexed, what queries bring traffic, and what's broken. Adding the verification ID is how Google confirms we own the domain before granting access.

**Why we wait:** The Webflow staging URL `kin-investor.webflow.io` is blocked from indexing (correctly). Google can't verify a blocked domain. We must wait until the site is live on `www.kin.net`.

### Steps

1. Go to [search.google.com/search-console](https://search.google.com/search-console)
2. Click **Add property** → enter `https://www.kin.net`
3. Choose the **HTML tag** verification method (the one that gives you a meta tag)
4. From the meta tag Google shows, copy **only the long random ID** from inside the `content="..."` quotes
   - Example: `<meta name="google-site-verification" content="x88atPHmzG1G2FBivU1bk-..." />` → copy just `x88atPHmzG1G2FBivU1bk-...`
5. In Webflow → **Site Settings** → **SEO** → paste the ID into the **Google site verification ID** field → **Save**
6. **Publish** the Webflow site so the meta tag goes live
7. Back in Google Search Console, click **Verify**
8. Once verified, submit the sitemap (see next task)

---

## Submit sitemap to Google

**Why:** Webflow auto-generates `sitemap.xml` on every publish (because Auto-generate sitemap is ON in Site Settings → SEO). Google won't *find* it automatically though — we have to point Search Console at it so Google starts crawling efficiently.

**Where Webflow publishes it:** `https://www.kin.net/sitemap.xml`

### Steps

1. After the site is live on `www.kin.net` and Google site verification is complete (above), open [Google Search Console](https://search.google.com/search-console)
2. Left sidebar → **Sitemaps**
3. In the **Add a new sitemap** field, paste: `sitemap.xml` (just the filename — Search Console already knows your domain)
4. Click **Submit**
5. Status should show **Success** within a few minutes. If it shows "Couldn't fetch", confirm the URL loads in a browser and try again.

### Quick sanity check before submitting

Visit `https://www.kin.net/sitemap.xml` in a browser. You should see an XML list of all your public pages — Home, About, Services, Portfolio, Investor Relations, etc. Style Guide should *not* appear (since its Sitemap indexing is Off).

---

## Nice to have — dedicated dark-mode favicon

**Status:** Not blocking. The current dark-mode favicon (navy circle + white "k") nearly disappears on dark browser tabs because the navy blends into the tab background. Small cosmetic issue; affects only users who browse with dark-mode tabs and look at the tab favicon.

**Fix when there's a moment:**

1. Create a dark-mode variant of the icon — recommended: **white circle with navy "k"** (the inverse of the current light version)
2. In Webflow Designer → **Site Settings → General → Favicon and Webclip**
3. Click to open the **Update icons** dialog
4. **Uncheck** *Favicon — light* and *Webclip* — keep only **Favicon — dark** checked
5. Upload the new dark variant → **Apply**
6. Publish

Only the dark variant updates; the light favicon and webclip stay as they are.

---

## Post-launch SEO polish — Person schemas

**Why:** Each director and executive officer gets their own `Person` JSON-LD block. Helps Google's knowledge graph associate them with KIN Global — so when someone searches a director's name, Google can show their role and link back to KIN.

### Prep — upload headshots to Webflow Assets

The descriptions below reference `[CDN_URL_...]` placeholders. Replace each with the actual Webflow CDN URL after uploading the headshot.

1. Designer → **Assets** panel → **Upload assets**
2. From `/Users/karenhuang/Documents/Projects/KIN/Claude/2026/assets/images/`, upload all 9 mgmt headshots:
   - `mgmt-Ko_Chee_Wah_2026.webp`
   - `mgmt-Vincent_2026.webp`
   - `mgmt-Clement_2026.webp`
   - `mgmt-adrian_2026.webp`
   - `mgmt-Raymond.webp`
   - `mgmt-YewTong.webp`
   - `mgmt-BOD_Leong.webp`
   - `mgmt-BOD_Daisy.webp`
   - `mgmt-BOD_Steven.webp`
3. Click each file in the Assets panel → copy the CDN URL (looks like `https://cdn.prod.website-files.com/69f5ade35adb69631fc0fa1a/.../mgmt-X.webp`)
4. Paste into the corresponding placeholder below

### Block A — Management Team page

Paste **all six blocks** into **Management Team → Page Settings → Custom Code → Inside `<head>` tag**. Multiple `<script>` tags is fine — Google reads them all.

```html
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Person",
  "name": "Ko Chee Wah",
  "jobTitle": "Executive Chairman & Co-Founder",
  "image": "[CDN_URL_KoCheeWah]",
  "description": "Executive Chairman and co-founder of KIN Global. Over 30 years of experience in the MICE industry, including Universal Studios Singapore, Nanjing 2014 Youth Olympic Games, and SEA Games 2015.",
  "worksFor": {"@type": "Organization", "name": "KIN Global", "url": "https://www.kin.net/"}
}
</script>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Person",
  "name": "Vincent Chai",
  "jobTitle": "Executive Director & Chief Executive Officer",
  "image": "[CDN_URL_VincentChai]",
  "description": "Chief Executive Officer and co-founder of KIN Global, responsible for the company's overall vision and strategic direction. Over 20 years of experience in events and experience creation.",
  "worksFor": {"@type": "Organization", "name": "KIN Global", "url": "https://www.kin.net/"}
}
</script>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Person",
  "name": "Clement Tan",
  "jobTitle": "Chief Operating Officer",
  "image": "[CDN_URL_ClementTan]",
  "description": "Chief Operating Officer and co-founder of KIN Global, overseeing project operations and manpower across the Group. Experience includes Nanjing 2014 Youth Olympic Games and SEA Games 2015.",
  "worksFor": {"@type": "Organization", "name": "KIN Global", "url": "https://www.kin.net/"}
}
</script>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Person",
  "name": "Adrian Tan",
  "jobTitle": "Chief Commercial Officer",
  "image": "[CDN_URL_AdrianTan]",
  "description": "Chief Commercial Officer and co-founder of KIN Global, responsible for sales, commercial strategy, partnerships, and marketing. Almost 20 years of experience in experience creation and event presentation.",
  "worksFor": {"@type": "Organization", "name": "KIN Global", "url": "https://www.kin.net/"}
}
</script>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Person",
  "name": "Raymond Lee",
  "jobTitle": "Group Financial Controller",
  "image": "[CDN_URL_RaymondLee]",
  "description": "Group Financial Controller at KIN Global, responsible for financial reporting, audit, treasury, tax, M&A, and internal control. Member of CPA Australia.",
  "worksFor": {"@type": "Organization", "name": "KIN Global", "url": "https://www.kin.net/"}
}
</script>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Person",
  "name": "Chan Yew Tong",
  "jobTitle": "Director, Kin D+B Pte Ltd",
  "image": "[CDN_URL_ChanYewTong]",
  "description": "Director of Kin D+B Pte Ltd, KIN Global's design and build subsidiary. Over 27 years in themed entertainment, including Hong Kong Disneyland's Land of Arendelle and Universal Studios Singapore.",
  "worksFor": {"@type": "Organization", "name": "KIN Global", "url": "https://www.kin.net/"}
}
</script>
```

### Block B — Corporate Information page

Paste **all eight blocks** into **Corporate Information → Page Settings → Custom Code → Inside `<head>` tag**.

```html
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Person",
  "name": "Ko Chee Wah",
  "jobTitle": "Executive Chairman & Co-Founder",
  "image": "[CDN_URL_KoCheeWah]",
  "description": "Executive Chairman and co-founder of KIN Global. Over 30 years of experience in the MICE industry, including Universal Studios Singapore, Nanjing 2014 Youth Olympic Games, and SEA Games 2015.",
  "worksFor": {"@type": "Organization", "name": "KIN Global", "url": "https://www.kin.net/"}
}
</script>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Person",
  "name": "Vincent Chai",
  "jobTitle": "Executive Director & Chief Executive Officer",
  "image": "[CDN_URL_VincentChai]",
  "description": "Chief Executive Officer and co-founder of KIN Global, responsible for the company's overall vision and strategic direction. Over 20 years of experience in events and experience creation.",
  "worksFor": {"@type": "Organization", "name": "KIN Global", "url": "https://www.kin.net/"}
}
</script>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Person",
  "name": "Leong Yue Kheong",
  "jobTitle": "Lead Independent Director",
  "image": "[CDN_URL_LeongYueKheong]",
  "description": "Lead Independent Director of KIN Global. Founder of 3 Quensz; former Deputy CEO (Development) at Mandai Park Development and Assistant Chief Executive of the Singapore Tourism Board.",
  "worksFor": {"@type": "Organization", "name": "KIN Global", "url": "https://www.kin.net/"}
}
</script>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Person",
  "name": "Ong Lizhen, Daisy",
  "jobTitle": "Independent Director",
  "image": "[CDN_URL_OngDaisy]",
  "description": "Independent Director of KIN Global. Chief Financial Officer of Fu Yu Corporation Limited and Independent Director of HG Metal Manufacturing Limited (SGX Mainboard).",
  "worksFor": {"@type": "Organization", "name": "KIN Global", "url": "https://www.kin.net/"}
}
</script>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Person",
  "name": "Lim Jun Xiong, Steven",
  "jobTitle": "Independent Director",
  "image": "[CDN_URL_LimSteven]",
  "description": "Independent Director of KIN Global. Over 30 years in financial, trust, and wealth management; former CEO of SG Trust (Asia) Ltd and Managing Director of Global Wealth Solutions at HSBC Investment Bank Asia.",
  "worksFor": {"@type": "Organization", "name": "KIN Global", "url": "https://www.kin.net/"}
}
</script>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Person",
  "name": "Clement Tan",
  "jobTitle": "Chief Operating Officer",
  "image": "[CDN_URL_ClementTan]",
  "description": "Chief Operating Officer and co-founder of KIN Global, overseeing project operations and manpower across the Group. Experience includes Nanjing 2014 Youth Olympic Games and SEA Games 2015.",
  "worksFor": {"@type": "Organization", "name": "KIN Global", "url": "https://www.kin.net/"}
}
</script>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Person",
  "name": "Adrian Tan",
  "jobTitle": "Chief Commercial Officer",
  "image": "[CDN_URL_AdrianTan]",
  "description": "Chief Commercial Officer and co-founder of KIN Global, responsible for sales, commercial strategy, partnerships, and marketing. Almost 20 years of experience in experience creation and event presentation.",
  "worksFor": {"@type": "Organization", "name": "KIN Global", "url": "https://www.kin.net/"}
}
</script>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Person",
  "name": "Raymond Lee",
  "jobTitle": "Group Financial Controller",
  "image": "[CDN_URL_RaymondLee]",
  "description": "Group Financial Controller at KIN Global, responsible for financial reporting, audit, treasury, tax, M&A, and internal control. Member of CPA Australia.",
  "worksFor": {"@type": "Organization", "name": "KIN Global", "url": "https://www.kin.net/"}
}
</script>
```

### After pasting — validate

Run [validator.schema.org](https://validator.schema.org/) on each page URL once published. You should see all blocks parsed cleanly with `@type: Person`.

### Optional polish

If/when LinkedIn profile URLs are available for each person, add a `sameAs` field to each block:

```json
"sameAs": ["https://www.linkedin.com/in/their-handle/"]
```

This links the schema Person entity to their public LinkedIn profile, strengthening the knowledge graph association.

---

## Pre-launch — port the JSON-LD Organization schema to Webflow

**Why:** The static kin.net site has a JSON-LD Organization block on `index.html` (Google Knowledge Panel data — legal name, logo, address, phone, social profiles). When Webflow takes over `www.kin.net`, that block disappears. Need to recreate it in Webflow before launch.

### Steps

1. In Webflow Designer → **Pages** → click **Home**
2. Click cog icon → **Page Settings**
3. Scroll to **Custom Code** section
4. In the **"Inside `<head>` tag"** box, paste the block below
5. **Save** → **Publish**

```html
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "KIN Global",
  "legalName": "Kin Global Limited",
  "alternateName": "Kin Productions",
  "url": "https://www.kin.net/",
  "logo": "https://www.kin.net/assets/images/kin-logo-navy.svg",
  "description": "KIN engineers world-class sporting events, immersive exhibitions, and cultural experiences that turn cities into global destinations.",
  "foundingDate": "2017",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "51 Tai Seng Avenue #04-06, Pixel Red, Lobby C",
    "addressLocality": "Singapore",
    "postalCode": "533941",
    "addressCountry": "SG"
  },
  "contactPoint": [{
    "@type": "ContactPoint",
    "telephone": "+65-6386-9958",
    "contactType": "customer service",
    "email": "info@kin.net",
    "areaServed": "SG",
    "availableLanguage": "English"
  }],
  "sameAs": [
    "https://www.linkedin.com/company/kin-productions-pte-ltd/",
    "https://www.instagram.com/kindotnet/",
    "https://www.facebook.com/kinproductionspteltd/"
  ]
}
</script>
```

⚠️ **The `logo` URL** points to `/assets/images/kin-logo-navy.svg`. That path exists on the static kin.net site but **may not exist** on the Webflow site. Before launch, either:
- Upload the navy logo SVG to Webflow assets and update the URL, OR
- Change `logo` to point at the favicon CDN URL (Webflow generates one after favicon upload)

---

## Post-launch verification

Run these checks within an hour of going live on `www.kin.net`. Copy-paste into Terminal.

### 1. robots.txt is no longer blocking everything

```bash
curl -s https://www.kin.net/robots.txt
```

**Expected:** Should NOT contain `Disallow: /`. Should contain a `Sitemap:` line pointing to `https://www.kin.net/sitemap.xml`. If you still see `Disallow: /`, the Webflow subdomain indexing toggle hasn't been turned on for the custom domain.

### 2. Sitemap.xml exists and lists public pages

```bash
curl -s https://www.kin.net/sitemap.xml | grep -oE '<loc>[^<]+</loc>'
```

**Expected:** A list of URLs — Home, About, Services, Portfolio, Investor Relations, etc. Style Guide should NOT appear. Stub CMS detail pages (`/services/*`, `/media-coverage/*`) should NOT appear if their items don't render real pages.

### 3. Page titles + canonical on key pages

```bash
for p in "" "about" "services" "portfolio" "investor-relations" "contact"; do
  echo "── /$p"
  curl -s "https://www.kin.net/$p" | grep -oE '<(title>[^<]+|link rel="canonical"[^>]+|meta[^>]+og:image[^>]+)>' | head -3
  echo
done
```

**Expected:** Each page returns its proper title (e.g. *About KIN Global | Event Tourism Agency, Singapore*), canonical pointing to `https://www.kin.net/[slug]`, and `og:image` pointing to the OG_image asset on Webflow's CDN.

### 4. Portfolio dynamic SEO is rendering

```bash
curl -s "https://www.kin.net/portfolio/world-aquatics-championship-case-study" | grep -oE '<(title>[^<]+|meta[^>]+name="description"[^>]+)>'
```

**Expected:** Title and description contain real CMS field values (Name, Client, Location) — not literal `[Client]` text.

### 5. Favicon is the KIN navy "k", not Webflow's default

```bash
curl -s https://www.kin.net/ | grep -oE '<link[^>]+(icon|apple-touch)[^>]+>'
```

**Expected:** `href` URLs pointing at Webflow's CDN with file names you uploaded — *not* `webflow.com/img/favicon.ico`.

### 6. JSON-LD Organization schema is present (homepage)

```bash
curl -s https://www.kin.net/ | grep -A 2 'application/ld+json'
```

**Expected:** Shows the start of the JSON-LD block. Validate fully at [validator.schema.org](https://validator.schema.org/) by pasting `https://www.kin.net/`.

### 7. Social preview cards work

Visit each of these and paste `https://www.kin.net/`:
- [developers.facebook.com/tools/debug](https://developers.facebook.com/tools/debug)
- [linkedin.com/post-inspector](https://www.linkedin.com/post-inspector/)

**Expected:** Each shows the Supertrees OG image, the KIN Global title, and the description. If LinkedIn still shows cached old content, click "Inspect" / "Scrape Again".
