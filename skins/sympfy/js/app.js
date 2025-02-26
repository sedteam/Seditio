var seditio 				  	= [];

(function ()
{
	var t;  
		
	seditio.win                     = $(window);
	seditio.winHeight 			  	= $(window).height();
	seditio.winWidth 			  	= $(window).width();
	seditio.header 					= $('#header');
	seditio.footer 					= $('#footer');
	seditio.navTrigger 				= $('.nav-trigger');
	seditio.mobileNav 				= $('.mobile-menu');
	seditio.jsMenu 					= $('.js-menu');
	seditio.desctopNav 				= $('.menu-wrapper .menu>ul');
	seditio.SliderTriggleDown      	= $('.slick-down');
	
	// Top slider Triggle Down Click
	seditio.TopsliderTrggleDown = function(id) {
		seditio.SliderTriggleDown.on({'touchstart click': function() { 
			if ((seditio.winWidth + seditio.scrollbar) <= 992) {
				var position = $(id).offset().top-62;
			} else {
				var position = $(id).offset().top-78;
			}			
			$('html, body').animate({scrollTop:position}, 400);
		}});
	};
	
	//ScrollBar Get Width
	seditio.getScrollbar  = function() {
		if ($(document).height() > $(window).height()) {
			$('body').append('<div id="fakescrollbar" style="width:50px;height:50px;overflow:hidden;position:absolute;top:-200px;left:-200px;"></div>');
			fakeScrollBar = $('#fakescrollbar');
			fakeScrollBar.append('<div style="height:100px;">&nbsp;</div>');
			var w1 = fakeScrollBar.find('div').innerWidth();
			fakeScrollBar.css('overflow-y', 'scroll');
			var w2 = $('#fakescrollbar').find('div').html('html is required to init new width.').innerWidth();
			fakeScrollBar.remove();
			return (w1-w2);
		}
		return 0;
	};	
	
	seditio.ScrollTo  = function(anch) {					
		var position = $("#" + anch).offset().top - 100;		
		$('html, body').animate({
			scrollTop: position
		  }, 1000, 'linear');
	};

	seditio.scrollbar				= seditio.getScrollbar();

  t = function ()
  {    
		
	if ((seditio.winWidth + seditio.scrollbar) <= 992) {		
		seditio.desctopNav.clone().appendTo(seditio.jsMenu);  // clone and append menu				
		var slinky = $(seditio.jsMenu).slinky({
			title: true
		});         
	} 

	if (seditio.SliderTriggleDown.length) {
		seditio.TopsliderTrggleDown('#home');
	}	
	
	//on resize eventing    
	$(window).on('resize', function() {
		
		// it's desctop device
		if ((seditio.winWidth + seditio.scrollbar) > 992) { 
			$(document.documentElement).removeClass('disable-scrolling');
			$(seditio.mobileNav).removeClass('nav-is-visible');
			$(seditio.navTrigger).removeClass('nav-is-visible');
		}	
		
		// it's mobile device
		if ((seditio.winWidth + seditio.scrollbar) <= 992) {		  		
			if ($(".mobile-menu *").is("ul") == false) {            				
				seditio.desctopNav.clone().appendTo(seditio.jsMenu);  // clone and append menu			
				var slinky = $(seditio.jsMenu).slinky({
					title: true
				});   		
			}   		  
			if ($(seditio.mobileNav).hasClass('nav-is-visible')) {  
				$(document.documentElement).addClass('disable-scrolling');           
			} 
		}	
	});  	

	//mobile only, block scrolling
	seditio.navTrigger.on('click', function(event) {
		event.preventDefault();
		$([seditio.navTrigger, seditio.mobileNav]).toggleClass('nav-is-visible');        
		if (seditio.mobileNav.hasClass('nav-is-visible')) {  
		  $(document.documentElement).addClass('disable-scrolling');           
		}  else {
		  $(document.documentElement).removeClass('disable-scrolling');
		}
	});

	/*  tabs for recenitems */
	$('.tabs-nav a').on('click', function (event) {
		event.preventDefault();		
		$('.tabs-nav li').removeClass('active');		
		$(this).parent().addClass('active');		
		$('.tab').hide();		
		$($(this).attr('href')).show();
	});

	$('.tabs-nav a:first').trigger('click'); // Default	
		
	/*  top slider height 100% */
	$('#slider-section').css('height', seditio.winHeight);	
	$(window).on('resize', function() {
		seditio.winHeight = $(window).height();
		seditio.winWidth = $(window).width();		
		$('#slider-section').css('height', seditio.winHeight);
	});

	/*  homepage top slider */
	var slider = $('#slider').slick({
		dots: true,
		infinite: true,
		autoplay: true,
		autoplaySpeed: 5000,
		speed: 500,
		/*fade: true, */
		arrows: true,
		appendDots: '.home-slider-dots',
		appendArrows: '.home-slider-arrows',
		cssEase: 'linear' 
	});	
	
	/*  page slider */
	var page_slider = $('.page-slider').slick({
		dots: true,
		infinite: true,
		autoplay: true,
		autoplaySpeed: 5000,
		speed: 500,
		/*fade: true, */
		arrows: true,
		appendDots: '.page-slider-dots',
		appendArrows: '.page-slider-arrows',
		cssEase: 'linear' 
	});		
	
	/*  similar carousel slider */
	var similar_slider = $('.similar-slider').slick({
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

        // You can unslick at a given breakpoint now by adding:
        // settings: "unslick"
        // instead of a settings object
      ]
    }); 	
	
	/* Menu sliding */
	$('.menu-wrapper .menu ul li').hover(
		function() {
		  $('ul', this).eq(0).stop().slideDown(200);
		},
		function() {
		  $('ul', this).eq(0).stop().slideUp(200);
		}
	); 
	
	/* click on spoiler header */		
	$('.spoiler-jump').click(function(e){ 
		e.preventDefault();
		$(this).closest('.spoiler-container').toggleClass('active');		
		if ($(this).closest('.spoiler-container').hasClass('active'))
			{ $(this).closest('.spoiler-container').find('.spoiler-body').slideDown(300); }
		else 
			{ $(this).closest('.spoiler-container').find('.spoiler-body').slideUp(300);	}
	});	
	
	$('input, textarea').placeholder();
	$(".phone_field").mask("+7 (999) 999-99-99",{completed:function(){ok=1}});  

	
	/* sticky header */
	$(window).scroll(function() {
		  if ($(this).scrollTop() > 100) {
			  $('#header').addClass('header-sticky');
		  } else {
			  $('#header').removeClass('header-sticky');
		  }
	});
	
	/* user panel menu */
	$('.openuserpanel').on('click', function () {
	  $(this).closest('.userpanel').toggleClass('open');
	});
	
  },
  $(document).ready(t)
}
.call(this));