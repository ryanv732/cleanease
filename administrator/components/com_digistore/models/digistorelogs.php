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

jimport('joomla.application.component.modellist');
jimport('joomla.utilities.date');

class digistoreAdminModeldigistoreLogs extends JModelList{

	protected $_context = 'com_digistore.digistoreLogs';
	var $_emails;
	var $_total = 0;
	var $_pagination = null;
	var $_statusList = array("Active", "Pending");

	function __construct(){
		parent::__construct();
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
		$c = $this->getInstance( "digistoreConfig", "digistoreAdminModel" );
		$configs = $c->getConfigs();
		$db = JFactory::getDBO();
		$task = JRequest::getVar("task", "");

		$sql = "";
		$and = "";
		$order = "";

		$search = JRequest::getVar("search", "");
		if(trim($search) != ""){
			$and .= " and (c.firstname like '%".$search."%' OR c.lastname like '%".$search."%' OR l.subject like '%".$search."%' OR l.body like '%".$search."%' OR l.to like '%".$search."%')";
		}

		if($task == "systememails"){
			//$sql = "select l.* from #__digistore_logs l, #__digistore_customers c where c.id=l.userid and l.send_date <>'0000-00-00 00:00:00' ".$and." order by l.`send_date` desc";
			$sql = "select l.`id`, l.`userid`, l.`productid`, l.`emailname`, l.`emailid`, l.`to`, l.`subject`, l.`body`, l.`buy_date`, l.`send_date`, l.`download_date`, l.`buy_type`
					from #__digistore_logs l, #__digistore_customers c
					where c.id=l.userid and l.send_date <>'0000-00-00 00:00:00' ".$and."
					group by l.`subject`
					order by l.`send_date` desc";
		}
		elseif($task == "download"){
			$and = "";
			if(trim($search) != ""){
				$and .= " and (c.firstname like '%".$search."%' OR c.lastname like '%".$search."%' OR l.subject like '%".$search."%' OR l.body like '%".$search."%' OR l.to like '%".$search."%' OR p.name like '%".$search."%')";
			}
			$sql = "select l.*
					from #__digistore_logs l, #__digistore_customers c, #__digistore_products p
					where p.id=l.productid and c.id=l.userid and l.download_date <> '0000-00-00 00:00:00' ".$and."
					order by l.`download_date` desc";
		}
		elseif($task == "purchases"){
			$and = "";
			if(trim($search) != ""){
				$and .= " and (c.firstname like '%".$search."%' OR c.lastname like '%".$search."%' OR p.name like '%".$search."%' OR u.username like '%".$search."%') ";
			}
			$purchase = JRequest::getVar("purchase", "");
			if(trim($purchase) != ""){
				$and .= " and l.buy_type='".trim($purchase)."'";
			}
			$sql = "select l.*
					from #__digistore_logs as l
						 inner join #__digistore_customers as c on c.id=l.userid
						 inner join #__digistore_products as p on p.id=l.productid
						 inner join #__users as u on u.id=c.id
					where l.buy_date <> '0000-00-00 00:00:00' ".$and."
					order by l.`buy_date` desc";
		}
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

	function getEmailName($id){
		$db = JFactory::getDBO();
		$sql = "select name from #__digistore_emailreminders where id=".intval($id);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadResult();
		return $result;
	}

	function getUserDetails($id){
		$db = JFactory::getDBO();
		$sql = "select c.firstname, c.lastname, u.username from #__users u, #__digistore_customers c where c.id=u.id and u.id=".intval($id);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		return $result;
	}

	function getProductDetails($id){
		$db = JFactory::getDBO();
		$sql = "select * from #__digistore_products where id=".intval($id);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		return $result;
	}

	function getemail(){
		$id = JRequest::getVar("id", "0");
		$db = JFactory::getDBO();
		$sql = "select * from #__digistore_logs where id=".intval($id);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		return $result;
	}
};
?>