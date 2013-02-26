<?php namespace Validation\Validators;

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '/Validator.php');

use \Validation;

/**
 * @class Between
 *
 * Performes some validation
 *
 */
class Between extends \Validation\Validator {

    /**
     * @var $name Validator name
     */
    public $name = "Between";

    /**
     * @var $min That which the tested value should be greater than
     */
    public $min;

    /**
     * @var $min That which the tested value should be less than
     */
    public $max;

    /**
     * @fn __construct
     *
     * @param $message A message for returning to the user when validation fails.
     * @param $blocking If a validation failure should cause the parent validation to stop
     */
    public function __construct($min, $max, $message, $blocking=false) {
        $this->min = $min;
        $this->max = $max;

        $this->message = $message;
        $this->blocking = $blocking;
    }

    /**
     * @fn validate Performe the validation
     *
     * @return boolean
     */
    public function validate($value) {
        return ($this->min < $value) && ($value < $this->max);
    }
}

?>