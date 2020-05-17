<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
function themeConfig($form) {
    $logoUrl = new Typecho_Widget_Helper_Form_Element_Text('logoUrl', NULL, NULL, _t('站点 LOGO 地址'));
    $form->addInput($logoUrl);
    $icoUrl = new Typecho_Widget_Helper_Form_Element_Text('icoUrl', NULL, NULL, _t('站点 Favicon 地址'));
    $form->addInput($icoUrl);
    $sticky = new Typecho_Widget_Helper_Form_Element_Text('sticky', NULL,NULL, _t('文章置顶'), _t('置顶的文章cid，按照排序输入, 请以半角逗号或空格分隔'));
    $form->addInput($sticky);
    $mulu= new Typecho_Widget_Helper_Form_Element_Radio('mulu', 
    array(1 => _t('启用'),
    0 => _t('关闭')),
    0, _t('文章目录'), _t('默认关闭，文章和页面显示一级、二级标题'));
    $form->addInput($mulu);
    $imgbox= new Typecho_Widget_Helper_Form_Element_Radio('imgbox', 
    array(1 => _t('启用'),
    0 => _t('关闭')),
    0, _t('图片灯箱'), _t('默认关闭，显示Fancybox灯箱效果'));
    $form->addInput($imgbox);
    $compressHtml = new Typecho_Widget_Helper_Form_Element_Radio('compressHtml', 
    array(1 => _t('启用'),
    0 => _t('关闭')),
    0, _t('HTML压缩'), _t('默认关闭，启用则会对HTML代码进行压缩，可能与部分插件存在兼容问题，请酌情选择开启或者关闭'));
    $form->addInput($compressHtml);
    $tongji = new Typecho_Widget_Helper_Form_Element_Textarea('tongji', NULL, NULL, _t('统计代码'), _t('位于底部，footer之后body之前，适合放置一些JS内容，如网站统计代码等'));
    $form->addInput($tongji);
    $links= new Typecho_Widget_Helper_Form_Element_Textarea('links', NULL, NULL, _t('友情链接'), _t('整站链接，位于底部'));
    $form->addInput($links);
}
function themeInit($archive) {
    Helper::options()->commentsAntiSpam = false; 
    if ($archive->is('single')) {
        $archive->content = createCatalog($archive->content);
    }
    $comment = spam_protection_pre($comment, $post, $result);
}
function createCatalog($obj) {
	global $catalog;
	global $catalog_count;
	$catalog = array();
	$catalog_count = 0;
	$obj = preg_replace_callback('/<h([1-6])(.*?)>(.*?)<\/h\1>/i', function($obj) {
		global $catalog;
		global $catalog_count;
		$catalog_count ++;
		$catalog[] = array('text' => trim(strip_tags($obj[3])), 'depth' => $obj[1], 'count' => $catalog_count);
		return '<h'.$obj[1].$obj[2].'><a class="cl-offset" name="cl-'.$catalog_count.'"></a>'.$obj[3].'</h'.$obj[1].'>';
	}, $obj);
	return $obj."\n".getCatalog();
}
function getCatalog() {
	global $catalog;
	$index = '';
	if ($catalog) {
		$index = '<ul>'."\n";
		$prev_depth = '';
		$to_depth = 0;
		foreach($catalog as $catalog_item) {
			$catalog_depth = $catalog_item['depth'];
			if ($prev_depth) {
				if ($catalog_depth == $prev_depth) {
					$index .= '</li>'."\n";
				} elseif ($catalog_depth > $prev_depth) {
					$to_depth++;
					$index .= "\n".'<ul>'."\n";
				} else {
					$to_depth2 = ($to_depth > ($prev_depth - $catalog_depth)) ? ($prev_depth - $catalog_depth) : $to_depth;
					if ($to_depth2) {
						for ($i=0; $i<$to_depth2; $i++) {
							$index .= '</li>'."\n".'</ul>'."\n";
							$to_depth--;
						}
					}
					$index .= '</li>'."\n";
				}
			}
			$index .= '<li><a href="#cl-'.$catalog_item['count'].'" onclick="Catalogswith()">'.$catalog_item['text'].'</a>';
			$prev_depth = $catalog_item['depth'];
		}
		for ($i=0; $i<=$to_depth; $i++) {
			$index .= '</li>'."\n".'</ul>'."\n";
		}
	$index = '<div id="catalog-col">'."\n".'<b>文章目录</b>'."\n".$index.'<script>function Catalogswith(){document.getElementById("catalog-col").classList.toggle("catalog");document.getElementById("catalog").classList.toggle("catalog")}</script>'."\n".'</div>'."\n";
	}
	return $index;
}
function spam_protection_math(){
    $num1=rand(1,9);
    $num2=rand(1,9);
    echo '<input type="text" id="code" required name="sum" value="" placeholder="'.$num1.' + '.$num2. ' = ? *" />';
    echo '<input type="hidden" name="num1" value="'.$num1.'" />';
    echo '<input type="hidden" name="num2" value="'.$num2.'" />';
}
function spam_protection_pre($comment, $post, $result){
    $sum=$_POST['sum'];
    switch($sum){
        case $_POST['num1']+$_POST['num2']:
        break;
        case null:
        throw new Typecho_Widget_Exception(_t('对不起: 请输入验证码。<a href="javascript:history.back(-1)">返回上一页</a>','评论失败'));
        break;
        default:
        throw new Typecho_Widget_Exception(_t('对不起: 验证码错误，请<a href="javascript:history.back(-1)">返回重试</a>。','评论失败'));
    }
    return $comment;
}
function get_post_view($archive) {
    $cid = $archive->cid;
    $db = Typecho_Db::get();
    $prefix = $db->getPrefix();
    if (!array_key_exists('views', $db->fetchRow($db->select()->from('table.contents')))) {
        $db->query('ALTER TABLE `' . $prefix . 'contents` ADD `views` INT(10) DEFAULT 0;');
        echo 0;
        return;
    }
    $row = $db->fetchRow($db->select('views')->from('table.contents')->where('cid = ?', $cid));
    if ($archive->is('single')) {
        $views = Typecho_Cookie::get('extend_contents_views');
        if (empty($views)) {
            $views = array();
        } else {
            $views = explode(',', $views);
        }
        if (!in_array($cid, $views)) {
            $db->query($db->update('table.contents')->rows(array('views' => (int)$row['views'] + 1))->where('cid = ?', $cid));
            array_push($views, $cid);
            $views = implode(',', $views);
            Typecho_Cookie::set('extend_contents_views', $views); 
        }
    }
    echo $row['views'];
}
function theme_random_posts(){
  $defaults = array(
    'number' => 5,
    'before' => '',
    'after' => '',
    'xformat' => '<li><a href="{permalink}" title="{title}">{title}</a></li>'
  );
  $db = Typecho_Db::get();
  $sql = $db->select()->from('table.contents')
    ->where('status = ?','publish')
    ->where('type = ?', 'post')
    ->where('created <= unix_timestamp(now())', 'post') 
    ->limit($defaults['number'])
    ->order('RAND()');
  $result = $db->fetchAll($sql);
  echo $defaults['before'];
  foreach($result as $val){
    $val = Typecho_Widget::widget('Widget_Abstract_Contents')->filter($val);
    echo str_replace(array('{permalink}', '{title}'),array($val['permalink'], $val['title']), $defaults['xformat']);
  }
  echo $defaults['after'];
}
function parseContent($obj) {
	$obj->content = preg_replace(['/\<img.*?src\=\"(.*?)\"[^>]*>/i'],['<a href="$1" data-fancybox="gallery"><img src="$1" alt="'.$obj->title.'" title="点击放大图片"></a>'],$obj->content);
	echo trim($obj->content);
}
function compressHtml($html_source) {
    $chunks = preg_split('/(<!--<nocompress>-->.*?<!--<\/nocompress>-->|<nocompress>.*?<\/nocompress>|<pre.*?\/pre>|<textarea.*?\/textarea>|<script.*?\/script>)/msi', $html_source, -1, PREG_SPLIT_DELIM_CAPTURE);
    $compress = '';
    foreach ($chunks as $c) {
        if (strtolower(substr($c, 0, 19)) == '<!--<nocompress>-->') {
            $c = substr($c, 19, strlen($c) - 19 - 20);
            $compress .= $c;
            continue;
        } else if (strtolower(substr($c, 0, 12)) == '<nocompress>') {
            $c = substr($c, 12, strlen($c) - 12 - 13);
            $compress .= $c;
            continue;
        } else if (strtolower(substr($c, 0, 4)) == '<pre' || strtolower(substr($c, 0, 9)) == '<textarea') {
            $compress .= $c;
            continue;
        } else if (strtolower(substr($c, 0, 7)) == '<script' && strpos($c, '//') != false && (strpos($c, "\r") !== false || strpos($c, "\n") !== false)) {
            $tmps = preg_split('/(\r|\n)/ms', $c, -1, PREG_SPLIT_NO_EMPTY);
            $c = '';
            foreach ($tmps as $tmp) {
                if (strpos($tmp, '//') !== false) {
                    if (substr(trim($tmp), 0, 2) == '//') {
                        continue;
                    }
                    $chars = preg_split('//', $tmp, -1, PREG_SPLIT_NO_EMPTY);
                    $is_quot = $is_apos = false;
                    foreach ($chars as $key => $char) {
                        if ($char == '"' && $chars[$key - 1] != '\\' && !$is_apos) {
                            $is_quot = !$is_quot;
                        } else if ($char == '\'' && $chars[$key - 1] != '\\' && !$is_quot) {
                            $is_apos = !$is_apos;
                        } else if ($char == '/' && $chars[$key + 1] == '/' && !$is_quot && !$is_apos) {
                            $tmp = substr($tmp, 0, $key);
                            break;
                        }
                    }
                }
                $c .= $tmp;
            }
        }
        $c = preg_replace('/[\\n\\r\\t]+/', ' ', $c);
        $c = preg_replace('/\\s{2,}/', ' ', $c);
        $c = preg_replace('/>\\s</', '> <', $c);
        $c = preg_replace('/\\/\\*.*?\\*\\//i', '', $c);
        $c = preg_replace('/<!--[^!]*-->/', '', $c);
        $compress .= $c;
    }
    return $compress;
}