<?php

namespace App\Classes\Tools;

/**
 * Class FormValidator
 *
 * @package ad4mat\api
 */
class FormValidator {

	//fixed strings
	const DATA_BLADE_KEY    = 'validData';
	const MESSAGE_BLADE_KEY = 'validatorMessages';

	/*
	 * @var \Validator
	 */
	private $validator;
	private $data;


	/**
	 * Creator method of the Form validator
	 *
	 * @param array $data the data returned by the form
	 * @param array $rules the rule set the data should be tested with
	 * @param array $messages the messages that the rules shall return on fail
	 *
	 * @return $this a new FormValidator Instance
	 */
	public function make(array $data, array $rules, array $messages = []){
		$this->validator = \Validator::make($data, $rules, $messages);
		$this->data = $data;
		return $this;
	}

	/**
	 * Method to check whether the check failed
	 *
	 * @return mixed
	 */
	public function fails(){
			return $this->validator->fails();
	}

	/**
	 * method to check whether the check succeeded
	 *
	 * @return boolean
	 */
	public function passes(){
			return $this->validator->passes();
	}

	/**
	 * returns a standarised form response with data that passed the validation
	 * plus appropriate messages to the alertMessages blade
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function generateRedirect(){

		$validData = [];
		$keys      = array_keys($this->data);
		$messages  = $this->validator->messages();
		foreach ($keys AS $key)
		{
			if (!$messages->has($key))
			{
				$validData[$key] = $this->data[$key];
			}
		}
		$messages = [];
		foreach($this->validator->messages()->toArray() AS $key => $value){
			$messages[$key] = $value[0];
		}
		return \Redirect::back()->with(self::MESSAGE_BLADE_KEY, \SystemMessage::warning($messages))
			->with(self::DATA_BLADE_KEY , $validData);
	}


	/**
	 * @param callable $func the function to be run if the  validation succeeds
	 * @param array    $params additional params for the function
	 *
	 * @return mixed the result of the function, a redirect otherwise
	 */
	public function ifPassesDoElseRedirect(Callable $func, array $params = []){
		if($this->passes()){
			return call_user_func_array($func, $params);
		}
		else {
			return $this->generateRedirect();
		}
	}

	/**
	 * Returns the underlying laravel validator to do custom stuff if needed
	 * @return \Validator
	 */
	public function getValidator(){
		return $this->validator;
	}

	/**
	 * helper function:
	 * to get the data from supplied valid inputs optional with default value
	 *
	 * @param string $fieldName the name of the input field
	 * @param string $default optional default value if value not found
	 *
	 * @return string the value of the input field if found, the default value if supplied or '' otherwise
	 */
	public function getInput($fieldName, $default = ''){
		if(\Session::has(self::DATA_BLADE_KEY)){
			$validData = \Session::get(self::DATA_BLADE_KEY);
			if(isset($validData[$fieldName]))
			{
				return $validData[$fieldName];
			}
		}
		return $default;
	}


	/**
	 * helper function:
	 * designed to check whether a certain option of an multi select selection-field
	 * has a certain option checked or not for easy use in templates
	 *
	 * @param String $fieldName the name of the field to be checked
	 * @param mixed $value the value to be checked against
	 * @param bool $default optional default value if needed
	 *
	 * @return bool
	 */
	public function multiSelectOptionWasSet($fieldName, $value, $default = false)
	{
		$haystack = $this->getInput($fieldName);
		if ($haystack != '' && is_array($haystack))
		{
			return in_array($value, $haystack);
		}
		// if the post data contains form data but not for the given field name
		// we have to assume no option was selected this is the only thing a little vague on this method
		// if anybody has a better solution to this feel free to correct
		if(\Session::has(self::DATA_BLADE_KEY)){
			return false;
		}
		return $default;
	}

	/**
	 * function to get the $_Session key the messages are stored at
	 * @return string the Key
	 */
	public function getMessageBladeKey(){
		return self::MESSAGE_BLADE_KEY;
	}

	/**
	 * function to get the $_Session key the valid data fields are stored at in the form field-name => data
	 * @return string the key
	 */
	public function getDataBladeKey(){
		return self::DATA_BLADE_KEY;
	}


} 