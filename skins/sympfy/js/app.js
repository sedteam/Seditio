/**
 * Seditio Sympfy Skin Application Scripts
 * 100% Native Vanilla JS (ES6+)
 *
 * @version 2.0.0
 * @author Seditio Team
 * @license BSD-3-Clause
 */

(function (global) {
    'use strict';

    const seditio = {};

    // Get Scrollbar width
    seditio.getScrollbar = function () {
        return window.innerWidth - document.documentElement.clientWidth;
    };

    seditio.scrollbar = seditio.getScrollbar();

    // Smooth Scroll Helper
    seditio.scrollTo = function (anch) {
        if (!anch) return;
        const targetId = typeof anch === 'string' ? anch.replace('#', '') : '';
        const targetEl = document.getElementById(targetId) || document.querySelector(anch);
        if (targetEl) {
            const position = targetEl.getBoundingClientRect().top + window.pageYOffset - 100;
            window.scrollTo({
                top: position,
                behavior: 'smooth'
            });
        }
    };
    seditio.ScrollTo = seditio.scrollTo; // Backward compatibility

    // Top Slider Down Trigger Click
    seditio.TopsliderTrggleDown = function (id) {
        const triggers = document.querySelectorAll('.sed-slider-down');
        triggers.forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const targetEl = document.querySelector(id);
                if (targetEl) {
                    const offset = (window.innerWidth <= 992) ? 62 : 78;
                    const position = targetEl.getBoundingClientRect().top + window.pageYOffset - offset;
                    window.scrollTo({
                        top: position,
                        behavior: 'smooth'
                    });
                }
            });
        });
    };

    // Mobile Menu Helper
    seditio.initMobileMenu = function () {
        const jsMenuEl = document.querySelector('.js-menu');
        const desktopNav = document.querySelector('.menu-wrapper .menu > ul');
        if (jsMenuEl && desktopNav && !jsMenuEl.querySelector('ul')) {
            const cloned = desktopNav.cloneNode(true);
            jsMenuEl.appendChild(cloned);
            if (typeof SedMenu !== 'undefined') {
                new SedMenu(jsMenuEl, {
                    title: true,
                    speed: 300
                });
            }
        }
    };

    // DOM Ready Initialization
    const init = function () {
        const header = document.getElementById('header');
        const navTrigger = document.querySelector('.nav-trigger');
        const mobileNav = document.querySelector('.mobile-menu');
        const sliderSection = document.getElementById('slider-section');

        // Initialize mobile menu if mobile viewport
        if ((window.innerWidth + seditio.getScrollbar()) <= 992) {
            seditio.initMobileMenu();
        }

        // Initialize slider down trigger
        if (document.querySelector('.sed-slider-down')) {
            seditio.TopsliderTrggleDown('#home');
        }

        // Window resize event
        window.addEventListener('resize', function () {
            const winWidth = window.innerWidth;
            const scrollbarWidth = seditio.getScrollbar();

            // Desktop viewport
            if ((winWidth + scrollbarWidth) > 992) {
                document.documentElement.classList.remove('disable-scrolling');
                if (mobileNav) mobileNav.classList.remove('nav-is-visible');
                if (navTrigger) navTrigger.classList.remove('nav-is-visible');
            }

            // Mobile viewport
            if ((winWidth + scrollbarWidth) <= 992) {
                seditio.initMobileMenu();
                if (mobileNav && mobileNav.classList.contains('nav-is-visible')) {
                    document.documentElement.classList.add('disable-scrolling');
                }
            }

            // Adjust top slider height
            if (sliderSection) {
                sliderSection.style.height = `${window.innerHeight}px`;
            }
        }, { passive: true });

        // Mobile Nav Trigger (toggle menu and prevent body scrolling)
        if (navTrigger && mobileNav) {
            navTrigger.addEventListener('click', function (e) {
                e.preventDefault();
                navTrigger.classList.toggle('nav-is-visible');
                mobileNav.classList.toggle('nav-is-visible');

                if (mobileNav.classList.contains('nav-is-visible')) {
                    document.documentElement.classList.add('disable-scrolling');
                } else {
                    document.documentElement.classList.remove('disable-scrolling');
                }
            });
        }

        // Top Slider Section 100% height
        if (sliderSection) {
            sliderSection.style.height = `${window.innerHeight}px`;
        }

        // Homepage Top Slider
        if (document.getElementById('slider') && typeof SedSlider !== 'undefined') {
            new SedSlider('#slider', {
                dots: true,
                infinite: true,
                autoplay: true,
                autoplaySpeed: 5000,
                speed: 500,
                arrows: true,
                appendDots: '.home-slider-dots',
                appendArrows: '.home-slider-arrows',
                cssEase: 'linear'
            });
        }

        // Page Slider
        if (typeof SedSlider !== 'undefined') {
            document.querySelectorAll('.page-slider').forEach(function (el) {
                new SedSlider(el, {
                    dots: true,
                    infinite: true,
                    autoplay: true,
                    autoplaySpeed: 5000,
                    speed: 500,
                    arrows: true,
                    appendDots: '.page-slider-dots',
                    appendArrows: '.page-slider-arrows',
                    cssEase: 'linear'
                });
            });
        }

        // Similar Carousel Slider
        if (typeof SedSlider !== 'undefined') {
            document.querySelectorAll('.similar-slider').forEach(function (el) {
                new SedSlider(el, {
                    dots: true,
                    infinite: true,
                    speed: 300,
                    slidesToShow: 4,
                    slidesToScroll: 1,
                    arrows: true,
                    appendArrows: '.similar-arrows',
                    appendDots: '.similar-dots',
                    responsive: [
                        {
                            breakpoint: 1200,
                            settings: {
                                slidesToShow: 3,
                                slidesToScroll: 1,
                                infinite: true,
                                dots: true,
                                arrows: true
                            }
                        },
                        {
                            breakpoint: 992,
                            settings: {
                                slidesToShow: 2,
                                slidesToScroll: 1,
                                infinite: true,
                                dots: false,
                                arrows: true
                            }
                        },
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1,
                                dots: false,
                                arrows: true
                            }
                        }
                    ]
                });
            });
        }

        // Comments spoiler with custom skin classes
        if (typeof sedjs !== 'undefined' && typeof sedjs.spoiler === 'function') {
            sedjs.spoiler({
                container: 'spoiler-container',
                title: 'spoiler-jump, .comments-box-title',
                content: 'spoiler-body'
            });
        }

        // Sticky Header
        if (header) {
            window.addEventListener('scroll', function () {
                if (window.pageYOffset > 100) {
                    header.classList.add('header-sticky');
                } else {
                    header.classList.remove('header-sticky');
                }
            }, { passive: true });
        }
    };

    // Attach to sedjs.ready or DOMContentLoaded
    if (typeof sedjs !== 'undefined' && typeof sedjs.ready === 'function') {
        sedjs.ready(init);
    } else if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Expose globally
    global.seditio = seditio;

})(typeof window !== 'undefined' ? window : this);