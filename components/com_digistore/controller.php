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

jimport('joomla.application.component.controller');

class digistoreController extends JControllerLegacy
{
	var $_customer = null;

	function __construct()
	{
		parent::__construct();
		$ajax_req = JRequest::getVar("no_html", 0, "request");
		$this->_customer = new digistoreSessionHelper();
		$document = JFactory::getDocument();
		$document->addStyleSheet(JURI::root()."components/com_digistore/assets/css/digistore.css");
		$document->addStyleSheet(JURI::root().'components/com_digistore/assets/css/digistore_cart_lic_orders.css');
		$document->addStyleSheet(JURI::root().'components/com_digistore/assets/css/bootstrap.css');
		$document->addStyleSheet(JURI::root().'components/com_digistore/assets/css/bootstrap-responsive.css');
	}

	function display($cachable = false, $urlparams = false){
		parent::display();
	}

	function debugStop($msg = ''){
		$mainframe=JFactory::getApplication();
		echo $msg;
		$mainframe->close();
	}

	function breadcrumbs(){
		$Itemid = JRequest::getInt("Itemid", 0);
		$mainframe=JFactory::getApplication();

		// Get the PathWay object from the application
		$pw = $mainframe->getPathway();
		$db = JFactory::getDBO();
		$cids = JRequest::getVar('cid', 0, '', 'array');
		$cid = intval($cids[0]);
		$pids = JRequest::getVar('pid', 0, '', 'array');
		$pid = intval($pids[0]);
		$c = JRequest::getVar("controller", "");
		$t = JRequest::getVar("task", "");

		if($c != "digistoreLicenses" && $c != "digistoreOrders") {
			$sql = "select name, parent_id from #__digistore_categories where id=".intval($cid);
			$db->setQuery($sql);
			$res = $db->loadObjectList();
			$res = $res[0];
			$cname = $res->name;
			$parent_id = $res->parent_id;

			$sql = "select name from #__digistore_products where id=".intval($pid);
			$db->setQuery($sql);
			$pname = $db->loadResult();
		}
		else{
			$cname = $cid;
			$pname = $pid;
		}

		$link = JRoute::_("index.php?option=com_digistore&controller=digistoreCategories&Itemid".$Itemid);
		$name = "Category List";
		$pw->addItem($name, $link);
		$bc_added = 0;

		if($c == "digistoreCategories"){
			if($parent_id > 0){
				$sql = "select name from #__digistore_categories where id=".intval($parent_id);
				$db->setQuery($sql);
				$name = $db->loadResult();
				$link = JRoute::_("index.php?option=com_digistore&controller=digistoreCategories&task=list&cid=" . $parent_id . "&Itemid=" . $Itemid);
				$pw->addItem($name, $link);
				$bc_added = 1;
			}
		}

		if($c == "digistoreProducts"){
			if($t == "list"){
				$link = JRoute::_("index.php?option=com_digistore&controller=digistoreProducts&task=list&cid=".$parent_id."&Itemid=".$Itemid);
				$pw->addItem($cname, $link);
				$bc_added = 1;
			}

			if($t == "show"){
				$link = JRoute::_("index.php?option=com_digistore&controller=digistoreProducts&task=list&cid=" . $parent_id . "&Itemid=" . $Itemid);
				$pw->addItem($cname, $link);
				$bc_added = 1;
			}
		}

		if($c == "digistoreCart"){
			$link = JRoute::_("index.php?option=com_digistore&controller=digistoreCart&task=showCart&Itemid=" . $Itemid);
			$name = "Cart";
			$pw->addItem($name, $link);
			if($t == "checkout"){
				$link = "";
				$name = "Checkout";
				$pw->addItem($name, $link);
			}
			$bc_added = 1;
		}

		if(strlen(trim($c)) > 0 && $bc_added == 0 && $c != "digistoreCategories"){
			$link = "";
			$name = $c;
			$pw->addItem($name, $link);
		}
	}
}
