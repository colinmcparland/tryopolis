<?
/*  Template Name:  New Event  */
include(dirname(__DIR__) . '/../role-redirects/mentor-dashboard.php'); 
get_header();
?>

<div class="page-header-container header-new-event">
	<div class="narrow">
		<h2>New Event</h2>
	</div>
</div>

<div class="narrow new-event">
<form method="POST" class="dropzone" enctype="multipart/form-data">
<label>Event Name<br /><input type="text" placeholder="My Awesome Event" name="new-event-name"></label>
<label>Event Description<br /><textarea name="new-event-desc" rows=8></textarea></label>
<label>Time zone<br /><? include(dirname(__DIR__) . '/../forms/new-event-timezone-select.php'); ?></label>
<label class="half">Start Date<br /><input type="text" class="datepicker" name="new-event-startdate" /></label>
<label class="half">Start Time<br /><input type="text" class="timepicker" name="new-event-starttime" /></label>
<label class="half">End Date<br /><input type="text" class="datepicker" name="new-event-enddate" /></label>
<label class="half">End Time<br /><input type="text" class="timepicker" name="new-event-endtime" /></label>
<label>Venue Name<br /><input type="text" name="new-event-venue-name" /></label>
<label>Venue Address<br /><input type="text" name="new-event-venue-address" /></label>
<label class="half">Venue City<br /><input type="text" name="new-event-venue-city" /></label>
<label class="half">Venue Province<br /><input type="text" name="new-event-venue-province" /></label>
<label>How many tickets are available?<br /><input type="number" name="new-event-ticket-quantity" /></label>
<label>Choose an image for the event<br /><input type="file" name="new-event-image" /></label>
<div>
<input type="submit" name="new-event-submit" class="action-btn" value="Add Event" />
<a class="cancel-btn" href='/mentor-dashboard/'>Cancel</a>
</div>
</form>
</div>

<?
get_footer();
?>