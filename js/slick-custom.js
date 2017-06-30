jQuery(document).ready(function($)	{

	$(".blog-header-slider").slick({
		autoplay: true,
  		autoplaySpeed: 7000,
  		arrows: false,
  		dots: true,
  		slidesToShow: 1,
  		infinite: true,
  		pauseOnFocus: false,
		pauseOnHover: false
	});



	// $(".changemakers >div").slick({
	// 	slidesToScroll: 1,
	// 	slidesToShow: 5,
	// 	arrows: false,
	// 	pauseOnFocus: false,
	// 	pauseOnHover: false,
	// 	autoplay: true,
	// 	autoplaySpeed: 1000,
	// 	responsive: [
	// 		{
	// 			breakpoint: 768,
	// 			settings: {
	// 				slidesToShow: 4
	// 			}
	// 		},
	// 		{
	// 			breakpoint: 580,
	// 			settings: {
	// 				slidesToShow: 2
	// 			}
	// 		}
	// 	]
	// });
});