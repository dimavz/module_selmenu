<?php
defined('_JEXEC') or die;

JLoader::register('ModSelMenuHelper', __DIR__ . '/helper.php');
$app = JFactory::getApplication();
$document = $app->getDocument();
$path_script = '/modules/mod_selmenu/js/selitems.js';
$document->addScript($path_script, array('version' => 'auto', 'relative' => true));

$listLinks = ModSelMenuHelper::getList($params);

$layout = $params->get('layout','default');

require JModuleHelper::getLayoutPath('mod_selmenu',$layout);