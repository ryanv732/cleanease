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

class digistoreViewdigistoreOrders extends obView {

	function display($tpl = null)
	{
		$mainframe=JFactory::getApplication();
		$Itemid = JRequest::getInt("Itemid", 0);
		$ga = JRequest::getInt("ga", 0);

		$orders = $this->get('listOrders');
		$configs = $this->_models['digistoreconfig']->getConfigs();
		$database = JFactory::getDBO();
		$db = $database;
		$sql = "select params
				from #__modules
				where `module`='mod_digistore_cart'";
		$db->setQuery($sql);
		$d = $db->loadResult();
		$d = explode ("\n", $d);
		$categ_digistore = '';

		foreach ($d as $i => $v)
		{
			$x = explode ("=", $v);
			if ($x[0] == "digistore_category")
			{
				$categ_digistore = $x[1];
				break;
			}
		}

		/* Get Cart items */
		$cart = $this->getModel('digistoreCart');
		$customer = new digistoreSessionHelper();
		$cartitems = $cart->getCartItems($customer, $configs);
	   
		if ( $categ_digistore != '' )
		{
			$sql = "select id from #__digistore_categories where title like '".$categ_digistore."' or name like '".$categ_digistore."'";
			$database->setQuery($sql);
			$id = $database->loadResult();
			$cat_url = JRoute::_("index.php?option=com_digistore&controller=digistoreProducts&task=list&cid=" . $id."&Itemid=".$Itemid);
		}
		else
		{
			$cat_url = JRoute::_("index.php?option=com_digistore&controller=digistoreCategories&task=listCategories"."&Itemid=".$Itemid);
		}

		$this->assignRef('orders', $orders);
		$this->assign("configs", $configs);
		$this->assignRef('cartitems', $cartitems);
		$this->assign("caturl", $cat_url);

		parent::display($tpl);
	}

	function showOrder($tpl = null)
	{
		$db = JFactory::getDBO();
		$order = $this->_models['digistoreorder']->getOrder();
		$this->assign("order", $order);
		$configs = $this->_models['digistoreconfig']->getConfigs();
		$this->assign("configs", $configs);
		parent::display($tpl);
	}

	function showReceipt($tpl = null)
	{
		$db = JFactory::getDBO();
		$order = $this->_models['digistoreorder']->getOrder();
		$this->assign("order", $order);
		$configs = $this->_models['digistoreconfig']->getConfigs();
		$this->assign("configs", $configs);
		$customer = new digistoreSessionHelper();
	   	$this->assign("customer", $customer);
		parent::display($tpl);
	}
}
