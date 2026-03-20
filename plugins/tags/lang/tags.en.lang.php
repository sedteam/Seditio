<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/tags/lang/tags.en.lang.php
Version=185
Type=Plugin
[END_SED]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$L['tags_tags'] = 'Tags';
$L['tags_title'] = 'Tags';
$L['tags_cloud'] = 'Tag cloud';
$L['tags_search'] = 'Search by tags';
$L['tags_search_hint'] = 'Enter tag or use comma for AND, semicolon for OR';
$L['tags_input_hint'] = 'Separate with commas';
$L['tags_results'] = 'Search results for tag';
$L['tags_results_pages'] = 'Pages';
$L['tags_results_forums'] = 'Forum topics';
$L['tags_noresults'] = 'No results found for this tag';
$L['tags_alltags'] = 'All tags';
$L['tags_area'] = 'Area';
$L['tags_area_all'] = 'All';
$L['tags_area_pages'] = 'Pages';
$L['tags_area_forums'] = 'Forums';
$L['tags_count'] = 'Count';
$L['tags_delete'] = 'Delete';
$L['tags_rename'] = 'Rename';
$L['tags_admin_title'] = 'Tags management';
$L['tags_admin_cleanup'] = 'Cleanup orphaned';
$L['tags_admin_cleanup_done'] = 'Cleaned up %d orphaned references';
$L['tags_admin_deleted'] = 'Tag deleted';
$L['tags_admin_renamed'] = 'Tag renamed';
$L['tags_admin_rename_exists'] = 'Target tag already exists';
$L['tags_admin_newtag'] = 'New tag name';
$L['tags_admin_update'] = 'Update';
$L['tags_filter'] = 'Filter';
$L['tags_filter_all'] = 'All';
$L['tags_total'] = 'Total';
$L['tags_filter_letters'] = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

/* Config */
$L['cfg_pages'] = array("Enable tags for pages", "");
$L['cfg_forums'] = array("Enable tags for forum topics", "");
$L['cfg_title'] = array("Display tags in Title Case", "");
$L['cfg_order'] = array("Tag cloud sort order", "");
$L['cfg_limit'] = array("Max tags per item (0 = unlimited)", "");
$L['cfg_lim_pages'] = array("Cloud limit in page listings (0 = unlimited)", "");
$L['cfg_lim_forums'] = array("Cloud limit in forums (0 = unlimited)", "");
$L['cfg_lim_index'] = array("Cloud limit on index page (0 = unlimited)", "");
$L['cfg_more'] = array("Show 'All tags' link when cloud is limited", "");
$L['cfg_perpage'] = array("Tags per page in standalone cloud (0 = unlimited)", "");
$L['cfg_index'] = array("Index cloud area", "");
$L['cfg_noindex'] = array("Add meta noindex to standalone tag search", "");
$L['cfg_sort'] = array("Search results sort", "");
$L['cfg_css'] = array("Include plugin CSS", "");
$L['cfg_autocomplete_minlen'] = array("Minimum characters for autocomplete", "");
$L['cfg_maxrowsperpage'] = array("Maximum lines in tags", "");
$L['cfg_cloud_index_on'] = array("Show tag cloud on index page", "");
$L['cfg_cloud_list_on'] = array("Show tag cloud in page listings", "");
$L['cfg_cloud_page_on'] = array("Show tag cloud on page view", "");
$L['cfg_tagstitle'] = array("Page title mask", "Available options: {MAINTITLE}, {SUBTITLE}, {TITLE}");
$L['cfg_list_separator'] = array("Separator between tags in list", "e.g. space, comma, dot");
$L['cfg_cloud_forums_on'] = array("Show tag cloud on forum main page", "");
