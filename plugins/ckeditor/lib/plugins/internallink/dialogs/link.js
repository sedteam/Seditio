'use strict';
( function() {
	CKEDITOR.dialog.add( 'internallink', function( editor ) {		
		var plugin = CKEDITOR.plugins.internallink,
			initialLinkText;

		function createRangeForLink( editor, link ) {
			var range = editor.createRange();

			range.setStartBefore( link );
			range.setEndAfter( link );

			return range;
		}

		//Adds a new link at caret position
		function insertLinksIntoSelection( editor, data ) {
			var attributes = plugin.getLinkAttributes( editor, data ),
				ranges = editor.getSelection().getRanges(),
				style = new CKEDITOR.style( {
					element: 'a',
					attributes: attributes.set
				} ),
				rangesToSelect = [],
				range,
				text,
				nestedLinks,
				i,
				j;

			style.type = CKEDITOR.STYLE_INLINE;

			for ( i = 0; i < ranges.length; i++ ) {
				range = ranges[ i ];

				// Use link URL as text with a collapsed cursor.
				if ( range.collapsed ) {
					text = new CKEDITOR.dom.text( data.linkText || attributes.set[ 'data-cke-saved-href' ], editor.document );
					range.insertNode( text );
					range.selectNodeContents( text );
				} else if ( initialLinkText !== data.linkText ) {
					text = new CKEDITOR.dom.text( data.linkText, editor.document );

					// Shrink range to preserve block element.
					range.shrink( CKEDITOR.SHRINK_TEXT );

					// Use extractHtmlFromRange to remove markup within the selection. Also this method is a little
					// smarter than range#deleteContents as it plays better e.g. with table cells.
					editor.editable().extractHtmlFromRange( range );

					range.insertNode( text );
				}

				// Editable links nested within current range should be removed, so that the link is applied to whole selection.
				nestedLinks = range._find( 'a' );

				for	( j = 0; j < nestedLinks.length; j++ ) {
					nestedLinks[ j ].remove( true );
				}


				// Apply style.
				style.applyToRange( range, editor );

				rangesToSelect.push( range );
			}

			editor.getSelection().selectRanges( rangesToSelect );
		}

		//Edits the current selection
		function editLinksInSelection( editor, selectedElements, data ) {
			var attributes = plugin.getLinkAttributes( editor, data ),
				ranges = [],
				element,
				href,
				textView,
				newText,
				i;

			for ( i = 0; i < selectedElements.length; i++ ) {
				// We're only editing an existing link, so just overwrite the attributes.
				element = selectedElements[ i ];
				href = element.data( 'cke-saved-href' );
				textView = element.getHtml();

				element.setAttributes( attributes.set );
				element.removeAttributes( attributes.removed );


				if ( data.linkText && initialLinkText != data.linkText ) {
					// Display text has been changed.
					newText = data.linkText;
				} else if ( href == textView && textView.indexOf( '@' ) != -1 ) {
					newText = attributes.set[ 'data-cke-saved-href' ];
				}

				if ( newText ) {
					element.setText( newText );
				}

				ranges.push( createRangeForLink( editor, element ) );
			}

			// We changed the content, so need to select it again.
			editor.getSelection().selectRanges( ranges );
		}

				
		var KEYS = {
			ENTER	: 13,
			UP		: 38,
			DOWN	: 40,
		}

		function Autocomplete(config) {
			this.textbox = config.textbox;
			this.onSearch = config.onSearch;

			//Because it is a timeoput function, bind the object
			this.onSearchTimeout = this.onSearchTimeout.bind(this);
			this.onkeydown = this.onkeydown.bind(this);
			this.itemSelectCB = this.itemSelectCB.bind(this);

			//Create area
			this.area = new Area({
				width: this.textbox.offsetWidth,
				itemSelectCB: this.itemSelectCB
			});
			this.area.getNode().setAttribute('data-empty', editor.lang.internallink.empty + ' "' + this.textbox.value + '"');

			//Once all set, call inititialize
			this.init();
			//Add events
			this.attachEvents();
		}

		Autocomplete.Destroy = function(aInst) {
			aInst.detachEvents();
			aInst.textbox.parentNode.removeChild(aInst.area.getNode());

			Area.Destroy(aInst.area);
			aInst.area = null;

			aInst.itemSelectCB = null;
			aInst.onSearchTimeout = null;
			aInst.onkeydown = null;

			aInst.onSearch = null;
			aInst.textbox = null;
		};

		Autocomplete.prototype.init = function() {
			this.textbox.setAttribute('type', 'search');
			this.textbox.parentNode.appendChild(this.area.getNode());
		};

		Autocomplete.prototype.attachEvents = function() {
			this.textbox.addEventListener('keydown', this.onkeydown)
		};

		Autocomplete.prototype.detachEvents = function() {
			this.textbox.removeEventListener('keydown', this.onkeydown)
		};

		Autocomplete.prototype.onkeydown = function(event) {
			event = event || window.event;
			switch(event.keyCode) {
				case KEYS.DOWN: 	event.preventDefault(); this.area.move(1);	break;
				case KEYS.UP:		event.preventDefault(); this.area.move(-1);	break;
				case KEYS.ENTER:	event.preventDefault(); break;
				default:
					this.delaySearch();
			}
		};

		Autocomplete.prototype.delaySearch = function() {
			this.timeoutCount && clearTimeout(this.timeoutCount);
			this.timeoutCount = setTimeout(this.onSearchTimeout, 500);
		};

		Autocomplete.prototype.onSearchTimeout = function() {
			this.timeoutCount = null;
			this.area.getNode().setAttribute('data-empty', editor.lang.internallink.empty + ' "' + this.textbox.value + '"');
			this.onSearch();
		};

		Autocomplete.prototype.setData = function() {
			this.area.setData.apply(this.area, arguments);
		};

		Autocomplete.prototype.itemSelectCB = function(item, bSelect) {
			bSelect && 
			(this.textbox.value = item[0]);
			this.textbox.focus();
			this.selected = item;
			
			bSelect &&
			document.getElementById(this.dialog.getButton('ok').domId).click();
		};


		/**
			Displays just below to the textbox as a sibling
			Responsible to show the items
			capable of highlighting items
			Returns the selected items to the callback
		*/
		function Area(config) {
			//Do it first, to get decent time to prepare rule tree
			this.loadCSS()

			this.width = config.width;
			this.itemSelectCB = config.itemSelectCB;

			//Bind context for event listeners
			this.onItemSelect = this.onItemSelect.bind(this);
			this.onmouseover = this.onmouseover.bind(this);

			this.items = [];
			this.current = null;

			this.node = this.init();
			this.attachEvents();
			
		}

		Area.Destroy = function(aInst) {
			aInst.detachEvents();
			aInst.node = null;
			aInst.items = null;

			aInst.onmouseover = null;
			aInst.onItemSelect = null;

			aInst.unloadCSS();
		};

		Area.prototype.init = function() {
			var node = document.createElement('ul');
			node.className = 'internal-links';
			node.style.width = this.width + 'px';
			node.style.minHeight = '180px';
			return node;
		};

		Area.prototype.attachEvents = function() {
			this.node.addEventListener('click', this.onItemSelect);
			this.node.addEventListener('mouseover', this.onmouseover);
		};

		Area.prototype.detachEvents = function() {
			this.node.removeEventListener('mouseover', this.onmouseover);
			this.node.removeEventListener('click', this.onItemSelect);
		};

		Area.prototype.onItemSelect = function(event) {
			this.highlight(true);
		};

		Area.prototype.onmouseover = function(event) {
			event = event || window.event;

			this.dehighlight();
			this.current = (event.target || event.srcElement).index;
			this.highlight(false);
		};


		Area.prototype.getNode = function() {
			return this.node;
		};

		Area.prototype.getItemNode = function(item, index) {
			var node = document.createElement('li');
			node.className = 'internal-link';
			node.innerHTML = node.title = item[0];
			node.index = index;
			return node;
		};

		Area.prototype.setData = function(items) {
			this.resetData();
			this.items = items;
			this.generateItems();
		};

		Area.prototype.resetData = function(items) {
			this.removeItems();
			this.items = [];
			this.current = null;
		};

		Area.prototype.generateItems = function(items) {
			this.items
				.map(this.getItemNode)
				.forEach(this.node.appendChild.bind(this.node));
		};

		Area.prototype.removeItems = function(items) {
			while(this.node.childNodes.length) {
				this.node.removeChild(this.node.childNodes[0])
			}
		};

		Area.prototype.addBy = function(n) {
			if(this.current === null) {
				if(n < 0) {
					this.current = 0;
				} else {
					this.current = -1;
				}	
			}

			//Add by
			this.current += n;

			//Valid value is 0 - length-1
			this.current =  ( this.items.length + this.current ) % this.items.length
		};


		Area.prototype.move = function(n) {
			this.dehighlight();
			this.addBy(n);
			this.highlight(false);
		};

		Area.prototype.highlight = function(bSelect) {
			this.node.childNodes[this.current] &&
			(this.node.childNodes[this.current].className = 'internal-link highlight') &&
			this.scrollIntoViewIfNeeded(this.node.childNodes[this.current]);

			this.itemSelectCB(this.items[this.current], bSelect);
		};

		Area.prototype.dehighlight = function(items) {
			this.node.childNodes[this.current] &&
			(this.node.childNodes[this.current].className = 'internal-link');
		};

		Area.prototype.loadCSS = function(){
			this.styleSheetNode = document.createElement('style');
			this.styleSheetNode.setAttribute('type', 'text/css');
			this.styleSheetNode.setAttribute('id', 'internal-link-autocomplete');
			document.getElementsByTagName("head")[0].appendChild(this.styleSheetNode);

			var s_js = document.styleSheets[document.styleSheets.length-1];
			Area.STYLES.forEach(s_js.insertRule.bind(s_js));
		};

		Area.prototype.unloadCSS = function(){
			this.styleSheetNode.parentNode.removeChild(this.styleSheetNode);
			this.styleSheetNode = null;
		};
		
		Area.prototype.scrollIntoViewIfNeeded = function(node) {
			var parent = node.parentNode,
				overTop = node.offsetTop - parent.scrollTop,
				overBottom = (node.offsetTop + node.clientHeight) - (parent.scrollTop + parent.clientHeight);
				
			if (overTop < 0) {
				parent.scrollTop += overTop;
			} else if(overBottom > 0) {
				parent.scrollTop += overBottom;
			}
		};

		Area.STYLES = [
			[".internal-links {",
				"border: 1px none  #bcbcbc!important;",
				"margin: 0px!important;",
				"margin-top: 10px!important;",
				"margin-left: 1px!important;",
				"max-height: 300px!important;",
				"overflow: auto;",
				"padding: 0px!important;",
				"position: relative;",
                "background-color: #fff",
			"}"].join('\n'),
			
			[".internal-links:empty:after {",
				"content: attr(data-empty);",
				"color: #8a8a8a;",
				"position: absolute;",
				"margin-top: -1em;",
				"top: 50%;",
				"right: 1%;",
				"left: 1%;",
				"text-align: center;",
			"}"].join('\n'),

			[".internal-links>.internal-link {",
				"padding: 8px !important;",
				"list-style-type: none;",
				"text-overflow: ellipsis;",
				"width: 100%!important;",
				"overflow: hidden;",
				"pointer-events: auto;",
				"box-sizing: border-box;",
				"cursor: pointer;",
                "border-top: 1px solid #d5d5d5",
			"}"].join('\n'),

			[".internal-link.highlight {",
				"background: #9c9c9c;",
				"color: #ffffff;",
			"}"].join('\n')
		]



		return {
			title: editor.lang.internallink.title,
			minWidth: ( CKEDITOR.skinName || editor.config.skin ) == 'moono-lisa' ? 450 : 350,
			minHeight: 240,
			contents: [ {
				id: 'info',
				label: 'Internal Link',
				title: 'Internal Link',
				elements: [{
					type: 'text',
					id: 'linkDisplayText',
					label: editor.lang.internallink.link,
					onChange: function() {},
					setup: function(data) {
						if(editor.config.internallinkHideDisplayText) {
							this.getElement().hide();
						} else {
							this.setValue( editor.getSelection().getSelectedText() );
						}
					},
					commit: function( data ) {
						data.linkText = editor.config.internallinkHideDisplayText ? '' : this.getValue();
					}
				}, {
					type: 'text',
					id: 'linkSearchText',
					label: editor.lang.internallink.search,
					onChange: function() {
						
					},
					setup: function(data) {

						this.setValue( editor.getSelection().getSelectedText() );

						// Keep inner text so that it can be compared in commit function. By obtaining value from getData()
						// we get value stripped from new line chars which is important when comparing the value later on.
						initialLinkText = this.getValue();
						
						var url = "";
						if(data.url) 
							url = (data.url.protocol || "") + (data.url.url || "");
						
						if(url)
							this.url = url;

					},
					commit: function( data ) {}
				}, {
					type: 'html',
					html: '<div id="resultsArea"></div>'
				}]
			}],
			onShow: function() {
				var editor = this.getParentEditor(),
					selection = editor.getSelection(),
					elements = plugin.getSelectedLink( editor, true ),
					firstLink = elements[ 0 ] || null;

				// Fill in all the relevant fields if there's already one link selected.
				if ( firstLink && firstLink.hasAttribute( 'href' ) ) {
					// Don't change selection if some element is already selected.
					// For example - don't destroy fake selection.
					if ( !selection.getSelectedElement() && !selection.isInTable() ) {
						selection.selectElement( firstLink );
					}
				}

				var data = plugin.parseLinkAttributes( editor, firstLink );

				// Record down the selected element in the dialog.
				this._.selectedElements = elements;

				this.setupContent( data );
				
				function getLinks() {
					var that = this;
					CKEDITOR.plugins.internallink.getLinks(editor, {
						query: this.autocomplete.textbox.value,
						c: data.url.url
					}, function(err, links) {
						if(err) {
							console.error("Error while fetching links:" + err);
						} else {
							that.autocomplete.setData(links);
						}
					});
				}
				getLinks = getLinks.bind(this);
				this.autocomplete = (new Autocomplete({
					textbox: this.getContentElement('info', 'linkSearchText').getInputElement().$,
					onSearch: getLinks
				}));
										
				this.autocomplete.selected = [this.autocomplete.textbox.value, this.url || ""];
				this.autocomplete.dialog = this;
										
				getLinks();
			},
			onOk: function() {
				var data = {};

				// Collect data from fields.
				this.commitContent( data );

				data.linkText = data.linkText || this.autocomplete.selected[0];
						
				if ( !data.url )
					data.url = {};

				data.type = 'url';
				data.url.protocol = '';
				data.url.url = this.autocomplete.selected[1];
				
				Autocomplete.Destroy(this.autocomplete);
				this.autocomplete = null;

				if(!data.url.url) {
					//Nothing selected, nothing to do.
					delete this._.selectedElements;
				} else if ( !this._.selectedElements.length ) {
					insertLinksIntoSelection( editor, data );
				} else {
					editLinksInSelection( editor, this._.selectedElements, data );

					delete this._.selectedElements;
				}
			},
			onCancel: function() {
				Autocomplete.Destroy(this.autocomplete);
				this.autocomplete = null;
			},
			onLoad: function() {},
			// Inital focus on 'Display text' field.
			onFocus: function() {
				var linkDisplayText = this.getContentElement('info', 'linkDisplayText');
				linkDisplayText.select();
			}
		};
	} );
} )();
