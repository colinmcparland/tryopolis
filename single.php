<?php 
get_header(); 
the_post();
?>

<div class="page-header-container header-single-post">
	<div class="narrow">
		<h2><? echo get_the_title(); ?></h2>
		<span class='date'>Date: <? echo get_the_date('F d, Y') ?></span>
		<span class='author'>By: <? echo get_the_author(); ?></span>
	</div>
</div>

<div class="icons narrow">
	<img src="/wp-content/uploads/2017/05/icon1.png" />
	<img src="/wp-content/uploads/2017/05/icon2.png" />
	<img src="/wp-content/uploads/2017/05/icon3.png" />
	<img src="/wp-content/uploads/2017/05/icon4.png" />
	<img src="/wp-content/uploads/2017/05/icon5.png" />
	<img src="/wp-content/uploads/2017/05/icon6.png" />
</div>

<div class="single main-content narrow">
<h2 style="text-align: center;"><? echo get_the_title(); ?></h2>
<? echo apply_filters('the_content', get_the_content()); ?>
</div>

<div class="narrow author-box">
	<img src='http://via.placeholder.com/300x375' />
	<div class="col">
		<h2><? echo get_the_author(); ?></h2>
		<p><? echo get_the_author_meta('description'); ?></p>
		<h2>Share this article!</h2>
		<div class="row">
			<a target="_blank" href='<? echo "https://www.facebook.com/sharer/sharer.php?u=http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>'><img src="/wp-content/uploads/2017/06/004-facebook-logo-button.png" /></a>
			<a target="_blank" href='<? echo "https://twitter.com/home?status=http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>'><img src="/wp-content/uploads/2017/06/003-twitter-logo-button.png" /></a>
			<a target="_blank" href='<? echo "https://pinterest.com/pin/create/button/?url=http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]&media=" . get_the_title(); ?>'><img src="/wp-content/uploads/2017/06/002-pinterest-logotype-circle.png" /></a>
			<a target="_blank" href='<? echo "https://plus.google.com/share?url=http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>'><img src="/wp-content/uploads/2017/06/001-google-plus-logo-button.png" /></a>
		</div>
	</div>
</div>


<div class="narrow single-newsletter">
	<h1>Want to learn more?</h1>
	<h2>Join Our Newsletter!</h2>
	<span>
		<form method="POST">
			<input required type="email" placeholder="your e-mail address *" name="single-email">
			<input type="submit" value="Sign Me Up" name="single-submit">
		</form>
	</span>
</div>

<div class="narrow single-comments">
	<? echo do_shortcode('[fbcomments]'); ?>
</div>

<div class="backtop">
	<a>^</a>
	<span>Back to top</span>
</div>

<div class="wide single-related-posts">
	<h2>Related Posts</h2>
	<? include ('scripts/related-posts.php'); ?>
</div> 

<?php get_footer(); ?>