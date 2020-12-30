<?php

/*
 * This file is part of the nilsir/pacypay.
 *
 * (c) nilsir <nilsir@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Nilsir\Pacypay\Transaction;

use Nilsir\Pacypay\Core\AbstractAPI;

class Transaction extends AbstractAPI
{
    /**
     * 统一支付
     *
     * @param $data
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Nilsir\Pacypay\Exceptions\HttpException
     */
    public function payment($data)
    {
        return $this->http->post('payment', $this->withBaseData($data)->withSign());
    }
}
