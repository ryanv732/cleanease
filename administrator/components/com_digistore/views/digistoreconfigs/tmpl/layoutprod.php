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
	<th colspan="2" class="first_title_dgst"><?php echo JText::_('VIEW_PRODUCT_PAGE');?></th>
</tr>

<tr>
	<th colspan="2" class="second_title_dgst"><?php echo JText::_('VIEW_IMAGE_GALLERY');?></th>
</tr>

<tr>
	<td colspan="3" align="right">
		<a class="modal digi_video" rel="{handler: 'iframe', size: {x: 750, y: 435}}" href="index.php?option=com_digistore&controller=digistoreAbout&task=vimeo&id=38437587">
			<img src="<?php echo JURI::base(); ?>components/com_digistore/assets/images/icon_video.gif" class="video_img" />
			<?php echo JText::_("COM_DIGISTORE_VIDEO_PRODUCT_MANAGER"); ?>				  
		</a>
	</td>
</tr>

<tr>
	<td colspan="2">
		<table>
			<tr>
				<td width="15%">
					<?php 
						echo JText::_("DIGI_STYLE");
					?>
				</td>
				<td>
					<select name="gallery_style" onchange="javascript:galeryColumns(this.value);">
						<option value="0" <?php if($configs->gallery_style == 0){ echo 'selected="selected"'; } ?> ><?php echo JText::_("DIGI_SCROLLER"); ?></option>
						<option value="1" <?php if($configs->gallery_style == 1){ echo 'selected="selected"'; } ?> ><?php echo JText::_("DIGI_SIMPLE"); ?></option>
					</select>
					<?php
						echo JHTML::tooltip(JText::_("COM_DIGISTORE_LAYOUTGALERYSTYLE_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
					?>
				</td>
			</tr>
		</table>
	</td>
</tr>

<?php
	$display = "table-cell";
	if($configs->gallery_style == "0"){
		$display = "none";
	}
?>

<tr>
	<td colspan="2" style="display:<?php echo $display; ?>;" id="galery_columns_td">
		<table>
			<tr>
				<td width="15%">
					<?php 
						echo JText::_("VIEWCONFIGTABLEPRODPERROW");
					?>
				</td>
				<td>
					<select name="gallery_columns">
						<?php
							for($i=1; $i<=5; $i++){
						?>
								<option value="<?php echo $i; ?>" <?php if($configs->gallery_columns == $i){ echo 'selected="selected"';} ?> ><?php echo $i; ?></option>
						<?php
							}
						?>
					</select>
					<?php
						echo JHTML::tooltip(JText::_("COM_DIGISTORE_LAYOUTGALERYCOLUMNS_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
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
					<?php echo JText::_("VIEW_THUMBNAILS"); ?>
				</td>
				<td width="10%">
					<input type="text" name="prodlayoutthumbnails" value="<?php echo $configs->prodlayoutthumbnails; ?>" />
				</td>
				<td width="2">
					px
				</td>
				<td>
					<select name="prodlayoutthumbnailstype">
						<option value="0" <?php if($configs->prodlayoutthumbnailstype == "0"){echo 'selected="selected"'; } ?> ><?php echo JText::_("VIEWHEIGH"); ?></option>
						<option value="1" <?php if($configs->prodlayoutthumbnailstype == "1"){echo 'selected="selected"'; } ?> ><?php echo JText::_("VIEWWIDE"); ?></option>
					</select>
					<?php
						echo JHTML::tooltip(JText::_("COM_DIGISTORE_LAYOUTPRODLAYOUT_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
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
					<?php echo JText::_("VIEW_LARGE_IMG_PREV"); ?>
				</td>
				<td width="10%">
					<input type="text" name="prodlayoutlargeimgprev" value="<?php echo $configs->prodlayoutlargeimgprev; ?>" />
				</td>
				<td width="2">
					px
				</td>
				<td>
					<select name="prodlayoutlargeimgprevtype">
						<option value="0" <?php if($configs->prodlayoutlargeimgprevtype == "0"){echo 'selected="selected"'; } ?> ><?php echo JText::_("VIEWHEIGH"); ?></option>
						<option value="1" <?php if($configs->prodlayoutlargeimgprevtype == "1"){echo 'selected="selected"'; } ?> ><?php echo JText::_("VIEWWIDE"); ?></option>
					</select>
					<?php
						echo JHTML::tooltip(JText::_("COM_DIGISTORE_LAYOUTPRODLARGEIMG_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
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
					<?php echo JText::_("VIEW_LIGHTBOX_IMG_PREV"); ?>
				</td>
				<td width="10%">
					<input type="text" name="prodlayoutlightimgprev" value="<?php echo $configs->prodlayoutlightimgprev; ?>" />
				</td>
				<td width="2">
					px
				</td>
				<td>
					<select name="prodlayoutlightimgprevtype">
						<option value="0" <?php if($configs->prodlayoutlightimgprevtype == "0"){echo 'selected="selected"'; } ?> ><?php echo JText::_("VIEWHEIGH"); ?></option>
						<option value="1" <?php if($configs->prodlayoutlightimgprevtype == "1"){echo 'selected="selected"'; } ?> ><?php echo JText::_("VIEWWIDE"); ?></option>
					</select>
					<?php
						echo JHTML::tooltip(JText::_("COM_DIGISTORE_LAYOUTPRODLIGHIMG_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
					?>
				</td>
			</tr>
		</table>
	</td>
</tr>

<tr>
	<th colspan="2" class="second_title_dgst"><?php echo JText::_('VIEW_DESCRIPTION');?></th>
</tr>

<tr> 
	<td colspan="2">
		<table>
			<tr>
				<td width="15%">
					<?php echo JText::_('VIEW_SHORT_DESCRIPTION');?>
				</td>
				<td width="13%">
					<input type="radio" name="showshortdescription" value="1"  <?php echo ($configs->showshortdescription == '1' ?"checked":"");?> ><?php echo JText::_('DSYES');?>
					&nbsp;
					<input type="radio" name="showshortdescription" value="0"  <?php echo ($configs->showshortdescription != '1' ?"checked":"");?> ><?php echo JText::_('DSNO');?>
				</td>
				<td>
					<?php echo JText::_("DIGI_SHORT_DESCRIPTION_CLASS"); ?>:&nbsp;&nbsp;
					<input type="text" name="prod_short_desc_class" value="<?php echo $configs->prod_short_desc_class; ?>" />
					<?php
						echo JHTML::tooltip(JText::_("COM_DIGISTORE_LAYOUTPRODSHORTCLASS_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
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
					<?php echo JText::_('VIEW_LONG_DESCRIPTION');?>
				</td>
				<td width="13%">	 
					<input type="radio" name="showlongdescription" value="1"  <?php echo ($configs->showlongdescription == '1' ?"checked":"");?> ><?php echo JText::_('DSYES');?>
					&nbsp;
					<input type="radio" name="showlongdescription" value="0"  <?php echo ($configs->showlongdescription != '1' ?"checked":"");?> ><?php echo JText::_('DSNO');?>
				</td>
				<td>
					<?php echo JText::_("DIGI_LONG_DESCRIPTION_CLASS"); ?>:&nbsp;&nbsp;
					<input type="text" name="prod_long_desc_class" value="<?php echo $configs->prod_long_desc_class; ?>" />
					<?php
						echo JHTML::tooltip(JText::_("COM_DIGISTORE_LAYOUTPRODDESCCLASS_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
					?>
				</td>
			</tr>
		</table>
	</td>
</tr>

<tr>
	<th colspan="2" class="second_title_dgst"><?php echo JText::_('VIEW_EXTRAS');?></th>
</tr>

<tr> 
	<td colspan="2">
		<table>
			<tr>
				<td width="15%"> 
					<?php echo JText::_('VIEW_RELATED_PROD');?>
				</td>
				<td>	 
					<input type="radio" name="showrelatedprod" value="1"  <?php echo ($configs->showrelatedprod == '1' ?"checked":"");?> ><?php echo JText::_('DSYES');?>
					&nbsp;
					<input type="radio" name="showrelatedprod" value="0"  <?php echo ($configs->showrelatedprod != '1' ?"checked":"");?> ><?php echo JText::_('DSNO');?>
					<?php
						echo JHTML::tooltip(JText::_("COM_DIGISTORE_LAYOUTPRODRELATEDPROD_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
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
					<?php echo JText::_('VIEW_RELATED_ROWS');?>
				</td>
				<td>
					<?php echo JText::_("VIEWCONFIGTABLEROWPERPAGE"); ?>
					<select name="relatedrows">
						<?php
							for($i=1; $i<=25; $i++){
						?>
								<option value="<?php echo $i; ?>" <?php if($configs->relatedrows == $i){echo 'selected="selected"';} ?> ><?php echo $i; ?></option>
						<?php
							}
						?>
					</select>
					&nbsp; &nbsp; &nbsp;
					<?php echo JText::_("VIEWCONFIGTABLEPRODPERROW"); ?>
					<select name="relatedcolumns">
						<?php
							for($i=1; $i<=5; $i++){
						?>
								<option value="<?php echo $i; ?>" <?php if($configs->relatedcolumns == $i){echo 'selected="selected"';} ?> ><?php echo $i; ?></option>
						<?php
							}
						?>
					</select>
					<?php
						echo JHTML::tooltip(JText::_("COM_DIGISTORE_LAYOUTPRODCOLSROWS_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
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
					<?php echo JText::_('DIGI_SOCIAL_MEDIA');?>
				</td>
				<td>	 
					<input type="checkbox" name="showretwitter" value="1"  <?php echo ($configs->showretwitter == '1' ? 'checked="checked"' : "");?> ><?php echo JText::_('DIGI_RETWITTER');?>
					<?php
						echo JHTML::tooltip(JText::_("COM_DIGISTORE_LAYOUTPRODRETWITTER_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
					?>
					&nbsp;
					<input type="checkbox" name="showtwitter" value="1"  <?php echo ($configs->showtwitter == '1' ? 'checked="checked"' : "");?> ><?php echo JText::_('DIGI_TWITTER');?>
					<?php
						echo JHTML::tooltip(JText::_("COM_DIGISTORE_LAYOUTPRODTWITTER_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
					?>
					&nbsp;
					<input type="checkbox" name="showfacebook" value="1" <?php echo ($configs->showfacebook == '1' ? 'checked="checked"' : "");?> ><?php echo JText::_('DIGI_FACEBOOK');?>
					<?php
						echo JHTML::tooltip(JText::_("COM_DIGISTORE_LAYOUTPRODFACEBOOK_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
					?>

				</td>
				<td align="right">
					<a class="modal digi_video" rel="{handler: 'iframe', size: {x: 750, y: 435}}" href="index.php?option=com_digistore&controller=digistoreAbout&task=vimeo&id=38539742">
						<img src="<?php echo JURI::base(); ?>components/com_digistore/assets/images/icon_video.gif" class="video_img" />
						<?php echo JText::_("COM_DIGISTORE_VIDEO_SETTINGSSOCIAL"); ?>				  
					</a>
				</td>
			</tr>
		</table>
	</td>
</tr>