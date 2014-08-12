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

JHtml::_('behavior.tooltip');

$document = JFactory::getDocument();
$document->addStyleSheet("components/com_digistore/assets/css/digistore.css");

?>
<script language="javascript" type="text/javascript">
		<!--

		function submitbutton(pressbutton) {

			var form = document.adminForm;
			if (pressbutton == 'cancel') {
				submitform(pressbutton);
				return;
			}


			if ( form.name.value == "" ) {
				alert( '<?php echo JText::_("VIEWCATEGORYCATMUSTHAVENAME");?>' );
				return false;
			} else if (form.title.value == ""){
				alert( '<?php echo JText::_("VIEWCATEGORYCATMUSTHAVETITLE");?>' )
				return false;
			} 

			submitform( pressbutton );
		}
		-->
</script>

 <form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" class="form-horizontal">
<?php

		$options = array(
				'onActive' => 'function(title, description){
					description.setStyle("display", "block");
					title.addClass("open").removeClass("closed");
				}',
						'onBackground' => 'function(title, description){
					description.setStyle("display", "none");
					title.addClass("closed").removeClass("open");
				}',
				'useCookie' => false, // this must not be a string. Don't use quotes.
				'startOffset'=>0,
				'active' => 'general_settings'
		);

		echo JHtml::_( 'obtabs.start', 'category_settings', $options ); 
		echo JHtml::_( 'obtabs.addTab', 'category_settings', 'general_settings', JText::_('VIEWCATEGORYGENERAL',true));
?>
	<fieldset class="adminform">
			<div>
				<div class="control-group">
					<div class="control-label"><label>
					<?php echo JText::_('VIEWCATEGORYCATNAME');?>:
					</label></div>
					<div class="controls">
						<input class="text_area" type="text" name="name" value="<?php echo stripslashes( $this->cat->name ); ?>" size="50" maxlength="255" title="<?php echo JText::_('Menu name');?>" />
						<?php
							echo JHTML::tooltip(JText::_("COM_DIGISTORE_CATEGNAME_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
						?>
					</div>
				</div>
		<div style="clear: both;"></div>
				<div class="control-group">
					<div class="control-label"><label>
					<?php echo JText::_('VIEWCATEGORYORDERING'); ?>:
					</label></div>
					<div class="controls">
					<?php echo $this->lists['ordering']; ?>
					<?php
						echo JHTML::tooltip(JText::_("COM_DIGISTORE_CATEGORDERING_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
					?>
					</div>
				</div>
		<div style="clear: both;"></div>
				<div class="control-group">
					<div class="control-label"><label>
					<?php echo JText::_('VIEWCATEGORYAL');?>:
					</label></div>
					<div class="controls">
					<?php echo $this->lists['access']; ?>
					<?php
						echo JHTML::tooltip(JText::_("COM_DIGISTORE_CATEGACCESS_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
					?>
					</div>
				</div>
		<div style="clear: both;"></div>
				<div class="control-group">
					<div class="control-label"><label>
					<?php echo JText::_('VIEWCATEGORYPUBLISHED');?>
					</label>
					<div class="controls">
						<table>
							<tr>
								<td style="width:1%">
									<input type="radio" value="0" id="published0" name="published">
								</td>
								<td style="width:1%">
									<?php echo JText::_("DSNO"); ?>
								</td>
								<td style="width:1%">
									<input type="radio" checked="checked" value="1" id="published1" name="published">
								</td>
								<td style="width:1%">
									<?php echo JText::_("DSYES"); ?>
								</td>
								<td style="width:50%">
									<?php
										echo JHTML::tooltip(JText::_("COM_DIGISTORE_CATEGPUBLISHED_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
									?>
								</td>
							</tr>
						</table>
					</div>
				</div>

				<div class="control-group">
					<div class="control-label"><label>
					<?php echo JText::_('VIEWCATEGORYPARENT');?>
					</label></div>
					<div class="controls">
					<?php echo $this->lists['parent']; ?>
					<?php
						echo JHTML::tooltip(JText::_("COM_DIGISTORE_CATEGPARENT_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
					?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label"><label>
						<?php echo JText::_('VIEWCATEGORYDESCRIPTION');?>: <?php
						echo JHTML::tooltip(JText::_("COM_DIGISTORE_CATEGDESCRIPTION_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
						?>
					</label></div>
				</div>
				<div class="control-group">
						<textarea id="description" name="description" class="useredactor" style="width:100%;height:550px;"><?php echo $this->cat->description;?></textarea>
				</div>
			</div>
	</fieldset>
<?php
	echo JHtml::_( 'obtabs.endTab');


	echo JHtml::_( 'obtabs.addTab', 'category_settings', 'image_info_new', JText::_('CATEGORY_EDIT_TAB_TITLE',true));
	// Image Tab (NEW)
?>
	<fieldset class="adminform">
		<div>
			<input class="btn" type="button" name="uploadimagebutton" id="ajaxuploadcatimage" value="Upload single image"/>
		</div>

		<div>
			<?php echo JText::_('CATEGORY_EDIT_IMAGE_PREVIEW_TITLE'); ?>
			<div id="catthumbnail"><?php 

				if ( !empty($this->cat->image) ) {

					$uniqid = uniqid (rand (),true);

					echo "<div id='box".$uniqid."' style='float:left;padding:0.5em;margin:0.5em;'>
							<img src='".ImageHelper::ShowCategoryImage($this->cat->image)."'/>
							<input type='hidden' name='catimageshidden' value='".$this->cat->image."'/>
							<div style='padding:0.5em 0;'>
								<span style='float:right;'><a href='javascript:void(0);'  onclick='document.getElements(\"div[id=box".$uniqid."]\").each( function(el) { el.getParent().removeChild(el); });'>Delete</a></span>
							</div>
						</div>";
				}

			?></div>
		</div>

	</fieldset>
<?php
	echo JHtml::_( 'obtabs.endTab');


	echo JHtml::_( 'obtabs.addTab', 'category_settings', 'meta_info', JText::_('VIEWCATEGORYMETATAGS',true));
	// Meta Tab
?>
			<div class="control-group">
				<div class="control-label"><label><?php echo JText::_('VIEWCATEGORYMETATITLE');?>:</label></div>
				<div class="controls">
					<input class="text_area" type="text" name="title" value="<?php echo stripslashes( $this->cat->title ); ?>" size="50" maxlength="50"/>
				</div>
			</div>
			<div class="control-group">
				<div class="control-label"><label><?php echo JText::_('VIEWCATEGORYMK');?>:</label></div>
				<div class="controls">
					<textarea rows=10 cols=30 name="metakeywords" ><?php echo stripslashes( $this->cat->metakeywords );?></textarea>
				</div>
			</div>
			<div class="control-group">
				<div class="control-label"><label><?php echo JText::_('VIEWCATEGORYMT');?>:</label></div>
				<div class="controls">
					<textarea rows=10 cols=30 name="metadescription" ><?php echo stripslashes( $this->cat->metadescription );?></textarea>
				</div>
			</div>

<?php
		echo JHtml::_( 'obtabs.endTab');
		echo JHtml::_( 'obtabs.end' );
?>
			<input type="hidden" name="option" value="com_digistore" />
			<input type="hidden" name="id" value="<?php echo $this->cat->id; ?>" />
			<input type="hidden" name="task" value="" />
		<input type="hidden" name="controller" value="digistoreCategories" />
		<input type="hidden" name="images" id="images" value="" />
		<input type="hidden" name="oldtitle" value="<?php echo $this->cat->name;?>" />
		</form>