<?php
/**
* @package			DigiStore Joomla Extension
 * @author			foobla.com
 * @version			$Revision: 341 $
 * @lastmodified	$LastChangedDate: 2013-10-10 14:28:28 +0200 (Thu, 10 Oct 2013) $
 * @copyright		Copyright (C) 2013 foobla.com. All rights reserved.
* @license			GNU/GPLv3
*/

defined ('_JEXEC') or die ("Go away.");

jimport ("joomla.application.component.view");

class digistoreAdminViewdigistoreFieldsets extends obView {

	function display ($tpl =  null ) {
		JToolBarHelper::title(JText::_('Fieldsets Manager'), 'generic.png');
		JToolBarHelper::deleteList();
		JToolBarHelper::editList();
		JToolBarHelper::addNew();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();

		$fsets = $this->get('listFieldsets');
		$this->assignRef('fsets', $fsets);
		parent::display($tpl);

	}

	function editForm($tpl = null) {

		$db = JFactory::getDBO();

		$fset = $this->get('Fieldset');
		$isNew = ($attr->id < 1);
		$text = $isNew?JText::_('New'):JText::_('Edit');

		JToolBarHelper::title(JText::_('DS_FIELDSET').":<small>[".$text."]</small>");
		JToolBarHelper::save();
		if ($isNew) {
			JToolBarHelper::divider();
			JToolBarHelper::cancel();
		} else {
			JToolBarHelper::apply();
			JToolBarHelper::divider();
			JToolBarHelper::cancel ('cancel', 'Close');
		}

		$this->assign("fset", $fset);

		$configs = $this->_models['digistoreconfig']->getConfigs();
		$lists = null;

		$this->assign("configs", $configs);
		$this->assign("lists", $lists);
		parent::display($tpl);

	}

}

?>