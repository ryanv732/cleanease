CREATE TABLE IF NOT EXISTS `#__digistore_cart` (
  `cid` int(11) NOT NULL auto_increment,
  `item_id` int(11) NOT NULL default '0',
  `userid` int(11) NOT NULL default '0',
  `quantity` int(11) NOT NULL default '0',
  `sid` int(11) NOT NULL default '0',
  `plan_id` int(11) NOT NULL default '0',
  `plugin_id` int(11) NOT NULL default '0',
  `renew` int(11) NOT NULL default '0',
  `renewlicid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`cid`),
  KEY `productid` (`item_id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `#__digistore_cartfields` (
  `fieldid` int(11) NOT NULL default '0',
  `productid` int(11) NOT NULL default '0',
  `sid` int(11) NOT NULL default '0',
  `optionid` int(11) NOT NULL default '-1',
  `cid` int(11) NOT NULL default '0'
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `#__digistore_categories` (
  `id` int(11) NOT NULL auto_increment,
  `parent_id` int(11) NOT NULL default '0',
  `title` varchar(50) NOT NULL default '',
  `name` varchar(255) NOT NULL default '',
  `image` varchar(500) default NULL,
  `section` varchar(50) NOT NULL default '',
  `image_position` varchar(10) NOT NULL default '',
  `description` text NOT NULL,
  `published` tinyint(1) NOT NULL default '0',
  `checked_out` int(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `editor` varchar(50) default NULL,
  `ordering` int(11) NOT NULL default '0',
  `access` tinyint(3) unsigned NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  `metakeywords` text NOT NULL,
  `metadescription` text NOT NULL,
  `images` text NOT NULL,
  `params` text NOT NULL,
  `thumb` varchar(500) NOT NULL,
  PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `#__digistore_currencies` (
  `id` int(11) NOT NULL auto_increment,
  `pluginid` varchar(30) NOT NULL default '',
  `currency_name` varchar(20) NOT NULL default '',
  `currency_full` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
drop table if exists `#__digistore_currency_symbols`;
CREATE TABLE IF NOT EXISTS `#__digistore_currency_symbols` (
  `id` int(11) NOT NULL auto_increment,
  `ccode` varchar(5) NOT NULL default '',
  `csym` varchar(255) NOT NULL default '',
  `cimg` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `#__digistore_customers` (
  `id` int(11) NOT NULL auto_increment,
  `address` varchar(200) NOT NULL default '',
  `city` varchar(100) default NULL,
  `state` varchar(100) default NULL,
  `province` varchar(100) default NULL,
  `zipcode` varchar(20) NOT NULL default '',
  `country` varchar(20) NOT NULL default '',
  `payment_type` varchar(20) NOT NULL default '',
  `company` varchar(100) NOT NULL default '',
  `firstname` varchar(50) NOT NULL default '',
  `lastname` varchar(50) NOT NULL default '',
  `shipaddress` varchar(200) NOT NULL default '',
  `shipcity` varchar(100) default NULL,
  `shipstate` varchar(100) default NULL,
  `shipzipcode` varchar(20) NOT NULL default '',
  `shipcountry` varchar(100) default NULL,
  `person` int(2) NOT NULL default '1',
  `taxnum` varchar(11) default '1',
  `taxclass` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `#__digistore_customfields` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(200) NOT NULL default '',
  `options` text NOT NULL,
  `published` int(2) NOT NULL default '0',
  `checked_out` int(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL default '0',
  `size` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `#__digistore_emailreminders` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `type` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `ordering` int(11) NOT NULL,
  `published` int(11) NOT NULL,
  `period` VARCHAR( 5 ) NULL ,
  `calc` VARCHAR( 6 ) NULL ,
  `date_calc` VARCHAR( 10 ) NULL ,
  PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `#__digistore_featuredproducts` (
  `productid` int(11) NOT NULL default '0',
  `featuredid` int(11) NOT NULL default '0',
  `planid` int(11) NOT NULL default '0'
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `#__digistore_fields` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL,
  `type` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `file` varchar(100) NOT NULL,
  `params` text NOT NULL,
  `comment` text NOT NULL,
  `published` tinyint(1) NOT NULL default '0',
  `access` tinyint(3) NOT NULL default '0',
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `ordering` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `#__digistore_fieldsets` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL,
  `fields` text NOT NULL,
  `core` int(2) NOT NULL default '0',
  `editable` int(2) NOT NULL default '1',
  `comment` text NOT NULL,
  `published` tinyint(1) NOT NULL default '0',
  `access` tinyint(3) NOT NULL default '0',
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `ordering` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `#__digistore_field_types` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default 'text',
  `default` text NOT NULL,
  `special` int(2) NOT NULL default '0',
  `published` tinyint(1) NOT NULL default '0',
  `access` tinyint(3) NOT NULL default '0',
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `ordering` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `#__digistore_languages` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(200) NOT NULL default '',
  `fefilename` text NOT NULL,
  `befilename` text NOT NULL,
  PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
truncate table `#__digistore_languages`;
CREATE TABLE IF NOT EXISTS `#__digistore_licensefields` (
  `licenseid` int(11) NOT NULL default '0',
  `fieldname` varchar(200) NOT NULL default '',
  `optioname` varchar(200) NOT NULL default ''
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `#__digistore_licenseprodfields` (
  `fieldid` int(11) NOT NULL default '0',
  `licenseid` int(11) NOT NULL default '0',
  `optionid` int(2) NOT NULL default '-1'
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `#__digistore_licenses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `licenseid` varchar(20) NOT NULL DEFAULT '',
  `userid` int(11) NOT NULL DEFAULT '0',
  `productid` int(11) NOT NULL DEFAULT '0',
  `domain` varchar(100) NOT NULL DEFAULT '',
  `amount_paid` float NOT NULL DEFAULT '0',
  `orderid` int(11) NOT NULL DEFAULT '0',
  `dev_domain` text NOT NULL,
  `hosting_service` varchar(50) NOT NULL DEFAULT '',
  `published` int(2) NOT NULL DEFAULT '1',
  `ltype` varchar(200) NOT NULL DEFAULT 'common',
  `package_id` int(11) NOT NULL,
  `purchase_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `expires` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `renew` int(11) NOT NULL DEFAULT '0',
  `renewlicid` int(11) NOT NULL DEFAULT '0',
  `download_count` int(11) NOT NULL DEFAULT '0',
  `plan_id` int(11) NOT NULL DEFAULT '0',
  `old_orders` text NOT NULL,
  `cancelled` tinyint(1) NOT NULL default '0',
  `cancelled_amount` float NOT NULL default '0',
  `domain_change` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `licenseid` (`licenseid`),
  KEY `orderid` (`orderid`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `#__digistore_licenses_notes` (
  `id` int(11) NOT NULL auto_increment,
  `lic_id` int(11) NOT NULL,
  `notes` text NOT NULL,
  `expires` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `#__digistore_licenses_payments` (
  `id` int(11) NOT NULL auto_increment,
  `lic_id` int(11) NOT NULL,
  `amount` float NOT NULL default '0',
  `expires` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `#__digistore_mailtemplates` (
  `id` int(11) NOT NULL auto_increment,
  `type` varchar(50) NOT NULL default '',
  `subject` text NOT NULL,
  `body` text NOT NULL,
  PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `#__digistore_orders` (
  `id` int(11) NOT NULL auto_increment,
  `userid` int(11) NOT NULL default '0',
  `order_date` int(11) NOT NULL default '0',
  `amount` float NOT NULL default '0',
  `number_of_licenses` int(11) NOT NULL default '0',
  `currency` varchar(10) NOT NULL default '',
  `status` varchar(10) NOT NULL default '',
  `tax` float NOT NULL default '0',
  `shipping` float NOT NULL default '0',
  `promocodeid` int(11) NOT NULL default '0',
  `promocode` varchar(255) NOT NULL default '',
  `promocodediscount` float NOT NULL default '0',
  `shipto` int(2) NOT NULL default '0',
  `fullshipto` text NOT NULL,
  `amount_paid` float NOT NULL default '0',
  `processor` varchar(100) NOT NULL,
  `published` int(11) NOT NULL default '0',
  `chargeback` tinyint(1) NOT NULL default '0',
  `analytics` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
UPDATE `#__digistore_orders` SET `status`='Active' WHERE `status`='in_progres';
CREATE TABLE IF NOT EXISTS `#__digistore_plans` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `duration_count` int(11) NOT NULL,
  `duration_type` int(11) NOT NULL,
  `ordering` int(11) NOT NULL,
  `published` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `#__digistore_prodfields` (
  `fieldid` int(11) NOT NULL default '0',
  `productid` int(11) NOT NULL default '0',
  `publishing` int(2) NOT NULL default '0',
  `mandatory` int(2) NOT NULL default '0'
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `#__digistore_productclass` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(200) NOT NULL default '',
  `published` tinyint(4) NOT NULL default '0',
  `ordering` int(11) NOT NULL default '0',
  `checked_out` int(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `#__digistore_products` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(150) NOT NULL default '',
  `images` varchar(150) NOT NULL default '',
  `discount` float NOT NULL default '0',
  `ordering` int(11) NOT NULL default '0',
  `file` varchar(150) NOT NULL default '',
  `description` text NOT NULL,
  `publish_up` int(11) NOT NULL default '0',
  `publish_down` int(11) NOT NULL default '0',
  `checked_out` int(11) NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `published` int(11) NOT NULL default '0',
  `passphrase` varchar(150) NOT NULL default '',
  `main_zip_file` mediumtext NOT NULL,
  `encoding_files` text NOT NULL,
  `domainrequired` int(11) NOT NULL default '0',
  `articlelink` text NOT NULL,
  `articlelinkid` int(11) NOT NULL default '0',
  `articlelinkuse` int(3) NOT NULL default '0',
  `shippingtype` int(11) NOT NULL default '0',
  `shippingvalue0` float NOT NULL default '0',
  `shippingvalue1` float NOT NULL default '0',
  `shippingvalue2` float NOT NULL default '0',
  `productemailsubject` text NOT NULL,
  `productemail` text NOT NULL,
  `sendmail` int(11) NOT NULL default '1',
  `popupwidth` int(11) NOT NULL default '800',
  `popupheight` int(11) NOT NULL default '600',
  `stock` int(11) NOT NULL default '0',
  `used` int(11) NOT NULL default '0',
  `usestock` int(11) NOT NULL default '0',
  `emptystockact` int(11) NOT NULL default '0',
  `showstockleft` int(11) NOT NULL default '0',
  `fulldescription` text NOT NULL,
  `metatitle` varchar(100) NOT NULL default '',
  `metakeywords` text NOT NULL,
  `metadescription` text NOT NULL,
  `access` tinyint(3) unsigned NOT NULL default '0',
  `prodtypeforplugin` text NOT NULL,
  `taxclass` int(11) NOT NULL default '0',
  `class` int(11) NOT NULL default '0',
  `sku` varchar(100) NOT NULL default '',
  `showqtydropdown` int(11) NOT NULL default '0',
  `priceformat` int(11) NOT NULL default '1',
  `featured` int(11) NOT NULL default '0',
  `prodimages` text NOT NULL,
  `defprodimage` varchar(500) NOT NULL default '',
  `mailchimplistid` varchar(255) NOT NULL default '',
  `subtitle` varchar(255) NOT NULL default '',
  `mailchimpapi` text NOT NULL,
  `mailchimplist` text NOT NULL,
  `mailchimpregister` int(3) NOT NULL default '0',
  `mailchimpgroupid` text NOT NULL,
  `video_url` text NOT NULL,
  `video_width` int(11) NOT NULL default '0',
  `video_height` int(11) NOT NULL default '0',
  `offerplans` int(3) NOT NULL default '0',
  `hide_public` tinyint(1) NOT NULL default '0',
  `cartlinkuse` int(3) NOT NULL default '0',
  `cartlink` text NOT NULL,
  PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `#__digistore_products_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL DEFAULT '0',
  `path` varchar(255) NOT NULL,
  `title` text NOT NULL,
  `default` int(3) NOT NULL,
  `order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__digistore_products_emailreminders` (
  `id` int(11) NOT NULL auto_increment,
  `product_id` int(11) NOT NULL,
  `emailreminder_id` int(11) NOT NULL,
  `send` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `#__digistore_products_plans` (
  `id` int(11) NOT NULL auto_increment,
  `product_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `price` float NOT NULL,
  `default` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `#__digistore_products_renewals` (
  `id` int(11) NOT NULL auto_increment,
  `product_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `price` float NOT NULL,
  `default` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `#__digistore_product_categories` (
  `productid` int(11) NOT NULL DEFAULT '0',
  `catid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`productid`,`catid`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
CREATE TABLE IF NOT EXISTS `#__digistore_promocodes` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(200) NOT NULL default '',
  `code` varchar(100) NOT NULL default '',
  `codelimit` int(11) NOT NULL default '0',
  `amount` float NOT NULL default '0',
  `codestart` int(11) NOT NULL default '0',
  `codeend` int(11) NOT NULL default '0',
  `forexisting` int(11) NOT NULL default '0',
  `published` int(11) NOT NULL default '0',
  `aftertax` int(11) NOT NULL default '0',
  `promotype` int(11) NOT NULL default '0',
  `used` int(11) NOT NULL default '0',
  `validfornew` int(11) NOT NULL default '0',
  `validforrenewal` int(11) NOT NULL default '0',
  `ordering` int(11) NOT NULL default '0',
  `checked_out` int(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `code` (`code`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `#__digistore_sendmails` (
  `id` int(11) NOT NULL auto_increment,
  `date` int(11) NOT NULL default '0',
  `email` varchar(40) NOT NULL default '',
  `body` text NOT NULL,
  `flag` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `#__digistore_session` (
  `sid` int(11) NOT NULL auto_increment,
  `create_time` int(11) NOT NULL default '0',
  `cart_details` text NOT NULL,
  `transaction_details` text NOT NULL,
  `shipping_details` int(11) NOT NULL default '0',
  `processor` varchar(250) NOT NULL,
  PRIMARY KEY  (`sid`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `#__digistore_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `currency` varchar(10) NOT NULL DEFAULT 'USD',
  `catalogue` INT(2) NULL DEFAULT '0',
  `showviewproducts` INT(2) NOT NULL DEFAULT 1,
  `price_groups_separation` VARCHAR(2) NULL DEFAULT ',',
  `showproductdetails` INT(2) NOT NULL DEFAULT '1',
  `store_name` varchar(200) NOT NULL DEFAULT '',
  `store_url` varchar(200) NOT NULL DEFAULT '',
  `store_email` varchar(200) NOT NULL DEFAULT '',
  `product_per_page` int(11) NOT NULL DEFAULT '10',
  `google_account` varchar(20) NOT NULL DEFAULT '',
  `country` varchar(30) NOT NULL DEFAULT '',
  `state` varchar(30) NOT NULL DEFAULT '',
  `city` varchar(30) NOT NULL DEFAULT '',
  `tax_option` varchar(20) NOT NULL DEFAULT '',
  `tax_rate` float NOT NULL DEFAULT '0',
  `tax_type` varchar(20) NOT NULL DEFAULT '',
  `totaldigits` int(11) NOT NULL DEFAULT '5',
  `decimaldigits` int(11) NOT NULL DEFAULT '2',
  `ftp_source_path` varchar(100) NOT NULL DEFAULT 'media',
  `time_format` varchar(150) NOT NULL DEFAULT '',
  `afteradditem` int(11) NOT NULL DEFAULT '0',
  `showreplic` int(11) NOT NULL DEFAULT '0',
  `idevaff` varchar(100) NOT NULL DEFAULT 'notapplied',
  `askterms` int(11) NOT NULL DEFAULT '0',
  `termsid` int(11) NOT NULL DEFAULT '-1',
  `termsheight` int(11) NOT NULL DEFAULT '-1',
  `termswidth` int(11) NOT NULL DEFAULT '-1',
  `topcountries` text NOT NULL,
  `usestoremail` int(11) NOT NULL DEFAULT '1',
  `catlayoutstyle` int(11) NOT NULL DEFAULT '1',
  `catlayoutcol` int(11) NOT NULL DEFAULT '1',
  `catlayoutrow` int(11) NOT NULL DEFAULT '1',
  `prodlayouttype` int(11) NOT NULL DEFAULT '0',
  `prodlayoutstyle` int(11) NOT NULL DEFAULT '1',
  `prodlayoutcol` int(11) NOT NULL DEFAULT '1',
  `prodlayoutrow` int(11) NOT NULL DEFAULT '1',
  `orderidvar` varchar(100) NOT NULL DEFAULT 'order_id',
  `ordersubtotalvar` varchar(100) NOT NULL DEFAULT 'order_subtotal',
  `idevpath` varchar(255) NOT NULL DEFAULT 'aff',
  `askforship` int(2) NOT NULL DEFAULT '1',
  `person` int(2) NOT NULL DEFAULT '1',
  `taxnum` int(11) NOT NULL DEFAULT '0',
  `modbuynow` int(11) NOT NULL DEFAULT '0',
  `usecimg` int(2) NOT NULL DEFAULT '0',
  `showthumb` int(2) NOT NULL DEFAULT '0',
  `showsku` int(2) NOT NULL DEFAULT '0',
  `sendmailtoadmin` int(2) NOT NULL DEFAULT '1',
  `directfilelink` int(2) NOT NULL DEFAULT '0',
  `debugstore` int(2) NOT NULL DEFAULT '0',
  `dumptofile` int(2) NOT NULL DEFAULT '0',
  `dumpvars` text NOT NULL,
  `ftranshtml` text NOT NULL,
  `thankshtml` text NOT NULL,
  `showprodshort` int(11) NOT NULL DEFAULT '0',
  `pendinghtml` text NOT NULL,
  `address` varchar(255) NOT NULL DEFAULT '',
  `zip` varchar(100) NOT NULL DEFAULT '',
  `phone` varchar(200) NOT NULL DEFAULT '',
  `fax` varchar(200) NOT NULL DEFAULT '',
  `afterpurchase` int(2) NOT NULL DEFAULT '1',
  `showoid` int(2) NOT NULL DEFAULT '1',
  `showoipurch` int(2) NOT NULL DEFAULT '1',
  `showolics` int(2) NOT NULL DEFAULT '1',
  `showopaid` int(2) NOT NULL DEFAULT '1',
  `showodate` int(2) NOT NULL DEFAULT '1',
  `showorec` int(2) NOT NULL DEFAULT '1',
  `showlid` int(2) NOT NULL DEFAULT '1',
  `showlprod` int(2) NOT NULL DEFAULT '1',
  `showloid` int(2) NOT NULL DEFAULT '1',
  `showldate` int(2) NOT NULL DEFAULT '1',
  `showldown` int(2) NOT NULL DEFAULT '1',
  `showcam` int(2) NOT NULL DEFAULT '1',
  `showcpromo` int(2) NOT NULL DEFAULT '1',
  `showcremove` int(2) NOT NULL DEFAULT '1',
  `showccont` int(2) NOT NULL DEFAULT '1',
  `showldomain` int(2) NOT NULL DEFAULT '1',
  `tax_classes` int(11) NOT NULL DEFAULT '0',
  `tax_base` int(11) NOT NULL DEFAULT '0',
  `tax_catalog` int(11) NOT NULL DEFAULT '0',
  `tax_shipping` int(11) NOT NULL DEFAULT '0',
  `tax_discount` int(11) NOT NULL DEFAULT '0',
  `discount_tax` int(11) NOT NULL DEFAULT '0',
  `tax_country` varchar(200) NOT NULL DEFAULT '',
  `tax_state` varchar(200) NOT NULL DEFAULT '',
  `tax_zip` varchar(200) NOT NULL DEFAULT '',
  `tax_price` int(11) NOT NULL DEFAULT '0',
  `tax_summary` int(11) NOT NULL DEFAULT '0',
  `shipping_price` int(11) NOT NULL DEFAULT '0',
  `product_price` int(11) NOT NULL DEFAULT '0',
  `tax_zero` int(11) NOT NULL DEFAULT '0',
  `tax_apply` varchar(200) NOT NULL DEFAULT '',
  `usestorelocation` int(11) NOT NULL DEFAULT '0',
  `allowcustomerchoseclass` int(11) NOT NULL DEFAULT '2',
  `takecheckout` int(11) NOT NULL DEFAULT '1',
  `continue_shopping_url` varchar(500) NOT NULL,
  `currency_position` int(1) NOT NULL DEFAULT '0',
  `showlterms` int(1) NOT NULL DEFAULT '0',
  `showlexpires` int(1) NOT NULL DEFAULT '0',
  `storedesc` mediumtext NOT NULL,
  `displaystoredesc` int(11) NOT NULL DEFAULT '1',
  `showfeatured` int(11) NOT NULL DEFAULT '0',
  `showrelated` int(11) NOT NULL DEFAULT '0',
  `hour24format` int(11) NOT NULL DEFAULT '0',
  `imagecatsizevalue` int(11) NOT NULL DEFAULT '100',
  `imagecatsizetype` int(11) NOT NULL DEFAULT '1',
  `imageprodsizefullvalue` int(11) NOT NULL DEFAULT '300',
  `imageprodsizefulltype` int(11) NOT NULL DEFAULT '1',
  `imageprodsizethumbvalue` int(11) NOT NULL DEFAULT '100',
  `imageprodsizethumbtype` int(11) NOT NULL DEFAULT '1',
  `imagecatdescvalue` int(11) NOT NULL DEFAULT '10',
  `imagecatdesctype` int(11) NOT NULL DEFAULT '0',
  `imageproddescvalue` int(11) NOT NULL DEFAULT '10',
  `imageproddesctype` int(11) NOT NULL DEFAULT '0',
  `mailchimplistid` varchar(255) DEFAULT NULL,
  `showpowered` tinyint(4) NOT NULL DEFAULT '1',
  `catlayoutimagesize` int(10) NOT NULL,
  `catlayoutimagetype` int(10) NOT NULL,
  `catlayoutdesclength` int(10) NOT NULL,
  `catlayoutdesctype` int(10) NOT NULL,
  `prodlayoutdesclength` int(10) NOT NULL,
  `prodlayoutdesctype` int(10) NOT NULL,
  `showfeatured_prod` int(10) NOT NULL,
  `prodlayoutthumbnails` int(10) NOT NULL,
  `prodlayoutthumbnailstype` int(10) NOT NULL,
  `prodlayoutlargeimgprev` int(10) NOT NULL,
  `prodlayoutlargeimgprevtype` int(10) NOT NULL,
  `prodlayoutlightimgprev` int(10) NOT NULL,
  `prodlayoutlightimgprevtype` int(10) NOT NULL,
  `showshortdescription` int(10) NOT NULL,
  `showlongdescription` int(10) NOT NULL,
  `showrelatedprod` int(10) NOT NULL,
  `last_check_date` datetime NOT NULL,
  `prodlayoutsort` int(10) NOT NULL,
  `relatedrows` int(10) NOT NULL DEFAULT '1',
  `relatedcolumns` int(10) NOT NULL DEFAULT '3',
  `grid_image_align` int(10) NOT NULL DEFAULT '0',
  `grid_title_align` int(10) NOT NULL DEFAULT '2',
  `grid_subtitle_align` int(10) NOT NULL DEFAULT '2',
  `grid_description_align` int(10) NOT NULL DEFAULT '2',
  `grid_quantity_align` int(10) NOT NULL DEFAULT '2',
  `grid_add_to_cat_align` int(10) NOT NULL DEFAULT '2',
  `list_multi_selection` int(10) NOT NULL DEFAULT '1',
  `list_orientation` int(10) NOT NULL DEFAULT '0',
  `featured_row` int(10) NOT NULL DEFAULT '1',
  `featured_col` int(10) NOT NULL DEFAULT '3',
  `store_logo` varchar(255) NOT NULL,
  `shopping_cart_style` int(10) NOT NULL DEFAULT '0',
  `cart_width` varchar(255) NOT NULL DEFAULT '100',
  `cart_width_type` int(10) NOT NULL DEFAULT '1',
  `cart_alignment` int(10) NOT NULL DEFAULT '1',
  `prod_short_desc_class` varchar(255) NOT NULL DEFAULT 'digi_short_desc_page',
  `prod_long_desc_class` varchar(255) NOT NULL DEFAULT 'digi_long_desc_page',
  `prods_short_desc_class` varchar(255) NOT NULL DEFAULT 'digi_short_desc_list',
  `prods_price_class` varchar(255) NOT NULL DEFAULT 'digi_price_list',
  `prods_name_class` varchar(255) NOT NULL DEFAULT 'digi_name_list',
  `cart_popoup_image` int(10) NOT NULL DEFAULT '50',
  `gallery_style` int(3) NOT NULL DEFAULT '1',
  `gallery_columns` int(3) NOT NULL DEFAULT '3',
  `in_trans` int(10) NOT NULL DEFAULT '0',
  `show_bradcrumbs` int(3) NOT NULL DEFAULT '0',
  `mailchimpapi` text NOT NULL,
  `mailchimplist` text NOT NULL,
  `showfacebook` int(2) NOT NULL DEFAULT '1',
  `showtwitter` int(2) NOT NULL DEFAULT '1',
  `showretwitter` int(2) NOT NULL DEFAULT '1',
  `newinstall` int(3) NOT NULL DEFAULT '1',
  `tax_eumode` tinyint(1) NOT NULL DEFAULT '0',
  `askforbilling` int(2) NOT NULL DEFAULT '0',
  `show_steps` int(3) NOT NULL DEFAULT '0',
  `askforcompany` int(3) NOT NULL DEFAULT '0',
  `conversion_id` varchar(255) NOT NULL DEFAULT '',
  `conversion_language` varchar(255) NOT NULL DEFAULT 'en',
  `conversion_format` varchar(255) NOT NULL DEFAULT '2',
  `conversion_color` varchar(255) NOT NULL DEFAULT 'ffffff',
  `conversion_label` varchar(255) NOT NULL DEFAULT '',
  `default_payment` varchar(255) NOT NULL DEFAULT '',
  `thousands_group_symbol` varchar(5) NOT NULL DEFAULT ',',
  PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `#__digistore_states` (
  `id` int(11) NOT NULL auto_increment,
  `state` varchar(200) NOT NULL default '',
  `country` varchar(200) NOT NULL default '',
  `eumember` int(2) NOT NULL default '0',
  `ccode` varchar(5) NOT NULL default '',
  `scode` varchar(5) NOT NULL default '',
  PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `#__digistore_taxplugin` (
  `pluginid` int(11) NOT NULL default '0',
  `ptypes` text NOT NULL,
  `locations` text NOT NULL
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `#__digistore_tax_customerclass` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(200) NOT NULL default '',
  `published` tinyint(4) NOT NULL default '0',
  `ordering` int(11) NOT NULL default '0',
  `checked_out` int(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `#__digistore_tax_productclass` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(200) NOT NULL default '',
  `published` tinyint(4) NOT NULL default '0',
  `ordering` int(11) NOT NULL default '0',
  `checked_out` int(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `#__digistore_tax_rate` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(250) NOT NULL default '',
  `country` varchar(200) NOT NULL default '',
  `state` varchar(200) NOT NULL default '',
  `zip` varchar(200) NOT NULL default '',
  `rate` double NOT NULL default '0',
  `published` int(11) NOT NULL default '1',
  `ordering` int(11) NOT NULL default '0',
  `checked_out` int(11) NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `name_2` (`name`),
  UNIQUE KEY `name_3` (`name`),
  UNIQUE KEY `name_4` (`name`),
  UNIQUE KEY `name_5` (`name`),
  UNIQUE KEY `name_6` (`name`),
  UNIQUE KEY `name_7` (`name`),
  UNIQUE KEY `name_8` (`name`),
  UNIQUE KEY `name_9` (`name`),
  UNIQUE KEY `name_10` (`name`),
  UNIQUE KEY `name_11` (`name`),
  UNIQUE KEY `name_12` (`name`),
  UNIQUE KEY `name_13` (`name`),
  UNIQUE KEY `name_14` (`name`),
  UNIQUE KEY `name_15` (`name`),
  UNIQUE KEY `name_16` (`name`),
  UNIQUE KEY `name_17` (`name`),
  UNIQUE KEY `name_18` (`name`),
  UNIQUE KEY `name_19` (`name`),
  UNIQUE KEY `name_20` (`name`),
  UNIQUE KEY `name_21` (`name`),
  UNIQUE KEY `name_22` (`name`),
  UNIQUE KEY `name_23` (`name`),
  UNIQUE KEY `name_24` (`name`),
  UNIQUE KEY `name_25` (`name`),
  UNIQUE KEY `name_26` (`name`),
  UNIQUE KEY `name_27` (`name`),
  UNIQUE KEY `name_28` (`name`),
  UNIQUE KEY `name_29` (`name`),
  UNIQUE KEY `name_30` (`name`),
  UNIQUE KEY `name_31` (`name`),
  UNIQUE KEY `name_32` (`name`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `#__digistore_tax_rule` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(200) NOT NULL default '',
  `cclass` text NOT NULL,
  `pclass` text NOT NULL,
  `trate` text NOT NULL,
  `ptype` text NOT NULL,
  `published` int(11) NOT NULL default '1',
  `ordering` int(11) NOT NULL default '0',
  `checked_out` int(11) NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
drop table if exists `#__digistore_topleveldomains`;
CREATE TABLE IF NOT EXISTS `#__digistore_topleveldomains` (
  `tld_id` mediumint(9) NOT NULL auto_increment,
  `tld` varchar(6) NOT NULL default '',
  `fullname` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`tld_id`),
  KEY `tld` (`tld`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `#__digistore_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0',
  `productid` int(11) NOT NULL DEFAULT '0',
  `emailname` text NOT NULL,
  `emailid` int(11) NOT NULL,
  `to` text NOT NULL,
  `subject` text NOT NULL,
  `body` text NOT NULL,
  `buy_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `send_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `download_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `buy_type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `#__digistore_promocodes_orders` (
  `productid` int(11) NOT NULL,
  `promoid` int(11) NOT NULL,
  KEY `productid` (`productid`,`promoid`)
);
CREATE TABLE IF NOT EXISTS `#__digistore_promocodes_products` (
  `productid` int(11) NOT NULL,
  `promoid` int(11) NOT NULL,
  KEY `productid` (`productid`,`promoid`)
);
CREATE TABLE IF NOT EXISTS `#__digistore_product_groups` (
  `id_product` INT( 11 ) NOT NULL ,
  `id_group` INT( 11 ) NOT NULL ,
  PRIMARY KEY (  `id_product` ,  `id_group` )
);
CREATE TABLE IF NOT EXISTS `#__digistore_product_groups_exp` (
  `id_product` INT( 11 ) NOT NULL ,
  `id_group` INT( 11 ) NOT NULL ,
  PRIMARY KEY (  `id_product` ,  `id_group` )
);