# Repository Guidelines

_Last updated: 2026-01-07 (UTC)_

## Project Structure & Module Organization

- Root-level `.html` files (e.g., `index.html`, `aerospace-defense.html`, `portfolio-*.html`) are page-ready templates for each business area.
- Industry microsites live under similarly named directories such as `aerospace-defense/`, `automotive/`, `oil-gas/`, and `retail-fashion/`, each containing its own `index.html` for easy duplication.
- Global styling resides in `css/` (`style-core.css` from the GoodLayers Logisco theme, `logisco-style-custom.css` for inherited tweaks, and `modern-primegate.css` for bespoke branding rules).
- Interactive behavior is in `js/`, notably `heroTypewriter.ts` (source) and its compiled `heroTypewriter.js`, plus `plugins.js` and the `jquery/` bundle.
- Third-party assets stay sandboxed in `plugins/` (`goodlayers-core`, `quform`, `revslider`) so upstream updates do not collide with custom code.
- Brand assets, photography, and PDFs live in `upload/` and `details/`. Legacy blog exports remain under `2016/06/06/` for archival reference.

## Build, Test, and Development Commands

```bash
# Preview the static site over HTTP for local QA
python -m http.server 8000

# Recompile the hero typewriter TypeScript after edits
npx tsc js/heroTypewriter.ts --target ES5 --outFile js/heroTypewriter.js

# Smoke-test multiple pages with a headless browser (Chromium must be installed)
npx playwright open index.html
```

## Coding Style & Naming Conventions

- **Indentation**: 2 spaces for TypeScript/JavaScript (see `heroTypewriter.ts`), tab-indented selectors plus aligned properties in CSS themes.
- **File naming**: Lowercase, hyphen-delimited slugs (`air-freight.html`, `goodlayers-core`). Directory names mirror their landing-page slugs for predictable linking.
- **Function/variable naming**: UpperCamelCase for classes (`TypeWriter`), UPPER_SNAKE_CASE for shared constants, and descriptive camelCase for DOM helpers.
- **Linting**: No automated linters are wired in. Rely on the TypeScript compiler for syntax guarantees and keep CSS edits scoped to `modern-primegate.css` to avoid upstream merge conflicts.

## Testing Guidelines

- **Framework**: No automated suite; interactions rely on GoodLayers widgets, jQuery, and Quform.
- **Test files**: Manual regression focuses on `index.html`, `contact.html`, `get-a-quote.html`, and representative industry pages.
- **Running tests**: Serve locally (`python -m http.server 8000`) and validate menus, sliders, Quform inputs, and the hero typewriter animation across Chrome, Firefox, and at least one mobile emulator.
- **Coverage**: Treat every navigation cluster (top bar, mobile menu, footer CTAs) as must-pass before shipping; document manual results in PRs.

## Commit & Pull Request Guidelines

- **Commit format**: Keep the terse, verb-first style already in history (e.g., `type script push`, `second commit`). Prefer descriptive suffixes such as `type script push: add caret animation` when touching multiple assets.
- **PR process**: Include screenshots or screencasts for any visual change, list touched HTML/CSS/JS files, and describe the manual regression path you ran. Tag another contributor for sanity checks on navigation behavior.
- **Branch naming**: Use short kebab-case topics mirroring the affected section, e.g., `feature/hero-typewriter`, `fix/contact-form-layout`.

---

# Repository Tour

## 🎯 What This Repository Does

Primegate International is a static marketing site built on the GoodLayers Logisco theme with custom TypeScript/CSS overlays that highlight logistics, freight, and industry-specific services.

**Key responsibilities:**
- Present productized service lines (air freight, break bulk, customs, warehousing) with rich imagery.
- Showcase sector landing pages (`aerospace-defense/`, `oil-gas/`, etc.) and archived blog content under `2016/`.
- Power lead capture via Quform-based contact and quote flows plus deep links to WhatsApp chat.

---

## 🏗️ Architecture Overview

### System Context
```
[Visitor Browser]
     ↓ (HTTP/HTTPS requests)
[Primegate static HTML/CSS/JS]
     ↓ assets
[GoodLayers + jQuery plugins]
     ↓ outbound links
[WhatsApp API / Quform handler]
```

### Key Components
- **Page templates (`*.html`)** – Pre-rendered layouts combining the Logisco grid system with Primegate content; mobile navigation and mega menus are configured per file.
- **Styling layer (`css/`)** – Base theme styles plus `modern-primegate.css` for gradients, cards, and typography customizations applied site-wide.
- **Interaction layer (`js/`)** – `heroTypewriter.ts` animates hero copy, `plugins.js` wires GoodLayers behaviors (sticky nav, sliders, anchors), and `plugins/quform` handles form validation.
- **Plugin assets (`plugins/`)** – Vendor CSS/JS bundles (GoodLayers page builder, Revolution Slider, Quform) kept intact for easier vendor updates.

### Data Flow
1. Browser loads an HTML page that references Google Fonts, FontAwesome, and local CSS/JS bundles.
2. `plugins.js` initializes menus, sliders, sticky headers, and preloader logic based on viewport events.
3. Page-specific modules (e.g., hero typewriter, Quform) pick up DOM hooks and run animations or validations.
4. User interactions submit forms to Quform handlers or redirect to WhatsApp; responses are surfaced inline without backend changes in this repo.

---

## 📁 Project Structure [Partial Directory Tree]

```
./
├── index.html                  # Primary landing page wired to GoodLayers components
├── css/
│   ├── style-core.css          # Vendor theme stylesheet (Logisco)
│   ├── logisco-style-custom.css# Theme overrides inherited from template
│   └── modern-primegate.css    # Primegate-specific visual system
├── js/
│   ├── heroTypewriter.ts       # Source TypeScript for hero animation
│   ├── heroTypewriter.js       # ES5 output consumed by pages
│   ├── plugins.js              # Bundled GoodLayers interactions
│   └── jquery/                 # jQuery + jQuery UI utilities
├── plugins/
│   ├── goodlayers-core/        # Page builder CSS/JS + icon fonts
│   ├── revslider/              # Revolution Slider assets
│   └── quform/                 # Form styling/scripts and icons
├── upload/                     # Images, icons, PDFs referenced across pages
├── contact.html                # Modernized contact + Quform implementation
├── get-a-quote.html            # Lead capture form
├── aerospace-defense/          # Sector-specific microsite (index.html)
├── automotive/                 # Sector-specific microsite (index.html)
├── oil-gas/                    # Sector-specific microsite (index.html)
├── portfolio/ai-system-integration/
│   └── index.html              # Portfolio detail layout
└── 2016/06/06/...              # Archived blog exports for legacy content
```

### Key Files to Know

| File | Purpose | When You'd Touch It |
|------|---------|---------------------|
| `index.html` | Main hero, navigation, and service highlights | Update marketing copy, hero CTAs, or masthead visuals |
| `index-2.html` | Alternate home variant used by navigation links | Keep nav targets in sync or experiment with layout changes |
| `css/style-core.css` | Base Logisco theme styles | Rarely changed; only when vendor theme tweaks are unavoidable |
| `css/logisco-style-custom.css` | Theme override layer shipped with template | Adjust inherited spacing/typography without breaking vendor file |
| `css/modern-primegate.css` | Primegate-specific gradients, cards, grids | Add new brand elements or section-specific styling |
| `js/heroTypewriter.ts` | TypeScript source for hero typing effect | Modify animation speed/text handling before recompiling |
| `js/heroTypewriter.js` | Generated ES5 consumed by browsers | Never edit directly; regenerated via `tsc` |
| `js/plugins.js` | Bundled vendor interactions (menus, sliders) | Inspect to understand behaviors; prefer extension scripts instead of editing |
| `contact.html` | Contact page with Quform form + modern cards | Update office info, Quform IDs, or CTA design |
| `plugins/quform/css/base.css` | Styling for Quform widgets | Align form appearance with site branding |
| `upload/*` | Image/PDF assets referenced by all pages | Compress or replace imagery; keep filenames stable |
| `2016/06/06/five-ways-to-style-a-room-on-a-tiny-budget/index.html` | Sample archived article | Use as template if re-enabling blog |

---

## 🔧 Technology Stack

### Core Technologies
- **Language:** HTML5 + CSS3 for layout; TypeScript transpiled to ES5 JavaScript for targeted enhancements.
- **Framework:** GoodLayers Logisco page-builder/shortcode system powers grid layouts and mega menus without a CMS backend.
- **JavaScript Runtime:** jQuery (bundled under `js/jquery/`) plus minified plugin scripts for sliders, menus, and sticky navigation.
- **Forms/Widgets:** Quform assets drive validation and UX for contact/quote forms.

### Key Libraries
- **GoodLayers Core** – Supplies grid classes, mega-menu logic, and helper widgets referenced throughout `index*.html`.
- **Revolution Slider** – Animates hero carousels via `plugins/revslider/public/assets/css/settings.css` and related assets.
- **FontAwesome 4.5** – Injected via CDN for icons used in nav bars and cards.
- **Hero Typewriter module** – Custom TypeScript module responsible for animated hero messaging.

### Development Tools
- **TypeScript CLI (`tsc`)** – Ensures the hero animation stays type-safe and outputs ES5-friendly code.
- **Local static server (Python/Node)** – Provides HTTP previews needed for AJAX-heavy widgets (search overlay, Quform).
- **Design assets under `upload/`** – Source-of-truth for hero imagery, testimonial photos, and PDFs.

---

## 🌐 External Dependencies

### Required Services
- **Google Fonts CDN** – Loads Assistant, Karla, and PT Sans for typography; network failures fall back to default sans-serif.
- **FontAwesome CDN** – Supplies iconography used throughout navigation and CTA widgets.
- **WhatsApp API Deep Links** – `index.html` and `contact.html` include floating buttons pointing to `api.whatsapp.com` for instant outreach.
- **Quform Backend** – Client-side assets live here, but successful submission depends on an external handler not stored in this repo.

### Optional Integrations
- **Social Media Links** – Top bar icons target Facebook, LinkedIn, Twitter, Instagram; URLs are placeholders and can be swapped safely.

---

## 🔄 Common Workflows

### Update Hero Messaging & Animation
1. Edit textual content within `index.html` (or `index-2.html`) hero sections.
2. If the typing effect needs new copy or speeds, adjust constants in `js/heroTypewriter.ts` and recompile via `tsc`.
3. Serve locally (`python -m http.server 8000`) to confirm the animation sequence, caret blink, and responsive layout.

**Code path:** `index.html` → `js/heroTypewriter.ts` → `js/heroTypewriter.js`

### Launch a New Industry Page
1. Duplicate any existing sector folder (e.g., `oil-gas/`) and rename it to the new slug.
2. Update metadata, hero background URLs, and service copy in the new `index.html`.
3. Link the new page from `industry-solutions.html` and relevant menus.
4. Re-test navigation on desktop and mobile to ensure GoodLayers menus pick up the new entry.

**Code path:** `industry-solutions.html` → `navigation sections in index.html` → `new-sector/index.html`

---

## 📈 Performance & Scale

- **Image Weight:** Assets live under `upload/`; keep replacements optimized (≤200KB when possible) to avoid sluggish hero loads.
- **Preloader:** The GoodLayers preloader (`#logisco-page-preload`) masks content until CSS/JS settle—leave hooks intact when adding scripts.
- **Animation Budget:** `plugins.js` already schedules numerous scroll/resize handlers; prefer lightweight, requestAnimationFrame-based add-ons for new effects.

---

## 🚨 Things to Be Careful About

### 🔒 Security Considerations
- **Quform Submissions:** Front-end markup expects a server-side handler; sanitize inputs server-side and keep form IDs consistent with the handler configuration.
- **External Links:** The WhatsApp floating button opens new tabs—validate phone numbers before pushing to production.
- **Third-party Scripts:** GoodLayers and Revolution Slider files are vendor-provided; replace them as whole units to avoid checksum mismatches or licensing drift.

*Update to last commit: 492b2f98d786b8683fc2cb176ecddf28d5e39f35*
