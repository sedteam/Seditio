/**
 * Seditio Native Slider (SedSlider)
 * Fast, lightweight, zero-dependency Vanilla JS slider and carousel engine.
 *
 * @version 1.0.1
 * @author Seditio Team
 * @license BSD-3-Clause
 */

(function (global) {
    'use strict';

    class SedSlider {
        constructor(element, options = {}) {
            if (typeof element === 'string') {
                this.element = document.querySelector(element);
            } else {
                this.element = element;
            }

            if (!this.element) {
                return;
            }

            // Prevent double initialization
            if (this.element._sedSlider) {
                return this.element._sedSlider;
            }
            this.element._sedSlider = this;

            this.rawOptions = Object.assign({}, options);
            this.options = Object.assign({}, this.getDefaults(), options);

            this.slides = [];
            this.clonesCount = 0;
            this.currentIndex = 0;
            this.totalSlides = 0;
            this.isAnimating = false;
            this.autoplayTimer = null;
            this.isHovered = false;

            // Touch / Drag tracking
            this.touchStartX = 0;
            this.touchStartY = 0;
            this.touchCurrentX = 0;
            this.touchCurrentY = 0;
            this.isDragging = false;
            this.hasMoved = false;
            this.dragDistance = 0;
            this.preventClick = false;

            // Bound event handlers
            this._onResize = this._debounce(this._handleResize.bind(this), 100);
            this._onPointerDown = this._handlePointerDown.bind(this);
            this._onPointerMove = this._handlePointerMove.bind(this);
            this._onPointerUp = this._handlePointerUp.bind(this);
            this._onPointerCancel = this._handlePointerCancel.bind(this);
            this._onMouseEnter = this._handleMouseEnter.bind(this);
            this._onMouseLeave = this._handleMouseLeave.bind(this);
            this._onClickCapture = this._handleClickCapture.bind(this);

            this.init();
        }

        getDefaults() {
            return {
                slidesToShow: 1,
                slidesToScroll: 1,
                speed: 500,
                autoplay: false,
                autoplaySpeed: 5000,
                pauseOnHover: true,
                infinite: true,
                arrows: true,
                dots: false,
                fade: false,
                cssEase: 'ease',
                appendArrows: null,
                appendDots: null,
                prevArrow: '<button class="sed-slider-prev sed-slider-arrow" aria-label="Previous" type="button">Previous</button>',
                nextArrow: '<button class="sed-slider-next sed-slider-arrow" aria-label="Next" type="button">Next</button>',
                responsive: [],
                swipe: true,
                swipeThreshold: 50
            };
        }

        init() {
            // Extract initial slides
            const children = Array.from(this.element.children).filter(el => 
                !el.classList.contains('sed-slider-arrow') && 
                !el.classList.contains('sed-slider-dots') &&
                !el.classList.contains('sed-slider-down') &&
                !el.classList.contains('home-slider-arrows') &&
                !el.classList.contains('home-slider-dots')
            );

            if (children.length === 0) return;

            this.originalSlides = children;
            this.totalSlides = children.length;

            this._applyResponsiveSettings();

            // Setup DOM wrapper
            this.element.classList.add('sed-slider');
            if (this.options.fade) {
                this.element.classList.add('sed-slider-fade');
            }

            this.list = document.createElement('div');
            this.list.className = 'sed-slider-list';

            this.track = document.createElement('div');
            this.track.className = 'sed-slider-track';

            this.list.appendChild(this.track);
            this.element.appendChild(this.list);

            // Populate track
            this.originalSlides.forEach((slide, index) => {
                slide.classList.add('sed-slider-slide');
                slide.setAttribute('data-sed-index', index);
                this.track.appendChild(slide);
            });

            this._setupInfiniteClones();
            this._setupArrows();
            this._setupDots();
            this._bindEvents();

            this.element.classList.add('sed-slider-initialized');

            this.goTo(0, true);

            if (this.options.autoplay) {
                this.startAutoplay();
            }
        }

        _applyResponsiveSettings() {
            this.options = Object.assign({}, this.getDefaults(), this.rawOptions);
            const windowWidth = window.innerWidth;

            if (Array.isArray(this.rawOptions.responsive) && this.rawOptions.responsive.length > 0) {
                const sorted = [...this.rawOptions.responsive].sort((a, b) => b.breakpoint - a.breakpoint);
                for (let i = 0; i < sorted.length; i++) {
                    if (windowWidth <= sorted[i].breakpoint) {
                        this.options = Object.assign(this.options, sorted[i].settings);
                    }
                }
            }

            if (this.totalSlides > 0 && this.totalSlides < this.options.slidesToShow) {
                this.options.slidesToShow = this.totalSlides;
            }
        }

        _setupInfiniteClones() {
            // Remove existing clones if any
            const existingClones = this.track.querySelectorAll('.sed-slider-cloned');
            existingClones.forEach(c => c.remove());

            if (this.options.infinite && !this.options.fade && this.totalSlides > 1) {
                const cloneCount = Math.max(this.options.slidesToShow, this.options.slidesToScroll) + 1;
                this.clonesCount = cloneCount;

                // Clones before (from the end of original list)
                for (let i = 0; i < cloneCount; i++) {
                    const originalIdx = (this.totalSlides - 1 - (i % this.totalSlides));
                    const clone = this.originalSlides[originalIdx].cloneNode(true);
                    clone.classList.add('sed-slider-cloned');
                    clone.setAttribute('data-sed-clone', 'pre');
                    this.track.insertBefore(clone, this.track.firstChild);
                }

                // Clones after (from the start of original list)
                for (let i = 0; i < cloneCount; i++) {
                    const originalIdx = (i % this.totalSlides);
                    const clone = this.originalSlides[originalIdx].cloneNode(true);
                    clone.classList.add('sed-slider-cloned');
                    clone.setAttribute('data-sed-clone', 'post');
                    this.track.appendChild(clone);
                }
            } else {
                this.clonesCount = 0;
            }

            this.allSlides = Array.from(this.track.children);
            this._updateSlideDimensions();
        }

        _updateSlideDimensions() {
            const listWidth = this.list.getBoundingClientRect().width || this.element.offsetWidth;
            if (listWidth === 0) return;

            const slideWidth = listWidth / this.options.slidesToShow;
            this.slideWidth = slideWidth;

            if (this.allSlides && this.allSlides.length > 0) {
                this.allSlides.forEach(slide => {
                    slide.style.width = `${slideWidth}px`;
                });

                if (!this.options.fade) {
                    this.track.style.width = `${slideWidth * this.allSlides.length}px`;
                }
            }
        }

        _setupArrows() {
            if (!this.options.arrows) return;

            if (!this.prevBtn) {
                if (typeof this.options.prevArrow === 'string') {
                    const temp = document.createElement('div');
                    temp.innerHTML = this.options.prevArrow.trim();
                    this.prevBtn = temp.firstElementChild;
                }
                if (!this.prevBtn) {
                    this.prevBtn = document.createElement('button');
                    this.prevBtn.className = 'sed-slider-prev sed-slider-arrow';
                    this.prevBtn.type = 'button';
                    this.prevBtn.textContent = 'Previous';
                }

                this.prevBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.prev();
                });

                const arrowContainer = this.options.appendArrows ? 
                    (typeof this.options.appendArrows === 'string' ? document.querySelector(this.options.appendArrows) : this.options.appendArrows) 
                    : this.element;

                if (arrowContainer) {
                    arrowContainer.appendChild(this.prevBtn);
                }
            }

            if (!this.nextBtn) {
                if (typeof this.options.nextArrow === 'string') {
                    const temp = document.createElement('div');
                    temp.innerHTML = this.options.nextArrow.trim();
                    this.nextBtn = temp.firstElementChild;
                }
                if (!this.nextBtn) {
                    this.nextBtn = document.createElement('button');
                    this.nextBtn.className = 'sed-slider-next sed-slider-arrow';
                    this.nextBtn.type = 'button';
                    this.nextBtn.textContent = 'Next';
                }

                this.nextBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.next();
                });

                const arrowContainer = this.options.appendArrows ? 
                    (typeof this.options.appendArrows === 'string' ? document.querySelector(this.options.appendArrows) : this.options.appendArrows) 
                    : this.element;

                if (arrowContainer) {
                    arrowContainer.appendChild(this.nextBtn);
                }
            }
        }

        _setupDots() {
            if (this.dotsContainer) {
                this.dotsContainer.remove();
                this.dotsContainer = null;
            }

            if (!this.options.dots || this.totalSlides <= 1) return;

            const dotsContainer = document.createElement('ul');
            dotsContainer.className = 'sed-slider-dots';

            const dotCount = Math.ceil(this.totalSlides / this.options.slidesToScroll);
            for (let i = 0; i < dotCount; i++) {
                const li = document.createElement('li');
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.textContent = (i + 1).toString();
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.goTo(i * this.options.slidesToScroll);
                });
                li.appendChild(btn);
                dotsContainer.appendChild(li);
            }

            this.dotsContainer = dotsContainer;

            const targetContainer = this.options.appendDots ? 
                (typeof this.options.appendDots === 'string' ? document.querySelector(this.options.appendDots) : this.options.appendDots) 
                : this.element;

            if (targetContainer) {
                targetContainer.appendChild(dotsContainer);
            }
        }

        _bindEvents() {
            window.addEventListener('resize', this._onResize, { passive: true });

            if (this.options.pauseOnHover) {
                this.element.addEventListener('mouseenter', this._onMouseEnter);
                this.element.addEventListener('mouseleave', this._onMouseLeave);
            }

            if (this.options.swipe) {
                if (window.PointerEvent) {
                    this.list.addEventListener('pointerdown', this._onPointerDown);
                    window.addEventListener('pointermove', this._onPointerMove);
                    window.addEventListener('pointerup', this._onPointerUp);
                    window.addEventListener('pointercancel', this._onPointerCancel);
                } else {
                    this.list.addEventListener('touchstart', this._onPointerDown, { passive: true });
                    window.addEventListener('touchmove', this._onPointerMove, { passive: false });
                    window.addEventListener('touchend', this._onPointerUp);
                    this.list.addEventListener('mousedown', this._onPointerDown);
                    window.addEventListener('mousemove', this._onPointerMove);
                    window.addEventListener('mouseup', this._onPointerUp);
                }

                // Prevent accidental link clicking after dragging
                this.list.addEventListener('click', this._onClickCapture, true);
            }
        }

        _handleClickCapture(e) {
            if (this.preventClick) {
                e.preventDefault();
                e.stopPropagation();
                this.preventClick = false;
            }
        }

        _handleMouseEnter() {
            this.isHovered = true;
            this.stopAutoplay();
        }

        _handleMouseLeave(e) {
            if (e && e.relatedTarget && typeof e.relatedTarget.closest === 'function') {
                if (e.relatedTarget.closest('.adm-tooltip')) {
                    // Moving into admin tooltip - keep hovered state
                    return;
                }
            }

            this.isHovered = false;
            if (this.options.autoplay) {
                this.startAutoplay();
            }
        }

        _handleResize() {
            const prevSlidesToShow = this.options.slidesToShow;
            this._applyResponsiveSettings();

            if (prevSlidesToShow !== this.options.slidesToShow) {
                this._setupInfiniteClones();
                this._setupDots();
            } else {
                this._updateSlideDimensions();
            }

            this.goTo(this.currentIndex, true);
        }

        _handlePointerDown(e) {
            if (e.button !== undefined && e.button !== 0) return;
            if (this.isAnimating) return;

            this.isDragging = true;
            this.hasMoved = false;
            this.touchStartX = e.type.startsWith('touch') ? e.touches[0].clientX : e.clientX;
            this.touchStartY = e.type.startsWith('touch') ? e.touches[0].clientY : e.clientY;
            this.touchCurrentX = this.touchStartX;
            this.touchCurrentY = this.touchStartY;
            this.dragDistance = 0;

            this.stopAutoplay();
        }

        _handlePointerMove(e) {
            if (!this.isDragging) return;

            const clientX = e.type.startsWith('touch') ? e.touches[0].clientX : e.clientX;
            const clientY = e.type.startsWith('touch') ? e.touches[0].clientY : e.clientY;

            const deltaX = clientX - this.touchStartX;
            const deltaY = clientY - this.touchStartY;

            // Allow vertical scrolling if user scrolls vertically
            if (Math.abs(deltaY) > Math.abs(deltaX) && Math.abs(deltaX) < 10) {
                return;
            }

            if (Math.abs(deltaX) > 10) {
                this.hasMoved = true;
                this.preventClick = true;
                this.list.classList.add('dragging');
                if (e.cancelable) {
                    e.preventDefault();
                }
            }

            this.touchCurrentX = clientX;
            this.dragDistance = deltaX;

            if (!this.options.fade && this.hasMoved) {
                const currentOffset = this._getTrackPosition(this.currentIndex);
                const targetOffset = currentOffset + deltaX;
                this.track.style.transition = 'none';
                this.track.style.transform = `translate3d(${targetOffset}px, 0, 0)`;
            }
        }

        _handlePointerUp() {
            if (!this.isDragging) return;
            this.isDragging = false;
            this.list.classList.remove('dragging');

            const wasMoved = this.hasMoved;
            const distance = this.dragDistance;
            this.dragDistance = 0;
            this.hasMoved = false;

            if (wasMoved && Math.abs(distance) >= this.options.swipeThreshold) {
                if (distance < 0) {
                    this.next();
                } else {
                    this.prev();
                }
            } else if (wasMoved) {
                // Dragged slightly below threshold - snap back to current slide
                this.goTo(this.currentIndex);
            } else {
                // Pure click without movement - restore transform without trigger animation
                if (!this.options.fade) {
                    const currentOffset = this._getTrackPosition(this.currentIndex);
                    this.track.style.transform = `translate3d(${currentOffset}px, 0, 0)`;
                }
            }

            if (this.options.autoplay && !this.isHovered) {
                this.startAutoplay();
            }
        }

        _handlePointerCancel() {
            if (this.isDragging) {
                this.isDragging = false;
                this.list.classList.remove('dragging');
                this.goTo(this.currentIndex, true);
                this.dragDistance = 0;
                this.hasMoved = false;
            }
        }

        _getTrackPosition(slideIndex) {
            const actualIndex = slideIndex + this.clonesCount;
            return -(actualIndex * this.slideWidth);
        }

        goTo(index, immediate = false) {
            if (this.isAnimating && !immediate) return;

            let targetIndex = index;

            if (!this.options.infinite) {
                const maxIndex = Math.max(0, this.totalSlides - this.options.slidesToShow);
                targetIndex = Math.max(0, Math.min(targetIndex, maxIndex));
            }

            this.currentIndex = targetIndex;

            if (this.options.fade) {
                this._renderFade(targetIndex, immediate);
                return;
            }

            const targetPos = this._getTrackPosition(targetIndex);

            if (immediate) {
                this.isAnimating = false;
                this.track.style.transition = 'none';
                this.track.style.transform = `translate3d(${targetPos}px, 0, 0)`;
                this._updateActiveClasses();
                return;
            }

            this.isAnimating = true;
            this.track.style.transition = `transform ${this.options.speed}ms ${this.options.cssEase}`;
            this.track.style.transform = `translate3d(${targetPos}px, 0, 0)`;

            this._updateActiveClasses();

            let finished = false;
            const finish = () => {
                if (finished) return;
                finished = true;
                clearTimeout(safetyTimeout);
                this.track.removeEventListener('transitionend', onTransitionEnd);
                this.isAnimating = false;

                // Handle loop reset for infinite carousel
                if (this.options.infinite) {
                    let resetIndex = null;
                    if (this.currentIndex >= this.totalSlides) {
                        resetIndex = this.currentIndex % this.totalSlides;
                    } else if (this.currentIndex < 0) {
                        resetIndex = (this.currentIndex % this.totalSlides + this.totalSlides) % this.totalSlides;
                    }

                    if (resetIndex !== null) {
                        this.currentIndex = resetIndex;
                        const resetPos = this._getTrackPosition(this.currentIndex);
                        this.track.style.transition = 'none';
                        this.track.style.transform = `translate3d(${resetPos}px, 0, 0)`;
                        this._updateActiveClasses();
                    }
                }
            };

            const onTransitionEnd = (e) => {
                if (e && e.target !== this.track) return;
                finish();
            };

            const safetyTimeout = setTimeout(finish, this.options.speed + 50);
            this.track.addEventListener('transitionend', onTransitionEnd);
        }

        _renderFade(targetIndex, immediate) {
            const normIndex = (targetIndex % this.totalSlides + this.totalSlides) % this.totalSlides;
            this.currentIndex = normIndex;

            this.originalSlides.forEach((slide, idx) => {
                if (idx === normIndex) {
                    slide.classList.add('sed-slider-active');
                    slide.style.opacity = '1';
                    slide.style.zIndex = '2';
                } else {
                    slide.classList.remove('sed-slider-active');
                    slide.style.opacity = '0';
                    slide.style.zIndex = '1';
                }
            });

            this._updateActiveClasses();
        }

        _updateActiveClasses() {
            const normIndex = (this.currentIndex % this.totalSlides + this.totalSlides) % this.totalSlides;

            // Update original slides active states
            this.originalSlides.forEach((slide, idx) => {
                const isActive = (idx >= normIndex && idx < normIndex + this.options.slidesToShow) ||
                                (normIndex + this.options.slidesToShow > this.totalSlides && idx < (normIndex + this.options.slidesToShow) % this.totalSlides);

                if (isActive) {
                    slide.classList.add('sed-slider-active');
                } else {
                    slide.classList.remove('sed-slider-active');
                }

                if (idx === normIndex) {
                    slide.classList.add('sed-slider-current');
                } else {
                    slide.classList.remove('sed-slider-current');
                }
            });

            // Update dots
            if (this.dotsContainer) {
                const dots = this.dotsContainer.querySelectorAll('li');
                const activeDotIdx = Math.floor(normIndex / this.options.slidesToScroll);
                dots.forEach((dot, idx) => {
                    if (idx === activeDotIdx) {
                        dot.classList.add('sed-slider-active');
                    } else {
                        dot.classList.remove('sed-slider-active');
                    }
                });
            }

            // Update arrow disabled states for non-infinite
            if (!this.options.infinite) {
                const maxIndex = Math.max(0, this.totalSlides - this.options.slidesToShow);
                if (this.prevBtn) {
                    this.prevBtn.classList.toggle('sed-slider-disabled', this.currentIndex <= 0);
                }
                if (this.nextBtn) {
                    this.nextBtn.classList.toggle('sed-slider-disabled', this.currentIndex >= maxIndex);
                }
            }
        }

        next() {
            this.goTo(this.currentIndex + this.options.slidesToScroll);
        }

        prev() {
            this.goTo(this.currentIndex - this.options.slidesToScroll);
        }

        startAutoplay() {
            this.stopAutoplay();
            this.autoplayTimer = setInterval(() => {
                if (!this.isHovered) {
                    this.next();
                }
            }, this.options.autoplaySpeed);
        }

        stopAutoplay() {
            if (this.autoplayTimer) {
                clearInterval(this.autoplayTimer);
                this.autoplayTimer = null;
            }
        }

        _debounce(fn, delay) {
            let timer = null;
            return function (...args) {
                if (timer) clearTimeout(timer);
                timer = setTimeout(() => fn.apply(this, args), delay);
            };
        }

        destroy() {
            this.stopAutoplay();
            window.removeEventListener('resize', this._onResize);

            if (this.element) {
                this.element._sedSlider = null;
                this.element.classList.remove('sed-slider', 'sed-slider-initialized', 'sed-slider-fade');

                if (this.prevBtn) this.prevBtn.remove();
                if (this.nextBtn) this.nextBtn.remove();
                if (this.dotsContainer) this.dotsContainer.remove();

                if (this.originalSlides && this.list) {
                    this.originalSlides.forEach(slide => {
                        slide.classList.remove('sed-slider-slide', 'sed-slider-active', 'sed-slider-current');
                        slide.removeAttribute('data-sed-index');
                        slide.style.width = '';
                        this.element.appendChild(slide);
                    });
                    this.list.remove();
                }
            }
        }
    }

    // Expose globally
    global.SedSlider = SedSlider;
    global.sedSlider = function (selector, options) {
        if (typeof selector === 'string') {
            const elements = document.querySelectorAll(selector);
            const instances = [];
            elements.forEach(el => instances.push(new SedSlider(el, options)));
            return instances.length === 1 ? instances[0] : instances;
        } else if (selector instanceof HTMLElement) {
            return new SedSlider(selector, options);
        }
    };

})(typeof window !== 'undefined' ? window : this);
