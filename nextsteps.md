# KIN Webflow — Next Steps

Running list of cleanup / improvement work to pick up after current section is shipped. Add new items as they surface.

---

## Nav — transparent + blur variant for overlay heroes

**Why.** The phase2 home (and likely future full-bleed video pages — immersive, project case studies) wants the Nav to sit *over* the hero media: transparent background with a soft `backdrop-filter: blur(10px)`. Once the user scrolls past the hero, the Nav switches to its solid state (the existing default).

**Current state.** The live Nav component on the Webflow site has a solid background only. No transparent variant exists yet. For the phase2 home migration we used the dark Nav as-is, knowing the proper variant would land later.

**How to fix (don't detach).**

Two paths, in increasing order of polish:

1. **Combo class on instances.** Apply a second class to the Nav component instance on overlay pages — e.g. `nav-wrap--overlay`. Style that combo: `background: transparent; backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);`. Master stays intact; only flagged instances render transparent. Fastest.

2. **Prop on the master component.** Open the Nav component, add a Boolean prop named "Overlay". Wire it to a class toggle on the root. Cleaner if more than one page ever uses it — single source of truth, toggle from the instance settings.

**Trigger logic.** The existing phase2 home JS toggles a `.scrolled` class on the Nav after `window.scrollY > 60`. Same JS belongs in Webflow as a small custom-code embed on overlay pages — switches the Nav from transparent → solid as the user scrolls past the hero.

**When.** After the index migration is complete and the rest of the site is in.

---
