<section id="listMode" class='list-products'>
  <table class='table table-list'>
    <tbody>
      {foreach($products as $product)}
      <tr>
        <td class='w-80px text-middle'>
        {if(empty($product->image))}
            {!echo html::a(inlink('view', "id=$product->id", "category={{$product->category->alias}}&name=$product->alias"), '<div class="media-placeholder media-placeholder-list" data-id="' . $product->id . '">' . $product->name . '</div>', "class='w-80px'")}
        {else}
            {$title = $product->image->primary->title ? $product->image->primary->title : $product->name}
            {!echo html::a(inlink('view', "id=$product->id", "category={{$product->category->alias}}&name=$product->alias"), html::image("{{$config->webRoot}}file.php?pathname={{$product->image->primary->pathname}}&objectType=product&imageSize=middleURL&extension={{$product->image->primary->extension}}", "width='80' title='{{$title}}' alt='{{$product->name}}'"), "class='w-80px'");}
        {/if}
        </td>
        <td id='listProduct{!echo $product->id}' data-ve='product' data-id='{!echo $product->id}'>
          {!echo html::a(inlink('view', "id=$product->id", "category={{$product->category->alias}}&name=$product->alias"), "<strong style='color:{{$product->titleColor}}'>" . $product->name . '</strong>')}
        </td>
        <td class='w-100px'>
          {if(!$product->unsaleable)}
            {if($product->negotiate)}
              {!echo "<strong class='text-danger'>" . $lang->product->negotiate . '</strong>&nbsp;&nbsp;'}
            {else}
              {if($product->promotion != 0)}
                {!echo "<strong class='text-muted'>"  .'</strong>'}
                {!echo "<strong class='text-danger'>" . $control->config->product->currencySymbol . $product->promotion . '</strong>&nbsp;&nbsp;'}
              {else}
                {if($product->price != 0)}
                  {!echo "<strong class='text-danger'>" . $control->config->product->currencySymbol . $product->price . '</strong>&nbsp;&nbsp;'}
                {/if}
              {/if}
            {/if}
          {/if}
        </td>
        <td class="w-100px">
          {if(!$product->unsaleable and commonModel::isAvailable('shop'))}
            {if($product->negotiate)}
              {!echo html::a(helper::createLink('company', 'contact'), $lang->product->contact, "class='btn btn-xs btn-success'")}
            {else}
              {!echo html::a(inlink('view', "id=$product->id", "category={{$product->category->alias}}&name=$product->alias"), $lang->product->buyNow, "class='btn btn-xs btn-success'")}
            {/if}
          {else}
            {!echo html::a(inlink('view', "id=$product->id", "category={{$product->category->alias}}&name=$product->alias"), $lang->product->detail, "class='btn btn-xs btn-success'")}
          {/if}
        </td>
      </tr>
      {/foreach}
    </tbody>
  </table>
</section>
