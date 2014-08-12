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

$Itemid = JRequest::getInt("Itemid", 0);

?>

<script type="text/javascript">
	function ChangeLogOption(value){
		if(value == 0){
			document.getElementById("log_form").style.display = "block";
			document.getElementById("reg_form").style.display = "none";
			document.getElementById("continue_button").style.display = "none";
		}
		else if(value == 1){
			document.getElementById("log_form").style.display = "none";
			document.getElementById("reg_form").style.display = "block";
			document.getElementById("continue_button").style.display = "block";
		}
	}
</script>

<div class="digistore" style="<?php echo $this->configs->shopping_cart_style ? 'width: 70%;margin-left: auto;margin-right: auto;padding: 20px;background: #fff;' : ''; ?>">

<?php if($this->configs->shopping_cart_style) { 
		echo '<a href="' . JURI::root() . '">
			<img src="' . JURI::root() . 'images/stories/digistore/store_logo/' . trim($this->configs->store_logo) . '" alt="store_logo" border="0">
		</a>';
 } ?>

<?php
	if($this->configs->show_steps == 0){
?>
		<div class="bar">
			<span class="inactive-step">
			<?php
				echo JText::_("DIGI_STEP_ONE");
			?>
			</span>
	
			<span class="active-step">
			<?php
				echo JText::_("DIGI_STEP_TWO");
			?>
			</span>
			<span class="inactive-step">
			<?php
				echo JText::_("DIGI_STEP_THREE");
			?>
			</span>
 		</div>
<?php
	}
?>

<h1><?php echo JText::_("DSREGORLOG");?></h1>

<?php
	$checked = "";
	$display = "none";
	$display1 = "block";
	$login_register_invalid = isset($_SESSION["login_register_invalid"])?$_SESSION["login_register_invalid"]:'';
	if(trim($login_register_invalid) == "notok"){
		$checked = ' checked="checked" ';
		$display = "block";
		$display1 = "none";
	}
?>

<?php
if(count(JFactory::getApplication()->getMessageQueue())):
	$message = JFactory::getApplication()->getMessageQueue();
?>
	<div class="">
	<?php echo $message[0]['message']; ?>
	</div>
	<?php
endif;
?>

<input type="radio" name="log_option" value="0" onclick="javascript:ChangeLogOption(this.value);" checked="checked" />&nbsp;&nbsp;<span class="digi_subtitle"><?php echo JText::_("DIGI_LOGIN_BELOW"); ?></span><br/>
<div id="log_form" style="display:<?php echo $display1; ?>;">
	<form name="login" id="login" method="post" action="index.php">
		<table width="100%" style="border-collapse:separate !important;">
			<tr>
				<td class="field-login"><?php echo JText::_("DSUSERNAME");?>:
					<input type="text" size="30" class="digi_textbox" id="username" name="username"  />
				</td>
			</tr>
			<tr>
				<td class="field-login"><?php echo JText::_("DSPASS");?>:
					<?php $link = JRoute::_("index.php?option=com_users&view=reset"); ?>
					<input type="password" size="30" class="digi_textbox" id="passwd" name="passwd" /> (<a href="<?php echo $link;?>"><?php echo JText::_("DIGI_PROFILE_FRG_PSW");?></a>)
				</td>
			</tr>
			<tr>
				<td>
					<input type="checkbox" value="1" name="rememeber"> <span class="general_text_larger"><?php echo JText::_("DIGI_PROFILE_REMEMBER_ME");?></span>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<button type="submit" name="submit" class="btn btn-inverse">Login <i class="ico-chevron-right ico-white"></i></button>
				</td>
			</tr>
		</table>

		<input type="hidden" name="Itemid" value="<?php echo $Itemid;?>" />
		<input type="hidden" name="option" value="com_digistore" />
		<input type="hidden" name="controller" value="digistoreProfile" />
		<input type="hidden" name="task" value="logCustomerIn" />
		<input type="hidden" name="processor" value="<?php echo JRequest::getVar("processor", ""); ?>" />
		<input type="hidden" name="returnpage" value="<?php echo JRequest::getVar("returnpage", ""); ?>" />
	</form>
</div>

<input type="radio" name="log_option" value="1" onclick="javascript:ChangeLogOption(this.value);" <?php echo $checked; ?> />&nbsp;&nbsp;<span class="digi_subtitle"><?php echo JText::_("DIGI_REGISTER_BELOW"); ?></span><br/>
<div id="reg_form" style="display:<?php echo $display; ?>;">
	<form name="adminForm" id="adminForm" method="post" action="index.php" onsubmit="return validateForm();" >
		<table style="border-collapse:separate !important;">
	<?php
			require_once( JPATH_COMPONENT.DS.'helpers'.DS.'sajax.php' );
			require_once(JPATH_SITE.DS."components".DS."com_digistore".DS."views".DS."digistoreprofile".DS."tmpl".DS."cart_editform.php"); 
	?>
		</table>

		<input type="hidden" name="Itemid" value="<?php echo $Itemid;?>" />
		<input type="hidden" value="com_digistore" name="option">
		<input type="hidden" value="saveCustomer" name="task">
		<input type="hidden" name="processor" value="<?php echo JRequest::getVar("processor", ""); ?>" />
		<input type="hidden" name="returnpage" value="<?php echo JRequest::getVar("returnpage", ""); ?>" />
		<input type="hidden" value="digistoreProfile" name="controller">
		<table width="100%">
			<tr>
				<td align="left">
					<button id="continue_button" type="submit" name="submit" class="btn btn-success"><?php echo JText::_("DSSAVEPROFILE"); ?> <i class="ico-chevron-right ico-white"></i></button>
				</td>
			</tr>
		</table>
	</form>
</div>

</div>
<!--	<input type="button" class="digistore_cancel" value="<?php //echo JText::_("DIGI_BACK"); ?>" onclick="javascript:back();" /> -->
