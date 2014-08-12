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

class TabledigistoreCustomer extends JTable
{

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
	var $taxclass = null;

	function TabledigistoreCustomer( &$db )
	{
		parent::__construct( '#__digistore_customers', 'id', $db );
	}

	function load( $id = NULL, $reset = true )
	{
		parent::load( $id );
		$db = JFactory::getDBO();
		$sql = "select username, email from #__users where id='" . $id . "'";
		$db->setQuery( $sql );
		$r = $db->loadObjectList();
		if ( count( $r ) > 0 ) {
			$this->email = $r[0]->email;
			$this->username = $r[0]->username;
		} else {
			$this->email = null;
			$this->username = null;
			$this->address = (JRequest::getVar('address','') != '') ? JRequest::getVar('address','') : null;
			$this->city = (JRequest::getVar('city','') != '') ? JRequest::getVar('city','') : null;
			$this->state = (JRequest::getVar('state','') != '') ? JRequest::getVar('state','') : null;
			$this->province = (JRequest::getVar('province','') != '') ? JRequest::getVar('province','') : null;
			$this->zipcode = (JRequest::getVar('zipcode','') != '') ? JRequest::getVar('zipcode','') : null;
			$this->country = (JRequest::getVar('country','') != '') ? JRequest::getVar('country','') : null;
			$this->payment_type = (JRequest::getVar('payment_type','') != '') ? JRequest::getVar('payment_type','') : null;
			$this->company = (JRequest::getVar('company','') != '') ? JRequest::getVar('company','') : null;
			$this->firstname = (JRequest::getVar('firstname','') != '') ? JRequest::getVar('firstname','') : null;
			$this->lastname = (JRequest::getVar('lastname','') != '') ? JRequest::getVar('lastname','') : null;
			$this->shipaddress = (JRequest::getVar('shipaddress','') != '') ? JRequest::getVar('shipaddress','') : null;
			$this->shipcity = (JRequest::getVar('shipcity','') != '') ? JRequest::getVar('shipcity','') : null;
			$this->shipstate = (JRequest::getVar('shipstate','') != '') ? JRequest::getVar('shipstate','') : null;
			$this->shipzipcode = (JRequest::getVar('shipzipcode','') != '') ? JRequest::getVar('shipzipcode','') : null;
			$this->shipcountry = (JRequest::getVar('shipcountry','') != '') ? JRequest::getVar('shipcountry','') : null;
			$this->person = (JRequest::getVar('person','') != '') ? JRequest::getVar('person','') : null;
			$this->taxnum = (JRequest::getVar('taxnum','') != '') ? JRequest::getVar('taxnum','') : null;
			$this->taxclass = (JRequest::getVar('taxclass','') != '') ? JRequest::getVar('taxclass','') : null;
		}
	}

	function store($updateNulls = false)
	{
		$db = JFactory::getDBO();
		$sql = "select count(*) from #__digistore_customers where id='" . $this->id . "'";
		$db->setQuery( $sql );
		$n = $db->loadResult();

		if ( $n < 1 & $this->id > 0 ) {
			$sql = "insert into #__digistore_customers(`id`) values ('" . $this->id . "')";
			$db->setQuery( $sql );
			$db->query();
		} else if ( $n < 1 & $this->id < 1 ) {
			return false;
		}

		parent::store($updateNulls = false);

		return true;
	}

}


?>