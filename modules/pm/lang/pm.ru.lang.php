<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=modules/pm/lang/pm.ru.lang.php
Version=185
Updated=2026-feb-14
Type=Module.lang
Author=Seditio Team
Description=PM Russian language file
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$L['core_pm'] = "Личные сообщения";

$L['cfg_pmtitle'] = array("Заголовок для личных сообщений", "Доступные опции: {MAINTITLE}, {SUBTITLE}, {TITLE}");
$L['cfg_pm_maxsize'] = array("Макс. длина сообщений", "По умолчанию: 10000 знаков");
$L['cfg_pm_allownotifications'] = array("Разрешить уведомления на e-mail о получении личных сообщений", "");

$L['pm_titletooshort'] = "Заголовок слишком короткий либо отсутствует";
$L['pm_bodytooshort'] = "Текст сообщения слишком короткий либо отсутствует";
$L['pm_bodytoolong'] = "Текст сообщения слишком длинный, максимальное количество символов " . (isset($cfg['pm_maxsize']) ? $cfg['pm_maxsize'] : 10000) . ".";
$L['pm_wrongname'] = "По крайней мере одно имя получателя было указано неверно и поэтому было удалено из списка";
$L['pm_toomanyrecipients'] = "Максимальное количество получателей %1\$s";
$L['pmsend_title'] = "Написать новое личное сообщение (ЛС)";
$L['pmsend_subtitle'] = "";
$L['pm_sendnew'] = "Написать новое личное сообщение";
$L['pm_inbox'] = "Входящие";
$L['pm_inboxsubtitle'] = "Личные сообщения, новые сверху";
$L['pm_sentbox'] = "Отправленные";
$L['pm_sentboxsubtitle'] = "Здесь находятся сообщения, отосланные вами, но еще не прочитанные получателем. При необходимости, эти сообщения можно редактировать или удалять.";
$L['pm_archives'] = "Архив";
$L['pm_arcsubtitle'] = "Старые сообщения, последние сверху";
$L['pm_replyto'] = "Ответить на это сообщение";
$L['pm_putinarchives'] = "Переместить в архив";
$L['pm_notifytitle'] = "Новое Личное Сообщение";
$L['pm_notify'] = "Здравствуйте, %1\$s!\n\nВам пришло новое Личное Сообщение, которое находится в вашей папке inbox.\nОтправитель : %2\$s\nКликните на ссылку, чтобы прочитать полученное сообщение : %3\$s";
$L['pm_multiplerecipients'] = "Это сообщение также было отправлено ещё %1\$s пользователю(ям).";
