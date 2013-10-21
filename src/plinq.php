<?php


namespace plinq;
use plinq\plinqArrayWrapper;

require_once("plinqWrapper.php");

class plinq {
	/** Flags  **/
	public static $MAINTAIN_KEY = TRUE;

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
	 * Used by __callStatic to direct a "static" function call
	 * without having to know about the arguments.
	 * 		Eg $wrapper->where($expr);
	 * @param 	$method
	 * @param 	$args
	 *
	 * @return 	mixed
	 */
	public function __call($method, $args)
	{
		$lowerMethod = strtolower($method);

		if(!method_exists($this, $lowerMethod))
		{
			throw new \BadMethodCallException();
		}

		if(empty($args))
		{
			throw new \InvalidArgumentException("No input supplied");
		}

		// Bind the input
		$input = $args[0];
		$bound = $this->bindInputOn($lowerMethod, $input);

		$funcArgs = array_slice($args, 1);
		return $bound($funcArgs);
	}

	/**
	 * Partially applies the given method, binding the input parameter
	 * @param 	$methodName
	 * @param 	$input
	 *
	 * @return 	callable
	 */
	public function bindInputOn($methodName, $input)
	{
		return function($args) use($methodName, $input){
			$numArgs = count($args);
			switch($numArgs)
			{
				case 0:
					return $this->$methodName($input);
					break;

				case 1:
					return $this->$methodName($input, $args[0]);
					break;

				case 2:
					return $this->$methodName($input, $args[0], $args[1]);
					break;

				case 3:
					return $this->$methodName($input, $args[0], $args[1], $args[2]);
					break;

				case 4:
					return $this->$methodName($input, $args[0], $args[1], $args[2], $args[3]);
					break;

				case 5:
					return $this->$methodName($input, $args[0], $args[1], $args[2], $args[3], $args[4]);
					break;

				default:
					$combinedArgs = array_merge(array($input), $args);
					return call_user_func_array(array($this, $methodName), $combinedArgs);
					break;
			}
		};
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
	 * Wraps an input in a plinqWrapper
	 * @param 	array 	$input
	 *
	 * @return 	plinqArrayWrapper
	 */
	public static function on(Array $input)
	{
		$p = new plinq();
		return new plinqWrapper($p, $input);
	}

	/**
	 * Alias of on
	 * @param 	array 	$input
	 *
	 * @return 	plinqArrayWrapper
	 */
	public static function with(Array $input)
	{
		return plinq::on($input);
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
		return array_filter($input, $expr);
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
			$filteredInput = array_filter($input, $expr);
			return count($filteredInput);
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

		foreach($input as $k => $v){
			$r = $expr($k, $v);
			$results[] = $r;
		}

		return $results;
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

		foreach($input as $k => $v)
		{
			$acc = $expr($acc, $k, $v);
		}

		return $acc;
	}

	/**
	 * Returns the value of the first key in the input
	 * @param 	array 	$input
	 *
	 * @return 	array	Returns empty array if the array is empty
	 */
	public function head(Array $input, $retainKey = false)
	{
		if(empty($input))
		{
			return array();
		}

		if(!$retainKey)
		{
			$keys = array_keys($input);
			return array($input[$keys[0]]);
		}
		else
		{
			$keys = array_keys($input);
			$firstKey = $keys[0];

			$value = $input[$firstKey];

			return array($firstKey => $value);
		}

	}

	/**
	 * Removes the first element of the input and returns the rest
	 * @param 	array 	$input
	 *
	 * @return 	array	Returns an empty array if the input contains 0 or 1 elements;
	 */
	public function tail(Array $input)
	{
		if(empty($input))
		{
			return array();
		}

		return array_slice($input, 1);
	}

	/**
	 * Finds the largest value in the input
	 * @param 	array    	$input
	 * @param 	callable 	$comparator Comparison callback - callback($currentMax, $compareTo):bool - should return true if $compareTo > $currentMax
	 *
	 * @return	mixed	Null on empty input, largest value otherwise
	 * @throws 	\InvalidArgumentException	Thrown if the input contains anything other than numbers, but provides no comparator function
	 */
	public function max(Array $input, callable $comparator = null)
	{
		if(empty($input))
		{
			return null;
		}

		$isCallable = is_callable($comparator);
		$useComparator = false;
		if(!self::arrayAllNumbers($input))
		{
			if(!$isCallable)
			{
				throw new \InvalidArgumentException("Input contains values other than numbers, but no comparator provided to compare them");
			}
			else
			{
				$useComparator = true;
			}
		}

		$max = $input[0];
		for($i = 1; $i <= count($input)-1; $i++)
		{
			$j = $input[$i];
			if($useComparator)
			{
				if($comparator($max, $j)){
					$max = $j;
				}
			}
			else
			{
				if($j > $max)
				{
					$max = $j;
				}
			}
		}

		return $max;
	}

	/**
	 * Finds the smallest value in the input
	 * @param 	array    	$input
	 * @param 	callable 	$comparator Comparison callback - callback($currentMin, $compareTo):bool - should return true if $compareTo < $currentMin
	 *
	 * @return	mixed	Null on empty input, smallest value otherwise
	 * @throws 	\InvalidArgumentException	Thrown if the input contains anything other than numbers, but provides no comparator function
	 */
	public function min(Array $input, callable $comparator = null)
	{
		if(empty($input))
		{
			return null;
		}

		$isCallable = is_callable($comparator);
		$useComparator = false;
		if(!self::arrayAllNumbers($input))
		{
			if(!$isCallable)
			{
				throw new \InvalidArgumentException("Input contains values other than numbers, but no comparator provided to compare them");
			}
			else
			{
				$useComparator = true;
			}
		}

		$min = $input[0];
		for($i = 1; $i <= count($input)-1; $i++)
		{
			$j = $input[$i];
			if($useComparator)
			{
				if($comparator($min, $j)){
					$min = $j;
				}
			}
			else
			{
				if($j < $min)
				{
					$min = $j;
				}
			}
		}

		return $min;
	}

	/**
	 * Finds if every element in the input matches the expression
	 * @param array    	$input	The input to test
	 * @param callable 	$expr	The expression which all elements are tested against
	 *
	 * @return bool		True if all elements match the expression
	 */
	public function all(Array $input, Callable $expr)
	{
		foreach($input as $k => $v)
		{
			if(!$expr($k, $v))
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * Finds if any element in the input matches the expression.
	 * If no expression is provided, finds if the input has any elements.
	 * @param array    	$input	The input to test
	 * @param callable 	$expr	The expression to test with
	 *
	 * @return bool	True if any element matches the expression, or true if the input is not empty
	 */
	public function any(Array $input, Callable $expr = null)
	{
		if(!is_callable($expr))
		{
			return !empty($input);
		}

		foreach($input as $k => $v)
		{
			if($expr($k, $v))
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * Finds the first element in the input matching the expression
	 * @param 	array    	$input		The input to test
	 * @param 	callable 	$expr		The expression to match against
	 * @param 	bool     	$retainKey	Whether to return the key with the result
	 *
	 * @return array|null
	 */
	public function first(Array $input, Callable $expr, $retainKey = false)
	{
		if(empty($input))
		{
			return null;
		}

		foreach($input as $k => $v)
		{
			if($expr($k, $v))
			{
				if($retainKey)
				{
					return array($k => $v);
				}
				else
				{
					return $v;
				}
			}
		}

		return null;
	}

	/**
	 * Finds all distinct elements in the input
	 * @param 	array 	$input
	 *
	 * @return 	array
	 */
	public function distinct(Array $input, Callable $expr = null)
	{
		$results = array();
		foreach($input as $k => $v)
		{
			$val = $v;
			if($expr != null){
				$val = $expr($k, $v);
			}

			if(!in_array($val, $results))
			{
				$results[] = $val;
			}
		}

		return $results;
	}

	private static function arrayAllNumbers(Array $input)
	{
		foreach($input as $k => $v)
		{
			if(gettype($v) != "integer"
			&& gettype($v) != "double")
			{
				return false;
			}
		}

		return true;
	}
}