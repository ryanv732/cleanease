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

class TabledigistoreCategory extends JTable {
	var $id = null;
	var $parent_id = null;
	var $title = null;
	var $name = null;
	var $section = null;
	var $image_position = null;
	var $description = null;
	var $published = null;
	var $checked_out = null;
	var $checked_out_time = null;
	var $editor = null;
	var $ordering = null;
	var $access = null;
	var $count = null;
	var $metakeywords = null;
	var $metadescription = null;
	var $images = null;
	var $params = null;

	function TabledigistoreCategory (&$db) {
		parent::__construct('#__digistore_categories', 'id', $db);
	}

};


?>