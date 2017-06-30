<?
/*  Template Name:  Student Dashboard  */
include('role-redirects/student-dashboard.php'); 
get_header();
?>
<div class="page-header-container header-student-dashboard">
	<div class="narrow">
		<h2><? echo $user->first_name . ' ' . $user->last_name; ?></h2>
	</div>
</div>

<div class="student-dashboard">
	<a href="/all-events"><div class="action-btn">All Events</div></a>
	<a href="/my-events-student"><div class="action-btn">My Events</div></a>
</div>

<?
get_footer();
?>