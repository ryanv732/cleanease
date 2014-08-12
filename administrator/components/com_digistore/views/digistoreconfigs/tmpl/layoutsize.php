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

?>

<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<th colspan="2"  class="second_title_dgst"><?php echo JText::_('Image &amp; Thumbnails');?></th>
</tr>
<tr>
	<td class="digispacer" colspan="2"></td>
</tr>
<tr> 
	<td colspan="2">

		<div style="padding:0.5em;">
			<div style="float:left;width:150px;"><?php  echo JText::_('Category'); ?></div>
			<div style="float:left;">
				<input type="text" name="imagecatsizevalue" value="<?php echo ($configs->imagecatsizevalue ? $configs->imagecatsizevalue : '100'); ?>"/>
				&nbsp; px &nbsp;
				<?php 
					$imagecatsizetype[] = JHTML::_('select.option','0', JText::_( 'high' ) );
					$imagecatsizetype[] = JHTML::_('select.option','1', JText::_( 'wide' ) );
					echo JHTML::_('select.genericlist',  $imagecatsizetype, 'imagecatsizetype', null, 'value', 'text', $configs->imagecatsizetype);
				?>
			</div>		   
		</div>
		<div class="clr"></div>

		<div style="padding:0.5em;">
			<div style="float:left;width:150px;"><?php  echo JText::_('Product full size'); ?></div>
			<div style="float:left;">
				<input type="text" name="imageprodsizefullvalue" value="<?php echo ($configs->imageprodsizefullvalue ? $configs->imageprodsizefullvalue : '300'); ?>"/>
				&nbsp; px &nbsp;
				<?php 
					$imageprodsizefulltype[] = JHTML::_('select.option','0', JText::_( 'high' ) );
					$imageprodsizefulltype[] = JHTML::_('select.option','1', JText::_( 'wide' ) );
					echo JHTML::_('select.genericlist',  $imageprodsizefulltype, 'imageprodsizefulltype', null, 'value', 'text', $configs->imageprodsizefulltype);
				?>
			</div>
		</div>
		<div class="clr"></div>

		<div style="padding:0.5em;">
			<div style="float:left;width:150px;"><?php  echo JText::_('Product thumbnail'); ?></div>
			<div style="float:left;">
				<input type="text" name="imageprodsizethumbvalue" value="<?php echo ($configs->imageprodsizethumbvalue ? $configs->imageprodsizethumbvalue : '100'); ?>"/>
				&nbsp; px &nbsp;
				<?php 
					$imageprodsizethumbtype[] = JHTML::_('select.option','0', JText::_( 'high' ) );
					$imageprodsizethumbtype[] = JHTML::_('select.option','1', JText::_( 'wide' ) );
					echo JHTML::_('select.genericlist',  $imageprodsizethumbtype, 'imageprodsizethumbtype', null, 'value', 'text', $configs->imageprodsizethumbtype);
				?>
			</div>
		</div>
		<div class="clr"></div>

	</td>
</tr>
<tr>
	<th colspan="2"  class="second_title_dgst"><?php echo JText::_('Description');?></th>
</tr>
<tr>
	<td class="digispacer" colspan="2"></td>
</tr>
<tr> 
	<td colspan="2">

		<div style="padding:0.5em;">
			<div style="float:left;width:150px;"><?php  echo JText::_('Categories'); ?></div>
			<div style="float:left;">
				<input type="text" name="imagecatdescvalue" value="<?php echo ($configs->imagecatdescvalue ? $configs->imagecatdescvalue : '10'); ?>"/>
				&nbsp; px &nbsp;
				<?php 
					$imagecatdesctype[] = JHTML::_('select.option','0', JText::_( 'words' ) );
					$imagecatdesctype[] = JHTML::_('select.option','1', JText::_( 'characters' ) );
					echo JHTML::_('select.genericlist',  $imagecatdesctype, 'imagecatdesctype', null, 'value', 'text', $configs->imagecatdesctype);
				?>
			</div>
		</div>
		<div class="clr"></div>

		<div style="padding:0.5em;">
			<div style="float:left;width:150px;"><?php  echo JText::_('Products'); ?></div>
			<div style="float:left;">
				<input type="text" name="imageproddescvalue" value="<?php echo ($configs->imageproddescvalue ? $configs->imageproddescvalue : '10') ; ?>"/>
				&nbsp; px &nbsp;
				<?php 
					$imageproddesctype[] = JHTML::_('select.option','0', JText::_( 'words' ) );
					$imageproddesctype[] = JHTML::_('select.option','1', JText::_( 'characters' ) );
					echo JHTML::_('select.genericlist',  $imageproddesctype, 'imageproddesctype', null, 'value', 'text', $configs->imageproddesctype);
				?>
			</div>
		</div>
		<div class="clr"></div>

	</td>
</tr>
