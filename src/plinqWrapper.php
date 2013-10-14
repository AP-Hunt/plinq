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

	/**
	 * Overrides ArrayObject functionality to use plinq
	 * @return $this|int
	 */
	public function count()
	{
		$args = func_get_args();
		if(!$args)
		{
			$args = array();
		}
		return $this->__call("count", $args);
	}

	public function __call($method, $args)
	{
		$boundFunc = $this->plinq->bindInputOn($method, $this->wrapped);
		$this->wrapped = $boundFunc($args);

		if(!is_array($this->wrapped))
		{
			return $this->wrapped;
		}

		return $this;
	}
}