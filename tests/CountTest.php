<?php
require_once("./src/plinq.php");

use plinq\plinq;

class CountTest extends PHPUnit_Framework_TestCase {
	private $evenNumberExpression;

	public function  __construct(){
		$this->evenNumberExpression = function($value){
			return $value % 2 == 0;
		};
	}

	public function testCountOneParamReturnsTheNumberElements()
	{
		$input = [1, 2, 3];
		$expected = 3;

		$actual = plinq::count($input);

		$this->assertEquals($expected, $actual);
	}

	public function  testCountTwoParamsReturnsNumberOfElementsMatchingTheExpression()
	{
		$input = [1, 2, 3];
		$expected = 1;

		$actual = plinq::count($input, $this->evenNumberExpression);

		$this->assertEquals($expected, $actual);
	}
}