<!-- BEGIN: UPLOADER -->

<script>
    sedjs.ready(function() {
        var uploaderSettings = {
            '{UPLOADER_ID}': {
            attach_images: {UPLOADER_ATTACH_IMAGES},
            userid: {UPLOADER_USERID},
            c1: '{UPLOADER_ACTION}',
            c2: '{UPLOADER_EXTRA}'
        }
        /* Examle for other field
		imageuploader2: {
            attach_images: [], // Empty array or specific images for the second element
            userid: 1,
            c1: 'updatepage1',
            c2: 'rpagethumb1'
        }
		*/		
    };

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
        add_label: '{PHP.L.upl_upload_images}', // Assuming this is a placeholder for "Upload images"
        main_changed: function(filename) {
            var field_name = this.field_name;
            var parent = document.getElementById(field_name).parentNode;
            var existing = parent.querySelector('#mainlabel-images');
            if (existing) existing.parentNode.removeChild(existing);
            var mainBox = parent.querySelector('[filename="' + filename + '"]');
            if (mainBox) {
                var label = document.createElement('div');
                label.id = 'mainlabel-images';
                label.className = 'maintext';
                label.textContent = '{PHP.L.upl_mainimage}'; // Assuming this is a placeholder for "Main image"
                mainBox.appendChild(label);
            }
        }
    };

    for (var uploaderId in uploaderSettings) {
        if (uploaderSettings.hasOwnProperty(uploaderId)) {
            var element = document.getElementById(uploaderId);
            if (element) {
                var settings = sedjs.extend({}, commonSettings, uploaderSettings[uploaderId]);
                new SedUploader(element, settings);

                // Use the wrapper to find elements after replacement
                var wrapper = document.getElementById(uploaderId + '_wrapper');
                if (wrapper) {
                    var uploadButton = wrapper.querySelector('.uploadButton');
                    if (uploadButton) {
                        var pfsButton = document.createElement('div');
                        pfsButton.className = 'multibox uploadButton uploadPfs';
                        pfsButton.innerHTML = '<img src="plugins/uploader/images/add.png"/><br/><br/>{PHP.L.upl_choose_from_pfs}'; // Assuming this is a placeholder for "Choose from PFS"
                        pfsButton.onclick = function() {
                            sedjs.pfs(
                                uploaderSettings[uploaderId].userid,
                                uploaderSettings[uploaderId].c1,
                                uploaderSettings[uploaderId].c2,
                                true
                            );
                        };
                        uploadButton.parentNode.insertBefore(pfsButton, uploadButton.nextSibling);
                    }
                } else {
                    console.error('Wrapper #' + uploaderId + '_wrapper not found after initialization');
                }
            } else {
                console.error('Element with ID ' + uploaderId + ' not found in DOM');
            }
        }
    }
    });
</script>

<!-- END: UPLOADER -->