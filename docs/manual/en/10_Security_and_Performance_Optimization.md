# Chapter 10. Security and Performance Optimization

This chapter covers Seditio CMS's built-in security mechanisms (input filtering, CSRF/XSS and SQL injection protection), as well as caching and database query optimization tools to enhance website performance.

---

## 10.1. Input Data Filtering (sed_import)

Seditio CMS follows a strict principle: "never trust input data." Any variable received from external sources (GET, POST, Cookies) must be imported and sanitized using the core function `sed_import()` (located in [system/functions.php](../../system/functions.php)) before use.

### 10.1.1. Signature and Parameter Purposes
The function is structured as follows:
```php
function sed_import($name, $source, $filter, $maxlen = 0, $dieonerror = false)
```
* **`$name`** (string) — the name of the input variable (key in `$_GET`, `$_POST`, or `$_COOKIE` arrays).
* **`$source`** (string) — the source of the data (where to read the value from).
* **`$filter`** (string) — the type of sanitization filter to apply.
* **`$maxlen`** (int, optional) — the maximum allowed string length limit. If the value length exceeds `$maxlen` (and `$maxlen > 0`), the string is automatically truncated using the multibyte `mb_substr()` function. Default is `0` (unlimited).
* **`$dieonerror`** (bool, optional) — critical execution flag. If set to `true` and the imported value fails validation by the chosen filter, script execution stops immediately with a fatal error `sed_diefatal('Wrong input.')`. Default is `false`.

### 10.1.2. Input Data Sources
The `$source` parameter accepts one of four string values:
* **`'G'`** — data is read from the global `$_GET` array.
* **`'P'`** — data is read from the global `$_POST` array.
* **`'C'`** — data is read from the global `$_COOKIE` array.
* **`'D'`** — direct variable usage (DIRECT). In this case, the `$name` parameter receives an already existing PHP variable to be filtered according to the specified rules.

### 10.1.3. Sanitization Filters Reference
Depending on the expected data type, the `$filter` parameter accepts the following values:

| Filter | Sanitization and Validation Logic Description |
| :--- | :--- |
| **`INT`** | Checks if the value is an integer. On success, casts the value to `(int)`. |
| **`NUM`** | Checks if the value is numeric. Casts to float or integer (`$v + 0`). |
| **`TXT`** | Safe text. If the string contains `<` characters, it is automatically escaped via `htmlspecialchars($v, ENT_QUOTES, 'UTF-8')`. |
| **`SLU`** | SEF URL slug. Allows only alphanumeric characters, underscores, hyphens, equals, and slashes: `a-zA-Z0-9_\-=/`. All other characters are stripped. |
| **`ALP`** | Alphabetic characters only. Strips all characters except latin letters (uses helper function `sed_alphaonly()`). |
| **`ALS`** | Titles and names. Allows letters, digits, spaces, and hyphens. Other characters are replaced with underscores `_`. |
| **`PSW`** | Safe password. Strips quotes (`'`, `"`), ampersands (`&`), and angle brackets (`<`, `>`). Maximum length is strictly limited to 32 characters. |
| **`H32`** | MD5 hash representation. Allows only alphanumeric characters. Truncated to 32 characters. |
| **`HTR`** | HTML Trusted. Returns the string as-is without any filtering. Used only for content submitted by administrators. |
| **`HTM`** | HTML markup subject to processing by sanitization plugins via the `import.filter` hook (e.g., Jevix/HTMLPurifier). |
| **`ARR`** | Array. Returns the array unchanged. Element filtering must be handled manually. |
| **`BOL`** | Boolean value. If the value is `'1'`, `'on'`, `true`, or `1`, returns `true`. For `'0'`, `'off'`, `false`, or `0`, returns `false`. Returns `false` in all other cases. |
| **`LVL`** | User level. Numeric value strictly between `0` and `100`. |
| **`NOC`** | No Check. Returns the raw string without modifications. |

### 10.1.4. Processing Invalid Values
If an input value fails validation by regular expression or filter conditions:
1. The incident is automatically logged in the security journal using `sed_log_sed_import()`.
2. If `$dieonerror` is set to `true`, execution is halted with a fatal error.
3. If execution continues, the function returns a safe default value (for most string filters it returns a pre-sanitized string, `false` for `BOL`, and `null` for numeric filters).

Example of secure parameter imports in module code:
```php
// Securely get page ID (GET)
$id = sed_import('id', 'G', 'INT');

// Securely get comment text (POST)
$text = sed_import('rtext', 'P', 'TXT', 1000);

// Secure import with fatal abort on failure
$category = sed_import('c', 'G', 'SLU', 64, true);
```

---

## 10.2. CSRF and XSS Attack Protection

Cross-Site Request Forgery (CSRF) is an attack where a user is forced to perform unwanted actions on a web application in which they are authenticated. Seditio CMS blocks these attacks via token authorization.

### 10.2.1. Security Token Generation (sed_sourcekey)
During session initialization in the `system/common.php` file, a unique session key is generated for every visitor:
* **For Guests:** A random MD5 hash is generated via `sed_unique()` and stored in the session:
  ```php
  $_SESSION['guest_sourcekey'] = md5(sed_unique(16));
  ```
* **For Registered Members:** The key is generated dynamically based on the user's secret hash and last visit time:
  ```php
  $usr['sourcekey'] = md5($row['user_secret'] . $row['user_lastvisit']);
  ```

The `sed_sourcekey()` function in `system/functions.php` reads this key, extracts the first 6 characters, casts them to uppercase (prepending `GUEST_` for guests), and returns the generated token.

### 10.2.2. Protecting GET Requests (sed_check_xg)
Any operations that modify system state via GET requests (e.g., deleting a page, clearing cache, changing statuses) must include the verification parameter `x` in the URL.
* **PHP Generation:** All links in PHP code are generated via the address assembler `sed_url()`, appending the security helper `sed_xg()`:
  ```php
  $delete_url = sed_url('page', 'm=delete&id=5&' . sed_xg());
  ```
* **TPL Usage:** If you need to manually append a GET security parameter in a template layout, call the system PHP function via the template syntax:
  ```html
  <a href="index.php?module=page&m=delete&id=5&{PHP|sed_xg}">Delete page</a>
  ```

When parsing this request, the core controller must call the validator:
```php
sed_check_xg(); // Compares $_GET['x'] with the token from sed_sourcekey() and aborts on mismatch
```

### 10.2.3. Automatic POST Forms Protection (sed_check_xp)
To defend against CSRF attacks, all POST forms must submit the security token in a hidden field named `x`.

Seditio CMS implements an **automatic replacement mechanism** that relieves template designers of manually adding this field:
1. The designer creates a standard form in the template:
   ```html
   <form action="index.php?module=comments&m=add" method="post">
       <textarea name="rtext"></textarea>
       <button type="submit">Submit</button>
   </form>
   ```
2. During the global output phase, the core function `sed_output()` (in `system/functions.php`) intercepts the compiled HTML code of the page, finds all closing `</form>` tags, and automatically inserts the hidden token field:
   ```php
   $output = str_replace('</form>', sed_xp() . '</form>', $output);
   ```
   *The `sed_xp()` function returns: `<div><input type="hidden" id="x" name="x" value="XXXXXX" /></div>`.*
3. If necessary (e.g., when outputting external forms), automatic token embedding can be disabled by declaring a constant:
   ```php
   define('SED_DISABLE_XFORM', true);
   ```

In the PHP controller handling the form, validation is executed by calling:
```php
sed_check_xp(); // Protects forms from submission by third-party scripts
```

### 10.2.4. AJAX Calls Security (sed_check_csrf)
For asynchronous AJAX requests, the security token is sent in the `X-Seditio-CSRF` HTTP header. On the server side, validation is done using `sed_check_csrf()`:
```php
if (!sed_check_csrf()) {
    sed_diefatal('Invalid CSRF token for AJAX request.');
}
```

---

## 10.3. SQL Injection Protection

SQL injection is an attack where malicious SQL code is injected into input queries to the database.

### 10.3.1. Escaping via sed_sql_prep
To sanitize string values before they are used in SQL statements, the `sed_sql_prep()` function (or DBMS-native escaping methods) is used:
```php
function sed_sql_prep($string)
```
It escapes SQL special characters (single quotes, control symbols) to prevent escaping from the SQL context.

### 10.3.2. Guidelines for Writing Secure Queries
1. **Numeric Parameters:** All numeric variables must be cast to `(int)` or `(float)` or imported using `INT` / `NUM` filters:
   ```php
   // SECURE:
   $id = (int)$id;
   $sql = sed_sql_query("SELECT * FROM $db_pages WHERE page_id = $id");
   ```
2. **String Parameters:** All strings must be wrapped in `sed_sql_prep()` and enclosed in single quotes inside the query:
   ```php
   // SECURE:
   $title = sed_sql_prep($title);
   $sql = sed_sql_query("SELECT * FROM $db_pages WHERE page_title = '$title'");
   ```

> [!CAUTION]
> **Direct String Concatenation:**
> Never insert raw global arrays (`$_GET`, `$_POST`) directly into SQL statements. Queries like `SELECT * FROM table WHERE field = '` . `$_GET['val']` . `'` are strictly forbidden and constitute critical vulnerabilities!

---

## 10.4. Performance Optimization and Caching

To reduce database server load and speed up page load times, Seditio CMS implements a hybrid caching system.

### 10.4.1. Built-in Database Caching (DB Cache)
Caching is enabled in the administrator panel (Configuration → Main Settings → Internal Cache), after which the core saves the `$cfg['cache'] = 1` status in the `sed_config` table. In PHP code, this state is retrieved from the global configuration array `$cfg['cache']`. When enabled, the system saves resource-intensive structures (e.g., menu trees, tag clouds, plugin outputs) in the cache table `$db_cache` (typically `sed_cache`).

Cache records are managed using the following functions:
* **`sed_cache_store($name, $value, $expire, $auto = 1)`** — stores a value under a unique key `$name`.
  - `$value` — any data structure (arrays, objects, strings) that is automatically serialized via `serialize()`.
  - `$expire` — cache lifetime in seconds.
  - `$auto` — autoload flag (described below).
* **`sed_cache_get($name, $expire = true)`** — retrieves data from the cache. If the cache lifetime has expired (and the `$expire` parameter is `true`), the function returns `false`.
* **`sed_cache_clear($name)`** — deletes a specific cache record by name.
* **`sed_cache_clearall()`** — completely clears the cache table.

### 10.4.2. Cache Autoload Mechanism (c_auto)
To minimize database query counts when loading pages, Seditio CMS uses autoloaded cache records.
When writing cache with the `$auto = 1` flag, the record is flagged in the DB. During core initialization in `system/common.php`, a single group SQL query is executed:
```sql
SELECT c_name, c_value FROM sed_cache WHERE c_auto = 1
```
All returned records are deserialized and loaded into global PHP memory in one step. Subsequently, `sed_cache_get()` calls for autoloaded items are handled instantly in memory, avoiding SQL database calls.

### 10.4.3. Client Output Optimization (cleanup)
At the template engine level, a basic whitespace sanitization option is available for HTML outputs. When the `cleanupEnabled` property is active (set via static configurator `XTemplate::configure()`):
* Leading and trailing spaces and tabulations are stripped from each line of compiled HTML.
* Line breaks and tab characters surrounding HTML tags (`<` and `>`) are removed to merge the markup.
* Multiple consecutive spaces in text and tags are compressed into a single space.

> [!WARNING]
> **No Specialized Minification:**
> The `cleanup()` method **does not** delete HTML comments and **does not** compress inline JS/CSS. Because line breaks surrounding tags are stripped, using single-line JS comments (`//`) can lead to script syntax errors (all subsequent code is merged into the commented line). In inline scripts, only block comments `/* ... */` must be used.
