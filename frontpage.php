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
			<h2>Ignite Your Curiosity!</h2>
			<span>Meet new people and learn together.  Imagine the possibilities!  Try it, share it, have fun with it.</span>
			<a href="#">Learn More</a>
		</div>
		<img src='/wp-content/uploads/2017/06/woman.png' />
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
			<h3>Title Here</h3>
			<span>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</span>
			<a href="/students">Enter Here</a>
		</div>
	</div>
	<div class='item portalitem'>
		<div class='title'>
			<h2>Mentors</h2>
		</div>
		<div class="content">
			<h3>Title Here</h3>
			<span>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</span>
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
					<a target="_blank" href="<?echo get_the_permalink($event['post']); ?>">Read More</a>
				</div>
	
			<?  endforeach;  ?>
		</div>
	</div>
</div>



<?   get_footer();  ?>

