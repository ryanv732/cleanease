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

class digistoreControllerdigistoreOrders extends digistoreController {

	var $_model = null;
	var $_config = null;
	var $_order = null;

	function __construct () {
		global $Itemid;
		parent::__construct();
		$this->registerTask ("", "listOrders");
		$this->registerTask ("list", "listOrders");
		$this->registerTask ("view", "showOrder");
		$this->registerTask ("showrec", "showOrderReceipt");
		$this->registerTask ("wait", "waitipn");
		$this->_model = $this->getModel("digistoreOrder");
		$this->_config = $this->getModel("digistoreConfig");
		$this->_license = $this->getModel("digistoreLicense");
		$this->_cart = $this->getModel("digistoreCart");
		$this->_customers_model = $this->getModel("digistoreCustomer");

		$this->log_link = JRoute::_("index.php?option=com_digistore&controller=digistoreProfile&task=login&returnpage=orders&Itemid=".$Itemid, false);
		$this->prof_link = JRoute::_("index.php?option=com_digistore&controller=digistoreProfile&task=edit&returnpage=orders&Itemid=".$Itemid, false);
		$this->order_link = JRoute::_("index.php?option=com_digistore&controller=digistoreOrders&Itemid=".$Itemid, false);
	}

	function listOrders()
	{
		global $Itemid;
		if($this->_customer->_user->id < 1)
		{
			$this->setRedirect(JRoute::_($this->log_link, false));
			return;
		}

		$res = digistoreHelper::checkProfileCompletion($this->_customer);
		if($res < 1)
		{
			$this->setRedirect($this->prof_link);
		}

		JRequest::setVar ("view", "digistoreOrders");
		$view = $this->getView("digistoreOrders", "html");
		$view->setModel($this->_model, true);
		$view->setModel($this->_config);
		$view->setModel($this->_license);
		$view->setModel($this->_cart);
		$view->setModel($this->_customers_model);
		$conf = $this->_config->getConfigs();
		$view->display();
	}

	function showOrder()
	{
		if ($this->_customer->_user->id < 1)
		{
			$this->setRedirect(JRoute::_($this->log_link) );
			return;
		}
		$res = digistoreHelper::checkProfileCompletion( $this->_customer );
		if ($res < 1)
		{
			$this->setRedirect( $this->prof_link );

		}

		$view = $this->getView("digistoreOrders", "html");
		$view->setModel($this->_model, true);
		$view->setModel($this->_config);
		$view->setModel($this->_license);
		$view->setLayout("showorder");
		$view->showOrder();
	}

	function showOrderReceipt()
	{
		if ($this->_customer->_user->id < 1)
		{
			$this->setRedirect(JRoute::_($this->log_link) );
			return;
		}

		$res = digistoreHelper::checkProfileCompletion( $this->_customer );
		if ($res < 1)
		{
			$this->setRedirect( $this->prof_link );
		}
		$view = $this->getView("digistoreOrders", "html");
		$view->setModel($this->_model, true);
		$view->setModel($this->_config);
		$view->setModel($this->_license);
		$view->setLayout("receipt");
		$view->showReceipt();
	}

};
