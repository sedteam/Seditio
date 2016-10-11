var sedjs = {
	/*= Popup
	-------------------------------------*/
	popup : function(code,w,h,modal)
		{ 
		if (!modal) { window.open(sedjs.get_basehref()+'plug.php?o='+code,'','toolbar=0,location=0,directories=0,menuBar=0,resizable=0,scrollbars=yes,width='+w+',height='+h+',left=32,top=16'); } 
		else { sedjs.modal.open('popup', 'iframe', sedjs.get_basehref()+'plug.php?o='+code, "Popup", 'width='+w+'px,height='+h+'px,resize=1,scrolling=1,center=1', 'load');  }
		},
		
	/*= PFS
	-------------------------------------*/
	pfs : function(id,c1,c2,modal)
		{	
		if (!modal) { window.open(sedjs.get_basehref()+'pfs.php?userid='+id+'&c1='+c1+'&c2='+c2,'PFS','status=1, toolbar=0,location=0,directories=0,menuBar=0,resizable=1,scrollbars=yes,width=754,height=512,left=32,top=16'); }
		else { sedjs.modal.open("pfs", "iframe", sedjs.get_basehref()+'pfs.php?userid='+id+'&c1='+c1+'&c2='+c2, "PFS", "width=754px,height=512px,resize=1,scrolling=1,center=1", "load");  }
		},
		
	/*= Help
	-------------------------------------*/
	help : function(rcode,c1,c2,modal)
		{
		if (!modal) { window.open(sedjs.get_basehref()+'plug.php?h='+rcode+'&c1='+c1+'&c2='+c2,'Help','toolbar=0,location=0,directories=0,menuBar=0,resizable=0,scrollbars=yes,width=480,height=512,left=32,top=16'); }
		else { sedjs.modal.open("help", "iframe", sedjs.get_basehref()+'plug.php?h='+rcode+'&c1='+c1+'&c2='+c2, "Help", "width=500px,height=520px,resize=1,scrolling=1,center=1", "load");  }
		},
		
	/*= Polls
	-------------------------------------*/
	polls : function(rcode,modal)
		{  
		if (!modal) { window.open(sedjs.get_basehref()+'polls.php?id='+rcode,'Polls','toolbar=0,location=0,directories=0,menuBar=0,resizable=0,scrollbars=yes,width=608,height=448,left=16,top=16'); }    
		else { sedjs.modal.open("polls", "iframe", sedjs.get_basehref()+'polls.php?id='+rcode, "Polls", "width=610px,height=450px,resize=1,scrolling=1,center=1", "load");  }
		},
		
	/*= Poll vote
	-------------------------------------*/
	pollvote : function(rcode,rvote,modal)
		{ 
		if (!modal) { window.open(sedjs.get_basehref()+'polls.php?a=send&id='+rcode+'&vote='+rvote,'Polls','toolbar=0,location=0,directories=0,menuBar=0,resizable=0,scrollbars=yes,width=608,height=448,left=16,top=16'); }
		else {sedjs.modal.open("pollvote", "iframe", sedjs.get_basehref()+'polls.php?a=send&id='+rcode+'&vote='+rvote, "Polls", "width=610px,height=450px,resize=1,scrolling=1,center=1", "load");  }
		},
		
	/*= Picture show 
	-------------------------------------*/						
	picture : function(url,sx,sy,modal)
		{
		  if (!modal) { 
				var ptop=(window.screen.height-200)/2;
			  var pleft=(window.screen.width-200)/2;
			  window.open(sedjs.get_basehref()+'pfs.php?m=view&v='+url,'Picture','toolbar=0,location=0,status=0, directories=0,menubar=0,resizable=1,scrollbars=yes,width='+sx+',height='+sy+',left='+pleft+',top='+ptop+'');
			} else {
			  var imglink = 'datas/users/'+url;
			  var randid = imglink.replace(/[^a-z0-9]/gi,'');
			  sedjs.modal.open('img-'+randid, 'image', imglink, 'Picture', 'resize=0, scrolling=0, center=1', 'load');
			}
		},
	  
	/*= Redirect
	-------------------------------------*/						
	redirect : function(url)
		{ 
		location.href = url.options[url.selectedIndex].value; 
		},

	/*= Confirm action
	-------------------------------------*/	
	confirmact : function(mess) 
		{
		if (confirm(mess)) { return true; } else { return false; }
		},
		
	/*= Get base href
	-------------------------------------*/	
	get_basehref : function()
		{
		 var loc = "";
		 var b = document.getElementsByTagName('base');
		 if (b && b[0] && b[0].href) {
			   if (b[0].href.substr(b[0].href.length-1) == '/' && loc.charAt(0) == '/')
				 loc = loc.substr(1);
			   loc = b[0].href + loc;
				}
		return loc;
		},
		
	/*= Toggle Block
	-------------------------------------*/				
	toggleblock : function(id)
		{
		var bl = document.getElementById(id);
		if(bl.style.display == 'none')
			{ bl.style.display = ''; }
		else
			{ bl.style.display = 'none'; }
		},
		
	/*= Seditio Tabs
	based on Nanotabs - www.sunsean.com
	-------------------------------------*/	
	sedtabs : function(settings) { 			
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
	  stabs = function(s) {
		var i, s=s||{}, o="cesdf".split(''); 
		for(i in o) s[o[i]]=s[o[i]]||stabs.settings[o[i]];
		var c=w(s.c), f=function(){
		  var t=this,o=t[0],a=[t[1],t[2],t[3],t[4]];
		  if( !s.f || s.f.apply(o,a)!==false ) tab.apply(o,a);
		  return false;
		}
		for(i=0; i<c.length; i++) {
		  var x=0, e=[], a=[], h=[], t=c[i].getElementsByTagName("a");
		  for(var j=0; j<t.length; j++)
			if(t[j].href.match(/#tab/)) {
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
	  
	  stabs.settings = { c:"sedtabs", e:"click", s:"selected", d:0, f:false };
	  stabs(settings);		
		
	},

	/*= Get Attribute rel on links & start show thumb in modal window
	-------------------------------------*/
	getrel : function(rel) {
		var pagelinks = document.getElementsByTagName("a");
		for (var i=0; i<pagelinks.length; i++) { 
		  if (pagelinks[i].getAttribute("rel") && pagelinks[i].getAttribute("rel")==rel) { 
			pagelinks[i].onclick=function() {
			  var imglink = this.getAttribute("href");
			  var imgtitle = "Picture";
			  if (this.getAttribute("title")) {
				imgtitle = this.getAttribute("title");
			  }          
			  var randid = imglink.replace(/[^a-z0-9]/gi,'');
			  sedjs.modal.open('img-'+randid, 'image', imglink, imgtitle, 'resize=0,scrolling=0,center=1', 'load');
			  return false;
			}
		  }
		}		
	},

	/*= Seditio Ajax functions
	-------------------------------------*/
	ajax : {
			getHTTPObject : function() {
				var http = false;
				if(typeof ActiveXObject != 'undefined') {
					try {http = new ActiveXObject("Msxml2.XMLHTTP");}
					catch (e) {
						try {http = new ActiveXObject("Microsoft.XMLHTTP");}
						catch (E) {http = false;}
					}
				} else if (window.XMLHttpRequest) {
					try {http = new XMLHttpRequest();}
					catch (e) {http = false;}
				}
				return http;
			},		
			load : function (url,callback,format,method,opt) {
				var http = this.init();
				if(!http||!url) return;
				if (http.overrideMimeType) http.overrideMimeType('text/xml');	
				if(!method) method = "GET";
				if(!format) format = "text";
				if(!opt) opt = {};
				format = format.toLowerCase();
				method = method.toUpperCase();
				var now = "uid=" + new Date().getTime();
				url += (url.indexOf("?")+1) ? "&" : "?";
				url += now;	
				var parameters = null;	
				if(method=="POST") {				
					var postparams = '';			
					if(opt.formid) postparams = '&' + this.serialize(opt.formid);			
					var parts = url.split("\?");
					//url = parts[0];
					parameters = parts[1] + postparams; 
				}			
				http.open(method, url, true);	
				if(method=="POST") { http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");  }	
				var ths = this;
				if(opt.handler) { 
					http.onreadystatechange = function() { opt.handler(http); };
				} else {
					http.onreadystatechange = function () {
						if (http.readyState == 4) {
							if(http.status == 200) {
								var result = "";
								if(http.responseText) result = http.responseText;
								if(format.charAt(0) == "j") {
									result = result.replace(/[\n\r]/g,"");
									result = eval('('+result+')');
		
								} else if(format.charAt(0) == "x") {
									result = http.responseXML;
								}
								if(callback) callback(result);
							} else {						
								if(opt.loading) document.getElementById(opt.loadingid).removeChild(opt.loading);
											
								if(opt.onError) {opt.onError(http.status); }
							}
						}
					}
				}
				http.send(parameters);
			},
			bind : function(user_options) {
				var opt = {
					'url':'', 
					'onSuccess':false,
					'onError':false,
					'format':"text",
					'method':"GET",
					'update':"",
					'loading':"",
					'formid':""
				}
				for(var key in opt) {
					if(user_options[key]) {
						opt[key] = user_options[key];
					}
				}			
				if(!opt.url) return;
				var div = false;
				if(opt.loading) {
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
					if(div) document.getElementById(opt.loadingid).removeChild(div);
					
						
					if(opt.update && data != "") {document.getElementById(opt.update).innerHTML = data;}
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
		},
		
	/*= Seditio Modal Windows functions
	based on DHTML Windows - www.dynamicdrive.com
	-------------------------------------*/			
	modal : {			
		imagefiles : ['/system/img/vars/min.gif', '/system/img/vars/close.gif', '/system/img/vars/restore.gif', '/system/img/vars/resize.gif'],
		maxheightimage: 600,
		maxwidthimage : 800,
		minimizeorder : 0,
		zIndexvalue : 100,
		tobjects : [], 
		lastactivet : {}, 
		
		init : function(t){
			var domwindow = document.createElement("div");
			domwindow.id = t;
			domwindow.className = "sed_modal";
			var domwindowdata = '';
			domwindowdata = '<div class="modal-handle">';
			domwindowdata += 'Modal Window<div class="modal-controls"><img src="'+this.imagefiles[0]+'" title="Minimize" /><img src="'+this.imagefiles[1]+'" title="Close" /></div>';
			domwindowdata += '</div>';
			domwindowdata += '<div class="modal-contentarea" id="area-'+t+'"></div>';
			domwindowdata += '<div class="modal-statusarea"><div class="modal-resizearea" style="background: transparent url('+this.imagefiles[3]+') top right no-repeat;">&nbsp;</div></div>';
			domwindowdata += '</div>';
			domwindow.innerHTML = domwindowdata;
		  document.body.appendChild(domwindow);
			//this.zIndexvalue=(this.zIndexvalue)? this.zIndexvalue+1 : 100
			var t = document.getElementById(t);
			var divs = t.getElementsByTagName("div");
			for (var i = 0; i < divs.length; i++){
				if (/modal-/.test(divs[i].className)) {						  
					t[divs[i].className.replace(/modal-/, "")]=divs[i];						
					}
			}
			//t.style.zIndex=this.zIndexvalue //set z-index of this dhtml window
			t.handle._parent = t;
			t.resizearea._parent = t;
			t.controls._parent = t;
			t.onclose = function() { return true; } 
			t.onmousedown = function() { sedjs.modal.setfocus(this); }
			t.handle.onmousedown = sedjs.modal.setupdrag;
			t.resizearea.onmousedown = sedjs.modal.setupdrag; 
			t.controls.onclick = sedjs.modal.enablecontrols;
			t.show = function() { sedjs.modal.show(this); }
			t.hide = function() { sedjs.modal.hide(this); } 
			t.close = function(){ sedjs.modal.close(this); }
			t.setSize = function(w, h) { sedjs.modal.setSize(this, w, h); } 
			t.moveTo = function(x, y) { sedjs.modal.moveTo(this, x, y); } 
			t.isResize = function(bol) { sedjs.modal.isResize(this, bol); } 
			t.isResize = function(bol) { sedjs.modal.isResize(this, bol); } 
			t.isScrolling = function(bol) { sedjs.modal.isScrolling(this, bol); } 
			t.load = function(contenttype, contentsource, title) { sedjs.modal.load(this, contenttype, contentsource, title); } 
			this.tobjects[this.tobjects.length] = t;
			return t; 
		},
		
		open : function(t, contenttype, contentsource, title, attr, recalonload){
			var d = sedjs.modal; 
			function getValue(Name){
				var config = new RegExp(Name+"=([^,]+)", "i"); 
				return (config.test(attr))? parseInt(RegExp.$1) : 0;
			}
			if (document.getElementById(t) == null) {	t = this.init(t); }
			else { t = document.getElementById(t); }
			this.setfocus(t);
			if (contenttype != 'image') { 
					t.setSize(getValue(("width")), (getValue("height")));
					var xpos = getValue("center") ? "middle" : getValue("left");
					var ypos = getValue("center") ? "middle" : getValue("top");
					//t.moveTo(xpos, ypos) //Position window
					if (typeof recalonload != "undefined" && recalonload == "recal" && this.scroll_top == 0){ 
						if (window.attachEvent && !window.opera) 
							this.addEvent(window, function(){setTimeout(function(){t.moveTo(xpos, ypos)}, 400)}, "load");
						else
							this.addEvent(window, function(){t.moveTo(xpos, ypos)}, "load");
					}
					t.isResize(getValue("resize"));
					t.isScrolling(getValue("scrolling"));
					t.style.visibility = "visible";
					t.style.display = "block";
					t.contentarea.style.display = "block";
					t.contentarea.innerHTML = ""; //?? clear content
					t.moveTo(xpos, ypos);
			}
			//sedjs.modal.hide(t);
			t.load(contenttype, contentsource, title);
			if (t.state == "minimized" && t.controls.firstChild.title == "Restore"){
				t.controls.firstChild.setAttribute("src", sedjs.modal.imagefiles[0]);
				t.controls.firstChild.setAttribute("title", "Minimize");
				t.state = "fullview";
			}
			//sedjs.modal.show(t);
			return t;
		},
		
		//set window size (min is 150px wide by 100px tall)
		setSize : function(t, w, h){ 
			t.style.width=Math.max(parseInt(w), 150)+"px";
			t.contentarea.style.height = Math.max(parseInt(h), 100) + "px";
		},
		
		//move window. Position includes current viewpoint of document
		moveTo : function(t, x, y){ 
			this.getviewpoint();
			t.style.left = (x == "middle") ? this.scroll_left + (this.docwidth - t.offsetWidth)/2 + "px" : this.scroll_left+parseInt(x) + "px";
			t.style.top =( y == "middle") ? this.scroll_top + (this.docheight - t.offsetHeight)/2 + "px" : this.scroll_top+parseInt(y) + "px";
		},
		
		//show or hide resize inteface (part of the status bar)
		isResize : function(t, bol){
			t.statusarea.style.display = (bol) ? "block" : "none";
			t.resizeBool = (bol) ? 1 : 0;
		},
		
		//scale size image
		scaleSize : function(maxW, maxH, currW, currH){
			var ratio = currH / currW;
			if(currW >= maxW && ratio <= 1){
				currW = maxW;
				currH = currW * ratio;
			} else if(currH >= maxH){
				currH = maxH;
				currW = currH / ratio;
			}
			return [currW, currH];
		},
		
		//set whether loaded content contains scrollbars
		isScrolling : function(t, bol){ 
			t.contentarea.style.overflow=(bol)? "auto" : "hidden";
		},
		
		//loads content into window plus set its title (4 content types: "inline", "iframe", "image" or "ajax")
		load : function(t, contenttype, contentsource, title){
			if (t.isClosed){
				alert("Modal Window has been closed, so no window to load contents into. Open/Create the window again.");
				return
			}
			var contenttype = contenttype.toLowerCase();
			if (typeof title != "undefined")
				t.handle.firstChild.nodeValue = title;
			if (contenttype == "inline")
				t.contentarea.innerHTML = contentsource;	  
		  else if (contenttype == "image") {
				
				/* Loading Indicator */
				loader = document.createElement("div");
				loader.setAttribute("style","position:absolute;");
				loader.setAttribute("class","loading-indicator");
				document.body.appendChild(loader);
				sedjs.modal.moveTo(loader, 'middle', 'middle');					
				t.loading=loader;		  
	      /**/
		  
			  var i = new Image();  	     
			  i.onload = function(isa) {				
					var max_h = (window.innerHeight > 150) ?  window.innerHeight - 100 : sedjs.modal.maxheightimage;
					var max_w = (window.innerWidth > 150) ?  window.innerWidth - 100 : sedjs.modal.maxwidthimage;					
					if (i.height > max_h) { 			
							var newSize = sedjs.modal.scaleSize(max_w, max_h, i.width, i.height);
							i.width = newSize[0];
							i.height = newSize[1];
					}	         			
					t.setSize(i.width + 4, i.height);
					t.moveTo('middle', 'middle');
					if(loader) document.body.removeChild(loader);	
				};
				i.onerror = function(asa) {
					if(loader) document.body.removeChild(loader);	
				};

				i.src = contentsource;
				t.contentarea.appendChild(i);
				t.statusarea.style.display = "none";
				t.contentarea.style.overflow = "hidden";
				t.style.visibility = "visible";
				t.style.display = "block";
				t.contentarea.style.display = "block";
		  }
		  else if (contenttype == "div"){
				var inlinedivref = document.getElementById(contentsource);
				t.contentarea.innerHTML = (inlinedivref.defaultHTML || inlinedivref.innerHTML); 
				if (!inlinedivref.defaultHTML) { inlinedivref.defaultHTML = inlinedivref.innerHTML; }
				inlinedivref.innerHTML = "";
				inlinedivref.style.display = "none";
			}
			else if (contenttype == "iframe"){
				t.contentarea.style.overflow = "hidden";				
				if (!t.contentarea.firstChild || t.contentarea.firstChild.tagName != "IFRAME") {				
					t.contentarea.innerHTML = '<iframe src="" style="margin:0; padding:0; width:100%; height: 100%" name="_iframe-'+t.id+'" id="id_iframe-'+t.id+'"></iframe>';
				}
				window.frames["_iframe-"+t.id].location.replace(contentsource);
			}
			else if (contenttype == "ajax"){
					sedjs.ajax.bind({url : contentsource, method : 'GET', update : 'area-'+t.id, loading : t.id});
			}
			t.contentarea.datatype = contenttype;
		},
		
		setupdrag : function(e){
			var d = sedjs.modal;
			var t = this._parent;
			d.etarget = this;
			var e = window.event || e;
			d.initmousex = e.clientX;
			d.initmousey = e.clientY;
			d.initx = parseInt(t.offsetLeft);
			d.inity = parseInt(t.offsetTop);
			d.width = parseInt(t.offsetWidth);
			d.contentheight = parseInt(t.contentarea.offsetHeight);
			if (t.contentarea.datatype == "iframe"){ 
				t.style.backgroundColor = "#F8F8F8"; 
				t.contentarea.style.visibility = "hidden";
			}
			document.onmousemove = d.getdistance;
			document.onmouseup = function(){
				if (t.contentarea.datatype == "iframe"){ 
					t.contentarea.style.backgroundColor = "white";
					t.contentarea.style.visibility = "visible";
				}
				d.stop();
			}
			return false;
		},
		
		getdistance : function(e){
			var d = sedjs.modal;
			var etarget = d.etarget;
			var e = window.event || e;
			d.distancex = e.clientX-d.initmousex;
			d.distancey = e.clientY-d.initmousey;
			if (etarget.className == "modal-handle")
				d.move(etarget._parent, e);
			else if (etarget.className == "modal-resizearea")
				d.resize(etarget._parent, e);
			return false;
		},
		
		//get window viewpoint numbers
		getviewpoint : function(){ 
			var ie = document.all && !window.opera;
			var domclientWidth = document.documentElement && parseInt(document.documentElement.clientWidth) || 100000;
			this.standardbody = (document.compatMode == "CSS1Compat") ? document.documentElement : document.body;
			this.scroll_top = (ie) ? this.standardbody.scrollTop : window.pageYOffset;
			this.scroll_left = (ie) ? this.standardbody.scrollLeft : window.pageXOffset;
			this.docwidth = (ie) ? this.standardbody.clientWidth : (/Safari/i.test(navigator.userAgent)) ? window.innerWidth : Math.min(domclientWidth, window.innerWidth-16);
			this.docheight = (ie) ? this.standardbody.clientHeight: window.innerHeight;
		},
		
		//remember certain attributes of the window when it's minimized or closed, such as dimensions, position on page
		rememberattrs : function(t){ 
			this.getviewpoint(); 
			t.lastx = parseInt((t.style.left || t.offsetLeft)) - sedjs.modal.scroll_left;
			t.lasty = parseInt((t.style.top || t.offsetTop)) - sedjs.modal.scroll_top;
			t.lastwidth = parseInt(t.style.width); 
		},
		
		move : function(t, e){
			t.style.left = sedjs.modal.distancex + sedjs.modal.initx + "px";
			t.style.top = sedjs.modal.distancey + sedjs.modal.inity + "px";
		},
		
		resize : function(t, e){
			t.style.width = Math.max(sedjs.modal.width + sedjs.modal.distancex, 150) + "px";
			t.contentarea.style.height = Math.max(sedjs.modal.contentheight + sedjs.modal.distancey, 100) + "px";
		},
		
		enablecontrols : function(e){
			var d = sedjs.modal;
			var sourceobj = window.event ? window.event.srcElement : e.target;
			if (/Minimize/i.test(sourceobj.getAttribute("title")))
				d.minimize(sourceobj, this._parent);
			else if (/Restore/i.test(sourceobj.getAttribute("title")))
				d.restore(sourceobj, this._parent);
			else if (/Close/i.test(sourceobj.getAttribute("title")))
				d.close(this._parent);
			return false;
		},
		
		minimize : function(button, t){
			sedjs.modal.rememberattrs(t);
			button.setAttribute("src", sedjs.modal.imagefiles[2]);
			button.setAttribute("title", "Restore");
			t.state = "minimized";
			t.contentarea.style.display = "none";
			t.statusarea.style.display = "none";
			if (typeof t.minimizeorder == "undefined"){
				sedjs.modal.minimizeorder++;
				t.minimizeorder = sedjs.modal.minimizeorder;
			}
			t.style.left = "10px";
			t.style.width = "200px";
			var windowspacing = t.minimizeorder*10;
			t.style.top = sedjs.modal.scroll_top + sedjs.modal.docheight - (t.handle.offsetHeight*t.minimizeorder) - windowspacing + "px";
		},
		
		restore : function(button, t){
			sedjs.modal.getviewpoint();
			button.setAttribute("src", sedjs.modal.imagefiles[0]);
			button.setAttribute("title", "Minimize");
			t.state = "fullview";
			t.style.display = "block";
			t.contentarea.style.display = "block";
			if (t.resizeBool)
				t.statusarea.style.display = "block";
			t.style.left = parseInt(t.lastx) + sedjs.modal.scroll_left + "px";
			t.style.top = parseInt(t.lasty) + sedjs.modal.scroll_top + "px";
			t.style.width = parseInt(t.lastwidth) + "px";
		},
				
		close : function(t){
			try{
				var closewinbol = t.onclose();
			}
			catch(err){ 
				var closewinbol = true;
		 }
			finally{ 
				if (typeof closewinbol == "undefined"){
					alert("An error has occured somwhere inside your \"onclose\" event handler");
					var closewinbol = true;
				}
			}
			if (closewinbol){ 
				if (t.state!="minimized") 
					sedjs.modal.rememberattrs(t); 
				if (window.frames["_iframe-"+t.id]) 
					window.frames["_iframe-"+t.id].location.replace("about:blank");
				else
					t.contentarea.innerHTML = "";
				t.style.display = "none";
				t.isClosed = true;
				document.body.removeChild(t); // ???
			}
			return closewinbol;
		},
		
		//Sets the opacity of targetobject based on the passed in value setting (0 to 1 and in between)	
		setopacity : function(targetobject, value){ 
			if (!targetobject)
				return;
			if (targetobject.filters && targetobject.filters[0]){ 
				if (typeof targetobject.filters[0].opacity == "number") 
					targetobject.filters[0].opacity = value*100;
				else
					targetobject.style.filter = "alpha(opacity="+value*100+")";
				}
			else if (typeof targetobject.style.MozOpacity != "undefined") 
				targetobject.style.MozOpacity = value;
			else if (typeof targetobject.style.opacity != "undefined") 
				targetobject.style.opacity = value;
		},
		
		//Sets focus to the currently active window
		setfocus : function(t){ 
			this.zIndexvalue++;
			t.style.zIndex = this.zIndexvalue;
			t.isClosed = false;
			this.setopacity(this.lastactivet.handle, 0.5);
			this.setopacity(t.handle, 1);
			this.lastactivet = t;
		},
			
		show : function(t){
			if (t.isClosed){
				alert("Modal Window has been closed, so nothing to show. Open/Create the window again.");
				return;
			}
			if (t.lastx)
				sedjs.modal.restore(t.controls.firstChild, t);
			else
				t.style.display = "block";
			this.setfocus(t);
			t.state = "fullview";
		},
		
		hide : function(t){
			t.style.display = "none";
		},
		
		stop : function(){
			sedjs.modal.etarget = null;
			document.onmousemove = null;
			document.onmouseup = null;
		},
		
		//assign a function to execute to an event handler (ie: onunload)
		addEvent : function(target, functionref, tasktype){ 
			var tasktype = (window.addEventListener) ? tasktype : "on" + tasktype;
			if (target.addEventListener)
				target.addEventListener(tasktype, functionref, false);
			else if (target.attachEvent)
				target.attachEvent(tasktype, functionref);
		},
		
		cleanup : function(){
			for (var i = 0; i < sedjs.modal.tobjects.length; i++){
				sedjs.modal.tobjects[i].handle._parent = sedjs.modal.tobjects[i].resizearea._parent = sedjs.modal.tobjects[i].controls._parent = null;
			}
			window.onload = null;
		}	
	},

	setActiveStyleSheet : function(title){  
	    var i, a, main;  
	    for(i=0; (a = document.getElementsByTagName("link")[i]); i++) {    
	    if(a.getAttribute("rel").indexOf("style") != -1 && a.getAttribute("title")) 
	        {      
	            a.disabled = true;      
	            if(a.getAttribute("title") == title) a.disabled = false;   
	        }  
	    }
	},
	 
	getActiveStyleSheet : function(){
	    var i, a;  
	    for(i=0; (a = document.getElementsByTagName("link")[i]); i++) 
	    {    
	        if(a.getAttribute("rel").indexOf("style") != -1 && a.getAttribute("title") && !a.disabled) 
	        return a.getAttribute("title");  
	    } 
	    return null;
	},

	getPreferredStyleSheet : function() 
	{  
	    var i, a;  
	    for(i=0; (a = document.getElementsByTagName("link")[i]); i++) 
	    {    
	        if(a.getAttribute("rel").indexOf("style") != -1 && a.getAttribute("rel").indexOf("alt") == -1 && a.getAttribute("title")) 
	        return a.getAttribute("title");  
	    }  
	    return null;
	},

	createCookie : function(name,value,days) 
	{  
	    if (days) 
	    {    
		    var date = new Date();    
		    date.setTime(date.getTime()+(days*24*60*60*1000));    
		    var expires = "; expires="+date.toGMTString();  
	    }  
	    else expires = "";  
	    document.cookie = name+"="+value+expires+"; path=/";
	},

	readCookie : function(name) 
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
	},

	antispam : function()
	{
		if (document.getElementById('anti1'))
		  { 
		    document.getElementById('anti1').value += document.getElementById('anti2').value;	
	  	  }
	},

	genSEF : function(from, to, allow_slashes )
	{
	   var str = from.value.toLowerCase();
	   var slash = "";
	   if (allow_slashes) slash = "\\/";
	   
	   var LettersFrom = "абвгдезиклмнопрстуфыэйхё";
	   var LettersTo   = "abvgdeziklmnoprstufyejxe";
	   var Consonant = "бвгджзйклмнпрстфхцчшщ";
	   var Vowel = "аеёиоуыэюя";
	   var BiLetters = { 
		 "ж" : "zh", "ц" : "ts",  "ч" : "ch",
		 "ш" : "sh", "щ" : "sch", "ю" : "ju", "я" : "ja"
		               };

	   str = str.replace( /[_\s\.,?!\[\](){}]+/g, "_");
	   str = str.replace( /-{2,}/g, "--");
	   str = str.replace( /_\-+_/g, "--");

	   str = str.toLowerCase();

	   //here we replace ъ/ь
	   str = str.replace(
		  new RegExp( "(ь|ъ)(["+Vowel+"])", "g" ), "j$2");
	   str = str.replace( /(ь|ъ)/g, "");

	   //transliterating
	   var _str = "";
	   for( var x=0; x<str.length; x++)
		if ((index = LettersFrom.indexOf(str.charAt(x))) > -1)
		 _str+=LettersTo.charAt(index);
		else
		 _str+=str.charAt(x);
	   str = _str;

	   var _str = "";
	   for( var x=0; x<str.length; x++)
		if (BiLetters[str.charAt(x)])
		 _str+=BiLetters[str.charAt(x)];
		else
		 _str+=str.charAt(x);
	   str = _str;

	   str = str.replace( /j{2,}/g, "j");

	   str = str.replace( new RegExp( "[^"+slash+"0-9a-z_\\-]+", "g"), "");

	   to.value = str;
	}
}

function addLoadEvent(funct) {
  var oldonload = window.onload;
  if (typeof window.onload != 'function') {
    window.onload = funct;
  } else {
    window.onload = function() {
      oldonload();
      funct();
    }
  }
}

onloadfunct = function(){ 
	sedjs.sedtabs();
//	sedjs.sedtabs({c:"sedtabs2", e:"click", s:"selected", d:0, f:false });  //Example other tab conteiner
	sedjs.getrel("sedthumb");
	var cookie = sedjs.readCookie("style");  
	var title = cookie ? cookie : sedjs.getPreferredStyleSheet();  
	sedjs.setActiveStyleSheet(title);	
};

addLoadEvent(onloadfunct);

window.onunload = function() {  
	var title = sedjs.getActiveStyleSheet();  
	sedjs.createCookie("style", title, 365);
	//sedjs.modal.cleanup();
};
