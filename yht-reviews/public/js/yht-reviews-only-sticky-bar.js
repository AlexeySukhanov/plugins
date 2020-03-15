jQuery(document).ready(function($) {

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