<?php 
/**
 * @package Social Ads
 * @copyright Copyright (C) 2009 -2010 foobla.com, Tekdi Web Solutions . All rights reserved.
 * @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     http://www.foobla.com.com
 */
defined('_JEXEC') or die('Restricted access');


 ?>
<script type="text/javascript">
function calculate(convert_val,count,user_points,not_enough_pts_message,success_message)
{
  var charge_points = convert_val * count;
	var newRow="";
	var flag=0;
 	if(user_points==0 || user_points < charge_points)
  {
		newRow=not_enough_pts_message;
		   alert(newRow);
	}
	else if(user_points >= charge_points)
	{
		flag=1;

	}

	if(flag==1)
	{
	alert(success_message);
		document.getElementById('js_buy').setAttribute('type', 'submit');
		 return true;
  }
	else
	{
		document.getElementById('js_buy').disabled='disabled';
				document.getElementById('edit').disabled='';
		return false;
  }
}

</script>
<div class="akeeba-bootstrap">
<form action="<?php echo $vars->notify_url ?>" class="form-validate form-horizontal"  method="post" id="paymentForm" name="paymentForm">
<div>
		<div>
			<div class="controls">
			<?php echo JText::sprintf( 'CONVERSION_RATE_MESSAGE', $vars->convert_val, $vars->currency_code);?>
			</div>
		</div>
	<div >
	<div>
	<div class="controls">
			<?php
		$charge_points = $vars->convert_val * $vars->amount;
		echo JText::sprintf( 'TOTAL_POINTS_NEEDED_MESSAGE', $charge_points);?>
	</div>
	</div>


	<div>
	<div class="controls">
			<?php
		 echo JText::sprintf( 'CURRENT_POINTS_SITUATION', $vars->user_points);?>
	</div>
	</div>





		<?php $not_enough_pts_message="'".JText::_('NOT_ENOUGH_POINTS_MESSAGE')."'";
			/*if(!empty($vars->success_message))
			$success_message="'".$vars->success_message."'";
			else*/
			$success_message="'".JText::sprintf( 'TOTAL_POINTS_DEDUCTED_MESSAGE',$charge_points)."'";
		?>


	<div class="form-actions">
		<input class="btn btn-success btn-large" id="js_buy" type="button" value="<?php JText::_('SUBMIT');?>" onclick="calculate(<?php echo $vars->convert_val;?>,<?php echo $vars->amount;?>,<?php echo $vars->user_points;?>,<?php echo $not_enough_pts_message; ?>,<?php echo $success_message; ?> );">
		<input type="hidden" name="order_id" value="<?php echo $vars->order_id; ?>" />
		<input type="hidden" name="client" value="<?php echo $vars->client; ?>" />
		<input type="hidden" name="total" value="<?php echo sprintf('%02.2f',$vars->amount) ?>" />
		<input type="hidden" name="user_id" size="10" value="<?php echo $vars->user_id;?>" />
		<input type='hidden' name='return' value="<?php echo $vars->return;?>" >
		<input type="hidden" name="plugin_payment_method" value="onsite" />
	</div>

</div>
</form>
</div>
