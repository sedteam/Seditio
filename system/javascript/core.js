"use strict";
/* Target: ES6+ (Chrome 51+, Firefox 54+, Safari 10+, Edge 15+). No IE11. */

const sedjs = {
    /**
     * Execute a function when the DOM is fully loaded.
     * @param {Function} fn - The function to execute when DOM is ready.
     */
    ready(fn) {
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
    extend(out) {
        out = out || {};
        for (let i = 1; i < arguments.length; i++) {
            if (!arguments[i]) continue;
            for (const key in arguments[i]) {
                if (Object.prototype.hasOwnProperty.call(arguments[i], key)) {
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
    hasClass(el, cls) {
        if (!el || !cls) return false;
        return (` ${el.className} `).indexOf(` ${cls} `) > -1;
    },

    /**
     * Add a class to an element if it doesn't already have it.
     * @param {HTMLElement} el - The DOM element to modify.
     * @param {string} cls - The class name to add.
     */
    addClass(el, cls) {
        if (!el || !cls) return;
        if (!this.hasClass(el, cls)) {
            el.className = el.className ? `${el.className} ${cls}` : cls;
        }
    },

    /**
     * Remove a class from an element if it has it.
     * @param {HTMLElement} el - The DOM element to modify.
     * @param {string} cls - The class name to remove.
     */
    removeClass(el, cls) {
        if (!el || !cls) return;
        if (this.hasClass(el, cls)) {
            const reg = new RegExp(`(\\s|^)${cls}(\\s|$)`);
            el.className = el.className.replace(reg, ' ').trim();
        }
    },

    /**
     * Toggle a class on an element (add if absent, remove if present).
     * @param {HTMLElement} el - The DOM element to modify.
     * @param {string} cls - The class name to toggle.
     */
    toggleClass(el, cls) {
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
     * @param {string} [title='Popup'] - The title of the window or modal.
     */
    popup(code, w, h, modal, title = 'Popup') {
        const base = sedjs.get_basehref();
        if (!modal) {
            window.open(`${base}plug?o=${code}`, title, `toolbar=0,location=0,directories=0,menuBar=0,resizable=0,scrollbars=yes,width=${w},height=${h},left=32,top=16`);
        } else {
            window.modal = sedjs.modal.open('popup', 'iframe', `${base}plug?o=${code}`, title, `width=${w}px,height=${h}px,resize=1,scrolling=1,center=1`, 'load');
        }
    },

    /**
     * Open a PFS (Personal File Storage) window or modal.
     * @param {string} id - The user ID for the PFS.
     * @param {string} c1 - Additional parameter for the PFS.
     * @param {string} c2 - Additional parameter for the PFS.
     * @param {boolean} modal - Whether to open the PFS in a modal window.
     * @param {string} [title='PFS'] - The title of the window or modal.
     */
    pfs(id, c1, c2, modal, title = 'PFS') {
        const base = sedjs.get_basehref();
        if (!modal) {
            window.open(`${base}pfs?userid=${id}&c1=${c1}&c2=${c2}`, title, 'status=1, toolbar=0,location=0,directories=0,menuBar=0,resizable=1,scrollbars=yes,width=990,height=600,left=32,top=16');
        } else {
            window.modal = sedjs.modal.open("pfs", "iframe", `${base}pfs?userid=${id}&c1=${c1}&c2=${c2}`, title, "width=990px,height=600px,resize=1,scrolling=1,center=1", "load");
        }
    },

    /**
     * Open a help window or modal with specified content.
     * @param {string} rcode - The help code or identifier.
     * @param {string} c1 - Additional parameter for the help content.
     * @param {string} c2 - Additional parameter for the help content.
     * @param {boolean} modal - Whether to open the help content in a modal window.
     * @param {string} [title='Help'] - The title of the window or modal.
     */
    help(rcode, c1, c2, modal, title = 'Help') {
        const base = sedjs.get_basehref();
        if (!modal) {
            window.open(`${base}plug?h=${rcode}&c1=${c1}&c2=${c2}`, title, 'toolbar=0,location=0,directories=0,menuBar=0,resizable=0,scrollbars=yes,width=480,height=512,left=32,top=16');
        } else {
            window.modal = sedjs.modal.open("help", "iframe", `${base}plug?h=${rcode}&c1=${c1}&c2=${c2}`, title, "width=500px,height=520px,resize=1,scrolling=1,center=1", "load");
        }
    },

    /**
     * Open a polls window or modal with specified content.
     * @param {string} rcode - The poll code or identifier.
     * @param {boolean} modal - Whether to open the polls content in a modal window.
     * @param {string} [title='Polls'] - The title of the window or modal.
     */
    polls(rcode, modal, title = 'Polls') {
        const base = sedjs.get_basehref();
        if (!modal) {
            window.open(`${base}polls?stndl=1&id=${rcode}`, title, 'toolbar=0,location=0,directories=0,menuBar=0,resizable=0,scrollbars=yes,width=608,height=448,left=16,top=16');
        } else {
            window.modal = sedjs.modal.open("polls", "iframe", `${base}polls?stndl=1&id=${rcode}`, title, "width=610px,height=450px,resize=1,scrolling=1,center=1", "load");
        }
    },

    /**
     * Open a poll voting window or modal with specified content.
     * @param {string} rcode - The poll code or identifier.
     * @param {string} rvote - The vote identifier.
     * @param {boolean} modal - Whether to open the poll voting content in a modal window.
     * @param {string} [title='Polls'] - The title of the window or modal.
     */
    pollvote(rcode, rvote, modal, title = 'Polls') {
        const base = sedjs.get_basehref();
        if (!modal) {
            window.open(`${base}polls?a=send&stndl=1&id=${rcode}&vote=${rvote}`, title, 'toolbar=0,location=0,directories=0,menuBar=0,resizable=0,scrollbars=yes,width=608,height=448,left=16,top=16');
        } else {
            window.modal = sedjs.modal.open("pollvote", "iframe", `${base}polls?a=send&stndl=1&id=${rcode}&vote=${rvote}`, title, "width=610px,height=450px,resize=1,scrolling=1,center=1", "load");
        }
    },

    /**
     * Open a picture window or modal with specified content.
     * @param {string} url - The URL of the picture.
     * @param {number} sx - The width of the picture window.
     * @param {number} sy - The height of the picture window.
     * @param {boolean} modal - Whether to open the picture in a modal window.
     * @param {string} [title='Picture'] - The title of the window or modal.
     */
    picture(url, sx, sy, modal, title = 'Picture') {
        if (!modal) {
            const ptop = (window.screen.height - 200) / 2;
            const pleft = (window.screen.width - 200) / 2;
            const base = sedjs.get_basehref();
            window.open(`${base}pfs?m=view&v=${url}`, title, `toolbar=0,location=0,status=0, directories=0,menubar=0,resizable=1,scrollbars=yes,width=${sx},height=${sy},left=${pleft},top=${ptop}`);
        } else {
            const imglink = `datas/users/${url}`;
            const randid = imglink.replace(/[^a-z0-9]/gi, '');
            window.modal = sedjs.modal.open(`img-${randid}`, 'image', imglink, title, 'resize=0, scrolling=0, center=1', 'load');
        }
    },

    /**
     * Redirect the browser to a specified URL from a select element.
     * @param {HTMLElement} url - The select element containing the URL options.
     */
    redirect(url) {
        location.href = url.options[url.selectedIndex].value;
    },

	/**
	 * Updates URL query parameters from the selected <option> value.
	 * Fully replaces existing parameters with new ones, removes duplicates,
	 * preserves unrelated params, produces clean URL (no &&, ??, ?& etc.).
	 * Empty value removes all parameters that were in the option value.
	 *
	 * @param {HTMLSelectElement} selectElement - the changed <select>
	 */
	redirectWithParam(selectElement) {
		const paramString = selectElement.value.replace(/^\s+|\s+$/g, '');
		const pathname = window.location.pathname;
		const hash = window.location.hash;
		const search = window.location.search;

		const currentParams = {};
		if (search) {
			const pairs = search.substring(1).split('&');
			for (let i = 0; i < pairs.length; i++) {
				const pair = pairs[i];
				if (!pair) continue;
				const pos = pair.indexOf('=');
				let key, val;
				if (pos === -1) {
					key = decodeURIComponent(pair);
					val = '';
				} else {
					key = decodeURIComponent(pair.substring(0, pos));
					val = decodeURIComponent(pair.substring(pos + 1));
				}
				if (key) currentParams[key] = val;
			}
		}

		if (paramString === '') {
			delete currentParams['filial'];
		} else {
			const newPairs = paramString.split('&');
			for (let j = 0; j < newPairs.length; j++) {
				const newPair = newPairs[j];
				if (!newPair) continue;
				const newPos = newPair.indexOf('=');
				let newKey, newVal;
				if (newPos === -1) {
					newKey = decodeURIComponent(newPair);
					newVal = '';
				} else {
					newKey = decodeURIComponent(newPair.substring(0, newPos));
					newVal = decodeURIComponent(newPair.substring(newPos + 1));
				}
				if (newKey) currentParams[newKey] = newVal;
			}
		}

		const finalParts = [];
		for (const finalKey in currentParams) {
			if (Object.prototype.hasOwnProperty.call(currentParams, finalKey)) {
				finalParts.push(`${encodeURIComponent(finalKey)}=${encodeURIComponent(currentParams[finalKey])}`);
			}
		}

		const newSearch = finalParts.length ? `?${finalParts.join('&')}` : '';
		window.location.href = pathname + newSearch + hash;
	},

    /**
     * Display a confirmation dialog with a specified message.
     * @param {string} mess - The message to display in the confirmation dialog.
     * @returns {boolean} Whether the user confirmed the action.
     */
    confirmact(mess) {
        return confirm(mess);
    },

    /**
     * Get the base href for the current page.
     * @returns {string} The base href.
     */
    get_basehref() {
        let loc = "";
        const baseElement = document.querySelector('base');
        if (baseElement && baseElement.href) {
            if (baseElement.href.slice(-1) === '/' && loc.charAt(0) === '/') {
                loc = loc.slice(1);
            }
            loc = baseElement.href + loc;
        }
        return loc;
    },

    /**
     * Toggle the visibility of a block element.
     * @param {string} id - The ID of the block element to toggle.
     */
    toggleblock(id) {
        const block = document.querySelector(`#${id}`);
        if (block) {
            block.style.display = block.style.display === 'none' ? '' : 'none';
        }
    },

    /**
     * Initialize Tabs functionality with specified settings.
     * @param {Object} settings - The settings for the tab functionality.
     */
    sedtabs(settings) {
        const getElementOrElements = (identifier) => {
            if (identifier.charAt(0) === '#') {
                const byId = document.querySelector(identifier);
                return byId ? [byId] : [];
            }
            return document.querySelectorAll(`.${identifier}`);
        };

        const bindFunction = function(func) {
            const context = this;
            return function() {
                return func.apply(context, arguments);
            };
        };

        const applyToAllElements = (func, elements, args) => {
            for (let i = 0; i < elements.length; i++) {
                func.apply(elements[i], args || []);
            }
        };

        const addClass = function(className) {
            sedjs.addClass(this, className);
            const tabTitle = this.getAttribute('data-tabtitle');
            if (tabTitle && document.querySelector('.tab-title')) {
                document.querySelector('.tab-title').textContent = tabTitle;
            }
        };

        const removeClass = function(className) {
            sedjs.removeClass(this, className);
        };

        const hideElement = function() {
            this.style.display = 'none';
        };

        const showElement = function() {
            this.style.display = 'block';
        };

        const switchTab = function(tabId, tabLinks, tabContents, settings) {
            applyToAllElements(removeClass, tabLinks, [settings.selectedClass]);
            addClass.call(this, settings.selectedClass);
            applyToAllElements(hideElement, tabContents);
            applyToAllElements(showElement, document.querySelectorAll(`#${tabId}`));
        };

        const initTabs = (config) => {
            const mergedConfig = sedjs.extend({
                containerClass: 'sedtabs',
                eventType: 'click',
                selectedClass: 'selected',
                defaultTabIndex: 0,
                beforeSwitchCallback: false
            }, config);

            const tabContainers = document.querySelectorAll(`.${mergedConfig.containerClass}`);

            const handleTabClick = function() {
                const clickedTab = this;
                const tabLinks = this.tabLinks;
                const tabContents = this.tabContents;

                if (!mergedConfig.beforeSwitchCallback ||
                    mergedConfig.beforeSwitchCallback.apply(clickedTab, this.callbackArgs) !== false) {
                    switchTab.apply(clickedTab, this.callbackArgs);
                }
                return false;
            };

            for (let i = 0; i < tabContainers.length; i++) {
                const container = tabContainers[i];
                const tabLinks = container.querySelectorAll('a');
                const tabIds = [];
                const tabs = [];
                let tabContents = [];

                for (let j = 0; j < tabLinks.length; j++) {
                    if (tabLinks[j].href.match(/#tab/)) {
                        const tabId = tabLinks[j].href.split('#')[1];
                        tabIds.push(tabId);

                        if (typeof mergedConfig.defaultTabIndex === 'string' &&
                            tabId === mergedConfig.defaultTabIndex) {
                            mergedConfig.defaultTabIndex = j;
                        }

                        tabs.push(tabLinks[j]);

                        const contentElements = document.querySelectorAll(`#${tabId}`);
                        for (let k = 0; k < contentElements.length; k++) {
                            tabContents.push(contentElements[k]);
                        }
                    }
                }

                for (let j = 0; j < tabs.length; j++) {
                    const callbackArgs = [
                        tabIds[j],
                        tabs,
                        tabContents,
                        mergedConfig
                    ];

                    tabs[j].tabLinks = tabs;
                    tabs[j].tabContents = tabContents;
                    tabs[j].callbackArgs = callbackArgs;

                    tabs[j][`on${mergedConfig.eventType}`] =
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
    getrel(rel) {
        const pageLinks = document.querySelectorAll(`a[rel="${rel}"]`);
        pageLinks.forEach((link) => {
            link.addEventListener('click', (event) => {
                event.preventDefault();
                const imgLink = link.getAttribute('href');
                const imgTitle = link.getAttribute('title') || 'Picture';
                const randId = imgLink.replace(/[^a-z0-9]/gi, '');
                sedjs.modal.open(`img-${randId}`, 'image', imgLink, imgTitle, 'resize=0,scrolling=0,center=1', 'load');
            });
        });
    },

    /**
     * Perform an AJAX request with specified options.
     * @param {Object} options - The options for the AJAX request, including URL, method, data, etc.
     * @returns {XMLHttpRequest} The XMLHttpRequest object used for the request.
     */
    ajax(options) {
        const settings = sedjs.extend({
            url: '',
            method: 'GET',
            data: null,
            dataType: 'text',
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            processData: true,
            headers: {},
            timeout: 0,
            cache: true,
            async: true,
            context: null,
            beforeSend: null,
            success: null,
            error: null,
            complete: null
        }, options);

        const xhr = new XMLHttpRequest();
        let timer;
        let url = settings.url;
        const method = settings.method.toUpperCase();
        const isGet = method === 'GET';

        if (settings.processData && typeof settings.data === 'object' && !(settings.data instanceof FormData)) {
            settings.data = sedjs.serialization(settings.data);
        }

        if (isGet && settings.data) {
            url += (url.indexOf('?') === -1 ? '?' : '&') + settings.data;
        }

        if (!settings.cache && isGet) {
            url += (url.indexOf('?') === -1 ? '?' : '&') + `_=${Date.now()}`;
        }

        xhr.open(method, url, settings.async);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        xhr.setRequestHeader('X-Seditio-Csrf', csrfMeta ? csrfMeta.content : '');

        if (!isGet) {
            if (settings.data instanceof FormData) {
                if (settings.contentType !== false) {
                    xhr.setRequestHeader('Content-Type', settings.contentType);
                }
            } else if (settings.data && typeof settings.data === 'object' && settings.data.type) {
                if (settings.contentType === false) {
                    xhr.setRequestHeader('Content-Type', settings.data.type || 'application/octet-stream');
                } else {
                    xhr.setRequestHeader('Content-Type', settings.contentType);
                }
            } else if (settings.contentType !== false) {
                xhr.setRequestHeader('Content-Type', settings.contentType);
            }
        }

        for (const header in settings.headers) {
            if (Object.prototype.hasOwnProperty.call(settings.headers, header)) {
                xhr.setRequestHeader(header, settings.headers[header]);
            }
        }

        if (settings.timeout > 0) {
            timer = setTimeout(() => {
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
                const status = xhr.status;
                const response = parseResponse();

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
            const response = xhr.responseText;
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
                const status = xhr.status;
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
    serialization(data) {
        const pairs = [];

        function buildParams(prefix, obj) {
            if (Array.isArray(obj)) {
                obj.forEach((value, i) => {
                    if (/\[\]$/.test(prefix)) {
                        add(prefix, value);
                    } else {
                        buildParams(`${prefix}[${typeof value === 'object' ? i : ''}]`, value);
                    }
                });
            } else if (typeof obj === 'object') {
                for (const key in obj) {
                    if (Object.prototype.hasOwnProperty.call(obj, key)) {
                        buildParams(prefix ? `${prefix}[${key}]` : key, obj[key]);
                    }
                }
            } else {
                add(prefix, obj);
            }
        }

        function add(key, value) {
            pairs.push(
                `${encodeURIComponent(key)}=${encodeURIComponent(value == null ? '' : value)}`
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
    ajaxbind(userOptions) {
        const defaults = {
            url: '',
            format: 'html',
            method: 'POST',
            update: null,
            loading: null,
            formid: null,
            onSuccess: null,
            onError: null
        };
        const options = sedjs.extend({}, defaults, userOptions);

        const formElement = options.formid ? document.querySelector(options.formid) : null;
        const updateElement = options.update ? document.querySelector(options.update) : null;
        const loadingElement = options.loading ? document.querySelector(options.loading) : null;

        const formData = new FormData();

        if (formElement) {
            const elements = formElement.elements;
            for (let i = 0; i < elements.length; i++) {
                const element = elements[i];
                if (!element.name || element.disabled) continue;

                if (element.type === 'file') {
                    Array.from(element.files).forEach((file) => {
                        formData.append(element.name, file);
                    });
                } else if (element.type === 'checkbox' || element.type === 'radio') {
                    if (element.checked) formData.append(element.name, element.value);
                } else if (element.tagName === 'SELECT') {
                    if (element.multiple) {
                        Array.from(element.selectedOptions).forEach((opt) => {
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
            const urlParts = options.url.split('?');
            if (urlParts.length > 1) {
                const urlParams = new URLSearchParams(urlParts[1]);
                urlParams.forEach((value, key) => {
                    formData.append(key, value);
                });
            }
        }

        let loaderDiv = null;
        if (loadingElement) {
            loaderDiv = document.createElement("div");
            const intElemOffsetHeight = Math.floor(loadingElement.offsetHeight / 2) + 16;
            const intElemOffsetWidth = Math.floor(loadingElement.offsetWidth / 2) - 16;
            loaderDiv.setAttribute("style", `position:absolute; margin-top:-${intElemOffsetHeight}px; margin-left:${intElemOffsetWidth}px;`);
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
            success(response) {
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
            error(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                if (typeof options.onError === 'function') {
                    options.onError(xhr, status, error);
                }
            },
            complete() {
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
        init(t) {
            const domwindow = document.createElement("div");
            domwindow.id = t;
            sedjs.addClass(domwindow, "sed_modal");

            const domwindowdata = [
                '<div class="modal-handle">',
                'Modal Window',
                '<div class="modal-controls">',
                `<img src="${this.imagefiles[0]}" title="Minimize" />`,
                `<img src="${this.imagefiles[1]}" title="Close" />`,
                '</div>',
                '</div>',
                `<div class="modal-contentarea" id="area-${t}"></div>`,
                '<div class="modal-statusarea">',
                `<div class="modal-resizearea" style="background: transparent url(${this.imagefiles[3]}) top right no-repeat;"> </div>`,
                '</div>'
            ].join('');

            domwindow.innerHTML = domwindowdata;
            document.body.appendChild(domwindow);

            const tElement = document.querySelector(`#${t}`);
            if (!tElement) return null;

            const divs = tElement.querySelectorAll("div[class^='modal-']");
            for (let i = 0; i < divs.length; i++) {
                const className = divs[i].className.replace(/modal-/, "");
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
        open(t, contenttype, contentsource, title, attr, recalonload) {
            const getValue = (name) => {
                const match = new RegExp(`${name}=([^,]+)`, "i").exec(attr);
                return match ? parseInt(match[1], 10) : 0;
            };

            const tElement = document.querySelector(`#${t}`) || this.init(t);
            if (!tElement) return null;

            this.setfocus(tElement);

            if (contenttype === 'image') {
                let overlay = document.querySelector('.modal-overlay');
                if (!overlay) {
                    overlay = document.createElement("div");
                    sedjs.addClass(overlay, "modal-overlay");
                    document.body.appendChild(overlay);
                }
                overlay.style.display = 'block';
                overlay.style.opacity = '0';
                setTimeout(() => { overlay.style.opacity = '0.7'; }, 10);

                sedjs.addClass(tElement, 'image-modal');
                tElement.handle.style.display = 'none';
                tElement.statusarea.style.display = 'none';
                tElement.style.visibility = 'visible';
                tElement.style.display = 'block';
                tElement.contentarea.style.display = 'block';

                overlay.onclick = (e) => {
                    if (e.target === overlay) {
                        sedjs.modal.close(tElement);
                    }
                };
            } else {
                const width = getValue("width");
                const height = getValue("height");
                const isCenter = getValue("center");
                const xpos = isCenter ? "middle" : getValue("left");
                const ypos = isCenter ? "middle" : getValue("top");

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
        setSize(t, w, h) {
            t.style.width = `${Math.max(parseInt(w), 150)}px`;
            t.contentarea.style.height = `${Math.max(parseInt(h), 100)}px`;
        },

        /**
         * Enable or disable resizing of the modal window.
         * @param {HTMLElement} t - The modal window element.
         * @param {boolean} bol - Whether resizing is enabled.
         */
        isResize(t, bol) {
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
        scaleSize(maxW, maxH, currW, currH) {
            let ratio = currH / currW;
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
        isScrolling(t, bol) {
            t.contentarea.style.overflow = (bol) ? "auto" : "hidden";
        },

        /**
         * Load content into the modal window.
         * @param {HTMLElement} t - The modal window element.
         * @param {string} contenttype - The type of content to load.
         * @param {string} contentsource - The source of the content to load.
         * @param {string} title - The title to display in the modal window.
         */
        load(t, contenttype, contentsource, title) {
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
        manageLoader(t, show, isImage) {
            if (show) {
                const loaderDiv = document.createElement("div");
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
                    const loaderDiv = t.contentarea.querySelector(".loading-indicator");
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
        loadImage(t, contentsource) {
            this.manageLoader(t, true, true);

            const i = new Image();
            const self = this;
            i.onload = () => {
                console.log(`Image loaded successfully, source: ${contentsource}`); // Debug log
                self.getviewpoint(); // Update viewport dimensions and scroll position
                self.handleResize(t, i); // Handle initial sizing and positioning

                // Add close button (X) in the top-right corner
                const closeBtn = document.createElement('div');
                closeBtn.innerHTML = '✕';
                closeBtn.className = 'close-image'; // Use the class 'close-image'
                closeBtn.onclick = () => { sedjs.modal.close(t); };
                t.contentarea.appendChild(closeBtn);

                // Ensure image is added after the button or clear content first
                t.contentarea.appendChild(i); // Add the image explicitly

                // Remove the loading indicator and show the window with animation
                self.manageLoader(t, false, true);
                t.style.opacity = '0';
                setTimeout(() => { t.style.opacity = '1'; }, 10);

                // Add resize listener for this modal instance
                if (!t.resizeHandler) {
                    t.resizeHandler = () => { self.handleResize(t, i); };
                    self.addEvent(window, t.resizeHandler, 'resize');
                }
            };
            i.onerror = () => {
                self.manageLoader(t, false, true);
                t.contentarea.innerHTML = 'Error loading image';
                console.error(`Image loading failed for source: ${contentsource}`); // Debug log
            };

            i.src = contentsource;
        },

        /**
         * Handle resizing of the modal window for images, ensuring it stays centered and scaled.
         * @param {HTMLElement} t - The modal window element.
         * @param {HTMLImageElement} img - The image element to resize.
         */
        handleResize(t, img) {
            this.getviewpoint(); // Update viewport dimensions and scroll position
            const maxW = this.docwidth - 40; // Margins for convenience
            const maxH = this.docheight - 40;

            // Scale the image to fit within the visible area
            const ratio = img.height / img.width;
            let newWidth = img.width;
            let newHeight = img.height;

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
            let left = (this.docwidth - newWidth) / 2; // Horizontal centering relative to viewport
            let top = (this.docheight - newHeight) / 2; // Vertical centering relative to viewport

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
        loadIframe(t, contentsource) {
            this.manageLoader(t, true, false);

            t.contentarea.style.overflow = "hidden";
            let iframe = t.contentarea.querySelector("iframe");
            if (!iframe) {
                iframe = document.createElement("iframe");
                iframe.style.margin = "0";
                iframe.style.padding = "0";
                iframe.style.width = "100%";
                iframe.style.height = "100%";
                iframe.name = `_iframe-${t.id}`;
                iframe.id = `id_iframe-${t.id}`;
                t.contentarea.appendChild(iframe);
            }

            const self = this;
            iframe.onload = () => { self.manageLoader(t, false, false); };

            iframe.src = contentsource;
        },

        /**
         * Load inline HTML content into the modal window.
         * @param {HTMLElement} t - The modal window element.
         * @param {string} contentsource - The HTML content to load.
         */
        loadInline(t, contentsource) {
            t.contentarea.innerHTML = contentsource;
        },

        /**
         * Load content from a div element into the modal window.
         * @param {HTMLElement} t - The modal window element.
         * @param {string} contentsource - The ID of the div to load content from.
         */
        loadDiv(t, contentsource) {
            const inlinedivref = document.querySelector(`#${contentsource}`);
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
        loadAjax(t, contentsource) {
            this.manageLoader(t, true, false);
            const self = this;

            sedjs.ajax({
                url: contentsource,
                method: 'GET',
                dataType: 'html',
                success: (response) => { t.contentarea.innerHTML = response; },
                error: (xhr, status, error) => { console.error('AJAX Error:', status, error); },
                complete: () => { self.manageLoader(t, false, false); }
            });
        },

        /**
         * Set up dragging functionality for the modal window.
         * @param {HTMLElement} t - The modal window element.
         * @param {Event} e - The mouse event.
         */
        setupdrag(t, e) {
            const tElement = t;
            this.etarget = e.target;
            const evt = window.event || e;
            this.initmousex = evt.clientX;
            this.initmousey = evt.clientY;
            this.initx = parseInt(tElement.offsetLeft, 10);
            this.inity = parseInt(tElement.offsetTop, 10);
            this.width = parseInt(tElement.offsetWidth, 10);
            this.contentheight = parseInt(tElement.contentarea.offsetHeight, 10);
            if (tElement.contentarea.datatype == "iframe") {
                tElement.style.backgroundColor = "#F8F8F8";
                tElement.contentarea.style.visibility = "hidden";
            }
            const self = this;
            document.onmousemove = (e) => { self.getdistance(e); };
            document.onmouseup = () => {
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
        getdistance(e) {
            const etarget = this.etarget;
            const evt = window.event || e;
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
        move(t, e) {
            this.getviewpoint();

            let newLeft = this.distancex + this.initx;
            let newTop = this.distancey + this.inity;

            if (this.constrainToViewport) {
                const minX = this.scroll_left;
                const maxX = this.scroll_left + this.docwidth - t.offsetWidth;
                newLeft = Math.max(minX, Math.min(newLeft, maxX));

                const minY = this.scroll_top;
                const maxY = this.scroll_top + this.docheight - t.offsetHeight;
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
        resize(t, e) {
            t.style.width = Math.max(this.width + this.distancex, 150) + "px";
            t.contentarea.style.height = Math.max(this.contentheight + this.distancey, 100) + "px";
        },

        /**
         * Enable control buttons for the modal window.
         * @param {HTMLElement} t - The modal window element.
         * @param {Event} e - The mouse event.
         */
        enablecontrols(t, e) {
            const sourceobj = window.event ? window.event.srcElement : e.target;
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
        minimize(button, t) {
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
            const windowspacing = t.minimizeorder * 10;
            t.style.top = this.scroll_top + this.docheight - (t.handle.offsetHeight * t.minimizeorder) - windowspacing + "px";
        },

        /**
         * Restore the modal window.
         * @param {HTMLElement} button - The restore button element.
         * @param {HTMLElement} t - The modal window element.
         */
        restore(button, t) {
            this.getviewpoint();
            button.setAttribute("src", this.imagefiles[0]);
            button.setAttribute("title", "Minimize");
            t.state = "fullview";
            t.style.display = "block";
            t.contentarea.style.display = "block";
            if (t.resizeBool) {
                t.statusarea.style.display = "block";
            }
            t.style.left = parseInt(t.lastx, 10) + this.scroll_left + "px";
            t.style.top = parseInt(t.lasty, 10) + this.scroll_top + "px";
            t.style.width = parseInt(t.lastwidth, 10) + "px";
        },

        /**
         * Close the modal window.
         * @param {HTMLElement} t - The modal window element.
         * @returns {boolean} Whether the window was successfully closed.
         */
        close(t) {
            const overlay = document.querySelector('.modal-overlay');
            const closeResult = t.onclose();
            if (closeResult !== false) {
                t.style.opacity = '0';
                if (overlay && sedjs.hasClass(t, 'image-modal')) {
                    overlay.style.opacity = '0';
                    setTimeout(() => { overlay.style.display = 'none'; }, 300);
                }
                // Clean up the resize handler
                if (t.resizeHandler) {
                    this.removeEvent(window, t.resizeHandler, 'resize');
                    t.resizeHandler = null;
                }
                setTimeout(() => {
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
        setfocus(t) {
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
        show(t) {
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
        hide(t) {
            t.style.display = "none";
        },

        /**
         * Set the opacity of the modal window.
         * @param {HTMLElement} targetobject - The modal window element.
         * @param {number} value - The opacity value to set.
         */
        setopacity(targetobject, value) {
            if (!targetobject) {
                return;
            }
            if (targetobject.filters && targetobject.filters[0]) {
                if (typeof targetobject.filters[0].opacity == "number") {
                    targetobject.filters[0].opacity = value * 100;
                } else {
                    targetobject.style.filter = `alpha(opacity=${value * 100})`;
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
        moveTo(t, x, y) {
            this.getviewpoint();

            let newX, newY;

            if (x === "middle") {
                newX = this.scroll_left + Math.max(0, (this.docwidth - t.offsetWidth) / 2);
            } else {
                newX = this.scroll_left + parseInt(x, 10);
            }

            if (y === "middle") {
                newY = this.scroll_top + Math.max(0, (this.docheight - t.offsetHeight) / 2);
            } else {
                newY = this.scroll_top + parseInt(y, 10);
            }

            if (this.constrainToViewport) {
                const minX = this.scroll_left;
                const maxX = this.scroll_left + this.docwidth - t.offsetWidth;
                newX = Math.max(minX, Math.min(newX, maxX));

                const minY = this.scroll_top;
                const maxY = this.scroll_top + this.docheight - t.offsetHeight;
                newY = Math.max(minY, Math.min(newY, maxY));
            }

            t.style.left = newX + "px";
            t.style.top = newY + "px";
        },

        /**
         * Get the viewport dimensions and scroll positions.
         */
        getviewpoint() {
            const ie = document.all && !window.opera;
            const docElement = document.documentElement;
            const body = document.body;

            // Get the width of the visible area
            const domclientWidth = docElement && parseInt(docElement.clientWidth, 10) || 100000;
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
        rememberattrs(t) {
            this.getviewpoint();
            t.lastx = parseInt((t.style.left || t.offsetLeft), 10) - this.scroll_left;
            t.lasty = parseInt((t.style.top || t.offsetTop), 10) - this.scroll_top;
            t.lastwidth = parseInt(t.style.width, 10);
        },

        /**
         * Stop dragging or resizing the modal window.
         */
        stop() {
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
        addEvent(target, functionref, tasktype) {
            const tasktypeAdjusted = (window.addEventListener) ? tasktype : "on" + tasktype;
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
        removeEvent(target, functionref, tasktype) {
            const tasktypeAdjusted = (window.removeEventListener) ? tasktype : "on" + tasktype;
            if (target.removeEventListener) {
                target.removeEventListener(tasktypeAdjusted, functionref, false);
            } else if (target.detachEvent) {
                target.detachEvent(tasktypeAdjusted, functionref);
            }
        },

        /**
         * Clean up event listeners and references.
         */
        cleanup() {
            for (let i = 0; i < this.tobjects.length; i++) {
                this.tobjects[i].handle._parent = this.tobjects[i].resizearea._parent = this.tobjects[i].controls._parent = null;
            }
            window.onload = null;
        }
    },

    /**
     * Activates a stylesheet by its title.
     * @param {string} title - The title attribute of the stylesheet to activate.
     */
    setActiveStyleSheet(title) {
        const links = document.querySelectorAll('link[rel*="style"][title]');
        for (let i = 0; i < links.length; i++) {
            links[i].disabled = links[i].getAttribute('title') !== title;
        }
    },

    /**
     * Gets the title of the currently active stylesheet.
     * @returns {string|null} The title of the active stylesheet, or null if none is active.
     */
    getActiveStyleSheet() {
        const activeLink = document.querySelector('link[rel*="style"][title]:not([disabled])');
        return activeLink ? activeLink.getAttribute('title') : null;
    },

    /**
     * Gets the title of the preferred stylesheet.
     * @returns {string|null} The title of the preferred stylesheet, or null if none is found.
     */
    getPreferredStyleSheet() {
        const preferredLink = document.querySelector('link[rel*="style"]:not([rel*="alt"])[title]');
        return preferredLink ? preferredLink.getAttribute('title') : null;
    },

    /**
     * Creates a cookie with a specified name, value, and optional expiration in days.
     * @param {string} name - The name of the cookie.
     * @param {string} value - The value of the cookie.
     * @param {number} [days] - The number of days until the cookie expires (optional).
     */
    createCookie(name, value, days) {
        const date = new Date();
        let expires = "";
        if (days) {
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toGMTString();
        }
        document.cookie = `${name}=${value}${expires}; path=/`;
    },

    /**
     * Reads the value of a cookie by its name.
     * @param {string} name - The name of the cookie.
     * @returns {string|null} The value of the cookie, or null if the cookie is not found.
     */
    readCookie(name) {
        const nameEQ = name + "=";
        const cookies = document.cookie.split(';');
        for (let i = 0; i < cookies.length; i++) {
            let cookie = cookies[i];
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
    antispam() {
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
    genSEF(from, to, allow_slashes) {
        let str = from.value.toLowerCase();
        const slash = allow_slashes ? "\\/" : "";

        const LettersFrom = "абвгдезиклмнопрстуфыэйхё";
        const LettersTo = "abvgdeziklmnoprstufyejxe";
        const Vowel = "аеёиоуыэюя";
        const BiLetters = {
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
            new RegExp(`(ь|ъ)([${Vowel}])`, "g"), "j$2");
        str = str.replace(/(ь|ъ)/g, "");

        let _str = "";
        for (let x = 0; x < str.length; x++) {
            const index = LettersFrom.indexOf(str.charAt(x));
            if (index > -1) {
                _str += LettersTo.charAt(index);
            } else {
                _str += str.charAt(x);
            }
        }
        str = _str;

        _str = "";
        for (let x = 0; x < str.length; x++) {
            if (BiLetters[str.charAt(x)]) {
                _str += BiLetters[str.charAt(x)];
            } else {
                _str += str.charAt(x);
            }
        }
        str = _str;
        str = str.replace(/j{2,}/g, "j");
        str = str.replace(new RegExp(`[^${slash}0-9a-z_\\-]+`, "g"), "");
        to.value = str;
    },

    /**
     * Adds a new poll option to the poll.
     * @param {string} apo - The CSS selector for the poll options container.
     */
    add_poll_option(apo) {
        const delete_button = '<button type="button" class="poll-option-delete" onclick="sedjs.remove_poll_option(this);">x</button>';
        const elem = document.querySelectorAll(apo);
        const last = elem[elem.length - 1];
        const clone = last.cloneNode(true);
        if (clone.querySelector('.poll-option-delete') == null) {
            clone.innerHTML += delete_button;
        }
        const num = clone.querySelector('.num').innerHTML;
        clone.querySelector('.num').innerHTML = parseInt(num, 10) + 1;
        clone.querySelector('input').value = '';
        clone.querySelector('input').setAttribute('name', 'poll_option[]');
        clone.querySelector('input').setAttribute('value', '');
        last.parentNode.insertBefore(clone, last.nextSibling);
    },

    /**
     * Removes a poll option from the poll.
     * @param {HTMLElement} apo - The DOM element representing the poll option to be removed.
     */
    remove_poll_option(apo) {
        const root = apo.parentNode;
        root.parentNode.removeChild(root);
    },

    /**
     * Copies the URL from the href attribute of a link to the clipboard.
     * @param {HTMLElement} el - The DOM element containing the href attribute with the URL to copy.
     */
    copyurl(el) {
        event.preventDefault();
        const cpLink = el.getAttribute('href');
        const dummy = document.createElement("input");
        document.body.appendChild(dummy);
        dummy.setAttribute('value', cpLink);
        dummy.select();
        document.execCommand('copy');
        document.body.removeChild(dummy);
    },

    /**
     * Automatically sets the file title input field based on the selected file name, excluding the file extension.
     */
    autofiletitle() {
        const fileUpload = document.querySelectorAll('.file');
        for (let i = 0; i < fileUpload.length; i++) {
            fileUpload[i].onchange = function() {
                const filename = this.value;
                const inputFileTitleName = this.name.replace('userfile', 'ntitle');
                const k = filename.split('\\').pop().split('/').pop();
                const lastDotIndex = k.lastIndexOf('.');
                const title = (lastDotIndex === -1) ? k : k.substring(0, lastDotIndex);
                document.querySelector(`[name="${inputFileTitleName}"]`).value = title;
            };
        }
    },

    /**
     * Toggles the visibility of spoiler content.
     */
    spoiler() {
        const toggleSpoilerContent = (spoilerToggle) => {
            const spoiler = spoilerToggle.closest('.spoiler');
            const spoilerContent = spoiler.querySelector('.spoiler-content');
            const isHidden = window.getComputedStyle(spoilerContent).display === 'none';

            if (isHidden) {
                spoilerContent.style.display = 'block';
                sedjs.removeClass(spoilerToggle, 'show-icon');
                sedjs.addClass(spoilerToggle, 'hide-icon');
            } else {
                spoilerContent.style.display = 'none';
                sedjs.removeClass(spoilerToggle, 'hide-icon');
                sedjs.addClass(spoilerToggle, 'show-icon');
            }
        };

        const spoilerToggles = document.querySelectorAll('.spoiler-toggle');
        spoilerToggles.forEach((toggle) => {
            toggle.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                toggleSpoilerContent(toggle);
            });
        });

        const spoilerTitles = document.querySelectorAll('.spoiler-title');
        spoilerTitles.forEach((title) => {
            title.addEventListener('click', (e) => {
                e.preventDefault();
                const spoilerToggle = title.querySelector('.spoiler-toggle');
                toggleSpoilerContent(spoilerToggle);
            });
        });
    },

    /**
     * Adds click event listeners to specified elements to fade out and slide up their parent elements.
     * @param {string} [elements] - A CSS selector for the elements to which the click event listeners should be added.
     */
    closealert(elements) {
        const targets = elements || '.close, .alert-close, .fn-close';
        document.querySelectorAll(targets).forEach((element) => {
            element.addEventListener('click', (event) => {
                event.preventDefault();
                const parent = event.currentTarget.parentElement;
                parent.style.transition = 'opacity 0.4s';
                parent.style.opacity = 0;
                setTimeout(() => {
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
    sortable(element, options) {
        // Merge default settings with user-provided options
        const settings = sedjs.extend({
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

        let dragItem = null; // Currently dragged item
        let placeholder = null; // Placeholder element during drag
        let connectedLists = []; // Array of connected sortable containers
        let orderChanged = false; // Flag to track if order has changed
        let items = []; // Array of current sortable items
        let observer = null; // MutationObserver instance for modern browsers
        let intervalId = null; // ID for setInterval fallback in older browsers

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
            const allItems = element.querySelectorAll(settings.items);
            const newItems = [];
            for (let i = 0; i < allItems.length; i++) {
                if (!sedjs.hasClass(allItems[i], 'disabled')) {
                    newItems.push(allItems[i]);
                }
            }

            // Check if items have changed
            let itemsChanged = newItems.length !== items.length;
            if (!itemsChanged) {
                for (let i = 0; i < newItems.length; i++) {
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
            for (let i = 0; i < items.length; i++) {
                const item = items[i];
                const dragElement = settings.handle ?
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
            for (let i = 0; i < items.length; i++) {
                const item = items[i];
                const dragElement = settings.handle ?
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
                observer = new MutationObserver((mutations) => {
                    for (let i = 0; i < mutations.length; i++) {
                        const mutation = mutations[i];
                        if (mutation.addedNodes.length || mutation.removedNodes.length) {
                            const itemsChanged = updateItems();
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
                let lastChildCount = element.children.length;
                intervalId = setInterval(() => {
                    const currentChildCount = element.children.length;
                    if (currentChildCount !== lastChildCount) {
                        const itemsChanged = updateItems();
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
            const target = getItem(e.target);
            if (!target) return;

            dragItem = target;
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/html', target.outerHTML);

            const existingPlaceholders = element.querySelectorAll(`.${settings.placeholder}`);
            for (let i = 0; i < existingPlaceholders.length; i++) {
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

            const links = dragItem.querySelectorAll('a');
            for (let i = 0; i < links.length; i++) {
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
            const target = getItem(e.target) || getClosest(e.target, settings.connectWith);
            if (!target || target === dragItem || target === placeholder || !placeholder) return;

            const rect = target.getBoundingClientRect();
            const isVertical = !settings.axis || settings.axis === 'y';
            const isHorizontal = settings.axis === 'x';

            const shouldInsertBefore = settings.tolerance === 'pointer' ?
                (isVertical ? e.clientY < rect.top + rect.height / 2 :
                    e.clientX < rect.left + rect.width / 2) :
                (isVertical ? rect.top < dragItem.getBoundingClientRect().top :
                    rect.left < dragItem.getBoundingClientRect().left);

            const parent = target.parentNode;
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

            const dropTarget = placeholder.parentNode;

            if (settings.beforeStop) {
                settings.beforeStop(e, { item: dragItem });
            }

            dropTarget.insertBefore(dragItem, placeholder);
            if (placeholder.parentNode) {
                placeholder.parentNode.removeChild(placeholder);
            }

            const isReceived = dropTarget !== element;
            if (isReceived && settings.receive) {
                settings.receive(e, { item: dragItem, sender: element });
            }

            if (orderChanged && settings.update) {
                settings.update(e, { item: dragItem });
            }

            dragItem.style.opacity = '1';
            const links = dragItem.querySelectorAll('a');
            for (let i = 0; i < links.length; i++) {
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
            const links = dragItem.querySelectorAll('a');
            for (let i = 0; i < links.length; i++) {
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
            const startPos = dragItem.getBoundingClientRect();
            const endPos = placeholder.getBoundingClientRect();

            dragItem.style.transition = 'all 0.3s';
            dragItem.style.transform = `translate(${endPos.left - startPos.left}px,${endPos.top - startPos.top}px)`;

            setTimeout(() => {
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
        function matchesSelector(el, selector) {
            if (el.matches) return el.matches(selector);
            if (el.msMatchesSelector) return el.msMatchesSelector(selector);
            const nodes = el.parentNode.querySelectorAll(selector);
            for (let i = 0; i < nodes.length; i++) {
                if (nodes[i] === el) return true;
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
            disable,
            enable,
            destroy,
            update() {
                const itemsChanged = updateItems();
                if (itemsChanged && !settings.disabled) {
                    unbindEvents();
                    bindEvents();
                }
            }
        };
    }
};

// Expose sedjs globally for other scripts (e.g. uploader.js) that expect window.sedjs
window.sedjs = sedjs;

// Initialize functions when DOM is ready
sedjs.ready(() => {
    sedjs.sedtabs();
    sedjs.autofiletitle();
    sedjs.spoiler();
    sedjs.closealert();
    sedjs.getrel("sedthumb");
    const cookie = sedjs.readCookie("style");
    const title = cookie ? cookie : sedjs.getPreferredStyleSheet();
    sedjs.setActiveStyleSheet(title);
});

// Save stylesheet preference before unloading the page
window.addEventListener('beforeunload', (event) => {
    const title = sedjs.getActiveStyleSheet();
    sedjs.createCookie("style", title, 365);
});