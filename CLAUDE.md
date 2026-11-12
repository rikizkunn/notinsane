# CLAUDE.md

Guidance for Claude Code when building the application in this repository.

> Note: Laravel's default Boost bootstrap lives in `AGENTS.md`. This file is the project-specific guide and takes precedence for this app.

## Project overview

A web-based Production Checksheet Information System for PT Global Anugerah Setia (GAS Electronics), an electronics manufacturer in Purwakarta. The app replaces paper-based production checksheets.

Problems it solves (from the current manual system):

- Handwritten records are error-prone and hard to read.
- Daily/monthly recaps are re-typed one by one, which is slow.
- Paper archives can be damaged, wet, or lost.
- Supervisors and management cannot monitor production results in real time.
- Finding old inspection data means digging through physical archives.

The new system centralizes recording, storage, monitoring, verification, and reporting in one web app. Operators enter digital checksheets, data is saved to the database, the dashboard updates immediately, supervisors verify, then recap reports are generated automatically.

Development follows the source report: Waterfall method (analysis, design, implementation, testing, maintenance) with Black Box testing.

## Tech stack

| Layer | Choice | Notes |
|---|---|---|
| Language | PHP 8.5 | Provided by Flock |
| Framework | Laravel 13 | Latest stable; routing, Eloquent ORM, migrations, validation |
| Database | MySQL 9 | Served by Flock on 127.0.0.1:3306 |
| Views | Blade + Bootstrap 5 | Server-side templates, no SPA |
| Auth | Custom session auth | Username + password login; access via role middleware. Breeze skipped (it ships Tailwind, which fights Bootstrap) |
| PDF reports | barryvdh/laravel-dompdf | Laporan recap exports to PDF; recap page is also filterable by area and paginated |
| Asset build | Vite | Laravel default; UI currently loads Bootstrap 5 from CDN |

Scope limits (from the report's constraints): no production planning, no inventory, no accounting, no prediction or AI. Reports only process user-entered data.

## Local environment (Flock)

PHP, Composer, Node, npm, and MySQL all come from Flock, not system PATH. Flock's binaries are in `C:\Users\zethx\AppData\Roaming\Flock\bin`; put that directory first on PATH before running any `php`/`composer`/`artisan` command, otherwise a stale Herd PHP is used and crashes.

- Database: MySQL on `127.0.0.1:3306`, user `root`, no password.
- App database: `checksheet_produksi` (already created).
- Create more databases with `flock services create-db mysql <name>`.

### Serving the site

The app is served by Flock at **http://app.test** (run `flock link` once in the project root; already done). Use that URL, not `php artisan serve`.

Why not `artisan serve`: it runs the PHP built-in server as the `cli-server` SAPI, which under Flock has no `pdo_mysql` driver, so every DB request 500s with "could not find driver". Flock's FPM (behind `app.test`) has the driver. CLI commands (`migrate`, `test`, `tinker`) run as the plain `cli` SAPI and work fine.

### Seeded accounts

`php artisan migrate:fresh --seed` creates one account per role, all with password `password`:

| Username | Role |
|---|---|
| admin | administrator |
| operator | operator |
| supervisor | supervisor |
| manajemen | manajemen |

## Roles and access

Four user roles. The `role` column on `users` uses: `administrator`, `operator`, `supervisor`, `manajemen`.

| Role | Responsibility |
|---|---|
| Administrator | Manage user accounts and production master data |
| Operator / Engineer | Inspect machines/processes, enter results into digital checksheets |
| Supervisor | Monitor dashboard, verify and approve checksheets |
| Manajemen | Access recap reports for evaluation |

Access matrix per feature:

| Feature | Administrator | Operator | Supervisor | Manajemen |
|---|---|---|---|---|
| Login/logout | yes | yes | yes | yes |
| Manage users | yes | — | — | — |
| Manage production data | yes | — | — | — |
| Input checksheet | — | yes | — | — |
| View inspection data | yes | yes | yes | — |
| Monitoring dashboard | — | — | yes | yes |
| Verify checksheet | — | — | yes | — |
| Approve/reject checksheet | — | — | yes | — |
| Search historical data | yes | yes | yes | yes |
| Generate/print reports | — | — | — | yes |

Enforce restrictions with role middleware on the server, not just by hiding buttons in views.

## Database schema

Five tables derived from the report's Class Diagram. Table names are snake_case plural; Eloquent models are singular.

### users

| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| nama | varchar | |
| username | varchar unique | Used for login |
| password | varchar | bcrypt hash |
| role | enum | administrator, operator, supervisor, manajemen |
| timestamps | | created_at, updated_at |

### data_produksi

| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| tanggal | date | |
| area | varchar | Production work area |
| mesin_peralatan | varchar | Machine or equipment inspected |
| keterangan | text nullable | |
| created_by | bigint FK → users.id | Administrator who recorded it |
| timestamps | | |

### checksheets

| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| data_produksi_id | bigint FK → data_produksi.id | |
| user_id | bigint FK → users.id | Operator who entered it |
| tanggal_pemeriksaan | date | |
| hasil_pemeriksaan | text | |
| catatan | text nullable | |
| status | enum | draft, dikirim, disetujui, ditolak |
| timestamps | | |

### verifikasi

| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| checksheet_id | bigint FK → checksheets.id | |
| supervisor_id | bigint FK → users.id | Supervisor who verified |
| tanggal_verifikasi | date | |
| status_persetujuan | enum | disetujui, ditolak |
| catatan | text nullable | |
| timestamps | | |

### laporan

| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| periode | varchar | e.g. "harian 2025-11-07" or "bulanan 2025-11" |
| tanggal_dibuat | date | |
| id_pembuat | bigint FK → users.id | |
| timestamps | | |

Relationships:

```
users (1) --- (*) data_produksi      [created_by]
users (1) --- (*) checksheets         [user_id, operator]
users (1) --- (*) verifikasi          [supervisor_id]
users (1) --- (*) laporan             [id_pembuat]
data_produksi (1) --- (*) checksheets
checksheets (1) --- (0..1) verifikasi
```

## Class Diagram to model mapping

| Class (report) | Eloquent model | Table |
|---|---|---|
| Pengguna / User | User | users |
| DataProduksi | DataProduksi | data_produksi |
| Checksheet | Checksheet | checksheets |
| Verifikasi | Verifikasi | verifikasi |
| Laporan | Laporan | laporan |

The class-diagram methods map to controller actions, not model methods: `login()`, `kelolaAkun()`, `inputPemeriksaan()`, `verifikasiChecksheet()`, `buatLaporan()`, `tambahData()`, `ubahData()`, `generateLaporan()`, `cetakLaporan()`.

## Core business flow

### Checksheet status

```
draft --> dikirim --> disetujui
                 \--> ditolak --> (operator revises) --> dikirim
```

- Operator creates a checksheet with status `draft`, then submits it for verification (`dikirim`).
- Supervisor reviews `dikirim` records. If correct, status becomes `disetujui` and a `verifikasi` row is created with `status_persetujuan = disetujui`.
- If incorrect, status becomes `ditolak` with a note; the operator revises and resubmits.

### Login-to-report flow

1. User logs in; the system validates the account. Invalid accounts show an error; valid ones open a dashboard scoped to the role.
2. Operator inspects the machine/process, enters results into the digital checksheet, the system saves to the database.
3. The monitoring dashboard shows the latest inspection data for supervisor and management.
4. Supervisor verifies and approves or rejects.
5. Approved data feeds daily or monthly recap reports, printable to PDF or exportable to Excel.

## Project structure

Follow standard Laravel conventions:

```
app/
  Models/            User, DataProduksi, Checksheet, Verifikasi, Laporan
  Http/Controllers/  per feature (Auth, Pengguna, DataProduksi, Checksheet, Verifikasi, Laporan, Dashboard)
  Http/Middleware/   role middleware
database/
  migrations/        one migration per table
  seeders/           seed initial account per role + sample production data
resources/views/     Blade per module, shared Bootstrap layout
routes/web.php       routes grouped by role middleware
```

## Development commands

Run these from the project root with Flock's bin on PATH.

```powershell
$env:PATH = "C:\Users\zethx\AppData\Roaming\Flock\bin;$env:PATH"
composer install
php artisan migrate            # database checksheet_produksi already exists
php artisan migrate --seed     # once seeders are written
php artisan test               # run tests (sqlite in-memory, set in phpunit.xml)
flock link                     # serve at http://app.test (do this instead of artisan serve)
```

Do not use `php artisan serve` for this project (its `cli-server` SAPI lacks `pdo_mysql` under Flock). Open http://app.test instead.

The project is scaffolded (Laravel 13), `.env` points at MySQL, all migrations have run, the database is seeded, and the 5 domain tables + auth + role-gated CRUD/flow + Bootstrap UI + feature tests (19 passing) are in place.

## UI copy language

All user-facing text (buttons, labels, page titles, placeholders, flash messages, validation errors) is written in Indonesian. Keep it casual and natural, the way a coworker talks, not stiff officialese.

Rules:

- Prefer short imperative verbs on buttons: `Masuk`, `Simpan`, `Kirim`, `Setujui`, `Tolak`, `Cetak`, `Keluar`, `Batal`, `Hapus`, `Ubah`.
- Drop the formal `Anda` and `Silakan` in most places. "Masukkan username" beats "Silakan masukkan username Anda".
- No stiff phrasing like "Mohon menunggu sejenak" or "Data telah berhasil disimpan ke dalam sistem". Say "Tunggu sebentar" and "Data tersimpan".
- Keep domain terms as-is: checksheet, verifikasi, rekapitulasi.
- One consistent tone across the app. Don't mix formal and casual.

Examples:

| Context | Use | Avoid |
|---|---|---|
| Login button | Masuk | Silakan Login |
| Save success | Data tersimpan | Data telah berhasil disimpan ke sistem |
| Delete confirm | Yakin mau hapus data ini? | Apakah Anda yakin ingin menghapus data ini? |
| Empty state | Belum ada data | Tidak ada data yang tersedia saat ini |
| Validation | Username wajib diisi | Kolom username tidak boleh kosong |
| Approve action | Setujui | Berikan Persetujuan |
| Logout | Keluar | Silakan Logout dari sistem |

## Conventions and rules

- Tables snake_case plural (`data_produksi`), models singular (`DataProduksi`), columns snake_case.
- Keep domain terms as-is: checksheet, pengguna, verifikasi, rekapitulasi. Do not translate column or role names.
- Use real foreign keys with constraints, not bare id columns.
- Validate input in Form Requests, not inside controllers.
- Enforce access control with role middleware on the server side.
- Test the main functions with a Black Box approach: login, checksheet input, verification, report generation.
- Write comments only where intent is non-obvious; avoid comments that just restate the function name.
