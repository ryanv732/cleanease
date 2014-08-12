<?php
/**
* @package			DigiStore Joomla Extension
 * @author			foobla.com
 * @version			$Revision: 422 $
 * @lastmodified	$LastChangedDate: 2013-11-16 11:37:09 +0100 (Sat, 16 Nov 2013) $
 * @copyright		Copyright (C) 2013 foobla.com. All rights reserved.
* @license			GNU/GPLv3
*/

defined ('_JEXEC') or die ("Go away.");

/*error_reporting(0);
ini_set('display_errors','Off');*/

defined ('DS') or define('DS', DIRECTORY_SEPARATOR);
global $isJ25;
$jv = new JVersion();
$isJ25 = $jv->RELEASE == '2.5';
if ($isJ25) {
	jimport( 'joomla.application.component.controller' );
	jimport('joomla.application.component.model');
	jimport( 'joomla.application.component.view');
	class obController	extends JController {}
	class obModel		extends JModel {}
	class obView		extends JView {}
}else{
	class obController	extends JControllerLegacy {}
	class obModel		extends JModelLegacy {}
	class obView		extends JViewLegacy {}
}

// Config Singelton
require_once( JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'config.php' );
// Debug and Log helper
require_once( JPATH_COMPONENT_SITE . DS . 'helpers' . DS . 'helper.php' );
require_once( JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'log.php' );
// Image Helper
require_once( JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'image.php' );
// Google Analitics
require_once( JPATH_COMPONENT_SITE . DS . 'helpers' . DS . 'google.php' );

//cron jobs
$cronparam = JRequest::getVar('cron','');
if ($cronparam != '') {
	echo "Cron execute";
	require_once( JPATH_COMPONENT_SITE . DS . 'helpers' . DS . 'cronjobs.php' );
	cronjobs();
	exit;
}

require_once( JPATH_COMPONENT_SITE . DS . 'controller.php' );
require_once( JPATH_COMPONENT_SITE . DS . 'helpers' . DS . 'session.php' );
$controller = JRequest::getCmd('controller');
$task = JRequest::getCmd('task');
if( $controller != 'digistoreCart' && $task != 'getCartItem'){
	echo '<div class="digistore">';
	digistoreHelper::createBreacrumbs();
	echo '</div>';
}

$msg = JRequest::getVar ('product_added', '', 'request');
if($msg){
	$cart_itemid = digistoreHelper::getCartItemid();
	$and_itemid = "";
	if($cart_itemid != ""){
		$and_itemid = "&Itemid=".$cart_itemid;
	}
	$msg = "";
	$products = urldecode(JRequest::getVar('product_added', '', 'request'));
	$array_products = explode(",", $products);
	$link = JRoute::_("index.php?option=com_digistore&controller=digistoreCart&task=showCart".$and_itemid);
	$msg = JText::_("DSFOLLOWINGPRODSADDED");
?>
	<div id="system-message-container">
		<dl id="system-message">
			<dt class="message">Message</dt>
			<dd class="message message">
				<ul>
					<li>
						<?php echo $msg; ?>
				   	</li>
					<li>
						<?php
						if(isset($array_products) && count($array_products) > 0){
							echo "<br/>";
							foreach($array_products as $key=>$value){
								if(trim($value) != ""){
									echo '<li class="list-products-msg"> '.trim($value)."</li>";
								}
							}
						}
						?>
					</li>
					<li>
						<?php
							echo "<br/>";
							echo JText::_("DIGI_CLICK")." <a href='".$link."' style='color:gray;'>".JText::_("DSHERE")."</a> ".JText::_("DSTOVISIT");
						?>
					</li>
				</ul>
			</dd>
		</dl>
	</div>
<?php
}

//normal linking
$controller = JRequest::getWord('controller');
$controller = ($controller == 'digistorecart' ? 'digistoreCart' : $controller);
$task = JRequest::getWord('task');

if($task=='get_cursym'){
	digistoreHelper::get_cursym();
	die();
}

//joomla linking
$view = JRequest::getWord('view');
$layout = JRequest::getWord('layout');
// To dispaly product details with view and layout sets
if($layout == 'viewproduct'){
	$task = 'view';
}

if($layout == 'summary'){
	$task = 'summary';
}

if($layout == 'login'){
	$task = 'login';
}

if(strlen(trim($view)) > 0 && strlen(trim($controller)) < 1){
	$view_to_controller = array("digistorecart" => "digistoreCart",
			"digistorelicenses" => "digistoreLicenses",
			"digistoreorders" => "digistoreOrders",
			"digistorecategories" => "digistoreCategories",
			"digistoreproducts" => "digistoreProducts",
			"digistoreprofile" => "digistoreProfile");
	$layout_to_task = array("");
	$controller = @$view_to_controller[strtolower($view)];
}

if($controller == 'digistoreorders')
{
	$controller = 'digistoreOrders';
}

if($controller){
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
	if(file_exists($path)){
		require_once($path);
	}
	else{
	}
}
else{
	$controller = 'digistoreCategories';
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
	if(file_exists($path)){
		require_once($path);
	}
	else{
	}
}

JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . DS . 'tables');

$classname = "digistoreController".$controller;
$ajax_req = JRequest::getVar("no_html", 0, "request");
$controller = new $classname();
$controller->execute ($task);
$controller->redirect();

?>