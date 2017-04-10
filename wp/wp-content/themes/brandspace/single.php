<?php $t =& peTheme(); ?>
<?php $content =& $t->content; ?>
<?php get_header(); ?>
<?php get_template_part("tagline"); ?>

<div class="row-fluid">
	
	<?php get_sidebar(); ?>
	
	<!--main content-->
	<div class="span8">

		<?php while ($content->looping() ) : ?>
		<?php $meta =& $content->meta(); ?>

		<?php $link = get_permalink(); ?>
		<?php $hasFeatImage = $content->hasFeatImage(); ?>

		<div class="row-fluid">
			<div class="span12">
				<div class="post full">
					<div class="row-fluid">
						<div class="span11 offset1">

							<!--post titles-->
							<div class="post-title">
								<h1>
									<a href="<?php echo $link; ?>"><?php $content->title() ?></a>
									<?php if (!empty($meta->subtitle->content)): ?>
									<span class="sub-heading hand-written"><?php echo $meta->subtitle->content; ?></span>
									<?php endif; ?>
								</h1>
							</div>
							
							<!--post media-->
							<div class="post-image">
							<?php switch($t->content->format()): case "gallery": // Gallery post ?>
							<?php $t->media->size($t->content->meta()->gallery,620,260); ?>
							<?php $t->slider->output(); ?>
							<?php $t->media->size(); ?>
							<?php break; case "video": // Video post ?>
							<?php $t->video->output(); ?>
							<?php break; default: // Standard post ?>
							<?php if ($hasFeatImage): ?>
								<a href="<?php echo $link; ?>"><?php $content->img(620,260); ?></a>
							<?php endif; ?>
							<?php endswitch; ?>
							</div>

							<!--post meta-->
							<div class="post-meta">
								<span class="user"><i class="icon-user"></i><?php $content->author(); ?></span>
								<span class="date"><i class="icon-calendar"></i><?php $content->date(); ?></span>
								<span class="category"><i class="icon-tag"></i><?php $content->tags(); ?></span>
								<span class="comments"><a href="<?php echo $link; ?>" title="comments"><?php $content->comments() ?><i class="icon-comment"></i></a></span>
							</div>
							
						</div>
					</div>
					
					<div class="row-fluid">
						<div class="span11 offset1">
							<?php $content->content(); ?>

							
							<!--post body-->

							<!--share box-->
							<div class="shareBox">                        
								
								<h5><?php e__pe("Share This Post: "); ?></h5>
								
								<!--tweet this button-->
								<button class="share twitter"></button>
								
								<!--google plus 1 button-->
								<button class="share google"></button>
								
								<!--facebook like btn-->
								<button class="share facebook"></button>
								
							</div>
							
						</div>
					</div>
				</div>
				<!--end post-->
			</div>
		</div>

		<?php endwhile; ?>
		<?php get_template_part("common-pager"); ?>
		<?php comments_template(); ?>

	</div>
	<!--end main content-->
</div>

<?php get_footer(); ?>
