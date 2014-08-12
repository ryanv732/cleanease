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

jimport('joomla.application.component.modellist');
jimport('joomla.utilities.date');

class digistoreAdminModeldigistorePromo extends JModelList {

	protected $_context = 'com_digistore.digistorePromo';
	var $_valid_promos;
	var $_promos;
	var $_promo;
	var $_id = null;
	var $_pagination = null;

	function __construct () {
		parent::__construct();
		$cids = JRequest::getVar('cid', 0, '', 'array');
		$this->setId((int)$cids[0]);
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

	function setId($id) {
		$this->_id = $id;
		$this->_promo = null;
	}

	protected function getListQuery()
	{
		$promosearch = JRequest::getVar("promosearch", "");
		$condition = JRequest::getVar("condition", '1');
		$status = JRequest::getVar("status", '1');

		if(trim($promosearch) != "")
		{
			$where[] = " (title like '%".trim($promosearch)."%' or code like '%".trim($promosearch)."%') ";
		}

		if(trim($status) != "")
		{
			$where[] = "(published='$status')";
		}

		if($condition == 0)
		{
			$where[] = "(( `codestart` >= UNIX_TIMESTAMP() ) OR  ( `codeend` <= UNIX_TIMESTAMP() AND `codeend`<>0 ) OR ( `codestart` <= UNIX_TIMESTAMP() AND `codeend`<>0 ) OR ( `codelimit` = `used` AND `codelimit` > 0 ))";
		}
		elseif ($condition == 1)
		{
			$where[] = "((codestart<=UNIX_TIMESTAMP() AND codeend>=UNIX_TIMESTAMP()) OR (codestart<=UNIX_TIMESTAMP() AND codeend=0)) AND (codelimit=0 OR codelimit>used)";
		}

		$sql = "select *
				from #__digistore_promocodes " . (count($where) ? 'WHERE ' . implode(' AND ', $where) : '') . "
				order by id desc";

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
		return $result;
	}

	/*function getlistPromos () {
		if(empty ($this->_promos)){
			$promosearch = JRequest::getVar("promosearch", "");
			$and = "";
			if(trim($promosearch) != ""){
				$and .= " where (title like '%".trim($promosearch)."%' or code like '%".trim($promosearch)."%') ";
			}

			$sql = "select * from #__digistore_promocodes ".$and." order by id desc";
			$this->_total = $this->_getListCount($sql);
			if ($this->getState('limitstart') > $this->_total) $this->setState('limitstart', 0);
			if ($this->getState('limitstart') > 0 & $this->getState('limit') == 0)  $this->setState('limitstart', 0);
			$this->_promos = $this->_getList($sql, $this->getState('limitstart'), $this->getState('limit'));
		}
		return $this->_promos;
	}*/

	function getlistPromosValid () {
		if (empty ($this->_valid_promos)) {

			$sql = "select * from #__digistore_promocodes order by id desc";
			$this->_db->setQuery( $sql ); 
			$promos = $this->_db->loadObjectList();

			$nullDate = 0;

			$promos_valid = array();

			foreach($promos as $promo) {

				$published = $promo->published;
				$timestart = $promo->codestart;
				$timeend = $promo->codeend;
				$limit = $promo->codelimit;
				$used = $promo->used;
				$now = time();

				$promo_status = true;

				if ( $now <= $timestart && $published == "1") {
					$promo_status = true;
				} else if ($limit > 0 && $used >= $limit) {
					$promo_status = true;
				} else if ( ( $now <= $timeend || $timeend == $nullDate ) && $published == "1" ) {
					$promo_status = true;
				} else if ( $now > $timeend && $published == "1" && $timeend != $nullDate) {
					$promo_status = true;
				} elseif ( $published == "0" ) {
					$promo_status = false;
				} else {
					$promo_status = false;
				}

				if ($promo_status)
					$this->_valid_promos[] = $promo;
			}
		}

		return $this->_valid_promos;
	}

	function getPromo()
	{
		if (empty ($this->_promo))
		{
			$this->_promo = $this->getTable("digistorePromo");
			$this->_promo->load($this->_id);
		}

		return $this->_promo;
	}

	function getPromoOrders()
	{
		$db = JFactory::getDBO();

		if (($this->_promo))
		{
			// Get previous orders restrictions
			$sql = "SELECT p.`name`, o.`productid`
					FROM `#__digistore_promocodes_orders` AS o
						 INNER JOIN `#__digistore_products` AS p ON p.`id`=o.`productid`
					WHERE o.`promoid`=" . (int) $this->_id . "
					ORDER BY p.`name`";
			$db->setQuery($sql);
			$promo_orders = $db->loadObjectList();

			return $promo_orders;
		}
	}

	function getPromoProducts()
	{
		$db = JFactory::getDBO();

		if (($this->_promo))
		{
			// Get previous orders restrictions
			$sql = "SELECT p.`name`, o.`productid`
					FROM `#__digistore_promocodes_products` AS o
						 INNER JOIN `#__digistore_products` AS p ON p.`id`=o.`productid`
					WHERE o.`promoid`=" . (int) $this->_id . "
					ORDER BY p.`name`";
			$db->setQuery($sql);
			$promo_products = $db->loadObjectList();

			return $promo_products;
		}
	}

	function store()
	{
		$item = $this->getTable('digistorePromo');
		$data = JRequest::get('post');
		$conf = $this->getInstance ("digistoreconfig", "digistoreAdminModel");
		$configs = $conf->getConfigs();

		//$data["codestart"] = digistoreAdminHelper::parseDate($configs->time_format, $data['codestart']);
		//$data["codeend"] = digistoreAdminHelper::parseDate($configs->time_format, $data['codeend']);

		if (!isset($data['validfornew'])) {
			$data["validfornew"] = 0;
		}
		if (!isset($data['validforrenewal'])) {
			$data["validforrenewal"] = 0;
		}

		$data["codestart"] = strtotime($data["codestart"]);
		$data["codeend"] = strtotime($data["codeend"]);

		if(!$item->bind($data)){
			$this->setError($item->getErrorMsg());
			return false;
		}

		if (!$item->check()) {
			$this->setError($item->getErrorMsg());
			return false;
		}

		if (!$item->store()) {
			return false;
		}

		// Set products
		$item->storeProducts($item->id);

		// Set previous orders
		$item->storeOrders($item->id);

		return true;
	}

	function delete () {
		$cids = JRequest::getVar('cid', array(0), 'post', 'array');
		$item = $this->getTable('digistorePromo');
		foreach ($cids as $cid) {
			if (!$item->delete($cid)) {
				$this->setError($item->getErrorMsg());
				return false;
			}
		}
		return true;
	}


	function publish () {
		$db = JFactory::getDBO();
		$cids = JRequest::getVar('cid', array(0), 'post', 'array');
		$task = JRequest::getVar('task', '', 'post');
		$item = $this->getTable('digistorePromo');
		$res = 0;
		if ($task == 'publish'){
			$res = 1;
			$sql = "update #__digistore_promocodes set published='1' where id in ('".implode("','", $cids)."')";
		} else {
			$res = -1;
			$sql = "update #__digistore_promocodes set published='0' where id in ('".implode("','", $cids)."')";
		}
		$db->setQuery($sql);
		if (!$db->query() ){
			$this->setError($db->getErrorMsg());
		}
		return $res;
	}

}

?>