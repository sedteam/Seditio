/*
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org
*/

(function() {

CKEDITOR.plugins.add('imagepaste', {
    init: function(editor) {
        editor.on('paste', function(evt) {
            var data = evt.data.dataValue;
            var tempDiv = document.createElement('div');
            tempDiv.innerHTML = data;
            var images = tempDiv.getElementsByTagName('img');
            for (var i = 0; i < images.length; i++) {
                var img = images[i];
                var src = img.getAttribute('src');
                if (src) {
                    var xhr = new XMLHttpRequest();
                    var uploadUrl = editor.config.uploadUrl;
                    var formData = new FormData();
                    formData.append('imageUrl', src);

                    xhr.open('POST', uploadUrl, true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            try {
                                var response = JSON.parse(xhr.responseText);
                                if (response.uploaded) {
                                    img.setAttribute('src', response.url);
                                } else {
                                    console.log('Error uploading image:', response.error.message);
                                }
                            } catch (e) {
                                console.log('Invalid JSON response:', xhr.responseText);
                            }
                        } else {
                           console.log('Server error:', xhr.statusText);
                        }
                    };
                    xhr.onerror = function() {
                        console.log('Request failed');
                    };
                    xhr.send('imageUrl=' + encodeURIComponent(src));
                }
            }
            evt.data.dataValue = tempDiv.innerHTML;
        });
    }
});
	
})();
