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

class TabledigistoreLicenseProdFields extends JTable {
	var $fieldid = null;
	var $licenseid = null;
	var $optionid = null;


	function TabledigistoreLicenseProdFields (&$db) {
		parent::__construct('#__digistore_licenseprodfields', '', $db);

	}

};


?>