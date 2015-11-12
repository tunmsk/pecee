<?php
namespace Pecee\Http\Input\Validation;
class ValidateInputDate extends ValidateInput {

	protected $error;
	protected $allowNull;

	public function __construct($allowNull=false) {
		$this->allowNull=$allowNull;
	}
	
	public function validate() {
		if($this->allowNull && !$this->value) {
			return true;
		}
		return \Pecee\Date::IsValid($this->value);
	}
	
	public function getErrorMessage() {
		return lang('%s is not a valid date', $this->name);
	}

}