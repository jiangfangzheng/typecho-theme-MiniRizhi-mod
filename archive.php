<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>
<div id="main">
        <?php if ($this->have()): ?>
        <div class="place"><?php $this->archiveTitle(array(
            'category'  =>  _t('分类<span> %s </span>下的文章'),
            'search'    =>  _t('包含关键字<span> %s </span>的文章'),
            'date'      =>  _t('在<span> %s </span>发布的文章'),
            'tag'       =>  _t('标签<span> %s </span>下的文章'),
            'author'    =>  _t('<span>%s </span>发布的文章')
        ), '', ''); ?></div>
        <?php while($this->next()): ?>
        <article class="post">
			<h2 class="post-title"><a href="<?php $this->permalink() ?>"><?php $this->title() ?></a></h2>
			<ul class="meta">
				<li><time><?php $this->date('Y-m-d'); ?></time></li>
				<li><?php $this->category(','); ?></li>
				<li><a href="<?php $this->permalink() ?>#comments"><?php $this->commentsNum('评论', '1 评论', '%d 评论'); ?></a></li>
			</ul>
            <div class="post-excerpt">
    			<?php $this->excerpt(180); ?>
            </div>
            <div class="clearfix"></div>
        </article>
        <?php endwhile; ?>
        <?php else: ?>
        <div class="page404">
        <h2>404 - 页面没找到</h2>
        <p>你想查看的页面已被转移或删除了</p>
        </div>
        <?php endif; ?>
    <?php $this->pageNav('&laquo; 前一页', '后一页 &raquo;'); ?>
</div>
<?php $this->need('footer.php'); ?>