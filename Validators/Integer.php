<?php namespace Validation\Validators;

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '/Validator.php');

use \Validation;

/**
 * @class Integer
 *
 * Performes some validation
 *
 */
class Integer extends \Validation\Validator {

    /**
     * @var $name Validator name
     */
    public $name = "Integer";

    /**
     * @fn __construct
     *
     * @param $message A message for returning to the user when validation fails.
     * @param $blocking If a validation failure should cause the parent validation to stop
     */
    public function __construct($message, $blocking=false) {
        $this->message = $message;
        $this->blocking = $blocking;
    }

    /**
     * @fn validate Performe the validation
     *
     * @return boolean
     */
    public function validate($value) {
        if (!is_numeric($value)) return false;

        return (int)$value == $value;
    }
}

?>