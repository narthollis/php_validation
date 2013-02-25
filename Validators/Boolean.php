<?php namespace Validation\Validators;

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '/Validator.php');

use \Validation;

/**
 * @class Boolean
 *
 * Performes some validation
 *
 */
class Boolean extends \Validation\Validator {

    /**
     * @var $name Validator name
     */
    public $name = "Boolean";

    /**
     * @var $extended More true options
     */
    public $extended = false;

    /**
     * @fn __construct
     *
     * @param $message A message for returning to the user when validation fails.
     * @param $blocking If a validation failure should cause the parent validation to stop
     */
    public function __construct($message, $extended=false, $blocking=false) {
        $this->extended = $extended;
        $this->message = $message;
        $this->blocking = $blocking;
    }

    /**
     * @fn validate Performe the validation
     *
     * @return boolean
     */
    public function validate($value) {
        if (is_bool($value)) return true;

        if (is_numeric($value)) {
            if ((int)$value == $value) {
                $value = (int)$value;

                if (is_int($value) || is_float($value)) {
                    return (float)$value === 0.0 || (float)$value === 1.0;
                }
            }
        }

        if (is_string($value)) {
            $value == strtolower($value);
        }

        if ($this->extended) {
            if ($value == "yes" || $value == "no" ||
                $value == "y" || $value == "n") return true;
        }

        return ($value == "true" || $value == "false" ||
                $value == "1" || $value = "0");
    }
}

?>