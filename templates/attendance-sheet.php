<?
/*  Template Name:  Attendance Sheet  */
get_header();

if(isset($_GET['id'])) {
	$event_id = $_GET['id'];
	$wp_attendees = tb_get_wp_attendees($event_id);
	$event_title = get_the_title($event_id);
} 
else {
	wp_safe_redirect("/mentor-dashboard");
}
?>
<div class="page-header-container header-attendance">
	<div class="narrow">
		<h2>Attendance Sheet</h2>
		<h3><? echo $event_title; ?></h3>
	</div>
</div>

<div class="events events-bg-container">
	<div class="wide attendance">
		<form method="POST">
			<table>
			<tr>
				<th>Student Name</th>
				<th>In Attendance</th>
				<th>Absent</th>
			</tr>
			
			<?
			foreach($wp_attendees as $attendee) {
				$username = get_userdata($attendee['student_id']);
				$is_rsvp = $attendee['confirmed'];
			?>
			<tr>
				<td>
					<? echo $username->first_name . ' ' . $username->last_name; ?>
				</td>
				<?
				if(empty($is_rsvp)) {
				?>
				<td>
					<input name="student-<? echo $attendee['student_id'] ?>" value="present" type="radio" />
				</td>
				<td>
					<input checked name="student-<? echo $attendee['student_id'] ?>" value="absent" type="radio" />
				</td>
				<?
				}
				else {
				?>
				<td>
					<input checked name="student-<? echo $attendee['student_id'] ?>" value="present" type="radio" />
				</td>
				<td>
					<input name="student-<? echo $attendee['student_id'] ?>" value="absent" type="radio" />
				</td>
				<?
				}
				?>
				</td>
			</tr>
			<?
			}
			?>
			</table>
			<input type="submit" name="attendance-sheet-submit" class="action-btn" value="Save Attendance" />
		</form>
	</div>
</div>


<?

get_footer();
?>