<?php $t =& peTheme(); ?>
<?php $content =& $t->content; ?>
<?php while ($content->looping()): ?>
<div>
	<a href="<?php $content->link(); ?>" ><?php $content->img(230,150); ?></a>
	<a class="caption" href="<?php $content->link(); ?>"><i class="icon-right-open"></i><?php $content->title(); ?></a>
</div>
<?php endwhile; ?>

