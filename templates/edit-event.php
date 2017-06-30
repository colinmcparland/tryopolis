<?
/*  Template Name:  Edit Event  */
include('role-redirects/mentor-dashboard.php'); 
get_header();

$token = getenv("EB_PERSONAL_TOKEN");
if(isset($_GET['id'])) {
	$event_id = $_GET['id'];
}
else {
	wp_safe_redirect('//' . $_SERVER['HTTP_HOST']);
}

$this_event_id = tb_get_event_by_wp_id($event_id)[0]['id'];
$event = tb_get_eventbrite_event($this_event_id, $token);
$mentor = tb_get_mentor_by_eventbrite_id($event['organizer_id']);
$venue = tb_get_eventbrite_venue($event['venue_id'], $token);

$nicestartdate = date("m/d/Y", strtotime(explode('T', $event['start']['local'])[0]));
$niceenddate = date("m/d/Y", strtotime(explode('T', $event['end']['local'])[0]));

$nicestarttime = date("H:i A", strtotime(explode('T', $event['start']['local'])[1]));
$niceendtime = date("H:i A", strtotime(explode('T', $event['end']['local'])[1]));

$featured_image_url = the_post_thumbnail_url();
?>

<div class="page-header-container header-edit-event">
	<div class="narrow">
		<h2>Edit Event: <? echo $event['name']['text']; ?></h2>
	</div>
</div>

<div class="narrow edit-event">
<form method="POST">
<label>Event Name<br /><input type="text" value="<?echo $event['name']['text'];?>" name="edit-event-name"></label>

<label>Event Description<br /><textarea name="edit-event-desc" rows=8><?echo $event['description']['text'];?></textarea></label>

<label class="half">Start Date<br /><input type="text" class="datepicker" name="edit-event-startdate" value="<?echo $nicestartdate; ?>" /></label>

<label class="half">Start Time<br /><input value="<?echo $nicestarttime; ?>" type="text" class="timepicker" name="edit-event-starttime" /></label>

<label class="half">End Date<br /><input value="<?echo $niceenddate; ?>" type="text" class="datepicker" name="edit-event-enddate" /></label>

<label class="half">End Time<br /><input value="<?echo $niceendtime; ?>" type="text" class="timepicker" name="edit-event-endtime" /></label>

<label>Venue Name<br /><input type="text" name="edit-event-venue-name" value="<? echo $venue['name'];?>" /></label>

<label>Venue Address<br /><input value="<? echo $venue['address']['address_1'];?>" type="text" name="edit-event-venue-address" /></label>

<label class="half">Venue City<br /><input value="<? echo $venue['address']['city'];?>" type="text" name="edit-event-venue-city" /></label>

<label class="half">Venue Province<br /><input value="<? echo $venue['address']['region'];?>" type="text" name="edit-event-venue-province" /></label>

<label>How many tickets are available?<br /><input value="<? echo $event['capacity'];?>" type="number" name="edit-event-ticket-quantity" /></label>

<?
if(has_post_thumbnail()) {
?>
<img src="<?echo $featured_image_url; ?>" />
<?
}
?>

<label>Choose an image for the event<br /><input type="file" name="edit-event-image" /></label>

<div>
<input type="submit" name="edit-event-submit" class="action-btn" value="Edit Event" />
<a class="cancel-btn" href='/mentor-dashboard/'>Cancel</a>
</div>
<div>
<form method="POST" id="delete-event-form">
<input type="hidden" name="delete-event-id" value="<?echo $event['id'];?>">
<input style="background: transparent; border: none; outline: none; font-family: inherit; font-size: 1.5rem; color: red; cursor: pointer;" name="delete-event-submit" type="submit" value="Delete Event">
</div>
</form>
</div>

<?
get_footer();
?>