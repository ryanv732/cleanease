<?php
/**
* @package			DigiStore Joomla Extension
 * @author			foobla.com
 * @version			$Revision: 355 $
 * @lastmodified	$LastChangedDate: 2013-10-11 13:18:38 +0200 (Fri, 11 Oct 2013) $
 * @copyright		Copyright (C) 2013 foobla.com. All rights reserved.
* @license			GNU/GPLv3
*/

defined ('_JEXEC') or die ("Go away.");
defined ('DS') or define('DS', DIRECTORY_SEPARATOR);
global $isJ25;

/*
include dirname(dirname(__FILE__)).DS.'script.php';
$t = new com_DigistoreInstallerScript();
$t->updateAllTables2_0_0();
*/
$jv = new JVersion();
$isJ25 = $jv->RELEASE == '2.5';
if ($isJ25) {
	jimport('joomla.application.component.controller');
	jimport('joomla.application.component.model');
	jimport('joomla.application.component.view');
	class obController	extends JController {}
	class obModel		extends JModel {}
	class obView		extends JView {}
}else{
	class obController	extends JControllerLegacy {}
	class obModel		extends JModelLegacy {}
	class obView		extends JViewLegacy {}
}
// error_reporting("0");
// ini_set('display_errors','Off');
JHtml::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'html');

JHtml::_( 'behavior.modal' );

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_digistore'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

function updateLicenses()
{
	$db = JFactory::getDBO();
	$sql = "SELECT count(*) FROM #__digistore_licenses WHERE `purchase_date` <> '0000-00-00 00:00:00'";
	$db->setQuery($sql);
	$db->query();
	$count = $db->loadResult();
	if($count == 0){
		$sql = "SELECT o.`order_date`, l.`id` FROM #__digistore_orders o, #__digistore_licenses l WHERE o.id=l.orderid";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		if(isset($result) && count($result) > 0){
			foreach($result as $key=>$value){
				$date_string = date("Y-m-d H:i:s", $value["order_date"]);
				$sql = "UPDATE #__digistore_licenses SET `purchase_date`='".$date_string."' WHERE id=".intval($value["id"]);
				$db->setQuery($sql);
				$db->query();
			}
		}
	}
}

if(!class_exists("Services_JSON")){
	require_once (JPATH_COMPONENT.DS.'libs'.DS. 'JSON.php');
}

require_once(JPATH_COMPONENT.DS.'controller.php');
require_once(JPATH_COMPONENT.DS.'helpers'.DS.'helper.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'log.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'config.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'image.php');

$controller = JRequest::getWord('controller');
define ("myDS", "/");

updateLicenses();

if($controller)
{
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
	if(file_exists($path))
	{
		require_once($path);
	}
	else
	{
	 	$controller = '';
	}
}

$classname = "digistoreAdminController".$controller;
$ajax_req = JRequest::getVar("no_html", 0, "request");
$task = JRequest::getVar("task", "");
if(!$ajax_req & $task != "export" & $task != "customerexport"){
?>
	<table width="100%">
		<tr>
			<td valign="top" width="18%">
<?php
}

$controller = new $classname();
// $task = JRequest::getWord('task');
$controller->execute ($task);
$controller->redirect();

if(!$ajax_req & $task != "export" & $task != "customerexport"){
?>
			</td>
		</tr>
	</table>
<?php
}elseif($ajax_req){
exit();
}

?>