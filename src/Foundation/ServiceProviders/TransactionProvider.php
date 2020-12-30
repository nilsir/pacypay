<?php

/*
 * This file is part of the nilsir/pacypay.
 *
 * (c) nilsir <nilsir@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Nilsir\Pacypay\Foundation\ServiceProviders;

use Nilsir\Pacypay\Transaction\Transaction;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class TransactionProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['transaction'] = function ($pimple) {
            return new Transaction($pimple);
        };
    }
}
