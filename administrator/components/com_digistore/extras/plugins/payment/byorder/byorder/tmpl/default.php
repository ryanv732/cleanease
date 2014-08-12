<?php 
/**
 * @package Social Ads
 * @copyright Copyright (C) 2009 -2010 foobla.com, Tekdi Web Solutions . All rights reserved.
 * @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     http://www.foobla.com.com
 */

// no direct access
	defined('_JEXEC') or die('Restricted access'); 

$document = JFactory::getDocument();
defined('_JEXEC') or die('Restricted access');

	JHTML::_('behavior.formvalidation');
	 
	if($vars->custom_email=="")
		$email = JText::_('NO_ADDRS');
	else
		$email = $vars->custom_email;




?>
<script type="text/javascript">
function myValidate(f)
{
if (document.formvalidator.isValid(f)) {
		f.check.value='<?php echo JUtility::getToken(); ?>'; 
		return true; 
	}
	else {
		var msg = 'Some values are not acceptable.  Please retry.';
		alert(msg);
	}
	return false;
}



</script> 
<div class="akeeba-bootstrap">
<form action="<?php echo $vars->url; ?>" name="adminForm" id="adminForm" onSubmit="return myValidate(this);" class="form-validate form-horizontal"  method="post">
	<div>
		<div class="control-group">
			<label for="cardfname" class="control-label"><?php  echo JText::_( 'Order Information' );?></label>
			<div class="controls">	<?php  echo JText::sprintf( 'ORDER_INFO', $vars->custom_name);?></div>
		</div>
		<div class="control-group">
			<label for="cardlname" class="control-label"><?php echo JText::_( 'COMMENT' ); ?></label>
			<div class="controls"><textarea id='comment' name='comment' class="inputbox required" rows='3' maxlength='135' size='28'></textarea></div>
		</div>
		<div class="control-group">
			<label for="cardaddress1" class="control-label"><?php echo JText::_( 'CON_PAY_PRO' ) ?></label>
			<div class="controls"><?php  echo $email;?>
				<input type='hidden' name='mail_addr' value="<?php echo $email;?>" />
			</div>
		</div>
			<div class="form-actions">
					<input type='hidden' name='order_id' value="<?php echo $vars->order_id;?>" />
					<input type='hidden' name="total" value="<?php echo sprintf('%02.2f',$vars->amount) ?>" />
					<input type="hidden" name="user_id" size="10" value="<?php echo $vars->user_id;?>" />
					<input type='hidden' name='return' value="<?php echo $vars->return;?>" >
					<input type="hidden" name="plugin_payment_method" value="onsite" />
					<input type='submit' name='btn_check' id='btn_check' class="btn btn-success btn-large"  value="<?php echo JText::_('SUBMIT'); ?>">
				</div>

	</div>
</form>
</div>
