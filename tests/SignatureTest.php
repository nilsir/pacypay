<?php

class SignatureTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function can_get_a_valid_signature()
    {
        $app = new \Nilsir\Pacypay\Application([]);

        $data = [
            "merchantNo"=> "2510",
            "responseCode"=> 88,
            "uniqueId"=> "2510194114455957675",
            "transactionId"=> "10220002",
            "message"=> "Payment Success!!!",
            "currency"=> "USD",
            "amount"=> "1.00",
            "timestamp"=> 1554101161030,
            "transactionType"=> "Sale",
        ];

        $sign = $app->signature->getSign($data, '000000');

        $this->assertSame('4f234a79607ed94159d6a7736ca43d26c209ba899268206cdf71316e378496f5', $sign);
    }
}