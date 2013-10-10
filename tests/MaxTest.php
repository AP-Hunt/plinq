<?php

require_once("./src/plinq.php");

use plinq\plinq;

class MaxTest extends PHPUnit_Framework_TestCase {
	public function testMaxFindsTheMaxInAnArrayOfNumbers()
	{
		$input = [1, 2, 9, 4, 6, 7];
		$expected = 9;
		$actual = plinq::max($input);

		$this->assertEquals($expected, $actual);
	}

	public function testMaxReturnsNullForEmptyArray()
	{
		$input = [];
		$actual = plinq::max($input);

		$this->assertNull($actual);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testMaxThrowsExceptionIfArrayContainsNotJustNumbersAndDoesntProvideAComparator()
	{
		$input = [1, 2, "three"];
		plinq::max($input);
	}

	public function testMaxUsesComparatorWhenInputContainsNotJustNumbers()
	{
		$comparator = $this->getMock("stdClass", array("callback"));
		$comparator->expects($this->atLeastOnce())
			       ->method("callback");

		$input = [1, 2, "three"];
		$callback = function($currentMax, $compareTo) use($comparator){
			$comparator->callback($currentMax, $compareTo);
		};

		plinq::max($input, $callback);
	}

	public function testMaxChoosesNewNumberAsLargerIfComparatorReturnsTrue()
	{
		$input = [1, 2, "three"];
		$expected = "three";
		$callback = function($currentMax, $compareTo){
			return true;
		};

		$actual = plinq::max($input, $callback);

		$this->assertEquals($expected, $actual);
	}
}