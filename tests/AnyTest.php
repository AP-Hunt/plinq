<?php
require_once("./src/plinq.php");

use plinq\plinq;

class AnyTest extends PHPUnit_Framework_TestCase {
	private $condition;

	public function __construct()
	{
		$this->condition = function($k, $v)
		{
			return ($v % 2) == 0;
		};
	}

	public function testAnyReturnsTrueWhenAnyElementMatchesTheCondition()
	{
		$input = [1, 3, 4, 7];
		$expected = true;
		$actual = plinq::any($input, $this->condition);

		$this->assertEquals($expected, $actual);
	}

	public function testAnyReturnsFalseWhenNoElementsMatchTheCondition()
	{
		$input = [1, 3, 5, 7];
		$expected = false;
		$actual = plinq::any($input, $this->condition);

		$this->assertEquals($expected, $actual);
	}

	public function testAnyReturnsFalseWithEmptyInput()
	{
		$input = [];
		$expected = false;
		$actual = plinq::any($input, $this->condition);

		$this->assertEquals($expected, $actual);
	}

	public function testAnyReturnsTrueWithNonEmptyInputAndNoCondition()
	{
		$input = [1];
		$expected = true;
		$actual = plinq::any($input);

		$this->assertEquals($expected, $actual);
	}

	public function testAnyReturnsFalseWithEmptyInputAndNoCondition()
	{
		$input = [];
		$expected = false;
		$actual = plinq::any($input);

		$this->assertEquals($expected, $actual);
	}
}
