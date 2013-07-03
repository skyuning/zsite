<?php
/**
 * The control file of article category of XiRangEPS.
 *
 * @copyright   Copyright 2013-2013 QingDao XiRang Network Infomation Co,LTD (www.xirang.biz)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     article
 * @version     $Id$
 * @link        http://www.xirang.biz
 */
class article extends control
{
    /** 
     * The index page, locate to the browse page.
     * 
     * @access public
     * @return void
     */
    public function index()
    {   
        $indexCategories = explode(',', $this->app->site->indexCategories);
        $defaultCategory = $indexCategories[0];
        $this->locate(inlink('browse', "category=$defaultCategory"));
    }   

    /** 
     * Browse article in front.
     * 
     * @param int $categoryID     the category id
     * @param string $orderBy   the order by
     * @param int $recTotal     record total
     * @param int $recPerPage   record per page
     * @param int $pageID       current page id
     * @access public
     * @return void
     */
    public function browse($categoryID = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {   
        $this->app->loadLang('user');

        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $childCategories = $this->loadModel('tree')->getAllChildID($categoryID);
        $articles        = $this->article->getList($childCategories, $orderBy, $pager);
        $category        = $this->tree->getById($categoryID);

        if($this->session->site->type == 'blog')
        {   
            $this->article->createDigest($articles);
            $this->view->comments   = $this->article->getCommentCounts(array_keys($articles));
            $this->view->categories = $this->tree->getPairs($childCategories);
        }   

        $this->view->header->title = $category->name;
        if($category)
        {
            $this->view->header->keywords = trim($category->keyword . ' ' . $this->app->site->keywords);
            if($category->desc) $this->view->header->desc = trim(preg_replace('/<[a-z\/]+.*>/Ui', '', $category->desc));
        }

        $this->view->category    = $category;
        $this->view->articles    = $articles;
        $this->view->pager       = $pager;
        $this->view->site        = $this->app->site;
        $this->view->layouts     = $this->loadModel('block')->getLayouts('article.list');
        $this->view->articleTree = $this->loadModel('tree')->getTreeMenu($this->view->category->tree, 0, array('treeModel', 'createBrowseLink'));

        $this->display();
    }

    /**
     * Browse article in admin.
     * 
     * @param string $tree      the article tree
     * @param int    $categoryID  the category id
     * @param string $orderBy   the order by
     * @param int    $recTotal 
     * @param int    $recPerPage 
     * @param int    $pageID 
     * @access public
     * @return void
     */
    public function browseAdmin($tree = 'article', $categoryID = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {   
        /* Set the session. */
        $this->session->set('articleList', $this->app->getURI(true));

        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $childCategories = $this->loadModel('tree')->getAllChildID($categoryID, $tree);
        $articles = $childCategories ? $this->article->getList($childCategories, $orderBy, $pager) : array();
        $this->view->articles   = $articles;
        $this->view->pager      = $pager;
        $this->view->category   = $this->tree->getById($categoryID);
        $this->view->tree       = $tree;
        $this->view->categories = $this->loadModel('tree')->getPairs();

        $this->display();
    }   

    /**
     * Create a article.
     * 
     * @param mixed $categoryID   the category or the tree
     * @param string $tree 
     * @access public
     * @return void
     */
    public function create()                                                                                                                      
    {
        $categoryID = $this->get->categoryID;
        $tree       ='article';    

        /* Set the mdoule and tree.  */ 
        $category   = $this->loadModel('tree')->getById($categoryID);                                                                                            
        $categoryID = 0;

        if($_POST)
        {
            $error = $this->article->validate();
            if(!empty($error)) $this->send(array('result'=> 'falt', 'message'=> $error));

            $result = $this->article->create();       
            if(dao::isError())  $this->send(array('result' => 'fail', 'message' => dao::geterror()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate'=>inlink('browseadmin')));
        }

        $this->view->category    = $category;
        $this->view->tree        = $this->loadModel('tree')->getOptionMenu($tree);
        $this->display();
    }

    /**
     * Edit a article.
     * 
     * @param string $articleID 
     * @param string $tree 
     * @access public
     * @return void
     */
    public function edit($articleID)
    {
        $this->view->article = $this->article->getById($articleID);
        if($_POST)
        {
            $error = $this->article->validate();
            if(!empty($error)) $this->send(array('result' => 'falt', 'message' => $error));

            $this->article->update($articleID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browseAdmin')));
        }

        $this->view->category = $this->loadModel('tree')->getById($this->view->article->category);
        $this->view->tree     = $this->loadModel('tree')->getOptionMenu($this->view->article->tree);
        $this->display();
    }

    /**
     * Update order fields.
     * 
     * @access public
     * @return void
     */
    public function updateOrder()
    {
        if($this->post->orders)
        {
            foreach($this->post->orders as $articleID => $order)
            {
                $this->dao->update(TABLE_ARTICLE)
                     ->set('`order`')->eq($order)
                     ->where('id')->eq($articleID)
                     ->limit(1)
                     ->exec(false);
            }
            die(js::reload('parent'));
        }
    }

    /**
     * View an article.
     * 
     * @param string $articleID 
     * @access public
     * @return void
     */
    public function view($articleID)
    {
        $article = $this->article->getById($articleID);
        if(RUN_MODE == 'front')
        {
            $this->view->layouts          = $this->loadModel('block')->getLayouts('article.view');
            $this->view->articleTree      = $this->loadModel('tree')->getTreeMenu('article', 0, array('treeModel', 'createBrowseLink'));
            $this->view->category         = $this->tree->getById($article->category);

            $this->view->header->title    = $article->title . (isset($this->view->category->name) ? '|' . $this->view->category->name : '');
            $this->view->header->keywords = trim($article->keywords . ' ' . $this->view->category->keyword . ' ' . $this->app->site->keywords);
            $this->view->header->desc     = trim($article->summary . ' ' .preg_replace('/<[a-z\/]+.*>/Ui', '', $this->view->category->desc));

            $this->dao->update(TABLE_ARTICLE)->set('views = views + 1')->where('id')->eq($articleID)->exec(false);
        }
        $this->view->article = $article;

        $this->display();
    }

    /**
     * Delete a article
     * 
     * @param string $articleID 
     * @param string $confirm 
     * @access public
     * @return void
     */
    public function delete($articleID)
    {
        $result = $this->article->delete($articleID);
        if($result) $this->send(array('result' => 'success'));
        $this->send(array('result' => 'fail', 'message' => dao::getError()));
    }
}
