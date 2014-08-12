<?php
/**
* @package			DigiStore Joomla Extension
 * @author			foobla.com
 * @version			$Revision: 418 $
 * @lastmodified	$LastChangedDate: 2013-11-16 09:20:18 +0100 (Sat, 16 Nov 2013) $
 * @copyright		Copyright (C) 2013 foobla.com. All rights reserved.
* @license			GNU/GPLv3
*/

defined ('_JEXEC') or die ("Go away.");

class TabledigistoreMail extends JTable {
	public $id 		= null;
	public $date 	= null;
	public $email 	= null;
	public $body 	= null;
	public $flag 	= null;

	function TabledigistoreMail (&$db) {
		parent::__construct('#__digistore_sendmails', 'id', $db);
	}

};


?>