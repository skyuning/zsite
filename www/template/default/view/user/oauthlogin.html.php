<?php 
foreach($lang->user->oauth->providers as $providerCode => $providerName) $providerConfig[$providerCode] = isset($config->oauth->$providerCode) ? json_decode($config->oauth->$providerCode) : '';
if(!empty($providerConfig['sina']->clientID) or !empty($providerConfig['qq']->clientID)):
?>
  <div class='col-md-6'>
    <div class='panel panel-pure'>
      <div class='panel-heading'><strong><?php echo $lang->user->oauth->lblWelcome;?></strong></div>
      <div class='panel-body'>
        <?php 
        foreach($lang->user->oauth->providers as $providerCode => $providerName) 
        {
            $providerConfig = isset($config->oauth->$providerCode) ? json_decode($config->oauth->$providerCode) : '';
            if(empty($providerConfig->clientID)) continue;
            $params = "provider=$providerCode&fingerprint=fingerprintval";
            if($referer and !strpos($referer, 'login') and !strpos($referer, 'oauth')) $params .= "&referer=" . helper::safe64Encode($referer);
            echo html::a(inlink('oauthLogin', $params), "<i class='icon-{$providerCode} icon'></i> " . $providerName, "class='btn btn-default btn-oauth btn-lg btn-block btn-{$providerCode}'");
        }
        ?>
      </div>
    </div>
  </div>
  <div class='col-md-6'>
<?php else:?>
  <div class='col-md-12'>
<?php endif;?>
<script>
$().ready(function()
{
    $('a.btn-oauth').each(function()
    {
        fingerprint = new Fingerprint().get();
        alert(fingerprint);
        $(this).attr('href', $(this).attr('href').replace('fingerprintval', fingerprint) )
    })
})
</script>
