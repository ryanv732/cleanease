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

class digistoreAdminViewdigistoreCustomers extends obView {

	function display ($tpl =  null )
	{
		// Access check.
		if (!JFactory::getUser()->authorise('digistore.customers', 'com_digistore'))
		{
			return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
		}

		JToolBarHelper::title(JText::_('VIEWDSADMINCUSTOMERS'), 'generic.png');
		JToolBarHelper::addNew();
		JToolBarHelper::editList();
		JToolBarHelper::divider();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::divider();
		JToolBarHelper::deleteList();

		$customers = $this->get('Items');
		$pagination = $this->get('Pagination');

		$this->custs = $customers;
		$this->pagination = $pagination;

		$prd = JRequest::getVar("prd", 0, "request");
		$this->assign("prd", $prd);

		$keyword = JRequest::getVar("keyword", "", "request");
		$this->assign ("keyword", $keyword);

		parent::display($tpl);

	}

	function settypeform($tpl = null){
		$id = JRequest::getVar("id", "0");
		if($id == "0"){
			JToolBarHelper::title(JText::_('VIEWLICCUSTOMER').":<small>[".trim(JText::_("DIGI_NEW"))."]</small>");
		}
		else{
			JToolBarHelper::title(JText::_('VIEWLICCUSTOMER').":<small>[".trim(JText::_("DIGI_EDIT"))."]</small>");
		}

		JToolBarHelper::custom('next','forward.png','forward_f2.png','Next',false);
		JToolBarHelper::cancel();
		parent::display($tpl);
	}

	function editForm($tpl = null) {
		require_once( JPATH_COMPONENT.DS.'helpers'.DS.'sajax.php' );
		$db = JFactory::getDBO();
		$customer = $this->get('customer');
		$user = $this->get('User');
		$isNew = ($customer->id < 1);
		$text = $isNew?JText::_('New'):JText::_('Edit');

		JToolBarHelper::title(JText::_('Customer').":<small>[".$text."]</small>");
		JToolBarHelper::save();
		if ($isNew) {
			JToolBarHelper::divider();
			JToolBarHelper::cancel();
		} else {
			JToolBarHelper::apply();
			JToolBarHelper::divider();
			JToolBarHelper::cancel ('cancel', 'Close');
		}

		$this->assign("cust", $customer);
		$this->assign("user", $user);

		$configs = $this->get("Configs");
		$country_option = digistoreAdminHelper::get_country_options($customer, false, $configs);
		$lists['country_option'] = $country_option;

		$profile = new StdClass();
		$profile->country = $customer->shipcountry;
		$profile->state = $customer->shipstate;
		$shipcountry_option = digistoreAdminHelper::get_country_options($customer, true, $configs);
		$lists['shipcountry_options'] = $shipcountry_option;

		$lists['customerlocation'] = digistoreAdminHelper::get_store_province($customer);

		$profile = new StdClass();
		$profile->country = $customer->shipcountry;
		$profile->state = $customer->shipstate;
		$lists['customershippinglocation'] = digistoreAdminHelper::get_store_province($profile, true, $configs);

		$cclasses = explode("\n", $customer->taxclass);
		$data = $this->get('listCustomerClasses');
		$select = '<select name="taxclass" >';
		if (count($data) > 0)
		foreach($data as $i => $v) {
			$select .= '<option value="'.$v->id.'" ';
			if (in_array($v->id, $cclasses)) {
				$select .= ' selected ' ;
			}
			$select .= ' > '.$v->name.'</option>';
		}
		$select .= '</select>';
		$lists['customer_class'] = $select;

		$this->assign("lists", $lists);
		$keyword = JRequest::getVar("keyword", "", "request");
		$this->assign ("keyword", $keyword);

		parent::display($tpl);
	}

}

?>