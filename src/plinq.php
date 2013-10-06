<?php


namespace plinq;
use plinq\plinqArrayWrapper;

require_once("plinqWrapper.php");
require_once("plinqArrayWrapper.php");

class plinq {
	public function __construct(){}

	/**
	 * Intercepts the first call to a static plinq method and creates a new plinq object,
	 * before dispatching the method call
	 * @param 	$method
	 * @param 	$args
	 *
	 * @return	mixed
	 */
	public static function __callStatic($method, $args)
	{
		$p = new plinq();
		return $p->__call($method, $args);
	}

	/**
	 * Dispatches method calls with the correct arguments.
	 * Used by plinqWrapper to pass function calls across without
	 * having to know about the arguments.
	 * 		Eg $wrapper->where($expr);
	 * @param 	$method
	 * @param 	$args
	 *
	 * @return 	mixed
	 */
	public function __call($method, $args)
	{
		$lowerMethod = strtolower($method);
		switch($lowerMethod){
			//Methods of signature x(input, expression)
			case "where";
			case "count";
			case "select";
				return plinq::$lowerMethod($args[0], $args[1]);
				break;

			//Methods of signature x(input, y, expression)
			case "aggregate";
				return plinq::$lowerMethod($args[0], $args[1], $args[2]);
		}
	}

	/**
	 * Utility method.
	 * Used to make sure wrappers are passed an instance of plinq when $this isn't plinq.
	 * @param 	$plinq
	 *
	 * @return 	plinq
	 */
	private static function thisOrNew($plinq){
		if(!($plinq instanceof plinq)){
			return new plinq();
		}

		return $plinq;
	}

	/**
	 * Returns those elements in the input which match the expression
	 * @param 	array    	$input	The input to filter
	 * @param 	callable 	$expr	The expression by which fo filter
	 *
	 * @return 	plinqArrayWrapper
	 */
	public function where(Array $input, Callable $expr)
	{
		$p = plinq::thisOrNew($this);
		return new \plinq\plinqArrayWrapper($p, array_filter($input, $expr));
	}

	/**
	 * Counts the number of elements in the input.
	 * If $expr is provided, counts the number of elements matching it.
	 * @param  	array    	$input	The collection to work on
	 * @param 	callable 	$expr	An optional expression.
	 *
	 * @return 	int
	 */
	public function count(Array $input, Callable $expr = null)
	{
		if($expr != null)
		{
			$p = plinq::thisOrNew($this);
			return $p->where($input, $expr)->count();
		}
		else
		{
			return count($input);
		}
	}

	/**
	 * Projects each element of the input to a new form using the expression
	 * @param 	array    	$input	The input to be projection
	 * @param 	callable 	$expr	The projection expression.
	 *
	 * @return 	plinqArrayWrapper
	 */
	public function select(Array $input, Callable $expr)
	{
		$results = [];
		$p = plinq::thisOrNew($this);

		foreach($input as $k => $v){
			$r = $expr($k, $v);
			$results[] = $r;
		}

		return new plinqArrayWrapper($p, $results);
	}

	/**
	 * Applies an accumulator function to each element in the input.
	 * The seed and expression are used to determine the return value.
	 * @param 	array    	$input	The input to aggregate
	 * @param   mixed       $seed	The starting value
	 * @param 	callable 	$expr	The accumulator function
	 *
	 * @return 	mixed				The result of aggregation
	 */
	public function aggregate(Array $input, $seed, Callable $expr)
	{
		$acc = $seed;

		$arr = array_slice($input, 1);
		foreach($arr as $k => $v)
		{
			$acc = $expr($acc, $k, $v);
		}

		return $acc;
	}
}