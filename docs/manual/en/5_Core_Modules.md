# Chapter 5. Core Modules

This chapter discusses in detail the architecture, internal design, data storage structure, and capabilities of the seven key built-in modules of Seditio CMS: Pages (`page`), Forums (`forums`), Users (`users`), Personal File System (`pfs`), Private Messages (`pm`), Polls (`polls`), and Gallery (`gallery`).

> [!NOTE]
> Control panels for built-in modules (located in `admin/` subdirectories) are connected dynamically by the core. A detailed description of the routing mechanism for built-in module admin panels is provided in [Chapter 3. Control Panel (Administration Area)](/doc/admin-panel#313-internal-admin-panel-routing-adminincphp).

---

## 5.1. Page Module (page)

The page module is the primary content component of Seditio. It is responsible for creating, structuring, editing, sorting, and outputting text and multimedia materials on the site. The main module code is concentrated in the `modules/page` directory.

### 5.1.1. Category Tree and Content Structure
The entire hierarchy of page sections (categories) is stored in a single structure table `sed_structure`.
* Each category is identified by a unique text code in the `structure_code` field (e.g., `news`, `articles`, `sport`).
* The hierarchical relationship (nested sections) is built based on the path field `structure_path`. For example, if a parent section has the path `1`, then its subcategory will receive the path `1.1`, and a subcategory of the next level will receive `1.1.1`.
* Page data is linked to categories via the `page_cat` field of the `sed_pages` table, which references the corresponding `structure_code`.

### 5.1.2. Page Life Cycle and Page Manager
Each page in the `sed_pages` table passes through a specific cycle of states controlled by the `page_state` field:
* **Published (`page_state = 0`):** The page is fully active and visible to all site visitors who have Read permissions for this category.
* **Pending Validation (`page_state = 1`):** The page has been submitted for moderation. It is not displayed in general content lists for regular users. Moderators and administrators see such pages in a special "Validation Queue" panel and can approve their publication.
* The author of the material is the user whose ID is recorded in the `page_ownerid` field (linked via a `LEFT JOIN` relation to the `sed_users` table).

For bulk content management, administrators have access to the **Page Manager** (script `page.admin.manager.php`), which opens in the control panel at `/admin/page?s=manager` (or `index.php?module=admin&m=page&s=manager` without SEF URLs). With its help, you can:
* Quickly filter all site pages by a specific category.
* Track page statuses (published/pending validation).
* Perform bulk moderation:
  * Approve publications: link `/admin/page?a=validate&id=X` (or `index.php?module=admin&m=page&a=validate&id=X` without SEF URLs).
  * Temporarily unvalidate pages: link `/admin/page?a=unvalidate&id=X` (or `index.php?module=admin&m=page&a=unvalidate&id=X` without SEF URLs).
  * Clone existing materials: link `/admin/page?s=add&id=X&a=clone` (or `index.php?module=admin&m=page&s=add&id=X&a=clone` without SEF URLs), handled by the controller `modules/page/page.add.php` when `$a == 'clone'`.
  * Permanently delete pages: link `/admin/page?s=manager&a=delete&id=X` (or `index.php?module=admin&m=page&s=manager&a=delete&id=X` without SEF URLs).

### 5.1.3. Page Sorting in Categories
Seditio supports flexible sorting configuration individually for each category of the structure. Default sorting parameters are defined in category settings (`sed_structure` table):
* `structure_order` — database column by which sorting occurs:
  * `date` (by publication date `page_date`).
  * `title` (alphabetically by title `page_title`).
  * `id` (by unique ID `page_id`).
  * `owner` (by author ID `page_ownerid`).
  * `count` (by popularity/views `page_count`).
* `structure_way` — sorting direction:
  * `asc` — ascending (from A to Z, oldest to newest).
  * `desc` — descending (from Z to A, newest to oldest).

These parameters are taken into account by the core when generating page lists (controller `modules/page/page.php`), but in Page Manager or custom selections, the sorting order can be overridden via GET parameters `sm` (sorted field) and `wm` (direction).

### 5.1.4. Content Parsing and Formatting
When creating and editing pages, content is saved in the `page_text` field of the `sed_pages` table. To format content, HTML markup is used, integrated with visual WYSIWYG editors (the **CKEditor** plugin is provided by default).

To ensure the safety and neatness of texts, Seditio uses the **Jevix** typographical filter (class `plugins/jevix/inc/jevix.class.php`), which is integrated into the core's incoming data filtering system:
* **Integration Point (Hook `import.filter`):** When importing HTML data from forms via the `sed_import()` function using the `'HTM'` filter type, the Seditio core automatically calls plugins registered to the `import.filter` hook (script `plugins/jevix/jevix.import.filter.php`).
* **Dynamic Filtering Strictness:** Depending on the current system section (the value of the `$flocation` / `$location` variable), the plugin selects one of the predefined strictness levels of Jevix settings (e.g., `full` for pages and plugins, `medium` for private messages and comments, `micro` for PFS, gallery, and polls).
* **Security (XSS Filtering):** It cleans incoming HTML code from dangerous tags and scripts. It permits only tags from the white list of the corresponding complexity profile (`<p>`, `<span>`, `<a>`, `<img>`, etc.) and strictly controls their attributes (for example, it permits loading `<iframe>` only from trusted video hostings like `youtube.com` or `vimeo.com`).
* **Typographer:** Performs automatic text formatting: converts regular quotes into typographer quotes (“ ”), hyphens between words into dashes (—), corrects non-breaking spaces, and closes broken HTML tags.
* **Exceptions for Administrators:** In the plugin configuration, you can allow administrators to insert HTML code directly, bypassing Jevix filtering (option `use_for_admin = no`).

For search engine optimization (SEO), friendly URLs (SEF URLs) are supported. If the "Alias" field (`page_alias`) is filled when creating a page, the core will replace the numeric ID with it in the SEF URL (e.g., `/news/my-article` instead of `/news/12`). Furthermore, Seditio supports full management of SEO parameters: manual filling of meta tags title (`page_seo_title`), description (`page_seo_desc`), keywords (`page_seo_keywords`), and a separate H1 header (`page_seo_h1`), which is displayed on the page instead of the default `page_title`.

### 5.1.5. Page Extrapoles
If the standard fields of the `sed_pages` table (title, text, description, date) are insufficient, the administrator can create custom fields (extrapoles) using the directory constructor at `/admin/dic` (or `index.php?module=admin&m=dic` without SEF URLs; for details, see [Chapter 3. Control Panel (Administration Area)](/doc/admin-panel#36-managing-directories-and-cck-extra-fields-mdic)). New fields (e.g., price `page_price` or source link `page_source`) are physically added to the `sed_pages` table and output in the `page.tpl` template via corresponding tags `{PAGE_PRICE}` and `{PAGE_SOURCE}`.

---

## 5.2. Forums Module (forums)

The forums module is a full-featured built-in message board system. All module code is located in the `modules/forums` directory.

### 5.2.1. Section Structure and Unlimited Subforum Nesting
The forum architecture is based on three main database tables:
1. `sed_forum_sections` (Forum Sections): Stores the structure of categories and branches.
2. `sed_forum_topics` (Forum Topics): Stores topic titles, author, creation date, and view/reply statistics.
3. `sed_forum_posts` (Forum Posts): Stores the actual text of user messages.

**Unlimited Nesting of Subforums:**
Forums in Seditio support building section trees of absolutely any depth. Linking child categories to parent ones is carried out using two key fields in the `sed_forum_sections` table:
* `fs_id` — unique numeric identifier of the forum branch.
* `fs_parentcat` — identifier of the parent forum branch. If the branch is a root section, the value of `fs_parentcat` is `0`. When creating a subforum, the `fs_id` of its parent is written into this field.
The core recursively scans the sections table when building the forum tree, which allows building complex multi-level section structures without nesting limitations.

### 5.2.2. Moderation and Topic Management
Moderators and administrators with moderating rights (Admin bit = 128 in ACL for the corresponding forum category) have a set of tools to control discussions:
* **Deleting Topics and Posts:** A moderator can completely delete a topic (`a=delete`) or an individual post (`a=delete`).
* **Pinning Topics (`sticky`):** Pinning a topic at the top of the list with an "Important" flag (the topic is always displayed first in the section).
* **Locking Topics (`lock`):** Locking a topic to prevent regular users from posting new messages.
* **Creating Announcements (`announcement`):** Changing the status of a topic to an announcement.
* **Bumping Topics (`bump`):** Updating the last modified date of a topic to move it to the top of the list without adding posts.
* **Private Topics (`private`):** Making a topic visible only to administrators and the topic creator.
* **Moving Topics (`move`):** Moving the entire topic with all posts to another forum category, with automatic recalculation of message statistics in both sections, and an option to leave a ghost topic (`ghost`).
* **Resetting Topic Settings (`clear` / `resetr`):** Resetting all special statuses of a topic and clearing post ratings.
* **Anti-bump Protection:** The forum module includes a system anti-spam option **Antibump (`antibumpforums`)**. If active, users cannot post multiple messages in a row in a single topic. Adding a second consecutive message is blocked with a warning until another user replies to the topic.

### 5.2.3. Forum Poll Integration
When creating a topic on the forum, a user (provided they have rights) can attach a poll to it. The logic of forum polls is integrated with the general polls module (`polls`). A poll linked to the forum topic ID is created in the DB. Users can vote directly inside the first post of the topic, and the results are displayed as an interactive graph of the vote percentages.

---

## 5.3. Users Module (users)

The users module manages new account registrations, authentication, personal profiles, user list outputs, and account moderation. The entire module code is concentrated in the `modules/users` directory.

> [!IMPORTANT]
> The `users` module is a locked system core module (`Lock_module = 1` in the configuration file `modules/users/users.setup.php`), so it cannot be disabled or removed from the system.

> [!NOTE]
> The architecture of access rights delimitation (ACL), sessions, cookie authorization, and secure salted password hashing is described in detail in [Chapter 4. User System and Access Control (ACL)](/doc/users-acl). The `users` module acts as an interface wrapper over these system mechanisms.

### 5.3.1. Main Components and Controllers of the Module
The module is divided into several controller files, each responsible for its own part of the logic:
* **Login and Logout (`users.auth.php`, `users.logout.php`):**
  * `users.auth.php` is responsible for displaying the login form (template `users.auth.tpl`) and processing incoming POST data (username/password). On successful validation, a session or an autologin cookie is created.
  * `users.logout.php` destroys the user session, deletes persistent authentication cookies, and performs a redirect to the main page.
* **Registration (`users.register.php`):**
  Manages the creation of new accounts. It outputs the registration form (`users.register.tpl`), checks email and username uniqueness, and validates data. By default, it creates a record in the `sed_users` table with the "Inactive" status (ID = 2), generates an activation hash, and sends it to the user's email. After clicking the activation link, the status changes to "Members" (ID = 4).
* **Password Recovery (`users.passrecover.php`):**
  Implements a secure password reset mechanism. If a user has forgotten their password, the controller generates a temporary secret reset code and sends a link to the email. Upon clicking, a new random password is generated and sent to the user.
* **Personal Profile (`users.profile.php`):**
  Allows authenticated users to edit their personal details: change email, password, avatar (`user_avatar`), photo (`user_photo`), timezone, interface language, skin, and fill custom extrapoles. Data is output and edited via the `users.profile.tpl` template.
* **User Details (`users.details.php`):**
  This controller is responsible for displaying public information about any registered user (template `users.details.tpl`). It displays the registration date, last visit date, group, and activity statistics (number of forum topics created, posts written, and pages added).
* **User List (`users.main.php`):**
  Outputs the general table of registered users with pagination. It supports filtering by the first letter of the name, sorting by database columns (name, ID, group, registration date), and searching. Rendered via the `users.tpl` template.
* **Admin Editing (`users.edit.php`):**
  An interface for moderators and administrators (with rights to edit users). It allows changing the user's group (including the main group `user_maingrp`), activating inactive accounts, modifying profile data, or completely deleting a user from the site. Template `users.edit.tpl`.

**Ability to Pause Parts of the Module:**
The Seditio core allows temporarily disabling (pausing) individual functional controller files of the `users` module using the built-in `sed_dieifdisabled_part()` function. Through the administrative control panel, you can disable registration (`register`), profiles (`profile`), detail cards (`details`), user lists (`main`), and even logouts (`logout`). The only non-disableable part is authorization (`auth`) — checks for its status are absent in the code to guarantee that administrators can always log in.

---

## 5.4. Personal File System Module (pfs)

The PFS (Personal File System) module is the system manager for uploading and storing user files. It is accessible both from the personal cabinet and during content editing. The module code is located in the `modules/pfs` directory.

### 5.4.1. PFS, Folder Hierarchy, and Disk Space Quotas
All uploaded files are distributed across user folders:
* **Hierarchical Folder Structure:** The `sed_pfs_folders` table stores information about user folders and albums. The system supports **full hierarchy (nested folders)**. Each folder has a unique ID, and linking to a parent folder is done via the `pff_parentid` column. If a folder is in the user's PFS root, the `pff_parentid` value is `0`. When creating a nested folder, the ID of the parent folder is recorded in this field. Folders are also separated by access types into "Private" and "Public" (`pff_type`).
* The `sed_pfs` table registers the files themselves (system name on the server, original name, size, file type, upload date, owner ID).
* **Quotas and Limits:** Rigid disk space limits are defined in user group settings (ACL). Each group is assigned a maximum total upload volume (e.g., 50 MB for regular users) and a maximum size for a single file. When the quota is exceeded, PFS will block uploading new files until the user clears their storage.

### 5.4.2. PFS Integration into CMS Interface
PFS is tightly integrated with visual text editors (primarily CKEditor):
* When creating a page or writing a message on the forum, the user can open a popup PFS window to select files.
* In the popup PFS window, next to each file, special quick action icons are displayed to insert content into the editor:
  * **Full-size image:** Inserts the image in its original resolution via the HTML `<img>` tag.
  * **Clickable thumbnail:** Inserts a scaled copy of the image (`thumb`) linked to the original file.
  * **File link:** Inserts a regular text link to download or view any file.

---

## 5.5. Private Messages Module (pm)

The `pm` module provides secure internal correspondence between registered users without exposing their personal email addresses. The module code is located in the `modules/pm` directory.

### 5.5.1. Message Delivery Mechanism
* Correspondence is stored in the `sed_pm` table. Each message contains sender ID (`pm_fromuserid`), recipient ID (`pm_touserid`), subject, text, precise sending date, and status `pm_state`.
* **Private Message States (`pm_state`):**
  * `0` — New (unread) message. It is highlighted for the recipient.
  * `1` — Read message.
  * `2` — Message moved to archive.
* The engine splits correspondence into three virtual folders: Inbox, Sentbox, and Archive.

### 5.5.2. Notifications of New Messages
* In the site header (template `header.tpl`), the count of new (unread) messages is output via the global `{HEADER_USER_PMREMINDER}` tag. This counter is updated on every page reload.

---

## 5.6. Polls Module (polls)

The polls module is designed to conduct surveys and votes among site visitors. The module code is located in the `modules/polls` directory.

### 5.6.1. Poll Management
* All polls are stored in the `sed_polls` table (poll title, poll type, active status, creation date, poll owner ID).
* Answer options are stored in the `sed_polls_options` table (poll ID, answer text, number of votes `po_count`).
* Polls can be of two types:
  1. **Global:** Displayed in a special block on the homepage or in the template sidebar.
  2. **Local:** Attached to specific pages (`page`) or forum topics (`forums`).

### 5.6.2. AJAX Voting Support and Anti-Cheat Protection
**AJAX Voting:**
If AJAX is enabled in the configuration (`$cfg['ajax'] = true`), voting occurs in the background. The form submission button receives a JS event to call AJAX, which passes the parameter `ajax=1` to the handler `polls.main.php`. Poll results are re-rendered and updated in real time inside the `#pollajx` block without reloading the entire page.

**Anti-Cheat Protection:**
To prevent repeat voting and falsification of results, Seditio performs a database check (table `sed_polls_voters`):
1. **By User ID:** For registered users, the presence of a record with their `pv_userid` is verified.
2. **By IP Address:** For guests and registered users, matching IP address (`pv_userip`) is verified. The `pv_userip` field is of type `VARCHAR(45)` to fully support both IPv4 and IPv6 addresses.

Results are rendered using XTemplate in the `polls.tpl` template, outputting the vote percentages in colored progress bars.

---

## 5.7. Gallery Module (gallery)

The gallery module is responsible for structuring and demonstrating graphic content (photo albums) on the site. A unique architectural feature of Seditio is that **the Gallery module does not have its own database tables and is fully based on the Personal File System (PFS)**.

The module code is located in the `modules/gallery` directory.

### 5.7.1. Architectural Link with PFS
* **Using PFS Tables:** Instead of dedicated tables, the gallery directly uses the `sed_pfs_folders` table (for albums) and `sed_pfs` table (for images).
* **Filtering by Folder Type (`pff_type`):** A PFS folder is considered a gallery album if its folder type is equal to gallery (`pff_type = '2'`). In the public gallery of the site (controllers `gallery.main.php`, `gallery.browse.php`), only folders with this mark are displayed.
* **Images as PFS Files:** Each photo in an album is a record in the `sed_pfs` table linked to the ID of the corresponding gallery folder via the `pfs_folderid` field.

### 5.7.2. Automatic Image Processing
When uploading files to a gallery folder, the system processes them via the built-in library `system/functions.image.php`:
* **Resize:** The original image is scaled to match the maximum width/height settings defined in the configuration.
* **Thumbnails (Thumbs):** A scaled copy of the image (`thumb`) is automatically generated for quick list display.
* **Crop:** The ability to crop an image to a square or a specific preview format.
* **Watermark:** When enabled, a semi-transparent PNG watermark is overlaid onto uploaded photos to protect copyrights.
* **Slideshow:** The gallery output is configured in `gallery.tpl` using special tags that output paths to original images, previews, and links to slideshows.
