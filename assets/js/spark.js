jQuery(function() {
    /* Panels */
    jQuery('.panel-slider').slick({
        dots: false,
        infinite: true,
        speed: 1500,
        slidesToShow: 1,
        adaptiveHeight: true,
        autoplay: true,
        autoplaySpeed: 8000,
        arrows: true
    });
    
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
    
    /* Initialise slider block */
    var initializeBlock = function(block) {
        block.find('.slides').slick({
            arrows: false,
            dots: true,
            infinite: false,
            easing: 'swing'
        });     
    }

    // Initialize each block on page load (front end).
    jQuery('.slider-block').each(function(){
        initializeBlock(jQuery(this));
    });

    // Initialize dynamic block preview (editor).
    if (window.acf) {
        window.acf.addAction('render_block_preview/type=slider', initializeBlock);
    }
});
