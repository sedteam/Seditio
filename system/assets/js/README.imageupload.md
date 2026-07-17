# sedjs.imageUpload — deferred form image upload

Universal UI for **preview + drag-and-drop + URL input** with files sent on **form submit** (not AJAX).

Use **`sedjs.imageUpload`** for admin CRUD forms, profile, future extrafields.  
Use **`SedUploader`** (`plugins/uploader`) when files must upload immediately via AJAX (e.g. page thumbs).

## Files

| File | Role |
|------|------|
| `system/javascript/imageupload.js` | `sedjs.imageUpload.init()`, `initAll()` |
| `system/adminskin/sympfy/css/imageupload.css` | Widget styles |
| `system/functions.image.php` | `sed_image_upload_html()`, `sed_image_upload_register_assets()`, `sed_image_upload_get_dropped_files()` |

Assets load automatically when `sed_image_upload_html()` is called (before `header.php` / `admin.header.php`). Auto-init on DOM ready runs at the end of `imageupload.js`.

Allowed file types come from **`$cfg['gd_supported']`** (see `system/functions.php`). PHP builds the `accept` attribute and passes `extensions` to JS; server-side upload handlers (shop, profile) also validate against the same config.

## JavaScript API

```javascript
// Auto-init on DOM ready (end of imageupload.js)

// Manual init
var api = sedjs.imageUpload.init(containerElement, {
    prefix: 'brand_images',
    maxFiles: 0,
    sortable: true,
    dropzone: true,
    urlUpload: true
});
api.destroy();
```

### Options

| Option | Default | Description |
|--------|---------|-------------|
| `prefix` | `images` | POST field base name |
| `maxFiles` | `0` | `0` = unlimited; `1` = single (avatar) |
| `sortable` | `true` | Drag-reorder list (`false` when `maxFiles: 1`) |
| `dropzone` | `true` | Drag-and-drop area |
| `urlUpload` | `true` | «Upload from internet» link |
| `accept` | from `$cfg['gd_supported']` | HTML accept (MIME + `.ext`) |
| `extensions` | from `$cfg['gd_supported']` | Client-side preview filter |
| `singleField` | auto | Single file uses `prefix` without `_upload[]` |
| `labels` | `{}` | Override UI strings |
| `onChange` | `null` | Callback after add/remove/reorder |

Auto-init: container `.sed-image-upload` with `data-sed-image-upload='{"prefix":"..."}'` (JSON from PHP).

## PHP API

```php
echo sed_image_upload_html(array(
    'prefix'      => 'brand_images',
    'title'       => $L['shop_brand_images'],
    'max_files'   => 0,
    'sortable'    => true,
    'dropzone'    => true,
    'url_upload'  => true,
    'existing'    => array(
        array('id' => 5, 'url' => 'https://example/datas/shop/brands/x.jpg'),
    ),
    'id'          => 'shop-brand-images',
));
```

### Single file (profile avatar)

```php
echo sed_image_upload_html(array(
    'prefix'      => 'userfile',
    'max_files'   => 1,
    'sortable'    => false,
    'dropzone'    => true,
    'url_upload'  => false,
    'existing'    => !empty($path) ? array(array('id' => 0, 'url' => $path)) : array(),
    'id'          => 'profile-avatar',
));
```

Server handler unchanged: `$_FILES['userfile']` on submit.

## POST field contract

For prefix `category_images`:

| Field | Purpose |
|-------|---------|
| `category_images[]` | Existing image ids to keep |
| `category_images_sort[]` | Order tokens: `id:N`, `url:...`, `file:name.jpg` |
| `category_images_upload[]` | New files from picker |
| `category_images_urls[]` | Remote URLs / drop wizard names |
| `category_images_drop[]` | Files from dropzone |

Legacy fallback: `dropped_images[]` still read if `{prefix}_drop` is empty.

## Shop integration

Registry + API in `modules/shop/inc/shop.functions.images.php`:

- `sed_shop_images_admin_html($part, $id)` — widget (`category`, `brand`, `product`, `variant`)
- `sed_shop_images_process($part, $id)` — save gallery + sync `{entity}_image` listing column
- `sed_shop_images_listing_url($part, $filename)` — URL from denormalized column value

Prefixes from registry: `category_images`, `brand_images`, `product_images`, `variant_images`.

## Users profile integration

Wrappers in `modules/users/inc/users.functions.php`:

- `sed_users_profile_images_process($user_id)` — avatar, photo, signature on profile save
- `sed_users_profile_image_save()` — uses core `sed_image_upload_save()` (`crop` or `resize` per kind)
- `sed_userimage_url()` — cache-busting URLs in `users.functions.php`

## Lang keys

In `system/lang/*/main.lang.php`:

- `sed_image_upload_drop`
- `sed_image_upload_add`
- `sed_image_upload_add_url`
- `sed_image_upload_sort_hint`

## Future: extrafields

`sed_image_upload_process($opts)` in `functions.image.php` is a stub for stage 3 — generic POST handler for custom storage paths.
