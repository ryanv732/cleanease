<?php
/**
* @package			DigiStore Joomla Extension
 * @author			foobla.com
 * @version			$Revision: 458 $
 * @lastmodified	$LastChangedDate: 2014-02-10 11:47:01 +0100 (Mon, 10 Feb 2014) $
 * @copyright		Copyright (C) 2013 foobla.com. All rights reserved.
* @license			GNU/GPLv3
*/

defined ('_JEXEC') or die ("Go away.");

jimport ("joomla.application.component.view");

class digistoreAdminViewdigistoreAdmin extends obView {
	
	public $version			= null;
	public $newversion 		= null;
	
	function display ($tpl =  null ) {
		global $isJ25;
		
		// Options button.
		if (JFactory::getUser()->authorise('core.admin', 'com_digistore')) {
			JToolBarHelper::preferences('com_digistore');
		}

		JToolBarHelper::title(JText::_('DIGISTORE_DASHBOARD'), 'generic.png');
		
		if ($isJ25) {
			parent::display($tpl); // Load Joomla! 2.5 Dashboard
		} else {
			// Get latest orders & top customers from helpers
			include_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'helper.php');
			$this->latest_orders = digistoreAdminHelper::getOrders(5);
			$this->latest_products = digistoreAdminHelper::getProducts(5);
			$this->top_customers = digistoreAdminHelper::getCustomers(5);
			parent::display('j3'); // Load Joomla! 3 Dashboard
		}
	}

	function isCurlInstalled() {
		$array = get_loaded_extensions();
		if(in_array("curl", $array)){
			return true;
		}
		else{
			return false;
		}
	}
	
	function getVersion(){
		if( !$this->version ) {
			$db 	= JFactory::getDbo();
			$sql 	= "SELECT `manifest_cache` FROM `#__extensions` WHERE `type`='component' AND `element`='com_digistore'";
			$db->setQuery($sql);
			$res 		= $db->loadResult();
			$manifest 	= new JRegistry($res);
			$this->version 	= $manifest->get('version');
		}
		return $this->version;
	}
	
	function getNewVersion(){
		if(!$this->newversion){
			$db = JFactory::getDbo();
			$ext = JComponentHelper::getComponent('com_digistore');
			$sql = 'SELECT `version` FROM `#__updates` WHERE `extension_id`='.$ext->id.' ORDER BY update_id DESC LIMIT 1';
			$db->setQuery($sql);
			$this->newversion = $db->loadResult();
		}
		return $this->newversion;
	}
	
	function hashNewVersion() {
		$current_version 	= $this->getVersion();
		$update_version 	= $this->getNewVersion();
		if (version_compare( $update_version, $current_version ) == 1 )
		{
			return true;
		} else {
			return false;
		}
	}
	
	function versionNotify(){
		$html = '';
		if($this->hashNewVersion()){
			$html = sprintf(JText::_('DIGISTORE_NEWVERSION_AVAILABLE_OLD'),$this->getVersion());
			$html .= '<br />';
			$html .= sprintf(JText::_('DIGISTORE_NEWVERSION_AVAILABLE_NEW'),$this->getNewVersion());
			$html .= '<br />';
			$html .= '<br />';
			$html .= '<a class="btn btn-small btn-danger" href="index.php?option=com_installer&view=update&filter_search=digistore">';
			$html .= '<i class="icon-download"></i> '.JText::_('DIGISTORE_UPDATE_NOW');
			$html .= '</a>';
		} else {
			$html = sprintf(JText::_('DIGISTORE_NEWVERSION_NOTAVAILABLE'),$this->getVersion());
		}
		return $html;
	}
}

?>