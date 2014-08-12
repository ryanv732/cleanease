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

class TabledigistoreSession extends JTable {
	var $sid = null;
	var $cart_details = null;
	var $transaction_details = null;
	var $shipping_details = null;
	var $create_time = null;

	function TabledigistoreSession (&$db) {
		parent::__construct('#__digistore_session', 'sid', $db);
	}

};


?>