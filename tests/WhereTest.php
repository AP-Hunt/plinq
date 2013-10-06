<?php
require_once("./src/plinq.php");

use plinq\plinq;

class WhereTest extends PHPUnit_Framework_TestCase {
	private $evenNumberExpression;

	public function  __construct(){
		$this->evenNumberExpression = function($value){
			return $value % 2 == 0;
		};
	}

	public function testFiltersByCondition()
	{
		$input = [1, 2, 3, 4, 5, 6];
		$expectedOutput = [2, 4, 6];

		$actual = plinq::where($input, $this->evenNumberExpression);
		$this->assertEquals($expectedOutput, array_values((array)$actual));
	}

	public function testResultIsPlinqWrapper()
	{
		$input = [1, 2, 3, 4, 5, 6];
		$expected = 'plinq\plinqWrapper';

		$actual = plinq::where($input, $this->evenNumberExpression);

		$this->assertInstanceOf($expected, $actual);
	}
}