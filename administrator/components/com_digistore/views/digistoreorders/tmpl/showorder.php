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
$n = count ($this->order->products);
//Log::debug($n);
$configs = $this->configs;
$order = $this->order;
$refunds = digistoreAdminModeldigistoreOrder::getRefunds($order->id);
$chargebacks = digistoreAdminModeldigistoreOrder::getChargebacks($order->id);
$deleted = digistoreAdminModeldigistoreOrder::getDeleted($order->id);
//Log::debug($order);
if(isset($this->order->id) & $this->order->id < 1):
	echo JText::_('DSEMPTYORDER');
	global $Itemid; ?>

	<form action="<?php echo JRoute::_("index.php?option=com_digistore&controller=digistoreOrders&task=list"."&Itemid=".$Itemid); ?>" name="adminForm" method="post">
		<input type="hidden" name="option" value="com_digistore" />
		<input type="submit" value="<?php echo JText::_("DSVIEWORDERS");?>" />
	</form>

<?php


	else:

	//Log::debug($order);

?>
<form id="adminForm" action="index.php" name="adminForm" method="post">
<div id="contentpane" >
<table class="adminlist" width="100%"  border="1" cellpadding="3" cellspacing="0" bordercolor="#cccccc" style="border-collapse: collapse">
	<caption class="componentheading"><?php echo JText::_("DSMYORDER")." #".$order->id; ?>: <?php echo $order->status; ?></caption>
</table>
<span align="left"><b><?php echo JText::_("DSDATE")." ".date( $configs->time_format, $order->order_date);?></b></span>
<br /><br />
<table class="adminlist table table-striped">
<thead>
	<tr>
		<th class="sectiontableheader"></th>
		<th class="sectiontableheader"  >
			<?php echo JText::_('DSPROD');?>
		</th>

		<th class="sectiontableheader"  >
			<?php echo JText::_('DSLICENSEID');?>
		</th>


		<th class="sectiontableheader"  >
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
$oll_courses_total = 0;
for ($i = 0; $i < $n; $i++):
	$prod = $order->products[$i];
	$id = $order->id;
	if (count ($prod->orig_fields) > 0)
	foreach ($prod->orig_fields as $j => $z) {
		$val = explode(",", $z->optioname);
		if (isset($val[1]) && strlen (trim($val[1])) > 0) {
			$prod->price += floatval(trim($val[1]));
			$prod->amount_paid += floatval(trim($val[1]));
		}



	}
	if (!isset($prod->currency)) $prod->currency = $configs->currency;
	$refund = digistoreAdminModeldigistoreOrder::getRefunds($order->id, $prod->licenseid);
	$chargeback = digistoreAdminModeldigistoreOrder::getChargebacks($order->id, $prod->licenseid);
	$cancelled = digistoreAdminModeldigistoreOrder::isLicenseDeleted($prod->licenseid);?>
	<tr class="row<?php echo $k;?> sectiontableentry<?php echo ($i%2 + 1);?>">
		<td style="<?php echo $cancelled == 3 ? 'text-decoration: line-through;' : '';?>"><?php echo $i+1; ?></td>
		<td style="<?php echo $cancelled == 3 ? 'text-decoration: line-through;' : '';?>">
			<?php echo $prod->name;?>
			<?php
			if(!empty($prod->orig_fields)){
				foreach($prod->orig_fields as $attr){
					echo "<br/>".$attr->fieldname.":".$attr->optioname;
				}
			} ?>
		</td>
		<td style="<?php echo $cancelled == 3 ? 'text-decoration: line-through;' : '';?>"><?php echo $prod->licenseid;?></td>
		<td style="<?php echo $cancelled == 3 ? 'text-decoration: line-through;' : '';?>"><?php
			$price = $prod->price - $refund - $chargeback;
			echo digistoreAdminHelper::format_price($prod->price, $prod->currency, true, $configs);
			if ($refund > 0)
			{
				echo '&nbsp;<span style="color:#ff0000;"><em>('.JText::_("LICENSE_REFUND")." - ".digistoreAdminHelper::format_price($refund, $prod->currency, true, $configs).')</em></span>';
			}
			if ($chargeback > 0)
			{
				echo '&nbsp;<span style="color:#ff0000;"><em>('.JText::_("LICENSE_CHARGEBACK")." - ".digistoreAdminHelper::format_price($chargeback, $prod->currency, true, $configs).')</em></span>';
			} ?>
		</td>
		<td style="<?php echo $cancelled == 3 ? 'text-decoration: line-through;' : '';?>">
			<?php
				echo digistoreAdminHelper::format_price($prod->price - $prod->amount_paid, $prod->currency, true, $configs);
				$oll_courses_total += $prod->price;
			?>
		</td>
		<td style="<?php echo $cancelled == 3 ? 'text-decoration: line-through;' : '';?>"><?php
			$prod->amount_paid = $prod->amount_paid - $refund - $chargeback;
			echo digistoreAdminHelper::format_price($prod->amount_paid, $prod->currency, true, $configs);?>
		</td>
	</tr><?php
	$k = 1 - $k;
endfor; ?>

<tr style="border-style:none;"><td style="border-style:none;" colspan="6"><hr /></td></tr>
<tr><td colspan="4" ></td>
	<td style="font-weight:bold"><?php echo JText::_("DSSUBTOTAL");?></td>
	<td>
		<?php 
			echo digistoreAdminHelper::format_price($oll_courses_total, $prod->currency, true, $configs);
		?>
	</td></tr>
<?php if ($order->shipping > 0):?>
<tr><td colspan="4"></td>
	<td style="font-weight:bold"><?php echo JText::_("DSSHIPPING");?></td>
	<td><?php echo digistoreAdminHelper::format_price($order->shipping, $prod->currency, true, $configs);?></td></tr>
<?php endif; ?>
<tr><td colspan="4"></td>
	<td style="font-weight:bold"><?php echo JText::_("DSTAX");?></td>
	<td><?php echo digistoreAdminHelper::format_price($order->tax, $prod->currency, true, $configs);?></td></tr>
<tr><td colspan="4"></td>
	<td style="font-weight:bold"><?php echo JText::_("VIEWCONFIGSHOWCPROMO");?> "<?php echo $order->promocode; ?>"</td>
	<td><?php echo digistoreAdminHelper::format_price($order->promocodediscount, $prod->currency, true, $configs);?></td></tr>
<?php if ($refunds > 0):?>
<tr>
	<td colspan="4"></td>
	<td style="font-weight:bold;color:#ff0000;"><?php echo JText::_("LICENSE_REFUNDS");?></td>
	<td style="color:#ff0000;"><?php echo digistoreAdminHelper::format_price($refunds, $prod->currency, true, $configs); ?></td>
</tr>
<?php endif;?>
<?php if ($chargebacks > 0):?>
<tr>
	<td colspan="4"></td>
	<td style="font-weight:bold;color:#ff0000;"><?php echo JText::_("LICENSE_CHARGEBACKS");?></td>
	<td style="color:#ff0000;"><?php echo digistoreAdminHelper::format_price($chargebacks, $prod->currency, true, $configs); ?></td>
</tr>
<?php endif;?>
<?php if ($deleted > 0):?>
<tr>
	<td colspan="4"></td>
	<td style="font-weight:bold;color:#ff0000;"><?php echo JText::_("DELETED_LICENSES");?></td>
	<td style="color:#ff0000;"><?php echo digistoreAdminHelper::format_price($deleted, $prod->currency, true, $configs); ?></td>
</tr>
<?php endif;?>
<tr><td colspan="4"></td>
	   	<td style="font-weight:bold"><?php echo JText::_("DSTOTAL");?></td>
	<td>
		<?php
			$value = $order->amount_paid;
			if($value == "-1"){
				$value = $order->amount;
			}
			$value = $value - $refunds - $chargebacks;
			echo digistoreAdminHelper::format_price($value, $prod->currency, true, $configs);
		?>
	</td>
</tr>
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