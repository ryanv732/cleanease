<?php
/**
* @package			DigiStore Joomla Extension
 * @author			foobla.com
 * @version			$Revision: 341 $
 * @lastmodified	$LastChangedDate: 2013-10-10 14:28:28 +0200 (Thu, 10 Oct 2013) $
 * @copyright		Copyright (C) 2013 foobla.com. All rights reserved.
* @license			GNU/GPLv3
*/

	// add to product fields for store Images "prodimages"
	$this->addField('#__digistore_products', 'prodimages', 'text', false, '');

	// default image for product
	$this->addField('#__digistore_products', 'defprodimage', 'varchar(500)', false, '');

	@mkdir( JPATH_ROOT . DS . 'images' . DS . 'stories' . DS . 'digistore' . DS . 'categories' );
	@mkdir( JPATH_ROOT . DS . 'images' . DS . 'stories' . DS . 'digistore' . DS . 'categories' . DS . 'thumb' );

	@mkdir( JPATH_ROOT . DS . 'images' . DS . 'stories' . DS . 'digistore' . DS . 'products'  );
	@mkdir( JPATH_ROOT . DS . 'images' . DS . 'stories' . DS . 'digistore' . DS . 'products' . DS . 'thumb' );

?>