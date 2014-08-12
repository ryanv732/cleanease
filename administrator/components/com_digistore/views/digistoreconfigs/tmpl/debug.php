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
		<table style="display:none">
		 <tr class="<?php  echo "row$k"; ?>">
				<td> 
				<?php  echo _DEBUG_STORE;?>
				</td>

				<td> 
				 <input name="debugstore" type="checkbox" value="1" <?php  echo (($configs->debugstore != '0')?'checked':'');	?>>
				</td>
				<?php 
				$k = 1 - $k;
				?>
			</tr>
			 <tr class="<?php  echo "row$k"; ?>">
				<td> 
				<?php  echo _DUMPTOFILE;?>
				</td>

				<td> 
				 <input name="dumptofile" type="checkbox" value="1" <?php  echo (($configs->dumptofile != '0')?'checked':'');	?>>
				</td>
				<?php 
				$k = 1 - $k;
				?>
			</tr>
			<tr class="<?php  echo "row$k"; ?>">
				<td> 
				<?php  echo _DUMPVARS;?>
				</td>

				<td> 
				 <textarea name="dumpvars" ><?php  echo (
(strlen($configs->dumpvars) > 0 )?
$configs->dumpvars:'');
?></textarea>
				</td>
				<?php 
				$k = 1 - $k;
				?>
			</tr>
		 </table>
