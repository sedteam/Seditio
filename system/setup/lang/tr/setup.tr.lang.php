<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=system/setup/lang/tr/setup.tr.lang.php
Version=186
Updated=2026-jul-20
Type=Core.setup
Author=Seditio Team
Description=Turkish setup language file
[END_SED]
==================== */

// Language name
$L['lang_name'] = "Türkçe";

// Steps
$L['setup_step1'] = "Hoş Geldiniz";
$L['setup_step2'] = "Kontroller";
$L['setup_step3'] = "Veritabanı";
$L['setup_step4'] = "Ayarlar";
$L['setup_step5'] = "Eklentiler";
$L['setup_step6'] = "Kurulum";

// Navigation
$L['setup_next_step'] = "İleri";
$L['setup_prev_step'] = "Geri";

// Welcome
$L['setup_welcome_title'] = "Seditio'ya Hoş Geldiniz";
$L['setup_welcome_desc'] = "Bu kurulum sihirbazı, Seditio CMS'yi birkaç basit adımda sıfırdan kurmanıza ve yapılandırmanıza yardımcı olacaktır.";
$L['setup_select_language'] = "Kurulum dilini seçin";

// System Check
$L['setup_system_check_title'] = "Uyumluluk Kontrolü";
$L['setup_system_check_desc'] = "Motorun doğru çalışmasını sağlamak için sunucu yapılandırmalarınızı ve klasör izinlerinizi kontrol ediyoruz.";
$L['setup_checking'] = "Kontrol ediliyor";
$L['setup_php_version'] = "PHP Sürümü";
$L['setup_php_min_74'] = "PHP 5.6 veya üzeri gereklidir";
$L['setup_available'] = "Mevcut";
$L['setup_missing'] = "Mevcut değil";
$L['setup_folder'] = "Klasör";
$L['setup_writable'] = "Yazılabilir";
$L['setup_not_writable'] = "Yazılamaz";
$L['setup_not_found'] = "Bulunamadı";
$L['setup_found_writable'] = "Bulundu ve yazılabilir";
$L['setup_found_notwritable'] = "Bulundu, ancak yazmaya karşı korumalı";
$L['setup_notfound_folderwritable'] = "Bulunamadı, ancak klasör yazılabilir";
$L['setup_notfound_foldernotwritable'] = "Bulunamadı ve klasör yazmaya karşı korumalı";

// DB
$L['setup_db_title'] = "Veritabanı Bağlantısı";
$L['setup_db_desc'] = "MySQL sunucunuz için bağlantı parametrelerini belirtin.";
$L['setup_db_host'] = "VT Sunucusu";
$L['setup_db_host_hint'] = "Neredeyse her zaman 'localhost'";
$L['setup_db_name'] = "Veritabanı Adı";
$L['setup_db_user'] = "VT Kullanıcısı";
$L['setup_db_password'] = "VT Parolası";
$L['setup_db_prefix'] = "Tablo Öneki";
$L['setup_db_prefix_hint'] = "Genellikle 'sed_' (emin değilseniz değiştirmeyin)";
$L['setup_db_clear'] = "İçe aktarmadan önce veritabanını temizle";
$L['setup_db_clear_hint'] = "Uyarı: Belirtilen veritabanındaki tüm tablolar silinecektir!";
$L['setup_test_connection'] = "Bağlantıyı Test Et";
$L['setup_testing'] = "Test ediliyor...";
$L['setup_db_connected'] = "Bağlantı kuruldu! MySQL sürümü: %s";
$L['setup_check_credentials'] = "Bağlantı başarısız oldu. Lütfen bilgilerinizi kontrol edin.";

// Settings
$L['setup_settings_title'] = "Site Ayarları";
$L['setup_default_skin'] = "Varsayılan Tema";
$L['setup_default_lang'] = "Varsayılan Dil";
$L['setup_admin_account'] = "Süper Yönetici Hesabı";
$L['setup_admin_name'] = "Kullanıcı Adı";
$L['setup_admin_pass'] = "Parola";
$L['setup_admin_email'] = "E-posta Adresi";
$L['setup_admin_country'] = "Ülke";
$L['setup_generate_password'] = "Oluştur";
$L['setup_password_copied'] = "Parola panoya kopyalandı!";
$L['setup_password_min'] = "En az 8 karakter";
$L['setup_ownaccount_name'] = "Sistemdeki kullanıcı adınız";
$L['setup_least8chars'] = "En az 8 karakter";
$L['setup_doublecheck'] = "Tekrar kontrol edin, bu önemlidir!";

// Extensions
$L['setup_extensions_title'] = "Modül ve Eklenti Seçimi";
$L['setup_tab_modules'] = "Modüller";
$L['setup_tab_plugins'] = "Eklentiler";
$L['setup_locked_module'] = "Gerekli";
$L['setup_select_all'] = "Tümünü seç";
$L['setup_deselect_all'] = "Tümünün seçimini kaldır";
$L['setup_optional_modules'] = "Web siteniz için modülleri seçin:";
$L['setup_optional_plugins'] = "Ek eklentileri seçin:";
$L['setup_no_modules'] = "/modules/ dizininde modül bulunamadı.";
$L['setup_no_plugins'] = "/plugins/ dizininde eklenti bulunamadı.";

// Installation
$L['setup_install'] = "Kur";
$L['setup_installing_title'] = "Kurulum Süreci";
$L['setup_connected_to_db'] = "Veritabanına bağlanılıyor";
$L['setup_config_created'] = "datas/config.php yapılandırma dosyası yazılıyor";
$L['setup_tables_created'] = "Veritabanı tabloları oluşturuluyor";
$L['setup_config_loaded'] = "Varsayılan yapılandırmalar içe aktarılıyor";
$L['setup_admin_created'] = "Yönetici hesabı oluşturuluyor";
$L['setup_complete'] = "Kurulum başarıyla tamamlandı";
$L['setup_success_title'] = "Seditio başarıyla kuruldu!";
$L['setup_success_desc'] = "Web siteniz tamamen hazır. Güvenlik nedeniyle lütfen kurulum dosyalarını silin!";
$L['setup_go_home'] = "Ana Sayfaya Git";
$L['setup_go_admin'] = "Yönetim Paneli";

// Errors
$L['setup_error_db_connection'] = "Veritabanına bağlanılamıyor.";
$L['setup_error_config_write'] = "Hata: datas/config.php dosyasına yazılamadı. İzinleri kontrol edin.";
$L['setup_error_field_required'] = "Bu alan zorunludur.";
$L['setup_error_notwrite'] = "Hata, yazılamıyor, lütfen CHMOD izinlerini kontrol edin.";
$L['setup_wrong_manual'] = "Bir şeyler yanlış gitti. Detaylı manuel kurulum kılavuzuna <a href=\"https://seditio.org/doc/\" target=\"_blank\">buradan</a> ulaşabilirsiniz.";
$L['setup_title'] = "Seditio — Kurulum";
$L['setup_database_cleared'] = "Veritabanı başarıyla temizlendi";
$L['setup_tables_dropped'] = "tablo silindi";
$L['setup_config_size'] = "Yapılandırma dosyası boyutu: %s bayt.";
$L['setup_plugin_skipped'] = "atlandı (gerekli %s modülü kurulu değil)";
