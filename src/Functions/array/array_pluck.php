<?php

function array_pluck(array &$array, $paths = null) {
  if (empty($paths)) {
    return $array;
  }

  assert(is_string($paths) || is_array($paths));

  $new = [];

  foreach ((array) $paths as $path) {
    assert(is_string($path) || is_array($path));
    $path = is_string($path) ? array_filter(explode('.', $path)) : $path;

    $key = array_shift($path);

    if (array_key_exists($key, $array)) {
      $value =& $array[$key];

      if (count($path) > 0) {
          try {
                array_pluck($value, $path);
          } catch (\Throwable $e) {
              throw new \Exception("Unable to pluck path ".implode('.', $path)." from array", 400, $e);
          }
      }

      $new[$key] = $value;
    }
  }

  return $new;
}