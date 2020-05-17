<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
    <footer id="footer">
    <p><?php $this->options->links(); ?></p>
            &copy; <?php echo date('Y'); ?> <a href="<?php $this->options->siteUrl(); ?>"><?php $this->options->title(); ?></a>. 主题:<a target="_blank" href="https://minirizhi.com">日志</a>
    <?php $this->options->tongji(); ?>
    </footer>
</div>

<div id="cornertool"><ul><li id="top" class="hidden"></li><li id="DarkModeButton" onclick = "switchNightMode()" class="">◐</li></ul></div>
<script src="<?php $this->options->themeUrl('/js/rizhi.js'); ?>"></script>

<?php if ($this->options->mulu): ?><script>var cornertool=true;function cl(){var a=document.getElementById("catalog-col"),b=document.getElementById("catalog"),c=document.getElementById("cornertool"),d;if(a&&!b){if(c){c=c.getElementsByTagName("ul")[0];d=document.createElement("li");d.setAttribute("id","catalog");d.setAttribute("onclick","Catalogswith()");d.appendChild(document.createElement("span"));c.appendChild(d)}else{cornertool=false;c=document.createElement("div");c.setAttribute("id","cornertool");c.innerHTML='<ul><li id="catalog" onclick="Catalogswith()"><span></span></li></ul>';document.body.appendChild(c)}document.getElementById("catalog").className=a.className}if(!a&&b){cornertool?c.getElementsByTagName("ul")[0].removeChild(b):document.body.removeChild(c)}if(a&&b){b.className=a.className}}cl();</script><?php endif; ?>


<?php if ($this->options->imgbox): ?><script src="//cdn.staticfile.org/jquery/2.1.4/jquery.min.js"></script>
<link rel="stylesheet" href="//cdn.staticfile.org/fancybox/3.5.2/jquery.fancybox.min.css">
<script src="//cdn.staticfile.org/fancybox/3.5.2/jquery.fancybox.min.js"></script>
<script>$('[data-fancybox="gallery"]').fancybox({ buttons: ["zoom",/*"share",*/"slideShow","fullScreen",/*"download",*/"thumbs","close"],lang: "cn",i18n: { cn: { CLOSE: "关闭", NEXT: "下一张", PREV: "上一张", ERROR: "无法加载图片！ <br/> 请稍后再试……", PLAY_START: "开始预览", PLAY_STOP: "停止预览", FULL_SCREEN: "全屏", THUMBS: "缩略图", DOWNLOAD: "下载", SHARE: "分享", ZOOM: "放大" } },slideShow: { autoStart: false,speed: 3000 }, });</script><?php endif; ?>

<?php $this->footer(); ?>
</body>
</html>
<?php if ($this->options->compressHtml): $html_source = ob_get_contents(); ob_clean(); print compressHtml($html_source); ob_end_flush(); endif; ?>