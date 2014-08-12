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

class digistoreAdminControllerdigistoreFieldsets extends digistoreAdminController {
	var $_model = null;

	function __construct () {

		parent::__construct();

		$this->registerTask ("add", "edit");
		$this->registerTask ("", "listFieldsets");
		$this->_model = $this->getModel("digistoreFieldset");
		$this->registerTask ("unpublish", "publish");
	}

	function listFieldsets() {
		$view = $this->getView("digistoreFieldsets", "html");
		$view->setModel($this->_model, true);
		$view->display();


	}


	function edit () {
		JRequest::setVar ("hidemainmenu", 1);
		$view = $this->getView("digistoreFieldsets", "html");
		$view->setLayout("editForm");
		$view->setModel($this->_model, true);

		$model = $this->getModel("digistoreConfig");
		$view->setModel($model);

		$view->editForm();

	}


	function save () {
		if ($this->_model->store() ) {
			$msg = JText::_('DS_SAVEFIELDSET_SUCC');
		} else {
			$msg = JText::_('DS_SAVEFIELDSET_FAILED');
		}
		$link = "index.php?option=com_digistore&controller=digistoreFieldsets";
		$this->setRedirect($link, $msg);

	}


	function remove () {
		if (!$this->_model->delete()) {
			$msg = JText::_('DS_REMOFEFIELDSET_FAIL');
		} else {
		 	$msg = JText::_('DS_REMOFEFIELDSET_SUCC');
		}

		$link = "index.php?option=com_digistore&controller=digistoreFieldsets";
		$this->setRedirect($link, $msg);

	}

	function cancel () {
	 	$msg = JText::_('DS_FIELDSET_OPERATIONCANCELED');
		$link = "index.php?option=com_digistore&controller=digistoreFieldsets";
		$this->setRedirect($link, $msg);


	}

	function publish () {
		$res = $this->_model->publish();

		if (!$res) {
			$msg = JText::_('DS_FIELDSET_BLOCKIGERROR');
		} elseif ($res == -1) {
		 	$msg = JText::_('DS_FIELDSET_UNPUBSUCC');
		} elseif ($res == 1) {
			$msg = JText::_('DS_FIELDSET_PUBSUCC');
		} else {
				 	$msg = JText::_('DS_FIELDSET_UNSPECERROR');
		}

		$link = "index.php?option=com_digistore&controller=digistoreFieldsets";
		$this->setRedirect($link, $msg);


	}

};

?>