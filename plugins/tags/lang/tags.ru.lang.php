<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/tags/lang/tags.ru.lang.php
Version=185
Type=Plugin
[END_SED]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$L['tags_tags'] = 'Теги';
$L['tags_title'] = 'Теги';
$L['tags_cloud'] = 'Облако тегов';
$L['tags_search'] = 'Поиск по тегам';
$L['tags_search_hint'] = 'Введите тег, запятая — И, точка с запятой — ИЛИ';
$L['tags_input_hint'] = 'Через запятую';
$L['tags_results'] = 'Результаты поиска по тегу';
$L['tags_results_pages'] = 'Страницы';
$L['tags_results_forums'] = 'Темы форума';
$L['tags_noresults'] = 'По данному тегу ничего не найдено';
$L['tags_alltags'] = 'Все теги';
$L['tags_area'] = 'Область';
$L['tags_area_all'] = 'Все';
$L['tags_area_pages'] = 'Страницы';
$L['tags_area_forums'] = 'Форум';
$L['tags_count'] = 'Количество';
$L['tags_delete'] = 'Удалить';
$L['tags_rename'] = 'Переименовать';
$L['tags_admin_title'] = 'Управление тегами';
$L['tags_admin_cleanup'] = 'Очистить осиротевшие';
$L['tags_admin_cleanup_done'] = 'Удалено осиротевших связей: %d';
$L['tags_admin_deleted'] = 'Тег удалён';
$L['tags_admin_renamed'] = 'Тег переименован';
$L['tags_admin_rename_exists'] = 'Целевой тег уже существует';
$L['tags_admin_newtag'] = 'Новое имя тега';
$L['tags_admin_update'] = 'Обновить';
$L['tags_filter'] = 'Фильтр';
$L['tags_filter_all'] = 'Все';
$L['tags_total'] = 'Всего';
$L['tags_filter_letters'] = 'ABCDEFGHIJKLMNOPQRSTUVWXYZАБВГДЕЖЗИКЛМНОПРСТУФХЦЧШЩЭЮЯ';

/* Config */
$L['cfg_pages'] = array("Теги для страниц", "");
$L['cfg_forums'] = array("Теги для тем форума", "");
$L['cfg_title'] = array("Отображать теги с заглавной буквы", "");
$L['cfg_order'] = array("Сортировка облака тегов", "");
$L['cfg_limit'] = array("Макс. тегов на объект (0 = без ограничений)", "");
$L['cfg_lim_pages'] = array("Лимит облака в листингах страниц (0 = без ограничений)", "");
$L['cfg_lim_forums'] = array("Лимит облака в форумах (0 = без ограничений)", "");
$L['cfg_lim_index'] = array("Лимит облака на главной (0 = без ограничений)", "");
$L['cfg_more'] = array("Показывать ссылку «Все теги» при ограниченном облаке", "");
$L['cfg_perpage'] = array("Тегов на страницу в облаке (0 = без ограничений)", "");
$L['cfg_index'] = array("Область облака на главной", "");
$L['cfg_noindex'] = array("Добавлять meta noindex в поиск по тегам", "");
$L['cfg_sort'] = array("Сортировка результатов поиска", "");
$L['cfg_css'] = array("Подключать CSS плагина", "");
$L['cfg_autocomplete_minlen'] = array("Минимум символов для автодополнения", "");
$L['cfg_maxrowsperpage'] = array("Макс. строк в списке тегов", "");
$L['cfg_cloud_index_on'] = array("Показывать облако тегов на главной", "");
$L['cfg_cloud_list_on'] = array("Показывать облако тегов в листингах", "");
$L['cfg_cloud_page_on'] = array("Показывать облако тегов на странице", "");
$L['cfg_tagstitle'] = array("Маска заголовка страницы", "Доступные опции: {MAINTITLE}, {SUBTITLE}, {TITLE}");
$L['cfg_list_separator'] = array("Разделитель между тегами в списке", "напр. пробел, запятая, точка");
$L['cfg_cloud_forums_on'] = array("Показывать облако тегов на главной форума", "");
