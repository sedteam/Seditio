<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/tags/lang/tags.tr.lang.php
Version=185
Type=Plugin
[END_SED]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$L['tags_tags'] = 'Etiketler';
$L['tags_title'] = 'Etiketler';
$L['tags_cloud'] = 'Etiket bulutu';
$L['tags_search'] = 'Etiketlere göre ara';
$L['tags_search_hint'] = 'Etiket girin, virgül — VE, noktalı virgül — VEYA';
$L['tags_input_hint'] = 'Virgülle ayırın';
$L['tags_results'] = 'Etiket arama sonuçları';
$L['tags_results_pages'] = 'Sayfalar';
$L['tags_results_forums'] = 'Forum konuları';
$L['tags_noresults'] = 'Bu etiket için sonuç bulunamadı';
$L['tags_alltags'] = 'Tüm etiketler';
$L['tags_area'] = 'Alan';
$L['tags_area_all'] = 'Tümü';
$L['tags_area_pages'] = 'Sayfalar';
$L['tags_area_forums'] = 'Forum';
$L['tags_count'] = 'Sayı';
$L['tags_delete'] = 'Sil';
$L['tags_rename'] = 'Yeniden adlandır';
$L['tags_admin_title'] = 'Etiket yönetimi';
$L['tags_admin_cleanup'] = 'Yetim kayıtları temizle';
$L['tags_admin_cleanup_done'] = '%d yetim referans temizlendi';
$L['tags_admin_deleted'] = 'Etiket silindi';
$L['tags_admin_renamed'] = 'Etiket yeniden adlandırıldı';
$L['tags_admin_rename_exists'] = 'Hedef etiket zaten mevcut';
$L['tags_admin_newtag'] = 'Yeni etiket adı';
$L['tags_admin_update'] = 'Güncelle';
$L['tags_filter'] = 'Filtre';
$L['tags_filter_all'] = 'Tümü';
$L['tags_total'] = 'Toplam';
$L['tags_filter_letters'] = 'ABCÇDEFGĞHIİJKLMNOÖPRSŞTUÜVYZ';

/* Config */
$L['cfg_pages'] = array("Sayfalar için etiketler", "");
$L['cfg_forums'] = array("Forum konuları için etiketler", "");
$L['cfg_title'] = array("Etiketleri büyük harfle göster", "");
$L['cfg_order'] = array("Etiket bulutu sıralama düzeni", "");
$L['cfg_limit'] = array("Öğe başına maks. etiket (0 = sınırsız)", "");
$L['cfg_lim_pages'] = array("Sayfa listelerinde bulut limiti (0 = sınırsız)", "");
$L['cfg_lim_forums'] = array("Forumlarda bulut limiti (0 = sınırsız)", "");
$L['cfg_lim_index'] = array("Ana sayfada bulut limiti (0 = sınırsız)", "");
$L['cfg_more'] = array("Bulut sınırlı olduğunda 'Tüm etiketler' bağlantısını göster", "");
$L['cfg_perpage'] = array("Bulutta sayfa başına etiket (0 = sınırsız)", "");
$L['cfg_index'] = array("Ana sayfa bulut alanı", "");
$L['cfg_noindex'] = array("Etiket aramasına meta noindex ekle", "");
$L['cfg_sort'] = array("Arama sonuçları sıralaması", "");
$L['cfg_css'] = array("Eklenti CSS'sini dahil et", "");
$L['cfg_autocomplete_minlen'] = array("Otomatik tamamlama için minimum karakter", "");
$L['cfg_maxrowsperpage'] = array("Maximum lines in tags", "");
$L['cfg_cloud_index_on'] = array("Show tags cloud on index", "");
$L['cfg_cloud_list_on'] = array("Show tags cloud in page list", "");
$L['cfg_cloud_page_on'] = array("Show tags cloud on page view", "");
$L['cfg_tagstitle'] = array("Page title mask", "Available options: {MAINTITLE}, {SUBTITLE}, {TITLE}");
$L['cfg_list_separator'] = array("Listede etiketler arası ayırıcı", "örn. boşluk, virgül, nokta");
$L['cfg_cloud_forums_on'] = array("Forum ana sayfasında etiket bulutunu göster", "");
