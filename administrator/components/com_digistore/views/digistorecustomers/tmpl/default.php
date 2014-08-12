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

	$k = 0;
	$n = count ($this->custs);

	if ($n < 1): 
//		echo JText::_('VIEWCUSTOMERNOCUST');
?>

<table class="adminlist table">
<thead></thead>
<tbody><tr><td colspan="4" nowrap align="right">
<?php //echo $this->psel;?><br />
<form name="search" method="post" action="index.php">
<?php echo JText::_("DSKEYWORD");?>:
<input type="text" name="keyword" value="<?php echo (strlen(trim($this->keyword)) > 0 ?$this->keyword:"");?>" />
<input type="submit" name="go" value="<?php echo JText::_("DSSEARCH");?>" />
<input type="hidden" name="option" value="com_digistore" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="digistoreCustomers" />
<input type="hidden" name="prd" value="<?php echo $this->prd;?>" />
</form><br />
</td></tr>
	<tr>
		<td class="header_zone" colspan="4">
			<?php
				echo JText::_("HEADER_CUSTOMERS");
			?>
		</td>
	</tr>
	<tr>
		<td colspan="5" align="right">
			<a class="modal digi_video" rel="{handler: 'iframe', size: {x: 750, y: 435}}" href="index.php?option=com_digistore&controller=digistoreAbout&task=vimeo&id=38437487">
				<img src="<?php echo JURI::base(); ?>components/com_digistore/assets/images/icon_video.gif" class="video_img" />
				<?php echo JText::_("COM_DIGISTORE_VIDEO_CUST_WIZARD"); ?>				  
			</a>
		</td>
	</tr>
</tbody>
<thead>

	<tr>
		<th width="5">
			<input type="checkbox" onclick="checkAll(<?php echo $n; ?>)" name="toggle" value="" />
		</th>
			<th width="20">
			<?php echo JText::_('VIEWCUSTOMERID');?>
		</th>
		<th>
			<?php echo JText::_('VIEWCUSTOMERNAME');?>
		</th>
		<th>
			<?php echo JText::_('VIEWCUSTOMERUSER');?>
		</th>


	</tr>
</thead>

<tbody>

	<tr>
	<td colspan="4">
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

</td>
</tr>
</tbody>
</table>

	<form id="adminForm" action="index.php" name="adminForm" method="post">
	  	<input type="hidden" name="option" value="com_digistore" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="controller" value="digistoreCustomers" />
	</form>

<?php

	else:

?>
<div id="editcell" >
<table class="adminlist table">
<thead></thead>
<tbody><tr><td colspan="4" nowrap align="right">
<?php //echo $this->psel;?><br />
<form name="search" method="post" action="index.php">
<?php echo JText::_("DSKEYWORD");?>:
<input type="text" name="keyword" value="<?php echo (strlen(trim($this->keyword)) > 0 ?$this->keyword:"");?>" />
<input type="submit" name="go" value="<?php echo JText::_("DSSEARCH");?>" />
<input type="hidden" name="option" value="com_digistore" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="digistoreCustomers" />
<input type="hidden" name="prd" value="<?php echo $this->prd;?>" />
</form><br />
</td></tr>
<tr>
	<td class="header_zone" colspan="4">
		<?php
			echo JText::_("HEADER_CUSTOMERS");
		?>
	</td>
</tr>
<tr>
	<td colspan="5" align="right">
		<a class="modal digi_video" rel="{handler: 'iframe', size: {x: 750, y: 435}}" href="index.php?option=com_digistore&controller=digistoreAbout&task=vimeo&id=38437487">
			<img src="<?php echo JURI::base(); ?>components/com_digistore/assets/images/icon_video.gif" class="video_img" />
			<?php echo JText::_("COM_DIGISTORE_VIDEO_CUST_WIZARD"); ?>				  
		</a>
	</td>
</tr>
</tbody>

<form id="adminForm" action="index.php" name="adminForm" method="post">

<thead>

	<tr>
		<th width="5">
			<input type="checkbox" onclick="checkAll(<?php echo $n; ?>)" name="toggle" value="" />
		</th>
			<th width="20">
			<?php echo JText::_('VIEWCUSTOMERID');?>
		</th>
		<th>
			<?php echo JText::_('VIEWCUSTOMERNAME');?>
		</th>
		<th>
			<?php echo JText::_('VIEWCUSTOMERUSER');?>
		</th>


	</tr>
</thead>

<tbody>

<?php 
	for ($i = 0; $i < $n; $i++):
	$cust = $this->custs[$i];
	$id = $cust->id;
	$checked = JHTML::_('grid.id', $i, $id);
	$link = JRoute::_("index.php?option=com_digistore&controller=digistoreCustomers&task=edit&cid[]=".$id.(strlen(trim($this->keyword))>0?"&keyword=".$this->keyword:""));
	$ulink = JRoute::_("index.php?option=com_users&view=user&layout=edit&id=".$id);
?>
	<tr class="row<?php echo $k;?>"> 
		 	<td>
		 			<?php echo $checked;?>
		</td>

		 	<td>
		 			<?php echo $id;?>
		</td>
		 	<td>
		 			<a href="<?php echo $link;?>" ><?php echo $cust->firstname." ".$cust->lastname;?></a>
		</td>
		 	<td>
		 			<?php echo $cust->username;?>
		</td>

	</tr>


<?php 
		$k = 1 - $k;
	endfor;
?>

	<tr>
		<td colspan="4">
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

</tbody>


</table>

</div>

<input type="hidden" name="option" value="com_digistore" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="digistoreCustomers" />
</form>

<?php
	endif;

?>