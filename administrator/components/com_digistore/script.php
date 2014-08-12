<?php
/**
  DigiStore component
 * http://foob.la/ds
 *
 * @copyright  (C) 2006-2012 Components Lab, Lda.
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 *
 */

defined('_JEXEC') or die('Direct Access to this location is not allowed.');

defined ('DS') or define('DS', DIRECTORY_SEPARATOR);
/**
  Script file of DigiStore component
 */
class com_DigistoreInstallerScript
{

	private $installation_queue = array (
		// plugins => { (folder) => { (element) => (published) }* }*
		'plugins'=>array(
			'system'=>array(
				'jgive_api'=>1
			),
			'community'=>array(
				'jgive'=>0
			),
			'payment'=>array(
				'2checkout'=>0,
				'alphauserpoints'=>0,
				'authorizenet'=>0,
				'bycheck'=>0,
				'byorder'=>0,
				'ccavenue'=>0,
				'jomsocialpoints'=>0,
				'linkpoint'=>0,
				'paypal'=>1,
				'paypalpro'=>0,
				'payu'=>0
			)
		)
	);

	/**
	 *
	 * method to run before an install/update/unistall method
	 *
	 * return void
	 *
	 */
	function preflight($type, $parent)
	{
	}

	/**
	 *
	 * method to install the component
	 *
	 * return void
	 *
	 */
	function install($parent)
	{
		$db = JFactory::getDBO();

		// set Defailt Billing Address
		$sql = "UPDATE `#__digistore_settings` SET `tax_base` = '1' WHERE `id` = 1;";
		$db->setQuery($sql);
		$db->query();

		$template = '
			<table width="100%"  border="0" cellspacing="0" cellpadding="5">
				<tr valign="top">
					<td width="20%">{image} </td>
					<td width="52%">
						<table width="100%"  border="0" cellspacing="0" cellpadding="5">
							<tr valign="top">
								<td><b>Name: </b></td>
								<td>{name} </td>
							</tr>
				
							<tr valign="top">
								<td><b>Price:</b></td>
								<td>{price} </td>
							</tr>
							<tr valign="top">
								<td><b>Quantity:</b></td>
								<td>{qty}</td>
					
							</tr>
							<tr valign="top">
								<td><b>Short description:</b></td>
								<td>{short_description}</td>
							  </tr>
							<tr valign="top">
								<td><b>Full description:</b></td>
								<td>{full_description}</td>
							</tr>
					
							<tr valign="top">
								<td><b>Fields</b></td>
								<td>{fields}</td>
					
							</tr>
							<tr valign="top">
								<td>{addtocart}</td>
								<td>&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		';
		$mosConfig_mailfrom = "";
		$thankshtml = "Thank you for your order! We\'ve just sent you a confirmation email with your order details. Please download your product below and thank you for shopping at our store";
		$ftranshtml = "We are sorry, but it looks like your transaction failed. If you are sure that it is our fault and not one of choosen payment system, please feel free to <a href=\'mailto:".$mosConfig_mailfrom."\'>contact</a> our site\'s administrator.";
		$pendinghtml = "Thank you for submitting your order. We will charge you off line and you will get an email when your order is confirmed. ";

		$sql = "SELECT COUNT(*) FROM #__digistore_settings";
		$db->setQuery($sql);
		$num = $db->loadColumn();
		if ($num < 1) {
			$config = JFactory::getConfig();
			$sql = "
			INSERT IGNORE INTO `#__digistore_settings`
				(`id`, `currency`, `store_name`, `store_url`, `store_email`, `product_per_page`, `google_account`, 
				`country`, `state`, `city`, `tax_option`, `tax_rate`, `tax_type`, `totaldigits`, `decimaldigits`, 
				`ftp_source_path`, `time_format`, `afteradditem`, `showreplic`, `idevaff`, 
				`askterms`, `termsid`, `termsheight`, `termswidth`, `topcountries`, `usestoremail`, 
				`catlayoutstyle`, `catlayoutcol`, `catlayoutrow`, 
				`prodlayouttype`, `prodlayoutstyle`, `prodlayoutcol`, `prodlayoutrow`, 
				`orderidvar`, `ordersubtotalvar`, `idevpath`, `askforship`, `person`, `taxnum`, 
				`modbuynow`, `usecimg`, `showthumb`, `showsku`, `sendmailtoadmin`, `directfilelink`, 
				`debugstore`, `dumptofile`, `dumpvars`, `ftranshtml`, `thankshtml`, `showprodshort`, 
				`pendinghtml`, `address`, `zip`, `phone`, `fax`, `afterpurchase`, `showoid`, 
				`showoipurch`, `showolics`, `showopaid`, `showodate`, `showorec`, `showlid`, 
				`showlprod`, `showloid`, `showldate`, `showldown`, `showcam`, `showcpromo`, 
				`showcremove`, `showccont`, `showldomain`, 
				`tax_classes`, `tax_base`, `tax_catalog`, `tax_shipping`, `tax_discount`, `discount_tax`, 
				`tax_country`, `tax_state`, `tax_zip`, `tax_price`, `tax_summary`, `shipping_price`, 
				`product_price`, `tax_zero`, `tax_apply`, `usestorelocation`, `allowcustomerchoseclass`, 
				`takecheckout`, `continue_shopping_url`, `currency_position`, `showlterms`, `showlexpires`, 
				`storedesc`, `displaystoredesc`, `showfeatured`, `showrelated`, `hour24format`, 
				`imagecatsizevalue`, `imagecatsizetype`, `imageprodsizefullvalue`, `imageprodsizefulltype`, 
				`imageprodsizethumbvalue`, `imageprodsizethumbtype`, `imagecatdescvalue`, `imagecatdesctype`, 
				`imageproddescvalue`, `imageproddesctype`, `mailchimplistid`, `showpowered`, 
				`catlayoutimagesize`, `catlayoutimagetype`, `catlayoutdesclength`, `catlayoutdesctype`, 
				`prodlayoutdesclength`, `prodlayoutdesctype`, `showfeatured_prod`, `prodlayoutthumbnails`, 
				`prodlayoutthumbnailstype`, `prodlayoutlargeimgprev`, `prodlayoutlargeimgprevtype`, 
				`prodlayoutlightimgprev`, `prodlayoutlightimgprevtype`, `showshortdescription`, 
				`showlongdescription`, `showrelatedprod`)
			VALUES
				(1, 'USD', 'My Store Name', '".JURI::root()."', '".$config->get('mailfrom')."', 10, '', '', '', '', '', 0, '', 5, 2, 
				'media', 'MM-DD-YYYY', 2, 0, 'standalone', 0, 0, -1, -1, 'Canada,United-States', 1, 
				1, 3, 5,
				0, 1, 3, 10,
				'order_id', 'order_subtotal', '/aff', 0, 1, 0, 1, 0, 1, 1, 0, 0, 0, 0, '',
				'<p>We are sorry, but it looks like your transaction failed. It''s possible that our server didn''t receive the payment notification back from PayPal or Authorize.</p>\r\n<p>If you were charged, but no license was added to your account, please contact us and we will add a license to your account ASAP.</p>',
				'<p>Thank you for your order! We''ve just sent you a confirmation email with your order details. Please download your product below and thank you for shopping at our store.</p>\r\n<p><strong><span style=\"color: #ff0000\">NOTE:</span></strong> If you can''t see your license below, please wait 5 minutes and refresh the page. If you still can''t see it, please contact us, we will add the license to your account ASAP.</p>', 
				0, 
				'<p>Thank you for submitting your order. We will charge you off line and you will get an email when your order is confirmed.</p>',
				'', '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 0, 0, 1, 0, 0, 1, 0,
				'United-States', 'All', '', 1, 1, 1, 1, 1, '0', 0, 2, 2, '', 0, 0, 0, 'Welcome to our store', 
				1, 0, 0, 0, 100, 0, 800, 1, 65, 0, 100, 1, 20,
				1, '2', 1, 100, 0, 20, 1, 20, 0, 1, 50, 1, 200, 0, 600, 0, 1, 1, 1);
			";

			$db->setQuery($sql);
			$db->query();
		}

		$sql = "SELECT pendinghtml FROM #__digistore_settings";
		$db->setQuery($sql);
		$ph = $db->loadResult();
		if (strlen(trim($ph)) < 1) {
			$sql = "UPDATE #__digistore_settings SET `pendinghtml`='".$pendinghtml."'";
			$db->setQuery($sql);
			$db->query();
		}


		$sql = "SELECT COUNT(*) FROM #__digistore_languages";
		$db->setQuery($sql);
		$lang_count = $db->loadColumn();
		if(intval($lang_count) <= 0){
			$code = "en-GB";
			$lang_file = "en-GB.com_digistore.ini";
			$sql = "INSERT INTO #__digistore_languages(`name`, `fefilename`, `befilename`) VALUES ('".$code."', '".$lang_file."', '".$lang_file."')";
			$db->setQuery($sql);
			$db->query();
		}

		$sql = "SELECT COUNT(*) FROM #__digistore_mailtemplates WHERE type='register'";
		$db->setQuery ($sql);
		$mailtemplates_count = $db->loadColumn();
		if ($mailtemplates_count == 0) {
			$confirmail = str_replace ("\n", "<br />", "Dear [CUSTOMER_FIRST_NAME],

						Thanks for registering at the [SITENAME] store! The next time you visit, use the following username and password to log in. You only have to register once. You can now also access all the features of [SITENAME] using the information below.

						Username: [CUSTOMER_USER_NAME]
						Password: [CUSTOMER_PASSWORD]

						Once again, thanks for registering at [SITENAME]. We hope to see you often.

						Sincerely,
						[SITENAME]
						[SITEURL]
						");
			//echo ("mail template issue");
			$sql = "INSERT INTO `#__digistore_mailtemplates` ( `id` , `type` , `subject`, `body` )
				VALUES (
 					1, 'register', 'Registration details', '".$confirmail."'
				);";
			$db->setQuery ($sql);
			$db->query();
		}

		$query = "SELECT COUNT(*) FROM #__digistore_mailtemplates WHERE type='order'";
		$db->setQuery ($query);
		$mailtemplates_count = $db->loadColumn();
		if ($mailtemplates_count == 0) {
			$ordermail = str_replace ("\n", "<br />","Dear [CUSTOMER_FIRST_NAME]

					Thank you for purchasing from [SITENAME]. We hope you'll enjoy our products.

					[ORDER_AMOUNT] has been charged to the account you used to make your purchase. Your order confirmation number is [ORDER_ID].

					Visit [SITEURL] often to check out new products and get the latest news. Remember, your username and password get you access to all the features of [SITENAME].

					Once again, thanks for buying from [SITENAME]. We value your business, and we hope to see you again soon.

					Best Regards,


					[SITENAME]
					[SITEURL] ");

			$sql = "INSERT INTO `#__digistore_mailtemplates` ( `id` , `type` , `subject`, `body` )
				VALUES (
 					2, 'order', 'Order details',  '".mysql_escape_string($ordermail)."'
				);";

			$db->setQuery ($sql);
			$db->query();
		}

		$query = "SELECT COUNT(*) FROM #__digistore_mailtemplates WHERE type='approved'";
		$db->setQuery ($query);
		$mailtemplates_count = $db->loadColumn();
		if ($mailtemplates_count == 0) {
			$approvedmail = str_replace ("\n", "<br />","We've received your order and your products are on their way ");

			$sql = "INSERT INTO `#__digistore_mailtemplates` ( `id` , `type` , `subject`, `body` )
				VALUES (
 					3, 'approved', 'Your order has been approved',  '".mysql_escape_string($approvedmail)."'
				);";

			$db->setQuery ($sql);
			$db->query();
		}

		jimport('joomla.filesystem.archive');
		$fe_path = JPATH_SITE.DS."components".DS."com_digistore".DS;
		//extract ioncube
		$be_path = JPATH_SITE.DS."administrator".DS."components".DS."com_digistore".DS; //dirname(__FILE__)."/administrator/components/com_digistore/";

// 		$licenses_updated = self::give_orders_to_licenses();
// 		if ($licenses_updated > 0){
// 			echo $licenses_updated." licenses were assigned orders.";
// 		}

		// self::updateAllTables2_0_0();
		//createDigistoreMenu();
		// self::installDigistorePlugins();

		echo '
			<img src="'.JURI::root().'administrator/components/com_digistore/assets/images/logo.png"><br />
			<strong>Thanks for installing DigiStore!</strong>
			<div>
				<strong>For sample data:</strong>
				<ul>
					<li>Click to install <a href="http://foobla.com/joomla/digistore/downloads/download/9_6f0f5a256ce478c1fd14b765f862d084?ebooks.zip" onclick="installFromUrl(this.href); return false;" />Ebook</a></li>
					<li>Click to install  <a href="http://foobla.com/joomla/digistore/downloads/download/10_882c177d643950b952b2052c745a35b9?vm.zip" onclick="installFromUrl(this.href); return false;" />Virtuemart data</a></li>
				</ul>
			</div>
			<script>
				function installFromUrl(url){
					document.getElementById("install_url").value=url;
					Joomla.submitbutton4();
				}
			</script>
		';

		$db = JFactory::getDBO();
		$sql = "SELECT COUNT(*) FROM #__digistore_categories";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadColumn();
		if($result == 0){
			echo "<br/><br/><br/>";
			echo 	'<span style="font-size:18px; color:red;">Would you like to start selling awesome eBooks today? If yes, we will set up our default ebooks with the stores. You\'d have to still download the ebooks and upload them via FTP.
				</span>
				<br><br/>
				<span style="font-size:14px">
					Click <a href=index.php?option=com_digistore&controller=digistoreConfigs&task=install>here</a> to continue with eBooks included
					<br>
					Click <a href=index.php?option=com_digistore>here</a> to continue without eBooks</td>
				</span>';
		}
	}

	/**
	 *
	 * method to uninstall the component
	 *
	 * return void
	 *
	 */
	function uninstall($parent)
	{
		self::_uninstallModules();
		self::_uninstallPlugins();
		self::_uninstallPayments();
	}

	/**
	 *
	 * method to update the component
	 *
	 * return void
	 *
	 */
	function update($parent)
	{
		// self::updateAllTables2_0_0();
	}

	/**
	 *
	 * method to run after an install/update/unistall method
	 *
	 * return void
	 *
	 */
	function postflight($type, $parent)
	{
		if( $type == 'update' ) {
			self::updateAllTables2_0_0();
		}
		self::_installPlugins($type,$parent);
		self::_installModules($type,$parent);
	}


	function updateAllTables2_0_0()
	{
		$db = JFactory::getDBO();
		$sqlfile = dirname(__FILE__).DS.'admin'.DS.'sql'.DS.'update.sql';
		self::executeSqlFile( $sqlfile );
		$sql = "CREATE TABLE IF NOT EXISTS `#__digistore_product_groups` (`id_product` INT( 11 ) NOT NULL ,`id_group` INT( 11 ) NOT NULL ,PRIMARY KEY (  `id_product` ,  `id_group` ));";
		$db->setQuery($sql);
		$db->query();

		$sql = "CREATE TABLE IF NOT EXISTS `#__digistore_product_groups_exp` (`id_product` INT( 11 ) NOT NULL ,`id_group` INT( 11 ) NOT NULL ,PRIMARY KEY (  `id_product` ,  `id_group` ));";
		$db->setQuery($sql);
		$db->query();

		$sql = "SHOW COLUMNS FROM #__digistore_cart";
		$db->setQuery($sql);
		$result = $db->loadColumn();
		if(!in_array("plan_id", $result)){
			$sql = "ALTER TABLE `#__digistore_cart` ADD `plan_id` INT(11) NOT NULL DEFAULT '0'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("plugin_id", $result)){
			$sql = "ALTER TABLE `#__digistore_cart` ADD `plugin_id` INT(11) NOT NULL DEFAULT '0'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("renew", $result)){
			$sql = "ALTER TABLE `#__digistore_cart` ADD `renew` INT(11) NOT NULL DEFAULT '0'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("renewlicid", $result)){
			$sql = "ALTER TABLE `#__digistore_cart` ADD `renewlicid` INT(11) NOT NULL DEFAULT '0'";
			$db->setQuery($sql);
			$db->query();
		}
		//------------------------------------------------------------------------------------------
		$sql = "SHOW COLUMNS FROM #__digistore_categories";
		$db->setQuery($sql);
		$result = $db->loadColumn();
		if(!in_array("thumb", $result)){
			$sql = "ALTER TABLE `#__digistore_categories` ADD `thumb` VARCHAR(255) NOT NULL";
			$db->setQuery($sql);
			$db->query();
		}
		//------------------------------------------------------------------------------------------
		$sql = "SHOW COLUMNS FROM #__digistore_emailreminders";
		$db->setQuery($sql);
		$result = $db->loadColumn();
		if(!in_array("period", $result)){
			$sql = "ALTER TABLE  `#__digistore_emailreminders` ADD `period` VARCHAR( 5 ) NULL ,
					ADD `calc` VARCHAR( 6 ) NULL ,
					ADD `date_calc` VARCHAR( 10 ) NULL";
			$db->setQuery($sql);
			$db->query();
		}
		//------------------------------------------------------------------------------------------
		$sql = "SHOW COLUMNS FROM #__digistore_featuredproducts";
		$db->setQuery($sql);
		$result = $db->loadColumn();
		if(!in_array("planid", $result)){
			$sql = "ALTER TABLE `#__digistore_featuredproducts` ADD `planid` INT(11) NOT NULL DEFAULT '0'";
			$db->setQuery($sql);
			$db->query();
		}
		//------------------------------------------------------------------------------------------
		$sql = "SHOW COLUMNS FROM #__digistore_licenses";
		$db->setQuery($sql);
		$result = $db->loadColumn();
		if(in_array("email", $result)){
			$sql = "ALTER TABLE #__digistore_licenses DROP COLUMN `email`";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("package_id", $result)){
			$sql = "ALTER TABLE `#__digistore_licenses` ADD `package_id` int(11) NOT NULL";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("purchase_date", $result)){
			$sql = "ALTER TABLE `#__digistore_licenses` ADD `purchase_date` datetime NOT NULL default '0000-00-00 00:00:00'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("expires", $result)){
			$sql = "ALTER TABLE `#__digistore_licenses` ADD `expires` datetime NOT NULL default '0000-00-00 00:00:00'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("renew", $result)){
			$sql = "ALTER TABLE `#__digistore_licenses` ADD `renew` int(11) NOT NULL default '0'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("renewlicid", $result)){
			$sql = "ALTER TABLE `#__digistore_licenses` ADD `renewlicid` int(11) NOT NULL default '0'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("download_count", $result)){
			$sql = "ALTER TABLE `#__digistore_licenses` ADD `download_count` int(11) NOT NULL default '0'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("plan_id", $result)){
			$sql = "ALTER TABLE `#__digistore_licenses` ADD `plan_id` int(11) NOT NULL default '0'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("old_orders", $result)){
			$sql = "ALTER TABLE `#__digistore_licenses` ADD `old_orders` text NOT NULL";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("cancelled", $result)){
			$sql = "ALTER TABLE `#__digistore_licenses` ADD `cancelled` tinyint(1) NOT NULL default '0'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("cancelled_amount", $result)){
			$sql = "ALTER TABLE `#__digistore_licenses` ADD `cancelled_amount` float NOT NULL default '0'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("domain_change", $result)){
			$sql = "ALTER TABLE `#__digistore_licenses` ADD `domain_change` int(11) NOT NULL default '0'";
			$db->setQuery($sql);
			$db->query();
		}

		$sql = "ALTER TABLE `#__digistore_licenses` MODIFY `dev_domain` text NOT NULL";
		$db->setQuery($sql);
		$db->query();

		//------------------------------------------------------------------------------------------
		$sql = "SHOW COLUMNS FROM #__digistore_orders";
		$db->setQuery($sql);
		$result = $db->loadColumn();
		if(in_array("payment_method", $result)){
			$sql = "ALTER TABLE #__digistore_orders DROP COLUMN `payment_method`";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("amount_paid", $result)){
			$sql = "ALTER TABLE `#__digistore_orders` ADD `amount_paid` float NOT NULL default '0'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("processor", $result)){
			$sql = "ALTER TABLE `#__digistore_orders` ADD `processor` varchar(100) NOT NULL";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("processor", $result)){
			$sql = "ALTER TABLE `#__digistore_orders` ADD `published` int(11) NOT NULL default '0'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("chargeback", $result)){
			$sql = "ALTER TABLE `#__digistore_orders` ADD `chargeback` tinyint(1) NOT NULL default '0'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("analytics", $result)){
			$sql = "ALTER TABLE `#__digistore_orders` ADD `analytics` tinyint(1) NOT NULL default '0'";
			$db->setQuery($sql);
			$db->query();
			$sql = "UPDATE `#__digistore_orders` SET `analytics`='1'";
			$db->setQuery($sql);
			$db->query();
		}
		//------------------------------------------------------------------------------------------
		$sql = "SHOW COLUMNS FROM #__digistore_products";
		$db->setQuery($sql);
		$result = $db->loadColumn();
		if(!in_array("sku", $result)){
			$sql = "ALTER TABLE `#__digistore_products` ADD `sku` varchar(100) NOT NULL default ''";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("showqtydropdown", $result)){
			$sql = "ALTER TABLE `#__digistore_products` ADD `showqtydropdown` int(11) NOT NULL default '0'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("priceformat", $result)){
			$sql = "ALTER TABLE `#__digistore_products` ADD `priceformat` int(11) NOT NULL default '1'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("featured", $result)){
			$sql = "ALTER TABLE `#__digistore_products` ADD `featured` int(11) NOT NULL default '0'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("prodimages", $result)){
			$sql = "ALTER TABLE `#__digistore_products` ADD `prodimages` text NOT NULL";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("defprodimage", $result)){
			$sql = "ALTER TABLE `#__digistore_products` ADD `defprodimage` varchar(500) NOT NULL default ''";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("mailchimplistid", $result)){
			$sql = "ALTER TABLE `#__digistore_products` ADD `mailchimplistid` varchar(255) NOT NULL default ''";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("subtitle", $result)){
			$sql = "ALTER TABLE `#__digistore_products` ADD `subtitle` varchar(255) NOT NULL default ''";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("mailchimpapi", $result)){
			$sql = "ALTER TABLE `#__digistore_products` ADD `mailchimpapi` text NOT NULL";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("mailchimplist", $result)){
			$sql = "ALTER TABLE `#__digistore_products` ADD `mailchimplist` text NOT NULL";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("mailchimpregister", $result)){
			$sql = "ALTER TABLE `#__digistore_products` ADD `mailchimpregister` int(3) NOT NULL default '1'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("mailchimpgroupid", $result)){
			$sql = "ALTER TABLE `#__digistore_products` ADD `mailchimpgroupid` text NOT NULL";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("video_url", $result)){
			$sql = "ALTER TABLE `#__digistore_products` ADD `video_url` text NOT NULL";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("video_width", $result)){
			$sql = "ALTER TABLE `#__digistore_products` ADD `video_width` int(11) NOT NULL default '0'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("video_height", $result)){
			$sql = "ALTER TABLE `#__digistore_products` ADD `video_height` int(11) NOT NULL default '0'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("offerplans", $result)){
			$sql = "ALTER TABLE `#__digistore_products` ADD `offerplans` int(3) NOT NULL default '0'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("hide_public", $result)){
			$sql = "ALTER TABLE `#__digistore_products` ADD `hide_public` tinyint(1) NOT NULL default '0'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("cartlinkuse", $result)){
			$sql = "ALTER TABLE `#__digistore_products` ADD `cartlinkuse` int(3) NOT NULL default '0'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("cartlink", $result)){
			$sql = "ALTER TABLE `#__digistore_products` ADD `cartlink` text NOT NULL";
			$db->setQuery($sql);
			$db->query();
		}
		//------------------------------------------------------------------------------------------
		$sql = "SHOW COLUMNS FROM #__digistore_session";
		$db->setQuery($sql);
		$result = $db->loadColumn();
		if(!in_array("processor", $result)){
			$sql = "ALTER TABLE `#__digistore_session` ADD `processor` varchar(250) NOT NULL";
			$db->setQuery($sql);
			$db->query();
		}
		//------------------------------------------------------------------------------------------
		$sql = "SHOW COLUMNS FROM #__digistore_promocodes";
		$db->setQuery($sql);
		$result = $db->loadColumn();
		if(!in_array("validfornew", $result)){
			$sql = "ALTER TABLE `#__digistore_promocodes` ADD `validfornew` int(11) NOT NULL default '0'";
			$db->setQuery($sql);
			$db->query();

			$sql = "UPDATE `#__digistore_promocodes` set `validfornew` = 1";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("validforrenewal", $result)){
			$sql = "ALTER TABLE `#__digistore_promocodes` ADD `validforrenewal` int(11) NOT NULL default '0'";
			$db->setQuery($sql);
			$db->query();
		}

		//------------------------------------------------------------------------------------------
		$sql = "ALTER TABLE `#__digistore_settings` MODIFY `in_trans` INT(10)";
		$db->setQuery($sql);
		$db->query();

		$sql = "SHOW COLUMNS FROM #__digistore_settings";
		$db->setQuery($sql);
		$result = $db->loadColumn();
		if(!in_array("catalogue", $result)){
			$sql = $sql = "ALTER TABLE `#__digistore_settings` ADD COLUMN `catalogue` INT(2) NULL DEFAULT 0  AFTER `currency`";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("showviewproducts", $result)){
			$sql = $sql = "ALTER TABLE `#__digistore_settings` ADD COLUMN `showviewproducts` INT(2) NULL DEFAULT 1  AFTER `catalogue`";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("price_groups_separation", $result)){
			$sql = $sql = "ALTER TABLE `#__digistore_settings` ADD COLUMN `price_groups_separation` VARCHAR(2) NULL DEFAULT ','  AFTER `showviewproducts`";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("showproductdetails", $result)){
			$sql = $sql = "ALTER TABLE `#__digistore_settings` ADD COLUMN `showproductdetails` INT(2) NOT NULL DEFAULT '1' AFTER `price_groups_separation`";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("continue_shopping_url", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `continue_shopping_url` varchar(255) NOT NULL";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("currency_position", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `currency_position` int(1) NOT NULL default '0'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("showlterms", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `showlterms` int(1) NOT NULL default '0'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("showlexpires", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `showlexpires` int(1) NOT NULL default '0'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("storedesc", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `storedesc` mediumtext NOT NULL";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("displaystoredesc", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `displaystoredesc` int(11) NOT NULL default '1'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("showfeatured", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `showfeatured` int(11) NOT NULL default '0'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("showrelated", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `showrelated` int(11) NOT NULL default '0'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("hour24format", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `hour24format` int(11) NOT NULL default '0'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("imagecatsizevalue", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `imagecatsizevalue` int(11) NOT NULL default '100'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("imagecatsizetype", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `imagecatsizetype` int(11) NOT NULL default '1'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("imageprodsizefullvalue", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `imageprodsizefullvalue` int(11) NOT NULL default '300'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("imageprodsizefulltype", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `imageprodsizefulltype` int(11) NOT NULL default '1'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("imageprodsizethumbvalue", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `imageprodsizethumbvalue` int(11) NOT NULL default '65'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("imageprodsizethumbtype", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `imageprodsizethumbtype` int(11) NOT NULL default '1'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("imagecatdescvalue", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `imagecatdescvalue` int(11) NOT NULL default '10'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("imagecatdesctype", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `imagecatdesctype` int(11) NOT NULL default '0'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("imageproddescvalue", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `imageproddescvalue` int(11) NOT NULL default '10'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("imageproddesctype", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `imageproddesctype` int(11) NOT NULL default '0'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("mailchimplistid", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `mailchimplistid` varchar(255) default NULL";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("showpowered", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `showpowered` tinyint(4) NOT NULL default '1'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("catlayoutimagesize", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `catlayoutimagesize` int(10) NOT NULL default '100'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("catlayoutimagetype", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `catlayoutimagetype` int(10) NOT NULL";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("catlayoutdesclength", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `catlayoutdesclength` int(10) NOT NULL default '20'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("catlayoutdesctype", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `catlayoutdesctype` int(10) NOT NULL";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("prodlayoutdesclength", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `prodlayoutdesclength` int(10) NOT NULL default '20'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("prodlayoutdesctype", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `prodlayoutdesctype` int(10) NOT NULL";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("showfeatured_prod", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `showfeatured_prod` int(10) NOT NULL";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("prodlayoutthumbnails", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `prodlayoutthumbnails` int(10) NOT NULL default '50'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("prodlayoutthumbnailstype", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `prodlayoutthumbnailstype` int(10) NOT NULL default '1'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("prodlayoutlargeimgprev", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `prodlayoutlargeimgprev` int(10) NOT NULL default '200'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("prodlayoutlargeimgprevtype", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `prodlayoutlargeimgprevtype` int(10) NOT NULL default '0'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("prodlayoutlightimgprev", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `prodlayoutlightimgprev` int(10) NOT NULL default '600'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("prodlayoutlightimgprevtype", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `prodlayoutlightimgprevtype` int(10) NOT NULL default '0'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("showshortdescription", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `showshortdescription` int(10) NOT NULL default '1'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("showlongdescription", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `showlongdescription` int(10) NOT NULL default '1'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("showrelatedprod", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `showrelatedprod` int(10) NOT NULL default '1'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("last_check_date", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `last_check_date` datetime NOT NULL";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("prodlayoutsort", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `prodlayoutsort` int(10) NOT NULL default '0'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("relatedrows", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `relatedrows` int(10) NOT NULL default '0'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("relatedcolumns", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `relatedcolumns` int(10) NOT NULL default '3'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("grid_image_align", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `grid_image_align` int(10) NOT NULL default '0'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("grid_title_align", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `grid_title_align` int(10) NOT NULL default '2'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("grid_subtitle_align", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `grid_subtitle_align` int(10) NOT NULL default '2'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("grid_description_align", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `grid_description_align` int(10) NOT NULL default '2'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("grid_quantity_align", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `grid_quantity_align` int(10) NOT NULL default '2'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("grid_add_to_cat_align", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `grid_add_to_cat_align` int(10) NOT NULL default '2'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("list_multi_selection", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `list_multi_selection` int(10) NOT NULL default '1'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("list_orientation", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `list_orientation` int(10) NOT NULL default '0'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("featured_row", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `featured_row` int(10) NOT NULL default '1'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("featured_col", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `featured_col` int(10) NOT NULL default '3'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("store_logo", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `store_logo` varchar(255) NOT NULL";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("shopping_cart_style", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `shopping_cart_style` int(3) NOT NULL default '0'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("cart_width", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `cart_width` varchar(255) NOT NULL default '100'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("cart_width_type", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `cart_width_type` int(10) NOT NULL default '1'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("cart_alignment", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `cart_alignment` int(10) NOT NULL default '1'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("prod_short_desc_class", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `prod_short_desc_class` varchar(255) NOT NULL default 'digi_short_desc_page'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("prod_long_desc_class", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `prod_long_desc_class` varchar(255) NOT NULL default 'digi_long_desc_page'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("prods_short_desc_class", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `prods_short_desc_class` varchar(255) NOT NULL default 'digi_short_desc_list'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("prods_price_class", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `prods_price_class` varchar(255) NOT NULL default 'digi_price_list'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("prods_name_class", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `prods_name_class` varchar(255) NOT NULL default 'digi_name_list'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("cart_popoup_image", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `cart_popoup_image` int(10) NOT NULL default '50'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("gallery_style", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `gallery_style` int(3) NOT NULL default '1'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("gallery_columns", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `gallery_columns` int(3) NOT NULL default '3'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("in_trans", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `in_trans` int(3) NOT NULL default '0'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("show_bradcrumbs", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `show_bradcrumbs` int(3) NOT NULL default '0'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("mailchimpapi", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `mailchimpapi` text NOT NULL";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("mailchimplist", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `mailchimplist` text NOT NULL";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("showfacebook", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `showfacebook` int(2) NOT NULL default '1'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("showtwitter", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `showtwitter` int(2) NOT NULL default '1'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("showretwitter", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `showretwitter` int(2) NOT NULL default '1'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("tax_eumode", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `tax_eumode` TINYINT(1) NOT NULL DEFAULT '0'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("askforbilling", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `askforbilling` int(2) NOT NULL default '0'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("show_steps", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `show_steps` int(3) NOT NULL default '0'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("askforcompany", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `askforcompany` int(3) NOT NULL default '0'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("conversion_id", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `conversion_id` varchar(255) NOT NULL default ''";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("conversion_language", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `conversion_language` varchar(255) NOT NULL default 'en'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("conversion_format", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `conversion_format` varchar(255) NOT NULL default '2'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("conversion_color", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `conversion_color` varchar(255) NOT NULL default 'ffffff'";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("conversion_label", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `conversion_label` varchar(255) NOT NULL default ''";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array("default_payment", $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `default_payment` varchar(255) NOT NULL default ''";
			$db->setQuery($sql);
			$db->query();
		}
		if(!in_array('thousands_group_symbol', $result)){
			$sql = "ALTER TABLE `#__digistore_settings` ADD `thousands_group_symbol` varchar(5) NOT NULL default ','";
			$db->setQuery($sql);
			$db->query();
			
		}
		//------------------------------------------------------------------------------------------
		$sql = "SELECT COUNT(*) FROM #__digistore_plans";
		$db->setQuery($sql);
		$db->query();
		$count = $db->loadColumn();
		if($count <= 0){
			$sql = "INSERT INTO `#__digistore_plans` (`id`, `name`, `duration_count`, `duration_type`, `ordering`, `published`) VALUES
					(1, '5 Downloads Access', 5, 0, 6, 1),
					(2, '1 Month Access', 1, 3, 5, 1),
					(3, '3 Months Access', 3, 3, 4, 1),
					(4, '6 Months Access', 6, 3, 3, 1),
					(5, '1 Year Access', 1, 4, 2, 1),
					(6, 'Unlimited Access', -1, 0, 1, 1);";
			$db->setQuery($sql);
			$db->query();
		}
		//------------------------------------------------------------------------------------------
		$sql = "SELECT COUNT(*) FROM #__digistore_emailreminders";
		$db->setQuery($sql);
		$db->query();
		$count = $db->loadColumn();
		if($count <= 0){
			$sql = "INSERT INTO `#__digistore_emailreminders` (`id`, `name`, `type`, `subject`, `body`, `ordering`, `published`) VALUES
					(1, '2 days before expiration', 2, '[CUSTOMER_FIRST_NAME], your subscription to [PRODUCT_NAME] is about to expire', '<p>Dear [CUSTOMER_FIRST_NAME], <br /> <br /> Just a quick note to let you know that your subscription to  [PRODUCT_NAME], with license number [LICENSE_NUMBER], will expire in 2  days ([EXPIRE_DATE]). Please visit [SITENAME] and renew your  subscription here: <br /> <br /> [RENEW_URL]  <br /> <br /> Your username to login is: [CUSTOMER_USER_NAME] and your email in case your''ve forgotten your password is: [CUSTOMER_EMAIL]  <br /> <br /> We offer the following renewal plans: <br /> <br /> [RENEW_TERM] 	 <br /> <br /> You can always renew your subscription or view your licenses here: <br /> <br /> [MY_LICENSES] <br /> <br /> Thank you, <br /> [SITENAME] <br /> Admin</p>', 3, 1),
					(2, 'On expiration', 0, '[CUSTOMER_FIRST_NAME], your subscription to [PRODUCT_NAME] has expired', '<p>Dear [CUSTOMER_FIRST_NAME], <br /> <br /> Just a quick note to let you know that your subscription to  [PRODUCT_NAME], with license number [LICENSE_NUMBER], has expired on  ([EXPIRE_DATE]). Please visit [SITENAME] and renew your subscription  here: <br /> <br /> [RENEW_URL]  <br /> <br /> Your username to login is: [CUSTOMER_USER_NAME] and your email in case your''ve forgotten your password is: [CUSTOMER_EMAIL]  <br /> <br /> We offer the following renewal plans: <br /> <br /> [RENEW_TERM] 	 <br /> <br /> You can always renew your subscription or view your licenses here: <br /> <br /> [MY_LICENSES] <br /> <br /> Thank you, <br /> [SITENAME] <br /> Admin</p>', 2, 1),
					(3, '2 days after expiration', 7, '[CUSTOMER_FIRST_NAME], your subscription to [PRODUCT_NAME] is about to expire', '<p>Dear [CUSTOMER_FIRST_NAME], <br /> <br /> Just a quick note to let you know that your subscription to  [PRODUCT_NAME], with license number [LICENSE_NUMBER], has expired on  ([EXPIRE_DATE]). Please visit [SITENAME] and renew your subscription  here: <br /> <br /> [RENEW_URL]  <br /> <br /> Your username to login is: [CUSTOMER_USER_NAME] and your email in case your''ve forgotten your password is: [CUSTOMER_EMAIL]  <br /> <br /> We offer the following renewal plans: <br /> <br /> [RENEW_TERM] 	 <br /> <br /> You can always renew your subscription or view your licenses here: <br /> <br /> [MY_LICENSES] <br /> <br /> Thank you, <br /> [SITENAME] <br /> Admin</p>', 1, 1);";
			$db->setQuery($sql);
			$db->query();
		}
		//------------------------------------------------------------------------------------------
		// @TODO: Phong > doan nay khong biet nhet vao .SQL kieu j, P xem co can thiet khong!!!
		$sql = "SELECT *
			FROM #__digistore_emailreminders
			WHERE (`calc`='' OR `calc` IS NULL)
			  AND (`period`='' OR `period` IS NULL)
			  AND (`date_calc`='' OR `date_calc` IS NULL)";
		$db->setQuery($sql);
		$db->query();
		$emails = $db->loadObjectList();
		for ($i=0; $i<count($emails); $i++)
		{
			$email = $emails[$i];
			switch($email->type)
			{
				case 0:
					$sql = "UPDATE `#__digistore_emailreminders`
						SET `calc`='before',
							`period`='day',
							`date_calc`='expiration',
							`type`=0
						WHERE `id`=" . $email->id;
					break;
				case 1:
					$sql = "UPDATE `#__digistore_emailreminders`
						SET `calc`='before',
							`period`='day',
							`date_calc`='expiration',
							`type`=1
						WHERE `id`=" . $email->id;
					break;
				case 2:
					$sql = "UPDATE `#__digistore_emailreminders`
						SET `calc`='before',
							`period`='day',
							`date_calc`='expiration',
							`type`=2
						WHERE `id`=" . $email->id;
					break;
				case 3:
					$sql = "UPDATE `#__digistore_emailreminders`
						SET `calc`='before',
							`period`='day',
							`date_calc`='expiration',
							`type`=3
						WHERE `id`=" . $email->id;
					break;
				case 4:
					$sql = "UPDATE `#__digistore_emailreminders`
						SET `calc`='before',
							`period`='week',
							`date_calc`='expiration',
							`type`=1
						WHERE `id`=" . $email->id;
					break;
				case 5:
					$sql = "UPDATE `#__digistore_emailreminders`
						SET `calc`='before',
							`period`='week',
							`date_calc`='expiration',
							`type`=2
						WHERE `id`=" . $email->id;
					break;
				case 6:
					$sql = "UPDATE `#__digistore_emailreminders`
						SET `calc`='after',
							`period`='day',
							`date_calc`='expiration',
							`type`=1
						WHERE `id`=" . $email->id;
					break;
				case 7:
					$sql = "UPDATE `#__digistore_emailreminders`
						SET `calc`='after',
							`period`='day',
							`date_calc`='expiration',
							`type`=2
						WHERE `id`=" . $email->id;
					break;
				case 8:
					$sql = "UPDATE `#__digistore_emailreminders`
						SET `calc`='after',
							`period`='day',
							`date_calc`='expiration',
							`type`=3
						WHERE `id`=" . $email->id;
					break;
				case 9:
					$sql = "UPDATE `#__digistore_emailreminders`
						SET `calc`='after',
							`period`='week',
							`date_calc`='expiration',
							`type`=1
						WHERE `id`=" . $email->id;
					break;
				case 10:
					$sql = "UPDATE `#__digistore_emailreminders`
						SET `calc`='after',
							`period`='week',
							`date_calc`='expiration',
							`type`=2
						WHERE `id`=" . $email->id;
					break;
				case 11:
					$sql = "UPDATE `#__digistore_emailreminders`
						SET `calc`='after',
							`period`='week',
							`date_calc`='purchase',
							`type`=1
						WHERE `id`=" . $email->id;
					break;
				case 12:
					$sql = "UPDATE `#__digistore_emailreminders`
						SET `calc`='after',
							`period`='week',
							`date_calc`='purchase',
							`type`=2
						WHERE `id`=" . $email->id;
					break;
			}
			$db->setQuery($sql);
			$db->query();
		}


		$app = JFactory::getApplication();
		$db_prefix = $db->getPrefix();

		$sql = "SHOW TABLES LIKE '".$db_prefix."digistore_%plans'";
		$db->setQuery($sql);
		$tables = $db->loadColumn();

		if(!in_array( $db_prefix.'digistore_plans', $tables )) {
			$sql = 'ALTER TABLE `'.$db_prefix.'digistore_plains` 
						RENAME TO  `'.$db_prefix.'digistore_plans`';
			$db->setQuery($sql);
			$db->query();
			if($db->getErrorNum()){
				$app->enqueueMessage($db->getErrorMsg(),'error');
			}
		}

		if(!in_array( $db_prefix.'digistore_products_plans', $tables )) {
			$sql = 'ALTER TABLE `'.$db_prefix.'digistore_products_plains` 
						CHANGE COLUMN `plain_id` `plan_id` INT(11) NOT NULL  , 
						RENAME TO `'.$db_prefix.'digistore_products_plans`';
			$db->setQuery($sql);
			$db->query();
			if($db->getErrorNum()){
				$app->enqueueMessage($db->getErrorMsg(),'error');
			}
		}
		
		$sql = 'SHOW COLUMNS FROM '.$db_prefix.'digistore_products_renewals LIKE "plan_id"';
		$db->setQuery($sql);
		$column = $db->loadResult();
		if(!$column) {
			$sql = 'ALTER TABLE `'.$db_prefix.'digistore_products_renewals` CHANGE COLUMN `plain_id` `plan_id` INT(11) NOT NULL';
			$db->setQuery($sql);
			$db->query();
			if($db->getErrorNum()){
				$app->enqueueMessage($db->getErrorMsg(),'error');
			}
		}
		
		$sql = 'SHOW KEYS FROM `'.$db_prefix.'digistore_product_categories` WHERE Key_name = "PRIMARY"';
		$db->setQuery($sql);
		$keys = $db->loadColumn();
		if ( !count($keys)||!$keys ) {
			$sql = 'CREATE TABLE `'.$db_prefix.'digistore_product_categories2` AS (SELECT distinct * FROM `'.$db_prefix.'digistore_product_categories`)';
			$db->setQuery($sql);
			$db->query();
			if ( !$db->getErrorNum() ) {
				$sql = 'DROP TABLE `'.$db_prefix.'digistore_product_categories`';
				$db->setQuery($sql);
				$db->query();
				if( !$db->getErrorNum() ) {
					$sql = 'ALTER TABLE '.$db_prefix.'digistore_product_categories2 RENAME '.$db_prefix.'digistore_product_categories ,ADD PRIMARY KEY (`productid`, `catid`)';
					$db->setQuery($sql);
					$db->query();
				} else {
					$app->enqueueMessage($db->getErrorMsg(),'error');
				}
			} else {
				$app->enqueueMessage($db->getErrorMsg(),'error');
			}
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__digistore_products_files` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `product_id` int(11) NOT NULL DEFAULT '0',
				  `filename` varchar(255) NOT NULL,
				  `version` varchar(10) NOT NULL,
				  `changelog` varchar(1000),
				  `published` tinyint(1) NOT NULL DEFAULT '0',
				  `default` int(3) NOT NULL,
				  `ordering` int(11) NOT NULL DEFAULT '0',
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
		$db->setQuery($sql);
		$db->query();
	}

	/**
	 * Install associated plugins to work with DigiStore
	 * @param unknown $type
	 * @param unknown $parent
	 */
	private function _installPlugins($type, $parent){
		$exts = array(
			'digistorecron'=>0,
			'jw_allvideos'=>0,
			'plg_search_dsc'=>0,
			'plg_search_dsp'=>0,
			'plg_system_digistore_addtocart'=>0
		);

		foreach ($exts as $ext => $enable ) {
			$installer 	= new JInstaller();
			$path 	= JPath::clean(dirname(__FILE__).DS.'admin'.DS.'extras'.DS.'plugins'.DS.$ext);
			if(JFolder::exists($path)){
				$res = $installer->install($path);
				if ( !$res ) {
					echo $path;
				} elseif( $enable ) {
					#TODO: enable plugin
				}
			} else {
				echo $path.'<br/>';
			}
		}
		self::_installPayments($type);
	}
	
	private function _installPayments($type){
		$default = array('paypal','paypalpro');
		$exts_path = dirname(__FILE__).DS.'admin'.DS.'extras'.DS.'plugins'.DS.'payment';
		$exts = JFolder::folders( $exts_path, '.', false, true );
		foreach( $exts as $ext ) {
			if(JFolder::exists($ext)){
				$installer 	= new JInstaller();
				$installer->install( $ext );
			}else{
				echo $ext.'<br/>';
			}
		}
		if($type=='install'){
			#TODO enable default payment
			$app 	= JFactory::getApplication();
			$db 	= JFactory::getDbo();

			$default_exts = "'".implode("','",$default)."'";
			$sql = 'UPDATE 
						#__extensions
					SET `enabled` = 1
					WHERE
						`type` = "plugin"
							AND `folder` = "payment"
							AND `element` in ('.$default_exts.')';

			$db->setQuery($sql);
			$db->query();
			if($db->getErrorNum()){
				$app->enqueueMessage($db->getErrorMsg());
			}
		}
	}
	
	/**
	 * Install associated modules to work with DigiStore
	 * @param unknown $type
	 * @param unknown $parent
	 */
	private function _installModules($type,$parent){
		$exts_path 	= dirname(__FILE__).DS.'admin'.DS.'extras'.DS.'modules';
		$exts 	= JFolder::folders( $exts_path, '.', false, true );
		foreach( $exts as $ext ) {
			
			if(JFolder::exists($ext)){
				$installer 	= new JInstaller();
				$res = $installer->install( $ext );
				if(!$res){
					echo $ext;
				}
			}else{
				echo $ext.'<br/>';
			}
		}
	}
	
	private function _uninstallModules(){
		$db = JFactory::getDbo();
		$sql = 'SELECT * FROM 
					`#__extensions` 
				WHERE 
					`type` = "module"
						AND `element` in ("mod_digistore_cart",
											"mod_digistore_categories",
											"mod_digistore_google",
											"mod_digistore_manager");';
		$db->setQuery($sql);
		$exts = $db->loadObjectList();
		if(count($exts)){
			foreach( $exts as $ext ){
				$installer 	= new JInstaller();
				$installer->uninstall( $ext->type, $ext->extension_id );
			}
		}
	}

	private function _uninstallPlugins(){
		$db = JFactory::getDbo();
		$sql = 'SELECT * FROM 
					`#__extensions` 
				WHERE 
					(`type` = "plugin"
						AND `folder`="system"
						AND `element` in ("digistorecron",
											"digistore_addtocart"))
					OR
					(`type` = "plugin"
						AND `folder`="search"
						AND `element` in ("dsc", "dsp"))';
		$db->setQuery($sql);
		$exts = $db->loadObjectList();
		if(count($exts)){
			foreach( $exts as $ext ){
				$installer 	= new JInstaller();
				$installer->uninstall( $ext->type, $ext->extension_id );
			}
		}
	}

	private function _uninstallPayments(){
		$db = JFactory::getDbo();
		$sql = 'SELECT * FROM 
					`#__extensions` 
				WHERE 
					`type` = "plugin"
						AND `folder` = "payment";';
		$db->setQuery($sql);
		$exts = $db->loadObjectList();
		if(count($exts)){
			foreach( $exts as $ext ){
				$installer 	= new JInstaller();
				$installer->uninstall( $ext->type, $ext->extension_id );
			}
		}
	}
	
	private function executeSqlFile( $sqlfile ){
		$app 	= JFactory::getApplication();
		$db 	= JFactory::getDbo();
		$sql 	= JFile::read( $sqlfile );
		$queries = $db->splitSql($sql);
		if( $queries && count($queries) ) {
			foreach ( $queries AS $query ) {
				$db->setQuery($query);
				$db->query();
				if($db->getErrorNum()){
					$app->enqueueMessage($db->getErrorMsg(),'error');
				}
			}
		}
	}
}
