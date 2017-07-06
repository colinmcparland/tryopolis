<?
/*  Setup variables  */
include("variable_setup.php");

add_action( 'wp_enqueue_scripts', 'theme_enqueue_custom_scripts' );	
function theme_enqueue_custom_scripts()	{

	$template_url = get_stylesheet_directory_uri();

	wp_register_script( 'main-script',  $template_url.'/js/main.js', 'jquery', "1", true);

	wp_enqueue_script( 'main-script' );

	wp_register_script( 'slick-custom-script',  $template_url.'/js/slick-custom.js', 'jquery', "1", true);

	wp_enqueue_script( 'slick-custom-script' );

	wp_register_script( 'slick', '//cdn.jsdelivr.net/jquery.slick/1.6.0/slick.min.js', 'jquery', '1', true);

	wp_enqueue_script( 'slick' );

	wp_register_style('jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css');
	wp_enqueue_style('jquery-ui');

	wp_register_script('jquery-ui-script', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js');

	wp_enqueue_script('jquery-ui-script');

	wp_register_style('jquery-timepicker', '//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css');
	wp_enqueue_style('jquery-timepicker');

	wp_register_script('jquery-timepicker-script', '//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js');

	wp_enqueue_script('jquery-timepicker-script');

}

/*  Get all the events from the database and return each row in an array.  */
function tb_get_all_events() {
	global $wpdb;
	$querystr = "SELECT * FROM wp_tinybird_events";
	$results = $wpdb->get_results( $querystr, "ARRAY_A" );
	return $results;
}

/*  Get event by Eventbrite ID  */
function tb_get_event_by_id($id) {
	global $wpdb;
	$id = htmlspecialchars($id);
	$querystr = "SELECT * FROM wp_tinybird_events WHERE id='$id'";
	$results = $wpdb->get_results( $querystr, "ARRAY_A" );
	return $results;
}

/*  Get event by Wordpress ID  */
function tb_get_event_by_wp_id($id) {
	global $wpdb;
	$id = htmlspecialchars($id);
	$querystr = "SELECT * FROM wp_tinybird_events WHERE post='$id'";
	$results = $wpdb->get_results( $querystr, "ARRAY_A" );
	return $results;
}

/*  Get mentor by eventbrite-assigned ID  */
function tb_get_mentor_by_eventbrite_id($id) {
	global $wpdb;
	$id = htmlspecialchars($id);
	$querystr = "SELECT * FROM wp_tinybird_mentors WHERE id='$id'";
	$results = $wpdb->get_results( $querystr, "ARRAY_A" );
	return $results;
}

/*  Get mentor by WP user ID  */
function tb_get_mentor_by_wp_id($id) {
	global $wpdb;
	$id = htmlspecialchars($id);
	$querystr = "SELECT * FROM wp_tinybird_mentors WHERE wp_id='$id'";
	$results = $wpdb->get_results( $querystr, "ARRAY_A" );
	return $results;
}

/*  When a new facebook user is created, give them the appropriate role.  */
add_action( 'user_register', 'tb_theme_assign_roles', 10, 1 );
function tb_theme_assign_roles($user_id) {

	$ref = $_SERVER['HTTP_REFERER'];
	$website = get_userdata($user_id)->user_url;
	$role = '';

	/*  Check if the user was registered through Facebook by checking the website.  */
	if(strpos($website, 'facebook.com/app_scoped_user_id') !== false) {
		if(strpos($ref, 'students') !== false) {
			$role = 'student';

			$update_args = array(
				"ID" => $user_id, 
				"role" => $role
			);

			wp_update_user($update_args);
		}
		else if(strpos($ref, 'mentors') !== false) {
			$role = 'mentor';
			$update_args = array(
				"ID" => $user_id, 
				"role" => $role
			);

			wp_update_user($update_args);


			/*  Add the new organizer to EventBrite  */
			$user = get_userdata($user_id);
			$name = $user->first_name . ' ' . $user->last_name;
			$token = getenv("EB_PERSONAL_TOKEN");

			$organizer = tb_new_eventbrite_organizer($name, $token, $user_id);

			/*  Add as a new organizer in the database  */
			$new_organizer = array(
				'name' => $name,
				'description' => $user->description,
				'wp_id' => $user_id,
				'id' =>  $organizer['id']
			);

			tb_add_organizer($new_organizer);	

		}
	}

}


/*  Create a new organizer in EventBrite  */
function tb_new_eventbrite_organizer($name, $token, $id) {

	$url = 'https://www.eventbriteapi.com/v3/organizers/?token=' . $token;

	$cURL = curl_init();

	$postfields = array("organizer.name" => $name);
	$postfields = json_encode($postfields);

	curl_setopt($cURL, CURLOPT_URL, $url);
	curl_setopt($cURL, CURLOPT_POST, true);
	curl_setopt($cURL, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($cURL, CURLOPT_POSTFIELDS, $postfields);

	curl_setopt($cURL, CURLOPT_HTTPHEADER, array(
    	'Content-Type: application/json',
    	'Accept: application/json'
	));

	$result = curl_exec($cURL);

	curl_close($cURL);

	$result = json_decode($result, true);

	return $result;
}

/*  Function to add a new organizer to the database.  */
function tb_add_organizer($organizer) {
	global $wpdb;
	$id = htmlspecialchars($organizer['id']);
	$wp_id = htmlspecialchars($organizer['wp_id']);
	$desc = htmlspecialchars($organizer['description']);
	$name = htmlspecialchars($organizer['name']);

	$querystr = "INSERT INTO wp_tinybird_mentors (id, wp_id, name, description) VALUES ('$id', '$wp_id', '$name', '$desc')";
	$wpdb->query($querystr);	
}


// facebook social plugin remove on wp admin login page
add_filter("fbl/bypass_registration_disabled", "__return_true");

add_action( 'init', function() {
	global $fbl;
    remove_action('login_form', array( $fbl->fbl, 'print_button'), 10);
 	remove_action('login_form', array( $fbl->fbl, 'add_fb_scripts'), 10);
});


/*  Remove wp-admin access for mentors and students */
function tb_no_admin_access()
{
    $redirect = isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : home_url( '/' );
    if ( 
        current_user_can( 'mentor' )
        OR current_user_can( 'student' )
    )
        exit( wp_redirect( $redirect ) );
}
add_action( 'admin_init', 'tb_no_admin_access', 100 );

// add_action('after_setup_theme', 'remove_admin_bar');

// function remove_admin_bar() {
// if (current_user_can('mentor') || current_user_can('mentor') || !is_admin()) {
//   show_admin_bar(false);
// }
// }



/*  Add a new event to eventbrite  */
function tb_new_eventbrite_event($event, $organizer_id, $venue_id, $token) {

	$url = 'https://www.eventbriteapi.com/v3/events/?token=' . $token;

	$cURL = curl_init();

	$postfields = array(
		"event.name.html" => $event['name'],
		"event.description.html" => $event['desc'],
		"event.organizer_id" => $organizer_id,
		"event.start.utc" => $event['start'],
		"event.end.utc" => $event['end'],
		"event.start.timezone" => $event['timezone'],
		"event.end.timezone" => $event['timezone'],
		"event.currency" => $event['currency'],
		"event.venue_id" => $venue_id
	);
	
	$postfields = json_encode($postfields);

	curl_setopt($cURL, CURLOPT_URL, $url);
	curl_setopt($cURL, CURLOPT_POST, true);
	curl_setopt($cURL, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($cURL, CURLOPT_POSTFIELDS, $postfields);

	curl_setopt($cURL, CURLOPT_HTTPHEADER, array(
    	'Content-Type: application/json',
    	'Accept: application/json'
	));

	$result = curl_exec($cURL);

	curl_close($cURL);

	$result = json_decode($result, true);

	return $result;

	die();
}

/*  Add a ticket to the event  */
function tb_add_eventbrite_event_tickets($event_id, $token, $quantity) {

	$url = 'https://www.eventbriteapi.com/v3/events/' . $event_id . '/ticket_classes/?token=' . $token;

	$cURL = curl_init();

	$postfields = array(
		"ticket_class.name" => 'Tickets',
		"ticket_class.free" => true,
		"ticket_class.quantity_total" => $quantity
	);
	
	$postfields = json_encode($postfields);

	curl_setopt($cURL, CURLOPT_URL, $url);
	curl_setopt($cURL, CURLOPT_POST, true);
	curl_setopt($cURL, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($cURL, CURLOPT_POSTFIELDS, $postfields);

	curl_setopt($cURL, CURLOPT_HTTPHEADER, array(
    	'Content-Type: application/json',
    	'Accept: application/json'
	));

	$result = curl_exec($cURL);

	curl_close($cURL);

	$result = json_decode($result, true);

	return $result;

	die();
}


/*  Add a new venue to eventbrite  */
function tb_add_eventbrite_venue($venue, $token) {
	$url = 'https://www.eventbriteapi.com/v3/venues/?token=' . $token;

	$cURL = curl_init();

	$postfields = array(
		"venue.name" => $venue['name'],
		"venue.address.address_1" => $venue['address'],
		"venue.address.city" => $venue['city'],
		"venue.address.region" => $venue['prov'],
		"venue.address.country" => "CA"
	);
	
	$postfields = json_encode($postfields);

	curl_setopt($cURL, CURLOPT_URL, $url);
	curl_setopt($cURL, CURLOPT_POST, true);
	curl_setopt($cURL, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($cURL, CURLOPT_POSTFIELDS, $postfields);

	curl_setopt($cURL, CURLOPT_HTTPHEADER, array(
    	'Content-Type: application/json',
    	'Accept: application/json'
	));

	$result = curl_exec($cURL);

	curl_close($cURL);

	$result = json_decode($result, true);

	return $result;

	die();
}

/*  Add a new event to the databse.  */
function tb_add_event_to_db($event_id, $organizer_id, $post_id) {
	global $wpdb;

	$id = htmlspecialchars($event_id);
	$organizer_id = htmlspecialchars($organizer_id);
	
	$querystr = "INSERT INTO wp_tinybird_events (id, organizer, post) VALUES ('$event_id', '$organizer_id', '$post_id')";
	$wpdb->query($querystr);
}


/*  Get an event object from EB  */
function tb_get_eventbrite_event($id, $token) {

	$url = 'https://www.eventbriteapi.com/v3/events/' . $id . '/?token=' . $token;

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

	$result_array = json_decode($result, true);

	return $result_array;
}

/*  Get a venue object from EB  */
function tb_get_eventbrite_venue($id, $token) {

	$url = 'https://www.eventbriteapi.com/v3/venues/' . $id . '/?token=' . $token;

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

	$result_array = json_decode($result, true);

	return $result_array;
}



/*  Function to update an event  */
function tb_update_eventbrite_event($event, $token) {
	$url = 'https://www.eventbriteapi.com/v3/events/' . $event['id'] . '/?token=' . $token;

	$cURL = curl_init();


	$postfields = array(
		"event.name.html" => $event['name'],
		"event.description.html" => $event['desc'],
		"event.start.utc" => $event['start'],
		"event.end.utc" => $event['end'],
		"event.start.timezone" => $event['timezone'],
		"event.end.timezone" => $event['timezone']
	);
	
	$postfields = json_encode($postfields);

	curl_setopt($cURL, CURLOPT_URL, $url);
	curl_setopt($cURL, CURLOPT_POST, true);
	curl_setopt($cURL, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($cURL, CURLOPT_POSTFIELDS, $postfields);

	curl_setopt($cURL, CURLOPT_HTTPHEADER, array(
    	'Content-Type: application/json',
    	'Accept: application/json'
	));

	$result = curl_exec($cURL);

	curl_close($cURL);

	$result = json_decode($result, true);

	return $result;

	die();

}



/*  Function to update an event in EB  */
function tb_update_eventbrite_venue($venue, $token) {
	$url = 'https://www.eventbriteapi.com/v3/venues/' . $venue['id'] . '/?token=' . $token;

	$cURL = curl_init();


	$postfields = array(
		"venue.name" => $venue['name'],
		"venue.address.address_1" => $venue['address'],
		"venue.address.city" => $venue['city'],
		"venue.address.region" => $venue['province']
	);
	
	$postfields = json_encode($postfields);

	curl_setopt($cURL, CURLOPT_URL, $url);
	curl_setopt($cURL, CURLOPT_POST, true);
	curl_setopt($cURL, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($cURL, CURLOPT_POSTFIELDS, $postfields);

	curl_setopt($cURL, CURLOPT_HTTPHEADER, array(
    	'Content-Type: application/json',
    	'Accept: application/json'
	));

	$result = curl_exec($cURL);

	curl_close($cURL);

	$result = json_decode($result, true);

	return $result;

	die();

}





/*  Function to delete an event from EB  */
function tb_delete_eventbrite_event($id, $token) {
	$url = 'https://www.eventbriteapi.com/v3/events/' . $id . '/?token=' . $token;

	$cURL = curl_init();

	$postfields = array(
		"venue.name" => $venue['name'],
		"venue.address.address_1" => $venue['address'],
		"venue.address.city" => $venue['city'],
		"venue.address.region" => $venue['province']
	);
	
	$postfields = json_encode($postfields);

	curl_setopt($cURL, CURLOPT_URL, $url);
	curl_setopt($cURL, CURLOPT_CUSTOMREQUEST, 'DELETE');
	curl_setopt($cURL, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($cURL, CURLOPT_POSTFIELDS, $postfields);

	curl_setopt($cURL, CURLOPT_HTTPHEADER, array(
    	'Content-Type: application/json',
    	'Accept: application/json'
	));

	$result = curl_exec($cURL);

	curl_close($cURL);

	$result = json_decode($result, true);

	return $result;

	die();
}




/*  Function to add an RSVP for a certain student.  */
function tb_add_rsvp($student_id, $event_id) {
	global $wpdb;
	$results = tb_is_student_rsvp($student_id, $event_id);

	if($results == 0) :
		$querystr = "INSERT INTO wp_tinybird_rsvp (student_id, event_id) VALUES ('$student_id', '$event_id')";
		$wpdb->query($querystr);
		return 0;
	else:
		return 1;
	endif;

}



/*  Function to add an RSVP for a certain student.  */
function tb_remove_rsvp($student_id, $event_id) {
	global $wpdb;
	$results = tb_is_student_rsvp($student_id, $event_id);

	if(sizeof($results) == 1) :
		$querystr = "DELETE FROM wp_tinybird_rsvp WHERE student_id='$student_id' AND event_id='$event_id'";
		$wpdb->query($querystr);
		return 0;
	endif;

}



/*  Function to update the number of tickets available for an event  */
function tb_update_tickets($event_id, $token, $action) {
	$ticket_object = tb_get_ticket_class($event_id, $token);
	$ticket_id = $ticket_object['ticket_classes'][0]['id'];
	$numleft = $ticket_object['ticket_classes'][0]['quantity_total'];
	
	if($action == 'increment') {
		$numleft++;
	}
	else if($action == 'decrement') {
		$numleft--;
	}

	$url = 'https://www.eventbriteapi.com/v3/events/' . $event_id . '/ticket_classes/' . $ticket_id . '/?token=' . $token;

	$cURL = curl_init();

	$postfields = array(
		"ticket_class.quantity_total" => $numleft
		);
	
	$postfields = json_encode($postfields);

	curl_setopt($cURL, CURLOPT_URL, $url);
	curl_setopt($cURL, CURLOPT_POST, true);
	curl_setopt($cURL, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($cURL, CURLOPT_POSTFIELDS, $postfields);

	curl_setopt($cURL, CURLOPT_HTTPHEADER, array(
    	'Content-Type: application/json',
    	'Accept: application/json'
	));

	$result = curl_exec($cURL);

	curl_close($cURL);

	$result = json_decode($result, true);

	return $result;

	die();	
}


/*  Function to get ticket classes for an eventbrite event  */
function tb_get_ticket_class($event_id, $token) {
	$url = 'https://www.eventbriteapi.com/v3/events/' . $event_id . '/ticket_classes/?token=' . $token;

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

	$result_array = json_decode($result, true);

	return $result_array;
}

/*  Check if a student is RSVPs to an event via the website  */
function tb_is_student_rsvp($student_id, $event_id) {
	global $wpdb;

	$student_id = htmlspecialchars($student_id);
	$event_id = htmlspecialchars($event_id);

	$querystr = "SELECT * FROM wp_tinybird_rsvp WHERE student_id='$student_id' AND event_id='$event_id'";
	$results = $wpdb->get_results( $querystr, "ARRAY_A" );

	if(sizeof($results) > 0) {
		return 1;
	}
	else {
		return 0;
	}
}


/*  Delete an event from the database  */
function tb_delete_event($event_id) {
	global $wpdb;

	$querystr = "DELETE FROM wp_tinybird_events WHERE event_id='$event_id'";
	$wpdb->query($querystr);

	$querystr = "DELETE FROM wp_tinybird_rsvp WHERE event_id='$event_id'";
	$wpdb->query($querystr);
}


/*  Get the password for a user  */
function tb_get_password($user_id) {
	global $wpdb;
	$querystr = "SELECT * FROM wp_users WHERE id='$user_id'";
	$results = $wpdb->get_results( $querystr, "ARRAY_A" );
	return $results[0]['user_pass'];
}

/*  Add a logout button  */
add_filter( 'wp_nav_menu_items', 'add_logout_link', 10, 2 );
function add_logout_link( $items, $args ) {
	$user = get_userdata(get_current_user_ID());

    if (in_array('student', $user->roles) && $args->menu->slug == 'main-menu') {
        $items .= '<li><a href="'. wp_logout_url('/students') .'">logout</a></li>';
    }
    elseif (in_array('mentor', $user->roles) && $args->menu->slug == 'main-menu') {
        $items .= '<li><a href="'. wp_logout_url('/mentors') .'">logout</a></li>';
    }
    elseif (in_array('administrator', $user->roles) && $args->menu->slug == 'main-menu') {
        $items .= '<li><a href="'. wp_logout_url('/home') .'">logout</a></li>';
    }
    return $items;
}


/*  Publish an event in EB  */
function tb_publish_eb_event($token, $id) {
	$url = 'https://www.eventbriteapi.com/v3/events/' . $id . '/publish/?token=' . $token;

	$cURL = curl_init();

	curl_setopt($cURL, CURLOPT_URL, $url);
	curl_setopt($cURL, CURLOPT_POST, true);
	curl_setopt($cURL, CURLOPT_RETURNTRANSFER, 1);

	curl_setopt($cURL, CURLOPT_HTTPHEADER, array(
    	'Content-Type: application/json',
    	'Accept: application/json'
	));

	$result = curl_exec($cURL);

	curl_close($cURL);

	$result_array = json_decode($result, true);

	return $result_array;

}
?>