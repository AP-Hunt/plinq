<?php
require_once("./src/plinq.php");

use plinq\plinq;

class ChainingTest extends PHPUnit_Framework_TestCase {
	private $evenNumberExpression;
	private $doublingExpression;

	public function  __construct(){
		$this->evenNumberExpression = function($value){
			return $value % 2 == 0;
		};

		$this->doublingExpression = function($k, $v){
			return $v * 2;
		};
	}

	public function testResultOfFirstFilterPassedToSecond()
	{
		$input = [1, 2, 3, 4, 5, 6];
		$expected = [4, 8, 12];

		$actual = plinq::with($input)
			           ->where($this->evenNumberExpression)
					   ->select($this->doublingExpression);

		$this->assertEquals($expected, array_values($actual->toArray()));
	}

}