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

jimport ("joomla.application.component.view");

class digistoreAdminViewdigistoreCategories extends obView {

	protected $items;
	protected $pagination;

	function display($tpl=null)
	{
		// Access check.
		if (!JFactory::getUser()->authorise('digistore.categories', 'com_digistore'))
		{
			return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
		}

		JToolBarHelper::title(JText::_('VIEWDSADMINCATEGORIES'), 'generic.png');
		JToolBarHelper::addNew();
		JToolBarHelper::editList();
		JToolBarHelper::divider();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::divider();
		JToolBarHelper::deleteList();

		$categories = $this->get('Items');
		$pagination = $this->get('Pagination');

		$this->cats = $categories;
		$this->pagination = $pagination;

		parent::display($tpl);
	}

	function editForm($tpl = null)
	{
		$db = JFactory::getDBO();
		$category = $this->get('category');
		$isNew = ($category->id < 1);
		$text = $isNew?JText::_('New'):JText::_('Edit');

		JToolBarHelper::title(JText::_('DSCATEGORY').":<small>[".$text."]</small>");
		JToolBarHelper::save();
		if ($isNew) {
			JToolBarHelper::divider();
			JToolBarHelper::cancel();

		} else {
// 			JToolbarHelper::save2new();
			JToolBarHelper::apply();
			JToolBarHelper::divider();
			JToolBarHelper::cancel ('cancel', 'Close');

		}

		$this->assign("cat", $category);
// 		var_dump($category);
// 		$lists['access'] = JHTML::_('list.accesslevel',  $category );
		$lists['access'] = JHTML::_('access.assetgrouplist','access', $category->access );

		if ($isNew){
			$lists['published'] = JHTML::_('select.booleanlist',  'published', '', "1" );
		}
		else{
			$lists['published'] = JHTML::_('select.booleanlist',  'published', '', $category->published );
		}

		// build the html select list for ordering
		$query = 'SELECT ordering AS value, name AS text'
		. ' FROM #__digistore_categories'
		. ' ORDER BY ordering'
		;

		if ($isNew) {
			$lists['ordering'] = JHtml::_('list.ordering','ordering', $query, '');
// 			$lists['ordering'] = JHTML::_('list.specificordering',  $category, '', $query );
		} else {
			$lists['ordering'] = JHtml::_('list.ordering','ordering', $query, '', $category->id);
// 			$lists['ordering'] = JHTML::_('list.specificordering',  $category, $category->id, $query );
		}

		// Upload image 
		global $isJ25; 
		if($isJ25) 
			JHTML::_('behavior.mootools');
		else 
			JHTML::_('jquery.framework');
		
		$doc = JFactory::getDocument();
		$doc->addScript(JURI::root() . 'components/com_digistore/assets/js/ajaxupload.js');
		if($isJ25){
			$doc->addScript(JURI::root() . 'components/com_digistore/assets/js/jquery.digistore.js');
			$doc->addScript(JURI::root() . 'components/com_digistore/assets/js/jquery.noconflict.digistore.js');
		}
		$doc->addScript(JURI::root() . 'administrator/components/com_digistore/assets/js/redactor.min.js');
		
		$doc->addStyleSheet(JURI::root() . 'administrator/components/com_digistore/assets/css/redactor.css');

		$upload_script = '
			window.addEvent( "domready", function(){
				new AjaxUpload("ajaxuploadcatimage", {
					action: "' . JURI::root() . 'administrator/index.php?option=com_digistore&controller=digistoreCategories&task=uploadimage&tmpl=component&no_html=1",
					name: "catimage",
					multiple: false,
					data: {\'CatId\' : \''.( $category->id ? $category->id : 0 ).'\'},
					onComplete: function(file, response){
						document.getElementById("catthumbnail").innerHTML = response;
					}
				});

 				jQuery(".useredactor").redactor();
				jQuery(".redactor_useredactor").css("height","400px");
			});
		';
		$doc->addScriptDeclaration( $upload_script );
		$query = 'SELECT s.id AS value, s.name AS text FROM #__digistore_categories AS s  ORDER BY s.ordering';
		$db->setQuery( $query );
		$categories = $db->loadObjectList();
		#$lists['parent'] = digistoreAdminHelper::getParent($category);
		$lists['parent'] = $this->getParentCategory($category);
		$this->assign("lists", $lists);
		parent::display($tpl);

	}
	
	public function getParentCategory($row){
		$db = JFactory::getDBO();
		if ($row->id == '') {
			$row->id = 0;
		}
		$query = 'SELECT s.id AS value, s.* FROM #__digistore_categories AS s  
					WHERE s.id NOT IN('.$row->id.')
					ORDER BY s.parent_id ASC ,s.ordering ASC';
		$db->setQuery($query);
		$mitems = $db->loadObjectList();

		$children = array();
		if ( $mitems )
		{
			foreach ( $mitems as $v )
			{
				$v->title 		= $v->name;
				$pt = $v->parent_id;
				$list = @$children[$pt] ? $children[$pt] : array();
				array_push($list, $v);
				$children[$pt] = $list;
			}
		}
		$list = JHTML::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0);
		$mitems = array();
		// @$mitems[] = JHTML::_('select.option', 0, JText::_('PLG_OBSS_INTER_HIKASHOP_ALL_CATEGORIES'));
		$msg = JText::_('HELPERTOP');
		$mitems[] = JHTML::_('select.option', 0, $msg );
		foreach ($list as $item)
		{
			$item->treename = JString::str_ireplace('&#160;', '- ', $item->treename);
			$mitems[] = JHTML::_('select.option', $item->id, $item->treename);
		}
		$output = JHTML::_('select.genericlist',  $mitems, 'parent_id', 'class="inputbox" size="10"', 'value', 'text', $row->parent_id );
		return $output;
	}
}

?>