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

class digistoreAdminModeldigistoreCustomer extends JModelList {

	protected $_context = 'com_digistore.digistoreCustomer';   
	var $_customers;
	var $_customer;
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

		$this->_customer = null;
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

	protected function getListQuery(){
		$db = JFactory::getDBO();
		$session = JFactory::getSession();
		$keyword = JRequest::getVar("keyword", "");

		$where = " 1=1 ";

		if(trim($keyword) != ""){
			$where .= " and (u.username like '%".$keyword."%' or c.firstname like '%".$keyword."%' or c.lastname like '%".$keyword."%' ) ";
		}

		$sql = "select c.*, u.username, u.email from #__digistore_customers c left join #__users u on( u.id=c.id) where ".$where." order by c.id desc";
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

	function getCustomer() {
		if (empty($this->_customer)) {
			$this->_customer = $this->getTable("digistoreCustomer");
			$this->_customer->load($this->_id);
		}
		return $this->_customer;
	}

	function getCustomerByID($id) {
		$customer = $this->getTable("digistoreCustomer");
		$customer->load($id);

		return $customer;
	}

	function getCustomerIDbyUserName($username) {
		$this->_db->setQuery("SELECT dc.id FROM #__digistore_customers dc inner join #__users u on(u.id = dc.id) WHERE u.username = '".$username."'");
		$id = $this->_db->loadResult();
		return $id;
	}

	function getUserByName($username) {
		$this->_db->setQuery("SELECT * FROM #__users u WHERE u.username = '".$username."'");
		$user = $this->_db->loadObject();
		return $user;
	}

	function getUserByID($id) {
		$this->_db->setQuery("SELECT * FROM #__users u WHERE u.id = '".$id."'");
		$user = $this->_db->loadObject();
		return $user;
	}

	function store (&$error){
		jimport("joomla.database.table.user");
		
		$db = JFactory::getDBO();
		$user = new JUser();
		$my = new stdClass;
		$item = $this->getTable('digistoreCustomer');
		
		$id = JRequest::getVar("id", "0");
		if($id != "0"){
			$data = JRequest::get('post');
			$data['password2'] = $data['password_confirm'];
			$data['name'] = $data['firstname'];
			$data['groups']= array(2);
			$data['block'] = 0;
			$user->bind($data);
			$user->gid = 18;
			$res = true;
			$my->id = $data['id'];

			if(!$my->id){
				if(!$user->save()){
					$error = $user->getError();
					$res = false;
				}
			}
			else{
				$user->id = $my->id;
			}
		}

		if(intval($id) == "0"){
			$sql = 'SELECT id FROM #__users ORDER BY id DESC LIMIT 1';
			$db->setQuery($sql);
			$data['id'] = intval($db->loadResult());
		}

		if (!$item->bind($data)) {
			$res = false;
		}

		if (!$item->check()) {
			$res = false;
		}

		if (!$item->store()) {
			$res = false;
		}

		$this->setId($item->id);
		$this->getCustomer();
		
		return $res;
	}

	function delete () {
		
		$cids = JRequest::getVar('cid', array(0), 'post', 'array');
		$item = $this->getTable('digistoreCustomer');
		foreach ($cids as $cid) {
			if (!$item->delete($cid)) {
				$this->setError($item->getErrorMsg());
				return false;

			}
		}
		
		jimport("joomla.database.table.user");
		$db = JFactory::getDBO();
		$user = new JUser();

		foreach ($cids as $cid) {
			if (!$user->delete($cid)) {
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
		$item = $this->getTable('digistoreCustomer');
		if ($task == 'publish') {
			$sql = "update #__digistore_categories set published='1' where id in ('".implode("','", $cids)."')";
		} else {
			$sql = "update #__digistore_categories set published='0' where id in ('".implode("','", $cids)."')";

		}
		$db->setQuery($sql);
		if (!$db->query() ) {
			$this->setError($db->getErrorMsg());
			return false;
		}
	}

	function getlistCustomerClasses () {
		$sql = "select * from #__digistore_tax_customerclass order by ordering asc";
		$db = JFactory::getDBO();
		$db->setQuery($sql);
		return ($db->loadObjectList());
	}

	function getCustomerId($username){
		$db = JFactory::getDBO();
		$sql = "select id from #__users where username='".trim($username)."'";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadResult();
		return $result;
	}

	function existUser($username_value){
		$db = JFactory::getDBO();
		$sql = "select count(*) as total from #__users where username='".$username_value."'";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadObject();
		if($result->total == 0){
			return false;
		}
		return true;
	}

	function existNewAuthor($username_value){
		$db = JFactory::getDBO();

		$sql = "select id from #__users u where u.username='".addslashes(trim($username_value))."'";
		$db->setQuery($sql);
		$db->query();
		$id = $db->loadResult();

		$sql = "select count(*) as total from #__digistore_customers a where a.id=".intval($id);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadObject();
		if($result->total==0){
			return false;
		} 
		return true;
	}

	function getUserId($username){
		$db = JFactory::getDBO();
		$sql = "select id from #__users where username='".$username."'";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadResult();
		return $result;
	}

	function getConfigs() {
		if (empty ($this->_configs)) {
			$this->_configs = $this->getTable("digistoreConfig");
			$this->_configs->load($this->_id);
		}
		$f = $this->_configs->time_format;
		$f = str_replace ("MM", "m", $f);
		$f = str_replace ("DD", "d", $f);
		$f = str_replace ("YYYY", "Y", $f);
		$this->_configs->time_format = $f;

		return $this->_configs;
	}

	function getUser(){
		$id = JRequest::getVar("id");
		if(intval($id) == "0"){
			$id = JRequest::getVar("cid", array(), "array");
			$id = $id["0"];
		}
		$db = JFactory::getDBO();
		$sql = "select * from #__users where id='".intval($id)."'";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		return $result;
	}

};

?>