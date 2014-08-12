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

$configs = $this->configs;

if($configs->shopping_cart_style == "1"){
	JRequest::setVar("tmpl", "component");
}

?>

<?php
	if($configs->show_steps == 0){
?>
		<div class="bar">
			<span class="inactive-step">
			<?php
				echo JText::_("DIGI_STEP_ONE");
			?>
			</span>

			<span class="inactive-step">
			<?php
				echo JText::_("DIGI_STEP_TWO");
			?>
			</span>

			<span class="active-step">
			<?php
				echo JText::_("DIGI_STEP_THREE");
			?>
			</span>
		</div>
<?php
	}
?>

<span id="cart_wrapper_component">
<?php
	global $Itemid;
	$customer = $this->customer;
		$items = $this->items;
	$total = 0;//$this->total;//0;
		$totalfields = $this->totalfields;//0;
		$optlen = $this->optlen;//array();
//		$select_only = $this->select_only;//array();
	  //  $optexist = 0;
	$discount = $this->discount;//0;

	$lists = $this->lists;
	$cat_url = $this->cat_url;
 	$totalfields = 0;
 	$shippingexists = 0;
	foreach ($items as $itemnum => $item) {
		if ($itemnum < 0) continue;
			if ($item->domainrequired == 2) $shippingexists++;
			if (!empty($item->productfields))
			foreach ($item->productfields as $field) {
					$totalfields += count ($field);
				}

	}
	$invisible = 'style="display:none;"';
		if ( count ( $items ) == 0 ) {
		   	$formlink = JRoute::_("index.php?option=com_digistore"."&Itemid=".$Itemid);
			//echo _CART_EMPTY . "<br>";
			?>

			<form name="cart_form" method="post" action="<?php echo $formlink;?>">
			<input type="button"  class="button" name="continue" value="<?php echo JText::_("DSCONTINUESHOPING");?>" onClick="window.location='<?php
				echo digistoreHelper::DisplayContinueUrl($configs,$cat_url);
			 ?>';">
			</form>

			<?php
			return;
		}

	$login_link = JRoute::_("index.php?option=com_digistore&controller=digistoreProfile&task=login&returnpage=cart&Itemid=".$Itemid);
	$cart_url = JRoute::_("index.php?option=com_digistore&controller=digistoreCart&task=view&Itemid=".$Itemid);
	$checkout_url = JRoute::_("index.php?option=com_digistore&controller=digistoreCart&task=checkout&fromsum=1&Itemid=".$Itemid);
//print_r($customer);
		?>

 <?php if (!isset($customer->_user->id) || !$customer->_user->id ) {?><p style="font-size: 18px"><?php echo JText::_("DSRETURNCUSTMERSCLICK")?>
 <a href="<?php echo $login_link; ?>"><?php echo JText::_("DSHERE");?></a><?php echo ' '; ?><?php echo JText::_("DSTOLOGIN");?></p>
	<?php }
?>



<table  class="adminlist" width="100%"  border="0" cellpadding="3" cellspacing="0" bordercolor="#cccccc" style="border-apse: apse">
	<tr>
		<td align="left" valign="top">
<?php
	$store_logo = $configs->store_logo;
	if(trim($store_logo) != "" && $configs->shopping_cart_style == "1"){
?>
		<img src="<?php echo JURI::root()."images/stories/digistore/store_logo/".trim($store_logo); ?>" alt="store_logo" border="0">
<?php
	}
?>
		</td>
	</tr>
</table>

<?php $formlink = JRoute::_("index.php?option=com_digistore&controller=digistoreCart"."&Itemid=".$Itemid); ?>
<table width="100%" cellspacing="0" id="digi_table">
<h1><?php echo JText::_("DSSUMMARY")?></h1>
	<tr ><?php // class was cart_heading ?>

	  <th class="summary_header" width="10%" <?php
	  if ($configs->showreplic != "1" || ($configs->showreplic == "1" && $totalfields < 1))
	  		echo 'style="display:none;"'; ?> >
		<?php echo JText::_("DSREPLICATE");?>
	  </th>
	  <th class="summary_header">
	  </th>
	  <th class="summary_header" <?php if ($configs->showcam == 0) echo $invisible;?> >
		  <?php echo JText::_("DSQUANTITY");?>
	  </th>
	  <th class="summary_header">
		<?php echo JText::_("DSPRICE");?>
	  </th>
	  <th class="summary_header" <?php if ($discount!=1) echo 'style="display:none"'?> >
		<?php echo JText::_("DSDISCOUNT");?>
	  </th>
	  <th class="summary_header" <?php echo ($shippingexists >0 )?"":'style="display:none;"';?>>
		<?php echo JText::_("DSSHIPPINGCOST"); ?>
	  </th>


	  <?php if ($totalfields > 0  ){ ?>
	  <th class="summary_header"><?php echo JText::_("DSATTR"); ?></th>
	  <?php } ?>
	  <th nowrap class="summary_header">
		<?php echo JText::_("DSSUBTOTAL");?>
	  </th>
	</tr>
	<?php $k=1;foreach ( $items as $itemnum => $item ) {
			if ($itemnum < 0) continue;
	?>
	<tr class="item_row">

	<td class="item_column" <?php
	if ($configs->showreplic != "1" || ($configs->showreplic == "1" && $totalfields < 1))
			echo 'style="display:none;"'; ?> > <input type="checkbox" name="replicate[<?php echo $item->cid;?>]" value="<?php echo $item->item_id; ?>" <?php if (count($item->productfields) < 1) echo "disabled"; ?> /></td>
	  <td class="item_column">
		  <?php echo $item->name; ?>
		  <br/>
		  <?php echo $item->plan_text; ?>
	  </td>
	  <td class="item_column" style="text-align:center; <?php if ($configs->showcam == 0) echo ' display:none;';?>" >
	  <?php  if ( !isset( $item->noupdate) ) {
		   echo $lists[$item->cid]['quantity'];
	   } else {
		   echo $item->quantity;
	   }?>
	</td>
	<td class="item_column" style="text-align:center;"> <?php
		echo  digistoreHelper::format_price($item->price, $item->currency, true, $configs);//sprintf($price_format,$item->product_price)." ".$item->currency;
	?></td>
	<td class="item_column" style="text-align:center; <?php if ($discount!=1) echo 'display:none;'?>"><?php
		//echo (isset($item->discount)) ? $item->discount." %" : "N/A" ;
		echo (isset($item->percent_discount)) ? $item->percent_discount  : "N/A" ;
	?></td>
	<td class="item_column"  <?php echo ($shippingexists >0 )?"style='text-align:center;'":'style="text-align:center; display:none;"';?>><?php
if ($configs->shipping_price == 1) $item->shipping += $item->itemtax;
echo (isset($item->shipping)&&$item->domainrequired==2? digistoreHelper::format_price($item->shipping, $item->currency, true, $configs):"N/A");
	?></td>


	  <?php if ($totalfields > 0  ){ ?>
	  <td  class="item_column" style="text-align:center;" nowrap="nowrap"><?php
	  $i = 0;

	echo $lists[$item->cid]['attribs'];
//   add_selector_to_cart($item, $optlen, $select_only, $i);

	?></td>
	  <?php } ?>
	  <td class="item_column" nowrap style="text-align:center;"><?php
	  /*
									if ($configs->tax_price == 1)
										echo  digistoreHelper::format_price($item->subtotal, $item->currency, true, $configs);
									else
										echo  digistoreHelper::format_price($item->price, $item->currency, true, $configs);
	   */
			echo  digistoreHelper::format_price($item->subtotal, $item->currency, true, $configs);
//print_r($item);
	  ?>
	</td>
	</tr>
	<?php
		$total += $item->subtotal;//+$item->product_price;
		$k = 1 - $k;}?>
	<tr>

	  <td colspan="<?php
	  $span = 6;
	  if ($configs->showreplic != 1 || ($configs->showreplic == "1" && $totalfields < 1)) $span--;
	  if ($totalfields > 0 && $shippingexists > 0) ;//echo '7';
	  else if ($totalfields > 0 || $shippingexists > 0) $span--;//echo'6';
	  else $span-=2;
	  if ($discount != 1) $span--;
	if ($configs->showcam == 0) $span--;
	if ($configs->showcremove == 0) $span--;

	  echo $span;

	  ?>" valign="top" class="item_column_right" nowrap  >
<span <?php if ($configs->showcpromo == 0) echo $invisible;?> >
	  <?php
	if (strlen(trim($this->promocode)))
	  	echo JText::_("DSPROMO"). ":  ";
	  ?><?php echo $this->promocode; ?>
	  <?php
//	  	echo JText::_("DSOPTIONAL");
//	  	echo "<br /><span style='color:red'>".$this->promoerror."</span> <br />";
?>
</span>
<span style="display:none" >
<?php
	  	if ($shippingexists) {
		echo " <input type='radio' name='shipto' value='2' checked  />";
	 	}
	  ?>
</span>
	  </td>
	  <td class="item_column_right"><?php

		$tax = $this->tax;
		if ($configs->tax_summary == 1){
				   if ($tax['promo'] > 0 && $tax['promoaftertax'] == '0'):
				?>
				<?php echo JText::_("DSPROMODISCOUNT"); ?><br />
				<?php endif; ?>

			  <?php
			  //print_r($tax);
			if ($tax['value'] > 0 && $customer->_user->id > 0) {
				  echo $tax['type'];
			}
				if ($tax['shipping'] > 0 && $customer->_user->id > 0):
				?>
				<?php echo JText::_("DSSHIPING"); ?><br />
				<?php endif; ?>
				<?php
				 if ($tax['promo'] > 0 && $tax['promoaftertax'] == '1'):
				?>
				<?php echo JText::_("DSPROMOCODEDISCOUNT"); ?><br />
				<?php endif;
		}
		 ?>
		<?php echo JText::_("DSTOTAL");?><br />
	  </td>
	  <td class="item_column_right" nowrap style="text-align:center; padding-top:10px;">

		<?php
				if ($configs->tax_summary == 1){
										if ($tax['promo'] > 0 && $tax['promoaftertax'] == '0')
											echo digistoreHelper::format_price($tax['promo'], $tax['currency'], true, $configs)."<br />";
											//echo $tax['promo']." ".$tax['currency'];
										?>
														  <?php
										if ($tax['value'] > 0 && $customer->_user->id > 0)
											echo digistoreHelper::format_price($tax['value'], $tax['currency'], true, $configs)."<br />";
												//echo $tax['value']." ".$tax['currency'];
										?>

										<?php
										if ($tax['shipping'] > 0 && $customer->_user->id > 0)
											echo digistoreHelper::format_price($tax['shipping'], $tax['currency'], true, $configs)."<br />";
											//echo $tax['shipping']." ".$tax['currency'];
										?>

										<?php
										if ($tax['promo'] > 0 && $tax['promoaftertax'] == '1')
										echo digistoreHelper::format_price($tax['promo'], $tax['currency'], true, $configs)."<br />";
											//echo $tax['promo']." ".$tax['currency'];
				}
		?>
	<?php
		  echo digistoreHelper::format_price($tax['taxed'], $tax['currency'], true, $configs)."<br />";
	?></td>
	</tr>
	<tr >
	  <td height="30" colspan="10" width="100%">
	  <table width="100%"  border="0" cellspacing="0" cellpadding="2">
		<tr>
		  <td> <input type="button" class="digistore_cancel btn" name="continue" value="<?php echo JText::_("DSEDITORDER")?>" onClick="window.location='<?php echo $cart_url;?>';" <?php if ($configs->showccont == 0) echo $invisible;?>  ></td>
		  <td class="item_column_right">
			<input type="button" class="btn" name="Submit" value="<?php echo JText::_("DSPLACEORDER");?>" onClick="document.location='<?php echo $checkout_url;?>';">
		  </td>
		</tr>
	  </table>

	 </td>
	</tr>
  </table>

</span>

<?php //echo digistoreHelper::powered_by(); ?>
