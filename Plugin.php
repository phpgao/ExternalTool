<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 * 链接替换
 *
 * @package ExternalTool
 * @author 老高
 * @version 0.2
 * @link http://www.phpgao.com
 */
class ExternalTool_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     *
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
        Typecho_Plugin::factory('Widget_Abstract_Contents')->contentEx = array('ExternalTool_Plugin', 'replace');
        Typecho_Plugin::factory('Widget_Abstract_Contents')->excerptEx = array('ExternalTool_Plugin', 'replace');
    }

    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     *
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate(){}

    /**
     * 获取插件配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form)
    {

    }

    /**
     * 个人用户的配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}

    /**
     * 插件实现方法
     *
     * @access public
     * @param html $string
     * @return string
     */
    public static function replace($content, $class, $string)
    {

        $html_string = is_null($string) ? $content : $string;

        class_exists('simple_html_dom') || require_once 'simple_html_dom.php';

        $html = str_get_html($html_string);

        $html = self::external_tool($html);

        return $html->save();

    }

    public static function external_tool($dom)
    {
        //Helper::options()->plugin(str_replace('_Plugin','',__CLASS__))
        $domain = parse_url( Helper::options()->siteUrl);
        $domain =$domain['host'];

        foreach($dom->find('a[href^="http"]') as $a){
            $a_tmp = parse_url($a->href);

            if($a_tmp['host'] != $domain){
                $a->setAttribute('class', 'external');
            }else{
                $a->setAttribute('class', 'internal');
            }
        }

        return $dom;

    }

}