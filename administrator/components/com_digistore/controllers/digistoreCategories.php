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

jimport ('joomla.application.component.controller');

class digistoreAdminControllerdigistoreCategories extends digistoreAdminController {
	var $_model = null;

	function __construct () {

		parent::__construct();

		$this->registerTask ("add", "edit");
		$this->registerTask ("apply", "save");
		$this->registerTask ("", "listCategories");
		$this->registerTask ("unpublish", "publish");
		$this->registerTask ("orderdown", "orderdown");
		$this->registerTask ("orderup", "orderup");
		$this->registerTask ("saveorder", "saveorder");

		$this->_model = $this->getModel("digistoreCategory");
	}

	function listCategories() {
		JRequest::setVar ("view", "digistoreCategories");
		$view = $this->getView("digistoreCategories", "html");
		$view->setModel($this->_model, true);
		$view->display();
	}


	function edit () {
		JRequest::setVar ("hidemainmenu", 1);
		$view = $this->getView("digistoreCategories", "html");
		$view->setLayout("editForm");
		$view->setModel($this->_model, true);
		$view->editForm();

	}

	function uploadimage() {

		jimport('joomla.filesystem.file');

		$path_image = JPATH_ROOT . DS . "images" . DS . "stories" . DS .  "digistore" . DS . "categories" . DS;
		$path_thumb = JPATH_ROOT . DS . "images" . DS . "stories" . DS .  "digistore" . DS . "categories" . DS . "thumb" . DS;

		// resize source image file
		require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'libs' . DS . 'thumbnail.inc.php';

		$config_model = $this->getModel("digistoreConfig");
		$configs = $config_model->getConfigs();

		$file = JRequest::getVar('catimage', null, 'files', 'array');

		if ($file['error'] == 0) {

			$uniqid = uniqid (rand (),true);
			$filename = JFile::makeSafe($file['name']);

			$filepath = JPath::clean($path_image . strtolower($uniqid.'_'.$file['name']));

			if (!JFile::upload( $file['tmp_name'], $filepath )) {
				echo 'Can not upload file to "' . $filepath . '"';
			}

			echo "<div id='box".$uniqid."' style='float:left;padding:0.5em;margin:0.5em;'>
					<img src='".ImageHelper::ShowCategoryImage(strtolower($uniqid.'_'.$file['name']))."'/>
					<input type='hidden' name='catimageshidden' value='".strtolower($uniqid.'_'.$file['name'])."'/>
					<div style='padding:0.5em 0;'>
						<span style='float:right;'><a href='javascript:void(0);'  onclick='document.getElements(\"div[id=box".$uniqid."]\").each( function(el) { el.getParent().removeChild(el); });'>Delete</a></span>
					</div>
				</div>";
		}

		die();
	}

	function save () {

		if ($this->_model->store() ) {
			$msg = JText::_('CATEGORYSAVED');
		} else {
			$msg = JText::_('CATEGORYSAVEFAILED');
		}

		if ( JRequest::getVar('task','') == 'save' ) {
			$save_url =  "index.php?option=com_digistore&controller=digistoreCategories";
			$this->setRedirect($save_url, $msg);
		} else {
			$category_id = JRequest::getVar('id','');
			$apply_url = "index.php?option=com_digistore&controller=digistoreCategories&task=edit&cid[]=" . $category_id;
			$this->setRedirect($apply_url, $msg);
		}
	}

	function remove () {
		if (!$this->_model->delete()) {
			$msg = JText::_('CATEGORYREMOVEFAILL');
		} else {
			$msg = JText::_('CATEGORYREMOVEFAILL');
		}

		$link = "index.php?option=com_digistore&controller=digistoreCategories";
		$this->setRedirect($link, $msg);
	}

	function cancel () {
		$msg = JText::_('CATEGORYCANCELED');
		$link = "index.php?option=com_digistore&controller=digistoreCategories";
		$this->setRedirect($link, $msg);
	}

	function publish () {
		$res = $this->_model->publish();

		if (!$res) {
			$msg = JText::_('CATEGORYPUBLISHINGERROR');
		} elseif ($res == -1) {
			$msg = JText::_('CATEGORYUNPUBLISHINGSUCC');
		} elseif ($res == 1) {
			$msg = JText::_('CATEGORYPUBLISHINGSUCC');
		} else {
			$msg = JText::_('CATEGORYUNSPECERROR');
		}

		$link = "index.php?option=com_digistore&controller=digistoreCategories";
		$this->setRedirect($link, $msg);


	}

	function orderdown() {
		$cid = JRequest::getVar( 'cid', array(0), '', 'array' );
		$this->_model->orderField( $cid[0], 1 );
		$link = "index.php?option=com_digistore&controller=digistoreCategories";
		$this->setRedirect($link, $msg);
	}

	function orderup() {
		$cid = JRequest::getVar( 'cid', array(0), '', 'array' );
		$msg = $this->_model->orderField( $cid[0], -1 );
		$link = "index.php?option=com_digistore&controller=digistoreCategories";
		$this->setRedirect($link, $msg);
	}

	function saveorder() {
		$msg = $this->_model->saveorder();
		$link = "index.php?option=com_digistore&controller=digistoreCategories";
		$this->setRedirect($link, $msg);
	}

};

?>