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
	<legend><?php echo JText::_('VIEWCONFIGCONTENTTEMPLATES');?></legend>

		<table width="100%">
			<tr>
				<td class="header_zone">
					<?php
						echo JText::_("HEADER_CONTENT_SETTINGS");
					?>
				</td>
			</tr>
			<tr>
				<td colspan="3" align="right">
					<a class="modal digi_video" rel="{handler: 'iframe', size: {x: 750, y: 435}}" href="index.php?option=com_digistore&controller=digistoreAbout&task=vimeo&id=38437584">
						<img src="<?php echo JURI::base(); ?>components/com_digistore/assets/images/icon_video.gif" class="video_img" />
						<?php echo JText::_("COM_DIGISTORE_VIDEO_LAYOUT_CONTENT"); ?>				  
					</a>
				</td>
			</tr>
		</table>

	   <table class="admintable">
		<tr>
		<th>
		<?php
			echo JText::_('VIEWCONFIGSUCCTRANS');
		?>
		<?php
			echo JHTML::tooltip(JText::_("COM_DIGISTORE_TRANSACTIONTEMP_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
		?>
		</th>
		</tr>
		<tr>
		<td style="width: 100%;">
			<textarea id="thankshtml" name="thankshtml" class="useredactor" style="width:100%;height:450px;"><?php echo $configs->thankshtml;?></textarea>
		</td>
		</tr>


		<tr>
			<th>
			<?php
				echo JText::_('VIEWCONFIGERRTEMPLATE');
			?>
			<?php
				echo JHTML::tooltip(JText::_("COM_DIGISTORE_FAILEDTRANSACTION_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
			?>
			</th>
		</tr>

		<tr>
		<td>
			<textarea id="ftranshtml" name="ftranshtml" class="useredactor" style="width:100%;height:450px;"><?php echo $configs->ftranshtml;?></textarea>
		</td>
		</tr>

		<tr>
			<th>
			<?php
				echo JText::_('VIEWCONFIGPENDINGTEMPLATE');;
			?>
			</th>
		</tr>

		<tr>
		<td>
			<textarea id="pendinghtml" name="pendinghtml" class="useredactor" style="width:100%;height:450px;"><?php echo $configs->ftranshtml;?></textarea>
		</td>
		</tr>

		</table>
	</fieldset>