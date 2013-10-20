<?php
require_once("./src/plinq.php");

use plinq\plinq;

class FirstTest extends PHPUnit_Framework_TestCase {
	private $condition;

	public function __construct()
	{
		$this->condition = function($k, $v)
		{
			return $v == 4;
		};
	}

	public function testReturnsNullOnWithEmptyInput()
	{
		$input = [];
		$expected = null;
		$actual = plinq::on($input)->first($this->condition);

		$this->assertEquals($expected, $actual);
	}

	public function testReturnsFirstElementMatchingTheExpression()
	{
		$input = [1, 2, 3, 4];
		$expected = 4;
		$actual = plinq::on($input)->first($this->condition);

		$this->assertEquals($expected, $actual);
	}

	public function testReturnsNullIfNoElementMatchesTheExpression()
	{
		$input = [1, 2, 3];
		$expected = null;
		$actual = plinq::on($input)->first($this->condition);

		$this->assertEquals($expected, $actual);
	}

	public function testReturnsKeyAndValueWhenFlagSet()
	{
		$input = ["a" => 1, "b" => 2, "c" => 3, "d" => 4];
		$expected = ["d" => 4];
		$actual = plinq::on($input)->first($this->condition, plinq::$MAINTAIN_KEY);

		$this->assertEquals($expected, $actual->toArray());
	}
}
