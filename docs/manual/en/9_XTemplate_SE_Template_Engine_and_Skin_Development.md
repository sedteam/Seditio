# Chapter 9. XTemplate SE Template Engine and Skin (Theme) Development

This chapter discusses the **XTemplate SE** (Special Edition) template engine built into the Seditio CMS core, describing its syntax constructs (blocks, conditions, loops, filters), skin file architecture, PHP API for template integration, and customization mechanisms for modules and plugins.

---

## 9.1. Introduction and XTemplate SE Concept

The Seditio CMS template engine ensures complete separation of application logic from its visual presentation.

### 9.1.1. Origin and Purpose
The `XTemplate` class in Seditio CMS (file `system/templates.php`) is a deep adaptation of the `CoTemplate` engine used in the Cotonti project, which in turn inherits and develops the classical `XTemplate` concept.
The primary goals of using the template engine in the system are:
* **Development Security:** The layout designer works exclusively with HTML markup and template placeholders, without the ability to execute arbitrary PHP code directly in templates.
* **Code Cleanliness:** Core controllers, modules, and plugins format data in PHP, while the template engine handles rendering exclusively.
* **Performance:** The template engine compiles source TPL files into an intermediate block structure cached by the `XtplCache` class. On subsequent requests, the cached template is loaded instantly, bypassing regular expression parsing.

### 9.1.2. Compilation and Caching Mechanism
On the first template invocation, the core reads the source `.tpl` file, processes dynamic file inclusion directives, strips BOM characters, and compiles the template into an intermediate block structure cached by `XtplCache`. On subsequent hits, the cached template is loaded immediately, bypassing regular expression parsing.

### 9.1.3. Differences Between XTemplate SE and CoTemplate
Despite sharing a codebase with `CoTemplate` (Cotonti), Seditio's `XTemplate SE` version features key architectural differences:

1. **Security (Function Filtering):**
   * **XTemplate SE:** Restricts function calls (filters) in templates (via `whitelist`/`blacklist` modes). By default, only safe helpers (e.g., `trim`, `date`, `crop`, `resize`) are allowed.
   * **CoTemplate:** Permits designers to call any PHP functions without restrictions, creating RCE risks.
2. **Arithmetic Expressions in Output Tags:**
   * **XTemplate SE:** Supports evaluation of simple expressions directly inside variable placeholders: `{SOME_TAG + 5}` or `{VAR_1 * VAR_2}`.
   * **CoTemplate:** Restricts arithmetic only to condition tags `<!-- IF ... -->`.
3. **Compilation Code Cleanliness and Decomposition:**
   * **XTemplate SE:** Block parsing logic (`XtplBlock::compile()`) is split into clean, compact methods (`detectNextConstruct`, `extractConstruct`, `compileConstruct`).
   * **CoTemplate:** Compilation is a monolithic 150-line method with nested loops and regular expressions.
4. **Optimized RPN Expression Parser (`XtplExpr`):**
   * **XTemplate SE:** Stores operators in the RPN stack as strings.
   * **CoTemplate:** Uses over 20 global constants to encode operators, complicating code and cluttering the global namespace.
5. **Optimized Disk Caching:**
   * **XTemplate SE:** The `XtplCache` class serializes the compiled template into **one file** on disk.
   * **CoTemplate:** Creates **three separate files** (`.tpl`, `.idx`, `.tags`) per template, increasing filesystem I/O load.
6. **Dedicated Debugging (`XtplDebugger`):**
   * **XTemplate SE:** Logging debug info is moved to a separate `XtplDebugger` class.
   * **CoTemplate:** Debugging logic is hardcoded inside `parse()` and `out()` rendering methods.
7. **Global Configuration:**
   * **XTemplate SE:** Configuring options is done via a single static entry point `XTemplate::configure()`.
   * **CoTemplate:** Configured via the `init()` method.

---

## 9.2. Template Syntax: Blocks, Loops, and Conditions

Seditio templates use the default `.tpl` extension. The syntax consists of placeholders (tags) and control comments.

### 9.2.1. Named Blocks
All markup in XTemplate SE is built on a hierarchical block structure. A block is declared using special HTML comments:
```html
<!-- BEGIN: MAIN -->
<div class="wrapper">
    <h1>{PAGE_TITLE}</h1>
    
    <!-- BEGIN: SUBBLOCK -->
    <p>Subblock additional text: {SUB_INFO}</p>
    <!-- END: SUBBLOCK -->
</div>
<!-- END: MAIN -->
```
* The root (primary) block in a file is almost always the `MAIN` block.
* Blocks can be nested to any depth.
* In PHP code, the block path relative to the root is used for parsing (e.g., `MAIN` or `MAIN.SUBBLOCK`).

### 9.2.2. Conditions (IF/ELSE)
Conditions allow controlling element displays depending on passed variables:
```html
<!-- IF {PAGE_AUTHOR} == 'Admin' -->
    <span class="badge red">Administrator</span>
<!-- ELSE -->
    <span>Author: {PAGE_AUTHOR}</span>
<!-- ENDIF -->
```
The expression analyzer `XtplExpr` is used inside the `<!-- IF {EXPR} -->` tag. The following operations are supported:
* **Arithmetic:** `+`, `-`, `*`, `/`, `%`.
* **Comparison:** `==`, `===`, `!=`, `!==`, `<`, `>`, `<=`, `>=`.
* **Logical:** `AND` (or `&&`), `OR` (or `||`), `XOR`, `!` (logical negation).
* **Seditio-specific:**
  - `HAS` — checks if an element is in an array (similar to PHP's `in_array()`). E.g.: `<!-- IF {USER_GROUP_ID} HAS 5 -->...<!-- ENDIF -->`.
  - `~=` — checks substring inclusion (similar to `strpos() !== false`). E.g.: `<!-- IF {PAGE_TITLE} ~= 'Important' -->...<!-- ENDIF -->`.

### 9.2.3. Loops (FOR)
XTemplate SE supports automatic iteration over arrays:
```html
<ul>
<!-- FOR {VAL} IN {PHP.cfg.menu_links} -->
    <li><a href="{VAL.url}">{VAL.title}</a></li>
<!-- ENDFOR -->
</ul>
```
Obtaining key/value pairs of associative arrays is also supported:
```html
<ul>
<!-- FOR {KEY}, {VAL} IN {PHP.usr.auth_groups} -->
    <li>Group ID: {KEY}, Name: {VAL}</li>
<!-- ENDFOR -->
</ul>
```
When loop tags are used, the engine automatically calculates the array size and renders block contents for each iteration, populating the loop variables.

### 9.2.4. File Inclusions (FILE)
To assemble pages from components (e.g., including site headers, footers, or widgets), the `FILE` tag is used:
```html
{FILE "skins/default/header.tpl"}
```
The file path can be quoted with single or double quotes. Included templates can contain their own blocks, conditions, loops, and tags, which will be compiled into the single template tree.

---

## 9.3. Placeholders (Tags) Syntax and Filters

Placeholders are variables inside curly braces replaced with text or HTML data during execution.

### 9.3.1. Basic Variables and Arrays
* Simple tag: `{PAGE_TITLE}`.
* Multidimensional array element: `{PAGE_ROW.page_cat}` or `{PAGE_ROW.page_owner.user_name}`.

### 9.3.2. Function Filters (Callbacks)
Placeholder values can be modified on the fly using filter chains separated by a vertical bar `|`:
```html
<p>{PAGE_TEXT|strip_tags|trim}</p>
```
Filters can accept arguments passed in parentheses separated by commas:
```html
<h2>{PAGE_TITLE|strtolower|ucfirst}</h2>
<p>Excerpt: {PAGE_TEXT|strip_tags|substr(0, 150)}</p>
<p>Publication date: {PAGE_DATE|date("d.m.Y H:i", $this)}</p>
```

> [!IMPORTANT]
> **Function Filtering:**
> To ensure security, the `XTemplate` class implements a whitelist of approved functions. In templates, only safe functions approved by the core can be called (by default: `trim`, `nl2br`, `date`, `htmlspecialchars`, `strip_tags`, `substr`, `sprintf`, `count`, `resize`, `crop`, etc.). Attempting to call system execution functions like `eval`, `system`, or `file_get_contents` will result in security errors or blocks.

### 9.3.3. Global Context (PHP Prefix)
Seditio templates provide direct access to global variables and core arrays via the `PHP.` prefix:
* `{PHP.cfg.mainurl}` — website root URL.
* `{PHP.usr.name}` — username of the current visitor.
* `{PHP.usr.id}` — visitor's user ID (0 for guests).
* `{PHP.L.Yes}` / `{PHP.L.No}` — localized language strings.
* `{PHP.out.subtitle}` — current page subtitle.

---

## 9.4. Skins and Templates Structure

User-defined design themes (skins) are stored in the `/skins/` directory. Standard templates of modules and plugins are supplied by default in their respective folders: `/modules/{module_name}/tpl/` and `/plugins/{plugin_code}/` (or `/plugins/{plugin_code}/tpl/`).

A skin in Seditio is a set of overriding templates (`.tpl`), stylesheet files (`.css`), scripts (`.js`), and media assets that allow you to completely customize the interface without editing core module and plugin files.

### 9.4.1. Skin Folder Location
The directory of a specific skin matches the format: `/skins/{skin_code}/` (e.g., `/skins/default/`).

### 9.4.2. Core and Module Templates
Templates can be stored in the active skin folder (`/skins/{skin_code}/`) or inside module folders (`/modules/{module_name}/tpl/`). The core function `sed_skinfile()` searches for template files, prioritizing active skin templates, falling back to module folder defaults.

Below is a list of default template files grouped by purpose:

#### System Templates
* **`header.tpl`** — global site header. Contains HTML headers, meta tags, the `{HEADER_STYLESHEET}` tag to include CSS files, and the navigation bar.
* **`footer.tpl`** — global site footer. Contains closing tags, counters, developer copyrights, and the `{FOOTER_DEVINFO}` tag (displays generation time, SQL queries, and RAM usage).
* **`index.tpl`** — layout for the homepage.
* **`message.tpl`** — displays system alerts, success messages, and errors.
* **`popup.tpl`** — template for popup windows (e.g., smilies or BB-codes insert lists).
* **`plugin.tpl`** — default wrapper for standalone plugins lacking custom templates.
* **`breadcrumbs.tpl`** — layouts for breadcrumbs navigation paths.
* **`maintenance.tpl`** — placeholder page for technical maintenance mode.

#### Page Module (Page)
* **`page.tpl`** — details view of an individual page.
* **`page.add.tpl`** — page creation form for frontend users.
* **`page.edit.tpl`** — page edit/update form.
* **`page.print.tpl`** — printable version layout.
* **`list.tpl`** — displays the list of pages inside a category.
* **`list.group.tpl`** — page lists grouped by subfolders.
* **`admin.page.tpl`** — administration page lists layout.
* **`admin.page.add.tpl`** / **`admin.page.edit.tpl`** — page forms in the admin area.
* **`admin.page.manager.tpl`** — Page Manager tool in the admin area.

#### Forum Module (Forums)
* **`forums.sections.tpl`** — main forums page (categories list).
* **`forums.topics.tpl`** — topics list inside a forum section.
* **`forums.posts.tpl`** — posts list and quick reply form inside a topic.
* **`forums.newtopic.tpl`** — new topic creation form.
* **`forums.editpost.tpl`** — post editing form.
* **`admin.forums.tpl`** / **`admin.forums.structure.tpl`** — forum sections administration.

#### Personal File System (PFS)
* **`pfs.tpl`** — file manager layout (folders, file uploads).
* **`pfs.edit.tpl`** — file/folder properties edit form.
* **`pfs.standalone.tpl`** — popup file manager layout (for editor attachments).
* **`pfs.edit.standalone.tpl`** — popup file properties editor.
* **`admin.pfs.tpl`** — user file system management in the admin area.

#### Users Module (Users)
* **`users.tpl`** — general registered users list.
* **`users.details.tpl`** — public details profile card.
* **`users.profile.tpl`** — user cabinet profile edit form.
* **`users.register.tpl`** — registration form.
* **`users.auth.tpl`** — login/authentication page.
* **`users.edit.tpl`** — user properties editor (for moderators/admins).
* **`admin.users.tpl`** — user accounts management in the admin area.

#### Private Messages (PM)
* **`pm.tpl`** — lists of inbox, sentbox, and archived messages.
* **`pm.send.tpl`** — send message form.
* **`admin.pm.tpl`** / **`admin.pm.stat.tpl`** — private messages statistics in the admin area.

#### Polls Module (Polls)
* **`polls.tpl`** — poll results view.
* **`polls.poll.tpl`** — voting form.
* **`polls.standalone.tpl`** — poll view for widgets or sidebar blocks.
* **`admin.polls.tpl`** — polls management in the admin area.

#### Gallery Module (Gallery)
* **`gallery.home.tpl`** — gallery homepage (list of albums).
* **`gallery.browse.tpl`** — photo grid of selected album.
* **`gallery.details.tpl`** — photo details page with comments/ratings.
* **`admin.gallery.tpl`** / **`admin.config.gallery.tpl`** — gallery management in the admin area.

---

## 9.5. Template Engine PHP API (for Developers)

Module and plugin developers interact with the template engine using an instance of the `XTemplate` class.

### 9.5.1. Creating an Instance and Compilation
The constructor accepts the path to the template file:
```php
$t = new XTemplate(sed_skinfile('index'));
```
The `sed_skinfile('filename')` function checks for the presence of the template file in the active skin folder, returning the correct path.

### 9.5.2. Assigning Variables
The `assign()` method transfers variables to the template:
```php
// Assign single variable
$t->assign('PAGE_TITLE', $page_title);

// Bulk assign via associative array
$t->assign(array(
    'PAGE_ID' => $row['page_id'],
    'PAGE_CAT' => $row['page_cat'],
    'PAGE_OWNER' => $row['page_owner']
));

// Assign array keys prefixing them (e.g., outputs PAGE_ROW_VAR)
$t->assign($row, null, 'PAGE_ROW_');
```

### 9.5.3. Parsing Blocks
After variable assignment inside a block, the block is rendered using `parse()`:
```php
// Rendering nested block inside a loop
foreach ($items as $item) {
    $t->assign(array(
        'ITEM_ID' => $item['id'],
        'ITEM_NAME' => $item['name']
    ));
    $t->parse('MAIN.ITEMS_LIST.ITEM_ROW');
}

// Parsing parent blocks
$t->parse('MAIN.ITEMS_LIST');
$t->parse('MAIN');
```
*Note: Conditions `IF` and loops `FOR` are evaluated **automatically** when parsing their parent blocks. Calling `parse()` on `IF`/`FOR` blocks in PHP code is **not** required.*

### 9.5.4. Retrieving HTML Code and Displaying
* The `$t->text('MAIN')` method returns the compiled HTML output as a PHP string (useful for caching or buffers).
* The `$t->out('MAIN')` method prints the compiled HTML directly to output.

---

## 9.6. Overriding Module and Plugin Templates

Seditio CMS supports overriding module and plugin styles without modifying system core files.

### 9.6.1. Overriding Plugin Templates
When a plugin loads templates (in direct, standalone, or popup modes), the core searches for the `.tpl` file in the following order of priority:
1. **Active theme folder:** `/skins/{active_skin}/plugins/{plugin_code}.tpl` (highest priority — allows overriding default layout under a specific theme).
2. **Default plugin template:** `/plugins/{plugin_code}/tpl/{plugin_code}.tpl` (default template supplied with the plugin).

To support this lookup order in the code, templates must be loaded via `sed_skinfile()`:
```php
// Loads from skin folder if it exists, falling back to plugin directory
$t = new XTemplate(sed_skinfile('myplugin', true));
```
*The second argument `true` tells `sed_skinfile` to look for a plugin template.*

### 9.6.2. Overriding Module Templates
Templates for page views and categories inside the `page` module can be overridden for specific sections:
* If `page.news.tpl` exists in the skin folder, the page module automatically loads it for category `news`.
* If no category-specific file is found, it falls back to the default `page.tpl`.

---

## 9.7. Practical Guide to Creating a Skin

To create a new design theme (skin) for Seditio CMS, follow the structure of the default `sympfy` skin. Let's look at creating a theme with the code name `mytheme`.

> [!NOTE]
> Registered themes are managed in the control panel at `/admin/config?n=edit&o=core&p=skin` (or `index.php?module=admin&m=config&n=edit&o=core&p=skin` without SEF URLs), where the administrator can set the default theme.

### 9.7.1. Skin File Structure
Create a `/skins/mytheme/` folder. Place the following folders and files inside:
* `mytheme.php` — theme configuration and metadata.
* `mytheme.ru.lang.php` — Russian translations for theme layout phrases.
* `mytheme.png` — skin preview image in the admin panel (recommended size: 240x180 pixels).
* `header.tpl` — global header layout.
* `footer.tpl` — global footer layout.
* `index.tpl` — homepage layout.
* `css/style.css` — main stylesheet.
* `js/` — scripts directory.

> [!NOTE]
> A skin does not need to duplicate all module templates (e.g., `page.tpl`, `forums.tpl`, `users.tpl`). If they are missing, Seditio will automatically load standard defaults from module directories (e.g., `/modules/page/tpl/page.tpl`).

### 9.7.2. Theme Configuration (`mytheme.php`)
This file contains the mandatory `SED` header for Seditio, and overrides pagination, breadcrumbs, and plugin layout settings:

```php
<?php
/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=skins/mytheme/mytheme.php
Version=1.0.0
Updated=2026-jul-06
Type=Skin
Name=My Custom Theme
Author=Developer
Url=https://mysite.com
Description=My custom responsive skin for Seditio CMS
[END_SED]
==================== */

// Overriding pagination layout styles
$cfg['pagination'] = '<li class="page-item">%s</li>';
$cfg['pagination_cur'] = '<li class="page-item active"><span class="page-link">%s</span></li>';
$cfg['pagination_arrowleft'] = '<i class="icon-chevron-left"></i>';
$cfg['pagination_arrowright'] = '<i class="icon-chevron-right"></i>';

// Overriding breadcrumbs separator
$cfg['separator'] = '<i class="icon-arrow-right"></i>';

// Interface icons
$out['ic_arrow_up'] = '<i class="icon-arrow-up"></i>';
$out['ic_folder'] = '<i class="icon-folder"></i>';
$out['ic_checked'] = '<i class="icon-check"></i>';
$out['ic_close'] = '<i class="icon-close"></i>';

// RecentItems plugin display masks
$cfg['plu_mask_pages'] = '<span class="date">%3\$s</span> %1\$s ' . $cfg['separator'] . ' %2\$s<br />';
$cfg['plu_mask_comments'] = '<span class="date">%3\$s</span> %1\$s ' . $cfg['separator'] . ' %2\$s (%4\$s)<br />';
```

### 9.7.3. Theme Localization (`mytheme.ru.lang.php` or `mytheme.en.lang.php`)
The file contains translations of layout strings, grouped into the `$skinlang` array:

```php
<?php
/* ====================
[BEGIN_SED]
File=skins/mytheme/mytheme.ru.lang.php
Version=1.0.0
Updated=2026-jul-06
Type=Skin
Name=My Custom Theme (Russian Localization)
[END_SED]
==================== */

// Localization for header.tpl
$skinlang['header']['Login'] = "Вход на сайт";
$skinlang['header']['Register'] = "Регистрация";
$skinlang['header']['Lostyourpassword'] = "Забыли пароль?";

// Localization for index.tpl
$skinlang['index']['Newinforums'] = "Новое на форумах";
$skinlang['index']['Recentadditions'] = "Последние обновления";
$skinlang['index']['Online'] = "Сейчас на сайте";
```
In template files, localized phrases are loaded via: `{PHP.skinlang.header.Login}` or `{PHP.skinlang.index.Online}`.

### 9.7.4. Base Layout of Theme Files

> [!IMPORTANT]
> **Automatic Header and Footer Inclusion:**
> In Seditio CMS, the header (`header.tpl`) and footer (`footer.tpl`) templates are included **automatically** by core controllers (by executing `/system/header.php` and `/system/footer.php` before and after rendering). In content files (like `index.tpl`, `page.tpl`, etc.), you **do not** need to include the header or footer manually via the `{FILE ...}` directive.

#### Header Layout (`header.tpl`)
```html
<!-- BEGIN: HEADER -->{HEADER_DOCTYPE}
<html>
<head>
    <title>{HEADER_TITLE}</title>
    {HEADER_METAS}
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="skins/{PHP.skin}/css/style.css" type="text/css" rel="stylesheet" />
    {HEADER_CSS}
</head>
<body>
    <!-- BEGIN: USER -->
    <div class="userpanel">
        <ul>
            <li><span>{HEADER_LOGSTATUS}</span></li>
            <!-- IF {HEADER_USER_ADMINPANEL} -->
            <li>{HEADER_USER_ADMINPANEL}</li>
            <!-- ENDIF -->
            <!-- IF {HEADER_USER_PROFILE} -->
            <li>{HEADER_USER_PROFILE}</li>
            <!-- ENDIF -->
            <!-- IF {HEADER_USER_LOGINOUT} -->
            <li>{HEADER_USER_LOGINOUT}</li>
            <!-- ENDIF -->
        </ul>
    </div>
    <!-- END: USER -->

    <header class="main-header">
        <div class="logo">
            <a href="{PHP.sys.dir_uri}" alt="{HEADER_TITLE}">{PHP.cfg.maintitle}</a>
        </div>
        <div class="menu">
            {PHP.sed_menu.1.childrens}
        </div>
    </header>
<!-- END: HEADER -->
```

#### Footer Layout (`footer.tpl`)
```html
<!-- BEGIN: FOOTER -->
    <footer class="main-footer">
        <div class="copyright">{FOOTER_BOTTOMLINE}</div>
        <div class="stats">
            {FOOTER_CREATIONTIME}<br />{FOOTER_SQLSTATISTICS}
        </div>
        {FOOTER_DEVMODE}
    </footer>
    {FOOTER_JAVASCRIPT}
</body>
</html>
<!-- END: FOOTER -->
```

#### Homepage Layout (`index.tpl`)
```html
<!-- BEGIN: MAIN -->
<div class="content-wrapper">
    <section class="intro">
        <h1>{PHP.cfg.maintitle}</h1>
        <p>{PHP.cfg.subtitle}</p>
    </section>
    
    <aside class="sidebar-box">
        <h3>{PHP.skinlang.index.Online}</h3>
        <p><a href="{PHP.out.whosonline_link}">{PHP.out.whosonline}</a> : {PHP.out.whosonline_reg_list}</p>
    </aside>
</div>
<!-- END: MAIN -->
```
