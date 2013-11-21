<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=system/functions/database.lib.php
Version=173
Updated=2012-sep-23
Type=Core
Author=Neocrome
Description=Functions
[END_SED]
==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

/* ------------------ */

if (!function_exists('mysql_set_charset'))
  {
  function mysql_set_charset($charset, $link_identifier = null)
    {
    if ($link_identifier == null)
      { return mysql_query('SET CHARACTER SET "'.$charset.'"'); }
    else
      { return mysql_query('SET CHARACTER SET "'.$charset.'"', $link_identifier); }
    }
  }

/* ------------------ */

function sed_sql_affectedrows()
	{ return (mysql_affected_rows()); }

/* ------------------ */

function sed_sql_close($id='')
	{ return(mysql_close($id)); }

/* ------------------ */

function sed_sql_connect($host, $user, $pass, $db)
	{
	$connection = @mysql_connect($host, $user, $pass) or sed_diefatal('Could not connect to database !<br />Please check your settings in the file datas/config.php<br />'.'MySQL error : '.sed_sql_error());
	$select = @mysql_select_db($db, $connection) or sed_diefatal('Could not select the database !<br />Please check your settings in the file datas/config.php<br />'.'MySQL error : '.sed_sql_error());
	return($connection);
	}

/* ------------------ */

function sed_sql_errno()
	{ return(mysql_errno()); }

/* ------------------ */

function sed_sql_error()
	{ return(mysql_error()); }

/* ------------------ */

function sed_sql_fetcharray($res)
	{ return (mysql_fetch_array($res)); }

/* ------------------ */

function sed_sql_fetchassoc($res)
   { return (mysql_fetch_assoc($res)); }

/* ------------------ */

function sed_sql_fetchrow($res)
	{ return (mysql_fetch_row($res)); }

/* ------------------ */

function sed_sql_freeresult($res)
	{ return (mysql_free_result($res)); }

/* ------------------ */

function sed_sql_insertid()
	{ return (mysql_insert_id()); }

/* ------------------ */

function sed_sql_listtables($res)
	{ return (mysql_list_tables($res)); }

/* ------------------ */

function sed_sql_numrows($res)
	{ return (mysql_num_rows($res)); }

/* ------------------ */

function sed_sql_prep($res)
	{
	$res = mysql_real_escape_string($res);
	return($res);
	}

/* ------------------ */

function sed_sql_query($query, $halterr = 'TRUE')
	{
	global $sys, $cfg, $usr;
	$sys['qcount']++;
	$xtime = microtime();
	if ($halterr)
    { $result = mysql_query($query) OR sed_diefatal('SQL error : '.sed_sql_error()); }
  else
    { $result = mysql_query($query); }
    
	$ytime = microtime();
	$xtime = explode(' ',$xtime);
	$ytime = explode(' ',$ytime);
	$sys['tcount'] = $sys['tcount'] + $ytime[1] + $ytime[0] - $xtime[1] - $xtime[0];
	if ($cfg['devmode'])
		{
		$sys['devmode']['queries'][] = array ($sys['qcount'], $ytime[1] + $ytime[0] - $xtime[1] - $xtime[0], $query);
		$sys['devmode']['timeline'][] = $xtime[1] + $xtime[0] - $sys['starttime'];
		}
	return($result);
	}

/* ------------------ */

function sed_sql_result($res, $row, $col)
	{ return (mysql_result($res, $row, $col)); }

/* ------------------ */

 function sed_sql_set_charset($link_identifier, $charset)
	{ return (mysql_set_charset($charset, $link_identifier)); }

/* ------------------ */

function sed_sql_rowcount($table)
	{
	$sqltmp = sed_sql_query("SELECT COUNT(*) FROM $table");
	return(mysql_result($sqltmp, 0, "COUNT(*)"));
	}

?>