<?php

require_once("./src/lazyPlinq.php");

use plinq\lazyPlinq;

class LazyPlinqTest extends PHPUnit_Framework_TestCase
{
	private $evenNumberExpression;
	private $doublingExpresion;
	private $plinqMock;

	public function  __construct(){
		$this->evenNumberExpression = function($value){
			return $value % 2 == 0;
		};

		$this->doublingExpresion = function($k, $v){
			return $v * 2;
		};

		$this->plinqMock = $this->getMock("plinq\plinq", array("__call"));
	}

	public function testLazyPlinqReturnsLazyPlinqWrapperBeforeEval()
	{
		$input = [1, 2, 3];
		$expected = "plinq\lazyPlinqWrapper";
		$actual = lazyPlinq::on($input)
					       ->where($this->evenNumberExpression)
						   ->select($this->doublingExpresion);

		$this->assertInstanceOf($expected, $actual);
	}

	public function testLazyPlinqReturnsArrayOnExec()
	{
		$input = [1, 2, 3];
		$expected = "array";
		$actual = lazyPlinq::on($input)
						   ->where($this->evenNumberExpression)
						   ->select($this->doublingExpresion)
						   ->exec();

		$this->assertInternalType($expected, $actual);
	}
}