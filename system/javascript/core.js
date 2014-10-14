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

window.name='main';

function sed_ajax_getxmlhttp()
	{
  	var xmlhttp = false;
  	if (window.XMLHttpRequest)
  		{ xmlhttp = new XMLHttpRequest() }
	else if (window.ActiveXObject)
		{
		try
    		{ xmlhttp = new ActiveXObject("Msxml2.XMLHTTP") }
		catch (e)
			{
			try
				{ xmlhttp = new ActiveXObject("Microsoft.XMLHTTP") }
			catch (E)
				{ xmlhttp=false }
			}
		}
	return xmlhttp;
	}

function sed_ajax_pass(url, callbackFunction, params)
	{
	var xmlhttp = new sed_ajax_getxmlhttp();

	if (xmlhttp)
		{
		xmlhttp.onreadystatechange =
		function ()
      {
      	if (xmlhttp && xmlhttp.readyState==4)
        	{
					if (xmlhttp.status==200)
						{
	         		var response = xmlhttp.responseText;
	         		var functionToCall = callbackFunction + '(response,'+params+')';
							eval(functionToCall);
						}
					}
			}
    	xmlhttp.open("GET",url,true);
    	xmlhttp.send(null);
		}
	}

function sed_ajax_set(url, obj_id)
	{
	var xmlhttp = new sed_ajax_getxmlhttp();

	if (xmlhttp)
		{
    xmlhttp.onreadystatechange =
		function ()
			{
			if (xmlhttp && xmlhttp.readyState==4)
				{
                if (xmlhttp.status==200)
	                {
                  	if(typeof obj_id == 'object')
                  		{ obj_id.innerHTML = xmlhttp.responseText; }
                  	else
                  		{ document.getElementById(obj_id).innerHTML = xmlhttp.responseText; }
                	}
        }
      }
    	xmlhttp.open("GET",url,true);
			xmlhttp.send(null);
		}
	}

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

  nanotabs = function(s){
    var i, s=s||{}, o="cesdf".split(''); 
    for(i in o) s[o[i]]=s[o[i]]||nanotabs.settings[o[i]];
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

  nanotabs.settings = { c:"sedtabs", e:"click", s:"selected", d:0, f:false };
  nanotabs();
}

var cookie = readCookie("style"); 
var title = cookie ? cookie : getPreferredStyleSheet();
setActiveStyleSheet(title);