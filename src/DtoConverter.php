<?php

namespace helvete\Tools;

use JMS\Serializer\Serializer;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;

class DtoConverter extends DtoTester
{
    const TYPE_XML = 'xml';

    protected $jsonData;
    protected $dtoClass;
    protected $jms;

    /**
     * Class construct
     *
     * @param  string   $dtoClassName
     * @param  string   $inputString
     */
    public function __construct($dtoClassName, $inputString)
    {
        if (empty($inputString)) {
            throw new \Exception('Invalid input string provided');
        }
        $this->jsonData = $inputString;
        if (!class_exists($dtoClassName)) {
            throw new \Exception('Unknown DTO class provided');
        }
        $this->dtoClass = $dtoClassName;
        $this->jms = SerializerBuilder::create()->build();
    }


    /**
     * Public conversion interface
     *
     * @param  string   $from
     * @param  string   $to
     * @return string
     */
    public function convert($from, $to)
    {
        if (array_diff([$from, $to], self::allowedFormats())) {
            throw new \InvalidArgumentException('Invalid conversion format');
        }

        #$fileName = preg_replace('~\\\~', '_', $this->dtoClass) . time();
        #file_put_contents("{$fileName}.json", self::prettyPrint($jsonData));
        return $this->internalConvert(...array_values(get_defined_vars()));
    }


    /**
     * Convert between formats
     *
     * @param  string   $from
     * @param  string   $to
     * @return string
     */
    protected function internalConvert($from, $to)
    {
        $objectRef = $this->jms->deserialize(
            $this->jsonData,
            $this->dtoClass,
            $from
        );

        $jsonData = $this->jms->serialize(
            $objectRef,
            $to,
            SerializationContext::create()->setSerializeNull(true)
        );

        return $jsonData;
    }


    static public function allowedFormats()
    {
        return [
            self::TYPE_XML,
            self::TYPE_JSON,
        ];
    }
}
