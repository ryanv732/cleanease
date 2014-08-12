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
?>

<h4><?php echo JText::_("CATEGORIES"); ?></h4>

<?php
$k = 0;
$n = count($this->cats);

if ($n < 1)
{ ?>
	<div class="well well-small">
		<h3><?php echo JText::_('DSNOCAT'); ?></h3>
	</div><?php
}
else
{
	$cols = $this->configs->catlayoutcol;

	if($cols < 1)
	{
		$cols = 1;
	}

	if($cols > 10)
	{
		$cols = 10;
	}

	$style_cols = floor(100/$cols);
	$rows = $this->configs->catlayoutrow;
	$k = 0;

	$span = floor(12/$cols);

	$Itemid = JRequest::getInt("Itemid", 0);
	if(trim($Itemid) == "")
	{
		$Itemid = 0;
	}

	for($i = 0; $i < $rows; $i++)
	{
		if($k >= $n)
		{
			break;
		}

		echo '<div class="row-fluid">';
		for($j = 0; $j < $cols; $j++)
		{
			if($k >= $n)
			{
				break;
			}

			$cat = $this->cats[$k];
			$id = $cat->id;

			if($j == ($cols-1))
			{
				$separator_vert = ' ';
			}

			// if(count($cat->cats) > 0)
			// {
				// $catlink = JRoute::_("index.php?option=com_digistore&controller=digistoreCategories&task=view&cid=".$id."&Itemid=".$Itemid);
			// }
			// else
			// {
				$catlink = JRoute::_("index.php?option=com_digistore&controller=digistoreProducts&task=list&cid=".$id."&Itemid=".$Itemid);
			// }

			$prodlink = JRoute::_("index.php?option=com_digistore&controller=digistoreProducts&task=list&cid=".$id."&Itemid=".$Itemid); ?>

			<div class="span<?php echo $span; ?>">
				<div class="thumbnail">
					<p style="text-align:center;font-weight:bold;"><a href="<?php echo $catlink;?>" title="<?php echo $cat->name; ?>"><?php echo $cat->name;?></a></p><?php
					if(trim($cat->image) != "")
					{ ?>
						<p style="text-align:center;">
							<a href="<?php echo $catlink;?>" title="<?php echo $cat->name; ?>"><img alt="<?php echo $cat->name; ?>" <?php echo ($this->configs->catlayoutimagetype ? 'style="width:'.$this->configs->catlayoutimagesize.'px;height:auto;width:auto;float:none;margin-left:auto;margin-right:auto;"' : 'style="height:'.$this->configs->catlayoutimagesize.'px;float:none;margin-left:auto;margin-right:auto;"');?> src="<?php echo ImageHelper::ShowCategoryImage($cat->image); ?>"></a>
						</p><?php
					} ?>
					<div class="caption" style="text-align:center;">
						<?php
						if(trim($this->configs->catlayoutdesclength) != "" && trim($this->configs->catlayoutdesclength != "0")) { ?>
							<p><?php echo digistoreHelper::ShowCatDesc($cat->description, $this->configs); ?></p><?php
						} ?>
						<?php if ($this->configs->showviewproducts) : ?>
						<p style="text-align:center;">
							<a href="<?php echo $catlink;?>" title="<?php echo $cat->name; ?>" class="btn btn-small"><?php echo JText::_("DIGI_VIEW_PRODUCTS");?></a>
						</p>
						<?php endif; ?>
					</div>
				</div>
			</div><?php
			$k++;
		}
		if ($k <= $n) {
			echo '</div>';
		}
	}
}

if ($this->pagination->getPagesLinks() != ''): ?>
	<div class="pagination pagination-centered"><?php echo $this->pagination->getPagesLinks(); ?></div>
	<div style="text-align:center;"><?php echo $this->pagination->getPagesCounter(); ?></div><?php
endif; ?>
</div>

<?php
$view = JRequest::getVar('view');
if($view=='digistoreCategories'){
	echo digistoreHelper::powered_by();
}
?>