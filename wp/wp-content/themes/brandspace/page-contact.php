<?php
/*
Template Name: Contact
*/
?><?php $t =& peTheme(); ?>
<?php get_header(); ?>
<?php get_template_part("tagline"); ?>
<?php $content =& $t->content; ?>

<!--main content-->
<?php while ($content->looping()): ?>

<?php $gmap =& $t->content->meta()->gmap; ?>
<?php $gmapShow = $gmap->show != "no"; ?>
<?php $contact =& $t->content->meta()->contact; ?>
<?php $image = $t->content->hasFeatImage(); ?>

<div class="row-fluid">
	
	<?php if ($gmapShow || $image): ?>
	<!-- banner-->
	<div class="span12 contact-banner">
	 
		<div class="row-fluid">
			<?php if ($image): ?>
			<!--office pic-->
			<div class="span<?php echo ($gmapShow ? 5 : 12); ?> office-image">
				<?php $content->img($gmapShow ? 420 : 940,387); ?>
				<?php echo $contact->caption; ?>
			</div>
			<?php endif; ?>

			<!--google maps-->
			<?php if ($gmapShow == "yes"): ?>
			<div class="span<?php echo $image ? 7 : "12 full" ?> gmapWrap">
				<div class="gmap" data-latitude="<?php echo $gmap->latitude; ?>" data-longitude="<?php echo $gmap->longitude; ?>" data-title="<?php echo esc_attr($gmap->title); ?>" data-zoom="<?php echo $gmap->zoom; ?>" >
					<div class="description"><?php echo $gmap->description; ?></div>
				</div>
			</div>
			<?php endif; ?>

		</div>
	 
	 
	</div>
	<!--end banner -->
	<?php endif; ?>
</div>

<div class="row-fluid">
	
	<?php get_sidebar(); ?>
	
	<!--main content-->
	<div class="span8">
	
	<!--forms--> <!--add class="noquote" to remove quote function-->
	<form method="post" class="peThemeContactForm" >
		
		<?php if ($contact->quote == "yes"): ?>
		<div id="contactType" class="bay form-horizontal">
			<?php echo $contact->quoteSelectBox; ?>
			
			<div class="control-group">
				<label for="#" class="control-label"><?php e__pe("Enquiry Type"); ?></label>
				<div class="controls">
					<label class="radio"><input type="radio" name="contactType" checked value="enquiry" /><?php e__pe("General Enquiry"); ?></label>
					<label class="radio"><input type="radio" name="contactType" value="quotation" /><?php e__pe("Quotation Request"); ?></label>
				</div>
			</div>
		</div>
		<?php endif; ?>
		
		<div id="personal" class="bay form-horizontal">
			<?php echo $contact->personalBox; ?>
				
				<!--name field-->
				<div class="control-group">
					<label for="inputName" class="control-label"><?php e__pe("Name"); ?></label>
					<div class="controls">
						<input name="author" class="required span9" type="text" id="inputName">
						<span class="help-inline"><?php e__pe("required"); ?></span>
					</div>
				</div>
				
				<!--address field-->
				<div class="control-group">
					<label for="inputName" class="control-label"><?php e__pe("Address"); ?></label>
					<div class="controls">
						<input name="address" type="text" id="inputAddress" class="span9">
						<span class="help-inline"></span>
					</div>
				</div>
				
				<!--phone field-->
				<div class="control-group">
					<label for="inputName" class="control-label"><?php e__pe("Phone"); ?></label>
					<div class="controls">
						<input name="phone" type="text" id="inputPhone" class="span9">
						<span class="help-inline"></span>
					</div>
				</div>
				
				<!--email field-->
				<div class="control-group">
					<label for="inputName" class="control-label"><?php e__pe("Email"); ?></label>
					<div class="controls">
						<input name="email" class="required span9" type="text" id="inputEmail">
						<span class="help-inline"><?php e__pe("required"); ?></span>
					</div>
				</div>
				
				
				<!--website field-->
				<div class="control-group">
					<label for="inputName" class="control-label"><?php e__pe("Website"); ?></label>
					<div class="controls">
						<input name="website" type="text" id="inputWebsite" class="span9">
						<span class="help-inline"></span>
					</div>
				</div>
		</div>
		
		<?php if ($contact->quote == "yes"): ?>
		<div id="project" class="bay form-horizontal">
			<?php echo $contact->quoteBox; ?>
			
			<!--website field-->
			<div class="control-group">
				<label for="inputProject" class="control-label">Project Name</label>
				<div class="controls">
					<input name="project" type="text" id="inputProject" class="span9">
					<span class="help-inline"></span>
				</div>
			</div>
			
			<!--website field-->
			<div class="control-group">
				<label for="selectService" class="control-label">Service Required</label>
				<div class="controls">
					<select name="service" class="span9">
						<?php foreach ($contact->services as $service): ?>
						<option value="<?php echo esc_attr($service); ?>"><?php echo $service; ?></option>
						<?php endforeach; ?>
					</select>
					<span class="help-inline"></span>
				</div>
			</div>
			
			<div class="control-group">
				<label for="#" class="control-label">Project Budget</label>
				<div class="controls">
					<?php $checked = "checked"; ?>
					<?php foreach ($contact->budgets as $budget): ?>
					<label class="radio"><input type="radio" name="budget" <?php echo $checked; ?> value="<?php echo esc_attr($budget); ?>" /><?php echo $budget; ?></label>
					<?php $checked = "";  ?>
					<?php endforeach; ?>
					<span class="help-inline"></span>
				</div>
			</div>
			
			<div class="control-group">
				<label for="inputTime" class="control-label">Time Frame</label>
				<div class="controls">
					<input name="timeframe" type="text" id="inputTime" class="span9">
					<span class="help-inline"></span>
				</div>
			</div>
			
		</div>
		<?php endif; ?>
		
		<div id="message" class="bay form-horizontal">
			<?php echo $contact->messageBox; ?>
			
			<div class="control-group">
				<label class="control-label">Description</label>
				<div class="controls">
					<textarea name="message" id="msg" rows="12" class="required span9"></textarea>
					<span class="help-inline">required</span>
				</div>
			</div>
			
			<div class="control-group">
				<div class="controls">
					<button type="submit" class="btn btn-info">Submit Form</button>
				</div>
			</div>
			
		</div>
		
		<div class="notifications">
			<div id="contactFormSent" class="formSent alert alert-success" style="display: none;">
				<?php echo $contact->msgOK; ?>
			</div>	
			<div id="contactFormError" class="formError alert alert-error" style="display: none;">
				<?php echo $contact->msgKO ?>
			</div>
		</div>
	
	</form><!--end form-->
	
	</div>
	<!--end main content-->

</div>
<?php endwhile; ?>


<?php get_footer(); ?>
