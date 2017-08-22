<h1>Student Records</h1>
<table>
	<tr>
		<th>Student Name</th>
		<th>Event</th>
		<th>Present</th>
	</tr>
	<tr>
	<tr>
<?
$rsvps = tb_get_all_rsvp();
$token = getenv("EB_PERSONAL_TOKEN");

foreach ($rsvps as $rsvp) :

	$student = get_userdata($rsvp['student_id']);
	$student_name = $student->first_name . ' ' . $student->last_name;

	$event = tb_get_eventbrite_event($rsvp['event_id'], $token);
	$event_name = $event['name']['text'];
	$event_link = tb_get_event_by_id($rsvp['event_id'])[0]['post'];
	$event_link = get_post_permalink($event_link);

	$confirmed = !empty($rsvp['confirmed']);
	$confirmed_html = '';
	if($confirmed == FALSE) {
		$confirmed_html = 'Present';
	} 
	else if($confirmed == TRUE) {
		$confirmed_html = 'Not Present';
	}

?>
		<td><? echo $student_name; ?></td>
		<td><a target="_blank" href="<? echo $event_link; ?>"><? echo $event_name; ?></a></td>
		<td><? echo $confirmed_html; ?></td>

<?

endforeach;

?>