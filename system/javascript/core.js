function popup(code,w,h)
	{ window.open(get_basehref()+'plug.php?o='+code,'','toolbar=0,location=0,directories=0,menuBar=0,resizable=0,scrollbars=yes,width='+w+',height='+h+',left=32,top=16'); }

function pfs(id,c1,c2)
	{ window.open(get_basehref()+'pfs.php?userid='+id+'&c1='+c1+'&c2='+c2,'PFS','status=1, toolbar=0,location=0,directories=0,menuBar=0,resizable=1,scrollbars=yes,width=754,height=512,left=32,top=16'); }

function help(rcode,c1,c2)
	{ window.open(get_basehref()+'plug.php?h='+rcode+'&c1='+c1+'&c2='+c2,'Help','toolbar=0,location=0,directories=0,menuBar=0,resizable=0,scrollbars=yes,width=480,height=512,left=32,top=16'); }

function comments(rcode)
	{ window.open(get_basehref()+'comments.php?id='+rcode,'Comments','toolbar=0,location=0,directories=0,menuBar=0,resizable=0,scrollbars=yes,width=480,height=512,left=16,top=16'); }

function ratings(rcode)
	{ window.open(get_basehref()+'ratings.php?id='+rcode,'Ratings','toolbar=0,location=0,directories=0,menuBar=0,resizable=0,scrollbars=yes,width=480,height=512,left=16,top=16'); }

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
    document.cookie = name+"="+value+expires+"; path=/";}

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

window.onload = function(e) {  
	var cookie = readCookie("style");  
	var title = cookie ? cookie : getPreferredStyleSheet();  
	setActiveStyleSheet(title);
}

window.onunload = function(e) {  
	var title = getActiveStyleSheet();  
	createCookie("style", title, 365);
}

var cookie = readCookie("style"); 
var title = cookie ? cookie : getPreferredStyleSheet();
setActiveStyleSheet(title);