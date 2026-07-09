# Chapter 1. Introduction and System Architecture

This chapter covers the basic concepts of Seditio CMS, the history of its creation and development, system requirements, architecture, engine file structure, and built-in security mechanisms.

---

## 1.1. Seditio CMS Definition, Scope of Application, and Key Features

**Seditio** is a lightweight, fast, and flexible modular content management system (CMS) and web development platform (CMF) written in PHP and using MySQL DBMS.

### 1.1.1. Scope of Application
Due to its high performance, minimalist core, and flexible template system, Seditio is excellent for developing:
* Medium to large-scale information and news portals.
* Thematic communities with integrated forums.
* Corporate and business card websites.
* Blogs, portfolios, and personal pages.
* Web resources with non-standard data structures (thanks to CMF capabilities).

### 1.1.2. Key Features
1. **Complete separation of logic and presentation.** In Seditio, PHP code is completely separated from HTML/CSS markup. Templates are implemented based on `.tpl` files (XTemplate SE template engine), which use special placeholder tags (for example, `{PHP.cfg.mainurl}` or `{PAGE_TITLE}`). This allows web designers to layout the interface without needing to write PHP code.
2. **Procedural architecture (Procedural Core).** Seditio CMS is one of the few modern actively supported systems that maintain an efficient procedural code architecture. Instead of heavy cascades of OOP abstractions, design patterns, class factories, and dependency injection (DI) containers, the Seditio core is built on a set of flat, sequential functions. This ensures:
   * **Low entry barrier:** A developer only needs a basic understanding of procedural programming in PHP and global scopes to create full-fledged modules and plugins.
   * **Extreme performance:** Calling procedural functions and direct access to global data structures (`$cfg`, `$usr`, `$L`, `$db_*`) are executed significantly faster than initializing the object graph of OOP frameworks. Seditio generates pages in a few milliseconds and consumes minimum RAM (within 2–4 MB per hit).
   * **Simplicity of debugging and transparency:** Code execution tracing is straightforward, which minimizes troubleshooting time and simplifies technical audits of the codebase.
3. **High speed and low load.** The system core is optimized to work with a minimum number of SQL queries. The built-in caching system and data autoloading reduce the load on the database.
4. **Modularity.** The architecture is built on a clear separation of the core, pluggable functional modules (Modules), and plugin extensions (Plugins).
5. **Out-of-the-box security.** The system strictly filters all incoming data, has built-in mechanisms to protect against CSRF/XSS attacks, and a flexible user access control subsystem (ACL).
6. **SEO-oriented.** Support for SEF URLs with customizable URL rewrite rules and built-in meta tag management tools.

---

## 1.2. Brief History of Seditio Creation and Development

The history of Seditio spans more than two decades and includes several key stages of development:

* **The LDU Era (2001–2006):** The engine originated in 2001 under the name *Land Down Under (LDU)*. It was authored by Olivier Chapuis, a French programmer (company *Neocrome*). A simple, fast, and secure system on PHP/MySQL quickly gained popularity, especially in the gaming community, where it was used to create gaming portals.
* **Transition to Seditio and the Emergence of Cotonti (2006–2009):** In 2006, LDU underwent rebranding and deep architectural redesign, receiving the name *Seditio* (Land Down Under Second Edition). The main focus was placed on improving plugin support. In 2008, Neocrome suspended the release of versions, and part of the team, with the official consent of the author, created a fork — *Cotonti* (released in 2009), which began to develop in parallel as a fully open-source project.
* **Completion of the Author's Phase (2011–2012):** In 2011, Olivier Chapuis announced the closure of the LDU and Seditio projects at version 150. An attempt to resume development in 2012 with the release of version 160 was unsuccessful due to identified security vulnerabilities.
* **Modern Phase (from 2012 to the present):** In 2012, Russian developers Alexander Tishov (Amro) and Anton Sazanov (Antony) received official permission from Olivier Chapuis to continue development and maintenance of the Seditio engine. The main contribution to further development of the system and writing all of its code is made by Alexander Tishov (Amro). A significant contribution to the project development was also made by the Turkish developer community. The project was transitioned to an open-source software model under the license of BSD and has gone through versions from 171 to the stable 185. The system is continuously improved for modern commercial and community projects, maintaining a focus on simplicity, speed, and security.

---

## 1.3. System Requirements and Environment

To deploy and run Seditio CMS correctly, the following software environment is required:

### 1.3.1. Minimum System Requirements
* **Web Server:** Apache 2.x (with `mod_rewrite` module to support SEF URLs) or Nginx (with configured rewrite rules).
* **PHP:** Version 5.6.0 or higher (including full compatibility with PHP 7.x and PHP 8.x branches). Seditio web installer checks PHP version compatibility with the condition `version_compare(PHP_VERSION, '5.6') >= 0`.
* **DBMS:** MySQL 5.0.7 or higher / MariaDB (with support for `mysqli` extension).

### 1.3.2. PHP Extensions
* **Checked by the installer as mandatory:**
  * `mysqli` — driver for interacting with the MySQL database.
  * `mbstring` — required for correct operation with multi-byte encodings (UTF-8) in core string functions.
  * `gd` — required for generating thumbnails, processing avatars, image gallery, and captcha.
* **Used by the system core:**
  * `hash` — used for generating secure tokens and hashing passwords.
  * `pcre` — Perl Compatible Regular Expressions library for routing and data validation.
  * `sessions` — support for PHP sessions for user authentication.

---

## 1.4. General Architecture: Core, Modules, Plugins

Architecturally, Seditio is divided into three main layers: **Core**, **Modules**, and **Plugins**. This three-tier model ensures a balance between high performance of the base system, functionality of key site sections, and flexibility of extending engine capabilities without interfering with its source code.

### 1.4.1. Core
The Seditio core provides the basic life support of the system:
* Initialization of environment, configuration, and localization.
* Session management and user authentication.
* Access Control List (ACL) mechanism.
* Database interaction via `mysqli` wrapper.
* Template engine (XTemplate SE) for rendering pages.
* Request routing and SEF URL processing.

The main system core libraries are concentrated in the `system/` directory.

### 1.4.2. Modules
Modules are large, functionally complete sections of the site that can work independently of each other. Examples of built-in modules:
* `page` — management of pages and content categories.
* `forums` — a full-featured forum.
* `users` — personal cabinets, profiles, groups, and registration.
* `pfs` — personal file system (file uploading, media manager).

Each module is located in its own directory inside `/modules/` and is connected via the entry point `index.php?module=module_name`.

For a detailed description of built-in modules and their development process, see [Chapter 5. Built-in Modules](/doc/modules-builtin) and [Chapter 6. Module Architecture and Development](/doc/modules-dev).

### 1.4.3. Plugins
Plugins are designed to extend the functionality of the core or modules without modifying their source code. Seditio implements a flexible plugin system:
* **Event plugins (on hooks):** connected at the call locations of the `sed_getextplugins()` function in the core code. They run in the context of the calling script and have access to its variables.
* **Autonomous plugins (Standalone/Direct):** run as independent pages via the front controller `/plug` (or `index.php?module=plug`).

For a detailed guide on creating, structuring, registering hooks, and plugin call modes, see [Chapter 8. Plugin Architecture and Development](/doc/plugins-dev).

---

## 1.5. Engine File Structure

The root directory of Seditio contains the following main directories and files:

* **`/datas/`** — folder for mutable dynamic data. Avatars, gallery photos, user files (`datas/users`) are uploaded here, and the configuration file `datas/config.php` and system cache (`datas/cache/`) are stored here. This folder must have write permissions (`CHMOD 777` or `775`).
* **`/modules/`** — contains subfolders with built-in and installed modules (for example, `/modules/page/`, `/modules/forums/`).
* **`/plugins/`** — contains subfolders with all plugins (for example, `/plugins/comments/`, `/plugins/ckeditor/`).
* **`/skins/`** — site design themes. Each theme contains template files with the `.tpl` extension, images, scripts, and styles.
* **`/system/`** — system core:
  * `/system/common.php` — the main configuration and initialization script. Connects database, loads settings, sessions, and access rights.
  * `/system/functions.php` — the main set of global Seditio API functions (including work with users, strings, SEF URLs).
  * `/system/functions.admin.php` — auxiliary functions for the administration panel (management of structure, access rights, logging, list rendering).
  * `/system/functions.image.php` — image processing functions (resize, thumbnail generation, watermarks, crop).
  * `/system/templates.php` — XTemplate SE template engine class and helper functions (TPL file parser).
  * `/system/database.mysqli.php` — MySQL database interaction module (mysqli wrapper).
  * `/system/config.urlrewrite.php` — global configuration of SEF URL rewrite rules.
  * `/system/config.urltranslation.php` — rules of parameter and character translation/transliteration when generating URLs.
  * `/system/config.extensions.php` — configuration file with an array of allowed extensions, groups, and file icons for PFS.
  * `/system/core/` — core base controllers (for example, `/system/core/admin/` for the control panel, `/system/core/plug/` for processing plugins).
  * `/system/lang/` — system localization files in different languages.
* **`/index.php`** — the main entry file. Parses incoming URI, matches it with rewrite rules, and connects the required core module or script.
* **`/.htaccess`** — Apache configuration file containing rewrite rules redirecting requests to `index.php` for SEF URLs.

---

## 1.6. System Security: Input Filtering, Protection Tokens, and Access Levels

Seditio security is built on three fundamental principles:

### 1.6.1. Built-in Security Mechanisms (Input Filtering and CSRF Protection)
To ensure data security, the Seditio CMS core uses the following built-in mechanisms:
* **Input Filtering:** Direct access to global arrays `$_GET`, `$_POST`, or `$_COOKIE` is forbidden in the engine code. All incoming data is imported and filtered through the global `sed_import()` function, which supports 12 filter types (including `INT`, `NUM`, `TXT`, `SLU`, `ALP`, `HTM`, `BOL`, etc.).
* **CSRF and XSS Protection:** To protect against cross-site request forgery, Seditio applies unique session security tokens generated by the `sed_sourcekey()` function. GET requests are verified by the `sed_check_xg()` function, POST forms by the `sed_check_xp()` function, and asynchronous AJAX calls are verified by the `X-Seditio-Csrf` header using `sed_check_csrf()`.

For a detailed technical description of all 12 input filters, the security token generation algorithm, and protection against SQL injection, see [Chapter 10. Security and Performance Optimization](/doc/security-perf).

### 1.6.2. Access Control List (ACL)
The access rights delimitation system in Seditio is built on a flexible and very fast group model using **bitmasks**.

Key principles:
* **Group Inheritance:** Access rights are configured for user groups (for example, "Guests", "Members", "Moderators"). Each user inherits the rights of the groups they belong to. If a user belongs to multiple groups, their rights are combined using bitwise "OR" (`|`).
* **Rights Addressing:** Rights are checked for a specific area (`area` — for example, `page`, `forums`, `admin`) and resource (`option` — page category code or forum section ID).
* **Rights Bitmasks:** Each permission is encoded by a power of two: `Read` = 1, `Write` = 2, `Admin` = 128, plus 5 user bits (4, 8, 16, 32, 64) for custom module and plugin needs.

To study the design of the access control system, the database table structure, caching rights in the `user_auth` field, and the authorization check process in PHP code in detail, refer to [Chapter 4. User System and Access Control (ACL)](/doc/users-acl).
