<?php

namespace plinq;

class lazyPlinqWrapper {
	private $wrapped;
	private $lazyPlinq;

	private $evaluators = array();

	public function __construct(lazyPlinq $instance, $value)
	{
		$this->wrapped = $value;
		$this->lazyPlinq = $instance;
	}

	public function __call($method, $args)
	{
		$boundFunc = $this->lazyPlinq->bindArgsOn($method, $args);
		$this->evaluators[]  = $boundFunc;

		return $this;
	}

	public function exec()
	{
		if(count($this->evaluators) == 0){
			return array();
		}

		$value = $this->evaluators[0]($this->wrapped);
		$tail = array_slice($this->evaluators, 1);

		if(count($tail) > 0)
		{
			foreach($tail as $f)
			{
				$value = $f((array)$value);
			}
		}

		if(!is_array($value))
		{
			return $value;
		}

		return (array)$value;
	}
}