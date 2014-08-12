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

class digistoreAdminControllerdigistoreLanguages extends digistoreAdminController {
	
	var $_model = null;

	function __construct () {

		parent::__construct();

		$this->registerTask ("add", "edit");
		$this->registerTask ("apply", "save");
		$this->registerTask ("editFE", "edit");
		$this->registerTask ("editBE", "edit");
		$this->registerTask ("editML", "edit");
		$this->registerTask ("", "listLanguages");

		$this->_model = $this->getModel("digistoreLanguage");

		$this->registerTask ("unpublish", "publish");
	}

	function listLanguages() {

		$view = $this->getView("digistoreLanguages", "html");
		$view->setModel($this->_model, true);

		$view->display();


	}


	function edit () {
		JRequest::setVar ("hidemainmenu", 1);
		$view = $this->getView("digistoreLanguages", "html");
		$view->setLayout("editForm");
		$view->setModel($this->_model, true);

		$model = $this->getModel("digistoreConfig");
		$view->setModel($model);

		$view->editForm();

	}


	function save () {
		if ($this->_model->store() ) {

			$msg = JText::_('LANGSAVED');
		} else {
			$msg = JText::_('LANGSAVEFAILED');
		}

		if ( JRequest::getVar('task','') == 'save' ) {
			$link = "index.php?option=com_digistore&controller=digistoreLanguages";
		} else {
			$attr_id = JRequest::getVar('id','');
			$type = JRequest::getVar('type','');

			$link = "index.php?option=com_digistore&controller=digistoreLanguages&task=" . $type . "&cid[]=" . $attr_id;
		}

		$this->setRedirect($link, $msg);

	}

	function upload () {
		$msg = $this->_model->upload();

		$link = "index.php?option=com_digistore&controller=digistoreLanguages";
		$this->setRedirect($link, $msg);

	}

	function remove () {
		if (!$this->_model->delete()) {
			$msg = JText::_('LANGREMERROR');
		} else {
		 	$msg = JText::_('LALNGREMSUCC');
		}

		$link = "index.php?option=com_digistore&controller=digistoreLanguages";
		$this->setRedirect($link, $msg);

	}

	function cancel () {
	 	$msg = JText::_('LANGCANCELED');
		$link = "index.php?option=com_digistore&controller=digistoreLanguages";
		$this->setRedirect($link, $msg);


	}

	function publish () {
		$res = $this->_model->publish();
		if (!$res) {
			$msg = JText::_('LANGPUBLICHERROR');
		} elseif ($res == -1) {
		 	$msg = JText::_('LANGUNPUBSUCC');
		} elseif ($res == 1) {
			$msg = JText::_('LANGPUBSUCC');
		} else {
				 	$msg = JText::_('LANGUNSPECERROR');
		}

		$link = "index.php?option=com_digistore&controller=digistoreLanguages";
		$this->setRedirect($link, $msg);


	}

};

?>