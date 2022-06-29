(function ($) {
	tooltip = $("<div class='adm-tooltip'></div>").appendTo($('body'));    
	$(document).on('mouseleave', '.adm-tooltip', function(){tooltipcanclose=true;setTimeout("close_tooltip();", 300);});
	$(document).on('mouseover', '.adm-tooltip', function(){tooltipcanclose=false;});
	$('[data-page], [data-category], [data-config]').on('mouseover', show_tooltip);	
})(jQuery);

function show_tooltip()
{
    tooltipcanclose=false;
    tooltip.show();
    $(this).on('mouseleave', function(){tooltipcanclose=true;setTimeout("close_tooltip();", 500);});

    flip = !($(this).offset().left+tooltip.width()+25 < $('body').width());

    tooltip.css('top',  $(this).outerHeight()/2 + 5 + $(this).offset().top + 'px');
    tooltip.css('left', ($(this).offset().left + $(this).outerWidth()*0.5 - (flip ? tooltip.width()-40 : 0)  + 0) + 'px');

    from = encodeURIComponent(window.location);
    tooltipcontent = '';

    if(id = $(this).attr('data-page'))
    {
        tooltipcontent = "<a href='page/edit?id="+id+"&r="+$(this).attr('data-cat')+"' class=admin_tooltip_edit>"+L.pageedit+"</a>";
        tooltipcontent += "<a href='page/add?c="+$(this).attr('data-cat')+"' class=admin_tooltip_add>"+L.pageadd+"</a>";
    }

    if(id = $(this).attr('data-category'))
    {
      tooltipcontent = "<a href='admin/page?mn=structure&n=options&id="+id+"&return="+from+"' class=admin_tooltip_edit>"+L.pageeditcategory+"</a>";
    }

    if(id = $(this).attr('data-config'))
    {
        tooltipcontent = "<a href='admin/config?n=edit&o=core&p="+id+"&return="+from+"' class=admin_tooltip_edit>"+L.pageeditoption+"</a>";
    }

    $('.adm-tooltip').html(tooltipcontent);
}

function close_tooltip()
{
    if(tooltipcanclose)
    {
        tooltipcanclose=false;
        tooltip.hide();
    }
}

function ShowTooltip(i, content) {

    tooltip = document.getElementById('adm-tooltip');

    document.getElementById('adm-tooltip').innerHTML = content;
    tooltip.style.display = 'block';

    var xleft=0;
    var xtop=0;
    o = i;

    do {
        xleft += o.offsetLeft;
        xtop  += o.offsetTop;

    } while (o=o.offsetParent);

    xwidth  = i.offsetWidth  ? i.offsetWidth  : i.style.pixelWidth;
    xheight = i.offsetHeight ? i.offsetHeight : i.style.pixelHeight;

    bwidth =  tooltip.offsetWidth  ? tooltip.offsetWidth  : tooltip.style.pixelWidth;

    w = window;

    xbody  = document.compatMode=='CSS1Compat' ? w.document.documentElement : w.document.body;
    dwidth = xbody.clientWidth  ? xbody.clientWidth   : w.innerWidth;
    bwidth = tooltip.offsetWidth ? tooltip.offsetWidth  : tooltip.style.pixelWidth;

    flip = !( 25 + xleft + bwidth < dwidth);

    tooltip.style.top  = xheight - 3 + xtop + 'px';
    tooltip.style.left = (xleft - (flip ? bwidth : 0)  + 25) + 'px';

    return false;
}