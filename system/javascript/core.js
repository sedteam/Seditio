var sedjs = {
    /**
     * Open a popup window or modal with specified content.
     * @param {string} code - The code or identifier for the content to display.
     * @param {number} w - The width of the popup window.
     * @param {number} h - The height of the popup window.
     * @param {boolean} modal - Whether to open the content in a modal window.
     */
    popup: function(code, w, h, modal) {
        if (!modal) {
            window.open(sedjs.get_basehref() + 'plug?o=' + code, '', 'toolbar=0,location=0,directories=0,menuBar=0,resizable=0,scrollbars=yes,width=' + w + ',height=' + h + ',left=32,top=16');
        } else {
            window.modal = sedjs.modal.open('popup', 'iframe', sedjs.get_basehref() + 'plug?o=' + code, "Popup", 'width=' + w + 'px,height=' + h + 'px,resize=1,scrolling=1,center=1', 'load');
        }
    },

    /**
     * Open a PFS (Personal File Storage) window or modal.
     * @param {string} id - The user ID for the PFS.
     * @param {string} c1 - Additional parameter for the PFS.
     * @param {string} c2 - Additional parameter for the PFS.
     * @param {boolean} modal - Whether to open the PFS in a modal window.
     */
    pfs: function(id, c1, c2, modal) {
        if (!modal) {
            window.open(sedjs.get_basehref() + 'pfs?userid=' + id + '&c1=' + c1 + '&c2=' + c2, 'PFS', 'status=1, toolbar=0,location=0,directories=0,menuBar=0,resizable=1,scrollbars=yes,width=990,height=600,left=32,top=16');
        } else {
            window.modal = sedjs.modal.open("pfs", "iframe", sedjs.get_basehref() + 'pfs?userid=' + id + '&c1=' + c1 + '&c2=' + c2, "PFS", "width=990px,height=600px,resize=1,scrolling=1,center=1", "load");
        }
    },

    /**
     * Open a help window or modal with specified content.
     * @param {string} rcode - The help code or identifier.
     * @param {string} c1 - Additional parameter for the help content.
     * @param {string} c2 - Additional parameter for the help content.
     * @param {boolean} modal - Whether to open the help content in a modal window.
     */
    help: function(rcode, c1, c2, modal) {
        if (!modal) {
            window.open(sedjs.get_basehref() + 'plug?h=' + rcode + '&c1=' + c1 + '&c2=' + c2, 'Help', 'toolbar=0,location=0,directories=0,menuBar=0,resizable=0,scrollbars=yes,width=480,height=512,left=32,top=16');
        } else {
            window.modal = sedjs.modal.open("help", "iframe", sedjs.get_basehref() + 'plug?h=' + rcode + '&c1=' + c1 + '&c2=' + c2, "Help", "width=500px,height=520px,resize=1,scrolling=1,center=1", "load");
        }
    },

    /**
     * Open a polls window or modal with specified content.
     * @param {string} rcode - The poll code or identifier.
     * @param {boolean} modal - Whether to open the polls content in a modal window.
     */
    polls: function(rcode, modal) {
        if (!modal) {
            window.open(sedjs.get_basehref() + 'polls?stndl=1&id=' + rcode, 'Polls', 'toolbar=0,location=0,directories=0,menuBar=0,resizable=0,scrollbars=yes,width=608,height=448,left=16,top=16');
        } else {
            window.modal = sedjs.modal.open("polls", "iframe", sedjs.get_basehref() + 'polls?stndl=1&id=' + rcode, "Polls", "width=610px,height=450px,resize=1,scrolling=1,center=1", "load");
        }
    },

    /**
     * Open a poll voting window or modal with specified content.
     * @param {string} rcode - The poll code or identifier.
     * @param {string} rvote - The vote identifier.
     * @param {boolean} modal - Whether to open the poll voting content in a modal window.
     */
    pollvote: function(rcode, rvote, modal) {
        if (!modal) {
            window.open(sedjs.get_basehref() + 'polls?a=send&stndl=1&id=' + rcode + '&vote=' + rvote, 'Polls', 'toolbar=0,location=0,directories=0,menuBar=0,resizable=0,scrollbars=yes,width=608,height=448,left=16,top=16');
        } else {
            window.modal = sedjs.modal.open("pollvote", "iframe", sedjs.get_basehref() + 'polls?a=send&stndl=1&id=' + rcode + '&vote=' + rvote, "Polls", "width=610px,height=450px,resize=1,scrolling=1,center=1", "load");
        }
    },

    /**
     * Open a picture window or modal with specified content.
     * @param {string} url - The URL of the picture.
     * @param {number} sx - The width of the picture window.
     * @param {number} sy - The height of the picture window.
     * @param {boolean} modal - Whether to open the picture in a modal window.
     */
    picture: function(url, sx, sy, modal) {
        if (!modal) {
            var ptop = (window.screen.height - 200) / 2;
            var pleft = (window.screen.width - 200) / 2;
            window.open(sedjs.get_basehref() + 'pfs?m=view&v=' + url, 'Picture', 'toolbar=0,location=0,status=0, directories=0,menubar=0,resizable=1,scrollbars=yes,width=' + sx + ',height=' + sy + ',left=' + pleft + ',top=' + ptop + '');
        } else {
            var imglink = 'datas/users/' + url;
            var randid = imglink.replace(/[^a-z0-9]/gi, '');
            window.modal = sedjs.modal.open('img-' + randid, 'image', imglink, 'Picture', 'resize=0, scrolling=0, center=1', 'load');
        }
    },

    /**
     * Redirect the browser to a specified URL.
     * @param {HTMLElement} url - The select element containing the URL options.
     */
    redirect: function(url) {
        location.href = url.options[url.selectedIndex].value;
    },

    /**
     * Display a confirmation dialog with a specified message.
     * @param {string} mess - The message to display in the confirmation dialog.
     * @returns {boolean} Whether the user confirmed the action.
     */
    confirmact: function(mess) {
        if (confirm(mess)) {
            return true;
        } else {
            return false;
        }
    },

    /**
     * Get the base href for the current page.
     * @returns {string} The base href.
     */
    get_basehref: function() {
        var loc = "";
        var baseElement = document.querySelector('base');
        if (baseElement && baseElement.href) {
            if (baseElement.href.substr(baseElement.href.length - 1) === '/' && loc.charAt(0) === '/') {
                loc = loc.substr(1);
            }
            loc = baseElement.href + loc;
        }
        return loc;
    },

    /**
     * Toggle the visibility of a block element.
     * @param {string} id - The ID of the block element to toggle.
     */
    toggleblock: function(id) {
        var block = document.querySelector('#' + id);
        if (block) {
            block.style.display = block.style.display === 'none' ? '' : 'none';
        }
    },

    /**
     * Initialize Tabs functionality with specified settings.
     * @param {Object} settings - The settings for the tab functionality.
     */
    sedtabs: function(settings) {
        var getElementOrElements = function(identifier) {
            if (identifier.charAt(0) === '#') {
                var byId = document.querySelector(identifier);
                return byId ? [byId] : [];
            } else {
                return document.querySelectorAll('.' + identifier);
            }
        };

        var bindFunction = function(func) {
            var context = this;
            return function() {
                return func.apply(context, arguments);
            };
        };

        var applyToAllElements = function(func, elements, args) {
            for (var i = 0; i < elements.length; i++) {
                func.apply(elements[i], args || []);
            }
        };

        var addClass = function(className) {
            this.classList.add(className);
            var tabTitle = this.getAttribute('data-tabtitle');
            if (tabTitle && document.querySelector('.tab-title')) {
                document.querySelector('.tab-title').textContent = tabTitle;
            }
        };

        var removeClass = function(className) {
            this.classList.remove(className);
        };

        var hideElement = function() {
            this.style.display = 'none';
        };

        var showElement = function() {
            this.style.display = 'block';
        };

        var switchTab = function(tabId, tabLinks, tabContents, settings) {
            applyToAllElements(removeClass, tabLinks, [settings.selectedClass]);
            addClass.call(this, settings.selectedClass);
            applyToAllElements(hideElement, tabContents);
            applyToAllElements(showElement, document.querySelectorAll('#' + tabId));
        };

        var initTabs = function(config) {
            var mergedConfig = config || {};
            var defaultSettings = {
                containerClass: 'sedtabs',
                eventType: 'click',
                selectedClass: 'selected',
                defaultTabIndex: 0,
                beforeSwitchCallback: false
            };

            for (var key in defaultSettings) {
                if (defaultSettings.hasOwnProperty(key)) {
                    mergedConfig[key] = mergedConfig[key] || defaultSettings[key];
                }
            }

            var tabContainers = document.querySelectorAll('.' + mergedConfig.containerClass);

            var handleTabClick = function() {
                var clickedTab = this;
                var tabLinks = this.tabLinks;
                var tabContents = this.tabContents;

                if (!mergedConfig.beforeSwitchCallback ||
                    mergedConfig.beforeSwitchCallback.apply(clickedTab, this.callbackArgs) !== false) {
                    switchTab.apply(clickedTab, this.callbackArgs);
                }
                return false;
            };

            for (var i = 0; i < tabContainers.length; i++) {
                var container = tabContainers[i];
                var tabLinks = container.querySelectorAll('a');
                var tabIds = [];
                var tabs = [];
                var tabContents = [];

                for (var j = 0; j < tabLinks.length; j++) {
                    if (tabLinks[j].href.match(/#tab/)) {
                        var tabId = tabLinks[j].href.split('#')[1];
                        tabIds.push(tabId);

                        if (typeof mergedConfig.defaultTabIndex === 'string' &&
                            tabId === mergedConfig.defaultTabIndex) {
                            mergedConfig.defaultTabIndex = j;
                        }

                        tabs.push(tabLinks[j]);

                        var contentElements = document.querySelectorAll('#' + tabId);
                        for (var k = 0; k < contentElements.length; k++) {
                            tabContents.push(contentElements[k]);
                        }
                    }
                }

                for (var j = 0; j < tabs.length; j++) {
                    var callbackArgs = [
                        tabIds[j],
                        tabs,
                        tabContents,
                        mergedConfig
                    ];

                    tabs[j].tabLinks = tabs;
                    tabs[j].tabContents = tabContents;
                    tabs[j].callbackArgs = callbackArgs;

                    tabs[j]['on' + mergedConfig.eventType] =
                        bindFunction.call(tabs[j], handleTabClick);
                }

                if (typeof mergedConfig.defaultTabIndex === 'number' &&
                    mergedConfig.defaultTabIndex >= 0) {
                    switchTab.call(
                        tabs[mergedConfig.defaultTabIndex],
                        tabIds[mergedConfig.defaultTabIndex],
                        tabs,
                        tabContents,
                        mergedConfig
                    );
                }
            }
        };

        initTabs(settings);
    },

    /**
     * Open a modal window with an image thumbnail when a link with a specific rel attribute is clicked.
     * @param {string} rel - The rel attribute value to target.
     */
    getrel: function(rel) {
        var pageLinks = document.querySelectorAll('a[rel="' + rel + '"]');
        Array.prototype.forEach.call(pageLinks, function(link) {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                var imgLink = link.getAttribute('href');
                var imgTitle = link.getAttribute('title') || 'Picture';
                var randId = imgLink.replace(/[^a-z0-9]/gi, '');
                sedjs.modal.open('img-' + randId, 'image', imgLink, imgTitle, 'resize=0,scrolling=0,center=1', 'load');
            });
        });
    },

    /**
     * Perform an AJAX request with specified options.
     * @param {Object} options - The options for the AJAX request.
     * @returns {XMLHttpRequest} The XMLHttpRequest object.
     */
    ajax: function(options) {
        var settings = {
            url: '',
            method: 'GET',
            data: null,
            dataType: 'text',
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            headers: {},
            timeout: 0,
            cache: true,
            async: true,
            context: null,
            beforeSend: null,
            success: null,
            error: null,
            complete: null
        };

        for (var key in options) {
            if (options.hasOwnProperty(key)) {
                settings[key] = options[key];
            }
        }

        var xhr = new XMLHttpRequest();
        var timer;
        var url = settings.url;
        var method = settings.method.toUpperCase();
        var isGet = method === 'GET';

        if (typeof settings.data === 'object' && !(settings.data instanceof FormData)) {
            settings.data = sedjs.serialization(settings.data);
        }

        if (isGet && settings.data) {
            url += (url.indexOf('?') === -1 ? '?' : '&') + settings.data;
        }

        if (!settings.cache && isGet) {
            url += (url.indexOf('?') === -1 ? '?' : '&') + '_=' + Date.now();
        }

        xhr.open(method, url, settings.async);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        if (!isGet && !(settings.data instanceof FormData)) {
            xhr.setRequestHeader('Content-Type', settings.contentType);
        }
        for (var header in settings.headers) {
            xhr.setRequestHeader(header, settings.headers[header]);
        }

        if (settings.timeout > 0) {
            timer = setTimeout(function() {
                xhr.abort();
                handleError('timeout', 'Request timed out');
            }, settings.timeout);
        }

        if (typeof settings.beforeSend === 'function') {
            if (settings.beforeSend.call(settings.context, xhr, settings) === false) {
                xhr.abort();
                return xhr;
            }
        }

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                clearTimeout(timer);
                var status = xhr.status;
                var response = parseResponse();

                if (status >= 200 && status < 300 || status === 304) {
                    handleSuccess(response);
                } else {
                    handleError(status, xhr.statusText);
                }
                handleComplete(response);
            }
        };

        xhr.send(isGet ? null : settings.data);

        function parseResponse() {
            var response = xhr.responseText;

            switch (settings.dataType.toLowerCase()) {
                case 'json':
                    try {
                        return JSON.parse(response);
                    } catch (e) {
                        return response;
                    }
                case 'xml':
                    return xhr.responseXML;
                case 'script':
                    (1, eval)(response);
                    return response;
                default:
                    return response;
            }
        }

        function handleSuccess(response) {
            if (typeof settings.success === 'function') {
                settings.success.call(settings.context, response, xhr.status, xhr);
            }
        }

        function handleError(status, error) {
            if (typeof settings.error === 'function') {
                settings.error.call(settings.context, xhr, status, error);
            }
        }

        function handleComplete(response) {
            if (typeof settings.complete === 'function') {
                settings.complete.call(settings.context, xhr, status);
            }
        }

        return xhr;
    },

    /**
     * Serialize an object into a query string.
     * @param {Object} data - The data object to serialize.
     * @returns {string} The serialized query string.
     */
    serialization: function(data) {
        var pairs = [];

        function buildParams(prefix, obj) {
            if (Array.isArray(obj)) {
                obj.forEach(function(value, i) {
                    if (/\[\]$/.test(prefix)) {
                        add(prefix, value);
                    } else {
                        buildParams(prefix + '[' + (typeof value === 'object' ? i : '') + ']', value);
                    }
                });
            } else if (typeof obj === 'object') {
                for (var key in obj) {
                    if (obj.hasOwnProperty(key)) {
                        buildParams(prefix ? prefix + '[' + key + ']' : key, obj[key]);
                    }
                }
            } else {
                add(prefix, obj);
            }
        }

        function add(key, value) {
            pairs.push(
                encodeURIComponent(key) + '=' +
                encodeURIComponent(value == null ? '' : value)
            );
        }

        if (typeof data === 'object') {
            buildParams('', data);
        }

        return pairs.join('&').replace(/%20/g, '+');
    },

    /**
     * Bind form data to an AJAX request.
     * @param {Object} userOptions - The user options for the AJAX request.
     */
    ajaxbind: function(userOptions) {
        var defaults = {
            url: '',
            format: 'html',
            method: 'POST',
            update: null,
            loading: null,
            formid: null,
            onSuccess: null,
            onError: null
        };

        var options = Object.assign({}, defaults, userOptions);

        var formElement = options.formid ? document.querySelector(options.formid) : null;
        var updateElement = options.update ? document.querySelector(options.update) : null;
        var loadingElement = options.loading ? document.querySelector(options.loading) : null;

        var formData = new FormData();

        if (formElement) {
            var elements = formElement.elements;
            for (var i = 0; i < elements.length; i++) {
                var element = elements[i];
                if (!element.name || element.disabled) continue;

                if (element.type === 'file') {
                    Array.from(element.files).forEach(function(file) {
                        formData.append(element.name, file);
                    });
                } else if (element.type === 'checkbox' || element.type === 'radio') {
                    if (element.checked) formData.append(element.name, element.value);
                } else if (element.tagName === 'SELECT') {
                    if (element.multiple) {
                        Array.from(element.selectedOptions).forEach(function(opt) {
                            formData.append(element.name, opt.value);
                        });
                    } else {
                        formData.append(element.name, element.value);
                    }
                } else {
                    formData.append(element.name, element.value);
                }
            }
        }

        var loaderDiv = null;
        if (loadingElement) {
            loaderDiv = document.createElement("div");
            var intElemOffsetHeight = Math.floor(loadingElement.offsetHeight / 2) + 16;
            var intElemOffsetWidth = Math.floor(loadingElement.offsetWidth / 2) - 16;
            loaderDiv.setAttribute("style", "position:absolute; margin-top:-" + intElemOffsetHeight + "px; margin-left:" + intElemOffsetWidth + "px;");
            loaderDiv.setAttribute("class", "loading-indicator");
            loadingElement.appendChild(loaderDiv);
            options.loading = loaderDiv;
        }

        sedjs.ajax({
            url: options.url,
            method: options.method,
            data: formData,
            contentType: false,
            processData: false,
            dataType: options.format,
            success: function(response) {
                if (updateElement) {
                    if (options.format === 'html') {
                        updateElement.innerHTML = response;
                    } else {
                        updateElement.textContent = response;
                    }
                }

                if (typeof options.onSuccess === 'function') {
                    options.onSuccess(response);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                if (typeof options.onError === 'function') {
                    options.onError(xhr, status, error);
                }
            },
            complete: function() {
                if (loaderDiv && loaderDiv.parentNode) {
                    loaderDiv.parentNode.removeChild(loaderDiv);
                }
            }
        });
    },

    /**
     * Modal Windows functions
     */
    modal: {
        // Configuration parameters
        imagefiles: [ // Paths to control icons
            '/system/img/vars/min.gif',
            '/system/img/vars/close.gif',
            '/system/img/vars/restore.gif',
            '/system/img/vars/resize.gif'
        ],
        maxheightimage: 600, // Max. image height
        maxwidthimage: 800, // Max. image width
        minimizeorder: 0, // Minimization order of windows
        zIndexvalue: 1000, // Base z-index
        tobjects: [], // Storage for created windows
        lastactivet: {}, // Last active window
        constrainToViewport: false, // Whether to constrain window movement within the viewport

        /**
         * Initialize a new modal window.
         * @param {string} t - The ID of the modal window to be created.
         * @returns {HTMLElement|null} The created modal window element, or null if it couldn't be created.
         */
        init: function(t) {
            var domwindow = document.createElement("div");
            domwindow.id = t;
            domwindow.className = "sed_modal";

            var domwindowdata = [
                '<div class="modal-handle">',
                'Modal Window',
                '<div class="modal-controls">',
                '<img src="' + this.imagefiles[0] + '" title="Minimize" />',
                '<img src="' + this.imagefiles[1] + '" title="Close" />',
                '</div>',
                '</div>',
                '<div class="modal-contentarea" id="area-' + t + '"></div>',
                '<div class="modal-statusarea">',
                '<div class="modal-resizearea" style="background: transparent url(' + this.imagefiles[3] + ') top right no-repeat;">&nbsp;</div>',
                '</div>'
            ].join('');

            domwindow.innerHTML = domwindowdata;
            document.body.appendChild(domwindow);

            var tElement = document.querySelector('#' + t);
            if (!tElement) return null;

            var divs = tElement.querySelectorAll("div[class^='modal-']");
            for (var i = 0; i < divs.length; i++) {
                var className = divs[i].className.replace(/modal-/, "");
                tElement[className] = divs[i];
            }

            tElement.handle._parent = tElement;
            tElement.resizearea._parent = tElement;
            tElement.controls._parent = tElement;
            tElement.onclose = function() {
                return true;
            };
            tElement.onmousedown = function() {
                this.setfocus(tElement);
            }.bind(this);
            tElement.handle.onmousedown = this.setupdrag.bind(this, tElement);
            tElement.resizearea.onmousedown = this.setupdrag.bind(this, tElement);
            tElement.controls.onclick = this.enablecontrols.bind(this, tElement);
            tElement.show = function() {
                this.show(tElement);
            }.bind(this);
            tElement.hide = function() {
                this.hide(tElement);
            }.bind(this);
            tElement.close = function() {
                this.close(tElement);
            }.bind(this);
            tElement.setSize = function(w, h) {
                this.setSize(tElement, w, h);
            }.bind(this);
            tElement.moveTo = function(x, y) {
                this.moveTo(tElement, x, y);
            }.bind(this);
            tElement.isResize = function(bol) {
                this.isResize(tElement, bol);
            }.bind(this);
            tElement.isScrolling = function(bol) {
                this.isScrolling(tElement, bol);
            }.bind(this);
            tElement.load = function(contenttype, contentsource, title) {
                this.load(tElement, contenttype, contentsource, title);
            }.bind(this);
            this.tobjects.push(tElement);
            return tElement;
        },

        /**
         * Open a modal window with specified content.
         * @param {string} t - The ID of the modal window to open.
         * @param {string} contenttype - The type of content to load (e.g., 'iframe', 'image', 'ajax', 'inline', 'div').
         * @param {string} contentsource - The source of the content to load.
         * @param {string} title - The title to display in the modal window.
         * @param {string} attr - Attributes for the modal window (e.g., width, height, center).
         * @param {string} recalonload - Whether to recalculate the position on load.
         * @returns {HTMLElement|null} The opened modal window element, or null if it couldn't be opened.
         */
        open: function(t, contenttype, contentsource, title, attr, recalonload) {
            function getValue(name) {
                var match = new RegExp(name + "=([^,]+)", "i").exec(attr);
                return match ? parseInt(match[1], 10) : 0;
            }

            var tElement = document.querySelector('#' + t) || this.init(t);
            if (!tElement) return null;

            this.setfocus(tElement);

            if (contenttype !== 'image') {
                var width = getValue("width");
                var height = getValue("height");
                var isCenter = getValue("center");
                var xpos = isCenter ? "middle" : getValue("left");
                var ypos = isCenter ? "middle" : getValue("top");

                tElement.setSize(width, height);

                if (recalonload === "recal" && this.scroll_top === 0) {
                    var moveWindow = function() {
                        tElement.moveTo(xpos, ypos);
                    };
                    if (window.attachEvent && !window.opera) {
                        this.addEvent(window, function() {
                            setTimeout(moveWindow, 400);
                        }, "load");
                    } else {
                        this.addEvent(window, moveWindow, "load");
                    }
                }

                tElement.isResize(getValue("resize"));
                tElement.isScrolling(getValue("scrolling"));
                tElement.style.visibility = "visible";
                tElement.style.display = "block";
                tElement.contentarea.style.display = "block";
                tElement.contentarea.innerHTML = "";
                tElement.moveTo(xpos, ypos);
            }

            tElement.load(contenttype, contentsource, title);

            if (tElement.state === "minimized" && tElement.controls.firstChild.title === "Restore") {
                var controlIcon = tElement.controls.firstChild;
                controlIcon.setAttribute("src", this.imagefiles[0]);
                controlIcon.setAttribute("title", "Minimize");
                tElement.state = "fullview";
            }

            return tElement;
        },

        /**
         * Set the size of the modal window.
         * @param {HTMLElement} t - The modal window element.
         * @param {number} w - The width to set.
         * @param {number} h - The height to set.
         */
        setSize: function(t, w, h) {
            t.style.width = Math.max(parseInt(w), 150) + "px";
            t.contentarea.style.height = Math.max(parseInt(h), 100) + "px";
        },

        /**
         * Enable or disable resizing of the modal window.
         * @param {HTMLElement} t - The modal window element.
         * @param {boolean} bol - Whether resizing is enabled.
         */
        isResize: function(t, bol) {
            t.statusarea.style.display = (bol) ? "block" : "none";
            t.resizeBool = (bol) ? 1 : 0;
        },

        /**
         * Scale the size of the content to fit within the maximum dimensions.
         * @param {number} maxW - The maximum width.
         * @param {number} maxH - The maximum height.
         * @param {number} currW - The current width.
         * @param {number} currH - The current height.
         * @returns {Array} The scaled width and height.
         */
        scaleSize: function(maxW, maxH, currW, currH) {
            var ratio = currH / currW;
            if (currW >= maxW && ratio <= 1) {
                currW = maxW;
                currH = currW * ratio;
            } else if (currH >= maxH) {
                currH = maxH;
                currW = currH / ratio;
            }
            return [currW, currH];
        },

        /**
         * Enable or disable scrolling within the modal window.
         * @param {HTMLElement} t - The modal window element.
         * @param {boolean} bol - Whether scrolling is enabled.
         */
        isScrolling: function(t, bol) {
            t.contentarea.style.overflow = (bol) ? "auto" : "hidden";
        },

        /**
         * Load content into the modal window.
         * @param {HTMLElement} t - The modal window element.
         * @param {string} contenttype - The type of content to load.
         * @param {string} contentsource - The source of the content to load.
         * @param {string} title - The title to display in the modal window.
         */
        load: function(t, contenttype, contentsource, title) {
            if (t.isClosed) {
                alert("Modal Window has been closed, so no window to load contents into. Open/Create the window again.");
                return;
            }
            contenttype = contenttype.toLowerCase();
            if (title) {
                t.handle.firstChild.nodeValue = title;
            }

            switch (contenttype) {
                case 'iframe':
                    this.loadIframe(t, contentsource);
                    break;
                case 'image':
                    this.loadImage(t, contentsource);
                    break;
                case 'ajax':
                    this.loadAjax(t, contentsource);
                    break;
                case 'inline':
                    this.loadInline(t, contentsource);
                    break;
                case 'div':
                    this.loadDiv(t, contentsource);
                    break;
                default:
                    console.error('Unsupported content type:', contenttype);
            }
        },

        /**
         * Manage the loading indicator for the modal window.
         * @param {HTMLElement} t - The modal window element.
         * @param {boolean} show - Whether to show the loading indicator.
         * @param {boolean} isImage - Whether the content is an image.
         */
        manageLoader: function(t, show, isImage) {
            if (show) {
                var loaderDiv = document.createElement("div");
                loaderDiv.setAttribute("class", "loading-indicator");

                if (isImage) {
                    loaderDiv.setAttribute("style", "position:absolute;");
                    document.body.appendChild(loaderDiv);
                    this.moveTo(loaderDiv, 'middle', 'middle');
                    t.loading = loaderDiv;
                } else {
                    loaderDiv.setAttribute("style", "position:absolute; top:50%; left:50%; transform: translate(-50%, -50%);");
                    t.contentarea.appendChild(loaderDiv);
                }

                return loaderDiv;
            } else {
                if (isImage && t.loading) {
                    document.body.removeChild(t.loading);
                    t.loading = null;
                } else {
                    var loaderDiv = t.contentarea.querySelector(".loading-indicator");
                    if (loaderDiv) {
                        t.contentarea.removeChild(loaderDiv);
                    }
                }
            }
        },

        /**
         * Load content via AJAX into the modal window.
         * @param {HTMLElement} t - The modal window element.
         * @param {string} contentsource - The URL to load content from.
         */
        loadAjax: function(t, contentsource) {
            var loaderDiv = this.manageLoader(t, true, false);

            sedjs.ajax({
                url: contentsource,
                method: 'GET',
                dataType: 'html',
                success: function(response) {
                    t.contentarea.innerHTML = response;
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                },
                complete: function() {
                    this.manageLoader(t, false, false);
                }.bind(this)
            });
        },

        /**
         * Load an image into the modal window.
         * @param {HTMLElement} t - The modal window element.
         * @param {string} contentsource - The URL of the image to load.
         */
        loadImage: function(t, contentsource) {
            var loaderDiv = this.manageLoader(t, true, true);

            var i = new Image();
            i.onload = function() {
                var max_h = (window.innerHeight > 150) ? window.innerHeight - 100 : this.maxheightimage;
                var max_w = (window.innerWidth > 150) ? window.innerWidth - 100 : this.maxwidthimage;
                if (i.height > max_h) {
                    var newSize = this.scaleSize(max_w, max_h, i.width, i.height);
                    i.width = newSize[0];
                    i.height = newSize[1];
                }
                t.setSize(i.width + 4, i.height);
                t.moveTo('middle', 'middle');
                this.manageLoader(t, false, true);
            }.bind(this);
            i.onerror = function() {
                this.manageLoader(t, false, true);
            }.bind(this);

            i.src = contentsource;
            t.contentarea.appendChild(i);
            t.statusarea.style.display = "none";
            t.contentarea.style.overflow = "hidden";
            t.style.visibility = "visible";
            t.style.display = "block";
            t.contentarea.style.display = "block";
        },

        /**
         * Load an iframe into the modal window.
         * @param {HTMLElement} t - The modal window element.
         * @param {string} contentsource - The URL to load in the iframe.
         */
        loadIframe: function(t, contentsource) {
            var loaderDiv = this.manageLoader(t, true, false);

            t.contentarea.style.overflow = "hidden";
            var iframe = t.contentarea.querySelector("iframe");
            if (!iframe) {
                iframe = document.createElement("iframe");
                iframe.style.margin = "0";
                iframe.style.padding = "0";
                iframe.style.width = "100%";
                iframe.style.height = "100%";
                iframe.name = "_iframe-" + t.id;
                iframe.id = "id_iframe-" + t.id;
                t.contentarea.appendChild(iframe);
            }

            iframe.onload = function() {
                this.manageLoader(t, false, false);
            }.bind(this);

            iframe.src = contentsource;
        },

        /**
         * Load inline HTML content into the modal window.
         * @param {HTMLElement} t - The modal window element.
         * @param {string} contentsource - The HTML content to load.
         */
        loadInline: function(t, contentsource) {
            t.contentarea.innerHTML = contentsource;
        },

        /**
         * Load content from a div element into the modal window.
         * @param {HTMLElement} t - The modal window element.
         * @param {string} contentsource - The ID of the div to load content from.
         */
        loadDiv: function(t, contentsource) {
            var inlinedivref = document.querySelector('#' + contentsource);
            t.contentarea.innerHTML = (inlinedivref.defaultHTML || inlinedivref.innerHTML);
            if (!inlinedivref.defaultHTML) {
                inlinedivref.defaultHTML = inlinedivref.innerHTML;
            }
            inlinedivref.innerHTML = "";
            inlinedivref.style.display = "none";
        },

        /**
         * Set up dragging functionality for the modal window.
         * @param {HTMLElement} t - The modal window element.
         * @param {Event} e - The mouse event.
         */
        setupdrag: function(t, e) {
            var tElement = t;
            this.etarget = e.target;
            var e = window.event || e;
            this.initmousex = e.clientX;
            this.initmousey = e.clientY;
            this.initx = parseInt(tElement.offsetLeft);
            this.inity = parseInt(tElement.offsetTop);
            this.width = parseInt(tElement.offsetWidth);
            this.contentheight = parseInt(tElement.contentarea.offsetHeight);
            if (tElement.contentarea.datatype == "iframe") {
                tElement.style.backgroundColor = "#F8F8F8";
                tElement.contentarea.style.visibility = "hidden";
            }
            document.onmousemove = this.getdistance.bind(this);
            document.onmouseup = function() {
                if (tElement.contentarea.datatype == "iframe") {
                    tElement.contentarea.style.backgroundColor = "white";
                    tElement.contentarea.style.visibility = "visible";
                }
                this.stop();
            }.bind(this);
            return false;
        },

        /**
         * Calculate the distance moved during dragging.
         * @param {Event} e - The mouse event.
         */
        getdistance: function(e) {
            var etarget = this.etarget;
            var e = window.event || e;
            this.distancex = e.clientX - this.initmousex;
            this.distancey = e.clientY - this.initmousey;
            if (etarget.className == "modal-handle") {
                this.move(etarget._parent, e);
            } else if (etarget.className == "modal-resizearea") {
                this.resize(etarget._parent, e);
            }
            return false;
        },

        /**
         * Get the viewport dimensions and scroll positions.
         */
        getviewpoint: function() {
            var ie = document.all && !window.opera;
            var domclientWidth = document.documentElement && parseInt(document.documentElement.clientWidth) || 100000;
            this.standardbody = (document.compatMode == "CSS1Compat") ? document.documentElement : document.body;
            this.scroll_top = (ie) ? this.standardbody.scrollTop : window.pageYOffset;
            this.scroll_left = (ie) ? this.standardbody.scrollLeft : window.pageXOffset;
            this.docwidth = (ie) ? this.standardbody.clientWidth : (/Safari/i.test(navigator.userAgent)) ? window.innerWidth : Math.min(domclientWidth, window.innerWidth - 16);
            this.docheight = (ie) ? this.standardbody.clientHeight : window.innerHeight;
        },

        /**
         * Remember the attributes of the modal window.
         * @param {HTMLElement} t - The modal window element.
         */
        rememberattrs: function(t) {
            this.getviewpoint();
            t.lastx = parseInt((t.style.left || t.offsetLeft)) - this.scroll_left;
            t.lasty = parseInt((t.style.top || t.offsetTop)) - this.scroll_top;
            t.lastwidth = parseInt(t.style.width);
        },

        /**
         * Move the modal window with constraints.
         * @param {HTMLElement} t - The modal window element to be moved.
         * @param {Event} e - The mouse event.
         */
        move: function(t, e) {
            this.getviewpoint(); // Update viewport data

            var newLeft = this.distancex + this.initx;
            var newTop = this.distancey + this.inity;

            if (this.constrainToViewport) {
                // Horizontal constraints
                var minX = this.scroll_left;
                var maxX = this.scroll_left + this.docwidth - t.offsetWidth;
                newLeft = Math.max(minX, Math.min(newLeft, maxX));

                // Vertical constraints
                var minY = this.scroll_top;
                var maxY = this.scroll_top + this.docheight - t.offsetHeight;
                newTop = Math.max(minY, Math.min(newTop, maxY));
            }

            t.style.left = newLeft + "px";
            t.style.top = newTop + "px";
        },

        /**
         * Move the modal window to a specific position with constraints.
         * @param {HTMLElement} t - The modal window element to be moved.
         * @param {string|number} x - The horizontal position. Use "middle" to center horizontally, or a number for a specific position.
         * @param {string|number} y - The vertical position. Use "middle" to center vertically, or a number for a specific position.
         */
        moveTo: function(t, x, y) {
            this.getviewpoint();

            var newX, newY;

            if (x === "middle") {
                newX = this.scroll_left + Math.max(0, (this.docwidth - t.offsetWidth) / 2);
            } else {
                newX = this.scroll_left + parseInt(x);
            }

            if (y === "middle") {
                newY = this.scroll_top + Math.max(0, (this.docheight - t.offsetHeight) / 2);
            } else {
                newY = this.scroll_top + parseInt(y);
            }

            if (this.constrainToViewport) {
                // Horizontal constraints
                var minX = this.scroll_left;
                var maxX = this.scroll_left + this.docwidth - t.offsetWidth;
                newX = Math.max(minX, Math.min(newX, maxX));

                // Vertical constraints
                var minY = this.scroll_top;
                var maxY = this.scroll_top + this.docheight - t.offsetHeight;
                newY = Math.max(minY, Math.min(newY, maxY));
            }

            t.style.left = newX + "px";
            t.style.top = newY + "px";
        },

        /**
         * Resize the modal window.
         * @param {HTMLElement} t - The modal window element.
         * @param {Event} e - The mouse event.
         */
        resize: function(t, e) {
            t.style.width = Math.max(this.width + this.distancex, 150) + "px";
            t.contentarea.style.height = Math.max(this.contentheight + this.distancey, 100) + "px";
        },

        /**
         * Enable control buttons for the modal window.
         * @param {HTMLElement} t - The modal window element.
         * @param {Event} e - The mouse event.
         */
        enablecontrols: function(t, e) {
            var sourceobj = window.event ? window.event.srcElement : e.target;
            if (/Minimize/i.test(sourceobj.getAttribute("title"))) {
                this.minimize(sourceobj, t);
            } else if (/Restore/i.test(sourceobj.getAttribute("title"))) {
                this.restore(sourceobj, t);
            } else if (/Close/i.test(sourceobj.getAttribute("title"))) {
                this.close(t);
            }
            return false;
        },

        /**
         * Minimize the modal window.
         * @param {HTMLElement} button - The minimize button element.
         * @param {HTMLElement} t - The modal window element.
         */
        minimize: function(button, t) {
            this.rememberattrs(t);
            button.setAttribute("src", this.imagefiles[2]);
            button.setAttribute("title", "Restore");
            t.state = "minimized";
            t.contentarea.style.display = "none";
            t.statusarea.style.display = "none";
            if (typeof t.minimizeorder == "undefined") {
                this.minimizeorder++;
                t.minimizeorder = this.minimizeorder;
            }
            t.style.left = "10px";
            t.style.width = "200px";
            var windowspacing = t.minimizeorder * 10;
            t.style.top = this.scroll_top + this.docheight - (t.handle.offsetHeight * t.minimizeorder) - windowspacing + "px";
        },

        /**
         * Restore the modal window.
         * @param {HTMLElement} button - The restore button element.
         * @param {HTMLElement} t - The modal window element.
         */
        restore: function(button, t) {
            this.getviewpoint();
            button.setAttribute("src", this.imagefiles[0]);
            button.setAttribute("title", "Minimize");
            t.state = "fullview";
            t.style.display = "block";
            t.contentarea.style.display = "block";
            if (t.resizeBool) {
                t.statusarea.style.display = "block";
            }
            t.style.left = parseInt(t.lastx) + this.scroll_left + "px";
            t.style.top = parseInt(t.lasty) + this.scroll_top + "px";
            t.style.width = parseInt(t.lastwidth) + "px";
        },

        /**
         * Close the modal window.
         * @param {HTMLElement} t - The modal window element.
         * @returns {boolean} Whether the window was successfully closed.
         */
        close: function(t) {
            try {
                var closewinbol = t.onclose();
            } catch (err) {
                var closewinbol = true;
            } finally {
                if (typeof closewinbol == "undefined") {
                    alert("An error has occurred somewhere inside your \"onclose\" event handler");
                    var closewinbol = true;
                }
            }
            if (closewinbol) {
                if (t.state != "minimized") {
                    this.rememberattrs(t);
                }
                if (window.frames["_iframe-" + t.id]) {
                    window.frames["_iframe-" + t.id].location.replace("about:blank");
                } else {
                    t.contentarea.innerHTML = "";
                }
                t.style.display = "none";
                t.isClosed = true;
                document.body.removeChild(t);
            }
            return closewinbol;
        },

        /**
         * Set the opacity of the modal window.
         * @param {HTMLElement} targetobject - The modal window element.
         * @param {number} value - The opacity value to set.
         */
        setopacity: function(targetobject, value) {
            if (!targetobject) {
                return;
            }
            if (targetobject.filters && targetobject.filters[0]) {
                if (typeof targetobject.filters[0].opacity == "number") {
                    targetobject.filters[0].opacity = value * 100;
                } else {
                    targetobject.style.filter = "alpha(opacity=" + value * 100 + ")";
                }
            } else if (typeof targetobject.style.MozOpacity != "undefined") {
                targetobject.style.MozOpacity = value;
            } else if (typeof targetobject.style.opacity != "undefined") {
                targetobject.style.opacity = value;
            }
        },

        /**
         * Set focus on the modal window.
         * @param {HTMLElement} t - The modal window element.
         */
        setfocus: function(t) {
            this.zIndexvalue++;
            t.style.zIndex = this.zIndexvalue;
            t.isClosed = false;
            this.setopacity(this.lastactivet.handle, 0.5);
            this.setopacity(t.handle, 1);
            this.lastactivet = t;
        },

        /**
         * Show the modal window.
         * @param {HTMLElement} t - The modal window element.
         */
        show: function(t) {
            if (t.isClosed) {
                alert("Modal Window has been closed, so nothing to show. Open/Create the window again.");
                return;
            }
            if (t.lastx) {
                this.restore(t.controls.firstChild, t);
            } else {
                t.style.display = "block";
            }
            this.setfocus(t);
            t.state = "fullview";
        },

        /**
         * Hide the modal window.
         * @param {HTMLElement} t - The modal window element.
         */
        hide: function(t) {
            t.style.display = "none";
        },

        /**
         * Stop dragging or resizing the modal window.
         */
        stop: function() {
            this.etarget = null;
            document.onmousemove = null;
            document.onmouseup = null;
        },

        /**
         * Add an event listener to the modal window.
         * @param {HTMLElement} target - The element to attach the event to.
         * @param {Function} functionref - The function to call when the event occurs.
         * @param {string} tasktype - The type of event to listen for.
         */
        addEvent: function(target, functionref, tasktype) {
            var tasktype = (window.addEventListener) ? tasktype : "on" + tasktype;
            if (target.addEventListener) {
                target.addEventListener(tasktype, functionref, false);
            } else if (target.attachEvent) {
                target.attachEvent(tasktype, functionref);
            }
        },

        /**
         * Clean up event listeners and references.
         */
        cleanup: function() {
            for (var i = 0; i < this.tobjects.length; i++) {
                this.tobjects[i].handle._parent = this.tobjects[i].resizearea._parent = this.tobjects[i].controls._parent = null;
            }
            window.onload = null;
        }
    },

    /**
     * Activates a stylesheet by its title.
     * @param {string} title - The title attribute of the stylesheet to activate.
     */
    setActiveStyleSheet: function(title) {
        var links = document.querySelectorAll('link[rel*="style"][title]');
        for (var i = 0; i < links.length; i++) {
            links[i].disabled = links[i].getAttribute('title') !== title;
        }
    },

    /**
     * Gets the title of the currently active stylesheet.
     * @returns {string|null} - The title of the active stylesheet, or null if none is active.
     */
    getActiveStyleSheet: function() {
        var activeLink = document.querySelector('link[rel*="style"][title]:not([disabled])');
        return activeLink ? activeLink.getAttribute('title') : null;
    },

    /**
     * Gets the title of the preferred stylesheet.
     * @returns {string|null} - The title of the preferred stylesheet, or null if none is found.
     */
    getPreferredStyleSheet: function() {
        var preferredLink = document.querySelector('link[rel*="style"]:not([rel*="alt"])[title]');
        return preferredLink ? preferredLink.getAttribute('title') : null;
    },

    /**
     * Creates a cookie with a specified name, value, and optional expiration in days.
     * @param {string} name - The name of the cookie.
     * @param {string} value - The value of the cookie.
     * @param {number} [days] - The number of days until the cookie expires. If not provided, the cookie will be a session cookie.
     *
     * This function sets a cookie with the given name and value. If the 'days' parameter is provided,
     * the cookie will have an expiration date set to the specified number of days from the current date.
     * If 'days' is not provided, the cookie will be a session cookie, which expires when the browser is closed.
     */
    createCookie: function(name, value, days) {
        var date = new Date();
        if (days) {
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            var expires = "; expires=" + date.toGMTString();
        }
        document.cookie = name + "=" + value + (expires || "") + "; path=/";
    },

    /**
     * Reads the value of a cookie by its name.
     * @param {string} name - The name of the cookie.
     * @returns {string|null} - The value of the cookie, or null if the cookie is not found.
     */
    readCookie: function(name) {
        var nameEQ = name + "=";
        var cookies = document.cookie.split(';');
        for (var i = 0; i < cookies.length; i++) {
            var cookie = cookies[i];
            while (cookie.charAt(0) === ' ') {
                cookie = cookie.substring(1, cookie.length);
            }
            if (cookie.indexOf(nameEQ) === 0) {
                return cookie.substring(nameEQ.length, cookie.length);
            }
        }
        return null;
    },

    /**
     * Combines the values of two anti-spam fields.
     */
    antispam: function() {
        if (document.getElementById('anti1')) {
            document.getElementById('anti1').value += document.getElementById('anti2').value;
        }
    },

    /**
     * Generates a Search Engine Friendly (SEF) URL.
     * @param {HTMLInputElement} from - The input element containing the original string.
     * @param {HTMLInputElement} to - The input element where the SEF URL will be placed.
     * @param {boolean} allow_slashes - Whether to allow slashes in the SEF URL.
     */
    genSEF: function(from, to, allow_slashes) {
        var str = from.value.toLowerCase();
        var slash = "";
        if (allow_slashes) {
            slash = "\\/";
        }

        var LettersFrom = "абвгдезиклмнопрстуфыэйхё";
        var LettersTo = "abvgdeziklmnoprstufyejxe";
        var Consonant = "бвгджзйклмнпрстфхцчшщ";
        var Vowel = "аеёиоуыэюя";
        var BiLetters = {
            "ж": "zh",
            "ц": "ts",
            "ч": "ch",
            "ш": "sh",
            "щ": "sch",
            "ю": "ju",
            "я": "ja"
        };

        str = str.replace(/[_\s\.,?!\[\](){}]+/g, "_");
        str = str.replace(/-{2,}/g, "--");
        str = str.replace(/_\-+_/g, "--");

        str = str.toLowerCase();

        //here we replace ъ/ь
        str = str.replace(
            new RegExp("(ь|ъ)([" + Vowel + "])", "g"), "j$2");
        str = str.replace(/(ь|ъ)/g, "");

        //transliterating
        var _str = "";
        for (var x = 0; x < str.length; x++) {
            if ((index = LettersFrom.indexOf(str.charAt(x))) > -1) {
                _str += LettersTo.charAt(index);
            } else {
                _str += str.charAt(x);
            }
        }
        str = _str;

        var _str = "";
        for (var x = 0; x < str.length; x++) {
            if (BiLetters[str.charAt(x)]) {
                _str += BiLetters[str.charAt(x)];
            } else {
                _str += str.charAt(x);
            }
        }
        str = _str;
        str = str.replace(/j{2,}/g, "j");
        str = str.replace(new RegExp("[^" + slash + "0-9a-z_\\-]+", "g"), "");
        to.value = str;
    },

    /**
     * Adds a new poll option to the poll.
     * @param {string} apo - The CSS selector for the poll options container.
     *
     * This function clones the last poll option element, increments its number,
     * clears its input value, and appends a delete button if not already present.
     * The new option is then inserted after the last existing option.
     */
    add_poll_option: function(apo) {
        var delete_button = '<button type="button" class="poll-option-delete" onclick="sedjs.remove_poll_option(this);">x</button>';
        var elem = document.querySelectorAll(apo);
        var last = elem[elem.length - 1];
        var clone = last.cloneNode(true);
        if (clone.querySelector('.poll-option-delete') == null) {
            clone.innerHTML += delete_button;
        }
        var num = clone.querySelector('.num').innerHTML;
        clone.querySelector('.num').innerHTML = parseInt(num) + 1;
        clone.querySelector('input').value = '';
        clone.querySelector('input').setAttribute('name', 'poll_option[]');
        clone.querySelector('input').setAttribute('value', '');
        last.parentNode.insertBefore(clone, last.nextSibling);
    },


    /**
     * Removes a poll option from the poll.
     * @param {HTMLElement} apo - The DOM element representing the poll option to be removed.
     *
     * This function removes the specified poll option element from the DOM.
     */
    remove_poll_option: function(apo) {
        var root = apo.parentNode;
        root.parentNode.removeChild(root);
    },

    /**
     * Copies the URL from the href attribute of a link to the clipboard.
     * @param {HTMLElement} el - The DOM element containing the href attribute with the URL to copy.
     *
     * This function prevents the default action, creates a temporary input element,
     * sets its value to the URL, selects the input value, copies it to the clipboard,
     * and then removes the temporary input element.
     */
    copyurl: function(el) {
        event.preventDefault();
        var cpLink = el.getAttribute('href');
        var dummy = document.createElement("input");
        document.body.appendChild(dummy);
        dummy.setAttribute('value', cpLink);
        dummy.select();
        document.execCommand('copy');
        document.body.removeChild(dummy);
    },

    /**
     * Automatically sets the file title input field based on the selected file name, excluding the file extension.
     *
     * This function listens for changes on file input elements with the class 'file'.
     * When a file is selected, it extracts the file name without the extension and sets it as the value
     * of the corresponding file title input field.
     */
    autofiletitle: function() {
        var fileUpload = document.querySelectorAll('.file');
        for (var i = 0; i < fileUpload.length; i++) {
            fileUpload[i].onchange = function() {
                var filename = this.value;
                var inputFileTitleName = this.name.replace('userfile', 'ntitle');
                var k = filename.split('\\').pop().split('/').pop();
                var lastDotIndex = k.lastIndexOf('.');
                var title = (lastDotIndex === -1) ? k : k.substring(0, lastDotIndex);
                document.querySelector('[name="' + inputFileTitleName + '"]').value = title;
            }
        }
    },

    /**
     * Toggles the visibility of spoiler content.
     *
     * This function adds click event listeners to elements with the class 'spoiler-toggle'.
     * When clicked, it toggles the visibility of the associated spoiler content and updates the toggle icon.
     */
    spoiler: function() {
        // Function to toggle the visibility of the spoiler content
        function toggleSpoilerContent(spoilerToggle) {
            var spoilerContent = spoilerToggle.closest('.spoiler').querySelector('.spoiler-content');
            var displayStyle = window.getComputedStyle(spoilerContent).display;
            // Check the current display style of the spoiler content
            if (displayStyle === 'none') {
                // If the content is hidden, show it and update the toggle icon
                spoilerContent.style.display = 'block';
                spoilerToggle.classList.remove('hide-icon');
                spoilerToggle.classList.add('show-icon');
            } else {
                // If the content is visible, hide it and update the toggle icon
                spoilerContent.style.display = 'none';
                spoilerToggle.classList.remove('show-icon');
                spoilerToggle.classList.add('hide-icon');
            }
        }
        // Find all elements with the class 'spoiler-toggle' and add a click event listener
        var spoilerToggles = document.querySelectorAll('.spoiler-toggle');
        for (var i = 0; i < spoilerToggles.length; i++) {
            spoilerToggles[i].addEventListener('click', function() {
                toggleSpoilerContent(this);
            });
        }
    }
};

function addLoadEvent(funct) {
    var oldonload = window.onload;
    if (typeof window.onload != 'function') {
        window.onload = funct;
    } else {
        window.onload = function() {
            oldonload();
            funct();
        }
    }
}

onloadfunct = function() {
    sedjs.sedtabs();
    sedjs.autofiletitle();
    sedjs.spoiler();
    //	sedjs.sedtabs({c:"sedtabs2", e:"click", s:"selected", d:0, f:false });  //Example other tab conteiner
    sedjs.getrel("sedthumb");
    var cookie = sedjs.readCookie("style");
    var title = cookie ? cookie : sedjs.getPreferredStyleSheet();
    sedjs.setActiveStyleSheet(title);
};

addLoadEvent(onloadfunct);

window.addEventListener('beforeunload', function(event) {
    var title = sedjs.getActiveStyleSheet();
    sedjs.createCookie("style", title, 365);
    // sedjs.modal.cleanup();
});