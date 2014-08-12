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

class TabledigistoreProduct extends JTable {
	var $id = null;
	var $sku = null;
	var $name = null;
	var $images = null;
	var $price = null;
	var $discount = null;
	var $ordering = null;
	var $file = null;
	var $description = null;
	var $publish_up = null;
	var $publish_down = null;
	var $checked_out = null;
	var $checked_out_time = null;
	var $published = null;
	var $passphrase = null;
	var $main_zip_file = null;
	var $encoding_files = null;
	var $domainrequired = null;
	var $articlelink = null;
	var $articlelinkid = null;
	var $articlelinkuse = null;
	var $shippingtype = null;
	var $shippingvalue0 = null;
	var $shippingvalue1 = null;
	var $shippingvalue2 = null;
	var $productemailsubject = null;
	var $productemail = null;
	var $sendmail = null;
	var $popupwidth = null;
	var $popupheight = null;
	var $stock = null;
	var $used = null;
	var $usestock = null;
	var $emptystockact = null;
	var $showstockleft = null;
	var $fulldescription = null;
	var $metatitle = null;
	var $metakeywords = null;
	var $metadescription = null;
	var $access = null;
	var $prodtypeforplugin = null;
	var $taxclass = null;
	var $class = null;
	var $showqtydropdown = null;
	var $priceformat = null;
	var $featured = null;
	var $prodimages = null;
	var $defprodimage = null;
	var $mailchimplistid = null;
	var $subtitle = null;
	var $mailchimpapi = null;
	var $mailchimplist = null;
	var $mailchimpregister = null;
	var $mailchimpgroupid = null;
	var $shortdesc = null;
	var $fulldesc = null;
	var $video_url = null;
	var $video_width = null;
	var $video_height = null;
	var $offerplans = null;
	var $hide_public = 0;
	var $cartlink = null;
	var $cartlinkuse = 0;

	function TabledigistoreProduct (&$db) {
		parent::__construct('#__digistore_products', 'id', $db);
	}

	function load ($id = 0, $reset = true) {
		parent::load($id);
		$this->selection = array();
		$this->productfields = array();
		if ($id == 0) {
			$this->id = 0;
		}
		//if( $id OR 1==1 ) {
		$db = JFactory::getDBO();
		$sql = "SELECT catid FROM #__digistore_product_categories WHERE productid='".$this->id."'";
		$db->setQuery($sql);
		$this->selection = $db->loadColumn();

		$where = array();
		$where1[] = " f.published=1 ";
		$sql = 'SELECT 
					f.name, f.id, fp.publishing, fp.mandatory, f.options, f.size
				FROM
					#__digistore_customfields f
						LEFT JOIN
					#__digistore_prodfields fp ON (f.id = fp.fieldid and fp.productid = '.$this->id.')'
					.(count ($where1)>0? " WHERE ".implode (" AND ", $where1):"");
		$db->setQuery($sql);

		$fields = $db->loadObjectList();

		$this->productfields = $fields;
		//}
	}

	function store($updateNulls = false) {

		$db = JFactory::getDBO();

		$this->published = JRequest::getVar("published", "0", "request");
		$this->showqtydropdown = JRequest::getVar("showqtydropdown", "0", "request");

  //	  print_r($this);die;
		//$this->name = mysql_escape_string(stripslashes(JRequest::getVar("name")));
		$res = parent::store($updateNulls = false);
		if (!$res) return $res;

		$catid =  JRequest::getVar('catid', array(0), 'post', 'array');
		$sql_tmp = array();
		$sql = "delete from #__digistore_product_categories where productid='".$this->id."'";
		$db->setQuery($sql);
		$res = $db->query();
		if (!$res) return $res;

		$sql = "insert into #__digistore_product_categories(productid, catid) values ";
		foreach ($catid as $id) {
			$sql_tmp[] = " ('".$this->id."', '".$id."' ) ";
		}
		$sql .= implode (",", $sql_tmp).";";
		$db->setQuery($sql);
		$res = $db->query();
		if (!$res) return $res;

		$fields =  JRequest::getVar('fieldid', array(), 'post', 'array');
		$sql = "delete from #__digistore_prodfields where productid='".$this->id."'";
		$db->setQuery($sql);
		$res = $db->query();
//		if (!$res) return $res;


		if (!empty($fields)) {
			$sqltmp = array();
			$sql = "insert into #__digistore_prodfields(productid, fieldid, publishing, mandatory) values ";
			foreach ($fields as $field) {
				$pub = JRequest::getVar('pub'.$field, 0, 'post');
				$mand = JRequest::getVar('mand'.$field, 0, 'post');
				$sqltmp[] = "('".$this->id."', '".$field."', '".$pub."', '".$mand."')";
			}

			$sql .= implode (",", $sqltmp).";";

			$db->setQuery($sql);
			$res = $db->query();
		}
//		if (!$res) return $res;

		$featured =  JRequest::getVar('featuredproducts', '', 'post');
		$featured = explode ("\n", $featured);
		$sql = "delete from #__digistore_featuredproducts where productid='".$this->id."'";
		$db->setQuery($sql);
		$res = $db->query();
//		if (!$res) return $res;


		$sqltmp = array();
		$sql = "insert into #__digistore_featuredproducts(productid, featuredid) values ";
		foreach ($featured as $f) {
			$sqltmp[] = "('".$this->id."', '".$f."')";
		}

		$sql .= implode (",", $sqltmp).";";

		$db->setQuery($sql);
		$res = $db->query();
		if (!$res) return $res;

		$prodplugintypes = array();

		if (count(JRequest::getVar('prodtypeforplugin', array(0), 'request', 'array') > 0))

			foreach (JRequest::getVar('prodtypeforplugin', array(0), 'request', 'array') as $i => $v) {
				$prodplugintypes[] = $i."=".$v;
			}

		$ptype = JRequest::getVar("ptype");
		$ptype = implode("\n", $ptype);
//		$pclass = JRequest::getVar("pclass");
//		$pclass = implode("\n", $pclass);

		$sql = "update #__digistore_products set "
				." `description`='".$db->escape(stripslashes(JRequest::getVar('description', '', 'request',  'string', JREQUEST_ALLOWRAW)))."', "
				." `fulldescription`='".$db->escape(stripslashes(JRequest::getVar('fulldescription', '', 'request',  'string', JREQUEST_ALLOWRAW)))."', "
				." `productemail`='".$db->escape(stripslashes(JRequest::getVar('productemail', '', 'request',  'string', JREQUEST_ALLOWRAW)))."', "
//				." `taxclass`='".$pclass."', "
				." `class`='".$ptype."', "
				." `prodtypeforplugin`='".implode("\n", $prodplugintypes)."' "
				." where id=".$this->id;
		$db->setQuery($sql);

		$res = $db->query();
		if (!$res) return $res;
		return true;
	}

};


?>