<?php
/**
* @package			DigiStore Joomla Extension
 * @author			foobla.com
 * @version			$Revision: 432 $
 * @lastmodified	$LastChangedDate: 2013-11-18 04:29:45 +0100 (Mon, 18 Nov 2013) $
 * @copyright		Copyright (C) 2013 foobla.com. All rights reserved.
* @license			GNU/GPLv3
*/

defined ('_JEXEC') or die ("Go away.");

JHtml::_('behavior.tooltip');
JHTML::_('behavior.modal');
jimport('joomla.html.pane');

global $isJ25;
$configs = $this->configs;
//echo "<pre>";print_r($this->prod);die();
$f = $configs->time_format;
$f = str_replace ("-", "-%", $f);
$f = "%".$f;
$hidetab = $this->lists['hidetab'];
$document = JFactory::getDocument();
$document->addScript( JURI::root() . 'components/com_digistore/assets/js/jquery.digistore.js');
$document->addScript( JURI::root() . 'components/com_digistore/assets/js/jquery.noconflict.digistore.js');
$document->addStyleSheet("components/com_digistore/assets/css/digistore.css");

?>
<script language="javascript" type="text/javascript">

/*###################################################################*/
	function getPlainDefault(eclass){
		var default_value = '';
		var dradios = $$('.'+eclass);
		dradios.each( function(el, index){
			if(el.checked){
				default_value =el.value;
			}
		});
		return default_value;
	}

	window.addEvent('domready',function(){
		$$('.plain_default').each(function(el,index){
			el.addEvent('click', function(ev){
				if(el.checked){
					$('plain_amount'+el.value).focus();
				}
			});
		});
		
		$$('.renewal_default').each(function(el,index){
			el.addEvent('click', function(ev){
				if(el.checked){
					$('renewal_amount'+el.value).focus();
				}
			});
		});
		
		$$('.plain').each(function(el, index){
			el.addEvent('click', function(ev){
				if(el.checked){
					var eid = el.id.substr(5);
					var renewal_amount = $('plain_amount'+eid).value;
					if(!renewal_amount) {
						$('plain_amount'+eid).focus();
					}
				}
			});
		});
		$$('.renewal').each(function(el, index){
			el.addEvent('click', function(ev){
				if(el.checked){
					var eid = el.id.substr(7);
					var renewal_amount = $('renewal_amount'+eid).value;
					if(!renewal_amount) {
						$('renewal_amount'+eid).focus();
					}
				}
			});
		});
		$$('.plain_amount').each(function(el,index){
			el.addEvent('focus',function(e){
				eid = this.id.substr(12);
				eplan = $('plain'+eid).checked="checked";
				var plain_default = getPlainDefault('plain_default');
				if(!plain_default){
					$('plain_default'+eid).checked="checked";
				}
			});
			el.addEvent('blur', function(e){
				if(isNaN(el.value)){
					alert("<?php echo JText::_('COM_DIGISTORE_PRICE_MUST_IS_A_NUMBER'); ?>");
					el.focus();
					return;
				}
				eid = this.id.substr(12);
				if(!this.value){
					eplan = $('plain'+eid).checked="";
					$('plain_default'+eid).checked="";
				}
			});
		})

		$$('.renewal_amount').each(function(el,index){
			el.addEvent('focus',function(e){
				eid = this.id.substr(14);
				eplan = $('renewal'+eid).checked="checked";
				var plain_default = getPlainDefault('renewal_default');
				if(!plain_default){
					$('renewal_default'+eid).checked="checked";
				}
			});
			el.addEvent('blur', function(e){
				if(isNaN(el.value)){
					alert("<?php echo JText::_('COM_DIGISTORE_PRICE_MUST_IS_A_NUMBER'); ?>");
					el.focus();
					return;
				}
				eid = this.id.substr(14);
				if(!this.value){
					eplan = $('renewal'+eid).checked="";
					$('renewal_default'+eid).checked="";
				}
			});
		})
	});
/*###################################################################*/
	
	function changeImageList(data) {
		var lists = new Array();
<?php
foreach ($this->lists['imagelists'] as $folder => $list) {
	echo 'lists["'.$folder.'"]="'.$list.'";';
}
?>
		document.getElementById('srcimageselector').innerHTML = unescape(lists[data.value]);
	}

	function changeShownImg(imgtype) {
		var subpath = document.getElementById('folders').options[document.getElementById('folders').selectedIndex].value;
		if (imgtype == 'src'){
			if (document.adminForm.srcimg.value !='') {
				document.adminForm.view_srcimg.src = '../images/' + subpath + '/' + document.adminForm.srcimg.value;
			} else {
				document.adminForm.view_srcimg.src = 'images/blank.png';
			}
		} else if (imgtype == 'prod') {
			if (document.adminForm.prodimg.value !='') {
				document.adminForm.view_prodimg.src = '../images/' + document.adminForm.prodimg.value;
			} else {
				document.adminForm.view_prodimg.src = 'images/blank.png';
			}
		}
	}

	function addSelectedToList() {
		var subpath = document.getElementById('folders').options[document.getElementById('folders').selectedIndex].value;
		var img = document.adminForm.srcimg.value;
		var imgpath = '../images/' + subpath + "/" + img ;
		var dst = document.getElementById("prodimg");
		var flag = 0;
		for (var i = 0; i < dst.childNodes.length; i++) {
			if (dst.childNodes[i].value == imgpath) {
				flag = 1;
				break;
			}
		}
		if (!flag) {
			insertOption(dst, imgpath, img, "option");
		}
	}

	function removeSelectedFromList() {
		var dst = document.getElementById("prodimg");
		var opt = dst.options[dst.selectedIndex];
		dst.removeChild(opt);
	}


	function moveUp () {
		var dst = document.getElementById("prodimg");
		var opt = dst.options[dst.selectedIndex];
		var prev = opt.previousSibling;
		if ( prev != null) {
			var tmp = dst.removeChild(opt);
			dst.insertBefore(tmp, prev);
		}
	}

	function moveDown () {
		var dst = document.getElementById("prodimg");
		var opt = dst.options[dst.selectedIndex];
		var next = opt.nextSibling;
		if ( next != null) {
			var tmp = dst.removeChild(next);
			dst.insertBefore(tmp, opt);
		}
	}

	function insertOption (where, value, in_html, type){
		var new_option = document.createElement(type);
		new_option.setAttribute('value', value);
		var new_text = document.createTextNode(in_html);
		new_option.appendChild(new_text);

		where.appendChild(new_option);
		return (where.options.length - 1);
	}

	function updateCheckbox (id) {
		var ids = new Array();
		ids[0] = 'linkno';
		ids[1] = 'linktoid';
		ids[2] = 'linktourl';
		for (var i = 0; i< 3; i++){
			document.getElementById(ids[i]).checked = 0;
		}
		document.getElementById(id).checked = 1;

	}


	Joomla.submitbutton = function(pressbutton){
		var form = document.adminForm;

		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}
		// assemble the images back into one field
		
		var id = 'linkno';
		var ids = new Array();
		ids[0] = 'linkno';
		ids[1] = 'linktoid';
		ids[2] = 'linktourl';
		for (var i = 0; i< 3; i++){
			if (document.getElementById(ids[i]).checked == 1) {
				id = ids[i] + "value";
				break;
			}
		}
		if (document.getElementById(id)) {
			var linkvalue = document.getElementById(id).value;
		}
		var flag=  false;
		//do field validation
		if (id == "linktourlvalue" && linkvalue.length < 10 ) {
			alert( "<?php echo JText::_("VIEWPRODEXTRESINV");?>" );
			return false;
		} else if (id == "linktoidvalue" && (isNaN(parseInt(linkvalue,10))|| parseInt(linkvalue,10) < 1 || !/^[\s]*\d+\s*$/.test(linkvalue))) {
			alert( "<?php echo JText::_("VIEWPRODPROVARTWRONG");?>" );
			return false;
		} else if (form.catid.value == "-1"){
			alert( "<?php echo JText::_("VIEWPRODSELPRODCAT");?>" );
			return false;
		} else if (form.catid.value == ""){
			alert( "<?php echo JText::_("VIEWPRODSELPRODCAT1"); ?>" );
			return false;
		} else if (form.title.value == "") {
			alert( "<?php echo JText::_("VIEWPRODPRODTITLEISEMPTY");?>" );
			return false;
		}  else if (form.name.value == "") {
			alert( "<?php echo JText::_("VIEWPRODPRODNAMEISEMPTY");?>" );
			return false;
		} else if (form.domainrequired.value < 2){
			flag = checkFileRequired();
			if(!flag) return false;
		} else {
			flag = true;
		}

		if (!flag) return false;
		var dst = document.getElementById("prodimg");
		var flag = 0;
		var tmp = document.getElementById("images");
		tmp.value = '';
		for (var i = 0; i < dst.childNodes.length; i++) {
			if(typeof(dst.childNodes[i].value) != 'undefined'){
				tmp.value += "\n" + dst.childNodes[i].value;
			}
		}

		submitform( pressbutton );
	}


	function getElementsByClass(node,searchClass,tag) {
		var classElements = new Array();
		var els = node.getElementsByTagName(tag); // use "*" for all elements
		var elsLen = els.length;
		var pattern = new RegExp("\\b"+searchClass+"\\b");
		for (i = 0, j = 0; i < elsLen; i++) {
			if ( pattern.test(els[i].className) ) {
				classElements[j] = els[i];
				j++;
			}
		}
		return classElements;
	}


	function checkPlans(status) {

		var el = getElementsByClass(document,'plain','input');

		// var splains = document.getElementById('splains');

		for (i = 0; i < el.length; i++) {
			var eid = (el[i].id).substr(5);
			var plain_amount = document.getElementById('plain_amount'+eid).value;
			if (status) {
				if (!el[i].checked && plain_amount) el[i].checked = true;
			} else {
				if (el[i].checked && plain_amount ) el[i].checked = false;
			}
		}
	}


	function checkRenewal(status) {

		var el = getElementsByClass(document,'renewal','input');
		for (i = 0; i < el.length; i++) {
			var eid = (el[i].id).substr(7);
			var renewal_amount = document.getElementById('renewal_amount'+eid).value;
			if ( status ) {
				if (!el[i].checked && renewal_amount) el[i].checked = true;
			} else {
				if (el[i].checked && renewal_amount) el[i].checked = false;
			}
		}
	}


	function checkEmailreminder() {

		var el = getElementsByClass(document,'emailreminder','input');

		var semails = document.getElementById('semails');

		for (i = 0; i < el.length; i++) {
			if (semails.checked) {
				if (!el[i].checked) el[i].checked = true;
			} else {
				if (el[i].checked) el[i].checked = false;
			}
		}
	}
</script>
<form action="index.php" method="post" name="adminForm" enctype="multipart/form-data" id="adminForm" class="form-horizontal">

<table width="100%" class="admintable">
	<tr>
		<td align="right" colspan="3">
			<a class="modal digi_video" rel="{handler: 'iframe', size: {x: 750, y: 435}}" href="index.php?option=com_digistore&controller=digistoreAbout&task=vimeo&id=38448917">
				<img src="<?php echo JURI::base(); ?>components/com_digistore/assets/images/icon_video.gif" class="video_img" />
				<?php echo JText::_("COM_DIGISTORE_VIDEO_PROD_ADDPROD"); ?>
			</a>
		</td>
	</tr>
	<tr>
		<td width="200"></td>
		<td width="17%"><?php echo JText::_('VIEWPRODPRODTYPE');//_REQUEST_DOMAIN_REG;?></td>
		<td>
			<?php echo $this->lists['domainrequired']; ?>
		</td>
	</tr>
	<tr>
		<td></td>
		<td>
			<?php echo JText::_('VIEWPRODSKU');?>
		</td>
		<td>
			<?php echo $this->lists['sku']; ?> ( <?php echo JText::_('VIEWPRODID');?>:  <strong><?php  echo (isset($this->prod->id)& $this->prod->id>0)?$this->prod->id:JText::_('VIEWPRODASSIGNAFTERADD'); ?></strong> )
			<span class="editlinktip hasTip" title="<?php echo JText::_('COM_DIGISTORE_PRODSKU_TIP'); ?>" ><img style="float: none; margin: 0px;" src="components/com_digistore/assets/images/icons/tooltip.png" border="0"/></span>
		</td>
	</tr>
	<tr>
		<td></td>
		<td>
			<?php echo JText::_('VIEWPRODPRODNAME');?>
		</td>
		<td>
			<input class="text_area" type="text" name="name" size="30" maxlength="100" value="<?php echo $this->prod->name; ?>" />
			<span class="editlinktip hasTip" title="<?php echo JText::_('COM_DIGISTORE_PRODNAME_TIP'); ?>" ><img style="float: none; margin: 0px;" src="components/com_digistore/assets/images/icons/tooltip.png" border="0"/></span>
		</td>
	</tr>
</table>
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
	$options = array( 'active' => 'general_settings' );
	echo '<div class="tabbable tabs-left">';
	echo JHtml::_( 'obtabs.start', 'product_settings', $options );
	
	echo JHtml::_( 'obtabs.addTab', 'product_settings', 'general_settings', '<i class=\"icon-pencil\"></i>'.(($isJ25)?'':'&nbsp;').'<strong>'.JText::_('DSDETAILS').'</strong>');
	?>
	<fieldset class="adminform">
		<legend><?php echo JText::_('VIEWPRODDET');?></legend>
		<table class="admintable table">
			<tr>
				<td width="20%">
					<?php echo JText::_('VIEWPRODPRODSUBTITLE');?>:
				</td>
				<td>
					<input class="text_area" type="text" name="subtitle" size="30" maxlength="100" value="<?php echo $this->prod->subtitle; ?>" />
					<span class="editlinktip hasTip" title="<?php echo JText::_('COM_DIGISTORE_PRODSUBTITLE_TIP'); ?>" ><img style="float: none; margin: 0px;" src="components/com_digistore/assets/images/icons/tooltip.png" border="0"/></span>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('VIEWPRODPRODCAT');?>:
				</td>
				<td>
					<?php  echo $this->lists['catid']; ?>
					<span class="editlinktip hasTip" title="<?php echo JText::_('COM_DIGISTORE_PRODCATEGS_TIP'); ?>" ><img style="float: none; margin: 0px;" src="components/com_digistore/assets/images/icons/tooltip.png" border="0"/></span>
				</td>
			</tr>
			<tr>
				<td ><?php echo JText::_('VIEWPRODORDERING');?>:</td>
				<td>
					<?php echo $this->lists['ordering']; ?>
					<span class="editlinktip hasTip" title="<?php echo JText::_('COM_DIGISTORE_PRODORDERING_TIP'); ?>" ><img style="float: none; margin: 0px;" src="components/com_digistore/assets/images/icons/tooltip.png" border="0"/></span>
				</td>
			</tr>

			<tr>
				<td ><?php echo JText::_('VIEWPRODPCLASS');//_REQUEST_DOMAIN_REG;?></td>
				<td>
					<?php echo $this->lists['product_class'];?>
					<span class="editlinktip hasTip" title="<?php echo JText::_('COM_DIGISTORE_PRODFAMILY_TIP'); ?>" ><img style="float: none; margin: 0px;" src="components/com_digistore/assets/images/icons/tooltip.png" border="0"/></span>
				</td>
			</tr>

			<tr>
				<td ><?php echo JText::_('VIEWPRODPRODTAXCLASS');//_REQUEST_DOMAIN_REG;?></td>
				<td>
					<?php echo $this->lists['product_tax_class'];?>
					<span class="editlinktip hasTip" title="<?php echo JText::_('COM_DIGISTORE_PRODTAXCLASS_TIP'); ?>" ><img style="float: none; margin: 0px;" src="components/com_digistore/assets/images/icons/tooltip.png" border="0"/></span>
				</td>
			</tr>

			<tr>
				<td><?php echo JText::_('VIEWPRODFEATURED'); ?></td>
				<td>
					<label class="radio">
						<input type="radio" name="featured" id="featured1" value="1" <?php echo (($this->prod->featured == 1)?"checked":"");?> />
						<?php echo JText::_('DSYES'); ?>
					</label>
					<label class="radio">
						<input type="radio" name="featured" id="featured0" value="0" <?php echo (($this->prod->featured == 0 || $this->prod->featured === null)?"checked":"");?> />
						<?php echo JText::_('DSNO'); ?>
					</label>
				</td>
			</tr>
		</table>
	</fieldset>
	
	<fieldset class="adminform">
	<legend><?php echo JText::_('VIEWPRODPRODDESCINFO');?></legend>
		<table width="100%" class="admintable">
			 <tr>
				<td width="100%">
					<?php echo JText::_('VIEWPRODSHORTDESC');?>:
					<span class="editlinktip hasTip" title="<?php echo JText::_('COM_DIGISTORE_PRODSHORTDESC_TIP'); ?>" ><img style="float: none; margin: 0px;" src="components/com_digistore/assets/images/icons/tooltip.png" border="0"/></span>
					<br />
					<textarea id="description" name="description" class="useredactor" style="width:100%;height:150px;"><?php echo $this->prod->description;?></textarea>
				</td>
			</tr>

			<tr>
				<td width="100%">
					<?php echo JText::_('VIEWPRODFULLDSC');?>:
						<span class="editlinktip hasTip" title="<?php echo JText::_('COM_DIGISTORE_PRODFULLDESC_TIP'); ?>" ><img style="float: none; margin: 0px;" src="components/com_digistore/assets/images/icons/tooltip.png" border="0"/></span>
					<br />
					<textarea id="fulldescription" name="fulldescription" class="useredactor" style="width:100%;height:450px;"><?php echo $this->prod->fulldescription;?></textarea>
				</td>
			</tr>
		</table>
	</fieldset>
	<?php
	echo JHtml::_( 'obtabs.endTab' );
	
	echo JHtml::_( 'obtabs.addTab', 'product_settings', 'video-info', '<i class=\"icon-pictures\"></i>'.(($isJ25)?'':'&nbsp;').'<strong>'.JText::_('DIGI_MEDIA_TAB').'</strong>');
	?>
		<fieldset class="adminform">
	
			<legend><?php echo JText::_('VIEWPRODPRODIMAGE');?></legend>
	
			<table width="100%">
				<tr>
					<td class="header_zone">
						<?php
							echo JText::_("HEADER_PRODUCTSIMAGE");
						?>
					</td>
				</tr>
				 <tr>
					<td align="right">
						<a class="modal digi_video" rel="{handler: 'iframe', size: {x: 750, y: 435}}" href="index.php?option=com_digistore&controller=digistoreAbout&task=vimeo&id=38539777">
							<img src="<?php echo JURI::base(); ?>components/com_digistore/assets/images/icon_video.gif" class="video_img" />
							<?php echo JText::_("COM_DIGISTORE_VIDEO_PROD_IMAGE"); ?>
						</a>
					</td>
				</tr>
			</table>
	
			<div>
				<div>
					<input type="button" name="ajaxuploadproductimages" value="Upload Images" id="ajaxuploadproductimages" class="btn btn-small btn-primary" />
					<div id="onAjax" style="display: none;font-weight: bold;margin-left: 20px;width: 300px;">
						<img src="<?php echo JURI::root(); ?>components/com_digistore/assets/images/ajax-loader.gif" />
						Uploading image...
					</div>
				</div>
				<div id="productsthumbnails">
					<?php
					//$prodimages = explode(',\n', $this->prod->prodimages);
					$prodimages = $this->prod->prodimages;
	
					if (!empty($prodimages)) {
					?>
						<div style='padding-top:5px;'>
							<table>
								<tr>
									<td width="3%">&nbsp;</td>
									<td width="2%" align="center" nowrap="nowrap">
										<?php echo JText::_("DIGI_DEFAULT"); ?>
									</td>
									<td width="15%" align="center" nowrap="nowrap">
										<?php echo JText::_("VIEWPRODPRODIMAGE"); ?>
									</td>
									<td width="12%" align="center" nowrap="nowrap">
										<?php echo JText::_("DIGI_IMAGE_TITLE"); ?>
									</td>
									<td width="10%" align="center" nowrap="nowrap">
										<?php echo JText::_("DIGI_ORDER"); ?>
									</td>
									<td>&nbsp;
	
									</td>
								</tr>
							</table>
						</div>
						<?php
						foreach ($prodimages as $key=>$prodimage) {
							if (!empty($prodimage)) {
								if($prodimage["order"] == 0) {
									$prodimage["order"] = 1;
								}
								$uniqid = uniqid(rand(),false);
								$prodimage["title"] = str_replace('"', "&quot;", $prodimage["title"]);
								?>
								<div id='box<?php echo $uniqid; ?>' style='padding-top:5px;'>
									<table>
										<tr>
											<td width="3%">
												<input type="checkbox" name="selected_image[]" value="<?php echo $prodimage["path"]; ?>" />
											</td>
											<td width="4%" align="center">
									<input id='def<?php echo $uniqid; ?>' type='radio' name='default_image' value='<?php echo $prodimage["path"]; ?>' <?php echo ($prodimage["default"] == "1") ? "checked='checked'" : ""; ?>/>
											</td>
											<td width="15%" align="center">
									<a href='<?php echo ImageHelper::GetProductImageURL($prodimage["path"]); ?>' class='modal'>
										<img src='<?php echo ImageHelper::GetProductThumbImageURL($prodimage["path"]); ?>'/>
									</a>
											</td>
											<td width="12%" align="center">
									<input type="text" name="title[]" value="<?php echo $prodimage["title"]; ?>">
											</td>
											<td width="10%" align="center">
									<?php
										if(count($prodimages) == "1"){
									?>
											<input type="text" style="text-align: center;" class="text_area" value="<?php echo $prodimage["order"]; ?>" size="5" name="order[]">
									<?php
										}
										elseif($key == 0){
									?>
											<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
											<span><a title="Move Down" onclick="document.adminForm.forchange.value=<?php echo $prodimage["id"]; ?>; document.adminForm.task.value='move_image_down'; document.adminForm.submit(); " href="#reorder">  <img height="16" width="16" border="0" alt="Move Down" src="<?php echo JURI::root()."administrator/components/com_digistore/assets/images/downarrow.png"; ?>"></a></span>
											<input type="text" style="text-align: center;" class="text_area" value="<?php echo $prodimage["order"]; ?>" size="5" name="order[]">
									<?php
										}
										elseif($key == count($prodimages) -1){
									?>
											<span>
												<a title="Move Up" onclick="document.adminForm.forchange.value=<?php echo $prodimage["id"]; ?>; document.adminForm.task.value='move_image_up'; document.adminForm.submit();" href="#reorder">   <img height="16" width="16" border="0" alt="Move Up" src="<?php echo JURI::root()."administrator/components/com_digistore/assets/images/uparrow.png"; ?>"></a>
											</span>
											<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
											<input type="text" style="text-align: center;" class="text_area" value="<?php echo $prodimage["order"]; ?>" size="5" name="order[]">
									<?php
										}
										else{
									?>
											<span><a title="Move Up" onclick="document.adminForm.forchange.value=<?php echo $prodimage["id"]; ?>; document.adminForm.task.value='move_image_up'; document.adminForm.submit();" href="#reorder">   <img height="16" width="16" border="0" alt="Move Up" src="<?php echo JURI::root()."/administrator/components/com_digistore/assets/images/uparrow.png"; ?>"></a></span>
							<span><a title="Move Down" onclick="document.adminForm.forchange.value=<?php echo $prodimage["id"]; ?>; document.adminForm.task.value='move_image_down'; document.adminForm.submit(); " href="#reorder">  <img height="16" width="16" border="0" alt="Move Down" src="<?php echo JURI::root()."administrator/components/com_digistore/assets/images/downarrow.png"; ?>"></a></span>
											<input type="text" style="text-align: center;" class="text_area" value="<?php echo $prodimage["order"]; ?>" size="5" name="order[]">
									<?php
										}
									?>
											</td>
											<td>
									<a href='javascript:void(0);' onclick='document.getElements("div[id=box<?php echo $uniqid; ?>]").each( function(el) { el.getParent().removeChild(el); }); document.adminForm.tab.value=6; submitbutton("apply")'>Delete</a>
											</td>
										</tr>
									</table>
									<input type='hidden' name='prodimageshidden[]' value='<?php echo $prodimage["path"]; ?>'/>
									<input type='hidden' name='images_ids[]' value='<?php echo $prodimage["id"]; ?>'/>
								</div>
								<br/>
	<?php
							}
						}
					}
	?>
				</div>
			</div>
			<?php
				if (!empty($prodimages)){
			?>
			<input type="button" class="btn btn-small btn-danger" name="delete_selected" onclick="document.adminForm.task.value='delete_selected'; document.adminForm.submit();" value="<?php echo Jtext::_("DIGI_DELETE_SELECTED"); ?>" />
			<input type="button" class="btn btn-small btn-danger" name="delete_all" onclick="document.adminForm.task.value='delete_all'; document.adminForm.submit();" value="<?php echo Jtext::_("DIGI_DELETE_ALL"); ?>" />
			<?php
				}
			?>
	
			<table class="adminform" width="100%" style="display:none;">
	
				<tr>
					<td colspan="2"><?php echo JText::_('VIEWPRODSELIMAGE'); ?></td>
				</tr>
				<tr>
					<td colspan="2">
						<table width="100%">
							<tr>
								<td width="48%">
									<div align="center">
										<?php echo JText::_('VIEWPRODAVAILIMG');?>:
										<br />
										<span id="srcimageselector">
											<?php  echo $this->lists['imagelist'];?>
										</span>
										<br />
										<?php echo JText::_('VIEWPRODCHOOSEFOLDER');?>: <?php  echo $this->lists['folders'];?>
									</div>
								</td>
								<td width="2%">
									<input class="button" type="button" value=">>" onclick="addSelectedToList()" title="Add"/>
									<br/>
									<br/>
									<br/>
									<input class="button" type="button" value="<<" onclick="removeSelectedFromList()" title="Remove"/>
								</td>
								<td width="48%">
									<div align="center">
										<?php echo JText::_('VIEWPRODCURIMAGES');?>:
										<br />
										<?php  echo $this->lists['prodimagelist'];?>
										<br />
										<input class="button" type="button" value="<?php echo JText::_('VIEWPRODMOVEUP');?>" onclick="moveUp()" />
										<input class="button" type="button" value="<?php echo JText::_('VIEWPRODMOVEDOWN');?>" onclick="moveDown()" />
									</div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr valign="top">
					<td>
						<div align="center">
							<?php echo JText::_('VIEWPRODSAMPLEIMAGE');?>:<br/>
							<img name="view_srcimg" src="../images/M_images/blank.png" width="100" />
						</div>
					</td>
					<td valign="top">
						<div align="center">
							<?php echo JText::_('VIEWPRODACTIVEIMAGE');?>:<br/>
							<img name="view_prodimg" src="../images/M_images/blank.png" width="100" />
						</div>
					</td>
				</tr>
	
			</table>
		</fieldset>
		
		<fieldset class="adminform">
		<legend><?php echo JText::_('DIGI_VIDEO');?></legend>
			<table width="100%">
				<tr>
					<td class="header_zone">
						<?php
							echo JText::_("HEADER_PRODUCTSMEDIA");
						?>
					</td>
				</tr>
	
				<tr>
					<td align="right">
						<a class="modal digi_video" rel="{handler: 'iframe', size: {x: 750, y: 435}}" href="index.php?option=com_digistore&controller=digistoreAbout&task=vimeo&id=38539741">
							<img src="<?php echo JURI::base(); ?>components/com_digistore/assets/images/icon_video.gif" class="video_img" />
							<?php echo JText::_("COM_DIGISTORE_VIDEO_PROD_MEDIA"); ?>
						</a>
					</td>
				</tr>
			</table>
	
			<table width="100%" class="admintable">
				 <tr> 
					<td colspan="2">
						<table>
							<tr>
								<td width="15%"> 
									<?php echo JText::_('DIGI_VIDEO_URL');?>
								</td>
								<td>	 
									<input type="text" name="video_url" value="<?php echo $this->prod->video_url; ?>" size="60" class="span9" />
									&nbsp;&nbsp;
									<?php echo "<a rel=\"{handler: 'iframe', size: {x:550,y:350}}\"  class=\"modal\"  href=\"index.php?option=com_digistore&controller=digistoreConfigs&task=supportedsites&tmpl=component\">".JText::_('DIGI_SUPPORTED_SITES')."</a>"; ?>
									&nbsp;&nbsp;
									<span class="editlinktip hasTip" title="<?php echo JText::_('DIGI_TOOLTIP_VIDEO_URL'); ?>" ><img style="float: none; margin: 0px;" src="components/com_digistore/assets/images/icons/tooltip.png" border="0"/></span>
								</td>
							</tr>
						</table>
					</td>
				</tr>
	
				<tr>
					<td colspan="2">
						<table>
							<tr>
								<td width="15%"> 
									<?php echo JText::_('DIGI_VIDEO_SIZE');?>
								</td>
								<td>
									<?php
	
									if(intval($this->prod->video_width) == 0){
										$this->prod->video_width = "500";
									}
	
									if(intval($this->prod->video_height) == 0){
										$this->prod->video_height = "281";
									}
	
									?> 
									<input style="float: none;" type="text" name="video_width" value="<?php echo $this->prod->video_width; ?>" size="10" class="span2" />
									&nbsp;&nbsp;
									x
									&nbsp;&nbsp;
									<input style="float: none;" type="text" name="video_height" value="<?php echo $this->prod->video_height; ?>" size="10" class="span2" />
									&nbsp;&nbsp;
									(<?php echo JText::_("VIEWWIDE"); ?> x <?php echo JText::_("VIEWHEIGH"); ?>)
									&nbsp;&nbsp;
									<span class="editlinktip hasTip" title="<?php echo JText::_('DIGI_TOOLTIP_VIDEO_SIZE'); ?>" >
									<img style="float: none; margin:0px;" src="components/com_digistore/assets/images/icons/tooltip.png" border="0"/></span>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</fieldset>
	<?php
	echo JHtml::_( 'obtabs.endTab' );

	echo JHtml::_( 'obtabs.addTab', 'product_settings', 'pricing_details', '<i class=\"icon-cart\"></i>'.(($isJ25)?'':'&nbsp;').'<strong>'.JText::_('VIEWPRODPORODPRICING').'</strong>');
	?>
	<?php if ($this->prod->domainrequired != 3) { ?>
		<fieldset class="adminform">
	
			<legend><?php echo JText::_('VIEWPRODPORODPRICING_DISPLAY_PRICE_TITLE');?></legend>
	
			<table width="100%">
				<tr>
					<td class="header_zone">
						<?php
							echo JText::_("HEADER_PRODUCTSPRICING");
						?>
					</td>
				</tr>
				<?php
				if($this->producttype == 0 || $this->producttype == 1){
				?>
				<tr>
					<td align="right">
						<a class="modal digi_video" rel="{handler: 'iframe', size: {x: 750, y: 435}}" href="index.php?option=com_digistore&controller=digistoreAbout&task=vimeo&id=38539738">
							<img src="<?php echo JURI::base(); ?>components/com_digistore/assets/images/icon_video.gif" class="video_img" />
							<?php echo JText::_("COM_DIGISTORE_VIDEO_PROD_PRICE_DET"); ?>				  
						</a>
					</td>
				</tr>
				<?php
				}
				?>
			</table>
	
			<table>
				<tr>
					<td>
						<?php echo $this->lists['showqtydropdown']?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo $this->lists['priceformat']; ?>
						<span class="editlinktip hasTip" title="<?php echo JText::_('COM_DIGISTORE_PRODPRICEFORMAT_TIP'); ?>" ><img style="float: none; margin: 0px;" src="components/com_digistore/assets/images/icons/tooltip.png" border="0"/></span>
					</td>
				</tr>
			</table>
	
		</fieldset>
		<?php } ?>
	
		<fieldset class="adminform">
			<legend><?php echo JText::_('VIEWPRODPORODPRICING');?></legend>
			<?php
			$styleheader = " style='font-size:1.2em;font-weight:bold;padding:0.5em;' ";
			$stylerow = " style='padding:0.5em;' ";
			$producttype = $this->producttype;
			if( $producttype != 2 && $producttype != 3 && $producttype != 4 ) {
			?>
				<table>
					<tr>
						<td <?php echo $styleheader; ?>><?php echo JText::_("DIGI_SUBSCRIPTION_PLANS"); ?></td>
					</tr>
					<tr>
						<td>
							<?php echo $this->plains; ?>
						</td>
					</tr>
					<?php if ($this->prod->domainrequired != 3) { ?>
					<tr>
						<td <?php echo $styleheader; ?>><?php echo JText::_("VIEWTREERENEWAL"); ?></td>
					</tr>
					<tr>
						<td>
							<table>
								<tr>
									<td width="1%">
										<input type="checkbox" name="offerplans" id="offerplans" value="1" <?php if($this->prod->offerplans == 1){ echo 'checked="checked"'; } ?>  />
									</td>
									<td>
										<span style="padding-left:3px; line-height:20px;"><?php echo JText::_("COM_DIGISTORE_OFFER"); ?></span>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $this->renewals; ?>
						</td>
					</tr>
					<?php } ?>  
					<?php if ($this->prod->domainrequired != 3) { ?>
					<tr>
						<td <?php echo $styleheader; ?>><?php echo JText::_("DIGI_EMAIL_PLANS"); ?></td>
					</tr>
					<tr>
						<td>
						   <?php echo $this->emails; ?>
						</td>
					</tr>
					<?php } ?>
	
				</table>
			<?php
			}
			else{
				echo $this->plains;
			}
			?>
		</fieldset>
	<?php
	echo JHtml::_( 'obtabs.endTab' );
	
if (!in_array('file',$hidetab)) {
	echo JHtml::_( 'obtabs.addTab', 'product_settings', 'file_details', '<i class=\"icon-download\"></i>'.(($isJ25)?'':'&nbsp;').'<strong>'.JText::_('VIEWPRODFILE').'</strong>');
	// $dispatcher	= JEventDispatcher::getInstance();
	if($isJ25){
		$dispatcher = JDispatcher::getInstance();
	} else {
		$dispatcher	= JEventDispatcher::getInstance();
	}
	JPluginHelper::importPlugin('digistore');
	$html = '';
	// Trigger the data preparation event.
	$dispatcher->trigger( 'onFileTabDisplay', array( 'com_digistore.product_edit' , $this, &$html ) );
	if( $html ) {
		echo $html;
	} elseif( !$html ) {
	?>
	<script type="text/javascript">
		function checkFileRequired(){
			var form = document.adminForm;
			file = document.getElementById("iscurfile");
			ftp_file = document.getElementById("ftpfile");
			
			if ((form.file.value == "")  && ((!file.innerHTML) && (ftp_file.value == ''))) {
				alert( "<?php echo JText::_("VIEWPRODNODOWNLOADFILE");?>" );
				return false;
			} else {
				var ttt = form.file.value;
				ttt= (ttt == '') ? ftp_file.value : ttt;
				ttt= (ttt == '') ? file.innerHTML : ttt;
				ttt = ttt.substr (ttt.length-3, ttt.length) ;
				if (ttt != 'zip' && (form.main_zip_file != undefined && form.main_zip_file.value != '')) {
					alert ("<?php echo JText::_("VIEWPRODISNOTZIP");?>");
					return false;
				} else {
					return true;
					// flag = true;
				}
			}
			return true;
		}
	</script>
		<fieldset class="adminform">
			<legend><?php echo JText::_('VIEWPRODFILE');?></legend>
	
			<table width="100%">
				<tr>
					<td class="header_zone">
						<?php
							echo JText::_("HEADER_PRODUCTSFILE");
						?>
					</td>
				</tr>
			</table>
	
			<table class="admintable">
	
	
				<tr>
					<td valign="top" align="right">
						<?php echo JText::_('VIEWPRODFILE');?>:
					</td>
					<td>
						<input type="file" name="file" />
									<span class="editlinktip hasTip" title="<?php echo JText::_('COM_DIGISTORE_PRODUPLOADFILE_TIP'); ?>" ><img style="float: none; margin: 0px;" src="components/com_digistore/assets/images/icons/tooltip.png" border="0"/></span>
						<br />
						<span style="color:#ff0000;"><em><?php echo sprintf(JText::_("MAXIMUM_UPLOAD"), '<b>'.ini_get("upload_max_filesize").'</b>'); ?></em></span>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right">
						<?php echo JText::_('VIEWPRODUPLFILE');?>:
					</td>
					<td>
						<?php echo $this->lists['ftpfilelist'];?>
									<span class="editlinktip hasTip" title="<?php echo JText::_('COM_DIGISTORE_PRODPRODUCTUPLOADFILE_TIP'); ?>" ><img style="float: none; margin: 0px;" src="components/com_digistore/assets/images/icons/tooltip.png" border="0"/></span>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<?php echo JText::_('VIEWPRODCURFILE');?>:
						<span id="iscurfile"><?php echo $this->prod->file; ?></span>
						<?php
						if(trim($this->prod->file) != "" && !is_file(JPATH_SITE.DS."administrator".DS."components".DS."digistore_product_uploads".DS.$this->prod->id.DS."original".DS.trim($this->prod->file))){
							echo '<br/><span style="color:red;">'.JText::_("DIGI_UPLOAD_FILE_MESSAGE")."</span>";
						}
						?>
					</td>
				</tr>
				<tr>
					<?php if ( isset ( $this->prod->file ) ) {
						?>
	
					<td colspan="2">
						<input name="delete_file" type="checkbox" id="delete_file" value="1"> <?php echo JText::_('VIEWPRODDELETE');?>
					</td>
				</tr>
					<?php }
				?>
			</table>
	
		</fieldset>
		<?php
		}// end if(!$html)
		#echo $pane->endPanel();
		echo JHtml::_( 'obtabs.endTab' );
	} // hide file tab

if (!in_array('package',$hidetab)) {
	echo JHtml::_( 'obtabs.addTab', 'product_settings', 'package_info', '<i class=\"icon-cube\"></i>'.(($isJ25)?'':'&nbsp;').'<strong>'.JText::_('VIEWPRODPRODPACK').'</strong>');
	?>

		<fieldset class="adminform">
	
			<legend><?php echo JText::_('VIEWPRODPACKAGE');?></legend>
	
			<table width="100%">
				<tr>
					<td class="header_zone">
						<?php
							echo JText::_("HEADER_PRODUCTINCLUDE");
						?>
					</td>
				</tr>
				<tr>
					<td align="right">
						<a class="modal digi_video" rel="{handler: 'iframe', size: {x: 750, y: 435}}" href="index.php?option=com_digistore&controller=digistoreAbout&task=vimeo&id=38539775">
							<img src="<?php echo JURI::base(); ?>components/com_digistore/assets/images/icon_video.gif" class="video_img" />
							<?php echo JText::_("COM_DIGISTORE_VIDEO_PROD_INCLUDE"); ?>
						</a>
					</td>
				</tr>
			</table>
	
			<script type="text/javascript">
	
				function grayBoxiJoomla(link_element, width, height){
					SqueezeBox.open(link_element, {
						handler: 'iframe',
						size: {x: width, y: height}
					});
				}
	
				// Add new include item
	
				window.addEvent('domready', function(){
	
					$('buttonaddincludeproduct').addEvent('click', function(e) {
						e.stop()||new Event(e).stop();
	
						var url = "index.php?option=com_digistore&controller=digistoreProducts&task=productincludeitem&no_html=1";
	
						 var req = new Request.HTML({
							method: 'get',
							url: url,
							data: { 'do' : '1' },
							//update: $('productincludes'),
							onComplete: function(transport){
								$('productincludes').adopt(transport);
	
								$$('a.modal').each(function(el) {
									el.addEvent('click', function(e) {
										new Event(e).stop();
										SqueezeBox.fromElement(el);
									});
								});
							}
						}).send();
					});
				});
	
	
				// Remove include item
	
				function remove_product_include( box_id ) {
	
					var box = document.getElementById('product_include_box_' + box_id);
					//var box = box.parentNode;
					while (box.firstChild) {
						box.removeChild( box.firstChild );
					}
	
					// remove wrapper div to include item
					var parent_box = document.getElementById('productincludes');
					parent_box.removeChild(box);
				}
	
	
				function show_plan_for_product_include( product_id, include_id ) {
	
					var plan_box = document.getElementById( 'product_include_subscr_plan_' + include_id );
	
					//plan_box.style = '';
					if(plan_box.style) {
						if(plan_box.style.display == 'none') {
							plan_box.style.display = '';
						}
					}
	
					window.addEvent('domready', function(){
	
						var url = "index.php?option=com_digistore&controller=digistorePlans&task=getPlainsByProductIDSelect&pid="+product_id+"&hid="+include_id+"&no_html=1";
	
						 var req = new Request.HTML({
							method: 'get',
							url: url,
							data: { 'do' : '1' },
							update: $('product_include_subscr_plan_select' + include_id),
							onComplete: function(response){
							}
						}).send();
	
						/*new Ajax(url, {
							method: 'get',
							onComplete: function( response ) {
	
								nd = $('product_include_subscr_plan_select' + include_id);
								nd.innerHTML = response;
							}
						} ).request();*/
	
					});
	
				}
	
			</script>
	
			<div id="productincludes">
	
	<?php foreach($this->include_products as $key => $include) { ?>
	
	<div id="product_include_box_<?php echo $key; ?>" style="border-bottom:1px solid #ccc;margin:15px;padding:10px;">
		<table width="100%">
			<tr>
				<td style="" width="30%"><?php echo JText::_( 'DSPROD' ); ?></td>
				<td style="">
					<div style="float:left">
						<span id="product_include_name_text_<?php echo $key; ?>" style="line-height: 17px;padding: 0.2em; border: 1px solid rgb(204, 204, 204); display: block; width: 250px;"><?php echo $include['name']; ?></span>
						<input type="hidden" value="<?php echo $include['id']; ?>" id="product_include_id<?php echo $key; ?>" name="product_include_id[<?php echo $key; ?>]"/>
					</div>
					<div class="button2-left"><div class="blank" style="padding:0">
						<a rel="{handler: 'iframe', size: {x: 800, y: 600}}" href="index.php?option=com_digistore&controller=digistoreProducts&task=selectProductInclude&id=<?php echo $key; ?>&tmpl=component" title="Select a Product Include" class="modal">Select</a>
					</div></div>
				</td>
				<td style="">
					<a href="javascript:void(0)" id="product_include_remove_1" onclick="remove_product_include('<?php echo $key; ?>');">Remove</a>
				</td>
			</tr>
			<tr id="product_include_subscr_plan_<?php echo $key; ?>" style="">
				<td width="30%"><?php echo JText::_( 'Subcription plan' ); ?></td>
				<td id="product_include_subscr_plan_select<?php echo $key; ?>"><?php echo $include['plans']; ?></td>
				<td></td>
			</tr>
		</table>
	</div>
	
	<?php } ?>
			</div>
	
			<div style="margin:15px;padding:10px;">
				<a id="buttonaddincludeproduct" class="btn btn-small" href="#"><?php echo JText::_('VIEWPRODADDPRODUCT'); ?></a>
			</div>
	
		</fieldset>
	
	<?php
		echo JHtml::_( 'obtabs.endTab' );
	} // end include tab (package)

	echo JHtml::_( 'obtabs.addTab', 'product_settings', 'acl-groups', '<i class=\"icon-users\"></i>'.(($isJ25)?'':'&nbsp;').JText::_('VIEWPRODACLGROUPS',true));
	?>
	<fieldset class="adminform">
		<legend><?php echo JText::_('VIEWPRODPRODACCESSINFO');?></legend>
		<table width="100%" class="admintable">
			<tr>
				<td>
					<?php echo JHtml::_('access.usergroups', 'groups', $this->lists['groups'], true); ?>
				</td>
			</tr>
		</table>
	</fieldset>
	<fieldset class="adminform">
		<legend><?php echo JText::_('Expire');?></legend>
		<table width="100%" class="admintable">
			<tr>
				<td>
					<?php echo JHtml::_('access.usergroups', 'expgroups', $this->lists['expgroups'], true); ?>
				</td>
			</tr>
		</table>
	</fieldset>

	<script>
		jQuery(function() {
			jQuery("#1group_1,#1group_2,#1group_3,#1group_4,#1group_5,#1group_6,#1group_7,#1group_8").closest('li').hide();
			jQuery("#2group_1,#2group_2,#2group_3,#2group_4,#2group_5,#2group_6,#2group_7,#2group_8").closest('li').hide();
		});
	</script>

	<?php
	echo JHtml::_( 'obtabs.endTab' );
	
	echo JHtml::_( 'obtabs.addTab', 'product_settings', 'access_info', '<i class=\"icon-user\"></i>'.(($isJ25)?'':'&nbsp;').JText::_('VIEWPRODPRODACCESSINFO',true));
	?>
		<fieldset class="adminform">
			<legend><?php echo JText::_('VIEWPRODPRODACCESSINFO');?></legend>
			<table width="100%" class="admintable">
				<tr>
					<td width="10%"><?php echo JText::_('VIEWPRODUCTAL');?>: </td>
					<td>
						<?php echo $this->lists['access']; ?>
								<span class="editlinktip hasTip" title="<?php echo JText::_('COM_DIGISTORE_PRODACCESS_TIP'); ?>" ><img style="float: none; margin: 0px;" src="components/com_digistore/assets/images/icons/tooltip.png" border="0"/></span>
					</td>
				</tr>
			</table>
		</fieldset>
	<?php
	echo JHtml::_( 'obtabs.endTab' );
	echo JHtml::_( 'obtabs.addTab', 'product_settings', 'extra_info', '<i class=\"icon-bookmark\"></i>'.(($isJ25)?'':'&nbsp;').JText::_('VIEWPRODPRODEXTRAINFO',true));
	?>
	<script>
	
		function jSelectArticleID(id, title){
			document.getElementById('linktoidvalue').value=id;
			SqueezeBox.close();
		}
	</script>
		<fieldset class="adminform">
			<legend><?php echo JText::_('VIEWPRODPRODEXTRAINFO');?></legend>
			<table width="100%" class="admintable">
				<tr>
					<td width="250">
						<?php echo JText::_('VIEWPRODLINKTOART');?>
								<span class="editlinktip hasTip" title="<?php echo JText::_('COM_DIGISTORE_PRODSHOUDLINKTO_TIP'); ?>" ><img style="float: none; margin: 0px;" src="components/com_digistore/assets/images/icons/tooltip.png" border="0"/></span>
					</td>
					<td nowrap>
						<table>
							<tr>
								<td>
									<input style="float: none;" type="radio" name="articlelinkuse" id="linkno" value="0" <?php echo (($this->prod->articlelinkuse == 0 & $this->prod->articlelinkuse !== null)?"checked":"");?> />&nbsp;<?php echo JText::_('VIEWPRODDONTLINK');?>
								<span class="editlinktip hasTip" title="<?php echo JText::_('COM_DIGISTORE_PRODNOTHING_TIP'); ?>" ><img style="float: none; margin: 0px;" src="components/com_digistore/assets/images/icons/tooltip.png" border="0"/></span>
								</td>
								<td>

								</td>
							</tr>
							<tr>
								<td>
									<input style="float: none;" type="radio" id="linktoid" name="articlelinkuse" value="1" <?php echo (($this->prod->articlelinkuse == 1)?"checked":"");?> />&nbsp;<?php echo JText::_('VIEWPRODCID'); ?>
								<span class="editlinktip hasTip" title="<?php echo JText::_('COM_DIGISTORE_PRODLINKUSE_TIP'); ?>" ><img style="float: none; margin: 0px;" src="components/com_digistore/assets/images/icons/tooltip.png" border="0"/></span>
								</td>
								<td>
									<input type="text" id="linktoidvalue" onkeyup="updateCheckbox('linktoid');" value="<?php echo (isset($this->prod->articlelinkid)&&$this->prod->articlelinkid>0?$this->prod->articlelinkid:"");?>" name="articlelinkid" /> <a class="modal btn hasTooltip" title="" href="index.php?option=com_content&amp;view=articles&amp;layout=modal&amp;tmpl=component&amp;function=jSelectArticleID" rel="{handler: 'iframe', size: {x: 800, y: 450}}" data-original-title="**Select or Change article**"><?php echo JText::_('VIEWPRODSHOWALLCI');?></a>
								</td>
							</tr>
							<tr>
								<td>
									<input type="radio" id="linktourl" name="articlelinkuse" value="2" <?php echo (($this->prod->articlelinkuse == 2)?"checked":"");?> />&nbsp;<?php echo JText::_('VIEWPRODEXTURL');?>
								<span class="editlinktip hasTip" title="<?php echo JText::_('COM_DIGISTORE_PRODLINKUSEURL_TIP'); ?>" ><img style="float: none; margin: 0px;" src="components/com_digistore/assets/images/icons/tooltip.png" border="0"/></span>
								</td>
								<td>
									<input type="text" id="linktourlvalue" onkeyup="updateCheckbox('linktourl');" size="60" value="<?php echo (isset($this->prod->articlelink)?$this->prod->articlelink:"http://");?>" name="articlelink" />
								</td>
							</tr>
							<tr>
								<td>
									<input style="float: none;" type="radio" name="articlelinkuse" id="linkpage" value="3" <?php echo (($this->prod->articlelinkuse == 3 || $this->prod->articlelinkuse === null)?"checked":"");?> />&nbsp;<?php echo JText::_('VIEWPRODLINKTOPAGE');?>
								<span class="editlinktip hasTip" title="<?php echo JText::_('COM_DIGISTORE_PRODLINKPAGE_TIP'); ?>" ><img style="float: none; margin: 0px;" src="components/com_digistore/assets/images/icons/tooltip.png" border="0"/></span>
								</td>
								<td>

								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>

			<table width="100%" class="admintable">
				<tr>
					<td width="250">
						<?php echo JText::_('CART_LINK_USE_SECTION');?>
					</td>
					<td nowrap>
						<table>
							<tr>
								<td>
									<input style="float: none;" type="radio" name="cartlinkuse" id="cartlinkuse0" value="0" <?php echo (($this->prod->cartlinkuse == 0 & $this->prod->cartlinkuse !== null)?"checked":"");?> />&nbsp;<?php echo JText::_('CART_LINK_USE_ADD_TO_CART');?>
								<span class="editlinktip hasTip" title="<?php echo JText::_('CART_LINK_USE_ADD_TO_CART_TIP'); ?>" ><img style="float: none; margin: 0px;" src="components/com_digistore/assets/images/icons/tooltip.png" border="0"/></span>
								</td>
								<td>

								</td>
							</tr>
							<tr>
								<td>
									<input type="radio" id="cartlinkuse1" name="cartlinkuse" value="1" <?php echo (($this->prod->cartlinkuse == 1)?"checked":"");?> />&nbsp;<?php echo JText::_('CART_LINK_USE_LINK_URL');?>
								<span class="editlinktip hasTip" title="<?php echo JText::_('CART_LINK_USE_LINK_URL_TIP'); ?>" ><img style="float: none; margin: 0px;" src="components/com_digistore/assets/images/icons/tooltip.png" border="0"/></span>
								</td>
								<td>
									<input type="text" id="cartlink" size="60" value="<?php echo (isset($this->prod->cartlink) & $this->prod->cartlink!=''?$this->prod->cartlink:"http://");?>" name="cartlink" />
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</fieldset>
	<?php
	echo JHtml::_( 'obtabs.endTab' );

	echo JHtml::_( 'obtabs.addTab', 'product_settings', 'meta_info', '<i class=\"icon-flag\"></i>'.(($isJ25)?'':'&nbsp;').JText::_('VIEWPRODPRODMETAINFO',true));
	?>
	<fieldset class="adminform">
		<legend><?php echo JText::_('VIEWPRODPRODMETAINFO');?></legend>
		<table width="100%">
			<tr>
				<td class="header_zone">
					<?php
						echo JText::_("HEADER_PRODUCTSMETA");
					?>
				</td>
			</tr>
			<tr>
				<td align="right">
					<a class="modal digi_video" rel="{handler: 'iframe', size: {x: 750, y: 435}}" href="index.php?option=com_digistore&controller=digistoreAbout&task=vimeo&id=38539743">
						<img src="<?php echo JURI::base(); ?>components/com_digistore/assets/images/icon_video.gif" class="video_img" />
						<?php echo JText::_("COM_DIGISTORE_VIDEO_PROD_META_RELATED"); ?>
					</a>
				</td>
			</tr>
		</table>
		<table width="100%">
			<tr>
				<td valign="top">
					<table class="admintable">

						<tr>
							<td valign="top" align="right">
								<?php echo JText::_('VIEWPRODPRODMETATITLE'); ?>:
							</td>
							<td>
								<input type="text" name="metatitle" value="<?php echo $this->prod->metatitle ; ?>" size="80"/>
								<span class="editlinktip hasTip" title="<?php echo JText::_('COM_DIGISTORE_PRODMETATITLE_TIP'); ?>" ><img style="float: none; margin: 0px;" src="components/com_digistore/assets/images/icons/tooltip.png" border="0"/></span>
							</td>
						</tr>

						<tr>
							<td valign="top" align="right">
								<?php echo JText::_('VIEWPRODMETAKEY'); ?>:
							</td>
							<td>
								<textarea name="metakeywords" cols="50" rows="10"><?php echo $this->prod->metakeywords ; ?></textarea>
								<span class="editlinktip hasTip" title="<?php echo JText::_('COM_DIGISTORE_PRODMETAKEYS_TIP'); ?>" ><img style="float: none; margin: 0px;" src="components/com_digistore/assets/images/icons/tooltip.png" border="0"/></span>
							</td>
						</tr>

						<tr>
							<td valign="top" align="right">
								<?php echo JText::_('VIEWPRODMETADESC'); ?>:
							</td>
							<td>
								<textarea name="metadescription" cols="50" rows="10"><?php echo $this->prod->metadescription; ?></textarea>
								<span class="editlinktip hasTip" title="<?php echo JText::_('COM_DIGISTORE_PRODMETADESC_TIP'); ?>" ><img style="float: none; margin: 0px;" src="components/com_digistore/assets/images/icons/tooltip.png" border="0"/></span>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</fieldset>
	<?php
	echo JHtml::_( 'obtabs.endTab' );

	
	echo JHtml::_( 'obtabs.addTab', 'product_settings', 'publishing_details', '<i class=\"icon-calendar\"></i>'.(($isJ25)?'':'&nbsp;').JText::_('VIEWPRODPRODPUBDET',true));
	?>
	<fieldset class="adminform">
		<legend><?php echo JText::_('VIEWPRODPRODPUBDET');?></legend>

		<table width="100%">
			<tr>
				<td class="header_zone">
					<?php
						echo JText::_("HEADER_PRODUCTSPUBLISH");
					?>
				</td>
			</tr>
		</table>

		<table class="admintable">
			<tr>
				<td valign="top" align="right">
					<?php echo JText::_('VIEWPRODPUBLISHED');?>:
				</td>
				<td>
					<input type="checkbox" name="published" value="1" <?php  echo $this->prod->published || $this->prod->published === null ? 'checked="checked"' : ''; ?> />
								<span class="editlinktip hasTip" title="<?php echo JText::_('COM_DIGISTORE_PRODPUBLISH_TIP'); ?>" ><img style="float: none; margin: 0px;" src="components/com_digistore/assets/images/icons/tooltip.png" border="0"/></span>
				</td>
			</tr>
			<tr >
				<td valign="top" align="right">
					<?php echo JText::_('VIEWPRODSTART');?>:
				</td>
				<td>
					<?php echo JHTML::_("calendar", $this->prod->publish_up > 0 ? date("Y-m-d", $this->prod->publish_up) : date("Y-m-d"), 'publish_up', 'publish_up'); ?>
								<span class="editlinktip hasTip" title="<?php echo JText::_('COM_DIGISTORE_PRODPUSTARTBLISH_TIP'); ?>" ><img style="float: none; margin: 0px;" src="components/com_digistore/assets/images/icons/tooltip.png" border="0"/></span>
				</td>
			</tr>
			<tr >
				<td valign="top" align="right">
					<?php echo JText::_('VIEWPRODEND');?>:
				</td>
				<td>
					<?php echo JHTML::_("calendar", ($this->prod->publish_down>0?date("Y-m-d", $this->prod->publish_down):"Never"), 'publish_down', 'publish_down'); ?>
								<span class="editlinktip hasTip" title="<?php echo JText::_('COM_DIGISTORE_PRODPUENDBLISH_TIP'); ?>" ><img style="float: none; margin: 0px;" src="components/com_digistore/assets/images/icons/tooltip.png" border="0"/></span>
				</td>
			</tr>
			<tr>
				<td valign="top" align="right">
					<?php echo JText::_('VIEWPRODHIDDEN');?>:
				</td>
				<td>
					<input type="checkbox" name="hide_public" value="1" <?php  echo $this->prod->hide_public || $this->prod->hide_public === null ? 'checked="checked"' : ''; ?> />
								<span class="editlinktip hasTip" title="<?php echo JText::_('COM_DIGISTORE_PRODHIDDEN_TIP'); ?>" ><img style="float: none; margin: 0px;" src="components/com_digistore/assets/images/icons/tooltip.png" border="0"/></span>
				</td>
			</tr>
		</table>
	</fieldset>
	<?php
	echo JHtml::_( 'obtabs.endTab' );

if (!in_array('shipping',$hidetab)) {
	echo JHtml::_( 'obtabs.addTab', 'product_settings', 'shipping_info', '<i class=\"icon-location\"></i>'.(($isJ25)?'':'&nbsp;').JText::_('VIEWPRODPRODSHIPPING',true));
	$shippingtype = ((isset ($this->prod->shippingtype)) && ($this->prod->shippingtype == 1))? '%': $this->configs->currency ;
	?>
	<fieldset class="adminform">
		<legend><?php echo JText::_('VIEWPRODSHOPDET');?></legend>
		<table width="100%">
			<tr>
				<td class="header_zone">
					<?php
						echo JText::_("HEADER_PRODUCTSSHIPPING");
					?>
				</td>
			</tr>
		</table>

		<table class="admintable">
			<tr>
				<td>
					<script language="javascript">
						function updateShippingDelimiters(val) {
							delims = document.getElementsByName("shippingdelimiters");
							for (i = 0; i < delims.length; i++) delims[i].innerHTML = val;
							return;

						}
					</script>
					<table class="adminform" >
						<tr>
							<td colspan="2"><?php echo JText::_('VIEWPRODCOSTCALC'); ?></td>
							<td><?php echo JText::_('VIEWPRODDESCRIPTION'); ?></td>
						</tr>
						<tr>
							<td>
								<input type="radio" value="0" name="shippingtype" <?php echo ($this->prod->shippingtype == 0?"checked":"");?> onclick="updateShippingDelimiters('<?php echo $this->configs->currency;?>');" />
							</td>

							<td><?php echo JText::_('VIEWPRODFLATAMOUNT'); ?></td>
							<td><?php echo JText::_('VIEWPRODFLATAMOUNTDESC'); ?></td>
						</tr>
						<tr>
							<td>
								<input type="radio" value="1" name="shippingtype" <?php echo ($this->prod->shippingtype == 1?"checked":"");?> onclick="updateShippingDelimiters('%');" />
							</td>
							<td><?php echo JText::_('VIEWPRODPRECENTAGE'); ?></td>
							<td><?php echo JText::_('VIEWPRODPRECENTAGEDESC'); ?></td>
						</tr>
					</table>
				</td>
				<td><h3><?php echo JText::_('VIEWPRODHELPLINKS'); ?></h3><br/>
					<a href="http://www.ijoomla.com/redirect/digistore/usps.htm" target="_blank" ><?php echo JText::_('VIEWPRODUSPSSC');
						;?></a><br />
					<a href="http://www.ijoomla.com/redirect/digistore/usp.htm" target="_blank" ><?php echo JText::_('VIEWPRODUSPSC');
						;?></a><br />
					<a href="http://www.ijoomla.com/redirect/digistore/fedex.htm" target="_blank"><?php echo JText::_('VIEWPRODFEDEX');
						;?></a><br />
				</td>
			</tr>
			<tr>
				<td colspan="3" style="text-align:left">
					<table >
						<tr>
							<td nowrap="nowrap"><?php echo JText::_('VIEWPRODEVERYWHEREINMYSTATE'); ?></td>
							<td nowrap="nowrap">
								<input type="text" value="<?php echo (isset ($this->prod->shippingvalue0)?$this->prod->shippingvalue0:"0");?>" name="shippingvalue0" /> <span id="shippingdelimiters" name="shippingdelimiters"><?php echo $shippingtype;?></span>
								<span class="editlinktip hasTip" title="<?php echo JText::_('COM_DIGISTORE_PRODEVERYWHERESTATE_TIP'); ?>" ><img style="float: none; margin: 0px;" src="components/com_digistore/assets/images/icons/tooltip.png" border="0"/></span>
							</td>
						</tr>
						<tr>
							<td nowrap="nowrap"><?php echo JText::_('VIEWPRODEVERYWHEREINMYCOUNTRY'); ?></td>
							<td nowrap="nowrap">
								<input type="text" value="<?php echo (isset ($this->prod->shippingvalue1)?$this->prod->shippingvalue1:"0");?>" name="shippingvalue1" /> <span id="shippingdelimiters" name="shippingdelimiters"><?php echo $shippingtype;?></span>
								<span class="editlinktip hasTip" title="<?php echo JText::_('COM_DIGISTORE_PRODEVERYWHERECOUNTRY_TIP'); ?>" ><img style="float: none; margin: 0px;" src="components/com_digistore/assets/images/icons/tooltip.png" border="0"/></span>
							</td>
						</tr>
						<tr>
							<td nowrap="nowrap"><?php echo JText::_('VIEWPRODINOTHERCOUNTRY'); ?></td>
							<td nowrap="nowrap">
								<input type="text" value="<?php echo (isset ($this->prod->shippingvalue2)?$this->prod->shippingvalue2:"0");?>" name="shippingvalue2" /> <span id="shippingdelimiters" name="shippingdelimiters"><?php echo $shippingtype;?></span>
								<span class="editlinktip hasTip" title="<?php echo JText::_('COM_DIGISTORE_PRODEVERYWHEREOTHERCOUNTRY_TIP'); ?>" ><img style="float: none; margin: 0px;" src="components/com_digistore/assets/images/icons/tooltip.png" border="0"/></span>
							</td>
						</tr>

					</table>

			</tr>
		</table>
	</fieldset>
	<?php
	echo JHtml::_( 'obtabs.endTab' );
} // hide shipping tab

if (!in_array('attribute',$hidetab)) {
	echo JHtml::_( 'obtabs.addTab', 'product_settings', 'attributes_info', '<i class=\"icon-color-palette\"></i>'.(($isJ25)?'':'&nbsp;').JText::_('VIEWPRODPRODATTR',true));
	?>
	<script language="javascript">
		function changefield (id, type) {
			var img = type + 'img'+ id.toString();
			var ident = type + id.toString();
			var green = "templates/isis/images/admin/tick.png";
			var red = "templates/isis/images/admin/publish_x.png";
			var current = document.getElementById(ident);
			if (current.value == "1") {
				current.value = "0";
				document.getElementById(img).src = red;
			} else {
				current.value = "1";
				document.getElementById(img).src = green;
			}
		}
	</script>
	<fieldset class="adminform">
		<legend><?php echo JText::_('VIEWPRODFIELDS');?></legend>

		<table width="100%" class="table">
			<tr>
				<td class="header_zone">
					<?php
						echo JText::_("HEADER_PRODUCTSATRIBUTES");
					?>
				</td>
			</tr>
		</table>
		
		<table class="admintable table table-striped">
			<thead>
			<tr>
				<th valign="top"  >
					<?php echo JText::_('VIEWPRODFIELDNAME');
					;?>
				</th>
				<th valign="top"  >
					<?php echo JText::_('VIEWPRODFIELDINCLUDE');
					;?>
				</th>
				<th valign="top"  >
					<?php echo JText::_('VIEWPRODFIELDMANDATORY');
					;?>
				</th>
			</tr>
			</thead>
			<?php
			foreach ($this->fields as $i => $field) {
				$img_pub = ($field->publishing == 1)?$img_pub = "tick.png": $img_pub = "publish_x.png";
				$alt_pub = ($field->publishing == 1)?$alt_pub = "Included": $alt_pub = "Not Included";
				$img_mand = ($field->mandatory == 1)?$img_mand = "tick.png": $img_mand = "publish_x.png";
				$alt_mand = ($field->mandatory == 1)?$alt_mand = "Required": $alt_mand = "Not Required";
				?>
			<tr>
				<td>
					<strong><?php echo $field->name; ?></strong>&nbsp;<span class="small"><?php echo $field->options; ?></span>
					<input type="hidden" name="fieldid[]" value="<?php echo $field->id?>" />
				</td>
				<td>
					<a href="javascript: void(0);" onclick="changefield(<?php echo $field->id;?>, 'pub');" >
						<img id="pubimg<?php echo $field->id;?>" src="templates/isis/images/admin/<?php  echo $img_pub;?>" width="12" height="12" border="0" alt="<?php  echo $alt_pub; ?>" />
					</a>
					<input type="hidden" name="pub<?php echo $field->id;?>" id="pub<?php echo $field->id;?>" value="<?php echo ($field->publishing == 1)?"1":"0";?>" />
				</td>
				<td>
					<a href="javascript: void(0);" onclick="changefield(<?php echo $field->id;?>, 'mand');" >
						<img id="mandimg<?php echo $field->id;?>" src="templates/isis/images/admin/<?php  echo $img_mand;?>" width="12" height="12" border="0" alt="<?php  echo $alt_mand; ?>" />
					</a>
					<input type="hidden" name="mand<?php echo $field->id;?>" id="mand<?php echo $field->id;?>" value="<?php echo ($field->mandatory == 1)?"1":"0"; ?>" />
				</td>
			</tr>
				<?php
			}
			?>
		</table>
	</fieldset>
	
	<!-- Go to Attribute Manager -->
	<a class="btn" href="index.php?option=com_digistore&controller=digistoreAttributes"><i class="icon-arrow-right"></i>&nbsp;<?php echo JText::_('DSATTRMAN'); ?></a>
	<?php
	echo JHtml::_( 'obtabs.endTab' );
} // hide attribute tab

	echo JHtml::_( 'obtabs.addTab', 'product_settings', 'email_info', '<i class=\"icon-mail\"></i>'.(($isJ25)?'':'&nbsp;'). JText::_('VIEWPRODEMAIL',true));
	?>
	<fieldset class="adminform">
		<legend><?php echo JText::_('VIEWPRODEMAILS');?></legend>

		<table width="100%">
			<tr>
				<td class="header_zone">
					<?php
						echo JText::_("HEADER_PRODUCTSEMAIL");
					?>
				</td>
			</tr>
			<tr>
				<td align="right">
					<a class="modal digi_video" rel="{handler: 'iframe', size: {x: 750, y: 435}}" href="index.php?option=com_digistore&controller=digistoreAbout&task=vimeo&id=38539740">
						<img src="<?php echo JURI::base(); ?>components/com_digistore/assets/images/icon_video.gif" class="video_img" />
						<?php echo JText::_("COM_DIGISTORE_VIDEO_PROD_EMAIL"); ?>
					</a>
				</td>
			</tr>
		</table>

		<table class="adminlist table">
			<tr><td>
					<?php

					echo "<b><br><br>" . (JText::_('VIEWPRODAVAILABLEVARIABLES')) . "</b>";
					?>

								<span class="editlinktip hasTip" title="<?php echo JText::_('COM_DIGISTORE_PRODAVAILABEVARS_TIP'); ?>" ><img style="float: none; margin: 0px;" src="components/com_digistore/assets/images/icons/tooltip.png" border="0"/></span>
					<br />
				</td></tr>
			<tr><td>
					<table border=0>
						<tr>
							<td width=200><?php echo JText::_('VIEWPRODVARSITENAME'); ?></td>
							<td width=200><b>[SITENAME]</b></td>
						</tr>
						<tr>
							<td width=200><?php echo JText::_('VIEWPRODVARSITEURL'); ?></td>
							<td width=200><b>[SITEURL]</b></td>
						</tr>
						<tr>
							<td width=200><?php echo JText::_('VIEWPRODCUSTUSERNAME'); ?></td>
							<td width=200><b>[CUSTOMER_USER_NAME]</b></td>
						</tr>

						<tr>
							<td width=200><?php echo JText::_('VIEWPRODCVARCFN'); ?></td>
							<td width=200><b>[CUSTOMER_FIRST_NAME]</b></td>
						</tr>
						<tr>
							<td width=200><?php echo JText::_('VIEWPRODCVARCLN'); ?></td>
							<td width=200><b>[CUSTOMER_LAST_NAME]</b></td>
						</tr>
						<tr>
							<td width=200><?php echo JText::_('VIEWPRODVARCEMAIL');?></td>
							<td width=200><b>[CUSTOMER_EMAIL]</b></td>
						</tr>
						<tr>
							<td width=200><?php echo JText::_('VIEWPRODVARTODDATE'); ?></td>
							<td width=200><b>[TODAY_DATE]</b></td>
						</tr>
						<tr>
							<td width=200><?php echo JText::_('VIEWPRODPRODUCTNAME'); ?></td>
							<td width=200><b>[PRODUCT_NAME]</b></td>
						</tr>
						<tr>
							<td width=200><?php echo JText::_('VIEWPRODPRODUCTATTR'); ?></td>
							<td width=200><b>[ATTRIBUTES]</b></td>
						</tr>
						<tr>
							<td width=200><?php echo JText::_('VIEWCONFIGTEMPLATECUSTCOMPANY'); ?></td>
							<td width=200><b>[CUSTOMER_COMPANY_NAME]</b></td>
						</tr>
					</table>
				</td></tr>

			<tr>
				<td nowrap>
					<?php echo JText::_('VIEWPRODSENDEMAIL'); ?>: <?php echo JText::_('VIEWPRODSENDEMAILYES');?> 
						<input style="float:none;" type="radio" name="sendmail" value="1" <?php echo (($this->prod->sendmail=="1")?"checked":"");?> />
					<?php echo JText::_('VIEWPRODSENDEMAILNO');?> 
						<input style="float:none;" type="radio" name="sendmail" value="0" <?php echo (($this->prod->sendmail!="1")?"checked":"");?> />
								<span class="editlinktip hasTip" title="<?php echo JText::_('COM_DIGISTORE_PRODSENDEMAIL_TIP'); ?>" ><img style="float: none; margin: 0px;" src="components/com_digistore/assets/images/icons/tooltip.png" border="0"/></span>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo "<b><br>".JText::_('VIEWPRODCONFEMAIL')."</b><br>"; ?>
					<?php echo JText::_('VIEWPRODSUBJ'); ?>: <input style="float:none;" type="text" value="<?php echo $this->prod->productemailsubject;?>" name="productemailsubject" size="60"/>
								<span class="editlinktip hasTip" title="<?php echo JText::_('COM_DIGISTORE_PRODSUBJECTEMAIL_TIP'); ?>" ><img style="float: none; margin: 0px;" src="components/com_digistore/assets/images/icons/tooltip.png" border="0"/></span>
				</td>
			</tr>
			<tr>
				<td style="width: 100%;">
					<textarea id="productemail" name="productemail" class="useredactor" style="width:100%;height:450px;"><?php echo $this->prod->productemail;?></textarea>
				</td>
			</tr>
		</table>


	</fieldset>
	<?php
	echo JHtml::_( 'obtabs.endTab' );

if (!in_array('stock',$hidetab)) {
	echo JHtml::_( 'obtabs.addTab', 'product_settings', 'stock_info', '<i class=\"icon-database\"></i>'.(($isJ25)?'':'&nbsp;').JText::_('VIEWPRODSTOCK',true));
?>
	<fieldset class="adminform">
		<legend><?php echo JText::_('VIEWPRODSTOCK1');?></legend>

		<table width="100%">
			<tr>
				<td class="header_zone">
					<?php
						echo JText::_("HEADER_PRODUCTSTOK");
					?>
				</td>
			</tr>
		</table>

		<script type="text/javascript" language="javascript" >
			<!--
			function addToStock () {
				var amount = document.getElementById('addstock').value;
				var stocktext = document.getElementById('stocktext');
				var stockmes = document.getElementById('stockmes');
				var stock = document.getElementById('stock');
				var leftstock = document.getElementById('leftstock')
				amount = parseInt(amount);
				if ((parseInt(stocktext.innerHTML)) + amount < 0 || isNaN(amount)) {
					stockmes.innerHTML = " <?php echo JText::_('VIEWPRODINVALIDSTOCKAMOUNT'); ?>";
				} else {
					stocktext.innerHTML = parseInt(stocktext.innerHTML) + amount;
					leftstock.innerHTML = parseInt(leftstock.innerHTML) + amount;
					stockmes.innerHTML = " <?php echo JText::_('VIEWPRODSHOODHAVEPROD'); ?>";
					stock.value = parseInt(stock.value) + amount;
				}
				document.getElementById('addstock').value = '';
			}
			//-->
		</script>

		<table class="adminlist table">
			<tr>
				<td valign="top" align="right">
					<?php echo JText::_('VIEWPRODUSESTOCK');?>:
				</td>
				<td>
					<select name="usestock">
						<option value="0" <?php echo (($this->prod->usestock == "0" || !$this->prod->usestock)?"selected":""); ?> ><?php echo JText::_('VIEWPRODSENDEMAILNO'); ?> </option>
						<option value="1" <?php echo (($this->prod->usestock == "1" )?"selected":""); ?> ><?php echo JText::_('VIEWPRODSENDEMAILYES');?> </option>
					</select>
					<span class="editlinktip hasTip" title="<?php echo JText::_('COM_DIGISTORE_PRODVIEWSTOK_TIP'); ?>" ><img style="float: none; margin: 0px;" src="components/com_digistore/assets/images/icons/tooltip.png" border="0"/></span>
				</td>
			</tr>
			<tr>
				<td valign="top" align="right">
					<?php echo JText::_('VIEWPRODADDTOSTOCK');?>:
				</td>
				<td>
					<input type="text" id="addstock" value="" /><input type="button" class="btn" value="<?php echo JText::_('VIEWPRODADDITEMS');?>" onclick="addToStock();" />
					You may also add negative amounts
					<span class="editlinktip hasTip" title="<?php echo JText::_('COM_DIGISTORE_PRODADDTOSTOK_TIP'); ?>" ><img style="float: none; margin: 0px;" src="components/com_digistore/assets/images/icons/tooltip.png" border="0"/></span>
				</td>
			</tr>
			<tr>
				<td valign="top" align="right">
					<?php echo JText::_('VIEWPRODTOTALINSTOCK');?>:
				</td>
				<td>
					<span id="stocktext"><?php echo $this->prod->stock;?></span><span id="stockmes" ></span>
					<input type="hidden" id="stock" name="stock" value="<?php echo $this->prod->stock;?>" />
					<span class="editlinktip hasTip" title="<?php echo JText::_('COM_DIGISTORE_PRODTOTALSTOCK_TIP'); ?>" ><img style="float: none; margin: 0px;" src="components/com_digistore/assets/images/icons/tooltip.png" border="0"/></span>
				</td>
			</tr>
			<tr>
				<td valign="top" align="right">
					<?php echo JText::_('VIEWPRODUSEDINSTOCK');?>:
				</td>
				<td>
					<?php echo $this->prod->used;?>
					<span class="editlinktip hasTip" title="<?php echo JText::_('COM_DIGISTORE_PRODUSEDSTOCK_TIP'); ?>" ><img style="float: none; margin: 0px;" src="components/com_digistore/assets/images/icons/tooltip.png" border="0"/></span>
				</td>
			</tr>
			<tr>
				<td valign="top" align="right">
					<?php echo JText::_('VIEWPRODREMAININGINSTOCK');?>:
				</td>
				<td>
					<span id="leftstock"><?php echo ($this->prod->stock - $this->prod->used);?></span>
					<span class="editlinktip hasTip" title="<?php echo JText::_('COM_DIGISTORE_PRODREMAININGSTOCK_TIP'); ?>" ><img style="float: none; margin: 0px;" src="components/com_digistore/assets/images/icons/tooltip.png" border="0"/></span>
				</td>
			</tr>
			<tr>
				<td valign="top" align="right">
					<?php echo JText::_('VIEWPRODSOLDOUTACTION');?>:
				</td>
				<td nowrap>
					<input type="radio" name="emptystockact" value="0" <?php echo ($this->prod->emptystockact == "0" || !$this->prod->emptystockact)?"checked":"";?> /> <?php echo JText::_('VIEWPRODSOLDOUTACTIONSAN');?>
					<span class="editlinktip hasTip" title="<?php echo JText::_('COM_DIGISTORE_PRODSTOCKDISPLNORMAL_TIP'); ?>" ><img style="float: none; margin: 0px;" src="components/com_digistore/assets/images/icons/tooltip.png" border="0"/></span>
					<br />
					<input type="radio" name="emptystockact" value="1" <?php echo ($this->prod->emptystockact == "1" )?"checked":"";?> /> <?php echo JText::_('VIEWPRODSOLDOUTACTIONDSOW');?>
					<span class="editlinktip hasTip" title="<?php echo JText::_('COM_DIGISTORE_PRODSTOCKSOLDOUT_TIP'); ?>" ><img style="float: none; margin: 0px;" src="components/com_digistore/assets/images/icons/tooltip.png" border="0"/></span>
					<br />
					<input type="radio" name="emptystockact" value="2" <?php echo ($this->prod->emptystockact == "2" )?"checked":"";?> /> <?php echo JText::_('VIEWPRODSOLDOUTACTIONDND'); ?>
					<span class="editlinktip hasTip" title="<?php echo JText::_('COM_DIGISTORE_PRODSTOCKNOTDISPLAY_TIP'); ?>" ><img style="float: none; margin: 0px;" src="components/com_digistore/assets/images/icons/tooltip.png" border="0"/></span>
					<br />
				</td>
			</tr>
			<tr>
				<td valign="top" align="right">
					<?php echo JText::_('VIEWPRODSHOWREMAINSTOCK');?>:
				</td>
				<td>
					<select name="showstockleft">
						<option value="0" <?php echo (($this->prod->showstockleft == "0" || !$this->prod->showstockleft)?"selected":""); ?> ><?php echo JText::_('VIEWPRODSENDEMAILNO'); ?></option>
						<option value="1" <?php echo (($this->prod->showstockleft == "1" )?"selected":""); ?> ><?php echo JText::_('VIEWPRODSENDEMAILYES'); ?></option>
					</select>
					<span class="editlinktip hasTip" title="<?php echo JText::_('COM_DIGISTORE_PRODSHOWREMAINING_TIP'); ?>" ><img style="float: none; margin: 0px;" src="components/com_digistore/assets/images/icons/tooltip.png" border="0"/></span>
				</td>
			</tr>
		</table>
	</fieldset>
<?php
	echo JHtml::_( 'obtabs.endTab' );
} // hide stock tab



echo JHtml::_( 'obtabs.addTab', 'product_settings', 'mailchimp', '<i class=\"icon-mail-2\"></i>'.(($isJ25)?'':'&nbsp;').JText::_('DIGI_MAILCHIMP',true));
?>
	<fieldset class="adminform">
		<legend><?php echo JText::_('DIGI_MAILCHIMP');?></legend>
		<table>
			<tr>
				<td align="right">
					<a class="modal digi_video" rel="{handler: 'iframe', size: {x: 750, y: 435}}" href="index.php?option=com_digistore&controller=digistoreAbout&task=vimeo&id=38539781">
						<img src="<?php echo JURI::base(); ?>components/com_digistore/assets/images/icon_video.gif" class="video_img" />
						<?php echo JText::_("COM_DIGISTORE_VIDEO_PROD_MAILCHIMP"); ?>
					</a>
				</td>
			</tr>
		</table>
		<table width="100%" class="admintable">
			<tr>
				<td width="20%">
					<?php
						echo JText::_("DIGI_MAILCHIMP_API");
					?>
				</td>
				<td>
					<input style="float:none;" type="text" name="mailchimpapi" value="<?php echo $this->prod->mailchimpapi; ?>">
				</td>
			</tr>

			<tr>
				<td width="20%">
					<?php
						echo JText::_("DIGI_MAILCHIMP_LIST");
					?>
				</td>
				<td width="10%">
					<input style="float:none;" type="text" name="mailchimplist" value="<?php echo $this->prod->mailchimplist; ?>">
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_("DIGI_GROUP_ID"); ?>
				</td>
				<td>
					<input style="float:none;" type="text" name="mailchimpgroupid" value="<?php echo $this->prod->mailchimpgroupid; ?>">
				</td>
			</tr>

			<tr>
				<td width="20%">
					<?php
						echo JText::_("DIGI_MAILCHIMP_AUTOREGISTER");
					?>
				</td>
				<td colspan="2">
					<?php
						$mailchimpregister = isset($this->prod->mailchimpregister) ? $this->prod->mailchimpregister : "0";
					?>
					<input style="float:none;" type="radio" name="mailchimpregister" value="0" <?php if($mailchimpregister == "0"){ echo 'checked="checked"';} ?> />
					<?php
						echo JText::_("VIEWLICYES");
					?>
					&nbsp;&nbsp;&nbsp;
					<input style="float:none;" type="radio" name="mailchimpregister" value="1" <?php if($mailchimpregister == "1"){ echo 'checked="checked"';} ?> />
					<?php
						echo JText::_("DIGI_ONLY_OPTED_IN");
					?>
				</td>
			</tr>
		</table>
	</fieldset>
<?php
echo JHtml::_( 'obtabs.endTab' );
echo JHtml::_( 'obtabs.end' );
?>
</div>
	<input type="hidden" name="images" id="images" value="" />
	<input type="hidden" name="featuredproducts" id="featuredproducts" value="" />
	<input type="hidden" name="option" value="com_digistore" />
	<input type="hidden" name="id" value="<?php echo $this->prod->id; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="prc" value="<?php echo $this->prc; ?>" />
	<input type="hidden" name="controller" value="digistoreProducts" />
	<input type="hidden" name="forchange" value="" />
	<input type="hidden" name="tab" value="" />
	<input type="hidden" name="state_filter" value="<?php echo JRequest::getVar("state_filter", "-1"); ?>" />
</form>
