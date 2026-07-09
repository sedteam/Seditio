# Chapter 6. Module Architecture and Development

This chapter is a detailed guide for developers creating their own functional modules for Seditio CMS. It covers the directory structure of a custom module, the setup manifest format, control panel integration, the SEF URL routing mechanism, and access rights management.

---

## 6.1. Directory Structure and Files of a Custom Module

All Seditio modules are physically located in the `modules` directory. Each module must reside in its own folder, the name of which matches the module's system code (e.g., `modules/gallery` or `modules/mymodule`).

### 6.1.1. Naming Conventions and File Structure
Below is a standard file structure of a module named `mymodule`:

```
modules/mymodule/
├── mymodule.php                # Main public controller (entry point)
├── mymodule.setup.php          # Setup manifest and module configuration
├── mymodule.install.php        # Script executed on module installation (optional)
├── mymodule.uninstall.php      # Script executed on module uninstallation (optional)
├── mymodule.urls.php           # Module SEF URL rewrite/translation rules
├── admin/
│   ├── mymodule.admin.php      # Module control panel in the administration area (optional)
│   └── mymodule.admin.menu.php # Menu item button in the admin sidebar (optional)
├── inc/
│   └── mymodule.functions.php  # Auxiliary functions and module API (optional)
├── lang/
│   ├── mymodule.en.lang.php    # English localization
│   └── mymodule.ru.lang.php    # Russian localization
└── tpl/
    ├── mymodule.tpl            # Public template file
    └── admin.mymodule.tpl      # Admin template file
```

### 6.1.2. Public Controller `mymodule.php`
This is the primary module file that processes visitor requests. The module is invoked via the Seditio front controller: `index.php?module=mymodule`.
Each executable PHP file of the module must start with a `SED_CODE` constant check for security reasons:
```php
if (!defined('SED_CODE')) {
	die('Wrong URL.');
}
```
The controller is responsible for processing incoming GET/POST parameters, interacting with the database, loading templates via XTemplate, and assigning the resulting content to the global `$main` variable:
```php
$t = new XTemplate(sed_skinfile('mymodule', false, true));
// ... controller logic ...
$t->parse('MAIN');
$main .= $t->text('MAIN');
```

### 6.1.3. Administrative Section of the Module
If the module registers configuration settings or administration panels (defined as `Admin=1` in the `[BEGIN_SED_MODULE]` section), then:
* In the control panel at `/admin/mymodule` (or `index.php?module=admin&m=mymodule` without SEF URLs), the core will automatically load the controller `admin/mymodule.admin.php`. For a detailed description of the routing logic of module administration panels (using `page.admin.php` as an example), see [Chapter 3. Control Panel (Administration Area)](/doc/admin-panel#313-internal-admin-panel-routing-adminincphp).
* To display the module management item in the left sidebar of the control panel, an `admin/mymodule.admin.menu.php` file is created. The menu item is declared in it:
  ```php
  $adminmenu[] = array(
      'code' => 'mymodule',
      'title' => $L['My_Module_Title'],
      'url' => sed_url('admin', 'm=mymodule'),
      'icon' => 'folder' // SVG icon name or icon CSS class
  );
  ```

---

## 6.2. Manifest and Configuration File `{module_name}.setup.php`

The `.setup.php` file (e.g., `page.setup.php`) contains metadata and configuration options for the Seditio installer. It tells the core how to register the module in the database, what default access rights to create, and what configuration options to add to the control panel.

The manifest consists of three main comment blocks.

### 6.2.1. File Metadata (`[BEGIN_SED]`)
A standard block containing information about the file:
```php
/* ====================
[BEGIN_SED]
File=modules/mymodule/mymodule.setup.php
Version=185
Updated=2026-feb-14
Type=Module
Author=Developer Name
Description=Custom Module for Seditio
[END_SED]
*/
```

### 6.2.2. Module Parameters (`[BEGIN_SED_MODULE]`)
Contains instructions on integrating the module into the database and rights matrix:
```ini
[BEGIN_SED_MODULE]
Code=mymodule                    ; Unique text code of the module
Name=My Module                    ; Module title for the admin area
Description=Handles custom tasks  ; Module description
Version=1.0.0                     ; Module version
Date=2026-feb-15                  ; Release date
Author=Developer                  ; Author
Copyright=                        ; Copyright
Notes=                            ; Important notes
Requires=                         ; Code list of other modules/plugins required
Admin=1                           ; 1 - registers control panel in /admin/mymodule, 0 - no admin panel
Auth_guests=R                     ; Default guest rights (Read)
Lock_guests=W12345A               ; Locked guest rights
Auth_members=RW                   ; Default registered member rights (Read + Write)
Lock_members=12345A               ; Locked registered member rights
Lock_module=0                     ; 1 - system core module (cannot delete), 0 - regular module
[END_SED_MODULE]
```

### 6.2.3. Module Configuration (`[BEGIN_SED_MODULE_CONFIG]`)
Declares module settings that will be available to the administrator in the configuration section at `/admin/config?n=edit&o=module&p=mymodule` (or `index.php?module=admin&m=config&n=edit&o=module&p=mymodule` without SEF URLs). Each line represents a configuration option in the following format:
`variable_name=order:type:values:default_value:description`

Example:
```ini
[BEGIN_SED_MODULE_CONFIG]
maxitems=01:select:5,10,15,20,25:10:Max items per page
enable_custom=02:radio::1:Enable custom features (1 - yes, 0 - no)
api_key=03:string::default_key:API Key for integration
intro_text=04:text::Greeting:Header text of the module
[END_SED_MODULE_CONFIG]
```

**Configuration Field Types:**
* `string` — single-line text input.
* `select` — drop-down list (values are separated by commas in the third parameter).
* `radio` — Yes/No switch (1/0).
* `text` — multi-line text input (textarea).
* `custom` — field with complex input logic, managed by a custom script or function.

All declared options are automatically written to the `sed_config` table and are available in the code via the global array `$cfg['plugin']['mymodule']['variable_name']` (for plugins) or `$cfg['mymodule']['variable_name']` (for modules).

---

## 6.3. Module Installation Scripts

When a module is installed or uninstalled by the administrator, the core executes additional tasks if installation files are present in the module directory.

### 6.3.1. Module Installation Script (`mymodule.install.php`)
This script runs automatically immediately after registering the module in the `sed_auth` table and writing the configuration settings.
It is typically used to create custom database tables for the module:
```php
<?php
if (!defined('SED_CODE') || !defined('SED_ADMIN')) { die('Wrong URL.'); }

// Creating custom module table
$db_my_table = $cfg['sqldbprefix'] . 'my_table';
$sql = sed_sql_query("CREATE TABLE IF NOT EXISTS $db_my_table (
    item_id int(11) NOT NULL auto_increment,
    item_title varchar(255) NOT NULL default '',
    item_date int(11) NOT NULL default '0',
    PRIMARY KEY (item_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
```
You can also perform any system actions here (for example, checking PHP version, creating cache directories in `/datas/`, and configuring `chmod` permissions).

### 6.3.2. Module Uninstallation Script (`mymodule.uninstall.php`)
Runs when the module is removed. Its primary task is to drop custom tables and columns created by the module to keep the database clean:
```php
<?php
if (!defined('SED_CODE') || !defined('SED_ADMIN')) { die('Wrong URL.'); }

$db_my_table = $cfg['sqldbprefix'] . 'my_table';
sed_sql_query("DROP TABLE IF EXISTS $db_my_table;");
```
*Note: The Seditio core automatically deletes records from the `sed_config` and `sed_auth` tables.*

---

## 6.4. Developing SEF URL Rules: `{module_name}.urls.php`

For the module to support clean, friendly URLs, the developer must provide routing rules in the `{module_name}.urls.php` file. A sample of such rules can be studied in the page module file `modules/page/page.urls.php`.

> [!NOTE]
> Module SEF URL rules are dynamically compiled by the core into a single cache file `datas/cache/sed_urls.php` on module installation/activation or manual cache clearing. For more details on the assembly mechanism, priority weights, and rules caching, see [Chapter 2. Installation, Configuration, and Deployment](/doc/install-deploy#243-sef-url-architecture-and-internal-logic).

The file must return the priority value and the URL transformation rules:

### 6.4.1. SEF URL Parsing Priority (`$mod_urlrewrite_order`)
This variable sets the priority order for executing the regular expression rules of this module. The lower the number, the earlier the rules will be processed:
```php
$mod_urlrewrite_order = 500;
```

### 6.4.2. Inbound Routes (`$mod_urlrewrite`)
The `$mod_urlrewrite` array contains rules for matching the URL entered by the user in the address bar with internal script parameters:
```php
$mod_urlrewrite = array(
    // SEF URL example: /mymodule/125/view
    array(
        'order' => 500,
        'cond' => '#^/mymodule/([0-9]+)/view(/?)$#',
        'rule' => 'modules/mymodule/mymodule.php?id=$1&action=view'
    ),
    // SEF URL example: /mymodule/category-name/
    array(
        'order' => 510,
        'cond' => '#^/mymodule/([a-zA-Z0-9_-]+)/$#',
        'rule' => 'modules/mymodule/mymodule.php?cat=$1'
    )
);
```
* `cond` — regular expression to match the SEF URL address.
* `rule` — internal Seditio request address to which the request will be transparently redirected.

### 6.4.3. Outbound Links (`$mod_urltrans`)
The `$mod_urltrans` array describes how the address generation function `sed_url()` should assemble SEF URLs when outputting links in templates:
```php
$mod_urltrans = array();
$mod_urltrans['mymodule'] = array(
    // Translates sed_url('mymodule', 'id=125&action=view') into /mymodule/125/view
    array(
        'params' => 'id=*&action=*',
        'rewrite' => 'mymodule/{id}/{action}'
    ),
    // Translates sed_url('mymodule', 'cat=sports') into /mymodule/sports/
    array(
        'params' => 'cat=*',
        'rewrite' => 'mymodule/{cat}/'
    )
);
```

---

## 6.5. Validating Access Rights (ACL) in Module Code

Module access rights delimitation is built on the standard Seditio ACL bitmask.

### 6.5.1. Registering Module Rights
On module installation, the core checks the `Auth_guests` and `Auth_members` fields in the `setup.php` file and automatically creates rights records for all existing groups in the `sed_auth` table. The area code (`auth_code`) will match the module's system code (in our example, `mymodule`).

### 6.5.2. Verifying Rights in PHP Code
To restrict access to module actions, the developer should call the core function `sed_auth()`:
```php
// Retrieve current user rights for the 'mymodule' area
// The second parameter 'a' refers to the base section of the module
list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('mymodule', 'a');
```

The retrieved variables are used to block unauthorized actions:
```php
// Block page completely if user lacks Read permissions
sed_block($usr['auth_read']);

// Block form submission if user lacks Write permissions
if ($action == 'save' && !$usr['auth_write']) {
    sed_redirect(sed_url('mymodule', '', '', true));
    exit;
}

// Display admin links to moderators
if ($usr['isadmin']) {
    $t->assign("ADMIN_ACTIONS_URL", sed_url('admin', 'm=mymodule'));
    $t->parse("MAIN.ADMIN_ZONE");
}
```
Thanks to this scheme, the module integrates fully into Seditio's security system, allowing administrators to configure permissions via user group management.
