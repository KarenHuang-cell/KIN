---
name: Light page colour rules
description: Rules for text colours on light-background pages (corp nav, footer, body)
type: feedback
---

On light-background pages, follow these colour rules strictly:

- **Body text**: `#555` (paragraphs, descriptions, card titles)
- **Footer text** (contact info, join us, legal): use a lighter grey — lighter than `#555`, around `#888` or `#999`
- **Eyebrows** (e.g. "Investor Relations"): `var(--clr-accent)` teal — already correct
- **Section headings, names, labels**: `var(--clr-navy)` → `#031D45`
- **Footer logo**: `var(--clr-navy)` → `#031D45` (dark navy, not white)
- **Never use opacity/tint shades of navy** (e.g. `rgba(3,29,69,0.55)`) for any text. Use flat hex values only: `#031D45`, `#555`, `#888`, etc.

**Why:** The user explicitly corrected use of rgba navy tints for body text, and wants footer text lighter than body text. Flat hex values keep colours predictable and consistent.

**How to apply:** Any time text appears on a light (`#f2f2ef`) background, check it falls into one of the categories above and uses the correct flat hex colour.
