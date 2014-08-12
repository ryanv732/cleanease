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
$document->addScript(JURI::base()."components/com_digistore/assets/js/digistore.js");

?>

<tr>
	<th colspan="2" class="first_title_dgst"><?php echo JText::_('VIEWCONFIGPRODAPP');?></th>
</tr>

<tr>
	<td colspan="3" align="right">
		<a class="modal digi_video" rel="{handler: 'iframe', size: {x: 750, y: 435}}" href="index.php?option=com_digistore&controller=digistoreAbout&task=vimeo&id=38437588">
			<img src="<?php echo JURI::base(); ?>components/com_digistore/assets/images/icon_video.gif" class="video_img" />
			<?php echo JText::_("COM_DIGISTORE_VIDEO_PRODUCT_LAYOUT"); ?>
		</a>
	</td>
</tr>

<tr>
	<td width="20%">
		<?php echo JText::_("VIEWCONFIGLAYOUTS"); ?>
	</td>
	<td>
		<select name="prodlayoutstyle" onchange="javascript:changeLayoutType(this.value)">
			<option value="1" <?php if($configs->prodlayoutstyle == '1'){ echo 'selected="selected"'; } ?> ><?php echo JText::_("LAYOUT_GRID"); ?></option>
			<option value="2" <?php if($configs->prodlayoutstyle == '2'){ echo 'selected="selected"'; } ?> ><?php echo JText::_("LAYOUT_LIST"); ?></option>
		</select>
	</td>
</tr>

<tr>
	<td width="20%">
		<?php echo JText::_("VIEW_HOW_MANY_PRODUCTS"); ?>
	</td>
	<td>
		<table>
			<tr>
				<td width="15%">
					<select name="prodlayoutrow" id="prodlayoutrow">
						<?php 
							for($i=1; $i<=25; $i++){
								$selected = "";
								if($i == $configs->prodlayoutrow){
									$selected = 'selected="selected"';
								}
								echo '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
							}
						?>
					</select>
					<?php echo JText::_('VIEWCONFIGTABLEROWPERPAGE');?>
				</td>
				<td width="15%">
					<?php
						$display = $configs->prodlayoutstyle == '1' ? "block" : "none";
					?>
					<div id="div_cols" style="display:<?php echo $display; ?>">
						<select name="prodlayoutcol" id="prodlayoutcol">
							<?php 
								for($i=1; $i<=5; $i++){
									$selected = "";
									if($i == $configs->prodlayoutcol){
										$selected = 'selected="selected"';
									}
									echo '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
								}
							?>
						</select>
						<?php echo JText::_('VIEWCONFIGTABLEPRODPERROW');?>
					</div>
				</td>
				<td>
					<?php
						echo JHTML::tooltip(JText::_("COM_DIGISTORE_LAYOUTPRODSROW_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
					?>
				</td>
			</tr>
		</table>
	</td>
</tr>

<tr>
	<td colspan="2">
		<?php
			$grid_display = $configs->prodlayoutstyle == '1' ? "block" : "none";
		?>
		<div style="display:<?php echo $grid_display; ?>" id="div_grid">
			<table>
				<tr>
					<td width="20%">
						<?php echo JText::_("PRODUCT_BOX_ALIGNMENT"); ?>
					</td>
					<td>
						<select name="grid_image_align">
							<option value="0" <?php if($configs->grid_image_align == '0'){ echo 'selected="selected"'; } ?> ><?php echo JText::_("VIEW_TOP_CENTER"); ?></option>
							<option value="1" <?php if($configs->grid_image_align == '1'){ echo 'selected="selected"'; } ?> ><?php echo JText::_("VIEW_LEFT"); ?></option>
							<option value="2" <?php if($configs->grid_image_align == '2'){ echo 'selected="selected"'; } ?> ><?php echo JText::_("VIEW_RIGHT"); ?></option>
						</select>
						<?php
							echo JHTML::tooltip(JText::_("PRODUCT_BOX_ALIGNMENT_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
						?>
					</td>
				</tr>
				<tr>
					<td width="20%">
						<?php echo JText::_("DIGI_SHORT_DESCRIPTION_CLASS"); ?>
					</td>
					<td>
						<input type="text" name="prods_short_desc_class" value="<?php echo $configs->prods_short_desc_class; ?>" />
						<?php
							echo JHTML::tooltip(JText::_("COM_DIGISTORE_LAYOUTSHORTCLASS_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
						?>
					</td>
				</tr>
				<tr>
					<td width="20%">
						<?php echo JText::_("DIGI_PRICE_CLASS"); ?>
					</td>
					<td>
						<input type="text" name="prods_price_class" value="<?php echo $configs->prods_price_class; ?>" />
						<?php
							echo JHTML::tooltip(JText::_("COM_DIGISTORE_LAYOUTPRICECLASS_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
						?>
					</td>
				</tr>
				<tr>
					<td width="20%">
						<?php echo JText::_("DIGI_PRODUCT_NAME_CLASS"); ?>
					</td>
					<td>
						<input type="text" name="prods_name_class" value="<?php echo $configs->prods_name_class; ?>" />
						<?php
							echo JHTML::tooltip(JText::_("COM_DIGISTORE_LAYOUTNAMECLASS_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
						?>
					</td>
				</tr>
			</table>
		</div>
	</td>
</tr>

<tr>
	<td>
		<?php echo JText::_('VIEWCONFIGSHOWPRODDETAILS'); ?>
	</td>
	<td>
		<select name="showproductdetails">
			<option value="1" <?php echo ($configs->showproductdetails == '1') ? "selected" : '' ; ?>><?php echo JText::_('DSYES'); ?></option>
			<option value="0" <?php echo ($configs->showproductdetails == '0') ? "selected" : '' ; ?>><?php echo JText::_('DSNO'); ?></option>
		</select>
	</td>
</tr>

<tr>
	<td colspan="2">
		<?php
			$list_display = $configs->prodlayoutstyle == '2' ? "block" : "none";
		?>
		<div style="display:<?php echo $list_display; ?>" id="div_list">
			<table>
				<tr>
					<td width="20%">
						<?php echo JText::_("VIEW_MULTI_SELECTION"); ?>
					</td>
					<td>
						<select name="list_multi_selection">
							<option value="0" <?php if($configs->list_multi_selection == '0'){ echo 'selected="selected"'; } ?> ><?php echo JText::_("DSYES"); ?></option>
							<option value="1" <?php if($configs->list_multi_selection == '1'){ echo 'selected="selected"'; } ?> ><?php echo JText::_("DSNO"); ?></option>
						</select>
						<?php
							echo JHTML::tooltip(JText::_("COM_DIGISTORE_LAYOUTMULTISELECT_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
						?>
					</td>
				</tr>
				<tr>
					<td width="20%">
						<?php echo JText::_("VIEW_ORIENTATION"); ?>
					</td>
					<td>
						<select name="list_orientation">
							<option value="0" <?php if($configs->list_orientation == '0'){ echo 'selected="selected"'; } ?> ><?php echo JText::_("VIEW_LEFT"); ?></option>
							<option value="1" <?php if($configs->list_orientation == '1'){ echo 'selected="selected"'; } ?> ><?php echo JText::_("VIEW_RIGHT"); ?></option>
						</select>
						<?php
							echo JHTML::tooltip(JText::_("COM_DIGISTORE_LAYOUTORIENTATION_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
						?>
					</td>
				</tr>
			</table>
		</div>
	</td>
</tr>

<tr>
	<th colspan="2" class="second_title_dgst"><?php echo JText::_('VIEW_THUMBNAILS_DESCRIPTION');?></th>
</tr>

<tr> 
	<td colspan="2">
		<table>
			<tr>
				<td width="15%"> 
					<?php echo JText::_('VIEWCONFIGTSHOWTHUMB');?>
				</td>
				<td>
					<select name="showthumb">
						<option value="1" <?php echo ($configs->showthumb == '1' ?"selected":"");?>><?php echo JText::_('DSYES');?></option>
						<option value="0" <?php echo ($configs->showthumb == '0' ?"selected":"");?>><?php echo JText::_('DSNO');?></option>
					</select>
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
					<?php  echo JText::_('VIEW_PRODUCT_THUMBNAIL'); ?>
				</td>
				<td>
					<input type="text" name="imageprodsizethumbvalue" value="<?php echo ($configs->imageprodsizethumbvalue ? $configs->imageprodsizethumbvalue : '100'); ?>"/>
					&nbsp; px &nbsp;
					<?php 
						$imageprodsizethumbtype[] = JHTML::_('select.option','0', JText::_( 'DS_HIGH' ) );
						$imageprodsizethumbtype[] = JHTML::_('select.option','1', JText::_( 'DS_WIDE' ) );
						echo JHTML::_('select.genericlist',  $imageprodsizethumbtype, 'imageprodsizethumbtype', null, 'value', 'text', $configs->imageprodsizethumbtype);
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
					<input type="text" name="prodlayoutdesclength" value="<?php echo $configs->prodlayoutdesclength; ?>" />
				</td>
				<td width="11">
				</td>
				<td>
					<select name="prodlayoutdesctype">
						<option value="0" <?php if($configs->prodlayoutdesctype == "0"){echo 'selected="selected"'; } ?> ><?php echo JText::_("VIEW_CHARACTERS"); ?></option>
						<option value="1" <?php if($configs->prodlayoutdesctype == "1"){echo 'selected="selected"'; } ?> ><?php echo JText::_("VIEW_WORDS"); ?></option>
					</select>
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
				<td width="20%"> 
					<?php echo JText::_('VIEW_SHOW_FEATURED_PROD');?>
				</td>
				<td width="10%">
					<input type="radio" name="showfeatured_prod" value="1"  <?php echo ($configs->showfeatured_prod == '1' ?"checked":"");?> ><?php echo JText::_('DSYES');?>
					&nbsp;
					<input type="radio" name="showfeatured_prod" value="0"  <?php echo ($configs->showfeatured_prod != '1' ?"checked":"");?> ><?php echo JText::_('DSNO');?>
				</td>
				<td width="15%">
					<select name="featured_row" id="featured_row">
						<?php 
							for($i=1; $i<=25; $i++){
								$selected = "";
								if($i == $configs->featured_row){
									$selected = 'selected="selected"';
								}
								echo '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
							}
						?>
					</select>
					<?php echo JText::_('VIEWCONFIGTABLEROWPERPAGE');?>
				</td>
				<td width="15%">
					<select name="featured_col" id="featured_col">
						<?php 
							for($i=1; $i<=5; $i++){
								$selected = "";
								if($i == $configs->featured_col){
									$selected = 'selected="selected"';
								}
								echo '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
							}
						?>
					</select>
					<?php echo JText::_('VIEWCONFIGTABLEPRODPERROW');?>
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
					<?php echo JText::_('VIEW_SHOW_SORT_PRODUCT');?>
				</td>
				<td>	 
					<select name="prodlayoutsort">
						<option value="0" <?php if($configs->prodlayoutsort == "0"){echo 'selected="selected"'; } ?> ><?php echo JText::_("DSYES"); ?></option>
						<option value="1" <?php if($configs->prodlayoutsort == "1"){echo 'selected="selected"'; } ?> ><?php echo JText::_("DSNO"); ?></option>
					</select>
				</td>
			</tr>
		</table>
	</td>
</tr>