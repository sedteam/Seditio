<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/forums/lang/forums.tr.lang.php
Version=185
Updated=2026-feb-14
Type=Module.lang
Author=Seditio Team
Description=Forums Turkish language file
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

/* ====== Forums ======= */

$L['Forums'] = "Forumlar";
$L['Topics'] = "Konular";
$L['Posts'] = "Mesajlar";
$L['Post'] = "Mesaj";
$L['Topic'] = "Konu";
$L['Replies'] = "Yanıtlar";
$L['Lastpost'] = "Son mesaj";
$L['Started'] = "Başlatıldı";
$L['Topicoptions'] = "Konu seçenekleri";
$L['Topiclocked'] = "Bu konu kilitli, yeni mesaj yazılamaz.";
$L['Announcement'] = "Duyuru";
$L['Bump'] = "Yukarı taşı";
$L['Ghost'] = "Taşındı bildirimi bırak";
$L['Lock'] = "Kilitle";
$L['Makesticky'] = "Sabit yap";
$L['Moved'] = "Taşındı";
$L['Poll'] = "Anket";
$L['Private'] = "Özel";

$L['for_newtopic'] = "Yeni konu";
$L['for_markallasread'] = "Tüm mesajları okundu işaretle";
$L['for_titletooshort'] = "Başlık çok kısa veya eksik";
$L['for_msgtooshort'] = "Konu metni çok kısa veya eksik";
$L['for_updatedby'] = "<br /><em>Bu mesaj %1\$s tarafından düzenlendi (%2\$s, %3\$s önce)</em>";
$L['for_antibump'] = "Ardışık mesaj koruması aktif, art arda iki mesaj yazamazsınız.";
$L['for_mod_clear'] = "Oylamaları temizle";
$L['for_mod_force'] = "Oylamayı şu değere zorla: ";
$L['for_quickpost'] = "Hızlı yanıt";
$L['for_post_text'] = "Mesaj metni";

/* ====== Forums admin ======= */

$L['adm_forums'] = "Forumlar";
$L['adm_forums_main'] = "Forum yapısı";
$L['adm_autoprune'] = "Konuları * gün sonra otomatik sil";
$L['adm_postcounters'] = "Sayaçları kontrol et";
$L['adm_help_forums'] = "Mevcut değil";
$L['adm_forum_structure'] = "Forum yapısı (kategoriler)";
$L['adm_forum_structure_cat'] = "Forum yapısı";
$L['adm_help_forums_structure'] = "Mevcut değil";

/* ====== Config labels ======= */

$L['core_forums'] = "Forumlar";
$L['cfg_formatmonthdayhourmin'] = array("Forum tarih biçimi", "Varsayılan: m-d H:i");
$L['cfg_hideprivateforums'] = array("Özel forumları gizle", "");
$L['cfg_hottopictrigger'] = array("Konunun 'popüler' sayılması için mesaj sayısı", "");
$L['cfg_maxtopicsperpage'] = array("Sayfa başına en fazla konu veya mesaj", "");
$L['cfg_antibumpforums'] = array("Ardışık mesaj koruması", "Aynı konuda art arda iki mesaj yazılmasını engeller");
$L['cfg_disable_forums'] = array("Forumları devre dışı bırak", "");
$L['cfg_trash_forum'] = array("Forumlar için çöp kutusu kullan", "");
$L['cfg_forumstitle'] = array("Forum başlığı", "Mevcut seçenekler: {MAINTITLE}, {SUBTITLE}, {TITLE}");
$L['cfg_disable_rssforums'] = array("Forum RSS beslemesini devre dışı bırak", "");
