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

$document = JFactory::getDocument();
$document->addStyleSheet("components/com_digistore/assets/css/digistore.css");

// $first = JRequest::getVar("first", "");
// if($first == "true"){
// 	$text = "";
// 	if($this->isCurlInstalled() == true){
// 		$data = 'http://www.ijoomla.com/annoucements/first_install_digistore.txt';
// 		$ch = @curl_init($data);
// 		@curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// 		@curl_setopt($ch, CURLOPT_TIMEOUT, 10);
// 		$text = @curl_exec($ch);
// 	}
// 	echo $text;
// }

?>
<img src="components/com_digistore/assets/images/logo.png" />

<table class="adminform table span12">
	<tr>
		<td >
			<div id="cpanel" class="row-fluid">
		
				<div class="span3">
					<div class="icon">
		
						<a href="<?php echo JRoute::_("index.php?option=com_digistore&controller=digistoreConfigs&task2=general");?>">
							<img src="components/com_digistore/assets/images/icons/settings.png"
							alt="<?php  echo JText::_('VIEWDSADMINSETTINGS');?>" align="middle" name="" border="0" />
							<span><?php  echo JText::_('VIEWDSADMINSETTINGS');?></span>
						</a>
					</div>
				</div>
				<div class="span3">
					<div class="icon">
		
						<a href="<?php echo JRoute::_("index.php?option=com_digistore&controller=digistoreConfigs&task2=payments");?>">
							<img src="components/com_digistore/assets/images/icons/payments.png"
							alt="<?php  echo JText::_('VIEWDSADMINPAYMENTS');?>" align="middle" name="" border="0" />
							<span><?php  echo JText::_('VIEWDSADMINPAYMENTS');?></span>
						</a>
					</div>
				</div>
				<div class="span3">
					<div class="icon">
		
						<a href="<?php echo JRoute::_("index.php?option=com_digistore&controller=digistoreConfigs&task2=content");?>">
							<img src="components/com_digistore/assets/images/icons/content.png"
							alt="<?php  echo JText::_('VIEWDSADMINCONTENT');;?>" align="middle" name="" border="0" />
							<span><?php  echo JText::_('VIEWDSADMINCONTENT');;?></span>
						</a>
					</div>
				</div>
				<?php /* ?>
				<div class="span3">
					<div class="icon">
						<a href="<?php echo JRoute::_("index.php?option=com_digistore&controller=digistoreLanguages");?>">
							<img src="components/com_digistore/assets/images/icons/language.png"
							alt="<?php  echo JText::_('VIEWDSADMINLANGUAGE');?>" align="middle" name="" border="0" />
							<span><?php  echo JText::_('VIEWDSADMINLANGUAGE');?></span>
						</a>
					</div>
				</div>
				<?php */ ?>
				<div class="span3">
					<div class="icon">
						<a href="<?php echo JRoute::_("index.php?option=com_digistore&controller=digistoreCategories");?>">
							<img src="components/com_digistore/assets/images/icons/packages.png"
							alt="<?php  echo JText::_('VIEWDSADMINCATEGORIES');?>" align="middle" name="" border="0" />
							<span><?php  echo JText::_('VIEWDSADMINCATEGORIES');?></span>
						</a>
					</div>
				</div>
				<div class="span3">
					<div class="icon">
						<a href="<?php echo JRoute::_("index.php?option=com_digistore&controller=digistoreProducts");?>">
							<img src="components/com_digistore/assets/images/icons/packages.png"
							alt="<?php  echo JText::_('VIEWDSADMINPRODUCTS');?>" align="middle" name="" border="0" />
							<span><?php  echo JText::_('VIEWDSADMINPRODUCTS');?></span>
						</a>
					</div>
				</div>
		
				 <div class="span3">
					<div class="icon">
		
						<a href="<?php echo JRoute::_("index.php?option=com_digistore&controller=digistoreLicenses");?>">
							<img src="components/com_digistore/assets/images/icons/zones.png"
							alt="<?php  echo JText::_('VIEWDSADMINLICENCES');?>" align="middle" name="" border="0" />
							<span><?php  echo JText::_('VIEWDSADMINLICENCES');?></span>
						</a>
					</div>
				</div>
				<div class="span3">
					<div class="icon">
		
						<a href="<?php echo JRoute::_("index.php?option=com_digistore&controller=digistoreStats");?>">
							<img src="components/com_digistore/assets/images/icons/reports.png"
							alt="<?php  echo JText::_('VIEWDSADMINSTATS');?>" align="middle" name="" border="0" />
							<span><?php  echo JText::_('VIEWDSADMINSTATS');?></span>
						</a>
					</div>
				</div>
				<div class="span3">
					<div class="icon">
		
						<a href="<?php echo JRoute::_("index.php?option=com_digistore&controller=digistoreCustomers");?>">
							<img src="components/com_digistore/assets/images/icons/customers.png"
							alt="<?php  echo JText::_('VIEWDSADMINCUSTOMERS');?>" align="middle" name="" border="0" />
							<span><?php  echo JText::_('VIEWDSADMINCUSTOMERS');?></span>
						</a>
					</div>
				</div>
		
				<div class="span3">
					<div class="icon">
		
						<a href="<?php echo JRoute::_("index.php?option=com_digistore&controller=digistoreOrders");?>">
							<img src="components/com_digistore/assets/images/icons/orders.png"
							alt="<?php  echo JText::_('VIEWDSADMINORDERS');?>" align="middle" name="" border="0" />
							<span><?php  echo JText::_('VIEWDSADMINORDERS');?></span>
						</a>
					</div>
				</div>
			</div>
		</td>
	</tr>
</table>