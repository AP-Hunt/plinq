<?php

require_once("./src/plinq.php");

use plinq\plinq;

class DistinctTest extends PHPUnit_Framework_TestCase {
	public function __construct()
	{}

	public function testReturnsDistinctValuesFromTneInput()
	{
		$input = [1, 1.1, 2, 2, 4, 6, "a", "A"];
		$expected = [1, 1.1, 2, 4, 6, "a", "A"];
		$actual = plinq::with($input)
					   ->distinct()
					   ->toArray();

		$this->assertEquals($expected, $actual);
	}

	public function testReturnsEmptyArrayWithEmptyInput()
	{
		$input = [];
		$expected = [];
		$actual = plinq::with($input)
					   ->distinct()
					   ->toArray();

		$this->assertEquals($expected, $actual);
	}

	public function testReturnsDistinctValuesFromExpressionIfProvided()
	{
		$input = ["a", "A", "b", "B"];
		$expected = ["a", "b"];
		$expression = function($k, $v){ return strtolower($v); };
		$actual = plinq::with($input)
					   ->distinct($expression)
			  		   ->toArray();

		$this->assertEquals($expected, $actual);

	}
}
