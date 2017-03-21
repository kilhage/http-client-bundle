# http-client-bundle

## Requirements

 - PHP 5.6 or above
 - [Guzzle PHP Framework][guzzle] (included by composer)
 - Symfony 2.7 or above (including Symfony 3.x)

## Installation
To install this bundle, run the command below and you will get the latest version by [Packagist][packagist].

``` bash
composer require glooby/http-client-bundle dev-master
```

To use the newest (maybe unstable) version please add following into your composer.json:

``` json
{
    "require": {
        "glooby/http-client-bundle": "dev-master"
    }
}
```

## Usage

Load bundle in AppKernel.php:
``` php
new Glooby\HttpClientBundle\GloobyHttpClientBundle(),
```

## License

This bundle is released under the [MIT license](Resources/meta/LICENSE)
