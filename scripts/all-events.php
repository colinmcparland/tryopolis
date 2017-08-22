<?
	$query_args = array(
		"posts_per_page" => '-1',
		"post_type" => 'tb_event'
	);

	query_posts($query_args);

	while(have_posts()) : the_post();

		$title = get_the_title();
		$date = get_the_date('F d, Y');
		$author = get_the_author();
		$link = get_the_permalink();
		$img = get_the_post_thumbnail_url('full');

		$excerpt = get_the_excerpt();
		$excerpt = strip_tags($excerpt);
		$excerpt = strip_shortcodes($excerpt);

		$tags = get_the_tags();

		$author_link = get_author_posts_url();

		$tags_string = '';

		foreach($tags as $tag) : 
			$tags_string .= $tag->name . ', ';
		endforeach;

		$tags_string = substr($tags_string, 0, -2);


		echo <<<EOT
			<div class="item">
					<div class="image" style="background-image: url('$image');">
					</div>
					<div class="title">
						<h2>$title</h2>
					</div>
					<div class="meta">
						<span>Date: $date</span>
						<a href='$author_link'><span>By: $author</span></a>
					</div>
					<div class="content">$excerpt</div>
					<a href={$link}>Read More</a>
				</div>
EOT;
	endwhile;

	wp_reset_query();
?>