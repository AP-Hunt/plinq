<?php
require_once("./src/plinq.php");

use plinq\plinq;

class OnWithTest extends PHPUnit_Framework_TestCase {
	public function  __construct(){
	}

	public function testOnReturnsPlinqWrapper()
	{
		$input = [1];
		$expected = 'plinq\plinqWrapper';
		$actual = plinq::on($input);

		$this->assertInstanceOf($expected, $actual);
	}

	public function testWithReturnsPlinqWrapper()
	{
		$input = [1];
		$expected = 'plinq\plinqWrapper';
		$actual = plinq::with($input);

		$this->assertInstanceOf($expected, $actual);
	}
}