<?php
/**
* @package			DigiStore Joomla Extension
 * @author			foobla.com
 * @version			$Revision: 398 $
 * @lastmodified	$LastChangedDate: 2013-11-04 05:07:10 +0100 (Mon, 04 Nov 2013) $
 * @copyright		Copyright (C) 2013 foobla.com. All rights reserved.
* @license			GNU/GPLv3
*/

defined ('_JEXEC') or die ("Go away.");

jimport('joomla.application.component.modellist');
jimport('joomla.utilities.date');

class digistoreAdminModeldigistoreProduct extends JModelList {

	protected $_context = 'com_digistore.digistoreProduct';
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

	function getCategories() {
		$db = JFactory::getDBO();
		$sql = "SELECT id,name FROM #__digistore_categories";
		$db->setQuery($sql);
		return $db->loadObjectList();
	}

	protected function getListQuery($sort = "ordering") {
		$db = JFactory::getDBO();
		$session = JFactory::getSession();
		$category = $session->get('dsproducategory', 0, 'digistore');
		$prc = JRequest::getVar("prc", $category, "request");
		$search = trim(JRequest::getVar("search", '', "post"));
		$session_search = $session->get( 'digistore.product.search');
		$state_filter = JRequest::getVar("state_filter", '-1');

		$where = " 1=1 ";

		if($prc > 0){
			$where .= " and id IN (SELECT productid FROM #__digistore_product_categories WHERE catid='".$prc."' ) ";
		}
		if($state_filter != "-1"){
			$where .= " and published=".$state_filter;
		}
		$session->set( 'digistore.product.search', $search );
		if (!empty($search)) {
			$where .= " AND (name LIKE '%".$search."%') ";
		}
		elseif (isset($_POST['submit_search'])) {
			$session->set('digistore.product.search', '');
		}
		elseif ($session_search) {
			$where .= " AND (name LIKE '%".$session_search."%') ";
		}

		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__digistore_products');
		$query->where($where);
		$query->order($sort." asc");
		return $query;
	}

	function getItems()
	{
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

		foreach($result as $i => $v)
		{
			$sql = "SELECT id,name
					FROM #__digistore_categories
					WHERE id in "." (SELECT catid FROM #__digistore_product_categories WHERE productid='".$v->id."')";
			$db->setQuery($sql);
			$result[$i]->cats = $db->loadObjectList();
		}
		return $result;
	}

	/*function getListProducts ($sort = "ordering") {
		$session = JFactory::getSession();

		if (empty ($this->_products)) {
			$db = JFactory::getDBO();
			$prc = JRequest::getVar("prc", 0, "request");
			$search = trim(JRequest::getVar("search", '', "post"));
			$session_search = $session->get( 'digistore.product.search');
			$state_filter = JRequest::getVar("state_filter", '-1');

			$where = "WHERE 1=1 ";

			if($prc > 0){
				$where .= " and id IN (SELECT productid FROM #__digistore_product_categories WHERE catid='".$prc."' ) ";
			}
			if($state_filter != "-1"){
				$where .= " and published=".$state_filter;
			}
			$session->set( 'digistore.product.search', $search );
			if (!empty($search)) {
				$where .= " AND (name LIKE '%".$search."%') ";
			}
			elseif (isset($_POST['submit_search'])) {
				$session->set('digistore.product.search', '');
			}
			elseif ($session_search) {
				$where .= " AND (name LIKE '%".$session_search."%') ";
			}

			$sql = "SELECT * FROM #__digistore_products ".$where." ORDER BY ".$sort." ASC";
			$this->_total = $this->_getListCount($sql);

			if ($this->getState('limitstart') > $this->_total) $this->setState('limitstart', 0);
			if ($this->getState('limitstart') > 0 & $this->getState('limit') == 0)  $this->setState('limitstart', 0);
			if ($this->controller == "digistoreProducts")
				$this->_products = $this->_getList($sql, $this->getState('limitstart'), $this->getState('limit'));
			else
				$this->_products = $this->_getList($sql);

			foreach ($this->_products as $i => $v) {
				$sql = "SELECT id,name FROM #__digistore_categories WHERE id in "
						." (SELECT catid FROM #__digistore_product_categories WHERE productid='".$v->id."') "
				;

				$db->setQuery($sql);
				$this->_products[$i]->cats = $db->loadObjectList();
			}

		}
		return $this->_products;
	}*/

	function getProduct($pid = 0) {
		if ($pid > 0 ) {
			$this->_id = $pid;
		}
		if (empty($this->_product)) {
			$this->_product = $this->getTable("digistoreProduct");
			$this->_product->load($this->_id);
		}
		$db = JFactory::getDBO();
		if(trim($this->_product->prodimages) != ""){//move images to digistore_products_images
			$all_images_string = trim($this->_product->prodimages);
			$all_images_array = explode(",\\n", $all_images_string);
			$default_image = trim($this->_product->defprodimage);
			if(isset($all_images_array) && count($all_images_array) > 0){
				$sql = "INSERT INTO #__digistore_products_images (`product_id`, `path`, `title`, `default`, `order`) VALUES ";
				foreach($all_images_array as $key=>$value){
					$default = trim($value) == trim($default_image) ? "1" : "0";
					$sql .= "(".intval($this->_product->id).", '".trim($value)."', '', ".$default.", ".($key+1)."), ";
				}
				$sql = substr($sql, 0, -2);
				$db->setQuery($sql);
				if($db->query()){
					$sql = "UPDATE #__digistore_products SET `prodimages`='', `defprodimage`='' WHERE id=".intval($this->_product->id);
					$db->setQuery($sql);
					$db->query();
				}
			}
		}

		$sql = "SELECT * FROM #__digistore_products_images WHERE `product_id`=".intval($this->_product->id)." ORDER BY `order` ASC";
		$db->setQuery($sql);
		$db->query();
		$this->_product->prodimages = $db->loadAssocList();

		if(trim($this->_product->mailchimpapi) == ""){
			$sql = "SELECT `mailchimpapi` FROM #__digistore_settings";
			$db->setQuery($sql);
			$db->query();
			$this->_product->mailchimpapi = trim($db->loadResult());
		}

		if(trim($this->_product->mailchimplist) == ""){
			$sql = "SELECT `mailchimplist` FROM #__digistore_settings";
			$db->setQuery($sql);
			$db->query();
			$this->_product->mailchimplist = trim($db->loadResult());
		}

		return $this->_product;
	}

	function getFeatured() {
		$db = JFactory::getDBO();
		$sql = "SELECT p.* FROM #__digistore_products p WHERE p.featured = 1";
		$db->setQuery($sql);
		$featured_products = $db->loadObjectList();
		return $featured_products;
	}


	function getFeatured2 ( $product_id ) {
		if(!$product_id) return;
		$db = JFactory::getDBO();
		$sql = "select f.featuredid as id, p.name as name, f.planid from #__digistore_featuredproducts f, #__digistore_products p where f.featuredid=p.id and f.productid=".$product_id;
		$db->setQuery($sql);
		$featured_list = $db->loadObjectList();
		return $featured_list;
	}


	function _storeFile($file, $pid){
		jimport('joomla.filesystem.folder');
		$maindir = JPATH_ROOT.DS."administrator".DS."components".DS."digistore_product_uploads".DS;
		$tmpdir = $maindir."tmp".DS.$pid.DS;
		$resdir = $maindir.$pid.DS;

		digistoreAdminHelper::CreateIndexFile($maindir);
		digistoreAdminHelper::CreateIndexFile($resdir);
		$resdir_orig = $resdir."original".DS;
		digistoreAdminHelper::CreateIndexFile($resdir_orig);
		$resdir_encoded = $resdir."encoded".DS;
		digistoreAdminHelper::CreateIndexFile($resdir_encoded);

		$filename = $this->_getFilename ($file);
		$delete_file = JRequest::getVar('delete_file', 0, 'post');
		if($delete_file){
			if(file_exists($resdir)){
				JFolder::delete ($resdir);
			}

			if(file_exists($tmpdir)){
				JFolder::delete ($tmpdir);
			}

			if(file_exists($tmpdir)){
				JFolder::delete ($tmpdir);
			}
			JFolder::create($tmpdir);
			JFolder::create($tmpdir.DS."1".DS);
		}
		if(!file_exists($resdir_orig)){
			JFolder::create($resdir_orig);
		}

		if(!file_exists($file)){
			return true;
		}

		if(file_exists($file) && !JFile::copy ($file, $resdir_orig.$filename)){
			return false;
		}

		return true;
	}


	function _getFile ($pid = 0) {
		
		jimport('joomla.filesystem.file');
		$fullpath = '';

		$file = JRequest::getVar("ftpfile", '', "post");
		$c = $this->getInstance("digistoreConfig", "digistoreAdminModel");
		$configs = $c->getConfigs();

		if( trim( strlen($file) ) > 0 ) {
			$fullpath = JPATH_SITE.DS.$configs->ftp_source_path.DS.$file;
		} else {
			$file = JRequest::getVar("file", array(), "files", "array");
			$fullpath = JPATH_ROOT.DS."tmp".DS.$file['name'];

			if(file_exists($file['tmp_name']) && JFile::copy($file['tmp_name'], $fullpath)){

			}
			elseif($pid > 0){
				$db = JFactory::getDBO();
				$sql = "select file from #__digistore_products where id='".$pid."'";
				$db->setQuery($sql);
				$file = $db->loadResult();
				$fullpath = JPATH_ROOT.DS."administrator".DS."components".DS."digistore_product_uploads".DS.$pid.DS.$file;
			}
			else{
				$fullpath = '';
			}
		}
		return $fullpath;
	}

	function _getFilename ($file) {
		$filename = explode(DS, $file);
		$filename = $filename[count($filename) - 1];
		return $filename;

	}

	function getImagesIds($product_id){
		$db = JFactory::getDBO();
		$sql = "select id from #__digistore_products_images where product_id=".intval($product_id);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadColumn();
		return $result;
	}

	function existImage($path){
		$db = JFactory::getDBO();
		$sql = "select count(*) from #__digistore_products_images where `path`='".trim($path)."'";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadResult();
		if($result > 0){
			return true;
		}
		return false;
	}

	function store(){
		$task = JRequest::getVar('task');
		$item = $this->getTable('digistoreProduct');
		$data = JRequest::get('post');
		$return = array();
		$db = JFactory::getDBO();
		$jconf = JFactory::getConfig();
		$dbprefix = $jconf->get('dbprefix');//$jconf->_registry['config']['data']->dbprefix;
		//file processing
		$data = JRequest::get('post');
		$conf = $this->getInstance ("digistoreconfig", "digistoreAdminModel");
		$configs = $conf->getConfigs();
		//$data["publish_up"] = digistoreAdminHelper::parseDate ($configs->time_format, $data['publish_up']);
		//$data["publish_down"] = digistoreAdminHelper::parseDate ($configs->time_format, $data['publish_down']);

		$data["publish_up"] = strtotime($data["publish_up"]);
		$data["publish_down"] = $data["publish_down"] == "Never" ? "0" : strtotime($data["publish_down"]);

		if(!isset($data["offerplans"])){
			$data["offerplans"] = 0;
		}

		if(!$item->bind($data)){
		   	$this->setError($item->getErrorMsg());
			$return["return"] = false;
			$return["id"] = "";
			return $return;
		}

		if (!$item->check()) {
			$this->setError($item->getErrorMsg());
			$return["return"] = false;
			$return["id"] = "";
			return $return;
		}
		if (!$item->store()){
			return false;
		}

		if(!$item->id) {
			$query = "show table status";
			$db->setQuery($query);
			$suggested_id = $db->loadObjectList();
			foreach ($suggested_id as $res) {
				if ($res->Name == $dbprefix.'digistore_products') {
					$pid = $res->Auto_increment;
					break;
				}
			}
		}
		else {
			$pid = $item->id;
		}

		// images store -------------------------------------------------------------------------
		$new_images_ids = $data["images_ids"];
		$old_images_ids = $this->getImagesIds($pid);

		if(isset($new_images_ids) && isset($old_images_ids)){
			$diff = array_diff($old_images_ids, $new_images_ids);
			if(isset($diff) && count($diff) > 0){
				$sql = "delete from #__digistore_products_images where id in (".implode(",", $diff).")";
				$db->setQuery($sql);
				$db->query();
			}
		}
		elseif(!isset($new_images_ids) && isset($old_images_ids)){
			$new_images_ids = array("0"=>"0");
			$diff = array_diff($old_images_ids, $new_images_ids);
			if(isset($diff) && count($diff) > 0){
				$sql = "delete from #__digistore_products_images where id in (".implode(",", $diff).")";
				$db->setQuery($sql);
				$db->query();
			}
		}

		if(isset($data['prodimageshidden']) && !empty($data['prodimageshidden'])){
			foreach($data['prodimageshidden'] as $key=>$value){
				$sql = "";
				$default = $value == $data["default_image"] ? "1" : "0";
				if($this->existImage($value)){
					$sql = "update #__digistore_products_images set `title`='".addslashes(trim($data["title"][$key]))."', `default`=".$default.", `order`=".intval($data["order"][$key])." where `path`='".$value."' and `product_id`=".intval($pid);
				}
				else{
					$sql = "insert into #__digistore_products_images (`product_id`, `path`, `title`, `default`, `order`) values (".$pid.", '".trim($value)."', '".addslashes(trim($data["title"][$key]))."', ".$default.", ".intval($data["order"][$key]).")";
				}
				$db->setQuery($sql);
				$db->query();
			}
		}// end of images store -------------------------------------------------------------------------

		// start get upload file
		$plg_multifiles = JPluginHelper::getPlugin( 'digistore', 'multifiles' );
		if ( $item->domainrequired < 2 && ( !$plg_multifiles || !count($plg_multifiles) ) ) {
			$file = $this->_getFile($item->id);
			if (strlen ($file) < 1) {
				$return["return"] = false;
				$return["id"] = "";
				return $return;
			}

			if (!$this->_storeFile($file, $pid)){
				$return["return"] = false;
				$return["id"] = "";
				return $return;
			}

			$item->file = $this->_getFilename($file);
			if(trim($item->file) != ""){
				$sql = "update #__digistore_products set `file`='".trim($item->file)."' where id=".intval($item->id);
				$db->setQuery($sql);
				$db->query();
			}
		}
		
		if ( $item->domainrequired >= 2 ) {
			$item->file = '';
			$item->main_zip_file = '';
			$item->encoding_files = '';
			$item->passphrase = '';
		}

		$product_id = $item->id;

		/* Plans */
		$plain_model = $this->getInstance ("digistoreplain", "digistoreAdminModel");
		$plain_model->storeSelectedPlains($product_id, $data['plain'], $data['plain_amount'], $data['plain_default']);

		/* Renewals */
		$renewal_model = $this->getInstance ("digistoreplain", "digistoreAdminModel");
		$renewal_model->storeSelectedRenewals($product_id, $data['renewal'], $data['renewal_amount'], $data['renewal_default']);

		/* Emailreminders */
		if (isset($data['emailreminder'])) {
			$emailreminder_model = $this->getInstance ("digistoreemailreminder", "digistoreAdminModel");
			$emailreminder_model->storeSelectedEmailreminders($product_id, $data['emailreminder']);
		}

		/* Featured Products */
		$this->storeFeaturedProducts($product_id);

		/* */
		$table = $this->getTable();
		$table->reorder();
		/* */

		// Clear user groups
		$sql = "DELETE FROM `#__digistore_product_groups`
				WHERE `id_product`=" . $product_id;
		$db->setQuery($sql);
		$db->query();

		// Set user groups
		$groups = JRequest::getVar("groups", null, "", "array");
		for($i=0; $i<count($groups); $i++)
		{
			$sql = "INSERT INTO `#__digistore_product_groups`(`id_product`, `id_group`)
					VALUES(" . (int) $product_id . ", " . (int) $groups[$i] . ")";
			$db->setQuery($sql);
			$db->query();
		}

		// Clear expired user groups
		$sql = "DELETE FROM `#__digistore_product_groups_exp`
				WHERE `id_product`=" . $product_id;
		$db->setQuery($sql);
		$db->query();

		// Set user expire groups
		$expgroups = JRequest::getVar("expgroups", null, "", "array");
		for($i=0; $i<count($expgroups); $i++)
		{
			$sql = "INSERT INTO `#__digistore_product_groups_exp`(`id_product`, `id_group`)
					VALUES(" . (int) $product_id . ", " . (int) $expgroups[$i] . ")";
			$db->setQuery($sql);
			$db->query();
		}

		$return["return"] = true;
		$return["id"] = $product_id;
		// trigger plugin evens
		$jv = new JVersion();
		$isJ25 = $jv->RELEASE == '2.5';
		JPluginHelper::importPlugin('digistore');
		if($isJ25){
			$dispatcher = JDispatcher::getInstance();
		} else {
			$dispatcher	= JEventDispatcher::getInstance();
		}
		$dispatcher->trigger( 'onAfterDigiStoreProductSave', array( 'com_digistore.product_save' , $product_id, &$return ) );
		return $return;
	}

	function moveImageDown(){
		$db = JFactory::getDBO();
		$forchange = JRequest::getVar("forchange", "");
		$order = JRequest::getVar("order", array(), "array");
		$images_ids = JRequest::getVar("images_ids", array(), "array");
		$order1 = ""; //for find element;
		$order2 = ""; // for forchange element
		$find_id = "0";
		if(isset($images_ids) && count($images_ids) > 0){
			foreach($images_ids as $key=>$value){
				if($value == $forchange){
					$order1 = $order[$key];
					$order2 = $order[$key+1];
					$find_id = $images_ids[$key+1];
				}
			}
		}
		$sql = "update #__digistore_products_images set `order`=".intval($order2)." where id=".intval($forchange);
		$db->setQuery($sql);
		if($db->query()){
			$sql = "update #__digistore_products_images set `order`=".intval($order1)." where id=".intval($find_id);
			$db->setQuery($sql);
			$db->query();
		}
	}

	function moveImageUp(){
		$db = JFactory::getDBO();
		$forchange = JRequest::getVar("forchange", "");
		$order = JRequest::getVar("order", array(), "array");
		$images_ids = JRequest::getVar("images_ids", array(), "array");
		$order1 = ""; //for find element;
		$order2 = ""; // for forchange element
		$find_id = "0";
		if(isset($images_ids) && count($images_ids) > 0){
			foreach($images_ids as $key=>$value){
				if($value == $forchange){
					$order1 = $order[$key];
					$order2 = $order[$key-1];
					$find_id = $images_ids[$key-1];
				}
			}
		}
		$sql = "update #__digistore_products_images set `order`=".intval($order2)." where id=".intval($forchange);
		$db->setQuery($sql);
		if($db->query()){
			$sql = "update #__digistore_products_images set `order`=".intval($order1)." where id=".intval($find_id);
			$db->setQuery($sql);
			$db->query();
		}
	}

	function storeFeaturedProducts($product_id) {

		$db = JFactory::getDBO();

		$product_include_ids = JRequest::getVar('product_include_id',array(0));
		$plan_include_ids = JRequest::getVar('plan_include_id',array(0));

		// Clear previos
		$sql = "DELETE FROM #__digistore_featuredproducts WHERE productid = '".$product_id."';\n";
		$db->setQuery($sql);
		$db->query();
//		dsdebug($sql);

		// Store
		foreach($product_include_ids as $key => $product_include_id) {
			$sql = "INSERT INTO #__digistore_featuredproducts ( productid, featuredid, planid ) VALUES ('".$product_id."', '".$product_include_id."','".$plan_include_ids[$key]."'); \n";
			$db->setQuery($sql);
			$db->query();
//			dsdebug($sql);
		}

	}

	function delete () {
		$cids = JRequest::getVar('cid', array(0), 'post', 'array');
		$item = $this->getTable('digistoreProduct');
		foreach ($cids as $cid) {
			if(!$item->delete($cid)){
				$this->setError($item->getErrorMsg());
				return false;
			}
			else{
				$db = JFactory::getDBO();
				$sql = "DELETE FROM #__digistore_featuredproducts WHERE productid=".intval($cid);
				$db->setQuery($sql);
				$db->query();

				$sql = "DELETE FROM #__digistore_prodfields WHERE productid=".intval($cid);
				$db->setQuery($sql);
				$db->query();

				$sql = "DELETE FROM #__digistore_products_images WHERE product_id=".intval($cid);
				$db->setQuery($sql);
				$db->query();

				$sql = "DELETE FROM #__digistore_products_plans WHERE product_id=".intval($cid);
				$db->setQuery($sql);
				$db->query();

				$sql = "DELETE FROM #__digistore_products_renewals WHERE product_id=".intval($cid);
				$db->setQuery($sql);
				$db->query();

				$sql = "DELETE FROM #__digistore_product_categories WHERE productid=".intval($cid);
				$db->setQuery($sql);
				$db->query();
			}
		}

		return true;
	}


	function publish () {
		$db = JFactory::getDBO();
		$cids = JRequest::getVar('cid', array(0), 'post', 'array');
		$task = JRequest::getVar('task', '', 'post');
		$item = $this->getTable('digistoreProduct');
		$res = 0;
		if ($task == 'publish') {
			$res = 1;
			$sql = "update #__digistore_products set published='1' where id in ('".implode("','", $cids)."')";
		} else {
			$res = -1;
			$sql = "update #__digistore_products set published='0' where id in ('".implode("','", $cids)."')";

		}
//die ($sql);
		$db->setQuery($sql);
		if (!$db->query() ) {
			$this->setError($db->getErrorMsg());
//			return false;
		}
		return $res;
	}

	function shiftorder($direction = 1) {
		$db = JFactory::getDBO();
		$cids = JRequest::getVar('cid', array(0), 'post', 'array');
		$order = JRequest::getVar('order', array(0), 'post', 'array');
		$sql = "update #__digistore_products set `ordering`=ordering".($direction == 1?"+1":"-1")." where id=".$cids[0];
		$db->setQuery($sql);
		$res = $db->query();
		if (!$res) return $res;
		return true;
	}

	function reorder () {
		$db = JFactory::getDBO();
		$cids = JRequest::getVar('cid', array(0), 'post', 'array');
		$order = JRequest::getVar('order', array(0), 'post', 'array');
		foreach ($cids as $i => $v) {
			$sql = "update #__digistore_products set `ordering`='".$order[$i]."' where id=".$v;
			$db->setQuery($sql);
			$res = $db->query();
			if (!$res) return $res;
		}
		/* */
		$table = $this->getTable();
		$table->reorder();
		/* */
		return true;
	}

/* NEW function */

	function orderField( $uid, $inc )
	{
		// Initialize variables
		//$db		= JFactory::getDBO();
		$row	= $this->getTable();
		$row->load( $uid );
		$row->move( $inc ); // , '`group` = '.$db->Quote($row->group)
		$row->reorder();

		return true;
	}


	function saveorder() {

		// Initialize variables
		$db			= JFactory::getDBO();

		$cid		= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$order		= JRequest::getVar( 'order', array (0), 'post', 'array' );
		$total		= count($cid);
		$conditions	= array();


		JArrayHelper::toInteger($cid, array(0));
		JArrayHelper::toInteger($order, array(0));

		$row = $this->getTable();
		// update ordering values
		for( $i=0; $i < $total; $i++ ) {
			$row->load( (int) $cid[$i] );
			// track sections
			//$groupings[] = $row->group;
			if ($row->ordering != $order[$i]) {
				$row->ordering = $order[$i];
				if (!$row->store()) {
					JError::raiseError(500, $db->getErrorMsg());
				}
			}
		}

		$row->reorder();
		/*
		// execute updateOrder for each parent group
		$groupings = array_unique( $groupings );
		foreach ($groupings as $group){
		//	echo 'group = '.$db->Quote($group)."<br/>";
			$row->reorder('`group` = '.$db->Quote($group));
		}
		*/
		$msg = JText::_('New ordering saved');
	}

/* /END NEW function */

	function getlistProductTaxClasses () {
		$sql = "select * from #__digistore_tax_productclass order by ordering asc";
		$db = JFactory::getDBO();
		$db->setQuery($sql);
		return ($db->loadObjectList());
	}

	function getlistProductClasses () {
		$sql = "select * from #__digistore_productclass order by ordering asc";
		$db = JFactory::getDBO();
		$db->setQuery($sql);
		return ($db->loadObjectList());
	}

	function deleteImages(){
		$db = JFactory::getDBO();
		$task = JRequest::getVar("task", "");
		if($task == "delete_selected"){
			$selected_image = JRequest::getVar("selected_image", array(), "array");
			$product_id = JRequest::getVar("id", "0");
			if(isset($selected_image) && count($selected_image) > 0){
				foreach($selected_image as $key=>$path){
					$sql = "delete from #__digistore_products_images where `product_id`=".intval($product_id)." and `path`='".trim($path)."'";
					$db->setQuery($sql);
					$db->query();
				}
			}
		}
		elseif($task == "delete_all"){
			$product_id = JRequest::getVar("id", "0");
			$sql = "delete from #__digistore_products_images where `product_id`=".intval($product_id);
			$db->setQuery($sql);
			$db->query();
		}
		return true;
	}

	function copyProduct($ids=array()){
		if(!$ids||!count($ids)){
			$ids = JRequest::getVar("cid", array(), "array");
		}
		if(isset($ids) && count($ids) > 0){
			$db = JFactory::getDBO();
			foreach($ids as $key_id=>$id){
				$new_id = "";
				$sql = "SELECT * FROM #__digistore_prodfields WHERE `productid`=".intval($id);
				$db->setQuery($sql);
				$db->query();
				$result = $db->loadAssocList();
				if(isset($result)){
					$sql = "SELECT MAX(fieldid) FROM #__digistore_prodfields";
					$db->setQuery($sql);
					$db->query();
					$max = $db->loadResult();

					$sql = "INSERT INTO #__digistore_prodfields (`fieldid`, `productid`, `publishing`, `mandatory`) VALUES (".intval($max+1).", ".intval($id).", ".intval($result["0"]["publishing"]).", ".intval($result["0"]["mandatory"]).")";
					$db->setQuery($sql);
					if(!$db->query()){
						return false;
					}
				}

				$sql = "select * from #__digistore_products where id=".intval($id);
				$db->setQuery($sql);
				$db->query();
				$product = $db->loadAssocList();
				if(isset($product)){
					$sql = "INSERT INTO `#__digistore_products` (`name`, `images`, `discount`, `ordering`, `file`, `description`, `publish_up`, `publish_down`, `checked_out`, `checked_out_time`, `published`, `passphrase`, `main_zip_file`, `encoding_files`, `domainrequired`, `articlelink`, `articlelinkid`, `articlelinkuse`, `shippingtype`, `shippingvalue0`, `shippingvalue1`, `shippingvalue2`, `productemailsubject`, `productemail`, `sendmail`, `popupwidth`, `popupheight`, `stock`, `used`, `usestock`, `emptystockact`, `showstockleft`, `fulldescription`, `metatitle`, `metakeywords`, `metadescription`, `access`, `prodtypeforplugin`, `taxclass`, `class`, `sku`, `showqtydropdown`, `priceformat`, `featured`, `prodimages`, `defprodimage`, `mailchimplistid`, `subtitle`, `mailchimpapi`, `mailchimplist`, `mailchimpregister`, `mailchimpgroupid`, `video_url`, `video_width`, `video_height`) VALUES 
					('Copy ".addslashes(trim($product["0"]["name"]))."', '".trim($product["0"]["images"])."', ".$product["0"]["discount"].", ".$product["0"]["ordering"].", '".$product["0"]["file"]."', '".addslashes(trim($product["0"]["description"]))."', ".$product["0"]["publish_up"].", ".$product["0"]["publish_down"].", ".$product["0"]["checked_out"].", '".$product["0"]["checked_out_time"]."', 0, '".$product["0"]["passphrase"]."', '".$product["0"]["main_zip_file"]."', '".$product["0"]["encoding_files"]."', ".$product["0"]["domainrequired"].", '".$product["0"]["articlelink"]."', ".$product["0"]["articlelinkid"].", ".$product["0"]["articlelinkuse"].", ".$product["0"]["shippingtype"].", ".$product["0"]["shippingvalue0"].", ".$product["0"]["shippingvalue1"].", ".$product["0"]["shippingvalue2"].", '".addslashes(trim($product["0"]["productemailsubject"]))."', '".addslashes($product["0"]["productemail"])."', ".$product["0"]["sendmail"].", ".$product["0"]["popupwidth"].", ".$product["0"]["popupheight"].", ".$product["0"]["stock"].", ".$product["0"]["used"].", ".$product["0"]["usestock"].", ".$product["0"]["emptystockact"].", ".$product["0"]["showstockleft"].", '".addslashes(trim($product["0"]["fulldescription"]))."', '".addslashes(trim($product["0"]["metatitle"]))."', '".addslashes(trim($product["0"]["metakeywords"]))."', '".addslashes(trim($product["0"]["metadescription"]))."', ".$product["0"]["access"].", '".$product["0"]["prodtypeforplugin"]."', ".$product["0"]["taxclass"].", ".$product["0"]["class"].", '".$product["0"]["sku"]."', ".$product["0"]["showqtydropdown"].", ".$product["0"]["priceformat"].", ".$product["0"]["featured"].", '".$product["0"]["prodimages"]."', '".$product["0"]["defprodimage"]."', '".$product["0"]["mailchimplistid"]."', '".addslashes(trim($product["0"]["subtitle"]))."', '".$product["0"]["mailchimpapi"]."', '".$product["0"]["mailchimplist"]."', ".$product["0"]["mailchimpregister"].", '".$product["0"]["mailchimpgroupid"]."', '".$product["0"]["video_url"]."', ".$product["0"]["video_width"].", ".$product["0"]["video_height"].")";
					$db->setQuery($sql);
					if(!$db->query()){
						return false;
					}
					else{
						$sql = "select max(id) from #__digistore_products";
						$db->setQuery($sql);
						$db->query();
						$new_id = $db->loadResult();
					}
				}

				$sql = "select * from #__digistore_products_emailreminders where product_id=".intval($id);
				$db->setQuery($sql);
				$db->query();
				$emails = $db->loadAssocList();
				if(isset($emails) && count($emails) > 0){
					foreach($emails as $key=>$value){
						$sql = "insert into #__digistore_products_emailreminders (`product_id`, `emailreminder_id`, `send`) values (".$new_id.", ".$value["emailreminder_id"].", ".$value["send"].")";
						$db->setQuery($sql);
						if(!$db->query()){
							return false;
						}
					}
				}

				$sql = "select * from #__digistore_products_images where product_id=".intval($id);
				$db->setQuery($sql);
				$db->query();
				$images = $db->loadAssocList();
				if(isset($images) && count($images) > 0){
					foreach($images as $key=>$value){
						$sql = "insert into #__digistore_products_images (`product_id`, `path`, `title`, `default`, `order`) values (".intval($new_id).", '".$value["path"]."', '".$value["title"]."', ".$value["default"].", ".$value["order"].")";
						$db->setQuery($sql);
						if(!$db->query()){
							return false;
						}
					}
				}

				$sql = "select * from #__digistore_products_plans where product_id=".intval($id);
				$db->setQuery($sql);
				$db->query();
				$plains = $db->loadAssocList();
				if(isset($plains) && count($plains) > 0){
					foreach($plains as $key=>$value){
						$sql = "insert into #__digistore_products_plans (`product_id`, `plan_id`, `price`, `default`) values (".intval($new_id).", ".$value["plan_id"].", ".$value["price"].", ".$value["default"].")";
						$db->setQuery($sql);
						if(!$db->query()){
							return false;
						}
					}
				}

				$sql = "select * from #__digistore_products_renewals where product_id=".intval($id);
				$db->setQuery($sql);
				$db->query();
				$renewals = $db->loadAssocList();
				if(isset($renewals) && count($renewals) > 0){
					foreach($renewals as $key=>$value){
						$sql = "insert into #__digistore_products_renewals (`product_id`, `plan_id`, `price`, `default`) values (".intval($new_id).", ".$value["plan_id"].", ".$value["price"].", ".$value["default"].")";
						$db->setQuery($sql);
						if(!$db->query()){
							return false;
						}
					}
				}

				$sql = "select * from #__digistore_product_categories where productid=".intval($id);
				$db->setQuery($sql);
				$db->query();
				$categs = $db->loadAssocList();
				if(isset($categs) && count($categs) > 0){
					foreach($categs as $key=>$value){
						$sql = "insert into #__digistore_product_categories (`productid`, `catid`) values (".intval($new_id).", ".$value["catid"].")";
						$db->setQuery($sql);
						if(!$db->query()){
							return false;
						}
					}
				}

				$sql = "select * from #__digistore_featuredproducts where productid=".intval($id);
				$db->setQuery($sql);
				$db->query();
				$featured = $db->loadAssocList();
				if(isset($featured) && count($featured) > 0){
					foreach($featured as $key=>$value){
						$sql = "insert into #__digistore_featuredproducts (`productid`, `featuredid`, `planid`) values (".intval($new_id).", ".$value["featuredid"].", ".$value["planid"].")";
						$db->setQuery($sql);
						if(!$db->query()){
							return false;
						}
					}
				}

			}
		}
		return true;
	}

	function getlistCategories(){
		$db = JFactory::getDBO();
		$sql = "select * from #__digistore_categories order by parent_id, ordering asc";
		$db->setQuery($sql);
		$result = $db->loadObjectList();
		return $result;
	}

	function getlistPlains ($pack = false) {	 
		if (empty ($this->_plains)) {

			$c = $this->getInstance("digistoreConfig", "digistoreAdminModel");
			$configs = $c->getConfigs();

			$db = JFactory::getDBO();

			if ($pack) { 
				$sql = "SELECT p.* FROM #__digistore_plans p 
					WHERE p.duration_count = '-1' AND p.duration_type = '0'
					ORDER BY p.ordering ";
					//echo $sql; die();
			} else {
				$sql = "SELECT p.* FROM #__digistore_plans p "
					//." where u.id=o.userid and c.id=u.id "
					." ORDER BY p.ordering"
					;
					//echo $sql;
			}

			$this->_total = $this->_getListCount($sql);
			if ($this->getState('limitstart') > $this->_total) $this->setState('limitstart', 0);
			if ($this->getState('limitstart') > 0 & $this->getState('limit') == 0)  $this->setState('limitstart', 0);

			$this->_plains = $this->_getList($sql, $this->getState('limitstart'), $this->getState('limit'));

		}

		return $this->_plains;
	}

};
?>