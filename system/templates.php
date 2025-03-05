<?PHP

/* ====================
Seditio - Website engine
Copyright Seditio Team
https://seditio.org
[BEGIN_SED]
File=system/templates.php
Version=180
Updated=2025-mar-03
Type=Core
Author=Seditio Team
Description=Xtemplate class
[END_SED]
==================== */

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
 * Latest stable & Subversion versions available @ https://github.com/sedteam/Xtemplate
 */
class XTemplate
{
	/**
	 * Raw contents of the template file
	 *
	 * @var string Main file contents
	 */
	public $filecontents = '';

	/**
	 * Unparsed blocks
	 *
	 * @var array Template blocks
	 */
	public $blocks = array();

	/**
	 * Parsed blocks
	 *
	 * @var array Parsed blocks
	 */
	public $parsed_blocks = array();

	/**
	 * Preparsed blocks (for file includes)
	 *
	 * @var array Pre-parsed blocks
	 */
	public $preparsed_blocks = array();

	/**
	 * Block parsing order for recursive parsing
	 * (Sometimes reverse :)
	 *
	 * @var array Block parsing order
	 */
	public $block_parse_order = array();

	/**
	 * Store sub-block names
	 * (For fast resetting)
	 *
	 * @var array Sub-blocks hierarchy
	 */
	public $sub_blocks = array();

	/**
	 * Variables array
	 *
	 * @var array Assigned variables
	 */
	public $vars = array();

	/**
	 * File variables array
	 *
	 * @var array Assigned file variables
	 */
	public $filevars = array();

	/**
	 * Filevars' parent block
	 *
	 * @var array File variable parents
	 */
	public $filevar_parent = array();

	/**
	 * File caching during duration of script
	 * e.g. files only cached to speed {FILE "filename"} repeats
	 *
	 * @var array File content cache
	 */
	public $filecache = array();

	/**
	 * Location of template files
	 *
	 * @var string Template directory
	 */
	public $tpldir = '';

	/**
	 * Filenames lookup table
	 *
	 * @var array|null Array of file paths
	 */
	public $files = null;

	/**
	 * Template filename
	 *
	 * @var string Current template filename
	 */
	public $filename = '';

	// NEW: Added in Seditio version for regex delimiter customization
	/**
	 * Delimiter used in preg regular expressions
	 *
	 * @var string Regular expression delimiter
	 */
	public $preg_delimiter = '`';

	// moved to setup method so uses the tag_start & end_delims
	/**
	 * RegEx for file includes
	 *
	 * "/\{FILE\s*\"([^\"]+)\"\s*\}/m";
	 *
	 * @var string File include delimiter pattern
	 */
	public $file_delim = '';

	/**
	 * RegEx for file include variable
	 *
	 * "/\{FILE\s*\{([A-Za-z0-9\._]+?)\}\s*\}/m";
	 *
	 * @var string File variable delimiter pattern
	 */
	public $filevar_delim = '';

	/**
	 * RegEx for file includes with newlines
	 *
	 * "/^\s*\{FILE\s*\{([A-Za-z0-9\._]+?)\}\s*\}\s*\n/m";
	 *
	 * @var string File variable delimiter pattern with newline
	 */
	public $filevar_delim_nl = '';

	/**
	 * Template block start delimiter
	 *
	 * @var string Block start delimiter
	 */
	public $block_start_delim = '<!--';

	/**
	 * Template block end delimiter
	 *
	 * @var string Block end delimiter
	 */
	public $block_end_delim = '-->';

	/**
	 * Template block start word
	 *
	 * @var string Block start keyword
	 */
	public $block_start_word = 'BEGIN:';

	/**
	 * Template block end word
	 *
	 * The last 3 properties and this make the delimiters look like:
	 * @example <!-- BEGIN: block_name -->
	 * if you use the default syntax.
	 *
	 * @var string Block end keyword
	 */
	public $block_end_word = 'END:';

	/**
	 * Template tag start delimiter
	 *
	 * This makes the delimiters look like:
	 * @example {tagname}
	 * if you use the default syntax.
	 *
	 * @var string Tag start delimiter
	 */
	public $tag_start_delim = '{';

	/**
	 * Template tag end delimiter
	 *
	 * This makes the delimiters look like:
	 * @example {tagname}
	 * if you use the default syntax.
	 *
	 * @var string Tag end delimiter
	 */
	public $tag_end_delim = '}';

	/**
	 * Regular expression element for comments within tags and blocks
	 *
	 * @example {tagname#My Comment}
	 * @example {tagname #My Comment}
	 * @example <!-- BEGIN: blockname#My Comment -->
	 * @example <!-- BEGIN: blockname #My Comment -->
	 *
	 * @var string Comment pattern
	 */
	public $comment_preg = '( ?#.*?)?';

	// NEW: Added in Seditio version for callback delimiters and regex
	/**
	 * Delimiter used for callback functions in tags
	 *
	 * @var string Comment delimiter
	 */
	public $comment_delim = '#';

	// NEW: Added in Seditio version for callback delimiters
	/**
	 * Delimiter used to separate callback functions in tags
	 *
	 * @var string Callback delimiter
	 */
	public $callback_delim = '|';

	// NEW: Added in Seditio version for callback support
	/**
	 * Regular expression pattern for callback functions
	 *
	 * @var string Callback pattern
	 */
	public $callback_preg = '[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*(\(.*?\))?';

	// NEW: Added in Seditio version to enable/disable callbacks
	/**
	 * Flag to allow callback functions in templates
	 *
	 * @var bool Enable callbacks
	 */
	public $allow_callbacks = true;

	// NEW: Added in Seditio version to define allowed callback functions
	/**
	 * Array of allowed callback functions
	 *
	 * @var array Allowed callback functions
	 */
	public $allowed_callbacks = [
		// Simple string modifiers
		'strtoupper', 'strtolower', 'ucwords', 'ucfirst', 'strrev', 'str_word_count', 'strlen',
		// String replacement modifiers
		'str_replace', 'str_ireplace', 'preg_replace', 'strip_tags', 'stripcslashes', 'stripslashes', 'substr',
		'str_pad', 'str_repeat', 'strtr', 'trim', 'ltrim', 'rtrim', 'nl2br', 'wordwrap', 'printf', 'sprintf',
		'addslashes', 'addcslashes',
		// Encoding / decoding modifiers
		'htmlentities', 'html_entity_decode', 'htmlspecialchars', 'htmlspecialchars_decode',
		'urlencode', 'urldecode',
		// Date / time modifiers
		'date', 'idate', 'strtotime', 'strftime', 'getdate', 'gettimeofday',
		// Number modifiers
		'number_format', 'money_format',
		// Miscellaneous modifiers
		'var_dump', 'print_r', 'crop', 'resize', 'crop_image', 'resize_image'
	];

	/**
	 * Default main template block name
	 *
	 * @var string Main block name
	 */
	public $mainblock = 'main';

	/**
	 * Script output type
	 *
	 * @var string Output type (e.g., 'HTML')
	 */
	public $output_type = 'HTML';

	// NEW: Added in Seditio version to force global variable initialization
	/**
	 * Flag to force initialization of global variables even with JIT enabled
	 *
	 * @var bool Force global variables
	 */
	public $force_globals = true;

	// NEW: Added in Seditio version for HTML output compression
	/**
	 * Flag to enable HTML output compression (minification)
	 *
	 * @var bool Enable HTML compression
	 */
	public $compress_output = false;

	/**
	 * Null string for unassigned vars
	 *
	 * @var array Default null string
	 */
	protected $_null_string = array('' => '');

	/**
	 * Null string for unassigned blocks
	 *
	 * @var array Default null block
	 */
	protected $_null_block = array('' => '');

	/**
	 * Errors
	 *
	 * @var array Error storage
	 */
	protected $_error = [];

	/**
	 * Auto-reset sub blocks
	 *
	 * @var bool Auto-reset sub-blocks
	 */
	protected $_autoreset = true;

	/**
	 * Set to FALSE to generate errors if a non-existent blocks is referenced
	 *
	 * @author NW
	 * @since 2002/10/17
	 * @var bool Ignore missing blocks
	 */
	protected $_ignore_missing_blocks = true;

	/**
	 * Keyword for starting an IF condition
	 * @var string
	 */
	public $if_delim = 'IF';

	/**
	 * Keyword for ELSE
	 * @var string
	 */
	public $else_delim = 'ELSE';

	/**
	 * Keyword for ending an IF condition
	 * @var string
	 */
	public $endif_delim = 'ENDIF';

	// NEW: Added in 2025 version for RPN evaluation
	/**
	 * Operator precedence for RPN evaluation
	 *
	 * @var array Operator precedence for RPN evaluation
	 */
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
	 * Debug mode
	 *
	 * @var bool Enable debugging
	 */
	public $debug = false;

	// NEW: Added in 2025 version for error handling
	/**
	 * Error handling mode ('log', 'display', 'both', 'none')
	 *
	 * @var string Error handling mode
	 */
	protected $_error_handling = 'display';

	// NEW: Added in 2025 version for error logging
	/**
	 * Error log file name
	 *
	 * @var string Error log file name
	 */
	protected $_error_log_file = 'xtemplate_errors.log';

	/**
	 * Constructor for the XTemplate class.
	 *
	 * @param mixed $options Options array or filename (can include 'file', 'path', 'files', 'mainblock', 'autosetup', 'tag_start', 'tag_end')
	 * @param string $tpldir Template directory
	 * @param array|null $files Array of files
	 * @param string $mainblock Main block name
	 * @param bool $autosetup Automatically setup the template
	 * @return XTemplate
	 */
	public function __construct($options, $tpldir = '', $files = null, $mainblock = 'main', $autosetup = true)
	{
		// NEW: Seditio version accepts an options array or string, with additional handling
		if (!is_array($options)) {
			$options = array('file' => $options, 'path' => $tpldir, 'files' => $files, 'mainblock' => $mainblock, 'autosetup' => $autosetup);
		}

		if (!isset($options['tag_start'])) {
			$options['tag_start'] = $this->tag_start_delim;
		}
		if (!isset($options['tag_end'])) {
			$options['tag_end'] = $this->tag_end_delim;
		}

		$this->restart($options);
	}

	/**
	 * Restart the class - allows one instantiation with several files processed by restarting
	 * e.g. $xtpl = new XTemplate('file1.xtpl');
	 * $xtpl->parse('main');
	 * $xtpl->out('main');
	 * $xtpl->restart('file2.xtpl');
	 * $xtpl->parse('main');
	 * $xtpl->out('main');
	 * (Added in response to sf:641407 feature request)
	 *
	 * @param mixed $options Options array or filename (can include 'file', 'path', 'files', 'mainblock', 'autosetup', 'tag_start', 'tag_end', 'callbacks', 'debug', 'error_handling', 'error_log_file')
	 * @param string $tpldir Template directory
	 * @param array|null $files Array of files
	 * @param string $mainblock Main block name
	 * @param bool $autosetup Automatically setup the template
	 * @param string $tag_start Starting delimiter for tags
	 * @param string $tag_end Ending delimiter for tags
	 */
	public function restart($options, $tpldir = '', $files = null, $mainblock = 'main', $autosetup = true, $tag_start = '{', $tag_end = '}')
	{
		// NEW: Seditio version processes options as an array or string with extended functionality
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
						// NEW: Added in 2025 version for error handling customization
					case 'error_handling':
						$this->_error_handling = in_array($value, ['log', 'display', 'both', 'none']) ? $value : 'none';
						break;
					case 'error_log_file':
						$this->_error_log_file = $value;
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
		$this->_error = []; // Reset errors

		// NEW: Seditio version adds callback regex preparation
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
	 * setup - the elements that were previously in the constructor
	 *
	 * @param bool $add_outer If true is passed when called, it adds an outer main block to the file
	 */
	public function setup($add_outer = false)
	{
		$this->tag_start_delim = preg_quote($this->tag_start_delim);
		$this->tag_end_delim = preg_quote($this->tag_end_delim);

		// Setup the file delimiters
		// NEW: Seditio version uses preg_delimiter for regex patterns
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

	/***************************************************************************/
	/***[ public stuff ]********************************************************/
	/***************************************************************************/

	/**
	 * assign a variable
	 *
	 * @example Simplest case:
	 * @example $xtpl->assign('name', 'value');
	 * @example {name} in template
	 *
	 * @example Array assign:
	 * @example $xtpl->assign(array('name' => 'value', 'name2' => 'value2'));
	 * @example {name} {name2} in template
	 *
	 * @example Value as array assign:
	 * @example $xtpl->assign('name', array('key' => 'value', 'key2' => 'value2'));
	 * @example {name.key} {name.key2} in template
	 *
	 * @example Reset array:
	 * @example $xtpl->assign('name', array('key' => 'value', 'key2' => 'value2'));
	 * @example // Other code then:
	 * @example $xtpl->assign('name', array('key3' => 'value3'), false);
	 * @example {name.key} {name.key2} {name.key3} in template
	 *
	 * @param mixed $name Variable name or array of variables
	 * @param mixed $val Variable value
	 * @param bool $reset_array Reset the array
	 */
	public function assign($name, $val = '', $reset_array = true)
	{
		// NEW: Seditio version adds support for objects in addition to arrays
		if (is_array($name) || is_object($name)) {
			foreach ($name as $k => $v) {
				$this->vars[$k] = $v;
			}
		} elseif (is_array($val) || is_object($val)) {
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
	 * assign a file variable
	 *
	 * @param mixed $name File variable name or array of file variables
	 * @param mixed $val File variable value
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
	 * parse a block
	 *
	 * @param string $bname Block name
	 */
	public function parse($bname)
	{
		if (isset($this->preparsed_blocks[$bname])) {
			$copy = $this->preparsed_blocks[$bname];
		} elseif (isset($this->blocks[$bname])) {
			$copy = $this->blocks[$bname];
		} elseif ($this->_ignore_missing_blocks) {
			// ------------------------------------------------------
			// NW : 17 Oct 2002. Added default of ignore_missing_blocks
			//      to allow for generalised processing where some
			//      blocks may be removed from the HTML without the
			//      processing code needing to be altered.
			// ------------------------------------------------------
			// JRC: 3/1/2003 added set error to ignore missing functionality
			$this->_set_error("parse: blockname [$bname] does not exist", "File: {$this->filename}, Block: $bname");
			return;
		} else {
			$this->_set_error("parse: blockname [$bname] does not exist", "File: {$this->filename}, Block: $bname");
		}

		if (!isset($copy)) {
			die('Block: ' . $bname);
		}

		// NEW: 2025 version processes IF/ELSE/ENDIF conditions
		$copy = $this->_process_conditions($copy);
		$copy = preg_replace($this->filevar_delim_nl, '', $copy);

		$var_array = array();
		// NEW: Seditio version uses preg_delimiter and adds callback support in regex
		preg_match_all($this->preg_delimiter . $this->tag_start_delim . '([A-Za-z0-9\._\x7f-\xff]+?' . $this->callback_preg . $this->comment_preg . ')' . $this->tag_end_delim . $this->preg_delimiter, $copy, $var_array);
		$var_array = $var_array[1];

		foreach ($var_array as $k => $v) {
			$orig_v = $v;
			// NEW: Seditio version uses comment_delim instead of # directly
			$comment = '';
			$any_comments = explode($this->comment_delim, $v);
			if (count($any_comments) > 1) {
				$comment = array_pop($any_comments);
			}
			$v = rtrim(implode($this->comment_delim, $any_comments));

			// NEW: Seditio version adds callback function processing
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
					// NEW: Seditio version uses mb_substr instead of substr for multibyte support
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
				$var = $this->vars;
				foreach ($sub as $v1) {
					// NEW: Seditio version adds object support and uses mb_strlen
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

				// NEW: Seditio version adds callback function execution
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

				// NEW: Seditio version adds special handling for string variables
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
	 * returns the parsed text for a block, including all sub-blocks.
	 *
	 * @param string $bname Block name
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
	 * inserts a loop ( call assign & parse )
	 *
	 * @param string $bname Block name
	 * @param string $var Variable name
	 * @param mixed $value Variable value
	 */
	public function insert_loop($bname, $var, $value = '')
	{
		$this->assign($var, $value);
		$this->parse($bname);
	}

	/**
	 * Compresses HTML content while preserving <pre> and <code> blocks.
	 *
	 * @param string $out The HTML content to be compressed
	 * @return string The compressed HTML content
	 */
	public function compress($out)
	{
		// NEW: 2025 version enhances compression by preserving <pre>, <code>, and <textarea> blocks
		$patterns = [
			'pre' => '/<pre\b[^>]*>(.*?)<\/pre>/si',
			'code' => '/<code\b[^>]*>(.*?)<\/code>/si',
			'textarea' => '/<textarea\b[^>]*>(.*?)<\/textarea>/si'
		];

		$replacements = [];
		foreach ($patterns as $type => $pattern) {
			$matches = [];
			preg_match_all($pattern, $out, $matches, PREG_SET_ORDER);
			foreach ($matches as $match) {
				$content = $match[1];
				$placeholder = "%%{$type}_" . md5($content) . "%%";
				$out = str_replace($match[0], $placeholder, $out);
				$replacements[$placeholder] = $match[0];
			}
		}

		$out = preg_replace("/(?:(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:(?<!\:|\\\|\'|\")\/\/.*))/", "", $out);
		$out = preg_replace('/\s+(?=<)/', '', $out);
		$out = preg_replace('/(>)\s+/', '$1', $out);
		$out = preg_replace('/\s+/', ' ', $out);

		foreach ($replacements as $placeholder => $original) {
			$out = str_replace($placeholder, $original, $out);
		}

		return $out;
	}

	/**
	 * parses a block for every set of data in the values array
	 *
	 * @param string $bname Block name
	 * @param string $var Variable name
	 * @param array $values Array of values
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
	 * returns the parsed text for a block
	 *
	 * @param string $bname Block name
	 * @return string Block text or error message if block is not found and debug is enabled
	 */
	public function text($bname = '')
	{
		$text = '';

		if ($this->debug && $this->output_type == 'HTML') {
			// NEW: Seditio version enhances debug output with more detailed path info
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
		// Return parsed block content, or error if debug is on, otherwise empty string
		$text .= isset($this->parsed_blocks[$bname])
			? $this->parsed_blocks[$bname]
			: ($this->debug ? $this->get_error() : '');

		return $text;
	}

	/**
	 * prints the parsed text
	 *
	 * @param string $bname Block name
	 */
	public function out($bname)
	{
		$out = $this->text($bname);
		// NEW: Seditio version adds trimming and optional compression
		$out = preg_replace('/\s+$/m', '', $out);
		if ($this->compress_output) {
			$out = $this->compress($out);
		}
		// NEW: 2025 version adds error handling output
		if ($this->debug && !empty($this->_error)) {
			switch ($this->_error_handling) {
				case 'display':
					$out = "<!-- XTemplate Errors for Block: $bname -->\n" . $this->get_error() . "\n" . $out;
					break;
				case 'log':
					$this->log_errors();
					break;
				case 'both':
					$out = "<!-- XTemplate Errors for Block: $bname -->\n" . $this->get_error() . "\n" . $out;
					$this->log_errors();
					break;
				case 'none':
					break;
			}
		}

		echo trim($out);
	}

	/**
	 * Log errors to a file.
	 *
	 * @return void
	 */
	protected function log_errors()
	{
		if (!empty($this->_error)) {
			$log_message = "XTemplate Errors (" . date('Y-m-d H:i:s') . "):\n";
			foreach ($this->_error as $error) {
				$log_message .= "[{$error['timestamp']}] {$error['message']} (Context: {$error['context']})\n";
			}
			file_put_contents($this->_error_log_file, $log_message, FILE_APPEND);
		}
	}

	/**
	 * prints the parsed text to a specified file
	 *
	 * @param string $bname Block name
	 * @param string $fname File name
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
	 * resets the parsed text
	 *
	 * @param string $bname Block name
	 */
	public function reset($bname)
	{
		$this->parsed_blocks[$bname] = '';
	}

	/**
	 * returns true if block was parsed, false if not
	 *
	 * @param string $bname Block name
	 * @return bool True if parsed, false otherwise
	 */
	public function parsed($bname)
	{
		return (!empty($this->parsed_blocks[$bname]));
	}

	/**
	 * sets the string to replace in case the var was not assigned
	 *
	 * @param string $str Null string
	 * @param string $varname Variable name
	 */
	public function set_null_string($str, $varname = '')
	{
		$this->_null_string[$varname] = $str;
	}

	/**
	 * Backwards compatibility only
	 *
	 * @param string $str Null string
	 * @param string $varname Variable name
	 * @deprecated Change to set_null_string to keep in with rest of naming convention
	 */
	public function SetNullString($str, $varname = '')
	{
		$this->set_null_string($str, $varname);
	}

	/**
	 * sets the string to replace in case the block was not parsed
	 *
	 * @param string $str Null block
	 * @param string $bname Block name
	 */
	public function set_null_block($str, $bname = '')
	{
		$this->_null_block[$bname] = $str;
	}

	/**
	 * Backwards compatibility only
	 *
	 * @param string $str Null block
	 * @param string $bname Block name
	 * @deprecated Change to set_null_block to keep in with rest of naming convention
	 */
	public function SetNullBlock($str, $bname = '')
	{
		$this->set_null_block($str, $bname);
	}

	/**
	 * sets AUTORESET to 1. (default is 1)
	 * if set to 1, parse() automatically resets the parsed blocks' sub blocks
	 * (for multiple level blocks)
	 */
	public function set_autoreset()
	{
		$this->_autoreset = true;
	}

	/**
	 * sets AUTORESET to 0. (default is 1)
	 * if set to 1, parse() automatically resets the parsed blocks' sub blocks
	 * (for multiple level blocks)
	 */
	public function clear_autoreset()
	{
		$this->_autoreset = false;
	}

	/**
	 * scans global variables and assigns to PHP array
	 */
	public function scan_globals()
	{
		// NEW: Seditio version enhances global scanning with force_globals and specific handling
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
	 * gets error condition / string
	 *
	 * @return string|bool Error message or false if no errors
	 */
	public function get_error()
	{
		// JRC: 3/1/2003 Added output wrapper and detection of output type for error message output
		$retval = false;

		if (!empty($this->_error)) {
			// NEW: 2025 version enhances error output with timestamp and context
			$error_html = '<b>[XTemplate Errors]</b><ul>';
			foreach ($this->_error as $error) {
				$error_html .= "<li>{$error['message']} (Context: {$error['context']}, Time: {$error['timestamp']})</li>";
			}
			$error_html .= '</ul>';

			switch ($this->output_type) {
				case 'HTML':
				case 'html':
					$retval = $error_html;
					break;
				default:
					$retval = '[XTemplate] ' . implode("\n", array_map(function ($e) {
						return "{$e['message']} (Context: {$e['context']}, Time: {$e['timestamp']})";
					}, $this->_error));
					break;
			}
		}

		return $retval;
	}

	/***************************************************************************/
	/***[ private stuff ]*******************************************************/
	/***************************************************************************/

	/**
	 * generates the array containing to-be-parsed stuff:
	 * $blocks["main"],$blocks["main.table"],$blocks["main.table.row"], etc.
	 * also builds the reverse parse order.
	 *
	 * @param string $con Template content
	 * @param string $parentblock Parent block name
	 * @return array Tree structure of blocks
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

		// JRC 06/04/2005 Added block comments (on BEGIN or END) <!-- BEGIN: block_name#Comments placed here -->
		$patt = "(" . $this->block_start_word . "|" . $this->block_end_word . ")\s*(\w+)" . $this->comment_preg . "\s*" . $this->block_end_delim . "(.*)";

		foreach ($con2 as $k => $v) {
			$res = array();

			// NEW: Seditio version uses preg_delimiter in pattern matching
			if (preg_match_all($this->preg_delimiter . "$patt" . $this->preg_delimiter . 'ims', $v, $res, PREG_SET_ORDER)) {
				$block_word = $res[0][1];
				$block_name = $res[0][2];
				$comment = $res[0][3];
				$content = $res[0][4];

				// NEW: Seditio version uses mb_strtoupper instead of strtoupper
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
	 * Assign a file variable to the template (internal).
	 *
	 * @param string $name File variable name
	 * @param string $val File variable value
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
								// NEW: Seditio version uses preg_delimiter in replacement
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
	 * store container block's name for file variables
	 *
	 * @param array $blocks Blocks array
	 * @return array File variable parents
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
	 * Set an error message with context (internal).
	 *
	 * @param string $str Error message
	 * @param string $context Additional context (e.g., file and block)
	 */
	private function _set_error($str, $context = '')
	{
		// JRC: 3/1/2003 Made to append the error messages
		// NEW: 2025 version stores errors as an array with timestamp and context
		if (!is_array($this->_error)) {
			$this->_error = [];
		}
		$this->_error[] = [
			'message' => $str,
			'context' => $context ?: "File: {$this->filename}, Block: Unknown",
			'timestamp' => date('Y-m-d H:i:s')
		];
		// JRC: 3/1/2003 Removed trigger error, use this externally if you want it eg. trigger_error($xtpl->get_error())
	}

	/**
	 * returns the contents of a file
	 *
	 * @param string $file File name
	 * @return string File content
	 */
	protected function _getfile($file)
	{
		if (!isset($file)) {
			$this->_set_error('!isset file name!' . $file, "File: {$this->filename}");
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
			// NEW: Seditio version enhances debug output for cached files
			if ($this->debug && $this->output_type == 'HTML') {
				$file_text = '<!-- XTemplate debug CACHED: ' . realpath($file) . ' -->' . "\n" . $file_text;
			}
		} else {
			if (is_file($file) && is_readable($file)) {
				if (filesize($file)) {
					if (!($fh = fopen($file, 'r'))) {
						$this->_set_error('Cannot open file: ' . realpath($file), "File: {$this->filename}");
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
					$this->_set_error("[" . realpath($file) . "] ($file) does not exist", "File: {$this->filename}");
					// NEW: Seditio version adjusts error output based on output_type
					if ($this->output_type == 'HTML') {
						$file_text = "<b>__XTemplate fatal error: file [$file] does not exist in the include path__</b>";
					}
				} elseif ($this->debug && $this->output_type == 'HTML') {
					$file_text = '<!-- XTemplate debug (via include path): ' . realpath($file) . ' -->' . "\n" . $file_text;
				}
			} elseif (!is_file($file)) {
				$this->_set_error("[" . realpath($file) . "] ($file) does not exist", "File: {$this->filename}");
				if ($this->output_type == 'HTML') {
					$file_text .= "<b>__XTemplate fatal error: file [$file] does not exist__</b>";
				}
			} elseif (!is_readable($file)) {
				$this->_set_error("[" . realpath($file) . "] ($file) is not readable", "File: {$this->filename}");
				if ($this->output_type == 'HTML') {
					$file_text .= "<b>__XTemplate fatal error: file [$file] is not readable__</b>";
				}
			}
			$this->filecache[$file] = $file_text;
		}

		return $file_text;
	}

	/**
	 * recursively gets the content of a file with {FILE "filename.tpl"} directives
	 *
	 * @param string $file File name
	 * @return string File content
	 */
	public function _r_getfile($file)
	{
		$text = $this->_getfile($file);
		$res = array();

		// NEW: Seditio version uses preg_delimiter in file inclusion
		while (preg_match($this->file_delim, $text, $res)) {
			$text2 = $this->_getfile($res[1]);
			$text = preg_replace($this->preg_delimiter . preg_quote($res[0]) . $this->preg_delimiter, $text2, $text);
		}

		return $text;
	}

	// NEW: Seditio version adds trim_callback for processing callback parameters
	/**
	 * Trims and processes callback parameters
	 *
	 * @param string $value Callback parameter
	 */
	protected function trim_callback(&$value)
	{
		$value = preg_replace($this->preg_delimiter . "^.*(%s).*$" . $this->preg_delimiter, '\\1', trim($value));
		$value = preg_replace($this->preg_delimiter . '^,?\s*?(.*?)[,|\)]?$' . $this->preg_delimiter, '\\1', trim($value));
		$value = preg_replace($this->preg_delimiter . '^[\'|"]?(.*?)[\'|"]?$' . $this->preg_delimiter, '\\1', trim($value));
		$value = preg_replace($this->preg_delimiter . '\\\\(?=\'|")' . $this->preg_delimiter, '', $value);
		$value = preg_replace($this->preg_delimiter . '\\\,' . $this->preg_delimiter, ',', $value);
	}

	// NEW: Added in 2025 version for conditional logic
	/**
	 * Tokenize an expression for condition evaluation.
	 *
	 * @param string $expression Expression to tokenize
	 * @return array Tokens
	 */
	protected function _tokenize($expression)
	{
		$pattern = '`\s*(?:(\d*\.\d+|\d+)|([+\-*/%()]|&&|\s*\|\|\s*|==|!=|<=|>=|<|>)|(?:"([^"]*)")|(?:\'([^\']*)\')|([A-Za-z0-9\._\x7f-\xff]+))\s*`';
		preg_match_all($pattern, $expression, $matches, PREG_SET_ORDER);

		$tokens = [];
		foreach ($matches as $match) {
			if (isset($match[1]) && $match[1] !== '') {
				$tokens[] = ['type' => 'number', 'value' => $match[1]];
			} elseif (isset($match[2]) && $match[2] !== '') {
				$tokens[] = ['type' => 'operator', 'value' => trim($match[2])]; // Убираем пробелы из оператора
			} elseif (isset($match[3]) && $match[3] !== '') {
				$tokens[] = ['type' => 'string', 'value' => $match[3]];
			} elseif (isset($match[4]) && $match[4] !== '') {
				$tokens[] = ['type' => 'string', 'value' => $match[4]];
			} elseif (isset($match[5]) && $match[5] !== '') {
				$tokens[] = ['type' => 'variable', 'value' => $match[5]];
			}
		}

		return $tokens;
	}

	// NEW: Added in 2025 version for conditional logic
	/**
	 * Convert tokens to Reverse Polish Notation (RPN) using Shunting Yard algorithm.
	 *
	 * @param array $tokens Tokens
	 * @return array RPN
	 */
	protected function _shunting_yard($tokens)
	{
		$output = []; // Output queue for RPN
		$stack = [];  // Operator stack

		foreach ($tokens as $token) {
			// Handle operands (numbers, variables, strings) by adding them directly to output
			if ($token['type'] === 'number' || $token['type'] === 'variable' || $token['type'] === 'string') {
				$output[] = $token;
			} elseif ($token['type'] === 'operator') {
				if ($token['value'] === '(') {
					// Push opening parenthesis onto the stack
					$stack[] = $token;
				} elseif ($token['value'] === ')') {
					// Pop operators from stack to output until matching '(' is found
					while (!empty($stack) && $stack[count($stack) - 1]['value'] !== '(') {
						$output[] = array_pop($stack);
					}
					if (!empty($stack) && $stack[count($stack) - 1]['value'] === '(') {
						// Remove the matching '(' from the stack
						array_pop($stack);
					} else {
						// Error: no matching opening parenthesis
						$this->_set_error("Mismatched parentheses: no matching opening parenthesis", "File: {$this->filename}");
						return [];
					}
				} else {
					// Handle other operators (e.g., +, -, *, /, ==, &&, ||)
					while (
						!empty($stack) &&
						$stack[count($stack) - 1]['type'] === 'operator' &&
						$stack[count($stack) - 1]['value'] !== '(' && // Skip if top is '('
						isset($this->operator_precedence[$stack[count($stack) - 1]['value']]) && // Check if top operator has precedence
						isset($this->operator_precedence[$token['value']]) && // Check if current operator has precedence
						$this->operator_precedence[$stack[count($stack) - 1]['value']] >= $this->operator_precedence[$token['value']]
					) {
						$output[] = array_pop($stack);
					}
					// Push the current operator onto the stack
					$stack[] = $token;
				}
			}
		}

		// Empty the stack into output, checking for unmatched parentheses
		while (!empty($stack)) {
			$top = array_pop($stack);
			if ($top['value'] === '(') {
				// Error: unclosed parenthesis found
				$this->_set_error("Mismatched parentheses: unclosed parenthesis", "File: {$this->filename}");
				return [];
			}
			$output[] = $top;
		}

		// Debug: Log the resulting RPN expression
		if ($this->debug) {
			$rpn_str = implode(' ', array_map(function ($t) {
				return $t['value'];
			}, $output));
			error_log("RPN for expression: $rpn_str");
		}

		return $output;
	}

	// NEW: Added in 2025 version for conditional logic
	/**
	 * Get the value of a variable.
	 *
	 * @param string $var_name Variable name
	 * @return mixed Variable value
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
				$this->_set_error("Variable {$var_name} not found or inaccessible at part: $part", "File: {$this->filename}");
				return 0;
			}
		}

		return $value;
	}

	// NEW: Added in 2025 version for conditional logic
	/**
	 * Evaluate Reverse Polish Notation (RPN).
	 *
	 * @param array $rpn RPN
	 * @return mixed Evaluation result
	 */
	protected function _evaluate_rpn($rpn)
	{
		$stack = [];

		foreach ($rpn as $token) {
			if ($token['type'] === 'number') {
				$stack[] = (float)$token['value']; // Явно приводим к числу
			} elseif ($token['type'] === 'string') {
				$stack[] = $token['value'];
			} elseif ($token['type'] === 'variable') {
				$value = $this->_get_variable_value($token['value']);
				$stack[] = $value !== null ? $value : 0;
			} elseif ($token['type'] === 'operator') {
				if (count($stack) < 2) {
					$this->_set_error("Insufficient operands for operator: " . $token['value'], "File: {$this->filename}");
					return null;
				}
				$b = array_pop($stack);
				$a = array_pop($stack);
				$result = $this->_apply_operator($token['value'], $a, $b);
				if ($result === null) {
					return null;
				}
				$stack[] = $result;
			}
		}

		if (count($stack) !== 1) {
			$this->_set_error("Invalid RPN expression: stack imbalance", "File: {$this->filename}");
			return null;
		}

		return array_pop($stack);
	}

	// NEW: Added in 2025 version for conditional logic
	/**
	 * Evaluate a condition.
	 *
	 * @param string $condition Condition to evaluate
	 * @return bool Evaluation result
	 */
	protected function _evaluate_condition($condition)
	{
		$tokens = $this->_tokenize($condition);
		$rpn = $this->_shunting_yard($tokens);
		return (bool) $this->_evaluate_rpn($rpn);
	}

	// NEW: Added in 2025 version for conditional logic
	/**
	 * Apply an operator to two values.
	 *
	 * @param string $operator Operator
	 * @param mixed $a First value
	 * @param mixed $b Second value
	 * @return mixed Result of the operation
	 */
	protected function _apply_operator($operator, $a, $b)
	{
		$a = is_numeric($a) ? (float)$a : $a;
		$b = is_numeric($b) ? (float)$b : $b;

		switch ($operator) {
			case '+':
				return $a + $b;
			case '-':
				return $a - $b;
			case '*':
				return $a * $b;
			case '/':
				if ($b == 0) {
					$this->_set_error("Division by zero in condition", "File: {$this->filename}");
					return null;
				}
				return $a / $b;
			case '%':
				if ($b == 0) {
					$this->_set_error("Modulo by zero in condition", "File: {$this->filename}");
					return null;
				}
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
				$this->_set_error("Unknown operator: $operator", "File: {$this->filename}");
				return null;
		}
	}

	// NEW: Added in 2025 version for conditional logic
	/**
	 * Process conditions in the template content recursively.
	 *
	 * This method evaluates IF/ELSE/ENDIF conditions by tokenizing the content and processing
	 * nested conditions from the deepest level outward.
	 *
	 * @param string $content Template content to process
	 * @return string Processed content with conditions evaluated
	 */
	protected function _process_conditions($content)
	{
		$tokens = [];
		$pos = 0;
		$length = mb_strlen($content);

		$if_full_delim = $this->block_start_delim . ' ' . $this->if_delim . ' ';  // <!-- IF 
		$else_full_delim = $this->block_start_delim . ' ' . $this->else_delim . ' ' . $this->block_end_delim;  // <!-- ELSE -->
		$endif_full_delim = $this->block_start_delim . ' ' . $this->endif_delim . ' ' . $this->block_end_delim;  // <!-- ENDIF -->

		while ($pos < $length) {
			$if_start = mb_strpos($content, $if_full_delim, $pos);
			$else_pos = mb_strpos($content, $else_full_delim, $pos);
			$endif_pos = mb_strpos($content, $endif_full_delim, $pos);

			$next_positions = array_filter([
				'if' => $if_start !== false ? $if_start : null,
				'else' => $else_pos !== false ? $else_pos : null,
				'endif' => $endif_pos !== false ? $endif_pos : null
			], function ($val) {
				return $val !== null;
			});

			if (empty($next_positions)) {
				$tokens[] = mb_substr($content, $pos);
				break;
			}

			$next_pos = min($next_positions);
			$next_type = array_search($next_pos, $next_positions);

			if ($pos < $next_pos) {
				$tokens[] = mb_substr($content, $pos, $next_pos - $pos);
			}

			if ($next_type === 'if') {
				$if_end = mb_strpos($content, $this->block_end_delim, $if_start);
				if ($if_end === false) {
					$this->_set_error("Unclosed IF block detected, start at position: $if_start", "File: {$this->filename}");
					$tokens[] = mb_substr($content, $if_start);
					break;
				}
				$condition = mb_substr($content, $if_start + mb_strlen($if_full_delim), $if_end - ($if_start + mb_strlen($if_full_delim)));
				$tokens[] = ['type' => 'if', 'condition' => trim($condition)];
				$pos = $if_end + mb_strlen($this->block_end_delim);
			} elseif ($next_type === 'else') {
				$tokens[] = ['type' => 'else'];
				$pos = $else_pos + mb_strlen($else_full_delim);
			} elseif ($next_type === 'endif') {
				$tokens[] = ['type' => 'endif'];
				$pos = $endif_pos + mb_strlen($endif_full_delim);
			}
		}

		return $this->_process_tokens($tokens);
	}

	// NEW: Added in 2025 version for conditional logic
	/**
	 * Process an array of tokens to evaluate conditions.
	 *
	 * @param array $tokens Array of tokens (text or condition blocks)
	 * @param int $start Starting index for processing
	 * @param int|null $end Ending index for processing (null for end of array)
	 * @return string Processed content
	 */
	protected function _process_tokens($tokens, $start = 0, $end = null)
	{
		if ($end === null) {
			$end = count($tokens);
		}

		$result = '';
		$i = $start;
		while ($i < $end) {
			$token = $tokens[$i];

			if (is_string($token)) {
				$result .= $token;
				$i++;
			} elseif ($token['type'] === 'if') {
				// Find the end of this IF block
				$nesting = 0;
				$else_index = null;
				$endif_index = null;

				for ($j = $i + 1; $j < $end; $j++) {
					if (is_array($tokens[$j])) {
						if ($tokens[$j]['type'] === 'if') {
							$nesting++;
						} elseif ($tokens[$j]['type'] === 'else' && $nesting === 0) {
							$else_index = $j;
						} elseif ($tokens[$j]['type'] === 'endif') {
							if ($nesting === 0) {
								$endif_index = $j;
								break;
							}
							$nesting--;
						}
					}
				}

				// Process IF content
				if ($endif_index === null) {
					// Unclosed IF block, process content without error here (handled at top level)
					$if_content = $this->_process_tokens($tokens, $i + 1, $end);
					if (empty($token['condition'])) {
						$result .= $if_content;
					} else {
						$condition_result = $this->_evaluate_condition($token['condition']);
						$result .= $condition_result === true ? $if_content : '';
					}
					break;
				}

				$if_content = $this->_process_tokens($tokens, $i + 1, $else_index ?? $endif_index);
				$else_content = $else_index !== null ? $this->_process_tokens($tokens, $else_index + 1, $endif_index) : '';

				if (empty($token['condition'])) {
					$this->_set_error("Empty condition in IF block", "File: {$this->filename}");
					$result .= $if_content;
				} else {
					$condition_result = $this->_evaluate_condition($token['condition']);
					if ($condition_result === null) {
						$this->_set_error("Failed to evaluate condition: {$token['condition']}", "File: {$this->filename}");
						$result .= $if_content;
					} else {
						$result .= $condition_result ? $if_content : $else_content;
					}
				}

				$i = $endif_index + 1;
			} else {
				// Skip unexpected ELSE or ENDIF
				$i++;
			}
		}

		return $result;
	}

	/**
	 * add an outer block delimiter set useful for rtfs etc - keeps them editable in word
	 */
	private function _add_outer_block()
	{
		$before = $this->block_start_delim . $this->block_start_word . ' ' . $this->mainblock . ' ' . $this->block_end_delim;
		$after = $this->block_start_delim . $this->block_end_word . ' ' . $this->mainblock . ' ' . $this->block_end_delim;
		$this->filecontents = $before . "\n" . $this->filecontents . "\n" . $after;
	}

	/**
	 * Debug function - var_dump wrapped in '<pre></pre>' tags
	 *
	 * @param mixed $args Arguments to var_dump
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
	 * Debug function with output buffering
	 *
	 * @param mixed $args Arguments to var_dump
	 * @return string|null Output of var_dump or null if debug is off
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
