<?

		wp_reset_query();
		
		$query_args = array(
			"category_name" => 'featured',
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
			<div class='featured-slide'>
				<h2>$title</h2>
				<span class='date'>Date: $date</span>
				<span class='author'>By: $author</span>
				<p>$excerpt <a href={$link}>Read More</a></p>
				<span class='tags'>Tags: $tags_string</span>
			</div>

EOT;
		endwhile;

		wp_reset_query();
?>