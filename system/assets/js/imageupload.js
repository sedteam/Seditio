/**
 * sedjs.imageUpload — deferred form image upload UI (preview, dropzone, sort tokens)
 * Requires: core.js (sedjs.extend, sedjs.sortable)
 */
(function(window, document) {
	'use strict';

	var sedjs = window.sedjs;
	if (!sedjs) {
		return;
	}

	var defaults = {
		prefix: 'images',
		maxFiles: 0,
		sortable: true,
		dropzone: true,
		urlUpload: true,
		accept: '',
		extensions: [],
		singleField: false,
		labels: {}
	};

	function fileAllowed(file, extensions) {
		if (!file || !file.name) {
			return false;
		}
		if (extensions && extensions.length) {
			var parts = file.name.split('.');
			var ext = parts.length > 1 ? parts.pop().toLowerCase() : '';
			return extensions.indexOf(ext) !== -1;
		}
		return file.type && file.type.match('image.*');
	}

	function extendOptions(userOptions, dataAttr) {
		var opts = sedjs.extend({}, defaults, userOptions || {});
		if (dataAttr) {
			try {
				var parsed = JSON.parse(dataAttr);
				opts = sedjs.extend(opts, parsed);
			} catch (e) {
				// ignore invalid JSON
			}
		}
		if (opts.maxFiles === 1) {
			opts.sortable = false;
			opts.singleField = true;
		}
		if (opts.maxFiles === 1 && opts.sortable !== false) {
			opts.sortable = false;
		}
		return opts;
	}

	function fieldName(prefix, suffix, singleField) {
		if (singleField && suffix === 'upload') {
			return prefix;
		}
		return prefix + suffix;
	}

	function ImageUploadInstance(container, options) {
		this.container = container;
		this.options = options;
		this.prefix = options.prefix;
		this.destroyed = false;

		this.list = container.querySelector('.sed-image-upload-list');
		this.dropzone = container.querySelector('.sed-image-upload-dropzone');
		this.addImageBox = container.querySelector('.sed-image-upload-add-box');
		this.urlLink = container.querySelector('.sed-image-upload-url-link .dash_link');
		this.form = container.closest('form');
		this.sortableInstance = null;
		this.submitHandler = null;
	}

	ImageUploadInstance.prototype.getKeepField = function() {
		return fieldName(this.prefix, '[]', false);
	};

	ImageUploadInstance.prototype.getSortField = function() {
		return fieldName(this.prefix, '_sort[]', false);
	};

	ImageUploadInstance.prototype.getUploadField = function() {
		return fieldName(this.prefix, '_upload[]', this.options.singleField);
	};

	ImageUploadInstance.prototype.getUrlsField = function() {
		return fieldName(this.prefix, '_urls[]', false);
	};

	ImageUploadInstance.prototype.getDropField = function() {
		if (this.options.singleField) {
			return this.prefix;
		}
		return this.prefix + '_drop[]';
	};

	ImageUploadInstance.prototype.countItems = function() {
		if (!this.list) {
			return 0;
		}
		return this.list.querySelectorAll('li:not(.sed-image-upload-add-tile)').length;
	};

	ImageUploadInstance.prototype.canAddMore = function() {
		if (this.options.maxFiles === 1) {
			return true;
		}
		if (this.options.maxFiles > 0 && this.countItems() >= this.options.maxFiles) {
			return false;
		}
		return true;
	};

	ImageUploadInstance.prototype.shouldShowAddSlot = function() {
		var count = this.countItems();
		if (this.options.maxFiles === 1) {
			return count === 0;
		}
		if (this.options.maxFiles > 0) {
			return count < this.options.maxFiles;
		}
		return true;
	};

	ImageUploadInstance.prototype.syncDropzone = function() {
		if (!this.dropzone || !this.options.dropzone) {
			return;
		}
		this.dropzone.style.display = this.shouldShowAddSlot() ? '' : 'none';
	};

	ImageUploadInstance.prototype.bindAddTileInput = function(input) {
		var self = this;
		if (!input || input.getAttribute('data-bound')) {
			return;
		}
		input.setAttribute('data-bound', '1');
		input.addEventListener('change', function() {
			if (!this.files || !this.files.length) {
				return;
			}
			if (self.options.singleField) {
				self.processFileList(this.files, this, false);
				return;
			}
			for (var i = 0; i < this.files.length; i++) {
				if (!self.canAddMore()) {
					break;
				}
				self.previewWizardFile(this.files[i], true);
			}
			var inputEl = this;
			inputEl.style.display = 'none';
			inputEl.style.position = 'absolute';
			inputEl.style.left = '-9999px';
			inputEl.style.width = '0';
			inputEl.style.height = '0';
			inputEl.className = 'sed-image-upload-addinput sed-image-upload-addinput-saved';
			if (self.addImageBox) {
				self.addImageBox.appendChild(inputEl);
			}
			var tile = inputEl.closest ? inputEl.closest('.sed-image-upload-add-tile') : null;
			if (tile) {
				var next = document.createElement('input');
				next.type = 'file';
				next.name = self.getDropField();
				next.multiple = true;
				next.className = 'sed-image-upload-addinput';
				next.accept = self.options.accept || 'image/*';
				tile.appendChild(next);
				self.bindAddTileInput(next);
			}
			self.notifyChange();
		});
	};

	ImageUploadInstance.prototype.createAddTile = function() {
		var li = document.createElement('li');
		li.className = 'sed-image-upload-add-tile';

		var icon = document.createElement('span');
		icon.className = 'sed-image-upload-add-icon';
		icon.setAttribute('aria-hidden', 'true');
		icon.textContent = '+';

		var label = document.createElement('span');
		label.className = 'sed-image-upload-add-label';
		label.textContent = this.options.labels.select || '+';

		var input = document.createElement('input');
		input.type = 'file';
		input.name = this.getDropField();
		input.className = 'sed-image-upload-addinput';
		input.accept = this.options.accept || 'image/*';
		if (!this.options.singleField) {
			input.multiple = true;
		}

		li.appendChild(icon);
		li.appendChild(label);
		li.appendChild(input);
		this.bindAddTileInput(input);
		return li;
	};

	ImageUploadInstance.prototype.syncAddTile = function() {
		if (!this.list || this.options.dropzone) {
			return;
		}

		var tile = this.list.querySelector('.sed-image-upload-add-tile');
		if (this.shouldShowAddSlot()) {
			if (!tile) {
				this.list.appendChild(this.createAddTile());
			}
		} else if (tile && tile.parentNode) {
			tile.parentNode.removeChild(tile);
		}
	};

	ImageUploadInstance.prototype.syncUI = function() {
		this.syncAddTile();
		this.syncDropzone();
	};

	ImageUploadInstance.prototype.notifyChange = function() {
		this.syncUI();
		if (typeof this.options.onChange === 'function') {
			this.options.onChange(this);
		}
	};

	ImageUploadInstance.prototype.bindDeleteLinks = function() {
		var self = this;
		var links = self.container.querySelectorAll('a.delete');
		for (var i = 0; i < links.length; i++) {
			if (links[i].getAttribute('data-bound')) {
				continue;
			}
			links[i].setAttribute('data-bound', '1');
			links[i].addEventListener('click', function(e) {
				e.preventDefault();
				var li = this.parentNode;
				if (li && li.parentNode) {
					li.parentNode.removeChild(li);
					self.notifyChange();
				}
			});
		}
	};

	ImageUploadInstance.prototype.clearList = function() {
		if (!this.list) {
			return;
		}
		var tiles = this.list.querySelectorAll('li:not(.sed-image-upload-add-tile)');
		for (var i = 0; i < tiles.length; i++) {
			if (tiles[i].parentNode) {
				tiles[i].parentNode.removeChild(tiles[i]);
			}
		}
	};

	ImageUploadInstance.prototype.processFileList = function(files, fileInput, addHidden) {
		var self = this;
		if (!files || !files.length) {
			return;
		}
		if (self.options.maxFiles === 1) {
			self.previewWizardFile(files[0], false, fileInput || null);
			return;
		}
		for (var i = 0; i < files.length; i++) {
			if (!self.canAddMore() && self.options.maxFiles !== 1) {
				break;
			}
			self.previewWizardFile(files[i], addHidden !== false, (fileInput && i === 0) ? fileInput : null);
		}
	};

	ImageUploadInstance.prototype.previewWizardFile = function(file, addHidden, fileInput) {
		var self = this;
		if (!fileAllowed(file, self.options.extensions)) {
			return;
		}
		if (!window.FileReader || !self.list) {
			return;
		}
		if (self.options.maxFiles === 1) {
			self.clearList();
		} else if (!self.canAddMore()) {
			return;
		}

		var reader = new FileReader();
		reader.onload = function(e) {
			var li = document.createElement('li');
			li.className = 'wizard';
			var del = document.createElement('a');
			del.href = '#';
			del.className = 'delete';
			del.title = self.options.labels.delete || 'Delete';
			var img = document.createElement('img');
			img.src = e.target.result;
			li.appendChild(del);
			li.appendChild(img);
			if (addHidden) {
				var hidden = document.createElement('input');
				hidden.type = 'hidden';
				hidden.name = self.getUrlsField();
				hidden.value = file.name;
				li.appendChild(hidden);
			}
			if (fileInput) {
				li.appendChild(fileInput);
			}

			var tile = self.list.querySelector('.sed-image-upload-add-tile');
			if (tile) {
				self.list.insertBefore(li, tile);
			} else {
				self.list.appendChild(li);
			}

			self.bindDeleteLinks();
			self.notifyChange();
		};
		reader.readAsDataURL(file);
	};

	ImageUploadInstance.prototype.pruneEmptyFileInputs = function() {
		var self = this;
		if (!self.form) {
			return;
		}

		var fieldNames = [];
		if (self.options.singleField) {
			fieldNames.push(self.prefix);
		} else {
			fieldNames.push(self.getDropField());
			fieldNames.push(self.getUploadField());
		}

		for (var n = 0; n < fieldNames.length; n++) {
			var fieldName = fieldNames[n];
			var inputs = self.form.querySelectorAll('input[type="file"][name="' + fieldName + '"]');
			if (!inputs.length) {
				continue;
			}

			var withFiles = [];
			for (var i = 0; i < inputs.length; i++) {
				var inp = inputs[i];
				if (inp.files && inp.files.length) {
					withFiles.push(inp);
				} else if (inp.parentNode && inp.className.indexOf('sed-image-upload-addinput') === -1 && inp.className.indexOf('sed-image-upload-dropinput') === -1) {
					inp.parentNode.removeChild(inp);
				}
			}

			if (self.options.singleField && withFiles.length > 1) {
				var keep = null;
				for (var j = 0; j < withFiles.length; j++) {
					if (self.list && self.list.contains(withFiles[j])) {
						keep = withFiles[j];
						break;
					}
				}
				if (!keep) {
					keep = withFiles[withFiles.length - 1];
				}
				for (var k = 0; k < withFiles.length; k++) {
					if (withFiles[k] !== keep && withFiles[k].parentNode) {
						withFiles[k].parentNode.removeChild(withFiles[k]);
					}
				}
			}
		}
	};

	ImageUploadInstance.prototype.buildSortKeys = function() {
		var self = this;
		var old = self.container.querySelectorAll('input.sed-image-upload-sort-key');
		for (var i = 0; i < old.length; i++) {
			if (old[i].parentNode) {
				old[i].parentNode.removeChild(old[i]);
			}
		}
		if (!self.list) {
			return;
		}

		var keepField = self.getKeepField();
		var urlsField = self.getUrlsField();
		var uploadField = self.getUploadField();
		var sortField = self.getSortField();
		var lis = self.list.querySelectorAll('li:not(.sed-image-upload-add-tile)');

		for (var j = 0; j < lis.length; j++) {
			var li = lis[j];
			var token = '';
			var idInp = li.querySelector('input[name="' + keepField + '"]');
			var urlInp = li.querySelector('input[name="' + urlsField + '"]');
			var fileInp = li.querySelector('input[name="' + uploadField + '"]');
			if (idInp) {
				token = 'id:' + idInp.value;
			} else if (urlInp && urlInp.value && urlInp.value !== 'http://') {
				token = 'url:' + urlInp.value;
			} else if (fileInp && fileInp.files && fileInp.files.length) {
				token = 'file:' + fileInp.files[0].name;
			}
			if (token !== '') {
				var inp = document.createElement('input');
				inp.type = 'hidden';
				inp.name = sortField;
				inp.className = 'sed-image-upload-sort-key';
				inp.value = token;
				li.appendChild(inp);
			}
		}
	};

	ImageUploadInstance.prototype.initSortable = function() {
		var self = this;
		if (!self.list || !self.options.sortable) {
			return;
		}
		if (typeof sedjs.sortable !== 'function') {
			return;
		}
		self.sortableInstance = sedjs.sortable(self.list, {
			items: 'li:not(.sed-image-upload-add-tile)',
			tolerance: 'pointer',
			placeholder: 'sortable-placeholder',
			helper: 'sortable-helper',
			update: function() {
				self.notifyChange();
			}
		});
	};

	ImageUploadInstance.prototype.initUrlLink = function() {
		var self = this;
		if (!self.urlLink || !self.addImageBox || !self.options.urlUpload) {
			return;
		}
		self.urlLink.addEventListener('click', function(e) {
			e.preventDefault();
			if (!self.canAddMore() && self.options.maxFiles !== 1) {
				return;
			}
			if (self.options.maxFiles === 1) {
				self.clearList();
			}
			var wrap = document.createElement('div');
			wrap.className = 'sed-image-upload-url-row';
			var input = document.createElement('input');
			input.type = 'text';
			input.name = self.getUrlsField();
			input.value = 'http://';
			input.className = 'text sed-image-upload-remote-input';
			input.style.width = '100%';
			input.style.marginTop = '6px';
			wrap.appendChild(input);
			self.addImageBox.appendChild(wrap);
			input.focus();
			input.select();
			self.notifyChange();
		});
	};

	ImageUploadInstance.prototype.initSelectButton = function() {
		var self = this;
		if (!self.dropzone) {
			return;
		}
		var btn = self.dropzone.querySelector('.sed-image-upload-select-btn');
		var input = self.dropzone.querySelector('.sed-image-upload-dropinput');
		if (!btn || !input) {
			return;
		}
		btn.addEventListener('click', function(e) {
			e.preventDefault();
			if (!self.shouldShowAddSlot()) {
				return;
			}
			input.click();
		});
	};

	ImageUploadInstance.prototype.bindDropInput = function(input) {
		var self = this;
		if (!input || input.getAttribute('data-bound')) {
			return;
		}
		input.setAttribute('data-bound', '1');
		input.addEventListener('change', function() {
			if (!this.files || !this.files.length) {
				return;
			}
			var inputEl = this;
			if (self.options.maxFiles === 1) {
				self.previewWizardFile(this.files[0], false, inputEl);
				return;
			}
			for (var i = 0; i < this.files.length; i++) {
				if (!self.canAddMore() && self.options.maxFiles !== 1) {
					break;
				}
				self.previewWizardFile(this.files[i], true);
			}
			inputEl.style.display = 'none';
			inputEl.style.position = 'absolute';
			inputEl.style.left = '-9999px';
			inputEl.style.width = '0';
			inputEl.style.height = '0';
			inputEl.className = 'sed-image-upload-dropinput sed-image-upload-dropinput-saved';
			var next = document.createElement('input');
			next.type = 'file';
			next.name = self.getDropField();
			next.multiple = !self.options.singleField;
			next.className = 'sed-image-upload-dropinput';
			next.accept = self.options.accept || 'image/*';
			if (self.dropzone) {
				self.dropzone.appendChild(next);
			}
			self.bindDropInput(next);
		});
	};

	ImageUploadInstance.prototype.initDropzone = function() {
		var self = this;
		if (!self.dropzone || !self.options.dropzone || !window.FileReader) {
			return;
		}

		self.dropzone.addEventListener('dragover', function(e) {
			e.preventDefault();
			self.dropzone.className = 'sed-image-upload-dropzone is-dragover';
		});
		self.dropzone.addEventListener('dragleave', function() {
			self.dropzone.className = 'sed-image-upload-dropzone';
		});
		self.dropzone.addEventListener('drop', function(e) {
			e.preventDefault();
			self.dropzone.className = 'sed-image-upload-dropzone';
			if (!self.shouldShowAddSlot()) {
				return;
			}
			var files = e.dataTransfer ? e.dataTransfer.files : null;
			if (!files || !files.length) {
				return;
			}
			if (self.options.maxFiles === 1) {
				var singleInput = self.dropzone.querySelector('.sed-image-upload-dropinput');
				if (singleInput && typeof DataTransfer !== 'undefined') {
					var dtSingle = new DataTransfer();
					dtSingle.items.add(files[0]);
					singleInput.files = dtSingle.files;
					self.previewWizardFile(files[0], false, singleInput);
				}
				return;
			}
			for (var i = 0; i < files.length; i++) {
				if (!self.canAddMore()) {
					break;
				}
				self.previewWizardFile(files[i], true);
			}
			if (typeof DataTransfer !== 'undefined' && self.addImageBox) {
				var dt = new DataTransfer();
				for (var j = 0; j < files.length; j++) {
					if (!fileAllowed(files[j], self.options.extensions)) {
						continue;
					}
					dt.items.add(files[j]);
				}
				if (dt.files.length > 0) {
					var hiddenInput = document.createElement('input');
					hiddenInput.type = 'file';
					hiddenInput.name = self.getDropField();
					hiddenInput.multiple = true;
					hiddenInput.className = 'sed-image-upload-dropinput sed-image-upload-dropinput-saved';
					hiddenInput.files = dt.files;
					hiddenInput.style.display = 'none';
					self.addImageBox.appendChild(hiddenInput);
				}
			}
			self.notifyChange();
		});

		var dropInput = self.dropzone.querySelector('.sed-image-upload-dropinput');
		if (dropInput) {
			self.bindDropInput(dropInput);
		}
	};

	ImageUploadInstance.prototype.init = function() {
		var self = this;
		self.bindDeleteLinks();
		self.initSortable();
		self.initUrlLink();
		self.initSelectButton();
		self.initDropzone();
		self.syncUI();

		if (self.form) {
			self.submitHandler = function() {
				self.pruneEmptyFileInputs();
				self.buildSortKeys();
			};
			self.form.addEventListener('submit', self.submitHandler);
		}
	};

	ImageUploadInstance.prototype.destroy = function() {
		if (this.destroyed) {
			return;
		}
		if (this.form && this.submitHandler) {
			this.form.removeEventListener('submit', this.submitHandler);
		}
		if (this.sortableInstance && typeof this.sortableInstance.destroy === 'function') {
			this.sortableInstance.destroy();
		}
		this.destroyed = true;
	};

	sedjs.imageUpload = {
		init: function(container, options) {
			if (!container) {
				return null;
			}
			var dataAttr = container.getAttribute('data-sed-image-upload');
			var opts = extendOptions(options, dataAttr);
			var instance = new ImageUploadInstance(container, opts);
			instance.init();
			return {
				destroy: function() {
					instance.destroy();
				},
				refresh: function() {
					instance.bindDeleteLinks();
					instance.syncUI();
					if (instance.sortableInstance && typeof instance.sortableInstance.update === 'function') {
						instance.sortableInstance.update();
					}
				},
				instance: instance
			};
		},

		initAll: function(root) {
			root = root || document;
			var blocks = root.querySelectorAll('.sed-image-upload[data-sed-image-upload]');
			var instances = [];
			for (var i = 0; i < blocks.length; i++) {
				var api = sedjs.imageUpload.init(blocks[i]);
				if (api) {
					instances.push(api);
				}
			}
			return instances;
		}
	};

	sedjs.ready(function() {
		sedjs.imageUpload.initAll();
	});

})(window, document);
