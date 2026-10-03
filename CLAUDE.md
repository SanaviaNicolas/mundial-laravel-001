# CLAUDE.md

Project: **Visciano 82** (ex "Mundial 82") — multipage website for a pizzeria-restaurant.
Technical identifier: `mundial` (repo, database, slugs, service names). Public name "Visciano 82" is used for `APP_NAME`, titles and content.
Stack: PHP 8.5, Laravel 13, Filament 5, PostgreSQL, PHPUnit, Pint, Larastan, Vite + Tailwind.

## Read first
- **At the start of EVERY prompt, read `CLAUDE.md` and `README.md`** (then the relevant pages in `docs/`).

## Workflow rules
- ALWAYS use the superpowers skills: brainstorming/planning before implementing, TDD, verification before declaring work done.
- Strict TDD: write the failing test first, watch it fail for the right reason, then write the code. No feature without a test.
- Minimal, clean code. No duplication, no over-engineering, no unnecessary dependencies.
- Follow current Laravel / PHP / Filament best practices: check the official documentation for the versions in use, never rely on memory.
- Do not add Laravel Boost or other agent tooling dependencies unless asked.

## Git rules
- Small, atomic commits, Conventional Commits, in English. Commit freely when `composer check` is green.
- NEVER `git push`: the user always pushes manually. When it is time, tell the user to do it and give the exact command.
- NEVER run anything that needs passwords, passphrases or interactive credentials (push, fetch, ssh-add, ...). If needed, STOP and tell the user exactly which command to run.
- Never force push. Never commit `.env`, `vendor`, `node_modules`.
- The repository is PUBLIC: never commit secrets. Real credentials live only in `.env`; `.env.example` has placeholders.
- Remote `origin` uses the SSH alias of the correct account: `git@SanaviaNicolas:SanaviaNicolas/mundial-laravel-001.git` (NOT `git@github.com`).
- We work directly on `main`. Before pushing, the user runs `git pull --rebase`; CI must be green.
- Before declaring a task done, `composer check` must ALSO pass on a clean clone (clone the local repo into a temp folder, copy `.env`, `composer install`, `composer check`, delete the folder): CI starts only from tracked files (git does not track empty directories).
- Before every commit: `git status` / `git diff`, then `composer check` (Pint + Larastan + tests) must pass.

## Local environment
- The site is served by Laravel Herd (NOT `php artisan serve`): `herd link` + `herd secure` from the project folder (no name argument) → https://mundial-laravel-001.test. Never run `herd` commands: tell the user what to run.
- The admin panel path is secret: it comes from `ADMIN_PATH` in `.env` (empty = panel disabled), read via `config('admin.path')`. NEVER write the real value anywhere in the repo, docs, tests or commit messages (public repo); tests must use `config('admin.path')`. Do not expose the path in `robots.txt`. The panel is always `noindex` via `X-Robots-Tag`. See `docs/decisioni/0003-pannello-admin.md`.

## Commands
- `composer test` — tests (PHPUnit, database `mundial_testing`, `RefreshDatabase`)
- `composer lint` — Pint (check only); `vendor/bin/pint` fixes
- `composer analyse` — Larastan (level 5)
- `composer check` — lint + analyse + test

## Documentation
- `/docs/` must always be up to date: every change to content, development, technical or graphic choices updates the docs in the SAME commit.
- `/docs/` is public: no sensitive data.
- README.md and `/docs/` in Italian; code, comments and commits in English; site content in Italian (locale `it`, timezone `Europe/Rome`).
- Technical decisions are recorded as ADRs in `docs/decisioni/`.
- Implementation plans are NOT committed in `/docs/`.

## Priorities
- **SEO and indexing** are fundamental, including "AI friendly" practices. Semantic HTML, correct heading hierarchy, exactly one H1 per page, server-side rendering (content readable without JS), title/meta description/canonical per page, Open Graph, sitemap.xml, robots.txt, JSON-LD (Restaurant/Pizzeria, Menu, opening hours), clean URLs, optimized images with alt, Core Web Vitals. AI-friendly: clear textual content (menu, hours, address in HTML, never only in images), structured data, evaluate `llms.txt`, explicit AI-crawler policy in robots.txt. Non-production environments must be `noindex`. Status of each item: `docs/seo/checklist.md`.
- **Mobile first**: correct viewport meta, adequate touch targets, no horizontal scroll, test layouts at mobile viewport.

## Task wrap-up
Every task ends with a short, structured summary (max ~15 lines): done, decisions, open problems, what the user must do, files to paste into the chat.
