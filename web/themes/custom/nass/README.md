# NASS Drupal Theme

USWDS (U.S. Web Design System) subtheme for the NASS Drupal site.

- **Base theme**: `uswds` (contrib)
- **Build tool**: Gulp 4 + Dart Sass (configured in `gulpfile.js`)
- **SCSS source**:   `assets/scss/styles.scss`  →  **compiled output**: `assets/css/styles.css`
- **USWDS SCSS**:    `uswds/scss/uswds.scss`    →  **compiled output**: `uswds/css/uswds.css` (rarely rebuilt)
- **JS source**:     `assets/js/scripts.js`     →  **compiled output**: `assets/js/scripts.min.js`

## Why the compiled CSS is committed

This theme has no automated build in CI. The files under `assets/css/` and `uswds/css/`
are pre-compiled and committed; Drupal loads them directly. **If you change a `.scss`
file but don't compile, the browser will see no change** — the SCSS is source-only.

## Prerequisites

- **Node.js 18 LTS recommended** (anything 14+ should work). Check with `node -v`.
- npm (bundled with Node).
- A terminal opened in this directory: `web/themes/custom/nass/`.

## First-time setup

```sh
npm install
```

Installs Gulp, Dart Sass, and supporting plugins into `node_modules/` (gitignored).
Only needed once per checkout (or after `package.json` changes).

## Compile SCSS once (most common task)

```sh
npx gulp build-sass
```

Reads `assets/scss/*.scss`, writes minified output to `assets/css/styles.css`
(with sourcemap). Then tell Drupal to pick up the new file:

```sh
ddev drush cr
```

Hard-refresh the browser (⌘-Shift-R / Ctrl-Shift-R) so it doesn't keep the
old `styles.css` from cache.

## Watch mode (during active SCSS work)

```sh
npx gulp watch-sass
```

Recompiles `styles.css` automatically every time you save a `.scss` file.
Leave it running in a terminal tab. You'll still need `ddev drush cr` once
after the first compile, and a browser hard-refresh.

## Rebuild USWDS base CSS (rare)

Only when something in `uswds/scss/` changes (e.g. you've updated USWDS settings):

```sh
npx gulp build-uswds
```

Reads `uswds/scss/uswds.scss`, writes `uswds/css/uswds.css`. Not part of the
watch task — run it explicitly when needed.

## Rebuild the JS bundle

```sh
npx gulp compile-js
```

Minifies `assets/js/scripts.js` → `assets/js/scripts.min.js`. Run after editing
the JS source.

## What to commit after compiling

After a successful build, commit **both** the source and the compiled output:

- Source: `assets/scss/**/*.scss`, `uswds/scss/**/*.scss`, `assets/js/scripts.js`
- Compiled: `assets/css/styles.css`, `assets/css/styles.css.map`,
  `uswds/css/uswds.css` (if rebuilt), `assets/js/scripts.min.js`

`node_modules/` is gitignored — do **not** commit it.

## Gulp tasks reference

| Task           | What it does                                                    |
|----------------|-----------------------------------------------------------------|
| `build-sass`   | Compile `assets/scss/*.scss` → `assets/css/` (one-shot)         |
| `watch-sass`   | Watch `.scss` files and re-run `build-sass` on save             |
| `build-uswds`  | Compile `uswds/scss/*.scss` → `uswds/css/` (one-shot)           |
| `compile-js`   | Minify `assets/js/scripts.js` → `assets/js/scripts.min.js`      |

> Note: the `default` and `watch` tasks in `gulpfile.js` chain `build-sass`,
> `watch-sass`, and `compile-js` together. Because `watch-sass` never exits,
> `compile-js` never runs in that chain. Run `compile-js` separately when
> you need it.

## Troubleshooting

**"I changed an `.scss` file and nothing changed in the browser."**
You didn't compile. Run `npx gulp build-sass`, then `ddev drush cr`, then hard-refresh.

**"`gulp: command not found`."**
Use `npx gulp …` (works without a global install), or `npm install -g gulp-cli`.

**"`npm install` errors on Node 20+."**
Try Node 18 LTS. Gulp 4 occasionally has issues on bleeding-edge Node releases.

**"My teammate compiled and overwrote my CSS changes."**
The compiled `styles.css` is a build artifact. Whoever compiles last wins.
Coordinate, or always re-compile and re-commit before pushing.
