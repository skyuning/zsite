{!echo '<?xml version="1.0" encoding="UTF-8" ?>'}
<urlset xmlns="http://www.google.com/schemas/sitemap/0.84" xmlns:mobile="http://www.google.com/schemas/sitemap-mobile/1.0">
{*php*}
  foreach($products as $product):
  $url = str_replace('&', '&amp;', $systemURL . helper::createLink('product', 'view', "id=$product->id", "category={$product->category->alias}&name=$product->alias", 'html'));
  $mobileUrl = str_replace('&', '&amp;', $systemURL . helper::createLink('product', 'view', "id=$product->id", "category={$product->category->alias}&name=$product->alias", 'mhtml'));
{*/php*}
  <url>
    <loc>{!echo $url}</loc>
    <lastmod>{!echo substr($product->editedDate, 0, 10)}</lastmod>
    <changefreq>daily</changefreq>
    <priority>0.9</priority>
  </url>
  <url>
    <loc>{!echo $mobileUrl}</loc>
    <mobile:mobile type='mobile'/> 
    <lastmod>{!echo substr($product->editedDate, 0, 10)}</lastmod>
    <changefreq>daily</changefreq>
    <priority>0.9</priority>
  </url>
  {/foreach}
{*php*}
  foreach($articles as $article):
  $url = str_replace('&', '&amp;', $systemURL . helper::createLink('article', 'view', "id=$article->id", "category={$article->category->alias}&name=$article->alias", 'html'));
  $mobileUrl = str_replace('&', '&amp;', $systemURL . helper::createLink('article', 'view', "id=$article->id", "category={$article->category->alias}&name=$article->alias", 'mhtml'));
{*/php*}
  <url>
    <loc>{!echo $url}</loc>
    <lastmod>{!echo substr($article->editedDate, 0, 10)}</lastmod>
    <changefreq>daily</changefreq>
    <priority>0.8</priority>
  </url>
  <url>
    <loc>{!echo $mobileUrl}</loc>
    <mobile:mobile type='mobile'/>
    <lastmod>{!echo substr($article->editedDate, 0, 10)}</lastmod>
    <changefreq>daily</changefreq>
    <priority>0.8</priority>
  </url>
  {/foreach}
  {if(commonModel::isAvailable('blog'))}
{*php*}
  foreach($blogs as $blog):
  $url = str_replace('&', '&amp;', $systemURL . helper::createLink('blog', 'view', "id=$blog->id", "category={$blog->category->alias}&name=$blog->alias", 'html'));
  $mobileUrl = str_replace('&', '&amp;', $systemURL . helper::createLink('blog', 'view', "id=$blog->id", "category={$blog->category->alias}&name=$blog->alias", 'mhtml'));
{*/php*}
  <url>
    <loc>{!echo $url}</loc>
    <lastmod>{!echo substr($blog->editedDate, 0, 10)}</lastmod>
    <changefreq>daily</changefreq>
    <priority>0.8</priority>
  </url>
  <url>
    <loc>{!echo $mobileUrl}</loc>
    <mobile:mobile type='mobile'/>
    <lastmod>{!echo substr($blog->editedDate, 0, 10)}</lastmod>
    <changefreq>daily</changefreq>
    <priority>0.8</priority>
  </url>
  {/foreach}
  {/if}
  {if(commonModel::isAvailable('book'))}
  {foreach($books as $nodeID => $node)}
{*php*}
  if($node->type != 'article') $url = str_replace('&', '&amp;', $systemURL . helper::createLink('book', 'browse', "nodeID=$node->id", "book={$node->book}&node={$node->alias}", 'html'));
  if($node->type == 'article') $url = str_replace('&', '&amp;', $systemURL . helper::createLink('book', 'read', "nodeID=$node->id", "book={$node->book}&node={$node->alias}", 'html'));
  if($node->type != 'article') $mobileUrl = str_replace('&', '&amp;', $systemURL . helper::createLink('book', 'browse', "nodeID=$node->id", "book={$node->book}&node={$node->alias}", 'mhtml'));
  if($node->type == 'article') $mobileUrl = str_replace('&', '&amp;', $systemURL . helper::createLink('book', 'read', "nodeID=$node->id", "book={$node->book}&node={$node->alias}", 'mhtml'));
{*/php*}
  <url>
    <loc>{!echo $url}</loc>
    <lastmod>{!echo substr($node->editedDate, 0, 10)}</lastmod>
    <changefreq>daily</changefreq>
    <priority>0.8</priority>
  </url>
  <url>
    <loc>{!echo $mobileUrl}</loc>
    <mobile:mobile type='mobile'/>
    <lastmod>{!echo substr($node->editedDate, 0, 10)}</lastmod>
    <changefreq>daily</changefreq>
    <priority>0.8</priority>
  </url>
  {/foreach}
  {/if}
  {if(commonModel::isAvailable('forum'))}
{*php*}
  foreach($threads as $id => $editedDate):
  $url = str_replace('&', '&amp;', $systemURL . helper::createLink('thread', 'view', "id=$id", 'html'));
  $mobileUrl = str_replace('&', '&amp;', $systemURL . helper::createLink('thread', 'view', "id=$id", '', 'mhtml'));
{*/php*}
  <url>
    <loc>{!echo $url}</loc>
    <lastmod>{!echo substr($editedDate, 0, 10)}</lastmod>
    <changefreq>daily</changefreq>
    <priority>0.8</priority>
  </url>
  <url>
    <loc>{!echo $mobileUrl}</loc>
    <mobile:mobile type='mobile'/>
    <lastmod>{!echo substr($editedDate, 0, 10)}</lastmod>
    <changefreq>daily</changefreq>
    <priority>0.8</priority>
  </url>
  {/foreach}
  {/if}
{*php*}
  foreach($pages as $page):
  $url = str_replace('&', '&amp;', $systemURL . helper::createLink('page', 'view', "id=$page->id", "name=$page->alias", 'html'));
  $mobileUrl = str_replace('&', '&amp;', $systemURL . helper::createLink('page', 'view', "id=$page->id", "name=$page->alias", 'mhtml'));
{*/php*}
  <url>
    <loc>{!echo $url}</loc>
    <lastmod>{!echo substr($page->editedDate, 0, 10)}</lastmod>
    <changefreq>daily</changefreq>
    <priority>0.8</priority>
  </url>
  <url>
    <loc>{!echo $mobileUrl}</loc>
    <mobile:mobile type='mobile'/>
    <lastmod>{!echo substr($page->editedDate, 0, 10)}</lastmod>
    <changefreq>daily</changefreq>
    <priority>0.8</priority>
  </url>
  {/foreach}
</urlset>
