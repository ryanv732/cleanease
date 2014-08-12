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
							<th colspan="2"  class="second_title_dgst"><?php echo JText::_('VIEWCONFIGOCOLUMNS');?></th>
						</tr>
						<tr>
							<td colspan="2">
							<input type="checkbox" name="showoid" value="1"  <?php echo ($configs->showoid == '1' ?"checked":"");?> />
							<?php echo JText::_('VIEWCONFIGSHOWOID');?>
							</td>
						</tr>

						<tr> 
							<td colspan="2">  
					<input type="checkbox" name="showoipurch" value="1"  <?php echo ($configs->showoipurch == '1' ?"checked":"");?> />

							<?php echo JText::_('VIEWCONFIGSHOWOIPURCH');?>
							</td>
						</tr>
						<tr> 
							<td colspan="2">  

					<input type="checkbox" name="showolics" value="1"  <?php echo ($configs->showolics == '1' ?"checked":"");?> />


							<?php echo JText::_('VIEWCONFIGSHOWOLICS');?>
							</td>
						</tr>
						<tr> 
							<td colspan="2">  

					<input type="checkbox" name="showopaid" value="1"  <?php echo ($configs->showopaid == '1' ?"checked":"");?> />
							<?php echo JText::_('VIEWCONFIGSHOWOPAID');?>
							</td>
						</tr>

						<tr> 
							<td colspan="2">  

					<input type="checkbox" name="showodate" value="1"  <?php echo ($configs->showodate == '1' ?"checked":"");?> />
							<?php echo JText::_('VIEWCONFIGSHOWODATE');?>
							</td>
						</tr>

						<tr> 
							<td colspan="2">  

					<input type="checkbox" id="showorec" name="showorec" value="1"  <?php echo ($configs->showorec == '1' ?"checked":"");?> />
							<?php echo JText::_('VIEWCONFIGSHOWOREC');?>
							</td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
						</tr>						