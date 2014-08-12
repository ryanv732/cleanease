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

if ( $this->configs->showrelatedprod ) :

	//dsdebug($this->related_prods);

	//dsdebug($this->catid);

	$rcatid = (isset($this->catid)) ? $this->catid : JRequest::getVar('cid', 0);

	if ( !empty($this->related_prods) && (count($this->related_prods) > 0)) :	// && $rcatid  

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

<!-- Related products -->
<div class="digistore">
	<h4><?php echo JText::_('DS_RELATED_PRODUCTS'); ?></h4>
	<div class="row-fluid">
		<?php

		$img_align = "align_topcenter";
		if($this->configs->grid_image_align == "0"){// top center
			$img_align = "align_topcenter";
		}
		elseif($this->configs->grid_image_align == "1"){// left
			$img_align = "align_left";
		}
		elseif($this->configs->grid_image_align == "2"){// right
			$img_align = "align_right";
		}

		$fitem = 1;
		$i = 0;
		foreach( $this->related_prods as $related ) :
			$rcatid = $this->getRelatedCategory($related->id);
			$rplanid = $this->getDefaultPlan($related->id);

			$i ++;
			$link = JRoute::_( "index.php?option=com_digistore&controller=digistoreProducts&task=view&cid=".$rcatid."&pid=".$related->id.$andProdItem);

		?>
		<div class="span<?php echo $span; ?>">
			<p style="font-weight:bold;" class="<?php echo $img_align;?>">
				<a title="<?php echo $related->name;?>" href="<?php echo $link;?>"><?php echo $related->name; ?></a>
			</p>
			<?php if(trim($related->defprodimage) != ""):?>
			<p class="<?php echo $img_align;?>">
				<a title="<?php echo $related->name;?>" href="<?php echo $link;?>">
					<?php echo str_replace("<div class='dsimage'>", '', str_replace("</div>", '', ImageHelper::ShowImage($related))); ?>
				</a>
				<?php if(trim($related->subtitle) != ""): ?>
					<br />
					<?php echo $related->subtitle;?>
				<?php endif;?>
			</p>
			<?php endif;?>

			<?php if(!is_null($related->price)) : ?>
			<p class="<?php echo $img_align;?>"><?php echo digistoreHelper::format_price2($related->price, $this->configs->currency, true, $this->configs); ?></p>
			<?php endif; ?>

			<p class="<?php echo $img_align; ?>">
				<?php echo digistoreHelper::ShowProdDesc($related->description, $this->configs, "list"); ?>
			</p>

			<!-- Add to Cart -->
			<?php
						$validation_js_script = digistoreHelper::addValidation(@$related->productfields, @$this->pids[$k]);
						$doc = JFactory::getDocument();
						$doc->addScriptDeclaration( $validation_js_script );
			?>

						<?php if (!$related->usestock || ($related->usestock && $related->showstockleft && $related->stock == 0 && $related->emptystockact == 0) ) { ?>
						<form name="featured<?php echo $related->id;?>" method="post" action="index.php">
						<input type="hidden" name="option" value="com_digistore"/>
						<input type="hidden" name="controller" value="digistoreCart"/>
						<input type="hidden" name="task" value="add"/>
						<?php if (JRequest::getVar('layout','none') != 'none') : ?>
							<input type="hidden" name="layout" value="<?php echo JRequest::getVar('layout','list'); ?>"/>
						<?php endif; ?>
						<input type="hidden" name="qty" value="1" id="product_qty_id_<?php echo $related->id; ?>"/>
						<input type="hidden" name="pid" value="<?php echo $related->id; ?>"/>
						<input type="hidden" name="cid" value="<?php echo $this->catid; ?>"/>
						<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>"/>
						<input type="hidden" name="plan_id" value="<?php echo $rplanid; ?>"/>

						<table width="100%">
							<tr><td>
							<div class="<?php echo $img_align;?>">
								<?php
									if($this->configs->afteradditem == "2"){
										$doc = JFactory::getDocument();
										$doc->addScript(JURI::base()."components/com_digistore/assets/js/createpopup.js");
								?>
										<button type="button" class="btn btn-warning" onclick="javascript:createPopUp(<?php echo $related->id; ?>, <?php echo $this->catid; ?>, '<?php echo JURI::root(); ?>', '', '', <?php echo $cart_itemid; ?>, '<?php echo JRoute::_("index.php?option=com_digistore&view=digistorecart&Itemid=".$cart_itemid) ?>');"><i class="ico-shopping-cart"></i> <?php echo JText::_('DSADDTOCART'); ?></button>
								<?php
									}
									else{
								?>
										<button type="submit" class="btn btn-warning"><i class="ico-shopping-cart"></i> <?php echo JText::_('DSADDTOCART'); ?></button>
								<?php
									}
								?>
							</div>
							</td></tr>
						</table>
						</form>

				<?php } else { ?>

						<?php if ($related->usestock && ($related->used < $related->stock)) : ?>
						<form name="featured<?php echo $related->id;?>" method="post" action="index.php">
						<?php endif; ?>
						<table width="100%">
							<?php if ($related->usestock && (($related->stock - $related->used) > 0)) : ?>
							<?php if ($related->showqtydropdown == 1) { ?>
							<tr><td><?php echo $lists['qty'][$id];?></td></tr>
							<?php } ?>
							<?php if ($this->maxf > 0):?>
							<tr><td>
								<?php echo $lists[$related->id]['attribs'];?>
							</td></tr>
							<?php endif; ?>
							<tr><td><input type="hidden" name="option" value="com_digistore"/>
							<input type="hidden" name="controller" value="digistoreCart"/>
							<input type="hidden" name="task" value="add"/>
							<?php if (JRequest::getVar('layout','none') != 'none') : ?>
							<input type="hidden" name="layout" value="<?php echo JRequest::getVar('layout','list'); ?>"/>
							<?php endif; ?>
							<input type="hidden" name="qty" value="1" id="product_qty_id_<?php echo $related->id; ?>"/>
							<input type="hidden" name="pid" value="<?php echo $related->id; ?>"/>
							<input type="hidden" name="cid" value="<?php echo $this->catid; ?>"/>
							<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>"/>
							<div class="<?php echo $img_align;?>">
								<button type="submit" class="btn btn-warning"><i class="ico-shopping-cart"></i> <?php echo JText::_('DSADDTOCART'); ?></button>
							</div>
							</td></tr>
							<?php endif; ?>

							<?php if ($related->usestock && $related->showstockleft && ($related->used < $related->stock)) : ?>
							<tr><td><div class="dsremainingstock"><?php echo sprintf(JText::_("DS_NUMBER_IN_STOCK"), $related->stock - $related->used); ?></div></td></tr>
							<?php endif; ?>

							<?php if ($related->usestock && $related->emptystockact && ($related->used >= $related->stock)) :?>
							<tr><td><div class="dssoldout"><?php echo JText::_("DSOUTOFSTOCK"); ?></div></td></tr>
							<?php endif; ?>

						</table>
				<?php if ($related->usestock && ($related->used < $related->stock)) : ?>
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
	<!-- /End Related products -->
	<?php

		endif;

	endif;

	?>
</div>