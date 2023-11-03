# AWS Bucket PHP

[![PRs Welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg?style=flat-square)](http://makeapullrequest.com)

PHP library to connect to and use AWS S3 Bucket.

### Installation

[Release 1.0.0](https://github.com/not-empty/aws-bucket-php-lib/releases/tag/1.0.0) Requires [PHP](https://php.net) 7.1

The recommended way to install is through [Composer](https://getcomposer.org/).

```sh
composer require not-empty/aws-bucket-php-lib
```

### Usage

Putting a file

```php
use AwsBucket\AwsBucket;
$config = [
   'credentials' => [
       'key' => '', //you must put your aws iam key here
       'secret' => '', //you must put your aws iam secret here
   ],
   'version' => 'latest',
   'region' => 'us-east-1',
];
$awsBucket = new AwsBucket($config);
$bucket = 'my-bucket';
$content = 'this is your file content';
$name = 'sample';
$extension = 'txt';
$putFile = $awsBucket->putFile(
    $bucket,
    $content,
    $name,
    $extension
);
var_dump($putFile);
```

Listing files

```php
$listFiles = $awsBucket->listFiles(
    $bucket
);
var_dump($listFiles);
```

If you getting 403 or 400 erros, you must configure your bucket permitions, in AWS console to allow the files uploads.

if you want an environment to run or test it, you can build and install dependences like this

```sh
docker build --build-arg PHP_VERSION=7.1.33-cli -t not-empty/aws-bucket-php-lib:php71 -f contrib/Dockerfile .
```

Access the container
```sh
docker run -v ${PWD}/:/var/www/html -it not-empty/aws-bucket-php-lib:php71 bash
```

Verify if all dependencies is installed
```sh
composer install --no-dev --prefer-dist
```

put your credentials, region and name of bucket in sample file sample/aws-bucket-sample.php
```php
...
$config = [
   'credentials' => [
       'key' => '', //you must put your aws iam key here
       'secret' => '', //you must put your aws iam secret here
   ],
   'version' => 'latest',
   'region' => 'us-east-1', //you must put your bucket region here
];
$bucket = 'my-bucket'; //you must put your bucket name here
```

and run
```sh
php sample/aws-bucket-sample.php
```

### Development

Want to contribute? Great!

The project using a simple code.
Make a change in your file and be careful with your updates!
**Any new code will only be accepted with all validations.**

To ensure that the entire project is fine:

First you need to building a correct environment to install all dependences

```sh
docker build --build-arg PHP_VERSION=7.1.33-cli -t not-empty/aws-bucket-php-lib:php71 -f contrib/Dockerfile .
```

Access the container
```sh
docker run -v ${PWD}/:/var/www/html -it not-empty/aws-bucket-php-lib:php71 bash
```

Install all dependences
```sh
composer install --dev --prefer-dist
```

Run all validations
```sh
composer check
```

**Not Empty Foundation - Free codes, full minds**
