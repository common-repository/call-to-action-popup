(function ($) {
	"use strict";
	var documentHeight = 0;
	var offsetBottom = 0;
	var getRepeatHours = $('.cactus-popup').attr('data-repeat-hours');
	var popupTestMode = $('.cactus-popup').attr('data-test-mode');
	var clickAnywhere = $('.cactus-popup').attr('data-click-anywhere');
	var popupId = $('.cactus-popup').attr('data-id');
	var popupNonce = $('.cactus-popup').attr('data-nonce');
	var popupDisableScroll = $('.cactus-popup').attr('data-disable-scroll');
	var beforeScroll = -1;
	var currentScroll = 0;
	var windowHeight = 0;
	var scrollDetect = 0;

	if (popupTestMode === 'on') {
		$.removeCookie('cactus-cta-popup-cookie', {
			path: '/'
		});
		$.removeCookie('cactus-cta-popup-subscribed-' + popupId, {
			path: '/'
		});
	}

	$(window).load(function () {
		documentHeight = $(document).height();
		offsetBottom = $('.cactus-popup').attr('data-offset-bottom');
		//remove popup when browser refresh
	});

	$('.popup-close .close').click(function () {

		if (popupTestMode === 'off') {
			var date = new Date();
			date.setTime(date.getTime() + (getRepeatHours * 60 * 60 * 1000));
			$.cookie('cactus-cta-popup-cookie', 1, {
				expires: date,
				path: '/'
			});
		}
	});

	if (clickAnywhere === 'on') {
		if (popupTestMode === 'off') {
			$(document).mouseup(function (e) {
				var container = $('.click-anywhere');
				if (!container.is(e.target) && container.has(e.target).length === 0) {
					var date = new Date();
					date.setTime(date.getTime() + (getRepeatHours * 60 * 60 * 1000));
					$.cookie('cactus-cta-popup-cookie', 1, {
						expires: date,
						path: '/'
					});
				}
			});
		}
	}

	$(window).scroll(function () {
		//Popup Working...
		currentScroll = $(this).scrollTop();
		windowHeight = $(window).height();
		scrollDetect = beforeScroll > 0 ? currentScroll - beforeScroll : 0;
		beforeScroll = currentScroll;

		var CheckCookie = $.cookie('cactus-cta-popup-cookie');
		var CheckSubcribed = $.cookie('cactus-cta-popup-subscribed-' + popupId);

		if ((CheckCookie === '1') || (CheckSubcribed === 'subcribed')) {
			$('#cactus-popup').modal('hide');
		} else {
			if (scrollDetect > 10) {
				if (currentScroll + windowHeight >= documentHeight - offsetBottom) {
					$('#cactus-popup').modal({
						show: true,
						backdrop: clickAnywhere === 'on' ? true : 'static',
					});
				}
			}
		}
	});

	$('#cactus-popup').on('show.bs.modal', function () {
		if (popupDisableScroll === 'disable') {
			if ($(document).height() > $(window).height()) {
				var scrollTop = ($('html').scrollTop()) ? $('html').scrollTop() : $('body').scrollTop();
				$('html').addClass('cactus-popup-disable-scroll').css('top', -scrollTop);
			}
		}
	});

	$('#cactus-popup').on('shown.bs.modal', function () {
		//ajax process display
		var popupType = 'display';
		jQuery.ajax({
			type: 'post',
			dataType: 'text',
			url: cactus_popup.ajaxurl,
			data: {
				action: "cactus_get_popup_metadata",
				popupId: popupId,
				popupNonce: popupNonce,
				popupType: popupType
			},
			success: function (response) {
				if (response === 'success') {
					//do something when display is updated.
				}
			}
		});
	});

	$('#cactus-popup').on('hidden.bs.modal', function () {

		if (popupDisableScroll === 'disable') {
			var scrollTop = parseInt($('html').css('top'));
			$('html').removeClass('cactus-popup-disable-scroll');
			$('html,body').scrollTop(-scrollTop);
		}

		$('.modal-backdrop').remove();
		$('#body').removeAttr('style');

		if (popupTestMode === 'on') {
			var date = new Date();
			date.setTime(date.getTime() + (getRepeatHours * 60 * 60 * 1000));
			$.cookie('cactus-cta-popup-cookie', 1, {
				expires: date,
				path: '/'
			});
		}

		$('.modal').remove();
		$('.modal-backdrop').remove();
		$('body').removeClass("modal-open");
		$('body').css('padding-right', '0');
		$(this).data('bs.modal', null);

	});

	$("#cactus-popup").on('subscribed', function () {
		//ajax process subscribed
		var popupType = 'subscribed';
		jQuery.ajax({
			type: "post",
			dataType: "text",
			url: cactus_popup.ajaxurl,
			data: {
				action: "cactus_get_popup_metadata",
				popupId: popupId,
				popupNonce: popupNonce,
				popupType: popupType
			},
			success: function (response) {
				if (popupTestMode === 'off') {
					if (response === "success") {
						var date = new Date();
						// expires ~ 2030 year.
						date.setTime(date.getTime() + (10 * 365 * 24 * 5000000));
						$.cookie('cactus-cta-popup-subscribed-' + popupId, 'subcribed', {
							expires: date,
							path: '/'
						});
					}
				}
			}
		});
	});

	$(document).ready(function () {
		//
	});
}(jQuery));