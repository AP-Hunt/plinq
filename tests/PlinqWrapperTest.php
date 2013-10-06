<?php

require_once("src/plinq.php");
use plinq\plinqWrapper;

class TestPlinqWrapper extends PHPUnit_Framework_TestCase
{
	private $evenNumberExpression;
	private $plinqMock;

	public function  __construct(){
		$this->evenNumberExpression = function($value){
			return $value % 2 == 0;
		};

		$this->plinqMock = $this->getMock("plinq\plinq", array("__call"));
	}

	public function testPlinqWrapperIsAnArrayObject()
	{
		$input = [1, 2, 3];
		$wrapper = new \plinq\plinqWrapper($this->plinqMock, $input);

		$this->assertInstanceOf('ArrayObject', $wrapper);
	}

	public function testPlinqWrapperCanBeCastToArray()
	{
		$input = [1, 2, 3];
		$wrapper = new \plinq\plinqWrapper($this->plinqMock, $input);

		$this->assertInternalType("array", (array)$wrapper);
	}

	public function testCanAccessWrappedValueElementsLikeArray()
	{
		$input = [3, 2, 1];
		$wrapper = new \plinq\plinqWrapper($this->plinqMock, $input);

		$this->assertEquals($input[0], $wrapper[0]);
	}

	public function testCanAccessWrappedValueElementsLikeObject()
	{
		$input = ["one"=>3, "two"=>2, "three"=>1];
		$wrapper = new \plinq\plinqWrapper($this->plinqMock, $input);

		$this->assertEquals($input["one"], $wrapper->one);
	}

	public function testCanCallPlinqMethodsFromWrapper()
	{
		$input = [1, 2, 3];
		$expected = [2];

		$plinq = new \plinq\plinq();
		$wrapper = new \plinq\plinqWrapper($plinq, $input);

		$output = $wrapper->where($this->evenNumberExpression);

		$this->assertEquals($expected, array_values($output->toArray()));
	}
}