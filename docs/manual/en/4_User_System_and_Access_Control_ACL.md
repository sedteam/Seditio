# Chapter 4. User System and Access Control (ACL)

This chapter details the structure of the user subsystem, the Access Control List (ACL) architecture, and security mechanisms in Seditio CMS. You will learn how user roles are distributed, how the access rights bitmask is organized, how permissions are cached in the database, as well as the principles of authentication and encryption.

---

## 4.1. User Groups and System Roles

The access management system in Seditio is built on a role model. All registered users must belong to groups.

### 4.1.1. System (Reserved) User Groups
By default, Seditio includes predefined groups with reserved identifiers (IDs) that are hard-coded into the internal core logic:
1. **Guests (ID = 1):** Unauthenticated site visitors. The group defines which content and features are publicly accessible.
2. **Inactive (ID = 2):** Users who have registered but are awaiting account activation (e.g., via email activation link or admin approval).
3. **Banned (ID = 3):** Users whose accounts are temporarily or permanently blocked.
4. **Members (ID = 4):** The default group for all successfully registered and activated users.
5. **Administrators (ID = 5):** Team members with full administrative rights. Members of this group have access to the control panel (at `/admin` or `index.php?module=admin` without SEF URLs) by default.
6. **Moderators (ID = 6):** Users with moderation privileges on forums and page categories.

---

### 4.1.2. The Concept of Main Group and Additional Groups
Each user in Seditio belongs to groups in two dimensions:
* **Main Group (`user_maingrp`):** Stored in the `sed_users` user table. It defines the user's primary role on the site, default skin, interface language, and basic access level.
* **Additional Groups (`sed_groups_users` table):** A user can belong to an unlimited number of additional groups. This relationship is linked through the `sed_groups_users` relation table (columns `gru_userid` and `gru_groupid`).

When checking access rights, the Seditio core merges the privileges of the user's main group and all additional groups they belong to.

---

## 4.2. ACL (Access Control List) Architecture and Bitmask

The entire access rights matrix in Seditio CMS is stored in the `sed_auth` table. A permission record is mapped to a user group (`auth_groupid`), a system area (`auth_code` — e.g., `page`, `forums`, `admin`), and a specific option within that area (`auth_option` — e.g., a page category or forum section).

### 4.2.1. A Byte as an Access Rights Bitmask
To minimize database size and maximize performance, rights for a specific action are stored as an **8-bit integer** (a single byte from `0` to `255`), where each bit corresponds to a specific permission:

* **Bit 0 (weight 1):** `Read` (`R`). Allows viewing content.
* **Bit 1 (weight 2):** `Write` (`W`). Allows adding and editing one's own content.
* **Bit 2 (weight 4):** Custom permission 1 (`1`).
* **Bit 3 (weight 8):** Custom permission 2 (`2`).
* **Bit 4 (weight 16):** Custom permission 3 (`3`).
* **Bit 5 (weight 32):** Custom permission 4 (`4`).
* **Bit 6 (weight 64):** Custom permission 5 (`5`).
* **Bit 7 (weight 128):** `Admin` (`A`). Allows moderating, deleting others' content, and managing section settings.

Thus, if a group has full rights to a section (Read + Write + Admin), its database permission value will be `1 + 2 + 128 = 131`.

---

### 4.2.2. Inheritance and Cumulative Merging of Rights
If a user belongs to multiple groups simultaneously, the system does not overwrite the rights of one group with another, but applies the principle of **cumulative merging (bitwise OR)**.

During user authorization, the `sed_auth_build()` function retrieves rights for all groups the user belongs to and compiles them into a single rights grid using the bitwise OR operator (`|=`):

```php
// Cumulative bitwise OR merging of user rights from multiple groups
@$authgrid[$row['auth_code']][$row['auth_option']] |= $row['auth_rights'];
```

**Example:**
* A user belongs to the *Members* group, which has read permissions on a forum equal to `1` (Read only).
* The same user also belongs to the *Moderators* group, which has rights to the same forum equal to `128` (Admin).
* The user's final merged rights will be `1 | 128 = 129` (Read + Admin).

---

## 4.3. Access Authorization Check in Code (sed_auth)

The system core uses built-in functions to build and verify the rights grid.

### 4.3.1. Assembling the Rights Grid on User Login
On successful login or the first page load, the `sed_auth_build()` function (located in `system/functions.php`) is called. It scans all the user's groups (main and additional), runs an SQL query on the `sed_auth` table, and builds a two-dimensional associative array of rights:

```php
$usr['auth'][$area][$option] = $bitwise_rights;
```

##### Function Signature:
```php
function sed_auth_build($userid, $maingrp = 0)
```
* **Guest Logic:** If `$userid` is `0` or `$maingrp` is `0`, the function automatically treats the user as a guest and assigns the Guests group ID (ID = 1).
* **Gathering Groups:** For authenticated users, `$maingrp` is added to the group list, along with all group IDs from the `sed_groups_users` relations table by querying the database.
* **Group Union:** A single SQL query is executed to select rights for all found groups:
  ```sql
  SELECT auth_code, auth_option, auth_rights FROM sed_auth WHERE auth_groupid IN (...) ORDER BY auth_code ASC, auth_option ASC
  ```
  In a loop, the rights are merged using bitwise OR (`|=`) into a single two-dimensional array `$authgrid`:
  ```php
  @$authgrid[$row['auth_code']][$row['auth_option']] |= $row['auth_rights'];
  ```
  The method returns the compiled `$authgrid` array.

---

### 4.3.2. Access Validation in PHP Code
To verify permissions in PHP scripts, the core function `sed_auth()` (located in `system/functions.php`) is used. It matches the requested rights bitmask against the current user's rights and returns a boolean value (`TRUE` or `FALSE`) or an array of results (if checking multiple masks simultaneously).

##### Function Signature:
```php
function sed_auth($area, $option, $mask = 'RWA')
```
Parameters:
* `$area` (string) — the protected area of the system (e.g., `'page'`, `'forums'`, `'admin'`).
* `$option` (string) — the specific section or category (e.g., `'news'` for a page category).
* `$mask` (string) — the mask of rights to check (defaults to `'RWA'`).

##### Internal Verification Logic:
1. The function splits the `$mask` string into separate characters-bits (via `str_split()`) and maps them to their numeric weights: `R = 1`, `W = 2`, `1 = 4`, `2 = 8`, `3 = 16`, `4 = 32`, `5 = 64`, `A = 128`.
2. **The any option:** If the `$option` parameter is passed as `'any'` (checking if the user has a right to anything within the area), the function checks the entire `$usr['auth'][$area]` branch for the presence of at least one record with this bit. Additionally, if admin right (`A`) is requested and the user has super-admin rights (`admin/a`), the function automatically returns `TRUE`.
3. **Regular Check:** In other cases, the exact intersection of bitwise AND is checked: `($usr['auth'][$area][$option] & $weight) == $weight`.
4. **Return Value:** If a single-character mask is passed (e.g., `'R'`), the function returns a single boolean value (`TRUE` or `FALSE`). If a multi-character mask is passed (e.g., `'RWA'`), an array of boolean results is returned (e.g., `[TRUE, FALSE, FALSE]`), which allows using the `list()` construct:
   ```php
   list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('page', 'news', 'RWA');
   ```

---

### 4.3.3. Performance Optimization: Caching Rights
To avoid executing resource-heavy SQL queries and bitwise array merging on every page hit, Seditio CMS caches the compiled rights grid of authenticated users:
* The `sed_users` table includes a `user_auth` column of type `TEXT`.
* During the first build, the result of `sed_auth_build()` is serialized via `serialize()` and stored in this column.
* On every subsequent page hit, the user's rights are loaded from the database in a single query and deserialized: `$usr['auth'] = unserialize($row['user_auth']);`.
* **Clearing Cache (`sed_auth_clear`):** When group rights are modified in the admin panel, category structure is changed, or users are added to new groups, the system calls `sed_auth_clear($id)` (located in `system/functions.php`). If the `$id` parameter is `'all'`, the cache is cleared for all users (`UPDATE sed_users SET user_auth=''`); if a numeric ID is passed, the rights cache is cleared selectively only for that user.

---

## 4.4. User Profile and Its Expansion

Each user has an individual profile card, whose data is stored in the `sed_users` table.

> [!NOTE]
> The logic of outputting registration and login forms, changing profile settings, and public user information on the site is handled by the built-in `users` module (a detailed description of its structure, controllers, and TPL templates is provided in [Chapter 5. Built-in Modules](/doc/core-modules#53-users-module-users)).

### 4.4.1. System Profile Structure
By default, the user profile includes the following key fields:
* `user_id` — unique user identifier.
* `user_name` — login (unique username for authentication).
* `user_password` and `user_salt` — password hash and salt.
* `user_maingrp` — main user group.
* `user_email` — email address.
* `user_avatar` and `user_photo` — avatar and photo.
* `user_timezone`, `user_lang`, `user_skin` — regional preferences (timezone, language, theme).
* `user_regdate`, `user_lastlog`, `user_lastip` — activity statistics.

### 4.4.2. Profile Expansion via Directories (CCK)
If default profile fields are insufficient (for example, you need to add *«Phone Number»*, *«Shipping Address»*, or *«Telegram ID»*), the administrator can extend the profile without modifying the PHP code:
1. In the **Directories** section at `/admin/dic` (or `index.php?module=admin&m=dic` without SEF URLs), a new directory is created.
2. The user table `users` is selected as the target.
3. Directory items are created (`sed_dic` / `sed_dic_items`).
4. The system runs an `ALTER TABLE sed_users ADD user_field_name ...` SQL query, physically extending the database structure.
5. The new field is automatically integrated into the profile edit form, the admin panel, and becomes available in TPL templates via tags like `{USER_FIELD_NAME}`.

---

## 4.5. Session, Authentication, and Security Mechanisms

User authentication security is ensured by a combination of sessions, cookies, and cryptographic hashing.

### 4.5.1. Authentication Scheme and Persistent Login
Seditio supports three authentication modes (configured via the `$cfg['authmode']` parameter in `datas/config.php`):
1. **Cookies only (mode 1):** Authentication is tied exclusively to client cookies.
2. **Sessions only (mode 2):** Authentication is tied to PHP sessions.
3. **Combined mode (mode 3):** Both cookies and sessions are used (default mode).

During cookie authentication (modes 1 and 3), Persistent Login is based on a special autologin cookie named after `$sys['site_id']`:
* Cookie data is stored in `base64` and structured as: `user_id:_:user_secret:_:user_skin`.
* Note: the cookie stores a session secret token `user_secret` (MD5 hash) generated and saved in the database on login, rather than the user's password.
* On each request, the core reads the cookie, decodes it, and validates the fields against the database:
  ```php
  // Decoding persistent login cookie value
  $u = base64_decode($_COOKIE[$sys['site_id']]);
  $u = explode(':_:', $u);
  $rsedition = sed_import($u[0], 'D', 'INT');  // User ID
  $rseditiop = sed_import($u[1], 'D', 'H32');  // User Secret hash
  $rseditios = sed_import($u[2], 'D', 'ALP');  // User Skin
  ```
* If IP validation is enabled (`$cfg['ipcheck']`), the core additionally matches the current IP address of the request (`$usr['ip']`) against the IP address of the user's last successful login (`user_lastip`) saved in `sed_users`. In case of a mismatch (e.g., when a user changes networks), the cookie authorization is reset.

---

### 4.5.2. Password Hashing and Site Secret Usage
To protect passwords from compromise, Seditio uses two-factor salted hashing using the `sed_hash($data, $type, $salt)` function (located in `system/functions.php`):
* During registration, a unique random salt (`user_salt`) is generated for the user.
* The password hash is generated by the `sed_hash()` function with the `$type = 1` parameter. The global site secret `$cfg['site_secret']` is **not used** for this:
  ```php
  // Hashing password with user salt (type = 1)
  $res = hash($algorithm, hash($algorithm, $password) . $salt);
  ```
* The global unique site secret `$cfg['site_secret']` (defined in `datas/config.php` during installation) is used by the `sed_hash()` function with the `$type = 2` parameter only to generate a unique session identifier/token (`sessionid` / `user_secret`):
  ```php
  // Hashing dynamic session identifier (type = 2)
  $res = hash($algorithm, hash($algorithm, $data) . $cfg['site_secret'] . $salt);
  ```
* Double hashing (`md5` by default) and individual user salt protect passwords from cracking via Rainbow Tables.

---

### 4.5.3. Brute Force Protection and Banlist
* **Audit of Failed Logins:** A security log is kept. All failed login attempts under any name are recorded in the security log (`sec`), along with the attacker's IP address.
* **IP and E-mail Blockings (Banlist):** In the control panel at `/admin/banlist` (or `index.php?module=admin&m=banlist` without SEF URLs), the administrator can block access to the site by IP address, email mask (e.g., `*@spamdomain.com`), or subnet mask. The system fully supports both IPv4 and IPv6 addresses (the `banlist_ip` field is `VARCHAR(45)`). Verification is performed by the `sed_check_banlist($userip)` function (located in `system/functions.php`), which checks the visitor's IP against the database on every page view, automatically building comparison masks (e.g., `192.168.1.*` for IPv4 or `2001:db8:85a3:0:*:*:*:*` for IPv6). If a match is found, script execution is terminated immediately.
