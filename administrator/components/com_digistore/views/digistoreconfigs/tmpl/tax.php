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
							<script>

							function changeTaxProvince_cb(province_option) {
								//alert(province_option);
								document.getElementById("configsprovince").innerHTML = province_option;
							}

							function changeProvince_configstax() {
								 // get the folder name
								var country;
								country = document.getElementById('tax_country').value;

										//alert(country);
								x_phpchangetaxProvince(country, changeTaxProvince_cb);
							}


							</script>


	<fieldset class="adminform">
	<legend><?php echo JText::_('VIEWCONFIGLOCATIONANDTAX');?></legend>

	<table width="100%">
		<tr>
			<td class="header_zone">
				<?php
					echo JText::_("HEADER_TAX_SETTINGS");
				?>
			</td>
		</tr>
		<tr>
			<td align="right">
				<a class="modal digi_video" rel="{handler: 'iframe', size: {x: 750, y: 435}}" href="index.php?option=com_digistore&controller=digistoreAbout&task=vimeo&id=38440228">
					<img src="<?php echo JURI::base(); ?>components/com_digistore/assets/images/icon_video.gif" class="video_img" />
					<?php echo JText::_("COM_DIGISTORE_VIDEO_TAX_SETTINGS"); ?>				  
				</a>
			</td>
		</tr>
	</table>

			<br>
	<table  class="admintable">
	<tr><th class="second_title_dgst" colspan="2" style="text-align:left"><?php echo JText::_('DSTAXCLASSES');?></th></tr>
	<tr>
			<td width="250px"><?php echo JText::_("DSTAXFORSHIP");?></td>
		<td align="left">
			<?php echo $this->lists['tax_classes'];?>
			<?php
				echo JHTML::tooltip(JText::_("COM_DIGISTORE_TAXCLASS_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
			?>
		</td></tr>

	</table>

	<table  class="admintable">
	<tr><th class="second_title_dgst" colspan="2" style="text-align:left"><?php echo JText::_('DSTAXCALCULATION');?></th></tr>
	<tr>
			<td width="250px"><?php echo JText::_("DSTAXBASE");?></td>
		<td align="left">
			<?php echo $this->lists['tax_base'];?>
			<?php
				echo JHTML::tooltip(JText::_("COM_DIGISTORE_TAXBASE_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
			?>
		</td></tr>
	<!-- PDG :: start -->
	<tr>
		<td width="250px"><?php echo JText::_("DSTAXEUMODE");?></td>
		<td align="left">
			<?php echo $this->lists['tax_eumode'];?>
			<?php
				echo JHTML::tooltip(JText::_("COM_DIGISTORE_TAXEUMODE_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
			?>
		</td>
	</tr>
	<!-- PDG :: start -->
	<tr style="display:none">
			<td><?php echo JText::_("DSTAXCATALOGPRICE");?></td>
		<td align="left"><?php echo $this->lists['tax_catalog'];?></td></tr>
	<tr style="display:none">
			<td><?php echo JText::_("DSTAXSHIPPINGPRICE");?></td>
		<td align="left"><?php echo $this->lists['tax_shipping'];?></td></tr>
	<!-- <tr>
			<td><?php //echo JText::_("DSTAXAFTERDISCOUNT");?></td>
		<td align="left"><?php //echo $this->lists['tax_discount'];?></td></tr> -->
	<tr style="display:none">
			<td><?php echo JText::_("DSDISCOUNTTAX");?></td>
		<td align="left">
			<?php echo $this->lists['discount_tax'];?>
			<br />
			<?php echo JText::_("DSTAXIGNORE");?>
		</td></tr>
	<tr style="display:none">
			<td><?php echo JText::_("DSTAXAPPLYON");?></td>
		<td align="left"><?php echo $this->lists['tax_apply'];?></td></tr>

	</table>


	<table  class="admintable">
	<tr><th class="second_title_dgst" colspan="2" style="text-align:left"><?php echo JText::_('DSTAXORIGINCALCULATION');?></th></tr>
	<tr><td><?php echo JText::_("DSUSESTORELOCATION");?></td>
		<td align="left">
			<input type="checkbox" name="usestorelocation" value="1" <?php if ($configs->usestorelocation == 1) echo "checked";?>/>
			<?php
				echo JHTML::tooltip(JText::_("COM_DIGISTORE_TAXLOCATION_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
			?>
		</td>
	</tr>

	<tr>
			<td width="250px"><?php echo JText::_("DSDEFAULTCOUNTRY");?></td>
		<td align="left">
			<?php echo $this->lists['tax_country'];?>
			<?php
				echo JHTML::tooltip(JText::_("COM_DIGISTORE_TAXLCOUNTRY_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
			?>
		</td></tr>
	<tr>
			<td>
				<?php echo JText::_("DSDEFAULTSTATE");?>
			</td>
		<td align="left">
			<?php echo $this->lists['tax_state'];?>
			<?php
				echo JHTML::tooltip(JText::_("COM_DIGISTORE_TAXSTATE_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
			?>
		</td></tr>
	<tr>
			<td><?php echo JText::_("DSTAXDEFAULTZIP");?></td>
		<td align="left">
			<input type="text" name="tax_zip" value="<?php echo $configs->tax_zip;?>" />
			<?php
				echo JHTML::tooltip(JText::_("COM_DIGISTORE_TAXZIP_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
			?>
		</td></tr>

	</table>

	<table  class="admintable">
	<tr><th class="second_title_dgst" colspan="2" style="text-align:left"><?php echo JText::_('DSTAXDISPLAY');?></th></tr>
	<!-- <tr>
			<td width="250px"><?php //echo JText::_("DSCARTORDERPRICE");?></td>
		<td align="left"><?php //echo $this->lists['tax_price'];?></td></tr> -->
	<tr>
			<td width="250px">
				<?php echo JText::_("DSTAXSUMMARY");?>
			</td>
		<td align="left">
			<?php echo $this->lists['tax_summary'];?>
			<?php
				echo JHTML::tooltip(JText::_("COM_DIGISTORE_TAXSUMMARY_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
			?>
		</td></tr>
	<tr>
			<td><?php echo JText::_("DSSHIPPINGPRICE");?></td>
		<td align="left"><?php echo $this->lists['shipping_price'];?></td></tr>
	<!-- <tr>
			<td><?php// echo JText::_("DSPRODUCTPRICE");?></td>
		<td align="left"><?php //echo $this->lists['product_price'];?></td></tr> -->
	<tr>
			<td>
				<?php echo JText::_("DSTAXZERO");?>
			</td>
		<td align="left">
			<?php echo $this->lists['tax_zero'];?>
			<?php
				echo JHTML::tooltip(JText::_("COM_DIGISTORE_TAXZERO_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
			?>
		</td></tr>

	</table>
		</fieldset>

