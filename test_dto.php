#!/usr/bin/env php
<?php

require('./vendor/autoload.php');
require('./DtoTester/Autoloader.php');
\DtoTester\Autoloader::register();
Doctrine\Common\Annotations\AnnotationRegistry::registerLoader('class_exists');

if (count($argv) < 2) {
    echo "Please provide the DTO class name\n";
    exit(2);
}

$dtoClassName = $argv[1];
$jsonString = file_get_contents("php://stdin");

$testInstance = new \DtoTester\TestUnit($dtoClassName, $jsonString);

// return the response for evaluation purposes
$testData = $testInstance->test(true);

print_r(
	json_encode(
		json_decode($testData, true),
		JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES
	)
);
