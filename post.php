<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>
<div id="main">
    <article class="post">
			<h2 class="post-title center"><?php $this->title() ?></h2>
			<ul class="meta center">
				<li><time><?php $this->date('Y-m-d'); ?></time></li> 
				<li><?php $this->category(','); ?></li> 
				<li><a><?php get_post_view($this) ?></a> 阅读</li> 
				<li><a href="<?php $this->permalink() ?>#comments"><?php $this->commentsNum('评论', '1 评论', '%d 评论'); ?></a></li>
			</ul>
        <div class="post-content">
            <?php echo parseContent($this); ?>
        </div>
        <ul>
        <li>上一篇: <?php $this->thePrev('%s','没有了'); ?></li>
        <li>下一篇: <?php $this->theNext('%s','没有了'); ?></li>
        </ul>
        <p class="tags"><?php _e('标签: '); ?><?php $this->tags(', ', true, '无标签'); ?></p>
    </article>
    <h2 class="more"><?php _e('猜您喜欢'); ?></h2>
    <ul class="widget-list">
        <?php $this->related(5)->to($relatedPosts); ?>
        <?php if ($relatedPosts->have()): ?>
        <?php while ($relatedPosts->next()): ?>
        <li><a href="<?php $relatedPosts->permalink(); ?>" title="<?php $relatedPosts->title(); ?>"><?php $relatedPosts->title(); ?></a></li>
        <?php endwhile; ?>
        <?php else : ?>
        <?php theme_random_posts();?>
        <?php endif; ?>
    </ul>
    <?php $this->need('comments.php'); ?>
</div>
<?php $this->need('footer.php'); ?>
