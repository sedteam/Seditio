<?php

/* ====================
Seditio - Website engine
Copyright (c) Seditio Team
https://seditio.org
-----------------------
Seditio language pack
Language : Russian (code:ru)
Localization done by : Antar, Antony, Amro
-----------------------
[BEGIN_SED]
File=system/lang/ru/admin.lang.php
Version=180
Updated=2025-jan-25
Type=Lang
Author=Seditio Team
Description=Admin panel
[END_SED]
==================== */

/* ====== Core ====== */

$L['core_main'] = "Основные настройки";
$L['core_parser'] = "Настройки парсинга";             // New in v120
$L['core_rss'] = "Настройка RSS лент";             // New in v173
$L['core_dic'] = "Справочники и Экстраполя";             // New in v173
$L['core_time'] = "Время и дата";
$L['core_skin'] = "Настройки оформления";
$L['core_lang'] = "Языки";
$L['core_menus'] = "Слоты меню";
$L['core_comments'] = "Комментарии";
$L['core_forums'] = "Форум";
$L['core_page'] = "Страницы";
$L['core_pfs'] = "Файловые архивы";
$L['core_gallery'] = "Галерея";
$L['core_plug'] = "Плагины";
$L['core_pm'] = "Личные сообщения";
$L['core_polls'] = "Опросы";
$L['core_ratings'] = "Рейтинги";
$L['core_trash'] = "Корзина";
$L['core_users'] = "Пользователи";
$L['core_meta'] = "HTML Meta";
$L['core_index'] = "Главная страница";
$L['core_menu'] = "Менеджер меню"; // New in v178

/* ====== Upgrade ====== */

$L['upg_upgrade'] = "Обновление";      // New in v130
$L['upg_codeversion'] = "Версия Seditio";     // New in v130
$L['upg_sqlversion'] = "Версия базы SQL";    // New in v130
$L['upg_codeisnewer'] = "Версия файлов движка новее чем версия SQL.";    // New in v130
$L['upg_codeisolder'] = "Версия файлов движка старее чем версия SQL. Это не соответствует стандартам, и не поддерживается движком.<br/>Проверьте, что вы действительно обновили все необходимые файлы из последнего релиза.";    // New in v130
$L['upg_codeissame'] = "Версия файлов движка и версия SQL базы совпадают.";    // New in v130
$L['upg_upgradenow'] = "Рекомендуется обновить базу вашу базу данных SQL немедленно. Кликните, чтобы обновить!";    // New in v130
$L['upg_upgradenotavail'] = "Для данной версии обновление пока не доступно.";       // New in v130
$L['upg_manual'] = "Если нужно обновить базу данных вручную, необходимые SQL файлы находятся в папке /docs/upgrade/ .";       // New in v130
$L['upg_success'] = "Обновление выполнено, нажми сюда для продолжения...";       // New in v130
$L['upg_failure'] = "Обновление прошло не удачно, нажми сюда для продолжения... ";       // New in v130
$L['upg_force'] = "Если по каким-либо причинам версия Seditio, записанная в SQL базе - ошибочна, можно исправить её, выбрав номер версии и нажав Обновить. Это не внесёт в таблицы никаких других изменений. Изменить номер версии на: ";    // New in v130

/* ====== General ====== */

$L['editdeleteentries'] = "Редактировать/удалить записи";
$L['viewdeleteentries'] = "Посмотреть/удалить записи";
$L['addnewentry'] = "Добавить новую запись";
$L['adm_purgeall'] = "Очистить все";
$L['adm_listisempty'] = "Список пуст";
$L['adm_totalsize'] = "Общий размер";
$L['adm_showall'] = "Показать все";
$L['adm_area'] = "Область";
$L['adm_option'] = "Опция";
$L['adm_setby'] = "Установлено";
$L['adm_more'] = "Дополнительные инструменты";
$L['adm_from'] = "Из";
$L['adm_to'] = "В";
$L['adm_confirm'] = "Нажми для подтверждения:";
$L['adm_done'] = "Готово";
$L['adm_failed'] = "Неудачно";
$L['adm_warnings'] = "Предупреждения";
$L['adm_valqueue'] = "В очереди на публикацию";
$L['adm_required'] = "(обязательное)";
$L['adm_clicktoedit'] = "(нажми для редактирования)";
$L['adm_manage'] = "Инструменты";  // New in v150
$L['adm_pagemanager'] = "Менеджер страниц";  // New in v177
$L['adm_module_name'] = "Название модуля";  // New in v178
$L['adm_tool_name'] = "Название инструмента";  // New in v178

/* ====== Banlist ====== */

$L['adm_ipmask'] = "Маска IP";
$L['adm_emailmask'] = "Маска e-mail";
$L['adm_neverexpire'] = "Навсегда";
$L['adm_help_banlist'] = "Примеры масок IP:<br />
- IPv4: 194.31.13.41, 194.31.13.*, 194.31.*.*, 194.*.*.*<br />
- IPv6: 2001:0db8:85a3:0000:0000:8a2e:0370:7334, 2001:0db8:85a3:0000:0000:8a2e:0370:*, 2001:0db8:85a3:0000:0000:*:*, 2001:0db8:85a3:*:*:*:*
<br />Примеры масок email: @hotmail.com, @yahoo (Подстановочные знаки не поддерживаются)
<br />Одна запись может содержать одну маску IP или одну маску email, или и то, и другое.
<br />IP фильтруются на каждой отображаемой странице, а маски email только при регистрации пользователя.";

/* ====== Cache ====== */

$L['adm_internalcache'] = "Внутренний кэш";
$L['adm_help_cache'] = "Недоступно";

/* ====== Configuration ====== */

$L['adm_help_config'] = "Недоступно";
$L['cfg_adminemail'] = array("Администраторский e-mail", "Обязательно");
$L['cfg_maintitle'] = array("Название сайта", "Основной заголовок сайта, обязательно");
$L['cfg_subtitle'] = array("Описание", "Опционально, отображается сразу за названием сайта");
$L['cfg_mainurl'] = array("URL сайта", "Включая http://, и без слэша в конце !");
$L['cfg_clustermode'] = array("Серверный кластер", "Выбери Да, если используется кластерная балансировка нагрузки.");            // New in v125
$L['cfg_hostip'] = array("IP Сервера", "IP Сервера, опционально");
$L['cfg_gzip'] = array("Gzip", "Gzip компрессия при выводе HTML");
$L['cfg_cache'] = array("Внутренний кэш", "Оставьте включенным для лучшей производительности");
$L['cfg_devmode'] = array("Режим отладки", "Не включать на действующих сайтах");
$L['cfg_doctypeid'] = array("Тип документа", "&lt;!DOCTYPE> в шапке HTML");
$L['cfg_charset'] = array("HTML кодировка", "");
$L['cfg_cookiedomain'] = array("Домен для cookies", "По умолчанию: пусто");
$L['cfg_cookiepath'] = array("Путь для cookies", "По умолчанию: пусто");
$L['cfg_cookielifetime'] = array("Продолжительность жизни cookie", "В секундах");
$L['cfg_metakeywords'] = array("HTML Meta keywords - ключевые слова, разделенные запятой", "Для поисковых машин");
$L['cfg_disablesysinfos'] = array("Отключить время создания страницы", "В footer.tpl");
$L['cfg_keepcrbottom'] = array("Показывать копирайт в тэге {FOOTER_BOTTOMLINE}", "В footer.tpl");
$L['cfg_showsqlstats'] = array("Показывать статистику SQL запросов", "В footer.tpl");
$L['cfg_shieldenabled'] = array("Включить Щит", "Анти-спамминг и анти-хаммеринг");
$L['cfg_shieldtadjust'] = array("Настроить таймеры Щита (в %)", "Чем больше значение, тем жестче ограничение на спам");
$L['cfg_shieldzhammer'] = array("Анти-хаммеринг после * быстрых кликов", "Чем меньше значение, тем быстрее включится 3-х минутная автоблокировка");
$L['cfg_maintenance'] = array("Режим техобслуживания", "Включить при проведении технических работ на сайте");  // New in v175
$L['cfg_maintenancelevel'] = array("Уровень доступа пользователей", "Выберите уровень доступа пользователей");   // New in v175
$L['cfg_maintenancereason'] = array("Причина техобслуживания", "Опишите причину техобслуживания");  // New in v175
$L['cfg_multihost'] = array("Мультихост", "Включить поддержку нескольких хостов");  // New in v175
$L['cfg_absurls'] = array("Абсолютные URL", "Включает использование абсолютных URL");  // New in v175
$L['cfg_sefurls'] = array("SEF URLs", "Включает использование SEF URLs на сайте");  // New in v175
$L['cfg_sefurls301'] = array("301 редирект на SEF URLs", "Включает 301 редирект со старых URL на SEF URLs");  // New in v175
$L['cfg_dateformat'] = array("Основная маска даты", "По умолчанию: Y-m-d H:i");
$L['cfg_formatmonthday'] = array("Краткая маска даты", "По умолчанию: m-d");
$L['cfg_formatyearmonthday'] = array("Средняя маска даты", "По умолчанию: Y-m-d");
$L['cfg_formatmonthdayhourmin'] = array("Маска даты в форуме", "По умолчанию: m-d H:i");
$L['cfg_servertimezone'] = array("Часовой пояс сервера", "Смещение времени на сервере от GMT+00");
$L['cfg_defaulttimezone'] = array("Часовой пояс по умолчанию", "Для гостей и новых пользователей, от -12 до +12");
$L['cfg_timedout'] = array("Время бездействия, в секундах", "Время бездействия, после которого пользователь считается отсутствующим");
$L['cfg_maxusersperpage'] = array("Максимальное количество строк в списке пользователей", "");
$L['cfg_regrequireadmin'] = array("Администраторы должны утверждать регистрацию новых пользовательских счетов", "");
$L['cfg_regnoactivation'] = array("Отключить проверку e-mail для новых пользователей", "Рекомендуется \"Нет\", в целях безопасности");
$L['cfg_useremailchange'] = array("Разрешить пользователям менять свой e-mail адрес", "Рекомендуется \"Нет\", в целях безопасности");
$L['cfg_usertextimg'] = array("Разрешить изображения и HTML в подписях", "Рекомендуется \"Нет\", в целях безопасности");
$L['cfg_av_maxsize'] = array("Аватар, макс. размер файла", "По умолчанию: 8000 байт");
$L['cfg_av_maxx'] = array("Аватар, макс. ширина", "По умолчанию: 64 пикселя");
$L['cfg_av_maxy'] = array("Аватар, макс. высота", "По умолчанию: 64 пикселя");
$L['cfg_usertextmax'] = array("Макс. длина подписи пользователя", "По умолчанию: 300 знаков");
$L['cfg_sig_maxsize'] = array("Подпись, макс. размер файла", "По умолчанию: 50000 байт");
$L['cfg_sig_maxx'] = array("Подпись, макс. ширина", "По умолчанию: 468 пикселей");
$L['cfg_sig_maxy'] = array("Подпись, макс. высота", "По умолчанию: 60 пикселей");
$L['cfg_ph_maxsize'] = array("Фото, макс. размер файла", "По умолчанию: 8000 байт");
$L['cfg_ph_maxx'] = array("Фото, макс. ширина", "По умолчанию: 96 пикселей");
$L['cfg_ph_maxy'] = array("Фото, макс. высота", "По умолчанию: 96 пикселей");
$L['cfg_maxrowsperpage'] = array("Максимальное кол-во строк в списках", "");
$L['cfg_showpagesubcatgroup'] = array("Отображать в группах страницы из подразделов", "");   //New Sed171
$L['cfg_genseourls'] = array("Генерировать SEO url (авто page alias)? ", "");   //New Sed178
$L['cfg_showcommentsonpage'] = array("Отображать комментарии на страницах", "По умолчанию показывать комментарии на странице");   //New Sed171
$L['cfg_maxcommentsperpage'] = array("Максимум комментариев на страницу", "");  //New Sed173
$L['cfg_commentsorder'] = array("Сортировка комментариев", "ASC - новые снизу, DESC - новые сверху");  //New Sed173
$L['cfg_maxtimeallowcomedit'] = array("Разрешенное время на редактирование комментария", "В минутах, если 0 - редактирование запрещено");  //New Sed173
$L['cfg_maxcommentlenght'] = array("Максимальная длина комментария", "В символах, по-умолчанию: 2000 символов");  //New Sed175
$L['cfg_countcomments'] = array("Считать комментарии", "Показывать общее число комментариев рядом с иконкой");
$L['cfg_hideprivateforums'] = array("Скрывать приватные разделы форума", "");
$L['cfg_hottopictrigger'] = array("Сообщений в теме для присвоения ей статуса 'популярная'", "");
$L['cfg_maxtopicsperpage'] = array("Максимум тем или сообщений на страницу", "");
$L['cfg_antibumpforums'] = array("'Анти-бамп' защита", "Запрет на добавление пользователями нескольких сообщений подряд в одной и той же теме форума");
$L['cfg_pfsuserfolder'] = array("Метод хранения в папках", "Если включено, файлы пользователя будут сохраняться в подпапках /datas/users/USERID/... вместо добавления USERID к имени файла. Должна быть установлена ТОЛЬКО при ПЕРВОЙ установке сайта. Как только файл закачан в Персональный Файловый Архив, уже поздно изменять эту опцию. Не рекомендуется менять первоначально установленную опцию.");
$L['cfg_th_amode'] = array("Генератор эскизов", "");
$L['cfg_th_x'] = array("Ширина эскиза", "По умолчанию: 112 пикселей");
$L['cfg_th_y'] = array("Высота эскиза", "По умолчанию: 84 пикселя, рекомендуется: Ширина x 0.75");
//$L['cfg_th_border'] = array("Толщина рамки для эскизов", "По умолчанию: 4 пикселя");
$L['cfg_th_keepratio'] = array("Сохранять пропорции для эскизов?", "");
$L['cfg_th_jpeg_quality'] = array("Качество Jpeg эскизов", "По умолчанию: 85");
//$L['cfg_th_colorbg'] = array("Цвет рамки для эскизов", "По умолчанию: 000000, hex-код цвета");
//$L['cfg_th_colortext'] = array("Цвет текста в эскизах", "По умолчанию: FFFFFF, hex-код цвета");
$L['cfg_th_rel'] = array("Атрибут rel ссылки на миниатюре", "По умолчанию: sedthumb"); // New in v175
$L['cfg_th_dimpriority'] = array("Приоритет формирования эскизов", "По-умолчанию: Width");
//$L['cfg_th_textsize'] = array("Размер шрифта для текста в эскизах", "");
$L['cfg_pfs_filemask'] = array("Имена файлов на основе шаблона времени", "Генерировать имена файлов по шаблону времени.");  // New Sed 172

$L['cfg_available_image_sizes'] = array("Доступные разрешения изображений", "Перечислять через запятую, без пробелов. Пример: 120x80,800x600");  // New in sed180

$L['cfg_disable_gallery'] = array("Отключить галерею", "");         // New in v150
$L['cfg_gallery_gcol'] = array("Количество колонок для галерей", "По умолчанию : 4");     // New in v150
$L['cfg_gallery_bcol'] = array("Количество колонок для изображений", "По умолчанию : 6");        // New in v150
$L['cfg_gallery_logofile'] = array("Png/jpeg/Gif водяной знак, будет добавлен во все загружаемые в PFS изображения", "Оставьте пустым для отключения");        // New in v150

$L['cfg_gallery_logopos'] = array("Позиция вставки лого в PFS изображении", "Default : Bottom left");        // New in v150
$L['cfg_gallery_logotrsp'] = array("Слияние уровня для логотипа в %", "Default : 50");        // New in v150
$L['cfg_gallery_logojpegqual'] = array("Качество конечного изображения после вставки лого, если это Jpeg", "Default : 90");        // New in v150
$L['cfg_gallery_imgmaxwidth'] = array("Ширина отображаемой картинки, до которой будет уменьшено изображение, если оно больше", "");         // New in v150

$L['cfg_pm_maxsize'] = array("Макс. длина сообщений", "По умолчанию: 10000 знаков");
$L['cfg_pm_allownotifications'] = array("Разрешить уведомления на e-mail о получении личных сообщений", "");
$L['cfg_disablehitstats'] = array("Отключить статистику просмотров", "Переходов с других сайтов и просмотров в день");
$L['cfg_disablereg'] = array("Отключить регистрацию", "Запрещает регистрацию новых пользователей");
$L['cfg_disablewhosonline'] = array("Отключить 'кто онлайн?'", "Автоматически включается, если вы активизировали Щит");
$L['cfg_defaultcountry'] = array("Страна по умолчанию для новых пользователей", "код страны из двух букв");    // New in v130
$L['cfg_forcedefaultskin'] = array("Использовать установленный по умолчанию скин для всех пользователей", "");
$L['cfg_forcedefaultlang'] = array("Использовать установленный по умолчанию язык для всех пользователей", "");
$L['cfg_separator'] = array("Разделитель", "По умолчанию:>");
$L['cfg_menu1'] = array("Слот меню #1<br />{PHP.cfg.menu1} во всех tpl файлах", "");
$L['cfg_menu2'] = array("Слот меню #2<br />{PHP.cfg.menu2} во всех tpl файлах", "");
$L['cfg_menu3'] = array("Слот меню #3<br />{PHP.cfg.menu3} во всех tpl файлах", "");
$L['cfg_menu4'] = array("Слот меню #4<br />{PHP.cfg.menu4} во всех tpl файлах", "");
$L['cfg_menu5'] = array("Слот меню #5<br />{PHP.cfg.menu5} во всех tpl файлах", "");
$L['cfg_menu6'] = array("Слот меню #6<br />{PHP.cfg.menu6} во всех tpl файлах", "");
$L['cfg_menu7'] = array("Слот меню #7<br />{PHP.cfg.menu7} во всех tpl файлах", "");
$L['cfg_menu8'] = array("Слот меню #8<br />{PHP.cfg.menu8} во всех tpl файлах", "");
$L['cfg_menu9'] = array("Слот меню #9<br />{PHP.cfg.menu9} во всех tpl файлах", "");
$L['cfg_topline'] = array("Верхняя линия<br />{HEADER_TOPLINE} в header.tpl", "");
$L['cfg_banner'] = array("Баннер<br />{HEADER_BANNER} в header.tpl", "");
$L['cfg_motd'] = array("Фраза дня<br />{NEWS_MOTD} в index.tpl", "");
$L['cfg_bottomline'] = array("Нижняя линия<br />{FOOTER_BOTTOMLINE} в footer.tpl", "");
$L['cfg_freetext1'] = array("Свободный текст, слот #1<br />{PHP.cfg.freetext1} во всех tpl файлах", "");
$L['cfg_freetext2'] = array("Свободный текст, слот #2<br />{PHP.cfg.freetext2} во всех tpl файлах", "");
$L['cfg_freetext3'] = array("Свободный текст, слот #3<br />{PHP.cfg.freetext3} во всех tpl файлах", "");
$L['cfg_freetext4'] = array("Свободный текст, слот #4<br />{PHP.cfg.freetext4} во всех tpl файлах", "");
$L['cfg_freetext5'] = array("Свободный текст, слот #5<br />{PHP.cfg.freetext5} во всех tpl файлах", "");
$L['cfg_freetext6'] = array("Свободный текст, слот #6<br />{PHP.cfg.freetext6} во всех tpl файлах", "");
$L['cfg_freetext7'] = array("Свободный текст, слот #7<br />{PHP.cfg.freetext7} во всех tpl файлах", "");
$L['cfg_freetext8'] = array("Свободный текст, слот #8<br />{PHP.cfg.freetext8} во всех tpl файлах", "");
$L['cfg_freetext9'] = array("Свободный текст, слот #9<br />{PHP.cfg.freetext9} во всех tpl файлах", "");
$L['cfg_extra1title'] = array("Поле #1 (Строка), заголовок", "");
$L['cfg_extra2title'] = array("Поле #2 (Строка), заголовок", "");
$L['cfg_extra3title'] = array("Поле #3 (Строка), заголовок", "");
$L['cfg_extra4title'] = array("Поле #4 (Строка), заголовок", "");
$L['cfg_extra5title'] = array("Поле #5 (Строка), заголовок", "");
$L['cfg_extra6title'] = array("Поле #6 (Поле выбора), заголовок", "");
$L['cfg_extra7title'] = array("Поле #7 (Поле выбора), заголовок", "");
$L['cfg_extra8title'] = array("Поле #8 (Поле выбора), заголовок", "");
$L['cfg_extra9title'] = array("Поле #9 (Длинный текст), заголовок", "");
$L['cfg_extra1tsetting'] = array("Максимум символов в этом поле", "");
$L['cfg_extra2tsetting'] = array("Максимум символов в этом поле", "");
$L['cfg_extra3tsetting'] = array("Максимум символов в этом поле", "");
$L['cfg_extra4tsetting'] = array("Максимум символов в этом поле", "");
$L['cfg_extra5tsetting'] = array("Максимум символов в этом поле", "");
$L['cfg_extra6tsetting'] = array("Значения для поля выбора, через запятую", "");
$L['cfg_extra7tsetting'] = array("Значения для поля выбора, через запятую", "");
$L['cfg_extra8tsetting'] = array("Значения для поля выбора, через запятую", "");
$L['cfg_extra9tsetting'] = array("Максимальная длина текста", "");
$L['cfg_extra1uchange'] = array("Доступно для редактирования в профиле пользователя?", "");
$L['cfg_extra2uchange'] = array("Доступно для редактирования в профиле пользователя?", "");
$L['cfg_extra3uchange'] = array("Доступно для редактирования в профиле пользователя?", "");
$L['cfg_extra4uchange'] = array("Доступно для редактирования в профиле пользователя?", "");
$L['cfg_extra5uchange'] = array("Доступно для редактирования в профиле пользователя?", "");
$L['cfg_extra6uchange'] = array("Доступно для редактирования в профиле пользователя?", "");
$L['cfg_extra7uchange'] = array("Доступно для редактирования в профиле пользователя?", "");
$L['cfg_extra8uchange'] = array("Доступно для редактирования в профиле пользователя?", "");
$L['cfg_extra9uchange'] = array("Доступно для редактирования в профиле пользователя?", "");
$L['cfg_disable_comments'] = array("Отключить комментарии", "");
$L['cfg_disable_forums'] = array("Отключить форум", "");
$L['cfg_disable_pfs'] = array("Отключить Персональные Файловые Архивы", "");
$L['cfg_disable_polls'] = array("Отключить опросы", "");
$L['cfg_disable_pm'] = array("Отключить личные сообщения", "");
$L['cfg_disable_ratings'] = array("Отключить рейтинги", "");
$L['cfg_disable_page'] = array("Отключить страницы", "");
$L['cfg_disable_plug'] = array("Отключить плагины", "");
$L['cfg_trash_prunedelay'] = array("Удалить элементы из корзины через * дней (0 - не удалять никогда)", "");     // New in v110
$L['cfg_trash_comment'] = array("Разрешить использование корзины для комментариев", "");        // New in v110
$L['cfg_trash_forum'] = array("Разрешить использование корзины для форумов", "");        // New in v110
$L['cfg_trash_page'] = array("Разрешить использование корзины для страниц", "");        // New in v110
$L['cfg_trash_pm'] = array("Разрешить использование корзины для личных сообщений", "");        // New in v110
$L['cfg_trash_user'] = array("Разрешить использование корзины для пользователей", "");        // New in v110

$L['cfg_color_group'] = array("Раскрашивать пользователей по группам", "По-умолчанию: Нет, для лучшей производительности");    // New in v175

$L['cfg_ajax'] = array("Включить AJAX", "");  // New in v175
$L['cfg_enablemodal'] = array("Включить модальные окна", "");  // New in v175

$L['cfg_hometitle'] = array("Заголовок главной", "Опционально, для SEO"); // New in v179
$L['cfg_homemetadescription'] = array("Мета описание главной", "Опционально, для SEO"); // New in v179
$L['cfg_homemetakeywords'] = array("Ключевые слова главной", "Опционально, для SEO"); // New in v179

/* ====== HTML Meta ====== */

$L['cfg_defaulttitle'] = array("Заголовок по-умолчанию", "Доступные опции: {MAINTITLE}, {SUBTITLE}");        //Sed 175
$L['cfg_indextitle'] = array("Заголовок для главной", "Доступные опции: {MAINTITLE}, {SUBTITLE}, {TITLE}");        //Sed 179
$L['cfg_listtitle'] = array("Заголовок для списков страниц", "Доступные опции: {MAINTITLE}, {SUBTITLE}, {TITLE}");        //Sed 175
$L['cfg_pagetitle'] = array("Заголовок для страниц", "Доступные опции: {MAINTITLE}, {SUBTITLE}, {TITLE}, {CATEGORY}");        //Sed 175
$L['cfg_forumstitle'] = array("Заголовок для форумов", "Доступные опции: {MAINTITLE}, {SUBTITLE}, {TITLE}");        //Sed 175
$L['cfg_userstitle'] = array("Заголовок для пользователей", "Доступные опции: {MAINTITLE}, {SUBTITLE}, {TITLE}");        //Sed 175
$L['cfg_pmtitle'] = array("Заголовок для личных сообщений", "Доступные опции: {MAINTITLE}, {SUBTITLE}, {TITLE}");        //Sed 175
$L['cfg_gallerytitle'] = array("Заголовок для галереи", "Доступные опции: {MAINTITLE}, {SUBTITLE}, {TITLE}");        //Sed 175
$L['cfg_pfstitle'] = array("Заголовок для файлового архива", "Доступные опции: {MAINTITLE}, {SUBTITLE}, {TITLE}");        ///Sed 175
$L['cfg_plugtitle'] = array("Заголовок для плагинов", "Доступные опции: {MAINTITLE}, {SUBTITLE}, {TITLE}");        ///Sed 175

/* ====== Rss ====== */

$L['cfg_disable_rss'] = array("Отключить RSS ленты", "");
$L['cfg_disable_rsspages'] = array("Отключить RSS ленту для страниц", "");
$L['cfg_disable_rsscomments'] = array("Отключить RSS ленту для комментариев", "");
$L['cfg_disable_rssforums'] = array("Отключить RSS ленту для форума", "");
$L['cfg_rss_defaultcode'] = array("RSS лента по-умолчанию", "ввести код категории");
$L['cfg_rss_timetolive'] = array("Время кэша для RSS ленты", "в секундах");
$L['cfg_rss_maxitems'] = array("Максимальное количество строк в RSS ленте", "");

$L['adm_help_config_rss'] = "Ссылки для вызова RSS лент:<br />" . $cfg['mainurl'] . "/" . "rss (по-умолчанию, выводятся новости из категории указанной в настройках)<br />" . $cfg['mainurl'] . "/" . "rss/pages?c=XX (XX - код категории, последние страницы из категории)<br />" . $cfg['mainurl'] . "/" . "rss/comments?id=XX (XX - ID страницы, комментарии к странице)<br />" . $cfg['mainurl'] . "/" . "rss/forums (последние посты из всех секций форума)<br />" . $cfg['mainurl'] . "/" . "rss/forums?s=XX (XX - ID секции, последние посты в секции)<br />" . $cfg['mainurl'] . "/" . "rss/forums?q=XX (XX - ID топика, последние посты в топике)<br />" . $cfg['mainurl'] . "/" . "rss/forums?s=XX&q=YY(XX - ID секции, YY - ID топика)";

/* ====== Forums ====== */

$L['adm_diplaysignatures'] = "Показывать подписи";
$L['adm_enablebbcodes'] = "Включить BB-коды";
$L['adm_enablesmilies'] = "Включить смайлы";
$L['adm_enableprvtopics'] = "Разрешить приватные темы";
$L['adm_countposts'] = "Включить счётчик сообщений";
$L['adm_autoprune'] = "Автоматически удалять темы после * дней";
$L['adm_postcounters'] = "Сверить счётчики";
$L['adm_help_forums'] = "Не доступно";
$L['adm_forum_structure'] = "Структура категорий форума";    // New in v11
$L['adm_forum_structure_cat'] = "Структура разделов форума";    // New in v11
$L['adm_help_forums_structure'] = "Недоступно";    // New in v11
$L['adm_defstate'] = "Состояние по умолчанию";    // New in v11
$L['adm_defstate_0'] = "Свёрнуто";    // New in v11
$L['adm_defstate_1'] = "Развёрнуто";    // New in v11
$L['adm_parentcat'] = "Родительский раздел";    // New in v172


/* ====== IP search ====== */

$L['adm_searchthisuser'] = "Поиск этого IP в базе данных пользователей";
$L['adm_dnsrecord'] = "Запись DNS для этого адреса";

/* ====== Smilies ====== */

$L['adm_help_smilies'] = "Недоступно";

/* ====== Directories ====== */

$L['adm_directory'] = "Справочник";
$L['adm_dic_title'] = "Заголовок справочника";
$L['adm_dic_code'] = "Код справочника (имя экстраполя)";
$L['adm_dic_list'] = "Список справочников";
$L['adm_dic_term_list'] = "Список терминов";

$L['adm_dic_add'] = "Добавить новый справочник";
$L['adm_dic_edit'] = "Редактирование справочника";
$L['adm_dic_add_term'] = "Добавить новый термин";
$L['adm_dic_term_title'] = "Название термина";
$L['adm_dic_term_value'] = "Значение термина";
$L['adm_dic_term_defval'] = "Термин по умолчанию?";
$L['adm_dic_term_edit'] = "Редактирование термина из справочника";
$L['adm_dic_children'] = "Дочерний справочник";

$L['adm_dic_mera'] = "Единица измерения";

$L['adm_dic_values'] = "Значения для select, radio, checkbox";

$L['adm_dic_form_title'] = "Заголовок для элемента формы";
$L['adm_dic_form_desc'] = "Подпись для элемента формы";
$L['adm_dic_form_size'] = "Размер текстового поля";
$L['adm_dic_form_maxsize'] = "Максимальный размер поля";
$L['adm_dic_form_cols'] = "Ширина текстового поля";
$L['adm_dic_form_rows'] = "Высота текстового поля";

$L['adm_dic_extra'] = "Экстраполе";
$L['adm_dic_addextra'] = "Добавление экстраполя";
$L['adm_dic_editextra'] = "Редактирование экстраполя";
$L['adm_dic_extra_location'] = "Наименование таблицы";
$L['adm_dic_extra_type'] = "Тип данных поля";
$L['adm_dic_extra_size'] = "Длина поля";

$L['adm_dic_comma_separat'] = "(разделенные через запятую)";

$L['adm_help_dic'] = ""; //Need add

/* ====== Menu manager ====== */

$L['adm_menuitems'] = "Пункты меню";
$L['adm_additem'] = "Добавить пункт";
$L['adm_position'] = "Позиция";
$L['adm_confirm_delete'] = "Вы подтверждаете удаление?";
$L['adm_addmenuitem'] = "Добавление пункта меню";
$L['adm_editmenuitem'] = "Редактирование пункта меню";
$L['adm_parentitem'] = "Родительский пункт";
$L['adm_url'] = "URL адрес";
$L['adm_activity'] = "Активен?";

/* ====== PFS ====== */

$L['adm_gd'] = "Графическая библиотека GD";
$L['adm_allpfs'] = "Все Персональные Файловые Архивы";
$L['adm_allfiles'] = "Все файлы";
$L['adm_thumbnails'] = "Миниатюры";
$L['adm_orphandbentries'] = "Потерянные записи БД";
$L['adm_orphanfiles'] = "Потерянные файлы";
$L['adm_delallthumbs'] = "Удалить все миниатюры";
$L['adm_rebuildallthumbs'] = "Удалить и реконструировать все миниатюры";
$L['adm_help_pfsthumbs'] = "Недоступно";
$L['adm_help_check1'] = "Недоступно";
$L['adm_help_check2'] = "Недоступно";
$L['adm_help_pfsfiles'] = "Недоступно";
$L['adm_help_allpfs'] = "Недоступно"; // и неизвестно, когда будет..
$L['adm_nogd'] = "Графическая библиотека GD не поддерживается на данном хостинге. Система не сможет создать миниатюры для изображений в файловом архиве. Вам необходимо из админпанели пройти по закладке 'Персональный Файловый Архив' и отключить опцию генерации миниатюр.";

/* ====== Pages ====== */

$L['adm_structure'] = "Структура страниц (категорий)";
$L['adm_syspages'] = "Смотреть категорию 'системные'";
$L['adm_help_page'] = "Страницы, которые относятся к категории 'системные', не показываются в публично доступных списках страниц. Это сделано для создания изолированных страниц.";
$L['adm_sortingorder'] = "Порядок сортировки для категорий";
$L['adm_fileyesno'] = "Файл (да/нет)";
$L['adm_fileurl'] = "URL файла";
$L['adm_filesize'] = "Размер файла";
$L['adm_filecount'] = "Счётчик загрузок файла";

$L['adm_tpl_mode'] = "Использовать TPL";
$L['adm_tpl_empty'] = "По умолчанию";
$L['adm_tpl_forced'] = "Такой же, как у";
$L['adm_tpl_parent'] = "Такой же, как у родительского раздела";

$L['adm_enablecomments'] = "Включить комментарии";   // New v173
$L['adm_enableratings'] = "Включить рейтинги";     // New v173

/* ====== Polls ====== */

$L['adm_help_polls'] = "Как только создана тема нового опроса, нажми 'Редактировать', чтобы добавить варианты ответов для этого опроса.<br />Кнопка 'Удалить' удалит выбранный опрос, его опции, и все относящиеся к нему записи о голосовании.<br />Кнопка 'Сбросить' удалит все голоса для выбранного опроса, не удаляя сам опрос и его опции.<br />Кнопка 'Переместить наверх' изменит дату создания опроса на сегодняшнюю, и таким образом сделает опрос 'текущим', т.е. первым в списке.";
$L['adm_poll_title'] = "Заголовок опроса";

/* ====== Statistics ====== */

$L['adm_phpver'] = "Установленная версия PHP";
$L['adm_zendver'] = "Установленная версия Zend";
$L['adm_interface'] = "Интерфейс между веб-сервером и PHP";
$L['adm_os'] = "Операционная система";
$L['adm_clocks'] = "Часы";
$L['adm_time1'] = "#1: Время на сервере";
$L['adm_time2'] = "#2: GMT время, возвращённое сервером";
$L['adm_time3'] = "#3: GMT время + смещение времени сервера (Транслируемое Seditio)";
$L['adm_time4'] = "#4: Локальное время, установленное в вашем профиле";
$L['adm_help_versions'] = "Установи нужный часовой пояс сервера <a href=\"admin.php?m=config&amp;n=edit&amp;o=core&amp;p=time\">здесь</a>, чтобы время №3 отражалось правильно.<br />Время №4 зависит от установленного в вашем профиле часового пояса.<br />Значения времени #1 и #2 игнорируются Seditio.";
$L['adm_log'] = "Системный лог";
$L['adm_infos'] = "Информация";
$L['adm_versiondclocks'] = "Версии и часы";
$L['adm_checkcoreskins'] = "Проверить файлы ядра и скины";
$L['adm_checkcorenow'] = "Проверить файлы ядра сейчас!";
$L['adm_checkingcore'] = "Идёт проверка файлов ядра...";
$L['adm_checkskins'] = "Проверить наличие всех файлов в скинах";
$L['adm_checkskin'] = "Проверить наличие TPL файлов для скина";
$L['adm_checkingskin'] = "Идёт проверка скина...";
$L['adm_hits'] = "Просмотров";
$L['adm_check_ok'] = "Ok";
$L['adm_check_missing'] = "Отсутствует";
$L['adm_ref_lowhits'] = "Удалить записи с количеством просмотров меньше 5";
$L['adm_maxhits'] = "Максимум просмотров был зафиксирован %1\$s, в этот день было показано %2\$s страниц."; // Новое в v102
$L['adm_byyear'] = "По годам";
$L['adm_bymonth'] = "По месяцам";
$L['adm_byweek'] = "По неделям";

/* ====== Ratings ====== */

$L['adm_ratings_totalitems'] = "Всего оценённых страниц";
$L['adm_ratings_totalvotes'] = "Всего голосов";
$L['adm_help_ratings'] = "Чтобы обнулить рейтинг, просто удалите его. Он будет воссоздан с первым новым голосом.";

/* ====== Trash can ====== */

$L['adm_help_trashcan'] = "Здесь находится список элементов сайта, недавно удаленных модераторами и пользователями.<br />Обратите внимание, что восстановление темы форума восстанавливает также все сообщения из этой темы.<br />Также, восстановление сообщения в удаленной теме восстанавливает всю тему (если она доступна), и все сообщения в этой теме.<br />&nbsp;<br />Окончательное удаление: удаление элемента без возможности восстановления.<br />Восстановление: возвращает удалённый элемент на сайт."; // New in v110

/* ====== Users ====== */

$L['adm_defauth_members'] = "Права по умолчанию для пользователей";
$L['adm_deflock_members'] = "Маска-замок для пользователей";
$L['adm_defauth_guests'] = "Права по умолчанию для посетителей";
$L['adm_deflock_guests'] = "Маска-замок для гостей";
$L['adm_rightspergroup'] = "Права по группам";
$L['adm_copyrightsfrom'] = "Установить набор прав как в группе";
$L['adm_maxsizesingle'] = "Максимальный размер одного файла для Файлового Архива (KB)";
$L['adm_maxsizeallpfs'] = "Суммарный размер всех файлов в Файловом Архиве (KB)";
$L['adm_rights_allow10'] = "Разрешено";
$L['adm_rights_allow00'] = "Запрещено";
$L['adm_rights_allow11'] = "Разрешено и закрыто в целях безопасности";
$L['adm_rights_allow01'] = "Запрещено и закрыто в целях безопасности";
$L['adm_color'] = "Цвет группы";   // New in v175

/* ====== Plugins ====== */

$L['adm_extplugins'] = "Расширенные плагины";
$L['adm_present'] = "Присутствует";
$L['adm_missing'] = "Отсутствует";
$L['adm_paused'] = "Приостановлен";
$L['adm_running'] = "Работает";
$L['adm_partrunning'] = "Работает частично";
$L['adm_notinstalled'] = "Не установлен";

$L['adm_opt_installall'] = "Установить";
$L['adm_opt_installall_explain'] = "Эта опция установит или перезапустит все модули плагина.";
$L['adm_opt_uninstallall'] = "Отключить";
$L['adm_opt_uninstallall_explain'] = "Эта опция отключит все модули плагина (файлы плагина при этом физически не удаляются).";
$L['adm_opt_pauseall'] = "Приостановить";
$L['adm_opt_pauseall_explain'] = "Эта опция приостановит работу всех модулей плагина.";
$L['adm_opt_unpauseall'] = "Возобновить";
$L['adm_opt_unpauseall_explain'] = "Эта опция возобновит работу всех модулей плагина.";

/* ====== Private messages ====== */

$L['adm_pm_totaldb'] = "Личных сообщений в базе данных";
$L['adm_pm_totalsent'] = "Общее количество отправленных на этот момент личных сообщений";
