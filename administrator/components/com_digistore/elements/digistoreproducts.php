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

class JElementDigistoreproducts extends JElement
{
   /**
	* Element name
	*
	* @access	protected
	* @var		string
	*/
	var	$_name = 'Digistoreproducts';

	function fetchElement($name, $value, &$node, $control_name)
	{			

		$fieldName	= $control_name.'['.$name.']';
		$id	= $control_name . $name;

		$html = '<input type="text" name="'.$fieldName.'" id="'.$id.'" value="'.$value.'" class="text_area" size="20" />';

		return $html;
	}
}

?>