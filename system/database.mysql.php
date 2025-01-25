<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=system/database.mysql.php
Version=180
Updated=2025-jan-25
Type=Core
Author=Seditio Team
Description=Functions
[END_SED]
==================== */

if (!defined('SED_CODE')) {
	die('Wrong URL.');
}

/* ------------------ */

if (!function_exists('mysql_set_charset')) {
	function mysql_set_charset($charset, $conn_id = null)
	{
		return ($conn_id == null) ? mysql_query('SET CHARACTER SET "' . $charset . '"') : mysql_query('SET CHARACTER SET "' . $charset . '"', $conn_id);
	}
}

/** 
 * Gets the number of affected rows in a previous MySQL 
 * 
 * @return int Number of rows affected by the last INSERT, UPDATE, REPLACE or DELETE query.  
 */
function sed_sql_affectedrows($conn_id = null)
{
	global $connection_id;
	return is_null($conn_id) ? mysql_affected_rows($connection_id) : mysql_affected_rows($conn_id);
}

/** 
 * Сloses the non-persistent connection to the MySQL server that's associated with the specified link identifier.
 * 
 * @param resource $conn_id The MySQL connection link identifier   
 * @return bool Returns TRUE on success or FALSE on failure.  
 */
function sed_sql_close($conn_id = null)
{
	global $connection_id;
	return is_null($conn_id) ? mysql_close($connection_id) : mysql_close($conn_id);
}

/** 
 * Open a connection to a MySQL Server & Select a MySQL database
 * 
 * @param string $host The MySQL server. It can also include a port number. e.g. "hostname:port" or a path to a local socket e.g. ":/path/to/socket" for the localhost.
 * @param string $user The username
 * @param string $pass The password
 * @param string $db The name of the database that is to be selected.     
 * @return resource Specified link identifier  
 */
function sed_sql_connect($host, $user, $pass, $db)
{
	$conn_id = @mysql_connect($host, $user, $pass) or sed_diefatal('Could not connect to database !<br />Please check your settings in the file datas/config.php<br />' . 'MySQL error : ' . sed_sql_error());
	$select = @mysql_select_db($db, $conn_id) or sed_diefatal('Could not select the database !<br />Please check your settings in the file datas/config.php<br />' . 'MySQL error : ' . sed_sql_error());
	return ($conn_id);
}

/** 
 * Returns the error number from the last MySQL function.
 * 
 * @return int Returns the error number from the last MySQL function, or 0 (zero) if no error occurred.  
 */
function sed_sql_errno($conn_id = null)
{
	global $connection_id;
	return is_null($conn_id) ? mysql_errno($connection_id) : mysql_errno($conn_id);
}

/** 
 * Returns the error text from the last MySQL function.
 *
 * @return string Returns the error text from the last MySQL function, or '' (empty string) if no error occurred.  
 */
function sed_sql_error($conn_id = null)
{
	global $connection_id;
	return is_null($conn_id) ? mysql_error($connection_id) : mysql_error($conn_id);
}

/** 
 * Returns an array that corresponds to the fetched row and moves the internal data pointer ahead.
 *   
 * @param resource $res The result resource that is being evaluated. This result comes from a call to sed_sql_query(). 
 * @return array|bool Returns an array of strings that corresponds to the fetched row, or FALSE if there are no more rows.  
 */
function sed_sql_fetcharray($res)
{
	return (mysql_fetch_array($res));
}

/** 
 * Returns an associative array that corresponds to the fetched row and moves the internal data pointer ahead.
 *   
 * @param resource $res The result resource that is being evaluated. This result comes from a call to sed_sql_query(). 
 * @return array|bool Returns an associative array of strings that corresponds to the fetched row, or FALSE if there are no more rows.  
 */
function sed_sql_fetchassoc($res)
{
	return (mysql_fetch_assoc($res));
}

/** 
 * Returns a numerical array that corresponds to the fetched row and moves the internal data pointer ahead.
 *   
 * @param resource $res The result resource that is being evaluated. This result comes from a call to sed_sql_query(). 
 * @return array|bool Returns an numerical array of strings that corresponds to the fetched row, or FALSE if there are no more rows.  
 */
function sed_sql_fetchrow($res)
{
	return (mysql_fetch_row($res));
}

/** 
 * Returns an object containing field information. This function can be used to obtain information about fields in the provided query result.
 *   
 * @param resource $res The result resource that is being evaluated. This result comes from a call to sed_sql_query(). 
 * @return Returns an object containing field information  
 */
function sed_sql_fetchfield($res, $field_offset = 0)
{
	return (mysql_fetch_field($res, $field_offset));
}

/** 
 * Retrieves the number of fields from a query.
 *   
 * @param resource $res The result resource that is being evaluated. This result comes from a call to sed_sql_query(). 
 * @return Returns the number of fields in the result set resource on success or FALSE on failure.
 */
function sed_sql_numfields($res)
{
	return (mysql_num_fields($res));
}

/** 
 * Free result memory
 *   
 * @param resource $res The result resource that is being evaluated. This result comes from a call to sed_sql_query(). 
 * @return bool Returns TRUE on success or FALSE on failure.  
 */
function sed_sql_freeresult($res)
{
	return (mysql_free_result($res));
}

/** 
 * Get the ID generated in the last query
 * 
 * Retrieves the ID generated for an AUTO_INCREMENT column by the previous query (usually INSERT).  
 *
 * @return int|bool The ID generated for an AUTO_INCREMENT column by the previous query on success, 0 if the previous query does not generate an AUTO_INCREMENT value, or FALSE if no MySQL connection was established. 
 */
function sed_sql_insertid($conn_id = null)
{
	global $connection_id;
	return is_null($conn_id) ? mysql_insert_id($connection_id) : mysql_insert_id($conn_id);
}

/** 
 * List tables in a MySQL database
 *  
 * @param string $res The name of the database  
 * @return string List tables in a MySQL database 
 */
function sed_sql_listtables($res)
{
	return (mysql_list_tables($res));
}

/** 
 * Get number of rows in result
 * 
 * Retrieves the number of rows from a result set. This command is only valid 
 * for statements like SELECT or SHOW that return an actual result set. 
 * To retrieve the number of rows affected by a INSERT, UPDATE, REPLACE or DELETE query, use sed_sql_affectedrows().  
 *   
 * @param resource $res The result resource that is being evaluated. This result comes from a call to sed_sql_query(). 
 * @return int|bool The number of rows in a result set on success or FALSE on failure.  
 */
function sed_sql_numrows($res)
{
	return (mysql_num_rows($res));
}

/** 
 * Escapes a string for use in a mysql_query
 *  
 * @param string $res The string that is to be escaped.
 * @return string Returns the escaped string.
 */
function sed_sql_prep($res, $conn_id = null)
{
	global $connection_id;
	return is_null($conn_id) ? mysql_real_escape_string($res, $connection_id) : mysql_real_escape_string($res, $conn_id);
}

/** 
 * Send a MySQL query & build sql statistics
 *  
 * @param string $query An SQL query
 * @param bool $halterr Show SQL error 
 * @return mixed Returns a resource on success, or FALSE on error for SELECT, SHOW, DESCRIBE, EXPLAIN. Returns TRUE on success or FALSE on error for INSERT, UPDATE, DELETE, DROP
 */
function sed_sql_query($query, $halterr = true, $conn_id = null)
{
	global $sys, $cfg, $usr, $connection_id;

	$conn_id = is_null($conn_id) ? $connection_id : $conn_id;

	$sys['qcount']++;
	$xtime = microtime();
	if ($halterr) {
		$result = mysql_query($query, $conn_id) or sed_diefatal('SQL error : ' . sed_sql_error());
	} else {
		$result = mysql_query($query, $conn_id);
	}

	$ytime = microtime();
	$xtime = explode(' ', $xtime);
	$ytime = explode(' ', $ytime);
	$sys['tcount'] = $sys['tcount'] + $ytime[1] + $ytime[0] - $xtime[1] - $xtime[0];
	if ($cfg['devmode']) {
		$sys['devmode']['queries'][] = array($sys['qcount'], $ytime[1] + $ytime[0] - $xtime[1] - $xtime[0], $query);
		$sys['devmode']['timeline'][] = $xtime[1] + $xtime[0] - $sys['starttime'];
	}
	return ($result);
}

/** 
 * Get result data
 * 
 * Retrieves the contents of one cell from a MySQL result set.  
 *  
 * @param resource $result The result resource that is being evaluated. This result comes from a call to mysql_query().
 * @param int $row The row number from the result that's being retrieved. Row numbers start at 0.
 * @param mixed $col The name or offset of the field being retrieved.  
 * @return string The contents of one cell from a MySQL result set on success, or FALSE on failure.
 */
function sed_sql_result($res, $row, $col)
{
	return (mysql_result($res, $row, $col));
}

/** 
 * Sets the client character set
 * 
 * Sets the default character set for the current connection. 
 *   
 * @param string $charset A valid character set name.
 * @param resource $conn_id The MySQL connection link identifier 
 * @return bool Returns TRUE on success or FALSE on failure.  
 */
function sed_sql_set_charset($conn_id, $charset)
{
	return (mysql_set_charset($charset, $conn_id));
}

/** 
 * Returns count of rows in table.
 *   
 * @param string $table Name of table.
 * @return int Count of rows in table.  
 */
function sed_sql_rowcount($table)
{
	$sqltmp = sed_sql_query("SELECT COUNT(*) FROM $table");
	return (mysql_result($sqltmp, 0, "COUNT(*)"));
}
