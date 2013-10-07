<?php

namespace plinq;

use ArrayAccess;
use IteratorAggregate;

class plinqWrapper extends \ArrayObject implements IteratorAggregate, ArrayAccess
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

	public function toArray()
	{
		return $this->wrapped;
	}

	public function __call($method, $args)
	{
		$boundFunc = $this->plinq->bindInputOn($method, $this->wrapped);
		$this->wrapped = $boundFunc($args);

		return $this;
	}
}