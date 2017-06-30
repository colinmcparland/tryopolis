<?
	$query_args = array(
		"posts_per_page" => '-1'
	);

	query_posts($query_args);

	while(have_posts()) : the_post();

		$title = get_the_title();
		$date = get_the_date('F d, Y');
		$author = get_the_author();
		$link = get_the_permalink();

		$excerpt = get_the_excerpt();
		$excerpt = strip_tags($excerpt);
		$excerpt = strip_shortcodes($excerpt);

		$tags = get_the_tags();

		$tags_string = '';

		foreach($tags as $tag) : 
			$tags_string .= $tag->name . ', ';
		endforeach;

		$tags_string = substr($tags_string, 0, -2);


		echo <<<EOT
			<div class="item">
					<div class="image">
					</div>
					<div class="title">
						<h2>$title</h2>
					</div>
					<div class="meta">
						<span>Date: $date</span>
						<span>By: $author</span>	
					</div>
					<div class="content">$excerpt</div>
					<a href={$link}>Read More</a>
				</div>
EOT;
	endwhile;

	wp_reset_query();
?>