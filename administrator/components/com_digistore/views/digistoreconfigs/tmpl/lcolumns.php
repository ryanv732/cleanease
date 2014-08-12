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
<tr>
	<th colspan="2"  class="second_title_dgst"><?php echo JText::_('VIEWCONFIGLCOLUMNS');?></th>
</tr>
<tr>
	<td colspan="2">
		<input type="checkbox" name="showlid" value="1"  <?php echo ($configs->showlid == '1' ?"checked":"");?> />

		<?php echo JText::_('VIEWCONFIGSHOWLID');?>
	</td>
</tr>

<tr>
	<td colspan="2">
		<input type="checkbox" name="showlterms" value="1"  <?php echo ($configs->showlterms == '1' ?"checked":"");?> />

		<?php echo JText::_('VIEWCONFIGSHOWLTERMS');?>
	</td>
</tr>

<tr>
	<td colspan="2">
		<input type="checkbox" name="showlexpires" value="1"  <?php echo ($configs->showlexpires == '1' ?"checked":"");?> />

		<?php echo JText::_('VIEWCONFIGSHOWLEXPIRES');?>
	</td>
</tr>

<tr> 
	<td colspan="2">
		<input type="checkbox" name="showlprod" value="1"  <?php echo ($configs->showlprod == '1' ?"checked":"");?> />
		<?php echo JText::_('VIEWCONFIGSHOWLPROD');?>
	</td>
</tr>

<tr> 
	<td colspan="2">

		<input type="checkbox" name="showloid" value="1"  <?php echo ($configs->showloid == '1' ?"checked":"");?> />


		<?php echo JText::_('VIEWCONFIGSHOWLOID');?>
	</td>
</tr>

<tr> 
	<td colspan="2">

		<input type="checkbox" name="showldate" value="1"  <?php echo ($configs->showldate == '1' ?"checked":"");?> />
		<?php echo JText::_('VIEWCONFIGSHOWLDATE');?>
	</td>
</tr>

<tr> 
	<td colspan="2">
		<input type="checkbox" name="showldown" value="1"  <?php echo ($configs->showldown == '1' ?"checked":"");?> />
		<?php echo JText::_('VIEWCONFIGSHOWLDOWN');?>
	</td>
</tr>
<tr>
	<td colspan="2">
		<input type="checkbox" name="showldomain" value="1"  <?php echo ($configs->showldomain == '1' ?"checked":"");?> />
		<?php echo JText::_('VIEWCONFIGSHOWLDOMAIN');?>
	</td>
</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
						</tr>						