<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_modules
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Initialise related data.
JLoader::register('MenusHelper', JPATH_ADMINISTRATOR . '/components/com_menus/helpers/menus.php');
$menuTypes = MenusHelper::getMenuLinks();
$params = 0;
$input = JFactory::getApplication()->input;
$module_id = $input->get('id', 0, 'INT');

$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select($db->qn('params'))->from($db->qn('#__modules'))->where($db->qn('id') . "=" . $db->q($module_id));
$db->setQuery($query);
try {
    $params = $db->loadResult();
} catch (RuntimeException $e) {
    JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
}

$selItemsMenu = 0;
if (isset($params) && !empty($params)) {
    $params = json_decode($params);
    if (!empty($params->treemenuitems)) {
        $selItemsMenu = $params->treemenuitems;
    }

}


JHtml::_('script', 'jui/treeselectmenu.jquery.min.js', array('version' => 'auto', 'relative' => true));
$app = JFactory::getApplication();
$document = $app->getDocument();
$document->addScript('/modules/mod_selmenu/js/treeselectmenu.js', array('version' => 'auto', 'relative' => true));
$document->addStyleSheet('/modules/mod_selmenu/css/module_style.css');
//JHtml::_('script', JPATH_SITE.'/modules/mod_selmenu/js/treeselectmenu.js', array('version' => 'auto', 'relative' =>true));

$script = "
	jQuery(document).ready(function()
	{
		menuHide(jQuery('#jform_treemenu').val());
		jQuery('#jform_treemenu').change(function()
		{
			menuHide(jQuery(this).val());
		})
	});
	function menuHide(val)
	{
		if (val == 0 || val == '-')
		{
			jQuery('#mymenuselect-group').hide();
		}
		else
		{
			jQuery('#mymenuselect-group').show();
		}
	}
";

// Add the script to the document head
JFactory::getDocument()->addScriptDeclaration($script);
?>

<div id="mymenuselect-group" class="control-group">

    <div id="jform_mymenuselect" class="controls">
        <?php if (!empty($menuTypes)) : ?>
            <?php $id = 'jform_treemenuitems'; ?>

            <div class="well well-small">
                <div class="form-inline">
				<span class="small"><?php echo JText::_('JSELECT'); ?>:
					<a id="mytreeCheckAll" href="javascript://"><?php echo JText::_('JALL'); ?></a>,
					<a id="mytreeUncheckAll" href="javascript://"><?php echo JText::_('JNONE'); ?></a>
				</span>
                    <span class="width-20">|</span>
                    <span class="small"><?php echo JText::_('COM_MODULES_EXPAND'); ?>:
					<a id="mytreeExpandAll" href="javascript://"><?php echo JText::_('JALL'); ?></a>,
					<a id="mytreeCollapseAll" href="javascript://"><?php echo JText::_('JNONE'); ?></a>
				</span>
                    <input type="text" id="treeselectfilter" name="treeselectfilter"
                           class="input-medium search-query pull-right" size="16"
                           autocomplete="off" placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>"
                           aria-invalid="false" tabindex="-1">
                </div>

                <div class="clearfix"></div>

                <hr class="hr-condensed"/>

                <ul class="mytreeselect">
                    <?php foreach ($menuTypes as &$type) : ?>
                        <?php if (count($type->links)) : ?>
                            <?php $prevlevel = 0; ?>
                            <li>
                            <div class="tree-item pull-left">
                                <label class="pull-left nav-header"><?php echo $type->title; ?></label></div>
                            <?php foreach ($type->links as $i => $link) : ?>
                                <?php
                                if ($prevlevel < $link->level) {
                                    echo '<ul class="tree-sub">';
                                } elseif ($prevlevel > $link->level) {
                                    echo str_repeat('</li></ul>', $prevlevel - $link->level);
                                } else {
                                    echo '</li>';
                                }
                                $selected = 0;
                                if ($selItemsMenu == 0) {
                                    $selected = 1;
                                } elseif (count($selItemsMenu) < 0) {
                                    $selected = in_array(-$link->value, $selItemsMenu);
                                } elseif (count($selItemsMenu) > 0) {
                                    $selected = in_array($link->value, $selItemsMenu);
                                }

                                ?>
                                <li>
                                <div class="tree-item pull-left">
                                    <?php
                                    $uselessMenuItem = in_array($link->type,
                                        array('separator', 'heading', 'alias', 'url'));
                                    ?>
                                    <span style="float:left">id: <?php echo $link->value; ?></span>
                                    <input type="checkbox" class="pull-left novalidate"
                                           name="jform[params][treemenuitems][]"
                                           id="<?php echo $id . $link->value; ?>"
                                           value="<?php echo (int)$link->value; ?>"<?php echo $selected ? ' checked="checked"' : '';
//                                    echo $uselessMenuItem ? ' disabled="disabled"' : ''; ?>
                                    <label for="<?php echo $id . $link->value; ?>" class="pull-left">
                                        <?php echo $link->text; ?> <span
                                                class="small"><?php echo JText::sprintf('JGLOBAL_LIST_ALIAS',
                                                $this->escape($link->alias)); ?></span>
                                        <?php if (JLanguageMultilang::isEnabled() && $link->language != '' && $link->language != '*') : ?>
                                            <?php if ($link->language_image) : ?>
                                                <?php echo JHtml::_('image',
                                                    'mod_languages/' . $link->language_image . '.gif',
                                                    $link->language_title, array('title' => $link->language_title),
                                                    true); ?>
                                            <?php else : ?>
                                                <?php echo '<span class="label" title="' . $link->language_title . '">' . $link->language_sef . '</span>'; ?>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <?php if ($link->published == 0) : ?>
                                            <?php echo ' <span class="label">' . JText::_('JUNPUBLISHED') . '</span>'; ?>
                                        <?php endif; ?>
                                        <?php if ($uselessMenuItem) : ?>
                                            <?php echo ' <span class="label">' . JText::_('COM_MODULES_MENU_ITEM_' . strtoupper($link->type)) . '</span>'; ?>
                                        <?php endif; ?>
                                    </label>
                                </div>
                                <?php

                                if (!isset($type->links[$i + 1])) {
                                    echo str_repeat('</li></ul>', $link->level);
                                }
                                $prevlevel = $link->level;
                                ?>
                            <?php endforeach; ?>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
                <div id="noresultsfound" style="display:none;" class="alert alert-no-items">
                    <?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
                </div>
                <div style="display:none;" id="treeselectmenu">
                    <div class="pull-left nav-hover treeselect-menu">
                        <div class="btn-group">
                            <a href="#" data-toggle="dropdown" class="dropdown-toggle btn btn-micro">
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="nav-header"><?php echo JText::_('COM_MODULES_SUBITEMS'); ?></li>
                                <li class="divider"></li>
                                <li class=""><a class="checkall" href="javascript://"><span class="icon-checkbox"
                                                                                            aria-hidden="true"></span> <?php echo JText::_('JSELECT'); ?>
                                    </a>
                                </li>
                                <li><a class="uncheckall" href="javascript://"><span class="icon-checkbox-unchecked"
                                                                                     aria-hidden="true"></span> <?php echo JText::_('COM_MODULES_DESELECT'); ?>
                                    </a>
                                </li>
                                <div class="treeselect-menu-expand">
                                    <li class="divider"></li>
                                    <li><a class="expandall" href="javascript://"><span class="icon-plus"
                                                                                        aria-hidden="true"></span> <?php echo JText::_('COM_MODULES_EXPAND'); ?>
                                        </a></li>
                                    <li><a class="collapseall" href="javascript://"><span class="icon-minus"
                                                                                          aria-hidden="true"></span> <?php echo JText::_('COM_MODULES_COLLAPSE'); ?>
                                        </a></li>
                                </div>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
