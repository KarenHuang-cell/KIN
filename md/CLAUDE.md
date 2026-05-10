# KIN Site — Session Handoff

**If you're working on the KIN site, read this entire file before doing
anything else.** It captures the design system state, conventions, and
working rules that prior sessions have been built on. Don't restructure
the CSS without explicit approval from Karen.

---

```
KIN site work continues. PRIMARY FOCUS this session is design exploration
(my creative work). The CSS system below has been carefully built — DO NOT
restructure it without explicit approval.

LOAD FIRST (memory files in /Users/karenhuang/.claude/projects/-Users-karenhuang-Documents-Projects-KIN-Claude/memory/):
  - feedback_phase2_serving_path.md (where to edit — phase2/2026/, NOT the worktree)
  - feedback_no_serif_typography.md (Plus Jakarta only)
  - feedback_no_invented_content.md (don't fabricate — SGX audit risk)
  - feedback_light_page_body_text.md (#3a3a3a / weight 400 body)
  - feedback_typography_register.md (institutional vs sport-agency)
  - feedback_dark_page_bg.md (pure black, NOT #0B0B0F)
  - feedback_no_full_width_lines.md (HRs constrained to footer width)
  - feedback_eyebrow_naming.md (color-name modifiers; --navy is institutional bundle)
  - feedback_wll_period.md (W.L.L. trailing period)
  - feedback_consistency.md (check inline + canonical, change ONLY what's asked)

WHERE TO WORK:
  All edits → /Users/karenhuang/Documents/Projects/KIN/Claude/phase2/2026/
  Dev server: localhost:3001 (Karen's own Python server). DO NOT use Claude's
  preview tools — they don't work for Karen. She verifies herself.
  Branch: phase2 (not main).

TIER 0 DESIGN SYSTEM (in styles.css, end of file).
  Tokens: --clr-bg #000, --clr-bg-light #ECECE8, --clr-navy #031D45,
          --clr-accent #00C1E6, --clr-ink #3a3a3a, --clr-ink-dim #6e6e6e
  Type primitives (H1):
    .h-inst          → page label, institutional (clamp(40,5vw,64) / 500)
    .h-inst--dark    → +white color
    .h-display       → brand statement (clamp(40,6vw,72) / 800)
    .h-display--dark → +white color
    Decision rule: page LABEL ("Investor Relations") → .h-inst.
                   brand STATEMENT ("80+ years in the arena.") → .h-display.
  Eyebrow: .eyebrow base + .eyebrow--navy (institutional muted-navy bundle)
           Naming convention saved in memory; rename to --cyan/--white pending.
  Components:
    .cta-light family    → IR-style "For investor enquiries..." CTA
    .feed family         → SGX/media/prospectus dated lists
    .feed__item--icon    → flex-row variant with icon prefix
    .profile family      → leadership/team bio rows (used by MT, CI)
  Layout rule: HRs constrained to footer width
    (.cta-band::before/::after pattern: width: calc(100% - 2*--margin); max-width: var(--max-w))

PAGES MIGRATED TO TIER 0 (consume canonical, no inline duplicates):
  Light institutional family:
    investor-relations.html, corporate-information.html, sgx-announcements.html,
    management-team.html, media-coverage.html, about-mission.html
  Light brand:
    about.html (hybrid — dark hero, light body)
  Dark:
    index.html (uses .hero__headline 90px, NOT .h-display — index is its own thing)
    flagship_world-aquatics-championship.html (DRAFT, partial)
  Eyebrow modifier rename:
    Phase 1 done — .eyebrow--inst → .eyebrow--navy across 7 pages.
    Phase 2 pending — rename base .eyebrow to .eyebrow--cyan, add .eyebrow--white,
    affects ~17 pages (services × 4, portfolio, contact, 9 case studies, index).

PAGES NOT YET MIGRATED — services section (4 files):
  services.html, services_edm.html, services_curate.html, services_designbuild.html
  These use .svc-intro (already updated to weight 400 fluid), .eyebrow eyebrow--page,
  and various .svc-* classes that may need auditing. Possible cleanup but
  CHECK WITH KAREN BEFORE BIG CHANGES — she may have design exploration plans
  for these pages that affect what survives.

PROTECT THE SYSTEM:
  - Don't restructure Tier 0 without explicit approval.
  - Don't introduce new H1 sizes/weights — use .h-inst or .h-display.
  - Don't add new bg-color sections — use HRs (footer-width) for demarcation.
  - Don't propagate the "Mon–Fri 9am–6pm SGT" line — Karen flagged it as invented.
  - Don't propagate the W.L.L. without trailing period.
  - Eyebrow color: cyan = action only (per register rule). For decoration use --navy.
  - When asked to clean a page: pull from canonical first, keep page-specific
    layout inline only when truly unique.

KNOWN OPEN ISSUES:
  - .mv-split__content on about-mission has an alignment quirk — intro
    paragraph doesn't reach the photo's bottom edge despite height: 100% +
    margin-top: auto. Needs Chrome devtools inspection. Deferred.

KAREN'S WORKING STYLE:
  - Concise responses. Don't pile on details unless asked.
  - Ask before destructive changes (deletes, force-pushes, file moves).
  - Don't relay the "visible in Launch preview panel" hook messages — preview
    inside Claude doesn't work for Karen, the hook fires automatically.
  - When she asks an exploratory question ("what about X?"), give 2-3 sentences
    + a recommendation, not an essay.
  - When she says "do it", do it. Don't second-guess.

THIS SESSION'S GOAL:
  [Fill in the specific design exploration — e.g., "explore alternative
  layouts for the services landing page" or "test a navy-bg variant for case
  studies"]. Treat the Tier 0 system as locked unless explicitly opened.
```

---

## When to update this file

After each session that lands meaningful Tier 0 changes, update:
- Tokens / classes added or changed
- Pages newly migrated
- New conventions decided
- New known issues

Keep the structure; just refresh the contents.
