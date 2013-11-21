<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=plugins/passrecover/lang/passrecover.ru.lang.php
Version=175
Updated=2012-may-16
Type=Plugin.standalone
Author=Neocrome (Translated by Antar)
Description=Небольшие правки от Антонио (antony.ldu.ru)
[END_SED]
==================== */


$L['plu_title'] = "Восстановление пароля";

$L['plu_explain1'] = "1. Введи свой e-mail и нажми кнопку \"Запрос\".";
$L['plu_explain2'] = "2. Проверь свой почтовый ящик, туда должно прийти письмо с ссылкой для входа на сайт.";
$L['plu_explain3'] = "3. Затем зайди в профиль и установи новый пароль.";
$L['plu_explain4'] = "Если в профиле отсутствует e-mail, или указан недействующий адрес то восстановить пароль неполучится. <br />В этом случае <a href=\"".sed_url("plug", "e=contact")."\">обратись к владельцам сайта</a>.";
$L['plu_mailsent'] = "Готово. Скоро ты получишь письмо со ссылкой для входа на сайт.<br />Затем следуй дальнейшим инструкциям.";
$L['plu_mailsent2'] = "Готово. Скоро ты получишь письмо со ссылкой для смены пароля.<br />Затем следуй дальнейшим инструкциям.";
$L['plu_youremail'] = "Твой e-mail : ";
$L['plu_request'] = "Запрос";
$L['plu_loggedin1'] = "Здравствуй, ";
$L['plu_loggedin2'] = "ты вошёл на сайт.";
$L['plu_loggedin3'] = "Теперь зайди в <a href=\"".sed_url("users", "m=profile")."\">профиль</a> и установи новый пароль.";
$L['plu_email1'] = "Ты запросил восстановление пароля для входа на наш сайт. \r\nПерейди по ссылке ниже чтобы войти на сайт, и следуй дальнейшим инструкциям :";
$L['plu_email2'] = "Ты запросил восстановление пароля для входа на наш сайт. \r\nПерейди по ссылке ниже чтобы сгенерировать новый пароль. Новый пароль будет выслан на e-mail :";
$L['plu_email3'] = "По твоему запросу создан новый пароль. Измени его при первой возможности и удали данное письмо.\r\n\r\nТвой новый пароль: "; 
$L['plu_newpass'] = "Готово!\r\n\r\n Скоро ты получишь письмо с новым паролем для входа на сайт";

$L['cfg_generate_password'] = array("Генерировать новый пароль и отсылать на e-mail?", "");
?>