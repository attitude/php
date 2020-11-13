<?php

define('ARRAY_COBMINATIONS_OF_ELEMENTS_NO_SORT', 0);
define('ARRAY_COBMINATIONS_OF_ELEMENTS_SORT_ASC', 1);
define('ARRAY_COBMINATIONS_OF_ELEMENTS_SORT_DESC', -1);

if (!defined('ARRAY_COBMINATIONS_OF_ELEMENTS_DEFAULT_SORT')) {
  define('ARRAY_COBMINATIONS_OF_ELEMENTS_DEFAULT_SORT', ARRAY_COBMINATIONS_OF_ELEMENTS_SORT_ASC);
}

/**
 * Generates arrays of possible combinations of elements
 *
 * Note that generated combinations is sorted only by length
 * not by its contents.
 *
 * Default is to sort generated combinations by length in ascending order.
 * Define ARRAY_COBMINATIONS_OF_ELEMENTS_DEFAULT_SORT constant to change
 * default behaviour.
 * 
 * Retuns `((n^2) - 1)` combinations of elements, where `n` is the lenght of
 * the original array of elelements.
 *
 * @param array $array Array of elements to generate combinations
 * @param integer $sort Flag how to sort the result
 * @return array[array]
 */
function array_combinations_of_elements(array $array, int $sort = ARRAY_COBMINATIONS_OF_ELEMENTS_DEFAULT_SORT): array {
  $results = [$array];

  $restCount = count($array);

  while ($restCount > 0) {
    $element = array_shift($array);
    $restCount = count($array);

    if ($restCount > 0) {
      $results[] = [$element];
    }

    // echo "Element: ${element}\n";
    // echo "Array: ".json_encode($array)."\n";
    // echo "Rest count: ${restCount}\n";

    if ($restCount > 1) {
      foreach ($array as $i => $elementLeft) {
        // echo "> Iterating: ${i}\n";
        $results[] = [$element, $elementLeft];
      }
    }

    if ($restCount > 1) {
      $results[] = $array;
    }
  }

  if ($sort === ARRAY_COBMINATIONS_OF_ELEMENTS_SORT_ASC) {
    usort($results, function ($a, $b) { return count($a) - count($b); });
  } elseif ($sort === ARRAY_COBMINATIONS_OF_ELEMENTS_SORT_DESC) {
    usort($results, function ($a, $b) { return count($b) - count($a); });
  }

  return $results;
}

/** /

// 2,9 GHz Dual-Core Intel Core i5
// 16 GB 2133 MHz LPDDR3

// BENCHMARKS, REPEATS: 9999, SET: 10 elements
// ---

// #1 array_combinations_of_elements_benchmark(NO SORT):
// Size of set: 1023
// Time took: 56 ms

// #2 array_combinations_of_elements_benchmark(ASC SORT):
// Size of set: 1023
// Time took: 246 ms

// #3 array_combinations_of_elements_v2_benchmark():
// Size of set: 1023
// Time took: 5057 ms


function int2bin(int $n): string {
  return str_pad(decbin($n), 3, '0', STR_PAD_LEFT);
}

// Using binary operations version
// Keeping here for study purposes
function array_combinations_of_elements_v2(array $array) {
  $length = count($array);

  $rows = [];
  $powers = [];

  for ($i = 0; $i < $length; $i++) {
    $powers[] = 2 ** $i;
  }

  $max = 2 ** $length;

  for ($i = 1; $i < $max; $i++) {
    $newRow = [];
    // $rows[$i - 1] = [];

    // foreach($array as $j => $value) {
    for ($j = 0; $j < $length; $j++) {
      $power = $powers[$j];
      //echo "\n$i: ".int2bin($i)." ".int2bin($power)." ".(($i & ($power)) > 0);

      if (($i & $power) > 0) {
        // $newRow[] = $value;
        $newRow[] = $array[$j];
      }
    }

    //echo "\n";

    $rows[] = $newRow;
  }

  return $rows;
}

function array_combinations_of_elements_benchmark(int $repeats = 9999, int $sort) {
  for($i = 0; $i < $repeats; $i++) {
    array_combinations_of_elements(ARRAY_ALL_POSSIBLE_COBMINATIONS_TEST_SET, $sort);
  }

  if (count(ARRAY_ALL_POSSIBLE_COBMINATIONS_TEST_SET) > 4) {
    echo "Size of set: ".count(array_combinations_of_elements_v2(ARRAY_ALL_POSSIBLE_COBMINATIONS_TEST_SET, $sort))."\n"; return;
  }

  echo json_encode(array_combinations_of_elements(ARRAY_ALL_POSSIBLE_COBMINATIONS_TEST_SET, $sort), JSON_PRETTY_PRINT);
}


function array_combinations_of_elements_v2_benchmark(int $repeats = 9999) {
  for($i = 0; $i < $repeats; $i++) {
    array_combinations_of_elements_v2(ARRAY_ALL_POSSIBLE_COBMINATIONS_TEST_SET);
  }

  if (count(ARRAY_ALL_POSSIBLE_COBMINATIONS_TEST_SET) > 4) {
    echo "Size of set: ".count(array_combinations_of_elements_v2(ARRAY_ALL_POSSIBLE_COBMINATIONS_TEST_SET))."\n"; return;
  }

  echo json_encode(array_combinations_of_elements_v2(ARRAY_ALL_POSSIBLE_COBMINATIONS_TEST_SET), JSON_PRETTY_PRINT)."\n";
}

$size = 10;
$testArray = [];
$repeats = 9999;

$start = -5;

for ($i = $start; $i < $start + $size; $i++) $testArray[] = $i;

define ('ARRAY_ALL_POSSIBLE_COBMINATIONS_TEST_SET', $testArray);

echo "BENCHMARKS, REPEATS: ${repeats}, SET: ${size} elements\n";
echo "---\n";

echo "\n#1 array_combinations_of_elements_benchmark(NO SORT): \n";
$start = microtime(true);
array_combinations_of_elements_benchmark($repeats, ARRAY_COBMINATIONS_OF_ELEMENTS_NO_SORT);
echo "Time took: ".round((microtime(true) - $start) * 1000)." ms\n";

echo "\n#2 array_combinations_of_elements_benchmark(ASC SORT): \n";
$start = microtime(true);
array_combinations_of_elements_benchmark($repeats, ARRAY_COBMINATIONS_OF_ELEMENTS_SORT_ASC);
echo "Time took: ".round((microtime(true) - $start) * 1000)." ms\n";

echo "\n#3 array_combinations_of_elements_v2_benchmark(): \n";
$start = microtime(true);
array_combinations_of_elements_v2_benchmark($repeats);
echo "Time took: ".round((microtime(true) - $start) * 1000)." ms\n";

/**/
