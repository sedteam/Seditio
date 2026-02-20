<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/pm/lang/pm.tr.lang.php
Version=185
Updated=2026-feb-14
Type=Module.lang
Author=Seditio Team
Description=PM Turkish language file
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$L['core_pm'] = "Özel mesajlar";

$L['cfg_pmtitle'] = array("Özel Mesaj başlığı", "Mevcut seçenekler: {MAINTITLE}, {SUBTITLE}, {TITLE}");
$L['cfg_pm_maxsize'] = array("Mesajlar için maksimum uzunluk", "Varsayılan: 10000 karakter");
$L['cfg_pm_allownotifications'] = array("E-posta ile PM bildirimlerine izin ver", "");

$L['pm_titletooshort'] = "Başlık çok kısa veya eksik";
$L['pm_bodytooshort'] = "Özel mesajın içeriği çok kısa veya eksik";
$L['pm_bodytoolong'] = "Özel mesajın içeriği çok uzun, maksimum " . (isset($cfg['pm_maxsize']) ? $cfg['pm_maxsize'] : 10000) . " karakter";
$L['pm_wrongname'] = "En az bir alıcı yanlış yazılmış ve listeden çıkarılmıştır";
$L['pm_toomanyrecipients'] = "Maksimum %1\$s alıcı lütfen";
$L['pmsend_title'] = "Yeni bir özel mesaj gönder";
$L['pmsend_subtitle'] = "";
$L['pm_sendnew'] = "Yeni bir özel mesaj gönder";
$L['pm_inbox'] = "Gelen kutusu";
$L['pm_inboxsubtitle'] = "Özel mesajlar, en yenisi üstte";
$L['pm_sentbox'] = "Gönderilen kutusu";
$L['pm_sentboxsubtitle'] = "Gönderilen ve alıcı tarafından henüz görüntülenmeyen mesajlar";
$L['pm_archives'] = "Arşivler";
$L['pm_arcsubtitle'] = "Eski mesajlar, en yenisi üstte";
$L['pm_replyto'] = "Bu kullanıcıya yanıt ver";
$L['pm_putinarchives'] = "Arşive taşı";
$L['pm_notifytitle'] = "Yeni özel mesaj";
$L['pm_notify'] = "Merhaba %1\$s,\n\nBu e-postayı alıyorsunuz çünkü gelen kutunuzda yeni bir özel mesaj var.\nGönderen: %2\$s\nMesajı okumak için bu bağlantıya tıklayın: %3\$s";
$L['pm_multiplerecipients'] = "Bu özel mesaj, %1\$s diğer alıcıya da gönderildi.";
