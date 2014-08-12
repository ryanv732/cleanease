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

class digistoreAdminViewdigistoreAbout extends obView {

	function display($tpl =  null){
		JToolBarHelper::title(JText::_('About DigiStore'), 'generic.png');
		parent::display($tpl);
	}

	function vimeo($tpl = null) {
		$id = JRequest::getVar('id', '0');
		$this->assignRef('id', $id);
		parent::display($tpl);
	}
}

?>