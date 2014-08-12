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

$startdate = JRequest::getVar('startdate', '');
$enddate = JRequest::getVar('enddate', '');
$report = JRequest::getVar("report", "daily");
$configs = $this->configs;

$result = $this->getStartEndDate($report);
$startdate = $result["0"];
$enddate = $result["1"];

$document = JFactory::getDocument();
$document->addStyleSheet("components/com_digistore/assets/css/diagrams.css");
$document->addStyleSheet("components/com_digistore/assets/css/digistore.css");

?>

<script language="javascript" type="text/javascript">
	function changereport(report){
		if(report == "custom"){
			document.getElementById("td_date").style.display = "block";
		}
		else{
			document.getElementById("td_date").style.display = "none";
			document.adminFormStats.submit();
		}
	}
</script>

<form action="index.php" name="adminFormStats" class="form-horizontal">
	<!-- start select report anc custom date -->
	<table>
		<tr>
			<td nowrap="nowrap"  width="20%">
				<?php
					echo JText::_("DIGI_SELECT_A_REPORT").":";
				?>
			</td>
			<td>
				<select name="report" onchange="javascript:changereport(this.value);">
					<option value="daily" <?php if($report == "daily"){ echo 'selected="selected"';} ?> ><?php echo JText::_("DIGI_DAILY"); ?></option>
					<option value="weekly" <?php if($report == "weekly"){ echo 'selected="selected"';} ?> ><?php echo JText::_("DIGI_WEEKLY"); ?></option>
					<option value="monthly" <?php if($report == "monthly"){ echo 'selected="selected"';} ?> ><?php echo JText::_("DIGI_MONTHLY"); ?></option>
					<option value="yearly" <?php if($report == "yearly"){ echo 'selected="selected"';} ?> ><?php echo JText::_("DIGI_YEARLY"); ?></option>
					<option value="custom" <?php if($report == "custom"){ echo 'selected="selected"';} ?> ><?php echo JText::_("DIGI_CUSTOM"); ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<?php
				$display = "none";
				if($report == "custom"){
					$display = "block";
				}
			?>
			<td colspan="2" nowrap="nowrap" style="display:<?php echo $display; ?>;" id="td_date">
				<?php
					if($report == "custom"){
						$startdate = JRequest::getVar('startdate', '');
						$enddate = JRequest::getVar('enddate', '');
					}

					echo JText::_("VIEWSTATFROM")."&nbsp;";
					echo JHTML::_("calendar", $startdate, 'startdate', 'startdate', "%Y-%m-%d")."&nbsp;";
					echo JText::_("VIEWSTATTO")."&nbsp;";
					echo JHTML::_("calendar", $enddate, 'enddate', 'enddate', "%Y-%m-%d")."&nbsp;&nbsp;";
					echo '<input type="submit" name="Submit" value="'.JText::_("VIEWSTATGO").'" class="btn" />';
				?>
			</td>
		</tr>
	</table>
	<!-- stop select report anc custom date -->

	<!-- start edit header message -->
	<table>
		<tr>
			<td>
				<span style="color:#66CC00; font-size:18px; font-family:Georgia, Arial, Helvetica, sans-serif; font-weight: bold;">
					<?php
						if($report == "daily"){
							$startdate_temp = strtotime($startdate);
							$startdate_temp = date($configs->time_format, $startdate_temp);
							echo JText::_("DIGI_REVENUE_FOR")." ".$startdate_temp;
						}
						elseif($report == "weekly"){
							echo JText::_("DIGI_REVENUE_WEEKLY");
						}
						elseif($report == "monthly"){
							echo JText::_("DIGI_REVENUE_MONTHLY");
						}
						elseif($report == "yearly"){
							echo JText::_("DIGI_REVENUE_YEARLY");
						}
						elseif($report == "custom"){
							$startdate_temp = strtotime($startdate);
							$startdate_temp = date($configs->time_format, $startdate_temp);

							$enddate_temp = strtotime($enddate);
							$enddate_temp = date($configs->time_format, $enddate_temp);
							echo JText::_("DIGI_REVENUE_FOR")." ".$startdate_temp." ".JText::_("VIEWSTATTO")." ".$enddate_temp;
						}
					?>
				</span>
			</td>
		</tr>
	</table>
	<!-- stop edit header message -->

	<!-- start show pagination -->
	<?php 
	if($report == "daily" || $report == "weekly" || $report == "monthly"){ 
	?>
	<table style="background-color:#F7F7F7; height: 30px; /*width: 400px; */color:#C4C3BA;">
		<tr>
			<td align="left" width="<?php if($report == "monthly"){echo "40";}else{echo "25";} ?>%">
				<?php
					$pas = JRequest::getVar("pas", "0");
				?>
				<a href="index.php?report=<?php echo $report; ?>&controller=digistoreStats&option=com_digistore&task=showStats&action=prev&pas=<?php echo ++$pas;?>">
					<?php
						echo JText::_("DIGI_".strtoupper($report)."_PREV");
					?>
				</a>
			</td>
			<td align="center" width="<?php if($report == "monthly"){echo "20";}else{echo "50";} ?>%">
				<?php
					echo $this->getPaginationDate($configs);
				?>
			</td>
			<td align="right" width="<?php if($report == "monthly"){echo "40";}else{echo "25";} ?>%">
				<?php
					$pas = JRequest::getVar("pas", "0");
					if($pas != "0"){
				?>
						<a href="index.php?report=<?php echo $report; ?>&controller=digistoreStats&option=com_digistore&task=showStats&action=next&pas=<?php echo --$pas;?>">
						<?php
							echo JText::_("DIGI_".strtoupper($report)."_NEXT");
						?>
						</a>
				<?php
					}
				?>
			</td>
		</tr>
	</table>
	<?php
	}
	?>
	<!-- stop show pagination -->

	<!-- start edit total, orders and licenses -->
	<?php
		$link_start_date = $startdate;
		$link_end_date = $enddate;
	?>
	<table>
		<tr>
			<td valign="top" style="color:#666666; font-family:Georgia, 'Times New Roman', Times, serif; width:15%">
				<?php
					$total = $this->getTotal('');
					$chargebacks = $this->getTotal('chargebacks');
					$refunds = $this->getTotal('refunds');
					echo '<p style="margin-bottom:5px;"><b>'.JText::_("DSTOTAL").": ".digistoreAdminHelper::format_price($total, $configs->currency, true, $configs) . '</b></p>';
					echo '<p style="margin-bottom:5px;">'.JText::_("LICENSE_CHARGEBACKS").': <span style="color:#ff0000;">'.digistoreAdminHelper::format_price($chargebacks, $configs->currency, true, $configs) . '</span></p>';
					echo '<p style="margin-bottom:5px;">'.JText::_("LICENSE_REFUNDS").': <span style="color:#ff0000;">'.digistoreAdminHelper::format_price($refunds, $configs->currency, true, $configs).'</span></p>';
				?>
			</td>
			<td valign="top" style="color:#666666; font-family:Georgia, 'Times New Roman', Times, serif; width:15%">
				<?php
					$nr_orders = $this->getNrOrders();
					echo '<p><b>'.JText::_("VIEWSTATORD").": ".'<a href="index.php?option=com_digistore&controller=digistoreOrders&startdate='.$link_start_date.'&enddate='.$link_end_date.'">'.$nr_orders.'</a></p>';
				?>
			</td>
			<td valign="top" style="color:#666666; font-family:Georgia, 'Times New Roman', Times, serif; width:15%">
				<?php
					$nr_licenses = $this->getNrLicenses('');
					$chargebacks = $this->getNrLicenses('chargebacks');
					$refunds = $this->getNrLicenses('refunds');
					echo '<p style="margin-bottom:5px;"><b>'.JText::_("VIEWTREELICENCES").": ".'<a href="index.php?option=com_digistore&controller=digistoreLicenses&startdate='.$link_start_date.'&enddate='.$link_end_date.'&ltype=common">'.$nr_licenses.'</a></b></p>';
					echo '<p style="margin-bottom:5px;">'.JText::_("LICENSE_CHARGEBACKS").': <span style="color:#ff0000;">'.$chargebacks . '</span></p>';
					echo '<p style="margin-bottom:5px;">'.JText::_("LICENSE_REFUNDS").': <span style="color:#ff0000;">'.$refunds.'</span></p>';
				?>
			</td>
			<td></td>
		</tr>
	</table>
	<!-- stop edit total, orders and licenses -->

	<!-- start edit first diagram -->
	<br/>
	<br/>
	<?php
		require_once(JPATH_SITE.DS."administrator".DS."components".DS."com_digistore".DS."helpers".DS."diagrams.php");
		echo digistoreDiagram::createTotalDiagram($report, $startdate, $enddate, $configs);
	?>
	<!-- stop edit first diagram -->
	<br/>
	<table>
		<tr>
			<td>
				<span style="color:#66CC00; font-size:18px; font-family:Georgia, Arial, Helvetica, sans-serif; font-weight: bold;">
					<?php echo JText::_("DIGI_TOP_10"); ?>
					<?php
						$startdate_temp = strtotime($startdate);
						$startdate_temp = date($configs->time_format, $startdate_temp);

						$enddate_temp = strtotime($enddate);
						$enddate_temp = date($configs->time_format, $enddate_temp);
						echo $startdate_temp." ".JText::_("DIGI_AND")." ".$enddate_temp; 
					?>
				</span>
			</td>
		</tr>
	</table>
	<br/>
	<!-- start edit second diagram -->
	<?php
		echo digistoreDiagram::createProductsDiagram($report, $startdate, $enddate, $configs);
	?>
	<!-- stop edit second diagram -->

	<input type="hidden" name="controller" value="digistoreStats" />
	<input type="hidden" name="option" value="com_digistore" />
	<input type="hidden" name="task" value="showStats" />
</form>