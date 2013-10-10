<?php

require_once("./src/plinq.php");

use plinq\plinq;

class MinTest extends PHPUnit_Framework_TestCase {
	public function testMinFindsTheMinInAnArrayOfNumbers()
	{
		$input = [1, 2, 9, 4, 6, 7];
		$expected = 1;
		$actual = plinq::min($input);

		$this->assertEquals($expected, $actual);
	}

	public function testMinReturnsNullForEmptyArray()
	{
		$input = [];
		$actual = plinq::min($input);

		$this->assertNull($actual);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testMinThrowsExceptionIfArrayContainsNotJustNumbersAndDoesntProvideAComparator()
	{
		$input = [1, 2, "three"];
		plinq::min($input);
	}

	public function testMinUsesComparatorWhenInputContainsNotJustNumbers()
	{
		$comparator = $this->getMock("stdClass", array("callback"));
		$comparator->expects($this->atLeastOnce())
			->method("callback");

		$input = [1, 2, "three"];
		$callback = function($currentMin, $compareTo) use($comparator){
			$comparator->callback($currentMin, $compareTo);
		};

		plinq::min($input, $callback);
	}

	public function testMinChoosesNewNumberAsSmallerIfComparatorReturnsTrue()
	{
		$input = [1, 2, "three"];
		$expected = "three"; // The mock callback always returns true, thus the last element is "smallest"
		$callback = function($currentMin, $compareTo){
			return true;
		};

		$actual = plinq::min($input, $callback);

		$this->assertEquals($expected, $actual);
	}
}