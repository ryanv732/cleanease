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
	<legend><?php echo JText::_('VIEWCONFIGEMAILS');?></legend>

	<table width="100%">
		<tr>
			<td class="header_zone">
				<?php
					echo JText::_("HEADER_EMAIL_SETTINGS");
				?>
			</td>
		</tr>
	</table>


	<table class="admintable">


	<tr><td>
	<b><br><?php echo (JText::_('VIEWCONFIGTEMPLATEVARS')) ;?></b><br>
		<table border=0  >
			<tr>
				<td width=200><?php echo JText::_('VIEWCONFIGTEMPLATEVARSSITENAME'); ?></td>
				<td width=200><b>[SITENAME]</b></td>
			</tr>
			<tr>
				<td width=200><?php echo JText::_('VIEWCONFIGTEMPLATEVARSSITEURL'); ?></td>
				<td width=200><b>[SITEURL]</b></td>
			</tr>
			<tr>
				<td width=200><?php echo JText::_('VIEWCONFIGTEMPLATEVARSUSERNAME'); ?></td>
				<td width=200><b>[CUSTOMER_USER_NAME]</b></td>
			</tr>
			 <tr>
				<td width=200><?php echo JText::_('VIEWCONFIGTEMPLATEVARSCUSTOMERNAME');?></td>
				<td width=200><b>[CUSTOMER_FIRST_NAME]</b></td>
			</tr>
			<tr>
				<td width=200><?php echo JText::_('VIEWCONFIGTEMPLATEVARSLANSTNAME'); ?></td>
				<td width=200><b>[CUSTOMER_LAST_NAME]</b></td>
			</tr>
			<tr>
				<td width=200><?php echo JText::_('VIEWCONFIGTEMPLATEVARSEMAIL'); ?></td>
				<td width=200><b>[CUSTOMER_EMAIL]</b></td>
						<tr>
				<td width=200><?php echo JText::_('VIEWCONFIGTEMPLATEVARSTODAY'); ?></td>
				<td width=200><b>[TODAY_DATE]</b></td>
			</tr>
			<tr>
				<td width=200><?php echo JText::_('VIEWCONFIGTEMPLATEPWD'); ?></td>
				<td width=200><b>[CUSTOMER_PASSWORD]</b></td>
			</tr>
			<tr>
				<td width=200><?php echo JText::_('VIEWCONFIGTEMPLATECUSTCOMPANY'); ?></td>
				<td width=200><b>[CUSTOMER_COMPANY_NAME]</b></td>
			</tr>
			</tr>
		</table>
		</td></tr>

	<tr><td style="width: 100%;" >
	<b><br><?php  echo JText::_('VIEWCONFIGREGISTRATIONCONFIRM'); ?>
			<?php
				echo JHTML::tooltip(JText::_("COM_DIGISTORE_CONFIRMEMAIL_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
			?>
	</b><br>
		</tr></td>
		<tr><td>
		<?php echo JText::_('VIEWCONFIGSUBJ'); ?>: <input type="text" value="<?php echo $this->register->subject;?>" name="registersubj" size="60"/>

		</td></tr>
		<tr>
			<td style="width: 100%;" >
			<textarea id="register_editor" name="register_editor" class="useredactor" style="width:100%;height:550px;"><?php echo $this->register->body;?></textarea>
	</td></tr>
	<tr><td>
	<b><br><?php echo (JText::_('VIEWCONFIGTEMPLATEVARS')) ;?></b><br>
		<table border=0>
			<tr>
				<td width=200><?php echo JText::_('VIEWCONFIGTEMPLATEVARSSITENAME'); ?></td>
				<td width=200><b>[SITENAME]</b></td>
			</tr>
			<tr>
				<td width=200><?php echo JText::_('VIEWCONFIGTEMPLATEVARSSITEURL'); ?></td>
				<td width=200><b>[SITEURL]</b></td>
			</tr>
			<tr>
				<td width=200><?php echo JText::_('VIEWCONFIGTEMPLATEVARSUSERNAME'); ?></td>
				<td width=200><b>[CUSTOMER_USER_NAME]</b></td>
			</tr>
			 <tr>
				<td width=200><?php echo JText::_('VIEWCONFIGTEMPLATEVARSCUSTOMERNAME');?></td>
				<td width=200><b>[CUSTOMER_FIRST_NAME]</b></td>
			</tr>
			<tr>
				<td width=200><?php echo JText::_('VIEWCONFIGTEMPLATEVARSLANSTNAME'); ?></td>
				<td width=200><b>[CUSTOMER_LAST_NAME]</b></td>
			</tr>
			<tr>
				<td width=200><?php echo JText::_('VIEWCONFIGTEMPLATEVARSEMAIL'); ?></td>
				<td width=200><b>[CUSTOMER_EMAIL]</b></td>
			</tr>
						<tr>
				<td width=200><?php echo JText::_('VIEWCONFIGTEMPLATEVARSTODAY'); ?></td>
				<td width=200><b>[TODAY_DATE]</b></td>
			</tr>
						<tr>
				<td width=200><?php echo JText::_('VIEWCONFIGTEMPLATEVARSOID'); ?></td>
				<td width=200><b>[ORDER_ID]</b></td>
			</tr>
						<tr>
				<td width=200><?php echo JText::_('VIEWCONFIGTEMPLATEVARSAMOUNT'); ?></td>
				<td width=200><b>[ORDER_AMOUNT]</b></td>
			</tr>
						<tr>
				<td width=200><?php echo JText::_('VIEWCONFIGTEMPLATEVARSLICNUM'); ?></td>
				<td width=200><b>[NUMBER_OF_LICENSES]</b></td>
			</tr>
									<tr>
				<td width=200><?php echo JText::_('VIEWCONFIGTEMPLATEVARSPRODB'); ?></td>
				<td width=200><b>[PRODUCTS]</b></td>
			</tr>

			<tr >
				<td width=200><?php echo JText::_('VIEWCONFIGTEMPLATEVARSPROMO'); ?></td>
				<td width=200><b>[PROMO]</b></td>
			</tr> 
			<tr>
				<td width=200><?php echo JText::_('VIEWCONFIGTEMPLATECUSTCOMPANY'); ?></td>
				<td width=200><b>[CUSTOMER_COMPANY_NAME]</b></td>
			</tr>
		</table></td>
	</tr>


		<tr><td>
	<b><br><?php echo JText::_('VIEWCONFIGORDERCONFORMATIONEMAIL');?>
			<?php
				echo JHTML::tooltip(JText::_("COM_DIGISTORE_ORDEREMAIL_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
			?>
	</b><br>
			<?php echo JText::_('VIEWCONFIGSUBJ'); ?>: <input type="text" value="<?php echo $this->order->subject;?>" name="ordersubj" size="60"/>
		</tr></td>
	<tr>
		<td style="width: 100%;">
			<textarea id="order_editor" name="order_editor" class="useredactor" style="width:100%;height:550px;"><?php echo $this->order->body;?></textarea>
		</td></tr>

<?php
	$db = JFactory::getDBO();
	$sql = "select count(*) from #__extensions where `element` = 'ijdigistoreoffline_plugin'";
	$db->setQuery($sql);
	$db->query();
	$result = $db->loadResult();

	if($result > 0){
?>

	<tr><td>
	<b><br><?php echo (JText::_('VIEWCONFIGTEMPLATEVARS')) ;?></b><br>
		<table border=0>
			<tr>
				<td width=200><?php echo JText::_('VIEWCONFIGTEMPLATEVARSSITENAME'); ?></td>
				<td width=200><b>[SITENAME]</b></td>
			</tr>
			<tr>
				<td width=200><?php echo JText::_('VIEWCONFIGTEMPLATEVARSSITEURL'); ?></td>
				<td width=200><b>[SITEURL]</b></td>
			</tr>
			<tr>
				<td width=200><?php echo JText::_('VIEWCONFIGTEMPLATEVARSUSERNAME'); ?></td>
				<td width=200><b>[CUSTOMER_USER_NAME]</b></td>
			</tr>
			 <tr>
				<td width=200><?php echo JText::_('VIEWCONFIGTEMPLATEVARSCUSTOMERNAME');?></td>
				<td width=200><b>[CUSTOMER_FIRST_NAME]</b></td>
			</tr>
			<tr>
				<td width=200><?php echo JText::_('VIEWCONFIGTEMPLATEVARSLANSTNAME'); ?></td>
				<td width=200><b>[CUSTOMER_LAST_NAME]</b></td>
			</tr>
			<tr>
				<td width=200><?php echo JText::_('VIEWCONFIGTEMPLATEVARSEMAIL'); ?></td>
				<td width=200><b>[CUSTOMER_EMAIL]</b></td>
			</tr>
						<tr>
				<td width=200><?php echo JText::_('VIEWCONFIGTEMPLATEVARSTODAY'); ?></td>
				<td width=200><b>[TODAY_DATE]</b></td>
			</tr>
			</tr>
						<tr>
				<td width=200><?php echo JText::_('VIEWCONFIGTEMPLATEVARSOID'); ?></td>
				<td width=200><b>[ORDER_ID]</b></td>
			</tr>
			</tr>
						<tr>
				<td width=200><?php echo JText::_('VIEWCONFIGTEMPLATEVARSAMOUNT'); ?></td>
				<td width=200><b>[ORDER_AMOUNT]</b></td>
			</tr>
			</tr>
						<tr>
				<td width=200><?php echo JText::_('VIEWCONFIGTEMPLATEVARSLICNUM'); ?></td>
				<td width=200><b>[NUMBER_OF_LICENSES]</b></td>
			</tr>
									<tr>
				<td width=200><?php echo JText::_('VIEWCONFIGTEMPLATEVARSPRODB'); ?></td>
				<td width=200><b>[PRODUCTS]</b></td>
			</tr>

			<tr >
				<td width=200><?php echo JText::_('VIEWCONFIGTEMPLATEVARSPROMO'); ?></td>
				<td width=200><b>[PROMO]</b></td>
			</tr> 
			<tr>
				<td width=200><?php echo JText::_('VIEWCONFIGTEMPLATECUSTCOMPANY'); ?></td>
				<td width=200><b>[CUSTOMER_COMPANY_NAME]</b></td>
			</tr>
		</table></td>
	</tr>

	<tr>
		<td>
			<b><br>
			<?php echo JText::_('VIEWCONFIGAPPROVEDEMAIL');?></b><br>
			<?php echo JText::_('VIEWCONFIGSUBJ'); ?>: <input type="text" value="<?php echo $this->approved->subject;?>" name="approvedsubj" size="60"/>
	</td>
   	</tr>
	<tr>
		<td style="width: 100%;">
			<textarea id="approved_editor" name="approved_editor" class="useredactor" style="width:100%;height:550px;"><?php echo $this->approved->body;?></textarea>
		</td>
	</tr>
<?php
	}
?>
</table>
	</fieldset>
