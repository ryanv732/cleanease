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

$invisible = 'style="display:none;"';

$document = JFactory::getDocument();
$document->addStyleSheet("components/com_digistore/assets/css/digistore.css");

$k = 0;
$n = count( $this->orders );
$configs = $this->configs;
$f = $configs->time_format;
$f = str_replace( "-", "-%", $f );
$f = "%" . $f;

if ( $n < 1 ):

?>

<div style="float:right;">
	<div style="padding:0 1em 1em;">
		<form name="timesearch" method="post" action="index.php">
			<?php echo JText::_( "DSFROM" ); ?>:
			<?php echo JHTML::_( "calendar", $this->startdate > 0 ? date( $configs->time_format, $this->startdate ) : "", 'startdate', 'startdate', $f, ' class="span2"' ); ?>&nbsp;
			<?php echo JText::_( "DSTO" ); ?>:
			<?php echo JHTML::_( "calendar", $this->enddate > 0 ? date( $configs->time_format, $this->enddate ) : "", 'enddate', 'enddate', $f ); ?>
			&nbsp;
			<input type="submit" name="go" value="<?php echo JText::_( "DSGO" ); ?>" class="btn" />
			<input type="hidden" name="option" value="com_digistore" />
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="controller" value="digistoreOrders" />
		</form>
	</div>

	<div style="padding:0 1em 1em;">
		<form name="search" method="post" action="index.php">
			<?php echo JText::_( "DSKEYWORD" ); ?>:
			<input type="text" name="keyword" value="<?php echo (strlen( trim( $this->keyword ) ) > 0 ? $this->keyword : ""); ?>" />
			<input type="submit" name="go" value="<?php echo JText::_( "DSSEARCH" ); ?>" class="btn" />
			<input type="hidden" name="option" value="com_digistore" />
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="controller" value="digistoreOrders" />
		</form>
	</div>
</div>

<table>
	<tr>
		<td class="header_zone" colspan="4">
			<?php
				echo JText::_("HEADER_ORDERS");
			?>
		</td>
	</tr>
</table>

<table class="adminlist table">
	<thead>
		<tr>
			<th width="5">
			<input type="checkbox" onclick="checkAll(<?php echo $n; ?>)" name="toggle" value="" />
			</th>
			<th>
			<?php echo JText::_( 'VIEWORDERSDATE' ); ?>
			</th>
			<th <?php if ( $configs->showolics == 0 )
					echo $invisible; ?>>
			<?php echo JText::_( 'VIEWORDERSNOL' ); ?>
			</th>
			<th>
			<?php echo JText::_( 'VIEWORDERSPRICE' ); ?>
			</th>
			<th>
			<?php echo JText::_( 'VIEWORDERSUSERNAME' ); ?>
			</th>
			<th>
			<?php echo JText::_( 'VIEWORDERSCUST' ); ?>
			</th>
			<th>
			<?php echo JText::_( 'VIEWORDERSSTATUS' ); ?>
			</th>
			<th>
			<?php echo JText::_( 'VIEWORDERSPAYMETHOD' ); ?>
			</th>
			<th width="20">
			<?php echo JText::_( 'VIEWORDERSID' ); ?>
			</th>
		</tr>
	</thead>
</table>
<div style="padding:1em 0;">
	<?php echo $this->pagination->getListFooter(); ?>
</div>

<form action="index.php" id="adminForm" name="adminForm" method="post">
	<input type="hidden" name="option" value="com_digistore" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="controller" value="digistoreOrders" />
</form>

<?php

else:

?>
<table>
	<tr>
		<td align="right">
			<form name="search" method="post" action="index.php" class="form-inline">
			<input type="text" name="keyword" value="<?php echo (strlen( trim( $this->keyword ) ) > 0 ? $this->keyword : ""); ?>" placeholder="<?php echo JText::_( "DSKEYWORD" ); ?>" class="span6" />
			<input type="submit" name="go" value="<?php echo JText::_( "DSSEARCH" ); ?>" class="btn" />
			<input type="hidden" name="option" value="com_digistore" />
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="controller" value="digistoreOrders" />
			</form>
		</td>
	</tr>
	<tr>
		<td align="right">
			<form name="timesearch" method="post" action="index.php" class="form-inline">
			<?php echo JText::_( "DSFROM" ); ?>:
			<?php echo JHTML::_( "calendar", $this->startdate > 0 ? date( $configs->time_format, $this->startdate ) : "", 'startdate', 'startdate', $f, array('class'=>'span2'), array('class'=>'span2'), array('class'=>'span2')); ?>&nbsp;
			<?php echo JText::_( "DSTO" ); ?>:
			<?php echo JHTML::_( "calendar", $this->enddate > 0 ? date( $configs->time_format, $this->enddate ) : "", 'enddate', 'enddate', $f ); ?>
			&nbsp;
			<input type="submit" name="go" value="<?php echo JText::_( "DSGO" ); ?>" class="btn" />
			<input type="hidden" name="option" value="com_digistore" />
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="controller" value="digistoreOrders" />
			</form>
		</td>
	</tr>
</table>

<table>
	<tr>
		<td class="header_zone" colspan="4">
			<?php
				echo JText::_("HEADER_ORDERS");
			?>
		</td>
	</tr>
</table>

<form action="index.php" id="adminForm" name="adminForm" method="post">
	<table class="adminlist table table-striped">
		<thead>
			<tr>
				<th width="5">
					<input type="checkbox" onclick="checkAll(<?php echo $n; ?>)" name="toggle" value="" />
				</th>
				<th>
					<?php echo JText::_( 'VIEWORDERSDATE' ); ?>
				</th>
				<th  <?php if ( $configs->showolics == 0 ) echo $invisible; ?>>
					<?php echo JText::_( 'VIEWORDERSNOL' ); ?>
				</th>
				<th>
					<?php echo JText::_( 'VIEWORDERSPRICE' ); ?>
				</th>
				<th>
					<?php echo JText::_( 'VIEWORDERSUSERNAME' ); ?>
				</th>
				<th>
					<?php echo JText::_( 'VIEWORDERSCUST' ); ?>
				</th>
				<th>
					<?php echo JText::_( 'VIEWORDERSSTATUS' ); ?>
				</th>
				<th>
					<?php echo JText::_( 'VIEWORDERSPAYMETHOD' ); ?>
				</th>
				<th width="20">
					<?php echo JText::_( 'VIEWORDERSID' ); ?>
				</th>
			</tr>
		</thead>

		<tbody>

<?php
	$z = 0;
	for ( $i = 0; $i < $n; $i++ ):
		++$z;
		$order =  $this->orders[$i];

		$id = $order->id;
		$checked = JHTML::_( 'grid.id', $i, $id );
		$link = JRoute::_( "index.php?option=com_digistore&controller=digistoreLicenses&task=list&oid[]=" . $id );
		$olink = JRoute::_( "index.php?option=com_digistore&controller=digistoreOrders&task=show&cid[]=" . $id );
		$customerlink = JRoute::_( "index.php?option=com_digistore&controller=digistoreCustomers&task=edit&cid[]=" . $order->userid );
		$order->published = 1;
		$published = JHTML::_( 'grid.published', $order, $i );
		$orderstatuslink = JRoute::_( "index.php?option=com_digistore&controller=digistoreOrders&task=cycleStatus&cid[]=" . $id );
		$userlink = "index.php?option=com_users&view=users&filter_search=".$order->username;

?>
		<tr class="row<?php echo $k; ?>">
			<td align="center">
				<?php echo $checked; ?>
			</td>
			<td align="center">
				<?php echo date( $configs->time_format, $order->order_date ); ?>
			</td>
			<td align="center" <?php if ( $configs->showolics == 0 )
					echo $invisible; ?>>
				<a href="<?php echo $link; ?>" ><?php echo $order->licensenum; ?></a>
			</td>
			<td align="center">
				<?php 
					if ($order->amount_paid == "-1") $order->amount_paid = $order->amount;
					$refunds = digistoreAdminModeldigistoreOrder::getRefunds($order->id);
					$chargebacks = digistoreAdminModeldigistoreOrder::getChargebacks($order->id);
					$order->amount_paid = $order->amount_paid - $refunds - $chargebacks;
					echo digistoreAdminHelper::format_price($order->amount_paid, $configs->currency, true, $configs); 
				?>
			</td>
			<td align="center">
				<?php echo ($order->username); ?>
			</td>
			<td align="center">
				<a href="<?php echo $customerlink; ?>" ><?php echo ($order->firstname . " " . $order->lastname); ?></a>
			</td>
			<td align="center">
				<?php
					$a_style = "";
					if($order->status == "Pending"){
						$a_style = 'style="color:red;"';
					}
				?>
				<a href="<?php echo $orderstatuslink; ?>" <?php echo $a_style; ?> ><?php echo (trim( $order->status ) != "in_progres" ? $order->status : "Active"); ?></a>
			</td>
			<td align="center">
				<?php echo $order->processor; ?>
			</td>
			<td align="center">
				<a href="<?php echo $olink; ?>"><?php echo $id; ?></a>
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

	<input type="hidden" name="keyword" value="<?php echo $this->keyword; ?>" />
	<input type="hidden" name="startdate" value="<?php echo $this->startdate > 0 ? date( $configs->time_format, $this->startdate ) : ""; ?>" />
	<input type="hidden" name="enddate" value="<?php echo $this->enddate > 0 ? date( $configs->time_format, $this->enddate ) : ""; ?>" />
	<input type="hidden" name="option" value="com_digistore" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="controller" value="digistoreOrders" />
</form>

<?php

endif;

?>

<script language="javascript" type="text/javascript">
Joomla.submitbutton = function (pressbutton) {
	var form = document.adminForm;
	if (pressbutton == 'remove')
	{
		if (confirm("<?php echo JText::_("CONFIRM_ORDER_DELETE");?>"))
		{
			Joomla.submitform(pressbutton);
		}
		return;
	}

	Joomla.submitform(pressbutton);
}
</script>