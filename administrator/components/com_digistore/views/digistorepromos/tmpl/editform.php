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

$promo = $this->promo;
$configs = $this->configs;
$nullDate = 0;
JHTML::_("behavior.calendar");
jimport('joomla.html.pane');

$f = $configs->time_format;
$f = str_replace ("-", "-%", $f);
$f = "%".$f;;

$ajax = <<<EOD

	window.addEvent('domready', function(){
		addProduct('items');
		addProduct('orders');
	});

	function addProduct(TYPE, ID)
	{
		var url = "index.php?option=com_digistore&controller=digistorePromos&task=productitem&no_html=1&type="+TYPE;
		var req = new Request.HTML({
			method: 'get',
			url: url,
			data: { 'do' : '1' },
			onComplete: function(response){
				$('product_'+TYPE).adopt(response);
				$$('a.modal').each(function(el) {
					el.addEvent('click', function(e) {
						new Event(e).stop();
						SqueezeBox.fromElement(el);
					});
				});
			}
		}).send();
	};

	function grayBoxiJoomla(link_element, width, height)
	{
		SqueezeBox.open(link_element, {
			handler: 'iframe',
			size: {x: width, y: height}
		});
	}

	function remove_product(ID, TYPE)
	{
		if (document.getElementById("product_"+TYPE+"s_id"+ID).value <= 0 || document.getElementById("product_"+TYPE+"s_id"+ID).value == '') return;
		var complete_id = "product_"+TYPE+"_"+ID;
		var par = document.getElementById(complete_id);
		par.parentNode.removeChild(par);
		//var parent_element = par.parentNode.parentNode.parentNode;
		//parent_element.removeChild(par);
	}

/* ]]> */
EOD;
$doc = JFactory::getDocument();
$doc->addScriptDeclaration( $ajax );

// $pane = JPane::getInstance('Tabs', array('startOffset'=>0));
?>

		<script language="javascript" type="text/javascript">
		<!--

		function submitbutton(pressbutton) {
			submitform( pressbutton );
		}
		-->
		</script>

	<table>
		<tr>
			<td align="right">
				<a class="modal digi_video" rel="{handler: 'iframe', size: {x: 750, y: 435}}" href="index.php?option=com_digistore&controller=digistoreAbout&task=vimeo&id=38437542">
					<img src="<?php echo JURI::base(); ?>components/com_digistore/assets/images/icon_video.gif" class="video_img" />
					<?php echo JText::_("COM_DIGISTORE_VIDEO_PROMO_MANAGER"); ?>
				</a>
			</td>
		</tr>
	</table>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<?php
//  echo $pane->startPane("Promo-pane");
//  JHtml::_('obtabs.start');
$options = array(
 		'onActive'		=> 'function(title, description){
					description.setStyle("display", "block");
					title.addClass("open").removeClass("closed");
				}',
		'onBackground'	=> 'function(title, description){
					description.setStyle("display", "none");
					title.addClass("closed").removeClass("open");
				}',
		'useCookie'		=> false, // this must not be a string. Don't use quotes.
		'active'		=> 'general-settings'
);
echo JHtml::_('obtabs.start', 'promo_settings', $options);
//  echo JHtml::_('obtabs.addTab', 'promo_settings', JText::_('VIEWCATEGORYGENERAL'), 'general-settings');
echo JHtml::_('obtabs.addTab', 'promo_settings', 'general-settings', JText::_('VIEWPROMOPROMOCODESETTINGS') );
?>
	<fieldset class="adminform">
	<legend><?php echo JText::_('VIEWPROMOPROMOCODESETTINGS');?></legend>
	<table class="admintable">
	<tr>
		<td>
			<?php echo JText::_("VIEWPROMOTITLE");?>
		</td>
		<td>
			<input type="text" name="title" value="<?php echo $promo->title;?>" />
			<?php
				echo JHTML::tooltip(JText::_("COM_DIGISTORE_PROMOTITLE_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
			?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_("VIEWPROMOCODE");?>
		</td>
		<td>
			<input type="text" name="code" value="<?php echo $promo->code;?>" />
			<?php
				echo JHTML::tooltip(JText::_("COM_DIGISTORE_PROMOCODE_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
			?>
		</td>
	</tr>
	<tr>
	<td>
			<?php echo JText::_("VIEWPROMOUSAGELIMIT");?>
		</td>
		<td nowrap>
			<input type="text" name="codelimit" value="<?php echo ($promo->codelimit == 0?'':$promo->codelimit);?>" />
			<?php
				echo JHTML::tooltip(JText::_("COM_DIGISTORE_PROMOUSAGELIMIT_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
			?>
		</td>
	</tr>
	<tr>
	<td>
			<?php echo JText::_("VIEWPROMODISCAMOUNT");?>
		</td>
		<td nowrap>
			<input type="text" style="width:50px" name="amount" value="<?php echo $promo->amount;?>" />
			<input type="radio" name="promotype" value="0" <?php echo ($promo->promotype == 0)?"checked":""; ?> /><?php echo $configs->currency;?>
			<input type="radio" name="promotype" value="1" <?php echo ($promo->promotype == 1 || $promo->promotype !== '0')?"checked":""; ?> /> %
			&nbsp;
			<?php
				echo JHTML::tooltip(JText::_("COM_DIGISTORE_PROMODISCOUNT_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
			?>
		</td>
	</tr>
	<tr>
	<td>
			<?php echo JText::_("VIEWPROMOSTARTPUBLISH");?>
		</td>
		<td nowrap>
			<?php
				if($promo->codestart == NULL){
					$promo->codestart = date("Y-m-d", time());
				}
				else{
					$promo->codestart = date("Y-m-d", $promo->codestart);
				}

				if(isset($promo->codeend) && $promo->codeend > 0){
					$promo->codeend = date("Y-m-d", $promo->codeend);
				}
				else{
					$promo->codeend = "Never";
				}
			?>
			<?php echo JHTML::_('calendar',  $promo->codestart, 'codestart', 'codestart', '%Y-%m-%d', array('class'=>'inputbox', 'size'=>'20',  'maxlength'=>'19')); ?>
			<?php
				echo JHTML::tooltip(JText::_("COM_DIGISTORE_PROMOSTARTPUB_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
			?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_("VIEWPROMOENDPUB");?>
		</td>
		<td>
			<?php echo JHTML::_("calendar",  $promo->codeend, 'codeend', 'codeend', '%Y-%m-%d'); ?>
			<?php
				echo JHTML::tooltip(JText::_("COM_DIGISTORE_PROMOENDPUB_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
			?>
		</td>
		</tr>
		<tr>
				<td>
			<?php echo JText::_("Apply promo discount after tax");?>
		</td>
		<td nowrap>
			<?php echo JText::_("VIEWPROMONO"); ?> <input type="radio" name="aftertax" value="0" <?php echo ($promo->aftertax == 0 || $promo->aftertax !== '1')?"checked":""; ?> />
			<?php echo JText::_("VIEWPROMOYES"); ?> <input type="radio" name="aftertax" value="1" <?php echo ($promo->aftertax == 1 )?"checked":""; ?> />
			<?php
				echo JHTML::tooltip(JText::_("COM_DIGISTORE_PROMOAFTERTAX_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
			?>
		</td>
		</tr>
			<tr>
				<td>
			<?php echo JText::_("VIEWPROMOPUBLISHING");?>
		</td>
		<td nowrap>
			<input type="checkbox" name="published" value="1" <?php  echo $promo->published ? 'checked="checked"' : ''; ?> />
			<?php
				echo JHTML::tooltip(JText::_("COM_DIGISTORE_PROMOPUBLISHING_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
			?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_("VIEWPROMOVALIDFOR");?>
		</td>
		<td nowrap>
			<?php echo JText::_("VIEWPROMOVALIDFORNEW"); ?> <input type="checkbox" name="validfornew" value="1" <?php  echo $promo->validfornew ? 'checked="checked"' : ''; ?> />
			<?php echo JText::_("VIEWPROMOVALIDFORRENEWAL"); ?> <input type="checkbox" name="validforrenewal" value="1" <?php  echo $promo->validforrenewal ? 'checked="checked"' : ''; ?> />
		</td>
	</tr>
	</table>

	</fieldset>

	<fieldset class="adminform">
		<legend><?php echo JText::_('VIEWPROMOSTATS');?></legend>
		<?php
		if ($promo->codeend != $nullDate) {
			$period = $promo->codeend - time(); //$promo->codestart;
			$days = (int ) ($period / (3600 * 24)) ;
			$left = $period % (3600 * 24);
			$hours = (int ) ($left / 3600 );
			$mins = (int )(($left - $hours*3600)/60) ;//$left % (3600 );

		} else {
			$period = 0;// $promo->codeend - time(); //$promo->codestart;
			$days = JText::_("VIEWPROMOUNLIM");//(int ) ($period / (3600 * 24)) ;
			$left = JText::_("VIEWPROMOUNLIM");//$period % (3600 * 24);
			$hours = JText::_("VIEWPROMOUNLIM");//(int ) ($left / 3600 );
			$mins = JText::_("VIEWPROMOUNLIM");//(int )(($left - $hours*3600)/60) ;//$left % (3600 );
		}
		$codelimit = ($promo->codelimit != 0)?$promo->codelimit:JText::_("VIEWPROMOINF");
		$codeleft = ($promo->codelimit != 0)?($promo->codelimit - $promo->used):JText::_("VIEWPROMOINF");
		?>
		<table class="adminlist" border="0">
			<tr>
				<td><h3><?php echo JText::_("VIEWPROMOTOTALUSES")." ".$codelimit;?></h3></td>
				<td><h3><?php echo JText::_("VIEWPROMOREMUSES")." ".$codeleft;?></h3></td>
				<td><h3><?php echo JText::_("VIEWPROMOUSED")." ".$promo->used;?></h3></td>
			</tr>
			<tr>
				<td><?php echo JText::_("VIEWPROMOTTL") ." ". $days ." ". JText::_("VIEWPROMOTTLDAYS"); ?></td>
				<td><?php echo $hours ." ". JText::_("VIEWPROMOTTLHOWRS"); ?></td>
				<td><?php echo $mins ." ". JText::_("VIEWPROMOTTLMIN"); ?></td>
			</tr>

		</table>
	</fieldset>

		<input type="hidden" name="images" value="" />
			<input type="hidden" name="option" value="com_digistore" />
			<input type="hidden" name="id" value="<?php echo $promo->id; ?>" />
			<input type="hidden" name="task" value="" />
		<input type="hidden" name="controller" value="digistorePromos" />
		</form>

<?php
echo JHtml::_('obtabs.endTab');
echo JHtml::_('obtabs.addTab', 'promo_settings', 'product-items', JText::_('VIEWPROMOPROMOCODEPRODUCTS') ); ?>
<table width="100%">
	<tr>
		<td>
			<h3><?php echo JText::_( 'VIEWPROMOPROMOCODEPRODUCTS_TIP' ); ?></h3>
		</td>
	</tr>
	<tr>
		<td id="product_items">
			<!-- Products -->
			<div id="product_item_1"></div><?php
			foreach ($this->promo_products as $product)
			{
				$id_rand = uniqid(rand()); ?>
				<div id="product_item_<?php echo $id_rand; ?>">
					<table width="100%">
						<tr>
							<td style="border-top:1px solid #ccc;padding-top:5px;" width="30%">
								<?php echo JText::_( 'Product' ); ?>
							</td>
							<td style="border-top:1px solid #ccc;padding-top:5px;">
								<div style="float:left">
									<span id="product_items_name_text_<?php echo $id_rand; ?>" style="color:#999;line-height: 17px;padding: 0.2em; border: 1px solid rgb(204, 204, 204); display: block; width: 250px; overflow: visible;"><?php echo $product->name;?></span>
									<input type="hidden" value="<?php echo $product->productid;?>" id="product_items_id<?php echo $id_rand; ?>" name="items_product_id[<?php echo $id_rand; ?>]"/>
								</div>
								<div><div class="blank" style="padding:0">
									<input type="button" class="btn btn-small" onclick="javascript:grayBoxiJoomla('index.php?option=com_digistore&controller=digistoreProducts&task=selectProducts&id=<?php echo $id_rand; ?>&userid=0&tmpl=component&type=item&prodedit=1', 600, 400)" value="<?php echo JText::_('DIGI_SELECT')?>" />
									<?php
									echo JHTML::tooltip(JText::_("COM_DIGISTORE_ORDERPRODUCT_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
									?>
								</div></div>
							</td>
							<td style="border-top:1px solid #ccc;padding-top:5px;" class="product_item_remove_btn">
								<a href="javascript:void(0)" id="product_item_remove_<?php echo $id_rand; ?>" onclick="remove_product('<?php echo $id_rand; ?>', 'item');">Remove</a>
							</td>
						</tr>
					</table>
				</div><?php
			} ?>
			<!-- /Products -->
		</td>
	</tr>
</table><?php
echo JHtml::_('obtabs.endTab');
echo JHtml::_('obtabs.addTab', 'promo_settings', 'product-orders' ,JText::_('VIEWPROMOPROMOCODEPURCHASES') ); ?>
<table width="100%">
	<tr>
		<td colspan="2">
			<h3><?php echo JText::_( 'VIEWPROMOPROMOCODEPURCHASES_TIP' ); ?></h3>
		</td>
	</tr>
	<tr>
		<td width="20%">
			<?php echo JText::_("VIEWPROMOOFEC");?>
		</td>
		<td nowrap>
			<?php echo JText::_("VIEWPROMONO"); ?> <input type="radio" name="forexisting" value="0" <?php echo ($promo->forexisting == 0)?"checked":""; ?> />
			<?php echo JText::_("VIEWPROMOYES"); ?> <input type="radio" name="forexisting" value="1" <?php echo ($promo->forexisting == 1 || $promo->forexisting != 0)?"checked":""; ?> />
			<?php
			echo JHTML::tooltip(JText::_("COM_DIGISTORE_PROMOOFEC_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
			?>
		</td>
	</tr>
	<tr>
		<td colspan="2" id="product_orders">
			<!-- Products -->
			<div id="product_orders_1"></div><?php
			foreach ($this->promo_orders as $product)
			{
				$id_rand = uniqid(rand()); ?>
				<div id="product_order_<?php echo $id_rand; ?>">
					<table width="100%">
						<tr>
							<td style="border-top:1px solid #ccc;padding-top:5px;" width="30%">
								<?php echo JText::_( 'Product' ); ?>
							</td>
							<td style="border-top:1px solid #ccc;padding-top:5px;">
								<div style="float:left">
									<span id="product_orders_name_text_<?php echo $id_rand; ?>" style="color:#999;line-height: 17px;padding: 0.2em; border: 1px solid rgb(204, 204, 204); display: block; width: 250px; overflow: visible;"><?php echo $product->name;?></span>
									<input type="hidden" value="<?php echo $product->productid;?>" id="product_orders_id<?php echo $id_rand; ?>" name="orders_product_id[<?php echo $id_rand; ?>]"/>
								</div>
								<div><div class="blank" style="padding:0">
									<input type="button" class="btn btn-small" onclick="javascript:grayBoxiJoomla('index.php?option=com_digistore&controller=digistoreProducts&task=selectProducts&id=<?php echo $id_rand; ?>&userid=0&tmpl=component&type=order&prodedit=1', 600, 400)" value="<?php echo JText::_('DIGI_SELECT')?>" />
									<?php
									echo JHTML::tooltip(JText::_("COM_DIGISTORE_ORDERPRODUCT_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
									?>
								</div></div>
							</td>
							<td style="border-top:1px solid #ccc;padding-top:5px;" class="product_order_remove_btn">
								<a href="javascript:void(0)" id="product_order_remove_<?php echo $id_rand; ?>" onclick="remove_product('<?php echo $id_rand; ?>', 'order');">Remove</a>
							</td>
						</tr>
					</table>
				</div><?php
			} ?>
			<!-- /Products -->
		</td>
	</tr>
</table><?php
echo JHtml::_('obtabs.endTab');
echo JHtml::_('obtabs.end');
