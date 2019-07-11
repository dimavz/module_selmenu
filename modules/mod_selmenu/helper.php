<?php
defined('_JEXEC') or die;

class ModSelMenuHelper
{

    public static function &getList(&$params)
    {
//        echo "<pre>";
//        print_r($params);
//        echo "</pre>";
//        exit();
        $ids_excl = $params->get('excluded_ids');
        $excluded = array();
        if(!empty($ids_excl)){
            $excluded = explode(',',$ids_excl);
        }
        $listSelItems = $params->get('treemenuitems');

//        echo "<pre>";
//        print_r($excluded);
//        echo "</pre>";
//        exit();

        JLoader::register('MenusHelper', JPATH_ADMINISTRATOR . '/components/com_menus/helpers/menus.php');
        $menuTypes = MenusHelper::getMenuLinks();

        $menu = JFactory::getApplication()->getMenu('site');
        $items = $menu->getMenu();

//        echo "<pre>";
//        print_r($items);
//        echo "</pre>";
//        exit();

        $listLinks = array();
        foreach ($menuTypes as $key => $mt_item) {
            if (!empty($mt_item->links)) {
                foreach ($mt_item->links as $k => &$link_item) {
                    foreach ($items as $itm) {

                        if ($itm->id == $link_item->value) {
                            $link_item->link = $itm->link;
                            $link_item->parent_id = $itm->parent_id;
                        }
                    }
                    if (in_array($link_item->value,$listSelItems)){
                        if(!empty($excluded)){
                            if (in_array($link_item->value,$excluded)){
                                $link_item->selected = 0;
                            }
                            else{
                                $link_item->selected = 1;
                            }
                        }
                        else{
                            $link_item->selected = 1;
                        }

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
//        print_r($listLinks['level_1']);
//        echo "</pre>";
//        exit();

        return $listLinks;
    }
}