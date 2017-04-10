<?php $t =& peTheme(); ?>
<?php $content =& $t->content; ?>
<?php list($conf) = $t->template->data(); ?>
<?php $cols = 3; ?>
<div class="row-fluid cat-wrap">
	<div class="category span12">
		<div class="row-fluid">
			<div class="span5">
				<div class="cat-info">
					<?php if (!empty($conf->title)): ?>
					<h2><?php echo $conf->title; ?></h2>
					<?php endif; ?>
					<?php if (!empty($conf->content)): ?>
					<?php echo $conf->content; ?>
					<?php endif; ?>
					<?php if (!empty($conf->url) && !empty($conf->label)): ?>
					<a href="<?php echo $conf->url; ?>" class="read-more hand-written"><?php echo $conf->label; ?></a>
					<?php endif; ?>
				</div>
			</div>
			
			<div class="span7 category-thumbs">
				<?php while ($content->looping() ) : ?>
				<?php $idx = $content->idx(); ?>
				<?php $last = $content->last(); ?>
				
				<?php if ($cols > 0 && ($idx % $cols) == 0): ?>
				<div class="row-fluid">
					<?php endif; ?>
					
					<div class="span4 thumb">
						<a href="<?php echo get_permalink(); ?>">
							<?php $t->content->img(460,340) ?>
						</a>
						<h3>
							<a href="<?php echo get_permalink(); ?>"><?php $content->title(); ?></a>
						</h3>
						<span><?php echo $t->utils->truncateString(get_the_excerpt(),30); ?></span>
					</div>
					
					<?php if ($cols > 0 && (($idx == $last) || ($idx % $cols) == ($cols-1))): ?>
				</div>
				<?php endif; ?>
				
				<?php endwhile; ?>
			</div>
		</div>
	</div>
</div>