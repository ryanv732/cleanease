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

	$orderby = JRequest::getVar('orderby', 'default');
	if($this->configs->prodlayoutsort == "0"){
		$controller = JRequest::getVar('controller', "");
		if(trim($controller) == ""){
			$controller = JRequest::getVar('view', "");
		}
?>
		<div class="ijd-box ijd-rounded ijd-pad5">
			<form action="index.php" name="digistore_orderby" method="get">
				<?php echo JText::_("DIGI_SORT_BY"); ?>:   
				<input type="hidden" name="option" value="<?php echo JRequest::getVar('option'); ?>"/>
				<input type="hidden" name="controller" value="<?php echo $controller; ?>"/>
				<input type="hidden" name="task" value="<?php echo JRequest::getVar('task'); ?>"/>
				<input type="hidden" name="cid" value="<?php echo JRequest::getVar('cid'); ?>"/>
				<select class="inputbox" id="orderby" name="orderby" onchange="digistore_orderby.submit()">
					<option value="default"<?php echo ($orderby == 'default') ? " selected='selected'":"" ; ?>><?php echo JText::_("DIGI_SELECT"); ?></option>
					<option value="name"<?php echo ($orderby == 'name') ? " selected='selected'":"" ; ?>><?php echo JText::_("DIGI_PRODUCT_NAME"); ?></option>
					<option value="sku"<?php echo ($orderby == 'sku') ? " selected='selected'":"" ; ?>><?php echo JText::_("DIGI_SKU"); ?></option>	  
					<option value="latest"<?php echo ($orderby == 'latest') ? " selected='selected'":"" ; ?>><?php echo JText::_("DIGI_LATEST_PRODUCTS"); ?></option>
				</select>
				<input type="hidden" name="Itemid" value="<?php echo JRequest::getVar('Itemid'); ?>"/>
			</form>
		</div>
<?php
	}
?>