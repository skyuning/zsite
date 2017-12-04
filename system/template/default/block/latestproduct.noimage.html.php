  <div class='panel-body'>
    <ul class='ul-list'>
{*php*}
      foreach($products as $product):
      $url = helper::createLink('product', 'view', "id=$product->id", "category={$product->category->alias}&name=$product->alias");
{*/php*}
      <li>
        <span class='text-latin pull-right'>
        {if(isset($content->showPrice) and $content->showPrice)}
        <span>
{*php*}
        if(!$product->unsaleable)
        {
            if($product->negotiate)
            { 
                echo "&nbsp;&nbsp;";
                echo "<strong class='text-danger'>" . $lang->product->negotiate . '</strong>';
            }
{else}
            {
                if($product->promotion != 0)
                {
                    if($product->price != 0)
                    {
                        echo "<small class='text-muted'>" . $lang->product->currencySymbol . "</small> ";
                        echo "<del><small class='text-muted'>" . $product->price . "</small></del>";
                    }
                    echo "&nbsp; <small class='text-muted'>" . $lang->product->currencySymbol . "</small> ";
                    echo "<strong class='text-danger'>" . $product->promotion . "</strong>";
                }
                else if($product->price != 0)
                {
                    echo "&nbsp; <small class='text-muted'>" . $lang->product->currencySymbol . "</small> ";
                    echo "<strong class='text-important'>" . $product->price . "</strong>";
                }
            }
        }
{*/php*}
        {/if}
        </span>
        {if(isset($content->showViews) and $content->showViews)}
        <span>
          <i class="icon icon-eye-open"></i> {!echo $product->views}
        </span>
        {/if}
        </span>
        {if(isset($content->showCategory) and $content->showCategory == 1)}
          {if($content->categoryName == 'abbr')}
          {$categoryName = '[' . ($product->category->abbr ? $product->category->abbr : $product->category->name) . '] '}
          {!echo html::a(helper::createLink('product', 'browse', "categoryID={$product->category->id}", "category={$product->category->alias}"), $categoryName)}
          {else}
          {!echo html::a(helper::createLink('product', 'browse', "categoryID={$product->category->id}", "category={$product->category->alias}"), '[' . $product->category->name . '] ')}
          {/if}
        {/if}
        {!echo html::a($url, $product->name)}
      </li>
      {if(isset($content->showInfo) and isset($content->infoAmount))}
{*php*}
        $productInfo = empty($product->desc) ? $product->content : $product->desc; 
        $productInfo = strip_tags($productInfo);
        $productInfo = (mb_strlen($productInfo) > $content->infoAmount) ? mb_substr($productInfo, 0 , $content->infoAmount, 'utf8') : $productInfo;
{*/php*}
      <div style='padding-left:30px;'>{!echo $productInfo}</div>
      {/if}
      {/foreach}
    </ul>
  </div>
