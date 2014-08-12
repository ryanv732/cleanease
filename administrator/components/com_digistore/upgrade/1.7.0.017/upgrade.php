<?php

	// Drop UNIQUE INDEX licenseid
	$prefix017 = $this->db->getPrefix();
	$table017 = str_replace('#__', $prefix017, '#__digistore_licenses');
	$this->db->setQuery('ALTER TABLE '.$table017.' DROP INDEX licenseid');
	$this->db->query();



?>