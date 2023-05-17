<?php
require(__DIR__ . "/vendor/autoload.php");

use Main\Hamming;

if($argv[1]) {
    $input = $argv[1];
} else {
    $input = "1001"; // Например
}

$encoded = Hamming::encode($input);
var_dump($encoded);
var_dump(Hamming::decode($encoded));