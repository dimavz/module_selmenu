<?php
defined('_JEXEC') or die;

JLoader::register('ModSelMenuHelper', __DIR__ . '/helper.php');

$listMenuLinks = ModSelMenuHelper::getList($params);

$layout = $params->get('layout','default');

require JModuleHelper::getLayoutPath('mod_selmenu',$layout);