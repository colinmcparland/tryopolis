<?
include(dirname(__FILE__) . '/../mailchimp.php');
use \DrewM\MailChimp\MailChimp;

/*  Add subscriber to the mailchimp list when footer form submitted  */
if(isset($_POST['footer-form-submit'])) {

    $mc_key = getenv("MAILCHIMP_API_KEY");

	$MailChimp = new MailChimp($mc_key);

	$list_id = '38b9776b83';

	$result = $MailChimp->post("lists/$list_id/members", [
		'email_address' => $_POST['footer-email'],
		'merge_fields' => ['FNAME'=>$_POST['footer-fname'], 'LNAME'=>$_POST['footer-lname'], 'MESSAGE'=>$_POST['footer-message']],
		'status'        => 'subscribed'
	]);
?>
	<script>
	jQuery(document).ready(function()	{
		jQuery(".footer-form .submitplaceholder").text("Thank you!  Your message has been sent.");
		var scrolllocation = jQuery(".footer-form").offset().top;
		jQuery("html, body").animate({
			scrollTop: scrolllocation
		})
	})
	</script>
<?
}


/*  Register a mentor from the sign up box  */
if(isset($_POST['mentor-signup-submit'])) {
	$fname = $_POST['mentor-signup-fname'];
	$lname = $_POST['mentor-signup-lname'];
	$email = $_POST['mentor-signup-email'];
	$password = $_POST['mentor-signup-password'];

	/*  Create the user  */
	$user_id = wp_create_user($email, $password, $email);

	/*  If the user already exists, show error message  */
	if(is_wp_error($user_id)) {
		?>
	<script>
	jQuery(document).ready(function()	{
		if(jQuery(".signup-err").length == 0) {
			jQuery("<p class='signup-err'>There seems to be an error creating your account.  Are you sure you're not already registered?</p>").insertAfter(".mentor-signup-box form input[name=mentor-signup-password-confirm]");
		}
		var scrolllocation = jQuery(".mentor-signup-box").offset().top;
		jQuery("html, body").animate({
			scrollTop: scrolllocation
		})
	})
	</script>
		<?
	}

	$update_args = array(
			"ID" => $user_id, 
			"role" => "mentor",
			"first_name" => $fname,
			"last_name" => $lname
		);

	wp_update_user($update_args);

	/*  Check if the persons first and last name is already an organizer in Event Brite.  */
	$name = $fname . ' ' . $lname;
	$token = getenv("EB_PERSONAL_TOKEN");
	$exists_flag = 0;

	$url = 'https://www.eventbriteapi.com/v3/users/me/organizers/?token=' . $token;

	$cURL = curl_init();

	curl_setopt($cURL, CURLOPT_URL, $url);
	curl_setopt($cURL, CURLOPT_HTTPGET, true);
	curl_setopt($cURL, CURLOPT_RETURNTRANSFER, 1);

	curl_setopt($cURL, CURLOPT_HTTPHEADER, array(
	 	'Content-Type: application/json',
	 	'Accept: application/json'
	));

	$result = curl_exec($cURL);

	curl_close($cURL);

	$result = json_decode($result, true);

	foreach($result['organizers'] as $organizer) {
		if($organizer['name'] == $name) {
			$exists_flag = 1;
		}
	}

	if($exists_flag == 0) {
		/*  Add the new organizer to EventBrite  */
		$organizer = tb_new_eventbrite_organizer($name, $token, $user_id);

		/*  Add as a new organizer in the database  */
		$new_organizer = array(
			'name' => $name,
			'description' => $user->description,
			'wp_id' => $user_id,
			'id' =>  $organizer['id']
		);

		tb_add_organizer($new_organizer);	

		/*  Log the user in  */
		wp_set_current_user($user_id);
    	wp_set_auth_cookie($user_id);

    	wp_safe_redirect('//' . $_SERVER['HTTP_HOST'] . '/mentor-dashboard/');

	}
	else if($exists_flag == 1) {
		/*  Remove the user we just created, since they already exist in EB.  */
		// wp_delete_user($user_id);
		?>
	<script>
	jQuery(document).ready(function()	{
		if(jQuery(".signup-err").length == 0) {
			jQuery("<p class='signup-err'>There seems to be an error creating your account.  Are you sure you're not already registered?</p>").insertAfter(".mentor-signup-box form input[name=mentor-signup-password-confirm]");
		}
		var scrolllocation = jQuery(".mentor-signup-box").offset().top;
		jQuery("html, body").animate({
			scrollTop: scrolllocation
		})
	})
	</script>
		<?

	}

}



/*  Register a student from the sign up box  */
if(isset($_POST['student-signup-submit'])) {
	$fname = $_POST['student-signup-fname'];
	$lname = $_POST['student-signup-lname'];
	$email = $_POST['student-signup-email'];
	$password = $_POST['student-signup-password'];

	$user_id = wp_create_user($email, $password, $email);

	if(is_wp_error($user_id)) {
		?>
	<script>
	jQuery(document).ready(function()	{
		if(jQuery(".signup-err").length == 0) {
			jQuery("<p class='signup-err'>There seems to be an error creating your account.  Are you sure you're not already registered?</p>").insertAfter(".student-signup-box form input[name=student-signup-password-confirm]");
		}
		var scrolllocation = jQuery(".student-signup-box").offset().top;
		jQuery("html, body").animate({
			scrollTop: scrolllocation
		})
	})
	</script>
		<?
	}
	else {
		$update_args = array(
				"ID" => $user_id, 
				"role" => "student",
				"first_name" => $fname,
				"last_name" => $lname
			);

		wp_update_user($update_args);

		/*  Log the user in  */
		wp_set_current_user($user_id);
		wp_set_auth_cookie($user_id);

		header('Location: //' . $_SERVER['HTTP_HOST'] . '/student-dashboard/');
	}
}




/*  Add a new event to Eventbrite and Wordpress.  */
if(isset($_POST['new-event-submit'])) {
	
	$name = $_POST['new-event-name'];
	$desc = $_POST['new-event-desc'];
	$organizer_id = tb_get_mentor_by_wp_id(get_current_user_ID())[0]['id'];

	/*  Format timezone, dates and times so they play nice with EB API  */
	$timezone = $_POST['new-event-timezone'];
	str_replace(":", ".", $timezone);
	str_replace("+", "", $timezone);
	$timezone = timezone_name_from_abbr('', $timezone*3600, 0);

	$startdate = $_POST['new-event-startdate'];
	$startdate_arr = explode('/', $startdate);
	$startdate = $startdate_arr[2] . '-' . $startdate_arr[0] . '-' . $startdate_arr[1];

	$starttime = $_POST['new-event-starttime'];
	$starttime = date("H:i:s", strtotime($starttime));

	$enddate = $_POST['new-event-enddate'];
	$enddate_arr = explode('/', $enddate);
	$enddate = $enddate_arr[2] . '-' . $enddate_arr[0] . '-' . $enddate_arr[1];

	$endtime = $_POST['new-event-endtime'];
	$endtime = date("H:i:s", strtotime($endtime));

	$startdatetime = $startdate . "T" . $starttime . "Z";
	$enddatetime = $enddate . "T" . $endtime . "Z";

	$event = array(
		'name' => $name,
		'desc' => $desc,
		'timezone' => $timezone,
		'start' => $startdatetime,
		'end' => $enddatetime,
		'currency' => 'CAD'
	);

	$token = getenv("EB_PERSONAL_TOKEN");

    /*  Add the venue to EB  */
    $venue = array(
    	'name' => $_POST['new-event-venue-name'],
    	'address' => $_POST['new-event-venue-address'],
    	'city' => $_POST['new-event-venue-city'],
    	'prov' => $_POST['new-event-venue-province']
    );

    $venue_id = tb_add_eventbrite_venue($venue, $token);

    var_dump($venue_id);

    $venue_id = $venue_id['id'];

	/*  Add the event to EB  */
	$event = tb_new_eventbrite_event($event, $organizer_id, $venue_id, $token);

	/*  Add tickets to the event  */
	$quantity = $_POST['new-event-ticket-quantity'];
	$newevent = tb_add_eventbrite_event_tickets($event['id'], $token, $quantity);

	/*  Publish the event!  */
	tb_publish_eb_event($token, $event['id']);

	/*  Add post to Wordpress */
	$post = array(
        'post_title' => $event['name']['text'],
        'post_author' => get_current_user_id(),
        'post_type' => 'tb_event',
        'post_status' => 'publish'
    );
    $postID = wp_insert_post( $post );

    /*  Add image to the post  */
    if ( ! function_exists( 'wp_handle_upload' ) ) {
    	require_once(ABSPATH . 'wp-admin/includes/media.php');
		require_once(ABSPATH . 'wp-admin/includes/file.php');
		require_once(ABSPATH . 'wp-admin/includes/image.php');
	}

    /*  Upload the image to WP  */
   	$newfile = wp_handle_upload($_FILES['new-event-image'], array('test_form' => false));

   	/*  Associate the image with the new post  */
   	$finishedfile = media_sideload_image($newfile['url'], $postID);

   	$attachments = get_posts(array('numberposts' => '1', 'post_parent' => $postID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC'));

   	if(sizeof($attachments) > 0){
    	set_post_thumbnail($postID, $attachments[0]->ID);
	}  

	/*  Add the event to Wordpress database  */
	tb_add_event_to_db($event['id'], $organizer_id, $postID);

	/*  Take user to the new event  */
	wp_safe_redirect(get_the_permalink($postID));

}



/*  Update an eventbrite event */
if(isset($_POST['edit-event-submit'])) {
	if(isset($_GET['id'])) {
		$event_id = $_GET['id'];
	}
	else {
		wp_safe_redirect('//' . $_SERVER['HTTP_HOST']);
	}
	$token = getenv("EB_PERSONAL_TOKEN");
	$this_event_id = tb_get_event_by_wp_id($event_id)[0]['id'];
	$event = tb_get_eventbrite_event($this_event_id, $token);
	$mentor = tb_get_mentor_by_eventbrite_id($event['organizer_id']);
	$venue = tb_get_eventbrite_venue($event['venue_id'], $token);
	
	$nicestartdate = date("m/d/Y", strtotime(explode('T', $event['start']['utc'])[0])	);
	$niceenddate = date("m/d/Y", strtotime(explode('T', $event['end']['utc'])[0]));
	
	$nicestarttime = date("H:i A", strtotime(explode('T', $event['start']['utc'])[1])	);
	$niceendtime = date("H:i A", strtotime(explode('T', $event['end']['utc'])[1]));


	/*  Format date and time to EB acceptable format  */
	$startdate = $_POST['edit-event-startdate'];
	$startdate_arr = explode('/', $startdate);
	$startdate = $startdate_arr[2] . '-' . $startdate_arr[0] . '-' . $startdate_arr[1];

	$starttime = $_POST['edit-event-starttime'];
	$starttime = date("H:i:s", strtotime($starttime));

	$enddate = $_POST['edit-event-enddate'];
	$enddate_arr = explode('/', $enddate);
	$enddate = $enddate_arr[2] . '-' . $enddate_arr[0] . '-' . $enddate_arr[1];

	$endtime = $_POST['edit-event-endtime'];
	$endtime = date("H:i:s", strtotime($endtime));

	$startdatetime = $startdate . "T" . $starttime . "Z";
	$enddatetime = $enddate . "T" . $endtime . "Z";


	/*  Build the query and update the event  */
	$new_event = array(
		'id' => $event['id'],
		'name' => $_POST['edit-event-name'],
		'desc' => htmlentities($_POST['edit-event-desc']),
		'start' => $startdatetime,
		'end' => $enddatetime,
		'timezone' => $event['start']['timezone']
	);

	tb_update_eventbrite_event($new_event, $token);


	/*  Build the query and update the venue  */
	$new_venue = array(
		'id' => $venue['id'],
		'name' => $_POST['edit-event-venue-name'],
		'address' => $_POST['edit-event-venue-address'],
		'city' => $_POST['edit-event-venue-city'],
		'province' => $_POST['edit-event-venue-province']
	);

	tb_update_eventbrite_venue($new_venue, $token);

	/*  TODO: Build the query and update the tickets  */

	/*  Edit the event image  */

    if ( ! function_exists( 'wp_handle_upload' ) ) {
    	require_once(ABSPATH . 'wp-admin/includes/media.php');
		require_once(ABSPATH . 'wp-admin/includes/file.php');
		require_once(ABSPATH . 'wp-admin/includes/image.php');
	}

    /*  Upload the image to WP  */
   	$newfile = wp_handle_upload($_FILES['edit-event-image'], array('test_form' => false));

   	/*  Associate the image with the new post  */
   	$finishedfile = media_sideload_image($newfile['url'], $event_id);

   	$attachments = get_posts(array('numberposts' => '1', 'post_parent' => $event_id, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC'));

   	if(sizeof($attachments) > 0){
    	set_post_thumbnail($event_id, $attachments[0]->ID);
	}  


	wp_safe_redirect(get_post_permalink($event_id));

}



/*  Delete an event from EB and Wordpress  */
if(isset($_POST['delete-event-submit'])) {
	$token = getenv("EB_PERSONAL_TOKEN");

	$deletereq = tb_delete_eventbrite_event($_POST['delete-event-id'], $token);

	wp_delete_post($_GET['id']);

	/*  Delete it from the database  */
	tb_delete_event($event_id);

	wp_safe_redirect("/my-events-mentor");
}




/*  Register a student for an event  */
if(isset($_POST['register-now-submit'])) {
	$token = getenv("EB_PERSONAL_TOKEN");

	/*  Add an RSVP for the student  */
	$student_id = get_current_user_ID();
	$event_id = get_the_ID();
	$eb_event = tb_get_event_by_wp_id($event_id)[0];

	$registered = tb_add_rsvp($student_id, $eb_event['id']);

	/*  Deduct the available tickets on EB  */
	if($registered == 0) {
		$eb_event = tb_get_event_by_wp_id($event_id)[0];
		tb_update_tickets($eb_event['id'], $token, 'decrement');
	}
}




/*  Deregister a student for an event  */
if(isset($_POST['deregister-now-submit'])) {
	$token = getenv("EB_PERSONAL_TOKEN");

	/*  Remove an RSVP for the student  */
	$student_id = get_current_user_ID();
	$event_id = get_the_ID();
	$eb_event = tb_get_event_by_wp_id($event_id)[0];

	$registered = tb_remove_rsvp($student_id, $eb_event['id']);

	/*  Increment the available tickets on EB  */
	if($registered == 0) {
		$eb_event = tb_get_event_by_wp_id($event_id)[0];
		tb_update_tickets($eb_event['id'], $token, 'increment');
	}
}


/*  Log in a student  */
if(isset($_POST['student-login-submit'])) {
	$user = get_user_by('email', $_POST['student-login-email']);
	$hash = wp_hash_password( $_POST['student-login-password'] );
	
	$auth = wp_authenticate($user->user_login, $_POST['student-login-password']);

	if(!is_wp_error($auth)) {
		wp_set_current_user($user->id);
    	wp_set_auth_cookie($user->id);
    	wp_safe_redirect("//" . $_SERVER['HTTP_HOST'] . "/student-dashboard");
	}


}


/*  Log in a mentor  */
if(isset($_POST['mentor-login-submit'])) {
	$user = get_user_by('email', $_POST['mentor-login-email']);
	$hash = wp_hash_password( $_POST['mentor-login-password'] );
	
	$auth = wp_authenticate($user->user_login, $_POST['mentor-login-password']);

	if(!is_wp_error($auth)) {
		wp_set_current_user($user->id);
    	wp_set_auth_cookie($user->id);
    	wp_safe_redirect("//" . $_SERVER['HTTP_HOST'] . "/mentor-dashboard/");
	}

}

?>