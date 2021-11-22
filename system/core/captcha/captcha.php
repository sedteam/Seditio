<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
https://seditio.org
[BEGIN_SED]
File=captcha.php
Version=178
Updated=2021-jun-17
Type=Core
Author=Amro
Description=Captcha generate
[END_SED]
==================== */

if (!defined('SED_CODE')) exit();

require(SED_ROOT . '/system/functions.php');

/*
require(SED_ROOT . '/datas/config.php');
require(SED_ROOT . '/system/common.php');
*/

$cfg['font_dir'] = "datas/fonts/";

$captcha_code = sed_generate_code();

sed_captcha_image($captcha_code);

?>
