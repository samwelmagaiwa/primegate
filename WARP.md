# WARP.md

This file provides guidance to WARP (warp.dev) when working with code in this repository.

## Quick commands

Run these from the repository root.

```bash path=null start=null
# Serve the static site locally on http://localhost:8000
python -m http.server 8000

# Recompile the hero typewriter TypeScript after edits
npx tsc js/heroTypewriter.ts --target ES5 --outFile js/heroTypewriter.js

# Optional: open a page in a real browser (or via Playwright if installed)
# (example) npx playwright open index.html
```

Notes:
- There is no standard JS build pipeline; the only compilation step is `heroTypewriter.ts → heroTypewriter.js` via `tsc`.
- There is no configured test runner or linter. TypeScript compilation and manual browser QA are the primary safety nets.

## Project overview

Primegate International is a static marketing site for logistics and industry-specific services, built on the GoodLayers Logisco theme with a thin layer of custom TypeScript and CSS.

High-level responsibilities:
- Present core service lines (air freight, customs, warehousing, break bulk, etc.).
- Provide sector landing pages (e.g. `aerospace-defense/`, `oil-gas/`, `automotive/`, `retail-fashion/`).
- Capture leads via Quform-based contact and quote pages, plus WhatsApp deep links.

For a more exhaustive tour of the repository, see `AGENTS.md`.

## Architecture & key components

### System context

- Static HTML/CSS/JS only; no backend code in this repo.
- The browser loads root-level `*.html` pages, which include theme CSS/JS and plugin bundles.
- Client-side plugins handle navigation, sliders, forms, and animations.
- Some flows (Quform submissions, WhatsApp chat) depend on external services.

### Layout & content

- **Root HTML files** (`index.html`, `index-2.html`, `industry-solutions.html`, `contact.html`, `get-a-quote.html`, multiple `*-services` pages) are full page templates wired directly to GoodLayers components.
- **Industry microsites** live in directories like `aerospace-defense/`, `automotive/`, and `oil-gas/`, each with its own `index.html` that largely mirrors the root layout.
- **Archived content** sits under `2016/06/06/...` and is not part of the modern navigation but can be used as reference.

### Styling layer (`css/`)

- `css/style-core.css` – Vendor Logisco theme stylesheet. Treat as third-party; avoid editing unless strictly necessary.
- `css/logisco-style-custom.css` – Template-level overrides shipped with the theme.
- `css/modern-primegate.css` – Primary location for Primegate-specific visual changes (gradients, cards, typography, section layouts). Prefer placing new styles here.

### Interaction layer (`js/`)

- `js/heroTypewriter.ts` – TypeScript source for the hero typing animation used on the home page variant(s).
- `js/heroTypewriter.js` – Generated ES5 output consumed by pages; do not edit directly.
- `js/plugins.js` – Aggregated vendor interactions (sticky nav, sliders, scroll behavior, etc.). It is primarily for understanding existing behavior rather than for modifications.
- `js/jquery/` – jQuery and related utilities bundled for theme plugins.

### Third-party plugins & assets

- `plugins/goodlayers-core/` – GoodLayers theme core CSS/JS and icon fonts that power grids, widgets, and navigation.
- `plugins/revslider/` – Revolution Slider assets used for hero and carousel animations on some pages.
- `plugins/quform/` – Quform front-end assets (CSS, JS, icons) backing contact and quote forms.
- `upload/` and `details/` – Images, PDFs, and other static assets referenced across pages.

### Data & control flow (conceptual)

1. Visitor hits an HTML page (e.g. `index.html`).
2. The page loads Google Fonts and FontAwesome from CDNs, plus local CSS in `css/` and JS in `js/` and `plugins/`.
3. Theme scripts (`plugins.js` and vendor bundles) attach behaviors to navigation, sliders, and form elements.
4. Custom scripts (notably `heroTypewriter`) enhance specific sections.
5. Form submits and WhatsApp CTAs leave this repository and hit external handlers/APIs.

## Development workflows

### Update hero messaging & animation

- Edit hero section copy directly in `index.html` / `index-2.html`.
- If the typed text, speed, or looping behavior needs to change, update the relevant constants/logic in `js/heroTypewriter.ts`.
- Recompile with `npx tsc js/heroTypewriter.ts --target ES5 --outFile js/heroTypewriter.js`.
- Serve the site locally and verify the animation and layout on desktop and mobile viewports.

### Add or clone an industry microsite

- Copy an existing sector directory (e.g. duplicate `oil-gas/` to a new folder matching the desired slug).
- In the new folder’s `index.html`, update:
  - Page metadata (title, description).
  - Hero background imagery.
  - Section copy and CTAs.
- Wire the new sector page into navigation and index pages (e.g. `industry-solutions.html` and any relevant menus on `index.html`/`index-2.html`).
- Re-test navigation on desktop and mobile.

### Adjust global styling

- Prefer to:
  - Add or modify rules in `css/modern-primegate.css`.
  - Leave `css/style-core.css` and most of the theme’s vendor CSS untouched unless there is a compelling reason.
- When changing components shared across many pages (cards, hero sections, footers), confirm the impact on a representative set of root pages and sector pages.

## Testing & QA

- There is no automated test suite configured (no Jest, Playwright config, or similar in the repo). Testing is manual.
- Typical manual QA loop:
  - Serve the site: `python -m http.server 8000`.
  - Validate key flows on modern browsers (Chrome, Firefox, and a mobile viewport):
    - Top navigation and mobile menu open/close behavior.
    - Hero slider and typewriter animation on home pages.
    - Contact and quote forms (Quform validation, submission UX).
    - WhatsApp floating button links.
    - A sample of sector pages for layout regressions.
- You can optionally use `npx playwright open index.html` for quick smoke tests if Playwright is installed, but this is not wired into any formal config.

## Gotchas & caveats

- Do not edit `js/heroTypewriter.js` directly; always change `js/heroTypewriter.ts` and recompile.
- Treat everything in `plugins/` and most of `css/style-core.css` as third-party vendor code. If a change is required, prefer replacing vendor bundles wholesale rather than making ad-hoc edits.
- Quform front-end markup in `contact.html`, `get-a-quote.html`, and related files assumes a matching server-side handler not present in this repo. If you change form IDs or structure, ensure the external handler configuration is updated accordingly.
- Image and asset paths under `upload/` are widely reused; when replacing files, keep filenames and directory layout stable to avoid broken references.
