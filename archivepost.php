<?php 
/**
 * 文章归档
 * 
 * @package custom 
 * 
 */
?>
<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>
<div id="main">
    <div class="archivepage">
    <h2 class="post-title center"><?php $this->title() ?></h2>
    <?php $this->widget('Widget_Contents_Post_Recent', 'pageSize=10000')->to($archives);
	$year=0; $mon=0; $i=0; $j=0;
	$output = '<div id="archives">';
	while($archives->next()):
		$year_tmp = date('Y',$archives->created);
		$mon_tmp = date('m',$archives->created);
		$y=$year; $m=$mon;
		if ($mon != $mon_tmp && $mon > 0) $output .= '</ul></li>';
		if ($year != $year_tmp && $year > 0) $output .= '</ul>';
		if ($year != $year_tmp) {
			$year = $year_tmp;
			$output .= '<h2>'. $year .' 年</h2><ul>'; //输出年份
		}
		if ($mon != $mon_tmp) {
			$mon = $mon_tmp;
			$output .= '<li><span><b>'. $mon .' 月</b></span><ul>'; //输出月份
		}
		//$output .= '<li>'.date('d日: ',$archives->created).'<a href="'.$archives->permalink .'" title="'. $archives->title .'">'. $archives->title .'</a> <em>('. $archives->commentsNum.' 评论)</em></li>'; //输出文章日期和标题
		$output .= '<li>'.date('d日: ',$archives->created).'<a href="'.$archives->permalink .'" title="'. $archives->title .'">'. $archives->title .'</a></li>'; //输出文章日期和标题
	endwhile;
	$output .= '</ul></li></ul></div>';
	echo $output;
    ?>	
		<div class="clearfix"></div>
    </div>
</div>
<?php $this->need('footer.php'); ?>