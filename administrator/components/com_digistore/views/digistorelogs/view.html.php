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

jimport( "joomla.application.component.view" );
JHTML::_( 'behavior.modal' );

class digistoreAdminViewdigistoreLogs extends obView
{

	function display($tpl = null)
	{
		// Access check.
		if (!JFactory::getUser()->authorise('digistore.logs', 'com_digistore'))
		{
			return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
		}

		$document = JFactory::getDocument();
		$task = JRequest::getVar("task", "");
		if($task == "systememails"){
			JToolBarHelper::title(JText::_('Emails Log'), 'generic.png');
		}
		elseif($task == "download"){
			JToolBarHelper::title(JText::_('Downloads Log'), 'generic.png');
		}
		elseif($task == "purchases"){
			JToolBarHelper::title(JText::_('Purchases Log'), 'generic.png');
		}

		$emails = $this->get('Items');
		$pagination = $this->get('Pagination');

		$this->emails = $emails;
		$this->pagination = $pagination;

		$configs =  $this->_models['digistoreconfig']->getConfigs();
		$this->assign( "configs", $configs );
		parent::display($tpl);
	}

	function getEmailName($id){
		$model = $this->getModel();
		$email_name = $model->getEmailName($id);
		return $email_name;
	}

	function getUserDetails($id){
		$model = $this->getModel();
		$user_details = $model->getUserDetails($id);
		return $user_details;
	}

	function getProductDetails($id){
		$model = $this->getModel();
		$product_details = $model->getProductDetails($id);
		return $product_details;
	}

	function editEmail($tpl=null){
		$email = $this->get('email');
		$this->assignRef('email', $email);
		$configs =  $this->_models['digistoreconfig']->getConfigs();
		$this->assign( "configs", $configs );
		parent::display($tpl);
	}
}
?>
