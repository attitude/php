<?php

function array_except(array $array, $paths = null) {
  if (empty($paths)) {
    return $array;
  }

  assert(is_string($paths) || is_array($paths));

  foreach ((array) $paths as $path) {
    assert(is_string($path) || is_array($path));
    $path = is_string($path) ? array_filter(explode('.', $path)) : $path;

    $key = array_shift($path);

    if (array_key_exists($key, $array)) {
      $value =& $array[$key];

      if (count($path) > 0) {
        try {
          $array[$key] = array_except($value, $path);
        } catch (\Throwable $e) {
          throw new \Exception("Unable to exclude path ".implode('.', $path)." from array", 400, $e);
        }
      } else {
        unset($array[$key]);
      }
    }
  }

  return $array;
}
