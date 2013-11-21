<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
http://www.neocrome.net
http://www.seditio.org
[BEGIN_SED]
File=system/functions/database.mysqli.php
Version=175
Updated=2012-may-24
Type=Core
Author=Neocrome
Description=Functions MySQLi driver
[END_SED]
==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

/* ------------------ */

if (function_exists('mysqli_set_charset') === false) {

   function mysqli_set_charset($link_identifier, $charset){
      return mysqli_query($link_identifier, 'SET NAMES "'.$charset.'"');
   }
}

/* ------------------ */

function sed_sql_affectedrows($conn_id = null) {  
  global $connection_id;   
  return is_null($conn_id) ? mysqli_affected_rows($connection_id) : mysqli_affected_rows($conn_id);
}

/* ------------------ */

function sed_sql_close($conn_id = null) { 
  global $connection_id; 
  return is_null($conn_id) ? mysqli_close($connection_id) : mysqli_close($conn_id); 
}

/* ------------------ */

function sed_sql_connect($host, $user, $pass, $db) {
	$conn_id = @mysqli_connect($host, $user, $pass) or sed_diefatal('Could not connect to database !<br />Please check your settings in the file datas/config.php<br />'.'MySQL error : '.sed_sql_error());
	$select = @mysqli_select_db($conn_id, $db) or sed_diefatal('Could not select the database !<br />Please check your settings in the file datas/config.php<br />'.'MySQL error : '.sed_sql_error());
	return($conn_id);
}

/* ------------------ */

function sed_sql_errno($conn_id = null) { 
  global $connection_id; 
  return is_null($conn_id) ? mysqli_errno($connection_id) : mysqli_errno($conn_id); 
}

/* ------------------ */

function sed_sql_error($conn_id = null) { 
  global $connection_id; 
  return is_null($conn_id) ? mysqli_error($connection_id) : mysqli_error($conn_id); 
}

/* ------------------ */

function sed_sql_fetcharray($res) {   
  return (mysqli_fetch_array($res)); 
}

/* ------------------ */

function sed_sql_fetchassoc($res) {     
  return (mysqli_fetch_assoc($res)); 
}

/* ------------------ */

function sed_sql_fetchrow($res) {     
  return (mysqli_fetch_row($res)); 
}

/* ------------------ */

function sed_sql_freeresult($res) { 
  return (mysqli_free_result($res)); 
}

/* ------------------ */

function sed_sql_insertid($conn_id = null) { 
  global $connection_id; 
  return is_null($conn_id) ? mysqli_insert_id($connection_id) : mysqli_insert_id($conn_id);   
}

/* ------------------ */

function sed_sql_listtables($res, $conn_id = null) { 
 global $connection_id, $cfg;
 $conn_id = is_null($conn_id) ? $connection_id : $conn_id; 
 $res = mysqli_query($conn_id, "SHOW TABLES FROM ".$res." "); 
 return($res);
}  

/* ------------------ */

function sed_sql_numrows($res) {  
  return (mysqli_num_rows($res)); 
}

/* ------------------ */

function sed_sql_prep($res, $conn_id = null) {
  global $connection_id; 
  return is_null($conn_id) ? mysqli_real_escape_string($connection_id, $res) : mysqli_real_escape_string($conn_id, $res);  
}

/* ------------------ */

function sed_sql_query($query, $halterr = 'TRUE', $conn_id = null)
	{
	global $sys, $cfg, $usr, $connection_id;

	$conn_id = is_null($conn_id) ? $connection_id : $conn_id; 

	$sys['qcount']++;
	$xtime = microtime();
	if ($halterr)
    { $result = mysqli_query($conn_id, $query) OR sed_diefatal('SQL error : '.sed_sql_error()); }
  else
    { $result = mysqli_query($conn_id, $query); }
    
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

function sed_sql_result($res, $row, $col=0) {  
  mysqli_data_seek($res, $row);
  $result = mysqli_fetch_array($res); 
  return($result[$col]);
}

/* ------------------ */

 function sed_sql_set_charset($link_identifier, $charset)
	{ return (mysqli_set_charset($link_identifier, $charset)); }

/* ------------------ */

function sed_sql_rowcount($table)
	{
	$sqltmp = sed_sql_query("SELECT COUNT(*) FROM $table");
	return(sed_sql_result($sqltmp, 0, "COUNT(*)"));
	}

?>