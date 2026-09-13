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
- `/admin/dashboard`: no location refresh control or background batch requests.
  Switching periods still updates the location panel.
- `/admin/management`: open Karyawan, then Tambah Karyawan. The role dropdown
  uses the application theme and includes Guru Daycare. Select it, close/reopen
  the modal, and verify the placeholder resets. Edit Guru Uji and verify Guru
  Daycare is preselected; changing the role must update the submitted field.
  Verify keyboard arrows/Enter/Escape and the existing compact dropdowns.

Stop the fixture server when finished. It is only for local development.

## Location filtering in production

Deploy the changed files; no SSH command or database migration is required.
The Locations panel directly uses cached country, city and hosting metadata.
It includes only identified Indonesian cities with non-hosting visitor traffic,
excluding recognized automated user agents. Cached results from earlier refreshes
remain usable. Unresolved and legacy city-only entries are excluded, rather than
launching a long historical lookup when the dashboard opens.

Normal new visits populate location metadata, within the existing provider limit.
Recognized bots skip location lookup. Dashboard loading and period changes never
call the GeoIP provider; overall visit metrics are preserved. The removed refresh
endpoint and JavaScript are no longer used. The optional CLI maintenance script
remains CLI-only and is not required for admin operation.

GeoIP and user-agent classification are estimates; this filter cannot prove that
every included visitor is human. The storage/cache directory must stay writable.
