<?php
/**
* @package			DigiStore Joomla Extension
 * @author			foobla.com
 * @version			$Revision: 341 $
 * @lastmodified	$LastChangedDate: 2013-10-10 14:28:28 +0200 (Thu, 10 Oct 2013) $
 * @copyright		Copyright (C) 2013 foobla.com. All rights reserved.
* @license			GNU/GPLv3
*/

	// add to config fields Images 
	$this->addField('#__digistore_settings', 'imagecatsizevalue', 'int(11)', false, '0');
	$this->addField('#__digistore_settings', 'imagecatsizetype', 'int(11)', false, '0');

	$this->addField('#__digistore_settings', 'imageprodsizefullvalue', 'int(11)', false, '0');
	$this->addField('#__digistore_settings', 'imageprodsizefulltype', 'int(11)', false, '0');

	$this->addField('#__digistore_settings', 'imageprodsizethumbvalue', 'int(11)', false, '0');
	$this->addField('#__digistore_settings', 'imageprodsizethumbtype', 'int(11)', false, '0');

	// add to config fields Desciptions 
	$this->addField('#__digistore_settings', 'imagecatdescvalue', 'int(11)', false, '0');
	$this->addField('#__digistore_settings', 'imagecatdesctype', 'int(11)', false, '0');

	$this->addField('#__digistore_settings', 'imageproddescvalue', 'int(11)', false, '0');
	$this->addField('#__digistore_settings', 'imageproddesctype', 'int(11)', false, '0');

?>