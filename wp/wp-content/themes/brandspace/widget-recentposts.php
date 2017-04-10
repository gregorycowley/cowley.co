<?php $t =& peTheme(); ?>
<?php $content =& $t->content; ?>
<?php $conf = $t->template->args; ?>
<?php if (!empty($conf->title)): ?>
<h3><?php echo $conf->title; ?></h3>
<?php endif; ?>
<?php while ($content->looping()): ?>
<span><?php $content->date() ?></span>
<a class="comments-num" href="<?php $content->link() ?>"><?php $content->comments() ?><i class="icon-comment"></i></a>
<p><?php echo $t->utils->truncateString(get_the_excerpt(),200) ?></p>
<?php endwhile; ?>
<?php if (!empty($conf->link) && !empty($conf->url)): ?>
<a class="read-more hand-written" href="<?php echo $conf->url ?>"><i class="icon-forward"></i><?php echo $conf->link ?></a>
<?php endif; ?>

