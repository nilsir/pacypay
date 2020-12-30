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
    public function getSign(array $data, string $key)
    {
        $str = '';
        ksort($data);
        foreach ($data as $val) {
            $str .= $val;
        }
        $str .= $key;

        return hash('sha256', $str);
    }
}
