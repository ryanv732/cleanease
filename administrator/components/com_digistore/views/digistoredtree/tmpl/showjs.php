<?php
/**
* @package			DigiStore Joomla Extension
 * @author			foobla.com
 * @version			$Revision: 432 $
 * @lastmodified	$LastChangedDate: 2013-11-18 04:29:45 +0100 (Mon, 18 Nov 2013) $
 * @copyright		Copyright (C) 2013 foobla.com. All rights reserved.
* @license			GNU/GPLv3
*/

defined ('_JEXEC') or die ("Go away.");

$task = JRequest::getVar("task", "");

if($task == "export"){
	return true;
}
?>

var d = new dTree('d');

d.add(0,-1,'&nbsp;<?php echo JText::_('VIEWTREEDSCP');?>','index.php?option=com_digistore','','','components/com_digistore/assets/images/icons/small/a.png');

d.add(840,0,'&nbsp;<?php echo JText::_('VIEWTREESETTINGMANAGER');?>','','VIEWDSADMINSETTINGS','','components/com_digistore/assets/images/icons/small/settings.png','components/com_digistore/assets/images/icons/small/settings.png');

d.add(841,840,'&nbsp;<?php echo JText::_('VIEWTREEGENERAL');?>','index.php?option=com_digistore&controller=digistoreConfigs&task2=general','','','components/com_digistore/assets/images/icons/small/settings.png','components/com_digistore/assets/images/icons/small/settings.png');
d.add(842,840,'&nbsp;<?php echo JText::_('VIEWCONFIGCURRENCY');?>','index.php?option=com_digistore&controller=digistoreConfigs&task2=payments','','','components/com_digistore/assets/images/icons/small/payments.png','components/com_digistore/assets/images/icons/small/payments.png');
d.add(842,840,'&nbsp;<?php echo JText::_('VIEWTREEEMAILS');?>','index.php?option=com_digistore&controller=digistoreConfigs&task2=email','','','components/com_digistore/assets/images/icons/small/settings.png','components/com_digistore/assets/images/icons/small/settings.png');
d.add(846,840,'&nbsp;<?php echo JText::_('VIEWTREECONTENT');?>','index.php?option=com_digistore&controller=digistoreConfigs&task2=content','','','components/com_digistore/assets/images/icons/small/orders.png');
d.add(849,840,'&nbsp;<?php echo JText::_('VIEWTREESTORE');?>','index.php?option=com_digistore&controller=digistoreConfigs&task2=store','','','components/com_digistore/assets/images/icons/small/orders.png');
d.add(847,840,'&nbsp;<?php echo JText::_('VIEWTREETAX');?>','index.php?option=com_digistore&controller=digistoreConfigs&task2=tax','','','components/com_digistore/assets/images/icons/small/payments.png');
d.add(848,840,'&nbsp;<?php echo JText::_('VIEWTREELAYOUTS');?>','index.php?option=com_digistore&controller=digistoreConfigs&task2=layout','','','components/com_digistore/assets/images/icons/small/payments.png');

//d.add(810,0,'&nbsp;<?php echo JText::_('VIEWTREEMANAGERS');?>','','','','components/com_digistore/assets/images/icons/small/packages.png','components/com_digistore/assets/images/icons/small/packages.png');
d.add(811,0,'&nbsp;<?php echo JText::_('VIEWTREECATEGORIES');?>','index.php?option=com_digistore&controller=digistoreCategories&section=com_digistore_product','','','components/com_digistore/assets/images/icons/small/packages.png','components/com_digistore/assets/images/icons/small/packages.png');

d.add(812,0,'&nbsp;<?php echo JText::_('VIEWTREEPRODUCTS');?>','index.php?option=com_digistore&controller=digistoreProducts','<?php echo JText::_('VIEWTREEPRODUCTS');?>','','components/com_digistore/assets/images/icons/small/packages.png','components/com_digistore/assets/images/icons/small/packages.png','components/com_digistore/assets/images/icons/small/packages.png');
d.add(8121,812,'&nbsp;<?php echo JText::_('DIGISTORE_NEWPRODUCT_DOWNLOADABLE');?>','index.php?option=com_digistore&controller=digistoreProducts&task=add&producttype=0','<?php echo JText::_('VIEWPRODPRODTYPEDNR');?>','','components/com_digistore/assets/images/icons/small/packages.png','components/com_digistore/assets/images/icons/small/packages.png','components/com_digistore/assets/images/icons/small/packages.png');
d.add(8122,812,'&nbsp;<?php echo JText::_('DIGISTORE_NEWPRODUCT_DOWNLOADABLE_DOMAIN');?>','index.php?option=com_digistore&controller=digistoreProducts&task=add&producttype=1','<?php echo JText::_('VIEWPRODPRODTYPEDR');?>','','components/com_digistore/assets/images/icons/small/packages.png','components/com_digistore/assets/images/icons/small/packages.png','components/com_digistore/assets/images/icons/small/packages.png');
d.add(8123,812,'&nbsp;<?php echo JText::_('DIGISTORE_NEWPRODUCT_SHIPABLE');?>','index.php?option=com_digistore&controller=digistoreProducts&task=add&producttype=2','<?php echo JText::_('VIEWPRODPRODTYPESP');?>','','components/com_digistore/assets/images/icons/small/packages.png','components/com_digistore/assets/images/icons/small/packages.png','components/com_digistore/assets/images/icons/small/packages.png');
d.add(8124,812,'&nbsp;<?php echo JText::_('DIGISTORE_NEWPRODUCT_PACKAGE');?>','index.php?option=com_digistore&controller=digistoreProducts&task=add&producttype=3','<?php echo JText::_('VIEWPRODPRODTYPEPAK');?>','','components/com_digistore/assets/images/icons/small/packages.png','components/com_digistore/assets/images/icons/small/packages.png','components/com_digistore/assets/images/icons/small/packages.png');
d.add(8125,812,'&nbsp;<?php echo JText::_('DIGISTORE_NEWPRODUCT_SERVICE');?>','index.php?option=com_digistore&controller=digistoreProducts&task=add&producttype=4','<?php echo JText::_('VIEWPRODPRODTYPESERV');?>','','components/com_digistore/assets/images/icons/small/packages.png','components/com_digistore/assets/images/icons/small/packages.png','components/com_digistore/assets/images/icons/small/packages.png');

d.add(912,0,'&nbsp;<?php echo JText::_('VIEWTREEPRODUCTCLASSES');?>','index.php?option=com_digistore&controller=digistoreProductClasses','','','components/com_digistore/assets/images/icons/small/packages.png');
d.add(815,0,'&nbsp;<?php echo JText::_('VIEWTREECUSTOMERS');?>','index.php?option=com_digistore&controller=digistoreCustomers','','','components/com_digistore/assets/images/icons/small/customers.png','components/com_digistore/assets/images/icons/small/customers.png');
d.add(814,0,'&nbsp;<?php echo JText::_('VIEWTREEORDERS');?>','index.php?option=com_digistore&controller=digistoreOrders','','','components/com_digistore/assets/images/icons/small/orders.png','components/com_digistore/assets/images/icons/small/orders.png');
d.add(813,0,'&nbsp;<?php echo JText::_('VIEWTREELICENCES');?>','index.php?option=com_digistore&controller=digistoreLicenses','','','components/com_digistore/assets/images/icons/small/zones.png','components/com_digistore/assets/images/icons/small/zones.png');
<?php
	$db = JFactory::getDBO();
	$sql = "SHOW COLUMNS FROM #__digistore_settings";
	$db->setQuery($sql);
	$result = $db->loadColumn();
	if(!in_array("newinstall", $result) OR 1==1){

?>
		d.add(816,0,'&nbsp;<?php echo JText::_('VIEWTREEATTR');?>','index.php?option=com_digistore&controller=digistoreAttributes','','','components/com_digistore/assets/images/icons/small/reports.png','components/com_digistore/assets/images/icons/small/reports.png');
<?php
	}
?>

//d.add(819,0,'&nbsp;<?php echo JText::_('VIEWTREELANGUAGES');?>','index.php?option=com_digistore&controller=digistoreLanguages','','','components/com_digistore/assets/images/icons/small/language.png','components/com_digistore/assets/images/icons/small/language.png');
d.add(820,0,'&nbsp;<?php echo JText::_('VIEWTREEPROMO');?>','index.php?option=com_digistore&controller=digistorePromos','','','components/com_digistore/assets/images/icons/small/language.png','components/com_digistore/assets/images/icons/small/language.png');

/**
   Subscriptions
 */
d.add(831,0,'&nbsp;<?php echo JText::_('VIEWTREESUBSCRIP');?>','','','','components/com_digistore/assets/images/icons/small/zones.png','components/com_digistore/assets/images/icons/small/zones.png');
d.add(832,831,'&nbsp;<?php echo JText::_('VIEWTREEPLANS');?>','index.php?option=com_digistore&controller=digistorePlans','Subscription Plans','','components/com_digistore/assets/images/icons/small/zones.png');
d.add(834,831,'&nbsp;<?php echo JText::_('VIEWTREEEMAILREMINDER');?>','index.php?option=com_digistore&controller=digistoreEmailreminders','Email reminders','','components/com_digistore/assets/images/icons/small/other_components.png');

/**
  Logs
 */

d.add(833,0,'&nbsp;<?php echo JText::_('VIEWTREELOGS');?>','','','','components/com_digistore/assets/images/icons/small/support.png','components/com_digistore/assets/images/icons/small/support.png');
d.add(8331,833,'&nbsp;<?php echo JText::_('VIEWTREEPSYSTEMEMAILS');?>','index.php?option=com_digistore&controller=digistoreLogs&task=systememails','System Emails','','components/com_digistore/assets/images/icons/small/forum.png');
d.add(8332,833,'&nbsp;<?php echo JText::_('VIEWTREEDOWNLOAD');?>','index.php?option=com_digistore&controller=digistoreLogs&task=download','Download','','components/com_digistore/assets/images/icons/small/manual.png');
d.add(8333,833,'&nbsp;<?php echo JText::_('VIEWTREEPURCHASES');?>','index.php?option=com_digistore&controller=digistoreLogs&task=purchases','Purchases','','components/com_digistore/assets/images/icons/small/payments.png');


d.add(800,0,'&nbsp;<?php echo JText::_('VIEWTREETAX');?>','','','','components/com_digistore/assets/images/icons/small/support.png','components/com_digistore/assets/images/icons/small/support.png');
d.add(801,800,'&nbsp;<?php echo JText::_('VIEWTREEPRODTAXCLASS');?>','index.php?option=com_digistore&controller=digistoreTaxProductClasses','Product tax classes','','components/com_digistore/assets/images/icons/small/forum.png');
d.add(802,800,'&nbsp;<?php echo JText::_('VIEWTREECUSTTAXCLASS');?>','index.php?option=com_digistore&controller=digistoreTaxCustomerClasses','Customer tax classes','','components/com_digistore/assets/images/icons/small/manual.png');
d.add(803,800,'&nbsp;<?php echo JText::_('VIEWTREETAXRATE');?>','index.php?option=com_digistore&controller=digistoreTaxRates','Tax rates','','components/com_digistore/assets/images/icons/small/other_components.png');
d.add(804,800,'&nbsp;<?php echo JText::_('VIEWTREETAXRULE');?>','index.php?option=com_digistore&controller=digistoreTaxRules','Tax rules','','components/com_digistore/assets/images/icons/small/campaigns.png');

d.add(851,0,'&nbsp;<?php echo JText::_('VIEWTREESTATS');?>','index.php?option=com_digistore&controller=digistoreStats','','','components/com_digistore/assets/images/icons/small/reports.png','components/com_digistore/assets/images/icons/small/reports.png');

d.add(830,0,'&nbsp;<?php echo JText::_('VIEWTREEABOUT');?>','index.php?option=com_digistore&controller=digistoreAbout','About DigiStore','','components/com_digistore/assets/images/icons/small/about.png');

function putDtree () {
	if(document.getElementById("dtreespan")){
		document.getElementById("dtreespan").innerHTML = d;
	}
}

window.onload = putDtree;
