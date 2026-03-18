/**
 * @package Ajax Autocomplete (Vanilla JS)
 * @author Tomas Kirda [original author]
 * @copyright Tomas Kirda 2017
 * @author Tishov Alexander [info@avego.org]
 * @copyright Tishov Alexander, Seditio Team 2026
 * @see https://github.com/devbridge/jQuery-Autocomplete MIT license (Original: Ajax Autocomplete for jQuery, version 1.5.0)
 * @since JavaScript ES6+
 * @version 2.0
 */

(function (global) {
    "use strict";

    const utils = {
        escapeRegExChars(value) {
            return value.replace(/[|\\{}()[\]^$+*?.]/g, "\\$&");
        },
        createNode(containerClass) {
            const div = document.createElement("div");
            div.className = containerClass;
            div.style.position = "absolute";
            div.style.display = "none";
            return div;
        },
    };

    const keys = { ESC: 27, TAB: 9, RETURN: 13, LEFT: 37, UP: 38, RIGHT: 39, DOWN: 40 };

    const noop = () => {};

    const instances = new WeakMap();

    const deepMerge = (target, ...sources) => {
        for (const source of sources) {
            if (!source) continue;
            for (const key of Object.keys(source)) {
                const val = source[key];
                if (
                    val &&
                    typeof val === "object" &&
                    !Array.isArray(val) &&
                    !(val instanceof HTMLElement) &&
                    typeof val !== "function"
                ) {
                    target[key] = deepMerge(
                        target[key] && typeof target[key] === "object" ? target[key] : {},
                        val
                    );
                } else {
                    target[key] = val;
                }
            }
        }
        return target;
    };

    const serializeParams = (params) => {
        if (!params || typeof params !== "object") return "";
        return Object.entries(params)
            .filter(([, val]) => val !== null && val !== undefined)
            .map(([key, val]) => `${encodeURIComponent(key)}=${encodeURIComponent(val)}`)
            .join("&");
    };

    const getOffset = (el) => {
        const rect = el.getBoundingClientRect();
        return {
            top: rect.top + (window.pageYOffset || document.documentElement.scrollTop),
            left: rect.left + (window.pageXOffset || document.documentElement.scrollLeft),
        };
    };

    const resolveElement = (value) => {
        if (!value || value === "body") return document.body;
        if (typeof value === "string") return document.querySelector(value) || document.body;
        return value;
    };

    const setContent = (el, content) => {
        el.innerHTML = "";
        if (typeof content === "string") {
            el.innerHTML = content;
        } else if (content instanceof HTMLElement) {
            el.appendChild(content);
        } else if (content != null) {
            el.textContent = String(content);
        }
    };

    // --- Default option functions ---

    const _lookupFilter = (suggestion, originalQuery, queryLowerCase) =>
        suggestion.value.toLowerCase().includes(queryLowerCase);

    const _transformResult = (response) =>
        typeof response === "string" ? JSON.parse(response) : response;

    const _formatResult = (suggestion, currentValue) => {
        if (!currentValue) {
            return suggestion.value;
        }

        const pattern = `(${utils.escapeRegExChars(currentValue)})`;

        return suggestion.value
            .replace(new RegExp(pattern, "gi"), "<strong>$1</strong>")
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/&lt;(\/?strong)&gt;/g, "<$1>");
    };

    const _formatGroup = (suggestion, category) =>
        `<div class="autocomplete-group">${category}</div>`;

    // --- Autocomplete class ---

    class Autocomplete {
        static utils = utils;

        static defaults = {
            ajaxSettings: {},
            autoSelectFirst: false,
            appendTo: "body",
            serviceUrl: null,
            lookup: null,
            onSelect: null,
            onHint: null,
            width: "auto",
            minChars: 1,
            maxHeight: 300,
            deferRequestBy: 0,
            params: {},
            formatResult: _formatResult,
            formatGroup: _formatGroup,
            delimiter: null,
            zIndex: 9999,
            type: "GET",
            noCache: false,
            onSearchStart: noop,
            onSearchComplete: noop,
            onSearchError: noop,
            preserveInput: false,
            containerClass: "autocomplete-suggestions",
            tabDisabled: false,
            dataType: "text",
            currentRequest: null,
            triggerSelectOnValidInput: true,
            preventBadQueries: true,
            lookupFilter: _lookupFilter,
            paramName: "query",
            transformResult: _transformResult,
            showNoSuggestionNotice: false,
            noSuggestionNotice: "No results",
            orientation: "bottom",
            forceFixPosition: false,
            ignoreParams: false,
            jsonpCallbackParam: "callback",
            jsonpTimeout: 10000,
        };

        static getInstance(el) {
            return instances.get(el) || null;
        }

        constructor(el, options) {
            this.element = el;
            this.suggestions = [];
            this.badQueries = [];
            this.selectedIndex = -1;
            this.currentValue = this.element.value;
            this.timeoutId = null;
            this.cachedResponse = {};
            this.onChangeTimeout = null;
            this.onChange = null;
            this.isLocal = false;
            this.suggestionsContainer = null;
            this.noSuggestionsContainer = null;
            this.options = deepMerge({}, Autocomplete.defaults, options);
            this.classes = {
                selected: "autocomplete-selected",
                suggestion: "autocomplete-suggestion",
            };
            this.hint = null;
            this.hintValue = "";
            this.selection = null;
            this.currentRequest = null;
            this._handlers = {};

            this.initialize();
            this.setOptions(options);

            instances.set(el, this);
        }

        initialize() {
            const suggestionSelector = `.${this.classes.suggestion}`;
            const { selected } = this.classes;
            const { options } = this;

            this.element.setAttribute("autocomplete", "off");

            this.noSuggestionsContainer = document.createElement("div");
            this.noSuggestionsContainer.className = "autocomplete-no-suggestion";
            setContent(this.noSuggestionsContainer, options.noSuggestionNotice);

            this.suggestionsContainer = Autocomplete.utils.createNode(options.containerClass);
            const container = this.suggestionsContainer;

            resolveElement(options.appendTo).appendChild(container);

            if (options.width !== "auto") {
                container.style.width =
                    typeof options.width === "number" ? `${options.width}px` : options.width;
            }

            this._onContainerMouseOver = (e) => {
                const target = e.target.closest(suggestionSelector);
                if (target) {
                    this.activate(parseInt(target.getAttribute("data-index"), 10));
                }
            };

            this._onContainerMouseOut = () => {
                this.selectedIndex = -1;
                const el = container.querySelector(`.${selected}`);
                if (el) el.classList.remove(selected);
            };

            this._onContainerClick = (e) => {
                clearTimeout(this.blurTimeoutId);
                const target = e.target.closest(suggestionSelector);
                if (target) {
                    this.select(parseInt(target.getAttribute("data-index"), 10));
                }
            };

            container.addEventListener("mouseover", this._onContainerMouseOver);
            container.addEventListener("mouseout", this._onContainerMouseOut);
            container.addEventListener("click", this._onContainerClick);

            this.fixPositionCapture = () => {
                if (this.visible) {
                    this.fixPosition();
                }
            };

            window.addEventListener("resize", this.fixPositionCapture);

            this._onKeyDown = (e) => this.onKeyPress(e);
            this._onKeyUp = (e) => this.onKeyUp(e);
            this._onBlur = () => this.onBlur();
            this._onFocus = () => this.onFocus();
            this._onInputChange = (e) => this.onKeyUp(e);

            this.element.addEventListener("keydown", this._onKeyDown);
            this.element.addEventListener("keyup", this._onKeyUp);
            this.element.addEventListener("blur", this._onBlur);
            this.element.addEventListener("focus", this._onFocus);
            this.element.addEventListener("change", this._onInputChange);
            this.element.addEventListener("input", this._onInputChange);
        }

        onFocus() {
            if (this.disabled) {
                return;
            }

            this.fixPosition();

            if (this.element.value.length >= this.options.minChars) {
                this.onValueChange();
            }
        }

        onBlur() {
            const { options } = this;
            const value = this.element.value;
            const query = this.getQuery(value);

            this.blurTimeoutId = setTimeout(() => {
                this.hide();

                if (this.selection && this.currentValue !== query) {
                    (options.onInvalidateSelection || noop).call(this.element);
                }
            }, 200);
        }

        abortAjax() {
            if (this.currentRequest) {
                this.currentRequest.abort();
                this.currentRequest = null;
            }
        }

        setOptions(suppliedOptions) {
            const options = Object.assign({}, this.options, suppliedOptions);

            this.isLocal = Array.isArray(options.lookup);

            if (this.isLocal) {
                options.lookup = this.verifySuggestionsFormat(options.lookup);
            }

            options.orientation = this.validateOrientation(options.orientation, "bottom");

            const container = this.suggestionsContainer;
            container.style.maxHeight = `${options.maxHeight}px`;
            container.style.zIndex = options.zIndex;

            if (typeof options.width === "number") {
                container.style.width = `${options.width}px`;
            }

            this.options = options;
        }

        clearCache() {
            this.cachedResponse = {};
            this.badQueries = [];
        }

        clear() {
            this.clearCache();
            this.currentValue = "";
            this.suggestions = [];
        }

        disable() {
            this.disabled = true;
            clearTimeout(this.onChangeTimeout);
            this.abortAjax();
        }

        enable() {
            this.disabled = false;
        }

        fixPosition() {
            const container = this.suggestionsContainer;
            const containerParent = container.parentNode;

            if (containerParent !== document.body && !this.options.forceFixPosition) {
                return;
            }

            let orientation = this.options.orientation;
            const containerHeight = container.offsetHeight;
            const height = this.element.offsetHeight;
            const offset = getOffset(this.element);
            const styles = { top: offset.top, left: offset.left };

            if (orientation === "auto") {
                const viewPortHeight = window.innerHeight;
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                const topOverflow = -scrollTop + offset.top - containerHeight;
                const bottomOverflow =
                    scrollTop + viewPortHeight - (offset.top + height + containerHeight);

                orientation =
                    Math.max(topOverflow, bottomOverflow) === topOverflow ? "top" : "bottom";
            }

            if (orientation === "top") {
                styles.top += -containerHeight;
            } else {
                styles.top += height;
            }

            if (containerParent !== document.body) {
                const originalOpacity = container.style.opacity;
                const originalDisplay = container.style.display;

                if (!this.visible) {
                    container.style.opacity = "0";
                    container.style.display = "block";
                }

                const offsetParent = container.offsetParent || document.body;
                const parentOffsetDiff = getOffset(offsetParent);
                styles.top -= parentOffsetDiff.top;
                styles.top += containerParent.scrollTop;
                styles.left -= parentOffsetDiff.left;

                if (!this.visible) {
                    container.style.opacity = originalOpacity;
                    container.style.display = originalDisplay;
                }
            }

            if (this.options.width === "auto") {
                styles.width = `${this.element.offsetWidth}px`;
            }

            container.style.top = `${styles.top}px`;
            container.style.left = `${styles.left}px`;
            if (styles.width) {
                container.style.width = styles.width;
            }
        }

        isCursorAtEnd() {
            const valLength = this.element.value.length;
            const selectionStart = this.element.selectionStart;

            if (typeof selectionStart === "number") {
                return selectionStart === valLength;
            }
            return true;
        }

        onKeyPress(e) {
            if (!this.disabled && !this.visible && e.which === keys.DOWN && this.currentValue) {
                this.suggest();
                return;
            }

            if (this.disabled || !this.visible) {
                return;
            }

            switch (e.which) {
                case keys.ESC:
                    this.element.value = this.currentValue;
                    this.hide();
                    break;
                case keys.RIGHT:
                    if (this.hint && this.options.onHint && this.isCursorAtEnd()) {
                        this.selectHint();
                        break;
                    }
                    return;
                case keys.TAB:
                    if (this.hint && this.options.onHint) {
                        this.selectHint();
                        return;
                    }
                    if (this.selectedIndex === -1) {
                        this.hide();
                        return;
                    }
                    this.select(this.selectedIndex);
                    if (this.options.tabDisabled === false) {
                        return;
                    }
                    break;
                case keys.RETURN:
                    if (this.selectedIndex === -1) {
                        this.hide();
                        return;
                    }
                    this.select(this.selectedIndex);
                    break;
                case keys.UP:
                    this.moveUp();
                    break;
                case keys.DOWN:
                    this.moveDown();
                    break;
                default:
                    return;
            }

            e.stopImmediatePropagation();
            e.preventDefault();
        }

        onKeyUp(e) {
            if (this.disabled) {
                return;
            }

            switch (e.which) {
                case keys.UP:
                case keys.DOWN:
                    return;
            }

            clearTimeout(this.onChangeTimeout);

            if (this.currentValue !== this.element.value) {
                this.findBestHint();
                if (this.options.deferRequestBy > 0) {
                    this.onChangeTimeout = setTimeout(() => {
                        this.onValueChange();
                    }, this.options.deferRequestBy);
                } else {
                    this.onValueChange();
                }
            }
        }

        onValueChange() {
            if (this.ignoreValueChange) {
                this.ignoreValueChange = false;
                return;
            }

            const { options } = this;
            const value = this.element.value;
            const query = this.getQuery(value);

            if (this.selection && this.currentValue !== query) {
                this.selection = null;
                (options.onInvalidateSelection || noop).call(this.element);
            }

            clearTimeout(this.onChangeTimeout);
            this.currentValue = value;
            this.selectedIndex = -1;

            if (options.triggerSelectOnValidInput && this.isExactMatch(query)) {
                this.select(0);
                return;
            }

            if (query.length < options.minChars) {
                this.hide();
            } else {
                this.getSuggestions(query);
            }
        }

        isExactMatch(query) {
            const { suggestions } = this;

            return (
                suggestions.length === 1 &&
                suggestions[0].value.toLowerCase() === query.toLowerCase()
            );
        }

        getQuery(value) {
            const { delimiter } = this.options;

            if (!delimiter) {
                return value;
            }
            const parts = value.split(delimiter);
            return parts[parts.length - 1].trim();
        }

        getSuggestionsLocal(query) {
            const { options } = this;
            const queryLowerCase = query.toLowerCase();
            const { lookupFilter: filter } = options;
            const limit = parseInt(options.lookupLimit, 10);

            const data = {
                suggestions: options.lookup.filter((suggestion) =>
                    filter(suggestion, query, queryLowerCase)
                ),
            };

            if (limit && data.suggestions.length > limit) {
                data.suggestions = data.suggestions.slice(0, limit);
            }

            return data;
        }

        getSuggestions(q) {
            let response;
            const { options } = this;
            let serviceUrl = options.serviceUrl;

            options.params[options.paramName] = q;

            if (options.onSearchStart.call(this.element, options.params) === false) {
                return;
            }

            const params = options.ignoreParams ? null : options.params;

            if (typeof options.lookup === "function") {
                options.lookup(q, (data) => {
                    this.suggestions = data.suggestions;
                    this.suggest();
                    options.onSearchComplete.call(this.element, q, data.suggestions);
                });
                return;
            }

            let cacheKey;
            if (this.isLocal) {
                response = this.getSuggestionsLocal(q);
            } else {
                if (typeof serviceUrl === "function") {
                    serviceUrl = serviceUrl.call(this.element, q);
                }
                cacheKey = `${serviceUrl}?${serializeParams(params || {})}`;
                response = this.cachedResponse[cacheKey];
            }

            if (response && Array.isArray(response.suggestions)) {
                this.suggestions = response.suggestions;
                this.suggest();
                options.onSearchComplete.call(this.element, q, response.suggestions);
            } else if (!this.isBadQuery(q)) {
                this.abortAjax();

                const isJsonp =
                    options.dataType && options.dataType.toLowerCase() === "jsonp";

                if (isJsonp) {
                    this._requestJsonp(serviceUrl, params, q, cacheKey);
                } else {
                    this._requestFetch(serviceUrl, params, q, cacheKey);
                }
            } else {
                options.onSearchComplete.call(this.element, q, []);
            }
        }

        _requestFetch(serviceUrl, params, q, cacheKey) {
            const { options } = this;
            const controller = new AbortController();
            const method = (options.type || "GET").toUpperCase();
            const serialized = serializeParams(params || {});
            let url = serviceUrl;
            const fetchOptions = {
                method,
                headers: {},
                signal: controller.signal,
            };

            if (options.ajaxSettings) {
                if (options.ajaxSettings.headers) {
                    Object.assign(fetchOptions.headers, options.ajaxSettings.headers);
                }
                if (options.ajaxSettings.credentials) {
                    fetchOptions.credentials = options.ajaxSettings.credentials;
                }
                if (options.ajaxSettings.mode) {
                    fetchOptions.mode = options.ajaxSettings.mode;
                }
                if (options.ajaxSettings.cache) {
                    fetchOptions.cache = options.ajaxSettings.cache;
                }
            }

            if (method === "GET") {
                if (serialized) {
                    url += `${url.includes("?") ? "&" : "?"}${serialized}`;
                }
            } else {
                fetchOptions.headers["Content-Type"] =
                    "application/x-www-form-urlencoded; charset=UTF-8";
                fetchOptions.body = serialized;
            }

            this.currentRequest = {
                abort() {
                    controller.abort();
                },
            };

            fetch(url, fetchOptions)
                .then((response) => {
                    if (!response.ok) {
                        const err = new Error(response.statusText);
                        err.response = response;
                        throw err;
                    }
                    const dataType = (options.dataType || "text").toLowerCase();
                    return dataType === "json" ? response.json() : response.text();
                })
                .then((data) => {
                    this.currentRequest = null;
                    const result = options.transformResult(data, q);
                    this.processResponse(result, q, cacheKey);
                    options.onSearchComplete.call(this.element, q, result.suggestions);
                })
                .catch((error) => {
                    this.currentRequest = null;
                    if (error.name === "AbortError") return;
                    options.onSearchError.call(
                        this.element,
                        q,
                        error.response || null,
                        error.message || "error",
                        error
                    );
                });
        }

        _requestJsonp(serviceUrl, params, q, cacheKey) {
            const { options } = this;
            const callbackParam = options.jsonpCallbackParam || "callback";
            const timeout = options.jsonpTimeout || 10000;
            const callbackName =
                `__ac_jsonp_${Date.now()}_${Math.floor(Math.random() * 100000)}`;
            const script = document.createElement("script");
            let completed = false;
            let timer = null;

            const serialized = serializeParams(params || {});
            let url = serviceUrl;
            if (serialized) {
                url += `${url.includes("?") ? "&" : "?"}${serialized}`;
            }
            url +=
                `${url.includes("?") ? "&" : "?"}${encodeURIComponent(callbackParam)}=${callbackName}`;

            const cleanup = () => {
                if (script.parentNode) {
                    script.parentNode.removeChild(script);
                }
                delete window[callbackName];
                if (timer) {
                    clearTimeout(timer);
                    timer = null;
                }
            };

            window[callbackName] = (data) => {
                if (completed) return;
                completed = true;
                cleanup();
                this.currentRequest = null;
                const result = options.transformResult(data, q);
                this.processResponse(result, q, cacheKey);
                options.onSearchComplete.call(this.element, q, result.suggestions);
            };

            script.onerror = () => {
                if (completed) return;
                completed = true;
                cleanup();
                this.currentRequest = null;
                options.onSearchError.call(
                    this.element,
                    q,
                    null,
                    "error",
                    "JSONP script load error"
                );
            };

            if (timeout > 0) {
                timer = setTimeout(() => {
                    if (completed) return;
                    completed = true;
                    cleanup();
                    this.currentRequest = null;
                    options.onSearchError.call(
                        this.element,
                        q,
                        null,
                        "timeout",
                        "JSONP request timed out"
                    );
                }, timeout);
            }

            this.currentRequest = {
                abort() {
                    if (!completed) {
                        completed = true;
                        cleanup();
                    }
                },
            };

            script.src = url;
            document.head.appendChild(script);
        }

        isBadQuery(q) {
            if (!this.options.preventBadQueries) {
                return false;
            }

            const { badQueries } = this;
            let i = badQueries.length;

            while (i--) {
                if (q.startsWith(badQueries[i])) {
                    return true;
                }
            }

            return false;
        }

        hide() {
            const container = this.suggestionsContainer;

            if (typeof this.options.onHide === "function" && this.visible) {
                this.options.onHide.call(this.element, container);
            }

            this.visible = false;
            this.selectedIndex = -1;
            clearTimeout(this.onChangeTimeout);
            container.style.display = "none";
            this.onHint(null);
        }

        suggest() {
            if (!this.suggestions.length) {
                if (this.options.showNoSuggestionNotice) {
                    this.noSuggestions();
                } else {
                    this.hide();
                }
                return;
            }

            const { options } = this;
            const { groupBy, formatResult, beforeRender } = options;
            const value = this.getQuery(this.currentValue);
            const className = this.classes.suggestion;
            const classSelected = this.classes.selected;
            const container = this.suggestionsContainer;
            const { noSuggestionsContainer } = this;
            let html = "";
            let category;
            const formatGroup = (suggestion) => {
                const currentCategory = suggestion.data[groupBy];

                if (category === currentCategory) {
                    return "";
                }

                category = currentCategory;

                return options.formatGroup(suggestion, category);
            };

            if (options.triggerSelectOnValidInput && this.isExactMatch(value)) {
                this.select(0);
                return;
            }

            this.suggestions.forEach((suggestion, i) => {
                if (groupBy) {
                    html += formatGroup(suggestion, value, i);
                }

                html += `<div class="${className}" data-index="${i}">${formatResult(suggestion, value, i)}</div>`;
            });

            this.adjustContainerWidth();

            if (noSuggestionsContainer.parentNode) {
                noSuggestionsContainer.parentNode.removeChild(noSuggestionsContainer);
            }
            container.innerHTML = html;

            if (typeof beforeRender === "function") {
                beforeRender.call(this.element, container, this.suggestions);
            }

            this.fixPosition();
            container.style.display = "block";

            if (options.autoSelectFirst) {
                this.selectedIndex = 0;
                container.scrollTop = 0;
                const firstItem = container.querySelector(`.${className}`);
                if (firstItem) {
                    firstItem.classList.add(classSelected);
                }
            }

            this.visible = true;
            this.findBestHint();
        }

        noSuggestions() {
            const { beforeRender } = this.options;
            const container = this.suggestionsContainer;
            const { noSuggestionsContainer } = this;

            this.adjustContainerWidth();

            if (noSuggestionsContainer.parentNode) {
                noSuggestionsContainer.parentNode.removeChild(noSuggestionsContainer);
            }

            container.innerHTML = "";
            container.appendChild(noSuggestionsContainer);

            if (typeof beforeRender === "function") {
                beforeRender.call(this.element, container, this.suggestions);
            }

            this.fixPosition();
            container.style.display = "block";
            this.visible = true;
        }

        adjustContainerWidth() {
            const { options } = this;
            const container = this.suggestionsContainer;

            if (options.width === "auto") {
                const width = this.element.offsetWidth;
                container.style.width = `${width > 0 ? width : 300}px`;
            } else if (options.width === "flex") {
                container.style.width = "";
            }
        }

        findBestHint() {
            const value = this.element.value.toLowerCase();
            let bestMatch = null;

            if (!value) {
                return;
            }

            this.suggestions.some((suggestion) => {
                const foundMatch = suggestion.value.toLowerCase().startsWith(value);
                if (foundMatch) {
                    bestMatch = suggestion;
                }
                return foundMatch;
            });

            this.onHint(bestMatch);
        }

        onHint(suggestion) {
            const onHintCallback = this.options.onHint;
            let hintValue = "";

            if (suggestion) {
                hintValue =
                    this.currentValue + suggestion.value.substring(this.currentValue.length);
            }
            if (this.hintValue !== hintValue) {
                this.hintValue = hintValue;
                this.hint = suggestion;
                if (typeof onHintCallback === "function") {
                    onHintCallback.call(this.element, hintValue);
                }
            }
        }

        verifySuggestionsFormat(suggestions) {
            if (suggestions.length && typeof suggestions[0] === "string") {
                return suggestions.map((value) => ({ value, data: null }));
            }

            return suggestions;
        }

        validateOrientation(orientation, fallback) {
            orientation = (orientation || "").trim().toLowerCase();

            if (!["auto", "bottom", "top"].includes(orientation)) {
                orientation = fallback;
            }

            return orientation;
        }

        processResponse(result, originalQuery, cacheKey) {
            const { options } = this;

            result.suggestions = this.verifySuggestionsFormat(result.suggestions);

            if (!options.noCache) {
                this.cachedResponse[cacheKey] = result;
                if (options.preventBadQueries && !result.suggestions.length) {
                    this.badQueries.push(originalQuery);
                }
            }

            if (originalQuery !== this.getQuery(this.currentValue)) {
                return;
            }

            this.suggestions = result.suggestions;
            this.suggest();
        }

        activate(index) {
            const { selected } = this.classes;
            const container = this.suggestionsContainer;
            const children = container.querySelectorAll(`.${this.classes.suggestion}`);

            const prev = container.querySelector(`.${selected}`);
            if (prev) prev.classList.remove(selected);

            this.selectedIndex = index;

            if (this.selectedIndex !== -1 && children.length > this.selectedIndex) {
                const activeItem = children[this.selectedIndex];
                activeItem.classList.add(selected);
                return activeItem;
            }

            return null;
        }

        selectHint() {
            const i = this.suggestions.indexOf(this.hint);
            this.select(i);
        }

        select(i) {
            this.hide();
            this.onSelect(i);
        }

        moveUp() {
            if (this.selectedIndex === -1) {
                return;
            }

            if (this.selectedIndex === 0) {
                const first = this.suggestionsContainer.querySelector(
                    `.${this.classes.suggestion}`
                );
                if (first) first.classList.remove(this.classes.selected);
                this.selectedIndex = -1;
                this.ignoreValueChange = false;
                this.element.value = this.currentValue;
                this.findBestHint();
                return;
            }

            this.adjustScroll(this.selectedIndex - 1);
        }

        moveDown() {
            if (this.selectedIndex === this.suggestions.length - 1) {
                return;
            }

            this.adjustScroll(this.selectedIndex + 1);
        }

        adjustScroll(index) {
            const activeItem = this.activate(index);

            if (!activeItem) {
                return;
            }

            const heightDelta = activeItem.offsetHeight;
            const offsetTop = activeItem.offsetTop;
            const upperBound = this.suggestionsContainer.scrollTop;
            const lowerBound = upperBound + this.options.maxHeight - heightDelta;

            if (offsetTop < upperBound) {
                this.suggestionsContainer.scrollTop = offsetTop;
            } else if (offsetTop > lowerBound) {
                this.suggestionsContainer.scrollTop =
                    offsetTop - this.options.maxHeight + heightDelta;
            }

            if (!this.options.preserveInput) {
                this.ignoreValueChange = true;
                this.element.value = this.getValue(this.suggestions[index].value);
            }

            this.onHint(null);
        }

        onSelect(index) {
            const onSelectCallback = this.options.onSelect;
            const suggestion = this.suggestions[index];

            this.currentValue = this.getValue(suggestion.value);

            if (this.currentValue !== this.element.value && !this.options.preserveInput) {
                this.element.value = this.currentValue;
            }

            this.onHint(null);
            this.suggestions = [];
            this.selection = suggestion;

            if (typeof onSelectCallback === "function") {
                onSelectCallback.call(this.element, suggestion);
            }
        }

        getValue(value) {
            const { delimiter } = this.options;

            if (!delimiter) {
                return value;
            }

            const { currentValue } = this;
            const parts = currentValue.split(delimiter);

            if (parts.length === 1) {
                return value;
            }

            return (
                currentValue.substring(
                    0,
                    currentValue.length - parts[parts.length - 1].length
                ) + value
            );
        }

        dispose() {
            this.element.removeEventListener("keydown", this._onKeyDown);
            this.element.removeEventListener("keyup", this._onKeyUp);
            this.element.removeEventListener("blur", this._onBlur);
            this.element.removeEventListener("focus", this._onFocus);
            this.element.removeEventListener("change", this._onInputChange);
            this.element.removeEventListener("input", this._onInputChange);

            window.removeEventListener("resize", this.fixPositionCapture);

            const container = this.suggestionsContainer;
            if (container) {
                container.removeEventListener("mouseover", this._onContainerMouseOver);
                container.removeEventListener("mouseout", this._onContainerMouseOut);
                container.removeEventListener("click", this._onContainerClick);
                if (container.parentNode) {
                    container.parentNode.removeChild(container);
                }
            }

            instances.delete(this.element);
        }
    }

    // UMD export
    if (typeof define === "function" && define.amd) {
        define([], () => Autocomplete);
    } else if (typeof module === "object" && module.exports) {
        module.exports = Autocomplete;
    } else {
        global.Autocomplete = Autocomplete;
    }
})(typeof window !== "undefined" ? window : this);
