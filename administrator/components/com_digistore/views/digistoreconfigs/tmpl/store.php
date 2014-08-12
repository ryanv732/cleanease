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
	<legend><?php echo JText::_('VIEWCONFIGSTORESETTINGS');?></legend>

		<table width="100%">
			<tr>
				<td class="header_zone">
					<?php
						echo JText::_("HEADER_STORE_SETTINGS");
					?>
				</td>
			</tr>
			<tr>
				<td colspan="3" align="right">
					<a class="modal digi_video" rel="{handler: 'iframe', size: {x: 750, y: 435}}" href="index.php?option=com_digistore&controller=digistoreAbout&task=vimeo&id=38437590">
						<img src="<?php echo JURI::base(); ?>components/com_digistore/assets/images/icon_video.gif" class="video_img" />
						<?php echo JText::_("COM_DIGISTORE_VIDEO_STORE_LAYOUT"); ?>				  
					</a>
				</td>
			</tr>
		</table>

		<table class="admintable" >

			<tr class="<?php  echo "row$k"; $k = 1 - $k;?>">
				<td> 
				<?php  echo JText::_('VIEWCONFIGCATSTORE');?>
				</td>

				<td> 
				 <input name="store_name" type="text" size="30" value="<?php  echo $configs->store_name;?>">
				 <?php
					echo JHTML::tooltip(JText::_("COM_DIGISTORE_STORENAME_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
			  	?>
				</td>
			</tr>

			<tr class="<?php  echo "row$k"; $k = 1 - $k;?>">
				<td> 
				<?php  echo JText::_('VIEWCONFIGCATSTOREURL');?>
				</td>

				<td> 
				 <input name="store_url" type="text" size="40" value="<?php  echo $configs->store_url;?>">
				 <?php
					echo JHTML::tooltip(JText::_("COM_DIGISTORE_STOREURL_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
			  	?>
				</td>
			</tr>
			<tr class="<?php  echo "row$k"; $k = 1 - $k;?>">
				<td> 
				<?php  echo JText::_('VIEWCONFIGCATSTOREEMAIL'); ?>
				</td>

				<td nowrap> 
				 <input name="store_email" type="text" size="30" value="<?php  echo $configs->store_email;?>">
				 <input name="usestoremail" type="checkbox" value="1" <?php  echo (($configs->usestoremail != 0)?'checked':'');	?>>
				 <?php
					echo JHTML::tooltip(JText::_("COM_DIGISTORE_STOREEMAIL_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
			  	?>
				</td>
			</tr>

				 <tr class="<?php  echo "row$k"; $k = 1 - $k;?>">
				<td> 
				<?php  echo JText::_('VIEWCONFIGCOPYTOADMIN'); ; ?>
				</td>

				<td> 
				<select name="sendmailtoadmin" >
					<option value="1" <?php echo (($configs->sendmailtoadmin != 0)?'selected':''); ?>><?php echo JText::_('VIEWCONFIGCOPYTOADMINYES'); ?></option>
					<option value="0" <?php echo (($configs->sendmailtoadmin == 0)?'selected':''); ?>><?php echo JText::_('VIEWCONFIGCOPYTOADMINYESNO');; ?></option>
				</select>
				<?php
					echo JHTML::tooltip(JText::_("COM_DIGISTORE_STORESENDEMAIL_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
				?>
				 </td>
			</tr>

				<tr>
					<td witgh="130px"><?php echo JText::_('VIEWCONFIGSELECTCOUNTRY'); ?></td>
					<td>
							<script>
							<?php
							sajax_show_javascript();
							?>

							function changeProvince_cb(province_option) {
								//alert(province_option);
								document.getElementById("province").innerHTML = province_option;
							}

							function changeProvince() {
								 // get the folder name
								var country;
								country = document.getElementById('country').value;

								var euc = Array(<?php echo "'".implode ("','" , $eu)."'"; ?>); 
								var flag = 0;
								for (i = 0; i< euc.length; i++) 
									if (country == euc[i]) flag = 1;
								if (flag == 1) {
//									document.getElementById('vathead').style.display = '';
//									document.getElementById('personcomp').style.display = '';
//									document.getElementById('comptaxnum').style.display = '';
								} else {
//									document.getElementById('vathead').style.display = 'none';
//									document.getElementById('personcomp').style.display = 'none';
//									document.getElementById('comptaxnum').style.display = 'none';
								}

								//alert(country);
								x_phpchangeProvince(country, changeProvince_cb);
							}

							function changeCity_cb(city_option) {
								//alert(city_option);
								document.getElementById("city").innerHTML = city_option;
							}

							function changeCity() {
								var province;
								province = document.getElementById('sel_province').value;
								//alert(province);
								x_phpchangeCity(province, changeCity_cb);
							}
							</script>



<?php
		echo $lists['country_option'];
?>
		<?php
			echo JHTML::tooltip(JText::_("COM_DIGISTORE_STORECOUNTRY_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
		?>

					</td>
				</tr>
				<tr>
					<td>
						<?php echo JText::_('VIEWCONFIGSTORESTATE'); ?>
						<?php
							echo JHTML::tooltip(JText::_("COM_DIGISTORE_STORESTATE_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
						?>
					</td>
					<td>


<?php
	echo $lists['storelocation'];

?>
					</td>
				</tr>
			<tr class="<?php  echo "row$k"; $k = 1 - $k;?>">
				<td> 
				<?php  echo JText::_('VIEWCONFIGCITY');?>
				</td>

				<td>
					<input name="city" type="text" size="30" value="<?php  echo $configs->city;?>">
				<?php
					echo JHTML::tooltip(JText::_("COM_DIGISTORE_STORECITY_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
				?>
				</td>
			</tr>

			<tr class="<?php  echo "row$k"; $k = 1 - $k;?>">
				<td> 
				<?php  echo JText::_('VIEWCONFIGZIP');?>
				</td>

				<td> 
				 <input name="zip" type="text" size="40" value="<?php  echo $configs->zip;?>">
				<?php
					echo JHTML::tooltip(JText::_("COM_DIGISTORE_STOREZIP_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
				?>
				</td>
			</tr>
			<tr class="<?php  echo "row$k"; $k = 1 - $k;?>">
				<td> 
				<?php  echo JText::_('VIEWCONFIGADDRESS');?>
				</td>

				<td> 
				 <input name="address" type="text" size="40" value="<?php  echo $configs->address;?>">
				<?php
					echo JHTML::tooltip(JText::_("COM_DIGISTORE_STOREADDRESS_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
				?>
				</td>
			</tr>


			<tr class="<?php  echo "row$k"; $k = 1 - $k;?>">
				<td> 
				<?php  echo JText::_('VIEWCONFIGPHONE');?>
				</td>

				<td> 
				 <input name="phone" type="text" size="30" value="<?php  echo $configs->phone;?>">
				 <?php
					echo JHTML::tooltip(JText::_("COM_DIGISTORE_STOREPHONE_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
				?>
				</td>
			</tr>

			<tr class="<?php  echo "row$k"; $k = 1 - $k;?>">
				<td> 
				<?php  echo JText::_('VIEWCONFIGFAX');?>
				</td>

				<td> 
				 <input name="fax" type="text" size="40" value="<?php  echo $configs->fax;?>">
				<?php
					echo JHTML::tooltip(JText::_("COM_DIGISTORE_STOREFAX_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
				?>
				</td>
			</tr>

			<tr class="<?php  echo "row$k"; $k = 1 - $k;?>">
				<td valign="top"> 
					<?php echo JText::_('DIGI_STORE_LOGO'); ?>
				</td>

				<td> 
					<div>
						<div>
							<input type="button" name="uploadimagebutton" id="ajaxuploadcatimage" value="Upload image"/>
						</div>
						<div id="sitelogo">
						<?php
							//<img src='".ImageHelper::shouStoreLogoThumb(trim($configs->store_logo))."'/>
							if(trim($configs->store_logo) != ""){
								echo "<div id='box' style='float:left;padding:0.5em;margin:0.5em;'>
										<img src='".JURI::root()."/images/stories/digistore/store_logo/".$configs->store_logo."'/>
										<input type='hidden' name='store_logo' value='".$configs->store_logo."'/>
										<div style='padding:0.5em 0;'>
											<span style='float:right;'><a href='javascript:void(0);'  onclick='document.getElements(\"div[id=box]\").each( function(el) { el.getParent().removeChild(el); });'>Delete</a></span>
										</div>
									</div>";
							}
						?>
						</div>
					</div>
					<?php
						echo JHTML::tooltip(JText::_("COM_DIGISTORE_STOREIMAGE_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
					?>
				</td>
			</tr>

			<tr class="<?php  echo "row$k"; $k = 1 - $k;?>">
				<td>
					<?php echo JText::_('VIEWCONFIGDISPLAYSTOREDESC'); ?>
				</td>
				<td>
					<select name="displaystoredesc" >
						<option value="1" <?php echo (($configs->displaystoredesc == 1)?'selected':''); ?>><?php echo JText::_('DSYES'); ?></option>
						<option value="0" <?php echo (($configs->displaystoredesc == 0)?'selected':''); ?>><?php echo JText::_('DSNO');; ?></option>
					</select>
					<?php
						echo JHTML::tooltip(JText::_("COM_DIGISTORE_STOREDISPLAY_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
					?>

				</td>
			</tr>

			<tr class="<?php  echo "row$k"; $k = 1 - $k;?>">
				<td colspan="2">
					<?php  echo JText::_('VIEWCONFIGSTOREDESC');?>: <?php
					echo JHTML::tooltip(JText::_("COM_DIGISTORE_STOREDESC_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
					?>
					<br />
					<textarea id="storedesc" name="storedesc" class="useredactor" style="width:100%;height:450px;"><?php echo $configs->storedesc;?></textarea>
				</td>
			</tr>

		</table>
	</fieldset>
