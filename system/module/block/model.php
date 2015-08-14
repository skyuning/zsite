<?php
/**
 * The model file of block module of chanzhiEPS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPLV1 (http://www.chanzhi.org/license/)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     block
 * @version     $ID$
 * @link        http://www.chanzhi.org
 */
class blockModel extends model
{
    public $counter;

    /**
     * Get block by id.
     * 
     * @param string $blockID 
     * @access public
     * @return object   the block.
     */
    public function getByID($blockID)
    {
        $block = $this->dao->findByID($blockID)->from(TABLE_BLOCK)->fetch();
        if(strpos($block->type, 'code') === false) $block->content = json_decode($block->content);
        if(empty($block->content)) $block->content = new stdclass();
        return $block;
    }

    /**
     * Get block list of one site.
     * 
     * @param  string    $template
     * @param  object    $pager 
     * @access public
     * @return array
     */
    public function getList($template, $pager = null)
    {
        return $this->dao->select('*')->from(TABLE_BLOCK)->where('template')->eq($template)->orderBy('id_desc')->page($pager)->fetchAll('id');
    }

    /**
     * Get block list of one region.
     * 
     * @param  string    $module 
     * @param  string    $method 
     * @access public
     * @return array
     */
    public function getPageBlocks($module, $method)
    {
        $device = helper::getDevice();
        $pages  = "all,{$module}_{$method}";
        $rawLayouts = $this->dao->select('*')->from(TABLE_LAYOUT)
            ->where('page')->in($pages)
            ->andWhere('template')->eq(!empty($this->config->template->{$device}->name) ? $this->config->template->{$device}->name : 'default')
            ->andWhere('theme')->eq(!empty($this->config->template->{$device}->theme) ? $this->config->template->{$device}->theme : 'default')
            ->fetchGroup('page', 'region');

        $blocks = $this->dao->select('*')->from(TABLE_BLOCK)->fetchAll('id');

        $layouts = array();
        foreach($rawLayouts as $page => $pageBlocks) 
        {
            $layouts[$page] = array();
            foreach($pageBlocks as $region => $regionBlock)
            {
                $layouts[$page][$region] = array();

                $regionBlocks = json_decode($regionBlock->blocks);

                foreach($regionBlocks as $block)
                {
                    if(!empty($blocks[$block->id]))
                    {
                        $block->title    = $blocks[$block->id]->title;
                        $block->type     = $blocks[$block->id]->type;
                        $block->content  = $blocks[$block->id]->content;
                        $block->template = $blocks[$block->id]->template;
                    }
                    else
                    {
                        $block->title    = '';
                        $block->type     = '';
                        $block->content  = '';
                        $block->template = '';
                    }

                    $mergedBlock = new stdclass();
                    
                    if(!empty($block->children))
                    {
                        $mergedBlock->id = '';
                        $children = array();
                        foreach($block->children as $child)
                        {
                            if(!empty($blocks[$child->id]))
                            {
                                $child->title    = $blocks[$child->id]->title;
                                $child->type     = $blocks[$child->id]->type;
                                $child->content  = $blocks[$child->id]->content;
                                $child->template = $blocks[$child->id]->template;
                            }
                            else
                            {
                                $child->title    = '';
                                $child->type     = '';
                                $child->content  = '';
                                $child->template = '';
                            }

                            $mergedChild = new stdclass();
                            $mergedChild->id         = $child->id;
                            $mergedChild->title      = $child->title;
                            $mergedChild->type       = $child->type;
                            $mergedChild->content    = $child->content;
                            $mergedChild->template   = $child->template;
                            $mergedChild->grid       = $child->grid;
                            $mergedChild->titleless  = $child->titleless;
                            $mergedChild->borderless = $child->borderless;
                            $children[] = $mergedChild;
                        }
                        $mergedBlock->children = $children;
                    }
                    else
                    {
                        $mergedBlock->id       = $block->id;
                        $mergedBlock->title    = $block->title;
                        $mergedBlock->type     = $block->type;
                        $mergedBlock->content  = $block->content;
                        $mergedBlock->template = $block->template;
                    }

                    if(isset($block->grid))       $mergedBlock->grid       = $block->grid;
                    if(isset($block->titleless))  $mergedBlock->titleless  = $block->titleless;
                    if(isset($block->borderless)) $mergedBlock->borderless = $block->borderless;
                    $layouts[$page][$region][] = $mergedBlock;
                }
            }
        }
        return $layouts;
    }

    /**
     * Get block list of one region.
     * 
     * @param  string    $page 
     * @param  string    $region 
     * @param  string    $template 
     * @access public
     * @return array
     */
    public function getRegionBlocks($page, $region, $template, $theme)
    {
        $regionBlocks = $this->dao->select('*')->from(TABLE_LAYOUT)->where('page')->eq($page)->andWhere('region')->eq($region)->andWhere('template')->eq($template)->andWhere('theme')->eq($theme)->fetch('blocks');
        $regionBlocks = json_decode($regionBlocks);
        if(empty($regionBlocks)) return array();

        $blocks = $this->dao->select('*')->from(TABLE_BLOCK)->fetchAll('id');

        $sortedBlocks = array();
        foreach($regionBlocks as $block) 
        {
            if(!empty($blocks[$block->id]))
            {
                $block->title    = $blocks[$block->id]->title;
                $block->type     = $blocks[$block->id]->type;
                $block->template = $blocks[$block->id]->template;
                $block->content  = $blocks[$block->id]->content;
            }
            else
            {
                $block->title    = '';
                $block->type     = '';
                $block->template = '';
                $block->content  = '';

            }

            $rawBlock = new stdclass();
            if(!empty($block->children))
            {
                $rawBlock->id = '';
                $children = array();
                foreach($block->children as $child)
                {
                    if(!empty($blocks[$child->id]))
                    {
                        $child->title    = $blocks[$child->id]->title;
                        $child->type     = $blocks[$child->id]->type;
                        $child->content  = $blocks[$child->id]->content;
                        $child->template = $blocks[$child->id]->template;
                    }
                    else
                    {
                        $child->title    = '';
                        $child->type     = '';
                        $child->content  = '';
                        $child->template = '';
                    }

                    $rawChild = new stdclass();
                    $rawChild->grid       = $child->grid;
                    $rawChild->titleless  = $child->titleless;
                    $rawChild->borderless = $child->borderless;
                    $rawChild->id         = $child->id;
                    $rawChild->title      = $child->title;
                    $rawChild->type       = $child->type;
                    $rawChild->content    = $child->content;
                    $rawChild->template   = $child->template;
                    $children[] = $rawChild;
                }
                $rawBlock->children = $children;
            }
            else
            {
                $rawBlock->id       = $block->id;
                $rawBlock->title    = $block->title;
                $rawBlock->type     = $block->type;
                $rawBlock->content  = $block->content;
                $rawBlock->template = $block->template;
            }

            $rawBlock->grid       = $block->grid;
            $rawBlock->titleless  = $block->titleless;
            $rawBlock->borderless = $block->borderless;

            $sortedBlocks[] = $rawBlock;
        }
        return $sortedBlocks;
    }

    /**
     * Get block id => title pairs.
     * 
     * @access public
     * @return array
     */
    public function getPairs($template)
    {
        return $this->dao->select('id, title')->from(TABLE_BLOCK)->where('template')->eq($template)->fetchPairs();
    }
    
    /**
     * Create type  select area.
     * 
     * @param  string    $template 
     * @param  string    $type 
     * @param  int       $blockID 
     * @access public
     * @return string
     */
    public function createTypeSelector($template, $type, $blockID = 0)
    {
        $select = "<div class='btn-group'><button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>";
        $select .= $this->lang->block->$template->typeList[$type] . " <span class='caret'></span></button>";
        $select .= "<ul class='dropdown-menu' role='menu'>";
        foreach($this->lang->block->$template->typeGroups as $block => $group)
        {
            if(isset($lastGroup) and $group !== $lastGroup) $select .= "<li class='divider'></li>";
            $lastGroup = $group;
            $class = ($block == $type) ? "class='active'" : '';
            if($blockID)
            {
                $select .= "<li {$class}>" . html::a(helper::createLink('block', $this->app->getMethodName(), "blockID={$blockID}&type={$block}"), $this->lang->block->$template->typeList[$block]) . "</li>";
            }
            else
            {
                $select .= "<li {$class}>" . html::a(helper::createLink('block', $this->app->getMethodName(), "type={$block}"), $this->lang->block->$template->typeList[$block]) . "</li>";
            }
        }
        $select .= "</ul></div>" .  html::hidden('type', $type);
        return $select;
    }

    /**
     * Create form entry of one block backend.
     * 
     * @param  string  $template
     * @param  object  $block 
     * @param  mix     $key 
     * @param  int     $grade 1,2
     * @access public
     * @return void
     */
    public function createEntry($template, $region, $block = null, $key, $grade = 1)
    {
        $blockOptions[''] = $this->lang->block->select;
        $blockOptions += $this->getPairs($template);

        $blockID = isset($block->id) ? $block->id : '';
        $type    = isset($block->type) ? $block->type : '';
        $grid    = isset($block->grid) ? $block->grid : '';

        $entry = "<div class='block-item row' data-block='{$key}' data-id='{$blockID}'>";
        $readonly = !empty($block->children) ? "readonly='readonly'" : '';
        $entry .= "<div class='col col-type'>" . html::select("blocks[{$key}]", $blockOptions, $blockID, "class='form-control block' id='block_{$key}' $readonly") . "</div>";
        $entry .= "<div class='col col-grid'><div class='input-group'><span class='input-group-addon'>{$this->lang->block->grid}</span>" . html::select("grid[{$key}]", $this->lang->block->gridOptions, $grid, "class='form-control'") . '</div></div>';

        $titlelessChecked  = isset($block->titleless) && $block->titleless ? 'checked' : '';
        $borderlessChecked = isset($block->borderless) && $block->borderless ? 'checked' : '';
        $containerChecked  = isset($block->container) && $block->container ? 'checked' : '';
        $entry .= "
            <div class='text-center col col-style'>
               <label>
                 <input type='checkbox' {$titlelessChecked} value='1'><input type='hidden' name='titleless[{$key}]' /><span>{$this->lang->block->titleless}</span>
               </label>
              <label>
                <input type='checkbox' {$borderlessChecked} value='1'><input type='hidden' name='borderless[{$key}]' /><span>{$this->lang->block->borderless}</span>
              </label>
            </div>";

        $entry .= '<div class="col col-actions actions">';
        if($grade == 1) $entry .= html::a('javascript:;', $this->lang->block->add, "class='plus'");
        if($grade == 2) $entry .= html::a('javascript:;', $this->lang->block->add, "class='plus-child'");
        $entry .= html::a('javascript:;', $this->lang->delete, "class='delete'");
        $entry .= html::a(inlink('edit', "blockID={$blockID}&type={$type}"), $this->lang->edit, "class='edit'");
        if($grade == 1) $entry .= html::a('javascript:;', $this->lang->block->addChild, "class='btn-add-child'");
        $entry .= '</div>';
        $entry .= "<div class='col col-move'><span class='sort-handle sort-handle-{$grade}'><i class='icon-move'></i> {$this->lang->block->sort}</span></div>";
        if($grade == 1)
        {
            $entry .= "<div class='children'>";
            if(!empty($block->children))
            {
                foreach($block->children as $child)
                {
                    $key ++;
                    $entry .= $this->createEntry($template, $region, $child, $key, 2);
                }
            }
            $entry .= '</div>';
        }
        if($grade == 2) $entry .= html::hidden("parent[{$key}]", '');
        $entry .= "</div>";
        if($grade == 1) $this->counter = $key;
        return $entry;
    }

    /**
     * Create a block.
     * 
     * @param  string  $template
     * @access public
     * @return bool
     */
    public function create($template, $theme)
    {
        $block = fixer::input('post')->add('template', $template)->stripTags('content', $this->config->block->allowedTags)->get();
        if($this->post->type == 'phpcode') $block = fixer::input('post')->add('template', $template)->get();

        $gpcOn = (version_compare(phpversion(), '5.4', '<') and get_magic_quotes_gpc());

        if(!isset($block->params)) $block->params = array();
        $block->params['custom'][$theme]['css'] = $block->css;
        $block->params['custom'][$theme]['js']  = $block->js;
        foreach($block->params as $field => $value)
        {
            if($field == 'category' and is_array($value)) $block->params[$field] = join($value, ',');
        }
        if($this->post->content) $block->params['content'] = $gpcOn ? stripslashes($block->content) : $block->content;
        $block->content = helper::jsonEncode($block->params);

        $this->dao->insert(TABLE_BLOCK)->data($block, 'params,uid,css,js')->batchCheck($this->config->block->require->create, 'notempty')->autoCheck()->exec();

        $blockID = $this->dao->lastInsertID();
        $this->loadModel('file')->updateObjectID($this->post->uid, $blockID, 'block');

        return true;
    }

    /**
     * Update  block.
     * 
     * @param  string  $template
     * @access public
     * @return bool
     */
    public function update($template, $theme)
    {
        $block = $this->getByID($this->post->blockID);

        $data = fixer::input('post')->add('template', $template)->stripTags('content', $this->config->block->allowedTags)->get();
        if($this->post->type == 'phpcode') $data = fixer::input('post')->add('template', $template)->get();

        $gpcOn = (version_compare(phpversion(), '5.4', '<') and get_magic_quotes_gpc());

        if(!isset($data->params)) $data->params = array();
        $data->params['custom'][$theme]['css'] = $data->css;
        $data->params['custom'][$theme]['js']  = $data->js;
        foreach($data->params as $field => $value)
        {
            if($field == 'category' and is_array($value)) $data->params[$field] = join($value, ',');
        }

        if(isset($block->content->custom))
        {
            foreach($block->content->custom as $field => $value)
            {
                if(!isset($data->params['custom'][$field])) $data->params['custom'][$field] = $value;
            }
        }

        if($this->post->content) $data->params['content'] = $gpcOn ? stripslashes($data->content) : $data->content;
        $data->content = helper::jsonEncode($data->params);

        $this->dao->update(TABLE_BLOCK)->data($data, 'params,uid,blockID,css,js')
            ->batchCheck($this->config->block->require->edit, 'notempty')
            ->autoCheck()
            ->where('id')->eq($this->post->blockID)
            ->exec();

        $this->loadModel('file')->updateObjectID($this->post->uid, $this->post->blockID, 'block');
        return true;
    }

    /**
     * Delete one block.
     * 
     * @param  int    $blockID 
     * @param  null    $table 
     * @access public
     * @return bool
     */
    public function delete($blockID, $table = null)
    {
        $this->dao->delete()->from(TABLE_BLOCK)->where('id')->eq($blockID)->exec();
        return !dao::isError();
    }

    /**
     * Set block of one region.
     * 
     * @param string $page 
     * @param string $region 
     * @param string $template 
     * @param string $theme 
     * @access public
     * @return bool
     */
    public function setRegion($page, $region, $template, $theme)
    {
        $layout = new stdclass();
        $layout->page     = $page;
        $layout->region   = $region;
        $layout->template = $template;
        $layout->theme    = $theme;

        if(!$this->post->blocks)
        {
            $this->dao->delete()->from(TABLE_LAYOUT)->where('page')->eq($page)->andWhere('region')->eq($region)->andWhere('template')->eq($template)->andWhere('theme')->eq($theme)->exec();
            if(!dao::isError()) return true;
        }

        $blocks = array();
        foreach($this->post->blocks as $key => $block)
        {
            $blocks[$key]['id']         = $block;
            $blocks[$key]['grid']       = $this->post->grid[$key];
            $blocks[$key]['titleless']  = $this->post->titleless[$key];
            $blocks[$key]['borderless'] = $this->post->borderless[$key];
        }

        /* Compute children blocks. */
        if($this->post->parent)
        {
            $parents = (array) $this->post->parent;
            foreach($parents as $key => $parent) $children[$parent][] = $key;
            foreach($blocks as $key => $block)
            { 
                if(empty($children[$key])) continue;
                foreach($children[$key] as $child)
                {
                    $blocks[$key]['children'][] = $blocks[$child];
                    unset($blocks[$child]);
                }
            }
        }

        /* Clear blocks keys. */
        $sortedBlocks = array();
        foreach($blocks as $key => $block) $sortedBlocks[] = $block;
        $layout->blocks = helper::jsonEncode($sortedBlocks);

        $this->dao->replace(TABLE_LAYOUT)->data($layout)->exec();

        return !dao::isError();
    }

    /**
     * Print blocks of one region.
     * 
     * @param  array    $blocks 
     * @param  string   $method 
     * @param  string   $region 
     * @param  bool     $withGrid 
     * @param  string   $containerHeader 
     * @param  string   $containerFooter 
     * @access public
     * @return string
     */
    public function printRegion($blocks, $method = '', $region = '', $withGrid = false, $containerHeader = '', $containerFooter = '')
    {
        if(!isset($blocks[$method][$region])) return '';
        $blocks = $blocks[$method][$region];
        $html   = '';
        foreach($blocks as $block) $html .= $this->parseBlockContent($block, $withGrid, $containerHeader, $containerFooter);
        echo $html;
    }

    /**
     * Parse the content of one block.
     * 
     * @param  object    $block 
     * @param  bool      $withGrid          
     * @param  string    $containerHeader 
     * @param  string    $containerFooter 
     * @access private
     * @return string
     */
    private function parseBlockContent($block, $withGrid = false, $containerHeader, $containerFooter)
    {
        $withGrid = ($withGrid and isset($block->grid));
        if(!empty($block->children)) 
        {
            if($withGrid)
            {
                if($block->grid == 0) echo "<div class='col-md-12 col-auto'><div class='row'>";
                else echo "<div class='col-md-{$block->grid}' data-grid='{$block->grid}'><div class='row'>";
            }

            foreach($block->children as $child) $this->parseBlockContent($child, $withGrid, $containerHeader, $containerFooter);

            if($withGrid) echo '</div></div>';
        }
        else
        {
            if($withGrid)
            {
                if(!isset($block->grid)) $block->grid = 12; 
                if($block->grid == 0)
                {
                    echo "<div class='col-md-4 col-auto'>";
                }
                else
                {
                    echo "<div class='col-md-{$block->grid}' data-grid='{$block->grid}'>";
                }
            }

            $device   = helper::getDevice();
            $template = $this->config->template->{$device}->name;
            $theme    = $this->config->template->{$device}->theme;
            $tplPath  = $this->app->getTplRoot() . $template . DS . 'view' . DS . 'block' . DS;

            /* First try block/ext/sitecode/view/block/ */
            $extBlockRoot = $tplPath . "/ext/_{$this->config->site->code}/";
            $blockFile    = $extBlockRoot . strtolower($block->type) . '.html.php';

            /* Then try block/ext/view/block/ */
            if(!file_exists($blockFile))
            {
                $extBlockRoot = $tplPath . 'ext' . DS;
                $blockFile    = $extBlockRoot . strtolower($block->type) . '.html.php';

                /* No ext file, use the block/view/block/. */
                if(!file_exists($blockFile))
                {
                    $blockFile = $tplPath . strtolower($block->type) . '.html.php';
                    if(!file_exists($blockFile))
                    {
                        if($withGrid) echo '</div>';
                        return '';
                    }
                }
            }

            foreach($this->config->block->categoryList as $category => $typeList)
            {
                if(strpos($typeList, ",{$block->type},") !== false) $blockCategory = $category;
            }

            $blockClass = "block-{$blockCategory}-{$block->type}";
            if(isset($block->borderless) and $block->borderless) $blockClass .= ' panel-borderless';
            if(isset($block->titleless) and $block->titleless) $blockClass  .= ' panel-titleless';

            $content = is_object($block->content) ? $block->content : json_decode($block->content);
            if(isset($content->class)) $blockClass .= ' ' . $content->class;

            if(isset($this->config->block->defaultIcons[$block->type])) 
            {
                $defaultIcon = $this->config->block->defaultIcons[$block->type];
                $iconClass   = isset($content->icon) ? $content->icon : $defaultIcon;
                $icon        = $iconClass ? "<i class='icon panel-icon {$iconClass}'></i> " : "" ;
            }

            $style  = '<style>';
            if(isset($content->custom->$theme))
            {
                $style .= '#block' . $block->id . '{';
                $style .= !empty($content->custom->$theme->backgroundColor) ? 'background-color:' . $content->custom->$theme->backgroundColor . ' !important;' : '';
                $style .= !empty($content->custom->$theme->textColor) ? 'color:' . $content->custom->$theme->textColor . ' !important;;' : '';
                $style .= !empty($content->custom->$theme->borderColor) ? 'border-color:' . $content->custom->$theme->borderColor . ' !important;' : '';
                $style .= '}';
                $style .= '#block' . $block->id . ' .panel-heading{';
                $style .= !empty($content->custom->$theme->titleColor) ? 'color:' .$content->custom->$theme->titleColor . ';' : '';
                $style .= !empty($content->custom->$theme->titleBackground) ? 'background:' .$content->custom->$theme->titleBackground . ' !important;;' : '';
                $style .= '}';
                $style .= !empty($content->custom->$theme->iconColor) ? '#block' . $block->id . ' .panel-icon {color:' .$content->custom->$theme->iconColor . ' !important;}' : '';
                $style .= !empty($content->custom->$theme->linkColor) ? '#block' . $block->id . ' a{color:' .$content->custom->$theme->linkColor . ' !important;}' : '';
                $style .= isset($content->custom->$theme->paddingTop) ? '#block' . $block->id . ' .panel-body' . '{padding-top:' . $content->custom->$theme->paddingTop . 'px !important;}' : '';
                $style .= isset($content->custom->$theme->paddingRight) ? '#block' . $block->id . ' .panel-body' . '{padding-right:' . $content->custom->$theme->paddingRight . 'px !important;}' : '';
                $style .= isset($content->custom->$theme->paddingBottom) ? '#block' . $block->id . ' .panel-body' . '{padding-bottom:' . $content->custom->$theme->paddingBottom . 'px !important;}' : '';
                $style .= isset($content->custom->$theme->paddingLeft) ? '#block' . $block->id . ' .panel-body' . '{padding-left:' . $content->custom->$theme->paddingLeft . 'px !important;}' : '';
                if(!empty($content->custom->$theme->css))
                {
                    $customStyle = str_ireplace('#blockID', "#block{$block->id}", $content->custom->$theme->css);
                    $lessc = $this->app->loadClass('lessc');
                    $lessc->setFormatter("compressed");
                    $customStyle = $lessc->compile($customStyle);
                    $style .= $customStyle;
                }
            }
            $style .= '</style>';
            $script = !empty($content->custom->$theme->js) ? "<script language='Javascript'>" . str_ireplace('#blockID', "#block{$block->id}", $content->custom->$theme->js) . "</script>" : '';

            echo $containerHeader;
            if(file_exists($blockFile)) include $blockFile;
            echo $style;
            echo $script;
            echo $containerFooter;

            if($withGrid) echo "</div>";
        }
    }

    /**
     * Load language from a template.
     * 
     * @param  string $template
     * @access public
     * @return void
     */
    public function loadTemplateLang($template)
    {
        $this->app->loadLang('block');
    }
}
