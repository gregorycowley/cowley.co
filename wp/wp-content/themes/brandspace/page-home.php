<?php
/*
Template Name: Home
*/
?><?php $t =& peTheme(); ?>
<?php get_header(); ?>
<?php get_template_part("tagline"); ?>
<?php $content =& $t->content; ?>
<?php $meta =& $content->meta(); ?>


<section  class="row-fluid banner">
	<?php $t->media->size($meta->gallery,940,350); ?>
	<?php $t->slider->output(); ?>
	<?php $t->media->size(); ?>
</section>

<?php while ($content->looping()): ?>

<div class="row-fluid">
	
	<!--statistic sidebar-->
	<aside class="span5 stat-sidebar">
		<?php $logos =& $meta->logos; ?>

		<?php if ($logos->title): ?>
		<h3><?php echo $logos->title; ?></h3>
		<?php endif; ?>
		
		<ul class="stats">
			<?php for ($i=1; $i<=4;$i++): ?>
			<?php $logo = $logos->{"logo$i"}; ?>
			<?php $link = $logos->{"link$i"}; ?>
			<?php $text = $logos->{"text$i"}; ?>
			<li>
				<?php if ($logo && $link): ?>
				<a href="<?php echo $link; ?>">
					<?php endif; ?>
					<img src="<?php echo $logos->{"logo$i"} ?>" alt="" />
					<?php if ($link): ?>
				</a>
				<?php endif; ?>
				<?php if ($text): ?>
				<span><?php echo $text ?></span>
				<?php endif; ?>
			</li>
			<?php endfor; ?>
		</ul>
	</aside>
	<!--end statistic sidebar-->
		
	<!--main content-->
	<section class="span7 home-content">
		
		<div class="row-fluid">
			<div class="inner-spacer-left">
				<!--who are we-->
				<div class="span12 upper">
					<div class="row-fluid">
						<?php $content->content(); ?>
					</div>
					
				</div>
				<!--end who are we-->
			</div>
			
		</div>

		<?php $projects = $meta->projects; ?>

		<?php if (count($projects->items) > 0) : ?>
		<div class="row-fluid">
			
			<div class="inner-spacer-left">
				<!--latest work-->
				<div class="span12 lower">
					<h3>
						<?php echo $projects->title; ?>
						<?php if (!empty($projects->label) && !empty($projects->link) ): ?>
						<a href="<?php echo $projects->link; ?>">
							<span class="hand-written"><?php echo $projects->label; ?></span>
						</a>
						<?php endif; ?>
					</h3>

					<?php if ($content->customLoop("project",-1,null,array("post__in" => $projects->items),false)): ?>
					<?php while ($content->looping(2)): ?>
					<?php $content->beginRow('<div class="row-fluid">') ?>
					<div class="span6 feat-<?php echo $content->idx() % 2 ? "right" : "left"; ?>">
						<a href="<?php $content->link(); ?>">
							<?php $content->img(460,300); ?>
						</a>
						<a class="caption" href="<?php $content->link(); ?>">
							<span class="icon-right-open"></span>
							<?php $content->title(); ?>
						</a>
					</div>
					<?php $content->endRow('</div>'); ?>
					<?php endwhile; ?>
					<?php $content->resetLoop(); ?>
					<?php endif; ?>
					
					

				</div>
				<!--end latest work-->
			</div>			
		</div>
		<?php endif; ?>

		
	</section>
	<!--end main content-->
</div>

<?php endwhile; ?>

<?php get_footer(); ?>
