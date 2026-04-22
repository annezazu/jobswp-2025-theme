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
- **Mobile** — `core/navigation`'s built-in mobile overlay replaces the old hand-rolled menu toggle.
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
├── parts/                          # header.html, footer.html
├── patterns/                       # block patterns (PHP)
├── blocks/                         # six dynamic blocks
│   ├── job-browser/                # homepage: stats + filter pills + grid, Interactivity API
│   ├── job-meta-card/              # single job sidebar
│   ├── archive-header/             # category / search header
│   ├── job-list/                   # archive job list table
│   ├── sidebar-category-list/      # "Position Types" sidebar
│   └── remove-job-form/            # remove-a-job form
├── content-post-job.php            # classic template part (plugin couples to this path)
├── content-post-job-success.php    # classic template part (plugin couples to this path)
├── content-single.php              # classic template part (plugin couples to this path)
└── inc/template-tags.php           # helpers used by the above
```

## Feedback

Open an issue with what you tested, what worked, and what didn't. Screenshots welcome.

## License

GPL-2.0-or-later. See [LICENSE](./LICENSE).
