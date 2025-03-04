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
 */
class XTemplate
{
	/** @var string Main file contents */
	public $filecontents = '';

	/** @var array Template blocks */
	public $blocks = array();

	/** @var array Parsed blocks */
	public $parsed_blocks = array();

	/** @var array Pre-parsed blocks */
	public $preparsed_blocks = array();

	/** @var array Block parsing order */
	public $block_parse_order = array();

	/** @var array Sub-blocks hierarchy */
	public $sub_blocks = array();

	/** @var array Assigned variables */
	public $vars = array();

	/** @var array Assigned file variables */
	public $filevars = array();

	/** @var array File variable parents */
	public $filevar_parent = array();

	/** @var array File content cache */
	public $filecache = array();

	/** @var string Template directory */
	public $tpldir = '';

	/** @var array|null Array of file paths */
	public $files = null;

	/** @var string Current template filename */
	public $filename = '';

	/** @var string Regular expression delimiter */
	public $preg_delimiter = '`';

	/** @var string File include delimiter pattern */
	public $file_delim = '';

	/** @var string File variable delimiter pattern */
	public $filevar_delim = '';

	/** @var string File variable delimiter pattern with newline */
	public $filevar_delim_nl = '';

	/** @var string Block start delimiter */
	public $block_start_delim = '<!-- ';

	/** @var string Block end delimiter */
	public $block_end_delim = '-->';

	/** @var string Block start keyword */
	public $block_start_word = 'BEGIN:';

	/** @var string Block end keyword */
	public $block_end_word = 'END:';

	/** @var string Tag start delimiter */
	public $tag_start_delim = '{';

	/** @var string Tag end delimiter */
	public $tag_end_delim = '}';

	/** @var string Comment delimiter */
	public $comment_delim = '#';

	/** @var string Comment pattern */
	public $comment_preg = '( ?#.*?)?';

	/** @var string Callback delimiter */
	public $callback_delim = '|';

	/** @var string Callback pattern */
	public $callback_preg = '[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*(\(.*?\))?';

	/** @var bool Enable callbacks */
	public $allow_callbacks = true;

	/** @var array Allowed callback functions */
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

	/** @var string Main block name */
	public $mainblock = 'main';

	/** @var string Output type (e.g., 'HTML') */
	public $output_type = 'HTML';

	/** @var bool Force global variables */
	public $force_globals = true;

	/** @var bool Enable HTML compression */
	public $compress_output = false;

	/** @var array Default null string */
	protected $_null_string = array('' => '');

	/** @var array Default null block */
	protected $_null_block = array('' => '');

	/** @var array Error storage */
	protected $_error = [];

	/** @var bool Auto-reset sub-blocks */
	protected $_autoreset = true;

	/** @var bool Ignore missing blocks */
	protected $_ignore_missing_blocks = true;

	/** @var string IF condition start delimiter */
	public $if_start_delim = '<!-- IF ';

	/** @var string IF condition end delimiter */
	public $if_end_delim = ' -->';

	/** @var string ELSE delimiter */
	public $else_delim = '<!-- ELSE -->';

	/** @var string ENDIF delimiter */
	public $endif_delim = '<!-- ENDIF -->';

	/** @var string IF condition pattern */
	public $if_preg = '`<!-- IF (.*?) -->`';

	/** @var string ELSE pattern */
	public $else_preg = '`<!-- ELSE -->`';

	/** @var string ENDIF pattern */
	public $endif_preg = '`<!-- ENDIF -->`';

	/** @var array Operator precedence for RPN evaluation */
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

	/** @var bool Enable debugging */
	public $debug = false;

	/** @var string Error handling mode ('log', 'display', 'both', 'none') */
	protected $_error_handling = 'display';

	/** @var string Error log file name */
	protected $_error_log_file = 'xtemplate_errors.log';

	/**
	 * Constructor for the XTemplate class.
	 *
	 * @param mixed $options Options array or filename
	 * @param string $tpldir Template directory
	 * @param array|null $files Array of files
	 * @param string $mainblock Main block name
	 * @param bool $autosetup Automatically setup the template
	 */
	public function __construct($options, $tpldir = '', $files = null, $mainblock = 'main', $autosetup = true)
	{
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
	 * Restart the template engine with new options.
	 *
	 * @param mixed $options Options array or filename
	 * @param string $tpldir Template directory
	 * @param array|null $files Array of files
	 * @param string $mainblock Main block name
	 * @param bool $autosetup Automatically setup the template
	 * @param string $tag_start Starting delimiter for tags
	 * @param string $tag_end Ending delimiter for tags
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
	 * @param bool $add_outer Add outer block
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
	 * @param mixed $name Variable name or array of variables
	 * @param mixed $val Variable value
	 * @param bool $reset_array Reset the array
	 */
	public function assign($name, $val = '', $reset_array = true)
	{
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
	 * Assign a file to the template.
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
	 * Parse a block in the template.
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
			$this->_set_error("parse: blockname [$bname] does not exist", "File: {$this->filename}, Block: $bname");
			return;
		} else {
			$this->_set_error("parse: blockname [$bname] does not exist", "File: {$this->filename}, Block: $bname");
		}

		if (!isset($copy)) {
			die('Block: ' . $bname);
		}

		$copy = $this->_process_conditions($copy);
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
	 * Insert a loop into the template.
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
	 * Loop through an array and insert into the template.
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
	 * Get the text of a block.
	 *
	 * @param string $bname Block name
	 * @return string Block text or error message if block is not found and debug is enabled
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
		// Return parsed block content, or error if debug is on, otherwise empty string
		$text .= isset($this->parsed_blocks[$bname])
			? $this->parsed_blocks[$bname]
			: ($this->debug ? $this->get_error() : '');

		return $text;
	}

	/**
	 * Output the text of a block.
	 *
	 * @param string $bname Block name
	 */
	public function out($bname)
	{
		$out = $this->text($bname);
		$out = preg_replace('/\s+$/m', '', $out);
		if ($this->compress_output) {
			$out = $this->compress($out);
		}
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
	 * Output the text of a block to a file.
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
	 * Reset a block.
	 *
	 * @param string $bname Block name
	 */
	public function reset($bname)
	{
		$this->parsed_blocks[$bname] = '';
	}

	/**
	 * Check if a block has been parsed.
	 *
	 * @param string $bname Block name
	 * @return bool True if parsed, false otherwise
	 */
	public function parsed($bname)
	{
		return (!empty($this->parsed_blocks[$bname]));
	}

	/**
	 * Set the null string for a variable.
	 *
	 * @param string $str Null string
	 * @param string $varname Variable name
	 */
	public function set_null_string($str, $varname = '')
	{
		$this->_null_string[$varname] = $str;
	}

	/**
	 * Set the null string for a variable (alias).
	 *
	 * @param string $str Null string
	 * @param string $varname Variable name
	 */
	public function SetNullString($str, $varname = '')
	{
		$this->set_null_string($str, $varname);
	}

	/**
	 * Set the null block for a block.
	 *
	 * @param string $str Null block
	 * @param string $bname Block name
	 */
	public function set_null_block($str, $bname = '')
	{
		$this->_null_block[$bname] = $str;
	}

	/**
	 * Set the null block for a block (alias).
	 *
	 * @param string $str Null block
	 * @param string $bname Block name
	 */
	public function SetNullBlock($str, $bname = '')
	{
		$this->set_null_block($str, $bname);
	}

	/**
	 * Enable auto-reset for blocks.
	 */
	public function set_autoreset()
	{
		$this->_autoreset = true;
	}

	/**
	 * Disable auto-reset for blocks.
	 */
	public function clear_autoreset()
	{
		$this->_autoreset = false;
	}

	/**
	 * Scan for global variables and assign them.
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
	 * Get the formatted error message with context.
	 *
	 * @return string|bool Error message or false if no errors
	 */
	public function get_error()
	{
		$retval = false;

		if (!empty($this->_error)) {
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

	/**
	 * Create a tree structure from the template content.
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

		$patt = "(" . $this->block_start_word . "|" . $this->block_end_word . ")\s*(\w+)" . $this->comment_preg . "\s*" . $this->block_end_delim . "(.*)";

		foreach ($con2 as $k => $v) {
			$res = array();

			if (preg_match_all($this->preg_delimiter . "$patt" . $this->preg_delimiter . 'ims', $v, $res, PREG_SET_ORDER)) {
				$block_word = $res[0][1];
				$block_name = $res[0][2];
				$comment = $res[0][3];
				$content = $res[0][4];

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
		if (!is_array($this->_error)) {
			$this->_error = [];
		}
		$this->_error[] = [
			'message' => $str,
			'context' => $context ?: "File: {$this->filename}, Block: Unknown",
			'timestamp' => date('Y-m-d H:i:s')
		];
	}

	/**
	 * Get the content of a file (internal).
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
	 * Recursively get the content of a file with includes.
	 *
	 * @param string $file File name
	 * @return string File content
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
	 * Tokenize an expression for condition evaluation.
	 *
	 * @param string $expression Expression to tokenize
	 * @return array Tokens
	 */
	protected function _tokenize($expression)
	{
		$pattern = '`\s*(?:(\d*\.\d+|\d+)|([+\-*/%()]|&&|\|\||==|!=|<=|>=|<|>)|(?:' . preg_quote($this->tag_start_delim) . ')?([A-Za-z0-9\._\x7f-\xff]+)(?:' . preg_quote($this->tag_end_delim) . ')?)\s*`';
		preg_match_all($pattern, $expression, $matches, PREG_SET_ORDER);

		$tokens = [];
		foreach ($matches as $match) {
			if (isset($match[1]) && $match[1] !== '') {
				$tokens[] = ['type' => 'number', 'value' => $match[1]];
			} elseif (isset($match[2]) && $match[2] !== '') {
				$tokens[] = ['type' => 'operator', 'value' => $match[2]];
			} elseif (isset($match[3]) && $match[3] !== '') {
				$var_name = trim($match[0], $this->tag_start_delim . $this->tag_end_delim . " \t\n\r\0\x0B");
				$tokens[] = ['type' => 'variable', 'value' => $var_name];
			}
		}

		return $tokens;
	}

	/**
	 * Convert tokens to Reverse Polish Notation (RPN) using Shunting Yard algorithm.
	 *
	 * @param array $tokens Tokens
	 * @return array RPN
	 */
	protected function _shunting_yard($tokens)
	{
		$output = [];
		$stack = [];

		foreach ($tokens as $token) {
			if ($token['type'] === 'number' || $token['type'] === 'variable') {
				$output[] = $token;
			} elseif ($token['type'] === 'operator') {
				while (
					!empty($stack) &&
					$stack[count($stack) - 1]['type'] === 'operator' &&
					$this->operator_precedence[$stack[count($stack) - 1]['value']] >= $this->operator_precedence[$token['value']]
				) {
					$output[] = array_pop($stack);
				}
				$stack[] = $token;
			} elseif ($token['value'] === '(') {
				$stack[] = $token;
			} elseif ($token['value'] === ')') {
				while (!empty($stack) && $stack[count($stack) - 1]['value'] !== '(') {
					$output[] = array_pop($stack);
				}
				array_pop($stack);
			}
		}

		while (!empty($stack)) {
			$output[] = array_pop($stack);
		}

		return $output;
	}

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
		// Tokenize the content into parts: IF blocks, ELSE, ENDIF, and plain text
		$tokens = [];
		$pos = 0;
		$length = mb_strlen($content);

		while ($pos < $length) {
			$if_start = mb_strpos($content, $this->if_start_delim, $pos);
			$else_pos = mb_strpos($content, $this->else_delim, $pos);
			$endif_pos = mb_strpos($content, $this->endif_delim, $pos);

			// No more conditions found
			if ($if_start === false && $else_pos === false && $endif_pos === false) {
				$tokens[] = mb_substr($content, $pos);
				break;
			}

			// Find the earliest delimiter
			$next_pos = $length;
			if ($if_start !== false && $if_start < $next_pos) $next_pos = $if_start;
			if ($else_pos !== false && $else_pos < $next_pos) $next_pos = $else_pos;
			if ($endif_pos !== false && $endif_pos < $next_pos) $next_pos = $endif_pos;

			// Add text before the delimiter
			if ($pos < $next_pos) {
				$tokens[] = mb_substr($content, $pos, $next_pos - $pos);
			}

			// Process the delimiter
			if ($next_pos === $if_start) {
				$if_end = mb_strpos($content, $this->if_end_delim, $if_start);
				if ($if_end === false) {
					$tokens[] = mb_substr($content, $if_start);
					break;
				}
				$condition = mb_substr($content, $if_start + mb_strlen($this->if_start_delim), $if_end - ($if_start + mb_strlen($this->if_start_delim)));
				$tokens[] = ['type' => 'if', 'condition' => trim($condition)];
				$pos = $if_end + mb_strlen($this->if_end_delim);
			} elseif ($next_pos === $else_pos) {
				$tokens[] = ['type' => 'else'];
				$pos = $else_pos + mb_strlen($this->else_delim);
			} elseif ($next_pos === $endif_pos) {
				$tokens[] = ['type' => 'endif'];
				$pos = $endif_pos + mb_strlen($this->endif_delim);
			}
		}

		// Process tokens recursively
		$result = $this->_process_tokens($tokens);

		// Check for unclosed IF blocks at the top level
		$nesting = 0;
		$last_unclosed_condition = null;
		foreach ($tokens as $token) {
			if (is_array($token) && $token['type'] === 'if') {
				$nesting++;
				$last_unclosed_condition = $token['condition']; // Track the last IF condition
			} elseif (is_array($token) && $token['type'] === 'endif') {
				$nesting--;
				if ($nesting < 0) {
					$nesting = 0; // Reset if too many ENDIFs
				}
				$last_unclosed_condition = null; // Clear if closed
			}
		}
		if ($nesting > 0 && $last_unclosed_condition !== null) {
			$this->_set_error("Unclosed IF block detected: <!-- IF {$last_unclosed_condition} -->", "File: {$this->filename}");
		}

		return $result;
	}

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
	 * Trim callback parameters.
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

	/**
	 * Add an outer block to the template content (internal).
	 */
	private function _add_outer_block()
	{
		$before = $this->block_start_delim . $this->block_start_word . ' ' . $this->mainblock . ' ' . $this->block_end_delim;
		$after = $this->block_start_delim . $this->block_end_word . ' ' . $this->mainblock . ' ' . $this->block_end_delim;
		$this->filecontents = $before . "\n" . $this->filecontents . "\n" . $after;
	}

	/**
	 * Debug var_dump output (internal).
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
	 * Debug var_dump with output buffering (internal).
	 *
	 * @param mixed $args Arguments to var_dump
	 * @return string Output of var_dump
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
