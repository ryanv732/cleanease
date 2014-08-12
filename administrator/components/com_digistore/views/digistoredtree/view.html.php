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

class digistoreAdminViewdigistoreDtree extends obView {

	function showDtree ($tpl =  null ) {
		parent::display($tpl);

	}
	function showJS($tpl = null) {
		$this->setLayout("showJS");
		$no_html = JRequest::getVar('no_html');
		parent::display($tpl);
		if ( $no_html ) exit();
	}
}

?>