<?
/*  Template Name: Mentor Dashboard  */
include('role-redirects/mentor-dashboard.php');
get_header();
?>

<div class="page-header-container header-mentor-dashboard">
	<div class="narrow">
		<h2><? echo $user->first_name . ' ' . $user->last_name; ?></h2>
	</div>
</div>

<div class="mentor-dashboard">
	<a href="/new-event"><div class="action-btn">New Event</div></a>
	<a href="/my-events-mentor"><div class="action-btn">Manage My Events</div></a>
</div>

<?
get_footer();
?>