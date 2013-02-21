<?php namespace Validation;

require_once('validation_methods.php');
require_once('Validator.php');


class ValidationGroup {
    protected $required = false;
    protected $validators = Array();
    protected $parent = null;
    protected $blocking = false;

    public function __construct(Array $validators=Array(), $required=false, $blocking=false, ValidationGroup $parent=null) {
        foreach($validators as $validator) {
            $v = $validator;
            if(is_array($validator)) {
                if (array_key_exists('extraParams', $validator)) {
                    $v = new Validator($validator['name'], $validator['method'], $validator['errorMessage'], $validator['blocking'], $validator['extraParams']);
                } elseif (array_key_exists('blocking', $validator)) {
                    $v = new Validator($validator['name'], $validator['method'], $validator['errorMessage'], $validator['blocking']);
                } else {
                    $v = new Validator($validator['name'], $validator['method'], $validator['errorMessage']);
                }
            }
            if ($v instanceof Validator) {
                $this->addValidator($v);
            }
        }

        $this->required = $required;
        $this->parent = $parent;

    }

    public function setRequired() {
        $this->required = true;
    }

    public function setOptional() {
        $this->required = false;
    }

    public function getRequired() {
        return $this->required;
    }

    public function setParent(ValidationGroup $parent) {
        $this->parent = $parent;
    }

    public function getParent() {
        return $this->parent;
    }

    public function setBlocking() {
        $this->blocking = true;
    }

    public function setNonBlocking() {
        $this->blocking = false;
    }

    public function getBlocking() {
        return $this->blocking;
    }

    public function addValidator(Validator $validator) {
        $this->validators[] = $validator;
    }

    public function validate($value) {
        if (empty($value) && !$this->required) return true;

        $out = Array();
        foreach($this->validators as $validator) {
            $result = $validator->validate($value);
            if ($result !== true) {
                $out[$validator->name] = $result;
                if ($validator->blocking) break;
            }
        }

        if (empty($out)) return true;
        return $out;
    }
}

?>

