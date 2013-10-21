<?php
require_once("./src/plinq.php");

use plinq\plinq;

class AggregateTest extends PHPUnit_Framework_TestCase {
	public function  __construct(){
	}

	public function testFunctionAppliedToEachElement()
	{
		$input = [1, 2, 3, 4, 5];
		$seed = 0;
		$accumulator = function($acc, $key, $val){
			$acc += $val;
			return $acc;
		};
		$expected = 15;

		$actual = plinq::aggregate($input, $seed, $accumulator);

		$this->assertEquals($expected, $actual);
	}
}