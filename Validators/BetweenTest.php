<?php
require_once "PHPUnit/Autoload.php";
require_once "Between.php";

class BetweenTest extends PHPUnit_Framework_TestCase {

    public function provideValid() {
        return Array(
            Array(0,10,5),
            Array(-20,10,0),
            Array(-20,-10,-15),
            Array(1,100,50),
            Array(135135135123,315215241524532123,2352352523525)
        );
    }

    public function provideLow() {
        return Array(
            Array(0,10,-1),
            Array(-20,10,-21),
            Array(-20,-10,-21),
            Array(1,100,0),
            Array(135135135123,315215241524532123,135135135122),
            Array(0,10,0),
            Array(-20,10,-20),
            Array(-20,-10,-20),
            Array(1,100,1),
            Array(135135135123,315215241524532123,135135135123)
        );
    }

    public function provideHigh() {
        return Array(
            Array(0,10,11),
            Array(-20,10,11),
            Array(-20,-10,-9),
            Array(1,100,101),
            Array(135135135123,315215241524532123,315215241524532124),
            Array(0,10,10),
            Array(-20,10,10),
            Array(-20,-10,-10),
            Array(1,100,100),
            Array(135135135123,315215241524532123,315215241524532123)
        );
    }

    /**
     * @dataProvider provideValid
     */
    public function testValid($min, $max, $value) {
        $v = new \Validation\Validators\Between($min, $max, "Must be between $min and $max");

        $this->assertTrue($v->validate($value), "is $value between $min and $max");
    }

    /**
     * @dataProvider provideLow
     */
    public function testLow($min, $max, $value) {
        $v = new \Validation\Validators\Between($min, $max, "Must be between $min and $max");

        $this->assertFalse($v->validate($value), "is $value lower $min and $max");
    }

    /**
     * @dataProvider provideHigh
     */
    public function testHigh($min, $max, $value) {
        $v = new \Validation\Validators\Between($min, $max, "Must be between $min and $max");

        $this->assertFalse($v->validate($value), "is $value higher $min and $max");
    }

}

?>