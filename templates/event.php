<?
/*  Template Name:  Single Event  */

//  Check if the GET argument has been set.  If not, redirect to home.  

if(!isset($_GET['event_id'])) {
	header('Location: //tryopolis.com');
}
else {
	$GLOBALS['event_id'] = $_GET['event_id'];
}

get_header();

$event = tb_theme_get_event_by_id($GLOBALS['event_id']);
var_dump($event);

get_footer();
?>