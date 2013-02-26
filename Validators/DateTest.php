<?php
require_once "PHPUnit/Autoload.php";
require_once "Date.php";

class DateTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        date_default_timezone_set('UTC');
    }

    public function provideValid() {
        return Array(
            Array('2012-01-21', 'Y-m-d'),
            Array('2431-11-23', 'Y-m-d'),
            Array('23-02-2012', 'd-m-Y'),
            Array('02-23-2012', 'm-d-Y')
        );
    }

    public function provideInvalid() {
        return Array(
            Array('afsagas', 'Y-m-d'),
            Array('2012-13-21', 'Y-m-d'),
            Array('2012-2-30', 'Y-m-d'),
            Array('2013-2-29', 'Y-m-d'),
            Array('23-13-2012', 'd-m-Y'),
            Array('23-23-2012', 'm-d-Y'),
            Array('t12-12-2012', 'd-m-Y')
        );
    }

    /**
     * @dataProvider provideValid
     */
    public function testValid($date, $format) {
        $v = new \Validation\Validators\Date("must match $format", $format);

        $this->assertTrue($v->validate($date), "$date didnt match $format");
    }

    /**
     * @dataProvider provideInvalid
     */
    public function testInvalid($date, $format) {
        $v = new \Validation\Validators\Date("must match $format", $format);

        $this->assertFalse($v->validate($date), "$date didnt match $format");
    }

}

?>
