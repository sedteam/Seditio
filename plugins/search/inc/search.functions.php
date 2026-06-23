<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/search/inc/search.functions.php
Version=185
Type=Plugin
Description=Search snippet helpers
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

/**
 * Wrap whole words containing a search term in span.word.
 *
 * @param array $matches PCRE match
 * @return string
 */
function sed_plug_search_highlight_word_cb($matches)
{
	return '<span class="word">' . $matches[1] . '</span>';
}

/**
 * Wrap search words in span.word inside escaped text.
 *
 * @param string $text  Plain text
 * @param array  $words Search words
 * @return string
 */
function sed_plug_search_highlight($text, $words)
{
	$text = sed_cc($text);

	foreach ($words as $word) {
		$word = trim($word);
		if ($word === '' || mb_strlen($word) < 2) {
			continue;
		}
		$quoted = preg_quote($word, '/');
		$pattern = '/(?<![\p{L}\p{N}])([\p{L}\p{N}]*' . $quoted . '[\p{L}\p{N}]*)(?![\p{L}\p{N}])/iu';
		$text = preg_replace_callback($pattern, 'sed_plug_search_highlight_word_cb', $text);
	}

	return $text;
}

/**
 * Build a plain-text snippet around the first matched search word.
 *
 * @param string $text   Raw page/post text (BBCode/HTML)
 * @param array  $words  Search words (plain, not SQL-escaped)
 * @param int    $length Max snippet length in characters
 * @return string
 */
function sed_plug_search_snippet($text, $words, $length = 220)
{
	global $cfg;

	$plain = strip_tags(sed_parse($text));
	$plain = preg_replace('/\s+/u', ' ', trim($plain));

	if ($plain === '') {
		return '';
	}

	$pos = false;

	foreach ($words as $word) {
		$word = trim($word);
		if ($word === '' || mb_strlen($word) < 2) {
			continue;
		}
		$p = mb_stripos($plain, $word);
		if ($p !== false && ($pos === false || $p < $pos)) {
			$pos = $p;
		}
	}

	if ($pos === false) {
		$pos = 0;
	}

	$enc = (!empty($cfg['charset'])) ? $cfg['charset'] : 'UTF-8';
	$start = max(0, $pos - (int) ($length / 3));
	$snippet = mb_substr($plain, $start, $length, $enc);

	if ($start > 0) {
		$snippet = '...' . $snippet;
	}
	if (mb_strlen($plain, $enc) > $start + $length) {
		$snippet .= '...';
	}

	return sed_plug_search_highlight($snippet, $words);
}
