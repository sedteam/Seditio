<?PHP

define('SED_CODE', TRUE);

require('system/functions.php');

/*
require('datas/config.php');
require('system/common.php');
*/

$cfg['font_dir'] = "datas/fonts/";

$captcha_code = sed_generate_code();

sed_captcha_image($captcha_code);

?>
