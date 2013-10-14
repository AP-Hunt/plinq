<?php

require_once("./src/plinq.php");

use plinq\plinq;

class AllTest extends PHPUnit_Framework_TestCase {
	private $condition;

	public function __construct()
	{
		$this->condition = function($k, $v){
			return ($v % 2) == 0;
		};
	}

	public function testAllReturnsTrueWhenAllElementsMeetTheCondition()
	{
		$input = [2, 4, 6, 8];
		$expected = true;
		$actual = plinq::all($input, $this->condition);

		$this->assertEquals($expected, $actual);
	}

	public function testAllReturnsFalseWhenAnyElementDoesntMeetTheCondition()
	{
		$input = [2, 4, 5, 8];
		$expected = false;
		$actual = plinq::all($input, $this->condition);

		$this->assertEquals($expected, $actual);
	}

	public function testAllReturnsTrueWhenInputIsEmpty()
	{
		$input = [];
		$expected = true;
		$actual = plinq::all($input, $this->condition);

		$this->assertEquals($expected, $actual);
	}
}
