# SedSlider — Нативный слайдер и карусель на чистом JavaScript

[English](readme_en.md) | [Русский](readme_ru.md) | [Türkçe](readme_tr.md)

**SedSlider** — быстрый, легковесный и полностью автономный слайдер/карусель на чистом JavaScript (ES6+) без внешних зависимостей (jQuery не требуется).

---

## 🚀 Быстрый старт

### 1. Подключение стилей и скрипта

Если плагин `slider` активен в Seditio, стили и скрипты подключаются автоматически в заголовке страницы через хук `header.first`:
```html
<link rel="stylesheet" href="plugins/slider/css/slider.css" />
<script src="plugins/slider/js/slider.js"></script>
```

### 2. HTML-разметка

Для создания слайдера достаточно контейнера с дочерними элементами (слайдами). Вся внутренняя структура (`sed-slider-list`, `sed-slider-track`) генерируется автоматически:

```html
<div class="my-slider">
    <div class="slide-item">
        <img src="path/to/image1.jpg" alt="Slide 1" />
        <div class="slide-caption">Заголовок 1</div>
    </div>
    <div class="slide-item">
        <img src="path/to/image2.jpg" alt="Slide 2" />
        <div class="slide-caption">Заголовок 2</div>
    </div>
    <div class="slide-item">
        <img src="path/to/image3.jpg" alt="Slide 3" />
        <div class="slide-caption">Заголовок 3</div>
    </div>
</div>
```

### 3. Инициализация в JavaScript

Инициализировать слайдер можно двумя способами:

#### Способ 1: Через функцию-хелпер `sedSlider()`
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

#### Способ 2: Через класс `new SedSlider()`
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

## ⚙️ Параметры и опции конфигурации

| Параметр | Тип | По умолчанию | Описание |
| :--- | :--- | :--- | :--- |
| `slidesToShow` | `number` | `1` | Количество отображаемых слайдов одновременно |
| `slidesToScroll` | `number` | `1` | Количество слайдов, прокручиваемых за один шаг |
| `speed` | `number` | `500` | Длительность анимации перехода в миллисекундах |
| `autoplay` | `boolean` | `false` | Автоматическая прокрутка слайдов |
| `autoplaySpeed` | `number` | `5000` | Интервал автопрокрутки (в мс) |
| `pauseOnHover` | `boolean` | `true` | Приостановка автопрокрутки при наведении курсора |
| `infinite` | `boolean` | `true` | Бесконечная циклическая прокрутка (луп) |
| `arrows` | `boolean` | `true` | Отображение стрелок навигации «Вперед» / «Назад» |
| `dots` | `boolean` | `false` | Отображение навигационных точек (пагинации) |
| `fade` | `boolean` | `false` | Эффект плавного растворения (Cross-Fade) вместо скольжения (требует `slidesToShow: 1`) |
| `cssEase` | `string` | `'ease'` | CSS функция плавности перехода (`ease`, `linear`, `cubic-bezier(...)`) |
| `appendArrows` | `string\|Element` | `null` | Селектор или DOM-элемент, куда будут добавлены стрелки навигации |
| `appendDots` | `string\|Element` | `null` | Селектор или DOM-элемент, куда будут добавлены точки пагинации |
| `prevArrow` | `string` | `<button ...>Previous</button>` | HTML-разметка кнопки «Назад» |
| `nextArrow` | `string` | `<button ...>Next</button>` | HTML-разметка кнопки «Вперед» |
| `swipe` | `boolean` | `true` | Поддержка свайпов на тач-устройствах и перетаскивания мышью |
| `swipeThreshold` | `number` | `50` | Минимальная дистанция свайпа (в пикселях) для переключения |
| `responsive` | `array` | `[]` | Массив настроек для различных экранов и разрешений (брейкпоинтов) |

---

## 📱 Адаптивность (Responsive Breakpoints)

Вы можете переопределять любые опции слайдера для разных ширин экрана при помощи массива `responsive`:

```javascript
sedSlider('.carousel', {
    slidesToShow: 4,
    slidesToScroll: 1,
    arrows: true,
    dots: false,
    responsive: [
        {
            breakpoint: 1200, // Экран <= 1200px
            settings: {
                slidesToShow: 3
            }
        },
        {
            breakpoint: 992,  // Экран <= 992px
            settings: {
                slidesToShow: 2,
                arrows: false,
                dots: true
            }
        },
        {
            breakpoint: 576,  // Экран <= 576px
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

## 🕹 Методы API (Управление из JS)

Если вы сохранили инстанс слайдера, вам доступны следующие методы:

```javascript
const slider = new SedSlider('.my-slider', { autoplay: true });

// Переключить на следующий слайд
slider.next();

// Переключить на предыдущий слайд
slider.prev();

// Перейти к конкретному слайду (индекс от 0)
slider.goTo(2);

// Остановить автопрокрутку
slider.stopAutoplay();

// Возобновить / запустить автопрокрутку
slider.startAutoplay();

// Полностью уничтожить слайдер и вернуть исходный HTML
slider.destroy();
```

Также получить инстанс уже инициализированного слайдера можно прямо из DOM-элемента:
```javascript
const element = document.querySelector('.my-slider');
const sliderInstance = element._sedSlider;
if (sliderInstance) {
    sliderInstance.next();
}
```

---

## 🎨 CSS-классы и стилизация

При инициализации SedSlider автоматически создает структурированные классы:

| Класс | Элемент | Назначение |
| :--- | :--- | :--- |
| `.sed-slider` | Корневой элемент | Главный контейнер слайдера |
| `.sed-slider-initialized` | Корневой элемент | Применяется после успешной инициализации |
| `.sed-slider-fade` | Корневой элемент | Применяется при включенной опции `fade: true` |
| `.sed-slider-list` | Обертка списка | Внутренний контейнер с `overflow: hidden` |
| `.sed-slider-track` | Дорожка слайдов | Контейнер, смещаемый через `transform: translate3d` |
| `.sed-slider-slide` | Слайд | Отдельный слайд |
| `.sed-slider-active` | Слайд | Слайды, видимые в текущей области просмотра |
| `.sed-slider-current` | Слайд | Первый активный слайд в текущей группе |
| `.sed-slider-cloned` | Слайд | Клонированные слайды для обеспечения бесконечного цикла |
| `.sed-slider-prev` | Кнопка | Стрелка «Назад» |
| `.sed-slider-next` | Кнопка | Стрелка «Вперед» |
| `.sed-slider-disabled` | Стрелка | Неактивное состояние стрелки (при `infinite: false`) |
| `.sed-slider-dots` | `<ul>` | Список точек пагинации |
| `.sed-slider-dots li.sed-slider-active` | `<li>` | Активная точка текущего слайда |

---

## 💡 Примеры использования

### 1. Полноэкранный Hero-баннер с плавным Cross-Fade
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

### 2. Карусель похожих страниц / карточек товаров
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
