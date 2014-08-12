<?php

/**

 *
 * @package			DigiStore Joomla Extension
 * @author			foobla.com
 * @version			$Revision: 453 $
 * @lastmodified	$LastChangedDate: 2014-01-03 05:49:07 +0100 (Fri, 03 Jan 2014) $
 * @copyright		Copyright (C) 2013 foobla.com. All rights reserved.
 * @license			GNU/GPLv3 */
defined( '_JEXEC' ) or die( "Go away." );

jimport('joomla.application.component.modellist');
jimport('joomla.utilities.date');

class digistoreAdminModeldigistoreOrder extends JModelList{

	protected $_context = 'com_digistore.digistoreOrder';
	var $_orders;
	var $_order;
	var $_id = null;
	var $_total = 0;
	var $_pagination = null;
	var $_statusList = array("Active", "Pending");

	function __construct(){
		parent::__construct();
		$cids = JRequest::getVar( 'cid', 0, '', 'array' );

		$this->setId( (int) $cids[0] );
	}

	function populateState($ordering = NULL, $direction = NULL){
		$app = JFactory::getApplication('administrator');
		$this->setState('list.start', $app->getUserStateFromRequest($this->_context . '.list.start', 'limitstart', 0, 'int'));
		$this->setState('list.limit', $app->getUserStateFromRequest($this->_context . '.list.limit', 'limit', $app->getCfg('list_limit', 25) , 'int'));
		$this->setState('selected', JRequest::getVar('cid', array()));
	}

	function getPagination(){
		$pagination=parent::getPagination();
		$pagination->total=$this->total;
		if($pagination->total%$pagination->limit>0){
			$nr_pages=intval($pagination->total/$pagination->limit)+1;
		}
		else{ 
			$nr_pages=intval($pagination->total/$pagination->limit);
		}
		$pagination->set('pages.total',$nr_pages);
		$pagination->set('pages.stop',$nr_pages);
		return $pagination;
	}

	function getPromocodeByCode($code){
		$sql = "SELECT id FROM #__digistore_promocodes WHERE code = '" . $code . "'";
		$this->_db->setQuery( $sql );
		$promocode_id = $this->_db->loadResult();

		if ( $promocode_id ) {
			return $promocode_id;
		} else {
			return "0";
		}

	}

	function getExpireDate($plan_id, $purchase_date_int){
		$sql = "select `duration_count`, `duration_type` from #__digistore_plans where id=".intval($plan_id);
		$this->_db->setQuery($sql);
		$this->_db->query();
		$plan_values = $this->_db->loadAssocList();

		$time = "";
		$expires_date = "";
		switch($plan_values["0"]["duration_type"]){
			case "1" :
					$time = "hour";
					break;
			case "2" :
					$time = "day";
					break;
			case "3" :
					$time = "month";
					break;
			case "4" :
					$time = "year";
					break;
		}

		if($plan_values["0"]["duration_count"] != "-1"){
			$expires_date_int = strtotime("+".$plan_values["0"]["duration_count"]." ".$time, $purchase_date_int);
			$expires_date_string = date("Y-m-d H:i:s", $expires_date_int);
			$expires_date = $expires_date_string;
		}
		else{
			$expires_date = "0000-00-00 00:00:00";
		}
		return $expires_date;
	}

	function saveorder(){
		$post = JRequest::get('post');
		$config = JFactory::getConfig();
		$tzoffset = $config->get('offset');
		
		if(isset($post['purchase_date'])&& $post['purchase_date']){
			$date = JFactory::getDate($post['purchase_date']);
			$purchase_date = $date->toSql();
			$order_date = $date->toUNIX();
		} else{
			$purchase_date = date('Y-m-d H:i:s', time() + $tzoffset);
			$date = JFactory::getDate();
			$order_date = $date->toUNIX();
		}
		
		$order = array();
		$order['id'] = '0';
		$order['userid'] = $post['userid'];
		$order['order_date'] = $order_date;
		$order['processor'] = $post['processor'];
		$order['promocode'] = $post['promocode'];
		$order['promocodediscount'] = $post['discount'];
		$order['promocodeid'] = $this->getPromocodeByCode( $order['promocode'] );
		$order['number_of_licenses'] = count( $post['product_id'] );
		$order['currency'] = $post['currency'];
		$order['status'] = $post['status'];
		$order['tax'] = $post['tax'];
		$order['discount'] = $post['discount'];
		$order['shipping'] = $post['shipping'];
		$order['amount'] = $post['amount'];
		$order['amount_paid'] = trim($post['amount_paid']) != "" ? trim($post['amount_paid']) : '-1';
		$order['published'] = '1';

		$order_table = $this->getTable( 'digistoreOrder' );

		if(!$order_table->bind($order)){
			return false;
		}

		if(!$order_table->check()){
			return false;
		}

		if(!$order_table->store()){
			return false;
		}

		// Licenses
		$license_table = $this->getTable( 'digistoreLicense' );
		$sql = "select id from #__digistore_plans where `duration_count` = -1";
		$this->_db->setQuery($sql);
		$this->_db->query();
		$unlimited_plan_id = intval($this->_db->loadResult());

		
		$date_today = date('Y-m-d H:i:s', time() + $tzoffset);
		// exit();

		foreach($post['product_id'] as $key => $product_id){
			$lic_id = "0";
			//$purchase_date = date('Y-m-d H:i:s');
			//$date_today = date('Y-m-d H:i:s');
			
			

			$expires_date = "0000-00-00 00:00:00";
			$new_download_count = "0";
			$buy_type = "new";

			if(isset($post["subscr_type_select"][$key])&& $post["subscr_type_select"][$key] == "renewal"){// for renew license
				$buy_type = "renewal";
				$expires_date_string = "";
				$expires_date_int = "";
				$lic_id = intval($post["licences_select"][$key]);
				$sql = "select `purchase_date`, `expires`, `plan_id` from #__digistore_licenses where id=".intval($post["licences_select"][$key]);
				$this->_db->setQuery($sql);
				$this->_db->query();
				$detailes = $this->_db->loadAssocList();

				$purchase_date_string = $detailes["0"]["purchase_date"];
				$purchase_date_int = strtotime($purchase_date_string);

				$expires_date_string = "0000-00-00 00:00:00";
				$expires_date_int = "";

				if($detailes["0"]["expires"] == "0000-00-00 00:00:00" && $detailes["0"]["plan_id"] != $unlimited_plan_id){
					$plan_id = intval($post["subscr_plan_select"][$key]);
					$sql = "select `duration_count`, `duration_type` from #__digistore_plans where id=".intval($detailes["0"]["plan_id"]);
					$this->_db->setQuery($sql);
					$this->_db->query();
					$plan_values = $this->_db->loadAssocList();

					$time = "";
					switch($plan_values["0"]["duration_type"]){
						case "1" :
								$time = "hour";
								break;
						case "2" :
								$time = "day";
								break;
						case "3" :
								$time = "month";
								break;
						case "4" :
								$time = "year";
								break;
					}
					if($plan_values["0"]["duration_count"] != "-1"){
						$expires_date_int = strtotime("+".$plan_values["0"]["duration_count"]." ".$time, $purchase_date_int);
						$expires_date_string = date("Y-m-d H:i:s", $expires_date_int);
					}
					else{
						$expires_date_string = "0000-00-00 00:00:00";
					}
				}
				else{
					$expires_date_string = $detailes["0"]["expires"];
					$expires_date_int = strtotime($expires_date_string);

					$date_today_int = strtotime($date_today);
				if($expires_date_int < $date_today_int){
						$purchase_date_string = $date_today;
						$purchase_date_int = strtotime($purchase_date_string);
						$expires_date_string = $date_today;
						$expires_date_int = strtotime($expires_date_string);
					}

				}

				$plan_id = intval($post["subscr_plan_select"][$key]);
				$sql = "select `duration_count`, `duration_type` from #__digistore_plans where id=".intval($plan_id);
				$this->_db->setQuery($sql);
				$this->_db->query();
				$plan_values = $this->_db->loadAssocList();

				$time = "";
				switch($plan_values["0"]["duration_type"]){
					case "1" :
							$time = "hour";
							break;
					case "2" :
							$time = "day";
							break;
					case "3" :
							$time = "month";
							break;
					case "4" :
							$time = "year";
							break;
				}
				if($plan_values["0"]["duration_type"] != "0" && $expires_date_string != "0000-00-00 00:00:00"){//dowloads
					$expires_date_int = strtotime("+".$plan_values["0"]["duration_count"]." ".$time, $expires_date_int);
					$expires_date_string = date("Y-m-d H:i:s", $expires_date_int);
					$expires_date = $expires_date_string;
				}
				else{
					if($plan_values["0"]["duration_count"] != "-1"){
						$expires_date_int = strtotime("+".$plan_values["0"]["duration_count"]." ".$time, $purchase_date_int);
						$expires_date_string = date("Y-m-d H:i:s", $expires_date_int);
						$expires_date = $expires_date_string;
					}
					else{
						$expires_date = "0000-00-00 00:00:00";
					}
				}

				//check if is a downlaod plan to make expired date to 0000-00-00...
				$sql = "select id from #__digistore_plans where duration_count <> -1 and duration_type=0";
				$this->_db->setQuery($sql);
				$this->_db->query();
				$download_plans_ids = $this->_db->loadAssocList("id");
				$download_plans_ids = array_keys($download_plans_ids);

				if(in_array($plan_id, $download_plans_ids)){
					$expires_date = "0000-00-00 00:00:00";
				}
			}
			else{//for new license
				$buy_type = "new";
				$plan_id = intval($post["subscr_plan_select"][$key]);
				$sql = "select `duration_count`, `duration_type` from #__digistore_plans where id=".intval($plan_id);
				$this->_db->setQuery($sql);
				$this->_db->query();
				$plan_values = $this->_db->loadAssocList();

				$time = "";
				switch($plan_values["0"]["duration_type"]){
					case "1" :
							$time = "hour";
							break;
					case "2" :
							$time = "day";
							break;
					case "3" :
							$time = "month";
							break;
					case "4" :
							$time = "year";
							break;
				}

				$purchase_date_int = strtotime($purchase_date);
				if($plan_values["0"]["duration_count"] != "-1"){
					$expires_date_int = strtotime("+".$plan_values["0"]["duration_count"]." ".$time, $purchase_date_int);
					$expires_date_string = date("Y-m-d H:i:s", $expires_date_int);
					$expires_date = $expires_date_string;
				}
				else{
					$expires_date = "0000-00-00 00:00:00";
				}
			}

			$buy_date = date("Y-m-d H:i:s");
			$sql = "insert into #__digistore_logs (`userid`, `productid`, `buy_date`, `buy_type`) values (".$post['userid'].", ".$product_id.", '".$buy_date."', '".$buy_type."')";
			$this->_db->setQuery($sql);
			$this->_db->query();

			$sql = "SELECT domainrequired FROM `#__digistore_products` WHERE id = ".$product_id;
			$this->_db->setQuery( $sql );
			$producttype = $this->_db->loadResult();

			if($producttype == 3){
				$sql = "select f.featuredid as id, p.name as name, f.planid as plan_id from #__digistore_featuredproducts f, #__digistore_products p where f.featuredid=p.id and f.productid=".$product_id;
				$this->_db->setQuery($sql);
				$prodincludes = $this->_db->loadObjectList();

				foreach($prodincludes as $pi){
					$sql = "";
					if($post['subscr_type_select'][$key] == 'renewal'){
						$sql = "select `price` from #__digistore_products_renewals where product_id=".$pi->id." and `default`=1";
					}
					else{
						$sql = "select `price` from #__digistore_products_plans where product_id=".$pi->id." and `default`=1";
					}
					$this->_db->setQuery($sql);
					$this->_db->query();
					$amount_paid = $this->_db->loadResult();
					if(!isset($amount_paid)){ //if renew but not renew plans
						$sql = "select `price` from #__digistore_products_plans where product_id=".$pi->id." and `default`=1";
						$this->_db->setQuery($sql);
						$this->_db->query();
						$amount_paid = $this->_db->loadResult();
					}

					$license = array();
					$license['id'] = $lic_id;
					$license['userid'] = $order['userid'];
					$license['plan_id'] = $pi->plan_id;
					$license['productid'] = $pi->id;
					$license['domain'] = '';
					$license['amount_paid'] = $amount_paid;
					$license['orderid'] = $order_table->id;
					$license['dev_domain'] = '';
					$license['hosting_service'] = '';
					$license['ltype'] = 'package_item';
					$license['package_id'] = intval($product_id);
					$license['purchase_date'] = $purchase_date;
					$license['download_count'] = $new_download_count;
					$license['published'] = '1';
					$license['expires'] = $this->getExpireDate($pi->plan_id, $purchase_date_int);

					$sql = "select max(licenseid) from #__digistore_licenses";
					$this->_db->setQuery($sql);
					$this->_db->query();
					$licenseid = $this->_db->loadResult();
					if(isset($licenseid) && intval($licenseid) != "0"){
						if($post['subscr_type_select'][$key] == 'renewal'){
						}
						else{
							$license_table->licenseid = intval($licenseid) + 1;
						}
					}
					else{
						$license_table->licenseid = intval($licenseid) + 100000000 + 1;
					}

					if(!$license_table->bind($license)){
						die('stop bind');
						return false;
					}

					if(!$license_table->check()){
						die('stop check');
						return false;
					}

					if(!$license_table->store()){
						die('stop store');
						return false;
					}
				}
				continue;
			}

			$domain = "";
			$dev_domain = "";
			$old_orders = "";
			if($post['subscr_type_select'][$key] == 'renewal'){
				$sql = "select `domain`, `dev_domain`, `orderid`, `old_orders` from #__digistore_licenses where id=".trim($post['licences_select'][$key]);
				$this->_db->setQuery($sql);
				$this->_db->query();
				$renew_licence_value = $this->_db->loadAssocList();
				if(isset($renew_licence_value)){
					$domain = $renew_licence_value["0"]["domain"];
					$dev_domain = $renew_licence_value["0"]["dev_domain"];
					$old_orders = trim($renew_licence_value["0"]["old_orders"]).trim($renew_licence_value["0"]["orderid"])."|";
				}
			}

			// common or package
			$license = array();
			$license['id'] = $lic_id;
			$license['userid'] = $order['userid'];
			$license['plan_id'] = $post['subscr_plan_select'][$key];
			$license['productid'] = $product_id;
			$license['domain'] = $domain;
			$license['amount_paid'] = '0';
			$license['orderid'] = $order_table->id;
			$license['dev_domain'] = $dev_domain;
			$license['hosting_service'] = '';
			$license['expires'] = $expires_date;
			$license['old_orders'] = $old_orders;

			if($producttype == 3){
				$license['ltype'] = 'package';
			}
			else{
				$license['ltype'] = 'common';
			}

			$license['package_id'] = "0";
			$license['purchase_date'] = $purchase_date;
			$license['download_count'] = $new_download_count;
			$license['published'] = '1';

			if(isset($post['licences_select'][$key]) && !empty($post['licences_select'][$key])){
				if(isset($post['subscr_type_select'][$key]) && $post['subscr_type_select'][$key] == 'renewal'){
					$license['renew'] = 1;
					$license['renewlicid'] = $post['licences_select'][$key];
				} else {
					$license['renew'] = 0;
				}
			} else {
				$license['renew'] = 0;
			}

			// get price for license
			if ($license['renew']) {
				$sql = 'SELECT price FROM `#__digistore_products_renewals` WHERE product_id='.$product_id.' AND plan_id='.$license['plan_id'];
				$this->_db->setQuery( 'SELECT price FROM `#__digistore_products_renewals` WHERE product_id='.$product_id.' AND plan_id='.$license['plan_id'] );
			} else {
				$sql = 'SELECT price FROM `#__digistore_products_plans` WHERE product_id='.$product_id.' AND plan_id='.$license['plan_id'];
				$this->_db->setQuery( $sql );
			}
			$license_price = $this->_db->loadResult();
			$license['amount_paid'] = ($license_price) ? $license_price : '0';

			if(!$license_table->bind($license)){
				return false;
			}

			if(!$license_table->check()){
				return false;
			}

			$sql = "select max(licenseid) from #__digistore_licenses";
			$this->_db->setQuery($sql);
			$this->_db->query();
			$licenseid = $this->_db->loadResult();
			if(isset($licenseid) && intval($licenseid) != "0"){
				if($post['subscr_type_select'][$key] == 'renewal'){
					//$license_table->licenseid = intval($licenseid);
				}
				else{
					$license_table->licenseid = intval($licenseid) + 1;
				}
			}
			else{
				$license_table->licenseid = intval($licenseid) + 100000000 + 1;
			}

			if(!$license_table->store()){
				return false;
			}

			// Set customer groups
			digistoreAdminHelper::expireUserProduct($license_table->userid);

			//set no emails send to this course
			$db = JFactory::getDBO();
			$sql = "update #__digistore_products_emailreminders set `send`=0 where product_id=".intval($license["productid"]);
			$db->setQuery($sql);
			$db->query();
		}

		$license["licenseid"] = $license_table->id;
		require_once(JPATH_SITE.DS."components".DS."com_digistore".DS."helpers".DS."cronjobs.php");
		submitEmailFromBackend($order, $license);

		return true;
	}

	function _getRate($ptc, $pc, $cust)
	{
		$configs = $this->getInstance("digistoreConfig", "digistoreAdminModel");
		$configs = $configs->getConfigs();
		$db = JFactory::getDBO();

		$sql = "select tra.rate
				from #__digistore_tax_rate tra, #__digistore_tax_rule tru
				where tru.trate=tra.id
				  and tru.pclass=".intval($ptc);
		$db->setQuery($sql);
		$db->query();
		$res = $db->loadResult();

		// EU Rule
		if ($configs->tax_eumode)
		{
			// Get customer country percentage
			$sql = "select rate
					from #__digistore_tax_rate
					where country='" . $cust->country . "'";
			$db->setQuery($sql);
			$db->query();
			$ratecountry = $db->loadResult();

			if (!(int)$cust->person && $cust->country != $configs->country)
			{
				$res = 0;
			}
			else
			{
				$res = $ratecountry;
			}
		}

		if(!isset($res))
		{
			$res = 0;
		}

		return $res;
	}

	function getTax( $product_id, $cust_id, &$price ){
		$db = JFactory::getDBO();

		$configs = $this->getInstance("digistoreConfig", "digistoreAdminModel");
		$configs = $configs->getConfigs();

		$sql = "select * from #__digistore_tax_productclass order by ordering asc";
		$db->setQuery($sql);
		$taxpclass = $db->loadObjectList();

		$sql = "select * from #__digistore_productclass order by ordering asc";
		$db->setQuery($sql);
		$pclass = $db->loadObjectList();

		$taxvalue = 0;

		$sql = "select * from #__digistore_products where id = ".$product_id;
		$db->setQuery($sql);
		$item = $db->loadObject();

		$sql ="SELECT * FROM #__digistore_customers WHERE id=".$cust_id;
		$db->setQuery($sql);
		$cust = $db->loadObject();

		if ($item->domainrequired == 2){
			if ($configs->tax_classes > 0){
				$itemtax = 0;
				$ptc = $configs->tax_classes;
				$pc = $item->class > 0 ? $item->class : $pclass[0]->id;
				$rate = $this->_getRate($ptc, $pc, $cust);

				if($configs->tax_catalog == 0){
					$itemtax = $price * $rate/100;
				}
				else{
					$itemtax = $price /(1 + $rate/100);
				}
				$taxvalue = $itemtax;
			}
			else{
				$itemtax = 0;
				$taxvalue = $itemtax;
			}
		}
		else{
			$itemtax = 0;
			//product tax class
			$ptc = $item->taxclass > 0 ? $item->taxclass : $taxpclass[0]->id;
			//product class
			$pc = $item->class > 0 ? $item->class : $pclass[0]->id;
			$rate = $this->_getRate($ptc, $pc, $cust);

			if($configs->tax_catalog == 0){
				$itemtax = $price * $rate/100;
			}
			else{
				$itemtax = $price /(1 + $rate/100);		
			}
			$taxvalue = $itemtax;
		}
		$price = $price - $taxvalue;
		return $taxvalue;
	}


	function calcPrice($req){
		$c = $this->getInstance( "digistoreConfig", "digistoreAdminModel" );
		$configs = $c->getConfigs();

		$result = array();
		$amount_subtotal = 0;
		$amount = 0;
		$taxvalue = 0;

		$cust_id = $req->customer_id;
		if(isset($req)){
			foreach($req->pids as $item ) {
				if (!empty($item[0])) {
					if ( $item[1] == 'renewal' ) {
						$sql = "SELECT * FROM #__digistore_products_renewals WHERE (product_id = " . $item[0] . " AND plan_id = " . $item[3] . ")";
					} else {
						$sql = "SELECT * FROM #__digistore_products_plans WHERE (product_id = " . $item[0] . " AND plan_id = " . $item[3] . ")";
					}

					$this->_db->setQuery( $sql );
					$plan = $this->_db->loadObject();

					$product_id = $item[0];
					$price = $plan->price;

					$amount_subtotal += $price;
					$amount += $price;
					$taxvalue += $this->getTax( $product_id, $cust_id, $price );
				}
			}
		}
		//add tax to total
		$amount = $amount + $taxvalue;

//--------------------------------------------------------
// Promo code
//--------------------------------------------------------

		$promovalue = 0;
		if($req->promocode != "none"){
			$q = "select * from #__digistore_promocodes where code = '".trim($req->promocode)."'";
			$this->_db->setQuery($q);
			$promo = $this->_db->loadObject();

			if($promo->id > 0){
				$promoid = $promo->id;
				$promocode = $promo->code;
			}
			else{
				$promoid = '0';
				$promocode = '';
			}

			$promo_applied = -1; //no need for promo

			if($promo->id > 0){//valid promocode was provided
				if($promo->codelimit <= $promo->used && $promo->codelimit > 0){
				}
				else{
					if($promo->aftertax == '0'){//promo discount should be applied before taxation
						$promo_applied = 1; //all discounts are applied
						if($promo->promotype == '0'){//use absolute values
							$amount -= $promo->amount;
							$promovalue = $promo->amount;
						}
						else{ //use percentage
							$promovalue = $amount * $promo->amount / 100;
							$amount *= 1 - $promo->amount / 100;
						}
					}
					else{//promo discount should be applied after tax
						$promo_applied = 0; //we should apply promo later
						//nothing to do here - tax is not calculated yet
					}
				}
			}
			//now lets apply promo discounts if there are any
			if($promo_applied == 0){
				if($promo->promotype == '0'){//use absolute values
					$amount -= $promo->amount;
					$promovalue = $promo->amount;
				}
				else{ //use percentage
					$promovalue = $amount * $promo->amount / 100;
					$amount *= 1 - $promo->amount / 100;
				}
			}
		}

//--------------------------------------------------------
		$amount_subtotal = $amount_subtotal < 0 ? "0.00" : $amount_subtotal;
		$amount = $amount < 0 ? "0.00" : $amount;

		$result['amount'] = trim( digistoreAdminHelper::format_price( $amount_subtotal, $configs->currency, true, $configs ) );
		$result['amount_value'] = trim( digistoreAdminHelper::format_price( $amount_subtotal, $configs->currency, false, $configs ) );
		$result['tax_value'] = trim( digistoreAdminHelper::format_price( $taxvalue, $configs->currency, false, $configs ) );
		$result['tax'] = trim( digistoreAdminHelper::format_price( $taxvalue, $configs->currency, true, $configs ) );;
		$result['discount_sign'] = trim( digistoreAdminHelper::format_price( $promovalue, $configs->currency, true, $configs ) );
		$result['discount'] = trim( digistoreAdminHelper::format_price( $promovalue, $configs->currency, false, $configs ) );
		$result['total'] = trim( digistoreAdminHelper::format_price( $amount, $configs->currency, true, $configs ) );
		$result['total_value'] = trim( digistoreAdminHelper::format_price( $amount, $configs->currency, false, $configs ) );
		$result['currency'] = $configs->currency;
		$result['shipping'] = 0;

		return $result;
	}

	function setId( $id )
	{
		$this->_id = $id;
		$this->_order = null;
	}

	protected function getListQuery(){
		$db = JFactory::getDBO();
		$c = $this->getInstance( "digistoreConfig", "digistoreAdminModel" );
		$configs = $c->getConfigs();

		$startdate = JRequest::getVar("startdate", "", "request");
		$startdate = strtotime($startdate);

		$enddate = JRequest::getVar("enddate", "", "request");
		$enddate = strtotime($enddate);

		$keyword = JRequest::getVar( "keyword", "", "request" );
		$keyword_where = "(u.username like '%" . $keyword . "%' 
							or c.firstname like '%" . $keyword . "%' 
							or c.lastname like '%" . $keyword . "%'
							or o.id like '%" . $keyword . "%')";

		$sql = "SELECT o.*, u.username, c.firstname, c.lastname
				FROM #__digistore_orders o
						INNER JOIN 
					#__users u ON u.id=o.userid
						LEFT JOIN 
					#__digistore_customers c ON u.id=c.id ";
		$where = array();
		if($startdate > 0) 
			$where[]=" o.order_date > " . $startdate . " ";
		if($enddate > 0) 
			$where[]=" o.order_date < " . $enddate . " ";
		if(strlen( trim( $keyword ) ) > 0)
			$where[]=$keyword_where;
		$where_clause = (count($where))? ' WHERE '. implode(' AND ',$where) : '';
		$sql .= $where_clause. " ORDER BY o.id DESC";
		return $sql;
	}

	function getItems(){
		$config = JFactory::getConfig();
		$app = JFactory::getApplication('administrator');
		$limistart = $app->getUserStateFromRequest($this->context.'.list.start', 'limitstart');
		$limit = $app->getUserStateFromRequest($this->context.'.list.limit', 'limit', $config->get('list_limit'));
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query = $this->getListQuery();

		$db->setQuery($query);
		$db->query();
		$result	= $db->loadObjectList();
		$this->total=count($result);
		$db->setQuery($query, $limistart, $limit);
		$db->query();
		$result	= $db->loadObjectList();

		foreach($result as $i => $v){
			$sql = "select count(*) from #__digistore_licenses where orderid=".$v->id;
			$db->setQuery($sql);
			$result[$i]->licensenum = $db->loadResult();
		}

		return $result;
	}

	public static function getChargebacks($order, $license=0)
	{
		$db = JFactory::getDBO();
		$sql = "SELECT SUM(`cancelled_amount`)
				FROM `#__digistore_licenses`
				WHERE `cancelled`=1
				  AND `orderid`=" . (int) $order . ($license ? " AND `licenseid`='".$license."'" : '');
		$db->setQuery($sql);
		return $db->loadResult();
	}

	public static function getRefunds($order, $license=0)
	{
		$db = JFactory::getDBO();
		$sql = "SELECT SUM(`cancelled_amount`)
				FROM `#__digistore_licenses`
				WHERE `cancelled`=2
				  AND `orderid`=" . (int) $order . ($license ? " AND `licenseid`='".$license."'" : '');
		$db->setQuery($sql);
		return $db->loadResult();
	}

	public static function getDeleted($order, $license=0)
	{
		$db = JFactory::getDBO();
		$sql = "SELECT SUM(`amount_paid`)
				FROM `#__digistore_licenses`
				WHERE `cancelled`=3
				  AND `orderid`=" . (int) $order . ($license ? " AND `licenseid`='".$license."'" : '');
		$db->setQuery($sql);
		return $db->loadResult();
	}

	public static function isLicenseDeleted($id)
	{
		$db = JFactory::getDBO();
		$sql = "SELECT `cancelled`
				FROM `#__digistore_licenses`
				WHERE `licenseid`='" . $id . "'";
		$db->setQuery($sql);
		return $db->loadResult();
	}

	/*function getlistOrders(){
		if ( empty( $this->_orders ) ) {
			$c = $this->getInstance( "digistoreConfig", "digistoreAdminModel" );
			$configs = $c->getConfigs();

			$startdate = JRequest::getVar("startdate", "", "request");
			$startdate = strtotime($startdate);

			$enddate = JRequest::getVar("enddate", "", "request");
			$enddate = strtotime($enddate);

			$keyword = JRequest::getVar( "keyword", "", "request" );
			$keyword_where = " and (u.username like '%" . $keyword . "%' or c.firstname like '%" . $keyword . "%' or c.lastname like '%" . $keyword . "%'
							or o.id like '%" . $keyword . "%')";

			$db = JFactory::getDBO();

			$sql = "select o.*, u.username, c.firstname, c.lastname from #__digistore_orders o, #__users u, #__digistore_customers c "
			. " where u.id=o.userid and c.id=u.id "
			. ($startdate > 0 ? " and o.order_date > " . $startdate . " " : "")
			. ($enddate > 0 ? " and o.order_date < " . $enddate . " " : "")
			. (strlen( trim( $keyword ) ) > 0 ? $keyword_where . " " : "")
			. " order by o.id desc";

			$this->_total = $this->_getListCount( $sql );
			if ( $this->getState( 'limitstart' ) > $this->_total )
				$this->setState( 'limitstart', 0 );
			if ( $this->getState( 'limitstart' ) > 0 & $this->getState( 'limit' ) == 0 )
				$this->setState( 'limitstart', 0 );

			if($this->getState('limit') == 0){
				$this->setState('limit', 100);
			}

			$this->_orders = $this->_getList( $sql, $this->getState( 'limitstart' ), $this->getState( 'limit' ) );

			foreach ( $this->_orders as $i => $v ) {
				$sql = "select count(*) from #__digistore_licenses where orderid=" . $v->id;
				$db->setQuery( $sql );
				$this->_orders[$i]->licensenum = $db->loadResult();
			}
		}

		return $this->_orders;
	}*/

	function getOrder($id = 0){
		if(empty($this->_order)){
			$db = JFactory::getDBO();
			if($id > 0){
				$this->_id = $id;
			}
			else{
				$id = $this->_id;
			}
			$sql = "select o.*, count(l.id) as lcount, u.username "
			. " from #__digistore_licenses l, #__digistore_orders o, #__users u "
			. " where l.orderid=o.id and o.userid=u.id and o.id='" . $id . "' group by o.id order by o.order_date desc";
			$this->_order = $this->_getList($sql);
			if(isset($this->_order) && isset($this->_order["0"])){
				$this->_order = $this->_order["0"];
			}

			//check on old orders
			if(!isset($this->_order) || count($this->_order) <= 0){
				$sql = "select o.*, count(l.id) as lcount, u.username "
					. " from #__digistore_licenses l, #__digistore_orders o, #__users u "
					. " where (l.old_orders like '".$id."|%' or l.old_orders like '%|".$id."|%') and o.userid=u.id and o.id='".$id."' group by o.id order by o.order_date desc";
				$this->_order = $this->_getList($sql);
			}
			//check on old orders

			//search default product
			$sql = "select p.*, l.licenseid, l.amount_paid as price, l.id as lid, l.amount_paid from #__digistore_products p, #__digistore_licenses l "
			. " where p.id=l.productid and l.ltype <> 'package_item' and (l.orderid='" . $id . "' or (l.old_orders like '".$id."|%' or l.old_orders like '%|".$id."|%'))";
			$db->setQuery($sql);
			$prods = $db->loadObjectList();
			//search default product

			//search license packages
			$sql = "select p.* from #__digistore_products p where id in (SELECT package_id FROM `#__digistore_licenses` WHERE `orderid`=".$id." and `ltype`='package_item' group by package_id)";
			$db->setQuery($sql);
			$prods2 = $db->loadObjectList();
			if(isset($prods2)){
				foreach($prods2 as $key=>$product){
					$sql = "select p.price as total, GROUP_CONCAT(l.licenseid) as licenses from #__digistore_licenses l, #__digistore_products_plans p where l.`orderid`=".$id." and l.package_id=".intval($product->id)." and l.package_id=p.product_id";
					$db->setQuery($sql);
					$db->query();
					$result = $db->loadAssocList();
					if(isset($result)){
						$prods2[$key]->licenseid = $result["0"]["licenses"];
						$prods2[$key]->amount_paid = $result["0"]["total"];
						$prods2[$key]->price = $result["0"]["total"];
					}
				}
			}
			//search license packages
			$prods = array_merge($prods, $prods2);

			//check on old orders
			if(!isset($prods) || count($prods) <= "0"){
				$sql = "select p.*, l.licenseid, l.amount_paid as price, l.id as lid, l.amount_paid from #__digistore_products p, #__digistore_licenses l "
					. " where p.id=l.productid and (l.old_orders like '".$id."|%' or l.old_orders like '%|".$id."|%')";
				$db->setQuery( $sql );
				$prods = $db->loadObjectList();
			}
			//check on old orders

			$distinct = array();
			$prods1 = array();

			foreach($prods as $i => $v){
				if(!in_array( $v->id, $distinct)){
					$distinct[] = $v->id;
					$prods1[$v->id] = $v;
					$prods1[$v->id]->count = 1;
				}
				else{
					$prods1[$v->id]->count++;
				}
			}

			foreach($prods as $i => $v){
				$sql = "select * from #__digistore_licensefields where licenseid=".$v->lid;
				$db->setQuery($sql);
				$prods[$i]->orig_fields = $db->loadObjectList();
			}
			$this->_order->products = $prods;
			if(is_array($this->_order)){
				$this->_order["0"]->products = $prods;
				$this->_order = $this->_order["0"];
			}
		}
		return $this->_order;
	}

	function store()
	{
		dsdebug();

		$item = $this->getTable( 'digistoreOrder' );
		$data = JRequest::get( 'post' );
		if ( !$item->bind( $data ) ) {
//			$this->setError($item->getErrorMsg());
			return false;
		}

		if ( !$item->check() ) {
//			$this->setError($item->getErrorMsg());
			return false;
		}

		if ( !$item->store() ) {
//			$this->setError($item->getErrorMsg());
			return false;
		}
		return true;

	}

	function delete()
	{
		$db = JFactory::getDBO();
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$item = $this->getTable( 'digistoreOrder' );

		foreach ($cids as $cid)
		{
			if (!$item->delete($cid))
			{
				$this->setError($item->getErrorMsg());
				return false;
			}
		}

		// delete licenses
		$db->setQuery('delete from #__digistore_licenses where orderid in ('.implode(',', $cids).')');

		if (!$db->query())
		{
			$this->setError($db->getErrorMsg());
			return false;
		}

		return true;
	}

	function publish()
	{
		$db = JFactory::getDBO();
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$task = JRequest::getVar( 'task', '', 'post' );
		$item = $this->getTable( 'digistoreOrder' );

		// Orders
		if ( $task == 'publish' ) {
			$sql = "update #__digistore_orders set published='1' where id in ('" . implode( "','", $cids ) . "')";
			$return = 1;
		} else {
			$sql = "update #__digistore_orders set published='0' where id in ('" . implode( "','", $cids ) . "')";
			$return = -1;
		}
		$db->setQuery( $sql );
		if ( !$db->query() ) {
			$this->setError( $db->getErrorMsg() );
			return 0;
		}

		// Licenses
		if ( $task == 'publish' ) {
			$sql = "update #__digistore_licenses set published='1' where orderid in ('" . implode( "','", $cids ) . "')";
		} else {
			$sql = "update #__digistore_licenses set published='0' where orderid in ('" . implode( "','", $cids ) . "')";
		}
		$db->setQuery( $sql );
		if ( !$db->query() ) {
			$this->setError( $db->getErrorMsg() );
			return 0;
		}

		return $return;
	}

	function cycleStatus(){
		$db = JFactory::getDBO();
		$cids = JRequest::getVar( 'cid', array(0), 'request', 'array' );
		$sql = "select status from #__digistore_orders where  id in ('" . implode( "','", $cids ) . "')";
		$db->setQuery( $sql );

		$status = $db->loadResult();
		$max_status = count( $this->_statusList ) - 1;
		$statid = $max_status;
		foreach ( $this->_statusList as $i => $stat){
			if ( $stat == $status ) {
				$statid = $i;
				break;
			}
		}
		$statid++;
		if ( $statid > $max_status )
			$statid = 0;
		$status = $this->_statusList[$statid];
		$sql = "update #__digistore_orders set status='" . $status . "' where id in ('" . implode( "','", $cids ) . "')";
		$db->setQuery($sql);
		$res = true;

		if(!$db->query()){
			$res = false;
		}

		if($status == "Pending"){
			$sql = "update #__digistore_licenses set published=0 where orderid in ('".implode("','", $cids)."')";

		}
		elseif($status == "Active"){
			$sql = "update #__digistore_licenses set published=1 where orderid in ('" . implode( "','", $cids ) . "')";
			/*foreach($cids as $cid){
				$this->sendApprovedEmail($cid);
			}*/
		}
		$db->setQuery($sql);
		if(!$db->query()){
			$res = false;
		}
		return $res;
	}

	function sendApprovedEmail( $cid = 0 )
	{
		if ( $cid < 1 )
			return;
		$db = JFactory::getDBO();
		$order = $this->getTable( "digistoreOrder" );
		$order->load( $cid );

		$c = $this->getInstance( "digistoreConfig", "digistoreAdminModel" );
		$configs = $c->getConfigs();

		$cust_info = $this->getTable( "digistoreCustomer" );
		$cust_info->load( $order->userid );

		$my = $cust_info;

		$database = JFactory::getDBO();

		$mes = new stdClass();

		$mes->body = "Template is empty";
		$sql = "SELECT * FROM #__digistore_mailtemplates where `type`='approved'";
		$database->setQuery( $sql );
		$db = JFactory::getDBO();
		$db->setQuery( $sql );
		$mes = $db->loadObjectList();
		$mes = $mes[0];
		$message = $mes->body;

		$timestamp = time();
//		$email = $this->getTable("digistoreMail");
		$email->date = $timestamp;
		$email->flag = "order";
		$email->email = trim( $my->email );


		$subject = $mes->subject;
		// Replace all variables in template
		$flag = "approved";
		$promo = new stdClass(); //$cart->get_promo($cust_info);
		$promo->id = $order->promocodeid;
		$promo->code = $order->promocode;
		if ( $promo->id > 0 ) {
			$promoid = $promo->id;
			$promocode = $promo->code;
		} else {
			$promoid = '0';
			$promocode = '0';
		}

		global $mainframe;
		$sitename = (trim( $configs->store_name ) != '') ? $configs->store_name : $mainframe->getCfg( 'sitename' );
		$siteurl = (trim( $configs->store_url ) != '') ? $configs->store_url : $mosConfig_live_site;

		$message = str_replace( "[SITENAME]", $sitename, $message );

		$message = str_replace( "../%5BSITEURL%5D", $siteurl, $message );
		$message = str_replace( "%5BSITEURL%5D", $siteurl, $message );
		$message = str_replace( "[SITEURL]", $siteurl, $message );


		$message = str_replace( "[CUSTOMER_USER_NAME]", $my->username, $message );
		$message = str_replace( "[CUSTOMER_FIRST_NAME]", $my->firstname, $message );
		$message = str_replace( "[CUSTOMER_LAST_NAME]", $my->lastname, $message );
		$message = str_replace( "[CUSTOMER_EMAIL]", $my->email, $message );

		$message = str_replace( "[TODAY_DATE]", date( $configs->time_format, $timestamp ), $message );
		$message = str_replace( "[ORDER_ID]", $cid, $message );
		$message = str_replace( "[ORDER_AMOUNT]", $order->amount, $message );
		$message = str_replace( "[NUMBER_OF_LICENSES]", $order->number_of_licenses, $message );
		$message = str_replace( "[PROMO]", $promo->code, $message );


		$displayed = array();
		$product_list = '';

		$sql = "select l.*, p.name, p.sendmail from #__digistore_licenses l, #__digistore_products p where l.productid=p.id and l.orderid=" . $cid;
		$db->setQuery( $sql );
		$items = $db->loadObjectList();

		$product_list = "";
		foreach ( $items as $item ) {
			$sql = "select * from #__digistore_license_fields where licenseid=" . $item->id;
			$db->setQuery( $sql );
			$fields = $db->loadObjectList();
			$optionlist = '';
			if ( count( $fields ) > 0 )
				foreach ( $fields as $v ) {
					$optionlist .= $v->fieldname . ": " . $v->optionname . "<br />";
				}
			if ( !in_array( $item->name, $displayed ) ) {
				$product_list .= $item->name . '<br />';
				$product_list .= $optionlist . '<br />';
			}

			$displayed[] = $item->name;
		}
		$message = str_replace( "[PRODUCTS]", $product_list, $message );
		$email->body = $message;


		//subject
		$subject = str_replace( "[SITENAME]", $sitename, $subject );

		$subject = str_replace( "../%5BSITEURL%5D", $siteurl, $subject );
		$subject = str_replace( "%5BSITEURL%5D", $siteurl, $subject );
		$subject = str_replace( "[SITEURL]", $siteurl, $subject );

		$message = str_replace( "[CUSTOMER_USER_NAME]", $my->username, $message );
		$message = str_replace( "[CUSTOMER_FIRST_NAME]", $my->firstname, $message );
		$message = str_replace( "[CUSTOMER_LAST_NAME]", $my->lastname, $message );
		$message = str_replace( "[CUSTOMER_EMAIL]", $my->email, $message );


		$message = str_replace( "[TODAY_DATE]", date( $configs->time_format, $timestamp ), $message );
		$message = str_replace( "[ORDER_ID]", $cid, $message );
		$message = str_replace( "[ORDER_AMOUNT]", $order->amount, $message );
		$message = str_replace( "[NUMBER_OF_LICENSES]", $order->number_of_licenses, $message );
		$subject = str_replace( "[PROMO]", $promo->code, $subject );
		$displayed = array();
		$product_list = '';
		foreach ( $items as $item ) {
			if ( !in_array( $item->name, $displayed ) )
				$product_list .= $item->name . '<br />';
			$displayed[] = $item->name;
		}
		$subject = str_replace( "[PRODUCTS]", $product_list, $subject );

		$subject = html_entity_decode( $subject, ENT_QUOTES );

		$message = html_entity_decode( $message, ENT_QUOTES );

		// Send email to user
//			global $mosConfig_mailfrom, $mosConfig_fromname, $configs;

		$mosConfig_mailfrom = $mainframe->getCfg( "mailfrom" );
		$mosConfig_fromname = $mainframe->getCfg( "fromname" );
		if ( $configs->usestoremail == '1' && strlen( trim( $configs->store_name ) ) > 0 && strlen( trim( $configs->store_email ) ) > 0 ) {
			$adminName2 = $configs->store_name;
			$adminEmail2 = $configs->store_email;
		} else if ( $mosConfig_mailfrom != "" && $mosConfig_fromname != "" ) {
			$adminName2 = $mosConfig_fromname;
			$adminEmail2 = $mosConfig_mailfrom;
		} else {

			$query = "SELECT name, email"
			. "\n FROM #__users"
			. "\n WHERE LOWER( usertype ) = 'superadministrator'"
			. "\n OR LOWER( usertype ) = 'super administrator'"
			;
			$database->setQuery( $query );
			$rows = $database->loadObjectList();
			$row2 = $rows[0];
			$adminName2 = $row2->name;
			$adminEmail2 = $row2->email;
		}


		$mailSender = JFactory::getMailer();
		$mailSender->IsHTML( true );
		$mailSender->addRecipient( $my->email );
		$mailSender->setSender( array($adminEmail2, $adminName2) );
		$mailSender->setSubject( $subject );
		$mailSender->setBody( $message );

		if ( !$mailSender->Send() ) {

//			<Your error code management>
		}
//			mosMail( $adminEmail2, $adminName2, $my->email, $subject, $message, 1 ); // Send mail
		if ( $configs->sendmailtoadmin != 0 ) {


			$mailSender = JFactory::getMailer();
			$mailSender->IsHTML( true );
			$mailSender->addRecipient( $adminEmail2 );
			$mailSender->setSender( array($adminEmail2, $adminName2) );
			$mailSender->setSubject( $subject );
			$mailSender->setBody( $message );
			if ( !$mailSender->Send() ) {
//					<Your error code management>
			}
//				mosMail( $adminEmail2, $adminName2, $adminEmail2, $subject, $message, 1 ); // Send mail
		}

//###
		//$email->store(); // Nik (10/23/2006): Save in #__digistore_sendmails

		$sent = array();

		//send per product emails
		foreach ( $items as $item ) {
			if ( !in_array( $item->name, $sent ) && $item->sendmail == '1' ) {
				$subject = $item->productemailsubject;
				$subject = str_replace( "[SITENAME]", $sitename, $subject );

				$subject = str_replace( "../%5BSITEURL%5D", $siteurl, $subject );
				$subject = str_replace( "%5BSITEURL%5D", $siteurl, $subject );
				$subject = str_replace( "[SITEURL]", $siteurl, $subject );

				//$query = "select lastname from #__digistore_customer where userid=".$my->id;
				//$database->setQuery($query);
				//$lastname = $database->loadResult();

				$subject = str_replace( "[CUSTOMER_USER_NAME]", $my->username, $subject );
				$subject = str_replace( "[CUSTOMER_FIRST_NAME]", $my->firstname, $subject );
				$subject = str_replace( "[CUSTOMER_LAST_NAME]", $my->lastname, $subject );
				$subject = str_replace( "[CUSTOMER_EMAIL]", $my->email, $subject );

				$subject = str_replace( "[TODAY_DATE]", date( $configs->time_format, $timestamp ), $subject );

				$message = $item->productemail;
				$message = str_replace( "[SITENAME]", $sitename, $message );

				$message = str_replace( "../%5BSITEURL%5D", $siteurl, $message );
				$message = str_replace( "%5BSITEURL%5D", $siteurl, $message );
				$message = str_replace( "[SITEURL]", $siteurl, $message );

				$message = str_replace( "[CUSTOMER_USER_NAME]", $my->username, $message );
				$message = str_replace( "[CUSTOMER_FIRST_NAME]", $my->firstname, $message );
				$message = str_replace( "[CUSTOMER_LAST_NAME]", $my->lastname, $message );
				$message = str_replace( "[CUSTOMER_EMAIL]", $my->email, $message );

				$message = str_replace( "[TODAY_DATE]", date( $configs->time_format, $timestamp ), $message );

				$optionlist = '';


				$sql = "select * from #__digistore_license_fields where licenseid=" . $item->id;
				$db->setQuery( $sql );
				$fields = $db->loadObjectList();
				$optionlist = '';
				if ( count( $fields ) > 0 )
					foreach ( $fields as $v ) {
						$optionlist .= $v->fieldname . ": " . $v->optionname . "<br />";
					}

				$message = str_replace( "[ATTRIBUTES]", $optionlist, $message );
				$message = str_replace( "[PRODUCT_NAME]", $item->name, $message );

				$subject = str_replace( "[ATTRIBUTES]", $optionlist, $subject );
				$subject = str_replace( "[PRODUCT_NAME]", $item->name, $subject );
				$mailSender = JFactory::getMailer();
				$mailSender->IsHTML( true );
				$mailSender->addRecipient( $my->email );
				$mailSender->setSender( array($adminEmail2, $adminName2) );
				$mailSender->setSubject( $subject );
				$mailSender->setBody( $message );
				if ( !$mailSender->Send() ) {
//						<Your error code management>
				}

				//		mosMail( $adminEmail2, $adminName2, $my->email, $subject, $message, 1 ); // Send mail
				$sent[] = $item->name;
			}
		}

	}

}

;


?>