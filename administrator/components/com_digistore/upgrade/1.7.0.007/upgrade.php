<?php
/**
* @package			DigiStore Joomla Extension
 * @author			foobla.com
 * @version			$Revision: 341 $
 * @lastmodified	$LastChangedDate: 2013-10-10 14:28:28 +0200 (Thu, 10 Oct 2013) $
 * @copyright		Copyright (C) 2013 foobla.com. All rights reserved.
* @license			GNU/GPLv3
*/

	// Hour Format / Settings
	$this->addField('#__digistore_settings', 'hour24format', 'int(11)', false, '0');

	// Default for hour format
	$this->updateTable('#__digistore_settings', array('hour24format' => '0'), " id=1 ");


?>