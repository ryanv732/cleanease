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

class digistoreAdminViewdigistoreEmailreminders extends obView {

	function display ($tpl =  null )
	{
		// Access check.
		if (!JFactory::getUser()->authorise('digistore.autoresponders', 'com_digistore'))
		{
			return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
		}

		JToolBarHelper::title(JText::_('Email reminders Manager'), 'generic.png');
		JToolBarHelper::custom( 'duplicate', 'save.png', 'save.png', 'Duplicate', true, false );
		JToolBarHelper::divider();
		JToolBarHelper::addNew();
		JToolBarHelper::editList();
		JToolBarHelper::divider();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::divider();
		JToolBarHelper::deleteList();

		$emails = $this->get('Items');
		$pagination = $this->get('Pagination');

		$this->emails = $emails;
		$this->pagination = $pagination;

		$configs = $this->_models['digistoreconfig']->getConfigs();
		$this->assign("configs", $configs);

		parent::display($tpl);

	}

	function editForm($tpl = null) {
		global $isJ25;
		$db = JFactory::getDBO();

		$email = $this->get('emailreminder');

		$isNew = ($email->id < 1);
		$text = $isNew?JText::_('New'):JText::_('Edit');
		$this->assign("action", $text);
		
		$doc = JFactory::getDocument();
		if($isJ25){
			$doc->addScript(JURI::root() . 'components/com_digistore/assets/js/jquery.digistore.js');
			$doc->addScript(JURI::root() . 'components/com_digistore/assets/js/jquery.noconflict.digistore.js');
		} else {
			JHtml::_('jquery.framework');
		}
		$doc->addScript(JURI::root() . 'administrator/components/com_digistore/assets/js/redactor.min.js');
		$doc->addStyleSheet(JURI::root() . 'administrator/components/com_digistore/assets/css/redactor.css');
		$upload_script = '
		window.addEvent( "domready", function(){
				jQuery(".useredactor").redactor();
				jQuery(".redactor_useredactor").css("height","400px");
			});';
		$doc->addScriptDeclaration( $upload_script );

		JToolBarHelper::title(JText::_('Email reminders').":<small>[".$text."]</small>");
		JToolBarHelper::save();

		if ($isNew) {
			JToolBarHelper::divider();
			JToolBarHelper::cancel();
		} else {
			JToolBarHelper::apply();
			JToolBarHelper::divider();
			JToolBarHelper::cancel ('cancel', 'Close');
		}

		$this->assign("email", $email);

		$configs = $this->_models['digistoreconfig']->getConfigs();
		$lists = null;

		/* Email Trigger Number */
		for ($i=1; $i<=100; $i++)
		{
			$type[] = JHTML::_('select.option', $i, $i);
		}
		$lists['trigger_type'] = JHTML::_('select.genericlist', $type, 'type', 'class="inputbox" size="1"', 'value', 'text', $email->type);

		/* Email Trigger Period */
		$period[] = JHTML::_('select.option', 'day', JText::_("SUBCRUB_DURATION_DAY"));
		$period[] = JHTML::_('select.option', 'week', JText::_("SUBCRUB_DURATION_WEEK"));
		$period[] = JHTML::_('select.option', 'month', JText::_("SUBCRUB_DURATION_MONTH"));
		$period[] = JHTML::_('select.option', 'year', JText::_("SUBCRUB_DURATION_YEAR"));
		$lists['trigger_period'] = JHTML::_('select.genericlist', $period, 'period', 'class="inputbox" size="1"', 'value', 'text', $email->period);

		/* Email Trigger Calc */
		$calc[] = JHTML::_('select.option', 'before', JText::_("TRIGGER_BEFORE"));
		$calc[] = JHTML::_('select.option', 'after', JText::_("TRIGGER_AFTER"));
		$lists['trigger_calc'] = JHTML::_('select.genericlist', $calc, 'calc', 'class="inputbox" size="1"', 'value', 'text', $email->calc);

		/* Email Trigger Date Calc */
		$date_calc[] = JHTML::_('select.option', 'expiration', JText::_("DATE_CALC_EXPIRATION"));
		$date_calc[] = JHTML::_('select.option', 'purchase', JText::_("DATE_CALC_PURCHASE"));
		$lists['trigger_date_calc'] = JHTML::_('select.genericlist', $date_calc, 'date_calc', 'class="inputbox" size="1"', 'value', 'text', $email->date_calc);

		$lists['subject'] = "<input type='text' style='width:60%' id='subject' name='subject' value='".$email->subject."'/>";

		/* Published */
		$published[] = JHTML::_( 'select.option', '1', JText::_("DSYES") );
		$published[] = JHTML::_( 'select.option', '0', JText::_("DSNO") );

		$lists['published'] = JHTML::_('select.radiolist', $published, 'published', 'class="inputbox" ', 'value', 'text', $email->published, 'published' );

		$this->assign("configs", $configs);
		$this->assign("lists", $lists);

		parent::display($tpl);

	}

}

?>