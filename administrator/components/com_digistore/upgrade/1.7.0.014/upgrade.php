<?php
/**
* @package			DigiStore Joomla Extension
 * @author			foobla.com
 * @version			$Revision: 341 $
 * @lastmodified	$LastChangedDate: 2013-10-10 14:28:28 +0200 (Thu, 10 Oct 2013) $
 * @copyright		Copyright (C) 2013 foobla.com. All rights reserved.
* @license			GNU/GPLv3
*/

	// Change permission for media folders

	@chmod( JPATH_ROOT . DS . 'images' . DS . 'stories' . DS . 'digistore' . DS . 'categories', 0755 );
	@chmod( JPATH_ROOT . DS . 'images' . DS . 'stories' . DS . 'digistore' . DS . 'categories' . DS . 'thumb', 0755 );

	@chmod( JPATH_ROOT . DS . 'images' . DS . 'stories' . DS . 'digistore' . DS . 'products', 0755  );
	@chmod( JPATH_ROOT . DS . 'images' . DS . 'stories' . DS . 'digistore' . DS . 'products' . DS . 'thumb', 0755 );

	// set default images setting and description words

	$this->changeFieldDefault('#__digistore_settings', 'imagecatsizevalue', '100');
	$this->changeFieldDefault('#__digistore_settings', 'imagecatsizetype', '1');

	$this->changeFieldDefault('#__digistore_settings', 'imageprodsizefullvalue', '300');
	$this->changeFieldDefault('#__digistore_settings', 'imageprodsizefulltype', '1');

	$this->changeFieldDefault('#__digistore_settings', 'imageprodsizethumbvalue', '100');
	$this->changeFieldDefault('#__digistore_settings', 'imageprodsizethumbtype', '1');

	$this->changeFieldDefault('#__digistore_settings', 'imagecatdescvalue', '10');
	$this->changeFieldDefault('#__digistore_settings', 'imagecatdesctype', '0');

	$this->changeFieldDefault('#__digistore_settings', 'imageproddescvalue', '10');
	$this->changeFieldDefault('#__digistore_settings', 'imageproddesctype', '0');

?>