# AGENTS.md

## Layout

- The git root is this directory, but the Laravel app lives in `seb/`. Run all app commands from `seb/`.
- Laravel 13 / PHP 8.3+ Blade app (Portuguese UI) for school/library management: users own turmas, turmas have alunos, alunos optionally reference livros. PDFs use `barryvdh/laravel-dompdf`.

## Run / database

- Preferred environment: `docker compose up -d --build` from `seb/`. App: `http://localhost:8000`; Mailpit UI: `http://localhost:8025`. Source is bind-mounted into the container.
- Use Docker for anything that touches the database: `docker compose exec app php artisan ...`.
- The host PHP in this workspace has no `pdo_pgsql`; the container does. The existing `.env` points to a remote Supabase PostgreSQL database, while `.env.example` defaults fresh setups to local SQLite.
- `php artisan migrate` / `migrate --force` affects whatever is in `.env`. Do not run destructive database commands against the remote Supabase DB. `.env` is gitignored but contains live credentials; never print or commit it.
- `composer setup` creates `.env` from `.env.example` when missing, creates `database/database.sqlite`, runs migrations, then installs/builds the frontend. If an existing `.env` points at Supabase, setup migrates that database instead.

## Verify

- `composer test` / `php artisan test` passes. Tests use the in-memory SQLite configuration in `phpunit.xml` and `RefreshDatabase`. There are no seeders or factories; tests create data inline.
- Format PHP with `vendor/bin/pint` (use `--dirty` to limit changes to modified files).
- `npm run build` works. `resources/js/app.js` is currently a placeholder because the layouts still load Alpine, Tailwind, and SweetAlert2 from CDNs; do not assume a compiled Vite bundle is rendered.
- Keep `npm audit` clean; the lockfile is currently free of reported vulnerabilities.

## Code map

- All HTTP routes are in `routes/web.php`; `routes/api.php` is empty. Guest routes handle login, registration, and password reset. Email verification/logout require `auth`; turmas, alunos, livros, and PDF routes require `auth` + `verified`.
- Controllers and models use PSR-4 PascalCase (`AlunoController`, `Aluno`, `Livro`, ...). Keep new files consistent; Docker/Linux is case-sensitive.
- `Turma` has a global scope filtering by `Auth::id()`, sets `user_id` on create, and soft-deletes/restores its `alunos`. `Aluno` has a global scope filtering by the owning turma. `TurmaPolicy` and `AlunoPolicy` are enforced through `authorize()` in the controllers. `Livro` is shared across users and has no ownership policy.
- Routes and Blade views rely on route names. `resources/views/layouts/app.blade.php` yields `login`, `createUser`, `forgot-password`, `reset-password`, `turmas`, `alunos`, and `livros` sections, and renders the aluno modal for authenticated users.
- Turmas and alunos have nested CRUD; livros has full CRUD at `/livros` plus a PDF report. The `2026_11_06_000000_add_estoque_and_status_to_livros_table.php` migration adds `estoque` and `status` to `livros`.
- Auth: `User implements MustVerifyEmail`. Verification and reset emails use the custom Portuguese notifications/templates in `app/Notifications` and `resources/views/{email,vendor/mail}`. Mail is sent to Mailpit. `MagicLinkController` and `MagicLoginNotification` are unused placeholders with no routes.
- `app/Services/Login.php` is legacy dead code (JSON-file auth); the web login uses Laravel's session guard.

## Conventions

- UI copy, validation errors, and flash messages are in Portuguese; keep new messages consistent.
- `User` uses Laravel 13 `#[Fillable]` / `#[Hidden]` attributes; other models use `$fillable`. Follow the existing style of the file being edited.
- `2026_10_21_010214_add_livro_id_to_alunos_table.php` is intentionally idempotent: `alunos` already receives `livro_id` in its create migration, so the guard avoids a duplicate column.
- PDF reports use `Pdf::loadView(...)` with templates in `resources/views/{turma,aluno,livro}/pdf.blade.php`; keep the paper orientation already set by each controller.
