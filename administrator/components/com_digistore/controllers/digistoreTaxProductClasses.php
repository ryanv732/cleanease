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

jimport ('joomla.application.component.controller');

class digistoreAdminControllerdigistoreTaxProductClasses extends digistoreAdminController {
	var $_model = null;

	function __construct () {

		parent::__construct();

		$this->registerTask ("add", "edit");
		$this->registerTask ("", "listClasses");
		$this->registerTask ("unpublish", "publish");
		$this->registerTask ("orderup", "shiftorder");
		$this->registerTask ("orderdown", "shiftorder");
		$this->_model = $this->getModel("digistoreTaxProductClass");
	}

	function listClasses() {
	   		JRequest::setVar ("view", "digistoreTaxProductClasses");
		$view = $this->getView("digistoreTaxProductClasses", "html");
		$view->setModel($this->_model, true);
		$view->display();
	}


	function edit () {
		JRequest::setVar ("hidemainmenu", 1);
		$view = $this->getView("digistoreTaxProductClasses", "html");
		$view->setLayout("editForm");
		$view->setModel($this->_model, true);
		$view->editForm();

	}


	function save () {
		$result = $this->_model->store();
		if($result["status"] === TRUE){
			$msg = JText::_('DSTAXPRODUCTCLASSSAVED');
		}
		else{
			$msg = JText::_('DSTAXPRODUCTCLASSSAVEFAILED');
		}
		$link = "index.php?option=com_digistore&controller=digistoreTaxProductClasses";
		$this->setRedirect($link, $msg);
	}

	function apply(){
		$result = $this->_model->store();
		if($result["status"] === TRUE){
			$msg = JText::_('DSTAXPRODUCTCLASSSAVED');
		}
		else{
			$msg = JText::_('DSTAXPRODUCTCLASSSAVEFAILED');
		}
		$link = "index.php?option=com_digistore&controller=digistoreTaxProductClasses&task=edit&cid[]=".$result["id"];
		$this->setRedirect($link, $msg);
	}


	function remove () {
		if (!$this->_model->delete()) {
			$msg = JText::_('DSTAXPRODUCTCLASSREMOVEFAIL');
		} else {
		 	$msg = JText::_('DSTAXPRODUCTCLASSREMOVESUCCESS');
		}

		$link = "index.php?option=com_digistore&controller=digistoreTaxProductClasses";
		$this->setRedirect($link, $msg);

	}

	function cancel () {
	 	$msg = JText::_('DSTAXPRODUCTCLASSCANCELED');
		$link = "index.php?option=com_digistore&controller=digistoreTaxProductClasses";
		$this->setRedirect($link, $msg);


	}

	function publish () {
		$res = $this->_model->publish();

		if (!$res) {
			$msg = JText::_('DSTAXPRODUCTCLASSPUBLISHINGERROR');
		} elseif ($res == -1) {
		 	$msg = JText::_('DSTAXPRODUCTCLASSUNPUBLISHINGSUCC');
		} elseif ($res == 1) {
			$msg = JText::_('DSTAXPRODUCTCLASSPUBLISHINGSUCC');
		} else {
				 	$msg = JText::_('DSTAXPRODUCTCLASSUNSPECERROR');
		}

		$link = "index.php?option=com_digistore&controller=digistoreTaxProductClasses";
		$this->setRedirect($link, $msg);


	}

	function saveorder () {
		$res = $this->_model->reorder();

		if (!$res) {
			$msg = JText::_('DSTAXPRODUCTCLASSORDERINGERROR');
		} else {
			$msg = JText::_('DSTAXPRODUCTCLASSORDERINGSUCCESS');
		}
		$link = "index.php?option=com_digistore&controller=digistoreTaxProductClasses";
		$this->setRedirect($link, $msg);


	}

	function shiftorder () {
		$task = JRequest::getVar("task", "orderup", "request");

		$direct = ($task == "orderup")?(-1):(1);
		$res = $this->_model->shiftorder($direct);

		if (!$res) {
			$msg = JText::_('DSTAXPRODUCTCLASSORDERINGERROR');
		} else {
			$msg = JText::_('DSTAXPRODUCTCLASSORDERINGSUCCESS');
		}
		$link = "index.php?option=com_digistore&controller=digistoreTaxProductClasses";
		$this->setRedirect($link, $msg);


	}


};

?>