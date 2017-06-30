<?
/*  Template Name: Mentors Login  */
include('role-redirects/mentor-login.php');
get_header();
?>

<a name="signin"></a>
<div class="page-header-container header-mentors">
	<div class="narrow">
		<form id="mentor-login-form" method="POST">
			<h2>Mentor Login</h2>
			<span>Welcome back</span>
			<input type="email" placeholder="email" required name="mentor-login-email">
			<input required type="password" placeholder="password" name="mentor-login-password">
			<div class="submitplaceholder">Login</div>
			<input type="submit" style="display: none;" name="mentor-login-submit"/>
			<span>Don't have an account?  <a href="#signup">Sign up here!</a></span>
		</form>
		<img src='/wp-content/uploads/2017/06/woman.png' />
	</div>
</div>

<div class="icons narrow">
	<img src="/wp-content/uploads/2017/05/icon1.png" />
	<img src="/wp-content/uploads/2017/05/icon2.png" />
	<img src="/wp-content/uploads/2017/05/icon3.png" />
	<img src="/wp-content/uploads/2017/05/icon4.png" />
	<img src="/wp-content/uploads/2017/05/icon5.png" />
	<img src="/wp-content/uploads/2017/05/icon6.png" />
</div>

<div class="main-content narrow">
<? echo apply_filters('the_content', the_field('upper_content')); ?>

<a name="signup"></a>
<div class="mentor-signup-box" name="mentor-signup-box">
	<h2>Sign Up Now</h2>
	<div class="flexcol">
		<form method="POST">
			<h3>Create Account</h3>
			<input required name="mentor-signup-fname" type="text" placeholder="first name">
			<input required name="mentor-signup-lname" type="text" placeholder="last name">
			<input type="text" placeholder="school">
			<input required name="mentor-signup-email" type="email" placeholder="email">
			<input required name="mentor-signup-password"  type="password" placeholder="password">
			<input required name="mentor-signup-password-confirm" type="password" placeholder="confirm password">
			<input type="submit" name="mentor-signup-submit" value="Sign Up">
			<span>Already a member?  <a href="#signin">Sign in here</a></span>
		</form>
		<div>
			<h3>Login with Facebook</h3>
			<? do_action('facebook_login_button'); ?>
		</div>
	</div>
</div>


<? echo apply_filters('the_content', the_field('lower_content')); ?>
</div>

<?
get_footer();
?>