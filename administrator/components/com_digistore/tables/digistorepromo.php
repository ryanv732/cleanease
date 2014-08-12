<?php
/**
* @package			DigiStore Joomla Extension
 * @author			foobla.com
 * @version			$Revision: 341 $
 * @lastmodified	$LastChangedDate: 2013-10-10 14:28:28 +0200 (Thu, 10 Oct 2013) $
 * @copyright		Copyright (C) 2013 foobla.com. All rights reserved.
* @license			GNU/GPLv3
*/

defined('_JEXEC') or die("Go away.");

class TabledigistorePromo extends JTable
{
	var $id = null;
	var $title = null;
	var $code = null;
	var $codelimit = null;
	var $amount = null;
	var $codestart = null;
	var $codeend = null;
	var $forexisting = null;
	var $published = null;
	var $aftertax = null;
	var $promotype = null;
	var $used = null;
	var $ordering = null;
	var $checked_out = null;
	var $checked_out_time = null;

	function TabledigistorePromo (&$db) {
		parent::__construct('#__digistore_promocodes', 'id', $db);
	}


	function store($updateNulls = false) {
		if ((int)$this->codeend != 0 ) {
//			$end_date = parse_date($dat);
		} else {

			$this->codeend = 0;
		}
		if (!parent::store($updateNulls = false)) return false;
		return true;

	}

	function storeProducts($promoid)
	{
		$db = JFactory::getDBO();

		if ($promoid)
		{
			$sql = "DELETE FROM `#__digistore_promocodes_products`
					WHERE `promoid`=$promoid";
			$db->setQuery($sql);
			$db->query();

			foreach($_POST['items_product_id'] as $item)
			{
				if ((int) $item)
				{
					$sql = "INSERT INTO `#__digistore_promocodes_products`(`promoid`, `productid`)
							VALUES($promoid, $item)";
					$db->setQuery($sql);
					$db->query();
				}
			}
		}
	}

	function storeOrders($promoid)
	{
		$db = JFactory::getDBO();

		if ($promoid)
		{
			$sql = "DELETE FROM `#__digistore_promocodes_orders`
					WHERE `promoid`=$promoid";
			$db->setQuery($sql);
			$db->query();

			foreach($_POST['orders_product_id'] as $item)
			{
				if ((int) $item)
				{
					$sql = "INSERT INTO `#__digistore_promocodes_orders`(`promoid`, `productid`)
							VALUES($promoid, $item)";
					$db->setQuery($sql);
					$db->query();
				}
			}
		}
	}
};
