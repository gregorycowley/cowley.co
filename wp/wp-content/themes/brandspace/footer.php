<?php $t =& peTheme(); ?>
<?php $connectBar = $t->options->get("footerExtra") === "yes"; ?>
<?php if ($connectBar): ?>
<!--connect bar-->
<section class="row-fluid connect">
	
	<!--social media icons-->
	<div class="span5 smedia">
		<span class="hand-written"><?php e__pe("Hang With Us") ?></span>
		<ul class="social-media">
			<?php $t->content->socialLinks($t->options->get("footerSocialLinks"),"footer"); ?>
		</ul>
	</div>
	   			
	<?php if ($t->options->get("newsletter")): ?>
	<!--newsletter signup form-->
	<div class="span7 newsletter" data-default="youremail@yourdomain.com" data-subscribed="<?php e__pe("Thank you for subscribing."); ?>" data-instance="options">
		<form method="get">
			<div class="form-horizontal">
				<div class="control-group">
					<label for="inputNewsletter" class="control-label hand-written"><?php e__pe("Our Newsletter"); ?></label>
					<div class="controls">
						<div class="input-append">
							<input type="text" name="email"  />
							<button class="btn btn-info" type="submit"><?php e__pe("Go!"); ?></button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
	<?php endif; ?>
	   			
</section>
<!--end connect bar-->
<?php endif; ?>

</div>
<!-- end site wrapper -->
	  
	<!--footer-->
	<footer class="container <?php echo $connectBar ? "" : "no-connect"; ?>">
	 
	    <div class="row-fluid">
			<!--footer info-->
			<div class="span5">
				<div class="widget widget_info">
					<div>
						<div class="logo-wrap">
							<img class="logo-foot" src="<?php echo $t->options->get("footerLogo"); ?>"/>
						</div>
						<?php echo $t->options->get("footerInfo"); ?>
					</div>
				</div>
			</div>
			<!--end footer info-->

			<div class="span7">
				<div class="row-fluid">
					<div class="inner-spacer-left">
						<?php $t->footer->widgets(); ?>
					</div>
				</div>
			</div>
		</div>
	     
		<!--btm color bar-->
		<div class="row-fluid btm-bar">
			<div class="span5"></div>
			<div class="span7"></div>
		</div>
	     
	</footer>
	<!--end footer-->
	<?php $t->footer->wp_footer(); ?>
    </body>
</html>