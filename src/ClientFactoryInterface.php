<?php

namespace Glooby\HttpClientBundle;

use Glooby\HttpClientBundle\Proxy\Proxy;

/**
 * @author Emil Kilhage
 */
interface ClientFactoryInterface
{
    /**
     * @param int $timeout
     */
    public function setTimeout($timeout);

    /**
     * @param Proxy $proxy
     */
    public function setProxy(Proxy $proxy);
}
