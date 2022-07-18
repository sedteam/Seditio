<!-- BEGIN: UPLOADER -->
<script>      
      function upl_insertimg(nfilepath, nfile) 
      { 
        $('#imageuploader').append('<div class="multibox file" style="cursor: move;" id="'+nfile+'" filename="'+nfile+'"><div class="picture_delete">\
        </div><img src="'+nfilepath+'" alt="" onerror="this.src=plugins/uploader/images/no-image.jpg" class="picture_uploaded">\
        <input type="hidden" value="'+nfile+'" name="imageuploader[]"></div>');
      }

      $(document).ready(function(){
      	$('#imageuploader').sed_uploader({
			sed_uploader                       : true,
			sed_uploader_path                  : 'plugins/uploader',
			sed_uploader_main_path             : 'datas/users',
			sed_uploader_thumbnail_path        : 'datas/thumbs',
			sed_uploader_use_main              : true,
			sed_uploader_use_sortable          : {UPLOADER_PRELOAD_USE_SORTABLE},
			sed_uploader_use_dragndrop         : {UPLOADER_PRELOAD_USE_DRAGNDROP},
			sed_uploader_use_rotation          : {UPLOADER_PRELOAD_USE_ROTATION},
			sed_uploader_maximum_uploads       : {UPLOADER_PRELOAD_MAXIMUM_UPLOADS},
			sed_uploader_add_image             : 'plugins/uploader/images/add.png',
			sed_uploader_add_label             : '{PHP.L.upl_upload_images}',
			{UPLOADER_PRELOAD_IMAGES}
      		sed_uploader_main_changed    : function (filename) {
      			$("#mainlabel-images").remove();
      			$("div").find("[filename='" + filename + "']").append("<div id='mainlabel-images' class='maintext'>{PHP.L.upl_mainimage}</div>");
      		}      
      	});

  		$("div").find(".uploadButton").after("<div class=\"multibox uploadButton uploadPfs\" onclick=\"javascript:sedjs.pfs('{UPLOADER_PRELOAD_USERID}','{UPLOADER_PRELOAD_ACTION}','{UPLOADER_PRELOAD_EXTRA}',{UPLOADER_PRELOAD_ISMODAL});\">\
        <img src=\"plugins/uploader/images/add.png\"/><br /><br />\
        {PHP.L.upl_choose_from_pfs}</div>");     

      });  
</script>
<!-- END: UPLOADER -->