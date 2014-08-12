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

class TabledigistoreCustomer extends JTable {
	var $id = null;
	var $address = null;
	var $city = null;
	var $state = null;
	var $province = null;
	var $zipcode = null;
	var $country = null;
	var $payment_type = null;
	var $company = null;
	var $firstname = null;
	var $lastname = null;
	var $shipaddress = null;
	var $shipcity = null;
	var $shipstate = null;
	var $shipzipcode = null;
	var $shipcountry = null;
	var $person = null;
	var $taxnum = null;

	function TabledigistoreCustomer (&$db) {

		parent::__construct('#__digistore_customers', 'id', $db);
	}

	function load ($id = 0) {
		parent::load($id);
		$db = JFactory::getDBO();
		$sql = "SELECT username, email FROM #__users WHERE id='".$id."'";
		$db->setQuery($sql);
		$r = $db->loadObjectList();
		if (count($r) > 0){
			$this->email = $r[0]->email;
			$this->username = $r[0]->username;
		} else {
			$this->email = null;
			$this->username = null;

		}
	}

	function store(){
		$db = JFactory::getDBO();
		$sql = "SELECT COUNT(*) FROM #__digistore_customers WHERE id='".$this->id."'";
		$db->setQuery($sql);
		$n = $db->loadResult();
		if ($n < 1 && $this->id > 0) {
			$sql = "INSERT INTO #__digistore_customers(`id`) VALUES ('".$this->id."')";
			$db->setQuery($sql);
			$db->query();
		} else if ($n < 1 && $this->id < 1){

			return false;
		}
		parent::store();
		return true;
	}

};


?>