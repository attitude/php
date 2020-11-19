<?php

/**
 * Find all the positions of the occurrence of a substring in a string
 *
 * @param string $haystack The string to search in
 * @param string $needle If needle is not a string, it is converted to an integer and applied as the ordinal value of a character
 * @return array
 */
function allstrpos(string $haystack, $needle): array {
  $offset = 0;
  $positions = [];
  
  while (($position = strpos($haystack, $needle, $offset)) !== false) {
      $offset = $position + 1;
      $positions[] = $position;
  }
  
  return $positions;
}