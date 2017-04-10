<?php $t =& peTheme(); ?>
<?php list($pid,$conf,$loop) = $t->template->data(); ?>

<div class="row-fluid gallery cover">
	<div class="span12">
		<a class="peOver" href="#fsGallery<?php echo $pid; ?>" data-target="flare">
			<?php echo $t->image->resizedImg($t->gallery->cover($pid),$t->media->width(940),$t->media->height(300)); ?>
		</a>
	</div>
</div>

<div class="hiddenLightboxContent">
	<?php while ($item =& $loop->next()): ?>
	<a href="<?php echo $item->img; ?>"
	   title="<?php echo esc_attr($item->title); ?>"
	   data-flare-thumb="<?php echo $t->image->resizedImgUrl($item->img,90,74); ?>"
	   data-target="flare"
	   <?php if ($conf->bw): ?>
	   data-flare-bw="<?php echo $t->image->bw($item->img); ?>"
	   <?php endif; ?>
	   data-flare-plugin="<?php echo $conf->plugin; ?>"
	   data-flare-gallery="fsGallery<?php echo $pid ?>"
	   data-flare-scale="<?php echo $conf->scale; ?>"
	   >
	</a>
	<?php endwhile; ?>
</div>