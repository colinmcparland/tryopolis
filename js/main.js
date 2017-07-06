(function($) {

	/*  Function to even out all the heights of the blog tiles.  */
	function adjustBlogTileHeight() {
		if($(".item").length) {
			var largest = 0;
			$(".item").each(function()	{
				if($(this).innerHeight() > largest && $(this).parent().attr("class")) {
					largest = $(this).innerHeight();
				}
			})
			$(".item:not(.portalitem)").innerHeight(largest+60);
		}
	};

	/*  Function to scroll to top of screen.  */
	function goToTop() {
		$("html, body").animate({
			scrollTop: '0'
		}, 2000);
	}

	$(document).ready(function() {

		/*  Only adjust tile height if were not on mobile.  */
		if(window.innerWidth >= 800) {
			adjustBlogTileHeight();
		}
		else {
			$(".item .content").css("margin-bottom", '85px');
		}

		$(".datepicker").datepicker();
		$(".timepicker").timepicker({});

		$(".formarea form").submit(function()	{
			if($("#g-recaptcha-response").val().length == 0) {
				if($(".recaptcha-err").length == 0) {
					$("<p class='recaptcha-err' style='color: red;'>Please check the CAPTCHA.</p>").insertAfter(".g-recaptcha");
				}
				return false;
			}
		})

		//  Validate mentor registration password
		$(".mentor-signup-box form").submit(function()	{
			if($("input[name=mentor-signup-password").val() != $("input[name=mentor-signup-password-confirm").val()) {
				if($(".password-confirm-err").length == 0) {
					$("<p class='password-confirm-err' style='color: red;'>Passwords do not match.</p>").insertAfter(".mentor-signup-box form input[name=mentor-signup-password-confirm");
				}
				return false;
			}
		})


		/*  TODO: Check student password???  */

		//  Scroll to top
		$(".backtop").click(function()	{
			goToTop();
		})

		// Confirmation dialog when deleting an event
		$("input[name=delete-event-submit]").click(function(e)	{
			if(confirm('Are you sure?  This action cannot be undone.')) {
				$("#delete-event-form").submit();
				return true;
			}
			else {
				e.preventDefault();
				return false;
			}
		})

		//  Submit placeholder logic
		$("#mentor-login-form .submitplaceholder, .student-login-form .submitplaceholder").click(function()	{
			$(this).next().click();
		})

		$(".footer-form .submitplaceholder").click(function()	{
			$(".footer-form input[type=submit]").click();		})

		//  Slide down the mobile menu
		$(".menu-icon").click(function()	{
			if($("#menu-main-menu").is(":hidden")) {
				$("#menu-main-menu").slideDown();
			}
			else {
				$("#menu-main-menu").slideUp();
			}
			
		})


	}) //  End document ready

})(jQuery);