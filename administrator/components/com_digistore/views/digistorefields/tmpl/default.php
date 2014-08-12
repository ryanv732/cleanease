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

$k = 0;
$n = count ($this->attrs);
//		echo JText::_('VIEWATTRIBNOATTRIB');
?>
<form id="adminForm" action="index.php" name="adminForm" method="post">
<div id="editcell" >
<table class="adminlist table">
<thead>

	<tr>
		<th width="5">
			<input type="checkbox" onclick="checkAll(<?php echo $n; ?>)" name="toggle" value="" />
		</th>
			<th width="20">
			<?php echo JText::_('VIEWATTRIBID');?>
		</th>
		<th>
			<?php echo JText::_('VIEWATTRIBNAME');?>
		</th>
		<th> <?php JText::_('VIEWATTRIBPUBLISHING');?>
		</th>
		<th>
			<?php echo JText::_('VIEWATTRIBOPTNUM');?>
		</th>

	</tr>
</thead>

<tbody>

<?php 
if ($n ){
	for ($i = 0; $i < $n; $i++):
	$attr = $this->attrs[$i];
	$id = $attr->id;
	$opts = explode ("\n", $attr->options);
	$numopts = count($opts);
	$checked = JHTML::_('grid.id', $i, $id);
	$link = JRoute::_("index.php?option=com_digistore&controller=digistoreAttributes&task=edit&cid[]=".$id);
	$published = JHTML::_('grid.published', $attr, $i );
?>
	<tr class="row<?php echo $k;?>"> 
		 	<td>
		 			<?php echo $checked;?>
		</td>

		 	<td>
		 			<?php echo $id;?>
		</td>
		 	<td>
		 			<a href="<?php echo $link;?>" ><?php echo $attr->name;?></a>
		</td>

		<td>
			<?php echo $published;?>
		</td>
		 	<td>
		 			<a href="<?php echo $link;?>" ><?php echo $numopts;?></a>
		</td>

	</tr>


<?php 
		$k = 1 - $k;
	endfor;
}
?>
</tbody>


</table>

</div>

<input type="hidden" name="option" value="com_digistore" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="digistoreAttributes" />
</form>