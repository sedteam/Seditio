<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/thanks/lang/thanks.tr.lang.php
Version=185
Type=Plugin
[END_SED]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$L['thanks_thanks'] = '<i class="ic-thumb-up"></i> Teşekkürler!';
$L['thanks_thanks_title'] = 'Teşekkürler!';
$L['thanks_done'] = 'Yazara teşekkür ettiniz';
$L['thanks_title'] = 'Kullanıcılara teşekkürler';
$L['thanks_title_short'] = 'Teşekkürler';
$L['thanks_title_user'] = 'Kullanıcının aldığı teşekkürler';
$L['thanks_err_maxday'] = 'Üzgünüz, bugün daha fazla teşekkür edemezsiniz';
$L['thanks_err_maxuser'] = 'Üzgünüz, bu kullanıcıya bugün tekrar teşekkür edemezsiniz';
$L['thanks_err_item'] = 'Üzgünüz, bu öğe için zaten teşekkür ettiniz';
$L['thanks_err_self'] = 'Kendinize teşekkür edemezsiniz';
$L['thanks_none'] = 'Teşekkür yok';
$L['thanks_users_none'] = 'Teşekkür alan kullanıcı yok';
$L['thanks_date'] = 'Tarih';
$L['thanks_from'] = 'Gönderen';
$L['thanks_to'] = 'Alıcı';
$L['thanks_item'] = 'Öğe';
$L['thanks_category'] = 'Kategori';
$L['thanks_for'] = 'Ne için';
$L['thanks_type_page'] = 'Sayfa';
$L['thanks_type_post'] = 'Gönderi';
$L['thanks_type_comment'] = 'Yorum';
$L['thanks_total'] = 'Toplam';
$L['thanks_thanked'] = 'Teşekkür edenler';
$L['thanks_thanked_times'] = 'Teşekkür: %s kez';
$L['thanks_etc'] = '...';
$L['thanks_notify_pm_subject'] = 'Teşekkür aldınız';
$L['thanks_notify_pm_body'] = "Kullanıcı %1\$s size teşekkür etti.\nDetaylar: %2\$s";
$L['thanks_notify_email_subject'] = 'Teşekkür aldınız';
$L['thanks_notify_email_body'] = "Merhaba %1\$s,\n\nKullanıcı %2\$s size teşekkür etti.\nDetaylar: %3\$s";

/* Config */
$L['cfg_maxday'] = array("Kullanıcının günde verebileceği maksimum teşekkür sayısı", "");
$L['cfg_maxuser'] = array("Bir kullanıcıya günde maksimum teşekkür sayısı", "");
$L['cfg_maxthanked'] = array("Kaç teşekkür eden gösterilsin (0=hepsi)", "");
$L['cfg_page_on'] = array("Sayfalar için teşekkürler", "");
$L['cfg_forums_on'] = array("Forum gönderileri için teşekkürler", "");
$L['cfg_comments_on'] = array("Yorumlar için teşekkürler", "");
$L['cfg_short'] = array("Kısa format (sadece isimler)", "");
$L['cfg_notify_by_pm'] = array("Yeni teşekkürde ÖM ile bildir", "");
$L['cfg_notify_by_email'] = array("Yeni teşekkürde e-posta ile bildir", "");
$L['cfg_notify_from'] = array("Bildirimler için e-posta gönderici adresi", "");
$L['cfg_thanksperpage'] = array("Listelerde sayfa başına teşekkür sayısı", "");
$L['cfg_format'] = array("Tarih format maskesi (boş = sistem varsayılanı)", "");
$L['cfg_css'] = array("Eklenti CSS'sini dahil et", "");
