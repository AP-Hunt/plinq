plinq (PHP >= 5.4.0)
=====

A php library for working with arrays, inspired by .NET's LINQ. Currently supports a small set of actions, but will be expanded. Also supports *lazy evaluation*.

### Available methods

* **on/with**     -   Starts a chain of actions

* **where**       -   Filters the input through the provided expression
* **count**       -   Counts the number of elements in the input (optionally those matching an expression)
* **select**      -   Transforms each element of the input
* **aggregate**   -   Applies an accumulator function over each element in the input, returning the final accumulator value
* **head**        -   Returns the first element in the input (optionally returns the key value pair)
* **tail**        -   Returns all but the first element in the input

### Example

    include("plinq.php");
    use plinq\plinq;
    
    $numIsEven = function($k, $v)
    {
        return ($v % ")==0;
    };

    $doubleNum = function($k, $v)
    {
        return $v * 2;
    }

    $input = [1, 2, 3, 4, 5, 6];
    
    //plinq can perform chains of actions
    $filtered = plinq::with($input)
                     ->where($numIsEven)
                     ->select($doubleNum);
                     
    //Or a single action
    $numOfElements = plinq::count($input);
                     
The result of a plinq chain is a **plinqWrapper** object. This can be manipulated like an array but, crucially, will not be recognised as one by functions hinting an array. To get around this, either cast the result to an array or call toArray.
    
    $castArray = (array)$filtered;
    $methodArray = $filtered->toArray();

Until a result is converted to an array, you can still call plinq methods on it.

    $newFilter = plinq::with($input)
                      ->where($numIsEven);
                      
    print "The first even number is ".$newFilter[0];
    print "The double of the last number is " + $newFilter->select($doubleNum)[2];
    
### Lazy evaluation
Using lazy evaluation with Plinq is done almost precisely like normal. The only difference is there's no access to the resulting value until exec has been called, returning an array (not an ArrayObject like standard plinq).

    include("lazyPlinq.php");
    use plinq\lazyPlinq;
    
    $numIsEven = function($k, $v)
    {
        return ($v % ")==0;
    };

    $doubleNum = function($k, $v)
    {
        return $v * 2;
    }

    $input = [1, 2, 3, 4, 5, 6];

    $filtered = lazyPlinq::with($input)
                         ->where($numIsEven)
                         ->select($doubleNum)
                         ->exec();
