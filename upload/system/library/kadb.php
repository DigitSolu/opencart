<?php
/*
	$Project: Ka Extensions $
	$Author: karapuz team <support@ka-station.com> $

	$Version: 4.1.1.8 $ ($Revision: 456 $)
	
	This class is deprecated. Please use \extension\ka_extensions\Db instead.
*/
require_once(modification(DIR_SYSTEM . 'library/extension/ka_extensions/db.php'));

class_alias('\extension\ka_extensions\Db', '\KaDb');