<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=plugins/contact/lang/contact.ru.lang.php
Version=177
Updated=2012-may-16
Type=
Author=Neocrome
Description=Перевод на могучий - Антонио [antony.ldu.ru]
[END_SED]
==================== */


$L['plu_title'] = "Контакт";


$L['plu_explain'] = "Заполни эту форму чтобы отправить письмо : ";
$L['plu_recipients_title'] = "Получатель";
$L['plu_email_title'] = "Электропочта";
$L['plu_name_title'] = "Имя";
$L['plu_phone_title'] = "Номер телефона";
$L['plu_subject_title'] = "Тема письма";
$L['plu_message_title'] = "Сообщение";
$L['plu_required'] = "* = обязательные поля";
$L['plu_verify'] = "введи эти цифры без точек : ";
$L['plu_send'] = "Отправить";

$L['plu_fieldempty'] = "Одно из обязательных полей незаполнено.";
$L['plu_wrongentry'] = "Обязательное поле заполнено с ошибкой.";
$L['plu_antispam'] = "Защитный код введён неправильно, пожалуйста, попробуй напечатать его ещё раз !";
$L['plu_notsent'] = "Сообщение неотправлено.";
$L['plu_sent'] = "Сообщение успешно отправлено !";
$L['plu_notice'] = "Это сообщение было оправлено с ".$cfg['maintitle']." от : ";

$L['cfg_emails'] = array("Список email адресов, разделенных через запятую", "");
$L['cfg_recipients'] = array("Имена получателей, разделенные через запятые, в порядке списка email", "");
$L['cfg_admincopy1'] = array("Отсылать копию сообщения на email", "");
$L['cfg_admincopy2'] = array("Отсылать копию сообщения на email", "");
$L['cfg_extra1'] = array("Экстра слот #1 / {PLUGIN_CONTACT_EXTRA1} в skins/.../plugin.standalone.contact.tpl", "");
$L['cfg_extra2'] = array("Экстра слот #2 / {PLUGIN_CONTACT_EXTRA2} в skins/.../plugin.standalone.contact.tpl", "");
$L['cfg_extra3'] = array("Экстра слот #3 / {PLUGIN_CONTACT_EXTRA2} в skins/.../plugin.standalone.contact.tpl", "");

?>
