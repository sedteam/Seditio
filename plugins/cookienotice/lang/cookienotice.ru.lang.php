<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/cookienotice/lang/cookienotice.ru.lang.php
Version=1.0.0
Updated=2026-jul-14
Type=Plugin
Author=Seditio Team
Description=Russian language file for cookienotice plugin
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die("Wrong URL.");
}

// Configuration descriptions
$L['cfg_cookie_text'] = array("Текст уведомления", "Оставьте пустым для использования текста по умолчанию. Поддерживает плейсхолдеры: {STAT_URL} — ссылка на сбор статистики, {POLICY_URL} — ссылка на политику конфиденциальности.");
$L['cfg_cookie_url_stat'] = array("Ссылка на страницу сбора статистики", "Например: /sborstat");
$L['cfg_cookie_url_policy'] = array("Ссылка на Политику конфиденциальности", "Например: /policy");

// Front-end text
$L['cookienotice_text'] = 'Для улучшения работы сайта мы применяем <a href="{STAT_URL}">файлы cookies и сервисы статистики</a>. Вы можете принять все cookie или настроить их использование в соответствии с нашей <a href="{POLICY_URL}">Политикой конфиденциальности</a>.';
$L['cookienotice_btn_accept'] = 'Принять все';
$L['cookienotice_btn_close'] = 'Отказаться';
