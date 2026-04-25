# Jobs Theme 2026 (`jobswp-2025`)

A WordPress block theme for [jobs.wordpress.net](https://jobs.wordpress.net/), successor to the classic `jobswp` theme. Currently in review — not yet shipped to the live site.

## Requirements

- WordPress 6.5+ (uses the Interactivity API and `viewScriptModule`)
- PHP 7.4+
- The `jobswp` plugin must be active. The theme calls into `Jobs_Dot_WP::*` and helpers from `jobswp-template.php`. The plugin is untouched; it lives separately at `wp-content/plugins/jobswp/`.

## Local install

1. Clone this repo into `wp-content/themes/jobswp-2025` of a local WordPress install that has the `jobswp` plugin active.
2. Activate **Jobs Theme 2026** via Appearance → Themes.
3. Make sure pages exist at `/faq/`, `/feedback/`, `/post-a-job/`, `/remove-a-job/`.

## What to test

- **Homepage** — hero, stats row, category filter pills (should filter the job grid client-side via the Interactivity API), job cards.
- **Category archives** and **search results** — archive header with RSS link and column labels, job list table, sidebar "Position Types" list, pagination.
- **Single job** — breadcrumb, two-column layout with meta sidebar (company, job type, location, budget, how to apply).
- **Post a job** — full form flow: contact/company/job sections → verify step → submit → job token generated and emailed → success preview renders via the plugin's `the_content` filter.
- **Remove a job** — token input → redirect with `?removedjob=1` on success.
- **FAQ, Feedback** — generic page template.
- **404.**
- **Mobile** — `parts/mobile-overlay.html` is the editable overlay; `js/mobile-overlay.js` toggles `body.has-mobile-overlay-open` on hamburger / close clicks (`jobswp-2025/menu-toggle`).
- **GTM** — tag fires in `<head>` and the `<noscript>` iframe is present right after `<body>`.

## Notes for reviewers

- The post-a-job / remove-a-job / success-preview flow still depends on the `jobswp` plugin's `the_content` filter calling `get_template_part('content', 'post-job')`, `'post-job-success'`, and `'single'`. Because of that coupling, `content-post-job.php`, `content-post-job-success.php`, and `content-single.php` remain at the theme root as classic PHP template parts. Everything else is block-based.
- Design tokens live in `theme.json`. `style.css`'s `:root` aliases the original `--color-*` / `--font-*` / `--space-*` names to the theme.json-generated `--wp--preset--*` vars so the existing class-based CSS keeps working without a full rewrite.
- Form interactivity on the homepage (filter pills → show/hide job cards) uses the [Interactivity API](https://developer.wordpress.org/block-editor/reference-guides/interactivity-api/) via `viewScriptModule` on the `jobswp-2025/job-browser` block. No build step — native ES modules resolve `@wordpress/interactivity` through the import map WordPress injects.
- GTM snippet is hardcoded to the production container ID (`GTM-P24PF4B`) via `wp_head` / `wp_body_open` hooks in `functions.php`. Safe to leave as-is for local testing.

## Structure

```
jobswp-2025-theme/
├── theme.json                      # design tokens, element styles
├── style.css                       # ported CSS with :root alias bridge
├── functions.php                   # theme setup, GTM, block registration
├── templates/                      # block templates (HTML)
├── parts/                          # header.html, footer.html, mobile-overlay.html
├── patterns/                       # block patterns (PHP)
├── blocks/                         # six dynamic blocks (see "Custom blocks" below)
│   ├── job-browser/                # homepage: stats + filter pills + grid, Interactivity API
│   ├── job-meta-card/              # single-job sidebar
│   ├── archive-header/             # category / search archive header
│   ├── job-list/                   # archive job list table
│   ├── menu-toggle/                # mobile-overlay open/close button
│   └── open-to-work-candidates/    # paginated wp.org "Open to Work" profiles grid
├── content-post-job.php            # classic template part (plugin couples to this path)
├── content-post-job-success.php    # classic template part (plugin couples to this path)
├── content-single.php              # classic template part (plugin couples to this path)
└── inc/template-tags.php           # helpers (incl. jobswp_2025_homepage_snapshot)
```

## Custom blocks — why each one exists

Every custom block in this theme has been audited against the Core block library to make sure we're not reinventing something WordPress already ships. Each entry below is a contract: if Core grows the missing capability, the custom block is fair game to delete.

| Block | Why it can't be Core today |
|---|---|
| `job-browser` | Filter pills + interactive grid driven by the Interactivity API, with server-rendered initial state from the shared `jobswp_2025_homepage_snapshot()` helper. `core/query` doesn't expose a directives-driven filter UI. |
| `archive-header` | Mixes `core/query-title`-style title rendering with a job-count, RSS link, and the column labels that head the `job-list` table. Could become a pattern + `core/query-title` once we're willing to inline the RSS link as a small helper. **Audit candidate for a follow-up PR.** |
| `job-list` | Server-rendered table with a custom row layout (date / title / type / location). Could become `core/query` + `core/post-template` + `core/post-meta`, but the plugin's `jobswp_get_job_meta()` returns HTML for some fields (auto-linked locations) that `core/post-meta` doesn't render. **Audit candidate for a follow-up PR** that adds a `render_block_core/post-meta` filter. |
| `job-meta-card` | Five plugin-meta fields on the single-job sidebar with a "skip the row if empty" rule. `core/post-meta` always renders a row even when the value is blank, so a custom block stays the cleanest option until Core grows conditional rendering. |
| `menu-toggle` | Pairs with `parts/mobile-overlay.html` and `js/mobile-overlay.js` to give us an editable mobile-overlay template part. `core/navigation`'s built-in `overlayMenu` can't be edited as a template part. |
| `open-to-work-candidates` | External REST call to `profiles.wordpress.org` plus its own pagination. Out of scope for `core/query` (different post-type / external data). |

Two former blocks were replaced with patterns + Core blocks once the audit confirmed they were doing nothing Core didn't already handle:

- **`sidebar-category-list`** → `patterns/sidebar-category-list.php` using `core/categories` with `taxonomy: 'job_category'`.
- **`remove-job-form`** → `patterns/remove-job-form.php` (server-side notice rendering plus `core/html` for the form, since the plugin's `maybe_remove_job()` action handles submission).

## Feedback

Open an issue with what you tested, what worked, and what didn't. Screenshots welcome.

## License

GPL-2.0-or-later. See [LICENSE](./LICENSE).
