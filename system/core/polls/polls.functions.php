<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org

[BEGIN_SED]
File=polls.php
Version=178
Updated=2022-jul-12
Type=Core
Author=Seditio Team
Description=Polls functions
[END_SED]
==================== */

if (!defined('SED_CODE')) { die('Wrong URL.'); }

function sed_poll_add($part) 
{
	global $t;
	
	$poll_options = array();
	$poll_text = sed_import('poll_text', 'P', 'TXT');
	$poll_option_arr = sed_import('poll_option', 'P', 'ARR');		
	
	if (is_array($poll_option_arr))
		{ foreach ($poll_option_arr as $v) { if (!empty($v)) $poll_options[] = sed_import($v, 'D', 'TXT'); } }
	else 
		{ $poll_options = array(0 => '', 1 => ''); }
		
	$i = 1;
	foreach ($poll_options as $nop) 
		{
		$t->assign(array(		
			"NEW_POLL_OPTION" => sed_textbox('poll_option[]', $nop, 32, 128),
			"NEW_POLL_NUM" => $i
		));
		$i++;
		if ($i > 2) $t->parse($part."_NEW_POLL.NEW_POLL_OPTIONS.NEW_POLL_OPTIONS_DELETE");
		$t->parse($part."_NEW_POLL.NEW_POLL_OPTIONS");		
		}
	
	$t->assign(array(		
		"NEW_POLL_TEXT" => sed_textbox('poll_text', $poll_text, 64, 255)
	));	
	
	$t->parse($part."_NEW_POLL");		
}

function sed_poll_addsave($type = 0, $code) 
{
	global $cfg, $usr, $L, $sys, $db_polls, $db_polls_options;
		
	$poll_text = sed_import('poll_text', 'P', 'TXT');
	$poll_option_arr = sed_import('poll_option', 'P', 'ARR');		
	
	foreach ($poll_option_arr as $v)
		{ if (!empty($v)) $poll_options[] = sed_import($v, 'D', 'TXT'); }

	$sql = sed_sql_query("INSERT INTO $db_polls 
		(
		poll_type, 
		poll_state, 
		poll_creationdate, 
		poll_text, 
		poll_ownerid, 
		poll_code
		) 
		VALUES 
		(
		'".sed_sql_prep($type)."', 
		0, 
		".(int)$sys['now_offset'].", 
		'".sed_sql_prep($poll_text)."', 
		'".(int)$usr['id']."', 
		'".(int)$code."')"
		);
		
	$poll_id = sed_sql_insertid();
		
	foreach ($poll_options as $npo)
		{
		$sql2 = sed_sql_query("INSERT into $db_polls_options 
			(
			po_pollid, 
			po_text, 
			po_count
			) 
			VALUES 
			(
			'".$poll_id."', 
			'".sed_sql_prep($npo)."', 
			'0')"
			);
		}
	return($poll_id);					
}

function sed_poll_edit($part, $poll_id) 
{
	global $t, $db_polls, $db_polls_options;
	
	$poll_options = array();
	$poll_text = sed_import('poll_text', 'P', 'TXT');
	$poll_option_arr = sed_import('poll_option', 'P', 'ARR');
	
	$sql = sed_sql_query("SELECT * FROM $db_polls WHERE poll_id = '".$poll_id."' LIMIT 1");	
	$row = sed_sql_fetchassoc($sql);
	$poll_text = (empty($poll_text)) ? $row['poll_text'] : $poll_text;

	$sql = sed_sql_query("SELECT * FROM $db_polls_options WHERE po_pollid = '".$poll_id."'");	
	if (sed_sql_numrows($sql) > 0)
		{
		while ($row = sed_sql_fetchassoc($sql)) 
			{ $poll_options[$row['po_id']] = $row['po_text']; }
		}
	
	if (is_array($poll_option_arr))
		{ 
		foreach ($poll_option_arr as $key => $v) 
			{ if (!empty($v)) $poll_options[$key] = sed_import($v, 'D', 'TXT'); } 
		}
	elseif (count($poll_options) == 0) 
		{ $poll_options = array(0 => '', 1 => ''); }
		
	$i = 1;
	foreach ($poll_options as $key => $nop) 
		{
		$t->assign(array(		
			"EDIT_POLL_OPTION" => sed_textbox('poll_option[]', $nop, 32, 128),
			"EDIT_POLL_NUM" => $i
		));
		$i++;
		if ($i > 2) $t->parse($part."_EDIT_POLL.EDIT_POLL_OPTIONS.EDIT_POLL_OPTIONS_DELETE");
		$t->parse($part."_EDIT_POLL.EDIT_POLL_OPTIONS");		
		}
	
	$t->assign(array(		
		"EDIT_POLL_TEXT" => sed_textbox('poll_text', $poll_text, 64, 255)
	));	
	
	$t->parse($part."_EDIT_POLL");		
}

function sed_poll_editsave($poll_id) 
{
	global $cfg, $usr, $L, $sys, $db_polls, $db_polls_options;
		
	$poll_text = sed_import('poll_text', 'P', 'TXT');
	$poll_option_arr = sed_import('poll_option', 'P', 'ARR');		
	
	foreach ($poll_option_arr as $key => $v)
		{ 
		if (!empty($v)) { 
			$poll_options[$key] = sed_import($v, 'D', 'TXT');
			}			
		}

	$sql = sed_sql_query("UPDATE $db_polls SET poll_text='".sed_sql_prep($poll_text)."' WHERE poll_id='".(int)$poll_id."'");	
	$sql2 = sed_sql_query("DELETE FROM $db_polls_options WHERE po_pollid='".(int)$poll_id."'");
	
	foreach ($poll_options as $key => $npo)
		{			
		$sql3 = sed_sql_query("INSERT into $db_polls_options 
			(
			po_pollid, 
			po_text, 
			po_count
			) 
			VALUES 
			(
			'".$poll_id."', 
			'".sed_sql_prep($npo)."', 
			'0')"
			);
		}				
}

function sed_poll_check()
{
	global $error_string, $L;
		
	$poll_options = array();
	$poll_text = sed_import('poll_text', 'P', 'TXT');
	$poll_option_arr = sed_import('poll_option', 'P', 'ARR');		
	
	if (is_array($poll_option_arr))
		{
		foreach ($poll_option_arr as $v)
			{ if (!empty($v)) $poll_options[] = sed_import($v, 'D', 'TXT'); }
		}

	if (empty($poll_text) || count($poll_options) < 2)
		{			
		$error_string .= $L['polls_emptytitle']."<br />";
		}
}


?>
