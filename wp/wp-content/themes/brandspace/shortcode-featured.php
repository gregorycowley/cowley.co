<?php $t =& peTheme(); ?>
<?php $content =& $t->content; ?>
<?php list($loop) = $t->template->data(); ?>

<?php while ($content->looping() ) : ?>
<?php $meta =& $content->meta(); ?>
<!--featured project info-->
<div class="row-fluid">
	<div class="span12 feat-info">
		<div class="row-fluid">
			<div class="span5">
				<div class="feat-title">
					<h1>
						<?php $content->title(); ?>
						<?php if (!empty($meta->subtitle->content)): ?>
						<span class="hand-written sub-heading"><?php echo $meta->subtitle->content; ?></span>
						<?php endif; ?>
					</h1>
					
				</div>
			</div>
				
			<div class="span7">
				<?php $content->content(); ?>
			</div>
		</div>
	</div>
</div>
<!--end featured project info-->
<?php endwhile; ?>