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

class digistoreAdminViewdigistoreFieldtypes extends obView {

	function display ($tpl =  null ) {
		JToolBarHelper::title(JText::_('Fieldtypes Manager'), 'generic.png');
		JToolBarHelper::deleteList();
		JToolBarHelper::editList();
		JToolBarHelper::addNew();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();

		$ftypes = $this->get('listFieldtypes');
		$this->assignRef('ftypes', $ftypes);
		parent::display($tpl);

	}

	function editForm($tpl = null) {

		$db = JFactory::getDBO();

		$ftype = $this->get('Fieldtype');
		$isNew = ($attr->id < 1);
		$text = $isNew?JText::_('New'):JText::_('Edit');

		JToolBarHelper::title(JText::_('DS_FIELDTYPE').":<small>[".$text."]</small>");
		JToolBarHelper::save();
		if ($isNew) {
			JToolBarHelper::divider();
			JToolBarHelper::cancel();
		} else {
			JToolBarHelper::apply();
			JToolBarHelper::divider();
			JToolBarHelper::cancel ('cancel', 'Close');
		}

		$this->assign("ftype", $ftype);

		$configs = $this->_models['digistoreconfig']->getConfigs();
		$lists = null;

		$this->assign("configs", $configs);
		$this->assign("lists", $lists);
		parent::display($tpl);

	}

}

?>