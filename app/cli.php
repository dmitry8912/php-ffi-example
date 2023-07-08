<?php
require_once 'FFIWrapper.php';

echo "C Array vs Pure PHP Array".PHP_EOL;
$arrayLength = 1000000;
FFIWrapper::arraySizeDifferences($arrayLength);
FFIWrapper::arrayInitTime($arrayLength);
FFIWrapper::arrayIncrementTime($arrayLength);

echo PHP_EOL.PHP_EOL;
echo "C Lib usage".PHP_EOL;
echo "Calculator result 4+4: " . FFIWrapper::passValueAndReadResult(4,4,'+');
echo PHP_EOL."Free space (bytes) at '/': " . FFIWrapper::getFreeSpace('/');

echo PHP_EOL.PHP_EOL;
echo "Go Lib usage (http requests)". PHP_EOL;

var_dump(FFIWrapper::makeAsyncUrlRequests(['https://google.com', 'https://yandex.ru']));
