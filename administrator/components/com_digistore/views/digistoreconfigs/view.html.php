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

jimport("joomla.application.component.view");

class digistoreAdminViewdigistoreConfigs extends obView 
{

	function display($tpl = null)
	{

		$db	  = JFactory::getDBO();
		$configs = $this->get('Configs');
		require_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'sajax.php');

		JToolBarHelper::title(JText::_('VIEWDSADMINSETTINGS'));

		JToolBarHelper::save();
		JToolBarHelper::apply();
		JToolBarHelper::divider();

		JToolBarHelper::cancel('cancel', 'JTOOLBAR_CLOSE');

		$this->assign("configs", $configs);

		$priceformat = '<select name="decimaldigits">';
		for ($i = 0; $i <= 5; ++$i) {
			$priceformat .= "<option value='" . $i . "'";
			if ($i == $configs->decimaldigits) $priceformat .= 'selected';
			$priceformat .= ('>');

			for ($j = 0; $j < $i; ++$j) $priceformat .= (($i != 0) ? "x" : "");

			$priceformat .= "</option>";
		}

		$priceformat .= "</select>";
		$lists['priceformat'] = $priceformat;


		$flag			 = 0;
		$currency_support = '<span style="vertical-align:middle" id="plugins_to_currencies">';
		$plug_currencies  = digistoreAdminHelper::getListPluginCurrency();
		$sql			  = "select distinct currency_name, currency_full from #__digistore_currencies";
		$db->setQuery($sql);
		$currencies = $db->loadObjectList();

		$plugs = digistoreAdminHelper::getListPlugins();

		// if (count($plug_currencies) > 0) {
			$copt = array();
			foreach ($currencies as $i => $v) {
				$copt[] = JHTML::_('select.option', $v->currency_name, $v->currency_full);
			}
			$select = JHTML::_('select.genericlist', $copt, 'currency', 'class="inputbox" onchange="validatePluginCurrency(this);" ', 'value', 'text', $configs->currency);
			$flag   = 0; //suppose all plugin support current default currency
			if (strlen(trim($configs->currency)) > 0) {
				if (count($plugs) > 0)
					foreach ($plugs as $plug) {
						if (!isset($plug_currencies[$plug->name][$configs->currency])) {
							$flag = 1; //nope - some of them does not
							break;
						}
					}

				if ($flag) {
					$currency_support .= JText::_("Some of plugins do not support current default currency");
				} else {
					$currency_support .= JText::_("DIGISTORE_CURRENCY_DESC");

				}
			} else {
				$currency_support .= JText::_("No default currency is selected");
			}
		/*} else {
			$currency_support .= JText::_("No currencies available or payment plugins published");
			$select = "";
		}*/

		$currency_support .= '</span>';

		$lists['currency_list']	= $select;
		$lists['currency_support'] = $currency_support;

		$currency_position_option   = array();
		$currency_position_option[] = JHTML::_('select.option', '0', JText::_('DIGISTORE_CURRENCY_DISPLAY_POSITION_BEFORE'));
		$currency_position_option[] = JHTML::_('select.option', '1', JText::_('DIGISTORE_CURRENCY_DISPLAY_POSITION_AFTER'));

		$lists['currency_display_position_list'] = JHTML::_('select.genericlist', $currency_position_option, 'currency_position', 'class="inputbox"', 'value', 'text', $configs->currency_position);
		$lists['currency_display_position_desc'] = JText::_('DIGISTORE_CURRENCY_DISPLAY_POSITION_DESC');
		;

		$lists['currencies'] = $currencies;

		$lists['currency_to_plugin'] = $plug_currencies;

		$sql = "SELECT * FROM #__digistore_mailtemplates WHERE type='register'";
		$db->setQuery($sql);
		$result = $db->loadObjectList();
		if (count($result) > 0) {
			$register = $result[0];
		} else {
			$register = -1;
		}

		$sql = "SELECT * FROM #__digistore_mailtemplates WHERE type='order'";
		$db->setQuery($sql);
		$result = $db->loadObjectList();
		if (count($result) > 0) {
			$order = $result[0];
		} else {
			$order = -1;
		}

		$sql = "SELECT * FROM #__digistore_mailtemplates WHERE type='approved'";
		$db->setQuery($sql);
		$result = $db->loadObjectList();
		if (count($result) > 0) {
			$approved = $result[0];
		} else {
			$approved = -1;
		}
		if (!is_object($approved)) {
			$approved		  = new stdClass();
			$approved->subject = '';
			$approved->body	= '';
		}
		if (!is_object($order)) {
			$order		  = new stdClass();
			$order->subject = '';
			$order->body	= '';
		}
		if (!is_object($register)) {
			$register		  = new stdClass();
			$register->subject = '';
			$register->body	= '';
		}
		$this->assign('register', $register);
		$this->assign('order', $order);
		$this->assign('approved', $approved);

		$profile				 = new StdClass();
		$profile->country		= $configs->country;
		$profile->state		  = $configs->state;
		$country_option		  = digistoreAdminHelper::get_country_options($profile, false, $configs);
		$lists['country_option'] = $country_option;

		$topcountrieslist	  = digistoreAdminHelper::get_topcountries_option($configs);
		$lists['topcountries'] = $topcountrieslist;

		$lists['storelocation'] = digistoreAdminHelper::get_store_province($configs);


		$names = array("tax_catalog", "tax_shipping", "tax_discount", "discount_tax", "tax_apply", "tax_summary", "tax_zero");
		foreach ($names as $name) {
			$selected1	= $configs->$name == 1 ? "selected" : "";
			$selected0	= $configs->$name != 1 ? "selected" : "";
			$select	   = '<select name="' . $name . '" >
						<option value="0" ' . $selected0 . ' >' . (JText::_("DSNO")) . '</option>
						<option value="1" ' . $selected1 . ' >' . (JText::_("DSYES")) . '</option>
				</select>';
			$lists[$name] = $select;
		}

		$names = array("tax_price", "shipping_price", "product_price");
		foreach ($names as $name) {
			$selected1	= $configs->$name == 1 ? "selected" : "";
			$selected0	= $configs->$name != 1 ? "selected" : "";
			$select	   = '<select name="' . $name . '" >
						<option value="0" ' . $selected0 . ' >' . (JText::_("DSEXCLUDINGTAX")) . '</option>
						<option value="1" ' . $selected1 . ' >' . (JText::_("DSINCLUDINGTAX")) . '</option>
				</select>';
			$lists[$name] = $select;
		}

		$name		 = "tax_base";
		$selected0	= $configs->$name == 0 ? "selected" : "";
		$selected1	= $configs->$name != 0 ? "selected" : "";
		$select	   = '<select name="' . $name . '" id="' . $name . '" >
					<option value="1" ' . $selected1 . ' >' . (JText::_("DSBILLINGADDRESS")) . '</option>
					<option value="0" ' . $selected0 . ' >' . (JText::_("DSSHIPPINGADDRESS")) . '</option>
			</select>';
		$lists[$name] = $select;

		$name		 = "tax_apply";
		$selected1	= $configs->$name == 1 ? "selected" : "";
		$selected0	= $configs->$name != 1 ? "selected" : "";
		$select	   = '<select name="' . $name . '" >
					<option value="0" ' . $selected0 . ' >' . (JText::_("DSCUSTOMPRICE")) . '</option>
			</select>';
		$lists[$name] = $select;

		// PDG :: start
		$name		 = "tax_eumode";
		$selected1	= $configs->$name == 1 ? "selected" : "";
		$selected0	= $configs->$name != 1 ? "selected" : "";
		$select	   = '<select name="' . $name . '" >
					<option value="0" ' . $selected0 . ' >' . (JText::_("DSNO")) . '</option>
					<option value="1" ' . $selected1 . ' >' . (JText::_("DSYES")) . '</option>
			</select>';
		$lists[$name] = $select;
		// PDG :: end

		$profile			  = new StdClass();
		$profile->tax_country = $configs->tax_country;
		$profile->tax_state   = $configs->tax_state;

		$profile->country = $configs->tax_country;
		$profile->state   = $configs->tax_state;

		$country_option	   = digistoreAdminHelper::get_tax_country_options($profile, true, $configs);
		$lists['tax_country'] = $country_option;

		$lists['tax_state'] = digistoreAdminHelper::get_tax_province($profile, true);

		$name	 = "tax_classes";
		$data	 = $this->_models['digistoretaxrule']->getlistProductTaxClasses();
		$selected = $configs->$name > 0 ? "selected" : "";

		$select = '<select name="tax_classes" >';
		$select .= '<option value="0" ' . $selected . ' >' . (JText::_("DSNO")) . '</option>';
		if (count($data) > 0)
			foreach ($data as $i => $v) {
				$select .= '<option value="' . $v->id . '" ';
				if ($v->id == $configs->$name) {
					$select .= ' selected ';
				}
				$select .= ' > ' . $v->name . '</option>';
			}
		$select .= '</select>';
		$lists[$name] = $select;


		$this->assign('lists', $lists);


		$sql = "SELECT * FROM #__digistore_states WHERE eumember='1'";
		$db->setQuery($sql);
		$eucs = $db->loadObjectList();
		$eu   = array();
		foreach ($eucs as $euc) $eu[] = $euc->country;

		$this->assign("eu", $eu);

		// Upload image
		global $isJ25; if($isJ25) JHTML::_('behavior.mootools');

		$doc = JFactory::getDocument();
		$doc->addScript(JURI::root() . 'components/com_digistore/assets/js/ajaxupload.js');
		$doc->addScript(JURI::root() . 'components/com_digistore/assets/js/jquery.digistore.js');
		$doc->addScript(JURI::root() . 'components/com_digistore/assets/js/jquery.noconflict.digistore.js');
		$doc->addScript(JURI::root() . 'administrator/components/com_digistore/assets/js/redactor.min.js');
		$doc->addStyleSheet(JURI::root() . 'administrator/components/com_digistore/assets/css/redactor.css');

		$upload_script = '

			window.addEvent( "domready", function(){
				new AjaxUpload("ajaxuploadcatimage", {
					action: "' . JURI::root() . 'administrator/index.php?option=com_digistore&controller=digistoreConfigs&task=uploadimage&tmpl=component&no_html=1",
					name: "store_logo",
					multiple: false,
					onComplete: function(file, response){
						document.getElementById("sitelogo").innerHTML = response;
					}
				});

				//jQuery(".useredactor").redactor();
				jQuery(".redactor_useredactor").css("height","400px");
			});
		';
		$doc->addScriptDeclaration($upload_script);
		parent::display($tpl);
	}

	function supportedsites($tpl = null)
	{
		parent::display($tpl);
	}

}

?>