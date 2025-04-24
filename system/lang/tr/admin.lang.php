<?php

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
-----------------------
Seditio language pack
Language : English (code:tr)
Localization done by : Neocrome
-----------------------
[BEGIN_SED]
File=system/core/admin/lang/tr/admin.lang.php
Version=180
Updated=2025-jan-25
Type=Lang
Author=Seditio Team
Description=Admin panel
[END_SED]
==================== */

/* ====== Core ====== */

$L['core_main'] = "Ana ayar";
$L['core_parser'] = "Yorumlayıcı";             // v120'de yeni
$L['core_rss'] = "RSS beslemeleri";             // v173'te yeni
$L['core_dic'] = "Dizinler ve Ekstra alanlar";  // v173'te yeni
$L['core_time'] = "Zaman ve tarih";
$L['core_skin'] = "Temalar";
$L['core_lang'] = "Diller";
$L['core_menus'] = "Menü slotları";
$L['core_comments'] = "Yorumlar";
$L['core_forums'] = "Forumlar";
$L['core_page'] = "Sayfalar";
$L['core_pfs'] = "Kişisel dosya alanı";
$L['core_gallery'] = "Galeri";
$L['core_plug'] = "Eklentiler";
$L['core_pm'] = "Özel mesajlar";
$L['core_polls'] = "Anketler";
$L['core_ratings'] = "Değerlendirmeler";
$L['core_trash'] = "Çöp kutusu";
$L['core_users'] = "Kullanıcılar";
$L['core_meta'] = "HTML Meta";
$L['core_index'] = "Ana sayfa";
$L['core_menu'] = "Menü yöneticisi"; // v178'de yeni

/* ====== Upgrade ====== */

$L['upg_upgrade'] = "Yükseltme";      // v130'da yeni
$L['upg_codeversion'] = "Kod sürümü";     // v130'da yeni
$L['upg_sqlversion'] = "SQL veritabanı sürümü";    // v130'da yeni
$L['upg_codeisnewer'] = "Kod, SQL sürümünden daha yenidir.";    // v130'da yeni
$L['upg_codeisolder'] = "Kod, SQL sürümünden daha eskidir, bu olağan dışıdır ve desteklenmez.<br />En yeni paketle tüm dosyaları yüklediğinizden emin olun.";    // v130'da yeni
$L['upg_codeissame'] = "Kod ve SQL sürümleri eşleşiyor.";    // v130'da yeni
$L['upg_upgradenow'] = "SQL veritabanını hemen yükseltmeniz şiddetle tavsiye edilir, yükseltmek için buraya tıklayın!";    // v130'da yeni
$L['upg_upgradenotavail'] = "Bu sürüm numaraları için bir yükseltme mevcut değil.";       // v130'da yeni
$L['upg_manual'] = "Veritabanını manuel olarak yükseltmek isterseniz, SQL betikleri /docs/upgrade/ klasöründe bulunmaktadır.";       // v130'da yeni
$L['upg_success'] = "Yükseltme başarılı oldu, devam etmek için buraya tıklayın...";       // v130'da yeni
$L['upg_failure'] = "Yükseltme başarısız oldu, devam etmek için buraya tıklayın...";       // v130'da yeni
$L['upg_force'] = "Bazı nedenlerden dolayı, SQL veritabanında yazılı olan Seditio sürüm numarası yanlış olabilir. Aşağıda SQL sürüm numarasını zorlamak için bir buton bulunmaktadır, bu yalnızca SQL veritabanını etiketleyecektir, başka hiçbir değişiklik yapmayacaktır.<br />SQL sürüm numarasını şu şekilde zorla: ";    // v130'da yeni

/* ====== General ====== */

$L['editdeleteentries'] = "Girişleri düzenle veya sil";
$L['viewdeleteentries'] = "Girişleri görüntüle veya sil";
$L['addnewentry'] = "Yeni bir giriş ekle";
$L['adm_purgeall'] = "Hepsini temizle";
$L['adm_listisempty'] = "Liste boş";
$L['adm_totalsize'] = "Toplam boyut";
$L['adm_showall'] = "Hepsini göster";
$L['adm_area'] = "Bölge";
$L['adm_option'] = "Seçenek";
$L['adm_setby'] = "Tarafından ayarlandı";
$L['adm_more'] = "Daha fazla araç...";
$L['adm_from'] = "Kimden";
$L['adm_to'] = "Kime";
$L['adm_confirm'] = "Onaylamak için bu butona basın : ";
$L['adm_done'] = "Tamamlandı";
$L['adm_failed'] = "Başarısız";
$L['adm_warnings'] = "Uyarılar";
$L['adm_valqueue'] = "Onay bekliyor";
$L['adm_required'] = "(Gerekli)";
$L['adm_clicktoedit'] = "(Düzenlemek için tıklayın)";
$L['adm_manage'] = "Yönet";  // v150'de yeni
$L['adm_pagemanager'] = "Sayfa yöneticisi";  // v177'de yeni
$L['adm_module_name'] = "Modül adı";  // v178'de yeni
$L['adm_tool_name'] = "Araç adı";  // v178'de yeni

/* ====== Banlist ====== */

$L['adm_ipmask'] = "IP maskesi";
$L['adm_emailmask'] = "Email maskesi";
$L['adm_neverexpire'] = "Asla süresi dolmaz";
$L['adm_help_banlist'] = "IP maskeleri için örnekler:<br />
- IPv4: 194.31.13.41, 194.31.13.*, 194.31.*.*, 194.*.*.*<br />
- IPv6: 2001:0db8:85a3:0000:0000:8a2e:0370:7334, 2001:0db8:85a3:0000:0000:8a2e:0370:*, 2001:0db8:85a3:0000:0000:*:*, 2001:0db8:85a3:*:*:*:*<br />
Email maskeleri için örnekler: @hotmail.com, @yahoo (Joker karakterler desteklenmez)<br />
Bir giriş yalnızca bir IP maskesi veya bir email maskesi veya her ikisini de içerebilir.<br />
IP'ler her sayfa görüntülenirken filtrelenir, email maskeleri ise yalnızca kullanıcı kaydında filtrelenir.";

/* ====== Cache ====== */

$L['adm_internalcache'] = "Dahili önbellek";
$L['adm_help_cache'] = "Kullanılamaz";

/* ====== Configuration ====== */

$L['adm_help_config'] = "Kullanılamaz";
$L['cfg_adminemail'] = array("Yönetici e-posta adresi", "Gerekli");
$L['cfg_maintitle'] = array("Site başlığı", "Web sitesi için ana başlık, gerekli");
$L['cfg_subtitle'] = array("Açıklama", "Opsiyonel, site başlığının ardından gösterilecektir");
$L['cfg_mainurl'] = array("Site URL'si", "http:// ile, ve sonlandırıcı eğik çizgi olmadan!");
$L['cfg_clustermode'] = array("Sunucu kümesi", "Yük dengeleme yapılandırması ise evet olarak ayarlayın.");            // v125'te yeni
$L['cfg_hostip'] = array("Sunucu IP'si", "Sunucunun IP'si, isteğe bağlı.");
$L['cfg_gzip'] = array("Gzip", "HTML çıktısının Gzip sıkıştırması");
$L['cfg_cache'] = array("Dahili önbellek", "Daha iyi performans için etkin bırakın");
$L['cfg_devmode'] = array("Hata ayıklama modu", "Canlı sitelerde etkin bırakmayın");
$L['cfg_doctypeid'] = array("Belge Türü", "&lt;!DOCTYPE> HTML düzeni");
$L['cfg_charset'] = array("HTML karakter seti", "");
$L['cfg_cookiedomain'] = array("Çerezler için domain", "Varsayılan: boş");
$L['cfg_cookiepath'] = array("Çerezler için yol", "Varsayılan: boş");
$L['cfg_cookielifetime'] = array("Maksimum çerez ömrü", "Saniye cinsinden");
$L['cfg_metakeywords'] = array("HTML Meta anahtar kelimeleri (virgülle ayrılmış)", "Arama motorları için");
$L['cfg_disablesysinfos'] = array("Sayfa oluşturma zamanını kapat", "footer.tpl içinde");
$L['cfg_keepcrbottom'] = array("Telif hakkı bildirimini {FOOTER_BOTTOMLINE} etiketinde tut", "footer.tpl içinde");
$L['cfg_showsqlstats'] = array("SQL sorguları istatistiklerini göster", "footer.tpl içinde");
$L['cfg_shieldenabled'] = array("Kalkanı etkinleştir", "Anti-spam ve anti-hammering");
$L['cfg_shieldtadjust'] = array("Kalkan zamanlayıcılarını ayarla (%)", "Yüksekse, spam yapması daha zor olur");
$L['cfg_shieldzhammer'] = array("Hızlı tıklamalar sonrası anti-hammer", "Küçükse, otomatik yasaklama 3 dakika sonra daha hızlı olur");
$L['cfg_maintenance'] = array("Bakım modu", "Sitedeki teknik çalışmaları başlat");  // v175'te yeni
$L['cfg_maintenancelevel'] = array("Kullanıcı Erişim Seviyesi", "Kullanıcılar için erişim seviyesini seçin"); // v175'te yeni
$L['cfg_maintenancereason'] = array("Bakım nedeni", "Bakım nedenini açıklayın"); // v175'te yeni
$L['cfg_multihost'] = array("Çoklu host modu", "Birden fazla hostu etkinleştirin");  // v175'te yeni
$L['cfg_absurls'] = array("Mutlak URL", "Mutlak URL kullanımını etkinleştir");  // v175'te yeni
$L['cfg_sefurls'] = array("SEF URL'leri", "Sitede SEF URL'lerini etkinleştir");  // v175'te yeni
$L['cfg_sefurls301'] = array("SEF URL'lere 301 yönlendirmesi", "Eski URL'den SEF URL'lere 301 yönlendirmesini etkinleştir");  // v175'te yeni

$L['cfg_dateformat'] = array("Ana tarih maskesi", "Varsayılan: Y-m-d H:i");
$L['cfg_formatmonthday'] = array("Kısa tarih maskesi", "Varsayılan: m-d");
$L['cfg_formatyearmonthday'] = array("Orta tarih maskesi", "Varsayılan: Y-m-d");
$L['cfg_formatmonthdayhourmin'] = array("Forum tarih maskesi", "Varsayılan: m-d H:i");
$L['cfg_servertimezone'] = array("Sunucu zaman dilimi", "Sunucunun GMT+00'a göre farkı");
$L['cfg_defaulttimezone'] = array("Varsayılan zaman dilimi", "Misafirler ve yeni üyeler için, -12 ile +12 arasında");
$L['cfg_timedout'] = array("Boşta bekleme süresi, saniye cinsinden", "Bu süre sonrasında kullanıcı 'uzak' olarak kabul edilir");
$L['cfg_maxusersperpage'] = array("Kullanıcı listesinde maksimum satır", "");
$L['cfg_regrequireadmin'] = array("Yöneticiler yeni hesapları onaylamalı", "");
$L['cfg_regnoactivation'] = array("Yeni kullanıcılar için e-posta kontrolünü atla", "\"Hayır\" önerilir, güvenlik nedeniyle");
$L['cfg_useremailchange'] = array("Kullanıcıların e-posta adreslerini değiştirmelerine izin ver", "\"Hayır\" önerilir, güvenlik nedeniyle");
$L['cfg_usertextimg'] = array("Kullanıcı imzasında resim ve HTML'ye izin ver", "\"Hayır\" önerilir, güvenlik nedeniyle");
$L['cfg_av_maxsize'] = array("Avatar, maksimum dosya boyutu", "Varsayılan: 8000 bayt");
$L['cfg_av_maxx'] = array("Avatar, maksimum genişlik", "Varsayılan: 64 piksel");
$L['cfg_av_maxy'] = array("Avatar, maksimum yükseklik", "Varsayılan: 64 piksel");
$L['cfg_usertextmax'] = array("Kullanıcı imzası için maksimum uzunluk", "Varsayılan: 300 karakter");
$L['cfg_sig_maxsize'] = array("İmza, maksimum dosya boyutu", "Varsayılan: 50000 bayt");
$L['cfg_sig_maxx'] = array("İmza, maksimum genişlik", "Varsayılan: 468 piksel");
$L['cfg_sig_maxy'] = array("İmza, maksimum yükseklik", "Varsayılan: 60 piksel");
$L['cfg_ph_maxsize'] = array("Fotoğraf, maksimum dosya boyutu", "Varsayılan: 8000 bayt");
$L['cfg_ph_maxx'] = array("Fotoğraf, maksimum genişlik", "Varsayılan: 96 piksel");
$L['cfg_ph_maxy'] = array("Fotoğraf, maksimum yükseklik", "Varsayılan: 96 piksel");
$L['cfg_maxrowsperpage'] = array("Listelerdeki maksimum satır", "");
$L['cfg_showpagesubcatgroup'] = array("Alt kategorilerden sayfaları gruplar halinde göster", "");   //Yeni Sed171
$L['cfg_genseourls'] = array("SEO URL'si oluştur (otomatik oluştur* sayfa takma adı)?", "");   //Yeni Sed178
$L['cfg_maxcommentsperpage'] = array("Sayfa başına maksimum yorum", "");  //Yeni Sed173
$L['cfg_commentsorder'] = array("Yorum sıralama düzeni", "ASC - yeni alt sırada, DESC - en yenisi üstte");  //Yeni Sed173
$L['cfg_maxtimeallowcomedit'] = array("Yorumları düzenlemek için izin verilen süre", "Dakika cinsinden, 0 ise - düzenleme yasaktır");  //Yeni Sed173
$L['cfg_showcommentsonpage'] = array("Sayfalarda yorumları göster", "Varsayılan olarak sayfada yorum gösterir");   //Yeni Sed171
$L['cfg_maxcommentlenght'] = array("Bir yorumun maksimum uzunluğu", "Varsayılan: 2000 karakter");  //Yeni Sed175
$L['cfg_countcomments'] = array("Yorum sayısını say", "Yorum simgesinin yanında yorum sayısını göster");
$L['cfg_hideprivateforums'] = array("Özel forumları gizle", "");
$L['cfg_hottopictrigger'] = array("Bir konu 'sıcak' hale gelmesi için gönderiler", "");
$L['cfg_maxtopicsperpage'] = array("Sayfa başına maksimum konu veya gönderi", "");
$L['cfg_antibumpforums'] = array("Anti-bump koruması", "Kullanıcıların aynı konuda arka arkaya iki kez gönderi yapmasını engeller");
$L['cfg_pfsuserfolder'] = array("Klasör depolama modu", "Etkinleştirilirse, kullanıcı dosyalarını /datas/users/USERID/... alt klasörlerinde depolar, dosya adının önüne USERID eklemek yerine. Sadece siteyi ilk kurarken ayarlanabilir. Bir dosya PFS'ye yüklendiğinde, bu değiştirilemez.");
$L['cfg_th_amode'] = array("Küçük resim oluşturma", "");
$L['cfg_th_x'] = array("Küçük resimler, genişlik", "Varsayılan: 112 piksel");
$L['cfg_th_y'] = array("Küçük resimler, yükseklik", "Varsayılan: 84 piksel, önerilen: Genişlik x 0.75");

//$L['cfg_th_border'] = array("Küçük resimler, kenarlık boyutu", "Varsayılan: 4 piksel");
$L['cfg_th_keepratio'] = array("Küçük resimler, oranı korusun mu?", "");
$L['cfg_th_jpeg_quality'] = array("Küçük resimler, Jpeg kalitesi", "Varsayılan: 85");
//$L['cfg_th_colorbg'] = array("Küçük resimler, kenarlık rengi", "Varsayılan: 000000, hex renk kodu");
//$L['cfg_th_colortext'] = array("Küçük resimler, yazı rengi", "Varsayılan: FFFFFF, hex renk kodu");
$L['cfg_th_rel'] = array("Küçük resimler, rel özniteliği", "Varsayılan: sedthumb"); // Yeni v175
$L['cfg_th_dimpriority'] = array("Küçük resimler, yeniden boyutlandırma önceliği", "Varsayılan: Genişlik"); // Yeni v160
//$L['cfg_th_textsize'] = array("Küçük resimler, yazı boyutu", "");
$L['cfg_pfs_filemask'] = array("Dosya isimleri zaman desenine göre", "Zaman desenine göre dosya isimleri oluştur");  // Yeni sed172

$L['cfg_available_image_sizes'] = array("Mevcut görüntü çözünürlükleri", "Virgülle sıralanmış, boşluk yok. Örnek: 120x80,800x600");  // Yeni sed180

$L['cfg_disable_gallery'] = array("Galeriyi devre dışı bırak", "");         // Yeni v150
$L['cfg_gallery_gcol'] = array("Galeriler için sütun sayısı", "Varsayılan : 4");     // Yeni v150
$L['cfg_gallery_bcol'] = array("Resimler için sütun sayısı", "Varsayılan : 6");        // Yeni v150
$L['cfg_gallery_logofile'] = array("Tüm yeni PFS resimlerine eklenecek Png/jpeg/Gif logosu", "Devre dışı bırakmak için boş bırakın");        // Yeni v150
$L['cfg_gallery_logopos'] = array("PFS resimlerindeki logo konumu", "Varsayılan : Alt sol");        // Yeni v150
$L['cfg_gallery_logotrsp'] = array("Logonun birleştirme seviyesi, % olarak", "Varsayılan : 50");        // Yeni v150
$L['cfg_gallery_logojpegqual'] = array("Logo eklendikten sonra final görüntüsünün kalitesi, eğer Jpeg ise", "Varsayılan : 90");        // Yeni v150
$L['cfg_gallery_imgmaxwidth'] = array("Görüntü için maksimum genişlik, eğer daha büyükse boyut küçültülmüş bir kopya işlenecek", "");         // Yeni v150

$L['cfg_pm_maxsize'] = array("Mesajlar için maksimum uzunluk", "Varsayılan: 10000 karakter");
$L['cfg_pm_allownotifications'] = array("E-posta ile PM bildirimlerine izin ver", "");
$L['cfg_disablehitstats'] = array("Hit istatistiklerini devre dışı bırak", "Referanslar ve günlük hitler");
$L['cfg_disablereg'] = array("Kayıt işlemini devre dışı bırak", "Kullanıcıların yeni hesaplar kaydetmesini engeller");
$L['cfg_disablewhosonline'] = array("Kimler çevrimiçi kısmını devre dışı bırak", "Shield'ı etkinleştirirseniz otomatik olarak etkinleştirilir");
$L['cfg_defaultcountry'] = array("Yeni kullanıcılar için varsayılan ülke", "2 harfli ülke kodu");    // Yeni v130
$L['cfg_forcedefaultskin'] = array("Tüm kullanıcılar için varsayılan temayı zorla", "");
$L['cfg_forcedefaultlang'] = array("Tüm kullanıcılar için varsayılan dili zorla", "");
$L['cfg_separator'] = array("Genel ayırıcı", "Varsayılan:>");
$L['cfg_menu1'] = array("Menü slotu #1<br />{PHP.cfg.menu1} tüm tpl dosyalarında", "");
$L['cfg_menu2'] = array("Menü slotu #2<br />{PHP.cfg.menu2} tüm tpl dosyalarında", "");
$L['cfg_menu3'] = array("Menü slotu #3<br />{PHP.cfg.menu3} tüm tpl dosyalarında", "");
$L['cfg_menu4'] = array("Menü slotu #4<br />{PHP.cfg.menu4} tüm tpl dosyalarında", "");
$L['cfg_menu5'] = array("Menü slotu #5<br />{PHP.cfg.menu5} tüm tpl dosyalarında", "");
$L['cfg_menu6'] = array("Menü slotu #6<br />{PHP.cfg.menu6} tüm tpl dosyalarında", "");
$L['cfg_menu7'] = array("Menü slotu #7<br />{PHP.cfg.menu7} tüm tpl dosyalarında", "");
$L['cfg_menu8'] = array("Menü slotu #8<br />{PHP.cfg.menu8} tüm tpl dosyalarında", "");
$L['cfg_menu9'] = array("Menü slotu #9<br />{PHP.cfg.menu9} tüm tpl dosyalarında", "");
$L['cfg_topline'] = array("Üst satır<br />{HEADER_TOPLINE} header.tpl dosyasında", "");
$L['cfg_banner'] = array("Banner<br />{HEADER_BANNER} header.tpl dosyasında", "");
$L['cfg_motd'] = array("Günün mesajı<br />{NEWS_MOTD} index.tpl dosyasında", "");
$L['cfg_bottomline'] = array("Alt satır<br />{FOOTER_BOTTOMLINE} footer.tpl dosyasında", "");
$L['cfg_freetext1'] = array("Serbest metin Alanı #1<br />{PHP.cfg.freetext1} tüm tpl dosyalarında", "");
$L['cfg_freetext2'] = array("Serbest metin Alanı #2<br />{PHP.cfg.freetext2} tüm tpl dosyalarında", "");
$L['cfg_freetext3'] = array("Serbest metin Alanı #3<br />{PHP.cfg.freetext3} tüm tpl dosyalarında", "");
$L['cfg_freetext4'] = array("Serbest metin Alanı #4<br />{PHP.cfg.freetext4} tüm tpl dosyalarında", "");
$L['cfg_freetext5'] = array("Serbest metin Alanı #5<br />{PHP.cfg.freetext5} tüm tpl dosyalarında", "");
$L['cfg_freetext6'] = array("Serbest metin Alanı #6<br />{PHP.cfg.freetext6} tüm tpl dosyalarında", "");
$L['cfg_freetext7'] = array("Serbest metin Alanı #7<br />{PHP.cfg.freetext7} tüm tpl dosyalarında", "");
$L['cfg_freetext8'] = array("Serbest metin Alanı #8<br />{PHP.cfg.freetext8} tüm tpl dosyalarında", "");
$L['cfg_freetext9'] = array("Serbest metin Alanı #9<br />{PHP.cfg.freetext9} tüm tpl dosyalarında", "");

$L['cfg_extra1title'] = array("Alan #1 (Dizi), başlık", "");
$L['cfg_extra2title'] = array("Alan #2 (Dizi), başlık", "");
$L['cfg_extra3title'] = array("Alan #3 (Dizi), başlık", "");
$L['cfg_extra4title'] = array("Alan #4 (Dizi), başlık", "");
$L['cfg_extra5title'] = array("Alan #5 (Dizi), başlık", "");
$L['cfg_extra6title'] = array("Alan #6 (Seçim kutusu), başlık", "");
$L['cfg_extra7title'] = array("Alan #7 (Seçim kutusu), başlık", "");
$L['cfg_extra8title'] = array("Alan #8 (Seçim kutusu), başlık", "");
$L['cfg_extra9title'] = array("Alan #9 (Uzun metin), başlık", "");
$L['cfg_extra1tsetting'] = array("Bu alandaki maksimum karakter sayısı", "");
$L['cfg_extra2tsetting'] = array("Bu alandaki maksimum karakter sayısı", "");
$L['cfg_extra3tsetting'] = array("Bu alandaki maksimum karakter sayısı", "");
$L['cfg_extra4tsetting'] = array("Bu alandaki maksimum karakter sayısı", "");
$L['cfg_extra5tsetting'] = array("Bu alandaki maksimum karakter sayısı", "");
$L['cfg_extra6tsetting'] = array("Seçim kutusundaki değerler, virgülle ayrılmış", "");
$L['cfg_extra7tsetting'] = array("Seçim kutusundaki değerler, virgülle ayrılmış", "");
$L['cfg_extra8tsetting'] = array("Seçim kutusundaki değerler, virgülle ayrılmış", "");
$L['cfg_extra9tsetting'] = array("Metnin maksimum uzunluğu", "");
$L['cfg_extra1uchange'] = array("Kullanıcı profilinde düzenlenebilir mi?", "");
$L['cfg_extra2uchange'] = array("Kullanıcı profilinde düzenlenebilir mi?", "");
$L['cfg_extra3uchange'] = array("Kullanıcı profilinde düzenlenebilir mi?", "");
$L['cfg_extra4uchange'] = array("Kullanıcı profilinde düzenlenebilir mi?", "");
$L['cfg_extra5uchange'] = array("Kullanıcı profilinde düzenlenebilir mi?", "");
$L['cfg_extra6uchange'] = array("Kullanıcı profilinde düzenlenebilir mi?", "");
$L['cfg_extra7uchange'] = array("Kullanıcı profilinde düzenlenebilir mi?", "");
$L['cfg_extra8uchange'] = array("Kullanıcı profilinde düzenlenebilir mi?", "");
$L['cfg_extra9uchange'] = array("Kullanıcı profilinde düzenlenebilir mi?", "");

$L['cfg_disable_comments'] = array("Yorumları devre dışı bırak", "");
$L['cfg_disable_forums'] = array("Forumları devre dışı bırak", "");
$L['cfg_disable_pfs'] = array("PFS'yi devre dışı bırak", "");
$L['cfg_disable_polls'] = array("Anketleri devre dışı bırak", "");
$L['cfg_disable_pm'] = array("Özel mesajları devre dışı bırak", "");
$L['cfg_disable_ratings'] = array("Değerlendirmeleri devre dışı bırak", "");
$L['cfg_disable_page'] = array("Sayfaları devre dışı bırak", "");
$L['cfg_disable_plug'] = array("Eklentileri devre dışı bırak", "");
$L['cfg_trash_prunedelay'] = array("Çöp kutasındaki öğeleri * gün sonra sil (Sonsuza kadar tutmak için sıfır)", "");
$L['cfg_trash_comment'] = array("Yorumlar için çöp kutusunu kullan", "");
$L['cfg_trash_forum'] = array("Forumlar için çöp kutusunu kullan", "");
$L['cfg_trash_page'] = array("Sayfalar için çöp kutusunu kullan", "");
$L['cfg_trash_pm'] = array("Özel mesajlar için çöp kutusunu kullan", "");
$L['cfg_trash_user'] = array("Kullanıcılar için çöp kutusunu kullan", "");

$L['cfg_color_group'] = array("Kullanıcı gruplarını renkli yap", "Varsayılan: Hayır, daha iyi performans için");  // Yeni v175
$L['cfg_ajax'] = array("AJAX'ı etkinleştir", "");  // Yeni v175
$L['cfg_enablemodal'] = array("Modal pencereleri etkinleştir", "");  // Yeni v175
$L['cfg_hometitle'] = array("Ana sayfa başlığı", "Opsiyonel, SEO için"); // Yeni v179
$L['cfg_homemetadescription'] = array("Ana sayfa meta açıklaması", "Opsiyonel, SEO için"); // Yeni v179
$L['cfg_homemetakeywords'] = array("Ana sayfa meta anahtar kelimeleri", "Opsiyonel, SEO için"); // Yeni v179

/* ====== HTML Meta ====== */

$L['cfg_defaulttitle'] = array("Varsayılan Başlık", "Mevcut seçenekler: {MAINTITLE}, {SUBTITLE}");        //Sed 175
$L['cfg_indextitle'] = array("Ana Sayfa Başlığı", "Mevcut seçenekler: {MAINTITLE}, {SUBTITLE}, {TITLE}");        //Sed 179
$L['cfg_listtitle'] = array("Sayfa listeleri için başlık", "Mevcut seçenekler: {MAINTITLE}, {SUBTITLE}, {TITLE}");        //Sed 175
$L['cfg_pagetitle'] = array("Sayfa başlığı", "Mevcut seçenekler: {MAINTITLE}, {SUBTITLE}, {TITLE}, {CATEGORY}");        //Sed 175
$L['cfg_forumstitle'] = array("Forum başlığı", "Mevcut seçenekler: {MAINTITLE}, {SUBTITLE}, {TITLE}");        //Sed 175
$L['cfg_userstitle'] = array("Kullanıcı başlığı", "Mevcut seçenekler: {MAINTITLE}, {SUBTITLE}, {TITLE}");        //Sed 175
$L['cfg_pmtitle'] = array("Özel Mesaj başlığı", "Mevcut seçenekler: {MAINTITLE}, {SUBTITLE}, {TITLE}");        //Sed 175
$L['cfg_gallerytitle'] = array("Galeri başlığı", "Mevcut seçenekler: {MAINTITLE}, {SUBTITLE}, {TITLE}");        //Sed 175
$L['cfg_pfstitle'] = array("PFS başlığı", "Mevcut seçenekler: {MAINTITLE}, {SUBTITLE}, {TITLE}");        ///Sed 175
$L['cfg_plugtitle'] = array("Eklenti başlığı", "Mevcut seçenekler: {MAINTITLE}, {SUBTITLE}, {TITLE}");        ///Sed 175

/* ====== Rss ====== */

$L['cfg_disable_rss'] = array("RSS beslemelerini devre dışı bırak", "");
$L['cfg_disable_rsspages'] = array("Sayfalar için RSS beslemesini devre dışı bırak", "");
$L['cfg_disable_rsscomments'] = array("Yorumlar için RSS beslemesini devre dışı bırak", "");
$L['cfg_disable_rssforums'] = array("Forumlar için RSS beslemesini devre dışı bırak", "");
$L['cfg_rss_timetolive'] = array("RSS beslemesi için önbellek süresi", "saniye cinsinden");
$L['cfg_rss_defaultcode'] = array("Varsayılan RSS beslemesi", "kategori kodunu girin");
$L['cfg_rss_maxitems'] = array("RSS beslemesindeki maksimum satır sayısı", "");

$L['adm_help_config_rss'] = "Açılabilir RSS besleme bağlantıları: <br />" . $cfg['mainurl'] . "/" . "rss (varsayılan olarak, ayarlarda belirtilen haber kategorilerinin çıktısı) <br /> " . $cfg['mainurl'] . "/" . "rss/pages?c=XX (XX - Kategori kodu, kategorinin son sayfaları) <br />" . $cfg['mainurl'] . "/" . "rss/comments?id=XX (XX - Sayfa ID'si, yorumlar sayfası) <br />" . $cfg['mainurl'] . "/" . "rss/forums (tüm forum bölümlerinden son yazılar) <br />" . $cfg['mainurl'] . "/" . "rss/forums?s=XX (XX - Bölüm ID'si, bölümdeki son yazılar) <br />" . $cfg['mainurl'] . "/" . "rss/forums?q=XX (XX - Konu ID'si, konudaki son yazılar) <br />" . $cfg['mainurl'] . "/" . "rss/forums?s=XX&q=YY (XX - Bölüm ID'si, YY - Konu ID'si)";

/* ====== Forums ====== */

$L['adm_diplaysignatures'] = "İmzalara Göster";
$L['adm_enablebbcodes'] = "BBcode'ları Etkinleştir";
$L['adm_enablesmilies'] = "Gülücükleri Etkinleştir";
$L['adm_enableprvtopics'] = "Özel Konulara İzin Ver";
$L['adm_countposts'] = "Gönderi Sayısını Say";
$L['adm_autoprune'] = "* gün sonra konuları otomatik olarak temizle";
$L['adm_postcounters'] = "Sayaçları Kontrol Et";
$L['adm_help_forums'] = "Mevcut değil";
$L['adm_forum_structure'] = "Forumların Yapısı (Kategoriler)";
$L['adm_forum_structure_cat'] = "Forumların Yapısı";
$L['adm_help_forums_structure'] = "Mevcut değil";
$L['adm_defstate'] = "Varsayılan Durum";
$L['adm_defstate_0'] = "Katlanmış";
$L['adm_defstate_1'] = "Açık";
$L['adm_parentcat'] = "Üst Kategori";    // Sed 172'de yeni

/* ====== IP search ====== */

$L['adm_searchthisuser'] = "Bu IP'yi kullanıcı veritabanında ara";
$L['adm_dnsrecord'] = "Bu adres için DNS kaydı";

/* ====== Gülücükler ====== */

$L['adm_help_smilies'] = "Mevcut değil";

/* ====== Dictionary ====== */

$L['adm_dic_list'] = "Dizinler listesi";
$L['adm_dictionary'] = "Dizin";
$L['adm_dic_title'] = "Dizin başlığı";
$L['adm_dic_code'] = "Dizin kodu (ekstra alan adı)";
$L['adm_dic_list'] = "Dizinler listesi";
$L['adm_dic_term_list'] = "Terimler listesi";
$L['adm_dic_add'] = "Yeni dizin ekle";
$L['adm_dic_edit'] = "Dizini düzenle";
$L['adm_dic_add_term'] = "Yeni terim ekle";
$L['adm_dic_term_title'] = "Terim başlığı";
$L['adm_dic_term_value'] = "Terim değeri";
$L['adm_dic_term_defval'] = "Bir terimi varsayılan yap?";
$L['adm_dic_term_edit'] = "Dizinden terimi düzenle";
$L['adm_dic_children'] = "Alt dizin";
$L['adm_dic_mera'] = "Birim";
$L['adm_dic_values'] = "Seçim, radio, checkbox için değerler";
$L['adm_dic_form_title'] = "Form elemanı başlığı";
$L['adm_dic_form_desc'] = "Form elemanı için metin";
$L['adm_dic_form_size'] = "Metin alanının boyutu";
$L['adm_dic_form_maxsize'] = "Metin alanının maksimum boyutu";
$L['adm_dic_form_cols'] = "Metin alanının sütun sayısı";
$L['adm_dic_form_rows'] = "Metin alanının satır sayısı";

$L['adm_dic_extra'] = "Ekstra alan";
$L['adm_dic_addextra'] = "Ekstra alan ekle";
$L['adm_dic_editextra'] = "Ekstra alanı düzenle";
$L['adm_dic_extra_location'] = "Tablo adı";
$L['adm_dic_extra_type'] = "Alan veri tipi";
$L['adm_dic_extra_size'] = "Alan uzunluğu";
$L['adm_dic_comma_separat'] = "(virgülle ayrılmış)";
$L['adm_help_dic'] = ""; //Ekleme gerekli

/* ====== Menu manager ====== */

$L['adm_menuitems'] = "Menü öğeleri";
$L['adm_additem'] = "Öğe ekle";
$L['adm_position'] = "Pozisyon";
$L['adm_confirm_delete'] = "Silmeyi onaylıyor musunuz?";
$L['adm_addmenuitem'] = "Menü öğesi ekle";
$L['adm_editmenuitem'] = "Menü öğesini düzenle";
$L['adm_parentitem'] = "Ana öğe";
$L['adm_url'] = "URL";
$L['adm_activity'] = "Aktif mi?";

/* ====== PFS ====== */

$L['adm_gd'] = "GD grafik kütüphanesi";
$L['adm_allpfs'] = "Tüm PFS'ler";
$L['adm_allfiles'] = "Tüm dosyalar";
$L['adm_thumbnails'] = "Küçük resimler";
$L['adm_orphandbentries'] = "Yalnız kalan veritabanı girişleri";
$L['adm_orphanfiles'] = "Yalnız kalan dosyalar";
$L['adm_delallthumbs'] = "Tüm küçük resimleri sil";
$L['adm_rebuildallthumbs'] = "Tüm küçük resimleri sil ve yeniden oluştur";
$L['adm_help_pfsthumbs'] = "Mevcut değil";
$L['adm_help_check1'] = "Mevcut değil";
$L['adm_help_check2'] = "Mevcut değil";
$L['adm_help_pfsfiles'] = "Mevcut değil";
$L['adm_help_allpfs'] = "Mevcut değil";
$L['adm_nogd'] = "Bu host GD grafik kütüphanesini desteklemiyor, bu yüzden Seditio PFS resimleri için küçük resimler oluşturamayacak. Konfigürasyon paneline gitmeli, 'Kişisel Dosya Alanı' sekmesinden Küçük Resim Oluşturma = 'Devre Dışı' olarak ayarlanmalıdır.";

/* ====== Pages ====== */

$L['adm_structure'] = "Sayfaların yapısı (kategoriler)";
$L['adm_syspages'] = "'Sistem' kategorisini görüntüle";
$L['adm_help_page'] = "'Sistem' kategorisine ait sayfalar, kamuya açık listelemelerde yer almaz, bağımsız sayfalar oluşturmak içindir.";
$L['adm_sortingorder'] = "Kategoriler için varsayılan sıralama düzenini ayarla";
$L['adm_fileyesno'] = "Dosya (evet/hayır)";
$L['adm_fileurl'] = "Dosya URL'si";
$L['adm_filesize'] = "Dosya boyutu";
$L['adm_filecount'] = "Dosya görüntülenme sayısı";
$L['adm_tpl_mode'] = "Şablon modu";
$L['adm_tpl_empty'] = "Varsayılan";
$L['adm_tpl_forced'] = "Aynı olan";
$L['adm_tpl_parent'] = "Ana kategoriyle aynı olan";
$L['adm_enablecomments'] = "Yorumları etkinleştir";   // Yeni v173
$L['adm_enableratings'] = "Puanlamayı etkinleştir";     // Yeni v173

/* ====== Polls ====== */

$L['adm_help_polls'] = "Yeni bir anket konusu oluşturduktan sonra, bu ankete seçenekler (tercihler) eklemek için 'Düzenle'yi seçin.<br />'Sil' seçeneği, seçilen anketi, seçenekleri ve ilgili tüm oyları siler.<br />'Sıfırla' seçeneği, seçilen anketin tüm oylarını siler. Ankete veya seçeneklere zarar vermez.<br />'Yükselt' seçeneği, anketin oluşturulma tarihini geçerli tarihe değiştirir ve böylece anketi 'güncel' yaparak listenin en üstüne çıkarır.";
$L['adm_poll_title'] = "Anket başlığı";

/* ====== Statistics ====== */

$L['adm_phpver'] = "PHP motoru sürümü";
$L['adm_zendver'] = "Zend motoru sürümü";
$L['adm_interface'] = "Web sunucusu ile PHP arasındaki arayüz";
$L['adm_os'] = "İşletim sistemi";
$L['adm_clocks'] = "Saatler";
$L['adm_time1'] = "#1 : Ham sunucu saati";
$L['adm_time2'] = "#2 : Sunucu tarafından döndürülen GMT saati";
$L['adm_time3'] = "#3 : GMT saati + sunucu offseti (Seditio referansı)";
$L['adm_time4'] = "#4 : Profilinizden ayarlanmış yerel saatiniz";
$L['adm_help_versions'] = "Sunucu saat dilimini ayarlayarak saat #3'ün düzgün bir şekilde ayarlandığından emin olun.<br />Saat #4, profilinizdeki saat dilimi ayarına bağlıdır.<br />Saatler #1 ve #2, Seditio tarafından dikkate alınmaz.";
$L['adm_log'] = "Sistem kaydı";
$L['adm_infos'] = "Bilgiler";
$L['adm_versiondclocks'] = "Sürümler ve saatler";
$L['adm_checkcoreskins'] = "Temel dosyalar ve temalar kontrol edilsin";
$L['adm_checkcorenow'] = "Temel dosyaları şimdi kontrol et!";
$L['adm_checkingcore'] = "Temel dosyalar kontrol ediliyor...";
$L['adm_checkskins'] = "Tüm dosyaların temalarda mevcut olup olmadığını kontrol et";
$L['adm_checkskin'] = "Tema için TPL dosyalarını kontrol et";
$L['adm_checkingskin'] = "Tema kontrol ediliyor...";
$L['adm_hits'] = "Ziyaretler";
$L['adm_check_ok'] = "Tamam";
$L['adm_check_missing'] = "Eksik";
$L['adm_ref_lowhits'] = "5'ten düşük ziyaret sayısına sahip öğeleri temizle";
$L['adm_maxhits'] = "Maksimum ziyaret sayısına ulaşıldı %1\$s, %2\$s sayfa bu gün görüntülendi.";
$L['adm_byyear'] = "Yıla göre";
$L['adm_bymonth'] = "Aya göre";
$L['adm_byweek'] = "Haftaya göre";

/* ====== Ratings ====== */

$L['adm_ratings_totalitems'] = "Toplam puanlanan sayfalar";
$L['adm_ratings_totalvotes'] = "Toplam oylar";
$L['adm_help_ratings'] = "Bir oyu sıfırlamak için, onu basitçe silin. İlk yeni oy ile yeniden oluşturulacaktır.";

/* ====== Trash can ====== */

$L['adm_help_trashcan'] = "Burada kullanıcılar ve moderatörler tarafından yeni silinen öğeler listelenmiştir.<br />Bir forum konusunu geri yüklemek, konuya ait tüm gönderileri de geri yükleyecektir.<br />Ve silinmiş bir konudaki bir gönderiyi geri yüklemek, tüm konuyu (mevcutsa) ve tüm alt gönderileri geri yükleyecektir.<br />&nbsp;<br />Sil : Öğeyi sonsuza kadar sil.<br />Geri Yükle : Öğeyi canlı veritabanına geri koy.";

/* ====== Users ====== */

$L['adm_defauth_members'] = "Üyeler için varsayılan haklar";
$L['adm_deflock_members'] = "Üyeler için kilit maskesi";
$L['adm_defauth_guests'] = "Misafirler için varsayılan haklar";
$L['adm_deflock_guests'] = "Misafirler için kilit maskesi";
$L['adm_rightspergroup'] = "Grup başına haklar";
$L['adm_copyrightsfrom'] = "Aynı hakları gruptan al";
$L['adm_maxsizesingle'] = "Tek bir dosya için PFS maksimum boyutu (KB)";
$L['adm_maxsizeallpfs'] = "Tüm PFS dosyalarının toplam maksimum boyutu (KB)";
$L['adm_rights_allow10'] = "İzin verildi";
$L['adm_rights_allow00'] = "Yasaklandı";
$L['adm_rights_allow11'] = "İzin verildi ve güvenlik nedeniyle kilitlendi";
$L['adm_rights_allow01'] = "Yasaklandı ve güvenlik nedeniyle kilitlendi";
$L['adm_color'] = "Grup rengi"; // Yeni v175

/* ====== Plugins ====== */

$L['adm_extplugins'] = "Genişletilmiş eklentiler";
$L['adm_present'] = "Mevcut";
$L['adm_missing'] = "Eksik";
$L['adm_paused'] = "Duraklatıldı";
$L['adm_running'] = "Çalışıyor";
$L['adm_partrunning'] = "Kısmen çalışıyor";
$L['adm_notinstalled'] = "Yüklenmedi";

$L['adm_opt_installall'] = "Tümünü yükle";
$L['adm_opt_installall_explain'] = "Bu, eklentinin tüm bölümlerini yükleyecek veya sıfırlayacaktır.";
$L['adm_opt_uninstallall'] = "Tümünü kaldır";
$L['adm_opt_uninstallall_explain'] = "Bu, eklentinin tüm bölümlerini devre dışı bırakacak, ancak dosyaları fiziksel olarak kaldırmayacaktır.";
$L['adm_opt_pauseall'] = "Tümünü duraklat";
$L['adm_opt_pauseall_explain'] = "Bu, eklentinin tüm bölümlerini duraklatacak (devre dışı bırakacak).";
$L['adm_opt_unpauseall'] = "Tümünü duraklatmayı kaldır";
$L['adm_opt_unpauseall_explain'] = "Bu, eklentinin tüm bölümlerinin duraklatılmasını kaldıracak (etkinleştirecek).";

/* ====== Private messages ====== */

$L['adm_pm_totaldb'] = "Veritabanındaki özel mesajlar";
$L['adm_pm_totalsent'] = "Şimdiye kadar gönderilen toplam özel mesaj sayısı";
