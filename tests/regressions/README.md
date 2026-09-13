# September 2026 regression checks

Run `php tests/regressions/run.php` for pagination ranges, invalid pages, search,
page-size limits, visitor classification and location aggregation. The doubles use
synthetic data and never load `.env` or connect to MySQL, SMTP or R2.

Run `php tests/regressions/location-refresh.php` for bounded batches, cursor
retries, CSRF rotation/replay and request-method validation. Run
`node tests/regressions/location-refresh-client.cjs` for duplicate-click
protection, token forwarding, selected-period refresh and failure recovery.

For browser verification, from the repository root run:

```sh
php -S 127.0.0.1:8013 -t public tests/regressions/router.php
```

Fixtures render the actual application views and shared JavaScript. Use `count`
to exercise boundaries:

- `/admin/registrations`: 23 rows; next pages must contain 10, 10 and 3 rows.
- `/activities?count=6`: no reveal button. For counts 9, 10, 11 and 12, desktop
  starts at six, reveals three per click, then switches to **Tampilkan Lebih Sedikit**.
  Collapse restores six; repeat the cycle. Tablet adds two per click, mobile one.
- `/activities-gallery?program_id=1&count=9` and `&count=10`: initially nine photos;
  check preview for a newly revealed photo.
- `/profile?count=15`: 12 staff on desktop, six on mobile; all 15 must be reachable.
  Check `count=6` and `count=12` for absent buttons at their respective breakpoints.
- `/profile?count=15&facilities=7`: mobile facilities start at five, add one per
  click, then collapse to five. Desktop shows all facilities without a button.
- `/articles?count=10`: desktop initially seven articles, mobile three. Reveal one
  list row per click, then collapse to the initial count. Repeat after AJAX navigation.
- Every reveal button stays available when all items are visible, with the label
  **Tampilkan Lebih Sedikit**. It is absent/hidden only when total <= initial.
  Verify the button wrapper has a 32px top margin on desktop and mobile.
- `/`: open/close testimonial, navigate to Activities, return Home, open/close
  again; repeat with browser back/forward and Escape.
- `/admin/dashboard`: click **Perbarui** in Locations twice sequentially. Both
  runs must finish successfully and re-enable the button. This fixture uses an
  empty visit history and makes no external GeoIP requests.

Stop the fixture server when finished. It is only for local development.

## Applying the location filter in production

Deploy the changed files, including the dashboard controller, route, view,
`app/helpers/LocationCacheWarmer.php`, `app/helpers/geoip.php` and
`public/assets/js/admin-location-refresh.js`. No SSH or database migration is required.

Sign in to Admin, open Dashboard, then click **Perbarui** beside the location icon
in the **Lokasi** panel. Keep the page open until completion. Requests process up
to 25 cached entries and at most one external lookup at a time, with a shared
provider cooldown across admin tabs, visitors and CLI workers. The button is
immediately disabled while running; progress and retry delays appear in the panel.
POST requests require the existing admin login and a rotating CSRF token.

The process reads historical visit IPs and enriches the existing cache. It never
changes/deletes visit rows. Reopening the page and clicking again reuses completed
entries and retries unresolved ones. The `storage/cache` directory must be writable.
Do not expose the CLI script at a public URL; it remains CLI-only. The optional
`php scripts/warm-location-cache.php` command remains available for SSH users.

New visits populate the metadata through normal tracking. The Locations panel
includes only resolved Indonesian cities, excludes identified hosting IPs and
recognized automated user agents, then sums eligible visits and ranks cities.
Unknown/legacy string-only cache entries are excluded until enriched. Ordinary dashboard loading
does no outbound GeoIP requests; only the explicit refresh does, and its other metrics remain unchanged.

GeoIP and user-agent classification are estimates; undetected bots, VPNs, mobile
IP routing and stale IP allocations can still affect location accuracy. The filter
does not prove that every included visitor is human. API fields and rate limits:
https://ip-api.com/docs/api:json
