<?php $t =& peTheme(); ?>
<?php while ($t->content->looping() ) : ?>
<?php $media = isset($t->template->args["media"]) ? $t->template->args["media"] : true; ?>
<?php $meta =& $t->content->meta(); ?>

<?php $link = get_permalink(); ?>
<?php $hasFeatImage = $t->content->hasFeatImage(); ?>


<!--new post-->
<div class="row-fluid">
	<div class="span12">
		<div class="post compact">
			<!--post titles-->
			<div class="row-fluid">
				<div class="inner-spacer-left">
					<div class="span9 offset3">
						<div class="post-title">
							<h1>
								<a href="<?php echo $link; ?>"><?php $t->content->title() ?></a>
								<?php if (!empty($meta->subtitle->content)): ?>
								<span class="sub-heading hand-written"><?php echo $meta->subtitle->content; ?></span>
								<?php endif; ?>
							</h1>
						</div>
					</div>
				</div>
			</div>
			
			<!--post image-->
			<div class="row-fluid">
				<div class="span3  post-image">
					<?php if ($media): ?>
					<a href="<?php echo $link; ?>"><?php $t->content->img(128,128); ?></a>
					<?php endif; ?>
					<div class="post-meta">
						<span class="user"><?php $t->content->author(); ?><i class="icon-user"></i></span>
						<span class="date"><?php $t->content->date(); ?><i class="icon-calendar"></i></span>
						<span class="comments"><a href="<?php echo $link; ?>" title="comments"><?php $t->content->comments() ?><i class="icon-comment"></i></a></span>
					</div>
				</div>
				
				<div class="span9">
					<div class="row-fluid">
						<div class="inner-spacer-left">
							<div class="span12">
								<?php $t->content->content() ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			
		</div>
		
	</div>
</div>
<!--end post-->

<?php endwhile; ?>
