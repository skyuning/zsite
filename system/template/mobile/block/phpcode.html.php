<?php
/**
 * The php code block view file of block module of chanzhiEPS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPLV12 (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.chanzhi.org
*/
?>
<?php $block->content = is_null(json_decode($block->content)) ? $block->content : json_decode($block->content);?>
<?php if(!is_object($block->content)) $content = $block->content;?>
<?php if(is_object($block->content))  $content = isset($block->content->content) ? $block->content->content : '';?>
<?php eval('?>' . htmlspecialchars_decode($content, ENT_QUOTES));?>

