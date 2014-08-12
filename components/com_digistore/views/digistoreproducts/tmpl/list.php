<?php
/**
* @package			DigiStore Joomla Extension
 * @author			foobla.com
 * @version			$Revision: 375 $
 * @lastmodified	$LastChangedDate: 2013-10-21 11:33:34 +0200 (Mon, 21 Oct 2013) $
 * @copyright		Copyright (C) 2013 foobla.com. All rights reserved.
* @license			GNU/GPLv3
*/

defined ('_JEXEC') or die ("Go away.");

JHTML::_('behavior.modal');
$k = 0;
$n = count($this->prods);
$tp = $this->totalprods;

$lists = $this->lists;

$direction = $this->configs->list_orientation == "0" ? "ltr" : "rtl";

?>

<div class="digistore">
<div id="dslayout-listmulti" style="direction:<?php echo $direction; ?>;">
<?php
	if ($n < 1) : // empty
?>
	<div class="ijd-box ijd-rounded">
		<div class="ijd-index-product ijd-width-100 ijd-left">
		<?php
			echo "<div style='padding:20px;text-align:center;'>".JText::_('DSNOPROD')."<br/><br/><a href='".JRoute::_("index.php?option=com_digistore")."'>".JText::_('DSCONTINUESHOPING')."</a></div>";
		?>
		</div>
	</div>
<?php

	else: // is full

   	global $Itemid;
	$cart_itemid = digistoreHelper::getCartItemid();
	$andItemid = "";
	if($cart_itemid != "0"){
		$andItemid = "&Itemid=".$cart_itemid;
	}
	$product_itemid = digistoreHelper::getProductItemid();
	$andProdItem = "";
	if($product_itemid != "0"){
		$andProdItem = "&Itemid=".$product_itemid;
	}


?>

<?php
	include('sortbox_include.php');

	$direction = $this->configs->list_orientation == "0" ? "ltr" : "rtl";
?>

<?php 
if($direction == "rtl"){
?>
	<style type="text/css">
		.ijd-td{
			float:right !important;
		}

		.dsdesc{
			text-align:right !important;
		}

		#dslayout-listmulti a:link, #dslayout-listmulti a:visited{
			float: right !important;
			text-align: right !important;
		}
	</style>
<?php
}
?>

<form name="addproduct" method="post" action="index.php" style="direction:<?php echo $direction; ?>">

<div class="ijd-box ijd-rounded">
	<div class="ijd-row" style="direction:rtl !important;">
		<?php if ($this->configs->showthumb == 1) : ?>
		<div class='ijd-width-25 ijd-center ijd-th-<?php echo $direction; ?> hidden-phone'>&nbsp;</div>
		<div class='ijd-width-25 ijd-center ijd-th-<?php echo $direction; ?>'><?php echo JText::_("DSNAME");?></div>
		<?php else :  ?>
		<div class='ijd-width-50 ijd-center ijd-th-<?php echo $direction; ?>'><?php echo JText::_("DSNAME");?></div>
		<?php endif; ?>
		<div class='ijd-width-25 ijd-center ijd-th-<?php echo $direction; ?>'><?php echo JText::_("DSPRICE");?></div>
		<div class='ijd-width-19 ijd-center ijd-th-<?php echo $direction; ?>'><?php echo JText::_("DSQUONT");?></div>
	</div>

	<div class="ijd-horizontal-separator"></div>

<?php
	foreach($this->prods as $i => $prod):
		$k++;
		$id = $prod->id;
		$db = JFactory::getDBO();
		$sql = "select price from #__digistore_products_plans where product_id=".intval($id)." and `default`=1";
		$db->setQuery($sql);
		$db->query();
		$default_result = $db->loadResult();
		if(isset($default_result)){
			$prod->price = $default_result;
			$prod->price = digistoreHelper::format_price2($prod->price, $this->configs->currency, true, $this->configs);
		}

		switch ($prod->articlelinkuse) {
			case "0":
				$link = "javascript: void(0);";
				break;
			case "1":
					$article_id = intval($prod->articlelinkid);
					$link = "";
					if($article_id != "0"){
						$itemid_string = "";
						$itemid_page = JRequest::getVar("Itemid", "0");
						if($itemid_page != "0" && $itemid_page != ""){
							$itemid_string = "&Itemid=".$itemid_page;
						}

						$db = JFactory::getDBO();
						$sql = "select `alias`, `catid` from #__content where id=".intval($article_id);
						$db->setQuery($sql);
						$db->query();
						$result = $db->loadAssocList();
						$link = JRoute::_("index.php?option=com_content&view=article&id=".$article_id.":".trim($result["0"]["alias"])."&catid=".intval(trim($result["0"]["catid"])).$itemid_string);
					}
				break;
			case "2":
				$link = $prod->articlelink;
				break;
			case "3":
			default:
				$link = JRoute::_("index.php?option=com_digistore&controller=digistoreProducts&task=view&cid=".$this->catid.$andProdItem."&pid=".$id);
		}

		if (!$prod->usestock || ($prod->usestock && $prod->showstockleft && $prod->stock == 0 && $prod->emptystockact == 0) ) :
?>
	<div class="ijd-row ijd-pad5">
<?php
		if ( $this->configs->showthumb ) :
?>
		<div class="ijd-width-25 ijd-center ijd-td hidden-phone">
			<?php
				echo '<a title="'.$prod->name.'" href="'.$link.'" ' . ($prod->articlelinkuse == 2 ? 'target="_blank"' : '') . '>'.ImageHelper::ShowImage($prod).'</a>';
			?>
		</div>
		<div class="ijd-width-25 ijd-td">
<?php
		else :
?>
		<div class="ijd-width-50 ijd-td">
<?php
		endif;
?>
			<a href="<?php echo $link;?>" <?php echo ($prod->articlelinkuse == 2 ? 'target="_blank"' : '');?>><?php echo $prod->name;?></a>

			<?php
				if(trim($prod->subtitle) != ""){
			?>
					<span class="ijd-product-subtitle" style="margin-left:0px !important;"><?php echo $prod->subtitle; ?></span>
			<?php
				}
			?>

			<?php
					echo "<br /><div class='dsdesc hidden-phone'>".digistoreHelper::ShowProdDesc($prod->description, $this->configs, "list")."</div>";
			?>
		</div>

		<div class="ijd-width-25 ijd-center ijd-td <?php echo trim($this->configs->prods_price_class); ?>">
			<?php 
				if(!is_null($prod->price)){
					echo $prod->price;
				}
				else{
					echo "&nbsp;";
				}
			?>
		</div>

		<div class="ijd-width-19 ijd-center ijd-td">
			<?php echo $lists['qty'][$id];?>
		</div>

		<?php
			if($this->configs->list_multi_selection == "0"){
		?>
				<div class="ijd-width-10 ijd-center ijd-td">
					<input type="checkbox" name="addtocart[<?php echo $prod->id;?>]"  class="button" value="1"/>
					<input type="hidden" name="prodid[]" value="<?php echo $prod->id;?>" />
				</div>
		<?php
			}
			else{
		?>
				<div>
					<form name="addproduct2" method="post" action="index.php" onsubmit="return prodformsubmitA<?php echo $i; ?>()">
						<input type="hidden" name="option" value="com_digistore"/>
						<input type="hidden" name="controller" value="digistoreCart"/>
						<input type="hidden" name="task" value="add"/>
						<?php if (JRequest::getVar('layout','none') != 'none') : ?>
							<input type="hidden" name="layout" value="<?php echo JRequest::getVar('layout','list'); ?>"/>
						<?php endif; ?>
						<input type="hidden" name="qty" value="1" id="product_qty_id_<?php echo $prod->id; ?>"/>
						<?php echo $lists[$prod->id]['attrib_hidden'];?>
						<input type="hidden" name="pid" value="<?php echo $id; ?>"/>
						<input type="hidden" name="cid" value="<?php echo $this->catid; ?>"/>
						<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>"/>
						<div class="dsaddtocart" <?php if($direction == "rtl"){echo 'style="float:left;"';} ?> ><?php
							if ($prod->cartlinkuse == 1)
							{ ?>
								<div class="btn-group">
									<a href="<?php echo $link_article;?>" class="btn btn-small" title="<?php echo JText::_("DIGI_PRODUCT_DETAILS");?>" <?php echo ($prod->articlelinkuse == 2 ? 'target="_blank"' : '');?>><i class="ico-folder-open"></i> <?php echo JText::_("DIGI_PRODUCT_DETAILS");?></a>
									<a href="<?php echo $prod->cartlink;?>" class="btn btn-warning" target="_blank"><i class="ico-shopping-cart"></i> <?php echo JText::_("DSADDTOCART");?></a>
								</div><?php
							}
							else
							{
								if($this->configs->afteradditem == "2"){
									$doc = JFactory::getDocument();
									$doc->addScript(JURI::base()."components/com_digistore/assets/js/createpopup.js"); ?>
									<button type="button" class="btn btn-warning" onclick="javascript:createPopUp(<?php echo $id; ?>, <?php echo $this->catid; ?>, '<?php echo JURI::root(); ?>', '', '', <?php echo $cart_itemid; ?>, '<?php echo JRoute::_("index.php?option=com_digistore&view=digistorecart&Itemid=".$cart_itemid) ?>');"><i class="ico-shopping-cart"></i> <?php echo JText::_("DSADDTOCART");?></button><?php
								}
								else
								{ ?>
									<button type="submit" class="btn btn-warning"><i class="ico-shopping-cart"></i> <?php echo JText::_("DSADDTOCART");?></button><?php
								}
							} ?>
						</div>
					</form>
			</div>
		<?php
			}
		?>
	</div>
<?php
		else :
?>
	<div class="ijd-row ijd-pad5">
<?php
		if ( $this->configs->showthumb ) :
?>
		<div class="ijd-width-25 ijd-td">
			<?php
				echo '<a title="'.$prod->name.'" href="'.$link.'">'.ImageHelper::ShowImage($prod).'</a>';
			?>
		</div>
		<div class="ijd-width-25 ijd-td">
<?php
		else :
?>
		<div class="ijd-width-50 ijd-td">
<?php
		endif;
?>
			<a href="<?php echo $link;?>" ><?php echo $prod->name;?></a>

			<br/>
		 	<?php
					echo "<div class='dsdesc'>".digistoreHelper::ShowProdDesc($prod->description, $this->configs, "list")."</div>";
			?>
		</div>

		<div class="ijd-width-10 ijd-td <?php echo trim($this->configs->prods_price_class); ?>">
			<?php echo $prod->price; ?>
		</div>

		<div class="ijd-width-10 ijd-td">
			<?php echo $lists['qty'][$id];?>
		</div>

		<?php if ($this->maxf > 0):?>
		<div class="ijd-width-14 ijd-center ijd-td">&nbsp;
			<?php echo $lists[$prod->id]['attribs'];?>
		</div>
	   <?php endif; ?>

		<div class="ijd-width-10 ijd-center ijd-td">
			<input type="checkbox" name="addtocart[<?php echo $prod->id;?>]"  class="button" value="1"/>
			<input type="hidden" name="prodid[]" value="<?php echo $prod->id;?>" />
		</div>
	</div>
<?php
		endif;
?>
<?php
	if($k != count($this->prods)){
?>
		<div class="ijd-horizontal-separator"></div>
<?php
	}
	endforeach;
?>

	<?php
		if($this->configs->list_multi_selection == "0"){//multi = Yes
	?>
			<div class="dsaddtocart" <?php if($direction == "rtl"){echo 'style="float:left;"';} ?> ><input type="submit" value="<?php echo JText::_("DSADDTOCART");?>" class="btn"/></div>
	<?php
		}
	?>

		<input type="hidden" name="option" value="com_digistore"/>
		<input type="hidden" name="controller" value="<?php echo 'digistoreCart'; ?>"/>
		<input type="hidden" name="task" value="<?php echo $this->configs->list_multi_selection == "0" ? "addMulti" : 'add'; ?>"/>
		<?php if (JRequest::getVar('layout','none') != 'none') : ?>
		<input type="hidden" name="layout" value="<?php echo JRequest::getVar('layout','list'); ?>"/>
		<?php endif; ?>
		<input type="hidden" name="cid" value="<?php echo $this->catid; ?>"/>
		<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>"/>

</form>

	<!-- <div class="ijd-horizontal-separator"></div> -->
	<?php
		// $rows = $this->configs->prodlayoutrow;
		// if($rows < $this->pagination->limit)
		// {
			// $limitstart = JRequest::getVar("limitstart", "0");
			// $pagination = new JPagination($this->total, $limitstart, $rows);
			// $this->pagination = $pagination;
			if($this->pagination->getPagesLinks() != ''):?>
				<div class="pagination pagination-centered"><?php echo $this->pagination->getPagesLinks(); ?></div>
				<div style="text-align:center;"><?php echo $this->pagination->getPagesCounter(); ?></div><?php
			endif;
		// }
	?>

</div>

<?php
endif;
?>
 
</div>

<?php

	include ('featured_include.php');

	include ('related_include.php');
?>

</div>

<?php echo digistoreHelper::powered_by(); ?>
