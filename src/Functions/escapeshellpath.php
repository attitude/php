<?php

if (!function_exists('escapeshellpath')) {
  function escapeshellpath(string $path): string {
    return str_replace(' ', '\\ ', $path);
  }
}
