{*php
/**
 * The check view file of order for mobile template of chanzhiEPS.
 * The file should be used as ajax content
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPLV12 (http://zpl.pub/page/zplv12.html)
 * @author      Hao Sun <sunhao@cnezsoft.com>
 * @package     order
 * @version     $Id$
 * @link        http://www.chanzhi.org
 */
/php*}
{include $control->loadModel('ui')->getEffectViewFile('mobile', 'common', 'header')}
{!js::set('currencySymbol', $currencySymbol)}
{!js::set('createdSuccess', $lang->order->createdSuccess)}
{!js::set('goToPay', $lang->order->goToPay)}

<div class='panel panel-section'>
  <div class='panel-heading page-header'>
    <div class='title'><i class='icon icon-shopping-cart'></i> <strong>{$lang->order->selectPayment}</strong></div>
  </div>
  <form id='checkForm' action='{!helper::createLink('order', 'pay', "orderID=$order->id")}' method='post' target='_blank'>
    <div class="panel-body">
      <div class='alert bg-gray-pale'><strong>{$lang->order->payment}</strong></div>
      <div class="form-group">
        {!html::radio('payment', $paymentList)}
      </div>
      {if($inWechat)}<div class='alert bg-primary-pale'>{$lang->order->inWechatTip}</div>{/if}
    </div>
    <div class='panel-body'>
      <div class='alert bg-primary-pale'>
        {!printf($lang->order->selectProducts, count($products))}
        {!printf($lang->order->totalToPay, $currencySymbol . $order->amount)}
      </div>
    </div>
    <div class='panel-footer'>
      {!html::submitButton($lang->order->settlement, 'btn-order-submit btn danger block')}
      {!html::a(helper::createLink('order', 'browse'), $lang->order->admin, "class='btn default block'")}
    </div>
  </form>
</div>

{include TPL_ROOT . 'common/form.html.php'}
{noparse}
<script>
$(function()
{
    $('[name=payment]').eq(0).prop('checked', true);

    $('#checkForm').ajaxform(
    {
        onSuccess: function(response)
        {
            if(response.result != 'success') $.messager.success(response.message);
            if(response.locate) window.location.href = response.locate;
        }
    });

});
</script>
{/noparse}
{include $control->loadModel('ui')->getEffectViewFile('mobile', 'common', 'footer')}
