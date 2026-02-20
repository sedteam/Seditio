<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/forums/lang/forums.ru.lang.php
Version=185
Updated=2026-feb-14
Type=Module.lang
Author=Seditio Team
Description=Forums Russian language file
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

/* ====== Форумы ======= */

$L['Forums'] = "Форумы";
$L['Topics'] = "Темы";
$L['Posts'] = "Сообщений";
$L['Post'] = "Сообщение";
$L['Topic'] = "Тема";
$L['Replies'] = "Ответов";
$L['Lastpost'] = "Последнее сообщение";
$L['Started'] = "Cоздана";
$L['Topicoptions'] = "Опции темы";
$L['Topiclocked'] = "Эта тема закрыта, публикация новых сообщений недоступна.";
$L['Announcement'] = "Обьявление";
$L['Bump'] = "Переместить наверх";
$L['Ghost'] = "Оставить уведомление о перемещении";
$L['Lock'] = "Закрыть";
$L['Makesticky'] = "Прикрепить";
$L['Moved'] = "Перемещено";
$L['Poll'] = "Опрос";
$L['Private'] = "Приватная";

$L['for_newtopic'] = "Новая тема";
$L['for_markallasread'] = "Отметить все сообщения как прочитанные";
$L['for_titletooshort'] = "Заголовок слишком короткий либо отсутствует";
$L['for_msgtooshort'] = "Текст топика слишком короткий либо отсутствует";
$L['for_updatedby'] = "<br /><em>отредактировал(а) %1\$s: %2\$s</em>";
$L['for_antibump'] = "Включена система защиты от спама. Вы не можете добавлять несколько сообщений подряд.";
$L['for_mod_clear'] = "Очистить рейтинги";
$L['for_mod_force'] = "Принудительно установить рейтинг ";
$L['for_quickpost'] = "Быстрый ответ";
$L['for_post_text'] = "Текст поста";

/* ====== Форумы — админка ======= */

$L['adm_forums'] = "Форумы";
$L['adm_forums_main'] = "Структура форума";
$L['adm_autoprune'] = "Автоматически удалять темы после * дней";
$L['adm_postcounters'] = "Сверить счётчики";
$L['adm_help_forums'] = "Не доступно";
$L['adm_forum_structure'] = "Структура категорий форума";
$L['adm_forum_structure_cat'] = "Структура разделов форума";
$L['adm_help_forums_structure'] = "Недоступно";

/* ====== Метки конфигурации ======= */

$L['core_forums'] = "Форум";
$L['cfg_hideprivateforums'] = array("Скрывать приватные разделы форума", "");
$L['cfg_hottopictrigger'] = array("Сообщений в теме для присвоения ей статуса 'популярная'", "");
$L['cfg_maxtopicsperpage'] = array("Максимум тем или сообщений на странице", "");
$L['cfg_antibumpforums'] = array("'Анти-бамп' защита", "Запрет на добавление пользователями нескольких сообщений подряд в одной и той же теме форума");
$L['cfg_disable_forums'] = array("Отключить форум", "");
$L['cfg_trash_forum'] = array("Разрешить использование корзины для форумов", "");
$L['cfg_forumstitle'] = array("Заголовок для форумов", "Доступные опции: {MAINTITLE}, {SUBTITLE}, {TITLE}");
$L['cfg_disable_rssforums'] = array("Отключить RSS ленту для форума", "");
