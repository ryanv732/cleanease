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

class TabledigistorePlain extends JTable {

	var $id = null;
	var $name = null;
	var $duration_count = null;
	var $duration_type = null;
	var $ordering = null;
	var $published = null;

	function TabledigistorePlain (&$db) {
		parent::__construct('#__digistore_plans', 'id', $db);
	}

};

?>
