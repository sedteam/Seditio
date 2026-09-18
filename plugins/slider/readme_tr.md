# SedSlider — Saf JavaScript (Vanilla JS) Slider & Karusel

[English](readme_en.md) | [Русский](readme_ru.md) | [Türkçe](readme_tr.md)

**SedSlider**, hiçbir harici kütüphaneye (jQuery vb.) ihtiyaç duymayan, modern ve saf JavaScript (ES6+) ile geliştirilmiş hızlı ve hafif bir slider ve karusel motorudur.

---

## 🚀 Hızlı Başlangıç

### 1. Stil ve Script Bağlantısı

Seditio'da `slider` eklentisi aktif olduğunda, CSS ve JS dosyaları `header.first` kancası (hook) üzerinden sayfa başlığına otomatik olarak eklenir:
```html
<link rel="stylesheet" href="plugins/slider/css/slider.css" />
<script src="plugins/slider/js/slider.js"></script>
```

### 2. HTML Yapısı

Bir slider oluşturmak için yalnızca slayt elemanlarını içeren bir kapsayıcı (container) yeterlidir. İç ray ve liste yapısı (`sed-slider-list`, `sed-slider-track`) JavaScript tarafından otomatik olarak oluşturulur:

```html
<div class="my-slider">
    <div class="slide-item">
        <img src="path/to/image1.jpg" alt="Slide 1" />
        <div class="slide-caption">Başlık 1</div>
    </div>
    <div class="slide-item">
        <img src="path/to/image2.jpg" alt="Slide 2" />
        <div class="slide-caption">Başlık 2</div>
    </div>
    <div class="slide-item">
        <img src="path/to/image3.jpg" alt="Slide 3" />
        <div class="slide-caption">Başlık 3</div>
    </div>
</div>
```

### 3. JavaScript ile Başlatma

Slider iki farklı şekilde başlatılabilir:

#### Yöntem 1: Yardımcı `sedSlider()` Fonksiyonu ile
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

#### Yöntem 2: `new SedSlider()` Sınıfı ile
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

## ⚙️ Yapılandırma Seçenekleri ve Parametreler

| Seçenek | Tür | Varsayılan | Açıklama |
| :--- | :--- | :--- | :--- |
| `slidesToShow` | `number` | `1` | Aynı anda ekranda gösterilecek slayt sayısı |
| `slidesToScroll` | `number` | `1` | Her adımda kaydırılacak slayt sayısı |
| `speed` | `number` | `500` | Geçiş animasyonunun milisaniye cinsinden süresi |
| `autoplay` | `boolean` | `false` | Otomatik kaydırmayı etkinleştirir |
| `autoplaySpeed` | `number` | `5000` | Otomatik kaydırma aralığı (milisaniye) |
| `pauseOnHover` | `boolean` | `true` | Fare üzerine geldiğinde otomatik oynatmayı duraklatır |
| `infinite` | `boolean` | `true` | Sonsuz döngü kaydırma (Loop) |
| `arrows` | `boolean` | `true` | İleri / Geri gezinme oklarını gösterir |
| `dots` | `boolean` | `false` | Sayfalama noktalarını (pagination dots) gösterir |
| `fade` | `boolean` | `false` | Kaydırma yerine kararma/çözünme (Cross-Fade) efekti (`slidesToShow: 1` gerektirir) |
| `cssEase` | `string` | `'ease'` | CSS geçiş yumuşatma fonksiyonu (`ease`, `linear`, `cubic-bezier(...)`) |
| `appendArrows` | `string\|Element` | `null` | Okların ekleneceği özel hedef seçici veya DOM öğesi |
| `appendDots` | `string\|Element` | `null` | Noktaların ekleneceği özel hedef seçici veya DOM öğesi |
| `prevArrow` | `string` | `<button ...>Previous</button>` | «Önceki» butonu için HTML şablonu |
| `nextArrow` | `string` | `<button ...>Next</button>` | «Sonraki» butonu için HTML şablonu |
| `swipe` | `boolean` | `true` | Dokunmatik ekranlarda kaydırma ve fareyle sürükleme desteği |
| `swipeThreshold` | `number` | `50` | Slayt geçişi için gereken minimum sürükleme mesafesi (px) |
| `responsive` | `array` | `[]` | Farklı ekran genişlikleri için kırılma noktası ayarları |

---

## 📱 Duyarlı Kırılma Noktaları (Responsive Breakpoints)

Farklı ekran boyutları için `responsive` dizisini kullanarak ayarları geçersiz kılabilirsiniz:

```javascript
sedSlider('.carousel', {
    slidesToShow: 4,
    slidesToScroll: 1,
    arrows: true,
    dots: false,
    responsive: [
        {
            breakpoint: 1200, // Ekran <= 1200px
            settings: {
                slidesToShow: 3
            }
        },
        {
            breakpoint: 992,  // Ekran <= 992px
            settings: {
                slidesToShow: 2,
                arrows: false,
                dots: true
            }
        },
        {
            breakpoint: 576,  // Ekran <= 576px
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

## 🕹 JavaScript API ve Metodlar

Oluşturulan slider örneği üzerinde aşağıdaki kontrol yöntemlerini kullanabilirsiniz:

```javascript
const slider = new SedSlider('.my-slider', { autoplay: true });

// Sonraki slayta geç
slider.next();

// Önceki slayta geç
slider.prev();

// Belirtilen indeksteki slayta git (0'dan başlar)
slider.goTo(2);

// Otomatik oynatmayı durdur
slider.stopAutoplay();

// Otomatik oynatmayı yeniden başlat
slider.startAutoplay();

// Slider'ı tamamen kaldırıp orijinal HTML yapısına geri döndür
slider.destroy();
```

Ayrıca başlatılmış bir örneğe doğrudan DOM elemanı üzerinden erişebilirsiniz:
```javascript
const element = document.querySelector('.my-slider');
const sliderInstance = element._sedSlider;
if (sliderInstance) {
    sliderInstance.next();
}
```

---

## 🎨 CSS Sınıfları ve Stil Rehberi

Başlatıldığında, `SedSlider` aşağıdaki CSS sınıflarını otomatik olarak uygular:

| Sınıf Adı | Hedef Öğe | Görevi / Açıklaması |
| :--- | :--- | :--- |
| `.sed-slider` | Kök eleman | Ana slider kapsayıcısı |
| `.sed-slider-initialized` | Kök eleman | Başlatma tamamlandığında eklenir |
| `.sed-slider-fade` | Kök eleman | `fade: true` modu etkinleştirildiğinde eklenir |
| `.sed-slider-list` | Liste kapsayıcısı | `overflow: hidden` içeren iç kapsayıcı |
| `.sed-slider-track` | Ray elemanı | `transform: translate3d` ile kaydırılan ana ray |
| `.sed-slider-slide` | Slayt elemanı | Tekil slayt öğesi |
| `.sed-slider-active` | Slayt elemanı | Görünür alanda bulunan aktif slaytlar |
| `.sed-slider-current` | Slayt elemanı | Geçerli gruptaki ilk aktif slayt |
| `.sed-slider-cloned` | Slayt elemanı | Sonsuz döngü (infinite loop) için klonlanan slaytlar |
| `.sed-slider-prev` | Buton | «Önceki» gezinme butonu |
| `.sed-slider-next` | Buton | «Sonraki» gezinme butonu |
| `.sed-slider-disabled` | Buton | Devre dışı buton durumu (`infinite: false` iken) |
| `.sed-slider-dots` | `<ul>` | Gezinme noktaları listesi |
| `.sed-slider-dots li.sed-slider-active` | `<li>` | Geçerli aktif nokta göstergesi |

---

## 💡 Kullanım Örnekleri

### 1. Yumuşak Cross-Fade Geçişli Hero Banner
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

### 2. Benzer Sayfalar / Ürün Kartları Çoklu Karuseli
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
