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

class digistoreAdminController extends obController {

	function __construct() {
		parent::__construct();
		$ajax_req = JRequest::getVar("no_html", 0, "request");
		$ajax_tmpl = JRequest::getVar("tmpl", 0, "request");

		$this->createDigistoreMenu();

		if ( !$ajax_req && ($ajax_tmpl !== 'component') ){
			$document = JFactory::getDocument();
			$document->addScript("components/com_digistore/assets/js/dtree.js");
			$document->addScript("index.php?option=com_digistore&controller=digistoreDtree&task=showJS&no_html=1");
			$document->addStyleSheet("components/com_digistore/assets/css/dtree.css");
			$view = $this->getView('digistoreDtree', 'html');
			$task = JRequest::getVar("task", "");
			$controller= JRequest::getVar('controller');
			if ( $task != "export" && $task != "customerexport" && $task != "edit" && $task != 'add' ) {
			?>
				<span style="float:left;display:inline;background-color:white;" class="navtreewrap">
			<?php
				$view->showDtree();
			?>
				</span>
				</td><td class="mainwrap" valign="top">
			<?php
			}
		}
	}

	function debugStop($msg = '') {
		global $mainframe;
		echo $msg;
		$mainframe->close();
	}

	/**
	 * @TODO: get this function works on Joomla 3
	 */
	function createDigistoreMenu(){
		$db = JFactory::getDBO();
		$sql = "SELECT COUNT(*) from #__menu_types WHERE `menutype` = 'Digistore-Menu' AND `title` = 'Digistore Menu'";
		$db->setQuery($sql);
		$count = $db->loadResult();
		if(intval($count) == 0){
			$sql = "
				INSERT IGNORE INTO #__menu_types(`menutype`, `title`, `description`)
				VALUES
					('DigiStore-Menu', 'DigiStore Menu', 'DigiStore Menu')
			";
			$db->setQuery($sql);
			if($db->query()){
				$sql = "SELECT `extension_id` FROM #__extensions WHERE `name`='com_digistore' AND `element`='com_digistore'";
				$db->setQuery($sql);
				$db->query();
				$componentid = intval($db->loadResult());
				$sql = "
							INSERT IGNORE INTO `#__menu` (`menutype`, `title`, `alias`, `note`, `path`, `link`, `type`, `published`, `parent_id`, `level`, `component_id`, `checked_out`, `checked_out_time`, `browserNav`, `access`, `img`, `template_style_id`, `params`, `lft`, `rgt`, `home`, `language`, `client_id`)
							VALUES
								('DigiStore-Menu', 'Products', 'products', '', 'products', 'index.php?option=com_digistore&view=digistoreproducts&layout=list', 'component', 1, 1, 1, ".$componentid.", 0, '0000-00-00 00:00:00', 0, 1, '', 0, '{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1,\"page_title\":\"\",\"show_page_heading\":0,\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}', 289, 290, 0, '*', 0),
								('DigiStore-Menu', 'Cart', 'cart', '', 'cart', 'index.php?option=com_digistore&view=digistorecart', 'component', 1, 1, 1, ".$componentid.", 0, '0000-00-00 00:00:00', 0, 1, '', 0, '{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1,\"page_title\":\"\",\"show_page_heading\":0,\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}', 291, 292, 0, '*', 0),
								('DigiStore-Menu', 'Categories', 'categories', '', 'categories', 'index.php?option=com_digistore&view=digistorecategories&layout=listthumbs', 'component', 1, 1, 1, ".$componentid.", 0, '0000-00-00 00:00:00', 0, 1, '', 0, '{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1,\"page_title\":\"\",\"show_page_heading\":0,\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}', 293, 294, 0, '*', 0),
								('DigiStore-Menu', 'My Downloads', 'my-licenses', '', 'my-licenses', 'index.php?option=com_digistore&view=digistorelicenses', 'component', 1, 1, 1, ".$componentid.", 0, '0000-00-00 00:00:00', 0, 1, '', 0, '{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1,\"page_title\":\"\",\"show_page_heading\":0,\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}', 295, 296, 0, '*', 0),
								('DigiStore-Menu', 'My Orders', 'my-orders', '', 'my-orders', 'index.php?option=com_digistore&view=digistoreorders', 'component', 1, 1, 1, ".$componentid.", 0, '0000-00-00 00:00:00', 0, 1, '', 0, '{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1,\"page_title\":\"\",\"show_page_heading\":0,\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}', 297, 298, 0, '*', 0),
								('DigiStore-Menu', 'My Profile', 'my-profile', '', 'my-profile', 'index.php?option=com_digistore&view=digistoreprofile', 'component', 1, 1, 1, ".$componentid.", 0, '0000-00-00 00:00:00', 0, 1, '', 0, '{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1,\"page_title\":\"\",\"show_page_heading\":0,\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}', 299, 300, 0, '*', 0),
								('DigiStore-Menu', 'Login', 'digi-login', '', 'digi-login', 'index.php?option=com_digistore&view=digistoreprofile&layout=login', 'component', 1, 1, 1, ".$componentid.", 0, '0000-00-00 00:00:00', 0, 1, '', 0, '{\"menu-anchor_title\":\"\",\"menu-anchor_css\":\"\",\"menu_image\":\"\",\"menu_text\":1,\"page_title\":\"\",\"show_page_heading\":0,\"page_heading\":\"\",\"pageclass_sfx\":\"\",\"menu-meta_description\":\"\",\"menu-meta_keywords\":\"\",\"robots\":\"\",\"secure\":0}', 301, 302, 0, '*', 0)
							";
				$db->setQuery($sql);
				if (!$db->query()) {
					echo "FIX ME: admin/controller.php, line: ".__LINE__.'<br />';
					echo $db->getErrorMsg();
				}
			}
		}
	}

};

?>
