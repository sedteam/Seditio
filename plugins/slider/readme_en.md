# SedSlider — Native Vanilla JS Slider & Carousel

[English](readme_en.md) | [Русский](readme_ru.md) | [Türkçe](readme_tr.md)

**SedSlider** is a fast, lightweight, and zero-dependency Vanilla JavaScript (ES6+) slider and carousel engine (no jQuery required).

---

## 🚀 Quick Start

### 1. Styles and Script Inclusion

When the `slider` plugin is activated in Seditio, CSS and JavaScript files are automatically loaded in the header via the `header.first` hook:
```html
<link rel="stylesheet" href="plugins/slider/css/slider.css" />
<script src="plugins/slider/js/slider.js"></script>
```

### 2. HTML Markup

To build a slider, you only need a parent container with child items (slides). The internal track structure (`sed-slider-list`, `sed-slider-track`) is created automatically:

```html
<div class="my-slider">
    <div class="slide-item">
        <img src="path/to/image1.jpg" alt="Slide 1" />
        <div class="slide-caption">Slide Title 1</div>
    </div>
    <div class="slide-item">
        <img src="path/to/image2.jpg" alt="Slide 2" />
        <div class="slide-caption">Slide Title 2</div>
    </div>
    <div class="slide-item">
        <img src="path/to/image3.jpg" alt="Slide 3" />
        <div class="slide-caption">Slide Title 3</div>
    </div>
</div>
```

### 3. JavaScript Initialization

You can initialize the slider in two ways:

#### Method 1: Via the `sedSlider()` Helper Function
```javascript
document.addEventListener('DOMContentLoaded', function () {
    sedSlider('.my-slider', {
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 4000,
        arrows: true,
        dots: true
    });
});
```

#### Method 2: Via the `new SedSlider()` Class Constructor
```javascript
const sliderEl = document.querySelector('.my-slider');
const sliderInstance = new SedSlider(sliderEl, {
    slidesToShow: 3,
    slidesToScroll: 1,
    infinite: true,
    responsive: [
        {
            breakpoint: 768,
            settings: {
                slidesToShow: 1
            }
        }
    ]
});
```

---

## ⚙️ Configuration Options

| Option | Type | Default | Description |
| :--- | :--- | :--- | :--- |
| `slidesToShow` | `number` | `1` | Number of slides visible simultaneously |
| `slidesToScroll` | `number` | `1` | Number of slides to scroll per step |
| `speed` | `number` | `500` | Transition speed in milliseconds |
| `autoplay` | `boolean` | `false` | Enable automatic slide cycling |
| `autoplaySpeed` | `number` | `5000` | Autoplay interval in milliseconds |
| `pauseOnHover` | `boolean` | `true` | Pause autoplay when mouse hovers over slider |
| `infinite` | `boolean` | `true` | Infinite loop sliding |
| `arrows` | `boolean` | `true` | Show Prev/Next navigation arrows |
| `dots` | `boolean` | `false` | Show pagination navigation dots |
| `fade` | `boolean` | `false` | Cross-fade transition effect instead of sliding (`slidesToShow: 1` required) |
| `cssEase` | `string` | `'ease'` | CSS transition timing function (`ease`, `linear`, `cubic-bezier(...)`) |
| `appendArrows` | `string\|Element` | `null` | Target container selector or DOM element to append arrows to |
| `appendDots` | `string\|Element` | `null` | Target container selector or DOM element to append pagination dots to |
| `prevArrow` | `string` | `<button ...>Previous</button>` | HTML string for Previous button |
| `nextArrow` | `string` | `<button ...>Next</button>` | HTML string for Next button |
| `swipe` | `boolean` | `true` | Enable touch swipe gestures and mouse drag |
| `swipeThreshold` | `number` | `50` | Minimum swipe drag distance (in px) to trigger slide change |
| `responsive` | `array` | `[]` | Array of responsive breakpoint configuration objects |

---

## 📱 Responsive Breakpoints

You can override any slider options for specific viewport widths using the `responsive` array:

```javascript
sedSlider('.carousel', {
    slidesToShow: 4,
    slidesToScroll: 1,
    arrows: true,
    dots: false,
    responsive: [
        {
            breakpoint: 1200, // Viewport <= 1200px
            settings: {
                slidesToShow: 3
            }
        },
        {
            breakpoint: 992,  // Viewport <= 992px
            settings: {
                slidesToShow: 2,
                arrows: false,
                dots: true
            }
        },
        {
            breakpoint: 576,  // Viewport <= 576px
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
                arrows: false,
                dots: true
            }
        }
    ]
});
```

---

## 🕹 JavaScript API & Methods

When you store an instance of `SedSlider`, you have access to the following control methods:

```javascript
const slider = new SedSlider('.my-slider', { autoplay: true });

// Move to next slide
slider.next();

// Move to previous slide
slider.prev();

// Navigate directly to slide index (0-based)
slider.goTo(2);

// Pause autoplay
slider.stopAutoplay();

// Resume / start autoplay
slider.startAutoplay();

// Destroy slider instance and restore original HTML markup
slider.destroy();
```

You can also retrieve an active slider instance directly from the DOM element:
```javascript
const element = document.querySelector('.my-slider');
const sliderInstance = element._sedSlider;
if (sliderInstance) {
    sliderInstance.next();
}
```

---

## 🎨 CSS Classes & Styling Reference

Upon initialization, `SedSlider` automatically applies structured CSS classes:

| Class Name | Element | Purpose |
| :--- | :--- | :--- |
| `.sed-slider` | Root container | Main slider wrapper element |
| `.sed-slider-initialized` | Root container | Added once slider is fully initialized |
| `.sed-slider-fade` | Root container | Added when `fade: true` is enabled |
| `.sed-slider-list` | List wrapper | Inner container with `overflow: hidden` |
| `.sed-slider-track` | Slide track | Moving track translated via `transform: translate3d` |
| `.sed-slider-slide` | Slide element | Individual slide |
| `.sed-slider-active` | Slide element | Slides currently visible in viewport |
| `.sed-slider-current` | Slide element | Primary active slide in current view |
| `.sed-slider-cloned` | Slide element | Cloned elements used for seamless infinite looping |
| `.sed-slider-prev` | Button | Previous arrow button |
| `.sed-slider-next` | Button | Next arrow button |
| `.sed-slider-disabled` | Button | Disabled arrow state (when `infinite: false`) |
| `.sed-slider-dots` | `<ul>` | Navigation dots list |
| `.sed-slider-dots li.sed-slider-active` | `<li>` | Active dot indicator |

---

## 💡 Code Examples

### 1. Fullscreen Hero Banner with Smooth Cross-Fade
```javascript
sedSlider('.hero-slider', {
    slidesToShow: 1,
    fade: true,
    speed: 700,
    autoplay: true,
    autoplaySpeed: 6000,
    arrows: true,
    dots: true,
    prevArrow: '<button class="hero-arrow prev"><i class="ic-arrow-left"></i></button>',
    nextArrow: '<button class="hero-arrow next"><i class="ic-arrow-right"></i></button>'
});
```

### 2. Multi-Item Similar Pages / Product Cards Carousel
```javascript
sedSlider('.similar-pages-grid', {
    slidesToShow: 3,
    slidesToScroll: 1,
    speed: 400,
    arrows: true,
    dots: true,
    responsive: [
        {
            breakpoint: 768,
            settings: {
                slidesToShow: 1
            }
        }
    ]
});
```
