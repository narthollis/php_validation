<?php namespace Validation;

require_once('validation_methods.php');

class Validator {
    public $name;
    public $method;
    public $message;
    public $blocking;
    public $extraParams;

    public function __construct($name, $method, $message, $blocking=false, $extraParams=Array()) {
        $this->name = $name;
        $this->method = $method;
        $this->message = $message;
        $this->blocking = $blocking;
        $this->extraParams = $extraParams;
    }

    public function validate($value) {
        $output = call_user_func_array($this->method, array_merge(Array($value), $this->extraParams));

        if ($output !== true) return $this->message;
        return true;
    }
}

?>
