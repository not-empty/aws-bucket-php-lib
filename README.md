# AWS Bucket PHP

[![Latest Version](https://img.shields.io/github/v/release/kiwfy/aws-bucket-php.svg?style=flat-square)](https://github.com/kiwfy/aws-bucket-php/releases)
[![codecov](https://codecov.io/gh/kiwfy/aws-bucket-php/branch/master/graph/badge.svg)](https://codecov.io/gh/kiwfy/aws-bucket-php)
[![CI Build](https://img.shields.io/circleci/build/github/kiwfy/aws-bucket-php/master?label=CI%20Build&token=34d8b3820b7229d742897f0a6982ced5bf6a99c8)](https://github.com/kiwfy/aws-bucket-php)
[![Total Downloads](https://img.shields.io/packagist/dt/kiwfy/aws-bucket-php.svg?style=flat-square)](https://packagist.org/packages/kiwfy/aws-bucket-php)
[![PRs Welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg?style=flat-square)](http://makeapullrequest.com)

PHP library to connect to and use AWS S3 Bucket.

### Installation

[Release 2.0.0](https://github.com/kiwfy/aws-bucket-php/releases/tag/2.0.0) Requires [PHP](https://php.net) 8.1

[Release 1.2.1](https://github.com/kiwfy/aws-bucket-php/releases/tag/1.2.0) or earlier Requires [PHP](https://php.net) 7.1

The recommended way to install is through [Composer](https://getcomposer.org/).

```sh
composer require kiwfy/aws-bucket-php
```

### Sample

it's a good idea to look in the sample folder to understand how it works.

First you need to building a correct environment to install dependences

```sh
docker build -t kiwfy/aws-bucket-php -f contrib/Dockerfile .
```

Access the container
```sh
docker run -v ${PWD}/:/var/www/html -it kiwfy/aws-bucket-php bash
```

Verify if all dependencies is installed (if need anyelse)
```sh
composer install --no-dev --prefer-dist
```

and run
```sh
php sample/AwsBucketSample.php
```

### Development

Want to contribute? Great!

The project using a simple code.
Make a change in your file and be careful with your updates!
**Any new code will only be accepted with all validations.**

To ensure that the entire project is fine:

First you need to building a correct environment to install/update all dependences
```sh
docker build -t kiwfy/aws-bucket-php -f contrib/Dockerfile .
```

Access the container
```sh
docker run -v ${PWD}/:/var/www/html -it kiwfy/aws-bucket-php bash
```

Install all dependences
```sh
composer install --dev --prefer-dist
```

Run all validations
```sh
composer check
```
**Kiwfy - Open your code, open your mind!**
