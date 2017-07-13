<?
/*  Template Name: My Events (Student)  */
get_header();
?>
<div class="page-header-container header-my-events">
	<div class="narrow">
		<h2>My Events</h2>
	</div>
</div>

<div class="events-bg-container">
	<div class="events wide my-events">
	
		<div class="events-container">
<?
		/*  Get all the events from the database and look each one up.  If its owner is the current user, display it.  */

		$events = tb_get_all_events();
		$myid = get_current_user_id();
		$token = getenv("EB_PERSONAL_TOKEN");
		$counter = 0;
		/*  Check if the event belongs ot this mentor  */
		foreach($events as $event) : 
			$eb_event = tb_get_eventbrite_event($event['id'], $token);

			if(tb_is_student_rsvp(get_current_user_ID(), $eb_event['id'])) :

				/*  So we can tell if no events  */
				$counter++;

				/*  Get the authors name from organizer ID  */
				$author = tb_get_mentor_by_eventbrite_id($eb_event['organizer_id'])[0]['name'];
				$image = get_the_post_thumbnail_url($event['post'], 'full');
				$link = get_the_permalink($event['post']);
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
							<span>By: $author</span>	
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
			<h2 style="text-align: center;">You havent RSVP'd to any events.</h2>
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