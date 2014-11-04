function popup(code,w,h,modal)
	{ 
		if (!modal) { window.open(get_basehref()+'plug.php?o='+code,'','toolbar=0,location=0,directories=0,menuBar=0,resizable=0,scrollbars=yes,width='+w+',height='+h+',left=32,top=16'); }
		else { sed_modal.show({iframe:get_basehref()+'plug.php?o='+code, boxid:'frameless',animate:false,width:w,height:h,fixed:false,maskid:'bluemask',maskopacity:40}); } 
	}

function pfs(id,c1,c2,modal)
	{	
		if (!modal) { window.open(get_basehref()+'pfs.php?userid='+id+'&c1='+c1+'&c2='+c2,'PFS','status=1, toolbar=0,location=0,directories=0,menuBar=0,resizable=1,scrollbars=yes,width=754,height=512,left=32,top=16'); }
		else { sed_modal.show({iframe:get_basehref()+'pfs.php?userid='+id+'&c1='+c1+'&c2='+c2, boxid:'frameless',animate:false,width:754,height:512,fixed:false,maskid:'bluemask',maskopacity:40}); }
	}

function help(rcode,c1,c2,modal)
	{
		if (!modal) { window.open(get_basehref()+'plug.php?h='+rcode+'&c1='+c1+'&c2='+c2,'Help','toolbar=0,location=0,directories=0,menuBar=0,resizable=0,scrollbars=yes,width=480,height=512,left=32,top=16'); }
		else { sed_modal.show({iframe:get_basehref()+'plug.php?h='+rcode+'&c1='+c1+'&c2='+c2, boxid:'frameless',animate:false,width:500,height:512,fixed:false,maskid:'bluemask',maskopacity:40}); } 
	}

function polls(rcode,modal)
	{  
		if (!modal) { window.open(get_basehref()+'polls.php?id='+rcode,'Polls','toolbar=0,location=0,directories=0,menuBar=0,resizable=0,scrollbars=yes,width=608,height=448,left=16,top=16'); }
		else { sed_modal.show({iframe:get_basehref()+'polls.php?id='+rcode, boxid:'frameless',animate:false,width:608,height:448,fixed:false,maskid:'bluemask',maskopacity:40}); }
	}

function pollvote(rcode,rvote,modal)
	{ 
		if (!modal) { window.open(get_basehref()+'polls.php?a=send&id='+rcode+'&vote='+rvote,'Polls','toolbar=0,location=0,directories=0,menuBar=0,resizable=0,scrollbars=yes,width=608,height=448,left=16,top=16'); }
		else { sed_modal.show({iframe:get_basehref()+'polls.php?a=send&id='+rcode+'&vote='+rvote, boxid:'frameless',animate:false,width:608,height:448,fixed:false,maskid:'bluemask',maskopacity:40}); }
	}

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

 /* ========= Modal Windows ====== */
 
 // TinyBox2 + Dragable JavaScript Modal Windows
sed_modal = function(){
	var j,m,b,g,v,p=0;
	
	var Drag = {
		obj : null,
		init : function(o, oRoot, minX, maxX, minY, maxY, bSwapHorzRef, bSwapVertRef, fXMapper, fYMapper) {
			o.onmousedown = Drag.start;
			o.hmode = bSwapHorzRef ? false : true;
			o.vmode = bSwapVertRef ? false : true;
			o.root = oRoot && oRoot != null ? oRoot : o;
	
			if (o.hmode  && isNaN(parseInt(o.root.style.left  ))) o.root.style.left = "0px";
			if (o.vmode  && isNaN(parseInt(o.root.style.top   ))) o.root.style.top = "0px";
			if (!o.hmode && isNaN(parseInt(o.root.style.right ))) o.root.style.right = "0px";
			if (!o.vmode && isNaN(parseInt(o.root.style.bottom))) o.root.style.bottom = "0px";
	
			o.minX = typeof minX != 'undefined' ? minX : null;
			o.minY = typeof minY != 'undefined' ? minY : null;
			o.maxX = typeof maxX != 'undefined' ? maxX : null;
			o.maxY = typeof maxY != 'undefined' ? maxY : null;
	
			o.xMapper = fXMapper ? fXMapper : null;
			o.yMapper = fYMapper ? fYMapper : null;
	
			o.root.onDragStart = new Function();
			o.root.onDragEnd = new Function();
			o.root.onDrag = new Function();
			},
		start : function(e) {
			var o = Drag.obj = this;
			e = Drag.fixE(e);
			var y = parseInt(o.vmode ? o.root.style.top  : o.root.style.bottom);
			var x = parseInt(o.hmode ? o.root.style.left : o.root.style.right );
			o.root.onDragStart(x, y);
	
			o.lastMouseX = e.clientX;
			o.lastMouseY = e.clientY;
	
			if (o.hmode) {
				if (o.minX != null)	o.minMouseX	= e.clientX - x + o.minX;
				if (o.maxX != null)	o.maxMouseX	= o.minMouseX + o.maxX - o.minX;
			} else {
				if (o.minX != null) o.maxMouseX = -o.minX + e.clientX + x;
				if (o.maxX != null) o.minMouseX = -o.maxX + e.clientX + x;
			}
	
			if (o.vmode) {
				if (o.minY != null)	o.minMouseY	= e.clientY - y + o.minY;
				if (o.maxY != null)	o.maxMouseY	= o.minMouseY + o.maxY - o.minY;
			} else {
				if (o.minY != null) o.maxMouseY = -o.minY + e.clientY + y;
				if (o.maxY != null) o.minMouseY = -o.maxY + e.clientY + y;
			}
	
			document.onmousemove = Drag.drag;
			document.onmouseup = Drag.end;
			return false;
			},
		drag : function(e) {
			e = Drag.fixE(e);
			var o = Drag.obj;
	
			var ey = e.clientY;
			var ex = e.clientX;
			var y = parseInt(o.vmode ? o.root.style.top  : o.root.style.bottom);
			var x = parseInt(o.hmode ? o.root.style.left : o.root.style.right );
			var nx, ny;
	
			if (o.minX != null) ex = o.hmode ? Math.max(ex, o.minMouseX) : Math.min(ex, o.maxMouseX);
			if (o.maxX != null) ex = o.hmode ? Math.min(ex, o.maxMouseX) : Math.max(ex, o.minMouseX);
			if (o.minY != null) ey = o.vmode ? Math.max(ey, o.minMouseY) : Math.min(ey, o.maxMouseY);
			if (o.maxY != null) ey = o.vmode ? Math.min(ey, o.maxMouseY) : Math.max(ey, o.minMouseY);
	
			nx = x + ((ex - o.lastMouseX) * (o.hmode ? 1 : -1));
			ny = y + ((ey - o.lastMouseY) * (o.vmode ? 1 : -1));
	
			if (o.xMapper) nx = o.xMapper(y);
			else if (o.yMapper)	ny = o.yMapper(x);
	
			Drag.obj.root.style[o.hmode ? "left" : "right"] = nx + "px";
			Drag.obj.root.style[o.vmode ? "top" : "bottom"] = ny + "px";
			Drag.obj.lastMouseX	= ex;
			Drag.obj.lastMouseY	= ey;
			Drag.obj.root.onDrag(nx, ny);
			return false;
			},
		end : function() {
			document.onmousemove = null;
			document.onmouseup   = null;
			Drag.obj.root.onDragEnd(parseInt(Drag.obj.root.style[Drag.obj.hmode ? "left" : "right"]), 
									parseInt(Drag.obj.root.style[Drag.obj.vmode ? "top" : "bottom"]));
			Drag.obj = null;
			},
		fixE : function(e) {
			if (typeof e == 'undefined') e = window.event;
			if (typeof e.layerX == 'undefined') e.layerX = e.offsetX;
			if (typeof e.layerY == 'undefined') e.layerY = e.offsetY;
			return e;
			}
		};	
	
	return { 		
		show:function(o){
			/* Parameters
			html - HTML content for window (string) - false
			iframe - URL for embedded iframe (string) - false
			url - path for AJAX call (string) - false
			post - post variable string, used in conjunction with url (string) - false
			image - image path (string) - false
			width - preset width (int) - false
			height - preset height (int) - false
			animate - toggle animation (bool) - true
			close - toggle close button (bool) - true
			openjs - generic function executed on open (string) - null
			closejs - generic function executed on close (string) - null
			autohide - number of seconds to wait until auto-hiding (int) - false
			boxid - ID of box div for style overriding purposes (string) - ''
			maskid - ID of mask div for style overriding purposes (string) - ''
			fixed - toggle fixed position vs static (bool) - true
			opacity - set the opacity of the mask from 0-100 (int) - 70
			mask - toggle mask display (bool) - true
			top - absolute pixels from top (int) - null
			left - absolute pixels from left (int) - null
			topsplit - 1/x where x is the denominator in the split from the top (int) - 2
			*/
		
			v={opacity:70,close:1,animate:1,fixed:1,mask:1,maskid:'',boxid:'',topsplit:2,url:0,post:0,height:0,width:0,html:0,iframe:0};
			for(s in o){v[s]=o[s]}
			if(!p){
				j=document.createElement('div'); j.className='tbox';
				p=document.createElement('div'); p.className='tinner';
				b=document.createElement('div'); b.className='tcontent';
				m=document.createElement('div'); m.className='tmask';
				g=document.createElement('div'); g.className='tclose'; g.v=0;
				
	      Drag.init(j, j, 0, null, 0);
	      j.style.cursor = "move";				
				
				document.body.appendChild(m); document.body.appendChild(j); j.appendChild(p); p.appendChild(b);
				m.onclick=g.onclick=sed_modal.hide; window.onresize=sed_modal.resize
			}else{
				j.style.display='none'; clearTimeout(p.ah); if(g.v){p.removeChild(g); g.v=0}
			}
			p.id=v.boxid; m.id=v.maskid; j.style.position=v.fixed?'fixed':'absolute';
			if(v.html&&!v.animate){
				p.style.backgroundImage='none'; b.innerHTML=v.html; b.style.display='';
				p.style.width=v.width?v.width+'px':'auto'; p.style.height=v.height?v.height+'px':'auto'
			}else{
				b.style.display='none'; 
				if(!v.animate&&v.width&&v.height){
					p.style.width=v.width+'px'; p.style.height=v.height+'px'
				}else{
					p.style.width=p.style.height='100px'
				}
			}
			if(v.mask){this.mask(); this.alpha(m,1,v.opacity)}else{this.alpha(j,1,100)}
			if(v.autohide){p.ah=setTimeout(sed_modal.hide,1000*v.autohide)}else{document.onkeyup=sed_modal.esc}
		},
		fill:function(c,u,k,a,w,h){
			if(u){
				if(v.image){
					var i=new Image(); i.onload=function(){w=w||i.width; h=h||i.height; sed_modal.psh(i,a,w,h)}; i.src=v.image
				}else if(v.iframe){
					this.psh('<iframe src="'+v.iframe+'" width="'+v.width+'" frameborder="0" height="'+v.height+'"></iframe>',a,w,h)
				}else{
					var x=window.XMLHttpRequest?new XMLHttpRequest():new ActiveXObject('Microsoft.XMLHTTP');
					x.onreadystatechange=function(){
						if(x.readyState==4&&x.status==200){p.style.backgroundImage=''; sed_modal.psh(x.responseText,a,w,h)}
					};
					if(k){
    	            	x.open('POST',c,true); x.setRequestHeader('Content-type','application/x-www-form-urlencoded'); x.send(k)
					}else{
       	         		x.open('GET',c,true); x.send(null)
					}
				}
			}else{
				this.psh(c,a,w,h)
			}
		},
		psh:function(c,a,w,h){
			if(typeof c=='object'){b.appendChild(c)}else{b.innerHTML=c}
			var x=p.style.width, y=p.style.height;
			if(!w||!h){
				p.style.width=w?w+'px':''; p.style.height=h?h+'px':''; b.style.display='';
				if(!h){h=parseInt(b.offsetHeight)}
				if(!w){w=parseInt(b.offsetWidth)}
				b.style.display='none'
			}
			p.style.width=x; p.style.height=y;
			this.size(w,h,a)
		},
		esc:function(e){e=e||window.event; if(e.keyCode==27){sed_modal.hide()}},
		hide:function(){sed_modal.alpha(j,-1,0,3); document.onkeypress=null; if(v.closejs){v.closejs()}},
		resize:function(){sed_modal.pos(); sed_modal.mask()},
		mask:function(){m.style.height=this.total(1)+'px'; m.style.width=this.total(0)+'px'},
		pos:function(){
			var t;
			if(typeof v.top!='undefined'){t=v.top}else{t=(this.height()/v.topsplit)-(j.offsetHeight/2); t=t<20?20:t}
			if(!v.fixed&&!v.top){t+=this.top()}
			j.style.top=t+'px'; 
			j.style.left=typeof v.left!='undefined'?v.left+'px':(this.width()/2)-(j.offsetWidth/2)+'px'
		},
		alpha:function(e,d,a){
			clearInterval(e.ai);
			if(d){e.style.opacity=0; e.style.filter='alpha(opacity=0)'; e.style.display='block'; sed_modal.pos()}
			e.ai=setInterval(function(){sed_modal.ta(e,a,d)},20)
		},
		ta:function(e,a,d){
			var o=Math.round(e.style.opacity*100);
			if(o==a){
				clearInterval(e.ai);
				if(d==-1){
					e.style.display='none';
					e==j?sed_modal.alpha(m,-1,0,2):b.innerHTML=p.style.backgroundImage=''
				}else{
					if(e==m){
						this.alpha(j,1,100)
					}else{
						j.style.filter='';
						sed_modal.fill(v.html||v.url,v.url||v.iframe||v.image,v.post,v.animate,v.width,v.height)
					}
				}
			}else{
				var n=a-Math.floor(Math.abs(a-o)*.5)*d;
				e.style.opacity=n/100; e.style.filter='alpha(opacity='+n+')'
			}
		},
		size:function(w,h,a){
			if(a){
				clearInterval(p.si); var wd=parseInt(p.style.width)>w?-1:1, hd=parseInt(p.style.height)>h?-1:1;
				p.si=setInterval(function(){sed_modal.ts(w,wd,h,hd)},20)
			}else{
				p.style.backgroundImage='none'; if(v.close){p.appendChild(g); g.v=1}
				p.style.width=w+'px'; p.style.height=h+'px'; b.style.display=''; this.pos();
				if(v.openjs){v.openjs()}
			}
		},
		ts:function(w,wd,h,hd){
			var cw=parseInt(p.style.width), ch=parseInt(p.style.height);
			if(cw==w&&ch==h){
				clearInterval(p.si); p.style.backgroundImage='none'; b.style.display='block'; if(v.close){p.appendChild(g); g.v=1}
				if(v.openjs){v.openjs()}
			}else{
				if(cw!=w){p.style.width=(w-Math.floor(Math.abs(w-cw)*.6)*wd)+'px'}
				if(ch!=h){p.style.height=(h-Math.floor(Math.abs(h-ch)*.6)*hd)+'px'}
				this.pos()
			}
		},
		top:function(){return document.documentElement.scrollTop||document.body.scrollTop},
		width:function(){return self.innerWidth||document.documentElement.clientWidth||document.body.clientWidth},
		height:function(){return self.innerHeight||document.documentElement.clientHeight||document.body.clientHeight},
		total:function(d){
			var b=document.body, e=document.documentElement;
			return d?Math.max(Math.max(b.scrollHeight,e.scrollHeight),Math.max(b.clientHeight,e.clientHeight)):
			Math.max(Math.max(b.scrollWidth,e.scrollWidth),Math.max(b.clientWidth,e.clientWidth))
		}
	}
}();

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