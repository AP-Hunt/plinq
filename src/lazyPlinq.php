<?php
namespace plinq;

require_once("plinq.php");
require_once("lazyPlinqWrapper.php");

class lazyPlinq extends plinq {

	public static function thisOrNew($plinq)
	{
		if(!($plinq instanceof lazyPlinq))
		{
			return new lazyPlinq();
		}

		return $plinq;
	}

	/**
	 * Partially applies the given method, binding the args
	 * @param 	$methodName
	 * @param 	$args
	 *
	 * @return 	callable
	 */
	public function bindArgsOn($methodName, $args)
	{
		return function($input) use($methodName, $args){
			$p = lazyPlinq::thisOrNew($this);
			$numArgs =  count($args);
			switch($numArgs)
			{
				case 0:
					return $p->$methodName($input);
					break;

				case 1:
					return $p->$methodName($input, $args[0]);
					break;

				case 2:
					return $p->$methodName($input, $args[0], $args[1]);
					break;

				case 3:
					return $p->$methodName($input, $args[0], $args[1], $args[2]);
					break;

				case 4:
					return $p->$methodName($input, $args[0], $args[1], $args[2], $args[3]);
					break;

				case 5:
					return $p->$methodName($input, $args[0], $args[1], $args[2], $args[3], $args[4]);
					break;

				default:
					$combinedArgs = array_merge(array($input), $args);
					return call_user_func_array(array($p, $methodName), $combinedArgs);
					break;
			}
		};
	}

	public function on(array $input)
	{
		$lp = lazyPlinq::thisOrNew($this);
		return new lazyPlinqWrapper($lp, $input);
	}
}