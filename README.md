# Seditio CMS

**Seditio** is a powerful, fast, and flexible Content Management System (CMS) and Content Management Framework (CMF) powered by PHP and MySQL.

The key distinguishing feature of Seditio is the complete separation of HTML design and layout from PHP business logic, achieved using the **XTemplate SE** template engine. This enables web designers and front-end developers to craft rich themes and skins without requiring deep PHP knowledge.

---

## 🚀 Key Features

- **High Performance & Optimization**: Minimal database load with built-in two-level caching system.
- **Flexible Access Control (ACL)**: Granular per-category and per-module permissions for user groups.
- **Modular & Extensible**: Event hook-driven plugin system and standalone core modules.
- **Strict Separation of Logic & Presentation**: Clean TPL templates powered by XTemplate SE.
- **Personal File System (PFS)**: Per-user file management, quota system, and seamless media integration.
- **PHP 5.6 to 8.x Compatibility**: Full support across legacy and modern PHP environments.

---

## 📚 Documentation (Manual)

Comprehensive user and developer documentation is available in the [`docs/manual/en`](docs/manual/en/) directory.

*(Note: Documentation is also available in Russian in [`docs/manual/ru`](docs/manual/ru/)).*

### 📖 User & Developer Manual (`docs/manual/en`)

1. 📖 [Introduction and System Architecture](docs/manual/en/1_Introduction_and_System_Architecture.md) — History, philosophy, directory structure, global variables, and system architecture.
2. 🛠️ [Installation, Configuration, and Deployment](docs/manual/en/2_Installation_Configuration_and_Deployment.md) — Server requirements, installation steps, directory permissions (CHMOD), and configuration.
3. ⚙️ [Control Panel (Administration Area)](docs/manual/en/3_Control_Panel_Administration_Area.md) — Admin interface overview, managing categories, pages, users, and system settings.
4. 🔐 [User System and Access Control (ACL)](docs/manual/en/4_User_System_and_Access_Control_ACL.md) — ACL access matrix, permission masks (Read/Write/Admin), and group management.
5. 📦 [Core Modules](docs/manual/en/5_Core_Modules.md) — Overview of built-in modules (Pages, Forums, PM, PFS, Users, Polls, etc.).
6. 🧩 [Module Architecture and Development](docs/manual/en/6_Module_Architecture_and_Development.md) — Module file structure, URL routing, and database interactions.
7. 🔌 [Core Plugins](docs/manual/en/7_Core_Plugins.md) — Built-in plugins overview (Cleaner, Hitcounter, CKEditor, etc.).
8. ⚡ [Plugin Architecture and Development](docs/manual/en/8_Plugin_Architecture_and_Development.md) — `.setup.php` manifest files, event hook system, and building custom plugins.
9. 🎨 [XTemplate SE Template Engine and Skin Development](docs/manual/en/9_XTemplate_SE_Template_Engine_and_Skin_Development.md) — XTemplate syntax, `BEGIN/END` blocks, `TPLTAG` tags, `IF/ELSE` logic, and skin creation.
10. 🛡️ [Security and Performance Optimization](docs/manual/en/10_Security_and_Performance_Optimization.md) — SQL escaping (`sed_sql_prep`), input sanitation (`sed_import`), XSS/CSRF protections, and caching engine.
11. 🌐 [Localization and Internationalization](docs/manual/en/11_Localization_and_Internationalization.md) — Language files organization (`lang/*.lang.php`) and multi-language configuration.
12. 📁 [Personal File System (PFS) and Media Processing](docs/manual/en/12_Personal_File_System_and_Media_Processing.md) — File uploads, thumbnail generation, storage quotas, and editor integration.

---

## 💻 System Requirements

To install and run Seditio, you need the following server environment pre-installed:

- **Web Server**: Apache, Nginx, or IIS with URL Rewrite (SEF URLs) support.
- **PHP**: Version 5.6.0 or higher (fully compatible with PHP 7.x and PHP 8.x).
- **Database**: MySQL 5.0.7 or higher / MariaDB.
- **Required PHP Extensions**:
  - `gd` (image processing & thumbnail creation)
  - `hash`
  - `mbstring` (multibyte string handling)
  - `mysqli`
  - `pcre`
  - `session`

---

## ⚙️ Installation

1. Copy all engine files to your web server root directory or a subdirectory.
2. Make the following directories and all their subdirectories writable with `CHMOD 777` or `CHMOD 775`:
   - `/datas/avatars`
   - `/datas/defaultav`
   - `/datas/photos`
   - `/datas/thumbs`
   - `/datas/resized`
   - `/datas/signatures`
   - `/datas/users`
3. Open your web browser and navigate to `http://your_domain/install` (or `http://your_domain/path_to_seditio/install`).
4. Follow the step-by-step instructions of the setup wizard.

---

## 📄 License and Copyright

- **Copyright (c) 2011-2026, Seditio Team**
- **Copyright (c) 2001-2011, Neocrome**

Seditio is free software; you can redistribute it and/or modify it under the terms of the **BSD 3-Clause License**.

For full license details, please see the [LICENSE](LICENSE) file.
