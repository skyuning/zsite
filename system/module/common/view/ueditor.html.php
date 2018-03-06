<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php
$module = $this->moduleName;
$method = $this->methodName;
js::set('themeRoot', $themeRoot);
if(!isset($config->$module->editor->$method)) return;
$editor = $config->$module->editor->$method;
$editor['id'] = explode(',', $editor['id']);
$editorLangs  = array('en' => 'en', 'zh-cn' => 'zh-cn', 'zh-tw' => 'zh-tw');
$editorLang   = isset($editorLangs[$app->getClientLang()]) ? $editorLangs[$app->getClientLang()] : 'en';

/* set uid for upload. */
$uid = uniqid('');
js::set('kuid', $uid);
?>
<script type="text/javascript" charset="utf-8" src="<?php echo $this->app->getWebRoot() . "js/"?>ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo $this->app->getWebRoot() . "js/"?>ueditor/ueditor.all.min.js"> </script>
<script language='javascript'>
var editor = <?php echo json_encode($editor);?>;

var toolbars = [[
    'paragraph', 'fontfamily', 'fontsize', '|',
    'forecolor', 'backcolor', 'bold', 'italic', 'underline', 'strikethrough', 'blockquote', 'pasteplain', '|',
    'insertorderedlist', 'insertunorderedlist', 'justifyleft', 'justifycenter', 'justifyright', '|'],
    ['simpleupload', 'insertcode', '|',
    'link', 'unlink', '|',
    'inserttable', '|',
    'fullscreen', 'source', '|',
    'preview', 'help'
]];

var simple =
[ 'formatblock', 'fontsize', '|', 'bold', 'italic','underline', '|',
'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist', 'insertunorderedlist', '|',
'emoticons', 'image', 'link', '|', 'removeformat','undo', 'redo', 'source' ];
var simple = [[
    'paragraph', 'fontfamily', 'fontsize', 'lineheight', '|',
    'bold', 'italic', 'underline', 'strikethrough', '|',
    'justifyleft', 'justifycenter', 'justifyright', '|',
    'pasteplain', 'emotion', 'simpleupload', '|', 
    'link', 'unlink', 'anchor',
    'undo', 'redo', 'removeformat','insertorderedlist', 'insertunorderedlist', '|',
    'source', 'help']];

var full = [[
    'paragraph', 'fontfamily', 'fontsize', 'lineheight', '|',
    'forecolor', 'backcolor', '|', 
    'bold', 'italic', 'underline', 'strikethrough', '|',
    'justifyleft', 'justifycenter', 'justifyright', '|',
    'pasteplain', 'emotion', 'simpleupload', 'insertimage', '|', 
    'link', 'unlink', 'anchor', 'insertvideo', 'map'],
    ['undo', 'redo', 'removeformat', 'insertcode', '|',
    'insertorderedlist', 'insertunorderedlist', 'inserttable', '|',
    'indent', 'fullscreen', '|',
    'preview', 'source', 'searchreplace', 'help']];
$(document).ready(initUeditor);
function initUeditor(afterInit)
{
    $(':input[type=submit]').after("<input type='hidden' id='uid' name='uid' value=" + v.kuid + ">");
    var options = 
    {
        lang: '<?php echo $editorLang?>',
        toolbars: <?php echo $editor['tools']?>,
        serverUrl: '<?php echo $this->createLink('file', 'apiforueditor', "uid=$uid")?>',
        autoClearinitialContent:false,
        wordCount:false,
        <?php if($editorLang != 'zh-cn' and $editorLang != 'zh-tw') echo "iframeCssUrl:'',"; //When lang is zh-cn or zh-tw then load ueditor/themes/iframe.css file for font-family and size of editor.?>
        enableAutoSave:false,
        elementPathEnabled:false
    };
    $.each(editor.id, function(key, editorID)
    {
        if(!window.editor) window.editor = {};
        if($('#' + editorID).size() != 0)
        {
            ueditor = UE.getEditor(editorID, options);
            window.editor['#'] = window.editor[editorID] = ueditor;
            ueditor.addListener('ready', function()
            {
                $('#' + editorID).find('.edui-editor').css('z-index', '5');
            });
        }
    });

    if($.isFunction(afterInit)) afterInit();
}
</script>
