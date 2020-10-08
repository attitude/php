<?php

if (!function_exists('array_flatten')) {
  function array_flatten(array $array) {
    $return = array();

    array_walk_recursive($array, function($a) use (&$return) { $return[] = $a; });

    return $return;
  }
}
