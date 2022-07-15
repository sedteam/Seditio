<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=index.inc.php
Version=179
Updated=2022-jul-15
Type=Core
Author=Seditio Team
Description=Home page
[END_SED]
==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

/* === Hook === */
$extp = sed_getextplugins('index.first');
if (is_array($extp))
	{ foreach($extp as $k => $pl) { include(SED_ROOT . '/plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
/* ===== */

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('index', 'a');

/* === Hook === */
$extp = sed_getextplugins('index.main');
if (is_array($extp))
	{ foreach($extp as $k => $pl) { include(SED_ROOT . '/plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
/* ===== */


require(SED_ROOT . "/system/header.php");

$mskin = sed_skinfile('index');
$t = new XTemplate($mskin);

/* === Hook === */
$extp = sed_getextplugins('index.tags');
if (is_array($extp))
	{ foreach($extp as $k => $pl) { include(SED_ROOT . '/plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
/* ===== */    

$t->parse("MAIN");
$t->out("MAIN");

require(SED_ROOT . "/system/footer.php");

?>
