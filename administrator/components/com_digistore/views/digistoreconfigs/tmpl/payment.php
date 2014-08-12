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

?>
<fieldset class="adminform">
	<legend><?php echo JText::_('VIEWCONFIGCURRENCY');?></legend>

	<table class="admintable" >

		<tr class="<?php echo "row$k"; $k = 1 - $k; ?>">
			<td colspan="2">
				<?php echo JText::_('VIEWCONFIGPRICEFORMAT');?><br />
				<table cellspacing="0" cellpadding="0" style="vertical-align:bottom;">
					<tr>
						<td style="border:none;"><b>x.</b></td><td style="border:none;">
							<?php echo $this->lists['priceformat'];?>
							<?php
								echo JHTML::tooltip(JText::_("COM_DIGISTORE_PRICEFORMAT_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
							?>
						</td>
					</tr>
				</table>
			</td>
		</tr>

		<tr class="<?php  echo "row$k"; $k = 1 - $k; ?>">
			<td> 
				<?php  echo JText::_('VIEWCONFIGCURRENCY');?>
			</td>
			<td> 
				<table><tr><td style="border:none;">
							<?php
							echo $lists['currency_list'];
							?>
							<?php
								echo JHTML::tooltip(JText::_("COM_DIGISTORE_CURRENCY_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
							?>
				</td><td style="border:none;">
							<?php
							echo $lists['currency_support'];
							?>
							</td></tr>
				</table>
			</td>
		</tr>

		<tr class="<?php  echo "row$k"; $k = 1 - $k; ?>">
			<td>
				<?php  echo JText::_('DIGISTORE_CURRENCY_DISPLAY_POSITION');?>
			</td>
			<td>
				<table><tr><td style="border:none;">
							<?php
							echo $lists['currency_display_position_list'];
							?>
							<?php
								echo JHTML::tooltip(JText::_("COM_DIGISTORE_POSITION_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
							?>
				</td><td style="border:none;">
							<?php
							echo $lists['currency_display_position_desc'];
							?></td></tr>
				</table>
			</td>
		</tr>

		<tr class="<?php  echo "row$k"; $k = 1 - $k;?>" style="display:none;">
			<td>
				<?php  echo JText::_('VIEWCONFIGIMGFORCUR');?>
			</td>
			<td>
				<input name="usecimg" type="checkbox" value="1" <?php echo ($configs->usecimg==0?"":"checked"); ?>/>
			</td>
		</tr>
		
		<tr>
			<td>
				<?php echo JText::_('COM_DIGISTORE_THOUSANDS_GROUP_SYMBOL'); ?>
			</td>
			<td>
				<input type="text" name="thousands_group_symbol" value="<?php echo $configs->thousands_group_symbol; ?>"/>
				<?php echo JHTML::tooltip(JText::_("COM_DIGISTORE_THOUSANDS_GROUP_SYMBOL_DESC"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip'); ?>
			</td>
		</tr>
	</table>
</fieldset>
