<?php 
/**
 * 标签云
 * 
 * @package custom 
 * 
 */
?>
<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>
<div id="main">
    <div class="tagcloud">
        <h2 class="post-title center"><?php $this->title() ?></h2>
   <?php $this->widget('Widget_Metas_Tag_Cloud@tagcloud', array('sort' => 'count', 'ignoreZeroCount' => true, 'desc' => true, 'limit' => 10000))->to($tags); ?> 
	<?php if($tags->have()): ?>
	<ul class="widget-list">
    		<?php while ($tags->next()): ?>
   		 <li class="tag-list"><a href="<?php $tags->permalink();?>" title="<?php $tags->name(); ?>">
         <?php $tags->name(); ?></a></li> 
    <?php endwhile; ?>
	</ul>
    <?php endif; ?>
		<div class="clearfix"></div>
    </div>
</div>
<?php $this->need('footer.php'); ?>