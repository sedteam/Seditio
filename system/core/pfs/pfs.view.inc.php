<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=pfs.view.inc.php
Version=180
Updated=2025-jan-25
Type=Core
Author=Seditio Team
Description=PFS
[END_SED]
==================== */

if (!defined('SED_CODE')) {
  die('Wrong URL.');
}

$v = sed_import('v', 'G', 'TXT');

$error = FALSE;


$sql = sed_sql_query("SELECT pfs_id FROM $db_pfs WHERE pfs_file='$v' LIMIT 1");

if (sed_sql_numrows($sql) > 0) {
  $row = sed_sql_fetchassoc($sql);
  $imgpath = $cfg['pfs_dir'] . $v;
} else {
  $error = TRUE;
}

$dotpos = mb_strrpos($imgpath, ".") + 1;
$f_extension = mb_strtolower(mb_substr($imgpath, $dotpos, 4));

if (!empty($v) && file_exists($imgpath) && in_array($f_extension, $cfg['gd_supported']) && !$error) {
  echo ("<html><head>
	<meta name=\"title\" content=\"" . $cfg['maintitle'] . "\" />
	<meta name=\"description\" content=\"" . $cfg['maintitle'] . "\" />
	<meta name=\"generator\" content=\"Seditio Copyright Neocrome & Seditio Team https://seditio.org\" />
	<meta http-equiv=\"content-type\" content=\"text/html; charset=" . $cfg['charset'] . "\" />
	<meta http-equiv=\"pragma\" content\"=no-cache\" />
	<meta http-equiv=\"cache-control\" content=\"no-cache\" />
  <script language='javascript'>
  var arrTemp=self.location.href.split(\"?\");
  var picUrl = (arrTemp.length>0)?arrTemp[1]:\"\";
  
  function adapt() {
  iWidth = document.body.clientWidth;
  iHeight = document.body.clientHeight;
  iWidth = document.images[0].width - iWidth;
  iHeight = document.images[0].height - iHeight;

  window.resizeBy(iWidth, iHeight);
  var ptop=(window.screen.height-document.images[0].height)/2;
  var pleft=(window.screen.width-document.images[0].width)/2;
  window.moveTo(pleft,ptop);
  self.focus();
  };
 </script> </head><body onload='adapt();'>");

  echo ("<img src=\"" . $imgpath . "\" alt=\"\" onClick=\"window.close()\"/>");
  echo ("</body></html>");

  $sql = sed_sql_query("UPDATE $db_pfs SET pfs_count=pfs_count+1 WHERE pfs_id='" . $row['pfs_id'] . "' LIMIT 1");
} else {
  echo ("Wrong URL.");
}
