<?php

namespace extension\ka_extensions\library;

class Mail extends \Mail {

	public function addNamedAttachment($filename, $name) {
		$this->attachments[$name] = $filename;
	}

	public function addAttachment($filename) {
		$this->addNamedAttachment($filename, basename($filename));
	}	
}