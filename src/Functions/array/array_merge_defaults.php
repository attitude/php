<?php

define('ARRAY_MERGE_DEFAULTS_EMPTY_VALUES', 1);
define('ARRAY_MERGE_DEFAULTS_MISSING_KEYS', 2);
define(
  'ARRAY_MERGE_DEFAULTS_ALL',
  ARRAY_MERGE_DEFAULTS_EMPTY_VALUES | ARRAY_MERGE_DEFAULTS_MISSING_KEYS
);

/**
 * Merges current array with defaults array
 *
 * Flags:
 * - `ARRAY_MERGE_DEFAULTS_EMPTY_VALUES` — (default) replace all undefined/null on existing keys
 * - `ARRAY_MERGE_DEFAULTS_MISSING_KEYS` — merge the missing keys and their values in
 *
 * @param array $array
 * @param array $defautls
 * @param integer $flags Flag to pick behaviour
 * @return array
 */
function array_merge_defaults(array $array, array $defautls, int $flags = ARRAY_MERGE_DEFAULTS_ALL): array {
  if ($flags < 1 || $flags > 3) {
    $error = "Flag of int literal ${flags} has no effect";

    if (ini_get('assert.exception')) {
      throw new \Exception($error);
    }

    if (ini_get('assert.warning') && ini_get('display_errors')) {
      trigger_error($error);
    }

    return $array;
  }

  $all = $flags === ARRAY_MERGE_DEFAULTS_ALL;

  foreach ($array as $key => &$value) {
    // Current is array & there is a default & it is an array:
    if (is_array($value) && array_key_exists($key, $defautls) && is_array($defautls[$key])) {
      $value = array_merge_defaults($value, $defautls[$key], $flags);
    }
    // Maybe a null/undefined && there is default:
    elseif (!isset($value)) {
      if ($all || ARRAY_MERGE_DEFAULTS_ALL & $flags === ARRAY_MERGE_DEFAULTS_EMPTY_VALUES) {
        if (array_key_exists($key, $defautls)) {
          $value = $defautls[$key];
        }
      }
    }
  }

  if ($all || ARRAY_MERGE_DEFAULTS_ALL & $flags === ARRAY_MERGE_DEFAULTS_MISSING_KEYS) {
    foreach ($defautls as $key => &$value) {
      if (!array_key_exists($key, $array)) {
        $array[$key] = $value;
      }
    }
  }

  return $array;
}
