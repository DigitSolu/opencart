<?php
/*
	$Project: Ka Extensions $
	$Author: karapuz team <support@ka-station.com> $

	$Version: 4.1.1.8 $ ($Revision: 456 $)
	
	This class is deprecated. Please use \extension\ka_extensions\Store instead.
*/
require_once(modification(DIR_SYSTEM . 'library/extension/ka_extensions/store.php'));

class_alias('\extension\ka_extensions\Store', '\KaStore');