document.addEventListener('DOMContentLoaded', function() {
    var tooltip = document.createElement('div');
    tooltip.className = 'adm-tooltip';
    document.body.appendChild(tooltip);

    var tooltipTimeout;

    tooltip.addEventListener('mouseenter', function() {
        clearTimeout(tooltipTimeout);
    });

    tooltip.addEventListener('mouseleave', function() {
        tooltipTimeout = setTimeout(closeTooltip, 300);
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
        },
        'data-translate': function (id) {
            return "<a href='admin/translations?s=list&q=" + encodeURIComponent(id) + "&return=" + encodeURIComponent(window.location) + "' class=\"admin_tooltip_edit\">" + L.translateedit + "</a>";
        }
    };

    function findTooltipHost(el) {
        while (el && el.nodeType === 1) {
            var val = el.getAttribute('data-page') || 
                      el.getAttribute('data-category') || 
                      el.getAttribute('data-config') || 
                      el.getAttribute('data-translate');
            if (val && String(val).trim() !== '') {
                return el;
            }
            el = el.parentNode;
        }
        return null;
    }

    document.addEventListener('mouseover', function(event) {
        var from = event.relatedTarget || event.fromElement;
        if (!tooltip.contains(from)) {
            clearTimeout(tooltipTimeout);
        }

        var t = event.target;
        if (t.nodeType === 3) {
            t = t.parentNode;
        }
        var element = findTooltipHost(t);
        if (!element) {
            return;
        }
        if (from && element.contains && element.contains(from)) {
            return;
        }

        var id = element.getAttribute('data-page');
        var category = element.getAttribute('data-category');
        var config = element.getAttribute('data-config');
        var translate = element.getAttribute('data-translate');

        if (id) {
            showTooltip(tooltipLinks['data-page'], id, element, tooltip);
        } else if (category) {
            showTooltip(tooltipLinks['data-category'], category, element, tooltip);
        } else if (config) {
            showTooltip(tooltipLinks['data-config'], config, element, tooltip);
        } else if (translate) {
            showTooltip(tooltipLinks['data-translate'], translate, element, tooltip);
        }
    });
});

function closeTooltip() {
    var tooltip = document.querySelector('.adm-tooltip');
    if (tooltip) {
        tooltip.style.display = 'none';
    }
}

function showTooltip(tooltipLink, id, element, tooltip) {
    var tooltipcontent = tooltipLink(id);

    if (tooltipcontent !== '') {
        tooltip.innerHTML = tooltipcontent;
        tooltip.style.visibility = 'hidden';
        tooltip.style.display = 'block';

        var scrollY = window.scrollY || window.pageYOffset;
        var scrollX = window.scrollX || window.pageXOffset;
        var rect = element.getBoundingClientRect();
        var top = rect.top + scrollY;
        var left = rect.left + scrollX;
        
        var flip = !(rect.left + tooltip.offsetWidth + 25 < document.body.offsetWidth);
        
        tooltip.style.top = (top + rect.height / 2 + 5) + 'px';
        tooltip.style.left = (left + rect.width * 0.5 - (flip ? tooltip.offsetWidth - 40 : 0)) + 'px';
        tooltip.style.visibility = 'visible';
    } else {
        tooltip.style.display = 'none';
    }
}
