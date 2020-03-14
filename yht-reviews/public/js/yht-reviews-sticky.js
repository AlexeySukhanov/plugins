

jQuery(document).ready( function(){
    // When the user scrolls the page, execute myFunction
    window.onscroll = function() { sticky_action_bar() };

    // Get the action_bar
    var action_bar = document.getElementById("action_bar");

    // Get the offset position of the action_bar
    // var sticky = action_bar.offsetTop;

    var sticky = jQuery("#action_bar").offset();
    sticky = sticky.top;
    // Add the sticky class to the action_bar when you reach its scroll position. Remove "sticky" when you leave the scroll position
    function sticky_action_bar() {

        if ( window.pageYOffset <  sticky + 84  ) {

            action_bar.classList.remove("sticky");
            action_bar.classList.remove("fadeout");
            if (jQuery("#action_bar").width() <= 752 ){
                jQuery("#action_bar").removeClass("m752 m944");
                jQuery("#action_bar").addClass("m752");
            } else if (jQuery("#action_bar").width() <= 944 ) {
                jQuery("#action_bar").removeClass("m752 m944");
                jQuery("#action_bar").addClass("m944");
            } else if (jQuery("#action_bar").width() > 944 ) {
                jQuery("#action_bar").removeClass("m752 m944");
            }
        } else if (window.pageYOffset >= sticky + 180) {
            action_bar.classList.add("sticky");
            action_bar.classList.remove("fadeout");
            action_bar.classList.add("fadein");
        } else {
            action_bar.classList.remove("fadein");
            action_bar.classList.add("fadeout");
        }
    }

    if (jQuery("#action_bar").width() <= 752 ){
        jQuery("#action_bar").removeClass("m752 m944");
        jQuery("#action_bar").addClass("m752");
    } else if (jQuery("#action_bar").width() <= 944 ) {
        jQuery("#action_bar").removeClass("m752 m944");
        jQuery("#action_bar").addClass("m944");
    } else if (jQuery("#action_bar").width() > 944 ) {
        jQuery("#action_bar").removeClass("m752 m944");
    }

    jQuery(window).resize(function(){
        if (jQuery("#action_bar").width() <= 752 ){
            jQuery("#action_bar").removeClass("m752 m944");
            jQuery("#action_bar").addClass("m752");
        } else if (jQuery("#action_bar").width() <= 944 ) {
            jQuery("#action_bar").removeClass("m752 m944");
            jQuery("#action_bar").addClass("m944");
        } else if (jQuery("#action_bar").width() > 944 ) {
            jQuery("#action_bar").removeClass("m752 m944");
        }
    });

    jQuery(window).scroll(function(){
        if (jQuery("#action_bar").width() <= 752 ){
            jQuery("#action_bar").removeClass("m752 m944");
            jQuery("#action_bar").addClass("m752");
        } else if (jQuery("#action_bar").width() <= 944 ) {
            jQuery("#action_bar").removeClass("m752 m944");
            jQuery("#action_bar").addClass("m944");
        } else if (jQuery("#action_bar").width() > 944 ) {
            jQuery("#action_bar").removeClass("m752 m944");
        }
    });
});