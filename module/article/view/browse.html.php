<?php 
include '../../common/view/header.html.php';

$categoryPath = array_keys($category->pathNames);
js::set('categoryPath', json_encode($categoryPath));

include '../../common/view/treeview.html.php';
?>
<?php echo $common->printPositionBar($category);?>
<div class='row'>
  <div class='span9'>
    <div class='widget widget-category radius'>
      <h4><?php echo $category->name;?></h4>
      <ul class='article-list'>
      <?php foreach($articles as $article):?>
        <li>
          <?php 
          if(empty($article->images)) $firstImage = false;

          $firstImage = $article->images[0];
          $imageURL   = $firstImage ? $firstImage->smallURL : $themeRoot . 'default/images/main/noimage.jpg';
          echo html::a(inlink('view', "id=$article->id"), html::image($imageURL, "title='{$file->title}'"), '', "class='f-left article-img'");
          ?>
          <div class='f-right w-p75'>
            <h5 class='article-title f-left'><?php echo html::a(inlink('view', "id=$article->id"), $article->title);?></h5>
            <span class='f-left'><?php echo $article->addedDate;?></span>
            <div class='c-both'></div>
            <?php
            $summary = $article->summary;
            if(empty($article->summary)) $summary = html::cutString($article->content, 300);
            ?>
            <div class='summary'>
              <?php echo $summary;?> 
            </div>
          </div>
          <div class='c-both'></div>
        </li>
      <?php endforeach;?>
      </ul>
      <div class='w-p95 pd-10px'><?php $pager->show('right', 'short');?></div>
      <div class='c-both'></div>
    </div>
  </div>
  <?php include '../../common/view/side.html.php';?>
</div>
<?php include '../../common/view/footer.html.php';?>
