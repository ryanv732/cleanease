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

class digistoreAdminControllerdigistoreAttributes extends digistoreAdminController {
	var $_model = null;

	function __construct () {

		parent::__construct();

		$this->registerTask ("apply", "save");
		$this->registerTask ("add", "edit");
		$this->registerTask ("", "listAttributes");
		$this->_model = $this->getModel("digistoreAttribute");
		$this->registerTask ("unpublish", "publish");
	}

	function listAttributes() {
		$view = $this->getView("digistoreAttributes", "html");
		$view->setModel($this->_model, true);
		$view->display();


	}


	function edit () {
		JRequest::setVar ("hidemainmenu", 1);
		$view = $this->getView("digistoreAttributes", "html");
		$view->setLayout("editForm");
		$view->setModel($this->_model, true);

		$model = $this->getModel("digistoreConfig");
		$view->setModel($model);

		$view->editForm();

	}


	function save () {
		if ($this->_model->store() ) {
			$msg = JText::_('SAVEATTRIB');
		} else {
			$msg = JText::_('SAVEATTRIBFAILED');
		}

		if ( JRequest::getVar('task','') == 'save' ) {
			$link = "index.php?option=com_digistore&controller=digistoreAttributes";
		} else {
			$attr_id = JRequest::getVar('id','');
			$link = "index.php?option=com_digistore&controller=digistoreAttributes&task=edit&cid[]=" . $attr_id;
		}

		$this->setRedirect($link, $msg);
	}


	function remove () {
		if (!$this->_model->delete()) {
			$msg = JText::_('REMOFEATTRIBFAIL');
		} else {
		 	$msg = JText::_('REMOFEATTRIBSUCC');
		}

		$link = "index.php?option=com_digistore&controller=digistoreAttributes";
		$this->setRedirect($link, $msg);

	}

	function cancel () {
	 	$msg = JText::_('ATTRIBOPERATIONCANCELED');
		$link = "index.php?option=com_digistore&controller=digistoreAttributes";
		$this->setRedirect($link, $msg);


	}

	function publish () {
		$res = $this->_model->publish();

		if (!$res) {
			$msg = JText::_('ATTRIBBLOCKIGERROR');
		} elseif ($res == -1) {
		 	$msg = JText::_('ATTRIBUNPUBSUCC');
		} elseif ($res == 1) {
			$msg = JText::_('ATTRIBPUBSUCC');
		} else {
				 	$msg = JText::_('ATTRIBUNSPECERROR');
		}

		$link = "index.php?option=com_digistore&controller=digistoreAttributes";
		$this->setRedirect($link, $msg);


	}

};

?>