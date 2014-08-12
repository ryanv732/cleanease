<?php

	// add to product field "mailchimplistid"
	$this->addField('#__digistore_products', 'mailchimplistid', 'varchar(255)', false, '');

	// delete mailchimplistid
	$this->dropField('#__digistore_settings', 'mailchimplistid');


?>