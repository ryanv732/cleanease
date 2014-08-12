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
$document->addStyleSheet("components/com_digistore/assets/css/digistore.css");

	$configs = $this->configs;
	$nullDate = 0;
?>

		<script language="javascript" type="text/javascript">
		<!--

		function submitbutton(pressbutton) {
			submitform( pressbutton );
		}
		-->
		</script>

 <form action="index.php" method="post" name="adminForm" id="adminForm">
	<fieldset class="adminform">
	<table width="100%">
<tr>
	<th><?php echo $this->lang_file_path;?></th>
</tr><tr>
	<td>
		<textarea cols="100" rows="100" name="langfiledata" style="width:100%"><?php echo $this->langfiledata; ?></textarea>
	</td>
</tr>
	</table>

	</fieldset>

		<input type="hidden" name="images" value="" />
			<input type="hidden" name="option" value="com_digistore" />
			<input type="hidden" name="id" value="<?php echo $this->lang_id; ?>" />
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="type" value="<?php echo $this->type;?>" />

		<input type="hidden" name="controller" value="digistoreLanguages" />
		</form>

