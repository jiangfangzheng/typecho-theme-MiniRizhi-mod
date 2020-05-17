<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>
<div id="main">
    <article class="post">
			<h2 class="post-title center"><?php $this->title() ?></h2>
        <div class="post-content">
            <?php echo parseContent($this); ?>
        </div>
    </article>
    <?php $this->need('comments.php'); ?>
</div>
<?php $this->need('footer.php'); ?>
