<?PHP

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=system/templates.php
Version=180
Updated=2025-jan-25
Type=Core
Author=Seditio Team
Description=Xtemplate class
[END_SED]
==================== */

// When developing uncomment the line below, re-comment before making public
//error_reporting(E_ALL);

/**
 * XTemplate PHP templating engine
 *
 * @package XTemplate
 * @author Barnabas Debreceni [cranx@users.sourceforge.net]
 * @copyright Barnabas Debreceni 2000-2001
 * @author Jeremy Coates [cocomp@users.sourceforge.net]
 * @copyright Jeremy Coates 2002-2007
 * @author Tishov Alexander [info@avego.org]
 * @copyright Tishov Alexander, Seditio Team 2025
 * @see license.txt LGPL / BSD license
 * @since PHP 5
 * @link $HeadURL$
 * @version $Id$
 *
 * XTemplate class - http://www.phpxtemplate.org/ (x)html / xml generation with templates - fast & easy
 * Latest stable & Subversion versions available @ http://sourceforge.net/projects/xtpl/
 * License: LGPL / BSD - see license.txt
 * Changelog: see changelog.txt
 */

class XTemplate
{

	public $filecontents = '';
	public $blocks = array();
	public $parsed_blocks = array();
	public $preparsed_blocks = array();
	public $block_parse_order = array();
	public $sub_blocks = array();
	public $vars = array();
	public $filevars = array();
	public $filevar_parent = array();
	public $filecache = array();
	public $tpldir = '';
	public $files = null;
	public $filename = '';
	public $preg_delimiter = '`';
	public $file_delim = '';
	public $filevar_delim = '';
	public $filevar_delim_nl = '';
	public $block_start_delim = '<!-- ';
	public $block_end_delim = '-->';
	public $block_start_word = 'BEGIN:';
	public $block_end_word = 'END:';
	public $tag_start_delim = '{';
	public $tag_end_delim = '}';
	public $comment_delim = '#';
	public $comment_preg = '( ?#.*?)?';
	public $callback_delim = '|';
	public $callback_preg = '[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*(\(.*?\))?';
	public $allow_callbacks = true;
	public $allowed_callbacks = array(
		'strtoupper', 'strtolower', 'ucwords', 'ucfirst', 'strrev', 'str_word_count', 'strlen',
		'str_replace', 'str_ireplace', 'preg_replace', 'strip_tags', 'stripcslashes', 'stripslashes', 'substr',
		'str_pad', 'str_repeat', 'strtr', 'trim', 'ltrim', 'rtrim', 'nl2br', 'wordwrap', 'printf', 'sprintf',
		'addslashes', 'addcslashes',
		'htmlentities', 'html_entity_decode', 'htmlspecialchars', 'htmlspecialchars_decode',
		'urlencode', 'urldecode',
		'date', 'idate', 'strtotime', 'strftime', 'getdate', 'gettimeofday',
		'number_format', 'money_format',
		'var_dump', 'print_r', 'crop', 'resize', 'crop_image', 'resize_image'
	);

	public $mainblock = 'main';
	public $output_type = 'HTML';
	public $force_globals = true;
	public $debug = false;
	public $compress_output = false; // HTML minify sed 177 by Amro
	protected $_null_string = array('' => '');
	protected $_null_block = array('' => '');
	protected $_error = '';
	protected $_autoreset = true;
	protected $_ignore_missing_blocks = true;

	// Syntax for conditions
	public $if_start_delim = '<!-- IF ';
	public $if_end_delim = ' -->';
	public $else_delim = '<!-- ELSE -->';
	public $endif_delim = '<!-- ENDIF -->';

	// Regular expressions for finding conditions
	public $if_preg = '`<!-- IF (.*?) -->`';
	public $else_preg = '`<!-- ELSE -->`';
	public $endif_preg = '`<!-- ENDIF -->`';

	// Operator precedence for RPN
	protected $operator_precedence = [
		'&&' => 2,
		'||' => 2,
		'==' => 3,
		'!=' => 3,
		'<' => 4,
		'>' => 4,
		'<=' => 4,
		'>=' => 4,
		'+' => 5,
		'-' => 5,
		'*' => 6,
		'/' => 6,
		'%' => 6,
	];

	/**
	 * Constructor for the XTemplate class.
	 *
	 * @param mixed $options Options array or filename.
	 * @param string $tpldir Template directory.
	 * @param array $files Array of files.
	 * @param string $mainblock Main block name.
	 * @param bool $autosetup Automatically setup the template.
	 */
	public function __construct($options, $tpldir = '', $files = null, $mainblock = 'main', $autosetup = true)
	{

		if (!is_array($options)) {
			$options = array('file' => $options, 'path' => $tpldir, 'files' => $files, 'mainblock' => $mainblock, 'autosetup' => $autosetup);
		}

		if (!isset($options['tag_start'])) {
			$options['tag_start']	= $this->tag_start_delim;
		}
		if (!isset($options['tag_end'])) {
			$options['tag_end']		= $this->tag_end_delim;
		}

		$this->restart($options);
	}

	/**
	 * Restart the template engine with new options.
	 *
	 * @param mixed $options Options array or filename.
	 * @param string $tpldir Template directory.
	 * @param array $files Array of files.
	 * @param string $mainblock Main block name.
	 * @param bool $autosetup Automatically setup the template.
	 * @param string $tag_start Starting delimiter for tags.
	 * @param string $tag_end Ending delimiter for tags.
	 */
	public function restart($options, $tpldir = '', $files = null, $mainblock = 'main', $autosetup = true, $tag_start = '{', $tag_end = '}')
	{

		if (is_array($options)) {
			foreach ($options as $option => $value) {
				switch ($option) {
					case 'path':
					case 'tpldir':
						$tpldir = $value;
						break;

					case 'callbacks':
						$this->allow_callbacks = true;
						$this->allowed_callbacks = array_merge($this->allowed_callbacks, (array) $value);
						break;

					case 'debug':
						$this->debug = $value;
						break;

					case 'file':
					case 'files':
					case 'mainblock':
					case 'autosetup':
					case 'tag_start':
					case 'tag_end':
						$$option = $value;
						break;
				}
			}

			$this->filename = $file;
		} else {
			$this->filename = $options;
		}

		if (isset($tpldir)) {
			$this->tpldir = $tpldir;
		}
		if (defined('XTPL_DIR') && empty($this->tpldir)) {
			$this->tpldir = XTPL_DIR;
		}

		if (isset($files) && is_array($files)) {
			$this->files = $files;
		}

		if (isset($mainblock)) {
			$this->mainblock = $mainblock;
		}

		if (isset($tag_start)) {
			$this->tag_start_delim = $tag_start;
		}

		if (isset($tag_end)) {
			$this->tag_end_delim = $tag_end;
		}

		$this->filecontents = '';

		$this->blocks = array();
		$this->parsed_blocks = array();
		$this->preparsed_blocks = array();
		$this->block_parse_order = array();
		$this->sub_blocks = array();
		$this->vars = array();
		$this->filevars = array();
		$this->filevar_parent = array();
		$this->filecache = array();

		if ($this->allow_callbacks) {
			$delim = preg_quote($this->callback_delim);
			if (mb_strlen($this->callback_delim) < mb_strlen($delim)) {

				$delim = preg_quote($delim);
			}

			$this->callback_preg = preg_replace($this->preg_delimiter . '^\(' . $delim . '(.*)\)\*$' . $this->preg_delimiter, '\\1', $this->callback_preg);
		}

		if (!isset($autosetup) || $autosetup) {
			$this->setup();
		}
	}

	/**
	 * Setup the template engine.
	 *
	 * @param bool $add_outer Add outer block.
	 */
	public function setup($add_outer = false)
	{

		$this->tag_start_delim = preg_quote($this->tag_start_delim);
		$this->tag_end_delim = preg_quote($this->tag_end_delim);

		$this->file_delim = $this->preg_delimiter . $this->tag_start_delim . "FILE\s*\"([^\"]+)\"" . $this->comment_preg . $this->tag_end_delim . $this->preg_delimiter . 'm';

		$this->filevar_delim = $this->preg_delimiter . $this->tag_start_delim . "FILE\s*" . $this->tag_start_delim . "([A-Za-z0-9\._\x7f-\xff]+?)" . $this->comment_preg . $this->tag_end_delim . $this->comment_preg . $this->tag_end_delim . $this->preg_delimiter . 'm';

		$this->filevar_delim_nl = $this->preg_delimiter . "^\s*" . $this->tag_start_delim . "FILE\s*" . $this->tag_start_delim . "([A-Za-z0-9\._\x7f-\xff]+?)" . $this->comment_preg . $this->tag_end_delim . $this->comment_preg . $this->tag_end_delim . "\s*\n" . $this->preg_delimiter . 'm';

		$this->callback_preg = '(' . preg_quote($this->callback_delim) . $this->callback_preg . ')*';

		if (empty($this->filecontents)) {
			$this->filecontents = $this->_r_getfile($this->filename);
		}

		if ($add_outer) {
			$this->_add_outer_block();
		}

		$this->blocks = $this->_maketree($this->filecontents, '');
		$this->filevar_parent = $this->_store_filevar_parents($this->blocks);
		$this->scan_globals();
	}

	/**
	 * Assign a variable to the template.
	 *
	 * @param mixed $name Variable name or array of variables.
	 * @param mixed $val Variable value.
	 * @param bool $reset_array Reset the array.
	 */
	public function assign($name, $val = '', $reset_array = true)
	{

		if (is_array($name) || is_object($name)) {

			foreach ($name as $k => $v) {

				$this->vars[$k] = $v;
			}
		} elseif (is_array($val) || is_object($val)) {

			// Clear the existing values
			if ($reset_array) {
				$this->vars[$name] = array();
			}

			foreach ($val as $k => $v) {

				$this->vars[$name][$k] = $v;
			}
		} else {

			$this->vars[$name] = $val;
		}
	}

	/**
	 * Assign a file to the template.
	 *
	 * @param mixed $name File variable name or array of file variables.
	 * @param mixed $val File variable value.
	 */
	public function assign_file($name, $val = '')
	{

		if (is_array($name)) {

			foreach ($name as $k => $v) {

				$this->_assign_file_sub($k, $v);
			}
		} else {

			$this->_assign_file_sub($name, $val);
		}
	}

	/**
	 * Parse a block in the template.
	 *
	 * @param string $bname Block name.
	 */
	public function parse($bname)
	{

		if (isset($this->preparsed_blocks[$bname])) {

			$copy = $this->preparsed_blocks[$bname];
		} elseif (isset($this->blocks[$bname])) {

			$copy = $this->blocks[$bname];
		} elseif ($this->_ignore_missing_blocks) {

			$this->_set_error("parse: blockname [$bname] does not exist");
			return;
		} else {

			$this->_set_error("parse: blockname [$bname] does not exist");
		}

		// Process conditions
		$copy = $this->_process_conditions($copy);

		if (!isset($copy)) {
			die('Block: ' . $bname);
		}

		$copy = preg_replace($this->filevar_delim_nl, '', $copy);

		$var_array = array();

		preg_match_all($this->preg_delimiter . $this->tag_start_delim . '([A-Za-z0-9\._\x7f-\xff]+?' . $this->callback_preg . $this->comment_preg . ')' . $this->tag_end_delim . $this->preg_delimiter, $copy, $var_array);

		$var_array = $var_array[1];

		foreach ($var_array as $k => $v) {
			$orig_v = $v;

			$comment = '';
			$any_comments = explode($this->comment_delim, $v);
			if (count($any_comments) > 1) {
				$comment = array_pop($any_comments);
			}
			$v = rtrim(implode($this->comment_delim, $any_comments));

			if ($this->allow_callbacks) {
				$callback_funcs = explode($this->callback_delim, $v);
				$v = rtrim($callback_funcs[0]);
				unset($callback_funcs[0]);
			}

			$sub = explode('.', $v);

			if ($sub[0] == '_BLOCK_') {

				unset($sub[0]);

				$bname2 = implode('.', $sub);

				$var = isset($this->parsed_blocks[$bname2]) ? $this->parsed_blocks[$bname2] : '';
				$nul = (!isset($this->_null_block[$bname2])) ? $this->_null_block[''] : $this->_null_block[$bname2];

				if ($var === '') {

					if ($nul == '') {
						$copy = preg_replace($this->preg_delimiter . $this->tag_start_delim . $v . $this->tag_end_delim . $this->preg_delimiter . 'm', '', $copy);
					} else {

						$copy = preg_replace($this->preg_delimiter . $this->tag_start_delim . $v . $this->tag_end_delim . $this->preg_delimiter . 'm', "$nul", $copy);
					}
				} else {

					switch (true) {
						case preg_match($this->preg_delimiter . "^\n" . $this->preg_delimiter, $var) && preg_match($this->preg_delimiter . "\n$" . $this->preg_delimiter, $var):
							$var = mb_substr($var, 1, -1);
							break;

						case preg_match($this->preg_delimiter . "^\n" . $this->preg_delimiter, $var):
							$var = mb_substr($var, 1);
							break;

						case preg_match($this->preg_delimiter . "\n$" . $this->preg_delimiter, $var):
							$var = mb_substr($var, 0, -1);
							break;
					}

					$var = str_replace('\\', '\\\\', $var);
					$var = str_replace('$', '\\$', $var);
					$var = str_replace('\\|', '|', $var);
					$copy = preg_replace($this->preg_delimiter . $this->tag_start_delim . $v . $this->tag_end_delim . $this->preg_delimiter . 'm', "$var", $copy);

					if (preg_match($this->preg_delimiter . "^\n" . $this->preg_delimiter, $copy) && preg_match($this->preg_delimiter . "\n$" . $this->preg_delimiter, $copy)) {
						$copy = mb_substr($copy, 1, -1);
					}
				}
			} else {
				// TAGS

				$var = $this->vars;

				foreach ($sub as $v1) {

					switch (true) {
						case is_array($var):
							if (!isset($var[$v1]) || (is_string($var[$v1]) && mb_strlen($var[$v1]) == 0)) {

								if (defined($v1)) {

									$var[$v1] = constant($v1);
								} else {

									$var[$v1] = null;
								}
							}
							$var = $var[$v1];
							break;

						case is_object($var):
							if (!isset($var->$v1) || (is_string($var->$v1) && mb_strlen($var->$v1) == 0)) {
								if (defined($v1)) {

									$var->$v1 = constant($v1);
								} else {

									$var->$v1 = null;
								}
							}
							$var = $var->$v1;
							break;
					}
				}

				if ($this->allow_callbacks) {
					if (is_array($callback_funcs) && !empty($callback_funcs)) {
						foreach ($callback_funcs as $callback) {
							if (preg_match($this->preg_delimiter . '\((.*?)\)' . $this->preg_delimiter, $callback, $matches)) {
								$parameters = array();

								if (preg_match_all($this->preg_delimiter . '(?#
									match optional comma, optional other stuff, then
									apostrophes / quotes then stuff followed by comma or
									closing bracket negative look behind for an apostrophe
									or quote not preceeded by an escaping back slash
									)[,?\s*?]?[\'|"](.*?)(?<!\\\\)(?<=[\'|"])[,|\)$](?#
									OR match optional comma, optional other stuff, then
									multiple word \w with look behind % for our %s followed
									by comma or closing bracket
									)|,?\s*?([\w(?<!\%)]+)[,|\)$]' . $this->preg_delimiter, $matches[1] . ')', $param_matches)) {
									$parameters = $param_matches[0];
								}
								if (count($parameters)) {
									array_walk($parameters, array($this, 'trim_callback'));
									if (($key = array_search('%s', $parameters)) !== false) {
										$parameters[$key] = $var;
									} else {
										array_unshift($parameters, $var);
									}
								} else {
									unset($parameters);
								}
							}

							$callback = preg_replace($this->preg_delimiter . '\(.*?\)' . $this->preg_delimiter, '', $callback);

							if (is_subclass_of($this, 'XTemplate') && method_exists($this, $callback) && is_callable(array($this, $callback))) {
								if (isset($parameters)) {
									$var = call_user_func_array(array($this, $callback), $parameters);
									unset($parameters);
								} else {
									$var = call_user_func(array($this, $callback), $var);
								}
							} elseif (in_array($callback, $this->allowed_callbacks) && function_exists($callback) && is_callable($callback)) {
								if (isset($parameters)) {
									$var = call_user_func_array($callback, $parameters);
									unset($parameters);
								} else {
									$var = call_user_func($callback, isset($var) ? $var : '');
								}
							}
						}
					}
				}

				$nul = (!isset($this->_null_string[$v])) ? ($this->_null_string[""]) : ($this->_null_string[$v]);
				$var = (!isset($var)) ? $nul : $var;

				if (is_string($var)) {
					if ($var === '') {
						$copy = preg_replace($this->preg_delimiter . $this->tag_start_delim . preg_quote($orig_v) . $this->tag_end_delim . $this->preg_delimiter . 'm', '', $copy);
					} else {
						$var = str_replace('\\', '\\\\', $var);
						$var = str_replace('$', '\\$', $var);
						$var = str_replace('\\|', '|', $var);
					}
				}

				$copy = preg_replace($this->preg_delimiter . $this->tag_start_delim . preg_quote($orig_v) . $this->tag_end_delim . $this->preg_delimiter . 'm', "$var", $copy);

				if (preg_match($this->preg_delimiter . "^\n" . $this->preg_delimiter, $copy) && preg_match($this->preg_delimiter . "\n$" . $this->preg_delimiter, $copy)) {
					$copy = mb_substr($copy, 1);
				}
			}
		}

		if (isset($this->parsed_blocks[$bname])) {
			$this->parsed_blocks[$bname] .= $copy;
		} else {
			$this->parsed_blocks[$bname] = $copy;
		}

		if ($this->_autoreset && (!empty($this->sub_blocks[$bname]))) {

			reset($this->sub_blocks[$bname]);

			foreach ($this->sub_blocks[$bname] as $k => $v) {
				$this->reset($v);
			}
		}
	}

	/**
	 * Recursively parse a block in the template.
	 *
	 * @param string $bname Block name.
	 */
	public function rparse($bname)
	{

		if (!empty($this->sub_blocks[$bname])) {

			reset($this->sub_blocks[$bname]);

			foreach ($this->sub_blocks[$bname] as $k => $v) {

				if (!empty($v)) {
					$this->rparse($v);
				}
			}
		}

		$this->parse($bname);
	}

	/**
	 * Insert a loop into the template.
	 *
	 * @param string $bname Block name.
	 * @param string $var Variable name.
	 * @param mixed $value Variable value.
	 */
	public function insert_loop($bname, $var, $value = '')
	{

		$this->assign($var, $value);
		$this->parse($bname);
	}

	/**
	 * Compress the output by removing whitespace.
	 *
	 * @param string $out Output string.
	 * @return string Compressed output.
	 */
	public function compress($out)
	{
		$out = preg_replace("/(?:(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:(?<!\:|\\\|\'|\")\/\/.*))/", "", $out);
		$out = str_replace(array("\r\n", "\r", "\n", "\t", "  ", "    ", "    "), "", $out);
		return $out;
	}

	/**
	 * Loop through an array and insert into the template.
	 *
	 * @param string $bname Block name.
	 * @param string $var Variable name.
	 * @param array $values Array of values.
	 */
	public function array_loop($bname, $var, &$values)
	{

		if (is_array($values)) {

			foreach ($values as $v) {

				$this->insert_loop($bname, $var, $v);
			}
		}
	}

	/**
	 * Get the text of a block.
	 *
	 * @param string $bname Block name.
	 * @return string Block text.
	 */
	public function text($bname = '')
	{

		$text = '';

		if ($this->debug && $this->output_type == 'HTML') {

			$text .= '<!-- XTemplate debug TEXT: ' . $bname . ' ';
			if (is_array($this->tpldir)) {

				foreach ($this->tpldir as $dir) {

					if (is_readable($dir . DIRECTORY_SEPARATOR . $this->filename)) {
						$text .= realpath($dir . DIRECTORY_SEPARATOR . $this->filename);
						break;
					}
				}
			} elseif (!empty($this->tpldir)) {

				$text .= realpath($this->tpldir . DIRECTORY_SEPARATOR . $this->filename);
			} else {

				$text .= $this->filename;
			}
			$text .= " -->\n";
		}

		$bname = !empty($bname) ? $bname : $this->mainblock;

		$text .= isset($this->parsed_blocks[$bname]) ? $this->parsed_blocks[$bname] : $this->get_error();

		return $text;
	}

	/**
	 * Output the text of a block.
	 *
	 * @param string $bname Block name.
	 */
	public function out($bname)
	{
		$out = $this->text($bname);
		$out = preg_replace('/\s+$/m', '', $out); // fix Amro 04.11.2017
		if ($this->compress_output) {
			$out = $this->compress($out);
		}
		echo trim($out);
	}

	/**
	 * Output the text of a block to a file.
	 *
	 * @param string $bname Block name.
	 * @param string $fname File name.
	 */
	public function out_file($bname, $fname)
	{

		if (!empty($bname) && !empty($fname) && is_writeable($fname)) {

			$fp = fopen($fname, 'w');
			fwrite($fp, $this->text($bname));
			fclose($fp);
		}
	}

	/**
	 * Reset a block.
	 *
	 * @param string $bname Block name.
	 */
	public function reset($bname)
	{

		$this->parsed_blocks[$bname] = '';
	}

	/**
	 * Check if a block has been parsed.
	 *
	 * @param string $bname Block name.
	 * @return bool True if parsed, false otherwise.
	 */
	public function parsed($bname)
	{

		return (!empty($this->parsed_blocks[$bname]));
	}

	/**
	 * Set the null string for a variable.
	 *
	 * @param string $str Null string.
	 * @param string $varname Variable name.
	 */
	public function set_null_string($str, $varname = '')
	{

		$this->_null_string[$varname] = $str;
	}

	/**
	 * Set the null string for a variable (alias).
	 *
	 * @param string $str Null string.
	 * @param string $varname Variable name.
	 */
	public function SetNullString($str, $varname = '')
	{
		$this->set_null_string($str, $varname);
	}

	/**
	 * Set the null block for a block.
	 *
	 * @param string $str Null block.
	 * @param string $bname Block name.
	 */
	public function set_null_block($str, $bname = '')
	{

		$this->_null_block[$bname] = $str;
	}

	/**
	 * Set the null block for a block (alias).
	 *
	 * @param string $str Null block.
	 * @param string $bname Block name.
	 */
	public function SetNullBlock($str, $bname = '')
	{
		$this->set_null_block($str, $bname);
	}

	/**
	 * Set auto reset for blocks.
	 */
	public function set_autoreset()
	{

		$this->_autoreset = true;
	}

	/**
	 * Clear auto reset for blocks.
	 */
	public function clear_autoreset()
	{

		$this->_autoreset = false;
	}

	/**
	 * Scan for global variables.
	 */
	public function scan_globals()
	{

		$GLOB = array();

		if ($this->force_globals && ini_get('auto_globals_jit') == true) {
			$tmp = $_SERVER;
			$tmp = $_ENV;
			$tmp = $_REQUEST;
			unset($tmp);
		}

		foreach ($GLOBALS as $k => $v) {

			$GLOB[$k] = array();

			switch ($k) {

				case 'GLOBALS':
					break;

				case '_COOKIE':
				case '_SESSION':
					$GLOB[$k] = array_merge($GLOB[$k], $v);
					break;

				case '_ENV':
				case '_FILES':
				case '_GET':
				case '_POST':
				case '_REQUEST':
				case '_SERVER':
				default:
					$GLOB[$k] = $v;
					break;
			}
		}

		$this->assign('PHP', $GLOB);
	}

	/**
	 * Get the error message.
	 *
	 * @return string Error message.
	 */
	public function get_error()
	{

		$retval = false;

		if ($this->_error != '') {

			switch ($this->output_type) {
				case 'HTML':
				case 'html':
					$retval = '<b>[XTemplate]</b><ul>' . nl2br(str_replace('* ', '<li>', str_replace(" *\n", "</li>\n", $this->_error))) . '</ul>';
					break;

				default:
					$retval = '[XTemplate] ' . str_replace(' *\n', "\n", $this->_error);
					break;
			}
		}

		return $retval;
	}

	/**
	 * Make a tree structure from the template content.
	 *
	 * @param string $con Template content.
	 * @param string $parentblock Parent block name.
	 * @return array Tree structure.
	 */
	public function _maketree($con, $parentblock = '')
	{

		$blocks = array();

		$con2 = explode($this->block_start_delim, $con);

		if (!empty($parentblock)) {

			$block_names = explode('.', $parentblock);
			$level = sizeof($block_names);
		} else {

			$block_names = array();
			$level = 0;
		}

		$patt = "(" . $this->block_start_word . "|" . $this->block_end_word . ")\s*(\w+)" . $this->comment_preg . "\s*" . $this->block_end_delim . "(.*)";

		foreach ($con2 as $k => $v) {

			$res = array();

			if (preg_match_all($this->preg_delimiter . "$patt" . $this->preg_delimiter . 'ims', $v, $res, PREG_SET_ORDER)) {

				$block_word	= $res[0][1];
				$block_name	= $res[0][2];
				$comment	= $res[0][3];
				$content	= $res[0][4];

				if (mb_strtoupper($block_word) == $this->block_start_word) {

					$parent_name = implode('.', $block_names);
					$block_names[++$level] = $block_name;
					$cur_block_name = implode('.', $block_names);
					$this->block_parse_order[] = $cur_block_name;
					$blocks[$cur_block_name] = isset($blocks[$cur_block_name]) ? $blocks[$cur_block_name] . $content : $content;
					$blocks[$parent_name] .= str_replace('\\', '', $this->tag_start_delim) . '_BLOCK_.' . $cur_block_name . str_replace('\\', '', $this->tag_end_delim);
					$this->sub_blocks[$parent_name][] = $cur_block_name;
					$this->sub_blocks[$cur_block_name][] = '';
				} else if (mb_strtoupper($block_word) == $this->block_end_word) {

					unset($block_names[$level--]);

					$parent_name = implode('.', $block_names);
					$blocks[$parent_name] .= $content;
				}
			} else {

				$tmp = implode('.', $block_names);

				if ($k) {
					$blocks[$tmp] .= $this->block_start_delim;
				}

				$blocks[$tmp] = isset($blocks[$tmp]) ? $blocks[$tmp] . $v : $v;
			}
		}

		return $blocks;
	}

	/**
	 * Assign a file variable to the template.
	 *
	 * @param string $name File variable name.
	 * @param string $val File variable value.
	 */
	private function _assign_file_sub($name, $val)
	{

		if (isset($this->filevar_parent[$name])) {

			if ($val != '') {

				$val = $this->_r_getfile($val);

				foreach ($this->filevar_parent[$name] as $parent) {

					if (isset($this->preparsed_blocks[$parent]) && !isset($this->filevars[$name])) {

						$copy = $this->preparsed_blocks[$parent];
					} elseif (isset($this->blocks[$parent])) {

						$copy = $this->blocks[$parent];
					}

					$res = array();

					preg_match_all($this->filevar_delim, $copy, $res, PREG_SET_ORDER);

					if (is_array($res) && isset($res[0])) {

						foreach ($res as $v) {

							if ($v[1] == $name) {

								$copy = preg_replace($this->preg_delimiter . preg_quote($v[0]) . $this->preg_delimiter, "$val", $copy);
								$this->preparsed_blocks = array_merge($this->preparsed_blocks, $this->_maketree($copy, $parent));
								$this->filevar_parent = array_merge($this->filevar_parent, $this->_store_filevar_parents($this->preparsed_blocks));
							}
						}
					}
				}
			}
		}

		$this->filevars[$name] = $val;
	}

	/**
	 * Store file variable parents.
	 *
	 * @param array $blocks Blocks array.
	 * @return array File variable parents.
	 */
	public function _store_filevar_parents($blocks)
	{

		$parents = array();

		foreach ($blocks as $bname => $con) {

			$res = array();

			preg_match_all($this->filevar_delim, $con, $res);

			foreach ($res[1] as $k => $v) {

				$parents[$v][] = $bname;
			}
		}
		return $parents;
	}

	/**
	 * Set an error message.
	 *
	 * @param string $str Error message.
	 */
	private function _set_error($str)
	{

		$this->_error .= '* ' . $str . " *\n";
	}

	/**
	 * Get the content of a file.
	 *
	 * @param string $file File name.
	 * @return string File content.
	 */
	protected function _getfile($file)
	{

		if (!isset($file)) {
			$this->_set_error('!isset file name!' . $file);

			return '';
		}

		if (isset($this->files)) {

			if (isset($this->files[$file])) {

				$file = $this->files[$file];
			}
		}

		if (!empty($this->tpldir)) {

			if (is_array($this->tpldir)) {

				foreach ($this->tpldir as $dir) {

					if (is_readable($dir . DIRECTORY_SEPARATOR . $file)) {
						$file = $dir . DIRECTORY_SEPARATOR . $file;
						break;
					}
				}
			} else {

				$file = $this->tpldir . DIRECTORY_SEPARATOR . $file;
			}
		}

		$file_text = '';

		if (isset($this->filecache[$file])) {

			$file_text .= $this->filecache[$file];

			if ($this->debug && $this->output_type == 'HTML') {
				$file_text = '<!-- XTemplate debug CACHED: ' . realpath($file) . ' -->' . "\n" . $file_text;
			}
		} else {

			if (is_file($file) && is_readable($file)) {

				if (filesize($file)) {

					if (!($fh = fopen($file, 'r'))) {

						$this->_set_error('Cannot open file: ' . realpath($file));
						return '';
					}

					$file_text .= fread($fh, filesize($file));
					fclose($fh);
				}

				if ($this->debug && $this->output_type == 'HTML') {
					$file_text = '<!-- XTemplate debug: ' . realpath($file) . ' -->' . "\n" . $file_text;
				}
			} elseif (str_replace('.', '', phpversion()) >= '430' && $file_text = @file_get_contents($file, true)) {

				if ($file_text === false) {
					$this->_set_error("[" . realpath($file) . "] ($file) does not exist");
					if ($this->output_type == 'HTML') {
						$file_text = "<b>__XTemplate fatal error: file [$file] does not exist in the include path__</b>";
					}
				} elseif ($this->debug && $this->output_type == 'HTML') {
					$file_text = '<!-- XTemplate debug (via include path): ' . realpath($file) . ' -->' . "\n" . $file_text;
				}
			} elseif (!is_file($file)) {

				$this->_set_error("[" . realpath($file) . "] ($file) does not exist");
				if ($this->output_type == 'HTML') {
					$file_text .= "<b>__XTemplate fatal error: file [$file] does not exist__</b>";
				}
			} elseif (!is_readable($file)) {

				$this->_set_error("[" . realpath($file) . "] ($file) is not readable");
				if ($this->output_type == 'HTML') {
					$file_text .= "<b>__XTemplate fatal error: file [$file] is not readable__</b>";
				}
			}

			$this->filecache[$file] = $file_text;
		}

		return $file_text;
	}

	/**
	 * Recursively get the content of a file.
	 *
	 * @param string $file File name.
	 * @return string File content.
	 */
	public function _r_getfile($file)
	{

		$text = $this->_getfile($file);

		$res = array();

		while (preg_match($this->file_delim, $text, $res)) {

			$text2 = $this->_getfile($res[1]);
			$text = preg_replace($this->preg_delimiter . preg_quote($res[0]) . $this->preg_delimiter, $text2, $text);
		}

		return $text;
	}

	/**
	 * Tokenize an expression.
	 *
	 * @param string $expression Expression to tokenize.
	 * @return array Tokens.
	 */
	protected function _tokenize($expression)
	{
		// Regular expression for tokenization
		$pattern = '`\s*(?:(\d*\.\d+|\d+)|([+\-*/%()]|&&|\|\||==|!=|<=|>=|<|>)|([A-Za-z0-9\._\x7f-\xff]+))\s*`';
		preg_match_all($pattern, $expression, $matches, PREG_SET_ORDER);

		$tokens = [];
		foreach ($matches as $match) {
			if (isset($match[1]) && $match[1] !== '') {
				// Number
				$tokens[] = ['type' => 'number', 'value' => $match[1]];
			} elseif (isset($match[2]) && $match[2] !== '') {
				// Operator or parenthesis
				$tokens[] = ['type' => 'operator', 'value' => $match[2]];
			} elseif (isset($match[3]) && $match[3] !== '') {
				// Variable
				$tokens[] = ['type' => 'variable', 'value' => $match[3]];
			}
		}

		return $tokens;
	}

	/**
	 * Convert tokens to Reverse Polish Notation (RPN).
	 *
	 * @param array $tokens Tokens.
	 * @return array RPN.
	 */
	protected function _shunting_yard($tokens)
	{
		$output = [];
		$stack = [];

		foreach ($tokens as $token) {
			if ($token['type'] === 'number' || $token['type'] === 'variable') {
				// Numbers and variables go directly to output
				$output[] = $token;
			} elseif ($token['type'] === 'operator') {
				// Operators
				while (
					!empty($stack) &&
					$stack[count($stack) - 1]['type'] === 'operator' &&
					$this->operator_precedence[$stack[count($stack) - 1]['value']] >= $this->operator_precedence[$token['value']]
				) {
					$output[] = array_pop($stack);
				}
				$stack[] = $token;
			} elseif ($token['value'] === '(') {
				// Opening parenthesis to stack
				$stack[] = $token;
			} elseif ($token['value'] === ')') {
				// Closing parenthesis: pop operators to output until opening parenthesis
				while (!empty($stack) && $stack[count($stack) - 1]['value'] !== '(') {
					$output[] = array_pop($stack);
				}
				array_pop($stack); // Remove opening parenthesis
			}
		}

		// Pop remaining operators to output
		while (!empty($stack)) {
			$output[] = array_pop($stack);
		}

		return $output;
	}

	/**
	 * Get the value of a variable.
	 *
	 * @param string $var_name Variable name.
	 * @return mixed Variable value.
	 */
	protected function _get_variable_value($var_name)
	{
		$parts = explode('.', $var_name);
		$value = $this->vars;

		foreach ($parts as $part) {
			if (is_array($value) && isset($value[$part])) {
				$value = $value[$part];
			} elseif (is_object($value) && isset($value->$part)) {
				$value = $value->$part;
			} else {
				return null;
			}
		}

		return $value;
	}

	/**
	 * Evaluate Reverse Polish Notation (RPN).
	 *
	 * @param array $rpn RPN.
	 * @return mixed Evaluation result.
	 */
	protected function _evaluate_rpn($rpn)
	{
		$stack = [];

		foreach ($rpn as $token) {
			if ($token['type'] === 'number') {
				// Number: push to stack
				$stack[] = $token['value'];
			} elseif ($token['type'] === 'variable') {
				// Variable: replace with value and push to stack
				$value = $this->_get_variable_value($token['value']);
				$stack[] = $value;
			} elseif ($token['type'] === 'operator') {
				// Operator: evaluate
				$b = array_pop($stack);
				$a = array_pop($stack);
				$result = $this->_apply_operator($token['value'], $a, $b);
				$stack[] = $result;
			}
		}

		return array_pop($stack);
	}

	/**
	 * Evaluate a condition.
	 *
	 * @param string $condition Condition to evaluate.
	 * @return bool Evaluation result.
	 */
	protected function _evaluate_condition($condition)
	{
		// Tokenize the expression
		$tokens = $this->_tokenize($condition);

		// Convert to Reverse Polish Notation (RPN)
		$rpn = $this->_shunting_yard($tokens);

		// Evaluate RPN
		return (bool) $this->_evaluate_rpn($rpn);
	}

	/**
	 * Apply an operator to two values.
	 *
	 * @param string $operator Operator.
	 * @param mixed $a First value.
	 * @param mixed $b Second value.
	 * @return mixed Result of the operation.
	 * @throws Exception If the operator is unknown.
	 */
	protected function _apply_operator($operator, $a, $b)
	{
		switch ($operator) {
			case '+':
				return $a + $b;
			case '-':
				return $a - $b;
			case '*':
				return $a * $b;
			case '/':
				return $a / $b;
			case '%':
				return $a % $b;
			case '==':
				return $a == $b;
			case '!=':
				return $a != $b;
			case '<':
				return $a < $b;
			case '>':
				return $a > $b;
			case '<=':
				return $a <= $b;
			case '>=':
				return $a >= $b;
			case '&&':
				return $a && $b;
			case '||':
				return $a || $b;
			default:
				throw new Exception("Unknown operator: $operator");
		}
	}

	/**
	 * Process conditions in the template content.
	 *
	 * @param string $content Template content.
	 * @return string Processed content.
	 */
	protected function _process_conditions($content)
	{
		// Process all conditions in the template
		while (preg_match($this->if_preg, $content, $if_matches)) {
			$condition = trim($if_matches[1]); // // Condition (e.g., "{PHP.i} % 2 == 0 && {PHP.usr.isadmin}")
			$if_block_start = strpos($content, $if_matches[0]); // Start of IF block
			$if_block_length = strlen($if_matches[0]); // Length of IF block

			// Find the end of the IF block (either ELSE or ENDIF)
			$else_pos = strpos($content, $this->else_delim, $if_block_start + $if_block_length);
			$endif_pos = strpos($content, $this->endif_delim, $if_block_start + $if_block_length);

			// Determine if there is an ELSE
			$has_else = ($else_pos !== false && $else_pos < $endif_pos);

			// Calculate the boundaries of the blocks
			$if_content_start = $if_block_start + $if_block_length;
			$if_content_end = $has_else ? $else_pos : $endif_pos;
			$else_content_start = $has_else ? $else_pos + strlen($this->else_delim) : null;
			$else_content_end = $has_else ? $endif_pos : null;

			// Extract the content of the blocks
			$if_content = substr($content, $if_content_start, $if_content_end - $if_content_start);
			$else_content = $has_else ? substr($content, $else_content_start, $else_content_end - $else_content_start) : '';

			// Evaluate the condition
			$condition_result = $this->_evaluate_condition($condition);

			// Replace the IF block with the corresponding content
			$replacement = $condition_result ? $if_content : $else_content;
			$content = substr_replace($content, $replacement, $if_block_start, $endif_pos + strlen($this->endif_delim) - $if_block_start);
		}

		return $content;
	}

	/**
	 * Trim callback parameters.
	 *
	 * @param string $value Callback parameter.
	 */
	protected function trim_callback(&$value)
	{
		$value = preg_replace($this->preg_delimiter . "^.*(%s).*$" . $this->preg_delimiter, '\\1', trim($value));
		$value = preg_replace($this->preg_delimiter . '^,?\s*?(.*?)[,|\)]?$' . $this->preg_delimiter, '\\1', trim($value));
		$value = preg_replace($this->preg_delimiter . '^[\'|"]?(.*?)[\'|"]?$' . $this->preg_delimiter, '\\1', trim($value));
		$value = preg_replace($this->preg_delimiter . '\\\\(?=\'|")' . $this->preg_delimiter, '', $value);
		// Deal with escaped commas (beta)
		$value = preg_replace($this->preg_delimiter . '\\\,' . $this->preg_delimiter, ',', $value);
	}

	/**
	 * Add an outer block to the template content.
	 */
	private function _add_outer_block()
	{

		$before = $this->block_start_delim . $this->block_start_word . ' ' . $this->mainblock . ' ' . $this->block_end_delim;
		$after = $this->block_start_delim . $this->block_end_word . ' ' . $this->mainblock . ' ' . $this->block_end_delim;

		$this->filecontents = $before . "\n" . $this->filecontents . "\n" . $after;
	}

	/**
	 * Debug var_dump.
	 *
	 * @param mixed $args Arguments to var_dump.
	 */
	protected function _pre_var_dump($args)
	{

		if ($this->debug) {
			echo '<pre>';
			var_dump(func_get_args());
			echo '</pre>';
		}
	}

	/**
	 * Debug var_dump with output buffering.
	 *
	 * @param mixed $args Arguments to var_dump.
	 * @return string Output of var_dump.
	 */
	protected function _ob_var_dump($args)
	{

		if ($this->debug) {
			ob_start();
			$this->_pre_var_dump(func_get_args());
			return ob_get_clean();
		}
	}
}
