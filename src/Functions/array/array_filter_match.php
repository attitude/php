<?php

require_once('array_keys_match.php');

/**
 * Filters elements of an array using a shell wildcard pattern/patterns
 *
 * Nested array elements can be excluded recursivelly using the `.`
 * path notation in the pattern, eg.: `some.path.to.match*`.
 *
 * Each path segment becomes it's own match pattern when comparing
 * nested array keys.
 *
 * The `!` on the first char position is treated as a negative match,
 * which is an optional addition to `fnmatch()` behaviour.
 *
 * See {@see array_keys_match()} for pattern details.
 *
 * @param array $array Array to filter
 * @param string|string[] $paths Wildcard patterns
 * @return void
 */
function array_filter_match(array $array, $paths = null) {
  if (empty($paths)) {
    return $array;
  }

  assert(is_string($paths) || is_array($paths));

  $new = [];

  foreach ((array) $paths as $path) {
    assert(is_string($path) || is_array($path));
    $path = is_string($path) ? array_filter(explode('.', $path)) : $path;

    $key = array_shift($path);
    $allowedKey = array_keys_match($key, $array);

    foreach ($allowedKey as $key) {
      if (array_key_exists($key, $array)) {
        $value =& $array[$key];

        if (count($path) > 0) {
          try {
            array_filter_match($value, $path);
          } catch (\Throwable $e) {
            throw new \Exception("Unable to pluck path ".implode('.', $path)." from array", 400, $e);
          }
        }

        $new[$key] = $value;
      }
    }
  }

  return $new;
}
