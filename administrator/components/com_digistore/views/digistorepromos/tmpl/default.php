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
$n = count ($this->promos);
$configs = $this->configs;

$document = JFactory::getDocument();
$document->addStyleSheet("components/com_digistore/assets/css/digistore.css");

if ($n < 1): 
//		echo JText::_('VIEWPROMONOPROMO');
?>

	<form id="adminForm" action="index.php" name="adminForm" method="post" class="form-horizontal">
		<table width="100%">
			<tr>
				<td width="100%" align="right" style="padding-bottom: 5px;">
					<div style="padding-bottom:0.5em">
						<?php
							$promosearch = JRequest::getVar("promosearch", "");
						?>
						<?php echo JText::_("DIGI_FIND");?>:
						<input type="text" name="promosearch" value="<?php echo trim($promosearch);?>" />
						<input type="submit" name="go" value="<?php echo JText::_("DSSEARCH");?>" class="btn" />
					</div>
					<div style="padding-bottom:0.5em">
						<?php echo JText::_("VIEWPROMOPUBLISHED");?>:
						<select name="status" onchange="document.adminForm.task.value=''; document.adminForm.submit();">
							<option value="" <?php if($this->status == ""){ echo 'selected="selected"';} ?> ><?php echo JText::_("DIGI_SELECT"); ?></option>
							<option value="0" <?php if($this->status == "0"){ echo 'selected="selected"';} ?> ><?php echo JText::_("HELPERUNPUBLISHED"); ?></option>
							<option value="1" <?php if($this->status == "1"){ echo 'selected="selected"';} ?> ><?php echo JText::_("PLAINPUBLISHED"); ?></option>
						</select>
					</div>
					<div style="padding-bottom:0.5em">
						<?php echo JText::_("VIEWORDERSSTATUS");?>:
						<select name="condition" onchange="document.adminForm.task.value=''; document.adminForm.submit();">
							<option value="-1" <?php if($this->condition == "-1"){ echo 'selected="selected"';} ?> ><?php echo JText::_("DIGI_SELECT"); ?></option>
							<option value="1" <?php if($this->condition == "1"){ echo 'selected="selected"';} ?> ><?php echo JText::_("HELPERACTIVE"); ?></option>
							<option value="0" <?php if($this->condition == "0"){ echo 'selected="selected"';} ?> ><?php echo JText::_("HELPEREXPIRE"); ?></option>
						</select>
					</div>
				</td>
			</tr>
		</table>

		<input type="hidden" name="option" value="com_digistore" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="controller" value="digistorePromos" />
	</form>

<table>
	<tr>
		<td class="header_zone" colspan="4">
			<?php
				echo JText::_("HEADER_PROMOS");
			?>
		</td>
	</tr>
	<tr>
		<td align="right">
			<a class="modal digi_video" rel="{handler: 'iframe', size: {x: 750, y: 435}}" href="index.php?option=com_digistore&controller=digistoreAbout&task=vimeo&id=38437542">
				<img src="<?php echo JURI::base(); ?>components/com_digistore/assets/images/icon_video.gif" class="video_img" />
				<?php echo JText::_("COM_DIGISTORE_VIDEO_PROMO_MANAGER"); ?>				  
			</a>
		</td>
	</tr>
</table>

<table class="adminlist table">
<thead>

	<tr>
		<th width="5">
			<input type="checkbox" onclick="checkAll(<?php echo $n; ?>)" name="toggle" value="" />
		</th>
			<th width="20">
			<?php echo JText::_('VIEWPROMOID');?>
		</th>
		<th>
			<?php echo JText::_('VIEWPROMOTITLE');?>
		</th>

		<th>
			<?php echo JText::_('VIEWPROMOCODE');?>
		</th>
		<th><?php echo JText::_("VIEWPROMOPUBLISHED");?>
		</th>
		<th><?php echo JText::_("VIEWORDERSSTATUS");?></th>
		<th>
			<?php echo JText::_('VIEWPROMOTIMEUSED');?>
		</th>

		<th>
			<?php echo JText::_('VIEWPROMOUSAGESLIST');?>
		</th>

	</tr>
</thead>

<tbody>
</tbody>
</table>

<?php

	else:

?>
<form id="adminForm" action="index.php" name="adminForm" method="post" class="form-horizontal">
	<table width="100%">
		<tr>
			<td width="100%" align="right" style="padding-bottom: 5px;">
				<div style="padding-bottom:0.5em">
					<?php
					$promosearch = JRequest::getVar("promosearch", "");
					?>
					<input type="text" name="promosearch" value="<?php echo trim($promosearch);?>" placeholder="<?php echo JText::_("DIGI_FIND");?>" />
					<input type="submit" name="go" value="<?php echo JText::_("DSSEARCH");?>" class="btn" />
				</div>
				<div style="padding-bottom:0.5em">
					<?php echo JText::_("VIEWPROMOPUBLISHED");?>:
					<select name="status" onchange="document.adminForm.task.value=''; document.adminForm.submit();">
						<option value="" <?php if($this->status == ""){ echo 'selected="selected"';} ?> ><?php echo JText::_("DIGI_SELECT"); ?></option>
						<option value="0" <?php if($this->status == "0"){ echo 'selected="selected"';} ?> ><?php echo JText::_("HELPERUNPUBLISHED"); ?></option>
						<option value="1" <?php if($this->status == "1"){ echo 'selected="selected"';} ?> ><?php echo JText::_("PLAINPUBLISHED"); ?></option>
					</select>
					<?php echo JText::_("VIEWORDERSSTATUS");?>:
					<select name="condition" onchange="document.adminForm.task.value=''; document.adminForm.submit();">
						<option value="-1" <?php if($this->condition == "-1"){ echo 'selected="selected"';} ?> ><?php echo JText::_("DIGI_SELECT"); ?></option>
						<option value="0" <?php if($this->condition == "0"){ echo 'selected="selected"';} ?> ><?php echo JText::_("HELPEREXPIRE"); ?></option>
						<option value="1" <?php if($this->condition == "1"){ echo 'selected="selected"';} ?> ><?php echo JText::_("HELPERACTIVE"); ?></option>
					</select>
				</div>
			</td>
		</tr>
	</table>

	<table>
		<tr>
			<td class="header_zone" colspan="4">
				<?php
					echo JText::_("HEADER_PROMOS");
				?>
			</td>
		</tr>
		<tr>
			<td align="right">
				<a class="modal digi_video" rel="{handler: 'iframe', size: {x: 750, y: 435}}" href="index.php?option=com_digistore&controller=digistoreAbout&task=vimeo&id=38437542">
					<img src="<?php echo JURI::base(); ?>components/com_digistore/assets/images/icon_video.gif" class="video_img" />
					<?php echo JText::_("COM_DIGISTORE_VIDEO_PROMO_MANAGER"); ?>				  
				</a>
			</td>
		</tr>
	</table>

<div id="editcell" >
<table class="adminlist table">
<thead>

	<tr>
		<th width="5">
			<input type="checkbox" onclick="checkAll(<?php echo $n; ?>)" name="toggle" value="" />
		</th>
			<th width="20">
			<?php echo JText::_('VIEWPROMOID');?>
		</th>
		<th>
			<?php echo JText::_('VIEWPROMOTITLE');?>
		</th>

		<th>
			<?php echo JText::_('VIEWPROMOCODE');?>
		</th>
		<th>
			<?php echo JText::_('VIEWPROMODISCAMOUNT');?>
		</th>
		<th><?php echo JText::_("VIEWPROMOPUBLISHED");?>
		</th>
		<th><?php echo JText::_("VIEWORDERSSTATUS");?></th>
		<th>
			<?php echo JText::_('VIEWPROMOTIMEUSED');?>
		</th>

		<th>
			<?php echo JText::_('VIEWPROMOUSAGESLIST');?>
		</th>

	</tr>
</thead>

<tbody>

<?php 
	JHTML::_("behavior.tooltip");
	for ($i = 0; $i < $n; $i++):
		$promo = $this->promos[$i];
		$id = $promo->id;

		$checked = JHTML::_('grid.id', $i, $id);
		$link = JRoute::_("index.php?option=com_digistore&controller=digistorePromos&task=edit&cid[]=".$id);

		$published = JHTML::_('grid.published', $promo->published, $i);
		digistoreAdminHelper::publishAndExpiryHelper($img, $alt, $times, $status, $promo->codestart, $promo->codeend, $promo->published, $configs, $promo->codelimit, $promo->used);

?>
	<tr class="row<?php echo $k;?>"> 
		<td>
			<?php echo $checked;?>
		</td>

		<td>
			<?php echo $id;?>
		</td>
		<td>
			<a href="<?php echo $link;?>" ><?php echo $promo->title;?></a>
		</td>

		<td>
			<a href="<?php echo $link;?>" ><?php echo $promo->code;?></a>
		</td>
		<td align="center">
			<?php echo ($promo->promotype == '0' ? digistoreAdminHelper::format_price($promo->amount, $configs->currency, true, $configs) : $promo->amount . ' %');?>
		</td>
		<td align="center">
			<?php echo $published; ?>
		</td>
		<td align="center">
			<?php
			echo $status;
			?>
		</td>
		<td align="center">
			<?php echo ($promo->used);?>
		</td>

		<td align="center">
					<?php echo $promo->codelimit>0?($promo->codelimit - $promo->used):JText::_("DS_UNLIMITED");?>
		</td>

	</tr>


<?php 
		$k = 1 - $k;
	endfor;
?>

</tbody>

	<tfoot>
		<tr>
			<td colspan="9">
				<?php
					$total_pag = $this->pagination->get("pages.total", "0");
					$pag_start = $this->pagination->get("pages.start", "1");
					if($total_pag > ($pag_start + 9)){
						$this->pagination->set("pages.stop", ($pag_start + 9));
					}
					else{
						$this->pagination->set("pages.stop", $total_pag);
					}
					echo $this->pagination->getListFooter();
				?>
			</td>
		</tr>
	</tfoot>

</table>

</div>

<input type="hidden" name="option" value="com_digistore" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="digistorePromos" />
</form>

<?php
	endif;
?>