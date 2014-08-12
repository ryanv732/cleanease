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

jimport('joomla.html.pane');

$document = JFactory::getDocument();
$document->addStyleSheet("components/com_digistore/assets/css/digistore.css");

?>

		<script language="javascript" type="text/javascript">
		<!--
		function submitbutton(pressbutton) {

			var form = document.adminForm;
			if (pressbutton == 'cancel') {
				submitform(pressbutton);
				return;
			}
			submitform( pressbutton );
		}
		-->
		</script>

 <form action="index.php" method="post" name="adminForm" id="adminForm">

		<fieldset class="adminform">
			<legend><?php echo JText::_('VIEWPRODUCTCLASS');?></legend>
				<table class="admintable">

				<tr>
					<td>
					<?php echo JText::_('VIEWPRODUCTCLASSNAME');?>:
					</td>
					<td >
					<input class="text_area" type="text" name="name" value="<?php echo stripslashes( $this->pclass->name ); ?>" size="50" maxlength="50" title="<?php echo DSTAXPRODUCTCLASSNAME;?>" />
					</td>
				</tr>
				<tr>
					<td>
					<?php echo JText::_('VIEWPRODUCTCLASSORDERING');;?>:
					</td>
					<td>
					<?php echo $this->lists['ordering']; ?>
					</td>
				</tr>
				<tr>
					<td>
					<?php echo JText::_('VIEWPRODUCTCLASSPUBLISHED');?>
					</td>
					<td>
					<?php echo $this->lists['published']; ?>
					</td>
				</tr>

				</table>
		</fieldset>
			<input type="hidden" name="option" value="com_digistore" />
			<input type="hidden" name="id" value="<?php echo $this->pclass->id; ?>" />
			<input type="hidden" name="task" value="" />
		<input type="hidden" name="controller" value="digistoreProductClasses" />
		<input type="hidden" name="images" id="images" value="" />
		</form>