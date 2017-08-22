<?
get_header();
the_post();
$token = getenv("EB_PERSONAL_TOKEN");
$this_event_id = tb_get_event_by_wp_id(get_the_ID())[0]['id'];
$event = tb_get_eventbrite_event($this_event_id, $token);
$mentor = tb_get_mentor_by_eventbrite_id($event['organizer_id']);
$venue = tb_get_eventbrite_venue($event['venue_id'], $token);
$user = get_userdata(get_current_user_ID());
?>

<div class="page-header-container header-single-event">
	<div class="narrow">
		<h2><? echo $event['name']['text']; ?></h2>
		<span>By <? echo $mentor[0]['name']; ?></span>
	</div>
</div>

<div class="narrow single-event">
	<div>
	<img src="<? echo the_post_thumbnail_url('full');  ?>" />
	</div>
	<div>
		<span>Description</span>
		<p><? echo str_replace('\\', '', $event['description']['text']); ?></p>
	</div>
	<div class="third">
		<span>Location</span>
		<p><? echo $venue['name']; ?></p>
		<p><? echo $venue['address']['address_1']; ?></p>
		<p><? echo $venue['address']['city']; ?>, <? echo $venue['address']['region'];?></p>
	</div>
	<div class="third">
		<span>Start Date</span>
		<p><? echo date("F jS, Y", strtotime(explode('T', $event['start']['local'])[0])); ?></p>
		<p><? echo date("g:i A", strtotime(explode('T', $event['start']['local'])[1])); ?></p>
	</div>
	<div class="third">
		<span>End Date</span>
		<p><? echo date("F jS, Y", strtotime(explode('T', $event['end']['local'])[0])); ?></p>
		<p><? echo date("g:i A", strtotime(explode('T', $event['end']['local'])[1])); ?></p>
	</div>
	<div>
		<h2>Only <span><? echo $event['capacity']; ?></span> spots left!</h2>
	</div>
	<?
	/*  If the student isnt registered for the event, show the registration button.  */
	if(in_array('student', $user->roles) && tb_is_student_rsvp($user->id, $event['id']) == 0) :
	?>
	<div>
		<form id="student-event-register" method="POST">
		<input type='submit' class="action-btn" value="Register Now!" name="register-now-submit" style="width: 100%; font-size: 2rem;" />
		</form>
	</div>
	<?
	/*  If the student is registered, show the unregister button */
	elseif(in_array('student', $user->roles) && tb_is_student_rsvp($user->id, $event['id']) == 1) :
		?>
	<div>
		<form id="student-event-deregister" method="POST">
		<input type='submit' class="action-btn" value="Deregister" name="deregister-now-submit" style="width: 100%; font-size: 2rem;" />
		</form>
	</div>
	<?
	/*  If the user owns this event, show edit and attendance sheet buttons.  */
	elseif((in_array('mentor', $user->roles) && $user->id == $mentor[0]['wp_id']) ||  in_array('administrator', $user->roles)) :
	?>
	<div>
		<a href="/edit-event/?id=<? echo get_the_ID(); ?>" class="action-btn">Edit Event</a>
	</div>
	<div>
		<a href="/attendance/?id=<? echo get_the_ID(); ?>" class="action-btn">Take Attendance</a>
	</div>

	<?
	endif;
	?>
</div>


<?
get_footer();
?>