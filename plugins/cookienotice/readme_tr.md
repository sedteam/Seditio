# `cookienotice` Eklentisi Kurulum ve Yapılandırma Kılavuzu

Bu eklenti, web sitesi ziyaretçilerine "Tümünü kabul et" ve "Reddet" butonları ile birlikte bir çerez kullanım bildirimi ve istatistik servisleri onay çubuğu görüntüler.

## Adım 1. Eklentinin kurulması
1. Seditio **Yönetim Paneli**'ne gidin.
2. **Araçlar / Eklentiler** sekmesini açın.
3. Listeden **Cookie Notice** eklentisini bulun ve **Kur** butonuna tıklayın.

## Adım 2. Eklentinin yapılandırılması
4. Kurulum tamamlandıktan sonra **Cookie Notice** eklentisinin yanındaki **Yapılandır** düğmesine tıklayın (veya *Konfigürasyon* -> *Cookie Notice* sayfasına gidin).
5. Ayarları doldurun:
   - **Çerez bildirim metni**: Varsayılan dil dosyasındaki metni kullanmak için bu alanı boş bırakabilirsiniz ya da kendi özel metninizi yazabilirsiniz. Bu alan, aşağıda yapılandırılan bağlantılarla otomatik olarak değiştirilecek olan `{STAT_URL}` ve `{POLICY_URL}` yer tutucularını destekler.
   - **İstatistik servisleri sayfası bağlantısı**: Çerez bilgi sayfasının URL adresini girin (örn. `/sborstat`).
   - **Gizlilik Politikası sayfası bağlantısı**: Gizlilik politikası sayfasının URL adresini girin (örn. `/policy`).

## Adım 3. Şablona etiketin eklenmesi
Bildirimi görüntülemek için alt bilgi şablonunuza `{FOOTER_COOKIENOTICE}` etiketini eklemeniz gerekir.

1. Etkin temanızın alt bilgi şablon dosyasını açın:
   `skins/<tema_adınız>/footer.tpl`
2. `{FOOTER_COOKIENOTICE}` etiketini, kapanış `</body>` etiketinden önce ekleyin. Temel JS dosyalarının yüklenmesinden hemen önce yerleştirilmesi önerilir. Örnek:
   ```html
   {FOOTER_COOKIENOTICE}
   {FOOTER_JAVASCRIPT}
   ```
3. Değişiklikleri kaydedin ve Yönetim Panelinde site önbelleğini temizleyin.
