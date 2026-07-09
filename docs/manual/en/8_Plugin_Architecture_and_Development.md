# Chapter 8. Plugin Architecture and Development

Plugins (extensions) in Seditio are flexible and lightweight software components designed to modify core behavior or add new features without directly editing the CMS's original files. This chapter discusses the hook concept, plugin directory structure, part manifest syntax, operation modes, and security.

---

## 8.1. Difference Between Plugins and Modules, and the Hook Concept

### 8.1.1. Modules vs. Plugins
* **Modules:** Large, independent content blocks (e.g., Pages, Forums, Gallery). They have dedicated controllers, their own entry point via the front controller (`index.php?module=module_name`), and often have a standalone control panel.
* **Plugins:** General-purpose extensions (e.g., captcha, anti-spam, statistics output, HTML compression). They run "under the hood" of the system, subscribing to specific extension points (hooks) of the core and modules.

### 8.1.2. How the Hook System Works
The hook system allows embedding plugin code into strictly defined locations in system files. In the Seditio core, extension points are marked with a special PHP comment and a call block:
```php
/* === Hook === */
$extp = sed_getextplugins('hook_name');
if (is_array($extp)) {
	foreach ($extp as $k => $pl) {
		include(SED_ROOT . '/plugins/' . $pl['pl_code'] . '/' . $pl['pl_file'] . '.php');
	}
}
/* ===== */
```

* **`sed_getextplugins('hook_name', $cond)`:** A core function that scans the `sed_plugins` table in the database (which is cached in `$sed_plugins` during initialization) and returns a sorted array of active plugin files subscribed to this hook. The `$cond` parameter (defaults to `'R'`) specifies the required access permission level for the plugin to run.
* **`include(...)`:** The core includes the PHP files of these plugins one by one. The plugin code runs in the same context (variable scope) as the calling core file itself, obtaining direct access to its global variables (e.g., `$usr`, `$cfg`, `$L`, `$t` for XTemplate).

---

## 8.2. Plugin Directory Structure and Parts

All plugins in Seditio reside in the `plugins` directory. The plugin folder must be named after its system code (written in lowercase, e.g., `plugins/myplugin`).

### 8.2.1. File Naming Conventions
Below is the standard directory structure of a custom plugin `myplugin`:

```
plugins/myplugin/
├── myplugin.setup.php            # General manifest and configuration options
├── myplugin.install.php          # SQL/PHP installation script (optional)
├── myplugin.uninstall.php        # SQL/PHP uninstallation script (optional)
├── myplugin.urls.php             # Plugin SEF URL rewrite/translation rules (optional)
├── myplugin.php                  # Executable file for a plugin part
├── myplugin.admin.php            # Optional administration part file
├── admin/
│   └── myplugin.admin.menu.php   # Returns sidebar menu item array (optional)
├── inc/
│   └── myplugin.functions.php    # Plugin custom functions API (optional)
├── lang/
│   ├── myplugin.en.lang.php      # English translations
│   └── myplugin.ru.lang.php      # Russian translations
└── tpl/
    ├── myplugin.tpl              # Template for standalone page mode (optional)
    └── myplugin.admin.tpl        # Template for plugin admin area (optional)
```

### 8.2.2. The Concept of Plugin Parts
Unlike other CMSs where a plugin is a single large file, in Seditio a plugin is divided into isolated parts. Each executable PHP file in the plugin folder (excluding setup, install, uninstall, and urls files) corresponds to a separate plugin part.
For example, a plugin can validate a form during registration, log login events, and display a widget on the homepage:
* For form validation, `myplugin.register.php` is created (subscribes to the `users.register.add.first` hook).
* For logging, `myplugin.login.php` is created (subscribes to the `users.auth.done` hook).
* For the widget, `myplugin.php` is created (subscribes to the `index.tags` hook).

---

## 8.3. Plugin Setup Manifest `{plugin_code}.setup.php`

The `.setup.php` file (e.g., `trashcan.setup.php`) contains general metadata for the Seditio installer.

### 8.3.1. Installer Metadata (`[BEGIN_SED_EXTPLUGIN]`)
This block registers the plugin in the system, defines dependencies, and configures default access rights:
```php
/* ====================
[BEGIN_SED]
File=plugins/myplugin/myplugin.setup.php
Version=185
Updated=2026-apr-10
Type=Plugin
Author=Developer
Description=My custom plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=myplugin                     ; Unique text code of the plugin
Name=My Plugin                    ; Plugin name
Description=Handles custom hooks  ; Description
Version=1.0.0                     ; Version
Date=2026-apr-10                  ; Creation date
Author=Developer                  ; Author
Copyright=                        ; Copyright
Notes=                            ; Notes
SQL=                              ; Reserved
Auth_guests=R                     ; Default guest rights (Read)
Lock_guests=W12345A               ; Locked guest rights
Auth_members=R                    ; Default registered member rights (Read)
Lock_members=W12345A              ; Locked member rights
Requires_modules=page,pfs         ; Modules required (separated by commas, optional)
Requires_plugins=tags             ; Plugins required (separated by commas, optional)
[END_SED_EXTPLUGIN]
==================== */
```

### 8.3.2. Plugin Configuration Options (`[BEGIN_SED_EXTPLUGIN_CONFIG]`)
Declares settings that are output in the control panel at `/admin/config?n=edit&o=plug&p=myplugin` (or `index.php?module=admin&m=config&n=edit&o=plug&p=myplugin` without SEF URLs). Options are described using the following template:
`variable_name=order:type:values:default_value:description`

Example:
```ini
[BEGIN_SED_EXTPLUGIN_CONFIG]
limit=01:select:5,10,15,20:10:Limit of items to display
enable_logs=02:radio:0,1:1:Log user activities
custom_title=03:string::Widget Title:Title of the homepage block
[END_SED_EXTPLUGIN_CONFIG]
```
These settings are automatically imported into the `sed_config` table on installation and are available in the code via the global array `$cfg['plugin']['myplugin']['variable_name']`.

---

## 8.4. Anatomy of Part Executable Files and Hook Registration

Each executable PHP file representing a plugin part (e.g., `trashcan.admin.home.php`) must contain a local manifest `[BEGIN_SED_EXTPLUGIN] ... [END_SED_EXTPLUGIN]` in its header.

### 8.4.1. Local Manifest of a Plugin Part
Example header content for `myplugin.admin.php`:
```php
<?php
/* ====================
[BEGIN_SED]
File=plugins/myplugin/myplugin.admin.php
Version=1.0.0
Updated=2026-apr-10
Type=Plugin
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=myplugin                     ; Plugin code this file belongs to
Part=admin                        ; Name identifier of this part
Hooks=admin.home.first            ; System hooks to run on (separated by commas)
File=myplugin.admin               ; Name of this PHP file (without extension)
Order=10                          ; Execution priority (0..99, defaults to 10)
Lock=0                            ; Lock flag: 1 - part cannot be disabled individually, 0 - yes
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

// PHP execution code...
```

* **Multihooks Support:** Since version 1.7.3, a single plugin part can subscribe to multiple hooks simultaneously. System hooks are separated by commas in the `Hooks` parameter (e.g., `Hooks=index.tags,header.tags`). You can also specify corresponding priorities in the `Order` parameter (e.g., `Order=10,20` or `Order=10` for all).
* **The Lock Parameter:** If `Lock=1` is set, this part cannot be temporarily paused (disabled) in the control panel independently of the rest of the plugin.

### 8.4.2. Installer Registration Algorithm
When an administrator goes to `/admin/plug` (or `index.php?module=admin&m=plug` without SEF URLs):
1. The installer scans the `/plugins/myplugin/` folder and locates all `.php` files (excluding setup, install, uninstall, and urls).
2. The `[BEGIN_SED_EXTPLUGIN]` section is parsed from each PHP file.
3. For each part found, a record is inserted into the `sed_plugins` database table with the following structure:
   * `pl_code` — plugin code (`myplugin`).
   * `pl_part` — part identifier (`admin`).
   * `pl_hook` — hook name (`admin.home.first`).
   * `pl_file` — associated filename (`myplugin.admin`).
   * `pl_order` — execution priority (`10`).
   * `pl_active` — part active status (`1` or `0`).
   * `pl_lock` — lock flag (`0` or `1`).
4. When the core executes the `admin.home.first` hook, an SQL query selects all active plugins for this hook sorted by `pl_order` and includes their files.

---

## 8.5. Plugin Operation Modes

Depending on their purpose and hooks, plugins can operate in several modes:

### 8.5.1. Hook Mode (Event Handlers)
This is the classic mode. The plugin is embedded in the PHP backend execution flow.
* It can process arrays (e.g., sanitizing `$_POST` variables before saving).
* It can assign template TPL tags. For example, a part attached to `index.tags` can assign a value to a homepage placeholder:
  ```php
  $t->assign("MYPLUGIN_WIDGET", "Hello, this is plugin output!");
  ```

### 8.5.2. Standalone Mode
Allows a plugin to act as a full-featured isolated page of the website with standard design formatting. The plugin part must be registered to the `standalone` hook.
* **Link to Plugin:** The URL is generated as `/plug/myplugin` (or `index.php?module=plug&e=myplugin` without SEF URLs).
* **Routing:** The request is redirected to the `system/core/plug/plug.php` controller, which reads the `e` parameter, checks access rights, and includes the standalone part (via `system/core/plug/plug.inc.php`).
* **Templating:** The Seditio core automatically includes the global header `system/header.php` (outputting `header.tpl`) before execution and the footer `system/footer.php` (outputting `footer.tpl`) after execution. The plugin template file is searched for in the following order:
  1. Skin directory: `skins/{skin_name}/{plugin_code}.tpl`
  2. Plugin directory: `plugins/{plugin_code}/{plugin_code}.tpl`
  3. System default template: `skins/{skin_name}/plugin.tpl` (in this case, `PLUGIN_TITLE` and `PLUGIN_BODY` variables are populated from the plugin code).

### 8.5.3. Direct Mode
Grants the plugin complete control over output formatting. The plugin part must be registered to the `direct` hook.
* **Link to Plugin:** Invoked similarly to standalone mode — `/plug/myplugin` (or `index.php?module=plug&e=myplugin` without SEF URLs).
* **Primary Difference:** Unlike standalone mode, Seditio **does not include** the system `header.php` and `footer.php` files. The plugin runs directly, allowing the developer to output custom layouts, generate XML/RSS feeds, serve file downloads, or return JSON responses.
* **Access Rights:** The core still checks access permissions before including the script.

### 8.5.4. Popup Mode
Used to run a plugin in a popup window with simplified styling. The part must be registered to the `popup` hook.
* **Link to Plugin:** Invoked at `/plug?o=myplugin` (or `index.php?module=plug&o=myplugin` without SEF URLs).
* **Features:** The core includes the simplified template `popup.tpl` (or `skins/{skin_name}/popup.myplugin.tpl`) and automatically passes the parent form/field name to the JS function `add(text)` to insert data (e.g., BB-codes or smilies).

### 8.5.5. AJAX Mode
Used to process background requests. The part must be registered to the `ajax` hook.
* **Link to Plugin:** Invoked at `/plug?ajx=myplugin` (or `index.php?module=plug&ajx=myplugin` without SEF URLs).
* **Features:** No templates, headers, or footers are loaded. Only data printed directly by the plugin is returned (via `echo` or `exit`).

### 8.5.6. Code Launchers (Manual Executables)
Allows running scripts without registering parts in the `sed_plugins` table.
* **Link to Script:** Invoked at `/plug?r=myscript` (or `index.php?module=plug&r=myscript` without SEF URLs).
* **Features:** Directly loads the PHP file at `plugins/code/myscript.php` (if it exists). This is useful for utilities or administrative tasks running outside the hook architecture.

### 8.5.7. Plugin SEF URL Routing
A custom plugin can declare its own URL routing rules by creating a `myplugin.urls.php` file. The structure is identical to module URL files (see [Chapter 6. Module Architecture and Development](/doc/modules-dev#64-developing-sef-url-rules-module_nameurlsphp)). Translation rules from this file are automatically imported during cache generation.

---

## 8.6. Installation, Menu Integration, and Access Control (ACL) Scripts

### 8.6.1. SQL and PHP on Installation (`myplugin.install.php`)
The `myplugin.install.php` script is run in a single click during installation. It handles table creation, importing default records, and checking folder write permissions:
```php
<?php
if (!defined('SED_CODE') || !defined('SED_ADMIN')) { die('Wrong URL.'); }

// Creating custom table
$db_myplugin_table = $cfg['sqldbprefix'] . 'myplugin_table';
sed_sql_query("CREATE TABLE IF NOT EXISTS $db_myplugin_table (
    log_id int(11) NOT NULL auto_increment,
    log_text text NOT NULL,
    PRIMARY KEY (log_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
```
The uninstallation script `myplugin.uninstall.php` performs the reverse operation — dropping tables when uninstalled.

### 8.6.2. Integrating Menu Links (`admin/{plugin_code}.admin.menu.php`)
If the plugin registers a section in the control panel (via the `admin.plug` hook), a file `admin/myplugin.admin.menu.php` is created to add a link to the sidebar, returning the menu configuration array:
```php
<?php
if (!defined('SED_CODE')) { die('Wrong URL.'); }

return array(
	'title'     => 'My Plugin',               // Menu item title
	'order'     => 15,                        // Sidebar sorting order
	'adminlink' => sed_url('admin', 'm=myplugin'), // Link
	'auth'      => array('plug', 'myplugin', 'A'), // ACL check rule: area, code, option
	'sections'  => array()                    // Subsections (optional)
);
```

### 8.6.3. Access Control List (ACL) in Plugins
Plugin access permissions are configured in `/admin/users` per group. Upon installation, the system populates default permissions using the `Auth_guests` and `Auth_members` setup manifest parameters.

Within the plugin code (standalone, AJAX, or admin parts), the developer must validate the visitor's permissions:
```php
// Check Read permission for the current plugin
// The first column 'plug' refers to the plugins area
list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('plug', 'myplugin');

// Block access if Read permission is not granted
sed_block($usr['auth_read']);
```
This validation blocks unauthorized requests to the plugin.
