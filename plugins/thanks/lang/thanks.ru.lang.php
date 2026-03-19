<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/thanks/lang/thanks.ru.lang.php
Version=185
Type=Plugin
[END_SED]

==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

$L['thanks_thanks'] = '<i class="ic-thumb-up"></i> Спасибо!';
$L['thanks_thanks_title'] = 'Спасибо!';
$L['thanks_done'] = 'Вы поблагодарили автора';
$L['thanks_title'] = 'Благодарности пользователям';
$L['thanks_title_short'] = 'Благодарности';
$L['thanks_title_user'] = 'Благодарности пользователю';
$L['thanks_err_maxday'] = 'Извините, сегодня благодарить больше нельзя';
$L['thanks_err_maxuser'] = 'Извините, этого пользователя поблагодарить сегодня снова нельзя';
$L['thanks_err_item'] = 'Извините, вы уже поблагодарили за этот объект';
$L['thanks_err_self'] = 'Вы не можете поблагодарить себя';
$L['thanks_none'] = 'Благодарностей нет';
$L['thanks_users_none'] = 'Пользователей с благодарностями нет';
$L['thanks_date'] = 'Дата';
$L['thanks_from'] = 'От';
$L['thanks_to'] = 'Кому';
$L['thanks_item'] = 'Объект';
$L['thanks_category'] = 'Категория';
$L['thanks_for'] = 'За что';
$L['thanks_type_page'] = 'Страница';
$L['thanks_type_post'] = 'Пост';
$L['thanks_type_comment'] = 'Комментарий';
$L['thanks_total'] = 'Всего';
$L['thanks_thanked'] = 'Поблагодарили';
$L['thanks_thanked_times'] = 'Поблагодарили: %s раз';
$L['thanks_etc'] = '...';
$L['thanks_notify_pm_subject'] = 'Вам выразили благодарность';
$L['thanks_notify_pm_body'] = "Пользователь %1\$s поблагодарил вас.\nПодробнее: %2\$s";
$L['thanks_notify_email_subject'] = 'Вам выразили благодарность';
$L['thanks_notify_email_body'] = "Здравствуйте, %1\$s!\n\nПользователь %2\$s поблагодарил вас.\nПодробнее: %3\$s";

/* Config */
$L['cfg_maxday'] = array("Макс. благодарностей пользователя в день", "");
$L['cfg_maxuser'] = array("Макс. благодарностей одному пользователю в день", "");
$L['cfg_maxthanked'] = array("Сколько благодаривших показывать (0=все)", "");
$L['cfg_page_on'] = array("Благодарности за страницы", "");
$L['cfg_forums_on'] = array("Благодарности за посты форума", "");
$L['cfg_comments_on'] = array("Благодарности за комментарии", "");
$L['cfg_short'] = array("Короткий формат (только имена)", "");
$L['cfg_notify_by_pm'] = array("Уведомлять в ЛС о новой благодарности", "");
$L['cfg_notify_by_email'] = array("Уведомлять по e-mail о новой благодарности", "");
$L['cfg_notify_from'] = array("E-mail отправителя для уведомлений", "");
$L['cfg_thanksperpage'] = array("Благодарностей на страницу в списках", "");
$L['cfg_format'] = array("Маска формата даты (пусто = системная по умолчанию)", "");
$L['cfg_css'] = array("Подключать CSS плагина", "");
