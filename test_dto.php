#!/usr/bin/env php
<?php

require('./vendor/autoload.php');
require('./src/Psr2Autoloader.php');
helvete\Tools\Psr2Autoloader::register();
Doctrine\Common\Annotations\AnnotationRegistry::registerLoader('class_exists');

if (count($argv) < 2) {
    echo "Please provide the DTO class name\n";
    exit(2);
}

$dtoClassName = $argv[1];
$jsonString = file_get_contents("php://stdin");

$testInstance = new helvete\Tools\DtoTester($dtoClassName, $jsonString);

// return the response for evaluation purposes
$testData = $testInstance->test(true);

echo $testInstance::prettyPrint($testData);

echo PHP_EOL;
