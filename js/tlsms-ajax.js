jQuery(function() {

	/* Text Local SMS, AJAX Script - By Alobaidi - wp-plugins.in */

	jQuery('#tlsms').submit( function(e) {

        jQuery("#tlsms-result").slideUp();

       	jQuery("p#tlsms-ajax-icon").slideDown();

        var tlsmsURL = jQuery(this).attr("action");

        var tlsmsSubmitName = jQuery("#tlsms_submit").attr("name");

        var tlsmsSerializeSubmit = tlsmsSubmitName + "=" + "send&";

        var tlsmsSerialize = tlsmsSerializeSubmit + jQuery(this).serialize();

        jQuery.ajax({
            type: 'POST',
            url: tlsmsURL,
            data: tlsmsSerialize,
            cache: false,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            processData: true,
            success: function(tlsmsResult) {
                jQuery("p#tlsms-ajax-icon").slideUp();
                jQuery("#tlsms-result").html(tlsmsResult).slideDown();
            }
    	});

		e.preventDefault();

	});

});