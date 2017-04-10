<?php $t =& peTheme(); ?>
<?php list($pid,$conf,$loop) = $t->template->data(); ?>

<!-- featured project viewer-->
<div class="row-fluid gallery thumbs-slider">
	<div class="span12 feat-project-viewer">
		<div class="row-fluid">
					
			<!--thumbs images-->
			<div class="span5 image-browser">
				<?php while ($item =& $loop->next()): ?>
				<?php $hidden = ($conf->max > 0 && $item->idx >= $conf->max); ?>
				<a href="#">
					<?php echo $t->image->resizedImg($item->img,186,115); ?>
				</a>
				<?php endwhile; ?>
			</div>
			<!--end thumbs-->
			<!--main images-->
			<div class="span7 feat-images">
				<?php $t->slider->output($pid); ?>
			</div>	
			<!--end images-->
				
		</div>
	</div>
</div>
<!--end featured project viewer -->
