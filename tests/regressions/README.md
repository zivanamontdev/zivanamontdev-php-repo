# September 2026 regression checks

Run `php tests/regressions/run.php` for pagination ranges, invalid pages, search,
page-size limits, visitor classification and location aggregation. The doubles use
synthetic data and never load `.env` or connect to MySQL, SMTP or R2.

For browser verification, from the repository root run:

```sh
php -S 127.0.0.1:8013 -t public tests/regressions/router.php
```

Fixtures render the actual application views and shared JavaScript. Use `count`
to exercise boundaries:

- `/admin/registrations`: 23 rows; next pages must contain 10, 10 and 3 rows.
- `/activities?count=6` and `?count=7`: initially six programs, then reveal the remainder.
- `/activities-gallery?program_id=1&count=9` and `&count=10`: initially nine photos;
  check preview for a newly revealed photo.
- `/profile?count=15`: 12 staff on desktop, six on mobile; all 15 must be reachable.
  Check `count=6` and `count=12` for absent buttons at their respective breakpoints.
- `/articles?count=15`: desktop initially seven articles, mobile three. Reveal all,
  then verify the button disappears. Repeat after navigating from another page.
- `/`: open/close testimonial, navigate to Activities, return Home, open/close
  again; repeat with browser back/forward and Escape.

Stop the fixture server when finished. It is only for local development.

## Applying the location filter in production

Deploy `app/helpers/geoip.php`, `app/models/Analytics.php`, the dashboard view and
`scripts/warm-location-cache.php` along with the other changed files. No database
migration is needed. Run this command on the server after deploying:

```sh
php scripts/warm-location-cache.php
```

It reads distinct historical visit IPs and enriches the existing GeoIP cache with
country and hosting metadata, respecting the provider's rate limit. It does not
delete or update visit rows. It can be rerun; resolved entries are reused and
unresolved entries are retried. The existing `storage/cache` directory must remain
writable and should not be replaced during deployment.

New visits populate the metadata through normal tracking. The Locations panel
includes only resolved Indonesian cities, excludes identified hosting IPs and
recognized automated user agents, then sums eligible visits and ranks cities.
Unknown/legacy string-only cache entries are excluded until enriched. The dashboard
does no outbound GeoIP requests itself, and its other metrics remain unchanged.

GeoIP and user-agent classification are estimates; undetected bots, VPNs, mobile
IP routing and stale IP allocations can still affect location accuracy. The filter
does not prove that every included visitor is human. API fields and rate limits:
https://ip-api.com/docs/api:json
