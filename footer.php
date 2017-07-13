<a name="footer-form"></a>
<div class="formarea">
	<form class="narrow footer-form" method="POST">
		<h2>Any More Questions?</h2>
		<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</p>
		<div class="flex-left">
			<div class="inputarea">
				<label>First Name:</label>
				<input required type="text" name="footer-fname" />
			</div>
			<div class="inputarea">
				<label>Last Name:</label>
				<input required type="text" name="footer-lname" />
			</div>
			<div class="inputarea">
				<label>Email:</label>
				<input required type="email" name="footer-email" />
			</div>
			<!-- <div class="inputarea">
				<input type="checkbox" />
				<label class="forcheckbox">&nbsp;Send Tryopolis newsletters to my inbox!</label>
			</div> -->
		</div>
		<div class="flex-right">
			<textarea placeholder="Your message here..." rows='7' name="footer-message"></textarea>
		<!-- 	<div class="g-recaptcha" data-sitekey="6LeQLCIUAAAAABQ997HwTX7h5h9VByvKw46PjOwf"></div> -->
			<div class="submitplaceholder">Submit</div>
			<input type="submit" style="display: none;" name="footer-form-submit" />
		</div>
	</form>
	<div class="social narrow">
		<span>Find and like us elsewhere on the web by clicking the links below</span>
		
		<div class="fb-like" data-href="https://developers.facebook.com/docs/plugins/" data-layout="button_count" data-action="like" data-size="large" data-show-faces="true" data-share="true"></div>
		<a href="https://twitter.com/frittershop" class="twitter-follow-button" data-show-count="false">Follow @frittershop</a><script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
		<a href="https://www.instagram.com/frittershop/" target="_blank"><img src="/wp-content/uploads/2017/05/follow-on-instagram.png" /></a>
	</div>	
</div>

<div class="clear"></div>
</div>
<footer id="footer" role="contentinfo">
<div id="copyright" class="narrow">
	<span>&copy; <? echo date('Y'); ?> Tryopolis</span>
	<a href='https://sixfive.co'><span>Designed by Sixfive</span></a>
</div>
</footer>
</div>
<?php wp_footer(); ?>
</body>
</html>