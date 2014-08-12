<?php
/**
* @package			DigiStore Joomla Extension
 * @author			foobla.com
 * @version			$Revision: 428 $
 * @lastmodified	$LastChangedDate: 2013-11-18 02:23:53 +0100 (Mon, 18 Nov 2013) $
 * @copyright		Copyright (C) 2013 foobla.com. All rights reserved.
* @license			GNU/GPLv3
*/

defined ('_JEXEC') or die ("Go away.");

global $isJ25;
$jv = new JVersion();
$isJ25 = $jv->RELEASE == '2.5';
if ($isJ25) {
	jimport( 'joomla.application.component.controller' );
	jimport('joomla.application.component.model');
	jimport( 'joomla.application.component.view');
	if (!class_exists('obController')) {
		class obController	extends JController {}
	}
	if (!class_exists('obModel')) {
		class obModel		extends JModel {}
	}
	if (!class_exists('obView')) {
		class obView		extends JView {}
	}
} else {
	if (!class_exists('obController')) {
		class obController	extends JControllerLegacy {}
	}
	if (!class_exists('obModel')) {
		class obModel		extends JModelLegacy {}
	}
	if (!class_exists('obView')) {
		class obView		extends JViewLegacy {}
	}
}
$class_sfx = $params->get("moduleclass_sfx", '');

?>
<li id="mod_digistore_cart_wrap" class="mod_digistore_cart<?php echo $class_sfx; ?> mod_digistore_cart_wrap dropdown">
<?php
$my	  			 		 = JFactory::getUser();
$mosConfig_absolute_path =JPATH_BASE; 
$mosConfig_live_site	 =JURI::base();
$database				= JFactory :: getDBO();

$http_host = explode(':', $_SERVER['HTTP_HOST'] );

if( (!empty( $_SERVER['HTTPS'] ) && strtolower( $_SERVER['HTTPS'] ) != 'off' || isset( $http_host[1] ) && $http_host[1] == 443) && substr( $mosConfig_live_site, 0, 8 ) != 'https://' ) {
	$mosConfig_live_site1 = 'https://'.substr( $mosConfig_live_site, 7 );
} else {$mosConfig_live_site1 = $mosConfig_live_site;}


	//show the shopping cart
	jimport('joomla.application.component.model');
	include_once JPATH_SITE.DS.'components'.DS.'com_digistore'.DS.'models'.DS.'digistorecart.php';
	include_once JPATH_SITE.DS.'components'.DS.'com_digistore'.DS.'models'.DS.'digistoretax.php';
	
	if(!class_exists("TabledigistoreConfig")){
		include_once(JPATH_SITE.DS.'components'.DS.'com_digistore'.DS.'tables'.DS.'digistoreconfig.php');
	}
	if(!class_exists("TabledigistorePromo")){
		include_once(JPATH_SITE.DS.'components'.DS.'com_digistore'.DS.'tables'.DS.'digistorepromo.php');
	}

	include_once(JPATH_SITE.DS.'components'.DS.'com_digistore'.DS.'models'.DS.'digistoreconfig.php');
	include_once(JPATH_SITE.DS.'components'.DS.'com_digistore'.DS.'helpers'.DS.'session.php');
	include_once(JPATH_SITE.DS.'components'.DS.'com_digistore'.DS.'helpers'.DS.'helper.php');
	$customer = new digistoreSessionHelper();
	$cart=new digistoreModeldigistoreCart();
	$helper=new digistoreHelper();
	$config=new digistoreModeldigistoreConfig();
	$configs=$config->getConfigs();


	$price_format = '%'.$configs->totaldigits.'.'.$configs->decimaldigits.'f'; 
	$categ_digistore = $params->get( 'digistore_category', '' );
	
	if($categ_digistore != ''){
		$sql = "SELECT id FROM #__digistore_categories WHERE title LIKE '".$categ_digistore."' OR name LIKE '".$categ_digistore."'";
		$database->setQuery($sql);
		$id = $database->loadResult();	
		$cat_url = (isset($configs->continue_shopping_url) && $configs->continue_shopping_url != '')?$configs->continue_shopping_url : "index.php?option=com_digistore&controller=digistoreProducts&task=list&cid=" . $id;
	} else {
		$cat_url = (isset($configs->continue_shopping_url) && $configs->continue_shopping_url != '')?$configs->continue_shopping_url : "index.php?option=com_digistore&controller=digistoreCategories&task=listCategories";		
	}

	$items = $cart->getCartItems ($customer, $configs);
	
	$cart_itemid = digistoreHelper::getCartItemid();
	$and_itemid = "";
	if ($cart_itemid != "0") {
		$and_itemid = "&Itemid=".$cart_itemid;
	}
	
	?>
	<a href="<?php echo JRoute::_('index.php?option=com_digistore&controller=digistoreCart&task=showCart'.$and_itemid, false)?>">
		<i class="fa fa-shopping-cart fa-fw"></i>
		Cart
		<b class="caret"></b>
	</a>
	<ul class="dropdown-menu">
		<li>
			<div class="panel panel-info">
				<div class="panel-heading">
					<?php echo JText::_('MOD_DIGISTORE_CART_HEADING'); ?>
				</div>
				<div class="panel-body text-center">
	<?php
	
	if (count($items) > 0) {
		$module_title = JText::_("_SHOPPING_CART");
		$total = 0;
		$number = 0;
		foreach ($items as $key=>$item) {
			if ($key >= 0) {
				$currency = $item->currency;
				if (!isset($item->discounted_price)) {
					$total += $item->price * $item->quantity;
				} else{
					$total += $item->discounted_price * $item->quantity;
				}
				$number ++;
			}
		}
		
		?>
	
		<?php
			if($number == 1){
				echo $number." ".JText::_("NR_ITEM");
			}
			else{
				echo $number." ".JText::_("NR_ITEMS");
			}
		?>
		<?php echo digistoreHelper::format_price2($total, $currency, true, $configs); ?>
		<?php 
	} else {
		$module_title = '_BUY_NOW';
		if($params->get('modbuynow', '') == '0'){
			?>
			<a href="<?php echo JRoute::_($cat_url); ?>" style="text-align:center; display:block;" class="btn btn-warning">
				<?php echo JText::_('CARTEMPTY');?>
			</a>
			<?php 
		}  elseif($params->get('modbuynow', '') == '1'){
			?>
			<?php echo JText::_('CARTEMPTY'); ?>
			<?php
		} else{
		}
	}
?>
				</div>
				<div class="panel-footer text-right">
					<a class="btn btn-foo" href="<?php echo JRoute::_('index.php?option=com_digistore&controller=digistoreCart&task=showCart'.$and_itemid, false)?>" <?php echo (count($items) == 0) ? 'disabled="disabled"':''; ?>><i class="fa fa-shopping-cart fa-fw"></i> <?php echo JText::_('MOD_DIGISTORE_CART_CHECKOUT'); ?></a>
				</div>
			</div>
		</li>
	</ul>
</li>