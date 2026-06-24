# KIN Investor Webflow Rebuild — Session Handoff (2026-05-13)

## Current status (2026-05-13)

**Build phase essentially complete.** All pages built, all case studies built, all slugs renamed, contact page polished, transparent-blur Nav variant shipped. **Only remaining build work is the responsive pass on About / Corporate Information / About Mission.**

After that: CSS cleanup tracked in `phase2/css-cleanup.md` (non-blocking), then launch via Client Billing handoff to KIN. Karen's Workspace is moving to Freelancer tier (renamed "Manic") in this session.

## Context

You are helping Karen rebuild the KIN Investor site (Singapore event-tourism agency, SGX-listed) on Webflow. Site: https://kin-investor.webflow.io. Site ID: `69f5ade35adb69631fc0fa1a`.

**Read all the project memories before touching anything.** Especially: `working_style.md`, `project_webflow_nav_build.md`, `feedback_webflow_section_class_convention.md`, `feedback_webflow_flex_over_grid.md`, `feedback_webflow_scroll_interactions_page_scoped.md`, `feedback_webflow_first_chip_locked.md`, `feedback_webflow_mcp_designer_round_trip.md`, `feedback_webflow_custom_properties_whitelist.md`, `feedback_real_content_during_build.md`, `feedback_webflow_ui_directions.md`. The role brief is in `phase2/md/webflow-expert-role-prompt.md`.

Local source of truth: `localhost:3001` (served from `/Users/karenhuang/Documents/Projects/KIN/Claude/phase2/2026/`). Latest live content is at `https://www.kin.net/*.html`. **Use the live site for latest content** unless Karen says otherwise.

## Pages built (in Webflow)

| Page | Slug (needs rename) | State |
|---|---|---|
| Corporate Information 2 | `corporate-information` | 7-instance Profile component populated manually by Karen with real bios. ⚠ Cite element exists per master; redundant for some entries |
| About | `about` | Hero, intro, mission grid, milestones embed, custom nav-overlay scroll behavior |
| About Mission | `about-mission` | Built |
| Services | `services` | 3-card grid, dark register flipped 2026-05-11 |
| **Services EDM** | `untitled-2` → rename `services-edm` | Built 2026-05-11. Dark register. 37 new edm-* styles |
| **Services Design Build** | `untitled-4` → rename `services-design-build` | Built 2026-05-11. Karen cleaned: swapped `edm-section`/`--ruled` → `Section` class on Section elements, removed `eyebrow--white`, swapped Navigation Overlay → regular Nav. 6 new styles (process atoms + `dbu-cap__grid`) |
| **Services Curate** | `untitled-5` → rename `services-curate` | Built 2026-05-11. 0 new styles — full primitive reuse. Atmospheric band uses `home-experiences.webp` placeholder (local references `card-experiences.webp` which isn't uploaded) |
| **Contact** | `untitled-6` → rename `contact` | Built 2026-05-11. Light register. 18 new contact-* styles. Form uses Webflow native FormForm/FormTextInput. ⚠ Form inputs need attribute config (name/placeholder/type/required) per row. Map slot is empty HtmlEmbed — Karen pastes iframe code |
| Portfolio | `portfolio` | CMS-driven card list. Option A "Editorial Slide-Up" hover landed 2026-05-11 via Custom Code in Page Settings → head. Title + sub hidden at rest, fade in on hover; image scales 1.06; overlay deepens; title goes cyan |
| IR / SGX / CI / Media Coverage / Mgmt Team / Style Guide / Sandbox / Nav | various | Pre-existing |

## Conventions established (use on all new pages)

1. **`Section` class** (Title Case) on every `<Section>` element EXCEPT hero + feature image. Provides vertical-padding base. Replaces deprecated `edm-section` / `edm-section--ruled` pattern. The `edm-section*` styles still exist in stylesheet but aren't applied to current pages.

2. **Bare `eyebrow`** class only. `eyebrow--white` combo dropped. Eyebrow colour comes from Body Variable Mode (Dark mode → muted-white via theme tokens).

3. **Service sub-pages use the regular `Nav` component** (id `ec12171d-684d-6d86-a5e6-abe41debef28`), NOT the `Navigation Overlay` component (id `b1fd1892-...`). Navigation Overlay is reserved for pages with full-bleed dark hero images (About).

4. **Flex over grid for new layout classes.** Karen's noted grid layouts don't render reliably in Designer canvas. Use flexbox with flex-wrap + per-child flex-basis for multi-column rows. Existing grid classes stay until next touch.

5. **`edm-*` prefix on shared service-page styles** (despite the name, used across EDM/D&B/Curate). Karen may rename to a generic `svc-*` or `proc-*`/`cap-*` later. Don't refactor preemptively.

6. **Body Variable Mode**:
   - Dark register pages (EDM, D&B, Curate, About, dark heroes): Body → Dark mode
   - Light register pages (Contact, IR, CI, SGX, MC, Mgmt Team, About Mission): Body → Base (Light) mode — the default, do not flip
   - **Set on Body element only**, never on a class chip (mode-pin pollution is unrecoverable; see `feedback_webflow_modes.md`)

7. **Page slugs auto-generate as `untitled-N`** when created via API and don't get the page name. Manual rename in Pages panel after creation. (Webflow's slug auto-generation collides on `untitled-3` despite different names — page still gets created.)

## Critical patterns learned this session

**Webflow MCP can't do:**
- Per-instance component property overrides on primary locale (confirmed via Webflow AI). Only Designer UI sets these. Karen fills text manually.
- Set text on `FormTextInput`/`FormButton` (not text-supporting types). Configure via attributes in Designer Settings.
- Nested inline emphasis inside Paragraphs (no `<em>` or `<strong>` mid-text via API). Karen styles manually post-build.
- Scroll Interactions that propagate cross-page (page-scoped, see `feedback_webflow_scroll_interactions_page_scoped.md`).
- Parent-hover cascade like `.parent:hover .child` — only element-level hover. Use Custom Code.

**Reliable patterns:**
- **Chunk element_builder calls** to ~4 nested levels max. 8+ levels fail with "Missing element" transactional rollback.
- **Combo classes** in `set_style` need a single space-joined string: `["eyebrow eyebrow--sm eyebrow--navy"]` not `["eyebrow", "eyebrow--sm", "eyebrow--navy"]`.
- **API-written clamp/calc values** show `0` or blank in Designer's number fields. CSS still renders. Press `=` in the field to re-register via Custom value popup for UI editability. Cosmetic — not a real bug.
- **Webflow scroll triggers on fixed-position elements** are broken. Use Custom Code (script + CSS in Footer Code) for fixed-nav scroll behaviour.
- **Custom Code DOES preview** in Webflow's Preview mode (▶ play button toggle), not the Design tab canvas.
- **Page-level Custom Code** in Page Settings → Inside `<head>` is the right home for page-specific cascades (e.g., portfolio card hover).
- **Webflow scroll Interactions** on COMPONENT MASTERS may propagate to instances but break on `position: fixed`. Real-Div as carrier > pseudo-element (Webflow's `w-nav` defaults fight `::before`).
- **Page state drifts** mid-build. Verify `get_current_page` before any insert. The bogus "slug conflict" error from `create_page` is misleading — page usually gets created anyway, check `list_pages`.

## Open work

### ✅ Slug renames — DONE (2026-05-13)
All four `untitled-N` pages renamed: `services-edm`, `services-design-build`, `services-curate`, `contact`.

### ✅ Contact page polish — DONE (2026-05-13)
Map iframe pasted, mailto:/tel: links wired, form input attributes (name/placeholder/type/required) configured, Submit button value set. Form notification to `info@kin.net` still pending paid Site Plan (unlocks at Client Billing handoff).

### Typography consolidation (Karen doing manually, as she touches each page)
**Tier 1 — consolidate 4 hero-display classes → one `h-display`** (or matching her local convention):
- `ci-about-hero-title` (clamp 40/96)
- `portfolio-hero-title` (clamp 40/80)
- `ci-svc-hero-title` (clamp 40/80, 14ch)
- `ci-mv-split-heading` (clamp 40/80, literal ls — should be variable)

**Tier 2 — three identical-clamp section heads (`28px, 3vw, 40px`)** with different weights → base + weight modifiers:
- `ci-about-timeline-heading` (500)
- `ci-about-mission-heading` (500)
- `ci-profile-name` (400)

Full inventory: `project_kin_investor_class_punch_list.md`.

### Layout variables
- Convert 5 rem-based vars → px (Karen's preference; conversion list in last transcript).
- Rename `space-section-y med` → `space-section-y-md` (hyphenate).
- Add `space-gutter-md` = `clamp(24px, 3vw, 48px)` to fill the gap between `space-gutter-sm` and `space-gutter`.

### Responsive pass
- About, CI 2, About Mission still to do (Services done).
- EDM page may still have stale `edm-section`/`--ruled` from earlier build — Karen's only done the swap on D&B / Curate.

### About page `ci-*` class renames
Extend Karen's earlier pattern: `ci-about-mission` → `mission-grid`, `ci-about-mission-heading` → `mission-heading`, etc. Mission-heading and timeline-heading are visually identical (clamp 28-40 weight 500) — could share one class.

### ✅ Pages still to build — DONE (2026-05-13)
- **Management Team** — built (slug `management-team`).
- **Portfolio case studies** — all 11 built (WTT, BLAST, BXL, FIBA ICC, FIDE, HSBC SVNS, IBC, KIN WAC2025, ATP250, Tour de France, World vTKD2024). WTT structural template duplicated and content-swapped per case.

### Smaller polish items
- **About Mission SVG filter embed** — paste the accreditation greyscale filter snippet into Designer Embed block (snippet in prior handover).
- **About page ci-* renames** (deferred).
- **`card-experiences.webp` upload** — replace placeholder home-experiences on Curate atmospheric band.
- **White-line border investigation on images** — appears in both Chrome + Safari; CSS audit clean; image assets look clean on inspection. Inconsistent across pages. Deferred. Possibly browser rendering artifact at certain viewport widths, possibly faint baked-in white edge in some webps.

### Open `nav-overlay` cleanup (per `project_webflow_nav_build.md`)
- Polluted `nav bar` graveyard class still in stylesheet (renamed to `nav-overlay` but original lingers as inert cruft). No action needed unless Webflow ships a way to delete legacy classes.
- Inline `nav-overlay` is duplicated on About + Home. When touched again, componentize as `Nav — Overlay` so scroll/hover Interactions can live on a master and propagate.

## How to work with Karen

(Repeats from `working_style.md` — re-emphasising what matters most for this work.)

- **She's not a developer.** UI directions must be exact: panel label, on-screen location, exact field name, exact value to type. No "should be there."
- **Concise responses.** No trailing summaries. No re-explaining what just happened. **Don't over-think simple yes/no questions** — if she asks "can I just rename X?", the answer is usually one word.
- **When she says "do it", do it.** Don't second-guess.
- **When stuck, hand off.** 5+ failed API attempts on the same problem = stop, suggest manual.
- **She works in parallel.** She manually swaps classes / variables / colours / text content while you work. **Do NOT revert her changes.** If a property looks different from what you wrote, she swapped it deliberately.
- **Real content during build.** Grep the local HTML or fetch live kin.net before touching `element_builder`. Placeholders are a sign you screwed up.
- **Refer to elements by class name** (e.g. `nav-overlay`, `edm-pillar__title`), not generic descriptions ("the new div", "the parent block").
- **Anticipate Designer disconnects.** Share the relaunch link proactively: https://kin-investor.design.webflow.com?app=dc8209c65e3ec02254d15275ca056539c89f6d15741893a0adf29ad6f381eb99. Don't ask "is Designer running?" — try, fail, share the link.
- **Service sub-page builds are now ~10 MCP calls.** The system is amortized.

## Today's wins (build efficiency arc)
- Services EDM: 19 MCP calls, 37 new styles
- Services Design Build: 12 calls, 6 new styles
- Services Curate: 10 calls, **0** new styles
- Contact: 10 calls, 18 new styles (light register required new palette)
- Portfolio hover: 1 MCP call + 1 Custom Code paste

End of handoff. Be opinionated. Be brief. Don't over-think yes/no questions. Hand off when stuck.
