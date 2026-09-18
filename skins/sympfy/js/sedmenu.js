/**
 * Seditio Native Multi-Level Sliding Menu (SedMenu)
 * Zero-dependency Vanilla JS multi-level drilldown sliding menu.
 *
 * @version 1.0.0
 * @author Seditio Team
 * @license BSD-3-Clause
 */

(function (global) {
    'use strict';

    class SedMenu {
        constructor(element, options = {}) {
            this.container = typeof element === 'string' ? document.querySelector(element) : element;
            if (!this.container) return;

            if (this.container._sedMenu) {
                return this.container._sedMenu;
            }
            this.container._sedMenu = this;

            this.options = Object.assign({
                speed: 300,
                title: true,
                resize: true
            }, options);

            this.base = this.container.querySelector('ul');
            if (!this.base) return;

            this.currentDepth = 0;
            this.activeUl = this.base;
            this._isAnimating = false;
            this._boundClick = this._handleClick.bind(this);

            this.init();
        }

        init() {
            this.container.classList.add('sed-menu');
            this.base.classList.add('sed-menu-list');
            this.base.style.position = 'relative';
            this.base.style.left = '0%';
            this.base.style.transition = `left ${this.options.speed}ms ease`;
            this.container.style.transition = `height ${this.options.speed}ms ease`;

            // Mark items with submenus
            const subLists = this.container.querySelectorAll('li > ul');
            subLists.forEach(ul => {
                const parentLi = ul.parentElement;
                parentLi.classList.add('sed-menu-sub');

                const parentLink = parentLi.querySelector(':scope > a');
                if (parentLink) {
                    parentLink.classList.add('sed-menu-next');
                    if (!parentLink.querySelector('span')) {
                        const span = document.createElement('span');
                        while (parentLink.firstChild) {
                            span.appendChild(parentLink.firstChild);
                        }
                        parentLink.appendChild(span);
                    }
                }

                // Create header with back button and title
                const headerLi = document.createElement('li');
                headerLi.className = 'sed-menu-header';

                const backLink = document.createElement('a');
                backLink.href = '#';
                backLink.className = 'sed-menu-back';
                headerLi.appendChild(backLink);

                if (this.options.title && parentLink) {
                    const titleText = parentLink.textContent.trim();
                    if (titleText) {
                        const titleHeader = document.createElement('header');
                        titleHeader.className = 'sed-menu-title';
                        titleHeader.textContent = titleText;
                        headerLi.appendChild(titleHeader);
                    }
                }

                ul.insertBefore(headerLi, ul.firstChild);
                ul.style.display = 'none';
            });

            // Wrap plain links in span if needed
            const allLinks = this.container.querySelectorAll('li > a:not(.sed-menu-back)');
            allLinks.forEach(link => {
                if (!link.querySelector('span')) {
                    const span = document.createElement('span');
                    while (link.firstChild) {
                        span.appendChild(link.firstChild);
                    }
                    link.appendChild(span);
                }
            });

            this.base.classList.add('sed-menu-active');
            this.container.addEventListener('click', this._boundClick);
            this._updateHeight(this.base);
        }

        _handleClick(e) {
            if (this._isAnimating) return;

            const nextBtn = e.target.closest('.sed-menu-next');
            const backBtn = e.target.closest('.sed-menu-back');

            if (nextBtn) {
                e.preventDefault();
                const subUl = nextBtn.parentElement.querySelector(':scope > ul');
                if (subUl) {
                    this.forward(subUl);
                }
                return;
            }

            if (backBtn) {
                e.preventDefault();
                const currentUl = backBtn.closest('ul');
                if (currentUl && currentUl !== this.base) {
                    this.backward(currentUl);
                }
                return;
            }
        }

        forward(subUl) {
            if (this._isAnimating) return;
            this._isAnimating = true;

            this.currentDepth++;
            subUl.style.display = 'block';
            subUl.classList.add('sed-menu-active');
            if (this.activeUl) {
                this.activeUl.classList.remove('sed-menu-active');
            }
            this.activeUl = subUl;

            this.base.style.left = `-${this.currentDepth * 100}%`;
            if (this.options.resize) {
                this._updateHeight(subUl);
            }

            setTimeout(() => {
                this._isAnimating = false;
            }, this.options.speed);
        }

        backward(currentUl) {
            if (this._isAnimating) return;
            this._isAnimating = true;

            const parentUl = currentUl.parentElement.closest('ul');
            this.currentDepth = Math.max(0, this.currentDepth - 1);

            currentUl.classList.remove('sed-menu-active');
            if (parentUl) {
                parentUl.classList.add('sed-menu-active');
                this.activeUl = parentUl;
            }

            this.base.style.left = `-${this.currentDepth * 100}%`;
            if (this.options.resize && parentUl) {
                this._updateHeight(parentUl);
            }

            setTimeout(() => {
                currentUl.style.display = 'none';
                this._isAnimating = false;
            }, this.options.speed);
        }

        _updateHeight(el) {
            if (el) {
                this.container.style.height = `${el.offsetHeight}px`;
            }
        }

        home() {
            if (this.currentDepth === 0) return;
            this.base.style.left = '0%';
            this.currentDepth = 0;
            const allSubs = this.container.querySelectorAll('li > ul');
            allSubs.forEach(ul => {
                ul.classList.remove('sed-menu-active');
                ul.style.display = 'none';
            });
            this.base.classList.add('sed-menu-active');
            this.activeUl = this.base;
            if (this.options.resize) {
                this._updateHeight(this.base);
            }
        }

        destroy() {
            this.container.removeEventListener('click', this._boundClick);
            this.container._sedMenu = null;
            this.container.classList.remove('sed-menu');
            this.container.style.height = '';
            this.container.style.transition = '';

            if (this.base) {
                this.base.classList.remove('sed-menu-list', 'sed-menu-active');
                this.base.style.position = '';
                this.base.style.left = '';
                this.base.style.transition = '';
            }

            const headers = this.container.querySelectorAll('.sed-menu-header');
            headers.forEach(h => h.remove());

            const subLists = this.container.querySelectorAll('li > ul');
            subLists.forEach(ul => {
                ul.classList.remove('sed-menu-active');
                ul.style.display = '';
                ul.parentElement.classList.remove('sed-menu-sub');
            });

            const nextLinks = this.container.querySelectorAll('.sed-menu-next');
            nextLinks.forEach(a => a.classList.remove('sed-menu-next'));
        }
    }

    // Expose globally
    global.SedMenu = SedMenu;
    global.sedMenu = function (selector, options) {
        if (typeof selector === 'string') {
            const elements = document.querySelectorAll(selector);
            const instances = [];
            elements.forEach(el => instances.push(new SedMenu(el, options)));
            return instances.length === 1 ? instances[0] : instances;
        } else if (selector instanceof HTMLElement) {
            return new SedMenu(selector, options);
        }
    };

})(typeof window !== 'undefined' ? window : this);
