<?php

namespace Glooby\HttpClientBundle\Proxy;

/**
 * @author Emil Kilhage
 */
class ProxyParser
{

    const VALID_PROXY_REGEX = '/^((socks4|socks4a|socks5|socks5h):\/\/){0,1}[a-z0-9\.\-\_]+(:[0-9]+){0,1}$/i';

    /**
     * @param string $string
     * @return Proxy
     */
    public function parse($string)
    {
        if (null !== $string) {
            $components = parse_url($string);

            $proxy = new Proxy();

            $proxy->setUrl($string);

            if (array_key_exists('scheme', $components)) {
                $proxy->setScheme($components['scheme']);
            }

            if (array_key_exists('host', $components)) {
                $proxy->setHost($components['host']);
            }

            if (array_key_exists('port', $components)) {
                $proxy->setPort($components['port']);
            }

            if (array_key_exists('user', $components)) {
                $proxy->setUser($components['user']);
            }

            if (array_key_exists('pass', $components)) {
                $proxy->setPassword($components['pass']);
            }

            return $proxy;
        }

        return null;
    }

    /**
     * Make sure that a specific proxy string is valid.
     *
     * @param string $proxy
     *
     * @return bool
     */
    public function validateProxy($proxy)
    {
        return !empty($proxy);
    }

    /**
     * @param string $file
     * @return Proxy|null
     */
    public function parseFile($file)
    {
        if (null !== $file) {
            // If the proxy file is set, we check if it exists, and if so read the proxy from it.
            if (file_exists($file)) {
                $proxy = trim(file_get_contents($file));

                return $this->parse($proxy);
            }
        }

        return null;
    }
}
