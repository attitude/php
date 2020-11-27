<?php

/**
 * Performs array key comparison againts the given shell wildcard pattern
 *
 * - An optional prefix "!" which negates the pattern is an addition to fnmatch();
 * - An asterisk "*" matches anything.
 * - The character "?" matches any single character.
 * - The range notation, e.g. [a-zA-Z], can be used to match one of the characters in a range.
 *
 * The `!` on the first char position is treated as a negative match,
 * which is an optional addition to `fnmatch()` behaviour.
 *
 * {@see fnmatch()} for flags and for a more detailed description.
 *
 * @param string $pattern
 * @param array $haystack
 * @param int $flags
 * @return array Array of matching keys
 *
 */
function array_keys_match(string $pattern, array $haystack, int $flags = 0): array {
    if (strlen($pattern) === 0) {
        return [];
    }

    if (!preg_match('/[\*\?\!\[\]]/', $pattern)) {
        echo "Skipping: ";

        if (array_key_exists($pattern, $haystack)) {
            return [$pattern];
        }

        return [];
    }

    $matches = $pattern[0] !== '!';
    $pattern = $matches ? $pattern : substr($pattern, 1);

    return array_filter(array_keys($haystack), function(string $key) use ($pattern, $flags, $matches) {
        return fnmatch($pattern, $key, $flags) ? $matches : !$matches;
    });
}
