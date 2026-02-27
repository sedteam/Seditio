/**
 * @package SedAdminJS
 * @author Tishov Alexander [info@avego.org]
 * @copyright Tishov Alexander, Seditio Team 2025
 * @since JavaScript ES6+
 * @version 2.0
 */

"use strict";
/* Target: ES6+ */

const sedadminjs = (() => {
    /**
     * Applies CSS styles to an element
     * @param {HTMLElement} element - The element to style
     * @param {Object} styles - CSS properties to apply
     */
    const setStyles = (element, styles) => {
        for (const prop in styles) {
            if (styles.hasOwnProperty(prop)) {
                element.style[prop] = styles[prop];
            }
        }
    };

    /**
     * Creates a styled DOM element
     * @param {string} tag - The HTML tag name
     * @param {string} className - The class to apply
     * @param {Object} styles - CSS styles to apply
     * @returns {HTMLElement} The created element
     */
    const createStyledElement = (tag, className, styles) => {
        const el = document.createElement(tag);
        el.className = className;
        setStyles(el, styles);
        return el;
    };

    /**
     * Checks if an element is a descendant of another
     * @param {HTMLElement} parent - The parent element
     * @param {HTMLElement} child - The child element to check
     * @returns {boolean} True if child is a descendant, false otherwise
     */
    const isDescendant = (parent, child) => {
        if (!parent) return false;
        let node = child;
        while (node !== null) {
            if (node === parent) return true;
            node = node.parentNode;
        }
        return false;
    };

    /**
     * Gets all sibling elements of a given element
     * @param {HTMLElement} element - The reference element
     * @returns {Array} Array of sibling elements
     */
    const getSiblings = (element) => {
        const siblings = [];
        let sibling = element.parentNode.firstChild;
        while (sibling) {
            if (sibling.nodeType === 1 && sibling !== element) {
                siblings.push(sibling);
            }
            sibling = sibling.nextSibling;
        }
        return siblings;
    };

    /**
     * Toggles an element's visibility with slide animation
     * @param {HTMLElement} element - The element to toggle
     * @param {number} duration - Animation duration in milliseconds
     */
    const slideToggle = (element, duration) => {
        if (element.style.display === 'none' || !element.style.display) {
            slideDown(element, duration);
        } else {
            slideUp(element, duration);
        }
    };

    /**
     * Slides down an element with animation
     * @param {HTMLElement} element - The element to slide down
     * @param {number} duration - Animation duration in milliseconds
     */
    const slideDown = (element, duration) => {
        element.style.display = 'block';
        const height = element.offsetHeight;
        element.style.height = '0px';
        element.style.overflow = 'hidden';
        animate(element, { height: `${height}px` }, duration, () => {
            element.style.height = '';
            element.style.overflow = '';
        });
    };

    /**
     * Slides up an element with animation
     * @param {HTMLElement} element - The element to slide up
     * @param {number} duration - Animation duration in milliseconds
     */
    const slideUp = (element, duration) => {
        const height = element.offsetHeight;
        element.style.height = `${height}px`;
        element.style.overflow = 'hidden';
        animate(element, { height: '0px' }, duration, () => {
            element.style.display = 'none';
            element.style.height = '';
            element.style.overflow = '';
        });
    };

    /**
     * Animates element properties
     * @param {HTMLElement} element - The element to animate
     * @param {Object} properties - CSS properties to animate
     * @param {number} duration - Animation duration in milliseconds
     * @param {Function} [callback] - Optional callback after animation
     */
    const animate = (element, properties, duration, callback) => {
        let start = null;
        const initialValues = {};
        const targetValues = {};

        for (const prop in properties) {
            initialValues[prop] = parseFloat(getComputedStyle(element)[prop]) || 0;
            targetValues[prop] = parseFloat(properties[prop]);
        }

        const step = (timestamp) => {
            if (!start) start = timestamp;
            const progress = Math.min((timestamp - start) / duration, 1);

            for (const prop in properties) {
                const value = initialValues[prop] + (targetValues[prop] - initialValues[prop]) * progress;
                element.style[prop] = `${value}px`;
            }

            if (progress < 1) {
                requestAnimationFrame(step);
            } else if (callback) {
                callback();
            }
        };

        requestAnimationFrame(step);
    };

    /**
     * Sidebar component for admin interface
     */
    class SedSidebar {
        /**
         * @param {string|HTMLElement} holder - Selector or element for sidebar container
         * @param {string|HTMLElement} eventElement - Selector or element for toggle button
         * @param {string} side - Sidebar position ('right' or 'left')
         * @param {string} width - Sidebar width
         * @param {number} speed - Animation speed in milliseconds
         */
        constructor(holder, eventElement, side, width, speed) {
            this.holder = typeof holder === 'string' ? document.querySelector(holder) : holder;
            this.eventElement = typeof eventElement === 'string' ? document.querySelector(eventElement) : eventElement;
            this.side = side;
            this.width = width;
            this.speed = speed;
            if (this.holder) this.init();
        }

        init() {
            if (this.side === 'right') {
                sedjs.addClass(this.holder, 'right');
            }
            if (this.width) {
                this.holder.style.maxWidth = this.width;
            }
            if (this.speed) {
                this.holder.style.transitionDuration = `${this.speed / 1000}s`;
            }
            this.clickEvent();
        }

        clickEvent() {
            const closeBtn = this.holder.querySelector('a.sidebar-close');

            document.addEventListener('click', (e) => {
                const target = e.target;
                if (target === this.eventElement) {
                    sedjs.addClass(this.holder, 'active');
                    sedjs.addClass(this.eventElement, 'nav-is-visible');
                    sedjs.addClass(document.documentElement, 'disable-scrolling');
                    e.preventDefault();
                    return false;
                }
                const isOutsideHolder = !isDescendant(this.holder, target);
                const isCloseBtnClick = closeBtn && isDescendant(closeBtn, target);
                if ((isOutsideHolder || isCloseBtnClick) && sedjs.hasClass(this.holder, 'active')) {
                    sedjs.removeClass(this.holder, 'active');
                    sedjs.removeClass(this.eventElement, 'nav-is-visible');
                    sedjs.removeClass(document.documentElement, 'disable-scrolling');
                    e.preventDefault();
                    return false;
                }
            });
        }
    }

    /**
     * Custom scrollbar component
     */
    class SedScroll {
        static defaults = {
            width: 'auto',
            height: '250px',
            size: '7px',
            color: '#000',
            position: 'right',
            distance: '1px',
            start: 'top',
            opacity: 0.4,
            alwaysVisible: false,
            disableFadeOut: false,
            railVisible: false,
            railColor: '#333',
            railOpacity: 0.2,
            railDraggable: true,
            railClass: 'sedScrollRail',
            barClass: 'sedScrollBar',
            wrapperClass: 'sedScrollDiv',
            allowPageScroll: false,
            wheelStep: 20,
            touchScrollStep: 200,
            borderRadius: '7px',
            railBorderRadius: '7px'
        };

        /**
         * @param {string|HTMLElement} selector - Selector or element to apply scrolling to
         * @param {Object} options - Configuration options for scroll behavior
         */
        constructor(selector, options) {
            this.element = typeof selector === 'string' ?
                selector.charAt(0) === '#' ?
                document.getElementById(selector.slice(1)) :
                document.querySelector(selector) :
                selector;
            this.settings = sedjs.extend({}, SedScroll.defaults, options);
            if (this.element) this.init();
        }

        init() {
            if (sedjs.hasClass(this.element.parentNode, this.settings.wrapperClass)) return;
            this.settings.height = this.settings.height === 'auto' ?
                `${this.element.parentNode.offsetHeight}px` :
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
            const pos = this.settings.position === 'right' ? { right: this.settings.distance } : { left: this.settings.distance };
            setStyles(this.rail, pos);
            setStyles(this.bar, pos);
            this.element.parentNode.insertBefore(this.wrapper, this.element);
            this.wrapper.appendChild(this.element);
            this.wrapper.appendChild(this.bar);
            this.wrapper.appendChild(this.rail);
            this.attachEvents();
            this.updateBarHeight();
            this.setInitialPosition();
        }

        attachEvents() {
            attachWheel(this.element, (e) => {
                const delta = e.wheelDelta ? -e.wheelDelta / 120 : e.detail / 3;
                this.scrollContent(delta, true);
                if (!this.settings.allowPageScroll) e.preventDefault();
            });
            if (this.settings.railDraggable) {
                this.bar.addEventListener('mousedown', (e) => {
                    this.isDragging = true;
                    this.startTop = parseFloat(this.bar.style.top) || 0;
                    this.pageY = e.pageY;
                    const moveHandler = (e) => {
                        const top = this.startTop + e.pageY - this.pageY;
                        this.bar.style.top = `${top}px`;
                        this.scrollContent(0, top, false);
                    };
                    const upHandler = () => {
                        this.isDragging = false;
                        document.removeEventListener('mousemove', moveHandler);
                        document.removeEventListener('mouseup', upHandler);
                    };
                    document.addEventListener('mousemove', moveHandler);
                    document.addEventListener('mouseup', upHandler);
                    return false;
                });
            }
            this.element.addEventListener('touchstart', (e) => {
                if (e.touches) this.touchDif = e.touches[0].pageY;
            });
            this.element.addEventListener('touchmove', (e) => {
                if (e.touches && !this.releaseScroll) {
                    e.preventDefault();
                    const diff = (this.touchDif - e.touches[0].pageY) / this.settings.touchScrollStep;
                    this.scrollContent(diff, true);
                    this.touchDif = e.touches[0].pageY;
                }
            });
        }

        scrollContent(y, isWheel) {
            const maxTop = this.element.offsetHeight - this.bar.offsetHeight;
            let delta = y;
            if (isWheel) {
                delta = (parseFloat(this.bar.style.top) || 0) +
                    y * this.settings.wheelStep / 100 * this.bar.offsetHeight;
                delta = Math.min(Math.max(delta, 0), maxTop);
                delta = y > 0 ? Math.ceil(delta) : Math.floor(delta);
                this.bar.style.top = `${delta}px`;
            }
            this.element.scrollTop =
                (parseFloat(this.bar.style.top) || 0) /
                (this.element.offsetHeight - this.bar.offsetHeight) *
                (this.element.scrollHeight - this.element.offsetHeight);
            this.updateBarHeight();
        }

        updateBarHeight() {
            this.barHeight = Math.max(
                (this.element.offsetHeight / this.element.scrollHeight) * this.element.offsetHeight,
                30
            );
            this.bar.style.height = `${this.barHeight}px`;
            this.bar.style.display = this.barHeight === this.element.offsetHeight ? 'none' : 'block';
        }

        setInitialPosition() {
            if (this.settings.start === 'bottom') {
                this.bar.style.top = `${this.element.offsetHeight - this.bar.offsetHeight}px`;
                this.scrollContent(0, true);
            }
        }
    }

    /**
     * Initializes menu behaviors including dropdowns and accordion menus
     */
    const initMenu = () => {
        const dropdowns = document.querySelectorAll('.dropdown-menu');

        for (const dropdown of dropdowns) {
            const btn = dropdown.querySelector('.dropdown-btn');

            if (btn) {
                btn.addEventListener('click', function(e) {
                    const parentDropdown = this.closest('.dropdown-menu');
                    const ul = parentDropdown.querySelector('ul');

                    const allMenus = document.querySelectorAll('.dropdown-menu ul');
                    for (const menu of allMenus) {
                        if (menu !== ul && sedjs.hasClass(menu, 'show')) {
                            sedjs.removeClass(menu, 'show');
                        }
                    }

                    sedjs.toggleClass(ul, 'show');

                    e.preventDefault();
                    e.stopPropagation();
                });
            }
        }

        document.addEventListener('click', (e) => {
            const dropdowns = document.querySelectorAll('.dropdown-menu');
            let clickedOutside = true;

            for (const dropdown of dropdowns) {
                if (isDescendant(dropdown, e.target)) {
                    clickedOutside = false;
                    break;
                }
            }

            if (clickedOutside) {
                const ulElements = document.querySelectorAll('.dropdown-menu ul');
                for (const ul of ulElements) {
                    sedjs.removeClass(ul, 'show');
                }
            }
        });

        const mainNav = document.getElementById('main-nav');
        if (!mainNav) return;

        const subMenus = mainNav.querySelectorAll('li ul');
        const topItems = mainNav.querySelectorAll('li a.nav-top-item');
        const noSubmenuItems = mainNav.querySelectorAll('li a.no-submenu');

        for (const subMenu of subMenus) {
            subMenu.style.display = 'none';
        }

        const currentItem = mainNav.querySelector('li a.current');
        if (currentItem) {
            const currentSubMenu = currentItem.parentNode.querySelector('ul');
            if (currentSubMenu) {
                slideToggle(currentSubMenu, 600);
            }
        }

        for (const topItem of topItems) {
            topItem.addEventListener('click', function(e) {
                sedjs.toggleClass(this, 'current');
                const siblings = getSiblings(this.parentNode);
                for (const sibling of siblings) {
                    const siblingLink = sibling.querySelector('a.nav-top-item');
                    if (siblingLink) {
                        sedjs.removeClass(siblingLink, 'current');
                    }
                    const siblingSubMenu = sibling.querySelector('ul');
                    if (siblingSubMenu) {
                        slideUp(siblingSubMenu, 400);
                    }
                }
                const nextSubMenu = this.nextElementSibling;
                if (nextSubMenu) {
                    slideToggle(nextSubMenu, 400);
                }
                e.preventDefault();
                return false;
            });
        }

        for (const noSubmenuItem of noSubmenuItems) {
            noSubmenuItem.addEventListener('click', function(e) {
                window.location.href = this.href;
                e.preventDefault();
                return false;
            });
        }

        const navTopItems = mainNav.querySelectorAll('li .nav-top-item');
        for (const navTopItem of navTopItems) {
            navTopItem.addEventListener('mouseenter', function() {
                animate(this, { paddingLeft: '25px' }, 200);
            });
            navTopItem.addEventListener('mouseleave', function() {
                animate(this, { paddingLeft: '15px' }, 200);
            });
        }
    };

    /**
     * Initializes content-related behaviors including collapsible boxes, table styling, and checkbox controls
     */
    const initContent = () => {
        const headers = document.querySelectorAll('.content-box-header h3');
        for (const header of headers) {
            setStyles(header, { cursor: 's-resize' });
            header.addEventListener('click', function() {
                const contentBox = this.parentNode.parentNode;
                const content = contentBox.querySelector('.content-box-content-tabs') ||
                    contentBox.querySelector('.content-box-content');
                const tabs = contentBox.querySelector('.content-box-tabs');

                toggleElement(content);
                sedjs.toggleClass(contentBox, 'closed-box');
                if (tabs) toggleElement(tabs);

                if (content) {
                    const activeTab = content.querySelector('.tab-content[style="display: block;"]');
                    if (!activeTab && !sedjs.hasClass(contentBox, 'closed-box')) {
                        const firstTab = content.querySelector('.tab-content');
                        if (firstTab) firstTab.style.display = 'block';
                    }
                }
            });
        }

        const closedBoxes = document.querySelectorAll('.closed-box');
        for (const closedBox of closedBoxes) {
            const content = closedBox.querySelector('.content-box-content-tabs') ||
                closedBox.querySelector('.content-box-content');
            const tabs = closedBox.querySelector('.content-box-tabs');
            if (content) content.style.display = 'none';
            if (tabs) tabs.style.display = 'none';
        }

        const tableRows = document.querySelectorAll('tbody tr');
        for (let i = 0; i < tableRows.length; i++) {
            if (i % 2 === 0) {
                sedjs.addClass(tableRows[i], 'alt-row');
            }
        }

        const checkAll = document.querySelectorAll('.check-all');
        for (const checkbox of checkAll) {
            checkbox.addEventListener('click', function() {
                const table = this.closest('table');
                const checkboxes = table.querySelectorAll("input[type='checkbox']");
                const isChecked = this.checked;
                for (const cb of checkboxes) {
                    cb.checked = isChecked;
                }
            });
        }

        const tabLinks = document.querySelectorAll('.content-box-tabs a');
        for (const tabLink of tabLinks) {
            tabLink.addEventListener('click', function(e) {
                e.preventDefault();

                const allLinks = this.parentNode.parentNode.querySelectorAll('a');
                for (const link of allLinks) {
                    sedjs.removeClass(link, 'selected');
                }
                sedjs.addClass(this, 'selected');

                const contentBox = this.closest('.content-box');
                const contentContainer = contentBox.querySelector('.content-box-content-tabs') ||
                    contentBox.querySelector('.content-box-content');
                if (contentContainer) {
                    const tabContents = contentContainer.querySelectorAll('.tab-content');
                    for (const tabContent of tabContents) {
                        tabContent.style.display = 'none';
                    }

                    const tabId = this.getAttribute('href').split('#')[1];
                    const targetTab = contentContainer.querySelector(`#${tabId}`);
                    if (targetTab) {
                        targetTab.style.display = 'block';
                    }

                    const tabTitle = this.getAttribute('data-tabtitle');
                    if (tabTitle) {
                        const h3 = contentBox.querySelector('h3.tab-title');
                        if (h3) h3.textContent = tabTitle;
                    }
                }
            });
        }
    };

    /**
     * Toggles an element's visibility
     * @param {HTMLElement} element - The element to toggle
     */
    const toggleElement = (element) => {
        if (element) {
            element.style.display = element.style.display === 'none' ? 'block' : 'none';
        }
    };

    /**
     * Attaches wheel event listeners
     * @param {HTMLElement} el - The element to attach to
     * @param {Function} callback - The event handler
     */
    const attachWheel = (el, callback) => {
        el.addEventListener('DOMMouseScroll', callback, false);
        el.addEventListener('mousewheel', callback, false);
        el.addEventListener('wheel', callback, false);
    };

    // Public API
    return {
        /**
         * Creates a new sidebar instance
         * @param {string|HTMLElement} holder - Sidebar container
         * @param {string|HTMLElement} eventElement - Toggle element
         * @param {string} side - Position ('right' or 'left')
         * @param {string} width - Sidebar width
         * @param {number} speed - Animation speed
         * @returns {SedSidebar} SedSidebar instance
         */
        sedSidebar(holder, eventElement, side, width, speed) {
            return new SedSidebar(holder, eventElement, side, width, speed);
        },

        /**
         * Creates a new scroll instance
         * @param {string|HTMLElement} selector - Scroll target
         * @param {Object} options - Scroll options
         * @returns {SedScroll} SedScroll instance
         */
        sedScroll(selector, options) {
            return new SedScroll(selector, options);
        },

        /**
         * Initializes menu functionality
         */
        initMenu() {
            initMenu();
        },

        /**
         * Initializes content functionality
         */
        initContent() {
            initContent();
        }
    };
})();