<?php
/**
 * @package		OpenCart
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2017, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.com
*/

/**
* Document class
*/
class Document {
	private $title;
	private $description;
	private $keywords;

	private $links = array();
	private $styles = array();
	private $scripts = array();
//karapuz (Ka Extensions platform) 
	protected $ka_meta = array();
	protected $ka_labels = array();
///karapuz (Ka Extensions platform)

	/**
     * 
     *
     * @param	string	$title
     */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
     * 
	 * 
	 * @return	string
     */
	public function getTitle() {
		return $this->title;
	}

	/**
     * 
     *
     * @param	string	$description
     */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
     * 
     *
     * @param	string	$description
	 * 
	 * @return	string
     */
	public function getDescription() {
		return $this->description;
	}

	/**
     * 
     *
     * @param	string	$keywords
     */
	public function setKeywords($keywords) {
		$this->keywords = $keywords;
	}

	/**
     *
	 * 
	 * @return	string
     */
	public function getKeywords() {
		return $this->keywords;
	}
	
	/**
     * 
     *
     * @param	string	$href
	 * @param	string	$rel
     */
	public function addLink($href, $rel) {
		$this->links[$href] = array(
			'href' => $href,
			'rel'  => $rel
		);
	}

	/**
     * 
	 * 
	 * @return	array
     */
	public function getLinks() {
		return $this->links;
	}

	/**
     * 
     *
     * @param	string	$href
	 * @param	string	$rel
	 * @param	string	$media
     */
	public function addStyle($href, $rel = 'stylesheet', $media = 'screen', $position = 'header') {
		$this->styles[$position][$href] = array(
			'href'  => $href,
			'rel'   => $rel,
			'media' => $media
		);
	}

	/**
     * 
	 * 
	 * @return	array
     */
	public function getStyles($position = 'header') {
		if (isset($this->styles[$position])) {
			return $this->styles[$position];
		} else {
			return array();
		}
	}

	/**
     * 
     *
     * @param	string	$href
	 * @param	string	$position
     */
//karapuz (Ka Extensions platform) 
	public function addKaMeta($name, $content) {
		$this->ka_meta[$name] = $content;
	}
	
	public function getKaMeta() {
		return $this->ka_meta;
	}

	public function addKaJsLables($data) {
		$this->ka_labels = array_merge($this->ka_labels, $data);
	}
	
	public function getKaJsLabels() {
		return $this->ka_labels;
	}
///karapuz (Ka Extensions platform)
	public function addScript($href, $position = 'header') {
		$this->scripts[$position][$href] = $href;
	}

	/**
     * 
     *
     * @param	string	$position
	 * 
	 * @return	array
     */
	public function getScripts($position = 'header') {
		if (isset($this->scripts[$position])) {
			return $this->scripts[$position];
		} else {
			return array();
		}
	}
}