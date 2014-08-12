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

jimport ("joomla.aplication.component.model");

class digistoreModeldigistoreConfig extends obModel
{
	
	var $_configs = null;
	var $_id = null;

	function __construct () {
		parent::__construct();
		$this->_id = 1;
	}

	function getConfigs() {
	
		if (empty ($this->_configs)) {
			$this->_configs = $this->getTable("digistoreConfig");
			$this->_configs->load($this->_id);
		}

		$f = $this->_configs->time_format;
		$f = str_replace ("MM", "m", $f);
		$f = str_replace ("DD", "d", $f);
		$f = str_replace ("YYYY", "Y", $f);
		$this->_configs->time_format = $f;

		$view = JRequest::getWord('view');
		$lay = JRequest::getWord('layout');

		if (strlen(trim($lay)) > 0) {
			if (strtolower(trim($view)) == "digistorecategories")
			switch(strtolower($lay)){
				case "list":
					$this->_configs->catlayoutstyle = 0;
					break;
		
				case "listthumbs":
					$this->_configs->catlayoutstyle = 1;
					break;
		
				case "dropdown":
					$this->_configs->catlayoutstyle = 2;
					break;

			}
		}
		return $this->_configs;

	}


};
?>