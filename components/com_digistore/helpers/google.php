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

class GoogleHelper {

	function trackingOrder($order_id) {

		$database = JFactory::getDBO();

		/*$track = "
		<script language='javascript' type='text/javascript'>

		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', 'UA-XXXXX-X']);
		  _gaq.push(['_trackPageview']);
		";

		$sql = "SELECT o.id, o.amount_paid, o.tax, o.shipping, c.city, c.state, c.country FROM #__digistore_orders o inner join #__digistore_customers c on (o.userid = c.id) WHERE o.id = ".$order_id;
		$database->setQuery($sql);
		$order = $database->loadObject();

		$track .= "
		  _gaq.push(['_addTrans',
			'".$order->id."',		   // order ID - required
			'',  // affiliation or store name
			'".$order->amount_paid."',		  // total - required
			'".$order->tax."',		   // tax
			'".$order->shipping."',			  // shipping
			'".$order->city."',	   // city
			'".$order->state."',	 // state or province
			'".$order->country."'			 // country
		  ]);

		   // add item might be called for every item in the shopping cart
		   // where your ecommerce engine loops through each item in the cart and
		   // prints out _addItem for each
		";


		$sql = "SELECT l.orderid, p.name, p.sku, amount_paid  FROM #__digistore_licenses l inner join #__digistore_products p on (l.productid = p.id) WHERE orderid = ".$order_id;
		$database->setQuery($sql);
		$licenses = $database->loadObjectList();

		foreach($licenses as $license) {

			$track .= "		   
			  _gaq.push(['_addItem',
				'".$license->orderid."',		   // order ID - required
				'".$license->sku."',		   // SKU/code - required
				'".$license->name."',		// product name
				'',   // category or variation
				'".$license->amount_paid."',		  // unit price - required
				'1'			   // quantity - required
			  ]);
			";

		}

		$track .= "		  
		  _gaq.push(['_trackTrans']); //submits transaction to the Analytics servers

		  (function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();

		</script>		  
		";  */

		$sql = "SELECT o.id, o.amount, o.amount_paid, o.tax, o.shipping, c.city, c.state, c.country FROM #__digistore_orders o inner join #__digistore_customers c on (o.userid = c.id) WHERE o.id = ".$order_id;
		$database->setQuery($sql);
		$order = $database->loadObject();

		$color = DSConfig::get('conversion_color', '');
		$color = str_replace("#", '', $color);

		$track = '<!-- Google Code for Purchase Conversion Page -->
				<script type="text/javascript">
				/* <![CDATA[ */
				var google_conversion_id = '.DSConfig::get('conversion_id').';
				var google_conversion_language = "'.DSConfig::get('conversion_language').'";
				var google_conversion_format = "'.DSConfig::get('conversion_format').'";
				var google_conversion_color = "'.$color.'";
				var google_conversion_label = "'.DSConfig::get('conversion_label').'";
				var google_conversion_value = '.$order->amount.';
				/* ]]> */
				</script>
				<script type="text/javascript" src="http://www.googleadservices.com/pagead/conversion.js">
				</script>
				<noscript>
				<div style="display:inline;">
				<img height="1" width="1" style="border-style:none;" alt="" src="http://www.googleadservices.com/pagead/conversion/'.DSConfig::get('conversion_id').'/?value='.$order->amount.'&amp;label='.DSConfig::get('conversion_label').'&amp;guid=ON&amp;script=0"/>
				</div>
				</noscript>';

		return $track;
	}

}


?>