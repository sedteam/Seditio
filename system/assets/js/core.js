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
        if (typeof url === 'string') {
            location.href = url;
        } else if (url && url.options && url.selectedIndex >= 0 && url.options[url.selectedIndex]) {
            location.href = url.options[url.selectedIndex].value;
        } else if (url && url.value) {
            location.href = url.value;
        }
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
     * Supports URL hash deep linking (e.g. #tab2) and updates address bar on tab switch.
     * @param {Object} [settings] - The settings for the tab functionality.
     * @param {string} [settings.containerClass='sedtabs'] - CSS class of tab containers.
     * @param {string} [settings.eventType='click'] - Event type triggering tab switch.
     * @param {string} [settings.selectedClass='selected'] - CSS class for active tab.
     * @param {number|string} [settings.defaultTabIndex=0] - Default active tab index or tab id.
     * @param {boolean} [settings.updateHash=true] - Whether to update URL hash in address bar on tab switch.
     * @param {boolean} [settings.useHash=true] - Whether to activate tab from window.location.hash on load and hashchange.
     * @param {string} [settings.historyMethod='replace'] - History API method ('replace' or 'push').
     * @param {Function|false} [settings.beforeSwitchCallback=false] - Callback before tab switch.
     */
    sedtabs(settings) {
        const getElementOrElements = (identifier) => {
            if (identifier.charAt(0) === '#') {
                const byId = document.querySelector(identifier);
                return byId ? [byId] : [];
            }
            return document.querySelectorAll(`.${identifier}`);
        };

        const bindFunction = function (func) {
            const context = this;
            return function () {
                return func.apply(context, arguments);
            };
        };

        const applyToAllElements = (func, elements, args) => {
            for (let i = 0; i < elements.length; i++) {
                func.apply(elements[i], args || []);
            }
        };

        const addClass = function (className, containerClass) {
            sedjs.addClass(this, className);
            const tabTitle = this.getAttribute('data-tabtitle');
            if (tabTitle) {
                const container = (containerClass && this.closest) ? this.closest(`.${containerClass}`) : null;
                const titleEl = (container && container.querySelector('.tab-title')) || document.querySelector('.tab-title');
                if (titleEl) {
                    titleEl.textContent = tabTitle;
                }
            }
        };

        const removeClass = function (className) {
            sedjs.removeClass(this, className);
        };

        const hideElement = function () {
            this.style.display = 'none';
        };

        const showElement = function () {
            this.style.display = 'block';
        };

        const switchTab = function (tabId, tabLinks, tabContents, settings) {
            applyToAllElements(removeClass, tabLinks, [settings.selectedClass]);
            addClass.call(this, settings.selectedClass, settings.containerClass);
            applyToAllElements(hideElement, tabContents);
            applyToAllElements(showElement, document.querySelectorAll(`#${tabId}`));
        };

        const getTabId = (link) => {
            let hash = '';
            if (link && link.hash) {
                hash = link.hash;
            } else if (link && typeof link.getAttribute === 'function') {
                const href = link.getAttribute('href') || '';
                const hashPos = href.indexOf('#');
                if (hashPos !== -1) {
                    hash = href.substring(hashPos);
                }
            }
            return hash ? hash.replace(/^#/, '') : '';
        };

        const safeDecode = (str) => {
            if (!str) return '';
            try {
                return decodeURIComponent(str);
            } catch (e) {
                return str;
            }
        };

        const updateUrlHash = (tabId, method) => {
            if (!tabId) return;
            const targetHash = '#' + tabId;
            if (window.location.hash === targetHash) return;

            if (window.history && (method === 'push' ? window.history.pushState : window.history.replaceState)) {
                const url = window.location.pathname + window.location.search + targetHash;
                try {
                    if (method === 'push') {
                        window.history.pushState(null, '', url);
                    } else {
                        window.history.replaceState(null, '', url);
                    }
                } catch (e) {
                    window.location.hash = tabId;
                }
            } else {
                window.location.hash = tabId;
            }
        };

        const initTabs = (config) => {
            const mergedConfig = sedjs.extend({
                containerClass: 'sedtabs',
                eventType: 'click',
                selectedClass: 'selected',
                defaultTabIndex: 0,
                beforeSwitchCallback: false,
                updateHash: true,
                useHash: true,
                historyMethod: 'replace'
            }, config);

            const tabContainers = document.querySelectorAll(`.${mergedConfig.containerClass}`);
            const initialHash = safeDecode((window.location.hash || '').replace(/^#/, ''));

            const handleTabClick = function (e) {
                if (e) {
                    if (typeof e.preventDefault === 'function') {
                        e.preventDefault();
                    }
                    e.returnValue = false;
                }
                const clickedTab = this;
                const tabId = clickedTab.tabId;

                if (!mergedConfig.beforeSwitchCallback ||
                    mergedConfig.beforeSwitchCallback.apply(clickedTab, this.callbackArgs) !== false) {
                    switchTab.apply(clickedTab, this.callbackArgs);

                    if (mergedConfig.updateHash && tabId) {
                        updateUrlHash(tabId, mergedConfig.historyMethod);
                    }
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
                    const link = tabLinks[j];
                    const tabId = safeDecode(getTabId(link));

                    if (tabId && (tabId.indexOf('tab') === 0 || link.href.indexOf('#tab') !== -1 || document.getElementById(tabId))) {
                        const contentElements = document.querySelectorAll(`#${tabId}`);
                        if (contentElements.length > 0) {
                            tabIds.push(tabId);
                            tabs.push(link);
                            for (let k = 0; k < contentElements.length; k++) {
                                tabContents.push(contentElements[k]);
                            }
                        }
                    }
                }

                if (tabs.length === 0) continue;

                for (let j = 0; j < tabs.length; j++) {
                    const callbackArgs = [
                        tabIds[j],
                        tabs,
                        tabContents,
                        mergedConfig
                    ];

                    tabs[j].tabId = tabIds[j];
                    tabs[j].tabLinks = tabs;
                    tabs[j].tabContents = tabContents;
                    tabs[j].callbackArgs = callbackArgs;

                    tabs[j][`on${mergedConfig.eventType}`] =
                        bindFunction.call(tabs[j], handleTabClick);

                    if (mergedConfig.eventType !== 'click') {
                        tabs[j].onclick = function (e) {
                            if (e && typeof e.preventDefault === 'function') {
                                e.preventDefault();
                            }
                            return false;
                        };
                    }
                }

                // Determine active tab index for this container
                let activeIndex = -1;

                // 1. Activate tab from URL hash if useHash is enabled
                if (mergedConfig.useHash && initialHash) {
                    const hashIdx = tabIds.indexOf(initialHash);
                    if (hashIdx !== -1) {
                        activeIndex = hashIdx;
                    }
                }

                // 2. Check if defaultTabIndex is a matching tab id
                if (activeIndex === -1 && typeof mergedConfig.defaultTabIndex === 'string') {
                    const namedIdx = tabIds.indexOf(mergedConfig.defaultTabIndex);
                    if (namedIdx !== -1) {
                        activeIndex = namedIdx;
                    }
                }

                // 3. Check if any tab already has selectedClass in DOM
                if (activeIndex === -1) {
                    for (let j = 0; j < tabs.length; j++) {
                        if (sedjs.hasClass(tabs[j], mergedConfig.selectedClass)) {
                            activeIndex = j;
                            break;
                        }
                    }
                }

                // 4. Fallback to defaultTabIndex (number) or first tab (0)
                if (activeIndex === -1) {
                    activeIndex = (typeof mergedConfig.defaultTabIndex === 'number' &&
                                  mergedConfig.defaultTabIndex >= 0 &&
                                  mergedConfig.defaultTabIndex < tabs.length)
                        ? mergedConfig.defaultTabIndex
                        : 0;
                }

                if (activeIndex >= 0 && activeIndex < tabs.length) {
                    switchTab.call(
                        tabs[activeIndex],
                        tabIds[activeIndex],
                        tabs,
                        tabContents,
                        mergedConfig
                    );
                }

                // Store metadata on container for hashchange support
                container._sedtabsData = {
                    tabIds: tabIds,
                    tabs: tabs,
                    tabContents: tabContents,
                    config: mergedConfig,
                    switchTab: function (idx) {
                        if (idx >= 0 && idx < tabs.length) {
                            switchTab.call(tabs[idx], tabIds[idx], tabs, tabContents, mergedConfig);
                        }
                    }
                };
            }

            // Bind single hashchange listener if useHash is enabled
            if (mergedConfig.useHash && !sedjs.sedtabs._hashListenerBound) {
                window.addEventListener('hashchange', () => {
                    const rawHash = (window.location.hash || '').replace(/^#/, '');
                    if (!rawHash) return;
                    const currentHash = safeDecode(rawHash);

                    const containers = document.querySelectorAll(`.${mergedConfig.containerClass}`);
                    for (let i = 0; i < containers.length; i++) {
                        const container = containers[i];
                        if (!container._sedtabsData) continue;
                        const data = container._sedtabsData;
                        const idx = data.tabIds.indexOf(currentHash);
                        if (idx !== -1 && !sedjs.hasClass(data.tabs[idx], data.config.selectedClass)) {
                            data.switchTab(idx);
                        }
                    }
                });
                sedjs.sedtabs._hashListenerBound = true;
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

        xhr.onreadystatechange = function () {
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
            const intElemOffsetWidth = Math.floor(loadingElement.offsetWidth / 2) - 8;
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
     * Modal & Floating Windows functions
     */
    modal: {
        imagefiles: ['/system/img/vars/min.gif', '/system/img/vars/close.gif', '/system/img/vars/restore.gif', '/system/img/vars/resize.gif'],
        maxheightimage: 600,
        maxwidthimage: 800,
        minimizeorder: 0,
        zIndexvalue: 1000,
        tobjects: [],
        lastactivet: null,
        constrainToViewport: true,

        /**
         * Initialize a new modal / floating window.
         * @param {string} t - The ID of the window to create.
         * @returns {HTMLElement|null}
         */
        init(t) {
            const domwindow = document.createElement("div");
            domwindow.id = t;
            sedjs.addClass(domwindow, "sed_modal");
            domwindow.style.position = "fixed";

            const domwindowdata = [
                '<div class="modal-handle">',
                '  <span class="modal-title">Modal Window</span>',
                '  <div class="modal-controls">',
                '    <button type="button" class="modal-control-btn modal-btn-min" title="Minimize" aria-label="Minimize">',
                '      <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M19 13H5v-2h14v2z"/></svg>',
                '    </button>',
                '    <button type="button" class="modal-control-btn modal-btn-max" title="Maximize" aria-label="Maximize">',
                '      <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M4 4h16v16H4V4zm2 4v10h12V8H6z"/></svg>',
                '    </button>',
                '    <button type="button" class="modal-control-btn modal-btn-close" title="Close" aria-label="Close">',
                '      <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>',
                '    </button>',
                '  </div>',
                '</div>',
                '<div class="modal-body">',
                '  <div class="modal-iframe-cover"></div>',
                `  <div class="modal-contentarea" id="area-${t}"></div>`,
                '</div>',
                '<div class="modal-statusarea">',
                '  <div class="modal-resizearea" title="Resize">',
                '    <svg width="14" height="14" viewBox="0 0 16 16" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"><line x1="14" y1="4" x2="4" y2="14"/><line x1="14" y1="8" x2="8" y2="14"/><line x1="14" y1="12" x2="12" y2="14"/></svg>',
                '  </div>',
                '</div>'
            ].join('');

            domwindow.innerHTML = domwindowdata;
            document.body.appendChild(domwindow);

            const tElement = document.querySelector(`#${t}`);
            if (!tElement) return null;

            tElement.handle = tElement.querySelector('.modal-handle');
            tElement.titleElement = tElement.querySelector('.modal-title');
            tElement.controls = tElement.querySelector('.modal-controls');
            tElement.body = tElement.querySelector('.modal-body');
            tElement.cover = tElement.querySelector('.modal-iframe-cover');
            tElement.contentarea = tElement.querySelector('.modal-contentarea');
            tElement.statusarea = tElement.querySelector('.modal-statusarea');
            tElement.resizearea = tElement.querySelector('.modal-resizearea');

            tElement.handle._parent = tElement;
            tElement.resizearea._parent = tElement;
            tElement.controls._parent = tElement;

            tElement.onclose = function () { return true; };
            tElement.onpointerdown = () => sedjs.modal.setfocus(tElement);

            // Bind controls
            const btnMin = tElement.querySelector('.modal-btn-min');
            const btnMax = tElement.querySelector('.modal-btn-max');
            const btnClose = tElement.querySelector('.modal-btn-close');

            if (btnMin) btnMin.onclick = (e) => { e.stopPropagation(); sedjs.modal.minimize(btnMin, tElement); };
            if (btnMax) btnMax.onclick = (e) => { e.stopPropagation(); sedjs.modal.maximize(btnMax, tElement); };
            if (btnClose) btnClose.onclick = (e) => { e.stopPropagation(); sedjs.modal.close(tElement); };

            // Setup pointer drag & resize
            sedjs.modal.setupPointerDrag(tElement);
            sedjs.modal.setupPointerResize(tElement);

            tElement.show = sedjs.modal.show.bind(this, tElement);
            tElement.hide = sedjs.modal.hide.bind(this, tElement);
            tElement.close = sedjs.modal.close.bind(this, tElement);
            tElement.setSize = sedjs.modal.setSize.bind(this, tElement);
            tElement.moveTo = sedjs.modal.moveTo.bind(this, tElement);
            tElement.isResize = sedjs.modal.isResize.bind(this, tElement);
            tElement.isScrolling = sedjs.modal.isScrolling.bind(this, tElement);
            tElement.load = sedjs.modal.load.bind(this, tElement);
            tElement.minimize = () => sedjs.modal.minimize(btnMin, tElement);
            tElement.maximize = () => sedjs.modal.maximize(btnMax, tElement);
            tElement.restore = () => sedjs.modal.restore(null, tElement);

            this.tobjects.push(tElement);
            return tElement;
        },

        /**
         * Open a modal / floating window with specified content.
         * @param {string} t - Window ID
         * @param {string} contenttype - 'iframe', 'image', 'ajax', 'inline', 'div'
         * @param {string} contentsource - URL or HTML
         * @param {string} title - Window title
         * @param {string} attr - Parameters: width, height, center, resize, scrolling
         * @param {string} recalonload - Position recalculation flag
         * @returns {HTMLElement|null}
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
                tElement.style.display = 'flex';
                tElement.contentarea.style.display = 'block';
                tElement.style.opacity = '1';

                overlay.onclick = (e) => {
                    if (e.target === overlay) {
                        sedjs.modal.close(tElement);
                    }
                };
            } else {
                let width = getValue("width") || 800;
                let height = getValue("height") || 550;
                const isCenter = getValue("center");

                // Clamp to viewport
                width = Math.min(width, window.innerWidth - 30);
                height = Math.min(height, window.innerHeight - 50);

                const xpos = isCenter ? "middle" : (getValue("left") || 40);
                const ypos = isCenter ? "middle" : (getValue("top") || 40);

                tElement.setSize(width, height);
                tElement.moveTo(xpos, ypos);
                tElement.isResize(getValue("resize") !== 0);
                tElement.isScrolling(getValue("scrolling") !== 0);

                tElement.style.visibility = 'visible';
                tElement.style.display = 'flex';
                tElement.style.opacity = '1';
                tElement.contentarea.style.display = 'block';
            }

            tElement.load(contenttype, contentsource, title);

            // Expose globally for child iframe scripts (PFS, CKEditor callback)
            window.modal = tElement;

            return tElement;
        },

        /**
         * Setup pointer-based drag for the window header.
         * @param {HTMLElement} t - Window element
         */
        setupPointerDrag(t) {
            const handle = t.handle;
            handle.addEventListener('pointerdown', (e) => {
                // If minimized, restore on header click (except close button)
                if (t.classList.contains('is-minimized')) {
                    if (!e.target.closest('.modal-btn-close')) {
                        sedjs.modal.restore(null, t);
                    }
                    return;
                }

                if (e.target.closest('.modal-controls') || t.classList.contains('is-maximized')) return;
                e.preventDefault();
                sedjs.modal.setfocus(t);

                if (window.getSelection) {
                    window.getSelection().removeAllRanges();
                }
                document.body.style.userSelect = 'none';
                document.body.style.webkitUserSelect = 'none';

                if (t.cover) t.cover.style.display = 'block';

                const startX = e.clientX;
                const startY = e.clientY;
                const initLeft = parseInt(t.style.left, 10) || t.offsetLeft;
                const initTop = parseInt(t.style.top, 10) || t.offsetTop;

                const onPointerMove = (evt) => {
                    evt.preventDefault();
                    const dx = evt.clientX - startX;
                    const dy = evt.clientY - startY;
                    let newX = initLeft + dx;
                    let newY = initTop + dy;

                    const maxX = window.innerWidth - t.offsetWidth;
                    const maxY = window.innerHeight - (t.handle.offsetHeight || 36);
                    newX = Math.max(0, Math.min(newX, maxX));
                    newY = Math.max(0, Math.min(newY, maxY));

                    t.style.left = `${newX}px`;
                    t.style.top = `${newY}px`;
                };

                const onPointerUp = () => {
                    document.body.style.userSelect = '';
                    document.body.style.webkitUserSelect = '';
                    if (t.cover) t.cover.style.display = 'none';
                    window.removeEventListener('pointermove', onPointerMove);
                    window.removeEventListener('pointerup', onPointerUp);
                    window.removeEventListener('pointercancel', onPointerUp);
                };

                window.addEventListener('pointermove', onPointerMove);
                window.addEventListener('pointerup', onPointerUp);
                window.addEventListener('pointercancel', onPointerUp);
            });
        },

        /**
         * Setup pointer-based resizing.
         * @param {HTMLElement} t - Window element
         */
        setupPointerResize(t) {
            const resizer = t.resizearea;
            if (!resizer) return;

            resizer.addEventListener('pointerdown', (e) => {
                if (t.classList.contains('is-maximized') || t.classList.contains('is-minimized')) return;
                e.stopPropagation();
                e.preventDefault();
                sedjs.modal.setfocus(t);

                if (window.getSelection) {
                    window.getSelection().removeAllRanges();
                }
                document.body.style.userSelect = 'none';
                document.body.style.webkitUserSelect = 'none';

                if (t.cover) t.cover.style.display = 'block';

                const startX = e.clientX;
                const startY = e.clientY;
                const startW = t.offsetWidth;
                const startH = t.offsetHeight;

                const onPointerMove = (evt) => {
                    evt.preventDefault();
                    const dx = evt.clientX - startX;
                    const dy = evt.clientY - startY;
                    const maxW = window.innerWidth - (parseInt(t.style.left, 10) || 0) - 10;
                    const maxH = window.innerHeight - (parseInt(t.style.top, 10) || 0) - 10;
                    const newW = Math.max(240, Math.min(startW + dx, maxW));
                    const newH = Math.max(100, Math.min(startH + dy, maxH));

                    t.style.width = `${newW}px`;
                    t.style.height = `${newH}px`;
                };

                const onPointerUp = () => {
                    document.body.style.userSelect = '';
                    document.body.style.webkitUserSelect = '';
                    if (t.cover) t.cover.style.display = 'none';
                    window.removeEventListener('pointermove', onPointerMove);
                    window.removeEventListener('pointerup', onPointerUp);
                    window.removeEventListener('pointercancel', onPointerUp);
                };

                window.addEventListener('pointermove', onPointerMove);
                window.addEventListener('pointerup', onPointerUp);
                window.addEventListener('pointercancel', onPointerUp);
            });
        },

        /**
         * Set the size of the modal window.
         * @param {HTMLElement} t - Window element
         * @param {number} w - Width
         * @param {number} h - Height
         */
        setSize(t, w, h) {
            const width = Math.max(parseInt(w, 10) || 800, 240);
            const height = Math.max(parseInt(h, 10) || 550, 100);
            t.style.width = `${width}px`;
            t.style.height = `${height}px`;
            if (t.contentarea) {
                t.contentarea.style.height = '100%';
            }
        },

        /**
         * Enable or disable resizing.
         * @param {HTMLElement} t - Window element
         * @param {boolean} bol - Status
         */
        isResize(t, bol) {
            t.statusarea.style.display = bol ? "flex" : "none";
            t.resizeBool = bol ? 1 : 0;
        },

        /**
         * Scale image size.
         */
        scaleSize(maxW, maxH, currW, currH) {
            const ratio = currH / currW;
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
         * Enable or disable scrolling.
         */
        isScrolling(t, bol) {
            t.contentarea.style.overflow = bol ? "auto" : "hidden";
        },

        /**
         * Load content into window.
         */
        load(t, contenttype, contentsource, title) {
            if (t.isClosed) {
                return;
            }
            contenttype = (contenttype || '').toLowerCase();
            if (title) {
                if (t.titleElement) {
                    t.titleElement.textContent = title;
                } else if (t.handle && t.handle.firstChild) {
                    t.handle.firstChild.nodeValue = title;
                }
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
         * Loading indicator management.
         */
        manageLoader(t, show, isImage) {
            if (show) {
                let loaderDiv = t.querySelector('.loading-indicator');
                if (!loaderDiv) {
                    loaderDiv = document.createElement("div");
                    sedjs.addClass(loaderDiv, "loading-indicator");
                    if (isImage) {
                        loaderDiv.setAttribute("style", "position:fixed; top:50%; left:50%; transform: translate(-50%, -50%); z-index:1001;");
                        document.body.appendChild(loaderDiv);
                        t.loading = loaderDiv;
                    } else {
                        loaderDiv.setAttribute("style", "position:absolute; top:50%; left:50%; transform: translate(-50%, -50%);");
                        t.contentarea.appendChild(loaderDiv);
                    }
                }
                return loaderDiv;
            } else {
                if (isImage && t.loading && t.loading.parentNode) {
                    t.loading.parentNode.removeChild(t.loading);
                    t.loading = null;
                } else {
                    const loaderDiv = t.contentarea.querySelector(".loading-indicator");
                    if (loaderDiv && loaderDiv.parentNode) {
                        loaderDiv.parentNode.removeChild(loaderDiv);
                    }
                }
            }
        },

        /**
         * Load image content.
         */
        loadImage(t, contentsource) {
            this.manageLoader(t, true, true);

            const i = new Image();
            const self = this;
            i.onload = () => {
                self.handleResize(t, i);

                let closeBtn = t.contentarea.querySelector('.close-image');
                if (!closeBtn) {
                    closeBtn = document.createElement('div');
                    closeBtn.innerHTML = '✕';
                    closeBtn.className = 'close-image';
                    closeBtn.onclick = () => { sedjs.modal.close(t); };
                    t.contentarea.appendChild(closeBtn);
                }

                t.contentarea.appendChild(i);
                self.manageLoader(t, false, true);

                t.style.opacity = '0';
                setTimeout(() => { t.style.opacity = '1'; }, 10);

                if (!t.resizeHandler) {
                    t.resizeHandler = () => { self.handleResize(t, i); };
                    window.addEventListener('resize', t.resizeHandler);
                }
            };
            i.onerror = () => {
                self.manageLoader(t, false, true);
                t.contentarea.innerHTML = 'Error loading image';
            };

            i.src = contentsource;
        },

        /**
         * Handle image resizing.
         */
        handleResize(t, img) {
            const maxW = window.innerWidth - 40;
            const maxH = window.innerHeight - 40;

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

            t.setSize(newWidth, newHeight);

            const left = Math.max(0, (window.innerWidth - newWidth) / 2);
            const top = Math.max(0, (window.innerHeight - newHeight) / 2);

            t.style.position = 'fixed';
            t.style.left = `${left}px`;
            t.style.top = `${top}px`;

            img.width = newWidth;
            img.height = newHeight;
        },

        /**
         * Load iframe content.
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
                iframe.style.border = "0";
                iframe.name = `_iframe-${t.id}`;
                iframe.id = `id_iframe-${t.id}`;
                t.contentarea.appendChild(iframe);
            }

            const self = this;
            iframe.onload = () => { self.manageLoader(t, false, false); };
            iframe.src = contentsource;
        },

        /**
         * Load inline HTML.
         */
        loadInline(t, contentsource) {
            t.contentarea.innerHTML = contentsource;
        },

        /**
         * Load div content.
         */
        loadDiv(t, contentsource) {
            const inlinedivref = document.querySelector(`#${contentsource}`);
            if (inlinedivref) {
                t.contentarea.innerHTML = (inlinedivref.defaultHTML || inlinedivref.innerHTML);
                if (!inlinedivref.defaultHTML) {
                    inlinedivref.defaultHTML = inlinedivref.innerHTML;
                }
                inlinedivref.innerHTML = "";
                inlinedivref.style.display = "none";
            }
        },

        /**
         * Load AJAX content.
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
         * Legacy drag setup (kept for backwards compatibility).
         */
        setupdrag(t, e) {
            return false;
        },

        getdistance(e) {
            return false;
        },

        move(t, e) {},

        resize(t, e) {},

        enablecontrols(t, e) {
            const sourceobj = window.event ? window.event.srcElement : e.target;
            const btn = sourceobj.closest('.modal-control-btn') || sourceobj;
            const title = btn.getAttribute("title") || "";
            if (/Minimize/i.test(title)) {
                this.minimize(btn, t);
            } else if (/Maximize/i.test(title)) {
                this.maximize(btn, t);
            } else if (/Restore/i.test(title)) {
                this.restore(btn, t);
            } else if (/Close/i.test(title)) {
                this.close(t);
            }
            return false;
        },

        /**
         * Minimize window to bottom-left corner.
         * @param {HTMLElement} button - Button element
         * @param {HTMLElement} t - Window element
         */
        minimize(button, t) {
            if (t.classList.contains('is-minimized')) {
                this.restore(button, t);
                return;
            }

            t.savedLeft = t.style.left;
            t.savedTop = t.style.top;
            t.savedWidth = t.style.width;
            t.savedHeight = t.style.height;

            const minimizedModals = this.tobjects.filter(win => win !== t && win.classList.contains('is-minimized'));
            t.minimizeOrder = minimizedModals.length;

            t.classList.add('is-minimized');
            t.state = "minimized";

            t.style.left = '10px';
            t.style.top = 'auto';
            t.style.bottom = `${10 + t.minimizeOrder * 42}px`;
            t.style.width = '240px';
            t.style.height = 'auto';

            const btnMin = t.querySelector('.modal-btn-min');
            if (btnMin) {
                btnMin.setAttribute('title', 'Restore');
                btnMin.innerHTML = '<svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M4 8h16v12H4V8zm2 2v8h12v-8H6zm2-6h12v2H8V4z"/></svg>';
            }
        },

        /**
         * Maximize window to viewport size or restore.
         * @param {HTMLElement} button - Button element
         * @param {HTMLElement} t - Window element
         */
        maximize(button, t) {
            if (t.classList.contains('is-minimized')) {
                this.restore(null, t);
            }
            if (t.classList.contains('is-maximized')) {
                t.classList.remove('is-maximized');
                t.style.left = t.savedLeft || '40px';
                t.style.top = t.savedTop || '40px';
                t.style.width = t.savedWidth || '800px';
                t.style.height = t.savedHeight || '550px';
                const btnMax = t.querySelector('.modal-btn-max');
                if (btnMax) {
                    btnMax.setAttribute('title', 'Maximize');
                    btnMax.innerHTML = '<svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M4 4h16v16H4V4zm2 4v10h12V8H6z"/></svg>';
                }
            } else {
                t.savedLeft = t.style.left;
                t.savedTop = t.style.top;
                t.savedWidth = t.style.width;
                t.savedHeight = t.style.height;

                t.classList.add('is-maximized');
                t.style.left = '8px';
                t.style.top = '8px';
                t.style.width = `${window.innerWidth - 16}px`;
                t.style.height = `${window.innerHeight - 16}px`;

                const btnMax = t.querySelector('.modal-btn-max');
                if (btnMax) {
                    btnMax.setAttribute('title', 'Restore');
                    btnMax.innerHTML = '<svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M4 8h16v12H4V8zm2 2v8h12v-8H6zm2-6h12v2H8V4z"/></svg>';
                }
            }
        },

        /**
         * Restore minimized window.
         * @param {HTMLElement} button - Button element
         * @param {HTMLElement} t - Window element
         */
        restore(button, t) {
            t.classList.remove('is-minimized');
            t.state = "fullview";

            t.style.bottom = 'auto';
            t.style.left = t.savedLeft || '40px';
            t.style.top = t.savedTop || '40px';
            t.style.width = t.savedWidth || '800px';
            t.style.height = t.savedHeight || '550px';

            const btnMin = t.querySelector('.modal-btn-min');
            if (btnMin) {
                btnMin.setAttribute('title', 'Minimize');
                btnMin.innerHTML = '<svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M19 13H5v-2h14v2z"/></svg>';
            }

            const remaining = this.tobjects.filter(win => win.classList.contains('is-minimized'));
            remaining.forEach((win, idx) => {
                win.minimizeOrder = idx;
                win.style.bottom = `${10 + idx * 42}px`;
            });

            this.setfocus(t);
        },

        /**
         * Close modal window.
         * @param {HTMLElement} t - Window element
         * @returns {boolean}
         */
        close(t) {
            if (!t) return false;
            const overlay = document.querySelector('.modal-overlay');
            const closeResult = t.onclose ? t.onclose() : true;
            if (closeResult !== false) {
                t.style.opacity = '0';
                if (overlay && sedjs.hasClass(t, 'image-modal')) {
                    overlay.style.opacity = '0';
                    setTimeout(() => {
                        if (overlay.parentNode) overlay.parentNode.removeChild(overlay);
                    }, 250);
                }

                if (t.classList.contains('is-minimized')) {
                    const remaining = this.tobjects.filter(win => win !== t && win.classList.contains('is-minimized'));
                    remaining.forEach((win, idx) => {
                        win.minimizeOrder = idx;
                        win.style.bottom = `${10 + idx * 42}px`;
                    });
                }

                if (t.resizeHandler) {
                    window.removeEventListener('resize', t.resizeHandler);
                    t.resizeHandler = null;
                }

                setTimeout(() => {
                    t.style.display = 'none';
                    if (t.parentNode) t.parentNode.removeChild(t);
                    sedjs.removeClass(t, 'image-modal');
                }, 200);

                t.isClosed = true;
                this.tobjects = this.tobjects.filter(item => item !== t);
            }
            return true;
        },

        /**
         * Set focus on active window.
         * @param {HTMLElement} t - Window element
         */
        setfocus(t) {
            if (!t) return;
            this.zIndexvalue++;
            t.style.zIndex = this.zIndexvalue;
            t.isClosed = false;

            this.tobjects.forEach(win => {
                sedjs.removeClass(win, 'is-active');
            });
            sedjs.addClass(t, 'is-active');
            this.lastactivet = t;
        },

        /**
         * Show window.
         */
        show(t) {
            if (t.isClosed) return;
            if (t.classList.contains('is-minimized')) {
                this.restore(null, t);
            } else {
                t.style.display = "flex";
                t.style.opacity = "1";
            }
            this.setfocus(t);
            t.state = "fullview";
        },

        /**
         * Hide window.
         */
        hide(t) {
            t.style.display = "none";
        },

        /**
         * Opacity helper (legacy compatibility).
         */
        setopacity(targetobject, value) {
            if (targetobject) {
                targetobject.style.opacity = value;
            }
        },

        /**
         * Move window to coordinates.
         */
        moveTo(t, x, y) {
            let newX, newY;
            if (x === "middle") {
                newX = Math.max(0, (window.innerWidth - t.offsetWidth) / 2);
            } else {
                newX = parseInt(x, 10) || 0;
            }

            if (y === "middle") {
                newY = Math.max(0, (window.innerHeight - t.offsetHeight) / 2);
            } else {
                newY = parseInt(y, 10) || 0;
            }

            if (this.constrainToViewport) {
                newX = Math.max(0, Math.min(newX, window.innerWidth - t.offsetWidth));
                newY = Math.max(0, Math.min(newY, window.innerHeight - (t.handle ? t.handle.offsetHeight : 36)));
            }

            t.style.left = `${newX}px`;
            t.style.top = `${newY}px`;
        },

        /**
         * Viewport helper (legacy compatibility).
         */
        getviewpoint() {
            this.docwidth = window.innerWidth;
            this.docheight = window.innerHeight;
            this.scroll_top = window.pageYOffset || document.documentElement.scrollTop || 0;
            this.scroll_left = window.pageXOffset || document.documentElement.scrollLeft || 0;
        },

        rememberattrs(t) {
            t.lastx = parseInt(t.style.left || t.offsetLeft, 10);
            t.lasty = parseInt(t.style.top || t.offsetTop, 10);
            t.lastwidth = parseInt(t.style.width, 10);
        },

        stop() {},

        addEvent(target, functionref, tasktype) {
            if (target && target.addEventListener) {
                target.addEventListener(tasktype, functionref, false);
            }
        },

        removeEvent(target, functionref, tasktype) {
            if (target && target.removeEventListener) {
                target.removeEventListener(tasktype, functionref, false);
            }
        },

        cleanup() {
            this.tobjects = [];
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
        const delete_button = '<button type="button" class="poll-option-delete" onclick="sedjs.remove_poll_option(this);">&times;</button>';
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
        const fileUpload = document.querySelectorAll('.file, .sed-file, input[name^="userfile"]');
        for (let i = 0; i < fileUpload.length; i++) {
            fileUpload[i].addEventListener('change', function () {
                const filename = this.value;
                if (!this.name || !this.name.includes('userfile')) return;
                const inputFileTitleName = this.name.replace('userfile', 'ntitle');
                const titleInput = document.querySelector(`[name="${inputFileTitleName}"]`);
                if (titleInput) {
                    const k = filename.split('\\').pop().split('/').pop();
                    const lastDotIndex = k.lastIndexOf('.');
                    const title = (lastDotIndex === -1) ? k : k.substring(0, lastDotIndex);
                    titleInput.value = title;
                }
            });
        }
    },

    /**
     * Toggles the visibility of spoiler content.
     * @param {Object} [options] - Configuration options for spoiler.
     */
    spoiler(options) {
        const config = sedjs.extend({
            container: 'spoiler',
            title: 'spoiler-title',
            content: 'spoiler-content',
            toggle: 'spoiler-toggle',
            activeClass: 'active',
            showIconClass: 'show-icon',
            hideIconClass: 'hide-icon'
        }, options);

        const containerSelector = config.container.charAt(0) === '.' ? config.container : `.${config.container}`;
        const titleSelector = config.title.split(',').map(s => s.trim().charAt(0) === '.' ? s.trim() : `.${s.trim()}`).join(', ');
        const contentSelector = config.content.charAt(0) === '.' ? config.content : `.${config.content}`;
        const toggleSelector = config.toggle.charAt(0) === '.' ? config.toggle : `.${config.toggle}`;

        document.addEventListener('click', (e) => {
            const title = e.target.closest(titleSelector);
            if (!title) return;

            const spoiler = title.closest(containerSelector);
            if (!spoiler) return;

            e.preventDefault();
            e.stopPropagation();

            const content = spoiler.querySelector(contentSelector);
            if (content) {
                const isHidden = content.style.display === 'none' || (content.style.display === '' && window.getComputedStyle(content).display === 'none');
                
                if (isHidden) {
                    content.style.display = 'block';
                    spoiler.classList.add(config.activeClass);
                    title.classList.add(config.activeClass);
                } else {
                    content.style.display = 'none';
                    spoiler.classList.remove(config.activeClass);
                    title.classList.remove(config.activeClass);
                }

                const toggleEl = title.querySelector(toggleSelector);
                if (toggleEl) {
                    if (isHidden) {
                        toggleEl.classList.remove(config.showIconClass);
                        toggleEl.classList.add(config.hideIconClass);
                    } else {
                        toggleEl.classList.remove(config.hideIconClass);
                        toggleEl.classList.add(config.showIconClass);
                    }
                }
            }
        });
    },

    /**
     * Toggles the visibility of accordion items (closing sibling items, clicking active closes it).
     * @param {Object} [options] - Configuration options for accordion.
     */
    accordion(options) {
        const config = sedjs.extend({
            container: 'sedaccordion',
            item: 'accordion-item',
            header: 'accordion-header',
            content: 'accordion-content',
            activeClass: 'active'
        }, options);

        const containerSelector = config.container.charAt(0) === '.' ? config.container : `.${config.container}`;
        const itemSelector = config.item.charAt(0) === '.' ? config.item : `.${config.item}`;
        const headerSelector = config.header.split(',').map(s => s.trim().charAt(0) === '.' ? s.trim() : `.${s.trim()}`).join(', ');
        const contentSelector = config.content.charAt(0) === '.' ? config.content : `.${config.content}`;

        document.addEventListener('click', (e) => {
            const header = e.target.closest(headerSelector);
            if (!header) return;

            const accordion = header.closest(containerSelector);
            const item = header.closest(itemSelector);

            if (item && accordion) {
                e.preventDefault();
                const isAlreadyActive = item.classList.contains(config.activeClass);
                const allItems = accordion.querySelectorAll(itemSelector);
                allItems.forEach((el) => {
                    el.classList.remove(config.activeClass);
                    const content = el.querySelector(contentSelector);
                    if (content) {
                        content.style.display = 'none';
                    }
                });

                if (!isAlreadyActive) {
                    item.classList.add(config.activeClass);
                    const content = item.querySelector(contentSelector);
                    if (content) {
                        content.style.display = 'block';
                    }
                }
            }
        });
    },

    /**
     * Adds click event listeners to specified elements to fade out and slide up their parent elements.
     * Also processes containers with data-autoclose (ms) to auto-hide alerts after the given delay.
     * @param {string} [elements] - A CSS selector for the elements to which the click event listeners should be added.
     */
    closealert(elements) {
        const hideAlert = (alertEl) => {
            if (!alertEl) return;
            alertEl.style.transition = 'opacity 0.4s';
            alertEl.style.opacity = 0;
            setTimeout(() => {
                alertEl.style.transition = 'height 0.4s';
                alertEl.style.height = 0;
                alertEl.style.overflow = 'hidden';
                alertEl.style.display = 'none';
            }, 400);
        };

        const targets = elements || '.close, .alert-close, .fn-close';
        document.querySelectorAll(targets).forEach((element) => {
            element.addEventListener('click', (event) => {
                event.preventDefault();
                hideAlert(event.currentTarget.parentElement);
            });
        });

        document.querySelectorAll('[data-autoclose]').forEach((container) => {
            const delay = parseInt(container.getAttribute('data-autoclose'), 10);
            if (isNaN(delay) || delay <= 0) return;
            const alertEl = container.querySelector('.alert');
            if (alertEl) {
                setTimeout(() => hideAlert(alertEl), delay);
            }
        });
    },

    /**
     * Popover: click-triggered floating panel with template in JS.
     * Closes on [data-sed-popover-close] and on click outside.
     * Position via data-sed-popover-position on trigger: left, right, top, bottom, center.
     */
    popover: {
        template: '<div class="sed-popover" style="display:none;" data-sed-popover>' +
            '<button type="button" class="sed-popover-close" data-sed-popover-close>×</button>' +
            '<div class="sed-popover-body"></div></div>',
        activePopover: null,

        init(containerEl) {
            const container = containerEl || document;
            const triggers = container.querySelectorAll('[data-sed-popover-trigger]');

            for (let i = 0; i < triggers.length; i++) {
                const trigger = triggers[i];
                if (trigger._sedPopoverInit) continue;
                trigger._sedPopoverInit = true;

                const targetId = trigger.getAttribute('data-sed-popover-target');
                const contentEl = targetId ?
                    document.getElementById(targetId) :
                    trigger.parentElement.querySelector('[data-sed-popover-content]');
                if (!contentEl) continue;

                const popover = this.createFromContent(contentEl);
                const parent = trigger.parentElement;
                if (!parent) continue;

                const cs = window.getComputedStyle(parent);
                if (!parent.style.position || cs.position === 'static') {
                    parent.style.position = 'relative';
                }
                parent.appendChild(popover);

                popover._sedTrigger = trigger;
                popover._sedContent = contentEl;
                contentEl.style.display = 'none';

                trigger.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    this.toggle(trigger, popover);
                });

                const closeBtn = popover.querySelector('[data-sed-popover-close]');
                if (closeBtn) {
                    closeBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        this.hide(popover);
                    });
                }
            }

            if (!document._sedPopoverDocClick) {
                document._sedPopoverDocClick = true;
                document.addEventListener('click', (e) => {
                    const active = this.activePopover;
                    if (!active) return;
                    const target = e.target;
                    if (active.contains(target) || (active._sedTrigger && active._sedTrigger.contains(target))) return;
                    this.hide(active);
                });
            }
        },

        createFromContent(contentEl) {
            const wrap = document.createElement('div');
            wrap.innerHTML = this.template.trim();
            const popover = wrap.firstChild;
            const body = popover.querySelector('.sed-popover-body');
            while (contentEl.firstChild) {
                body.appendChild(contentEl.firstChild);
            }
            return popover;
        },

        getPosition(trigger) {
            const pos = (trigger.getAttribute('data-sed-popover-position') || 'bottom').toLowerCase();
            const allowed = ['left', 'right', 'top', 'bottom', 'center'];
            return allowed.indexOf(pos) >= 0 ? pos : 'bottom';
        },

        position(trigger, popover) {
            const pos = this.getPosition(trigger);
            const tRect = trigger.getBoundingClientRect();
            const pRect = popover.getBoundingClientRect();
            const parent = popover.offsetParent || document.body;
            const parentRect = parent.getBoundingClientRect();
            const scrollY = window.pageYOffset || document.documentElement.scrollTop;
            const scrollX = window.pageXOffset || document.documentElement.scrollLeft;

            let top = 0;
            let left = 0;

            if (parent === document.body) {
                top = scrollY;
                left = scrollX;
            }

            const tLeft = tRect.left - parentRect.left;
            const tTop = tRect.top - parentRect.top;
            const tRight = tLeft + tRect.width;
            const tBottom = tTop + tRect.height;
            const gap = 4;

            switch (pos) {
                case 'bottom':
                    top = tBottom + gap;
                    left = tLeft + (tRect.width / 2) - (pRect.width / 2);
                    break;
                case 'top':
                    top = tTop - pRect.height - gap;
                    left = tLeft + (tRect.width / 2) - (pRect.width / 2);
                    break;
                case 'left':
                    top = tTop + (tRect.height / 2) - (pRect.height / 2);
                    left = tLeft - pRect.width - gap;
                    break;
                case 'right':
                    top = tTop + (tRect.height / 2) - (pRect.height / 2);
                    left = tRight + gap;
                    break;
                case 'center':
                    top = tTop + (tRect.height / 2) - (pRect.height / 2);
                    left = tLeft + (tRect.width / 2) - (pRect.width / 2);
                    break;
                default:
                    top = tBottom + gap;
                    left = tLeft + (tRect.width / 2) - (pRect.width / 2);
            }

            popover.style.top = top + 'px';
            popover.style.left = left + 'px';
        },

        show(triggerEl, popoverEl) {
            if (this.activePopover && this.activePopover !== popoverEl) {
                this.hide(this.activePopover);
            }
            this.activePopover = popoverEl;
            popoverEl.style.display = 'block';
            this.position(triggerEl, popoverEl);
        },

        hide(popoverEl) {
            if (!popoverEl) return;
            popoverEl.style.display = 'none';
            if (this.activePopover === popoverEl) {
                this.activePopover = null;
            }
        },

        toggle(triggerEl, popoverEl) {
            const isShown = popoverEl.style.display !== 'none';
            if (isShown) {
                this.hide(popoverEl);
            } else {
                this.show(triggerEl, popoverEl);
            }
        }
    },

    /**
     * Sortable functionality for drag-and-drop reordering of elements.
     * Uses mouse/pointer/touch events for full control over drag behavior (jQuery UI-like).
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
            helper: 'sortable-helper', // Class name for the helper (dragged element)
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
        let helper = null; // Helper element that follows cursor
        let placeholder = null; // Placeholder element during drag
        let connectedLists = []; // Array of connected sortable containers
        let orderChanged = false; // Flag to track if order has changed
        let items = []; // Array of current sortable items
        let observer = null; // MutationObserver instance for modern browsers
        let intervalId = null; // ID for setInterval fallback in older browsers
        let isDragging = false; // Flag to track active dragging state
        let startX = 0; // Initial mouse X position
        let startY = 0; // Initial mouse Y position
        let offsetX = 0; // Offset from mouse to element left
        let offsetY = 0; // Offset from mouse to element top
        let scrollIntervalId = null; // Auto-scroll interval ID
        let initialItemRect = null; // Stored bounding rect of dragItem before drag

        function getTargetContainer(c) {
            if (!c) return null;
            if (c.tagName && c.tagName.toLowerCase() === 'table') {
                return c.querySelector('tbody') || c;
            }
            return c;
        }

        function getAllContainers() {
            const list = [element];
            if (settings.connectWith) {
                const nodes = document.querySelectorAll(settings.connectWith);
                for (let i = 0; i < nodes.length; i++) {
                    if (nodes[i] !== element && list.indexOf(nodes[i]) === -1) {
                        list.push(nodes[i]);
                    }
                }
            }
            return list.map(getTargetContainer).filter(Boolean);
        }

        function getContainerItems(cnt) {
            const target = getTargetContainer(cnt);
            if (!target) return [];
            const result = [];
            const ch = target.children;
            for (let i = 0; i < ch.length; i++) {
                const child = ch[i];
                if (child === helper || child === placeholder || child === dragItem) continue;
                if (matchesSelector(child, settings.items) && !sedjs.hasClass(child, 'disabled')) {
                    result.push(child);
                }
            }
            return result;
        }

        /**
         * Initialize the sortable functionality.
         */
        function init() {
            updateConnectedLists();
            updateItems();
            bindEvents();
            setupDynamicCheck();
            if (settings.disabled) {
                disable();
            }
        }

        function updateConnectedLists() {
            if (settings.connectWith) {
                connectedLists = Array.prototype.slice.call(
                    document.querySelectorAll(settings.connectWith)
                ).filter(function(list) {
                    return list !== element;
                });
            } else {
                connectedLists = [];
            }
        }

        /**
         * Update the list of sortable items based on the current DOM state.
         * Only immediate children of the target container are considered items!
         */
        function updateItems() {
            const targetContainer = getTargetContainer(element);
            if (!targetContainer) return false;

            const children = targetContainer.children;
            const newItems = [];
            for (let i = 0; i < children.length; i++) {
                const child = children[i];
                if (child === helper || child === placeholder) continue;
                if (matchesSelector(child, settings.items) && !sedjs.hasClass(child, 'disabled')) {
                    newItems.push(child);
                }
            }

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
         * Bind mouse/pointer/touch events to current sortable items.
         */
        function bindEvents() {
            for (let i = 0; i < items.length; i++) {
                const item = items[i];
                const dragElement = settings.handle ?
                    (item.querySelector(settings.handle) || item) : item;

                if (!item._sortableBound) {
                    item._sortableBound = true;
                    item._sortableDragEl = dragElement;
                    dragElement.style.cursor = 'grab';

                    dragElement.addEventListener('mousedown', handlePointerDown, false);
                    dragElement.addEventListener('touchstart', handlePointerDown, false);
                }
            }
        }

        /**
         * Remove mouse/pointer/touch events from current sortable items.
         */
        function unbindEvents() {
            for (let i = 0; i < items.length; i++) {
                const item = items[i];
                if (item._sortableBound) {
                    const dragElement = item._sortableDragEl || item;
                    dragElement.style.cursor = '';
                    dragElement.removeEventListener('mousedown', handlePointerDown, false);
                    dragElement.removeEventListener('touchstart', handlePointerDown, false);
                    item._sortableBound = false;
                    item._sortableDragEl = null;
                }
            }
        }

        /**
         * Set up dynamic checking for DOM changes.
         */
        function setupDynamicCheck() {
            const targetContainer = getTargetContainer(element) || element;
            if (window.MutationObserver) {
                observer = new MutationObserver((mutations) => {
                    if (isDragging) return; // Do not disrupt during active drag!
                    for (let i = 0; i < mutations.length; i++) {
                        const mutation = mutations[i];
                        if (mutation.addedNodes.length || mutation.removedNodes.length) {
                            const itemsChanged = updateItems();
                            if (itemsChanged && !settings.disabled) {
                                unbindEvents();
                                bindEvents();
                            }
                            break;
                        }
                    }
                });
                observer.observe(targetContainer, {
                    childList: true,
                    subtree: false // Watch immediate children only!
                });
            } else {
                let lastChildCount = targetContainer.children.length;
                intervalId = setInterval(() => {
                    if (isDragging) return;
                    const currentChildCount = targetContainer.children.length;
                    if (currentChildCount !== lastChildCount) {
                        const itemsChanged = updateItems();
                        if (itemsChanged && !settings.disabled) {
                            unbindEvents();
                            bindEvents();
                        }
                        lastChildCount = currentChildCount;
                    }
                }, 500);
            }
        }

        /**
         * Get the sortable item from an event target.
         * Must be an immediate child of one of the valid containers.
         */
        function getItem(el) {
            const containers = getAllContainers();
            while (el && el !== document.body && el !== document.documentElement) {
                if (el.parentNode && containers.indexOf(el.parentNode) !== -1) {
                    if (matchesSelector(el, settings.items) && !sedjs.hasClass(el, 'disabled')) {
                        return el;
                    }
                }
                el = el.parentNode;
            }
            return null;
        }

        /**
         * Handle pointer down (mousedown/touchstart).
         * @param {Event} e - The pointer down event.
         */
        function handlePointerDown(e) {
            if (settings.disabled || isDragging) return;
            if (e.type === 'mousedown' && e.button !== 0) return;

            // If handle is defined, ensure click was inside the handle
            if (settings.handle) {
                let handleEl = e.target;
                let insideHandle = false;
                while (handleEl && handleEl !== document.body) {
                    if (matchesSelector(handleEl, settings.handle)) {
                        insideHandle = true;
                        break;
                    }
                    if (handleEl === element) break;
                    handleEl = handleEl.parentNode;
                }
                if (!insideHandle) return;
            }

            const target = getItem(e.target);
            if (!target) return;

            const touch = e.touches ? e.touches[0] : e;
            dragItem = target;
            startX = touch.clientX;
            startY = touch.clientY;

            const rect = dragItem.getBoundingClientRect();
            initialItemRect = {
                left: rect.left,
                top: rect.top,
                width: rect.width,
                height: rect.height
            };
            offsetX = startX - rect.left;
            offsetY = startY - rect.top;

            document.addEventListener('mousemove', handlePointerMove, false);
            document.addEventListener('touchmove', handlePointerMove, false);
            document.addEventListener('mouseup', handlePointerUp, false);
            document.addEventListener('touchend', handlePointerUp, false);

            if (e.type === 'mousedown') {
                e.preventDefault();
            }
        }

        /**
         * Handle pointer move (mousemove/touchmove).
         * @param {Event} e - The pointer move event.
         */
        function handlePointerMove(e) {
            if (!dragItem) return;

            const touch = e.touches ? e.touches[0] : e;
            const moveX = touch.clientX - startX;
            const moveY = touch.clientY - startY;
            const distance = Math.sqrt(moveX * moveX + moveY * moveY);

            if (!isDragging && distance > 4) {
                startDragging(e);
            }

            if (isDragging && helper) {
                updateHelperPosition(touch.clientX, touch.clientY);
                checkIntersection(touch.clientX, touch.clientY);
                handleAutoScroll(touch.clientY);

                if (settings.sort) {
                    settings.sort(e, { item: dragItem, helper: helper });
                }
                if (e.cancelable) {
                    e.preventDefault();
                }
            }
        }

        /**
         * Handle pointer up (mouseup/touchend).
         * @param {Event} e - The pointer up event.
         */
        function handlePointerUp(e) {
            document.removeEventListener('mousemove', handlePointerMove);
            document.removeEventListener('touchmove', handlePointerMove);
            document.removeEventListener('mouseup', handlePointerUp);
            document.removeEventListener('touchend', handlePointerUp);

            if (isDragging) {
                stopDragging(e);
            }

            dragItem = null;
            isDragging = false;
        }

        /**
         * Start the dragging operation.
         * @param {Event} e - The triggering event.
         */
        function startDragging(e) {
            isDragging = true;
            orderChanged = false;

            updateConnectedLists();
            cleanupExistingElements();
            createHelper();
            createPlaceholder();

            dragItem.style.display = 'none';

            if (settings.start) {
                settings.start(e, { item: dragItem, helper: helper });
            }
        }

        /**
         * Stop the dragging operation.
         * @param {Event} e - The triggering event.
         */
        function stopDragging(e) {
            if (scrollIntervalId) {
                clearInterval(scrollIntervalId);
                scrollIntervalId = null;
            }

            if (settings.beforeStop) {
                settings.beforeStop(e, { item: dragItem });
            }

            const placeholderParent = placeholder ? placeholder.parentNode : null;
            const originalContainer = getTargetContainer(element);

            if (placeholder && placeholderParent) {
                placeholderParent.insertBefore(dragItem, placeholder);
                placeholderParent.removeChild(placeholder);
            }

            dragItem.style.display = '';

            if (helper && helper.parentNode) {
                helper.parentNode.removeChild(helper);
            }

            const isReceived = placeholderParent && placeholderParent !== originalContainer;
            if (isReceived && settings.receive) {
                settings.receive(e, { item: dragItem, sender: element });
            }

            if (orderChanged && settings.update) {
                settings.update(e, { item: dragItem });
            }

            if (settings.stop) {
                settings.stop(e, { item: dragItem });
            }

            helper = null;
            placeholder = null;
            initialItemRect = null;
            orderChanged = false;
            isDragging = false;

            // Re-sync items and listeners
            updateItems();
            unbindEvents();
            bindEvents();
        }

        /**
         * Create the helper element (clone that follows cursor).
         */
        function createHelper() {
            helper = dragItem.cloneNode(true);
            helper.removeAttribute('id');
            sedjs.addClass(helper, settings.helper);

            const rect = initialItemRect || dragItem.getBoundingClientRect();
            helper.style.setProperty('position', 'fixed', 'important');
            helper.style.setProperty('z-index', '99999', 'important');
            helper.style.setProperty('left', (settings.axis === 'y' ? rect.left : (startX - offsetX)) + 'px', 'important');
            helper.style.setProperty('top', (settings.axis === 'x' ? rect.top : (startY - offsetY)) + 'px', 'important');
            helper.style.setProperty('width', rect.width + 'px', 'important');
            helper.style.setProperty('height', rect.height + 'px', 'important');
            helper.style.setProperty('box-sizing', 'border-box', 'important');
            helper.style.setProperty('pointer-events', 'none', 'important');
            helper.style.setProperty('margin', '0', 'important');
            helper.style.setProperty('opacity', '0.9', 'important');
            helper.style.setProperty('cursor', 'grabbing', 'important');
            helper.style.setProperty('transition', 'none', 'important');

            document.body.appendChild(helper);
        }

        /**
         * Create the placeholder element (shows where item will be dropped).
         */
        function createPlaceholder() {
            placeholder = document.createElement(dragItem.tagName);
            sedjs.addClass(placeholder, settings.placeholder);

            const rect = initialItemRect || dragItem.getBoundingClientRect();
            placeholder.style.setProperty('height', rect.height + 'px', 'important');
            placeholder.style.setProperty('min-height', rect.height + 'px', 'important');
            placeholder.style.setProperty('box-sizing', 'border-box', 'important');
            placeholder.style.setProperty('transition', 'none', 'important');

            dragItem.parentNode.insertBefore(placeholder, dragItem);
        }

        /**
         * Update helper position based on cursor.
         * @param {number} clientX - Cursor X position.
         * @param {number} clientY - Cursor Y position.
         */
        function updateHelperPosition(clientX, clientY) {
            if (!helper) return;

            let x = clientX - offsetX;
            let y = clientY - offsetY;

            if (settings.axis === 'x' && initialItemRect) {
                y = initialItemRect.top;
            } else if (settings.axis === 'y' && initialItemRect) {
                x = initialItemRect.left;
            }

            helper.style.setProperty('left', x + 'px', 'important');
            helper.style.setProperty('top', y + 'px', 'important');
        }

        /**
         * Check intersection with items and update placeholder position.
         * @param {number} clientX - Cursor X position.
         * @param {number} clientY - Cursor Y position.
         */
        function checkIntersection(clientX, clientY) {
            if (!placeholder) return;

            const containers = getAllContainers();
            let matchedContainer = null;

            // Find which container cursor is over or closest to
            for (let c = 0; c < containers.length; c++) {
                const cnt = containers[c];
                const cRect = cnt.getBoundingClientRect();
                if (clientX >= cRect.left - 20 && clientX <= cRect.right + 20 &&
                    clientY >= cRect.top - 20 && clientY <= cRect.bottom + 20) {
                    matchedContainer = cnt;
                    break;
                }
            }

            if (!matchedContainer) return;

            const containerItems = getContainerItems(matchedContainer);

            // If target container is empty (or has only our placeholder)
            if (containerItems.length === 0) {
                if (placeholder.parentNode !== matchedContainer) {
                    matchedContainer.appendChild(placeholder);
                    orderChanged = true;
                    if (settings.change) {
                        settings.change(null, { item: dragItem, placeholder: placeholder });
                    }
                }
                return;
            }

            // Find closest or intersecting item in container
            let targetItem = null;
            let minDistance = Infinity;

            for (let i = 0; i < containerItems.length; i++) {
                const item = containerItems[i];
                const rect = item.getBoundingClientRect();

                if (clientX >= rect.left && clientX <= rect.right &&
                    clientY >= rect.top && clientY <= rect.bottom) {
                    targetItem = item;
                    break;
                }

                const centerY = rect.top + rect.height / 2;
                const distance = Math.abs(clientY - centerY);
                if (distance < minDistance) {
                    minDistance = distance;
                    targetItem = item;
                }
            }

            if (targetItem) {
                movePlaceholder(targetItem, clientX, clientY);
            }
        }

        /**
         * Move placeholder to appropriate position relative to target.
         * @param {HTMLElement} target - The target item.
         * @param {number} clientX - Cursor X position.
         * @param {number} clientY - Cursor Y position.
         */
        function movePlaceholder(target, clientX, clientY) {
            if (!placeholder || !target) return;

            const rect = target.getBoundingClientRect();
            const isVertical = !settings.axis || settings.axis === 'y';

            const shouldInsertBefore = isVertical ?
                clientY < rect.top + rect.height / 2 :
                clientX < rect.left + rect.width / 2;

            const parent = target.parentNode;
            const newPosition = shouldInsertBefore ? target : target.nextSibling;

            if (placeholder.parentNode !== parent || placeholder.nextSibling !== newPosition) {
                parent.insertBefore(placeholder, newPosition);
                orderChanged = true;

                if (settings.change) {
                    settings.change(null, { item: dragItem, placeholder: placeholder });
                }
            }
        }

        /**
         * Handle auto-scrolling when dragging near viewport edges.
         * @param {number} clientY - Cursor Y position.
         */
        function handleAutoScroll(clientY) {
            const scrollSpeed = 10;
            const scrollZone = 50;

            if (scrollIntervalId) {
                clearInterval(scrollIntervalId);
                scrollIntervalId = null;
            }

            if (clientY < scrollZone) {
                scrollIntervalId = setInterval(() => {
                    window.scrollBy(0, -scrollSpeed);
                }, 50);
            } else if (clientY > window.innerHeight - scrollZone) {
                scrollIntervalId = setInterval(() => {
                    window.scrollBy(0, scrollSpeed);
                }, 50);
            }
        }

        /**
         * Clean up any existing helper/placeholder elements.
         */
        function cleanupExistingElements() {
            const existingHelpers = document.querySelectorAll(`.${settings.helper}`);
            for (let i = 0; i < existingHelpers.length; i++) {
                if (existingHelpers[i].parentNode) {
                    existingHelpers[i].parentNode.removeChild(existingHelpers[i]);
                }
            }

            const existingPlaceholders = document.querySelectorAll(`.${settings.placeholder}`);
            for (let i = 0; i < existingPlaceholders.length; i++) {
                if (existingPlaceholders[i].parentNode) {
                    existingPlaceholders[i].parentNode.removeChild(existingPlaceholders[i]);
                }
            }
        }

        /**
         * Check if element matches selector (polyfill).
         * @param {HTMLElement} el - The element to test.
         * @param {string} selector - The CSS selector to match.
         * @returns {boolean} Whether the element matches the selector.
         */
        function matchesSelector(el, selector) {
            if (!el || !el.parentNode) return false;
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
         * Destroy the sortable instance.
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
            if (scrollIntervalId) {
                clearInterval(scrollIntervalId);
                scrollIntervalId = null;
            }
            document.removeEventListener('mousemove', handlePointerMove);
            document.removeEventListener('touchmove', handlePointerMove);
            document.removeEventListener('mouseup', handlePointerUp);
            document.removeEventListener('touchend', handlePointerUp);
            if (helper && helper.parentNode) helper.parentNode.removeChild(helper);
            if (placeholder && placeholder.parentNode) placeholder.parentNode.removeChild(placeholder);
            if (dragItem) dragItem.style.display = '';
        }

        init();

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
    },

    /**
     * User panel toggle listener (vanilla JS delegation)
     */
    userpanel() {
        document.addEventListener('click', (e) => {
            const btn = e.target.closest('.openuserpanel');
            if (btn) {
                e.preventDefault();
                const panel = btn.closest('.userpanel');
                if (panel) {
                    this.toggleClass(panel, 'open');
                }
            }
        });
    },

    /**
     * Universal Custom Selectbox module (sedjs.select)
     */
    select: {
        instances: new WeakMap(),

        /**
         * Initialize custom selectbox on a single select element.
         * @param {HTMLSelectElement} select
         * @param {Object} [userOptions]
         * @returns {Object} API instance
         */
        init(select, userOptions = {}) {
            if (!select || select.tagName.toLowerCase() !== 'select') return null;
            if (this.instances.has(select)) return this.instances.get(select);

            const self = this;
            const data = select.dataset || {};
            const styleWidth = select.style.width;
            const options = sedjs.extend({
                placeholder: data.placeholder || self._getInitialPlaceholder(select) || '...',
                search: data.search !== undefined ? (data.search === 'true' || data.search === '') : false,
                searchPlaceholder: data.searchPlaceholder || '...',
                showValues: data.showValues === 'true',
                size: data.size || (sedjs.hasClass(select, 'sed-select-sm') ? 'sm' : ''),
                width: data.width || (styleWidth && styleWidth !== 'auto' ? styleWidth : (sedjs.hasClass(select, 'sed-select-block') ? '100%' : null)),
                searchThreshold: 8
            }, userOptions);

            const state = {
                select: select,
                options: options,
                isMultiple: select.multiple,
                isOpen: false,
                highlightedIndex: -1,
                flatItems: [],
                wrapper: null,
                toggle: null,
                label: null,
                arrow: null,
                dropdown: null,
                searchContainer: null,
                searchInput: null,
                actionsContainer: null,
                list: null,
                _onDocClick: null,
                _onNativeChange: null
            };

            if (!select.dataset.search && select.options.length >= options.searchThreshold) {
                options.search = true;
            }

            if (!options.width) {
                options.width = self._calculateAutoWidth(select, options);
            }

            self._buildUI(state);
            self._bindEvents(state);
            self._update(state);

            const api = {
                open: () => self._open(state),
                close: () => self._close(state),
                toggle: () => self._toggle(state),
                update: () => self._update(state),
                refresh: () => self._refresh(state),
                enable: () => self._enable(state),
                disable: () => self._disable(state),
                destroy: () => self._destroy(state)
            };

            select._sedSelect = api;
            this.instances.set(select, api);
            return api;
        },

        /**
         * Initialize all matching selects within a container.
         * @param {HTMLElement|Document|string} [container=document]
         * @param {string} [selector='select.sed-select, select[data-sed-select]']
         * @returns {Array}
         */
        initAll(container = document, selector = 'select.sed-select, select[data-sed-select]') {
            const root = typeof container === 'string' ? document.querySelector(container) : container;
            if (!root) return [];
            const elements = root.querySelectorAll(selector);
            const apis = [];
            elements.forEach(el => {
                const api = this.init(el);
                if (api) apis.push(api);
            });
            return apis;
        },

        /**
         * Get API instance from select element.
         * @param {HTMLSelectElement} select
         * @returns {Object|null}
         */
        get(select) {
            if (!select) return null;
            return this.instances.get(select) || select._sedSelect || null;
        },

        /**
         * Refresh select instance.
         * @param {HTMLSelectElement} select
         */
        refresh(select) {
            const api = this.get(select);
            if (api) api.refresh();
        },

        /**
         * Destroy select instance and restore native element.
         * @param {HTMLSelectElement} select
         */
        destroy(select) {
            const api = this.get(select);
            if (api) api.destroy();
        },

        _getInitialPlaceholder(select) {
            if (select.options.length > 0 && select.options[0].value === '') {
                return select.options[0].text;
            }
            return '';
        },

        _calculateAutoWidth(select, options) {
            // Check if element is currently rendered in DOM and has a measurable width
            if (select.offsetWidth > 0) {
                return Math.max(select.offsetWidth, 44) + 'px';
            }

            let longestText = '';
            Array.from(select.options).forEach(opt => {
                const txt = (opt.text || '').trim();
                if (txt.length > longestText.length) {
                    longestText = txt;
                }
            });

            if (!longestText && options.placeholder) {
                longestText = options.placeholder;
            }

            const span = document.createElement('span');
            span.style.visibility = 'hidden';
            span.style.position = 'absolute';
            span.style.whiteSpace = 'nowrap';
            span.style.fontSize = options.size === 'sm' ? '12px' : '13px';
            const compStyle = window.getComputedStyle ? getComputedStyle(select) : null;
            span.style.fontFamily = (compStyle && compStyle.fontFamily) ? compStyle.fontFamily : 'sans-serif';
            span.textContent = longestText;
            document.body.appendChild(span);
            const textWidth = Math.ceil(span.getBoundingClientRect().width);
            document.body.removeChild(span);

            const minWidth = longestText.length <= 2 ? 64 : (longestText.length <= 4 ? 78 : 98);
            return Math.max(minWidth, textWidth + 40) + 'px';
        },

        _buildUI(state) {
            const select = state.select;
            const options = state.options;

            select.style.position = 'absolute';
            select.style.opacity = '0';
            select.style.height = '0';
            select.style.width = '0';
            select.style.pointerEvents = 'none';
            select.setAttribute('tabindex', '-1');
            select.setAttribute('aria-hidden', 'true');

            const wrapper = document.createElement('div');
            let wrapperClass = 'sed-select-wrapper';
            if (state.isMultiple) wrapperClass += ' sed-multiselect';
            if (options.size === 'sm') wrapperClass += ' sed-select-sm';
            if (options.width === '100%') wrapperClass += ' sed-select-block';
            if (select.disabled) wrapperClass += ' disabled';

            wrapper.className = wrapperClass;
            if (options.width && options.width !== '100%') {
                wrapper.style.width = options.width;
            }

            const toggle = document.createElement('div');
            toggle.className = 'sed-select-toggle';
            toggle.setAttribute('tabindex', select.disabled ? '-1' : '0');
            toggle.setAttribute('role', 'combobox');
            toggle.setAttribute('aria-haspopup', 'listbox');
            toggle.setAttribute('aria-expanded', 'false');

            const label = document.createElement('span');
            label.className = 'sed-select-label';

            const arrow = document.createElement('span');
            arrow.className = 'sed-select-arrow';

            toggle.appendChild(label);
            toggle.appendChild(arrow);
            wrapper.appendChild(toggle);

            const dropdown = document.createElement('div');
            dropdown.className = 'sed-select-dropdown';
            dropdown.setAttribute('role', 'listbox');
            dropdown.addEventListener('click', (e) => e.stopPropagation());

            if (options.search) {
                const searchContainer = document.createElement('div');
                searchContainer.className = 'sed-select-search';
                const searchInput = document.createElement('input');
                searchInput.type = 'text';
                searchInput.placeholder = options.searchPlaceholder;
                searchInput.setAttribute('autocomplete', 'off');
                searchContainer.appendChild(searchInput);
                dropdown.appendChild(searchContainer);
                state.searchContainer = searchContainer;
                state.searchInput = searchInput;
            }

            if (state.isMultiple) {
                const actionsContainer = document.createElement('div');
                actionsContainer.className = 'sed-select-actions';

                const selectAllBtn = document.createElement('a');
                selectAllBtn.textContent = 'Select all';
                selectAllBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    Array.from(select.options).forEach(opt => {
                        if (!opt.disabled) opt.selected = true;
                    });
                    this._triggerChange(state);
                });

                const clearAllBtn = document.createElement('a');
                clearAllBtn.textContent = 'Clear';
                clearAllBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    Array.from(select.options).forEach(opt => opt.selected = false);
                    this._triggerChange(state);
                });

                actionsContainer.appendChild(selectAllBtn);
                actionsContainer.appendChild(clearAllBtn);
                dropdown.appendChild(actionsContainer);
                state.actionsContainer = actionsContainer;
            }

            const list = document.createElement('div');
            list.className = 'sed-select-list';
            dropdown.appendChild(list);

            wrapper.appendChild(dropdown);
            select.parentNode.insertBefore(wrapper, select.nextSibling);

            state.wrapper = wrapper;
            state.toggle = toggle;
            state.label = label;
            state.arrow = arrow;
            state.dropdown = dropdown;
            state.list = list;

            this._renderItems(state);
        },

        _renderItems(state, filter = '') {
            const list = state.list;
            const select = state.select;
            const options = state.options;
            list.innerHTML = '';
            const filterLower = filter.toLowerCase().trim();
            let hasMatches = false;
            state.flatItems = [];
            state.highlightedIndex = -1;

            const renderOption = (option, container) => {
                const text = option.text;
                const val = option.value;

                if (filterLower && !text.toLowerCase().includes(filterLower) && !val.toLowerCase().includes(filterLower)) {
                    return;
                }
                hasMatches = true;

                const item = document.createElement('div');
                let itemClass = 'sed-select-item';
                if (option.selected) itemClass += ' selected';
                if (option.disabled) itemClass += ' disabled';
                item.className = itemClass;
                item.setAttribute('role', 'option');
                item.setAttribute('aria-selected', option.selected ? 'true' : 'false');
                item._option = option;

                if (state.isMultiple) {
                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.checked = option.selected;
                    checkbox.disabled = option.disabled;
                    checkbox.tabIndex = -1;
                    checkbox.addEventListener('change', () => {
                        option.selected = checkbox.checked;
                        item.classList.toggle('selected', checkbox.checked);
                        item.setAttribute('aria-selected', checkbox.checked ? 'true' : 'false');
                        this._triggerChange(state);
                    });
                    item.appendChild(checkbox);
                }

                const textSpan = document.createElement('span');
                textSpan.textContent = text;
                item.appendChild(textSpan);

                if (options.showValues && val && val !== text) {
                    const badge = document.createElement('span');
                    badge.className = 'item-badge';
                    badge.textContent = val;
                    item.appendChild(badge);
                }

                if (!option.disabled) {
                    state.flatItems.push(item);

                    item.addEventListener('click', (e) => {
                        e.stopPropagation();
                        if (e.target.tagName.toLowerCase() === 'input') return;
                        if (state.isMultiple) {
                            const cb = item.querySelector('input[type="checkbox"]');
                            if (cb) {
                                cb.checked = !cb.checked;
                                cb.dispatchEvent(new Event('change'));
                            }
                        } else {
                            Array.from(select.options).forEach(opt => opt.selected = false);
                            option.selected = true;
                            select.value = option.value;
                            this._triggerChange(state);
                            this._close(state);
                            state.toggle.focus();
                        }
                    });

                    item.addEventListener('mouseenter', () => {
                        this._setHighlight(state, state.flatItems.indexOf(item));
                    });
                }

                container.appendChild(item);
            };

            Array.from(select.children).forEach(child => {
                if (child.tagName.toLowerCase() === 'optgroup') {
                    const groupContainer = document.createElement('div');
                    groupContainer.className = 'sed-select-group';

                    const groupLabel = document.createElement('div');
                    groupLabel.className = 'sed-select-group-label';
                    groupLabel.textContent = child.label;
                    groupContainer.appendChild(groupLabel);

                    let groupHasMatches = false;
                    Array.from(child.children).forEach(opt => {
                        const countBefore = groupContainer.children.length;
                        renderOption(opt, groupContainer);
                        if (groupContainer.children.length > countBefore) {
                            groupHasMatches = true;
                        }
                    });

                    if (groupHasMatches) {
                        list.appendChild(groupContainer);
                    }
                } else if (child.tagName.toLowerCase() === 'option') {
                    renderOption(child, list);
                }
            });

            if (!hasMatches) {
                const empty = document.createElement('div');
                empty.className = 'sed-select-empty';
                empty.textContent = '...';
                list.appendChild(empty);
            }
        },

        _update(state) {
            const select = state.select;
            const selectedOptions = Array.from(select.selectedOptions);

            if (selectedOptions.length === 0) {
                state.label.innerHTML = `<span class="sed-select-placeholder">${state.options.placeholder}</span>`;
            } else if (state.isMultiple) {
                const names = selectedOptions.map(opt => opt.text);
                state.label.textContent = names.join(', ');
            } else {
                state.label.textContent = selectedOptions[0].text;
            }

            state.wrapper.classList.toggle('disabled', select.disabled);
            state.toggle.setAttribute('tabindex', select.disabled ? '-1' : '0');

            this._syncItemStates(state);
        },

        _syncItemStates(state) {
            if (!state.flatItems || state.flatItems.length === 0) {
                this._renderItems(state, state.searchInput ? state.searchInput.value : '');
                return;
            }
            state.flatItems.forEach(item => {
                if (item._option) {
                    const isSel = item._option.selected;
                    item.classList.toggle('selected', isSel);
                    item.setAttribute('aria-selected', isSel ? 'true' : 'false');
                    if (state.isMultiple) {
                        const cb = item.querySelector('input[type="checkbox"]');
                        if (cb) cb.checked = isSel;
                    }
                }
            });
        },

        _triggerChange(state) {
            state.select.dispatchEvent(new Event('change', { bubbles: true }));
            this._update(state);
        },

        _checkPosition(state) {
            const rect = state.wrapper.getBoundingClientRect();
            const viewportHeight = window.innerHeight || document.documentElement.clientHeight;
            const spaceBelow = viewportHeight - rect.bottom;
            const spaceAbove = rect.top;
            const dropdownHeight = (state.dropdown && state.dropdown.offsetHeight > 50) ? state.dropdown.offsetHeight : 240;

            if (spaceBelow < dropdownHeight && spaceAbove > spaceBelow) {
                state.wrapper.classList.add('sed-select-dropup');
            } else {
                state.wrapper.classList.remove('sed-select-dropup');
            }
        },

        _open(state) {
            if (state.isOpen || state.select.disabled) return;

            document.querySelectorAll('.sed-select-wrapper.open').forEach(el => {
                const sel = el.previousElementSibling;
                if (sel && sel._sedSelect && sel !== state.select) {
                    sel._sedSelect.close();
                }
            });

            this._checkPosition(state);
            state.wrapper.classList.add('open');
            state.toggle.setAttribute('aria-expanded', 'true');
            state.isOpen = true;

            if (state.searchInput) {
                state.searchInput.value = '';
                this._renderItems(state);
                this._checkPosition(state);
                setTimeout(() => state.searchInput.focus(), 30);
            } else {
                const currentSelected = state.flatItems.find(item => item._option && item._option.selected);
                if (currentSelected) {
                    this._setHighlight(state, state.flatItems.indexOf(currentSelected));
                    this._scrollToHighlighted(state);
                }
                this._checkPosition(state);
            }
        },

        _close(state) {
            if (!state.isOpen) return;
            state.wrapper.classList.remove('open');
            state.wrapper.classList.remove('sed-select-dropup');
            state.toggle.setAttribute('aria-expanded', 'false');
            state.isOpen = false;
            state.highlightedIndex = -1;
            this._clearHighlight(state);
        },

        _toggle(state) {
            if (state.isOpen) {
                this._close(state);
            } else {
                this._open(state);
            }
        },

        _setHighlight(state, index) {
            this._clearHighlight(state);
            if (index >= 0 && index < state.flatItems.length) {
                state.highlightedIndex = index;
                state.flatItems[index].classList.add('highlighted');
            }
        },

        _clearHighlight(state) {
            state.flatItems.forEach(item => item.classList.remove('highlighted'));
        },

        _scrollToHighlighted(state) {
            if (state.highlightedIndex >= 0 && state.flatItems[state.highlightedIndex]) {
                state.flatItems[state.highlightedIndex].scrollIntoView({ block: 'nearest' });
            }
        },

        _navigateItems(state, direction) {
            if (!state.isOpen) {
                this._open(state);
                return;
            }
            if (state.flatItems.length === 0) return;

            let newIndex = state.highlightedIndex + direction;
            if (newIndex < 0) newIndex = 0;
            if (newIndex >= state.flatItems.length) newIndex = state.flatItems.length - 1;

            this._setHighlight(state, newIndex);
            this._scrollToHighlighted(state);
        },

        _selectHighlighted(state) {
            if (state.highlightedIndex >= 0 && state.flatItems[state.highlightedIndex]) {
                state.flatItems[state.highlightedIndex].click();
            }
        },

        _bindEvents(state) {
            state.toggle.addEventListener('click', (e) => {
                e.stopPropagation();
                this._toggle(state);
            });

            state.toggle.addEventListener('keydown', (e) => {
                if (state.select.disabled) return;

                switch (e.key) {
                    case 'Enter':
                    case ' ':
                    case 'ArrowDown':
                    case 'ArrowUp':
                        e.preventDefault();
                        if (!state.isOpen) {
                            this._open(state);
                        } else if (e.key === 'ArrowDown') {
                            this._navigateItems(state, 1);
                        } else if (e.key === 'ArrowUp') {
                            this._navigateItems(state, -1);
                        } else if (e.key === 'Enter' || e.key === ' ') {
                            this._selectHighlighted(state);
                        }
                        break;
                    case 'Escape':
                        if (state.isOpen) {
                            e.preventDefault();
                            this._close(state);
                        }
                        break;
                    case 'Tab':
                        if (state.isOpen) this._close(state);
                        break;
                }
            });

            if (state.searchInput) {
                state.searchInput.addEventListener('input', (e) => {
                    this._renderItems(state, e.target.value);
                    if (state.flatItems.length > 0) {
                        this._setHighlight(state, 0);
                    }
                });

                state.searchInput.addEventListener('keydown', (e) => {
                    switch (e.key) {
                        case 'ArrowDown':
                            e.preventDefault();
                            this._navigateItems(state, 1);
                            break;
                        case 'ArrowUp':
                            e.preventDefault();
                            this._navigateItems(state, -1);
                            break;
                        case 'Enter':
                            e.preventDefault();
                            this._selectHighlighted(state);
                            break;
                        case 'Escape':
                            e.preventDefault();
                            this._close(state);
                            state.toggle.focus();
                            break;
                    }
                });

                state.searchInput.addEventListener('click', (e) => e.stopPropagation());
            }

            state._onDocClick = (e) => {
                if (state.isOpen && !state.wrapper.contains(e.target)) {
                    this._close(state);
                }
            };
            document.addEventListener('click', state._onDocClick);

            state._onNativeChange = () => {
                this._update(state);
            };
            state.select.addEventListener('change', state._onNativeChange);
        },

        _enable(state) {
            state.select.disabled = false;
            this._update(state);
        },

        _disable(state) {
            state.select.disabled = true;
            this._close(state);
            this._update(state);
        },

        _refresh(state) {
            state.options.width = this._calculateAutoWidth(state.select, state.options);
            if (state.options.width && state.options.width !== '100%') {
                state.wrapper.style.width = state.options.width;
            }
            this._renderItems(state, state.searchInput ? state.searchInput.value : '');
            this._update(state);
        },

        _destroy(state) {
            this._close(state);
            document.removeEventListener('click', state._onDocClick);
            state.select.removeEventListener('change', state._onNativeChange);

            state.select.style.position = '';
            state.select.style.opacity = '';
            state.select.style.height = '';
            state.select.style.width = '';
            state.select.style.pointerEvents = '';
            state.select.removeAttribute('tabindex');
            state.select.removeAttribute('aria-hidden');

            if (state.wrapper && state.wrapper.parentNode) {
                state.wrapper.parentNode.removeChild(state.wrapper);
            }

            this.instances.delete(state.select);
            delete state.select._sedSelect;
        }
    },

    /**
     * Switch / Toggle helper (Auto-wraps checkboxes with .sed-switch slider & label)
     */
    switch: {
        init(input) {
            if (!input || input._sedSwitchInit || input.type !== 'checkbox') return;
            input._sedSwitchInit = true;

            const isSm = input.classList.contains('sed-switch-sm') || input.dataset.size === 'sm';
            const parent = input.parentElement;

            // Check if already in a complete .sed-switch label
            if (parent && parent.tagName.toLowerCase() === 'label' && parent.classList.contains('sed-switch')) {
                if (!parent.querySelector('.sed-switch-slider')) {
                    const slider = document.createElement('span');
                    slider.className = 'sed-switch-slider';
                    input.parentNode.insertBefore(slider, input.nextSibling);
                }
                this._syncState(input, parent);
                return;
            }

            // Find associated label text if any
            let labelText = '';
            let labelElement = null;

            if (input.id) {
                const doc = input.ownerDocument || document;
                const found = doc.querySelector(`label[for="${CSS.escape ? CSS.escape(input.id) : input.id}"]`);
                if (found && !found.contains(input)) {
                    labelElement = found;
                    labelText = found.innerHTML;
                }
            }

            // If inside a wrapper like <span class="checkbox-item">
            if (!labelElement && parent) {
                const siblingLabel = parent.querySelector(`label[for="${input.id || ''}"]`) || parent.querySelector('label');
                if (siblingLabel && siblingLabel !== input) {
                    labelElement = siblingLabel;
                    labelText = siblingLabel.innerHTML;
                }
            }

            const wrapper = document.createElement('label');
            wrapper.className = 'sed-switch' + (isSm ? ' sed-switch-sm' : '');
            if (input.disabled) wrapper.classList.add('disabled');

            const slider = document.createElement('span');
            slider.className = 'sed-switch-slider';

            // Insert wrapper into DOM
            if (parent && parent.classList.contains('checkbox-item')) {
                parent.parentNode.insertBefore(wrapper, parent);
                wrapper.appendChild(input);
                wrapper.appendChild(slider);
                if (labelText.trim()) {
                    const textSpan = document.createElement('span');
                    textSpan.className = 'sed-switch-label';
                    textSpan.innerHTML = labelText;
                    wrapper.appendChild(textSpan);
                }
                parent.parentNode.removeChild(parent);
            } else {
                input.parentNode.insertBefore(wrapper, input);
                wrapper.appendChild(input);
                wrapper.appendChild(slider);
                if (labelElement) {
                    if (labelText.trim()) {
                        const textSpan = document.createElement('span');
                        textSpan.className = 'sed-switch-label';
                        textSpan.innerHTML = labelText;
                        wrapper.appendChild(textSpan);
                    }
                    if (labelElement.parentNode) {
                        labelElement.parentNode.removeChild(labelElement);
                    }
                }
            }

            this._syncState(input, wrapper);
        },

        _syncState(input, wrapper) {
            input.setAttribute('role', 'switch');
            input.setAttribute('aria-checked', input.checked ? 'true' : 'false');
            wrapper.classList.toggle('disabled', input.disabled);

            input.addEventListener('change', () => {
                input.setAttribute('aria-checked', input.checked ? 'true' : 'false');
                wrapper.classList.toggle('disabled', input.disabled);
            });
        },

        initAll(container = document, selector = 'input[type="checkbox"].sed-switch, input[type="checkbox"][data-switch]') {
            const root = typeof container === 'string' ? document.querySelector(container) : container;
            if (!root) return;
            const checkboxes = root.querySelectorAll(selector);
            checkboxes.forEach(cb => this.init(cb));
        }
    },

    /**
     * Input helpers (Password show/hide toggle & clear button)
     */
    input: {
        initPasswordToggle(container = document, selector = 'input.sed-password, input[type="password"]:not([data-toggle="false"]):not(.no-password-toggle)') {
            const root = typeof container === 'string' ? document.querySelector(container) : container;
            if (!root) return;
            const inputs = root.querySelectorAll(selector);
            inputs.forEach(input => {
                if (input._sedPasswordInit) return;
                input._sedPasswordInit = true;

                const wrapper = document.createElement('div');
                wrapper.className = 'sed-input-wrapper sed-input-has-action';
                if (input.classList.contains('sed-input-block') || input.style.width === '100%') {
                    wrapper.classList.add('sed-input-block');
                } else if (input.style.width) {
                    wrapper.style.width = input.style.width;
                }

                input.parentNode.insertBefore(wrapper, input);
                wrapper.appendChild(input);

                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'sed-input-action-btn sed-password-toggle-btn';
                btn.setAttribute('tabindex', '-1');
                btn.setAttribute('aria-label', 'Show / Hide Password');
                btn.title = 'Show / Hide Password';
                btn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';

                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    const isPass = input.type === 'password';
                    input.type = isPass ? 'text' : 'password';
                    btn.innerHTML = isPass
                        ? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>'
                        : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';
                });

                wrapper.appendChild(btn);
            });
        },

        initClearable(container = document, selector = 'input.sed-clearable, input[data-clear="true"], input[type="search"]') {
            const root = typeof container === 'string' ? document.querySelector(container) : container;
            if (!root) return;
            const inputs = root.querySelectorAll(selector);
            inputs.forEach(input => {
                if (input._sedClearInit) return;
                input._sedClearInit = true;

                const wrapper = document.createElement('div');
                wrapper.className = 'sed-input-wrapper sed-input-has-action';
                if (input.classList.contains('sed-input-block') || input.style.width === '100%') {
                    wrapper.classList.add('sed-input-block');
                } else if (input.style.width) {
                    wrapper.style.width = input.style.width;
                }

                input.parentNode.insertBefore(wrapper, input);
                wrapper.appendChild(input);

                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'sed-input-action-btn sed-clear-btn';
                btn.setAttribute('tabindex', '-1');
                btn.setAttribute('aria-label', 'Clear');
                btn.title = 'Clear';
                btn.style.display = input.value ? 'flex' : 'none';
                btn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>';

                const toggleClearBtn = () => {
                    btn.style.display = input.value ? 'flex' : 'none';
                };

                input.addEventListener('input', toggleClearBtn);
                input.addEventListener('change', toggleClearBtn);

                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    input.value = '';
                    btn.style.display = 'none';
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                    input.focus();
                });

                wrapper.appendChild(btn);
            });
        },

        initAll(container = document) {
            this.initPasswordToggle(container);
            this.initClearable(container);
        }
    },

    /**
     * Date & Time Picker module (sedjs.datepicker)
     */
    datepicker: {
        activePopup: null,
        _currentBinding: null,
        _docClickAttached: false,

        _createPopup() {
            let popup = document.querySelector('.sed-datepicker-dropdown');
            if (!popup) {
                popup = document.createElement('div');
                popup.className = 'sed-datepicker-dropdown';
                document.body.appendChild(popup);
            }
            return popup;
        },

        initGroup(group) {
            if (!group || group._sedDatepickerGroupInit) return;
            group._sedDatepickerGroupInit = true;

            const selYear = group.querySelector('select[name^="ryear"]');
            const selMonth = group.querySelector('select[name^="rmonth"]');
            const selDay = group.querySelector('select[name^="rday"]');
            const selHour = group.querySelector('select[name^="rhour"]');
            const selMin = group.querySelector('select[name^="rminute"]');

            if (!selYear || !selMonth || !selDay) return;

            group.classList.add('sed-date-group-enhanced');

            // Hide/clean any loose text nodes (spaces, colons) outside wrapper
            Array.from(group.childNodes).forEach(node => {
                if (node.nodeType === 3) node.nodeValue = '';
            });

            const isTime = !!(selHour && selMin);

            const wrapper = document.createElement('div');
            wrapper.className = 'sed-input-wrapper sed-input-has-action sed-datepicker-wrapper' + (isTime ? ' has-time' : '');

            const displayInput = document.createElement('input');
            displayInput.type = 'text';
            displayInput.size = isTime ? 18 : 12;
            displayInput.className = 'sed-datepicker-input text' + (isTime ? ' has-time' : '');
            displayInput.readOnly = true;
            displayInput.placeholder = isTime ? 'DD.MM.YYYY HH:mm' : 'DD.MM.YYYY';

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'sed-input-action-btn sed-datepicker-btn';
            btn.setAttribute('tabindex', '-1');
            btn.title = 'Calendar';
            btn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>';

            wrapper.appendChild(displayInput);
            wrapper.appendChild(btn);
            group.appendChild(wrapper);

            const updateDisplay = () => {
                const y = parseInt(selYear.value, 10);
                const m = parseInt(selMonth.value, 10);
                const d = parseInt(selDay.value, 10);
                if (y > 0 && m > 0 && d > 0) {
                    const dd = String(d).padStart(2, '0');
                    const mm = String(m).padStart(2, '0');
                    if (isTime) {
                        const hh = String(parseInt(selHour ? selHour.value : 0, 10) || 0).padStart(2, '0');
                        const min = String(parseInt(selMin ? selMin.value : 0, 10) || 0).padStart(2, '0');
                        displayInput.value = `${dd}.${mm}.${y} ${hh}:${min}`;
                    } else {
                        displayInput.value = `${dd}.${mm}.${y}`;
                    }
                } else {
                    displayInput.value = '';
                }
            };

            updateDisplay();

            const onSelect = (dateObj) => {
                if (dateObj) {
                    selYear.value = dateObj.year;
                    selMonth.value = dateObj.month;
                    selDay.value = dateObj.day;
                    if (isTime && selHour && selMin) {
                        selHour.value = dateObj.hours;
                        selMin.value = dateObj.minutes;
                    }
                } else {
                    selYear.value = '0';
                    selMonth.value = '0';
                    selDay.value = '0';
                    if (selHour) selHour.value = '0';
                    if (selMin) selMin.value = '0';
                }
                [selYear, selMonth, selDay, selHour, selMin].forEach(sel => {
                    if (sel) {
                        sel.dispatchEvent(new Event('change', { bubbles: true }));
                        if (sel._sedSelect) sel._sedSelect.update();
                    }
                });
                updateDisplay();
            };

            this._bindPicker(displayInput, btn, {
                isTime: isTime,
                getInitialDate: () => {
                    const y = parseInt(selYear.value, 10);
                    const m = parseInt(selMonth.value, 10);
                    const d = parseInt(selDay.value, 10);
                    const hh = selHour ? parseInt(selHour.value, 10) : 0;
                    const min = selMin ? parseInt(selMin.value, 10) : 0;
                    if (y > 0 && m > 0 && d > 0) {
                        return new Date(y, m - 1, d, hh, min);
                    }
                    return new Date();
                },
                onSelect: onSelect
            });
        },

        init(input, userOptions = {}) {
            if (!input || input._sedDatepickerInit) return;
            input._sedDatepickerInit = true;

            const isTime = input.dataset.time === 'true' || userOptions.time;
            let wrapper = input.closest('.sed-datepicker-wrapper');
            let btn = null;

            if (!wrapper) {
                wrapper = document.createElement('div');
                wrapper.className = 'sed-input-wrapper sed-input-has-action sed-datepicker-wrapper' + (isTime ? ' has-time' : '');
                if (input.classList.contains('sed-input-block') || input.style.width === '100%') {
                    wrapper.classList.add('sed-input-block');
                }
                if (!input.hasAttribute('size') && !input.style.width) {
                    input.size = isTime ? 18 : 12;
                }
                input.parentNode.insertBefore(wrapper, input);
                wrapper.appendChild(input);

                btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'sed-input-action-btn sed-datepicker-btn';
                btn.setAttribute('tabindex', '-1');
                btn.title = 'Calendar';
                btn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>';
                wrapper.appendChild(btn);
            } else {
                btn = wrapper.querySelector('.sed-datepicker-btn');
            }

            const parseDate = (val) => {
                if (!val) return null;
                if (/^\d{2}\.\d{2}\.\d{4}/.test(val)) {
                    const parts = val.split(/[\s\.]+/);
                    const d = parseInt(parts[0], 10);
                    const m = parseInt(parts[1], 10) - 1;
                    const y = parseInt(parts[2], 10);
                    let hh = 0, min = 0;
                    if (parts[3] && parts[3].includes(':')) {
                        const tp = parts[3].split(':');
                        hh = parseInt(tp[0], 10) || 0;
                        min = parseInt(tp[1], 10) || 0;
                    }
                    return new Date(y, m, d, hh, min);
                }
                const parsed = new Date(val.replace(' ', 'T'));
                return isNaN(parsed) ? null : parsed;
            };

            this._bindPicker(input, btn, {
                isTime: isTime,
                getInitialDate: () => parseDate(input.value) || new Date(),
                onSelect: (dateObj) => {
                    if (dateObj) {
                        const dd = String(dateObj.day).padStart(2, '0');
                        const mm = String(dateObj.month).padStart(2, '0');
                        const y = dateObj.year;
                        if (isTime) {
                            const hh = String(dateObj.hours).padStart(2, '0');
                            const min = String(dateObj.minutes).padStart(2, '0');
                            input.value = `${y}-${mm}-${dd} ${hh}:${min}`;
                        } else {
                            input.value = `${y}-${mm}-${dd}`;
                        }
                    } else {
                        input.value = '';
                    }
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });
        },

        _bindPicker(inputEl, triggerBtn, options) {
            const self = this;
            const popup = this._createPopup();
            const isTime = options.isTime;

            const L = window.L || {};

            const monthNames = (Array.isArray(L.months) && L.months.length === 12)
                ? L.months
                : ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

            const dayNames = (Array.isArray(L.days) && L.days.length === 7)
                ? L.days
                : ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'];

            const todayText = L.today || 'Today';
            const clearText = L.clear || 'Clear';
            const timeLabel = L.time || 'Time:';

            let viewYear = new Date().getFullYear();
            let viewMonth = new Date().getMonth();
            let selectedDate = null;
            let timeHours = 0;
            let timeMinutes = 0;

            function render() {
                popup.innerHTML = '';

                // Header
                const header = document.createElement('div');
                header.className = 'sed-dp-header';

                const prevBtn = document.createElement('button');
                prevBtn.type = 'button';
                prevBtn.className = 'sed-dp-nav-btn';
                prevBtn.setAttribute('tabindex', '-1');
                prevBtn.innerHTML = '&lsaquo;';
                prevBtn.onclick = (e) => {
                    e.stopPropagation();
                    viewMonth--;
                    if (viewMonth < 0) { viewMonth = 11; viewYear--; }
                    render();
                };

                const title = document.createElement('span');
                title.className = 'sed-dp-title';
                title.textContent = `${monthNames[viewMonth]} ${viewYear}`;

                const nextBtn = document.createElement('button');
                nextBtn.type = 'button';
                nextBtn.className = 'sed-dp-nav-btn';
                nextBtn.setAttribute('tabindex', '-1');
                nextBtn.innerHTML = '&rsaquo;';
                nextBtn.onclick = (e) => {
                    e.stopPropagation();
                    viewMonth++;
                    if (viewMonth > 11) { viewMonth = 0; viewYear++; }
                    render();
                };

                header.appendChild(prevBtn);
                header.appendChild(title);
                header.appendChild(nextBtn);
                popup.appendChild(header);

                // Weekday labels (Flexbox)
                const weekdays = document.createElement('div');
                weekdays.className = 'sed-dp-weekdays';
                dayNames.forEach(name => {
                    const wd = document.createElement('div');
                    wd.className = 'sed-dp-weekday';
                    wd.textContent = name;
                    weekdays.appendChild(wd);
                });
                popup.appendChild(weekdays);

                // Calendar days grid (Flexbox wrap)
                const daysGrid = document.createElement('div');
                daysGrid.className = 'sed-dp-days';

                const firstDay = (new Date(viewYear, viewMonth, 1).getDay() + 6) % 7;
                const daysInMonth = new Date(viewYear, viewMonth + 1, 0).getDate();
                const daysInPrevMonth = new Date(viewYear, viewMonth, 0).getDate();

                for (let i = firstDay - 1; i >= 0; i--) {
                    const d = document.createElement('div');
                    d.className = 'sed-dp-day other-month';
                    d.textContent = daysInPrevMonth - i;
                    daysGrid.appendChild(d);
                }

                const today = new Date();
                for (let day = 1; day <= daysInMonth; day++) {
                    const d = document.createElement('div');
                    d.className = 'sed-dp-day';
                    d.textContent = day;

                    if (today.getFullYear() === viewYear && today.getMonth() === viewMonth && today.getDate() === day) {
                        d.classList.add('today');
                    }

                    if (selectedDate && selectedDate.getFullYear() === viewYear && selectedDate.getMonth() === viewMonth && selectedDate.getDate() === day) {
                        d.classList.add('selected');
                    }

                    d.onclick = (e) => {
                        e.stopPropagation();
                        selectedDate = new Date(viewYear, viewMonth, day, timeHours, timeMinutes);
                        applySelected();
                        if (!isTime) {
                            close();
                        } else {
                            render();
                        }
                    };

                    daysGrid.appendChild(d);
                }
                popup.appendChild(daysGrid);

                // Time selector
                if (isTime) {
                    const timeBox = document.createElement('div');
                    timeBox.className = 'sed-dp-time-box';

                    const lbl = document.createElement('span');
                    lbl.textContent = timeLabel;
                    timeBox.appendChild(lbl);

                    const hInp = document.createElement('input');
                    hInp.type = 'number';
                    hInp.min = 0; hInp.max = 23;
                    hInp.value = String(timeHours).padStart(2, '0');
                    hInp.onchange = () => {
                        timeHours = parseInt(hInp.value, 10) || 0;
                        if (selectedDate) selectedDate.setHours(timeHours);
                        applySelected();
                    };

                    const colon = document.createElement('span');
                    colon.textContent = ':';

                    const mInp = document.createElement('input');
                    mInp.type = 'number';
                    mInp.min = 0; mInp.max = 59;
                    mInp.value = String(timeMinutes).padStart(2, '0');
                    mInp.onchange = () => {
                        timeMinutes = parseInt(mInp.value, 10) || 0;
                        if (selectedDate) selectedDate.setMinutes(timeMinutes);
                        applySelected();
                    };

                    timeBox.appendChild(hInp);
                    timeBox.appendChild(colon);
                    timeBox.appendChild(mInp);
                    popup.appendChild(timeBox);
                }

                // Footer
                const footer = document.createElement('div');
                footer.className = 'sed-dp-footer';

                const todayLink = document.createElement('a');
                todayLink.textContent = todayText;
                todayLink.onclick = (e) => {
                    e.stopPropagation();
                    const now = new Date();
                    viewYear = now.getFullYear();
                    viewMonth = now.getMonth();
                    selectedDate = now;
                    timeHours = now.getHours();
                    timeMinutes = now.getMinutes();
                    applySelected();
                    close();
                };

                const clearLink = document.createElement('a');
                clearLink.textContent = clearText;
                clearLink.onclick = (e) => {
                    e.stopPropagation();
                    selectedDate = null;
                    options.onSelect(null);
                    close();
                };

                footer.appendChild(todayLink);
                footer.appendChild(clearLink);
                popup.appendChild(footer);
            }

            function applySelected() {
                if (!selectedDate) return;
                options.onSelect({
                    year: selectedDate.getFullYear(),
                    month: selectedDate.getMonth() + 1,
                    day: selectedDate.getDate(),
                    hours: timeHours,
                    minutes: timeMinutes
                });
            }

            function open(e) {
                if (e) e.stopPropagation();
                if (self.activePopup && self.activePopup !== popup) {
                    self.activePopup.classList.remove('open');
                }

                const initDate = options.getInitialDate();
                if (initDate) {
                    viewYear = initDate.getFullYear();
                    viewMonth = initDate.getMonth();
                    selectedDate = initDate;
                    timeHours = initDate.getHours();
                    timeMinutes = initDate.getMinutes();
                }

                const rect = inputEl.getBoundingClientRect();
                const scrollY = window.pageYOffset || document.documentElement.scrollTop;
                const scrollX = window.pageXOffset || document.documentElement.scrollLeft;

                popup.style.top = (scrollY + rect.bottom + 4) + 'px';
                popup.style.left = (scrollX + rect.left) + 'px';

                render();
                popup.classList.add('open');
                self.activePopup = popup;
                self._currentBinding = { inputEl: inputEl, triggerBtn: triggerBtn, close: close };
            }

            function close() {
                popup.classList.remove('open');
                if (self.activePopup === popup) {
                    self.activePopup = null;
                    self._currentBinding = null;
                }
            }

            inputEl.addEventListener('click', open);
            if (triggerBtn) triggerBtn.addEventListener('click', open);

            if (!self._docClickAttached) {
                self._docClickAttached = true;
                document.addEventListener('click', (e) => {
                    if (self.activePopup && self._currentBinding) {
                        const { inputEl, triggerBtn, close } = self._currentBinding;
                        if (!self.activePopup.contains(e.target) && e.target !== inputEl && (!triggerBtn || !triggerBtn.contains(e.target))) {
                            close();
                        }
                    }
                });
            }
        },

        initAll(container = document) {
            const root = typeof container === 'string' ? document.querySelector(container) : container;
            if (!root) return;

            // 1. Enhanced date select groups from sed_selectbox_date
            root.querySelectorAll('.sed-date-group[data-datepicker="true"]').forEach(grp => this.initGroup(grp));

            // 2. Standalone inputs
            root.querySelectorAll('input.sed-datepicker, input[data-datepicker]').forEach(inp => this.init(inp));
        }
    },

    /**
     * Tags / Chips Input module (sedjs.tags)
     */
    tags: {
        init(input, userOptions = {}) {
            if (!input || input._sedTagsInit) return;
            input._sedTagsInit = true;

            input.style.position = 'absolute';
            input.style.opacity = '0';
            input.style.height = '0';
            input.style.width = '0';
            input.style.pointerEvents = 'none';

            const wrapper = document.createElement('div');
            wrapper.className = 'sed-tags-wrapper';
            if (input.disabled) wrapper.classList.add('disabled');

            const textInput = document.createElement('input');
            textInput.type = 'text';
            textInput.className = 'sed-tags-input';
            textInput.placeholder = input.placeholder || 'Add tag...';
            textInput.disabled = input.disabled;

            let tagsList = input.value ? input.value.split(',').map(s => s.trim()).filter(Boolean) : [];

            function render() {
                wrapper.innerHTML = '';
                tagsList.forEach((tag, idx) => {
                    const chip = document.createElement('span');
                    chip.className = 'sed-tag-chip';
                    chip.textContent = tag;

                    if (!input.disabled) {
                        const removeBtn = document.createElement('span');
                        removeBtn.className = 'sed-tag-remove';
                        removeBtn.innerHTML = '&times;';
                        removeBtn.onclick = (e) => {
                            e.stopPropagation();
                            tagsList.splice(idx, 1);
                            sync();
                            render();
                        };
                        chip.appendChild(removeBtn);
                    }
                    wrapper.appendChild(chip);
                });
                wrapper.appendChild(textInput);
            }

            function sync() {
                input.value = tagsList.join(', ');
                input.dispatchEvent(new Event('change', { bubbles: true }));
            }

            function addTag(val) {
                const clean = val.trim();
                if (clean && !tagsList.includes(clean)) {
                    tagsList.push(clean);
                    sync();
                    textInput.value = '';
                    render();
                }
            }

            textInput.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ',') {
                    e.preventDefault();
                    addTag(textInput.value);
                } else if (e.key === 'Backspace' && !textInput.value && tagsList.length > 0) {
                    tagsList.pop();
                    sync();
                    render();
                }
            });

            textInput.addEventListener('blur', () => {
                if (textInput.value) addTag(textInput.value);
                wrapper.classList.remove('focus');
            });

            textInput.addEventListener('focus', () => {
                wrapper.classList.add('focus');
            });

            wrapper.addEventListener('click', () => {
                textInput.focus();
            });

            input.parentNode.insertBefore(wrapper, input.nextSibling);
            render();
        },

        initAll(container = document, selector = 'input.sed-tags, input[data-tags]') {
            const root = typeof container === 'string' ? document.querySelector(container) : container;
            if (!root) return;
            root.querySelectorAll(selector).forEach(inp => this.init(inp));
        }
    },

    /**
     * Number Stepper module (sedjs.stepper)
     */
    stepper: {
        init(input) {
            if (!input || input._sedStepperInit) return;
            input._sedStepperInit = true;

            const stepper = document.createElement('div');
            stepper.className = 'sed-stepper';
            if (input.disabled) stepper.classList.add('disabled');

            const btnMinus = document.createElement('button');
            btnMinus.type = 'button';
            btnMinus.className = 'sed-step-btn';
            btnMinus.textContent = '−';

            const btnPlus = document.createElement('button');
            btnPlus.type = 'button';
            btnPlus.className = 'sed-step-btn';
            btnPlus.textContent = '+';

            const step = parseFloat(input.step) || 1;
            const min = input.min !== '' ? parseFloat(input.min) : -Infinity;
            const max = input.max !== '' ? parseFloat(input.max) : Infinity;

            btnMinus.onclick = () => {
                if (input.disabled) return;
                let cur = parseFloat(input.value) || 0;
                let next = Math.max(min, cur - step);
                input.value = next;
                input.dispatchEvent(new Event('input', { bubbles: true }));
                input.dispatchEvent(new Event('change', { bubbles: true }));
            };

            btnPlus.onclick = () => {
                if (input.disabled) return;
                let cur = parseFloat(input.value) || 0;
                let next = Math.min(max, cur + step);
                input.value = next;
                input.dispatchEvent(new Event('input', { bubbles: true }));
                input.dispatchEvent(new Event('change', { bubbles: true }));
            };

            input.parentNode.insertBefore(stepper, input);
            stepper.appendChild(btnMinus);
            stepper.appendChild(input);
            stepper.appendChild(btnPlus);
        },

        initAll(container = document, selector = 'input[type="number"].sed-stepper, input[data-stepper]') {
            const root = typeof container === 'string' ? document.querySelector(container) : container;
            if (!root) return;
            root.querySelectorAll(selector).forEach(inp => this.init(inp));
        }
    },

    /**
     * File Drop / Custom File Input module (sedjs.file)
     */
    file: {
        init(input) {
            if (!input || input._sedFileInit) return;
            input._sedFileInit = true;

            const inlineWidth = input.style.width;
            const explicitWidth = input.dataset.width || (inlineWidth && inlineWidth !== '0px' && inlineWidth !== '0' ? inlineWidth : '');
            const isBlock = input.classList.contains('sed-file-block') || explicitWidth === '100%' || input.classList.contains('sed-input-block');

            input.style.position = 'absolute';
            input.style.opacity = '0';
            input.style.height = '0';
            input.style.width = '0';
            input.style.pointerEvents = 'none';

            const L = window.L || {};
            const browseText = L.file_browse || 'Browse';
            const changeText = L.file_change || 'Change';
            const choosePlaceholder = L.file_choose || 'Choose a file or drag & drop...';
            const filesChosenText = L.file_files_chosen || 'files chosen';

            const drop = document.createElement('div');
            drop.className = 'sed-file-drop';

            if (isBlock) {
                drop.classList.add('sed-file-block');
            } else if (explicitWidth) {
                drop.style.width = explicitWidth;
            }

            if (input.disabled) {
                drop.classList.add('disabled');
            }

            const info = document.createElement('div');
            info.className = 'sed-file-info';

            const btn = document.createElement('span');
            btn.className = 'sed-file-btn';
            btn.textContent = browseText;

            function updateUI() {
                if (input.files && input.files.length > 0) {
                    if (input.files.length === 1) {
                        const f = input.files[0];
                        const kb = (f.size / 1024).toFixed(1);
                        info.innerHTML = `<span class="sed-file-name" title="${f.name}">${f.name}</span> <span class="sed-file-size">(${kb} KB)</span>`;
                    } else {
                        let totalSize = 0;
                        for (let i = 0; i < input.files.length; i++) {
                            totalSize += input.files[i].size;
                        }
                        const totalKb = (totalSize / 1024).toFixed(1);
                        info.innerHTML = `<span class="sed-file-name">${input.files.length} ${filesChosenText}</span> <span class="sed-file-size">(${totalKb} KB)</span>`;
                    }
                    btn.textContent = changeText;
                } else {
                    info.innerHTML = `<span class="sed-file-placeholder">${input.placeholder || choosePlaceholder}</span>`;
                    btn.textContent = browseText;
                }
            }

            drop.appendChild(info);
            drop.appendChild(btn);
            input.parentNode.insertBefore(drop, input.nextSibling);

            drop.addEventListener('click', (e) => {
                if (input.disabled) return;
                input.click();
            });

            input.addEventListener('change', updateUI);

            // Drag and drop support
            drop.addEventListener('dragover', (e) => {
                e.preventDefault();
                if (!input.disabled) drop.classList.add('dragover');
            });
            drop.addEventListener('dragleave', () => drop.classList.remove('dragover'));
            drop.addEventListener('drop', (e) => {
                e.preventDefault();
                drop.classList.remove('dragover');
                if (input.disabled) return;
                if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files.length > 0) {
                    input.files = e.dataTransfer.files;
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });

            updateUI();
        },

        initAll(container = document, selector = 'input[type="file"].sed-file, input[data-file]') {
            const root = typeof container === 'string' ? document.querySelector(container) : container;
            if (!root) return;
            root.querySelectorAll(selector).forEach(inp => this.init(inp));
        }
    }
};

// Expose sedjs globally for other scripts (e.g. uploader.js) that expect window.sedjs
window.sedjs = sedjs;

// Initialize functions when DOM is ready
sedjs.ready(() => {
    sedjs.userpanel();
    sedjs.sedtabs();
    sedjs.autofiletitle();
    sedjs.spoiler();
    sedjs.accordion();
    sedjs.closealert();
    sedjs.popover.init(document);

    sedjs.select.initAll();
    sedjs.switch.initAll();
    sedjs.input.initAll();
    sedjs.datepicker.initAll();
    sedjs.tags.initAll();
    sedjs.stepper.initAll();
    sedjs.file.initAll();

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