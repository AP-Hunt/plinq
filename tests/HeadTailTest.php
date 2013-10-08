<?php

require_once("./src/plinq.php");

use plinq\plinq;

class HeadTailTest extends PHPUnit_Framework_TestCase {
	public function testHeadSelectsTheFirstElement()
	{
		$input = [1, 2, 3];
		$expected = [1];
		$actual = plinq::head($input);

		$this->assertEquals($expected, $actual);
	}

	public function testHeadReturnsEmptyArrayIfElementIsEmpty()
	{
		$input = [ ];
		$expected = [ ];
		$actual = plinq::head($input);

		$this->assertEquals($expected, $actual);
	}

	public function testHeadReturnsKeyValuePairIfRetainKeyFlagIsTruthy()
	{
		$input = [1, 2, 3];
		$expected = [0 => 1];
		$actual = plinq::head($input, plinq::$MAINTAIN_KEY);

		$this->assertEquals($expected, $actual);
	}

	public function testHeadReturnsOnlyTheFirstElementIfRetainKeyFlagIsFalsy()
	{
		$input = [1, 2, 3];
		$expected = [1];
		$actual = plinq::head($input, false);

		$this->assertEquals($expected, $actual);
	}

	public function testTailReturnsAllButTheFirstElementInTheInput()
	{
		$input = [1, 2, 3];
		$expected = [2, 3];
		$actual = plinq::tail($input);

		$this->assertEquals($expected, $actual);
	}

	public function testTailReturnsEmptyArrayIfElementIsEmpty()
	{
		$input = [ ];
		$expected = [ ];
		$actual = plinq::tail($input);

		$this->assertEquals($expected, $actual);
	}

	public function testTailReturnsEmptyArrayIfInputHasOneElement()
	{
		$input = [1];
		$expected = [ ];
		$actual = plinq::tail($input);

		$this->assertEquals($expected, $actual);
	}
}