# Chapter 12. Personal File System (PFS) and Media Processing

This chapter describes Seditio CMS's Personal File System (PFS), database schemas for files and folders, access rights (ACL) and disk limits, as well as the low-level and high-level programming interfaces for processing media assets (Image API, on-the-fly resizing, watermarking, and asynchronous Drag & Drop uploads).

---

## 12.1. PFS Database Architecture

The Personal File System (PFS) is a built-in core module of Seditio. PFS table schemas are registered by the installer upon module activation via the [pfs.install.php](../../modules/pfs/pfs.install.php) file.

PFS data is stored in two linked database tables: `sed_pfs` (files) and `sed_pfs_folders` (virtual directories).

### 12.1.1. Uploaded Files Table (`sed_pfs`)
Each file uploaded by a user via PFS is described by a record in this table:

| Column | Type | Description |
| :--- | :--- | :--- |
| **`pfs_id`** | `int` | Unique primary key (AUTO_INCREMENT). |
| **`pfs_userid`** | `int` | ID of the file owner (foreign key referencing `sed_users`). |
| **`pfs_date`** | `int` | UNIX timestamp of the upload time. |
| **`pfs_file`** | `varchar(255)` | Physical filename on the server (stored in `/datas/users/`). |
| **`pfs_extension`** | `varchar(8)` | File extension in lowercase (e.g., `jpg`, `zip`, `pdf`). |
| **`pfs_folderid`** | `int` | ID of the virtual folder containing the file (foreign key to `sed_pfs_folders`). If in the PFS root, equals `0`. |
| **`pfs_title`** | `varchar(255)` | File title specified by the user. |
| **`pfs_desc`** | `text` | File text description. |
| **`pfs_size`** | `int` | File size in bytes. |
| **`pfs_count`** | `int` | Download/view counter. |

### 12.1.2. Virtual Folders Table (`sed_pfs_folders`)
Users can organize files into virtual directories. Folder structures are defined by the following schema:

| Column | Type | Description |
| :--- | :--- | :--- |
| **`pff_id`** | `int` | Unique primary key (AUTO_INCREMENT). |
| **`pff_userid`** | `int` | ID of the folder owner. |
| **`pff_parentid`** | `int` | Parent folder ID (for nesting support). Defaults to `0` (root). |
| **`pff_date`** | `int` | UNIX timestamp of the creation time. |
| **`pff_updated`** | `int` | UNIX timestamp of the last content update. |
| **`pff_title`** | `varchar(255)` | Folder name. |
| **`pff_desc`** | `text` | Folder description. |
| **`pff_type`** | `tinyint` | Folder privacy flag (`0` — public, `1` — private/owner only, `2` — gallery album). |
| **`pff_sample`** | `int` | ID of a file (`pfs_id`) from this folder used as a preview/cover image. |
| **`pff_count`** | `int` | Counter of files contained in this folder. |

---

## 12.2. Access Rights (ACL) and Disk Space Quotas

PFS is a restricted resource managed via ACL permissions and disk quotas allocated to user groups.

### 12.2.1. Access Control (ACL)
Access to the PFS module is defined by authorization rights for the `pfs` resource code (area `a`):
* **Guests** (`SED_GROUP_GUESTS`) are denied read and write access by default.
* **Registered Members** (`SED_GROUP_MEMBERS`) have `RW` rights (Read and Write own files). Members cannot modify other users' files.
* **Administrators** (`SED_GROUP_SUPERADMINS`) have `RWA12345` rights (complete control, including viewing and deleting files of any user via the Control Panel).

### 12.2.2. Group Disk Quotas and Limits
To prevent server storage overflow, individual quotas are configured for each user group:
1. **`grp_pfs_maxfile`** — maximum allowed size of a single uploaded file (in KB). If a file exceeds this limit, the core blocks the upload.
2. **`grp_pfs_maxtotal`** — maximum cumulative size of files (total disk space quota per group user in KB).

With every upload attempt, the Seditio core calculates the sum of all files already uploaded by the user:
```sql
SELECT SUM(pfs_size) FROM sed_pfs WHERE pfs_userid = $userid
```
And matches the total sum (including the new file size) against the group's `grp_pfs_maxtotal` limit. If the limit is exceeded, the upload is aborted.

---

## 12.3. Image API

Image processing functions are located in the system file `system/functions.image.php`. Seditio supports both GD and ImageMagick extensions (configured by the administrator).

### 12.3.1. Resize and Crop Functions
For easy use directly inside HTML templates, two high-level template filter functions are available:

1. **`resize_image($filename, $width, $height, $set_watermark, $use_webp)`**
   Scales an image to the specified width and height while maintaining aspect ratios (encloses the image within target boundaries).
2. **`crop_image($filename, $width, $height, $set_watermark, $use_webp)`**
   Crops an image exactly to the target dimensions. The image is centered and excess edges are trimmed.

**Parameters:**
* `$filename` (string) — relative path to the source file on disk (e.g., `datas/users/photo.jpg`).
* `$width` (int) — target width in pixels.
* `$height` (int) — target height in pixels.
* `$set_watermark` (bool, optional) — watermark overlay flag.
* `$use_webp` (bool, optional) — flag to convert images to WebP format for optimal size.

### 12.3.2. Deferred Resizing Concept
To ensure that heavy image rendering does not slow down page load times, Seditio employs a **deferred resizing** mechanism:
1. During page rendering, the template engine calls `resize_image()` or `crop_image()`.
2. The function does not process the resize operation immediately. Instead, it encodes the target dimensions and compression mode, returning a virtual path to the cache directory `$cfg['res_dir']` (typically `/datas/resized/`):
   *Example output path:* `datas/resized/photo.resize.1920.1080.jpg`.
3. The visitor's browser loads the compiled HTML containing this virtual path and sends a GET request for the image.
4. The web server redirects missing file requests inside `/datas/resized/` to the image processor controller (resizer script).
5. The resizer intercepts the request, decodes parameters, locates the original file, processes it physically using `sed_resize()`, saves the output to the cache directory, and serves it to the browser.
6. Subsequent requests serve the cached file from disk directly, bypassing PHP execution.

### 12.3.3. Watermarking (`sed_image_merge`)
If the `$set_watermark = true` flag is passed, the system calls `sed_image_merge()`.
* Watermark options (path to the transparent PNG file, opacity percentage, and positioning) are read from the core configuration.
* Image blending is processed preserving the alpha channel using `sed_imagecopymerge_alpha()`, which prevents artifacts on translucent watermark edges.

---

## 12.4. Asynchronous Drag & Drop File Uploading

For convenient batch uploads, Seditio CMS features an integrated asynchronous uploader whose functions are declared in `system/functions.image.php`.

### 12.4.1. Uploader API Functions
* **`sed_image_upload_html($opts)`** — compiles the HTML layout of the dropzone area, loads CSS/JS assets, and binds events.
  Accepts an associative settings array with the following keys:
  * `'prefix'` (string) — input fields prefix name (defaults to `'images'`).
  * `'title'` (string) — dropzone title.
  * `'max_files'` (int) — upload files limit count (defaults to `0` — unlimited).
  * `'sortable'` (bool) — drag sorting support for uploaded images (defaults to `true`).
  * `'dropzone'` (bool) — enables the Drag & Drop area (defaults to `true`).
  * `'url_upload'` (bool) — allows fetching files from external URLs (defaults to `true`).
  * `'existing'` (array) — list of previously uploaded files to display.
  * `'id'` (string) — unique DOM ID.
  * `'accept'` (string) — allowed file types (defaults to values derived from GD/ImageMagick configurations).
* **`sed_image_upload_save($tmp_path, $dest_path, $max_w, $max_h, $mode = 'crop', $is_uploaded = true)`** — handles low-level resizing/cropping and saves the file securely on disk.
* **`sed_image_upload_process($opts)`** — processes the asynchronous POST request containing files, validates ACL permissions and group storage quotas, saves source files, and generates thumbnail previews.

### 12.4.2. Integrating Drag & Drop Uploads in Plugins
To integrate the uploader into a content editing form, call the helper inside the PHP controller:
```php
// Generate HTML for the dropzone bound to 'album_images' fields prefix
$uploader_html = sed_image_upload_html(array(
    'prefix' => 'album_images',
    'max_files' => 10
));

$t->assign('MYPLUGIN_UPLOADER', $uploader_html);
```
And output the tag in the TPL template:
```html
<div class="form-group">
    <label>Upload album photos:</label>
    {MYPLUGIN_UPLOADER}
</div>
```
Upon form submission, Seditio automatically handles uploaded files, binding them to the user's PFS session, and saving paths to the database.
