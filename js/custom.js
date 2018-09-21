/* Demo slider */
jQuery(window).load(function(){
    // console.log('loaded up');
    jQuery('a[href^="http://www.thinkupthemes.com"]').attr( 'target', '_blank' );
    jQuery('a[href^="http://www.wordpress.org"]').attr( 'target', '_blank' );

    jQuery('a[href="#"]').attr( 'onclick', 'return false;' );

    jQuery('#wpcf7-f9604-w1-o1').click(function(){
        alert('Contact form is disabled');
        return false;
    });

    // Remove 'Upcoming Events ›' breadcrumb from tribe events calendar list displays
    $target = jQuery('h2.tribe-events-page-title');
    if ($target.length !== 0){
        $string = $target.html();
        $string = $string.replace('Upcoming Events', '');
        $string = $string.replace('›', '');
        $target.html($string);
    }

/** Handle ios detection for iframes */
    var ua = window.navigator.userAgent;
    var iOS = !!ua.match(/iPhone/i);
    var webkit = !!ua.match(/WebKit/i);
    var iOSSafari = true;
    if (!iOS || !webkit || !!ua.match(/CriOS/i)) {
        iOSSafari = false;
    }
    // var iOSSafari = iOS && webkit && !ua.match(/CriOS/i);
    if (iOSSafari) {
        jQuery('body').addClass('ios-iphone-safari');
    }

    if (jQuery('h1.tribe-events-page-title').length >= 1) {
        // console.log('Tribe events heading exists.');
        jQuery('h1.tribe-events-page-title a').remove();
        $inner = jQuery('h1.tribe-events-page-title').text();
        // console.log($inner);
        $inner = $inner.replace(/› /g,'');
        // console.log($inner);
        jQuery('h1.tribe-events-page-title').text($inner).css('display', 'inline-block');
    }

});
