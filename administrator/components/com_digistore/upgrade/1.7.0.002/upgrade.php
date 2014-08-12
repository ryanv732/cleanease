<?php
/**
* @package			DigiStore Joomla Extension
 * @author			foobla.com
 * @version			$Revision: 341 $
 * @lastmodified	$LastChangedDate: 2013-10-10 14:28:28 +0200 (Thu, 10 Oct 2013) $
 * @copyright		Copyright (C) 2013 foobla.com. All rights reserved.
* @license			GNU/GPLv3
*/

	// Store Description
	$this->addField('#__digistore_settings', 'storedesc', 'mediumtext', false, '');

	// Display store description
	$this->addField('#__digistore_settings', 'displaystoredesc', 'int(11)', false, '1');

	// Default for Store 
	$this->updateTable('#__digistore_settings', array('storedesc' => 'Welcome to our store', 'displaystoredesc' => '1'), " id=1 ");



?>