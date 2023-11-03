<?php

require_once __DIR__ . '/../vendor/autoload.php';

use AwsBucket\AwsBucket;

$config = [
   'credentials' => [
       'key' => '', //you must put your aws iam key here
       'secret' => '', //you must put your aws iam secret here
   ],
   'version' => 'latest',
   'region' => 'us-east-2',
];

$awsBucket = new AwsBucket($config);

$bucket = 'not-empty-test-bucket';
$content = 'this is your file content';
$name = 'sample';
$extension = 'txt';

$putFile = $awsBucket->putFile(
    $bucket,
    $content,
    $name,
    $extension
);
print_r($putFile);
echo PHP_EOL;

sleep(3);

$listFiles = $awsBucket->listFiles(
    $bucket
);
print_r($listFiles);