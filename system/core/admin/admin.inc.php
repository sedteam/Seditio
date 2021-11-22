<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
https://seditio.org
[BEGIN_SED]
File=admin.inc.php
Version=178
Updated=2021-jun-17
Type=Core
Author=Neocrome
Description=Administration panel
[END_SED]
==================== */

if (!defined('SED_CODE') || !defined('SED_ADMIN')) { die('Wrong URL.'); }

$c = sed_import('c','G','TXT');
$id = sed_import('id','G','TXT');
$m = sed_import('m','G','ALP', 24);
$mn = sed_import('mn','G','ALP', 24);
$po = sed_import('po','G','TXT');
$p = sed_import('p','G','TXT');
$l = sed_import('l','G','TXT');
$o = sed_import('o','P','TXT');
$w = sed_import('w','P','TXT');
$u = sed_import('u','P','TXT');
$s = sed_import('s','G','ALP', 24);
$msg = sed_import('msg', 'G', 'ALP');

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('admin', 'any');
sed_block($usr['auth_read']);

$enabled[0] = $L['Disabled'];
$enabled[1] = $L['Enabled'];

/* === Hook for the plugins === */
$extp = sed_getextplugins('admin.main');
if (is_array($extp))
	{ foreach($extp as $k => $pl) { include(SED_ROOT . '/plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }

$sys['inc'] = (empty($m)) ? 'admin.home' : "admin.$m";
$sys['inc'] = (empty($s)) ? $sys['inc'] : $sys['inc'].".$s";
$sys['inc'] = SED_ROOT . '/system/core/admin/'.$sys['inc'].'.inc.php';

if (!file_exists($sys['inc']))
	{ sed_die(); }

$allow_img['0']['0'] = "<img src=\"system/img/admin/deny.gif\" alt=\"\" />";
$allow_img['1']['0'] = "<img src=\"system/img/admin/allow.gif\" alt=\"\" />";
$allow_img['0']['1'] = "<img src=\"system/img/admin/deny_locked.gif\" alt=\"\" />";
$allow_img['1']['1'] = "<img src=\"system/img/admin/allow_locked.gif\" alt=\"\" />";

$t = new XTemplate(sed_skinfile('admin.nav', true)); 

// Options menu

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = sed_auth('admin', 'a');

if (sed_auth('admin', 'a', 'A'))
	{    
		$sql = sed_sql_query("SELECT DISTINCT(config_cat) FROM $db_config WHERE config_owner='core'");			
		$config_menu .= "<ul>";
		
		while ($row = sed_sql_fetchassoc($sql))
			{
			$config_menu .= "<li>";
			$code = "core_".$row['config_cat'];
			$config_menu_class = ($row['config_cat'] == $p) ? "current" : '';
			$config_menu .= "<a href=\"".sed_url("admin", "m=config&n=edit&o=core&p=".$row['config_cat'])."\" class=\"".$config_menu_class."\">".$L[$code]."</a>";
			$config_menu .= "</li>";
			}
			
		$config_menu .= "</ul>";		
		
		$t -> assign(array( 
		    "ADMINMENU_CONFIG_URL" => sed_url('admin', "m=config"), 
		    "ADMINMENU_CONFIG_URL_CLASS" => ($m == "config") ? "current" : "",
		    "ADMINMENU_CONFIG" => $config_menu
		));		
		$t -> parse("ADMINMENU.CONFIG_MENU"); 
	}

// Pages menu 

$page_menu .= "<ul>";
$page_menu .= ($mn == 'queue') ? "<li>".sed_linkif(sed_url("admin", "m=page&mn=queue"), $L['adm_valqueue'], sed_auth('admin', 'any', 'A'), 'current')."</li>" : "<li>".sed_linkif(sed_url("admin", "m=page&mn=queue"), $L['adm_valqueue'], sed_auth('admin', 'any', 'A'))."</li>";
$page_menu .= ($m == 'page' && $s == 'add') ? "<li>".sed_linkif(sed_url("admin", "m=page&s=add"), $L['addnewentry'], sed_auth('page', 'any', 'A'), 'current')."</li>" : "<li>".sed_linkif(sed_url("admin", "m=page&s=add"), $L['addnewentry'], sed_auth('page', 'any', 'A'))."</li>";
$page_menu .= ($m == 'page' && $s == 'manager') ? "<li>".sed_linkif(sed_url("admin", "m=page&s=manager"), $L['adm_pagemanager'], sed_auth('page', 'any', 'A'), 'current')."</li>" : "<li>".sed_linkif(sed_url("admin", "m=page&s=manager"), $L['adm_pagemanager'], sed_auth('page', 'any', 'A'))."</li>";

if (sed_auth('admin', 'a', 'A'))
{
	$page_menu .= ($mn == 'catorder') ? "<li>".sed_linkif(sed_url("admin", "m=page&mn=catorder"), $L['adm_sortingorder'], sed_auth('admin', 'a', 'A'), 'current')."</li>" : "<li>".sed_linkif(sed_url("admin", "m=page&mn=catorder"), $L['adm_sortingorder'], sed_auth('admin', 'a', 'A'))."</li>"; 
	$page_menu .= ($mn == 'structure') ? "<li>".sed_linkif(sed_url("admin", "m=page&mn=structure"), $L['adm_structure'], sed_auth('admin', 'a', 'A'), 'current')."</li>" : "<li>".sed_linkif(sed_url("admin", "m=page&mn=structure"), $L['adm_structure'], sed_auth('admin', 'a', 'A'))."</li>";
}

$page_menu .= "</ul>";

$t -> assign(array( 
    "ADMINMENU_PAGE_URL" => sed_url('admin', "m=page"),
    "ADMINMENU_PAGE_URL_CLASS" => ($m == "page" || $m == "pageadd") ? "current" : "",
    "ADMINMENU_PAGE" => $page_menu
));    
     
$t -> parse("ADMINMENU.PAGE_MENU"); 


// Forums menu & other

if (sed_auth('admin', 'a', 'A'))
{
  $forums_menu .= "<ul class=\"arrow_list\">";
  $forums_menu .= ($m == "forums" && empty($s)) ? "<li>".sed_linkif(sed_url("admin", "m=forums"), $L['adm_forum_structure_cat'], sed_auth('admin', 'a', 'A'), 'current')."</li>" : "<li>".sed_linkif(sed_url("admin", "m=forums"), $L['adm_forum_structure_cat'], sed_auth('admin', 'a', 'A'))."</li>";
  $forums_menu .= ($s == "structure") ? "<li>".sed_linkif(sed_url("admin", "m=forums&s=structure"), $L['adm_forum_structure'], sed_auth('admin', 'a', 'A'), 'current')."</li>" : "<li>".sed_linkif(sed_url("admin", "m=forums&s=structure"), $L['adm_forum_structure'], sed_auth('admin', 'a', 'A'))."</li>";
  $forums_menu .= "</ul>";

  $t -> assign(array( 
      "ADMINMENU_FORUMS_URL" => sed_url('admin', "m=forums"),
      "ADMINMENU_FORUMS_URL_CLASS" => ($m == "forums") ? "current" : "",
      "ADMINMENU_FORUMS" => $forums_menu
  )); 
  
  $t -> parse("ADMINMENU.FORUMS_MENU");

  $t -> assign(array( 
      "ADMINMENU_USERS_URL" => sed_url('admin', "m=users"),
      "ADMINMENU_USERS_URL_CLASS" => ($m == 'users') ? 'current' : ''    
  ));
  
  $t -> parse("ADMINMENU.USERS_MENU");
  
  $t -> assign(array(    
      "ADMINMENU_PLUGINS_URL" => sed_url('admin', "m=plug"),
      "ADMINMENU_PLUGINS_URL_CLASS" => ($m == 'plug') ? 'current' : ''     
  ));  

  $t -> parse("ADMINMENU.PLUGINS_MENU");

  $t -> assign(array(   
      "ADMINMENU_LOG_URL" => sed_url('admin', "m=log"),
      "ADMINMENU_LOG_URL_CLASS" => ($m == 'log') ? 'current' : ''      
  ));
  
  $t -> parse("ADMINMENU.LOG_MENU");

  $t -> assign(array(   
      "ADMINMENU_TRASHCAN_URL" => sed_url('admin', "m=trashcan"),
      "ADMINMENU_TRASHCAN_URL_CLASS" => ($m == 'trashcan') ? 'current' : ''      
  ));  

  $t -> parse("ADMINMENU.TRASHCAN_MENU");

  $t -> assign(array( 
      "ADMINMENU_TOOLS_URL" => sed_url('admin', "m=tools"),
      "ADMINMENU_TOOLS_URL_CLASS" => ($m == 'tools') ? 'current' : ''    
  ));
  
  $t -> parse("ADMINMENU.TOOLS_MENU");

}

$t -> assign(array( 
    "ADMINMENU_URL" => sed_url('admin'), 
    "ADMINMENU_URL_CLASS" => (empty($m)) ? 'current' : ''     
)); 
 
$t -> parse("ADMINMENU"); 

$adminmenu = $t -> text("ADMINMENU");

require($sys['inc']);

$adminmain .= (empty($adminhelp)) ? '' : "<div class=\"content-box\"><div class=\"content-box-header\"><h3>".$L['Help']."</h3></div>";
$adminmain .= (empty($adminhelp)) ? '' : "<div class=\"content-box-content\">".$adminhelp."</div></div>";

$out['subtitle'] = $L['Administration'];

/**/
$title_tags[] = array('{MAINTITLE}', '{SUBTITLE}', '{TITLE}');
$title_tags[] = array('%1$s', '%2$s', '%3$s');
$title_data = array($cfg['maintitle'], $cfg['subtitle'], $out['subtitle']);
$out['subtitle'] = sed_title('admintitle', $title_tags, $title_data);
/**/

require(SED_ROOT . "/system/header.php");

$t = new XTemplate(sed_skinfile("admin", true));

$t->assign(array(
	"ADMIN_TITLE" => sed_build_adminsection($adminpath),
	"ADMIN_BREADCRUMBS" => sed_build_adminsection($adminpath, 'breadcrumbs', '<i class="fa fa-lg fa-home"></i> '),
	"ADMIN_SUBTITLE" => $adminsubtitle,
	"ADMIN_MENU" => $adminmenu,
	"ADMIN_URL" => sed_url('admin'),
	"ADMIN_MAIN" => $adminmain,
		));
		
$t->assign(array (
	"ADMIN_USER_NAME" => $usr['name'],
	"ADMIN_USER_LOGINOUT" => $out['loginout']
		));

$t->parse("MAIN.ADMIN_USER");		
    
if (!empty($msg) || !empty($adminwarnings)) 
  {
  require(SED_ROOT . "/system/lang/$lang/message.lang.php");
  	
  $msg_type = (array_key_exists($msg, $cfg['msgtype'])) ? $cfg['msgtype_name'][$cfg['msgtype'][$msg]] : $cfg['msgtype_name']['i'];	

  $t->assign(array(
    "ADMIN_MSG_CLASS" => (!empty($adminwarnings)) ? $cfg['msgtype_name']['a'] : $msg_type,
    "ADMIN_MSG_TITLE" => (!empty($adminwarnings)) ? $L['adm_warnings'] : $L["msg".$msg."_0"],
    "ADMIN_MSG_TEXT" => (!empty($adminwarnings)) ? $adminwarnings : $L["msg".$msg."_1"]
  ));
  
  $t->parse("MAIN.ADMIN_MESSAGE");
  }   

/* === Hook for the plugins === */
$extp = sed_getextplugins('admin.tags');
if (is_array($extp))
	{ foreach($extp as $k => $pl) { include(SED_ROOT . '/plugins/'.$pl['pl_code'].'/'.$pl['pl_file'].'.php'); } }
/* ===== */

$t->parse("MAIN");
$t->out("MAIN");

require(SED_ROOT . "/system/footer.php");

?>
