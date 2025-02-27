/**
 * @package SedAdminJS
 * @author Tishov Alexander [info@avego.org]
 * @copyright Tishov Alexander, Seditio Team 2025
 * @since JavaScript ES5
 * @version 1.0
 */

var sedadminjs = (function() {
    // Utility Functions

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
     * Toggles a class on an element
     * @param {HTMLElement} element - The element to modify
     * @param {string} className - The class to toggle
     */
    function toggleClass(element, className) {
        if (hasClass(element, className)) {
            removeClass(element, className);
        } else {
            addClass(element, className);
        }
    }

    /**
     * Applies CSS styles to an element
     * @param {HTMLElement} element - The element to style
     * @param {Object} styles - CSS properties to apply
     */
    function setStyles(element, styles) {
        for (var prop in styles) {
            if (styles.hasOwnProperty(prop)) {
                element.style[prop] = styles[prop];
            }
        }
    }

    /**
     * Creates a styled DOM element
     * @param {string} tag - The HTML tag name
     * @param {string} className - The class to apply
     * @param {Object} styles - CSS styles to apply
     * @returns {HTMLElement} The created element
     */
    function createStyledElement(tag, className, styles) {
        var el = document.createElement(tag);
        el.className = className;
        setStyles(el, styles);
        return el;
    }

    /**
     * Adds an event listener to an element
     * @param {HTMLElement} element - The element to attach the event to
     * @param {string} event - The event type
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
     * @param {string} event - The event type
     * @param {Function} handler - The event handler to remove
     */
    function removeEvent(element, event, handler) {
        if (element.removeEventListener) {
            element.removeEventListener(event, handler, false);
        } else {
            element.detachEvent('on' + event, handler);
        }
    }

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
     * Checks if an element is a descendant of another
     * @param {HTMLElement} parent - The parent element
     * @param {HTMLElement} child - The child element to check
     * @returns {boolean} True if child is a descendant, false otherwise
     */
    function isDescendant(parent, child) {
        if (!parent) return false;
        var node = child;
        while (node !== null) {
            if (node === parent) return true;
            node = node.parentNode;
        }
        return false;
    }

    /**
     * Gets all sibling elements of a given element
     * @param {HTMLElement} element - The reference element
     * @returns {Array} Array of sibling elements
     */
    function getSiblings(element) {
        var siblings = [];
        var sibling = element.parentNode.firstChild;
        while (sibling) {
            if (sibling.nodeType === 1 && sibling !== element) {
                siblings.push(sibling);
            }
            sibling = sibling.nextSibling;
        }
        return siblings;
    }

    /**
     * Toggles an element's visibility with slide animation
     * @param {HTMLElement} element - The element to toggle
     * @param {number} duration - Animation duration in milliseconds
     */
    function slideToggle(element, duration) {
        if (element.style.display === 'none' || !element.style.display) {
            slideDown(element, duration);
        } else {
            slideUp(element, duration);
        }
    }

    /**
     * Slides down an element with animation
     * @param {HTMLElement} element - The element to slide down
     * @param {number} duration - Animation duration in milliseconds
     */
    function slideDown(element, duration) {
        element.style.display = 'block';
        var height = element.offsetHeight;
        element.style.height = '0px';
        element.style.overflow = 'hidden';
        animate(element, { height: height + 'px' }, duration, function() {
            element.style.height = '';
            element.style.overflow = '';
        });
    }

    /**
     * Slides up an element with animation
     * @param {HTMLElement} element - The element to slide up
     * @param {number} duration - Animation duration in milliseconds
     */
    function slideUp(element, duration) {
        var height = element.offsetHeight;
        element.style.height = height + 'px';
        element.style.overflow = 'hidden';
        animate(element, { height: '0px' }, duration, function() {
            element.style.display = 'none';
            element.style.height = '';
            element.style.overflow = '';
        });
    }

    /**
     * Animates element properties
     * @param {HTMLElement} element - The element to animate
     * @param {Object} properties - CSS properties to animate
     * @param {number} duration - Animation duration in milliseconds
     * @param {Function} [callback] - Optional callback after animation
     */
    function animate(element, properties, duration, callback) {
        var start = null;
        var initialValues = {};
        var targetValues = {};

        for (var prop in properties) {
            initialValues[prop] = parseFloat(getComputedStyle(element)[prop]) || 0;
            targetValues[prop] = parseFloat(properties[prop]);
        }

        function step(timestamp) {
            if (!start) start = timestamp;
            var progress = Math.min((timestamp - start) / duration, 1);

            for (var prop in properties) {
                var value = initialValues[prop] + (targetValues[prop] - initialValues[prop]) * progress;
                element.style[prop] = value + 'px';
            }

            if (progress < 1) {
                requestAnimationFrame(step);
            } else if (callback) {
                callback();
            }
        }

        requestAnimationFrame(step);
    }

    // sedSidebar Component

    /**
     * sedSidebar constructor
     * @param {string|HTMLElement} holder - Selector or element for sidebar container
     * @param {string|HTMLElement} eventElement - Selector or element for toggle button
     * @param {string} side - Sidebar position ('right' or 'left')
     * @param {string} width - Sidebar width
     * @param {number} speed - Animation speed in milliseconds
     */
    function sedSidebar(holder, eventElement, side, width, speed) {
        this.holder = typeof holder === 'string' ? document.querySelector(holder) : holder;
        this.eventElement = typeof eventElement === 'string' ? document.querySelector(eventElement) : eventElement;
        this.side = side;
        this.width = width;
        this.speed = speed;
        if (this.holder) this.init();
    }

    sedSidebar.prototype = {
        /**
         * Initializes the sidebar
         */
        init: function() {
            if (this.side === 'right') {
                addClass(this.holder, 'right');
            }
            if (this.width) {
                this.holder.style.maxWidth = this.width;
            }
            if (this.speed) {
                this.holder.style.transitionDuration = (this.speed / 1000) + 's';
            }
            this.clickEvent();
        },

        /**
         * Sets up click events for sidebar toggle
         */
        clickEvent: function() {
            var self = this;
            var closeBtn = this.holder.querySelector('a.sidebar-close');

            addEvent(document, 'click', function(e) {
                var target = e.target;
                if (target === self.eventElement) {
                    addClass(self.holder, 'active');
                    addClass(self.eventElement, 'nav-is-visible');
                    addClass(document.documentElement, 'disable-scrolling');
                    e.preventDefault();
                    return false;
                }
                var isOutsideHolder = !isDescendant(self.holder, target);
                var isCloseBtnClick = closeBtn && isDescendant(closeBtn, target);
                if ((isOutsideHolder || isCloseBtnClick) && hasClass(self.holder, 'active')) {
                    removeClass(self.holder, 'active');
                    removeClass(self.eventElement, 'nav-is-visible');
                    removeClass(document.documentElement, 'disable-scrolling');
                    e.preventDefault();
                    return false;
                }
            });
        }
    };

    // SedScroll Component

    /**
     * SedScroll constructor
     * @param {string|HTMLElement} selector - Selector or element to apply scrolling to
     * @param {Object} options - Configuration options for scroll behavior
     */
    function SedScroll(selector, options) {
        this.element = typeof selector === 'string' ?
            selector.charAt(0) === '#' ?
            document.getElementById(selector.slice(1)) :
            document.querySelector(selector) :
            selector;
        this.settings = extend({}, SedScroll.defaults, options);
        if (this.element) this.init();
    }

    SedScroll.defaults = {
        width: 'auto', // Width of scrollable area
        height: '250px', // Height of scrollable area
        size: '7px', // Scrollbar and rail width
        color: '#000', // Scrollbar color
        position: 'right', // Scrollbar position
        distance: '1px', // Distance from edge
        start: 'top', // Initial scroll position
        opacity: 0.4, // Scrollbar opacity
        alwaysVisible: false, // Keep scrollbar visible
        disableFadeOut: false, // Disable fade out
        railVisible: false, // Show rail
        railColor: '#333', // Rail color
        railOpacity: 0.2, // Rail opacity
        railDraggable: true, // Enable dragging
        railClass: 'sedScrollRail', // Rail CSS class
        barClass: 'sedScrollBar', // Bar CSS class
        wrapperClass: 'sedScrollDiv', // Wrapper CSS class
        allowPageScroll: false, // Allow page scrolling
        wheelStep: 20, // Wheel scroll step
        touchScrollStep: 200, // Touch scroll step
        borderRadius: '7px', // Scrollbar border radius
        railBorderRadius: '7px' // Rail border radius
    };

    SedScroll.prototype = {
        /**
         * Initializes the scrollbar
         */
        init: function() {
            if (hasClass(this.element.parentNode, this.settings.wrapperClass)) return;
            this.settings.height = this.settings.height === 'auto' ?
                this.element.parentNode.offsetHeight + 'px' :
                this.settings.height;
            this.wrapper = createStyledElement('div', this.settings.wrapperClass, {
                position: 'relative',
                overflow: 'hidden',
                width: this.settings.width,
                height: this.settings.height
            });
            setStyles(this.element, {
                overflow: 'hidden',
                width: this.settings.width,
                height: this.settings.height
            });
            this.rail = createStyledElement('div', this.settings.railClass, {
                width: this.settings.size,
                height: '100%',
                position: 'absolute',
                top: '0',
                display: this.settings.alwaysVisible && this.settings.railVisible ? 'block' : 'none',
                borderRadius: this.settings.railBorderRadius,
                background: this.settings.railColor,
                opacity: this.settings.railOpacity,
                zIndex: '90'
            });
            this.bar = createStyledElement('div', this.settings.barClass, {
                background: this.settings.color,
                width: this.settings.size,
                position: 'absolute',
                top: '0',
                opacity: this.settings.opacity,
                display: this.settings.alwaysVisible ? 'block' : 'none',
                borderRadius: this.settings.borderRadius,
                zIndex: '99'
            });
            var pos = this.settings.position === 'right' ? { right: this.settings.distance } : { left: this.settings.distance };
            setStyles(this.rail, pos);
            setStyles(this.bar, pos);
            this.element.parentNode.insertBefore(this.wrapper, this.element);
            this.wrapper.appendChild(this.element);
            this.wrapper.appendChild(this.bar);
            this.wrapper.appendChild(this.rail);
            this.attachEvents();
            this.updateBarHeight();
            this.setInitialPosition();
        },

        /**
         * Attaches scroll event listeners
         */
        attachEvents: function() {
            var self = this;
            attachWheel(this.element, function(e) {
                var delta = e.wheelDelta ? -e.wheelDelta / 120 : e.detail / 3;
                self.scrollContent(delta, true);
                if (!self.settings.allowPageScroll) e.preventDefault();
            });
            if (this.settings.railDraggable) {
                addEvent(this.bar, 'mousedown', function(e) {
                    self.isDragging = true;
                    self.startTop = parseFloat(self.bar.style.top) || 0;
                    self.pageY = e.pageY;
                    var moveHandler = function(e) {
                        var top = self.startTop + e.pageY - self.pageY;
                        self.bar.style.top = top + 'px';
                        self.scrollContent(0, top, false);
                    };
                    var upHandler = function() {
                        self.isDragging = false;
                        removeEvent(document, 'mousemove', moveHandler);
                        removeEvent(document, 'mouseup', upHandler);
                    };
                    addEvent(document, 'mousemove', moveHandler);
                    addEvent(document, 'mouseup', upHandler);
                    return false;
                });
            }
            addEvent(this.element, 'touchstart', function(e) {
                if (e.touches) self.touchDif = e.touches[0].pageY;
            });
            addEvent(this.element, 'touchmove', function(e) {
                if (e.touches && !self.releaseScroll) {
                    e.preventDefault();
                    var diff = (self.touchDif - e.touches[0].pageY) / self.settings.touchScrollStep;
                    self.scrollContent(diff, true);
                    self.touchDif = e.touches[0].pageY;
                }
            });
        },

        /**
         * Scrolls content by a specified amount
         * @param {number} y - Scroll distance
         * @param {boolean} isWheel - Whether triggered by wheel
         */
        scrollContent: function(y, isWheel) {
            var maxTop = this.element.offsetHeight - this.bar.offsetHeight;
            var delta = y;
            if (isWheel) {
                delta = (parseFloat(this.bar.style.top) || 0) +
                    y * this.settings.wheelStep / 100 * this.bar.offsetHeight;
                delta = Math.min(Math.max(delta, 0), maxTop);
                delta = y > 0 ? Math.ceil(delta) : Math.floor(delta);
                this.bar.style.top = delta + 'px';
            }
            this.element.scrollTop =
                (parseFloat(this.bar.style.top) || 0) /
                (this.element.offsetHeight - this.bar.offsetHeight) *
                (this.element.scrollHeight - this.element.offsetHeight);
            this.updateBarHeight();
        },

        /**
         * Updates scrollbar height based on content
         */
        updateBarHeight: function() {
            this.barHeight = Math.max(
                (this.element.offsetHeight / this.element.scrollHeight) * this.element.offsetHeight,
                30
            );
            this.bar.style.height = this.barHeight + 'px';
            this.bar.style.display = this.barHeight === this.element.offsetHeight ? 'none' : 'block';
        },

        /**
         * Sets initial scroll position
         */
        setInitialPosition: function() {
            if (this.settings.start === 'bottom') {
                this.bar.style.top = (this.element.offsetHeight - this.bar.offsetHeight) + 'px';
                this.scrollContent(0, true);
            }
        }
    };

    // Menu Functionality

    /**
     * Initializes menu behaviors including dropdowns and accordion menus
     */
    function initMenu() {
        // Dropdown menu handler
        var dropdowns = document.querySelectorAll('.dropdown-menu');

        for (var i = 0; i < dropdowns.length; i++) {
            var dropdown = dropdowns[i];
            var btn = dropdown.querySelector('.dropdown-btn');

            // Handle button click for dropdown
            if (btn) {
                addEvent(btn, 'click', function(e) {
                    var parentDropdown = this.closest('.dropdown-menu');
                    var ul = parentDropdown.querySelector('ul');

                    // Close all other open dropdowns
                    var allMenus = document.querySelectorAll('.dropdown-menu ul');
                    for (var j = 0; j < allMenus.length; j++) {
                        if (allMenus[j] !== ul && hasClass(allMenus[j], 'show')) {
                            removeClass(allMenus[j], 'show');
                        }
                    }

                    // Toggle current dropdown
                    toggleClass(ul, 'show');

                    e.preventDefault();
                    e.stopPropagation();
                });
            }
        }

        // Close dropdowns on outside click
        addEvent(document, 'click', function(e) {
            var dropdowns = document.querySelectorAll('.dropdown-menu');
            var clickedOutside = true;

            for (var i = 0; i < dropdowns.length; i++) {
                if (isDescendant(dropdowns[i], e.target)) {
                    clickedOutside = false;
                    break;
                }
            }

            if (clickedOutside) {
                var ulElements = document.querySelectorAll('.dropdown-menu ul');
                for (var i = 0; i < ulElements.length; i++) {
                    removeClass(ulElements[i], 'show');
                }
            }
        });

        // Accordion Menu
        var mainNav = document.getElementById('main-nav');
        if (!mainNav) return;

        var subMenus = mainNav.querySelectorAll('li ul');
        var topItems = mainNav.querySelectorAll('li a.nav-top-item');
        var noSubmenuItems = mainNav.querySelectorAll('li a.no-submenu');

        for (var i = 0; i < subMenus.length; i++) {
            subMenus[i].style.display = 'none';
        }

        var currentItem = mainNav.querySelector('li a.current');
        if (currentItem) {
            var currentSubMenu = currentItem.parentNode.querySelector('ul');
            if (currentSubMenu) {
                slideToggle(currentSubMenu, 600);
            }
        }

        for (var i = 0; i < topItems.length; i++) {
            addEvent(topItems[i], 'click', function(e) {
                toggleClass(this, 'current');
                var siblings = getSiblings(this.parentNode);
                for (var j = 0; j < siblings.length; j++) {
                    var siblingSubMenu = siblings[j].querySelector('ul');
                    if (siblingSubMenu) {
                        slideUp(siblingSubMenu, 400);
                    }
                }
                var nextSubMenu = this.nextElementSibling;
                if (nextSubMenu) {
                    slideToggle(nextSubMenu, 400);
                }
                e.preventDefault();
                return false;
            });
        }

        for (var i = 0; i < noSubmenuItems.length; i++) {
            addEvent(noSubmenuItems[i], 'click', function(e) {
                window.location.href = this.href;
                e.preventDefault();
                return false;
            });
        }

        var navTopItems = mainNav.querySelectorAll('li .nav-top-item');
        for (var i = 0; i < navTopItems.length; i++) {
            addEvent(navTopItems[i], 'mouseenter', function() {
                animate(this, { paddingLeft: '25px' }, 200);
            });
            addEvent(navTopItems[i], 'mouseleave', function() {
                animate(this, { paddingLeft: '15px' }, 200);
            });
        }
    }

    // Content Functionality

    /**
     * Initializes content-related behaviors including collapsible boxes, table styling, and checkbox controls
     */
    function initContent() {
        // Minimize Content Box
        var headers = document.querySelectorAll('.content-box-header h3');
        for (var i = 0; i < headers.length; i++) {
            setStyles(headers[i], { cursor: 's-resize' });
            addEvent(headers[i], 'click', function() {
                var contentBox = this.parentNode.parentNode;
                // Check for either content class to support both structures
                var content = contentBox.querySelector('.content-box-content-tabs') ||
                    contentBox.querySelector('.content-box-content');
                var tabs = contentBox.querySelector('.content-box-tabs');

                // Toggle content visibility
                toggleElement(content);
                toggleClass(contentBox, 'closed-box');
                // Toggle tabs visibility if present
                if (tabs) toggleElement(tabs);

                // Preserve active tab state if applicable
                if (content) {
                    var activeTab = content.querySelector('.tab-content[style="display: block;"]');
                    if (!activeTab && !hasClass(contentBox, 'closed-box')) {
                        // Show first tab if no active tab exists and box is open
                        var firstTab = content.querySelector('.tab-content');
                        if (firstTab) firstTab.style.display = 'block';
                    }
                }
            });
        }

        // Hide content of initially closed boxes
        var closedBoxes = document.querySelectorAll('.closed-box');
        for (var i = 0; i < closedBoxes.length; i++) {
            var content = closedBoxes[i].querySelector('.content-box-content-tabs') ||
                closedBoxes[i].querySelector('.content-box-content');
            var tabs = closedBoxes[i].querySelector('.content-box-tabs');
            if (content) content.style.display = 'none';
            if (tabs) tabs.style.display = 'none';
        }

        // Apply alternating styles to table rows
        var tableRows = document.querySelectorAll('tbody tr');
        for (var i = 0; i < tableRows.length; i++) {
            if (i % 2 === 0) {
                addClass(tableRows[i], 'alt-row');
            }
        }

        // Handle "check all" checkboxes
        var checkAll = document.querySelectorAll('.check-all');
        for (var i = 0; i < checkAll.length; i++) {
            addEvent(checkAll[i], 'click', function() {
                var table = this.closest('table');
                var checkboxes = table.querySelectorAll("input[type='checkbox']");
                var isChecked = this.checked;
                for (var j = 0; j < checkboxes.length; j++) {
                    checkboxes[j].checked = isChecked;
                }
            });
        }

        // Tab switching functionality
        var tabLinks = document.querySelectorAll('.content-box-tabs a');
        for (var i = 0; i < tabLinks.length; i++) {
            addEvent(tabLinks[i], 'click', function(e) {
                e.preventDefault();

                // Remove 'selected' class from all tab links
                var allLinks = this.parentNode.parentNode.querySelectorAll('a');
                for (var j = 0; j < allLinks.length; j++) {
                    removeClass(allLinks[j], 'selected');
                }
                addClass(this, 'selected');

                // Show corresponding tab content
                var contentBox = this.closest('.content-box');
                var contentContainer = contentBox.querySelector('.content-box-content-tabs') ||
                    contentBox.querySelector('.content-box-content');
                if (contentContainer) {
                    var tabContents = contentContainer.querySelectorAll('.tab-content');
                    for (var j = 0; j < tabContents.length; j++) {
                        tabContents[j].style.display = 'none';
                    }

                    var tabId = this.getAttribute('href').split('#')[1];
                    var targetTab = contentContainer.querySelector('#' + tabId);
                    if (targetTab) {
                        targetTab.style.display = 'block';
                    }

                    // Update header title if data-tabtitle is present
                    var tabTitle = this.getAttribute('data-tabtitle');
                    if (tabTitle) {
                        var h3 = contentBox.querySelector('h3.tab-title');
                        if (h3) h3.textContent = tabTitle;
                    }
                }
            });
        }
    }

    /**
     * Toggles an element's visibility
     * @param {HTMLElement} element - The element to toggle
     */
    function toggleElement(element) {
        if (element) {
            element.style.display = element.style.display === 'none' ? 'block' : 'none';
        }
    }

    /**
     * Attaches wheel event listeners
     * @param {HTMLElement} el - The element to attach to
     * @param {Function} callback - The event handler
     */
    function attachWheel(el, callback) {
        if (window.addEventListener) {
            el.addEventListener('DOMMouseScroll', callback, false);
            el.addEventListener('mousewheel', callback, false);
            el.addEventListener('wheel', callback, false);
        } else {
            el.attachEvent('onmousewheel', callback);
        }
    }

    // Public API
    return {
        /**
         * Creates a new sidebar instance
         * @param {string|HTMLElement} holder - Sidebar container
         * @param {string|HTMLElement} eventElement - Toggle element
         * @param {string} side - Position ('right' or 'left')
         * @param {string} width - Sidebar width
         * @param {number} speed - Animation speed
         * @returns {sedSidebar} sedSidebar instance
         */
        sedSidebar: function(holder, eventElement, side, width, speed) {
            return new sedSidebar(holder, eventElement, side, width, speed);
        },

        /**
         * Creates a new scroll instance
         * @param {string|HTMLElement} selector - Scroll target
         * @param {Object} options - Scroll options
         * @returns {SedScroll} SedScroll instance
         */
        sedScroll: function(selector, options) {
            return new SedScroll(selector, options);
        },

        /**
         * Initializes menu functionality
         */
        initMenu: function() {
            initMenu();
        },

        /**
         * Initializes content functionality
         */
        initContent: function() {
            initContent();
        }
    };
})();