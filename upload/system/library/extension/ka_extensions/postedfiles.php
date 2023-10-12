<?php
/*
	$Project$
	$Author$

	$Version$ ($Revision$)
*/

namespace extension\ka_extensions;

class PostedFiles {

	protected $files_tmp_dir = DIR_CACHE;
	protected $session, $request;
	
	public function __construct($tmp_dir = '') {
	
		if (!empty($tmp_dir)) {
			$this->files_tmp_dir = $tmp_dir;
		}
		
		$this->request = KaGlobal::getRegistry()->get('request');
		$this->session = KaGlobal::getRegistry()->get('session');
	}
	
	
	/*
		The function forgets the posted files data.
	*/
	public function clearPostedFiles($prefix) {
		$this->session->data['ka_ext_posted_files'][$prefix] = [];
  	}
	
  	/*
  		Return a posted file for specific parameters. Example:
  			$file = $this->getPostedFile('product_downloads','file', 0);
  			
  		File array:
			'name' => real file name
			'type' => mime file type
			'size' => file size
			'file' => fill path to the temporary file
  			
  	*/
	public function getPostedFile($prefix, $code, $key) {
		
		if (!empty($this->session->data['ka_ext_posted_files'][$prefix][$code][$key])) {
			return $this->session->data['ka_ext_posted_files'][$prefix][$code][$key];
		}
		
		return [];
	}  	
	
	
  	/*
  		The function saves files from $this->request->files array to the directory
  		and keeps an information about these files.
  	*/
  	public function savePostedFiles($prefix, $code) {

  		if (!isset($this->session->data['ka_ext_posted_files'])) {
			$this->session->data['ka_ext_posted_files'] = array();
		}

  		if (empty($this->request->files) || empty($this->request->files[$prefix])) {
  			return false;
  		}

	  	foreach ($this->request->files[$prefix]['size'] as $k => $file) {

  			$file_data = [
  				'name'     => $this->request->files[$prefix]['name'][$k][$code],
  				'type'     => $this->request->files[$prefix]['type'][$k][$code],
  				'tmp_name' => $this->request->files[$prefix]['tmp_name'][$k][$code],
  				'size'     => $this->request->files[$prefix]['size'][$k][$code],
  				'error'    => $this->request->files[$prefix]['error'][$k][$code],
  			];
  			
			if (!is_uploaded_file($file_data['tmp_name'])) {
				continue;
			}
			
			$filename = tempnam($this->files_tmp_dir, 'dn-');
			
			move_uploaded_file($file_data['tmp_name'], $filename);
			if (!file_exists($filename)) {
				continue;
			}
			
			$key = 'id' . $k;
			$this->session->data['ka_ext_posted_files'][$prefix][$code][$key] = array(
				'name' => $file_data['name'],
				'type' => $file_data['type'],
				'size' => $file_data['size'],
				'file' => $filename,
			);
			
			$this->request->post[$prefix][$k][$code . '_id'] = $key;
			$this->request->post[$prefix][$k][$code] = $this->session->data['ka_ext_posted_files'][$prefix][$code][$key];
		}
		
		return true;
	}	
}