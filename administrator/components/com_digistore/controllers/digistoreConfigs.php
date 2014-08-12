<?php
/**
* @package			DigiStore Joomla Extension
 * @author			foobla.com
 * @version			$Revision: 341 $
 * @lastmodified	$LastChangedDate: 2013-10-10 14:28:28 +0200 (Thu, 10 Oct 2013) $
 * @copyright		Copyright (C) 2013 foobla.com. All rights reserved.
* @license


*/

defined ('_JEXEC') or die ("Go away.");

jimport ('joomla.application.component.controller');

class digistoreAdminControllerdigistoreConfigs extends digistoreAdminController {

	var $_model = null;

	function __construct () {

		parent::__construct();

		require_once( JPATH_COMPONENT.DS.'helpers'.DS.'helper.php' );

		$this->registerTask ("", "listConfigs");
		$this->registerTask("general", "listConfigs");
		$this->registerTask("payments", "listConfigs");
		$this->registerTask("content", "listConfigs");
		$this->registerTask("apply", "save");
		$this->registerTask("supportedsites", "supportedsites");
		$this->registerTask ("install", "installDefaultValues");
		$this->_model = $this->getModel("digistoreConfig");
	}

	function installDefaultValues(){
		$model = $this->getModel("digistoreConfig");
		$model->installDefaultValues();
		$this->setRedirect("index.php?option=com_digistore&first=true");
	}

	function listConfigs() {
		$view = $this->getView("digistoreConfigs", "html");
		$view->setModel($this->_model, true);
		$model = $this->getModel("digistoreTaxRule");
		$view->setModel($model);

		$view->display();
	}


	function save () {
		if($this->_model->store()){
			$msg = JText::_('CONFIGSAVED');
		}
		else{
			$msg = JText::_('Configs not saved');
		}

		if(JRequest::getVar('task','') == 'save'){
			$save_url =  "index.php?option=com_digistore";
			$this->setRedirect($save_url, $msg);
		}
		else{
			$task2 = JRequest::getVar('task2', 'general');
			$apply_url = "index.php?option=com_digistore&controller=digistoreConfigs&task2=" . $task2;
			$this->setRedirect($apply_url, $msg);
		}
	}

	function supportedsites(){
		$view = $this->getView("digistoreConfigs", "html");
		$view->setLayout("supportedsites");
		$view->supportedsites();
	}

	function uploadimage() {
		jimport('joomla.filesystem.file');

		$path_image = JPATH_ROOT . DS . "images" . DS . "stories" . DS .  "digistore" . DS . "store_logo" . DS;
		$path_thumb = JPATH_ROOT . DS . "images" . DS . "stories" . DS .  "digistore" . DS . "store_logo" . DS . "thumb" . DS;

		// resize source image file
		require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'libs' . DS . 'thumbnail.inc.php';
		require_once(JPATH_SITE.DS."administrator".DS."components".DS."com_digistore".DS."helpers".DS."image.php");

		$config_model = $this->getModel("digistoreConfig");
		$configs = $config_model->getConfigs();

		$file = JRequest::getVar('store_logo', null, 'files', 'array');

		if($file['error'] == 0) {
			$filename = JFile::makeSafe($file['name']);
			$filepath = JPath::clean($path_image.strtolower($file['name']));

			if(!JFile::upload($file['tmp_name'], $filepath)){
				echo 'Can not upload file to "'.$filepath.'"';
			}
			//<img src='".ImageHelper::shouStoreLogoThumb(strtolower($file['name']))."'/>
			echo "<div id='box' style='float:left;padding:0.5em;margin:0.5em;'>
					<img src='".JURI::root()."/images/stories/digistore/store_logo/".$file['name']."'/>
					<input type='hidden' name='store_logo' value='".strtolower($file['name'])."'/>
					<div style='padding:0.5em 0;'>
						<span style='float:right;'><a href='javascript:void(0);'  onclick='document.getElements(\"div[id=box]\").each( function(el) { el.getParent().removeChild(el); });'>Delete</a></span>
					</div>
				</div>";
		}

		die();
	}

}


?>
