(function ( $ ) {
 
    $.fn.sed_uploader = function( options ) {

		var settings = $.extend({
			
			sed_uploader       		    	 : true,
			sed_uploader_use_main          	 : false,
			sed_uploader_use_sortable      	 : false,
			sed_uploader_use_dragndrop       : false,
			sed_uploader_use_rotation        : false,
			sed_uploader_hide_on_exceed      : true,
			sed_uploader_hide_in_progress    : false,
			sed_uploader_attach_images       : [],	
			sed_uploader_path  	   	         : 'plugins/uploader',
			sed_uploader_main_path           : 'datas/users',
			sed_uploader_thumbnail_path      : 'datas/thumbs',
			sed_uploader_file_delete_label 	 : "",
			sed_uploader_file_rotation_label : "",
			sed_uploader_field_name          : $(this).attr('id'),
			sed_uploader_add_image           : '',
			sed_uploader_add_label           : 'Browser for images',
			sed_uploader_main_changed        : "",
			sed_uploader_finished		     : "",
			sed_uploader_picture_deleted     : "",
			sed_uploader_maximum_uploads     : 100,
			sed_uploader_max_exceeded 	     : "",
			
		}, options);
			
		var holdername = this;
		
		if ($(this).attr('sed_uploader') == 'on') {
			var imageHolderId = '#'+$(this).attr('id');
			holdername = $(this).replaceWith(getHtml($(this).attr('id'), settings.sed_uploader_add_image, settings.sed_uploader_add_label, settings.sed_uploader_path));
			holdername = $("body").find(imageHolderId);
		}
		
		jQuery.data(holdername, 'already_uploaded', 1);
		jQuery.data(holdername, 'count', 0);
		jQuery.data(holdername, 'counter', 0);
				
		if (settings.sed_uploader_attach_images.length == parseInt(settings.sed_uploader_maximum_uploads)) {
			$(holdername).parent().find('.uploadButton').hide();
			$(holdername).parent().find('.uploadPfs').hide();
			jQuery.data(holdername, 'already_uploaded', 2);			
		}
		
		if (settings.sed_uploader_use_sortable)
			{
			$(holdername).sortable({
				update: function(event, ui) {				
					if (typeof settings.sed_uploader_rearranged == 'function') { 
						settings.sed_uploader_rearranged();
					}
					changeMain(holdername, settings);
				}
			});
			
			$("#"+holdername.attr("id") + "_to_clone").find(".file").css("cursor", "move");
			$("#"+holdername.attr("id") + "_to_clone").find(".multibox .file").css("cursor", "move");
			}
		else
			{
			$("#"+holdername.attr("id") + "_to_clone").find(".file").css("cursor", "auto");
			$("#"+holdername.attr("id") + "_to_clone").find(".multibox .file").css("cursor", "auto");
			}
			
		$(holdername).disableSelection();
		
		$(document).on("change", "."+$(holdername).attr("id")+"Input", function() {
			if(settings.sed_uploader_hide_in_progress == true) {
				if(parseInt(jQuery.data(holdername, 'currently_uploading')) == 1) return false;
				jQuery.data(holdername, 'currently_uploading', 1);
				$(holdername).parent().find('.uploadButton').hide();
			}
			jQuery.data(holdername, 'count', 0);
			sed_uploaderHandle(this.files,holdername,settings);
		});
		
		
		for (i = 0; i < settings.sed_uploader_attach_images.length; i++) {  
			var image = settings.sed_uploader_attach_images[i];
			
			var clone = $("#"+$(holdername).attr("id")+"_to_clone").find(".multibox").clone();
			$(holdername).append($(clone));
			$(clone).html("<div class='picture_insert'></div><div class='picture_delete'>"+settings.sed_uploader_file_delete_label+"</div><img src='"+settings.sed_uploader_thumbnail_path+"/"+image+"' alt='' onerror=this.src='"+settings.sed_uploader_path+"/images/no-image.jpg' class='picture_uploaded'/> <input type='hidden' value='"+image+"' name='"+settings.sed_uploader_field_name+"[]' />");
			$(clone).attr('id', image);
			$(clone).attr('filename', image);
		}
		
		if(settings.sed_uploader_attach_images.length > 0) {
			changeMain(holdername, settings);
		}
		
		$(holdername).on("click", ".picture_insert", function() {
		if (CKEDITOR.instances['rpagetext'])
		{
		  var html = '<img src=\"datas/users/'+$(this).parent().attr('filename')+'\" alt=\"\" />';
		  CKEDITOR.instances['rpagetext'].insertHtml(html);
		}
		if (CKEDITOR.instances['newpagetext'])
		{
		  var html = '<img src=\"datas/users/'+$(this).parent().attr('filename')+'\" alt=\"\" />';
		  CKEDITOR.instances['newpagetext'].insertHtml(html);          
		}
		if (CKEDITOR.instances['rstext'])
		{
		  var html = '<img src=\"datas/users/'+$(this).parent().attr('filename')+'\" alt=\"\" />';
		  CKEDITOR.instances['rstext'].insertHtml(html);          
		}		
    });
      
    $(holdername).on("click", ".picture_delete", function() {

			jQuery.data(holdername, "already_uploaded", jQuery.data(holdername, "already_uploaded")-1);
			
			$.ajax({
				url: "plug/?ajx=uploader&upl_delete=" + encodeURIComponent($(this).parent().attr('filename'))
			});			
			
			$(this).parent().fadeOut("slow", function() {
				
				$(this).remove();
				$(holdername).parent().find('.upl_errors').text("");
				
				if(jQuery.data(holdername, "already_uploaded")-1 > 0) {
					changeMain(holdername, settings);
				}
				
				if(settings.sed_uploader_hide_on_exceed == true) {
					$(holdername).parent().find('.uploadButton').show();
				}
			});
			
			if (typeof settings.sed_uploader_picture_deleted == 'function') { 
				settings.sed_uploader_picture_deleted($(this).parent().attr('filename'));
			}
		});

		$(holdername).on("click", ".rotate_picture", function() {	
			var context = this;
			
			$.ajax({
				url: "plug/?ajx=uploader&upl_rotate=" + encodeURIComponent($(this).parent().attr('filename'))+"&upl_degree_lvl=" + $(this).closest('.rotate_picture').attr('degree-lvl')
			}).done(function(file_name) {
				$img = $('html,body').find("input[value^='"+file_name+"']").prev('img');				
				if (parseInt($(context).closest('.rotate_picture').attr('degree-lvl')) > 3)  {
					$(context).closest('.rotate_picture').attr('degree-lvl', 1)
				} else {
					$(context).closest('.rotate_picture').attr('degree-lvl', parseInt($(context).closest('.rotate_picture').attr('degree-lvl'))+1)
				}
				$img.attr('src', $img.attr('src') +"?"+ new Date().getTime());
			});	
		});
		
		if (settings.sed_uploader_use_dragndrop)
		{
			var holder = document.getElementById($(holdername).attr("id")+"DDArea");
			holder.ondragover = function () { $(".uploadButton").addClass("DragAndDropHover"); return false; };
			holder.ondragend  = function () { $(".uploadButton").removeClass("DragAndDropHover"); return false; };
			
			holder.ondrop = function (e) {
				$(".uploadButton").removeClass("DragAndDropHover");
				e.preventDefault();
				sed_uploaderHandle(e.dataTransfer.files, holdername, settings);
			}
		}
		
    };

	function changeMain(holder, settings) {
		if (settings.sed_uploader_use_main) 
		{
			$(holder).find(".multibox").removeClass("main");
			$(holder).find(".multibox").first().addClass("main");
			
			if (typeof settings.sed_uploader_main_changed == 'function') {
				settings.sed_uploader_main_changed($(holder).find(".multibox").first().attr('filename'));
			}
		}
	}
	
	function sed_uploaderHandle(files, holder, settings) {
		var i = 0;
		var msg_alert = false;
		for(i=0; i<files.length; i++)
		{			
			if (jQuery.data(holder, "already_uploaded") > settings.sed_uploader_maximum_uploads && (typeof settings.sed_uploader_max_exceeded == 'function')) {
				if (msg_alert == false) settings.sed_uploader_max_exceeded();
				msg_alert = true;
				if (settings.sed_uploader_hide_on_exceed == true) $(holder).closest('.uploadButton').hide();				
			}
			var re = /(?:\.([^.]+))?$/;
			var ext = re.exec(files[i].name)[1].toLowerCase();
			
			if((ext == 'jpg' || ext == 'jpeg' || ext == 'png' || ext == 'gif') &&  jQuery.data(holder, "already_uploaded") <= settings.sed_uploader_maximum_uploads)
			{
				var clone = $("#"+$(holder).attr("id")+"_to_clone").find(".multibox").clone();
				
				$(holder).append($(clone));
				upload(files[i], clone, i, holder, settings); 
				jQuery.data(holder, "already_uploaded", jQuery.data(holder, "already_uploaded")+1);
				jQuery.data(holder, "count", jQuery.data(holder, "count")+1);
			}
		}
	}	
	
	window.counter = 0;
	function upload(file, clone, place, holder, settings)
	{
		if(settings.sed_uploader_hide_on_exceed == true && parseInt(jQuery.data(holder, 'already_uploaded')) == parseInt(settings.sed_uploader_maximum_uploads)) {
			$(holder).parent().find('.uploadButton').hide();	
		}
		var xhr = new XMLHttpRequest();
		xhr.open("POST", "plug/?ajx=uploader&upl_filename="+encodeURIComponent(file.name), true);
		xhr.send(file);
		xhr.onreadystatechange = function() 
		{
			var rotation_html = "";
			if (xhr.readyState == 4) 
			{				
				var response = JSON.parse(xhr.responseText);
				if (response.error == "" || response.error == null)
				{					
					if (settings.sed_uploader_use_rotation == true) {
						rotation_html = "<div class='rotate_picture' degree-lvl='1'>"+settings.sed_uploader_file_rotation_label+"</div>";
					}
					$(clone).html("<div class='picture_insert'></div><div class='picture_delete'>"+settings.sed_uploader_file_delete_label+"</div>"+rotation_html+"<img src='"+settings.sed_uploader_thumbnail_path+"/"+response.filename+"' alt='' onerror=this.src='"+settings.sed_uploader_path+"/images/no-image.jpg' class='picture_uploaded'/> <input type='hidden' value='"+response.filename+"' name='"+settings.sed_uploader_field_name+"[]' />");
					$(clone).attr('id', response.filename);
					$(clone).attr('filename', response.filename);
					jQuery.data(holder, "counter", jQuery.data(holder, "counter")+1);
					if(jQuery.data(holder, "count") == jQuery.data(holder, "counter")) 
						{
							if (typeof settings.sed_uploader_finished == 'function') { 
								settings.sed_uploader_finished();
							}
							changeMain(holder, settings);
							jQuery.data(holder, "counter", 0);
							if (settings.sed_uploader_hide_in_progress == true) {
								jQuery.data(holder, 'currently_uploading', 0);
								$(holder).parent().find('.uploadButton').show();
							}
						}
				} else {
					$(clone).remove();
					$(holder).parent().find(".upl_errors").text(response.error);
				}
			}
		}
	}
 
}( jQuery ));

window.initialized = 0;

function sed_uploaderLoad(name) {
	$('.'+(name)+'Input').click();
	window.initialized++;
}

function getHtml(name, add_image, add_label, path)
{
	return '<div id="'+name+'_to_clone" class="clone_item"><div class="multibox file"><div class="loading"><img src="'+path+'/images/loader.gif" alt="loader"/></div></div></div>\
		<div id="'+name+'DDArea">\
			<div id="'+name+'">\
			</div>\
			<div class="multibox uploadButton" onclick="javascript:sed_uploaderLoad(\''+name+'\');">\
			<img src="'+add_image+'"/>\
			<br/><br/>'+add_label+'\
			</div>\
			<input type="file" class="'+name+'Input sed_uploaderFileInput" accept="image/*" multiple/>\
			<div class="clear"></div>\
			<div class="upl_errors"></div>\
		</div>';
}
