<?php
/**
 * The html template file of deny method of user module of chanzhiEPS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPLV1.2 (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     chanzhiEPS
 * @version     $Id: deny.html.php 824 2010-05-02 15:32:06Z wwccss $
 */
include $this->loadModel('ui')->getEffectViewFile('default', 'common', 'header.lite');
?>
<?php if(isset($locate)):?>
<meta http-equiv='refresh' content="5;url='<?php echo $locate;?>'">
<?php endif;?>
<script>
$(function()
{
    var $icon = $('.alert-deny > .icon');
    $('.alert-deny').on('mouseenter', '.actions .btn', function()
    {
        $icon.removeClass('icon-frown').addClass('icon-smile');
    }).on('mouseleave', '.actions .btn', function()
    {
       $icon.removeClass('icon-smile').addClass('icon-frown');
    });
});
</script>
<style>
body{margin-top:40px;}
.alert.with-icon > .icon, .alert.with-icon > .icon + .content {padding: 10px 20px 20px;}
.alert.with-icon > .icon {padding-left: 35px;}
.alert-deny {max-width: 500px; margin: 8% auto; padding: 0; background-color: #FFF; border: 1px solid #DDD; box-shadow: 0px 2px 20px rgba(0, 0, 0, 0.2); border-radius: 6px;}
.btn-link {border-color: none!important}
#errorInfo{font-weight:normal; font-size:14px;}
</style>
<div class='container w-600px'>
  <div class='alert with-icon alert-deny'>
    <i class='icon-info icon'></i>
    <div class='content'>
      <h4 id='errorInfo' class='text-info'><?php echo $reason;?></h4>
      <div class='actions'><?php printf($lang->redirecting, $target, $locate);?></div>
    </div>
  </div>
</div>
</body>
</html>
