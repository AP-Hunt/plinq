<?php
require_once("./src/plinq.php");

use plinq\plinq;

class SelectTest extends PHPUnit_Framework_TestCase {
	public function  __construct(){
	}

	public function testSelectReturnsResultOfExpression()
	{
		$input = [1, 2, 3];
		$expected = [2, 4, 6];
		$doublingSelection = function($key, $value){
			return $value * 2;
		};

		$actual = plinq::select($input, $doublingSelection);

		$this->assertEquals($expected, array_values($actual->toArray()));
	}
}