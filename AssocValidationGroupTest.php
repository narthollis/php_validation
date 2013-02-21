<?php
require_once "PHPUnit/Autoload.php";
require_once "Validator.php";
require_once "AssocValidationGroup.php";

class AssocValidationGroupTest extends PHPUnit_Framework_TestCase {

    protected $by_programatic;
    protected $by_array;

    protected function setUp() {
        $this->by_programatic = new \Validation\AssocValidationGroup();

        $v = new \Validation\Validator('string_is_test', function($value) { return $value == 'test'; }, 'String must be test');
        $this->by_programatic->addValidator('test', $v);

        $vg = new \Validation\ValidationGroup();
        $v = new \Validation\Validator('is_int', function($value) { return is_int($value); }, 'Must be an integer', true);
        $vg->addValidator($v);
        $v = new \Validation\Validator('less_100', function($value) { return $value < 100; }, 'Must be less than 100');
        $vg->addValidator($v);
        $v = new \Validation\Validator('greater_10', function($value) { return $value > 10; }, 'Must be greater than 10');
        $vg->addValidator($v);

        $this->by_programatic->addValidationGroup('between', $vg);

        $this->by_array = new \Validation\AssocValidationGroup(
            Array(
                'test' => Array(
                    Array(
                        'name'=>'string_is_test',
                        'method'=>function($value) { return $value == 'test'; },
                        'errorMessage'=>'String must be test',
                    )
                ),
                'between' => Array(
                    Array(
                        'name'=>'is_int',
                        'method'=>function($value) { return is_int($value); },
                        'errorMessage'=>'Must be an integer',
                        'blocking'=>true
                    ),
                    Array(
                        'name'=>'less_100',
                        'method'=>function($value) { return $value < 100; },
                        'errorMessage'=>'Must be less than 100',
                    ),
                    Array(
                        'name'=>'greater_10',
                        'method'=>function($value) { return $value > 10; },
                        'errorMessage'=>'Must be greater than 10',
                    )
                )
            )
        );
    }

    public function testValid() {
        $data = Array(
            'test' => 'test',
            'between' => 15
        );


        $this->assertTrue($this->by_programatic->validate($data));
        $this->assertTrue($this->by_array->validate($data));
    }

    public function testInvalid() {
        $data = Array(
            'test' => 'this string is not just test',
            'between' => -1
        );

        $programatic_out = $this->by_programatic->validate($data);
        $array_out = $this->by_array->validate($data);

        $this->assertArrayHasKey('test', $programatic_out);
        $this->assertArrayHasKey('test', $array_out);

        $this->assertArrayHasKey('between', $programatic_out);
        $this->assertArrayHasKey('greater_10', $programatic_out['between']);
        $this->assertArrayHasKey('between', $array_out);
        $this->assertArrayHasKey('greater_10', $array_out['between']);
    }

    public function testRequired() {
        $avg = new \Validation\AssocValidationGroup();

        $vg = new \Validation\ValidationGroup();
        $v = new \Validation\Validator('is_int', function($value) { return is_int($value); }, 'Must be an integer', true);
        $vg->addValidator($v);
        $v = new \Validation\Validator('less_100', function($value) { return $value < 100; }, 'Must be less than 100');
        $vg->addValidator($v);
        $v = new \Validation\Validator('greater_10', function($value) { return $value > 10; }, 'Must be greater than 10');
        $vg->addValidator($v);

        $vg->setRequired();

        $avg->addValidationGroup('between', $vg);

        $this->assertArrayHasKey('between', $avg->validate(Array('beteen'=>'')));
        $this->assertArrayHasKey('between', $avg->validate(Array('beteen'=>null)));
    }

    public function testOptional() {
        $avg = new \Validation\AssocValidationGroup();

        $vg = new \Validation\ValidationGroup();
        $v = new \Validation\Validator('is_int', function($value) { return is_int($value); }, 'Must be an integer', true);
        $vg->addValidator($v);
        $v = new \Validation\Validator('less_100', function($value) { return $value < 100; }, 'Must be less than 100');
        $vg->addValidator($v);
        $v = new \Validation\Validator('greater_10', function($value) { return $value > 10; }, 'Must be greater than 10');
        $vg->addValidator($v);

        $vg->setOptional();

        $avg->addValidationGroup('between', $vg);

        $this->assertTrue($avg->validate(Array('between'=>'')));
        $this->assertTrue($avg->validate(Array('between'=>null)));
    }

    public function testAllowExtra() {
        $avg = new \Validation\AssocValidationGroup();

        $vg = new \Validation\ValidationGroup();
        $v = new \Validation\Validator('is_int', function($value) { return is_int($value); }, 'Must be an integer', true);
        $vg->addValidator($v);
        $v = new \Validation\Validator('less_100', function($value) { return $value < 100; }, 'Must be less than 100');
        $vg->addValidator($v);
        $v = new \Validation\Validator('greater_10', function($value) { return $value > 10; }, 'Must be greater than 10');
        $vg->addValidator($v);

        $vg->setOptional();

        $avg->addValidationGroup('between', $vg);
        $avg->setAllowExtra();

        $this->assertTrue($avg->validate(Array('between'=>15, 'test'=>'abc')));
    }

    public function testDontAllowExtra() {
        $avg = new \Validation\AssocValidationGroup();

        $vg = new \Validation\ValidationGroup();
        $v = new \Validation\Validator('is_int', function($value) { return is_int($value); }, 'Must be an integer', true);
        $vg->addValidator($v);
        $v = new \Validation\Validator('less_100', function($value) { return $value < 100; }, 'Must be less than 100');
        $vg->addValidator($v);
        $v = new \Validation\Validator('greater_10', function($value) { return $value > 10; }, 'Must be greater than 10');
        $vg->addValidator($v);

        $vg->setOptional();

        $avg->addValidationGroup('between', $vg);
        $avg->setDontAllowExtra();

        $this->assertEquals($avg->validate(Array('between'=>15, 'test'=>'abc')), Array(0 => 'There are extra values. [abc]'));
    }

    public function testBlocking() {
        $avg = new \Validation\AssocValidationGroup();

        $v = new \Validation\Validator('string_is_test', function($value) { return $value == 'test'; }, 'String must be test');
        $v->blocking = true;
        $avg->addValidator('test', $v);

        $vg = new \Validation\ValidationGroup();
        $v = new \Validation\Validator('is_int', function($value) { return is_int($value); }, 'Must be an integer', true);
        $vg->addValidator($v);
        $v = new \Validation\Validator('less_100', function($value) { return $value < 100; }, 'Must be less than 100');
        $vg->addValidator($v);
        $v = new \Validation\Validator('greater_10', function($value) { return $value > 10; }, 'Must be greater than 10');
        $vg->addValidator($v);

        $avg->addValidationGroup('between', $vg);

        $this->assertArrayHasKey('test', $avg->validate(Array('between'=>-1, 'test'=>'abc')));
        $this->assertArrayNotHasKey('between', $avg->validate(Array('between'=>-1, 'test'=>'abc')));
    }
}
?>