<?php
namespace Pecee\Http\Input\Validation;

class ValidateFileNotNullOrEmpty extends ValidateFile {

	public function validate() {
		return (!empty($this->fileName) && $this->fileSize > 0 && $this->fileError == 0);
	}

	public function getErrorMessage() {
		return lang('%s cannot be empty', array($this->name));
	}

}