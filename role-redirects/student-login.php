<?
the_post();
$user = get_userdata(get_current_user_ID());
if(!is_null($user->roles) && in_array('student', $user->roles) && is_user_logged_in()) {
	wp_safe_redirect('//' . $_SERVER['HTTP_HOST'] . '/student-dashboard/');
}
else if(!is_null($user->roles) && in_array('mentor', $user->roles) && is_user_logged_in()) {
	wp_safe_redirect('//' . $_SERVER['HTTP_HOST'] . '/mentor-dashboard/');
}
else if(isset($_POST['student-login-submit']) && is_null($user->roles)) {
	$loginerr = 1;
}
?>