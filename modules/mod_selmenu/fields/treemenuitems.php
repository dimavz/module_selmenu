<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');

class JFormFieldTreemenuitems extends JFormField
{
    protected $type = 'Treemenuitems';

    public function getInput()
    {
        $view = new ModulesViewModule();
        $view->setLayout('edit');
        $view->addTemplatePath(JPATH_SITE . "/modules/mod_selmenu/layouts");
        return $view->loadTemplate('treemenu');
    }

}