# Chapter 11. Localization and Internationalization (i18n)

This chapter discusses the architecture of multi-language support (internationalization) in Seditio CMS, rules for location and structure of core, module, plugin, and theme language files, as well as the logic for automatic loading and overriding of localizations.

---

## 11.1. Multi-language Support in Seditio CMS

Seditio CMS was designed from the ground up to support multiple languages for both the interface (control panel, system messages, design themes) and structural elements (page categories and forum sections).

### 11.1.1. Active Language Detection
The interface language for each visitor is determined during page loading in the system core:
* **For Registered Members:** The language is read from the user's profile (`user_lang` field in the DB) and saved in the `$usr['lang']` variable.
* **For Guests:** The default value from the global configuration file (`datas/config.php`) is used, which is stored in `$cfg['defaultlang']` (typically `'en'` or `'ru'`).
* **Final Selection:** The selected two-character language code (e.g., `'ru'`, `'en'`, `'tr'`) is written to the global `$lang` variable, which is used in all subsequent localization queries.

### 11.1.2. Site Structure Localization
To support multilingual page categories (structure) and forum sections, alternative titles and description fields are available in the administrator panel. This allows categories to be displayed in the user's active language without duplicating the category tree.

---

## 11.2. Core Language Pack File Structure

All language packs for the Seditio CMS core are located in the system directory `/system/lang/`.

### 11.2.1. Language Directories
Within `/system/lang/`, a separate subdirectory is created for each language using its two-character ISO 639-1 code:
* `/system/lang/en/` — English language pack.
* `/system/lang/ru/` — Russian language pack.
* `/system/lang/tr/` — Turkish language pack.

### 11.2.2. Core Language Pack Composition
Inside each language folder, three main files are located:
1. **`main.lang.php`** — contains localization for the public area (buttons, headers, form texts, dates, permission labels). All phrases are written to the global `$L` array (e.g., `$L['Home'] = "Home";`).
2. **`admin.lang.php`** — localization for the administration panel. Used only in the control panel (`SED_ADMIN`) and populates admin phrases.
3. **`message.lang.php`** — localization for system alerts, errors, and notifications (e.g., success messages, validation errors, SEF URL errors, 404 errors).

---

## 11.3. Module and Plugin Localization

Each additional module and plugin in Seditio is an autonomous component and contains its own localization files.

### 11.3.1. Language Files Location
* **Modules:** Module language files are located in the module directory:
  `/modules/{module_code}/lang/{module_code}.{language_code}.lang.php`
  *Example:* `/modules/page/lang/page.ru.lang.php` — Russian localization file for the Page module.
* **Plugins:** Plugin language files are located in the plugin directory:
  `/plugins/{plugin_code}/lang/{plugin_code}.{language_code}.lang.php`
  *Example:* `/plugins/recentitems/lang/recentitems.ru.lang.php` — Russian localization file for the RecentItems plugin.

### 11.3.2. Configuration Localization (Prefix `cfg_`)
Seditio CMS uses module and plugin localization files to translate option titles and descriptions displayed in the configuration area of the control panel.
If a module or plugin has a registered configuration option (e.g., `maxpages` in the `recentitems` plugin), an array element with the `cfg_` prefix is declared in the language file to translate it:
```php
$L['cfg_maxpages'] = array("Page limit", "Specify the maximum number of pages to display in the block");
```
* **First array element** — human-readable short option name.
* **Second array element** — detailed tooltip/description for the option.

### 11.3.3. Component Interface Localization
All other strings required for controller execution or display in TPL templates are written to the global `$L` array using unique prefixes to prevent name collisions:
```php
$L['pag_titletooshort'] = "Title is too short or missing";
$L['lis_submitnew'] = "Submit new entry";
```

---

## 11.4. Language Resolution and Loading (sed_langfile)

To properly include localization files, Seditio uses the system function `sed_langfile()` from [system/functions.php](../../system/functions.php).

### 11.4.1. Function Signature
```php
function sed_langfile($code, $type = 'plugin', $lang = null)
```
* **`$code`** (string) — unique component code (plugin or module code).
* **`$type`** (string, optional) — component type (`'plugin'` or `'module'`). Default is `'plugin'`.
* **`$lang`** (string, optional) — explicitly requested language. If not specified, the active visitor language `$usr['lang']` or default `$cfg['defaultlang']` is used.

### 11.4.2. Resolution and Fallback Logic
The function searches for the file on disk according to the following priorities:
1. **Target Language:** Searches for the file matching the requested language. E.g., for the plugin `myplugin` and Russian visitor language, the path will be: `/plugins/myplugin/lang/myplugin.ru.lang.php`.
2. **English Fallback:** If the target language file is not found (e.g., the visitor has French `'fr'` language selected but the plugin only has English translations), the system falls back to the English file: `/plugins/myplugin/lang/myplugin.en.lang.php`.
3. **Result:** The function returns the absolute path to the existing file on disk, or an empty string if no localizations are found.

Example of loading languages in plugin code:
```php
$langfile = sed_langfile('myplugin', 'plugin');
if (!empty($langfile)) {
    require($langfile);
}
```

---

## 11.5. Sequence of Language Initialization on Page Load

Initialization of the language environment occurs during early core startup in the system file [system/common.php](../../system/common.php). File loading is strictly sequenced:

### 11.5.1. Loading Core Localization
First, the main core system language file is loaded:
```php
$mlang = SED_ROOT . '/system/lang/' . $usr['lang'] . '/main.lang.php';
// ...
require($mlang);
```
After this, default site localization arrays become available (e.g., dates, UI labels, core headers).

### 11.5.2. Auto-loading Active Module Languages
To make common module strings (such as category titles, page title prefixes `$L['core_page']`, etc.) available globally (e.g., when rendering header menus or footer widgets), the core iterates over the active modules array `$sed_modules` and includes their language files:
```php
foreach ($sed_modules as $mod_code => $mod_row) {
    if ($mod_lang_file = sed_langfile($mod_code, 'module', $lang)) {
        include_once($mod_lang_file);
    }
}
```

### 11.5.3. Overriding Strings at Skin (Theme) Level
The active site skin has the highest priority in localization. After loading all core and module files, Seditio checks for a language file inside the active theme directory (e.g., `skins/sympfy/sympfy.ru.lang.php`):
```php
if (@file_exists($usr['skin_lang'])) {
    require($usr['skin_lang']);
}
```
This allows layout designers to override any core or module language string (via the `$skinlang` array) directly at the active skin level without modifying Seditio's original PHP source files.

---

## 11.6. Practical Guide to Creating a Multilingual Plugin

Let's create localization files for a custom plugin `myplugin` that registers a single text configuration option `banner_text`.

### 11.6.1. Creating Language Directory
Inside `/plugins/myplugin/`, create a `lang/` subdirectory. Inside it, create two files: `myplugin.en.lang.php` (English) and `myplugin.ru.lang.php` (Russian).

### 11.6.2. English Language File `myplugin.en.lang.php`
```php
<?php
/* ====================
[BEGIN_SED]
File=plugins/myplugin/lang/myplugin.en.lang.php
Version=1.0.0
Updated=2026-jul-06
Type=Plugin.lang
Name=My Plugin (English Localization)
[END_SED]
==================== */

if (!defined('SED_CODE')) exit();

// Admin panel plugin settings translation
$L['cfg_banner_text'] = array("Banner html text", "Enter the text or HTML markup to display in the header banner.");

// Public area display strings
$L['myplug_welcome'] = "Welcome to our website!";
$L['myplug_readmore'] = "Read more";
```

### 11.6.3. Russian Language File `myplugin.ru.lang.php`
```php
<?php
/* ====================
[BEGIN_SED]
File=plugins/myplugin/lang/myplugin.ru.lang.php
Version=1.0.0
Updated=2026-jul-06
Type=Plugin.lang
Name=My Plugin (Russian Localization)
[END_SED]
==================== */

if (!defined('SED_CODE')) exit();

// Admin panel plugin settings translation
$L['cfg_banner_text'] = array("HTML текст баннера", "Введите текст или HTML-разметку для отображения в баннере шапки.");

// Public area display strings
$L['myplug_welcome'] = "Добро пожаловать на наш сайт!";
$L['myplug_readmore'] = "Читать далее";
```

### 11.6.4. Usage in PHP Plugin Code (`myplugin.php`)
```php
<?php
if (!defined('SED_CODE')) exit();

// Load language file (auto language detection or English fallback)
$langfile = sed_langfile('myplugin', 'plugin');
if (!empty($langfile)) {
    require($langfile);
}

// Render template (no need to manually transfer $L strings to the template engine)
$t->parse("MAIN");
$t->out("MAIN");
```

> [!NOTE]
> **Automatic vs. Manual Plugin Language Loading:**
> * **Automatic:** The Seditio core automatically resolves and includes the language file of the plugin when processing its standalone sections (`standalone`, `popup`) and when outputting settings in the control panel. In these executable files, calling `sed_langfile()` manually is not required.
> * **Manual:** In all other hook-handling parts (e.g., `index.tags`, `header.tags`, `users.register.add.first`), the core does not autoload plugin languages. The developer must include them manually: `if ($f = sed_langfile('plugin_code', 'plugin')) { require($f); }`.

> [!TIP]
> **Avoiding Redundant `assign` Method Calls:**
> In Seditio CMS, you do not need to register each language string of a plugin in the template engine using `$t->assign()`. Since the template engine supports the global context prefix `PHP.`, any language phrase from the `$L` array (once loaded) is accessible in TPL templates directly via `{PHP.L.var_name}` syntax. This keeps PHP code clean and lightweight.

### 11.6.5. Usage in TPL Template (`myplugin.tpl`)
```html
<!-- BEGIN: MAIN -->
<div class="welcome-banner">
    <h2>{PHP.L.myplug_welcome}</h2>
    <p>{PHP.cfg.plugin.myplugin.banner_text}</p>
    <a href="index.php?module=page" class="btn">{PHP.L.myplug_readmore}</a>
</div>
<!-- END: MAIN -->
```
When the page is opened by a visitor with Russian selected, Seditio renders *"Добро пожаловать на наш сайт!"*, and for an English visitor — *"Welcome to our website!"*, reading values directly from the global `$L` array.
