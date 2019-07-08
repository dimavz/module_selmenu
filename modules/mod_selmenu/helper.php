<?php
defined('_JEXEC') or die;

class ModSelMenuHelper {

    public static function &getList(&$params)
    {
        $listSelItems = $params->get('treemenuitems');

        $menu = JFactory::getApplication()->getMenu('site');
        $items = $menu->getMenu();

        $sel_items = array();
        foreach ($items as $key=>$item)
        {
            if(!empty($listSelItems)){
                if(in_array($key,$listSelItems)){
                    $sel_items[$key]=$item;
                }
            }
        }

        $list = self::createTreeMenu($sel_items);
        return $list;
    }

    public static function createTreeMenu($items)
    {
        $arr = self::toArray($items);

        $parents_arr = array();
        foreach ($arr as $k => $item) {
            $parents_arr[$item['parent_id']][$item['id']] = $item;
        }

        $treeElement = $parents_arr[1];

        self::generateElemTree($treeElement,$parents_arr);

        return $treeElement;

    }

    public static function generateElemTree(&$treeElement,$parents_arr){
        foreach ($treeElement as $k => $item) {
            if(!isset($item->children))
            {
                $treeElement[$k]['children'] = array();
            }
            if(array_key_exists($k,$parents_arr)){
                $treeElement[$k]['children']= $parents_arr[$k];
                self::generateElemTree($treeElement[$k]['children'],$parents_arr);
            }

        }
    }

    static function toArray($obj)
    {
        if (is_object($obj)) $obj = (array)$obj;
        if (is_array($obj)) {
            $new = array();
            foreach ($obj as $key => $val) {
                $new[$key] = self::toArray($val);
            }
        } else {
            $new = $obj;
        }

        return $new;
    }
}