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

JHtml::_('behavior.tooltip');
$k = 0;

$document = JFactory::getDocument();
$document->addStyleSheet("components/com_digistore/assets/css/digistore.css");
$document->addScript("components/com_digistore/assets/js/color_picker.js");

?>
<!-- General -->
<fieldset class="adminform">
	<legend>
		<?php echo JText::_('VIEWCONFIGCATGENERALSETT');?>
	</legend>

	<table class="admintable table table-striped"">

		<tr>
			<td width="200">
				<?php echo JText::_('DS_SHOW_POWERED'); ?>
			</td>
			<td>
				<select name="showpowered">
					<option value="1" <?php echo ($configs->showpowered == '1') ? "selected" : '' ; ?>>
						<?php echo JText::_('DSYES'); ?>
					</option>
					<option value="0" <?php echo ($configs->showpowered == '0') ? "selected" : '' ; ?>>
						<?php echo JText::_('DSNO'); ?>
					</option>
				</select>
				<?php
					echo JHTML::tooltip(JText::_("COM_DIGISTORE_POWERED_TIP"), '', '', "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
				?>
			</td>
		</tr>
		
		<!-- Catalogue Mode -->
		<tr>
			<td width="200">
				<?php echo JText::_('DS_CATALOGUEMODE'); ?>
			</td>
			<td>
				<select name="catalogue">
					<option value="1" <?php echo ($configs->catalogue == '1') ? "selected" : '' ; ?>>
						<?php echo JText::_('DSYES'); ?>
					</option>
					<option value="0" <?php echo ($configs->catalogue == '0') ? "selected" : '' ; ?>>
						<?php echo JText::_('DSNO'); ?>
					</option>
				</select>
				<?php
					echo JHTML::tooltip(JText::_("COM_DIGISTORE_CATALOGUEMODE_TIP"), '', '', "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
				?>
			</td>
		</tr>

		<tr>
			<td>
				<?php  echo JText::_('VIEWCONFIGTIMEFORMAT');; ?>
			</td>
			<td>
				<select name="time_format" >
					<option value="DD-MM-YYYY" <?php echo ($configs->time_format == 'd-m-Y')?"selected":'' ; ?>>DD-MM-YYYY</option>
					<option value="MM-DD-YYYY" <?php echo ($configs->time_format == 'm-d-Y' || $configs->time_format == '')?"selected":'' ; ?>>MM-DD-YYYY</option>
					<option value="YYYY-MM-DD" <?php echo ($configs->time_format == 'Y-m-d')?"selected":'' ; ?>>YYYY-MM-DD</option>
					<option value="YYYY-DD-MM" <?php echo ($configs->time_format == 'Y-d-m')?"selected":'' ; ?>>YYYY-DD-MM</option>
				</select>
				<?php
					echo JHTML::tooltip(JText::_("COM_DIGISTORE_DATE_TIP"), '', '', "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
				?>
			</td>
		</tr>

		<tr>
			<td>
				<?php echo JText::_('VIEWCONFIGHOUR24FORMAT'); ?>
			</td>
			<td>
				<select name="hour24format">
					<option value="0" <?php echo ($configs->hour24format == '0') ? "selected" : '' ; ?>>12-hour clock (AM/PM)</option>
					<option value="1" <?php echo ($configs->hour24format == '1') ? "selected" : '' ; ?>>24-hour clock</option>
				</select>
				<?php
					echo JHTML::tooltip(JText::_("COM_DIGISTORE_HOUR_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
				?>
			</td>
		</tr>

		<?php /* ?>
		<tr class="<?php echo "row$k"; $k = 1 - $k;?> ">
			<td>
				<?php echo JText::_('VIEWCONFIGGOOGLEACC'); ?>
			</td>

			<td>
				<input name="google_account" type="text" size="10" value="<?php //echo $configs->google_account ; ?>">
				<?php
					echo JHTML::tooltip(JText::_("COM_DIGISTORE_GOOGLE_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
				?>
			</td>
		</tr>
		<?php */ ?>

		<tr class="<?php echo "row$k"; $k = 1 - $k;?> ">
			<td>
				<?php echo JText::_('VIEWCONFIGCONVERSIONID'); ?>
			</td>

			<td>
				<input name="conversion_id" type="text" value="<?php echo $configs->conversion_id; ?>">
				<?php
					//echo JHTML::tooltip(JText::_("COM_DIGISTORE_GOOGLE_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
				?>
			</td>
		</tr>

		<tr class="<?php echo "row$k"; $k = 1 - $k;?> ">
			<td>
				<?php echo JText::_('VIEWCONFIGCONVERSIONLANG'); ?>
			</td>

			<td>
				<input name="conversion_language" type="text" size="5" value="<?php echo $configs->conversion_language; ?>">
				<?php
					//echo JHTML::tooltip(JText::_("COM_DIGISTORE_GOOGLE_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
				?>
			</td>
		</tr>

		<tr class="<?php echo "row$k"; $k = 1 - $k;?> ">
			<td>
				<?php echo JText::_('VIEWCONFIGCONVERSIONFORMAT'); ?>
			</td>

			<td>
				<input name="conversion_format " type="text" size="5" value="<?php echo $configs->conversion_format; ?>">
				<?php
					//echo JHTML::tooltip(JText::_("COM_DIGISTORE_GOOGLE_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
				?>
			</td>
		</tr>

		<tr class="<?php echo "row$k"; $k = 1 - $k;?> ">
			<td>
				<?php echo JText::_('VIEWCONFIGCONVERSIONCOLOR'); ?>
			</td>

			<td>
				<input size="10" type="text" name="conversion_color" id="conversion_colorfield" value="<?php echo $configs->conversion_color; ?>" onChange="relateColor('conversion_color', this.value);" />&nbsp; <a href="javascript:pickColor('conversion_color','1');" id="conversion_color" style="border: 1px solid #000000; font-family:Verdana; font-size:10px; text-decoration: none;">&nbsp;&nbsp;&nbsp;</a>
				<SCRIPT LANGUAGE="javascript">relateColor('conversion_color', getObj('conversion_colorfield').value);</script>

				<?php
					//echo JHTML::tooltip(JText::_("COM_DIGISTORE_GOOGLE_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
				?>
			</td>
		</tr>

		<tr class="<?php echo "row$k"; $k = 1 - $k;?> ">
			<td>
				<?php echo JText::_('VIEWCONFIGCONVERSIONLABEL'); ?>
			</td>

			<td>
				<input name="conversion_label" type="text" value="<?php echo $configs->conversion_label; ?>">
				<?php
					//echo JHTML::tooltip(JText::_("COM_DIGISTORE_GOOGLE_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
				?>
			</td>
		</tr>

		<tr>
			<td>
				<?php  echo JText::_('VIEWCONFIGFTPPATH'); ; ?>
			</td>
			<td>
				<input name="ftp_source_path" type="text" size="10" value="<?php echo (isset($configs->ftp_source_path)&&$configs->ftp_source_path != '')?$configs->ftp_source_path:'media' ; ?>">
				<?php
					echo JHTML::tooltip(JText::_("COM_DIGISTORE_FTP_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
				?>
			</td>
		</tr>

		<tr>
			<td>
				<?php  echo JText::_('VIEWCONFIGDIRECTLINK'); ; ?>
			</td>
			<td>
				<select name="directfilelink" >
					<option value="1" <?php echo (($configs->directfilelink != '0')?'selected':''); ?>><?php echo JText::_('VIEWCONFIGCOPYTOADMINYES');; ?></option>
					<option value="0" <?php echo (($configs->directfilelink == '0')?'selected':''); ?>><?php echo JText::_('VIEWCONFIGCOPYTOADMINYESNO');; ?></option>
				</select>
				<?php
					echo JHTML::tooltip(JText::_("COM_DIGISTORE_DIRECT_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
				?>
			</td>
		</tr>

		<tr>
			<td>
				<?php  echo JText::_('DIGI_MAILCHIMP_API'); ?>
			</td>
			<td>
				<input type="text" name="mailchimpapi" value="<?php echo $configs->mailchimpapi; ?>" />
				<?php
					echo JHTML::tooltip(JText::_("COM_DIGISTORE_MAILCHIMPAPI_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
				?>
			</td>
		</tr>

		<tr>
			<td>
				<?php  echo JText::_('DIGI_MAILCHIMP_LIST'); ?>
			</td>
			<td>
				<input type="text" name="mailchimplist" value="<?php echo $configs->mailchimplist; ?>" />
				<?php
					echo JHTML::tooltip(JText::_("COM_DIGISTORE_MAILCHIMPLIST_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
				?>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<a class="modal digi_video" rel="{handler: 'iframe', size: {x: 750, y: 435}}" href="index.php?option=com_digistore&controller=digistoreAbout&task=vimeo&id=38539781">
					<img src="<?php echo JURI::base(); ?>components/com_digistore/assets/images/icon_video.gif" class="video_img" />
					<?php echo JText::_("COM_DIGISTORE_VIDEO_PROD_MAILCHIMP"); ?>
				</a>
			</td>
		</tr>
	</table>
</fieldset>

<!-- Registration -->
<fieldset class="adminform">
	<legend>
		<?php echo JText::_('DIGI_REGISTRATION');?>
	</legend>
	
	<table class="table table-striped">

		<tr>
			<td>
			<?php  echo JText::_('VIEWCONFIGASKSHIPPING'); ?>
			</td>
			<td>
				<select name="askforship" id="askforship">
					<option value="1" <?php echo (($configs->askforship != '0')?'selected':''); ?>><?php echo JText::_('VIEWCONFIGCOPYTOADMINYES'); ?></option>
					<option value="0" <?php echo (($configs->askforship == '0')?'selected':''); ?>><?php echo JText::_('VIEWCONFIGCOPYTOADMINYESNO'); ?></option>
				</select>
				<?php
					echo JHTML::tooltip(JText::_("COM_DIGISTORE_ASKFORSHIP_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
				?>
			</td>
		</tr>

		<tr>
			<td>
				<?php  echo JText::_('DIGI_ASK_FOR_BILLING_DETAILS'); ?>
			</td>
			<td>
				<select name="askforbilling" id="askforbilling">
					<option value="1" <?php echo (($configs->askforbilling != '0')?'selected':''); ?>><?php echo JText::_('VIEWCONFIGCOPYTOADMINYES'); ?></option>
					<option value="0" <?php echo (($configs->askforbilling == '0')?'selected':''); ?>><?php echo JText::_('VIEWCONFIGCOPYTOADMINYESNO'); ?></option>
				</select>
				<?php
					echo JHTML::tooltip(JText::_("COM_DIGISTORE_ASKFORBILLING_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
				?>
			</td>
		</tr>

		<tr>
			<td>
				<?php  echo JText::_('DIGI_ASK_FOR_COMPANY_NAME'); ?>
			</td>
			<td>
				<select name="askforcompany" id="askforcompany">
					<option value="1" <?php echo (($configs->askforcompany != '0')?'selected':''); ?>><?php echo JText::_('VIEWCONFIGCOPYTOADMINYES'); ?></option>
					<option value="0" <?php echo (($configs->askforcompany == '0')?'selected':''); ?>><?php echo JText::_('VIEWCONFIGCOPYTOADMINYESNO'); ?></option>
				</select>
				<?php
					echo JHTML::tooltip(JText::_("COM_DIGISTORE_ASKFORCOMPANY_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
				?>
			</td>
		</tr>

		<tr>
			<td valign="top">
				<?php echo JText::_('VIEWCONFIGTOPCOUNTRIES'); ?>
			</td>
			<td nowrap>
				<?php echo $lists['topcountries'];?>
				<?php
					echo JHTML::tooltip(JText::_("COM_DIGISTORE_COUNTRY_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
				?>
			</td>
		</tr>

		<tr>
			<td>
				<?php echo JText::_('VIEWCONFIGTERMSANDCOND'); ?>
			</td>
			<td nowrap="nowrap">
				<select name="askterms" id="askterms">
					<option value="1" <?php echo (($configs->askterms == '1')?'selected':''); ?>><?php echo JText::_('VIEWCONFIGCOPYTOADMINYES');; ?></option>
					<option value="0" <?php echo (($configs->askterms != '1')?'selected':''); ?>><?php echo JText::_('VIEWCONFIGCOPYTOADMINYESNO'); ?></option>
				</select>
			</td>
		</tr>
		
		<tr>
			<td>
				<?php echo JText::_('VIEWCONFIGTERMSANDCONDID'); ?>
			</td>
			<td>
				<input type="text" name="termsid" id="termsid" value="<?php echo (($configs->termsid >0) ?$configs->termsid:'');?>" />
				<?php
					echo JHTML::tooltip(JText::_("COM_DIGISTORE_TERMSID_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
				?>
			</td>
		</tr>
</table>
</fieldset>

<!-- Buying Process -->
<fieldset class="adminform">
	<legend>
		<?php echo JText::_('DIGI_BUIYNG_PROCESS');?>
	</legend>
	
	<table class="admintable table table-striped">

		<tr>
			<td>
				<?php echo JText::_('VIEWCONFIGAFTERADDING'); ?>
			</td>
			<td>
				<select name="afteradditem">
					<option value="0" <?php echo $configs->afteradditem == '0'?"selected":""; ?> ><?php echo JText::_('VIEWCONFIGAFTERADDINGTOCART'); ?></option>
					<option value="1" <?php echo $configs->afteradditem == '1'?"selected":"";?>><?php echo JText::_('VIEWCONFIGAFTERADDINGSTAYONPROD'); ?></option>
					<option value="2" <?php echo $configs->afteradditem == '2'?"selected":"";?>><?php echo JText::_('VIEWCONFIGAFTERADDINGOPENGRAY'); ?></option>
				</select>
				<?php
					echo JHTML::tooltip(JText::_("COM_DIGISTORE_AFTERADDITEM_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
				?>
			</td>
		</tr>

		<tr>
			<td>
				<?php echo JText::_('VIEWCONFIG_CONTINUE_SHOPPING_URL'); ?>
			</td>
			<td nowrap>
				<input name="continue_shopping_url" type="text" size="100" value="<?php echo (isset($configs->continue_shopping_url)&&$configs->continue_shopping_url != '')?$configs->continue_shopping_url:'' ; ?>">
				<?php
					echo JHTML::tooltip(JText::_("COM_DIGISTORE_CONTINUE_SHOPPING_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
				?>
				<br/>
				<br/>
				<?php echo JText::_('VIEWCONFIG_CONTINUE_SHOPPING_URL_DESC'); ?>
			</td>
		</tr>

		<tr>
			<td>
				<?php echo JText::_('DEFAULT_PAYMENT'); ?>
			</td>
			<td nowrap>
				<select name="default_payment">
					<option value=""></option><?php
					$db = JFactory::getDBO();
					$condtion = array(0 => '\'payment\'');
					$condtionatype = join(',',$condtion);
					if(JVERSION >= '1.6.0')
					{
						$query = "SELECT extension_id as id,name,element,enabled as published
								  FROM #__extensions
								  WHERE folder in ($condtionatype) AND enabled=1";
					}
					else
					{
						$query = "SELECT id,name,element,published
								  FROM #__plugins
								  WHERE folder in ($condtionatype) AND published=1";
					}
					$db->setQuery($query);
					$gatewayplugin = $db->loadobjectList();

					$lang = JFactory::getLanguage();
					$options = array();
					$options[] = JHTML::_('select.option', '', 'Select payment gateway');
					foreach($gatewayplugin as $gateway)
					{
						$gatewayname = strtoupper(str_replace('plugpayment', '',$gateway->element));
						$lang->load('plg_payment_' . strtolower($gatewayname), JPATH_ADMINISTRATOR);
						echo '<option value="' . $gateway->element . '" ' . ($configs->default_payment == $gateway->element ? "selected" : "") . '>' . JText::_($gatewayname) . '</option>';
					} ?>
				</select><?php
				echo JHTML::tooltip(JText::_("DEFAULT_PAYMENT_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip'); ?>
			</td>
		</tr>

</table>
</fieldset>

<fieldset class="adminform">
	<legend>
		<?php echo JText::_('DIGI_CHECKOUT');?>
	</legend>
	<table class="admintable table table-striped">
		<?php /* ?>
		<tr class="<?php echo "row$k"; $k = 1 - $k;?>">
			<td>
				<?php echo JText::_('VIEWCONFIGTAKECHECKOUT'); ?>
			</td>
			<td>
				<select name="takecheckout" >
					<option value="1" <?php echo (($configs->takecheckout != '2')?'selected':''); ?>><?php //echo JText::_('VIEWCONFIGTAKECHECKOUTSUMMARY'); ?></option>
					<option value="2" <?php echo (($configs->takecheckout == '2')?'selected':''); ?>><?php //echo JText::_('VIEWCONFIGTAKECHECKOUTPAY'); ?></option>
				</select>
				<?php
					echo JHTML::tooltip(JText::_("COM_DIGISTORE_TAKECHECKOUT_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
				?>
			</td>
		</tr>
		<?php */ ?>

		<tr>
			<td>
				<?php echo JText::_('DIGI_SHOW_STEPS'); ?>
			</td>
			<td>
				<select name="show_steps">
					<option value="0" <?php echo $configs->show_steps != '1'?"selected":""; ?> ><?php echo JText::_('VIEWCONFIGCOPYTOADMINYES'); ?></option>
					<option value="1" <?php echo $configs->show_steps == '1'?"selected":"";?>><?php echo JText::_('VIEWCONFIGCOPYTOADMINYESNO'); ?></option>
				</select>
				<?php
					echo JHTML::tooltip(JText::_("DIGI_SHOW_STEPS_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
				?>
			</td>
		</tr>

		<tr>
			<td>
				<?php  echo JText::_('VIEWCONFIGCUSTOMERALLOW'); ?>
			</td>

			<td>
				<select name="allowcustomerchoseclass" >
					<option value="1" <?php echo (($configs->allowcustomerchoseclass == '1')?'selected':''); ?>><?php echo JText::_('VIEWCONFIGALLOWCTAXCLASSFE'); ?></option>
					<option value="2" <?php echo (($configs->allowcustomerchoseclass != '1')?'selected':''); ?>><?php echo JText::_('VIEWCONFIGALLOWCTAXCLASSBE'); ?></option>
				</select>
				<?php
					echo JHTML::tooltip(JText::_("COM_DIGISTORE_ALLOWCUSTOMER_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
				?>
			</td>
		</tr>

		<tr>
			<td>
				<?php echo JText::_('VIEWCONFIGAFTERBUYING'); ?>
			</td>
			<td>
				<select name="afterpurchase">
					<option value="0" <?php echo $configs->afterpurchase != '1'?"selected":""; ?> ><?php echo JText::_('VIEWCONFIGAFTERBUYINGTOLICENSE'); ?></option>
					<option value="1" <?php echo $configs->afterpurchase == '1'?"selected":"";?>><?php echo JText::_('VIEWCONFIGAFTERBUYINGTOORDER'); ?></option>
				</select>
				<?php
					echo JHTML::tooltip(JText::_("COM_DIGISTORE_AFTERPURCHASE_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
				?>
			</td>
		</tr>
</table>
</fieldset>

<!-- iDev Affiliate Integration -->
<fieldset class="adminform">
	<legend>
		<?php echo JText::_('DIGI_IDEV_AFFILIATE_INTEGRATION');?>
	</legend>
	<table class="admintable table">
		
		<tr class="warning">
			<td nowrap="nowrap" colspan="2">
				<label class="radio">
					<input type="radio" name="idevaff" id="idevaff-notapplied" value="notapplied" <?php echo (($configs->idevaff != 'standalone' && $configs->idevaff != 'component')?'checked':''); ?> />
					<?php echo JText::_('VIEWCONFIGUSEIDEVNOUSE'); ?>
				</label>
			</td>
		</tr>
		<tr class="info">
			<td nowrap="nowrap" colspan="2">
				<label class="radio">
					<input type="radio" name="idevaff" id="idevaff-standalone" value="standalone" <?php echo (($configs->idevaff == 'standalone')?'checked':''); ?> />
					<?php echo JText::_('VIEWCONFIGSTANDALONE'); ?>
				</label>
			</td>
		</tr>
		<tr class="info">
			<td nowrap="nowrap">
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php echo JText::_('VIEWCONFIGPATHTOSTANDALONE'); ?>
			</td>
			<td>
				<input style="float:none;" type="text" name="idevpath" value="<?php echo $configs->idevpath; ?>" />
			</td>
		</tr>
		<tr class="success">
			<td nowrap="nowrap" colspan="2">
				<label class="radio">
					<input type="radio" name="idevaff" id="idevaff-component" value="component" <?php echo (($configs->idevaff == 'component')?'checked':''); ?> />
					<?php echo JText::_('VIEWCONFIGJOOMLAVRSION'); ?>
				</label>
			</td>
		</tr>
		<tr class="success">
			<td nowrap="nowrap">
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php echo JText::_('VIEWCONFIGIDEVORDER'); ?>
			</td>
			<td> 
				<input style="float:none;" type="text" name="orderidvar" value="<?php echo $configs->orderidvar; ?>" />
		</tr>
		<tr class="success">
			<td>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php echo JText::_('VIEWCONFIGIDEVAMOUNT'); ?>
			</td>
			<td> 
				<input style="float:none;" type="text" name="ordersubtotalvar" value="<?php echo $configs->ordersubtotalvar; ?>" />
			</td>
		</tr>
	</table>
</fieldset>
