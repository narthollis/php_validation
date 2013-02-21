<?php namespace Validation;

require_once('validation_methods.php');
require_once('Validator.php');
require_once('ValidationGroup.php');


class AssocValidationGroup extends ValidationGroup {
    protected $allowExtra = false;

    public function __construct(Array $validators=Array(), $required=false, $blocking=false, $allowExtra=false, ValidationGroup $parent=null) {
        foreach ($validators as $name => $validator) {
            $v = $validator;
            if (is_array($validator)) {
                if (array_key_exists('name', $validator)) {
                    if (array_key_exists('extraParams', $validator)) {
                        $v = new Validator($validator['name'], $validator['method'], $validator['errorMessage'], $validator['blocking'], $validator['extraParams']);
                    } elseif (array_key_exists('blocking', $validator)) {
                        $v = new Validator($validator['name'], $validator['method'], $validator['errorMessage'], $validator['blocking']);
                    } else {
                        $v = new Validator($validator['name'], $validator['method'], $validator['errorMessage']);
                    }
                } else {
                    $v = new ValidationGroup($validator);
                }
            }
            if ($v instanceof Validator) {
                $this->addValidator($name, $v);
            }
            if ($v instanceof ValidationGroup) {
                $this->addValidationGroup($name, $v);
            }
        }

        $this->required = $required;
        $this->blocking = $blocking;
        $this->allowExtra = $allowExtra;
        $this->parent = $parent;
    }

    public function setAllowExtra() {
        $this->allowExtra = true;
    }

    public function setDontAllowExtra() {
        $this->allowExtra = false;
    }

    public function getAllowExtra() {
        return $this->allowExtra;
    }

    public function addValidator($name, Validator $validator) {
        $this->validators[$name] = $validator;
    }

    public function addValidationGroup($name, ValidationGroup $validator) {
        $this->validators[$name] = $validator;
        $this->validators[$name]->setParent($this);
    }

    public function validate(Array $data) {
        if (empty($data) && !$this->required) return true;

        $out = Array();
        if (!$this->allowExtra) {
            $extraKeysInData = array_diff_key($data, $this->validators);
            if (!empty($extraKeysInData)) $out[] = 'There are extra values. [' . implode(', ', $extraKeysInData) . ']';
        }

        foreach ($this->validators as $name => $validator) {
            if (array_key_exists($name, $data)) {
                $result = $validator->validate($data[$name]);

                if ($result !== true) {
                    $out[$name] = $result;
                    if ($validator->blocking) break;
                }
            } elseif ($validator->getRequired()) {
                $out[$name] = Array('required', "$name is required.");
            }
        }

        if (empty($out)) return true;
        return $out;
    }

}

?>

