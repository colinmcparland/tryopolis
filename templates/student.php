<?
/*  Template Name: Student Login  */
include('role-redirects/student-login.php');
get_header();
?>

<a name="signin"></a>
<div class="page-header-container header-students">
	<div class="narrow">
		<form id="student-login" method="POST">
			<h2>Student Login</h2>
			<span>Welcome back</span>
			<input required type="email" placeholder="email" type="email" name="student-login-email">
			<input type="password" placeholder="password" type="password" name="student-login-password">
			<div class="submitplaceholder">Login</div>
			<input type="submit" style="display: none;" name="student-login-submit" />
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
			<h2>Step outside of your classroom into the world beyond.  Expamnd your knowledge through hands-on experience!</h2>
			<a href="#">Learn More</a>
		</div>
		<div class="col">
			<img src='/wp-content/uploads/2017/06/woman.png' />
		</div>
	</div>
	<div class="row">
		<div class="col">
			<img src='/wp-content/uploads/2017/06/woman.png' />
		</div>
		<div class="col">
			<h2>Let your personal interests and curiosity drive your exploration and learning.  It's fun!</h2>
		</div>
	</div>
</div>


<div class="main-content narrow">
<h2 style="text-align: center;">There's no pressure to know what you want to do in life.  Try many things!</h2>
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