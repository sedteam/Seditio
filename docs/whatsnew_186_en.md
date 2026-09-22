# What's New in Seditio 186

This document provides a comprehensive technical overview of the new features, architectural improvements, and bug fixes introduced in **Seditio 186** compared to the previous stable release **Seditio 185**.

---

## 1. Core & System Architecture

### New Modern Setup Installer (`system/setup/` replacing `system/install/`)
* The legacy `system/install/` directory has been completely removed.
* A modern, modular Setup Wizard (`system/setup/`) has been introduced, featuring responsive styling, server environment checks, and vector SVG graphics.
* Core routing for `/install` in `system/index.php` and SEF URL rewriting rules have been updated to point to `system/setup/setup.php`.
* The `syscheck` administrative diagnostic tool has been updated to verify the file integrity of the new setup installer.

### Dedicated Flood Protection Table (`sed_shield`)
* Anti-hammer and flood protection mechanisms have been decoupled from the online user tracking subsystem.
* A dedicated system table `sed_shield` (`shield_ip`, `shield_lastseen`, `shield_hammer`, `shield_limit`, `shield_action`) has been created.
* The deprecated `sed_online` table has been completely removed.

### Decoupling Who's Online into a Standalone Plugin (v3.0)
* Online user activity tracking queries have been removed from the core system and user modules (`users`).
* The feature has been refactored into a standalone, modular plugin (`whosonline` v3.0):
  * Built-in caching with configurable Time-To-Live (TTL) has been implemented in `plugins/whosonline/inc/whosonline.functions.php`.
  * Installation, uninstallation, and updates are now managed cleanly through the canonical extensions API (`sed_plugin_install` / `sed_plugin_uninstall`).
  * Availability checks across templates and modules have been standardized to `sed_plug_active('whosonline')`.

### Modernized HTTP Header Management (`sed_sendheaders()`)
* The system status registry `$cfg['msg_status']` has been expanded to include all standard HTTP status codes (200, 201, 204, 301, 302, 303, 304, 307, 308, 400, 401, 403, 404, 410, 500, 503).
* The `sed_sendheaders()` function has been modernized with intelligent character set negotiation, custom response headers, and `Cache-Control` directive support.
* Direct native PHP `header()` calls across AJAX endpoints, feeds (RSS, Sitemap, Robots), the image resizer, and redirects have been replaced with `sed_sendheaders()`.
* Extended HTTP redirect status code support in `sed_redirect()`, improving canonical redirects for SEF URLs.

### Improved Anti-CSRF Token Handling
* The anti-CSRF token verification system now preserves the previous token across requests. This eliminates false-positive "Wrong parameter in the URL" errors during multi-tab browsing or repeated form submissions.

### Global Localization of System Messages and Inline Auth Errors
* The system message dictionary `system/lang/**/message.lang.php` is now included globally in `system/common.php`, making `$L['msgXXX']` arrays accessible across all modules and plugins without redundant `require` calls.
* Dynamic PHP evaluations (`eval`) in message strings were replaced with standard `{username}` and `{num}` placeholder syntax across all language packs (RU, EN, TR).
* Authentication errors (`msg151`–`msg154`) no longer trigger redirects to a separate `message.php` screen; they are now accumulated in `$error_string` and rendered inline directly on the login form.

### Unified Dynamic Translation Engine & Compiled Language Cache
* Centralized storage of all core, module, plugin, and theme language strings in the database table `sed_translations` paired with active language registry `sed_languages`.
* Automated compilation into optimized, flat PHP cache files `datas/cache/sed_lang.{lang}.php`. Frontend and backend requests load language dictionaries with zero database queries.
* Introduced `Translations=1` flag in the `[BEGIN_SED]` block of `.lang.php` files to govern participation in the centralized DB translation repository.
* Intelligent isolation in `sed_langfile()`: extensions with `Translations=1` leverage the precompiled memory cache, while legacy/third-party extensions without the flag load their local `.lang.php` files dynamically at runtime, preventing namespace pollution and variable collisions (e.g. legacy `$L['plu_title']`).

### Modular Hooks Subsystem (Module Hooks)
* Modules (`modules/`) now enjoy first-class support for subscribing to system events and plugin hooks via internal modular hook parts (`modules/{code}/{code}.{part}.php`).
* Automatic discovery and registration of module hook parts in `sed_module_install()` with the `pl_module = 1` flag in `sed_plugins`.
* The `sed_getextplugins()` core hook execution function transparently resolves both plugins (`pl_module = 0`, checking `sed_auth('plug', $code)`) and modules (`pl_module = 1`, checking `sed_auth($code, 'any')`).
* The extension manager in the admin area (`admin/plug` or `index.php?module=admin&m=plug`) displays module hook parts with individual pause/unpause controls.

### Automatic URL Cache Regeneration
* In `system/common.php`, missing or corrupted `datas/cache/sed_urls.php` cache files are now detected and rebuilt automatically on the fly, preventing routing failures after cache flushes.

---

## 2. Database & Compatibility (PHP & MySQL)

### MySQL 5.7+ and MySQL 8.0 Compatibility
* During database connection establishment in `sed_sql_connect()` (`system/database.mysqli.php`), `NO_ENGINE_SUBSTITUTION` is enforced in the session `sql_mode`, preventing runtime failures on strict database servers.
* Queries combining incompatible `DISTINCT` and `ORDER BY` clauses (such as configuration category lookups in the admin header) have been refactored.
* Standard SQL compliance fixes for `ONLY_FULL_GROUP_BY` were applied across forum search, sitemap generation, PFS administration queries, and the `whosonline` plugin.

### PHP 8.1, 8.2, 8.3, 8.4+ Compatibility with Continued PHP 5.6 Support
* Sanitized input variables in `modules/users/users.register.php` and related controllers to prevent PHP 8.1+ deprecation warnings when passing `null` to `mb_strtolower()`, `str_replace()`, and `mb_strlen()`.
* Safe initialization of user permissions (`user_auth` is guaranteed to be an array even when empty).
* User registration processing is strictly restricted to `POST` requests, eliminating validation errors on accidental direct `GET` requests.
* Strict compliance with PHP 5.6+ syntax standards has been preserved across all modifications.

---

## 3. Assets, UI & Modern Front-End Architecture

### Relocation of System Assets to `system/assets/`
* Established a clean, unified static assets directory structure:
  * `system/assets/js/` — core JavaScript libraries.
  * `system/assets/css/` — core stylesheets.
  * `system/assets/fonts/` — web fonts and icon sets.
* Consolidated core JavaScript files (`core.js`, `autocomplete.js`, `imageupload.js`) from legacy directories.
* Centralized the Seditio icon font under `system/assets/fonts/` with a shared `fonts.css`.
* Created `system/assets/css/core.css` containing universal styles for modals, forms, PFS file icons, tooltips, and the user panel, removing core dependencies on specific admin skins.

### CSS Custom Properties (Variables) and Deprecated Prefix Removal
* Introduced a centralized CSS variable design token system (`--core-*`, `--admin-*`, `--sympfy-*`) in `:root` across core assets, the admin skin, and the default Sympfy theme:
  * Cohesive color palettes (primary, accents, borders, backgrounds, text).
  * UI component tokens (tabs, accordions, spoilers, modal dialogs, forms, buttons, progress bars, badges).
  * State and interaction tokens (hover, focus, active).
* Replaced hardcoded HEX/RGB values with custom properties.
* Migrated legacy vendor prefixes (`-webkit-`, `-moz-`, `-ms-`, `-o-`) and IE filter gradients to standard CSS3 `linear-gradient()`.

### Modernized Modal Window Manager (`sedjs.modal`)
* Drag and resize interactions have been upgraded to the modern `Pointer Events` standard with iframe hover freeze prevention.
* Added a full-screen window toggle button (Maximize).
* Implemented clean window docking to the bottom-left corner of the viewport (Minimize).
* Replaced legacy GIF icons and loading animations with crisp SVG controls and CSS spinners.

### New Image Upload Widget (`sedjs.imageUpload`)
* Replaced legacy avatar, photo, and signature file inputs in user profile management with the unified `sedjs.imageUpload` widget in `tiles` mode.
* Form state retention prevents file selection loss during validation failures; previews and deletion markers (`{prefix}_keep`) are handled cleanly.
* File sizes are formatted consistently throughout the UI using `sed_format_size()`.

### Complete Removal of jQuery & Frontend Vanilla JS (ES6+) Architecture
* **Legacy Dependency Purge:** Completely removed `jquery.min.js`, `jquery.plugins.min.js`, and third-party libraries (`Fancybox 3`, `Slick Slider`, `Slinky Menu`).
* **Native Sympfy Skin Scripts (`app.js`):** Public theme scripts were entirely rewritten in clean Vanilla JavaScript (ES6+). Includes native smooth scrolling (`window.scrollTo({ behavior: 'smooth' })`), passive scroll event listeners for the Sticky Header, and dynamic body scroll locking when the mobile drawer is open.
* **Standalone Mobile Navigation Component `SedMenu` (`skins/sympfy/js/sedmenu.js`):** Built a standalone, zero-dependency sliding drill-down navigation component supporting infinite submenu nesting, back buttons, dynamic title breadcrumbs, and standardized `.sed-menu*` classes.
* **Native Carousel `SedSlider` (`plugins/slider/`):** The primary homepage carousel and similar-pages slider were migrated to the in-house `SedSlider` (`slider.js` & `slider.css`) featuring pointer/touch swipe gestures, dots/arrow controls, infinite looping, responsive breakpoints, and autoplay.
* **Centralized `sedjs` UI Widgets:**
  * Sidebar and content tab switching standardized on core `sedjs.sedtabs()`.
  * Core spoilers and accordions (`sedjs.spoiler`, `sedjs.accordion`) extended with custom container/title/content class configuration.
  * Desktop navigation dropdowns upgraded to 60 FPS hardware-accelerated CSS3 transitions (Fade & Slide).

---

## 4. Control Panel (Admin Area)

### Dashboard Overhaul
* Redesigned the main administrative dashboard with responsive KPI metrics cards, quick action widgets, and system status indicators.
* Integrated `SedChart` (`system/assets/js/sedchart.js`), a lightweight standalone HTML5 Canvas charting engine with zero external dependencies.
* Completely rewrote the `adminqv` quick-view plugin using modern Flexbox layouts.
* Added one-click maintenance actions directly to the dashboard (cache clearing, database optimization).

### Interactive Traffic Statistics (`admin/hits`)
* Upgraded the hits statistics view with an interactive Canvas chart supporting 3-level period filtering (Year, Month, Week).

### Header Notification Hub (Topbar Notifications)
* Added a notification bell dropdown to the administration header, providing instant visual indicators for system alerts and module statuses.

### Extension Version Transparency
* The plugin and module lists now clearly distinguish between the extension's own version and the target compatible Seditio core version (e.g., `(186)`).

### Translation & Language Management Suite (`admin/translations`)
* **Visual Translation Browser:** Search and filter language strings across all scopes (Core, Modules, Plugins, Skins) and types (System vs Custom) with pagination.
* **Custom Overrides Tracking:** System strings (`tra_type = 1`) and custom administrator modifications (`tra_type = 0`) are clearly delineated, ensuring custom user tweaks are never accidentally overwritten during updates.
* **Language Registry Manager:** Add, edit, activate/deactivate, and re-order active system languages directly through the UI.
* **JSON Import & Export:** Export full or scoped translation sets into structured JSON packages for external localization teams, and import them with configurable conflict strategies (Update, Insert Missing, Full Replace).
* **Maintenance & Clean Re-import:** One-click language cache compilation, soft incremental disk import, and complete factory reset for disk-based languages with user customization protection.

---

## 5. Core Modules & Built-in Plugins

### PFS (Personal File Space)
* **Nested Folders**: Added support for hierarchical parent-child directory structures using `pff_parentid`.
* **Site File Space (SFS) Fixes**:
  * Fixed an issue in `sed_url()` where zero-value parameters (`userid=0`) were stripped, preventing SFS files from inadvertently saving to the administrator's personal space.
  * Resolved PHP 8 undefined index warnings and invalid breadcrumbs when managing SFS.
* Improved uniqueness and security sanitization for uploaded filenames.

### Menu Management (`sed_menu`)
* Implemented category-based auto-children generation:
  * Added `menu_cat` (source category), `menu_cat_subcats` (include subcategories), and `menu_cat_pages` (include category pages) to the menu schema.
  * Added corresponding controls in the administrative menu manager.

### Internationalization (i18n)
* Added multi-language translation support for structural category titles and descriptions (stored in the database and manageable via admin).
* Added the dynamic language switcher tag `{HEADER_I18N_SELECTOR}`, which preserves the active request URI while hiding the current language.

### CKEditor
* Integrated the `iconic` plugin for inserting Seditio icon-font glyphs directly within the editor.
* Integrated the `accordion` plugin (collapsible content sections) with full localization in English, Russian, and Turkish.

### New Core Plugins
* **`cookienotice`**: Clean, customizable cookie consent banner for compliance with privacy regulations.
* **`contact`**: Turnkey contact form plugin with validation, captcha integration, and terms agreement support.

### Search Engine
* Added query term highlighting in search results along with dynamic contextual snippet generation.

### Captcha (`sedcaptcha`)
* Added request method validation and improved captcha image generation and lifecycle management.

### Universal Trashcan API 2.0
* The `trashcan` plugin has been re-architected around an extensible API based on the `trashcan.api` system hook and `$sed_trashcan_types` global type registry.
* Removed hard-coded entity `switch` logic from the trashcan plugin core.
* Soft-delete and restoration handlers for all standard entities were moved into their respective modules and plugins: `modules/page/page.trashcan.php`, `modules/pm/pm.trashcan.php`, `modules/users/users.trashcan.php`, `modules/forums/forums.trashcan.php`, `modules/polls/polls.trashcan.php`, `plugins/comments/comments.trashcan.php`.
* Third-party modules (catalogues, shops, galleries, etc.) can now cleanly register custom item types with custom `restore` and `wipe` callbacks.

---

## 6. Database Upgrade Script (`upgrade_185_186.php`)

The automatic upgrade migration script `system/upgrade/upgrade_185_186.php` performs the following steps:

1. **Table `sed_pfs_folders`**: Adds column `pff_parentid int(11) NOT NULL DEFAULT '0'` and composite key `pff_userid_parent (pff_userid, pff_parentid)`.
2. **Table `sed_menu`**: Adds columns `menu_cat`, `menu_cat_subcats`, and `menu_cat_pages`.
3. **Table `sed_shield`**: Creates the dedicated anti-hammer protection table.
4. **Table `sed_online`**: Drops the obsolete online activity table.
5. **Plugin `whosonline`**: Performs a clean uninstallation of legacy hooks and registers the plugin v3.0 architecture.
6. **Version Registry**: Updates the database version entry in `sed_stats` to `186`.
