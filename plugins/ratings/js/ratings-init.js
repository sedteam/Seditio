document.addEventListener('DOMContentLoaded', function() {
	if (typeof StarRating === 'undefined') return;
	document.querySelectorAll('.ratings-star-widget').forEach(function(el) {
		var opts = {};
		if (el.dataset.ajaxUrl) {
			opts.callback = function(rating, el) {
				if (typeof sedjs === 'undefined' || !sedjs.ajax) return;
				sedjs.ajax({
					url: el.dataset.ajaxUrl,
					method: 'POST',
					data: { newrate: rating },
					dataType: 'json',
					success: function(data) {
						if (!data.ok) return;
						var instance = StarRating(el);
						if (instance) {
							instance.setRating(data.average);
							instance.setReadOnly(true);
						}
						var code = el.dataset.code || (el.closest('.rating-box') && el.closest('.rating-box').id ? el.closest('.rating-box').id.replace('rat-', '') : '');
						if (code) {
							var span = document.querySelector('.ratings-count-value[data-ratings-code="' + code + '"]');
							if (span) span.textContent = (data.average !== undefined) ? '(' + Number(data.average).toFixed(2) + ')' : span.textContent;
						}
					}
				});
			};
		} else if (el.dataset.inputId) {
			opts.callback = function(rating, el) {
				var inp = document.getElementById(el.dataset.inputId);
				if (inp) inp.value = rating;
			};
		}
		var parent = el.closest('[data-gradient-start]');
		if (el.dataset.gradientStart && el.dataset.gradientEnd) {
			opts.starGradient = { start: el.dataset.gradientStart, end: el.dataset.gradientEnd };
		} else if (parent) {
			opts.starGradient = { start: parent.dataset.gradientStart || '#FEF7CD', end: parent.dataset.gradientEnd || '#FF9511' };
		}
		if (el.dataset.labelsJson) {
			try { opts.ratingLabels = JSON.parse(el.dataset.labelsJson); } catch (e) {}
		}
		StarRating(el, opts);
	});
});
