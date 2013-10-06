<?php

namespace plinq;

use IteratorAggregate;

class plinqWrapper extends \ArrayObject implements IteratorAggregate
{
	protected $wrapped;
	protected $plinq;

	public function __construct(plinq $instance, $value)
	{
		$this->wrapped = $value;
		parent::__construct($value, \ArrayObject::ARRAY_AS_PROPS);

		$this->plinq = $instance;
	}

	public function getIterator()
	{
		return new \ArrayIterator($this->wrapped);
	}

	public function __call($method, $args)
	{
		//Push the wrapped value on to the front of the array
		$combinedArgs = array_merge(array($this->wrapped), $args);
		return $this->plinq->__call($method, $combinedArgs);
	}
}