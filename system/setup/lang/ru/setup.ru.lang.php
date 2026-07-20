<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org

[BEGIN_SED]
File=system/setup/lang/ru/setup.ru.lang.php
Version=186
Updated=2026-jul-20
Type=Core.setup
Author=Seditio Team
Description=Russian setup language file
[END_SED]
==================== */

// Language name
$L['lang_name'] = "Русский";

// Steps
$L['setup_step1'] = "Приветствие";
$L['setup_step2'] = "Проверка";
$L['setup_step3'] = "База данных";
$L['setup_step4'] = "Настройки";
$L['setup_step5'] = "Компоненты";
$L['setup_step6'] = "Установка";

// Navigation
$L['setup_next_step'] = "Далее";
$L['setup_prev_step'] = "Назад";

// Welcome
$L['setup_welcome_title'] = "Добро пожаловать в Seditio";
$L['setup_welcome_desc'] = "Этот интерактивный мастер поможет вам развернуть и настроить Seditio CMS с нуля за несколько простых шагов.";
$L['setup_select_language'] = "Выберите язык установки";

// System Check
$L['setup_system_check_title'] = "Проверка совместимости";
$L['setup_system_check_desc'] = "Мы проверяем конфигурацию вашего сервера и права доступа к файлам для корректной работы движка.";
$L['setup_checking'] = "Проверка";
$L['setup_php_version'] = "Версия PHP";
$L['setup_php_min_74'] = "Требуется PHP 5.6 или выше";
$L['setup_available'] = "Доступно";
$L['setup_missing'] = "Не найдено";
$L['setup_folder'] = "Папка";
$L['setup_writable'] = "Доступно для записи";
$L['setup_not_writable'] = "Запрещено для записи";
$L['setup_not_found'] = "Не найдено";
$L['setup_found_writable'] = "Найдено и доступно для записи";
$L['setup_found_notwritable'] = "Найдено, но защищено от записи";
$L['setup_notfound_folderwritable'] = "Не найдено, но папка доступна для записи";
$L['setup_notfound_foldernotwritable'] = "Не найдено, папка защищена от записи";

// Database
$L['setup_db_title'] = "Подключение к базе данных";
$L['setup_db_desc'] = "Укажите параметры подключения к вашему SQL-серверу.";
$L['setup_db_host'] = "Хост БД";
$L['setup_db_host_hint'] = "Чаще всего это 'localhost'";
$L['setup_db_name'] = "Имя базы данных";
$L['setup_db_user'] = "Пользователь БД";
$L['setup_db_password'] = "Пароль БД";
$L['setup_db_prefix'] = "Префикс таблиц";
$L['setup_db_prefix_hint'] = "Обычно 'sed_' (не меняйте, если не уверены)";
$L['setup_db_clear'] = "Очистить базу данных перед импортом";
$L['setup_db_clear_hint'] = "Внимание: все таблицы в указанной БД будут удалены!";
$L['setup_test_connection'] = "Проверить подключение";
$L['setup_testing'] = "Проверка...";
$L['setup_db_connected'] = "Соединение успешно установлено! Версия MySQL: %s";
$L['setup_check_credentials'] = "Ошибка подключения. Пожалуйста, проверьте введённые данные.";

// Settings
$L['setup_settings_title'] = "Первичные настройки сайта";
$L['setup_default_skin'] = "Скин по умолчанию";
$L['setup_default_lang'] = "Язык по умолчанию";
$L['setup_admin_account'] = "Аккаунт суперадминистратора";
$L['setup_admin_name'] = "Имя пользователя";
$L['setup_admin_pass'] = "Пароль";
$L['setup_admin_email'] = "Электронная почта (Email)";
$L['setup_admin_country'] = "Страна";
$L['setup_generate_password'] = "Сгенерировать";
$L['setup_password_copied'] = "Пароль скопирован в буфер обмена!";
$L['setup_password_min'] = "Минимум 8 символов";
$L['setup_ownaccount_name'] = "Ваш логин в системе";
$L['setup_least8chars'] = "Как минимум 8 символов";
$L['setup_doublecheck'] = "Проверьте дважды, это важно!";

// Extensions
$L['setup_extensions_title'] = "Выбор модулей и плагинов";
$L['setup_tab_modules'] = "Модули";
$L['setup_tab_plugins'] = "Плагины";
$L['setup_locked_module'] = "Обязательный";
$L['setup_select_all'] = "Выбрать все";
$L['setup_deselect_all'] = "Снять все";
$L['setup_optional_modules'] = "Выберите модули для вашего сайта:";
$L['setup_optional_plugins'] = "Выберите дополнительные плагины:";
$L['setup_no_modules'] = "Модули в /modules/ не найдены.";
$L['setup_no_plugins'] = "Плагины в /plugins/ не найдены.";

// Installation
$L['setup_install'] = "Установить";
$L['setup_installing_title'] = "Процесс установки";
$L['setup_connected_to_db'] = "Подключение к базе данных";
$L['setup_config_created'] = "Запись файла конфигурации datas/config.php";
$L['setup_tables_created'] = "Создание таблиц базы данных";
$L['setup_config_loaded'] = "Импорт базовых конфигураций";
$L['setup_admin_created'] = "Создание учетной записи администратора";
$L['setup_complete'] = "Настройка успешно завершена";
$L['setup_success_title'] = "Seditio успешно установлен!";
$L['setup_success_desc'] = "Сайт полностью готов к наполнению и запуску. Не забудьте удалить установщик для безопасности!";
$L['setup_go_home'] = "На главную";
$L['setup_go_admin'] = "Панель управления";

// Errors
$L['setup_error_db_connection'] = "Не удалось подключиться к БД.";
$L['setup_error_config_write'] = "Ошибка: не удалось записать datas/config.php. Проверьте права доступа.";
$L['setup_error_field_required'] = "Это поле обязательно для заполнения.";
$L['setup_error_notwrite'] = "Ошибка записи, пожалуйста, проверьте права CHMOD.";
$L['setup_wrong_manual'] = "Что-то пошло не так. Подробное руководство по ручной установке доступно <a href=\"https://seditio.org/doc/\" target=\"_blank\">здесь</a>.";
$L['setup_title'] = "Seditio — Установка";
$L['setup_database_cleared'] = "База данных успешно очищена";
$L['setup_tables_dropped'] = "таблиц было удалено";
$L['setup_config_size'] = "Размер файла конфигурации: %s байт.";
$L['setup_plugin_skipped'] = "пропущен (требуемый модуль %s не установлен)";
