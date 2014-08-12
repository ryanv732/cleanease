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

jimport ("joomla.application.component.view");

class digistoreViewdigistoreCategories extends obView {

	function display ($tpl =  null ) {

		$conf = $configs = $this->_models['digistoreconfig']->getConfigs();
		$this->assignRef("configs", $configs);
		$this->assignRef("conf", $conf);

		$catid =  JRequest::getVar('cid', array(), 'request', 'array');

		if (is_array($catid)&&count($catid) > 0)	$catid = intval($catid[0]);
		elseif(!is_array($catid) ) $catid = intval($catid);
		else $catid = 0;

		if(!$catid){
			$cats = $this->get('listCategories');
			$pagination = $this->_models['digistorecategory']->getPagination($catid);
		}
		else{
		 	$cat = $this->_models['digistorecategory']->getCategory($catid);
			$cats = $this->_models['digistorecategory']->getCategoryCategories($catid);
			$pagination = $this->_models['digistorecategory']->getPagination($catid);
			$prods = $this->_models['digistorecategory']->getCategoryProducts();
			$this->assignRef('prods', $prods);
		}
		
		$this->assignRef('pagination', $pagination);

		if (count($cats) > 0)
		foreach ($cats as $i => $subcat) {
			$cats[$i]->prods = $this->_models['digistorecategory']->getCategoryProducts($subcat->id);
			$cats[$i]->cats = $this->_models['digistorecategory']->getCategoryCategories ($subcat->id);
		}
		$this->assignRef('cats', $cats);
		switch ($configs->catlayoutstyle) {
			case "0":
				$cats_out = $this->_models['digistorecategory']->getCategoriesTree();
				$this->assignRef('cats_out', $cats_out);
				break;

			case "1":

				break;

			case "2":

				break;

			default:
				$cats_out = $this->_models['digistorecategory']->getCategoriesTree();
				$this->assignRef('cats_out', $cats_out);
				break;
		}

		parent::display($tpl);

	}

	function countSublist($cat_id){
		$return = "";
		$db = JFactory::getDBO();
		$sql = "select count(*) from #__digistore_categories where `parent_id`=".intval($cat_id)." and `published`=1";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadResult();
		if($result == 1){
			$return = $result." ".JText::_("DIGI_CATEGORY");
		}
		elseif($result > 1){
			$return = $result." ".JText::_("DIGI_CATEGORIES");
		}
		elseif($result == 0){
			$sql = "select count(*)
					from #__digistore_product_categories pc, #__digistore_products p
					where pc.catid=".intval($cat_id)."
					  and pc.productid=p.id
					  and p.published=1
					  and p.hide_public=0";
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadResult();
			if($result == 1){
				$return = $result." ".JText::_("DIGI_PRODUCT");
			}
			elseif($result > 1){
				$return = $result." ".JText::_("DIGI_PRODUCTS");
			}
		}
		return $return;
	}

}

?>
