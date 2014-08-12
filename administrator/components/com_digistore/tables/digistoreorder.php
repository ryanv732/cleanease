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

class TabledigistoreOrder extends JTable {

	var $id = null;
	var $userid = null;
	var $order_date = null;
	var $amount = null;
	var $amount_paid = null;
	var $processor = null;
	var $number_of_licenses = null;
	var $currency = null;
	var $status = null;
	var $tax = null;
	var $shipping = null;
	var $promocodeid = null;
	var $promocode = null;
	var $promocodediscount = null;
	var $shipto = null;
	var $fullshipto = null;
	var $published = null;

	function TabledigistoreOrder (&$db) {
		parent::__construct('#__digistore_orders', 'id', $db);
	}

};


?>