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
$string = file_get_contents("php://stdin");

$testInstance = new helvete\Tools\DtoConverter($dtoClassName, $string);

$testData = $testInstance->convert(
    helvete\Tools\DtoConverter::TYPE_XML,
    helvete\Tools\DtoConverter::TYPE_JSON
);

echo $testInstance::prettyPrint($testData);

echo PHP_EOL;
