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

class digistoreAdminViewdigistorePromos extends obView
{

	function display ($tpl =  null )
	{
		// Access check.
		if (!JFactory::getUser()->authorise('digistore.promocodes', 'com_digistore'))
		{
			return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
		}

		JToolBarHelper::title(JText::_('Promotions Manager'), 'generic.png');
		JToolBarHelper::addNew();
		JToolBarHelper::editList();
		JToolBarHelper::divider();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();

		JToolBarHelper::divider();

		JToolBarHelper::deleteList();

		$configs = $this->_models['digistoreconfig']->getConfigs();
		$this->assign("configs", $configs);

		$condition = JRequest::getVar("condition", '1');
		$this->assign ("condition", $condition);

		$status = JRequest::getVar("status", '1');
		$this->assign ("status", $status);

		$promos = $this->get('Items');
		$pagination = $this->get('Pagination');

		$this->promos = $promos;
		$this->pagination = $pagination;

		parent::display($tpl);

	}

	function editForm($tpl = null)
	{
		$db = JFactory::getDBO();
		$promo = $this->get('promo');
		$isNew = ($promo->id < 1);
		$text = $isNew?JText::_('New'):JText::_('Edit');
		JHtml::_( 'behavior.modal' );

		JToolBarHelper::title(JText::_('Promo').":<small>[".$text."]</small>");
		JToolBarHelper::save();
		if ($isNew) {
			JToolBarHelper::spacer();
			JToolBarHelper::divider();
			JToolBarHelper::cancel();
		} else {
			JToolBarHelper::spacer();
			JToolBarHelper::apply();
			JToolBarHelper::divider();

			JToolBarHelper::cancel ('cancel', 'Close');
		}

		$this->assign("promo", $promo);
		$this->assign("promo_products", $this->get('promoProducts'));
		$this->assign("promo_orders", $this->get('promoOrders'));

		$configs = $this->_models['digistoreconfig']->getConfigs();
		$lists = null;

		$this->assign("configs", $configs);
		$this->assign("lists", $lists);
		parent::display($tpl);

	}

	function productitem( $tpl = null )
	{
		// Rand ID
		$id_rand = uniqid (rand ());
		$this->assign( "id_rand", $id_rand );

		// Customer
		$userid = JRequest::getVar('userid', 0);

		// Subcription plain
		$plans[] = JHTML::_('select.option',  'none',  'none' );
		$plans = JHTML::_('select.genericlist',  $plans, 'subscr_plan_select['.$id_rand.']', 'class="inputbox" size="1" ', 'value', 'text');
		$this->assign( "plans", $plans );

		$configs =  $this->_models['digistoreconfig']->getConfigs();
		$this->assign( "configs", $configs );

		parent::display( $tpl );
	}

}
