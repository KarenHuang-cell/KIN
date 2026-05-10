# KIN Investor Webflow Rebuild — Session Handoff

## Context

You are helping Karen rebuild the KIN Investor site (a Singapore event-tourism agency, SGX-listed) on Webflow. Site: https://kin-investor.webflow.io. Site ID: `69f5ade35adb69631fc0fa1a`.

The local source of truth lives at `localhost:3001` (served from `/Users/karenhuang/Documents/Projects/KIN/Claude/phase2/2026/`). The Webflow rebuild migrates each page faithfully, with permission to improve where the local has obvious quirks.

**You have access to it via Bash and WebFetch.** Don't claim "I can't see localhost" — that's wrong. You can.

The role you're playing is "Senior Web Designer → Webflow Developer" — opinionated about typography, spacing, and editorial restraint. The previous Claude was lazy, had no design opinions, and used `whtml_builder` (raw HTML+CSS strings) which created phantom CSS Designer couldn't surface or edit. Don't repeat that. Use **typed Webflow primitives only**: `element_builder`, `style_tool`, `component_builder`. Avoid `whtml_builder` entirely.

Load the `/webflow` skill or read `phase2/md/webflow-expert-role-prompt.md` for the role brief.

## What's been built this session (2026-05-10)

**4 pages on Webflow:**

1. **`Corporate Information 2`** at `/corporate-information-2` — hero with TOC, 2 body section shells, 1 clean Profile card (Ko Chee Wah). 7 more Profile cards pending.
2. **`About Mission`** at `/about-mission` — hero (mv-split flex), Values 4-col grid, Accreditations with 7 bound logos. SVG filter embed code provided but Karen needs to paste.
3. **`About`** at `/about` — full-viewport hero (image slot empty), intro panel, mission asymmetric grid, milestone timeline (Karen pasted the interactive embed).
4. **`Services`** at `/services` — hero, feature image slot, 3-discipline card grid. Built LIGHT but **must be DARK register** (canonical uses `nav.html` dark include — see Open work).

All `ci-` prefixed classes Karen owns. System classes (`Section`, `shrinkwrap`, `eyebrow`, `eyebrow--navy`, `subhead-rule`) reused where possible.

## Open work

**Tomorrow's first move:** the **Profile component lesson**. Karen is hesitant about Webflow components (got burned by text-on-instance editing the master). Walk her through: (1) what the component definition vs instance owns, (2) text content as a per-instance override (use Component Properties panel, not direct edit), (3) how to safely populate 7 more directors after she converts Ko's card.

**Pending fixes & polish (in roughly this order):**

1. **Services page → Dark mode.** Click Body element, Style panel → Variable Modes → Theme = Dark. Then check eyebrow contrast; if still grey-on-black, add a `ci-svc-hero-eyebrow` override class with white colour.
2. **About hero image** — upload `hero-bg.webp` to assets, bind to the empty Image slot inside `ci-about-hero`.
3. **Services icons + hero** — Karen uploads 3 SVGs from `phase2/2026/assets/icons/services/` (`icon-edm.svg`, `icon-designbuild.svg`, `icon-curate.svg`) and `hero-bg-services.webp`. Bind via `set_image_asset` API on the four empty slots.
4. **About Mission SVG filter embed** — Karen pastes the snippet into an Embed Code block. Filter rule already on `ci-accred-logo` — once embed is in, logos render dark navy.
5. **CI 2 Profile component** — convert Ko's card to component, then create 7 instances and populate (5 more Board: Vincent Chai, Leong Yue Kheong, Ong Lizhen Daisy, Lim Jun Xiong Steven; 3 Execs: Adrian Tan, Clement Tan, Raymond Lee). Content already extracted in earlier session — query the old `Corporate Information` page (id `69ff294314c5b8f4761d5a1b`) via `data_pages_tool > get_page_content` to get it again. **Note: 2 Board cards on the old page have placeholder content (Vincent + Leong have copy-pasted Ko bios).** Real bios still needed from client.
6. **System class audit + swaps** — Karen wants to lean on the system `Section`, `text-body`, and `text-body-muted` classes. Audit the four built pages and combo where appropriate (strip duplicated padding from ci-* sections so Section's `padding-bottom` isn't double-applied). Karen is creating a `text-body-400` modifier (the system base is weight 300; Karen prefers 400 for body). Wait for that class to land before combo-ing.
7. **Swap Corporate Information 2 → Corporate Information.** Once content + component land and you've verified everything, delete the old `Corporate Information` page and rename the new one.
8. **Responsive pass.** Defer until structural rebuild is mostly done. Build first instance of each duplicate-able structure (services sub-pages template, case study template) WITH full responsive, then duplicate. Webflow classes are global, so duplicates inherit responsive automatically. Don't do per-page responsive prematurely or you'll retro-fit.

**Pages still to build:**

- **Management Team** — uses Profile component (reuse from CI).
- **3 Services sub-pages** (`services_edm`, `services_curate`, `services_designbuild`) — dark register, similar shape. Build one fully responsive, duplicate × 2.
- **11 Portfolio case studies** (`port_*`) — share template. Build one, duplicate × 10.
- Possibly more (Privacy, Contact form, others).

## How to work with Karen

- **She's not a developer.** UI directions must be exact: name the panel, on-screen location, exact label, exact value. No "should be there." Skip keyboard shortcuts.
- **Concise responses.** No trailing summaries. No re-explaining what just happened.
- **When she says "do it", do it.** Don't second-guess or re-prompt.
- **When stuck, ask her.** Her own rule: "if anything is taking too long, ask me to do it instead of being stuck." 5+ failed API attempts on the same problem = stop, hand off. Manual UI work is often faster than fighting the API.
- **She works in parallel.** She's often manually swapping classes / variables / colours while you work. **Do NOT revert her changes.** If a property looks different from what you wrote, she swapped it deliberately.
- **Reference the canonical source.** For every page: grep the local HTML inline `<style>` block first, then `phase2/2026/assets/css/styles.css`. Don't improvise sizes or spacing from intuition. The local site isn't perfectly canonical (it has its own quirks), but it's the closest thing to a spec.
- **Real content during build, never dummy text.** If you don't have the content, run `data_pages_tool > get_page_content` on the source page first or grep the local HTML. "This is some text inside of a div block" is a sign you screwed up.
- **Register check first.** Read the local HTML's `loadInclude('nav-include', '...')` — `nav.html` = dark page (default), `nav-light.html` = light page (cream override). Default body has `background: var(--clr-bg)` + `html { background: #000 }`. Easy to miss — check before building.
- **`ci-` prefix for all new classes** so Karen can identify and delete unused ones later.

## Webflow MCP gotchas (every one of these bit me this session)

1. **`whtml_builder` is the enemy** — produces phantom CSS Designer can't see or edit. Never use it. Use `element_builder` with typed primitives.

2. **`element_builder` type `TextBlock` + `set_text` fails silently.** Element gets created but text doesn't apply. Use `Paragraph` for inline div text, `Heading` for h1–h6. Add `margin-top: 0; margin-bottom: 0` to neutralise default `<p>` margins on classes used for inline labels.

3. **`element_builder` type `DOM` + `settings: [{key: "textContent", ...}]` is REJECTED** ("Setting textContent is not applicable to this element"). textContent isn't a valid setting key for divs. Use Paragraph instead.

4. **Auto-class injection on `set_style`.** When you create a DivBlock and apply system class `shrinkwrap`, Webflow re-attaches `cs-hero__text` (Events Template hero overlay — `position: absolute, bottom: 6vh`). Same for Heading + `subhead-rule` re-attaching `heading-section--inst`. The "Remove class" option in Designer is **greyed out** for these auto-attached classes. `set_style` with `[]` clears, but the next non-empty `set_style` re-injects.
   - **Workaround 1 (preferred):** create fresh `ci-` prefixed class with identical properties (e.g. `ci-shrinkwrap` mirroring `shrinkwrap`'s max-width / margin-auto / padding-inline). Apply that. After build, Karen can manually swap `ci-shrinkwrap` → `shrinkwrap` via the Designer Style Selector chip — manual swap doesn't trigger the inject.
   - **Workaround 2:** `heading-section--inst` is harmless (typography only — no positioning), leave it as combo with `subhead-rule`.
   - **Diagnostic:** if a section's content overlaps the next section, suspect `cs-hero__text` on a child div.

5. **Gap properties REJECT variables via API.** `gap`, `column-gap`, `row-gap` all error with "does not support setting a variable of type length". They DO accept literal `clamp(36px, 6vw, 96px)`. The `grid-column-gap` / `grid-row-gap` longhands DO accept variables — but only via the Designer Style panel, not the API. Workflow: write literal `gap: clamp(...)` via API, Karen swaps to variable in panel later.

6. **`alt` and `href` are reserved attribute names.** `add_or_update_attribute` rejects them. For alt text, set `alt_text` in `set_image_asset` during element_builder (works), OR set at the asset level via `asset_tool > update_asset(asset_id, alt_text)` — cascades to every use of that asset.

7. **Webflow grid creates phantom auto-rows** that resist deletion. For multi-column layouts, prefer **flex with flex-grow ratios** on children. Set `grid-template-rows: auto` explicitly if you must use grid for a small known-count grid.

8. **HtmlEmbed via element_builder doesn't accept content cleanly** — no `set_text` support, no obvious settings key for embed code. For interactive features (timeline scrubbers, custom JS), give Karen the embed code as chat output and have her paste via Designer Add → Embed.

9. **Designer disconnects when its tab loses focus.** Symptom: API returns "Unable to connect to Webflow Designer". Share the relaunch link, ask Karen to click, retry.

10. **`create_page` does NOT reliably auto-switch the Designer.** Always confirm with `get_current_page` after switch_page.

11. **`update_page_settings` ignores `draft: true`** — the API doesn't honour the draft flag through that route. If Karen wants a page truly hidden during build, she sets Draft via Designer pages panel.

12. **Variable Modes are element-only, never on classes.** Set Theme: Dark on the Body element only. Setting on a class (or inside a component view) pollutes classes with mode-pinned overrides that can't be cleaned via UI/API.

## Memory files written this session

In `/Users/karenhuang/.claude/projects/-Users-karenhuang-Documents-Projects-KIN-Claude/memory/`:

- `project_kin_investor_ir_page.md` — IR vertical-rhythm cleanup is done; use as reference impl.
- `feedback_reference_original.md` — don't wing it; pull canonical sizes/spacing/colours from local /3001 first.
- `feedback_real_content_during_build.md` — inject real text, never placeholders.
- `reference_webflow_element_builder_quirks.md` — the gotchas above, codified.

Read these before touching anything.

---

End of handoff. Be opinionated, be brief, work in chunks, hand off to Karen when stuck.
