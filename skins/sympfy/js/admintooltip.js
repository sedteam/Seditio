document.addEventListener('DOMContentLoaded', function() {
    var tooltip = document.createElement('div');
    tooltip.className = 'adm-tooltip';
    document.body.appendChild(tooltip);

    var tooltipTimeout;

    document.addEventListener('mouseover', function(event) {
        var from = event.relatedTarget || event.fromElement;
        if (!tooltip.contains(from)) {
            clearTimeout(tooltipTimeout);
        }
    });

    document.addEventListener('mouseout', function(event) {
        var to = event.relatedTarget || event.toElement;
        if (!tooltip.contains(to)) {
            tooltipTimeout = setTimeout(closeTooltip, 300);
        }
    });

    var tooltipLinks = {
        'data-page': function (id) {
            return "<a href='page/edit?id=" + id + "' class=\"admin_tooltip_edit\">" + L.pageedit + "</a>" +
                "<a href='page/add' class=\"admin_tooltip_add\">" + L.pageadd + "</a>";
        },
        'data-category': function (id) {
            return "<a href='admin/page?mn=structure&n=options&id=" + id + "&return=" + encodeURIComponent(window.location) + "' class=\"admin_tooltip_edit\">" + L.pageeditcategory + "</a>";
        },
        'data-config': function (id) {
            return "<a href='admin/config?n=edit&o=core&p=" + id + "&return=" + encodeURIComponent(window.location) + "' class=\"admin_tooltip_edit\">" + L.pageeditoption + "</a>";
        }
    };

    var elements = document.querySelectorAll('[data-page], [data-category], [data-config]');
    elements.forEach(function(element) {
        element.addEventListener('mouseover', function(event) {
            var id = element.getAttribute('data-page');
            var category = element.getAttribute('data-category');
            var config = element.getAttribute('data-config');
            if (id) {
                showTooltip(tooltipLinks['data-page'], id, element, tooltip);
            } else if (category) {
                showTooltip(tooltipLinks['data-category'], category, element, tooltip);
            } else if (config) {
                showTooltip(tooltipLinks['data-config'], config, element, tooltip);
            }
        });
    });
});

function closeTooltip() {
    var tooltip = document.querySelector('.adm-tooltip');
    tooltip.style.display = 'none';
}

function showTooltip(tooltipLink, id, element, tooltip) {
    var tooltipcontent = tooltipLink(id);

    if (tooltipcontent !== '') {
        tooltip.innerHTML = tooltipcontent;

        var flip = !(element.getBoundingClientRect().left + tooltip.offsetWidth + 25 < document.body.offsetWidth);
        var scrollY = window.scrollY || window.pageYOffset;
        var scrollX = window.scrollX || window.pageXOffset;
        var top = element.getBoundingClientRect().top + scrollY;
        var left = element.getBoundingClientRect().left + scrollX;
        
        tooltip.style.top = top + element.offsetHeight / 2 + 5 + 'px';
        tooltip.style.left = left + element.offsetWidth * 0.5 - (flip ? tooltip.offsetWidth - 40 : 0) + 'px';

        tooltip.style.display = 'block';
    } else {
        tooltip.style.display = 'none';
    }
}
