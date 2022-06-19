<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=admin.banlist.inc.php
Version=178
Updated=2022-jun-12
Type=Core.admin
Author=Neocrome
Description=Banlist
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) { die('Wrong URL.'); }

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('users', 'a');
sed_block($usr['isadmin']);

// ---------- Breadcrumbs
$urlpaths = array();
$urlpaths[sed_url("admin", "m=users")] = $L['Users'];
$urlpaths[sed_url("admin", "m=banlist")] = $L['Banlist'];
$admintitle = $L['Banlist'];

$adminhelp = $L['adm_help_banlist'];

if ($a=='update')
	{
	sed_check_xg();
	$id = sed_import('id', 'G', 'INT');
	$rbanlistip = sed_import('rbanlistip', 'P', 'TXT');
	$rbanlistemail = sed_sql_prep(sed_import('rbanlistemail', 'P', 'TXT'));
	$rbanlistreason = sed_sql_prep(sed_import('rbanlistreason', 'P', 'TXT'));
	$sql = sed_sql_query("UPDATE $db_banlist SET banlist_ip='$rbanlistip', banlist_email='$rbanlistemail', banlist_reason='$rbanlistreason' WHERE banlist_id='$id'");
	sed_redirect(sed_url("admin", "m=banlist", "", true));
	exit;
	}
elseif ($a=='add')
	{
	sed_check_xg();
	$nbanlistip = sed_import('nbanlistip', 'P', 'TXT');
	$nbanlistemail = sed_sql_prep(sed_import('nbanlistemail', 'P', 'TXT'));
	$nbanlistreason = sed_sql_prep(sed_import('nbanlistreason', 'P', 'TXT'));
	$nexpire = sed_import('nexpire', 'P', 'INT');

	$nbanlistip_cnt = explode('.', $nbanlistip);
	$nbanlistip = (count($nbanlistip_cnt)==4) ? $nbanlistip : '';

	if ($nexpire>0)
		{ $nexpire += $sys['now']; }
	$sql = sed_sql_query("INSERT INTO $db_banlist (banlist_ip, banlist_email, banlist_reason, banlist_expire) VALUES ('$nbanlistip', '$nbanlistemail', '$nbanlistreason', ".(int)$nexpire.")");
	sed_log("Banlist : New line for IP ".$nbanlistip." / Email ".$nbanlistemail,'adm');
	sed_redirect(sed_url("admin", "m=banlist", "", true));
	exit;
	}

elseif ($a=='delete')
	{
	sed_check_xg();
	$id = sed_import('id', 'G', 'INT');
	$sql = sed_sql_query("DELETE FROM $db_banlist WHERE banlist_id='$id'");
	sed_log("Banlist : Deleted line ".$id,'adm'); 
	sed_redirect(sed_url("admin", "m=banlist", "", true));
	exit;
	}

$t = new XTemplate(sed_skinfile('admin.banlist', true));

$sql = sed_sql_query("SELECT * FROM $db_banlist ORDER by banlist_expire DESC");

while ($row = sed_sql_fetchassoc($sql))
	{
	$banlist_id = $row['banlist_id'];
	$banlist_ip = $row['banlist_ip'];
	$banlist_email = $row['banlist_email'];
	$banlist_reason = $row['banlist_reason'];
	$banlist_expire = $row['banlist_expire'];

	$t->assign(array(
		"BANLIST_EDIT_ID" => $banlist_id,
		"BANLIST_EDIT_SEND_URL" => sed_url("admin", "m=banlist&a=update&id=".$banlist_id."&".sed_xg()),
		"BANLIST_EDIT_DELETE_URL" => sed_url("admin", "m=banlist&a=delete&id=".$banlist_id."&".sed_xg()),
		"BANLIST_EDIT_EXPIRE" => ($banlist_expire > 0) ? date($cfg['dateformat'], $banlist_expire)." GMT" : $L['adm_neverexpire'], 
		"BANLIST_EDIT_IP" => sed_textbox('rbanlistip', $banlist_ip, 14, 16),
		"BANLIST_EDIT_EMAIL_MASK" => sed_textbox('rbanlistemail', $banlist_email, 10, 64),
		"BANLIST_EDIT_REASON" => sed_textbox('rbanlistreason', $banlist_reason, 18, 64)
	));

	$t -> parse("ADMIN_BANLIST.BANLIST_EDIT_LIST");

	}

	$nexpire = "<select name=\"nexpire\" size=\"1\">";
	$nexpire .= "<option value=\"3600\">1 hour</option><option value=\"7200\">2 hours</option><option value=\"14400\">4 hours</option><option value=\"28800\">8 hours</option>";
	$nexpire .= "<option value=\"57600\">16 hours</option><option value=\"86400\">1 day</option><option value=\"172800\">2 days</option><option value=\"345600\">4 days</option>";
	$nexpire .= "<option value=\"604800\">1 week</option><option value=\"1209600\">2 weeks</option><option value=\"1814400\">3 weeks</option><option value=\"2592000\">1 month</option>";
	$nexpire .= "<option value=\"0\" selected=\"selected\">".$L['adm_neverexpire']."</option></select>";

	$t->assign(array(
		"BANLIST_ADD_SEND_URL" => sed_url("admin", "m=banlist&a=add&".sed_xg()),
		"BANLIST_ADD_NEXPIRE" => $nexpire,
		"BANLIST_ADD_IP" => sed_textbox('nbanlistip', $nbanlistip, 14, 16),
		"BANLIST_ADD_EMAIL_MASK" => sed_textbox('nbanlistemail', $nbanlistemail, 24, 64),
		"BANLIST_ADD_REASON" => sed_textbox('nbanlistreason', $nbanlistreason, 48, 64)
	));
	
$t->assign("ADMIN_BANLIST_TITLE", $admintitle);	

$t -> parse("ADMIN_BANLIST");

$adminmain .= $t -> text("ADMIN_BANLIST"); 

?>
