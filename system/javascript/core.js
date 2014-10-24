function popup(code,w,h)
	{ window.open(get_basehref()+'plug.php?o='+code,'','toolbar=0,location=0,directories=0,menuBar=0,resizable=0,scrollbars=yes,width='+w+',height='+h+',left=32,top=16'); }

function pfs(id,c1,c2)
	{ window.open(get_basehref()+'pfs.php?userid='+id+'&c1='+c1+'&c2='+c2,'PFS','status=1, toolbar=0,location=0,directories=0,menuBar=0,resizable=1,scrollbars=yes,width=754,height=512,left=32,top=16'); }

function help(rcode,c1,c2)
	{ window.open(get_basehref()+'plug.php?h='+rcode+'&c1='+c1+'&c2='+c2,'Help','toolbar=0,location=0,directories=0,menuBar=0,resizable=0,scrollbars=yes,width=480,height=512,left=32,top=16'); }

function polls(rcode)
	{ window.open(get_basehref()+'polls.php?id='+rcode,'Polls','toolbar=0,location=0,directories=0,menuBar=0,resizable=0,scrollbars=yes,width=608,height=448,left=16,top=16'); }

function pollvote(rcode,rvote)
	{ window.open(get_basehref()+'polls.php?a=send&id='+rcode+'&vote='+rvote,'Polls','toolbar=0,location=0,directories=0,menuBar=0,resizable=0,scrollbars=yes,width=608,height=448,left=16,top=16'); }

function picture(url,sx,sy)
	{
  var ptop=(window.screen.height-200)/2;
  var pleft=(window.screen.width-200)/2;
  window.open(url,'Picture','toolbar=0,location=0,status=0, directories=0,menubar=0,resizable=1,scrollbars=yes,width='+sx+',height='+sy+',left='+pleft+',top='+ptop+'');
  }

function redirect(url)
	{ location.href = url.options[url.selectedIndex].value; }
	

function get_basehref()
{
 var loc = "";
 var b = document.getElementsByTagName('base');
 if (b && b[0] && b[0].href) {
   if (b[0].href.substr(b[0].href.length-1) == '/' && loc.charAt(0) == '/')
     loc = loc.substr(1);
   loc = b[0].href + loc;
 }
return loc;
}

function toggleblock(id)
	{
	var bl = document.getElementById(id);
	if(bl.style.display == 'none')
		{ bl.style.display = ''; }
	else
		{ bl.style.display = 'none'; }
	}
	
/* =============== AJAX functions ===================== */

sed_ajx = {
	//Create a xmlHttpRequest object - this is the constructor. 
	getHTTPObject : function() {
		var http = false;
		//Use IE's ActiveX items to load the file.
		if(typeof ActiveXObject != 'undefined') {
			try {http = new ActiveXObject("Msxml2.XMLHTTP");}
			catch (e) {
				try {http = new ActiveXObject("Microsoft.XMLHTTP");}
				catch (E) {http = false;}
			}
		//If ActiveX is not available, use the XMLHttpRequest of Firefox/Mozilla etc. to load the document.
		} else if (window.XMLHttpRequest) {
			try {http = new XMLHttpRequest();}
			catch (e) {http = false;}
		}
		return http;
	},
	
	// This function is called from the user's script. 
	//Arguments - 
	//	url	- The url of the serverside script that is to be called. 
	//	callback - Function that must be called once the data is ready.
	//	format - 'xml','json' or 'text'. Default:'text'
	//	method - GET or POST. Default 'GET'
	
	load : function (url,callback,format,method,opt) {
		var http = this.init(); //The XMLHttpRequest object is recreated at every call - to defeat Cache problem in IE
		if(!http||!url) return;
		//XML Format need this for some Mozilla Browsers
		if (http.overrideMimeType) http.overrideMimeType('text/xml');

		if(!method) method = "GET";//Default method is GET
		if(!format) format = "text";//Default return type is 'text'
		if(!opt) opt = {};
		format = format.toLowerCase();
		method = method.toUpperCase();
		
		//Kill the Cache problem in IE.
		var now = "uid=" + new Date().getTime();
		url += (url.indexOf("?")+1) ? "&" : "?";
		url += now;

		var parameters = null;

		if(method=="POST") {				
			var postparams = '';			
			if(opt.formid) postparams = '&' + this.serialize(opt.formid);			
			var parts = url.split("\?");
			url = parts[0];
			parameters = parts[1] + postparams; 
		}
		
		http.open(method, url, true);

		if(method=="POST") { http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");  }

		var ths = this;// Closure
		if(opt.handler) { //If a custom handler is defined, use it
			http.onreadystatechange = function() { opt.handler(http); };
		} else {
			http.onreadystatechange = function () {//Call a function when the state changes.
				if (http.readyState == 4) {//Ready State will be 4 when the document is loaded.
					if(http.status == 200) {
						var result = "";
						if(http.responseText) result = http.responseText;
						//If the return is in JSON format, eval the result before returning it.
						if(format.charAt(0) == "j") {
							//\n's in JSON string, when evaluated will create errors in IE
							result = result.replace(/[\n\r]/g,"");
							result = eval('('+result+')');

						} else if(format.charAt(0) == "x") { //XML Return
							result = http.responseXML;
						}

						//Give the data to the callback function.
						if(callback) callback(result);
					} else {						
						if(opt.loading) document.getElementById(opt.loadingid).removeChild(opt.loading); //Remove the loading indicator					
						if(opt.onError) opt.onError(http.status);
					}
				}
			}
		}
		http.send(parameters);
	},
	bind : function(user_options) {
		var opt = {
			'url':'', 			//URL to be loaded
			'onSuccess':false,	//Function that should be called at success
			'onError':false,	//Function that should be called at error
			'format':"text",	//Return type - could be 'xml','json' or 'text'
			'method':"GET",		//GET or POST
			'update':"",		//The id of the element where the resulting data should be shown.
			'loading':"", //ID element that would be inserted into the document once the url starts loading and removed when the data has finished loading. This will be inserted into a div with class name 'loading-indicator' and will be placed at 'top:0px;left:0px;'
			'formid':"" //ID form, serialize data from form for POST send
		}
		for(var key in opt) {
			if(user_options[key]) {//If the user given options contain any valid option, ...
				opt[key] = user_options[key];// ..that option will be put in the opt array.
			}
		}
		
		if(!opt.url) return; //Return if a url is not provided

		var div = false;
		if(opt.loading) { //Show a loading indicator from the given HTML
			div = document.createElement("div");
			opt.loadingid = opt.loading;						
			var intElemOffsetHeight = Math.floor(document.getElementById(opt.loading).offsetHeight/2) + 16;
			var intElemOffsetWidth = Math.floor(document.getElementById(opt.loading).offsetWidth/2) - 16;						
			div.setAttribute("style","position:absolute; margin-top:-" + intElemOffsetHeight + "px; margin-left:" + intElemOffsetWidth + "px;");
			div.setAttribute("class","loading-indicator");
			document.getElementById(opt.loading).appendChild(div);
			opt.loading=div;
		}
		
		this.load(opt.url,function(data){
			if(opt.onSuccess) opt.onSuccess(data);
			if(div) document.getElementById(opt.loadingid).removeChild(div); //Remove the loading indicator			
			if(opt.update && data != "") document.getElementById(opt.update).innerHTML = data;
		},opt.format,opt.method, opt);
	},
	serialize : function(formid)
	{
		var form = document.getElementById(formid);  
	  if (!form || form.nodeName !== "FORM") {
			return;
		}
		var i, j, q = [];
		for (i = form.elements.length - 1; i >= 0; i = i - 1) {
			if (form.elements[i].name === "") {
				continue;
			}
			switch (form.elements[i].nodeName) {
			case 'INPUT':
				switch (form.elements[i].type) {
				case 'text':
				case 'hidden':
				case 'password':
				case 'button':
				case 'reset':
				case 'submit':
					q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
					break;
				case 'checkbox':
				case 'radio':
					if (form.elements[i].checked) {
						q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
					}						
					break;
				case 'file':
					break;
				}
				break;			 
			case 'TEXTAREA':
				q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
				break;
			case 'SELECT':
				switch (form.elements[i].type) {
				case 'select-one':
					q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
					break;
				case 'select-multiple':
					for (j = form.elements[i].options.length - 1; j >= 0; j = j - 1) {
						if (form.elements[i].options[j].selected) {
							q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].options[j].value));
						}
					}
					break;
				}
				break;
			case 'BUTTON':
				switch (form.elements[i].type) {
				case 'reset':
				case 'submit':
				case 'button':
					q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
					break;
				}
				break;
			}
		}
		return q.join("&");	
	},
	init : function() {return this.getHTTPObject();}
}

 /* ============================== */

function setActiveStyleSheet(title) {  
    var i, a, main;  
    for(i=0; (a = document.getElementsByTagName("link")[i]); i++) {    
    if(a.getAttribute("rel").indexOf("style") != -1 && a.getAttribute("title")) 
        {      
            a.disabled = true;      
            if(a.getAttribute("title") == title) a.disabled = false;   
        }  
    }
}
 
function getActiveStyleSheet(){
    var i, a;  
    for(i=0; (a = document.getElementsByTagName("link")[i]); i++) 
    {    
        if(a.getAttribute("rel").indexOf("style") != -1 && a.getAttribute("title") && !a.disabled) 
        return a.getAttribute("title");  
    } 
    return null;
}

function getPreferredStyleSheet() 
{  
    var i, a;  
    for(i=0; (a = document.getElementsByTagName("link")[i]); i++) 
    {    
        if(a.getAttribute("rel").indexOf("style") != -1 && a.getAttribute("rel").indexOf("alt") == -1 && a.getAttribute("title")) 
        return a.getAttribute("title");  
    }  
    return null;
}

function createCookie(name,value,days) 
{  
    if (days) 
    {    
    var date = new Date();    
    date.setTime(date.getTime()+(days*24*60*60*1000));    
    var expires = "; expires="+date.toGMTString();  
    }  
    else expires = "";  
    document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) 
{  
    var nameEQ = name + "=";  
    var ca = document.cookie.split(';');  
    for(var i=0;i < ca.length;i++) 
    {    
        var c = ca[i];    
        while (c.charAt(0)==' ') c = c.substring(1,c.length);    
        if (c.indexOf(nameEQ) == 0) 
        return c.substring(nameEQ.length,c.length);  
    }  
    return null;
}

window.onunload = function(e) {  
	var title = getActiveStyleSheet();  
	createCookie("style", title, 365);
}

window.onload =  function(e){

	var cookie = readCookie("style");  
	var title = cookie ? cookie : getPreferredStyleSheet();  
	setActiveStyleSheet(title);

  /* ============== Sed Tabs ================ */
  
	var gc = function(s){ return document.getElementsByClassName(s); };
  if(!document.getElementsByClassName) {
    var all = document.getElementsByTagName('*');
    gc = function(c){
      var e=[]; c=' '+c+' ';
      for(var i=0; i<all.length; i++)
        if((' '+all[i].className+' ').indexOf(c)>=0)
          e.push(all[i]);
      return e;
    }
  }

  var w = function(id){ var d; return (d=document.getElementById(id))&&[d]||gc(id); },
  bind = function(f){ var self=this; return function(){ return f.apply(self, arguments); }; },
  map = function(f,e,a) { for(var i=0; i<e.length; i++) f.apply(e[i],a||[]); },
  add = function(c) { this.className += c; },
  remove = function(c) { this.className = this.className.replace(new RegExp("(^|\\s)" + c + "(\\s|$)",'g'),''); } ,
  hide = function() { this.style.display="none"; },
  show = function() { this.style.display="block"; };

  var tab = function(id,a,e,s) {
    map(remove,a,[s.s]);
    add.call(this,s.s);
    map(hide,e);
    map(show,w(id));
  }

  sedtabs = function(s) {
    var i, s=s||{}, o="cesdf".split(''); 
    for(i in o) s[o[i]]=s[o[i]]||sedtabs.settings[o[i]];
    var c=w(s.c), f=function(){
      var t=this,o=t[0],a=[t[1],t[2],t[3],t[4]];
      if( !s.f || s.f.apply(o,a)!==false ) tab.apply(o,a);
      return false;
    }
    for(i=0; i<c.length; i++) {
      var x=0, e=[], a=[], h=[], t=c[i].getElementsByTagName("a");
      for(var j=0; j<t.length; j++)
        if(t[j].href.match(/#/)) {
          h.push(t[j].href.split('#')[1]);
          if(typeof s.d=="string" && h[x]==s.d) s.d=x;
          a.push(t[j]);
          var g = w(h[x]);
          for(var k=0; k<g.length; k++) e.push(g[k]);
          x++;
        }
      for(var j=0; j<a.length; j++)
        a[j]['on'+s.e] = bind.call([a[j],h[j],a,e,s],f);
      if(typeof s.d=="number" && s.d>=0) tab.call(a[s.d],h[s.d],a,e,s);
    }
  }

  sedtabs.settings = { c:"sedtabs", e:"click", s:"selected", d:0, f:false };
  sedtabs();
  
 /* ============================== */

}

var cookie = readCookie("style"); 
var title = cookie ? cookie : getPreferredStyleSheet();
setActiveStyleSheet(title);

window.name='main';