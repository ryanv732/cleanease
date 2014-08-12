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

$my	= JFactory::getUser();
$showp = $params->get("show_profile", 1);
$showl = $params->get("show_lic", 1);
$showo = $params->get("show_ord", 1);
$invisible = 'style="display:none"';
$Itemid = JRequest::getVar("Itemid", "0");
$db = JFactory::getDBO();
$sql = "select afterpurchase from #__digistore_settings";
$db->setQuery($sql);
$after = $db->loadResult();

?>
<table class="digistore_manage" width="100%" border="0" cellspacing="0" cellpadding="1" align="center">
<?php
	if($my->get('id')){
?>
		<tr <?php if($showp != 1){ echo $invisible;} ?> >
			<td align="left">
				<a href="<?php echo JRoute::_("index.php?option=com_digistore&view=digistoreprofile&Itemid=".$Itemid); ?>"><?php echo JText::_('EDITACC')?></a>
			</td>
		</tr>
		<tr <?php if($showl != 1) echo $invisible; ?> >
			<td align="left">
				<a href="<?php echo JRoute::_("index.php?option=com_digistore&view=digistorelicenses&Itemid=".$Itemid); ?>"><?php echo JText::_('MYLICANDDLS');?></a>
			</td>
		</tr>
		
		<tr <?php if($showo != 1) echo $invisible; ?> >
			<td align="left">
				<a href="<?php echo JRoute::_("index.php?option=com_digistore&view=digistoreorders&Itemid=".$Itemid); ?>"><?php echo JText::_('MYORDERS');?></a>
			</td>
		</tr>

<?php 
	}
	else{
		if($after != 1){
?>
			<tr>
				<td align="left">
					<a href="<?php echo JRoute::_('index.php?option=com_digistore&controller=digistoreProfile&task=login&returnpage=licenses'."&Itemid=".$Itemid);?>"><?php echo JText::_('LOGINTOVIEW');?></a>
				</td>
			</tr>
<?php
		}
		else{
?>
			<tr>
				<td align="left">
					<a href="<?php echo JRoute::_('index.php?option=com_digistore&controller=digistoreProfile&task=login&returnpage=orders'."&Itemid=".$Itemid);?>"><?php echo JText::_('LOGINTOVIEW');?></a>
				</td>
			</tr>
<?php 
		}
	}
?>
</table>