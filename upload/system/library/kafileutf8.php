<?php
/*
	$Project: Ka Extensions $
	$Author: karapuz team <support@ka-station.com> $

	$Version: 4.1.1.8 $ ($Revision: 456 $)
	
	This class is deprecated. Please use \extension\ka_extensions\FileUTF8 instead.
*/

require_once(modification(DIR_SYSTEM . 'library/extension/ka_extensions/fileutf8.php'));

class_alias('\extension\ka_extensions\FileUTF8', '\KaFileUTF8');