# Installation and Configuration Instructions for the i18n Plugin for Seditio CMS

The `i18n` plugin is designed to organize multilingual content for pages and categories (structure). It allows translating titles, descriptions, page body (including the second text field `text2`), and all SEO metadata.

---

## 1. Installation and Configuration

1. Copy the plugin files to the `plugins/i18n/` directory.
2. Go to the Admin Panel -> **Plugins** (at `/admin/plug` or `index.php?module=admin&m=plug` without SEF) and install the **i18n** plugin. During installation, the necessary database tables will be created automatically (`{prefix}i18n_pages` and `{prefix}i18n_structure`).
3. In the plugin settings (at `/admin/plug` or `index.php?module=admin&m=plug` without SEF inside the i18n configuration section), specify the translation language codes separated by commas.
   - *Example:* `en,de`
   - *Note:* The default website language (e.g., `ru`) **does not** need to be specified. It is considered the base language.

---

## 2. Integration into Theme Templates (TPL)

To make tabs for other languages appear in the page and category add/edit forms, you need to add the corresponding tags to your `.tpl` files.

### A. Adding Pages (`skins/your_theme/page.add.tpl` or `modules/page/tpl/page.add.tpl`)

Find the tab headers block (class `tabs` inside `sedtabs`) and add the tab headers tag:
```html
<ul class="tabs">
    <li><a href="{PHP.sys.request_uri}#tab1" class="selected">{PHP.L.Page}</a></li>
    <li><a href="{PHP.sys.request_uri}#tab2">{PHP.L.Meta}</a></li>
    <li><a href="{PHP.sys.request_uri}#tab3">{PHP.L.Options}</a></li>
    <!-- Tabs for translation -->
    {PAGEADD_I18N_TABS_HEADERS}
</ul>
```

Then find the closing tag of the tab box container (`tab-box`) and add the tab body tag right before it:
```html
<div class="tab-box">
    ... (standard tabs tab1, tab2, tab3) ...

    <!-- Translation tabs content -->
    {PAGEADD_I18N_TABS_BODY}
</div>
```

---

### B. Editing Pages (`skins/your_theme/page.edit.tpl` or `modules/page/tpl/page.edit.tpl`)

Make similar changes in the edit form.

Add headers:
```html
<ul class="tabs">
    <li><a href="{PHP.sys.request_uri}#tab1" class="selected">{PHP.L.Page}</a></li>
    <li><a href="{PHP.sys.request_uri}#tab2">{PHP.L.Meta}</a></li>
    <li><a href="{PHP.sys.request_uri}#tab3">{PHP.L.Options}</a></li>
    <!-- Tabs for translation -->
    {PAGEEDIT_I18N_TABS_HEADERS}
</ul>
```

Add the tab body:
```html
<div class="tab-box">
    ... (standard tabs tab1, tab2, tab3) ...

    <!-- Translation tabs content -->
    {PAGEEDIT_I18N_TABS_BODY}
</div>
```

---

### C. Editing Categories (`skins/your_theme/admin.page.tpl` or `modules/page/tpl/admin.page.edit.tpl`)

Integrate tags into the category settings edit section (`STRUCTURE_UPDATE`).

Add headers:
```html
<ul class="tabs">
    <li><a href="{PHP.sys.request_uri}#tab1" class="selected">{PHP.L.Structure}</a></li>
    <li><a href="{PHP.sys.request_uri}#tab2">{PHP.L.Metadata}</a></li>
    <!-- Tabs for structure translation -->
    {STRUCTURE_UPDATE_I18N_TABS_HEADERS}
</ul>
```

Add the tab body:
```html
<div class="tab-box">
    ... (standard tabs tab1, tab2) ...

    <!-- Structure translation tabs content -->
    {STRUCTURE_UPDATE_I18N_TABS_BODY}
</div>
```

---

## 3. How It Works on the Website

1. When the administrator adds or edits a page/category, they will see tabs (e.g., **EN**, **DE**).
2. After filling in the fields on these tabs and saving the form, translations will be saved in the database tables.
3. When a visitor views the website:
   - If their language matches the default language (`$cfg['defaultlang']`), the base text from the main tables is displayed.
   - If their language is switched (e.g., to `en`), the plugin automatically replaces titles, descriptions, page bodies, and metatags with the translated values.
   - If a translation for the selected language is missing for some element, a **Fallback** mechanism is triggered, and the page will be displayed in the default language.

---

## 4. Language Switcher on the Website

To allow guests and visitors to switch the language of the website, add the `{HEADER_I18N_SELECTOR}` tag to the header template of your site (`skins/your_theme/header.tpl`).

This tag outputs a block with links to the available translation languages (the currently active language is automatically hidden, leaving only alternative options to switch to).

For example, when viewing the website in Russian, the switcher will display a link only to English:
```html
<div class="i18n-switcher">
    <a href="/some-page?id=123&lang=en" class="i18n-lang-en">EN</a>
</div>
```

You can style this widget in your theme's CSS file, for example:
```css
.i18n-switcher a {
    text-decoration: none;
    margin: 0 5px;
    font-weight: bold;
}
```
