<?php 
/**
 * @package Social Ads
 * @copyright Copyright (C) 2009 -2010 foobla.com, Tekdi Web Solutions . All rights reserved.
 * @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     http://www.foobla.com.com
 */
defined('_JEXEC') or die('Restricted access');

 ?>

<div class="akeeba-bootstrap">
<form action="<?php echo $vars->action_url ?>"  method="post" id="paymentForm">
	<div>
	<input type="hidden" name="sid" value="<?php echo $vars->sid?>" />
	<input type="hidden" name="cart_order_id" value="<?php echo $vars->order_id ?>" />
	<input type="hidden" name="total" value="<?php echo sprintf('%02.2f',$vars->amount) ?>" />

	<input type="hidden" name="demo" value="<?php echo  $vars->demo; ?>" />
  <input type="hidden" name="merchant_order_id" value="<?php echo $vars->order_id ?>" />
	<input type="hidden" name="fixed" value="Y" />
	<input type="hidden" name="lang" value="<?php echo $vars->lang; ?>" />
	<input type='hidden' name='return_url' value="<?php echo $vars->return;?>" >
	<input type='hidden' name='x_receipt_link_url' value="<?php echo $vars->notify_url;?>" >
	<input type="hidden" name="pay_method" value="<?php echo strtoupper($vars->pay_method); ?>" />
	<input type="hidden" name="id_type" value="1" />
		<div class="form-actions">
			<input name='submit' type='submit' class="btn btn-success btn-large" value="<?php echo JText::_('SUBMIT'); ?>" >
		</div>
	</div>
</form>
</div>

