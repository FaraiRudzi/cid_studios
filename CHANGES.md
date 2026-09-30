# CID Studios - evidence-integrity update

**Read this first.** None of this code has been executed: the sandbox it was written in has no PHP,
Composer or database. It was checked by reading, by diffing against your originals, and by a structural
lint (balanced brackets, unused imports, Blade directive pairing). `composer.json` was not supplied, so the
Filament/Laravel versions are inferred from your existing imports. **Apply to a staging copy first, run the
tests, and only then production.**

## What changed and why

| Problem found in review | Fix |
|---|---|
| Every save deleted and recreated all media rows; re-logged old uploads | `syncCaseMedia` is append-only. Existing media is never touched. |
| Evidence photos served from the public disk (no login needed) | Private `evidence` disk, served only via `cases.media.file` after an authorisation check |
| No proof a file is unchanged | SHA-256 recorded at upload, written into the audit entry, re-checked in exhibits and by `evidence:verify` |
| Audit log editable/deletable; deleted with the case | `CaseLog` is append-only; cases cannot be deleted; foreign keys are RESTRICT |
| Only creation/reassignment were logged | Every tracked field change logged with before and after; status, people, uploads, removals, exhibits logged |
| `ViewCase` fabricated back-dated `MEDIA_UPLOADED` entries on page view | Removed. Log entries are written when the event happens. |
| `uploaded_by` and file paths trusted from the browser | Uploader = session user; paths must be existing files inside `case-media/`, unclaimed by any case |
| Closed cases still editable | CLOSED/ARCHIVED cases are read-only; only an admin can reopen (with a logged reason) |
| Reassign / status change unexplained | Admin actions require a reason, stored in the log |
| Shared `Person` rows silently edited across cases | Person rows are never modified; a change creates a new row; participant changes are logged |
| Reference-number race | Unique index + automatic retry, in one transaction with the case and its log entry |
| SVG accepted by `image/*` (script risk) | Explicit list of image/video types |
| `maxSize(524288000)` (KB) = ~500 GB | 512000 KB = 500 MB |
| `--password` on CLI (shell history) | Prompted, confirmed, 12+ characters |
| Users could be deleted, nulling audit attribution | Users are deactivated, not deleted; stations with cases cannot be deleted |
| Blank password on user edit could null the column | `dehydrated()` only when filled |

## Files

**New:** `app/Exceptions/EvidenceProtectionException.php`, `app/Http/Controllers/EvidenceFileController.php`,
`app/Services/EvidenceStorage.php`, `app/Console/Commands/EvidenceMigrateLegacyCommand.php`,
`app/Console/Commands/EvidenceVerifyCommand.php`,
`database/migrations/2026_09_30_000001_harden_evidence_integrity.php`, `tests/Feature/EvidenceIntegrityTest.php`

**Replaced (full files):** `app/Models/{CaseLog,CaseModel,Media,User}.php`, `app/Observers/CaseObserver.php`,
`app/Console/Commands/CreateStudioUserCommand.php`,
`app/Filament/Resources/{CaseResource,UserResource,StationResource}.php`,
`app/Filament/Resources/CaseResource/Pages/{CreateCase,EditCase,ViewCase}.php`,
`config/filesystems.php`, `routes/web.php`, `routes/console.php`,
`resources/views/filament/case-media-grid.blade.php`,
`resources/views/filament/pages/{case-logs-modal,case-evidence-report,case-exhibit-report}.blade.php`

`config/filesystems.php` was built from the copy in your zip: diff it against your live one before overwriting.
`case-evidence-report.blade.php` is not referenced by any route or page in the code I was given; it is updated
anyway so it is correct if something else uses it.

**Delete:** `app/Models/Case_Logs.php` (byte-identical duplicate of `CaseLog.php`; the append-only guard would not
apply if it were ever loaded).

## Deploy steps (in this order)

1. **Back up** the database (`mysqldump`) and copy `storage/app/public/case-media/`. Choose a quiet window.
2. Copy the files over the project, keeping the paths. Delete `Case_Logs.php`.
3. `php artisan migrate:status`. Your live database was evidently not built purely by these migrations (it has
   `ARCHIVED`, no `stations.code`). If earlier migrations show *Pending*, do **not** run a bare `migrate`. Run only ours:
   `php artisan migrate --path=database/migrations/2026_09_30_000001_harden_evidence_integrity.php --pretend`
   read the SQL, then run it without `--pretend`. It widens `media.file_path` to TEXT, adds hash/removal columns and
   turns the cascading foreign keys into RESTRICT.
4. `php artisan optimize:clear`
5. `php artisan evidence:migrate-legacy` copies each file to the private disk, verifies the copy by SHA-256 and
   records a **baseline** hash (logged as `EVIDENCE_BASELINED`). Then `php artisan evidence:verify`. When both are clean:
   `php artisan evidence:migrate-legacy --delete-source` removes the old public copies.
   *Honest limit:* for items uploaded before this update the hash proves integrity from the migration onward, not from
   original capture. The log entry says so.
6. Add the scheduler cron so the nightly check runs: `* * * * * cd /path/to/app && php artisan schedule:run >> /dev/null 2>&1`
7. Make sure the web server cannot serve `storage/app/evidence` (it is outside `public/` by default; do not symlink it).
8. `php artisan test --filter=EvidenceIntegrityTest`

## Database privileges (strongly recommended, not done by code)

Application code cannot stop someone with SQL access editing `case_logs`. Run migrations as a separate account and
give the runtime account only what it needs. MySQL/MariaDB cannot subtract a table privilege from a schema-wide
grant, so grant per table:

```sql
CREATE USER 'studios_migrator'@'localhost' IDENTIFIED BY '<new strong password>';
GRANT ALL ON studios_db.* TO 'studios_migrator'@'localhost';

REVOKE ALL PRIVILEGES, GRANT OPTION FROM 'studios'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON studios_db.cache, studios_db.cache_locks, studios_db.sessions,
      studios_db.password_reset_tokens, studios_db.case_person TO 'studios'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON studios_db.stations TO 'studios'@'localhost';
GRANT SELECT, INSERT, UPDATE ON studios_db.users, studios_db.people, studios_db.cases,
      studios_db.media TO 'studios'@'localhost';
GRANT SELECT, INSERT ON studios_db.case_logs TO 'studios'@'localhost';
FLUSH PRIVILEGES;
```
Use `studios_migrator` only for `php artisan migrate`. Test on staging first.

## Behaviour changes your users will notice

- Photographers can add media but can no longer remove or replace saved media. Saved media is shown on the case page.
- Photographers can set only OPEN or PENDING_REVIEW. Closing/archiving/reopening is an admin action ("Change status").
- Admins get "Remove media": it needs a reason, hides the item, and keeps the record and file.
- "Reassign" now needs a reason. A closed case must be reopened before it can be reassigned.
- A closed or archived case rejects edits and new media.

## Not done / known limits

- **Two-factor authentication:** not added; the API differs by Filament version. Recommended for this system.
- **Tamper-evident log chaining** (each entry hashing the previous): not added. The database privileges above are the
  practical control for now.
- **Personal data** (ID numbers, phones, addresses) is still stored unencrypted.
- `EvidenceStorage::sha256()` reads the file through a local path. It assumes the evidence disk is local storage.
- If a save is rejected after the browser has uploaded files, the uploaded files remain on disk unlinked.
- ARCHIVED only works on MySQL/MariaDB (the enum is altered there); the SQLite test database keeps the 3-value enum.
- Livewire's default temporary-upload limit is 12 MB and there is no `config/livewire.php` in the code I received.
  Large photos/videos may be refused regardless of the 500 MB form setting; also check PHP `upload_max_filesize`,
  `post_max_size` and the web server body-size limit.
- The exhibit routes still live under `/admin/...` although the panel is at `/`; unchanged.

## Housekeeping outside this zip

`.env` was shared in this conversation, and `database/database.sqlite` and `police_officers.sql` were in what you
sent. Rotate the database password and `APP_KEY`, set `APP_DEBUG=false` / `APP_ENV=production`, serve over HTTPS,
and purge `police_officers.sql` from the Git history (or make the repository private) if it holds real data.
