<?php
/**
* @package			DigiStore Joomla Extension
 * @author			foobla.com
 * @version			$Revision: 440 $
 * @lastmodified	$LastChangedDate: 2013-11-20 04:53:55 +0100 (Wed, 20 Nov 2013) $
 * @copyright		Copyright (C) 2013 foobla.com. All rights reserved.
* @license			GNU/GPLv3
*/

defined ('_JEXEC') or die ("Go away.");

$document = JFactory::getDocument();
$document->addStyleSheet("components/com_digistore/assets/css/digistore.css");

?>

<div class="row-fluid">
	<!-- Main -->
	<div class="span7">
		<div class="row-fluid">
			<div class="span5">
				<img src="components/com_digistore/assets/images/logo.png" />
			</div>
			<div class="span6">
				<div class="span12" style="text-align: right; float: right;">
					<?php echo $this->versionNotify(); ?>
				</div>
			</div>
		</div>
		
		<!-- LATEST ORDERS -->
		<div class="well well-small">
			<div class="module-title nav-header"><a href="index.php?option=com_digistore&controller=digistoreOrders"><?php echo JText::_('DIGISTORE_LATESTORDERS'); ?></a></div>
			<div class="row-striped">
				<?php
				foreach($this->latest_orders AS $order) :
				?>
				<div class="row-fluid">
					<div class="span7">
						<span class="label label-ds hasTip" title="" data-original-title="Order ID"><a href="index.php?option=com_digistore&controller=digistoreOrders&task=show&cid[]=<?php echo $order->id; ?>"><?php echo JText::_('VIEWLICLICORDERID').$order->id; ?></a></span>
						<strong class="row-title">
							<a href="index.php?option=com_digistore&controller=digistoreCustomers&task=edit&cid[]=<?php echo $order->userid;?>">
								<?php echo $order->firstname.' '.$order->lastname;?></a>
						</strong>
					</div>
					<div class="span2">
						<span class="small pull-right"><?php echo $order->amount. ' '.$order->currency; ?></span>
					</div>
					<div class="span3">
						<span class="small"><i class="icon-calendar"></i> <?php echo date("Y-m-d", $order->order_date); ?></span>
					</div>
				</div>
				<?php
				endforeach;
				?>
			</div>
		</div>
		
		<!-- LATEST PRODUCTS -->
		<div class="well well-small">
			<div class="module-title nav-header"><a href="index.php?option=com_digistore&controller=digistoreProducts"><?php echo JText::_('DIGISTORE_RECENTPROD'); ?></a></div>
			<div class="row-striped">
				<?php
				foreach($this->latest_products AS $product) :
				?>
				<div class="row-fluid">
					<div class="span7">
						<span class="label label-ds hasTip" title="" data-original-title="Order ID"><?php echo $product->sku; ?></span>
						<strong class="row-title">
							<a href="index.php?option=com_digistore&controller=digistoreProducts&task=edit&cid[]=<?php echo $product->id;?>">
								<?php echo $product->name;?></a>
						</strong>
					</div>
					<div class="span2">
						<span class="small pull-right"><a href="index.php?option=com_digistore&controller=digistoreCategories&task=edit&cid[]=<?php echo $product->catid; ?>"><?php echo $product->category; ?></a></span>
					</div>
					<div class="span3">
						<span class="small"><i class="icon-calendar"></i> <?php echo date("Y-m-d", $product->publish_up); ?></span>
					</div>
				</div>
				<?php
				endforeach;
				?>
			</div>
		</div>
		
		<!-- TOP CUSTOMERS -->
		<div class="well well-small">
			<div class="module-title nav-header"><a href="index.php?option=com_digistore&controller=digistoreCustomers"><?php echo JText::_('DIGISTORE_TOPCUSTOMERS'); ?></a></div>
			<div class="row-striped">
				<?php
				foreach($this->top_customers AS $customer) :
				?>
				<div class="row-fluid">
					<div class="span7">
						<strong class="row-title">
							<a href="index.php?option=com_digistore&controller=digistoreCustomers&task=edit&cid[]=<?php echo $customer->userid;?>">
								<?php echo $customer->firstname.' '.$customer->lastname;?></a>
						</strong>
					</div>
					<div class="span2">
						<span class="small pull-right"><?php echo $customer->amount_paid. ' '.$customer->currency; ?></span>
					</div>
					<div class="span3">
						<span class="small"><i class="icon-calendar"></i> <?php echo date("Y-m-d", $customer->order_date); ?></span>
					</div>
				</div>
				<?php
				endforeach;
				?>
			</div>
		</div>
	</div>
	<!-- // End Main -->
	
	<!-- Right -->
	<div class="span5">
		<div class="well well-small">
			<div class="module-title nav-header"><?php echo JText::_('DIGISTORE_QUICKICONS'); ?></div>
			<div class="row-striped">
				<div class="row-fluid">
					<div class="span12">
						<a href="index.php?option=com_digistore&controller=digistoreProducts&task=add">
							<i class="icon-pencil"></i> <span><?php echo JText::_('DIGISTORE_ADDNEWPRODUCT'); ?></span>
						</a>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span12">
						<a href="index.php?option=com_digistore&controller=digistoreCategories&section=com_digistore_product&task=add">
							<i class="icon-folder"></i> <span><?php echo JText::_('DIGISTORE_ADDNEWCATEGORY'); ?></span>
						</a>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span12">
						<a href="index.php?option=com_digistore&controller=digistoreCustomers&task=add">
							<i class="icon-user"></i> <span><?php echo JText::_('DIGISTORE_ADDNEWCUSTOMER'); ?></span>
						</a>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span12">
						<a href="index.php?option=com_digistore&controller=digistoreAttributes&task=add">
							<i class="icon-color-palette"></i> <span><?php echo JText::_('DIGISTORE_ADDNEWATTRIBUTE'); ?></span>
						</a>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span12">
						<a href="index.php?option=com_digistore&controller=digistoreOrders&task=add">
							<i class="icon-key"></i> <span><?php echo JText::_('DIGISTORE_ADDNEWLICENSE'); ?></span>
						</a>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span12">
						<a href="index.php?option=com_digistore&controller=digistorePromos&task=add">
							<i class="icon-bookmark"></i> <span><?php echo JText::_('DIGISTORE_ADDNEWCOUPON'); ?></span>
						</a>
					</div>
				</div>
			</div>
		</div>
		
		<div class="alert alert-info">
			<?php echo JText::_('DIGISTORE_CREDITS'); ?>
			<div class="clearfix"></div>
		</div>
	</div>
	<!-- // End Right -->
</div>