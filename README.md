plinq
=====

A php library for working with arrays, inspired by .NET's LINQ. Currently supports a small set of actions, but will be expanded. Also supports *lazy evaluation*.

Example
==============
    $numIsEven = function($k, $v)
    {
        return ($v % ")==0;
    };

    $doubleNum = function($k, $v)
    {
        return $v * 2;
    }

    $input = [1, 2, 3, 4, 5, 6];

    $filtered = plinq::with($input)
                     ->where($numIsEven)
                     ->select($doubleNum);
                     ->toArray();
