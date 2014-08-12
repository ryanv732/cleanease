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

class digistoreAdminViewdigistoreLanguages extends obView {

	function display ($tpl =  null )
	{
		// Access check.
		if (!JFactory::getUser()->authorise('digistore.languages', 'com_digistore'))
		{
			return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
		}

		JToolBarHelper::title(JText::_('Language Manager'), 'generic.png');
		JToolBarHelper::deleteList();
		$languages = $this->get('listLanguages');
		$this->assignRef('languages', $languages);

		$mlanguages = $this->get('listMLanguages');
		$this->assignRef('mlanguages', $mlanguages);

		parent::display($tpl);

	}


	function editForm($tpl = null) {


		$db = JFactory::getDBO();


		$language = $this->get('language');

//		$isNew = ($language->id < 1);
//		$text = $isNew?JText::_('New'):JText::_('Edit');
		$text = JText::_('Edit');
		JToolBarHelper::title(JText::_('Language').":<small>[".$text."]</small>");

		JToolBarHelper::save();
		JToolBarHelper::apply();
		JToolBarHelper::divider();

		JToolBarHelper::cancel ('cancel', 'Close');

		$this->assign("lang_id", $language->id);
		$this->assign("lang_file_path", $language->path);
		$this->assign("langfiledata", $language->data);
		$this->assign("type", $language->type);
		$configs = $this->_models['digistoreconfig']->getConfigs();

		$this->assign("configs", $configs);
		parent::display($tpl);

	}

};

?>