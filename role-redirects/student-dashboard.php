<?
the_post();
$user = get_userdata(get_current_user_ID());
if(!is_null($user->roles) && !in_array('student', $user->roles) && in_array('mentor', $user->roles)) {
	header('Location: //' . $_SERVER['HTTP_HOST'] . '/mentor-dashboard/');
}
else if(!in_array('student', $user->roles) && !in_array('administrator', $user->roles)) {
	header('Location: //' . $_SERVER['HTTP_HOST']);
}
?>