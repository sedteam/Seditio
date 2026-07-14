# `useragreement` Eklentisi Kurulum Kılavuzu

Eklenti, kayıt formunda Kullanıcı Sözleşmesi, Gizlilik Politikası ve Kişisel Verilerin İşlenmesi Rızasını kabul etmek için bir onay kutusu görüntüler.

## Adım 1. Eklentinin Kurulması
1. Seditio **Yönetim Paneli**'ne gidin.
2. **Araçlar / Eklentiler** sekmesine geçin.
3. **User Agreement** eklentisini bulun ve **Kur** seçeneğine tıklayın.

## Adım 2. Eklentinin Yapılandırılması
1. Kurulum tamamlandıktan sonra, **User Agreement** eklentisinin yanındaki **Yapılandır** düğmesine tıklayın (veya *Yapılandırma* -> *User Agreement* seçeneğine gidin).
2. İlgili belgelerin URL adreslerini girin:
   - **Kullanıcı Sözleşmesi URL'si**
   - **Gizlilik Politikası URL'si**
   - **Kişisel Verilerin İşlenmesi Rızası URL'si**
   
   *İster 3 bağlantıyı da belirtebilir, isterseniz de herhangi bir kombinasyonunu kullanabilirsiniz. Onay metni ve hata mesajları, etkinleştirdiğiniz bağlantılara göre otomatik olarak oluşturulacaktır.*

## Adım 3. Şablon Entegrasyonu
Onay kutusunun kayıt formunda görüntülenmesi için skininizin kayıt şablonuna `{USERS_REGISTER_AGREEMENT}` etiketini eklemeniz gerekir.

1. Etkin skininizdeki kayıt şablonu dosyasını açın:
   `skins/<skin_adiniz>/users.register.tpl`
   *(Eğer bu dosya skin dizininizde yoksa, `modules/users/tpl/users.register.tpl` adresinden skin dizininize kopyalayın).*

2. Gönder (Submit) düğmesinden hemen önceki alanı bulun (genellikle `<input type="submit" ...>` etiketinin yakınındadır).

3. Aşağıdaki kod bloğunu ekleyin:
   ```html
   <li class="form-row">
       <div class="form-field-100">
           {USERS_REGISTER_AGREEMENT}
       </div>
   </li>
   ```
4. Şablon dosyasını kaydedin ve yönetim panelinden site önbelleğini temizleyin.

## Adım 4. Stil İşlemleri (İsteğe Bağlı)
Belge bağlantılarının altı çizili ve düzgün görünmesi için skininizin stil dosyasına (örneğin, `index.css` veya `custom.css`) aşağıdaki CSS kurallarını ekleyebilirsiniz:

```css
.useragreement-label a {
	text-decoration: underline;
}
.useragreement-label a:hover {
	text-decoration: none;
}
```

