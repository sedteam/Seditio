<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=install.ru.lang.php
Version=180
Updated=2025-jan-25
Type=Core
Author=Seditio Team
Description=Russian installation lang file
[END_SED]
==================== */

$L['install_step0'] = "Выбор языка установки";
$L['install_step1'] = "Проверка совместимости";
$L['install_step2'] = "Файл конфигурации";
$L['install_step3'] = "MySQL база данных";
$L['install_step4'] = "Плагины";
$L['install_step5'] = "Готово";

$L['install_language installation'] = "Язык установки";
$L['install_select_language installation'] = "Выбор языка установки";
$L['install_title'] = "Seditio - Установка";
$L['install_build_config'] = "Создание файла конфигурации ";
$L['install_looks_chmod'] = "Похоже успешно, сейчас попробую выставить файлу CHMOD в режим #только для чтения#...";
$L['install_setting_mysql'] = "Настройка SQL базы данных...";
$L['install_creating_mysql'] = "Создание таблиц в базе данных...";
$L['install_presettings'] = "Предварительная настройка конфигурационных записей...";
$L['install_adding_administrator'] = "Добавление аккаунта администратора...";
$L['install_done'] = "Готово.";
$L['install_contine_toplugins'] = "Перейдём к плагинам";
$L['install_error_notwrite'] = "Ошибка, не возможна запись в файл, пожалуйста проверьте права записи.";
$L['install_now'] = "Установить сейчас";
$L['install_plugins'] = "Плагины";
$L['install_install'] = "Установка";
$L['install_optional_plugins'] = "Здесь вы можете установить плагины и узнать о новых возможностях.<br /> Они не являются обязательными, \n
           и вы всегда сможете их изменить позже в панели администратора, в разделе, 'Плагины'.<br /> \n
           Если вы не знаете, что выбрать, просто оставьте чекбоксы как есть.<br />";
$L['install_installing_plugins'] = "Установка плагинов :";
$L['install_installed_plugins'] = "установлено плагинов (";
$L['install_display_log'] = "Показать лог";
$L['install_contine_homepage'] = "Продолжить и перейти на главную страницу";
$L['install_error'] = "Ошибка!";
$L['install_wrong_manual'] = "Что-то пошло не так, вам придется вручную настроить систему, подробные шаги <a href=\"https://seditio.org/doc/\">здесь</a>.";
$L['install_database_setup'] = "Настройка SQL базы данных :";
$L['install_database_hosturl'] = "Хост URL базы данных:";
$L['install_always_localhost'] = "Чаще всего это 'localhost'";
$L['install_database_user'] = "Пользователь базы данных :";
$L['install_see_yourhosting'] = "Смотреть у хостера в панели управления";
$L['install_database_password'] = "Пароль к базе данных :";
$L['install_database_name'] = "Имя базы данных :";
$L['install_database_tableprefix'] = "Префикс таблиц в базе данных :";
$L['install_seditio_already'] = "Не изменять, если у вас уже есть Seditio в этой базе данных";
$L['install_input_mode'] = "Режим работы для текстовых полей :";
$L['install_html_mode'] = "<strong>HTML</strong> (рекомендуется)<br /> \n
           Текстовые поля для страниц, постов на форуме, комментариев и личных сообщений обрабатываются как HTML код.<br />\n
           A WYSIWYG HTML редактор будет автоматически установлен.";
$L['install_bbcode_mode'] = "<strong>BBcode</strong><br />
          Текстовые поля для страниц, постов на форуме, комментариев и личных сообщений обрабатываются как BBCode тэги<br />\n
          Встроенный BBcode редактор будет автоматически установлен.";
$L['install_skinandlang'] = "Скин и язык :";
$L['install_default_skin'] = "Скин по-умолчанию :<br />(Файлы скинов хранятся в папке /skins/...";
$L['install_default_lang'] = "Язык по-умолчанию :";
$L['install_admin_account'] = "Аккаунт администратора :";
$L['install_account_name'] = "Логин :";
$L['install_ownaccount_name'] = "Ваш логин в системе";
$L['install_password'] = "Пароль :";
$L['install_least8chars'] = "По крайней мере 8 символов";
$L['install_email'] = "Email :";
$L['install_doublecheck'] = "Проверьте дважды, это важно!";
$L['install_country'] = "Страна :";
$L['install_validate'] = "Валидация";
$L['install_auto_installer'] = "Это автоматический установщик для Seditio (версия " . @$cfg['version'] . ")";
$L['install_create_configfile'] = "В процессе установки будет создан файл конфигурации <strong>" . @$cfg['config_file'] . "</strong>, \n
	         после чего будут созданы таблицы в вашей MySQL базе.<br /> \n
	         До запуска процесса установки вы должны создать базу данных на вашем хостинге, \n
	         и все PHP и системные файлы должны быть загружены на ваш хост.<br />&nbsp<br /> \n
	         В случае, если что-то пойдет не так в процессе установки, удалите файл <strong>" . @$cfg['config_file'] . "</strong> с помощью FTP клиента, и снова запустите процесс установки.<br />&nbsp<br /> \n
	         Сейчас вы должны установить атрибуты, CHMOD 0777 на все папки, перечисленные ниже, не доступные для записи :<br />";
$L['install_folder'] = "Папка";
$L['install_writable'] = "Доступно для записи";
$L['install_not_writable'] = "Не доступно для записи";
$L['install_not_found'] = "Не найдено";
$L['install_file'] = "Файл";
$L['install_found_writable'] = "Нейдено и доступно для записи";
$L['install_found_notwritable'] = "Найдено, Но не доступно для записи";
$L['install_notfound_folderwritable'] = "Не найдено, папка доступна для записи, так что всё должно быть ОК.";
$L['install_notfound_foldernotwritable'] = "Не найдено, и папка не доступна для записи";
$L['install_phpversion'] = "Версия PHP :";
$L['install_ok'] = "Ok";
$L['install_too_old'] = "Слишком старая";
$L['install_mysql_extension'] = "MySQL расширение :";
$L['install_gd_extension'] = "GD расширение :";
$L['install_mysqli_extension'] = "MySQLi расширение :";
$L['install_mysql_connector'] = "MySQL коннектор драйвер :";
$L['install_mysql_preffered'] = "Наиболее предпочтительно MySQLi расширение";
$L['install_available'] = "Доступно";
$L['install_missing'] = "Недоступно?";
$L['install_refresh'] = "Обновить";
$L['install_nextstep'] = "Следующий шаг";
