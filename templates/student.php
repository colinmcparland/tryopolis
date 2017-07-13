<?
/*  Template Name: Student Login  */
include(dirname(__FILE__) . '/../role-redirects/student-login.php');
get_header();
?>

<a name="signin"></a>
<div class="page-header-container header-students">
	<div class="narrow">
		<form id="student-login" method="POST">
			<h2>Student Login</h2>
			<span>Welcome back</span>
			<input required type="email" placeholder="email" type="email" name="student-login-email">
			<input required type="password" placeholder="password" type="password" name="student-login-password">
			<div class="submitplaceholder">Login</div>
			<input type="submit" style="display: none;" name="student-login-submit" />
			<?
			if($loginerr == 1) {
				?>
				<p>There was an error logging you in.  Please check your credentials and try again.</p>
				<?
			}
			?>
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

<div class="students-rows narrow">
	<div class="row">
		<div class="col">
			<? echo get_field("first_row_text"); ?>
			<a href="#">Learn More</a>
		</div>
		<div class="col">
			<img src='/wp-content/uploads/2017/07/Student01.png' />
		</div>
	</div>
	<div class="row">
		<div class="col">
			<img src='/wp-content/uploads/2017/07/Student02.png' />
		</div>
		<div class="col">
			<? echo get_field("second_row_text"); ?>
		</div>
	</div>
</div>


<div class="main-content narrow">
<? echo get_field("call_out_text"); ?>
<a name="signup"></a>
<div class="student-signup-box">
	<h2>Sign Up Now</h2>
	<div class="flexcol">
		<form method="POST">
			<h3>Create Account</h3>
			<input required name="student-signup-fname" type="text" placeholder="first name">
			<input required name="student-signup-lname" type="text" placeholder="last name">
			<input type="text" placeholder="school">
			<input required name="student-signup-email" type="email" placeholder="email">
			<input required name="student-signup-password"  type="password" placeholder="password">
			<input required name="student-signup-password-confirm" type="password" placeholder="confirm password">
			<input type="submit" name="student-signup-submit" value="Sign Up">
			<span>Already a member?  <a href="#signin">Sign in here</a></span>
		</form>
		<div>
			<h3>Login with Facebook</h3>
			<? do_action('facebook_login_button'); ?>
		</div>
	</div>
</div>
<? echo apply_filters('the_content', get_the_content()); ?>
</div>

<?
get_footer();
?>