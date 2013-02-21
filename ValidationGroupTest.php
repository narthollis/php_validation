<?php
require_once "PHPUnit/Autoload.php";
require_once "Validator.php";
require_once "ValidationGroup.php";

class ValidationGroupTest extends PHPUnit_Framework_TestCase {

    protected $validation_group_programtic;
    protected $validation_group_array;
    protected $validation_group_multi_programtic;
    protected $validation_group_multi_array;

    protected function setUp() {
        $this->validation_group_programatic = new \Validation\ValidationGroup();
        $v = new \Validation\Validator('string_is_test', function($value) { return $value == 'test'; }, 'String must be test');
        $this->validation_group_programatic->addValidator($v);

        $this->validation_group_array = new \Validation\ValidationGroup(
            Array(
                Array(
                    'name'=>'string_is_test',
                    'method'=>function($value) { return $value == 'test'; },
                    'errorMessage'=>'String must be test',
                )
            )
        );

        $this->validation_group_multi_programatic = new \Validation\ValidationGroup();
        $v = new \Validation\Validator('is_int', function($value) { return is_int($value); }, 'Must be an integer', true);
        $this->validation_group_multi_programatic->addValidator($v);
        $v = new \Validation\Validator('less_100', function($value) { return $value < 100; }, 'Must be less than 100');
        $this->validation_group_multi_programatic->addValidator($v);
        $v = new \Validation\Validator('greater_10', function($value) { return $value > 10; }, 'Must be greater than 10');
        $this->validation_group_multi_programatic->addValidator($v);

        $this->validation_group_multi_array = new \Validation\ValidationGroup(
            Array(
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
        );


    }

    public function testRequired() {
        $this->validation_group_programatic->setRequired();
        $this->validation_group_array->setRequired();

        $this->assertTrue($this->validation_group_programatic->getRequired());
        $this->assertTrue($this->validation_group_array->getRequired());
    }

    public function testOptional() {
        $this->validation_group_programatic->setOptional();
        $this->validation_group_array->setOptional();

        $this->assertFalse($this->validation_group_programatic->getRequired());
        $this->assertFalse($this->validation_group_array->getRequired());
    }

    public function testValid() {
        $this->validation_group_programatic->setRequired();
        $this->validation_group_array->setRequired();

        $this->assertTrue($this->validation_group_programatic->validate('test'));
        $this->assertTrue($this->validation_group_array->validate('test'));
    }

    public function testInvalid() {
        $this->validation_group_programatic->setRequired();
        $this->validation_group_array->setRequired();

        $this->assertEquals($this->validation_group_programatic->validate('not test'), Array('string_is_test' => 'String must be test'));
        $this->assertEquals($this->validation_group_array->validate('not test'), Array('string_is_test' => 'String must be test'));
    }

    public function testEmptyStringRequired() {
        $this->validation_group_programatic->setRequired();
        $this->validation_group_array->setRequired();

        $this->assertEquals($this->validation_group_programatic->validate(''), Array('string_is_test' => 'String must be test'));
        $this->assertEquals($this->validation_group_array->validate(''), Array('string_is_test' => 'String must be test'));
    }

    public function testEmptyStringOptional() {
        $this->validation_group_programatic->setOptional();
        $this->validation_group_array->setOptional();

        $this->assertTrue($this->validation_group_programatic->validate(''));
        $this->assertTrue($this->validation_group_array->validate(''));
    }

    public function multiTestTrueProvider() {
        return Array(Array(15), Array(25), Array(45), Array(67), Array(80), Array(89), Array(94));
    }

    public function multiTestLessProvider() {
        return Array(Array(-24), Array(4), Array(8));
    }

    public function multiTestGreaterProvider() {
        return Array(Array(102), Array(1000), Array(124124213));
    }

    public function multiTestNotIntProvider() {
        return Array(Array(Array()), Array(new stdClass()), Array('test'), Array(''), Array(null));
    }

    /**
     * @dataProvider multiTestTrueProvider
     */
    public function testMultiValid($a) {
        $this->validation_group_multi_programatic->setRequired();
        $this->validation_group_multi_array->setRequired();

        $this->assertTrue($this->validation_group_multi_programatic->validate($a));
        $this->assertTrue($this->validation_group_multi_array->validate($a));
    }

    /**
     * @dataProvider multiTestLessProvider
     */
    public function testMultiInvalidLess($a) {
        $this->validation_group_multi_programatic->setRequired();
        $this->validation_group_multi_array->setRequired();

        $this->assertArrayHasKey('greater_10', $this->validation_group_multi_programatic->validate($a));
        $this->assertArrayHasKey('greater_10', $this->validation_group_multi_array->validate($a));
    }

    /**
     * @dataProvider multiTestGreaterProvider
     */
    public function testMultiInvalidGreater($a) {
        $this->validation_group_multi_programatic->setRequired();
        $this->validation_group_multi_array->setRequired();

        $this->assertArrayHasKey('less_100', $this->validation_group_multi_programatic->validate($a));
        $this->assertArrayHasKey('less_100', $this->validation_group_multi_array->validate($a));
    }

    /**
     * @dataProvider multiTestNotIntProvider
     */
    public function testMultiInvalidNotInt($a) {
        $this->validation_group_multi_programatic->setRequired();
        $this->validation_group_multi_array->setRequired();

        $this->assertArrayHasKey('is_int', $this->validation_group_multi_programatic->validate($a));
        $this->assertArrayHasKey('is_int', $this->validation_group_multi_array->validate($a));
    }
}

?>
