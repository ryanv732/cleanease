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

JHTML::_('behavior.tooltip');

$document = JFactory::getDocument();
$document->addStyleSheet("components/com_digistore/assets/css/digistore.css");

?>
<fieldset class="adminform">
	<legend><?php echo JText::_( 'Select' ); ?></legend>

	<input style="float:none;" id="newuser" type="radio" name="usertype" value="1" onclick="window.location='<?php echo JURI::root()."administrator/index.php?option=com_digistore&controller=digistoreOrders&task=checkcreateuser&usertype=1"; ?>'"/>
	<label style="float:none; display: inline;" for="newuser"><?php echo JText::_("COM_DIGI_NEW_USER"); ?></label> 
	<?php
		echo JHTML::tooltip(JText::_("COM_DIGISTORE_ORDERNEWUSER_TIP"), '', '',  "<img style=\"float:none; margin:0px;\" src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
	?>
	<br/>

	<input style="float:none;" id="newcustomer" type="radio" name="usertype" value="2" onclick="window.location='<?php echo JURI::root()."administrator/index.php?option=com_digistore&controller=digistoreOrders&task=checkcreateuser&usertype=2"; ?>'"/>
	<label style="float:none; display: inline;" for="newcustomer"><?php echo JText::_("COM_DIGI_NEW_CUSTOMER"); ?></label>
	<?php
		echo JHTML::tooltip(JText::_("COM_DIGISTORE_ORDERNEWCUSTOMER_TIP"), '', '',  "<img style=\"float:none; margin:0px;\" src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
	?>
	<br/>

	<input style="float:none;" id="existingcustomer" type="radio" name="usertype" value="3" onclick="window.location='<?php echo JURI::root()."administrator/index.php?option=com_digistore&controller=digistoreOrders&task=checkcreateuser&usertype=3"; ?>'"/>
	<label style="float:none; display: inline;" for="existingcustomer"><?php echo JText::_("COM_DIGI_EXISTING_CUSTOMER"); ?></label>
	<?php
		echo JHTML::tooltip(JText::_("COM_DIGISTORE_ORDEREXISTINGCUSTOMER_TIP"), '', '',  "<img style=\"float:none; margin:0px;\" src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
	?>
	<br/>

	<form id="adminForm" name="adminForm" action="index.php">
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="option" value="com_digistore"/>
	<input type="hidden" name="controller" value="digistoreOrders"/>
	</form>
</fieldset>
