<?php
/**
* @package			DigiStore Joomla Extension
 * @author			foobla.com
 * @version			$Revision: 376 $
 * @lastmodified	$LastChangedDate: 2013-10-21 11:54:05 +0200 (Mon, 21 Oct 2013) $
 * @copyright		Copyright (C) 2013 foobla.com. All rights reserved.
* @license			GNU/GPLv3
*/

defined ('_JEXEC') or die ("Go away.");

$configs = $this->configs;
JRequest::setVar("tmpl", "component");
global $Itemid;
$customer = $this->customer;
$items = $this->items;

$total = 0;//$this->total;//0;
$totalfields = $this->totalfields;//0;
$optlen = $this->optlen;//array();

$discount = $this->discount;//0;

$lists = $this->lists;
$cat_url = $this->cat_url;
$totalfields = 0;
$shippingexists = 0;

foreach($items as $itemnum => $item){
	if($itemnum < 0){
		continue;
	}

	if($item->domainrequired == 2){
		$shippingexists++;
	}

	if(!empty($item->productfields)){
		foreach ($item->productfields as $field){
			$totalfields += count($field);
		}
	}
}

$invisible = 'style="display:none;"';
if(count($items) == 0){
	$formlink = JRoute::_("index.php?option=com_digistore"."&Itemid=".$Itemid);
	$redirect_url = digistoreHelper::DisplayContinueUrl($configs, $cat_url);
	echo JText::_("DIGI_CART_IS_EMPTY").'. <a href="'.$redirect_url.'">'.JText::_("DIGI_CLICK_HERE").'.</a>';
	return;
}

$login_link = JRoute::_("index.php?option=com_digistore&controller=digistoreProfile&task=login&returnpage=cart"."&Itemid=".$Itemid);

?>

<style type="text/css">
	#cart_body .rt-container {
		width: auto !important;
	}
</style>

<script language="javascript" type="text/javascript">

		function cartformsubmit () {

			if (!checkSelectedPlain()) return false;

			var mandatory = new Object();
			var i,j;
<?php
foreach ($items as $j => $v) {
	if ($j < 0 ) continue;
	echo "mandatory[".$v->cid."] = new Object();";
	if (!empty($v->productfields))
		foreach ($v->productfields as $ii => $field) {
			echo "mandatory[".$v->cid."][".$ii."] = new Object();";
			echo "mandatory[".$v->cid."][".$ii."]['fld'] = '".$field->id."';\n";
			echo ($field->mandatory == 1)?"mandatory[".$v->cid."][".$ii."]['req']=1;\n":"mandatory[".$v->cid."][".$ii."]['req']=0;\n";
		}
}
?>
		for (i in mandatory) {
			for (j in mandatory[i]){
				if (mandatory[i][j]['req'] == 1) {
					var el = document.getElementById("attributes[" + i + "][" +mandatory[i][j]['fld'] +"]");
					if (el.selectedIndex < 1) {
						alert ("<?php echo JText::_("DSSELECTALLREQ"); ?>");
						return false;
					}
				}
			}
		}

		return true;
	}

	function checkSelectedPlain() {

<?php
		foreach ($items as $key => $item) :
			if ($key < 0 ) continue;
?>
		plan_id<?php echo $item->cid;?> = document.getElementById('plan_id<?php echo $item->cid;?>');
		if (plan_id<?php echo $item->cid;?>.value == -1) {
			alert('Please select plan for <?php echo $item->name; ?>');
			plan_id<?php echo $item->cid;?>.focus();
			return false;
		}
<?php
		endforeach;
?>
		return true;
	}

	function ajaxRequest(Url,DivId)
	{
	 var AJAX;
	 try
	 {
	  AJAX = new XMLHttpRequest();
	 }
	 catch(e)
	 {
	  try
	  {
	   AJAX = new ActiveXObject("Msxml2.XMLHTTP");
	  }
	  catch(e)
	  {
	   try
	   {
		AJAX = new ActiveXObject("Microsoft.XMLHTTP");
	   }
	   catch(e)
	   {

		alert("Your browser does not support AJAX.");
		return false;
	   }
	  }
	 }
	 AJAX.onreadystatechange = function()
	 {
	  if(AJAX.readyState == 4)
	  {
	   if(AJAX.status == 200)
	   {
		// debug info
		//document.getElementById(DivId).innerHTML = AJAX.responseText;

		var myObject = eval('(' + AJAX.responseText + ')');

		var cid = myObject.cid;
		var cart_item_price = eval('myObject.cart_item_price'+cid);
		var cart_item_total = eval('myObject.cart_item_total'+cid);

		document.getElementById('cart_item_price'+cid).innerHTML = cart_item_price;
		document.getElementById('cart_item_total'+cid).innerHTML = cart_item_total;
		document.getElementById('cart_total').innerHTML = myObject.cart_total;

	   }
	   else
	   {
		alert("Error: "+ AJAX.statusText +" "+ AJAX.status);
	   }
	  }
	 }
	 AJAX.open("get", Url, true);
	 AJAX.send(null);
	}

	function update_cart(item_id) {
		var url = "index.php?option=com_digistore&controller=digistoreCart&task=getCartItem&cid="+item_id;

		var plan_id = document.getElementById('plan_id'+item_id);
		var plan_query = '';
		if ( plan_id.selectedIndex != -1)
		{
			var plan_value = plan_id.options[plan_id.selectedIndex].value;
			plan_query += '&plan_id='+plan_value;
		} else {
			return false;
		}

		url += plan_query;

		var qty = document.getElementById('quantity'+item_id);
		var qty_query = '';
		if ( qty.selectedIndex != -1)
		{
			var qty_value = qty.options[qty.selectedIndex].value;
			qty_query += '&quantity'+item_id+'='+qty_value;
		}

		url += qty_query;

		var attrs_query = '';
		for (var i = 1; i < 11; i++) {
			if ( document.getElementById('attributes'+item_id+''+i) ) {

				var attr = document.getElementById('attributes'+item_id+''+i);

				if ( attr.selectedIndex != -1)
				{
					var value = attr.options[attr.selectedIndex].value;
					attrs_query += '&attributes['+item_id+']['+i+']='+value;
				}

			} else break;
		}

		url += attrs_query;

		ajaxRequest(url, 'debugid');
	}

</script>

<?php 
	$formlink = JRoute::_("index.php?option=com_digistore&controller=digistoreCart");
	$currency = "";
?>

<form name="cart_form" method="post" action="<?php echo $formlink?>" onSubmit="return cartformsubmit();">
	<table class="table table-hover table-striped">
	<tbody><?php
	$k = 0;
	foreach($items as $itemnum => $item){
		if($itemnum < 0){
			continue;
		}
	?>
		<tr>
			<!-- Product image -->
			<td width="70">
				<?php
					if(isset($item->defprodimage) && $item->defprodimage != ""){
						$title = $item->image_title;
						$title = str_replace('"', "&quot;", $title);
						if(trim($title) != ""){
							$title = 'title="'.$title.'"';
						}
				?>
						<img <?php echo $title; ?> src="<?php echo ImageHelper::GetProductImageURL($item->defprodimage, "popup"); ?>" alt="<?php echo $item->name; ?>"/>
				<?php
					}
				?>
			</td>
			<!-- /End Product image -->

			<!-- Product name -->
			<td style="text-align:left;" class="digistore_product_name">
				<?php 
					$renew = "";
					if($item->renew == "1"){
						$renew = "&nbsp;&nbsp;&nbsp;(".JText::_("DIGI_RENEWAL").")";
					}
					echo $item->name.$renew; 
				?>
			</td>
			<!-- /End Product name -->

			<!-- Price -->
			<td align="right" style="vertical-align:top;text-align:right;">
				<?php 
					echo digistoreHelper::format_price2($item->price, $item->currency, true, $configs);
					$currency = $item->currency;
				?>
			</td>
			<!-- /End Price -->

			<!-- Remove -->
			<td align="center" style="vertical-align:top;width:80px;text-align:right;">
				<a href="javascript:void();" onclick="javascript:deleteFromCart(<?php echo $item->cid; ?>);"><i class="ico-trash"></i></a>
			</td>
			<!-- /End Remove -->
		</tr>
	<?php
		$total += $item->subtotal;
		$k++;
	}
	?>
		</tbody>
		<tfoot>
		<tr style="background:#ccc;">
			<td></td>
			<td style="color:#fff;font-weight:bold;">
				<b><?php
					$text = "DIGI_ITEM_IN_CART";
					if($k > 1){
						$text = "DIGI_ITEMS_IN_CART";
					}
					echo $k." ".JText::_($text); 
				?></b>
			</td>
			<td style="color:#fff;text-align:right;"><b><?php echo JText::_("DSSUBTOTAL");?></b></td>
			<td style="color:#fff;text-align:right;">
				<b><?php echo digistoreHelper::format_price2($total, $currency, true, $configs); ?></b>
			</td>
		</tr>
		</tfoot>
	</table>

	<input name="controller" type="hidden" id="controller" value="digistoreCart">
	<input name="task" type="hidden" id="task" value="updateCart">
	<input name="returnpage" type="hidden" id="returnpage" value="">
	<input name="Itemid" type="hidden" value="<?php global $Itemid; echo $Itemid; ?>">
	<input name="promocode" type="hidden" value="" />
	<input type="hidden" name="processor" id="processor" value="paypaypal">
</form>
<?php exit; ?>