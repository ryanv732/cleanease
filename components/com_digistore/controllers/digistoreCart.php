<?php
/**
* @package			DigiStore Joomla Extension
 * @author			foobla.com
 * @version			$Revision: 419 $
 * @lastmodified	$LastChangedDate: 2013-11-16 10:52:05 +0100 (Sat, 16 Nov 2013) $
 * @copyright		Copyright (C) 2013 foobla.com. All rights reserved.
* @license


*/

defined ('_JEXEC') or die ("Go away.");

jimport('joomla.application.component.controller');

class digistoreControllerdigistoreCart extends digistoreController
{

	var $_model = null;
	var $_config = null;
	var $_product = null;
	var $_session = null;

	function __construct()
	{
		parent::__construct();
		$this->registerTask("", "showCart");
		$this->registerTask("view", "showCart");
		$this->registerTask("summary", "showSummary");
		$this->registerTask("validate_input", "validateInput");
		$this->registerTask("cancel", "cancel");
		$this->registerTask("payment", "payment");
		$this->registerTask("getcountries", "getCountries");

		$this->_model = $this->getModel("digistoreCart");
		$this->_config = $this->getModel("digistoreConfig");
		$this->_product = $this->getModel("digistoreProduct");
	}

	function addMulti()
	{
		$cid = JRequest::getVar('cid', array(0), 'request', 'array');
		$cid = intval($cid[0]); //product category id

		$layout = JRequest::getVar('layout', '');
		if (!empty($layout)) {
			$layout = "&layout=" . $layout;
		}

		$db = JFactory::getDBO();
		$configs = $this->_config->getConfigs();
		$where1 = array();

		$prodids = JRequest::getVar("prodid", array(), 'request');
		$qtys = JRequest::getVar("quantity", array(), 'request');
		$add = JRequest::getVar('addtocart', array(), 'request');

		foreach ($prodids as $i => $id) {

			$where1 = array();
			$where1[] = " f.published=1 ";
			$where1[] = " fp.productid=" . $id;
			$sql = "select f.name, f.options, f.id, fp.publishing, fp.mandatory from #__digistore_customfields f left join
					#__digistore_prodfields fp on (f.id=fp.fieldid)"
			. (count($where1) > 0 ? " where " . implode(" and ", $where1) : "");
			$db->setQuery($sql);
			$fields = $db->loadObjectList();

			if (count($fields))
				foreach ($fields as $field) {
					$value = JRequest::getVar("field" . $field->id . "prod" . $id, '', 'request');
					JRequest::setVar('field' . $field->id, $value);
				}

			$_REQUEST['pid'][0] = $id;
			JRequest::setVar("qty", $qtys[$i]);
			JRequest::setVar("pid", array($id));

			if (isset($add[$id]) && $add[$id] > 0) {

				$res = $this->_model->addToCart($this->_customer, $productname);
			}
		}


		global $Itemid;

		if ($res < 0) {
			$msg = JText::_("DSWRONGPRODID");
			$link = "index.php?option=com_digistore&controller=digistoreProducts&task=list&cid=" . $cid . $layout . "&Itemid=" . $Itemid;
			$this->setRedirect(JRoute::_($link, false), $msg);
		} elseif ($res == 0) {
			$msg = JText::_("DSERRORUPDCARD");
			$link = "index.php?option=com_digistore&controller=digistoreProducts&task=list&cid=" . $cid . $layout . "&Itemid=" . $Itemid;
			$this->setRedirect(JRoute::_($link, false), $msg);
		}

		if($configs->afteradditem == 0){// Take to cart
			$cart_itemid = digistoreHelper::getCartItemid();
			$this->setRedirect(JRoute::_("index.php?option=com_digistore&controller=digistoreCart&task=showCart&Itemid=".$cart_itemid, false));
		}
		elseif($configs->afteradditem == 1){//Stay on product list
			$msg = "";
			$items = $this->_model->getCartItems($this->_customer, $configs);
			if(count($items) > 0){
				$temp_msg = array();
				foreach($items as $key=>$value){
					if(isset($value->name) && trim($value->name) != ""){
						$temp_msg[] = $value->name;
					}
				}
				$products = implode(", ", $temp_msg);
				$msg = urlencode($products);
			}
			$this->setRedirect(JRoute::_("index.php?option=com_digistore&controller=digistoreProducts&task=list&cid=" . $cid . $layout . "&product_added=" . $msg . "&Itemid=".$Itemid, false), "");
		}
		elseif($configs->afteradditem == 2){//Show cart in pop up
			//redirect to cart
			$cart_itemid = digistoreHelper::getCartItemid();
			$this->setRedirect(JRoute::_("index.php?option=com_digistore&controller=digistoreCart&task=showCart&Itemid=".$cart_itemid, false));
		}
	}

	function add()
	{
		$db = JFactory::getDBO();
		$pid = JRequest::getVar('pid',0);
		$pid = is_array($pid)?$pid[0]:$pid;

		//check if this product is unpublished
		$sql = "select count(*)
				from #__digistore_products
				where `id`=".intval($pid)." and `published`=1 and `publish_up` <= ".time()." and (`publish_down` >= ".time()." OR `publish_down` = 0)";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadResult();
		if($result == 0)
		{// expired or not published
			$renew = JRequest::getVar("renew", "");
			$this->showCart();
			return true;
		}
		//check if this product is unpublished

		$cid = JRequest::getVar('cid', array(0), 'request', 'array');
		$cid = intval($cid[0]); //product category id

		$layout = JRequest::getVar('layout', '');
		if(!empty($layout)){
			$layout = "&layout=".$layout;
		}

		$productname = "";
		$res = $this->_model->addToCart($this->_customer, $productname);
		$configs = $this->_config->getConfigs();

		$from = JRequest::getVar("from", "");

		if($from == "ajax"){
			$renew = JRequest::getVar("renew", "");
			$this->showCart();
		}

		global $Itemid;
		$cart_itemid = digistoreHelper::getCartItemid();

		if(JRequest::getVar('status', '-1') == 'change'){
			$url = JRoute::_("index.php?option=com_digistore&controller=digistoreCart&task=showCart&Itemid=".$cart_itemid, false);
			$this->setRedirect($url);
		}
		else{
			if($res < 0) {
				$msg = JText::_("DSWRONGPRODID");
				$link = "index.php?option=com_digistore&controller=digistoreProducts&task=list&cid=" . $cid . $layout . "&Itemid=" . $Itemid;
				$this->setRedirect(JRoute::_($link, false), $msg);
			}
			elseif($res == 0){
				$msg = JText::_("DSERRORUPDCARD");
				$link = "index.php?option=com_digistore&controller=digistoreProducts&task=list&cid=" . $cid . $layout . "&Itemid=" . $Itemid;
				$this->setRedirect(JRoute::_($link, false), $msg);
			}

			$from_add_plugin = JRequest::getVar("from_add_plugin", "0");
			if($from_add_plugin == 1){
				$configs->afteradditem = 0;
			}

			$type_afteradd = $configs->afteradditem;
			$gotocart = JRequest::getVar("gotocart", "");
			if($gotocart != ""){
				$type_afteradd = 0;
			}

			if($type_afteradd == 0){// Take to cart
				$url = JRoute::_("index.php?option=com_digistore&controller=digistoreCart&task=showCart&Itemid=".$cart_itemid, false);
				$this->setRedirect($url);
			}
			elseif($type_afteradd == 1){//Stay on product list
				$msg = "";
				$items = $this->_model->getCartItems($this->_customer, $configs);
				if(count($items) > 0){
					$temp_msg = array();
					foreach($items as $key=>$value){
						if(isset($value->name) && trim($value->name) != ""){
							$temp_msg[] = $value->name;
						}
					}
					$products = implode(", ", $temp_msg);
					$msg = urlencode($products);
				}
				$link = JRoute::_("index.php?option=com_digistore&controller=digistoreProducts&task=list&cid=" . $cid . $layout . "&product_added=" . $msg . "&Itemid=" . $Itemid, false);
				$this->setRedirect($link, "");
			}
			elseif($type_afteradd == 2){//Show cart in pop up
				$task = JRequest::getVar("task", "", "get");
				$renewlicid = JRequest::getVar("renewlicid", "", "post");
				if($task == "add" && $from != "ajax"){
					$url = JRoute::_("index.php?option=com_digistore&controller=digistoreCart&task=showCart&Itemid=".$cart_itemid, false);
					$this->setRedirect($url);
				}
				if(trim($renewlicid) != ""){
					$url = JRoute::_("index.php?option=com_digistore&controller=digistoreCart&task=showCart&Itemid=".$cart_itemid, false);
					$this->setRedirect($url);
				}
			}
		}
	}

	function showCart()
	{
		$from = JRequest::getVar("from", "test");
		$view = $this->getView("digistoreCart", "html");
		$view->setModel($this->_model, true);
		$view->setModel($this->_config);
		$view->setModel($this->_product);
		$view->display();
	}

	function showSummary()
	{
		$view = $this->getView("digistoreCart", "html");
		$view->setModel($this->_model, true);
		$view->setModel($this->_config);
		$view->setModel($this->_product);
		$view->setLayout('summary');
		$view->display();
	}

	function updateCart()
	{
		$res = $this->_model->updateCart($this->_customer, $this->_config->getConfigs());

		$from = JRequest::getVar("from", "");
		if($from == "ajax")
		{
			$url = JRoute::_("index.php?option=com_digistore&controller=digistoreCart&task=showCart&from=ajax&tmpl=component", false);
			$this->setRedirect($url);
		}
		else
		{
			$rp = JRequest::getVar('returnpage', '', 'request');
			$Itemid = JRequest::getInt('Itemid', 0);

			$firstname = JRequest::getVar("firstname", "");
			$lastname = JRequest::getVar("lastname", "");
			$company = JRequest::getVar("company", "");
			$email = JRequest::getVar("email", "");
			$username = JRequest::getVar("username", "");
			$password = JRequest::getVar("password", "");
			$password_confirm = JRequest::getVar("password_confirm", "");
			$address = JRequest::getVar("address", "");
			$city = JRequest::getVar("city", "");
			$zipcode = JRequest::getVar("zipcode", "");
			$country = JRequest::getVar("country", "");
			$state = JRequest::getVar("state", "");
			$array = array("firstname"=>$firstname, "lastname"=>$lastname, "company"=>$company, "email"=>$email, "username"=>$username, "password"=>$password, "password_confirm"=>$password_confirm, "address"=>$address, "city"=>$city, "zipcode"=>$zipcode, "country"=>$country, "state"=>$state);
			$_SESSION["new_customer"] = $array;
			$agreeterms = JRequest::getVar("agreeterms", "");
			$processor = JRequest::getVar("processor", "");

			if(strlen($rp) < 1)
			{
				$cart_itemid = digistoreHelper::getCartItemid();
				$this->setRedirect(JRoute::_("index.php?option=com_digistore&controller=digistoreCart&task=showCart&Itemid=".$cart_itemid."&processor=".$processor."&agreeterms=".$agreeterms, false));
			}
			else
			{
				$_SESSION["processor"] = $processor;
				if($this->_model->existUser($username, $email)){
					$renew = JRequest::getVar("renew", "", "get");
					if(trim($renew) != ""){
						$renew = "&renew=".$renew;
					}
					$this->setRedirect(JRoute::_("index.php?option=com_digistore&view=digistorecart".$renew."&Itemid=".$Itemid."&processor=".$processor."&agreeterms=".$agreeterms, false, ($processor=='authorizenet' ? true : false)), JText::_("DIGI_ALREADY_JOOMLA_USER"), "notice");
					return true;
				}
				else{
					$this->setRedirect(JRoute::_("index.php?option=com_digistore&controller=digistoreCart&task=checkout&processor=".$processor."&agreeterms=".$agreeterms, false, ($processor=='authorizenet' ? true : false)));
					$configs = $this->_config->getConfigs();
					/*if($configs->takecheckout == 1){
						$this->showSummary();
					}
					else{
						$this->checkout();
					}*/
					//$this->checkout();
				}
			}
		}
	}

	function deleteFromCart()
	{
		$res = $this->_model->deleteFromCart($this->_customer, $this->_config->getConfigs());
		$itemid = digistoreHelper::getCartItemid();
		$from = JRequest::getVar("from", "");
		$agreeterms = JRequest::getVar("agreeterms", "");
		$processor = JRequest::getVar("processor", "");

		if($from == "ajax"){
			$this->showCart();
		}
		else{
			//$this->showCart();
			$this->setRedirect("index.php?option=com_digistore&controller=digistoreCart&task=showCart&Itemid=".$itemid."&processor=".$processor."&agreeterms=".$agreeterms);
		}
	}

	function getPageURL(){
		$pageURL = 'http';

		if($_SERVER["HTTPS"] == "on"){
			$pageURL .= "s";
		}
		$pageURL .= "://";
		if($_SERVER["SERVER_PORT"] != "80"){
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		}
		else{
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return $pageURL;
	}

	function getReqhttps($content)
	{
		$reqhttps = "1";
		if(trim($content) != ""){
			$by_n = explode("\n", $content);
			if(isset($by_n)){
				foreach($by_n as $key=>$value){
					$by_equal = explode("=", $value);
					if(is_array($by_equal) && count($by_equal) > 0){
						if($by_equal["0"] == "reqhttps"){
							$reqhttps = trim($by_equal["1"]);
						}
					}
				}
			}
		}
		return $reqhttps;
	}

	function checkout()
	{
		$Itemid = JRequest::getInt("Itemid", 0);
		$processor = JRequest::getVar("processor", "");
		$_Itemid = $Itemid;
		$user = JFactory::getUser();
		$cart = $this->_model;
		//$plugins_enabled = $cart->getPluginList();

		// Check Login
		if(!$user->id){
			$uri = JURI::getInstance();
			$return = base64_encode($uri->toString());
			$this->setRedirect('index.php?option=com_users&view=login&return='.$return);
// 			$this->setRedirect("index.php?option=com_digistore&controller=digistoreProfile&task=login_register&returnpage=login_register&Itemid=".$Itemid."&processor=".$processor);
			return true;
		}
		if($this->_customer->_user->id < 1){
			$uri = JURI::getInstance();
			$return = base64_encode($uri->toString());
			$this->setRedirect('index.php?option=com_users&view=profile&layout=edit&return='.$return);
// 			$this->setRedirect("index.php?option=com_digistore&controller=digistoreProfile&task=login_register&returnpage=login_register&Itemid=".$Itemid."&processor=".$processor);
			return true;
		}

		// Check Payment Plugin installed
		/*if (empty($plugins_enabled)) {
			$msg = JText::_('Payment plugins not installed');
			$this->setRedirect(JRoute::_("index.php?option=com_digistore&controller=digistoreCart"), $msg);
			return;
		}*/

		$customer = $this->_customer;
		$configs = $this->_config->getConfigs();

		$res = digistoreHelper::checkProfileCompletion($customer);

		if( $res < 1 ) {
			if($configs->askforship != 0 || $configs->askforbilling != 0)
			{
				$this->setRedirect("index.php?option=com_digistore&controller=digistoreProfile&task=edit&returnpage=checkout&Itemid=".$_Itemid."&processor=".$processor);
			}
			else 
			{
				$name = $this->_customer->_user->name;
				$name_array = explode(" ", $name);
				$first_name = "";
				$last_name = "";
				if(count($name_array) == 1){
					$first_name = $name;
					$last_name = $name;
				}
				else{
					$last_name = $name_array[count($name_array)-1];
					unset($name_array[count($name_array)-1]);
					$first_name = implode(" ", $name_array);
				}
				$db = JFactory::getDBO();

				$sql = "SELECT `firstname`, `lastname` FROM #__digistore_customers WHERE id=".intval($this->_customer->_user->id);
				$db->setQuery($sql);
				$db->query();
				$result = $db->loadObject();
				if(isset($result) && (trim($result->firstname) == "" || trim($result->lastname) == "")){
					$sql = "UPDATE #__digistore_customers set `firstname`='".addslashes(trim($first_name))."', `lastname`='".addslashes(trim($last_name))."' where id=".intval($this->_customer->_user->id);
				} elseif (!$result){
					$sql = "INSERT INTO #__digistore_customers(`id`, `firstname`, `lastname`) VALUES (".intval($this->_customer->_user->id).", '".addslashes(trim($first_name))."', '".addslashes(trim($last_name))."')";
				}

				$db->setQuery($sql);
				$db->query();
				$this->_customer = new digistoreSessionHelper();
				$customer = $this->_customer;
			}
		}

		$total = 0;
		$fromsum 	= JRequest::getVar('fromsum', '0');
		$items 		= $cart->getCartItems($customer, $configs);
		$tax 		= $cart->calc_price($items, $customer, $configs);
		$total 		= $tax['taxed'];
		$now 		= time();

		if( (double)$total == 0 ) {
			if(count($items) != "0"){
				$cart->addFreeProduct($items, $customer, $tax);
				$itemid = JRequest::getVar("Itemid", "0");
				$link = JRoute::_("index.php?option=com_digistore&view=digistorelicenses&Itemid=".$itemid);
				$this->setRedirect($link, JText::_("DSSUCCESSFULPAYMENT"));
			}
		}
		else 
		{
			$db = JFactory::getDBO();
			$profile = "";
			$sql = "update #__digistore_session set transaction_details='" . base64_encode(serialize($customer)) . "' where sid=" . $customer->_sid;
			$db->setQuery($sql);
			$db->query();

			$sql = "select processor from #__digistore_session where sid='".$this->_customer->_sid."'";
			$db->setQuery($sql);
			$prosessor = $db->loadResult();
			if(!isset($prosessor) || trim($prosessor) == ""){
				$prosessor = $_SESSION["processor"];
			}

			if($prosessor == "payauthorize"){
				$page_url = $this->getPageURL();
				$reqhttps = "1";

				if(is_file(JPATH_SITE.DS."plugins".DS."digistorepayment".DS."payauthorize".DS."install")){
					$content = JFile::read(JPATH_SITE.DS."plugins".DS."digistorepayment".DS."payauthorize".DS."install");
					$reqhttps = $this->getReqhttps($content);

					if($reqhttps == "1"){//https
						if(strpos($page_url, "https") === FALSE){
							$site = JURI::root();
							$site = str_replace("http", "https", $site);
							$page_url = $site."index.php?option=com_digistore&controller=digistoreCart&task=checkout";
							$app = JFactory::getApplication("site");
							$app->redirect(JRoute::_($page_url));
							//$this->setRedirect(JRoute::_($page_url));
						}
					}
				}
			}

			$dispatcher = JDispatcher::getInstance();
			$params['user_id'] = $this->_customer->_user->id;

			if(isset($this->_customer) && isset($this->_customer->_customer)){
				$this->_customer->_customer->id = $user->id;
				$params['customer'] = ($this->_customer->_customer);
				$user = JFactory::getUser();
				$params['customer']->email = $user->get('email');

			}

			$params['products'] = $items; // array of products
			$params['config'] = $this->_config->getConfigs();
			$params['processor'] = $prosessor;//JRequest::getVar('processor'); //'payauthorize';
			$gataways = JPluginHelper::getPlugin('digistorepayment', $params['processor']);

			if(is_array($gataways)){
				foreach($gataways as $gw) {
					if($gw->name == $prosessor) {
						$params['params'] = $gw->params;
						break;
					}
				}
			}
			else{
				$params['params'] = $gataways->params;
			}

			$params['order_id'] = $this->_customer->_sid;
			$params['sid'] = $this->_customer->_sid;
			$params['option'] = 'com_digistore';
			$params['controller'] = 'digistoreCart';
			$params['task'] = 'payment';
			$params['order_amount'] = $items[-2]['taxed'];
			$params['order_currency'] = $items[-2]['currency'];
			$params['Itemid'] = JRequest::getInt('Itemid');

			JPluginHelper::importPlugin('digistorepayment');

			if($configs->shopping_cart_style == "1"){
				JRequest::setVar("tmpl", "component");
			}

			//$result = $dispatcher->trigger('onSendPayment', array(& $params));
// 			echo '<pre>'.print_r( $this->_customer, true ).'</pre>';
// 			exit();
			$this->getHTML();
		}
	}

	function wait()
	{
		//$this->_model->proccessWait($this);
		/*$view = $this->getView("digistoreCart", "html");
		$view->setModel($this->_model, true);
		$view->setModel($this->_config);
		$view->setLayout('wait');
		$view->paymentwait();*/
		$this->checkout();
	}

	function payment()
	{
		if(JRequest::getVar('processor', '') == ''){
			return false;
		}
		$pay = JRequest::getVar("pay", "");

		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$user_id = $user->id;

		$_SESSION["creditCardNumber"] = JRequest::getVar("creditCardNumber", "");
		$_SESSION["expDateMonth"] = JRequest::getVar("expDateMonth", "");
		$_SESSION["expDateYear"] = JRequest::getVar("expDateYear", "");
		$_SESSION["cvv2Number"] = JRequest::getVar("cvv2Number", "");

		$processor = JRequest::getVar('processor', '');
		$hidden_form = JRequest::getVar("hidden_form", "");
		if($hidden_form == "" && $processor != "paypaypal" && $processor != "offline"){
			include_once(JPATH_SITE.DS."components".DS."com_digistore".DS."helpers".DS."helper.php");
			$form = digistoreHelper::getSubmitForm($this->_config->getConfigs(), 'payauthorize');
			$script = '<script type=\'text/javascript\'>var time=setTimeout("document.digiadminForm.submit()", 2000); </script>';
			$form = $form.$script;
			echo $form;
		}
		elseif($processor == "offline"){
			$configs = $this->_config->getConfigs();
			$customer = $this->_customer;
			$cart = $this->_model;
			$items = $cart->getCartItems($customer, $configs);
			$now = time();

			$order_id = $this->_model->addOrder($items, $customer, $now, $processor, "Pending");
			$this->_model->addLicenses($items, $order_id, $now, $customer, "Pending");
			$tax = $this->_model->calc_price( $items, $customer, $configs );
			$total = $tax['taxed'];
			$licenses = $tax['licenses'];
			$this->_model->dispatchMail( $order_id, $total, $licenses, $now, $items, $customer );
			$this->_model->emptyCart($order_id);

			$controller = "digistoreOrders";
			$task = "list";
			$mosConfig_live_site = digistoreHelper::getLiveSite();
			$success_url = $mosConfig_live_site . "/index.php?option=com_digistore&controller=" . $controller . "&task=" . $task . "&success=1&sid=" . $order_id;

			$msg = JText::_("DIGI_THANK_YOU_FOR_PAYMENT");
			$this->setRedirect($success_url, $msg);
		}
		else{
			$dispatcher = JDispatcher::getInstance();
			JPluginHelper::importPlugin('digistorepayment');
			$params = JPluginHelper::getPlugin('digistorepayment', JRequest::getVar('processor'))->params;

			$param = array_merge(JRequest::get('request'), array('params' => $params));
			$param['handle'] = &$this;

			$customer = $this->_customer;
			$configs = $this->_config->getConfigs();
			$cart = $this->_model;
			$items = $cart->getCartItems($customer, $configs);
			$products = array();
			if(isset($items) && count($items) > 0){
				foreach($items as $key=>$product){
					if(trim($product->name) != ""){
						$products[] = trim($product->name);
					}
				}
			}
			$param["cart_products"] = implode(" - ", $products);

			$results_plugins = $dispatcher->trigger('onReceivePayment', array(& $param));

			$result = array();
			foreach($results_plugins as $result_plugin){
				if(!empty($result_plugin)){
					$result = $result_plugin;
				}
			}

			if(empty($result['sid'])){
				$result['sid'] = -1;
			}

			if(empty($result['pay'])){
				$result['pay'] = 'fail';
			}

			/*
			 * pay states (wait, success_stop) or (success, fail)
			 */

			if(isset($result) && !empty($result)){
				// set sid if empty
				if((!isset($result['sid']) || empty($result['sid'])) && !empty($result['order_id'])){
					$result['sid'] = $result['order_id'];
				}

				if($processor != "paypaypal" && $processor != "offline"){
					if($result['pay'] == "success"){
						$result['pay'] = "ipn";
					}
				}

				switch($result['pay']){
					case 'success':
						//$this->_model->proccessSuccess($this, $result);
						$msg = JText::_("DIGI_THANK_YOU_FOR_PAYMENT");
						$return_url = $this->_model->getReturnUrl(true);
						$this->_model->emptyCart($result['sid']);
						$this->setRedirect($return_url, $msg);
						break;
					case 'ipn':
						$this->_model->proccessIPN($this, $result);
						break;
					case 'wait':
						$this->_model->proccessWait($this, $result);
						break;
					case 'fail':
						$this->_model->proccessFail($this, $result);
						break;
					default:
						break;
				}
			}
		}
	}

	function getCartItem() {

		$cid = JRequest::getVar('cid', -1);
		$plan_id = JRequest::getVar('plan_id', -1);
		$qty = JRequest::getVar('quantity'.$cid, 1);

		$db = JFactory::getDBO();

		if ( ($cid > 0) && ($plan_id > 0) ) { //  && ($qty > 0)

			$cart = $this->_model;
			$customer = $this->_customer;
			$configs = $this->_config->getConfigs();

			$sid = $this->_customer->_sid;
			$sql = "UPDATE #__digistore_cart SET plan_id = " . $plan_id . ", quantity = ".$qty." where cid=" . $cid; // sid = " . $sid . " and
			$db->setQuery( $sql );
			$db->query();

			//Update Attributes

			// get product id
			$sql = "select item_id from #__digistore_cart where cid = " . $cid . " and sid = " . $sid;
			$db->setQuery( $sql );
			$pid = (int)$db->loadResult();

			$where1 = array();
			$where1[] = " f.published=1 ";
			$where1[] = " fp.productid=" . $pid;
			$sql = "select f.name, f.options, f.id, fp.publishing, fp.mandatory from #__digistore_customfields f left join
				#__digistore_prodfields fp on (f.id=fp.fieldid)" . (count( $where1 ) > 0 ? " where " . implode( " and ", $where1 ) : "");
			$db->setQuery( $sql );
			$fields = $db->loadObjectList();

			foreach ( $fields as $field ) {
				//$value = JRequest::getVar( "attributes" . $field->id . "", '-1', 'request' );
				$value = $_REQUEST['attributes'][$cid][$field->id];
				$sql = "delete from #__digistore_cartfields where sid='" . $sid . "' and productid='" . $pid . "' and fieldid='" . $field->id . "' and cid='" . $cid . "'";
				$db->setQuery( $sql );
				$db->query(); //remove old one regardless of they existence
				$sql = "insert into #__digistore_cartfields(cid, sid, productid, fieldid, optionid) values ('" . $cid . "','" . $sid . "','" . $pid . "','" . $field->id . "', '" . $value . "')";
				$db->setQuery( $sql );
				$db->query(); //and create new corresponding to cid obtained during processing
			}
			// END Attributes

			$items = $cart->getCartItems($customer, $configs);
			$result = array();

			foreach($items as $key=>$item) {
				if ($key < 0)	continue;
				if ($item->cid == $cid)
				{
					$result['cid'] = $cid;
					$result['cart_item_qty'.$cid] = $item->quantity;
					$result['cart_item_price'.$cid] = digistoreHelper::format_price($item->price, $item->currency, true, $configs);
					$result['cart_item_discount'.$cid] = digistoreHelper::format_price($item->discount, $item->currency, true, $configs);
					$result['cart_item_total'.$cid] = digistoreHelper::format_price($item->subtotal-$item->discount, $item->currency, true, $configs);
				}
			}

			$total = digistoreHelper::format_price($items[-2]['taxed'], $items[-2]['currency'], true, $configs);
			$result['cart_total'] = $total;//"{$items[-2]['taxed']}";

			$cart = $this->_model;
			$cart_tax = $cart->calc_price($items, $customer, $configs);
			$result['cart_discount'] = digistoreHelper::format_price($cart_tax["promo"], $items[-2]['currency'], true, $configs);
			$result['cart_tax'] = digistoreHelper::format_price($cart_tax["value"], $items[-2]['currency'], true, $configs);
			echo json_encode($result);

		} else {

			echo json_encode(array());
		}

		exit;
	}

	function validateInput(){
		$value = JRequest::getVar("value", "");
		if(trim($value) != ""){
			$input = JRequest::getVar("input", "");
			$db = JFactory::getDBO();
			$sql = "select count(*) from #__users where `".$input."` = '".$value."'";
			$db->setQuery($sql);
			$db->query();
			$response = $db->loadResult();
			if($response > "0"){
				echo "1";
			}
			else{
				echo "0";
			}
		}
	}

	function getHTML()
	{
// 		echo '<script src="'.JURI::root().'components/com_digistore/assets/js/jquery.digistore.js"></script>';
// 		echo '<script src="'.JURI::root().'components/com_digistore/assets/js/jquery.noconflict.digistore.js"></script>';
// 		echo '<script src="'.JURI::root().'components/com_digistore/assets/js/bootstrap.min.js"></script>';

		$pg_plugin 	= JRequest::getVar("processor", "", "", "string");
		$Itemid 	= JRequest::getInt("Itemid", "0");
		$dispatcher = JDispatcher::getInstance();
		JPluginHelper::importPlugin( 'payment', $pg_plugin );
		$session 	= JFactory::getSession();
		$customer 	= $this->_customer;
		$configs 	= $this->_config->getConfigs();
		$cart 		= $this->_model;
		$items 		= $cart->getCartItems( $customer, $configs );

		if ($pg_plugin == 'paypal')
		{
			if($configs->shopping_cart_style) {
				echo '
					<div class="digistore" style="width: 70%;margin-left: auto;margin-right: auto;padding: 20px 0 0 0;background: #fff;">
						<div class="container-fluid">
				';
				echo '
					<a href="' . JURI::root() . '">
						<img src="' . JURI::root() . 'images/stories/digistore/store_logo/' . trim($configs->store_logo) . '" alt="store_logo" border="0">
					</a>
				';
			}
			echo '<h3 style="text-align:center;">' . JText::_("DSPAYMENT_WITH_PAYPAL") . '</h3>';
			echo '<div class="progress progress-striped active" style="width: 50%; margin: 20px 0 40px 260px; float: left;"><div  id="progressBar" class="bar" style="border-radius: 3px; margin: 0; width: 100%;"></div></div>';

			if( $configs->shopping_cart_style ) {
				echo '</div></div>';
			}
		} else {
			// echo '<h2>' . JText::_("PAYMENT_DETAILS_PAGE") . '</h2>';
		}

		/*
		 * $items
		 * [0]
		 *	->name
		 *	->discount
		 *	->quantity
		 *	->price
		 *	->promo
		 *	  amount = price - promo
		 */
		$vars = new stdClass();


		$vars->custom = $this->_customer->_customer->id;
		$vars->user_firstname = $this->_customer->_customer->firstname;
		$vars->user_lastname = $this->_customer->_customer->lastname;
		$vars->user_id = JFactory::getUser()->id;
		$vars->user_email = $this->_customer->_user->email;
		$vars->item_name = '';

		for($i=0; $i<count($items)-2; $i++)
		{
			$vars->item_name.= $items[$i]->name . ', ';
		}
		$vars->item_name = substr($vars->item_name, 0, strlen($vars->item_name)-2);

		// downloads page
		if ($configs->afterpurchase == 0)
		{
			$vars->return = JRoute::_(JURI::root()."index.php?option=com_digistore&view=digistorelicenses&ga=1&orderid=".$this->_customer->_customer->id, true, 0);
		}
		// orders page
		else
		{
			$vars->return = JRoute::_(JURI::root()."index.php?option=com_digistore&view=digistoreorders&ga=1&orderid=".$this->_customer->_customer->id, true, 0);
		}
		
		
		$vars->return = str_replace('https', 'http', $vars->return);
		$vars->cancel_return = JRoute::_(JURI::root()."index.php?option=com_digistore&Itemid=".$Itemid."&controller=digistoreCart&task=cancel&processor={$pg_plugin}", true, 0);
		$vars->url = $vars->notify_url = JRoute::_(JURI::root()."index.php?option=com_digistore&controller=digistoreCart&task=processPayment&processor={$pg_plugin}&order_id=".$this->_customer->_customer->id."&sid=".$this->_customer->_sid, true, false);
		$vars->currency_code = $items[-2]['currency'];
		$vars->amount = $items[-2]['taxed'];//+$items[-2]['shipping'];
		$html = $dispatcher->trigger('onTP_GetHTML', array($vars));
		if (!isset($html[0])) {
			$html[0] = '';
		}
		$html[0] = $html[0] . '<script type="text/javascript">';
		if ($pg_plugin == 'paypal')
		{
			$html[0] = $html[0] . 'jQuery(".akeeba-bootstrap").hide();';
		}
		$html[0] = $html[0] . 'jQuery(".akeeba-bootstrap form").submit();';
		$html[0] = $html[0] . '</script>';

		echo $html[0];
	}

	function processPayment()
	{
		$mainframe=JFactory::getApplication();
		$post 		= JRequest::get('post');
		$pg_plugin 	= JRequest::getCmd('processor');
		$model 		= $this->getModel('digistorecart');
		$order_id 	= JRequest::getVar('order_id');
		$sid 		= JRequest::getVar('sid');
		
		$this->_model->proccessSuccess($post, $pg_plugin, $order_id, $sid);
	}

	function cancel()
	{
		$mainframe = JFactory::getApplication();
		$Itemid = JRequest::getInt("Itemid", "0");
		$this->_model->emptyCart();
		$mainframe->redirect("index.php?option=com_digistore&controller=digistoreCategories&task=listCategories&Itemid=".$Itemid);
	}

	function getCountries()
	{
		$db = JFactory::getDBO();
		$country = JRequest::getVar('ct', '');
		$cardstate = JRequest::getVar('cardstate', '');
		$html = '';

		if ($country != '')
		{
			$sql = "SELECT DISTINCT(`state`)
					FROM `#__digistore_states`
					WHERE `country`=" . $db->quote($country) . "
					ORDER BY `state`";
			$db->setQuery($sql);
			$states = $db->loadObjectList();

			for ($i=0; $i<count($states); $i++)
			{
				$html.= '<option value="'.$states[$i]->state.'" '.($cardstate == $states[$i]->state ? 'selected' : '').'>'.$states[$i]->state.'</option>';
			}
		}

		echo $html;
	}

	function get_cart_content(){
		$database = $db = JFactory :: getDBO();
		//show the shopping cart
		// JPlugin::loadLanguage('mod_digistore_cart',JPATH_SITE);
		$lang 	= JFactory::getLanguage();
		// $lang->load('mod_digistore_cart', JPATH_SITE, null, false, false)
		$lang->load('mod_digistore_cart', JPATH_SITE);
		jimport('joomla.application.component.model');
		include_once JPATH_SITE.DS.'components'.DS.'com_digistore'.DS.'models'.DS.'digistorecart.php';
		include_once JPATH_SITE.DS.'components'.DS.'com_digistore'.DS.'models'.DS.'digistoretax.php';
		if(!class_exists("TabledigistoreConfig")){
			include_once(JPATH_SITE.DS.'components'.DS.'com_digistore'.DS.'tables'.DS.'digistoreconfig.php');
		}
		if(!class_exists("TabledigistorePromo")){
			include_once(JPATH_SITE.DS.'components'.DS.'com_digistore'.DS.'tables'.DS.'digistorepromo.php');
		}
	
		include_once(JPATH_SITE.DS.'components'.DS.'com_digistore'.DS.'helpers'.DS.'session.php');
		include_once(JPATH_SITE.DS.'components'.DS.'com_digistore'.DS.'helpers'.DS.'helper.php');
		$customer = new digistoreSessionHelper();
		$cart= $this->_model;
		$helper=new digistoreHelper();
		$configs= $this->_config->getConfigs();
	
	
		$price_format = '%'.$configs->totaldigits.'.'.$configs->decimaldigits.'f'; 
		#get params
		$sql = "SELECT `params` FROM `#__extensions` WHERE `type`='module' AND `element`='mod_digistore_cart' and `enabled`=1";
		$db->setQuery($sql);
		$params_text = $db->loadResult();
		$params = new JRegistry($params_text);
		$categ_digistore = $params->get( 'digistore_category', '' );
		
		if($categ_digistore != ''){
			$sql = "select id from #__digistore_categories where title like '".$categ_digistore."' or name like '".$categ_digistore."'";
			$database->setQuery($sql);
			$id = $database->loadResult();	
			$cat_url = (isset($configs->continue_shopping_url) && $configs->continue_shopping_url != '')?$configs->continue_shopping_url : "index.php?option=com_digistore&controller=digistoreProducts&task=list&cid[]=" . $id;
		}
		else{
			$cat_url = (isset($configs->continue_shopping_url) && $configs->continue_shopping_url != '')?$configs->continue_shopping_url : "index.php?option=com_digistore&controller=digistoreCategories&task=listCategories";		
		};
	
		$items = $cart->getCartItems ($customer, $configs);
		
		$cart_itemid = digistoreHelper::getCartItemid();
		$and_itemid = "";
		if($cart_itemid != "0"){
			$and_itemid = "&Itemid=".$cart_itemid;
		}
		
		if(count($items) > 0){
			$module_title = JText::_("_SHOPPING_CART");
			$total = 0;
			$number = 0;
			foreach($items as $key=>$item){
				if($key >= 0){
					$currency = $item->currency;
					if(!isset($item->discounted_price)){
						$total += $item->price * $item->quantity;
					}
					else{
						$total += $item->discounted_price * $item->quantity;
					}
					$number ++;
				}
			}
			
			?>
			<table>
				<tr>
					<td>
						<a href="<?php echo JRoute::_('index.php?option=com_digistore&controller=digistoreCart&task=showCart'.$and_itemid, false)?>">
							<img src="<?php echo JUri::root();?>modules/mod_digistore_cart/cart.gif" border="0">
						</a>
					</td>
					<td>
						&nbsp;&nbsp;&nbsp;
						<a href="<?php echo JRoute::_('index.php?option=com_digistore&controller=digistoreCart&task=showCart'.$and_itemid, false)?>">
						<?php
							if($number == 1){
								echo $number." ".JText::_("NR_ITEM");
							}
							else{
								echo $number." ".JText::_("NR_ITEMS");
							}
						?>
						</a>
					</td>
					<td>
						&nbsp;&nbsp;&nbsp;<?php echo digistoreHelper::format_price($total, $currency, true, $configs); ?>
					</td>
				</tr>
			</table>
		<?php 
		}
		else{
			$module_title = '_BUY_NOW';
			if($params->get('modbuynow', '') == '0'){
		?>
				<a href="<?php echo $cat_url; ?>" style="text-align:center; display:block;" class="btn btn-warning">
					<?php echo JText::_('CARTEMPTY');?>
				</a>
		<?php 
			} 
			elseif($params->get('modbuynow', '') == '1'){
		?>
				<table width="100%">
					<tr>
						<td width="100%" align="center">
							<?php echo JText::_('CARTEMPTY'); ?>
						</td>
					</tr>
				</table>
		<?php
			}
			else{
			}
		}
		exit();
	}
}
