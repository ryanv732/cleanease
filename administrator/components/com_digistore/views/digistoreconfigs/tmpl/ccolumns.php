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

	<tr>
		<th colspan="2" class="first_title_dgst"><?php echo JText::_('VIEWCONFIGCCOLUMNS');?></th>
	</tr>
	<tr>
	<td colspan="2" align="right">
		<a class="modal digi_video" rel="{handler: 'iframe', size: {x: 750, y: 435}}" href="index.php?option=com_digistore&controller=digistoreAbout&task=vimeo&id=38437543">
			<img src="<?php echo JURI::base(); ?>components/com_digistore/assets/images/icon_video.gif" class="video_img" />
			<?php echo JText::_("COM_DIGISTORE_VIDEO_LAYOUT_CART"); ?>
		</a>
	</td>
</tr>

	<tr>
		<td colspan="2">
			<?php
				echo JText::_("DIGI_STYLE").": ";
			?>
			<select name="shopping_cart_style">
				<option value="0" <?php if($configs->shopping_cart_style == "0"){echo 'selected="selected"';} ?> ><?php echo JText::_("DIGI_INSIDE_TEMPLATE"); ?></option>
				<option value="1" <?php if($configs->shopping_cart_style == "1"){echo 'selected="selected"';} ?> ><?php echo JText::_("DIGI_ISOLATED"); ?></option>
			</select>
			<?php
				echo JHTML::tooltip(JText::_("COM_DIGISTORE_LAYOUTCARTSTYLE_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
			?>
		</td>
	</tr>

	<tr>
		<td colspan="2">
			<?php
				echo JText::_("DIGI_WIDTH").": ";
			?>
			<input type="text" name="cart_width" value="<?php echo trim($configs->cart_width); ?>">&nbsp;&nbsp;&nbsp;
			<select name="cart_width_type">
				<option value="0" <?php if($configs->cart_width_type == 0){echo 'selected="selected"';} ?> >px</option>
				<option value="1" <?php if($configs->cart_width_type == 1){echo 'selected="selected"';} ?> >%</option>
			</select>
			<?php
				echo JHTML::tooltip(JText::_("COM_DIGISTORE_LAYOUTCARTWITH_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
			?>
		</td>
	</tr>

	<tr>
		<td colspan="2">
			<?php
				echo JText::_("DIGI_ALIGNMENT").": ";
			?>
			<select name="cart_alignment">
				<option value="0" <?php if($configs->cart_alignment == 0){echo 'selected="selected"';} ?> ><?php echo JTExt::_("VIEW_LEFT"); ?></option>
				<option value="1" <?php if($configs->cart_alignment == 1){echo 'selected="selected"';} ?> ><?php echo JTExt::_("VIEW_CENTER"); ?></option>
				<option value="2" <?php if($configs->cart_alignment == 2){echo 'selected="selected"';} ?> ><?php echo JTExt::_("VIEW_RIGHT"); ?></option>
			</select>
			<?php
				echo JHTML::tooltip(JText::_("COM_DIGISTORE_LAYOUTCARTALIGNMENT_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
			?>
		</td>
	</tr>

	<tr>
		<td colspan="2">
			<?php
				echo JText::_("DIGI_POPUP_CART_IMAGE").": ";
			?>
			<input type="text" name="cart_popoup_image" value="<?php echo $configs->cart_popoup_image; ?>" size="5" /> px
			<?php
				echo JHTML::tooltip(JText::_("COM_DIGISTORE_LAYOUTCARTPOPUPIMAGE_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
			?>
		</td>
	</tr>

	<tr>
		<td colspan="2">
<input type="checkbox" name="showcam" value="1"  <?php echo ($configs->showcam == '1' ?"checked":"");?> />

		<?php echo JText::_('VIEWCONFIGSHCAM');?>
		<?php
			echo JHTML::tooltip(JText::_("COM_DIGISTORE_LAYOUTCARTQUANTITY_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
		?>
		</td>
	</tr>

	<tr> 
		<td colspan="2">
<input type="checkbox" name="showcpromo" value="1"  <?php echo ($configs->showcpromo == '1' ?"checked":"");?> />

		<?php echo JText::_('VIEWCONFIGSHOWCPROMO');?>
		<?php
			echo JHTML::tooltip(JText::_("COM_DIGISTORE_LAYOUTCARTPROMO_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
		?>
		</td>
	</tr>
	<tr> 
		<td colspan="2">
		<input type="checkbox" name="showcremove" value="1"  <?php echo ($configs->showcremove == '1' ?"checked":"");?> />
		<?php echo JText::_('VIEWCONFIGSHOWCREMOVE');?>
		<?php
			echo JHTML::tooltip(JText::_("COM_DIGISTORE_LAYOUTCARTREMOVE_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
		?>
		</td>
	</tr>
	<tr> 
		<td colspan="2">
		<input type="checkbox" name="showccont" value="1"  <?php echo ($configs->showccont == '1' ?"checked":"");?> />
		<?php echo JText::_('VIEWCONFIGSHOWCCONT');?>
		<?php
			echo JHTML::tooltip(JText::_("COM_DIGISTORE_LAYOUTCARTCONTINUE_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
		?>
		</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>