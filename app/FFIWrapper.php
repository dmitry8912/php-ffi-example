<?php

class FFIWrapper
{
    private static $_cLibPath = "./clib2.so";
    private static $_goLibPath = "./golib.so";

    public static function passValueAndReadResult($a, $b, $operation) {
        $ffi = \FFI::cdef("int arithmetical_operation(int left_operand, int right_operand, char operation);",
            self::$_cLibPath);

        return $ffi->arithmetical_operation($a, $b, $operation);
    }

    public static function getFreeSpace($path) {
        $ffi = \FFI::cdef("double get_free_space(const char* path);", self::$_cLibPath);

        return $ffi->get_free_space($path);
    }

    public static function arraySizeDifferences($arrayLength = 10) {
        $phpArray = [];

        for($i=0; $i<$arrayLength; $i++)
        {
            $phpArray[$i] = $i;
        }

        $currentMemoryUsage = memory_get_usage();
        $tmp = unserialize(serialize($phpArray));
        $usage = memory_get_usage() - $currentMemoryUsage;
        echo "PHP Array memory usage: {$usage}".PHP_EOL;

        $currentMemoryUsage = memory_get_usage();
        $cArray = FFI::new("int[{$arrayLength}]");
        for ($i = 0; $i < $arrayLength; $i++) {
            $cArray[$i] = $i;
        }
        $usage = memory_get_usage() - $currentMemoryUsage;
        echo "C Array memory usage: {$usage}".PHP_EOL;
    }

    public static function arrayInitTime($arrayLength = 10) {
        $phpArray = [];

        $currentMemoryUsage = microtime(true);
        for($i=0; $i<$arrayLength; $i++)
        {
            $phpArray[$i] = $i;
        }
        $usage = microtime(true) - $currentMemoryUsage;
        echo "PHP Array access time: {$usage}".PHP_EOL;


        $a = FFI::new("int[{$arrayLength}]");
        $currentMemoryUsage = microtime(true);
        for ($i = 0; $i < $arrayLength; $i++) {
            $a[$i] = $i;
        }
        $usage = microtime(true) - $currentMemoryUsage;
        echo "C Array access time: {$usage}".PHP_EOL;
    }

    public static function arrayIncrementTime($arrayLength = 10) {
        $phpArray = [];
        for($i=0; $i<$arrayLength; $i++)
        {
            $phpArray[$i] = $i;
        }

        $currentMemoryUsage = microtime(true);
        for($i=0; $i<$arrayLength; $i++)
        {
            if($phpArray[$i] % 3 == 0)
            {
                $phpArray[$i] = 0;
            }
        }
        $usage = microtime(true) - $currentMemoryUsage;
        echo "PHP Array operational time: {$usage}".PHP_EOL;


        $cArray = FFI::new("int[{$arrayLength}]");
        for ($i = 0; $i < $arrayLength; $i++) {
            $cArray[$i] = $i;
        }
        $currentMemoryUsage = microtime(true);
        for ($i = 0; $i < $arrayLength; $i++) {
            if($cArray[$i] % 3 == 0)
            {
                $cArray[$i] = 0;
            }
        }
        $usage = microtime(true) - $currentMemoryUsage;
        echo "C Array operational time: {$usage}".PHP_EOL;
    }

    public static function makeAsyncUrlRequests($urls = []) {

        $ffi = FFI::cdef("char* getStatus(char* data);", self::$_goLibPath);
        return json_decode(
            FFI::string(
                $ffi->getStatus(
                    implode(',', $urls)
                )
            )
            , true);
    }
}