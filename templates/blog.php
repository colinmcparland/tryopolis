<?
/*  Template Name:  Blog  */
get_header();
?>
<div class="page-header-container header-blog">
	<div class="narrow">
		<h2>Featured Blog Posts</h2>
		<div class="blog-header-slider">
		<? include(dirname(__FILE__) . '/../scripts/featured-posts.php'); ?>
		</div>
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

<div class="narrow latest-posts">
	<h2>Latest Posts</h2>
	<? include(dirname(__FILE__) . '/../scripts/latest-posts.php'); ?>
</div>

<div class="wide all-posts">
	<h2>All Our Posts</h2>
	<? include(dirname(__FILE__) . '/../scripts/all-posts.php'); ?>
</div>

<div class="backtop">
	<a>^</a>
	<span>Back to top</span>
</div>


<?
get_footer();
?>