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

$cust = $this->cust;
$user = $this->user["0"];

$document = JFactory::getDocument();
$document->addStyleSheet("components/com_digistore/assets/css/digistore.css");

$db = JFactory::getDBO();
$sql = "select `askforship` from #__digistore_settings";
$db->setQuery($sql);
$db->query();
$askforship = $db->loadResult();
?>

<script language="javascript" type="text/javascript">
	<!--
	var request_processed = 0;
	function submitbutton(pressbutton) {
		submitform( pressbutton );
	}

	function populateShipping () {
		var names = Array ('address','zipcode', 'city');
		var i;
		for (i = 0; i < names.length; i++) {
			val = document.getElementById(names[i]).value;
			document.getElementById('ship' + names[i]).value = val;
		}
		idx = document.getElementById('country').selectedIndex;
		document.getElementById('shipcountry').selectedIndex = idx;

		changeProvince_ship();
		request_processed = 1;
	}

	-->
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'CUSTOMER_DETAILS' ); ?></legend>
		<table class="admintable">

			<tr>
				<td width="250">
					<?php echo JText::_( 'VIEWCUSTOMERUSERNAME' ); ?><span class="error">*</span>
				</td>

				<td width="69%"><input name="username" <?php if(isset($user) && isset($user["id"])){ ?> disabled <?php } ?> type="text" id="username" size="30" value="<?php echo $user["username"]; ?>"><b>&nbsp;</b></td>

			</tr>
			<?php 
				if(!isset($user) && !$user["id"]){
 			?>
				<tr>
					<td ><?php echo JText::_( "VIEWCUSTOMERPASSWORD" ); ?><span class="error">*</span></td>
					<td><input name="password" type="password" id="password" size="30" ><b>&nbsp;</b></td>
				</tr>
				<tr>
					<td><?php echo JText::_( "VIEWCUSTOMERPASSWORDCONFIRM" ); ?><span class="error">*</span></td>
					<td><input name="password_confirm" type="password" id="password_confirm" size="30"><b>&nbsp;</b></td>
				</tr>
			<?php 
				}
			?>
			<tr>
				<td width="31%"><?php echo JText::_( "VIEWCUSTOMERFIRSTNAME" ); ?><span class="error">*</span></td>
				<td width="69%"><input name="firstname" type="text" id="firstname" size="30" value="<?php echo $cust->firstname ?>"><b>&nbsp;</b></td>
			</tr>
			<tr>
				<td><?php echo JText::_( "VIEWCUSTOMERLASTNAME" ); ?><span class="error">*</span></td>
				<td><input name="lastname" type="text" id="lastname" size="30" value="<?php echo $cust->lastname; ?>"><b>&nbsp;</b></td>
			</tr>
			<tr>
				<td><?php echo JText::_( "VIEWCUSTOMERCOMPANY" ); ?><b></b></td>
				<td><input name="company" type="text" id="company" size="30" value="<?php echo $cust->company; ?>"></td>
			</tr>
			<tr>
				<td width="31%">
					<?php 
						echo JText::_( 'VIEWCUSTOMEREMAIL' );
					?>
					<span class="error">*</span>
				</td>
				<td width="69%">
					<input name="email" type="text" <?php if(isset($user) && isset($user["id"])){ ?> disabled <?php } ?>
									   id="email" size="30" value="<?php echo $user["email"]; ?>"><b>&nbsp;</b>
				</td>
			</tr>

			<tr>
				<td><?php echo JText::_( "VIEWCUSTOMERPOC" ); ?><span class="error">*</span></td>
				<td>
					<select name="person" >
						<option value="1" <?php echo (($cust->person != 0) ? "selected" : ""); ?> ><?php echo JText::_( "VIEWCUSTOMERIMPERSON" ); ?></option>
						<option value="0" <?php echo (($cust->person == 0) ? "selected" : ""); ?>><?php echo JText::_( "VIEWCUSTOMERIMCOMPANY" ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td><?php echo JText::_( "VIEWCUSTOMERTAXNUM" ); ?><span class="error">*</span></td>
				<td><input name="taxnum" type="text" id="taxnum" size="30" value="<?php
					echo $cust->taxnum;
//echo ( $cust->taxnum > 0 )? $cust->taxnum : "";


?>"><b>&nbsp;</b></td>
			</tr>
		</table>
		<br>
		<table width="100%"  border="0">
			<tr>
				<td colspan="2"><h2><?php echo JText::_( "VIEWCUSTOMERBILLING" ); ?></h2></td>
			</tr>
			<tr>
				<td width="250"><?php echo Jtext::_( "VIEWCONFIGADDRESS" ); ?><span class="error">*</span></td>
				<td width="69%"><input name="address" type="text" id="address" size="30" value="<?php echo $cust->address; ?>"><b>&nbsp;</b></td>
			</tr>
			<script>
<?php
					sajax_show_javascript();


?>

				function changeProvince_cb(province_option) {
					//alert(province_option+'MYYYYYYYYYYYY');

					document.getElementById("province").innerHTML = province_option;
				}

				function changeProvince() {
					// get the folder name
					var country;
					country = document.getElementById('country').value;
					//alert(country);
					x_phpchangeProvince(country, 'main', changeProvince_cb);
				}
				var request_processed = 0;
				function changeProvince_cb_ship(province_option) {
					//alert(province_option+'MYYYYYYYYYYYY');

					document.getElementById("shipprovince").innerHTML = province_option;
					if (request_processed == 1) {
						idx = document.getElementById('sel_province').selectedIndex;
						document.getElementById('shipsel_province').selectedIndex = idx;
					}
					request_processed = 0;
				}

				function changeProvince_ship() {
					// get the folder name
					var country;
					country = document.getElementById('shipcountry').value;
					//alert(country);
					x_phpchangeProvince(country, 'ship', changeProvince_cb_ship);
				}
			</script>

			<tr>
				<td><?php echo JText::_( "VIEWCUSTOMERCOUNTRY" ); ?><span class="error">*</span></td>
				<td><?php echo $this->lists['country_option']; ?></td>
			</tr>
			<tr>
				<td><?php echo JText::_( "VIEWCUSTOMERSTATE" ); ?><span class="error">*</span></td>
				<td> <?php
					echo $this->lists['customerlocation'];


?>
				</td>
			</tr>
			<tr>
				<td><?php echo JText::_( "VIEWCUSTOMERCITY" ); ?><span class="error">*</span></td>
				<td>
					<div >
						<input id="city" type="text" value="<?php echo $cust->city; ?>" name="city" size="40" />
					</div>
				</td>
			</tr>

			<tr>
				<td><?php echo JText::_( "VIEWCUSTOMERZIP" ); ?><span class="error">*</span></td>
				<td><input name="zipcode" type="text" id="zipcode" size="30" value="<?php echo $cust->zipcode; ?>"><b>&nbsp;</b></td>
			</tr>
			<tr>
				<td></td>
				<td>&nbsp;</td>
			</tr>
			<?php
				if($askforship == 1){
			?>
				<tr>
					<td colspan="2"><h2><?php echo JText::_( "VIEWCUSTOMERSHIPADDR" ); ?></h2><br />
						<input type="checkbox" name="same_as_bil" onclick="populateShipping();" /><?php echo JText::_( "VIEWCUSTOMERSAMEASBILLING" ); ?></td>
				</tr>
				<tr>
					<td><?php echo JText::_( "VIEWCUSTOMERSCOUNTRY" ); ?><span class="error">*</span></td>
					<td><?php echo $this->lists['shipcountry_options']; ?></td>
				</tr>
				<tr>
					<td><?php echo JText::_( "VIEWCUSTOMERSSTATE" ); ?><span class="error">*</span></td>
					<td><?php
						echo $this->lists['customershippinglocation'];
					?></td>
				</tr>
				<tr>
					<td><?php echo JText::_( "VIEWCUSTOMERSCITY" ); ?><span class="error">*</span></td>
					<td>
						<div >
							<input id="shipcity" type="text" value="<?php echo $cust->shipcity; ?>" name="shipcity" size="40" />
						</div>
					</td>
				</tr>
				<tr>
					<td width="250"><?php echo JText::_( "VIEWCUSTOMERSADRESS" ); ?><span class="error">*</span></td>
					<td width="69%"><input name="shipaddress" type="text" id="shipaddress" size="30" value="<?php echo $cust->shipaddress; ?>"><b>&nbsp;</b></td>
				</tr>
				<tr>
					<td><?php echo JText::_( "VIEWCUSTOMERSZIP" ); ?><span class="error">*</span></td>
					<td><input name="shipzipcode" type="text" id="shipzipcode" size="30" value="<?php echo $cust->shipzipcode; ?>"><b>&nbsp;</b></td>
				</tr>
				<tr>
					<td></td>
					<td>&nbsp;</td>
				</tr>

				<tr>
					<td><?php echo JText::_( "VIEWCUSTOMERTAXCLASS" ); ?><span class="error">*</span></td>
					<td>
					<?php
						echo $this->lists['customer_class'];
					?>
					</td>
				</tr>
			<?php
			}
			?>
		</table>

	</fieldset>

	<input type="hidden" name="images" value="" />
	<input type="hidden" name="option" value="com_digistore" />
	<input type="hidden" name="id" value="<?php echo $user["id"]; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="controller" value="digistoreCustomers" />
	<input type="hidden" name="keyword" value="<?php echo $this->keyword; ?>" />
</form>