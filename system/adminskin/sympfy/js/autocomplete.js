/**
 * @package sedAutocomplete
 * @author Tomas Kirda [original author]
 * @copyright Tomas Kirda 2017
 * @author Tishov Alexander [info@avego.org]
 * @copyright Tishov Alexander, Seditio Team 2025
 * @see https://github.com/devbridge/jQuery-Autocomplete MIT license (Original: Ajax Autocomplete for jQuery, version 1.4.11)
 * @since JavaScript ES5
 * @version 1.0
 */

(function() {
    "use strict";

    // Utility functions
    var utils = {
        /**
         * Escapes special characters in a string for use in a regular expression
         * @param {string} value - The string to escape
         * @returns {string} The escaped string
         */
        escapeRegExChars: function(value) {
            return value.replace(/[|\\{}()[\]^$+*?.]/g, "\\$&");
        },

        /**
         * Creates a DOM node with specified class and initial styles
         * @param {string} containerClass - The CSS class to apply to the node
         * @returns {HTMLElement} The created DOM element
         */
        createNode: function(containerClass) {
            var div = document.createElement('div');
            div.className = containerClass;
            div.style.position = 'absolute';
            div.style.display = 'none';
            return div;
        }
    };

    // Key codes
    var keys = {
        ESC: 27,
        TAB: 9,
        RETURN: 13,
        LEFT: 37,
        UP: 38,
        RIGHT: 39,
        DOWN: 40
    };

    // No-op function
    var noop = function() {};

    /**
     * sedAutocomplete constructor
     * @param {HTMLElement} element - The input element to attach autocomplete to
     * @param {Object} options - Configuration options for the autocomplete
     */
    function sedAutocomplete(element, options) {
        this.element = element;
        this.suggestions = [];
        this.badQueries = [];
        this.selectedIndex = -1;
        this.currentValue = element.value;
        this.timeoutId = null;
        this.cachedResponse = {};
        this.onChangeTimeout = null;
        this.onChange = null;
        this.isLocal = false;
        this.suggestionsContainer = null;
        this.noSuggestionsContainer = null;
        this.options = extend({}, sedAutocomplete.defaults, options);
        this.classes = {
            selected: 'autocomplete-selected',
            suggestion: 'autocomplete-suggestion'
        };
        this.hint = null;
        this.hintValue = '';
        this.selection = null;
        this.currentRequest = null;

        this.initialize();
        this.setOptions(options);
    }

    /**
     * Default configuration options for sedAutocomplete
     */
    sedAutocomplete.defaults = {
        ajaxSettings: {},
        autoSelectFirst: false,
        appendTo: document.body,
        serviceUrl: null,
        lookup: null,
        onSelect: null,
        onHint: null,
        width: 'auto',
        minChars: 1,
        maxHeight: 300,
        deferRequestBy: 0,
        params: {},
        formatResult: function(suggestion, currentValue) {
            if (!currentValue) return suggestion.value;
            var pattern = '(' + utils.escapeRegExChars(currentValue) + ')';
            return suggestion.value
                .replace(new RegExp(pattern, 'gi'), '<strong>$1</strong>')
                .replace(/&/g, '&')
                .replace(/</g, '<')
                .replace(/>/g, '>')
                .replace(/"/g, '"')
                .replace(/<(\/?strong)>/g, '<$1>');
        },
        formatGroup: function(suggestion, category) {
            return '<div class="autocomplete-group">' + category + '</div>';
        },
        delimiter: null,
        zIndex: 9999,
        type: 'GET',
        noCache: false,
        onSearchStart: noop,
        onSearchComplete: noop,
        onSearchError: noop,
        preserveInput: false,
        containerClass: 'autocomplete-suggestions',
        tabDisabled: false,
        dataType: 'text',
        triggerSelectOnValidInput: true,
        preventBadQueries: true,
        lookupFilter: function(suggestion, originalQuery, queryLowerCase) {
            return suggestion.value.toLowerCase().indexOf(queryLowerCase) !== -1;
        },
        paramName: 'query',
        transformResult: function(response) {
            return typeof response === 'string' ? JSON.parse(response) : response;
        },
        showNoSuggestionNotice: false,
        noSuggestionNotice: 'No results',
        orientation: 'bottom',
        forceFixPosition: false
    };

    sedAutocomplete.prototype = {
        /**
         * Initializes the autocomplete instance by setting up DOM elements and event listeners
         */
        initialize: function() {
            var that = this,
                suggestionSelector = '.' + that.classes.suggestion,
                selected = that.classes.selected,
                options = that.options;

            that.element.setAttribute('autocomplete', 'off');

            that.noSuggestionsContainer = document.createElement('div');
            that.noSuggestionsContainer.className = 'autocomplete-no-suggestion';
            that.noSuggestionsContainer.innerHTML = options.noSuggestionNotice;

            that.suggestionsContainer = utils.createNode(options.containerClass);
            options.appendTo.appendChild(that.suggestionsContainer);

            if (options.width !== 'auto') {
                that.suggestionsContainer.style.width = options.width + 'px';
            }

            addEvent(that.suggestionsContainer, 'mouseover', function(e) {
                var target = e.target.closest(suggestionSelector);
                if (target) that.activate(parseInt(target.getAttribute('data-index'), 10));
            });

            addEvent(that.suggestionsContainer, 'mouseout', function() {
                that.selectedIndex = -1;
                var selectedItems = that.suggestionsContainer.querySelectorAll('.' + selected);
                for (var i = 0; i < selectedItems.length; i++) {
                    removeClass(selectedItems[i], selected);
                }
            });

            addEvent(that.suggestionsContainer, 'click', function(e) {
                var target = e.target.closest(suggestionSelector);
                if (target) that.select(parseInt(target.getAttribute('data-index'), 10));
                clearTimeout(that.blurTimeoutId);
            });

            that.fixPositionCapture = function() {
                if (that.visible) that.fixPosition();
            };

            addEvent(window, 'resize', that.fixPositionCapture);

            addEvent(that.element, 'keydown', function(e) { that.onKeyPress(e); });
            addEvent(that.element, 'keyup', function(e) { that.onKeyUp(e); });
            addEvent(that.element, 'blur', function() { that.onBlur(); });
            addEvent(that.element, 'focus', function() { that.onFocus(); });
            addEvent(that.element, 'change', function(e) { that.onKeyUp(e); });
            addEvent(that.element, 'input', function(e) { that.onKeyUp(e); });
        },

        /**
         * Handles the focus event on the input element
         */
        onFocus: function() {
            var that = this;
            if (that.disabled) return;
            that.fixPosition();
            // Only trigger value change if input has enough characters
            if (that.element.value.length >= that.options.minChars) {
                that.onValueChange();
            } else {
                that.hide(); // Hide suggestions if query is empty
            }
        },

        /**
         * Handles the blur event on the input element
         */
        onBlur: function() {
            var that = this,
                options = that.options,
                value = that.element.value,
                query = that.getQuery(value);

            that.blurTimeoutId = setTimeout(function() {
                that.hide();
                if (that.selection && that.currentValue !== query) {
                    (options.onInvalidateSelection || noop).call(that.element);
                }
            }, 200);
        },

        /**
         * Aborts any ongoing AJAX request
         */
        abortAjax: function() {
            var that = this;
            if (that.currentRequest) {
                that.currentRequest.abort();
                that.currentRequest = null;
            }
        },

        /**
         * Updates configuration options after initialization
         * @param {Object} suppliedOptions - New options to merge with existing ones
         */
        setOptions: function(suppliedOptions) {
            var that = this,
                options = extend({}, that.options, suppliedOptions);

            that.isLocal = Array.isArray(options.lookup);
            if (that.isLocal) {
                options.lookup = that.verifySuggestionsFormat(options.lookup);
            }

            options.orientation = that.validateOrientation(options.orientation, 'bottom');

            setStyles(that.suggestionsContainer, {
                'maxHeight': options.maxHeight + 'px',
                'width': options.width + 'px',
                'zIndex': options.zIndex
            });

            that.options = options;
        },

        /**
         * Clears cached responses and bad queries
         */
        clearCache: function() {
            this.cachedResponse = {};
            this.badQueries = [];
        },

        /**
         * Clears all autocomplete data
         */
        clear: function() {
            this.clearCache();
            this.currentValue = '';
            this.suggestions = [];
        },

        /**
         * Disables the autocomplete functionality
         */
        disable: function() {
            var that = this;
            that.disabled = true;
            clearTimeout(that.onChangeTimeout);
            that.abortAjax();
        },

        /**
         * Enables the autocomplete functionality
         */
        enable: function() {
            this.disabled = false;
        },

        /**
         * Fixes the position of the suggestions container relative to the input element
         */
        fixPosition: function() {
            var that = this,
                container = that.suggestionsContainer,
                containerParent = container.parentNode;

            var orientation = that.options.orientation,
                containerHeight = container.offsetHeight,
                height = that.element.offsetHeight,
                offset = getOffset(that.element),
                styles = { top: offset.top, left: offset.left };

            if (orientation === 'auto') {
                var viewPortHeight = window.innerHeight,
                    scrollTop = window.scrollY,
                    topOverflow = -scrollTop + offset.top - containerHeight,
                    bottomOverflow = scrollTop + viewPortHeight - (offset.top + height + containerHeight);
                orientation = (Math.max(topOverflow, bottomOverflow) === topOverflow) ? 'top' : 'bottom';
            }

            styles.top = (orientation === 'top') ? offset.top - containerHeight : offset.top + height;

            if (containerParent !== document.body) {
                var parentOffset = getOffset(containerParent);
                styles.top -= parentOffset.top;
                styles.top += containerParent.scrollTop;
                styles.left -= parentOffset.left;
            }

            if (that.options.width === 'auto') {
                styles.width = that.element.offsetWidth + 'px';
            }

            setStyles(container, {
                position: 'absolute',
                top: styles.top + 'px',
                left: styles.left + 'px',
                width: styles.width || '',
                maxHeight: that.options.maxHeight + 'px',
                zIndex: that.options.zIndex,
                display: 'block'
            });
        },

        /**
         * Checks if the cursor is at the end of the input value
         * @returns {boolean} True if cursor is at the end, false otherwise
         */
        isCursorAtEnd: function() {
            var valLength = this.element.value.length,
                selectionStart = this.element.selectionStart;
            return typeof selectionStart === 'number' ? selectionStart === valLength : true;
        },

        /**
         * Handles key press events for navigation and selection
         * @param {Event} e - The key event object
         */
        onKeyPress: function(e) {
            var that = this;

            if (!that.disabled && !that.visible && e.which === keys.DOWN && that.currentValue) {
                that.suggest();
                return;
            }

            if (that.disabled || !that.visible) return;

            switch (e.which) {
                case keys.ESC:
                    that.element.value = that.currentValue;
                    that.hide();
                    break;
                case keys.RIGHT:
                    if (that.hint && that.options.onHint && that.isCursorAtEnd()) {
                        that.selectHint();
                        break;
                    }
                    return;
                case keys.TAB:
                    if (that.hint && that.options.onHint) {
                        that.selectHint();
                        return;
                    }
                    if (that.selectedIndex === -1) {
                        that.hide();
                        return;
                    }
                    that.select(that.selectedIndex);
                    if (that.options.tabDisabled === false) return;
                    break;
                case keys.RETURN:
                    if (that.selectedIndex === -1) {
                        that.hide();
                        return;
                    }
                    that.select(that.selectedIndex);
                    break;
                case keys.UP:
                    that.moveUp();
                    break;
                case keys.DOWN:
                    that.moveDown();
                    break;
                default:
                    return;
            }

            e.stopPropagation();
            e.preventDefault();
        },

        /**
         * Handles key up events to trigger suggestion updates
         * @param {Event} e - The key event object
         */
        onKeyUp: function(e) {
            var that = this;

            if (that.disabled) return;

            switch (e.which) {
                case keys.UP:
                case keys.DOWN:
                    return;
            }

            clearTimeout(that.onChangeTimeout);

            if (that.currentValue !== that.element.value) {
                that.findBestHint();
                if (that.options.deferRequestBy > 0) {
                    that.onChangeTimeout = setTimeout(function() {
                        that.onValueChange();
                    }, that.options.deferRequestBy);
                } else {
                    that.onValueChange();
                }
            }
        },

        /**
         * Handles changes in input value to fetch and display suggestions
         */
        onValueChange: function() {
            var that = this,
                options = that.options,
                value = that.element.value,
                query = that.getQuery(value);

            if (that.selection && that.currentValue !== query) {
                that.selection = null;
                (options.onInvalidateSelection || noop).call(that.element);
            }

            clearTimeout(that.onChangeTimeout);
            that.currentValue = value;
            that.selectedIndex = -1;

            // Clear suggestions and hide if query is empty
            if (query === '') {
                that.suggestions = [];
                that.hide();
                return;
            }

            if (options.triggerSelectOnValidInput && that.isExactMatch(query)) {
                that.select(0);
                return;
            }

            if (query.length < options.minChars) {
                that.hide();
            } else {
                that.getSuggestions(query);
            }
        },

        /**
         * Checks if the current query exactly matches a suggestion
         * @param {string} query - The current input query
         * @returns {boolean} True if there's an exact match, false otherwise
         */
        isExactMatch: function(query) {
            var suggestions = this.suggestions;
            return (suggestions.length === 1 && suggestions[0].value.toLowerCase() === query.toLowerCase());
        },

        /**
         * Extracts the query from the input value based on delimiter
         * @param {string} value - The full input value
         * @returns {string} The extracted query
         */
        getQuery: function(value) {
            var delimiter = this.options.delimiter,
                parts;

            if (!delimiter) return value;
            parts = value.split(delimiter);
            return parts[parts.length - 1].trim();
        },

        /**
         * Retrieves suggestions from local data source
         * @param {string} query - The search query
         * @returns {Object} Object containing filtered suggestions
         */
        getSuggestionsLocal: function(query) {
            var that = this,
                options = that.options,
                queryLowerCase = query.toLowerCase(),
                filter = options.lookupFilter,
                limit = parseInt(options.lookupLimit, 10),
                data = {
                    suggestions: options.lookup.filter(function(suggestion) {
                        return filter(suggestion, query, queryLowerCase);
                    })
                };

            if (limit && data.suggestions.length > limit) {
                data.suggestions = data.suggestions.slice(0, limit);
            }

            return data;
        },

        /**
         * Fetches suggestions based on the query from local or remote source
         * @param {string} q - The search query
         */
        getSuggestions: function(q) {
            var that = this,
                options = that.options,
                serviceUrl = options.serviceUrl,
                params = options.params,
                cacheKey,
                response;

            params[options.paramName] = q;

            if (options.onSearchStart.call(that.element, params) === false) return;

            if (options.ignoreParams) params = null;

            if (typeof options.lookup === 'function') {
                options.lookup(q, function(data) {
                    that.suggestions = data.suggestions;
                    that.suggest();
                    options.onSearchComplete.call(that.element, q, data.suggestions);
                });
                return;
            }

            if (that.isLocal) {
                response = that.getSuggestionsLocal(q);
                that.suggestions = response.suggestions;
                that.suggest();
                options.onSearchComplete.call(that.element, q, response.suggestions);
            } else {
                if (typeof serviceUrl === 'function') {
                    serviceUrl = serviceUrl.call(that.element, q);
                }

                var separator = serviceUrl.indexOf('?') === -1 ? '?' : '&';
                cacheKey = serviceUrl + (params ? separator + serializeParams(params) : '');
                response = that.cachedResponse[cacheKey];

                if (response && Array.isArray(response.suggestions)) {
                    that.suggestions = response.suggestions;
                    that.suggest();
                    options.onSearchComplete.call(that.element, q, response.suggestions);
                } else if (!that.isBadQuery(q)) {
                    that.abortAjax();

                    var xhr = new XMLHttpRequest();
                    that.currentRequest = xhr;

                    xhr.open(options.type, serviceUrl + (params ? separator + serializeParams(params) : ''), true);

                    if (options.dataType === 'json') {
                        xhr.setRequestHeader('Accept', 'application/json');
                    }

                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4) {
                            if (xhr.status === 200) {
                                var result = options.transformResult(
                                    options.dataType === 'json' ? JSON.parse(xhr.responseText) : xhr.responseText,
                                    q
                                );
                                that.processResponse(result, q, cacheKey);
                                options.onSearchComplete.call(that.element, q, result.suggestions);
                            } else {
                                options.onSearchError.call(that.element, q, xhr, xhr.status, xhr.statusText);
                            }
                            that.currentRequest = null;
                        }
                    };

                    xhr.send(params ? serializeParams(params) : null);
                } else {
                    options.onSearchComplete.call(that.element, q, []);
                }
            }
        },

        /**
         * Checks if the query is in the list of bad queries
         * @param {string} q - The query to check
         * @returns {boolean} True if the query is bad, false otherwise
         */
        isBadQuery: function(q) {
            if (!this.options.preventBadQueries) return false;
            return this.badQueries.some(function(badQuery) {
                return q.indexOf(badQuery) === 0;
            });
        },

        /**
         * Hides the suggestions container
         */
        hide: function() {
            var that = this,
                container = that.suggestionsContainer;

            if (typeof that.options.onHide === 'function' && that.visible) {
                that.options.onHide.call(that.element, container);
            }

            that.visible = false;
            that.selectedIndex = -1;
            clearTimeout(that.onChangeTimeout);
            container.style.display = 'none';
            that.onHint(null);
        },

        /**
         * Displays the suggestions in the container
         */
        suggest: function() {
            var that = this,
                options = that.options;

            if (!that.suggestions.length) {
                if (options.showNoSuggestionNotice) {
                    that.noSuggestions();
                } else {
                    that.hide();
                }
                return;
            }

            var groupBy = options.groupBy,
                formatResult = options.formatResult,
                value = that.getQuery(that.currentValue),
                className = that.classes.suggestion,
                classSelected = that.classes.selected,
                container = that.suggestionsContainer,
                noSuggestionsContainer = that.noSuggestionsContainer,
                beforeRender = options.beforeRender,
                html = '',
                category;

            if (options.triggerSelectOnValidInput && that.isExactMatch(value)) {
                that.select(0);
                return;
            }

            that.suggestions.forEach(function(suggestion, i) {
                if (groupBy) {
                    var currentCategory = suggestion.data[groupBy];
                    if (category !== currentCategory) {
                        category = currentCategory;
                        html += options.formatGroup(suggestion, category);
                    }
                }
                html += '<div class="' + className + '" data-index="' + i + '">' + formatResult(suggestion, value) + '</div>';
            });

            that.adjustContainerWidth();
            noSuggestionsContainer.parentNode && noSuggestionsContainer.parentNode.removeChild(noSuggestionsContainer);
            container.innerHTML = html;

            if (typeof beforeRender === 'function') {
                beforeRender.call(that.element, container, that.suggestions);
            }

            that.fixPosition();
            container.style.display = 'block';

            if (options.autoSelectFirst) {
                that.selectedIndex = 0;
                container.scrollTop = 0;
                addClass(container.querySelector('.' + className), classSelected);
            }

            that.visible = true;
            that.findBestHint();
        },

        /**
         * Displays a "no suggestions" notice when no results are available
         */
        noSuggestions: function() {
            var that = this,
                beforeRender = that.options.beforeRender,
                container = that.suggestionsContainer,
                noSuggestionsContainer = that.noSuggestionsContainer;

            that.adjustContainerWidth();
            noSuggestionsContainer.parentNode && noSuggestionsContainer.parentNode.removeChild(noSuggestionsContainer);
            container.innerHTML = '';
            container.appendChild(noSuggestionsContainer);

            if (typeof beforeRender === 'function') {
                beforeRender.call(that.element, container, that.suggestions);
            }

            that.fixPosition();
            container.style.display = 'block';
            that.visible = true;
        },

        /**
         * Adjusts the width of the suggestions container based on options
         */
        adjustContainerWidth: function() {
            var that = this,
                options = that.options;

            if (options.width === 'auto') {
                var width = that.element.offsetWidth;
                that.suggestionsContainer.style.width = (width > 0 ? width : 300) + 'px';
            } else if (options.width === 'flex') {
                that.suggestionsContainer.style.width = '';
            }
        },

        /**
         * Finds the best hint suggestion matching the current input value
         */
        findBestHint: function() {
            var that = this,
                value = that.element.value.toLowerCase(),
                bestMatch = null;

            if (!value) return;

            that.suggestions.some(function(suggestion) {
                var foundMatch = suggestion.value.toLowerCase().indexOf(value) === 0;
                if (foundMatch) bestMatch = suggestion;
                return foundMatch;
            });

            that.onHint(bestMatch);
        },

        /**
         * Updates the hint value based on the best matching suggestion
         * @param {Object|null} suggestion - The suggestion to use as a hint, or null to clear
         */
        onHint: function(suggestion) {
            var that = this,
                onHintCallback = that.options.onHint,
                hintValue = suggestion ? that.currentValue + suggestion.value.substr(that.currentValue.length) : '';

            if (that.hintValue !== hintValue) {
                that.hintValue = hintValue;
                that.hint = suggestion;
                if (typeof onHintCallback === 'function') {
                    onHintCallback.call(that.element, hintValue);
                }
            }
        },

        /**
         * Verifies and formats suggestions into the expected structure
         * @param {Array} suggestions - The raw suggestions data
         * @returns {Array} The formatted suggestions array
         */
        verifySuggestionsFormat: function(suggestions) {
            if (suggestions.length && typeof suggestions[0] === 'string') {
                return suggestions.map(function(value) {
                    return { value: value, data: null };
                });
            }
            return suggestions;
        },

        /**
         * Validates and normalizes the orientation option
         * @param {string} orientation - The desired orientation ('auto', 'top', 'bottom')
         * @param {string} fallback - The default orientation if invalid
         * @returns {string} The validated orientation
         */
        validateOrientation: function(orientation, fallback) {
            orientation = (orientation || '').trim().toLowerCase();
            return ['auto', 'bottom', 'top'].indexOf(orientation) === -1 ? fallback : orientation;
        },

        /**
         * Processes the response from an AJAX request or local lookup
         * @param {Object} result - The response data containing suggestions
         * @param {string} originalQuery - The query that triggered the request
         * @param {string} cacheKey - The cache key for storing the response
         */
        processResponse: function(result, originalQuery, cacheKey) {
            var that = this,
                options = that.options;

            result.suggestions = that.verifySuggestionsFormat(result.suggestions);

            if (!options.noCache) {
                that.cachedResponse[cacheKey] = result;
                if (options.preventBadQueries && !result.suggestions.length) {
                    that.badQueries.push(originalQuery);
                }
            }

            if (originalQuery !== that.getQuery(that.currentValue)) return;

            that.suggestions = result.suggestions;
            that.suggest();
        },

        /**
         * Activates (highlights) a suggestion by index
         * @param {number} index - The index of the suggestion to activate
         * @returns {HTMLElement|null} The activated suggestion element, or null if invalid
         */
        activate: function(index) {
            var that = this,
                selected = that.classes.selected,
                container = that.suggestionsContainer,
                children = container.querySelectorAll('.' + that.classes.suggestion);

            var activeItem = container.querySelector('.' + selected);
            if (activeItem) removeClass(activeItem, selected);

            that.selectedIndex = index;

            if (that.selectedIndex !== -1 && children.length > that.selectedIndex) {
                activeItem = children[that.selectedIndex];
                addClass(activeItem, selected);
                return activeItem;
            }

            return null;
        },

        /**
         * Selects the current hint suggestion
         */
        selectHint: function() {
            var that = this,
                i = that.suggestions.indexOf(that.hint);

            that.select(i);
        },

        /**
         * Selects a suggestion by index and triggers the selection process
         * @param {number} i - The index of the suggestion to select
         */
        select: function(i) {
            var that = this;
            that.hide();
            that.onSelect(i);
        },

        /**
         * Moves the selection up in the suggestions list
         */
        moveUp: function() {
            var that = this;

            if (that.selectedIndex === -1) return;

            if (that.selectedIndex === 0) {
                removeClass(that.suggestionsContainer.querySelector('.' + that.classes.suggestion), that.classes.selected);
                that.selectedIndex = -1;
                that.element.value = that.currentValue;
                that.findBestHint();
                return;
            }

            that.adjustScroll(that.selectedIndex - 1);
        },

        /**
         * Moves the selection down in the suggestions list
         */
        moveDown: function() {
            var that = this;

            if (that.selectedIndex === (that.suggestions.length - 1)) return;

            that.adjustScroll(that.selectedIndex + 1);
        },

        /**
         * Adjusts the scroll position of the suggestions container to show the selected item
         * @param {number} index - The index of the suggestion to scroll to
         */
        adjustScroll: function(index) {
            var that = this,
                activeItem = that.activate(index);

            if (!activeItem) return;

            var offsetTop = activeItem.offsetTop,
                upperBound = that.suggestionsContainer.scrollTop,
                lowerBound = upperBound + that.options.maxHeight - activeItem.offsetHeight;

            if (offsetTop < upperBound) {
                that.suggestionsContainer.scrollTop = offsetTop;
            } else if (offsetTop > lowerBound) {
                that.suggestionsContainer.scrollTop = offsetTop - that.options.maxHeight + activeItem.offsetHeight;
            }

            if (!that.options.preserveInput) {
                that.element.value = that.getValue(that.suggestions[index].value);
            }

            that.onHint(null);
        },

        /**
         * Handles the selection of a suggestion
         * @param {number} index - The index of the suggestion to select
         */
        onSelect: function(index) {
            var that = this,
                onSelectCallback = that.options.onSelect,
                suggestion = that.suggestions[index];

            if (!suggestion) {
                that.hide();
                return;
            }

            that.currentValue = that.getValue(suggestion.value);

            if (that.currentValue !== that.element.value && !that.options.preserveInput) {
                that.element.value = that.currentValue;
            }

            that.onHint(null);
            that.suggestions = [];
            that.selection = suggestion;

            if (typeof onSelectCallback === 'function') {
                onSelectCallback.call(that.element, suggestion);
            }
        },

        /**
         * Gets the final value to set in the input, considering delimiters
         * @param {string} value - The suggestion value
         * @returns {string} The formatted value
         */
        getValue: function(value) {
            var that = this,
                delimiter = that.options.delimiter,
                currentValue,
                parts;

            if (!delimiter) return value;

            currentValue = that.currentValue;
            parts = currentValue.split(delimiter);

            if (parts.length === 1) return value;

            return currentValue.substr(0, currentValue.length - parts[parts.length - 1].length) + value;
        },

        /**
         * Cleans up the autocomplete instance by removing event listeners and DOM elements
         */
        dispose: function() {
            var that = this;
            removeEvent(that.element, 'keydown', that.onKeyPress);
            removeEvent(that.element, 'keyup', that.onKeyUp);
            removeEvent(that.element, 'blur', that.onBlur);
            removeEvent(that.element, 'focus', that.onFocus);
            removeEvent(that.element, 'change', that.onKeyUp);
            removeEvent(that.element, 'input', that.onKeyUp);
            removeEvent(window, 'resize', that.fixPositionCapture);
            that.suggestionsContainer.parentNode.removeChild(that.suggestionsContainer);
            delete that.element.dataset.autocompleteInstance;
        }
    };

    // Utility functions

    /**
     * Merges multiple objects into a single object
     * @param {Object} target - The target object to extend
     * @param {...Object} sources - Source objects to merge from
     * @returns {Object} The extended object
     */
    function extend(target) {
        for (var i = 1; i < arguments.length; i++) {
            var source = arguments[i];
            for (var prop in source) {
                if (source.hasOwnProperty(prop)) {
                    target[prop] = source[prop];
                }
            }
        }
        return target;
    }

    /**
     * Adds an event listener to an element
     * @param {HTMLElement} element - The element to attach the event to
     * @param {string} event - The event type (e.g., 'click')
     * @param {Function} handler - The event handler function
     */
    function addEvent(element, event, handler) {
        if (element.addEventListener) {
            element.addEventListener(event, handler, false);
        } else {
            element.attachEvent('on' + event, handler);
        }
    }

    /**
     * Removes an event listener from an element
     * @param {HTMLElement} element - The element to remove the event from
     * @param {string} event - The event type (e.g., 'click')
     * @param {Function} handler - The event handler function to remove
     */
    function removeEvent(element, event, handler) {
        if (element.removeEventListener) {
            element.removeEventListener(event, handler, false);
        } else {
            element.detachEvent('on' + event, handler);
        }
    }

    /**
     * Applies styles to an element
     * @param {HTMLElement} element - The element to style
     * @param {Object} styles - CSS styles to apply (e.g., {top: '10px'})
     */
    function setStyles(element, styles) {
        for (var prop in styles) {
            if (styles.hasOwnProperty(prop)) {
                element.style[prop] = styles[prop];
            }
        }
    }

    /**
     * Gets the offset position of an element relative to the document
     * @param {HTMLElement} element - The element to measure
     * @returns {Object} Object with top and left coordinates
     */
    function getOffset(element) {
        var rect = element.getBoundingClientRect(),
            scrollTop = window.pageYOffset || document.documentElement.scrollTop,
            scrollLeft = window.pageXOffset || document.documentElement.scrollLeft;
        return {
            top: rect.top + scrollTop,
            left: rect.left + scrollLeft
        };
    }

    /**
     * Adds a class to an element if it doesn't already have it
     * @param {HTMLElement} element - The element to modify
     * @param {string} className - The class to add
     */
    function addClass(element, className) {
        if (!hasClass(element, className)) {
            element.className += ' ' + className;
        }
    }

    /**
     * Removes a class from an element if it exists
     * @param {HTMLElement} element - The element to modify
     * @param {string} className - The class to remove
     */
    function removeClass(element, className) {
        if (hasClass(element, className)) {
            var reg = new RegExp('(\\s|^)' + className + '(\\s|$)');
            element.className = element.className.replace(reg, ' ').trim();
        }
    }

    /**
     * Checks if an element has a specific class
     * @param {HTMLElement} element - The element to check
     * @param {string} className - The class to look for
     * @returns {boolean} True if the class exists, false otherwise
     */
    function hasClass(element, className) {
        return (' ' + element.className + ' ').indexOf(' ' + className + ' ') > -1;
    }

    /**
     * Serializes an object into a URL query string
     * @param {Object} params - The parameters to serialize
     * @returns {string} The serialized query string
     */
    function serializeParams(params) {
        var parts = [];
        for (var key in params) {
            if (params.hasOwnProperty(key)) {
                parts.push(encodeURIComponent(key) + '=' + encodeURIComponent(params[key]));
            }
        }
        return parts.join('&');
    }

    // Expose to global scope
    window.sedAutocomplete = sedAutocomplete;

    // Add to HTMLElement prototype with a different name
    HTMLElement.prototype.sedAutoComplete = function(options) {
        var instance = new sedAutocomplete(this, options);
        this.dataset.autocompleteInstance = instance;
        return instance;
    };
})();