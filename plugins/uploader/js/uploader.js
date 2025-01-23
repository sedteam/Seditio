(function ($) {
    $.fn.sed_uploader = function (options) {
        var settings = $.extend({
            sed_uploader: true,
            use_main: false,
            use_sortable: false,
            use_dragndrop: false,
            use_rotation: false,
            hide_on_exceed: true,
            hide_in_progress: false,
            attach_images: [],
            path: 'plugins/uploader',
            main_path: 'datas/users',
            thumbnail_path: 'datas/thumbs',
            file_delete_label: "",
            file_rotation_label: "",
            field_name: $(this).attr('id'),
            add_image: '',
            add_label: 'Browser for images',
            main_changed: "",
            finished: "",
            picture_deleted: "",
            maximum_uploads: 100,
            max_exceeded: "",
        }, options);

        return this.each(function () {
            var holdername = $(this);
            var uniqueId = holdername.attr('id');

            if (holdername.attr('sed_uploader') == 'on') {
                var imageHolderId = '#' + uniqueId;
                holdername.replaceWith(getHtml(uniqueId, settings.add_image, settings.add_label, settings.path));
                holdername = $("body").find(imageHolderId);
            }

            jQuery.data(holdername, 'already_uploaded', 1);
            jQuery.data(holdername, 'count', 0);
            jQuery.data(holdername, 'counter', 0);

            if (settings.attach_images.length == parseInt(settings.maximum_uploads)) {
                holdername.parent().find('.uploadButton').hide();
                holdername.parent().find('.uploadPfs').hide();
                jQuery.data(holdername, 'already_uploaded', 2);
            }

            if (settings.use_sortable) {
                holdername.sortable({
                    update: function (event, ui) {
                        if (typeof settings.rearranged == 'function') {
                            settings.rearranged();
                        }
                        changeMain(holdername, settings);
                    }
                });

                $("#" + uniqueId + "_to_clone").find(".file").css("cursor", "move");
                $("#" + uniqueId + "_to_clone").find(".multibox .file").css("cursor", "move");
            } else {
                $("#" + uniqueId + "_to_clone").find(".file").css("cursor", "auto");
                $("#" + uniqueId + "_to_clone").find(".multibox .file").css("cursor", "auto");
            }

            holdername.disableSelection();

            $(document).on("change", "." + uniqueId + "Input", function () {
                if (settings.hide_in_progress == true) {
                    if (parseInt(jQuery.data(holdername, 'currently_uploading')) == 1) return false;
                    jQuery.data(holdername, 'currently_uploading', 1);
                    holdername.parent().find('.uploadButton').hide();
                }
                jQuery.data(holdername, 'count', 0);
                sed_uploaderHandle(this.files, holdername, settings);
            });

            for (var i = 0; i < settings.attach_images.length; i++) {
                var image = settings.attach_images[i];

                var clone = $("#" + uniqueId + "_to_clone").find(".multibox").clone();
                holdername.append(clone);
                clone.html("<div class='picture_insert'></div><div class='picture_delete'>" + settings.file_delete_label + "</div><img src='" + settings.thumbnail_path + "/" + image + "' alt='' onerror=this.src='" + settings.path + "/images/no-image.jpg' class='picture_uploaded'/> <input type='hidden' value='" + image + "' name='" + settings.field_name + "[]' />");
                clone.attr('id', uniqueId + '_' + image);
                clone.attr('filename', image);
            }

            if (settings.attach_images.length > 0) {
                changeMain(holdername, settings);
            }

            holdername.on("click", ".picture_insert", function () {
                var filename = $(this).parent().attr('filename');
                var html = '<img src="datas/users/' + filename + '" alt="" />';
                if (CKEDITOR.instances['rpagetext']) {
                    CKEDITOR.instances['rpagetext'].insertHtml(html);
                }
                if (CKEDITOR.instances['newpagetext']) {
                    CKEDITOR.instances['newpagetext'].insertHtml(html);
                }
                if (CKEDITOR.instances['rstext']) {
                    CKEDITOR.instances['rstext'].insertHtml(html);
                }
            });

            holdername.on("click", ".picture_delete", function () {
                jQuery.data(holdername, "already_uploaded", jQuery.data(holdername, "already_uploaded") - 1);

                $.ajax({
                    url: "plug/?ajx=uploader&upl_delete=" + encodeURIComponent($(this).parent().attr('filename'))
                });

                $(this).parent().fadeOut("slow", function () {
                    $(this).remove();
                    holdername.parent().find('.upl_errors').text("");

                    if (jQuery.data(holdername, "already_uploaded") - 1 > 0) {
                        changeMain(holdername, settings);
                    }

                    if (settings.hide_on_exceed == true) {
                        holdername.parent().find('.uploadButton').show();
                    }
                });

                if (typeof settings.picture_deleted == 'function') {
                    settings.picture_deleted($(this).parent().attr('filename'));
                }
            });

            holdername.on("click", ".rotate_picture", function () {
                var context = this;

                $.ajax({
                    url: "plug/?ajx=uploader&upl_rotate=" + encodeURIComponent($(this).parent().attr('filename')) + "&upl_degree_lvl=" + $(this).closest('.rotate_picture').attr('degree-lvl')
                }).done(function (file_name) {
                    var $img = holdername.find("input[value^='" + file_name + "']").prev('img');
                    if (parseInt($(context).closest('.rotate_picture').attr('degree-lvl')) > 3) {
                        $(context).closest('.rotate_picture').attr('degree-lvl', 1);
                    } else {
                        $(context).closest('.rotate_picture').attr('degree-lvl', parseInt($(context).closest('.rotate_picture').attr('degree-lvl')) + 1);
                    }
                    $img.attr('src', $img.attr('src') + "?" + new Date().getTime());
                });
            });

            if (settings.use_dragndrop) {
                var holder = document.getElementById(uniqueId + "DDArea");
                holder.ondragover = function () {
                    holdername.find(".uploadButton").addClass("DragAndDropHover");
                    return false;
                };
                holder.ondragend = function () {
                    holdername.find(".uploadButton").removeClass("DragAndDropHover");
                    return false;
                };

                holder.ondrop = function (e) {
                    holdername.find(".uploadButton").removeClass("DragAndDropHover");
                    e.preventDefault();
                    sed_uploaderHandle(e.dataTransfer.files, holdername, settings);
                };
            }
        });
    };

    function changeMain(holder, settings) {
        if (settings.use_main) {
            holder.find(".multibox").removeClass("main");
            holder.find(".multibox").first().addClass("main");

            if (typeof settings.main_changed == 'function') {
                settings.main_changed(holder.find(".multibox").first().attr('filename'));
            }
        }
    }

    function sed_uploaderHandle(files, holder, settings) {
        var i = 0;
        var msg_alert = false;
        for (i = 0; i < files.length; i++) {
            if (jQuery.data(holder, "already_uploaded") > settings.maximum_uploads && (typeof settings.max_exceeded == 'function')) {
                if (msg_alert == false) settings.max_exceeded();
                msg_alert = true;
                if (settings.hide_on_exceed == true) holder.closest('.uploadButton').hide();
            }
            var re = /(?:\.([^.]+))?$/;
            var ext = re.exec(files[i].name)[1].toLowerCase();

            if ((ext == 'jpg' || ext == 'jpeg' || ext == 'png' || ext == 'gif') && jQuery.data(holder, "already_uploaded") <= settings.maximum_uploads) {
                var clone = $("#" + holder.attr("id") + "_to_clone").find(".multibox").clone();

                holder.append(clone);
                upload(files[i], clone, i, holder, settings);
                jQuery.data(holder, "already_uploaded", jQuery.data(holder, "already_uploaded") + 1);
                jQuery.data(holder, "count", jQuery.data(holder, "count") + 1);
            }
        }
    }

    window.counter = 0;
    function upload(file, clone, place, holder, settings) {
        if (settings.hide_on_exceed == true && parseInt(jQuery.data(holder, 'already_uploaded')) == parseInt(settings.maximum_uploads)) {
            holder.parent().find('.uploadButton').hide();
        }
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "plug/?ajx=uploader&upl_filename=" + encodeURIComponent(file.name), true);
        xhr.send(file);
        xhr.onreadystatechange = function () {
            var rotation_html = "";
            if (xhr.readyState == 4) {
                var response = JSON.parse(xhr.responseText);
                if (response.error == "" || response.error == null) {
                    if (settings.use_rotation == true) {
                        rotation_html = "<div class='rotate_picture' degree-lvl='1'>" + settings.file_rotation_label + "</div>";
                    }
                    clone.html("<div class='picture_insert'></div><div class='picture_delete'>" + settings.file_delete_label + "</div>" + rotation_html + "<img src='" + settings.thumbnail_path + "/" + response.filename + "' alt='' onerror=this.src='" + settings.path + "/images/no-image.jpg' class='picture_uploaded'/> <input type='hidden' value='" + response.filename + "' name='" + settings.field_name + "[]' />");
                    clone.attr('id', response.filename);
                    clone.attr('filename', response.filename);
                    jQuery.data(holder, "counter", jQuery.data(holder, "counter") + 1);
                    if (jQuery.data(holder, "count") == jQuery.data(holder, "counter")) {
                        if (typeof settings.finished == 'function') {
                            settings.finished();
                        }
                        changeMain(holder, settings);
                        jQuery.data(holder, "counter", 0);
                        if (settings.hide_in_progress == true) {
                            jQuery.data(holder, 'currently_uploading', 0);
                            holder.parent().find('.uploadButton').show();
                        }
                    }
                } else {
                    clone.remove();
                    holder.parent().find(".upl_errors").text(response.error);
                }
            }
        };
    }

}(jQuery));

window.initialized = 0;

function sed_uploaderLoad(name) {
    $('.' + name + 'Input').click();
    window.initialized++;
}

function getHtml(name, add_image, add_label, path) {
    return '<div id="' + name + '_to_clone" class="clone_item"><div class="multibox file"><div class="loading"><img src="' + path + '/images/loader.gif" alt="loader"/></div></div></div>\
        <div id="' + name + 'DDArea">\
            <div id="' + name + '">\
            </div>\
            <div class="multibox uploadButton" onclick="javascript:sed_uploaderLoad(\'' + name + '\');">\
            <img src="' + add_image + '"/>\
            <br/><br/>' + add_label + '\
            </div>\
            <input type="file" class="' + name + 'Input sed_uploaderFileInput" accept="image/*" multiple/>\
            <div class="clear"></div>\
            <div class="upl_errors"></div>\
        </div>';
}
