<?php
require_once "PHPUnit/Autoload.php";
require_once "Integer.php";

class IntegerTest extends PHPUnit_Framework_TestCase {

    public function testInt() {
        $v = new \Validation\Validators\Integer('Must be an Integer');

        $this->assertTrue($v->validate(0));
        $this->assertTrue($v->validate(2141241));
        $this->assertTrue($v->validate(-15315135143));
        $this->assertTrue($v->validate(0x10));
    }

    public function testStringInt() {
        $v = new \Validation\Validators\Integer('Must be an Integer');

        $this->assertTrue($v->validate("0"));
        $this->assertTrue($v->validate("2141241"));
        $this->assertTrue($v->validate("-15315135143"));
    }

    public function testNotInteger() {
        $v = new \Validation\Validators\Integer('Must be an Integer');

        $this->assertFalse($v->validate(new stdClass()));
        $this->assertFalse($v->validate("This is not an integer"));
        $this->assertFalse($v->validate(null));
        $this->assertFalse($v->validate(true));
        $this->assertFalse($v->validate("t13"));
        $this->assertFalse($v->validate("0x10"));
    }

}

?>
