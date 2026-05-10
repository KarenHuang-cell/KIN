# Webflow CSS cleanup — KIN Investor

Tracker for class-level issues spotted during the build. None of these are blocking, but each violates the CSS discipline rules (semantic names, no duplicates, BEM consistency). Revisit when there's a quiet stretch.

---

## 1. Orphan / unclear classes

- **`tree-bold`** — properties: `color: <variable>; font-weight: 600; letter-spacing: 0em;`. Purpose unknown, no obvious owner. Likely dead. **Action:** check whether any element on the site uses it (search by class name in Webflow). If unused → delete. If used → rename to something semantic.

## 2. Webflow auto-named classes (violate "never Div Block 47" rule)

These were created when elements were added without explicit class names. Each needs a semantic rename or removal:

- `Text Block`
- `Flex Block`
- `Section`
- `Div Block`
- `Div Block 3`
- `Link Block`

**Action:** for each, find where it's applied, rename to a BEM class that describes its role (e.g. `Flex Block` → `nav__list`), then delete the auto-class.

## 3. Body-tag duplicates

- `Body` (capital B — element tag style)
- `body` (lowercase — appears to be default)

**Action:** confirm whether both are needed. Webflow usually uses one. Consolidate if duplicate.

## 4. Live nav still using Webflow auto-classes

The Navigation component has these inside its current desktop layout, all of which should be replaced with semantic BEM classes:

| Current | Suggested rename |
|---|---|
| `<a class="link-block w-inline-block">` (logo wrapper) | `nav__logo` |
| `<div class="w-layout-hflex flex-block">` (links container) | `nav__list` |
| Bare `<div>` wrappers around each `<a class="nav-link">` (no class) | Either remove the wrapper divs entirely OR class them as `nav__item` |

`w-inline-block`, `w-layout-hflex` are Webflow's built-in framework classes that come from using the Link Block / Horizontal Flex elements — those aren't "removable" without changing the element type, but the additional auto-classes (`link-block`, `flex-block`) ARE removable once renamed.

## 5. Naming inconsistency: nav uses single-hyphen, rest of site uses BEM

These five existing classes use single-hyphen naming:

- `navigation` (the nav wrapper)
- `nav-link`
- `nav-cta`
- `logo-navy`
- `tree-bold` (also captured in #1)

The rest of the site mostly uses BEM (`footer__bottom`, `cta-light__email`, `corp-node__name`, `heading--inst`, `grid--1-2`). Per the abandoned-rename note in memory, this drift exists because FS Folders fought mixed separators last time.

**Suggested renames:**
- `navigation` → `nav` (or `nav__wrap`)
- `nav-link` → `nav__link`
- `nav-cta` → `nav__cta`
- `logo-navy` → `nav__logo` (and use a CSS filter or variable to flip color per variant rather than a separate logo-navy class)

## 6. New nav classes added during responsive build (2026-05-06)

Added:
- `nav__hamburger`
- `mob-nav`
- `mob-nav__group`
- `mob-nav__parent`
- `mob-nav__child`
- `mob-nav__cta`

These follow BEM and are consistent with the dominant site pattern. No issue — listed here only for traceability.

## 7. Custom Code rules introduced for the responsive nav

Lives in **Site Settings → Custom Code → Footer Code**. Contains:
- Hamburger span styling (width/height/background/transition)
- Open-state CSS (`.is-open` rotation animation, `body.nav-open { overflow: hidden }`)
- Toggle JS (~10 lines)

Reason for Custom Code rather than Style panel: Webflow's Style panel can't author `:nth-child` selectors or pseudo-element animations cleanly. If/when Webflow adds those features, migrate the open-state CSS into the Style panel.

## 8. Footer — polluted class orphans (2026-05-08/09)

After the `footer-frame` migration:
- The Footer's root Section was retagged to `footer-frame` only (clean, theme-aware).
- `footer-light 2` was deleted (was orphan).
- **`footer-light` and `footer-base` still exist** — Webflow blocks deletion ("ensure no usages"). At least one element somewhere off the Footer component still wears one or both.

**Action:** Find remaining usage via Webflow's class-manager "instances" indicator or page-by-page scan. Retag offending elements to `footer-frame`, then delete both classes.

## 9. Events Template — cs-* typography utility migration

Per `reference_webflow_kin_design_system.md`. cs-* sections still redefine font/colour locally. Migrate as combo:
- `body-text-muted` → cs-* descriptive text (dark context body)
- `eyebrow` → cs-* labels / eyebrows
- Avoid `body-text--inst` (IR-specific, 56ch max-width)

## 10. Events Template — Rich Text scope migration (in progress)

Move embed CSS to Webflow-native inner-element scopes. Embed in cs-body (id `ac96707b…50d186`) holds:
- `.cs-body p` { margin-bottom: 20px; max-width: 600px } → **All Paragraphs** of `text-body-muted`
- `.cs-body h3` { 24px / 500 / 36px line-height / margin 36px 0 8px / max-width 600 } → **All H3 Headings** of `text-body-muted`. Use `--_theme---text-primary` for colour (theme-aware) instead of hard `#fff`.
- Medium breakpoint: `.cs-body p { max-width: 100% }`

Once styles migrated via Designer UI, delete the embed.

## 11. Events Template — responsive overrides pending

- `cs-services__list`: add 4-col → 2-col grid at ≤767px (no mobile override currently)
- `cs-gallery__img` (in carousel embed): `height: 580px` → `clamp(240px, 50vw, 580px)`
- `cs-hero__title`: oversized H1 overflows on narrow viewports (e.g. "WTT Singapore Smash" runs off screen) — needs clamp() on font-size or `text-wrap: balance`
- `cs-services__heading` / `cs-targets__heading`: decide eyebrow style (current: 0.75rem caps muted) vs 24px h3 to match body H3s

## 12. Events Template — `cs-gallery__wrap` missing `overflow: hidden` (2026-05-09)

The carousel's translateX slide system requires `overflow: hidden` on its wrap. The class definition lacks it; the carousel was only rendering correctly because of upstream clipping. Adding shrinkwrap as combo exposed the bug — slides overflowed visibly (two slides side-by-side).

**Current state:** Built `cs-gallery-v2` embed with its own `cs-gallery-v2__viewport` div carrying `overflow: hidden`. V2 reads from the same Lightbox (`.cs-gallery__source`). V2 sits as sibling above the broken `cs-gallery__wrap`.

**To clean up after v2 is verified stable:**
- Delete old `cs-gallery__wrap` Block and its child Code Embed
- OR: add `overflow: hidden` to the `cs-gallery__wrap` class definition and revert to the simpler structure. v2 approach is preferred (decoupled from class state).

## 13. Events Template — shrinkwrap migration on cs-* inners (2026-05-09)

Done on three of four:
- `cs-intro__inner` + shrinkwrap ✓
- `cs-stats__inner` + shrinkwrap ✓
- `cs-content__inner` + shrinkwrap ✓
- `cs-gallery__wrap` — reverted (broke the carousel; see #12)

`cs-*__inner` classes likely still have local `5vw` padding-inline, own `max-width`, `margin: 0 auto` rules that are now redundant (shrinkwrap combo overrides). Worth stripping for cleanliness on a future pass. Also: parent Sections (`cs-intro`, `cs-stats`, `cs-content`) may have horizontal padding that adds on top of shrinkwrap's — strip if present.

---

## Notes for the next pass

- Fixing #4 and #5 together makes sense — they're the same "nav area inconsistency" problem.
- #2 is the highest-volume but lowest-risk cleanup; can be done piecemeal.
- After cleanup, do another full inventory pull and verify no class has fewer than 1 use.
- #10 and #12 are in-flight — finish the migrations before tackling new items.
