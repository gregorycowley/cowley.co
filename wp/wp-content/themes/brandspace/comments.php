<?php $t =& peTheme(); ?>
<?php if ($t->comments->supported()): ?>
<!--comment section-->
<div class="row-fluid" id="comments">
	<div class="span11 offset1 commentsWrap">

		<!--title-->
		<div class="row-fluid">
			<div class="span12">
				<h1 id="comments-title">
					<?php e__pe("Comments"); ?> <span>( <?php $t->content->comments(); ?> )</span>
				</h1>
			</div>
		</div>
		
		<?php $t->comments->show(); ?>
		
		<div class="row-fluid">
			<div class="span12">
				<?php $t->comments->pager(); ?>
			</div>
		</div>
		
		<?php $t->comments->form(); ?>
		
	</div>
	<!--end comments wrap-->
</div>
<!--end comments-->
<?php endif; ?>