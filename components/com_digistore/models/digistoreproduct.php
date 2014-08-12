<?php
/**
* @package			DigiStore Joomla Extension
 * @author			foobla.com
 * @version			$Revision: 376 $
 * @lastmodified	$LastChangedDate: 2013-10-21 11:54:05 +0200 (Mon, 21 Oct 2013) $
 * @copyright		Copyright (C) 2013 foobla.com. All rights reserved.
* @license


*/

defined ('_JEXEC') or die ("Go away.");

jimport ("joomla.aplication.component.model");

class digistoreModeldigistoreProduct extends obModel
{

	var $_products;
	var $_product;
	var $_id = null;
	var $_total = 0;
	var $_pagination = null;

	function __construct () {
		parent::__construct();
		$cids = JRequest::getVar('cid', 0, '', 'array');
		$this->setId((int)$cids[0]);
	}


	function setId($id) {
		$this->_id = $id;
		$this->_product = null;
	}

	function getListProducts(){
		$my = JFactory::getUser();
		if(empty ($this->_products)){
			$user = JFactory::getUser();
			$where[] = " published = 1 ";
			$where[] = " hide_public = 0 ";

			$sql = "select * from #__digistore_products".
				(count($where) > 0 ? " where ": "").
				implode (" and ", $where);

			$order = " order by ordering asc  ";

			$this->_total = @$this->_getListCount( $sql . $order );

			
			$this->_products = $this->_getList( $sql . $order, $this->getState('limitstart'), $this->getState('limit'));
			$this->_pagination = new JPagination($this->_total, $this->getState('limitstart'), $this->getState('limit')); 

			if (count($this->_products) > 0)
			foreach ($this->_products as $i => $v) {
				if ($v->usestock) {
					if ($v->used >= $v->stock) {

						if ($v->emptystockact == 1){
						}
						elseif($v->emptystockact == 2){
							unset($this->_products[$i]);
						}
					}

				}

			}

		}
		$this->_addProductTax();
		return $this->_products;
	}

	function getProduct($id = 0){
		$my = JFactory::getUser();
		if(empty($this->_product)){
			$this->_product = $this->getTable("digistoreProduct");
			if ($id) $this->_id = $id;
			$this->_product->load($this->_id);
		}
		$this->_products[0] = $this->_product;
		$this->_addProductTax();
		$this->_product = $this->_products[0];
		if($this->_product->usestock){
			if($this->_product->used >= $this->_product->stock){
				if($this->_product->emptystockact == 1){
				}
				elseif($this->_product->emptystockact == 2){
					$this->_product = null;
				}
			}
		}

		$db = JFactory::getDBO();
		$sql = "select `path` from #__digistore_products_images where `product_id`=".intval($this->_product->id)." order by `order` asc";
		$db->setquery($sql);
		$db->query();
		$all_path = $db->loadColumn();
		$this->_product->prodimages = implode(",\\n", $all_path);

		$sql = "select `path` from #__digistore_products_images where `product_id`=".intval($this->_product->id)." and `default`=1";
		$db->setquery($sql);
		$db->query();
		$default_path = $db->loadResult();
		$this->_product->defprodimage = $default_path;

		return $this->_product;
	}

	function getPlansForProduct($id = 0) {
		$db = JFactory::getDBO();
	   	$id = intval($id);
		$sql = "SELECT p.id, p.name, r.price
				FROM `#__digistore_products_plans` AS r
				LEFT JOIN `#__digistore_plans` AS p ON r.`plan_id` = p.id
				WHERE r.`product_id` ={$id}
				AND p.published =1 ";
		$db->setQuery($sql);
		$res = $db->loadObjectList();
		return $res;
	}

	function getCategoryProducts($catid, &$totalprods)
	{
		if(intval($catid) < 1){
			return null;
		}
		$this->_products = null;
		$my = JFactory::getUser();

		$date_today = time();
		//print '<pre>'; print_r($my); print '</pre>';

		$where[] = " ( (p.usestock=1 and (p.stock-used)>0) or (p.usestock=0) or ((p.usestock=1 and (p.stock-used)<=0) and (p.emptystockact <> 2)) ) ";
		$where[] = " p.published=1 ";
		$where[] = " p.hide_public=0 ";
		$where[] = " (p.publish_up <= ".$date_today.") and (p.publish_down = 0 OR p.publish_down >= ".$date_today.") ";
		/*if (!$my->id)
		{
			$where[] = " p.access=0 ";
		}
		else
		{
			$where[] = " (p.access=0 OR p.access=)";
		}*/

		$configs =  $this->getInstance("digistoreConfig", "digistoreModel");
		$configs = $configs->getConfigs();
		$showfeatured_prod = $configs->showfeatured_prod;

		$sql = "select id
				from #__digistore_products p
				where p.id in (select productid from #__digistore_product_categories
				where catid='".intval($catid)."')".
				(count($where) > 0 ? " and ": "") .
				implode (" and ", $where);

		$this->_total = $this->_getListCount($sql);
		$totalprods = $this->_total;
		$orderby = JRequest::getVar('orderby', 'default' );

		switch ( $orderby ) {
			case 'sku' :
				$order_field = " sku asc ";
				break;

			case 'latest' :
				$order_field = " id desc ";
				break;

			case 'name' :
				$order_field = " name asc ";
				break;

			case 'default' :
			default:
				$order_field = " ordering asc ";
				break;
		}
		$order = " order by " . $order_field;

		$option = JRequest::getVar("option", "");
		$site_config = JFactory::getConfig();
		$limit		= JRequest::getVar('limit', intval($site_config->get('list_limit')), '', 'int');
		$limitstart	= JRequest::getVar('limitstart', 0, '', 'int');
		$pids = array();
		if($configs->prodlayoutstyle == 1){
			$limit = $configs->prodlayoutrow*$configs->prodlayoutcol;
			$pids = $this->_getList($sql.$order , $limitstart, $limit);
		}
		else{
			$limit = $configs->prodlayoutrow;
			$pids = $this->_getList($sql.$order , $limitstart, $limit);
		}

		if(isset($pids) && count($pids) > 0){
			foreach ($pids as $pid) {
				$this->_products[$pid->id] = $this->getAttributes($pid->id);
			}
		}

		if (count($this->_products) > 0)
		foreach ($this->_products as $i => $v) {
			if ($v->usestock) {
				if ($v->used >= $v->stock) {

					if ($v->emptystockact == 1) {
					}
					elseif ($v->emptystockact == 2) {
						unset($this->_products[$i]);
					}
				}

			}

		}

		$this->_addProductTax();

		// move images --------------------------------------
		$db = JFactory::getDBO();
		if(isset($this->_products) && count($this->_products) > 0){
			foreach($this->_products as $key=>$product){
				$images = "";
				if(trim($product->images) != ""){
					$product->images = str_replace("/", DS, trim($product->images));
					$product->images = str_replace("\\", DS, trim($product->images));
					$source = JPATH_SITE.trim($product->images);
					$images = explode(DS, trim($product->images));
					$images = $images[count($images)-1];
					if(!is_dir(JPATH_SITE.DS."images".DS."stories".DS."digistore".DS."products")){
						JFolder::create(JPATH_SITE.DS."images".DS."stories".DS."digistore".DS."products");
					}
					@copy($source, JPATH_SITE.DS."images".DS."stories".DS."digistore".DS."products".DS.$images);
				}

				$product->prodimages = $images;
				if(trim($product->prodimages) != ""){//move images to digistore_products_images
					$all_images_string = trim($product->prodimages);
					$all_images_array = explode(",\\n", $all_images_string);
					$default_image = trim($product->defprodimage);
					if(isset($all_images_array) && count($all_images_array) > 0){
						$sql = "insert into #__digistore_products_images (`product_id`, `path`, `title`, `default`, `order`) values ";

						foreach($all_images_array as $key=>$value){
							$default = trim($value) == trim($default_image) ? "1" : "0";
							$sql .= "(".intval($product->id).", '".trim($value)."', '', ".$default.", ".($key+1)."), ";
						}
						$sql = substr($sql, 0, -2);
						$db->setQuery($sql);
						if($db->query()){
							$sql = "update #__digistore_products set `prodimages`='', `defprodimage`='', `images`='' where id=".intval($product->id);
							$db->setQuery($sql);
							$db->query();
						}
					}
				}
				$sql = "select `path`, `title` from #__digistore_products_images where `product_id`=".intval($product->id)." and `default`=1";
				$db->setQuery($sql);
				$db->query();
				$result = $db->loadAssocList();
				if(count($result) == 0){
					$sql = "select `path`, `title` from #__digistore_products_images where `product_id`=".intval($product->id)." order by id asc limit 1";
					$db->setQuery($sql);
					$db->query();
					$result = $db->loadAssocList();
				}
				$product->defprodimage = @$result["0"]["path"];
				$product->image_title = @$result["0"]["title"];
				if($key){
					$this->_products[$key] = $product;
				}
			}
		}
		// move images --------------------------------------
		// $pagination = new JPagination($totalprods,$limitstart,$limit);
			
		$return = array('items' => $this->_products,
						'total' => $totalprods,
						'limit' => $limit, 
						'limitstart' => $limitstart,
						// 'pagiantion' => $pagination
						);
		// echo $totalprods;
		// exit();
		return $return;
	}

	function getAttributes($pid) {
		$db = JFactory::getDBO();

		if ($pid < 1) return null;
		$this->_id = $pid;
		$this->_product = $this->getTable("digistoreProduct");
		$this->_product->load($this->_id);

		$sql = "select price from #__digistore_products_plans where `product_id`=".intval($this->_id)." and `default`=1";
		$db->setQuery($sql);
		$db->query();
		$price = $db->loadResult();
		if(trim($price) != ""){
			$this->_product->price = trim($price);
		}
		return $this->_product;
	}

	function _addProductTax() {
		$configs =  $this->getInstance("digistoreConfig", "digistoreModel");
		$configs = $configs->getConfigs();
		if (count($this->_products) > 0) {
			foreach ($this->_products as $i => $v) {
				$item = &$this->_products[$i];
				$item->quantity = 1;
					$price = $item->price;
					$item->percent_discount = "N/A";
				$item->currency = $configs->currency;

					$item->subtotal = $price * ($item->quantity);
					$item->price = digistoreHelper::format_price($item->price, $item->currency, false, $configs);//sprintf( $price_format, $item->product_price );
					$item->price_formated = digistoreHelper::format_price2($item->price, $item->currency, false, $configs);
					$item->subtotal = digistoreHelper::format_price($item->subtotal, $item->currency, false, $configs);//sprintf( $price_format, $item->subtotal );
					$item->subtotal_formated = digistoreHelper::format_price2($item->subtotal, $item->currency, false, $configs);
			}

		}
		$taxmodel = $this->getInstance("digistoreTax", "digistoreModel");

		$customer = new digistoreSessionHelper();
		$taxmodel->getTax($tax, $this->_products, $configs, $customer->_customer);

		if ($configs->product_price == 1) {
			if ($configs->tax_catalog == 0)
				if (isset($this->_products)) foreach ($this->_products as $i => $v)  $this->_products[$i]->price += $v->itemtax;
		} else 	if ($configs->product_price == 0) {
			if ($configs->tax_catalog == 1)
				foreach ($this->_products as $i => $v)  $this->_products[$i]->price -= $v->itemtax;
		}
	}

	function getCategoryName(){
		$db = JFactory::getDBO();
		$id = JRequest::getVar("cid", "0");
		if(is_array($id)){
			$id = $id["0"]; 
		}

		if(intval($id) == 0){//for producs menu item
			$itemid = JRequest::getVar("Itemid", "0");
			$sql = "select `params` from #__menu where id=".intval($itemid);
			$db->setQuery($sql);
			$db->query();
			$params = $db->loadResult();
			$params = json_decode($params);
			$id = intval($params->category_id);
		}

		$sql = "select name from #__digistore_categories where id=".intval($id);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadResult();
		return $result;
	}

};
?>
