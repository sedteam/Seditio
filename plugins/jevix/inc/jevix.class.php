<?php

function sed_jevix($text, $filter = 'medium', $xhtml = false, $use_admin = true) 
  {	
  if ($use_admin == false) return $text; //Disable Jevix for Admin	
	
  $jevix = new Jevix();

  switch($filter)
  	{
  	/* -- Full settings -- */
  	case 'full':

      $jevix->cfgAllowTags(array(
      		'p','a','img','i','b','u','s','em','strong','strike','small',
      		'nobr','li','ol','ul','sup','abbr','sub','acronym','h1', 'h2', 
      		'h3', 'h4', 'h5', 'h6','br','hr','pre','code','object','param','embed','adabracut',
      		'blockquote','iframe','span','div','table','tbody','thead','tfoot','tr','td','th'
  		));    
  		 // Establish short tags. (Not having closing tag)
  		$jevix->cfgSetTagShort(array('br','img', 'hr'));       
  		// Establish preformatted tags. (In all of them will be will be replaced on HTML essence)
  		$jevix->cfgSetTagPreformatted(array('pre','code'));        
  		// Establish tags which are necessary for cutting out from the text together with a content.
  		$jevix->cfgSetTagCutWithContent(array('script', 'style', 'meta'));    	
  		// Establish the resolved parametres tags. Also it is possible to establish admissible values of these parametres.
  		$jevix->cfgAllowTagParams('p', array('style'));
  		$jevix->cfgAllowTagParams('span', array('style')); 
  		$jevix->cfgAllowTagParams('a', array('title', 'href' => '#link', 'rel' => '#text', 'name' => '#text', 'target' => array('_blank')));  
  		$jevix->cfgAllowTagParams('img', array('src' => '#image', 'style' => '#text', 'alt' => '#text', 'title', 'align' => array('right', 'left', 'center'), 'width' => '#int', 'height' => '#int', 'hspace' => '#int', 'vspace' => '#int'));    
  		$jevix->cfgAllowTagParams('object', array('width' => '#int', 'height' => '#int', 'data' => array('#domain'=>array('youtube.com','rutube.ru','vimeo.com','player.vimeo.com')), 'type' => '#text', 'class' => '#text', 'frameborder' => '#int', 'title' => '#text'));
  		$jevix->cfgAllowTagParams('param', array('name' => '#text', 'value' => '#text'));
  		$jevix->cfgAllowTagParams('embed', array('src' => '#image', 'type' => '#text','allowscriptaccess' => '#text', 'allowfullscreen' => '#text','width' => '#int', 'height' => '#int', 'flashvars'=> '#text', 'wmode'=> '#text'));
  		$jevix->cfgAllowTagParams('iframe', array('width' => '#int', 'height' => '#int', 'src' => array('#domain'=>array('youtube.com','rutube.ru','vimeo.com','player.vimeo.com')), 'type' => '#text', 'class' => '#text', 'frameborder' => '#int', 'title' => '#text'));
  		$jevix->cfgAllowTagParams('pre',	array('class')); 
  		$jevix->cfgAllowTagParams('acronym', array('title'));
  		$jevix->cfgAllowTagParams('abbr',	array('title'));
  		$jevix->cfgAllowTagParams('hr',	array('id' => '#text', 'class'));		
  		$jevix->cfgAllowTagParams('div', array('class', 'id', 'style'));
  		$jevix->cfgAllowTagParams('h1', array('style'));
  		$jevix->cfgAllowTagParams('h2', array('style'));
  		$jevix->cfgAllowTagParams('h3', array('style'));
  		$jevix->cfgAllowTagParams('h4', array('style'));
  		$jevix->cfgAllowTagParams('h5', array('style'));
  		$jevix->cfgAllowTagParams('h6', array('style'));
  		$jevix->cfgAllowTagParams('span', array('class', 'id', 'style'));	
  		$jevix->cfgAllowTagParams('table', array('border', 'class', 'width', 'align', 'valign', 'style'));
  		$jevix->cfgAllowTagParams('tr', array('height', 'class'));
  		$jevix->cfgAllowTagParams('td', array('colspan', 'rowspan', 'class', 'width', 'height', 'align', 'valign'));
  		$jevix->cfgAllowTagParams('th', array('colspan', 'rowspan', 'class', 'width', 'height', 'align', 'valign'));    
      // Establish the resolved parametres css styles for tags
  		$jevix->cfgSetTagStyleParams(array('span'), 
  			array(
  				'text-decoration'   =>  array('none', 'line-through', 'underline'),
  				'font-style'        =>  array('normal', 'italic'),
  				'font-family',
  				'font-weight'       =>  array('normal', 'bold'),
  				'font-size'         =>  '#regexp:%^(8|10|12|14|16|18|20)px$%i',          
  				'color'             =>  '#regexp:%^(#([a-f0-9]{6}|[a-f0-9]{3}))|(rgb\\((\\d{1,3}),\\s*(\\d{1,3}),\\s*(\\d{1,3})\\))$%i',           
  				'background-color'  =>  '#regexp:%^(#([a-f0-9]{6}|[a-f0-9]{3}))|(rgb\\((\\d{1,3}),\\s*(\\d{1,3}),\\s*(\\d{1,3})\\))$%i'            
  			)
  		);
      // Allowed style for tags		
  		$jevix->cfgSetTagStyleParams(array('p'), 
  			array(
  				'padding-left'      =>  '#regexp:%^(10|20|30|40|50|60|70|80|90|100|120|140|150|160|180)px$%i',
  				'margin-left'       =>  '#regexp:%^(10|20|30|40|50|60|70|80|90|100|120|140|150|160|180)px$%i',
  				'text-align'        =>  array('left', 'center', 'right', 'justify')        
  			)
  		);    		
  		// Establish parametres tags being the obligatory. Without them cuts out tag leaving contents.
  		$jevix->cfgSetTagParamsRequired('img', 'src');
  		$jevix->cfgSetTagParamsRequired('a', 'href');    		
  		// Establish tags which can contain tag the container
  		$jevix->cfgSetTagChilds('ul', array('li'), false, true);      
  		$jevix->cfgSetTagChilds('ol', array('li'), false, true);
  		$jevix->cfgSetTagChilds('object', 'param', false, true);
  		$jevix->cfgSetTagChilds('object', 'embed', false, false);    
  		// Establish tags which can be empty
  		$jevix->cfgSetTagIsEmpty(array('param','embed','a','iframe'));			
  		// Establish attributes tags which will be automatically added
  		$jevix->cfgSetTagParamDefault('embed', 'wmode',	'opaque',	true);			
  		// Establish autoreplacement
  		$jevix->cfgSetAutoReplace(array('+/-', '(c)', '(с)', '(r)', '(C)', '(С)', '(R)'), array('±', '©', '©', '®', '©', '©', '®'));    
  		// Disconnect typografy in defined tag	
  		$jevix->cfgSetTagNoTypography('code','video','object');
       	
    break;
    /* ---- */
           
    /* -- Medium settings -- */
  	case 'medium':

      $jevix->cfgAllowTags(array(
      		'p','a','img','i','b','u','s','em','strong','strike','small',
      		'nobr','li','ol','ul','sup','abbr','sub','acronym','h1', 'h2', 
      		'h3', 'h4', 'h5', 'h6','br','hr','pre','code','blockquote','span'
  		));    
  		$jevix->cfgSetTagShort(array('br','img', 'hr'));  
  		$jevix->cfgSetTagPreformatted(array('pre','code')); 
  		$jevix->cfgSetTagCutWithContent(array('script', 'style', 'meta'));      
  		$jevix->cfgAllowTagParams('p', array('style'));
  		$jevix->cfgAllowTagParams('span', array('style')); 
  		$jevix->cfgAllowTagParams('a', array('title', 'href' => '#link', 'rel' => '#text', 'name' => '#text', 'target' => array('_blank')));  
  		$jevix->cfgAllowTagParams('img', array('src' => '#image', 'style' => '#text', 'alt' => '#text', 'title', 'align' => array('right', 'left', 'center'), 'width' => '#int', 'height' => '#int', 'hspace' => '#int', 'vspace' => '#int'));    
  		$jevix->cfgAllowTagParams('pre',	array('class')); 
  		$jevix->cfgAllowTagParams('acronym', array('title'));
  		$jevix->cfgAllowTagParams('abbr',	array('title'));
  		$jevix->cfgAllowTagParams('hr',	array('id' => '#text', 'class'));		
  		$jevix->cfgAllowTagParams('h1', array('style'));
  		$jevix->cfgAllowTagParams('h2', array('style'));
  		$jevix->cfgAllowTagParams('h3', array('style'));
  		$jevix->cfgAllowTagParams('h4', array('style'));
  		$jevix->cfgAllowTagParams('h5', array('style'));
  		$jevix->cfgAllowTagParams('h6', array('style'));
  		$jevix->cfgSetTagStyleParams(array('span'), 
  			array(
  				'text-decoration'   =>  array('none', 'line-through', 'underline'),
  				'font-style'        =>  array('normal', 'italic'),
  				'font-family',
  				'font-weight'       =>  array('normal', 'bold'),
  				'font-size'         =>  '#regexp:%^(8|10|12|14|16|18|20)px$%i',          
  				'color'             =>  '#regexp:%^(#([a-f0-9]{6}|[a-f0-9]{3}))|(rgb\\((\\d{1,3}),\\s*(\\d{1,3}),\\s*(\\d{1,3})\\))$%i',           
  				'background-color'  =>  '#regexp:%^(#([a-f0-9]{6}|[a-f0-9]{3}))|(rgb\\((\\d{1,3}),\\s*(\\d{1,3}),\\s*(\\d{1,3})\\))$%i'            
  			)
  		);	
  		$jevix->cfgSetTagStyleParams(array('p'), 
  			array(
  				'padding-left'      =>  '#regexp:%^(10|20|30|40|50|60|70|80|90|100|120|140|150|160|180)px$%i',
  				'margin-left'       =>  '#regexp:%^(10|20|30|40|50|60|70|80|90|100|120|140|150|160|180)px$%i',
  				'text-align'        =>  array('left', 'center', 'right', 'justify')        
  			)
  		); 
  		$jevix->cfgSetTagParamsRequired('img', 'src');
  		$jevix->cfgSetTagParamsRequired('a', 'href');    		
  		$jevix->cfgSetTagChilds('ul', array('li'), false, true);      
  		$jevix->cfgSetTagChilds('ol', array('li'), false, true);			
  		$jevix->cfgSetAutoReplace(array('+/-', '(c)', '(с)', '(r)', '(C)', '(С)', '(R)'), array('±', '©', '©', '®', '©', '©', '®'));    
  		$jevix->cfgSetTagNoTypography('code');

  	break;
    /* ---- */
    
    /* -- Micro settings - default -- */
    default:

      $jevix->cfgAllowTags(array('p','a','i','b','u','s','em','strong','br','strike'));
  		$jevix->cfgSetTagShort(array('br'));
  		$jevix->cfgAllowTagParams('a', array('title', 'href' => '#link', 'rel' => '#text', 'name' => '#text', 'target' => array('_blank')));
  		$jevix->cfgSetTagParamsRequired('a', 'href');    	
  		$jevix->cfgSetTagIsEmpty(array('a'));

    break;
    /* ---- */    
  	}

  // Include or switch off mode XHTML. It (is by default included)
  $jevix->cfgSetXHTMLMode($xhtml);    
  // Include or switch off a mode of replacement of carrying over of lines on тег <br/>. It (is by default included)
  $jevix->cfgSetAutoBrMode(false);        
  // Include or switch off a mode of automatic definition of references. It (is by default included)
  $jevix->cfgSetAutoLinkMode(false);    
  // Variable in which will be write errors
  $errors = null;       
  return $jevix->parse($text, $errors);  
  }

/**
 * Jevix — means of automatic application of rules of a set of texts,
 * allocated with ability to unify marking HTML/XML of documents,
 * to supervise the list admissible tags and attributes,
 * to prevent possible XSS-attacks in a code of documents.
 * http://code.google.com/p/jevix/
 *
 * @author ur001 <ur001ur001@gmail.com>, http://ur001.habrahabr.ru
 * @version 1.01
*/


class Jevix{
	const PRINATABLE  = 0x1;
	const ALPHA       = 0x2;
	const LAT	 = 0x4;
	const RUS	 = 0x8;
	const NUMERIC     = 0x10;
	const SPACE       = 0x20;
	const NAME	= 0x40;
	const URL	 = 0x100;
	const NOPRINT     = 0x200;
	const PUNCTUATUON = 0x400;
	//const	   = 0x800;
	//const	   = 0x1000;
	const HTML_QUOTE  = 0x2000;
	const TAG_QUOTE   = 0x4000;
	const QUOTE_CLOSE = 0x8000;
	const NL	  = 0x10000;
	const QUOTE_OPEN  = 0;

	const STATE_TEXT = 0;
	const STATE_TAG_PARAMS = 1;
	const STATE_TAG_PARAM_VALUE = 2;
	const STATE_INSIDE_TAG = 3;
	const STATE_INSIDE_NOTEXT_TAG = 4;
	const STATE_INSIDE_PREFORMATTED_TAG = 5;
	const STATE_INSIDE_CALLBACK_TAG = 6;

	public $tagsRules = array();
	
	public $tagsStyleAllowed = array('font-family','font-size','font-weight','text-align','text-indent','text-decoration','line-height','color',
    'background-color','background-image','margin-left','margin-right','margin-top','margin-bottom','padding-left','padding-right',
    'padding-top','padding-bottom','border-width','border-style','border-color','padding','margin','width','height');    
		
	public $entities1 = array('"'=>'&quot;', "'"=>'&#39;', '&'=>'&amp;', '<'=>'&lt;', '>'=>'&gt;');
	public $entities2 = array('<'=>'&lt;', '>'=>'&gt;', '"'=>'&quot;');
	public $textQuotes = array(array('«', '»'), array('„', '“'));
	public $dash = " — ";
	public $apostrof = "’";
	public $dotes = "…";
	public $nl = "\r\n";
	public $defaultTagParamRules = array('href' => '#link', 'src' => '#image', 'width' => '#int', 'height' => '#int', 'text' => '#text', 'title' => '#text');

	protected $text;
	protected $textBuf;
	protected $textLen = 0;
	protected $curPos;
	protected $curCh;
	protected $curChOrd;
	protected $curChClass;
	protected $curParentTag;
	protected $states;
	protected $quotesOpened = 0;
	protected $brAdded = 0;
	protected $state;
	protected $tagsStack;
	protected $openedTag;
	protected $autoReplace; 
	protected $isXHTMLMode  = true; // <br/>, <img/>
	protected $isAutoBrMode = true; // \n = <br/>
	protected $isAutoLinkMode = true;
	protected $br = "<br />";

	protected $noTypoMode = false;

	public    $outBuffer = '';
	public    $errors;


	const TR_TAG_ALLOWED = 1;	
	const TR_PARAM_ALLOWED = 2;     
	const TR_PARAM_REQUIRED = 3;     
	const TR_TAG_SHORT = 4;	 
	const TR_TAG_CUT = 5;	    
	const TR_TAG_CHILD = 6;	 
	const TR_TAG_CONTAINER = 7;     
	const TR_TAG_CHILD_TAGS = 8;    
	const TR_TAG_PARENT = 9;	
	const TR_TAG_PREFORMATTED = 10;  
	const TR_PARAM_AUTO_ADD = 11;    
	const TR_TAG_NO_TYPOGRAPHY = 12; 
	const TR_TAG_IS_EMPTY = 13;      
	const TR_TAG_NO_AUTO_BR = 14;   
	const TR_TAG_CALLBACK = 15;     
	const TR_TAG_BLOCK_TYPE = 16;    
	const TR_TAG_CALLBACK_FULL = 17;    
  const TR_TAG_PARSE_STYLE = 20; 

	protected $chClasses = array(0=>512,1=>512,2=>512,3=>512,4=>512,5=>512,6=>512,7=>512,8=>512,9=>32,10=>66048,11=>512,12=>512,13=>66048,14=>512,15=>512,16=>512,17=>512,18=>512,19=>512,20=>512,21=>512,22=>512,23=>512,24=>512,25=>512,26=>512,27=>512,28=>512,29=>512,30=>512,31=>512,32=>32,97=>71,98=>71,99=>71,100=>71,101=>71,102=>71,103=>71,104=>71,105=>71,106=>71,107=>71,108=>71,109=>71,110=>71,111=>71,112=>71,113=>71,114=>71,115=>71,116=>71,117=>71,118=>71,119=>71,120=>71,121=>71,122=>71,65=>71,66=>71,67=>71,68=>71,69=>71,70=>71,71=>71,72=>71,73=>71,74=>71,75=>71,76=>71,77=>71,78=>71,79=>71,80=>71,81=>71,82=>71,83=>71,84=>71,85=>71,86=>71,87=>71,88=>71,89=>71,90=>71,1072=>11,1073=>11,1074=>11,1075=>11,1076=>11,1077=>11,1078=>11,1079=>11,1080=>11,1081=>11,1082=>11,1083=>11,1084=>11,1085=>11,1086=>11,1087=>11,1088=>11,1089=>11,1090=>11,1091=>11,1092=>11,1093=>11,1094=>11,1095=>11,1096=>11,1097=>11,1098=>11,1099=>11,1100=>11,1101=>11,1102=>11,1103=>11,1040=>11,1041=>11,1042=>11,1043=>11,1044=>11,1045=>11,1046=>11,1047=>11,1048=>11,1049=>11,1050=>11,1051=>11,1052=>11,1053=>11,1054=>11,1055=>11,1056=>11,1057=>11,1058=>11,1059=>11,1060=>11,1061=>11,1062=>11,1063=>11,1064=>11,1065=>11,1066=>11,1067=>11,1068=>11,1069=>11,1070=>11,1071=>11,48=>337,49=>337,50=>337,51=>337,52=>337,53=>337,54=>337,55=>337,56=>337,57=>337,34=>57345,39=>16385,46=>1281,44=>1025,33=>1025,63=>1281,58=>1025,59=>1281,1105=>11,1025=>11,47=>257,38=>257,37=>257,45=>257,95=>257,61=>257,43=>257,35=>257,124=>257,);

	protected function _cfgSetTagsFlag($tags, $flag, $value, $createIfNoExists = true){
		if(!is_array($tags)) $tags = array($tags);
		foreach($tags as $tag){
			if(!isset($this->tagsRules[$tag])) {
				if($createIfNoExists){
					$this->tagsRules[$tag] = array();
				} else {
					throw new Exception("Tag $tag is not in the list of allowed tags");
				}
			}
			$this->tagsRules[$tag][$flag] = $value;
		}
	}
   
	function cfgAllowTags($tags){
		$this->_cfgSetTagsFlag($tags, self::TR_TAG_ALLOWED, true);
	}

	function cfgSetTagShort($tags){
		$this->_cfgSetTagsFlag($tags, self::TR_TAG_SHORT, true, false);
	}

	function cfgSetTagPreformatted($tags){
		$this->_cfgSetTagsFlag($tags, self::TR_TAG_PREFORMATTED, true, false);
	}

	function cfgSetTagNoTypography($tags){
		$this->_cfgSetTagsFlag($tags, self::TR_TAG_NO_TYPOGRAPHY, true, false);
	}

	function cfgSetTagIsEmpty($tags){
		$this->_cfgSetTagsFlag($tags, self::TR_TAG_IS_EMPTY, true, false);
	}

	function cfgSetTagNoAutoBr($tags){
		$this->_cfgSetTagsFlag($tags, self::TR_TAG_NO_AUTO_BR, true, false);
	}

	function cfgSetTagCutWithContent($tags){
		$this->_cfgSetTagsFlag($tags, self::TR_TAG_CUT, true);
	}

	function cfgSetTagBlockType($tags){
		$this->_cfgSetTagsFlag($tags, self::TR_TAG_BLOCK_TYPE, true);
	}
	
	function cfgAllowTagParams($tag, $params){
	  //	if(!isset($this->tagsRules[$tag])) throw new Exception("Тег $tag отсутствует в списке разрешённых тегов");
	 	 // ====================
     if(isset($this->tagsRules[$tag])) {

      	if(!is_array($params)) $params = array($params);
    		if(!isset($this->tagsRules[$tag][self::TR_PARAM_ALLOWED])) {
    			$this->tagsRules[$tag][self::TR_PARAM_ALLOWED] = array();
    		}
    		foreach($params as $key => $value){
    			if(is_string($key)){
    				$this->tagsRules[$tag][self::TR_PARAM_ALLOWED][$key] = $value;
    			} else {
    				$this->tagsRules[$tag][self::TR_PARAM_ALLOWED][$value] = true;
    			}
    		}
    }
    // ====================
	}

	function cfgSetTagParamsRequired($tag, $params){
		if(!isset($this->tagsRules[$tag])) throw new Exception("Tag $tag is not in the list of allowed tags");
		if(!is_array($params)) $params = array($params);
		// Если ключа со списком разрешенных параметров не существует - создаём ео
		if(!isset($this->tagsRules[$tag][self::TR_PARAM_REQUIRED])) {
			$this->tagsRules[$tag][self::TR_PARAM_REQUIRED] = array();
		}
		foreach($params as $param){
			$this->tagsRules[$tag][self::TR_PARAM_REQUIRED][$param] = true;
		}
	}

	function cfgSetTagChilds($tag, $childs, $isContainerOnly = false, $isChildOnly = false){
		if(!isset($this->tagsRules[$tag])) throw new Exception("Tag $tag is not in the list of allowed tags");
		if(!is_array($childs)) $childs = array($childs);

		if($isContainerOnly) $this->tagsRules[$tag][self::TR_TAG_CONTAINER] = true;

		if(!isset($this->tagsRules[$tag][self::TR_TAG_CHILD_TAGS])) {
			$this->tagsRules[$tag][self::TR_TAG_CHILD_TAGS] = array();
		}
		foreach($childs as $child){
			$this->tagsRules[$tag][self::TR_TAG_CHILD_TAGS][$child] = true;

			if(!isset($this->tagsRules[$child])) throw new Exception("Tag $child is not in the list of allowed tags");
			if(!isset($this->tagsRules[$child][self::TR_TAG_PARENT])) $this->tagsRules[$child][self::TR_TAG_PARENT] = array();
			$this->tagsRules[$child][self::TR_TAG_PARENT][$tag] = true;

			if($isChildOnly) $this->tagsRules[$child][self::TR_TAG_CHILD] = true;
		}
	}

	function cfgSetTagParamsAutoAdd($tag, $params){
		throw new Exception("cfgSetTagParamsAutoAdd() is Deprecated. Use cfgSetTagParamDefault() instead");
	}

	function cfgSetTagParamDefault($tag, $param, $value, $isRewrite = false){
		if(!isset($this->tagsRules[$tag])) throw new Exception("Tag $tag is missing in allowed tags list");

		if(!isset($this->tagsRules[$tag][self::TR_PARAM_AUTO_ADD])) {
			$this->tagsRules[$tag][self::TR_PARAM_AUTO_ADD] = array();
		}

		$this->tagsRules[$tag][self::TR_PARAM_AUTO_ADD][$param] = array('value'=>$value, 'rewrite'=>$isRewrite);
	}

	function cfgSetTagCallback($tag, $callback = null){
		if(!isset($this->tagsRules[$tag])) throw new Exception("Tag $tag is not in the list of allowed tags");
		$this->tagsRules[$tag][self::TR_TAG_CALLBACK] = $callback;
	}

	function cfgSetTagCallbackFull($tag, $callback = null){
		if(!isset($this->tagsRules[$tag])) throw new Exception("Tag $tag is not in the list of allowed tags");
		$this->tagsRules[$tag][self::TR_TAG_CALLBACK_FULL] = $callback;
	}
	
	function cfgSetAutoReplace($from, $to){
		$this->autoReplace = array('from' => $from, 'to' => $to);
	}

	function cfgSetXHTMLMode($isXHTMLMode){
		$this->br = $isXHTMLMode ? '<br />' : '<br>';
		$this->isXHTMLMode = $isXHTMLMode;
	}

	function cfgSetAutoBrMode($isAutoBrMode){
		$this->isAutoBrMode = $isAutoBrMode;
	}

	function cfgSetAutoLinkMode($isAutoLinkMode){
		$this->isAutoLinkMode = $isAutoLinkMode;
	}
  
 // ------- Set Tag Style params --------------- // 
 
  function cfgSetTagStyleParams($tag, $params){
    $tags = is_array($tag) ? $tag : array($tag);
    if(!is_array($params)) $params = array($params);
   
    $this->_cfgSetTagsFlag($tags, self::TR_TAG_PARSE_STYLE, array());
    
    foreach($tags as $tag){
        //$this->cfgAllowTagParams($tag, array('style'));   // Атрибует style для тега будет принудительно позволен
        
        if(!isset($this->tagsRules[$tag][self::TR_TAG_PARSE_STYLE])) {
            $this->tagsRules[$tag][self::TR_TAG_PARSE_STYLE] = array();
        }

        foreach($params as $key => $value){
            if(is_string($key)){
                $this->tagsRules[$tag][self::TR_TAG_PARSE_STYLE][$key] = $value;
            } else {
                $this->tagsRules[$tag][self::TR_TAG_PARSE_STYLE][$value] = true;
            }			
        }                
    }
  }
 // -------------------------------------------- // 
  

	protected function &strToArray($str){
		$chars = null;
		preg_match_all('/./su', $str, $chars);
		return $chars[0];
	}


	function parse($text, &$errors){
		$this->curPos = -1;
		$this->curCh = null;
		$this->curChOrd = 0;
		$this->state = self::STATE_TEXT;
		$this->states = array();
		$this->quotesOpened = 0;
		$this->noTypoMode = false;

		if($this->isAutoBrMode) {
			$this->text = preg_replace('/<br\/?>(\r\n|\n\r|\n)?/ui', $this->nl, $text);
		} else {
			$this->text = $text;
		}


		if(!empty($this->autoReplace)){
			$this->text = str_ireplace($this->autoReplace['from'], $this->autoReplace['to'], $this->text);
		}
		$this->textBuf = $this->strToArray($this->text);
		$this->textLen = count($this->textBuf);
		$this->getCh();
		$content = '';
		$this->outBuffer='';
		$this->brAdded=0;
		$this->tagsStack = array();
		$this->openedTag = null;
		$this->errors = array();
		$this->skipSpaces();
		$this->anyThing($content);
		$errors = $this->errors;
		return $content;
	}

	protected function getCh(){
		return $this->goToPosition($this->curPos+1);
	}

	protected function goToPosition($position){
		$this->curPos = $position;
		if($this->curPos < $this->textLen){
			$this->curCh = $this->textBuf[$this->curPos];
			$this->curChOrd = uniord($this->curCh);
			$this->curChClass = $this->getCharClass($this->curChOrd);
		} else {
			$this->curCh = null;
			$this->curChOrd = 0;
			$this->curChClass = 0;
		}
		return $this->curCh;
	}

	protected function saveState(){
		$state = array(
			'pos'   => $this->curPos,
			'ch'    => $this->curCh,
			'ord'   => $this->curChOrd,
			'class' => $this->curChClass,
		);

		$this->states[] = $state;
		return count($this->states)-1;
	}

	protected function restoreState($index = null){
		if(!count($this->states)) throw new Exception('End of stack');
		if($index == null){
			$state = array_pop($this->states);
		} else {
			if(!isset($this->states[$index])) throw new Exception('Invalid stack index');
			$state = $this->states[$index];
			$this->states = array_slice($this->states, 0, $index);
		}

		$this->curPos     = $state['pos'];
		$this->curCh      = $state['ch'];
		$this->curChOrd   = $state['ord'];
		$this->curChClass = $state['class'];
	}

	protected function matchCh($ch, $skipSpaces = false){
		if($this->curCh == $ch) {
			$this->getCh();
			if($skipSpaces) $this->skipSpaces();
			return true;
		}

		return false;
	}

	protected function matchChClass($chClass, $skipSpaces = false){
		if(($this->curChClass & $chClass) == $chClass) {
			$ch = $this->curCh;
			$this->getCh();
			if($skipSpaces) $this->skipSpaces();
			return $ch;
		}

		return false;
	}

	protected function matchStr($str, $skipSpaces = false){
		$this->saveState();
		$len = mb_strlen($str, 'UTF-8');
		$test = '';
		while($len-- && $this->curChClass){
			$test.=$this->curCh;
			$this->getCh();
		}

		if($test == $str) {
			if($skipSpaces) $this->skipSpaces();
			return true;
		} else {
			$this->restoreState();
			return false;
		}
	}

	protected function skipUntilCh($ch){
		$chPos = mb_strpos($this->text, $ch, $this->curPos, 'UTF-8');
		if($chPos){
			return $this->goToPosition($chPos);
		} else {
			return false;
		}
	}

	protected function skipUntilStr($str){
		$str = $this->strToArray($str);
		$firstCh = $str[0];
		$len = count($str);
		while($this->curChClass){
			if($this->curCh == $firstCh){
				$this->saveState();
				$this->getCh();
				$strOK = true;
				for($i = 1; $i<$len ; $i++){
					if(!$this->curChClass){
						return false;
					}
					if($this->curCh != $str[$i]){
						$strOK = false;
						break;
					}
					$this->getCh();
				}
				if(!$strOK){
					$this->restoreState();
				} else {
					return true;
				}
			}
			$this->getCh();
		}
		return false;
	}

	protected function getCharClass($ord){
		return isset($this->chClasses[$ord]) ? $this->chClasses[$ord] : self::PRINATABLE;
	}

	protected function skipSpaces(&$count = 0){
		while($this->curChClass == self::SPACE) {
			$this->getCh();
			$count++;
		}
		return $count > 0;
	}

	protected function name(&$name = '', $minus = false){
		if(($this->curChClass & self::LAT) == self::LAT){
			$name.=$this->curCh;
			$this->getCh();
		} else {
			return false;
		}

		while((($this->curChClass & self::NAME) == self::NAME || ($minus && $this->curCh=='-'))){
			$name.=$this->curCh;
			$this->getCh();
		}

		$this->skipSpaces();
		return true;
	}

	protected function tag(&$tag, &$params, &$content, &$short){
		$this->saveState();
		$params = array();
		$tag = '';
		$closeTag = '';
		$params = array();
		$short = false;
		if(!$this->tagOpen($tag, $params, $short)) return false;
		if($short) return true;

		$oldState = $this->state;
		$oldNoTypoMode = $this->noTypoMode;
		//$this->quotesOpened = 0;

		if(!empty($this->tagsRules[$tag][self::TR_TAG_PREFORMATTED])){
			$this->state = self::STATE_INSIDE_PREFORMATTED_TAG;
		} elseif(!empty($this->tagsRules[$tag][self::TR_TAG_CONTAINER])){
			$this->state = self::STATE_INSIDE_NOTEXT_TAG;
		} elseif(!empty($this->tagsRules[$tag][self::TR_TAG_NO_TYPOGRAPHY])) {
			$this->noTypoMode = true;
			$this->state = self::STATE_INSIDE_TAG;
		} elseif(array_key_exists($tag, $this->tagsRules) && array_key_exists(self::TR_TAG_CALLBACK, $this->tagsRules[$tag])){
			$this->state = self::STATE_INSIDE_CALLBACK_TAG;
		} else {
			$this->state = self::STATE_INSIDE_TAG;
		}

		array_push($this->tagsStack, $tag);
		$this->openedTag = $tag;
		$content = '';
		if($this->state == self::STATE_INSIDE_PREFORMATTED_TAG){
			$this->preformatted($content, $tag);
		} elseif($this->state == self::STATE_INSIDE_CALLBACK_TAG){
			$this->callback($content, $tag);
		} else {
			$this->anyThing($content, $tag);
		}

		array_pop($this->tagsStack);
		$this->openedTag = !empty($this->tagsStack) ? array_pop($this->tagsStack) : null;

		$isTagClose = $this->tagClose($closeTag);
		if($isTagClose && ($tag != $closeTag)) {
			$this->eror("Invalid closing tag of $closeTag. Expected closing of $tag");
			//$this->restoreState();
		}

		$this->state = $oldState;
		$this->noTypoMode = $oldNoTypoMode;
		//$this->quotesOpened = $oldQuotesopen;

		return true;
	}

	protected function preformatted(&$content = '', $insideTag = null){
		while($this->curChClass){
			if($this->curCh == '<'){
				$tag = '';
				$this->saveState();
				$isClosedTag = $this->tagClose($tag);
				if($isClosedTag) $this->restoreState();
				if($isClosedTag && $tag == $insideTag) return;
			}
			$content.= isset($this->entities2[$this->curCh]) ? $this->entities2[$this->curCh] : $this->curCh;
			$this->getCh();
		}
	}

	protected function callback(&$content = '', $insideTag = null){
		while($this->curChClass){
			if($this->curCh == '<'){
				$tag = '';
				$this->saveState();
				$isClosedTag = $this->tagClose($tag);
				if($isClosedTag) $this->restoreState();
				if($isClosedTag && $tag == $insideTag) {
					if ($callback = $this->tagsRules[$tag][self::TR_TAG_CALLBACK]) {
						$content = call_user_func($callback, $content);
					}
					return;
				}
			}
			$content.= $this->curCh;
			$this->getCh();
		}
	}

	protected function tagOpen(&$name, &$params, &$short = false){
		$restore = $this->saveState();

		if(!$this->matchCh('<')) return false;
		$this->skipSpaces();
		if(!$this->name($name)){
			$this->restoreState();
			return false;
		}
		$name=mb_strtolower($name, 'UTF-8');
		if($this->curCh != '>' && $this->curCh != '/') $this->tagParams($params);

		$short = !empty($this->tagsRules[$name][self::TR_TAG_SHORT]);

		// Short && XHTML && !Slash || Short && !XHTML && !Slash = ERROR
		$slash = $this->matchCh('/');
		//if(($short && $this->isXHTMLMode && !$slash) || (!$short && !$this->isXHTMLMode && $slash)){
		if(!$short && $slash){
			$this->restoreState();
			return false;
		}

		$this->skipSpaces();

		if(!$this->matchCh('>')) {
			$this->restoreState($restore);
			return false;
		}

		$this->skipSpaces();
		return true;
	}

	protected function tagParams(&$params = array()){
		$name = null;
		$value = null;
		while($this->tagParam($name, $value)){
			$params[$name] = $value;
			$name = ''; $value = '';
		}
		return count($params) > 0;
	}

	protected function tagParam(&$name, &$value){
		$this->saveState();
		if(!$this->name($name, true)) return false;

		if(!$this->matchCh('=', true)){
			if(($this->curCh=='>' || ($this->curChClass & self::LAT) == self::LAT)){
				$value = $name;
				return true;
			} else {
				$this->restoreState();
				return false;
			}
		}

		$quote = $this->matchChClass(self::TAG_QUOTE, true);

		if(!$this->tagParamValue($value, $quote)){
			$this->restoreState();
			return false;
		}

		if($quote && !$this->matchCh($quote, true)){
			$this->restoreState();
			return false;
		}

		$this->skipSpaces();
		return true;
	}

	protected function tagParamValue(&$value, $quote){
		if($quote !== false){
			$escape = false;
			while($this->curChClass && ($this->curCh != $quote || $escape)){
				$escape = false;
				$value.=isset($this->entities1[$this->curCh]) ? $this->entities1[$this->curCh] : $this->curCh;
				if($this->curCh == '\\') $escape = true;
				$this->getCh();
			}
		} else {
			while($this->curChClass && !($this->curChClass & self::SPACE) && $this->curCh != '>'){
				$value.=isset($this->entities1[$this->curCh]) ? $this->entities1[$this->curCh] : $this->curCh;
				$this->getCh();
			}
		}

		return true;
	}

	protected function tagClose(&$name){
		$this->saveState();
		if(!$this->matchCh('<')) return false;
		$this->skipSpaces();
		if(!$this->matchCh('/')) {
			$this->restoreState();
			return false;
		}
		$this->skipSpaces();
		if(!$this->name($name)){
			$this->restoreState();
			return false;
		}
		$name=mb_strtolower($name, 'UTF-8');
		$this->skipSpaces();
		if(!$this->matchCh('>')) {
			$this->restoreState();
			return false;
		}
		return true;
	}

   // ------- Check valid Style params --------------- // 
  protected function cssParamValid($cssAllowParamsArray, $cssParamArray){
    if(!isset($cssAllowParamsArray[$cssParamArray[0]])) return false;

    $optionValue=$cssAllowParamsArray[$cssParamArray[0]];

    if(is_array($optionValue)) {
        if(!in_array($cssParamArray[1], $optionValue)) return false;
    } elseif(preg_match('%#([a-z]+):*(.*)%i', $optionValue, $m)) {
        $option =  isset($m[1]) ? $m[1] : '';
        $value =  isset($m[2]) ? $m[2] : '';
        
        switch ($option) {
            case 'regexp':
                if(!preg_match($value, $cssParamArray[1])) {
                    return false;
                }              
                break;
            default:
                return false;
                break;
        }
    } else {
        $optionValue=preg_replace('%[^a-z0-9,\.\s\+\-\_\(\)]%i', '', $optionValue);
        if(!$optionValue) return false;
    }
    
    return true;
}
 // --------------------------------------------------- // 

	protected function makeTag($tag, $params, $content, $short, $parentTag = null){
		$this->curParentTag=$parentTag;
		$tag = mb_strtolower($tag, 'UTF-8');

		$tagRules = isset($this->tagsRules[$tag]) ? $this->tagsRules[$tag] : null;

		$parentTagIsContainer = $parentTag && isset($this->tagsRules[$parentTag][self::TR_TAG_CONTAINER]);

		if($tagRules && isset($this->tagsRules[$tag][self::TR_TAG_CUT])) return '';

		if(!$tagRules || empty($tagRules[self::TR_TAG_ALLOWED])) return $parentTagIsContainer ? '' : $content;

		if($parentTagIsContainer){
			if(!isset($this->tagsRules[$parentTag][self::TR_TAG_CHILD_TAGS][$tag])) return '';
		}

		if(isset($tagRules[self::TR_TAG_CHILD])){
			if(!isset($tagRules[self::TR_TAG_PARENT][$parentTag])) return $content;
		}

    // ------- Check Style params --------------- // 
    if(isset($tagRules[self::TR_TAG_PARSE_STYLE]) && isset($params['style'])){
         $cssParams=array_map('trim', explode(';', $params['style']));
         $cssValidParams=array();           
         foreach($cssParams as $cssParam){
             if(!$cssParam) continue;
             $cssParamArray=array_map('trim', explode(':', $cssParam));   
             if(!isset($cssParamArray[0], $cssParamArray[1]) || empty($cssParamArray[0]) || empty($cssParamArray[1])) continue;              
             if(!$this->cssParamValid($tagRules[self::TR_TAG_PARSE_STYLE], $cssParamArray)) continue;       
             if(!in_array($cssParamArray[0], $this->tagsStyleAllowed)) continue;   //New v173                         
             $cssValidParams[]=$cssParamArray[0].':'.$cssParamArray[1].';';
         }
         
         $params['style']=implode(' ', $cssValidParams);                       
    }
    // ------------------------------------------ //
    
    $resParams = array();
		foreach($params as $param=>$value){
			$param = mb_strtolower($param, 'UTF-8');
			$value = trim($value);
			if($value == '') continue;
			$paramAllowedValues = isset($tagRules[self::TR_PARAM_ALLOWED][$param]) ? $tagRules[self::TR_PARAM_ALLOWED][$param] : false;
			if(empty($paramAllowedValues)) continue;

			if (is_array($paramAllowedValues)) {

				if (isset($paramAllowedValues['#domain']) and is_array($paramAllowedValues['#domain'])) {
					if(preg_match('/javascript:/ui', $value)) {
						$this->eror('An attempt to insert JavaScript in a URI');
						continue;
					}
					$bOK=false;
					foreach ($paramAllowedValues['#domain'] as $sDomain) {
						$sDomain=preg_quote($sDomain);						
						if 
(preg_match("@^(http://|https://|ftp://|//)([\w\d]+\.)?{$sDomain}/@ui",$value)) {
							$value = preg_replace('/&(amp;)+/ui', '&amp;', $value);
							$bOK=true;
							break;
						}
					}
					if (!$bOK) {
						$this->eror("Invalid value for the attribute tag $tag $param=$value");
						continue;
					}
				} elseif (!in_array($value, $paramAllowedValues)) {
					$this->eror("Invalid value for the attribute tag $tag $param=$value");
					continue;
				}
    	} elseif($paramAllowedValues === true && !empty($this->defaultTagParamRules[$param])){
				$paramAllowedValues = $this->defaultTagParamRules[$param];
			}

			if(is_string($paramAllowedValues)){
				switch($paramAllowedValues){
					case '#int':
						if(!is_numeric($value)) {
							$this->eror("Invalid value for the attribute tag $tag $param=$value. Expected number.");
							continue(2);
						}
						break;

					case '#text':
						$value = htmlspecialchars($value);
						break;

					case '#link':
						if(preg_match('/javascript:/ui', $value)) {
							$this->eror('An attempt to insert JavaScript in a URI.');
							continue(2);
						}
						if(!preg_match('/^[a-z0-9\/\#]/ui', $value)) {
							$this->eror('URI: The first character of address must be a letter or digit.');
							continue(2);
						}
					/*
          	if(!preg_match('/^(http|https|ftp):\/\//ui', $value) && !preg_match('/^(\/|\#)/ui', $value) && !preg_match('/^(mailto):/ui', $value) ) $value = 'http://'.$value;
					*/	
					
          	$value = preg_replace('/&(amp;)+/ui', '&amp;', $value);
						
						break;

					case '#image':
						if(preg_match('/javascript:/ui', $value)) {
							$this->eror('An attempt to insert JavaScript in your path to the image.');
							continue(2);
						}
						// Not work in Seditio
					/*	if(!preg_match('/^(http|https):\/\//ui', $value) && !preg_match('/^\//ui', $value)) $value = 'http://'.$value;
					*/	
					
					$value = preg_replace('/&(amp;)+/ui', '&amp;', $value);
					
					break;  

					default:
						$this->eror("Wrong description of attribute to configure the tag Jevix: $param => $paramAllowedValues");
						continue(2);
						break;
				}
			}

			$resParams[$param] = $value;
		}

		$requiredParams = isset($tagRules[self::TR_PARAM_REQUIRED]) ? array_keys($tagRules[self::TR_PARAM_REQUIRED]) : array();
		if($requiredParams){
			foreach($requiredParams as $requiredParam){
				if(!isset($resParams[$requiredParam])) return $content;
			}
		}

		if(!empty($tagRules[self::TR_PARAM_AUTO_ADD])){
		  foreach($tagRules[self::TR_PARAM_AUTO_ADD] as $name => $aValue) {
		      // If there isn't such attribute - setup it
		      if(!array_key_exists($name, $resParams) or ($aValue['rewrite'] and $resParams[$name] != $aValue['value'])) {
			  $resParams[$name] = $aValue['value'];
		      }
		  }
		}
		
		if (!isset($tagRules[self::TR_TAG_IS_EMPTY]) or !$tagRules[self::TR_TAG_IS_EMPTY]) {
			if(!$short && $content == '') return '';
		}
		
		if (isset($tagRules[self::TR_TAG_CALLBACK_FULL])) {
			$text = call_user_func($tagRules[self::TR_TAG_CALLBACK_FULL], $tag, $resParams);
		} else {
			$text='<'.$tag;

			foreach($resParams as $param => $value) {
				if ($value != '') {
					$text.=' '.$param.'="'.$value.'"';
				}
			}

			$text.= $short && $this->isXHTMLMode ? ' />' : '>';
			if(isset($tagRules[self::TR_TAG_CONTAINER])) $text .= "\r\n";
			if(!$short) $text.= $content.'</'.$tag.'>';
			if($parentTagIsContainer) $text .= "\r\n";
			if($tag == 'br') $text.="\r\n";
		}
		return $text;
	}

	protected function comment(){
		if(!$this->matchStr('<!--')) return false;
		return $this->skipUntilStr('-->');
	}

	protected function anyThing(&$content = '', $parentTag = null){
		$this->skipNL();
		while($this->curChClass){
			$tag = '';
			$params = null;
			$text = null;
			$shortTag = false;
			$name = null;

			if($this->state == self::STATE_INSIDE_NOTEXT_TAG && $this->curCh!='<'){
				$this->skipUntilCh('<');
			}

			if($this->curCh == '<' && $this->tag($tag, $params, $text, $shortTag)){
				$tagText = $this->makeTag($tag, $params, $text, $shortTag, $parentTag);
				$content.=$tagText;
				if ($tag=='br') {
					$this->skipNL();
				} elseif (isset($this->tagsRules[$tag]) and isset($this->tagsRules[$tag][self::TR_TAG_BLOCK_TYPE])) {
					$count=0;
					$this->skipNL($count,2);
				} elseif ($tagText == ''){
					$this->skipSpaces();
				}
			} elseif($this->curCh == '<' && $this->comment()){
				continue;

			} elseif($this->curCh == '<') {
				$this->saveState();
				if($this->tagClose($name)){
					if($this->state == self::STATE_INSIDE_TAG || $this->state == self::STATE_INSIDE_NOTEXT_TAG) {
						$this->restoreState();
						return false;
					} else {
						$this->eror('Do not expect a closing tag '.$name);
					}
				} else {
					if($this->state != self::STATE_INSIDE_NOTEXT_TAG) $content.=$this->entities2['<'];
					$this->getCh();
				}

			} elseif($this->text($text)){
				$content.=$text;
			}
		}

		return true;
	}

	protected function skipNL(&$count = 0,$limit=0){
		if(!($this->curChClass & self::NL)) return false;
		$count++;
		$firstNL = $this->curCh;
		$nl = $this->getCh();
		while($this->curChClass & self::NL){
			if($limit>0 and $count>=$limit) break;
			if($nl == $firstNL) $count++;
			$nl = $this->getCh();
			$this->skipSpaces();
		}
		return true;
	}

	protected function dash(&$dash){
		if($this->curCh != '-') return false;
		$dash = '';
		$this->saveState();
		$this->getCh();
		while($this->curCh == '-') $this->getCh();
		if(!$this->skipNL() && !$this->skipSpaces()){
			$this->restoreState();
			return false;
		}
		$dash = $this->dash;
		return true;
	}

	protected function punctuation(&$punctuation){
		if(!($this->curChClass & self::PUNCTUATUON)) return false;
		$this->saveState();
		$punctuation = $this->curCh;
		$this->getCh();

		if($punctuation == '.' && $this->curCh == '.'){
			while($this->curCh == '.') $this->getCh();
			$punctuation = $this->dotes;
		} elseif($punctuation == '!' && $this->curCh == '!'){
			while($this->curCh == '!') $this->getCh();
			$punctuation = '!!!';
		} elseif (($punctuation == '?' || $punctuation == '!') && $this->curCh == '.'){
			while($this->curCh == '.') $this->getCh();
			$punctuation.= '..';
		}

		if($this->curChClass & self::RUS) {
			if($punctuation != '.') $punctuation.= ' ';
			return true;
		} elseif(($this->curChClass & self::SPACE) || ($this->curChClass & self::NL) || !$this->curChClass){
			return true;
		} else {
			$this->restoreState();
			return false;
		}
	}

	protected function number(&$num){
		if(!(($this->curChClass & self::NUMERIC) == self::NUMERIC)) return false;
		$num = $this->curCh;
		$this->getCh();
		while(($this->curChClass & self::NUMERIC) == self::NUMERIC){
			$num.= $this->curCh;
			$this->getCh();
		}
		return true;
	}

	protected function htmlEntity(&$entityCh){
		if($this->curCh<>'&') return false;
		$this->saveState();
		$this->matchCh('&');
		if($this->matchCh('#')){
			$entityCode = 0;
			if(!$this->number($entityCode) || !$this->matchCh(';')){
				$this->restoreState();
				return false;
			}
			$entityCh = html_entity_decode("&#$entityCode;", ENT_COMPAT, 'UTF-8');
			return true;
		} else{
			$entityName = '';
			if(!$this->name($entityName) || !$this->matchCh(';')){
				$this->restoreState();
				return false;
			}
			$entityCh = html_entity_decode("&$entityName;", ENT_COMPAT, 'UTF-8');
			return true;
		}
	}

	protected function quote($spacesBefore,  &$quote, &$closed){
		$this->saveState();
		$quote = $this->curCh;
		$this->getCh();
		if($this->quotesOpened == 0 && !(($this->curChClass & self::ALPHA) || ($this->curChClass & self::NUMERIC))) {
			$this->restoreState();
			return false;
		}
		$closed =  ($this->quotesOpened >= 2) ||
			  (($this->quotesOpened >  0) &&
			   (!$spacesBefore || $this->curChClass & self::SPACE || $this->curChClass & self::PUNCTUATUON));
		return true;
	}

	protected function makeQuote($closed, $level){
		$levels = count($this->textQuotes);
		if($level > $levels) $level = $levels;
		return $this->textQuotes[$level][$closed ? 1 : 0];
	}


	protected function text(&$text){
		$text = '';
		//$punctuation = '';
		$dash = '';
		$newLine = true;
		$newWord = true; 
		$url = null;
		$href = null;

		//$typoEnabled = true;
		$typoEnabled = !$this->noTypoMode;

		while(($this->curCh != '<') && $this->curChClass){
			$brCount = 0;
			$spCount = 0;
			$quote = null;
			$closed = false;
			$punctuation = null;
			$entity = null;

			$this->skipSpaces($spCount);

			if (!$spCount && $this->curCh == '&' && $this->htmlEntity($entity)){
				$text.= isset($this->entities2[$entity]) ? $this->entities2[$entity] : $entity;
			} elseif ($typoEnabled && ($this->curChClass & self::PUNCTUATUON) && $this->punctuation($punctuation)){
				if($spCount && $punctuation == '.' && ($this->curChClass & self::LAT)) $punctuation = ' '.$punctuation;
				$text.=$punctuation;
				$newWord = true;
			} elseif ($typoEnabled && ($spCount || $newLine) && $this->curCh == '-' && $this->dash($dash)){
				$text.=$dash;
				$newWord = true;
			} elseif ($typoEnabled && ($this->curChClass & self::HTML_QUOTE) && $this->quote($spCount, $quote, $closed)){
				$this->quotesOpened+=$closed ? -1 : 1;
				if($this->quotesOpened<0){
					$closed = false;
					$this->quotesOpened=1;
				}
				$quote = $this->makeQuote($closed, $closed ? $this->quotesOpened : $this->quotesOpened-1);
				if($spCount) $quote = ' '.$quote;
				$text.= $quote;
				$newWord = true;
			} elseif ($spCount>0){
				$text.=' ';
				$newWord = true;
			} elseif ($this->isAutoBrMode && $this->skipNL($brCount)){
				if ($this->curParentTag
				  and isset($this->tagsRules[$this->curParentTag])
				  and isset($this->tagsRules[$this->curParentTag][self::TR_TAG_NO_AUTO_BR])
				  and (is_null($this->openedTag) or isset($this->tagsRules[$this->openedTag][self::TR_TAG_NO_AUTO_BR]))
				  ) {
				} else {
				  $br = $this->br.$this->nl;
				  $text.= $brCount == 1 ? $br : $br.$br;
				}
				$newLine = true;
				$newWord = true;
			} elseif ($newWord && $this->isAutoLinkMode && ($this->curChClass & self::LAT) && $this->openedTag!='a' && $this->url($url, $href)){
				$text.= $this->makeTag('a' , array('href' => $href), $url, false);
			} elseif($this->curChClass & self::PRINATABLE){
				$text.=isset($this->entities2[$this->curCh]) ? $this->entities2[$this->curCh] : $this->curCh;
				$this->getCh();
				$newWord = false;
				$newLine = false;
			} else {
				$this->getCh();
			}
		}

		$this->skipSpaces();
		return $text != '';
	}

	protected function url(&$url, &$href){
		$this->saveState();
		$url = '';
		//$name = $this->name();
		//switch($name)
		$urlChMask = self::URL | self::ALPHA | self::PUNCTUATUON;

		if($this->matchStr('http://')){
			while($this->curChClass & $urlChMask){
				$url.= $this->curCh;
				$this->getCh();
			}

			if(!mb_strlen($url, 'UTF-8')) {
				$this->restoreState();
				return false;
			}

			$href = 'http://'.$url;
			return true;
		} elseif($this->matchStr('https://')){
			while($this->curChClass & $urlChMask){
				$url.= $this->curCh;
				$this->getCh();
			}

			if(!mb_strlen($url, 'UTF-8')) {
				$this->restoreState();
				return false;
			}

			$href = 'https://'.$url;
			return true;
		} elseif($this->matchStr('www.')){
			while($this->curChClass & $urlChMask){
				$url.= $this->curCh;
				$this->getCh();
			}

			if(!mb_strlen($url, 'UTF-8')) {
				$this->restoreState();
				return false;
			}

			$url = 'www.'.$url;
			$href = 'http://'.$url;
			return true;
		}
		$this->restoreState();
		return false;
	}

	protected function eror($message){
		$str = '';
		$strEnd = min($this->curPos + 8, $this->textLen);
		for($i = $this->curPos; $i < $strEnd; $i++){
			$str.=$this->textBuf[$i];
		}

		$this->errors[] = array(
			'message' => $message,
			'pos'     => $this->curPos,
			'ch'      => $this->curCh,
			'line'    => 0,
			'str'     => $str,
		);
	}
}

function uniord($c) {
    $h = ord($c{0});
    if ($h <= 0x7F) {
	return $h;
    } else if ($h < 0xC2) {
	return false;
    } else if ($h <= 0xDF) {
	return ($h & 0x1F) << 6 | (ord($c{1}) & 0x3F);
    } else if ($h <= 0xEF) {
	return ($h & 0x0F) << 12 | (ord($c{1}) & 0x3F) << 6
				 | (ord($c{2}) & 0x3F);
    } else if ($h <= 0xF4) {
	return ($h & 0x0F) << 18 | (ord($c{1}) & 0x3F) << 12
				 | (ord($c{2}) & 0x3F) << 6
				 | (ord($c{3}) & 0x3F);
    } else {
	return false;
    }
}

function unichr($c) {
    if ($c <= 0x7F) {
	return chr($c);
    } else if ($c <= 0x7FF) {
	return chr(0xC0 | $c >> 6) . chr(0x80 | $c & 0x3F);
    } else if ($c <= 0xFFFF) {
	return chr(0xE0 | $c >> 12) . chr(0x80 | $c >> 6 & 0x3F)
				    . chr(0x80 | $c & 0x3F);
    } else if ($c <= 0x10FFFF) {
	return chr(0xF0 | $c >> 18) . chr(0x80 | $c >> 12 & 0x3F)
				    . chr(0x80 | $c >> 6 & 0x3F)
				    . chr(0x80 | $c & 0x3F);
    } else {
	return false;
    }
}

?>