# Barangay System — MVC Conversion

This is your original application restructured into a plain-PHP MVC
layout. No framework was introduced — just organized folders and
classes, per your request.

## What changed

```
app/
  Config/
    config.php          ← was root config.php (DB/SMTP constants)
  Core/
    bootstrap.php        ← replaces secure_header.php + conn.php + the
                            include('classes/xxx.class.php') calls that
                            used to sit at the top of every page
    Database.php          ← replaces classes/conn.php (see note below)
    Controller.php         ← base class every Controller extends
    Security.php           ← was classes/security.php (session, CSRF,
                              rate limiting, role-guard helpers, headers)
    Mailer.php              ← was classes/mailer.php
    pagination_helper.php    ← was root pagination_helper.php
  Models/
    BMISModel.php     ← was classes/main.class.php   (class BMISClass)
    ResidentModel.php  ← was classes/resident.class.php (class ResidentClass)
    StaffModel.php       ← was classes/staff.class.php   (class StaffClass)
  Controllers/
    17 controllers grouping the ~115 pages by feature — see below.
  Views/
    One .php view per page (the HTML/markup half of each old page),
    organized into subfolders that match their controller
    (admin/, resident/, auth/, form/, ...), plus Views/layouts/ for
    the shared sidebar/topbar/navbar partials every page used to
    include (dashboard_sidebar_start.php, dashboard_sidebar_end.php,
    dashboard_sidebar_start_sk.php, resident_navbar.php).
```

Every original root filename (`admn_dashboard.php`,
`resident_homepage.php`, `login.php`, `ajax_complaints.php`, ...) is
now a **thin stub**, e.g.:

```php
<?php
define('BMIS_ROLE_REQUIRED', 'admin_dashboard');
require_once __DIR__ . '/app/Core/bootstrap.php';
(new AdminController())->dashboard();
```

This means **every existing URL, bookmark, AJAX call, and form
action keeps working exactly as before** — nothing on the frontend
(JS, `fetch()` calls, `<form action="...">`, hardcoded filenames)
needed to change.

## How a page's logic got split

For every page, the PHP logic that ran *before* the HTML started
(queries, validation, role checks, redirects) became a Controller
method. The HTML/markup that followed became a View file, included
at the same point the original page would have started rendering
markup. Pages with no HTML at all (pure AJAX/JSON endpoints like
`ajax_complaints.php`, `search_resident.php`) became controller-only
actions with no view.

Because the original code was one long procedural script (not
functions), it relied on `$conn`, `$bmis`, `$residentbmis`,
`$staffbmis`, and `$userdetails` simply being in scope everywhere.
Each generated controller method re-declares these from
`$this->conn` / `$this->bmis` / etc. at its very top, so the rest of
the moved code — completely unmodified — works exactly as it did
before.

## Controller groups

| Controller | Covers |
|---|---|
| `AuthController` | login, logout, forgot password, security question setup, admin creation/password change |
| `PageController` | home/index, 403, 404, 500, offline |
| `AdminController` | dashboard, settings, archive, activity logs, login history, password reset requests, announcements |
| `AdminResidentController` | resident CRUD, pending approvals, household/voter/resident search |
| `AdminReportController` | the `admn_table_*` demographic/report pages and their search endpoints |
| `AdminCertificateController` | barangay clearance, ID, business permit, good moral/guardianship/indigency/livestock/residency certificates (admin side) |
| `AdminStaffController` | staff CRUD |
| `ComplaintController` | admin + resident complaint pages, complaint AJAX |
| `MessageController` | admin/resident messaging, chat AJAX, notifications |
| `BudgetController` | admin + resident budget pages |
| `SoloParentController` | admin/resident solo-parent pages + form |
| `YouthController` | admin/resident youth profile pages |
| `SKController` | SK dashboard, announcements, enrollment, programs, youth records |
| `ResidentController` | resident homepage, profile, registration, change password, announcements, programs |
| `ServiceController` | resident-facing "request a service" pages |
| `FormController` | printable certificate/ID/permit PDF forms |
| `ApiController` | misc AJAX/polling endpoints (announcements, FCM tokens, SSE stats) |

## One behavioral improvement (worth knowing about)

The old `classes/conn.php` connected to the database **immediately**
the moment it was `require`'d — so if the DB was ever unreachable,
*any* page that included it died instantly, even pages that didn't
actually need the DB for that particular request.

The new `Database.php` connects **lazily**: the connection only
opens the first time something actually calls a method on `$conn`.
Static/error pages (404, 403, 500, offline) never touch it, so they
now render correctly even if the database is down. Pages that do use
`$conn` behave exactly as before — they die with the same
"Failed to connect to database!" message if the DB is unreachable.

## What I verified

- Every `.php` file in the project (100+ files) passes `php -l` with
  no syntax errors.
- Static/error pages (`/`, `404.php`, `403.php`, `500.php`,
  `offline.php`) render fully without touching the database.
- Role-guarded pages (`admn_dashboard.php`, `resident_homepage.php`,
  etc.) correctly redirect when there's no session — the guard logic
  moved into `app/Core/bootstrap.php` behaves identically to the old
  `secure_header.php`.
- I could **not** fully exercise database-backed pages end-to-end in
  this sandbox (no `pdo_mysql` driver installed, and your live DB
  host isn't reachable from here) — those pages' *business logic* is
  untouched (moved verbatim), but I'd recommend testing a handful of
  the heavier flows (resident registration, certificate requests,
  staff CRUD) on your actual server before fully trusting this in
  production.

## Not converted (left as-is, intentionally)

- `libs/PHPMailer`, `vendor/`, `uploads/`, `css/`, `js/`, `icons/`,
  asset folders — untouched, since relative asset URLs in HTML
  resolve against the *browser's request URL* (still
  `/admn_dashboard.php` etc.), not the server filesystem, so moving
  PHP files around doesn't affect them.
- A handful of `*_search.php` fragment pages are still `include()`d
  by bare filename from other pages that reuse them as HTML
  fragments (e.g. `admn_table_voters.php` includes
  `admn_table_voters_search.php`). Since both are still valid
  standalone stub files at their original root paths, this keeps
  working unchanged — but if you want, these could be refactored
  further into shared View partials or Model methods later.

## Removed from this delivered copy

`.env`, `firebase-service-account.json`, and `mail_debug.log` were
excluded — they contain live credentials/secrets that shouldn't be
redistributed. Your DB/SMTP config now lives in
`app/Config/config.php`; re-add your Firebase service account file
at the project root (referenced by `app/Models/BMISModel.php`) and a
fresh `.env` as needed.
