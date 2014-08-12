<?php
/**
* @package			DigiStore Joomla Extension
 * @author			foobla.com
 * @version			$Revision: 341 $
 * @lastmodified	$LastChangedDate: 2013-10-10 14:28:28 +0200 (Thu, 10 Oct 2013) $
 * @copyright		Copyright (C) 2013 foobla.com. All rights reserved.
* @license			GNU/GPLv3
*/

	// Set purchase_date for license get date from order (order_date)
	$orders_010 = $this->getTableData('#__digistore_orders','','*','objectlist');

	foreach( $orders_010 as $order ) {
		$stamp = $order->order_date;
		if (!empty($stamp)) {
			$date = date("Y-m-d H:i:s",$stamp);
			$this->updateTable('#__digistore_licenses', array('purchase_date' => $date), "orderid = ".$order->id);
		}
	}

?>