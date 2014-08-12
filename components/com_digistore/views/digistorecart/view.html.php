<?php
/**
* @package			DigiStore Joomla Extension
 * @author			foobla.com
 * @version			$Revision: 377 $
 * @lastmodified	$LastChangedDate: 2013-10-21 12:02:56 +0200 (Mon, 21 Oct 2013) $
 * @copyright		Copyright (C) 2013 foobla.com. All rights reserved.
* @license			GNU/GPLv3
*/

defined ('_JEXEC') or die ("Go away.");

jimport ("joomla.application.component.view");
jimport('joomla.html.parameter');

class digistoreViewdigistoreCart extends JViewLegacy
{
	function display ($tpl = null)
	{
		global $isJ25;
		$db = JFactory::getDBO();
		$lists = array();
		$task = JRequest::getWord('task');
		$configs = $this->_models['digistoreconfig']->getConfigs();

		$document = JFactory::getDocument();
		if($isJ25){
			echo '<script src="'.JURI::root().'components/com_digistore/assets/js/jquery.digistore.js"></script>';
			echo '<script src="'.JURI::root().'components/com_digistore/assets/js/jquery.noconflict.digistore.js"></script>';
		}
		echo '<script src="'.JURI::root().'components/com_digistore/assets/js/bootstrap.min.js"></script>';

		$Itemid = JRequest::getvar("Itemid", "0");

		$this->assignRef("configs", $configs);
		$customer = new digistoreSessionHelper();
		
		$this->assign("customer", $customer);

		$items = $this->_models['digistorecart']->getCartItems($customer, $configs);
		foreach($items as $key => $item)
		{
			if ($key < 0)
				continue;
			// Plans
			if($item->renew == 0) {
				$sql = "SELECT pl.id AS value, pl.name AS text, pp.price, pp.default FROM #__digistore_products_plans pp LEFT JOIN #__digistore_plans pl ON ( pp.plan_id = pl.id ) WHERE pp.product_id = ".$item->id." AND pl.published=1";
			} else {
				$sql = "SELECT pl.id AS value, pl.name AS text, pp.price, pp.default FROM #__digistore_products_renewals pp LEFT JOIN #__digistore_plans pl ON ( pp.plan_id = pl.id ) WHERE pp.product_id = ".$item->id." AND pl.published=1";
			}
			$db->setQuery($sql);
			$item_plains = $db->loadObjectList();
			
			if(!isset($item_plains) || count($item_plains) <= 0)
			{
				$sql = "SELECT pl.id AS value, pl.name AS text, pp.price, pp.default FROM #__digistore_products_plans pp LEFT JOIN #__digistore_plans pl ON ( pp.plan_id = pl.id ) WHERE pp.product_id = ".$item->id." AND pl.published=1";
				$db->setQuery($sql);
				$item_plains = $db->loadObjectList();
			}

			$plans = array();
			$plans[] = JHTML::_('select.option',  "-1", "Select a Subscription");

			$plan_default_value = 0;
			$plan_price_format = "";
			foreach($item_plains as $plan_key => $plan_item)
			{
				if($plan_item->default == 1 )
				{
					$plan_default_value =& $item_plains[$plan_key];
				}
				$plan_price_format = digistoreHelper::format_price2($plan_item->price, $configs->currency, true, $configs);
				$plans[] = JHTML::_('select.option',  $plan_item->value,  $plan_item->text . " - " . $plan_price_format);
			}

			$selected_plain = $item->plan_id;
			if($item->renew == 1) {
				$selected_plain = $plan_default_value->value;
				$items[$key]->plan_id = $plan_default_value->value;
				$item->price = $plan_default_value->price;
				$items[$key]->price = $plan_default_value->price;
				$item->subtotal = $plan_default_value->price;
				$items[$key]->subtotal = $plan_default_value->price;
				$item->taxed = $plan_default_value->price;
				$items[$key]->taxed = $plan_default_value->price;
			}
			
			if ($item->plan_id <= 0 ) {
				$selected_plain = $plan_default_value->value;
				$item->price 	= $plan_default_value->price;
			}

			if ($item->domainrequired <> 3 && count($plans) > 2) {
				$items[$key]->plans_select = JHTML::_('select.genericlist',  $plans, 'plan_id['.$item->cid.']', 'size="1" class="inputbox span2" onchange="update_cart('.$item->cid.')" ', 'value', 'text', $selected_plain);
			} elseif(count($plans) <= 2) {
				$items[$key]->plans_select .= '<select name="plan_id['.$item->cid.']" id="plan_id'.$item->cid.'" style="display:none">';
				$items[$key]->plans_select .= 	'<option value="'.$selected_plain.'" selected="selected">&nbsp;</option>';
				$items[$key]->plans_select .= '</select>';
				if ($item->domainrequired <> 3) {
					if ($item->domainrequired == 0 || $item->domainrequired == 1) {
						$items[$key]->plans_select .= $plans["1"]->text;
					} else {
						$temp_price = explode("-", $plans["1"]->text);
						$price_text = trim($temp_price["1"]);
						$items[$key]->plans_select .= $price_text;
					}
				} else {
					$temp_price = explode("-", $plans["1"]->text);
					$price_text = trim($temp_price["1"]);
					$items[$key]->plans_select .= $price_text;
				}
			} else {
				$items[$key]->plans_select = JHTML::_('select.genericlist',  $plans, 'plan_id['.$item->cid.']', 'style="display:none;" size="1" class="inputbox span2" onchange="update_cart('.$item->cid.')" ', 'value', 'text', $selected_plain);

				$featureds = $item->featured;

				if(isset($featureds) && count($featureds) > 0){
					$items[$key]->plans_select .= '<div style="margin:auto !important;">This package includes the following products: </div>';
				}

				$items[$key]->plans_select .= '<ul class="features">';
				foreach($featureds as $key2 => $featured) {
					$items[$key]->plans_select .= '<li>' . $featured->name.' <nobr>( Plan: '.$featured->planname.' )</nobr></li>';
				}
				$items[$key]->plans_select .= "</ul>";
			}

			$items[$key]->plans_select .= "<input type='hidden' name='renew' value='".( ($item->renew == 1)?'1':'0' )."'/>";
			$items[$key]->plans_select .= "<input type='hidden' name='renewlicid' value='".( ($item->renewlicid != -1)? $item->renewlicid : '-1' )."'/>";

		}
		//dsdebug($items);

		$this->assignRef("items", $items);

		// Plugins
		$plugin_items = $this->get('PluginList');
		$plugins = array();
		foreach($plugin_items as $plugin_item){
			$plugin_params = new JRegistry($plugin_item->params);
			$pluginname = $plugin_params->get($plugin_item->name.'_label');
			$plugins[] = JHTML::_('select.option',  $plugin_item->name,  $pluginname );
		}
		$processor = '';
		if (isset($plan_details['processor'])) $processor = $plan_details['processor'];
		if (!empty($plugins)) {
			$lists['plugins'] = '<span class="digistore_details" style="display: inline-block; margin-left: 5px;">'. JHTML::_('select.genericlist',  $plugins, 'processor', 'class="inputbox" ', 'value', 'text', $processor) . '</span>';
		} else {
			$lists['plugins'] = '<span class="digistore_details" style="display: inline-block;" margin-left: 5px;>'.JText::_('Payment plugins not installed').'</span>';
		}

		$sid = $customer->_sid;
		$sql = "select cart_details from #__digistore_session where sid='".$sid."'";
		$db->setQuery($sql);
		$data = $db->loadResult();
		$promo = explode ("=", $data);

		$sql = "select shipping_details from #__digistore_session where sid='".$sid."'";
		$db->setQuery($sql);
		$shipto = $db->loadResult();
		$price_format = '%'.$configs->totaldigits.'.'.$configs->decimaldigits.'f';
		$categ_digistore = '';	   
		if ( $categ_digistore != '' ) {
			$sql = "select id from #__digistore_categories where title like '".$categ_digistore."' or name like '".$categ_digistore."'";
			$database->setQuery($sql);
			$id = $database->loadResult();
			$cat_url = JRoute::_("index.php?option=com_digistore&controller=digistoreProducts&task=listProducts&cid=" . $id."&Itemid=".$Itemid);
		} else {
			$cat_url = JRoute::_("index.php?option=com_digistore&controller=digistoreCategories&task=listCategories"."&Itemid=".$Itemid);
		}
		$this->assign ("cat_url", $cat_url);
		$maxfields = 0;
		$disc = 0;
		$optlen = array();
		$totalfields = 0;
		$select_only = array();

		foreach ($items as $i => $item) {

			if ($i < 0 ) continue;

			if (isset($item->productfields) && count($item->productfields) > 0)
				$maxfields = digistoreHelper::check_fields($item->productfields, $totalfields, $optlen, $select_only, $maxfields, $item->id);

			if (isset($item->discounted_price) && $item->discounted_price && $item->discount > 0) $disc = 1;

			if($task != 'summary'){
				$qty = array();
				if($item->usestock){
					$lim = $item->stock - $item->used + 1;
				}
				else{
					$lim = 26;
					if ($item->quantity > $lim) $lim = $item->quantity + 5;
				}

				for($i = 1; $i < $lim; $i++){
					$qty[] = JHTML::_('select.option',  $i );
				}

				$active = 1;
				$lists[$item->cid]['quantity'] = JHTML::_('select.genericlist',  $qty, 'quantity['.$item->cid.']', 'size="1" class="inputbox span1" onchange="update_cart('.$item->cid.')" ', 'value', 'text', $item->quantity);
				$lists[$item->cid]['attribs'] = digistoreHelper::add_selector_to_cart($item, $optlen, $select_only, $i, $configs, $configs);
			}
			else{
				$lists[$item->cid]['attribs'] = digistoreHelper::add_selector_to_summary ( $item, $optlen, $select_only, $i, $configs, $configs);
				$lists[$item->cid]['quantity'] = $item->quantity;//JHTML::_('select.genericlist',  $qty, 'quantity['.$item->cid.']', 'class="inputbox" ', 'value', 'text', $item->quantity);
			}
		}

		$this->assign("discount", $disc);
		$this->assign("maxfields", $maxfields);
		$this->assign("optlen", $optlen);
		$this->assign("totalfields", $totalfields);

		$tax = $this->_models["digistorecart"]->calc_price($items, $customer, $configs);
		$this->assign("tax", $tax);

		$promo = $this->_models['digistorecart']->get_promo($customer, 1);
		$this->assign("promocode", $promo->code);
		$this->assign("promoerror", $promo->error);
		$this->assign("lists", $lists);

		parent::display($tpl);
	}

	function paymentwait($tpl = null)
	{
		parent::display($tpl);
	}

}
