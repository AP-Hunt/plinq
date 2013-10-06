<?php

require_once("src/plinq.php");
use plinq\plinqArrayWrapper;

class TestPlinqArrayWrapper extends PHPUnit_Framework_TestCase
{
	private $evenNumberExpression;
	private $plinqMock;

	public function  __construct(){
		$this->evenNumberExpression = function($value){
			return $value % 2 == 0;
		};

		$this->plinqMock = $this->getMock("plinq\plinq", array("__call"));
	}

	public function testToArrayReturnsArray()
	{
		$input = [1, 2, 3];
		$wrapper = new \plinq\plinqArrayWrapper($this->plinqMock, $input);

		$this->assertInternalType('array', $wrapper->toArray());
	}
}