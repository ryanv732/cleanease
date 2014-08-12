<?php

/**

 *
 * @package			DigiStore Joomla Extension
 * @author			foobla.com
 * @version			$Revision: 341 $
 * @lastmodified	$LastChangedDate: 2013-10-10 14:28:28 +0200 (Thu, 10 Oct 2013) $
 * @copyright		Copyright (C) 2013 foobla.com. All rights reserved.
 * @license			GNU/GPLv3 */
defined( '_JEXEC' ) or die( "Go away." );

jimport( 'joomla.application.component.controller' );

class digistoreAdminControllerdigistoreLogs extends digistoreAdminController{

	var $_model = null;

	function __construct(){
		parent::__construct();
		$this->registerTask("systememails", "systememails");
		$this->registerTask("download", "download");
		$this->registerTask("editEmail", "editEmail");
		$this->registerTask("purchases", "purchases");
		$this->_model = $this->getModel("digistoreLogs");
		$this->_config = $this->getModel("digistoreConfig");
		$this->_license = $this->getModel("digistoreLicense");
	}

	function systememails(){
		$view = $this->getView("digistoreLogs", "html" );
		$view->setModel($this->_model, true);
		$view->setModel($this->_config);
		$view->setModel($this->_license);
		$view->setLayout("systememails");
		$view->display();
	}

	function download(){
		$view = $this->getView("digistoreLogs", "html" );
		$view->setModel($this->_model, true);
		$view->setModel($this->_config);
		$view->setModel($this->_license);
		$view->setLayout("download");
		$view->display();
	}

	function purchases(){
		$view = $this->getView("digistoreLogs", "html" );
		$view->setModel($this->_model, true);
		$view->setModel($this->_config);
		$view->setModel($this->_license);
		$view->setLayout("purchases");
		$view->display();
	}

	function editEmail(){
		$view = $this->getView("digistoreLogs", "html" );
		$view->setModel($this->_model, true);
		$view->setModel($this->_config);
		$view->setModel($this->_license);
		$view->setLayout("editemail");
		$view->editEmail();
	}

};
?>