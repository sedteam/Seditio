function popup(code,w,h,modal)
	{ 
		if (!modal) { window.open(get_basehref()+'plug.php?o='+code,'','toolbar=0,location=0,directories=0,menuBar=0,resizable=0,scrollbars=yes,width='+w+',height='+h+',left=32,top=16'); } 
    else { sed_modal.open('popup', 'iframe', get_basehref()+'plug.php?o='+code, "Popup", 'width='+w+'px,height='+h+'px,resize=1,scrolling=1,center=1', 'recal');  }
	}

function pfs(id,c1,c2,modal)
	{	
		if (!modal) { window.open(get_basehref()+'pfs.php?userid='+id+'&c1='+c1+'&c2='+c2,'PFS','status=1, toolbar=0,location=0,directories=0,menuBar=0,resizable=1,scrollbars=yes,width=754,height=512,left=32,top=16'); }
    else { sed_modal.open("pfs", "iframe", get_basehref()+'pfs.php?userid='+id+'&c1='+c1+'&c2='+c2, "PFS", "width=754px,height=512px,resize=1,scrolling=1,center=1", "recal");  }
	}

function help(rcode,c1,c2,modal)
	{
		if (!modal) { window.open(get_basehref()+'plug.php?h='+rcode+'&c1='+c1+'&c2='+c2,'Help','toolbar=0,location=0,directories=0,menuBar=0,resizable=0,scrollbars=yes,width=480,height=512,left=32,top=16'); }
		else { sed_modal.open("help", "iframe", get_basehref()+'plug.php?h='+rcode+'&c1='+c1+'&c2='+c2, "Help", "width=500px,height=520px,resize=1,scrolling=1,center=1", "recal");  }
	}

function polls(rcode,modal)
	{  
		if (!modal) { window.open(get_basehref()+'polls.php?id='+rcode,'Polls','toolbar=0,location=0,directories=0,menuBar=0,resizable=0,scrollbars=yes,width=608,height=448,left=16,top=16'); }    
    else { sed_modal.open("polls", "iframe", get_basehref()+'polls.php?id='+rcode, "Polls", "width=610px,height=450px,resize=1,scrolling=1,center=1", "recal");  }
}

function pollvote(rcode,rvote,modal)
	{ 
		if (!modal) { window.open(get_basehref()+'polls.php?a=send&id='+rcode+'&vote='+rvote,'Polls','toolbar=0,location=0,directories=0,menuBar=0,resizable=0,scrollbars=yes,width=608,height=448,left=16,top=16'); }
    else { sed_modal.open("pollvote", "iframe", get_basehref()+'polls.php?a=send&id='+rcode+'&vote='+rvote, "Polls", "width=610px,height=450px,resize=1,scrolling=1,center=1", "recal");  }
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
 
var sed_modal = {
imagefiles:['/system/img/vars/min.gif', '/system/img/vars/close.gif', '/system/img/vars/restore.gif', '/system/img/vars//resize.gif'], //Path to 4 images used by script, in that order

ajaxbustcache: true, //Bust caching when fetching a file via Ajax?
ajaxloadinghtml: '<b>Loading Page. Please wait...</b>', //HTML to show while window fetches Ajax Content?
maxheightimage: 600,
maxwidthimage:800,
minimizeorder: 0,
zIndexvalue:100,
tobjects: [], //object to contain references to dhtml window divs, for cleanup purposes
lastactivet: {}, //reference to last active DHTML window

init:function(t){
	var domwindow=document.createElement("div") //create dhtml window div
	domwindow.id=t
	domwindow.className="sed_modal"
	var domwindowdata=''
	domwindowdata='<div class="modal-handle">'
	domwindowdata+='SedModal<div class="modal-controls"><img src="'+this.imagefiles[0]+'" title="Minimize" /><img src="'+this.imagefiles[1]+'" title="Close" /></div>'
	domwindowdata+='</div>'
	domwindowdata+='<div class="modal-contentarea"></div>'
	domwindowdata+='<div class="modal-statusarea"><div class="modal-resizearea" style="background: transparent url('+this.imagefiles[3]+') top right no-repeat;">&nbsp;</div></div>'
	domwindowdata+='</div>'
	domwindow.innerHTML=domwindowdata
  document.body.appendChild(domwindow)
	//this.zIndexvalue=(this.zIndexvalue)? this.zIndexvalue+1 : 100 //z-index value for DHTML window: starts at 0, increments whenever a window has focus
	var t=document.getElementById(t)
	var divs=t.getElementsByTagName("div")
	for (var i=0; i<divs.length; i++){ //go through divs inside dhtml window and extract all those with class="modal-" prefix
		if (/modal-/.test(divs[i].className))
			t[divs[i].className.replace(/modal-/, "")]=divs[i] //take out the "modal-" prefix for shorter access by name
	}
	//t.style.zIndex=this.zIndexvalue //set z-index of this dhtml window
	t.handle._parent=t //store back reference to dhtml window
	t.resizearea._parent=t //same
	t.controls._parent=t //same
	t.onclose=function(){return true} //custom event handler "onclose"
	t.onmousedown=function(){sed_modal.setfocus(this)} //Increase z-index of window when focus is on it
	t.handle.onmousedown=sed_modal.setupdrag //set up drag behavior when mouse down on handle div
	t.resizearea.onmousedown=sed_modal.setupdrag //set up drag behavior when mouse down on resize div
	t.controls.onclick=sed_modal.enablecontrols
	t.show=function(){sed_modal.show(this)} //public function for showing dhtml window
	t.hide=function(){sed_modal.hide(this)} //public function for hiding dhtml window
	t.close=function(){sed_modal.close(this)} //public function for closing dhtml window (also empties DHTML window content)
	t.setSize=function(w, h){sed_modal.setSize(this, w, h)} //public function for setting window dimensions
	t.moveTo=function(x, y){sed_modal.moveTo(this, x, y)} //public function for moving dhtml window (relative to viewpoint)
	t.isResize=function(bol){sed_modal.isResize(this, bol)} //public function for specifying if window is resizable
	t.isResize=function(bol){sed_modal.isResize(this, bol)} //public function for specifying if window is resizable
	t.isScrolling=function(bol){sed_modal.isScrolling(this, bol)} //public function for specifying if window content contains scrollbars
	t.load=function(contenttype, contentsource, title){sed_modal.load(this, contenttype, contentsource, title)} //public function for loading content into window
	this.tobjects[this.tobjects.length]=t
	return t //return reference to dhtml window div
},

open:function(t, contenttype, contentsource, title, attr, recalonload){
	var d=sed_modal //reference dhtml window object
	function getValue(Name){
		var config=new RegExp(Name+"=([^,]+)", "i") //get name/value config pair (ie: width=400px,)
		return (config.test(attr))? parseInt(RegExp.$1) : 0 //return value portion (int), or 0 (false) if none found
	}
	if (document.getElementById(t)==null) //if window doesn't exist yet, create it
		t=this.init(t) //return reference to dhtml window div
	else
		t=document.getElementById(t)
	this.setfocus(t)
	t.setSize(getValue(("width")), (getValue("height"))) //Set dimensions of window
	var xpos=getValue("center")? "middle" : getValue("left") //Get x coord of window
	var ypos=getValue("center")? "middle" : getValue("top") //Get y coord of window
	//t.moveTo(xpos, ypos) //Position window
	if (typeof recalonload!="undefined" && recalonload=="recal" && this.scroll_top==0){ //reposition window when page fully loads with updated window viewpoints?
		if (window.attachEvent && !window.opera) //In IE, add another 400 milisecs on page load (viewpoint properties may return 0 b4 then)
			this.addEvent(window, function(){setTimeout(function(){t.moveTo(xpos, ypos)}, 400)}, "load")
		else
			this.addEvent(window, function(){t.moveTo(xpos, ypos)}, "load")
	}
	t.isResize(getValue("resize")) //Set whether window is resizable
	t.isScrolling(getValue("scrolling")) //Set whether window should contain scrollbars
	t.style.visibility="visible"
	t.style.display="block"
	t.contentarea.style.display="block"
	t.moveTo(xpos, ypos) //Position window
	t.load(contenttype, contentsource, title)
	if (t.state=="minimized" && t.controls.firstChild.title=="Restore"){ //If window exists and is currently minimized?
		t.controls.firstChild.setAttribute("src", sed_modal.imagefiles[0]) //Change "restore" icon within window interface to "minimize" icon
		t.controls.firstChild.setAttribute("title", "Minimize")
		t.state="fullview" //indicate the state of the window as being "fullview"
	}
	return t
},

setSize:function(t, w, h){ //set window size (min is 150px wide by 100px tall)
	t.style.width=Math.max(parseInt(w), 150)+"px"
	t.contentarea.style.height=Math.max(parseInt(h), 100)+"px"
},

moveTo:function(t, x, y){ //move window. Position includes current viewpoint of document
	this.getviewpoint() //Get current viewpoint numbers
	t.style.left=(x=="middle")? this.scroll_left+(this.docwidth-t.offsetWidth)/2+"px" : this.scroll_left+parseInt(x)+"px"
	t.style.top=(y=="middle")? this.scroll_top+(this.docheight-t.offsetHeight)/2+"px" : this.scroll_top+parseInt(y)+"px"
},

isResize:function(t, bol){ //show or hide resize inteface (part of the status bar)
	t.statusarea.style.display=(bol)? "block" : "none"
	t.resizeBool=(bol)? 1 : 0
},

scaleSize:function(maxW, maxH, currW, currH){
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

isScrolling:function(t, bol){ //set whether loaded content contains scrollbars
	t.contentarea.style.overflow=(bol)? "auto" : "hidden"
},

load:function(t, contenttype, contentsource, title){ //loads content into window plus set its title (3 content types: "inline", "iframe", or "ajax")
	if (t.isClosed){
		alert("DHTML Window has been closed, so no window to load contents into. Open/Create the window again.")
		return
	}
	var contenttype=contenttype.toLowerCase() //convert string to lower case
	if (typeof title!="undefined")
		t.handle.firstChild.nodeValue=title
	if (contenttype=="inline")
		t.contentarea.innerHTML=contentsource
  
  // ---------- Seditio Thumb ------------------
  else if (contenttype=="image") {
      var i = new Image();  
      i.src = contentsource;
      i.onload = function() {
			
        var max_h = (window.innerHeight > 0) ?  window.innerHeight - 100 : sed_modal.maxheightimage;
        var max_w = (window.innerWidth > 0) ?  window.innerWidth - 100 : sed_modal.maxwidthimage;
        
        if (i.height > max_h) { 			
            var newSize = sed_modal.scaleSize(max_w, max_h, i.width, i.height);
            i.width = newSize[0];
            i.height = newSize[1];					
  			 }
         			
  			t.setSize(i.width+4, i.height);      
        t.moveTo('middle', 'middle');

			};
      t.contentarea.appendChild(i);  
  }  
	// ----------------------------
  
  else if (contenttype=="div"){
		var inlinedivref=document.getElementById(contentsource)
		t.contentarea.innerHTML=(inlinedivref.defaultHTML || inlinedivref.innerHTML) //Populate window with contents of inline div on page
		if (!inlinedivref.defaultHTML)
			inlinedivref.defaultHTML=inlinedivref.innerHTML //save HTML within inline DIV
		inlinedivref.innerHTML="" //then, remove HTML within inline DIV (to prevent duplicate IDs, NAME attributes etc in contents of DHTML window
		inlinedivref.style.display="none" //hide that div
	}
	else if (contenttype=="iframe"){
		t.contentarea.style.overflow="hidden" //disable window scrollbars, as iframe already contains scrollbars
		if (!t.contentarea.firstChild || t.contentarea.firstChild.tagName!="IFRAME") //If iframe tag doesn't exist already, create it first
			t.contentarea.innerHTML='<iframe src="" style="margin:0; padding:0; width:100%; height: 100%" name="_iframe-'+t.id+'"></iframe>'
		window.frames["_iframe-"+t.id].location.replace(contentsource) //set location of iframe window to specified URL
		}
	else if (contenttype=="ajax"){
		this.ajax_connect(contentsource, t) //populate window with external contents fetched via Ajax
	}
	t.contentarea.datatype=contenttype //store contenttype of current window for future reference
},

setupdrag:function(e){
	var d=sed_modal //reference dhtml window object
	var t=this._parent //reference dhtml window div
	d.etarget=this //remember div mouse is currently held down on ("handle" or "resize" div)
	var e=window.event || e
	d.initmousex=e.clientX //store x position of mouse onmousedown
	d.initmousey=e.clientY
	d.initx=parseInt(t.offsetLeft) //store offset x of window div onmousedown
	d.inity=parseInt(t.offsetTop)
	d.width=parseInt(t.offsetWidth) //store width of window div
	d.contentheight=parseInt(t.contentarea.offsetHeight) //store height of window div's content div
	if (t.contentarea.datatype=="iframe"){ //if content of this window div is "iframe"
		t.style.backgroundColor="#F8F8F8" //colorize and hide content div (while window is being dragged)
		t.contentarea.style.visibility="hidden"
	}
	document.onmousemove=d.getdistance //get distance travelled by mouse as it moves
	document.onmouseup=function(){
		if (t.contentarea.datatype=="iframe"){ //restore color and visibility of content div onmouseup
			t.contentarea.style.backgroundColor="white"
			t.contentarea.style.visibility="visible"
		}
		d.stop()
	}
	return false
},

getdistance:function(e){
	var d=sed_modal
	var etarget=d.etarget
	var e=window.event || e
	d.distancex=e.clientX-d.initmousex //horizontal distance travelled relative to starting point
	d.distancey=e.clientY-d.initmousey
	if (etarget.className=="modal-handle") //if target element is "handle" div
		d.move(etarget._parent, e)
	else if (etarget.className=="modal-resizearea") //if target element is "resize" div
		d.resize(etarget._parent, e)
	return false //cancel default dragging behavior
},

getviewpoint:function(){ //get window viewpoint numbers
	var ie=document.all && !window.opera
	var domclientWidth=document.documentElement && parseInt(document.documentElement.clientWidth) || 100000 //Preliminary doc width in non IE browsers
	this.standardbody=(document.compatMode=="CSS1Compat")? document.documentElement : document.body //create reference to common "body" across doctypes
	this.scroll_top=(ie)? this.standardbody.scrollTop : window.pageYOffset
	this.scroll_left=(ie)? this.standardbody.scrollLeft : window.pageXOffset
	this.docwidth=(ie)? this.standardbody.clientWidth : (/Safari/i.test(navigator.userAgent))? window.innerWidth : Math.min(domclientWidth, window.innerWidth-16)
	this.docheight=(ie)? this.standardbody.clientHeight: window.innerHeight
},

rememberattrs:function(t){ //remember certain attributes of the window when it's minimized or closed, such as dimensions, position on page
	this.getviewpoint() //Get current window viewpoint numbers
	t.lastx=parseInt((t.style.left || t.offsetLeft))-sed_modal.scroll_left //store last known x coord of window just before minimizing
	t.lasty=parseInt((t.style.top || t.offsetTop))-sed_modal.scroll_top
	t.lastwidth=parseInt(t.style.width) //store last known width of window just before minimizing/ closing
},

move:function(t, e){
	t.style.left=sed_modal.distancex+sed_modal.initx+"px"
	t.style.top=sed_modal.distancey+sed_modal.inity+"px"
},

resize:function(t, e){
	t.style.width=Math.max(sed_modal.width+sed_modal.distancex, 150)+"px"
	t.contentarea.style.height=Math.max(sed_modal.contentheight+sed_modal.distancey, 100)+"px"
},

enablecontrols:function(e){
	var d=sed_modal
	var sourceobj=window.event? window.event.srcElement : e.target //Get element within "handle" div mouse is currently on (the controls)
	if (/Minimize/i.test(sourceobj.getAttribute("title"))) //if this is the "minimize" control
		d.minimize(sourceobj, this._parent)
	else if (/Restore/i.test(sourceobj.getAttribute("title"))) //if this is the "restore" control
		d.restore(sourceobj, this._parent)
	else if (/Close/i.test(sourceobj.getAttribute("title"))) //if this is the "close" control
		d.close(this._parent)
	return false
},

minimize:function(button, t){
	sed_modal.rememberattrs(t)
	button.setAttribute("src", sed_modal.imagefiles[2])
	button.setAttribute("title", "Restore")
	t.state="minimized" //indicate the state of the window as being "minimized"
	t.contentarea.style.display="none"
	t.statusarea.style.display="none"
	if (typeof t.minimizeorder=="undefined"){ //stack order of minmized window on screen relative to any other minimized windows
		sed_modal.minimizeorder++ //increment order
		t.minimizeorder=sed_modal.minimizeorder
	}
	t.style.left="10px" //left coord of minmized window
	t.style.width="200px"
	var windowspacing=t.minimizeorder*10 //spacing (gap) between each minmized window(s)
	t.style.top=sed_modal.scroll_top+sed_modal.docheight-(t.handle.offsetHeight*t.minimizeorder)-windowspacing+"px"
},

restore:function(button, t){
	sed_modal.getviewpoint()
	button.setAttribute("src", sed_modal.imagefiles[0])
	button.setAttribute("title", "Minimize")
	t.state="fullview" //indicate the state of the window as being "fullview"
	t.style.display="block"
	t.contentarea.style.display="block"
	if (t.resizeBool) //if this window is resizable, enable the resize icon
		t.statusarea.style.display="block"
	t.style.left=parseInt(t.lastx)+sed_modal.scroll_left+"px" //position window to last known x coord just before minimizing
	t.style.top=parseInt(t.lasty)+sed_modal.scroll_top+"px"
	t.style.width=parseInt(t.lastwidth)+"px"
},


close:function(t){
	try{
		var closewinbol=t.onclose()
	}
	catch(err){ //In non IE browsers, all errors are caught, so just run the below
		var closewinbol=true
 }
	finally{ //In IE, not all errors are caught, so check if variable isn't defined in IE in those cases
		if (typeof closewinbol=="undefined"){
			alert("An error has occured somwhere inside your \"onclose\" event handler")
			var closewinbol=true
		}
	}
	if (closewinbol){ //if custom event handler function returns true
		if (t.state!="minimized") //if this window isn't currently minimized
			sed_modal.rememberattrs(t) //remember window's dimensions/position on the page before closing
		if (window.frames["_iframe-"+t.id]) //if this is an IFRAME DHTML window
			window.frames["_iframe-"+t.id].location.replace("about:blank")
		else
			t.contentarea.innerHTML=""
		t.style.display="none"
		t.isClosed=true //tell script this window is closed (for detection in t.show())
	}
	return closewinbol
},


setopacity:function(targetobject, value){ //Sets the opacity of targetobject based on the passed in value setting (0 to 1 and in between)
	if (!targetobject)
		return
	if (targetobject.filters && targetobject.filters[0]){ //IE syntax
		if (typeof targetobject.filters[0].opacity=="number") //IE6
			targetobject.filters[0].opacity=value*100
		else //IE 5.5
			targetobject.style.filter="alpha(opacity="+value*100+")"
		}
	else if (typeof targetobject.style.MozOpacity!="undefined") //Old Mozilla syntax
		targetobject.style.MozOpacity=value
	else if (typeof targetobject.style.opacity!="undefined") //Standard opacity syntax
		targetobject.style.opacity=value
},

setfocus:function(t){ //Sets focus to the currently active window
	this.zIndexvalue++
	t.style.zIndex=this.zIndexvalue
	t.isClosed=false //tell script this window isn't closed (for detection in t.show())
	this.setopacity(this.lastactivet.handle, 0.5) //unfocus last active window
	this.setopacity(t.handle, 1) //focus currently active window
	this.lastactivet=t //remember last active window
},


show:function(t){
	if (t.isClosed){
		alert("DHTML Window has been closed, so nothing to show. Open/Create the window again.")
		return
	}
	if (t.lastx) //If there exists previously stored information such as last x position on window attributes (meaning it's been minimized or closed)
		sed_modal.restore(t.controls.firstChild, t) //restore the window using that info
	else
		t.style.display="block"
	this.setfocus(t)
	t.state="fullview" //indicate the state of the window as being "fullview"
},

hide:function(t){
	t.style.display="none"
},

ajax_connect:function(url, t){
	var page_request = false
	var bustcacheparameter=""
	if (window.XMLHttpRequest) // if Mozilla, IE7, Safari etc
		page_request = new XMLHttpRequest()
	else if (window.ActiveXObject){ // if IE6 or below
		try {
		page_request = new ActiveXObject("Msxml2.XMLHTTP")
		} 
		catch (e){
			try{
			page_request = new ActiveXObject("Microsoft.XMLHTTP")
			}
			catch (e){}
		}
	}
	else
		return false
	t.contentarea.innerHTML=this.ajaxloadinghtml
	page_request.onreadystatechange=function(){sed_modal.ajax_loadpage(page_request, t)}
	if (this.ajaxbustcache) //if bust caching of external page
		bustcacheparameter=(url.indexOf("?")!=-1)? "&"+new Date().getTime() : "?"+new Date().getTime()
	page_request.open('GET', url+bustcacheparameter, true)
	page_request.send(null)
},

ajax_loadpage:function(page_request, t){
	if (page_request.readyState == 4 && (page_request.status==200 || window.location.href.indexOf("http")==-1)){
	t.contentarea.innerHTML=page_request.responseText
	}
},


stop:function(){
	sed_modal.etarget=null //clean up
	document.onmousemove=null
	document.onmouseup=null
},

addEvent:function(target, functionref, tasktype){ //assign a function to execute to an event handler (ie: onunload)
	var tasktype=(window.addEventListener)? tasktype : "on"+tasktype
	if (target.addEventListener)
		target.addEventListener(tasktype, functionref, false)
	else if (target.attachEvent)
		target.attachEvent(tasktype, functionref)
},

cleanup:function(){
	for (var i=0; i<sed_modal.tobjects.length; i++){
		sed_modal.tobjects[i].handle._parent=sed_modal.tobjects[i].resizearea._parent=sed_modal.tobjects[i].controls._parent=null
	}
	window.onload=null
}

} //End sed_modal object 
 

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


  var pagelinks = document.getElementsByTagName("a");
  for (var i=0; i<pagelinks.length; i++) { 
    if (pagelinks[i].getAttribute("rel") && pagelinks[i].getAttribute("rel")=="sedthumb") { 
      pagelinks[i].onclick=function() {
        var imglink = this.getAttribute("href");
        var imgtitle = "Picture";
        if (this.getAttribute("title")) {
          imgtitle = this.getAttribute("title");
        }          
        var randid = Math.floor(Math.random() * (100000 - 1 + 1)) + 1;
        sed_modal.open('im'+randid, 'image', get_basehref() + imglink, imgtitle, 'resize=0,scrolling=0,center=1', 'recal');
        return false;
      }
    }
  }

}

var cookie = readCookie("style"); 
var title = cookie ? cookie : getPreferredStyleSheet();
setActiveStyleSheet(title);

window.name='main';

//window.onunload=sed_modal.cleanup