<?php
/**
* @package			DigiStore Joomla Extension
 * @author			foobla.com
 * @version			$Revision: 341 $
 * @lastmodified	$LastChangedDate: 2013-10-10 14:28:28 +0200 (Thu, 10 Oct 2013) $
 * @copyright		Copyright (C) 2013 foobla.com. All rights reserved.
* @license			GNU/GPLv3
*/

	// Cart

	$this->addField('#__digistore_cart', 'plan_id', 'int(11)', false, '0');
	$this->addField('#__digistore_cart', 'renew', 'int(11)', false, '0');
	$this->addField('#__digistore_cart', 'renewlicid', 'int(11)', false, '0');


	// Category

	$this->addField('#__digistore_categories', 'thumb', 'varchar(500)', false);
	$this->changeFieldType('#__digistore_categories', 'image', 'image', 'varchar(500)');


	// FeaturedProducts

	$this->addField('#__digistore_featuredproducts', 'planid', 'int(11)', false, '0');


	// Licenses

	$this->addField('#__digistore_licenses', 'renew', 'int(11)', false, '0');
	$this->addField('#__digistore_licenses', 'renewlicid', 'int(11)', false, '-1');
	$this->addField('#__digistore_licenses', 'download_count', 'int(11)', false, '0');
	$this->addField('#__digistore_licenses', 'purchase_date', 'datetime', false, '0000-00-00 00:00:00');
	$this->addField('#__digistore_licenses', 'plan_id', 'int(11)', false, '0');
	$this->dropField('#__digistore_licenses', 'email');
	$this->dropField('#__digistore_licenses', 'order_id');

	// Orders

	$this->addField('#__digistore_orders', 'amount_paid', 'float', false, '0');
	$this->addField('#__digistore_orders', 'processor', 'varchar(100)', false);
	$this->dropField('#__digistore_orders', 'payment_method');
	$this->addField('#__digistore_orders', 'published', 'int(11)', false, '0');

	// Plugins

	$this->dropTable('#__digistore_plugins');
	$this->dropTable('#__digistore_plugin_settings');

	// Products

	$this->addField('#__digistore_products', 'sku', 'varchar(100)', false, '');
	$this->addField('#__digistore_products', 'showqtydropdown', 'int(11)', false, '0');
	$this->addField('#__digistore_products', 'priceformat', 'int(11)', false, '1');
	$this->addField('#__digistore_products', 'featured', 'int(11)', false, '0');

	// Product: set plain 'Unlimited'

	if (!is_null($this->getFieldsTable('#__digistore_products', 'price'))) {
		$products_001 = $this->getTableData('#__digistore_products','', '*', 'objectlist');

		$products_plains_001 = array();

		foreach($products_001 as $product) {

			if ( empty($product->sku) || ($product->sku == 0) ) {
				$product_sku_001 = array('sku' => 'SKU'.$product->id);
				$this->updateTable( '#__digistore_products', $product_sku_001, ' id= '.$product->id );
			}

			if (isset($product->price))	$price_001 = $product->price;
			else $price_001 = '99.99';

			$products_plains_001[] = array(
				'product_id' => $product->id,
				'plan_id' => 1,
				'price' => $price_001,
				"`default`" => 1
			);
		}
		$this->insertTable('#__digistore_products_plans', $products_plains_001);
		$this->insertTable('#__digistore_products_renewals', $products_plains_001);
	}
	$this->dropField('#__digistore_products', 'price');


	// Session

	$this->addField('#__digistore_session', 'processor', 'varchar(250)', false);

?>