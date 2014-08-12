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

echo '<div class="digistore">';
echo digistoreHelper::ShowHomeDescriptionBlock($this->configs);

$k = 0;
$n = count ($this->cats);


if ($n < 1):
	echo JText::_('DSNOCAT');
else: ?>
	<div>
			<select name="" method="get" action="" onchange="(this.selectedIndex > 0) ?window.location=this.options[this.selectedIndex].value:'';" >
			<option value="-1"> <?php echo JText::_("DSSELECTCAT"); ?></option>

<?php
	$Itemid = JRequest::getInt("Itemid", "0");
	for ($i = 0; $i < $n; $i++):
		$cat = $this->cats[$i];
		$id = $cat->id;

		// if (count($cat->cats) > 0) {
			// $link = JRoute::_("index.php?option=com_digistore&controller=digistoreCategories&task=view&cid=".$id."&Itemid=".$Itemid);
		// } else {
			$link = JRoute::_("index.php?option=com_digistore&controller=digistoreProducts&task=list&cid=".$id."&Itemid=".$Itemid);
		// }
?>
			<?php
			echo "<option value='".$link."' >".$cat->name ."</option>";
			?>

<?php
		$k = 1 - $k;
	endfor;
?>

			</select>
</div><?php
	if($this->pagination->getPagesLinks() != ''):?>
		<div class="pagination pagination-centered"><?php echo $this->pagination->getPagesLinks(); ?></div>
		<div style="text-align:center;"><?php echo $this->pagination->getPagesCounter(); ?></div><?php
	endif;
endif;

echo '</div>';
$view = JRequest::getVar('view');
if($view=='digistoreCategories'){
	echo digistoreHelper::powered_by();
}
