<?php
define('ABSPATH', '/');
$dir = $argv[1]; $out = $argv[2];
foreach (glob($dir . '/*.php') as $f) {
  $d = include $f;
  file_put_contents($out . '/' . basename($f, '.php') . '.html', $d['code']);
}
echo count(glob($out.'/*.html')), "\n";
