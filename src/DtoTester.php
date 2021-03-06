<?php

namespace helvete\Tools;

use JMS\Serializer\Serializer;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;

class DtoTester
{
    const TYPE_JSON = 'json';

    protected $jsonData;
    protected $dtoClass;
    protected $jms;

    /**
     * Class construct
     *
     * @param  string   $dtoClassName
     * @param  string   $jsonString
     */
    public function __construct($dtoClassName, $jsonString)
    {
        if (is_null(json_decode($jsonString))) {
            throw new \Exception('Invalid JSON string provided');
        }
        $this->jsonData = $jsonString;
        if (!class_exists($dtoClassName)) {
            throw new \Exception('Unknown DTO class provided');
        }
        $this->dtoClass = $dtoClassName;
        $this->jms = SerializerBuilder::create()->build();
    }


    /**
     * Test a DTO by deserializing and serializing it again and comparing the
     *  json payloads
     *
     * @param  bool $returnPayload
     * @return string|bool
     */
    public function test($returnPayload = false)
    {
        $testA = $this->unifyString($this->jsonData);
        $objectRef = $this->jms->deserialize(
            $this->jsonData,
            $this->dtoClass,
            self::TYPE_JSON
        );

        $jsonData = $this->jms->serialize(
            $objectRef,
            self::TYPE_JSON,
            SerializationContext::create()->setSerializeNull(true)
        );

        $testB = $this->unifyString($jsonData);
        $match = $testA === $testB;
        if (!$match) {
            $fileName = preg_replace('~\\\~', '_', $this->dtoClass) . time();
            foreach (['out_a' => $testA, 'out_b' => $testB] as $ext => $str) {
                file_put_contents("{$fileName}.{$ext}", self::prettyPrint($str));
            }
        }

        if ($returnPayload) {
            return $jsonData;
        }

        return $match;
    }


    /**
     * JSON decode to array and sort it
     *
     * @param  string   $json
     * @return string
     */
    protected function unifyString($json)
    {
        $multiDimArr = json_decode($json, true);
        static::rksort($multiDimArr);

        return json_encode($multiDimArr);
    }


    /**
     * Recursively walk through a multi dimensional array and sort it by keys
     *  at each level
     *
     * @param  array    $sortMePlease
     * @return array
     */
    static protected function rksort(array &$sortMePlease)
    {
        foreach ($sortMePlease as $k => &$value) {
            if (is_array($value)) {
                self::rksort($value);
            }
        }
        ksort($sortMePlease);
    }


    /**
     * Pretty print strings for easier diffing
     *
     * @param  string   $jsonString
     * @return string
     */
    static public function prettyPrint($jsonString)
    {
        ob_start();
        print_r(
            json_encode(
                json_decode($jsonString, true),
                JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES
            )
        );

        return ob_get_clean();
    }
}
