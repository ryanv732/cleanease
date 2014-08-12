<?php

class DSConfig {

	var $__dsconfig = null;

	function DSConfig( $directCall = true ) {

	if ( $directCall ) {
		trigger_error("You can not use the designer to create a class DSConfig. Use the static method DSConfig::get('configparam', 'defaultvalue')",E_USER_ERROR);
	}

		$db = JFactory::getDBO();
		$db->setQuery('SELECT * FROM #__digistore_settings WHERE id=1');
		$this->__dsconfig = $db->loadObject();

	}

	public static function &getInstance() {
		static $instance;
		if ( !is_object( $instance ) ) {
			$instance = new DSConfig( false );
		}
		return $instance;
	}

	public static function get($param, $default=null){
		$config = DSConfig::getInstance();

		if(isset($config->__dsconfig->$param)){
			return $config->__dsconfig->$param;
		}
		elseif(!is_null($default)){
			return $default;
		}
		else{
			return null;
		}
	}
}

?>