<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/tags/inc/tags.functions.php
Version=185
Type=Plugin
[END_SED]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

/* ============ HTML MASKS (edit here to customize output) ============
 * cloud_wrapper: %1$s = inner links
 * cloud_link: %1$s = url, %2$s = level, %3$s = count, %4$s = display text
 * list_wrapper: %1$s = full content (label + links or links only)
 * list_link: %1$s = url, %2$s = display text
 * more_block: %1$s = url, %2$s = label
 */
$sed_tag_masks = array(
	'cloud_wrapper' => '<div class="tags-cloud">%1$s</div>',
	'cloud_link'    => '<a href="%1$s" class="tag-%2$s" title="%3$s">%4$s</a> ',
	'list_wrapper'  => '<span class="tags-list">%1$s</span>',
	'list_link'     => '<a href="%1$s" rel="tag">%2$s</a>',
	'more_block'    => '<div class="tags-more"><a href="%1$s">%2$s</a></div>',
);

/**
 * Normalizes a single tag string.
 * Lowercases, strips dangerous chars, collapses whitespace, trims.
 */
function sed_tag_normalize($tag)
{
	$tag = mb_strtolower($tag, 'UTF-8');
	$tag = preg_replace('/[\^\:\?\=\&\|\\\\\/"\\t\\r\\n\-]+/', ' ', $tag);
	$tag = preg_replace('/\s+/', ' ', $tag);
	$tag = trim($tag);
	return $tag;
}

/**
 * Makes value safe for string-based sed_url query assembly.
 * Keeps legacy style (string params) and avoids query split by "&" / "=".
 */
function sed_tag_qs_value($value)
{
	$value = str_replace(array('&', '='), ' ', (string)$value);
	$value = preg_replace('/\s+/', ' ', trim($value));
	return $value;
}

/**
 * Parses a comma-separated tag string into an array of normalized unique tags.
 *
 * @param string $input Raw input "tag1, tag2, tag3"
 * @return array Array of unique normalized tags
 */
function sed_tag_parse($input)
{
	global $cfg;

	if (empty($input)) {
		return array();
	}

	$parts = explode(',', $input);
	$tags = array();

	foreach ($parts as $part) {
		$tag = sed_tag_normalize($part);
		if ($tag !== '') {
			$tags[] = $tag;
		}
	}

	$tags = array_unique($tags);

	$limit = isset($cfg['plugin']['tags']['limit']) ? (int)$cfg['plugin']['tags']['limit'] : 0;
	if ($limit > 0 && count($tags) > $limit) {
		$tags = array_slice($tags, 0, $limit);
	}

	return array_values($tags);
}

/**
 * Registers a tag in the dictionary (INSERT IGNORE).
 */
function sed_tag_register($tag)
{
	global $db_tags;
	$tag = sed_tag_normalize($tag);
	if ($tag === '') return false;
	sed_sql_query("INSERT IGNORE INTO $db_tags (tag) VALUES ('" . sed_sql_prep($tag) . "')");
	return true;
}

/**
 * Removes a tag from the dictionary and all its references.
 */
function sed_tag_unregister($tag)
{
	global $db_tags, $db_tag_references;
	$tag = sed_tag_normalize($tag);
	if ($tag === '') return false;
	sed_sql_query("DELETE FROM $db_tag_references WHERE tag = '" . sed_sql_prep($tag) . "'");
	sed_sql_query("DELETE FROM $db_tags WHERE tag = '" . sed_sql_prep($tag) . "'");
	return true;
}

/**
 * Checks if a tag exists in the dictionary.
 */
function sed_tag_exists($tag)
{
	global $db_tags;
	$tag = sed_tag_normalize($tag);
	if ($tag === '') return false;
	$res = sed_sql_query("SELECT 1 FROM $db_tags WHERE tag = '" . sed_sql_prep($tag) . "' LIMIT 1");
	return (sed_sql_numrows($res) > 0);
}

/**
 * Adds a tag reference (tag <-> item).
 * Automatically registers the tag in the dictionary.
 *
 * @param string $tag  Normalized tag
 * @param int    $item Item ID (page_id or ft_id)
 * @param string $area 'pages' or 'forums'
 * @return bool
 */
function sed_tag($tag, $item, $area = 'pages')
{
	global $db_tags, $db_tag_references;

	$tag = sed_tag_normalize($tag);
	$item = (int)$item;
	$area = sed_sql_prep($area);
	if ($tag === '' || $item <= 0) return false;

	sed_sql_query("INSERT IGNORE INTO $db_tags (tag) VALUES ('" . sed_sql_prep($tag) . "')");
	sed_sql_query("INSERT IGNORE INTO $db_tag_references (tag, tag_item, tag_area)
		VALUES ('" . sed_sql_prep($tag) . "', $item, '$area')");
	return true;
}

/**
 * Removes a single tag reference.
 */
function sed_tag_remove($tag, $item, $area = 'pages')
{
	global $db_tag_references;
	$tag = sed_tag_normalize($tag);
	$item = (int)$item;
	if ($tag === '' || $item <= 0) return false;
	sed_sql_query("DELETE FROM $db_tag_references
		WHERE tag = '" . sed_sql_prep($tag) . "' AND tag_item = $item AND tag_area = '" . sed_sql_prep($area) . "'");
	return true;
}

/**
 * Removes all tag references for a given item (and area), or all references in an area.
 *
 * @param int    $item Item ID (0 = all items in area)
 * @param string $area Area code
 * @return int Number of deleted rows
 */
function sed_tag_remove_all($item = 0, $area = 'pages')
{
	global $db_tag_references;
	$item = (int)$item;

	if ($item > 0) {
		sed_sql_query("DELETE FROM $db_tag_references
			WHERE tag_item = $item AND tag_area = '" . sed_sql_prep($area) . "'");
	} else {
		sed_sql_query("DELETE FROM $db_tag_references
			WHERE tag_area = '" . sed_sql_prep($area) . "'");
	}
	return sed_sql_affectedrows();
}

/**
 * Gets tags for one item or a batch of items.
 *
 * @param int|array $items Single item ID or array of IDs
 * @param string    $area  Area code
 * @return array If single ID: flat array of tags. If array: assoc [item_id => [tags]]
 */
function sed_tag_list($items, $area = 'pages')
{
	global $db_tag_references;

	$is_batch = is_array($items);

	if ($is_batch) {
		if (empty($items)) return array();
		$ids = array_map('intval', $items);
		$in = implode(',', $ids);
		$sql = sed_sql_query("SELECT tag, tag_item FROM $db_tag_references
			WHERE tag_item IN ($in) AND tag_area = '" . sed_sql_prep($area) . "'
			ORDER BY tag ASC");

		$result = array();
		foreach ($ids as $id) {
			$result[$id] = array();
		}
		while ($row = sed_sql_fetchassoc($sql)) {
			$result[(int)$row['tag_item']][] = $row['tag'];
		}
		return $result;
	}

	$item = (int)$items;
	if ($item <= 0) return array();

	$sql = sed_sql_query("SELECT tag FROM $db_tag_references
		WHERE tag_item = $item AND tag_area = '" . sed_sql_prep($area) . "'
		ORDER BY tag ASC");

	$tags = array();
	while ($row = sed_sql_fetchassoc($sql)) {
		$tags[] = $row['tag'];
	}
	return $tags;
}

/**
 * Returns tag cloud data: array of ['tag' => ..., 'cnt' => ..., 'level' => xs|s|m|l|xl].
 *
 * @param string $area  'pages', 'forums', 'all'
 * @param string $order 'Alphabetical', 'Frequency', 'Random'
 * @param int    $limit Max tags (0 = unlimited)
 * @return array
 */
function sed_tag_cloud($area = 'all', $order = 'Alphabetical', $limit = 0)
{
	global $db_tags, $db_tag_references;

	$where = '';
	if ($area !== 'all' && $area !== '') {
		$where = " WHERE r.tag_area = '" . sed_sql_prep($area) . "'";
	}

	$order_sql = 'ORDER BY t.tag ASC';
	if ($order === 'Frequency') {
		$order_sql = 'ORDER BY cnt DESC, t.tag ASC';
	} elseif ($order === 'Random') {
		$order_sql = 'ORDER BY RAND()';
	}

	$limit_sql = ($limit > 0) ? "LIMIT " . (int)$limit : '';

	$sql = sed_sql_query("SELECT t.tag, COUNT(r.tag_item) AS cnt
		FROM $db_tags AS t
		LEFT JOIN $db_tag_references AS r ON r.tag = t.tag $where
		GROUP BY t.tag
		HAVING cnt > 0
		$order_sql
		$limit_sql");

	$cloud = array();
	$max_cnt = 0;
	$min_cnt = PHP_INT_MAX;

	while ($row = sed_sql_fetchassoc($sql)) {
		$row['cnt'] = (int)$row['cnt'];
		$cloud[] = $row;
		if ($row['cnt'] > $max_cnt) $max_cnt = $row['cnt'];
		if ($row['cnt'] < $min_cnt) $min_cnt = $row['cnt'];
	}

	if (empty($cloud)) return array();

	$range = max($max_cnt - $min_cnt, 1);
	$levels = array('xs', 's', 'm', 'l', 'xl');

	foreach ($cloud as &$item) {
		$ratio = ($item['cnt'] - $min_cnt) / $range;
		$idx = min((int)floor($ratio * 5), 4);
		$item['level'] = $levels[$idx];
	}
	unset($item);

	if ($order === 'Frequency') {
		usort($cloud, function ($a, $b) {
			return strcmp($a['tag'], $b['tag']);
		});
	}

	return $cloud;
}

/**
 * Autocomplete: finds tags starting with a given prefix.
 *
 * @param string $prefix Search prefix
 * @param int    $limit  Max results
 * @return array|false Array of tag strings, or false if prefix too short
 */
function sed_tag_complete($prefix, $limit = 20)
{
	global $db_tags, $cfg;

	$min_len = isset($cfg['plugin']['tags']['autocomplete_minlen']) ? (int)$cfg['plugin']['tags']['autocomplete_minlen'] : 3;
	$prefix = sed_tag_normalize($prefix);

	if (mb_strlen($prefix) < $min_len) {
		return false;
	}

	$sql = sed_sql_query("SELECT tag FROM $db_tags
		WHERE tag LIKE '" . sed_sql_prep($prefix) . "%'
		ORDER BY tag ASC
		LIMIT " . (int)$limit);

	$result = array();
	while ($row = sed_sql_fetchassoc($sql)) {
		$result[] = $row['tag'];
	}
	return $result;
}

/**
 * Converts tag to Title Case for display (if title=1 in settings).
 */
function sed_tag_title($tag)
{
	global $cfg;
	if (!empty($cfg['plugin']['tags']['title'])) {
		return mb_convert_case($tag, MB_CASE_TITLE, 'UTF-8');
	}
	return $tag;
}

/**
 * Builds SQL WHERE condition for tag search query.
 * Supports: comma = AND, semicolon = OR, asterisk = wildcard.
 *
 * @param string $qs         Query string from user
 * @param string $join_col   Column to match (e.g., "r.tag_item")
 * @param string $area       Area filter (or 'all')
 * @return string SQL WHERE clause (without leading WHERE)
 */
function sed_tag_parse_query($qs, $join_col = 'r.tag_item', $area = 'all')
{
	global $db_tag_references;

	$qs = trim($qs);
	if ($qs === '') return '0';

	$or_groups = explode(';', $qs);
	$or_conditions = array();

	foreach ($or_groups as $group) {
		$and_tags = explode(',', $group);
		$and_conditions = array();

		foreach ($and_tags as $raw_tag) {
			$tag = sed_tag_normalize($raw_tag);
			if ($tag === '') continue;

			$has_wildcard = (strpos($raw_tag, '*') !== false);

			if ($has_wildcard) {
				$pattern = str_replace('*', '%', sed_sql_prep($tag));
				$cond = "EXISTS (SELECT 1 FROM $db_tag_references AS rt WHERE rt.tag_item = $join_col AND rt.tag LIKE '$pattern'";
			} else {
				$cond = "EXISTS (SELECT 1 FROM $db_tag_references AS rt WHERE rt.tag_item = $join_col AND rt.tag = '" . sed_sql_prep($tag) . "'";
			}

			if ($area !== 'all' && $area !== '') {
				$cond .= " AND rt.tag_area = '" . sed_sql_prep($area) . "'";
			}
			$cond .= ")";
			$and_conditions[] = $cond;
		}

		if (!empty($and_conditions)) {
			$or_conditions[] = '(' . implode(' AND ', $and_conditions) . ')';
		}
	}

	if (empty($or_conditions)) return '0';
	return '(' . implode(' OR ', $or_conditions) . ')';
}

/**
 * Builds the HTML for a tag cloud.
 *
 * @param array  $cloud     Cloud data from sed_tag_cloud()
 * @param string $area      Area code for links
 * @param string $base_url  Base URL for tag links (standalone page)
 * @return string HTML
 */
function sed_tag_build_cloud($cloud, $area = 'all', $base_url = '')
{
	global $sed_tag_masks;

	if (empty($cloud)) return '';

	if (empty($base_url)) {
		$base_url = sed_url('plug', 'e=tags');
	}

	$inner = '';
	foreach ($cloud as $item) {
		$display = sed_tag_title(sed_cc($item['tag']));
		$sep = (strpos($base_url, '?') !== false) ? '&' : '?';
		$url = $base_url . $sep . 't=' . sed_tag_qs_value($item['tag']);
		if ($area !== 'all') {
			$url .= '&a=' . sed_tag_qs_value($area);
		}
		$inner .= sprintf($sed_tag_masks['cloud_link'], $url, $item['level'], $item['cnt'], $display);
	}

	return sprintf($sed_tag_masks['cloud_wrapper'], $inner);
}

/**
 * Builds inline tag list HTML for display on pages/topics.
 *
 * @param array       $tags  Array of tag strings
 * @param string      $area  Area code
 * @param string|bool $label Label text, null = default from lang, false or '' = no label
 * @return string HTML
 */
function sed_tag_build_list($tags, $area = 'pages', $label = null)
{
	global $L, $cfg, $sed_tag_masks;

	if (empty($tags)) return '';

	$links = array();
	foreach ($tags as $tag) {
		$display = sed_tag_title(sed_cc($tag));
		$url = sed_url('plug', 'e=tags&a=' . sed_tag_qs_value($area) . '&t=' . sed_tag_qs_value($tag));
		$links[] = sprintf($sed_tag_masks['list_link'], $url, $display);
	}

	$separator = isset($cfg['plugin']['tags']['list_separator']) ? $cfg['plugin']['tags']['list_separator'] : ' ';
	if ($separator === '') $separator = ' ';

	$links_str = implode($separator, $links);
	if ($label !== false && $label !== '') {
		$label_str = ($label !== null) ? $label : (isset($L['tags_tags']) ? $L['tags_tags'] : 'Tags');
		$content = $label_str . ': ' . $links_str;
	} else {
		$content = $links_str;
	}
	return sprintf($sed_tag_masks['list_wrapper'], $content);
}

/**
 * Builds the "All tags" link block for limited clouds.
 *
 * @param string $url   URL to full tags page
 * @param string $label Link text
 * @return string HTML
 */
function sed_tag_build_more($url, $label)
{
	global $sed_tag_masks;
	return sprintf($sed_tag_masks['more_block'], $url, $label);
}

/**
 * Renames a tag in the dictionary and all references.
 *
 * @param string $old_tag Old tag
 * @param string $new_tag New tag
 * @return bool
 */
function sed_tag_rename($old_tag, $new_tag)
{
	global $db_tags, $db_tag_references;

	$old_tag = sed_tag_normalize($old_tag);
	$new_tag = sed_tag_normalize($new_tag);

	if ($old_tag === '' || $new_tag === '' || $old_tag === $new_tag) return false;
	if (sed_tag_exists($new_tag)) return false;

	sed_sql_query("UPDATE $db_tags SET tag = '" . sed_sql_prep($new_tag) . "' WHERE tag = '" . sed_sql_prep($old_tag) . "'");
	sed_sql_query("UPDATE $db_tag_references SET tag = '" . sed_sql_prep($new_tag) . "' WHERE tag = '" . sed_sql_prep($old_tag) . "'");
	return true;
}

/**
 * Cleans up orphaned tag references (items that no longer exist).
 *
 * @return int Number of deleted references
 */
function sed_tag_cleanup()
{
	global $db_tag_references, $db_pages, $db_forum_topics;

	$deleted = 0;

	if (sed_module_active('page')) {
		sed_sql_query("DELETE r FROM $db_tag_references AS r
			LEFT JOIN $db_pages AS p ON r.tag_item = p.page_id
			WHERE r.tag_area = 'pages' AND p.page_id IS NULL");
		$deleted += sed_sql_affectedrows();
	}

	if (sed_module_active('forums')) {
		sed_sql_query("DELETE r FROM $db_tag_references AS r
			LEFT JOIN $db_forum_topics AS ft ON r.tag_item = ft.ft_id
			WHERE r.tag_area = 'forums' AND ft.ft_id IS NULL");
		$deleted += sed_sql_affectedrows();
	}

	return $deleted;
}

/**
 * Adds autocomplete JS to the page (once). Call from form hook files only.
 */
function sed_tags_add_autocomplete()
{
	global $cfg;
	static $added = false;
	if ($added) return;
	$added = true;

	sed_add_javascript('system/javascript/autocomplete.js', true);
	$min = isset($cfg['plugin']['tags']['autocomplete_minlen']) ? (int)$cfg['plugin']['tags']['autocomplete_minlen'] : 3;
	sed_add_javascript(
		"document.addEventListener('DOMContentLoaded',function(){"
		. "document.querySelectorAll('.autotags').forEach(function(el){"
		. "new Autocomplete(el,{"
		. "serviceUrl:'" . sed_url('plug', 'ajx=tags') . "',"
		. "paramName:'q',"
		. "minChars:" . $min . ","
		. "delimiter:','"
		. "});"
		. "});"
		. "});"
	);
}
