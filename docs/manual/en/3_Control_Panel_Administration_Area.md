# Chapter 3. Control Panel (Administration Area)

This chapter discusses in detail the structure and features of the Seditio CMS control panel (administration area). You will learn how the management interface is organized, how to configure the core, modules, and plugins, how the unique CCK/Extra Fields mechanism works, and how to maintain the database, clear cache, and run a system event audit.

---

## 3.1. Overview of the Admin Panel Interface

The Seditio CMS control panel is accessible at `/admin` (or `index.php?module=admin` without SEF URLs). Access is granted to users belonging to the administrator group (by default, the system group `administrators` with ID = 5). Access to specific sections of the admin panel is delimited using the access rights matrix (ACL).

The main system file responsible for loading the control panel is the controller `system/core/admin/admin.php`, which initializes the administrator session, includes the localization file `system/lang/ru/admin.lang.php` (or another language depending on the environment), and transfers control to the dispatcher `system/core/admin/admin.inc.php`.

### 3.1.1. Two-Pane Interface Structure
The admin panel interface is divided into two main functional areas:
1. **Sidebar (Left Panel):**
   Has a dark layout. The avatar of the current administrator and a greeting (e.g., *"Hi, Amro"*) are displayed at the top. Below is a vertical list of links to the main management sections.
2. **Main Content (Right Panel):**
   This displays working forms, lists of elements, and statistics tables.
   * **Top Horizontal Menu:** Located directly above the main content area. On the left side, breadcrumbs are displayed (e.g., `Administration panel > Home` or `Administration panel > Configuration`), along with a quick link to the website `Go to site`. On the right side, there are links to the administrator profile (`Profile`), personal files (`My files`), count of unread PMs (`No private messages`), and the logout button (`Logout`).

---

### 3.1.2. Dynamic Sidebar Structure
The Seditio sidebar is not rigidly fixed: **installed modules and plugins can dynamically add their own items and drop-down subsections to it**.

When assembling the admin page, the loader `system/core/admin/admin.header.php` scans the active component directories and looks for menu description files in them:
* **For modules:** `/modules/{module_name}/admin/{module_name}.admin.menu.php`
* **For plugins:** `/plugins/{plugin_name}/admin/{plugin_name}.admin.menu.php`

This file must return an associative array of the menu configuration. If the file is found and the user has permission to view the component, its menu item is automatically embedded in the sidebar. All items are sorted by the `order` key (ascending), and alphabetically by name in case of a tie.

---

### 3.1.3. Internal Admin Panel Routing (`admin.inc.php`)
The file `system/core/admin/admin.inc.php` is responsible for dispatching the control panel sections, accepting the parameter `m` (module/action) in the GET request:
1. **Module Check:** First, the code executes an SQL query to the `sed_core` table, checking if a module with code `m` is registered and has the administration flag (`ct_admin = 1`). If so, a module-specific administration file is included by convention: `modules/{m}/admin/{m}.admin.php` (for example, `modules/page/admin/page.admin.php` for the pages module when accessed at `/admin/page` or `index.php?module=admin&m=page` without SEF URLs).
   * **Dispatching within a module via parameter `s`:** The included module administration file acts as a second-level router. For example, in `page.admin.php`, the logic is distributed depending on the passed parameter `s` (`$s = sed_import('s', 'G', 'ALP', 24)`):
     * `$s == 'add'` includes `page.admin.add.php` (adding pages).
     * `$s == 'edit'` includes `page.admin.edit.php` (editing pages).
     * `$s == 'manager'` includes `page.admin.manager.php` (Page Manager).
     * In all other cases (by default), the file `page.admin.structure.php` is included (management of categories and structure of pages).
2. **Plugin Check:** If the module is not found, the router checks if a plugin with code `m` is active. If so, the plugin administration file is included: `plugins/{m}/{m}.admin.plug.php` (for example, for the trashcan plugin at `/admin/trashcan` or `index.php?module=admin&m=trashcan` without SEF URLs).
3. **Core Fallback:** If both checks yield no results, the corresponding core administration file from the `system/core/admin/` folder is loaded. By default, `admin.home.inc.php` is loaded (if `m` is empty), `admin.{m}.inc.php`, or `admin.{m}.{s}.inc.php` (if section parameter `s` is present).

#### Example 1. Dynamic Menu of the Page Module (`page`)
File: `/modules/page/admin/page.admin.menu.php`
```php
return array(
    'title'     => 'core_page',                       // Language key for sidebar title ($L['core_page'])
    'order'     => 10,                                 // Sort position
    'adminlink' => sed_url('admin', 'm=page&s=manager'), // Main link when clicking on the menu item
    'sections'  => array(                              // Subsections array
        'queue' => array(
            'label' => 'adm_valqueue',                 // Title: "Validation queue"
            'auth'  => array('admin', 'any', 'A'),     // Available only to administrators
            'param' => 'mn'                            // GET parameter (generates: m=page&mn=queue)
        ),
        'add' => array(
            'label' => 'addnewentry',                  // Title: "Add page"
            'auth'  => array('page', 'any', 'A'),      // Write/moderate rights in page module
            'param' => 's'                             // Link: m=page&s=add
        ),
        'manager' => array(
            'label' => 'adm_pagemanager',              // Title: "Page management"
            'auth'  => array('page', 'any', 'A'),
            'param' => 's'                             // Link: m=page&s=manager
        ),
        'structure' => array(
            'label' => 'adm_structure',                // Title: "Categories structure"
            'auth'  => array('admin', 'a', 'A'),
            'param' => 'mn'                            // Link: m=page&mn=structure
        )
    )
);
```

#### Example 2. Single Menu Item of the Trashcan Plugin (`trashcan`)
File: `/plugins/trashcan/admin/trashcan.admin.menu.php`
```php
return array(
    'title'     => 'Trashcan',                 // Sidebar title
    'order'     => 15,                          // Sort position
    'adminlink' => sed_url('admin', 'm=trashcan'), // Link
    'auth'      => array('plug', 'trashcan', 'A'), // Available only to trashcan plugin administrators
    'sections'  => array()                     // No submenu (displayed as a single button)
);
```

---

## 3.2. Main System Settings (Configuration)

All Seditio settings are configured in the **Configuration** section (`m=config`).

### 3.2.1. Core Configuration Subsections (`o=core`)
The system core is broken down into strictly defined configuration categories:
1. **Main setup:** Global site parameters (title, description, admin email, default skin, default interface language, content types).
2. **Time and date:** Formats for displaying date and time across the site, server and user time zones.
3. **Skins:** HTML output settings (force default skin for everyone, HTML document type, global charset, default keywords, title separator), as well as toggles for page generation time, copyright, and SQL statistics display in the footer (`footer.tpl`).
4. **Languages:** Contains a single option to force the default language for all visitors (`forcedefaultlang`).
5. **Menu slots (Menu slots / Text blocks):** Global text areas (slots) to input HTML/JS code for site-wide blocks (e.g., banner codes, header/footer lines, menu lists). The contents of these fields are inserted into TPL templates via corresponding tags (e.g., `{HEADER_BANNER}` in `header.tpl`, `{FOOTER_BOTTOMLINE}` in `footer.tpl`, `{HEADER_TOPLINE}` in `header.tpl`, and global tags like `{PHP.cfg.menu1}`, `{PHP.cfg.menu2}`, `{PHP.cfg.menu3}` for all template files). This allows the administrator to quickly modify general HTML structures directly from the admin panel without editing theme files.
6. **Images:** Image processing configuration (GD library, JPEG/WebP compression quality, maximum and minimum dimensions of uploaded images).
7. **Plugins:** Contains a single global option to turn off all plugins (`Disable the plugins`).
8. **HTML Meta:** Configuration of templates to generate the browser title tag (`<title>`) for the core and plugins. It contains three title templates: default (`defaulttitle`), main page (`indextitle`), and plugins (`plugtitle`). Meta tag settings for pages, forums, and PFS are defined in the configurations of the respective modules.
9. **Home page:** SEO settings specifically for the main page of the site. Here you can configure: **Homepage title** (`hometitle`), **Homepage meta description** (`homemetadescription`), and **Homepage meta keywords** (`homemetakeywords`). All these parameters are optional and are used by search engines to index the homepage.

### 3.2.2. Configuration of Modules and Plugins (`o=module` / `o=plug`)
Each installed module or plugin can register its own configuration parameters. They are read from its `.setup.php` file during installation and written to the `sed_config` database table.

In the configuration editing interface, two useful functions are available to the administrator:
1. **Reset:** Resets the component settings to the default values defined by the developer in the setup file.
2. **Add missing parameters:** If you have upgraded a plugin or module to a new version that includes new settings, this button scans the setup file and adds the new options to the database without needing to reinstall the component.

---

## 3.3. Module and Plugin Management

In Seditio, the **Modules** (`m=modules`) and **Plugins** (`m=plug`) sections are used for installation, state control, and quick administration of extensions.

### 3.3.1. Modules Control Panel (`m=modules`)
This section displays a summary table of all modules discovered in the `/modules/` directory:
* **Modules:** The name of the module (with an icon) and a link to its details.
* **Code:** System identifier of the module in the CMS (e.g., `forums`, `gallery`, `page`, `pfs`, `pm`, `polls`, `users`).
* **Version:** Module build version (e.g., `1.0.0`).
* **Running:** The current state of the module:
  * `Running` — active and available.
  * `Paused` — temporarily disabled by the administrator.
  * `Not installed` — files exist, but the module is not installed.
* **Quick Actions:**
  * **Configuration (Gear icon):** Leads to the configuration page of the module (if it has settings).
  * **Rights (Lock icon):** Redirects to the access rights matrix (ACL) to configure read, write, or admin permissions for various groups.
  * **Open (Arrow icon):** Quick link to the public section of this module on the site.

### 3.3.2. Plugins Control Panel (`m=plug`)
This section displays all plugins from the `/plugins/` folder:
* **Name and description:** Plugin icon, name, and description.
* **Code and Version:** System identifier (e.g., `comments`, `tags`, `sedcaptcha`) and version.
* **Status:**
  * `Running` — fully active.
  * `Paused` — disabled.
  * `Partially running` — some hooks/parts are active, others are disabled.
  * `Not installed` — ready for installation.
* **Quick Actions:**
  * **Configuration (Gear icon):** Access plugin configuration (if available).
  * **Rights (Lock icon):** Access plugin rights matrix (ACL).
  * **Open (Arrow icon):** Link to the plugin's standalone section on the site (if available).

### 3.3.3. Access Control List (ACL) for Components (Forums Example)
By clicking the lock icon ("Rights") next to a module or plugin, the administrator goes to the **Rights** interface.

On the rights settings page for a module (e.g., **Rights / Forums**), the table has the following structure:
* **Groups:** Complete list of registered user groups (e.g., *Administrators, Moderators, Inactive, Banned, Members, Guests*).
* **Rights per group (Lock icon):** Accesses the individual rights matrix for the selected group.
* **Base Rights Matrix:** Three checkboxes with visual indicators:
  1. **Read (Empty page icon):** Allows viewing sections (e.g., reading forum topics). Database mask value: `1`.
  2. **Write (Pencil page icon):** Allows creating content (e.g., posting messages, creating new topics). Database mask value: `2`.
  3. **Administration (Lightning icon):** Grants administrative/moderative rights inside this module (e.g., editing/deleting others' topics). Database mask value: `128`.
* **Advanced rights:** If the administrator opens rights settings with the GET parameter `advanced=1` (by clicking the advanced rights link), the table shows additional 5 custom checkboxes for each group: **Custom #1** (mask `4`), **Custom #2** (mask `8`), **Custom #3** (mask `16`), **Custom #4** (mask `32`), and **Custom #5** (mask `64`). These are used by modules and plugins to implement custom access logic.
* **Set by:** Displays the login of the administrator who last modified the rights for this group (e.g., *Amro*). This helps trace security configuration edits (the `auth_setbyuserid` field).
* **Open:** Green arrow to open the current settings of the group.

The administrator clicks **Update** at the bottom of the table to apply the changes to the database.

---

## 3.4. User and Group Management (User Groups & ACL)

The **Users** section (available at `/admin/users` or `index.php?module=admin&m=users` without SEF URLs) is designed for managing user groups, configuring their basic options, and full delimitation of access rights (ACL — Access Control List) to all system resources of the core, modules, and plugins.

### 3.4.1. User Groups Interface
Here, the administrator sees a list of all existing user groups:
* **#ID:** System ID of the group in the database. Some IDs are reserved by Seditio:
  * `5` — Administrators.
  * `6` — Moderators.
  * `4` — Members.
  * `3` — Banned.
  * `2` — Inactive.
  * `1` — Guests.
* **Groups:** Group name (e.g., *Administrators*, *Moderators*, *Russian team*).
* **Members:** Total number of users added to this group.
* **Main:** Number of users who have this group selected as their **main** group (determines skin, permissions, and role display by default).
* **State flags:**
  * `Disabled` (*Yes/No*) — whether the group is disabled (`grp_disabled` database column).
  * `Hidden` (*Yes/No*) — whether the group is hidden from normal users in lists (`grp_hidden` database column).
* **Rights (Lock icon):** Accesses the global rights matrix for this user group.

---

### 3.4.2. Global Group Rights Matrix (Rights : Administrators Example)
Clicking the lock icon ("Rights") next to a group opens the full table of access settings for all sections of the system:
* **Core sections:** List of all system core controllers (e.g., *Administration panel* — access to the admin panel, *Directories & Extra fields* — access to directories/CCK, *Gallery* — gallery, *Home page* — homepage settings, *Log* — system log, *Manage* — management utilities, *Menu manager* — menu, *Users* — user management).
* **Modules and plugins:** List of all installed modules and plugins.
* **Base rights (Read, Write, Administration checkboxes):**
  * `Read` — view permission.
  * `Write` — add and edit permission.
  * `Administration` — administration and moderation permission.
* **Set by:** Records the login of the administrator who last changed the rights for this section (e.g., *Amro*).

Using this flexible matrix, you can create any user role — from a simple editor who only has permission to write articles in the `Pages` section, to a technical administrator or global forum moderator.

---

## 3.5. Interface Localization (Language Packs)

Seditio CMS implements a flexible multilingual localization architecture. All text constants, system messages, input labels, and template translations are not hardcoded but moved to language files:
* **Global language files:** Located in the `/system/lang/` folder (e.g., `/system/lang/ru/main.ru.lang.php` — the main translation file for the Russian language). They localize the core, base constants, and titles.
* **Local module files:** Located inside the module folders (e.g., `/modules/page/lang/page.ru.lang.php` contains translations for the page module).
* **Local plugin files:** Stored in the plugin folders (e.g., `/plugins/contact/lang/contact.ru.lang.php` translates the feedback form).

The global default language of the site is configured directly in the `datas/config.php` file via the `$cfg['defaultlang']` parameter. In the admin panel (Configuration → Languages), only the status of installed language packs and the current default language (marked with `✓` in the Default column) are displayed, and an option is available to force the default language for all visitors (`forcedefaultlang`). If this option is disabled (`No`), Seditio automatically determines the language of an authorized user based on their profile settings (the `user_lang` field). For guests, the global default language is always used.

---

## 3.6. Managing Directories and CCK (Extra Fields, `m=dic`)

The **Directories** section (available at `/admin/dic` or `index.php?module=admin&m=dic` without SEF URLs) in Seditio is a powerful visual database constructor (similar to CCK in Drupal or Extra Fields in Cotonti). It allows the administrator to expand the default structure of database tables (`pages`, `users`, `comments`, etc.) by adding new columns directly from the control panel without writing SQL queries or using phpMyAdmin.

### 3.6.1. Key Concept: Directories and Extrapoles
The constructor in Seditio is based on separating directory entities from physical database columns:
* **Directories:** Act as a registry of terms (options). They describe the structure of data and choice options.
* **Extrapoles (Extra fields):** Associate directories with the system's data structures (pages, users, comments, etc.), converting them into physical DB columns and template TPL tags.

### 3.6.2. Step-by-Step Process of Creating a New Field
1. **Creating a Directory:**
   The administrator creates a new directory, specifying:
   * **Code:** A system identifier that serves as the basis for TPL placeholders and the DB column name.
   * **Type:** Field format (e.g., `input`, `number`, `textarea`, `wysiwyg`, `select`, `radio`, or `checkbox`).
   * **Values:** For list types (`select`, `radio`, `checkbox`), the data source (directory) is selected.
2. **Adding Terms ("Terms list"):**
   If necessary (for list types), the administrator fills the directory with terms. For each term:
   * **Title:** User-friendly display text (e.g., *"Red"*).
   * **Value:** System value stored in the database (e.g., *`red`*).
   * **Default term:** Optional flag setting the current term as the default option when creating a new record.
3. **Creating an Extrapole ("Extra field"):**
   The administrator configures the association, specifying the **Location** (e.g., `pages`, `users`, `comments`).
   Upon saving, Seditio automatically modifies the database: it runs an `ALTER TABLE` SQL query and adds a physical column to the target table matching the pattern `tableprefix_{CODE}` (e.g., `page_{CODE}`, `user_{CODE}`, `com_{CODE}`).

### 3.6.3. Automatic Integration and TPL Tags
After linking the extrapole, Seditio automatically generates a set of tags (placeholders) for use in TPL templates.

When outputting data on the site, the following placeholders are generated for each field `{CODE}` (e.g., `{PAGE_PRICE}`):
* `{XXX_CODE}` — outputs the final value of the extrapole (for simple fields, it outputs the text; for list types, it translates the stored `Value` into the display text `Title` based on the directory). In edit forms, this tag generates the complete input element (input box, select box, radio buttons).
* `{XXX_CODE_TITLE}` — outputs the title (label) of the extrapole.
* `{XXX_CODE_DESC}` — outputs the description of the extrapole.
* `{XXX_CODE_MERA}` — outputs the unit of measurement of the extrapole.
* `{XXX_CODE_VAL}` — outputs the raw value stored directly in the database column (e.g., the system code `red` instead of the display name *"Red"*).

---

## 3.7. Database Maintenance and Cache Clearing Tools

To ensure high speed and technical stability, the control panel includes built-in maintenance tools (available at `/admin/cache` and `/admin/manage` or `index.php?module=admin&m=cache` and `index.php?module=admin&m=manage` without SEF URLs).

### 3.7.1. Internal Cache Management (`m=cache`)
Seditio CMS uses a hybrid caching system to minimize database and CPU load. The engine divides cached data into three independent types:

1. **System Cache (DB):**
   * **Where stored:** In the `sed_cache` database table as serialized PHP data.
   * **What is cached:** Global configuration settings (`sed_config`), structure and categories of pages, lists and hooks of installed modules and plugins.
   * **Management:** The **Purge** button clears the `sed_cache` table. This forces the Seditio core to reload the structure of modules, plugins, and categories on the next page view.
2. **Template Cache (Filesystem):**
   * **Where stored:** In the `/datas/cache/` directory as compiled PHP template files.
   * **What is cached:** Compiled HTML layouts of the site (TPL files). The XTemplate engine compiles TPL files into optimized PHP code to prevent reparsing markup on every page view.
   * **Management:** The **Purge TPL cache** button triggers the `sed_tplcache_clear()` function, which deletes compiled files from `/datas/cache/`. Recommended after editing `.tpl` files to refresh the layout.
3. **SEF URL Cache (Filesystem):**
   * **Where stored:** In the `/datas/cache/sed_urls.php` file.
   * **What is cached:** The compiled array of routing and SEF URL rules for the core, modules, and plugins, sorted by priority.
   * **Management:**
     * **Delete URL Cache:** Deletes the `sed_urls.php` file.
     * **Regenerate SEF URLs:** Triggers the `sed_urls_generate()` function, which rescans rewrite rules, sorts them by priority, and writes a fresh cache file.

### 3.7.2. Management Utilities ("Tools", `m=manage`)
This section acts as a dispatcher (dashboard) for accessing auxiliary system tools, modules, and plugins that register administrative interfaces (Tools):
* **Built-in System Utilities:**
   * **Banlist (`m=banlist`):** View and edit blocked IP addresses, subnets, and email addresses.
   * **Internal Cache (`m=cache`):** Manage database, SEF URL, and template caches.
   * **Smilies (`m=smilies`):** Configure text code replacements and map them to smiley images.
   * **Hits (`m=hits`):** Site traffic logs grouped by years, months, and days.
   * **Referers (`m=referers`):** Track incoming traffic sources.
* **Additional Modules:** Management of modules (e.g., `forums`, `pfs`, `gallery`, `polls`) that are not part of the basic system core.
* **Plugin Tools:** Launches administrative panels for plugins that register handlers via the `tools` hook (e.g., `skineditor` for skin customization, `syscheck` for server checks, etc.).

---

## 3.8. Event Logging and User Activity Auditing

To control security and trace changes, Seditio includes a built-in event log (available at `/admin/log` or `index.php?module=admin&m=log` without SEF URLs).

System events are recorded using the `sed_log($text, $group = 'def')` function and saved in the `sed_logger` database table. Each record contains:
* **`log_id`** (mediumint, auto_increment): Unique event ID.
* **`log_date`** (int): Unix timestamp of the event (accounting for time offset `$sys['now_offset']`).
* **`log_ip`** (varchar(45)): User's IP address (IPv4 and IPv6 supported). In the log interface, the IP address is output as an active link to run a search for activity via the `ipsearch` tool (`m=manage&p=ipsearch&a=search&id={IP}`).
* **`log_name`** (varchar(24)): Username (login) of the actor (from `$usr['name']`).
* **`log_group`** (varchar(4)): Event group (log category).
* **`log_text`** (varchar(255)): Detailed log description. When saving, the Seditio core automatically truncates long text and appends the page address (`$sys['request_uri']`) where the event occurred.

### 3.8.1. System Log Groups (`log_group`)
When viewing the log, the administrator can filter events by:
* **All (`all`):** Complete list of entries without filtering.
* **Default (`def`):** General events not classified in specialized groups (e.g., adding/deleting directories or terms).
* **Administration (`adm`):** Modifying configuration settings, updating module settings, clearing cache.
* **Forums (`for`):** Moderator and admin actions on the forums (moving, closing, deleting topics, re-syncing forum sections).
* **Security (`sec`):** Failed login attempts, guests trying to access restricted areas, IP bans for spam or flooding.
* **Users (`usr`):** Profile edits, new registrations, user deletions, password changes.
* **Plugins (`plg`):** Installing, removing, pausing plugins, and calling their components.
* **System (`sys`):** System warnings, configuration import errors, and other technical logs.

An administrator with full rights (`isadmin`) can completely clear the event log using the **Purge** button (executes a `TRUNCATE` query on the `sed_logger` table and redirects back to the log page).
