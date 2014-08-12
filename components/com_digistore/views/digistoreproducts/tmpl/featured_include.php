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


JHTML::_('behavior.modal');

if ( $this->configs->showfeatured_prod ) :

	if ( count($this->featured_prods) > 0) : 

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
	$fcatid = JRequest::getVar('cid');

?>
<!-- Featured products -->
<h3 class="ijd-index-title ijd-rounded"><?php echo JText::_('DS_FEATURED_PRODUCTS'); ?></h3>
<div class="ijd-box ijd-rounded">
	<?php 
		$fitem = 1;
		foreach( $this->featured_prods as $featured ) :

			$link = JRoute::_( "index.php?option=com_digistore&controller=digistoreProducts&task=view&cid=".$fcatid."&pid=".$featured->id.$andProdItem);

	?>
	<div class="ijd-index-product <?php echo ( $fitem % 3 == 0 ) ? "" : "ijd-vertical-separator" ; ?> ijd-width-33 ijd-left">
		<a title="<?php echo $featured->name; ?>" href="<?php echo $link; ?>">
			<?php echo ImageHelper::ShowImage($featured); ?>
		</a>
		<h3 class="ijd-index-box-title ijd-center">
			<a title="<?php echo $featured->name; ?>" href="<?php echo $link; ?>"> 
				<?php echo $featured->name; ?>
			</a>
		</h3>
		<?php if ( !is_null($featured->price) ) : ?>
		<span class="ijd-product-price ijd-row ijd-center <?php echo trim($this->configs->prods_price_class); ?>"><?php echo $featured->price; ?></span>
		<?php endif; ?>
		<!-- Add to Cart -->

		<?php
// 					$validation_js_script = digistoreHelper::addValidation($featured->productfields, $this->pids[$k]);
// 					$doc = JFactory::getDocument();
// 					$doc->addScriptDeclaration( $validation_js_script );
		?>

					<?php if (!$featured->usestock || ($featured->usestock && $featured->showstockleft && $featured->stock == 0 && $featured->emptystockact == 0) ) { ?>
					<form name="featured<?php echo $featured->id;?>" method="post" action="index.php">
					<!--  onsubmit="return prodformsubmitA<?php //echo $this->pids[$k]; ?>()" -->
					<table width="100%"><tr><td>
					 </td></tr>

						<tr><td><input type="hidden" name="option" value="com_digistore"/>
						<input type="hidden" name="controller" value="digistoreCart"/>
						<input type="hidden" name="task" value="add"/>
						<?php if (JRequest::getVar('layout','none') != 'none') : ?>
						<input type="hidden" name="layout" value="<?php echo JRequest::getVar('layout','list'); ?>"/>
						<?php endif; ?>
						<input type="hidden" name="qty" value="1" id="product_qty_id_<?php echo $featured->id; ?>"/>
						<input type="hidden" name="pid" value="<?php echo $featured->id; ?>"/>
						<input type="hidden" name="cid" value="<?php echo $this->catid; ?>"/>
						<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>"/>   
						<div class="dsaddtocart" align="center" style="text-align:center">
							<?php
								if($this->configs->afteradditem == "2"){
									$doc = JFactory::getDocument();
									$doc->addScript(JURI::base()."components/com_digistore/assets/js/createpopup.js");
							?>
									<input type="button" value="<?php echo JText::_("DSADDTOCART");?>" class="btn" onclick="javascript:createPopUp(<?php echo $featured->id; ?>, <?php echo $this->catid; ?>, '<?php echo JURI::root(); ?>', '', '', <?php echo $cart_itemid; ?>, '<?php echo JRoute::_("index.php?option=com_digistore&view=digistorecart&Itemid=".$cart_itemid) ?>');"/>
							<?php
								}
								else{
							?>
									<input type="submit" value="<?php echo JText::_("DSADDTOCART");?>" class="btn"/>
							<?php
								}
							?>
						</div>
						</td></tr>
					</table>
					</form>

			<?php } else { ?>

					<?php if ($featured->usestock && ($featured->used < $featured->stock)) : ?>
					<form name="featured<?php echo $featured->id;?>" method="post" action="index.php">
					<?php endif; ?>
					<table width="100%"><tr><td>		 
							<?php
							//if ($this->configs->showprodshort == 1) echo "<div class='dsdesc'>".$featured->description."</div>";
					?>
					 </td></tr> 
						<?php if ($featured->usestock && (($featured->stock - $featured->used) > 0)) : ?>
		<?php if ($featured->showqtydropdown == 1) { ?>
						<tr><td><div class="dsqty"><?php echo $lists['qty'][$id];?></div></td></tr>
		<?php } ?>
						<?php if ($this->maxf > 0):?>  
						<tr><td><div class="dsattribs">
							<?php echo $lists[$featured->id]['attribs'];?>
							</div>
						</td></tr>
						<?php endif; ?>
						<tr><td><input type="hidden" name="option" value="com_digistore"/>
						<input type="hidden" name="controller" value="digistoreCart"/>
						<input type="hidden" name="task" value="add"/>
						<?php if (JRequest::getVar('layout','none') != 'none') : ?>
						<input type="hidden" name="layout" value="<?php echo JRequest::getVar('layout','list'); ?>"/>
						<?php endif; ?>
						<input type="hidden" name="qty" value="1" id="product_qty_id_<?php echo $featured->id; ?>"/>
						<input type="hidden" name="pid" value="<?php echo $featured->id; ?>"/>
						<input type="hidden" name="cid" value="<?php echo $this->catid; ?>"/>
						<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>"/>
						<div class="dsaddtocart" align="center" style="text-align:center"> 
							<input type="submit" value="<?php echo JText::_("DSADDTOCART");?>" class="button"/>
						</div>
						</td></tr>
						<?php endif; ?>  

						<?php if ($featured->usestock && $featured->showstockleft && ($featured->used < $featured->stock)) : ?>
						<tr><td><div class="dsremainingstock"><?php echo sprintf(JText::_("DS_NUMBER_IN_STOCK"), $featured->stock - $featured->used); ?></div></td></tr>
						<?php endif; ?>

						<?php if ($featured->usestock && $featured->emptystockact && ($featured->used >= $featured->stock)) :?>
						<tr><td><div class="dssoldout"><?php echo JText::_("DSOUTOFSTOCK"); ?></div></td></tr>
						<?php endif; ?>
							  
					</table>
			<?php if ($featured->usestock && ($featured->used < $featured->stock)) : ?>
					</form>
			<?php endif; ?> 
			<?php } // end if use stock option ?>

		<!-- End / Add to Cart -->

	</div>
	<?php
			$fitem++;
		endforeach;
	?>
</div>
<!-- /End Featured products -->
<?php
	endif;

endif;

?>