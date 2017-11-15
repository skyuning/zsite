<?php
/**
 * The control file of source of ChanzhiEPS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Xiying Guan <guanxiying@xirangit.com>
 * @package     source
 * @version     $Id$
 * @link        http://www.chanzhi.org
 */
class source extends control
{
    /**
     * Display css codes.
     * 
     * @param  string $page 
     * @access public
     * @return void
     */
    public function css($page, $version = '')
    {
        $seconds      = 3600 * 24 * 30; 
        $expires      = gmdate("D, d M Y H:i:s", time() + $seconds) . " GMT";
        $lastModified = date('D, d M Y H:i:s \G\M\T', $version);

        /* Check if the client has the same page cached. */
        if(isset($_SERVER["HTTP_IF_MODIFIED_SINCE"]) && ($_SERVER["HTTP_IF_MODIFIED_SINCE"] == $lastModified))
        {
            header("HTTP/1.1 304 Not Modified"); exit;
        }

        header('Content-type: text/css');
        header("Expires: $expires");
        header("Pragma: cache");
        header("Cache-Control: max-age=$seconds");
        header("Last-Modified: " . $lastModified);

        if($this->config->cache->type != 'close')
        {
            $key = strtolower("/css/$page");
            $css = $this->app->cache->get($key);
        }
        else
        {
            $cacheFile = $this->app->getTmpRoot() . 'cache' . DS . $this->app->getClientLang() . DS . 'css' . DS . $page . '.css';
            $css       = file_get_contents($cacheFile);
        }
        $page = helper::safe64Decode($page);
        echo "/* Css for $page, Version=$version */\n $css";
        exit;
    }

    /**
     * Display js codes.
     * 
     * @param  string $page 
     * @access public
     * @return void
     */
    public function js($page = '', $version = '')
    {
        $seconds      = 3600 * 24 * 30; 
        $expires      = gmdate("D, d M Y H:i:s", time() + $seconds) . " GMT";
        $lastModified = date('D, d M Y H:i:s \G\M\T', $version);

        /* Check if the client has the same page cached. */
        if(isset($_SERVER["HTTP_IF_MODIFIED_SINCE"]) && ($_SERVER["HTTP_IF_MODIFIED_SINCE"] == $lastModified))
        {
            header("HTTP/1.1 304 Not Modified"); exit;
        }

        header('Content-type: text/js');
        header("Expires: $expires");
        header("Pragma: cache");
        header("Cache-Control: max-age=$seconds");
        header("Last-Modified: " . $lastModified);

        if($this->config->cache->type != 'close')
        {
            $key = strtolower("/js/$page");
            $js  = $this->app->cache->get($key);
        }
        else
        {
            $cacheFile = $this->app->getTmpRoot() . 'cache' . DS . $this->app->getClientLang() . DS . 'js' . DS . $page . '.js';
            $js        = file_get_contents($cacheFile);
        }
        $page = helper::safe64Decode($page);
        echo "/* Js for $page, Version=$version */\n $js";
        exit;
    }
}
