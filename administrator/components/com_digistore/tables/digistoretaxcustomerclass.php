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

class TabledigistoreTaxcustomerclass extends JTable {
	var $id = null;
	var $name = null;
	var $published = null;
	var $checked_out = null;
	var $checked_out_time = null;
	var $ordering = null;

	function TabledigistoreTaxcustomerclass (&$db) {
		parent::__construct('#__digistore_tax_customerclass', 'id', $db);
	}


	function store($updateNulls = false) {

		$res = parent::store($updateNulls = false);
		if (!$res) return $res;
		return true;
	}

};


?>