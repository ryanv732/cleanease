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

jimport( "joomla.application.component.view" );

class digistoreAdminViewdigistoreProducts extends obView
{

	function display( $tpl = null )
	{
		// Access check.
		if (!JFactory::getUser()->authorise('digistore.products', 'com_digistore'))
		{
			return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
		}

		JToolBarHelper::title( JText::_( 'VIEWDSADMINPRODUCTS' ), 'generic.png' );
		JToolBarHelper::addNew();
		JToolBarHelper::editList();
		JToolBarHelper::custom('copy', 'copy.png', 'copy.png', 'JLIB_HTML_BATCH_COPY', true, false);
		JToolBarHelper::divider();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::divider();
		JToolBarHelper::deleteList();

		$configs = $this->_models['digistoreconfig']->getConfigs();
		$this->assign( "configs", $configs );

		$db = JFactory::getDBO();

		$session = JFactory::getSession();
		$session->set('dsproducategory', 0, 'digistore');

		$products = $this->get('Items');
		$pagination = $this->get('Pagination');

		$this->prods = $products;
		$this->pagination = $pagination;

		$cats = $this->_models['digistorecategory']->getlistCategories();
		$prc = JRequest::getVar( "prc", 0, "request" );
		$state_filter = JRequest::getVar("state_filter", "-1");

		$cselector = digistoreAdminHelper::getCatListProd( new stdClass, $cats, 1, $prc );
		$this->assign("csel", $cselector);
		$this->assign("prc", $prc);
		$this->assign("state_filter", $state_filter);
		parent::display( $tpl );
	}

	function select( $tpl = null )
	{
		$configs = $this->_models['digistoreconfig']->getConfigs();
		$this->assign( "configs", $configs );

		$session = JFactory::getSession();
		$prc = $session->get('dsproducategory', 0, 'digistore');
		$prc = JRequest::getVar( "prc", $prc, "request" );
		$session->set('dsproducategory', $prc, 'digistore');
		$products = $this->get('Items');

		$db = JFactory::getDBO();
		foreach ( $products as $key => $prod ) {
			$price = 0;

			switch ( $prod->priceformat )
			{

				case '2': // Don't show price
					$price = '';
					break;

				case '3': // Price and up
					$sql = "SELECT pp.product_id, min(pp.price) as price FROM #__digistore_plans dp
					LEFT JOIN #__digistore_products_plans pp on (dp.id = pp.plan_id)
					WHERE pp.product_id = " . $prod->id . "
					GROUP BY pp.product_id";
					$db->setQuery( $sql );
					$prodprice = $db->loadObject();
					if ( !empty( $prodprice ) )
						$price = digistoreAdminHelper::format_price( $prodprice->price, $configs->currency, true, $configs ) . " and up";
					break;

				case '4': // Price range
					$sql = "SELECT pp.product_id, min(pp.price) as price_min, max(pp.price) as price_max FROM #__digistore_plans dp
					LEFT JOIN #__digistore_products_plans pp on (dp.id = pp.plan_id)
					WHERE pp.product_id = 1
					GROUP BY pp.product_id";
					$db->setQuery( $sql );
					$prodprice = $db->loadObject();
					if ( !empty( $prodprice ) )
						$price = digistoreAdminHelper::format_price( $prodprice->price_min, $configs->currency, true, $configs ) . " - " . digistoreAdminHelper::format_price( $prodprice->price_max, $configs->currency, true, $configs );
					break;

				case '5': // Minimal price
					$sql = "SELECT pp.product_id, min(pp.price) as price FROM #__digistore_plans dp
					LEFT JOIN #__digistore_products_plans pp on (dp.id = pp.plan_id)
					WHERE pp.product_id = " . $prod->id . "
					GROUP BY pp.product_id";
					$db->setQuery( $sql );
					$prodprice = $db->loadObject();
					if ( !empty( $prodprice ) )
						$price = digistoreAdminHelper::format_price( $prodprice->price, $configs->currency, true, $configs );
					break;

				case '1': // Default price
				default:
					$sql = "SELECT pp.product_id, pp.price as price FROM #__digistore_plans dp
					LEFT JOIN #__digistore_products_plans pp on (dp.id = pp.plan_id)
					WHERE pp.default = 1 and pp.product_id = " . $prod->id;
					$db->setQuery( $sql );
					$prodprice = $db->loadObject();
					if ( !empty( $prodprice ) )
						$price = digistoreAdminHelper::format_price( $prodprice->price, $configs->currency, true, $configs );
					break;
			}

			$products[$key]->price = $price;
		}
		$this->assignRef( 'prods', $products );

		$pagination = $this->get( 'Pagination' );
		$this->assignRef( 'pagination', $pagination );

		$cats = $this->_models['digistorecategory']->getlistCategories();

		$cselector = digistoreAdminHelper::getSelectCatListProd( new stdClass, $cats, 1, $prc );
		$this->assign( "csel", $cselector );
		$this->assign( "prc", $prc );
		parent::display( $tpl );

	}

	function selectproductinclude($tpl = null){
		$configs = $this->_models['digistoreconfig']->getConfigs();
		$this->assign( "configs", $configs );

		//$products = $this->get('listProducts');
		$products = $this->get('Items');

		$db = JFactory::getDBO();
		foreach ( $products as $key => $prod ) {
			$price = 0;

			switch ( $prod->priceformat )
			{

				case '2': // Don't show price
					$price = '';
					break;

				case '3': // Price and up
					$sql = "SELECT pp.product_id, min(pp.price) as price FROM #__digistore_plans dp
					LEFT JOIN #__digistore_products_plans pp on (dp.id = pp.plan_id)
					WHERE pp.product_id = " . $prod->id . "
					GROUP BY pp.product_id";
					$db->setQuery( $sql );
					$prodprice = $db->loadObject();
					if ( !empty( $prodprice ) )
						$price = digistoreAdminHelper::format_price( $prodprice->price, $configs->currency, true, $configs ) . " and up";
					break;

				case '4': // Price range
					$sql = "SELECT pp.product_id, min(pp.price) as price_min, max(pp.price) as price_max FROM #__digistore_plans dp
					LEFT JOIN #__digistore_products_plans pp on (dp.id = pp.plan_id)
					WHERE pp.product_id = 1
					GROUP BY pp.product_id";
					$db->setQuery( $sql );
					$prodprice = $db->loadObject();
					if ( !empty( $prodprice ) )
						$price = digistoreAdminHelper::format_price( $prodprice->price_min, $configs->currency, true, $configs ) . " - " . digistoreAdminHelper::format_price( $prodprice->price_max, $configs->currency, true, $configs );
					break;

				case '5': // Minimal price
					$sql = "SELECT pp.product_id, min(pp.price) as price FROM #__digistore_plans dp
					LEFT JOIN #__digistore_products_plans pp on (dp.id = pp.plan_id)
					WHERE pp.product_id = " . $prod->id . "
					GROUP BY pp.product_id";
					$db->setQuery( $sql );
					$prodprice = $db->loadObject();
					if ( !empty( $prodprice ) )
						$price = digistoreAdminHelper::format_price( $prodprice->price, $configs->currency, true, $configs );
					break;

				case '1': // Default price
				default:
					$sql = "SELECT pp.product_id, pp.price as price FROM #__digistore_plans dp
					LEFT JOIN #__digistore_products_plans pp on (dp.id = pp.plan_id)
					WHERE pp.default = 1 and pp.product_id = " . $prod->id;
					$db->setQuery( $sql );
					$prodprice = $db->loadObject();
					if ( !empty( $prodprice ) )
						$price = digistoreAdminHelper::format_price( $prodprice->price, $configs->currency, true, $configs );
					break;
			}

			$products[$key]->price = $price;
		}
		$this->assignRef( 'prods', $products );

		$pagination = $this->get('Pagination');

		$this->prods = $products;
		$this->pagination = $pagination;

		$cats = $this->_models['digistorecategory']->getlistCategories();
		$prc = JRequest::getVar( "prc", 0, "request" );

		$cselector = digistoreAdminHelper::getSelectCatListProdInclude( new stdClass, $cats, 1, $prc );
		$this->assign( "csel", $cselector );
		$this->assign( "prc", $prc );
		parent::display( $tpl );

	}

	function addProduct( $tpl = null ) {

		JToolBarHelper::title( JText::_( 'Products Manager: select product type' ), 'generic.png' );
		JToolBarHelper::cancel();

		parent::display( $tpl );
	}

	function editForm( $tpl = null )
	{
		$db = JFactory::getDBO();
		$product = $this->get('product');

		$configs = $this->_models['digistoreconfig']->getConfigs();

		$isNew = ($product->id < 1);
		$text = $isNew ? JText::_( 'New' ) : JText::_( 'JACTION_EDIT' );

		JToolBarHelper::title( JText::_( 'DSPROD' ) . ":<small>[" . $text . "]</small>" );

		JToolBarHelper::save();
		JToolBarHelper::save2new();
		if($product->id){
			JToolBarHelper::save2copy();
		}

		JToolBarHelper::spacer();
		JToolBarHelper::apply();
		JToolBarHelper::divider();
		JToolBarHelper::cancel( 'cancel', 'JTOOLBAR_CLOSE' );

		$prc = JRequest::getVar( "prc", 0, "request" );
		$this->assign( "prc", $prc );
		$this->assign( "prod", $product );

		$directory = "images";
		$imageFolders = array();
		$imageFolders[] = myDS; //$directory;
		$imageFolders = digistoreAdminHelper::cleanUpImageFolders( $directory, digistoreAdminHelper::getImageFolderList( $directory, $imageFolders ) );

		//print_r($imageFolders);
		foreach ( $imageFolders as $folder ) {
			$folders[] = JHTML::_( 'select.option', $folder );
		}

		$folders = array();

		foreach ( $imageFolders as $folder ) {
			$folders[] = JHTML::_( 'select.option', $folder );
		}

		$images = array();
//print_r($imageFolders);
		$images = digistoreAdminHelper::getFolderImageList( $directory, $imageFolders );
//		print_r($images);

		$active = 1;

		$srcname = "srcimg";
		$dstname = "prodimg[]";

		$folderjs = 'onchange="changeImageList(this);"';
		$lists['folders'] = JHTML::_( 'select.genericlist', $folders, 'folders', 'class="inputbox" size="1" ' . $folderjs, 'value', 'text', "/" );


		$srcimgjs = 'onchange="changeShownImg(\'src\');"';
		$lists['imagelist'] = JHTML::_( 'select.genericlist', $images["/"], $srcname, 'class="inputbox" size="10" ' . $srcimgjs, 'value', 'text', '' );
		foreach ( $imageFolders as $folder ) {
			$lists['imagelists'][$folder] = rawurlencode( JHTML::_( 'select.genericlist', $images[$folder], $srcname, 'class="inputbox" size="10" ' . $srcimgjs, 'value', 'text', '' ) );
		}

		$prodimgjs = 'onchange="changeShownImg(\'prod\');"';
		$imgs = explode( "\n", $product->images );
		$prodimages = array();
		foreach ( $imgs as $img ) {
			if ( strlen( trim( $img ) ) > 0 ) {
				$tmp = explode( "/", $img );
				$n = count( $tmp );
				$img1 = $tmp[$n - 1];
				$prodimages[] = JHTML::_( 'select.option', $img, $img1 );
			}
		}
		$lists['prodimagelist'] = JHTML::_( 'select.genericlist', $prodimages, $dstname, 'class="inputbox" size="10" ' . $prodimgjs, 'value', 'text', '' );

		// $lists['access'] = JHTML::_('list.accesslevel',  $product );

		// Get product ACL groups
		$lists['groups'] = array();
		if(!$isNew){
			$sql = "SELECT `id_group`
				FROM `#__digistore_product_groups`
				WHERE `id_product`=" . $product->id;
			$db->setQuery($sql);
			$groups = $db->loadObjectList();

			foreach($groups as $group)
			{
				$lists['groups'][$group->id_group] = $group->id_group;
			}
		}
		// Get product expire ACL groups
		$lists['expgroups'] = array();
		if(!$isNew){
			$sql = "SELECT `id_group`
				FROM `#__digistore_product_groups_exp`
				WHERE `id_product`=" . $product->id;
			$db->setQuery($sql);
			$expgroups = $db->loadObjectList();

			foreach($expgroups as $group)
			{
				$lists['expgroups'][$group->id_group] = $group->id_group;
			}
		}

		// Multiple upload 
		global $isJ25; if($isJ25) JHTML::_('behavior.mootools');

		$doc = JFactory::getDocument();
		$doc->addScript(JURI::root().'components/com_digistore/assets/js/ajaxupload.js');
		$doc->addStyleSheet(JURI::root() . 'administrator/components/com_digistore/assets/css/redactor.css');
// 		echo '<script src="'.JURI::root() . 'components/com_digistore/assets/js/jquery.digistore.js"></script>';
		echo '<script src="'.JURI::root() . 'components/com_digistore/assets/js/jquery.noconflict.digistore.js"></script>';
		echo '<script src="'.JURI::root() . 'administrator/components/com_digistore/assets/js/redactor.min.js"></script>';
		echo '<script>jQuery(function(){
				jQuery(".useredactor").redactor();
				jQuery(".redactor_useredactor").css("height","400px");
			});
		</script>';

		$upload_script = '

			window.addEvent( "domready", function() {
				new AjaxUpload("ajaxuploadproductimages", {
					action: "' . JURI::root() . 'administrator/index.php?option=com_digistore&controller=digistoreProducts&task=uploadimages&tmpl=component&no_html=1",
					name: "prodimages[]",
					multiple: true,
					data: {\'ProductId\' : \''.( $product->id ? $product->id : 0 ).'\'},
					onSubmit: function(id, fileName) {
						// alert(id); alert(fileName);
						jQuery(\'#onAjax\').css(\'display\', \'inline\');
					},
					onComplete: function(file, response){
						jQuery(\'#onAjax\').hide();
						document.getElementById("productsthumbnails").innerHTML += response;
	
						$$("a.modal").each(function(el) {
								el.addEvent("click", function(e) {
								new Event(e).stop();
								SqueezeBox.fromElement(el);
							});
						});
						//Joomla.submitbutton(\'apply\');
					}
				});
			});
		';

		$doc->addScriptDeclaration( $upload_script );
		$prodimages = "";
		if(isset($product->prodimages) && is_string($product->prodimages)){
			$prodimages = explode(',\n', trim($product->prodimages));
		} else {
			$prodimages = $product->prodimages;
		}
		$lists['prodimages'] = $prodimages;
		$lists['defprodimage'] = $product->defprodimage;
	
		// end my upload

		$lists['published'] = JHTML::_( 'select.booleanlist', 'published', '', $product->published );

		$lists['sku'] = '<input id="sku" name="sku" type="text" value="'.$product->sku.'"/>';

		// build the html select list for ordering
		$query = 'SELECT ordering AS value, name AS text'
		. ' FROM #__digistore_products'
		. ' ORDER BY ordering'
		;

		/*$lists['showqtydropdown'] = '
			<input id="showqtydropdown" name="showqtydropdown" type="checkbox" value="1" ' . (($product->showqtydropdown == 1) ? ' checked ' : '') . '/>
		';*/
		$lists['showqtydropdown'] = '
			<label for="showqtydropdown">
				<input id="showqtydropdown" name="showqtydropdown" type="checkbox" value="1" ' . (($product->showqtydropdown == 1) ? ' checked ' : '') . '/>
				' . JText::_( 'VIEWPRODPORODPRICING_SHOWQTY' ) . '
				<span class="editlinktip hasTip" title="'.JText::_('COM_DIGISTORE_PRODQUANTITYDROP_TIP').'" ><img style="float: none; margin: 0px;" src="components/com_digistore/assets/images/icons/tooltip.png" border="0"/></span>
			</label>
		';

		$price_formats = array(
			'1' => 'Default plan',
			'2' => 'Don\'t show price',
			'3' => 'Price and up',
			'4' => 'Price range',
			'5' => 'Minimal price'
		);
		
		foreach ( $price_formats as $key => $pf ) {
			$pfs[] = JHTML::_( 'select.option', $key, $pf );
		}
		$lists['priceformat'] = '<label for="priceformat">' . JText::_( 'VIEWPRODPORODPRICING_SHOW_PRICEFORMAT' ) . '</label>';
		$lists['priceformat'] .= JHTML::_( 'select.genericlist', $pfs, 'priceformat', ' id="priceformat" class="inputbox" ', 'value', 'text', $product->priceformat );

		if ( $isNew ) {
			$lists['ordering'] = JHtml::_('list.ordering','ordering', $query, '');
// 			$lists['ordering'] = JHTML::_( 'list.specificordering', $product, '', $query );
		} else {
			$lists['ordering'] = JHtml::_('list.ordering','ordering', $query, '', $product->id);
// 			$lists['ordering'] = JHTML::_( 'list.specificordering', $product, $product->id, $query );
		}

		$directory = $configs->ftp_source_path;
		if ( file_exists( JPATH_SITE . DS . $directory ) ) {
			$ftpFiles = JFolder::files( JPATH_SITE . DS . $directory );
		} else {
			$ftpFiles = array();
		}
		$files = array();
		$files[] = JHTML::_( "select.option", "", 'Select ftp file' );
		foreach ( $ftpFiles as $file ) {
			$files[] = JHTML::_( 'select.option', $file );
		}

		$lists['ftpfilelist'] = JHTML::_( 'select.genericlist', $files, 'ftpfile', 'class="inputbox" size="1" ', 'value', 'text', "" );

		$files = array();
		$cats = $this->get("listCategories");
		$lists['catid'] = digistoreAdminHelper::getCatListProd($product, $cats);
		$lists['access'] = JHTML::_('access.assetgrouplist','access', $product->access );
// 		$lists['access'] = JHTML::_( 'list.accesslevel', $product );

		$pclasses = explode( "\n", $product->taxclass );
		$data = $this->get( 'listProductTaxClasses' );
		$select = '<select name="taxclass">';
		if ( count( $data ) > 0 )
			foreach ( $data as $i => $v ) {
				$select .= '<option value="' . $v->id . '" ';
				if ( in_array( $v->id, $pclasses ) ) {
					$select .= ' selected ';
				}
				$select .= ' > ' . $v->name . '</option>';
			}
		$select .= '</select>';
		$lists['product_tax_class'] = $select;


		$ptype = explode( "\n", $product->class );
		$data = $this->get( 'listProductClasses' );
		$select = '<select name="ptype[]" >';
		if ( count( $data ) > 0 )
			foreach ( $data as $i => $v ) {
				$select .= '<option value="' . $v->id . '" ';
				if ( in_array( $v->id, $ptype ) ) {
					$select .= ' selected ';
				}
				$select .= ' > ' . $v->name . '</option>';
			}
		$select .= '</select>';
		$lists['product_class'] = $select;

//		$fields = $this->_models['digistoreattribute']->getlistAttributes();

// 		echo "<pre>";
// 		print_r($product->productfields);
// 		echo "</pre>";
		$this->assign( "fields", $product->productfields );
		//$this->assign( "fields", $fields );

		if (isset($product->domainrequired) && !empty($product->domainrequired)) {
// Edit
			$producttype = $product->domainrequired;
			$lists['domainrequired'] = "<input type='hidden' name='domainrequired' value='{$product->domainrequired}'/>";

		} else {
// New
			$producttype = JRequest::getVar('producttype',0);
			$lists['domainrequired'] = "<input type='hidden' name='domainrequired' value='{$producttype}'/>";
		}

		switch($producttype){
			case 1:
				$lists['hidetab'] = array('shipping', 'stock', 'attribute', 'package');
				$lists['domainrequired'] .= JText::_('VIEWPRODPRODTYPEDR');
			break;

			case 2:
				$lists['hidetab'] = array('file', 'package');
				$lists['domainrequired'] .= JText::_('VIEWPRODPRODTYPESP');
			break;

			case 3:
				$lists['hidetab'] = array('shipping', 'stock', 'attribute', 'file');
				$lists['domainrequired'] .= JText::_('VIEWPRODPRODTYPEPAK');
			break;

			case 4:
				$lists['hidetab'] = array('shipping', 'stock', 'attribute', 'file', 'package');
				$lists['domainrequired'] .= JText::_('VIEWPRODPRODTYPESERV');
			break;

			case 0:
			default:
				$lists['hidetab'] = array('shipping', 'stock', 'attribute', 'package');
				$lists['domainrequired'] .= JText::_('VIEWPRODPRODTYPEDNR');
			break;
		}

		/*
		 * check to see if it is new install >>> disable Attribute feature
		 * We don't need it from 3.x by foobla 
		$db = JFactory::getDBO();
		$sql = "SHOW COLUMNS FROM #__digistore_settings";
		$db->setQuery($sql);
		$result = $db->loadColumn();
		if(in_array("newinstall", $result)){
			$lists['hidetab'][] = "attribute";
		}
		*/

		/*  Subcription Tab */

		if($producttype == 3) {
			$plains = $this->get('listPlains');
			$sql = "SELECT pp.`plan_id`, pp.`price`, pp.`default` 
				FROM #__digistore_products_plans AS pp
				LEFT JOIN #__digistore_plans AS p
				on pp.plan_id = p.id
				WHERE pp.product_id='" . $product->id . "'
				AND p.duration_count = '-1' 
				AND p.duration_type = '0' ";
		}
		else{
			$plains = $this->get('listPlains');
			$sql = "SELECT `plan_id`, `price`, `default` 
				FROM #__digistore_products_plans 
				WHERE product_id=" . $product->id;
		}
		$db->setQuery( $sql );
		$plainstoproduct = $isNew?array():$db->loadObjectList();

		$plains_html = "";
		if($producttype == 2 || $producttype == 3 || $producttype == 4){
			$plains_html .= '<table>';
			$plains_html .= 	'<tr>';
			$plains_html .= 		'<td width="4%">';
			$plains_html .= 			JText::_("DSPRICE").":";
			$plains_html .= 		'</td>';
			$plains_html .= 		'<td>';
			$price = "";
			$plan = "";
			if(isset($plainstoproduct) && isset($plainstoproduct["0"]->price)){
				$price = $plainstoproduct["0"]->price;
				$plan = $plainstoproduct["0"]->plan_id;
			}
			else{
				if(isset($plains) && is_array($plains) && count($plains) > 0){
					foreach($plains as $plain){
						if($plain->duration_count == "-1" && $plain->duration_type == "0"){
							$plan = $plain->id;
						}
					}
				}
			}

			$plains_html .= 			'<input type="radio" name="plain_default" value="'.$plan.'" checked="checked" style="visibility:hidden" />';
			$plains_html .= 			'<input class="plain" type="checkbox" name="plain[]" value="'.$plan.'" checked="checked" style="visibility:hidden" />';
			$plains_html .= 			'<input type="text" name="plain_amount['.$plan.']" value="'.$price.'" style="text-align:center;" />';
			$plains_html .= 		'</td>';
			$plains_html .= 	'</tr>';
			$plains_html .= '</table>';
		}
		else{
			$plains_html .= "<table>";
			$plains_html .= "<tr style='background:#999'>
								<th width='1%' style='padding:0.5em;'>" . JText::_( 'Default' ) . "</th>
								<th width='1%' style='padding:0.5em;'><input type='checkbox' id='splains' name='splains' value='' onclick='checkPlans(this.checked);'/></th>
								<th style='padding:0.5em;'>" . JText::_( 'Name' ) . "</th>
								<th style='padding:0.5em;'>" . JText::_( 'Terms' ) . "</th>
								<th style='padding:0.5em;'>" . JText::_( 'Price' ) . "</th>
							</tr>";
			if(isset($plains) && is_array($plains) && count($plains) > 0){
				foreach($plains as $plain){

					$checked = false;
					$default = false;
					$price = "";
					if(isset($plainstoproduct) && count($plainstoproduct) > 0){
						foreach($plainstoproduct as $plain_value ) {
							if ( $plain_value->plan_id == $plain->id ) {
								$checked = true;
								$price = $plain_value->price;
								if ( $plain_value->default == 1 ) {
									$default = true;
								}
							}
						}
					}

					$plains_html .= "<tr>";
					$plains_html .= '<td style="padding:0.5em;"><input type="radio" class="plain_default" id="plain_default'.$plain->id.'" name="plain_default" value="'.$plain->id.'" '.(($default) ? 'checked="checked"' : '') . '/></td>';
					$plains_html .= '<td style="padding:0.5em;"><input class="plain" type="checkbox" id="plain'.$plain->id.'" name="plain[]" value="'.$plain->id .'"' . (($checked) ? ' checked="checked"' : '') . '/></td>';
					$plains_html .= '<td style="padding:0.5em;">'.$plain->name.'</td>';
					$zplain = ($plain->duration_count != -1) ? digistoreAdminHelper::getDurationType( $plain->duration_count, $plain->duration_type ) : JText::_( 'DS_UNLIMITED' );
					$plains_html .= '<td style="padding:0.5em;">'. $zplain .'</td>';
					$plains_html .= '<td style="padding:0.5em;"><input type="text" class="plain_amount" id="plain_amount'.$plain->id.'" name="plain_amount['.$plain->id.']" value="'.$price.'" style="text-align:center;"/></td>';
					$plains_html .= "</tr>\n";
				}
			}
			$plains_html .= "</table>";
		}
		$this->assign( "plains", $plains_html );
		$this->assign( "producttype", $producttype );

		if($producttype != 2 && $producttype != 3 && $producttype != 4){

			/* Renewals */

			//$renewals = $this->get( 'listPlains', 'digistorePlain' );
			$renewals = $plains;

			$sql = "SELECT `plan_id`, `price`, `default` FROM #__digistore_products_renewals WHERE product_id=" . $product->id;
			$db->setQuery( $sql );
			$renewalstoproduct = $isNew?array():$db->loadObjectList();

			$plains_html = "<table>";
			$plains_html .= "<tr style='background:#999'>
								<th width='1%' style='padding:0.5em;'>" . JText::_( 'Deafult' ) . "</th>
								<th width='1%' style='padding:0.5em;'><input type='checkbox' id='srenewals' name='srenewals' value='' onclick='checkRenewal(this.checked)'/></th>
								<th style='padding:0.5em;'>" . JText::_( 'Name' ) . "</th>
								<th style='padding:0.5em;'>" . JText::_( 'Terms' ) . "</th>
								<th style='padding:0.5em;'>" . JText::_( 'Price' ) . "</th>
							</tr>";
			if(isset($renewals) && is_array($renewals) && count($renewals) > 0){
				foreach($renewals as $plain ) {

					$checked = false;
					$default = false;
					$price = "";
					if(isset($renewalstoproduct) && count($renewalstoproduct) > 0){
						foreach($renewalstoproduct as $plain_value ) {
							if ( $plain_value->plan_id == $plain->id ) {
								$checked = true;
								$price = $plain_value->price;
								if ( $plain_value->default == 1 ) {
									$default = true;
								}
							}
						}
					}

					$plains_html .= "<tr>";
					$plains_html .= '<td style="padding:0.5em;"><input type="radio" class="renewal_default" id="renewal_default'.$plain->id.'"  name="renewal_default" value="' . $plain->id . '" ' . (($default) ? 'checked="checked"' : '') . '/></td>';
					$plains_html .= '<td style="padding:0.5em;"><input class="renewal" id="renewal'.$plain->id.'" type="checkbox" name="renewal[]" value="'.$plain->id.'"' . (($checked) ? ' checked="checked"' : '') . "/></td>";
					$plains_html .= "<td style='padding:0.5em;'>" . $plain->name . "</td>";
					$zplain = ($plain->duration_count != -1) ? digistoreAdminHelper::getDurationType( $plain->duration_count, $plain->duration_type ) : JText::_( 'DS_UNLIMITED' );
					$plains_html .= "<td style='padding:0.5em;'>" . $zplain . "</td>";
					$plains_html .= '<td style="padding:0.5em;"><input type="text" class="renewal_amount" id="renewal_amount'.$plain->id.'" name="renewal_amount['.$plain->id.']" value="' . $price .'" style="text-align:center;"/></td>';
					$plains_html .= "</tr>\n";
				}
			}
			$plains_html .= "</table>";
			$this->assign( "renewals", $plains_html );

			/* Emails */

			$emails = $this->get('Items', 'digistoreEmailreminder');

			$sql = "SELECT emailreminder_id
					FROM #__digistore_products_emailreminders
					WHERE product_id=" . $product->id;
			$db->setQuery( $sql );
			$emailreminderstoproduct = $isNew?array():$db->loadObjectList();

			$plains_html = "<table>";
			$plains_html .= "<tr style='background:#999'>
								<th width='1%' style='padding:0.5em;'><input type='checkbox' id='semails' name='semails' value='' onclick='checkEmailreminder()'/></th>
								<th style='padding:0.5em;'>" . JText::_( 'Name' ) . "</th>
								<th style='padding:0.5em;'>" . JText::_( 'Terms' ) . "</th>
							</tr>";

			if(isset($emails) && is_array($emails) && count($emails) > 0){
				foreach($emails as $plain ){
					$checked = false;
					if(isset($emailreminderstoproduct) && is_array($emailreminderstoproduct) && count($emailreminderstoproduct) > 0){
						foreach ( $emailreminderstoproduct as $plain_value ) {
							if ( $plain_value->emailreminder_id == $plain->id )
								$checked = true;
						}
					}
					$plains_html .= "<tr>";
					$plains_html .= "<td style='padding:0.5em;'><input class='emailreminder' type='checkbox' name='emailreminder[]' value='" . $plain->id . "'" . (($checked) ? " checked='checked'" : "") . "/></td>";
					$plains_html .= "<td style='padding:0.5em;'>" . $plain->name . "</td>";
					$plains_html .= "<td style='padding:0.5em;'>" . digistoreAdminHelper::getEmailReminderType( $plain->type, $plain->calc, $plain->date_calc, $plain->period ) . "</td>";
					$plains_html .= "</tr>\n";
				}
			}
			$plains_html .= "</table>\n\n";
			$this->assign( "emails", $plains_html );
		}
		$this->assign( "lists", $lists );
		$this->assign( "configs", $configs );

		/* Include */

		$include_products = $this->_models["digistoreproduct"]->getFeatured2( $product->id );

		$include_products_out = array();

		if(isset($include_products) && is_array($include_products) && count($include_products) > 0){
			foreach( $include_products as $key => $fproduct ) {
				$sql = "SELECT `plan_id`, `price`, `default` FROM #__digistore_products_plans WHERE product_id=" . $fproduct->id;
				$db->setQuery( $sql );
				$plainstoproduct = $isNew?array():$db->loadObjectList();

				$include_products_out[$key]['id'] = $fproduct->id;
				$include_products_out[$key]['name'] = $fproduct->name;

				// get plains for include product tab
				$include_plans = array(); $include_plain_default = 0;

				foreach ($plainstoproduct as $plain_value ) {
					$sql = "select * from #__digistore_plans where id=".$plain_value->plan_id;
					$db->setQuery($sql);
					$db->query();
					$plain = $db->loadAssocList();

					$price = $plain_value->price;
					$include_plans[] = JHTML::_('select.option',  $plain["0"]["id"],  $plain["0"]["name"] . ' - ' . digistoreAdminHelper::format_price( $price, $configs->currency, true, $configs ) );
					if ( $fproduct->planid == 0 ) {
						if ( $plain_value->default == 1 ) {
							$include_plain_default = $plain_value->plan_id;
						}
					} else {
						$include_plain_default = $fproduct->planid;
					}
				}


				$include_products_out[$key]['plans'] = JHTML::_('select.genericlist',  $include_plans, 'plan_include_id['.$key.']', 'class="inputbox" size="1" ', 'value', 'text', $include_plain_default);
			}
		}
		$this->assign( "include_products", $include_products_out );

		parent::display( $tpl );
	}

	function productincludeitem( $tpl = null ) {
		// Rand ID
		$id_rand = uniqid (rand ());
		$this->assign( "id_rand", $id_rand );

		$include = array(
			'id' => $id_rand,
			'name' => JText::_('Select product'),
			'plans' => null
		);

		$this->assign( "newinclude", $include );
		parent::display( $tpl );
	}
}

?>