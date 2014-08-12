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
$document->addStyleSheet("components/com_digistore/assets/css/digistore.css");

?>
<div class="digistore">
<fieldset>
	<legend><?php echo JText::_('Select product type'); ?></legend>

		<table>
			<tr>
				<td align="right">
					<a class="modal digi_video" rel="{handler: 'iframe', size: {x: 750, y: 435}}" href="index.php?option=com_digistore&controller=digistoreAbout&task=vimeo&id=38448917">
						<img src="<?php echo JURI::base(); ?>components/com_digistore/assets/images/icon_video.gif" class="video_img" />
						<?php echo JText::_("COM_DIGISTORE_VIDEO_PROD_ADDPROD"); ?>
					</a>
				</td>
			</tr>
		</table>

		<div class="media">
			<a class="pull-left" href="<?php echo JURI::root()."administrator/index.php?option=com_digistore&controller=digistoreProducts&task=add&producttype=0"; ?>">
				<img class="media-object" src="<?php echo JURI::base(); ?>components/com_digistore/assets/images/download_64.png">
			</a>
			<div class="media-body">
				<h4 class="media-heading"><a href="<?php echo JURI::root()."administrator/index.php?option=com_digistore&controller=digistoreProducts&task=add&producttype=0"; ?>"><?php echo JText::_("DIGI_DONWNLOADABLE_NO_REQUIRED"); ?></a></h4>
				<?php echo JText::_('COM_DIGISTORE_PRODDOWNNOREQ_TIP'); ?>
			</div>
		</div>

		<div class="media">
			<a class="pull-left" href="<?php echo JURI::root()."administrator/index.php?option=com_digistore&controller=digistoreProducts&task=add&producttype=1"; ?>">
				<img class="media-object" src="<?php echo JURI::base(); ?>components/com_digistore/assets/images/download_64.png">
			</a>
			<div class="media-body">
				<h4 class="media-heading"><a href="<?php echo JURI::root()."administrator/index.php?option=com_digistore&controller=digistoreProducts&task=add&producttype=1"; ?>"><?php echo JText::_("DIGI_DONWNLOADABLE_REQUIRED"); ?></a></h4>
				<?php echo JText::_('COM_DIGISTORE_PRODDOWNREQ_TIP'); ?>
			</div>
		</div>

		<div class="media">
			<a class="pull-left" href="<?php echo JURI::root()."administrator/index.php?option=com_digistore&controller=digistoreProducts&task=add&producttype=2"; ?>">
				<img class="media-object" src="<?php echo JURI::base(); ?>components/com_digistore/assets/images/shipable_64.png">
			</a>
			<div class="media-body">
				<h4 class="media-heading"><a href="<?php echo JURI::root()."administrator/index.php?option=com_digistore&controller=digistoreProducts&task=add&producttype=2"; ?>"><?php echo JText::_("DIGI_SHIPPABLE_PRODUCT"); ?></a></h4>
				<?php echo JText::_('COM_DIGISTORE_PRODSHIPPABLE_TIP'); ?>
			</div>
		</div>

		<div class="media">
			<a class="pull-left" href="<?php echo JURI::root()."administrator/index.php?option=com_digistore&controller=digistoreProducts&task=add&producttype=3"; ?>">
				<img class="media-object" src="<?php echo JURI::base(); ?>components/com_digistore/assets/images/package_64.png">
			</a>
			<div class="media-body">
				<h4 class="media-heading"><a href="<?php echo JURI::root()."administrator/index.php?option=com_digistore&controller=digistoreProducts&task=add&producttype=3"; ?>"><?php echo JText::_("DIGI_PACKAGE_NO_UPLOAD"); ?></a></h4>
				<?php echo JText::_('COM_DIGISTORE_PRODPACKAGE_TIP'); ?>
			</div>
		</div>

		<div class="media">
			<a class="pull-left" href="<?php echo JURI::root()."administrator/index.php?option=com_digistore&controller=digistoreProducts&task=add&producttype=4"; ?>">
				<img class="media-object" src="<?php echo JURI::base(); ?>components/com_digistore/assets/images/service_64.png">
			</a>
			<div class="media-body">
				<h4 class="media-heading"><a href="<?php echo JURI::root()."administrator/index.php?option=com_digistore&controller=digistoreProducts&task=add&producttype=4"; ?>"><?php echo JText::_("DIGI_SERVICE_NO_UPLOAD"); ?></a></h4>
			<?php echo JText::_('COM_DIGISTORE_PRODSERVICE_TIP'); ?>
			</div>
		</div>
		<form action="index.php" method="post" name="adminForm" id="adminForm" >
			<input type="hidden" name="option" value="com_digistore"/>
			<input type="hidden" name="controller" value="digistoreProducts" />
			<input type="hidden" name="task" value=""/>
		</form>
</fieldset>
</div>