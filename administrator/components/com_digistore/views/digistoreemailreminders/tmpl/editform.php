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
$document = JFactory::getDocument();
$document->addStyleSheet("components/com_digistore/assets/css/digistore.css"); ?>

<form id="adminForm" action="index.php" name="adminForm" method="post">

	<fieldset>

		<legend><?php echo $this->action. " " . JText::_('EMAILSUBACTIONNAME') ?></legend>

		<table>
			<tr>
				<td class="header_zone" colspan="4">
					<?php
						echo JText::_("HEADER_EMAILSEDIT");
					?>
				</td>
			</tr>
			<tr>
				<td align="right">
					<a class="modal digi_video" rel="{handler: 'iframe', size: {x: 750, y: 435}}" href="index.php?option=com_digistore&controller=digistoreAbout&task=vimeo&id=38437489">
						<img src="<?php echo JURI::base(); ?>components/com_digistore/assets/images/icon_video.gif" class="video_img" />
						<?php echo JText::_("COM_DIGISTORE_VIDEO_EMAIL_REMAINDER"); ?>				  
					</a>
				</td>
			</tr>
		</table>

		<table>

			<tr>
				<td valign="top" width="100%" colspan="2">
					<div style="background:#f4f4f4;border:1px solid #999;">
						<table>
							<tr>
								<td>Site name</td>
								<td>[SITENAME]</td>
								<td>Customer email</td>
								<td>[CUSTOMER_EMAIL]</td>
								<td>First name</td>
								<td>[CUSTOMER_FIRST_NAME]</td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td>Site URL</td>
								<td>[SITEURL]</td>
								<td>User name</td>
								<td>[CUSTOMER_USER_NAME]</td>
								<td>Renew url</td>
								<td>[RENEW_URL]</td>
								<td>Product url</td>
								<td>[PRODUCT_URL]</td>
							</tr>
							<tr>
								<td>Last name</td>
								<td>[CUSTOMER_LAST_NAME]</td>
								<td>Terms</td>
								<td>[SUBSCRIPTION_TERM]</td>
								<td>License Number</td>
								<td>[LICENSE_NUMBER]</td>
								<td>My Licenses</td>
								<td>[MY_LICENSES]</td>
							</tr>
							<tr>
								<td>Product name</td>
								<td>[PRODUCT_NAME]</td>
								<td>Expiration date</td>
								<td>[EXPIRE_DATE]</td>
								<td>My Order</td>
								<td>[MY_ORDERS]</td>
								<td>Renewal Plans</td>
								<td>[RENEW_TERM]</td>
							</tr>
						</table>
					</div>
				</td>
			</tr>

			<tr>
				<td width="30"><?php echo JText::_('PLAINPUBLISHED'); ?></td>
				<td>
					<?php  echo $this->lists['published']; ?>
					<?php
						echo JHTML::tooltip(JText::_("COM_DIGISTORE_EMAILSPUBLISHED_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
					?>
				</td>
			</tr>

			<tr>
				<td width="30"><?php echo JText::_('PLAINNAME'); ?></td>
				<td>
					<input type="text" name="name" value="<?php echo $this->email->name; ?>" style="width:250px;" />
					<?php
						echo JHTML::tooltip(JText::_("COM_DIGISTORE_EMAILSNAME_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
					?>
				</td>
			</tr>

			<tr>
				<td><?php echo JText::_('TRIGGER'); ?></td>
				<td><?php
					echo $this->lists['trigger_type'];
					echo $this->lists['trigger_period'];
					echo $this->lists['trigger_calc'];
					echo $this->lists['trigger_date_calc'];
					?>
					<?php
						echo JHTML::tooltip(JText::_("COM_DIGISTORE_EMAILSTERMS_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
					?>
				</td>
			</tr>

			<tr>
				<td><?php echo JText::_('EMAILREMINDERSUBJECT'); ?></td>
				<td>
					<?php echo $this->lists['subject']; ?>
					<?php
						echo JHTML::tooltip(JText::_("COM_DIGISTORE_EMAILSSUBJECT_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
					?>
				</td>
			</tr>

			<tr>
				<td valign="top" width="100%" colspan="2">
					<?php
					echo JText::_("VIEWDSADMINCONTENT")." ";
					echo JHTML::tooltip(JText::_("COM_DIGISTORE_EMAILSBODY_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
					?>
					<textarea id="body" name="body" class="useredactor" style="width:100%;height:450px;"><?php echo $this->email->body;?></textarea>
				</td>
			</tr>

		</table>

	</fieldset>

	<input type="hidden" name="id" value="<?php echo $this->email->id; ?>"/>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_digistore" />
	<input type="hidden" name="controller" value="digistoreEmailreminders" />

</form>