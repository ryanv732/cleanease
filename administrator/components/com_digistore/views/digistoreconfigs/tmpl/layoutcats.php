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
	<td class="header_zone" colspan="2">
		<?php
			echo JText::_("HEADER_LAYOUT_SETTINGS");
		?>
	</td>
</tr>

<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<th colspan="2" class="first_title_dgst"><?php echo JText::_('VIEWCONFIGCATAPP');?></th>
</tr>

<tr>
	<td colspan="3" align="right">
		<a class="modal digi_video" rel="{handler: 'iframe', size: {x: 750, y: 435}}" href="index.php?option=com_digistore&controller=digistoreAbout&task=vimeo&id=38437585">
			<img src="<?php echo JURI::base(); ?>components/com_digistore/assets/images/icon_video.gif" class="video_img" />
			<?php echo JText::_("COM_DIGISTORE_VIDEO_CATEG_MANAGER"); ?>
		</a>
	</td>
</tr>

<tr>
	<td class="digispacer" colspan="2"></td>
</tr>
<tr>
	<td colspan="2">
		<label class="radio">
			<input type="radio" name="catlayoutstyle" id="catlayoutstyle0" <?php echo ($configs->catlayoutstyle == '0' ?"checked":"");?> value="0" />
			<?php  echo JText::_('VIEWCONFIGPLAINLIST'); ?>
			<?php
				echo JHTML::tooltip(JText::_("COM_DIGISTORE_LAYOUTCATSTYLE1_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
			?>
		</label>
	</td>
</tr>
<tr>
	<td colspan="2">
		<label class="radio">
			<input type="radio" name="catlayoutstyle" id="catlayoutstyle1" <?php echo ($configs->catlayoutstyle == '1' ?"checked":"");?> value="1" />
			<?php  echo JText::_('VIEWCONFIGPTHUMBS'); ?>
			<?php
				echo JHTML::tooltip(JText::_("COM_DIGISTORE_LAYOUTCATSTYLE2_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
			?>
		</label>
	</td>
</tr>
<tr>
	<td align="left" colspan="2" style="padding: 0em 2.5em;">
		<div style="padding-bottom:0.5em;">
			<input name="catlayoutcol" type="text" value="<?php echo $configs->catlayoutcol; ?>" style="width:30px;" /> 
			<?php echo JText::_('VIEWCONFIGCATCOLPP');?>
		</div>
		<div>
			<input name="catlayoutrow" type="text" value="<?php echo $configs->catlayoutrow; ?>" style="width:30px;" />
			<?php echo JText::_('VIEWCONFIGCATROWPP');?>
		</div>
		<div>
			<?php echo JText::_('VIEWCONFIGSHOWVIEWPRODUCTS'); ?>
			<select name="showviewproducts">
				<option value="1" <?php echo ($configs->showviewproducts == '1') ? "selected" : '' ; ?>><?php echo JText::_('DSYES'); ?></option>
				<option value="0" <?php echo ($configs->showviewproducts == '0') ? "selected" : '' ; ?>><?php echo JText::_('DSNO'); ?></option>
			</select>
		</div>
	</td>
</tr>
<tr>
	<td colspan="2">
		<label class="radio">
			<input type="radio" name="catlayoutstyle" id="catlayoutstyle2" <?php echo ($configs->catlayoutstyle == '2' ?"checked":"");?> value="2" />
			<?php  echo JText::_('VIEWCONFIGPDROPDOWN'); ?>
			<?php
				echo JHTML::tooltip(JText::_("COM_DIGISTORE_LAYOUTCATSTYLE3_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
			?>
		</label>
	</td>
</tr>

<tr>
	<td colspan="2">&nbsp;</td>
</tr>


<tr>
	<th colspan="2" class="second_title_dgst"><?php echo JText::_('VIEWCONFIGCATEGIMAGE');?></th>
</tr>

<tr>
	<td colspan="2">
		<table>
			<tr>
				<td width="15%">
					<?php echo JText::_("VIEWIMAGESIZE"); ?>
				</td>
				<td width="10%">
					<input type="text" name="catlayoutimagesize" value="<?php echo $configs->catlayoutimagesize; ?>" />
				</td>
				<td width="2">
					px
				</td>
				<td>
					<select name="catlayoutimagetype">
						<option value="0" <?php if($configs->catlayoutimagetype == "0"){echo 'selected="selected"'; } ?> ><?php echo JText::_("VIEWHEIGH"); ?></option>
						<option value="1" <?php if($configs->catlayoutimagetype == "1"){echo 'selected="selected"'; } ?> ><?php echo JText::_("VIEWWIDE"); ?></option>
					</select>
					<?php
						echo JHTML::tooltip(JText::_("COM_DIGISTORE_LAYOUTCATSIMAGETYPE_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
					?>
				</td>
			</tr>
		</table>
	</td>
</tr>

<tr>
	<td colspan="2">
		<table>
			<tr>
				<td width="15%">
					<?php echo JText::_("VIEW_DESCRIPTION_LENGTH"); ?>
				</td>
				<td width="10%">
					<input type="text" name="catlayoutdesclength" value="<?php echo $configs->catlayoutdesclength; ?>" />
				</td>
				<td width="11">
				</td>
				<td>
					<select name="catlayoutdesctype">
						<option value="0" <?php if($configs->catlayoutdesctype == "0"){echo 'selected="selected"'; } ?> ><?php echo JText::_("VIEW_CHARACTERS"); ?></option>
						<option value="1" <?php if($configs->catlayoutdesctype == "1"){echo 'selected="selected"'; } ?> ><?php echo JText::_("VIEW_WORDS"); ?></option>
					</select>
					<?php
						echo JHTML::tooltip(JText::_("COM_DIGISTORE_LAYOUTCATSDESCTYPE_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
					?>
				</td>
			</tr>
		</table>
	</td>
</tr>