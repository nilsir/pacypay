<?php

/*
 * This file is part of the nilsir/pacypay.
 *
 * (c) nilsir <nilsir@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Nilsir\Pacypay\Signature;

use Nilsir\Pacypay\Core\AbstractAPI;

class Signature extends AbstractAPI
{
    public function getSign(array $data)
    {
        $str = '';
        ksort($data);
        foreach ($data as $val) {
            $str .= $val;
        }
        $str .= $this->container['config']['key'];

        return hash('sha256', $str);
    }
}
