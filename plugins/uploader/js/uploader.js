(function(window, document, sedjs) {
    // Constructor for SedUploader
    function SedUploader(element, options) {
        // Default settings for the uploader
        var defaults = {
            sed_uploader: true, // Indicates if this is a SedUploader instance
            use_main: false, // Whether to mark the first image as main
            use_sortable: false, // Enable sorting of uploaded images
            use_dragndrop: false, // Enable drag-and-drop file upload
            use_rotation: false, // Enable image rotation feature
            hide_on_exceed: true, // Hide upload buttons when max uploads are reached
            hide_in_progress: false, // Hide upload buttons during upload process
            attach_images: [], // Array of pre-attached image filenames
            path: 'plugins/uploader', // Base path for uploader resources
            main_path: 'datas/users', // Path for full-size images
            thumbnail_path: 'datas/thumbs', // Path for thumbnail images
            file_delete_label: "", // Label for delete button (e.g., "Delete")
            file_rotation_label: "", // Label for rotation button (e.g., "Rotate")
            field_name: element.id, // Name of the hidden input fields
            add_image: '', // URL of the "add image" icon
            add_label: 'Select images', // Text for the upload button
            main_changed: "", // Callback when main image changes
            finished: "", // Callback when upload finishes
            picture_deleted: "", // Callback when an image is deleted
            maximum_uploads: 100, // Maximum number of allowed uploads
            max_exceeded: "" // Callback when max uploads are exceeded
        };

        this.settings = sedjs.extend({}, defaults, options); // Merge defaults with provided options
        this.element = element; // The DOM element to attach the uploader to
        this.uniqueId = element.id; // Unique ID of the element
        this.dragDropEnabled = this.settings.use_dragndrop; // Tracks drag-and-drop state
        this.init(); // Initialize the uploader
    }

    // Prototype methods for SedUploader
    SedUploader.prototype = {
        // Initialize the uploader
        init: function() {
            var self = this;
            var holder = self.element;

            if (!holder) {
                console.error('Element for SedUploader not found');
                return;
            }

            // Replace the element with uploader HTML if marked as uploader
            if (holder.getAttribute('sed_uploader') === 'on') {
                var imageHolderId = '#' + self.uniqueId;
                var newElement = self.getHtml();
                if (holder.parentNode) {
                    holder.parentNode.replaceChild(newElement, holder);
                    holder = document.querySelector(imageHolderId);
                    self.element = holder;
                } else {
                    console.error('Parent element for replacement not found');
                    return;
                }
            }

            if (!holder) {
                console.error('Could not find element with ID: ' + self.uniqueId + ' after replacement');
                return;
            }

            // Initialize holder data
            holder.data = holder.data || {};
            holder.data.already_uploaded = 1; // Track number of uploaded images
            holder.data.count = 0; // Count of files being processed
            holder.data.counter = 0; // Counter for completed uploads

            // Hide buttons if max uploads reached
            if (self.settings.attach_images.length === parseInt(self.settings.maximum_uploads)) {
                self.hideUploadButtons();
                holder.data.already_uploaded = 2;
            }

            // Enable sorting if specified
            if (self.settings.use_sortable) {
                sedjs.sortable(holder, {
                    items: '.multibox.file', // Target elements for sorting
                    start: function(event, ui) {
                        if (self.settings.use_dragndrop) {
                            self.disableDragDrop(); // Disable drag-and-drop during sorting
                        }
                        console.log('Sorting started');
                    },
                    update: function(event, ui) {
                        if (typeof self.settings.rearranged === 'function') {
                            self.settings.rearranged(); // Call rearranged callback if provided
                        }
                        self.changeMain(); // Update main image
                        console.log('Sorting updated');
                    },
                    stop: function(event, ui) {
                        if (self.settings.use_dragndrop) {
                            self.enableDragDrop(); // Re-enable drag-and-drop after sorting
                        }
                        console.log('Sorting stopped');
                    }
                });
                self.setCursor('move'); // Set cursor for sortable items
            } else {
                self.setCursor('auto');
            }

            self.attachImages(); // Attach pre-loaded images
            self.bindEvents(); // Bind event handlers

            // Set first image as main if applicable
            if (self.settings.attach_images.length > 0) {
                self.changeMain();
            }
        },

        // Generate the HTML structure for the uploader
        getHtml: function() {
            var self = this;
            var html = [
                '<div id="' + self.uniqueId + '_wrapper">',
                '<div id="' + self.uniqueId + '_to_clone" class="clone_item"><div class="multibox file"><div class="loading"><img src="' + self.settings.path + '/images/loader.gif" alt="loader"/></div></div></div>',
                '<div id="' + self.uniqueId + 'DDArea">',
                '<div id="' + self.uniqueId + '"></div>',
                '<div class="multibox uploadButton" onclick="SedUploader.load(\'' + self.uniqueId + '\');">',
                '<img src="' + self.settings.add_image + '"/>',
                '<br/><br/>' + self.settings.add_label,
                '</div>',
                '<input type="file" class="' + self.uniqueId + 'Input sed_uploaderFileInput" accept="image/*" multiple/>',
                '<div class="clear"></div>',
                '<div class="upl_errors"></div>',
                '</div>',
                '</div>'
            ].join('');
            var container = document.createElement('div');
            container.innerHTML = html;
            return container.querySelector('#' + self.uniqueId + '_wrapper');
        },

        // Hide upload buttons
        hideUploadButtons: function() {
            var self = this;
            var parent = self.element.parentNode;
            var buttons = parent.querySelectorAll('.uploadButton, .uploadPfs');
            for (var i = 0; i < buttons.length; i++) {
                buttons[i].style.display = 'none';
            }
        },

        // Set cursor style for file elements
        setCursor: function(cursor) {
            var self = this;
            var files = document.querySelectorAll('#' + self.uniqueId + '_to_clone .file, #' + self.uniqueId + '_to_clone .multibox .file');
            for (var i = 0; i < files.length; i++) {
                files[i].style.cursor = cursor;
            }
            var sortableItems = document.querySelectorAll('#' + self.uniqueId + ' .multibox.file');
            for (var j = 0; j < sortableItems.length; j++) {
                sortableItems[j].style.cursor = cursor;
            }
        },

        // Attach pre-loaded images to the holder
        attachImages: function() {
            var self = this;
            var holder = self.element;
            for (var i = 0; i < self.settings.attach_images.length; i++) {
                var image = self.settings.attach_images[i];
                var clone = self.cloneBox(image);
                holder.appendChild(clone);
            }
        },

        // Create a clone box for an image
        cloneBox: function(image) {
            var self = this;
            var cloneElement = document.querySelector('#' + self.uniqueId + '_to_clone .multibox');
            if (!cloneElement) {
                console.error('Element #_' + self.uniqueId + '_to_clone .multibox not found');
                return null;
            }
            var clone = cloneElement.cloneNode(true);
            if (image) {
                clone.innerHTML = [
                    '<div class="picture_insert"></div>',
                    '<div class="picture_delete">' + self.settings.file_delete_label + '</div>',
                    '<img src="' + self.settings.thumbnail_path + '/' + image + '" alt="" onerror="this.src=\'' + self.settings.path + '/images/no-image.jpg\'" class="picture_uploaded"/>',
                    '<input type="hidden" value="' + image + '" name="' + self.settings.field_name + '[]"/>'
                ].join('');
                clone.id = self.uniqueId + '_' + image;
                clone.setAttribute('filename', image);
            }
            return clone;
        },

        // Bind event handlers to the uploader
        bindEvents: function() {
            var self = this;
            var holder = self.element;

            var input = document.querySelector('.' + self.uniqueId + 'Input');
            if (input) {
                input.onchange = function() {
                    if (self.settings.hide_in_progress && holder.data.currently_uploading === 1) return;
                    holder.data.currently_uploading = 1;
                    if (self.settings.hide_in_progress) {
                        self.hideUploadButtons();
                    }
                    holder.data.count = 0;
                    self.handleFiles(this.files);
                };
            }

            holder.onclick = function(event) {
                var target = event.target;
                if (sedjs.hasClass(target, 'picture_insert')) {
                    self.insertImage(target.parentNode.getAttribute('filename'));
                } else if (sedjs.hasClass(target, 'picture_delete')) {
                    self.deleteImage(target);
                } else if (sedjs.hasClass(target, 'rotate_picture')) {
                    self.rotateImage(target);
                }
            };

            if (self.settings.use_dragndrop) {
                self.enableDragDrop();
            }
        },

        // Handle selected or dropped files
        handleFiles: function(files) {
            var self = this;
            var holder = self.element;
            var i, msg_alert = false;

            for (i = 0; i < files.length; i++) {
                if (holder.data.already_uploaded > self.settings.maximum_uploads && typeof self.settings.max_exceeded === 'function') {
                    if (!msg_alert) self.settings.max_exceeded();
                    msg_alert = true;
                    if (self.settings.hide_on_exceed) self.hideUploadButtons();
                }
                var ext = files[i].name.split('.').pop().toLowerCase();
                if (['jpg', 'jpeg', 'png', 'gif', 'webp'].indexOf(ext) !== -1 && holder.data.already_uploaded <= self.settings.maximum_uploads) {
                    var template = document.querySelector('#' + self.uniqueId + '_to_clone .multibox');
                    if (!template) {
                        console.error('Template #' + self.uniqueId + '_to_clone .multibox not found');
                        continue;
                    }
                    var clone = template.cloneNode(true);
                    holder.appendChild(clone);
                    self.uploadFile(files[i], clone, i);
                    holder.data.already_uploaded++;
                    holder.data.count++;
                }
            }
        },

        // Upload a single file via AJAX
        uploadFile: function(file, clone, place) {
            var self = this;
            var holder = self.element;

            if (self.settings.hide_on_exceed && holder.data.already_uploaded === self.settings.maximum_uploads) {
                self.hideUploadButtons();
            }

            sedjs.ajax({
                url: 'plug/?ajx=uploader&upl_filename=' + encodeURIComponent(file.name),
                method: 'POST',
                data: file,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    if (!response.error) {
                        var rotation_html = self.settings.use_rotation ? '<div class="rotate_picture" degree-lvl="1">' + self.settings.file_rotation_label + '</div>' : '';
                        clone.innerHTML = [
                            '<div class="picture_insert"></div>',
                            '<div class="picture_delete">' + self.settings.file_delete_label + '</div>',
                            rotation_html,
                            '<img src="' + self.settings.thumbnail_path + '/' + response.filename + '" alt="" onerror="this.src=\'' + self.settings.path + '/images/no-image.jpg\'" class="picture_uploaded"/>',
                            '<input type="hidden" value="' + response.filename + '" name="' + self.settings.field_name + '[]"/>'
                        ].join('');
                        clone.id = response.filename;
                        clone.setAttribute('filename', response.filename);
                        if (self.settings.use_sortable) {
                            clone.style.cursor = 'move';
                        }
                        holder.data.counter++;

                        if (holder.data.count === holder.data.counter) {
                            if (typeof self.settings.finished === 'function') {
                                self.settings.finished();
                            }
                            self.changeMain();
                            holder.data.counter = 0;
                            if (self.settings.hide_in_progress) {
                                holder.data.currently_uploading = 0;
                                holder.parentNode.querySelector('.uploadButton').style.display = 'block';
                            }
                        }
                    } else {
                        if (clone && clone.parentNode) {
                            clone.parentNode.removeChild(clone);
                        }
                        holder.parentNode.querySelector('.upl_errors').textContent = response.error;
                    }
                },
                error: function(xhr, status, error) {
                    if (clone && clone.parentNode) {
                        clone.parentNode.removeChild(clone);
                    }
                    holder.parentNode.querySelector('.upl_errors').textContent = 'Upload error: ' + error;
                }
            });
        },

        // Insert an image into CKEditor instances
        insertImage: function(filename) {
            var html = '<img src="datas/users/' + filename + '" alt="" />';
            if (window.CKEDITOR && CKEDITOR.instances['rpagetext']) {
                CKEDITOR.instances['rpagetext'].insertHtml(html);
            }
            if (window.CKEDITOR && CKEDITOR.instances['newpagetext']) {
                CKEDITOR.instances['newpagetext'].insertHtml(html);
            }
            if (window.CKEDITOR && CKEDITOR.instances['rstext']) {
                CKEDITOR.instances['rstext'].insertHtml(html);
            }
        },

        // Delete an uploaded image with animation, running AJAX in parallel
        deleteImage: function(target) {
            var self = this;
            var holder = self.element;
            var filename = target.parentNode.getAttribute('filename');
            var clone = target.parentNode;

            // Decrease the count of uploaded images immediately
            holder.data.already_uploaded--;

            // Step 1: Start animation immediately
            clone.style.transition = 'opacity 0.3s ease-out, height 0.3s ease-out'; // Add transition properties
            clone.style.opacity = '0'; // Fade out
            clone.style.height = clone.offsetHeight + 'px'; // Set initial height explicitly
            clone.style.overflow = 'hidden'; // Prevent content overflow during animation

            // Step 2: Trigger height reduction after a tiny delay to ensure transition applies
            setTimeout(function() {
                clone.style.height = '0'; // Collapse height to 0
            }, 10);

            // Step 3: Remove element from DOM after animation completes
            setTimeout(function() {
                if (clone && clone.parentNode) {
                    clone.parentNode.removeChild(clone); // Remove the element
                    holder.parentNode.querySelector('.upl_errors').textContent = ''; // Clear error messages
                    if (holder.data.already_uploaded - 1 > 0) {
                        self.changeMain(); // Update main image if any remain
                    }
                    if (self.settings.hide_on_exceed) {
                        holder.parentNode.querySelector('.uploadButton').style.display = 'block'; // Show upload button
                    }
                }
            }, 300); // Match duration of transition (300ms)

            // Step 4: Send AJAX request to delete the file on the server in parallel
            sedjs.ajax({
                url: 'plug/?ajx=uploader&upl_delete=' + encodeURIComponent(filename),
                method: 'GET',
                success: function() {
                    // Call picture_deleted callback if provided, even if animation is already done
                    if (typeof self.settings.picture_deleted === 'function') {
                        self.settings.picture_deleted(filename);
                    }
                },
                error: function(xhr, status, error) {
                    // Handle error (e.g., log it or show a message)
                    console.error('Failed to delete image on server: ' + error);
                    holder.parentNode.querySelector('.upl_errors').textContent = 'Failed to delete image: ' + error;
                }
            });
        },

        // Rotate an uploaded image
        rotateImage: function(target) {
            var self = this;
            var holder = self.element;
            var filename = target.parentNode.getAttribute('filename');
            var degreeLvl = parseInt(target.getAttribute('degree-lvl')) || 1;

            sedjs.ajax({
                url: 'plug/?ajx=uploader&upl_rotate=' + encodeURIComponent(filename) + '&upl_degree_lvl=' + degreeLvl,
                method: 'GET',
                success: function(file_name) {
                    var img = holder.querySelector('input[value^="' + file_name + '"]').previousSibling;
                    degreeLvl = degreeLvl > 3 ? 1 : degreeLvl + 1;
                    target.setAttribute('degree-lvl', degreeLvl);
                    img.src = img.src.split('?')[0] + '?' + new Date().getTime();
                }
            });
        },

        // Enable drag-and-drop functionality
        enableDragDrop: function() {
            var self = this;
            if (!self.settings.use_dragndrop) return;

            var holder = document.getElementById(self.uniqueId + 'DDArea');
            var uploadButton = holder.querySelector('.uploadButton');

            var dragHandler = {
                onDragOver: function(e) {
                    e.preventDefault();
                    sedjs.addClass(uploadButton, 'DragAndDropHover');
                    return false;
                },
                onDragEnd: function(e) {
                    e.preventDefault();
                    sedjs.removeClass(uploadButton, 'DragAndDropHover');
                    return false;
                },
                onDrop: function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    sedjs.removeClass(uploadButton, 'DragAndDropHover');
                    if (self.settings.hide_in_progress && self.element.data.currently_uploading === 1) return;
                    self.element.data.currently_uploading = 1;
                    if (self.settings.hide_in_progress) {
                        self.hideUploadButtons();
                    }
                    self.handleFiles(e.dataTransfer.files);
                }
            };

            if (holder) {
                holder.ondragover = dragHandler.onDragOver;
                holder.ondragend = dragHandler.onDragEnd;
                holder.ondrop = dragHandler.onDrop;
            }

            if (uploadButton) {
                uploadButton.ondragover = dragHandler.onDragOver;
                uploadButton.ondragend = dragHandler.onDragEnd;
                uploadButton.ondrop = dragHandler.onDrop;
            }

            self.dragDropEnabled = true;
        },

        // Disable drag-and-drop functionality
        disableDragDrop: function() {
            var self = this;
            var holder = document.getElementById(self.uniqueId + 'DDArea');
            var uploadButton = holder.querySelector('.uploadButton');

            if (holder) {
                holder.ondragover = null;
                holder.ondragend = null;
                holder.ondrop = null;
            }
            if (uploadButton) {
                uploadButton.ondragover = null;
                uploadButton.ondragend = null;
                uploadButton.ondrop = null;
            }
            self.dragDropEnabled = false;
        },

        // Update the main image designation
        changeMain: function() {
            var self = this;
            var holder = self.element;
            if (self.settings.use_main) {
                var boxes = holder.querySelectorAll('.multibox');
                for (var i = 0; i < boxes.length; i++) {
                    sedjs.removeClass(boxes[i], 'main');
                }
                if (boxes.length > 0) {
                    sedjs.addClass(boxes[0], 'main');
                    if (typeof self.settings.main_changed === 'function') {
                        self.settings.main_changed(boxes[0].getAttribute('filename'));
                    }
                }
            }
        }
    };

    // Static method to trigger file input click
    SedUploader.load = function(name) {
        var input = document.querySelector('.' + name + 'Input');
        if (input) {
            input.click();
            window.initialized = window.initialized || 0;
            window.initialized++;
        }
    };

    window.SedUploader = SedUploader;

    // Global function to insert an image into the uploader
    window.upl_insertimg = function(nfilepath, nfile, uploaderId) {
        var holder = document.getElementById(uploaderId);
        if (holder) {
            var div = document.createElement('div');
            div.className = 'multibox file';
            div.style.cursor = 'move';
            div.id = nfile;
            div.setAttribute('filename', nfile);
            div.innerHTML = [
                '<div class="picture_delete"></div>',
                '<img src="' + nfilepath + '" alt="" onerror="this.src=\'plugins/uploader/images/no-image.jpg\'" class="picture_uploaded"/>',
                '<input type="hidden" value="' + nfile + '" name="' + uploaderId + '[]"/>'
            ].join('');
            holder.appendChild(div);
        }
    };
})(window, document, window.sedjs);