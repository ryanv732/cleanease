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

class ImageHelper {
	/**
	 *	Genarate Category Image 
	 *
	 * 	return: full url to image
	 */
	public static function ShowCategoryImage($imagename=null){
		if(is_null($imagename) || empty($imagename)){
			return null;
		}

		$path = JPATH_ROOT.DS."images".DS."stories".DS."digistore".DS."categories".DS;
		$pathfly = JPATH_ROOT.DS."images".DS."stories".DS."digistore".DS."categories".DS.'fly'.DS;
		$filepath = $path.$imagename;
		$filepathfly = $pathfly.$imagename;
		$resize_flag = true;

		if(file_exists($filepathfly)){
			$filesize = getimagesize($filepathfly);
			if(DSConfig::get('catlayoutimagetype') == 1){
				if(DSConfig::get('catlayoutimagesize') == $filesize["0"]){
					$resize_flag = false;
				}
			}
			else{
				if(DSConfig::get('catlayoutimagesize') == $filesize["1"]){
					$resize_flag = false;
				}
			}
		}

		if($resize_flag){
			if(file_exists($filepath)){
				if (!file_exists($pathfly)){
					@mkdir($pathfly, 0755);
				}

				// resize source image file
				require_once JPATH_COMPONENT_ADMINISTRATOR.DS.'libs'.DS.'thumbnail.inc.php';

				// fullpath
				$thumbresizer = new Thumbnail($filepath);
				// 1- wide, 0 - high
				if(DSConfig::get('catlayoutimagetype') == 1){
					$thumbresizer->maxHeight = 0;
					$thumbresizer->maxWidth = DSConfig::get('catlayoutimagesize');
					$newsize = $thumbresizer->calcWidth( DSConfig::get('catlayoutimagesize'), $thumbresizer->getCurrentHeight() );
				}
				else{
					$thumbresizer->maxWidth = 0;
					$thumbresizer->maxHeight = DSConfig::get('catlayoutimagesize');
					$newsize = $thumbresizer->calcHeight( $thumbresizer->getCurrentWidth(), DSConfig::get('catlayoutimagesize') );
				}
				$thumbresizer->resize($newsize['newWidth'], $newsize['newHeight']);
				$thumbresizer->save($filepathfly);
				$thumbresizer->destruct();
				$url = JURI::root() . 'images/stories/digistore/categories/fly/'.$imagename;
			}
			else{
				$url = null;
			}
		}
		else{
			$url = JURI::root().'images/stories/digistore/categories/fly/'.$imagename;
		}
		return $url;
	}

	/**
	 *	Genarate Product Image 
	 *
	 * 	return: full url to image
	 */
	public static function GetProductImageURL($imagename=null, $popup=""){
		if(is_null($imagename) || empty($imagename)){
			return null;
		}

		$path = JPATH_ROOT . DS . "images" . DS . "stories" . DS .  "digistore" . DS . "products" . DS;
		$pathfly = JPATH_ROOT . DS . "images" . DS . "stories" . DS .  "digistore" . DS . "products" . DS . 'fly' . DS;

		$filepath = $path . $imagename;
		$filepathfly = $pathfly . $imagename;

		$resize_flag = true;

		$true_size = DSConfig::get('prodlayoutthumbnails');
		if($popup == "popup"){
			$true_size = DSConfig::get('cart_popoup_image');
			$pathfly = JPATH_ROOT . DS . "images" . DS . "stories" . DS .  "digistore" . DS . "products" . DS . 'popup' . DS;
			$filepathfly = $pathfly . $imagename;
		}

		if(file_exists($filepathfly)){
			$filesize = getimagesize($filepathfly);
			if(DSConfig::get('prodlayoutthumbnailstype') == 1){
				if($true_size == $filesize[0]){
					$resize_flag = false;
				}
			}
			else{
				if($true_size == $filesize[1]){
					$resize_flag = false;
				}
			}
		}

		if($resize_flag){
			if(file_exists($filepath)){
				if(!file_exists($pathfly)){
					@mkdir($pathfly, 0755); 
				}
				// resize source image file
				require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'libs' . DS . 'thumbnail.inc.php';

				// fullpath
				$thumbresizer = new Thumbnail($filepath);
				// 1- wide, 0 - high
				if(DSConfig::get('prodlayoutthumbnailstype') == 1){
					$thumbresizer->maxHeight = 0;
					$thumbresizer->maxWidth = $true_size;
					$newsize = $thumbresizer->calcWidth( $true_size, $thumbresizer->getCurrentHeight() );
				}
				else{
					$thumbresizer->maxWidth = 0;
					$thumbresizer->maxHeight = $true_size;
					$newsize = $thumbresizer->calcHeight( $thumbresizer->getCurrentWidth(), $true_size );
				}
				$thumbresizer->resize($newsize['newWidth'], $newsize['newHeight']);
				$thumbresizer->save($filepathfly);
				$thumbresizer->destruct();
				if($popup == "popup"){
					$url = JURI::root() . 'images/stories/digistore/products/popup/'.$imagename;
				}
				else{
					$url = JURI::root() . 'images/stories/digistore/products/fly/'.$imagename;
				}
			}
			else{
				$url = null;
			}
		}
		else{
			if($popup == "popup"){
				$url = JURI::root() . 'images/stories/digistore/products/popup/'.$imagename;
			}
			else{
				$url = JURI::root() . 'images/stories/digistore/products/fly/'.$imagename;
			}
		}
		return $url;
	}


	public static function shouStoreLogoThumb($imagename=null){
		if (is_null($imagename) || empty($imagename)) return null;

		$path = JPATH_ROOT . DS . "images" . DS . "stories" . DS .  "digistore" . DS . "store_logo" . DS;
		$pathfly = JPATH_ROOT . DS . "images" . DS . "stories" . DS .  "digistore" . DS . "store_logo" . DS . 'thumb' . DS;

		$filepath = $path . $imagename;
		$filepathfly = $pathfly . $imagename;

		$resize_flag = true;

		if(file_exists($filepathfly)) {
			$filesize = getimagesize($filepathfly);
			if (DSConfig::get('imageprodsizethumbtype') == 1) {
				if ( DSConfig::get('imageprodsizethumbvalue') ==  $filesize[0] ) { $resize_flag = false; }
			} else {
				if ( DSConfig::get('imageprodsizethumbvalue') ==  $filesize[1] ) { $resize_flag = false; }
			}
		}

		/*if($resize_flag) {

			if ( file_exists($filepath) ) {

				if (!file_exists($pathfly)) { @mkdir($pathfly, 0755); }

					// resize source image file
					require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'libs' . DS . 'thumbnail.inc.php';

					// fullpath
					$thumbresizer = new Thumbnail($filepath);
					// 1- wide, 0 - high
					if (DSConfig::get('imageprodsizethumbtype') == 1) {
						$thumbresizer->maxHeight = 0;
						$thumbresizer->maxWidth = DSConfig::get('imageprodsizethumbvalue');
						$newsize = $thumbresizer->calcWidth( DSConfig::get('imageprodsizethumbvalue'), $thumbresizer->getCurrentHeight() );
					} else {
						$thumbresizer->maxWidth = 0;
						$thumbresizer->maxHeight = DSConfig::get('imageprodsizethumbvalue');
						$newsize = $thumbresizer->calcHeight( $thumbresizer->getCurrentWidth(), DSConfig::get('imageprodsizethumbvalue') );
					}
					$thumbresizer->resize($newsize['newWidth'], $newsize['newHeight']);
					$thumbresizer->save($filepathfly);
					$thumbresizer->destruct();

					$url = JURI::root() . 'images/stories/digistore/store_logo/thumb/'.$imagename;

			} else {
				$url = null;
			}

		} else {
			$url = JURI::root() . 'images/stories/digistore/store_logo/thumb/'.$imagename;
		}

		return $url;*/
		if($resize_flag){
			if(file_exists($filepath)){
				if (!file_exists($pathfly)){
					@mkdir($pathfly, 0755);
				}

				// resize source image file
				require_once JPATH_COMPONENT_ADMINISTRATOR.DS.'libs'.DS.'thumbnail.inc.php';

				// fullpath
				$thumbresizer = new Thumbnail($filepath);
				// 1- wide, 0 - high
				if(DSConfig::get('catlayoutimagetype') == 1){
					$thumbresizer->maxHeight = 0;
					$thumbresizer->maxWidth = DSConfig::get('catlayoutimagesize');
					$newsize = $thumbresizer->calcWidth( DSConfig::get('catlayoutimagesize'), $thumbresizer->getCurrentHeight() );
				}
				else{
					$thumbresizer->maxWidth = 0;
					$thumbresizer->maxHeight = DSConfig::get('catlayoutimagesize');
					$newsize = $thumbresizer->calcHeight( $thumbresizer->getCurrentWidth(), DSConfig::get('catlayoutimagesize') );
				}
				$thumbresizer->resize($newsize['newWidth'], $newsize['newHeight']);
				$thumbresizer->save($filepathfly);
				$thumbresizer->destruct();
				$url = JURI::root() . 'images/stories/digistore/store_logo/thumb/'.$imagename;
			}
			else{
				$url = null;
			}
		}
		else{
			$url = JURI::root().'images/stories/digistore/store_logo/thumb/'.$imagename;
		}
		return $url;
	}


	/**
	 *	Genarate Product Thumb Image 
	 *
	 * 	return: full url to image
	 */
	public static function GetProductThumbImageURL( $imagename=null, $prev="") {
		if (is_null($imagename) || empty($imagename)) return null;

		$path = JPATH_ROOT . DS . "images" . DS . "stories" . DS .  "digistore" . DS . "products" . DS;
		$pathfly = JPATH_ROOT . DS . "images" . DS . "stories" . DS .  "digistore" . DS . "products" . DS . 'thumb' . DS;

		$filepath = $path . $imagename;
		$filepathfly = $pathfly . $imagename;

		$resize_flag = true;

		$from_database_size = DSConfig::get('imageprodsizethumbvalue');
		if($prev != ""){
			$from_database_size = DSConfig::get('prodlayoutlargeimgprev');
			$pathfly = JPATH_ROOT . DS . "images" . DS . "stories" . DS .  "digistore" . DS . "products" . DS . "viewproduct" . DS;
			$filepathfly = $pathfly . $imagename;
		}

		if(file_exists($filepathfly)) {
			$filesize = getimagesize($filepathfly);
			if(DSConfig::get('prodlayoutlargeimgprevtype') == 1){
				if($from_database_size ==  $filesize[0]){
					$resize_flag = false;
				}
			}
			else{
				if($from_database_size ==  $filesize[1]){
					$resize_flag = false;
				}
			}
		}

		if($resize_flag){
			if ( file_exists($filepath) ) {

				if (!file_exists($pathfly)) { @mkdir($pathfly, 0755); }

					// resize source image file
					require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'libs' . DS . 'thumbnail.inc.php';

					// fullpath
					$thumbresizer = new Thumbnail($filepath);
					// 1- wide, 0 - high
					if (DSConfig::get('prodlayoutlargeimgprevtype') == 1) {
						$thumbresizer->maxHeight = 0;
						$thumbresizer->maxWidth = $from_database_size;
						$newsize = $thumbresizer->calcWidth($from_database_size, $thumbresizer->getCurrentHeight() );
					} else {
						$thumbresizer->maxWidth = 0;
						$thumbresizer->maxHeight = $from_database_size;
						$newsize = $thumbresizer->calcHeight( $thumbresizer->getCurrentWidth(), $from_database_size);
					}
					$thumbresizer->resize($newsize['newWidth'], $newsize['newHeight']);
					$thumbresizer->save($filepathfly);
					$thumbresizer->destruct();
					if($prev == ""){
						$url = JURI::root() . 'images/stories/digistore/products/thumb/'.$imagename;
					}
					else{
						$url = JURI::root() . 'images/stories/digistore/products/viewproduct/'.$imagename;
					}

			} else {
				$url = null;
			}

		}
		else{
			if($prev == ""){
				$url = JURI::root() . 'images/stories/digistore/products/thumb/'.$imagename;
			}
			else{
				$url = JURI::root() . 'images/stories/digistore/products/viewproduct/'.$imagename;
			}
		}
		return $url;
	}


	public static function ShowImage($prod){
		$title = $prod->image_title;
		$title = str_replace('"', "&quot;", $title);
		if(trim($title) != ""){
			$title = 'title="'.$title.'"';
		}
		$tag_image = "<div class='dsimage'><img ".$title." src=\"".ImageHelper::GetProductThumbImageURL($prod->defprodimage)."\" alt=\"{$prod->name}  image\"  class=\"ijd-center\"></div>";

		return $tag_image;
	}

	public static function GetProductThumbImageURLBySize($imagename=null, $size) {
		if (is_null($imagename) || empty($imagename)) {
			return null;
		}
		if ($size == 0) {
			return ImageHelper::GetProductThumbImageURL($imagename);
		}

		$path = JPATH_ROOT . DS . "images" . DS . "stories" . DS .  "digistore" . DS . "products" . DS;
		$pathfly = JPATH_ROOT . DS . "images" . DS . "stories" . DS .  "digistore" . DS . "products" . DS . 'thumb' . DS . "preview" .DS;

		$filepath = $path . $imagename;
		$filepathfly = $pathfly . $imagename;

		$resize_flag = true;

		if(file_exists($filepathfly)){
			$filesize = getimagesize($filepathfly);
			if(DSConfig::get('imageprodsizethumbtype') == 1){
				if($size ==  $filesize["0"]){
					$resize_flag = false;
				}
			}
			else{
				if($size == $filesize["1"]){
					$resize_flag = false;
				}
			}
		}
		if($resize_flag){
			if(file_exists($filepath)){
				if(!file_exists($pathfly)){
					@mkdir($pathfly, 0755);
				}
				// resize source image file
				require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'libs' . DS . 'thumbnail.inc.php';
				// fullpath
				$thumbresizer = new Thumbnail($filepath);
				// 1- wide, 0 - high
				if(DSConfig::get('imageprodsizethumbtype') == 1) {
					$thumbresizer->maxHeight = 0;
					$thumbresizer->maxWidth = $size;
					$newsize = $thumbresizer->calcWidth($size, $thumbresizer->getCurrentHeight());
				}
				else{
					$thumbresizer->maxWidth = 0;
					$thumbresizer->maxHeight = $size;
					$newsize = $thumbresizer->calcHeight($thumbresizer->getCurrentWidth(), $size);
				}
				$thumbresizer->resize($newsize['newWidth'], $newsize['newHeight']);
				$thumbresizer->save($filepathfly);
				$thumbresizer->destruct();
				$url = JURI::root().'images/stories/digistore/products/thumb/preview/'.$imagename;
// 				$url = JPATH_SITE.'/images/stories/digistore/products/thumb/preview/'.$imagename;
			} else {
				$url = null;
			}
		} else {
			$url = JURI::root() . 'images/stories/digistore/products/thumb/preview/'.$imagename;
// 			$url = JPATH_SITE . '/2images/stories/digistore/products/thumb/preview/'.$imagename;
		}
		return $url;
	}
}

?>