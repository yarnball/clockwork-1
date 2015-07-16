/*!
 * jQuery Ajax Code for Clockwork SMS
 */
jQuery(document).ready(function($) {

    $("#clockworkform").submit(function(e) {

	e.preventDefault();

	var phonenum = $("input[name=phone]").val();

        $.ajax({
            type: 'POST',
            url: clockworkajax.ajaxurl,
  	    data: {
                action: "clockwork_send_sms",
                clockworkNonce: clockworkajax.clockworkNonce,
		phone: phonenum,
    	    },
            beforeSend: function() {
                $("#result").empty();
                $("#result").html('<img src="' + clockworkajax.loading + '" />');
            },
            success: function(data) {
                $("#result").html(data);
            }
        });
	return false;
   });
});
