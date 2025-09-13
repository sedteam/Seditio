var sedjs = {
    /**
     * Execute a function when the DOM is fully loaded.
     * @param {Function} fn - The function to execute when DOM is ready.
     */
    ready: function(fn) {
        if (typeof fn !== 'function') return;
        if (document.readyState === 'complete' || document.readyState === 'interactive') {
            return fn();
        }
        document.addEventListener('DOMContentLoaded', fn, false);
    },

    /**
     * Extend an object with one or more source objects.
     * @param {Object} out - The target object to extend (defaults to {}).
     * @param {...Object} sources - One or more source objects to merge into the target.
     * @returns {Object} The extended object.
     */
    extend: function(out) {
        out = out || {};
        for (var i = 1; i < arguments.length; i++) {
            if (!arguments[i]) continue;
            for (var key in arguments[i]) {
                if (arguments[i].hasOwnProperty(key)) {
                    out[key] = arguments[i][key];
                }
            }
        }
        return out;
    },

    /**
     * Check if an element has a specific class.
     * @param {HTMLElement} el - The DOM element to check.
     * @param {string} cls - The class name to look for.
     * @returns {boolean} True if the element has the class, false otherwise.
     */
    hasClass: function(el, cls) {
        if (!el || !cls) return false;
        return (' ' + el.className + ' ').indexOf(' ' + cls + ' ') > -1;
    },

    /**
     * Add a class to an element if it doesn't already have it.
     * @param {HTMLElement} el - The DOM element to modify.
     * @param {string} cls - The class name to add.
     */
    addClass: function(el, cls) {
        if (!el || !cls) return;
        if (!this.hasClass(el, cls)) {
            el.className = el.className ? el.className + ' ' + cls : cls;
        }
    },

    /**
     * Remove a class from an element if it has it.
     * @param {HTMLElement} el - The DOM element to modify.
     * @param {string} cls - The class name to remove.
     */
    removeClass: function(el, cls) {
        if (!el || !cls) return;
        if (this.hasClass(el, cls)) {
            var reg = new RegExp('(\\s|^)' + cls + '(\\s|$)');
            el.className = el.className.replace(reg, ' ').trim();
        }
    },

    /**
     * Toggle a class on an element (add if absent, remove if present).
     * @param {HTMLElement} el - The DOM element to modify.
     * @param {string} cls - The class name to toggle.
     */
    toggleClass: function(el, cls) {
        if (!el || !cls) return;
        if (this.hasClass(el, cls)) {
            this.removeClass(el, cls);
        } else {
            this.addClass(el, cls);
        }
    },

    /**
     * Open a popup window or modal with specified content.
     * @param {string} code - The code or identifier for the content to display.
     * @param {number} w - The width of the popup window.
     * @param {number} h - The height of the popup window.
     * @param {boolean} modal - Whether to open the content in a modal window.
     * @param {string} title - The title of the window or modal.
     */
    popup: function(code, w, h, modal, title) {
        title = title || 'Popup';
        if (!modal) {
            window.open(sedjs.get_basehref() + 'plug?o=' + code, title, 'toolbar=0,location=0,directories=0,menuBar=0,resizable=0,scrollbars=yes,width=' + w + ',height=' + h + ',left=32,top=16');
        } else {
            window.modal = sedjs.modal.open('popup', 'iframe', sedjs.get_basehref() + 'plug?o=' + code, title, 'width=' + w + 'px,height=' + h + 'px,resize=1,scrolling=1,center=1', 'load');
        }
    },

    /**
     * Open a PFS (Personal File Storage) window or modal.
     * @param {string} id - The user ID for the PFS.
     * @param {string} c1 - Additional parameter for the PFS.
     * @param {string} c2 - Additional parameter for the PFS.
     * @param {boolean} modal - Whether to open the PFS in a modal window.
     * @param {string} title - The title of the window or modal.
     */
    pfs: function(id, c1, c2, modal, title) {
        title = title || 'PFS';
        if (!modal) {
            window.open(sedjs.get_basehref() + 'pfs?userid=' + id + '&c1=' + c1 + '&c2=' + c2, title, 'status=1, toolbar=0,location=0,directories=0,menuBar=0,resizable=1,scrollbars=yes,width=990,height=600,left=32,top=16');
        } else {
            window.modal = sedjs.modal.open("pfs", "iframe", sedjs.get_basehref() + 'pfs?userid=' + id + '&c1=' + c1 + '&c2=' + c2, title, "width=990px,height=600px,resize=1,scrolling=1,center=1", "load");
        }
    },

    /**
     * Open a help window or modal with specified content.
     * @param {string} rcode - The help code or identifier.
     * @param {string} c1 - Additional parameter for the help content.
     * @param {string} c2 - Additional parameter for the help content.
     * @param {boolean} modal - Whether to open the help content in a modal window.
     * @param {string} title - The title of the window or modal.
     */
    help: function(rcode, c1, c2, modal, title) {
        title = title || 'Help';
        if (!modal) {
            window.open(sedjs.get_basehref() + 'plug?h=' + rcode + '&c1=' + c1 + '&c2=' + c2, title, 'toolbar=0,location=0,directories=0,menuBar=0,resizable=0,scrollbars=yes,width=480,height=512,left=32,top=16');
        } else {
            window.modal = sedjs.modal.open("help", "iframe", sedjs.get_basehref() + 'plug?h=' + rcode + '&c1=' + c1 + '&c2=' + c2, title, "width=500px,height=520px,resize=1,scrolling=1,center=1", "load");
        }
    },

    /**
     * Open a polls window or modal with specified content.
     * @param {string} rcode - The poll code or identifier.
     * @param {boolean} modal - Whether to open the polls content in a modal window.
     * @param {string} title - The title of the window or modal.
     */
    polls: function(rcode, modal, title) {
        title = title || 'Polls';
        if (!modal) {
            window.open(sedjs.get_basehref() + 'polls?stndl=1&id=' + rcode, title, 'toolbar=0,location=0,directories=0,menuBar=0,resizable=0,scrollbars=yes,width=608,height=448,left=16,top=16');
        } else {
            window.modal = sedjs.modal.open("polls", "iframe", sedjs.get_basehref() + 'polls?stndl=1&id=' + rcode, title, "width=610px,height=450px,resize=1,scrolling=1,center=1", "load");
        }
    },

    /**
     * Open a poll voting window or modal with specified content.
     * @param {string} rcode - The poll code or identifier.
     * @param {string} rvote - The vote identifier.
     * @param {boolean} modal - Whether to open the poll voting content in a modal window.
     * @param {string} title - The title of the window or modal.
     */
    pollvote: function(rcode, rvote, modal, title) {
        title = title || 'Polls';
        if (!modal) {
            window.open(sedjs.get_basehref() + 'polls?a=send&stndl=1&id=' + rcode + '&vote=' + rvote, title, 'toolbar=0,location=0,directories=0,menuBar=0,resizable=0,scrollbars=yes,width=608,height=448,left=16,top=16');
        } else {
            window.modal = sedjs.modal.open("pollvote", "iframe", sedjs.get_basehref() + 'polls?a=send&stndl=1&id=' + rcode + '&vote=' + rvote, title, "width=610px,height=450px,resize=1,scrolling=1,center=1", "load");
        }
    },

    /**
     * Open a picture window or modal with specified content.
     * @param {string} url - The URL of the picture.
     * @param {number} sx - The width of the picture window.
     * @param {number} sy - The height of the picture window.
     * @param {boolean} modal - Whether to open the picture in a modal window.
     * @param {string} title - The title of the window or modal.
     */
    picture: function(url, sx, sy, modal, title) {
        title = title || 'Picture';
        if (!modal) {
            var ptop = (window.screen.height - 200) / 2;
            var pleft = (window.screen.width - 200) / 2;
            window.open(sedjs.get_basehref() + 'pfs?m=view&v=' + url, title, 'toolbar=0,location=0,status=0, directories=0,menubar=0,resizable=1,scrollbars=yes,width=' + sx + ',height=' + sy + ',left=' + pleft + ',top=' + ptop + '');
        } else {
            var imglink = 'datas/users/' + url;
            var randid = imglink.replace(/[^a-z0-9]/gi, '');
            window.modal = sedjs.modal.open('img-' + randid, 'image', imglink, title, 'resize=0, scrolling=0, center=1', 'load');
        }
    },

    /**
     * Redirect the browser to a specified URL from a select element.
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
            sedjs.addClass(this, className);
            var tabTitle = this.getAttribute('data-tabtitle');
            if (tabTitle && document.querySelector('.tab-title')) {
                document.querySelector('.tab-title').textContent = tabTitle;
            }
        };

        var removeClass = function(className) {
            sedjs.removeClass(this, className);
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
            var mergedConfig = sedjs.extend({
                containerClass: 'sedtabs',
                eventType: 'click',
                selectedClass: 'selected',
                defaultTabIndex: 0,
                beforeSwitchCallback: false
            }, config);

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
     * @param {Object} options - The options for the AJAX request, including URL, method, data, etc.
     * @returns {XMLHttpRequest} The XMLHttpRequest object used for the request.
     */
    ajax: function(options) {
        // Merge default settings with provided options
        var settings = sedjs.extend({
            url: '', // Target URL for the request
            method: 'GET', // HTTP method (e.g., GET, POST)
            data: null, // Data to send (e.g., FormData, File, or object)
            dataType: 'text', // Expected response type (e.g., 'json', 'xml', 'text')
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8', // Default Content-Type header
            processData: true, // Whether to process data before sending (e.g., serialize objects)
            headers: {}, // Custom headers to include in the request
            timeout: 0, // Timeout in milliseconds (0 = no timeout)
            cache: true, // Whether to allow caching for GET requests
            async: true, // Whether the request should be asynchronous
            context: null, // Context for callback functions
            beforeSend: null, // Callback before the request is sent
            success: null, // Callback on successful response
            error: null, // Callback on error
            complete: null // Callback when request is completed
        }, options);

        // Create a new XMLHttpRequest object
        var xhr = new XMLHttpRequest();
        var timer; // Timer for request timeout
        var url = settings.url; // Request URL
        var method = settings.method.toUpperCase(); // Normalize method to uppercase
        var isGet = method === 'GET'; // Flag indicating if this is a GET request

        // Process the data if required and not FormData
        if (settings.processData && typeof settings.data === 'object' && !(settings.data instanceof FormData)) {
            settings.data = sedjs.serialization(settings.data); // Serialize object to query string
        }

        // Append data to URL for GET requests
        if (isGet && settings.data) {
            url += (url.indexOf('?') === -1 ? '?' : '&') + settings.data; // Add data as query parameters
        }

        // Prevent caching for GET requests if cache is disabled
        if (!settings.cache && isGet) {
            url += (url.indexOf('?') === -1 ? '?' : '&') + '_=' + Date.now(); // Add timestamp to URL
        }

        // Initialize the request
        xhr.open(method, url, settings.async);
		
		// Indicate AJAX request
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest'); 
		xhr.setRequestHeader('X-Seditio-Ajax', 'good-seditio-ajax');

        // Set Content-Type header based on data type and settings
        if (!isGet) {
            if (settings.data instanceof FormData) {
                // For FormData: Only set Content-Type if explicitly provided (not false)
                if (settings.contentType !== false) {
                    xhr.setRequestHeader('Content-Type', settings.contentType);
                }
            } else if (settings.data && typeof settings.data === 'object' && settings.data.type) {
                // For raw File: Use file.type (e.g., 'image/png') if contentType is false, else use provided
                if (settings.contentType === false) {
                    xhr.setRequestHeader('Content-Type', settings.data.type || 'application/octet-stream');
                } else {
                    xhr.setRequestHeader('Content-Type', settings.contentType);
                }
            } else if (settings.contentType !== false) {
                // For other data: Use specified contentType unless explicitly disabled
                xhr.setRequestHeader('Content-Type', settings.contentType);
            }
        }

        // Apply custom headers from settings
        for (var header in settings.headers) {
            xhr.setRequestHeader(header, settings.headers[header]);
        }

        // Set up timeout if specified
        if (settings.timeout > 0) {
            timer = setTimeout(function() {
                xhr.abort(); // Cancel request on timeout
                handleError('timeout', 'Request timed out'); // Trigger error callback
            }, settings.timeout);
        }

        // Execute beforeSend callback if provided
        if (typeof settings.beforeSend === 'function') {
            if (settings.beforeSend.call(settings.context, xhr, settings) === false) {
                xhr.abort(); // Abort if beforeSend returns false
                return xhr;
            }
        }

        // Handle state changes of the request
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) { // Request completed
                clearTimeout(timer); // Clear timeout
                var status = xhr.status;
                var response = parseResponse(); // Parse response based on dataType

                if (status >= 200 && status < 300 || status === 304) {
                    handleSuccess(response); // Success for 2xx or 304 status
                } else {
                    handleError(status, xhr.statusText); // Error for other statuses
                }
                handleComplete(response); // Always call complete callback
            }
        };

        // Send the request with data (null for GET)
        xhr.send(isGet ? null : settings.data);

        /**
         * Parse the response based on the specified dataType.
         * @returns {string|Object|Document} Parsed response (JSON, XML, or raw text).
         */
        function parseResponse() {
            var response = xhr.responseText;
            switch (settings.dataType.toLowerCase()) {
                case 'json':
                    try {
                        return JSON.parse(response); // Parse as JSON
                    } catch (e) {
                        return response; // Fallback to raw text on error
                    }
                case 'xml':
                    return xhr.responseXML; // Return XML document
                case 'script':
                    (1, eval)(response); // Execute as script
                    return response;
                default:
                    return response; // Return raw text
            }
        }

        /**
         * Handle successful response.
         * @param {string|Object|Document} response - The parsed response.
         */
        function handleSuccess(response) {
            if (typeof settings.success === 'function') {
                settings.success.call(settings.context, response, xhr.status, xhr);
            }
        }

        /**
         * Handle request error.
         * @param {number} status - HTTP status code.
         * @param {string} error - Error message.
         */
        function handleError(status, error) {
            if (typeof settings.error === 'function') {
                settings.error.call(settings.context, xhr, status, error);
            }
        }

        /**
         * Handle request completion (success or error).
         * @param {string|Object|Document} response - The parsed response.
         */
        function handleComplete(response) {
            if (typeof settings.complete === 'function') {
                settings.complete.call(settings.context, xhr, status);
            }
        }

        return xhr; // Return the XMLHttpRequest object for external control
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
        var options = sedjs.extend({}, defaults, userOptions);

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

        if (options.method.toUpperCase() === 'POST' && options.url) {
            var urlParts = options.url.split('?');
            var baseUrl = urlParts[0];
            if (urlParts.length > 1) {
                var urlParams = new URLSearchParams(urlParts[1]);
                urlParams.forEach(function(value, key) {
                    formData.append(key, value);
                });
                //options.url = baseUrl;
            }
        }

        var loaderDiv = null;
        if (loadingElement) {
            loaderDiv = document.createElement("div");
            var intElemOffsetHeight = Math.floor(loadingElement.offsetHeight / 2) + 16;
            var intElemOffsetWidth = Math.floor(loadingElement.offsetWidth / 2) - 16;
            loaderDiv.setAttribute("style", "position:absolute; margin-top:-" + intElemOffsetHeight + "px; margin-left:" + intElemOffsetWidth + "px;");
            sedjs.addClass(loaderDiv, "loading-indicator");
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
        imagefiles: ['/system/img/vars/min.gif', '/system/img/vars/close.gif', '/system/img/vars/restore.gif', '/system/img/vars/resize.gif'],
        maxheightimage: 600,
        maxwidthimage: 800,
        minimizeorder: 0,
        zIndexvalue: 1000,
        tobjects: [],
        lastactivet: {},
        constrainToViewport: false,

        /**
         * Initialize a new modal window.
         * @param {string} t - The ID of the modal window to be created.
         * @returns {HTMLElement|null} The created modal window element, or null if it couldn't be created.
         */
        init: function(t) {
            var domwindow = document.createElement("div");
            domwindow.id = t;
            sedjs.addClass(domwindow, "sed_modal");

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
                '<div class="modal-resizearea" style="background: transparent url(' + this.imagefiles[3] + ') top right no-repeat;"> </div>',
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
            tElement.onclose = function() { return true; };
            tElement.onmousedown = sedjs.modal.setfocus.bind(this, tElement);
            tElement.handle.onmousedown = sedjs.modal.setupdrag.bind(this, tElement);
            tElement.resizearea.onmousedown = sedjs.modal.setupdrag.bind(this, tElement);
            tElement.controls.onclick = sedjs.modal.enablecontrols.bind(this, tElement);
            tElement.show = sedjs.modal.show.bind(this, tElement);
            tElement.hide = sedjs.modal.hide.bind(this, tElement);
            tElement.close = sedjs.modal.close.bind(this, tElement);
            tElement.setSize = sedjs.modal.setSize.bind(this, tElement);
            tElement.moveTo = sedjs.modal.moveTo.bind(this, tElement);
            tElement.isResize = sedjs.modal.isResize.bind(this, tElement);
            tElement.isScrolling = sedjs.modal.isScrolling.bind(this, tElement);
            tElement.load = sedjs.modal.load.bind(this, tElement);
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

            if (contenttype === 'image') {
                var overlay = document.querySelector('.modal-overlay');
                if (!overlay) {
                    overlay = document.createElement("div");
                    sedjs.addClass(overlay, "modal-overlay");
                    document.body.appendChild(overlay);
                }
                overlay.style.display = 'block';
                overlay.style.opacity = '0';
                setTimeout(function() { overlay.style.opacity = '0.7'; }, 10);

                sedjs.addClass(tElement, 'image-modal');
                tElement.handle.style.display = 'none';
                tElement.statusarea.style.display = 'none';
                tElement.style.visibility = 'visible';
                tElement.style.display = 'block';
                tElement.contentarea.style.display = 'block';

                overlay.onclick = function(e) {
                    if (e.target === overlay) {
                        sedjs.modal.close(tElement);
                    }
                };
            } else {
                var width = getValue("width");
                var height = getValue("height");
                var isCenter = getValue("center");
                var xpos = isCenter ? "middle" : getValue("left");
                var ypos = isCenter ? "middle" : getValue("top");

                tElement.setSize(width, height);
                tElement.moveTo(xpos, ypos);
                tElement.isResize(getValue("resize"));
                tElement.isScrolling(getValue("scrolling"));
                tElement.style.visibility = 'visible';
                tElement.style.display = 'block';
                tElement.contentarea.style.display = 'block';
            }

            tElement.load(contenttype, contentsource, title);
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
                sedjs.addClass(loaderDiv, "loading-indicator");

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
         * Load an image into the modal window.
         * @param {HTMLElement} t - The modal window element.
         * @param {string} contentsource - The URL of the image to load.
         */
        loadImage: function(t, contentsource) {
            var loaderDiv = this.manageLoader(t, true, true);

            var i = new Image();
            var self = this;
            i.onload = function() {
                console.log('Image loaded successfully, source: ' + contentsource); // Debug log
                self.getviewpoint(); // Update viewport dimensions and scroll position
                self.handleResize(t, i); // Handle initial sizing and positioning

                // Add close button (X) in the top-right corner
                var closeBtn = document.createElement('div');
                closeBtn.innerHTML = '✕';
                closeBtn.className = 'close-image'; // Use the class 'close-image'
                closeBtn.onclick = function() {
                    sedjs.modal.close(t);
                };
                t.contentarea.appendChild(closeBtn);

                // Ensure image is added after the button or clear content first
                t.contentarea.appendChild(i); // Add the image explicitly

                // Remove the loading indicator and show the window with animation
                self.manageLoader(t, false, true);
                t.style.opacity = '0';
                setTimeout(function() { t.style.opacity = '1'; }, 10);

                // Add resize listener for this modal instance
                if (!t.resizeHandler) {
                    t.resizeHandler = function() {
                        self.handleResize(t, i);
                    };
                    self.addEvent(window, t.resizeHandler, 'resize');
                }
            };
            i.onerror = function() {
                self.manageLoader(t, false, true);
                t.contentarea.innerHTML = 'Error loading image';
                console.error('Image loading failed for source: ' + contentsource); // Debug log
            };

            i.src = contentsource;
        },

        /**
         * Handle resizing of the modal window for images, ensuring it stays centered and scaled.
         * @param {HTMLElement} t - The modal window element.
         * @param {HTMLImageElement} img - The image element to resize.
         */
        handleResize: function(t, img) {
            this.getviewpoint(); // Update viewport dimensions and scroll position
            var maxW = this.docwidth - 40; // Margins for convenience
            var maxH = this.docheight - 40;

            // Scale the image to fit within the visible area
            var ratio = img.height / img.width;
            var newWidth = img.width;
            var newHeight = img.height;

            if (newWidth > maxW) {
                newWidth = maxW;
                newHeight = newWidth * ratio;
            }
            if (newHeight > maxH) {
                newHeight = maxH;
                newWidth = newHeight / ratio;
            }

            // Set the window size
            t.setSize(newWidth, newHeight);

            // Center the window in the visible area using position: fixed
            var left = (this.docwidth - newWidth) / 2; // Horizontal centering relative to viewport
            var top = (this.docheight - newHeight) / 2; // Vertical centering relative to viewport

            // Constrain the position to prevent the window from exceeding the viewport
            left = Math.max(0, Math.min(left, this.docwidth - newWidth));
            top = Math.max(0, Math.min(top, this.docheight - newHeight));

            // Apply the position and size
            t.style.position = 'fixed'; // Ensure fixed positioning
            t.style.left = left + "px";
            t.style.top = top + "px";

            // Update the image dimensions
            img.width = newWidth;
            img.height = newHeight;
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

            var self = this;
            iframe.onload = function() {
                self.manageLoader(t, false, false);
            };

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
         * Load content via AJAX into the modal window.
         * @param {HTMLElement} t - The modal window element.
         * @param {string} contentsource - The URL to load content from.
         */
        loadAjax: function(t, contentsource) {
            var loaderDiv = this.manageLoader(t, true, false);
            var self = this;

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
                    self.manageLoader(t, false, false);
                }
            });
        },

        /**
         * Set up dragging functionality for the modal window.
         * @param {HTMLElement} t - The modal window element.
         * @param {Event} e - The mouse event.
         */
        setupdrag: function(t, e) {
            var tElement = t;
            this.etarget = e.target;
            var evt = window.event || e;
            this.initmousex = evt.clientX;
            this.initmousey = evt.clientY;
            this.initx = parseInt(tElement.offsetLeft);
            this.inity = parseInt(tElement.offsetTop);
            this.width = parseInt(tElement.offsetWidth);
            this.contentheight = parseInt(tElement.contentarea.offsetHeight);
            if (tElement.contentarea.datatype == "iframe") {
                tElement.style.backgroundColor = "#F8F8F8";
                tElement.contentarea.style.visibility = "hidden";
            }
            var self = this;
            document.onmousemove = function(e) { self.getdistance(e); };
            document.onmouseup = function() {
                if (tElement.contentarea.datatype == "iframe") {
                    tElement.contentarea.style.backgroundColor = "white";
                    tElement.contentarea.style.visibility = "visible";
                }
                self.stop();
            };
            return false;
        },

        /**
         * Calculate the distance moved during dragging.
         * @param {Event} e - The mouse event.
         */
        getdistance: function(e) {
            var etarget = this.etarget;
            var evt = window.event || e;
            this.distancex = evt.clientX - this.initmousex;
            this.distancey = evt.clientY - this.initmousey;
            if (etarget.className == "modal-handle") {
                this.move(etarget._parent, evt);
            } else if (etarget.className == "modal-resizearea") {
                this.resize(etarget._parent, evt);
            }
            return false;
        },

        /**
         * Move the modal window with constraints.
         * @param {HTMLElement} t - The modal window element to be moved.
         * @param {Event} e - The mouse event.
         */
        move: function(t, e) {
            this.getviewpoint();

            var newLeft = this.distancex + this.initx;
            var newTop = this.distancey + this.inity;

            if (this.constrainToViewport) {
                var minX = this.scroll_left;
                var maxX = this.scroll_left + this.docwidth - t.offsetWidth;
                newLeft = Math.max(minX, Math.min(newLeft, maxX));

                var minY = this.scroll_top;
                var maxY = this.scroll_top + this.docheight - t.offsetHeight;
                newTop = Math.max(minY, Math.min(newTop, maxY));
            }

            t.style.left = newLeft + "px";
            t.style.top = newTop + "px";
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
            var overlay = document.querySelector('.modal-overlay');
            var closeResult = t.onclose();
            if (closeResult !== false) {
                t.style.opacity = '0';
                if (overlay && sedjs.hasClass(t, 'image-modal')) {
                    overlay.style.opacity = '0';
                    setTimeout(function() {
                        overlay.style.display = 'none';
                    }, 300);
                }
                // Clean up the resize handler
                if (t.resizeHandler) {
                    this.removeEvent(window, t.resizeHandler, 'resize');
                    t.resizeHandler = null;
                }
                setTimeout(function() {
                    t.style.display = 'none';
                    document.body.removeChild(t);
                    sedjs.removeClass(t, 'image-modal');
                }, 300);
                t.isClosed = true;
            }
            return true;
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
         * Move the modal window to a specific position with constraints.
         * @param {HTMLElement} t - The modal window element to be moved.
         * @param {string|number} x - The horizontal position ("middle" or number).
         * @param {string|number} y - The vertical position ("middle" or number).
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
                var minX = this.scroll_left;
                var maxX = this.scroll_left + this.docwidth - t.offsetWidth;
                newX = Math.max(minX, Math.min(newX, maxX));

                var minY = this.scroll_top;
                var maxY = this.scroll_top + this.docheight - t.offsetHeight;
                newY = Math.max(minY, Math.min(newY, maxY));
            }

            t.style.left = newX + "px";
            t.style.top = newY + "px";
        },

        /**
         * Get the viewport dimensions and scroll positions.
         */
        getviewpoint: function() {
            var ie = document.all && !window.opera;
            var docElement = document.documentElement;
            var body = document.body;

            // Get the width of the visible area
            var domclientWidth = docElement && parseInt(docElement.clientWidth) || 100000;
            this.standardbody = (document.compatMode == "CSS1Compat") ? docElement : body;

            // Get scroll position
            this.scroll_top = (ie) ?
                (body.scrollTop || docElement.scrollTop) :
                (window.pageYOffset !== undefined) ? window.pageYOffset : 0;
            this.scroll_left = (ie) ?
                (body.scrollLeft || docElement.scrollLeft) :
                (window.pageXOffset !== undefined) ? window.pageXOffset : 0;

            // Get dimensions of the visible area
            this.docwidth = (ie) ?
                this.standardbody.clientWidth :
                (/Safari/i.test(navigator.userAgent)) ? window.innerWidth :
                Math.min(domclientWidth, window.innerWidth - 16);
            this.docheight = (ie) ?
                this.standardbody.clientHeight :
                window.innerHeight || 0;
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
            var tasktypeAdjusted = (window.addEventListener) ? tasktype : "on" + tasktype;
            if (target.addEventListener) {
                target.addEventListener(tasktypeAdjusted, functionref, false);
            } else if (target.attachEvent) {
                target.attachEvent(tasktypeAdjusted, functionref);
            }
        },

        /**
         * Remove an event listener from the target element.
         * @param {HTMLElement} target - The element to remove the event from.
         * @param {Function} functionref - The function to remove.
         * @param {string} tasktype - The type of event to remove.
         */
        removeEvent: function(target, functionref, tasktype) {
            var tasktypeAdjusted = (window.removeEventListener) ? tasktype : "on" + tasktype;
            if (target.removeEventListener) {
                target.removeEventListener(tasktypeAdjusted, functionref, false);
            } else if (target.detachEvent) {
                target.detachEvent(tasktypeAdjusted, functionref);
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
     * @returns {string|null} The title of the active stylesheet, or null if none is active.
     */
    getActiveStyleSheet: function() {
        var activeLink = document.querySelector('link[rel*="style"][title]:not([disabled])');
        return activeLink ? activeLink.getAttribute('title') : null;
    },

    /**
     * Gets the title of the preferred stylesheet.
     * @returns {string|null} The title of the preferred stylesheet, or null if none is found.
     */
    getPreferredStyleSheet: function() {
        var preferredLink = document.querySelector('link[rel*="style"]:not([rel*="alt"])[title]');
        return preferredLink ? preferredLink.getAttribute('title') : null;
    },

    /**
     * Creates a cookie with a specified name, value, and optional expiration in days.
     * @param {string} name - The name of the cookie.
     * @param {string} value - The value of the cookie.
     * @param {number} [days] - The number of days until the cookie expires (optional).
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
     * @returns {string|null} The value of the cookie, or null if the cookie is not found.
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

        str = str.replace(
            new RegExp("(ь|ъ)([" + Vowel + "])", "g"), "j$2");
        str = str.replace(/(ь|ъ)/g, "");

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
     */
    remove_poll_option: function(apo) {
        var root = apo.parentNode;
        root.parentNode.removeChild(root);
    },

    /**
     * Copies the URL from the href attribute of a link to the clipboard.
     * @param {HTMLElement} el - The DOM element containing the href attribute with the URL to copy.
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
     */
	spoiler: function() {
		function toggleSpoilerContent(spoilerToggle) {
			var spoiler = spoilerToggle.closest('.spoiler');
			var spoilerContent = spoiler.querySelector('.spoiler-content');
			var isHidden = window.getComputedStyle(spoilerContent).display === 'none';

			if (isHidden) {
				spoilerContent.style.display = 'block';
				sedjs.removeClass(spoilerToggle, 'show-icon');
				sedjs.addClass(spoilerToggle, 'hide-icon');
			} else {
				spoilerContent.style.display = 'none';
				sedjs.removeClass(spoilerToggle, 'hide-icon');
				sedjs.addClass(spoilerToggle, 'show-icon');
			}
		}

		var spoilerToggles = document.querySelectorAll('.spoiler-toggle');
		spoilerToggles.forEach(function(toggle) {
			toggle.addEventListener('click', function(e) {
				e.preventDefault();
				e.stopPropagation();
				toggleSpoilerContent(this);
			});
		});

		var spoilerTitles = document.querySelectorAll('.spoiler-title');
		spoilerTitles.forEach(function(title) {
			title.addEventListener('click', function(e) {
				e.preventDefault();
				var spoilerToggle = this.querySelector('.spoiler-toggle');
				toggleSpoilerContent(spoilerToggle);
			});
		});
	},

    /**
     * Adds click event listeners to specified elements to fade out and slide up their parent elements.
     * @param {string} [elements] - A CSS selector for the elements to which the click event listeners should be added.
     */
    closealert: function(elements) {
        var targets = elements || '.close, .alert-close, .fn-close';
        document.querySelectorAll(targets).forEach(function(element) {
            element.addEventListener('click', function(event) {
                event.preventDefault();
                var parent = this.parentElement;
                parent.style.transition = 'opacity 0.4s';
                parent.style.opacity = 0;
                setTimeout(function() {
                    parent.style.transition = 'height 0.4s';
                    parent.style.height = 0;
                    parent.style.overflow = 'hidden';
                    parent.style.display = 'none';
                }, 400);
            });
        });
    },

    /**
     * Sortable functionality for drag-and-drop reordering of elements.
     * @param {HTMLElement} element - The container element for sortable items.
     * @param {Object} options - Configuration options for sortable behavior.
     */
    sortable: function(element, options) {
        // Merge default settings with user-provided options
        var settings = sedjs.extend({
            items: ':not(.disabled)', // Selector for sortable items
            handle: null, // Selector for the drag handle (optional)
            connectWith: null, // Selector for connected sortable lists (optional)
            placeholder: 'sortable-placeholder', // Class name for the placeholder
            tolerance: 'pointer', // Tolerance mode: 'pointer' or 'intersect'
            disabled: false, // Whether the sortable is disabled initially
            axis: null, // Restrict movement to 'x' or 'y' axis (null for both)
            revert: false, // Whether to animate revert on cancel
            start: null, // Callback when dragging starts
            sort: null, // Callback during dragging
            change: null, // Callback when position changes
            beforeStop: null, // Callback before dragging stops
            stop: null, // Callback after dragging stops
            update: null, // Callback after order is updated
            receive: null // Callback when item is received from another list
        }, options);

        var dragItem = null; // Currently dragged item
        var placeholder = null; // Placeholder element during drag
        var connectedLists = []; // Array of connected sortable containers
        var orderChanged = false; // Flag to track if order has changed
        var items = []; // Array of current sortable items
        var observer = null; // MutationObserver instance for modern browsers
        var intervalId = null; // ID for setInterval fallback in older browsers

        /**
         * Initialize the sortable functionality.
         */
        function init() {
            if (settings.connectWith) {
                connectedLists = Array.prototype.slice.call(
                    document.querySelectorAll(settings.connectWith)
                );
            }
            updateItems(); // Initial population of items
            bindEvents();
            setupDynamicCheck(); // Set up dynamic DOM checking
            if (settings.disabled) {
                disable();
            }
        }

        /**
         * Update the list of sortable items based on the current DOM state.
         * @returns {boolean} Whether the items list has changed.
         */
        function updateItems() {
            var allItems = element.querySelectorAll(settings.items);
            var newItems = [];
            for (var i = 0; i < allItems.length; i++) {
                if (!sedjs.hasClass(allItems[i], 'disabled')) {
                    newItems.push(allItems[i]);
                }
            }

            // Check if items have changed
            var itemsChanged = newItems.length !== items.length;
            if (!itemsChanged) {
                for (var i = 0; i < newItems.length; i++) {
                    if (newItems[i] !== items[i]) {
                        itemsChanged = true;
                        break;
                    }
                }
            }

            items = newItems;
            return itemsChanged;
        }

        /**
         * Bind drag-and-drop events to current sortable items.
         */
        function bindEvents() {
            for (var i = 0; i < items.length; i++) {
                var item = items[i];
                var dragElement = settings.handle ?
                    item.querySelector(settings.handle) || item : item;

                if (!dragElement.draggable) { // Avoid re-binding if already set
                    dragElement.draggable = true;
                    dragElement.style.cursor = 'move';
                    dragElement.ondragstart = handleDragStart;
                    dragElement.ondragover = handleDragOver;
                    dragElement.ondragenter = handleDragEnter;
                    dragElement.ondragleave = handleDragLeave;
                    dragElement.ondrop = handleDrop;
                    dragElement.ondragend = handleDragEnd;
                }
            }
        }

        /**
         * Remove drag-and-drop events from current sortable items.
         */
        function unbindEvents() {
            for (var i = 0; i < items.length; i++) {
                var item = items[i];
                var dragElement = settings.handle ?
                    item.querySelector(settings.handle) || item : item;

                dragElement.draggable = false;
                dragElement.style.cursor = '';
                dragElement.ondragstart = null;
                dragElement.ondragover = null;
                dragElement.ondragenter = null;
                dragElement.ondragleave = null;
                dragElement.ondrop = null;
                dragElement.ondragend = null;
            }
        }

        /**
         * Set up dynamic checking for DOM changes, preferring MutationObserver if available.
         */
        function setupDynamicCheck() {
            if (window.MutationObserver) {
                // Use MutationObserver for modern browsers (IE11+)
                observer = new MutationObserver(function(mutations) {
                    for (var i = 0; i < mutations.length; i++) {
                        var mutation = mutations[i];
                        if (mutation.addedNodes.length || mutation.removedNodes.length) {
                            var itemsChanged = updateItems();
                            if (itemsChanged && !settings.disabled) {
                                unbindEvents();
                                bindEvents();
                            }
                        }
                    }
                });
                observer.observe(element, {
                    childList: true, // Watch for addition/removal of child elements
                    subtree: true // Watch the entire subtree
                });
            } else {
                // Fallback to setInterval for older browsers (e.g., IE9)
                var lastChildCount = element.children.length;
                intervalId = setInterval(function() {
                    var currentChildCount = element.children.length;
                    if (currentChildCount !== lastChildCount) {
                        var itemsChanged = updateItems();
                        if (itemsChanged && !settings.disabled) {
                            unbindEvents();
                            bindEvents();
                        }
                        lastChildCount = currentChildCount;
                    }
                }, 500); // Check every 500ms (adjustable)
            }
        }

        /**
         * Handle the start of dragging an item.
         * @param {Event} e - The dragstart event.
         */
        function handleDragStart(e) {
            var target = getItem(e.target);
            if (!target) return;

            dragItem = target;
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/html', target.outerHTML);

            var existingPlaceholders = element.querySelectorAll('.' + settings.placeholder);
            for (var i = 0; i < existingPlaceholders.length; i++) {
                if (existingPlaceholders[i].parentNode) {
                    existingPlaceholders[i].parentNode.removeChild(existingPlaceholders[i]);
                }
            }

            placeholder = document.createElement(dragItem.tagName);
            sedjs.addClass(placeholder, settings.placeholder);
            placeholder.style.height = dragItem.offsetHeight + 'px';

            dragItem.style.opacity = '0.5';
            dragItem.parentNode.insertBefore(placeholder, dragItem.nextSibling);
            orderChanged = false;

            var links = dragItem.querySelectorAll('a');
            for (var i = 0; i < links.length; i++) {
                links[i].style.pointerEvents = 'none';
            }

            if (settings.start) {
                settings.start(e, { item: dragItem });
            }
        }

        /**
         * Handle dragging over an item (required for drop to work).
         * @param {Event} e - The dragover event.
         */
        function handleDragOver(e) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
            if (settings.sort) {
                settings.sort(e, { item: dragItem });
            }
        }

        /**
         * Handle entering a potential drop target.
         * @param {Event} e - The dragenter event.
         */
        function handleDragEnter(e) {
            var target = getItem(e.target) || getClosest(e.target, settings.connectWith);
            if (!target || target === dragItem || target === placeholder || !placeholder) return;

            var rect = target.getBoundingClientRect();
            var isVertical = !settings.axis || settings.axis === 'y';
            var isHorizontal = settings.axis === 'x';

            var shouldInsertBefore = settings.tolerance === 'pointer' ?
                (isVertical ? e.clientY < rect.top + rect.height / 2 :
                    e.clientX < rect.left + rect.width / 2) :
                (isVertical ? rect.top < dragItem.getBoundingClientRect().top :
                    rect.left < dragItem.getBoundingClientRect().left);

            var parent = target.parentNode;
            if (parent && placeholder) {
                if (shouldInsertBefore) {
                    parent.insertBefore(placeholder, target);
                } else {
                    parent.insertBefore(placeholder, target.nextSibling);
                }
                orderChanged = true;
            }

            if (settings.change) {
                settings.change(e, { item: dragItem, placeholder: placeholder });
            }
        }

        /**
         * Handle leaving a potential drop target (currently empty).
         * @param {Event} e - The dragleave event.
         */
        function handleDragLeave(e) {}

        /**
         * Handle dropping an item.
         * @param {Event} e - The drop event.
         */
        function handleDrop(e) {
            e.preventDefault();
            if (!dragItem || !placeholder) return;

            var dropTarget = placeholder.parentNode;

            if (settings.beforeStop) {
                settings.beforeStop(e, { item: dragItem });
            }

            dropTarget.insertBefore(dragItem, placeholder);
            if (placeholder.parentNode) {
                placeholder.parentNode.removeChild(placeholder);
            }

            var isReceived = dropTarget !== element;
            if (isReceived && settings.receive) {
                settings.receive(e, { item: dragItem, sender: element });
            }

            if (orderChanged && settings.update) {
                settings.update(e, { item: dragItem });
            }

            dragItem.style.opacity = '1';
            var links = dragItem.querySelectorAll('a');
            for (var i = 0; i < links.length; i++) {
                links[i].style.pointerEvents = 'auto';
            }
            placeholder = null;
            orderChanged = false;
        }

        /**
         * Handle the end of dragging (mouse release).
         * @param {Event} e - The dragend event.
         */
        function handleDragEnd(e) {
            if (!dragItem) return;

            dragItem.style.opacity = '1';
            var links = dragItem.querySelectorAll('a');
            for (var i = 0; i < links.length; i++) {
                links[i].style.pointerEvents = 'auto';
            }

            if (settings.revert && placeholder) {
                revertAnimation();
            } else {
                if (orderChanged && settings.update) {
                    if (placeholder && placeholder.parentNode) {
                        placeholder.parentNode.insertBefore(dragItem, placeholder);
                        placeholder.parentNode.removeChild(placeholder);
                    }
                    settings.update(e, { item: dragItem });
                }
                cleanup();
            }

            if (settings.stop) {
                settings.stop(e, { item: dragItem });
            }
        }

        /**
         * Animate the dragged item back to its original position if revert is enabled.
         */
        function revertAnimation() {
            var startPos = dragItem.getBoundingClientRect();
            var endPos = placeholder.getBoundingClientRect();

            dragItem.style.transition = 'all 0.3s';
            dragItem.style.transform = 'translate(' +
                (endPos.left - startPos.left) + 'px,' +
                (endPos.top - startPos.top) + 'px)';

            setTimeout(function() {
                dragItem.style.transition = '';
                dragItem.style.transform = '';
                cleanup();
            }, 300);
        }

        /**
         * Clean up after dragging by removing the placeholder.
         */
        function cleanup() {
            if (placeholder && placeholder.parentNode) {
                placeholder.parentNode.removeChild(placeholder);
            }
            placeholder = null;
            orderChanged = false;
        }

        /**
         * Get the sortable item from an event target.
         * @param {HTMLElement} element - The element to check.
         * @returns {HTMLElement|null} The matching item or null.
         */
        function getItem(element) {
            return getClosest(element, settings.items);
        }

        /**
         * Find the closest ancestor matching a selector (IE9 polyfill).
         * @param {HTMLElement} element - The starting element.
         * @param {string} selector - The CSS selector to match.
         * @returns {HTMLElement|null} The closest matching element or null.
         */
        function getClosest(element, selector) {
            while (element) {
                if (matchesSelector(element, selector)) return element;
                element = element.parentNode;
            }
            return null;
        }

        /**
         * Polyfill for matches (IE9).
         * @param {HTMLElement} element - The element to test.
         * @param {string} selector - The CSS selector to match.
         * @returns {boolean} Whether the element matches the selector.
         */
        function matchesSelector(element, selector) {
            if (element.matches) return element.matches(selector);
            if (element.msMatchesSelector) return element.msMatchesSelector(selector);
            var nodes = element.parentNode.querySelectorAll(selector);
            for (var i = 0; i < nodes.length; i++) {
                if (nodes[i] === element) return true;
            }
            return false;
        }

        /**
         * Disable the sortable functionality.
         */
        function disable() {
            settings.disabled = true;
            unbindEvents();
        }

        /**
         * Enable the sortable functionality.
         */
        function enable() {
            settings.disabled = false;
            updateItems();
            bindEvents();
        }

        /**
         * Destroy the sortable instance, removing all events and observers.
         */
        function destroy() {
            unbindEvents();
            if (observer) {
                observer.disconnect();
                observer = null;
            }
            if (intervalId) {
                clearInterval(intervalId);
                intervalId = null;
            }
        }

        // Start the sortable functionality
        init();

        // Return public methods
        return {
            disable: disable,
            enable: enable,
            destroy: destroy,
            update: function() {
                var itemsChanged = updateItems();
                if (itemsChanged && !settings.disabled) {
                    unbindEvents();
                    bindEvents();
                }
            }
        };
    }
};

// Initialize functions when DOM is ready
sedjs.ready(function() {
    sedjs.sedtabs();
    sedjs.autofiletitle();
    sedjs.spoiler();
    sedjs.closealert();
    sedjs.getrel("sedthumb");
    var cookie = sedjs.readCookie("style");
    var title = cookie ? cookie : sedjs.getPreferredStyleSheet();
    sedjs.setActiveStyleSheet(title);
});

// Save stylesheet preference before unloading the page
window.addEventListener('beforeunload', function(event) {
    var title = sedjs.getActiveStyleSheet();
    sedjs.createCookie("style", title, 365);
});