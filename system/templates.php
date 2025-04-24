<?php

/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
[BEGIN_SED]
File=system/templates.php
Version=180
Updated=2025-mar-08
Type=Core
Author=Seditio Team
Description=Xtemplate SE class
[END_SED]
==================== */

/**
 * XTemplate SE class library. A fast and lightweight block template engine.
 * This implementation is inspired by and derived from the CoTemplate class 
 * of the Cotonti project (https://www.cotonti.com), licensed under the 
 * BSD 3-Clause "New" License (https://github.com/Cotonti/Cotonti/blob/master/License.txt).
 * While maintaining compatibility with the original XTemplate concept 
 * (http://www.phpxtemplate.org), this version extends functionality with 
 * additional features such as enhanced expression parsing, advanced 
 * caching mechanisms, and improved debugging capabilities, tailored 
 * for broader use cases while preserving the lightweight and efficient 
 * design philosophy of CoTemplate.
 *
 * Copyright (c) 2024-2025, Seditio Team
 * Copyright (c) 2008-2025, Cotonti Team
 * Copyright (c) 2001-2008, Neocrome
 *
 * @package API - XTemplate SE
 * @version 1.2.0
 * @license BSD 3-Clause License
 */
class XTemplate
{
    // Instance properties
    /** @var string $filename Path to the template file */
    public $filename = '';
    /** @var array $vars Associative array of assigned variables */
    public $vars = array();
    /** @var array $blocks Array of parsed template blocks */
    protected $blocks = array();
    /** @var array $displayed_blocks Tracks blocks displayed for debugging */
    protected $displayed_blocks = array();
    /** @var array $index Index mapping block names to their paths */
    protected $index = array();
    /** @var array|null $tags Cached array of tags used in the template */
    protected $tags = null;
    /** @var bool $cacheEnabled Global flag for enabling/disabling caching */
    protected static $cacheEnabled = false;
    /** @var string $cacheDir Directory path for cache storage */
    protected static $cacheDir = '';
    /** @var bool $debug Instance-specific debug mode flag */
    public $debug = false;
    /** @var bool $debugOutput Instance-specific debug output flag */
    public $debugOutput = false;
    /** @var bool $cleanupEnabled Global flag for enabling/disabling HTML cleanup */
    private static $cleanupEnabled = false;
    /** @var bool $found Flag indicating if blocks were found during compilation */
    private $found = false;
    /** @var XtplCache|null $cache Cache handler instance */
    private $cache = null;
    /** @var bool $defaultDebug Default debug mode for new instances */
    protected static $defaultDebug = false;
    /** @var bool $defaultDebugOutput Default debug output for new instances */
    protected static $defaultDebugOutput = false;

    // New properties for function filter management
    /**
     * @var string $functionFilterMode Defines the mode for filtering function calls:
     *  - 'all': All functions are allowed (default, matches current behavior).
     *  - 'whitelist': Only functions in $allowedFunctions are permitted.
     *  - 'blacklist': Functions in $disallowedFunctions are blocked, others are allowed.
     */
    public static $functionFilterMode = 'whitelist';

    /**
     * @var array $allowedFunctions List of functions permitted in 'whitelist' mode.
     */
    public static $allowedFunctions = [
        'strtoupper', 'strtolower', 'ucwords', 'ucfirst', 'strrev', 'str_word_count', 'strlen',
        'str_replace', 'str_ireplace', 'preg_replace', 'strip_tags', 'stripcslashes', 'stripslashes', 'substr',
        'str_pad', 'str_repeat', 'strtr', 'trim', 'ltrim', 'rtrim', 'nl2br', 'wordwrap', 'printf', 'sprintf',
        'addslashes', 'addcslashes',
        'htmlentities', 'html_entity_decode', 'htmlspecialchars', 'htmlspecialchars_decode',
        'urlencode', 'urldecode',
        'date', 'idate', 'strtotime', 'strftime', 'getdate', 'gettimeofday',
        'number_format', 'money_format',
        'var_dump', 'print_r', 'crop', 'resize', 'crop_image', 'resize_image', 'sed_onlydigits'
    ];

    /**
     * @var array $disallowedFunctions List of functions blocked in 'blacklist' mode.
     * Default includes common dangerous functions for security.
     */
    public static $disallowedFunctions = [
        'eval', 'system', 'exec', 'shell_exec', 'passthru', 'proc_open', 'popen', 'phpinfo', 'unlink',
        'fopen', 'file_put_contents', 'readfile', 'chmod', 'chown', 'chgrp', 'file_get_contents', 'file',
        'fsockopen', 'pfsockopen', 'curl_exec', 'curl_multi_exec', 'parse_ini_file', 'show_source', 'ini_set',
        'ini_get', 'ob_start', 'ob_end_flush', 'ob_get_clean', 'ob_get_contents', 'getimagesize', 'set_time_limit',
        'assert', 'proc_terminate', 'proc_close', 'stream_socket_server', 'stream_socket_accept', 'proc_nice', 'dl'
    ];

    /**
     * Configures global settings for XTemplate instances. Must be called before creating instances.
     *
     * @param array $settings Associative array of settings:
     *                        - 'cacheEnabled' (bool): Enable/disable caching.
     *                        - 'cacheDir' (string): Cache storage directory path.
     *                        - 'debug' (bool): Enable/disable debug mode for all instances.
     *                        - 'debugOutput' (bool): Enable/disable debug output for all instances.
     *                        - 'cleanupEnabled' (bool): Enable/disable HTML cleanup.
     *                        - 'functionFilterMode' (string): Mode for function filtering.
     *                        - 'allowedFunctions' (array): Functions allowed in whitelist mode.
     *                        - 'disallowedFunctions' (array): Functions blocked in blacklist mode.
     */
    public static function configure(array $settings = [])
    {
        // Use current static property values as defaults, override with provided settings
        $currentSettings = [
            'cacheEnabled' => self::$cacheEnabled,
            'cacheDir' => self::$cacheDir,
            'debug' => self::$defaultDebug,
            'debugOutput' => self::$defaultDebugOutput,
            'cleanupEnabled' => self::$cleanupEnabled,
            'functionFilterMode' => self::$functionFilterMode,
            'allowedFunctions' => self::$allowedFunctions,
            'disallowedFunctions' => self::$disallowedFunctions,
        ];

        // Merge user-provided settings with current values
        $settings = array_merge($currentSettings, $settings);

        // Apply settings to static properties
        self::$cacheEnabled = $settings['cacheEnabled'];
        self::$cacheDir = $settings['cacheDir'];
        self::$defaultDebug = $settings['debug'];
        self::$defaultDebugOutput = $settings['debugOutput'];
        self::$cleanupEnabled = $settings['cleanupEnabled'];
        self::$functionFilterMode = $settings['functionFilterMode'];
        self::$allowedFunctions = $settings['allowedFunctions'];
        self::$disallowedFunctions = $settings['disallowedFunctions'];
    }

    /**
     * Constructs an XTemplate instance, optionally initializing with a template file path.
     *
     * @param string|null $path The path to the template file. If provided, the template is loaded immediately.
     */
    public function __construct($path = null)
    {
        // Set default values for instance properties
        $this->debug = self::$defaultDebug;
        $this->debugOutput = self::$defaultDebugOutput;

        if (self::$cacheEnabled) {
            $this->cache = new XtplCache(self::$cacheDir);
        }
        if (is_string($path)) {
            $this->restart($path);
        }
    }

    /**
     * Converts the template blocks to a string representation.
     *
     * @return string The string representation of all template blocks.
     */
    public function __toString()
    {
        $str = '';
        foreach ($this->blocks as $name => $block) {
            $str .= "<!-- BEGIN: $name -->\n" . $block->__toString() . "<!-- END: $name -->\n";
        }
        return $str;
    }

    /**
     * Assigns variables to the template for use in rendering.
     *
     * @param string|array $name The variable name or an associative array of variables.
     * @param mixed|null $val The value to assign if $name is a string.
     * @param string $prefix A prefix to prepend to variable names.
     * @return XTemplate Returns the current instance for method chaining.
     */
    public function assign($name, $val = null, $prefix = '')
    {
        if (is_array($name)) {
            foreach ($name as $key => $val) {
                $this->vars[$prefix . $key] = $val;
            }
        } else {
            $this->vars[$prefix . $name] = $val;
        }
        return $this;
    }

    /**
     * Retrieves the debug data collected by the debugger.
     *
     * @return array The debug data array.
     */
    public static function debugData()
    {
        return XtplDebugger::$data;
    }

    /**
     * Formats a variable for debug output in HTML list format.
     *
     * @param string $name The name of the variable.
     * @param mixed $value The value of the variable to debug.
     * @return string The HTML-formatted debug output.
     */
    public static function debugVar($name, $value)
    {
        if (is_numeric($value)) {
            $val_disp = (string) $value;
        } elseif (is_object($value)) {
            $val_disp = get_class($value) . ' ' . json_encode((array) $value);
        } elseif (is_array($value)) {
            $val_disp = '<ul>';
            foreach ($value as $key => $subValue) {
                $val_disp .= self::debugVar($name . '.' . $key, $subValue);
            }
            $val_disp .= '</ul>';
        } else {
            $val_disp = '"' . htmlspecialchars((string) $value) . '"';
        }
        return '<li>{' . htmlspecialchars($name) . '} => <em>' . $val_disp . '</em></li>';
    }

    /**
     * Retrieves the value of a specific template variable.
     *
     * @param string $name The name of the variable to retrieve.
     * @return mixed The value of the variable, or null if not set.
     */
    public function get($name)
    {
        return $this->vars[$name];
    }

    /**
     * Gets all unique tags used in the template blocks.
     *
     * @return array An array of tag names.
     */
    public function getTags()
    {
        if (is_null($this->tags)) {
            $this->tags = array();
            foreach ($this->blocks as $block) {
                $this->tags = array_merge($this->tags, $block->getTags());
            }
        }
        return array_keys($this->tags);
    }

    /**
     * Checks if a block exists in the template.
     *
     * @param string $name The name of the block to check.
     * @return bool True if the block exists, false otherwise.
     */
    public function hasBlock($name)
    {
        return isset($this->index[$name]);
    }

    /**
     * Checks if a tag exists in the template.
     *
     * @param string $name The name of the tag to check.
     * @return bool True if the tag exists, false otherwise.
     */
    public function hasTag($name)
    {
        if (is_null($this->tags)) {
            $this->getTags();
        }
        return isset($this->tags[$name]);
    }

    /**
     * Processes include file directives in the template code recursively.
     *
     * @param array $m The regex match array containing the file directive.
     * @return string The processed content of the included file or the original filename.
     */
    private static function restartIncludeFiles($m)
    {
        $fname = preg_replace_callback('`\{((?:[\w\.\-]+)(?:\|.+?)?)\}`', 'XTemplate::substituteVar', $m[2]);
        if (preg_match('`\.tpl$`i', $fname) && file_exists($fname)) {
            $code = self::readFile($fname);
            if ($code[0] == chr(0xEF) && $code[1] == chr(0xBB) && $code[2] == chr(0xBF)) {
                $code = mb_substr($code, 0);
            }
            $code = preg_replace_callback('`\{FILE\s+("|\')(.+?)\1\}`', 'XTemplate::restartIncludeFiles', $code);
            return $code;
        }
        return $fname;
    }

    /**
     * Registers a root block during template compilation.
     *
     * @param array $m The regex match array containing block name and content.
     * @return string An empty string to remove the processed block from the code.
     */
    private function restartRootBlocks($m)
    {
        $name = $m[1];
        $text = trim($m[2]);
        $this->index[$name] = array($name);
        $this->blocks[$name] = new XtplBlock($text, $this->index, array($name));
        $this->found = true;
        return '';
    }

    /**
     * Initializes or reinitializes the template with a new file path.
     *
     * @param string $path The path to the template file.
     * @return XTemplate Returns the current instance for method chaining.
     * @throws Exception If the template file does not exist.
     */
    public function restart($path)
    {
        if (!file_exists($path)) {
            throw new Exception("Template file not found: $path");
        }

        $this->filename = $path;
        $this->vars = array();
        $this->blocks = array();
        $this->index = array();
        $this->tags = null;

        $code = self::readFile($path);
        $code = $this->prepare($code);
        $hash = md5($code);

        if (self::$cacheEnabled && $this->cache) {
            $cached = $this->cache->load($path, $hash);
            if ($cached !== null) {
                list($this->blocks, $this->index, $this->tags) = $cached;
                return $this;
            }
        }

        $this->compile($code);

        if (self::$cacheEnabled && $this->cache) {
            $this->cache->save($path, array($this->blocks, $this->index, $this->tags), $hash);
        }

        return $this;
    }

    /**
     * Prepares the template code by handling file includes and BOM removal.
     *
     * @param string $code The raw template code.
     * @return string The prepared template code.
     */
    private function prepare($code)
    {
        if ($code[0] == chr(0xEF) && $code[1] == chr(0xBB) && $code[2] == chr(0xBF)) {
            $code = mb_substr($code, 0);
        }
        return preg_replace_callback('`\{FILE\s+("|\')(.+?)\1\}`', 'XTemplate::restartIncludeFiles', $code);
    }

    /**
     * Compiles the template code into blocks.
     *
     * @param string $code The template code to compile.
     * @return XTemplate Returns the current instance for method chaining.
     */
    public function compile($code)
    {
        if (self::$cleanupEnabled) {
            $code = $this->cleanup($code);
        }

        do {
            $this->found = false;
            $code = preg_replace_callback(
                '`<!--\s*BEGIN:\s*([\w_]+)\s*-->(.*?)<!--\s*END:\s*\1\s*-->`s',
                array($this, 'restartRootBlocks'),
                $code
            );
        } while ($this->found);

        return $this;
    }

    /**
     * Cleans up HTML code by normalizing whitespace and formatting.
     *
     * @param string $html The HTML code to clean up.
     * @return string The cleaned-up HTML code.
     */
    private function cleanup($html)
    {
        $html = join("\n", array_map('trim', explode("\n", $html)));
        $html = preg_replace('#[\r\n\t]+<#', ' <', $html);
        $html = preg_replace('#>[\r\n\t]+#', '>', $html);
        $html = preg_replace('/[\t\f]+/', ' ', $html);
        $html = preg_replace('/ {2,}/', ' ', $html);
        $regexp = "/<\/?\w+((\s+(\w|\w[\w-]*\w)(\s*=\s*(?:\".*?\"|'.*?'|[^'\">\s]+))?)+\s*|\s*)\/?>/i";
        preg_match_all($regexp, $html, $matches);
        foreach ($matches[0] as $match) {
            $newHtml = preg_replace('/\s+/', ' ', $match);
            $html = str_replace($match, $newHtml, $html);
        }
        return $html;
    }

    /**
     * Substitutes a variable placeholder with its evaluated value.
     *
     * @param array $m The regex match array containing the variable name.
     * @return string The evaluated value of the variable.
     */
    private static function substituteVar($m)
    {
        $var = new XtplVar($m[1]);
        return $var->evaluate();
    }

    /**
     * Outputs the rendered content of a block, with optional debug output.
     *
     * @param string $block The name of the block to output (default: 'MAIN').
     * @return XTemplate Returns the current instance for method chaining.
     */
    public function out($block = 'MAIN')
    {
        if ($this->debug && $this->debugOutput) {
            echo XtplDebugger::display(basename($this->filename));
        } else {
            echo $this->text($block);
        }
        return $this;
    }

    /**
     * Parses a block, preparing it for rendering.
     *
     * @param string $block The name of the block to parse (default: 'MAIN').
     * @return XTemplate Returns the current instance for method chaining.
     */
    public function parse($block = 'MAIN')
    {
        $path = isset($this->index[$block]) ? $this->index[$block] : null;
        if ($path) {
            $blockIndex = array_shift($path);
            $blk = isset($this->blocks[$blockIndex]) ? $this->blocks[$blockIndex] : null;
            if (!empty($blk)) {
                foreach ($path as $node) {
                    if (is_array($blk)) {
                        $blk = $blk[$node];
                    } else {
                        $blk = $blk->blocks[$node];
                    }
                }
                $blk->parse($this);
            }
        }

        if ($this->debug && !in_array($block, $this->displayed_blocks)) {
            XtplDebugger::collect(basename($this->filename), $block, $this->vars);
            $this->displayed_blocks[] = $block;
        }
        return $this;
    }

    /**
     * Resets the parsed data of a block.
     *
     * @param string $block The name of the block to reset (default: 'MAIN').
     * @return XTemplate Returns the current instance for method chaining.
     */
    public function reset($block = 'MAIN')
    {
        $path = $this->index[$block];
        if ($path) {
            $blk = $this->blocks[array_shift($path)];
            foreach ($path as $node) {
                if (is_object($blk)) {
                    $blk = &$blk->blocks[$node];
                } else {
                    $blk = &$blk[$node];
                }
            }
            $blk->reset();
        }
        return $this;
    }

    /**
     * Retrieves the rendered text of a block.
     *
     * @param string $block The name of the block to render (default: 'MAIN').
     * @return string The rendered text of the block.
     */
    public function text($block = 'MAIN')
    {
        $path = isset($this->index[$block]) ? $this->index[$block] : null;

        if (empty($path)) {
            return '';
        }

        $blockIndex = array_shift($path);
        $blk = isset($this->blocks[$blockIndex]) ? $this->blocks[$blockIndex] : null;
        if (empty($blk)) {
            return '';
        }

        foreach ($path as $node) {
            if (is_object($blk)) {
                $blk = &$blk->blocks[$node];
            } else {
                $blk = &$blk[$node];
            }
        }

        return $blk->text($this);
    }

    /**
     * Reads the contents of a file.
     *
     * @param string $path The path to the file to read.
     * @return string The contents of the file.
     */
    public static function readFile($path)
    {
        $fp = fopen($path, 'r');
        $size = filesize($path);
        $code = $size > 0 ? fread($fp, $size) : '';
        fclose($fp);
        return $code;
    }

    /**
     * Glues a path array into a string index, skipping numeric keys after the first.
     *
     * @param array $path The path array to glue.
     * @return string The glued index string.
     */
    public static function indexGlue($path)
    {
        $str = array_shift($path);
        foreach ($path as $node) {
            if (!is_numeric($node)) {
                $str .= '.' . $node;
            }
        }
        return $str;
    }

    /**
     * Splits a string into tokens, respecting delimiters, quotes, and brackets.
     *
     * @param string $str The input string to tokenize.
     * @param array $delim Array of delimiter characters (default: [' ']).
     * @param bool $trimQuotes Whether to trim quotes and brackets (default: true).
     * @return array An array of tokens.
     */
    public static function tokenize($str, $delim = [' '], $trimQuotes = true)
    {
        $tokens = [0 => ''];
        $idx = 0;
        $quote = '';
        $quotePairs = ['"' => '"', "'" => "'", '{' => '}'];
        $prevWasDelim = false;

        $len = mb_strlen($str);
        for ($i = 0; $i < $len; $i++) {
            $char = mb_substr($str, $i, 1);

            if (array_key_exists($char, $quotePairs) && !$quote) {
                $quote = $char;
                if (!$trimQuotes) {
                    $tokens[$idx] .= $char;
                }
                $prevWasDelim = false;
                continue;
            }

            if ($quote && $char === $quotePairs[$quote]) {
                $quote = '';
                if (!$trimQuotes) {
                    $tokens[$idx] .= $char;
                }
                $prevWasDelim = false;
                continue;
            }

            if (in_array($char, $delim, true)) {
                if ($quote) {
                    $tokens[$idx] .= $char;
                } elseif (!$prevWasDelim) {
                    $tokens[++$idx] = '';
                    $prevWasDelim = true;
                }
                continue;
            }

            $tokens[$idx] .= $char;
            $prevWasDelim = false;
        }

        return array_filter($tokens, 'strlen');
    }

    /**
     * Converts a string argument to its appropriate data type or object.
     *
     * @param string $argument The input argument string.
     * @return mixed The converted value (bool, int, float, string, or XtplVar).
     */
    public static function parseArgument($argument)
    {
        if ($argument === '') {
            return '';
        }

        static $literals = [
            'true'  => true,
            'false' => false,
            'null'  => null,
        ];
        $argLC = mb_strtolower($argument);
        if (array_key_exists($argLC, $literals)) {
            return $literals[$argLC];
        }

        if (is_numeric($argument)) {
            return strpos($argument, '.') === false ? (int) $argument : (float) $argument;
        }

        $len = mb_strlen($argument);
        if ($len < 2) {
            return $argument;
        }

        $first = mb_substr($argument, 0, 1);
        $last = mb_substr($argument, -1, 1);

        if ($first === '{' && $last === '}') {
            return new XtplVar(mb_substr($argument, 1, $len - 2));
        }

        if ($first === $last && in_array($first, ['"', "'"], true)) {
            return mb_substr($argument, 1, $len - 2);
        }

        return $argument;
    }
}

class XtplBlock
{
    protected $data = array();
    public $blocks = array();

    /**
     * Constructs an XtplBlock instance and compiles the provided code.
     *
     * @param string $code The block code to compile.
     * @param array &$index Reference to the template index array.
     * @param array $path The path to this block in the template hierarchy.
     */
    public function __construct($code, &$index, $path)
    {
        $this->compile($code, $this->blocks, $index, $path);
    }

    /**
     * Converts the block and its sub-blocks to a string representation.
     *
     * @return string The string representation of the block.
     */
    public function __toString()
    {
        return $this->blocks_toString($this->blocks);
    }

    /**
     * Converts an array of blocks to a string representation.
     *
     * @param array &$blocks Reference to the array of blocks.
     * @return string The string representation of the blocks.
     */
    protected function blocks_toString(&$blocks)
    {
        $str = '';
        foreach ($blocks as $name => $block) {
            if (is_string($name) && !is_numeric($name)) {
                $str .= "<!-- BEGIN: $name -->\n" . $block->__toString() . "<!-- END: $name -->\n";
            } else {
                $str .= $block->__toString();
            }
        }
        return $str;
    }

    /**
     * Compiles the block code into a structure of sub-blocks and data.
     *
     * @param string $code The code to compile.
     * @param array &$blocks Reference to the array where compiled blocks are stored.
     * @param array &$index Reference to the template index array.
     * @param array $path The path to this block in the template hierarchy.
     */
    protected function compile($code, &$blocks, &$index, $path)
    {
        $i = 0;
        while (!empty($code)) {
            $construct = $this->detectNextConstruct($code);

            if ($construct === null && !empty($code)) {
                $blocks[$i++] = new XtplData($code);
                $code = '';
                continue;
            }

            list($constructCode, $remainingCode) = $this->extractConstruct($code, $construct);
            $code = $remainingCode;

            if (!empty($constructCode['before'])) {
                $blocks[$i++] = new XtplData($constructCode['before']);
            }

            $this->compileConstruct($constructCode, $construct, $blocks, $index, $path, $i);
        }
    }

    /**
     * Detects the type of the next construct in the code (BEGIN, FOR, IF).
     *
     * @param string $code The input code to analyze.
     * @return string|null The type of the next construct or null if none found.
     */
    private function detectNextConstruct($code)
    {
        if (preg_match('`<!--\s*(?:(BEGIN):\s*([\w_]+)|(FOR|IF)\s+).+?-->.*?<!--\s*(?:END:\s*\2|END\3)\s*-->`s', $code, $mt)) {
            if (!empty($mt[1])) {
                return 'BEGIN';
            } elseif (!empty($mt[3])) {
                return $mt[3];
            }
        }
        return null;
    }

    /**
     * Extracts a construct from the code, separating it from the remaining code.
     *
     * @param string $code The input code containing the construct.
     * @param string $type The type of construct to extract (BEGIN, FOR, IF).
     * @return array An array containing construct data and remaining code.
     * @throws Exception If the construct type is unknown.
     */
    private function extractConstruct($code, $type)
    {
        $patterns = [
            'BEGIN' => '`(?:(?:(?<=\n|\r)[^\S\n\r]*)(?=<!--\s*BEGIN:\s*(?:[\w_]+)\s*-->(?:\s*(?:\r?\n|\r))))?<!--\s*BEGIN:\s*([\w_]+)\s*-->(?:\s*(?:\r?\n|\r))?((?:.*?))?((?<=\n|\r)[^\S\n\r]*)?<!--\s*END:\s*\1\s*-->(?(3)(\s*(?:\r?\n|\r))?)`s',
            'FOR' => '`((?:(?<=\n|\r)[^\S\n\r]*)(?=<!--\s*FOR\s+[^>]+\s*-->(?:\s*(?:\r?\n|\r))))?<!--\s*FOR\s+(.+?)\s*-->(?(1)(?:\s*(?:\r?\n|\r))?)`',
            'IF' => '`((?:(?<=\n|\r)[^\S\n\r]*)(?=<!--\s*IF\s+[^>]+\s*-->(?:\s*(?:\r?\n|\r))))?<!--\s*IF\s+(.+?)\s*-->(?(1)(?:\s*(?:\r?\n|\r))?)`',
        ];

        if (!isset($patterns[$type])) {
            throw new Exception("Unknown construct type: $type");
        }

        preg_match($patterns[$type], $code, $mt);
        $startPos = mb_strpos($code, $mt[0]);
        $startLen = mb_strlen($mt[0]);

        $constructData = [
            'before' => $startPos > 0 ? mb_substr($code, 0, $startPos) : '',
            'header' => $mt[2],
            'content' => '',
            'elseContent' => '',
        ];

        $code = mb_substr($code, $startPos + $startLen);

        if ($type === 'BEGIN') {
            $constructData['content'] = $mt[2];
            $constructData['name'] = $mt[1];
            return [$constructData, $code];
        }

        list($mainContent, $elseContent, $remainingCode) = $this->extractNestedContent($code, $type);
        $constructData['content'] = $mainContent;
        $constructData['elseContent'] = $elseContent;

        return [$constructData, $remainingCode];
    }

    /**
     * Extracts nested content for FOR or IF constructs, handling ELSE for IF.
     *
     * @param string $code The code following the construct header.
     * @param string $type The type of construct (FOR or IF).
     * @return array An array containing main content, else content, and remaining code.
     * @throws Exception If the construct block is not properly closed.
     */
    private function extractNestedContent($code, $type)
    {
        $endTag = "END{$type}";
        $scope = 1;
        $mainContent = '';
        $elseContent = '';
        $hasElse = false;

        $pattern = "`((?:(?<=\n|\r)[^\S\n\r]*)(?=<!--\s*(?:{$type}\s+[^>]+?|ELSE|{$endTag})\s*-->(?:\s*(?:\r?\n|\r))))?<!--\s*({$type}\s+.+?|ELSE|{$endTag})\s*-->(?(1)(?:\s*(?:\r?\n|\r))?)`";

        while ($scope > 0 && preg_match($pattern, $code, $m)) {
            $mPos = mb_strpos($code, $m[0]);
            $mLen = mb_strlen($m[0]);

            if ($m[2] === $endTag) {
                $scope--;
            } elseif ($type === 'IF' && $m[2] === 'ELSE' && $scope === 1) {
                $mainContent .= mb_substr($code, 0, $mPos);
                $hasElse = true;
                $code = mb_substr($code, $mPos + $mLen);
                continue;
            } elseif (preg_match("/^{$type}\s+/", $m[2])) {
                $scope++;
            }

            $postfixLen = $scope === 0 ? 0 : $mLen;
            if (!$hasElse) {
                $mainContent .= mb_substr($code, 0, $mPos + $postfixLen);
            } else {
                $elseContent .= mb_substr($code, 0, $mPos + $postfixLen);
            }
            $code = mb_substr($code, $mPos + $mLen);
        }

        if ($scope !== 0) {
            throw new Exception("$type block not closed");
        }

        return [$mainContent, $elseContent, $code];
    }

    /**
     * Compiles a specific construct and adds it to the blocks array.
     *
     * @param array $constructData Data about the construct to compile.
     * @param string $type The type of construct (BEGIN, FOR, IF).
     * @param array &$blocks Reference to the array of blocks.
     * @param array &$index Reference to the template index array.
     * @param array $path The current path in the template hierarchy.
     * @param int &$i Reference to the block counter.
     * @throws Exception If the construct type is unknown.
     */
    private function compileConstruct($constructData, $type, &$blocks, &$index, $path, &$i)
    {
        $bpath = $path;

        switch ($type) {
            case 'BEGIN':
                $blockName = $constructData['name'];
                array_push($bpath, $blockName);
                $index[XTemplate::indexGlue($bpath)] = $bpath;
                $blocks[$blockName] = new XtplBlock($constructData['content'], $index, $bpath);
                break;

            case 'FOR':
                array_push($bpath, $i);
                $blocks[$i++] = new XtplLoop($constructData['header'], $constructData['content'], $index, $bpath);
                break;

            case 'IF':
                array_push($bpath, $i);
                $blocks[$i++] = new XtplLogical($constructData['header'], $constructData['content'], $constructData['elseContent'], $index, $bpath);
                break;

            default:
                throw new Exception("Unknown construct type: $type");
        }
    }

    /**
     * Retrieves all tags used within this block.
     *
     * @return array An associative array of tag names.
     */
    public function getTags()
    {
        $list = array();
        foreach ($this->blocks as $block) {
            if ($block instanceof XtplData || $block instanceof XtplBlock) {
                $list = array_merge($list, $block->getTags());
            }
        }
        return $list;
    }

    /**
     * Parses the block, rendering its content into the data array.
     *
     * @param XTemplate $tpl The template instance to use for parsing.
     */
    public function parse($tpl)
    {
        $data = '';
        foreach ($this->blocks as $block) {
            $data .= $block->text($tpl);
        }
        $this->data[] = $data;
    }

    /**
     * Resets the parsed data of the block.
     *
     * @param array $path Optional path parameter (unused in this implementation).
     */
    public function reset($path = array())
    {
        $this->data = array();
    }

    /**
     * Renders the block's text content.
     *
     * @param XTemplate $tpl The template instance to use for rendering.
     * @return string The rendered text of the block.
     */
    public function text($tpl)
    {
        $text = implode('', $this->data);
        $this->data = array();
        return $text;
    }
}

class XtplData
{
    protected $chunks = array();

    /**
     * Constructs an XtplData instance by splitting code into chunks.
     *
     * @param string $code The code to process into data chunks.
     */
    public function __construct($code)
    {
        $chunks = $this->splitToChunks($code);
        if (!empty($chunks)) {
            foreach ($chunks as $chunk) {
                $firstSymbol = mb_substr($chunk, 0, 1);
                $lastSymbol = mb_substr($chunk, -1, 1);
                if ($firstSymbol === '{' && $lastSymbol === '}') {
                    $this->chunks[] = new XtplVar(mb_substr($chunk, 1, mb_strlen($chunk) - 2));
                } elseif ($chunk !== '' && $chunk !== null) {
                    $this->chunks[] = $chunk;
                }
            }
        }
    }

    /**
     * Splits the code into chunks, separating variables from static content.
     *
     * @param string $code The input code to split.
     * @return array An array of code chunks.
     */
    private function splitToChunks($code)
    {
        return preg_split(
            '`(\{[A-Z0-9_$](?>[^{}]|(?0))*?[\w)]})`',
            $code,
            -1,
            PREG_SPLIT_DELIM_CAPTURE
        );
    }

    /**
     * Converts the data chunks to a string representation.
     *
     * @return string The string representation of the data.
     */
    public function __toString()
    {
        $str = '';
        foreach ($this->chunks as $chunk) {
            if ($chunk instanceof XtplVar) {
                $str .= $chunk->__toString();
            } else {
                $str .= $chunk;
            }
        }
        return $str . "\n";
    }

    /**
     * Retrieves all variable tags used in the data chunks.
     *
     * @return array An associative array of tag names.
     */
    public function getTags()
    {
        $list = array();
        foreach ($this->chunks as $chunk) {
            if ($chunk instanceof XtplVar) {
                $list[$chunk->name] = true;
            }
        }
        return $list;
    }

    /**
     * Renders the data chunks into text using the provided template.
     *
     * @param XTemplate $tpl The template instance to use for rendering.
     * @return string The rendered text of the data.
     */
    public function text($tpl)
    {
        $data = '';
        foreach ($this->chunks as $chunk) {
            if ($chunk instanceof XtplVar) {
                $tmp = $chunk->evaluate($tpl);
                if (is_array($tmp) || is_object($tmp)) {
                    $tmp = var_export($tmp, true);
                } elseif (is_bool($tmp)) {
                    $tmp = $tmp ? 'true' : 'false';
                }
                $data .= $tmp;
            } else {
                $data .= $chunk;
            }
        }
        return $data;
    }
}

class XtplExpr
{
    protected $tokens = array();
    protected static $precedence = array(
        '(' => -1,
        '*' => 1,
        '/' => 1,
        '%' => 1,
        '+' => 2,
        '-' => 2,
        'HAS' => 3,
        '~=' => 3,
        '==' => 4,
        '===' => 4,
        '!=' => 4,
        '!==' => 4,
        '<' => 4,
        '>' => 4,
        '<=' => 4,
        '>=' => 4,
        '!' => 5,
        'AND' => 6,
        'OR' => 7,
        'XOR' => 7,
        ')' => 99,
    );

    /**
     * Constructs an XtplExpr instance by parsing an expression into Reverse Polish Notation (RPN).
     *
     * @param string $text The expression text to parse.
     */
    public function __construct($text)
    {
        $this->tokens = $this->parseToRPN($text);
    }

    /**
     * Converts the expression tokens to a string representation.
     *
     * @return string The string representation of the expression in RPN.
     */
    public function __toString()
    {
        return implode(' ', array_map(function ($token) {
            return isset($token['op']) ? $token['op'] : (string) $token['var'];
        }, $this->tokens));
    }

    /**
     * Tokenizes an expression text into an array of tokens.
     *
     * @param string $text The expression text to tokenize.
     * @return array An array of parsed tokens.
     */
    protected function tokenize($text)
    {
        $text = str_replace(array('(', ')'), array(' ( ', ' ) '), $text);
        $text = str_replace('!{', ' ! {', $text);
        $text = str_replace('!(', ' ! (', $text);
        $words = XTemplate::tokenize($text, array(' ', "\t"), false);
        return array_map('XTemplate::parseArgument', $words);
    }

    /**
     * Parses an expression into Reverse Polish Notation (RPN).
     *
     * @param string $text The expression text to parse.
     * @return array An array of tokens in RPN order.
     * @throws Exception If parentheses are mismatched.
     */
    protected function parseToRPN($text)
    {
        $tokens = $this->tokenize($text);
        $output = array();
        $operatorStack = array();

        foreach ($tokens as $token) {
            if (is_object($token) && $token instanceof XtplVar) {
                $output[] = array('var' => $token, 'prec' => 0);
            } elseif (is_string($token) && array_key_exists($token, self::$precedence)) {
                $tokenData = array('op' => $token, 'prec' => self::$precedence[$token]);
                if ($token === '(') {
                    $operatorStack[] = $tokenData;
                } elseif ($token === ')') {
                    while (!empty($operatorStack) && $operatorStack[count($operatorStack) - 1]['op'] !== '(') {
                        $output[] = array_pop($operatorStack);
                    }
                    if (!empty($operatorStack)) {
                        array_pop($operatorStack);
                    } else {
                        throw new Exception("Mismatched parentheses: no matching opening parenthesis");
                    }
                } else {
                    while (
                        !empty($operatorStack) &&
                        $operatorStack[count($operatorStack) - 1]['prec'] >= $tokenData['prec'] &&
                        $operatorStack[count($operatorStack) - 1]['op'] !== '('
                    ) {
                        $output[] = array_pop($operatorStack);
                    }
                    $operatorStack[] = $tokenData;
                }
            } else {
                $output[] = array('var' => $token, 'prec' => 0);
            }
        }

        while (!empty($operatorStack)) {
            $op = array_pop($operatorStack);
            if ($op['op'] === '(') {
                throw new Exception("Mismatched parentheses: unclosed parenthesis");
            }
            $output[] = $op;
        }

        return $output;
    }

    /**
     * Applies an operator to one or two operands.
     *
     * @param string $op The operator to apply.
     * @param mixed $a The first operand.
     * @param mixed|null $b The second operand (optional).
     * @return mixed The result of the operation.
     * @throws Exception If division or modulo by zero occurs, or if the operator is unknown.
     */
    protected function applyOperator($op, $a, $b = null)
    {
        switch ($op) {
            case '+':
                if (is_string($a) && is_string($b)) {
                    return $a . $b;
                }
                return $a + $b;
            case '-':
                return $a - $b;
            case '*':
                return $a * $b;
            case '/':
                if ($b == 0) return 'Division by zero';
                return $a / $b;
            case '%':
                if ($b == 0) return 'Modulo by zero';
                if (!is_int($a) || !is_int($b)) return (int)$a % (int)$b;
                return $a % $b;
            case '==':
                return $a == $b;
            case '===':
                return $a === $b;
            case '!=':
                return $a != $b;
            case '!==':
                return $a !== $b;
            case '<':
                return $a < $b;
            case '>':
                return $a > $b;
            case '<=':
                return $a <= $b;
            case '>=':
                return $a >= $b;
            case 'AND':
                return $a && $b;
            case 'OR':
                return $a || $b;
            case 'XOR':
                return $a xor $b;
            case '!':
                return !$a;
            case 'HAS':
                return is_array($b) && in_array($a, $b);
            case '~=':
                return is_string($b) && is_string($a) && mb_strpos($b, $a) !== false;
            default:
                throw new Exception("Unknown operator: $op");
        }
    }
    /**
     * Evaluates the expression using the provided template for variable resolution.
     *
     * @param XTemplate $tpl The template instance to use for variable evaluation.
     * @return mixed The result of the evaluated expression.
     * @throws Exception If there are insufficient operands or an invalid expression.
     */
    public function evaluate($tpl)
    {
        $stack = array();

        foreach ($this->tokens as $token) {
            if (isset($token['op'])) {
                if ($token['op'] === '!') {
                    if (empty($stack)) throw new Exception("Insufficient operands for NOT");
                    $operand = array_pop($stack);
                    $stack[] = $this->applyOperator($token['op'], $operand);
                } elseif ($token['op'] !== '(' && $token['op'] !== ')') {
                    if (count($stack) < 2) throw new Exception("Insufficient operands for operator: {$token['op']}");
                    $b = array_pop($stack);
                    $a = array_pop($stack);
                    $stack[] = $this->applyOperator($token['op'], $a, $b);
                }
            } else {
                $value = $token['var'];
                if (is_object($value) && $value instanceof XtplVar) {
                    $stack[] = $value->evaluate($tpl);
                } elseif (is_string($value) && mb_strpos($value, '{') !== false) {
                    $stack[] = preg_replace_callback(
                        '`(?<!\{)\{([\w\.\-]+[\|.+?]?)\}`',
                        function ($matches) use ($tpl) {
                            $var = new XtplVar($matches[1]);
                            return $var->evaluate($tpl);
                        },
                        $value
                    );
                } elseif (is_string($value) && preg_match('#^[\w\.]+$#', $value)) {
                    if (mb_strpos($value, '.') !== false) {
                        $var = new XtplVar($value);
                        $stack[] = $var->evaluate($tpl);
                    } else {
                        $var = new XtplVar($value);
                        $evaluated = $var->evaluate($tpl);
                        if (isset($tpl->vars[$value]) && $evaluated !== null) {
                            $stack[] = $evaluated;
                        } else {
                            $stack[] = $value;
                        }
                    }
                } else {
                    $stack[] = $value;
                }
            }
        }

        if (count($stack) !== 1) throw new Exception("Invalid expression: stack imbalance");

        return array_pop($stack);
    }
}

class XtplLogical extends XtplBlock
{
    protected $expr = null;

    /**
     * Constructs an XtplLogical instance for IF/ELSE logic.
     *
     * @param string $expr_str The expression string to evaluate.
     * @param string $if_code The code to execute if the expression is true.
     * @param string $else_code The code to execute if the expression is false.
     * @param array &$index Reference to the template index array.
     * @param array $path The path to this block in the template hierarchy.
     */
    public function __construct($expr_str, $if_code, $else_code, &$index, $path)
    {
        $this->expr = new XtplExpr($expr_str);
        if (!empty($if_code)) {
            $bpath = $path;
            array_push($bpath, 0);
            $this->compile($if_code, $this->blocks[0], $index, $bpath);
        }
        if (!empty($else_code)) {
            $bpath = $path;
            array_push($bpath, 1);
            $this->compile($else_code, $this->blocks[1], $index, $bpath);
        }
    }

    /**
     * Converts the logical block to a string representation.
     *
     * @return string The string representation of the IF/ELSE block.
     */
    public function __toString()
    {
        $str = "<!-- IF " . $this->expr->__toString() . " -->\n";
        $str .= $this->blocks_toString($this->blocks[0]);
        if (count($this->blocks[1]) > 0) {
            $str .= "<!-- ELSE -->\n" . $this->blocks_toString($this->blocks[1]);
        }
        $str .= "<!-- ENDIF -->\n";
        return $str;
    }

    /**
     * Retrieves all tags used within the logical block.
     *
     * @return array An associative array of tag names.
     */
    public function getTags()
    {
        $list = array();
        for ($i = 0; $i < 2; $i++) {
            if (isset($this->blocks[$i]) && is_array($this->blocks[$i])) {
                foreach ($this->blocks[$i] as $block) {
                    if ($block instanceof XtplData || $block instanceof XtplBlock) {
                        $list = array_merge($list, $block->getTags());
                    }
                }
            }
        }
        return $list;
    }

    /**
     * Throws an exception as parsing is not supported for logical blocks.
     *
     * @param XTemplate $xtpl The template instance (unused).
     * @throws Exception Always thrown as this method is not applicable.
     */
    public function parse($xtpl)
    {
        throw new Exception('Calling parse() on logical block');
    }

    /**
     * Throws an exception as resetting is not supported for logical blocks.
     *
     * @param mixed|null $dummy Unused parameter.
     * @throws Exception Always thrown as this method is not applicable.
     */
    public function reset($dummy = null)
    {
        throw new Exception('Calling reset() on logical block');
    }

    /**
     * Renders the text of the logical block based on the expression result.
     *
     * @param XTemplate $tpl The template instance to use for rendering.
     * @return string The rendered text of the block.
     */
    public function text($tpl)
    {
        $data = '';
        if ($this->expr->evaluate($tpl)) {
            if (!empty($this->blocks[0])) {
                foreach ($this->blocks[0] as $block) {
                    $data .= $block->text($tpl);
                }
            }
        } elseif (!empty($this->blocks[1])) {
            foreach ($this->blocks[1] as $block) {
                $data .= $block->text($tpl);
            }
        }
        return $data;
    }
}

class XtplLoop extends XtplBlock
{
    protected $key = '';
    protected $set = null;
    protected $val = '';

    /**
     * Constructs an XtplLoop instance for loop constructs.
     *
     * @param string $header The loop header defining key, value, and set.
     * @param string $code The code to execute for each iteration.
     * @param array &$index Reference to the template index array.
     * @param array $path The path to this block in the template hierarchy.
     */
    public function __construct($header, $code, &$index, $path)
    {
        if (preg_match('`^\{(\w+)\}\s*,\s*\{(\w+)\}\s*IN\s*\{((?:[\w\.\-]+)(?:\|.+?)?)\}$`', $header, $m)) {
            $this->key = $m[1];
            $this->val = $m[2];
            $this->set = new XtplVar($m[3]);
        } elseif (preg_match('`^\{(\w+)\}\s*IN\s*\{((?:[\w\.\-]+)(?:\|.+?)?)\}$`', $header, $m)) {
            $this->val = $m[1];
            $this->set = new XtplVar($m[2]);
        }
        $this->compile($code, $this->blocks, $index, $path);
    }

    /**
     * Converts the loop block to a string representation.
     *
     * @return string The string representation of the FOR loop.
     */
    public function __toString()
    {
        $header = empty($this->key) ? '{' . $this->val . '}' : '{' . $this->key . '}, {' . $this->val . '}';
        $str = "<!-- FOR $header IN " . $this->set->__toString() . " -->\n";
        $str .= $this->blocks_toString($this->blocks);
        $str .= "<!-- ENDFOR -->\n";
        return $str;
    }

    /**
     * Throws an exception as parsing is not supported for loop blocks.
     *
     * @param XTemplate $xtpl The template instance (unused).
     * @throws Exception Always thrown as this method is not applicable.
     */
    public function parse($xtpl)
    {
        throw new Exception('Calling parse() on a loop');
    }

    /**
     * Throws an exception as resetting is not supported for loop blocks.
     *
     * @param mixed|null $dummy Unused parameter.
     * @throws Exception Always thrown as this method is not applicable.
     */
    public function reset($dummy = null)
    {
        throw new Exception('Calling reset() on a loop');
    }

    /**
     * Renders the text of the loop block by iterating over the set.
     *
     * @param XTemplate $tpl The template instance to use for rendering.
     * @return string The rendered text of the loop.
     */
    public function text($tpl)
    {
        $data = '';
        $set = $this->set->evaluate($tpl);
        if (is_array($set) && $this->blocks) {
            foreach ($set as $key => $val) {
                $tpl->assign($this->val, $val);
                if (!empty($this->key)) {
                    $tpl->assign($this->key, $key);
                }
                foreach ($this->blocks as $block) {
                    $data .= $block->text($tpl);
                }
            }
        }
        return $data;
    }
}

class XtplVar
{
    /** @var string $name Base name of the variable (e.g., "VAR" in "VAR.key|func") */
    protected $name = '';
    /** @var array|null $keys Array of nested keys (e.g., ["key1", "key2"] in "VAR.key1.key2") */
    protected $keys = null;
    /** @var array|null $callbacks Array of filter functions to apply (e.g., ["func" => ["arg1"]]) */
    protected $callbacks = null;
    /** @var XtplExpr|null $expression Expression object if the variable is an expression */
    protected $expression = null;

    /**
     * Constructs an XtplVar instance by parsing a variable or expression string.
     *
     * @param string $text The variable or expression text to parse (e.g., "VAR|func" or "expr + expr").
     */
    public function __construct($text)
    {
        // Check if the input string is an arithmetic expression
        // The regex ensures the string contains at least two operands separated by an arithmetic operator (+, -, *, /, %)
        // and allows for additional operands and operators (e.g., "2 + 3 + 8" or "{VAR} + 5")
        // Operands can be numbers, words, dot-separated paths (e.g., "ITEMS.0.price"), or variables in braces (e.g., "{VAR}")
        if (preg_match('#^\s*[\d\w\.\{\}]+\s*[\+\-\*/%]\s*[\d\w\.\{\}]+\s*(?:[\+\-\*/%]\s*[\d\w\.\{\}]+)*\s*$#', $text)) {
            // If matched, treat the string as an expression and delegate parsing to XtplExpr
            $this->name = 'expr';
            $this->expression = new XtplExpr($text);
        } else {
            // If not an arithmetic expression, process the string as a variable with optional filters and keys
            // Handle filters (e.g., "VAR|func(arg1, arg2)")
            if (mb_strpos($text, '|') !== false) {
                // Split the text into variable and filter chain, preserving special {PHP|} cases
                $chain = explode('|', str_replace('{PHP|', '{PHP#$%&!', $text));
                array_walk($chain, function (&$val) {
                    $val = str_replace('{PHP#$%&!', '{PHP|', $val);
                });
                $text = array_shift($chain); // Extract the base variable name
                foreach ($chain as $cbk) {
                    // Parse function calls with arguments (e.g., "func(arg1, arg2)")
                    if (mb_strpos($cbk, '(') !== false && preg_match('/(\w+)\s*\(((?>.|\n)*)\)/', $cbk, $mt)) {
                        $args = XTemplate::tokenize(trim($mt[2]), array(',', ' ', "\n", "\r", "\t"), false);
                        $args = array_map('XTemplate::parseArgument', $args);
                        $this->callbacks[] = array(
                            'name' => $mt[1], // Function name
                            'args' => $args,  // Arguments
                        );
                    } else {
                        // Simple filter without arguments (e.g., "trim")
                        $this->callbacks[] = str_replace('()', '', $cbk);
                    }
                }
            }

            // Handle nested keys (e.g., "VAR.key1.key2")
            if (mb_strpos($text, '.') !== false) {
                $keys = explode('.', $text);
                $text = array_shift($keys); // Base variable name
                $this->keys = $keys;        // Array of nested keys
            }

            // Store the base variable name (e.g., "VAR")
            $this->name = $text;
        }
    }

    /**
     * Gets a property value if it exists.
     *
     * @param string $name The name of the property to get.
     * @return mixed|null The property value or null if not set.
     */
    public function __get($name)
    {
        if (isset($this->$name)) {
            return $this->$name;
        }
        return null;
    }

    /**
     * Converts the variable to a string representation, including keys and callbacks.
     *
     * @return string The string representation of the variable (e.g., "{VAR|func(arg)}").
     */
    public function __toString()
    {
        $str = '{' . $this->name;
        if (is_array($this->keys)) {
            $str .= '.' . implode('.', $this->keys);
        }
        if (is_array($this->callbacks)) {
            foreach ($this->callbacks as $cb) {
                if (is_array($cb)) {
                    $str .= '|' . $cb['name'] . '(' . implode(',', $cb['args']) . ')';
                } else {
                    $str .= '|' . $cb;
                }
            }
        }
        $str .= '}';
        return $str;
    }

    /**
     * Dumps a variable's value in an HTML list format for debugging.
     *
     * @param mixed $val The value to dump.
     * @return string The HTML-formatted dump output.
     */
    private function dump($val)
    {
        $key = $this->keys ? $this->name . '.' . implode('.', $this->keys) : $this->name;
        if ($this->name == 'PHP' && !$this->keys) {
            $val = $GLOBALS;
        }
        return '<ul class="dump">' . self::dump_r($key, $val, 0) . '</ul>';
    }

    /**
     * Recursively dumps a variable's structure for debugging.
     *
     * @param string $key The variable key or path.
     * @param mixed $val The value to dump.
     * @param int $level The recursion level (max 5).
     * @return string The recursive dump output.
     */
    private static function dump_r($key, $val, $level)
    {
        if ($level > 5 || $key == 'PHP.GLOBALS') {
            return '';
        }
        $ret = '';
        if (is_array($val)) {
            ksort($val);
            foreach ($val as $key2 => $val2) {
                $ret .= self::dump_r($key . '.' . $key2, $val2, $level + 1);
            }
        } elseif (is_string($val)) {
            $ret = XTemplate::debugVar($key, $val);
        }
        return $ret;
    }

    /**
     * Evaluates the variable or expression using the provided template.
     * Applies function filters based on the configured mode (all, whitelist, blacklist).
     *
     * @param XTemplate|null $tpl The template instance for variable resolution (optional).
     * @return mixed|null The evaluated value or null if unresolved; returns string representation if a function is blocked.
     */
    public function evaluate($tpl = null)
    {
        // Handle expressions (e.g., "VAR1 + VAR2")
        if ($this->name === 'expr' && isset($this->expression)) {
            $val = $this->expression->evaluate($tpl);
        } else {
            $var = null;
            // Special case: access to global PHP variables
            if ($this->name === 'PHP') {
                $var = $GLOBALS;
            } elseif (!empty($tpl)) {
                // Initialize variable if not set
                if (!isset($tpl->vars[$this->name])) {
                    $tpl->vars[$this->name] = null;
                }
                $val = $tpl->vars[$this->name];
                if ($this->keys && (is_array($val) || is_object($val))) {
                    $var = &$tpl->vars[$this->name];
                }
            }

            // Resolve nested keys (e.g., "VAR.key1.key2")
            if ($this->keys) {
                $keys = $this->keys;
                $last_key = array_pop($keys);
                foreach ($keys as $key) {
                    if (is_object($var)) {
                        $var = &$var->$key;
                    } elseif (is_array($var)) {
                        $var = &$var[$key];
                    } else {
                        break;
                    }
                }
                if (is_object($var) && isset($var->$last_key)) {
                    $val = $var->$last_key;
                } elseif (is_array($var) && isset($var[$last_key])) {
                    $val = $var[$last_key];
                } else {
                    $val = null;
                }
            }
        }

        // Process function filters (e.g., "VAR|func(arg1)")
        if ($this->callbacks) {
            foreach ($this->callbacks as $func) {
                if (is_array($func)) {
                    // Process arguments for functions with parameters
                    array_walk(
                        $func['args'],
                        array($this, 'processCallbackArgument'),
                        array('value' => isset($val) ? $val : null, 'tpl' => $tpl)
                    );

                    $f = $func['name'];
                    $a = $func['args'];

                    // Replace %s placeholder with the current value
                    foreach ($a as &$arg) {
                        if (is_string($arg) && $arg === '%s' && isset($val)) {
                            $arg = $val;
                        }
                    }
                    unset($arg);

                    // Check if the function exists
                    if (!function_exists($f)) {
                        return $this->__toString();
                    }

                    // Apply function filter mode logic
                    switch (XTemplate::$functionFilterMode) {
                        case 'whitelist':
                            // Only allow functions in the whitelist
                            if (!in_array($f, XTemplate::$allowedFunctions)) {
                                return $this->__toString(); // Return string if not allowed
                            }
                            break;
                        case 'blacklist':
                            // Block functions in the blacklist
                            if (in_array($f, XTemplate::$disallowedFunctions)) {
                                return $this->__toString(); // Return string if disallowed
                            }
                            break;
                        case 'all':
                            // No restrictions, all functions allowed
                            break;
                    }

                    // Call the function with the appropriate number of arguments
                    switch (count($a)) {
                        case 0:
                            $val = $f();
                            break;
                        case 1:
                            $val = $f($a[0]);
                            break;
                        case 2:
                            $val = $f($a[0], $a[1]);
                            break;
                        case 3:
                            $val = $f($a[0], $a[1], $a[2]);
                            break;
                        case 4:
                            $val = $f($a[0], $a[1], $a[2], $a[3]);
                            break;
                        default:
                            $val = call_user_func_array($f, $a);
                            break;
                    }
                } elseif ($func == 'dump') {
                    // Special case: dump function for debugging
                    $val = $this->dump(isset($val) ? $val : null);
                } else {
                    // Simple filter without arguments (e.g., "VAR|trim")
                    if (!function_exists($func)) {
                        return $this->__toString();
                    }

                    // Apply function filter mode logic for simple filters
                    switch (XTemplate::$functionFilterMode) {
                        case 'whitelist':
                            if (!in_array($func, XTemplate::$allowedFunctions)) {
                                return $this->__toString(); // Return string if not allowed
                            }
                            break;
                        case 'blacklist':
                            if (in_array($func, XTemplate::$disallowedFunctions)) {
                                return $this->__toString(); // Return string if disallowed
                            }
                            break;
                        case 'all':
                            // No restrictions, all functions allowed
                            break;
                    }

                    $val = (array_key_exists('val', get_defined_vars())) ? $func($val) : $func();
                }
            }
        }
        return isset($val) ? $val : null;
    }

    /**
     * Processes callback arguments, resolving variables and placeholders.
     *
     * @param mixed &$argument Reference to the argument to process.
     * @param int $i The index of the argument (unused).
     * @param array $params Parameters including the value and template instance.
     */
    protected function processCallbackArgument(&$argument, $i, $params)
    {
        if ($argument instanceof XtplVar) {
            $argument = $argument->evaluate($params['tpl']);
            return;
        }

        if (!is_string($argument)) {
            return;
        }

        $thisPlaceHolder = '~~=={this-placeholder}==~~';

        if (mb_strpos($argument, '{') !== false) {
            $argument = preg_replace_callback(
                '`\{([\w$](?>[^{}]|(?0))*?)\}`',
                function ($matches) use ($params, $thisPlaceHolder) {
                    $var = new XtplVar($matches[1]);
                    return str_replace('$this', $thisPlaceHolder, $var->evaluate($params['tpl']));
                },
                $argument
            );
        }

        if (mb_strpos($argument, '$this') !== false) {
            if ($argument === '$this' || is_array($params['value']) || is_object($params['value'])) {
                $argument = $params['value'];
            } else {
                $argument = str_replace('$this', (string) $params['value'], $argument);
            }
        }

        if (is_string($argument) && $argument !== '') {
            $argument = str_replace($thisPlaceHolder, '$this', $argument);
        }
    }
}

class XtplDebugger
{
    private static $data = array();

    /**
     * Collects debug data for a specific file and block.
     *
     * @param string $file The template file name.
     * @param string $block The block name.
     * @param array $vars The variables to collect.
     */
    public static function collect($file, $block, $vars)
    {
        $tags = $vars;
        ksort($tags);
        foreach ($tags as $key => $val) {
            if (is_array($val)) {
                foreach ($val as $key2 => $val2) {
                    self::$data[$file][$block][$key . '.' . $key2] = $val2;
                }
            } else {
                self::$data[$file][$block][$key] = $val;
            }
        }
    }

    /**
     * Displays debug data for a file or specific block in HTML format.
     *
     * @param string $file The template file name.
     * @param string|null $block The specific block to display (optional).
     * @return string The HTML-formatted debug output.
     */
    public static function display($file = null, $block = null)
    {
        if ($file === null) {
            $output = '';
            foreach (self::$data as $fileName => $blocks) {
                $output .= "<h1>$fileName</h1>";
                foreach ($blocks as $blockName => $tags) {
                    $block_path = $fileName . ' / ' . str_replace('.', ' / ', $blockName);
                    $output .= "<h2>$block_path</h2><ul>";
                    ksort($tags);
                    foreach ($tags as $key => $val) {
                        if (is_array($val)) {
                            foreach ($val as $key2 => $val2) {
                                $output .= XTemplate::debugVar($key . '.' . $key2, $val2);
                            }
                        } else {
                            $output .= XTemplate::debugVar($key, $val);
                        }
                    }
                    $output .= "</ul>";
                }
            }
            return $output;
        }

        $output = "<h1>$file</h1>";
        $blocks = $block && isset(self::$data[$file][$block])
            ? [$block => self::$data[$file][$block]]
            : (isset(self::$data[$file]) ? self::$data[$file] : []);

        foreach ($blocks as $blockName => $tags) {
            $block_path = $file . ' / ' . str_replace('.', ' / ', $blockName);
            $output .= "<h2>$block_path</h2><ul>";
            ksort($tags);
            foreach ($tags as $key => $val) {
                if (is_array($val)) {
                    foreach ($val as $key2 => $val2) {
                        $output .= XTemplate::debugVar($key . '.' . $key2, $val2);
                    }
                } else {
                    $output .= XTemplate::debugVar($key, $val);
                }
            }
            $output .= "</ul>";
        }
        return $output;
    }

    /**
     * Clears all collected debug data.
     */
    public static function clear()
    {
        self::$data = array();
    }
}

class XtplCache
{
    private $cacheDir;

    /**
     * Constructs an XtplCache instance with a specified cache directory.
     *
     * @param string $cacheDir The directory path for caching.
     */
    public function __construct($cacheDir)
    {
        $this->cacheDir = $cacheDir ? rtrim($cacheDir, '/') . '/templates/' : '';
    }

    /**
     * Loads cached template data if available and valid.
     *
     * @param string $path The path to the original template file.
     * @param string $hash The hash of the template content.
     * @return array|null The cached data or null if not available or outdated.
     */
    public function load($path, $hash)
    {
        if (!$this->cacheDir) {
            return null;
        }

        $this->ensureCacheDirectory();
        $cachePath = $this->getCachePath($path, $hash);
        if (file_exists($cachePath) && filemtime($path) <= filemtime($cachePath)) {
            return unserialize(XTemplate::readFile($cachePath));
        }
        return null;
    }

    /**
     * Saves template data to the cache.
     *
     * @param string $path The path to the original template file.
     * @param array $data The data to cache.
     * @param string $hash The hash of the template content.
     */
    public function save($path, $data, $hash)
    {
        if (!$this->cacheDir) {
            return;
        }

        $this->ensureCacheDirectory();
        $cachePath = $this->getCachePath($path, $hash);
        file_put_contents($cachePath, serialize($data));
    }

    /**
     * Generates the cache file path based on the template path and hash.
     *
     * @param string $path The path to the original template file.
     * @param string $hash The hash of the template content.
     * @return string The full cache file path.
     */
    private function getCachePath($path, $hash)
    {
        $pathParts = pathinfo($path);
        $fileName = $pathParts['filename'];
        $fileExtension = !empty($pathParts['extension']) ? '.' . $pathParts['extension'] : '';
        return $this->cacheDir . str_replace(array('./', '/'), '_', $pathParts['dirname']) . '_' . $fileName . '_' . $hash . $fileExtension;
    }

    /**
     * Ensures the cache directory exists and is writable.
     *
     * @throws Exception If the cache directory cannot be created or is not writable.
     */
    private function ensureCacheDirectory()
    {
        if (!file_exists($this->cacheDir)) {
            mkdir($this->cacheDir, 0755, true);
        }
        if (!is_writable($this->cacheDir)) {
            throw new Exception('Cache directory "' . $this->cacheDir . '" is not writable');
        }
    }
}
