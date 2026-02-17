(function($) {
    function is_touch_device() {
	 return (('ontouchstart' in window) || (navigator.MaxTouchPoints > 0) || (navigator.msMaxTouchPoints > 0));
	}
	 
	if (is_touch_device()) {
	 $('.awsm-grid > .awsm-grid-card > figure').on('touchend', function(e) {
           if($(e.target).is('.awsm-grid > .awsm-grid-card > figure a') || $(e.target).is('.awsm-grid > .awsm-grid-card > figure a *')) return;
            e.preventDefault();
            $(this).toggleClass('cs-hover');
        });
	}
	if (!is_touch_device()) {
		$('.awsm-grid-wrapper').addClass('no-touchevents');
	}
	if (window.navigator.userAgent.indexOf("Windows NT 10.0")!= -1){
		$('.awsm-grid-wrapper').addClass('no-touchevents');
	}
})(jQuery);