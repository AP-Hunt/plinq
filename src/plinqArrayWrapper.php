<?php
namespace plinq;

use plinq\plinqWrapper;

class plinqArrayWrapper extends plinqWrapper {
	public function __construct(plinq $instance, array $value)
	{
		parent::__construct($instance,$value);
	}

	public function toArray()
	{
		return $this->wrapped;
	}
}