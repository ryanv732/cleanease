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

class digistoreAdminControllerdigistorePromos extends digistoreAdminController {
	var $_model = null;

	function __construct () {

		parent::__construct();

		$this->registerTask ("add", "edit");
		$this->registerTask ("apply", "save");
		$this->registerTask ("", "listPromos");
		$this->_model = $this->getModel("digistorePromo");
		$this->registerTask ("unpublish", "publish");
	}

	function listPromos() {
		$view = $this->getView("digistorePromos", "html");
		$view->setModel($this->_model, true);
		$model = $this->getModel("digistoreConfig");
		$view->setModel($model);

		$view->display();


	}


	function edit () {
		JRequest::setVar ("hidemainmenu", 1);
		$view = $this->getView("digistorePromos", "html");
		$view->setLayout("editForm");
		$view->setModel($this->_model, true);

		$model = $this->getModel("digistoreConfig");
		$view->setModel($model);

		$view->editForm();

	}


	function save () {
		if ($this->_model->store() ) {

			$msg = JText::_('PROMSAVED');
		} else {
			$msg = JText::_('PROMFAILED');
		}
		$link = "index.php?option=com_digistore&controller=digistorePromos";

		if ( JRequest::getVar('task','') == 'save' ) {
			$link = "index.php?option=com_digistore&controller=digistorePromos";
		} else {
			$promo_id = JRequest::getVar('id','');
			$link = "index.php?option=com_digistore&controller=digistorePromos&task=edit&cid[]=" . $promo_id;
		}

		$this->setRedirect($link, $msg);
	}


	function remove () {
		if (!$this->_model->delete()) {
			$msg = JText::_('PROMREMERR');
		} else {
		 	$msg = JText::_('PROMREMSUCC');
		}

		$link = "index.php?option=com_digistore&controller=digistorePromos";
		$this->setRedirect($link, $msg);

	}

	function cancel () {
	 	$msg = JText::_('PROMCANCEL');
		$link = "index.php?option=com_digistore&controller=digistorePromos";
		$this->setRedirect($link, $msg);


	}

	function publish () {
		$res = $this->_model->publish();
		if (!$res) {
			$msg = JText::_('PROMBLOCKERR');
		} elseif ($res == -1) {
		 	$msg = JText::_('PROMUNPUBSUCC');
		} elseif ($res == 1) {
			$msg = JText::_('PROMPPUBSUCC');
		} else {
				 	$msg = JText::_('PROMUNSPEC');
		}

		$link = "index.php?option=com_digistore&controller=digistorePromos";
		$this->setRedirect($link, $msg);


	}

	function productitem() {
		$view = $this->getView( "digistorePromos", "html" );
		$view->setModel( $this->_model, true );
		$model = $this->getModel( "digistoreConfig" );
		$view->setModel( $model );
		$view->setLayout( "productitem" );
		$view->productitem();
	}

};
