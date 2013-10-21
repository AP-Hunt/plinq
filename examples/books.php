<?php

require_once("../src/plinq.php");
use plinq\plinq;

// Get the XML
$xmlString = file_get_contents("books.xml");
$xml = new SimpleXMLElement($xmlString);

// Get each book as an object
$books = $xml->xpath("/catalog/book");

// Find all the books with a price <= 20.00 and print the title and price
print "==== Low priced books ==== \r\n";
$lowPricesBooks = plinq::with($books)
					   ->where(function($v){ return $v->price <= 20;})
					   ->select(function($k, $v){
							return array("Title" => $v->title,
										 "Price" => $v->price);
						 })
					   ->toArray();

foreach($lowPricesBooks as $b)
{
	print $b["Title"]." costs ".$b["Price"]." \r\n";
}

print "\r\n";

// Count the number of Fantasy books
print "==== Number of fantasy books ==== \r\n";
$numFantasyBooks = plinq::with($books)
					    ->count(function($v){ return strtolower($v->genre) == "fantasy"; });

print "There are $numFantasyBooks fantasy books \r\n";

print "\r\n";

// Calculate the price of all romance books
print "==== The price of all romance books ==== \r\n";

$sumAggregate = function($acc, $k, $v){
	return $acc + floatval($v->price);
};
$priceOfRomanceBooks = plinq::with($books)
							->where(function($v){ return strtolower($v->genre) == "romance"; })
							->aggregate(0.0, $sumAggregate);

print "The price of all romance books is $priceOfRomanceBooks \r\n";

print "\r\n";

// Calculate the price of all genres of book
print "==== The price of each genre book ==== \r\n";

$allPrices = plinq::with($books)
				  // Get all distinct genres
				  ->distinct(function($k, $v){ return strtolower($v->genre);})
				  // Get the prices of each
				  ->aggregate(array(), function($acc, $k, $v) use($books, $sumAggregate)
				 	{
						if(!array_key_exists($v, $acc))
						{
							// Find the sum of the prices in this genre
							$acc[$v] = plinq::on($books)
											->where(function($b)use($v){ return strtolower($b->genre) == $v; })
											->aggregate(0.0, $sumAggregate);
						}
						return $acc;
					})
				  ->toArray();

foreach($allPrices as $g => $p)
{
	print "The price of all $g books is $p \r\n";
}

print "\r\n";
