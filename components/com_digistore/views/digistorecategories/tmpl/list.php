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

echo '<div class="digistore">';
echo digistoreHelper::ShowHomeDescriptionBlock($this->configs);

$k = 0;
$n = count($this->cats);

if ($n < 1):
	echo JText::_('DSNOCAT');
else:
	echo $this->cats_out;
endif;

if($this->pagination->getPagesLinks() != ''):?>
<div class="pagination pagination-centered"><?php echo $this->pagination->getPagesLinks(); ?></div>
<div style="text-align:center;"><?php echo $this->pagination->getPagesCounter(); ?></div><?php
endif;

echo '</div>';
$view = JRequest::getVar('view');
if($view=='digistoreCategories'){
	echo digistoreHelper::powered_by();
}
