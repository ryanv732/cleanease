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

class digistoreAdminModeldigistoreTaxcustomerclass extends JModelList {
	protected $_context = 'com_digistore.digistoreTaxcustomerclass';
	var $_pagination = null;
	var $_total = 0;
	var $_cclasses;
	var $_cclass;
	var $_id = null;

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

	function setId($id){
		$this->_id = $id;
		$this->_cclass = null;
	}

	protected function getListQuery(){
		$db = JFactory::getDBO();
		$session = JFactory::getSession();
		$c = $this->getInstance( "digistoreConfig", "digistoreAdminModel" );
		$configs = $c->getConfigs();
		$sql = "select * from #__digistore_tax_customerclass";
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

	/*function getlistCustomerClasses () {
		if (empty ($this->_cclasses)) {
			$sql = "select * from #__digistore_tax_customerclass";
			$this->_total = $this->_getListCount($sql);
			if ($this->getState('limitstart') > $this->_total) $this->setState('limitstart', 0);
			if ($this->getState('limitstart') > 0 & $this->getState('limit') == 0)  $this->setState('limitstart', 0);

			$this->_cclasses = $this->_getList($sql, $this->getState('limitstart'), $this->getState('limit'));

		}
		return $this->_cclasses;

	}*/

	function getcustomerClass() {
		if (empty ($this->_cclass)) {
			$this->_cclass = $this->getTable("digistoreTaxCustomerClass");
			$this->_cclass->load($this->_id);
		}
		return $this->_cclass;

	}

	function store () {
		$item = $this->getTable('digistoreTaxCustomerClass');
		$data = JRequest::get('post');
		$conf = $this->getInstance ("digistoreconfig", "digistoreAdminModel");
		$configs = $conf->getConfigs();

		if (!$item->bind($data)){
			$this->setError($item->getErrorMsg());
			return false;

		}

		if (!$item->check()) {
			$this->setError($item->getErrorMsg());
			return false;

		}

		if (!$item->store()) {
//			$this->setError($item->getErrorMsg());
			return false;

		}
		return true;

	}

	function delete () {
		$cids = JRequest::getVar('cid', array(0), 'post', 'array');
		$item = $this->getTable('digistoreTaxCustomerClass');
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
		$item = $this->getTable('digistoreTaxCustomerClass');
		$res = 0;
		if ($task == 'publish'){
			$res = 1;
			$sql = "update #__digistore_tax_customerclass set published='1' where id in ('".implode("','", $cids)."')";
		} else {
			$res = -1;
			$sql = "update #__digistore_tax_customerclass set published='0' where id in ('".implode("','", $cids)."')";

		}
		$db->setQuery($sql);
		if (!$db->query() ){
			$this->setError($db->getErrorMsg());
//		return false;
		}
		return $res;


	}

	function shiftorder($direction = 1) {
		$db = JFactory::getDBO();
		$cids = JRequest::getVar('cid', array(0), 'post', 'array');
		$order = JRequest::getVar('order', array(0), 'post', 'array');
		$sql = "update #__digistore_tax_customerclass set `ordering`=ordering".($direction == 1?"+1":"-1")." where id=".$cids[0];
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
			$sql = "update #__digistore_tax_customerclass set `ordering`='".$order[$i]."' where id=".$v;
			$db->setQuery($sql);
			$res = $db->query();
			if (!$res) return $res;
		}
		return true;
	}



};
?>