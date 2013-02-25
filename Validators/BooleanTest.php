<?php
require_once "PHPUnit/Autoload.php";
require_once "Boolean.php";

class BooleanTest extends PHPUnit_Framework_TestCase {

    public function testValid() {
        $v = new \Validation\Validators\Boolean('Must be an Boolean');

        $this->assertTrue($v->validate(true), 'bool true');
        $this->assertTrue($v->validate(false), 'bool false');
        $this->assertTrue($v->validate('true'), 'str true');
        $this->assertTrue($v->validate('false'), 'str false');
        $this->assertTrue($v->validate('1'), 'str 1');
        $this->assertTrue($v->validate('0'), 'str 0');
        $this->assertTrue($v->validate(1), 'int 1');
        $this->assertTrue($v->validate(0), 'int 0');
    }

    public function testValidExtended() {
        $v = new \Validation\Validators\Boolean('Must be an Boolean', true);

        $this->assertTrue($v->validate(true), 'bool true');
        $this->assertTrue($v->validate(false), 'bool false');
        $this->assertTrue($v->validate('true'), 'str true');
        $this->assertTrue($v->validate('false'), 'str false');
        $this->assertTrue($v->validate('1'), 'str 1');
        $this->assertTrue($v->validate('0'), 'str 0');
        $this->assertTrue($v->validate(1), 'int 1');
        $this->assertTrue($v->validate(0), 'int 0');

        $this->assertTrue($v->validate('yes'), 'str yess');
        $this->assertTrue($v->validate('no'), 'str no');
        $this->assertTrue($v->validate('y'), 'str y');
        $this->assertTrue($v->validate('n'), 'str n');
    }

    public function testInvalid() {
        $v = new \Validation\Validators\Boolean('Must be an Boolean');

        $this->assertFalse($v->validate('yes'), 'str yes');
        $this->assertFalse($v->validate('no'), 'str no');
        $this->assertFalse($v->validate('y'), 'str y');
        $this->assertFalse($v->validate('n'), 'str n');

        $this->assertFalse($v->validate(new stdClass()), 'stdClass');
        $this->assertFalse($v->validate(124), 'int 124');
        $this->assertFalse($v->validate(-124), 'int -124');
        $this->assertFalse($v->validate('thisadxfgas'), 'str thisadxfgas');
        $this->assertFalse($v->validate('trueSomething'), 'str trueSomething');
        $this->assertFalse($v->validate('false something'), 'str false something');
        $this->assertFalse($v->validate(Array()), 'empty array');
        $this->assertFalse($v->validate(Array(1,2,3)), 'array with content');
    }

    public function testInvalidExtended() {
        $v = new \Validation\Validators\Boolean('Must be an Boolean', true);

        $this->assertFalse($v->validate(new stdClass()));
        $this->assertFalse($v->validate(124));
        $this->assertFalse($v->validate(-124));
        $this->assertFalse($v->validate('thisadxfgas'));
        $this->assertFalse($v->validate('trueSomething'));
        $this->assertFalse($v->validate('false something'));
        $this->assertFalse($v->validate(Array()));
        $this->assertFalse($v->validate(Array(1,2,3)));
    }
}

?>