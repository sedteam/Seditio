/*
 *  StarRatingSvg - Native JavaScript
 *
 *  https://github.com/avego/star-rating-svg-vanilla
 *  Copyright (c) Alexander Tishov
 *  Website: https://avego.org
 *  E-mail: info@avego.org
 *
 *  Original developer: Ignacio Chavez (hello@ignaciochavez.com)
 *  https://github.com/nashio/star-rating-svg
 *
 *  Licensed under MIT
 */

;(function (window, document) {
  'use strict';

  var noop = function () {};
  var defaults = {
    totalStars: 5,
    useFullStars: false,
    starShape: 'straight',
    emptyColor: 'lightgray',
    hoverColor: 'orange',
    activeColor: 'gold',
    ratedColor: 'crimson',
    useGradient: true,
    readOnly: false,
    disableAfterRate: true,
    baseUrl: false,
    starGradient: {
      start: '#FEF7CD',
      end: '#FF9511'
    },
    strokeWidth: 4,
    strokeColor: 'black',
    initialRating: 0,
    starSize: 40,
    valueMultiplier: 1,
    ratingLabels: null,
    callback: noop,
    onHover: noop,
    onLeave: noop
  };

  var instances = new WeakMap();

  var DATA_ATTR_MAP = {
    totalStars: 'number',
    valueMultiplier: 'number',
    initialRating: 'number',
    disableAfterRate: 'boolean',
    readOnly: 'boolean',
    useFullStars: 'boolean',
    starSize: 'number',
    minRating: 'number',
    starShape: 'string',
    strokeWidth: 'number',
    strokeColor: 'string',
    emptyColor: 'string',
    hoverColor: 'string',
    activeColor: 'string',
    ratedColor: 'string',
    useGradient: 'boolean',
    forceRoundUp: 'boolean'
  };

  function parseDataValue(value, type) {
    if (value === undefined || value === null || value === '') return undefined;
    if (type === 'number') {
      var n = parseFloat(value);
      return isNaN(n) ? undefined : n;
    }
    if (type === 'boolean') {
      return value === 'true' || value === '1';
    }
    return String(value);
  }

  function getDataAttributes(element) {
    var ds = element.dataset || {};
    var opts = {};
    var key;

    for (key in DATA_ATTR_MAP) {
      var val = ds[key];
      if (val !== undefined) {
        opts[key] = parseDataValue(val, DATA_ATTR_MAP[key]);
      }
    }
    if (ds.rating !== undefined && opts.initialRating === undefined) {
      opts.initialRating = parseDataValue(ds.rating, 'number');
    }

    return opts;
  }

  function StarRatingPlugin(element, options) {
    var _rating;
    var newRating;
    var roundFn;

    this.element = element;
    this.settings = Object.assign({}, defaults, getDataAttributes(element), options || {});

    _rating = this.settings.initialRating;
    _rating = parseFloat(_rating) || 0;

    var mult = this.settings.valueMultiplier;
    var internalRating = (mult !== 1) ? (_rating / mult) : _rating;

    roundFn = this.settings.forceRoundUp ? Math.ceil : Math.round;
    newRating = (roundFn(internalRating * 2) / 2).toFixed(1);
    this._state = {
      rating: parseFloat(newRating)
    };

    this._uid = Math.floor(Math.random() * 999);

    if (!options.starGradient && !this.settings.useGradient) {
      this.settings.starGradient = {
        start: this.settings.activeColor,
        end: this.settings.activeColor
      };
    }

    this._defaults = defaults;
    this._boundHandlers = {};
    this.init();
  }

  StarRatingPlugin.prototype.init = function () {
    this.renderMarkup();
    this.stars = Array.from(this.element.querySelectorAll('.jq-star'));
    this.addListeners();
    this.initRating();
    this.setPolygonTitles();
  };

  StarRatingPlugin.prototype.addListeners = function () {
    if (this.settings.readOnly) return;

    this._boundHandlers.hover = this.hoverRating.bind(this);
    this._boundHandlers.restore = this.restoreState.bind(this);
    this._boundHandlers.click = this.handleRating.bind(this);

    this.stars.forEach(function (star) {
      star.addEventListener('mouseover', this._boundHandlers.hover);
      star.addEventListener('mouseout', this._boundHandlers.restore);
      star.addEventListener('click', this._boundHandlers.click);
    }, this);
  };

  StarRatingPlugin.prototype.removeListeners = function () {
    this.stars.forEach(function (star) {
      star.removeEventListener('mouseover', this._boundHandlers.hover);
      star.removeEventListener('mouseout', this._boundHandlers.restore);
      star.removeEventListener('click', this._boundHandlers.click);
    }, this);
  };

  StarRatingPlugin.prototype.hoverRating = function (e) {
    var index = this.getIndex(e);
    this.paintStars(index, 'hovered');
    var mult = this.settings.valueMultiplier;
    var publicIndex = (index + 1) * mult;
    var publicRating = this._state.rating * mult;
    this.settings.onHover(publicIndex, publicRating, this.element);
  };

  StarRatingPlugin.prototype.handleRating = function (e) {
    var index = this.getIndex(e);
    var rating = index + 1;

    this.applyRating(rating);
    this.executeCallback(rating);

    if (this.settings.disableAfterRate) {
      this.removeListeners();
    }
  };

  StarRatingPlugin.prototype.applyRating = function (rating) {
    var index = rating - 1;
    this.paintStars(index, 'rated');
    this._state.rating = rating;
    this._state.rated = true;
  };

  StarRatingPlugin.prototype.restoreState = function (e) {
    var index = this.getIndex(e);
    var rating = this._state.rating || -1;
    var colorType = this._state.rated ? 'rated' : 'active';
    this.paintStars(rating - 1, colorType);
    var mult = this.settings.valueMultiplier;
    var publicIndex = (index + 1) * mult;
    var publicRating = this._state.rating * mult;
    this.settings.onLeave(publicIndex, publicRating, this.element);
  };

  StarRatingPlugin.prototype.getIndex = function (e) {
    var target = e.currentTarget;
    var rect = target.getBoundingClientRect();
    var width = rect.width;
    var clickedEl = e.target;
    var side = clickedEl.getAttribute ? clickedEl.getAttribute('data-side') : null;
    var minRating = this.settings.minRating;

    side = !side ? this.getOffsetByPixel(e, target, width) : side;
    side = this.settings.useFullStars ? 'right' : side;

    var starIndex = this.stars.indexOf(target);
    var index = starIndex - (side === 'left' ? 0.5 : 0);

    var offsetX = e.clientX - rect.left;
    index = (index < 0.5 && (offsetX < width / 4)) ? -1 : index;

    var mult = this.settings.valueMultiplier;
    var internalMin = minRating != null ? (minRating / mult) : null;
    var publicMax = this.settings.totalStars * mult;
    var minOk = internalMin != null && minRating <= publicMax && index < internalMin;
    index = minOk ? (internalMin - 1) : index;
    return index;
  };

  StarRatingPlugin.prototype.getOffsetByPixel = function (e, target, width) {
    var rect = target.getBoundingClientRect();
    var leftX = (e.pageX !== undefined ? e.pageX : e.clientX + window.scrollX) - (rect.left + window.scrollX);
    return (leftX <= (width / 2) && !this.settings.useFullStars) ? 'left' : 'right';
  };

  StarRatingPlugin.prototype.initRating = function () {
    this.paintStars(this._state.rating - 1, 'active');
  };

  StarRatingPlugin.prototype.setPolygonTitle = function (polygon, label) {
    if (!polygon || label == null) return;
    polygon.removeAttribute('title');
    var existing = polygon.querySelector('title');
    if (existing) polygon.removeChild(existing);
    var titleEl = document.createElementNS('http://www.w3.org/2000/svg', 'title');
    titleEl.textContent = label;
    polygon.appendChild(titleEl);
  };

  StarRatingPlugin.prototype.setPolygonTitles = function () {
    var labels = this.settings.ratingLabels;
    if (!labels || !Array.isArray(labels) || labels.length === 0) return;

    var mult = this.settings.valueMultiplier;
    var useFull = this.settings.useFullStars;

    this.stars.forEach(function (star, starIndex) {
      var polygonLeft = star.querySelector('[data-side="left"]');
      var polygonRight = star.querySelector('[data-side="right"]');

      if (useFull) {
        var fullRating = (starIndex + 1) * mult;
        var label = labels[fullRating - 1];
        if (label != null) {
          this.setPolygonTitle(polygonLeft, label);
          this.setPolygonTitle(polygonRight, label);
        }
      } else {
        var leftRating = (starIndex + 0.5) * mult;
        var rightRating = (starIndex + 1) * mult;
        var leftLabel = labels[Math.round(leftRating) - 1];
        var rightLabel = labels[Math.round(rightRating) - 1];
        this.setPolygonTitle(polygonLeft, leftLabel);
        this.setPolygonTitle(polygonRight, rightLabel);
      }
    }, this);
  };

  StarRatingPlugin.prototype.paintStars = function (endIndex, stateClass) {
    var s = this.settings;
    var self = this;

    this.stars.forEach(function (star, index) {
      var polygonLeft = star.querySelector('[data-side="left"]');
      var polygonRight = star.querySelector('[data-side="right"]');
      var leftClass = (index <= endIndex) ? stateClass : 'empty';
      var rightClass = (index <= endIndex) ? stateClass : 'empty';

      leftClass = (index - endIndex === 0.5) ? stateClass : leftClass;

      if (polygonLeft) {
        polygonLeft.setAttribute('class', 'svg-' + leftClass + '-' + self._uid);
      }
      if (polygonRight) {
        polygonRight.setAttribute('class', 'svg-' + rightClass + '-' + self._uid);
      }

      var ratedColorsIndex = endIndex >= 0 ? Math.ceil(endIndex) : 0;
      var ratedColor;
      if (s.ratedColors && s.ratedColors.length && s.ratedColors[ratedColorsIndex]) {
        ratedColor = s.ratedColors[ratedColorsIndex];
      } else {
        ratedColor = self._defaults.ratedColor;
      }

      if (stateClass === 'rated' && endIndex > -1) {
        if (index <= Math.ceil(endIndex) || (index < 1 && endIndex < 0)) {
          if (polygonLeft) polygonLeft.setAttribute('style', 'fill:' + ratedColor);
        }
        if (index <= endIndex) {
          if (polygonRight) polygonRight.setAttribute('style', 'fill:' + ratedColor);
        }
      }
    });
  };

  StarRatingPlugin.prototype.renderMarkup = function () {
    var s = this.settings;
    var baseUrl = s.baseUrl ? location.href.split('#')[0] : '';

    var star = '<div class="jq-star" style="width:' + s.starSize + 'px; height:' + s.starSize + 'px;"><svg version="1.0" class="jq-star-svg" shape-rendering="geometricPrecision" xmlns="http://www.w3.org/2000/svg" ' + this.getSvgDimensions(s.starShape) + ' stroke-width:' + s.strokeWidth + 'px;" xml:space="preserve"><style type="text/css">.svg-empty-' + this._uid + '{fill:url(' + baseUrl + '#' + this._uid + '_SVGID_1_);}.svg-hovered-' + this._uid + '{fill:url(' + baseUrl + '#' + this._uid + '_SVGID_2_);}.svg-active-' + this._uid + '{fill:url(' + baseUrl + '#' + this._uid + '_SVGID_3_);}.svg-rated-' + this._uid + '{fill:' + s.ratedColor + ';}</style>' +
      this.getLinearGradient(this._uid + '_SVGID_1_', s.emptyColor, s.emptyColor, s.starShape) +
      this.getLinearGradient(this._uid + '_SVGID_2_', s.hoverColor, s.hoverColor, s.starShape) +
      this.getLinearGradient(this._uid + '_SVGID_3_', s.starGradient.start, s.starGradient.end, s.starShape) +
      this.getVectorPath(this._uid, {
        starShape: s.starShape,
        strokeWidth: s.strokeWidth,
        strokeColor: s.strokeColor
      }) +
      '</svg></div>';

    var starsMarkup = '';
    for (var i = 0; i < s.totalStars; i++) {
      starsMarkup += star;
    }
    this.element.insertAdjacentHTML('beforeend', starsMarkup);
  };

  StarRatingPlugin.prototype.getVectorPath = function (id, attrs) {
    return (attrs.starShape === 'rounded') ?
      this.getRoundedVectorPath(id, attrs) : this.getSpikeVectorPath(id, attrs);
  };

  StarRatingPlugin.prototype.getSpikeVectorPath = function (id, attrs) {
    return '<polygon data-side="center" class="svg-empty-' + id + '" points="281.1,129.8 364,55.7 255.5,46.8 214,-59 172.5,46.8 64,55.4 146.8,129.7 121.1,241 212.9,181.1 213.9,181 306.5,241 " style="fill: transparent; stroke: ' + attrs.strokeColor + ';" />' +
      '<polygon data-side="left" class="svg-empty-' + id + '" points="281.1,129.8 364,55.7 255.5,46.8 214,-59 172.5,46.8 64,55.4 146.8,129.7 121.1,241 213.9,181.1 213.9,181 306.5,241 " style="stroke-opacity: 0;" />' +
      '<polygon data-side="right" class="svg-empty-' + id + '" points="364,55.7 255.5,46.8 214,-59 213.9,181 306.5,241 281.1,129.8 " style="stroke-opacity: 0;" />';
  };

  StarRatingPlugin.prototype.getRoundedVectorPath = function (id, attrs) {
    var fullPoints = 'M520.9,336.5c-3.8-11.8-14.2-20.5-26.5-22.2l-140.9-20.5l-63-127.7 c-5.5-11.2-16.8-18.2-29.3-18.2c-12.5,0-23.8,7-29.3,18.2l-63,127.7L28,314.2C15.7,316,5.4,324.7,1.6,336.5S1,361.3,9.9,370 l102,99.4l-24,140.3c-2.1,12.3,2.9,24.6,13,32c5.7,4.2,12.4,6.2,19.2,6.2c5.2,0,10.5-1.2,15.2-3.8l126-66.3l126,66.2 c4.8,2.6,10,3.8,15.2,3.8c6.8,0,13.5-2.1,19.2-6.2c10.1-7.3,15.1-19.7,13-32l-24-140.3l102-99.4 C521.6,361.3,524.8,348.3,520.9,336.5z';
    return '<path data-side="center" class="svg-empty-' + id + '" d="' + fullPoints + '" style="stroke: ' + attrs.strokeColor + '; fill: transparent; " /><path data-side="right" class="svg-empty-' + id + '" d="' + fullPoints + '" style="stroke-opacity: 0;" /><path data-side="left" class="svg-empty-' + id + '" d="M121,648c-7.3,0-14.1-2.2-19.8-6.4c-10.4-7.6-15.6-20.3-13.4-33l24-139.9l-101.6-99 c-9.1-8.9-12.4-22.4-8.6-34.5c3.9-12.1,14.6-21.1,27.2-23l140.4-20.4L232,164.6c5.7-11.6,17.3-18.8,30.2-16.8c0.6,0,1,0.4,1,1 v430.1c0,0.4-0.2,0.7-0.5,0.9l-126,66.3C132,646.6,126.6,648,121,648z" style="stroke: ' + attrs.strokeColor + '; stroke-opacity: 0;" />';
  };

  StarRatingPlugin.prototype.getSvgDimensions = function (starShape) {
    return (starShape === 'rounded') ? 'width="550px" height="500.2px" viewBox="0 146.8 550 500.2" style="enable-background:new 0 0 550 500.2;' : 'x="0px" y="0px" width="305px" height="305px" viewBox="60 -62 309 309" style="enable-background:new 64 -59 305 305;';
  };

  StarRatingPlugin.prototype.getLinearGradient = function (id, startColor, endColor, starShape) {
    var height = (starShape === 'rounded') ? 500 : 250;
    return '<linearGradient id="' + id + '" gradientUnits="userSpaceOnUse" x1="0" y1="-50" x2="0" y2="' + height + '"><stop offset="0" style="stop-color:' + startColor + '"/><stop offset="1" style="stop-color:' + endColor + '"/> </linearGradient>';
  };

  StarRatingPlugin.prototype.executeCallback = function (rating) {
    var publicRating = rating * this.settings.valueMultiplier;
    this.settings.callback(publicRating, this.element);
  };

  StarRatingPlugin.prototype.unload = function () {
    this.removeListeners();
    instances.delete(this.element);
    if (this.element.parentNode) {
      this.element.parentNode.removeChild(this.element);
    }
  };

  StarRatingPlugin.prototype.setRating = function (rating, round) {
    var mult = this.settings.valueMultiplier;
    var publicMax = this.settings.totalStars * mult;
    if (rating > publicMax || rating < 0) return;
    if (round) {
      rating = Math.round(rating);
    }
    var internalRating = (mult !== 1) ? (rating / mult) : rating;
    this.applyRating(internalRating);
  };

  StarRatingPlugin.prototype.getRating = function () {
    return this._state.rating * this.settings.valueMultiplier;
  };

  StarRatingPlugin.prototype.resize = function (newSize) {
    if (newSize <= 1 || newSize > 200) {
      console.error('star size out of bounds');
      return;
    }
    this.stars.forEach(function (star) {
      star.style.width = newSize + 'px';
      star.style.height = newSize + 'px';
    });
  };

  StarRatingPlugin.prototype.setReadOnly = function (flag) {
    if (flag === true) {
      this.removeListeners();
    } else {
      this.settings.readOnly = false;
      this.addListeners();
    }
  };

  function resolveElements(elementOrSelector) {
    if (typeof elementOrSelector === 'string') {
      var elements = document.querySelectorAll(elementOrSelector);
      return elements.length === 1 ? [elements[0]] : Array.from(elements);
    }
    if (elementOrSelector && elementOrSelector.nodeType === 1) {
      return [elementOrSelector];
    }
    return [];
  }

  function StarRating(elementOrSelector, options) {
    var elements = resolveElements(elementOrSelector);
    if (elements.length === 0) return null;

    var results = [];
    elements.forEach(function (el) {
      if (instances.has(el)) {
        results.push(instances.get(el));
      } else {
        var instance = new StarRatingPlugin(el, options || {});
        instances.set(el, instance);
        results.push(instance);
      }
    });

    return results.length === 1 ? results[0] : results;
  }

  if (typeof module !== 'undefined' && module.exports) {
    module.exports = StarRating;
  } else {
    window.StarRating = StarRating;
  }

})(typeof window !== 'undefined' ? window : this, typeof document !== 'undefined' ? document : null);
