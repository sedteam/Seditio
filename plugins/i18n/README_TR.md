# Seditio CMS için i18n Eklentisi Kurulum ve Yapılandırma Talimatları

`i18n` eklentisi, sayfalar ve kategoriler (yapı) için çok dilli içerik düzenlemek amacıyla tasarlanmıştır. Başlıkları, açıklamaları, sayfa gövdesini (ikinci metin alanı `text2` dahil) ve tüm SEO meta verilerini çevirmenize olanak tanır.

---

## 1. Kurulum ve Yapılandırma

1. Eklenti dosyalarını `plugins/i18n/` dizinine kopyalayın.
2. Yönetim Paneli -> **Eklentiler** bölümüne gidin ve **i18n** eklentisini kurun. Kurulum sırasında gerekli veritabanı tabloları otomatik olarak oluşturulacaktır (`{prefix}i18n_pages` ve `{prefix}i18n_structure`).
3. Eklenti ayarlarında (Yönetim Paneli -> Eklentiler -> i18n -> Yapılandırma), çeviri yapılacak dil kodlarını virgülle ayırarak belirtin.
   - *Örnek:* `en,de`
   - *Not:* Varsayılan web sitesi dilini (örneğin `ru` veya `tr`) belirtmenize **gerek yoktur**. Bu dil ana dil olarak kabul edilir.

---

## 2. Tema Şablonlarına (TPL) Entegrasyon

Sayfa ve kategori ekleme/düzenleme formlarında diğer diller için sekmelerin görünmesini sağlamak için `.tpl` dosyalarınıza ilgili etiketleri eklemeniz gerekir.

### A. Sayfa Ekleme (`skins/temaniz/page.add.tpl` veya `modules/page/tpl/page.add.tpl`)

Sekme konteyner bloğunu (`sedtabs` içindeki `tabs` sınıfı) bulun ve sekme başlıkları etiketini ekleyin:
```html
<ul class="tabs">
    <li><a href="{PHP.sys.request_uri}#tab1" class="selected">{PHP.L.Page}</a></li>
    <li><a href="{PHP.sys.request_uri}#tab2">{PHP.L.Meta}</a></li>
    <li><a href="{PHP.sys.request_uri}#tab3">{PHP.L.Options}</a></li>
    <!-- Çeviri için sekmeler -->
    {PAGEADD_I18N_TABS_HEADERS}
</ul>
```

Ardından sekme konteynerinin kapanış etiketini (`tab-box`) bulun ve hemen önüne sekme içeriği etiketini ekleyin:
```html
<div class="tab-box">
    ... (standart sekmeler tab1, tab2, tab3) ...

    <!-- Çeviri sekmelerinin içeriği -->
    {PAGEADD_I18N_TABS_BODY}
</div>
```

---

### B. Sayfa Düzenleme (`skins/temaniz/page.edit.tpl` veya `modules/page/tpl/page.edit.tpl`)

Düzenleme formunda da benzer değişiklikleri yapın.

Başlıkları ekleyin:
```html
<ul class="tabs">
    <li><a href="{PHP.sys.request_uri}#tab1" class="selected">{PHP.L.Page}</a></li>
    <li><a href="{PHP.sys.request_uri}#tab2">{PHP.L.Meta}</a></li>
    <li><a href="{PHP.sys.request_uri}#tab3">{PHP.L.Options}</a></li>
    <!-- Çeviri için sekmeler -->
    {PAGEEDIT_I18N_TABS_HEADERS}
</ul>
```

Sekme içeriğini ekleyin:
```html
<div class="tab-box">
    ... (standart sekmeler tab1, tab2, tab3) ...

    <!-- Çeviri sekmelerinin içeriği -->
    {PAGEEDIT_I18N_TABS_BODY}
</div>
```

---

### C. Kategori Düzenleme (`skins/temaniz/admin.page.tpl` veya `modules/page/tpl/admin.page.edit.tpl`)

Etiketleri kategori ayarları düzenleme bölümüne (`STRUCTURE_UPDATE`) entegre edin.

Başlıkları ekleyin:
```html
<ul class="tabs">
    <li><a href="{PHP.sys.request_uri}#tab1" class="selected">{PHP.L.Structure}</a></li>
    <li><a href="{PHP.sys.request_uri}#tab2">{PHP.L.Metadata}</a></li>
    <!-- Yapı çevirisi için sekmeler -->
    {STRUCTURE_UPDATE_I18N_TABS_HEADERS}
</ul>
```

Sekme içeriğini ekleyin:
```html
<div class="tab-box">
    ... (standart sekmeler tab1, tab2) ...

    <!-- Yapı çeviri sekmelerinin içeriği -->
    {STRUCTURE_UPDATE_I18N_TABS_BODY}
</div>
```

---

## 3. Web Sitesinde Nasıl Çalışır?

1. Yönetici bir sayfa/kategori eklediğinde veya düzenlediğinde sekmeleri görecektir (örneğin **EN**, **DE**).
2. Bu sekmelerdeki alanları doldurup formu kaydettikten sonra çeviriler veritabanı tablolarına kaydedilecektir.
3. Bir ziyaretçi web sitesini görüntülediğinde:
   - Dili varsayılan dille (`$cfg['defaultlang']`) eşleşiyorsa, ana tablolardaki temel metin görüntülenir.
   - Dili değiştirilirse (örneğin `en` olarak), eklenti başlıkları, açıklamaları, sayfa gövdelerini ve meta etiketlerini otomatik olarak çevrilmiş değerlerle değiştirir.
   - Seçilen dil için çeviri eksikse, **Fallback (Yedekleme)** mekanizması tetiklenir ve sayfa varsayılan dilde görüntülenir.
