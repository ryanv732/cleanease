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

$task2 = JRequest::getVar('task2');
global $isJ25;
$offsets = array(
	'payments'	=>1,
	'email'		=>2,
	'content'	=>3,
	'store'		=>4,
	'tax'		=>5,
	'layout'	=>6
);
$startOffset = isset( $offsets[$task2] )?$offsets[$task2]:0;

// 	jimport('joomla.html.pane');

// 	$document = JFactory::getDocument();
// 	$document->addStyleDeclaration('
// 		dl.tabs {
// 			//display: none !important;
// 		}

// 		div.current select {
// 			margin-bottom: 0px;
// 		}
// 	');

	$configs = $this->configs;
	$lists = $this->lists;
	$eu = $this->eu;
?>


<script language="javascript" type="text/javascript">

	function validatePluginCurrency(obj) {
		var supported = new Array();

		<?php 

			foreach ($lists['currencies'] as $cur) {
				echo "supported['".$cur->currency_name."'] = new Array();\n";
					foreach ($lists['currency_to_plugin'] as $plugin => $currency) {
						echo "supported['".$cur->currency_name."']['".$plugin."'] = 0;\n";
					}
			}

			foreach ($lists['currency_to_plugin'] as $plugin => $currency) {
				foreach ($currency as $code => $full ) {
					echo "supported['".$code."']['".$plugin."'] = 1;\n";
				}
			}

		?>
		var val = obj.options[obj.selectedIndex].value;
		var tmp = new Array();
		var flag = 0;
		for (key in supported[val]) {
			var i = 0;
			if (supported[val][key] == 0) {
					tmp[i] = key;
				flag = 1;

			} else {
				document.getElementById("plugins_to_currencies").innerHTML = '<?php echo JText::_("VIEWCONFIGALLCUR"); ?>';
				break;
			}
		}

		if (flag) {

			document.getElementById("plugins_to_currencies").innerHTML = '<?php echo JText::_("VIEWCONFIGNOTSUPPORT"); ?>';
			for (var j = 0; j < tmp.length; j++ ) {
				document.getElementById("plugins_to_currencies").innerHTML +=	tmp[i] + "<br />";

			}
		}
	}

		var folderimages = new Array;
		<?php 
		$i = 0;

		?>

		function submitbutton(pressbutton) {
				var form = document.adminForm;
				if (pressbutton == "cancel") {
					submitform( pressbutton );
				} 
				if (pressbutton == 'save') {
					if (form.askterms.value == '1' && parseInt(form.termsid.value) < 1){
						alert ("<?php echo JText::_("VIEWCONFIGNOTZERO");?>");
						return ;
					}

					if ( parseInt(form.catlayoutcol.value) < 1){
						alert ("<?php echo JText::_("VIEWCONFIGINTCOL");?>");
						return ;
					}
					if ( parseInt(form.catlayoutrow.value) < 1){
						alert ("<?php echo JText::_("VIEWCONFIGINTROW");?>");
						return ;
					}
					if ( parseInt(form.prodlayoutcol.value) < 1){
						alert ("<?php echo JText::_("VIEWCONFIGINTPRODCOL");?>");
						return ;
					}
					/*if(parseInt(form.prodlayoutcol.value) > 1 && document.getElementById("prodlayoutstyleold").checked){
						alert ("<?php echo JText::_("VIEWCONFIGPRODMORETHENONE");;?>");
						return ;
					}*/
					if ( parseInt(form.prodlayoutrow.value) < 1){
						alert ("<?php echo JText::_("VIEWCONFIGINTPRODROW");?>");
						return ;
					}
					<?php
						$task2 = JRequest::getVar("task2", "");
						if($task2 != "payments"){
					?>
						if ( parseInt(form.askforship.value) < 1 && parseInt(form.tax_base.value) < 1){
							alert ("<?php echo JText::_("VIEWCONFIGTAXSHIP");?>");
							return ;
						}
					<?php
						}
					?>
				}

			submitform( pressbutton );
		}

			function showTaxNum(x) {
				var taxnum = document.getElementById("comptaxnum");
				if (x == 0) taxnum.style.display = "";
				else taxnum.style.display = "none";

			}
window.addEvent('domready',function(){
	tabsDisplay(<?php echo $startOffset; ?>);
});

function tabsDisplay(offset, selecter ) {
	if(!selecter)selecter='dd.tabs';
	$$(selecter).each(function(dd,index){
		if(index==offset){
			dd.style.display='block';
		} else {
			dd.style.display='none';
		}
	});
	return;
}

</script>

<form enctype="multipart/form-data" action="index.php" method="post" name="adminForm" id="adminForm" class="form-horizontal">
<?php 
$selected_tab_name = JRequest::getVar('task2','general');
?>
	<input type="hidden" name="task2" value="<?php echo $selected_tab_name; ?>" />
	<?php

	$startOffset = 0;
	switch($selected_tab_name) {
		case 'payments': $startOffset = 1;
			break;
		case 'email': $startOffset = 2;
			break;
		case 'content': $startOffset = 3;
			break;
		case 'store': $startOffset = 4;
			break;
		case 'tax': $startOffset = 5;
			break;
		case 'layout': $startOffset = 6;
			break;
		case '':
		default: $startOffset = 0;
			break;
	}


// 	var_dump($startOffset);
// 	JHtmlTabs::start()
	$options = array(
		'onActive' => 'function(title, description){
			description.setStyle("display", "block");
			title.addClass("open").removeClass("closed");
		}',
		'onBackground' => 'function(title, description){
			description.setStyle("display", "none");
			title.addClass("closed").removeClass("open");
		}',
		'startOffset' => $startOffset,  // 0 starts on the first tab, 1 starts the second, etc...
		'useCookie' => false, // this must not be a string. Don't use quotes.
		'active' => 'tab_'.$task2
	);
	echo JHtml::_('obtabs.start', 'digistore_settings', $options);
	echo JHtml::_('obtabs.addTab', 'digistore_settings', 'tab_general', '<i class=\"icon-cog\"></i>'.(($isJ25)?'':'&nbsp;').JText::_('VIEWCONFIGCATGENERAL'));
	include_once(JPATH_SITE.DS."administrator".DS."components".DS."com_digistore".DS."views".DS."digistoreconfigs".DS."tmpl".DS."general.php");
	echo JHtml::_('obtabs.endTab');

	echo JHtml::_('obtabs.addTab', 'digistore_settings', 'tab_payments', '<i class=\"icon-basket\"></i>'.(($isJ25)?'':'&nbsp;').JText::_('VIEWCONFIGCURRENCY'));
	include_once(JPATH_SITE.DS."administrator".DS."components".DS."com_digistore".DS."views".DS."digistoreconfigs".DS."tmpl".DS."payment.php");
	echo JHtml::_('obtabs.endTab');
	
	echo JHtml::_('obtabs.addTab', 'digistore_settings', 'tab_email', '<i class=\"icon-mail\"></i>'.(($isJ25)?'':'&nbsp;').JText::_('VIEWCONFIGEMAILS'));
	include_once(JPATH_SITE.DS."administrator".DS."components".DS."com_digistore".DS."views".DS."digistoreconfigs".DS."tmpl".DS."email.php");
	echo JHtml::_('obtabs.endTab');
	
	echo JHtml::_('obtabs.addTab', 'digistore_settings', 'tab_content', '<i class=\"icon-comments\"></i>'.(($isJ25)?'':'&nbsp;').JText::_('VIEWCONFIGCONTENT'));
	include_once(JPATH_SITE.DS."administrator".DS."components".DS."com_digistore".DS."views".DS."digistoreconfigs".DS."tmpl".DS."content.php");
	echo JHtml::_('obtabs.endTab');
	
	echo JHtml::_('obtabs.addTab', 'digistore_settings', 'tab_store', '<i class=\"icon-basket\"></i>'.(($isJ25)?'':'&nbsp;').JText::_('VIEWCONFIGSTORE'));
	include_once(JPATH_SITE.DS."administrator".DS."components".DS."com_digistore".DS."views".DS."digistoreconfigs".DS."tmpl".DS."store.php");
	echo JHtml::_('obtabs.endTab');
	
	echo JHtml::_('obtabs.addTab', 'digistore_settings', 'tab_tax', '<i class=\"icon-pencil\"></i>'.(($isJ25)?'':'&nbsp;').JText::_('VIEWCONFIGTAX'));
	include_once(JPATH_SITE.DS."administrator".DS."components".DS."com_digistore".DS."views".DS."digistoreconfigs".DS."tmpl".DS."tax.php");
	echo JHtml::_('obtabs.endTab');
	
	echo JHtml::_('obtabs.addTab', 'digistore_settings', 'tab_layout', '<i class=\"icon-screen\"></i>'.(($isJ25)?'':'&nbsp;').JText::_('VIEWCONFIGLAYOUTS'));
?>

<style type="text/css" >
.whitespace td {
	white-space:nowrap;
}
</style>

		<table class="admintable" width="100%">
<?php
			include_once(JPATH_SITE.DS."administrator".DS."components".DS."com_digistore".DS."views".DS."digistoreconfigs".DS."tmpl".DS."layoutcats.php");
			include_once(JPATH_SITE.DS."administrator".DS."components".DS."com_digistore".DS."views".DS."digistoreconfigs".DS."tmpl".DS."layoutprods.php");
			include_once(JPATH_SITE.DS."administrator".DS."components".DS."com_digistore".DS."views".DS."digistoreconfigs".DS."tmpl".DS."layoutprod.php");
			include_once(JPATH_SITE.DS."administrator".DS."components".DS."com_digistore".DS."views".DS."digistoreconfigs".DS."tmpl".DS."layoutcolumns.php");
?>
		</table>
		<table class="admintable">
			<tr>
				<td width="12%" nowrap="nowrap">
					<?php
						echo JText::_("DIGI_SHOW_BRADCRUMBS");
					?>
				</td>
				<td>
					<input type="radio" name="show_bradcrumbs" value="0" <?php if($configs->show_bradcrumbs == "0"){echo 'checked="checked"';} ?> ><?php echo JText::_("DSYES"); ?>
					<input type="radio" name="show_bradcrumbs" value="1" <?php if($configs->show_bradcrumbs == "1"){echo 'checked="checked"';} ?> ><?php echo JText::_("DSNO"); ?>
					<?php
						echo JHTML::tooltip(JText::_("COM_DIGISTORE_LAYOUTBRADCRUMBS_TIP"), '', '',  "<img src=".JURI::root()."administrator/components/com_digistore/assets/images/tooltip.png />", '', '', 'hasTip');
					?>
				</td>
			</tr>
		</table>
<?php
	echo JHtml::_('obtabs.endTab');
	echo JHtml::_('obtabs.end');
?>
		<input type="hidden" name="images" value="" />
		<input type="hidden" name="option" value="com_digistore" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="task2" value="<?php echo JRequest::getVar("task2", ""); ?>" />
		<input type="hidden" name="id" value="<?php echo ($configs->id? $configs->id:"");?>" />
		<input type="hidden" name="controller" value="digistoreConfigs" />
</form>