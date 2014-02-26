<?php
/**
 * The html block form view file of block module of chanzhiEPS.
 *
 * @copyright   Copyright 2013-2013 ?ൺϢ????????Ϣ???޹?˾ (QingDao XiRang Network Infomation Co,LTD www.xirangit.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Xiying Guan <guanxiying@xirangit.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.chanzhi.org
*/
?>
<?php $config->block->editor->blockform =  array('id' => 'content', 'tools' => 'full', 'filterMode' => false); ?>
<?php include '../../common/view/kindeditor.html.php';?>
<tr>
  <th><?php echo $lang->block->icon;?></th>
  <td><?php echo html::select('params[icon]', '', '', 'class="chosen-icons" data-value="icon-file-alt"');?></td>
</tr>
<tr>
  <th><?php echo $lang->block->content;?></th>
  <td><?php echo html::textarea('content', isset($block) ? $block->content : '', 'rows=20 class=form-control');?></td>
</tr>
