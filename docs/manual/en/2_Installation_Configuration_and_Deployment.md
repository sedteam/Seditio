# Chapter 2. Installation, Configuration, and Deployment

This chapter covers the step-by-step process of installing Seditio CMS on a hosting or local server, the correct configuration of file and directory permissions, a breakdown of key configuration file parameters, Apache and Nginx web server settings for SEF URLs, and the algorithm for moving a site to another server.

---

## 2.1. Step-by-Step Guide to Installing CMS from Scratch

The Seditio CMS web installer is flexible and functional: it not only checks server requirements and imports the database, but also allows you to select the necessary modules and plugins during installation, checking their dependencies. The entire process consists of 7 sequential steps.

### 2.1.1. Step 1. Preparing the Engine Files
1. Download the Seditio distribution archive to your computer and unpack it.
2. Connect to your server/hosting via FTP, SFTP, or the control panel file manager (e.g., cPanel or ISPmanager).
3. Upload all folders and files from the unpacked archive to the root directory of your website (usually the `public_html`, `www`, or `httpdocs` folder).
   > *Note:* If you want to install the CMS in a subfolder (e.g., `mysite.com/blog/`), create this subfolder and upload the files there.

### 2.1.2. Step 2. Preparing an Empty MySQL Database
Seditio requires a MySQL (or MariaDB) database to operate:
1. Log in to your hosting control panel.
2. Go to the **MySQL Databases** section.
3. Create a new database (e.g., `seditio_db`).
4. Create a new database user (e.g., `seditio_user`) and generate a secure password for them.
5. **Be sure to link** the created user to the database, granting them **ALL PRIVILEGES**. Write down or copy the database host name (usually `localhost`), database name, username, and password — we will need them later.

---

### 2.1.3. Step 3. Running the Web Installer (7 Stages)

#### Stage 1: Selecting the Installation Language
Open your browser and navigate to: `http://your_domain/install` (or `http://your_domain/index.php?module=install`). On the first welcome screen, select the installation language (e.g., Russian (`ru`) or English (`en`)). The selected language will be saved in the installer session, and all subsequent steps will be translated into it.

#### Stage 2: Checking Server Requirements and Write Permissions
The installer will automatically analyze your server environment:
* PHP version compatibility (checks if `version_compare(PHP_VERSION, '5.6') >= 0`).
* Presence of critical extensions: `mbstring`, `gd`, and `mysqli` (the old `mysql` connector is commented out and not used).
* Writability of the `/datas/` folder and its subfolders (`CHMOD 777` or `775`). If any folders are marked in red, you will need to configure write permissions for them (see Section 2.2), and then refresh the installer page.

#### Stage 3: Entering Configuration Data (DB and Administrator)
In this step, you fill out a single form divided into two blocks:
1. **DB Connection Parameters:**
   * *Database host:* usually `localhost`.
   * *DB User and Password:* credentials of the created MySQL user.
   * *DB Name:* database name.
   * *Table prefix:* `sed_` by default. Allows installing multiple sites in one database.
   * *Clear database before import:* checkbox (parameter `db_clear_before_import`), which allows automatically dropping old tables. When selected, the installer executes `SET FOREIGN_KEY_CHECKS = 0`, retrieves the list of all tables via `SHOW TABLES`, sequentially executes `DROP TABLE IF EXISTS` for each table, and then enables key checks back (`SET FOREIGN_KEY_CHECKS = 1`).
   > **How are MySQL Engine and encoding determined?** There is no manual choice of engine or table encoding in the installer web interface — the system does this **completely automatically**. The installer reads the MySQL version on the server: if the MySQL version is 5.6 or higher, the modern `InnoDB` engine is automatically assigned (otherwise `MyISAM`). The default encoding is `utf8mb4` (collation `utf8mb4_unicode_ci`). The generated values are automatically written to the created `/datas/config.php` file.
2. **Administrator Account Details:**
   * *Administrator Name* (e.g., `admin`).
   * *Password* (generate a secure password).
   * *Administrator E-mail* (for password recovery and system emails).
   * *Country* (select from the list).
   * *Default Skin* and *Default Site Language*.

#### Stage 4: Table Generation and config.php File Creation
After submitting the form, the installer will perform the following actions:
1. Check the DB connection (in case of error, it will return to Stage 3 showing the connection error).
2. Generate the `/datas/config.php` file, write parameters to it, and attempt to set read-only permissions (`CHMOD 0444`).
3. Import the base database schema into MySQL, create system tables, and write default settings.
4. Create the first administrator user (with maximum rights determined by group ID = 5).
5. Display a "Continue module installation" button.

#### Stage 5: Selecting and Installing Modules
The installer scans the `/modules/` folder for `.setup.php` files and displays a list of all modules available in the distribution (forums, pages, gallery, etc.):
* Modules marked as system (`Lock_module = 1` in setup file metadata) are installed mandatorily and cannot be deselected.
* For other modules, you can uncheck the installation box.
* When clicking the "Install Modules" button, the installer automatically analyzes module dependencies (`Requires`). For example, if the `gallery` module requires the `pfs` module, the installer will sort them so that `pfs` is installed first.
* A detailed module installation log is displayed.

#### Stage 6: Selecting and Installing Plugins
The installer scans the `/plugins/` folder and displays a list of plugins with their descriptions:
* By default, all plugins are selected, except those with a skip flag in their setup file (`Installer_skip`).
* For each selected plugin, the installer checks its dependency on modules (`Requires_modules` in setup file metadata). If a plugin requires a module that you decided not to install in Stage 5, the installer will automatically skip this plugin and display a warning that the plugin cannot be installed due to the missing module.
* After successful installation of selected plugins, the final SEF URL cache is generated (`sed_urls_generate()`) and basic site statistics are initialized.

#### Stage 7: Completion and Transition to the Site
The installer displays a report on the successful completion of the installation of all components and a "Go to Main Page" button.
> **CRITICALLY IMPORTANT:** After completing the installation, **be sure to delete the `/system/setup/` folder** from the server. Leaving the installer folder poses a security threat, as an attacker could rerun the installation and compromise the site.

---

## 2.2. Setting File and Directory Permissions (Chmod Rights)

In order for Seditio to save configuration settings, cache data for faster operation, and allow you and your users to upload avatars, photos, and files, the server must have write permissions for specific folders.

In Linux/Unix operating systems, permissions are configured using the `CHMOD` command.

### 2.2.1. Required Access Rights

During installation or preparation for it, you must set write permissions for the following directories and files:

1. **The `/datas/` directory and all its contents:**
   This is the main storage for dynamic files of your site. Write permissions must be recursive (i.e., apply to all subfolders):
   * `/datas/avatars/` — uploaded user avatars.
   * `/datas/defaultav/` — default avatars.
   * `/datas/photos/` — gallery photos.
   * `/datas/thumbs/` — image thumbnails.
   * `/datas/resized/` — automatically scaled images.
   * `/datas/signatures/` — user signatures for the forum.
   * `/datas/users/` — personal user files (PFS).
   * `/datas/cache/` — system cache (create the folder if it does not exist).
   
   **Recommended permissions:** `777` (or `775` depending on your hosting security settings when PHP runs on behalf of the web server owner).

2. **The configuration file `/datas/config.php`:**
   During installation, the installer must write settings to this file. If the file does not exist yet, the installer will create it on its own (provided there are write permissions for the `/datas/` folder).
   
   **During installation:** Set permissions to `666` (or `664`) for the `config.php` file if it is already created empty.
   
   **After installation:** For security reasons, **it is recommended to restrict permissions** for the `config.php` file, making it read-only — `444` (or `644`), so that no one but the owner can overwrite it.

### 2.2.2. Ways to Change Access Rights
* **Via FTP client (e.g., FileZilla):** right-click the desired folder/file on the server, select **File Permissions**, enter the numeric value (e.g., `777`), check the box "Recurse into subdirectories" -> "Apply to directories only".
* **Via hosting control panel:** use the built-in file manager, highlight the desired folder, and click **Permissions/Chmod**.
* **Via SSH console (for advanced users):**
  ```bash
  chmod -R 777 datas
  ```

---

## 2.3. Configuration File config.php: Database Connection Parameters and Global Variables

All key settings of your site are stored in a single file — `/datas/config.php`. This file is created automatically during installation, but sometimes it needs to be edited manually (for example, when changing database password or moving the site).

Let us analyze in detail what each parameter is responsible for.

### 2.3.1. MySQL Database Connection Parameters
```php
$cfg['mysqlhost'] = 'localhost';          // Database server address. Almost always 'localhost'.
$cfg['mysqluser'] = 'seditio_user';       // Database username.
$cfg['mysqlpassword'] = 'my_super_pass';  // Database user password.
$cfg['mysqldb'] = 'seditio_db';           // Your database name.
```

### 2.3.2. Additional Database Settings
```php
$cfg['sqldbprefix'] = 'sed_';             // Table prefix in DB.
$cfg['sqldb'] = 'mysqli';                 // DB connector. Seditio uses only 'mysqli'.
$cfg['mysqlengine'] = 'InnoDB';           // Table engine. InnoDB is recommended to support transactions.
$cfg['mysqlcharset'] = 'utf8mb4';         // Encoding for correct display of any characters (UTF-8).
$cfg['mysqlcollate'] = 'utf8mb4_unicode_ci'; // Collation rules for database search and sort.
```

### 2.3.3. Default Presentation and Language Settings
```php
$cfg['defaultskin'] = 'sympfy';           // Name of the default skin folder (from the /skins/ folder).
$cfg['defaultlang'] = 'ru';               // Code of the default site language (from the /system/lang/ folder).
$cfg['adminskin'] = 'sympfy';             // Skin for the administration panel.
```

### 2.3.4. Security and Session Settings
```php
$cfg['site_secret'] = 'random_character_sequence'; // Secret key (salt). Used to generate CSRF protection tokens and hash passwords. Do not disclose it to anyone!
$cfg['authmode'] = 3;                     // User authentication mode: 
                                          // 1 - via Cookies only, 
                                          // 2 - via PHP sessions only, 
                                          // 3 - combined mode (Cookies + Sessions) — the most reliable and convenient.
$cfg['redirmode'] = FALSE;                // Redirect mode. If you experience login issues (endless login reset), set this to TRUE.
$cfg['ipcheck'] = TRUE;                   // IP address validation. If enabled (TRUE), user session will be reset if their IP changes (protection against session hijacking).
$cfg['multihost'] = TRUE;                 // Allow the site to open under multiple domain names (useful in case of mirrors).
$cfg['patchmode'] = FALSE;                // Automatic DB structure update (recommended to keep FALSE, unless CMS version upgrade is performed).
```

---

## 2.4. Web Server Configuration (Apache .htaccess / Nginx) and SEF URL Configuration

Seditio CMS has a powerful, flexible, and optimized SEF URL system. It completely strips addresses of parameters like `index.php?module=page&id=15`, converting them into clean hierarchical paths.

### 2.4.1. Seditio SEF URL Formats
* **Main page:** `/`
* **Category (page list):** `/news/` or `/articles/sport/` (**must end with a slash `/`**).
* **Page by its unique alias:** `/news/my-first-page` or `/articles/sport/new-record` (**no trailing slash**).
* **Page by its ID (if alias is not set):** `/news/15` or `/articles/sport/124` (**no trailing slash**).
> **Important difference:** In Seditio SEF URL routing, the type of resource (page or category) is determined precisely by the trailing slash. For example, `/news/` is a category (page list), and `/news` is a page with the alias `news`.
* **Downloading a file attached to a page:** `/news/my-first-page/download`.
* **Page comments:** `/news/my-first-page/comments`.
* **Plugin page (e.g., contacts):** `/plug/contact`.
* **Administration panel section:** `/admin/users` or `/admin/config`.
* **User profile (details):** `/users/details/1` (where `1` is the user ID in the database).
* **Login and registration pages:** `/login` and `/register`.

---

### 2.4.2. Configuring Redirects on the Web Server Side

For SEF URLs to work, the web server must redirect all requests to non-existent files and folders to a single entry point — `/index.php` in the site root.

#### Option 1. Configuring for Apache Server (via `.htaccess`)
At the root of Seditio distribution, there is already a pre-configured `/.htaccess` file. Here is its key content:
```apache
Options -Indexes
<IfModule mod_rewrite.c>
RewriteEngine On

# If the requested resource is not an existing file (-f), 
# symbolic link (-l), or real folder (-d)...
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-l
RewriteCond %{REQUEST_FILENAME} !-d

# ...then redirect the entire request to index.php
RewriteRule ^(.*)$ index.php [L]
</IfModule>
```
> **Tip for subfolders:** If you installed the CMS not in the domain root, but in a subfolder (e.g., `mysite.com/seditio/`), open the `/.htaccess` file, find the `#RewriteBase /` line, uncomment it (remove the `#` character), and specify the path to your folder: `RewriteBase /seditio/`.

#### Option 2. Configuring for Nginx Server
Since Nginx does not support `.htaccess` files, rewrite rules are specified in the virtual host configuration file (`server` block):
```nginx
location / {
    # Try to serve file or folder directly. 
    # If not found — redirect to index.php preserving arguments.
    try_files $uri $uri/ /index.php?$args;
}
```

---

### 2.4.3. SEF URL Architecture and Internal Logic

The SEF URL system in Seditio is divided into two independent processes: **path recognition (Rewrite)** and **outgoing link generation (Translation)**.

#### 1. Path Recognition (Rewrite) — from SEF URL to System URL
When a user requests the address `/news/my-first-page`, the server redirects this request to `/index.php`. The Seditio core reads the address and matches it with regular expressions in the `$sed_urlrewrite` rule array:
* For pages, the matching regex pattern is: `#^/([a-zA-Z0-9_\-\+/%]+)/([a-zA-Z0-9_\-\+%]+)$#`.
* Its corresponding rewrite rule is: `modules/page/page.php?al=$2`.
* The `index.php` loader extracts the passed parameters (page alias), places them into global arrays (filling `$_GET['al'] = 'my-first-page'`), and then directly includes the module file: `include_once(SED_ROOT . '/modules/page/page.php')` for execution in the current context.

#### 2. Link Generation (Translation) — from System URL to SEF URL
The single global function `sed_url()` (located in `/system/functions.php`) is responsible for generating all links in Seditio. Using this function in all modules, plugins, and PHP scripts allows the site to change link formats on the fly: when SEF URLs are enabled or disabled in the administration panel, links will be automatically rebuilt across the entire site without changing the code.

##### Function Signature:
```php
function sed_url($section, $params = '', $anchor = '', $header = false, $enableamp = true)
```

##### Parameter Breakdown:
1. **`$section`** (string) — The name of the target section or script (e.g., `'page'`, `'forums'`, `'users'`, `'plug'`).
2. **`$params`** (string|array) — URL parameters. Passed as a string (e.g., `'c=news&id=15'`) or an associative array (e.g., `array('c' => 'news', 'id' => 15)`).
3. **`$anchor`** (string) — Optional page anchor (e.g., `'#comments'`).
4. **`$header`** (bool) — If set to `TRUE`, the link is generated for use in HTTP headers (e.g., during `header("Location: ...")` redirects). In this case, ampersands are not escaped, and the full site domain is automatically attached to the path.
5. **`$enableamp`** (bool) — `TRUE` by default. Automatically replaces all `&` ampersands in links with the safe HTML entity `&amp;` (required for code validation).

##### Internal Execution Logic:
* **If SEF URLs are disabled** (`$cfg['sefurls'] = FALSE`): the function constructs a standard link like `index.php?module=page&c=news&id=15`.
* **If SEF URLs are enabled** (`$cfg['sefurls'] = TRUE`):
  1. The function accesses the translation rule array `$mod_urltrans[$section]`.
  2. It iterates through the rules from top to bottom, checking for the presence of the passed parameters in `$params`. A rule matches if all its fixed parameters match the passed ones (the `*` symbol means that the parameter can have any value).
  3. The rewrite template (the `rewrite` key) is taken from the matched rule.
  4. Placeholders are replaced:
     * **Simple variables `{var}`:** Replaced with values from parameters (e.g., the `{al}` placeholder will be replaced with the page alias).
     * **Callbacks `{callback()}` or `{param|callback}`:** Call PHP core or module functions to build complex paths. For example, the page rule uses the `{sed_get_pagepath()}` callback:
       `'params' => 'al=*', 'rewrite' => '{sed_get_pagepath()}{al}'`
       The `sed_get_pagepath()` function determines the page category and builds its hierarchical path (e.g., `articles/sport/`). The result is a clean SEF URL: `articles/sport/new-record`.
     * **Unused parameters:** If more parameters were passed to `sed_url()` than described in the `rewrite` template, they are automatically appended to the end of the SEF URL as a regular query string (e.g., `/news/my-first-page?highlight=word`).
     * Depending on the `$header` flag or the `$cfg['absurls']` setting, the full site address (domain) is attached to the link if necessary, double slashes are removed, and the `$anchor` is attached.

#### 3. Modular Structure, Priorities, and Caching of Rules
In order not to overload the core with giant arrays of rules, Seditio gathers SEF URL rules modularly:
* **Global rules** (basic core routes like `/admin` or `/plug`) are located in `/system/config.urlrewrite.php`.
* **Module rules** are located in `<module_name>.urls.php` files in the module folders (e.g., `/modules/page/page.urls.php` for pages).
* **Plugin rules** are located in `<plugin_name>.urls.php` files in the plugin folders.

**Priorities and Rule Sorting (`order`):**
Since SEF URL routing iterates through regular expressions until the first match, the order in which the rules are checked is critically important (from specific to general). For this purpose, each rule has a numeric position — **priority (`order`)**:
* System routes (system controllers, resizers, captcha): `100–199`.
* Modules (gallery, forums, private messages): `200–499`.
* Plugin sections and admin panel: `500–549`.
* User profiles and system messages: `550–599`.
* Page lists (categories): `600–649`.
* Pages themselves: `650–699`.
* Main page (the most general route `/`): `700`.

When building the cache, all rules are merged into a single array and sorted in ascending order of priority (`usort` by the `order` key). Thus, more specific routes (e.g., `/plug/contact` with priority 515) are always checked before general category and page rules.

**How the Rule Cache Works:**
During installation, update, or structural changes to the site, Seditio scans active module and plugin folders, gathers their SEF URL rules, sorts them by priority, and compiles them into a single cache file — `/datas/cache/sed_urls.php`.
On every site request, the core includes this pre-sorted cache file, which avoids scanning directories on every page view, ensuring extremely fast routing.

---

## 2.5. Transferring a Site Between Servers and Backups

Sooner or later, you will need to move your website from a local computer (OpenServer/Denwer) to a production hosting or change your hosting provider. The Seditio migration process consists of simple steps:

### 2.5.1. Step 1. Creating a Backup
1. **Files:** Download all files of your site to your computer using an FTP client or hosting file manager (or pack them into a ZIP archive on the server and download it).
2. **Database:** Log in to your database management tool (usually **phpMyAdmin**). Select your database, go to the **Export** tab, choose the quick export method in SQL format, and download the database dump file (e.g., `backup.sql`).

### 2.5.2. Step 2. Deploying Files on the New Server
1. Upload all files of your site (or the archive) to the root directory of the new site. If you uploaded an archive, unpack it.
2. Check and configure `CHMOD` write permissions for folders in the `/datas/` directory (set `777` or `775`), as described in Section 2.2.

### 2.5.3. Step 3. Importing the Database
1. Create a clean MySQL database and user on the new hosting (similar to Step 2 in Section 2.1).
2. Open **phpMyAdmin** for the new database.
3. Go to the **Import** tab, choose your database backup file (`backup.sql`) from your computer, and click **Go**. The database will be imported.

### 2.5.4. Step 4. Editing config.php
Open `/datas/config.php` on the new server in a text editor and change database connection parameters:
```php
$cfg['mysqlhost'] = 'localhost'; // New DB host
$cfg['mysqluser'] = 'new_db_user'; // New DB user
$cfg['mysqlpassword'] = 'new_db_password'; // New DB password
$cfg['mysqldb'] = 'new_db_name'; // New database name
```
Save your changes.

### 2.5.5. Step 5. Clearing Cache
After changing configuration and importing database, paths to images, SEF URL rules, or installed plugins may remain in the cache under old addresses. To make the site run correctly on the new server, you must clear the cache.

You can do this in two ways:

#### Method 1. Via Administration Panel (if admin access is available)
If you logged in successfully on the new site:
1. Go to **Admin Panel -> Management -> Internal Cache** (or open `/admin/cache` or `index.php?module=admin&m=cache` without SEF URLs).
2. You will see a list of cached resources and management buttons:
   * Click **Purge** — this resets the internal database cache.
   * Click **Regenerate SEF URLs** — this updates the SEF URL cache file (`sed_urls.php`) matching the current site structure and domain.
   * Click **Purge TPL cache** — this deletes old compiled template copies.

#### Method 2. Manually (if admin panel is inaccessible)
If the site displays incorrectly or you cannot log in due to old cache paths:
1. Connect to the site via FTP or hosting file manager.
2. Go to the `/datas/cache/` folder.
3. **Delete all files** from this folder (except the service protection file `index.php`, if present). Do not delete the `/datas/cache/` folder itself! It is also recommended to go to the `/datas/cache/templates/` subfolder and delete all compiled TPL files except the `index.php` file there.
   > *Note:* Upon the first page request, Seditio will automatically rebuild the SEF URL cache and generate new, actual cache files in this folder.

After clearing the cache, open your website in a browser and check all sections. The transfer is complete!
