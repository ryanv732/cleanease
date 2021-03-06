<?php
/**
* @package			DigiStore Joomla Extension
 * @author			foobla.com
 * @version			$Revision: 410 $
 * @lastmodified	$LastChangedDate: 2013-11-14 11:50:41 +0100 (Thu, 14 Nov 2013) $
 * @copyright		Copyright (C) 2013 foobla.com. All rights reserved.
* @license			GNU/GPLv3
*/

defined ('_JEXEC') or die ("Go away.");

$invisible = 'style="display:none;"';

$k = 0;
$n = count ($this->order->products);
$configs = $this->configs;
$order = $this->order;
if ($this->order->id < 1):
	echo JText::_('DSEMPTYORDER');
	global $Itemid;
?>

	<form action="<?php echo JRoute::_("index.php?option=com_digistore&controller=digistoreOrders&task=list"."&Itemid=".$Itemid); ?>" name="adminForm" method="post">
	  	<input type="hidden" name="option" value="com_digistore" />
		<input type="submit" value="<?php echo JText::_("DSVIEWORDERS");?>" />
	</form>

<?php


	else:
?>
<form action="index.php" name="adminForm" method="post">
<div id="contentpane" >
<table class="adminlist" width="100%"  border="1" cellpadding="3" cellspacing="0" bordercolor="#cccccc" style="border-collapse: collapse">
<caption class="componentheading"><?php echo JText::_("DSMYORDER")." #".$order->id; ?></caption>
</table>
<span align="left"><b><?php echo JText::_("DSDATE")." ".date( $configs->time_format, $order->order_date);?></b></span>
<br /><br />
<table class="adminlist" width="100%"  border="0" cellpadding="3" cellspacing="0" bordercolor="#cccccc" style="border-collapse: collapse">
<thead>

	<tr>
		<th class="sectiontableheader"></th>
		<th class="sectiontableheader"  >
			<?php echo JText::_('DSPROD');?>
		</th>

		<th class="sectiontableheader"  <?php if ($configs->showolics == 0) echo $invisible;?> >
			<?php echo JText::_('DSLICENSEID');?>
		</th>

		<th class="sectiontableheader"  >
			<?php echo JText::_('DSQUANTITY');?>
		</th>


		<th class="sectiontableheader">
			<?php echo JText::_('DSPRICE');?>
		</th>


		<th class="sectiontableheader"  >
			<?php echo JText::_('DSDISCOUNT');?>
		</th>

		<th class="sectiontableheader" >
			<?php echo JText::_('DSTOTAL');?>
		</th>

	</tr>
</thead>

<tbody>

<?php
	for ($i = 0; $i < $n; $i++):
		$prod = $order->products[$i];
		$id = $order->id;
//		$checked = JHTML::_('grid.id', $i, $id);
		if (count ($prod->orig_fields) > 0)
		foreach ($prod->orig_fields as $j => $z) {
			$val = explode(",", $z->optioname);
			if (isset($val[1]) && strlen (trim($val[1])) > 0) {
				$prod->price += floatval(trim($val[1]));
				$prod->amount_paid += floatval(trim($val[1]));
			}
		}
		
		if (!isset($prod->currency)) {$prod->currency = $configs->currency;}

?>
	<tr class="row<?php echo $k;?> sectiontableentry<?php echo ($i%2 + 1);?>">
		<td><?php echo $i+1; ?></td>
		 	<td >
		 			<?php echo $prod->name;?>
					<?php if (!empty($prod->orig_fields)) {
						foreach($prod->orig_fields as $attr){
							echo "<br/>".$attr->fieldname.":".$attr->optioname;
						}
					} ?>
		</td>

		 	<td <?php if ($configs->showolics == 0) echo $invisible;?>>
		 			<?php echo $prod->licenseid;?>
		</td>

		 	<td>
		 			<?php echo 1;//$prod->count;?>
		</td>

		<td>
			<?php echo digistoreHelper::format_price($prod->price, $prod->currency, true, $configs);?>
		</td>

		<td>
			<?php echo digistoreHelper::format_price($prod->price - $prod->amount_paid, $prod->currency, true, $configs);?>
		</td>

		<td>
			<?php echo digistoreHelper::format_price($prod->amount_paid, $prod->currency, true, $configs);?>
		</td>



<?php
		$k = 1 - $k;
	endfor;

	$colspan=5;
	if ($configs->showolics == 0) $colspan--;

?>
<tr style="border-style:none;"><td style="border-style:none;" colspan="7"><hr /></td></tr>
<tr><td colspan="<?php echo $colspan; ?>" ></td>
	<td style="font-weight:bold"><?php echo JText::_("DSSUBTOTAL");?></td>
	<td><?php echo digistoreHelper::format_price($order->amount - $order->tax - $order->shipping, $prod->currency, true, $configs);?></td></tr>
<?php if ($order->shipping > 0):?>
<tr><td colspan="<?php echo $colspan; ?>"></td>
	<td style="font-weight:bold"><?php echo JText::_("DSSHIPPING");?></td>
	<td><?php echo digistoreHelper::format_price($order->shipping, $prod->currency, true, $configs);?></td></tr>
<?php endif; ?>
<tr><td colspan="<?php echo $colspan; ?>"></td>
	<td style="font-weight:bold"><?php echo JText::_("DSTAX");?></td>
	<td><?php echo digistoreHelper::format_price($order->tax, $prod->currency, true, $configs);?></td></tr>
<tr><td colspan="<?php echo $colspan; ?>"></td>
	   	<td style="font-weight:bold"><?php echo JText::_("DSTOTAL");?></td>
	<td><?php echo digistoreHelper::format_price($order->amount, $prod->currency, true, $configs);?></td></tr>
</tbody>


</table>

</div>

<input type="hidden" name="option" value="com_digistore" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="digistoreOrders" />
</form>
<?php

endif;

echo digistoreHelper::powered_by(); 
