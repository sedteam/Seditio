<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/trashcan/lang/trashcan.tr.lang.php
Version=185
Type=Plugin
[END_SED]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$L['Trashcan'] = "Çöp kutusu";

$L['cfg_trash_prunedelay'] = array("Çöp kutasındaki öğeleri * gün sonra sil (Sonsuza kadar tutmak için sıfır)", "");
$L['cfg_trash_comment'] = array("Yorumlar için çöp kutusunu kullan", "");
$L['cfg_trash_forum'] = array("Forumlar için çöp kutusu kullan", "");
$L['cfg_trash_page'] = array("Sayfalar için çöp kutusunu kullan", "");
$L['cfg_trash_pm'] = array("Özel mesajlar için çöp kutusunu kullan", "");
$L['cfg_trash_user'] = array("Kullanıcılar için çöp kutusunu kullan", "");

$L['adm_help_trashcan'] = "Burada kullanıcılar ve moderatörler tarafından yeni silinen öğeler listelenmiştir.<br />Bir forum konusunu geri yüklemek, konuya ait tüm gönderileri de geri yükleyecektir.<br />Ve silinmiş bir konudaki bir gönderiyi geri yüklemek, tüm konuyu (mevcutsa) ve tüm alt gönderileri geri yükleyecektir.<br />&nbsp;<br />Sil : Öğeyi sonsuza kadar sil.<br />Geri Yükle : Öğeyi canlı veritabanına geri koy.";
