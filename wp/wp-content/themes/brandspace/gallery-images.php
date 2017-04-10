<?php $t =& peTheme(); ?>
<?php list($pid,$conf,$loop) = $t->template->data(); ?>

<?php while ($item =& $loop->next()): ?>
<div class="row-fluid gallery image">
	<div class="span12">
		<a 
			title="<?php echo esc_attr($item->title); ?>"
			class="peOver"
			data-target="flare" 
			data-flare-gallery="galPostThumb<?php echo $pid ?>"
			id="galPostThumb<?php echo "{$pid}_{$item->id}" ?>"
			data-flare-thumb="<?php echo $t->image->resizedImgUrl($item->img,90,74); ?>"
			<?php if ($conf->bw): ?>
			data-flare-bw="<?php echo $t->image->bw($item->img); ?>"
			<?php endif; ?>
			data-flare-plugin="<?php echo $conf->plugin ?>"
			data-flare-scale="<?php echo $conf->scale ?>"
			href="<?php echo $item->img; ?>"
			>
			<?php echo $t->image->resizedImg($item->img,$t->media->width(940),$t->media->height(300)); ?>
		</a>
	</div>
</div>
<?php endwhile; ?>
