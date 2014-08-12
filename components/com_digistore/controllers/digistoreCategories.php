<?php
/**
* @package			DigiStore Joomla Extension
 * @author			foobla.com
 * @version			$Revision: 341 $
 * @lastmodified	$LastChangedDate: 2013-10-10 14:28:28 +0200 (Thu, 10 Oct 2013) $
 * @copyright		Copyright (C) 2013 foobla.com. All rights reserved.
* @license


*/

defined ('_JEXEC') or die ("Go away.");

jimport ('joomla.application.component.controller');

class digistoreControllerdigistoreCategories extends digistoreController {

	var $_model = null;
	var $_config = null;
	var $_product = null;

	function __construct(){
		parent::__construct();
		$this->registerTask ("", "listCategories");
		$this->registerTask ("view", "listCategories");
		$this->_model = $this->getModel("digistoreCategory");
		$this->_config = $this->getModel("digistoreConfig");
		$this->_product = $this->getModel("digistoreProduct");
	}

	function listCategories() {

	   	JRequest::setVar ("view", "digistoreCategories");
		$view = $this->getView("digistoreCategories", "html");
		$view->setModel($this->_model, true);
		$view->setModel($this->_config);
		$view->setModel($this->_product);

		$conf = $this->_config->getConfigs();
		// echo "<pre>";var_dump($conf->catlayoutstyle);die();
		switch ($conf->catlayoutstyle) {
			case "0":
				$view->setLayout("list");
				break;

			case "1":
				$view->setLayout("listThumbs");
				break;

			case "2":
				$view->setLayout("dropdown");
				break;

			default:
				$view->setLayout("list");
				break;

		}
		$view->display();

	}

};

?>
