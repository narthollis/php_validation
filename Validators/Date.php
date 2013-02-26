<?php namespace Validation\Validators;

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '/Validator.php');

use \Validation;

/**
 * @class Date
 *
 * Performes some validation
 *
 */
class Date extends \Validation\Validator {

    /**
     * @var $name Validator name
     */
    public $name = "Date";

    /**
     * @var $format The date format extected
     */
    public $format;

    /**
     * @fn __construct
     *
     * @param $message A message for returning to the user when validation fails.
     * @param $format Expected format, defaults to Y-m-d, as per DateTime::createFromFormat
     * @param $blocking If a validation failure should cause the parent validation to stop
     */
    public function __construct($message, $format="Y-m-d", $blocking=false) {
        $this->message = $message;
        $this->format = $format;
        $this->blocking = $blocking;
    }

    /**
     * @fn validate Performe the validation
     *
     * @return boolean
     */
    public function validate($value) {
        $time = \DateTime::createFromFormat($this->format, $value);

        if ($time === False) return false;
        return $time->format($this->format) == $value;
    }
}

?>