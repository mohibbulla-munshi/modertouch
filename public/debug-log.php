<?php
$log = file_get_contents(__DIR__ . '/../storage/logs/laravel.log');
preg_match_all('/\[\d{4}-\d{2}-\d{2}.*?(?=\[\d{4}-\d{2}-\d{2}|\z)/s', $log, $m);
$last = end($m[0]);
// Show just the first 1000 chars (the exception message)
echo '<pre>' . htmlspecialchars(substr($last, 0, 1200)) . '</pre>';
