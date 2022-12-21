jQuery(function() {
    /* Close sticky menu when menu item clicked */
    jQuery('.sticky .menu[data-accordion-menu] ul.nested a').click(function() {
        jQuery(this).closest('.menu[data-accordion-menu]').foundation('hideAll');
    });
    
    /* Make equalizer and tabs play nicely together */
    jQuery('.tabs').on('change.zf.tabs', function() {
        Foundation.reInit('equalizer');
    });
    
    /* Smooth scrolling to anchors */
    jQuery('a[href*="#"]:not([href="#"]):not([role="tab"])').click(function() {
        if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
            var target = jQuery(this.hash);
            target = target.length ? target : jQuery('[name="' + this.hash.slice(1) +'"]');
            if (target.length) {
                jQuery('html,body').stop().animate({scrollTop: target.offset().top}, 1000);
                return false;
            }
        }
    });
});
