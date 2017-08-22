<?
/*  Template Name:  Home  */
?>

<?
get_header();

the_post();
?>

<div class="page-header-container header-home">
	<div class="narrow">
		<div>
			<video playsinline autoplay muted loop id="nameplate">
        		<source src="/wp-content/uploads/2017/07/Animated-1.webm" type="video/webm">
        		<source src="/wp-content/uploads/2017/07/Animated-1.mp4" type="video/mp4">
    		</video>
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

<div class='welcome narrow'>	
	<?  echo get_the_content();  ?>
</div>

<div class="portal narrow">
	<div class='item portalitem'>
		<div class='title'>
			<h2>Students</h2>
		</div>
		<div class="content">
			<? echo get_field("student_box_text"); ?>
			<a href="/students">Enter Here</a>
		</div>
	</div>
	<div class='item portalitem'>
		<div class='title'>
			<h2>Mentors</h2>
		</div>
		<div class="content">
			<? echo get_field("mentor_box_text"); ?>
			<a href="/mentors">Enter Here</a>
		</div>
	</div>
</div>

<div class="events-bg-container">
	<div class="events wide">
		<h2 class="narrow">Events</h2>
	
		<div class="events-container">
	
			<?  
			$events = tb_get_all_events();
			$token = getenv("EB_PERSONAL_TOKEN");
			foreach($events as $event) :

			$eb_event = tb_get_eventbrite_event($event['id'], $token);
			$author = tb_get_mentor_by_eventbrite_id($event['organizer'])[0]['name'];
			$link = get_the_permalink($event['post']);
			$image = get_the_post_thumbnail_url($event['post'], 'full');
			$author_link = get_author_posts_url(tb_get_mentor_by_eventbrite_id($event['organizer'])[0]['wp_id']);
			?>
			
				<div class="item">
					<div class="image" style="background-image: url('<? echo $image; ?>');">
			
					</div>
					<div class="title">
						<h2><? echo $eb_event['name']['text']; ?></h2>
					</div>
					<div class="meta">
						<a href='<? echo $author_link; ?>'><span>By: <? echo $author; ?></span></a>
					</div>
					<div class="content">
						<? echo str_replace('\\', '', $eb_event['description']['text']); ?>
					</div>
					<a target="_blank" href="<?echo get_the_permalink($event['post']); ?>">Read More</a>
				</div>
	
			<?  endforeach;  ?>
		</div>
	</div>
</div>



<?   get_footer();  ?>

