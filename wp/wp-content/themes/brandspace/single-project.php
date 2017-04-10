<?php $t =& peTheme(); ?>
<?php $content =& $t->content; ?>
<?php get_header(); ?>
<?php get_template_part("tagline"); ?>

<div class="row-fluid"><!--main content-->
	<div class="span12">
		
		<?php while ($content->looping() ) : ?>
		<?php $meta =& $content->meta(); ?>
		<?php $project =& $meta->project; ?>
		<?php $link = get_permalink(); ?>
		<?php $hasFeatImage = $content->hasFeatImage(); ?>
		<!--project-->
		<div class="row-fluid project">
		   
			<div class="span5">
				
				<!-- project navigation -->
				<div class="project-nav">
					<a 
						href="<?php echo (($prev = $content->prevPostLink()) ? $prev : "#");  ?>" 
						class="prev-project pull-left<?php echo ($prev ? "": " disabled"); ?>">
						<i class="icon-left-open"></i> <?php e__pe("prev"); ?>
					</a>
					<a 
						href="<?php echo (($next = $content->nextPostLink()) ? $next : "#");  ?>" 
						class="next-project pull-left<?php echo ($next ? "": " disabled"); ?>">
						<?php e__pe("next"); ?> <i class="icon-right-open"></i> 
					</a>
				</div>
				
				
				<div class="project-title">
					<h1><?php $content->title(); ?></h1>
					<?php if (!empty($meta->subtitle->content)): ?>
					<span class="hand-written sub-heading"><?php echo $meta->subtitle->content; ?></span>
					<?php endif; ?>
				</div>
				
				<!--project meta info-->
				<div class="project-meta">
					<?php if (!empty($project->client)): ?>
					<span class="client"><?php echo $project->client ?><i class="icon-user"></i></span>
					<?php endif; ?>
					<?php if (!empty($project->date)): ?>
					<span class="date"><?php echo $project->date ?><i class="icon-calendar"></i></span>
					<?php endif; ?>
					<span class="tags">
						<?php $t->project->tags(); ?>
						<i class="icon-tag"></i>
					</span>	
				</div>

				
				<?php if (!empty($project->label) && !empty($project->link)): ?>
				<a href="<?php echo $project->link ?>" class="read-more hand-written"><?php echo $project->label ?></a>
				<?php endif; ?>
				
				
				
				
			</div>
			
			<div class="span7 project-content">

				<div class="post-image">
				<?php switch($t->content->format()): case "gallery": // Gallery post ?>
				<?php $t->media->size($t->content->meta()->gallery,540,340); ?>
				<?php $t->slider->output(); ?>
				<?php $t->media->size(); ?>
				<?php break; case "video": // Video post ?>
				<?php $t->video->output(); ?>
				<?php break; default: // Standard post ?>
				<?php if ($hasFeatImage): ?>
				<?php $content->img(540,null); ?>
				<?php endif; ?>
				<?php endswitch; ?>
				</div>
				
				<?php $content->content(); ?>
				
				<!--share box-->
				<div class="shareBox">                        
					
					<h5>Share This Project: </h5>
							
					<!--tweet this button-->
					<button class="share twitter"></button>
					
					<!--google plus 1 button-->
					<button class="share google"></button>
					
					<!--facebook like btn-->
					<button class="share facebook"></button>
				</div>
			</div>
		 
			 
		</div>
		<!--end project-->
		<?php endwhile; ?>
		<?php comments_template(); ?>


	</div>
	<!--end mainContent-->
</div>

<?php get_footer(); ?>
