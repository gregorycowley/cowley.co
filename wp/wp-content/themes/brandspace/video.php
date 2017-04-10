<?php $t =& peTheme(); ?>
<?php list($video) = $t->template->data(); ?>

<?php if ($video->fullscreen === "yes"): ?>

<a 
	href="<?php echo $video->url ?>" 
	data-target="flare" 
	<?php if (!empty($video->formats)): ?>
	data-flare-videoformats="<?php echo join(",",$video->formats); ?>"
	<?php endif; ?>
	<?php if (!empty($video->poster)): ?>
	data-poster="<?php echo $video->poster; ?>" 
	data-flare-videoposter="<?php echo $video->poster; ?>"
	<?php endif; ?>
	class="peVideo">
</a>

<?php else: ?>

<a 
	href="<?php echo $video->url ?>" 
	<?php if (!empty($video->formats)): ?>
	data-formats="<?php echo join(",",$video->formats); ?>" 
	<?php endif; ?>
	<?php if (!empty($video->poster)): ?>
	data-poster="<?php echo $video->poster; ?>"
	<?php endif; ?>
	class="peVideo">
</a>


<?php endif; ?>
