<?
get_header();
$author = get_user_by( 'slug', get_query_var( 'author_name' ) );
?>
<div class="page-header-container header-my-events">
	<div class="narrow">
		<h2>Events by <? echo $author->first_name . ' ' . $author->last_name; ?></h2>
	</div>
</div>

<div class="events-bg-container">
	<div class="events wide my-events">
	
		<div class="events-container">
<?
		/*  Get all the events from the database and look each one up.  If its owner is the current user, display it.  */

		$events = tb_get_all_events();
		$myid = $author->id;
		$token = getenv("EB_PERSONAL_TOKEN");
		$counter = 0;


		/*  Check if the event belongs ot this mentor  */
		foreach($events as $event) : 

			$eb_event = tb_get_eventbrite_event($event['id'], $token);
			$mentor_id = tb_get_mentor_by_wp_id($myid);
			$mentor_id = $mentor_id[0]['id'];

			if($eb_event['organizer_id'] == $mentor_id) :
				$counter++;

				/*  Get the authors name from organizer ID  */
				$author = tb_get_mentor_by_eventbrite_id($mentor_id)[0]['name'];
				$image = get_the_post_thumbnail_url($event['post'], 'full');
				$link = get_the_permalink($event['post']);
				$author_link = get_author_posts_url(tb_get_mentor_by_eventbrite_id($mentor_id)[0]['wp_id']);
				/*  The post belongs to this user.  Display a tile.  */
				echo <<<EOT
					<div class="item">
						<div class="image" style="background-image: url('$image');">
					
						</div>
						<div class="title">
							<h2>{$eb_event['name']['text']}</h2>
						</div>
						<div class="meta">
							<span></span>
							<span><a href='$author_link'>By: $author</a></span>
						</div>
						<div class="content">
							{$eb_event['description']['text']}
						</div>
						<a href='$link'>Read More</a>
					</div>
EOT;
		endif;
		endforeach;
		
		if($counter == 0) {
		?>
			<h2 style="text-align: center;">You havent created any events.</h2>
			<a style="text-decoration: underline; width: 100%; text-align: center; color: #000; font-family: 'Abel', sans-serif; font-size: 1.5rem;" href="/student-dashboard">&lsaquo;&lsaquo;&nbsp;Back to Dashboard</a>
		<?
		}
		?>
		</div>  <!-- End events container -->
	</div>
</div> <!--  End events bg container -->

<?
get_footer();
?>