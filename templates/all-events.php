<?
/*  Template Name:  All Events  */
get_header();
?>

<div class="page-header-container header-my-events">
	<div class="narrow">
		<h2>All Events</h2>
	</div>
</div>

<div class="events-bg-container">
	<div class="events wide all-events">
		<div class="events-container">
	
			<?  
			$events = tb_get_all_events();

			foreach($events as $event) :
				$token = getenv("EB_PERSONAL_TOKEN");
				$eb_event = tb_get_eventbrite_event($event['id'], $token);

				$author = tb_get_mentor_by_eventbrite_id($event['organizer'])[0]['name'];

				$link = get_the_permalink($event['post']);

			?>
			
				<div class="item">
					<div class="image">
			
					</div>
					<div class="title">
						<h2><? echo $eb_event['name']['text']; ?></h2>
					</div>
					<div class="meta">
						<span>By: <? echo $author; ?></span>	
					</div>
					<div class="content">
						<? echo htmlspecialchars($eb_event['description']['text']); ?>
					</div>
					<a href='<? echo $link; ?>'>Read More</a>
				</div>
	
			<?  endforeach;  ?>
		</div>
	</div>
</div>

<?
get_footer();
?>