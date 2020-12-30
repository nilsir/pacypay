<?php

/*
 * This file is part of the nilsir/pacypay.
 *
 * (c) nilsir <nilsir@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Nilsir\Pacypay\Foundation\ServiceProviders;

use Nilsir\Pacypay\Signature\Signature;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class SignatureProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['signature'] = function () {
            return new Signature();
        };
    }
}
