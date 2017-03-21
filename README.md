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

    public function registerBundles()
    {
        $bundles = [
            ...
            new Glooby\HttpClientBundle\GloobyHttpClientBundle(),
            ...
        ];
    }
```

Configure in app/config/parameters.yml:

#### single
``` yaml
parameters:
...
    glooby.http.proxy: socks5://127.0.0.1:1080

```

#### file
``` yaml
parameters:
...
    glooby.http.proxy: /etc/proxyfile

```

    $ cat /etc/proxy

    socks5://127.0.0.1:1080

Supports single/multiple lines

#### multiple
``` yaml
parameters:
...
    glooby.http.proxy:
        - http://127.0.0.1:1081
        - socks5://127.0.0.1:1080
        - socks4://127.0.0.1:1082

```

#### mix
``` yaml
parameters:
...
    glooby.http.proxy:
        - socks5://127.0.0.1:1080
        - socks5://john:doe@127.0.0.1:1081
        - /etc/proxy1
        - /etc/proxy2

```

When providing multiple proxies a random one will be select each time creating a new client

## Support

### Supported proxy types

 * http
 * https
 * socks4
 * socks4a
 * socks5
 * socks5h

## License

This bundle is released under the [MIT license](Resources/meta/LICENSE)
