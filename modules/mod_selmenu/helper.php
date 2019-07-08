<?php
defined('_JEXEC') or die;

class ModSelMenuHelper
{

    public static function &getList(&$params)
    {
//        echo "<pre>";
//        print_r($params);
//        echo "</pre>";
////        exit();
        $listSelItems = $params->get('treemenuitems');

        JLoader::register('MenusHelper', JPATH_ADMINISTRATOR . '/components/com_menus/helpers/menus.php');
        $menuTypes = MenusHelper::getMenuLinks();

        $menu = JFactory::getApplication()->getMenu('site');
        $items = $menu->getMenu();

        $listLinks = array();
        foreach ($menuTypes as $key => $mt_item) {
            if (!empty($mt_item->links)) {
                foreach ($mt_item->links as $k => &$link_item) {
                    foreach ($items as $itm) {

                        if ($itm->id == $link_item->value) {
                            $link_item->link = $itm->link;
                        }
                    }
                    if (in_array($link_item->value,$listSelItems)){
                        $link_item->selected = 1;
                    }
                    else{
                        $link_item->selected = 0;
                    }
                    $link_item->menu_id = $mt_item->id;
                    $listLinks['level_'.$link_item->level][] = $link_item;
                }
            }
        }
//        echo "<pre>";
//        print_r($listLinks);
//        echo "</pre>";
//        exit();

        return $listLinks;
    }
}