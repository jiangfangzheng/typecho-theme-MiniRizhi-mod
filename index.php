<?php
/**
 * @package 日志
 * @author 迷你日志
 * @version 2.0
 * @link https://minirizhi.com
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
$sticky = $this->options->sticky; 
if($sticky && $this->is('index') || $this->is('front')){
    $sticky_cids = explode(',', strtr($sticky, ' ', ','));
    $sticky_html = "<span class='sticky'>[推荐] </span>";
    $db = Typecho_Db::get();
    $pageSize = $this->options->pageSize;
    $select1 = $this->select()->where('type = ?', 'post');
    $select2 = $this->select()->where('type = ? && status = ? && created < ?', 'post','publish',time());
    $this->row = [];
    $this->stack = [];
    $this->length = 0;
    $order = '';
    foreach($sticky_cids as $i => $cid) {
        if($i == 0) $select1->where('cid = ?', $cid);
        else $select1->orWhere('cid = ?', $cid);
        $order .= " when $cid then $i";
        $select2->where('table.contents.cid != ?', $cid);
    }
    if ($order) $select1->order(null,"(case cid$order end)");
    if ($this->_currentPage == 1) foreach($db->fetchAll($select1) as $sticky_post){ 
        $sticky_post['sticky'] = $sticky_html;
        $this->push($sticky_post);
    }
$uid = $this->user->uid; 
    if($uid) $select2->orWhere('authorId = ? && status = ?',$uid,'private');
    $sticky_posts = $db->fetchAll($select2->order('table.contents.created', Typecho_Db::SORT_DESC)->page($this->_currentPage, $this->parameter->pageSize));
    foreach($sticky_posts as $sticky_post) $this->push($sticky_post); 
    $this->setTotal($this->getTotal()-count($sticky_cids)); 
}
?>
<div id="main">
	<?php while($this->next()): ?>
        <article class="post">
			<h2 class="post-title"><a href="<?php $this->permalink() ?>"><?php $this->sticky(); $this->title() ?></a></h2>
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
    <?php $this->pageNav('&laquo; 前一页', '后一页 &raquo;'); ?>
</div>
<?php $this->need('footer.php'); ?>