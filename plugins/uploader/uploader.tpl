<!-- BEGIN: UPLOADER -->

<script>

function upl_insertimg(nfilepath, nfile, uploaderId) {
    // Append a new image box to the uploader element
    $('#' + uploaderId).append('<div class="multibox file" style="cursor: move;" id="' + nfile + '" filename="' + nfile + '"><div class="picture_delete"></div><img src="' + nfilepath + '" alt="" onerror="this.src=\'plugins/uploader/images/no-image.jpg\'" class="picture_uploaded"><input type="hidden" value="' + nfile + '" name="' + uploaderId + '[]"></div>');
}

$(document).ready(function () {
    // Object with settings for each uploader element
    var uploaderSettings = {
        {UPLOADER_ID}: {
            attach_images: {UPLOADER_ATTACH_IMAGES},
            userid: {UPLOADER_USERID},
            c1: '{UPLOADER_ACTION}',
            c2: '{UPLOADER_EXTRA}'
        },
        /* Examle for other field
		imageuploader2: {
            attach_images: [], // Empty array or specific images for the second element
            userid: 1,
            c1: 'updatepage1',
            c2: 'rpagethumb1'
        }
		*/
    };

    // Common settings for all uploader elements
    var commonSettings = {
        sed_uploader: true,
        path: 'plugins/uploader',
        main_path: 'datas/users',
        thumbnail_path: 'datas/thumbs',
        use_main: true,
        use_sortable: true,
        use_dragndrop: true,
        use_rotation: true,
        maximum_uploads: 100,
        add_image: 'plugins/uploader/images/add.png',
        add_label: '{PHP.L.upl_upload_images}',
        main_changed: function (filename) {
            var field_name = this.field_name;
            // Remove only the element with id 'mainlabel-images' in the context of the current element
            $('#' + field_name).parent().find("#mainlabel-images").remove();
            $('#' + field_name).parent().find("[filename='" + filename + "']").append("<div id='mainlabel-images' class='maintext'>{PHP.L.upl_mainimage}</div>");
        }
    };

    // Initialize each uploader element
    $.each(uploaderSettings, function (uploaderId, settings) {
        $('#' + uploaderId).sed_uploader($.extend({}, commonSettings, settings));

        // Check if the element with class 'uploadButton' exists
        var uploadButton = $('#' + uploaderId).parent().find(".uploadButton");
        if (uploadButton.length > 0) {
            // Add the "Choose from PFS" button with unique parameters
            uploadButton.after("<div class=\"multibox uploadButton uploadPfs\" onclick=\"javascript:sedjs.pfs('" + settings.userid + "','" + settings.c1 + "','" + settings.c2 + "',1);\"><img src=\"plugins/uploader/images/add.png\"/><br /><br />{PHP.L.upl_choose_from_pfs}</div>");
        }
    });
});

</script>

<!-- END: UPLOADER -->