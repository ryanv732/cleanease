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

class digistoreAdminViewdigistoreStats extends obView {

	function prepereJoomlaDataFormat($format = '%m-%d-%Y') {

		$result = $format;
		if ( strpos($result,'%') === false) {
			$r = array('m' => '%m', 'd' => '%d', 'Y' => '%Y');
			$result = str_replace(array_keys($r),array_values($r),$format);
		} 

		return $result;
	}

	function display ($tpl =  null )
	{
		// Access check.
		if (!JFactory::getUser()->authorise('digistore.stats', 'com_digistore'))
		{
			return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
		}

		JToolBarHelper::title(JText::_('Statistics'), 'generic.png');
		$configs = $this->_models['digistoreconfig']->getConfigs();
		$this->assign("configs", $configs);
		parent::display($tpl);
	}

	function getTotal($type){
		$total = $this->_models['digistorestat']->getreportTotal($type);
		return $total;
	}

	function getNrOrders(){
		$total = $this->_models['digistorestat']->getreportOrders();
		return $total;
	}

	function getNrLicenses($type){
		$total = $this->_models['digistorestat']->getreportLicenses($type);
		return $total;
	}

	function getStartEndDate($report){
		$total = $this->_models['digistorestat']->getStartEndDate($report);
		return $total;
	}

	function getPaginationDate($configs){
		$this->_models['digistorestat']->getPaginationDate($configs);
	}
};

?>