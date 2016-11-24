jQuery(document).ready(function(){
/* ==========================================================================
	Scroll
	========================================================================== */

	if (!("ontouchstart" in document.documentElement)) {

		document.documentElement.className += " no-touch";

		var jScrollOptions = {
			autoReinitialise: true,
			autoReinitialiseDelay: 100
		};

		jQuery('.box-typical-body').jScrollPane(jScrollOptions);
		jQuery('.side-menu').jScrollPane(jScrollOptions);
		jQuery('.side-menu-addl').jScrollPane(jScrollOptions);
		jQuery('.scrollable-block').jScrollPane(jScrollOptions);
	}

/* ==========================================================================
    Header search
    ========================================================================== */

	jQuery('.site-header .site-header-search').each(function(){
		var parent = jQuery(this),
			overlay = parent.find('.overlay');

		overlay.click(function(){
			parent.removeClass('closed');
		});

		parent.clickoutside(function(){
			if (!parent.hasClass('closed')) {
				parent.addClass('closed');
			}
		});
	});

/* ==========================================================================
    Header mobile menu
    ========================================================================== */

	// Dropdowns
	jQuery('.site-header-collapsed .dropdown').each(function(){
		var parent = jQuery(this),
			btn = parent.find('.dropdown-toggle');

		btn.click(function(){
			if (parent.hasClass('mobile-opened')) {
				parent.removeClass('mobile-opened');
			} else {
				parent.addClass('mobile-opened');
			}
		});
	});

	jQuery('.dropdown-more').each(function(){
		var parent = jQuery(this),
			more = parent.find('.dropdown-more-caption'),
			classOpen = 'opened';

		more.click(function(){
			if (parent.hasClass(classOpen)) {
				parent.removeClass(classOpen);
			} else {
				parent.addClass(classOpen);
			}
		});
	});

	// Left mobile menu
	jQuery('.hamburger').click(function(){
		if (jQuery('body').hasClass('menu-left-opened')) {
			jQuery(this).removeClass('is-active');
			jQuery('body').removeClass('menu-left-opened');
			jQuery('html').css('overflow','auto');
		} else {
			jQuery(this).addClass('is-active');
			jQuery('body').addClass('menu-left-opened');
			jQuery('html').css('overflow','hidden');
		}
	});

	jQuery('.mobile-menu-left-overlay').click(function(){
		jQuery('.hamburger').removeClass('is-active');
		jQuery('body').removeClass('menu-left-opened');
		jQuery('html').css('overflow','auto');
	});

	// Right mobile menu
	jQuery('.site-header .burger-right').click(function(){
		if (jQuery('body').hasClass('menu-right-opened')) {
			jQuery('body').removeClass('menu-right-opened');
			jQuery('html').css('overflow','auto');
		} else {
			jQuery('.hamburger').removeClass('is-active');
			jQuery('body').removeClass('menu-left-opened');
			jQuery('body').addClass('menu-right-opened');
			jQuery('html').css('overflow','hidden');
		}
	});

	jQuery('.mobile-menu-right-overlay').click(function(){
		jQuery('body').removeClass('menu-right-opened');
		jQuery('html').css('overflow','auto');
	});

/* ==========================================================================
    Header help
    ========================================================================== */

	jQuery('.help-dropdown').each(function(){
		var parent = jQuery(this),
			btn = parent.find('>button'),
			popup = parent.find('.help-dropdown-popup'),
			jscroll;

		btn.click(function(){
			if (parent.hasClass('opened')) {
				parent.removeClass('opened');
				jscroll.destroy();
			} else {
				parent.addClass('opened');

				jQuery('.help-dropdown-popup-cont, .help-dropdown-popup-side').matchHeight();

				if (!("ontouchstart" in document.documentElement)) {
					setTimeout(function(){
						jscroll = parent.find('.jscroll').jScrollPane(jScrollOptions).data().jsp;
						//jscroll.reinitialise();
					},0);
				}
			}
		});

		jQuery('html').click(function(event) {
		    if (
		        !jQuery(event.target).closest('.help-dropdown-popup').length
		        &&
		        !jQuery(event.target).closest('.help-dropdown>button').length
		        &&
		        !jQuery(event.target).is('.help-dropdown-popup')
		        &&
		        !jQuery(event.target).is('.help-dropdown>button')
		    ) {
				if (parent.hasClass('opened')) {
					parent.removeClass('opened');
					jscroll.destroy();
		        }
		    }
		});
	});

/* ==========================================================================
    Side menu list
    ========================================================================== */

	jQuery('.side-menu-list li.with-sub').each(function(){
		var parent = jQuery(this),
			clickLink = parent.find('>span'),
			subMenu = parent.find('>ul');

		clickLink.click(function() {
			if (parent.hasClass('opened')) {
				parent.removeClass('opened');
				subMenu.slideUp();
				subMenu.find('.opened').removeClass('opened');
			} else {
				if (clickLink.parents('.with-sub').size() == 1) {
					jQuery('.side-menu-list .opened').removeClass('opened').find('ul').slideUp();
				}
				parent.addClass('opened');
				subMenu.slideDown();
			}
		});
	});


/* ==========================================================================
    Dashboard
    ========================================================================== */

	jQuery(window).resize(function(){
		jQuery('body').click('click');
	});

	// Collapse box
	jQuery('.box-typical-dashboard').each(function(){
		var parent = jQuery(this),
			btnCollapse = parent.find('.action-btn-collapse');

		btnCollapse.click(function(){
			if (parent.hasClass('box-typical-collapsed')) {
				parent.removeClass('box-typical-collapsed');
			} else {
				parent.addClass('box-typical-collapsed');
			}
		});
	});

	// Full screen box
	jQuery('.box-typical-dashboard').each(function(){
		var parent = jQuery(this),
			btnExpand = parent.find('.action-btn-expand'),
			classExpand = 'box-typical-full-screen';

		btnExpand.click(function(){
			if (parent.hasClass(classExpand)) {
				parent.removeClass(classExpand);
				jQuery('html').css('overflow','auto');
			} else {
				parent.addClass(classExpand);
				jQuery('html').css('overflow','hidden');
			}
		});
	});

/* ==========================================================================
    Circle progress bar
    ========================================================================== */

	jQuery(".circle-progress-bar").asPieProgress({
		namespace: 'asPieProgress',
		speed: 500
	});

	jQuery(".circle-progress-bar").asPieProgress("start");


	jQuery(".circle-progress-bar-typical").asPieProgress({
		namespace: 'asPieProgress',
		speed: 25
	});

	jQuery(".circle-progress-bar-typical").asPieProgress("start");

/* ==========================================================================
	Select
	========================================================================== */

	if (jQuery('.bootstrap-select').size()) {
		// Bootstrap-select
		jQuery('.bootstrap-select').selectpicker({
			style: '',
			width: '100%',
			size: 8
		});
	}

	if (jQuery('.select2').size()) {
		// Select2
		//jQuery.fn.select2.defaults.set("minimumResultsForSearch", "Infinity");

		jQuery('.select2').not('.manual').select2();

		jQuery(".select2-icon").not('.manual').select2({
			templateSelection: select2Icons,
			templateResult: select2Icons
		});

		jQuery(".select2-arrow").not('.manual').select2({
			theme: "arrow"
		});

		jQuery('.select2-no-search-arrow').select2({
			minimumResultsForSearch: "Infinity",
			theme: "arrow"
		});

		jQuery('.select2-no-search-default').select2({
			minimumResultsForSearch: "Infinity"
		});

		jQuery(".select2-white").not('.manual').select2({
			theme: "white"
		});

		jQuery(".select2-photo").not('.manual').select2({
			templateSelection: select2Photos,
			templateResult: select2Photos
		});
	}

	function select2Icons (state) {
		if (!state.id) { return state.text; }
		var jQuerystate = jQuery(
			'<span class="font-icon ' + state.element.getAttribute('data-icon') + '"></span><span>' + state.text + '</span>'
		);
		return jQuerystate;
	}

	function select2Photos (state) {
		if (!state.id) { return state.text; }
		var jQuerystate = jQuery(
			'<span class="user-item"><img src="' + state.element.getAttribute('data-photo') + '"/>' + state.text + '</span>'
		);
		return jQuerystate;
	}

/* ==========================================================================
	Datepicker
	========================================================================== */

	jQuery('.datetimepicker-1').datetimepicker({
		widgetPositioning: {
			horizontal: 'right'
		},
		debug: false
	});

	jQuery('.datetimepicker-2').datetimepicker({
		widgetPositioning: {
			horizontal: 'right'
		},
		format: 'LT',
		debug: false
	});

/* ==========================================================================
	Tooltips
	========================================================================== */

	// Tooltip
	jQuery('[data-toggle="tooltip"]').tooltip({
		html: true
	});

	// Popovers
	jQuery('[data-toggle="popover"]').popover({
		trigger: 'focus'
	});

/* ==========================================================================
	Validation
	========================================================================== */

	jQuery('#form-signin_v1').validate({
		submit: {
			settings: {
				inputContainer: '.form-group'
			}
		}
	});

	jQuery('#form-signin_v2').validate({
		submit: {
			settings: {
				inputContainer: '.form-group',
				errorListClass: 'form-error-text-block',
				display: 'block',
				insertion: 'prepend'
			}
		}
	});

	jQuery('#form-signup_v1').validate({
		submit: {
			settings: {
				inputContainer: '.form-group',
				errorListClass: 'form-tooltip-error'
			}
		}
	});

	jQuery('#form-signup_v2').validate({
		submit: {
			settings: {
				inputContainer: '.form-group',
				errorListClass: 'form-tooltip-error'
			}
		}
	});

/* ==========================================================================
	Bar chart
	========================================================================== */

	jQuery(".bar-chart").peity("bar",{
		delimiter: ",",
		fill: ["#919fa9"],
		height: 16,
		max: null,
		min: 0,
		padding: 0.1,
		width: 384
	});

/* ==========================================================================
	Full height box
	========================================================================== */

	function boxFullHeight() {
		var sectionHeader = jQuery('.section-header');
		var sectionHeaderHeight = 0;

		if (sectionHeader.size()) {
			sectionHeaderHeight = parseInt(sectionHeader.height()) + parseInt(sectionHeader.css('padding-bottom'));
		}

		jQuery('.box-typical-full-height').css('min-height',
			jQuery(window).height() -
			parseInt(jQuery('.page-content').css('padding-top')) -
			parseInt(jQuery('.page-content').css('padding-bottom')) -
			sectionHeaderHeight -
			parseInt(jQuery('.box-typical-full-height').css('margin-bottom')) - 2
		);
		jQuery('.box-typical-full-height>.tbl, .box-typical-full-height>.box-typical-center').height(parseInt(jQuery('.box-typical-full-height').css('min-height')));
	}

	boxFullHeight();

	jQuery(window).resize(function(){
		boxFullHeight();
	});

/* ==========================================================================
	Chat
	========================================================================== */

	function chatHeights() {
		jQuery('.chat-dialog-area').height(
			jQuery(window).height() -
			parseInt(jQuery('.page-content').css('padding-top')) -
			parseInt(jQuery('.page-content').css('padding-bottom')) -
			parseInt(jQuery('.chat-container').css('margin-bottom')) - 2 -
			jQuery('.chat-area-header').outerHeight() -
			jQuery('.chat-area-bottom').outerHeight()
		);
		jQuery('.chat-list-in')
			.height(
				jQuery(window).height() -
				parseInt(jQuery('.page-content').css('padding-top')) -
				parseInt(jQuery('.page-content').css('padding-bottom')) -
				parseInt(jQuery('.chat-container').css('margin-bottom')) - 2 -
				jQuery('.chat-area-header').outerHeight()
			)
			.css('min-height', parseInt(jQuery('.chat-dialog-area').css('min-height')) + jQuery('.chat-area-bottom').outerHeight());
	}

	chatHeights();

	jQuery(window).resize(function(){
		chatHeights();
	});

/* ==========================================================================
	Auto size for textarea
	========================================================================== */

	autosize(jQuery('textarea[data-autosize]'));

/* ==========================================================================
	Pages center
	========================================================================== */

	jQuery('.page-center').matchHeight({
		target: jQuery('html')
	});

	jQuery(window).resize(function(){
		setTimeout(function(){
			jQuery('.page-center').matchHeight({ remove: true });
			jQuery('.page-center').matchHeight({
				target: jQuery('html')
			});
		},100);
	});

/* ==========================================================================
	Cards user
	========================================================================== */

	jQuery('.card-user').matchHeight();

/* ==========================================================================
	Fancybox
	========================================================================== */

	jQuery(".fancybox").fancybox({
		padding: 0,
		openEffect	: 'none',
		closeEffect	: 'none'
	});

/* ==========================================================================
	Profile slider
	========================================================================== */

	jQuery(".profile-card-slider").slick({
		slidesToShow: 1,
		adaptiveHeight: true,
		prevArrow: '<i class="slick-arrow font-icon-arrow-left"></i>',
		nextArrow: '<i class="slick-arrow font-icon-arrow-right"></i>'
	});

/* ==========================================================================
	Posts slider
	========================================================================== */

	var postsSlider = jQuery(".posts-slider");

	postsSlider.slick({
		slidesToShow: 4,
		adaptiveHeight: true,
		arrows: false,
		responsive: [
			{
				breakpoint: 1700,
				settings: {
					slidesToShow: 3
				}
			},
			{
				breakpoint: 1350,
				settings: {
					slidesToShow: 2
				}
			},
			{
				breakpoint: 992,
				settings: {
					slidesToShow: 3
				}
			},
			{
				breakpoint: 768,
				settings: {
					slidesToShow: 2
				}
			},
			{
				breakpoint: 500,
				settings: {
					slidesToShow: 1
				}
			}
		]
	});

	jQuery('.posts-slider-prev').click(function(){
		postsSlider.slick('slickPrev');
	});

	jQuery('.posts-slider-next').click(function(){
		postsSlider.slick('slickNext');
	});

/* ==========================================================================
	Recomendations slider
	========================================================================== */

	var recomendationsSlider = jQuery(".recomendations-slider");

	recomendationsSlider.slick({
		slidesToShow: 4,
		adaptiveHeight: true,
		arrows: false,
		responsive: [
			{
				breakpoint: 1700,
				settings: {
					slidesToShow: 3
				}
			},
			{
				breakpoint: 1350,
				settings: {
					slidesToShow: 2
				}
			},
			{
				breakpoint: 992,
				settings: {
					slidesToShow: 3
				}
			},
			{
				breakpoint: 768,
				settings: {
					slidesToShow: 2
				}
			},
			{
				breakpoint: 500,
				settings: {
					slidesToShow: 1
				}
			}
		]
	});

	jQuery('.recomendations-slider-prev').click(function(){
		recomendationsSlider.slick('slickPrev');
	});

	jQuery('.recomendations-slider-next').click(function(){
		recomendationsSlider.slick('slickNext');
	});

/* ==========================================================================
	Box typical full height with header
	========================================================================== */

	function boxWithHeaderFullHeight() {
		/*jQuery('.box-typical-full-height-with-header').each(function(){
			var box = jQuery(this),
				boxHeader = box.find('.box-typical-header'),
				boxBody = box.find('.box-typical-body');

			boxBody.height(
				jQuery(window).height() -
				parseInt(jQuery('.page-content').css('padding-top')) -
				parseInt(jQuery('.page-content').css('padding-bottom')) -
				parseInt(box.css('margin-bottom')) - 2 -
				boxHeader.outerHeight()
			);
		});*/
	}

	boxWithHeaderFullHeight();

	jQuery(window).resize(function(){
		boxWithHeaderFullHeight();
	});

/* ==========================================================================
	Gallery
	========================================================================== */

	jQuery('.gallery-item').matchHeight({
		target: jQuery('.gallery-item .gallery-picture')
	});

/* ==========================================================================
	File manager
	========================================================================== */

	function fileManagerHeight() {
		jQuery('.files-manager').each(function(){
			var box = jQuery(this),
				boxColLeft = box.find('.files-manager-side'),
				boxSubHeader = box.find('.files-manager-header'),
				boxCont = box.find('.files-manager-content-in'),
				boxColRight = box.find('.files-manager-aside');

			var paddings = parseInt(jQuery('.page-content').css('padding-top')) +
							parseInt(jQuery('.page-content').css('padding-bottom')) +
							parseInt(box.css('margin-bottom')) + 2;

			boxColLeft.height('auto');
			boxCont.height('auto');
			boxColRight.height('auto');

			if ( boxColLeft.height() <= (jQuery(window).height() - paddings) ) {
				boxColLeft.height(
					jQuery(window).height() - paddings
				);
			}

			if ( boxColRight.height() <= (jQuery(window).height() - paddings - boxSubHeader.outerHeight()) ) {
				boxColRight.height(
					jQuery(window).height() -
					paddings -
					boxSubHeader.outerHeight()
				);
			}

			boxCont.height(
				boxColRight.height()
			);
		});
	}

	fileManagerHeight();

	jQuery(window).resize(function(){
		fileManagerHeight();
	});

/* ==========================================================================
	Mail
	========================================================================== */

	function mailBoxHeight() {
		jQuery('.mail-box').each(function(){
			var box = jQuery(this),
				boxHeader = box.find('.mail-box-header'),
				boxColLeft = box.find('.mail-box-list'),
				boxSubHeader = box.find('.mail-box-work-area-header'),
				boxColRight = box.find('.mail-box-work-area-cont');

			boxColLeft.height(
				jQuery(window).height() -
				parseInt(jQuery('.page-content').css('padding-top')) -
				parseInt(jQuery('.page-content').css('padding-bottom')) -
				parseInt(box.css('margin-bottom')) - 2 -
				boxHeader.outerHeight()
			);

			boxColRight.height(
				jQuery(window).height() -
				parseInt(jQuery('.page-content').css('padding-top')) -
				parseInt(jQuery('.page-content').css('padding-bottom')) -
				parseInt(box.css('margin-bottom')) - 2 -
				boxHeader.outerHeight() -
				boxSubHeader.outerHeight()
			);
		});
	}

	mailBoxHeight();

	jQuery(window).resize(function(){
		mailBoxHeight();
	});

/* ==========================================================================
	Nestable
	========================================================================== */

	jQuery('.dd-handle').hover(function(){
		jQuery(this).prev('button').addClass('hover');
		jQuery(this).prev('button').prev('button').addClass('hover');
	}, function(){
		jQuery(this).prev('button').removeClass('hover');
		jQuery(this).prev('button').prev('button').removeClass('hover');
	});

/* ==========================================================================
	Widget weather slider
	========================================================================== */

	jQuery('.widget-weather-slider').slick({
		arrows: false,
		dots: true,
		infinite: false,
		slidesToShow: 4,
		slidesToScroll: 4
	});

/* ==========================================================================
	Addl side menu
	========================================================================== */

	setTimeout(function(){
		if (!("ontouchstart" in document.documentElement)) {
			jQuery('.side-menu-addl').jScrollPane(jScrollOptions);
		}
	},1000);

/* ==========================================================================
	Widget chart combo
	========================================================================== */

	jQuery('.widget-chart-combo-content-in, .widget-chart-combo-side').matchHeight();


/* ==========================================================================
	Header notifications
	========================================================================== */

	// Tabs hack
	jQuery('.dropdown-menu-messages a[data-toggle="tab"]').click(function (e) {
		e.stopPropagation();
		e.preventDefault();
		jQuery(this).tab('show');

		// Scroll
		if (!("ontouchstart" in document.documentElement)) {
			jspMessNotif = jQuery('.dropdown-notification.messages .tab-pane.active').jScrollPane(jScrollOptions).data().jsp;
		}
	});

	// Scroll
	var jspMessNotif,
		jspNotif;

	jQuery('.dropdown-notification.messages').on('show.bs.dropdown', function () {
		if (!("ontouchstart" in document.documentElement)) {
			jspMessNotif = jQuery('.dropdown-notification.messages .tab-pane.active').jScrollPane(jScrollOptions).data().jsp;
		}
	});

	jQuery('.dropdown-notification.messages').on('hide.bs.dropdown', function () {
		if (!("ontouchstart" in document.documentElement)) {
			jspMessNotif.destroy();
		}
	});

	jQuery('.dropdown-notification.notif').on('show.bs.dropdown', function () {
		if (!("ontouchstart" in document.documentElement)) {
			jspNotif = jQuery('.dropdown-notification.notif .dropdown-menu-notif-list').jScrollPane(jScrollOptions).data().jsp;
		}
	});

	jQuery('.dropdown-notification.notif').on('hide.bs.dropdown', function () {
		if (!("ontouchstart" in document.documentElement)) {
			jspNotif.destroy();
		}
	});

/* ==========================================================================
	Steps progress
	========================================================================== */

	function stepsProgresMarkup() {
		jQuery('.steps-icon-progress').each(function(){
			var parent = jQuery(this),
				cont = parent.find('ul'),
				padding = 0,
				padLeft = (parent.find('li:first-child').width() - parent.find('li:first-child .caption').width())/2,
				padRight = (parent.find('li:last-child').width() - parent.find('li:last-child .caption').width())/2;

			padding = padLeft;

			if (padLeft > padRight) padding = padRight;

			cont.css({
				marginLeft: -padding,
				marginRight: -padding
			});
		});
	}

	stepsProgresMarkup();

	jQuery(window).resize(function(){
		stepsProgresMarkup();
	});

/* ========================================================================== */

	jQuery('.control-panel-toggle').on('click', function() {
		var self = jQuery(this);
		
		if (self.hasClass('open')) {
			self.removeClass('open');
			jQuery('.control-panel').removeClass('open');
		} else {
			self.addClass('open');
			jQuery('.control-panel').addClass('open');
		}
	});

	jQuery('.control-item-header .icon-toggle, .control-item-header .text').on('click', function() {
		var content = jQuery(this).closest('li').find('.control-item-content');

		if (content.hasClass('open')) {
			content.removeClass('open');
		} else {
			jQuery('.control-item-content.open').removeClass('open');
			content.addClass('open');
		}
	});

	jQuery.browser = {};
	jQuery.browser.chrome = /chrome/.test(navigator.userAgent.toLowerCase());
	jQuery.browser.msie = /msie/.test(navigator.userAgent.toLowerCase());
	jQuery.browser.mozilla = /firefox/.test(navigator.userAgent.toLowerCase());

	if (jQuery.browser.chrome) {
		jQuery('body').addClass('chrome-browser');
	} else if (jQuery.browser.msie) {
		jQuery('body').addClass('msie-browser');
	} else if (jQuery.browser.mozilla) {
		jQuery('body').addClass('mozilla-browser');
	}
});